<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

require_once 'include/autoload.include.php';

$page = new Webpage("Camagru");
$page->appendJsUrl('js/home.js');
$page->appendContent(<<<HTML

            <div class="hero-body">
                <div class="container has-text-centered">
                    <div class="field" id="search_bar">
                        <div class="control is-large is-loading">
                            <input class="input is-large" id="search_user" type="text" placeholder="Search a user...">
                            <div class="live_search is-hidden" id="live_search_columns"></div>
                        </div>
                    </div>
                    <div class="columns is-vcentered">
                        <div class="column is-5">
                            <figure class="image is-3by3">
                                <img src="img/homeImg.jpeg" id="home_img" alt="Home description">
                            </figure>
                        </div>
                        <div class="column is-6 is-offset-1">
                            <h1 class="title is-2">
                                Camagru
                            </h1>
                            <h2 class="subtitle is-4">
                                Share your best photos
                            </h2>
                            <br>
                            <p class="has-text-centered">
                                <a class="button is-dark" href="gallery.php">
                                    See gallery
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
HTML
);

echo $page->toHTML();