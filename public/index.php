<?php 
require '../vendor/autoload.php'; 
header('Content-Type: application/json');

use Dotenv\Dotenv;
use App\Router;
use App\Middleware\JWTMiddleware;
// Load environment variables
$dotenv = Dotenv::createImmutable(dirname(__DIR__ . '\\'));
$dotenv->load();

// Load routes
$router = new Router();
$router->addRoute('GET', 'api', 'APIController', 'getproduct');
$router->addRoute('POST', 'api', 'APIController', 'addproduct');
$router->addRoute('PATCH', 'api', 'APIController', 'updateproduct');
$router->addRoute('POST', 'api', 'APIController', 'login');

$router->addRoute('GET', 'views', 'ViewsController', 'index');


$router->enroute($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);