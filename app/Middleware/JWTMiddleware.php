<?php

namespace App\Middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTMiddleware {

  private $secretKey;

  public function __construct() {
    $this->secretKey = $_ENV['SECRET_KEY'];
  }

  public function verifyToken($clearance, $data) {
    $headers = apache_request_headers();
    $authHeader = $headers['token'] ?? null;
    if ($authHeader && preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
        $token = $matches[1];
        try {
            $decoded = JWT::decode($token, new Key($this->secretKey, 'HS256'));
            $roleHierachy = [
                'admin' => 3,
                'manager' => 2,
                'staff' => 1,
            ];
            $userRole = $decoded->role ?? null;
            if($roleHierachy[$userRole] < $roleHierachy[$clearance]){
              return false;
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
  return false;
}

  public function generateToken($username, $role, $id) {
    $issuedAt = time();
    $expiration = $issuedAt + 3600; 

    $payload = [
        'iat' => $issuedAt,
        'exp' => $expiration,
        'sub' => $username,
        'role' => $role,
        'id' => $id
    ];

    return JWT::encode($payload, $this->secretKey, 'HS256');
}

}