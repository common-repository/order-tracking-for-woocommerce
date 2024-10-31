<?php

if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

?>
<div class="sn-ot-setting-page">
    <div class="sn-ot-header">
        <h2><?php echo( __('General Setting', SN_OT_SLUG) ); ?></h2>
        <p><?php echo( __('This section allows you to manage general settings of the plugin', SN_OT_SLUG) ); ?></p>
    </div>
    <div class="sn-ot-box-section">
        <?php wc_get_template( 'navigation.php', $args, '', SN_OT_TEMPLATE_PATH .'/' );?>
        <div class="setting-box">
            <form id="form_setting" action="<?php echo(admin_url('admin-post.php')) ?>" method="post" class="sn-ot-form  setting-form">
                <input type="hidden" id="action" name="action" value="sn_ot_update_setting" />
                <div class="input-group">
                    <label class="control-label"><?php echo( __('Tracking Form Caption', SN_OT_SLUG) ); ?></label>
                    <input type="text" id="sn_ot_tracking_form_caption" name="sn_ot_tracking_form_caption" class="form-control" value="<?php echo(get_option('sn_ot_tracking_form_caption')) ?>" />
                </div>
                <div class="input-group">
                    <label class="control-label"><?php echo( __('No Tracking Detail Found Text', SN_OT_SLUG) ); ?></label>
                    <input type="text" id="sn_ot_no_tracking_detail_found_text" name="sn_ot_no_tracking_detail_found_text" class="form-control" value="<?php echo(get_option('sn_ot_no_tracking_detail_found_text')) ?>" />
                </div>
                <div class="input-group">
                    <label class="control-label"><?php echo( __('Shipping Detail Text', SN_OT_SLUG) ); ?></label>
                    <input type="text" id="sn_ot_shipping_detail_text" name="sn_ot_shipping_detail_text" class="form-control" value="<?php echo(get_option('sn_ot_shipping_detail_text')) ?>" />
                    <div class="input-hint"><?php echo( __('Placeholders: {carrier_name}, {carrier_logo}', SN_OT_SLUG) ); ?></div>
                </div>
                <div class="input-group">
                    <label class="control-label"><?php echo( __('Tracking Email Text', SN_OT_SLUG) ); ?></label>
                    <input type="text" id="sn_ot_tracking_detail_email_text" name="sn_ot_tracking_detail_email_text" class="form-control" value="<?php echo(get_option('sn_ot_tracking_detail_email_text')) ?>" />
                    <div class="input-hint"><?php echo( __('Placeholders: {carrier_name}, {tracking_number}', SN_OT_SLUG) ); ?></div>
                </div>

                <div class="input-group">
                    <label class="control-label"><?php echo( __('Show order detail?', SN_OT_SLUG) ); ?></label>
                    <label class="switch"><input type="checkbox" id="sn_ot_show_order_detail" name="sn_ot_show_order_detail" class="form-control" value="1" <?php if(get_option('sn_ot_show_order_detail') == 1) { echo('checked'); } ?> /><span class="switch-slider"></span></label><?php echo( __('Yes, show order detail when customer search for tracking number', SN_OT_SLUG) ); ?>
                </div>
            </form>
            <div class="footer">
                <button type="button" class="button button-primary btn-submit" data-form="form_setting"><?php echo( __('Save', SN_OT_SLUG) ); ?></button>
            </div>
        </div>
    </div>
</div>
