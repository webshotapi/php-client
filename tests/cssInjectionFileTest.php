<?php

use PHPUnit\Framework\TestCase;

require_once('config.php');

class cssInjectionFileTest extends TestCase {

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
            'extract_selectors' => '1',
            'extract_words' => '1',
            'extract_style' => '1',
            'extract_text' => '1',
            'extract_html' => '1',
            'capture_element_selector' => '',
            'thumbnail_width' => '',
            'injection_css'=>'',
            'injection_css_url' => '',
        );

        $url = 'https://example.com';
        $url_md5 = 'cb5af5e5756e6cfa0f0e6e22b76daf50';

        $SAVE_PATH = '/tmp/'.uniqid().'.jpg';

        $i = new Webshotapi\Client\Webshotapi(API_KEY);
        $this->assertEquals(200, $i->screenshot_jpg($url, $params, $SAVE_PATH));

        //calc md5
        $md5_file = md5_file($SAVE_PATH);

        $this->assertEquals($url_md5, $md5_file);

        if(file_exists($SAVE_PATH))
            unlink($SAVE_PATH);

    }

}