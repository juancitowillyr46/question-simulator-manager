<?php
/**
 * modFirebaseJWT class file for modJWT extra
 *
 * Copyright (C) 2019 by Zaenal <zaenal(#)lokamaya.com>
 * Created on 03-26-2019
 *
 * modJWT is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * modJWT is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * modJWT; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package modjwt
 */
require_once dirname(__FILE__) . '/jwt/firebase/jwt.class.php';
use Firebase\JWT\JWT as FirebaseJWT;

class modFirebaseJWT extends FirebaseJWT {
    /** @var $modx modX */
    public $modx;

    /** @var array $config */
    public $config;

    /** @var bool isError */
    public $isError;

    /** @var string logError */
    public $logError;

    /** @var mixed output */
    public $errorData;
    
    /** @var mixed output */
    public $jsonData;

    /** @var mixed payload */
    public $payload;
    
    /** @var mixed token */
    public $token;
    
    /**
     * @param modX $modx
     * @param array $config
    **/
    function __construct(modX &$modx, array $config = []) {
        error_reporting(E_ALL);
        $this->modx =& $modx;
        $this->isError = false;
        
        $corePath = MODX_CORE_PATH . 'components/modjwt/';
        $assetsUrl = MODX_ASSETS_URL . 'components/modjwt/';
        
        $config = $this->prepareConfig($config);

        $this->config = array_merge([
            'corePath' => $corePath,
            'modelPath' => $corePath . 'model/',
            'assetsUrl' => $assetsUrl,
        ], $config);
        
        $this->modx->lexicon->load('modjwt:firebase');
        
        static::$timestamp = time();
    }

    /**
     * encodeJWT
     * Output JWT Token
     *
     * @return string
    **/
    public function encodeJWT() {
        // prepare header
        $jwtType   = strtoupper($this->config['typ']) == 'JWS' ? 'JWS' : 'JWT';
        $algorithm = strtoupper($this->config['alg']);
        
        if (!array_key_exists($algorithm, static::$supported_algs)) {
            return $this->outputError("Unknown algoritm: $algorithm", 405);
        }
        
        $header = array(
            'typ' => $jwtType,
            'alg' => $algorithm,
            );
        
        // prepare payload
        $payload = $this->preparePayload($this->config['payloadData']);
        $this->payload = $payload;
        
        // prepare secret key
        $secretk = $this->prepareSecretKey($algorithm);
        if (!$secretk) {
            return $this->outputError("Secret key not available!", 503);
        }

        // get Token
        $segments = array();
        $segments[] = static::urlsafeB64Encode(static::jsonEncode($header));
        $segments[] = static::urlsafeB64Encode(static::jsonEncode($payload));
        $signing_input = implode('.', $segments);

        $signature = static::sign($signing_input, $secretk, $algorithm);
        $segments[] = static::urlsafeB64Encode($signature);
        
        $token = implode('.', $segments);
        $this->token = $token;
        
        $this->payload = null; 
        $this->setJSONData();
        return $token;
    }
    
