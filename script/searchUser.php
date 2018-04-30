<?php

require_once '../include/autoload.include.php';

if (session_status() == PHP_SESSION_NONE)
    session_start();

function remove_duplicate($tab) {
    $new_tab = array();
    foreach ($tab as $value) {
        $flag = 0;
        foreach ($new_tab as $value_cmp)
            if ($value->getUserId() === $value_cmp->getUserId())
                $flag = 1;
        if ($flag === 0)
            $new_tab[] = $value;
    }
    return $new_tab;
}

$json = array('message' => "", 'valid' => false, 'usersLogin' => false, 'usersName' => false);

try {
    if (isset($_POST['search'])) {
        if (isset($_POST['wait']))
            usleep(rand(0, 5) * 100000);
        if ($_POST['search'] !== "") {
            $search = trim($_POST['search']);
            $search_splitted = explode(" ", $search);
            $usersLogin = array();
            $usersName = array();
            $json['search'] = $search_splitted;
            foreach ($search_splitted as $search) {
                if ($search === "")
                    break ;
                $usersLoginSearch = User::searchLogin($search);
                $usersNameSearch = User::searchName($search);
                if ($usersLoginSearch || $usersNameSearch) {
                    $json['valid'] = true;
                    if ($usersNameSearch)
                        $usersName = array_merge($usersName, $usersNameSearch);
                    if ($usersLoginSearch)
                        $usersLogin = array_merge($usersLogin, $usersLoginSearch);
                }
            }
            if (count($usersLogin) > 0)
                $usersLogin = remove_duplicate($usersLogin);
            if (count($usersName) > 0)
                $usersName = remove_duplicate($usersName);
            $json['usersLogin']= $usersLogin;
            $json['usersName']= $usersName;
            $json['message'] = "User(s) found";
        } else
            $json['message'] = "Search is empty";
    } else
        $json['message'] = "No image found";
    echo json_encode($json, JSON_PRETTY_PRINT);
} catch (Exception $e) {
    header('HTTP/1.1 400 Bad Request', true, 400);
    echo $e->getMessage();
}