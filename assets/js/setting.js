jQuery(document).ready(function()
{
    var $setting_box = jQuery('.sn-ot-setting-page .setting-box');
    var $carriers_box = jQuery('.sn-ot-carriers-page .carriers-box');

    // Form Submit Event
    $setting_box.find('.btn-submit').click(function() {

        jQuery('form#'+jQuery(this).data('form')).submit();
    });
    $carriers_box.find('.btn-submit').click(function() {
        jQuery('form#'+jQuery(this).data('form')).submit();
    });
    //------------------

    // Apply Custom Select
    $carriers_box.find('.custom-select').select2({'minimumResultsForSearch': -1});
    //--------------------
});


