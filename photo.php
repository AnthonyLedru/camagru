<?php

if (session_status() == PHP_SESSION_NONE)
    session_start();

require_once 'include/autoload.include.php';

$page = new Webpage("Photo page");
$page->appendJsUrl('js/photo.js');

if (isset($_GET['image_id']) && (($image = Image::CreateFromId($_GET['image_id'])) !== false)) {
    if (isset($_SESSION['user']))
        $currentUser = User::createFromLogin($_SESSION['user']['login']);
    else
        $currentUser = null;
    $imageUser = User::createFromId($image->getUserId());
    $like = Like::countFromImageId($image->getImageId());
    $comments = Comment::getAllFromImage($image->getImageId());
    $page->appendContent(<<<HTML
    
            <div class="hero-body">
                <div class="container has-text-centered">
                    <p class="title is-1">Photo page</p>
                    <br>
                    <div class="columns">
                        <div class="column is-half">
                            <div class="card image_card_id image_desc_card">
                                <div class="card">
                                    <figure class="image is-4by3">
                                        <img src="{$image->getPath()}" alt="galery image">
                                    </figure>
                                </div>
                                <div class="card-content">
                                    <div class="media">
                                        <div class="media-left">
                                            <figure class="image is-48x48">
                                                <img src="https://bulma.io/images/placeholders/96x96.png" alt="Placeholder image">
                                            </figure>
                                        </div>
                                        <div class="media-content">
                                            <p class="title is-4">{$imageUser->getFullName()}</p>
                                            <p class="subtitle is-6"><a class="link" href="profile.php?user_id={$imageUser->getUserId()}">@{$imageUser->getLogin()}</a></p>
                                        </div>
                                    </div>
                                    <div class="content">
                                        <i>{$image->getDescription()}</i>
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
HTML
        );
        if ($currentUser !== null && $currentUser->getUserId() === $image->getUserId()) {
            $page->appendContent(<<<HTML
                                        <div class="is-right">
                                            <a class="link" id="delete_photo_link">Delete this photo</a>
                                        </div>
HTML
            );
        }
        $page->appendContent(<<<HTML
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="column is-half">
                            <div class="card image_card_id">
                                <header class="card-header">
                                    <p class="card-header-title">Comments</p>
                                </header>
                                <div class="card-content" id="comment_card">
                                    <div class="content" id="list_comment">
HTML
        );
    $i = 0;
    foreach ($comments as $comment) {
        if ($i % 2 == 0)
            $class = '';
        else
            $class = 'class="div_gray"';
        $user = User::createFromId($comment->getUserId());
        $page->appendContent(<<<HTML

                                        <div $class>
                                            <p>
                                                <span class="name_date"><a class="link" href="profile.php?user_id={$imageUser->getUserId()}">@{$user->getLogin()}</a> ({$comment->getDate()}) :</span>
                                                <br>
                                                <i>{$comment->getComment()}</i>
                                            </p>
                                        </div>

HTML
        );
        $i++;
    }
        $page->appendContent(<<<HTML
                                    </div>
                                </div>
                                <footer class="card-footer">
                                    <form id="send_message_photo">
                                        <div class="card-footer-item columns">
                                            <div class="column is-four-fifths-mobile is-four-fifths-tablet is-four-fifths-desktop">
                                                <input name="imageId" value="{$image->getImageId()}" type="hidden">
                                                <input placeholder="Comment..." class="input" name="comment" type"text">
                                            </div>
                                            <div class="column">
                                                <button class="button is-dark send_message" type="submit">Send</button>
                                            </div>
                                        </div>
                                    </form>
                                </footer>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="modal" id="delete_photo_modal">
                <div class="modal-background"></div>
                    <div class="modal-card">
                        <header class="modal-card-head">
                            <p class="modal-card-title">Delete photo</p>
                            <button class="delete delete_cancel" aria-label="close"></button>
                        </header>
                        <section class="modal-card-body">
                            <input value="{$image->getImageId()}" id="image_id" hidden></input>
                            <p class="subtitle">Are you sure that you want to delete this photo ?</p>
                            <p>* This action is irreversible</p>
                        </section>
                        <footer class="modal-card-foot">
                            <button class="button is-dark" type="submit" id="delete_photo_button">Delete</button>
                            <button class="button delete_cancel is-dark">Cancel</button>
                        </footer>
                    </div>
                </div>
            </div>
            
HTML
    );
} else {
        
    $page->appendContent(<<<HTML
    
                <div class="hero-body">
                    <div class="container has-text-centered">
                        <div class="columns is-vcentered">
                            <div class="column is-fullWidth">
                                <h1 class="title">Image not found</h1>
                            </div>
                        </div>
                    </div>
                </div>
HTML
        );
}

echo $page->toHTML();