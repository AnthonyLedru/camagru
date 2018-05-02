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
        try {
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
        } catch (Exception $e) {
            throw new Exception("Query error => Can't create image");
        }
    }

    public static function deleteFromId($id) {
        try {
            $imageQuery = myPDO::getInstance()->prepare(<<<SQL
            DELETE FROM image 
            WHERE image_id = ?
SQL
            );
            $imageQuery->setFetchMode(PDO::FETCH_CLASS, __CLASS__ );
            $imageQuery->execute(array($id));
        } catch (Exception $e) {
            throw new Exception("Query error => Can't delete image");
        }
    }

    public static function insert($imageTab) {
        try {
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
        } catch (Exception $e) {
            throw new Exception("Query error => Can't insert image");
        }
    }

    public static function getAll() {
        try {
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
        } catch (Exception $e) {
            throw new Exception("Query error => Can't get images");
        }
    }

    public static function getLastPhotos($skip, $limit) {
        try {
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
        } catch (Exception $e) {
            throw new Exception("Query error => Can't get images");
        }
    }

    public static function getLastPhotosFromUser($userId, $skip, $limit) {
        try {
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
        } catch (Exception $e) {
            throw new Exception("Query error => Can't get images");
        }
    }

    public function delete() {
        try {
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
        } catch (Exception $e) {
            throw new Exception("Query error => Can't delete image");
        }
    }

    public function jsonSerialize() {
        try {
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
        } catch (Exception $e) {
            throw new Exception("Serialize error => Can't serialize image");
        }
    }
}