<?php 
require '../vendor/autoload.php'; 
header('Content-Type: application/json');

use Dotenv\Dotenv;
use App\Router;

// Load environment variables
$dotenv = Dotenv::createImmutable(dirname(__DIR__ . '\\'));
$dotenv->load();

// Load routes
$router = new Router();
$router->addRoute('GET', 'api', 'APIController', 'getproduct', 'staff');        //API
$router->addRoute('POST', 'api', 'APIController', 'addproduct', 'admin');       //API
$router->addRoute('PATCH', 'api', 'APIController', 'updateproduct','admin');    //API
$router->addRoute('POST', 'api', 'APIController', 'login','admin' );            //API
$router->addRoute('DELETE', 'api', 'APIController', 'deleteproduct','admin');   //API

$router->addRoute('GET', 'views', 'ViewsController', 'index','');        //Views


$router->enroute($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);