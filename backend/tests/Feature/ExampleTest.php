<?php

namespace Tests\Feature;

use League\Csv\Reader;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;

class ExampleTest extends TestCase
{

    const CSV_FILE_PATH = __DIR__ . "\..\..\app\csv";
    const fileName = "\data.csv";
    public $filePath;

    public function setUp(): void
    {
        parent::setUp();        
        $this->filePath = Reader::createFromPath(self::CSV_FILE_PATH . self::fileName);
    }

    public function testFileExist(){
        $this->assertFileExists(self::CSV_FILE_PATH . self::fileName);
    }
    
    public function testDirectoryIsWritable(){
        $this->assertDirectoryIsWritable(self::CSV_FILE_PATH, "directory path either doesn't exists or not writable");
    }

    public function testDirectoryIsReadable(){
        $this->assertDirectoryIsReadable(self::CSV_FILE_PATH,"directory path either doesn't exists or not readable");
    }

    public function testUpdateData(){ //update where id=2
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
     * A basic test example.
     *
     * @return void
     */
    public function testRead() //get all rows 
    {
        $this->withoutExceptionHandling();
        $response = $this->get('/api/readProduct'); 

        $response->assertStatus(200);
    }

    public function testUpdate() //update operation on id=4
    {
        $data = ["data" => ["id"=>"3","name"=>"Gnagss","state"=>"LO","zip"=>"45744","amount"=>"634","qty"=>"5","item"=>"OII3255"],"id"=>"4"];

        $this->withoutExceptionHandling();
    
        $response = $this->json('POST', '/api/updateProduct', $data); 
    
        $response->assertStatus(200);
    }

    public function testUpdateNameError() //Name should contain atleast 5 characters
    {
        $data = ["data" => ["id"=>"3","name"=>"Tdd","state"=>"LO","zip"=>"45744","amount"=>"634","qty"=>"5","item"=>"PO2323"],"id"=>"2"];

        $this->withoutExceptionHandling();
    
        $response = $this->json('POST', '/api/updateProduct', $data);
    
        $response->assertStatus(500);
    }

    public function testUpdateZipError() //should be between 5 to 6 digit
    {
        $data = ["data" => ["id"=>"3","name"=>"Tdd","state"=>"LO","zip"=>"4574","amount"=>"634","qty"=>"5","item"=>"PO2323"],"id"=>"2"];

        $this->withoutExceptionHandling();
    
        $response = $this->json('POST', '/api/updateProduct', $data);
    
        $response->assertStatus(500);
    }

    public function testUpdateAmountError() //Amount has characters in it, should be only numbers
    {
        $data = ["data" => ["id"=>"3","name"=>"Tdd","state"=>"LO","zip"=>"4574","amount"=>"634Rs","qty"=>"5","item"=>"PO2323"],"id"=>"2"];

        $this->withoutExceptionHandling();
    
        $response = $this->json('POST', '/api/updateProduct', $data);
    
        $response->assertStatus(500);
    }

    public function testUpdateWithoutId() //without Id for which update to be carried out is not provided
    {
        $data = ["data" => ["id"=>"3","name"=>"Gnagss","state"=>"LO","zip"=>"45744","amount"=>"634","qty"=>"5","item"=>"OII3255"]];

        $this->withoutExceptionHandling();
    
        $response = $this->json('POST', '/api/updateProduct', $data);
    
        $response->assertStatus(500);
    }

    public function testCreateErrorWithoutItem() //item parameter not given
    {
        $data = ['data'=> ['id'=>'9','name'=>'peanuts','state'=>'MP','zip'=>'43522','amount'=>'2012','qty'=>'102']];

        $this->withoutExceptionHandling();
    
        $response = $this->json('POST', '/api/createProduct', $data);
    
        $response->assertStatus(500);
    }

    public function testDelete()
    {
        $data = ["id" => "4"];

        $this->withoutExceptionHandling();
    
        $response = $this->json('POST', '/api/deleteProduct', $data); //delete operation 
    
        $response->assertStatus(200);
    }
}
