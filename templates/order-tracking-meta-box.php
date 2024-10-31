<?php

if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

?>
<div class="sn-ot-meta-box">
    <p>
        <label for="sn_ot_carrier_name">Carrier name:</label>
        <br />
        <select id="sn_ot_carrier_name" name="sn_ot_carrier_name">
            <option value>No carrier selected</option>
            <?php foreach($carriers_list as $key => $carrier) { ?>
                <?php
                if( in_array( $key, $selected_carriers_list ) ) {
                    if($key == $order['sn_ot_carrier_name'][0]) { ?>
                        <option value="<?php echo($key) ?>" selected="selected"><?php echo($carrier['name']) ?></option>
                        <?php
                    }
                    else { ?>
                        <option value="<?php echo($key) ?>"><?php echo($carrier['name']) ?></option>
                        <?php
                    }
                } ?>
                <?php
            } ?>
        </select>
    </p>
    <div>
        <label for="sn_ot_tracking_code">Tracking code(s):</label>
        <br />
        <?php
        if(!$order['sn_ot_tracking_code'][0]) {
            ?>
            <div class="tracking-number-list">
                <div class="tracking-number-row">
                    <input type="text" name="sn_ot_tracking_code[]" id="sn_ot_tracking_code_0" class="tracking-code-input" value="" placeholder="Tracking Code" />
                    <select name="sn_ot_tracking_code_status[]" id="sn_ot_tracking_code_status_0" class="tracking-code-status-select">
                        <option>Status</option>
                        <?php foreach(['shipped' => 'Shipped', 'delivered' => 'Delivered'] as $key => $status) {
                            ?>
                            <option value="<?php echo($key) ?>"><?php echo($status) ?></option>
                            <?php
                        } ?>
                    </select>
                </div>
            </div>
            <div class="button-section">
                <a href="#" class="add-new-tracking-detail">Add New</a>
                <a href="#" class="remove-tracking-detail">Remove</a>
            </div>
            <?php
        }
        elseif(is_serialized($order['sn_ot_tracking_code'][0])) {
            $tracking_code_list = maybe_unserialize($order['sn_ot_tracking_code'][0]);
            ?>
            <div class="tracking-number-list">
                <?php
                foreach($tracking_code_list as $key => $tracking_code_detail) {
                    ?>
                    <div class="tracking-number-row">
                        <input type="text" name="sn_ot_tracking_code[]" id="sn_ot_tracking_code_<?php echo($key) ?>" class="tracking-code-input" value="<?php echo($tracking_code_detail['tracking_number']) ?>" placeholder="Tracking Code" />
                        <select name="sn_ot_tracking_code_status[]" id="sn_ot_tracking_code_status_<?php echo($key) ?>" class="tracking-code-status-select">
                            <option>Status</option>
                            <?php foreach(['shipped' => 'Shipped', 'delivered' => 'Delivered'] as $key => $status) {
                                if(strtolower($key) == strtolower($tracking_code_detail['status'])) {
                                    ?>
                                    <option value="<?php echo($key) ?>" selected="selected"><?php echo($status) ?></option>
                                    <?php
                                } else {
                                    ?>
                                    <option value="<?php echo($key) ?>"><?php echo($status) ?></option>
                                    <?php
                                }
                            } ?>
                        </select>
                    </div>
                    <?php
                } ?>
            </div>
            <div class="button-section">
                <a href="#" class="add-new-tracking-detail">Add New</a>
                <a href="#" class="remove-tracking-detail">Remove</a>
            </div>
            <?php
        }
        else {
            ?>
            <input type="text" name="sn_ot_tracking_code" id="sn_ot_tracking_code" placeholder="Enter tracking code" value='<?php echo($order['sn_ot_tracking_code'][0]) ?>' />
            <?php
        }
        ?>
    </div>
    <?php if($order['sn_ot_pick_up_date'][0]) { ?>
        <p class="form-field form-field-wide">
            <label for="sn_ot_pick_up_date">Pickup date:</label>
            <br />
            <input type="text" class="date-picker-field" id="sn_ot_pick_up_date" name="sn_ot_pick_up_date" placeholder="Enter pick up date" value="<?php echo($order['qdwot_pick_up_date'][0]) ?>" pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])" autocomplete="off" />
        </p>
    <?php } ?>
    <p class="form-field form-field-wide">
        <label for="sn_ot_completed_date">Completed date:</label>
        <br />
        <?php
        $order_date_detail['date'] = '';
        $order_date_detail['hours'] = '';
        $order_date_detail['minutes'] = '';

        if($order['_order_completed_date'][0]) {
            $order_date_detail['date'] = date_i18n('Y-m-d', strtotime($order['_order_completed_date'][0]));
            $order_date_detail['hours'] = date_i18n('H', strtotime($order['_order_completed_date'][0]));
            $order_date_detail['minutes'] = date_i18n('i', strtotime($order['_order_completed_date'][0]));
        }
        ?>
        <input style="width: 50%" type="text" class="date-picker-field" id="sn_ot_completed_date" name="sn_ot_completed_date" placeholder="Completed date" value="<?php echo($order_date_detail['date']) ?>" pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])" autocomplete="off" />@
        <input type="number" class="hour" placeholder="h" id="sn_ot_order_completed_hour" name="sn_ot_order_completed_hour" min="0" max="23" step="1" value="<?php echo($order_date_detail['hours']); ?>" pattern="([01]?[0-9]{1}|2[0-3]{1})" style="width:20%;" />:<input type="number" class="minute" placeholder="m" id="sn_ot_order_completed_minute" name="sn_ot_order_completed_minute" min="0" max="59" step="1" value="<?php echo($order_date_detail['minutes']); ?>" pattern="[0-5]{1}[0-9]{1}" style="width:20%" />
    </p>
    <!--<p>
        <label><input type="checkbox" id="qdwot_picked_up" name="qdwot_picked_up" value="1"  />Order picked up</label>
    </p>-->
</div>