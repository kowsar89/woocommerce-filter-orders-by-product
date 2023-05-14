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

	public static function join_table_order_product_lookup( $join ) {
		global $wpdb;
		$t_order_product_lookup = $wpdb->prefix . 'wc_order_product_lookup';

		if ( str_contains( $join, $t_order_product_lookup ) ) {
			return $join;
		}

		$join .= " INNER JOIN $t_order_product_lookup ON $t_order_product_lookup.order_id = {$wpdb->posts}.ID";

		return $join;
	}

	public static function join_table_term_relationships( $join ) {
		global $wpdb;
		$t_term_relationships = $wpdb->term_relationships;
		$t_order_product_lookup = $wpdb->prefix . 'wc_order_product_lookup';

		if ( str_contains( $join, $t_term_relationships ) ) {
			return $join;
		}

		$join .= " INNER JOIN $t_term_relationships ON $t_term_relationships.object_id = $t_order_product_lookup.product_id";

		return $join;
	}

	public static function join_table_term_taxonomy( $join ) {
		global $wpdb;
		$t_term_relationships = $wpdb->term_relationships;
		$t_term_taxonomy = $wpdb->term_taxonomy;

		if ( str_contains( $join, $t_term_taxonomy ) ) {
			return $join;
		}

		$join .= " INNER JOIN $t_term_taxonomy ON $t_term_taxonomy.term_taxonomy_id = $t_term_relationships.term_taxonomy_id";

		return $join;
	}

	public static function query_by_product() {
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
