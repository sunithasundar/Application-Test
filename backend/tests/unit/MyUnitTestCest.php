<?php
require __DIR__ . "\..\..\api\controllers\CsvController.php";
use Codeception\Test\Unit;

class MyUnitTestCest
{
    // use Asserts;

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

//     public function testMyMethod(UnitTester $I)
//     {
//         // Test code goes here
//         $I->assertTrue(true);
//     }

//     /**
//      * @desc check if the file exists
//      */
//     public function testFileExist(UnitTester $I){
//         $I->assertFileExists(self::CSV_FILE_PATH . self::fileName); 
//     }

//     /**
//     * @desc check if the file is writable 
//     */
//     public function testDirectoryIsWritable(UnitTester $I){ 
//         $I->assertDirectoryIsWritable(self::CSV_FILE_PATH, "directory path either doesn't exists or not writable");
//     }

//     /**
//    * @desc check if the file is readable 
//    */
//     public function testDirectoryIsReadable(UnitTester $I){
//         $I->assertDirectoryIsReadable(self::CSV_FILE_PATH,"directory path either doesn't exists or not readable");
//     }

//     public function createProduct(UnitTester $I)
//     {
        
//         $values = [
//             "0" => "2", "1" => "peanut", "2" => "pl", "3" => "64466", "4" => "233", "5" => "432", "6" => "PEA123"
//         ];

//         $this->controller->createData($values);

//         // Read data from CSV file
//         $data = [];
        
//         $data = $this->controller->getAllData(); 

//         $getData = json_decode($data, true); //Decode JSON into an associative array
//         $lastData = end($getData);

//         $name = $lastData['name'];
//         $state = $lastData['state'];
        
//         $I->assertEquals('peanut', $name);
//         $I->assertEquals('pl', $state);
//     }

//     public function readProduct(UnitTester $I)
//     {
//         //Read data from CSV file
//         $data = [];
        
//         $data = $this->controller->getAllData(); 

//         $getData = json_decode($data, true); //Decode JSON into an associative array
//         $lastData = end($getData);

//         $name = $lastData['name'];
//         $state = $lastData['state'];
        
//         // Assertions
//         $I->assertEquals('peanut', $name);
//         $I->assertEquals('pl', $state);
//     }

    public function deleteUser(UnitTester $I)
    {
        $id = [5];
        $this->controller->deleteData($id);

        // Assertions
        $I->assertTrue($this->controller->dataExists($id));
    }    
}

