<?php
/**
 * jwtDecode snippet for modJWT extra
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

/**
 * jwtDecode
 *
 * DESCRIPTION
 *
 * Validating or encoding JWT Token, return Payload Data
 * You can output directly or to placeholder
 *
 *
 * PROPERTIES:
 * &method                  string     required    Default: `HEADER` (options: HEADER, JSON, POST, GET)
 * &httpQuery               string     required    required if using method JSON, POST, or GET. Default: token.
 * &redirectTo              mixed      optional    Default: null; otherwise will be redirected to given uri
 * &redirectScheme          string     optional    Default: `full` (modx->makeURL parameter)
 * -------
 * &typ                     string     optional    Default: `JWT` (options: JWT, JWS)
 * &alg                     string     required    Default: `HS256,HS384,HS512`; list of allowed alghoritms
 * &validAlg                string     optional    Alternate to &alg
 * -------
 * secretKey                string     required    Required for symetric algorithm, like HS256, HS384 or HS512.  
 *                                                 - using default modx setting: `[[++modjwt.secretkey]]`
 *                                                 - using custom secret key: `mysecretkey1234`
 * secretFile               string     required    Absolute path to file PUBLIC-KEY. Required for asymetric algorithm, like RS256.
 *                                                 - using default modx setting: `[[++modjwt.publickey]]`
 *                                                 - using custom file: `/var/www/modx/protectedfolder/mypublic.key`
 * -------
 * &mimeType                string     optional    Default: `JSON` (options: json, html)
 * &toPlaceholder           mixed      optional    Default: null (direct output), otherwise will be set to the &toPlaceholder's value.
 * &debugPlaceholder        string     optional    Default: `jwtDebug`
 * -------
 * &iss                     string     optional    URI of JWT issuer (default: your MODx URL) 
 * &sub                     string     optional    If empty, auto-populated by subField value below
 * &subField                string     optional    Default: email of visitor/user (options: username, email, userid, sessionid)
 * &aud                     string     optional    URI of the audience/server 
 * &jti                     bool       optional    Default: false (if true, auto-populated by sessionid)
 * &leeway                  integer    optional    Default: 0, the amount of seconds to move or act that is available; no need in same server
 *
 *
 * USAGE:
 *
 * [[jwtDecode]]
 * [[jwtDecode? &redirectTo=`50`]]                         //on success will be redirected to resourceid=10
 * [[jwtDecode? &redirectTo=`http://mydomain.com/video/`]] //on success will be redirected to this url
 * [[jwtDecode? &validAlg=`HS256,HS384`]]                  //only this algorithm allowed
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var modJWT $modJWT
**/

$output = '';

/**
 * You can edit scriptProperties configurations below
**/

// Core path
$corePath  = $modx->getOption('core_path', null, MODX_CORE_PATH) . 'components/modjwt/';
$scriptProperties['corePath'] = $corePath;
$scriptProperties['requestType'] = 'decode';

/** @var modJWT $modJWT */
$modJWT = $modx->getService(
    'modjwt', 
    'modFirebaseJWT', 
    $corePath . 'model/modjwt/',
    $scriptProperties
);

if (!$modJWT) {
    $log = 'Could not load modJWT class!';
    
    $modx->setPlaceholder($debugPlaceholder, $log);
    $modx->log(modX::LOG_LEVEL_ERROR, $log);
    
    return json_encode(array(
        '_valid'     => 0,
        'status'     => 503,
        'statusText' => 'Service Unavailable',
        'errorLog'   => $log
    ));
}

$output = '';
if ($payload = $modJWT->decodeJWT()) {  //success
  $output = $modJWT->jsonData;
} else {                                //error
  $output = $modJWT->errorData;
}

return $output;