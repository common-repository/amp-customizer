=== Plugin Name ===
Contributors: melvr, autovisie
Donate link: http://autovisie.nl/devblog/amp-customizer/
Tags: amp, mobile, amp customizer
Requires at least: 3.0.1
Tested up to: 4.5
Stable tag: 4.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin can be used to customize the output of the Google AMP plugin by Automattic (https://wordpress.org/plugins/amp/).
When using this plugin you have several possibilities to customize the output, which you will find at the AMP Customizer settings page.

This plugin also provides the possibility to exclude posts from AMP.

Based on the WordPress Boilerplate that can be found on http://wppb.io/.

* Plugin website: http://autovisie.nl/devblog/amp-customizer/
* Want to know more about our tools, check out our website at: http://autovisie.nl/devblog/

== Description ==

This plugin can be used to customize the output of the Google AMP plugin by Automattic (https://wordpress.org/plugins/amp/).
When using this plugin you have several possibilities to customize the output, which you will find at the AMP Customizer settings page.

This plugin also provides the possibility to exclude posts from AMP.

Based on the WordPress Boilerplate that can be found on http://wppb.io/.

* Plugin website: http://autovisie.nl/devblog/amp-customizer/
* Want to know more about our tools, check out our website at: http://autovisie.nl/devblog/

= Why we built it =

We wanted to use the Google AMP plugin, however we wanted to change the default output so we could give it the Autovisie look and feel.
To do this, we created this plugin and want to share this with the WordPress community.

== Installation ==

Activate the plugin and set the correct settings (settings -> AMP Customizer).
Then you will see an couple of things you can change.

1. Upload 'amp-customizer' to the '/wp-content/plugins/' directory
2. Activate the plugin through the ‘Plugins’ menu in WordPress
3. Set the correct settings (settings -> AMP Customizer)

**========**
**Settings**
**========**

= Add Featured Image =
When this setting is set, the plugin will add the featured image to the content. It will be displayed above the content.

= Select a Logo =
When selecting a logo from the media library, it will be displayed in the header of the AMP page.
If this is not set, the plugin will display the normal AMP page with the site title.

= Header Background =
You can select a color to use for the header (which contains the logo, if this is set).

= Title Text Color =
This color will be used for the title of the AMP page.

= Content Text Color =
With this color setting, you can set the color for the main text in the AMP page.

= Content Width (without px) =
Here you can set the content with of the AMP page. You can only use pixel sizes here.
Add the value without the “px” behind the value.

= Hide a post =
When editing a post, and having this plugin active you will see a extra metabox setting for “Hide in Google AMP”.
If you set this to “Yes”, the canonical link to the AMP page will be removed from the original page source.
When visiting the page like this: http://www.yoursite.com/category/post/amp/ it will redirect you to the original permalink of the post: http://www.yoursite.com/category/post/

== Frequently Asked Questions ==

= Does this plugin have any requirements? =
*Yes, it depends on the Google AMP plugin by Automattic (https://wordpress.org/plugins/amp/)*

== Screenshots ==

1. Menu
2. Settings
3. Post editing

== Changelog ==

= 1.0.1 =
* Fix to prevent a foreach loop when no settings are set.

= 1.0.0 =
* Our first release!