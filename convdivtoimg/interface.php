<?php

    if (isset($_GET['img'])) {
        $pathtofile = $_GET['img'];
        echo file_exists($pathtofile) ? '' : '<script>window.history.back()</script>';
    }else {
        echo "<script>window.history.back()</script>";
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Capture Tool</title>
    <script src="html2canvas.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script>
        function doCapture() {
            window.scrollTo(0,0);
            html2canvas(document.getElementById("container")).then(function (canvas) {
                let red = canvas.toDataURL("image/webp", 0.9);

                let file = urlToFile(red);
                let payload = new FormData();
                payload.append('file', file)
                
                $.ajax({
                    url: "save_capture.php",
                    type: "POST",
                    data: payload,
                    datatype: "JSON",
                    cache: false,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        console.log(res);
                    }
                })
            });
        }

        let urlToFile = (url) => {
            let arr = url.split(",")
            let mime = arr[0].match(/:(.*?);/)[1]
            let data = arr[1];

            let dataStr = atob(data)
            let n = dataStr.length;
            let dataArr = new Uint8Array(n)

            while (n--) {
                dataArr[n] = dataStr.charCodeAt(n)
            }

            let file = new File([dataArr], 'File.webp', {type: mime})
            return file;
            // console.log(dataArr);
            // console.log('mime:', mime);
            // console.log('data:', data);
        }
    </script>
    <style>
        #container {
            width: 400px;
            height: 400px;
            /* border: 1px solid black; */
            padding: 0;
            margin: auto;
            overflow: hidden;
        }
        #image {
            position: relative;
            -khtml-user-select: none;
            -o-user-select: none;
            -moz-user-select: none;
            -webkit-user-select: none;
            user-select: none;
        }
        #cropper {
            background-image: url('cropper.png');
            background-size: 100% 100%;
            height: 400px;
            width: 400px;
            position: absolute;
            cursor: move;
        }
        #range {
            margin: auto;
            width: 400px;
            display: block;
        }
        #output {
            margin: auto;
            width: 400px;
        }
    </style>
</head>
<body>
    <!-- <div id="container" onmousedown="mouseDown_on(event)" onmouseup="mouseDown_off(event)" onmouseenter="mouseMove_on(event)" onmouseleave="mouseMove_off(event)"> -->
    <div id="container">
        <img src="<?=$pathtofile?>" id="image" style="">
        <div id="cropper" ondblclick="addMore(event)"></div>
    </div>
    <br>
    <input type="range" min="10" value="10" max="100" id="range" onmousemove="resize_image(event)">
    <br>
    <button onclick="" type="button">Capture </button>
    <br>
    <div id="output"></div>
    <div id="info"></div>






    

