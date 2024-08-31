<?php

namespace App\Middleware;

use App\Middleware\JWTMiddleware;

class MiddlewareManager {
  private $middlewares = [];

  public function __construct(){
    $this->middlewares = [
      'jwt' => new JWTMiddleware()
    ];
  }

  public function authMiddleware($route){
    $authorized = false;
    if(!isset($route['clearance'])) {
      return true;
    }
    if($this->middlewares['jwt']->runMiddleware($route['clearance'])) {
        $authorized = true;
    }
    return $authorized;
  }

}