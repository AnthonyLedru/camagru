<?php

require_once '../include/autoload.include.php';


function areFieldsValid($userTab) {
    $valid = false;
    if (!filter_var($userTab['mail'], FILTER_VALIDATE_EMAIL))
        echo "Invalid mail";
    else if (!preg_match('/^[A-Za-z][A-Za-z0-9]{5,30}$/', $userTab['login']))
        echo "Login length must be at least 6 characters and include letters and numbers only.";
    else if (strlen($userTab['password']) < 8 ||
                !preg_match("#[0-9]+#", $userTab['password']) || 
                !preg_match("#[a-zA-Z]+#", $userTab['password'])) {
        echo "Password length must be at least 8 characters, include at least one number and letter";
    } else if (!preg_match("/[a-zA-z]+([ '-][a-zA-Z]+)*/", $userTab['lastName']))
        echo "Last name invalid";
    else if (!preg_match("/[a-zA-z]+([ '-][a-zA-Z]+)*/", $userTab['firstName']))
        echo "First name invalid.";
    else if ($userTab['gender'] !== "Male" && $userTab['gender'] !== "Female")
        echo "Gender invalid.";
    else
        $valid = true;
    return $valid;
}

if (isset($_POST['mail']) &&
    isset($_POST['login']) &&
    isset($_POST['password']) &&
    isset($_POST['passwordConf']) &&
    isset($_POST['lastName']) &&
    isset($_POST['firstName']) && 
    isset($_POST['gender'])) {

        if ($_POST['password'] === $_POST['passwordConf']) {
            if (areFieldsValid(array(
                            'mail' => $_POST['mail'], 'login' => $_POST['login'],
                            'password' => $_POST['password'], 'lastName' => $_POST['lastName'],
                            'firstName' => $_POST['firstName'], 'gender' => $_POST['gender']))
                ) {
                $userTab = array(
                    'mail' => htmlspecialchars($_POST['mail']),
                    'login' => htmlspecialchars($_POST['login']),
                    'password' => hash('whirlpool', $_POST['password']),
                    'lastName' => htmlspecialchars($_POST['lastName']),
                    'firstName' => htmlspecialchars($_POST['firstName']),
                    'gender' => htmlspecialchars($_POST['gender']),
                    'signupToken' => bin2hex(random_bytes(50)),
                    'active' => 0
                );

                if (areFieldsValid($userTab)) {
                    if (!User::alreadyExist($userTab['mail'], $userTab['login'])) {
                        User::insertUser($userTab);
                        echo "A confirmation mail has been sent to " . $userTab['mail'] . " to activate your account.";
                    } else
                        echo "An account with tihs mail or login already exists.";
                }
            }
        } else
            echo "Password you specified are not the same.";
} else {
    echo "You must fill all the fields to register.";
}