<?php

if (session_status() == PHP_SESSION_NONE)
    session_start();

require_once __DIR__ . '/../include/autoload.include.php';

$json = array('message' => "", 'follow' => false, 'unfollow' => false, 'nbFollower' => 0);

if (isset($_SESSION['user'])) { 
    if (isset($_POST['userId'])) {
        try {
            $userFollower = User::createFromLogin($_SESSION['user']['login']);
            $userFollowed = User::createFromId($_POST['userId']);
            if (($follow = Follow::hasFollow($userFollower->getUserId(), $userFollowed->getUserId())) !== false) {
                $follow->delete();
                $json['unfollow'] = true;
                $json['message'] = "You don't follow {$userFollowed->getLogin()} anymore";
            }
            else {
                Follow::insert($userFollower->getUserId(), $userFollowed->getUserId());
                $json['follow'] = true;
                $json['message'] = "You now follow {$userFollowed->getLogin()}";
            }
            $json['nbFollower'] = Follow::getNbFollower($userFollowed->getUserId());
            $json['FollowerLogin'] = "@" . $userFollower->getLogin();
            $json['FollowedLogin'] = $userFollowed->getLogin();
            $json['FollowerId'] = $userFollower->getUserId();
        } catch (Exception $e) {
            $json['message'] = $e->getMessage();
        }
    } else
        $json['message'] = "User not found";
} else
    $json['message'] = "You need to be logged in to follow people";

echo json_encode($json);
