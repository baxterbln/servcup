<?php

$position['user'] = 8;
$menu['user'] = array(
        'title' => lang('Tools'),
        'icon' => 'puzzle-piece',
        'url' => '/tools',
        'perms' => 'manage_customer',
        'children' => array(
            array(
                'title' => lang('Protect folder'),
                'url' => '/tools/folders',
                'perms' => 'manage_customer'
            ),
            array(
                'title' => lang('App Installer'),
                'url' => '/tools/installer',
                'perms' => 'manage_groups'
            )
        )
    );
