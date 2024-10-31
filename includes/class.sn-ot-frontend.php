<?php

if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

if ( ! class_exists( 'SN_OT_FRONTEND' ) ) {
    class SN_OT_FRONTEND {

        /**
         * Constructor
         * @description Function to initialize WP actions for the class
         */
        function __construct()
        {
            // Add order tracking details before order table
            add_action( 'woocommerce_order_details_before_order_table', array($this, 'add_order_tracking_details') );
            //----------------------------------------------

            // Add shortcode
            add_shortcode('sn_ot_order_tracking_form', array( $this, 'order_tracking_details' ) );
            //--------------
        }

        public function order_tracking_details() {
            $order_order_id = trim($_GET['order_id'])?intval($_GET['order_id']):null;
            $order_email = sanitize_email($_GET['order_email']);
            $args['order_order_id'] = $order_order_id;
            $args['order_email'] = $order_email;
            return wc_get_template_html( 'order-tracking-details.php', $args, '', SN_OT_TEMPLATE_PATH .'/');
        }

        public function add_order_tracking_details($order) {

            $order_meta = get_post_custom($order->get_id());

            if($order_meta['sn_ot_tracking_code'][0] && ($order->get_status() == 'completed' || $order->get_status() == 'shipped')) {
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
                ?>
                <div class="tracking-detail">
                    <div class="description"><?php echo($shipping_detail_text.' '.$tracking_detail_text) ?>
                        <div>
                            <a href="<?php echo($tracking_url) ?>" target="_blank"><button type="button" class="track-button">Live track your order</button></a>
                        </div>
                    </div>
                </div>
                <?php
            }
        }
    }
}

$sn_ot_frontend = new SN_OT_FRONTEND();
