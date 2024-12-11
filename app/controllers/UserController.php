<?php

namespace App\Controllers;

use App\Database;
use PDO;
use App\Middleware\AuthMiddleware;

class UserController
{
    public function __construct()
    {
        // Apply middleware to protect certain actions
        if ($_SERVER['REQUEST_URI'] === '/logout') {
            AuthMiddleware::handle();
        }
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);
            $stmt->execute();

            header('Location: /login');
        } else {
            $view = __DIR__ . '/../views/register.php';
            require __DIR__ . '/../views/layout.php';
        }
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user'] = $username;
                header('Location: /account');
            } else {
                echo "Invalid credentials";
            }
        } else {
            $view = __DIR__ . '/../views/login.php';
            require __DIR__ . '/../views/layout.php';
        }
    }

    public function logout()
    {
        session_destroy();
        header('Location: /?logged_out=true');
        exit();
    }
}