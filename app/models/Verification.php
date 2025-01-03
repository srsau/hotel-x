<?php

namespace App\Models;

use app\Database;
use PDO;

class Verification
{
    public static function create($user_id, $verification_code)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("INSERT INTO email_verifications (user_id, verification_code) VALUES (:user_id, :verification_code)");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':verification_code', $verification_code);
        $stmt->execute();
    }

    public static function findByCode($verification_code)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT user_id FROM email_verifications WHERE verification_code = :verification_code");
        $stmt->bindParam(':verification_code', $verification_code);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function deleteByCode($verification_code)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("DELETE FROM email_verifications WHERE verification_code = :verification_code");
        $stmt->bindParam(':verification_code', $verification_code);
        $stmt->execute();
    }
}
