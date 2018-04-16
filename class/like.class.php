<?php

require_once __DIR__ . '/../include/autoload.include.php';

class Like {

    private $like_id = null;
    private $user_id = null;
    private $image_id = null;

    public function getLikeId() { return $this->like_id; }
    public function getUserId() { return $this->user_id; }
    public function getImageId() { return $this->image_id; }

    public static function countFromImageId($imageId) {
        $likeQuery = myPDO::getInstance()->prepare(<<<SQL
        SELECT COUNT(*)
        FROM `like`
        WHERE image_id = ?
SQL
        );
        $likeQuery->setFetchMode(PDO::FETCH_CLASS, __CLASS__ );
        $likeQuery->execute(array($imageId));
        return $likeQuery->fetchColumn(); 
    }

    public static function add($userId, $imageId) {
        $likeQuery = myPDO::getInstance()->prepare(<<<SQL
        INSERT INTO `like`(user_id, image_id)
        VALUES (:user_id, :image_id)
SQL
        );
        $likeQuery->setFetchMode(PDO::FETCH_CLASS, __CLASS__ );
        $likeQuery->execute(array('user_id' => $userId, 'image_id' => $imageId));
    }

    public static function delete($likeId) {
        $likeQuery = myPDO::getInstance()->prepare(<<<SQL
        DELETE FROM `like`
        WHERE like_id = ?
SQL
        );
        $likeQuery->setFetchMode(PDO::FETCH_CLASS, __CLASS__ );
        $likeQuery->execute(array($likeId));
    }

    public static function hasUserLiked($userId, $imageId) {
        $likeQuery = myPDO::getInstance()->prepare(<<<SQL
        SELECT *
        FROM `like`
        WHERE user_id = :userId
        AND image_id = :imageId
SQL
        );
        $likeQuery->setFetchMode(PDO::FETCH_CLASS, __CLASS__ );
        $likeQuery->execute(array(':userId' => $userId, ':imageId' => $imageId));
        $like = $likeQuery->fetch();
        if (count($like) > 0)
            return $like;
        else
            return false; 
    }

}