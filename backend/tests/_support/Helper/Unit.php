<?php
namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class Unit extends \Codeception\Module
{
    const CSV_FILE_PATH = __DIR__ . "\..\..\api";
    const fileName = "\data.csv";

    /**
   * @desc check if the file exists
   */
  public function testFileExist(){
    $this->assertFileExists(self::CSV_FILE_PATH . self::fileName);

    $userName = "good";
    $this->assertEquals('good', $userName);
}

/**
* @desc check if the file is writable 
*/
}
