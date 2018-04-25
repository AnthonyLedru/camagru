<?php

if (session_status() == PHP_SESSION_NONE)
    session_start();

require_once 'include/autoload.include.php';

$page = new Webpage("Profile");
$error = false;
if (isset($_GET['user_id'])) {
    if (($user = User::createFromId($_GET['user_id'])) !== false) {
        $page->appendContent(<<<HTML

            <div class="hero-body" id="profile_card">
                <div class="container has-text-centered">
                    <div class="columns is-fullwidth has-text-centered">
                        <div class="column">
                            <p class="title is-4">
                                <a class="profile_name" id="profile_name" href="profile.php?user_id={$user->getUserId()}">{$user->getLogin()}</a>
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
                            <p class="title">{$user->getNbPhotos()}</p>
                            </div>
                        </div>
                        <div class="level-item has-text-centered">
                            <div>
                            <p class="heading">Following</p>
                            <p class="title">0</p>
                            </div>
                        </div>
                        <div class="level-item has-text-centered">
                            <div>
                            <p class="heading">Followers</p>
                            <p class="title">0</p>
                            </div>
                        </div>
                        <div class="level-item has-text-centered">
                            <div>
                            <p class="heading">Likes</p>
                            <p class="title">{$user->getNbLikes()}</p>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>

            <div class="hero-body is-hidden" id="second_tab">
                <div class="container has-text-centered">
HTML
        );
        $i = 0;
        $is_div_closed = true;
        $images = Image::getLastPhotosFromUser($user->getUserId(), 0, 6);
        foreach ($images as $image) {
            $user = User::createFromId($image->getUserId());
            $like = Like::countFromImageId($image->getImageId());
            if ($i === 0) {
                $is_div_closed = false;
                $page->appendContent(<<<HTML
                
                        <div class="columns is-vcentered image_container">
HTML
                );
            }
                $page->appendContent(<<<HTML
                
                            <div class="column is-one-third">
                                <figure class="image is-4by3">
                                    <a href="photo.php?image_id={$image->getImageId()}">
                                        <img src="{$image->getPath()}" alt="gallery image">
                                    </a>
                                </figure>
                            </div>
HTML
                );
            $i++;
            if ($i == 3) {
                $i = 0;
                $is_div_closed = true;
                $page->appendContent(<<<HTML
        
                        </div>
HTML
                );
            }
        }
            $page->appendContent(<<<HTML
                    <div class="column"></div>
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