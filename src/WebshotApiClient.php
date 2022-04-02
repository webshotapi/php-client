<?php

namespace Webshotapi\Client;


use Gawsoft\RestApiClientFramework\Interfaces\ClientInterface;
use Webshotapi\Client\Exceptions\WebshotApiClientException;
use Webshotapi\Client\Factories\FileTypeFactory;
use Gawsoft\RestApiClientFramework\Base;
use Gawsoft\RestApiClientFramework\Response;
use Gawsoft\RestApiClientFramework\ProjectUrl;
use Gawsoft\RestApiClientFramework\Project;

class WebshotApiClient implements ClientInterface {

    private $api_key;
    private $endpoint;
    private $timeout = 30;

    /**
     * @param string $api_key
     */
    function __construct(string $api_key){
        $this->api_key = $api_key;
        $this->endpoint = getenv('WEBSHOTAPI_ENV') == 'dev' ? env('WEBSHOTAPI_ENDPOINT','http://localhost:3000') : 'https://api.webshotapi.com/v1';
    }

    /**
     * Create pdf of specific url
     *
     * @param string $url
     * @param array $data
     * @return Response
     * @throws Exceptions\WebshotApiClientException
     */
    function pdf(string $url, array $data): Response{
        return $this->screenshot($url, $data, 'image', 'pdf');
    }

    /**
     * Download link to file
     *
     * @param string $url
     * @param string $path
     * @return Response
     * @throws WebshotApiClientException
     */
    function download(string $url, string $path): Response {
        try {
            $base = new Base($this);
            return $base->download($url, $path);
        } catch (\Exception $err) {
            throw new WebshotApiClientException($err);
        }
    }
    /**
     * Create screenshot for specific url and params
     * If you want to create png format call $client->screenshot('https://example.com',[],'image','png');
     *
     * @param string $url
     * @param array $data
     * @param string $response_type
     * @param string $file_type
     * @return Response
     * @throws Exceptions\WebshotApiClientException
     */
    function screenshot(string $url, array $data, string $response_type='image', string $file_type='jpg'): Response{

        if(!in_array($response_type,['image','json']))
            throw new WebshotApiClientException('Wrong response type accept only image or json');

        if(!in_array($file_type,['jpg','png','pdf','json']))
            throw new WebshotApiClientException('Wrong screenshot format accept only jpg, png or pdf');

        try {
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
        } catch (ClientException $e){
            throw new WebshotApiClientException($e);
        }
    }

    /**
     * Extract html, plain text, words coordinates with styles
     * @example $client->extract('https://example.com,['extract_words'=>true]);
     *
     * @param string $url
     * @param array $data
     * @return Response
     * @throws Exceptions\WebshotApiClientException
     */
    function extract(string $url, array $data): Response{

        try {
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
                'data' => $data,

            ]);
        } catch (ClientException $e){
            throw new WebshotApiClientException($e);
        }
    }

    /**
     * Download info about your account
     *
     * @return Response
     * @throws Exceptions\WebshotApiClientException
     */
    function info(): Response{
        try{
            $base = new Base($this);
            return $base->method([
                'path' => '/info',
                'method' => 'GET'
            ]);
        } catch (ClientException $e){
            throw new WebshotApiClientException($e);
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

    /**
     * CRUD methods for projects rest api
     *
     * @return Project
     * @throws Gawsoft\RestApiClientFramework\Exceptions\ClientException
     */
    function projects(): Project{
        return new Project($this);
    }

    /**
     * CRUD methods for projects url rest api
     *
     * @return Project
     * @throws Gawsoft\RestApiClientFramework\Exceptions\ClientException
     */
    function projectsUrl(): ProjectUrl{
        return new ProjectUrl($this);
    }

}