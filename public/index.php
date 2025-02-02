<?php
// phpinfo();
// error_reporting(E_ALL);
ini_set('display_errors', 0);

require_once __DIR__ . '/../autoload.php';
require_once __DIR__ . '/../app/helpers/analytics_helper.php';
require_once __DIR__ . '/../app/helpers/csrf_token.php';
use app\helpers\Csrf;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['preferred_currency'])) {
    $_SESSION['preferred_currency'] = 'USD'; 
}

Csrf::generateToken();

// Track analytics
trackAnalytics();

// Load routes
$routes = require_once __DIR__ . '/../routes/web.php';

// Get the current route
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Check if route exists
if (array_key_exists($requestUri, $routes)) {
    $action = explode('@', $routes[$requestUri]);
    $controller = 'app\\controllers\\' . $action[0];
    $method = $action[1];

    // Instantiate controller and call method
    (new $controller)->$method();
} else {
    http_response_code(404);
    $action = explode('@', $routes['/404']);
    $controller = 'app\\controllers\\' . $action[0];
    $method = $action[1];
    (new $controller)->$method();
}
?>
