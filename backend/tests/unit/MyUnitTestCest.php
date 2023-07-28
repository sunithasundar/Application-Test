<?php
require __DIR__ . "\..\..\api\controllers\CsvController.php";
use Codeception\Test\Unit;

class MyUnitTestCest
{
    const CSV_FILE_PATH = "..\backend";
    const fileName = "\data.csv";

    public function _before(UnitTester $I)
    { 
        // Executed before each test method 
        $this->controller = new CsvController();
    }

    public function _after(UnitTester $I)
    {
        // Executed after each test method
    }

    public function testMyMethod(UnitTester $I)
    {
        $I->assertTrue(true);
    }

    /**
     * @desc check if the file exists
     */
    public function testFileExist(UnitTester $I){
        $I->assertFileExists(self::CSV_FILE_PATH . self::fileName); 
    }

    /**
    * @desc check if the file is writable 
    */
    public function testDirectoryIsWritable(UnitTester $I){ 
        $I->assertDirectoryIsWritable(self::CSV_FILE_PATH, "directory path either doesn't exists or not writable");
    }

    /**
    * @desc check if the file is readable 
    */
    public function testDirectoryIsReadable(UnitTester $I){
        $I->assertDirectoryIsReadable(self::CSV_FILE_PATH,"directory path either doesn't exists or not readable");
    }

    /**
    * @desc check if the directory exists
    */
    public function testDirectoryExists(UnitTester $I){
        $I->assertDirectoryExists(self::CSV_FILE_PATH,'The directory should exist');
    }
    
    /**
    * @desc to create product
    */
    public function createProduct(UnitTester $I)
    {
        $values = [            
            "id" => "5", "name" => "peanut", "state" => "pl", "zip" => "64466", "amount" => "233", "qty" => "432", "item" => "PEA123"
        ];

        $this->controller->createData($values);

        $data = [];        
        $data = $this->controller->getAllData(); 

        $lastData = end($data); 

        $name = $lastData['name'];
        $state = $lastData['state'];
        
        $I->assertEquals('peanut', $name);
        $I->assertEquals('pl', $state);
    }

    /**
    * @desc to create product name invalid
    */
    public function createProductNameInvalid(UnitTester $I)
    {
        $values = [
            "id" => "5", "name" => "but", "state" => "bu", "zip" => "64642", "amount" => "754", "qty" => "543", "item" => "But123"
        ];

        $this->controller->createData($values);

        $data = [];        
        $data = $this->controller->getAllData(); 

        $lastData = end($data); 

        $name = $lastData['name'];
        $state = $lastData['state'];
        
        $I->assertFalse('butter'==$name, "doesnt match");
        $I->assertFalse('bu'==$state, "doesnt match");
    }


    /**
    * @desc to create product state invalid
    */    
    public function createProductStateInvalid(UnitTester $I)
    {
        $values = [
            "id" => "5", "name" => "butter", "state" => "bu7", "zip" => "645", "amount" => "754", "qty" => "543", "item" => "But123"
        ];

        $this->controller->createData($values);

        $data = [];        
        $data = $this->controller->getAllData();

        $lastData = end($data); 

        $name = $lastData['name'];
        $state = $lastData['state'];
    
        $I->assertFalse('butter'==$name, "doesnt match");
        $I->assertFalse('bu'==$state, "doesnt match");
    }
    
    /**
    * @desc to create product zip invalid
    */   
    public function createProductZipInvalid(UnitTester $I)
    {
        $values = [
            "id" => "5", "name" => "butter", "state" => "bu", "zip" => "645", "amount" => "754", "qty" => "543", "item" => "But123"
        ];

        $this->controller->createData($values);

        $data = [];        
        $data = $this->controller->getAllData();

        $lastData = end($data); 

        $name = $lastData['name'];
        $state = $lastData['state'];
    
        $I->assertFalse('butter'==$name, "doesnt match");
        $I->assertFalse('bu'==$state, "doesnt match");
    }

    /**
    * @desc to create product duplicate item
    */   
    public function createProductDuplicateItem(UnitTester $I)
    {
        $values = [
            "id" => "5", "name" => "butter", "state" => "bu", "zip" => "64642", "amount" => "754", "qty" => "543", "item" => "df3"
        ];

        $data = [];        
        $data = $this->controller->getAllData();

        $lastData = end($data); 

        $name = $lastData['name'];
        $state = $lastData['state'];
    
        $I->assertFalse('butter'==$name, "doesnt match");
        $I->assertFalse('bu'==$state, "doesnt match");
    }  

    /**
    * @desc to read product
    */   
    public function readProduct(UnitTester $I)
    {
        $data = [];
        
        $data = $this->controller->getAllData(); 

        $lastData = end($data);

        $name = $lastData['name'];
        $state = $lastData['state'];
        
        $I->assertEquals('peanut', $name);
        $I->assertEquals('pl', $state);
    }

