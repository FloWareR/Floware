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

    public function getProduct($clearance, $data) {
        if(!$token = $this->verifyToken($clearance, $data)) return;
        if(isset($_GET['id'])) {
            $this->productController->getById();
            return;
        }
       $this->productController->getAll();
    }

    public function addProduct($clearance, $data) { 
        if(!$this->verifyToken($clearance, $data)) return;
        $this->productController->add();
    }

    public function updateProduct($clearance, $data) { 
        if(!$this->verifyToken($clearance, $data)) return;
        $this->productController->update();
    }

    public function deleteProduct($clearance, $data) { 
        if(!$this->verifyToken($clearance, $data)) return;
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