<?php

    function crop_image($file, $ext, $max_resolution)
    {
        if (file_exists($file)) {
            if ($ext=="png") {
                $original_image = @imagecreatefrompng($file);
            }elseif ($ext=="jpeg" || $ext=="jpg") {
                $original_image = imagecreatefromjpeg($file);
            }elseif ($ext=="gif") {
                $original_image = imagecreatefromgif($file);
            }elseif ($ext=="webp"){
                $original_image = imagecreatefromwebp($file);
            }else {
                $ext = 'jpeg';
                $original_image = imagecreatefromjpeg($file);
            }

            //Get orientation information if file is jpg
            $orientation = 0;
            if ($meta_data = exif_read_data($file)) {
                if (isset($meta_data['Orientation'])) {
                    $number = $meta_data['Orientation'];
                    if ($number==3) {
                        $orientation = 180;
                    }elseif ($number==5) {
                        $orientation = -90;
                    }elseif ($number==6) {
                        $orientation = -90;
                    }elseif ($number==7) {
                        $orientation = -90;
                    }elseif ($number==8) {
                        $orientation = 90;
                    }
                }
            }

            //resolution
            $original_width = imagesx($original_image);
            $original_height = imagesy($original_image);

            if ($original_height >$original_width) {
                $ratio = $max_resolution / $original_width;
                $new_width = $max_resolution;
                $new_height = $original_height * $ratio;

                $diff = ($new_height - $new_width)/2;
                $x = 0;
                $y = round($diff);
            }else {
                $ratio = $max_resolution /$original_height;
                $new_height = $max_resolution;
                $new_width = $original_width * $ratio;

                $diff = ($new_width - $new_height)/2;
                $x = round($diff);
                $y = 0;
            }

            if ($original_image) {
                $new_image = imagecreatetruecolor($new_width,$new_height);
                imagecopyresampled($new_image,$original_image,0,0,0,0,$new_width,$new_height,$original_width,$original_height);

                $new_crop_image = imagecreatetruecolor($max_resolution,$max_resolution);
                imagecopyresampled($new_crop_image,$new_image,0,0,$x,$y,$max_resolution,$max_resolution,$max_resolution,$max_resolution);

                //rotate image if necessary
                if ($orientation!=0) {
                    $new_crop_image = imagerotate($new_crop_image, $orientation, 0);
                }
                
                if ($ext=="png") {
                    imagepng($new_crop_image,$file);
                }elseif ($ext=="jpeg" || $ext=="jpg") {
                    imagejpeg($new_crop_image,$file);
                }elseif ($ext=="gif") {
                    imagegif($new_crop_image,$file);
                }elseif($ext=="webp"){
                    imagewebp($new_crop_image,$file);
                }else {
                    imagejpeg($new_crop_image,$file);
                }
                imagedestroy($new_crop_image);
                imagedestroy($original_image);
            }
        }
    }


    if ($_SERVER['REQUEST_METHOD']=="POST") {
        if (isset($_FILES['image'])) {
            foreach ($_FILES['image']['name'] as $k => $value) {
                $exp = explode('.', $value);
                $ext = end($exp);
                $file = time().rand(1000,9999).'.'.$ext;
                move_uploaded_file($_FILES['image']['tmp_name'][$k], $file);

                crop_image($file, $ext, 500);
                echo "<img src='$file'>";
            }
        }
    }

?>

<form method="POST" enctype="multipart/form-data">
    <input type="file" name="image[]" multiple id="">
    <input type="submit" value="Process Image">
</form>