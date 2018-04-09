<?php
require_once 'class/myPDO.include.php';
require_once 'class/Webpage.class.php';

$page = new Webpage("Camagru");

$page->appendContent(<<<HTML


HTML
);

echo $page->toHTML();