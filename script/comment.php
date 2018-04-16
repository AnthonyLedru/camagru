<?php

if (session_status() == PHP_SESSION_NONE)
    session_start();

require_once __DIR__ . '/../include/autoload.include.php';

if (isset($_SESSION['user'])) {
    if (isset($_POST['comment']))
        $_POST['comment'] = trim($_POST['comment']);
    if (isset($_POST['imageId'])) {
        if (isset($_POST['comment']) && $_POST['comment'] != "") {
            try {
                $user = User::createFromLogin($_SESSION['user']['login']);
                $image = Image::createFromId($_POST['imageId']);
                $commentTab = array('userId' => $user->getUserId(),
                                    'imageId' => $image->getImageId(),
                                    'comment' => htmlspecialchars($_POST['comment']),
                                    'date' => date("Y-m-d H:i:s"));
                Comment::add($commentTab);
                echo "Comment sent";
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        } else
            echo "Your comment is invalid";
    } else
        echo "Image does not exists";
} else
    echo "You need to be logged in to comment photos";

