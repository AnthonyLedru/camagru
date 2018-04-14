<?php

require_once __DIR__ . '/../include/autoload.include.php';

Class Preference {

    private $preference_id = null;
    private $name = null;

    public function getPreferenceId() { return $this->preference_id; }
    public function getName() { return $this->name; }

    public static function createFromName($name) {
        $userQuery = myPDO::getInstance()->prepare(<<<SQL
        SELECT *
        FROM preference
        WHERE name = ?
SQL
        );
        $userQuery->setFetchMode(PDO::FETCH_CLASS, __CLASS__ );
        $userQuery->execute(array($name));
        if (($preference = $userQuery->fetch()) !== false)
            return $preference;
        return false;
    }

    public static function createFromId($id) {
        $userQuery = myPDO::getInstance()->prepare(<<<SQL
        SELECT *
        FROM preference
        WHERE preference_id = ?
SQL
        );
        $userQuery->setFetchMode(PDO::FETCH_CLASS, __CLASS__ );
        $userQuery->execute(array($id));
        if (($preference = $userQuery->fetch()) !== false)
            return $preference;
        return false;
    }

    public static function insertFromName($name) {
        $userQuery = myPDO::getInstance()->prepare(<<<SQL
        INSERT INTO preference (name)
        VALUES (?)
SQL
        );
        $userQuery->execute(array($name));
    }
}