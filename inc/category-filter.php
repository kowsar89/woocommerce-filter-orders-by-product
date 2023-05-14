<?php

namespace flyoutapps\wfobp;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Category_Filter extends Filter_Base {

	private function __construct() {
		parent::__construct( 'wfobp_by_category' );

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

		$terms = get_terms(
			array(
				'taxonomy' => 'product_cat',
				'fields' => 'id=>name',
			)
		);

		$fields = array();
		$fields[0] = esc_html__( 'All Categories', 'woocommerce-filter-orders-by-product' );

		foreach ( $terms as $id => $name ) {
			$fields[ $id ] = $name;
		}

		return $fields;
	}

	public function query_where( $where ) {
		if ( is_search() ) {
			if ( isset( $_GET[ $this->id ] ) && ! empty( $_GET[ $this->id ] ) ) {
				$cat = intval( $_GET[ $this->id ] );

				// Check if selected category is inside order query
				$where .= " AND $cat IN ({$this->query_by_category()})";
			}
		}
		return $where;
	}

	private function query_by_category() {
		global $wpdb;
		$t_term_relationships = $wpdb->term_relationships;

		$query = "SELECT $t_term_relationships.term_taxonomy_id FROM $t_term_relationships WHERE $t_term_relationships.object_id IN ({$this->query_by_product()})";

		return $query;
	}
}
