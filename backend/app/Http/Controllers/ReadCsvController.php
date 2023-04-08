<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use League\Csv\Reader;
use League\Csv\Writer;
use League\Csv\Statement;

class ReadCsvController extends Controller
{
    protected $header = ['Name', 'Age', 'City', 'Phone','State'];
    
    //reading the sample file
    public function uploadCsv(Request $request)
    { 
        $filePath = "../app/csv/sample.csv";
        $csv = Reader::createFromPath($filePath,"r");
        $csv->setHeaderOffset(0);
        $records = $csv->getRecords();
        $data = [];

        foreach ($records as $record) {
            $data[] = $record;
        }

        return response()->json($data);
    }

    //saving the sample file with the newly added record
    public function saveCsv(Request $request)
    { 
        $filePath = "../app/csv/sample.csv";
        $data = $request->input('data');
        $writer = Writer::createFromPath($filePath, 'w+');
        $writer->insertOne($this->header);

        foreach ($data as $row) {
            $writer->insertOne($row);
        }

        return response()->json($data);
    }
}
