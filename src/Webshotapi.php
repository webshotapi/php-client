<?php
/**
 * Webshotapi Client for our API
 * Ver: 1.0
 * https://webshotapi.com/docs
 * Require: PHP Ver > 5.0 with libaries: JSON, CURL, Zlib
 */

namespace Webshotapi\Client;

/**
 * 
 */

class Webshotapi_exception extends \Exception{}

class Webshotapi{
    
    protected $server_version_path = 'v1/';//v1/
    protected $server_url = 'https://api.webshotapi.com/';
    
    protected $apikey;
    
    protected $timeout_connection = 10;
    protected $timeout = 30;
    private $http_code = 0;
    protected $content_type = "";
    
    /*REquest data*/
    protected $response_raw_body=null;
    protected $response_headers=array();
    
    protected $errors;
    protected $body;
    protected $params;
    
    private $client_version = "1.0";
    
    /**
     * Constructor
     * @param string $api - api key from https://webshotapi.com/dashboard/api/
     * @param string $version - api version, actual V1
     */
    function __construct($api, $version='v1') {
          //check is curl installed
        if(!function_exists('curl_version')){
            throw new Webshotapi_exception('Cant\'t find CURL library. Please install curl lib first. More info: https://www.php.net/manual/en/book.curl.php');
        }
        
        if(!function_exists(('gzdecode'))){
            throw new Webshotapi_exception('You have to install PHP zlib library');
        }
        
        if(!function_exists(('json_decode'))){
            throw new Webshotapi_exception('You have to install PHP json library');
        }
        
        $this->server_version_path = $version.'/';
        $this->set_api_key($api);
    }
    
    /**
     * Set version path
     * @param int $v - example v1
     */
    function set_version_path($v){
        $this->server_version_path = $v;
    }
    
    /**
     * Set curl timeout connection
     * @param int $sec - seconds
     */
    function set_timeout_connection($sec){
        $this->timeout_connection = $sec;
    }
    
