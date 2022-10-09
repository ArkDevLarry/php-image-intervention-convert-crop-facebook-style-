<?php

    
    // function resize_image($pathtofile, $max_resolution)
    // {
    //     if (file_exists($pathtofile)) {
    //         $original_image = imagecreatefromwebp($pathtofile);

    //         //resolution
    //         $original_width = imagesx($original_image);
    //         $original_height = imagesy($original_image);

    //         $ratio = $max_resolution / $original_width;
    //         $new_width = $max_resolution;
    //         $new_height = $original_height*$ratio;

    //         if ($new_height > $max_resolution) {
    //             $ratio = $max_resolution / $original_height;
    //             $new_height = $max_resolution;
    //             $new_width = $original_width * $ratio;
    //         }

    //         if ($original_image) {
    //             $new_image = imagecreatetruecolor($new_width,$new_height);
    //             imagecopyresampled($new_image,$original_image,0,0,0,0,$new_width,$new_height,$original_width,$original_height);
    //             imagewebp($new_image,$pathtofile,40);
    //         }
    //     }
    // }

    function resize_image($pathtofile, $max_resolution)
    {
        if (file_exists($pathtofile)) {
            $original_image = imagecreatefromwebp($pathtofile);

            //resolution
            $original_width = imagesx($original_image);
            $original_height = imagesy($original_image);

            $ratio = $max_resolution / $original_width;
            $new_width = $max_resolution;
            $new_height = $original_height * $ratio;

            if ($new_height > $max_resolution) {
                $ratio = $max_resolution / $original_height;
                $new_height = $max_resolution;
                $new_width = $original_width * $ratio;
            }

            if ($original_image) {
                $new_image = imagecreatetruecolor($new_width,$new_height);
                imagecopyresampled($new_image,$original_image,0,0,0,0,$new_width,$new_height,$original_width,$original_height);
                imagewebp($new_image,$pathtofile,100);
            }
        }
    }

    function convertToWebP($rawN, $ext, $dir)
    {
        $image = @imagecreatefromstring(file_get_contents($dir.$rawN.'.'.$ext));
        ob_start();

        if ($ext=="png") {
            imagepng($image,NULL,9);
        }elseif ($ext=="jpeg" || $ext=="jpg") {
            imagejpeg($image,NULL,100);
        }elseif ($ext=="gif") {
            imagegif($image,NULL);
        }else {
            return false;
        }

        $cont = ob_get_contents();
        ob_end_clean();
        imagedestroy($image);
        $content = imagecreatefromstring($cont);
        $saveTo = $dir.$rawN.'.webp';
        imagewebp($content,$saveTo);
        imagedestroy($content);
        
    }





    if ($_SERVER['REQUEST_METHOD']=="POST") {
        if (isset($_FILES['image'])) {
            foreach ($_FILES['image']['name'] as $key => $value) {
                $exp = explode('.', strtolower($value));
                $ext = end($exp);
                $cTime = time();
                $file = $cTime.'.'.$ext;
                move_uploaded_file($_FILES['image']['tmp_name'][$key], 'images/'.$file);
                if ($ext=="webp") {
                    resize_image($file, $ext, 500);
                }else {
                    array_pop($exp);
                    $raw_name = $cTime;
                    convertToWebP($raw_name, $ext, $dir = 'images/'); //Raw name is filename without extension, Ext is file extension, Dir is directory to upload Converted Image.
                    $fullpathtoimg = 'images/'.$cTime.'.webp';
                    resize_image($fullpathtoimg, 500);
                }
                unlink('images/'.$cTime.'.'.$ext);

                echo "<img src='images/$cTime.webp'>";
            }
        }
    }

?>

<form method="POST" enctype="multipart/form-data">
    <input type="file" name="image[]" multiple id="">
    <input type="submit" value="Process Image">
</form>