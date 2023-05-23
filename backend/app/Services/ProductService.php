<?php
namespace App\Services;

use App\Interfaces\ProductInterface;
use League\Csv\Reader;
use League\Csv\Writer;
use Illuminate\Support\Facades\Storage;
use Throwable;
use Exception;
use File;

class ProductService implements ProductInterface
{
    protected $reader;
    protected $writer;
    protected $header;

    public function __construct()
    {
        $this->filePath = "../app/csv/data.csv";  //to run the testcase change this to "app/csv/data.csv"

        try {
            // code that throws exception        
            if (!file_exists($this->filePath)) {
                throw new Exception("data.csv file was not found, will generate one for you");
            }
        } catch (\Exception $e) {
            File::copy("../app/csv/copy.csv", "../app/csv/data.csv");  //to run the testcase change this to "app/csv/data.csv"
            throw new Exception("data.csv file was not found, will generate one for you");
        }

        //used for reading 
        $this->reader = Reader::createFromPath($this->filePath,"r");

        //used for update, appending operation
        $this->writer = Writer::createFromPath($this->filePath, 'a+');
        $this->header = ['id', 'name', 'state', 'zip','amount', 'qty','item'];

        //i have not declared w+ here since it will clear the data so i am using it below
    }

    public function initialise(){ 
        $this->reader->setHeaderOffset(0); //initialise headers 
        $data = $this->reader->getRecords(); //get datas to process
        
        return $data;
    }

    public function createProduct(string $filename, array $data): array
    {
        $this->writer->insertOne($data); //insert a row of data
        return $data;
    }

    public function readProduct(string $filename): array
    {
        //read the data to process
        $records  = self::initialise();   

        //Actual number of columns and uploaded files column count are being matched
        if(count($this->header) == count($this->reader->getHeader())){
        
            $result = [];
            foreach ($records as $record) {
                $result[] = $record;
            }

            return $result; //read Product
        }
        else
        {
            return [];
        }
    }

    public function updateProduct(string $filename, array $data, int $rowIndex): void{
        
        //read the data to process
        $records  = self::initialise();   

        $result = [];
        foreach ($records as $record) {
            //if the id match update that record and push to array
            if ($record['id'] == $rowIndex) {  
                $data['id'] = $record['id'];
                $record = array_replace($record, $data);              
            }          
            array_push($result,$record);
        }

        self::writeMode($result);  //writing the result to the file after delete and update operation
    }

    public function deleteProduct(string $filename, $rowIndex): void
    {
        //read the data to process
        $records  = self::initialise(); 
        
        $result = [];
        foreach ($records as $record) {
            //if the id match remove that record and push to array
            if ($record['id'] != $rowIndex) { 
                array_push($result,$record);           
            }            
        }

        self::writeMode($result); //writing the result to the file after delete and update operation
    }

    public function deleteMultipleProduct(string $filename, $rowIndex): void
    {
        //read the data to process
        $records  = self::initialise();
        
        $result = [];
        foreach ($records as $record) { 
            //if the id match remove that record and push to array
            if (in_array($record['id'],$rowIndex, TRUE)){

            }      
            else
            {
                array_push($result,$record);   
            }      
        }

        self::writeMode($result); //writing the result to the file after delete and update operation
    }

    //writing the result to the file after delete and update operation
    public function writeMode($data)
    { 
        $create = Writer::createFromPath($this->filePath, 'w+');
        $create->insertOne($this->header);        
        $create->insertAll($data);
    }
}

?>