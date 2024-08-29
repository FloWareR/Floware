<?php

namespace App\Middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTMiddleware {

  private $secretKey;

  public function __construct() {
    $this->secretKey = $_ENV['SECRET_KEY'];
  }

  public function verifyToken($data) {
    $headers = apache_request_headers();
    $authHeader = $headers['token'] ?? null;
    if (isset($headers['token'])) {
      $authHeader = $headers['token'];
    try {
      $decoded = JWT::decode($authHeader, new Key($this->secretKey, 'HS256'));
      return true;
    } catch (\Exception $e) {
      return false;
    }
  }
  return false;
}

  public function generateToken($username) {
    $issuedAt = time();
    $expiration = $issuedAt + 3600; 

    $payload = [
        'iat' => $issuedAt,
        'exp' => $expiration,
        'sub' => $username
    ];

    return JWT::encode($payload, $this->secretKey, 'HS256');
}

}