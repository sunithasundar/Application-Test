<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Throwable;
use Exception;
use Validator;
use File;

class ProductController extends Controller
{
    protected $product;
    protected $filePath;

    public function __construct(ProductService $product)
    {
        $this->product = $product;
        $this->filePath = "../app/csv/data.csv"; //to run the testcase change this to "app/csv/data.csv"

        if (!file_exists($this->filePath)) {
            throw new Exception("data.csv file was not found, will generate one for you"); //on empty rows giving alert message and generating rows for user

            File::copy("../app/csv/copy.csv", "../app/csv/data.csv");  //to run the testcase change this to "app/csv/data.csv"
        }
    }

    public function createProduct(Request $request)
    {
        try{
            //validation handled in BN laravel
            $validator = Validator::make($request->data, [
				'id' => 'required|numeric',
                'name' => 'required|regex:/^[a-zA-Z0-9\s]*[a-zA-Z0-9]+[a-zA-Z0-9\s]*$/|min:5|max:70',
                'state' => 'required|regex:/^[a-zA-Z\s]*[a-zA-Z]+[a-zA-Z\s]*$/',
                'zip' => 'required|digits_between:4,6',
                'amount' => 'required|numeric',
                'qty' => 'required|digits_between:1,4',
                'item' => 'required|regex:/^[a-zA-Z0-9\s]*[a-zA-Z0-9]+[a-zA-Z0-9\s]*$/|max:50'
			]);
			
			if ($validator->fails()) {
				return $this->failResponse('Validation Error.',$validator->messages()); //returns error message accordingly 
			}

            $inputData = $request->input('data'); //get data from request to pass to create Product

            $record = $this->product->readProduct($this->filePath); //read Product 

            $duplicateFlag = 0;
            //duplicate item name checking
            foreach ($record as $checkDuplicate) {
                if(($inputData['item'] == $checkDuplicate['item']) && ($inputData['id'] != $checkDuplicate['id'])){
                    $duplicateFlag = 1;
                    return $this->failResponse("Duplicate Products Item name, recheck!",[]); //on duplicate item name user is given notification and not allowed to create duplicate
                }
            }

            if($duplicateFlag == 0){
                $data = $this->product->createProduct($this->filePath,$inputData); //create Product 
                $records = $this->product->readProduct($this->filePath); //read Product 
                $returnResponse = response()->json($records); //json response

                return $this->successResponse("Success",$returnResponse);  //in controller.php have defined the success status check 
            }
        }
        catch(Throwable $e)
		{
			return $this->failResponse($e->getMessage(),[]); //in controller.php have defined the error status check 
		}
    }

    public function updateProduct(Request $request)
    { 
        try{
            //validation handled in BN laravel
            $validator = Validator::make($request->data, [
				'id' => 'required|numeric',
                'name' => 'required|regex:/^[a-zA-Z0-9\s]*[a-zA-Z0-9]+[a-zA-Z0-9\s]*$/|min:5|max:70',
                'state' => 'required|regex:/^[a-zA-Z\s]*[a-zA-Z]+[a-zA-Z\s]*$/',
                'zip' => 'required|digits_between:4,6',
                'amount' => 'required|numeric',
                'qty' => 'required|digits_between:1,4',
                'item' => 'required|regex:/^[a-zA-Z0-9\s]*[a-zA-Z0-9]+[a-zA-Z0-9\s]*$/|max:50'
			]);
			
			if ($validator->fails()) {
				return $this->failResponse('Validation Error.',$validator->messages()); //returns error message accordingly 
			}

            $inputData = $request->input('data');
            $rowIndex = $request->input('id'); // update accepts id 

            $record = $this->product->readProduct($this->filePath); //read Produt 
            $duplicateFlag = 0;
            //duplicate item name checking
            foreach ($record as $checkDuplicate) {
                if(($inputData['item'] == $checkDuplicate['item']) && ($inputData['id'] != $checkDuplicate['id'])){
                    $duplicateFlag = 1;
                    return $this->failResponse("Duplicate Products Item name, recheck!",[]); //on duplicate item name user is given notification and not allowed to create duplicate
                }
            }
            
            if($duplicateFlag == 0){
                $data = $this->product->updateProduct($this->filePath,$inputData,$rowIndex); //update Product passing id 
                $records = $this->product->readProduct($this->filePath); //read Product 
                $returnResponse = response()->json($records); //json response

                return $this->successResponse("Success",$returnResponse); //on success==true this response will be returned. 
            }
        }
        catch(Throwable $e)
		{
			return $this->failResponse($e->getMessage(),[]); //on success==false corresponding error will be returned. 
		}
    }

    public function readProduct()
    {
        try{
            $record = $this->product->readProduct($this->filePath); //read Product  
            $returnResponse = response()->json($record); //json response 
            
            return $this->successResponse("Success",$returnResponse);  //in controller.php have defined the success status check 
            
        }
        catch(Throwable $e)
		{
			return $this->failResponse($e->getMessage(),[]); //on success==false corresponding error will be returned. 
		}
    }

    public function deleteProduct(Request $request)
    { 
        try{
            // delete accepts id 
            $id = $request->input('id');
            $data = $this->product->deleteProduct($this->filePath,$id); //delete Product call with id passed to it 
            $record = $this->product->readProduct($this->filePath); //read Product            
            $returnResponse = response()->json($record); //json response 
            
            return $this->successResponse("Success",$returnResponse); //on success==true this response will be returned.               
        }
        catch(Throwable $e)
        {
            return $this->failResponse($e->getMessage(),[]); //on success==false corresponding error will be returned. 
        }
    }

    public function deleteMultipleProduct(Request $request)
    { 
        try{
            // delete accepts id 
            $id = $request->input('ids');
            $data = $this->product->deleteMultipleProduct($this->filePath,$id); //delete Product call with ids passed to it as a array
            $record = $this->product->readProduct($this->filePath); //read Product            
            $returnResponse = response()->json($record); //json response 
            
            return $this->successResponse("Success",$returnResponse); //on success==true this response will be returned.               
        }
        catch(Throwable $e)
        {
            return $this->failResponse($e->getMessage(),[]); //on success==false corresponding error will be returned. 
        }
    }
}