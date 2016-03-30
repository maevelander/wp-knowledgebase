=== WP Knowledgebase ===
Contributors: EnigmaWeb
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=CEJ9HFWJ94BG4
Tags: WP Knowledgebase, knowledgebase, knowledge base, faqs, wiki
Requires at least: 3.1
Tested up to: 4.2
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
* Integrated breacrumb (on/off)
* Display comments on knowledgebase articles (on/off)
* Drag & Drop for custom ordering of articles and categories
* Works across all major browsers and devices - IE8+, Safari, Firefox, Chrome
* Editable slug (default is /knowledgebase )

On activation, the plugin will create a page called "Knowledgebase" and on that page there will be the shortcode `[kbe_knowledgebase]` with page template "KBE" assigned. The plugin also copies that page template and related CSS into your active theme. You can directly edit those files to adjust the layout and design of the knowledgebase.

= Official Demo =

*	[Click here](http://demo.enigmaweb.com.au/knowledgebase/) for out-of-the-box demo

= User Examples =

*	[Cub Themes](http://cubthemes.com/support/) knowledgebase
*	[Orpheus](http://orpheus-app.com/knowledgebase) Android app knowledgebase
*	[OzBeans](http://ozbeanz.com.au/knowledgebase/) - IT consultants

= Languages =

* English
* German
* Dutch
* Bulgarian
* Spanish - Spain
* Spanish - USA
* Spanish - Puerto Rico
* Brazilian Portaguese
* Swedish

Translators, thank you all for your contribution to this plugin. Much appreciated.

If you'd like to help translate this plugin into your language please get in touch. It's very easy - you don't have to know any code and it's a great way to contribute to the WordPress community. Please [contact Maeve](http://www.enigmaplugins.com/contact/)


== Installation ==

1. Upload the `wp-knowledgebase` folder to the `/wp-content/plugins/` directory or install it from the plugin directory via your Plugins dash.
1. Activate the WP Knowledgebase plugin through the 'Plugins' menu in WordPress
1. Configure the plugin by going to the `Knowledgebase` tab that appears in your admin menu.

On activation, the plugin will create a page called "Knowledgebase" and on that page there will be the shortcode `[kbe_knowledgebase]` with page template "KBE" assigned. The plugin also copies that page template and related CSS into your active theme. You can directly edit those files to adjust the layout and design of the knowledgebase.
 
== Frequently Asked Questions ==

= I'm getting a 404 error =

Please go to Settings > Permalinks and resave your permalink structure.

= Can I add the search bar to my theme template manually? =

Yes, use this php snippet `<?php kbe_search_form(); ?>`

= Can users vote on articles? Like a thumbs up/down thing? =

This feature is not built into the plugin, however you can use another plugin to achieve this easily. I recommend [WTI Like Post](https://wordpress.org/plugins/wti-like-post/)

= How can I customise the design? =

You can do some basic presentation adjustments via Knowledgebase > Settings. Beyond this, you can completely customise the design via your theme css.

= It does not look good on my theme =

Please check that the shortcode `[kbe_knowledgebase]` is added on the Knowledgebase main page, with page template "KBE" assigned. The plugin also copies that page template and related CSS into your active theme. You can directly edit those files to adjust the layout and design of the knowledgebase for better integration with your theme as needed.

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
= 1.1.2 =
* Fixed count issue on parent category.
* Fixed page title issue.

= 1.1.1 =
* fixed warnings.
* 'parent' => 0 removed from the terms array

= 1.1 =
* Fixed search template issue.
* Fixed widgets issue 
* Fixed template redirect issue.
* Some minor css issues fixed.

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

= 1.0.8 =
* Added strip_tags() function for excerpt for the search results.
* Query corrected for getting KBE title and fixing the activation error.

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
