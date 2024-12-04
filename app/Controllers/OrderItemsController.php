<?php

namespace App\Controllers;

use App\Models\OrderItems;


class OrderItemsController extends Controller{

    private $model;


    public function __construct() {
        $this->model = new OrderItems();

        parent::__construct($this->model, 'Order_items');
    }

    public function add($data, $requiredData) {
        $ids = [];
        foreach ($data['order_data'] as $key) {
            if (!isset($key) || empty($key)) {
                Helper::sendResponse(400, ['error' => "Missing or empty data: $key"]);
                return;
            }
            $key['order_id'] = $data['order_id'];
            $key['total'] = $key['price'] * $key['quantity'];
            $response = parent::add($key, $requiredData);
        }

        return $response;
    }

    public function readByOrderId($data) {
        $response = $this->model->readByOrderId($data['id']);
        return $response;
    }


} 