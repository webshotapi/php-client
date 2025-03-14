<?php

namespace Webshotapi\Client\Tests;

use Webshotapi\Client\Exceptions\WebshotApiClientException;
use Webshotapi\Client\WebshotApiClient;

class ScreenshotTest extends BaseCase
{
    function test_take_screenshot(){
        $client = new WebshotApiClient($this->getApiKey());
        $resp = $client->screenshot(
            'https://example.com',
            [
                'ads' => true,
                'remove_modals' => true,
                'width' => 1024
            ]
        );

        $path = '/tmp/test.jpg';
        $resp->save($path);

        $this->assertEquals(200, $resp->statusCode());
        $this->assertEquals('image/jpeg', $resp->contentType());

        $this->assertFileExists($path);
    }

    function test_should_catch_exception(){
        $client = new WebshotApiClient($this->getApiKey());
        $this->expectException(WebshotApiClientException::class);
        $resp = $client->download(
            'https://example.com/sdfsfsd/fdsafsa',
            '/tmp/aaa.jpg'
        );

    }

    function test_take_screenshot_pdf(){
        $client = new WebshotApiClient($this->getApiKey());
        $resp = $client->pdf(
            'https://example.com',
            [
                'ads' => true,
                'remove_modals' => true,
                'width' => 1024
            ]
        );

        $path = '/tmp/test.pdf';
        $resp->save($path);

        $this->assertEquals(200, $resp->statusCode());
        $this->assertEquals('application/pdf', $resp->contentType());

        $this->assertFileExists($path);
        $this->assertGreaterThanOrEqual(10240, filesize($path));
    }

    function test_take_screenshot_png(){
        $client = new WebshotApiClient($this->getApiKey());
        $resp = $client->screenshot(
            'https://example.com',
            [
                'ads' => true,
                'remove_modals' => true,
                'width' => 1024,
                'image_type' => 'png'
            ]
        );

        $path = '/tmp/test.png';
        $resp->save($path);

        $this->assertEquals(200, $resp->statusCode());
        $this->assertEquals('image/png', $resp->contentType());
        $this->assertFileExists($path);
    }

    function test_take_screenshot_webp(){
        $client = new WebshotApiClient($this->getApiKey());
        $resp = $client->screenshot(
            'https://example.com',
            [
                'ads' => true,
                'remove_modals' => true,
                'width' => 1024,
                'image_type' => 'webp'
            ]
        );

        $path = '/tmp/test.webp';
        $resp->save($path);

        $this->assertEquals(200, $resp->statusCode());
        $this->assertEquals('image/webp', $resp->contentType());
        $this->assertFileExists($path);
    }

    function test_take_screenshot_json(){
        $client = new WebshotApiClient($this->getApiKey());
        $resp = $client->screenshotJson(
            'https://example.com',
            [
                'ads' => true,
                'remove_modals' => true,
                'width' => 1024,
                'image_type' => 'webp'
            ]
        );

        $this->assertEquals(200, $resp->statusCode());
        $this->assertEquals('application/json; charset=utf-8', $resp->contentType());

    }
}