<?php
/**
 * This file brings in the 2 basic salesking classes
 * @version     1.0.0
 * @package     SalesKing PHP SDK
 * @license     MIT License; see LICENSE
 * @copyright   Copyright (C) 2012 David Jardin
 * @link        http://www.salesking.eu
 */

require_once("object.php");
require_once("collection.php");
require_once("helper.php");

// make sure that curl is available
if(!function_exists("curl_init")) {
    throw new Exception("curl required");
}

/**
 * SaleskingException class
 * @since 1.0.0
 * @package SalesKing PHP SDK
*/
class SaleskingException extends Exception {
    /**
     * internal error code
     * @var string internal error code
     * @since 1.0.0
     */
    protected $code = null;

    /**
     * error message
     * @var string error message
     * @since 1.0.0
     */
    protected $message = null;

    /**
     * additional error information
     * @var mixed additional error information (http response code, api errors messages..)
     * @since 1.0.0
     */
    protected $errors = null;

    /**
     * constructs a new salesking exception
     * @param string $code error code
     * @param string $message error message
     * @param mixed $errors additional error information
     * @since 1.0.0
     */
    public function __construct($code, $message, $errors = null) {
        $this->code = $code;
        $this->message = $message;
        $this->errors = $errors;

        parent::__construct($message);
    }

    /**
     * Return additional error information
     * @return mixed
     * @since 1.0.0
     */
    public function getErrors()
    {
        return $this->errors;
    }
}

/**
 * Salesking API interface
 * @since 1.0.0
 * @package SalesKing PHP SDK
*/
class Salesking {
    /**
     * current SDK Version
     * @const VERSION current SDK version
     * @since 1.0.0
     */
    const VERSION = "1.0.0";

    /**
     * common curl options
     * @var array some common curl options
     * @since 1.0.0
    */
    public $curl_options = array(
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 60,
        CURLOPT_USERAGENT      => 'salesking-sdk-1.0',
        CURLOPT_SSL_VERIFYPEER => false,
        CURLINFO_HEADER_OUT => true
    );

    /**
     * access token
     * @var string access token
     * @since 1.0.0
     */
    public $accessToken = null;

    /**
     * api url
     * @var string sk api url
     * @since 1.0.0
     */
    public $sk_url = null;

    /**
     * app id
     * @var string salesking app id
     * @since 1.0.0
     */
    public $app_id = null;

    /**
     * app secret
     * @var string salesking app secret
     * @since 1.0.0
     */
    public $app_secret = null;

    /**
     * app url
     * @var string salesking app url
     * @since 1.0.0
     */
    public $app_url = null;

    /**
     * Constructor method which is used to set some config stuff
     * @param $config array
     * @since 1.0.0
     */
    public function __construct($config = array())
    {
        //make sure that all required variables are available
        if(!array_key_exists("app_url",$config)
            OR !array_key_exists("sk_url",$config)
            OR !array_key_exists("app_id",$config)
            OR !array_key_exists("app_secret",$config))
        {
            throw new SaleskingException("INITLIBRARY_MISSINGCONF","Could not initialize library - missing configuration parameters");
        }

        // set static properties
        $this->sk_url = $config['sk_url'];
        $this->app_url = $config['app_url'];
        $this->app_id = $config['app_id'];
        $this->app_secret = $config['app_secret'];

        if(array_key_exists("accessToken",$config))
        {
            $this->accessToken = $config['accessToken'];
        }
    }

    /**
     * get the current app id
     * @return string application id
     * @since 1.0.0
     */
    public function getAppID()
    {
        return $this->app_id;
    }

    /**
     * set a new app id
     * @param $app_id
     * @return Salesking
     * @since 1.0.0
     */
    public function setAppID($app_id)
    {
        $this->app_id = $app_id;
        return $this;
    }

    /**
     * get the current app secret
     * @return string application secret
     * @since 1.0.0
     */
    public function getAppSecret()
    {
        return $this->app_secret;
    }

    /**
     * Set a new app secret
     * @param $app_secret
     * @return Salesking
     * @since 1.0.0
     */
    public function setAppSecret($app_secret)
    {
        $this->app_secret = $app_secret;
        return $this;
    }

    /**
     * get current app url
     * @return string application url
     * @since 1.0.0
     */
    public function getAppUrl()
    {
        return $this->app_url;
    }

