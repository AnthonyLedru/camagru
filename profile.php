<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

require_once 'include/autoload.include.php';

if (isset($_SESSION['user'])) {
    $page = new Webpage("My Profile");
    $page->appendContent(<<<HTML

        <div class="hero-body" id="profile_card">
            <div class="container has-text-centered">
                <div class="columns is-fullwidth has-text-centered">
                    <div class="column">
                        <h1 class="profile_name">
                            <a class="profile_name" href="profile.php">Hello, {$_SESSION['user']['firstName']} ! </a>
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
                                <li class="is-active"><a>Informations</a></li>
                                <li><a>Preferences</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="hero-body">
            <div class="container has-text-centered">
                <form action="save_profile.php" method="post" id="profile_form">
                    <div class="field">
                        <label class="label">Bio</label>
                        <div class="control">
                            <textarea class="textarea has-text-centered" placeholder=" Let's introduce yourself"></textarea>
                        </div>
                    </div>
                    <div class="columns">
                        <div class="column">
                            <div class="field">
                                <label class="label">Login</label>
                                <div class="control">
                                    <input class="input has-text-centered" type="text" value="{$_SESSION['user']['login']}" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="column">
                            <div class="field">
                                <label class="label">Mail</label>
                                <div class="control">
                                    <input class="input has-text-centered" type="text" value="{$_SESSION['user']['mail']}">
                                </div>
                            </div>
                        </div>
                        <div class="column">
                            <div class="field">
                                <label class="label">Gender</label>
                                <div class="control">
                                    <div class="select is-fullwidth">
                                        <select name="gender">
HTML
);
    if ($_SESSION['user']['gender'] === "Male")
        $page->appendContent(<<<HTML

                                            <option selected>Male</option>
HTML
);
    else 
        $page->appendContent(<<<HTML
                                            <option>Male</option>
HTML
);
    if ($_SESSION['user']['gender'] === "Female")
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
                                <label class="label">First name</label>
                                <div class="control">
                                    <input class="input has-text-centered" type="text" value="{$_SESSION['user']['firstName']}">
                                </div>
                            </div>
                        </div>
                        <div class="column">
                            <div class="field">
                                <label class="label">Last name</label>
                                <div class="control">
                                    <input class="input has-text-centered" type="text" value="{$_SESSION['user']['lastName']}">
                                </div>
                            </div>
                        </div>
            
                    </div>
                    <div class="field">
                        <label class="label">Password</label>
                        <div class="control">
                            <input class="input has-text-centered" type="password" placeholder="Required to modify your informations">
                        </div>
                    </div>
                    <div class="columns">
                        <div class="column">
                            <div class="field">
                                <label class="label">New password</label>
                                <div class="control">
                                    <input class="input has-text-centered" type="password" placeholder="Left blank if you do not want to change it">
                                </div>
                            </div>
                        </div>
                        <div class="column">
                            <div class="field">
                                <label class="label">New password confirmation</label>
                                <div class="control">
                                    <input class="input has-text-centered" type="password" placeholder="Left blank if you do not want to change it">
                                </div>
                            </div>
                        </div>
                    </div>
                    <button class="button is-dark" type="submit">Save profile</button>
                </form>
            </div>
        </div>

HTML
);
    
    echo $page->toHTML();

}
else
    header("location:index.php");