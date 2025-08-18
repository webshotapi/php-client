<?php

namespace Webshotapi\Client\Tests;

use Webshotapi\Client\WebshotApiClient;

class ScrollingTest extends BaseCase
{
    function test_take_scrolling(){
        $client = new WebshotApiClient($this->getApiKey());
        $resp = $client->video(
            [
                'url' => 'https://example.com',
                'scrolling_enable' => true,
                'block_ads' => true,
                'remove_modals' => true,
                'viewport_width' => 1024
            ]
        );

        $path = '/tmp/test.mp4';
        $resp->save($path);

        $this->assertEquals(200, $resp->statusCode());
        $this->assertEquals('video/mp4', $resp->contentType());

        $this->assertFileExists($path);
    }


    function test_take_video_json(){
        $client = new WebshotApiClient($this->getApiKey());
        $resp = $client->videoJson(
            [
                'url' => 'https://example.com',
                'scrolling_enable' => true,
                'block_ads' => true,
                'remove_modals' => true,
                'viewport_width' => 1024,
                'video_format' => 'webm'
            ]
        );

        $this->assertEquals(200, $resp->statusCode());
        $this->assertEquals('application/json; charset=utf-8', $resp->contentType());

    }
}