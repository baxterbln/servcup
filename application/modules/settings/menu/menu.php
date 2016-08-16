<?php
$position['admin'] = 4;

$menu['admin'] = array(
        'title' =>'Einstellungen',
        'icon' => 'cogs',
        'url' => '/settings',
        'perms' => 'manage_server',
        'children' => array(
            array(
                'title' => 'Server',
                'url' => '/settings/server'
            ),
            array(
                'title' => 'Webseiten',
                'url' => '/system/server'
            ),
            array(
                'title' => 'Mail',
                'url' => '/system/ip'
            ),
            array(
                'title' => 'Domains',
                'url' => '/system/php'
            ),
            array(
                'title' => 'DNS',
                'url' => '/system/php'
            ),
            array(
                'title' => 'Sonstige',
                'url' => '/system/php'
            )
        )
    );
