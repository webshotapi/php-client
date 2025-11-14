<?php

include_once 'vendor/autoload.php';

use Webshotapi\Client\WebshotApiClient;
use Webshotapi\Client\Exceptions\WebshotApiClientException;

try{

    // Paste your API key here
    $API_KEY = 'd609cd1c96102bed02739b328ff35eb9';
    $URL = 'https://example.com';

    $SAVE_PATH = '/tmp/save2.json';

    $webshotapi = new WebshotApiClient($API_KEY);

    //Download, save jpg and send to browser
    $response = $webshotapi->extract([
        'url' => $URL,
        'extract_selectors' => true,
        'extract_words' => true,
        'extract_style' => 1,//0 - skip styles, 1 - download most import css styles, 2 - download all styles for element
    ]);

    // Save to file
    $response->save($SAVE_PATH);

    // Parse response
    $json_data = $response->json();
    var_dump($json_data);


} catch (WebshotApiClientException $e){
    echo"ERROR: ";
    echo $e->getMessage();
}