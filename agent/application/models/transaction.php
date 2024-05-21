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
	/**
	 * Create Payment record for payment gateway used in the application
	 */
	public function get_payment_status($app_reference)
	{
		$this->db->select('status');
		$this->db->where('app_reference =', trim($app_reference));
 		$this->db->from('payment_gateway_details');
 		return $this->db->get()->row_array();
	}
	public function create_payment_record($app_reference, $booking_fare, $firstname, $email, $phone, $productinfo, $convenience_fees=0, $promocode_discount=0,$currency_conversion_rate,$reward_point=0,$reward_amount=0,$reward_earned=0)
	{	
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
				'phone'=> $phone,
				'productinfo'=> $productinfo);
			//Add total amount and remove discount from total amount
			$data['amount'] = ceil($booking_fare+$convenience_fees-$promocode_discount);
			$data['domain_origin'] = get_domain_auth_id();
				$data['rewards_point'] = $reward_point;
			$data['rewards_amount'] = $reward_amount;
			$data['reward_earned'] = $reward_earned;
			$data['app_reference'] = $app_reference;
			$data['request_params'] = json_encode($request_params);
			$data['currency'] = $payment_gateway_currency;
			$data['currency_conversion_rate'] = $currency_conversion_rate;
			// debug($data);exit;
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
	function update_payment_record_status($app_reference, $status, $response_params=array())
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
	function update_convinence_discount_details($book_detail_table, $app_reference, $discount=0, $promo_code ='',$convinence=0, $convinence_value=0, $convinence_type=0, $convinence_per_pax=0, $gst=0)
	{
		
		$data = array();
		if (empty($discount) == false) {
			$data['discount'] = $discount;
		} else {
			$data['discount'] = 0;
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
	// debug($data);exit;
		$cond['app_reference'] = $app_reference;
		$this->custom_db->update_record($book_detail_table, $data, $cond);

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
