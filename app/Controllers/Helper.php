<?php

namespace App\Controllers;


use App\Middleware\JWTMiddleware;

class Helper{

  public static function getParamType($value) {
    if (is_int($value)) {
        return \PDO::PARAM_INT;
    } elseif (is_bool($value)) {
        return \PDO::PARAM_BOOL;
    } elseif (is_null($value)) {
        return \PDO::PARAM_NULL;
    } else {
        return \PDO::PARAM_STR;
    }
    }

    public static function sendResponse($statusCode, $data) {
        http_response_code($statusCode);
        echo json_encode($data);
    }

    public static function getData() {
        if($_SERVER['REQUEST_METHOD'] === 'GET') {
            return $_GET;
        }
        if($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'PATCH') {
            $inputData = file_get_contents('php://input');
            return json_decode($inputData, true);        }
    }


}