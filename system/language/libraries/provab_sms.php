<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

/**
 * provab
 *
 * Travel Portal Application
 *
 * @package provab
 * @author Balu A<balu.provab@gmail.com>
 * @copyright Copyright (c) 2013 - 2014
 * @link http://provab.com
 */
class Provab_Sms {
	public $CI; // instance of codeigniter super object
	public $sms_configuration; // sms configurations defined by user
	public function __construct($data = '') {
		if (valid_array ( $data ) == true and intval ( $data ['id'] ) > 0) {
			$id = intval ( $data ['id'] );
		} else {
			$id = GENERAL_SMS;
		}
		$this->CI = & get_instance ();
        
		$return_data = $this->CI->user_model->sms_configuration ( $id );
	//	debug($return_data);exit;
		$this->sms_configuration = $return_data;
	}
	/**
	 * switch statement to select sms-gateway
	 */
	public function send_msg($phone, $msg) {
		$gateway = $this->sms_configuration->gateway;
	
		switch ($gateway) {
			case "smsapi.24x7sms.com" :
				$this->infisms ( $phone, $msg );
				break;
			default :
				$status = false;
				return array (
						'status' => $status 
				);
				break;
		}
	}
	/**
	 * send sms to the user based on gateway from switch statement
	 */
	public function infisms($phone, $msg) {
	    
		$APIKEY = $this->sms_configuration->APIKEY;
		$password = $this->sms_configuration->password;
		$msg_link='https://smsapi.24x7sms.com/api_2.0/SendSMS.aspx?APIKEY='.$APIKEY.'&MobileNo=' . $phone . '&SenderID=SMSMsg&Message='.$msg.'&ServiceName=TEMPLATE_BASED';
		//$msg_link = 'http://ip.infisms.com/smsserver/SMS10N.aspx?Userid=' . $username . '&UserPassword=' . $password . '&PhoneNumber=' . $phone . '&Text=' . $msg;
		//echo $msg_link;exit;
		$url = $msg_link;
		//FIXME 
		
		/* $curl_handle = curl_init ();
		curl_setopt ( $curl_handle, CURLOPT_URL, $url );
		$status = curl_exec ( $curl_handle );
		curl_close ( $curl_handle ); */
		file_get_contents($url);
	}
}
?>
