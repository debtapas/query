<div class="wrap">
    <h2><?php _e( 'Add Group', 'sms-notifications' ); ?></h2>
    <form action="" method="post">
        <table>
            <tr>
                <td colspan="2"><h3><?php _e( 'Group Info:', 'sms-notifications' ); ?></h3></td>
            </tr>
            <tr>
                <td><span class="label_td" for="sms_notify_group_name"><?php _e( 'Name', 'sms-notifications' ); ?>:</span></td>
                <td><input type="text" id="sms_notify_group_name" name="sms_notify_group_name"/></td>
            </tr>

            <tr>
                <td colspan="2">
                    <a href="admin.php?page=sms_notifications_subscriber_groups" class="button"><?php _e( 'Back', 'sms-notifications' ); ?></a>
                    <input type="submit" class="button-primary" name="wp_add_group"
                           value="<?php _e( 'Add', 'sms-notifications' ); ?>"/>
                </td>
            </tr>
        </table>
    </form>
</div>  