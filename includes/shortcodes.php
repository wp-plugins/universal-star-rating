<?php

//############################################################################//
//                                                                            //
//                                 Shortcodes                                 //
//                                                                            //
//############################################################################//

//----------------------------------------------------------------------------//
//                       Insert function for one rating                       //
//----------------------------------------------------------------------------//

function insertUSR($atts) {

  //Read default settings
  $usrMaxStars = get_option('usrMaxStars');
  $usrStarImage = get_option('usrStarImage');
  $usrStarText = get_option('usrStarText');

  //If key 'img' is set the image type will be overridden
  if ($atts['img']) {
    $usrStarImage = $atts['img'];
    unset($atts['img']);
  }
  //If key 'max' is set the max star count will be overridden
  if ($atts['max'] && is_numeric($atts['max'])) {
    $usrMaxStars = intval($atts['max']);
    unset($atts['max']);
  }
  //if key 'text' is set to 'true' or 'false' it is possible to override default
  if ($atts['text'] == "false" || $atts['text'] == "true") {
    $usrStarText = $atts['text'];
    unset($atts['text']);
  }
  //if key 'avg' is set to 'true' or 'false' it is possible to override default
  if ($atts['avg'] == "false" || $atts['avg'] == "true") {
    $usrCalcAverage = $atts['avg'];
    unset($atts['avg']);
  }
  
  //if key 'usrPreviewImg' is set to 'true' or 'false' it is possible to override default
  if ($atts['usrPreviewImg'] == "false" || $atts['usrPreviewImg'] == "true") {
    $usrPreviewImg = $atts['usrPreviewImg'];
    unset($atts['usrPreviewImg']);
  }

  //If array is empty the rating is '0'
	if (!$atts[0]) {
		$ratingValue = 0;
	} else {
		$ratingValue = str_replace("=", "", $atts[0]);
	}

  //Get the right rating formats
  $ratingValue = getUsableRating($ratingValue, $usrMaxStars);
  
  //Setting up the string with the right picture
  $usr = getImageString($ratingValue, $usrStarImage, $usrMaxStars, $usrStarText, $usrPreviewImg); 

  //Output
  return $usr;
}
add_shortcode('usr', 'insertUSR');


//----------------------------------------------------------------------------//
//                      Insert function for more ratings                      //
//----------------------------------------------------------------------------//

// Insert Rating function for multi rating
function insertUSRList($atts) {
  $usrLang = get_option('usrLang');
  global $MESSAGES, $CONFIGURATION;

  //Read default settings
  $usrMaxStars = get_option('usrMaxStars');
  $usrStarImage = get_option('usrStarImage');
  $usrStarText = get_option('usrStarText');
  $usrCalcAverage = get_option('usrCalcAverage');
    
  //If key 'img' is set the image type will be overridden
  if ($atts['img']) {
    $usrStarImage = $atts['img'];
    unset($atts['img']);
  }
  //If key 'max' is set the max star count will be overridden
  if ($atts['max'] && is_numeric($atts['max'])) {
    $usrMaxStars = intval($atts['max']);
    unset($atts['max']);
  }
  //if key 'text' is set to 'true' or 'false' it is possible to override default
  if ($atts['text'] == "false" || $atts['text'] == "true") {
    $usrStarText = $atts['text'];
    unset($atts['text']);
  }
  //if key 'avg' is set to 'true' or 'false' it is possible to override default
  if ($atts['avg'] == "false" || $atts['avg'] == "true") {
    $usrCalcAverage = $atts['avg'];
    unset($atts['avg']);
  }

  //if key 'usrPreviewImg' is set to 'true' or 'false' it is possible to override default
  if ($atts['usrPreviewImg'] == "false" || $atts['usrPreviewImg'] == "true") {
    $usrPreviewImg = $atts['usrPreviewImg'];
    unset($atts['usrPreviewImg']);
  }

  //If there are more than 1 keys inside the array...
  if(count($atts) > 1){
  
    //set a helper variable to calculate the average
    $sumRatingValues = 0;
    
    //Using a table because it looks better
    $usrlist = '<table class="usr">';

    //For each key/value pair inside the array...
    foreach ($atts as $value) {
      //splitting Key:Value into two variables - User can't use a ':' inside Key
      list($splittedKey, $splittedValue) = explode(":", $value, 2);
      
      //Get the right rating formats
      $ratingValue = getUsableRating($splittedValue, $usrMaxStars);
      $sumRatingValues = $sumRatingValues + $ratingValue;

      //Setting up the string with the right picture
      $usrlist .= '<tr><td>'.$splittedKey.':</td><td>'.getImageString($ratingValue, $usrStarImage, $usrMaxStars, $usrStarText, $usrPreviewImg).'</td></tr>';
    }
    
    //If the average is needed...
    if ($usrCalcAverage == "true") {
      $averageRating = getUsableRating(round($sumRatingValues/count($atts), 1), $usrMaxStars);
      $usrlist .= '<tr><td style="border-top:1px solid;">'.$CONFIGURATION['AverageText'][$usrLang].':</td><td style="border-top:1px solid;">'.getImageString($averageRating, $usrStarImage, $usrMaxStars, $usrStarText, $usrPreviewImg).'</td></tr>';
    }

    //Finishing the table
    $usrlist .= '</table>';

    //Output
    return $usrlist;

  //There is just 1 key:value pair we return the error message
  } else {
    return $MESSAGES['ERROR']['NotEnoughParameters'][$usrLang];
  }
}
add_shortcode('usrlist', 'insertUSRList');

?>