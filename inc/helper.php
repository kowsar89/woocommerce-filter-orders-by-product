<?php
/**
 * @author  FlyoutApps
 * @since   1.0
 * @version 1.0
 */

namespace flyoutapps\wfobpp;

use Automattic\WooCommerce\Utilities\OrderUtil;

class Helper {

    public static function is_HPOS_active() {
        if ( ! class_exists( 'Automattic\WooCommerce\Utilities\OrderUtil' ) ) {
            return false;
        }

        if ( OrderUtil::custom_orders_table_usage_is_enabled() ) {
            return true;
        } else {
            return false;
        }
    }

	public static function query_by_product_hpos(){
		global $wpdb;
        $t_orders = $wpdb->prefix . "wc_orders";
		$t_order_items = $wpdb->prefix . "woocommerce_order_items";  
		$t_order_itemmeta = $wpdb->prefix . "woocommerce_order_itemmeta";

		// Build join query, select meta_value
		$query  = "SELECT $t_order_itemmeta.meta_value FROM";
		$query .= " $t_order_items LEFT JOIN $t_order_itemmeta";
		$query .= " on $t_order_itemmeta.order_item_id=$t_order_items.order_item_id";

		// Resultant table after join query
		/*------------------------------------------------------------------
		order_id | order_item_id* | order_item_type | meta_key | meta_value
		-------------------------------------------------------------------*/

		// Build where clause, where order_id = $t_posts.ID
		$query .= " WHERE $t_order_items.order_item_type='line_item'";
		$query .= " AND $t_order_itemmeta.meta_key='_product_id'";
		$query .= " AND $t_orders.Id=$t_order_items.order_id";

		// Visulize result
		/*-------------------------------------------------------------------
		order_id    | order_item_type | meta_key    | meta_value
		$t_posts.ID | line_item       | _product_id | <result>
		---------------------------------------------------------------------*/

		return $query;
	}

}