    /**
     * set a new app URL
     * @param $app_url
     * @return Salesking
     * @since 1.0.0
     */
    public function setAppUrl($app_url)
    {
        $this->app_url = $app_url;
        return $this;
    }

    /**
     * get current Salesking API URL
     * @return string salesking subdomain url
     * @since 1.0.0
     */
    public function getSkUrl()
    {
        return $this->sk_url;
    }

    /**
     * Set a new Salesking API URL
     * @param $sk_url
     * @return Salesking
     * @since 1.0.0
     */
    public function setSkUrl($sk_url)
    {
        $this->sk_url = $sk_url;
        return $this;
    }

    /**
     * get the current AccessToken
     * @return null|string access token
     * @since 1.0.0
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * Set a new accessToken
     * @param $accessToken
     * @return Salesking
     * @since 1.0.0
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
        return $this;
    }

    /**
     * Returns a new SaleskingObject
     * @param $type string object type
     * @return SaleskingObject
     * @since 1.0.0
     */
    public function getObject($type)
    {
        $config = array(
            "type" => $type
        );

        return new SaleskingObject($this,$config);
    }

    /**
     * Returns a new SaleskingCollection object
     * @param $type string object type
     * @return SaleskingCollection
     * @since 1.0.0
     */
    public function getCollection($config)
    {
        if(!is_array($config))
        {
            $config = array(
                "type" => $type
            );
        }

        return new SaleskingCollection($this,$config);
    }

    /**
     * Make a request against the Salesking API
     * @param null $url the url endpoint including a starting /
     * @param string $method the HTTP Method (GET; POST; PUT; DELETE)
     * @param null $data The json_encoded data to send with to the api
     * @return array Result with message body and status code
     * @throws SaleskingException
     * @since 1.0.0
     */
    public function request($url,$method = "GET", $data = null)
    {
        $curl = curl_init();

        //create options
        $options = $this->curl_options;
        $options[CURLOPT_POSTFIELDS] = $data;
        $options[CURLOPT_URL] =  $this->sk_url.$url;
        $options[CURLOPT_CUSTOMREQUEST] = $method;
        $options[CURLOPT_HTTPHEADER] = array("Content-type: application/json");

        // set accessToken
        if($this->accessToken)
        {
            $options[CURLOPT_HTTPHEADER][] = "Authorization: Bearer ".$this->accessToken;
        }

        //set options to curl handler
        curl_setopt_array($curl, $options);

        //execute curl request
        $result['body'] = json_decode(curl_exec($curl));

        //a really bad curl error occured
        if ($result === false) {
            $e = new SaleskingException("REQUEST_CURLERROR","A curl error occured",array("code" => curl_errno($curl), "message" => curl_error($curl)));
            curl_close($curl);
            throw $e;
        }

        //assign return code
        $result['code'] = curl_getinfo($curl,CURLINFO_HTTP_CODE);

        curl_close($curl);

        return $result;
    }

    /**
    * Generate an Authorization URL
    * @param string $scope required scope
    * @return string authorization url
    * @since 1.0.0
    */
    public function requestAuthorizationURL( $scope)
    {
        return $this->sk_url . "/oauth/authorize?" .
            "client_id=". $this->app_id .
            "&scope=" . urlencode($scope).
            "&redirect_uri=" . urlencode($this->app_url);
    }

    /**
     * request accesstoken from Salesking API
     * @param $code
     * @return string AccessToken
     * @throws SaleskingException
     * @since 1.0.0
     */
    public function requestAccessToken( $code)
    {
        $response = $this->request( accessTokenUrl($code) );

        if($response['code'] == "200")
        {
            return $response['body'];
        }

        throw new SaleskingException("REQUESTTOKEN_ERROR","Could not fetch access_token",$response);
    }

    /**
     * Construct accesstoken URI
     * @param $code
     * @return string AccessToken
     * @throws SaleskingException
     * @since 1.0.0
     */
    public function accessTokenUrl( $code)
    {
        return $this->sk_url . "/oauth/token?"
            . "client_id=". $this->app_id
            . "&redirect_uri=" . urlencode($this->app_url)
            . "&client_secret=" . $this->app_secret
            . "&code=" . $code;
    }
}