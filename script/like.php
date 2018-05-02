<?php

if (session_status() == PHP_SESSION_NONE)
    session_start();

require_once __DIR__ . '/../include/autoload.include.php';

$json = array('message' => "", 'like' => false, 'unlike' => false, 'nbLike' => 0);

try {
    if (isset($_SESSION['user'])) { 
        if (isset($_POST['imageId'])) {
                $user = User::createFromLogin($_SESSION['user']['login']);
                $image = Image::createFromId($_POST['imageId']);
                if (($like = Like::hasUserLiked($user->getUserId(), $image->getImageId())) !== false) {
                    $like->delete();
                    $json['nbLike'] = Like::countFromImageId($image->getImageId());
                    $json['unlike'] = true;
                    $json['message'] = "You unliked this image";
                }
                else {
                    Like::insert($user->getUserId(), $image->getImageId());
                    $json['nbLike'] = Like::countFromImageId($image->getImageId());
                    $json['like'] = true;
                    $json['message'] = "You liked this image";
                }
        } else
            $json['message'] = "Image does not exists";
    } else
        $json['message'] = "You need to be logged in to like photos";
} catch (Exception $e) {
    $json['valid'] = false;
    $json['message'] = $e->getMessage();
}

echo json_encode($json);
