<?php

if (session_status() == PHP_SESSION_NONE)
    session_start();

require_once 'include/autoload.include.php';

$page = new Webpage("Photo");

if (isset($_SESSION['user'])) {

    $page->appendContent(<<<HTML

                <div class="hero-body">
                    <div class="container has-text-centered">
                        <div class="columns is-vcentered">
                            <div class="column is-fullWidth">
                                <h1 class="title">Photo editing</h1>
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
                            <h1 class="title">Forbidden</h1>
                            <p>You must be logged in to access this page</p>
                        </div>
                    </div>
                </div>
            </div>
HTML
    );

}

echo $page->toHTML();