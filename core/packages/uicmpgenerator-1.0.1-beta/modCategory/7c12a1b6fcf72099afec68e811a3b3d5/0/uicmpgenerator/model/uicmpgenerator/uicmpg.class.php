<?php
/**
 * UI CMP generator
 *
 * Copyright 2015 by Prihod <prihod2004@gmail.com>
 *
 * @package uicmpgenerator
 */
/**
 * The base class for UI CMP generator.
 *
 * @package uicmpgenerator
 */
class Uicmpg {
    public $debugTimer;
    function __construct(modX &$modx,array $config = array()) {
        $this->modx =& $modx;

        $corePath = $this->modx->getOption('uicmpgenerator.core_path',$config,$this->modx->getOption('core_path').'components/uicmpgenerator/');
        $assetsPath = $this->modx->getOption('uicmpgenerator.assets_path',$config,$this->modx->getOption('assets_path').'components/uicmpgenerator/');
        $assetsUrl = $this->modx->getOption('uicmpgenerator.assets_url',$config,$this->modx->getOption('assets_url').'components/uicmpgenerator/');
        $connectorUrl = $assetsUrl.'connector.php';

        $this->config = array_merge(array(
            'assetsUrl' => $assetsUrl,
            'cssUrl' => $assetsUrl.'css/',
            'jsUrl' => $assetsUrl.'js/',
            'imagesUrl' => $assetsUrl.'images/',

            'connectorUrl' => $connectorUrl,

            'corePath' => $corePath,
            'modelPath' => $corePath.'model/',
            'chunksPath' => $corePath.'elements/chunks/',
            'processorsPath' => $corePath.'processors/',
            'templatesPath' => $corePath.'elements/templates/',
        ),$config);


        $this->modx->addPackage('uicmpgenerator',$this->config['modelPath']);
        if ($this->modx->getOption('uicmpgenerator.debug',$this->config,true)) {
            $this->startDebugTimer();
        }
    }

    /**
     * Initializes UI CMP generator into different contexts.
     *
     * @access public
     * @param string $ctx The context to load. Defaults to web.
     * @return string
     */
    public function initialize($ctx = 'web') {
        switch ($ctx) {
            case 'mgr':
                if (!$this->modx->loadClass('uicmpgenerator.request.GalControllerRequest',$this->config['modelPath'],true,true)) {
                    return 'Could not load controller request handler.';
                }
                $this->request = new GalControllerRequest($this);
                return $this->request->handleRequest();
                break;
            case 'connector':

                if (!$this->modx->loadClass('uicmpgenerator.request.GalConnectorRequest',$this->config['modelPath'],true,true)) {
                    return 'Could not load connector request handler.';
                }
                $this->request = new GalConnectorRequest($this);
                return $this->request->handle();
                break;
            default:
                break;
        }
    }


    public function loadProcessor($name,array $scriptProperties = array()) {
        if (!isset($this->modx->error)) $this->modx->request->loadErrorHandler();

        $path = $this->config['processorsPath'].$name.'.php';
        $processorOutput = false;
        if (file_exists($path)) {
            $modx =& $this->modx;
            $gallery =& $this;

            $processorOutput = include $path;
        } else {
            $processorOutput = $this->modx->error->failure('No action specified.');
        }
        return $processorOutput;
    }

    /**
     * Gets a Chunk and caches it; also falls back to file-based templates
     * for easier debugging.
     *
     * @access public
     * @param string $name The name of the Chunk
     * @param array $properties The properties for the Chunk
     * @return string The processed content of the Chunk
     */
    public function getChunk($name,array $properties = array()) {
        $chunk = null;
        if (!isset($this->chunks[$name])) {
            $chunk = $this->modx->getObject('modChunk',array('name' => $name),true);
            if (empty($chunk)) {
                $chunk = $this->_getTplChunk($name);
                if ($chunk == false) return false;
            }
            $this->chunks[$name] = $chunk->getContent();
        } else {
            $o = $this->chunks[$name];
            $chunk = $this->modx->newObject('modChunk');
            $chunk->setContent($o);
        }
        $chunk->setCacheable(false);
        return $chunk->process($properties);
    }
    /**
     * Returns a modChunk object from a template file.
     *
     * @access private
     * @param string $name The name of the Chunk. Will parse to name.chunk.tpl
     * @return modChunk/boolean Returns the modChunk object if found, otherwise
     * false.
     */
    private function _getTplChunk($name) {
        $chunk = false;
        $f = $this->config['chunksPath'].strtolower($name).'.chunk.tpl';
        if (file_exists($f)) {
            $o = file_get_contents($f);
            $chunk = $this->modx->newObject('modChunk');
            $chunk->set('name',$name);
            $chunk->setContent($o);
        }
        return $chunk;
    }

    /**
     * Used for development and debugging
     * @param string $name
     * @param array $properties
     * @return boolean
     */
    public function getPage($name,array $properties = array()) {
        $name = str_replace('.','/',$name);
        $f = $this->config['pagesPath'].strtolower($name).'.tpl';
        $o = '';
        if (file_exists($f)) {
            $o = file_get_contents($f);
            $chunk = $this->modx->newObject('modChunk');
            $chunk->setContent($o);
            return $chunk->process($properties);
        }
        return false;
    }


    /**
     * Starts the debug timer.
     *
     * @access protected
     * @return int The start time.
     */
    protected function startDebugTimer() {
        $mtime = microtime();
        $mtime = explode(' ', $mtime);
        $mtime = $mtime[1] + $mtime[0];
        $tstart = $mtime;
        $this->debugTimer = $tstart;
        return $this->debugTimer;
    }
    /**
     * Ends the debug timer and returns the total number of seconds Discuss took
     * to run.
     *
     * @access protected
     * @return int The end total time to execute the script.
     */
    protected function endDebugTimer() {
        $mtime= microtime();
        $mtime= explode(" ", $mtime);
        $mtime= $mtime[1] + $mtime[0];
        $tend= $mtime;
        $totalTime= ($tend - $this->debugTimer);
        $totalTime= sprintf("%2.4f s", $totalTime);
        $this->debugTimer = false;
        return $totalTime;
    }

    function getPackage($packageId){
        return $this->modx->getObject('Uicmpgenerator', $packageId);
    }
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
    function getPackageDirectories($package_name){
        return array(
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
    }
    function createPackageDirectories($package_name){
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
$directories = $this->getPackageDirectories($package_name);

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
    }
}