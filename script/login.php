<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

require_once '../include/autoload.include.php';

if (isset($_POST['login']) && isset($_POST['password'])) {

    $login = htmlspecialchars($_POST['login']);
    $password = hash('whirlpool', $_POST['password']);

    try {
        $user = User::createFromLogin($login);
        if ($user->getPassword() === $password)
            if ($user->getActive()) {
                $_SESSION['id'] = $user->getUserId();
                $_SESSION['login'] = $user->getLogin();
                $_SESSION['firstName'] = $user->getFirstName();
                echo "Welcome " . $user->getFirstName();
            } else
                echo "Please confirm your account by clicking on the link in your mail: <a href='lol'>click here to send a new one</a>";
        else
            echo "Password incorrect";
    } catch (Exception $e) {
        echo $e->getMessage();
    }
} else
    echo "Login failed";