    /**
     * decodeJWT
     * validate JWT Token
     * 
     * @return bool
    **/
    public function decodeJWT() {
        // prepare header
        $jwtType   = strtoupper($this->config['typ']) == 'JWS' ? 'JWS' : 'JWT';
        $algorithm = strtoupper($this->config['alg']);
        $timestamp = static::$timestamp;
        $_x = $this->modx;
        
        $token = $this->getTokenClaim($this->config['method']);
        //die($this->config['method']);
        $this->token = $token;
        
        // prepare secret key
        $secretk = $this->prepareSecretKey($algorithm);
        if (!$secretk) {
            return $this->outputError("Secret key not available!", 503);
        }
        
        $_tokenArray = explode('.', $token);
        if (count($_tokenArray) != 3) {
            return $this->outputError($this->modx->lexicon('modjwt_error_token_invalid') . $token, 400);
        }
        
        list($headb64, $bodyb64, $cryptob64) = $_tokenArray;
        
        // verify header
        $header = static::jsonDecode(static::urlsafeB64Decode($headb64));
        
        // verify signature
        $signature = static::urlsafeB64Decode($cryptob64);
        
        if ($signature === false) {
            return $this->outputError($this->modx->lexicon('modjwt_error_signature_invalid'), 400);
        }
        
        if (!parent::verify("$headb64.$bodyb64", $signature, $secretk, $header->alg)) {
            return $this->outputError($this->modx->lexicon('modjwt_error_signature_failed'), 400);
        }
        
        // if signature OK, more on header
        if ($header === null) {
            return $this->outputError($this->modx->lexicon('modjwt_error_header_empty'), 400);
        }
        
        if (empty($header->alg)) {
            return $this->outputError($this->modx->lexicon('modjwt_error_alg_empty'), 400);
        }
        
        if (empty(static::$supported_algs[$header->alg])) {
            return $this->outputError(sprintf($this->modx->lexicon('modjwt_error_alg_nosupport'), $header->alg), 400);
        }
        
        if (is_array($secretk) || $secretk instanceof \ArrayAccess) {
            if (isset($header->kid)) {
                if (!isset($secretk[$header->kid])) {
                    return $this->outputError($this->modx->lexicon('modjwt_error_kid_invalid'), 400);
                }
                $secretk = $secretk[$header->kid];
            } else {
                return $this->outputError($this->modx->lexicon('modjwt_error_kid_empty'), 400);
            }
        }
        
        // verify payload
        $payload = static::jsonDecode(static::urlsafeB64Decode($bodyb64));
        
        if ($payload === null) {
            return $this->outputError($this->modx->lexicon('modjwt_error_payload_claim'), 400);
        } else {
            // is expired?
            if (isset($payload->exp) && ($timestamp - static::$leeway) >= $payload->exp) {
                return $this->outputError($this->modx->lexicon('modjwt_error_token_expire'), 400);
            }
            // is not before time?
            if (isset($payload->nbf) && $payload->nbf > ($timestamp + static::$leeway)) {
                return $this->outputError(sprintf($this->modx->lexicon('modjwt_error_nbf_timestamp'), date(DateTime::ISO8601, $payload->nbf)), 400);
            }
            // valid time?
            if (isset($payload->iat) && $payload->iat > ($timestamp + static::$leeway)) {
                return $this->outputError(sprintf($this->modx->lexicon('modjwt_error_iat_timestamp'), date(DateTime::ISO8601, $payload->iat)), 400);
            }
        }
        
        $decoded_payload = (array)$payload;
        $this->payload = $payload;
        
        $this->token = null; 
        $this->setJSONData();
        return $decoded_payload;
    }
    
    /**
     * preparePayload
     * Prepare Payload, and merge with payloadData
     *
     * @var string payloadData
     * @output array Payload
    **/
    public function preparePayload($payloadData='') {
        $_issuedAt = static::$timestamp;
        $_subject  = $this->getUserField();
        $_audience = empty($this->config['aud']) ? null : $this->config['aud'];
        
        $payload = array(
            'iss' => $this->config['iss'],
            'iat' => $_issuedAt,
            'exp' => $_issuedAt + $this->config['expAge']
            );
        
        if (!empty($_subject))  $payload['sub'] = $_subject;
        if (!empty($_audience)) $payload['aud'] = $_audience;
        
        if (!empty($payloadData) && is_string($payloadData)) {
            $payloadData = static::jsonDecode($payloadData, true);
            $payload = array_merge($payload, $payloadData);
        }
        
        return $payload;
    }
    
    /**
     * prepareSecretKey
     * Prepare secret key for encoding JWT Token
     *
     * @var string key
     * @var string algorithm
     * @output string
    **/
    public function prepareSecretKey($algorithm) {
        $secretKey  = null;
        
        if (in_array( $algorithm, array('HS256', 'HS384', 'HS512') )) {
            $secretKey  = trim($this->config['secretKey']);
        } elseif (in_array( $algorithm, array('RS256', 'RS384', 'RS512') )) {
            $secretFile = trim($this->config['secretFile']);
            if (!empty($secretFile)) {
                $secretKey = @file_get_contents($secretFile);
            }
        }
        
        return $secretKey;
    }
    
