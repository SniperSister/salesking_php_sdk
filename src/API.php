<?php
namespace Salesking\PHPSDK;

/**
 * This file brings in the basic salesking API class
 * @version     2.0.0
 * @package     SalesKing PHP SDK
 * @license     MIT License; see LICENSE
 * @copyright   Copyright (C) 2012 David Jardin
 * @link        http://www.salesking.eu
 */

/**
 * Salesking API interface
 * @since 2.0.0
 * @package SalesKing PHP SDK
*/
class API
{
    /**
     * current SDK Version
     * @const VERSION current SDK version
     * @since 2.0.0
     */
    const VERSION = "2.0.0";

    /**
     * common curl options
     * @var array some common curl options
     * @since 2.0.0
    */
    public $curl_options = array(
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 60,
        CURLOPT_USERAGENT      => 'salesking-sdk-2.0',
        CURLOPT_SSL_VERIFYPEER => false,
        CURLINFO_HEADER_OUT => true
    );

    /**
     * access token
     * @var string access token
     * @since 2.0.0
     */
    public $accessToken = null;

    /**
     * api url
     * @var string sk api url
     * @since 2.0.0
     */
    public $sk_url = null;
    /**
     * user
     * @var string user email address for SalesKing login
     * @since 2.0.0
     */
    public $user = null;
    /**
     * password
     * @var string password for salesking user
     * @since 2.0.0
     */
    public $password = null;

    /**
     * app id
     * @var string salesking app id
     * @since 2.0.0
     */
    public $app_id = null;

    /**
     * app scope
     * @var string salesking app scope
     * @since 2.0.0
     */
    public $app_scope = null;

    /**
     * app secret
     * @var string salesking app secret
     * @since 2.0.0
     */
    public $app_secret = null;

    /**
     * app url
     * @var string salesking app url
     * @since 2.0.0
     */
    public $redirect_url = null;

    /**
     * debugging switch
     * @var boolean debugging switch
     * @since 2.0.0
     */
    public $debug = false;

    /**
     * use http basic auth with username password
     * @var boolean
     * @since 1.1.0
     */
    public $use_basic_auth = false;
    /**
     * use oAuth with app id & secret
     * @var boolean
     * @since 1.1.0
     */
    public $use_oauth = false;

    /**
     * Constructor method which is used to set some config stuff
     * @param $config array with oAuth or HTTP Basic AUTH informations
     * @since 2.0.0
     * @throws Exception
     */
    public function __construct($config = array())
    {
        // make sure that curl is available
        if (!function_exists("curl_init")) {
            throw new Exception(
                "INITLIBRARY_MISSINGCURL",
                "Could not initialize library - missing curl"
            );
        }

        if (array_key_exists("debug", $config)) {
            $this->debug = $config['debug'];
        }

        //make sure that all required variables are available
        if (array_key_exists("user", $config)
            && array_key_exists("password", $config)
            && array_key_exists("sk_url", $config)
        ) {
            $this->use_basic_auth = true;
            $this->setBasicAuth($config);
        }

        if (!$this->use_basic_auth
            && array_key_exists("redirect_url", $config)
            && array_key_exists("sk_url", $config)
            && array_key_exists("app_id", $config)
            && array_key_exists("app_secret", $config)
        ) {
            $this->use_oauth = true;
            $this->setOauth($config);
        }

        if (!$this->use_basic_auth && !$this->use_oauth) {
            throw new Exception(
                "INITLIBRARY_MISSINGCONF",
                "Could not initialize library - missing authentication params"
            );
        }

    }

    /**
     * get the current app id
     * @return string application id
     * @since 2.0.0
     */
    public function getAppID()
    {
        return $this->app_id;
    }

    /**
     * set a new app id
     * @param $app_id
     * @return API
     * @since 2.0.0
     */
    public function setAppID($app_id)
    {
        $this->app_id = $app_id;
        return $this;
    }

    /**
     * get the current app secret
     * @return string application secret
     * @since 2.0.0
     */
    public function getAppSecret()
    {
        return $this->app_secret;
    }

    /**
     * Set a new app secret
     * @param $app_secret
     * @return API
     * @since 2.0.0
     */
    public function setAppSecret($app_secret)
    {
        $this->app_secret = $app_secret;
        return $this;
    }

    /**
     * get current redirect url
     * @return string application url
     * @since 2.0.0
     */
    public function getRedirectUrl()
    {
        return $this->redirect_url;
    }

    /**
     * set a new redirect URL
     * @param $redirect_url
     * @return API
     * @since 2.0.0
     */
    public function setRedirectUrl($redirect_url)
    {
        $this->redirect_url = $redirect_url;
        return $this;
    }

    /**
     * get current Salesking API URL
     * @return string salesking subdomain url
     * @since 2.0.0
     */
    public function getSkUrl()
    {
        return $this->sk_url;
    }

    /**
     * Set a new Salesking API URL
     * @param $sk_url
     * @return API
     * @since 2.0.0
     */
    public function setSkUrl($sk_url)
    {
        $this->sk_url = $sk_url;
        return $this;
    }

