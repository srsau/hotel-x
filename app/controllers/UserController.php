<?php
namespace App\Controllers;

require_once __DIR__ . '/../helpers/getCurrencies.php';
require_once __DIR__ . '/../helpers/csrf_token.php';
require_once __DIR__ . '/../helpers/emailHelper.php';


use app\models\User;
use app\models\Verification;
use app\middleware\AuthMiddleware;
use app\helpers\Csrf;

class UserController
{
    public function __construct()
    {
        if ($_SERVER['REQUEST_URI'] === '/logout') {
            AuthMiddleware::handle();
        }

    }

    public function register()
    {
        if (isset($_SESSION['user'])) {
            header('Location: /');
            exit();
        }


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $name = $_POST['name'];
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $preferred_currency = $_POST['preferred_currency'];
            $verification_code = bin2hex(random_bytes(16));

            $user_id = User::create($email, $name, $password, $preferred_currency);

            Verification::create($user_id, $verification_code);

            $this->sendVerificationEmail($email, $verification_code);

            $message = "Registration successful! Please check your email to verify your account.";
            $title = 'Registration Successful';
            $view = __DIR__ . '/../views/message.php';
            require __DIR__ . '/../views/layout.php';
        } else {
            $currencies = getCurrencies();
            $view = __DIR__ . '/../views/register.php';
            require __DIR__ . '/../views/layout.php';
        }
    }

    private function sendVerificationEmail($email, $verification_code)
    {
        $base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
        $verification_link = $base_url . "/verify?code=$verification_code";
        $message = "Welcome to Hotel X! To get started, please confirm your email address by clicking the link below:<br><br> 
        <a href='$verification_link'>Verify My Email</a><br><br> 
        If you didn’t sign up for Hotel X, you can safely ignore this email.";

        sendEmail($email, 'Hotel X - Verification Code', $message);
    }

    public function verify()
    {
        $message = "Invalid verification code.";
        if (isset($_GET['code'])) {
            $verification_code = $_GET['code'];

            $verification = Verification::findByCode($verification_code);

            if ($verification) {
                User::verify($verification['user_id']);
                Verification::deleteByCode($verification_code);

                $message = "Email verified successfully! You can now log in.";
            }
        }
        $title = 'Verify Account';
        $view = __DIR__ . '/../views/verify.php';
        require __DIR__ . '/../views/layout.php';
    }

    public function login()
    {
        if (isset($_SESSION['user'])) {
            header('Location: /');
            exit();
        }

      

        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $csrfToken = $_POST['csrf_token'] ?? null;

            if (!Csrf::validateToken($csrfToken)) {
                $error = "Invalid csrf token.";
                $title = 'Login Failed';
                $view = __DIR__ . '/../views/login.php';
                require __DIR__ . '/../views/layout.php';
                return;
            }

            $email = $_POST['email'];
            $password = $_POST['password'];

            $user = User::findByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                if ($user['verified'] == 1) {
                    // session_regenerate_id(true);
                    $_SESSION['user'] = [
                        'id' => $user['id'],
                        'email' => $user['email'],
                        'name' => $user['name'],
                        'preferred_currency' => $user['preferred_currency'],
                        'role' => $user['role']
                    ];
                    $_SESSION['preferred_currency'] = $user['preferred_currency'];
                    Csrf::regenerateToken();
                    if (isset($_SESSION['booking']['step'])) {
                        header('Location: /book?step=' . $_SESSION['booking']['step']);
                        exit();
                    } else {
                        header('Location: /');
                        exit();
                    }
                } else {
                    $error = "Please verify your email before logging in.";
                }
            } else {
                $error = "Invalid credentials";
            }
        }
        $title = 'Login';
        $view = __DIR__ . '/../views/login.php';
        require __DIR__ . '/../views/layout.php';
    }

    public function logout()
    {
        session_destroy();
        header('Location: /?logged_out=true');
        exit();
    }

    public function changeCurrency()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['currency']) && isset($data['user_id'])) {
            User::updatePreferredCurrency($data['user_id'], $data['currency']);
            $_SESSION['preferred_currency'] = $data['currency'];
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error']);
        }
    }
}
