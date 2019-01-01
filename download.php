<?php

require_once 'dav_client.php';

# get path
$file = $config['uri_prefix'] . $_GET['file'];
$file = \Sabre\HTTP\encodePath($file);

# load file
$response = $client->request('GET', $file);

# get file name
$parts = explode('/', $file);
$filename = $parts[count($parts) - 1];

# display
header('Content-type: ' . $response['headers']['content-type'][0]);
header('Content-disposition: inline;filename="' . $filename . '"');
header('Content-Transfer-Encoding: binary');
header('Content-Length: ' . $response['headers']['content-length'][0]);
header('Accept-Ranges: bytes');

echo $response['body'];