<script type="text/javascript">
doCapture()
    // let info = document.getElementById("info");
    // let image = document.getElementById("image");
    // let container = document.getElementById("container");
    // let cropper = document.getElementById("cropper");
    // let range = document.getElementById("range");
    // let output = document.getElementById("output");
    
    // let mouseMove = false;
    // let mouseDown = false;
    // let ratio = 1;
    // let margin = 50;

    // let initMouseX = 0;
    // let initMouseY = 0;
    // let initImageX = 0;
    // let initImageY = 0;

    // cropper.style.top = container.offsetTop + 'px';
    // cropper.style.left = container.offsetLeft + 'px';

    // reset_image();

    // let originalImageWidth = image.clientWidth;
    // let originalImageHeight = image.clientHeight;

    // window.onmousemove = function(event) {

    //     info.innerHTML = event.clientX + ' : ' + event.clientY;

    //     if (mouseMove && mouseDown) {
    //         let x = event.clientX - initMouseX;
    //         let y = event.clientY - initMouseY;

    //         x = initImageX + x;
    //         y = initImageY + y;

    //         if (x > margin) {x = margin}
    //         if (y > margin) {y = margin}

    //         xlimit = container.clientWidth - image.clientWidth - margin;
    //         ylimit = container.clientHeight - image.clientHeight - margin;

    //         if (x < xlimit) {x = xlimit}
    //         if (y < ylimit) {y = ylimit}

    //         image.style.left = x + 'px' ;
    //         image.style.top = y + 'px' ;
    //     }
    // }

    // window.onmouseup = function(event) {
    //     mouseDown = false;
    // }

    // function resize_image() {
    //     let w = image.clientWidth;
    //     let h = image.clientHeight;
    //     image.style.width = (range.value / 10) * originalImageWidth + 'px';
    //     image.style.height = (range.value / 10) * originalImageHeight + 'px';

    //     let w2 = image.clientWidth;
    //     let h2 = image.clientHeight;

    //     if (w - w2 != 0) {
    //         let diffW = (w - w2) / 2;
    //         let diffH = (h - h2) / 2;

    //         x = (image.offsetLeft - container.offsetLeft) + diffW;
    //         y = (image.offsetTop - container.offsetTop) + diffH

    //         if (x > margin) {x = margin}
    //         if (y > margin) {y = margin}

    //         xlimit = container.clientWidth - image.clientWidth - margin;
    //         ylimit = container.clientHeight - image.clientHeight - margin;

    //         if (x < xlimit) {x = xlimit}
    //         if (y < ylimit) {y = ylimit}


    //         image.style.left = x + 'px';
    //         image.style.top = y + 'px';
    //     }
    // }

    // function addMore() {
    //     newRange = range.value + 10;
    //     range.value = 30 ;
    //     resize_image();
    // }

    // function reset_image() {
    //     if (image.naturalWidth > image.naturalHeight) {

    //         ratio = image.naturalWidth / image.naturalHeight;
    //         image.style.width = (container.clientWidth - (margin *2)) * ratio + 'px';
    //         image.style.height = container.clientHeight - (margin *2) + 'px';
    //         let extra = (image.clientWidth - container.clientWidth) / 2;
    //         image.style.top = margin + 'px';
    //         image.style.left = -extra + 'px';
    //     }else {
    //         ratio = image.naturalHeight / image.naturalWidth;
    //         image.style.width = container.clientWidth - (margin *2) + 'px';
    //         image.style.height = (container.clientWidth - (margin *2)) * ratio + 'px';
    //         let extra = (image.clientHeight - container.clientHeight) / 2;
    //         image.style.top = -extra + 'px';
    //         image.style.left = margin + 'px';
    //     }
    //     range.value = 10;
    // }

    // function mouseDown_on(event) {
    //     mouseDown = true;
    //     initMouseX = event.clientX;
    //     initMouseY = event.clientY;
    //     initImageX = image.offsetLeft - container.offsetLeft;
    //     initImageY = image.offsetTop - container.offsetTop;
    // }
    // function mouseDown_off() {
    //     mouseDown = false;
    // }
    // function mouseMove_on() {
    //     mouseMove = true;
    // }
    // function mouseMove_off() {
    //     mouseMove = false;
    // }

    // function crop() {

    //     if (image.naturalWidth > image.naturalHeight) {
    //         ratio = image.naturalHeight / (container.clientHeight - (margin * 2));
    //     }else {
    //         ratio = image.naturalWidth / (container.clientWidth - (margin * 2));
    //     }

    //     let x1 = image.style.left;
    //     x1 = x1.replace("px","");
    //     x1 = x1 - margin;

    //     if (x1 < 0) {x1 = x1 * -1}

    //     let y1 = image.style.top;
    //     y1 = y1.replace("px","");
    //     y1 = y1 - margin;

    //     if (y1 < 0) {y1 = y1 * -1}

    //     let x2 = (x1 + (container.clientWidth - (margin * 2)))
    //     let y2 = (y1 + (container.clientHeight - (margin * 2)))

    //     let width = (x2 - x1) * ratio;
    //     let height = (y2 - y1) * ratio;

    //     x1 = x1 * ratio;
    //     y1 = y1 * ratio;

    //     //For the zoom factor
    //     let zoomFactor = (range.value / 10);
    //     x1 = (x1 / zoomFactor);
    //     y1 = (y1 / zoomFactor);

    //     width = (width / zoomFactor);
    //     height = (height / zoomFactor);
    //     //End rof the zoom factor

    //     let path = "<?php //str_replace("/", "f2pP2p", str_replace(".","st34ps3",$pathtofile))?>";
    //     xhr = new XMLHttpRequest();
    //     xhr.open("GET", "crop.php?img=" + path + "&x=" + x1 + "&y=" + y1 + "&w=" + width + "&h=" + height, true);

    //     xhr.onload = function(){
    //         if (xhr.status == 200) {
    //             output.innerHTML = xhr.responseText
    //         }
    //     }

    //     xhr.send();

    // }
</script>
</body>
</html>