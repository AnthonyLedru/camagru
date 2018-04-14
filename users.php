<?php

if (session_status() == PHP_SESSION_NONE)
session_start();

require_once 'include/autoload.include.php';

$page = new Webpage("Users");

$page->appendContent(<<<HTML

            <div class="hero-body">
                <div class="container has-text-centered">
                    <div class="columns is-vcentered">
                        <div class="column is-fullWidth">
                            <div class="field">
                                <div class="control is-large is-loading">
                                    <input class="input is-large" type="text" placeholder="Anthony Ledru">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
HTML
);

echo $page->toHTML();