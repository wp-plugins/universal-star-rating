<?php

/*
Plugin Name: Universal Star Rating
Plugin URI: http://www.cizero.de/?p=1142
Description: Adds <code>[usr=10.0]</code> and <code>[usrlist NAME:RATING "ANOTHER NAME:RATING" (...)]</code> shortcode for inserting universal star ratings.
Version: 1.7.0
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
  - Sort GFXs by type
  - Add a Button to the WYSIWYG editor to add a rating to the post
	
*/


//############################################################################//
//                                                                            //
//                                 Variables                                  //
//                                                                            //
//############################################################################//


//Including locale files
include('includes/locales/locale.en');
include('includes/locales/locale.de');
include('includes/locales/locale.fr');
include('includes/locales/locale.it');
include('includes/locales/locale.es');

//Define used names
$usrPluginName = __("Universal Star Rating", 'universal-star-rating');
$usrPluginFilename = "universal-star-rating.php";

//############################################################################//
//                                                                            //
//                              Helper Functions                              //
//                                                                            //
//############################################################################//

//Including helper functions
include('includes/helper_functions.php');

//############################################################################//
//                                                                            //
//                                 Shortcodes                                 //
//                                                                            //
//############################################################################//

//Including shortcodes
include('includes/shortcodes.php');

//############################################################################//
//                                                                            //
//                                Options Page                                //
//                                                                            //
//############################################################################//

//Register options
add_option('usrLang', 'en', '', 'yes');
add_option('usrStarSize', '12', '', 'yes');
add_option('usrMaxStars', '5', '', 'yes');
add_option('usrStarImage', '01.png', '', 'yes');
add_option('usrStarText', 'true', '', 'yes');
add_option('usrCalcAverage', 'false', '', 'yes');
add_option('usrPermitShortcodedComments', 'false', '', 'yes');

//Register Filter if admins allows shortcodes inside comments
$usrPermitShortcodedComments = get_option('usrPermitShortcodedComments');
if ($usrPermitShortcodedComments == "true"){
  permitShortcodedComments();
}

