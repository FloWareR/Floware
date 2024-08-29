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
    public function getById(){
        $data = Helper::getData();
        $response = $this->productModel->readById($data['id']);
        if(!$response) {
            Helper::sendResponse(404, ['error' => 'Product not found']);
            return;
        }
        Helper::sendResponse(200, $response);

    }

    //Return all products
    public function getAll() {
        $data = Helper::getData();
        $response = $this->productModel->readAll();
        Helper::sendResponse(200, $response);
        return;
    }

    //Add a product
    public function add() { 
        $data = Helper::getData();
        if(!isset($data['name']) || !isset($data['description']) || !isset($data['price']) || !isset($data['quantity'])) {
            Helper::sendResponse(400, ['error' => 'Missing Data']);
            return;
        }

        $response = $this->productModel->create($data);
        Helper::sendResponse(201, $response);
    }

    //Update a product
    public function update() { 
        $data = Helper::getData();
        if(!isset($_GET['id'])) {
            http_response_code(400);
            $response = ['error' => 'Product not found'];
            echo json_encode($response);
            return;
        }
        $data['id'] = $_GET['id'];
        $response = $this->productModel->update($data);
        echo json_encode($response);

    }


}