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
  global $CONFIGURATION;
  
  //Using the right decimal mark for our output
  $ratingValue = str_replace('.', $CONFIGURATION['DecimalMark'][$usrLang], $ratingValue);
  
  return $ratingValue;
}

//Function to get the image string
function getImageString($ratingValue, $usrStarImage, $usrMaxStars, $usrStarText, $usrPreviewImg){
  $usrStarSize = get_option('usrStarSize');
  
  //Just in case it is not done yet...
  $ratingValue = getUsableRating($ratingValue, $usrMaxStars);
  $formattedRatingValue = getFormattedRating($ratingValue);
  if ($usrPreviewImg == "true"){
    $imageString = '<img class="usr" src="'.content_url().'/plugins/universal-star-rating/images/stars.php?img='.$usrStarImage.'&amp;px='.$usrStarSize.'&amp;max='.$usrMaxStars.'&amp;rat='.$ratingValue.'" style="height: '.$usrStarSize.'px !important;" alt="'.$ratingValue.' Stars" />';
  }else{
    $imageString = '<img class="usr" src="'.content_url().'/plugins/universal-star-rating/images/stars.php?img='.$usrStarImage.'&amp;px='.$usrStarSize.'&amp;max='.$usrMaxStars.'&amp;rat='.$ratingValue.'" alt="'.$ratingValue.' Stars" />';
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

?>