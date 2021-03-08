<?php 
if ( ! defined('ABSPATH')) exit; 
//define("SMS_NOTIFICATIONS_TEXT_DOMAIN","SMS-NOTIFICATIONS");

//$plugin_dir_name = dirname(plugin_basename( __FILE__ )); 

//define("SMS_NOTIFICATIONS_GATEEWAY_DIR", WP_PLUGIN_DIR."/".$plugin_dir_name);
//define("SMS_NOTIFICATIONS_GATEEWAY_URL", WP_PLUGIN_URL."/".$plugin_dir_name);

require_once (SMS_NOTIFICATIONS_GATEEWAY_DIR.'/core/textlocal.class.php');

global $wpdb,$woocommerce,$product;

if ( in_array( 'woocommerce/woocommerce.php', 
    apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) { 
	
	add_action("woocommerce_order_status_changed", "sms_notifications_order_status");
	
	function sms_notifications_order_status($order_id){
        
		global $woocommerce;
		$order = new WC_Order ($order_id);
        
		$options = get_option( 'sms_notifications_option_name' );
		
		$admin_notification = $options['sms_admin_notification'];

		$textlocal = new textlocal(false, false, $options['api_key']);

		if($order->status === 'pending' && $options['sms_order_status_pending_payment']=='on') {
			
			$telephoneNumber = $order->billing_phone;
			if($admin_notification=='on')
			{
				$recipients = array();
				$recipients[] = $options['adminnumber']; 
				array_push($recipients, $telephoneNumber);
			}else{
				$recipients = $telephoneNumber; 
			}
			
			$body = sms_notifications_shortcode_variable($options['sms_order_pending_payment'],$order);
			$result = $textlocal->sendSms($body,$recipients);

		}

		if($order->status === 'failed' && $options['sms_order_status_failed']=='on' ) {
			$telephoneNumber = $order->billing_phone;
			if($admin_notification=='on')
			{
				$recipients = array();
				$recipients[] = $options['adminnumber']; 
				array_push($recipients, $telephoneNumber);
			}else{
				$recipients = $telephoneNumber;
			}
			
			$body = sms_notifications_shortcode_variable($options['sms_order_failed'],$order);
			$result = $textlocal->sendSms($body,$recipients);
		}

		if($order->status === 'refunded' && $options['sms_order_status_refunded']=='on' ) {
			$telephoneNumber = $order->billing_phone;
			
			if($admin_notification=='on')
			{
				$recipients = array();
				$recipients[] = $options['adminnumber']; 
				array_push($recipients, $telephoneNumber);
			}else{
				$recipients = $telephoneNumber;
			}

			
			$body = sms_notifications_shortcode_variable($options['sms_order_refunded'],$order);
			$result = $textlocal->sendSms($body,$recipients);
		}

		if($order->status === 'completed' && $options['sms_order_status_completed']=='on' ) {
			$telephoneNumber = $order->billing_phone;

			if($admin_notification=='on')
			{
				$recipients = array();
				$recipients[] = $options['adminnumber']; 
				array_push($recipients, $telephoneNumber);
			}else{
				$recipients = $telephoneNumber;
			}
			
			$body = sms_notifications_shortcode_variable($options['sms_order_complete'],$order);
			$result = $textlocal->sendSms($body,$recipients);
		}

		if($order->status === 'cancelled' && $options['sms_order_status_cancelled']=='on') {
			$telephoneNumber = $order->billing_phone;

			if($admin_notification=='on')
			{
				$recipients = array();
				$recipients[] = $options['adminnumber']; 
				array_push($recipients, $telephoneNumber);
			}else{
				$recipients = $telephoneNumber;
			}
			
			$body = sms_notifications_shortcode_variable($options['sms_order_canceled'],$order);
			$result = $textlocal->sendSms($body,$recipients);
		}
		if($order->status === 'processing' && $options['sms_order_status_processing']=='on'){
			$telephoneNumber = $order->billing_phone;

			if($admin_notification=='on')
			{
				$recipients = array();
				$recipients[] = $options['adminnumber']; 
				array_push($recipients, $telephoneNumber);
			}else{
				$recipients = $telephoneNumber;
			}
			
			$body = sms_notifications_shortcode_variable($options['sms_order_processing'],$order);
			$result = $textlocal->sendSms($body,$recipients);
		
		}		
		if($order->status === 'on-hold' && $options['sms_order_status_on-hold']=='on'){
			$telephoneNumber = $order->billing_phone;

			if($admin_notification=='on')
			{
				$recipients = array();
				$recipients[] = $options['adminnumber']; 
				array_push($recipients, $telephoneNumber);
			}else{
				$recipients = $telephoneNumber;
			}
			
			$body = sms_notifications_shortcode_variable($options['sms_order_hold'],$order);
			$result = $textlocal->sendSms($body,$recipients);
		}
	}

	/** Review Submit **/
	
	add_action( 'comment_post', 'sms_notification_submit_comment');
	function sms_notification_submit_comment ($comment_id){

		global $product,$current_user;
		$options = get_option( 'sms_notifications_option_name' );
	    get_currentuserinfo();
		
		$user_id = get_current_user_id();
		$name = $current_user->user_firstname; 
		$recipients = get_user_meta( $user_id, 'billing_phone', true );
		$post_id = isset( $_POST['comment_post_ID'] ) ? (int) $_POST['comment_post_ID'] : 0;
		$product = wc_get_product( $post_id);
		$title = $product->post->post_title;

		$body = "Thank You ".$name.", \nYour review on ".$title." has been pending for approval. Your feedback will help millions of other customers, we really appreciate the time and effort you spent in sharing your personal experience with us.";
		if($options['product_review_notification']=='on'){
			
			$result = $textlocal->sendSms($body,$recipients);
		}
	}
	/** End **/
	
	/*Review Approval */
	add_action('transition_comment_status', 'sendsms_approve_comment', 10, 3);
	function sendsms_approve_comment($new_status, $old_status, $comment) {
	    if($old_status != $new_status) {

	        if($new_status == 'approved') {
	        global $product;

	        $options = get_option( 'sms_notifications_option_name' );
	        
	        $userid = $comment->user_id;
	        $user_data = get_userdata( $userid );
	        $name = $user_data->display_name;
	        $post_id = $comment->comment_post_ID;
	        $recipients = get_user_meta( $userid, 'billing_phone', true );
	           
	        $product = wc_get_product( $post_id);
			$title = $product->post->post_title;

			$body = "Thank You ".$name.", \nYour review on ".$title." has been published. Your feedback will help millions of other customers, we really appreciate the time and effort you spent in sharing your personal experience with us.";
				if($options['product_review_notification']=='on'){
					
					$result = $textlocal->sendSms($body,$recipients);
				}
	        }
	    }
	}
	/*End*/
}
?>