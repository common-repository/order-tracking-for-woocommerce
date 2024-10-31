<?php

if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

?>
<div class="sn-ot-carriers-page">
    <div class="sn-ot-header">
        <h2><?php echo( __('Manage Shipping Carriers', SN_OT_SLUG) ); ?></h2>
        <p><?php echo( __('This section allows you to manage the shipping carriers for tracking', SN_OT_SLUG) ); ?></p>
    </div>
    <div class="sn-ot-box-section">
        <?php wc_get_template( 'navigation.php', $args, '', SN_OT_TEMPLATE_PATH .'/' );?>
        <div class="carriers-box">
            <form id="carriers_form" action="<?php echo(admin_url('admin-post.php')) ?>" method="post" class="sn-ot-form carriers-form">
                <input type="hidden" id="action" name="action" value="sn_ot_update_carriers" />
                <div class="input-group">
                    <label class="control-label"><?php echo( __('Select carriers that that wil be used for order shipping?', SN_OT_SLUG) ); ?></label>
                    <select id="sn_ot_carriers" name="sn_ot_carriers[]" multiple="multiple" class="form-control custom-select" style="width:100%;">
                        <?php
                        foreach($carriers_list as $key => $carrier) {
                            ?>
                            <option value="<?php echo($key) ?>" <?php echo(in_array($key, $selected_carriers_list)?'selected':'') ?>><?php echo($carrier['name']) ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
            </form>

            <div class="footer">
                <button type="button" class="button button-primary btn-submit" data-form="carriers_form"><?php echo( __('Save', SN_OT_SLUG) ); ?></button>
            </div>
        </div>
    </div>
</div>