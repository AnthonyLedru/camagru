<?php

if (session_status() == PHP_SESSION_NONE)
    session_start();

require_once __DIR__ . '/../include/autoload.include.php';

$json = array('message' => "", 'valid' => false);

try {
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
                        if ($pref->getName() === "notification") {
                            if ($_POST['notification'] === "Enabled")
                                $userPreference->updateUserPreference(1);
                            else if ($_POST['notification'] === "Disabled")
                                $userPreference->updateUserPreference(0);
                        } else if ($pref->getName() === "default_theme") {
                            if ($_POST['theme'] === "Default")
                                $userPreference->updateUserPreference(1);
                            else {
                                $userPreference->updateUserPreference(0);
                            }
                        }
                    }
                    $json['message'] =  "Preferences updated !";
                    $json['valid'] = true;
                } else 
                    $json['message'] =  "Send mail field is invalid";
            } else
                $json['message'] =  "Please, select a valid theme";

        } else
            $json['message'] =  "A required field is empty";
    } else
        $json['message'] = "You are not connected";
} catch (Exception $e) {
    $json['message'] = $e->getMessage();
    $json['valid'] = false;
}

echo json_encode($json);
