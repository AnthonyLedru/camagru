<?php

require_once __DIR__ . '/../include/autoload.include.php';

$json = array('message' => "", 'valid' => false);
if (isset($_POST['passwordToken']) &&
    isset($_POST['password']) &&
    isset($_POST['passwordConf'])) {
        if ($_POST['password'] === $_POST['passwordConf']) {
            if (strlen($_POST['password']) < 8 ||
                !preg_match("#[0-9]+#", $_POST['password']) || 
                !preg_match("#[a-zA-Z]+#", $_POST['password'])) {
                $json['message'] = "Password must have at least 8 characters and include at least one number and letter";
            } else {
                if (($user = User::createFromPasswordToken($_POST['passwordToken'])) !== false) {
                    $password = hash('whirlpool', $_POST['password']);
                    $user->setPassword($password);
                    $user->setPasswordToken(null);
                    $user->update();
                    $json['valid'] = true;
                    $json['message'] = "Password changed";
                } else
                    $json['message'] = "Token invalid";
            }
        } else {
            $json['message'] = "The passwords you specified are not the same";
        }
} else
    $json['message'] = "Invalid request";

echo json_encode($json);