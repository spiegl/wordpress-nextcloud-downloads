<?php

require_once 'vendor/autoload.php';
require_once 'config.php';

$settings = array(
    'baseUri' => $config['baseUri'],
    'userName' => $config['userName'],
    'password' => $config['password'],
);

$client = new \Sabre\DAV\Client($settings);
