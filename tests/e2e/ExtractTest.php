<?php

namespace Webshotapi\Client\Tests;

use Webshotapi\Client\WebshotApiClient;

class ExtractTest extends BaseCase
{
    function test_take_extract()
    {
        $client = new WebshotApiClient($this->getApiKey());
        $resp = $client->extract(
            'https://example.com',
            [
                'ads' => true,
                'remove_modals' => true,
                'width' => 1024
            ]
        );

        $data = $resp->json();
        $this->assertEquals(200, $resp->statusCode());

        $this->assertObjectHasProperty('status_code', $data);
        $this->assertObjectHasProperty('html', $data);

    }
}