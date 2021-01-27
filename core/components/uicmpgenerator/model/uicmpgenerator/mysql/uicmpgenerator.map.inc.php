<?php
$xpdo_meta_map['Uicmpgenerator']= array (
  'package' => 'uicmpgenerator',
  'version' => '1.1',
  'table' => 'uicmpgenerator',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'package' => NULL,
    'database' => NULL,
    'table_prefix' => NULL,
    'scheme' => NULL,
    'properties' => NULL,
    'build_scheme' => NULL,
    'build_package' => NULL,
    'create_date' => NULL,
    'last_ran' => NULL,
  ),
  'fieldMeta' => 
  array (
    'package' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '225',
      'phptype' => 'string',
      'null' => false,
    ),
    'database' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '128',
      'phptype' => 'string',
      'null' => true,
    ),
    'table_prefix' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => true,
    ),
    'scheme' =>
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
    ),
    'properties' => 
    array (
      'dbtype' => 'mediumtext',
      'phptype' => 'string',
      'null' => true,
    ),
    'build_scheme' => 
    array (
      'dbtype' => 'int',
      'precision' => '1',
      'phptype' => 'integer',
      'null' => true,
    ),
    'build_package' => 
    array (
      'dbtype' => 'int',
      'precision' => '1',
      'phptype' => 'integer',
      'null' => true,
    ),
    'create_date' => 
    array (
      'dbtype' => 'timestamp',
      'phptype' => 'timestamp',
      'null' => true,
    ),
    'last_ran' => 
    array (
      'dbtype' => 'timestamp',
      'phptype' => 'timestamp',
      'null' => true,
    ),
  ),
);
