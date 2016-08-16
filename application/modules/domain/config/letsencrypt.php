<?php
defined('BASEPATH') OR exit('No direct script access allowed');

# Develop
$config['certificate_path'] = '/usr/local/servcup/conf/letsencrypt/';
$config['certificate_api'] = 'http://boulder.cconnect.es:4000';
$config['certificate_license'] = 'http://boulder:4000/terms/v1';

# Live
//$config['certificate_path'] = '/usr/local/servcup/conf/letsencrypt/';
//$config['certificat_api'] = 'https://acme-v01.api.letsencrypt.org';
//$config['certificate_license'] = 'https://letsencrypt.org/documents/LE-SA-v1.1.1-August-1-2016.pdf';
