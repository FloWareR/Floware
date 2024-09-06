<?php

namespace App\Controllers;

use App\Models\Product;
use App\Controllers\Helper;

class ProductController {

    private $productModel;

    #region Products
    public function __construct() {
        $this->productModel = new Product();
    }
    
    //Return a product by id
    public function getById($data){
        $response = $this->productModel->readById($data['id']);
        if(!$response) {
            Helper::sendResponse(404, ['error' => 'Product not found']);
            return;
        }
        Helper::sendResponse(200, $response);

    }

    //Return all products
    public function getAll($data) {
        $response = $this->productModel->readAll();
        Helper::sendResponse(200, $response);
        return;
    }

    //Add a product
    public function add($data) { 
        if(!isset($data['name']) || !isset($data['description']) || !isset($data['price']) || !isset($data['quantity'])) {
            Helper::sendResponse(400, ['error' => 'Missing Data']);
            return;
        }

        $response = $this->productModel->create($data);
        Helper::sendResponse(201, $response);
    }

    //Update a product
    public function update($data) { 
        if(!isset($_GET['id']) || !isset($data)) {
            Helper::sendResponse(400, ['error' => 'Product not found or data missing']);
            return;
        }
        $data['id'] = $_GET['id'];
        $response = $this->productModel->update($data);
        echo json_encode($response);

    }

    //Delete a product
    public function delete($data) { 
        if(!isset($_GET['id'])) {
            Helper::sendResponse(400, ['error' => 'Product not found']);
            return;
        }
        $response = $this->productModel->delete($data);
        Helper::sendResponse(200, $response);
    }


}