<?php

namespace Webshotapi\Client;

use Webshotapi\Client\Exceptions\WebshotApiClientException;

class ProjectUrl extends Base {

    function create(string $project_id, array $data){
        return $this->method([
            'path' => '/projects/'.$project_id.'/urls',
            'data' => $data,
            'accept_codes' => [201],
            'method' => 'POST'
        ]);
    }

    function list(string $project_id, int $page=1){
        return $this->method([
            'path' => '/projects/'.$project_id.'/urls?page='.$page,
            'method' => 'GET'
        ]);
    }

    function get(string $project_id, string $project_url_id){
        return $this->method([
            'path' => '/projects/'.$project_id.'/urls/'.$project_url_id,
            'method' => 'GET'
        ]);
    }

    function remove(string $project_id, string $project_url_id){
        return $this->method([
            'path' => '/projects/'.$project_id.'/urls/'.$project_url_id,
            'method' => 'DELETE'
        ]);
    }

    function processAgain(string $project_id, string $project_url_id){
        return $this->method([
            'path' => '/projects/'.$project_id.'/urls/'.$project_url_id.'/process_again',
            'method' => 'POST'
        ]);
    }

    function downloadUrl(mixed $projectUrl, $save_path){
        if(!is_string($projectUrl)){
            $url = $projectUrl['completed_file_url'];
        }else{
            $url = $projectUrl;
        }

        if (!filter_var($url, FILTER_VALIDATE_URL))
            throw new WebshotApiClientException("Url ".$url.' is not valid');

        return $this->download($url, $save_path);
    }
}