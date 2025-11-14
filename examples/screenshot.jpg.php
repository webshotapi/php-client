<?php

include_once 'vendor/autoload.php';

use Webshotapi\Client\Exceptions\WebshotApiClientException;
use Webshotapi\Client\WebshotApiClient;

try{
    // Paste your API key here
    $API_CLIENT = 'd609cd1c96102bed02739b328ff35eb9';

    $client = new WebshotApiClient($API_CLIENT);
    $response = $client->screenshot([
        "url" => 'https://example.com',
        'viewport_width' => 1024,
        'full_page' => true,
        'remove_modals' => true, // Remove cookies popup
    ]);

    $response->save('/tmp/screenshot.jpg');

}catch(WebshotApiClientException $e) {
    echo 'Client error';
    echo $e->getMessage();
}catch(\Exception $e) {
    echo 'Internal error';
    echo $e->getMessage();
}