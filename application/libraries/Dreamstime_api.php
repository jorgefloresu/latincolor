<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('DreamstimeClientConfig.php');
require_once('DreamstimeParams.php');

/**
 * Interface to the DREAMSTIME API client.
 *
 *
 */
class Dreamstime_Api
{
    const VERSION = '0.1';

    /**
     * DREAMSTIME uri.
     *
     * @var     string
     */
    protected $apiUrl;

    /**
     * DREAMSTIME API key.
     *
     * @var     string
     */
    protected $apiKey;

    /**
     * DREAMSTIME API password.
     *
     * @var     string
     */
    protected $apiPwd;

    /**
     * DREAMSTIME API session id.
     *
     * @var     string
     */
    protected $sessionId;

    /**
     * DREAMSTIME API session name.
     *
     * @var     string
     */
    protected $sessionName;


    /**
     * Constructor
     *
     * @param   string $apiUrl  DREAMSTIME RPC uri
     * @param   string $apiKey  DREAMSTIME API key
     */
    public function  __construct()
    {
        $this->apiUrl = DREAMSTIME_API_URL;
        $this->apiKey = DREAMSTIME_API_KEY;
        $this->apiPwd = DREAMSTIME_API_PASSWORD;
    }

    public function getApiKey()
    {
        echo "Api Url:".$this->apiUrl."<br/>";
        echo "Api Key:".$this->apiKey."<br/>";
        echo "Api Pwd:".$this->apiPwd;
    }

     /*
     * @param   GET variables string request
     * @return  XML
     */
    public function search($criteria = array())
    {
        $postParams = array(
            DreamstimeParams::API_KEY => $this->apiKey,
            DreamstimeParams::API_PASSWORD => $this->apiPwd,
            DreamstimeParams::TYPE_NAME => DreamstimeParams::TYPE_CMD,
            DreamstimeParams::REQUEST_NAME => DreamstimeParams::SEARCH_CMD
            );

        $postParams = array_merge($postParams, $criteria);
        $url = $this->getFullURI(DreamstimeParams::API_CMD, $postParams);
        return $url;
        //return $this->checkResponse($this->post($url, $postParams));
    }

    /**
     *
     * @param   integer $mediaId
     * @return  stdClass
     */
    public function getMediaData($mediaId)
    {
        $postParams = array(
            DreamstimeParams::API_KEY => $this->apiKey,
            DreamstimeParams::API_PASSWORD => $this->apiPwd,
            DreamstimeParams::TYPE_NAME => DreamstimeParams::TYPE_CMD,
            DreamstimeParams::REQUEST_NAME => DreamstimeParams::MEDIA_DATA_CMD,
            DreamstimeParams::MEDIA_IMAGE_CODE => $mediaId
           );
        $url = $this->getFullURI(DreamstimeParams::API_CMD, $postParams);
        //echo $url;
        return $this->checkResponse($this->post($url, $postParams));
    }

    public function getVideoData($mediaId)
    {
        $postParams = array(
            DreamstimeParams::API_KEY => $this->apiKey,
            DreamstimeParams::API_PASSWORD => $this->apiPwd,
            DreamstimeParams::TYPE_NAME => DreamstimeParams::TYPE_CMD,
            DreamstimeParams::REQUEST_NAME => DreamstimeParams::MEDIA_VIDEO_CMD,
            DreamstimeParams::MEDIA_VIDEO_CODE => $mediaId
           );
        $url = $this->getFullURI(DreamstimeParams::API_CMD, $postParams);
        //echo $url;
        return $this->checkResponse($this->post($url, $postParams));
    }

    public function goImageDownload($media, $size)
    {
        $postParams = array(
            DreamstimeParams::API_KEY => $this->apiKey,
            DreamstimeParams::API_PASSWORD => $this->apiPwd,
            DreamstimeParams::TYPE_NAME => DreamstimeParams::TYPE_CMD,
            DreamstimeParams::REQUEST_NAME => DreamstimeParams::DOWNLOAD_CMD,
            DreamstimeParams::MEDIA_IMAGE_CODE => $media,
            DreamstimeParams::MEDIA_SIZE => $size
          );

        $url = $this->getFullURI(DreamstimeParams::API_CMD, $postParams);
        //return $url;
        return $this->checkResponse($this->post($url, $postParams));
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
        $result = $this->decodeResponse($response);
        if (!is_object($result)) {
            show_error('The rpc response is empty, or have invalid format');
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
        $xml = simplexml_load_string($response, NULL, LIBXML_NOCDATA);
        if ($xml)
            return $xml;
        else
            return $response;

    }

    protected function is_valid_xml ( $xml ) {
        libxml_use_internal_errors( true );

        $doc = new DOMDocument('1.0', 'utf-8');

        $doc->loadXML( $xml );

        $errors = libxml_get_errors();

        return empty( $errors );
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
     * @return CurlHttpClient
     */
    protected function createHttpClient()
    {
        return new DreamCurlHttpClient();
    }

}

/**
 * Simple HTTP client based on cURL extension.
 *
 * You may use another HTTP client with one requared method {@link post}
 */

class DreamCurlHttpClient
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
    public function post($url, $parameters)
    {
        if (false === curl_setopt_array($this->ch, array(
            CURLOPT_POST            => true,
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_URL             => $url,
            CURLOPT_POSTFIELDS      => $parameters
        ))) {
            //throw new ECurlFail('Error at setting cURL options, reason: '.curl_error($this->ch), curl_errno($this->ch));
            show_error('Error at setting cURL options, reason: '.curl_error($this->ch), curl_errno($this->ch));
        }

        if (false === $result = curl_exec($this->ch)) {
            //throw new ECurlFail('Error at execute cURL request, reason: '.curl_error($this->ch), curl_errno($this->ch));
            show_error('Error at execute cURL request, reason: '.curl_error($this->ch), curl_errno($this->ch));
        }
        $curlgetinfo = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
        if (200 != $curlgetinfo) {
            if (302 != $curlgetinfo)
            //throw new EServiceUnavailable('The API servise is unavailable');
                show_error($curlgetinfo.'-'.curl_error($this->ch), curl_errno($this->ch));
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

/*
class EDepositClient extends Exception {};
class EResponseFail extends EDepositClient {};
class EDepositApiCall extends EDepositClient {};
class ECurlFail extends EDepositClient {};
class EServiceUnavailable extends EDepositClient {};
class EAuthenticationRequired extends EDepositClient {};
*/
?>
