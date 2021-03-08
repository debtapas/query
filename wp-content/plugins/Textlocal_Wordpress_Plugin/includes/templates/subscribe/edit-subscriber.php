<div class="wrap">
    <h2><?php _e( 'Edit Subscriber', 'sms-notifications' ); ?></h2>
    <form action="" method="post">
        <table>
            <tr>
                <td colspan="2"><h3><?php _e( 'Subscriber Info:', 'sms-notifications' ); ?></h3></td>
            </tr>
            <tr>
                <td><span class="label_td" for="sms_notify_subscribe_name"><?php _e( 'Name', 'sms-notifications' ); ?>:</span></td>
                <td><input type="text" id="sms_notify_subscribe_name" name="sms_notify_subscribe_name"
                           value="<?php echo $get_subscribe->name; ?>"/></td>
            </tr>

            <tr>
                <td><span class="label_td" for="sms_notify_subscribe_mobile"><?php _e( 'Mobile', 'sms-notifications' ); ?>:</span></td>
                <td><input type="text" name="sms_notify_subscribe_mobile" id="sms_notify_subscribe_mobile"
                           value="<?php echo $get_subscribe->mobile; ?>" class="code"/></td>
            </tr>

			<?php 
            
            if ( $this->subscribe->get_groups() ): ?>
                <tr>
                    <td><span class="label_td" for="sms_notifications_group_name"><?php _e( 'Group', 'sms-notifications' ); ?>:</span></td>
                    <td>
                        <select name="sms_notifications_group_name" id="sms_notifications_group_name">
							<?php foreach ( $this->subscribe->get_groups() as $items ): ?>
                                <option value="<?php echo $items->ID; ?>" <?php selected( $get_subscribe->group_ID, $items->ID ); ?>><?php echo $items->name; ?></option>
							<?php endforeach; ?>
                        </select>
                    </td>
                </tr>
			<?php else: ?>
                <tr>
                    <td><span class="label_td" for="sms_notifications_group_name"><?php _e( 'Group', 'sms-notifications' ); ?>:</span></td>
                    <td><?php echo sprintf( __( 'There is no group! <a href="%s">Add</a>', 'sms-notifications' ), 'admin.php?page=sms-notifications-subscribers-group' ); ?></td>
                </tr>
			<?php endif; ?>
            
            <tr>
                <td colspan="2">
                    <a href="admin.php?page=sms_notifications_subscribers" class="button"><?php _e( 'Back', 'sms-notifications' ); ?></a>
                    <input type="submit" class="button-primary" name="wp_update_subscribe"
                           value="<?php _e( 'Update', 'sms-notifications' ); ?>"/>
                </td>
            </tr>
        </table>
    </form>
</div>