<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

require_once __DIR__ . '/../include/autoload.include.php';

function sendRegisterMail($user) {
    $headers = 'Content-type: text/html; charset=utf-8' . "\r\n";
    $message = "Hello {$user->getFirstName()},<br>
                Thank you for signing up.<br>
                To activate your account, please click on the following link:<br>
                http://$_SERVER[HTTP_HOST]/camagru/script/activateAccount.php?token={$user->getSignupToken()}";
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