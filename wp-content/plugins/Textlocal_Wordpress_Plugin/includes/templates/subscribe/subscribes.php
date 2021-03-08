<div class="wrap">
    <h2><?php _e( 'Subscribers', 'sms-notifications' ); ?></h2>

    <div class="sms_notifications-button-group">
        <a href="admin.php?page=sms_notifications_subscribers&action=add" class="button"><span
                    class="dashicons dashicons-admin-users"></span> <?php _e( 'Add Subscribe', 'sms-notifications' ); ?></a>
        <a href="admin.php?page=sms_notifications_subscriber_groups" class="button"><span
                    class="dashicons dashicons-category"></span> <?php _e( 'Manage Group', 'sms-notifications' ); ?></a>
    </div>

    <form id="subscribers-filter" method="get">
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
		<?php $list_table->search_box( __( 'Search', 'sms-notifications' ), 'search_id' ); ?>
		<?php $list_table->display(); ?>
    </form>
</div>