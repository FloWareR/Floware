<?php

namespace App\Controllers;

use App\Models\Product;

class ProductController extends Controller{

    private $model;

    public function __construct() {
        $this->model = new Product();
        return parent::__construct($this->model, 'products');
    }

    public function getAll($data) {
        return parent::getAll($data);
    }

    public function getById($data) {
        return parent::getById($data);
    }

    public function add($data, $requiredData) {
        return parent::add($data, $requiredData);
    }

    public function update($data) {
        return parent::update($data);
    }

    public function delete($data) {
        return parent::delete($data);
    }

    public function updateStock($data) {
        $response = null;
        foreach ($data as $product) {
            $product['id'] = $product['product_id'];
            $product['quantity'] = "quantity - {$product['quantity']}";
            unset($product['product_id']);
            unset($product['price']);           
            $response = $this->model->update($product);
        }
        
        return $response;
    }

    public function getPrice($data) {
        $total_amount = 0; 
        foreach ($data['order_data'] as &$product) {
            $product['read_column'] = ['price'];
            $product_data = $this->model->readColumn($product);
            if ($product_data) {
                $product['price'] = $product_data['price'];
                $total_amount += $product['price'] * $product['quantity'];
                unset($product['read_column']);
            } else {
                return null;
            }
        }

        $data['total_amount'] = $total_amount;
    
        return $data;
    }
}