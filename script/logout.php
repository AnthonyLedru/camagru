<?php

if (session_status() == PHP_SESSION_NONE)
    session_start();

if (session_status() == PHP_SESSION_ACTIVE) {
    session_destroy();
    echo "Good bye !";
} else
    echo "You are not connected";
