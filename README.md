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

include_once __DIR__ . '/../vendor/autoload.php';

use Webshotapi\Client\WebshotApiClient;
use Webshotapi\Client\Exceptions\WebshotApiClientException;

try{

$API_KEY = 'YOU_API_KEY';
$URL = 'PUT_LINK_TO_WEBSITE_HERE';

$SAVE_PATH = '/tmp/save2.jpg';

$params = array(
    'remove_modals' => '1',
    'ads' => '1',
    'width' => '1280',
    'height' => '2040',
    'no_cache' => '1',
    'scroll_to_bottom' => '0',
    'wait_for_selector' => '',
    'wait_for_xpath' => '',
    'image_quality' => 75,
    'transparent_background' => false,
    'user_agent' => '',
    'accept_language' => '',
    'full_page' => true,
    'timezone' => '',
    'fail_statuscode' => '',
    'capture_element_selector' => '',
    'thumbnail_width' => 320,
);

$webshotapi = new WebshotApiClient($API_KEY);

//Download, save jpg and send to browser
$response = $webshotapi->screenshot($URL, $params);
$response->save($SAVE_PATH);


} catch (WebshotApiClientException $e){
echo"ERROR: ";
echo $e->getMessage();
}
```

### Take screenshot and save PDF to file
You can covert your html page to invoice in PDF.
```php
<?php

include_once __DIR__ . '/../vendor/autoload.php';

use Webshotapi\Client\WebshotApiClient;
use Webshotapi\Client\Exceptions\WebshotApiClientException;

try{

$API_KEY = 'YOU_API_KEY';
$URL = 'PUT_LINK_TO_WEBSITE_HERE';


$SAVE_PATH = '/tmp/save2.pdf';

$params = array(
    'remove_modals' => '1',
    'ads' => '1',
    'width' => '1280',
    'height' => '2040',
    'no_cache' => '1',
    'scroll_to_bottom' => '0',
    'wait_for_selector' => '',
    'wait_for_xpath' => '',
    'image_quality' => 75,
    'transparent_background' => false,
    'user_agent' => '',
    'accept_language' => '',
    'full_page' => true,
    'timezone' => '',
    'fail_statuscode' => '',
    'capture_element_selector' => '',
    'thumbnail_width' => 320,
);

$webshotapi = new WebshotApiClient($API_KEY);

//Download, save jpg and send to browser
$response = $webshotapi->pdf($URL, $params);
$response->save($SAVE_PATH);


} catch (WebshotApiClientException $e){
echo"ERROR: ";
echo $e->getMessage();
}
```

### Extract words map and HTML elements with css styles after rendering
Unique software to extract all selectors for HTML elements from website with css styles after browser rendering. Additionally, you can extract all words with position (x,y,width, height, offset from previous word). Thank that you can build words map of website.

#### Sample script:
```php
<?php

include_once __DIR__ . '/../vendor/autoload.php';

use Webshotapi\Client\WebshotApiClient;
use Webshotapi\Client\Exceptions\WebshotApiClientException;

try{

$API_KEY = 'YOU_API_KEY';
$URL = 'PUT_LINK_TO_WEBSITE_HERE';

$SAVE_PATH = '/tmp/save2.json';

$params = array(
    [
        'no_cache'=>1,
        'extract_selectors'=>1,
        'extract_words' => 1,
        'extract_style' => 1,//0 - skip styles, 1 - download most import css styles, 2 - download all styles for element
    ]
);

$webshotapi = new WebshotApiClient($API_KEY);

//Download, save jpg and send to browser
$response = $webshotapi->extract($URL, $params);

// Save to file
$response->save($SAVE_PATH);

// If you want to manipulate json f
$json_data = $response->json();



} catch (WebshotApiClientException $e){
echo"ERROR: ";
echo $e->getMessage();
}
```
#### Example result json for extract method

```json
{
  "status_code": 200,
  "html": "<!doctype html><html lang='en' dir='ltr'><head><base hr...",
  "text": "Welcome in our page\\nToday is Monday..",
  "screenshot_url": "https://api.webshotapi.com/v1/screenshot/?token=....&width=1920&height=960",
  "selectors": [
    {
      "class": ".col-12 col-lg-12",
      "id": "price",
      "itemprop": "title",
      "x": 434.8,
      "y": 343.4,
      "w": 434,
      "h": 43,
      "xpath": "/html/body/div[1]/div[2]/div/div[2]/ul/li[5]/ul/li[2]",
      "style": {
        "visibility": "visible",
        "display": "block",
        "fontWeight": "400",
        "backgroundImage": "none",
        "cursor": "pointer",
        "fontSize": "22px",
        "color": "rgb(255, 255, 255)",
        "position": "absolute",
        "textDecoration": "none solid rgb(255, 255, 255)",
        "textDecorationLine": "none",
        "backgroundColor": "rgba(0, 255, 255, 0)"
      }
    }
  ],
  "words": [
    {
      "xpath": "/html/body/div[1]/div[2]/div/div[2]/ul/li[5]/a",
      "word": "Hello",
      "word_index": 2,
      "offset": 14,
      "position": {
        "x": 434.8,
        "y": 343.4,
        "w": 434,
        "h": 43
      }
    }
  ]
}


```

## API docs
Full documentation about our api you can find in this website [API DOCS](https://webshotapi.com/docs/)

## About our service


## License
[MIT](https://choosealicense.com/licenses/mit/)