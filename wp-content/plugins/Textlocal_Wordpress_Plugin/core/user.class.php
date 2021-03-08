<?php
if ( ! defined('ABSPATH')) exit; 
//define("SMS_NOTIFICATIONS_TEXT_DOMAIN","SMS-NOTIFICATIONS");

//$plugin_dir_name = dirname(plugin_basename( __FILE__ )); 

//define("SMS_NOTIFICATIONS_GATEEWAY_DIR", WP_PLUGIN_DIR."/".$plugin_dir_name);
//define("SMS_NOTIFICATIONS_GATEEWAY_URL", WP_PLUGIN_URL."/".$plugin_dir_name);

include_once (SMS_NOTIFICATIONS_GATEEWAY_DIR.'/core/textlocal.class.php');

global $wpdb ,$woocommerce;

add_action( 'register_form', 'sms_notification_registration_form' );
function sms_notification_registration_form() {

	$billing_phone = ! empty( $_POST['billing_phone'] ) ?  $_POST['billing_phone']  : '';

	?>
	 <p>
        <label for="billing_phone"><?php _e( 'Mobile No', 'myplugin_textdomain' ) ?><br />
            <input type="text" name="billing_phone" id="billing_phone" class="input" value="<?php echo esc_attr( stripslashes( $billing_phone ) ); ?>" size="10" /></label>
    </p>
	<?php
}

add_filter( 'registration_errors', 'sms_notification_registration_errors', 10, 3 );
function sms_notification_registration_errors( $errors, $sanitized_user_login, $user_email ) {

	if ( empty( $_POST['billing_phone'] ) ) {
		$errors->add( 'billing_phone_error', __( '<strong>ERROR</strong>: Please enter your mobile no.', 'sms' ) );
	}

	return $errors;
}

add_action( 'user_register', 'sms_notification_user_register' );
function sms_notification_user_register( $user_id ) {
	$options = get_option( 'sms_notifications_option_name' );
	$recipients = $_POST['billing_phone'];

	if ( ! empty( $_POST['billing_phone'] ) ) {
			update_user_meta( $user_id, 'billing_phone',  $_POST['billing_phone'] );
			$textlocal = new textlocal(false, false, $options['api_key']);
			$body = $options['reg_status'];
			$result = $textlocal->sendSms($body,$recipients);
	}

	
}

add_action( 'user_new_form', 'sms_notification_admin_registration_form' );
function sms_notification_admin_registration_form( $operation ) {
	if ( 'add-new-user' !== $operation ) {
		// $operation may also be 'add-existing-user'
		return;
	}

	$billing_phone = ! empty( $_POST['billing_phone'] ) ?  $_POST['billing_phone']  : '';

	?>
	<h3><?php esc_html_e( 'Personal Information', 'sms' ); ?></h3>

	<table class="form-table">
		<tr>
			<th><label for="billing_phone"><?php esc_html_e( 'Mobile No', 'sms' ); ?></label> <span class="description"><?php esc_html_e( '(required)', 'sms' ); ?></span></th>
			<td>
				<input type="text" name="billing_phone" id="billing_phone" class="input" value="<?php echo esc_attr( stripslashes( $billing_phone ) ); ?>" size="50" /></label>
			</td>
		</tr>
	</table>
	<?php
}

add_action( 'show_user_profile', 'sms_notification_show_extra_profile_fields' );
add_action( 'edit_user_profile', 'sms_notification_show_extra_profile_fields' );

function sms_notification_show_extra_profile_fields( $user ) {
	?>
	<h3><?php esc_html_e( 'Personal Information', 'sms' ); ?></h3>

	<table class="form-table">
		<tr>
			<th><label for="billing_phone"><?php esc_html_e( 'Mobile No', 'sms' ); ?></label></th>
				<td><?php echo esc_html( get_the_author_meta( 'billing_phone', $user->ID ) ); ?></td>
		</tr>
	</table>
	<?php
}
	
add_action( 'password_reset', 'sms_notification_resetpass',10,1 );
function sms_notification_resetpass($user)
{
	$options = get_option( 'sms_notifications_option_name' );
	$admin_notification = $options['sms_admin_notification'];
	$userid = $user->ID;
	$telephoneNumber = get_user_meta( $userid, 'billing_phone', true );
	if($admin_notification=='on')
	{
		$recipients = array();
		$recipients[] = $options['adminnumber']; 
		array_push($recipients, $telephoneNumber);
	}else{
		$recipients = $telephoneNumber;
	}
	$body = $options['forget_pass'];
	$textlocal = new textlocal(false, false, $options['api_key']);
	$result = $textlocal->sendSms($body,$recipients);
}

if ( in_array( 'woocommerce/woocommerce.php', 
    apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
		add_action( 'woocommerce_customer_save_address', 'sms_notification_profile_update');
}
add_action( 'profile_update', 'sms_notification_profile_update',10,1); 
function sms_notification_profile_update($userid)
{	
	$options = get_option( 'sms_notifications_option_name' );
	$admin_notification = $options['sms_admin_notification'];
	$telephoneNumber = get_user_meta( $userid, 'billing_phone', true );
	if($admin_notification=='on')
	{
		$recipients = array();
		$recipients[] = $options['adminnumber']; 
		array_push($recipients, $telephoneNumber);
	}else{
		$recipients = $telephoneNumber;
	}
	$body = $options['profile_update'];
	$textlocal = new textlocal(false, false, $options['api_key']);
	$result = $textlocal->sendSms($body,$recipients);
}

