<?php
/**
 * @package uicmpgenerator
 * @subpackage processors
 */

$modx->lexicon->load('uicmpgenerator:default');

if ( empty($scriptProperties['id']) ) {
    return $modx->error->failure($modx->lexicon('uicmpgenerator.err_nf'));
}


$cmp = $modx->getObject('Uicmpgenerator',$scriptProperties['id']);
if (empty($cmp)){
    return $modx->error->failure($modx->lexicon('uicmpgenerator.err_nf'));
}

require_once MODX_CORE_PATH . 'components/uicmpgenerator/model/uicmpgenerator/uicmpg.class.php';
$uicmpg = new Uicmpg($this->modx);
$uicmpg->createPackageDirectories($cmp->package);

$manager = $modx->getManager();
$loaded = include_once(MODX_CORE_PATH . 'components/uicmpgenerator/model/uicmpgenerator/' . $modx->config['dbtype'] . '/dbhelper.class.php');
if ($loaded) {
    $generatorClass = 'Dbhelper_' . $modx->config['dbtype'];
    $generator = new $generatorClass ($manager);
    $dbname = $cmp->database;
    $generator->setDatabase($dbname);
    $table_prefix = $cmp->table_prefix;
    if (!empty($dbname) && empty($table_prefix)) {
        $restrict_prefix = false;
    }
}

$directories = $uicmpg->getPackageDirectories($cmp->package);
$xml_schema_file =  $directories['my_model'].$cmp->package.'.mysql.schema.xml';

$dbname = $cmp->get('database');
$generator->setDatabase($dbname);
$prefix = $cmp->get('table_prefix');
if ( !empty($dbname) && empty($prefix) ) {
    $restrict_prefix = false;
}
$generator->parseJsonSchema($cmp->scheme);
$xml = $generator->generateSchema($xml_schema_file, $cmp->package, 'xPDOObject', $table_prefix, $restrict_prefix);
if($cmp->scheme && $cmp->scheme != '[]') {
    $generator->parseSchema($xml_schema_file, $directories['model']);
    $cmp->set('last_ran',strftime('%Y-%m-%d %H:%M:%S'));
    $cmp->save();
}
return $modx->error->success('Ok');


