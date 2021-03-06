<?php

require_once __DIR__ . '/../include/autoload.include.php';

class Comment {

    private $comment_id = null;
    private $user_id = null;
    private $image_id = null;
    private $comment = null;
    private $date = null;

    public function getCommentId() { return $this->comment_id; }
    public function getUserId() { return $this->user_id; }
    public function getImageId() { return $this->image_id; }
    public function getComment() { return $this->comment; }
    public function getDate() {
        $date_obj = new DateTime($this->date);
        return $date_obj->format("F j, Y, g:i a"); 
    }

    public static function createFromId($commentId) {
        try {
            $commentQuery = myPDO::getInstance()->prepare(<<<SQL
            SELECT *
            FROM comment
            WHERE comment_id = ?
SQL
            );
            $commentQuery->setFetchMode(PDO::FETCH_CLASS, __CLASS__ );
            $commentQuery->execute(array($commentId));
            if (($comment = $commentQuery->fetch()) !== false)
                return $comment;
            return false;
        } catch (Exception $e) {
            throw new Exception("Query error: Can't get comment");
        }
    }

    public static function getAllFromImage($imageId) {
        try {
            $commentQuery = myPDO::getInstance()->prepare(<<<SQL
            SELECT *
            FROM comment
            WHERE image_id = ?
            ORDER BY DATE DESC
SQL
            );
            $commentQuery->setFetchMode(PDO::FETCH_CLASS, __CLASS__ );
            $commentQuery->execute(array($imageId));
            if (($comments = $commentQuery->fetchAll()) !== false)
                return $comments;
            return false;
        } catch (Exception $e) {
            throw new Exception("Query error: Can't get image comment");
        }
    }

    public static function insert($commentTab) {
        try {
            $likeQuery = myPDO::getInstance()->prepare(<<<SQL
            INSERT INTO comment (user_id, image_id, comment, `date`)
            VALUES (:user_id, :image_id, :comment, CURRENT_TIMESTAMP)
SQL
            );
            $likeQuery->setFetchMode(PDO::FETCH_CLASS, __CLASS__ );
            $likeQuery->execute(array(':user_id' => $commentTab['userId'],
                                    ':image_id' => $commentTab['imageId'],
                                    ':comment' => $commentTab['comment']));
        } catch (Exception $e) {
            throw new Exception("Query error: Can't insert a comment");
        }
    }
}