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
$router->addRoute('GET', 'api', 'APIController', 'getproduct');       //API
$router->addRoute('POST', 'api', 'APIController', 'addproduct');      //API
$router->addRoute('PATCH', 'api', 'APIController', 'updateproduct');  //API
$router->addRoute('POST', 'api', 'APIController', 'login');           //API
$router->addRoute('DELETE', 'api', 'APIController', 'deleteproduct');           //API

$router->addRoute('GET', 'views', 'ViewsController', 'index');        //Views


$router->enroute($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);