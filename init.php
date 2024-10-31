<?php
/*
    Plugin Name: PayFlexi Checkout for WooCommerce
    Plugin URI: https://developers.payflexi.co
    Description: PayFlexi buy now pay later checkout for Woocommerce is a payment option that allows you to accept pay in installment from your customers.
    Version: 1.1.0
    Author: PayFlexi
    Author URI: https://payflexi.co
    License: GPLv2 or later
    License URI: http://www.gnu.org/licenses/gpl-2.0.txt
    WC requires at least: 3.8.0
    WC tested up to: 6.5.1
*/
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define constants.
if ( defined('PAYFLEXI_CHECKOUT_VERSION')) {
	return;
} else {
	define('PAYFLEXI_CHECKOUT_VERSION', '1.1.0');
}

if (!defined('PAYFLEXI_CHECKOUT_DIR')) {
	define('PAYFLEXI_CHECKOUT_DIR', plugin_dir_path( __FILE__ ));
}

if (!defined('PAYFLEXI_CHECKOUT_FILE')) {
	define( 'PAYFLEXI_CHECKOUT_FILE', __FILE__ );
}

if (!defined('PAYFLEXI_CHECKOUT_URL')) {
	define( 'PAYFLEXI_CHECKOUT_URL', plugins_url( '/', __FILE__ ));
}

if (!defined('PAYFLEXI_CHECKOUT_INC')) {
	define('PAYFLEXI_CHECKOUT_INC', PAYFLEXI_CHECKOUT_DIR . '/includes/' );
}

if (!defined('PAYFLEXI_CHECKOUT_INIT')) {
	define('PAYFLEXI_CHECKOUT_INIT', plugin_basename( __FILE__ ) );
}

if (!defined('PAYFLEXI_CHECKOUT_ASSETS_URL')) {
	define('PAYFLEXI_CHECKOUT_ASSETS_URL', PAYFLEXI_CHECKOUT_URL . 'assets' );
}
/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-payflexi-checkout.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.1.0
 */
function run_payflexi_checkout() {

    $plugin = new Payflexi_Checkout();
    $plugin->run();
}

function woocommerce_payflexi_checkout_init() {
	run_payflexi_checkout();
}

add_action( 'plugins_loaded', 'woocommerce_payflexi_checkout_init');