    /**
     * get the current AccessToken
     * @return null|string access token
     * @since 2.0.0
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * Set a new accessToken
     * @param $accessToken
     * @return API
     * @since 2.0.0
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
        return $this;
    }

    /**
     * Returns a new SaleskingObject
     * @param $obj_type string object type
     * @return Object
     * @since 2.0.0
     */
    public function getObject($obj_type)
    {
        $config = array( "obj_type" => $obj_type );

        return new Object($this, $config);
    }

    /**
     * Returns a new SaleskingCollection object
     * @param $config mixed configuration options
     * @return Collection
     * @since 2.0.0
     */
    public function getCollection($config)
    {
        if (!is_array($config)) {
            $config = array(
                "obj_type" => $config
            );
        }

        return new Collection($this, $config);
    }

    /**
     * Make a request against the Salesking API
     * @param null $url the url endpoint including a starting /
     * @param string $method the HTTP Method (GET; POST; PUT; DELETE)
     * @param null $data The json_encoded data to send with to the api
     * @return array Result with message body and status code
     * @throws Exception
     * @since 2.0.0
     */
    public function request($url, $method = "GET", $data = null)
    {
        # add base url if not present
        if (strpos($url, $this->sk_url) !== 0) {
            $url = $this->sk_url.$url;
        }

        $curl = curl_init();
        $options = $this->curl_options;
        $options[CURLOPT_POSTFIELDS] = $data;
        $options[CURLOPT_URL] = $url;
        $options[CURLOPT_CUSTOMREQUEST] = $method;
        $options[CURLOPT_HTTPHEADER] = array("Content-type: application/json");

        // set accessToken
        if ($this->use_oauth && $this->accessToken) {
            $options[CURLOPT_HTTPHEADER][] = "Authorization: Bearer ".$this->accessToken;
        }

        if ($this->use_basic_auth) {
            $options[CURLOPT_USERPWD] = $this->user.":".$this->password;
            $options[CURLOPT_HTTPAUTH] = CURLAUTH_BASIC;
        }

        //set options to curl handler
        curl_setopt_array($curl, $options);

        //execute curl request
        $result['body'] = json_decode(curl_exec($curl));

        // output debugging information
        if ($this->debug == true) {
            echo "<pre>";
            print_r(curl_getinfo($curl));  // get error info
            echo "\n\ncURL error number:" .curl_errno($curl); // print error info
            echo "\n\ncURL error:" . curl_error($curl);
            echo "</pre>\n";
        }

        //a really bad curl error occured
        if ($result === false || curl_errno($curl)) {
            $e = new Exception(
                "REQUEST_CURLERROR",
                "A curl error occured",
                array("code" => curl_errno($curl), "message" => curl_error($curl))
            );

            curl_close($curl);
            throw $e;
        }

        //assign return code
        $result['code'] = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        return $result;
    }

    /**
     * Set config parameters needed for oAuth Logins
     * @param array $config
     * @return string authorization url
     * @since 1.1.0
     */
    public function setOauth($config)
    {
        $this->sk_url = $config['sk_url'];
        $this->redirect_url = $config['redirect_url'];
        $this->app_id = $config['app_id'];
        $this->app_secret = $config['app_secret'];

        if (array_key_exists("app_scope", $config)) {
            $this->app_scope = $config['app_scope'];
        }

        if (array_key_exists("accessToken", $config)) {
            $this->accessToken = $config['accessToken'];
        }

    }

    /**
     * Set config parameters needed for http basic auth logins
     * @param array $config
     * @since 1.1.0
     */
    public function setBasicAuth($config)
    {
        $this->sk_url = $config['sk_url'];
        $this->user = $config['user'];
        $this->password = $config['password'];

    }
    /**
    * Generate an Authorization URL
    * @param string|boolean $scope optional scope, if not set uses app_scope
    * @return string authorization url
    * @since 1.0.0
    */
    public function requestAuthorizationURL($scope = false)
    {
        return $this->sk_url . "/oauth/authorize?" .
            "client_id=". $this->app_id .
            "&scope=" . urlencode($scope ? $scope : $this->app_scope) .
            "&redirect_uri=" . urlencode($this->redirect_url);
    }

    /**
     * request accesstoken from Salesking API
     * @param $code
     * @return string AccessToken
     * @throws Exception
     * @since 2.0.0
     */
    public function requestAccessToken($code)
    {
        $response = $this->request($this->accessTokenUrl($code));

        if ($response['code'] == "200") {
            return $response['body'];
        }

        throw new Exception("REQUESTTOKEN_ERROR", "Could not fetch access_token", $response);
    }

    /**
     * Construct accesstoken URL
     * @param $code
     * @return string AccessToken
     * @since 2.0.0
     */
    public function accessTokenUrl($code)
    {
        return $this->sk_url. "/oauth/token?"
            . "client_id=". $this->app_id
            . "&redirect_uri=" . urlencode($this->redirect_url)
            . "&client_secret=" . $this->app_secret
            . "&code=" . $code;
    }
}
