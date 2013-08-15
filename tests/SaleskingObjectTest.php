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
 * Test class for SaleskingObject.
 * Generated by PHPUnit on 2012-04-23 at 21:46:33.
 */
class SaleskingObjectTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var SaleskingObject
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $api = $this->getMock("Salesking",array(),array(),'',false);

        $api->expects($this->any())
            ->method("request")
            ->will(
                $this->returnCallback(array($this,'getMockRequest'))
        );

        $this->object = new SaleskingObject($api,array("obj_type"=>"contact"));
    }

    public function getMockRequest($url,$method="GET",$data=null)
    {
        if($url == "/api/contacts/1ef0a371-2d8a-4478-8ef" AND $method == "GET"){
            $response["code"] = "200";
            $body = new stdClass();
            $body->contact = array("organisation" => "salesking","first_name" => "john","id" => "1ef0a371-2d8a-4478-8ef", "type"=> "Client");
            $response["body"] = $body;
        }

        if($url == "/api/contacts/aed0eb9b-0276-4da7-ad8" AND $method == "GET"){
            $response["code"] = "404";
            $response["body"] = '';
        }

        if($url == "/api/contacts/1ef0a371-2d8a-4478-8ef" AND $method == "DELETE"){
            $response["code"] = "200";
            $response["body"] = true;
        }

        if($url == "/api/contacts/aed0eb9b-0276-4da7-ad8" AND $method == "DELETE"){
            $response["code"] = "404";
            $response["body"] = '';
        }

        if($url == "/api/contacts/1ef0a371-2d8a-4478-8ef" AND $method == "PUT"){
            $response["code"] = "200";
            $body = new stdClass();
            $body->contact = array("organisation" => "salesking","first_name" => "john","id" => "1ef0a371-2d8a-4478-8ef", "type"=> "Client");
            $response["body"] = $body;
        }

        if($url == "/api/contacts/aed0eb9b-0276-4da7-ad8" AND $method == "PUT"){
            $response["code"] = "404";
            $response["body"] = '';
        }

        if($url == "/api/contacts" AND $method == "POST" AND $data == '{"contact":{"id":"","organisation":"salesking","type":"Client"}}'){
            $response["code"] = "201";
            $body = new stdClass();
            $body->contact = array("organisation" => "salesking","first_name" => "john","id" => "1ef0a371-2d8a-4478-8ef", "type"=> "Client");
            $response["body"] = $body;
        }
        # mock testSaveApiError
        if($url == "/api/contacts" AND $method == "POST" AND $data == '{"contact":{"organisation":"testcompany","id":""}}'){
            $response["code"] = "405";
            $response["body"] = '';
        }

        return $response;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers SaleskingObject::__set
     */
    public function test__set()
    {
        //set a correct value on a existing property
        $this->object->organisation = "salesking";
        $this->assertEquals(
            "salesking",
            $this->object->getData("object")->organisation
        );

        //set a value on a non existing property
        $thrown = false;
        try {
            $this->object->notexisting = "string";
        }
        catch (SaleskingException $e) {
            if($e->getMessage() == "invalid property for this object type" && $e->getCode() == "SET_INVALIDPROPERTY")
            {
                $thrown = true;
            }
        }
        $this->assertTrue($thrown);

        //set a invalid value on an existing property
        $thrown = false;
        try {
            $this->object->cash_discount = "string";
        }
        catch (SaleskingException $e) {
            if($e->getMessage() == "invalid property value. Property: cash_discount - Value: string" && $e->getCode() == "SET_PROPERTYVALIDATION")
            {
                $thrown = true;
            }
        }
        $this->assertTrue($thrown);
    }

    /**
     * @covers SaleskingObject::validate
     */
    public function testValidate()
    {
        // make sure that not existing properties return false
        $this->assertFalse($this->object->validate("notexisting","string"));

        // test property type integer
        $this->assertFalse($this->object->validate("due_days","string"));
        $this->assertFalse($this->object->validate("due_days",array()));
        $this->assertFalse($this->object->validate("due_days",new stdClass()));
        $this->assertFalse($this->object->validate("due_days",12.2));
        $this->assertTrue($this->object->validate("due_days","12"));
        $this->assertTrue($this->object->validate("due_days",12));

        // test property type number
        $this->assertFalse($this->object->validate("cash_discount","string"));
        $this->assertFalse($this->object->validate("cash_discount",array()));
        $this->assertFalse($this->object->validate("cash_discount",new stdClass()));
        $this->assertTrue($this->object->validate("cash_discount",12.2));
        $this->assertTrue($this->object->validate("cash_discount","12"));
        $this->assertTrue($this->object->validate("cash_discount",12));

        // test property type array
        $this->assertFalse($this->object->validate("addresses","string"));
        $this->assertFalse($this->object->validate("addresses",new stdClass()));
        $this->assertFalse($this->object->validate("addresses",12.2));
        $this->assertFalse($this->object->validate("addresses",12));
        $this->assertTrue($this->object->validate("addresses",array()));

        // test property maxlength
        $this->assertTrue($this->object->validate("tax_number","aaaaaaaaaaaaaaaaaaaaaaaaaaaaaa"));
        $this->assertFalse($this->object->validate("tax_number","aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa"));

        // test property default values
        $this->assertFalse($this->object->validate("payment_method","string"));
        $this->assertFalse($this->object->validate("payment_method",array()));
        $this->assertTrue($this->object->validate("payment_method",""));
        $this->assertTrue($this->object->validate("payment_method","paypal"));


        // test property format date
        $this->assertFalse($this->object->validate("birthday","1999/01/01"));
        $this->assertFalse($this->object->validate("birthday","string"));
        $this->assertFalse($this->object->validate("birthday","123"));
        $this->assertFalse($this->object->validate("birthday","1999-01-32"));
        $this->assertFalse($this->object->validate("birthday","1999-13-01"));
        $this->assertTrue($this->object->validate("birthday","1999-01-01"));

        // test property format date-time YYYY-MM-DDThh:mm:ss+z
        //$this->assertFalse($this->object->validate("created_at","1999/01/01"));
        //$this->assertFalse($this->object->validate("created_at","string"));
        //$this->assertFalse($this->object->validate("created_at","123"));
        //$this->assertFalse($this->object->validate("created_at","1999-01-32"));
        //$this->assertFalse($this->object->validate("created_at","1999-13-01"));
        //$this->assertTrue($this->object->validate("created_at","1999-01-01"));

    }

    /**
     * @covers SaleskingObject::__get
     */
    public function test__get()
    {
        $this->object->organisation = "salesking";

        $this->assertEquals(
            "salesking",
            $this->object->organisation
        );

        $this->assertNull($this->object->notexisting);
    }

    /**
     * @covers SaleskingObject::__toString
     */
    public function test__toString()
    {
        $this->object->organisation = "salesking";

        $this->assertEquals(
            '{"organisation":"salesking"}',
            (string)$this->object
        );
    }

    /**
     * @covers SaleskingObject::bind
     */
    public function testBind()
    {
        // test bind method with an array
        $data = array(
            "organisation" => "salesking",
            "first_name" => "max",
            "notexisting" => "string",
            "lastname" => "john"
        );

        $this->object->bind($data,array("lastname" => "last_name"));

        $this->assertEquals(
            "salesking",
            $this->object->organisation
        );

        $this->assertEquals(
            "max",
            $this->object->first_name
        );

        $this->assertEquals(
            "john",
            $this->object->last_name
        );

        // test bind method with an object
        $data = new stdClass();
        $data->organisation = "salesking1";
        $data->first_name = "max1";
        $data->notexisting = "string1";
        $data->lastname = "john1";

        $this->object->bind($data,array("lastname" => "last_name"));

        $this->assertEquals(
            "salesking1",
            $this->object->organisation
        );

        $this->assertEquals(
            "max1",
            $this->object->first_name
        );

        $this->assertEquals(
            "john1",
            $this->object->last_name
        );

        //bind invalid data type
        $thrown = false;
        try {
            $this->object->bind("string");
        }
        catch (SaleskingException $e)
        {
            if($e->getCode() == "BIND_INVALIDTYPE" AND $e->getMessage() == "invalid data type - please provide an array or object"){
                $thrown = true;
            }
        }
        $this->assertTrue($thrown);
    }

    /**
     * @covers SaleskingObject::getType
     */
    public function testGetObjType()
    {
        $this->assertEquals(
            "contact",
            $this->object->getObjType()
        );
    }

    /**
     * @covers SaleskingObject::setType
     */
    public function testSetObjType()
    {
        $this->object->setObjType("invoice");

        $this->assertEquals(
            "invoice",
            $this->object->getObjType()
        );

    }

    /**
     * @covers SaleskingObject::getData
     */
    public function testGetData()
    {
        $this->object->organisation = "salesking";
        $this->object->last_name = "bush";

        $this->assertEquals(
            array("organisation" => "salesking","last_name" => "bush"),
            $this->object->getData()
        );

        $expected = new stdClass();
        $expected->organisation = "salesking";
        $expected->last_name = "bush";

        $this->assertEquals(
            $expected,
            $this->object->getData("object")
        );

        //get data in not existing format
        $thrown = false;
        try {
            $this->object->getData("notexisting");
        }
        catch (SaleskingException $e) {
            if($e->getCode() == "GETDATA_FORMAT" && $e->getMessage() == "Invalid format") {
                $thrown = true;
            }
        }
        $this->assertTrue($thrown);
    }

    /**
     * @covers SaleskingObject::save
     */
    public function testSaveUpdateSuccess()
    {
      // simulate an successfull update
      $this->object->id = "1ef0a371-2d8a-4478-8ef";
      $this->object->save();
      $this->assertEquals(array("organisation" => "salesking","first_name" => "john","id" => "1ef0a371-2d8a-4478-8ef", "type"=> "Client"),$this->object->getData());
    }
    /**
     * @covers SaleskingObject::save
     */
    public function testSaveError()
    {
      // simulate an error while updating
      $this->object->id = "aed0eb9b-0276-4da7-ad8";
      $thrown = false;
      try {
        $this->object->save();
      }
      catch (SaleskingException $e) {
        if($e->getCode() == "UPDATE_ERROR" && $e->getMessage() == "Update failed, an error occured") {
          $thrown = true;
        }
      }
      $this->assertTrue($thrown);
    }
    /**
     * @covers SaleskingObject::save
     */
    public function testSaveCreateSuccess()
    {
        // simulate a successfull post
        $this->object->id = "";
        $this->object->organisation = "salesking";
        $this->object->type = "Client";
        $this->object->save();
        $this->assertEquals(array("organisation" => "salesking","first_name" => "john","id" => "1ef0a371-2d8a-4478-8ef", "type"=> "Client"),$this->object->getData());
    }

    /**
     * @covers SaleskingObject::save
     */
    public function testSaveApiError()
    {
        // simulate an error on the api side
        $this->object->organisation = "testcompany";
        $this->object->id = "";
        $thrown = false;
        try {
            $this->object->save();
        }
        catch (SaleskingException $e) {
            if($e->getCode() == "CREATE_ERROR" && $e->getMessage() == "Create failed, an error occured.") {
                $thrown = true;
            }
        }
        $this->assertTrue($thrown);
    }

    /**
     * @covers SaleskingObject::load
     */
    public function testLoad()
    {
        //load existing object
        $this->object->id = "1ef0a371-2d8a-4478-8ef";
        $this->object->load();
        $this->assertEquals(array("organisation" => "salesking","first_name" => "john","id" => "1ef0a371-2d8a-4478-8ef","type"=> "Client"),$this->object->getData());

        //load not existing object
        $this->object->id = "aed0eb9b-0276-4da7-ad8";
        $thrown = false;
        try {
            $this->object->load();
        }
        catch (SaleskingException $e) {
            if($e->getCode() == "LOAD_ERROR" && $e->getMessage() == "Fetching failed, an error happend") {
                $thrown = true;
            }
        }
        $this->assertTrue($thrown);

        //load object by directly setting ID as parameter
        $this->object->load("1ef0a371-2d8a-4478-8ef");
        $this->assertEquals(array("organisation" => "salesking","first_name" => "john","id" => "1ef0a371-2d8a-4478-8ef", "type"=> "Client"),$this->object->getData());

        //load object with empty id
        $this->object->id = "";
        $thrown = false;
        try {
            $this->object->load();
        }
        catch (SaleskingException $e) {
            if($e->getCode() == "LOAD_IDNOTSET" && $e->getMessage() == "could not load object") {
                $thrown = true;
            }
        }
        $this->assertTrue($thrown);
    }

    /**
     * @covers SaleskingObject::delete
     */
    public function testDelete()
    {
        // assert we're trying to delete an existing object
        $this->object->id = "1ef0a371-2d8a-4478-8ef";
        $result = $this->object->delete();
        $this->assertTrue($result["body"]);

        // assert we're trying to delete a non exisiting object
        $this->object->id = "aed0eb9b-0276-4da7-ad8";
        $thrown = false;
        try {
            $this->object->delete();
        }
        catch (SaleskingException $e)
        {
            if($e->getCode() == "DELETE_ERROR" AND $e->getMessage() == "Deleting failed, an error happend"){
                $thrown = true;
            }
        }
        $this->assertTrue($thrown);

        // assert we're trying to delete an object without an ID
        $this->object->id = "";
        $thrown = false;
        try {
            $this->object->delete();
        }
        catch (SaleskingException $e)
        {
            if($e->getCode() == "DELETE_IDNOTSET" AND $e->getMessage() == "could not delete object"){
                $thrown = true;
            }
        }
        $this->assertTrue($thrown);
    }

    /**
     * @covers SaleskingObject::getEndpoint
     */
    public function testGetEndpoint()
    {
        $expected = new stdClass();
        $expected->href = "contacts/{id}";
        $expected->rel = "self";

        //get valid endpoint
        $this->assertEquals(
            $expected,
            $this->object->getEndpoint()
        );

        //get invalid endpoint
        $thrown = false;
        try {
            $this->object->getEndpoint("notexisting");
        }
        catch (SaleskingException $e)
        {
            if($e->getCode() == "ENDPOINT_NOTFOUND" AND $e->getMessage() == "invalid endpoint"){
                $thrown = true;
            }
        }
        $this->assertTrue($thrown);
    }

    /**
     * @covers SaleskingObject::__construct
     */
    public function test__construct()
    {
        $thrown = false;
        try
        {
            new SaleskingObject();
        }
        catch (PHPUnit_Framework_Error $e)
        {
            $thrown = true;
        }
        $this->assertTrue($thrown);

    }

}
?>
