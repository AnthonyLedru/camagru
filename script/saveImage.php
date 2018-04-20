<?php

require_once '../include/autoload.include.php';

if (session_status() == PHP_SESSION_NONE)
    session_start();

$json = array('message' => "");

if (isset($_POST['imagesTab'])) {
    if (isset($_SESSION['user'])) {
        $user = User::createFromLogin($_SESSION['user']['login']);
        $path = __DIR__ . "/../photos/user_{$user->getUserId()}/";
        if (!is_dir($path)) {
            if (!mkdir($path)) {
                echo json_encode($json['message'] = "An error ocurred during the save");
                exit();
            }
        }
        $imagesTab = json_decode($_POST['imagesTab'], true);
        if (count($imagesTab) !== 0) {
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
                    $json['message'] = $e->getMessage();
                    echo json_encode($json);
                    exit();
                }
                $json['message'] = "Image(s) saved";
            }
        } else
            $json['message'] = "There is no images to save";
    } else 
        $json['message'] = "You must be logged in to save images";
} else
    $json['message'] = "No image data found";

echo json_encode($json);