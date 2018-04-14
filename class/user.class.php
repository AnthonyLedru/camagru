<?php

require_once __DIR__ . '/../include/autoload.include.php';

class User {

    private $user_id = null;
    private $mail = null;
    private $login = null;
    private $password = null;
    private $last_name = null;
    private $first_name = null;
    private $gender = null;
    private $signup_token = null;
    private $password_token = null;
    private $active = null;
    private $bio = null;

    public function getUserId() { return $this->user_id; }
    public function getMail() { return $this->mail; }
    public function getLogin() { return $this->login; }
    public function getPassword() { return $this->password; }
    public function getLastName() { return $this->last_name; }
    public function getFirstName() { return $this->first_name; }
    public function getGender() { return $this->gender; }
    public function getSignupToken() { return $this->signup_token; }
    public function getPasswordToken() { return $this->password_token; }
    public function getActive() { return $this->active; }
    public function getBio() { return $this->bio; }
    public function getAll() {
        return array('userId' => $this->user_id, 'mail' => $this->mail, 'login' => $this->login, 
                     'lastName' => $this->last_name, 'firstName' => $this->first_name,
                     'gender' => $this->gender, 'active' => $this->active, 'bio' => $this->bio);
    }
    

    public static function createFromLogin($userLogin) {
        $userQuery = myPDO::getInstance()->prepare(<<<SQL
        SELECT *
        FROM user
        WHERE login = ?
SQL
        );
        $userQuery->setFetchMode(PDO::FETCH_CLASS, __CLASS__ );
        $userQuery->execute(array($userLogin));
        if (($user = $userQuery->fetch()) !== false)
            return $user;
        throw new Exception("Login incorrect");
    }

    public static function createFromId($id) {
        $userQuery = myPDO::getInstance()->prepare(<<<SQL
        SELECT *
        FROM user
        WHERE user_id = ?
SQL
        );
        $userQuery->setFetchMode(PDO::FETCH_CLASS, __CLASS__ );
        $userQuery->execute(array($id));
        if (($user = $userQuery->fetch()) !== false)
            return $user;
        throw new Exception("Id inccorect");
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

    public static function loginAlreadyTaken($login) {
        $userQuery = myPDO::getInstance()->prepare(<<<SQL
        SELECT *
        FROM user
        WHERE login = :login
SQL
        );
        $userQuery->execute(array(':login' => $login));
        $userQuery->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
        if (count($userQuery->fetchAll()) > 0)
            return true;
        else
            return false;
    }

    public static function mailAlreadyTaken($mail) {
        $userQuery = myPDO::getInstance()->prepare(<<<SQL
        SELECT *
        FROM user
        WHERE mail = :mail
SQL
        );
        $userQuery->execute(array(':mail' => $mail));
        $userQuery->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
        if (count($userQuery->fetchAll()) > 0)
            return true;
        else
            return false;
    }

    public static function insertUser($userTab)
    {
        $userQuery = myPDO::getInstance()->prepare(<<<SQL
        INSERT INTO user (mail, login, password, last_name, first_name, gender, signup_token, active, bio)
        VALUES (:mail, :login, :password, :lastName, :firstName, :gender, :signupToken, :active, :bio)
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
            ':active' => $userTab['active'],
            ':bio' => $userTab['bio']
        ));
    }

    public static function UpdateUser($userTab)
    {
        $userQuery = myPDO::getInstance()->prepare(<<<SQL
        UPDATE user
        SET login = :login, mail = :mail, password = :password, last_name = :lastName, 
            first_name = :firstName, gender = :gender, bio = :bio 
        WHERE user_id = :userId
SQL
        );
        $userQuery->execute(array(
            ':login' => $userTab['login'],
            ':mail' => $userTab['mail'],
            ':password' => $userTab['password'],
            ':lastName' => $userTab['lastName'],
            ':firstName' => $userTab['firstName'],
            ':gender' => $userTab['gender'],
            ':bio' => $userTab['bio'],
            ':userId' => $userTab['userId']
        ));
    }   
}