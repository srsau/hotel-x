<?php

namespace App\Models;

use app\Database;
use PDO;

class Analytics
{
    public static function track($ip_address, $page, $session_id, $user_agent, $device_type, $browser_name)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM analytics WHERE ip_address = :ip_address AND page = :page AND session_id = :session_id");
        $stmt->bindParam(':ip_address', $ip_address);
        $stmt->bindParam(':page', $page);
        $stmt->bindParam(':session_id', $session_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $stmt = $db->prepare("UPDATE analytics SET load_count = load_count + 1 WHERE id = :id");
            $stmt->bindParam(':id', $result['id']);
            $stmt->execute();
        } else {
            $stmt = $db->prepare("INSERT INTO analytics (ip_address, page, session_id, user_agent, device_type, browser_name) VALUES (:ip_address, :page, :session_id, :user_agent, :device_type, :browser_name)");
            $stmt->bindParam(':ip_address', $ip_address);
            $stmt->bindParam(':page', $page);
            $stmt->bindParam(':session_id', $session_id);
            $stmt->bindParam(':user_agent', $user_agent);
            $stmt->bindParam(':device_type', $device_type);
            $stmt->bindParam(':browser_name', $browser_name);
            $stmt->execute();
        }
    }

    public static function getAll()
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT * FROM analytics");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getUniqueIps()
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT DISTINCT ip_address FROM analytics");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getUniquePages()
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT page, COUNT(DISTINCT session_id) AS unique_access_count FROM analytics GROUP BY page ORDER BY unique_access_count DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getUniqueAccessCount()
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT COUNT(DISTINCT session_id) AS unique_access_count FROM analytics");
        return $stmt->fetch(PDO::FETCH_ASSOC)['unique_access_count'];
    }

    public static function getDeviceTypeDistribution()
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT device_type, COUNT(DISTINCT session_id) AS count FROM analytics GROUP BY device_type");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getBrowserDistribution()
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT browser_name, COUNT(DISTINCT session_id) AS count FROM analytics GROUP BY browser_name");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
