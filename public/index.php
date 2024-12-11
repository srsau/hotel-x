<?php

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

require_once __DIR__ . '/../autoload.php';

$routes = require_once __DIR__ . '/../routes/web.php';

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (array_key_exists($requestUri, $routes)) {
    $action = explode('@', $routes[$requestUri]);
    $controller = 'App\\Controllers\\' . $action[0];
    $method = $action[1];

    (new $controller)->$method();
} else {
    http_response_code(404);
    $action = explode('@', $routes['/404']);
    $controller = 'App\\Controllers\\' . $action[0];
    $method = $action[1];
    (new $controller)->$method();
}
