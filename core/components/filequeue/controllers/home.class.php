<?php

/**
 * The home manager controller for fileQueue.
 *
 */
class fileQueueHomeManagerController extends modExtraManagerController
{
    /** @var fileQueue $fileQueue */
    public $fileQueue;


    /**
     *
     */
    public function initialize()
    {
        $path = $this->modx->getOption('filequeue_core_path', null,
                $this->modx->getOption('core_path') . 'components/filequeue/') . 'model/filequeue/';
        $this->fileQueue = $this->modx->getService('filequeue', 'fileQueue', $path);
        parent::initialize();
    }


    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return array('filequeue:default');
    }


    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return true;
    }


    /**
     * @return null|string
     */
    public function getPageTitle()
    {
        return $this->modx->lexicon('filequeue');
    }


    /**
     * @return void
     */
    public function loadCustomCssJs()
    {
        $this->addCss($this->fileQueue->config['cssUrl'] . 'mgr/main.css');
        $this->addCss($this->fileQueue->config['cssUrl'] . 'mgr/bootstrap.buttons.css');
        $this->addJavascript($this->fileQueue->config['jsUrl'] . 'mgr/filequeue.js');
        $this->addJavascript($this->fileQueue->config['jsUrl'] . 'mgr/misc/utils.js');
        $this->addJavascript($this->fileQueue->config['jsUrl'] . 'mgr/misc/combo.js');
        $this->addJavascript($this->fileQueue->config['jsUrl'] . 'mgr/misc/strftime-min-1.3.js');
        $this->addJavascript($this->fileQueue->config['jsUrl'] . 'mgr/widgets/items.grid.js');
        $this->addJavascript($this->fileQueue->config['jsUrl'] . 'mgr/widgets/items.windows.js');
        $this->addJavascript($this->fileQueue->config['jsUrl'] . 'mgr/widgets/home.panel.js');
        $this->addJavascript($this->fileQueue->config['jsUrl'] . 'mgr/sections/home.js');

        $this->addHtml('<script type="text/javascript">
        fileQueue.config = ' . json_encode($this->fileQueue->config) . ';
        fileQueue.config.connector_url = "' . $this->fileQueue->config['connectorUrl'] . '";
        Ext.onReady(function() {
            MODx.load({ xtype: "filequeue-page-home"});
        });
        </script>
        ');
    }


    /**
     * @return string
     */
    public function getTemplateFile()
    {
        return $this->fileQueue->config['templatesPath'] . 'home.tpl';
    }
}