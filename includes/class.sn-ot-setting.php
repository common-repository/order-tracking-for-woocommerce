<?php

if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

if ( ! class_exists( 'SN_OT_SETTING' ) ) {
    class SN_OT_SETTING {

        /**
         * Constructor
         * @description Function to initialize WP actions for the class
         */
        function __construct()
        {
            // Assets
            add_action( 'admin_enqueue_scripts', array( &$this, 'set_page_assets' ), 10 );
            //-------

            // Setting
            add_action( 'admin_post_sn_ot_update_setting', array( &$this, 'update_setting' ) );
            //--------
        }

        /**
         * Set page assets
         * @description Function to include the JS for product page
         */
        public function set_page_assets() {
            $page = @sanitize_key( $_GET['page'] );
            if ( $page == 'sn-ot-setting') {
                wp_enqueue_style('sn-ot-select2', SN_OT_ASSET_URL . '/css/select2.min.css', SN_OT_PLUGIN_VERSION);
                wp_enqueue_script('sn-ot-select2', SN_OT_ASSET_URL . '/js/select2.min.js', array('jquery'), SN_OT_PLUGIN_VERSION);
                wp_enqueue_script( 'sn-ot-setting', SN_OT_ASSET_URL . '/js/setting.js', array('jquery'), SN_OT_PLUGIN_VERSION );
            }
        }

        /**
         * Template page
         * @description Function to show the template page
         */
        public static function setting_page() {
            $args['page_name'] = 'setting';
            wc_get_template( 'setting.php', $args, '', SN_OT_TEMPLATE_PATH .'/' );
        }

        /**
         * Update setting
         * @description Function to update general setting
         */
        public function update_setting() {
            $fn_status = true;

            // Update option variables
            if($fn_status == true) {
                update_option( 'sn_ot_tracking_form_caption', sanitize_text_field( $_POST['sn_ot_tracking_form_caption'] ) );
                update_option( 'sn_ot_no_tracking_detail_found_text', sanitize_text_field( $_POST['sn_ot_no_tracking_detail_found_text'] ) );
                update_option( 'sn_ot_shipping_detail_text', sanitize_text_field( $_POST['sn_ot_shipping_detail_text'] ) );
                update_option( 'sn_ot_tracking_detail_text', sanitize_text_field( $_POST['sn_ot_tracking_detail_text'] ) );
                update_option( 'sn_ot_show_order_detail', sanitize_text_field( $_POST['sn_ot_show_order_detail'] ) );
            }
            //------------------------

            // Set message
            SN_OT_INIT::set_message(__('Settings updated', SN_OT_SLUG), 'success');
            //------------

            wp_redirect( 'admin.php?page=sn-ot-setting' );
        }
    }
}

$sn_ot_setting = new SN_OT_SETTING();
