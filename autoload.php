<?php

spl_autoload_register(function ($class) {
    $class = str_replace('App\\', '', $class);
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    $file = __DIR__ . '/app/' . $class . '.php';

    if (file_exists($file)) {
        require_once $file;
    } else {
        throw new Exception("Class {$class} not found!");
    }
});
