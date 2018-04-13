<?php

if (session_status() == PHP_SESSION_NONE)
    session_start();

require_once __DIR__ . '/../include/autoload.include.php';

function areFieldsValid($userTab) {
    $valid = false;
    if (!filter_var($userTab['mail'], FILTER_VALIDATE_EMAIL))
        echo "Invalid mail";
    else if (strlen($userTab['password']) < 8 ||
                !preg_match("#[0-9]+#", $userTab['password']) || 
                !preg_match("#[a-zA-Z]+#", $userTab['password'])) {
        echo "Password must have at least 8 characters and include at least one number and letter";
    } else if (!preg_match("/[a-zA-z]+([ '-][a-zA-Z]+)*/", $userTab['lastName']))
        echo "Last name invalid";
    else if (!preg_match("/[a-zA-z]+([ '-][a-zA-Z]+)*/", $userTab['firstName']))
        echo "First name invalid";
    else if ($userTab['gender'] !== "Male" && $userTab['gender'] !== "Female")
        echo "Gender invalid";
    else if (strlen($userTab['bio']) > 300)
        echo "Bio can't have more than 300 characters";
    else
        $valid = true;
    return $valid;
}

if (isset($_SESSION['user'])) {
    if (isset($_POST['gender']) && $_POST['gender'] !== "" &&
        isset($_POST['mail']) && $_POST['mail'] !== "" &&
        isset($_POST['firstName']) && $_POST['firstName'] !== "" &&
        isset($_POST['lastName']) && $_POST['lastName'] !== "") {

        if (isset($_POST['password']) && $_POST['password'] !== "") {
            try {
                $user = User::createFromId($_SESSION['user']['userId']);
            }
            catch (Exception $e) {
                echo "An error occurred: " . $e->getMessage();
                return ;
            }
            if ($user->getPassword() === hash('whirlpool', $_POST['password'])) {
                if ((isset($_POST['newPass1']) && $_POST['newPass1'] !== "") || 
                    (isset($_POST['newPass2']) && $_POST['newPass2'] !== "")) {
                    if ($_POST['newPass1'] === $_POST['newPass2']) {
                        $userTab = array(
                            'mail' => htmlspecialchars($_POST['mail']),
                            'password' => $_POST['newPass1'],
                            'lastName' => htmlspecialchars($_POST['lastName']),
                            'firstName' => htmlspecialchars($_POST['firstName']),
                            'gender' => htmlspecialchars($_POST['gender']),
                            'bio' => htmlspecialchars($_POST['bio']),
                            'userId' => $user->getUserId()
                        );
                    } else {
                        echo "Passwords are not identical";
                        exit();
                    }
                } else {
                    $userTab = array(
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
                    try {
                        User::UpdateUser($userTab);
                        $user = User::createFromId($_SESSION['user']['userId']);
                        unset($_SESSION['user']);
                        $_SESSION['user'] = $user->getAll();
                        echo "Profile updated !";
                    }
                    catch (Exception $e) {
                        echo "An error occurred " . $e->getMessage();
                    }
                }
            } else
                echo "Password incorrect";
        } else
            echo "You must specify you password to update your profile";
    } else
        echo "A required field is empty";
} else
    echo "You are not connected";