<?php
/**
 * UI CMP generator
 *
 * Copyright 2015 by Prihod <prihod2004@gmail.com>
 *
 * @package uicmpgenerator
 */
/**
 * Loads the build page.
 *
 * @package uicmpgenerator
 * @subpackage controllers
 */

require_once dirname(dirname(dirname(__FILE__))) . '/index.class.php';

class UicmpgeneratorBuildManagerController extends UicmpgManagerController {
    public function getPageTitle() { return $this->modx->lexicon('uicmpgenerator'); }
    public function loadCustomCssJs() {
        $packageId = (int)$_REQUEST['package'];
        if($packageId && $package = $this->uicmpg->getPackage($packageId)) {
            $manager = $this->modx->getManager();
            $loaded = include_once(MODX_CORE_PATH . 'components/uicmpgenerator/model/uicmpgenerator/' . $this->modx->config['dbtype'] . '/dbhelper.class.php');
            if ($loaded) {
                $generatorClass = 'Dbhelper_' . $this->modx->config['dbtype'];
                $generator = new $generatorClass ($manager);
                $dbname = $package->database;
                $generator->setDatabase($dbname);
                $table_prefix = $package->table_prefix;
                if ( !empty($dbname) && empty($table_prefix) ) {
                    $restrict_prefix = false;
                }

                $allClasses =  $generator->getAllClasses();
                $tables =  $generator->getAllTables('xPDOObject', $table_prefix, $restrict_prefix, $allClasses);

                $tables =  $tables ? $tables : array();
                $scheme = $package->scheme ? $package->scheme : '[]';

                $this->addJavascript($this->uicmpg->config['jsUrl'].'mgr/vendor/shifty.js');
                $this->addJavascript($this->uicmpg->config['jsUrl'].'mgr/vendor/raphael.js');
                $this->addJavascript($this->uicmpg->config['jsUrl'].'mgr/vendor/mustache.js');
                $this->addJavascript($this->uicmpg->config['jsUrl'].'mgr/vendor/rgbcolor.js');
                $this->addJavascript($this->uicmpg->config['jsUrl'].'mgr/vendor/canvg.js');
                $this->addJavascript($this->uicmpg->config['jsUrl'].'mgr/vendor/Class.js');
                $this->addJavascript($this->uicmpg->config['jsUrl'].'mgr/vendor/json2.js');

                $this->addJavascript($this->uicmpg->config['jsUrl'].'mgr/vendor/codemirror/codemirror.js');
                $this->addJavascript($this->uicmpg->config['jsUrl'].'mgr/vendor/codemirror/mode/xml/xml.js');
                $this->addJavascript($this->uicmpg->config['jsUrl'].'mgr/vendor/codemirror/addon/display/fullscreen.js');
                $this->addJavascript($this->uicmpg->config['jsUrl'].'mgr/vendor/codemirror/addon/selection/active-line.js');

                $this->addJavascript($this->uicmpg->config['jsUrl'].'mgr/vendor/jquery-1.10.2.min.js');
                $this->addJavascript($this->uicmpg->config['jsUrl'].'mgr/vendor/jquery-touch_punch.js');
                $this->addJavascript($this->uicmpg->config['jsUrl'].'mgr/vendor/pathfinding-browser.min.js');
                $this->addJavascript($this->uicmpg->config['jsUrl'].'mgr/vendor/draw2d/draw2d.js');
                $this->addJavascript($this->uicmpg->config['jsUrl'].'mgr/vendor/draw2d/CollapsibleLocator.js');
                $this->addJavascript($this->uicmpg->config['jsUrl'].'mgr/vendor/draw2d/CollapsibleInputLocator.js');
                $this->addJavascript($this->uicmpg->config['jsUrl'].'mgr/vendor/draw2d/CollapsibleOutputLocator.js');
                $this->addJavascript($this->uicmpg->config['jsUrl'].'mgr/vendor/draw2d/UiCMPConnection.js');
                $this->addJavascript($this->uicmpg->config['jsUrl'].'mgr/vendor/draw2d/UiCMPShape.js');
                $this->addJavascript($this->uicmpg->config['jsUrl'].'mgr/vendor/draw2d/UiCMPCanvas.js');
                $this->addJavascript($this->uicmpg->config['jsUrl'].'mgr/vendor/layout/jquery-latest.js');
                $this->addJavascript($this->uicmpg->config['jsUrl'].'mgr/vendor/layout/jquery-ui-latest.js');
                $this->addJavascript($this->uicmpg->config['jsUrl'].'mgr/vendor/layout/jquery.layout-latest.js');
                $this->addJavascript($this->uicmpg->config['jsUrl'].'mgr/vendor/contextmenu/jquery.contextmenu.js');
                $this->addJavascript($this->uicmpg->config['jsUrl'].'mgr/vendor/jquery.serializeObject.js');
                $this->addJavascript($this->uicmpg->config['jsUrl'].'mgr/vendor/jquery.tinysort.js');
                $this->addJavascript($this->uicmpg->config['jsUrl'].'mgr/vendor/jquery.autoresize.js');
                $this->addJavascript($this->uicmpg->config['jsUrl'].'mgr/vendor/UiCMPGenerator.js');


                $this->addJavascript($this->uicmpg->config['jsUrl'].'mgr/widgets/build.panel.js');
                $this->addJavascript($this->uicmpg->config['jsUrl'].'mgr/widgets/schema.view.js');
                $this->addLastJavascript($this->uicmpg->config['jsUrl'].'mgr/sections/build.js');

                $this->addCss($this->uicmpg->config['jsUrl'].'mgr/vendor/layout/layout-default-latest.css');
                $this->addCss($this->uicmpg->config['jsUrl'].'mgr/vendor/contextmenu/contextmenu.css');
                $this->addCss($this->uicmpg->config['jsUrl'].'mgr/vendor/codemirror/codemirror.css');
                $this->addCss($this->uicmpg->config['jsUrl'].'mgr/vendor/codemirror/theme/neo.css');
                $this->addCss($this->uicmpg->config['jsUrl'].'mgr/vendor/codemirror/addon/display/fullscreen.css');
                $this->addCss($this->uicmpg->config['cssUrl'].'mgr/mgr.css');


                $this->addHtml(file_get_contents($this->uicmpg->config['templatesPath'].'tpl_build.tpl'));
                $this->addHtml('<script type="text/javascript">
                    Ext.onReady(function() {
                        Uicmpg.tables = '.$this->modx->toJSON($tables).';
                        Uicmpg.scheme = '.$scheme.';
                        Uicmpg.packageId = '.$packageId.';
                    });
                </script>');
            }
        }
    }
    public function getTemplateFile() { return $this->uicmpg->config['templatesPath'].'build.tpl'; }
}