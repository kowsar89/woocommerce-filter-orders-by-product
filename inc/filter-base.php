<?php

namespace flyoutapps\wfobp;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Filter_Base {

	public $id;

	public function __construct( $id ) {
		$this->id = $id;
		add_action( 'restrict_manage_posts', array( $this, 'filter_dropdown' ), 50 );
		// add_filter( 'posts_request', array( $this, 'debug_query' ) );
	}

	abstract public function dropdown_fields();

	public function filter_dropdown() {
		if ( ! Helper::is_wc_order_screen() ) {
			return;
		}

		$fields = $this->dropdown_fields();
		?>
		<select class="wfobpp-select2" name="<?php echo esc_attr( $this->id ); ?>" id="<?php echo esc_attr( $this->id ); ?>">
			<?php
			$current_value = isset( $_GET[ $this->id ] ) ? sanitize_text_field( wp_unslash( $_GET[ $this->id ] ) ) : '';

			foreach ( $fields as $key => $title ) {
				printf(
					'<option value="%s"%s>%s</option>',
					esc_attr( $key ),
					$key == $current_value ? ' selected="selected"' : '',
					esc_html( $title )
				);
			}
			?>
		</select>
		<?php
	}

	/**
	 * Returns list of product id
	 */
	protected function query_by_product() {
		global $wpdb;
		$t_posts = $wpdb->posts;
		$t_order_items = $wpdb->prefix . 'woocommerce_order_items';
		$t_order_itemmeta = $wpdb->prefix . 'woocommerce_order_itemmeta';

		// Build join query, select meta_value
		$query  = "SELECT $t_order_itemmeta.meta_value FROM";
		$query .= " $t_order_items LEFT JOIN $t_order_itemmeta";
		$query .= " on $t_order_itemmeta.order_item_id=$t_order_items.order_item_id";

		// Resultant table after join query

		/*
		------------------------------------------------------------------
		order_id | order_item_id* | order_item_type | meta_key | meta_value
		-------------------------------------------------------------------
		*/

		// Build where clause, where order_id = $t_posts.ID
		$query .= " WHERE $t_order_items.order_item_type='line_item'";
		$query .= " AND $t_order_itemmeta.meta_key='_product_id'";
		$query .= " AND $t_posts.ID=$t_order_items.order_id";

		// Visulize result

		/*
		-------------------------------------------------------------------
		order_id    | order_item_type | meta_key    | meta_value
		$t_posts.ID | line_item       | _product_id | <result>
		---------------------------------------------------------------------
		*/

		return $query;
	}

	public function debug_query( $query ) {
		echo '<div style="margin-left:200px;max-width:960px;">';
		var_dump( $query );
		echo '</div>';
		return $query;
	}
}
