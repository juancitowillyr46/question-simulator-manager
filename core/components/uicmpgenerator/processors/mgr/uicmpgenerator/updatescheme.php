<?php
/**
 * @package uicmpgenerator
 * @subpackage processors
 */

$modx->lexicon->load('uicmpgenerator:default');

if ( empty($scriptProperties['id']) ) {
    return $modx->error->failure($modx->lexicon('uicmpgenerator.err_nf'));
}


$cmp = $modx->getObject('Uicmpgenerator',$scriptProperties['id']);
if (empty($cmp)){
    return $modx->error->failure($modx->lexicon('uicmpgenerator.err_nf'));
}
$cmp->set('scheme',$scriptProperties['scheme']);
if(!$cmp->save()) {
    return $modx->error->failure($modx->lexicon('uicmpgenerator.err_save_scheme'));
}
return $modx->error->success('');


