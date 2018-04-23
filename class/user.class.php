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
    public function getFullName() { return $this->last_name . " " . $this->first_name; }
    public function getAll() {
        return array('userId' => $this->user_id, 'mail' => $this->mail, 'login' => $this->login, 
                     'lastName' => $this->last_name, 'firstName' => $this->first_name,
                     'gender' => $this->gender, 'active' => $this->active, 'bio' => $this->bio);
    }

    public function setUserId($userId) { $this->user_id = $userId; }
    public function setMail($mail) { $this->mail = $mail; }
    public function setLogin($login) { $this->login = $login; }
    public function setPassword($password) { $this->password = $password; }
    public function setLastName($lastName) { $this->last_name = $lastName; }
    public function setFirstName($firstName) { $this->first_name = $firstName; }
    public function setGender($gender) { $this->gender = $gender; }
    public function setSignupToken($signupToken) { $this->signup_token = $signupToken; }
    public function setPasswordToken($passwordToken) { $this->password_token = $passwordToken; }
    public function setActive($active) { $this->active = $active; }
    public function setBio($bio) { $this->bio = $bio; }
    

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
        throw new Exception("Id incorrect");
    }

    public static function createFromMail($mail) {
        $userQuery = myPDO::getInstance()->prepare(<<<SQL
        SELECT *
        FROM user
        WHERE mail = ?
SQL
        );
        $userQuery->setFetchMode(PDO::FETCH_CLASS, __CLASS__ );
        $userQuery->execute(array($mail));
        if (($user = $userQuery->fetch()) !== false)
            return $user;
        return false;
    }

    public static function createFromSignupToken($token) {
        $userQuery = myPDO::getInstance()->prepare(<<<SQL
        SELECT *
        FROM user
        WHERE signup_token = ?
SQL
        );
        $userQuery->setFetchMode(PDO::FETCH_CLASS, __CLASS__ );
        $userQuery->execute(array($token));
        if (($user = $userQuery->fetch()) !== false)
            return $user;
        return false;
    }

    public static function createFromPasswordToken($token) {
        $userQuery = myPDO::getInstance()->prepare(<<<SQL
        SELECT *
        FROM user
        WHERE password_token = ?
SQL
        );
        $userQuery->setFetchMode(PDO::FETCH_CLASS, __CLASS__ );
        $userQuery->execute(array($token));
        if (($user = $userQuery->fetch()) !== false)
            return $user;
        return false;
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

    public static function insert($userTab)
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

    public static function updateFromTab($userTab)
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

    public function update() {
        $userQuery = myPDO::getInstance()->prepare(<<<SQL
        UPDATE user
        SET login = :login, mail = :mail, password = :password, last_name = :lastName, 
            first_name = :firstName, gender = :gender, bio = :bio, signup_token = :signupToken,
            active = :active, password_token = :passwordToken
        WHERE user_id = :userId
SQL
        );
        $userQuery->execute(array(
            'userId' => $this->user_id,
            ':login' => $this->login,
            ':mail' => $this->mail,
            ':password' => $this->password,
            ':lastName' => $this->last_name,
            ':firstName' => $this->first_name,
            ':gender' => $this->gender,
            ':bio' => $this->bio,
            ':signupToken' => $this->signup_token,
            ':active' => $this->active,
            ':passwordToken' => $this->password_token,
        ));
    }
}