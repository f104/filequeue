<?php
// For debug
ini_set('display_errors', 1);
ini_set('error_reporting', -1);
if (file_exists(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php')) {
    /** @noinspection PhpIncludeInspection */
    require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php';
}
else {
    require_once dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/config.core.php';
}
/** @noinspection PhpIncludeInspection */
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
/** @noinspection PhpIncludeInspection */
require_once MODX_CONNECTORS_PATH . 'index.php';
/** @var fileQueue $fileQueue */
$fileQueue = $modx->getService('filequeue', 'fileQueue', $modx->getOption('filequeue_core_path', null,
        $modx->getOption('core_path') . 'components/filequeue/') . 'model/filequeue/'
);
$modx->lexicon->load('filequeue:default');

// handle request
$corePath = $modx->getOption('filequeue_core_path', null, $modx->getOption('core_path') . 'components/filequeue/');
$path = $modx->getOption('processorsPath', $fileQueue->config, $corePath . 'processors/');
$modx->getRequest();

/** @var modConnectorRequest $request */
$request = $modx->request;
$request->handleRequest(array(
    'processors_path' => $path,
    'location' => '',
));