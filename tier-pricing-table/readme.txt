=== Tiered Pricing Table for WooCommerce ===

Contributors: bycrik, freemius
Tags: woocommerce, tiered pricing, dynamic price, price, wholesale
Requires at least: 4.2
Tested up to: 6.0
Requires PHP: 5.6
Stable tag: 2.8.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Allows you to set the price for a certain quantity of product. Shows quantity pricing table. Supports displaying the table in a tooltip.

== Description ==

Simple WooCommerce plugin which allows you to set different prices for different quantities of a product.
Display your pricing policy on a product page, and drive more sales by offering a discount for bulk!

The easiest to set up and powerful wholesale plugin for WooCommerce. Clean interface and extendable source code.

Features:

*   Set a specific price for a certain quantity of product
*   Set a specific price for a certain quantity of variation
*   Display pricing table on the product page (supports different places)
*   Display table in tooltip near product price (or variation price)
*   Customization (title, colors, positions, etc...)
*   Import/Export (WPAllImport supported)
*   REST API

Premium features:

*   Percentage discounts
*   Display percentage table
*   MOQ (minimum order quantity) for a product
*   Role-based pricing (including role-specific tiered pricing)
*   Set discounts in bulk for product categories
*   Show the lowest or range of price in the product catalog
*   Show tier price in the cart as a discount with the original price crossed out
*   Show total price on a product page
*   Clickable table rows
*   Summary block with pricing information
*   Built-in cache for the pricing strings

### Check out our site to get more information about the [Tiered Pricing Table](https://u2code.com/plugins/tiered-pricing-table-for-woocommerce/)

== Screenshots ==

1. Price table below the buy button
2. Create price rules
3. Settings
4. Display in tooltip
5. Price table below product summary
6. Create price rules for variation
7. Advanced settings
8. Discount in price
9. Price at the catalog
10. Percentage table
11. Summary table

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/tier-price-table` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the WooCommerce->Settings Name screen to configure the plugin

After installing the plugin set up your own settings

== Frequently Asked Questions ==

= What is the import format? =

"quantity:price,quantity:price"
For example:
"10:20,20:18" - in this case 20$ at 10pcs, $18 at 20pcs or more.
The same format is for the percentage-based rules:
"quantity:discount,quantity:discount"
Please note that you have to use a dot as a decimal separator because a comma is used to separate the pricing rules.

= Can I offer discounts across a variable product? =

Yes, you can.
The "summarize all variation" option in the plugin's settings will consider all variations as one product and calculate a discount based on every variation in the cart.

= Can I apply tiered pricing for manual (admin-made) orders? =

Yes, you can.
By default, this option is disabled to avoid conflicts with 3rd party plugins, but you can enable it via the following code:
`add_filter('tier_pricing_table/addons/manual_orders_active', '__return_true')`

== Changelog ==

= 1.1 =
* Fix bug with comma as thousand separators.
* Minor updates

= 2.0 =
* Fix bugs
* JS updating prices at product page
* Tooltip border
* Premium version

= 2.0.2 =
* Fix JS calculation prices
* Remove table from variation tier tables

= 2.1 =
* Support WooCommerce Taxes
* Do not show table head if column texts are blank
* Fix Updater
* Fix little issues

= 2.1.1 =
* Fixes
* Premium variable catalog prices

= 2.1.2 =
* Fixes
* Trial mode

= 2.2.0 =
* Added Import\Export tiered pricing
* Clickable quantity rows (Premium)
* Fix with some themes
* Fix mini-cart issue

= 2.2.1 =
* Fixed bugs
* Added total price feature

= 2.2.3 =
* Fixed bugs
* Added hooks

= 2.3.0 =
* Fix critical bug

= 2.3.1 =
* Fix jQuery issue

= 2.3.2 =
* Fix upgrading

= 2.3.3 =
* Fix taxes issue
* Added ability to calculate tiered price based on all variations
* Added ability to set bulk rules for variable product
* Added support minimum quantity in PREMIUM version
* Added summary table in PREMIUM version
* minor fixes
* Fixes for the popular themes

= 2.3.4 =
* Fix ajax issues
* Fix assets issues

= 2.3.5 =
* Fix issues
* Category tiers in premium version

= 2.3.6 =
* WooCommerce 4 variations fix

= 2.3.7 =
* Addon fixes
* Price Suffix fix
* Minor improves

= 2.4.0 =
* Role based pricing for the premium version
* Bug fixes
* Minor improves

= 2.4.1 =
* Freemius update
* Bugs fixes
* Minor improvements

= 2.5.0 =
* Freemius update
* Bugs fixes
* Performance improvements
* Improved role-based pricing
* WPML support

= 2.6.0 =
* Bugs fixes
* WPML extended support

= 2.6.1 =
* Security fix
* Fix WooCommerce Subscription variable products support
* Minor improvements

= 2.7.0 =
* New: static quantities for the pricing table
* New: Pricing cache for variable products
* New: WP All Import: "tiered pricing" import option
* Bugs fixes & minor improvements

= 2.8.0 =
* New: REST API
* New: WordPress 6.0 support
* New: WooCommerce 6.6 support
* Bugs fixes & minor improvements