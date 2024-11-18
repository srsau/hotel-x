<?php

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
    echo "404 Not Found";
}
