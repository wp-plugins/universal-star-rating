<?php

/*
Plugin Name: Universal Star Rating
Plugin URI: http://www.cizero.de/?p=1142
Description: Adds <code>[usr=10.0]</code> and <code>[usrlist NAME:RATING "ANOTHER NAME:RATING" (...)]</code> shortcode for inserting universal star ratings.
Version: 1.4.3
Author: Mike Wigge
Author URI: http://cizero.de
License: GPL3
*/

/*  Copyright 2013  Mike Wigge  (email : me@cizero.de)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 3, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/*

	Todos:
  - Add some more GFXs
  - Add a function to calculate the average rating value which can be used
  - Add a Button to the WYSIWYG editor to add a rating to the post
	
*/


//############################################################################//
//                                                                            //
//                                 Variables                                  //
//                                                                            //
//############################################################################//


//Including locale files
include('includes/locale.en');
include('includes/locale.de');
include('includes/locale.fr');

//Define used names
$usrPluginName = __("Universal Star Rating", 'universal-star-rating');
$usrPluginFilename = "universal-star-rating.php";

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
function getImageString($ratingValue, $usrStarImage, $usrMaxStars, $usrStarText){
  $usrStarSize = get_option('usrStarSize');
  
  //Just in case it is not done yet...
  $ratingValue = getUsableRating($ratingValue, $usrMaxStars);
  $formattedRatingValue = getFormattedRating($ratingValue);
  $imageString = '<img src="'.content_url().'/plugins/universal-star-rating/includes/stars.php?img='.$usrStarImage.'&max='.$usrMaxStars.'&rat='.$ratingValue.'" style="height:'.$usrStarSize.'px;" alt="'.$ratingValue.' Stars" />';
  if ($usrStarText == "true"){
    $imageString .= ' ('.$formattedRatingValue.' / '.$usrMaxStars.')';
  }

  return $imageString;
}


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
  }
  //If key 'max' is set the max star count will be overridden
  if ($atts['max'] && is_numeric($atts['max'])) {
    $usrMaxStars = intval($atts['max']);
  }
  //if key 'text' is set to 'yes' or 'no' it is possible to override default
  if ($atts['text'] == "false" || $atts['text'] == "true") {
    $usrStarText = $atts['text'];
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
  $usr = getImageString($ratingValue, $usrStarImage, $usrMaxStars, $usrStarText); 

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
  global $MESSAGES;

  //If there are more than 1 keys inside the array...
  if(count($atts) > 1){

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
    //if key 'text' is set to 'yes' or 'no' it is possible to override default
    if ($atts['text'] == "false" || $atts['text'] == "true") {
      $usrStarText = $atts['text'];
    }
  
    //Using a table because it looks better
    $usrlist = '<table border="0">';
  
    //For each key/value pair inside the array...
    foreach ($atts as $value) {
      //splitting Key:Value into two variables - User can't use a ':' inside Key
      list($splittedKey, $splittedValue) = explode(":", $value, 2);
      
      //Get the right rating formats
      $ratingValue = getUsableRating($splittedValue, $usrMaxStars);
      
      //Setting up the string with the right picture
      $usrlist .= '<tr><td>'.$splittedKey.':</td><td>'.getImageString($ratingValue, $usrStarImage, $usrMaxStars, $usrStarText).'</td></tr>';
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


//############################################################################//
//                                                                            //
//                                Options Page                                //
//                                                                            //
//############################################################################//


//Register options
add_option('usrLang', 'en', '', 'yes');
add_option('usrStarSize', '12', '', 'yes');
add_option('usrMaxStars', '10', '', 'yes');
add_option('usrStarImage', '01.png', '', 'yes');
add_option('usrStarText', 'true', '', 'yes');


//Initialize admin area
function usrAdminInit() {
  $usrLang = get_option('usrLang');
  global $MESSAGES;
  //if not administrator, kill WordPress execution and provide a message
	if (!current_user_can('manage_options')) {
		wp_die( __($MESSAGES['ERROR']['NoAdminAccess'][$usrLang]) );
	}
  
  //Register the option group usrSettings with the option name usrOption
	if (function_exists('register_setting')) {
		register_setting('usrSettings', 'usrOption', '');
	}
}
add_action('admin_init', 'usrAdminInit');


//Add USR option page
function addUsrOptionPage() {
  //globals
	global $usrPluginName, $usrPluginFilename;
	
	add_options_page($usrPluginName, $usrPluginName, 8, basename(__FILE__), 'usrOptionsPage');
}
add_action('admin_menu', 'addUsrOptionPage');


//Define USR option page
function usrOptionsPage() {
  //globals
  global $MESSAGES;
  
	if (isset($_POST['usrOptionsUpdate'])) {
		
		//Update user language
		$usrLang = $_POST["usrLang"];
		update_option("usrLang", $usrLang);
		
		//Update star size
    $usrStarSize = str_replace( ',', '.', $_POST["usrStarSize"]);
    if(is_numeric($usrStarSize)){
		  update_option("usrStarSize", $usrStarSize);
    } else {
      echo $MESSAGES['ERROR']['StarSizeNotNumeric'][$usrLang];
    }
    
    //Update max stars
		$usrMaxStars = intval($_POST["usrMaxStars"]);
    if($usrMaxStars < 1){$usrMaxStars=1;} 
    update_option("usrMaxStars", $usrMaxStars);

    //Update text output
    $usrStarText = $_POST["usrStarText"];
    update_option("usrStarText", $usrStarText);
    
    //Update star image
    $usrStarImage = $_POST["usrStarImage"];
    update_option("usrStarImage", $usrStarImage);
    
		//Tell user that options are updated
		echo '<div class="updated fade"><p><strong>' . __($MESSAGES['INFO']['SettingsUpdated'][$usrLang], "universal-star-rating") . '</strong></p></div>';
	}

	// Show options page
	?>

		<div class="wrap" style="width: 600px;">		
			<div class="options">		
				<form method="post" action="options-general.php?page=<?php global $usrPluginFilename; echo $usrPluginFilename; ?>">
        
				<h2><?php global $usrPluginName, $SETTINGS; $usrLang = get_option('usrLang'); printf(__('%s - '.$SETTINGS['GLOBAL']['Settings'][$usrLang], 'universal_star_rating'), $usrPluginName); ?></h2>
				
					<h3><?php _e($SETTINGS['NOU']['NotesOnUsage'][$usrLang], 'universal-star-rating'); ?></h3>					
					<p><?php _e($SETTINGS['NOU']['ShortCodeDefinition'][$usrLang], 'universal-star-rating'); ?></p>					
					<p><?php _e($SETTINGS['NOU']['HowToUSR'][$usrLang], 'universal-star-rating'); ?></p>					
					<p><?php _e($SETTINGS['NOU']['HowToUSRList'][$usrLang], 'universal-star-rating'); ?></p>					
          <p><?php _e($SETTINGS['NOU']['HowToShortCodes'][$usrLang], 'universal-star-rating'); ?></p>

					<h3><?php _e($SETTINGS['OPT']['Options'][$usrLang], 'universal-star-rating'); ?></h3>					
					<p><?php _e($SETTINGS['OPT']['ExplainOptions'][$usrLang], 'universal-star-rating'); ?></p>
					<p>
            <table border="0">
              <tr>
                <td><?php _e($SETTINGS['OPT']['ExplainLanguageSetting'][$usrLang], 'universal-star-rating'); ?></td><td>
      					<?php
      					$usrLang = get_option('usrLang');
                
                echo '<select name="usrLang"><option value="en"';
                  if($usrLang == "en"){echo ' selected';}
                echo '>English</option><option value="de"';
                  if($usrLang == "de"){echo ' selected';}
                echo '>Deutsch</option><option value="fr"';
                  if($usrLang == "fr"){echo ' selected';}
                echo '>Francais</option></select>';
                
      					?>
                </td><td><?php _e($SETTINGS['OPT']['DefaultLanguage'][$usrLang], 'universal-star-rating'); ?></td>
              </tr>
              <tr>
                <td><?php _e($SETTINGS['OPT']['ExplainStarSizeSetting'][$usrLang], 'universal-star-rating'); ?></td>
                <td><?php
      					echo "<input type='text' size='10' ";
      					echo "name='usrStarSize' ";
      					echo "id='usrStarSize' ";
      					echo "value='".get_option('usrStarSize')."' />\n";
      					?></td>
                <td><?php _e($SETTINGS['OPT']['DefaultStarSize'][$usrLang], 'universal-star-rating'); ?></td>
              </tr>
              <tr>
                <td><?php _e($SETTINGS['OPT']['ExplainStarCountSetting'][$usrLang], 'universal-star-rating'); ?></td>
                <td><?php
      					echo "<input type='text' size='10' ";
      					echo "name='usrMaxStars' ";
      					echo "id='usrMaxStars' ";
      					echo "value='".get_option('usrMaxStars')."' />\n";
      					?></td>
                <td><?php _e($SETTINGS['OPT']['DefaultStarCount'][$usrLang], 'universal-star-rating'); ?></td>
              </tr>
              <tr>
                <td><?php _e($SETTINGS['OPT']['ExplainStarText'][$usrLang], 'universal-star-rating'); ?></td>
                <td>
                <?php
      					$usrStarText = get_option('usrStarText');
                
                echo '<select name="usrStarText"><option value="true"';
                  if($usrStarText == "true"){echo ' selected';}
                echo '>'.$SETTINGS['OPT']["StarTextEnabled"][$usrLang].'</option><option value="false"';
                  if($usrStarText == "false"){echo ' selected';}
                echo '>'.$SETTINGS['OPT']["StarTextDisabled"][$usrLang].'</option></select>';
                
      					?>
                </td>
                <td><?php _e($SETTINGS['OPT']['DefaultStarText'][$usrLang], 'universal-star-rating'); ?></td>
              </tr>
              <tr>
                <td valign="top"><?php _e($SETTINGS['OPT']['ExplainStarImage'][$usrLang], 'universal-star-rating'); ?></td>
                <td colspan="2">
      
                <?php
                  //Let's have a look at the images...
                  $handle=opendir("../wp-content/plugins/universal-star-rating/images/");
                  while ($file = readdir($handle)) {
                    if(!is_dir($file)) $aFileArray[]=$file;
                  }
                  closedir($handle);
                  //Sort the array
                  sort($aFileArray);
                  $aAlowedExtensions = array('jpg','jpeg','gif','png');
                  //For each file inside the array...
                  for($i=0;$i<count($aFileArray);$i++) 
                  { 
                    $aFileParts = pathinfo($aFileArray[$i]);
                    //If file has an allowed extension...
                    if(in_array($aFileParts['extension'],$aAlowedExtensions)){
                      
                      //User has the opportunity to choose this image file
                      echo '<input type="radio" name="usrStarImage" value="'.$aFileArray[$i].'"';
                      if(get_option('usrStarImage') == $aFileArray[$i]){echo ' checked';}
                      echo '> ';
                      _e(insertUSR(array("=6.5", "img" => $aFileArray[$i], "text" => "false" )), 'universal-star-rating');
                      echo " <code>$aFileArray[$i]</code><br>";
                    }
                  }
                ?>
                
                </td>
              </tr>
            </table>
					</p>

					<h3><?php _e($SETTINGS['PREV']['Preview'][$usrLang], 'universal-star-rating'); ?></h3>
          <p>
            <table border="1" cellspacing="0" cellpadding="5" width="100%">
              <tr>
                <td><?php _e($SETTINGS['PREV']['Example'][$usrLang], 'universal-star-rating'); ?></td>
                <td><?php _e($SETTINGS['PREV']['ExampleResult'][$usrLang], 'universal-star-rating'); ?></td>
              </tr>
              <tr>
                <td><?php _e($SETTINGS['PREV']['ExampleUsr'][$usrLang], 'universal-star-rating'); ?></td>
                <td><?php _e($SETTINGS['PREV']['ExampleUsrResult'][$usrLang].insertUSR(array("=8.5")), 'universal-star-rating'); ?></td>
              </tr>
              <tr>
                <td><?php _e($SETTINGS['PREV']['ExampleUsrList'][$usrLang], 'universal-star-rating'); ?></td>
                <td><?php _e(insertUSRList(array($SETTINGS['PREV']['ExampleUsrListResult'][$usrLang][1], $SETTINGS['PREV']['ExampleUsrListResult'][$usrLang][2], $SETTINGS['PREV']['ExampleUsrListResult'][$usrLang][3])), 'universal-star-rating'); ?></td>
              </tr>
              <tr>
                <td><?php _e($SETTINGS['PREV']['ExampleUsrOverriddenImage'][$usrLang], 'universal-star-rating'); ?></td>
                <td><?php _e($SETTINGS['PREV']['ExampleUsrResult'][$usrLang].insertUSR(array("=8.5","img" => "03.png")), 'universal-star-rating'); ?></td>
              </tr>
              <tr>
                <td><?php _e($SETTINGS['PREV']['ExampleUsrOverriddenText'][$usrLang], 'universal-star-rating'); ?></td>
                <td><?php _e($SETTINGS['PREV']['ExampleUsrResult'][$usrLang].insertUSR(array("=8.5","text" => "false")), 'universal-star-rating'); ?></td>
              </tr>
              <tr>
                <td><?php _e($SETTINGS['PREV']['ExampleUsrOverriddenMax'][$usrLang], 'universal-star-rating'); ?></td>
                <td><?php _e($SETTINGS['PREV']['ExampleUsrResult'][$usrLang].insertUSR(array("=8.5","max" => "5")), 'universal-star-rating'); ?></td>
              </tr>
              <tr>
                <td><?php _e($SETTINGS['PREV']['ExampleUsrOverriddenAll'][$usrLang], 'universal-star-rating'); ?></td>
                <td><?php _e($SETTINGS['PREV']['ExampleUsrResult'][$usrLang].insertUSR(array("=8.5","max" => "5", "text" => "false", "img" => "03.png")), 'universal-star-rating'); ?></td>
              </tr>
            </table>
          </p>
          
          <?php if ( function_exists('settings_fields') ) settings_fields('rating_settings'); ?>
					<input type='submit' name='usrOptionsUpdate' value='<?php _e($SETTINGS['GLOBAL']['SubmitButton'][$usrLang], 'universal_star_rating'); ?>' />

				</form>				
			</div>
		</div>

<?php
}

?>