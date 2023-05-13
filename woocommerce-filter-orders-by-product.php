<?php
/**
 * Plugin Name: WooCommerce Filter Orders by Product
 * Plugin URI: http://kowsarhossain.com/
 * Description: This plugin lets you filter the WooCommrce Orders by any specific product
 * Version: 3.2.1
 * Author: Kowsar Hossain
 * Author URI: http://kowsarhossain.com
 * Text Domain: woocommerce-filter-orders-by-product
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WFOBP_VERSION', '3.2.1' );
define( 'WFOBP_PATH', plugin_dir_path( __FILE__ ) );

final class WFOBP {

	public function __construct() {
		add_action( 'init', array( $this, 'load_textdomain' ) );
		add_action( 'plugins_loaded', array( $this, 'bootstrap' ) );
	}

	public function load_textdomain() {
		load_plugin_textdomain( 'woocommerce-filter-orders-by-product', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	public function bootstrap() {
		if ( ! class_exists( 'WooCommerce' ) || ! is_admin() ) {
			return;
		}

		$this->autoload();

		add_action( 'admin_enqueue_scripts', array( $this, 'scripts_and_styles' ), 15 );

		new flyoutapps\wfobpp\Filter_By_Product();
		new flyoutapps\wfobpp\Filter_By_Category();
	}

	public function scripts_and_styles() {
		$screen = get_current_screen();
		if ( $screen->id != 'edit-shop_order' ) {
			return;
		}

		wp_add_inline_script( 'selectWoo', 'jQuery(document).ready(function($){$(".wfobpp-select2").selectWoo();});' );
	}

	public function autoload() {
		spl_autoload_register(
			function( $className ) {
				$namespace = 'flyoutapps\\wfobpp\\';
				$class = str_replace( $namespace, '', $className );
				$filePath = WFOBP_PATH . 'inc/' . str_replace( '_', '-', strtolower( $class ) ) . '.php';

				if ( file_exists( $filePath ) ) {
					require_once $filePath;
				}
			}
		);
	}
}

new WFOBP();
