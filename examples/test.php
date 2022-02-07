<?php

include_once __DIR__ . '/../vendor/autoload.php';

use Webshotapi\Client\WebshotApiClient;
use Webshotapi\Client\Exceptions\WebshotApiClientException;

try{

$API_KEY = '7815696ecbf1c96e6894b779456d330e7815696ecbf1c96e6894b779456d330d';
$URL = 'https://example.com';

$SAVE_PATH = '/tmp/save2.json';

$params = array(
    [
        'no_cache'=>1,
        'extract_selectors'=>1,
        'extract_words' => 1,
        'extract_style' => 1,//0 - skip styles, 1 - download most import css styles, 2 - download all styles for element
    ]
);

$webshotapi = new WebshotApiClient($API_KEY);

//Download, save jpg and send to browser
$response = $webshotapi->extract($URL, $params);

// Save to file
$response->save($SAVE_PATH);

// If you want to manipulate json f
$json_data = $response->json();



} catch (WebshotApiClientException $e){
echo"ERROR: ";
echo $e->getMessage();
}