<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('DepositClientConfig.php');
require_once('RpcParams.php');

/**
 * Interface to the DepositPhotos RPC API client.
 *
 * @copyright   Copyright (c) 2010 DepositPhotos Inc.
 */
class DepositClient
{
    const VERSION = '0.1';

    /**
     * DepositPhotos RPC uri.
     *
     * @var     string
     */
    protected $apiUrl;

    /**
     * DepositPhotos API key.
     *
     * @var     string
     */
    protected $apiKey;

    /**
     * Deposit API session id.
     *
     * @var     string
     */
    protected $sessionId;

    /**
     * Deposit API session name.
     *
     * @var     string
     */
    protected $sessionName;


    /**
     * Constructor
     *
     * @param   string $apiUrl  DepositPhotos RPC uri
     * @param   string $apiKey  DepositPhotos API key
     */
    public $fullurl;


    public function  __construct($params)
    {
        $this->apiUrl = DEPOSIT_API_URL;
        $this->apiKey = DEPOSIT_API_RESELLER_KEY;
        $this->sessionId = $params['sessionid'];
    }

    public function getApiKey()
    {
        echo "Api Key:".$this->apiKey."<br/>";
        echo "Api Url:".$this->apiUrl;
    }
    /**
     * This method makes possible to search media in the DepositPhotos image bank.
     *
     * The $criteria array must conform to the following format:
     * <code>
     * array(
     *  // all params are optional
     *  RpcParams::SEARCH_QUERY  => 'query string',
     *  RpcParams::SEARCH_SORT   => 'sort result by one of 1-6 sort types',
     *                              // WEIGHT       = 1;
     *                              // DOWNLOADS    = 2;
     *                              // POPULARITY   = 3;
     *                              // BEST_SALES   = 4;
     *                              // TIME         = 5;
     *                              // TIME_DESC    = 6;
     *  RpcParams::SEARCH_LIMIT  => 'limit of results to return',
     *  RpcParams::SEARCH_OFFSET => 'results offset', // if used without RpcParams::SEARCH_LIMIT, then ignored
     *  RpcParams::SEARCH_CATEGORIES => 'list of categories id's separated by whitspace',
     *  RpcParams::SEARCH_COLOR  => 'search by one of 1-17 colors',
     *  RpcParams::SEARCH_NUDITY => 'if true, exclude nudity photos',
     *  RpcParams::SEARCH_EXTENDED   => 'if true, include only with extended license',
     *  RpcParams::SEARCH_EXCLUSIVE  => 'if true, include only with exclusive',
     *  RpcParams::SEARCH_USER   => 'media author id',
     *  RpcParams::SEARCH_DATE1  => 'include results from date',
     *  RpcParams::SEARCH_DATE2  => 'include results to date',
     *  RpcParams::SEARCH_ORIENTATION=> 'image orientation, can be one of RpcParams::ORIENT_* constants',
     *  RpcParams::SEARCH_IMAGESIZE  => 'image size, can be one of RpcParams::SIZE_* constants',
     *  RpcParams::SEARCH_VECTOR => 'if true, include only vector media',
     *  RpcParams::SEARCH_PHOTO  => 'if true, include only photo media' // if both, vector and photo media is true, returns all media
     * )
     * </code>
     *
     * Response format:
     * <code>
     * array(
     *  0 => stdClass(
     *      id -> 'media id'
     *      url -> 'small tumbnail url'
     *      title -> 'media title'
     *      description -> 'media description'
     *      userid -> 'author id'
     *  )
     *  [, 1 -> ...]
     * )
     * </code>
     *
     * @param   array $criteria
     * @return  stdClass
     */
    public function search($criteria = array())
    {
        $postParams = array(
            RpcParams::APIKEY   => $this->apiKey,
            RpcParams::COMMAND  => RpcParams::SEARCH_CMD
            );

        $postParams = array_merge($postParams, $criteria);
        //return $this->post($this->apiUrl, $postParams);
        return $this->apiUrl . "?". http_build_query($postParams);
        //return $this->checkResponse($this->post($this->apiUrl, $postParams));
    }

    public function getRelated($mediaId)
    {
        $postParams = array(
            RpcParams::APIKEY   => $this->apiKey,
            RpcParams::COMMAND  => RpcParams::GET_RELATED_CMD,
            RpcParams::RELATED_TYPE  => 'similar',
            RpcParams::RELATED_LIMIT  => 15,
            RpcParams::RELATED_SEARCH_ID => $mediaId
            );

        return $this->checkResponse($this->post($this->apiUrl, $postParams));
    }

