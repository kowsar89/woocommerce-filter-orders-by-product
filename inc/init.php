<?php
/**
 * @author  FlyoutApps
 * @since   1.0
 * @version 1.0
 */

namespace flyoutapps\wfobpp;

class Initialize {

	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'scripts_and_styles' ), 15 );
		$this->load_filters();
	}

	public function scripts_and_styles(){
		$screen = get_current_screen();

		if( !in_array( $screen->id, array( 'edit-shop_order', 'woocommerce_page_wc-orders' ) ) ) return;

		wp_add_inline_script( 'selectWoo', 'jQuery(document).ready(function($){$(".wfobpp-select2").selectWoo();});' );
	}

	public function load_filters(){
		require_once WFOBP_PATH . 'inc/filter-by.php';
		require_once WFOBP_PATH . 'inc/filter-by-product.php';
		require_once WFOBP_PATH . 'inc/filter-by-category.php';
	}
}

new Initialize();