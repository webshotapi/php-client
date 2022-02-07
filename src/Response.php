<?php

namespace Webshotapi\Client;

class Response {

    private \GuzzleHttp\Psr7\Response $response;

    function __construct(\GuzzleHttp\Psr7\Response $response){
        $this->response = $response;
    }

    function contentEncoding(){
        return $this->response->getHeader('content-encoding');
    }
    function json(){
        return json_decode($this->response->getBody());
    }

    function body(){
        return (string)$this->response->getBody();
    }

    function contentType(){
        return $this->response->getHeader('content-type')[0];
    }

    function statusCode(){
        return $this->response->getStatusCode();
    }

    function getHeaders(){
        return $this->response->getHeaders();
    }

    function save($path){
        file_put_contents($path, $this->response->getBody());
    }
}