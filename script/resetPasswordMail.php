<?php

require_once __DIR__ . '/../include/autoload.include.php';

function sendResetPasswordMail($user) {
    $message = "Hello {$user->getFirstName()},\r\n
                To reset your password, please click on the following link:\r\n
                http://$_SERVER[HTTP_HOST]/camagru/resetPassword.php?token={$user->getPasswordToken()}";
    mail($user->getMail(), "Reset Password", $message);
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