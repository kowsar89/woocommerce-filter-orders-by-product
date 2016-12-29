=== WooCommerce Filter Orders by Product ===
Contributors: kowsar89
Tags: woocommerce, filter, order, product, admin
Requires at least: 3.0.1
Tested up to: 4.7
Stable tag: 2.0.6
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html

This plugin lets you filter the WooCommerce orders by any specific product

== Description ==
Ever wanted to filter the order page results by any specific product? Now with this plugin, you can!

After installing this plugin, a new filter dropdown will appear in WooCommerce Orders screen. This dropdown shows a list of all published products. Just select a product and click on "Filter" button. Results will display only the orders which contains that specific product.

Currently this only works for published products. If you want it to work for all product statuses eg. draft, private etc you have to add the following code in your theme's functions.php file:

	add_action( 'wfobp_product_status', 'filter_order_by_product_status' );
	function filter_order_by_product_status(){
		return 'any';
	}

== Installation ==
1. Upload the entire 'woocommerce-filter-orders-by-product' folder to the '/wp-content/plugins/' directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.

You will see a new filter appears in WooCommerce Orders page.

== Screenshots ==
1. From admin panel, Click on "Woocommerce>Orders" to visit the orders screen. There you'll see a new dropdown filter appears
2. Click on that dropdown and you'll see a list of all products. Select a product and click on "Filter" button. Results will display only the orders which contains that specific product.

== Changelog ==

= 2.0.6 =
* Added hook for changing product status

= 2.0.5 =
* Fixed SQL injection bug

= 2.0.4 =
* Improved code

= 2.0.3 =
* Fix: Language

= 2.0.2 =
* Fixed translation bug (Thanks to Kasperta)

= 2.0.1 =
* Fixed a minor bug

= 2.0.0 =
* New: search dropdown
* Fixed a major bug

= 1.0.0 =
* Initial release