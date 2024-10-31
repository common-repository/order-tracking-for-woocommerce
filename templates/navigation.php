<?php SN_OT_INIT::show_message(); ?>
<div class="sn-ot-navigation-box">
    <a href="admin.php?page=sn-ot-setting" class="nav-link <?php echo($page_name=='setting'?'active':'') ?>"><?php echo( __('General Setting', SN_OT_SLUG) ); ?></a>
    <a href="admin.php?page=sn-ot-carriers" class="nav-link <?php echo($page_name=='carriers'?'active':'') ?>"><?php echo( __('Carriers', SN_OT_SLUG) ); ?></a>
    <div class="support-links">
        <a href="<?php echo( SN_OT_PLUGIN_URL ); ?>" target="_blank"><?php echo( __('Documentation', SN_OT_SLUG) ); ?></a> | <a href="<?php echo( SN_OT_AUTHOR_URL ); ?>contact-us" target="_blank"><?php echo( __('Support', SN_OT_SLUG) ); ?></a>
    </div>
</div>
