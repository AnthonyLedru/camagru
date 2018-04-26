<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

require_once __DIR__ . '/../include/autoload.include.php';

$json = array('message' => "", 'images' => "", 'valid' => false);

if (isset($_POST['skip']) && isset($_POST['limit']) && isset($_POST['userId'])) {
    $images = Image::getLastPhotosFromUser($_POST['skip'], $_POST['limit']);
    $json['images'] = $images;
    $json = json_encode($json, JSON_PRETTY_PRINT);
    $json = json_decode($json, true);
    foreach ($json['images'] as $key => $image) {
        if (isset($_SESSION['user'])) {
            $hasLiked = true;
            if (Like::hasUserLiked($_SESSION['user']['userId'], $json['images'][$key]['imageId']) === false)
                $hasLiked = false;
            $json['images'][$key]['hasLiked'] = $hasLiked;
        }
    }
    $json['valid'] = true;
    $json['message'] = "Images loaded";
    echo json_encode($json, JSON_PRETTY_PRINT);
    exit();
} else
    $json['message'] = "An error occured while loading new images";

echo json_encode($json);