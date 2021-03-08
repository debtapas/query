<?php
/*
 Plugin Name: Textlocal SMS Notifications
 Plugin URI: http://www.textlocal.in
 Description: Textlocal SMS Notifications
 Version: 3.0.0
 Author: Textlocal
 Author URI: http://www.textlocal.in
 Text Domain: Textlocal-SMS-Notifications
 */
if ( ! defined('ABSPATH')) exit;  // if direct access

define("SMS_NOTIFICATIONS_TEXT_DOMAIN","SMS-NOTIFICATIONS");

$plugin_dir_name = dirname(plugin_basename( __FILE__ )); 

define("SMS_NOTIFICATIONS_GATEEWAY_DIR", WP_PLUGIN_DIR."/".$plugin_dir_name);
define("SMS_NOTIFICATIONS_GATEEWAY_URL", WP_PLUGIN_URL."/".$plugin_dir_name);



global $wc_settings_sms, $smsid, $smslabel, $smsforwooplnm, $wpdb,$woocommerce,$product;

class SMSNotifications{
	/**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */

	public function __construct(){
		add_action('admin_menu', array($this, 'sms_notifications_menu'));
		add_action( 'admin_init', array($this, 'sms_notifications_page_init'));
		add_action( 'admin_enqueue_scripts', array( $this, 'sms_notifications_enqueue_script'));

        include_once SMS_NOTIFICATIONS_GATEEWAY_DIR.'/includes/class-sms-notification-subscribe.php';
        $this->subscribe = new SMS_Notification_Subscriptions();
	}
	
	public function sms_notifications_menu()
    {
    	add_menu_page('SMS Notifications', 'SMS Notifications', 'manage_options',  'sms_notifications', array($this, 'sms_notifications_page'), 'dashicons-email-alt' );
        add_submenu_page('sms_notifications','Log History','Log History','manage_options','sms_notifications_log_history', array($this, 'sms_notifications_log_page'));
        
        add_submenu_page('sms_notifications','Subscribers','Subscribers','manage_options','sms_notifications_subscribers', array($this, 'sms_notifications_subscribers'));

        add_submenu_page('sms_notifications','Subscriber Groups','Subscriber Groups','manage_options','sms_notifications_subscriber_groups', array($this, 'sms_notifications_subscriber_groups'));
	}

	public function sms_notifications_enqueue_script( ) {
    		wp_enqueue_style( 'sms_notifications', SMS_NOTIFICATIONS_GATEEWAY_URL. '/assets/css/sms_style.css');
    		wp_enqueue_script( 'sms_notifications',SMS_NOTIFICATIONS_GATEEWAY_URL. '/assets/js/sms_notify.js' );
            wp_enqueue_style( 'sms_notifications_bootstrap_min', SMS_NOTIFICATIONS_GATEEWAY_URL. '/assets/css/bootstrap.min.css');
            wp_enqueue_script( 'sms_notifications_twbsPagination',SMS_NOTIFICATIONS_GATEEWAY_URL. '/assets/js/jquery.twbsPagination.min.js' );
	}

