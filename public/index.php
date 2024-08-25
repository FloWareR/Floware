<?php 
require '../vendor/autoload.php'; 

use Dotenv\Dotenv;
use App\Router;

// Load environment variables
$dotenv = Dotenv::createImmutable(dirname(__DIR__ . '\\'));
$dotenv->load();

// Load routes
$router = new Router();
$router->addRoute('GET', 'products', 'ProductsController', 'getproduct');
$router->addRoute('POST', 'products', 'ProductsController', 'addproduct');
$router->addRoute('PATCH', 'products', 'ProductsController', 'updateproduct');


$router->enroute($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);