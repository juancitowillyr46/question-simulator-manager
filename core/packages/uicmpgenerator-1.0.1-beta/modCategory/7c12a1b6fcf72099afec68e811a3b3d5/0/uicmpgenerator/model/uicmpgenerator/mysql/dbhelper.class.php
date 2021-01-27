<?php

/**
 * Include the parent {@link xPDOGenerator} class.
 */
include_once(XPDO_CORE_PATH . 'om/' . $this->modx->config['dbtype'] . '/xpdogenerator.class.php');

class Dbhelper_mysql extends xPDOGenerator_mysql {
    /**
     * an array of allowed tables
     * @var array
     */
    protected $allowed_tables;
    protected $disallowed_tables;
    protected $extend_tables;
    protected $dependence_tables;

    /**
     * active data base to connect to
     * @var (String) $database
     */
    protected $databaseName;
    
    /**
     * set the database
     * 
     */
    public function setDatabase($database=NULL) {
        if (empty($database) ) {
            $this->databaseName = $this->manager->xpdo->escape($this->manager->xpdo->config['dbname']);
        } else {
            $this->databaseName = $database;
        }
    }
    
    /**
     * set the allowed tables
     * 
     */
    public function setAllowedTables(array $tables=array()) {
        $this->allowed_tables = $tables;
    }
    public function setExtendTables(array $tables=array()) {
        $this->extend_tables = $tables;
    }
    public function setDependenceTables(array $tables=array()) {
        $this->dependence_tables = $tables;
    }

    public function setDisallowedTables(array $tables=array()) {
        $tables[] = $this->manager->xpdo->config[xPDO::OPT_TABLE_PREFIX].'uicmpgenerator';
        $this->disallowed_tables = $tables;
    }