	public function sms_notifications_page_init()
    {        
        register_setting(
            'sms_notifications_option_group', // Option group 
            'sms_notifications_option_name', // Option name
            array( $this, 'sms_notifications_sanitize' ) // Sanitize
        );

        add_settings_section(
            'sms_api_setting', // ID
            'API Credentials', // Title
            '',
            //array( $this, 'print_section_info' ), // Callback
            'sms_notifications' // Page
        );  

        add_settings_field(
            'api_key', // ID
            'Api Key', // Title 
            array( $this, 'api_key_callback' ), // Callback
            'sms_notifications', // Page
            'sms_api_setting' // Section           
        );      

        add_settings_field(
            'senderid', 
            'Sender ID', 
            array( $this, 'senderid_callback' ), 
            'sms_notifications', 
            'sms_api_setting'
        );

        add_settings_section(
            'sms_order_admin_notification_setting', // ID
            'SMS Notification Settings for Administrator',//'Order Notification Settings', // Title
            '',
            'sms_notifications' // Page sanitize_textarea_field()
        );

        add_settings_section(
            'sms_order_notification_setting', // ID
            'Order Notification Settings', // Title
            '',
            'sms_notifications' // Page sanitize_textarea_field()
        );
 
        add_settings_field(
            'sms_admin_notification',
            'Enable / Disable Admin Notification',
            //'If checked then enable admin sms notification for new order',
            array( $this, 'sms_admin_notification_callback' ),
            'sms_notifications',
            "sms_order_admin_notification_setting"
        );

        add_settings_field(
            'product_review_notification',
            'Enable / Disable Product Review Notification',
            //'If checked then enable admin sms notification for new order',
            array( $this, 'product_review_notification_callback' ),
            'sms_notifications',
            "sms_order_admin_notification_setting"
        );

        add_settings_field(
            'adminnumber', 
            'Primary Admin Number. You can specify multiple numbers seperated by comma', 
            array( $this, 'adminnumber_callback' ), 
            'sms_notifications', 
            'sms_order_admin_notification_setting'
        );

        add_settings_field(
            'sms_order_status_notification',
            'Select status to send notification',
            array( $this, 'sms_order_status_notification_callback' ),
            'sms_notifications',
            "sms_order_notification_setting"
        );

        add_settings_field(
            'sms_order_hold', 
            'Notification Text For Order Hold Status', 
            array( $this, 'sms_order_hold_callback' ), 
            'sms_notifications', 
            'sms_order_notification_setting'
        );
        //
        add_settings_field(
            'sms_order_complete', 
            'Notification Text For Order Complete Status', 
            array( $this, 'sms_order_complete_callback' ), 
            'sms_notifications', 
            'sms_order_notification_setting'
        );

        add_settings_field(
            'sms_order_processing', 
            'Notification Text For Order Processing Status', 
            array( $this, 'sms_order_processing_callback' ), 
            'sms_notifications', 
            'sms_order_notification_setting'
        );

        add_settings_field(
            'sms_order_pending_payment', 
            'Notification Text For Pending Payment Order Status', 
            array( $this, 'sms_order_pending_payment_callback' ), 
            'sms_notifications', 
            'sms_order_notification_setting'
        );

        add_settings_field(
            'sms_order_canceled', 
            'Notification Text For Order Cancelled Status', 
            array( $this, 'sms_order_canceled_callback' ), 
            'sms_notifications', 
            'sms_order_notification_setting'
        );
        //
        add_settings_field(
            'sms_order_refunded', 
            'Notification Text For Order Refunded Status', 
            array( $this, 'sms_order_refunded_callback' ), 
            'sms_notifications', 
            'sms_order_notification_setting'
        );

        add_settings_field(
            'sms_order_failed', 
            'Notification Text For Order Failed Status', 
            array( $this, 'sms_order_failed_callback' ), 
            'sms_notifications', 
            'sms_order_notification_setting'
        );

        add_settings_section(
            'sms_user_notification_setting', // ID
            'Customer Notification Settings', // Title
            '',
            'sms_notifications' // Page 
        );

        add_settings_field(
            'reg_status', // ID
            'Registration Status', // Title 
            array( $this, 'reg_status_callback' ), // Callback
            'sms_notifications', // Page
            'sms_user_notification_setting' // Section           
        );
        
        add_settings_field(
            'profile_update', // ID
            'Profile Update Status', // Title 
            array( $this, 'profile_update_callback' ), // Callback
            'sms_notifications', // Page
            'sms_user_notification_setting' // Section           
        );

        add_settings_field(
            'forget_pass', // ID
            'Forget Password Status', // Title 
            array( $this, 'forget_pass_callback' ), // Callback
            'sms_notifications', // Page
            'sms_user_notification_setting' // Section           
        );

        add_settings_field(
            'coupon_gen', // ID
            'Coupon Generation', // Title 
            array( $this, 'coupon_gen_callback' ), // Callback
            'sms_notifications', // Page
            'sms_user_notification_setting' // Section           
        );

    }

