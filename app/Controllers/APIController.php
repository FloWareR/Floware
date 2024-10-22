<?php

namespace App\Controllers;

use App\Controllers\Helper;
use App\Controllers\AuthController;
use App\Controllers\ProductController;
use App\Controllers\CustomerController;
use App\Controllers\OrderController;
use App\Controllers\OrderItemsController;
use App\Controllers\OneTimeCodeController;
use App\TransactionManager;

use App\Models\Model;

use Exception;

class APIController {

    private $authController;
    private $productController;
    private $jwtMiddleware;
    private $customerController;
    private $orderController;
    private $orderItemsController;
    private $onetimecodeController;


    #region Products
    public function __construct() {
        $this->authController = new AuthController();
        $this->productController = new productController();
        $this->customerController = new CustomerController();
        $this->orderController = new OrderController();
        $this->orderItemsController = new OrderItemsController();
        $this->onetimecodeController = new OneTimeCodeController();

    }

    public function getProduct($data) {
        if(isset($_GET['id'])) {
            $response = $this->productController->getById($data);
            Helper::sendResponse(200, $response);
        }
        $response = $this->productController->getAll($data);
        Helper::sendResponse(200, $response);
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
        if(!$response) {
            Helper::sendResponse(400, ['error' => 'Error deleting product']);
            die();
        }
        Helper::sendResponse(200, $response);
    }
    #endregion


    #region Users
    public function login($data){
        $response = $this->authController->authenticate($data);
        Helper::sendResponse(200, $response);
    }

    public function createUser($data){
        $response = $this->authController->create($data);
        if(isset($response['error'])) {
            Helper::sendResponse(400, $response);
            die();
        }
        Helper::sendResponse(200, $response);
    }

    public function getUser($data){
        $response = $this->authController->getById($data);
        Helper::sendResponse(200, $response);   
    }

    public function updateUser($data){
        $response = $this->authController->update($data);
        Helper::sendResponse(200, $response);
    }
    #endregion


    #region Customers
    public function getCustomer($data) {
        if(isset($_GET['id'])) {
            $response = $this->customerController->getById($data);
            Helper::sendResponse(200, $response);
        }
        $response = $this->customerController->getAll($data);
        Helper::sendResponse(200, $response);
        
    }

    public function addCustomer($data) {
        $requiredData = ['first_name', 'last_name', 'email', 'phone_number', 'address', 'type', 'company_name', 'payment_method'];
        $response = $this->customerController->add($data, $requiredData);
        if(!$response) {
            Helper::sendResponse(400, ['error' => 'Error creating customer']);
            die();
        }
        Helper::sendResponse(200, $response);
    }

    public function updateCustomer($data) {
        $response = $this->customerController->update($data);
        if(!$response) {
            Helper::sendResponse(400, ['error' => 'Error updating customer']);
            die();
        }
        Helper::sendResponse(200, $response);
    }

    public function deleteCustomer($data) {
        $response = $this->customerController->delete($data);
        if(!$response) {
            Helper::sendResponse(400, ['error' => 'Error deleting customer']);
            die();
        }
        Helper::sendResponse(200, $response);
    }
    #endregion

    #region Orders
 
    public function addOrder($data) {
        try {
            $transactionManager = TransactionManager::getInstance();
            $transactionManager->beginTransaction();
            $data = $this->productController->getPrice($data);
            $requiredData = ['user_id','customer_id', 'status','total_amount'];
            $data['order_id'] = $this->orderController->add($data, $requiredData);
            $requiredData = ['order_id', 'product_id', 'quantity', 'price'];
            $this->orderItemsController->add($data, $requiredData);
            $response = $this->productController->updateStock($data['order_data']);
            if(!$response) {
                throw new \Exception('Error updating stock');
            }
            $transactionManager->commit();
            Helper::sendResponse(200, ['order_id' => $data['order_id']]);
        } catch (\Exception $e) {
            $transactionManager->rollBack();
            Helper::sendResponse(500, ['error' => $e->getMessage()]);
        }
    }
    
    public function getOrder($data){
        if(isset($_GET['id'])) {
            $response = $this->orderController->getById($data);
            $response['products'] = $this->orderItemsController->readByOrderId($data);
            Helper::sendResponse(200, $response);
        }
        $response = $this->orderController->getAll($data);
        Helper::sendResponse(200, $response);
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

    public function getcustomerorders($data){
        echo "get order customer";
    }
    #endregion


    #region OneTimeCode

    public function createsignupcode($data) {
        $response = $this->onetimecodeController->create($data);
        Helper::sendResponse(200, $response);
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