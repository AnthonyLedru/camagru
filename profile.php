<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

require_once 'include/autoload.include.php';

if (isset($_SESSION['user'])) {
    $user = User::createFromId($_SESSION['user']['userId']);
    UserPreference::insertDefaultPreference($user->getUserId());
    $userPreferences = UserPreference::createFromUserId($_SESSION['user']['userId']);
    $page = new Webpage("My Profile");
    $page->appendContent(<<<HTML

        <div class="hero-body" id="profile_card">
            <div class="container has-text-centered">
                <div class="columns is-fullwidth has-text-centered">
                    <div class="column">
                        <h1 class="profile_name">
                            <a class="profile_name" href="profile.php">Hello, {$user->getLogin()} ! 😎</a>
                        </h1>
                    </div>
                </div>
                <div class="columns">
                    <div class="column is-flex is-horizontal-center">
                        <figure class="image" id="figure_profile">
                            <img src="img/profile.jpg" id="profile_img" alt="Home description">
                        </figure>
                    </div>
                </div>
                <div class="columns has-text-centered">
                    <div class="column">
                        <div class="tabs is-toggle is-fullwidth is-medium">
                            <ul>
                                <li id="informations_button" class="is-active"><a>Informations</a></li>
                                <li id="preferences_button"><a>Preferences</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="hero-body is-visible" id="informations">
            <div class="container has-text-centered">
                <form action="script/profileUpdate.php" method="post" id="informations_form">
                    <div class="columns is-centered">
                        <div class="column is-one-quarter">
                            <div class="field">
                                <label class="label">Login</label>
                                <div class="control">
                                    <input class="input has-text-centered" type="text" value="{$user->getLogin()}" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Bio</label>
                        <div class="control">
                            <textarea class="textarea has-text-centered" rows="1" placeholder="Let's introduce yourself" name="bio">{$user->getBio()}</textarea>
                        </div>
                    </div>
                    <div class="columns">
                        <div class="column">
                            <div class="field">
                                <label class="label">Mail</label>
                                <div class="control">
                                    <input class="input has-text-centered" type="text" value="{$user->getMail()}" name="mail">
                                </div>
                            </div>
                        </div>
                        <div class="column">
                            <div class="field">
                                <label class="label">First name</label>
                                <div class="control">
                                    <input class="input has-text-centered" type="text" value="{$user->getFirstName()}" name="firstName">
                                </div>
                            </div>
                        </div>
                        <div class="column">
                            <div class="field">
                                <label class="label">Last name</label>
                                <div class="control">
                                    <input class="input has-text-centered" type="text" value="{$user->getLastName()}" name="lastName">
                                </div>
                            </div>
                        </div>
                        <div class="column is-one-quarter">
                            <div class="field">
                                <label class="label">Gender</label>
                                <div class="control">
                                    <div class="select is-fullwidth">
                                        <select name="gender">
HTML
);
    if ($user->getGender() === "Male")
        $page->appendContent(<<<HTML

                                            <option selected>Male</option>
HTML
);
    else 
        $page->appendContent(<<<HTML
                                            <option>Male</option>
HTML
);
    if ($user->getGender() === "Female")
        $page->appendContent(<<<HTML

                                            <option selected>Female</option>
HTML
);
    else 
        $page->appendContent(<<<HTML

                                            <option>Female</option>
HTML
);
    $page->appendContent(<<<HTML

                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>                       
                    </div>
                    <div class="columns">
                        <div class="column">
                            <div class="field">
                                <label class="label">Password</label>
                                <div class="control">
                                    <input class="input has-text-centered" type="password" placeholder="Required to modify your informations" name="password">
                                </div>
                            </div>
                        </div>
                        <div class="column">
                            <div class="field">
                                <label class="label">New password</label>
                                <div class="control">
                                    <input class="input has-text-centered" type="password" placeholder="Left blank if you do not want to change it" name="newPass1">
                                </div>
                            </div>
                        </div>
                        <div class="column">
                            <div class="field">
                                <label class="label">New password confirmation</label>
                                <div class="control">
                                    <input class="input has-text-centered" type="password" placeholder="Left blank if you do not want to change it" name="newPass2">
                                </div>
                            </div>
                        </div>
                    </div>
                    <button class="button is-dark" id="save_profile_informations_button" form="informations_form" type="submit">Save profile</button>
                </form>
            </div>
        </div>

        <div class="hero-body is-hidden" id="preferences">
            <div class="container has-text-centered">
                <form action="save_profile.php" method="post" id="preferences_form">
                    <div class="columns">
                        <div class="column">
                            <div class="field">
                                <label class="label">Mail notification when someone comment your photos</label>
HTML
);
    $send_mail = UserPreference::getUserPreferenceFromTab($userPreferences, "send_mail");
    if ($send_mail->getActive() == 1)
        $page->appendContent(<<<HTML

                                <input type="checkbox" checked>
HTML
);
    else
        $page->appendContent(<<<HTML

                                <input type="checkbox">
HTML
);
$page->appendContent(<<<HTML
                            </div>
                        </div>
                    </div>
                    <div class="columns has-text-centered is-centered">
                        <div class="column is-one-quarter">
                            <div class="field">
                                <label class="label">Theme</label>
                            </div>
                            <div class="control">
                                <div class="select is-fullwidth">
                                    <select name="gender">
                                        <option selected>Default</option>
                                        <option>Dark</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button class="button is-dark" type="submit">Save preferences</button>
                </form>
            </div>
        </div>

HTML
);
    
    echo $page->toHTML();

}
else
    header("location:index.php");