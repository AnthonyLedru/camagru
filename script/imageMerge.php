<?php

require_once __DIR__ . '/../include/autoload.include.php';

$json = array('message' => "", 'photo' => "");
if (isset($_POST['data'])) {
    $data = json_decode($_POST['data'], true);
    $img_data = str_replace('data:image/png;base64,', '', $data['img_data']);
    $img_data = str_replace(' ', '+', $img_data);
    $img_data = base64_decode($img_data);
    $dest = imagecreatefromstring($img_data);
    imagealphablending($dest, false);
    imagesavealpha($dest, true);

    $json['message'] = $data['filters'];
    /*imagecopymerge($dest, $src, 10, 9, 0, 0, 181, 180, 100);

    
    imagepng($dest);
    
    imagedestroy($dest);
    imagedestroy($src);*/
    ob_start (); 
    imagejpeg ($dest);
    $final_image_data = ob_get_contents (); 
    ob_end_clean (); 
    $final_image_data_base_64 = base64_encode($final_image_data);
    $json['photo'] = $final_image_data_base_64;
    $json['message'] = "Image added";
} else {
    $json['message'] = "An error occured";
}

echo json_encode($json);