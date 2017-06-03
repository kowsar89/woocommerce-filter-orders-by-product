<?php
/**
 * Plugin Name: WooCommerce Filter Orders by Product
 * Plugin URI: http://kowsarhossain.com/
 * Description: This plugin lets you filter the WooCommrce Orders by any specific product
 * Version: 2.0.7
 * Author: Md. Kowsar Hossain
 * Author URI: http://kowsarhossain.com
 * Text Domain: woocommerce-filter-orders-by-product
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 */

if ( ! defined( 'WPINC' ) ) die;

class FOA_Woo_Filter_Orders_by_Product{
	private static $instance = null;

	private function __construct() {
		if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || !is_admin() ){
			return;
		}
		add_action( 'init', array( $this, 'load_textdomain' ) );
		add_action( 'restrict_manage_posts', array( $this, 'product_filter_in_order' ), 50  );
		add_action( 'posts_where', array( $this, 'product_filter_where' ));
		add_action( 'admin_enqueue_scripts', array( $this, 'scripts_and_styles' ));
	}

	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

    // Textdomain
    public function load_textdomain(){
        load_plugin_textdomain( 'woocommerce-filter-orders-by-product', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );
    }

	// Display dropdown
	public function product_filter_in_order(){
		global $typenow, $wpdb;

		if ( 'shop_order' != $typenow ) {
			return;
		}
		$status = apply_filters( 'wfobp_product_status', 'publish' );
		$sql    = "SELECT ID,post_title FROM $wpdb->posts WHERE post_type = 'product'";
		$sql   .= ( $status == 'any' ) ? '' : " AND post_status = '$status'";
		$all_posts = $wpdb->get_results($sql, ARRAY_A);

		$values = array();
		foreach ($all_posts as $all_post) {
			$values[$all_post['ID']] = $all_post['post_title'];
		}
	    ?>
	    <span id="foa_order_product_filter_wrap">
		    <select name="foa_order_product_filter" id="foa_order_product_filter">
		    <option value=""><?php _e('All products', 'woocommerce-filter-orders-by-product'); ?></option>
		    <?php
		        $current_v = isset($_GET['foa_order_product_filter'])? $_GET['foa_order_product_filter']:'';
		        foreach ($values as $key => $title) {
		            printf
		                (
		                    '<option value="%s"%s>%s</option>',
		                    $key,
		                    $key == $current_v? ' selected="selected"':'',
		                    $title
		                );
		            }
		    ?>
		    </select>
		    <div id="fuzzSearch">
		    	<div id="fuzzNameContainer">
		    		<span class="fuzzName"></span>
		    		<span class="fuzzArrow"></span>
		    	</div>
		    	<div id="fuzzDropdownContainer">
		    		<input type="text" value="" class="fuzzMagicBox" placeholder="<?php _e('Search...', 'woocommerce-filter-orders-by-product'); ?>" />
		    		<ul id="fuzzResults">
		    		</ul>
		    	</div>
		    </div>
		</span>
	    <script type="text/javascript">
			jQuery('#foa_order_product_filter').fuzzyDropdown({
			  mainContainer: '#fuzzSearch',
			  arrowUpClass: 'fuzzArrowUp',
			  selectedClass: 'selected',
			  enableBrowserDefaultScroll: true
			});
	   </script>
	    <?php
	}

	// modify where clause in query
	public function product_filter_where( $where ) {
		if( is_search() ) {
			global $wpdb;
			$t_posts = $wpdb->posts;
			$t_order_items = $wpdb->prefix . "woocommerce_order_items";  
			$t_order_itemmeta = $wpdb->prefix . "woocommerce_order_itemmeta";

			if ( isset( $_GET['foa_order_product_filter'] ) && !empty( $_GET['foa_order_product_filter'] ) ) {
				$product = intval($_GET['foa_order_product_filter']);
				$where .= " AND $product IN (SELECT $t_order_itemmeta.meta_value FROM $t_order_items LEFT JOIN $t_order_itemmeta on $t_order_itemmeta.order_item_id=$t_order_items.order_item_id WHERE $t_order_items.order_item_type='line_item' AND $t_order_itemmeta.meta_key='_product_id' AND $t_posts.ID=$t_order_items.order_id)";
			}
		}
		return $where;
	}
	// scripts_and_styles
	public function scripts_and_styles(){
		wp_enqueue_script( 'foa-fuzzy-script', plugin_dir_url( __FILE__ ).'fuzzy-dropdown.min.js', array( 'jquery' ) );
		wp_enqueue_style( 'foa-fuzzy-styles', plugin_dir_url( __FILE__ ).'style.css' );
	}
}

FOA_Woo_Filter_Orders_by_Product::instance();