    public function searchSchemaFiles($folder, $mask='.mysql.schema.xml'){
        static $list = array();
        $dir = opendir($folder);
        while (($file = readdir($dir)) !== false){
            if($file != "." && $file != ".."){
                if(is_file($folder."/".$file)){
                    if(strripos($file, $mask) !== false){
                        $list[] = $folder."/".$file;
                    }
                }
                if(is_dir($folder."/".$file)) $this->searchSchemaFiles($folder."/".$file, $mask);
            }
        }
        closedir($dir);
        return $list;
    }
    public function getClasses($schemaFile=''){
        $schemaFile = $schemaFile ? $schemaFile : MODX_CORE_PATH .'model/schema/modx.mysql.schema.xml';
        $this->schema = new SimpleXMLElement($schemaFile, 0, true);
        $out = array();
        if (isset($this->schema->object)) {
            foreach ($this->schema->object as $object) {
                if($table = (string) $object['table']) {
                    $out[$table] = (string)$object['class'];
                }
            }
        }
        return $out;
    }
    public function getAllClasses(){
        $out = $this->getClasses();
        $componentsPath = MODX_CORE_PATH . 'components';
        if($schemaList = $this->searchSchemaFiles($componentsPath)) {
            foreach($schemaList as $file) {
                if($classes = $this->getClasses($file)){
                    $out =  array_merge($out,$classes);
                }
            }
        }
        return $out;
    }
    public function getAllTables($baseClass= '', $tablePrefix= '', $restrictPrefix= false, $classes=array()) {
        if(!$this->allowed_tables) $this->setDisallowedTables();
        if (empty ($baseClass))
            $baseClass= 'xPDOObject';
        if (empty ($tablePrefix))
            $tablePrefix= $this->manager->xpdo->config[xPDO::OPT_TABLE_PREFIX];
        $jsonTables = array();
        //read list of tables
        $dbname = $this->databaseName;
        //$this->manager->xpdo->log(xPDO::LOG_LEVEL_ERROR, 'Database name: ' . $dbname);
        $tableLike= ($tablePrefix && $restrictPrefix) ? " LIKE '{$tablePrefix}%'" : '';
        $tablesStmt= $this->manager->xpdo->prepare("SHOW TABLES FROM {$dbname}{$tableLike}");

        $tablesStmt->execute();
        $tables= $tablesStmt->fetchAll(PDO::FETCH_NUM);
        if ($this->manager->xpdo->getDebug() === true) {
            $this->manager->xpdo->log(xPDO::LOG_LEVEL_DEBUG, print_r($tables, true));
        }
        foreach ($tables as $table) {
            $jsonFields= array();
            // the only thing added to this function the rest is copied:
            if (in_array($table[0],$this->disallowed_tables) ) {
                //echo '<br>No Table: '.$table[0];
                //$this->manager->xpdo->log(xPDO::LOG_LEVEL_ERROR, 'CMPGenerator->my_oPDO0->writeTableSchema -> No Table: '.$table[0]);
                continue;
            }
            //echo '<br>Table: '. $table[0];
            //$this->manager->xpdo->log(xPDO::LOG_LEVEL_ERROR, 'CMPGenerator->my_oPDO0->writeTableSchema -> Table: '.$table[0].' - Pre: '.$tablePrefix.' - Restrict: '.$restrictPrefix );

            // End custom
            if (!$tableName= $this->getTableName($table[0], $tablePrefix, $restrictPrefix)) {
                continue;
            }
            $class= $this->getClassName($tableName);
            $extends= $baseClass;
            $sql = 'SHOW COLUMNS FROM '.$this->manager->xpdo->escape($dbname).'.'.$this->manager->xpdo->escape($table[0]);
            //$this->manager->xpdo->log(xPDO::LOG_LEVEL_ERROR, 'Line: '.__LINE__.' Sql: '.$sql);
            $fieldsStmt= $this->manager->xpdo->query($sql);
            if ($fieldsStmt) {
                $fields= $fieldsStmt->fetchAll(PDO::FETCH_ASSOC);
                if ($this->manager->xpdo->getDebug() === true) $this->manager->xpdo->log(xPDO::LOG_LEVEL_DEBUG, print_r($fields, true));
                if (!empty($fields)) {
                    foreach ($fields as $field) {
                        $Field= '';
                        $Type= '';
                        $Null= '';
                        $Key= '';
                        $Default= '';
                        $Extra= '';
                        extract($field, EXTR_OVERWRITE);
                        $Type= xPDO :: escSplit(' ', $Type, "'", 2);
                        $precisionPos= strpos($Type[0], '(');
                        $dbType= $precisionPos? substr($Type[0], 0, $precisionPos): $Type[0];
                        $dbType= strtolower($dbType);
                        $Precision= $precisionPos? substr($Type[0], $precisionPos + 1, strrpos($Type[0], ')') - ($precisionPos + 1)): '';
                        if (!empty ($Precision)) {
                            $Precision= trim($Precision);
                        }
                        $attributes= '';
                        if (isset ($Type[1]) && !empty ($Type[1]) ) {
                            $attributes= trim($Type[1]);
                        }
                        $Null= ' null="' . (($Null === 'NO') ? 'false' : 'true') . '"';
                        $Key= $this->getIndex($Key);
                        $Default= $this->getDefault($Default);
                        $primaryKey = false;
                        if (!empty ($Extra)) {
                            if ($Extra === 'auto_increment') {
                                $primaryKey = true;
                                if ($baseClass === 'xPDOObject' && $Field === 'id') {
                                    $extends= 'xPDOSimpleObject';
                                    //  continue;
                                } else {
                                    $Extra= ' generated="native"';
                                }
                            } else {
                                $Extra= ' extra="' . strtolower($Extra) . '"';
                            }
                            $Extra= ' ' . $Extra;
                        }
                        $type = !$Precision ? $dbType : $dbType . '('.$Precision.')';
                        $jsonFields[$Field] =  array('field'=>$Field, 'type'=>$type,'key'=>$primaryKey);
                    }
                } else {
                    $this->manager->xpdo->log(xPDO::LOG_LEVEL_ERROR, 'No columns were found in table ' .  $table[0]);
                }
            } else {
                $this->manager->xpdo->log(xPDO::LOG_LEVEL_ERROR, 'Error retrieving columns for table ' .  $table[0]);
            }

            $jsonTables[$tableName] = array('fields'=>$jsonFields, 'cls'=>$classes[$tableName] ? $classes[$tableName] : '');

        }
        if ($this->manager->xpdo->getDebug() === true) {
            $this->manager->xpdo->log(xPDO::LOG_LEVEL_DEBUG, print_r($jsonTables,1));
        }
        return $jsonTables;
    }

