<?php

namespace App\Controllers;

require_once __DIR__ . '/../phpmailer/class.phpmailer.php';
require_once __DIR__ . '/../helpers/emailHelper.php';

use Exception;

class ContactController
{
    public function contact()
    {
        $returnMsg = '';
        if (isset($_SESSION['returnMsg'])) {
            $returnMsg = $_SESSION['returnMsg'];
            unset($_SESSION['returnMsg']);
        }
        $script = '<script src="https://www.google.com/recaptcha/api.js" async defer></script>';

        $view = __DIR__ . '/../views/contact.php';
        require __DIR__ . '/../views/layout.php';
    }

    public function submit()
    {
        $returnMsg = '';

        if (isset($_POST['submit'])) {
            // Form fields validation check
            if (!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['phone'])) {
                // reCAPTCHA checkbox validation
                if (isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
                    // Google reCAPTCHA API secret key
                    $secret_key = getenv('RECAPTCHA_SECRET_KEY');

                    // reCAPTCHA response verification
                    $verify_captcha = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret_key . '&response=' . $_POST['g-recaptcha-response']);

                    // Decode reCAPTCHA response
                    $verify_response = json_decode($verify_captcha);

                    // Check if reCAPTCHA response returns success
                    if ($verify_response->success) {
                        $name = $_POST['name'];
                        $email = $_POST['email'];
                        $phone = $_POST['phone'];
                        $message = $_POST['content'];

                        $mailBody = "User Name: " . $name . "<br>";
                        $mailBody .= "User Email: " . $email . "<br>";
                        $mailBody .= "Phone: " . $phone . "<br>";
                        $mailBody .= "Message: " . $message . "<br>";

                        try {
                            // admin
                            sendEmail('escdev7@gmail.com', 'Contact Form Submission - ' . $name, $mailBody, $email, $email);

                            // sender
                            $confirmationMessage = "Dear $name,<br><br>Thank you for contacting us. We have received your message and will get back to you shortly.<br><br>Regards,<br>Hotel X";
                            sendEmail($email, 'Contact Form Submission Confirmation', $confirmationMessage);

                            $returnMsg = 'Your message has been submitted successfully.';
                        } catch (Exception $e) {
                            $returnMsg = 'Message could not be sent. Mailer Error: ' . $e->getMessage();
                        }
                    } else {
                        $returnMsg = 'Robot verification failed, please try again.';
                    }
                } else {
                    $returnMsg = 'Please click on the reCAPTCHA box.';
                }
            } else {
                $returnMsg = 'Please fill all the required fields.';
            }
        }

        $_SESSION['returnMsg'] = $returnMsg;
        header('Location: /contact');
        exit();
    }
}
?>