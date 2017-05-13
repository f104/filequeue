<?php
$xpdo_meta_map['fileQueueItem']= array (
  'package' => 'filequeue',
  'version' => '1.1',
  'table' => 'filequeue_items',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'name' => '',
    'filename' => '',
    'status' => 0,
    'createdon' => 0,
    'createdby' => 0,
    'processedon' => 0,
  ),
  'fieldMeta' => 
  array (
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'filename' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'status' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'phptype' => 'int',
      'null' => false,
      'default' => 0,
    ),
    'createdon' => 
    array (
      'dbtype' => 'int',
      'precision' => '20',
      'phptype' => 'timestamp',
      'null' => false,
      'default' => 0,
    ),
    'createdby' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'processedon' => 
    array (
      'dbtype' => 'int',
      'precision' => '20',
      'phptype' => 'timestamp',
      'null' => false,
      'default' => 0,
    ),
  ),
  'composites' => 
  array (
    'fileQueueLogs' => 
    array (
      'class' => 'fileQueueLog',
      'local' => 'id',
      'foreign' => 'fileid',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);
