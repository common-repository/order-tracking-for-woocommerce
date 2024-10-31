<?php

if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

if ( ! class_exists( 'SN_OT_CARRIERS' ) ) {
    class SN_OT_CARRIERS {

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
            add_action( 'admin_post_sn_ot_update_carriers', array( &$this, 'update_carriers' ) );
            //--------
        }

        /**
         * Set page assets
         * @description Function to include the JS for product page
         */
        public function set_page_assets() {
            $page = @sanitize_key( $_GET['page'] );
            if ( $page == 'sn-ot-carriers') {
                wp_enqueue_style('sn-ot-select2', SN_OT_ASSET_URL . '/css/select2.min.css', SN_OT_PLUGIN_VERSION);
                wp_enqueue_script('sn-ot-select2', SN_OT_ASSET_URL . '/js/select2.min.js', array('jquery'), SN_OT_PLUGIN_VERSION);
                wp_enqueue_script( 'sn-ot-setting', SN_OT_ASSET_URL . '/js/setting.js', array('jquery'), SN_OT_PLUGIN_VERSION );
            }
        }

        /**
         * Dashboard page
         * @description Function to show the dashboard of the plugin
         */
        public static function carriers_page() {

            $args['page_name'] = 'carriers';
            $args['carriers_list'] = SN_OT_CARRIER_LIST;
            $args['selected_carriers_list'] = get_option('sn_ot_carriers', []);
            wc_get_template( 'carriers.php', $args, '', SN_OT_TEMPLATE_PATH .'/' );
        }

        /**
         * Update carriers
         * @description Function to update carriers
         */
        public function update_carriers() {
            $fn_status = true;

            // Update option variables
            if($fn_status == true) {
                $carriers = array_map('sanitize_text_field', $_POST['sn_ot_carriers']);
                update_option( 'sn_ot_carriers', $carriers );
            }
            //------------------------

            // Set message
            SN_OT_INIT::set_message(__('Carriers updated', SN_OT_SLUG), 'success');
            //------------

            wp_redirect( 'admin.php?page=sn-ot-carriers' );
        }
    }
}

$sn_ot_carriers = new SN_OT_CARRIERS();
