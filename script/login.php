<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

require_once __DIR__ . '/../include/autoload.include.php';

function sendRegisterMail($user) {
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
                <h1>Confirm your account</h1>
                <p>Hello {$user->getLogin()},</p>
                <p>To activate your account, please click <a href="http://$_SERVER[HTTP_HOST]/camagru/script/activateAccount.php?token={$user->getSignupToken()}">here</a></p>
        </body>
    </html>
HTML;
    mail($user->getMail(), "Account confirmation", $message, $headers);
}

if (isset($_POST['login']) && isset($_POST['password'])) {

    $login = htmlspecialchars($_POST['login']);
    $password = hash('whirlpool', $_POST['password']);

    try {
        $user = User::createFromLogin($login);
        if ($user->getPassword() === $password)
            if ($user->getActive()) {
                $_SESSION['user'] = $user->getAll();
                echo "Welcome " . $user->getFirstName();
            } else {
                $user->setSignupToken(bin2hex(random_bytes(50)));
                $user->update();
                sendRegisterMail($user);
                echo "Your account is not active, a new confirmation mail has been sent to {$user->getMail()}";
            }
        else
            echo "Password incorrect";
    } catch (Exception $e) {
        echo $e->getMessage();
    }
} else
    echo "Login failed";