<?php

namespace App\Controllers;

use App\Models\Product;
use App\Controllers\Helper;
use PHPUnit\TextUI\Help;

class ProductsController {

    private $productModel;

    public function __construct() {
        $this->productModel = new Product();
    }

    public function getProduct() {
        $data = $_GET;
        if(!isset($data['id'])){
            $response = $this->productModel->readAll();
            Helper::sendResponse(200, $response);
            return;
        }

        $response = $this->productModel->readById($data);

        if(!$response) {
            Helper::sendResponse(404, ['error' => 'Product not ound']);
            return;
        }

        Helper::sendResponse(200, $response);

    }

    public function addProduct() { 
        $data = Helper::getData();
        if(!isset($data['name']) || !isset($data['description']) || !isset($data['price']) || !isset($data['quantity'])) {
            Helper::sendResponse(400, ['error' => 'Missing Data']);
            return;
        }

        $response = $this->productModel->create($data);
        Helper::sendResponse(201, $response);
    }

    public function updateProduct() { 
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