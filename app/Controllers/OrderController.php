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
       return parent::getById($data);
    }

    public function add($data, $requiredData) {
        try {
            foreach ($requiredData as $key) {
                if ((!isset($data[$key]) || empty($data[$key])) && $key !== 'total_amount') { 
                    throw new Exception("Missing or empty data: $key");
                }
            }
    
            unset($data['order_data']);
            $response = $this->model->create($data);
            return $response['id'];

        } catch (Exception $e) {
            Helper::sendResponse(400, ['error' => $e->getMessage()]);
            die();
        }
    }

    public function update($data) {
        parent::update($data);
    }

    public function delete($data) {
        parent::delete($data);
    }


} 