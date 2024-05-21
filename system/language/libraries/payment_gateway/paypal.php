<?php if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
/**
 *
 * @package Provab
 * @subpackage paypal
 * @author Pankaj kumar <pankajprovab212@gmail.com>
 * @version V1
 */
class paypal {
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

	static $url;
	static $client_email;

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
		$this->CI->load->helper('custom/paypal_pgi_helper');
		$this->active_payment_system = $this->CI->config->item('active_payment_system');
	}

	function initialize($data)
	{	


		if ($this->active_payment_system == 'test') {
			self::$url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
			self::$client_email = 'sb-ofmn43573282@business.example.com';
			//self::$client_email = 'sb-fbtrr5301545@business.example.com';//password : admin@123
			//self::$client_email = 'contact2058@gmail.com';//password : admin@123
		} else {
			//die("carefully its live");
			self::$url = 'https://www.paypal.com/cgi-bin/webscr';
			self::$client_email = 'faisalhhh288@outlook.com';
		}
		$this->book_id = $data['txnid'];
		$this->pgi_amount = $data['pgi_amount'];
		$this->firstname = $data['firstname'];
		$this->email = $data['email'];
		$this->phone = $data['phone'];
		$this->productinfo = $data['productinfo'];
	}
	
	function process_payment() {
		$surl = base_url () . 'index.php/payment_gateway/verify/'.$this->book_id.'/'.$this->productinfo;
		$furl = base_url () . 'index.php/payment_gateway/cancel/'.$this->book_id.'/'.$this->productinfo;
		$PAYPAL_BASE_URL = 'https://www.paypal.com/cgi-bin/webscr';
		$url = $PAYPAL_BASE_URL . '/_payment';
		// $hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";	
		$post_data = array ();
		$post_data ['txnid'] = $this->book_id;
		$post_data ['amount'] = $this->pgi_amount;
		$post_data ['firstname'] = $this->firstname;
		$post_data ['email'] = $this->email;
		$post_data ['phone'] = $this->phone;
		$post_data ['productinfo'] = $this->productinfo;
		$post_data ['surl'] = $surl;
		$post_data ['furl'] = $furl;
		$post_data ['service_provider'] = 'Paypal';
		$post_data ['pay_target_url'] = self::$url;
		$post_data ['client_email'] = self::$client_email;
		//debug($post_data); exit("process_payment");
		return $post_data;
	}
}
