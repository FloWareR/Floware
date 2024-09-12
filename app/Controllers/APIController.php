<?php

namespace App\Controllers;

use App\Controllers\Helper;
use App\Controllers\AuthController;
use App\Controllers\ProductController;
use App\Controllers\CustomerController;
use App\Controllers\OrderController;
use App\Controllers\OrderItemsController;

class APIController {

    private $authController;
    private $productController;
    private $jwtMiddleware;
    private $customerController;
    private $orderController;
    private $orderItemsController;


    #region Products
    public function __construct() {
        $this->authController = new AuthController();
        $this->productController = new productController();
        $this->customerController = new CustomerController();
        $this->orderController = new OrderController();
        $this->orderItemsController = new OrderItemsController();


    }

    public function getProduct($data) {
        if(isset($_GET['id'])) {
            $response = $this->productController->getById($data);
            return;
        }
        $response = $this->productController->getAll($data);
    }

    public function addProduct($data) { 
        $requiredData = ['name', 'price', 'description', 'quantity'];
        $response = $this->productController->add($data, $requiredData);
        if(!$response) {
            Helper::sendResponse(400, ['error' => 'Error creating product']);
            die();
        }
        Helper::sendResponse(200, $response);
    }

    public function updateProduct($data) { 
        $response =$this->productController->update($data);
        if(!$response) {
            Helper::sendResponse(400, ['error' => 'Error updating product']);
            die();
        }
        Helper::sendResponse(200, $response);
    }

    public function deleteProduct($data) { 
        $response = $this->productController->delete($data);
    }
    #endregion


    #region Users
    public function login($data){
        $response = $this->authController->authenticate($data);
    }

    public function createUser($data){
        $response = $this->authController->create($data);
    }
    #endregion


    #region Customers
    public function getCustomer($data) {
        if(isset($_GET['id'])) {
            $response = $this->customerController->getById($data);
            return;
        }
        $response = $this->customerController->getAll($data);
        
    }

    public function addCustomer($data) {
        $requiredData = ['first_name', 'last_name', 'email', 'phone_number', 'address', 'type', 'company_name', 'payment_method'];
        $this->customerController->add($data, $requiredData);
    }

    public function updateCustomer($data) {
        $response = $this->customerController->update($data);
    }

    public function deleteCustomer($data) {
        $response = $this->customerController->delete($data);
    }
    #endregion

    #region Orders
 
    public function addOrder($data){
        
        $data = $this->productController->getPrice($data);
        $requiredData = ['user_id','customer_id', 'status','total_amount'];
        $data['order_id'] = $this->orderController->add($data, $requiredData);
        $requiredData = ['order_id', 'product_id', 'quantity', 'price'];
        $this->orderItemsController->add($data, $requiredData);
        $response = $this->productController->updateStock($data['order_data']);
        Helper::sendResponse(200, ['order_id' => $data['order_id']]);
        if(!$response) {
            Helper::sendResponse(400, ['error' => 'Error adding order items']);
            die();
        }
        return;
    }

    public function getOrder($data){
        echo "get order";
    }

    public function updateOrder($data){
        echo "update order";
    }

    public function deleteOrder($data){
        echo "delete order";
    }

    public function cancelOrder($data){
        echo "cancel order";
    }

    public function getordercustomer($data){
        echo "get order customer";
    }
    #endregion











    
    #region JWT
    public function verifyToken($clearance, $data) {
        if($this->jwtMiddleware->verifyToken($clearance, $data)) {
           return true;
        } else {
            Helper::sendResponse(401, ['message' => 'Unauthorized']);
            return false;
        }
    }
    #endregion
}