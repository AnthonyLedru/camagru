<?php

require_once __DIR__ . '/../include/autoload.include.php';

function sendRegisterMail($user) {
    $message = "Hello {$user->getFirstName()},\r\n
                Thank you for signing up.\r\n
                To activate your account, please click on the following link:\r\n
                http://$_SERVER[HTTP_HOST]/camagru/script/activateAccount.php?token={$user->getSignupToken()}";
    mail($user->getMail(), "Account confirmation", $message);
}

function areFieldsValid($userTab) {
    $valid = false;
    if (!filter_var($userTab['mail'], FILTER_VALIDATE_EMAIL))
        echo "Invalid mail";
    else if (!preg_match('/^[A-Za-z][A-Za-z0-9]{5,30}$/', $userTab['login']))
        echo "Login must have at least 6 characters and include letters and numbers only.";
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
    else
        $valid = true;
    return $valid;
}

if (isset($_POST['mail']) && $_POST['mail'] !== "" && 
    isset($_POST['login']) && $_POST['login'] !== "" &&
    isset($_POST['password']) && $_POST['password'] !== "" && 
    isset($_POST['passwordConf']) && $_POST['passwordConf'] !== "" && 
    isset($_POST['lastName']) && $_POST['lastName'] !== "" && 
    isset($_POST['firstName']) && $_POST['firstName'] !== "" && 
    isset($_POST['gender']) && $_POST['gender'] !== "") {

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
                    'active' => 0,
                    'bio' => ""
                );
                if (areFieldsValid($userTab)) {
                    if (!User::alreadyExist($userTab['mail'], $userTab['login'])) {
                        User::insert($userTab);
                        $user = User::createFromLogin($userTab['login']);
                        UserPreference::insertDefaultPreference($user->getUserId());
                        sendRegisterMail($user);
                        echo "A confirmation mail to activate your account has been sent to " . $user->getMail();
                    } else
                        echo "An account with this mail or login already exists";
                }
            }
        } else
            echo "The passwords you specified are not the same";
} else
    echo "You must fill all the fields to register";