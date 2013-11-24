<?php

header("Content-type: text/css");

//read the needed information - if not set use defaults
if(isset($_GET['px'])){
  $usrStarSize = $_GET['px'];
} else {
  $usrStarSize = "12";
}

?>

img.usr {
  padding:  0;
  margin:   0;
  border:   none;
  display:  inline;
  height:   <?php echo $usrStarSize; ?>px;
}

table.usr {
  border: 0px;
}