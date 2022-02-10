<?php

namespace Webshotapi\Client\Tests;

use Gawsoft\RestApiClientFramework\Exceptions\ClientException;
use Webshotapi\Client\WebshotApiClient;
use Webshotapi\Client\Exceptions\WebshotApiClientException;

class ProjectsTest extends BaseCase
{

    function test_create_project(){
        $client = new WebshotApiClient($this->getApiKey());
        $resp = $client->projects()->create([
            'name' => 'hello',
            'status' => 'active'
        ]);

        $project = $resp->json();
        $this->assertEquals(201, $resp->statusCode());

        $this->assertSame($project->name,'hello');
        $this->assertSame($project->status,'active');

    }

    function test_list_projects(){
        $client = new WebshotApiClient($this->getApiKey());
        $resp = $client->projects()->list(1);
        $this->assertEquals(200, $resp->statusCode());
        $projects = $resp->json();
        $this->assertGreaterThanOrEqual(1,count($projects->projects));
    }

    function test_update_project(){
        $client = new WebshotApiClient($this->getApiKey());

        $resp = $client->projects()->list(1);
        $projects = $resp->json();

        $resp = $client->projects()->update(
            $projects->projects[0]->id,
            [
                'name' => 'hello5',
                'status' => 'disabled'
            ]
        );

        $project = $resp->json();
        $this->assertEquals(200, $resp->statusCode());

        $this->assertSame($project->name,'hello5');
        $this->assertSame($project->status,'disabled');
    }

    function test_get_project(){
        $client = new WebshotApiClient($this->getApiKey());

        $resp = $client->projects()->list(1);
        $projects = $resp->json();

        $resp = $client->projects()->get($projects->projects[0]->id);

        $project = $resp->json();
        $this->assertEquals(200, $resp->statusCode());

        $this->assertSame($project->name,'hello5');
        $this->assertSame($project->status,'disabled');
    }

    function test_delete_project(){
        $client = new WebshotApiClient($this->getApiKey());

        $resp = $client->projects()->list(1);
        $projects = $resp->json();

        $resp = $client->projects()->remove($projects->projects[0]->id);

        $this->assertEquals(200, $resp->statusCode());

        $this->expectException(ClientException::class);
        $resp = $client->projects()->get($projects->projects[0]->id);

    }

}