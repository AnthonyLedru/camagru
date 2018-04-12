<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

require_once 'include/autoload.include.php';

if (isset($_SESSION['user'])) {
    $page = new Webpage("My Profile");
    $page->appendContent(<<<HTML

        <div class="hero-body" id="profile_card">
            <div class="container has-text-centered">
                <div class="columns is-vcentered">
                    <div class="column is-6 is-offset-1">
                        <h1 id="profile_login"> Hello, {$_SESSION['user']['login']} </h1>
                    </div>
                    <div class="column is-2">
                        <figure class="image is-square" id="figure_profile">
                            <img src="img/profile.jpg" id="profile_img" alt="Home description">
                        </figure>
                    </div>
                </div>
            </div>
        </div>

        <div class="hero-body">
            <div class="container has-text-centered">
                <div class="columns">
                    <div class="column">
                        <div class="field">
                            <label class="label">Login</label>
                            <div class="control">
                                <input class="input has-text-centered" type="text" value="{$_SESSION['user']['login']}" disabled>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Mail</label>
                            <div class="control">
                                <input class="input has-text-centered" type="text" value="{$_SESSION['user']['mail']}">
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Last name</label>
                            <div class="control">
                                <input class="input has-text-centered" type="text" value="{$_SESSION['user']['lastName']}">
                            </div>
                        </div>
                    </div>
                    <div class="column">
                        <div class="field">
                            <label class="label">First name</label>
                            <div class="control">
                                <input class="input has-text-centered" type="text" value="{$_SESSION['user']['firstName']}">
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Gender</label>
                            <div class="control has-text-centered">
                                <div class="select">
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
            </div>
        </div>

HTML
);
    
    echo $page->toHTML();

}
else
    header("location:index.php");