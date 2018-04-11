<?php

require_once '../include/autoload.include.php';

class User {

    private $userId = null;
    private $mail = null;
    private $login = null;
    private $password = null;
    private $lastName = null;
    private $firstName = null;
    private $gender = null;
    private $signupToken = null;
    private $passwordToken = null;
    private $active = null;

    public function getUserId() { return $this->userId; }
    public function getMail() { return $this->mail; }
    public function getPassword() { return $this->password; }
    public function getLastName() { return $this->lastName; }
    public function getFirstName() { return $this->firstName; }
    public function getGender() { return $this->userId; }
    public function getSignupToken() { return $this->userId; }
    public function getPasswordToken() { return $this->userId; }
    public function getActive() { return $this->active; }
    
    public static function createFromId($userId) {
        $userQuery = myPDO::getInstance()->prepare(<<<SQL
        SELECT *
        FROM user
        WHERE user_id = ?
SQL
        );
        $userQuery->setFetchMode(PDO::FETCH_CLASS, __CLASS__ );
        $userQuery->execute(array($userId));
        if (($user = $userQuery->fetch()) !== false)
            return $user;
        throw new Exception(__CLASS__ . ": Id not found");
    }

    public static function alreadyExist($mail, $login) {
        $userQuery = myPDO::getInstance()->prepare(<<<SQL
        SELECT *
        FROM user
        WHERE login = :login
        OR mail = :mail
SQL
        );
        $userQuery->execute(array(':login' => $login, ':mail' => $mail));
        $userQuery->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
        if (count($userQuery->fetchAll()) > 0)
            return true;
        else
            return false;
    }

    public static function insertUser($userTab)
    {
        $userQuery = myPDO::getInstance()->prepare(<<<SQL
        INSERT INTO user (mail, login, password, last_name, first_name, gender, signup_token, active)
        VALUES (:mail, :login, :password, :lastName, :firstName, :gender, :signupToken, :active)
SQL
        );
        $userQuery->execute(array(
            ':mail' => $userTab['mail'],
            ':login' => $userTab['login'],
            ':password' => $userTab['password'],
            ':lastName' => $userTab['lastName'],
            ':firstName' => $userTab['firstName'],
            ':gender' => $userTab['gender'],
            ':signupToken' => $userTab['signupToken'],
            ':active' => $userTab['active']
        ));
    }
}