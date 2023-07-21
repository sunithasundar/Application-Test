<?php
require_once __DIR__ ."\..\..\api\models\CsvModel.php";

class CsvController {
    private $model;

    public function __construct() {
        $this->model = new CsvModel('..\..\data.csv');
        // $this->model = new CsvModel('..\backend\data.csv');
        
    }

    public function getAllData() {
        return $this->model->getAllData();
    }

    public function createData($data) {
        return $this->model->createData($data);
    }

    public function updateData($index, $data) {
        return $this->model->updateData($index, $data);
    }

    public function deleteData($index) {
        return $this->model->deleteData($index);
    }

    public function deleteMultipleData($index) {
        return $this->model->deleteMultipleData($index);
    }

    public function dataExists($index) {
        return $this->model->dataExists($index);
    }

    public function successResponse($index, $data) {
        return $this->model->successResponse($index, $data);
    }

    public function failResponse($index, $data) {
        return $this->model->failResponse($index, $data);
    }
}
?>
