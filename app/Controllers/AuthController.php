<?php

namespace App\Controllers;

use App\Models\User;
use App\Controllers\Helper;
use App\Middleware\JWTMiddleware;


class AuthController {

    private $userModel; 
    private $jwtMiddleware;

    public function __construct() {
        $this->userModel = new User();
        $this->jwtMiddleware = new JWTMiddleware();

    }

    public function authenticate($data) {
      $hashedpassword = password_hash($data['password'], PASSWORD_BCRYPT, $options = ['cost' => 10, ]);
      $user = $this->userModel->getUserData($data);
      if(!$user) {
          Helper::sendResponse(404, ['error' => 'User not found']);
          return;
      }
      if(password_verify($data['password'], $user['password'])) {
        $jwtoken = $this->jwtMiddleware->generateToken($data['username']);
        Helper::sendResponse(200, ['message' => 'User authenticated', 'token' => $jwtoken]);
        return;
      } else {
        Helper::sendResponse(401, ['error' => 'P']);
        return;
      }
      return $user;
    }
}