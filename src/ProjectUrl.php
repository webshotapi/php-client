<?php

namespace Webshotapi\Client;

use Webshotapi\Client\Exceptions\WebshotApiClientException;

class ProjectUrl extends Base {

    /**
     * Add new urls to project for process
     *
     * @param string $project_id
     * @param array $data
     * @return Response
     * @throws WebshotApiClientException
     */
    function create(string $project_id, array $data): Response{
        return $this->method([
            'path' => '/projects/'.$project_id.'/urls',
            'data' => $data,
            'accept_codes' => [201],
            'method' => 'POST'
        ]);
    }

    /**
     * List all projects urls with link to completed output file
     *
     * @param string $project_id
     * @param int $page
     * @return Response
     * @throws WebshotApiClientException
     */
    function list(string $project_id, int $page=1): Response{
        return $this->method([
            'path' => '/projects/'.$project_id.'/urls?page='.$page,
            'method' => 'GET'
        ]);
    }

    /**
     * Get info about link for project_url_id
     *
     * @param string $project_id
     * @param string $project_url_id
     * @return Response
     * @throws WebshotApiClientException
     */
    function get(string $project_id, string $project_url_id): Response{
        return $this->method([
            'path' => '/projects/'.$project_id.'/urls/'.$project_url_id,
            'method' => 'GET'
        ]);
    }

    /**
     * Remove url from projects queue list
     *
     * @param string $project_id
     * @param string $project_url_id
     * @return Response
     * @throws WebshotApiClientException
     */
    function remove(string $project_id, string $project_url_id): Response{
        return $this->method([
            'path' => '/projects/'.$project_id.'/urls/'.$project_url_id,
            'method' => 'DELETE'
        ]);
    }

    /**
     * If you want you can process this link again
     *
     * @param string $project_id
     * @param string $project_url_id
     * @return Response
     * @throws WebshotApiClientException
     */
    function processAgain(string $project_id, string $project_url_id): Response{
        return $this->method([
            'path' => '/projects/'.$project_id.'/urls/'.$project_url_id.'/process_again',
            'method' => 'POST'
        ]);
    }

    /**
     * Download specific url to file
     *
     * @param mixed $projectUrl
     * @param $save_path
     * @return Response
     * @throws WebshotApiClientException
     */
    function downloadUrl(mixed $projectUrl, $save_path): Response{
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