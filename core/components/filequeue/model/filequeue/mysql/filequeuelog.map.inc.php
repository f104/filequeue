<?php
$xpdo_meta_map['fileQueueLog']= array (
  'package' => 'filequeue',
  'version' => '1.1',
  'table' => 'filequeue_logs',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'fileid' => 0,
    'createdon' => 0,
    'message' => '',
  ),
  'fieldMeta' => 
  array (
    'fileid' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
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
    'message' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '500',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
  ),
  'aggregates' => 
  array (
    'fileQueueItem' => 
    array (
      'class' => 'fileQueueItem',
      'local' => 'fileid',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
