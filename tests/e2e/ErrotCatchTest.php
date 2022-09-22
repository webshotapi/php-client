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

            $this->assertObjectHasAttribute('status_code', $data);
            $this->assertObjectHasAttribute('screenshot_url', $data);
            $this->assertObjectHasAttribute('html', $data);
        }catch(ClientException $e){
            $resp = $e->getResponse();
            $this->assertEquals(403,$resp->statusCode());
            $this->assertEquals((object)[
                "statusCode" => 403,
                "message" => "Access denied",
                "error" => "Forbidden"
            ], $resp->json());
        }

    }
}