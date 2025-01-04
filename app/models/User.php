<?php

namespace App\Models;

use app\Database;
use PDO;

class User
{
    public static function create($email, $name,  $password)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("INSERT INTO users (email, name, password) VALUES (:email, :name, :password)");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        return $db->lastInsertId();
    }

    public static function findByEmail($email)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function verify($user_id)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("UPDATE users SET verified = 1 WHERE id = :user_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
    }
}
