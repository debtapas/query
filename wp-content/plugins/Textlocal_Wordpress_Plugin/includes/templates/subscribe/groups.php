<div class="wrap">
    <h2><?php _e( 'Groups', 'sms-notifications' ); ?></h2>

    <div class="sms_notifications-button-group">
        <a href="admin.php?page=sms_notifications_subscriber_groups&action=add" class="button"><span
                    class="dashicons dashicons-groups"></span> <?php _e( 'Add Group', 'sms-notifications' ); ?></a>
    </div>

    <form id="subscribers-filter" method="get">
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
		<?php $list_table->search_box( __( 'Search', 'sms-notifications' ), 'search_id' ); ?>
		<?php $list_table->display(); ?>
    </form>
</div>