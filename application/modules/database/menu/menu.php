<?php

$position['user'] = 3;
$menu['user'] = array(
    'title' => lang('Databases'),
    'icon' => 'database',
    'url' => '/database',
    'perms' => 'manage_database',
    'children' => array(
        array(
            'title' => lang('Database user'),
            'url' => '/database/user',
            'perms' => 'manage_database',
        ),
        array(
            'title' => lang('Manage databases'),
            'url' => '/database',
            'perms' => 'manage_database',
        ),
        array(
            'title' => lang('phpMyAdmin'),
            'url' => '/phpmyadmin',
            'perms' => 'manage_database',
        ),
    ),
);
