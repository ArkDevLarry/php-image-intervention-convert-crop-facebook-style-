<?php

    if (isset($_FILES['file'])) {
        if ($_FILES["file"]["name"] != '')
        {
            $test = explode('.', $_FILES["file"]["name"]);
            $ext = end($test);
            $name = rand(100, 999) . '.' . $ext;
            $location = './captured/' . $name;  
            move_uploaded_file($_FILES["file"]["tmp_name"], $location);
            echo '<img src="'.$location.'" height="150" width="225" class="img-thumbnail" />';
        }
    }