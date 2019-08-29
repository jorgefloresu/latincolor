<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('AdobeStockClientConfig.php');
require_once('AdobeStockParams.php');

/**
 * Interface to the AdobeStock API client.
 *
 *
 */
class Adobestock_Api
{
    const VERSION = '1';

    /**
     * AdobeStock uri.
     *
     * @var     string
     */
    protected $apiUrl;

    /**
     * AdobeStock API key.
     *
     * @var     string
     */
    protected $apiKey;

    /**
     * Constructor
     *
     * @param   string $apiUrl  AdobeStock RPC uri
     * @param   string $apiKey  AdobeStock API key
     */
    public function  __construct()
    {
        $this->apiUrl = ADOBESTOCK_API_URL;
        $this->apiKey = ADOBESTOCK_API_KEY;
    }

    public function getApiKey()
    {
        echo "Api Key:".$this->apiKey."<br/>";
        echo "Api Url:".$this->apiUrl."<br/>";
    }
    
    /*
     * @param   GET variables string request
     * @return  XML
     */
    public function search($criteria = array())
    {
        $postParams = array(
            AdobeStockParams::RESULT_NUM_ROWS,
            AdobeStockParams::RESULT_ID,
            AdobeStockParams::RESULT_TITLE,
            AdobeStockParams::RESULT_THUMBNAIL,
            AdobeStockParams::RESULT_TYPE_ID
        );

        $postParams = array_merge($postParams, $criteria);
        $url = $this->getFullURI(AdobeStockParams::SEARCH_CMD, $postParams);
        //echo $url;
        return $url;
        //return $this->checkResponse($this->post($url, $postParams));
    }

    /*
     * @param   integer $mediaId
     * @return  stdClass
     */
    public function getMediaData($mediaId)
    {
        $mediaParams = array(
            AdobeStockParams::MEDIA_ID=>$mediaId
        );
        /* $postParams = array(
            AdobeStockParams::RESULT_ID,
            AdobeStockParams::RESULT_TITLE,
            AdobeStockParams::RESULT_KEYWORDS,
            AdobeStockParams::RESULT_THUMB_1000,
            AdobeStockParams::RESULT_TYPE_ID
        ); */
        $postParams = '&'.AdobeStockParams::RESULT_ID.'&'.
            AdobeStockParams::RESULT_TITLE.'&'.
            AdobeStockParams::RESULT_KEYWORDS.'&'.
            AdobeStockParams::RESULT_THUMB_1000.'&'.       
            AdobeStockParams::RESULT_TYPE_ID.'&'.
            AdobeStockParams::RESULT_VIDEO_PREVIEW;

        $url = $this->getFullURI(AdobeStockParams::MEDIA_DATA_CMD, $mediaParams) . $postParams;
        return $this->checkResponse($this->post($url, $postParams));
    }

    /**
    *  This method return the URL of an image preview
    *  @param string $mediaId
    *  @param string $mediaSize
    *  @return string
    */
    public function getMediaPreview($mediaId, $mediaSize=DEF_MEDIA_SIZE)
    {
         $postParams = array(
            AdobeStockParams::API_KEY => $this->apiKey,
            AdobeStockParams::MEDIA_IMAGE_CODE => $mediaId
           );
        $url = $this->getFullURI(AdobeStockParams::ASSET_PREVIEW_CMD, $postParams);
        return $url;
    }

    /**
    *  This method return an Id for download an image as resolution is indicated
    *  @param string $usage
    *  @param string $saveAs
    *  @param string $resolution
    *  @param string $mediaId
    *  @return stdClass
    */
    public function getDownloadURL($usage, $saveAs, $resolution, $mediaId)
    {
         $postParams = array(
            AdobeStockParams::API_KEY => $this->apiKey,
            AdobeStockParams::USAGE => $usage,
            AdobeStockParams::SAVE_AS => $saveAs,
            'resolution' => $resolution,
            'mediacode' => $mediaId
           );
        $url = $this->getFullURI(AdobeStockParams::GET_DOWNLOAD_URL, $postParams);
        return $this->checkResponse($this->post($url, $postParams));
    }

    /**
    *  This method makes the image download
    * @param   string $downloadId
    * @return  string
    */
    public function goDownload($downloadId)
    {
        $postParams = array(
            AdobeStockParams::API_KEY => $this->apiKey,
            'downloadId' => $downloadId
           );
        $url = $this->getFullURI(AdobeStockParams::DOWNLOAD_CMD, $postParams);
        return $url;
    }

