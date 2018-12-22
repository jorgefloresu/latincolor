<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/*
The contents of this file are subject to the Mozilla Public License
Version 1.1 (the "License"); you may not use this file except in compliance
with the License. You may obtain a copy of the License at
http://www.mozilla.org/MPL/MPL-1.1.html or see MPL-1.1.txt

Software distributed under the License is distributed on an "AS IS" basis,
WITHOUT WARRANTY OF ANY KIND, either expressed or implied. See the License for
the specific language governing rights and limitations under the License.

The Initial Developers of the Original Code are: 
Copyright (c) 2015, PantherMedia (http://www.panthermedia.net)
All Rights Reserved.
 
Contributor(s): Ricardo Cescon, Peter Ammel

PantherMedia RESTful Client for PHP 1.1
*/

require_once('PantherClientConfig.php');
require_once('PantherParams.php');


class Exception_PM_REST_Client extends Exception {};
//----------------------------------------------------------------------------------------------------------------------
/**
 * PHP SDK for easy implementation of the PantherMedia RESTful API
 * feel free to extend it in your own class
 * if you like you can send us extentions of the class and we verify it, to put it to the main version of the class
 */
class PantherMedia_Api{
   
   private $m_uri = null;
   
   private $m_api_key;
   private $m_api_secret;
   
   private $m_auth_token = null;
   private $m_token_secret = null;
   
   private $m_curl = null;
   private $m_curl_info = null;
   private $m_response_headers = null;
   private $m_response_body = null;
   
   public $m_timeout_connect = 3000;
   public $m_algo = 'sha1';   
//----------------------------------------------------------------------------------------------------------------------
   function __construct(){            
      $this->m_api_key = PANTHER_API_KEY;
      $this->m_api_secret = PANTHER_API_PASSWORD;
   }
//----------------------------------------------------------------------------------------------------------------------      
   /**
    * only used to debug with other URI (for SDK developers)
    */
   function setURI($uri){
      $this->m_uri = $uri;
   }
//----------------------------------------------------------------------------------------------------------------------      
   /**
    * create a access-token/key from api-key / auth-token
    * 
    * @param string $token
    * @param string $secret
    * @param string $timestamp
    * @param string $nonce
    * @return string
    */
   function createToken($token, $secret, &$timestamp=null, &$nonce=null){
      if($timestamp === null){
         $timestamp = str_replace('+0000', 'UTC', gmdate(DATE_RSS, time()));
      }
      if($nonce === null){
         $nonce = rand(900000, 999999);
      }
      $data = $timestamp . $token . $nonce;         
      return hash_hmac($this->m_algo, $data, $secret, false);
   }
//----------------------------------------------------------------------------------------------------------------------   
   private function &getCURL(){
      
      $ch = curl_init();   

      curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
      curl_setopt($ch, CURLOPT_HEADER, true);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_HTTPGET, false);
      curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, true );
      curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
      curl_setopt($ch, CURLOPT_ENCODING,  '');  
      curl_setopt($ch, CURLOPT_FRESH_CONNECT,  true);  

      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, $this->m_timeout_connect);
      curl_setopt($ch, CURLOPT_USERAGENT, "PantherMedia RESTful Client " . REST_VERSION . " (www.panthermedia.net)");
      curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);

      $this->m_curl =& $ch;
      
      return $this->m_curl;
   }
//----------------------------------------------------------------------------------------------------------------------      
   /**
    * 
    * @param string  $method
    * @param array $post_data
    * @param string $version
    * @param bool $ssl 
    * @return array
    * @throws Exception_PM_REST_Client
    */
   function request($method, $post_data, $version=null, $ssl=false){
      $ch =& $this->getCURL();
      
      $timestamp = null;
      $nonce = null;
      
      $post_data['api_key'] = $this->m_api_key;
      $post_data['access_key'] = $this->createToken($this->m_api_key, $this->m_api_secret, $timestamp, $nonce);
      $post_data['timestamp'] = $timestamp;
      $post_data['nonce'] = $nonce;
      $post_data['algo'] = $this->m_algo;
            
      if($this->m_auth_token !== null && $this->m_token_secret !== null){
         $post_data['auth_token'] = $this->m_auth_token;
         $post_data['access_token'] = $this->createToken($this->m_auth_token, $this->m_token_secret, $timestamp, $nonce);
      }
      
      if($version === null){
         $version = REST_VERSION;
      }
      
      if($this->m_uri === null){
         $this->m_uri = REST_URI;
      }
      
      $url_file = ($ssl?'https://':'http://').$this->m_uri.'/'.$method;
      curl_setopt($ch, CURLOPT_URL, $url_file);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                                                   'Connection: Keep-Alive',
                                                   'Keep-Alive: 300',
                                                   'Accept-Version: '.$version,                                                   
                                               ));
      curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
      
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
      
      $this->m_response_body = $body = curl_exec($ch);
      $this->m_curl_info = curl_getinfo($ch);
      
      $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
      $headers = substr($body, 0, $header_size);
      $body = substr($body, $header_size);
                  
      curl_close($ch);
      
            
      $headers = explode("\n", $headers);
      
      $tmp = $headers;
      $headers = array();
            
      foreach($tmp as $value){                  
         if(strpos($value, ':') !== false){
            $h = explode(':', $value);            
            $h[0] = str_replace('-', '_', $h[0]);
            $h[0] = strtoupper($h[0]);
            $headers[$h[0]] = trim($h[1]);
         }   
      }
      $tmp = null;
      
      $this->m_response_headers =& $headers;
                  
      if(!empty($body)){                              
         if(isset($this->m_curl_info['content_type']) && $this->m_curl_info['content_type'] == 'application/json'){ // JSON
            try{
               $body = json_decode($body, true);
            }catch(Exception $e){
               throw new Exception_PM_REST_Client('JSON Error: can\'t decode response', $e->getCode());
            }               
         }  
      }     
                        
      if(isset($this->m_curl_info['http_code']) && $this->m_curl_info['http_code'] != '200'){
         if ($body['stat']=='fail')
          $body = '';
        else{
         if(isset($body['err']['code']) && isset($body['err']['msg'])){
            //print_r($body);
            show_error($body['err']['code'].'-'.$body['err']['msg'], $body['err']['code']);
            //throw new Exception_PM_REST_Client($body['err']['msg'], $body['err']['code']);
         }         
         if(isset($headers['STATUS'])){
            throw new Exception_PM_REST_Client($headers['STATUS'], $this->m_curl_info['http_code']);
         }
         throw new Exception_PM_REST_Client('HTTP Error', $this->m_curl_info['http_code']);
        }           
      }
      return $body;
   }   
