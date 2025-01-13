<?php


require_once __DIR__ . '/../phpmailer/class.phpmailer.php';
require_once __DIR__ . '/../helpers/emailHelper.php';


function sendEmail($to, $subject, $body, $replyTo = null, $bcc = null)
{
    $mail = new PHPMailer(true);

    try {
        $mail->IsSMTP();
        $mail->SMTPDebug  = 0;
        $mail->SMTPAuth   = true;
        $mail->SMTPSecure = "ssl";
        $mail->Host       = "smtp.gmail.com";
        $mail->Port       = 465;
        $mail->Username   = getenv('EMAIL_USERNAME');
        $mail->Password   = getenv('EMAIL_PASSWORD');
        $mail->SetFrom('no-reply@hotelx.com', 'Hotel X');
        $mail->AddAddress($to);
        if ($replyTo) {
            $mail->AddReplyTo($replyTo);
        }
        if ($bcc) {
            $mail->addCustomHeader("BCC: " . $bcc);
        }
        $mail->Subject = $subject;
        $mail->AltBody = 'To view this email, please use an HTML compatible email viewer!';
        $mail->MsgHTML($body);
        $mail->Send();
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}
?>
