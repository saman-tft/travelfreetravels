<?php
/**
 * Library which has generic functions to get data
 *
 * @package    Provab Application
 * @subpackage Flight Model
 * @author     Balu A<balu.provab@gmail.com>
 * @version    V2
 */
Class Transaction extends CI_Model
{
	/**
	 * Lock All the tables necessary for flight transaction to be processed
	 */
	public static function lock_tables()
	{
		echo 'Under Construction';
	}

	/**f
	 * Create Payment record for payment gateway used in the application
	 */
	public function read_packagedetails($app_reference) {

		$query = "select p.package_id,p.module_type,p.package_name,p.package_description,pb.booking_source from package_booking_details as pb join package as p on pb.package_type=p.package_id where pb.app_reference='$app_reference'";
        $exe   = $this->db->query($query);
       if($exe->num_rows()>0)
       {
       	$data=$exe->result_array();
       	return $data;
       }
	}
	public function read_packagedetails_tran($app_reference) {

		$query = "select * from package_booking_details as pb where pb.app_reference='$app_reference'";
        $exe   = $this->db->query($query);
       if($exe->num_rows()>0)
       {
       	$data=$exe->result_array();
       	return $data;
       }
	}
		public function create_payment_record($app_reference, $booking_fare, $firstname, $email, $phone, $productinfo, $convenience_fees=0, $promocode_discount, $currency_conversion_rate,$reward_point=0,$reward_amount=0,$reward_earned=0)
	{
//echo "asd".$promocode_discount;
		$duplicate_pg = $this->read_payment_record($app_reference);
		if ($duplicate_pg == false) {
			$payment_gateway_currency = $this->config->item('payment_gateway_currency');
			$request_params = array('txnid' => $app_reference,
				'booking_fare' => $booking_fare,
				'convenience_amount' => $convenience_fees,
				'promocode_discount' => $promocode_discount,
					'rewards_point' => $reward_point,
					'rewards_amount' => $reward_amount,
					'reward_earned' => $reward_earned,
				'firstname' => $firstname,
				'email'=> $email,
				'phone'=> $phone);
			
			
			$data['amount'] = round($booking_fare+$convenience_fees-$promocode_discount);
		//debug($data['amount']);die;
//			debug($data['amount']);die;
			$data['domain_origin'] = get_domain_auth_id();
			$data['rewards_point'] = $reward_point;
			$data['rewards_amount'] = $reward_amount;
			$data['reward_earned'] = $reward_earned;
			$data['product_info'] = $productinfo;
			$data['app_reference'] = $app_reference;
			$data['request_params'] = json_encode($request_params);
			$data['currency'] = $payment_gateway_currency;
			$data['currency_conversion_rate'] = $currency_conversion_rate;
			$data['created_datetime'] = date('Y-m-d H:i:s');
			
			$this->custom_db->insert_record('payment_gateway_details', $data);
			return true;
		} else {
			return false;
		}
	}
	public function create_payment_recordoldeste($app_reference, $booking_fare, $firstname, $email, $phone, $productinfo, $convenience_fees=0, $promocode_discount, $currency_conversion_rate,$reward_point=0,$reward_amount=0,$reward_earned=0)
	{
//echo "asd".$promocode_discount;
		$duplicate_pg = $this->read_payment_record($app_reference);
		if ($duplicate_pg == false) {
			$payment_gateway_currency = $this->config->item('payment_gateway_currency');
			$request_params = array('txnid' => $app_reference,
				'booking_fare' => $booking_fare,
				'convenience_amount' => $convenience_fees,
				'promocode_discount' => $promocode_discount,
					'rewards_point' => $reward_point,
					'rewards_amount' => $reward_amount,
					'reward_earned' => $reward_earned,
					'productinfo' => $productinfo,
				'firstname' => $firstname,
				'email'=> $email,
				'phone'=> $phone);
			
			
			$data['amount'] = round($booking_fare+$convenience_fees-$promocode_discount);
		//debug($data['amount']);die;
//			debug($data['amount']);die;
			$data['domain_origin'] = get_domain_auth_id();
			$data['rewards_point'] = $reward_point;
			$data['rewards_amount'] = $reward_amount;
			$data['reward_earned'] = $reward_earned;
			$data['product_info'] = $productinfo;
			$data['app_reference'] = $app_reference;
			$data['request_params'] = json_encode($request_params);
			$data['currency'] = $payment_gateway_currency;
			$data['currency_conversion_rate'] = $currency_conversion_rate;
			$data['created_datetime'] = date('Y-m-d H:i:s');
			
			$this->custom_db->insert_record('payment_gateway_details', $data);
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Read Payment record with payment gateway reference
	 * @param $app_reference
	 */
	function read_payment_record($app_reference)
	{	
		
		$cond['app_reference'] = $app_reference;
		$data = $this->custom_db->single_table_records('payment_gateway_details', '*', $cond);
		if ($data['status'] == SUCCESS_STATUS) {
			return $data['data'][0];
		} else {
			return false;
		}
	}

	/**
	 * Update Payment record with payment gateway reference
	 * @param $app_reference
	 */
	function update_payment_record_status_with_out($app_reference, $response_params=array())
	{
		$cond['app_reference'] = $app_reference;		
		if (valid_array($response_params) == true) {
			$data['response_params'] = json_encode($response_params);
		}
		$this->custom_db->update_record('payment_gateway_details', $data, $cond);
	}
		function update_payment_record_status_new($app_reference, $status,$payment_mode)
	{
		$cond['app_reference'] = $app_reference;
		$data['status'] = $status;
	$data['payment_mode'] = $payment_mode;
		$this->custom_db->update_record('payment_gateway_details', $data, $cond);
	} 
		function update_payment_record_status($app_reference, $status,$payment_mode,$transaction_id,$response_params=array(),$create_process_booking_validation='')
	{
		$cond['app_reference'] = $app_reference;
		$data['status'] = $status;
		$data['payment_mode'] = $payment_mode;
		$data['transaction_id'] = $transaction_id;
		$data['payment_validation'] = $create_process_booking_validation;
		if (valid_array($response_params) == true) {
			$data['response_params'] = json_encode($response_params);
		}
		$this->custom_db->update_record('payment_gateway_details', $data, $cond);
	}
	function update_payment_record_statusoldest($app_reference, $status, $response_params=array())
	{
		$cond['app_reference'] = $app_reference;
		$data['status'] = $status;
		if (valid_array($response_params) == true) {
			$data['response_params'] = json_encode($response_params);
		}
		$this->custom_db->update_record('payment_gateway_details', $data, $cond);
	}

	/**
	 * Update additional details of transaction
	 */
	function update_convinence_discount_details($book_detail_table, $app_reference, $discount=0, $promo_code, $convinence=0, $convinence_value=0, $convinence_type=0, $convinence_per_pax=0, $gst=0)
	{
		$data = array();
		if (empty($discount) == false) {
			$data['discount'] = $discount;
		} else {
			$data['discount'] = 0;
		}
		if (empty($promo_code) == false) {
			$data['promo_code'] = $promo_code;
		} else {
			$data['promo_code'] = '';
		}
		if (empty($convinence) == false) {
			$data['convinence_amount'] = $convinence;
		}

		if (empty($convinence_value) == false) {
			$data['convinence_value'] = $convinence_value;
		}

		if (empty($convinence_type) == false) {
			$data['convinence_value_type'] = $convinence_type;
		}

		if (empty($convinence_per_pax) == false) {
			$data['convinence_per_pax'] = $convinence_per_pax;
		}
		if (empty($gst) == false) {
			$data['gst'] = $gst;
		}
		$cond['app_reference'] = $app_reference;
		$this->custom_db->update_record($book_detail_table, $data, $cond);

	}

	/*
	 * Sachin
	 * Returns the Payment Status
	 */
	public function get_payment_status($app_reference)
	{
		$this->db->select('*');
		$this->db->where('app_reference =', trim($app_reference));
 		$this->db->from('payment_gateway_details');
 		return $this->db->get()->row_array();
	}

	/**
	 * Unlock All The Tables
	 */
	public static function release_locked_tables()
	{
		$CI = & get_instance();
		$CI->db->query('UNLOCK TABLES');
	}
}
