<?php
/**
 * WEBSHOTAPI.com
 * https://webshotapi.com/docs/
 * Take screenshot of website and save to PNG
 */
require_once __DIR__ ."/../vendor/autoload.php";

use Webshotapi\Client\Webshotapi;
use Webshotapi\Client\Webshotapi_exception;
try{
 
    ini_set('display_errors',1);
    error_reporting(E_ALL);
    
    $API_KEY = 'YOU_API_KEY';
    $URL = 'PUT_LINK_TO_WEBSITE_HERE';
    
    $SAVE_PATH = '/tmp/save.png';
    
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
    
    $webshotapi = new Webshotapi($API_KEY);


    //Download,save pdf and send to browser
    if($webshotapi->screenshot_png($URL, $params, $SAVE_PATH, true) == 200){
        echo "File downloaded";    
    }else{
        echo "Error with download file";
    }

 
    
} catch (Webshotapi_exception $e){
    echo"ERROR";
    echo $e->getMessage();
}
