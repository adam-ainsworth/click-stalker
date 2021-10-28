=== Click Stalker ===

Contributors: adam.ainsworth
Donate link: https://adamainsworth.co.uk/donate/
Tags: digital marketing, click tracking, tracking cookies, stalking
Requires at least: 2.7.0
Tested up to: 5.8.1
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Allows you to add arbitrary tracking code to downloadable assets that a user can click on.

== Description ==

If you have downloadable assets on your site such as PDFs and would like to track when users download them via tracking scripts or pixels, this plugin will allow you to do that.

It should work with ANY site, by dynamically introducing an intermediary page when the user clicks, waitd for the code to fire and then forward the user on to the asset.

== Installation ==

1. Upload the `click-stalker` folder to the `/wp-content/plugins/` directory
2. Navigate to wp-admin/plugins.php on your site (your WP plugin page)
3. Alternatively, upload `click-stalker.zip` via the upload plugin section at wp-admin/plugins.php
4. Activate this plugin. 
5. Add your tracking codes to the settings page.

OR you can just install it with WordPress by going to Plugins >> Add New >> and typing Click Stalker

== Frequently Asked Questions ==

= Will this definitely work on my site =

Not definitely, but it should. As long as the assets are in the wp-content/uploads folder then they should be captured. However, if your theme does something funky, this may not be the case.

= Can I have different code depending on the asset? =

At the moment, it only supports the same tracking code on all assets. If there is demand, I will add this in the future.

= What features will you add in the future? =

None planned, but different code depending on the asset is a possibility. Probably an option to customise the click through link and the uploads folder as well.

Please contact me if you would like any other enhancements - https://adamainsworth.co.uk/contact

= Why is it called 'Click Stalker' =

Because click tracking is creepy and manipulative. But who am I to judge? :-)

== Screenshots ==

1. Click Stalker options page

== Changelog ==

= 1.0.0 =
* Initial release