    public function sms_notifications_sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['api_key'] ) )
            $new_input['api_key'] = sanitize_text_field( $input['api_key'] );

        if( isset( $input['senderid'] ) )
            $new_input['senderid'] = sanitize_text_field( $input['senderid'] );

        if( isset( $input['adminnumber'] ) )
            $new_input['adminnumber'] = sanitize_text_field( $input['adminnumber'] );

        if( isset( $input['sms_order_canceled'] ) )
            $new_input['sms_order_canceled'] = sanitize_textarea_field( $input['sms_order_canceled'] );	

        if( isset( $input['sms_order_refunded'] ) )
            $new_input['sms_order_refunded'] = sanitize_textarea_field( $input['sms_order_refunded'] );

        if( isset( $input['sms_order_failed'] ) )
            $new_input['sms_order_failed'] = sanitize_textarea_field( $input['sms_order_failed'] );
        
        if( isset( $input['sms_order_hold'] ) )
            $new_input['sms_order_hold'] = sanitize_textarea_field( $input['sms_order_hold'] );
        
        if( isset( $input['sms_order_complete'] ) )
            $new_input['sms_order_complete'] = sanitize_textarea_field( $input['sms_order_complete'] );

        if( isset( $input['sms_order_processing'] ) )
            $new_input['sms_order_processing'] = sanitize_textarea_field( $input['sms_order_processing'] );

        if( isset( $input['sms_order_pending_payment'] ) )
            $new_input['sms_order_pending_payment'] = sanitize_textarea_field( $input['sms_order_pending_payment'] );

        if( isset( $input['reg_status'] ) )
            $new_input['reg_status'] = sanitize_text_field( $input['reg_status'] );

        if( isset( $input['profile_update'] ) )
            $new_input['profile_update'] = sanitize_text_field( $input['profile_update'] );
        
        if( isset( $input['forget_pass'] ) )
            $new_input['forget_pass'] = sanitize_text_field( $input['forget_pass'] );

        if( isset( $input['coupon_gen'] ) )
            $new_input['coupon_gen'] = sanitize_textarea_field( $input['coupon_gen'] );
        
        if(isset( $input['sms_admin_notification']))
        	$new_input['sms_admin_notification'] = $input['sms_admin_notification'];
        
        if(isset( $input['product_review_notification']))
        	$new_input['product_review_notification'] = $input['product_review_notification'];

        if(isset( $input['sms_order_status_pending_payment']))
        	$new_input['sms_order_status_pending_payment'] = $input['sms_order_status_pending_payment'];

        if(isset( $input['sms_order_status_processing']))
        	$new_input['sms_order_status_processing'] = $input['sms_order_status_processing'];

        if(isset( $input['sms_order_status_on-hold']))
        	$new_input['sms_order_status_on-hold'] = $input['sms_order_status_on-hold'];

        if(isset( $input['sms_order_status_completed']))
        	$new_input['sms_order_status_completed'] = $input['sms_order_status_completed'];

        if(isset( $input['sms_order_status_cancelled']))
        	$new_input['sms_order_status_cancelled'] = $input['sms_order_status_cancelled'];

        if(isset( $input['sms_order_status_refunded']))
        	$new_input['sms_order_status_refunded'] = $input['sms_order_status_refunded'];

        if(isset( $input['sms_order_status_failed']))
        	$new_input['sms_order_status_failed'] = $input['sms_order_status_failed'];

        return $new_input;
    }

    public function adminnumber_callback()
    { 
        printf(
            '<input type="text" id="adminnumber" name="sms_notifications_option_name[adminnumber]" size="50" value="%s" />',
            isset( $this->options['adminnumber'] ) ? esc_attr( $this->options['adminnumber']) : ''
        );

    }

    public function sms_admin_notification_callback(){
    	printf(
        '<input id="%1$s" name="sms_notifications_option_name[sms_admin_notification]" type="checkbox" %2$s /> ','sms_admin_notification',checked( isset( $this->options['sms_admin_notification'] ), true, false ));
    }
    //
    public function product_review_notification_callback(){
    	printf(
        '<input id="%1$s" name="sms_notifications_option_name[product_review_notification]" type="checkbox" %2$s /> ','product_review_notification',checked( isset( $this->options['product_review_notification'] ), true, false ));
    }

    public function sms_order_status_notification_callback(){
 
    	printf(
        '<input id="%1$s" name="sms_notifications_option_name[sms_order_status_pending_payment]" type="checkbox" %2$s /> ','sms_order_status_pending_payment',checked( isset( $this->options['sms_order_status_pending_payment'] ), true, false ));
        printf('<label>Pending payment</label> <br/>');
        
        printf(
        '<input id="%1$s" name="sms_notifications_option_name[sms_order_status_processing]" type="checkbox" %2$s /> ','sms_order_status_processing',checked( isset( $this->options['sms_order_status_processing'] ), true, false ));
        printf('<label>Processing</label><br/>');

        printf(
        '<input id="%1$s" name="sms_notifications_option_name[sms_order_status_on-hold]" type="checkbox" %2$s /> ','sms_order_status_on-hold',checked( isset( $this->options['sms_order_status_on-hold'] ), true, false ));
        printf('<label>On hold</label><br/>');

        printf(
        '<input id="%1$s" name="sms_notifications_option_name[sms_order_status_completed]" type="checkbox" %2$s /> ','sms_order_status_completed',checked( isset( $this->options['sms_order_status_completed'] ), true, false ));
        printf('<label>Completed</label><br/>');

        printf(
        '<input id="%1$s" name="sms_notifications_option_name[sms_order_status_cancelled]" type="checkbox" %2$s /> ','sms_order_status_cancelled',checked( isset( $this->options['sms_order_status_cancelled'] ), true, false ));
        printf('<label>Cancelled</label><br/>');

        printf(
        '<input id="%1$s" name="sms_notifications_option_name[sms_order_status_refunded]" type="checkbox" %2$s /> ','sms_order_status_refunded',checked( isset( $this->options['sms_order_status_refunded'] ), true, false ));
        printf('<label>Refunded</label><br/>');

        printf(
        '<input id="%1$s" name="sms_notifications_option_name[sms_order_status_failed]" type="checkbox" %2$s /> ','sms_order_status_failed',checked( isset( $this->options['sms_order_status_failed'] ), true, false ));
        printf('<label>Failed</label><br/>');

    }
    public function sms_order_canceled_callback()
    {
    	printf(
            '<textarea id="sms_order_canceled" name="sms_notifications_option_name[sms_order_canceled]" cols="52" rows="3">%s</textarea>',
            isset( $this->options['sms_order_canceled'] ) ? esc_attr( $this->options['sms_order_canceled']) : ''
        );
        printf('<p class="variable_parameters"><span>Variable parameters :</span> {SHOP_NAME},{ORDER_NUMBER},{ORDER_STATUS},{ORDER_AMOUNT},{ORDER_DATE},{ORDER_ITEMS},{BILLING_FNAME},{BILLING_LNAME},{CURRENT_DATE},{CURRENT_TIME}</p>');
    }
    
    public function sms_order_refunded_callback()
    {
    	printf(
            '<textarea id="sms_order_refunded" name="sms_notifications_option_name[sms_order_refunded]" cols="52" rows="3">%s</textarea>',
            isset( $this->options['sms_order_refunded'] ) ? esc_attr( $this->options['sms_order_refunded']) : ''
        );
        printf('<p class="variable_parameters"><span>Variable parameters : </span>{SHOP_NAME},{ORDER_NUMBER},{ORDER_STATUS},{ORDER_AMOUNT},{ORDER_DATE},{ORDER_ITEMS},{BILLING_FNAME},{BILLING_LNAME},{CURRENT_DATE},{CURRENT_TIME}</p>');
    }

    public function sms_order_failed_callback()
    {
    	printf(
            '<textarea id="sms_order_failed" name="sms_notifications_option_name[sms_order_failed]" cols="52" rows="3">%s</textarea>',
            isset( $this->options['sms_order_failed'] ) ? esc_attr( $this->options['sms_order_failed']) : ''
        );
        printf('<p class="variable_parameters"><span>Variable parameters : </span>{SHOP_NAME},{ORDER_NUMBER},{ORDER_STATUS},{ORDER_AMOUNT},{ORDER_DATE},{ORDER_ITEMS},{BILLING_FNAME},{BILLING_LNAME},{CURRENT_DATE},{CURRENT_TIME}</p>');
    }
    
    public function sms_order_hold_callback()
    {
    	printf(
            '<textarea id="sms_order_hold" name="sms_notifications_option_name[sms_order_hold]" cols="52" rows="3">%s</textarea>',
            isset( $this->options['sms_order_hold'] ) ? esc_attr( $this->options['sms_order_hold']) : ''
        );

        printf('<p class="variable_parameters"><span>Variable parameters : </span>{SHOP_NAME},{ORDER_NUMBER},{ORDER_STATUS},{ORDER_AMOUNT},{ORDER_DATE},{ORDER_ITEMS},{BILLING_FNAME},{BILLING_LNAME},{CURRENT_DATE},{CURRENT_TIME}</p>');
        
    }
    
    public function sms_order_complete_callback()
    {
    	printf(
            '<textarea id="sms_order_complete" name="sms_notifications_option_name[sms_order_complete]" cols="52" rows="3">%s</textarea>',
            isset( $this->options['sms_order_complete'] ) ? esc_attr( $this->options['sms_order_complete']) : ''
        );
        printf('<p class="variable_parameters"><span>Variable parameters :</span> {SHOP_NAME},{ORDER_NUMBER},{ORDER_STATUS},{ORDER_AMOUNT},{ORDER_DATE},{ORDER_ITEMS},{BILLING_FNAME},{BILLING_LNAME},{CURRENT_DATE},{CURRENT_TIME}</p>');
    }

    public function sms_order_processing_callback()
    {
    	printf(
            '<textarea id="sms_order_processing" name="sms_notifications_option_name[sms_order_processing]" cols="52" rows="3">%s</textarea>',
            isset( $this->options['sms_order_processing'] ) ? esc_attr( $this->options['sms_order_processing']) : ''
        );
        printf('<p class="variable_parameters"><span>Variable parameters :</span> {SHOP_NAME},{ORDER_NUMBER},{ORDER_STATUS},{ORDER_AMOUNT},{ORDER_DATE},{ORDER_ITEMS},{BILLING_FNAME},{BILLING_LNAME},{CURRENT_DATE},{CURRENT_TIME}</p>');
    }

    public function sms_order_pending_payment_callback()
    {
    	printf(
            '<textarea id="sms_order_pending_payment" name="sms_notifications_option_name[sms_order_pending_payment]" cols="52" rows="3">%s</textarea>',
            isset( $this->options['sms_order_pending_payment'] ) ? esc_attr( $this->options['sms_order_pending_payment']) : ''
        );
        printf('<p class="variable_parameters"><span>Variable parameters :</span> {SHOP_NAME},{ORDER_NUMBER},{ORDER_STATUS},{ORDER_AMOUNT},{ORDER_DATE},{ORDER_ITEMS},{BILLING_FNAME},{BILLING_LNAME},{CURRENT_DATE},{CURRENT_TIME}</p>');
    }
    //

    public function reg_status_callback()
    {
        printf(
            '<input type="text" id="reg_status" name="sms_notifications_option_name[reg_status]" size="50" value="%s" />',
            isset( $this->options['reg_status'] ) ? esc_attr( $this->options['reg_status']) : ''
        );
    }

    public function profile_update_callback()
    {
        printf(
            '<input type="text" id="profile_update" name="sms_notifications_option_name[profile_update]" size="50" value="%s" />',
            isset( $this->options['profile_update'] ) ? esc_attr( $this->options['profile_update']) : ''
        );
    }
    
    public function forget_pass_callback()
    {
        printf(
            '<input type="text" id="forget_pass" name="sms_notifications_option_name[forget_pass]" size="50" value="%s" />',
            isset( $this->options['forget_pass'] ) ? esc_attr( $this->options['forget_pass']) : ''
        );
    }

    public function coupon_gen_callback()
    {
        printf(
            '<textarea id="coupon_gen" name="sms_notifications_option_name[coupon_gen]" cols="52" rows="3">%s</textarea>',
            isset( $this->options['coupon_gen'] ) ? esc_attr( $this->options['coupon_gen']) : ''
        );
    }

    public function api_key_callback()
    {
        printf(
            '<input type="text" id="api_key" name="sms_notifications_option_name[api_key]" size="50" value="%s" />',
            isset( $this->options['api_key'] ) ? esc_attr( $this->options['api_key']) : ''
        );
        printf('<span class="apikeys"><a href="https://control.textlocal.in/settings/apikeys/" target="_blank">Click Here</a> to create/manage your API Keys</span>');

    }

    public function senderid_callback()
    {
        $options = get_option( 'sms_notifications_option_name' );
        $settings['sms_auth_token'] = $options['api_key']; 
        $Textlocal = new textlocal(false, false, $settings['sms_auth_token']);
        $response = $Textlocal->getSenderNames();
        $sender_names = $response->sender_names;

        echo "<select id='senderid' name='sms_notifications_option_name[senderid]'>";
        foreach($sender_names as $sender_name) {
            $selected = ($options['senderid']==$sender_name) ? 'selected="selected"' : '';
            echo "<option value='$sender_name' $selected>$sender_name</option>";
        }
        echo "</select>";
    }

	public function sms_notifications_page()
  	{
  		$reputesmsid = 'sms_notifications';
  		$this->options = get_option( 'sms_notifications_option_name' );
  		?>
  	<div class="wrap">
  		<h2>SMS Settings</h2>
  		<form method="post" action="options.php">
  			<?php
  				settings_fields( 'sms_notifications_option_group' );
                do_settings_sections( 'sms_notifications' );
                submit_button();
  			?>
  		</form>
  	</div>
		<?php
  	}

    public function sms_notifications_log_page(){
        $options = get_option( 'sms_notifications_option_name' );
        $settings['sms_auth_token'] = $options['api_key']; 
        $textlocal_log = new textlocal(false, false, $settings['sms_auth_token']);
        $min_time = strtotime('-1 month'); // Get sends between a month ago 
        $max_time = time(); // and now
        $limit = 1000;
        $start = 0;

        $response = $textlocal_log->getAPIMessageHistory($start, $limit, $min_time, $max_time);
       
        $messages = $response->messages;

        ?>
        <div class="wrap">
            <h1>API Message History</h1>
            
            <form action="<?php echo SMS_NOTIFICATIONS_GATEEWAY_URL .'/admin/download.php'; ?>" method="post" id="form_export" target="_blank">
            <?php wp_nonce_field( 'csv_exporter' );?>

            <p class="submit">
                <input type="submit" class="button-primary" value="<?php echo 'Export To CSV'; ?>"/>
            </p>
            
            </form>
        <table id="messages" class="table table-bordered table table-hover" cellspacing="0" width="100%">
             <colgroup><col><col><col></colgroup>
              <thead>
              <tr>
                    <th>Datetime</th>
                    <th>Number</th>
                    <th>Sender</th>
                    <th>Message</th>
                    <th>Status</th>
              </tr>
              </thead>
              <tbody id="msg_body">
              </tbody>
        </table>

        <div id="pager">
              <ul id="pagination" class="pagination-sm"></ul>
        </div>
        
    <script type="text/javascript">
        var data = <?php echo json_encode($messages)?>;
        var PerPagerec = 10;
        var RecordsTotal = data.length;
        var Pages = Math.ceil(RecordsTotal / PerPagerec);
        totalRecords = 0,
        recPerPage = 10,
        page = 1,
        jQuery('#pagination').twbsPagination({
                totalPages: Pages,
                visiblePages: 20,
                next: 'Next',
                prev: 'Prev',
                onPageClick: function (event, page, recored) {
                    records = data;
                    totalRecords = records.length;
                    totalPages = Math.ceil(totalRecords / recPerPage);
                    displayRecordsIndex = Math.max(page - 1, 0) * recPerPage;
                    endRec = (displayRecordsIndex) + recPerPage;
                    displayRecords = records.slice(displayRecordsIndex, endRec);
                    var tr;
                    jQuery('#msg_body').html('');
                        for (var i = 0; i < displayRecords.length; i++) {
                        tr = jQuery('<tr/>');
                        tr.append("<td>" + displayRecords[i].datetime + "</td>");
                        tr.append("<td>" + displayRecords[i].number + "</td>");
                        tr.append("<td>" + displayRecords[i].sender + "</td>");
                        tr.append("<td>" + displayRecords[i].content + "</td>");
                        tr.append("<td>" + displayRecords[i].status + "</td>");
                        jQuery('#msg_body').append(tr);
                    }
                }
        });
    </script>
    </body>
        </div>
    <?php
    }
 

    public function sms_notifications_subscribers(){
                
        if ( isset( $_GET['action'] ) ) {

            // Add subscriber page
            if ( $_GET['action'] == 'add' ) {
                include_once dirname( __FILE__ ) . "/includes/templates/subscribe/add-subscriber.php";
                
                if ( isset( $_POST['wp_add_subscribe'] ) ) {
                    $result = $this->subscribe->add_subscriber( $_POST['sms_notify_subscribe_name'], $_POST['sms_notify_subscribe_mobile'], $_POST['sms_notifications_group_name'] );
                    echo $this->notice_result( $result['result'], $result['message'] );
                }

                return;
            }

            // Edit subscriber page
            if ( $_GET['action'] == 'edit' ) {
                if ( isset( $_POST['wp_update_subscribe'] ) ) {
                    $result = $this->subscribe->update_subscriber( $_GET['ID'], $_POST['sms_notify_subscribe_name'], $_POST['sms_notify_subscribe_mobile'], $_POST['sms_notifications_group_name'] /*$_POST['sms_notifications_subscribe_status']*/ );
                    echo $this->notice_result( $result['result'], $result['message'] );
                }

                $get_subscribe = $this->subscribe->get_subscriber( $_GET['ID'] );
                include_once dirname( __FILE__ ) . "/includes/templates/subscribe/edit-subscriber.php";

                return;
            }

        }

        include_once dirname( __FILE__ ) . '/includes/class-sms-notification-subscribers-table.php';

        //Create an instance of our package class...
        $list_table = new WP_SMS_Subscribers_List_Table();

        //Fetch, prepare, sort, and filter our data...
        $list_table->prepare_items();

        include_once dirname( __FILE__ ) . "/includes/templates/subscribe/subscribes.php";
    }

    public function sms_notifications_subscriber_groups(){
        
        if ( isset( $_GET['action'] ) ) {
            // Add group page
            if ( $_GET['action'] == 'add' ) {
                include_once dirname( __FILE__ ) . "/includes/templates/subscribe/add-group.php";
                if ( isset( $_POST['wp_add_group'] ) ) {
                    $result = $this->subscribe->add_group( $_POST['sms_notify_group_name'] );
                    echo $this->notice_result( $result['result'], $result['message'] );
                }

                return;
            }

            // Manage group page
            if ( $_GET['action'] == 'edit' ) {
                if ( isset( $_POST['wp_update_group'] ) ) {
                    $result = $this->subscribe->update_group( $_GET['ID'], $_POST['sms_notify_group_name'] );
                    echo $this->notice_result( $result['result'], $result['message'] );
                }

                $get_group = $this->subscribe->get_group( $_GET['ID'] );
                include_once dirname( __FILE__ ) . "/includes/templates/subscribe/edit-group.php";

                return;
            }
        }

        include_once dirname( __FILE__ ) . '/includes/class-sms-notification-groups-table.php';

        //Create an instance of our package class...
        $list_table = new WP_SMS_Subscribers_Groups_List_Table();

        //Fetch, prepare, sort, and filter our data...
        $list_table->prepare_items();

        include_once dirname( __FILE__ ) . "/includes/templates/subscribe/groups.php";
    }

    public function notice_result( $result, $message ) {

        if ( empty( $result ) ) {
            return;
        }

        if ( $result == 'error' ) {
            return '<div class="updated settings-error notice error is-dismissible"><p><strong>' . $message . '</strong></p><button class="notice-dismiss" type="button"><span class="screen-reader-text">' . __( 'Close', 'sms-notifications' ) . '</span></button></div>';
        }

        if ( $result == 'update' ) {
            return '<div class="updated settings-update notice is-dismissible"><p><strong>' . $message . '</strong></p><button class="notice-dismiss" type="button"><span class="screen-reader-text">' . __( 'Close', 'sms-notifications' ) . '</span></button></div>';
        }
    }


}
if( is_admin() )
$my_settings_page =  new SMSNotifications();

