<?php

$routes = [
    '/' => 'HomeController@index',
    '/contact' => 'ContactController@contact',
    '/contact/submit' => 'ContactController@submit',
    '/account' => 'AccountController@index',
    '/account/cancel' => 'AccountController@cancelBooking',
    '/account/pdf' => 'PdfController@generateReceipt',
    '/account/change_currency' => 'UserController@changeCurrency',
    '/404' => 'ErrorController@notFound',
    '/register' => 'UserController@register',
    '/login' => 'UserController@login',
    '/logout' => 'UserController@logout',
    '/verify' => 'UserController@verify',
    '/admin' => 'AdminController@index',
    '/admin/bookings' => 'AdminController@bookings',
    '/admin/analytics' => 'AdminController@analytics',
    '/admin/cancel' => 'AdminController@cancelBooking',
    '/room' => 'RoomController@details',
    '/room/create' => 'RoomController@create',
    '/room/edit' => 'RoomController@edit',
    '/rooms/available' => 'RoomController@getAvailableRooms',
    '/book' => 'BookingController@book',
    '/api/rooms/available' => 'ApiController@getAvailableRooms',
    '/api/addons' => 'ApiController@getAddons',
    '/api/room' => 'ApiController@getRoomById',
    '/api/book' => 'BookingController@finalizeBooking',
    '/api/convertAmount' => 'ApiController@convertAmount',
    '/initializeBooking' => 'BookingController@initializeBooking',
];

return $routes;