<?php

namespace flyoutapps\wfobp;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Product_Filter extends Filter_Base {

	private function __construct() {
		parent::__construct( 'wfobp_by_product' );

		add_filter( 'posts_join', array( $this, 'query_join' ) );
		add_filter( 'posts_where', array( $this, 'query_where' ) );
	}

	public static function instance() {
		static $instance = null;
		if ( null == $instance ) {
			$instance = new static();
		}

		return $instance;
	}

	public function dropdown_fields() {
		global $wpdb;

		$product_status = apply_filters( 'wfobp_product_status', 'publish' );
		$all_products = $wpdb->get_results( $wpdb->prepare( "SELECT ID,post_title FROM {$wpdb->posts} WHERE post_type = 'product'  AND post_status = %s", $product_status ), ARRAY_A );

		$fields    = array();
		$fields[0] = esc_html__( 'All Products', 'woocommerce-filter-orders-by-product' );

		foreach ( $all_products as $product ) {
			$fields[ $product['ID'] ] = $product['post_title'];
		}

		return $fields;
	}

	public function query_join( $join ) {
		if ( !is_search() || empty( $_GET[ $this->id ] ) ) {
			return $join;
		}

		$join = Helper::join_table_order_product_lookup( $join );

		return $join;
	}

	public function query_where( $where ) {
		global $wpdb;

		if ( !is_search() || empty( $_GET[ $this->id ] ) ) {
			return $where;
		}

		$product = intval( $_GET[ $this->id ] );
		$t_order_product_lookup = $wpdb->prefix . 'wc_order_product_lookup';

		$where .= " AND $t_order_product_lookup.product_id = $product";

		return $where;
	}
}
