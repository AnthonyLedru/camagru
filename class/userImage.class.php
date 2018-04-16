<?php

require_once __DIR__ . '/../include/autoload.include.php';

class userImage {

    private $user_image_id = null;
    private $user_id = null;
    private $image_id = null;
    private $nb_like = null;

    public function getUserImageId() { return $this->user_image_id; }
    public function getUserId() { return $this->user_id; }
    public function getImageId() { return $this->image_id; }
    public function getNbLike() { return $this->nb_like; }

    public static function createFromUserImageId($userImageId) {
        $userImageQuery = myPDO::getInstance()->prepare(<<<SQL
        SELECT *
        FROM user_image
        WHERE user_image_id = ?
SQL
        );
        $userImageQuery->setFetchMode(PDO::FETCH_CLASS, __CLASS__ );
        $userImageQuery->execute(array($userImageId));
        if (($userImage = $userImageQuery->fetch()) !== false)
            return $userImage;
        return false;
    }

    public static function createFromImageId($imageId) {
        $userImageQuery = myPDO::getInstance()->prepare(<<<SQL
        SELECT *
        FROM user_image
        WHERE image_id = ?
SQL
        );
        $userImageQuery->setFetchMode(PDO::FETCH_CLASS, __CLASS__ );
        $userImageQuery->execute(array($imageId));
        if (($userImage = $userImageQuery->fetch()) !== false)
            return $userImage;
        return false;        
    }
}