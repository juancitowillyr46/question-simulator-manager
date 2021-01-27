<?php
/**
 * UI CMP generator
 *
 * Copyright 2015 by Prihod <prihod2004@gmail.com>
 *
 * @package uicmpgenerator
 */
/**
 * @package uicmpgenerator
 * @subpackage controllers
 */
require_once dirname(__FILE__) . '/model/uicmpgenerator/uicmpg.class.php';
class IndexManagerController extends modExtraManagerController {
    public static function getDefaultController() { return 'index'; }
}

abstract class UicmpgManagerController extends modManagerController {
    /** @var Uicmpg $uicmpg */
    public $uicmpg;
    public function initialize() {
        $this->uicmpg = new Uicmpg($this->modx);
        $this->addCss($this->uicmpg->config['cssUrl'].'mgr/mgr.css');
        $this->addJavascript($this->uicmpg->config['jsUrl'].'mgr/uicmpg.js');
        $this->addHtml('<script type="text/javascript">
        Ext.onReady(function() {
            Uicmpg.config = '.$this->modx->toJSON($this->uicmpg->config).';
            Uicmpg.config.connector_url = "'.$this->uicmpg->config['connectorUrl'].'";
            Uicmpg.action = "'.(!empty($_REQUEST['a']) ? $_REQUEST['a'] : 0).'";
        });
        </script>');
    }
    public function process(array $scriptProperties = array()) {

    }
    public function getLanguageTopics() {
        return array('uicmpgenerator:default');
    }
    public function checkPermissions() { return true;}
}