    /**
     * Set curl timeout download
     * @param int $sec - seconds
     */
    function set_timeout($sec){
        $this->timeout = $sec;
    }
    
  
    /**
     * 
     * @param type $url
     * @param type $path
     * @param type $method
     * @param type $params
     * @param type $headers_manual
     * @return type
     * @throws Webshotapi_exception
     */
    function request(
        $url, 
        $path,
        $method, 
        $params,
        $headers_manual=array()
    ){
        

        if(!$this->apikey){
            throw new Webshotapi_exception('Please set apikey first. $webshotapi = new Webshotapi(YOUR_API_KEY_HERE); You can download api key from https://webshotapi.com/dashboard/api/');
        }
        
        if($url)
            $params['link'] = $url;
          
        if($method == 'GET'){
            $params = http_build_query($params); 
            $ch = curl_init($this->server_url.$this->server_version_path.$path.'?'.$params);
        }else{
            $ch = curl_init($this->server_url.$this->server_version_path.$path);
        }

        //curl_setopt($ch, CURLOPT_URL, );
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        /* try to follow redirects */
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        /* timeout after the specified number of seconds. assuming that this script runs
        on a server, 20 seconds should be plenty of time to verify a valid URL.  */
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->timeout_connection);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
        
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, "WebshotApi Client PHP ".$this->client_version);
        
        if($method=='POST'){
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); 
            curl_setopt($ch, CURLOPT_POST, 1);
            $data_string = json_encode($params);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);  //Post Fields
            $headers_manual[] = 'Content-Type: application/json';
            $headers_manual[] = 'Content-Length: ' . strlen($data_string);
          
        }else if($method != 'GET'){
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        }
      
        //headers
        $headers = [
            'Authorization: Bearer '.$this->apikey,
            'Accept-encoding: gzip',
            'Accept: application/json',
            'Cache-Control: no-cache'
        ];
   
        if($headers_manual){
            $headers = array_merge($headers, $headers_manual);
        }     
      
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
    
        if(!$response)
            throw new Webshotapi_exception('Cant connect with api');
        
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $rawHeader = substr($response, 0, $headerSize);
        // extract body
        $body = substr($response, $headerSize);
        
        $cutHeaders = explode("\r\n", $rawHeader);
        $headers = array();
        foreach ($cutHeaders as $row){
            $cutRow = explode(":", $row, 2);
            
            if(isset($cutRow[1]))
                $headers[strtolower($cutRow[0])] = strtolower(trim($cutRow[1]));
        }
   
        //http code
        $info = curl_getinfo($ch);  
        $this->http_code = $info['http_code'];
        $this->content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
      
       // $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        //$headers = substr($response, 0, $header_size);
        if(isset($headers['content-encoding'])){
            switch($headers['content-encoding']){
                case 'gzip':
                    $this->response_raw_body = gzdecode($body);
                break;

                case 'compress':
                    $this->response_raw_body = gzuncompress($body);
                break;

                case 'deflate':
                    $this->response_raw_body = gzuncompress($body);
                break;             
            } 
        }else{
            $this->response_raw_body = $body;
        }
     
        $this->response_headers = $headers;
     
        curl_close ($ch);
        
        $this->errors = [];
      
        if(strpos($this->content_type,'json')!==false){
            $this->body = json_decode($this->response_raw_body);
            if(isset($this->body->errors)){
                $this->errors = $this->body->errors;
            }
        }else{
            $this->body = $this->response_raw_body;
        }
     
        if($this->http_code==401){
            throw new Webshotapi_exception("Wrong access token api key. Please go to https://webshotapi.com/dashboard/api/ and validate your api key.");
        }
        
        if($this->http_code != 200 && $this->http_code!=500){
            throw new Webshotapi_exception('Cant connect http code: '.$this->http_code,$this->http_code);
        }

        if($this->http_code==500){
            $e = new Webshotapi_exception('Cant connect http code: '. $this->http_code,$this->http_code);
            throw new $e;
        }
        
        return $this->http_code;
        
    }
    
    function json(){
        if(strpos($this->content_type,'json')!==false)
            return $this->body;
        else
            throw new Webshotapi_exception("This is not json object");
    }
    
    /**
     * Return errors from rest api for requests
     * @return type
     */
    function getErrors(){
        return $this->errors;
    }
    
    /**
     * Save file in specific path 
     */
    function save_file($save_path){
        
        if(strpos($this->content_type,'json')!==false){
                file_put_contents($save_path, $this->response_raw_body);
        }else{
            switch($this->content_type){
                case 'image/jpeg':case 'application/pdf':case 'image/png':
                    file_put_contents($save_path, $this->response_raw_body);
                break;
                default:
                    throw new Webshotapi_exception("Unknown content type download from server");
            }
        }
    }
    
    /**
     * Return content type file from request
     * @return string
     */
    function get_request_content_type(){
        return $this->content_type;
    }
    
    /**
     * Return http code from last request
     * @return int
     */
    function get_request_http_code(){
        return (int)$this->http_code;
    }
    
    /**
     * Method for set api key
     * @param string $api - api key
     */
    function set_api_key($api){
        $this->apikey = $api;
    }
    
  
    /**
     * Get all projects
     * @return array
     */
    function get_projects(){
        return $this->request('','projects/','GET', []);
    }
    
    /**
     * Get project by id
     * @param int $id - project id
     * @return object
     */
    function get_project($id){
        return $this->request('','project/'.$id,'GET', []);
    }
    
    /**
     * Insert new project
     * @param string - $name
     * @param array - parameters for new project
     * @return object
     */
    function create_project($data){
        return $this->request('','project/','POST', $data);
    }
    
    /**
     * Update project
     * @param int $id - project id
     * @param array - parameters
     * @return type
     */
    function update_project($id, $data){
        return $this->request('','project/'.$id.'/','POST', $data);
    }
    
    /**
     * Delete project by id
     * @param int $id
     * @return object
     */
    function delete_project($id){
        return $this->request('','project/'.$id.'/','DELETE', []);
    }
        
    /**
     * Get urls added to project
     * @param int $id - project id
     * @param int $page - page number
     * @return array return array
     */
    function get_project_urls($id,$page=1){
        return $this->request('','project/'.$id.'/urls/'.$page,'GET', []);
    }
    
    function delete_project_urls($id,$url_id){
        return $this->request('', 'project/'.$id.'/urls/'.$url_id, 'DELETE', []);
    }
    /**
     * Insert new urls to project
     * @param int $id - project id
     * @param array $links - array of new links
     * @return object
     */
    function project_urls($id, $links, $params){
        return $this->request('','project/'.$id.'/urls/','POST', [
            'urls'=>$links,
            'params'=>$params
        ]);
    }
    
    /**
     * Send request to api for generate PDF from link
     * @param string $url - link to website
     * @param array $params - array with parameters
     * @param string $save_path - save path
     * @param boolean $show_pdf - show pdf file in browser
     * @return int - return http code
     * @throws Webshotapi_exception
     */
    function pdf($url, $params, $save_path=null, $show_pdf=false){
        if(!$url){
            throw new Webshotapi_exception("Please put link to website");
        }
        
        $request = $this->request($url,'screenshot/pdf/','POST', $params);
        if($save_path){
            $this->save_file($save_path);
        }
        
        if($show_pdf){
            header("Content-type: application/pdf");
            echo $this->response_raw_body;
        }
        
        return $request;
    }
    /**
     * Take screenshot and return jpg file
     * @param string $url - link to website
     * @param array $params - array with parameters
     * @param string $save_path - save path
     * @param bool $show - show file in browser (send headers to browser)
     * @return int - return http status code
     * @throws Webshotapi_exception
     */
    function screenshot_jpg($url, $params, $save_path=null, $show=false){
        if(!$url){
            throw new Webshotapi_exception("Please put link to website");
        }
        
        $request = $this->request($url,'screenshot/jpg/','POST', $params);
        if($save_path){
            $this->save_file($save_path);
        }
  
        if($show){
            header("Content-type: image/jpeg");
            echo $this->response_raw_body;
        }
        
        return $request;
    }
    /**
     * Take screenshot and return png file
     * @param string $url - link to website
     * @param array $params - array with parameters
     * @param string $save_path - save path
     * @param bool $show - show file in browser (send headers to browser)
     * @return int - return http status code
     * @throws Webshotapi_exception
     */
    function screenshot_png($url, $params, $save_path=null, $show=false){
        if(!$url){
            throw new Webshotapi_exception("Please put link to website");
        }
        
        $request = $this->request($url,'screenshot/png/','POST', $params);
        if($save_path){
            $this->save_file($save_path);
        }
        
        if($show){
            header("Content-type: image/png");
            echo $this->response_raw_body;
        }
        
        return $request;
    }
    
    /**
     * Take screenshot and return json file
     * @param string $url - link to website
     * @param array $params - array with parameters
     * @param string $save_path - save path, if you dont want to save file set this argument to null
     * @param bool $show - show file in browser (send headers to browser)
     * @return int - return http status code
     * @throws Webshotapi_exception
     */
    function screenshot_json($url, $params, $save_path=null, $show=false){
        if(!$url){
            throw new Webshotapi_exception("Please put link to website");
        }
        
        $request = $this->request($url,'screenshot/json/','POST', $params);
        if($save_path){
            $this->save_file($save_path);
        }
        
        if($show){
            header("Content-type: application/json");
            echo $this->response_raw_body;
        }
        
        return $request;
    }
    
    /**
     * Display downloaded content as json
     * @return string
     */
    function display_json(){
        header("Content-type: application/json");
        echo $this->response_raw_body;
    }
    
    /**
     * Return data downloaded from server
     * @return object|binary
     */
    function data(){
        return $this->body;
    }
    
    /**
     * Return data downloaded from server in raw format. If download json return string not json object
     * @return string|bytes
     */
    function raw_data(){
        return $this->response_raw_body;
    }
    
    /**
     * Extract selectors with xpath, css styles and coordinates(x,y,width,height). You can also extract website words map.
     * @param string $url - link to website
     * @param array $params - extrat parameters, more 
     * @param string $save_path - save downloaded content to file
     * @param bool $show - show downloaded json in browser
     * @return type
     * @throws Webshotapi_exception
     */
    function extract($url, $params, $save_path=null, $show=false){
        if(!$url){
            throw new Webshotapi_exception("Please put link to website");
        }
        
        $request = $this->request($url,'extract/','POST', $params);
        if($save_path){
            $this->save_file($save_path);
        }
        
        if($show){
            header("Content-type: application/json");
            echo $this->response_raw_body;
        }
        
        return $request;
    }
}

