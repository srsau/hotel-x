<?php

namespace App\Controllers;

use app\Database;
use PDO;
use app\Middleware\AuthMiddleware;

require_once __DIR__ . '/../phpmailer/class.phpmailer.php';

use PHPMailer;
use Exception;
use phpmailerException;

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
            $email = $_POST['email'];
            $name = $_POST['name'];
            $username = $_POST['username'];
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $verification_code = bin2hex(random_bytes(16));

            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("INSERT INTO users (email, name, username, password) VALUES (:email, :name, :username, :password)");
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);
            $stmt->execute();

            $user_id = $db->lastInsertId();
            $stmt = $db->prepare("INSERT INTO email_verifications (user_id, verification_code) VALUES (:user_id, :verification_code)");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':verification_code', $verification_code);
            $stmt->execute();

            $this->sendVerificationEmail($email, $verification_code);

            echo "Registration successful! Please check your email to verify your account.";
        } else {
            $view = __DIR__ . '/../views/register.php';
            require __DIR__ . '/../views/layout.php';
        }
    }

    private function sendVerificationEmail($email, $verification_code)
    {
        $mail = new PHPMailer(true);

        try {
            $mail->IsSMTP();
            $mail->SMTPDebug  = 0;
            $mail->SMTPAuth   = true;

            $to = 'ctinsergiu@gmail.com';
            $nume = 'Daw Project';
            $message = "Please click the link to verify your email: <a href='http://hotel-x.francecentral.cloudapp.azure.com/verify?code=$verification_code'>Verify Email</a>";
            
            $mail->SMTPSecure = "ssl";
            $mail->Host       = "smtp.gmail.com";
            $mail->Port       = 465;
            $mail->Username   = getenv('EMAIL_USERNAME');              // GMAIL username
            $mail->Password   = getenv('EMAIL_PASSWORD');            // GMAIL password
            $mail->AddReplyTo('escdev7@gmail.com', 'Daw Project');
            $mail->AddAddress($to, $nume);

            $mail->SetFrom('escdev7@gmail.com', 'Daw Project');
            $mail->Subject = 'Test';
            $mail->AltBody = 'To view this post you need a compatible HTML viewer!';
            $mail->MsgHTML($message);
            $mail->Send();
        } catch (phpmailerException $e) {
            echo $e->errorMessage(); //error from PHPMailer
        } catch (Exception $e) {
            echo $e->getMessage(); //error from anything else!
        }

    }

    public function verify()
    {
        if (isset($_GET['code'])) {
            $verification_code = $_GET['code'];

            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("SELECT user_id FROM email_verifications WHERE verification_code = :verification_code");
            $stmt->bindParam(':verification_code', $verification_code);
            $stmt->execute();
            $verification = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($verification) {
                $stmt = $db->prepare("UPDATE users SET verified = 1 WHERE id = :user_id");
                $stmt->bindParam(':user_id', $verification['user_id']);
                $stmt->execute();

                $stmt = $db->prepare("DELETE FROM email_verifications WHERE verification_code = :verification_code");
                $stmt->bindParam(':verification_code', $verification_code);
                $stmt->execute();

                echo "Email verified successfully! You can now log in.";
            } else {
                echo "Invalid verification code.";
            }
        } else {
            echo "Invalid verification code.";
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
                if ($user['verified'] == 1) {
                    $_SESSION['user'] = [
                        'id' => $user['id'],
                        'email' => $user['email'],
                        'name' => $user['name'],
                        'username' => $user['username']
                    ];
                    header('Location: /account');
                } else {
                    echo "Please verify your email before logging in.";
                }
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
