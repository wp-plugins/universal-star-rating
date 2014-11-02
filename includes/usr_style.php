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
	padding:  0 !important;
	margin:   0 !important;
	border:   none !important;
	display:  inline !important;
	height:   <?php echo $usrStarSize; ?>px !important;
}

table.usr {
	border: none;
}

span.usr, div.usr {
	display: inline !important;
}

#usrFooter {
	text-align: center;
	color: #888888;
	background-color: #FFFFFC;
	padding: 5px 0;
	margin: 20px 2px 0px;
	border-style: solid;
	border-color: #dadada;
	border-width: 1px 0;
}