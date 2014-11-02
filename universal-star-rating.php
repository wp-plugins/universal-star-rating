<?php

/*
Plugin Name: Universal Star Rating
Plugin URI: http://www.cizero.de/?p=1142
Description: Adds <code>[usr=10.0]</code> and <code>[usrlist NAME:RATING "ANOTHER NAME:RATING" (...)]</code> shortcode for inserting universal star ratings.
Version: 1.9.2
Author: Mike Wigge
Author URI: http://cizero.de
License: GPL3
*/

/*  Copyright 2013 - 2014  Mike Wigge  (email : me@cizero.de)

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

/*  Todos:
  - Add some more GFXs
  - Sort GFXs by type
  - Add a Button to the WYSIWYG editor to add a rating to the post
*/


//############################################################################//
//                                                                            //
//                                 Variables                                  //
//                                                                            //
//############################################################################//

define('USR_VERSION','1.9.2');
define('USR_DEFAULT_LANG','en');
define('USR_DEFAULT_STAR_SIZE','12');
define('USR_DEFAULT_MAX_STARS','5');
define('USR_DEFAULT_STAR_TEXT','true');
define('USR_DEFAULT_CALC_AVERAGE','false');
define('USR_DEFAULT_PERMIT_SHORTCODE_COMMENTS','false');
define('USR_DEFAULT_SCHEMA_ORG','false');
define('USR_DEFAULT_CUSTOM_IMAGE_FOLDER','cusri');
define('USR_DEFAULT_STAR_IMAGE','01.png');

//Including locale files
$usrLocales = scandir( WP_PLUGIN_DIR . '/universal-star-rating/includes/locales/');
sort($usrLocales);
foreach($usrLocales as $usrLocale) {
	if($usrLocale != "." && $usrLocale != ".." && $usrLocale != "locale.temp"){
		require_once('includes/locales/'.$usrLocale);
	}
}

//Define used names
$usrPluginName = __("Universal Star Rating", 'universal-star-rating');
$usrPluginFilename = "universal-star-rating.php";

//############################################################################//
//                                                                            //
//                              Helper Functions                              //
//                                                                            //
//############################################################################//

//Including helper functions
require 'includes/helper_functions.php';

//############################################################################//
//                                                                            //
//                                 Shortcodes                                 //
//                                                                            //
//############################################################################//

//Including shortcodes
require 'includes/shortcodes.php';

//############################################################################//
//                                                                            //
//                                Options Page                                //
//                                                                            //
//############################################################################//

//Register options
initUSR();

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
	
	add_options_page($usrPluginName, $usrPluginName, 'manage_options', basename(__FILE__), 'usrOptionsPage');
}
add_action('admin_menu', 'addUsrOptionPage');


