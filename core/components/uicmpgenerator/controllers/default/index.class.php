<?php
/**
 * UI CMP generator
 *
 * Copyright 2015 by Prihod <prihod2004@gmail.com>
 *
 * @package uicmpgenerator
 */
/**
 * Loads the index page.
 *
 * @package uicmpgenerator
 * @subpackage controllers
 */

require_once dirname(dirname(dirname(__FILE__))) . '/index.class.php';

class UicmpgeneratorIndexManagerController extends UicmpgManagerController {
    public function getPageTitle() { return $this->modx->lexicon('uicmpgenerator'); }
    public function loadCustomCssJs() {
        $this->addJavascript($this->uicmpg->config['jsUrl'].'mgr/widgets/home.grid.js');
        $this->addJavascript($this->uicmpg->config['jsUrl'].'mgr/widgets/home.panel.js');
        $this->addLastJavascript($this->uicmpg->config['jsUrl'].'mgr/sections/home.js');
    }
    public function getTemplateFile() { return $this->uicmpg->config['templatesPath'].'home.tpl'; }
}