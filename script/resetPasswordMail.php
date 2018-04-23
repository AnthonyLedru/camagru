<?php

require_once __DIR__ . '/../include/autoload.include.php';

function sendResetPasswordMail($user) {
    $headers = 'Content-type: text/html; charset=utf-8';
    $message = <<<HTML
    <html>
        <head>
            <style>
                p, h1, a {
                    text-align: center
                }
            </style>
        </head>
        <body>
                <h1>Change your password</h1>
                <p>Hello {$user->getLogin()},</p>
                <p>To reset your password, please click <a href="http://$_SERVER[HTTP_HOST]/camagru/resetPassword.php?token={$user->getPasswordToken()}">here</a></p>
        </body>
    </html>
HTML;
    mail($user->getMail(), "Change your password", $message, $headers);
}

$json = array('message' => "");

if (isset($_POST['mail'])) {
    if (($user = User::createFromMail($_POST['mail'])) !== false) {
        if ($user->getActive() === "1") {
            $user->setPasswordToken(bin2hex(random_bytes(50)));
            $user->update();
            sendResetPasswordMail($user);
            $json['message'] = "A mail to reset your password has been sent";
        } else
            $json['message'] = "Your account is not active";
    } else
        $json['message'] = "Invalid mail";
}

echo json_encode($json);