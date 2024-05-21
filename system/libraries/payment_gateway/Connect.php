<?php if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
/**
 *
 * @package Provab
 * @subpackage Payu
 * @author Pravinkumar P <pravinkumar.provab@gmail.com>
 * @version V1
 */
class Connect {
	/*
	 * Client Live credentials -_-
	 * ------------------------------
	 * Merchant ID		:	5331200
	 * ------------------------------
	 * Merchant Key		:	sZqbYi
	 * Merchant Salt	:	eMfHb7uk
	 * URL  :https://secure.payu.in
	 * ______________________________
	 */

	/**
	 * Client Test credentials -_-
	 * ------------------------------
	 * Merchant ID		:	4933825
	 * ------------------------------
	 * Merchant Key		:	4USjgC
	 * Merchant Salt	:	SCVEtzhP
	 * URL : https://test.payu.in
	 * ______________________________
	 */

	static $PID;
	static $sharedSecretKey;
	static $url;

	var $active_payment_system;

	var $book_id = '';
	var $book_origin = '';
	var $pgi_amount = '';
	var $name = '';
	var $email = '';
	var $phone = '';
	var $productinfo = '';
	public function __construct() {
		//echo "in connect lib";die;
		$this->CI = &get_instance ();
		//$this->CI->load->helper('custom/razorpay_pgi_helper');
		$this->active_payment_system = $this->CI->config->item('active_payment_system');
	}

	function initialize($data)
	{

		//echo 'hi';exit;
		if ($this->active_payment_system == 'test') {
			
			//test
			/*self::$key = 'rzp_test_dHI6SoUpWPKjGt';
			self::$salt = 'trRxQnTVxtTv8hwgWY70DIWn';
			self::$url = 'http://hoyyo.com/staging';*/
		//Server: https://clientapi.fonepay.com
			self::$PID = '';
			self::$sharedSecretKey = '';
			self::$url = '';
			
			
		} else {
			//live
			self::$PID = 'MEWH';
			self::$sharedSecretKey = '';
			self::$url = '';
			//self::$url = 'http://b2brayntourism.com';
		}
					self::$PID = 'MEWH';
			self::$sharedSecretKey = '';
			self::$url = '';
		$this->book_id = $data['txnid'];
		$this->pgi_amount = $data['pgi_amount'];
		$this->firstname = $data['firstname'];
		$this->email = $data['email'];
		$this->phone = $data['phone'];
		$this->productinfo = $data['productinfo'];
		//echo 'hi2';exit;
	}
	function process_payment(){
		$surl = base_url().'index.php/payment_gateway/success';
		$furl = base_url(). 'index.php/payment_gateway/cancel';
		
		//payumoney base url
		//$PAYU_BASE_URL = "http://test.payu.in";
		//$url = $PAYU_BASE_URL . '/_payment';
		//$hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";
		//$hash_string = self::$PID."|".$this->book_id."|".$this->pgi_amount."|".$this->productinfo."|".$this->firstname."|".$this->email."| | | | | | | | | |".self::$sharedSecretKey;
		//$hash = strtolower(hash('sha512', $hash_string));
		//Post_data to send data to the form (view) page
		$post_data=array();
		$post_data['PID'] = self::$PID;
		$post_data['txnid'] = $this->book_id;
		$post_data['amount'] = $this->pgi_amount;
		$post_data['firstname'] = $this->firstname;
		$post_data['email'] = $this->email;
		$post_data['phone'] = $this->phone;
		$post_data['productinfo'] = $this->productinfo;
		$post_data['surl'] = $surl;
		$post_data['furl'] = $furl;
		//$post_data['service_provider'] = 'payu_paisa';

		$post_data['pay_target_url'] = self::$url;
		$post_data['sharedSecretKey'] = self::$sharedSecretKey;
		$post_data['PID'] = self::$PID;
		// debug($post_data); exit();
		return $post_data;
	}
}
