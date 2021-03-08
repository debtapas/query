<?php
require_once '../../../../wp-load.php';
if (is_user_logged_in() && isset( $_POST['_wpnonce']) && wp_verify_nonce( $_POST['_wpnonce'],'csv_exporter' )) {

		check_admin_referer( 'csv_exporter' );
		
		$options = get_option( 'sms_notifications_option_name' );
        $settings['sms_auth_token'] = $options['api_key']; 
        $textlocal_log = new textlocal(false, false, $settings['sms_auth_token']);
        $min_time = strtotime('-1 month'); // Get sends between a month ago 
        $max_time = time(); // and now
        $limit = 1000;
        $start = 0;

        $response = $textlocal_log->getAPIMessageHistory($start, $limit, $min_time, $max_time);
       
        $messages = $response->messages;
        
        $messages_export = array();
        foreach ($messages as $key => $value) {
         $Datetime=$value->datetime;
         $Number =$value->number;
         $Sender= $value->sender;
         $Message=$value->content;
         $Status=$value->status;
         $messages_export[] = array(
             'Datetime' =>$Datetime ,
             'Number' => $Number,
             'Sender' => $Sender,
             'Message' => $Message,
             'Status' => $Status
          );
       }

		$filename = 'export-loghistory-'.date_i18n( "Y-m-d_H-i-s" ).'.csv';
		$filepath = SMS_NOTIFICATIONS_GATEEWAY_DIR . '/download/'.$filename;
		$fp = fopen( $filepath, 'w' );

		$heading = false;
        if(!empty($messages_export))
            foreach($messages_export as $fields) {
             	
             if(!$heading) {
               // output the column headings
               fputcsv($fp, array_keys($fields));
               $heading = true;
            }
            fputcsv($fp, array_values($fields));
        }

		fclose( $fp );
		header( 'Content-Type:application/octet-stream' );
		header( 'Content-Disposition:filename='.$filename );
		header( 'Content-Length:' . filesize( $filepath ) );
		readfile( $filepath ); 
		unlink( $filepath );
}
?>