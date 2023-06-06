<?php

namespace App\Traits;

trait ResponseTrait
{
    /** 
    * @desc Api success response is taken care
    * @param message and data
    * @return response in json format
    */
    public function successResponse($message, $data=[])
    {
    	$response = [
            'success' => true,
            'message' => $message,
            'data' => $data
        ];

        return response()->json($response, 200);
    }

    /** 
    * @desc Api failure response is taken care
    * @param message and data
    * @return response in json format
    */
    public function failResponse($message, $data=[])
    {
    	$response = [
            'success' => false,
            'message' => $message,
            'data' => $data
        ];

        return response()->json($response, 500);
    }
}
