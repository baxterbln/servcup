<?php

$position['user'] = 3;
$menu['user'] = array(
    'title' => lang('Databases'),
    'icon' => 'database',
    'url' => '/database',
    'perms' => 'manage_customer',
    'children' => array(
        array(
            'title' => lang('Database user'),
            'url' => '/database/user',
            'perms' => 'manage_customer',
        ),
        array(
            'title' => lang('Manage databases'),
            'url' => '/database',
            'perms' => 'manage_groups',
        ),
        array(
            'title' => lang('phpMyAdmin'),
            'url' => '/database/phpmyadmin',
            'perms' => 'manage_groups',
        ),
    ),
);
