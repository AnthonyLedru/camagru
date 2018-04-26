<?php

if (session_status() == PHP_SESSION_NONE)
    session_start();

$json = array('message' => "", 'valid' => false);

if (isset($_SESSION['user'])) {
    session_destroy();
    $json['message'] = "Good bye !";
    $json['valid'] = true;
} else
    $json['message'] = "You are not connected";

echo json_encode($json);
