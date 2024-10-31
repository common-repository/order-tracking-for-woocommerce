<?php

if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

if ( ! class_exists( 'SN_OT_INIT' ) ) {

    class SN_OT_INIT {

        /**
         * Constructor
         * @description Function to register and initialize WP actions for the plugin
         */
        function __construct() {

            register_activation_hook( SN_OT_FILE, array( 'SN_OT_INIT', 'install_plugin_data' ) );
            register_uninstall_hook( SN_OT_FILE, array( 'SN_OT_INIT', 'uninstall_plugin_data' ) );

            if ( class_exists( 'Woocommerce' ) ) {
                $this->init_plugin();
            } else {
                add_action( 'woocommerce_loaded', array( &$this, 'init_plugin' ) );
            }
        }

        /**
         * Initializer plugin
         * @description Function initialize action for the plugin
         */
        public function init_plugin() {
            add_filter( 'plugin_action_links_' . SN_OT_FILE_NAME, array($this, 'plugin_action_links'));
            add_action( 'admin_enqueue_scripts', array( &$this, 'set_admin_css' ), 10 );
            add_action( 'admin_enqueue_scripts', array( &$this, 'set_admin_js' ), 10 );
            add_action( 'admin_head', array( &$this, 'add_head_js'), 10 );
            add_action( 'admin_menu', array( &$this, 'set_menu' ) );

            add_action( 'woocommerce_init', array( $this, 'register_shipped_order_status' ) );
            add_filter( 'wc_order_statuses', array( &$this, 'add_shipped_order_status' ) );
            add_filter( 'woocommerce_email_classes', array( $this, 'add_woocommerce_emails' ) );
        }

        /**
         * Add action links on plugin page
         * @description Function to add plugin action links
         *
         * @param $links
         * @return array
         */
        public function plugin_action_links( $links ) {
            $plugin_links = array(
                '<a target="_blank" href="'.SN_OT_DOCUMENTATION_URL.'">' . __('Documentation', SN_OT_SLUG) . '</a>',
                '<a target="_blank" href="https://wordpress.org/support/plugin/order-tracking-for-woocommerce/reviews?rate=5#new-post">' . __('Review', SN_OT_SLUG) . '</a>',
            );
            return array_merge($plugin_links, $links);
        }

        /**
         * Set admin CSS
         * @description Function to include the admin CSS
         */
        public function set_admin_css() {
            wp_enqueue_style( 'sn-ot-admin', SN_OT_ASSET_URL . '/css/style.css', array(), SN_OT_PLUGIN_VERSION );
        }

        /**
         * Set admin JS
         * @description Function to include the admin JS
         */
        public function set_admin_js() {
            wp_enqueue_script( 'jquery-form', SN_OT_ASSET_URL . '/js/jquery.form.js', array('jquery'), SN_OT_PLUGIN_VERSION );
            wp_enqueue_script( 'sn-ot-admin', SN_OT_ASSET_URL . '/js/admin.js', array('jquery'), SN_OT_PLUGIN_VERSION );
        }

        /**
         * Add Head JS
         * @description Function to add global JS variables in adminhead
         */
        public function add_head_js() {
            ?>
            <script>
                var sn_ot_admin_url = "<?php echo( admin_url() ); ?>";
            </script>
            <?php
        }

        /**
         * Register order status
         * @description Function to register orders status with woocommerce
         *
         */
        public function register_shipped_order_status() {
            register_post_status('wc-shipped', array(
                'label'                     => 'Shipped',
                'public'                    => true,
                'exclude_from_search'       => false,
                'show_in_admin_all_list'    => true,
                'show_in_admin_status_list' => true,
                'label_count'               => _n_noop( 'Shipped <span class="count">(%s)</span>', 'Shipped <span class="count">(%s)</span>' )
            ));
        }

        /**
         * Add shipped order status to status list
         * @description Function to add orders status to woocommerce status list
         *
         * @param $order_statuses
         * @return array
         */
        public function add_shipped_order_status( $order_statuses ) {
            $new_order_statuses = array();
            foreach($order_statuses as $key => $status) {
                $new_order_statuses[ $key ] = $status;
                if('wc-on-hold' === $key) {
                    $new_order_statuses['wc-shipped'] = 'Shipped';
                }
            }
            return($new_order_statuses);
        }

        /**
         * Add emails to woocommerce
         * @description Function to add the plugin emails to woocommerce email list
         *
         * @param $emails
         * @return array
         */
        public function add_woocommerce_emails( $emails ) {
            $emails['SN_OT_CUSTOMER_SHIPPED_ORDER_MAIL'] = include('class-sn-ot-customer-shipped-order-mail.php');
            return($emails);
        }

        /**
         * Set menu
         * @description Function to set the menu for the plugin
         */
        public function set_menu() {
            global $current_user;

            if ( current_user_can( 'administrator' ) || is_super_admin() ) {
                $capabilities = $this->user_capabilities();
                foreach ( $capabilities as $capability => $cap_desc ) {
                    $current_user->add_cap( $capability );
                }
                unset ( $capabilities );
            }

            add_menu_page( __('Order Tracking', SN_OT_SLUG), __('Order Tracking', SN_OT_SLUG), 'sn_ot_manage_setting', 'sn-ot-setting', array('SN_OT_SETTING', 'setting_page') , SN_OT_ASSET_URL.'/images/icon.png' );
            add_submenu_page( 'sn-ot-setting', __('General Setting', SN_OT_SLUG), __('General Setting', SN_OT_SLUG), 'sn_ot_manage_setting', 'sn-ot-setting', array('SN_OT_SETTING', 'setting_page' ) );
            add_submenu_page( 'sn-ot-setting', __('Carriers', SN_OT_SLUG), __('Carriers', SN_OT_SLUG), 'sn_ot_manage_carriers', 'sn-ot-carriers', array('SN_OT_CARRIERS', 'carriers_page' ) );
        }

        /**
         * Install plugin data
         * @description Function to install the data at installation
         */
        public function install_plugin_data() {
            update_option( 'sn_ot_tracking_form_caption', SN_OT_TRACKING_FORM_CAPTION );
            update_option( 'sn_ot_no_tracking_detail_found_text', SN_OT_NO_TRACKING_DETAIL_FOUND_TEXT );
            update_option( 'sn_ot_shipping_detail_text', SN_OT_SHIPPING_DETAIL_TEXT );
            update_option( 'sn_ot_tracking_detail_text', SN_OT_TRACKING_DETAIL_TEXT );
            update_option( 'sn_ot_show_order_detail', SN_OT_SHOW_ORDER_DETAIL );
        }

        /**
         * Uninstall plugin data
         * @description Function to uninstall the data at un-installation
         */
        public function uninstall_plugin_data() {
            delete_option( 'sn_ot_tracking_form_caption' );
            delete_option( 'sn_ot_no_tracking_detail_found_text' );
            delete_option( 'sn_ot_shipping_detail_text' );
            delete_option( 'sn_ot_tracking_detail_text' );
            delete_option( 'sn_ot_show_order_detail' );
        }

        /**
         * Set message
         * @description Function to set the message in session
         * @param $message
         * @param $type
         */
        public static function set_message( $message, $type ) {
            $_SESSION['sn_ot_message'] = ['type' => $type, 'message' => $message];
        }

        /**
         * Show message
         * @description Function to show the message on the top of page
         */
        public static function show_message() {
            $message = sanitize_text_field( @$_SESSION['sn_ot_message']['message'] );
            if( $message ) {
                echo('<div id="message" class="sn-ot-message updated notice notice-success is-dismissible">');
                echo('<p>'. $message .'</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">'.__('Dismiss this notice', SN_OT_SLUG).'</span></button>');
                echo('</div>');
            }
            unset($_SESSION['sn_ot_message']);
        }

        /**
         * User capabilities
         * @description Function to return plugin user capabilities
         * @return array
         */
        private function user_capabilities() {

            return array (
                'sn_ot_manage_setting'         => __( 'User can manage General Setting', SN_OT_SLUG ),
                'sn_ot_manage_carriers'        => __( 'User can manage Carriers', SN_OT_SLUG )
            );
        }
    }
}

$sn_ot_init = new SN_OT_INIT();
