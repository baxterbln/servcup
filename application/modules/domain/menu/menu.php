<?php

$position['user'] = 2;
$menu['user'] = array(
        'title' => lang('Domain Managment'),
        'icon' => 'th-large',
        'url' => '/domain',
        'perms' => 'manage_customer',
        'children' => array(
            array(
                'title' => lang('Domains'),
                'url' => '/domain',
                'perms' => 'manage_domains'
            ),
            array(
                'title' => lang('Subdomains'),
                'url' => '/domain/subdomains',
                'perms' => 'manage_alias'
            ),
            array(
                'title' => lang('SSL-Certificates'),
                'url' => '/domain/ssl',
                'perms' => 'manage_ssl'
            ),
            array(
                'title' => lang('Alias Domain'),
                'url' => '/domain/forwards',
                'perms' => 'manage_domains'
            )
        )
    );
