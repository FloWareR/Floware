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
      $user = $this->userModel->get($data);
      if(!$user) {
          Helper::sendResponse(404, ['error' => 'User not found']);
          return;
      }
      if(password_verify($data['password'], $user['password'])) {
        $jwtoken = $this->jwtMiddleware->generateToken($user['username'], $user['role'], $user['id']);
        Helper::sendResponse(200, ['message' => 'User authenticated', 'token' => "Bearer ". $jwtoken]);
        return;
      } else {
        Helper::sendResponse(401, ['error' => 'Password incorrect']);
        return;
      }
      return $user;
    }

    public function create($data){
      $userExists = $this->userModel->get($data);
      if($userExists) {
        Helper::sendResponse(409, ['error' => 'User already exists']);
        return;
      }
      $data['password'] = $this->encryptPassword($data['password']);
      $response = $this->userModel->create($data);
      if(isset($response['error'])) {
        Helper::sendResponse(500, $response);
        return;
      }
      Helper::sendResponse(201, $response);
    }


    private function encryptPassword($password) {
      return password_hash($password, PASSWORD_BCRYPT, options: ['cost' => 10]);
    }
}