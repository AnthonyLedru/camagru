<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

require_once 'include/autoload.include.php';

$page = new Webpage("Camagru");

if (!isset($_SESSION['user'])) {

$page->appendContent(<<<HTML

        <div class="hero-body">
            <div class="container has-text-centered">
                <div class="columns is-vcentered">
                    <div class="column is-5">
                        <figure class="image is-4by3">
                            <img src="img/home_img.jpeg" id="home_img" alt="Home description">
                        </figure>
                    </div>
                    <div class="column is-6 is-offset-1">
                        <h1 class="title is-2">
                            Camagru
                        </h1>
                        <h2 class="subtitle is-4">
                            Share your best moments with your friends
                        </h2>
                        <br>
                        <p class="has-text-centered">
                            <a class="button is-dark" href="galery.php">
                                See galery
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
HTML
);

}

else {

}

echo $page->toHTML();