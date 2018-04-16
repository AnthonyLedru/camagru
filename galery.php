<?php

if (session_status() == PHP_SESSION_NONE)
    session_start();

require_once 'include/autoload.include.php';

$page = new Webpage("Galery");
$images = Image::getAll();

$page->appendContent(<<<HTML

            <div class="hero-body">
                <div class="container has-text-centered">
                    <p class="title is-1">Last photos</p>
                    <br>
HTML
);
$i = 0;
foreach ($images as $image) {
    $user = User::createFromId($image->getUserId());
    $like = Like::countFromImageId($image->getImageId());
    if ($i % 3 === 0)
        $page->appendContent(<<<HTML
        
                    <div class="columns is-vcentered">
HTML
);
    var_dump($image->getDate());
        $page->appendContent(<<<HTML
        
                        <div class="column is-one-third">
                            <div class="card">
                                <div class="card-image">
                                    <figure class="image is-4by3">
                                        <a href="image.php?image_id={$image->getImageId()}">
                                            <img src="{$image->getPath()}" alt="galery image">
                                        </a>
                                    </figure>
                                </div>
                                <div class="card-content">
                                    <div class="media">
                                        <div class="media-left">
                                            <figure class="image is-48x48">
                                                <img src="https://bulma.io/images/placeholders/96x96.png"  alt="Placeholder image">
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
                                        <div class="columns is-desktop">
                                            <div class="column is-four-fifths-desktop">
                                                <input placeholder="Comment..." class="input" name="comment" type"text">
                                            </div>
                                            <div class="column">
                                                <button class="button is-dark send_message" type="submit">Send</button>
                                            </div>
                                        </div>
                                        <a href="image.php?image_id={$image->getImageId()}" class="link comment_link">See comments</a>
                                    </div>
                                </div>
                            </div>
                        </div>
HTML
);

    if ($i % 2 === 0 && $i !== 0)
        $page->appendContent(<<<HTML

                    </div>
HTML
);
        $i++;
    }

if ($i % 3 !== 0)
    $page->appendContent(<<<HTML

            </div>
HTML
);

$page->appendContent(<<<HTML

                </div>
            </div>

            <div class="modal" id="comments_modal">
				<div class="modal-background"></div>
					<div class="modal-card">
						<header class="modal-card-head">
							<p class="modal-card-title">Comments</p>
							<button class="delete comments_cancel" aria-label="close"></button>
						</header>
						<section class="modal-card-body">

						</section>
						<footer class="modal-card-foot">
							<button class="button comments_cancel is-dark">Cancel</button>
						</footer>
					</div>
				</div>
			</div>

HTML
);

echo $page->toHTML();