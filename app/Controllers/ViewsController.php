<?php

namespace App\Controllers;

class ViewsController {

    private $path;

    public function __construct() {
        $this->path = dirname(__DIR__) . '/Views';
    }

    public function index() {
        if(file_exists($this->path . '/landingpage.php')) {
            header('Content-Type: text/html');
            echo file_get_contents($this->path . '/landingpage.php');
        } else {
            echo 'Error: File not found';
        }
    }

}