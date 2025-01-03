<?php

$routes = [
    '/' => 'HomeController@index',
    '/about' => 'AboutController@about',
    '/contact' => 'ContactController@contact',
    '/account' => 'AccountController@index',
    '/account/cancel' => 'AccountController@cancelBooking', // Add this line
    '/404' => 'ErrorController@notFound',
    '/register' => 'UserController@register',
    '/login' => 'UserController@login',
    '/logout' => 'UserController@logout',
    '/verify' => 'UserController@verify',
    '/admin' => 'AdminController@index',
    '/room' => 'RoomController@details',
    '/room/create' => 'RoomController@create',
    '/room/edit' => 'RoomController@edit',
    '/rooms/available' => 'RoomController@getAvailableRooms',
    '/booking/steps' => 'BookingController@steps',
    '/book' => 'BookingController@book',
    '/api/rooms/available' => 'ApiController@getAvailableRooms',
    '/api/addons' => 'ApiController@getAddons',
    '/api/room' => 'ApiController@getRoomById',
    '/api/book' => 'BookingController@finalizeBooking', // Add route for finalizing booking
];

return $routes;