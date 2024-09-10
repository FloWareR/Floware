<?php

namespace App\Controllers;

use App\Models\Model;
use App\Controllers\Helper;

class Controller {

    private $model;
    private $tableName;

    public function __construct($model, $tableName) {
        $this->model = $model;
        $this->tableName = $tableName;
    }
    
    public function getById($data){
        $response = $this->model->readById($data['id']);
        if(!$response) {
            Helper::sendResponse(404, ['error' => "$this->tableName not found"]);
            return;
        }
        Helper::sendResponse(200, $response);

    }

    public function getAll($data) {
        $response = $this->model->readAll();
        Helper::sendResponse(200, $response);
        return;
    }

    public function add($data, $requiredData) { 
      foreach ($requiredData as $key) {
        if (!isset($data[$key]) || empty($data[$key])) {
            Helper::sendResponse(400, ['error' => "Missing or empty data: $key"]);
            return;
            }
          }

        $response = $this->model->create($data);
        Helper::sendResponse(201, $response);
    }

    public function update($data) { 
        if(!isset($_GET['id']) || !isset($data)) {
            Helper::sendResponse(400, ['error' => "$this->tableName not found or data missing"]);
            return;
        }
        $data['id'] = $_GET['id'];
        $response = $this->model->update($data);
        echo json_encode($response);

    }

    public function delete($data) { 
        if(!isset($_GET['id'])) {
            Helper::sendResponse(400, ['error' => "$this->tableName not found"]);
            return;
        }
        $response = $this->model->delete($data);
        Helper::sendResponse(200, $response);
    }

}