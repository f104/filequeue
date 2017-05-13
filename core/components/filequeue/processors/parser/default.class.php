<?php

/**
 * Дефолтный процессор для обработки файлов.
 * Проверяет его существование, открывает, считает количество строк на первом листе, пишет в лог.
 * Нужно наследовать этот класс и дописывать свой функционал.
 * В классе есть несколько функций, полезных для разбора строк.
 * 
 * 
 * short array syntax use!
 */
require dirname(dirname(dirname(__FILE__))) . '/vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php';

class defaultParser extends modProcessor {
    
    /** var fileQueueItem */
    public $file;

    public function getLanguageTopics() {
        return array('propertyset','element');
    }
    
    public function initialize() {
        // error_reporting(E_ALL);
        // ini_set('display_errors', TRUE);
        // ini_set('display_startup_errors', TRUE);
        set_time_limit(0);
        $this->modx->addPackage('filequeue', $this->properties['modelPath']);
        if (empty($this->properties['id']) or !$this->file = $this->modx->getObject('fileQueueItem', $this->properties['id'])) {
            $this->failure($this->modx->lexicon('filequeue_item_err_nf'));
            return false;
        }
        $this->file->set('status', 1);
        $this->file->save();
        return true;
    }

    public function process() {
        if ($data = $this->readFile($this->properties['uploadPath'] . $this->file->filename)) {
            $this->log('Прочитано строк: ' . count($data));
            $this->parseData($data);
            $this->file->set('status', 2);
            $this->file->set('processedon', time());
            $this->file->save();
            return $this->success();
        } else {
            return $this->failure();
        }
    }
    
    public function parseData($data) {
        return true;
    }
    
    /**
     * читает файл
     * @param string $file
     * @return false|array
     */
    public function readFile($file) {
        try {
            $cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
            $cacheSettings = array( ' memoryCacheSize ' => '8MB');
            PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
            $inputFileType = PHPExcel_IOFactory::identify($file);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objReader->setReadDataOnly(true);
            $objPHPExcel = $objReader->load($file);
        } catch(Exception $e) {
            $this->failure('Error loading file "'.pathinfo($file,PATHINFO_BASENAME).'": '.$e->getMessage());
            return false;
        }
        
        $data = array();
        
        //  Get worksheet dimensions
        $sheet = $objPHPExcel->getSheet(0); 
        $highestRow = $sheet->getHighestRow(); 
        $highestColumn = $sheet->getHighestColumn();
        
        //  Loop through each row of the worksheet in turn
        for ($row = 1; $row <= $highestRow; $row++){ 
            //  Read a row of data into an array
            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                                            NULL,
                                            TRUE,
                                            FALSE);
            $data[] = $rowData;
        }
        
        return $data;
    }
    
    /**
     * Преобразует строку в массив
     * @param string $string
     * @param string $delemiter
     * @return array
     */
    public function explode($string, $delemiter = "\n") {
        return array_map('trim', explode($delemiter, $string));
    }
    
    /**
     * ucfirst для мультибайтных строк
     * @param string $string
     * @param string $encoding
     * @return string
     */
    public function mb_ucfirst($string, $encoding = 'UTF-8') {
        $strlen = mb_strlen($string, $encoding);
        $firstChar = mb_substr($string, 0, 1, $encoding);
        $then = mb_substr($string, 1, $strlen - 1, $encoding);
        return mb_strtoupper($firstChar, $encoding) . $then;
    }
    
    
    
    public function log($msg) {
        $log = $this->modx->newObject('fileQueueLog', array(
            'fileid' => $this->file->id,
            'createdon' => time(),
            'message' => $msg,
        ));
        $log->save();
    }
    
}
return 'defaultParser';