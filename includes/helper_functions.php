<?php

//############################################################################//
//                                                                            //
//                              Helper Functions                              //
//                                                                            //
//############################################################################//

//Function to get the right rating to calculate with
function getUsableRating($ratingValue, $usrMaxStars){

  //just in case someone used a ',' for the rating
  $ratingValue = str_replace(',', '.', $ratingValue);

  if (!$ratingValue || !is_numeric($ratingValue)){
    $ratingValue = 0;
  }

  //If rating value is higher than max it will be set to max
  if ($ratingValue > $usrMaxStars){
    $ratingValue = $usrMaxStars;
  //...and if it's below 0 or not set it's set to 0
  } elseif ($ratingValue < 0) {
    $ratingValue = 0;
  }
  
  return $ratingValue;
}

//Function to get the right formatted value
function getFormattedRating($ratingValue){
  $usrLang = get_option('usrLang');
  if($usrLang == ''){$usrLang='en';}

  global $CONFIGURATION;
  
  //Using the right decimal mark for our output
  $ratingValue = str_replace('.', $CONFIGURATION['DecimalMark'][$usrLang], $ratingValue);
  
  return $ratingValue;
}

//Function to get ready to use attributes
function getReadyToUseAttributes($atts){
  //Read default settings
  $usrMaxStars = get_option('usrMaxStars');
  $usrStarImage = get_option('usrStarImage');
  $usrStarText = get_option('usrStarText');
  $usrCalcAverage = get_option('usrCalcAverage');
  $usrStarSize = get_option('usrStarSize');
  $usrPreviewImg = get_option('usrStarImage');
    
  //If key 'img' is set the image type will be overridden
  if (isset($atts['img'])) {
    $usrStarImage = $atts['img'];
    unset($atts['img']);
  }
  //If key 'max' is set the max star count will be overridden
  if (isset($atts['max']) && is_numeric($atts['max'])) {
    $usrMaxStars = intval($atts['max']);
    unset($atts['max']);
  }
  //if key 'text' is set to 'true' or 'false' it is possible to override default
  if (isset($atts['text']) && ($atts['text'] == "false" || $atts['text'] == "true")) {
    $usrStarText = $atts['text'];
    unset($atts['text']);
  }
  //if key 'avg' is set to 'true' or 'false' it is possible to override default
  if (isset($atts['avg']) && ($atts['avg'] == "false" || $atts['avg'] == "true")) {
    $usrCalcAverage = $atts['avg'];
    unset($atts['avg']);
  }
  //if key 'size' is set the star size will be overridden
  if (isset($atts['size']) && is_numeric($atts['size'])) {
    $usrStarSize = intval($atts['size']);
    unset($atts['size']);
  }
  //if key 'usrPreviewImg' is set to 'true' or 'false' it is possible to override default
  if (isset($atts['usrPreviewImg']) && ($atts['usrPreviewImg'] == "false" || $atts['usrPreviewImg'] == "true")) {
    $usrPreviewImg = $atts['usrPreviewImg'];
    unset($atts['usrPreviewImg']);
  }

  //Define array which keeps the attributes
  $attributeArray = array("usrMaxStars" => $usrMaxStars, "usrStarImage" => $usrStarImage, "usrStarText" => $usrStarText, "usrCalcAverage" => $usrCalcAverage, "usrStarSize" => $usrStarSize, "usrPreviewImg" => $usrPreviewImg);
  return array("usrAtts" => $atts, "usrAttributeArray" => $attributeArray);
}

//Function to get the image string
function getImageString($ratingValue, $usrStarImage, $usrMaxStars, $usrStarText, $usrPreviewImg, $usrStarSize){
  
  //Just in case it is not done yet...
  $ratingValue = getUsableRating($ratingValue, $usrMaxStars);
  $formattedRatingValue = getFormattedRating($ratingValue);
  if ($usrPreviewImg == "true"){
    $imageString = '<img class="usr" src="'.content_url().'/plugins/universal-star-rating/images/stars.php?img='.$usrStarImage.'&amp;px='.$usrStarSize.'&amp;max='.$usrMaxStars.'&amp;rat='.$ratingValue.'" style="height: '.$usrStarSize.'px !important;" alt="'.$ratingValue.' Stars" />';
  }else{
    $imageString = '<img class="usr" src="'.content_url().'/plugins/universal-star-rating/images/stars.php?img='.$usrStarImage.'&amp;px='.$usrStarSize.'&amp;max='.$usrMaxStars.'&amp;rat='.$ratingValue.'"';
    if($usrStarSize != get_option('usrStarSize')){
      $imageString = $imageString.' style="height: '.$usrStarSize.'px !important;"';
    }
    $imageString = $imageString.' alt="'.$ratingValue.' Stars" />';
  }
  if ($usrStarText == "true"){
    $imageString .= ' ('.$formattedRatingValue.' / '.$usrMaxStars.')';
  }

  return $imageString;
}

//Add filter if shortcodes are allowed inside comments
function permitShortcodedComments(){
  add_filter( 'comment_text', 'shortcode_unautop');
  add_filter( 'comment_text', 'do_shortcode' );
}

//Add stylesheet to the page
function safelyAddStylesheet() {
  $usrStarSize = get_option('usrStarSize');
  wp_enqueue_style( 'usr-style', plugins_url('usr_style.php?px='.$usrStarSize, __FILE__) );
}
add_action('init', 'safelyAddStylesheet');

?>