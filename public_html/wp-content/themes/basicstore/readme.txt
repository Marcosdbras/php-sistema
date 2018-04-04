=== Basic Store ===
Contributors: Theme.al,algoritmika,karzin,anbinder
Requires at least: WordPress 4.0
Tested up to: WordPress 4.8
Version: 1.4.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Tags: one-column, two-columns, right-sidebar, editor-style, featured-images, footer-widgets, translation-ready

== Description ==

Basicstore is a free theme for shop owners using WooCommerce who like the bootstrap framework and want a clean design

== Installation ==

1. In your admin panel, go to Appearance -> Themes and click the 'Add New' button.
2. Type in Basic Store in the search form and press the 'Enter' key on your keyboard.
3. Click on the 'Activate' button to use your new theme right away.

== Setting Up Demo ==

If you want to setup your front page like our demo, just follow these steps:

1. Create a Page Called Home
2. Select the page as "Front Page" template
3. Setup it using the native WordPress customizer, on Appearance > Themes > Basicstore > Customize > Theme options > Front Page Options
4. Optionally, add some widgets to the Footer sidebar. If you just want some simple text, like our demo, you can use the "Custom Html" widget
5. Don't forget to add a menu. You can do that accessing the Front page on front-end and clicking on "Add a menu"

== Frequently Asked Questions ==

= Does this theme support any plugins? =

Basic Store is made for WooCommerce plugin, but you may use it for any WordPress website, even without using WooCommerce.

== Changelog ==

= 1.4.3 - Oct 19 2017 =
- Fix outdated templates

= 1.4.2 - Oct 02 2017 =
- Test

= 1.4.1 - Sept 29 2017 =
- Fix Theme URI

= 1.4.0 - Sept 27 2017 =
- Change style.css to basicstore.css
- Fix header image
- Fix home sidebar

= 1.3.9 - Sept 12 2017 =
- Add Custom background color support
- Fix custom header theme support
- Add editor style
- Change style.css tags

= 1.3.8 - Sept 08 2017 =
- Improve plugin's description

= 1.3.7 - Sept 07 2017 =
- Improve plugin's description
- Fix widget checking on front page template
- Fix links
- Fix theme checking

= 1.3.6 - July 28 2017 =
- Woocommerce templates files updated.

= 1.3.5 - July 12 2017 =
- Demo link in description in style.css updated

= 1.3.4 - July 10 2017 =
- Upgrade To Pro link updated.
- Hide Or Display option problem of Top Rated Product in customizer fixed.

= 1.3.3 - Jun 27 2017 =
- Upgrade To Pro Link Added
- Front Page (tpl-home.php) added.
- Customize Options for Show or Hide Latest Products, Featured Products, Top Rated Products and Product Categories added.

= 1.3.2 - Jun 20 2017 =
- Temporary removed "grouped.php" WooCommerce template file to pass WP tests
- Changed WooCommerce text-domain for WooCommerce template files from "woocommerce" to "basicstore"

= 1.3.1 - Jun 13 2017 =
- Correctly escaped get_post_meta on header.php

= 1.3.0 - Jun 13 2017 =
- Wrong tag in style.css "two column" and "editor-style"
- Unique prefix for all theme functions
- Escape get_post_meta in header.php
- Use "wp_enqueue_style" function to load all theme styles and not "@import"
- Display "Site Title and "Tagline" option does not work.
- Overriding WordPress globals is prohibited on grouped.php line 38 and cross-sells.php line 37
- Removed from "add_theme_support" "search-form" and "comment-form" as we are using custom code
- Changed to "has_custom_logo" WP function to check whether the site has a custom logo or not on header.php
- Removed all files that are not being overwritten from WooCommerce plugin
- Search form on header changed to default WP search if WooCommerce plugin is not enabled

= 1.2.3 - Jun 09 2017 =
- Fixed 2 translation issue on result-count.php

= 1.2.2 - Jun 08 2017 =
- Calendar widget layout issue on small screens

= 1.2.1 - Jun 07 2017 =
- Warning call_user_func_array() expects parameter 1 to be a valid callback, function 'basic_customize_preview_js' not found or invalid function name
- Css breaks in sidebar panel when Large image: linked in a caption and Large image: Hand Coded
- Removed readme.md
- Added license info for bootstrap tab collapse

= 1.2.0 - Jun 01 2017 =
- Less folder moved accidentally
- Sticky post styling
- WP default gallery styling
- Table styling without .table class needed
- Post images adjusted with alignments classes and made responsive
- Styled page links
- Title with long words. added word-wrap css property to post
- Styled nav links
- Styled post password form
- Fixed some bugs on comment styling
- Fixed missing jQuery script, added as dependency on the Bootstrap script

= 1.1.0 - Jun 01 2017 =
- The site header is covered by the admin bar.
- PHP NOTICE: wp-content/themes/basicstore/inc/woocommerce.php:180 - Trying to get property of non-object
- woocommerce/single-product/add-to-cart/grouped.php version 3.0.3 is out of date. The core version is 3.0.7,
- fixed layout issue on archive page
- archive page - Fatal error: Call to a member function get_cart_url() on a non-object in wp-content/themes/basicstore/inc/woocommerce.php on line 89
- Text domain changed from "basicstore" to "basic-store"
- Theme URI is redirected, this is not allowed.
- Missing singular placeholder, needed for some languages.
- payments-methods.php Translation issue

= 1.0.0 - Apr 18 2017 =
* Initial release

== Upgrade Notice ==

= 1.4.3 =
- Fix outdated templates

== Credits ==
* Based on Underscores http://underscores.me/, (C) 2012-2016 Automattic, Inc., [GPLv2 or later](https://www.gnu.org/licenses/gpl-2.0.html)
* WooCommerce plugin [GNUv3](https://github.com/woocommerce/woocommerce/blob/master/license.txt)
* Bootstrap framework [MIT](https://github.com/twbs/bootstrap/blob/master/LICENSE)
* WP Bootstrap Nav Walker [GNUv3](https://github.com/wp-bootstrap/wp-bootstrap-navwalker/blob/master/LICENSE.txt)
* WP Bootstrap pagination [GNUv2](https://github.com/talentedaamer/Bootstrap-wordpress-pagination/blob/master/LICENSE)
* Bootstrap Tab Collapse [GNUv2](https://github.com/flatlogic/bootstrap-tabcollapse)