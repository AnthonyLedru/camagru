<?php

require_once __DIR__ . '/../include/autoload.include.php';

class Image implements JsonSerializable {

    private $image_id = null;
    private $user_id = null;
    private $path = null;
    private $description = null;
    private $date = null;

    public function getImageId() { return $this->image_id; }
    public function getUserId() { return $this->user_id; }
    public function getPath() { return $this->path; }
    public function getDescription() { return $this->description; }
    public function getDate() {
        $date_obj = new DateTime($this->date);
        return $date_obj->format("F j, Y, g:i a"); 
    }

    public static function createFromId($id) {
        $imageQuery = myPDO::getInstance()->prepare(<<<SQL
        SELECT *
        FROM image
        WHERE image_id = ?
SQL
        );
        $imageQuery->setFetchMode(PDO::FETCH_CLASS, __CLASS__ );
        $imageQuery->execute(array($id));
        if (($image = $imageQuery->fetch()) !== false)
            return $image;
        return false;
    }

    public static function deleteFromId($id) {
        $imageQuery = myPDO::getInstance()->prepare(<<<SQL
        DELETE FROM image 
        WHERE image_id = ?
SQL
        );
        $imageQuery->setFetchMode(PDO::FETCH_CLASS, __CLASS__ );
        $imageQuery->execute(array($id));
    }

    public static function insert($imageTab) {
        $imageQuery = myPDO::getInstance()->prepare(<<<SQL
        INSERT INTO image (user_id, path, description, date)
        VALUES (:userId, :path, :description, CURRENT_TIMESTAMP)
SQL
        );
        $imageQuery->execute(array(
            ':userId' => $imageTab['userId'],
            ':path' => $imageTab['path'],
            ':description' => $imageTab['description'],
        ));
    }

    public static function getAll() {
        $imageQuery = myPDO::getInstance()->prepare(<<<SQL
        SELECT *
        FROM image
        ORDER BY date DESC
SQL
        );
        $imageQuery->setFetchMode(PDO::FETCH_CLASS, __CLASS__ );
        $imageQuery->execute();
        if (($userImages = $imageQuery->fetchAll()) !== false)
            return $userImages;
        return false;
    }

    public static function getLastPhotos($skip, $limit) {
        $imageQuery = myPDO::getInstance()->prepare(<<<SQL
        SELECT *
        FROM image
        ORDER BY date DESC
        LIMIT :skip, :limit;
SQL
        );
        $imageQuery->setFetchMode(PDO::FETCH_CLASS, __CLASS__ );
        $imageQuery->bindValue(':skip', (int)$skip, PDO::PARAM_INT);
        $imageQuery->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $imageQuery->execute();
        if (($userImages = $imageQuery->fetchAll()) !== false)
            return $userImages;
        return false;
    }

    public static function getLastPhotosFromUser($userId, $skip, $limit) {
        $imageQuery = myPDO::getInstance()->prepare(<<<SQL
        SELECT *
        FROM image
        WHERE image.user_id = :userId
        ORDER BY date DESC
        LIMIT :skip, :limit;
SQL
        );
        $imageQuery->setFetchMode(PDO::FETCH_CLASS, __CLASS__ );
        $imageQuery->bindValue(':userId', (int)$userId, PDO::PARAM_INT);
        $imageQuery->bindValue(':skip', (int)$skip, PDO::PARAM_INT);
        $imageQuery->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $imageQuery->execute();
        if (($userImages = $imageQuery->fetchAll()) !== false)
            return $userImages;
        return false;
    }

    public function delete() {
        $imageQuery = myPDO::getInstance()->prepare(<<<SQL
        DELETE FROM image
        WHERE image_id = :imageId
SQL
        );
        $imageQuery->setFetchMode(PDO::FETCH_CLASS, __CLASS__ );
        $imageQuery->bindValue(':imageId', (int)$this->image_id, PDO::PARAM_INT);
        $imageQuery->execute();
        if ($imageQuery->rowCount() === 1)
            return true;
        return false;
    }

    public function jsonSerialize()
    {
        $user = User::createFromId($this->getUserId());
        return 
        [
            'imageId' => $this->getImageId(),
            'userId' => $this->getUserId(),
            'path' => $this->getPath(),
            'description' => $this->getDescription(),
            'date' => $this->getDate(),
            'hasLiked' => false,
            'user' => $user,
            'nbLikes' => Like::countFromImageId($this->getImageId())
        ];
    }
}