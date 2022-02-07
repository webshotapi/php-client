<?php

namespace Webshotapi\Client;

class Project extends Base
{
    function create(array $data){
        return $this->method([
            'path' => '/projects',
            'data' => $data,
            'accept_codes' => [201],
            'method' => 'POST'
        ]);
    }

    function update(string $project_id, array $data){
        return $this->method([
            'path' => '/projects/'.$project_id,
            'data' => $data,
            'method' => 'PATCH'
        ]);
    }

    function list(int $page=1){
        return $this->method([
            'path' => '/projects/?page='.$page,
            'method' => 'GET'
        ]);
    }

    function get(string $project_id){
        return $this->method([
            'path' => '/projects/'.$project_id,
            'method' => 'GET'
        ]);
    }

    function remove(string $project_id){
        return $this->method([
            'path' => '/projects/'.$project_id,
            'method' => 'DELETE'
        ]);
    }
}