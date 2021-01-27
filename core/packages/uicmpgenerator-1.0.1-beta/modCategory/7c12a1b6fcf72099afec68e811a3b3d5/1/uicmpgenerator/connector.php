<?php
/**
 * UI CMP Connector
 *
 * @package uicmpgenerator
 */
// set the correct package name
$package_name = 'uicmpgenerator';

require_once dirname(dirname(dirname(dirname(__FILE__)))).'/config.core.php';
require_once MODX_CORE_PATH.'config/'.MODX_CONFIG_KEY.'.inc.php';
require_once MODX_CONNECTORS_PATH.'index.php';

$modx->lexicon->load('owd:default');

$corePath = $modx->getOption('uicmpgenerator.core_path',null,$modx->getOption('core_path').'components/uicmpgenerator/');
require_once $corePath.'model/uicmpgenerator/uicmpg.class.php';

$modx->uicmpg = new Uicmpg($modx);

/* handle request */
$path = $modx->getOption('processorsPath',$modx->uicmpg->config,$corePath.'processors/');
$modx->request->handleRequest(array(
    'processors_path' => $path,
    'location' => '',
));