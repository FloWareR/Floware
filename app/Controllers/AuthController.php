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
      $user = $this->userModel->read($data);
      if(!$user) {
          return ["error"];
      }
      if(password_verify($data['password'], $user['password'])) {
        $jwtoken = $this->jwtMiddleware->generateToken($user['username'], $user['role'], $user['id']);
        
        return ['message' => 'User authenticated', 'token' => "Bearer ". $jwtoken];
      } else {

        return ['error' => 'Password incorrect'];
      }
    }

    public function create($data){
      $passwordNoHash = $data['password'];
      $userExists = $this->userModel->read($data);
      if($userExists) {
        return ['error' => 'User already exists'];
      }
      $data['password'] = Helper::encryptPassword($data['password']);
      $data['role'] = 'staff';
      $response = $this->userModel->create($data);
      if(isset($response['error'])) {
        Helper::sendResponse(500, $response);
        return;
      }      
      $data['password'] = $passwordNoHash;
      return $this->authenticate($data);
    }


    public function getById($data){
      $data = $_SESSION['user'];
      $response = $this->userModel->readById($data['id']);
      $response['image'] = Helper::getImage($response); 
      return $response;
    }

    
    public function update($data){
      $data['id'] = $_SESSION['user']['id'];

      if(isset($data['image']) && isset($data['profile_picture'])) {
        $imageSaved = Helper::saveImage($data['image'], $data['profile_picture']);
        $data['profile_picture'] = $imageSaved;
        unset($data['image']);
        if (!isset($imageSaved)) {
          return ['error' => 'Error saving image'];
        }
      }
      if(isset($data['password'])) {
        $data['password'] = Helper::encryptPassword($data['password']);
      }
      $oldImage = $this->userModel->readById($data['id'])['profile_picture'];
      Helper::removeImage($oldImage);
      $response = $this->userModel->update($data);
      if(isset($response['error'])) {
        return $response['error'];
      }
      return $response;
    }



    #region Helper functions  


  
    #endregion
  }