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

if(isset($_GET['m'])){
  $imgCount = $_GET['m'];
} else {
  $imgCount = "10";
}

if(isset($_GET['r'])){
  $imgRating = $_GET['r'];
} else {  
  $imgRating = 0;
}

$imgBackTemp = LoadPNG('../images/'.$imgPrefix.'_dark.png');
$imgForeTemp = LoadPNG('../images/'.$imgPrefix.'_bright.png');

//list($width, $height) = getimagesize("../images/".$imgPrefix."_bright.png");
$imgWidth = imagesx($imgBackTemp);
$imgHeight = imagesy($imgBackTemp);

$imgOutputBackground = imagecreate($imgWidth*$imgCount, $imgHeight);
//$black = imagecolorallocate($imgOutputBackground, 0, 0, 0);
imagecolortransparent($imgOutputBackground, imagecolorallocate($imgOutputBackground, 0, 0, 0));

//be picasso
$roundedImgSize = floor($imgRating);
$moduloImgSize = fmod($imgRating, 1);

for ($i = 0; $i <= $imgCount; $i++) {
  imagecopy($imgOutputBackground, $imgBackTemp, $i*$imgWidth, 0, 0, 0, $imgWidth, $imgHeight);
}
for ($i = 0; $i < $roundedImgSize; $i++) {
  imagecopy($imgOutputBackground, $imgForeTemp, $i*$imgWidth, 0, 0, 0, $imgWidth, $imgHeight);
}
imagecopy($imgOutputBackground, $imgForeTemp, $roundedImgSize*$imgWidth, 0, 0, 0, $imgWidth*$moduloImgSize, $imgHeight);

imagepng($imgOutputBackground);
imagedestroy($imgForeTemp);
imagedestroy($imgBackTemp);
imagedestroy($imgOutputBackground);

?>