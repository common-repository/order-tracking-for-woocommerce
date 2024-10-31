<?php

if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

?>
<form class="sn-ot-order-tracking-form woocommerce" method="get">
    <p class="caption"><?php echo(get_option('sn_ot_tracking_form_caption')) ?></p>
    <div class="row">
        <div class="col-12">
            <div class="form-group">
                <input type="text" class="form-control" id="order_id" name="order_id" placeholder="Order Number" value="<?php echo($order_order_id) ?>" />
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="form-group">
                <input type="email" class="form-control" id="order_email" name="order_email" placeholder="Order Email" value="<?php echo($order_email) ?>" />
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <button type="submit" class="button alt btn-block btn-track-order">Track Order</button>
        </div>
    </div>
</form>
<div class="woocommerce sn-ot-order-detail">
    <?php
    if(isset($order_order_id) && isset($order_email)) {

        remove_action('woocommerce_order_details_after_order_table', 'woocommerce_order_again_button');

        $order_detail = get_post($order_order_id);
        if($order_detail != null && $order_detail->post_type == 'shop_order') {
            $order = wc_get_order($order_detail->ID);
            if(strtolower($order_email) == strtolower($order->get_billing_email()))
            {
                $order_meta = get_post_custom($order_detail->ID);
                $show_order_detail = get_option('sn_ot_show_order_detail');?>

                <div class="order-summary">
                    <ul class="order-details">
                        <li class="order-number">Order Number <span class="value"><?php echo "#".$order->get_order_number();?></span></li>
                        <li class="order-date">Date <span class="value"><?php echo wc_format_datetime($order->get_date_created());?></span></li>
                        <li class="order-status">Status <span class="value"><?php echo wp_kses_post($order->get_status());?></span></li>
                    </ul>
                </div>


                <?php
                if($show_order_detail == 1) {
                    wc_get_template( 'order/order-details.php', array('order_id' => $order->get_id()));
                }
                else {
                    $shipping_detail_text = '<div class="sn-ot-shipping-text">'.get_option('sn_ot_shipping_detail_text').'</div>';
                    $tracking_detail_text = '<div class="sn-ot-tracking-text">'.get_option('sn_ot_tracking_detail_text').'</div>';
                    $order_detail_text = '<div class="sn-ot-order-detail-link"><a href="'.$order->get_view_order_url().'">Click here</a> to view your order details.</div>';

                    $shipping_detail_text = str_replace('{carrier_name}', $order_meta['sn_ot_carrier_name'][0], $shipping_detail_text);
                    if($order_meta['sn_ot_pick_up_date'][0]) {
                        $shipping_detail_text = str_replace('{pickup_date}', date_i18n(get_option('date_format'), strtotime($order_meta['sn_ot_pick_up_date'][0])), $shipping_detail_text);
                    }

                    if(is_serialized($order_meta['sn_ot_tracking_code'][0])) {
                        $tracking_number_text = implode(',', array_column(maybe_unserialize($order_meta['sn_ot_tracking_code'][0]), 'tracking_number'));
                        $tracking_detail_text = str_replace('{tracking_number}', $tracking_number_text, $tracking_detail_text);
                    }
                    else {
                        $tracking_detail_text = str_replace('{tracking_number}', $order_meta['sn_ot_tracking_code'][0], $tracking_detail_text);
                    }

                    $tracking_text = $shipping_detail_text.$tracking_detail_text.$order_detail_text;
                    ?><div class="sn-ot-tracking-info"><?php echo($tracking_text) ?></div><?php
                }
            }
            else {
                ?>
                <div class="wot-no-tracking-info">
                    <div><?php echo(get_option('sn_ot_no_tracking_detail_found_text')) ?></div>
                </div>
                <?php
            }
        }
        else {
            ?>
            <div class="wot-no-tracking-info">
                <div><?php echo(get_option('sn_ot_no_tracking_detail_found_text')) ?></div>
            </div>
            <?php
        }
    }
    ?>
</div>