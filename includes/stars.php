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

//read the needed information - if not set use defaults
if(isset($_GET['img'])){
  $imgName = $_GET['img'];
} else {
  $imgName = "01.png";
}
if(isset($_GET['max'])){
  $imgCount = $_GET['max'];
} else {
  $imgCount = "10";
}
if(isset($_GET['rat'])){
  $imgRating = $_GET['rat'];
} else {  
  $imgRating = 0;
}

//load the source image
$imgTemp = LoadPNG('../images/'.$imgName);

//set x and y for temp images
$imgWidth = imagesx($imgTemp)/2;
$imgHeight = imagesy($imgTemp);

//create an output image with transparent background
$imgOutput = imagecreate($imgWidth*$imgCount, $imgHeight);
$black = imagecolorallocate($imgOutput, 0, 0, 0);
imagecolortransparent($imgOutput, $black);

//create two temp images (1 bright / 1 dark) with transparent background
$imgTempFore = imagecreate($imgWidth, $imgHeight);
$imgTempBack = imagecreate($imgWidth, $imgHeight);
$black = imagecolorallocate($imgTempFore, 0, 0, 0);
imagecolortransparent($imgTempFore, $black);
$black = imagecolorallocate($imgTempBack, 0, 0, 0);
imagecolortransparent($imgTempBack, $black);

//insert source image parts into the temp images
imagecopy($imgTempFore, $imgTemp, 0, 0, 0, 0, $imgWidth, $imgHeight);
imagecopy($imgTempBack, $imgTemp, 0, 0, $imgWidth, 0, $imgWidth*2, $imgHeight);

//set helper variables to fill the stars correct
$roundedImgCount = floor($imgRating);
$moduloImgCount = fmod($imgRating, 1);

//be Picasso
for ($i = 0; $i <= $imgCount; $i++) {
  if($i < $roundedImgCount){
    imagecopy($imgOutput, $imgTempFore, $i*$imgWidth, 0, 0, 0, $imgWidth, $imgHeight);
  } else {
    imagecopy($imgOutput, $imgTempBack, $i*$imgWidth, 0, 0, 0, $imgWidth, $imgHeight);
  }
}
imagecopy($imgOutput, $imgTempFore, $roundedImgCount*$imgWidth, 0, 0, 0, $imgWidth*$moduloImgCount, $imgHeight);

//output and destroy
imagepng($imgOutput);
imagedestroy($imgTemp);
imagedestroy($imgTempFore);
imagedestroy($imgTempBack);
imagedestroy($imgOutput);

?>