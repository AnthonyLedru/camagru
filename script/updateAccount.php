<?php

if (session_status() == PHP_SESSION_NONE)
    session_start();

require_once __DIR__ . '/../include/autoload.include.php';

$json = array('message' => "", 'login' => "", 'valid' => false);

function areFieldsValid($userTab) {
    global $json;
    $valid = false;
    if (!filter_var($userTab['mail'], FILTER_VALIDATE_EMAIL))
        $json['message'] = "Invalid mail";
    else if (!preg_match('/^[A-Za-z][A-Za-z0-9]{5,30}$/', $userTab['login']))
        $json['message'] = "Login must have at least 6 characters and include letters and numbers only";
    else if (strlen($userTab['password']) < 8 ||
                !preg_match("#[0-9]+#", $userTab['password']) || 
                !preg_match("#[a-zA-Z]+#", $userTab['password'])) {
        $json['message'] = "Password must have at least 8 characters and include at least one number and letter";
    } else if (!preg_match("/[a-zA-z]+([ '-][a-zA-Z]+)*/", $userTab['lastName']))
        $json['message'] =  "Last name invalid";
    else if (!preg_match("/[a-zA-z]+([ '-][a-zA-Z]+)*/", $userTab['firstName']))
        $json['message'] =  "First name invalid";
    else if ($userTab['gender'] !== "Male" && $userTab['gender'] !== "Female")
        $json['message'] =  "Gender invalid";
    else if (strlen($userTab['bio']) > 300)
        $json['message'] =  "Bio can't have more than 300 characters";
    else
        $valid = true;
    return $valid;
}

if (isset($_SESSION['user'])) {
    if (isset($_POST['gender']) && $_POST['gender'] !== "" &&
        isset($_POST['mail']) && $_POST['mail'] !== "" &&
        isset($_POST['firstName']) && $_POST['firstName'] !== "" &&
        isset($_POST['lastName']) && $_POST['lastName'] !== "" &&
        isset($_POST['login']) && $_POST['login'] !== "") {

        if (isset($_POST['password']) && $_POST['password'] !== "") {
            try {
                $user = User::createFromId($_SESSION['user']['userId']);
            }
            catch (Exception $e) {
                $json['message'] = "An error occurred: " . $e->getMessage();
                return ;
            }
            if ($user->getPassword() === hash('whirlpool', $_POST['password'])) {
                if ((isset($_POST['newPass1']) && $_POST['newPass1'] !== "") || 
                    (isset($_POST['newPass2']) && $_POST['newPass2'] !== "")) {
                    if ($_POST['newPass1'] === $_POST['newPass2']) {
                        $userTab = array(
                            'login' => htmlspecialchars($_POST['login']),
                            'mail' => htmlspecialchars($_POST['mail']),
                            'password' => $_POST['newPass1'],
                            'lastName' => htmlspecialchars($_POST['lastName']),
                            'firstName' => htmlspecialchars($_POST['firstName']),
                            'gender' => htmlspecialchars($_POST['gender']),
                            'bio' => htmlspecialchars($_POST['bio']),
                            'userId' => $user->getUserId()
                        );
                    } else {
                        $json['message'] = "Passwords are not identical";
                        echo json_encode($json);
                        exit();
                    }
                } else {
                    $userTab = array(
                        'login' => htmlspecialchars($_POST['login']),
                        'mail' => htmlspecialchars($_POST['mail']),
                        'password' => $_POST['password'],
                        'lastName' => htmlspecialchars($_POST['lastName']),
                        'firstName' => htmlspecialchars($_POST['firstName']),
                        'gender' => htmlspecialchars($_POST['gender']),
                        'bio' => htmlspecialchars($_POST['bio']),
                        'userId' => $user->getUserId()
                    );
                }
                if (areFieldsValid($userTab)) {
                    $userTab['password'] = hash('whirlpool', $userTab['password']);
                    if (!User::loginAlreadyTaken($userTab['login']) || $user->getLogin() == $userTab['login']) {
                        if (!User::mailAlreadyTaken($userTab['mail']) || $user->getMail() == $userTab['mail']) {
                            try {
                                User::updateFromTab($userTab);
                                $user = User::createFromId($_SESSION['user']['userId']);
                                unset($_SESSION['user']);
                                $_SESSION['user'] = $user->getAll();
                                $json['valid'] = true;
                                $json['login'] = $user->getLogin();
                                $json['message'] = "Account updated !";
                            }
                            catch (Exception $e) {
                                $json['message'] = "An error occurred " . $e->getMessage();
                            }
                        } else
                            $json['message'] = "Mail already taken";
                    } else
                        $json['message'] = "Login already taken";
                }
            } else
                $json['message'] = "Password incorrect";
        } else
            $json['message'] = "You must specify you password to update your account";
    } else
        $json['message'] = "A required field is empty";
} else
    $json['message'] = "You are not connected";

echo json_encode($json);