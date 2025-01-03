<?php

namespace App\Models;

use app\Database;
use PDO;

class Addon
{
    public static function getAllAddons()
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT * FROM addons");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAddonById($id)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM addons WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
