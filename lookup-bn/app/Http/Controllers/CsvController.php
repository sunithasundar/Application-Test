<?php

namespace App\Http\Controllers;

use App\Services\CsvOperation;
use Illuminate\Http\Request;

class CsvController extends Controller
{
    protected $csvOperation;
    protected $filePath;

    public function __construct(CsvOperation $csvOperation)
    {
        $this->csvOperation = $csvOperation;
        $this->filePath = "../app/csv/data.csv";
    }

    public function create(Request $request)
    {
        $inputData = $request->input('data'); 

        $data = $this->csvOperation->create($this->filePath,$inputData);
        return response()->json($data);
    }

    public function update(Request $request)
    { 
        // update accepts id 
        $inputData = $request->input('data');
        $rowIndex = $request->input('id');

        $data = $this->csvOperation->update($this->filePath,$inputData,$rowIndex); 
        return response()->json($data);
    }

    public function read()
    {
        $record = $this->csvOperation->read($this->filePath);
        return response()->json($record);
    }

    public function deleteById(Request $request)
    { 
        // delete accepts id 
        $id = $request->input('id');
        $data = $this->csvOperation->deleteById($this->filePath,$id);
        return response()->json($data);
    }
}