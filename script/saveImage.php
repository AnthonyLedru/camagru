<?php

require_once '../include/autoload.include.php';

if (session_status() == PHP_SESSION_NONE)
    session_start();

if (isset($_POST['image'])) {
    if (isset($_SESSION['user'])) {
        $user = User::createFromLogin($_SESSION['user']['login']);
        $img = $_POST['image'];
        if (!is_dir(__DIR__ . "/../photos/user_{$user->getUserId()}")) {
            if (!mkdir(__DIR__ . "/../photos/user_{$user->getUserId()}"))
                echo "An error ocurred during the save";
                return ;
        } else {
            $path = __DIR__ . "/../photos/user_{$user->getUserId()}/";
            $file = $path . md5(uniqid()) . '.png';
            $img = str_replace('data:image/png;base64,', '', $img);
            $img = str_replace(' ', '+', $img);
            file_put_contents($file, base64_decode($img));
            echo "Image(s) saved";
        }
    } else 
    echo "You must be logged in to save images";
} else
    echo "No image data found";