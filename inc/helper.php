<?php
/**
 * @author  FlyoutApps
 * @since   1.0
 * @version 1.0
 */

namespace flyoutapps\wfobpp;

use Automattic\WooCommerce\Utilities\OrderUtil;

class Helper {

    public static function is_HPOS_active() {
        if ( ! class_exists( 'Automattic\WooCommerce\Utilities\OrderUtil' ) ) {
            return false;
        }

        if ( OrderUtil::custom_orders_table_usage_is_enabled() ) {
            return true;
        } else {
            return false;
        }
    }

}