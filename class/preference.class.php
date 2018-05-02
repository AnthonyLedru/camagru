<?php

require_once __DIR__ . '/../include/autoload.include.php';

Class Preference {

    private $preference_id = null;
    private $name = null;

    public function getPreferenceId() { return $this->preference_id; }
    public function getName() { return $this->name; }

    public static function createFromName($name) {
        try {
            $preferenceQuery = myPDO::getInstance()->prepare(<<<SQL
            SELECT *
            FROM preference
            WHERE name = ?
SQL
            );
            $preferenceQuery->setFetchMode(PDO::FETCH_CLASS, __CLASS__ );
            $preferenceQuery->execute(array($name));
            if (($preference = $preferenceQuery->fetch()) !== false)
                return $preference;
            return false;
        } catch (Exception $e) {
            throw new Exception("Query error => Can't create preference");
        }
    }

    public static function createFromId($id) {
        try {
            $preferenceQuery = myPDO::getInstance()->prepare(<<<SQL
            SELECT *
            FROM preference
            WHERE preference_id = ?
SQL
            );
            $preferenceQuery->setFetchMode(PDO::FETCH_CLASS, __CLASS__ );
            $preferenceQuery->execute(array($id));
            if (($preference = $preferenceQuery->fetch()) !== false)
                return $preference;
            return false;
        } catch (Exception $e) {
            throw new Exception("Query error => Can't create preference");
        }
    }

    public static function insertFromName($name) {
        try {
            $preferenceQuery = myPDO::getInstance()->prepare(<<<SQL
            INSERT INTO preference (name)
            VALUES (?)
SQL
            );
            $preferenceQuery->execute(array($name));
        } catch (Exception $e) {
            throw new Exception("Query error => Can't insert preference");
        }
    }
}