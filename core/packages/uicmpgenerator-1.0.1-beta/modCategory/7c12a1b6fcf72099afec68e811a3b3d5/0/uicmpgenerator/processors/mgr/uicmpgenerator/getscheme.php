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
$dirModel =  MODX_CORE_PATH.'components/'.$cmp->package.'/model/'.$cmp->package.'/';
$xml_schema_file = $dirModel.$cmp->package.'.mysql.schema.xml';
$dbname = $cmp->get('database');
$generator->setDatabase($dbname);
$prefix = $cmp->get('table_prefix');
if ( !empty($dbname) && empty($prefix) ) {
    $restrict_prefix = false;
}
$generator->parseJsonSchema($cmp->scheme);
$xml = $generator->generateSchema($xml_schema_file, $cmp->package, 'xPDOObject', $table_prefix, $restrict_prefix);

return $modx->error->success($xml);


