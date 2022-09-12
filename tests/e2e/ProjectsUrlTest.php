<?php

namespace Webshotapi\Client\Tests;

use Webshotapi\Client\WebshotApiClient;

class ProjectsUrlTest extends BaseCase
{

    public function setUp(): void{
        // Create test project
        $client = new WebshotApiClient($this->getApiKey());
        $resp = $client->projects()->create([
            'name' => 'hello',
            'status' => 'active'
        ]);
    }

    function test_create_project_url(){
        $testProjects = $this->getTestProjects();

        $client = new WebshotApiClient($this->getApiKey());
        $resp = $client->projectsUrl()->create(
            $testProjects[0]->id,
            [
                'urls' => [
                    'https://example.com',
                    'https://example.com/blog'
                ],
                "command" => "screenshot",
                'params' => [
                    'ads' => true,
                    'remove_modals' => true
                ]
            ]
        );

        $response = $resp->json();
        $this->assertEquals(201, $resp->statusCode());

        $this->assertIsArray($response);
        $this->assertCount(2, $response);
    }

    function test_list_projects(){
        $testProjects = $this->getTestProjects();

        $client = new WebshotApiClient($this->getApiKey());
        $resp = $client->projectsUrl()->list($testProjects[0]->id);

        $response = $resp->json();
        $this->assertEquals(200, $resp->statusCode());

        $this->assertIsArray($response->urls);
        $this->assertGreaterThanOrEqual(2, count($response->urls));
    }

    function test_get_projects(){
        $testProjects = $this->getTestProjects();
        $client = new WebshotApiClient($this->getApiKey());

        $respList = $client->projectsUrl()->list($testProjects[0]->id);
        $exampleid = $respList->json()->urls[0]->id;
        $resp = $client->projectsUrl()->get($testProjects[0]->id, $exampleid);

        $response = $resp->json();
        $this->assertEquals(200, $resp->statusCode());

        $this->assertSame($exampleid, $response->id);
    }

    function test_process_again_project_url(){
        $testProjects = $this->getTestProjects();
        $client = new WebshotApiClient($this->getApiKey());

        $respList = $client->projectsUrl()->list($testProjects[0]->id);
        $exampleid = $respList->json()->urls[0]->id;

        $resp = $client->projectsUrl()->processAgain($testProjects[0]->id, $exampleid);
        $response = $resp->json();

        $this->assertEquals(200, $resp->statusCode());
        $this->assertSame($exampleid, $response->id);
        $this->assertSame(0, $response->errors_sum);
        $this->assertSame("waiting_for_execute", $response->status);
    }

    function test_download_url(){
        $client = new WebshotApiClient($this->getApiKey());
        $path = '/tmp/save.pdf';
        $res = $client->projectsUrl()->downloadUrl('https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf', $path);
        $this->assertSame(200, $res->statusCode());
        $this->assertGreaterThanOrEqual(13264, filesize($path));
        $this->assertTrue(file_exists($path));
    }

    function test_delete_projects(){
        $testProjects = $this->getTestProjects();
        $client = new WebshotApiClient($this->getApiKey());

        $respList = $client->projectsUrl()->list($testProjects[0]->id);
        $exampleid = $respList->json()->urls[0]->id;
        $resp = $client->projectsUrl()->remove($testProjects[0]->id, $exampleid);

        $response = $resp->json();
        $this->assertEquals(200, $resp->statusCode());

        $this->assertSame(true, $response->deleted);
    }
}