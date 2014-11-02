=== Universal Star Rating ===
Contributors: Chasil
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=mike%40jroene%2ede&lc=DE&item_name=Universal%20Star%20Rating%20%2d%20Cizero%2ede&no_note=0&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donate_SM%2egif%3aNonHostedGuest
Tags: stars, rating, movies, books, reviews, shortcodes
Requires at least: 3.0.1
Tested up to: 4.0
Stable tag: 1.9.2
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.html

With this plugin you can easily add ratings/reviews for desired data, products and services with the aid of a classic star rating system.

== Description ==

Universal Star Rating gives you the opportunity to add ratings/reviews for desired data, products and services with the aid of a classic star rating system. You can make use of two shortcodes to embed a single inline star rating or a tabularly list of star ratings.

Supported Languages: English, French, German, Italian, Spanish

To insert a Universal Star Rating inside a post just type `[usr=5]` where 5 is the amount of stars.

To insert a list of Universal Star Ratings inside a post just type `[usrlist "Pizza:3" "Ice Cream:3.5" (...)]` where the first value is what you want to rate and the second value is the rating. Your list can be as long as you want it to be but it must consist of more than 1 key:value pairs.

== Installation ==

1. Upload the content of the ZIP-file `universal-star-rating.zip` to the `/wp-content/plugins/universal-star-rating/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

Alternatively

1. Upload the ZIP-File inside your wordpress admin panel under `Plugins > Install > Upload`
2. Activate the plugin through the 'Plugins' menu in WordPress

== Screenshots ==

1. This screen shot shows a list of ratings inside a (german) post using the `[usrlist (...)]` shorttag.

2. This screen shot shows the english version of the options page inside the admin panel of WordPress.

3. This screen shot shows the preview inside the options page

== Changelog ==

= 1.9.2 =
* Changed paths inside USR so that it now uses WP constants
* USR now loads all language files dynamically
* Added some new internal functions
* Code optimization

= 1.9.1 =
* Proofed that the plugin works with WP 4.0
* Updated locale files and translation document
* Updated admin panel

= 1.9.0 =
* Added custom images support
* Updated admin panel

= 1.8.0 =
* Added Schema.org support
* Updated admin panel
* Moved content because of a problem while generating stars with some hosting services
* Some minor bugfixes

= 1.7.1 =
* Removed some unclean Code to avoid notice messages in WP debug mode

= 1.7.0 =
* Added an option to override the star size inside both shortcodes
* Code optimization

= 1.6.6 =
* Modified the settings page
* Added 3 new source images

= 1.6.5 =
* Added rtl support for the admin panel 
* Some minor bug fixes

= 1.6.4 =
* Added a Italian and a Spanish language package (Thanks to anddab!)
* Updated the options page

= 1.6.3 =
* A minor bugfix release which provides a change to the generated image url so that it becomes w3c conform

= 1.6.2 =
* Some changes to the stylesheet because other ones were higher priorized

= 1.6.1 =
* Added an external stylesheet for the images so that other stylesheets can not cause any side effects

= 1.6.0 =
* Added an option to enable shortcodes inside comments
* Excluded some functions so that the code is more readable

= 1.5.1 =
* Changed the average rating inside lists so that there is just one digit left after the decimal point
* Changed the admin check so that user can log in into WordPress backend without admin rights

= 1.5.0 =
* Added a new source image
* Added a function to calculate the average rating inside a list of Universal Star Ratings
* Some minor bug fixes

= 1.4.4 =
* Added images in different sizes so that they look better (even with browser downsizing) and to improve the response time 

= 1.4.3 =
* Changed the img-tag so that the height of the images are now sized via css

= 1.4.2 =
* Added a sort function to the usable images inside the admin panel
* Edited the img-tag because of an reported error with safari browsers

= 1.4.1 =
* Fixed the German locale because of an reported error

= 1.4.0 =
* Merged the source images
* Added a new source image
* Updated functions to support these merged source images
* Added a new option to enable/disable text output
* Added functionalities to override the standard settings in shortcodes
* Updated localization and settings page
* Code optimization

= 1.3.1 =
* Made the source images smaller
* Changed the way the output images are created
* Bugfix: The setting for the max amount of stars is now working

= 1.3.0 =
* Added new images
* Modified settings page 

= 1.2.3 =
* Added a french language package
* Bugfix: Ratings which are too high are now set to the maximum amount of stars and not to 10 (which is the default value for the maximum)

= 1.2.2 =
* Added a readme file

= 1.2.1 =
* Code optimization
* Bugfix: Preview inside settings page works now as it was expected to

= 1.2.0 =
* Code optimization
* Implementation of localization (English and German)
* Added a settings page for
 1. Some information about this plugin
 2. Localization
 3. Star size
 4. Amount of stars (Doesn't work correct at this time)

= 1.1.0 =
* Updated multy rating functionality
 1. Changed the used shortcode so that it is easier to use it
 2. Removed the restriction for the amount of ratings inside a post

= 1.0.0 =
* Initial release with basic functionality to add star ratings inside posts

== Upgrade Notice ==

= 1.9.2 =
This is a bugfix release which addresses a problem with incorrect paths which causes the plugin to not work correct (ver 1.9.1). While I was debugging the code I did some code optimization and added some new functions which should make the plugin a little faster.

= 1.9.1 =
This version of USR comes with a small UI improvement for the admin panel and a little code optimization. I tested it with WP version 4.0.

= 1.9.0 =
Now USR is able to support custom images. In the past customized images were deleted by updating the plugin but now you can use a seperated directory for your own images. Be aware that this seperated directory may not be inside the plugins directory or it still will be deleted when updateing the plugin!

= 1.8.0 =
Added Schema.org support which can be activated inside the admin panel. Please be aware that this causes W3 errors if activated! There are some minor bugfixes inside this release, too.

= 1.7.1 =
Removed some unclean Code to avoid notice messages in WP debug mode.

= 1.7.0 =
This release includes the function to override the star size inside both shortcodes. Some code optimization is also included.

= 1.6.6 =
This release comes with a slight modification for the settings page and includes 3 new source images.

= 1.6.5 =
This release includes support for admin panels based on rtl language packages.

= 1.6.4 =
This package includes a Italian and a Spanish language package (Thanks to anddab!). I also updated the options page.

= 1.6.3 =
This is a minor bugfix release which provides a change to the generated image url so that it becomes w3c conform.

= 1.6.2 =
Another bugfix release because other stylesheets were higher priorized so that unwanted side effects still occured. Finally this release should do the magic.

= 1.6.1 =
This is a bugfix release for those who use stylesheets which causes any unwanted side effects. Now USR comes with its own stylesheet to make sure it works like it is expected to.

= 1.6.0 =
This release contains the functionality which is needed to use shortcodes inside comments. This is disabled by default but can be changed inside the Universal Star Rating options page. Now you can give your visitors the oportunity to rate your posts and pages.

= 1.5.1 =
This release contains an update to the function which calculates the average rating for lists so that there is just one digit left after the decimal point and there is a bugfix inside this release so that user whithout admin rights can log in into WordPress backend again.

= 1.5.0 =
With this update I added a new source image and the possibility to calculate the average rating inside a list of Universal Star Ratings. There are some minor bug fixes inside this update, too.

= 1.4.4 =
With this update I added images in different sizes so that they look better (even with browser downsizing) and to improve the response time.

= 1.4.3 =
This is a hotfix which solved an reported error with the sizing of the star images. The img-tag now works with css instead of the img attribute height.

= 1.4.2 =
I added a sort function to the admin panel so that the usable images are now sorted. The second thing I've changed is the img-tag because of an reported error with Safari browsers which should be fixed now.

= 1.4.1 =
I fixed the German locale because of an error which was reported by the member brit77. This issue caused some WordPress installations to throw errors so the plugin had to be removed via FTP.

= 1.4.0 =
I added some new functionalities! Now you can enable/disable the text output and it is possible to override settings inside the shortcodes so that you can be more flexible inside your posts.

= 1.3.1 =
The setting for the max amount of stars is now working and I changed the way images are build so that the source images are now much smaller.

= 1.3.0 =
Added some images and modified settings page so that you can choose one of them for your posts

= 1.2.3 =
This package includes a french language package and a bugfix.

= 1.2.2 =
Just added a readme file which is necessary to host this plugin on wordpress.org

= 1.2.1 =
This is just a bugfix release. Nothing to worry about!

= 1.2.0 =
Now you can change the settings of Universal Star Rating under the settings section inside your WordPress admin panel.
"Settings > Universal Star Rating"

Please note that the setting of the amount of stars will just change the shown maximum amount of stars inside the brackets but will not effect the viewed stars (picture) itself. This will work with an future release. I recommend to not change this value!

= 1.1.0 =
Please be aware that this update will change the way multy rating via `[usrlist (...)]` will work. All posts which include this shortcode have to be updated because the old shortcode won't work any longer.

For future releases I will make sure that old functions will still work (marked as deprecated) while I add newer ones. Inside a future update these deprecated functions will be removed. This will give you the oppportunity to update the plugin without the loss of functionality so that you can modify your older posts unnoticed by anyone. 

= 1.0.0 =
The initial release of this plugin which brings basic functionality with it.