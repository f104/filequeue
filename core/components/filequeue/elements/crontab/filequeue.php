<?php

define('MODX_API_MODE', true);
require_once dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))) . '/index.php';

$path = $modx->getOption('filequeue_core_path', null,
        $modx->getOption('core_path') . 'components/filequeue/') . 'model/filequeue/';
$fileQueue = $modx->getService('filequeue', 'fileQueue', $path);

$fileQueue->run();