//Define USR option page
function usrOptionsPage() {
  //globals
  global $MESSAGES;
	//If options shall be resetted
	if (isset($_POST['usrOptionsReset'])) {
		updateUSRSettings(USR_DEFAULT_LANG, USR_DEFAULT_STAR_SIZE, USR_DEFAULT_MAX_STARS,
			USR_DEFAULT_STAR_TEXT, USR_DEFAULT_CALC_AVERAGE, USR_DEFAULT_PERMIT_SHORTCODE_COMMENTS,
			USR_DEFAULT_SCHEMA_ORG, USR_DEFAULT_CUSTOM_IMAGE_FOLDER, USR_DEFAULT_STAR_IMAGE);
	
		//Tell user that options are updated
		echo '<div class="updated fade"><p><strong>' . __($MESSAGES['INFO']['SettingsUpdated'][USR_DEFAULT_LANG], "universal-star-rating") . '</strong></p></div>';
	}
	//If options shall be updated
	if (isset($_POST['usrOptionsUpdate'])) {
		$usrLang = $_POST["usrLang"];
		$usrStarSize = $_POST["usrStarSize"];
		$usrMaxStars = $_POST["usrMaxStars"];
		$usrStarText = $_POST["usrStarText"];
		$usrCalcAverage = $_POST["usrCalcAverage"];
		$usrPermitShortcodedComments = $_POST["usrPermitShortcodedComments"];
		$usrSchemaOrg = $_POST["usrSchemaOrg"];
		$usrCustomImagesFolder = $_POST["usrCustomImagesFolder"];
		$usrStarImage = $_POST["usrStarImage"];
		
		updateUSRSettings($usrLang, $usrStarSize, $usrMaxStars, $usrStarText, $usrCalcAverage, $usrPermitShortcodedComments, $usrSchemaOrg, $usrCustomImagesFolder, $usrStarImage);
		
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
                if($usrLang == '')
					$usrLang = USR_DEFAULT_LANG;
				
				echo '<select name="usrLang">';
				
				//dynamically show all available languages
				$usrLocales = scandir( WP_PLUGIN_DIR . '/universal-star-rating/includes/locales/');
				foreach($usrLocales as $usrLocale) {
					if($usrLocale != "." && $usrLocale != ".." && $usrLocale != "locale.temp"){
						$localeParts = explode('.', $usrLocale);
						echo '<option value="' . $localeParts[1] . '"';
						if($usrLang == $localeParts[1])
							echo ' selected';
						echo '>' . $SETTINGS['OPT']['OriginLanguage'][$localeParts[1]] . '</option>';
					}
				}
				
				echo '</select>';

      					?>
                </td><td><?php _e($SETTINGS['OPT']['DefaultLanguage'][$usrLang], 'universal-star-rating'); ?></td>
              </tr>
              <tr>
                <td><?php _e($SETTINGS['OPT']['ExplainStarSizeSetting'][$usrLang].":", 'universal-star-rating'); ?></td>
                <td>
                  <input type="text" size="10" name="usrStarSize" id="usrStarSize" value="<?php echo get_option('usrStarSize');?>" />
      					</td>
                <td><?php _e($SETTINGS['OPT']['DefaultStarSize'][$usrLang], 'universal-star-rating'); ?></td>
              </tr>
              <tr>
                <td><?php _e($SETTINGS['OPT']['ExplainStarCountSetting'][$usrLang].":", 'universal-star-rating'); ?></td>
                <td>
      					  <input type="text" size="10" name="usrMaxStars" id="usrMaxStars" value="<?php echo get_option('usrMaxStars');?>" />
      					</td>
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
                <td><?php _e($SETTINGS['OPT']['ExplainSchemaOrg'][$usrLang].":", 'universal-star-rating'); ?></td>
                <td><?php
                $usrSchemaOrg = get_option('usrSchemaOrg');
                
                echo '<select name="usrSchemaOrg"><option value="true"';
                  if($usrSchemaOrg == "true"){echo ' selected';}
                echo '>'.$SETTINGS['OPT']['DefaultEnabled'][$usrLang].'</option><option value="false"';
                  if($usrSchemaOrg == "false"){echo ' selected';}
                echo '>'.$SETTINGS['OPT']['DefaultDisabled'][$usrLang].'</option></select>';
                
      					?>
                </td>
                <td><?php _e($SETTINGS['OPT']['DefaultSchemaOrg'][$usrLang], 'universal-star-rating'); ?></td>
              </tr>
              <tr>
                <td><?php _e($SETTINGS['OPT']['ExplainCUSRI'][$usrLang].":", 'universal-star-rating'); ?></td>
                <td><?php
                $usrCustomImagesFolder = get_option('usrCustomImagesFolder');
                
                echo '/wp-content/<input type="text" size="5" name="usrCustomImagesFolder" id="usrCustomImagesFolder" value="'.$usrCustomImagesFolder.'" />';
                
      					?>
                </td>
                <td><?php _e($SETTINGS['OPT']['DefaultCUSRI'][$usrLang], 'universal-star-rating'); ?></td>
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

                  //Print images which come within this plugin
                  printAvailableImages( WP_PLUGIN_DIR . "/universal-star-rating/images/" . $imgFolder . "/", 1);
                  //Is there a custom images folder?
                  $customImagesFolder = WP_CONTENT_DIR . get_option("usrCustomImagesFolder") . '/';
                  if(file_exists($customImagesFolder)){
                    //Print custom images
                    proofCUSRIStructure($customImagesFolder);
                    printAvailableImages($customImagesFolder.$imgFolder, 0);
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
                <td><?php _e($SETTINGS['PREV']['ExampleUsrResult'][$usrLang].insertUSR(array("=3.5","max" => "3", "text" => "false", "img" => "03.png", "usrPreviewImg" => "true", "size" => 20)), 'universal-star-rating'); ?></td>
              </tr>
              <tr>
                <td><?php _e($SETTINGS['PREV']['ExampleUsrListOverriddenAverage'][$usrLang], 'universal-star-rating'); ?></td>
                <td><?php _e(insertUSRList(array($SETTINGS['PREV']['ExampleUsrListResult'][$usrLang][1], $SETTINGS['PREV']['ExampleUsrListResult'][$usrLang][2], $SETTINGS['PREV']['ExampleUsrListResult'][$usrLang][3], "avg" => "true", "usrPreviewImg" => "true")), 'universal-star-rating'); ?></td>
              </tr>
            </table>
          </p>
          
          <?php if ( function_exists('settings_fields') ) settings_fields('rating_settings'); ?>
					<input type='submit' name='usrOptionsUpdate' class="button-primary" value='<?php _e($SETTINGS['GLOBAL']['SubmitButton'][$usrLang], 'universal_star_rating'); ?>' />
					<input type='submit' name='usrOptionsReset' class="button" value='<?php _e($SETTINGS['GLOBAL']['ResetButton'][$usrLang], 'universal_star_rating'); ?>' />
				</form>				
			</div>
			<div id="usrFooter" class="wrap">
				<?php echo 'Plugin Version: <strong>' . get_option('usrVersion') . '</strong>'; ?>
			</div>
		</div>

<?php
}

?>