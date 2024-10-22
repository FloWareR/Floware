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
      $userExists = $this->userModel->readById($data);
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


    public function getById($data){
      $data = $_SESSION['user'];
      $response = $this->userModel->readById($data['id']);
      $response['image'] = $this->getImage($response); 
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
        $data['password'] = $this->encryptPassword($data['password']);
      }
      $response = $this->userModel->update($data);
      if(isset($response['error'])) {
        return $response['error'];
      }
      return $response;
    }



    #region Helper functions  

    private function encryptPassword($password) {
      return password_hash($password, PASSWORD_BCRYPT, options: ['cost' => 10]);
    }


    private function getImage($data) {
      $projectRoot = dirname($_SERVER['SCRIPT_FILENAME']);
      $imageDir = $projectRoot . '/assets/images/' . $data['profile_picture'];
      $default = $projectRoot . '/assets/images/default.jpg';
  
      if (file_exists($imageDir)) {
          $imageData = file_get_contents($imageDir);
          $mimeType = mime_content_type($imageDir);
          $base64Image = 'data:' . $mimeType . ';base64,' . base64_encode($imageData); 
      } else {
          $imageData = file_get_contents($default);
          $mimeType = mime_content_type($default); 
          $base64Image = 'data:' . $mimeType . ';base64,' . base64_encode($imageData); 
      }
  
      return $base64Image;
  }
  
    #endregion
  }