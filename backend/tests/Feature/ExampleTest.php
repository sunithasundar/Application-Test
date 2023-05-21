<?php

namespace Tests\Feature;

use League\Csv\Reader;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;

class ExampleTest extends TestCase
{

    public $filePath;

    public function setUp(): void
    {
        parent::setUp();

        $this->filePath = Reader::createFromPath("app/csv/data.csv");
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTestRead()
    {
        $this->withoutExceptionHandling();
        $response = $this->get('/api/readProduct'); //get all rows 

        $response->assertStatus(200);
    }

    public function testBasicTestUpdate()
    {
        $data = ["data" => ["id"=>"3","name"=>"Gnagss","state"=>"LO","zip"=>"45744","amount"=>"634","qty"=>"5","item"=>"OII3255"],"id"=>"4"];

        $this->withoutExceptionHandling();
    
        $response = $this->json('POST', '/api/updateProduct', $data); //update operation on id=4
    
        $response->assertStatus(200);
    }

    public function testBasicTestUpdateNameError() //Name should contain atleast 5 characters
    {
        $data = ["data" => ["id"=>"3","name"=>"Tdd","state"=>"LO","zip"=>"45744","amount"=>"634","qty"=>"5","item"=>"PO2323"],"id"=>"2"];

        $this->withoutExceptionHandling();
    
        $response = $this->json('POST', '/api/updateProduct', $data);
    
        $response->assertStatus(500);
    }

    public function testBasicTestUpdateZipError() //should be between 5 to 6 digit
    {
        $data = ["data" => ["id"=>"3","name"=>"Tdd","state"=>"LO","zip"=>"4574","amount"=>"634","qty"=>"5","item"=>"PO2323"],"id"=>"2"];

        $this->withoutExceptionHandling();
    
        $response = $this->json('POST', '/api/updateProduct', $data);
    
        $response->assertStatus(500);
    }

    public function testBasicTestUpdateAmountError() //Amount has characters in it, should be only numbers
    {
        $data = ["data" => ["id"=>"3","name"=>"Tdd","state"=>"LO","zip"=>"4574","amount"=>"634Rs","qty"=>"5","item"=>"PO2323"],"id"=>"2"];

        $this->withoutExceptionHandling();
    
        $response = $this->json('POST', '/api/updateProduct', $data);
    
        $response->assertStatus(500);
    }

    public function testBasicTestUpdateWithoutId() //without Id for which update to be carried out is not provided
    {
        $data = ["data" => ["id"=>"3","name"=>"Gnagss","state"=>"LO","zip"=>"45744","amount"=>"634","qty"=>"5","item"=>"OII3255"]];

        $this->withoutExceptionHandling();
    
        $response = $this->json('POST', '/api/updateProduct', $data);
    
        $response->assertStatus(500);
    }

    public function testBasicTestCreateErrorWithoutItem() //item parameter not given
    {
        $data = ['data'=> ['id'=>'9','name'=>'peanuts','state'=>'MP','zip'=>'43522','amount'=>'2012','qty'=>'102']];

        $this->withoutExceptionHandling();
    
        $response = $this->json('POST', '/api/createProduct', $data);
    
        $response->assertStatus(500);
    }

    public function testBasicTestDelete()
    {
        $data = ["id" => "4"];

        $this->withoutExceptionHandling();
    
        $response = $this->json('POST', '/api/deleteProduct', $data); //delete operation 
    
        $response->assertStatus(200);
    }
}
