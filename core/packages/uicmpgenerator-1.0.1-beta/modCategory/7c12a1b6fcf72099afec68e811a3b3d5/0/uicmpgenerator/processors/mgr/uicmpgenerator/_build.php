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

// 1 delete all files for testing:
function rmdir_files($dir) {
    $glob = glob( $dir . '*', GLOB_MARK );
    if (is_array($glob)) {
        foreach ($glob as $file) {
            if (is_dir($file)) {
                rmdir_files($file."/");
                if( is_dir($file) ) {
                    rmdir($file);
                }
            } else if( is_file($file) )  {
                unlink($file);
            }
        }
    }
    if (is_dir($dir) ){
        if ( rmdir( $dir ) ){
            return true;
        }
        return false;
    }
} 
 
$directories = array(
    'assets' => MODX_ASSETS_PATH.'components/'.$package_name.'/',
    'code_base' => MODX_CORE_PATH.'components/'.$package_name.'/'
);
// Delete the directory folders:
if ( 1==2 ) {
    foreach ($directories as $folder) {
        if ( !file_exists($folder) ) {
            continue;
        } else {
            // delete the files and sub folders:
            rmdir_files($folder);
        }
    }
}
// create new files:
$directories = array(
    'assets' => MODX_ASSETS_PATH.'components/'.$package_name.'/',
        'assets_js' => MODX_ASSETS_PATH.'components/'.$package_name.'/js/',
            'assets_mgr' => MODX_ASSETS_PATH.'components/'.$package_name.'/js/mgr/',
                'assets_sections' => MODX_ASSETS_PATH.'components/'.$package_name.'/js/mgr/sections/',
                'assets_widgets' => MODX_ASSETS_PATH.'components/'.$package_name.'/js/mgr/widgets/',
    'code_base' => MODX_CORE_PATH.'components/'.$package_name.'/',
        'controllers' => MODX_CORE_PATH.'components/'.$package_name.'/controllers/',
            'controllers_mgr' => MODX_CORE_PATH.'components/'.$package_name.'/controllers/default/',
        'docs' => MODX_CORE_PATH.'components/'.$package_name.'/docs/',
        'elements' => MODX_CORE_PATH.'components/'.$package_name.'/elements/',
            'chunks' => MODX_CORE_PATH.'components/'.$package_name.'/elements/chunks/',
            'plugins' => MODX_CORE_PATH.'components/'.$package_name.'/elements/plugins/',
            'snippets' => MODX_CORE_PATH.'components/'.$package_name.'/elements/snippets/',
            'templates' => MODX_CORE_PATH.'components/'.$package_name.'/elements/templates/',
        'lexicon' => MODX_CORE_PATH.'components/'.$package_name.'/lexicon/',
            'en' => MODX_CORE_PATH.'components/'.$package_name.'/lexicon/en/',
        'model' => MODX_CORE_PATH.'components/'.$package_name.'/model/',
            'my_model' => MODX_CORE_PATH.'components/'.$package_name.'/model/'.$package_name.'/',
                'mysql' => MODX_CORE_PATH.'components/'.$package_name.'/model/'.$package_name.'/mysql',
            'request' => MODX_CORE_PATH.'components/'.$package_name.'/model/request/',
        'processors' => MODX_CORE_PATH.'components/'.$package_name.'/processors/',
            'processors_mgr' => MODX_CORE_PATH.'components/'.$package_name.'/processors/mgr/',
                // each table?
);

// now create directories if they do not exist
foreach ($directories as $folder) {
    if ( !file_exists($folder) ) {
        if ( !mkdir($folder) ) {
            return false;
        }
    }
    if ( !is_writable($folder) ) {
        return false;
    }
}

return $modx->error->success('',$cmp);
 