    /**
     * Generate the full URI to use for API calls
     *
     * @param  string $method
     * @param  array  $query
     * @return string
     */
    private function getFullURI($method, array $query = NULL)
    {
        $uri = $this->apiUrl . $method;

        $uri .= http_build_query($query);

        return $uri;
    }

    /**
     * Check whether the response is success.
     *
     * @param   string $response
     * @return  strClass
     * @throws  EResponseFail
     */
    protected function checkResponse($response)
    {
        if ($response == 'Not found') {
            $result = $response;
        } else {

            try {
                if ($response === false)
                    throw new EServiceUnavailable('Empty search');

                $result = $this->decodeResponse($response);

                if (!is_object($result)) {
                    throw new EResponseFail('The rpc response is empty, or have invalid format');
                }

                //if ('failure' == $result->type) {
                //    throw new EDepositApiCall($result->errormsg, $result->errorcode);
                //}
            }
            catch (EResponseFail $e){
                $result = $e->errorMessage();
                //return false;
            }
            catch (EServiceUnavailable $e){
                $result = $e->errorMessage();
                //return false;
            }
        }
        
        return $result;
    }

    /**
     * Decodes response from JSON format.
     *
     * @param   string $response
     * @return  array|stdClass
     */
    protected function decodeResponse($response)
    {
        return json_decode($response);
    }

    /**
     * Sends the POST request to the API service.
     *
     * This method uses the cURL extension.
     *
     * @param   array $url
     * @param   array $parameters
     * @return  string
     */
    protected function post($url, $parameters)
    {
        return $this->createHttpClient()->post($url, $parameters);
    }

    /**
     * For testing purposes.
     *
     * @return AdobeCurlHttpClient
     */
    protected function createHttpClient()
    {
        return new AdobeCurlHttpClient();
    }

}

/**
 * Simple HTTP client based on cURL extension.
 *
 * You may use another HTTP client with one requared method {@link post}
 */
class AdobeCurlHttpClient
{
    /**
     * The cURL resource handle.
     *
     * @var     resource
     */
    protected $ch;

    /**
     * Object constructor
     */
    public function __construct()
    {
        $this->ch = curl_init();
    }

    /**
     * Sends the HTTP POST request to specified URL with given parameters.
     *
     * @param   string $url         the URL to request
     * @param   array $parameters   the POST parameters to include to request
     * @return  string              the server response
     */
    public function post($url, $parameters=[])
    {
        try {
            if (false === curl_setopt_array($this->ch, array(
                CURLOPT_RETURNTRANSFER  => true,
                CURLOPT_URL             => $url,
                CURLOPT_HTTPHEADER => array(
                    'x-api-key: d6d8c59fc3fc4b498f38ea3c3a345802',
                    'x-product: MySampleApp/1.0'
            )))) {
                throw new Exception('Error at setting cURL options, reason: '.curl_error($this->ch), curl_errno($this->ch));
            }

            if (false === $result = curl_exec($this->ch)) {
                throw new Exception('Error at execute cURL request, reason: '.curl_error($this->ch), curl_errno($this->ch));
            }

            if ('No results' === $result)
                $result = false;

            elseif (200 != curl_getinfo($this->ch, CURLINFO_HTTP_CODE)) {
                throw new Exception('Not found');
            }
        }
        catch (Exception $e) {
            $result = $e->errorMessage();
            //show_error($e->errorMessage());
        }

        return $result;
    }

    /**
     * Object destructor.
     */
    public function __destruct()
    {
        if (is_resource($this->ch)) {
            curl_close($this->ch);
        }
    }
}

/* class EAdobeClient extends Exception {
    public function errorMessage() {
    //error message
    // $errorMsg = 'Error on line '.$this->getLine().' in '.$this->getFile()
    // .': <b>'.$this->getMessage();
    $errorMsg = $this->getMessage();
    return $errorMsg;
  }

};
class EResponseFail extends EAdobeClient {};
class EDepositApiCall extends EAdobeClient {};
class ECurlFail extends EAdobeClient {};
class EServiceUnavailable extends EAdobeClient {};
class EAuthenticationRequired extends EAdobeClient {}; */

?>