//----------------------------------------------------------------------------------------------------------------------                  
   /**
    * return RAW response (useful for debug)
    * 
    * @return string
    */
   function &get_raw_response(){
      return $this->m_response_body;
   }
//----------------------------------------------------------------------------------------------------------------------            
   /**
    * 
    * @return array
    */
   function &get_CURL_info(){
      return $this->m_curl_info;
   }   
//----------------------------------------------------------------------------------------------------------------------               
   /**
    * 
    * @return array
    */
   function &get_response_headers(){
      return $this->m_response_headers;
   }
//----------------------------------------------------------------------------------------------------------------------            
   /**
    * set the User authentication, to reset - set both to null
    * 
    * @param string $auth_token
    * @param string $token_secret
    */
   function setUser_Authentication($auth_token, $token_secret){
      $this->m_auth_token = $auth_token;
      $this->m_token_secret = $token_secret;
   }
//----------------------------------------------------------------------------------------------------------------------      
   /**
    * 
    * @return array like array('token'=>'a3ce21', 'secret'=>'12345');
    */
   function getUser_Authentication(){
      if($this->m_auth_token !== null && $this->m_token_secret !== null){
         return array('token'=>$this->m_auth_token, 'secret'=>$this->m_token_secret);
      }else{
         return false;
      }   
   }
//----------------------------------------------------------------------------------------------------------------------         
   /**
    * request a authentication token for a user login to authorize your app to access user data
    * 
    * @param string $token_secret
    * @param string $app_name
    * @param string $app_logo
    * @param string $app_callback
    * @param string $additional_device_info
    * @return array
    * @throws Exception_PM_REST_Client
    */
   function request_token($token_secret, $app_name, $app_logo=null, $app_callback=null, $additional_device_info=null){
      $post_data = array('content_type'=>'application/json');
      $post_data['token_secret'] = $token_secret;
      $post_data['app_name'] = $app_name.($additional_device_info!==null?' - '.$additional_device_info:'');      
      if($app_logo !== null) $post_data['app_logo'] = $app_logo;
      if($app_callback !== null) $post_data['app_callback'] = $app_callback;
                  
      return $this->request('request-token', $post_data, '1.0', true);
   }
//----------------------------------------------------------------------------------------------------------------------      
   /**
    * set the validation of a token<br><br>Sample for $valid_until:<br>$ts = time() + (7 * 24 * 60 * 60);// one week<br>$valid_until = str_replace('+0000', 'UTC', gmdate(DATE_RSS, $ts));   
    * 
    * @param string $valid_until as UTC formated DateTime
    */
   function token_valid_until($valid_until){
      $post_data = array('content_type'=>'application/json');
      $post_data['valid_until'] = $valid_until;
                        
      return $this->request('token-valid-until', $post_data, '1.0');
   }
//----------------------------------------------------------------------------------------------------------------------   
   /**
    * return infos about the API Host
    * 
    * @return array
    * @throws Exception_PM_REST_Client
    */
   function host_info(){      
      $post_data = array('content_type'=>'application/json');
                  
      return $this->request('host-info', $post_data, '1.0');      
   }
