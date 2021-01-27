<?php
$modx->lexicon->load('uicmpgenerator:default');
// required fields
if (empty($scriptProperties['package']) ) {
    $modx->error->addField('package',$modx->lexicon('uicmpgenerator.err_required'));
}

/*if (empty($scriptProperties['database']) && empty($scriptProperties['table_prefix']) ) {
    $modx->error->addField('table_prefix',$modx->lexicon('uicmpgenerator.err_required'));
}*/

if(empty($scriptProperties['table_prefix']) ) {
    $scriptProperties['table_prefix'] = $modx->getOption(xPDO::OPT_TABLE_PREFIX);
}

if ($modx->error->hasError()) {
    return $modx->error->failure();
}

$package_name = $scriptProperties['package'];

if ( empty($scriptProperties['id']) || $scriptProperties['id'] < 1  ) {
    // create
    $cmp = $modx->newObject('Uicmpgenerator');
    $scriptProperties['create_date'] = strftime('%Y-%m-%d %H:%M:%S');
    
} else {
    // it exists
    $cmp = $modx->getObject('Uicmpgenerator',$scriptProperties['id']);
    unset($scriptProperties['create_date']);
    unset($scriptProperties['last_ran']);
    
}
//$scriptProperties['last_ran'] = strftime('%Y-%m-%d %H:%M:%S');

if (!is_object($cmp) ) {
    return $modx->error->failure('Not an object id: '.$scriptProperties['id'] );
}
$cmp->fromArray($scriptProperties);

/* save */
if ($cmp->save() == false) {
    return $modx->error->failure($modx->lexicon('uicmpgenerator.err_save').' ID: '.$scriptProperties['id']);
}

require_once MODX_CORE_PATH . 'components/uicmpgenerator/model/uicmpgenerator/uicmpg.class.php';
$uicmpg = new Uicmpg($this->modx);
$uicmpg->createPackageDirectories($package_name);


return $modx->error->success('',$cmp);
 