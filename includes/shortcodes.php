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

  //Get all the needed attributes
  $readyToUseAttributes = getReadyToUseAttributes($atts);
  $atts = $readyToUseAttributes["usrAtts"];
  $usrAttributes = $readyToUseAttributes["usrAttributeArray"];

  //If array is empty the rating is '0'
	if (!$atts[0]) {
		$ratingValue = 0;
	} else {
		$ratingValue = str_replace("=", "", $atts[0]);
	}

  //Get the right rating formats
  $ratingValue = getUsableRating($ratingValue, $usrAttributes["usrMaxStars"]);
  
  //Setting up the string with the right picture
  $usr = getImageString($ratingValue, $usrAttributes["usrStarImage"], $usrAttributes["usrMaxStars"], $usrAttributes["usrStarText"], $usrAttributes["usrPreviewImg"], $usrAttributes["usrStarSize"]); 

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

  //Get all the needed attributes
  $readyToUseAttributes = getReadyToUseAttributes($atts);
  $atts = $readyToUseAttributes["usrAtts"];
  $usrAttributes = $readyToUseAttributes["usrAttributeArray"];

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
      $ratingValue = getUsableRating($splittedValue, $usrAttributes["usrMaxStars"]);
      $sumRatingValues = $sumRatingValues + $ratingValue;

      //Setting up the string with the right picture
      $usrlist .= '<tr><td>'.$splittedKey.':</td><td>'.getImageString($ratingValue, $usrAttributes["usrStarImage"], $usrAttributes["usrMaxStars"], $usrAttributes["usrStarText"], $usrAttributes["usrPreviewImg"], $usrAttributes["usrStarSize"]).'</td></tr>';
    }
    
    //If the average is needed...
    if ($usrAttributes["usrCalcAverage"] == "true") {
      $averageRating = getUsableRating(round($sumRatingValues/count($atts), 1), $usrAttributes["usrMaxStars"]);
      $usrlist .= '<tr><td style="border-top:1px solid;">'.$CONFIGURATION['AverageText'][$usrLang].':</td><td style="border-top:1px solid;">'.getImageString($averageRating, $usrAttributes["usrStarImage"], $usrAttributes["usrMaxStars"], $usrAttributes["usrStarText"], $usrAttributes["usrPreviewImg"], $usrAttributes["usrStarSize"]).'</td></tr>';
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