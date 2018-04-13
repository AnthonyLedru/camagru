<?php

require_once __DIR__ . '/../include/autoload.include.php';

class UserPreference {

    private $user_preference_id = null;
    private $user_id = null;
    private $preference_id = null;
    private $active = null;
    const DEFAULT_PREFERENCES = array(array('send_mail', 1), array('default_theme', 1), array('test', 0));

    public function getUserPreferenceId() { return $this->user_preference_id; }
    public function getUserId() { return $this->user_id; }
    public function getPreferenceId() { return $this->preference_id; }
    public function getActive() { return $this->active; }

    public static function createFromUserId($userId) {
        $userQuery = myPDO::getInstance()->prepare(<<<SQL
        SELECT *
        FROM user_preference
        WHERE user_id = ?
SQL
        );
        $userQuery->setFetchMode(PDO::FETCH_CLASS, __CLASS__ );
        $userQuery->execute(array($userId));
        if (($userPreference = $userQuery->fetchAll()) !== false)
            return $userPreference;
        return false;
    }

    public static function insertUserPreference($userId, $preferenceId, $active) {
        $userQuery = myPDO::getInstance()->prepare(<<<SQL
        INSERT INTO user_preference (user_id, preference_id, active)
        VALUES (:userId, :preferenceId, :active)
SQL
        );
        $userQuery->execute(array(
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
}