    /**
     * This method returns list of categories used on the DepositPhotos website.
     *
     * Response format:
     * <code>
     * stdClass(
     *  category_id -> 'category title'
     *  [, category_id -> ...]
     * )
     * </code>
     *
     * @return  stdClass
     */
    public function getCategoriesList()
    {
        $postParams = array(
            RpcParams::APIKEY   => $this->apiKey,
            RpcParams::COMMAND  => RpcParams::GET_CATEGORIES_CMD);

        return $this->checkResponse($this->post($this->apiUrl, $postParams))->result;
    }

    /**
     * This method return all information about a media.
     *
     * Response format:
     * <code>
     * stdClass(
     *  id -> 'media id'
     *  userid -> 'author id'
     *  username -> 'author name'
     *  title -> 'media title'
     *  description -> 'media description'
     *  published -> 'publish date'
     *  isextended -> 'is extended license'
     *  isexclusive -> 'is exclusive'
     *  width -> 'media width'
     *  height -> 'media height'
     *  mp -> 'media mega pixels'
     *  views -> 'count of media views'
     *  downloads -> 'count of media downloads'
     *  tags -> array( // tags associated with media
     *      0 => 'tag name'
     *      [, 1 => ...]
     *  )
     *  categories -> stdClass( // categories associated with media
     *      category_id -> 'category title'
     *      [, category_id -> ...]
     *  )
     *  url -> 'large tumbnail url'
     * )
     * </code>
     *
     * @param   integer $mediaId
     * @return  stdClass
     */
    public function getMediaData($mediaId)
    {
        $postParams = array(
            RpcParams::APIKEY   => $this->apiKey,
            RpcParams::COMMAND  => RpcParams::GET_MEDIA_DATA_CMD,
            RpcParams::MEDIA_ID => $mediaId
            );
            //echo $this->post($this->apiUrl, $postParams);
        return $this->checkResponse($this->post($this->apiUrl, $postParams));
    }

    /**
     * This method returns most searched tag and most used tag on the DepositPhotos website.
     *
     * This method may help you to create a tags cloud.
     *
     * Response format:
     * <code>
     * array(
     *  0 => stdClass(
     *      rate -> 'rate of tag'
     *      tag -> 'tag name'
     *  )
     *  [, 1 => ...]
     * )
     * </code>
     *
     * @return  array
     */
    public function getTagCloud()
    {
        $postParams = array(
            RpcParams::APIKEY   => $this->apiKey,
            RpcParams::COMMAND  => RpcParams::GET_TAG_CLOUD_CMD);

        return $this->checkResponse($this->post($this->apiUrl, $postParams))->result;
    }

    /**
     * This method authenticates the API client and gives the session ID.
     *
     * NOTE: Some methods require authentication before calling.
     *
     * @param   string $user
     * @param   string $password
     * @return  array   of session id and session name
     */
    public function login($user, $password)
    {
        $postParams = array(
            RpcParams::APIKEY       => $this->apiKey,
            RpcParams::COMMAND      => RpcParams::LOGIN_CMD,
            RpcParams::LOGIN_USER   => $user,
            RpcParams::LOGIN_PASSWORD=> $password);

        /* @var $result HttpRpcLogin */
        $result = $this->checkResponse($this->post($this->apiUrl, $postParams));

        //$this->sessionId    = $result->sessionid;
        //$this->sessionName  = $result->sessionName;
        //return array($this->sessionId, $this->sessionName);
        return $result;
    }

    public function logout()
    {
        $postParams = array(
            RpcParams::APIKEY       => $this->apiKey,
            RpcParams::COMMAND      => RpcParams::LOGOUT_CMD,
            RpcParams::SESSION_ID   => $this->getSessionId());

        return $this->checkResponse($this->post($this->apiUrl, $postParams));
    }
    /**
     * This method returns download link for specified media.
     *
     * NOTE: This method require authentication before calling.
     *
     * @param   integer $mediaId
     * @return  string
     */
    public function getMedia($mediaId, $license = RpcParams::LICENSE_STANDARD, $size = RpcParams::SIZE_SMALL, $subaccountId = null)
    {
        $this->checkLoggedIn();

        $postParams = array(
            RpcParams::APIKEY       => $this->apiKey,
            RpcParams::COMMAND      => RpcParams::GET_MEDIA_CMD,
            RpcParams::SESSION_ID   => $this->getSessionId(),
            RpcParams::MEDIA_ID     => $mediaId,
            RpcParams::MEDIA_LICENSE=> $license,
            RpcParams::MEDIA_OPTION => $size);

        if (null != $subaccountId) {
            $postParams[RpcParams::SUBACC_ID] = $subaccountId;
        }

        return $this->checkResponse($this->post($this->apiUrl, $postParams));
    }

