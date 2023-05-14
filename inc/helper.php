<?php

namespace flyoutapps\wfobp;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Helper {

	public static function is_order_page() {
		$screen = get_current_screen();
		return 'edit-shop_order' === $screen->id;
	}
}
