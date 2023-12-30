<?php
/**
 * @author  FlyoutApps
 * @since   1.0
 * @version 1.0
 */

namespace flyoutapps\wfobpp;

class Filter_By_Category extends Filter_By {

	public function __construct() {
		$this->id = 'wfobpp_by_category';
		parent::__construct();

		add_filter( 'posts_where', array( $this, 'filter_where' ), 10, 2 );
	}

	public function dropdown_fields(){

		$terms = get_terms( array('taxonomy' => 'product_cat', 'fields' => 'id=>name' ) );

		$fields = array();
		$fields[0] = esc_html__( 'All Categories', 'woocommerce-filter-orders-by-product' );

		foreach ( $terms as $id => $name ) {
			$fields[$id] = $name;
		}

		return $fields;
	}

	public function filter_where( $where, $query ) {
		if( $query->is_search() ) {
			if ( isset( $_GET[$this->id] ) && !empty( $_GET[$this->id] ) ) {
				$cat = intval($_GET[$this->id]);

				// Check if selected category is inside order query
				$where .= " AND $cat IN (";
				$where .= $this->query_by_category();
				$where .= ")";
			}
		}
		return $where;
	}

	private function query_by_category(){
		global $wpdb;
		$t_term_relationships = $wpdb->term_relationships;

		$query  = "SELECT $t_term_relationships.term_taxonomy_id FROM $t_term_relationships WHERE $t_term_relationships.object_id IN (";
		$query .= $this->query_by_product();
		$query .= ")";

		return $query;
	}
}