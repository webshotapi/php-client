<?php

namespace Webshotapi\Client;

use GuzzleHttp\Client;
use Webshotapi\Client\Exceptions\WebshotApiClientException;

class Base {

    protected $client;
    protected $headers=[];

    function __construct($client){
        $this->client = $client;
    }

    function download($url, $save_path){
        try{
            $http = new Client();

            $resource = \GuzzleHttp\Psr7\Utils::tryFopen($save_path, 'w');
            $res = $http->get($url,['sink'=>$resource]);
            return new Response($res);
        }catch(\Exception $e){
            throw new WebshotApiClientException($e->getMessage(), $e->getCode());
        }
    }

    function setHeaders(array $headers) {
        $this->headers = $headers;
    }

    function method(array $data, int $timeout = null){
        try {
            $http = new Client([
                'timeout' => $timeout ? $timeout : $this->client->getTimeout(),
                'base_uri' => $this->client->getEndpoint()
            ]);

           $toSend = [
               'headers' => array_merge([
                   'authorization' => 'Bearer '.$this->client->getApiKey()
               ], $this->headers)
           ];

           if(!in_array($data['method'],['GET','DELETE']) && isset($data['data']))
              $toSend['json'] = $data['data'];

           $res = $http->request(
               $data['method'],
               $data['path'],
               $toSend
           );

           if(!isset($data['accept_codes']))
               $data['accept_codes'] = [200];

            if(!in_array($res->getStatusCode(), $data['accept_codes'])){
                throw new WebshotApiClientException("Wrong status code");
            }

            return new Response($res);
        }catch(\Exception $e){
            throw new WebshotApiClientException($e->getMessage(), $e->getCode());
        }
    }
}