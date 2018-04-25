<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

require_once __DIR__ . '/../include/autoload.include.php';
if (isset($_SESSION['user'])) {
    if (isset($_POST['notification']) && $_POST['notification'] !== "" &&
            isset($_POST['theme']) && $_POST['theme'] !== "") {
        $themes = array("Default", "Dark");
        if (array_search($_POST['theme'], $themes) !== false) {
            if ($_POST['notification'] === "Enabled" || $_POST['notification'] === "Disabled") {
                $user = User::createFromLogin($_SESSION['user']['login']);
                $userPreferences = UserPreference::createFromUserId($user->getUserId());
                foreach ($userPreferences as $userPreference) {
                    $pref = Preference::createFromId($userPreference->getPreferenceId());
                    if ($pref->getName() === 'notification') {
                        if ($_POST['notification'] === "Enabled")
                            UserPreference::updateUserPreference($userPreference->getUserPreferenceId(), 1);
                        else if ($_POST['notification'] === "Disabled")
                            UserPreference::updateUserPreference($userPreference->getUserPreferenceId(), 0);
                    }
                }
                echo "Preferences updated !";
            } else 
                echo "Send mail field is invalid";
        } else
            echo "Please, select a valid theme";

    } else
        echo "A required field is empty";
} else
    echo "You are not connected";
