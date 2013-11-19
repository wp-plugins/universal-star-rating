<?php

/*
Plugin Name: Universal Star Rating
Plugin URI: http://www.cizero.de/?p=1142
Description: Adds <code>[usr=10.0]</code> and <code>[usrlist NAME:RATING "ANOTHER NAME:RATING" (...)]</code> shortcode for inserting universal star ratings.
Version: 1.1.0
Author: Mike Wigge
Author URI: http://cizero.de
License: GPL2
*/

/*  Copyright 2013  Mike Wigge  (email : me@cizero.de)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
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
  - Make a option site for the different options like size, picture and so on
  - Add some more GFXs to choose the preferred one
  - Add a function to calculate the average rating value which can be used
  - Add a Button to the WYSIWYG editor to add a rating to the post
  - Add a readme / host this plugin at wordpress.org
	
*/

// Register functions
add_shortcode('usr', 'insertUSR');
add_shortcode('usrlist', 'insertUSRList');

// Insert Rating function for one rating
function insertUSR($atts) {

	if (!$attr['stars'] && !$atts[0]) {
		$atts['stars'] = 0;
	}

	if ($atts['stars']) {
		$total_stars = $atts['stars'];
	} else if ($atts[0]) {
		$total_stars = str_replace( "=" , "" , $atts[0] ) ;
		$total_stars = str_replace( '"' , "" , $total_stars ) ;
		$total_stars = str_replace( '/' , "" , $total_stars ) ;
	}

  if ($total_stars > "10.0"){
    $total_stars = 10;
  } elseif ($total_stars < "0.0") {
    $total_stars = 0;
  }

  $euro_total_stars = str_replace(".", ",", $total_stars);
  $usr = '<img src="wp-content/plugins/universal-star-rating/includes/stars.php?r='.$total_stars.'" height="12px" /> ('.$euro_total_stars.' / 10)';

  return $usr;
}


// Insert Rating function for many ratings
function insertUSRList($atts) {

  $ERR_MSG['NotEnoughParameter'] = "[ERROR] You have to use more than 1 name/rating. If there is just 1 name/rating use <code>[usr=8.2]</code> instead.";

  if(count($atts) > 1){

    $usrlist = '<table border="0">';
  
    foreach ($atts as $value) {
  
      list($splittedKey, $splittedValue) = split(":", $value, 2);
      
      if (!$splittedValue || $splittedValue < "0.0") {
        $splittedValue = 0;
      } elseif ($splittedValue > "10.0"){
        $splittedValue = 10;
      }
      $euro_total_stars = str_replace(".", ",", $splittedValue);
      $usrlist .= '<tr><td>'.$splittedKey.':</td><td><img src="wp-content/plugins/universal-star-rating/includes/stars.php?r='.$splittedValue.'" height="12px" /> ('.$euro_total_stars.' / 10)</td></tr>';

    }

    $usrlist .= '</table>';
    return $usrlist;
  } else {
    return $ERR_MSG['NotEnoughParameter'];
  }
  
}

?>