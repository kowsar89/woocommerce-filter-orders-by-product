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
}
