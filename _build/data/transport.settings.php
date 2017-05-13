<?php
/** @var modX $modx */
/** @var array $sources */

$settings = array();

$tmp = array(
    'core_path' => array(
        'xtype' => 'textfield',
        'value' => '{base_path}fileQueue/core/components/filequeue/',
        'area' => 'filequeue_main',
    ),
    'assets_path' => array(
        'xtype' => 'textfield',
        'value' => '{base_path}fileQueue/assets/components/filequeue/',
        'area' => 'filequeue_main',
    ),
    'assets_url' => array(
        'xtype' => 'textfield',
        'value' => '/fileQueue/assets/components/filequeue/',
        'area' => 'filequeue_main',
    ),    
    'upload_path' => array(
        'xtype' => 'textfield',
        'value' => '{base_path}fileQueue/assets/components/filequeue/upload/',
        'area' => 'filequeue_main',
    ),
    'allowed_files' => array(
        'xtype' => 'textfield',
        'value' => 'xls,xlsx,csv',
        'area' => 'filequeue_main',
    ),
    'parser_processor' => array(
        'xtype' => 'textfield',
        'value' => 'default',
        'area' => 'filequeue_main',
    ),
);

foreach ($tmp as $k => $v) {
    /** @var modSystemSetting $setting */
    $setting = $modx->newObject('modSystemSetting');
    $setting->fromArray(array_merge(
        array(
            'key' => 'filequeue_' . $k,
            'namespace' => PKG_NAME_LOWER,
        ), $v
    ), '', true, true);

    $settings[] = $setting;
}
unset($tmp);

return $settings;
