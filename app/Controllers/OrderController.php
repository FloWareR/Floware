<?php

namespace App\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Controllers\Helper;
use Exception;


class OrderController extends Controller{

    private $model;
    private $productModel;


    public function __construct() {
        $this->model = new Order();
        $this->productModel = new Product();

        parent::__construct($this->model, 'orders');
    }

    public function getAll($data) {
        parent::getAll($data);
    }

    public function getById($data) {
        parent::getById($data);
    }

    public function add($data, $requiredData) {
        $this->model->beginTransaction();
    
        try {
            $order_data = $data['order_data'];
            $total_amount = 0;
    
            foreach ($order_data as $product) {
                $product_data = $this->productModel->readById($product['product_id']);
                if($product_data['quantity'] < $product['quantity']) {
                    throw new Exception("Product Id: {$product_data['id']} not enough stock");
                }
                $total_amount += ($product_data['price'] * $product['quantity']);
            }
    
            $data['total_amount'] = $total_amount;
            unset($data['order_data']);
    
            foreach ($requiredData as $key) {
                if (!isset($data[$key]) || empty($data[$key])) {
                    throw new Exception("Missing or empty data: $key");
                }
            }
    
            $response = $this->model->create($data);
            if (!$response) {
                throw new Exception("Error creating order");
            }
    
            foreach ($order_data as $product) {
                $update = $this->model->updateStock($product);
                if (!$update) {
                    throw new Exception("Failed to update stock for product ID: {$product['product_id']}");
                }
            }
    
            $this->model->commit();
            $combinedMessage = array_merge($response, $update);
            Helper::sendResponse(201, $combinedMessage);
    
        } catch (Exception $e) {
            $this->model->rollback();
            Helper::sendResponse(400, ['error' => $e->getMessage()]);
        }
    }
    

    public function update($data) {
        parent::update($data);
    }

    public function delete($data) {
        parent::delete($data);
    }

} 