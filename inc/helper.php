<?php

namespace flyoutapps\wfobp;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Helper {

	public static function is_wc_order_screen() {
		$screen = get_current_screen();
		return 'edit-shop_order' === $screen->id;
	}


	protected function query_by_product() {
		global $wpdb;
		$t_posts = $wpdb->posts;
		$t_order_items = $wpdb->prefix . 'woocommerce_order_items';
		$t_order_itemmeta = $wpdb->prefix . 'woocommerce_order_itemmeta';

		$query  = "SELECT $t_order_itemmeta.meta_value FROM";
		$query .= " $t_order_items LEFT JOIN $t_order_itemmeta";
		$query .= " on $t_order_itemmeta.order_item_id=$t_order_items.order_item_id";
		$query .= " WHERE $t_order_items.order_item_type='line_item'";
		$query .= " AND $t_order_itemmeta.meta_key='_product_id'";
		$query .= " AND $t_posts.ID=$t_order_items.order_id";

		return $query;
	}
}
