<?php

class fileQueueItemRemoveProcessor extends modObjectProcessor
{
    public $objectType = 'fileQueueItem';
    public $classKey = 'fileQueueItem';
    public $languageTopics = array('filequeue');
    //public $permission = 'remove';


    /**
     * @return array|string
     */
    public function process()
    {
        if (!$this->checkPermissions()) {
            return $this->failure($this->modx->lexicon('access_denied'));
        }

        $ids = $this->modx->fromJSON($this->getProperty('ids'));
        if (empty($ids)) {
            return $this->failure($this->modx->lexicon('filequeue_item_err_ns'));
        }
        
        $path = $this->modx->getOption('filequeue_core_path', null,
                $this->modx->getOption('core_path') . 'components/filequeue/') . 'model/filequeue/';
        $fileQueue = $this->modx->getService('filequeue', 'fileQueue', $path);
        

        foreach ($ids as $id) {
            /** @var fileQueueItem $object */
            if (!$object = $this->modx->getObject($this->classKey, $id)) {
                return $this->failure($this->modx->lexicon('filequeue_item_err_nf'));
            }

            unlink($fileQueue->config['uploadPath'] . $object->filename);
            $object->remove();
        }

        return $this->success();
    }

}

return 'fileQueueItemRemoveProcessor';