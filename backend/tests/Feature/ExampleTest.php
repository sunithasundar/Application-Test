<?php

namespace Tests\Feature;

use League\Csv\Reader;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    const CSV_FILE_PATH = __DIR__ . "\..\..\storage\csv";
    const fileName = "\data.csv";
    public $filePath;

    public function setUp(): void
    {
        parent::setUp();        
        $this->filePath = Reader::createFromPath(self::CSV_FILE_PATH . self::fileName);
    }

    /**
   * @desc check if the file exists
   */
    public function testFileExist(){
        $this->assertFileExists(self::CSV_FILE_PATH . self::fileName);
    }
    
    /**
   * @desc check if the file is writable 
   */
    public function testDirectoryIsWritable(){
        $this->assertDirectoryIsWritable(self::CSV_FILE_PATH, "directory path either doesn't exists or not writable");
    }

    /**
   * @desc check if the file is readable 
   */
    public function testDirectoryIsReadable(){
        $this->assertDirectoryIsReadable(self::CSV_FILE_PATH,"directory path either doesn't exists or not readable");
    }

    /**
   * @desc update data where id=2
   */
    public function testUpdateData(){
        $updateArray = [
            "id" => "2",
            "name" => "Jump Update",
            "state"=> "MP",
            "zip" => "53443",
            "amount" => "54.33",
            "qty" => "44",
            "item" => "JU22"
        ];

        $data = ["data" => $updateArray,"id"=>"2"];
        $response = $this->json('POST', '/api/updateProduct', $data);
        $response->assertSee($updateArray);
    }

    /**
   * @desc get all products 
   */
    public function testRead() //get all rows 
    {
        $this->withoutExceptionHandling();
        $response = $this->get('/api/readProduct'); 

        $response->assertStatus(200);
    }

    /**
   * @desc update operation where id=4
   */
    public function testUpdate() 
    {
        $data = ["data" => ["id"=>"3","name"=>"Gnagss","state"=>"LO","zip"=>"45744","amount"=>"634","qty"=>"5","item"=>"OII3255"],"id"=>"4"];

        $this->withoutExceptionHandling();
    
        $response = $this->json('POST', '/api/updateProduct', $data); 
    
        $response->assertStatus(200);
    }

    /**
   * @desc Name should contain atleast 5 characters, failure case 
   */
    public function testUpdateNameError()
    {
        $data = ["data" => ["id"=>"3","name"=>"Tdd","state"=>"LO","zip"=>"45744","amount"=>"634","qty"=>"5","item"=>"PO2323"],"id"=>"2"];

        $this->withoutExceptionHandling();
    
        $response = $this->json('POST', '/api/updateProduct', $data);
    
        $response->assertStatus(500);
    }

    /**
   * @desc zip should be between 5 to 6 digit, failure case 
   */
    public function testUpdateZipError()
    {
        $data = ["data" => ["id"=>"3","name"=>"Tdd","state"=>"LO","zip"=>"4574","amount"=>"634","qty"=>"5","item"=>"PO2323"],"id"=>"2"];

        $this->withoutExceptionHandling();
    
        $response = $this->json('POST', '/api/updateProduct', $data);
    
        $response->assertStatus(500);
    }

    /**
   * @desc Amount has characters in it, should be only numbers, failure case 
   */
    public function testUpdateAmountError()
    {
        $data = ["data" => ["id"=>"3","name"=>"Tdd","state"=>"LO","zip"=>"4574","amount"=>"634Rs","qty"=>"5","item"=>"PO2323"],"id"=>"2"];

        $this->withoutExceptionHandling();
    
        $response = $this->json('POST', '/api/updateProduct', $data);
    
        $response->assertStatus(500);
    }

    /**
   * @desc without Id for which update to be carried out is not provided, failure case 
   */
    public function testUpdateWithoutId() 
    {
        $data = ["data" => ["id"=>"3","name"=>"Gnagss","state"=>"LO","zip"=>"45744","amount"=>"634","qty"=>"5","item"=>"OII3255"]];

        $this->withoutExceptionHandling();
    
        $response = $this->json('POST', '/api/updateProduct', $data);
    
        $response->assertStatus(500);
    }

    /**
   * @desc item parameter not given for creating product, failure case 
   */
    public function testCreateErrorWithoutItem()
    {
        $data = ['data'=> ['id'=>'9','name'=>'peanuts','state'=>'MP','zip'=>'43522','amount'=>'2012','qty'=>'102']];

        $this->withoutExceptionHandling();
    
        $response = $this->json('POST', '/api/createProduct', $data);
    
        $response->assertStatus(500);
    }

    /**
   * @desc delete product where id is 4
   */
    public function testDelete()
    {
        $data = ["id" => "4"];

        $this->withoutExceptionHandling();
    
        $response = $this->json('POST', '/api/deleteProduct', $data); //delete operation 
    
        $response->assertStatus(200);
    }
}
