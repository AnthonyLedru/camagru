<?php

if (session_status() == PHP_SESSION_NONE)
    session_start();

require_once __DIR__ . '/../include/autoload.include.php';

$json = array('message' => "", 'valid' => false);

if (isset($_SESSION['user'])) {
    if (isset($_POST['imageId'])) {
        if (($image = Image::createFromId($_POST['imageId'])) !== false) {
            if ($image->getUserId() === $_SESSION['user']['userId']) {
                $image->delete();
                $json['message'] = "Image removed";
                $json['valid'] = true;
                $json['userId'] = $_SESSION['user']['userId'];
            } else
                $json['message'] = "You don't have the permission to delete this image";
        }
    } else
        $json['message'] = "Image not found";
} else
    $json['message'] = "You are not connected";

echo json_encode($json);