   /**
     * getTokenClaim
     *
     * @return string
    **/
    public function getTokenClaim($allowedMethod="HEADER,JSON,POST") {
        if (!is_array($allowedMethod)) {
            $allowedMethod = explode(",", $allowedMethod);
        }
        
        $token = null;
        $query = $this->config['httpQuery'];
        
        foreach ($allowedMethod as $method) {
            switch ($method) {
                case 'HEADER':
                    $token = $this->getBearerToken();
                    break;
                case 'JSON':
                    $token = $this->getJsonToken($query);
                    break;
                case 'POST':
                    $token = isset($_POST[$query]) ? trim($_POST[$query]) : null;
                    break;
                case 'GET':
                    $token = isset($_GET[$query]) ? trim($_GET[$query]) : null;
                    break;
                case 'COOKIE':
                    $token = isset($_COOKIE[$query]) ? trim($_COOKIE[$query]) : null;
                    break;
            }
            if ($token !== null) break;
        }
        return $token;
    }

    /**
     * getUserField
     * @return string
    **/
    public function getUserField() {
        $_subject = $this->config['sub'];
        $_userField = strtolower($this->config['subField']);
        
        $output = null;
        
        if (empty($_subject)) {
            $user = $this->modx->getUser();
            if (!$user) return 'anon';
            
            switch ($_userField) {
                case 'email':
                    if ($profile = $user->getOne('Profile')) {
                        $output = $profile->get('email');
                    }
                    break;
                case 'username':
                    $output = $profile->get('username');
                    break;
                case 'id':
                case 'userid':
                    $output = $profile->get('id');
                    break;
                case 'sessionid':
                    $output = session_id();
                    break;
            }
        }
        
        return $output;
    }
    
    /**
      * get access token from JSON
      * @return string
    **/
    function getJsonToken($query) {
        $json_request = (static::jsonDecode($request) !== NULL) ? true : false;
        
        if ($json_request = static::jsonDecode(file_get_contents("php://input"))) {
            $token = trim($json_request[$query]);
            return $token;
        }
        
        return null;
    }
    
