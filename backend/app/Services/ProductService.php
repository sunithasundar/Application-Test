<?php
namespace App\Services;

use App\Interfaces\ProductInterface;
use League\Csv\Reader;
use League\Csv\Writer;
use Illuminate\Support\Facades\Storage;
use Throwable;
use Exception;

class ProductService implements ProductInterface
{
    protected $reader;
    protected $writer;
    protected $header;

    public function __construct()
    {
        //array of header names
        $this->header = ['id', 'name', 'state', 'zip','amount', 'qty','item'];

        $fileName = config('common.fileName'); //file name from common file 
        
        $this->filePath = Storage::disk('csv')->path($fileName);
        try {
            // code that throws exception        
            if (!file_exists($this->filePath)) {
                throw new Exception("data.csv file was not found, will generate one for you");
            }
        } catch (\Exception $e) { 
            self::writeHeaders();
            throw new Exception("data.csv file was not found, will generate one for you");
        }
        
        //used for reading 
        $this->reader = Reader::createFromPath($this->filePath,"r");

        //used for update, appending operation
        $this->writer = Writer::createFromPath($this->filePath, 'a+');

        //i have not declared w+ here since it will clear the data so i am using it below
    }
    
    /** 
    * @desc initialises with headers and get data
    * @param 
    * @return data
    */
    public function initialise(){ 
        $this->reader->setHeaderOffset(0); //initialise headers 
        $data = $this->reader->getRecords(); //get datas to process
        
        return $data;
    }

    /** 
    * @desc create product by inserting one row
    * @param filename and data
    * @return data
    */
    public function createProduct(string $filename, array $data): array
    {
        $this->writer->insertOne($data); //insert a row of data
        return $data;
    }

    /** 
    * @desc read product displays the list of datas
    * @param filename
    * @return data
    */
    public function readProduct(string $filename): array
    {  
        //read the data to process
        $records  = self::initialise();   
        
        //Actual number of columns and uploaded files column count are being matched
        $getHeaders = $this->reader->getHeader();

        //checks if empty headers are present 
        $filteredHeaders = array_filter($getHeaders, function($getHeaders) {
            return !empty($getHeaders);
        });
        
        //Actual number of columns and uploaded files column count are being matched
        if(count($this->header) == count($filteredHeaders)){
            
            $result = [];
            foreach ($records as $record) {
                $result[] = $record;
            }

            return $result; //read Product
        }
        else
        { 
            self::writeHeaders();
            throw new Exception("Column headers not match, have generated sample file"); //if columns dont match with the array being passed, a sample file is being generated with headers from header array      
            return [];
        }
    }

    /** 
    * @desc update product by passing the id for which update needs to be applied
    * @param filename, data and rowIndex
    * @return
    */
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

        self::writeMode($result);  //writing the result to the file after update operation
    }

    /** 
    * @desc delete product by passing the id for which delete needs to be applied
    * @param filename and rowIndex
    * @return 
    */
    public function deleteProduct(string $filename, int $rowIndex): void
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

        self::writeMode($result); //writing the result to the file after delete operation
    }

    /** 
    * @desc delete multiple products by passing the ids for which delete needs to be applied
    * @param filename and rowIndex
    * @return 
    */
    public function deleteMultipleProduct(string $filename, array $rowIndex): void
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

        self::writeMode($result); //writing the result to the file after delete operation
    }

    /** 
    * @desc writing the result to the file after cud operation
    * @param data
    * @return 
    */
    public function writeMode($data)
    { 
        $create = Writer::createFromPath($this->filePath, 'w+');
        $create->insertOne($this->header);        
        $create->insertAll($data);
    }

    /** 
    * @desc writing the headers to the file
    * @param 
    * @return 
    */
    public function writeHeaders()
    { 
        $create = Writer::createFromPath($this->filePath, 'w+');
        $create->insertOne($this->header);
    }
}

?>