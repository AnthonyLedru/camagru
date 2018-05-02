<?php

if (session_status() == PHP_SESSION_NONE)
    session_start();

require_once __DIR__ . '/../include/autoload.include.php';

$json = array('message' => "", 'valid' => false);

try {
    if (isset($_POST['imageId'])) {
        if (isset($_SESSION['user'])) {
            if (($image = Image::createFromId($_POST['imageId'])) !== false) {
                if ($image->getUserId() === $_SESSION['user']['userId']) {
                    if (($user = User::createFromId($_SESSION['user']['userId'])) !== false) {
                        $user->setImageId($image->getImageId());
                        $user->update();
                        $json['valid'] = true;
                        $json['message'] = "Profile photo changed successfully";
                    } else
                        $json['message'] = "User not found";
                } else
                    $json['message'] = "Can't update your profile photo, this image is not your";
            } else
                $json['message'] = "Image not found";
        } else
            $json['message'] = "You are not connected";
    } else
        $json['message'] = "No image specified";
} catch (Exception $e) {
    $json['valid'] = false;
    $json['message'] = $e->getMessage();
}

echo json_encode($json);