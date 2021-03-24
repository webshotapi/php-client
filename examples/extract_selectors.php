<?php
/**
 * WEBSHOTAPI.com
 * https://webshotapi.com/docs/
 * Extract from website after browser rendering
 * - all html elements selectors with css style, positions, width, height, xpath, class names, ids and itemprop
 * - all words with position, x,y, word num in parent, offet in px from last word
 */

require_once __DIR__ ."/../vendor/autoload.php";

use Webshotapi\Client\Webshotapi;
use Webshotapi\Client\Webshotapi_exception;
try{
   
    ini_set('display_errors',1);
    ini_set("memory_limit","256M");
    
    error_reporting(E_ALL);
    
    $API_KEY = 'YOU_API_KEY';
    $URL = 'PUT_LINK_TO_WEBSITE_HERE';
    
    $SAVE_PATH = '/tmp/save.json';
    

    $webshotapi = new Webshotapi($API_KEY);

    
    if($webshotapi->extract($URL, [
        'no_cache'=>1,
        'extract_selectors'=>1,
        'extract_words' => 1,
        'extract_style' => 1,//0 - skip styles, 1 - download most import css styles, 2 - download all styles for element
    ]) == 200){
        echo "File downloaded";    
    }else{
        echo "Error with download file";
    }
    
    //get data
    $json = $webshotapi->json();
    
    echo '<pre>';
    //selectors
    //print_r($json->selectors);
    
    //words
    print_r($json->words);
    
    echo '</pre>';
    
} catch (Webshotapi_exception $e){
    echo"ERROR";
    echo $e->getMessage();
}
