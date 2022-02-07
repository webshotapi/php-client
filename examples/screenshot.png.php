<?php

include_once __DIR__ . '/../vendor/autoload.php';

use Webshotapi\Client\Exceptions\WebshotApiClientException;
use Webshotapi\Client\WebshotApiClient;

try{
    $API_CLIENT = '7815696ecbf1c96e6894b779456d330e7815696ecbf1c96e6894b779456d330d';
    $client = new WebshotApiClient($API_CLIENT);
    $response = $client->screenshot('https://example.com', [
        'width' => 1024,
        'full_page' => true
    ],'image','png');

    $response->save('/tmp/screenshot.png');

}catch(WebshotApiClientException $e) {
    echo 'Client error';
    echo $e->getMessage();
}catch(\Exception $e) {
    echo 'Internal error';
    echo $e->getMessage();
}