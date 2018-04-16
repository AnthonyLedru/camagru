<?php

if (session_status() == PHP_SESSION_NONE)
    session_start();

require_once 'include/autoload.include.php';

$page = new Webpage("Image page");
$images = Image::getAll();

if (isset($_GET['image_id']) && (($image = Image::CreateFromId($_GET['image_id'])) !== false)) {
    $user = User::createFromLogin($_SESSION['user']['login']);
    $like = Like::countFromImageId($image->getImageId());
    $comments = Comment::getAllFromImage($image->getImageId());
    $page->appendContent(<<<HTML
    
            <div class="hero-body">
                <div class="container has-text-centered">
                    <p class="title is-1">Image page</p>
                    <br>
                    <div class="columns">
                        <div class="column is-half">
                            <div class="card image_card_id image_desc_card">
                                <div class="card-image">
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
                                            <p class="title is-4">{$user->getFullName()}</p>
                                            <p class="subtitle is-6">@{$user->getLogin()}</p>
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
        if (Like::hasUserLiked($user->getUserId(), $image->getImageId()))
            $page->appendContent(<<<HTML

                                                <span class="like"> like(s) 💔</span>
HTML
            );
        else
            $page->appendContent(<<<HTML

                                                <span class="like"> like(s) ❤️</span>
HTML
            );
        $page->appendContent(<<<HTML
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="column is-half">
                            <div class="card episode image_card_id">
                                <header class="card-header">
                                    <p class="card-header-title">Comments</p>
                                    <a href="#" class="card-header-icon" aria-label="more options">
                                    <span class="icon">
                                        <i class="fas fa-angle-down" aria-hidden="true"></i>
                                    </span>
                                    </a>
                                </header>
                                <div class="card-content comment_card">
                                    <div class="content">
HTML
        );
    foreach ($comments as $comment) {
        $page->appendContent(<<<HTML
             <p>{$comment->getComment()}</p>
HTML
        );
    }
        $page->appendContent(<<<HTML
                                    </div>
                                </div>
                                <footer class="card-footer">
                                    <div class="card-footer-item columns">
                                        <div class="column is-four-fifths-desktop">
                                            <input placeholder="Comment..." class="input" name="comment" type"text">
                                        </div>
                                        <div class="column">
                                            <button class="button is-dark send_message" type="submit">Send</button>
                                        </div>
                                    </div>
                                </footer>
                                
                            </div>
                        </div>
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