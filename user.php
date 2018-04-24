<?php

if (session_status() == PHP_SESSION_NONE)
    session_start();

require_once 'include/autoload.include.php';

$page = new Webpage("Users");
$error = false;
if (isset($_GET['user_id'])) {
    if (($user = User::createFromId($_GET['user_id'])) !== false) {
        $page->appendContent(<<<HTML

            <div class="hero-body" id="profile_card">
                <div class="container has-text-centered">
                    <div class="columns is-fullwidth has-text-centered">
                        <div class="column">
                            <p class="title is-4">
                                <a class="profile_name" id="profile_name" href="profile.php">{$user->getLogin()}</a>
                            </p>
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
                                    <li id="first_tab_button" class="is-active"><a>Informations</a></li>
                                    <li id="second_tab_button"><a>Photos</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <p class="is-size-4">{$user->getMail()}</p>
HTML
        );
        if ($user->getBio() !== null) {
            $page->appendContent(<<<HTML

                    <br>
                    <p class="is-size-5">{$user->getBio()}</p>
HTML
            );
        }
        $page->appendContent(<<<HTML
                </div>
            </div>

            <div class="hero-body is-visible" id="first_tab">
                <div class="container has-text-centered">
                    <nav class="level">
                        <div class="level-item has-text-centered">
                            <div>
                            <p class="heading">Photos</p>
                            <p class="title">3,456</p>
                            </div>
                        </div>
                        <div class="level-item has-text-centered">
                            <div>
                            <p class="heading">Following</p>
                            <p class="title">123</p>
                            </div>
                        </div>
                        <div class="level-item has-text-centered">
                            <div>
                            <p class="heading">Followers</p>
                            <p class="title">456K</p>
                            </div>
                        </div>
                        <div class="level-item has-text-centered">
                            <div>
                            <p class="heading">Likes</p>
                            <p class="title">789</p>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>

            <div class="hero-body is-hidden" id="second_tab">
                <div class="container has-text-centered">
                    <form method="post" id="preferences_form">
                        <div class="columns">
                            <div class="column"></div>  
                                <div class="column is-half">

                                </div>
                                <div class="column"></div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

HTML
        );
    } else
        $error = true;
} else
    $error = true;

if ($error) {
    $page->appendContent(<<<HTML
    
            <div class="hero-body">
                <div class="container has-text-centered">
                    <div class="columns is-vcentered">
                        <div class="column is-fullWidth">
                            <h1 class="title">User not found</h1>
                        </div>
                    </div>
                </div>
            </div>
HTML
    );
}

echo $page->toHTML();