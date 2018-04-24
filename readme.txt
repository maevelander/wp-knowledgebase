=== WP Knowledgebase ===
Contributors: EnigmaWeb, helgatheviking, sormano, Base29, macbookandrew
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=CEJ9HFWJ94BG4
Tags: WP Knowledgebase, knowledgebase, knowledge base, faqs, wiki
Requires at least: 2.7
Tested up to: 4.9
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Simple and flexible knowledgebase plugin for WordPress

== Description ==

Create an attractive and professional knowledgebase. It's easy to use, easy to customise, and works with any theme.

= Key Features =

* Simple and easy to use
* Fully RESPONSIVE
* Customise your catalogue presentation easily (choose theme colour, sidebar layouts, number of articles to show etc)
* Super fast search, with predictive text - handy!
* A selection of sidebar widgets (search, categories, tags, posts)
* Integrated breadcrumb (on/off)
* Display comments on knowledgebase articles (on/off)
* Drag & Drop for custom ordering of articles and categories
* Works across all major browsers and devices - IE8+, Safari, Firefox, Chrome
* Editable slug (default is /knowledgebase )

= Important =

On activation, the plugin will create a page called "Knowledgebase" and on that page there will be the shortcode `[kbe_knowledgebase]`. If you want to change the slug of that page do so via the WP Knowledgebase settings.

= Advanced Customisation =

Developers, you can completely customise the way the WP Knowledgebase displays by copying the plugin templates to your theme and customising them there. You may be familiar with this method of templating as used by WooCommerce.

In the plugin's root directory you will find a folder called `template`. You can override that folder and any of the files within, by copying them into your active theme and renaming the folder to `/yourtheme/wp_knowledgebase`. WP Knowledgebase plugin will automatically load any template files you have in that folder in your theme, and use them instead of its default template files. If no such folder or files exist in your theme, it will use the ones from the plugin.

This is the safest way to customise the WP Knowledebase templates, as it means that your changes will not be overwritten when the plugin updates.

= Official Demo =

