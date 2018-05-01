<?php

Class Follow {

    private $follow_id = null;
    private $user_id_followed = null;
    private $user_id_follower = null;

    public function getFollowId() { return $this->follow_id; }
    public function getUserIdFollowed() { return $this->user_id_followed; }
    public function getUserIdFollower() { return $this->user_id_follower; }

    public static function getFollowers($userIdFollowed) {
        $followQuery = myPDO::getInstance()->prepare(<<<SQL
        SELECT *
        FROM follow
        WHERE user_id_followed = :userIdFollowed
SQL
        );
        $followQuery->setFetchMode(PDO::FETCH_CLASS, __CLASS__ );
        $followQuery->execute(array('userIdFollowed' => $userIdFollowed));
        if (($follows = $followQuery->fetchAll()) !== false)
            if (count($follows) > 0)
                return $follows;
        return false;
    }

    public static function getFolloweds($userIdFollower) {
        $followQuery = myPDO::getInstance()->prepare(<<<SQL
        SELECT *
        FROM follow
        WHERE user_id_follower = :userIdFollower
SQL
        );
        $followQuery->setFetchMode(PDO::FETCH_CLASS, __CLASS__ );
        $followQuery->execute(array('userIdFollower' => $userIdFollower));
        if (($follows = $followQuery->fetchAll()) !== false)
            if (count($follows) > 0)
                return $follows;
        return false;
    }

    public static function hasFollow($userIdFollower, $userIdFollowed) {
        $followQuery = myPDO::getInstance()->prepare(<<<SQL
        SELECT *
        FROM follow
        WHERE user_id_followed = :userIdFollowed
        AND user_id_follower = :userIdFollower
SQL
        );
        $followQuery->setFetchMode(PDO::FETCH_CLASS, __CLASS__ );
        $followQuery->execute(array('userIdFollowed' => $userIdFollowed, 'userIdFollower' => $userIdFollower));
        if (($follow = $followQuery->fetch()) !== false)
            return $follow;
        return false;
    }

    public static function insert($userIdFollower, $userIdFollowed) {
        $followQuery = myPDO::getInstance()->prepare(<<<SQL
        INSERT INTO `follow`(user_id_followed, user_id_follower)
        VALUES (:userIdFollowed, :userIdFollower)
SQL
        );
        $followQuery->setFetchMode(PDO::FETCH_CLASS, __CLASS__ );
        $followQuery->execute(array('userIdFollowed' => $userIdFollowed, 'userIdFollower' => $userIdFollower));
    }

    public function delete() {
        $followQuery = myPDO::getInstance()->prepare(<<<SQL
        DELETE FROM `follow`
        WHERE follow_id = :followId
SQL
        );
        $followQuery->setFetchMode(PDO::FETCH_CLASS, __CLASS__ );
        $followQuery->execute(array('followId' => $this->getFollowId()));
    }

    public static function getNbFollower($userIdFollowed) {
        $followQuery = myPDO::getInstance()->prepare(<<<SQL
        SELECT *
        FROM follow
        WHERE user_id_followed = :userIdFollowed
SQL
        );
        $followQuery->setFetchMode(PDO::FETCH_CLASS, __CLASS__ );
        $followQuery->execute(array('userIdFollowed' => $userIdFollowed));
        if (($follow = $followQuery->fetchAll()) !== false)
            return count($follow);
        return 0;
    }

    public static function getNbFollowing($userIdFollower) {
        $followQuery = myPDO::getInstance()->prepare(<<<SQL
        SELECT *
        FROM follow
        WHERE user_id_follower = :userIdFollower
SQL
        );
        $followQuery->setFetchMode(PDO::FETCH_CLASS, __CLASS__ );
        $followQuery->execute(array('userIdFollower' => $userIdFollower));
        if (($follow = $followQuery->fetchAll()) !== false)
            return count($follow);
        return 0;
    }
}