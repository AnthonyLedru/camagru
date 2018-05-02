<?php

require_once __DIR__ . '/../include/autoload.include.php';

class User implements JsonSerializable {

    private $user_id = null;
    private $image_id = null;
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
    public function getImageId() { return $this->image_id; }
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
        return array('userId' => $this->user_id, 'imageId' => $this->image_id, 'mail' => $this->mail, 'login' => $this->login, 
                     'lastName' => $this->last_name, 'firstName' => $this->first_name,
                     'gender' => $this->gender, 'active' => $this->active, 'bio' => $this->bio);
    }

    public function setUserId($userId) { $this->user_id = $userId; }
    public function setImageId($imageId) { $this->image_id = $imageId; }
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
        try {
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
            return false;
        } catch (Exception $e) {
            throw new Exception("Query error: Can't get User");
        }
    }

    public static function createFromId($id) {
        try {
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
            return false;
        } catch (Exception $e) {
            throw new Exception("Query error: Can't get user");
        }
    }

    public static function createFromMail($mail) {
        try {
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
        } catch (Exception $e) {
            throw new Exception("Query error: Can't get user");
        }
    }

    public static function createFromSignupToken($token) {
        try {
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
        } catch (Exception $e) {
            throw new Exception("Query error: Can't get user");
        }
    }

    public static function createFromPasswordToken($token) {
        try {
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
        } catch (Exception $e) {
            throw new Exception("Query error: Can't get user");
        }
    }

    public static function alreadyExist($mail, $login) {
        try {
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
            return false;
        } catch (Exception $e) {
            throw new Exception("Query error: Can't check user");
        }
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
        try {
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
        } catch (Exception $e) {
            throw new Exception("Query error: Can't insert user");
        }
    }

    public static function updateFromTab($userTab)
    {
        try {
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
        } catch (Exception $e) {
            throw new Exception("Query error: Can't update user");
        }
    }

    public function update() {
        try {
            $userQuery = myPDO::getInstance()->prepare(<<<SQL
            UPDATE user
            SET image_id = :imageId, login = :login, mail = :mail, password = :password, last_name = :lastName, 
                first_name = :firstName, gender = :gender, bio = :bio, signup_token = :signupToken,
                active = :active, password_token = :passwordToken
            WHERE user_id = :userId
SQL
            );
            $userQuery->execute(array(
                'userId' => $this->user_id,
                'imageId' => $this->image_id,
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
        } catch (Exception $e) {
            throw new Exception("Query error: Can't update user");
        }
    }

    public function sendCommentMail($image) {
        try {
            if (($imageOwner = User::createFromId($image->getUserId())) !== false) {
                if (($userPreferences = UserPreference::createFromUserId($imageOwner->getUserId())) !== false) {
                    foreach ($userPreferences as $userPreference) {
                        if (($preference = Preference::createFromId($userPreference->getPreferenceId())) !== false) {
                            if ($preference->getName() === "notification" && $userPreference->getActive() === "1") {
                                $headers = 'Content-type: text/html; charset=utf-8';
                                $message = <<<HTML
                                <html>
                                    <head>
                                        <style>
                                            p, h1, a {
                                                text-align: center
                                            }
                                        </style>
                                    </head>
                                    <body>
                                            <h1>A new comment on your photo</h1>
                                            <p>Hello {$imageOwner->getLogin()},</p>
                                            <p>{$this->getLogin()} recently commented your photo,
                                            <a href="http://$_SERVER[HTTP_HOST]/camagru/photo.php?image_id={$image->getImageId()}">click here to see the comment</a></p>
                                    </body>
                                </html>
HTML;
                                mail($imageOwner->getMail(), "A new comment on your photo", $message, $headers);
                            }
                        }
                    }
                }
            }
        } catch (Exception $e) {
            throw new Exception("Query error: Can't send comment mail");
        }
    }

    public function sendNewPhotoMail($userFollowed) {
        try {
            $headers = 'Content-type: text/html; charset=utf-8';
            $message = <<<HTML
            <html>
                <head>
                    <style>
                        p, h1, a {
                            text-align: center
                        }
                    </style>
                </head>
                <body>
                        <h1>New content from {$userFollowed->getLogin()}</h1>
                        <p>Hello {$this->getLogin()},</p>
                        <p>{$userFollowed->getLogin()} recently added contents,
                        <a href="http://$_SERVER[HTTP_HOST]/camagru/profile.php?user_id={$userFollowed->getUserId()}">click here to see his profile</a></p>
                </body>
            </html>
HTML;
            mail($this->getMail(), "New content from {$userFollowed->getLogin()}", $message, $headers);
        } catch (Exception $e) {
            throw new Exception("Query error: Can't send photo mail");
        }
    }

    public function getNbLikes() {
        try {
            $userQuery = myPDO::getInstance()->prepare(<<<SQL
            SELECT COUNT(*)
            FROM `like`, `image`
            WHERE like.image_id = image.image_id
            AND image.user_id = :userId
SQL
            );
            $userQuery->execute(array(
                ':userId' => $this->user_id,
            ));
            return ($userQuery->fetchColumn());
        } catch (Exception $e) {
            throw new Exception("Query error: Can't get user like number");
        }
    }

    public function getNbPhotos() {
        try {
            $userQuery = myPDO::getInstance()->prepare(<<<SQL
            SELECT COUNT(*)
            FROM `image`
            WHERE user_id = :userId
SQL
            );
            $userQuery->execute(array(
                ':userId' => $this->user_id,
            ));
            return ($userQuery->fetchColumn());
        } catch (Exception $e) {
            throw new Exception("Query error: Can't get user photo number");
        }
    }

    public function searchLogin($search) {
        try {
            $userQuery = myPDO::getInstance()->prepare(<<<SQL
            SELECT *
            FROM `user`
            WHERE login LIKE :search
            LIMIT 5
SQL
            );
            $userQuery->setFetchMode(PDO::FETCH_CLASS, __CLASS__ );
            $userQuery->execute(array(':search' => "%$search%"));
            if (($users = $userQuery->fetchAll()) !== false)
                if (count($users) > 0)
                    return $users;
            return false;
        } catch (Exception $e) {
            throw new Exception("Query error: Can't search user");
        }
    }

    public function searchName($search) {
        try {
            $userQuery = myPDO::getInstance()->prepare(<<<SQL
            SELECT *
            FROM `user`
            WHERE last_name LIKE :search
            OR first_name LIKE :search
            LIMIT 5
SQL
            );
            $userQuery->setFetchMode(PDO::FETCH_CLASS, __CLASS__ );
            $userQuery->execute(array(':search' => "%$search%"));
            if (($users = $userQuery->fetchAll()) !== false)
                if (count($users) > 0)
                    return $users;
            return false;
        } catch (Exception $e) {
            throw new Exception("Query error: Can't search user");
        }
    }

    public function jsonSerialize() {
        try {
            if ($this->getImageId() !== null)
                $profilePhotoPath = Image::createFromId($this->getImageId())->getPath();
            else
                $profilePhotoPath = "img/defaultProfile.png";
            return 
            [
                'userId' => $this->getUserId(),
                'imageId' => $this->getImageId(),
                'mail' => $this->getMail(),
                'login' => $this->getLogin(),
                'first_name' => $this->getFirstName(),
                'last_name' => $this->getLastName(),
                'gender' => $this->getGender(),
                'bio' => $this->getBio(),
                'fullName' => $this->getFullName(),
                'profilePhotoPath' => $profilePhotoPath
            ];
        } catch (Exception $e) {
            throw new Exception("Serialize error: Can't serialize user");
        }
    }
}