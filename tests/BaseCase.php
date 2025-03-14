<?php

namespace Webshotapi\Client\Tests;

use PHPUnit\Framework\TestCase;
use Webshotapi\Client\WebshotApiClient;

class BaseCase extends TestCase
{
    private $api_key;

    protected function setUp(): void
    {
        parent::setUp();
        if (!getenv('WEBSHOTAPI_TEST_API_KEY')) {
            throw new \Exception('No set api key for E2E test');
        }

        $this->api_key = getenv('WEBSHOTAPI_TEST_API_KEY');
    }

    function getApiKey(){
        return $this->api_key;
    }

    public function getTestProjects(){
        $client = new WebshotApiClient($this->getApiKey());
        $resp = $client->projects()->list(1);
        return $resp->json()->projects;
    }


}