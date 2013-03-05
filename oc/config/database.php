<?php defined('SYSPATH') or die('No direct script access.');
return array
(
    'default' => array(
        'type'       => 'mysql',
        'connection' => array(
            'hostname'   => 'localhost',
            // 'hostname' => 'mysql.hostinger.es', // production
            'username'   => 'root',
            // 'username' => 'u808612650_reoc', // production
            'password'   => '',
            // 'password' => 'reoc12345', // production
            'persistent' => FALSE,
            'database'   => 'reoc',
            // 'database' => 'u808612650_reoc' // production
            ),
        'table_prefix' => 'oc_',
        'charset'      => 'utf8',
        'profiling'    => TRUE,
     ),
);
