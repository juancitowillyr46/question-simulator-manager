<?php
/**
 * Get a list
 * 
 * @package uicmpgenerator
 * @subpackage processors
 * This file needs to be customized
 */
/* setup default properties */
$isLimit = !empty($scriptProperties['limit']);
$start = $modx->getOption('start',$scriptProperties,0);
$limit = $modx->getOption('limit',$scriptProperties,20);
$sort = $modx->getOption('sort',$scriptProperties,'create_date');
$dir = $modx->getOption('dir',$scriptProperties,'DESC');
$query = $modx->getOption('query',$scriptProperties,'');

/* build query */
$c = $modx->newQuery('Uicmpgenerator');

if (!empty($query)) {
    $c->where(array(
        'package:LIKE' => '%'.$query.'%'
    ));
}

$count = $modx->getCount('Uicmpgenerator',$c);
$c->sortby($sort,$dir);
if ($isLimit) {
    $c->limit($limit,$start);
}
$packages = $modx->getIterator('Uicmpgenerator', $c);


/* iterate */
$list = array();
foreach ($packages as $package) {
    $package_array = $package->toArray();
    // make the date readable
    $package_array['create_date'] = date('n/j/y g:ia',strtotime($package_array['create_date']));
    $list[] = $package_array; 
}
return $this->outputArray($list,$count);