    public function parseJsonSchema($jsonData,$tablePrefix= '') {
        if (empty ($tablePrefix))
            $tablePrefix= $this->manager->xpdo->config[xPDO::OPT_TABLE_PREFIX];

        $tables = array();
        $extendTables = array();
        $dependence = array();
        if($jsonData) {
            if($data = json_decode($jsonData)){
                foreach($data as $item){
                    if($item->type == 'UiCMPShape') {
                        if(!$item->extendCls) {
                            $table = $tablePrefix . $item->id;
                            $tables[$table] = $table;
                        } else {
                            $extendTables[$item->id] =  $item->extendCls;
                        }
                    } else if($item->type == 'UiCMPConnection'){
                        $dependence[$item->source->node][] = $this->renderDependence($item);
                        $dependence[$item->target->node][] = $this->renderDependence($item, false);
                    }
                }
            }
        }
        $this->setExtendTables($extendTables);
        $this->setAllowedTables($tables);
        $this->setDependenceTables($dependence);
    }
    private function removePortPrefix($str) {
        return preg_replace("/^(input_|output_)/",'',$str);
    }
    private function renderDependence($d, $isSource = true){
        if($isSource) {
            $dependence = $d->uicmpg->dependence == 1 ? 'aggregate': 'composite';
            $cardinality =  $d->uicmpg->relation == 1 ? 'one' :'many';
            $alias = $d->uicmpg->alias_source;
            $class = $d->target->cls ? $d->target->cls :$this->getClassName($d->target->node);
            $local =  $this->removePortPrefix($d->source->port);
            $foreign = $this->removePortPrefix($d->target->port);
            $owner = 'local';
        } else {
            $dependence = 'aggregate';
            $alias = $d->uicmpg->alias_target;
            $class = $d->source->cls ? $d->source->cls : $this->getClassName($d->source->node);
            $local = $this->removePortPrefix($d->target->port);
            $foreign = $this->removePortPrefix($d->source->port);
            $cardinality = 'one';
            $owner = 'foreign';
        }
        return "\t\t<{$dependence} alias=\"{$alias}\" class=\"{$class}\" local=\"{$local}\" foreign=\"{$foreign}\" cardinality=\"{$cardinality}\" owner=\"{$owner}\" />";
    }
    public function generateSchema($schemaFile, $package= '', $baseClass= '', $tablePrefix= '', $restrictPrefix= false){
        if (empty ($package))
            $package= $this->manager->xpdo->package;
        if (empty ($baseClass))
            $baseClass= 'xPDOObject';
        if (empty ($tablePrefix))
            $tablePrefix= $this->manager->xpdo->config[xPDO::OPT_TABLE_PREFIX];
        $schemaVersion = xPDO::SCHEMA_VERSION;
        $xmlContent = array();
        $xmlContent[] = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
        $xmlContent[] = "<model package=\"{$package}\" baseClass=\"{$baseClass}\" platform=\"mysql\" defaultEngine=\"MyISAM\" version=\"{$schemaVersion}\">";
        //read list of tables
        $dbname = $this->databaseName;
        $tableLike= ($tablePrefix && $restrictPrefix) ? " LIKE '{$tablePrefix}%'" : '';
        $tablesStmt= $this->manager->xpdo->prepare("SHOW TABLES FROM {$dbname}{$tableLike}");
        $tablesStmt->execute();
        $tables= $tablesStmt->fetchAll(PDO::FETCH_NUM);
        if ($this->manager->xpdo->getDebug() === true) {
            $this->manager->xpdo->log(xPDO::LOG_LEVEL_DEBUG, print_r($tables, true));
        }
        foreach ($tables as $table) {
            $xmlObject= array();
            $xmlFields= array();
            $xmlIndices= array();
            // the only thing added to this function the rest is copied:
            if ( !in_array($table[0],$this->allowed_tables) ) {
                //echo '<br>No Table: '.$table[0];
                //$this->manager->xpdo->log(xPDO::LOG_LEVEL_ERROR, 'CMPGenerator->my_oPDO0->writeTableSchema -> No Table: '.$table[0]);
                continue;
            }
            //echo '<br>Table: '. $table[0];
            //$this->manager->xpdo->log(xPDO::LOG_LEVEL_ERROR, 'CMPGenerator->my_oPDO0->writeTableSchema -> Table: '.$table[0].' - Pre: '.$tablePrefix.' - Restrict: '.$restrictPrefix );

            // End custom
            if (!$tableName= $this->getTableName($table[0], $tablePrefix, $restrictPrefix)) {
                continue;
            }
            $class= $this->getClassName($tableName);
            $extends= $baseClass;
            $sql = 'SHOW COLUMNS FROM '.$this->manager->xpdo->escape($dbname).'.'.$this->manager->xpdo->escape($table[0]);
            //$this->manager->xpdo->log(xPDO::LOG_LEVEL_ERROR, 'Line: '.__LINE__.' Sql: '.$sql);
            $fieldsStmt= $this->manager->xpdo->query($sql);
            if ($fieldsStmt) {
                $fields= $fieldsStmt->fetchAll(PDO::FETCH_ASSOC);
                if ($this->manager->xpdo->getDebug() === true) $this->manager->xpdo->log(xPDO::LOG_LEVEL_DEBUG, print_r($fields, true));
                if (!empty($fields)) {
                    foreach ($fields as $field) {
                        $Field= '';
                        $Type= '';
                        $Null= '';
                        $Key= '';
                        $Default= '';
                        $Extra= '';
                        extract($field, EXTR_OVERWRITE);
                        $Type= xPDO :: escSplit(' ', $Type, "'", 2);
                        $precisionPos= strpos($Type[0], '(');
                        $dbType= $precisionPos? substr($Type[0], 0, $precisionPos): $Type[0];
                        $dbType= strtolower($dbType);
                        $Precision= $precisionPos? substr($Type[0], $precisionPos + 1, strrpos($Type[0], ')') - ($precisionPos + 1)): '';
                        if (!empty ($Precision)) {
                            $Precision= ' precision="' . trim($Precision) . '"';
                        }
                        $attributes= '';
                        if (isset ($Type[1]) && !empty ($Type[1]) ) {
                            $attributes= ' attributes="' . trim($Type[1]) . '"';
                        }
                        $PhpType= $this->manager->xpdo->driver->getPhpType($dbType);
                        $Null= ' null="' . (($Null === 'NO') ? 'false' : 'true') . '"';
                        $Key= $this->getIndex($Key);
                        $Default= $this->getDefault($Default);
                        if (!empty ($Extra)) {
                            if ($Extra === 'auto_increment') {
                                if ($baseClass === 'xPDOObject' && $Field === 'id') {
                                    $extends= 'xPDOSimpleObject';
                                    continue;
                                } else {
                                    $Extra= ' generated="native"';
                                }
                            } else {
                                $Extra= ' extra="' . strtolower($Extra) . '"';
                            }
                            $Extra= ' ' . $Extra;
                        }
                        $xmlFields[] = "\t\t<field key=\"{$Field}\" dbtype=\"{$dbType}\"{$Precision}{$attributes} phptype=\"{$PhpType}\"{$Null}{$Default}{$Key}{$Extra} />";
                    }
                } else {
                    $this->manager->xpdo->log(xPDO::LOG_LEVEL_ERROR, 'No columns were found in table ' .  $table[0]);
                }
            } else {
                $this->manager->xpdo->log(xPDO::LOG_LEVEL_ERROR, 'Error retrieving columns for table ' .  $table[0]);
            }
            $whereClause= ($extends === 'xPDOSimpleObject' ? " WHERE `Key_name` != 'PRIMARY'" : '');
            $indexesStmt= $this->manager->xpdo->query('SHOW INDEXES FROM '.$this->manager->xpdo->escape($dbname).'.'.$this->manager->xpdo->escape($table[0]) . $whereClause);
            if ($indexesStmt) {
                $indexes= $indexesStmt->fetchAll(PDO::FETCH_ASSOC);
                if ($this->manager->xpdo->getDebug() === true) $this->manager->xpdo->log(xPDO::LOG_LEVEL_DEBUG, "Indices for table {$table[0]}: " . print_r($indexes, true));
                if (!empty($indexes)) {
                    $indices = array();
                    foreach ($indexes as $index) {
                        if (!array_key_exists($index['Key_name'], $indices)) $indices[$index['Key_name']] = array();
                        $indices[$index['Key_name']][$index['Seq_in_index']] = $index;
                    }
                    foreach ($indices as $index) {
                        $xmlIndexCols = array();
                        if ($this->manager->xpdo->getDebug() === true) $this->manager->xpdo->log(xPDO::LOG_LEVEL_DEBUG, "Details of index: " . print_r($index, true));
                        foreach ($index as $columnSeq => $column) {
                            if ($columnSeq == 1) {
                                $keyName = $column['Key_name'];
                                $primary = $keyName == 'PRIMARY' ? 'true' : 'false';
                                $unique = empty($column['Non_unique']) ? 'true' : 'false';
                                $packed = empty($column['Packed']) ? 'false' : 'true';
                                $type = $column['Index_type'];
                            }
                            $null = $column['Null'] == 'YES' ? 'true' : 'false';
                            $xmlIndexCols[]= "\t\t\t<column key=\"{$column['Column_name']}\" length=\"{$column['Sub_part']}\" collation=\"{$column['Collation']}\" null=\"{$null}\" />";
                        }
                        $xmlIndices[]= "\t\t<index alias=\"{$keyName}\" name=\"{$keyName}\" primary=\"{$primary}\" unique=\"{$unique}\" type=\"{$type}\" >";
                        $xmlIndices[]= implode("\n", $xmlIndexCols);
                        $xmlIndices[]= "\t\t</index>";
                    }
                } else {
                    $this->manager->xpdo->log(xPDO::LOG_LEVEL_WARN, 'No indexes were found in table ' .  $table[0]);
                }
            } else {
                $this->manager->xpdo->log(xPDO::LOG_LEVEL_ERROR, 'Error getting indexes for table ' .  $table[0]);
            }
            $xmlObject[] = "\t<object class=\"{$class}\" table=\"{$tableName}\" extends=\"{$extends}\">";
            $xmlObject[] = implode("\n", $xmlFields);
            if (!empty($xmlIndices)) {
                $xmlObject[] = '';
                $xmlObject[] = implode("\n", $xmlIndices);
            }
            if($this->dependence_tables[$tableName]) {
                $xmlObject[] = implode("\n",$this->dependence_tables[$tableName]);
            }
            $xmlObject[] = "\t</object>";
            $xmlContent[] = implode("\n", $xmlObject);
        }
        if($this->extend_tables) {
            foreach ($this->extend_tables as $key => $val) {
                $xmlObject[] = "\t<object class=\"{$key}\" extends=\"{$val}\">";
                if($this->dependence_tables[$key]) {
                    $xmlObject[] = implode("\n",$this->dependence_tables[$key]);
                }
                $xmlObject[] = "\t</object>";
                $xmlContent[] = implode("\n", $xmlObject);
            }
        }
        $xmlContent[] = "</model>";
        if ($this->manager->xpdo->getDebug() === true) {
            $this->manager->xpdo->log(xPDO::LOG_LEVEL_DEBUG, implode("\n", $xmlContent));
        }
        $file= fopen($schemaFile, 'wb');
        $xmlSchema = implode("\n", $xmlContent);
        $written = fwrite($file, $xmlSchema);
        fclose($file);
        return $xmlSchema;
    }

}
