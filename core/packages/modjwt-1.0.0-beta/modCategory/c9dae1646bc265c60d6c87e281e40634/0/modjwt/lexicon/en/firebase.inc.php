<?php
/**
 * en:firebase.inc.php topic lexicon file for modJWT extra
 *
 * Copyright 2019 by Zaenal zaenal(#)lokamaya.com
 * Created on 03-30-2019
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
 * Description
 * -----------
 * en:firebase.inc.php topic lexicon strings
 *
 * Variables
 * ---------
 * @var $modx modX
 * @var $scriptProperties array
 *
 * @package modjwt
 **/

/* Used in modfirebasejwt.class.php */
$_lang['modjwt'] = 'modJWT';
$_lang['modjwt_secretkey_not_match'] = 'A secret key not match!';
$_lang['modjwt_keypair_not_match']   = 'Private key and public key not match!';
$_lang['modjwt_error_class']      = 'Could not load modJWT class!';
$_lang['modjwt_error_unknow_alg'] = 'Unknown algoritm: %s';
$_lang['modjwt_error_secret_key'] = 'Secret key not available!';
$_lang['modjwt_error_token_invalid'] = 'Invalid Token: ';
$_lang['modjwt_error_token_expire'] = 'Token expired!';
$_lang['modjwt_error_signature_invalid'] = 'Invalid signature!';
$_lang['modjwt_error_signature_failed']  = 'Signature verification failed';
$_lang['modjwt_error_header_empty']  = 'There is no Header part on your Token!';
$_lang['modjwt_error_alg_empty']     = 'Header alg is empty';
$_lang['modjwt_error_alg_nosupport'] = 'Header alg %s is not supported';
$_lang['modjwt_error_kid_invalid'] = 'Parameter kid invalid, unable to lookup correct key';
$_lang['modjwt_error_kid_empty']   = 'Parameter kid empty, unable to lookup correct key';
$_lang['modjwt_error_payload_claim'] = 'Payload error: invalid claims encoding!';
$_lang['modjwt_error_nbf_timestamp'] = 'Cannot handle token prior to %s';
$_lang['modjwt_error_iat_timestamp'] = 'Invalid time: cannot handle token prior to %s';

$_lang['modjwt_status_400'] = 'Bad Request';
$_lang['modjwt_status_401'] = 'Unauthorized';
$_lang['modjwt_status_402'] = 'Payment Required';
$_lang['modjwt_status_403'] = 'Forbidden';
$_lang['modjwt_status_404'] = 'Not Found';
$_lang['modjwt_status_405'] = 'Method Not Allowed';
$_lang['modjwt_status_406'] = 'Not Acceptable';
$_lang['modjwt_status_407'] = 'Proxy Authentication Required';
$_lang['modjwt_status_408'] = 'Request Timeout';
$_lang['modjwt_status_409'] = 'Conflict';
$_lang['modjwt_status_410'] = 'Gone';
$_lang['modjwt_status_429'] = 'Too Many Requests';
$_lang['modjwt_status_413'] = 'Payload Too Large';
$_lang['modjwt_status_431'] = 'Request Header Fields Too Large';
$_lang['modjwt_status_500'] = 'Internal Server Error';
$_lang['modjwt_status_501'] = 'Not Implemented';
$_lang['modjwt_status_502'] = 'Bad Gateway';
$_lang['modjwt_status_503'] = 'Service Unavailable';
$_lang['modjwt_status_504'] = 'Gateway Timeout';
