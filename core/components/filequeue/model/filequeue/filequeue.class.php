<?php

class fileQueue {

    /** @var modX $modx */
    public $modx;

    /**
     * @param modX $modx
     * @param array $config
     */
    function __construct(modX &$modx, array $config = array()) {
        $this->modx = & $modx;

        $corePath = $this->modx->getOption('filequeue_core_path', $config, $this->modx->getOption('core_path') . 'components/filequeue/'
        );
        $assetsUrl = $this->modx->getOption('filequeue_assets_url', $config, $this->modx->getOption('assets_url') . 'components/filequeue/'
        );
        $connectorUrl = $assetsUrl . 'connector.php';

        $uploadPath = $this->modx->getOption('filequeue_upload_path', $config, MODX_ASSETS_PATH);

        $this->config = array_merge(array(
            'assetsUrl' => $assetsUrl,
            'cssUrl' => $assetsUrl . 'css/',
            'jsUrl' => $assetsUrl . 'js/',
            'imagesUrl' => $assetsUrl . 'images/',
            'connectorUrl' => $connectorUrl,
            'corePath' => $corePath,
            'modelPath' => $corePath . 'model/',
            'chunksPath' => $corePath . 'elements/chunks/',
            'templatesPath' => $corePath . 'elements/templates/',
            'chunkSuffix' => '.chunk.tpl',
            'snippetsPath' => $corePath . 'elements/snippets/',
            'processorsPath' => $corePath . 'processors/',
            'uploadPath' => $uploadPath,
            'allowedFiles' => $this->getAllowedFiles(),
            ), $config);

        $this->modx->addPackage('filequeue', $this->config['modelPath']);
        $this->modx->lexicon->load('filequeue:default');
    }
    
    /**
     * Запуск процесса.
     * Выходит, если есть файл со статусом 1 (обрабатывается).
     * Обрабатываем самый старый файл со статусом 0 (новый).
     */
    public function run() {
        if ($this->modx->getCount('fileQueueItem', array('status' => 1))) {
            return;
        }
        $c = $this->modx->newQuery('fileQueueItem');
        $c->where(array('status' => 0));
        $c->sortby('createdon', 'ASC');
        if ($file = $this->modx->getObject('fileQueueItem', $c)) {
            $parser = $this->getParser();
            if ($parser === false) {
                $this->modx->log(modX::LOG_LEVEL_ERROR, '[fileQueue] Не могу загрузить парсер');
                return;
            }
            $response = $this->modx->runProcessor($parser, array_merge(['id' => $file->id], $this->config), [
                'processors_path' => $this->config['processorsPath'] . 'parser/']);
            if ($response->isError()) {
                $this->modx->log(MODX::LOG_LEVEL_ERROR, '[fileQueue] ' . $response->getMessage());
            }
        }
        return;
    }
    
    /**
     * Проверяет наличие парсера и возвращает его имя или ложь
     * @return boolean or string
     */
    public function getParser() {
        $parser = $this->modx->getOption('filequeue_parser_processor', null, 'default');
        if (file_exists($this->config['processorsPath'] . 'parser/' . $parser . '.class.php')) {
            return $parser;
        }
        return false;
    }

    /**
     * Allowed file extension
     * 
     * @return array
     */
    public function getAllowedFiles() {
        $allowedFiles = $this->modx->getOption('filequeue_allowed_files');
        if (!empty($allowedFiles)) {
            $allowedFiles = array_map('trim', explode(',', $allowedFiles));
        } else {
            $allowedFiles = array();
        }
        return $allowedFiles;
    }

    /**
     * Pathinfo function for cyrillic files
     *
     * @param $path
     * @param string $part
     *
     * @return array
     */
    public function pathinfo($path, $part = '') {
        // Russian files
        if (preg_match('#[а-яё]#im', $path)) {
            $path = strtr($path, array('\\' => '/'));

            preg_match("#[^/]+$#", $path, $file);
            preg_match("#([^/]+)[.$]+(.*)#", $path, $file_ext);
            preg_match("#(.*)[/$]+#", $path, $dirname);

            $info = array(
                'dirname' => $dirname[1] ? : '.',
                'basename' => $file[0],
                'extension' => (isset($file_ext[2])) ? $file_ext[2] : '',
                'filename' => (isset($file_ext[1])) ? $file_ext[1] : $file[0],
            );
        } else {
            $info = pathinfo($path);
        }

        return !empty($part) && isset($info[$part]) ? $info[$part] : $info;
    }

}