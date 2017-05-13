<?php

class fileQueueItemCreateProcessor extends modObjectCreateProcessor
{
    public $objectType = 'fileQueueItem';
    public $classKey = 'fileQueueItem';
    public $languageTopics = array('filequeue');
    //public $permission = 'create';

    /** @var string $tf Temporary file name */
    public $tf;
    
    /** @var string $ext uploaded file extension */
    public $ext;
    
    /** @var fileQueue $fileQueue */
    public $fileQueue;
    
    public function initialize() {
        $path = $this->modx->getOption('filequeue_core_path', null,
                $this->modx->getOption('core_path') . 'components/filequeue/') . 'model/filequeue/';
        $this->fileQueue = $this->modx->getService('filequeue', 'fileQueue', $path);
        return parent::initialize();
    }

    /**
     * @return bool
     */
    public function beforeSet()
    {
        $file = $this->getProperty('file');
        /* verify file exists */
        if (empty($file) or !empty($file['error'])) {
            $this->modx->error->addField('file', $this->modx->lexicon('filequeue_item_err_upload'));
        } elseif (empty($_FILES['file']) or !is_uploaded_file($_FILES['file']['tmp_name'])) {
            $this->modx->error->addField('file', $this->modx->lexicon('filequeue_item_err_upload'));
        } else {
            $fileExt = $this->fileQueue->pathinfo($_FILES['file']['name'], 'extension');
            if (empty($fileExt) or !in_array($fileExt, $this->fileQueue->config['allowedFiles'])) {
                $this->modx->error->addField('file', $this->modx->lexicon('filequeue_item_err_ext'));                
            } else {
                $this->tf = tempnam(MODX_BASE_PATH, 'fq_');
                $this->ext = $fileExt;
                $user = $this->modx->getAuthenticatedUser($this->modx->context->key);
                $this->object->name = $_FILES['file']['name'];
                $this->object->createdon = time();
                $this->object->createdby = $user->id;
                move_uploaded_file($_FILES['file']['tmp_name'], $this->tf);
            }
        }

        return parent::beforeSet();
    }
    
    public function afterSave() { 
        rename($this->tf, $this->fileQueue->config['uploadPath'] . $this->object->id . '.' . $this->ext);
        $this->object->filename = $this->object->id . '.' . $this->ext;
        $this->object->save();
        return true;        
    }
    
}

return 'fileQueueItemCreateProcessor';