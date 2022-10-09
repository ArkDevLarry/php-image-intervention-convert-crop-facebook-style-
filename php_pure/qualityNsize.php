<?php

    function resize_image($file, $ext, $max_resolution)
    {
        if (file_exists($file)) {
            if ($ext=="png") {
                $original_image = imagecreatefrompng($file);
            }elseif ($ext=="jpg" || $ext=="jpeg") {
                $original_image = imagecreatefromjpeg($file);
            }elseif ($ext=="webp"){
                $original_image = imagecreatefromwebp($file);
            }else {
                return false;
            }

            //resolution
            $original_width = imagesx($original_image);
            $original_height = imagesy($original_image);

            $ratio = $max_resolution / $original_width;
            $new_width = $max_resolution;
            $new_height = $original_height*$ratio;

            if ($new_height > $max_resolution) {
                $ratio = $max_resolution /$original_height;
                $new_height = $max_resolution;
                $new_width = $original_width * $ratio;
            }

            if ($original_image) {
                $new_image = imagecreatetruecolor($new_width,$new_height);
                imagecopyresampled($new_image,$original_image,0,0,0,0,$new_width,$new_height,$original_width,$original_height);
                
                if ($ext=="png") {
                    imagepng($new_image,$file);
                }elseif ($ext=="jpg" || $ext=="jpeg") {
                    imagejpeg($new_image,$file);
                }elseif($ext=="webp"){
                    imagewebp($new_image,$file);
                }else {
                    return false;
                }
            }
        }
    }


    if ($_SERVER['REQUEST_METHOD']=="POST") {
        if (isset($_FILES['image'])) {
            $exp = explode('.', $_FILES['image']['name']);
            $ext = end($exp);
            $file = time().'.'.$ext;
            move_uploaded_file($_FILES['image']['tmp_name'], $file);

            resize_image($file, $ext, 500);
            echo "<img src='$file'>";
        }
    }

?>

<form method="POST" enctype="multipart/form-data">
    <input type="file" name="image" id="">
    <input type="submit" value="Process Image">
</form>