    /**
    * @desc to update product
    */  
    public function updateProduct(UnitTester $I)
    {
        $data = [
            "id" => "2", "name" => "cashew", "state" => "ca", "zip" => "53535", "amount" => "233", "qty" => "432", "item" => "CEA123"
        ];

        $id = ["id" => "2"]; 
        $passId = 2; 
        $this->controller->updateData($passId,$data);

        $rows = [];
        $rows = $this->controller->getAllData();  

        $result = [];
        foreach ($rows as $record) {
            if (in_array($record['id'],$id, TRUE)){
                
                $name = $record['name'];
                $state = $record['state'];
                
                $I->assertEquals('cashew', $name);
                $I->assertEquals('ca', $state);
            }      
            else
            {
                return true;
            }      
        }        
    }

    /**
    * @desc to update product with namve invalid
    */ 
    public function updateProductNameInvalid(UnitTester $I)
    {
        $data = [
            "id" => "2", "name" => "dia", "state" => "da", "zip" => "43453", "amount" => "644", "qty" => "234", "item" => "Dia123"
        ];

        $id = ["id" => "2"]; 
        $passId = 2; 
        $this->controller->updateData($passId,$data);

        $rows = [];
        $rows = $this->controller->getAllData();  

        $result = [];
        foreach ($rows as $record) {
            if (in_array($record['id'],$id, TRUE)){
                
                $name = $record['name'];
                $state = $record['state'];
                
                $I->assertFalse('diary'==$name, "doesnt match");
                $I->assertFalse('da'==$state, "doesnt match");
            }      
            else
            {
                return true;
            }      
        }        
    }

    /**
    * @desc to update product state invalid
    */ 
    public function updateProductStateInvalid(UnitTester $I)
    {
        $data = [
            "id" => "2", "name" => "diary", "state" => "da7", "zip" => "43453", "amount" => "644", "qty" => "234", "item" => "Dia123"
        ];

        $id = ["id" => "2"]; 
        $passId = 2; 
        $this->controller->updateData($passId,$data);

        $rows = [];
        $rows = $this->controller->getAllData();  

        $result = [];
        foreach ($rows as $record) {
            if (in_array($record['id'],$id, TRUE)){
                
                $name = $record['name'];
                $state = $record['state'];
                
                $I->assertFalse('diary'==$name, "doesnt match");
                $I->assertFalse('da'==$state, "doesnt match");
            }      
            else
            {
                return true;
            }      
        }        
    }
    
    /**
    * @desc to update product with zip invalid
    */ 
    public function updateProductZipInvalid(UnitTester $I)
    {
        $data = [
            "id" => "2", "name" => "diary", "state" => "da", "zip" => "434", "amount" => "644", "qty" => "234", "item" => "Dia123"
        ];

        $id = ["id" => "2"]; 
        $passId = 2; 
        $this->controller->updateData($passId,$data);

        $rows = [];
        $rows = $this->controller->getAllData();  

        $result = [];
        foreach ($rows as $record) {
            if (in_array($record['id'],$id, TRUE)){
                
                $name = $record['name'];
                $state = $record['state'];
                
                $I->assertFalse('diary'==$name, "doesnt match");
                $I->assertFalse('da'==$state, "doesnt match");
            }      
            else
            {
                return true;
            }      
        }        
    }

    /**
    * @desc to update product duplicate item
    */ 
    public function updateProductDuplicateItem(UnitTester $I)
    {
        $data = [
            "id" => "2", "name" => "diary", "state" => "da", "zip" => "43453", "amount" => "644", "qty" => "234", "item" => "Dia123"
        ];

        $id = ["id" => "2"]; 
        $passId = 2; 
        $this->controller->updateData($passId,$data);

        $rows = [];
        $rows = $this->controller->getAllData();

        $result = [];
        foreach ($rows as $record) {
            if (in_array($record['id'],$id, TRUE)){
                
                $name = $record['name'];
                $state = $record['state'];
                
                $I->assertFalse('diary'==$name, "doesnt match");
                $I->assertFalse('da'==$state, "doesnt match");
            }      
            else
            {
                return true;
            }      
        }        
    }    

    /**
    * @desc to delete item
    */ 
    public function deleteItem(UnitTester $I)
    {
        $id = [3];
        $this->controller->deleteData($id);

        $I->assertTrue($this->controller->dataExists($id));
    }    

    /**
    * @desc to delete item which is not valid
    */ 
    public function deleteItemNotValid(UnitTester $I)
    {
        $id = [6];
        $this->controller->deleteData($id);

        $I->assertFalse(!$this->controller->dataExists($id));
    }    
}