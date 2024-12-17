<?php

spl_autoload_register(function ($class) {
    // Remove the 'App\' prefix
    $class = str_replace('App\\', '', $class);

    // Replace namespace separators with directory separators
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);

    // Check if we're on XAMPP (localhost) or not
    if (strpos(__DIR__, 'xampp') !== false) {
        // On XAMPP (localhost), we directly look for controllers in the 'Controllers' directory
        $file = __DIR__ . '/app/' . $class . '.php';
    } else {
        // On the server, the path is correct already
        $file = __DIR__ . '/app/Controllers/' . $class . '.php';
    }

    // If the file exists, require it
    if (file_exists($file)) {
        require_once $file;
    } else {
        throw new Exception("Class {$class} not found! (File: {$file})");
    }
});
