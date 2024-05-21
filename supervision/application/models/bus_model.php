<?php
include_once 'report_model.php';
/**
 * @package    Provab Application
 * @subpackage Travel Portal
 * @author     Balu A<balu.provab@gmail.com>
 * @version    V2
 */
Class Bus_Model extends CI_Model implements report_model
{
	/**
	 *
	 * @param array $condition EX : array(array('booking_id', '=', 123))
	 * @param number $count
	 * @param number $offset
	 * @param number $limit
	 */
	function booking($condition=array(), $count=false, $offset=0, $limit=100000000000)
	{
		$condition = $this->custom_db->get_custom_condition($condition);
		if ($count) {
			$query = 'select count(distinct(BD.app_reference)) as total_records from bus_booking_details BD
					join bus_booking_customer_details BBCD on BD.app_reference=BBCD.app_reference 
					join bus_booking_itinerary_details AS ID on BD.app_reference=ID.app_reference
				 	join payment_option_list as POL on POL.payment_category_code=BD.payment_mode 
					where domain_origin='.get_domain_auth_id().''.$condition;
			$data = $this->db->query($query)->row_array();
			return $data['total_records'];
		} else {
			$this->load->library('booking_data_formatter');
			$response['status'] = SUCCESS_STATUS;
			$response['data'] = array();
			$booking_itinerary_details	= array();
			$booking_customer_details	= array();
			$bd_query = 'select * from bus_booking_details AS BD 
						WHERE BD.domain_origin='.get_domain_auth_id().'
						order by BD.origin desc limit '.$offset.', '.$limit;
			//'.$condition.'
			$booking_details = $this->db->query($bd_query)->result_array();
			$app_reference_ids = $this->booking_data_formatter->implode_app_reference_ids($booking_details);
			if(empty($app_reference_ids) == false) {
				$id_query = 'select * from bus_booking_itinerary_details AS ID 
							WHERE ID.app_reference IN ('.$app_reference_ids.')';
				$cd_query = 'select * from bus_booking_customer_details AS CD 
							WHERE CD.app_reference IN ('.$app_reference_ids.')';
				$booking_itinerary_details	= $this->db->query($id_query)->result_array();
				$booking_customer_details	= $this->db->query($cd_query)->result_array();
			}
			$response['data']['booking_details']			= $booking_details;
			$response['data']['booking_itinerary_details']	= $booking_itinerary_details;
			$response['data']['booking_customer_details']	= $booking_customer_details;
			return $response;
		}
	}
//------------------sudheep------------
	function b2c_bus_report($condition=array(), $count=false, $offset=0, $limit=100000000000)
	{
		$condition = $this->custom_db->get_custom_condition($condition);

		if(isset($condition) == true)
		{
			$offset = 0;
		}else{
			$offset = $offset;
		}

		if ($count) {

			$query = 'select count(distinct(BD.app_reference)) as total_records from bus_booking_details BD
					join bus_booking_customer_details BBCD on BD.app_reference=BBCD.app_reference
					join bus_booking_itinerary_details AS ID on BD.app_reference=ID.app_reference
					join payment_option_list as POL on POL.payment_category_code=BD.payment_mode
					left join user as U on BD.created_by_id = U.user_id  where (U.user_type='.B2C_USER.'
					OR BD.created_by_id = 0) AND  domain_origin='.get_domain_auth_id().''.$condition;

			$data = $this->db->query($query)->row_array();
		//	debug($data); die;
			return $data['total_records'];
		} else {
			$this->load->library('booking_data_formatter');
			$response['status'] = SUCCESS_STATUS;
			$response['data'] = array();
			$booking_itinerary_details	= array();
			$booking_customer_details	= array();

			$bd_query = 'select BD.*,U.user_name,U.first_name,U.last_name from bus_booking_details AS BD
						 left join user U on BD.created_by_id =U.user_id
						WHERE  (U.user_type='.B2C_USER.' OR BD.created_by_id = 0) AND BD.domain_origin='.get_domain_auth_id().' '.$condition.'
						 order by BD.created_datetime desc, BD.origin desc limit '.$offset.', '.$limit;
			
			$booking_details = $this->db->query($bd_query)->result_array();
			$app_reference_ids = $this->booking_data_formatter->implode_app_reference_ids($booking_details);
			if(empty($app_reference_ids) == false) {
				$id_query = 'select * from bus_booking_itinerary_details AS ID 
							WHERE ID.app_reference IN ('.$app_reference_ids.')';
				$cd_query = 'select * from bus_booking_customer_details AS CD 
							WHERE CD.app_reference IN ('.$app_reference_ids.')';
				$pd_query = 'select * from  payment_gateway_details AS PD
							WHERE PD.app_reference IN ('.$app_reference_ids.')';
				$booking_itinerary_details	= $this->db->query($id_query)->result_array();
				$booking_customer_details	= $this->db->query($cd_query)->result_array();
				$payment_details = $this->db->query($pd_query)->result_array();
			}
			$response['data']['booking_details']			= $booking_details;
			$response['data']['booking_itinerary_details']	= $booking_itinerary_details;
			$response['data']['booking_customer_details']	= $booking_customer_details;
			$response['data']['payment_details']	= $payment_details;
			// debug($response);exit;
			return $response;
		}
	}
	function b2c_bus_report_all_invoice($user_type='',$condition=array(), $count=false, $offset=0, $limit=100000000000)
	{
		$condition = $this->custom_db->get_custom_condition($condition);

		if(isset($condition) == true)
		{
			$offset = 0;
		}else{
			$offset = $offset;
		}

		
			$this->load->library('booking_data_formatter');
			$response['status'] = SUCCESS_STATUS;
			$response['data'] = array();
			$booking_itinerary_details	= array();
			$booking_customer_details	= array();
			if($user_type==B2C_USER)
			{

			$bd_query = 'select BD.*,U.user_name,U.first_name,U.last_name from bus_booking_details AS BD
						 left join user U on BD.created_by_id =U.user_id
						WHERE  (U.user_type='.B2C_USER.' OR BD.created_by_id = 0) AND BD.domain_origin='.get_domain_auth_id().' '.$condition.'
						 order by BD.created_datetime desc, BD.origin desc limit '.$offset.', '.$limit;
			}
			else
			{
				$bd_query = 'select BD.* ,U.agency_name,U.first_name,U.last_name from bus_booking_details AS BD
					     left join user U on U.user_id = BD.created_by_id
						 WHERE  U.user_type='.B2B_USER.' AND BD.domain_origin='.get_domain_auth_id().' '.$condition.'
						 order by BD.created_datetime desc, BD.origin desc limit '.$offset.', '.$limit;
			}
			
			$booking_details = $this->db->query($bd_query)->result_array();
			
			// debug($response);exit;
			return $booking_details;
		
	}
	function b2b_bus_report($condition=array(), $count=false, $offset=0, $limit=100000000000)
	{
		$condition = $this->custom_db->get_custom_condition($condition);
		
		if(isset($condition) == true)
		{
			$offset = 0;
		}else{
			$offset = $offset;
		}

		if ($count) {

			$query = 'select count(distinct(BD.app_reference)) as total_records from bus_booking_details BD 
					join bus_booking_customer_details BBCD on BD.app_reference=BBCD.app_reference 
					join bus_booking_itinerary_details AS ID on BD.app_reference=ID.app_reference 
					join payment_option_list as POL on POL.payment_category_code=BD.payment_mode 
					left join user as U on BD.created_by_id = U.user_id where U.user_type='.B2B_USER.' 
					AND  domain_origin='.get_domain_auth_id().''.$condition;
//debug($query); die;

			$data = $this->db->query($query)->row_array();
			return $data['total_records'];
		} else {
			$this->load->library('booking_data_formatter');
			$response['status'] = SUCCESS_STATUS;
			$response['data'] = array();
			$booking_itinerary_details	= array();
			$booking_customer_details	= array();
			$bd_query = 'select BD.* ,U.agency_name,U.first_name,U.last_name from bus_booking_details AS BD
					     left join user U on U.user_id = BD.created_by_id
						 WHERE  U.user_type='.B2B_USER.' AND BD.domain_origin='.get_domain_auth_id().' '.$condition.'
						 order by BD.created_datetime desc, BD.origin desc limit '.$offset.', '.$limit;

			$booking_details = $this->db->query($bd_query)->result_array();
			$app_reference_ids = $this->booking_data_formatter->implode_app_reference_ids($booking_details);
			if(empty($app_reference_ids) == false) {
				$id_query = 'select * from bus_booking_itinerary_details AS ID 
							WHERE ID.app_reference IN ('.$app_reference_ids.')';
				$cd_query = 'select * from bus_booking_customer_details AS CD 
							WHERE CD.app_reference IN ('.$app_reference_ids.')';
				$booking_itinerary_details	= $this->db->query($id_query)->result_array();
				$booking_customer_details	= $this->db->query($cd_query)->result_array();
			}
			$response['data']['booking_details']			= $booking_details;
			$response['data']['booking_itinerary_details']	= $booking_itinerary_details;
			$response['data']['booking_customer_details']	= $booking_customer_details;
			return $response;
		}
	}
	
	/**
	 * Read Individual booking details - dont use it to generate table
	 * @param $app_reference
	 * @param $booking_source
	 * @param $booking_status
	 */
	function get_booking_details($app_reference, $booking_source, $booking_status='')
	{
		//bus_booking_details
		//bus_booking_itinerary_details
		//bus_booking_customer_details
		$response['status'] = FAILURE_STATUS;
		$response['data'] = array();
		$bd_query = 'select * from bus_booking_details AS BD WHERE BD.app_reference like '.$this->db->escape($app_reference);
		if (empty($booking_source) == false) {
			$bd_query .= '	AND BD.booking_source = '.$this->db->escape($booking_source);
		}
		if (empty($booking_status) == false) {
			$bd_query .= ' AND BD.status = '.$this->db->escape($booking_status);
		}
		$id_query = 'select * from bus_booking_itinerary_details AS ID WHERE ID.app_reference='.$this->db->escape($app_reference);
		$cd_query = 'select * from bus_booking_customer_details AS CD WHERE CD.app_reference='.$this->db->escape($app_reference);
		$cancellation_details_query = 'select BCD.* from bus_cancellation_details AS BCD WHERE BCD.app_reference='.$this->db->escape($app_reference);
		$response['data']['booking_details']			= $this->db->query($bd_query)->result_array();
		$response['data']['booking_itinerary_details']	= $this->db->query($id_query)->result_array();
		$response['data']['booking_customer_details']	= $this->db->query($cd_query)->result_array();
		$response['data']['cancellation_details']	= $this->db->query($cancellation_details_query)->result_array();
		if (valid_array($response['data']['booking_details']) == true and valid_array($response['data']['booking_itinerary_details']) == true and valid_array($response['data']['booking_customer_details']) == true) {
			$response['status'] = SUCCESS_STATUS;
		}
		return $response;
	}



	/**
	 * Get auth token for bus - only for travel yaari
	 */
	function get_auth_token()
	{
		//get_auth_token
		$data = $this->custom_db->single_table_records('temp_cache', '*', array('domain_list_fk' => get_domain_auth_id(), 'type' => 'travelyaari'));
		if ($data['status']== SUCCESS_STATUS) {
			return $data['data'][0];
		} else {
			return false;
		}
	}

	/**
	 * Set auth token cache for travel yaari
	 * @param unknown_type $data
	 */
	function set_auth_token($data)
	{
		$this->custom_db->insert_record('temp_cache', array('domain_list_fk' => get_domain_auth_id(), 'type' => 'travelyaari', 'data' => $data, 'created_datetime' => date('Y-m-d H:i:s')));
	}

	/**
	 * return all booking events
	 */
	function booking_events()
	{
		//BT, CD, ID
		$query = 'select * from bus_booking_details where domain_origin='.get_domain_auth_id();
		return $this->db->query($query)->result_array();
	}

	function get_monthly_booking_summary($condition=array())
	{
		$condition = $this->custom_db->get_custom_condition($condition);
		$query = 'select count(distinct(BD.app_reference)) AS total_booking, sum(BBCD.fare+BBCD.admin_markup+BBCD.agent_markup) as monthly_payment, 
		sum(BBCD.admin_commission) as monthly_earning, MONTH(BD.created_datetime) as month_number
		from bus_booking_details BD
		join bus_booking_customer_details BBCD on BD.app_reference=BBCD.app_reference
		where (YEAR(BD.created_datetime) BETWEEN '.date('Y').' AND '.date('Y', strtotime('+1 year')).')  and BD.domain_origin='.get_domain_auth_id().' '.$condition.' 
		GROUP BY YEAR(BD.created_datetime), MONTH(BD.created_datetime)';
		return $this->db->query($query)->result_array();
	}
	function get_daily_booking_summary()
	{
		$query = 'select count(*) AS total_booking, sum(BD.total_fare+BD.level_one_markup) as daily_payment, sum(BD.domain_markup) as daily_earning from bus_booking_details AS BD
		where BD.created_datetime>= "'.date("Y-m-d 00:00:00").'" AND BD.created_datetime<= "'.date("Y-m-d 23:59:59").'"  AND BD.domain_origin='.get_domain_auth_id();
		return $this->db->query($query)->result_array();
	}

	function monthly_search_history($year_start, $year_end)
	{
		$query = 'select count(*) AS total_search, MONTH(created_datetime) as month_number from search_bus_history where
		(YEAR(created_datetime) BETWEEN '.$year_start.' AND '.$year_end.') AND domain_origin='.get_domain_auth_id().' 
		AND search_type="'.META_BUS_COURSE.'"
		GROUP BY YEAR(created_datetime), MONTH(created_datetime)';
		return $this->db->query($query)->result_array();
	}

	function top_search($year_start, $year_end)
	{
		$query = 'select count(*) AS total_search, concat(from_station, "-",to_station) label from search_bus_history where
		(YEAR(created_datetime) BETWEEN '.$year_start.' AND '.$year_end.') AND domain_origin='.get_domain_auth_id().' 
		AND search_type="'.META_BUS_COURSE.'"
		GROUP BY CONCAT(from_station, to_station) order by count(*) desc, created_datetime desc limit 0, 15';
		return $this->db->query($query)->result_array();
	}
/*
	 * Balu A
	 * Update cancellation details
	 */
	function update_cancellation_details($app_reference, $booking_status, $cancellation_details)
	{
		// debug($cancellation_details);exit;
		
		//1. Update Master Booking Status
		$update_condition['app_reference'] = trim($app_reference);
		$update_data['status'] = trim($booking_status);
		$GLOBALS['CI']->custom_db->update_record('bus_booking_details', $update_data, $update_condition);
		//2. Update Customer Ticket Status
		$GLOBALS['CI']->custom_db->update_record('bus_booking_customer_details', $update_data, $update_condition);
		//3.Adding cancellationde details
		$bus_cancellation_details = array();
		$CancelTicket2Result  = $cancellation_details['data']['CancelSeats'];
		$RefundAmount = $CancelTicket2Result['RefundAmount'];
		$ChargePct = $CancelTicket2Result['ChargePct'];
		// debug($CancelTicket2Result);exit;
		$bus_cancellation_details['app_reference'] = 				$app_reference;
		$bus_cancellation_details['cancellation_status'] = 			$booking_status;
		$bus_cancellation_details['api_refund_amount'] = 			$RefundAmount;
		$bus_cancellation_details['api_cancel_charge_percentage'] =	$ChargePct;
		$bus_cancellation_details['created_by_id'] = 				intval(@$this->entity_user_id);
		$bus_cancellation_details['created_datetime'] = 			db_current_datetime();
		$bus_cancellation_details['attributes'] = 					json_encode($cancellation_details);
		$this->custom_db->insert_record('bus_cancellation_details', $bus_cancellation_details);
	}
	function get_static_response($token_id)
	{
		$static_response = $this->custom_db->single_table_records('test', '*', array('origin' => intval($token_id)));
		return json_decode($static_response['data'][0]['test'], true);
	}
}
