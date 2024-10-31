jQuery(document).ready(function()
{
    // Add New Tracking Detail
    jQuery('.sn-ot-meta-box .add-new-tracking-detail').click(function(){
        var html = '';
        html += '<div class="tracking-number-row">';
        html += '<input type="text" name="tracking_code[]" id="tracking_code_0" class="tracking-code-input" value="" placeholder="Tracking Code" />';
        html += '<select name="tracking_code_status[]" id="tracking_code_status_0" class="tracking-code-status-select">';
        html += '<option>Status</option>';
        html += '<option value="shipped">Shipped</option>';
        html += '<option value="delivered">Delivered</option>';
        html += '</select>';
        html += '</div>';
        jQuery(this).closest('.sn-ot-meta-box').find('.tracking-number-list').append(html);
        return(false);
    });
    //------------------------

    // Remove Tracking Detail
    jQuery('.sn-ot-meta-box .remove-tracking-detail').click(function(){
        var tracking_info_count = jQuery(this).closest('.sn-ot-meta-box').find('.tracking-number-list .tracking-number-row').length;
        if(tracking_info_count > 1) {
            jQuery(this).closest('.sn-ot-meta-box').find('.tracking-number-list .tracking-number-row:last-child').remove();
        }
        return(false);
    });
    //-----------------------
});