<?php

namespace App\Controllers;

use app\Database; 
use PDO;

class HomeController
{
    public function index()
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT name FROM rooms");
        $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $title = 'Home - Hotel X';
        $view = __DIR__ . '/../views/home.php';
        require __DIR__ . '/../views/layout.php';
    }
}
