<?php

namespace Webshotapi\Client\Tests;

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
            ],
            'image',
            'jpg'
        );

        $path = '/tmp/test.jpg';
        $resp->save($path);

        $image1 = new \Imagick($path);
        $image_correct = new \Imagick(__DIR__ . '/../correct-files/example.jpeg');

        $result =  $image_correct->compareImages($image1, \Imagick::METRIC_MEANSQUAREERROR);;

        $this->assertEquals(200, $resp->statusCode());
        $this->assertEquals('image/jpeg', $resp->contentType());

        $this->assertFileExists($path);
        $this->assertGreaterThanOrEqual(1, $result);
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
                'width' => 1024
            ],
            'image',
            'png'
        );

        $path = '/tmp/test.png';
        $resp->save($path);

        $image1 = new \Imagick($path);
        $image_correct = new \Imagick(__DIR__ . '/../correct-files/example.jpeg');

        $result =  $image_correct->compareImages($image1, \Imagick::METRIC_MEANSQUAREERROR);;

        $this->assertEquals(200, $resp->statusCode());
        $this->assertEquals('image/png', $resp->contentType());
        $this->assertFileExists($path);
        $this->assertGreaterThanOrEqual(1, $result);
    }
}