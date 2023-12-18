<?php
/**
 * @author  FlyoutApps
 * @since   1.0
 * @version 1.0
 */

namespace flyoutapps\wfobpp;

class Filter_By_Product extends Filter_By {

	public function __construct() {
		$this->id = 'wfobpp_by_product';
		parent::__construct();

		add_filter( 'posts_where', array( $this, 'filter_where' ), 10, 2 );
	}

	public function dropdown_fields(){
		global $wpdb;

		$status = apply_filters( 'wfobp_product_status', 'publish' );
		$sql    = "SELECT ID,post_title FROM $wpdb->posts WHERE post_type = 'product'";
		$sql   .= ( $status == 'any' ) ? '' : " AND post_status = '$status'";
		$all_posts = $wpdb->get_results( $sql, ARRAY_A );

		$all_posts = array_filter( $all_posts, function( $product_post ) {
			if ( has_filter( 'wpml_element_type', 'wpml_element_type_filter' ) 
			  && has_filter( 'wpml_element_trid' ) 
			  && has_filter( 'wpml_get_element_translations' ) 
			  && has_filter( 'wpml_current_language' ) ) {

				$type = apply_filters( 'wpml_element_type', get_post_type( $product_post["ID"] ) );
				$trid = apply_filters( 'wpml_element_trid', false, $product_post["ID"], $type );
				$translations = apply_filters( 'wpml_get_element_translations', array(), $trid, $type );

				$current_language = apply_filters( 'wpml_current_language', null );

				foreach ( $translations as $translation ) {
					if ( $translation->element_id == $product_post["ID"] && $translation->language_code == $current_language ) {
						return true;
					}
				}
				return false;
			}
			return true;
		});

		$fields    = array();
		$fields[0] = esc_html__( 'All Products', 'woocommerce-filter-orders-by-product' );
		foreach ( $all_posts as $all_post ) {
			$fields[$all_post['ID']] = $all_post['post_title'];
		}

		return $fields;
	}

	// Modify where clause in query
	public function filter_where( $where, $query ) {
		if( $query->is_search() ) {
			if ( isset( $_GET[$this->id] ) && !empty( $_GET[$this->id] ) ) {
				$product = intval($_GET[$this->id]);

				if ( has_filter( 'wpml_element_type', 'wpml_element_type_filter' ) 
				  && has_filter( 'wpml_element_trid' )
				  && has_filter( 'wpml_get_element_translations' ) ) {

					$type = apply_filters( 'wpml_element_type', get_post_type( $product ) );
					$trid = apply_filters( 'wpml_element_trid', false, $product, $type );
					$translations = apply_filters( 'wpml_get_element_translations', array(), $trid, $type );
					$product_ids = array_map( function( $translation ) {
						return $translation->element_id;
					}, $translations );

					// Check if selected product is inside order query
					$where .= " AND wp_posts.ID IN (";
					$where .= $this->query_by_product( "product", $product_ids );
					$where .= ")";
				}
				else {
					// Check if selected product is inside order query
					$where .= " AND $product IN (";
					$where .= $this->query_by_product();
					$where .= ")";
				}
			}
		}
		return $where;
	}
}

new Filter_By_Product();