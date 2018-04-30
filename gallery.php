<?php

if (session_status() == PHP_SESSION_NONE)
    session_start();

require_once 'include/autoload.include.php';

$page = new Webpage("Gallery");
$page->appendJsUrl('js/gallery.js');
$images = Image::getLastPhotos(0, 6);
$page->appendContent(<<<HTML

            <div class="hero-body">
                <div class="container has-text-centered">
                    <p class="title is-1">Last photos</p>
                    <br>
HTML
);
if (isset($_SESSION['user']))
    $currentUser = User::createFromLogin($_SESSION['user']['login']);
else
    $currentUser = null;
$i = 0;
$is_div_closed = true;
foreach ($images as $image) {
    $user = User::createFromId($image->getUserId());
    $like = Like::countFromImageId($image->getImageId());
    if (($profilePhoto = Image::createFromId($user->getImageId())) !== false)
        $profilePhotoPath = $profilePhoto->getPath();
    else
        $profilePhotoPath = "img/defaultProfile.png";
    if ($i == 0) {
        $is_div_closed = false;
        $page->appendContent(<<<HTML
        
                    <div class="columns is-vcentered card_container is-centered">
HTML
        );
    }
        $page->appendContent(<<<HTML
        
                        <div class="column is-one-third">
                            <div class="card">
                                <div class="card-image">
                                    <figure class="image is-600x600">
                                        <a href="photo.php?image_id={$image->getImageId()}">
                                            <img src="{$image->getPath()}" alt="gallery image">
                                        </a>
                                    </figure>
                                </div>
                                <div class="card-content">
                                    <div class="media">
                                        <div class="media-left">
                                            <figure class="image is-48x48">
                                                <img class="user_thumbnail" src="{$profilePhotoPath}"  alt="Placeholder image">
                                            </figure>
                                        </div>
                                        <div class="media-content">
                                            <p class="title is-4">{$user->getFullName()}</p>
                                            <p class="subtitle is-6"><a class="link" href="profile.php?user_id={$user->getUserId()}">@{$user->getLogin()}</a></p>
                                        </div>
                                    </div>
                                    <div class="content">
                                        <i class="italic_desc">{$image->getDescription()}</i>
                                        <br><br>
                                        <div class="columns">
                                            <div class="column">
                                                <time>{$image->getDate()}</time>
                                            </div>
                                            <div class="column">
                                                <input name="imageId" value="{$image->getImageId()}" type="hidden">
                                                <span>$like</span>
HTML
        );
        if ($currentUser === null)
            $page->appendContent(<<<HTML

                                                <span class="like" title="Like it"> like(s) ❤️</span>
HTML
        );
        else if (Like::hasUserLiked($currentUser->getUserId(), $image->getImageId()))
            $page->appendContent(<<<HTML

                                                <span class="like" title="Unlike it"> like(s) 💔</span>
HTML
        );
        else
            $page->appendContent(<<<HTML

                                                <span class="like" title="Like it"> like(s) ❤️</span>
HTML
        );
        $page->appendContent(<<<HTML

                                            </div>
                                        </div>
                                        <form class="send_message">
                                            <div class="columns is-desktop">
                                                <div class="column is-four-fifths-desktop">
                                                    <input name="imageId" value="{$image->getImageId()}" type="hidden">
                                                    <input placeholder="Comment..." class="input" name="comment" type"text">
                                                </div>
                                                <div class="column">
                                                    <button class="button is-dark" type="submit">Send</button>
                                                </div>
                                            </div>
                                        </form>
                                        <a href="photo.php?image_id={$image->getImageId()}" class="link comment_link">See comments</a>
                                    </div>
                                </div>
                            </div>
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
HTML
);

echo $page->toHTML();