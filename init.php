<?php
/*
Plugin Name: Order Tracking For WooCommerce (Basic)
Plugin URI: https://www.codeteam.in/product/woocommerce-order-tracking/
Description: Easily track orders placed on your e-commerce website.
Version: 1.1
Requires at least: 4.9
Tested up to: 5.9.2
Requires PHP: 5.4
WC tested up to: 6.3.1
Author: Siddharth Nagar
Author URI: http://www.codeteam.in/
License: GPLv2
*/
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ( !defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

if ( ! defined( 'SN_OT_AUTHOR_URL' ) ) {
    define( 'SN_OT_AUTHOR_URL', 'https://www.codeteam.in/' );
}

if ( ! defined( 'SN_OT_PLUGIN_URL' ) ) {
    define( 'SN_OT_PLUGIN_URL', SN_OT_AUTHOR_URL.'product/woocommerce-order-tracking/' );
}

if ( ! defined( 'SN_OT_DOCUMENTATION_URL' ) ) {
    define( 'SN_OT_DOCUMENTATION_URL', SN_OT_AUTHOR_URL.'documentation/woocommerce-order-tracking/introduction/' );
}

if ( ! defined( 'SN_OT_PLUGIN_VERSION' ) ) {
    define( 'SN_OT_PLUGIN_VERSION', '1.1' );
}

if ( ! defined( 'SN_OT_SLUG' ) ) {
    define( 'SN_OT_SLUG', 'sn-order-tracking' );
}

if ( ! defined( 'SN_OT_DIR' ) ) {
    define( 'SN_OT_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'SN_OT_URL' ) ) {
    define( 'SN_OT_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'SN_OT_FILE' ) ) {
    define( 'SN_OT_FILE', __FILE__ );
}

if ( ! defined( 'SN_OT_FILE_NAME' ) ) {
    define( 'SN_OT_FILE_NAME', plugin_basename(__FILE__) );
}

if ( ! defined( 'SN_OT_TEMPLATE_PATH' ) ) {
    define( 'SN_OT_TEMPLATE_PATH', SN_OT_DIR . 'templates' );
}

if ( ! defined( 'SN_OT_ASSET_URL' ) ) {
    define( 'SN_OT_ASSET_URL', SN_OT_URL . 'assets' );
}


if ( ! defined( 'SN_OT_TRACKING_FORM_CAPTION' ) ) {
    define('SN_OT_TRACKING_FORM_CAPTION', 'Track your order using your order number and email.');
}
if ( ! defined( 'SN_OT_NO_TRACKING_DETAIL_FOUND_TEXT' ) ) {
    define('SN_OT_NO_TRACKING_DETAIL_FOUND_TEXT', 'Sorry, the order number and billing email provided does not match with details we have. Please try again.');
}
if ( ! defined( 'SN_OT_SHIPPING_DETAIL_TEXT' ) ) {
    define('SN_OT_SHIPPING_DETAIL_TEXT', 'Your order has been shipped by {carrier_name} {carrier_logo}.');
}
if ( ! defined( 'SN_OT_TRACKING_DETAIL_TEXT' ) ) {
    define('SN_OT_TRACKING_DETAIL_TEXT', 'Your tracking number is <strong>{tracking_number}</strong>.');
}
if ( ! defined( 'SN_OT_SHOW_ORDER_DETAIL' ) ) {
    define('SN_OT_SHOW_ORDER_DETAIL', 1);
}

if ( ! defined( 'SN_OT_CARRIER_LIST' ) ) {
    define('SN_OT_CARRIER_LIST', array('FEDEX'      => array('name' => 'FedEx', 'tracking_url' => 'https://www.fedex.com/apps/fedextrack/?action=track&locale=en_US&cntry_code=us&tracknumbers={tracking_number}'),
        'DHL'        => array('name' => 'DHL', 'tracking_url' => 'https://www.dhl.com/content/g0/en/express/tracking.shtml?brand=DHL&AWB={tracking_number}'),
        'CANPAR'     => array('name' => 'Canpar Courier', 'tracking_url' => 'https://www.canpar.com/'),
        'CORREOS'    => array('name' => 'Correos', 'tracking_url' => 'https://www.correos.es/ss/Satellite/site/aplicacion-1349167937616-herramientas_y_apps/detalle_app-num={tracking_number}-sidioma=en_GB'),
        'NEXIVE'     => array('name' => 'Nexive.it', 'tracking_url' => 'https://www.sistemacompleto.it/Tracking-Spedizioni-Nexive.aspx?b={tracking_number}&lang=IT')));
}

/**
 * Show woocommerce admin notice
 * @description Function to show woocommerce admin notice
 */
function sn_ot_install_woocommerce_admin_notice() {
    ?>
    <div class="error">
        <p><?php _e( 'Order Tracking is enabled but not effective. It requires WooCommerce in order to work.', SN_OT_SLUG ); ?></p>
    </div>
    <?php
}

/**
 * Initialize plugin
 * @description Function to initialize the plugin
 */
function sn_ot_init() {

    load_plugin_textdomain( SN_OT_SLUG, false, dirname( plugin_basename( __FILE__ ) ). '/languages/' );

    if(file_exists(SN_OT_DIR.'/includes/class.sn-ot-init.php')) {
        require_once(SN_OT_DIR.'/includes/class.sn-ot-init.php');
    }

    if(file_exists(SN_OT_DIR.'/includes/class.sn-ot-admin.php')) {
        require_once(SN_OT_DIR.'/includes/class.sn-ot-admin.php');
    }

    if(file_exists(SN_OT_DIR.'/includes/class.sn-ot-frontend.php')) {
        require_once(SN_OT_DIR.'/includes/class.sn-ot-frontend.php');
    }

    if(file_exists(SN_OT_DIR.'/includes/class.sn-ot-carriers.php')) {
        require_once(SN_OT_DIR.'/includes/class.sn-ot-carriers.php');
    }

    if(file_exists(SN_OT_DIR.'/includes/class.sn-ot-setting.php')) {
        require_once(SN_OT_DIR.'/includes/class.sn-ot-setting.php');
    }
}
add_action( 'sn_ot_init', 'sn_ot_init' );


/**
 * Install plugin
 * @description Function to initiate the plugin installation
 */
function sn_ot_install() {

    if ( ! function_exists( 'WC' ) ) {
        add_action( 'admin_notices', 'sn_ot_install_woocommerce_admin_notice' );
    }
    else {
        do_action( 'sn_ot_init' );
    }
}
add_action( 'plugins_loaded', 'sn_ot_install', 10 );