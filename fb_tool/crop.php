<?php
    if (isset($_GET['img']) && isset($_GET['x']) && isset($_GET['y']) && isset($_GET['w']) && isset($_GET['h'])) {
        $pathtofile = str_replace("f2pP2p", "/", str_replace("st34ps3", ".", $_GET['img']));
        echo file_exists($pathtofile) ? '' : '<script>window.history.back()</script>';
        $newpath = 'cropped/'.rand().'.jpeg';
        $source = imagecreatefromjpeg($pathtofile);
        $dest = imagecreatetruecolor(400, 400);
        
        imagecopyresampled($dest, $source, 0, 0, $_GET['x'], $_GET['y'], 400, 400, $_GET['w'], $_GET['h']);
    
        imagejpeg($dest, $newpath, 100);
    
        echo '<img src="'.$newpath.'?'.rand().'" style="width: 400px;">';

    }else {
        echo "<script>window.history.back()</script>";
    }