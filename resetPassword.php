<?php

if (session_status() == PHP_SESSION_NONE)
    session_start();

require_once __DIR__ . '/include/autoload.include.php';

if (!isset($_SESSION['user'])) {
    $page = new Webpage("Password Reset");
    if (isset($_GET['token'])) {

    } else {

        $page->appendContent(<<<HTML

                <div class="hero-body">
                    <div class="container">
                        <div class="columns has-text-centered">
                            <div class="column is-fullWidth">
                                <p class="title is-1">Reset your password</p>
                                <br>
                            </div>
                        </div>
                        <div class="columns is-centered has-text-centered is-vcentered">
                            <div class="column is-half">
                                <form name="reset_password_form" id="reset_password_form" method="POST">
                                    <input class="input" name="mail" type="mail" placeholder="Your mail">
                                </form>
                                <br>
                                <button class="button is-dark" form="reset_password_form" type="submit">Send reset password mail</button>
                            </div>
                        </div>
                    </div>
                </div>
HTML
        );
    }

    echo $page->toHTML();
} else {
    header("Location:index.php");
}