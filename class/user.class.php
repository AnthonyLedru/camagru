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

    public function getUserId() { return $this->user_id; }
    public function getMail() { return $this->mail; }
    public function getLogin() { return $this->login; }
    public function getPassword() { return $this->password; }
    public function getLastName() { return $this->last_name; }
    public function getFirstName() { return $this->first_name; }
    public function getGender() { return $this->user_id; }
    public function getSignupToken() { return $this->signup_token; }
    public function getPasswordToken() { return $this->password_token; }
    public function getActive() { return $this->active; }
    public function getAll() {
        return array('userId' => $this->user_id, 'mail' => $this->mail, 'login' => $this->login, 
                     'lastName' => $this->last_name, 'firstName' => $this->first_name,
                     'gender' => $this->gender, 'active' => $this->active);
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