*	[Click here](http://demo.enigmaweb.com.au/knowledgebase/) for out-of-the-box demo

= User Examples =

*	[Orpheus](http://orpheus-app.com/knowledgebase) Android app knowledgebase
*	[Cub Themes](http://cubthemes.com/support/) knowledgebase
* [Infinte](https://br.infinite.sx/wiki/) Wiki

= Languages =

English, German, Dutch, Blugarian, Spanish - Spain, Spanish - USA, Spanish - Puerto Rico, Brazilian Portaguese, Swedish, Polish and Indonesian.

Translators, thank you all for your contribution to this plugin. Much appreciated. If you'd like to help translate this plugin into your language please get in touch. It's very easy - you don't have to know any code and it's a great way to contribute to the WordPress community. Please [contact Maeve](http://www.enigmaplugins.com/contact/)


== Installation ==

1. Upload the `wp-knowledgebase` folder to the `/wp-content/plugins/` directory or install it from the plugin directory via your Plugins dash.
1. Activate the WP Knowledgebase plugin through the 'Plugins' menu in WordPress
1. Configure the plugin by going to the `Knowledgebase` tab that appears in your admin menu.

= Important =

On activation, the plugin will create a page called "Knowledgebase" and on that page there will be the shortcode `[kbe_knowledgebase]`. If you want to change the slug of that page do so via the WP Knowledgebase settings.

= Advanced Customisation =

Developers, you can completely customise the way the WP Knowledgebase displays by copying the plugin templates to your theme and customising them there. You may be familiar with this method of templating as used by WooCommerce.

In the plugin's root directory you will find a folder called `template`. You can override that folder and any of the files within, by copying them into your active theme and renaming the folder to `/yourtheme/wp_knowledgebase`. WP Knowledgebase plugin will automatically load any template files you have in that folder in your theme, and use them instead of its default template files. If no such folder or files exist in your theme, it will use the ones from the plugin.

This is the safest way to customise the WP Knowledebase templates, as it means that your changes will not be overwritten when the plugin updates.
 
== Frequently Asked Questions ==

= I'm getting a 404 error =

Please go to Settings > Permalinks and resave your permalink structure.

= Can I add the search bar to my theme template manually? =

Yes, use this php snippet `<?php kbe_search_form(); ?>`

= Can users vote on articles? Like a thumbs up/down thing? =

This feature is not built into the plugin, however you can use another plugin to achieve this easily. I recommend [WTI Like Post](https://wordpress.org/plugins/wti-like-post/)

= How can I customise the design? =

You can do some basic presentation adjustments via Knowledgebase > Settings.

Developers, you can completely customise the way the WP Knowledgebase displays by copying the plugin templates to your theme and customising them there. You may be familiar with this method of templating as used by WooCommerce.

In the plugin's root directory you will find a folder called `template`. You can override that folder and any of the files within, by copying them into your active theme and renaming the folder to `/yourtheme/wp_knowledgebase`. WP Knowledgebase plugin will automatically load any template files you have in that folder in your theme, and use them instead of its default template files. If no such folder or files exist in your theme, it will use the ones from the plugin.

This is the safest way to customise the WP Knowledebase templates, as it means that your changes will not be overwritten when the plugin updates.

= It does not look good on my theme =

Please check that the shortcode `[kbe_knowledgebase]` is added on the Knowledgebase main page.  You can tweak the design using CSS in your theme. Or for more advanced customisation see previous point.

= Can I control privacy or content restrictions for WP Knowledgebase categories and posts? =

Yes. Any content restriction solution that is compatible with Custom Post Types should work with WP Knowledgebase.

= Can I use WP Knowledgebase in my Language? =

Yes, the plugin is internationalized and ready for translation. If you would like to help with a translation please [contact me](http://www.enigmaweb.com.au/contact)
You can also use it WPML. After installing and activating both plugins, go to WPML > Translation Management > Multilangual Content Setup > scroll all the way down > tick the checkbox 'custom posts' and 'custom taxanomies' for this post type, set to 'Translate'.

= Can import/export WP Knowledgebase data? =

Yes. You can import/export data using the built in WordPress function via Tools. It may not import any images in use (although it will import the file paths) so you will need to copy across any images from your old site to the new site uploads folder via FTP. If images still appear broken or missing then you might need to run a search and replace tool to correct the image filepaths for your new site.

= Where can I get support for this plugin? =

If you've tried all the obvious stuff and it's still not working please request support via the forum.


== Screenshots ==

1. An example of WP Knowledgebase in action, main KB home view
2. Another example of WP Knowledgebase front-end, article view
3. The settings screen in WP-Admin
4. Available widgets

== Changelog ==

= 1.1.9 =
* Search fix

= 1.1.8 =
* Adds support for the excerpt
* Uses the_excerpt in search results

= 1.1.7 =
* Fixes for Divi Theme

= 1.1.6 =
* CSS updates for responsive widths

= 1.1.5 =
* fix notice when activating plugin
* code consistency changes and cleanup structuring
* fix admin css filename
* fix notices on widgets when creating them + other tweaks/improvements
* never delete data without confirming
* widget fixes
* add direct access checks to all files
* enhance template files
* add template-functions.php
* improve how breadcrumbs are handled
* fix initial kb page on install
* use default theme comments template
* improve search
* remove unused functions
* improve code commenting
* move taxonomy sort order from admin-only functions file
* deafult settings sanitize callback
* fix german translation
* fix db prefixes 
* add migration class

= 1.1.4 = 
* Enqueue scripts & styles issue fixed.
* Minor typo fixed

= 1.1.3 = 
* custom Styles and Templates issue fixed.
* fixed +/- icon filepaths so they change properly

= 1.1.2 =
* fixed count issue on parent category
* fixed page title issue - it will now reflect page name rather than being stuck on "knowledgebase"
* fixed pluralization if there is only one article
* register stylesheet and JS and call only on KBE pages 
* only eneque and load search inline script if search is enabled
* move some scripts to footer for better performance
* only print inline styles if color is defined
* add admin notice if theme contains customized templates
* optimize images
* use get_stylesheet_directory instead of replacing built-in constant
* fixed a few typos

= 1.1.1 =
* fixed error warnings
* 'parent' => 0 removed from the terms array to fix reorder of subcategories
* Registered support for 'author'

= 1.1 =
* renames index.php to wp-knowledgebase.php
* no longer automatically copies template files to theme folder
* no longer deletes kbe template folder from theme
* fixes template redirect issue
* fixes search template issue
* uninstall.php is called automatically as of WP2.7
* no longer silences error_reporting
* no need to flush_rewrite_rules() 3 times
* updates widget registration
* adds optional custom headers/footers
* fix for undefined $i variable
* fixes conditional loading of scripts/styles
* sanitizes merge options
* some minor css fixes

= 1.0.9 =
* Replace TEMPLATEPATH with STYLESHEETPATH.
* Replace get_template_directory_uri() with get_stylesheet_directory_uri().

= 1.0.8 =
* Added strip_tags() function for excerpt for the search results.
* Query corrected for getting KBE title and fixing the activation error.
* Images path corrected in CSS

= 1.0.7 =
* CSS file path corrected.

= 1.0.6 =
* Update query corrected on order.

= 1.0.5 =
* Issue with child theme fixed.
* Function corrected for copying plugin template files in to active theme folder

= 1.0.4 =
* Breadcrumbs text issue fixed.
* Added support for Sub-Categories.
* Added support for child theme.
* Added support for multi site.
* Some Code Correction.
* Added support for revisions.
* Languages added

= 1.0.3 =
* Minor CSS Changes.
* Breadcrumbs link issue fixed.
* Truncate function removed from the titles 
* Function corrected for loading plugin text domain

= 1.0.2 =
* Translation issue fixed
* Miscellaneous minor fixes

= 1.0.1 =
* Function fixed which was assigning template to the page
* Theme styling issue fixed

= 1.0 =
* Initial release

== Upgrade Notice ==

= 1.1.9 =
* Search fix

= 1.1.8 =
Adds support for the excerpt, used in search results

= 1.1.7 =
Fixes for Divi Theme

= 1.1.6 =
CSS updates for responsive widths in default templates. Check body width of KB following update.

= 1.1.5 =
This is a major upgrade to fix and improve a lot of underlying code in the plugin. You won't notice huge changes on the front end or feature wise, but it is important to upgrade for long term stability, security and optimization of the plugin.

A huge thank you to Jeroen Sormani for his big contributions to this release, as well as to other ongoing contributors to this plugin. 

= 1.1.4 =
Contains important fix for users with custom templates. Please upgrade immediately.

= 1.1.3 =
This is an urgent upgrade which fixes issues for users with custom templates. Please upgrade immediately.

= 1.1.2 =
This upgrade fixes a lot of small long running issues and optimises performance.  All users are encouraged to upgrade.

A big thank you to macbookandrew for his contributions to this release.

= 1.1.1 =
This is a minor release addressing a few small bugs and enhancements.

= 1.1 =
This is a major release which focuses on the plugin's templating system and overall code improvements. Upgrade carefully and please read new FAQ info and description regarding the templating.

A big thank you to helgatheviking for her contributions to this release.


= 1.0.9 =
Minor release, but fixes an important stylesheet issue. Upgrade immediately.

= 1.0.8 =
Minor release.

= 1.0.7 =
Patch only.

= 1.0.6 =
Patch only.

= 1.0.5 =
Important fixes to templating and css. Upgrade immediately.

= 1.0.4 =
Major release.

= 1.0.3 =
Minor release, fixing a number of irritating problems. Encouraged to upgrade.

= 1.0.2 =
Minor release.

= 1.0.1 =
Minor release.

= 1.0 =
Initial release
