<?php

if (session_status() == PHP_SESSION_NONE)
    session_start();

require_once __DIR__ . '/../include/autoload.include.php';

try {
    if (isset($_GET['token'])) {
        if (strlen($_GET['token']) > 10 && ($user = User::createFromSignupToken($_GET['token'])) !== false) {
                $user->setActive(1);
                $user->setSignupToken(null);
                $user->update();
                $_SESSION['user'] = $user->getAll();
                header ("Location:../index.php");
        } else
            echo "Invalid Token";
    } else
        header ("Location:../index.php");
} catch (Exception $e) {
    echo $e->getMessage();
}