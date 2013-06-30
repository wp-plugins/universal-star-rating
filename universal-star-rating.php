<?php

/*
Plugin Name: Universal Star Rating
Plugin URI: http://www.cizero.de/?p=1142
Description: Adds <code>[usr=10.0]</code> and <code>[usrlist NAME:RATING "ANOTHER NAME:RATING" (...)]</code> shortcode for inserting universal star ratings.
Version: 1.3.0
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
  - Add some more GFXs to choose the preferred one
  - Graphics shall be smaller / Change the way the graphics will be build
  - Add a function to override standard image in single posts
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
function getUsableRating($ratingValue){
  $usrMaxStars = get_option('usrMaxStars');

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
  global $OUTPUT_MSG; 
  
  //Just in case it is not done yet...
  $ratingValue = getUsableRating($ratingValue);
  
  //Using the right decimal mark for our output
  $ratingValue = str_replace('.', $OUTPUT_MSG['DecimalMark'][$usrLang], $ratingValue);
  
  return $ratingValue;
}

//Function to get the image string
function getImageString($ratingValue){
  $usrStarSize = get_option('usrStarSize');
  $usrMaxStars = get_option('usrMaxStars');
  $usrStarImage = get_option('usrStarImage');
  
  //Just in case it is not done yet...
  $ratingValue = getUsableRating($ratingValue);
  $formattedRatingValue = getFormattedRating($ratingValue);
  $imageString = '<img src="'.content_url().'/plugins/universal-star-rating/includes/stars.php?r='.$ratingValue.'&t='.$usrStarImage.'" height="'.$usrStarSize.'px" /> ('.$formattedRatingValue.' / '.$usrMaxStars.')';

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
  //If there is no value for key 'stars' and '0' inside the atts array the value of key 'stars' is set to 0
	if (!$attr['stars'] && !$atts[0]) {
		$atts['stars'] = 0;
	}

  //If key 'stars' is set the rating is its value
	if ($atts['stars']) {
		$ratingValue = $atts['stars'];
  //If key 'stars' is not set the key '0' is and its value is the rating
	} else if ($atts[0]) {
		$ratingValue = str_replace( "=" , "" , $atts[0] ) ;
		$ratingValue = str_replace( '"' , "" , $ratingValue ) ;
		$ratingValue = str_replace( '/' , "" , $ratingValue ) ;
	}

  //Get the right rating formats
  $ratingValue = getUsableRating($ratingValue);
  
  //Setting up the string with the right picture
  $usr = getImageString($ratingValue); 

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
  global $ERR_MSG;

  //If there are more than 1 keys inside the array...
  if(count($atts) > 1){

    //Using a table because it looks better
    $usrlist = '<table border="0">';
  
    //For each key/value pair inside the array...
    foreach ($atts as $value) {  
      //splitting Key:Value into two variables - User can't use a ':' inside Key
      list($splittedKey, $splittedValue) = split(":", $value, 2);
      
      //Get the right rating formats
      $ratingValue = getUsableRating($splittedValue);
      
      //Setting up the string with the right picture
      $usrlist .= '<tr><td>'.$splittedKey.':</td><td>'.getImageString($ratingValue).'</td></tr>';
    }

    //Finishing the table
    $usrlist .= '</table>';
    
    //Output
    return $usrlist;

  //There is just 1 key:value pair we return the error message
  } else {
    return $ERR_MSG['NotEnoughParameters'][$usrLang];
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
add_option('usrStarImage', '1', '', 'yes');


//Initialize admin area
function usrAdminInit() {
  $usrLang = get_option('usrLang');
  global $ERR_MSG;
  //if not administrator, kill WordPress execution and provide a message
	if (!current_user_can('manage_options')) {
		wp_die( __($ERR_MSG['NoAdminAccess'][$usrLang]) );
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
  global $ERR_MSG, $OUTPUT_MSG;
  
	if (isset($_POST['usrOptionsUpdate'])) {
			
		//Update user language
		$usrLang = $_POST["usrLang"];
		update_option("usrLang", $usrLang);
		
		//Update star size
    $usrStarSize = str_replace( ',', '.', $_POST["usrStarSize"]);
    if(is_numeric($usrStarSize)){
		  update_option("usrStarSize", $usrStarSize);
    } else {
      echo $ERR_MSG['StarSizeNotNumeric'][$usrLang];
    }
    
    //Update max stars
		$usrMaxStars = intval($_POST["usrMaxStars"]);
    if($usrMaxStars < 1){$usrMaxStars=1;}; 
    update_option("usrMaxStars", $usrMaxStars);

    //Update star image
    $usrStarImage = $_POST["usrStarImage"];
    update_option("usrStarImage", $usrStarImage);
    
		//Tell user that options are updated
		echo '<div class="updated fade"><p><strong>' . __($OUTPUT_MSG["OptionsUpdated"][$usrLang], "universal-star-rating") . '</strong></p></div>';
	}

	// Show options page
	?>

		<div class="wrap" style="width: 600px;">
		
			<div class="options">
		
				<form method="post" action="options-general.php?page=<?php global $usrPluginFilename; echo $usrPluginFilename; ?>">
        
				<h2><?php global $usrPluginName, $OUTPUT_MSG; $usrLang = get_option('usrLang'); printf(__('%s '.$OUTPUT_MSG['Settings'][$usrLang], 'universal_star_rating'), $usrPluginName); ?></h2>
				
					<h3><?php _e($OUTPUT_MSG['NotesOnUsage'][$usrLang], 'universal-star-rating'); ?></h3>
					
					<p><?php _e($OUTPUT_MSG['ShortCodeDefinition'][$usrLang], 'universal-star-rating'); ?></p>
					
					<p><?php _e($OUTPUT_MSG['HowToUSR'][$usrLang], 'universal-star-rating'); ?></p>
					
					<p><?php _e($OUTPUT_MSG['HowToUSRList'][$usrLang], 'universal-star-rating'); ?></p>
					
					
					<h3><?php _e($OUTPUT_MSG['Options'][$usrLang], 'universal-star-rating'); ?></h3>
					
					<p><?php _e($OUTPUT_MSG['ExplainOptions'][$usrLang], 'universal-star-rating'); ?></p>

					<p>

          <table border="0">
          <tr>
          <td><?php _e($OUTPUT_MSG['ExplainLanguageSetting'][$usrLang], 'universal-star-rating'); ?></td><td>
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
          </td><td><?php _e($OUTPUT_MSG['DefaultLanguage'][$usrLang], 'universal-star-rating'); ?></td>
          </tr>
          <tr>
          <td><?php _e($OUTPUT_MSG['ExplainStarSizeSetting'][$usrLang], 'universal-star-rating'); ?></td>
          <td><?php
					echo "<input type='text' size='10' ";
					echo "name='usrStarSize' ";
					echo "id='usrStarSize' ";
					echo "value='".get_option('usrStarSize')."' />\n";
					?></td>
          <td><?php _e($OUTPUT_MSG['DefaultStarSize'][$usrLang], 'universal-star-rating'); ?></td>
          </tr>
          <tr>
          <td><?php _e($OUTPUT_MSG['ExplainStarCountSetting'][$usrLang], 'universal-star-rating'); ?></td>
          <td><?php
					echo "<input type='text' size='10' ";
					echo "name='usrMaxStars' ";
					echo "id='usrMaxStars' ";
					echo "value='".get_option('usrMaxStars')."' />\n";
					?></td>
          <td><?php _e($OUTPUT_MSG['DefaultStarCount'][$usrLang], 'universal-star-rating'); ?></td>
          </tr>
          <tr>
          <td valign="top"><?php _e($OUTPUT_MSG['ExplainStarImage'][$usrLang], 'universal-star-rating'); ?></td>
          <td colspan="2">
            <input type="radio" name="usrStarImage" value="1"<?php if(get_option('usrStarImage') == "1"){echo ' checked';} ?>>
            <img src="<?php echo content_url(); ?>/plugins/universal-star-rating/images/1_preview.png" height="<?php echo get_option('usrStarSize'); ?>px">
            <br>
            <input type="radio" name="usrStarImage" value="2"<?php if(get_option('usrStarImage') == "2"){echo ' checked';} ?>>
            <img src="<?php echo content_url(); ?>/plugins/universal-star-rating/images/2_preview.png" height="<?php echo get_option('usrStarSize'); ?>px">
            <br>
            <input type="radio" name="usrStarImage" value="3"<?php if(get_option('usrStarImage') == "3"){echo ' checked';} ?>>
            <img src="<?php echo content_url(); ?>/plugins/universal-star-rating/images/3_preview.png" height="<?php echo get_option('usrStarSize'); ?>px">
          </td>
          </tr>
          </table>
          
					</p>

					<h3><?php _e($OUTPUT_MSG['Preview'][$usrLang], 'universal-star-rating'); ?></h3>
          <p>
          <table border="1" cellspacing="0" cellpadding="5" width="100%">
          <tr>
          <td><?php _e($OUTPUT_MSG['Example'][$usrLang], 'universal-star-rating'); ?></td>
          <td><?php _e($OUTPUT_MSG['ExampleResult'][$usrLang], 'universal-star-rating'); ?></td>
          <tr>
          <tr>
          <td><?php _e($OUTPUT_MSG['ExampleUsr'][$usrLang], 'universal-star-rating'); ?></td>
          <td><?php _e($OUTPUT_MSG['ExampleUsrResult'][$usrLang].insertUSR(array("=8.5")), 'universal-star-rating'); ?></td>
          <tr>
          <tr>
          <td><?php _e($OUTPUT_MSG['ExampleUsrList'][$usrLang], 'universal-star-rating'); ?></td>
          <td><?php _e(insertUSRList(array($OUTPUT_MSG['ExampleUsrListResult'][$usrLang][1], $OUTPUT_MSG['ExampleUsrListResult'][$usrLang][2], $OUTPUT_MSG['ExampleUsrListResult'][$usrLang][3])), 'universal-star-rating'); ?></td>
          <tr>
          </table>
          </p>
          
          
          <?php if ( function_exists('settings_fields') ) settings_fields('rating_settings'); ?>
					<input type='submit' name='usrOptionsUpdate' value='<?php _e($OUTPUT_MSG['SubmitButton'][$usrLang], 'universal_star_rating'); ?>' />

				</form>				
			</div>
		</div>

<?php
}

?>