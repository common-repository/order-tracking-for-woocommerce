<?php

if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

if ( ! class_exists( 'SN_OT_ADMIN' ) ) {
    class SN_OT_ADMIN {

        /**
         * Constructor
         * @description Function to initialize WP actions for the class
         */
        function __construct()
        {
            // Add meta box to post edit
            add_action( 'add_meta_boxes', array( $this, 'order_tracking_box' ) );
            //--------------------------

            // Add email content
            add_action( 'woocommerce_email_before_order_table', array($this, 'order_tracking_email'), 20, 4 );
            //------------------

            // Add custom action on order status change
            add_action( 'woocommerce_order_status_changed', array(&$this, 'send_email_notifications'), 20, 3 );
            //-----------------------------------------

            // Save meta box
            add_action( 'woocommerce_process_shop_order_meta', array($this, 'save_order_tracking_details'), 10 );
            //--------------
        }

        /**
         * Set order tracking meta box
         * @description Function to show order tracking meta box in post add/edit
         */
        public function order_tracking_box() {
            add_meta_box('sn_ot_order_tracking_meta_box', __( 'Order Tracking', SN_OT_SLUG), array( $this, 'order_tracking_meta_box_content'), 'shop_order', 'side','high');
        }

        /**
         * Set order tracking meta box
         * @description Function to show order tracking meta box in post add/edit
         *
         * @param $post
         */
        public function order_tracking_meta_box_content( $post ) {

            $order = get_post_custom($post->ID);
            $args['order'] = $order;
            $args['carriers_list'] = SN_OT_CARRIER_LIST;
            $args['selected_carriers_list'] = get_option('sn_ot_carriers', []);
            wc_get_template( 'order-tracking-meta-box.php', $args, '', SN_OT_TEMPLATE_PATH .'/');
        }

        /**
         * Save order tracking details
         * @description Function to save order tracking details
         *
         * @param $order_id
         */
        public function save_order_tracking_details( $order_id ) {

            $order = wc_get_order($order_id);
            if($order) {
                // Set Order Tracking Code
                $tracking_data = null;
                $tracking_code_list = array_map( 'sanitize_text_field', $_POST['sn_ot_tracking_code'] );
                foreach($tracking_code_list as $key => $tracking_code) {
                    if(trim($tracking_code)) {
                        $tracking_data[] = ['tracking_number' => trim($tracking_code), 'status' => sanitize_text_field($_POST['sn_ot_tracking_code_status'][$key])];
                    }
                }
                if($tracking_data == null) {
                    $order->delete_meta_data('sn_ot_tracking_code');
                }
                else {
                    $order->update_meta_data('sn_ot_tracking_code', $tracking_data);
                }
                //------------------------

                // Set Order Completed Date
                if(sanitize_text_field($_POST['sn_ot_completed_date'])!='') {
                    $order_completed_date = stripslashes($_POST['sn_ot_completed_date']).' '.str_pad(stripslashes($_POST['sn_ot_order_completed_hour']), 2, '0', STR_PAD_LEFT).':'.str_pad(stripslashes($_POST['sn_ot_order_completed_minute']), 2, '0', STR_PAD_LEFT);
                    $order->update_meta_data('_order_completed_date', $order_completed_date);
                }
                else {
                    $order->delete_meta_data('_order_completed_date');
                }
                //-------------------------

                // Set Carrier Name
                if(isset($_POST['sn_ot_carrier_name'])) {
                    $order->update_meta_data('sn_ot_carrier_name', stripslashes($_POST['sn_ot_carrier_name']));
                }
                else {
                    $order->delete_meta_data('sn_ot_carrier_name');
                }
                //-----------------

                // Save Order
                $order->save();
                //-----------
            }
        }

        public function order_tracking_email($order, $sent_to_admin, $plain_text, $email) {

            if($email->id == 'sn_ot_shipped_order_mail') {
                $order_meta = get_post_custom($order->get_id());

                if(isset($order_meta['sn_ot_tracking_code'][0]) && $order_meta['sn_ot_tracking_code'][0] != '') {

                    $shipping_detail_text = get_option('sn_ot_shipping_detail_text');
                    $tracking_detail_text = get_option('sn_ot_tracking_detail_text');

                    $shipping_detail_text = str_replace('{carrier_name}', SN_OT_CARRIER_LIST[$order_meta['sn_ot_carrier_name'][0]]['name'], $shipping_detail_text);
                    $shipping_detail_text = str_replace('{carrier_logo}', '<img src="'.SN_OT_ASSET_URL.'/images/carrier-logos/'.strtolower($order_meta['sn_ot_carrier_name'][0]).'.png" class="carrier-logo" />', $shipping_detail_text);


                    if(is_serialized($order_meta['sn_ot_tracking_code'][0])) {

                        $tracking_number_text = implode(',', array_column(maybe_unserialize($order_meta['sn_ot_tracking_code'][0]), 'tracking_number'));
                        $tracking_detail_text = str_replace('{tracking_number}', $tracking_number_text, $tracking_detail_text);
                        $tracking_url = str_replace('{tracking_number}', $tracking_number_text, SN_OT_CARRIER_LIST[$order_meta['sn_ot_carrier_name'][0]]['tracking_url']);
                    }
                    else {
                        $tracking_detail_text = str_replace('{tracking_number}', $order_meta['sn_ot_tracking_code'][0], $tracking_detail_text);
                        $tracking_url = str_replace('{tracking_number}', $order_meta['sn_ot_tracking_code'][0], SN_OT_CARRIER_LIST[$order_meta['sn_ot_carrier_name'][0]]['tracking_url']);
                    }

                    $html = '<h2>Tracking Detail</h2>';
                    $html .= '<p style="font-size:14px;margin-bottom:5px;">'.$shipping_detail_text.'<br />'.$tracking_detail_text.'</p>';
                    $html .= '<p style="font-size:14px;margin-bottom:20px;"><a href="'.$tracking_url.'" target="_blank">Live track your order</a></p>';
                    echo($html);
                }
            }
        }

        /**
         * Send email notifications on order status change
         * @description Function to save gallery image meta box info
         *
         * @param $order_id
         * @param $old_status
         * @param $new_status
         */
        public function send_email_notifications($order_id, $old_status, $new_status){

            if($old_status != 'completed' && $new_status == 'shipped') {

                // Get WooCommerce email objects
                $mailer = WC()->mailer()->get_emails();

                // Send the email with custom heading & subject
                $mailer['SN_OT_CUSTOMER_SHIPPED_ORDER_MAIL']->trigger( $order_id );
            }
        }
    }
}

$sn_gfp_admin = new SN_OT_ADMIN();
