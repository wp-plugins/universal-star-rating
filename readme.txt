=== Universal Star Rating ===
Contributors: Chasil
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=mike%40jroene%2ede&lc=DE&item_name=Universal%20Star%20Rating%20%2d%20Cizero%2ede&no_note=0&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donate_SM%2egif%3aNonHostedGuest
Tags: stars, rating, movies, books, reviews, shortcodes
Requires at least: 3.0.1
Tested up to: 3.5.2
Stable tag: 1.2.2
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.html

You can easily add star based ratings to everything you want to rate inside your posts.

== Description ==

This plugin gives you the opportunity to add star based ratings to your posts/sites via two shortcodes.

To insert a Universal Star Rating inside a post just type `[usr=5]` where 5 is the amount of stars.

To insert a list of Universal Star Ratings inside a post just type `[usrlist "Pizza:7" "Ice Cream:8.5" (...)]` where the first value is what you want to rate and the second value is the rating. Your list can be as long as you want it to be but it must consist more than 1 key:value pairs.

== Installation ==

1. Upload the content of the ZIP-file `universal-star-rating.zip` to the `/wp-content/plugins/universal-star-rating/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

Alternatively

1. Upload the ZIP-File inside your wordpress admin panel under `Plugins > Install > Upload`
2. Activate the plugin through the 'Plugins' menu in WordPress

== Screenshots ==

1. This screen shot shows a list of ratings inside a (german) post using the `[usrlist (...)]` shorttag.

== Changelog ==

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

= 1.2.2 =
Just added this readme file which is necessary if I want to host this plugin on wordpress.org

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