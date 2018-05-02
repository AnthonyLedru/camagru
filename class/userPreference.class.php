<?php

require_once __DIR__ . '/../include/autoload.include.php';

class UserPreference {

    private $user_preference_id = null;
    private $user_id = null;
    private $preference_id = null;
    private $active = null;
    const DEFAULT_PREFERENCES = array(array('notification', 1), array('default_theme', 1));

    public function getUserPreferenceId() { return $this->user_preference_id; }
    public function getUserId() { return $this->user_id; }
    public function getPreferenceId() { return $this->preference_id; }
    public function getActive() { return $this->active; }

    public static function createFromUserId($userId) {
        $userPrefQuery = myPDO::getInstance()->prepare(<<<SQL
        SELECT *
        FROM user_preference
        WHERE user_id = ?
SQL
        );
        $userPrefQuery->setFetchMode(PDO::FETCH_CLASS, __CLASS__ );
        $userPrefQuery->execute(array($userId));
        if (($userPreference = $userPrefQuery->fetchAll()) !== false)
            return $userPreference;
        return false;
    }

    public static function insertUserPreference($userId, $preferenceId, $active) {
        $userPrefQuery = myPDO::getInstance()->prepare(<<<SQL
        INSERT INTO user_preference (user_id, preference_id, active)
        VALUES (:userId, :preferenceId, :active)
SQL
        );
        $userPrefQuery->execute(array(
            ':userId' => $userId,
            ':preferenceId' => $preferenceId,
            ':active' => $active,
        ));
    }

    public static function insertDefaultPreference($userId) {
        foreach (self::DEFAULT_PREFERENCES as $pref_tab) {
            if (!($pref = Preference::createFromName($pref_tab[0]))) {
                Preference::insertFromName($pref_tab[0]);
                $pref = Preference::createFromName($pref_tab[0]);
            }
            $already_exist = false;
            if ($user_prefs = self::createFromUserId($userId)) {
                foreach ($user_prefs as $user_pref) {
                    if ($user_pref->getPreferenceId() === $pref->getPreferenceId())
                        $already_exist = true;
                }
            }
            if (!$already_exist)
                self::insertUserPreference($userId, $pref->getPreferenceId(), $pref_tab[1]);
        }
    }

    public static function getUserPreferenceFromTab($userPreferenceTab, $name) {
        foreach ($userPreferenceTab as $userPreference) {
            $pref = Preference::createFromId($userPreference->getPreferenceId());
            if ($pref->getName() === $name)
                return $userPreference;
        }
        return false;
    }

    public static function isDefaultThemeActive($userId) {
        $userPrefQuery = myPDO::getInstance()->prepare(<<<SQL
        SELECT *
        FROM user_preference, preference
        WHERE user_preference.preference_id = preference.preference_id
        AND user_preference.user_id = :userId
        AND preference.name = :theme
SQL
        );
        $userPrefQuery->setFetchMode(PDO::FETCH_CLASS, __CLASS__ );
        $userPrefQuery->execute(array('userId' => $userId, 'theme' => "default_theme"));
        if (($theme = $userPrefQuery->fetch()) !== false)
            return $theme->getActive();
        return false;
    }

    public function updateUserPreference($active) {
        $userPrefQuery = myPDO::getInstance()->prepare(<<<SQL
        UPDATE user_preference
        SET active = :active
        WHERE user_preference_id = :user_preference_id
SQL
        );
        $userPrefQuery->execute(array(':active' => $active, ':user_preference_id' => $this->getUserPreferenceId()));
    }
}

