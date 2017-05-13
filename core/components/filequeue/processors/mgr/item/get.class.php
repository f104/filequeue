<?php

class fileQueueItemGetProcessor extends modObjectGetProcessor {

    public $objectType = 'fileQueueItem';
    public $classKey = 'fileQueueItem';
    public $languageTopics = array('filequeue:default');

    //public $permission = 'view';

    /**
     * We doing special check of permission
     * because of our objects is not an instances of modAccessibleObject
     *
     * @return mixed
     */
    public function process() {
        if (!$this->checkPermissions()) {
            return $this->failure($this->modx->lexicon('access_denied'));
        }

        return parent::process();
    }
    
    /**
     * Used for adding custom data in derivative types
     * @return void
     */
    public function beforeOutput() { 
        $output = array();
        $logs = $this->object->getMany('fileQueueLogs');
        foreach ($logs as $row) {
            $output[] = '[' . date('d-m-Y H:i', $row->createdon) . '] ' . $row->message;
        }
        $this->object->set('log', implode("\n", $output));
    }

}

return 'fileQueueItemGetProcessor';
