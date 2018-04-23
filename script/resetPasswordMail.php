<?php

require_once __DIR__ . '/../include/autoload.include.php';

function sendResetPasswordMail($user) {
    $headers = 'Content-type: text/html; charset=utf-8' . "\r\n";
    $message = "Hello {$user->getFirstName()},<br>
                To reset your password, please click on the following link:<br>
                http://$_SERVER[HTTP_HOST]/camagru/resetPassword.php?token={$user->getPasswordToken()}";
    mail($user->getMail(), "Reset Password", $message, $headers);
}

$json = array('message' => "");

if (isset($_POST['mail'])) {
    if (($user = User::createFromMail($_POST['mail'])) !== false) {
        $user->setPasswordToken(bin2hex(random_bytes(50)));
        $user->update();
        sendResetPasswordMail($user);
        $json['message'] = "A mail to reset your password has been sent";
    } else
        $json['message'] = "Invalid mail";
}

echo json_encode($json);