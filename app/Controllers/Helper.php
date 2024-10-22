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
        if($_SERVER['REQUEST_METHOD'] === 'GET' || $_SERVER['REQUEST_METHOD'] === 'DELETE') {
            return $_GET;
        }
        if($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'PATCH') {
            $inputData = file_get_contents('php://input');
            return json_decode($inputData, true);        }
    }

    public static function saveImage($image, $originalFilename) {
        $projectRoot = dirname($_SERVER['SCRIPT_FILENAME']);
        $imageDir = $projectRoot . '/assets/images/';
        
        if (!is_dir($imageDir)) {
            mkdir($imageDir, 0755, true); 
        }
        
        $uniqueId = uniqid('', true);
        $extension = pathinfo($originalFilename, PATHINFO_EXTENSION); 
        $uniqueFilename = pathinfo($originalFilename, PATHINFO_FILENAME) . '_' . $uniqueId . '.' . $extension; 
        
        $filePath = $imageDir . $uniqueFilename;
        
        $image_parts = explode(";base64,", $image);
        $image_base64 = base64_decode($image_parts[1]);
        
        file_put_contents($filePath, $image_base64);
        
        return $uniqueFilename; 
    }
    
    public static function getImage($data) {
        if(!isset($data['profile_picture'])) {
          $data['profile_picture'] = 'default.jpg';
        }
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

    public static function removeImage($filename) {
        $projectRoot = dirname($_SERVER['SCRIPT_FILENAME']);
        $imageDir = $projectRoot . '/assets/images/' . $filename;
        if($filename === 'default.jpg') {
            return;
        }
        if (file_exists($imageDir)) {
            unlink($imageDir);
        }   
    }

    
    public static function encryptPassword($password) {
        return password_hash($password, PASSWORD_BCRYPT, options: ['cost' => 10]);
      }
  
}