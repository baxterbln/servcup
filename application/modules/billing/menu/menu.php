<?php
$position['reseller'] = 3;
$position['admin'] = 3;

$menu['admin'] = array(
        'title' =>'Buchhaltung',
        'icon' => 'credit-card',
        'url' => '/system',
        'perms' => 'access_billing',
        'children' => array(
            array(
                'title' => 'Bestellungen',
                'perms' => 'access_orders',
                'url' => '/system/server'
            ),
            array(
                'title' => 'Rechnungen',
                'perms' => 'access_invoice',
                'url' => '/system/php'
            ),
            array(
                'title' => 'Produkte',
                'perms' => 'access_products',
                'url' => '/system/ip'
            ),
            array(
                'title' => 'Offene Posten',
                'perms' => 'access_payments',
                'url' => '/system/ip'
            ),
            array(
                'title' => 'Auswertung',
                'perms' => 'access_overview',
                'url' => '/system/ip'
            )
        )
    );