    /**
      * get access token from header
      * @return string
    **/
    function getBearerToken() {
        $headers = $this->getAuthorizationHeader();
        
        // HEADER: Get the access token from the header
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return trim($matches[1]);
            }
        }
        return null;
    }
    
    /**
      * get header from http request
      * @return string
    **/
    public function getAuthorizationHeader(){
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            //print_r($requestHeaders);
            
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        
        return $headers;
    }
    
    public function setJSONData() {
        $output = array(
            '_valid'      => 1,
            'status'     => 200,
            'statusText' => 'OK',
            'payload' => $this->payload,
            'token'   => $this->token
            );
        
        if ($this->config['mimeType'] == 'json') {
            if (!headers_sent()) header('Content-Type: application/json;charset=utf-8');
            $output = static::jsonEncode($output);
        }
        $this->jsonData = $output;
        return $output;
    }

    
    /**
     * outputError
     *
     * @var string log
     * @var int errorCode
     * @var string status
     * @return string
    **/
    public function outputError($log='', $errorCode=403) {
        $errorMessage = array(
            400 => $this->modx->lexicon('modjwt_status_400'),
            401 => $this->modx->lexicon('modjwt_status_401'),
            402 => $this->modx->lexicon('modjwt_status_402'),
            403 => $this->modx->lexicon('modjwt_status_403'),
            404 => $this->modx->lexicon('modjwt_status_404'),
            405 => $this->modx->lexicon('modjwt_status_405'),
            406 => $this->modx->lexicon('modjwt_status_406'),
            407 => $this->modx->lexicon('modjwt_status_407'),
            408 => $this->modx->lexicon('modjwt_status_408'),
            409 => $this->modx->lexicon('modjwt_status_409'),
            410 => $this->modx->lexicon('modjwt_status_410'),
            413 => $this->modx->lexicon('modjwt_status_413'),
            429 => $this->modx->lexicon('modjwt_status_429'),
            431 => $this->modx->lexicon('modjwt_status_431'),
            500 => $this->modx->lexicon('modjwt_status_500'),
            501 => $this->modx->lexicon('modjwt_status_501'),
            502 => $this->modx->lexicon('modjwt_status_502'),
            503 => $this->modx->lexicon('modjwt_status_503'),
            504 => $this->modx->lexicon('modjwt_status_504'),
            );
        
        $output = array(
            '_valid'      => 0,
            'status'     => $errorCode,
            'statusText' => $errorMessage[$errorCode],
            'errorLog'   => $log,
            );
               
        if (empty($log)) $log = $errorCode . ': ' . $errorMessage[$errorCode];
        $log = '[JWT] ' . $log;
        
        $this->isError  = true;
        $this->logError = $log;
        
        $this->modx->log(modX::LOG_LEVEL_ERROR, $log);
        $this->modx->setPlaceholder($this->config['debugPlaceholder'], $log);

        /*
        if (!headers_sent()) {
            http_response_code($errorCode);
            header("HTTP/1.1 $errorCode " . $errorMessage[$errorCode]);
            header("Cache-Control: no-store");
            header("Pragma: no-cache");
        }
        */
        if ($this->config['mimeType'] == 'json') {
            if (!headers_sent()) header('Content-Type: application/json;charset=utf-8');
            $output = static::jsonEncode($output);
        }
        
        $this->errorData = $output;
        return false;
    }
    
    /** checkConfig
      * @return mixed
    **/
    private function checkConfig($key, $config, $default=null) {
        $output = isset($config[$key]) && !empty($config[$key]) ? $config[$key] : $default;
        return $output;
    }
    
    /** prepareConfig
      * @return array
    **/
    private function prepareConfig($configs) {
        $_conf = array();
        
        // Default configuration
        $_conf['method']    = $this->checkConfig('method', $configs, 'HEADER,GET');
        $_conf['httpQuery'] = $this->checkConfig('httpQuery', $configs, 'token');
        // Secret key
        $_conf['secretKey']  = $this->checkConfig('secretKey', $configs, $this->modx->getOption('modjwt.secretkey', null, null));
        
        $_conf['requestType'] = $this->checkConfig('requestType', $configs, null);
        $_conf['privateFile'] = $this->checkConfig('privateFile', $configs, $this->modx->getOption('modjwt.privatekey', null, null));
        $_conf['publicFile']  = $this->checkConfig('publicFile', $configs, $this->modx->getOption('modjwt.publickey', null, null));
        if ($_conf['requestType'] == 'encode') {
            $_conf['secretFile']  = $_conf['privateFile'];
        } elseif ($_conf['requestType'] == 'decode') {
            $_conf['secretFile']  = $_conf['publicFile'];
        }

        $redirectTo = $this->checkConfig('redirectTo', $configs, null);
        $redirectScheme = $this->checkConfig('redirectScheme', $configs, 'full');
        if (is_numeric($redirectTo)) {
            $redirectTo = $this->modx->makeURL((int)$redirectTo, '', '', $redirectScheme);
        }
        $_conf['redirectTo'] = $redirectTo;
        
        // Some JWT setting
        $_conf['typ'] = $this->checkConfig('typ', $configs, 'JWT');
        $_conf['alg'] = $this->checkConfig('validAlg', $configs, $this->checkConfig('alg', $configs, 'HS256,HS384,HS512'));
        
        
        $_conf['mimeType']         = $this->checkConfig('mimeType', $configs, 'JSON');
        $_conf['toPlaceholder']    = $this->checkConfig('toPlaceholder', $configs, null);
        $_conf['debugPlaceholder'] = $this->checkConfig('debugPlaceholder', $configs, 'jwtDebug');
        
        $_conf['directOutput'] = !empty($_conf['toPlaceholder']) ? false : true;
            
        $_domain = parse_url(MODX_SITE_URL,PHP_URL_HOST);
        $_conf['iss']      = $this->checkConfig('iss', $configs, $_domain);
        $_conf['sub']      = $this->checkConfig('sub', $configs, null);
        $_conf['subField'] = $this->checkConfig('subField', $configs, 'email');
        $_conf['aud']      = $this->checkConfig('aud', $configs, $_domain);
        $_conf['jti']      = $this->checkConfig('jti', $configs, false);
        

        $_conf['expAge']   = (int)$this->checkConfig('expAge', $configs, 3600);
        $_conf['nbfAge']   = (int)$this->checkConfig('nbfAge', $configs, 0);

        // Payload data
        $_conf['payloadData'] = $this->checkConfig('payloadData', $configs, null);
        
        $configs = array_merge($configs, $_conf);
        return $configs;        
    }
}