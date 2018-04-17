<?php

if (session_status() == PHP_SESSION_NONE)
    session_start();

require_once __DIR__ . '/../include/autoload.include.php';

$json = array('message' => "");

if (isset($_SESSION['user'])) {
    if (isset($_POST['comment']))
        $_POST['comment'] = trim($_POST['comment']);
    if (isset($_POST['imageId'])) {
        if (isset($_POST['comment']) && $_POST['comment'] != "") {
            if (strlen($_POST['comment']) <= 255) {
                try {
                    $user = User::createFromLogin($_SESSION['user']['login']);
                    $image = Image::createFromId($_POST['imageId']);
                    $commentTab = array('userId' => $user->getUserId(),
                                        'imageId' => $image->getImageId(),
                                        'comment' => htmlspecialchars($_POST['comment']),
                                        'date' => date("Y-m-d H:i:s"));
                    Comment::add($commentTab);
                    $json = array('comment' => htmlspecialchars($_POST['comment']),
                                                'date' => date("Y-m-d H:i:s"),
                                                'login' => $user->getLogin(),
                                                'message' => "Comment sent");
                } catch (Exception $e) {
                    $json['message'] = $e->getMessage();
                }
            } else
                $json['message'] = "Your comment is too long";
        } else 
            $json['message'] = "Your comment is invalid";
    } else
        $json['message'] = "Image does not exists";
} else
    $json['message'] = "You need to be logged in to comment photos";

echo json_encode($json);
