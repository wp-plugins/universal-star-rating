<?php

/**
 * Helper Functions
 *
 * @package UniversalStarRating
 */

 
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
function getImageString($ratingValue, $usrStarImage, $usrMaxStars, $usrStarText, $usrPreviewImg, $usrStarSize, $usrAggregatedOutput, $usrAggregationCount, $usrCustomImagesFolder){
  
  if(!isset($usrAggregatedOutput) || $usrAggregatedOutput != true)
    $usrAggregatedOutput = false;
  if(!isset($usrCustomImagesFolder))
    $usrCustomImagesFolder = "";
  
  //Just in case it is not done yet...
  $ratingValue = getUsableRating($ratingValue, $usrMaxStars);
  $formattedRatingValue = getFormattedRating($ratingValue);
  
  //Set the string
  $imageString = '';
  
  //Schema.org SEO
  if(get_option('usrSchemaOrg') == "true"){
    //If this is aggregated output from usrlist...
    if($usrAggregatedOutput == false){
      $imageString .= '<div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating" class="usr">';
      $imageString .= '<meta itemprop="worstRating" content="0" />';
      //If text will not be displayed...
      if ($usrStarText != "true"){
        $imageString .= '<meta itemprop="ratingValue" content="'.$ratingValue.'" />';
        $imageString .= '<meta itemprop="bestRating" content="'.$usrMaxStars.'" />';
      }
    //If it is not an aggregated output...
    }elseif($usrAggregatedOutput == true){
      $imageString .= '<div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating" class="usr">';
      if ($usrStarText != "true"){
        $imageString .= '<meta itemprop="ratingValue" content="'.$ratingValue.'" />';
        $imageString .= '<meta itemprop="reviewCount" content="'.$usrAggregationCount.'" />';
      }
    }
  }
  
  //If it is a preview image
  if ($usrPreviewImg == "true"){
    //Set image
    $imageString .= '<img class="usr" src="'.content_url().'/plugins/universal-star-rating/includes/stars.php?img='.$usrStarImage.'&amp;px='.$usrStarSize.'&amp;max='.$usrMaxStars.'&amp;rat='.$ratingValue;
    if($usrCustomImagesFolder != "")
      $imageString .= '&amp;folder='.$usrCustomImagesFolder;
    $imageString .= '" style="height: '.$usrStarSize.'px !important;" alt="'.$ratingValue.' Stars" />';
  //If it is not a preview
  }else{
    //Set image string
    $imageString .= '<img class="usr" src="'.content_url().'/plugins/universal-star-rating/includes/stars.php?img='.$usrStarImage.'&amp;px='.$usrStarSize.'&amp;max='.$usrMaxStars.'&amp;rat='.$ratingValue;
    if($usrCustomImagesFolder != "")
      $imageString .= '&amp;folder='.$usrCustomImagesFolder;
    $imageString .= '"';
    //If star size is not the default there has to be a style attribute...
    if($usrStarSize != get_option('usrStarSize')){
      $imageString .= ' style="height: '.$usrStarSize.'px !important;"';
    }
    $imageString .= ' alt="'.$ratingValue.' Stars" />';
  }
  //If text will be displayed...
  if ($usrStarText == "true"){
    //With schema.org SEO
    if(get_option('usrSchemaOrg') == "true"){
      if($usrAggregatedOutput == false)
        $imageString .= ' (<span itemprop="ratingValue">'.$formattedRatingValue.'</span> / <span itemprop="bestRating">'.$usrMaxStars.'</span>)';
      if($usrAggregatedOutput == true)
        $imageString .= ' (<span itemprop="ratingValue">'.$formattedRatingValue.'</span> / '.$usrMaxStars.')<meta itemprop="reviewCount" content="'.$usrAggregationCount.'" />';
    //Without schema.org SEO
    }else{
      $imageString .= ' ('.$formattedRatingValue.' / '.$usrMaxStars.')';
    }
  }

  //Close schema.org SEO tag
  if(get_option('usrSchemaOrg') == "true"){
    $imageString .= '</div>';
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
  wp_enqueue_style( 'usr-style', plugins_url('usr_style.php?px='.$usrStarSize.'&amp;usrver='.get_option('usrVersion'), __FILE__) );
}
add_action('init', 'safelyAddStylesheet');

//Print available images
function printAvailableImages($imgFolder, $standardFolder){
  if(!isset($standardFolder)){
    $standardFolder = 1;
  }

  $aFileArray = array();
  //Let's have a look at the images...
  $handle=opendir($imgFolder);
  while ($file = readdir($handle)) {
    if(!is_dir($file)) $aFileArray[]=$file;
  }
  closedir($handle);
  //Sort the array
  sort($aFileArray);
  $aAlowedExtensions = array('jpg','jpeg','gif','png');
  
  echo '<table border="0" cellpadding="5" cellspacing="0">';
  
  //For each file inside the array...
  for($i=0;$i<count($aFileArray);$i++) 
  { 
    $aFileParts = pathinfo($aFileArray[$i]);
    //If file has an allowed extension...
    if(in_array($aFileParts['extension'],$aAlowedExtensions)){
      
      if(!isset($aRadioPosition) || $aRadioPosition == 2){
        $aRadioPosition=1;
        echo '<tr><td>';
      } else {
        $aRadioPosition=2;
        echo '<td>';
      }
      //User has the opportunity to choose this image file
      if($standardFolder == 1){
        echo '<input type="radio" name="usrStarImage" value="'.$aFileArray[$i].'"';
        if(get_option('usrStarImage') == $aFileArray[$i]){echo ' checked';}
        echo '> ';
        _e(insertUSR(array("=3.5", "img" => $aFileArray[$i], "text" => "false", "usrPreviewImg" => "true", "max" => "5" )), 'universal-star-rating');
        echo ' <code>'.$aFileArray[$i].'</code></td>';
      }else{
        echo '<input type="radio" name="usrStarImage" value="c'.$aFileArray[$i].'"';
        if(get_option('usrStarImage') == 'c'.$aFileArray[$i]){echo ' checked';}
        echo '> ';
        _e(insertUSR(array("=3.5", "img" => 'c'.$aFileArray[$i], "text" => "false", "usrPreviewImg" => "true", "max" => "5" )), 'universal-star-rating');
        echo ' <code>c'.$aFileArray[$i].'</code></td>';
      }
      if($i+1 == count($aFileArray) || $aRadioPosition == 2){echo'</tr>';}
    }
  }
}

//Function to check if the image folder structure is right
function proofCUSRIStructure($imgFolder){
	$imgFolder = rtrim($imgFolder,"/");
	$numbers = array (20, 40, 60, 80, 100, 189);
	foreach ($numbers as $value) {
		if (!file_exists($imgFolder."/".$value)) {
			mkdir($imgFolder."/".$value);
		}
	}
}

/*
 * @since 1.9.1
 */
//Function to update the plugins settings
function updateUSRSettings($usrLang, $usrStarSize, $usrMaxStars, $usrStarText, $usrCalcAverage, $usrPermitShortcodedComments, $usrSchemaOrg, $usrCustomImagesFolder, $usrStarImage){
	//Update user language
	update_option("usrLang", $usrLang);
		
	//Update star size		
	$usrStarSize = str_replace( ',', '.', $usrStarSize);
	if(is_numeric($usrStarSize)){
		update_option("usrStarSize", $usrStarSize);
	} else {
		echo $MESSAGES['ERROR']['StarSizeNotNumeric'][$usrLang];
	}
	
	//Update max stars
	$usrMaxStars = intval($usrMaxStars);
	if($usrMaxStars < 1){$usrMaxStars=1;} 
	update_option("usrMaxStars", $usrMaxStars);

	//Update text output
	update_option("usrStarText", $usrStarText);
	
	//Update average rating calculation
	update_option("usrCalcAverage", $usrCalcAverage);
	
	//Update permission to use shortcodes inside comments
	update_option("usrPermitShortcodedComments", $usrPermitShortcodedComments);
	if ($usrPermitShortcodedComments == "true"){
		permitShortcodedComments();
	}
	
	//Update permission to use Schema.org SEO
	update_option("usrSchemaOrg", $usrSchemaOrg);
	
	//Update CUSRI folder
	update_option("usrCustomImagesFolder", $usrCustomImagesFolder);

	//Update star image
	update_option("usrStarImage", $usrStarImage);
}

/*
 * @since 1.9.2
 */
//Function to update the plugins version
function updateUSR(){
	if(get_option("usrVersion") != USR_VERSION)
		update_option("usrVersion", USR_VERSION);
}

//Function to initial set up the plugins variables
function initUSR(){
	if( !get_option( "usrVersion" ) )
		add_option('usrVersion', USR_VERSION, '', 'yes');
	if( !get_option( "usrLang" ) )
		add_option('usrLang', USR_DEFAULT_LANG, '', 'yes');
	if( !get_option( "usrStarSize" ) )
		add_option('usrStarSize', USR_DEFAULT_STAR_SIZE, '', 'yes');
	if( !get_option( "usrMaxStars" ) )
		add_option('usrMaxStars', USR_DEFAULT_MAX_STARS, '', 'yes');
	if( !get_option( "usrStarText" ) )
		add_option('usrStarText', USR_DEFAULT_STAR_TEXT, '', 'yes');
	if( !get_option( "usrCalcAverage" ) )
		add_option('usrCalcAverage', USR_DEFAULT_CALC_AVERAGE, '', 'yes');
	if( !get_option( "usrPermitShortcodedComments" ) )
		add_option('usrPermitShortcodedComments', USR_DEFAULT_PERMIT_SHORTCODE_COMMENTS, '', 'yes');
	if( !get_option( "usrSchemaOrg" ) )
		add_option('usrSchemaOrg', USR_DEFAULT_SCHEMA_ORG, '', 'yes');
	if( !get_option( "usrCustomImagesFolder" ) )
		add_option('usrCustomImagesFolder', USR_DEFAULT_CUSTOM_IMAGE_FOLDER, '', 'yes');
	if( !get_option( "usrStarImage" ) )
		add_option('usrStarImage', USR_DEFAULT_STAR_IMAGE, '', 'yes');
		
	updateUSR();
}

?>