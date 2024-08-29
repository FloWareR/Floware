<?php

namespace App\Controllers;

use App\Controllers\Helper;
use App\Controllers\AuthController;
use App\Controllers\ProductController;
use App\Middleware\JWTMiddleware;

class APIController {

    private $authController;
    private $productController;
    private $jwtMiddleware;

    #region Products
    public function __construct() {
        $this->authController = new AuthController();
        $this->productController = new productController();
        $this->jwtMiddleware = new JWTMiddleware();
    }

    public function getProduct($data) {
        if(!$this->verifyToken($data)) return;
        if(isset($_GET['id'])) {
            $this->productController->getById();
            return;
        }
       $this->productController->getAll();
    }

    public function addProduct($data) { 
        if(!$this->verifyToken($data)) return;
        $this->productController->add();
    }

    public function updateProduct($data) { 
        if(!$this->verifyToken($data)) return;
        $this->productController->update();
    }

    public function deleteProduct($data) { 
        if(!$this->verifyToken($data)) return;
        $this->productController->delete();
    }
    #endregion


    #region Users
    public function login(){
        $data = Helper::getData();
        $this->authController->authenticate($data);
    }
    #endregion





    #region JWT
    public function verifyToken($data) {
        if($this->jwtMiddleware->verifyToken($data)) {
           return true;
        } else {
            Helper::sendResponse(401, ['message' => 'Token is invalid']);
            return false;
        }
    }
    #endregion
}