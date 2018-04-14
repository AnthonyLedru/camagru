<?php

if (session_status() == PHP_SESSION_NONE)
session_start();

require_once 'include/autoload.include.php';

$page = new Webpage("Galery");

$page->appendContent(<<<HTML

            <div class="hero-body">
                <div class="container has-text-centered">
                    <div class="columns is-vcentered">
                        <div class="column is-fullWidth">
                            <h1 id="galery_title">Last photos</h1>
                        </div>
                    </div>
                </div>
            </div>

HTML
);

echo $page->toHTML();