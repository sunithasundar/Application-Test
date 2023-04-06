<?php


namespace App\Services;

use App\Interfaces\CsvOperationInterface;
use League\Csv\Reader;
use League\Csv\Writer;
use Illuminate\Support\Facades\Storage;

class CsvOperation implements CsvOperationInterface
{
    protected $reader;
    protected $writer;
    protected $header;

    public function __construct()
    {
        //used for reading 
        $this->reader = Reader::createFromPath("../app/csv/data.csv","r");

        //used for update 
        $this->writer = Writer::createFromPath(storage_path("../app/csv/data.csv"), 'a+');
        $this->header = ['id', 'name', 'state', 'zip','amount', 'qty','item'];

        //i have not declared w+ here since it will clear the data so i am using it below
    }

    //initialise headers and get datas to process
    public function initialise(){ 
        $this->reader->setHeaderOffset(0);
        $data = $this->reader->getRecords(); 
        
        return $data;
    }

    public function create(string $filename, array $data): array
    {
        $this->writer->insertOne($data);
    }

    public function read(string $filename): array
    {
        //read the data to process
        $records  = self::initialise();   
        
        $result = [];
        foreach ($records as $record) {
            $result[] = $record;
        }

        return $result;
    }

    public function update(string $filename, array $data, int $rowIndex): void{
        
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

        self::writeMode($result);
    }

    public function deleteById(string $filename, $rowIndex): void
    {
        $records  = self::initialise(); 
        
        $result = [];
        foreach ($records as $record) {
            //if the id match remove that record and push to array
            if ($record['id'] != $rowIndex) { 
                array_push($result,$record);           
            }            
        }

        self::writeMode($result);
    }

    //writing the resultant to the file after delete and update operation
    public function writeMode($data)
    { 
        $create = Writer::createFromPath("../app/csv/data.csv", 'w+');
        $create->insertOne($this->header);        
        $create->insertAll($data);
    }
}

?>