    public function reDownload($licenseId, $subaccountId=null)
    {
        $this->checkLoggedIn();

        $postParams = array(
            RpcParams::APIKEY       => $this->apiKey,
            RpcParams::COMMAND      => RpcParams::RE_DOWNLOAD_CMD,
            RpcParams::SESSION_ID   => $this->getSessionId(),
            RpcParams::LICENSE_ID   => $licenseId);

        if (null != $subaccountId) {
            $postParams[RpcParams::SUBACC_ID] = $subaccountId;
        }

        return $this->checkResponse($this->post($this->apiUrl, $postParams));
    }

    public function getPurchases($limit = DEF_NUMBER_OF_ROWS, $offset = 0, $sortfield = null, $sorttype = null, $dateformat = null)
    {
        $this->checkLoggedIn();

        $postParams = array(
            RpcParams::APIKEY       => $this->apiKey,
            RpcParams::COMMAND      => RpcParams::GET_PURCHASES_CMD,
            RpcParams::SESSION_ID   => $this->getSessionId(),
            RpcParams::PURCHASES_LIMIT => $limit,
            RpcParams::PURCHASES_OFFSET => $offset);

        if (null != $sortfield) {
            $postParams[RpcParams::PURCHASES_SORT_FIELD] = $sortfield;
        }
        if (null != $sorttype) {
            $postParams[RpcParams::PURCHASES_SORT_TYPE] = $sorttype;
        }
        if (null != $dateformat) {
            $postParams[RpcParams::PURCHASES_DATETIME_FORMAT] = $dateformat;
        }

        return $this->checkResponse($this->post($this->apiUrl, $postParams));
    }

    /**
     * This method creates a subaccount of reseller's account, which made the purchase.
     *
     * NOTE: This method require authentication before calling.
     *
     * @param   string $email
     * @param   string $firstName
     * @param   string $lastName
     * @param   string $company
     * @return  integer
     */
    public function createSubaccount($sessionId, $subUsername, $subPass, $email, $firstName, $lastName, $company = null)
    {
        //$this->sessionId    = $sessionId;

        $this->checkLoggedIn();

        $postParams = array(
            RpcParams::APIKEY       => $this->apiKey,
            RpcParams::COMMAND      => RpcParams::CREATE_SUBACCOUNT_CMD,
            RpcParams::SESSION_ID   => $sessionId,
            RpcParams::SUBACC_USERNAME => $subUsername,
            RpcParams::SUBACC_PASSWORD => $subPass,
            RpcParams::SUBACC_EMAIL => $email,
            RpcParams::SUBACC_FNAME => $firstName,
            RpcParams::SUBACC_LNAME => $lastName,
            RpcParams::SUBACC_SEND_MAIL => 0);

        if (null != $company) {
            $postParams[RpcParams::SUBACC_COMPANY] = $company;
        }

        return $this->checkResponse($this->post($this->apiUrl, $postParams)); //->subaccountId;
    }

    /**
     * This method deletes subaccount, created by method createSubaccount.
     *
     * NOTE: This method require authentication before calling.
     *
     * @param   integer $subaccountId
     */
    public function deleteSubaccount($subaccountId)
    {
        $this->checkLoggedIn();

        $postParams = array(
            RpcParams::APIKEY       => $this->apiKey,
            RpcParams::COMMAND      => RpcParams::DELETE_SUBACCOUNT_CMD,
            RpcParams::SESSION_ID   => $this->getSessionId(),
            RpcParams::SUBACC_ID    => $subaccountId);

        return $this->checkResponse($this->post($this->apiUrl, $postParams));
    }

    /**
     * This method updates subaccount, created by method createSubaccount.
     *
     * NOTE: This method require authentication before calling.
     *
     * @param   integer $subaccountId
     * @param   string $email
     * @param   string $firstName
     * @param   string $lastName
     * @param   string $company
     */
    public function updateSubaccount($subaccountId, $email, $firstName, $lastName, $company = null)
    {
        $this->checkLoggedIn();

        $postParams = array(
            RpcParams::APIKEY       => $this->apiKey,
            RpcParams::COMMAND      => RpcParams::UPDATE_SUBACCOUNT_CMD,
            RpcParams::SESSION_ID   => $this->getSessionId(),
            RpcParams::SUBACC_ID    => $subaccountId,
            RpcParams::SUBACC_EMAIL => $email,
            RpcParams::SUBACC_FNAME => $firstName,
            RpcParams::SUBACC_LNAME => $lastName);

        if (null != $company) {
            $postParams[RpcParams::SUBACC_COMPANY] = $company;
        }
        return $this->checkResponse($this->post($this->apiUrl, $postParams));
    }

