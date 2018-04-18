<?php

if (isset($_POST['data'])) {
    $data = $_POST['data'];
    $path = $_SERVER['DOCUMENT_ROOT'] . "/photos/";
    echo $path;
    $file = md5($path . uniqid() . '.png');

    $uri =  substr($data, strpos($data, ","), 1);

    file_put_contents($file, base64_decode($uri));

    echo json_encode($file);

}