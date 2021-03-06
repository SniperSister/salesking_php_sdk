<?php
namespace Salesking\Tests\PHPSDK;

/**
 * @version     2.0.0
 * @package     SalesKing PHP SDK Tests
 * @license     MIT License; see LICENSE
 * @copyright   Copyright (C) 2012 David Jardin
 * @link        http://www.salesking.eu
 */
use Salesking\PHPSDK\API;

/**
 * Test class for API.
 * Generated by PHPUnit on 2012-04-23 at 17:48:00.
 */
class APITest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var API
     */
    protected $object;

    /**
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $config = array(
            "accessToken" => "accessToken",
            "sk_url" => "sk_url",
            "redirect_url" => "redirect_url",
            "app_id" => "app_id",
            "app_secret" => "app_secret"
        );

        $this->object = new API($config);
    }

    /**
     * @covers Salesking\PHPSDK\API::__construct
     */
    public function testInstantiationWithCorrectParameters()
    {
        // test object initialization with parameters
        $this->assertInstanceOf("Salesking\\PHPSDK\\API", new API(array(
            "accessToken" => "accessToken",
            "sk_url" => "sk_url",
            "redirect_url" => "redirect_url",
            "app_id" => "app_id",
            "app_secret" => "app_secret"
        )));
    }

    /**
     * @covers Salesking\PHPSDK\API::__construct
     *
     * @expectedException \Salesking\PHPSDK\Exception
     * @expectedExceptionMessage Could not initialize library - missing authentication params
     * @expectedExceptionCode INITLIBRARY_MISSINGCONF
     */
    public function testInstantiationWithIncorrectParametersThrowsException()
    {
        $conf = array("auth params" => "missing");
        new API($conf);
    }

    /**
     * @covers Salesking\PHPSDK\API::__construct
     */
    public function testInstantiationWithBasicAuth()
    {
        $conf = array(
            "sk_url" => "https://test.dev.salesking.eu",
            "user" => "demo@salesking.eu",
            "password" => "demo");

        $sk = new API($conf);

        $this->assertEquals(true, $sk->use_basic_auth);
        $this->assertEquals("demo@salesking.eu", $sk->user);
    }

    /**
     * @covers Salesking\PHPSDK\API::getAppID
     */
    public function testAppIdGetterReturnsCorrectId()
    {
        $this->object->app_id = "aadd3f77bs935b44";

        $this->assertEquals(
            "aadd3f77bs935b44",
            $this->object->getAppID()
        );
    }

    /**
     * @covers Salesking\PHPSDK\API::setAppID
     */
    public function testAppIdSetterSetsCorrectId()
    {
        $this->object->setAppID("aadd3f77bs935b44");

        $this->assertEquals(
            "aadd3f77bs935b44",
            $this->object->app_id
        );
    }

    /**
     * @covers Salesking\PHPSDK\API::getAppSecret
     */
    public function testAppSecretGetterReturnsCorrectSecret()
    {
        $this->object->app_secret = "43c3e1cf85eebc28451f34739833591f";

        $this->assertEquals(
            "43c3e1cf85eebc28451f34739833591f",
            $this->object->getAppSecret()
        );
    }

    /**
     * @covers Salesking\PHPSDK\API::setAppSecret
     */
    public function testAppSecretSetterSetsCorrectSecret()
    {
        $this->object->setAppSecret("43c3e1cf85eebc28451f34739833591f");

        $this->assertEquals(
            "43c3e1cf85eebc28451f34739833591f",
            $this->object->app_secret
        );
    }

    /**
     * @covers Salesking\PHPSDK\API::getRedirectUrl
     */
    public function testRedirectUrlGetterReturnsCorrectUrl()
    {
        $this->object->redirect_url = "http://www.example.com";

        $this->assertEquals(
            "http://www.example.com",
            $this->object->getRedirectUrl()
        );
    }

    /**
     * @covers Salesking\PHPSDK\API::setRedirectUrl
     */
    public function testRedirectUrlSetterSetsCorrectUrl()
    {
        $this->object->setRedirectUrl("http://www.example.com");

        $this->assertEquals(
            "http://www.example.com",
            $this->object->redirect_url
        );
    }

    /**
     * @covers Salesking\PHPSDK\API::getSkUrl
     */
    public function testApiUrlGetterReturnsCorrectUrl()
    {
        $this->object->sk_url = "https://test.dev.salesking.eu";

        $this->assertEquals(
            "https://test.dev.salesking.eu",
            $this->object->getSkUrl()
        );
    }

    /**
     * @covers Salesking\PHPSDK\API::setSkUrl
     */
    public function testApiUrlSetterSetsCorrectUrl()
    {
        $this->object->setSkUrl("https://test.dev.salesking.eu");

        $this->assertEquals(
            "https://test.dev.salesking.eu",
            $this->object->sk_url
        );
    }

    /**
     * @covers Salesking\PHPSDK\API::setBasicAuth
     */
    public function testBasicAuthCredentialsSetterSetsCorrectValues()
    {
        $conf = array(
            "sk_url" => "https://test.dev.salesking.eu",
            "user" => "demo@salesking.eu",
            "password" => "demo");

        $this->object->setBasicAuth($conf);

        $this->assertEquals(
            "https://test.dev.salesking.eu",
            $this->object->sk_url
        );
        $this->assertEquals(
            "demo@salesking.eu",
            $this->object->user
        );
        $this->assertEquals(
            "demo",
            $this->object->password
        );
    }

    /**
     * @covers Salesking\PHPSDK\API::getAccessToken
     */
    public function testBasicAuthCredentialsGetterGetsCorrectValues()
    {
        $this->object->accessToken = "14e948d94dea01bdf87fc6146ad9b050";

        $this->assertEquals(
            "14e948d94dea01bdf87fc6146ad9b050",
            $this->object->getAccessToken()
        );
    }

    /**
     * @covers Salesking\PHPSDK\API::setAccessToken
     */
    public function testSetAccessTokenSetsCorrectToken()
    {
        $this->object->setAccessToken("14e948d94dea01bdf87fc6146ad9b050");

        $this->assertEquals(
            "14e948d94dea01bdf87fc6146ad9b050",
            $this->object->accessToken
        );
    }

    /**
     * @covers Salesking\PHPSDK\API::getObject
     */
    public function testValidObjectCanBeRetrieved()
    {
        $this->assertInstanceOf(
            "Salesking\\PHPSDK\\Object",
            $this->object->getObject("client")
        );
    }

    /**
     * @covers Salesking\PHPSDK\API::getObject
     *
     * @expectedException \Salesking\PHPSDK\Exception
     * @expectedExceptionCode SCHEMA_NOTFOUND
     * @expectedExceptionMessage Could not find schema file.
     */
    public function testExceptionIsThrownOnInvalidObjectName()
    {
        $this->object->getObject("notexisting");
    }

    /**
     * @covers Salesking\PHPSDK\API::requestAuthorizationURL
     */
    public function testCorrectAuthorizationUrlIsGenerated()
    {
        $this->assertEquals(
            $this->object->sk_url . "/oauth/authorize?"
            . "client_id=". $this->object->app_id
            . "&scope=" . urlencode("scope")
            . "&redirect_uri=" . urlencode($this->object->redirect_url),
            $this->object->requestAuthorizationURL("scope")
        );
    }

    /**
     * @covers Salesking\PHPSDK\API::accessTokenUrl
     */
    public function testCorrectAccessTokenUrlIsGenerated()
    {
        $some_code = "Some_long_code_returned_from_authorize";
        $this->assertEquals(
            $this->object->sk_url . "/oauth/token?"
            . "client_id=". $this->object->app_id
            . "&redirect_uri=" . urlencode($this->object->redirect_url)
            . "&client_secret=" . $this->object->app_secret
            . "&code=" . $some_code,
            $this->object->accessTokenUrl($some_code)
        );
    }

    /**
     * @covers Salesking\PHPSDK\API::getCollection
     */
    public function testValidCollectionCanBeRetrieved()
    {
        $this->assertInstanceOf(
            "Salesking\\PHPSDK\\Collection",
            $this->object->getCollection("client")
        );
    }

    /**
     * @covers Salesking\PHPSDK\API::getCollection
     *
     * @expectedException \Salesking\PHPSDK\Exception
     * @expectedExceptionCode SCHEMA_NOTFOUND
     * @expectedExceptionMessage Could not find schema file.
     */
    public function testExceptionIsThrownOnInvalidCollectionName()
    {
        $this->object->getCollection("notexisting");
    }
}
