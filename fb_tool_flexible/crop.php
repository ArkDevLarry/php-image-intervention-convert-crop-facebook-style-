<?php





    if (isset($_GET['img']) && isset($_GET['x']) && isset($_GET['y']) && isset($_GET['w']) && isset($_GET['h'])) {

        $pathtofile = str_replace("f2pP2p", "/", str_replace("st34ps3", ".", $_GET['img']));
        echo file_exists($pathtofile) ? '' : '<script>window.history.back()</script>';
        $exp = explode(".", strtolower($pathtofile));


        $ext = end($exp);
        $newpath = 'cropped/'.rand().'.'.$ext;

        if ($ext=="jpg" || $ext=="jpeg") {
            $source = imagecreatefromjpeg($pathtofile);
        }elseif ($ext=="webp") {
            $source = imagecreatefromwebp($pathtofile);
        }elseif ($ext=="gif") {
            $source = imagecreatefromgif($pathtofile);
        }elseif ($ext=="png") {
            $source = imagecreatefrompng($pathtofile);
        }
        

        $dest = imagecreatetruecolor(400, 400);
        
        imagecopyresampled($dest, $source, 0, 0, $_GET['x'], $_GET['y'], 400, 400, $_GET['w'], $_GET['h']);
    

        if ($ext=="jpg" || $ext=="jpeg") {
            imagejpeg($dest, $newpath, 100);
        }elseif ($ext=="webp") {
            imagewebp($dest, $newpath, 100);
        }elseif ($ext=="gif") {
            imagegif($dest, $newpath);
        }elseif ($ext=="png") {
            imagepng($dest, $newpath, 9);
        }
    
        echo '<img src="'.$newpath.'?'.rand().'" style="width: 400px;">';

    }else {
        echo "<script>window.history.back()</script>";
    }