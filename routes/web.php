<?php

$routes = [
    '/' => 'HomeController@index',
    '/about' => 'AboutController@about',
    '/contact' => 'ContactController@contact',
    '/account' => 'AccountController@index',
    '/404' => 'ErrorController@notFound',
    '/register' => 'UserController@register',
    '/login' => 'UserController@login',
    '/logout' => 'UserController@logout',
    '/verify' => 'UserController@verify',
    '/admin' => 'AdminController@index',
];

return $routes;
