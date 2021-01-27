<?php
$xpdo_meta_map['SimUserAssignedPlan']= array (
  'package' => 'pkgsimulador',
  'version' => '1.1',
  'table' => 'sim_user_assigned_plan',
  'extends' => 'xPDOSimpleObject',
  'tableMeta' => 
  array (
    'engine' => 'MyISAM',
  ),
  'fields' => 
  array (
    'user_id' => NULL,
    'plan_id' => NULL,
    'date_expiry' => NULL,
    'associated_exams' => NULL,
  ),
  'fieldMeta' => 
  array (
    'user_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => true,
    ),
    'plan_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => true,
    ),
    'date_expiry' => 
    array (
      'dbtype' => 'datetime',
      'phptype' => 'datetime',
      'null' => true,
    ),
    'associated_exams' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => false,
    ),
  ),
  'aggregates' => 
  array (
    'SimPlans' => 
    array (
      'class' => 'SimPlans',
      'local' => 'plan_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'local',
    ),
    'SimUserApDetail' => 
    array (
      'class' => 'SimUserApDetail',
      'local' => 'id',
      'foreign' => 'assigned_plan_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'Users' => 
    array (
      'class' => 'modUser',
      'local' => 'user_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
