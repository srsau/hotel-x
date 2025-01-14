<?php

use app\models\Analytics;

function trackAnalytics()
{
    $excludeRoutes = [
        '/favicon.ico',
    ];

    // Get the current route
    $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    $shouldTrack = true;
    foreach ($excludeRoutes as $route) {
        if (strpos($requestUri, $route) === 0) {
            $shouldTrack = false;
            break;
        }
    }

    if ($shouldTrack) {
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $page = $_SERVER['REQUEST_URI'];
        $session_id = session_id();
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $device_type = 'unknown';
        $browser_name = 'unknown';

        if (preg_match('/mobile/i', $user_agent)) {
            $device_type = 'mobile';
        } elseif (preg_match('/tablet/i', $user_agent)) {
            $device_type = 'tablet';
        } elseif (preg_match('/(windows|mac|linux)/i', $user_agent)) {
            $device_type = 'pc';
        }

        if (preg_match('/MSIE|Trident/i', $user_agent)) {
            $browser_name = 'Internet Explorer';
        } elseif (preg_match('/Firefox/i', $user_agent)) {
            $browser_name = 'Firefox';
        } elseif (preg_match('/Chrome/i', $user_agent)) {
            $browser_name = 'Chrome';
        } elseif (preg_match('/Safari/i', $user_agent)) {
            $browser_name = 'Safari';
        } elseif (preg_match('/Opera|OPR/i', $user_agent)) {
            $browser_name = 'Opera';
        }

        Analytics::track($ip_address, $page, $session_id, $user_agent, $device_type, $browser_name);
    }
}
?>
