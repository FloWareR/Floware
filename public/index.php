<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');  // Allow all origins (useful for development)
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PATCH, DELETE');  // Allow specific methods
header('Access-Control-Allow-Headers: Content-Type, token'); 

require_once __DIR__.'/../vendor/autoload.php';

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
$router = new Router();


#region Products
  $router->addRoute('GET', 'api', 'APIController', 'getproduct', 'staff');        
  $router->addRoute('POST', 'api', 'APIController', 'addproduct', 'admin');       
  $router->addRoute('PATCH', 'api', 'APIController', 'updateproduct','admin');   
  $router->addRoute('DELETE', 'api', 'APIController', 'deleteproduct','admin');  
#endregion

#region Users
  $router->addRoute('POST', 'api', 'APIController', 'login', null);        
  $router->addRoute('POST', 'api', 'APIController', 'createuser', 'admin');       
#endregion

#region Clients
  $router->addRoute('GET', 'api', 'APIController', 'getcustomer', 'staff');
  $router->addRoute('POST', 'api', 'APIController', 'addcustomer', 'manager');
  $router->addRoute('PATCH', 'api', 'APIController', 'updatecustomer', 'manager');
  $router->addRoute('DELETE', 'api', 'APIController', 'deletecustomer','manager');  
#endregion


$router->addRoute('GET', 'views', 'ViewsController', 'index', null);            //Views


$router->enroute($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);