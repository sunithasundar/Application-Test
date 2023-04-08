<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function successResponse($message, $data=[])
    {
    	$response = [
            'success' => true,
            'message' => $message,
            'data' => $data
        ];

        return response()->json($response, 200);
    }

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
