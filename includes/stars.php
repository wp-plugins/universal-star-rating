<?php

function LoadPNG($imgname)
{
    /* Attempt to open */
    $im = @imagecreatefrompng($imgname);

    /* See if it failed */
    if(!$im)
    {
        /* Create a blank image */
        $im  = imagecreatetruecolor(150, 30);
        $bgc = imagecolorallocate($im, 255, 255, 255);
        $tc  = imagecolorallocate($im, 0, 0, 0);

        imagefilledrectangle($im, 0, 0, 150, 30, $bgc);

        /* Output an error message */
        imagestring($im, 1, 5, 5, 'Error loading ' . $imgname, $tc);
    }

    return $im;
}


header('Content-Type: image/png');

if(isset($_GET['t'])){
  $imgPrefix = $_GET['t'];
} else {
  $imgPrefix = "1";
}

$img_back = LoadPNG('../images/'.$imgPrefix.'_dark.png');
$img_fore = LoadPNG('../images/'.$imgPrefix.'_bright.png');

list($width, $height) = getimagesize("../images/".$imgPrefix."_bright.png");

$offset_x = 0;
$offset_y = 0;

$new_height = $height;
$new_width = $width;

if(isset($_GET['r'])){
  $new_width = $_GET['r']*$width/10;
} else {  
  $new_width = 0;
}

imagecopy($img_back, $img_fore, 0, 0, $offset_x, $offset_y, $new_width, $height);

imagepng($img_back);
imagedestroy($img_back);
imagedestroy($img_fore);

?>