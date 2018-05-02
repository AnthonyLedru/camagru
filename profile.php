<?php

if (session_status() == PHP_SESSION_NONE)
    session_start();

require_once 'include/autoload.include.php';

try {
    $page = new Webpage("Profile");
    $page->appendJsUrl('js/tabs.js');
    $page->appendJsUrl('js/profile.js');
    $error = false;
    if (isset($_GET['user_id'])) {
        if (($user = User::createFromId($_GET['user_id'])) !== false) {
            if (($profilePhoto = Image::createFromId($user->getImageId())) !== false)
                $profilePhotoPath = $profilePhoto->getPath();
            else
                $profilePhotoPath = "img/defaultProfile.png";
            $nbFollowing = Follow::getNbFollowing($user->getUserId());
            $nbFollower = Follow::getNbFollower($user->getUserId());
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
                                    <img src="{$profilePhotoPath}" id="profile_img" alt="profile photo">
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
            if (isset($_SESSION['user']) && ($_SESSION['user']['userId'] !== $_GET['user_id'])) {
                $page->appendContent(<<<HTML

                        <br>
                        <div class="control">
                            <div class="tags has-addons is-horizontal-center">
                                <span class="tag follow_tag is-primary">Follow</span>
HTML
            );
                if (isset($_SESSION['user']) && Follow::hasFollow($_SESSION['user']['userId'], $user->getUserId()))
                    $page->appendContent(<<<HTML

                                <span class="tag follow_tag is-success">Yes</span>
HTML
                    );
                else
                    $page->appendContent(<<<HTML

                                <span class="tag follow_tag is-danger">No</span>
                                
HTML
                    );
                $page->appendContent(<<<HTML

                            </div>
                        </div>
HTML
            );
            }
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
                                <p class="title" id="photos">{$user->getNbPhotos()}</p>
                                </div>
                            </div>
                            <div class="level-item has-text-centered">
                                <div>
                                <p class="heading">Following</p>
                                <p class="title" id="following">{$nbFollowing}</p>
                                </div>
                            </div>
                            <div class="level-item has-text-centered">
                                <div>
                                <p class="heading">Followers</p>
                                <p class="title" id="follower">$nbFollower</p>
                                </div>
                            </div>
                            <div class="level-item has-text-centered">
                                <div>
                                <p class="heading">Likes</p>
                                <p class="title" id="like">{$user->getNbLikes()}</p>
                                </div>
                            </div>
                        </nav>
                    </div>
                </div>

                <div class="hero-body is-hidden" id="second_tab">
                    <div class="container has-text-centered" id="image_container">
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
                    
                            <div class="columns is-vcentered image_columns is-centered">
HTML
                    );
                }
                    $page->appendContent(<<<HTML
                    
                                <div class="column is-one-third">
                                    <figure class="image is-500x500">
                                        <a href="photo.php?image_id={$image->getImageId()}">
                                            <img src="{$image->getPath()}" alt="profile image">
                                        </a>
                                    </figure>
                                </div>
HTML
                    );
                $i++;
                if ($i === 3) {
                    $i = 0;
                    $is_div_closed = true;
                    $page->appendContent(<<<HTML
            
                            </div>
HTML
                    );
                }
            }

            if ($is_div_closed === false)
            $page->appendContent(<<<HTML
        
                    </div>
HTML
            );
            $page->appendContent(<<<HTML

                    </div>
                </div>

                <div class="modal" id="follower_modal">
                    <div class="modal-background"></div>
                        <div class="modal-card">
                            <header class="modal-card-head">
                                <p class="modal-card-title">{$user->getLogin()}'s followers</p>
                                <button class="delete follower_cancel" aria-label="close"></button>
                            </header>
                            <section class="modal-card-body" id="follower_modal_body">
HTML
            );
            if (($followers = Follow::getFollowers($user->getUserId())) !== false) {
                foreach ($followers as $follow) {
                    if (($userFollowing = User::createFromId($follow->getUserIdFollower())) !== false) {
                        $page->appendContent(<<<HTML

                            <p><a class="link" href="profile.php?user_id={$userFollowing->getUserId()}">@{$userFollowing->getLogin()}</a></p>
HTML
                        );
                    }
                }
            } else {
                $page->appendContent(<<<HTML
                
                    <p>{$user->getLogin()} has no followers</p>
HTML
                );
            }
            $page->appendContent(<<<HTML

                            </section>
                            <footer class="modal-card-foot">
                                <button class="button follower_cancel is-dark">Cancel</button>
                            </footer>
                        </div>
                    </div>
                </div>

                <div class="modal" id="following_modal">
                    <div class="modal-background"></div>
                        <div class="modal-card">
                            <header class="modal-card-head">
                                <p class="modal-card-title">{$user->getLogin()}'s following</p>
                                <button class="delete following_cancel" aria-label="close"></button>
                            </header>
                            <section class="modal-card-body">
HTML
            );
            if (($followers = Follow::getFolloweds($user->getUserId())) !== false) {
                foreach ($followers as $follow) {
                    if (($userFollowed = User::createFromId($follow->getUserIdFollowed())) !== false) {
                        $page->appendContent(<<<HTML

                            <p><a class="link" href="profile.php?user_id={$userFollowed->getUserId()}">@{$userFollowed->getLogin()}</a></p>
HTML
                        );
                    }
                }
            } else {
                $page->appendContent(<<<HTML
                
                    <p>{$user->getLogin()} follow nobody</p>
HTML
                );
            }
            $page->appendContent(<<<HTML
                            </section>
                            <footer class="modal-card-foot">
                                <button class="button following_cancel is-dark">Cancel</button>
                            </footer>
                        </div>
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
} catch (Exception $e) {
    $page = new WebPageError("Error");
    $page->appendError("{$e->getMessage()}");
}

echo $page->toHTML();