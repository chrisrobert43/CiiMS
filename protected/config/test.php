<?php
return array(
    'name' => 'CiiMS',
    // Overload modules for Codeception
    // Not sure why this has to be as explicity as it is
    'modules' => array(
        'dashboard',
        'api',
        'hybridauth',
        'install'
    ),
    'components' => array(
        // Override CHttpRequest for codeception
        'request' => array(
            'class' => 'CodeceptionHttpRequest'
        ),
        'db' => array(
            'class' => 'CDbConnection',
                'connectionString' => 'mysql:host=localhost;dbname=ciims_test',
                'emulatePrepare' => true,
                'username' => 'root',
                'password' => '',
                'charset' => 'utf8',
                'schemaCachingDuration' => '3600',
                'enableProfiling' => true,
                'enableParamLogging' => true
        ),
        'cache' => array(
            'class' => 'CFileCache',
        ),
    ),
    'params' => array(
        'encryptionKey' => 'ag93ba23r'
    )
);
