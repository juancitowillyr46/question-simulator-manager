<?php
$xpdo_meta_map['SimUserApDetail']= array (
  'package' => 'pkgsimulador',
  'version' => '1.1',
  'table' => 'sim_user_ap_detail',
  'extends' => 'xPDOSimpleObject',
  'tableMeta' => 
  array (
    'engine' => 'MyISAM',
  ),
  'fields' => 
  array (
    'assigned_plan_id' => NULL,
    'exam_id' => NULL,
    'num_attempts_made' => NULL,
    'attempts_made_at' => NULL,
  ),
  'fieldMeta' => 
  array (
    'assigned_plan_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => true,
    ),
    'exam_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => true,
    ),
    'num_attempts_made' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => true,
    ),
    'attempts_made_at' => 
    array (
      'dbtype' => 'datetime',
      'phptype' => 'datetime',
      'null' => true,
    ),
  ),
  'aggregates' => 
  array (
    'SimExams' => 
    array (
      'class' => 'SimExams',
      'local' => 'exam_id',
      'foreign' => 'id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'SimUserAssignedPlan' => 
    array (
      'class' => 'SimUserAssignedPlan',
      'local' => 'assigned_plan_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
