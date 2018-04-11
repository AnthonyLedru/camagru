<?php
require_once 'include/autoload.include.php';


$page = new Webpage("Camagru");

$page->appendContent(<<<HTML


HTML
);

echo $page->toHTML();