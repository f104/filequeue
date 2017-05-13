<?php

class fileQueueItemGetListProcessor extends modObjectGetListProcessor
{
    public $objectType = 'fileQueueItem';
    public $classKey = 'fileQueueItem';
    public $defaultSortField = 'createdon';
    public $defaultSortDirection = 'DESC';
    //public $permission = 'list';


    /**
     * We do a special check of permissions
     * because our objects is not an instances of modAccessibleObject
     *
     * @return boolean|string
     */
    public function beforeQuery()
    {
        if (!$this->checkPermissions()) {
            return $this->modx->lexicon('access_denied');
        }

        return true;
    }
    
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $c->leftJoin('modUser', 'modUser', array('modUser.id = fileQueueItem.createdby'));
        $c->select('fileQueueItem.*, modUser.username');
        return $c;
    }


    /**
     * @param xPDOObject $object
     *
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        $array = $object->toArray();
        $array['actions'] = array();

        // view log
        $array['actions'][] = array(
            'cls' => '',
            'icon' => 'icon icon-edit',
            'title' => $this->modx->lexicon('filequeue_item_view_log'),
            //'multiple' => $this->modx->lexicon('filequeue_items_update'),
            'action' => 'viewLog',
            'button' => true,
            'menu' => true,
        );

        // Remove
        $array['actions'][] = array(
            'cls' => '',
            'icon' => 'icon icon-trash-o action-red',
            'title' => $this->modx->lexicon('filequeue_item_remove'),
            'multiple' => $this->modx->lexicon('filequeue_items_remove'),
            'action' => 'removeItem',
            'button' => true,
            'menu' => true,
        );

        return $array;
    }

}

return 'fileQueueItemGetListProcessor';