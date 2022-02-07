<?php

namespace Webshotapi\Client;

class Project extends Base
{
    /**
     * Create new Project. You don't have to send request to our api. Simple send urls list to process
     *
     * @param array $data
     * @return Response
     * @throws Exceptions\WebshotApiClientException
     */
    function create(array $data): Response{
        return $this->method([
            'path' => '/projects',
            'data' => $data,
            'accept_codes' => [201],
            'method' => 'POST'
        ]);
    }

    /**
     * Change project name and status
     *
     * @param string $project_id
     * @param array $data
     * @return Response
     * @throws Exceptions\WebshotApiClientException
     */
    function update(string $project_id, array $data): Response{
        return $this->method([
            'path' => '/projects/'.$project_id,
            'data' => $data,
            'method' => 'PATCH'
        ]);
    }

    /**
     * List all projects grouped by page
     *
     * @param int $page
     * @return Response
     * @throws Exceptions\WebshotApiClientException
     */
    function list(int $page=1): Response{
        return $this->method([
            'path' => '/projects/?page='.$page,
            'method' => 'GET'
        ]);
    }

    /**
     * Get project data with stats
     *
     * @param string $project_id
     * @return Response
     * @throws Exceptions\WebshotApiClientException
     */
    function get(string $project_id): Response{
        return $this->method([
            'path' => '/projects/'.$project_id,
            'method' => 'GET'
        ]);
    }

    /**
     * Delete project
     *
     * @param string $project_id
     * @return Response
     * @throws Exceptions\WebshotApiClientException
     */
    function remove(string $project_id): Response{
        return $this->method([
            'path' => '/projects/'.$project_id,
            'method' => 'DELETE'
        ]);
    }
}