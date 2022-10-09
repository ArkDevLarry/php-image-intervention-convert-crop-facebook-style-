<?php

    if ($_SERVER['REQUEST_METHOD']=="POST") {
        $source = $_FILES['file']['tmp_name'];
        $exp = explode(".",$_FILES['file']['name']);
        $ext = end($exp);

        $raw = time().rand(1000,9999);

        $fullpathtoimg = 'images/'.$raw.'.'.$ext;
        move_uploaded_file($source, $fullpathtoimg);

        // header("Location: interface.php?img=".urlencode($fullpathtoimg));
        echo "<script>window.location='interface.php?img=".urlencode($fullpathtoimg)."'</script>";
        // die();
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form enctype="multipart/form-data" method="post">
        <input type="file" name="file">
        <input type="submit" value="Crop Image">
    </form>
</body>
</html>
