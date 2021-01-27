<?php
/**
 * jwtEncode snippet for modJWT extra
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
 * jwtEncode
 *
 * DESCRIPTION
 *
 * Issuing or encoding JWT Token.
 * You can output directly or to placeholder
 *
 *
 * PROPERTIES:
 * &alg                     string     optional    Default: HS256 (options: HS256, HS384, HS512 and RS256)
 * &typ                     string     optional    Default: JWT (options: JWT, JWS)
 * -------
 * secretFile               string     required    Absolute path to file PRIVATE-KEY. Required for asymetric algorithm, like RS256.
 *                                                 - using default modx setting: `[[++modjwt.privatekey]]`
 *                                                 - using custom file: `/var/www/modx/protectedfolder/myprivate.key`
 * secretKey                string     required    Required for symetric algorithm, like HS256, HS384 or HS512.  
 *                                                 - using default modx setting: `[[++modjwt.secretkey]]`
 *                                                 - using custom secret key: `mysecretkey1234`
 * -------
 * &mimeType                string     optional    Default: json (options: json, html)
 * &toPlaceholder           string     optional    Default: null (if null mean direct output)
 * &debugPlaceholder        string     optional    Default: jwtDebug
 * -------
 * &iss                     string     optional    URI of JWT issuer (default: your MODx URL) 
 * &sub                     string     optional    If empty, auto-populated by subField value below
 * &subField                string     optional    Default: email of visitor/user (options: username, email, userid, sessionid)
 * &aud                     string     optional    URI of the audience/server 
 * &jti                     bool       optional    Default: false (if true, auto-populated by sessionid)
 * -------
 * &iat                     timestamp  not-used    Auto-populated: current timestamp
 * &exp                     timestamp  not-used    Auto-populated: current timestamp + expAge
 * &nbf                     timestamp  not-used    Auto-populated: current timestamp + nbfAge
 * &expAge                  integer    optional    Default: 3600 seconds or 1 hour
 * &nbfAge                  integer    optional    Default: 0; if greater than 0 than &nbf will be populated
 * -------
 * &payloadData             json       optional    Example: `{"A":"1", "200":"enclosed with quote"}`
 *                                                 valid JSON-string key must be enclosed with quote
 *
 * USAGE:
 *
 * [[jwtCreate]]
 * [[jwtCreate? &payloadData=`{"fullname":"John Doe"}`]] //add fullname to JWT Payload
 * [[jwtCreate? &payloadData=`{"group":"superadmin", "access":"full"}` &iss=`https://www.google.com` ]] //add payloadData, and change the issuer
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var modJWT $modJWT
**/

$output = '';

// Core path
$corePath  = $modx->getOption('core_path', null, MODX_CORE_PATH) . 'components/modjwt/';
$scriptProperties['corePath'] = $corePath;
$scriptProperties['requestType'] = 'encode';

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

$output='';
if ($token = $modJWT->encodeJWT()) {  //success
  $output = $modJWT->jsonData;
} else {                              //error
  $output = $modJWT->errorData;
}

return $output;