require_once( SMS_NOTIFICATIONS_GATEEWAY_DIR.'/core/user.class.php' );
require_once( SMS_NOTIFICATIONS_GATEEWAY_DIR.'/core/order.class.php' );
require_once( SMS_NOTIFICATIONS_GATEEWAY_DIR.'/core/textlocal.class.php' );

function sms_notifications_install(){
	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
    $table_prefix = $wpdb->prefix;
    
	$sql_subscribe = "CREATE TABLE IF NOT EXISTS {$table_prefix}sms_notifications_subscribes(
    ID int(10) NOT NULL auto_increment,
    date DATETIME,
    name VARCHAR(20),
    mobile VARCHAR(20) NOT NULL,
    /*status tinyint(1),*/
    group_ID int(5),
    PRIMARY KEY(ID)) CHARSET=utf8";

    $sql_group = "CREATE TABLE IF NOT EXISTS {$table_prefix}sms_notifications_subscribes_group(
    ID int(10) NOT NULL auto_increment,
    name VARCHAR(250),
    PRIMARY KEY(ID)) CHARSET=utf8";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta($sql_subscribe);
    dbDelta($sql_group);
}
register_activation_hook( __FILE__, 'sms_notifications_install' ); 

/****Delete Plugin Remove Tables *****/
function sms_notifications_uninstall()
{
	global $wpdb;
	$wpdb->query('DROP TABLE IF EXISTS '. $wpdb->prefix.'sms_notifications_subscribes');
    $wpdb->query('DROP TABLE IF EXISTS '. $wpdb->prefix.'sms_notifications_subscribes_group');
	delete_option("sms_notifications_db_version");
}
register_uninstall_hook( __FILE__, 'sms_notifications_uninstall' );