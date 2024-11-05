<?php

namespace App\Controllers;

use App\Models\User;
use App\Controllers\Helper;
use App\Middleware\JWTMiddleware;
use App\Controllers\OneTimeCodeController;


class AuthController {

    private $userModel; 
    private $OneTimeCodeController;
    private $jwtMiddleware;

    public function __construct() {
        $this->userModel = new User();
        $this->jwtMiddleware = new JWTMiddleware();
        $this->OneTimeCodeController = new OneTimeCodeController();
    }

    public function authenticate($data) {
      $user = $this->userModel->read($data);
      if(!$user) {
          return ['error' => "User does not exist"];
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
      $oneTimeCode = $this->OneTimeCodeController->get($data);
      if(!$oneTimeCode || $oneTimeCode['used'] == 1) {
        return ['error' => 'Invalid one time code'];
      }

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
      $this->OneTimeCodeController->update(['code' => $data['code'], 'used' => 1, 'id' => $oneTimeCode['id']]);
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
        $oldImage = $this->userModel->readById($data['id'])['profile_picture'];
        Helper::removeImage($oldImage);

        if (!isset($imageSaved)) {
          return ['error' => 'Error saving image'];
        }
      }
      if(isset($data['password'])) {
        $data['password'] = Helper::encryptPassword($data['password']);
      }
      $response = $this->userModel->update($data);
      if(isset($response['error'])) {
        return $response['error'];
      }
      return $response;
    }

    public function subscribe($data){
      $data['id'] = $_SESSION['user']['id'];
      $response = $this->userModel->subscribe($data);
      $email = $this->userModel->readById($data['id']);
      if(isset($email['error'])) {
        return $response['error'];
      }
      if(isset($response['error'])) {
        return $response['error'];
      }
      $response = Helper::sendEmail($email['email'], 'Subscription', 'You have successfully subscribed to our newsletter, stay tuned for more updates!');
      return $response;
    }
  }