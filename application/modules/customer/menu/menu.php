<?php
$position['reseller'] = 2;
$position['admin'] = 2;

$menu['admin'] = array(
        'title' =>'Kunden',
        'icon' => 'users',
        'url' => '/customer',
        'perms' => 'manage_customer',
        'children' => array(
            array(
                'title' => 'Kunden verwalten',
                'url' => '/customer',
                'perms' => 'manage_customer'
            ),
            array(
                'title' => 'Gruppen',
                'url' => '/customer/group',
                'perms' => 'manage_groups'
            )
        )
    );