    /**
     * This method returns the subaccounts id's, created by reseller.
     *
     * NOTE: This method require authentication before calling.
     *
     * @param   integer $limit
     * @param   integer $offset
     * @return  array   of total subaccounts count and requested range of
     *                  subaccounts id's.
     */
    public function getSubaccounts($limit = null, $offset = null)
    {
        $this->checkLoggedIn();

        $postParams = array(
            RpcParams::APIKEY       => $this->apiKey,
            RpcParams::COMMAND      => RpcParams::GET_SUBACCOUNTS_CMD,
            RpcParams::SESSION_ID   => $this->getSessionId(),
            RpcParams::SUBACC_LIMIT => 10,
            RpcParams::SUBACC_OFFSET => 0);
        /*
        if (null !== $limit) {
            $postParams[RpcParams::SUBACC_LIMIT] = $limit;
        }

        if (null !== $offset) {
            $postParams[RpcParams::SUBACC_OFFSET] = $offset;
        }
        */
        /* @var $result HttpRpcSubaccounts */
        $result = $this->checkResponse($this->post($this->apiUrl, $postParams));
        return $result;
        //return array($result->count, $result->subaccounts);
    }

    /**
     * This method returns the specified subaccount data.
     *
     * NOTE: This method require authentication before calling.
     *
     * @param   integer $subaccountId
     * @return  stdClass
     */
    public function getSubaccountData($subaccountId)
    {
        $this->checkLoggedIn();

        $postParams = array(
            RpcParams::APIKEY       => $this->apiKey,
            RpcParams::COMMAND      => RpcParams::GET_SUBACCOUNT_DATA_CMD,
            RpcParams::SESSION_ID   => $this->getSessionId(),
            RpcParams::SUBACC_ID    => $subaccountId);

        return $this->checkResponse($this->post($this->apiUrl, $postParams));
    }

    /**
     * This method returns all data about subaccount purchases.
     *
     * NOTE: This method require authentication before calling.
     *
     * @param   integer $subaccountId
     * @return  array   of total purchases count and purchases data
     */
    public function getSubaccountPurchases($subaccountId, $limit = null, $offset = null)
    {
        $this->checkLoggedIn();

        $postParams = array(
            RpcParams::APIKEY       => $this->apiKey,
            RpcParams::COMMAND      => RpcParams::GET_SUBACCOUNT_PURCHASES_CMD,
            RpcParams::SESSION_ID   => $this->getSessionId(),
            RpcParams::SUBACC_ID    => $subaccountId);

        if (null !== $limit) {
            $postParams[RpcParams::SUBACC_LIMIT] = $limit;
        }

        if (null !== $offset) {
            $postParams[RpcParams::SUBACC_OFFSET] = $offset;
        }

        $result = $this->checkResponse($this->post($this->apiUrl, $postParams));

        //return array($result->count, $result->purchases);
        return $result;
    }

    public function getSubscriptions($subaccountId)
    {
        $this->checkLoggedIn();

        $postParams = array(
            RpcParams::APIKEY       => $this->apiKey,
            RpcParams::COMMAND      => RpcParams::GET_SUBSCRIPTION_OFFERS_CMD,
            RpcParams::SUBACC_ID    => $subaccountId,
            RpcParams::SESSION_ID   => $this->getSessionId());


        $result = $this->checkResponse($this->post($this->apiUrl, $postParams));

        return $result;

    }

    public function getSubscriptionOffers($subaccountId=null)
    {
        $this->checkLoggedIn();

        $postParams = array(
            RpcParams::APIKEY       => $this->apiKey,
            RpcParams::COMMAND      => RpcParams::GET_SUBSCRIPTION_OFFERS_CMD
            //RpcParams::SESSION_ID   => $this->getSessionId()
          );

        if (null !== $subaccountId) {
            $postParams[RpcParams::SUBACC_ID] = $subaccountId;
        }

        $result = $this->checkResponse($this->post($this->apiUrl, $postParams));

        return $result;
    }

    public function createSubaccountSubscription($subaccountId=null, $subscriptionId)
    {
        $this->checkLoggedIn();

        $postParams = array(
            RpcParams::APIKEY       => $this->apiKey,
            RpcParams::COMMAND      => RpcParams::CREATE_SUBSCRIPTION_CMD,
            RpcParams::SESSION_ID   => $this->getSessionId(),
            RpcParams::SUBSCRIPTION_ID => $subscriptionId);

        if (null !== $subaccountId) {
            $postParams[RpcParams::SUBACC_ID] = $subaccountId;
        }

        $result = $this->checkResponse($this->post($this->apiUrl, $postParams));

        return $result;
    }

