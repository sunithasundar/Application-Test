<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../controllers/CsvController.php';

$userController = new CsvController(); 

$csvFile = '..\..\data.csv';
if (!file_exists($csvFile)) {
            
    $handle = fopen($csvFile, "w");
    $header = ['id', 'name', 'state', 'zip','amount', 'qty','item'];
    fputcsv($handle, $header);

    return $userController->failResponse("data.csv file was not found, will generate one for you",[]);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try{
        $data = $userController->getAllData();

        if (is_string($data) && strpos($data, "file header mismatch") !== false) {

            $handle = fopen($csvFile, "w");
            $header = ['id', 'name', 'state', 'zip','amount', 'qty','item'];
            fputcsv($handle, $header);
            
            $response = $userController->failResponse($data,[]); //in ResponseTrait have defined 
        }
        else
        {
            $response = $userController->successResponse("Success",$data);  //on success==true in ResponseTrait have defined the success status check  
        }
    }
    catch(Exception $e)
    {
        $response = $userController->failResponse($e->getMessage(),[]); //in ResponseTrait have defined the error status check 
    }
    
    return $response;

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestPayload = json_decode(file_get_contents('php://input'), true);
    $data = $requestPayload['data']; 

    try{
        $aa = $userController->createData($data);
        if(!$aa){
            $data = $userController->getAllData();
        }
        else {
            return $userController->failResponse($aa,[]);
        }
        
        return $userController->successResponse("Success",$data);  //on success==true in ResponseTrait have defined the success status check  
    }
    catch(Exception $e)
    {
        $response = $userController->failResponse($e->getMessage(),[]); //in ResponseTrait have defined the error status check 
    }

    return $response;
    
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $requestPayload = json_decode(file_get_contents('php://input'), true); 
    $data = $requestPayload['data'];
    $id = $requestPayload['id']; 

    try{
        $aa = $userController->updateData($id,$data);

        if(!$aa){
            $data = $userController->getAllData();
        }
        else {
            return $userController->failResponse($aa,[]);
        }
        
        return $userController->successResponse("Success",$data);  //on success==true in ResponseTrait have defined the success status check  
    }
    catch(Exception $e)
    { 
        return $userController->failResponse($e->getMessage(),[]); //in ResponseTrait have defined the error status check 
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') { 
    $requestPayload = json_decode(file_get_contents('php://input'), true); 

    if(isset($requestPayload['id'])){
        $id = $requestPayload['id']; 
        $userController->deleteData($id);
    }
    else
    {
        $ids = $requestPayload['ids']; 
        $userController->deleteMultipleData($ids);
    }

    try{
        $data = $userController->getAllData();
        
        return $userController->successResponse("Success",$data);  //on success==true in ResponseTrait have defined the success status check  
    }
    catch(Exception $e)
    {
        return $userController->failResponse($e->getMessage(),[]); //in ResponseTrait have defined the error status check 
    }
}

?>