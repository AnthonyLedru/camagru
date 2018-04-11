<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

require_once 'include/autoload.include.php';

$page = new Webpage("Camagru");
$page->appendContent(<<<HTML

HTML
);

echo $page->toHTML();