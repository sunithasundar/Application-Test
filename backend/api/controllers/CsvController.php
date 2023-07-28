<?php
require_once __DIR__ ."\..\..\api\models\CsvModel.php";

class CsvController {
    private $model;

    public function __construct() {
        $this->model = new CsvModel('..\..\data.csv');
    }

    /** 
    * @desc Api to list products
    */
    public function getAllData() {
        return $this->model->getAllData();
    }

    /** 
    * @desc Api to create product
    */
    public function createData($data) {
        return $this->model->createData($data);
    }

    /** 
    * @desc Api to update product
    */
    public function updateData($index, $data) {
        return $this->model->updateData($index, $data);
    }

    /** 
    * @desc Api to delete product
    */
    public function deleteData($index) {
        return $this->model->deleteData($index);
    }

    /** 
    * @desc Api to delete multiple product
    */
    public function deleteMultipleData($index) {
        return $this->model->deleteMultipleData($index);
    }

    /** 
    * @desc Api to check if product exists
    */
    public function dataExists($index) {
        return $this->model->dataExists($index);
    }

    /** 
    * @desc Api success response is taken care
    */
    public function successResponse($index, $data) {
        return $this->model->successResponse($index, $data);
    }

    /** 
    * @desc Api failure response is taken care
    */
    public function failResponse($index, $data) {
        return $this->model->failResponse($index, $data);
    }
}
?>
