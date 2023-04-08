<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use Illuminate\Http\Request;
use Throwable;
use Validator;

class ProductController extends Controller
{
    protected $product;
    protected $filePath;

    public function __construct(ProductService $product)
    {
        $this->product = $product;
        $this->filePath = "../app/csv/data.csv";
    }

    public function createProduct(Request $request)
    {
        try{
            //validation handled in BN laravel
            $validator = Validator::make($request->data, [
				'id' => 'required|numeric',
                'name' => 'required|alpha|min:5|max:70',
                'state' => 'required|alpha',
                'zip' => 'required|digits_between:5,6',
                'amount' => 'required|numeric',
                'qty' => 'required|digits_between:1,4',
                'item' => 'required|alpha_num|max:70'
			]);
			
			if ($validator->fails()) {
				return $this->failResponse('Validation Error.',$validator->messages()); //returns error message accordingly 
			}

            $inputData = $request->input('data'); //get data from request to pass to create Product

            $data = $this->product->createProduct($this->filePath,$inputData); //create Product 
            $record = $this->product->readProduct($this->filePath); //read Product 
            $returnResponse = response()->json($record); //json response

            return $this->successResponse("Success",$returnResponse);  //in controller.php have defined the success status check 
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
                'name' => 'required|regex:/^[a-zA-Z0-9\s]+$/|min:5|max:70',
                'state' => 'required|regex:/^[a-zA-Z0-9\s]+$/',
                'zip' => 'required|digits_between:5,6',
                'amount' => 'required|numeric',
                'qty' => 'required|digits_between:1,4',
                'item' => 'required|regex:/^[a-zA-Z0-9\s]+$/|max:70'
			]);
			
			if ($validator->fails()) {
				return $this->failResponse('Validation Error.',$validator->messages()); //returns error message accordingly 
			}

            $inputData = $request->input('data');
            $rowIndex = $request->input('id'); // update accepts id 

            $data = $this->product->updateProduct($this->filePath,$inputData,$rowIndex); //update Product passing id 
            $record = $this->product->readProduct($this->filePath); //read Produt 
            $returnResponse = response()->json($record); //json response

            return $this->successResponse("Success",$returnResponse); //on success==true this response will be returned. 
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
            
            if(count($record) == 0)
            {
                return $this->failResponse("Please upload correct file, since columns doesnt match!",[]);
            }
            else
            { 
                return $this->successResponse("Success",$returnResponse);  //in controller.php have defined the success status check 
            }
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
}