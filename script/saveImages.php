<?php

require_once __DIR__ . '/../include/autoload.include.php';

if (session_status() == PHP_SESSION_NONE)
    session_start();

$json = array('message' => "", 'valid' => false);

try {
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
            $count = count($imagesTab);
            if ($count !== 0) {
                if ($count <= 5) {
                    foreach ($imagesTab as $img) {
                            if (strlen($img['description']) <= 80) {
                                if (substr($img['src'], 0, 22) === "data:image/png;base64,") {
                                    $file = $path . md5(uniqid()) . '.png';
                                    $img['src'] = str_replace('data:image/png;base64,', '', $img['src']);
                                    $img['src'] = str_replace(' ', '+', $img['src']);
                                    file_put_contents($file, base64_decode($img['src']));
                                    Image::insert(array('userId' => $user->getUserId(),
                                                        'path' => substr(strstr($file, "/photos/"), 1),
                                                        'description' => htmlentities($img['description'])));
                                } else {
                                    $json['message'] = "You can't upload something else than images";
                                    echo json_encode($json);
                                    exit();
                                }
                            } else {
                                $json['message'] = "Image description is too long, max 80 characters";
                                echo json_encode($json);
                                exit();
                            }
                        $json['valid'] = true;
                        $json['message'] = "Image(s) saved";
                    }
                    if (($followers = Follow::getFollowers($user->getUserId())) !== false) {
                        foreach ($followers as $follower)
                            if (($userFollower = User::createFromId($follower->getUserIdFollower())) !== false)
                                $userFollower->sendNewPhotoMail($user);
                    }
                } else
                    $json['message'] = "You can't upload more than 5 images";
            } else
                $json['message'] = "There is no images to save";
        } else 
            $json['message'] = "You must be logged in to save images";
    } else
        $json['message'] = "No image found";
} catch (Exception $e) {
    $json['valid'] = false;
    $json['message'] = $e->getMessage();
}

echo json_encode($json);