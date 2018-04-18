<?php

require_once __DIR__ . '/../include/autoload.include.php';

class Image {

    private $image_id = null;
    private $user_id = null;
    private $path = null;
    private $description = null;
    private $date = null;
    private $nb_like = null;

    public function getImageId() { return $this->image_id; }
    public function getUserId() { return $this->user_id; }
    public function getPath() { return $this->path; }
    public function getDescription() { return $this->description; }
    public function getNbLike() { return $this->nb_like; }
    public function getDate() {
        $date_obj = new DateTime($this->date);
        return $date_obj->format("F j, Y, g:i a"); 
    }

    public static function createFromId($id) {
        $ImageQuery = myPDO::getInstance()->prepare(<<<SQL
        SELECT *
        FROM image
        WHERE image_id = ?
SQL
        );
        $ImageQuery->setFetchMode(PDO::FETCH_CLASS, __CLASS__ );
        $ImageQuery->execute(array($id));
        if (($image = $ImageQuery->fetch()) !== false)
            return $image;
        return false;
    }

    public static function deleteFromId($id) {
        $ImageQuery = myPDO::getInstance()->prepare(<<<SQL
        DELETE FROM image 
        WHERE image_id = ?
SQL
        );
        $ImageQuery->setFetchMode(PDO::FETCH_CLASS, __CLASS__ );
        $ImageQuery->execute(array($id));
    }

    public static function getAll() {
        $ImageQuery = myPDO::getInstance()->prepare(<<<SQL
        SELECT *
        FROM image
        ORDER BY date DESC
SQL
        );
        $ImageQuery->setFetchMode(PDO::FETCH_CLASS, __CLASS__ );
        $ImageQuery->execute();
        if (($userImages = $ImageQuery->fetchAll()) !== false)
            return $userImages;
        return false;
    }

}