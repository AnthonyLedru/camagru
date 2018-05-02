<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

require_once __DIR__ . '/../include/autoload.include.php';

$json = array('message' => "", 'images' => "", 'valid' => false);

try {
    if (isset($_POST['skip']) && isset($_POST['limit'])) {
        $images = Image::getLastPhotos($_POST['skip'], $_POST['limit']);
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
    } else
        $json['message'] = "An error occured while loading new images";
} catch (Exception $e) {
    $json['valid'] = false;
    $json['message'] = $e->getMessage();
}

echo json_encode($json);