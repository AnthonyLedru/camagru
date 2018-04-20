<?php

require_once '../include/autoload.include.php';

if (session_status() == PHP_SESSION_NONE)
    session_start();

if (isset($_POST['imagesTab'])) {
    if (isset($_SESSION['user'])) {
        $user = User::createFromLogin($_SESSION['user']['login']);
        $path = __DIR__ . "/../photos/user_{$user->getUserId()}/";
        if (!is_dir($path)) {
            if (!mkdir($path)) {
                echo "An error ocurred during the save";
                exit();
            }
        }
        $imagesTab = json_decode($_POST['imagesTab']);
        foreach ($imagesTab as $img) {
            try {
            $file = $path . md5(uniqid()) . '.png';
            $img = str_replace('data:image/png;base64,', '', $img);
            $img = str_replace(' ', '+', $img);
            file_put_contents($file, base64_decode($img));
            Image::insert(array('userId' => $user->getUserId(),
                                'path' => substr(strstr($file, "/photos/"), 1),
                                'description' => ""));
            } catch (Exception $e) {
                echo $e->getMessage();
                exit();
            }
        }
        echo "Image(s) saved";
    } else 
        echo "You must be logged in to save images";
} else
    echo "No image data found";