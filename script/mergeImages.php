<?php

require_once __DIR__ . '/../include/autoload.include.php';

$json = array('message' => "", 'photo' => "", 'valid' => false);

try {
    if (isset($_POST['data'])) {
        $data = json_decode($_POST['data'], true);
        if (strlen($data['description']) <= 80) {
                $img_data = str_replace('data:image/png;base64,', '', $data['img_data']);
                $img_data = str_replace(' ', '+', $img_data);
                $img_data = base64_decode($img_data);
                $dest = imagecreatefromstring($img_data);
                imagealphablending($dest, true);
                imagesavealpha($dest, true);
                foreach ($data['filters'] as $filter) {
                    if (Filter::isValidPath($filter) === true) {
                        $src = imagecreatefrompng(__DIR__ . '/../' . $filter);
                        imagecopyresized($dest, $src, 0, 0, 0, 0, $data['width'], $data['height'], imagesx($src), imagesy($src));
                        imagedestroy($src);
                    }
                }
                ob_start (); 
                imagepng($dest);
                $final_image_data = ob_get_contents(); 
                ob_end_clean ();
                $final_image_data_base_64 = base64_encode($final_image_data);
                $json['photo'] = 'data:image/png;base64,' . $final_image_data_base_64;
                imagedestroy($dest);
                $json['valid'] = true;
                $json['message'] = "Image added to the list";
                $json['description'] = htmlspecialchars($data['description']);
        } else
            $json['message'] = "Image description is too long, max 80 characters";
    } else
        $json['message'] = "An error occured";
} catch (Exception $e) {
    $json['valid'] = false;
    $json['message'] = $e->getMessage();
    $json['photo'] = "";
    $json['description'] = "";
}

echo json_encode($json);