<?php

namespace Webshotapi\Client;

use Webshotapi\Client\Factories\FileTypeFactory;

class WebshotApiClient {

    private $api_key;
    private $endpoint;
    private $timeout = 30;

    function __construct($api_key){
        $this->api_key = $api_key;
        $this->endpoint = getenv('WEBSHOTAPI_ENV') == 'dev' ? 'http://localhost:3000' : 'https://api.webshotapi.com/v1';
    }

    /**
     * Create pdf of specific url
     *
     * @param string $url
     * @param array $data
     * @return Response
     * @throws Exceptions\WebshotApiClientException
     */
    function pdf(string $url, array $data){
        return $this->screenshot($url, $data, 'image', 'pdf');
    }

    /**
     * Create screenshot for specific url and params
     *
     * @param string $url
     * @param array $data
     * @param string $response_type
     * @param string $file_type
     * @return Response
     * @throws Exceptions\WebshotApiClientException
     */
    function screenshot(string $url, array $data, string $response_type='image', string $file_type='jpg'){

        if(!in_array($response_type,['image','json']))
            throw new WebshotApiClientException('Wrong response type accept only image or json');

        if(!in_array($file_type,['jpg','png','pdf','json']))
            throw new WebshotApiClientException('Wrong screenshot format accept only jpg, png or pdf');

        $data['link'] = $url;
        $data['image_type'] = $file_type;

        $base = new Base($this);
        $fileType = FileTypeFactory::factory($file_type);

        $base->setHeaders([
            'Accept' => $fileType->getMime()
        ]);

        return $base->method([
            'path' => '/screenshot/' . $response_type,
            'data' => $data,
            'method' => 'POST'
        ]);
    }

    /**
     * Extract html, plain text, words coordinates with styles
     * @example $client->extract('https://example.com,['words'=>true]
     *
     * @param string $url
     * @param array $data
     * @return Response
     * @throws Exceptions\WebshotApiClientException
     */
    function extract(string $url, array $data){

        $data['link'] = $url;

        $base = new Base($this);

        $fileType = FileTypeFactory::factory('json');
        $base->setHeaders([
            'Accept' => $fileType->getMime(),
            'Accept-Encoding' => 'gzip'
        ]);


        return $base->method([
            'method' => 'POST',
            'path' => '/extract',
            //'path' => 'https://httpbin.org/headers',
            'data' => $data,
           // 'method' => 'GET'

        ]);
    }

    /**
     * Download info about your account
     *
     * @return Response
     * @throws Exceptions\WebshotApiClientException
     */
    function info(){
        $base = new Base($this);
        return $base->method([
            'path' => '/info',
            'method' => 'GET'
        ]);
    }

    /**
     * Set connection timeout in seconds
     *
     * @param $timeout
     */
    function setTimeout(int $timeout){
        $this->timeout = $timeout;
    }

    function getApiKey(): string{
        return $this->api_key;
    }

    function getTimeout(): int{
        return $this->timeout;
    }

    /**
     * Set api endpoint. This method can use for test or if you want to change version of REST api
     * @param $endpoint
     */
    function setEndpoint(string $endpoint){
        $this->endpoint = $endpoint;
    }

    function getEndpoint(): string{
        return $this->endpoint;
    }

    function projects(): Project{
        return new Project($this);
    }

    function projectsUrl(): ProjectUrl{
        return new ProjectUrl($this);
    }

}