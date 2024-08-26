<?php

namespace App\Controllers;

class IndexController {

    private $path;

    public function __construct() {
        $this->path = dirname(__DIR__) . '\\Views';
    }

    public function index() {
        if(file_exists($this->path . '\\landingpage.php')) {
            echo file_get_contents($this->path . '\\landingpage.php');
        } else {
            echo 'Error: File not found';
        }
    }

}