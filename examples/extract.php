<?php

include_once __DIR__ . '/../vendor/autoload.php';

use Webshotapi\Client\Exceptions\WebshotApiClientException;
use Webshotapi\Client\WebshotApiClient;

try{
    $API_CLIENT = '7815696ecbf1c96e6894b779456d330e7815696ecbf1c96e6894b779456d330d';
    $client = new WebshotApiClient($API_CLIENT);
    $response = $client->extract('https://example.com', [
          "extract_text" => true,
          "extract_html" => true,
          "extract_selectors" => true,
          "extract_words" => true,
          "extract_style" => 1,
    ]);

    // Save json file
    $response->save('/tmp/extract.json');

    // show json value
    print_r($response->json());

}catch(WebshotApiClientException $e) {
    echo 'Client error';
    echo $e->getMessage();
}catch(\Exception $e) {
    echo 'Internal error';
    echo $e->getMessage();
}