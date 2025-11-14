<?php

include_once __DIR__ . '/../vendor/autoload.php';

use Webshotapi\Client\WebshotApiClient;
use Webshotapi\Client\Exceptions\WebshotApiClientException;

try{

    // Paste your API key here
    $API_KEY = 'd609cd1c96102bed02739b328ff35eb9';
    $URL = 'https://example.com';

    $SAVE_PATH = '/tmp/save2.json';

    $params = array(
        [
            'url' => $URL,
            'extract_selectors'=>1,
            'extract_words' => 1,
            'extract_style' => 1,//0 - skip styles, 1 - download most import css styles, 2 - download all styles for element
        ]
    );

    $webshotapi = new WebshotApiClient($API_KEY);

    //Download, save jpg and send to browser
    $response = $webshotapi->extract($params);

    // Save to file
    $response->save($SAVE_PATH);

    // If you want to manipulate json f
    $json_data = $response->json();



} catch (WebshotApiClientException $e){
    echo"ERROR: ";
    echo $e->getMessage();
}