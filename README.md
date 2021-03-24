# WebShotApi.com API client for PHP

Take screenshot and save image in JPG, PNG, PDF. You can also extract selectors for all HTML elements with coordinates and css styles after browser rendering.
In our api you can create project and send all you urls to queue. Our server will do all the work for you

Full documentation about our api you can find in this website [Website screenshot API DOCS](https://webshotapi.com/docs/)

## Installation

```bash
composer require webshotapi/client
```


## API KEY
Api key you can generate after register.
[https://webshotapi.com/dashboard/api/](https://webshotapi.com/dashboard/api/)

## Usage

### Take screenshot and save jpg to file
```php
<?php

require_once __DIR__ ."/../vendor/autoload.php";

use Webshotapi\Client\Webshotapi;
use Webshotapi\Client\Webshotapi_exception;

try{
   
    ini_set('display_errors',1);
    error_reporting(E_ALL);
    
    $API_KEY = 'YOU_API_KEY';
    $URL = 'PUT_LINK_TO_WEBSITE_HERE';
    
    $SAVE_PATH = '/tmp/save2.jpg';
    
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


    //Download, save jpg and send to browser
    if($webshotapi->screenshot_jpg($URL, $params, $SAVE_PATH) == 200){
        echo "File downloaded";    
    }else{
        echo "Error with download file";
    }

    
} catch (Webshotapi_exception $e){
    echo"ERROR: ";
    echo $e->getMessage();
}

```

### Take screenshot and save PDF to file
You can covert your html page to invoice in PDF.
```php
<?php

require_once __DIR__ ."/../vendor/autoload.php";

use Webshotapi\Client\Webshotapi;
use Webshotapi\Client\Webshotapi_exception;

try{
   
    ini_set('display_errors',1);
    error_reporting(E_ALL);
    
    $API_KEY = 'YOU_API_KEY';
    $URL = 'PUT_LINK_TO_WEBSITE_HERE';
    
    $SAVE_PATH = '/tmp/save2.jpg';
    
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


    //Download, save jpg and send to browser
    if($webshotapi->pdf($URL, $params, $SAVE_PATH) == 200){
        echo "File downloaded";    
    }else{
        echo "Error with download file";
    }

    
} catch (Webshotapi_exception $e){
    echo"ERROR: ";
    echo $e->getMessage();
}
```

### Extract words map and HTML elements with css styles after rendering
Unique software to extract all selectors for HTML elements from website with css styles after browser rendering. Additionally you can extract all words with position (x,y,width, height, offset from previous word). Thank that you can build words map of website.

#### Sample script:
```php
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
```
#### Results

```json
{
  "selectors": [
    {
      "xpath": "/html/body",
      "x": 1212,
      "y": 17,
      "w": 117,
      "h": 25,
      "style": {
        "visibility": "visible",
        "display": "inline",
        "fontWeight": "400",
        "backgroundImage": "none",
        "cursor": "pointer",
        "fontSize": "22px",
        "color": "rgb(255, 255, 255)",
        "position": "static",
        "textDecoration": "none solid rgb(255, 255, 255)",
        "text-decoration-line": "none",
        "backgroundColor": "rgba(0, 0, 0, 0)"
      },
      "class": ".col-12 col-sm-12",
      "id": "#price",
      "itemprop": "price"
    }
  ],
  "words": [
    {
      "xpath": "/html/body/div[1]/div[2]/div/div[2]/ul/li[5]/a",
      "word": "Welcome",
      "position": {
        "x": 434.8,
        "y": 343.4,
        "w": 434,
        "h": 43
      },
      "word_index": 2,
      "offset": 14
    }
  ],
  "html": "<!doctype html><html lang='en' dir='ltr'><head><base hr...",
  "text": "Welcome in our page\nToday is Monday...",
  "screenshot_url": "https://api.webshotapi.com/v1/screenshot/?token=....&width=1920&height=960",
  "status_code": 200
}

```

## API docs
Full documentation about our api you can find in this website [API DOCS](https://webshotapi.com/docs/)

## About our service


## License
[MIT](https://choosealicense.com/licenses/mit/)