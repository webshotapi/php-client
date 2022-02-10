<?php

include_once __DIR__ . '/../vendor/autoload.php';

use Webshotapi\Client\Exceptions\WebshotApiClientException;
use Webshotapi\Client\WebshotApiClient;

try{
    $API_CLIENT = '7815696ecbf1c96e6894b779456d330e7815696ecbf1c96e6894b779456d330d';
    $client = new WebshotApiClient($API_CLIENT);
    $new_project = $client->projects()->create([
        "name" => "Test project",
        "status" => "active"
    ])->json();

    $new_urls = $client->projectsUrl()->create(
        $new_project->id,
        [
            "urls" => [
                "https://example.com",
                "https://example.com/test"
            ],
            "command" => "screenshot",
            "params" => [
                  "image_type" => "png",
                  "remove_modals" => true,
                  "ads" => true,
                  "width" => 960,
                  "thumbnail_width" => 256,
                  "height" => 1440,
                  "no_cache" =>  true,
            ]
        ]
    )->json();

    var_dump($new_urls);

}catch(WebshotApiClientException $e) {
    echo 'Client error';
    echo $e->getMessage();
}catch(\Exception $e) {
    echo 'Internal error';
    echo $e->getMessage();
}