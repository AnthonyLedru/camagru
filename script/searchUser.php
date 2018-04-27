<?php

require_once '../include/autoload.include.php';

if (session_status() == PHP_SESSION_NONE)
    session_start();

$json = array('message' => "", 'valid' => false, 'usersLogin' => false, 'usersFirstName' => false, 'usersLastName' => false);

if (isset($_POST['search'])) {
    if ($_POST['search'] !== "") {
        $usersLogin = User::searchLogin($_POST['search']);
        $usersFirstName = User::searchFirstName($_POST['search']);
        $usersLastName = User::searchFirstName($_POST['search']);
        if ($usersLogin || $usersFirstName || $usersLastName) {
            $json['valid'] = true;
            $json['usersLogin']= $usersLogin;
            $json['usersFirstName']= $usersFirstName;
            $json['usersLastName']= $usersLastName;
            $json['message'] = "User(s) found";
        } else
            $json['message'] = "No user found";
    } else
        $json['message'] = "Search is empty";
} else
    $json['message'] = "No image found";

echo json_encode($json, JSON_PRETTY_PRINT);