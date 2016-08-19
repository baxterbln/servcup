<?php

$position['user'] = 5;
$menu['user'] = array(
        'title' => lang('Email'),
        'icon' => 'envelope',
        'url' => '/mail',
        'perms' => 'manage_mail',
        'children' => array(
            array(
                'title' => lang('Mail accounts'),
                'url' => '/mail',
                'perms' => 'manage_mail'
            ),
            array(
                'title' => lang('Mail forwards'),
                'url' => '/mail/forwards',
                'perms' => 'manage_mailfwd'
            )
        )
    );
