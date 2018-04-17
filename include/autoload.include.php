<?php

spl_autoload_register(function($className) {
    if (file_exists(__DIR__ . "/../class/" . $className . ".class.php"))
            require_once(__DIR__ . "/../class/" . $className . ".class.php");
});