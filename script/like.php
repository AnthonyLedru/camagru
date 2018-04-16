<?php

if (session_status() == PHP_SESSION_NONE)
    session_start();

require_once __DIR__ . '/../include/autoload.include.php';

if (isset($_SESSION['user'])) { 
    if (isset($_POST['imageId'])) {
        try {
            $user = User::createFromLogin($_SESSION['user']['login']);
            $image = Image::createFromId($_POST['imageId']);
            if (($like = Like::hasUserLiked($user->getUserId(), $image->getImageId())) !== false) {
                Like::delete($like->getLikeId());
                echo "You unliked this image";
            }
            else {
                Like::add($user->getUserId(), $image->getImageId());
                echo "You liked this image";
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    } else
        echo "Image does not exists";
} else
    echo "You need to be logged in to like photos";