//Initialize admin area
function usrAdminInit() {
  $usrLang = get_option('usrLang');
  if($usrLang == ''){$usrLang='en';}
  
  global $MESSAGES;
  //if user is administrator options will be displayed
	if (current_user_can('manage_options')) {
    //Register the option group usrSettings with the option name usrOption
    if (function_exists('register_setting')) {
      register_setting('usrSettings', 'usrOption', '');
    }
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
    
    //Update average rating calculation
    $usrCalcAverage = $_POST["usrCalcAverage"];
    update_option("usrCalcAverage", $usrCalcAverage);
    
    //Update permission to use shortcodes inside comments
    $usrPermitShortcodedComments = $_POST["usrPermitShortcodedComments"];
    update_option("usrPermitShortcodedComments", $usrPermitShortcodedComments);
    if ($usrPermitShortcodedComments == "true"){
      permitShortcodedComments();
    }

		//Tell user that options are updated
		echo '<div class="updated fade"><p><strong>' . __($MESSAGES['INFO']['SettingsUpdated'][$usrLang], "universal-star-rating") . '</strong></p></div>';
	}

	// Show options page
	?>

		<div class="wrap" style="width: 800px;">		
			<div class="options">		
				<form method="post" action="options-general.php?page=<?php global $usrPluginFilename; echo $usrPluginFilename; ?>">
        
				<h2><?php global $usrPluginName, $SETTINGS; $usrLang = get_option('usrLang'); if($usrLang == ''){$usrLang='en';} printf(__('%s - '.$SETTINGS['GLOBAL']['Settings'][$usrLang], 'universal_star_rating'), $usrPluginName); ?></h2>

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
                <td><?php _e($SETTINGS['OPT']['ExplainLanguageSetting'][$usrLang].":", 'universal-star-rating'); ?></td><td>
      					<?php
      					$usrLang = get_option('usrLang');
                if($usrLang == ''){$usrLang='en';}
                
                echo '<select name="usrLang"><option value="en"';
                  if($usrLang == "en"){echo ' selected';}
                echo '>English</option><option value="de"';
                  if($usrLang == "de"){echo ' selected';}
                echo '>Deutsch</option><option value="es"';
                  if($usrLang == "es"){echo ' selected';}
                echo '>Espa&ntilde;ol</option><option value="fr"';
                  if($usrLang == "fr"){echo ' selected';}
                echo '>Francais</option><option value="it"';
                  if($usrLang == "it"){echo ' selected';}
                echo '>Italiano</option></select>';
                
      					?>
                </td><td><?php _e($SETTINGS['OPT']['DefaultLanguage'][$usrLang], 'universal-star-rating'); ?></td>
              </tr>
              <tr>
                <td><?php _e($SETTINGS['OPT']['ExplainStarSizeSetting'][$usrLang].":", 'universal-star-rating'); ?></td>
                <td><?php
      					echo "<input type='text' size='10' ";
      					echo "name='usrStarSize' ";
      					echo "id='usrStarSize' ";
      					echo "value='".get_option('usrStarSize')."' />\n";
      					?></td>
                <td><?php _e($SETTINGS['OPT']['DefaultStarSize'][$usrLang], 'universal-star-rating'); ?></td>
              </tr>
              <tr>
                <td><?php _e($SETTINGS['OPT']['ExplainStarCountSetting'][$usrLang].":", 'universal-star-rating'); ?></td>
                <td><?php
      					echo "<input type='text' size='10' ";
      					echo "name='usrMaxStars' ";
      					echo "id='usrMaxStars' ";
      					echo "value='".get_option('usrMaxStars')."' />\n";
      					?></td>
                <td><?php _e($SETTINGS['OPT']['DefaultStarCount'][$usrLang], 'universal-star-rating'); ?></td>
              </tr>
              <tr>
                <td><?php _e($SETTINGS['OPT']['ExplainStarText'][$usrLang].":", 'universal-star-rating'); ?></td>
                <td>
                <?php
      					$usrStarText = get_option('usrStarText');
                
                echo '<select name="usrStarText"><option value="true"';
                  if($usrStarText == "true"){echo ' selected';}
                echo '>'.$SETTINGS['OPT']['DefaultEnabled'][$usrLang].'</option><option value="false"';
                  if($usrStarText == "false"){echo ' selected';}
                echo '>'.$SETTINGS['OPT']['DefaultDisabled'][$usrLang].'</option></select>';
                
      					?>
                </td>
                <td><?php _e($SETTINGS['OPT']['DefaultStarText'][$usrLang], 'universal-star-rating'); ?></td>
              </tr>
              <tr>
                <td><?php _e($SETTINGS['OPT']['ExplainAverageCalculation'][$usrLang].":", 'universal-star-rating'); ?></td>
                <td>
                <?php
      					$usrCalcAverage = get_option('usrCalcAverage');
                
                echo '<select name="usrCalcAverage"><option value="true"';
                  if($usrCalcAverage == "true"){echo ' selected';}
                echo '>'.$SETTINGS['OPT']['DefaultEnabled'][$usrLang].'</option><option value="false"';
                  if($usrCalcAverage == "false"){echo ' selected';}
                echo '>'.$SETTINGS['OPT']['DefaultDisabled'][$usrLang].'</option></select>';
                
      					?>
                </td>
                <td><?php _e($SETTINGS['OPT']['DefaultAverageCalculation'][$usrLang], 'universal-star-rating'); ?></td>
              </tr>
              <tr>
                <td><?php _e($SETTINGS['OPT']['ExplainPermitShortcodedComment'][$usrLang].":", 'universal-star-rating'); ?></td>
                <td><?php
                $usrPermitShortcodedComments = get_option('usrPermitShortcodedComments');
                
                echo '<select name="usrPermitShortcodedComments"><option value="true"';
                  if($usrPermitShortcodedComments == "true"){echo ' selected';}
                echo '>'.$SETTINGS['OPT']['DefaultEnabled'][$usrLang].'</option><option value="false"';
                  if($usrPermitShortcodedComments == "false"){echo ' selected';}
                echo '>'.$SETTINGS['OPT']['DefaultDisabled'][$usrLang].'</option></select>';
                
      					?>
                </td>
                <td><?php _e($SETTINGS['OPT']['DefaultPermitShortcodedComment'][$usrLang], 'universal-star-rating'); ?></td>
              </tr>
              <tr>
                <td valign="top"><?php _e($SETTINGS['OPT']['ExplainStarImage'][$usrLang].":", 'universal-star-rating'); ?></td>
                <td colspan="2">
      
                <?php

                  $imgHeight=get_option('usrStarSize');
                  if($imgHeight<=20){
                    $imgFolder=20;
                  } elseif($imgHeight<=40) {
                    $imgFolder=40;
                  } elseif($imgHeight<=60) {
                    $imgFolder=60;
                  } elseif($imgHeight<=80) {
                    $imgFolder=80;
                  } elseif($imgHeight<=100) {
                    $imgFolder=100;
                  } else {
                    $imgFolder=189;
                  }

                  //Let's have a look at the images...
                  $handle=opendir("../wp-content/plugins/universal-star-rating/images/".$imgFolder."/");
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
                      echo '<input type="radio" name="usrStarImage" value="'.$aFileArray[$i].'"';
                      if(get_option('usrStarImage') == $aFileArray[$i]){echo ' checked';}
                      echo '> ';
                      _e(insertUSR(array("=3.5", "img" => $aFileArray[$i], "text" => "false", "usrPreviewImg" => "true", "max" => "5" )), 'universal-star-rating');
                      echo " <code>$aFileArray[$i]</code></td>";
                      
                      if($i+1 == count($aFileArray) || $aRadioPosition == 2){echo'</tr>';}
                    }
                  }
                  
                  echo '</table>';
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
                <td><?php _e($SETTINGS['PREV']['ExampleUsrResult'][$usrLang].insertUSR(array("=3.5", "usrPreviewImg" => "true")), 'universal-star-rating'); ?></td>
              </tr>
              <tr>
                <td><?php _e($SETTINGS['PREV']['ExampleUsrList'][$usrLang], 'universal-star-rating'); ?></td>
                <td><?php _e(insertUSRList(array($SETTINGS['PREV']['ExampleUsrListResult'][$usrLang][1], $SETTINGS['PREV']['ExampleUsrListResult'][$usrLang][2], $SETTINGS['PREV']['ExampleUsrListResult'][$usrLang][3], "usrPreviewImg" => "true")), 'universal-star-rating'); ?></td>
              </tr>
              <tr>
                <td><?php _e($SETTINGS['PREV']['ExampleUsrOverriddenImage'][$usrLang], 'universal-star-rating'); ?></td>
                <td><?php _e($SETTINGS['PREV']['ExampleUsrResult'][$usrLang].insertUSR(array("=3.5","img" => "03.png", "usrPreviewImg" => "true")), 'universal-star-rating'); ?></td>
              </tr>
              <tr>
                <td><?php _e($SETTINGS['PREV']['ExampleUsrOverriddenText'][$usrLang], 'universal-star-rating'); ?></td>
                <td><?php _e($SETTINGS['PREV']['ExampleUsrResult'][$usrLang].insertUSR(array("=3.5","text" => "false", "usrPreviewImg" => "true")), 'universal-star-rating'); ?></td>
              </tr>
              <tr>
                <td><?php _e($SETTINGS['PREV']['ExampleUsrOverriddenMax'][$usrLang], 'universal-star-rating'); ?></td>
                <td><?php _e($SETTINGS['PREV']['ExampleUsrResult'][$usrLang].insertUSR(array("=3.5","max" => "3", "usrPreviewImg" => "true")), 'universal-star-rating'); ?></td>
              </tr>
              <tr>
                <td><?php _e($SETTINGS['PREV']['ExampleUsrSize'][$usrLang], 'universal-star-rating'); ?></td>
                <td><?php _e($SETTINGS['PREV']['ExampleUsrResult'][$usrLang].insertUSR(array("=3.5", "usrPreviewImg" => "true", "size" => 20)), 'universal-star-rating'); ?></td>
              </tr>
              <tr>
                <td><?php _e($SETTINGS['PREV']['ExampleUsrOverriddenAll'][$usrLang], 'universal-star-rating'); ?></td>
                <td><?php _e($SETTINGS['PREV']['ExampleUsrResult'][$usrLang].insertUSR(array("=3.5","max" => "3", "text" => "false", "img" => "03.png", "usrPreviewImg" => "true", size => 20)), 'universal-star-rating'); ?></td>
              </tr>
              <tr>
                <td><?php _e($SETTINGS['PREV']['ExampleUsrListOverriddenAverage'][$usrLang], 'universal-star-rating'); ?></td>
                <td><?php _e(insertUSRList(array($SETTINGS['PREV']['ExampleUsrListResult'][$usrLang][1], $SETTINGS['PREV']['ExampleUsrListResult'][$usrLang][2], $SETTINGS['PREV']['ExampleUsrListResult'][$usrLang][3], "avg" => "true", "usrPreviewImg" => "true")), 'universal-star-rating'); ?></td>
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