<?php
/**
 * @version     1.0.0
 * @package     SalesKing PHP SDK Tests
 * @license     MIT License; see LICENSE
 * @copyright   Copyright (C) 2012 David Jardin
 * @link        http://www.salesking.eu
 */

require_once (dirname(__FILE__).'/../src/salesking.php');

/**
 * Test class for Salesking.
 * Generated by PHPUnit on 2012-04-23 at 17:48:00.
 */
class SaleskingTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Salesking
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $config = array(
            "accessToken" => "accessToken",
            "sk_url" => "sk_url",
            "app_url" => "app_url",
            "app_id" => "app_id",
            "app_secret" => "app_secret"
        );

        $this->object = new Salesking($config);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Salesking::__construct
     */
    public function test__construct()
    {
        // test object initialization without parameters
        $thrown = false;
        try {
            new Salesking();
        }
        catch (SaleskingException $e)
        {
            if($e->getCode() == "INITLIBRARY_MISSINGCONF" AND $e->getMessage() == "Could not initialize library - missing configuration parameters"){
                $thrown = true;
            }
        }
        $this->assertTrue($thrown);

        // test object initialization with parameters
        $this->assertInstanceOf("Salesking",new Salesking(array(
            "accessToken" => "accessToken",
            "sk_url" => "sk_url",
            "app_url" => "app_url",
            "app_id" => "app_id",
            "app_secret" => "app_secret"
        )));
    }

    /**
     * @covers Salesking::getAppID
     */
    public function testGetAppID()
    {
        $this->object->app_id = "aadd3f77bs935b44";

        $this->assertEquals(
            "aadd3f77bs935b44",
            $this->object->getAppID()
        );
    }

    /**
     * @covers Salesking::setAppID
     */
    public function testSetAppID()
    {
        $this->object->setAppID("aadd3f77bs935b44");

        $this->assertEquals(
            "aadd3f77bs935b44",
            $this->object->app_id
        );
    }

    /**
     * @covers Salesking::getAppSecret
     */
    public function testGetAppSecret()
    {
        $this->object->app_secret = "43c3e1cf85eebc28451f34739833591f";

        $this->assertEquals(
            "43c3e1cf85eebc28451f34739833591f",
            $this->object->getAppSecret()
        );
    }

    /**
     * @covers Salesking::setAppSecret
     */
    public function testSetAppSecret()
    {
        $this->object->setAppSecret("43c3e1cf85eebc28451f34739833591f");

        $this->assertEquals(
            "43c3e1cf85eebc28451f34739833591f",
            $this->object->app_secret
        );
    }

    /**
     * @covers Salesking::getAppUrl
     */
    public function testGetAppUrl()
    {
        $this->object->app_url = "http://www.example.com";

        $this->assertEquals(
            "http://www.example.com",
            $this->object->getAppUrl()
        );
    }

    /**
     * @covers Salesking::setAppUrl
     */
    public function testSetAppUrl()
    {
        $this->object->setAppUrl("http://www.example.com");

        $this->assertEquals(
            "http://www.example.com",
            $this->object->app_url
        );
    }

    /**
     * @covers Salesking::getSkUrl
     */
    public function testGetSkUrl()
    {
        $this->object->app_url = "https://test.dev.salesking.eu";

        $this->assertEquals(
            "https://test.dev.salesking.eu",
            $this->object->getAppUrl()
        );
    }

    /**
     * @covers Salesking::setSkUrl
     */
    public function testSetSkUrl()
    {
        $this->object->setSkUrl("https://test.dev.salesking.eu");

        $this->assertEquals(
            "https://test.dev.salesking.eu",
            $this->object->sk_url
        );
    }

    /**
     * @covers Salesking::getAccessToken
     */
    public function testGetAccessToken()
    {
        $this->object->accessToken = "14e948d94dea01bdf87fc6146ad9b050";

        $this->assertEquals(
            "14e948d94dea01bdf87fc6146ad9b050",
            $this->object->getAccessToken()
        );
    }

    /**
     * @covers Salesking::setAccessToken
     */
    public function testSetAccessToken()
    {
        $this->object->setAccessToken("14e948d94dea01bdf87fc6146ad9b050");

        $this->assertEquals(
            "14e948d94dea01bdf87fc6146ad9b050",
            $this->object->accessToken
        );
    }

    /**
     * @covers Salesking::getObject
     */
    public function testGetObject()
    {
        $this->assertInstanceOf(
            "SaleskingObject",
            $this->object->getObject("client")
        );

        //get notexisting object
        $thrown = false;
        try {
            $this->object->getObject("notexisting");
        }
        catch (SaleskingException $e)
        {
            if($e->getCode() == "SCHEMA_NOTFOUND" AND $e->getMessage() == "Could not find schema file."){
                $thrown = true;
            }
        }
        $this->assertTrue($thrown);
    }

    /**
     * @covers Salesking::request
     * @todo Implement testRequest().
     */
    public function testRequest()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Salesking::requestAuthorizationURL
     */
    public function testRequestAuthorizationURL()
    {
        $this->assertEquals(
            $this->object->sk_url . "/oauth/authorize?"
                                . "client_id=". $this->object->app_id
                                . "&scope=" . urlencode("scope")
                                . "&redirect_uri=" . urlencode($this->object->app_url),
            $this->object->requestAuthorizationURL("scope")
        );
    }

    /**
     * @covers Salesking::accessTokenUrl
     */
    public function testAccessTokenUrl()
    {
        $some_code = "Some_long_code_returned_from_authorize";
        $this->assertEquals(
            $this->object->sk_url . "/oauth/token?"
                                  . "client_id=". $this->object->app_id
                                  . "&redirect_uri=" . urlencode($this->object->app_url)
                                  . "&client_secret=" . $this->object->app_secret
                                  . "&code=" . $some_code,
            $this->object->accessTokenUrl($some_code)
        );
    }



    /**
     * @covers Salesking::requestAccessToken
     * @todo Implement testRequestAccessToken().
     */
    public function testRequestAccessToken()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
}
?>
