<?php

namespace Webshotapi\Client\Tests;

use Gawsoft\RestApiClientFramework\Exceptions\ClientException;
use Webshotapi\Client\WebshotApiClient;

class ErrotCatchTest extends BaseCase
{
    function test_take_extract()
    {
        try {
            $client = new WebshotApiClient('WRONG_API_KEY');
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
            $this->assertObjectHasProperty('screenshot_url', $data);
            $this->assertObjectHasProperty('html', $data);

        }catch(ClientException $e){
            $resp = $e->getResponse();
            $this->assertEquals(401,$resp->statusCode());
            $this->assertEquals((object)[
                "statusCode" => 401,
                "message" => "Access denied",
                "error" => "Unauthorized"
            ], $resp->json());
        }

    }
}