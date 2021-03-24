<?php

use PHPUnit\Framework\TestCase;
use Webshotapi\Client\Webshotapi;

require_once('config.php');


class pdfTest extends TestCase {

    function test(){
        $params = array(
            'remove_modals' => '1',
            'ads' => '1',
            'width' => '1280',
            'height' => '2040',
            'no_cache' => '',
            'scroll_to_bottom' => '0',
            'wait_for_selector' => '',
            'wait_for_xpath' => '',
            'image_quality' => '',
            'transparent_background' => '',
            'user_agent' => '',
            'accept_language' => '',
            'cookies' => [],
            'headers' => [],
            'full_page' => '',
            'timezone' => '',
            'fail_statuscode' => '',
            'extract_selectors' => '',
            'extract_words' => '',
            'extract_style' => '0',
            'extract_text' => '',
            'extract_html' => '',
            'capture_element_selector' => '',
            'thumbnail_width' => '',
            'injection_css' => '',
            'injection_js' => '',
        );

        $url = 'https://example.com';
        $url_md5 = '8f80f212b34bcf82492b5d32def93b97';

        $SAVE_PATH = '/tmp/'.uniqid().'.pdf';

        $i = new WebshotApi(API_KEY);
        $this->assertEquals(200, $i->pdf($url, $params, $SAVE_PATH));

        //calc md5
        $md5_file = md5_file($SAVE_PATH);
        $this->assertEquals($url_md5, $md5_file);

        if(file_exists($SAVE_PATH))
            unlink($SAVE_PATH);

    }

}