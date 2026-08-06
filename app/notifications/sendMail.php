<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendMail($user_email, $user_name, $mailContent, $mailSubject, $emailAltBody = null)
{
    include __DIR__ . '/../controller/config.php';
    include __DIR__ . '/mail_templates/mail_style.php';

    if (empty($mail_host) || empty($mail_username)) {
        return 'Mail is not configured. Please set SMTP settings in the installer or config.php.';
    }

    $mail = new PHPMailer(true);
    try {
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->Host = $mail_host;
        $mail->SMTPSecure = $mail_encryption ?: 'ssl';
        $mail->SMTPAutoTLS = true;
        $mail->Username = $mail_username;
        $mail->Password = $mail_password;
        $mail->Port = (int) $mail_port;
        $mail->CharSet = 'UTF-8';

        $mail->setFrom($mail_from ?: $mail_username, $mail_from_name ?: 'Kundendienst');
        $mail->addAddress($user_email, $user_name);

        $mail->isHTML(true);
        $mail->Subject = $mailSubject;
        $mail->Body = $mailContent;
        $mail->AltBody = $emailAltBody;

        $mail->send();
        return true;
    } catch (Exception $e) {
        return 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
    }
}
