<?php

namespace flyoutapps\wfobp;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Bootstrap {

	private function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'scripts_and_styles' ), 15 );
		$this->initialize();
	}

	public static function instance() {
		static $instance = null;
		if ( null == $instance ) {
			$instance = new static();
		}

		return $instance;
	}

	public function scripts_and_styles() {
		if ( ! Helper::is_order_page() ) {
			return;
		}

		wp_add_inline_script( 'selectWoo', 'jQuery(document).ready(function($){$(".wfobpp-select2").selectWoo();});' );
	}

	private function initialize() {
		Product_Filter::instance();
		Category_Filter::instance();
	}

}
