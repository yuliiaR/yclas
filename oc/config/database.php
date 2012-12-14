<?php defined('SYSPATH') or die('No direct script access.');
return array
(
    'default' => array(
        'type'       => 'mysql',
        'connection' => array(
            'hostname'   => 'localhost',
            'username'   => 'root',
            'password'   => '',
            'persistent' => FALSE,
            'database'   => 'reoc',
            ),
        'table_prefix' => 'oc_',
        'charset'      => 'utf8',
        'profiling'    => TRUE,
     ),
);
