<?php
/**
 * jwtDecodeRunSnippet 
 *
 * DESCRIPTION
 *
 * Validating Token, run snippet, return custom output
 * You can duplicate or modify this snippet and play around
 *
 * PROPERTIES: && all properties of jwtDecode snippet
 * &retval           string   required   Return this value instead of Payload
 *
 * USAGE:
 * [[jwtDecodeRunSnippet? &retval=`https://domain/mysecreturl-nobodyknow`]]   //on success will supply this link
 *
 * @var modX $modx
 * @var array $scriptProperties
**/

//Evaluate required properties
$scriptProperties['retval'] = isset($scriptProperties['retval']) ? $scriptProperties['retval'] : '-required-';

//Run the snippet: jwtDecode
$output = $modx->runSnippet('jwtDecode', $scriptProperties);

//Turn the JSON Object to Array
$outputarray = json_decode($output, true); //return array

//Evaluate
if ($outputarray['_valid'] === 1) { //on success output custom value

    //Modify output
    $output = json_encode(array(
        '_valid'     => 1,
        'retval'     => $scriptProperties['retval']
    ));
}

//Return output
return $output;