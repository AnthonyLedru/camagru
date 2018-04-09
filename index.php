<?php
require_once 'class/myPDO.include.php';
require_once 'class/Webpage.class.php';

$page = new Webpage("Camagru");

$page->appendContent(<<<HTML
    <header class="navbar">
    <section class="navbar-section">
        <a href="#" class="btn btn-link">Docs</a>
        <a href="#" class="btn btn-link">Examples</a>
    </section>
    <section class="navbar-center">
        <img src="img/42.png"></img>
    </section>
    <section class="navbar-section">
        <a href="#" class="btn btn-link">Twitter</a>
        <a href="#" class="btn btn-link">GitHub</a>
    </section>
    </header>
HTML
);

echo $page->toHTML();