<?php
/**
 * Plugin Name: WooCommerce Filter Orders by Product
 * Plugin URI: http://kowsarhossain.com/
 * Description: This plugin lets you filter the WooCommrce Orders by any specific product
 * Version: 3.2
 * Author: Md. Kowsar Hossain
 * Author URI: http://kowsarhossain.com
 * Text Domain: woocommerce-filter-orders-by-product
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'WFOBP_VERSION', '3.2' );
define( 'WFOBP_PATH'   , plugin_dir_path( __FILE__ ) );

final class WFOBP {

	public function __construct() {
		add_action( 'init', array( $this, 'load_textdomain' ) );
		add_action( 'plugins_loaded', array( $this, 'includes' ) );
	}

    public function load_textdomain(){
        load_plugin_textdomain( 'woocommerce-filter-orders-by-product', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );
    }

	public function includes(){
		if ( !class_exists( 'WooCommerce' ) || !is_admin() ){
			return;
		}
		require_once WFOBP_PATH . 'inc/init.php';
	}
}

new WFOBP();