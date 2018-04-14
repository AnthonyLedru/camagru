<?php

require_once __DIR__ . '/../include/autoload.include.php';

class Image {

    private $image_id = null;
    private $path = null;
    private $description = null;
    private $date = null;

    public function getImageId() { return $this->image_id; }
    public function getPath() { return $this->path; }
    public function getDescription() { return $this->description; }
    public function getDate() { return $this->date; }

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
        throw new Exception("Id incorrect");
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

}