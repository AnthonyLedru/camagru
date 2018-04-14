<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

require_once 'include/autoload.include.php';

if (isset($_SESSION['user'])) {
    $user = User::createFromId($_SESSION['user']['userId']);
    UserPreference::insertDefaultPreference($user->getUserId());
    $userPreferences = UserPreference::createFromUserId($_SESSION['user']['userId']);
    $notification = UserPreference::getUserPreferenceFromTab($userPreferences, "notification");
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
                    <form method="post" id="informations_form">
                        <div class="columns">
                            <div class="column"></div>  
                            <div class="column is-half">
                                <div class="field is-horizontal">
                                    <div class="field-label is-normal">
                                        <label class="label">Login</label>
                                    </div>
                                    <div class="field-body">
                                        <div class="field">
                                            <p class="control is-expanded">
                                                <input class="input" type="text" disabled value="{$user->getLogin()}">
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="field is-horizontal">
                                    <div class="field-label is-normal">
                                        <label class="label">Bio</label>
                                    </div>
                                    <div class="field-body">
                                        <div class="field">
                                            <p class="control is-expanded">
                                                <textarea class="textarea" rows="1" type="textarea" name="bio" placeholder="Describe yourself :D">{$user->getBio()}</textarea>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="field is-horizontal">
                                    <div class="field-label is-normal">
                                        <label class="label">Mail</label>
                                    </div>
                                    <div class="field-body">
                                        <div class="field">
                                            <p class="control is-expanded">
                                                <input class="input" type="text" name="mail" value="{$user->getMail()}">
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="field is-horizontal">
                                    <div class="field-label is-normal">
                                        <label class="label">First Name</label>
                                    </div>
                                    <div class="field-body">
                                        <div class="field">
                                            <p class="control is-expanded">
                                                <input class="input" type="text" name="firstName" value="{$user->getFirstName()}">
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="field is-horizontal">
                                    <div class="field-label is-normal">
                                        <label class="label">Last Name</label>
                                    </div>
                                    <div class="field-body">
                                        <div class="field">
                                            <p class="control is-expanded">
                                                <input class="input" type="text" name="lastName" value="{$user->getLastName()}">
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="field is-horizontal">
                                    <div class="field-label is-normal">
                                        <label class="label">Gender</label>
                                    </div>
                                    <div class="field-body">
                                        <div class="field">
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
                                <div class="field is-horizontal">
                                    <div class="field-label is-normal">
                                        <label class="label">Password</label>
                                    </div>
                                    <div class="field-body">
                                        <div class="field">
                                            <p class="control is-expanded">
                                                <input class="input" type="password" placeholder="Required to update your profile" name="password">
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="field is-horizontal">
                                    <div class="field-label is-normal">
                                        <label class="label">New&nbsp;pass</label>
                                    </div>
                                    <div class="field-body">
                                        <div class="field">
                                            <p class="control is-expanded">
                                                <input class="input" type="password" placeholder="Left blank to not change" name="newPass1">
                                            </p>
                                        </div>
                                        <div class="field">
                                            <p class="control is-expanded">
                                                <input class="input" type="password" placeholder="Left blank to not change" name="newPass2">
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="column"></div>
                        </div>
                        <button class="button is-dark" id="save_profile_informations_button" form="informations_form" type="submit">Save profile</button>
                    </form>
                </div>
            </div>

            <div class="hero-body is-hidden" id="preferences">
                <div class="container has-text-centered">
                    <form method="post" id="preferences_form">
                        <div class="columns">
                            <div class="column"></div>  
                                <div class="column is-half">

                                    <div class="field is-horizontal">
                                        <div class="field-label is-normal">
                                            <label class="label">Notifications</label>
                                        </div>
                                        <div class="field-body">
                                            <div class="field">
                                                <div class="control">
                                                    <div class="select is-fullwidth">
                                                        <select name="notification">
HTML
);
    if ($notification->getActive() === "1")
        $page->appendContent(<<<HTML

                                                            <option selected>Enabled</option>
HTML
);
    else 
        $page->appendContent(<<<HTML

                                                            <option>Enabled</option>
HTML
);
    if ($notification->getActive() === "0")
        $page->appendContent(<<<HTML

                                                            <option selected>Disabled</option>
HTML
);
    else 
        $page->appendContent(<<<HTML

                                                            <option>Disabled</option>
HTML
);
    $page->appendContent(<<<HTML

                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="field is-horizontal">
                                        <div class="field-label is-normal">
                                            <label class="label">Theme</label>
                                        </div>
                                        <div class="field-body">
                                            <div class="field">
                                                <div class="control">
                                                    <div class="select is-fullwidth">
                                                        <select name="theme">
                                                            <option>Default</option>
                                                            <option>Dark</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="column"></div>
                            </div>
                            <button class="button is-dark" id="save_user_preference_button" type="submit">Save preferences</button>
                        </div>
                    </form>
                </div>
            </div>

HTML
);
    
    echo $page->toHTML();

}
else
    header("location:index.php");