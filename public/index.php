<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Simulate login state for testing
if (!isset($_SESSION['user']) && !isset($_GET['logged_out'])) {
    $_SESSION['user'] = 'testuser'; 
}

echo "Session ID: " . session_id();
echo "<br>";
echo "User: " . (isset($_SESSION['user']) ? $_SESSION['user'] : 'No user logged in');

// Include autoload
require_once __DIR__ . '/../autoload.php';

// Load routes
$routes = require_once __DIR__ . '/../routes/web.php';
print_r($routes); // Debugging: Check if routes are loaded correctly

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
echo "Request URI: " . $requestUri . "<br>"; // Debugging: Check the URI

// Check if route exists
if (array_key_exists($requestUri, $routes)) {
    $action = explode('@', $routes[$requestUri]);
    $controller = 'app\\controllers\\' . $action[0];
    $method = $action[1];

    echo "Controller: $controller, Method: $method<br>"; // Debugging: Check controller and method

    // Instantiate controller and call method
    (new $controller)->$method();
} else {
    http_response_code(404);
    $action = explode('@', $routes['/404']);
    $controller = 'app\\controllers\\' . $action[0];
    $method = $action[1];
    (new $controller)->$method();
}
