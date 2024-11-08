<?php

namespace Webshotapi\Client;

use Gawsoft\RestApiClientFramework\Exceptions\ClientException;
use Gawsoft\RestApiClientFramework\Interfaces\ClientInterface;
use Webshotapi\Client\Exceptions\WebshotApiClientException;
use Webshotapi\Client\Factories\FileTypeFactory;
use Gawsoft\RestApiClientFramework\Base;
use Gawsoft\RestApiClientFramework\Interfaces\ResponseInterface;

class WebshotApiClient implements ClientInterface {

    private $api_key;
    private $endpoint;
    private $timeout = 50;

    /**
     * @param string $api_key
     * @param string|null $endpoint
     */
    function __construct(string $api_key, string $endpoint = null){
        $this->api_key = $api_key;
        if ($endpoint) $this->endpoint = $endpoint;
        else $this->endpoint = getenv('WEBSHOTAPI_ENDPOINT')  ? getenv('WEBSHOTAPI_ENDPOINT') :  'https://api.webshotapi.com/v1/';
    }

    /**
     * Create pdf of specific url
     *
     * @param string $url
     * @param array $data
     * @return ResponseInterface
     * @throws Exceptions\WebshotApiClientException
     */
    function pdf(string $url, array $data): ResponseInterface{
        return $this->screenshot($url, $data, 'pdf');
    }

    /**
     * Download link to file
     *
     * @param string $url
     * @param string $path
     * @return ResponseInterface
     * @throws Exceptions\WebshotApiClientException
     */
    function download(string $url, string $path): ResponseInterface {
        try {
            $base = new Base($this);
            return $base->download($url, $path);
        } catch (ClientException $e){
            throw $this->exceptionHandle($e);
        } catch (\Exception $e) {
            throw new WebshotApiClientException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     *  Create screenshot for specific url and params
     *  If you want to create png format call $client->screenshot('https://example.com',[],'image','png');
     *
     * @param string $url
     * @param array $data
     * @param string $file_type
     * @return ResponseInterface
     * @throws WebshotApiClientException
     */
    function screenshot(string $url, array $data, string $file_type='jpg'): ResponseInterface{

        if(!in_array($file_type,['jpg','png','pdf','json']))
            throw new WebshotApiClientException('Wrong screenshot format accept only jpg, png or pdf');

        try {
            $data['url'] = $url;
            $data['image_type'] = $file_type;

            $base = new Base($this);
            $fileType = FileTypeFactory::factory($file_type);

            $base->setHeaders([
                'Accept' => $fileType->getMime()
            ]);

            return $base->method([
                'path' => 'screenshot/image',
                'data' => $data,
                'method' => 'POST'
            ]);
        } catch (ClientException $e){
            throw $this->exceptionHandle($e);
        }
    }

    /**
     * Extract html, plain text, words coordinates with styles
     * @example $client->extract('https://example.com,['extract_words'=>true]);
     *
     * @param string $url
     * @param array $data
     * @return ResponseInterface
     * @throws Exceptions\WebshotApiClientException
     */
    function extract(string $url, array $data): ResponseInterface{

        try {
            $data['url'] = $url;

            $base = new Base($this);

            $fileType = FileTypeFactory::factory('json');
            $base->setHeaders([
                'Accept' => $fileType->getMime(),
                'Accept-Encoding' => 'gzip'
            ]);

            return $base->method([
                'method' => 'POST',
                'path' => 'extract',
                'data' => $data,
            ]);

        } catch (ClientException $e){
            throw $this->exceptionHandle($e);
        }
    }

    /**
     * Download info about your account
     *
     * @return ResponseInterface
     * @throws Exceptions\WebshotApiClientException
     */
    function info(): ResponseInterface{
        try{
            $base = new Base($this);
            return $base->method([
                'path' => 'info',
                'method' => 'GET'
            ]);
        } catch (ClientException $e){
            throw $this->exceptionHandle($e);
        }
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

    protected function exceptionHandle(ClientException $exception): WebshotApiClientException{
        $psr_response = null;
        if($exception->hasResponse())
            $psr_response = $exception->getResponse()->psr7Response();

        return new WebshotApiClientException($exception->getMessage(), $exception->getCode(), $exception, $psr_response);
    }
}