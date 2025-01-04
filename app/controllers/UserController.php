<?php

namespace App\Controllers;

use app\models\User;
use app\models\Verification;
use app\middleware\AuthMiddleware;

require_once __DIR__ . '/../phpmailer/class.phpmailer.php';

use PHPMailer;
use Exception;
use phpmailerException;

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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $name = $_POST['name'];
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $verification_code = bin2hex(random_bytes(16));

            $user_id = User::create($email, $name, $password);

            Verification::create($user_id, $verification_code);

            $this->sendVerificationEmail($email, $verification_code);

            $message = "Registration successful! Please check your email to verify your account.";
            $title = 'Registration Successful';
            $view = __DIR__ . '/../views/message.php';
            require __DIR__ . '/../views/layout.php';
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

            $base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
            $verification_link = $base_url . "/verify?code=$verification_code";
            $message = "Please click the link to verify your email: <a href='$verification_link'>Verify Email</a>";

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
        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $user = User::findByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                if ($user['verified'] == 1) {
                    $_SESSION['user'] = [
                        'id' => $user['id'],
                        'email' => $user['email'],
                        'name' => $user['name'],
                        'role' => $user['role']
                    ];
                    echo '<script>
                    const currentStep = localStorage.getItem("currentStep");
                    if (currentStep) {
                        localStorage.removeItem("currentStep");
                        window.location.href = "/book?step=" + currentStep;
                    } else {
                        window.location.href = "/book";
                    }
                </script>';
                exit();
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
}
