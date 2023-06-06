<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\ResponseTrait;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Throwable;
use Exception;
use Validator;

class ProductController extends Controller
{
    protected $product;
    protected $filePath;

    use ResponseTrait; //Traits to capture success and failure response

    public function __construct()
    {
        $this->product = new ProductService;
        
        $fileName = config('common.fileName');
        
        $this->filePath = Storage::disk('csv')->path($fileName);

        if (!file_exists($this->filePath)) {
            throw new Exception("data.csv file was not found, will generate one for you"); //on empty rows giving alert message and generating rows for user
        }
    }

    /** 
    * @desc get all records from csv file, passing request containing data to insert
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response in json format
    */
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
            //duplicate item name checking and setting flag accordingly
            foreach ($record as $checkDuplicate) {
                if(($inputData['item'] == $checkDuplicate['item']) && ($inputData['id'] != $checkDuplicate['id'])){
                    $duplicateFlag = 1;
                    return $this->failResponse("Duplicate Products Item name, recheck!",[]); //on duplicate item name user is given notification and not allowed to create duplicate
                }
            }

            //if not an duplicate item create Product and read Product return json response 
            if($duplicateFlag == 0){
                $data = $this->product->createProduct($this->filePath,$inputData); //create Product 
                
                $returnResponse = $this->getDetails();            
                return $this->successResponse("Success",$returnResponse);  //on success==true in ResponseTrait have defined the success status check  
            }
        }
        catch(Throwable $e)
		{
			return $this->failResponse($e->getMessage(),[]); //in ResponseTrait have defined the error status check 
		}
    }

    /** 
    * @desc update product, request has the data and the id for which the data to be updated 
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response in json format
    */
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

            $inputData = $request->input('data'); //data to update 
            $rowIndex = $request->input('id'); //update accepts id 

            $record = $this->product->readProduct($this->filePath); //read Produt 
            $duplicateFlag = 0;
            //duplicate item name checking and setting flag accordingly
            foreach ($record as $checkDuplicate) {
                if(($inputData['item'] == $checkDuplicate['item']) && ($inputData['id'] != $checkDuplicate['id'])){
                    $duplicateFlag = 1;
                    return $this->failResponse("Duplicate Products Item name, recheck!",[]); //on duplicate item name user is given notification and not allowed to create duplicate
                }
            }
            
            //if not an duplicate item update Product and read Product return json response 
            if($duplicateFlag == 0){
                $data = $this->product->updateProduct($this->filePath,$inputData,$rowIndex); //update Product passing id 
                
                $returnResponse = $this->getDetails();            
                return $this->successResponse("Success",$returnResponse);  //on success==true in ResponseTrait have defined the success status check   
            }
        }
        catch(Throwable $e)
		{
			return $this->failResponse($e->getMessage(),[]); //on success==false corresponding error will be returned. 
		}
    }

    /** 
    * @desc read product, gets the data from csv file 
    * @param 
    * @return \Illuminate\Http\Response in json format
    */
    public function readProduct()
    {
        try{
            $returnResponse = $this->getDetails();            
            return $this->successResponse("Success",$returnResponse);  //on success==true in ResponseTrait have defined the success status check
        }
        catch(Throwable $e)
		{
			return $this->failResponse($e->getMessage(),[]); //on success==false corresponding error will be returned. 
		}
    }

    /** 
    * @desc delete product, deletes the id passed to it
    * @param \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response in json format
    */
    public function deleteProduct(Request $request)
    { 
        try{
            // delete accepts id 
            $id = $request->input('id');
            $data = $this->product->deleteProduct($this->filePath,$id); //delete Product call with id passed to it 
            
            $returnResponse = $this->getDetails();                      
        }
        catch(Throwable $e)
        {
            return $this->failResponse($e->getMessage(),[]); //on success==false corresponding error will be returned. 
        }
    }

    /** 
    * @desc delete multiple product, deletes the ids passed to it which is an array
    * @param \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response in json format
    */
    public function deleteMultipleProduct(Request $request)
    { 
        try{
            // delete accepts id 
            $id = $request->input('ids');
            $data = $this->product->deleteMultipleProduct($this->filePath,$id); //delete Product call with ids passed to it as a array
            
            $returnResponse = $this->getDetails();             
            return $this->successResponse("Success",$returnResponse);  //on success==true in ResponseTrait have defined the success status check    
        }
        catch(Throwable $e)
        {
            return $this->failResponse($e->getMessage(),[]); //on success==false corresponding error will be returned. 
        }
    }

    /** 
    * @desc after all the crud operation read product to display the datas, used by all the functions
    * @param 
    * @return \Illuminate\Http\Response in json format else empty response
    */
    public function getDetails() {
        $records = $this->product->readProduct($this->filePath); //read Product
        $returnResponse = $records ? response()->json($records) : []; //json response

        return $returnResponse;
    }
}