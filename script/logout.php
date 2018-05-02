<?php

if (session_status() == PHP_SESSION_NONE)
    session_start();

$json = array('message' => "", 'valid' => false);

try {
    if (isset($_SESSION['user'])) {
        session_destroy();
        $json['message'] = "Good bye !";
        $json['valid'] = true;
    } else
        $json['message'] = "You are not connected";
} catch (Exception $e) {
    $json['valid'] = false;
    $json['message'] = $e->getMessage();
}
echo json_encode($json);
