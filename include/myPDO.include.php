<?php

require_once __DIR__ . '/autoload.include.php';
require_once __DIR__ . '/../config/database.php';

myPDO::setConfiguration($DB_DSN, $DB_USER, $DB_PASSWORD);