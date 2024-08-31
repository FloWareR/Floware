<?php

namespace App\Controllers;

use App\Controllers\Helper;
use App\Controllers\AuthController;
use App\Controllers\ProductController;

class APIController {

    private $authController;
    private $productController;
    private $jwtMiddleware;

    #region Products
    public function __construct() {
        $this->authController = new AuthController();
        $this->productController = new productController();
    }

    public function getProduct($data) {
        if(isset($_GET['id'])) {
            $this->productController->getById();
            return;
        }
       $this->productController->getAll();
    }

    public function addProduct($data) { 
        $this->productController->add();
    }

    public function updateProduct($data) { 
        $this->productController->update();
    }

    public function deleteProduct($data) { 
        $this->productController->delete();
    }
    #endregion


    #region Users
    public function login($data){
        $this->authController->authenticate($data);
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