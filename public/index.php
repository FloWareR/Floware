<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');  // Allow all origins (useful for development)
header("Access-Control-Allow-Origin: http://floware.me");
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PATCH, DELETE');  // Allow specific methods
header('Access-Control-Allow-Headers: Content-Type, token');  

require_once __DIR__.'/../vendor/autoload.php';
$production = true;

use Dotenv\Dotenv;
use App\Router; 

// Load environment variables
$dotenv = Dotenv::createImmutable(dirname(__DIR__ . '\\'));
$dotenv->load();

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit;
}

// Load routes
$router = new Router($production);
$router->addRoute('POST', 'api', 'APIController', 'login', null);               //API
$router->addRoute('GET', 'api', 'APIController', 'getproduct', 'staff');        //API
$router->addRoute('POST', 'api', 'APIController', 'addproduct', 'admin');       //API
$router->addRoute('PATCH', 'api', 'APIController', 'updateproduct','admin');    //API
$router->addRoute('DELETE', 'api', 'APIController', 'deleteproduct','admin');   //API

$router->addRoute('GET', 'views', 'ViewsController', 'index', null);            //Views


$router->enroute($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);