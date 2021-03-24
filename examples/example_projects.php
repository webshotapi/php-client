<?php
/**
 * WEBSHOTAPI.com
 * https://webshotapi.com/docs/
 * Create new project
 */

require_once __DIR__ ."/../vendor/autoload.php";

use Webshotapi\Client\Webshotapi;
use Webshotapi\Client\Webshotapi_exception;
try{

    $API_KEY = 'YOU_API_KEY';
  
    $client = new Webshotapi($API_KEY);
    //create  new project
 
    //get all projects
    $http_status = $client->get_projects();
    
    //check returned http code
    if($http_status==200){
        $data = $client->data();
        
        //print formated data
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }
    
    /** Please uncomment below codes if you want to check how to use our client 
    //get Project by ID
    $project_id = 141;
    $http_status = $client->get_project($project_id);
    if($http_status==200){
        var_dump($client->data());
    }
    
    
    //create  new project
    $http_status = $client->create_project([
        "name" => "Test project added from php api",
        "output_format" => "json",
        "active" => "yes"
    ]);
    
    if($http_status==200){
        $data = $client->data();
        $project_id = $data->id;
    }

    //Update  project
    $http_status = $client->update_project($project_id,[
        "name" => "Updated project added from php api",
        "output_format" => "json",
        "active" => "yes"
    ]);
    
    if($http_status==200){
        $data = $client->data();
        var_dump($data);
    }

    
    //Delete project
    $data = $client->delete_project($project_id);
     
    //add urls to project
    $data = $client->project_urls($project_id, [
        'https://example.com',
        'https://example2.com'
        ],
        [
            'remove_models'=>1,
            'width'=>320,
            'height'=>960,
        ]
    );
    */



} catch (Webshotapi_exception $e){
    echo $e->getMessage();
}