    /**
     * This method returns text of proof of purchase.
     *
     * NOTE: This method require authentication before calling.
     *
     * @param   integer $subaccountId
     * @param   integer $licenseId
     * @return  string
     */
    public function getLicense($subaccountId, $licenseId)
    {
        $this->checkLoggedIn();

        $postParams = array(
            RpcParams::APIKEY       => $this->apiKey,
            RpcParams::COMMAND      => RpcParams::GET_LICENSE_CMD,
            RpcParams::SESSION_ID   => $this->getSessionId(),
            RpcParams::SUBACC_ID    => $subaccountId,
            RpcParams::SUBACC_LICENSE_ID => $licenseId);

        return $this->checkResponse($this->post($this->apiUrl, $postParams))->text;
    }

    /**
     * Sets the API session id.
     */
    public function setSessionId($id)
    {
        $this->sessionId = $id;
    }

    /**
     * Returns the API session id.
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }

    /**
     * This method returns count of stock photos, new photos by week and authors.
     *
     * Response format:
     * <code>
     * stdClass(
     *  totalFiles -> 'count of stock photos',
     *  totalWeekFiles -> 'count of new files by week',
     *  totalAuthors -> 'count of photographers'
     * )
     * </code>
     *
     * @return  stdClass
     */
    public function getInfo()
    {
        $postParams = array(
            RpcParams::APIKEY   => $this->apiKey,
            RpcParams::COMMAND  => RpcParams::GET_INFO_CMD);

        return $this->checkResponse($this->post($this->apiUrl, $postParams));
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
        try {
            $result = $this->decodeResponse($response);

            if ('failure' == $result->type) {
                //throw new EDepositApiCall($result->error->errormsg);
                if ($result->error->errorcode != 13001)
                  throw new Exception($result->error->errormsg, $result->error->errorcode);
                  //show_error($result->error->errormsg);
            }

            if (!is_object($result)) {
                //throw new EResponseFail('The rpc response is empty, or have invalid format');
                show_error('The rpc response is empty, or have invalid format');
            }
           if (isset($result->result)) {
                if (empty($result->result)){}
                    //show_error('Empty search');
                    //throw new EServiceUnavailable('Empty search');
            }

        }
        catch (Exception $e) {
          return array(
              'error' => array(
                  'msg' => $e->getMessage(),
                  'code' => $e->getCode(),
              ),
          );
        }
        // catch (EResponseFail $e){
        //     echo $e->errorMessage();
        //     return false;
        // }
        // catch (EServiceUnavailable $e){
        //     echo $e->errorMessage();
        //     return false;
        // }
        // catch (EDepositApiCall $e){
        //     echo $e->errorMessage();
        //     return false;
        // }


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
        $this->fullurl = $url . "?". http_build_query($parameters);
        return $this->createHttpClient()->post($url, $parameters);
        //return $this->fullurl;
    }

    /**
     * For testing purposes.
     *
     * @return CurlHttpClient
     */
    protected function createHttpClient()
    {
        return new DepCurlHttpClient();
    }

    /**
     * Check whether the session id is not empty.
     *
     * @throws  EAuthenticationRequired
     */
    protected function checkLoggedIn()
    {
        if (null === $this->getSessionId()) {
            //throw new EAuthenticationRequired('The called method requires authentication');
            show_error('The called method requires authentication');
        }
    }
}


/**
 * Simple HTTP client based on cURL extension.
 *
 * You may use another HTTP client with one requared method {@link post}
 */
class DepCurlHttpClient
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

        elseif (200 != $curlgetinfo = curl_getinfo($this->ch, CURLINFO_HTTP_CODE)) {
            //throw new EServiceUnavailable('The API servise is unavailable');
            show_error('The API servise is unavailable: '.$result);
        }
        //echo $curlgetinfo;
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
class EDepositClient extends Exception {
    public function errorMessage() {
    //error message
    $errorMsg = 'Error on line '.$this->getLine().' in '.$this->getFile()
    .': <b>'.$this->getMessage();
    return $errorMsg;
  }

};
class EResponseFail extends EDepositClient {};
class EDepositApiCall extends EDepositClient {};
class ECurlFail extends EDepositClient {};
class EServiceUnavailable extends EDepositClient {};
class EAuthenticationRequired extends EDepositClient {};
*/
?>