//----------------------------------------------------------------------------------------------------------------------      
   /**    
    * search on PantherMedia Database, for full search capabilities see: http://rest.panthermedia.net
    * 
    * @param string $q
    * @param string $lang
    * @param int $page
    * @param int $limit
    * @param string $extra_info
    * @param string $filters
    * @return array
    * @throws Exception_PM_REST_Client
    */
   //function search($q='',$lang='en',$page=0, $limit=40, $extra_info=null, $filters=null)
   function search($criteria=array(), $extra_info=null, $filters=null)
   {
      $post_data = array(
        'content_type'=>'application/json',
        PantherParams::SEARCH_LANGUAGE => 'en'
        );
      /*
      $post_data['q'] = $q;
      $post_data['lang'] = $lang;
      $post_data['page'] = $page;
      $post_data['limit'] = $limit;
      */

      if($extra_info !== null) $post_data['extra_info'] = $extra_info;
      if($filters !== null) $post_data['filters'] = $filters;

      $post_data = array_merge($post_data, $criteria);
                  
      return $this->request('search', $post_data, REST_VERSION);
   }
//----------------------------------------------------------------------------------------------------------------------      
   /**
    * get detail infos (metadata) and possible articels/pricing
    * 
    * @param string $id_media
    * @param string $lang
    * @param bool $show_articles
    * @param bool $show_top10_keywords
    * @return array
    * @throws Exception_PM_REST_Client
    */
   //function get_media_info($id_media, $lang='en', $show_articles=false, $show_top10_keywords=false)
   function get_media_info($id_media, $show_articles=false, $show_top10_keywords=false)
   {
      $post_data = array(
        'content_type'=>'application/json',
        PantherParams::MEDIA_ID => $id_media,
        PantherParams::SEARCH_LANGUAGE => 'en'
        );
      /*
      $post_data['id_media'] = $id_media;
      $post_data['lang'] = $lang;
      */
      if($show_articles) $post_data['show_articles'] = 'yes';
      if($show_top10_keywords) $post_data['show_top10_keywords'] = 'yes';
      
      return $this->request('get-media-info', $post_data, REST_VERSION);
   }
//----------------------------------------------------------------------------------------------------------------------         
   /**
    * download a preview image file
    * 
    * @param string $id_media
    * @return string as binary
    * @throws Exception_PM_REST_Client
    */
   function download_image_preview($id_media){
      //$post_data = array('content_type'=>'application/octet-stream, application/json');
      $post_data = array('content_type'=>'application/json');
      $post_data['id_media'] = $id_media;
      
      return $this->request('download-image-preview', $post_data, REST_VERSION);                         
   }
//----------------------------------------------------------------------------------------------------------------------            
   /**
    * 
    * @param string $id_media
    * @param string $id_article
    * @param string $lang
    * @param string $metadata as sample all, iptc, xmp, none
    * @param int $id_download
    * @param bool $test
    * @return string as binary
    */
   //function download_image($id_media, $id_article, $lang='en', $metadata='all', $id_download=null, $test=false){
   function download_image($id_media, $id_article, $id_download=null, $test=true)
   {
      $post_data = array(
        'content_type'=>'application/json',
        PantherParams::SEARCH_LANGUAGE => 'en',
        PantherParams::MEDIA_METADATA => 'all',
        PantherParams::MEDIA_ID => $id_media,
        PantherParams::MEDIA_ID_ARTICLE => $id_article
        );
      /*
      $post_data['id_media'] = $id_media;
      $post_data['id_article'] = $id_article;
      $post_data['lang'] = $lang;
      $post_data['metadata'] = $metadata;
      */
      if($id_download !== null) $post_data['id_download'] = $id_download;
      if($test) $post_data['test'] = 'yes';
      
      return $this->request('download-image', $post_data, REST_VERSION);                         
   }
//----------------------------------------------------------------------------------------------------------------------               
   function get_search_filter(){
      $post_data = array('content_type'=>'application/json');
            
      return $this->request('get-search-filter', $post_data, '1.0');                         
   }
//----------------------------------------------------------------------------------------------------------------------                     
  /**    
    * returns all downloaded images 
    *     
    * @param string $lang
    * @param int $offset
    * @param int $limit
    * @param string $extra_info    
    * @return array
    * @throws Exception_PM_REST_Client
    */
   function get_downloaded_images($lang='en',$offset=0, $limit=40, $extra_info=null){
      $post_data = array('content_type'=>'application/json');      
      $post_data['lang'] = $lang;
      $post_data['offset'] = $offset;
      $post_data['limit'] = $limit;
      if($extra_info !== null) $post_data['extra_info'] = $extra_info;
                     
      return $this->request('get-downloaded-images', $post_data, '1.0');
   } 
//----------------------------------------------------------------------------------------------------------------------                  
   /**    
    * returns the available deposit of an account and the URL where to buy new deposit
    *     
    * @param string $lang    
    * @return array
    * @throws Exception_PM_REST_Client
    */
   function get_user_deposit($lang='en'){
      $post_data = array('content_type'=>'application/json');      
      $post_data['lang'] = $lang;
                           
      return $this->request('get-user-deposit', $post_data, '1.0');
   } 
//----------------------------------------------------------------------------------------------------------------------                     
   function get_user_profile(){
      $post_data = array('content_type'=>'application/json');
      return $this->request('get-user-profile', $post_data, '1.0');
   }
//----------------------------------------------------------------------------------------------------------------------                        
} // class
?>