<?php

namespace Webshotapi\Client\Tests;

use PHPUnit\Framework\TestCase;
use Webshotapi\Client\WebshotApiClient;

class BaseCase extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        if (!getenv('WEBSHOTAPI_KEY')) {
            throw new \Exception('No set api key for E2E test');
        }
    }

    public function getTestProjects(){
        $client = new WebshotApiClient($this->getApiKey());
        $resp = $client->projects()->list(1);
        return $resp->json()->projects;
    }


}