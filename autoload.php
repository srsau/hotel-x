<?php

// Function to load environment variables from the .env file
function loadEnv($filePath) {
    if (!file_exists($filePath)) {
        return false;  // No .env file found
    }

    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue; // Skip comments in the .env file
        }

        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);

        // Set the environment variable
        putenv("$key=$value");
        $_ENV[$key] = $value;
    }

    return true;
}

// Load the .env file if it exists
loadEnv(__DIR__ . '/.env');

// Your existing autoloader code
spl_autoload_register(function ($class) {
    $class = str_replace('app\\', '', $class);
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    $file = __DIR__ . '/app/' . $class . '.php';

    if (file_exists($file)) {
        require_once $file;
    } else {
        throw new Exception("Class {$class} not found! ( $file )");
    }
});
