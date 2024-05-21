<?php if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
/**
 *
 * @package Provab
 * @subpackage Payu
 * @author Pravinkumar P <pravinkumar.provab@gmail.com>
 * @version V1
 */
class Razorpay {
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

	static $key;
	static $salt;
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
		$this->CI = &get_instance ();
		$this->CI->load->helper('custom/razorpay_pgi_helper');
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

			self::$key = 'rzp_test_39YZEct960WAEt';
			self::$salt = 'tiygwMS1vTWwsu8waOxPVYic';
			self::$url = 'http://hoyyo.com/staging';
			
		} else {
			//live
			self::$key = 'rzp_live_y1PFN9ekSYM5Py';
			self::$salt = 'mfL6is3bp2nwGc16k7UwkgeO';
			self::$url = 'http://b2brayntourism.com';
		}
// debug($data); exit();
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
		$PAYU_BASE_URL = "http://test.payu.in";
		$url = $PAYU_BASE_URL . '/_payment';
		//$hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";
		$hash_string = self::$key."|".$this->book_id."|".$this->pgi_amount."|".$this->productinfo."|".$this->firstname."|".$this->email."| | | | | | | | | |".self::$salt;
		$hash = strtolower(hash('sha512', $hash_string));
		//Post_data to send data to the form (view) page
		$post_data=array();
		$post_data['key'] = self::$key;
		$post_data['txnid'] = $this->book_id;
		$post_data['amount'] = $this->pgi_amount;
		$post_data['firstname'] = $this->firstname;
		$post_data['email'] = $this->email;
		$post_data['phone'] = $this->phone;
		$post_data['productinfo'] = $this->productinfo;
		$post_data['surl'] = $surl;
		$post_data['furl'] = $furl;
		$post_data['service_provider'] = 'payu_paisa';

		$post_data['pay_target_url'] = self::$url;
		$post_data['salt'] = self::$salt;
		$post_data['key'] = self::$key;
		// debug($post_data); exit();
		return $post_data;
	}
}
