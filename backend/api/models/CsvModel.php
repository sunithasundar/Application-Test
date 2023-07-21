<?php
class CsvModel {
    private $csvFile;

    public function __construct($csvFile) {
        $this->csvFile = $csvFile;        
    }

    public function getAllData() {
        $data = []; $header = []; 
        $data = array_map('str_getcsv', file($this->csvFile));
        $dataHeader = array_shift($data);
        $result = []; 

        $header = ['id', 'name', 'state', 'zip','amount', 'qty','item'];

        if(count($dataHeader) == count($header))
        {
            if (array_values($dataHeader) === array_values($header)) {
                
            } else {
                return "data.csv file header mismatch, will generate one for you";
            }
        }
        
        foreach ($data as $row) {
            $result[] = array_combine($dataHeader, $row);
        }
        
        header('Content-Type: application/json');
        return $result; 
    }

    public function createData($data) {

        $validation = $this->validation($data); 
        
        $result = [];
        if($validation == ""){            
            $handle = fopen($this->csvFile, "a");
            fputcsv($handle, $data);
            fclose($handle);
        }     
        else 
        { 
           return $validation;
        }
    }

    public function validation($data){
        
        // Validation rules
        $id = $data['id'];
        $name = $data['name'];
        $state = $data['state'];
        $zip = $data['zip'];
        $amount = $data['amount'];
        $qty = $data['qty'];
        $item = $data['item'];

        $errors = [];
        if (empty($name)) {
            $errors[] = "Name is required";
        } elseif (strlen($name) < 5 || strlen($name) > 70) {
            $errors[] = "Name must be between 5 and 70 characters long";
        } elseif (!preg_match('/^[a-zA-Z0-9 ]*[a-zA-Z0-9]+[a-zA-Z0-9 ]*$/', $name)) {
            $errors[] = "Name should not contain special characters and space";
        }

        if (empty($state)) {
            $errors[] = "State is required";
        } elseif (!preg_match('/^[a-zA-Z ]*[a-zA-Z]+[a-zA-Z ]*$/', $state)) {
            $errors[] = "State should not contain special characters and numbers";
        }

        if (empty($zip)) {
            $errors[] = "Zip is required";
        } elseif (strlen($zip) < 4 || strlen($zip) > 6) {
            $errors[] = "Zip must be maximum 6 digit long";
        } elseif (!preg_match('/^[0-9]*[1-9]+[0-9]*$/', $zip)) {
            $errors[] = "Zip should contain only numbers";
        }

        if (empty($amount)) {
            $errors[] = "Amount is required";
        } elseif (!preg_match('/^[0-9.]*[1-9]+[0-9]*$/', $amount)) {
            $errors[] = "Amount should contain only numbers";
        }

        if (empty($qty)) {
            $errors[] = "Qty is required";
        } elseif (strlen($qty) < 1 || strlen($qty) > 4) {
            $errors[] = "Qty must be maximum 4 digits long";
        } elseif (!preg_match('/^[0-9]*[1-9]+[0-9]*$/', $qty)) {
            $errors[] = "Qty should contain only numbers";
        }

        if (empty($item)) {
            $errors[] = "Item is required";
        } elseif (strlen($item) > 50) {
            $errors[] = "Item must be maximum 50 characters long";
        } elseif (!preg_match('/^[a-zA-Z0-9 ]*[a-zA-Z0-9]+[a-zA-Z0-9 ]*$/', $item)) {
            $errors[] = "Item should contain only characters and numbers";
        }

        if ($item) {
            $duplicateFlag = $this->duplicateItem($item, $id); 
            
            if($duplicateFlag >= 1){
                $errors[] = "Duplicate Products Item name, recheck!";
            }
        }
        
        $errorSet = "";
        if (count($errors) > 0) {
            // Display validation errors
            foreach ($errors as $error) {
                $errorSet .= $error . "  ";
            }
        }
        return $errorSet;
    }

    public function updateData($index, $data) {

        $validation = $this->validation($data);
        
        $result = [];
        if($validation == ""){
            
            $rows = $this->getAllData();        
            
            if (is_array($rows)) {
                $handle = fopen($this->csvFile, "w");
                $header = ['id', 'name', 'state', 'zip','amount', 'qty','item'];
                fputcsv($handle, $header);

                $record = [];
                foreach ($rows as $record) {
                    //if the id match update that record and push to array
                    if ($record['id'] == $index) {  
                        $data['id'] = $record['id'];
                        $record = array_replace($record, $data);
                    }   
                    array_push($result,$record);    
                } 
            }
            
            foreach ($result as $row) {
                fputcsv($handle, $row);
            }
            fclose($handle);
        }     
        else 
        {  
           return $validation;
        }   
    }

    public function deleteData($index) { 
        $rows = $this->getAllData();

        $result = [];
        foreach ($rows as $record) {
            //if the id match remove that record and push to array
            if ($record['id'] != $index) { 
                array_push($result,$record);           
            }            
        }

        $handle = fopen($this->csvFile, "w");
        $header = ['id', 'name', 'state', 'zip','amount', 'qty','item'];
        fputcsv($handle, $header);

        foreach ($result as $row) {
            fputcsv($handle, $row);
        }
        fclose($handle);
    }

    public function deleteMultipleData($index) { 
        $rows = $this->getAllData();

        $result = [];
        foreach ($rows as $record) { 
            //if the id match remove that record and push to array
            if (in_array($record['id'],$index, TRUE)){

            }      
            else
            {
                array_push($result,$record);   
            }      
        }

        $handle = fopen($this->csvFile, "w");
        $header = ['id', 'name', 'state', 'zip','amount', 'qty','item'];
        fputcsv($handle, $header);

        foreach ($result as $row) {
            fputcsv($handle, $row);
        }
        fclose($handle);
    }

    public function dataExists($index) { 
        $rows = $this->getAllData();

        $result = [];
        foreach ($rows as $record) { 
            //if the id match remove that record and push to array
            if (in_array($record['id'],$index, TRUE)){
                return false;
            }      
            else
            {
                return true;
            }      
        }        
    }

    public function duplicateItem($index,$id) {
        $rows = $this->getAllData();

        $duplicateFlag = 0;
        //duplicate item name checking
        foreach ($rows as $checkDuplicate) {
            if($index == $checkDuplicate['item'] && $id!=$checkDuplicate['id']){
                $duplicateFlag = $duplicateFlag + 1;
            }
        }

        return $duplicateFlag;    
    }


    /** 
    * @desc Api success response is taken care
    * @param message and data
    * @return response in json format
    */
    public function successResponse($message, $data=[])
    {
    	$response = [
            'success' => 'true',
            'message' => $message,
            'data' => $data
        ];

        $jsonResponse = json_encode($response); 
    
        header('Content-Type: application/json');
        echo $jsonResponse;
    }

    /** 
    * @desc Api failure response is taken care
    * @param message and data
    * @return response in json format
    */
    public function failResponse($message, $data=[])
    {
    	$response = [
            'success' => 'false',
            'message' => $message,
            'data' => $data
        ];

        $jsonResponse = json_encode($response); 
    
        header('Content-Type: application/json');
        echo $jsonResponse;
    }
}
?>
