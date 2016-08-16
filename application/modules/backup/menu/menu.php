<?php
$position['user'] = 7;
$menu['user'] = array(
        'title' => lang('Backup'),
        'icon' => 'download',
        'url' => '/backup',
        'perms' => 'manage_customer',
        'children' => array(
            array(
                'title' => lang('View backups'),
                'url' => '/backup',
                'perms' => 'manage_customer'
            )
        )
);
