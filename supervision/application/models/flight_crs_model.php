<?php
/**
 * Library which has generic functions to get data
 *
 * @package    Provab Application
 * @subpackage Flight Model
 * @author     Arjun J<arjunjgowda260389@gmail.com>
 * @version    V2
 */
Class Flight_Crs_Model extends CI_Model
{
	/**
	 *TEMPORARY FUNCTION NEEDS TO BE CLEANED UP IN PRODUCTION ENVIRONMENT
	 */
	function get_static_response($token_id)
	{
		$static_response = $this->custom_db->single_table_records('test', '*', array('origin' => intval($token_id)));
		return json_decode($static_response['data'][0]['test'], true);
	}
	/**
	 * Flight booking report
	 *
	 */
	function booking($condition=array(), $count=false, $offset=0, $limit=100000000000)
	{
		$condition = $this->custom_db->get_custom_condition($condition);
		//BT, CD, ID
		if ($count) {
			$query = 'select count(distinct(BD.app_reference)) AS total_records from flight_booking_details BD
					where domain_origin='.get_domain_auth_id().''.$condition;
			$data = $this->db->query($query)->row_array();
			return $data['total_records'];
		} else {
			$this->load->library('booking_data_formatter');
			$response['status'] = SUCCESS_STATUS;
			$response['data'] = array();
			$booking_itinerary_details	= array();
			$booking_customer_details	= array();
			$booking_transaction_details = array();
			$cancellation_details = array();
			$payment_details = array();
			//Booking Details
			$bd_query = 'select * from flight_booking_details AS BD
						WHERE BD.domain_origin='.get_domain_auth_id().' '.$condition.'
						order by BD.created_datetime desc, BD.origin desc limit '.$offset.', '.$limit;
			$booking_details	= $this->db->query($bd_query)->result_array();
			$app_reference_ids = $this->booking_data_formatter->implode_app_reference_ids($booking_details);
			if(empty($app_reference_ids) == false) {
				//Itinerary Details
				$id_query = 'select * from flight_booking_itinerary_details AS ID
							WHERE ID.app_reference IN ('.$app_reference_ids.')';
				//Transaction Details
				$td_query = 'select * from flight_booking_transaction_details AS TD
							WHERE TD.app_reference IN ('.$app_reference_ids.')';
				//Customer and Ticket Details
				$cd_query = 'select CD.*,FPTI.TicketId,FPTI.TicketNumber,FPTI.IssueDate,FPTI.Fare,FPTI.SegmentAdditionalInfo
							from flight_booking_passenger_details AS CD
							left join flight_passenger_ticket_info FPTI on CD.origin=FPTI.passenger_fk
							WHERE CD.flight_booking_transaction_details_fk IN 
							(select TD.origin from flight_booking_transaction_details AS TD 
							WHERE TD.app_reference IN ('.$app_reference_ids.'))';
				//Cancellation Details
				$cancellation_details_query = 'select FCD.*
						from flight_booking_passenger_details AS CD
						left join flight_cancellation_details AS FCD ON FCD.passenger_fk=CD.origin
						WHERE CD.flight_booking_transaction_details_fk IN 
						(select TD.origin from flight_booking_transaction_details AS TD 
						WHERE TD.app_reference IN ('.$app_reference_ids.'))';
				//$payment_details_query = '';
				$booking_itinerary_details	= $this->db->query($id_query)->result_array();
				$booking_customer_details	= $this->db->query($cd_query)->result_array();
				$booking_transaction_details = $this->db->query($td_query)->result_array();
				$cancellation_details = $this->db->query($cancellation_details_query)->result_array();
				//$payment_details = $this->db->query($payment_details_query)->result_array();
			}
				
			$response['data']['booking_details']			= $booking_details;
			$response['data']['booking_itinerary_details']	= $booking_itinerary_details;
			$response['data']['booking_transaction_details']	= $booking_transaction_details;
			$response['data']['booking_customer_details']	= $booking_customer_details;
			$response['data']['cancellation_details']	= $cancellation_details;
			//$response['data']['payment_details']	= $payment_details;
			return $response;
		}
	}
	/**
	 * Read Individual booking details - dont use it to generate table
	 * @param $app_reference
	 * @param $booking_source
	 * @param $booking_status
	 */
	function get_booking_details($app_reference, $booking_source='', $booking_status='')
	{
		$response['status'] = FAILURE_STATUS;
		$response['data'] = array();
		//Booking Details
		$bd_query = 'select * from flight_booking_details AS BD WHERE BD.app_reference like '.$this->db->escape($app_reference);
		if (empty($booking_source) == false) {
			$bd_query .= '	AND BD.booking_source = '.$this->db->escape($booking_source);
		}
		if (empty($booking_status) == false) {
			$bd_query .= ' AND BD.status = '.$this->db->escape($booking_status);
		}
		//Itinerary Details
		$id_query = 'select * from flight_booking_itinerary_details AS ID WHERE ID.app_reference='.$this->db->escape($app_reference);
		//Transaction Details
		$td_query = 'select * from flight_booking_transaction_details AS CD WHERE CD.app_reference='.$this->db->escape($app_reference);
		//Customer and Ticket Details
		$cd_query = 'select CD.*
						from flight_booking_passenger_details AS CD
						WHERE CD.flight_booking_transaction_details_fk IN 
						(select TD.origin from flight_booking_transaction_details AS TD 
						WHERE TD.app_reference ='.$this->db->escape($app_reference).')  order by origin asc';

		// Original query
/*		$cd_query = 'select DISTINCT CD.*, FPTI.TicketId,FPTI.TicketNumber,FPTI.IssueDate,FPTI.Fare,FPTI.SegmentAdditionalInfo
						from flight_booking_passenger_details AS CD
						left join flight_passenger_ticket_info FPTI on CD.origin=FPTI.passenger_fk
						WHERE CD.flight_booking_transaction_details_fk IN 
						(select TD.origin from flight_booking_transaction_details AS TD 
						WHERE TD.app_reference ='.$this->db->escape($app_reference).')';*/

					//	debug($cd_query); exit;
		// Cancellation Details
		$cancellation_details_query = 'select  FCD.*
						from flight_booking_passenger_details AS CD
						left join flight_cancellation_details AS FCD ON FCD.passenger_fk=CD.origin
						WHERE CD.flight_booking_transaction_details_fk IN 
						(select TD.origin from flight_booking_transaction_details AS TD 
						WHERE TD.app_reference ='.$this->db->escape($app_reference).')';
		
		//Baggage Details
		$baggage_query = 'select CD.flight_booking_transaction_details_fk,
						concat(CD.first_name," ", CD.last_name) as pax_name,FBG.*
						from flight_booking_passenger_details AS CD
						join flight_booking_baggage_details FBG on CD.origin=FBG.passenger_fk
						WHERE CD.flight_booking_transaction_details_fk IN 
						(select TD.origin from flight_booking_transaction_details AS TD 
						WHERE TD.app_reference ='.$this->db->escape($app_reference).')';
		//Meal Details
		$meal_query = 'select CD.flight_booking_transaction_details_fk,
						concat(CD.first_name," ", CD.last_name) as pax_name,FML.*
						from flight_booking_passenger_details AS CD
						join flight_booking_meal_details FML on CD.origin=FML.passenger_fk
						WHERE CD.flight_booking_transaction_details_fk IN 
						(select TD.origin from flight_booking_transaction_details AS TD 
						WHERE TD.app_reference ='.$this->db->escape($app_reference).')';
		//Seat Details
		$seat_query = 'select CD.flight_booking_transaction_details_fk,
						concat(CD.first_name," ", CD.last_name) as pax_name,FST.*
						from flight_booking_passenger_details AS CD
						join flight_booking_seat_details FST on CD.origin=FST.passenger_fk
						WHERE CD.flight_booking_transaction_details_fk IN 
						(select TD.origin from flight_booking_transaction_details AS TD 
						WHERE TD.app_reference ='.$this->db->escape($app_reference).')';

		$response['data']['booking_details']			= $this->db->query($bd_query)->result_array();
		$response['data']['booking_itinerary_details']	= $this->db->query($id_query)->result_array();
		$response['data']['booking_transaction_details']	= $this->db->query($td_query)->result_array();
		$response['data']['booking_customer_details']	= $this->db->query($cd_query)->result_array();
		$response['data']['cancellation_details']	= $this->db->query($cancellation_details_query)->result_array();
		$response['data']['baggage_details']	= $this->db->query($baggage_query)->result_array();
		$response['data']['meal_details']	= $this->db->query($meal_query)->result_array();
		$response['data']['seat_details']	= $this->db->query($seat_query)->result_array();
		
		if (valid_array($response['data']['booking_details']) == true and valid_array($response['data']['booking_itinerary_details']) == true and valid_array($response['data']['booking_customer_details']) == true) {
			$response['status'] = SUCCESS_STATUS;
		}
		return $response;
	}
	/**
	 * Sagar Wakchaure
	 * B2C Flight Report
	 * @param unknown $condition
	 * @param unknown $count
	 * @param unknown $offset
	 * @param unknown $limit
	 * $condition[] = array('U.user_typ', '=', B2C_USER, ' OR ', 'BD.created_by_i', '=', 0);
	 */
	function b2c_flight_report($condition=array(), $count=false, $offset=0, $limit=100000000000)
	{
		$condition = $this->custom_db->get_custom_condition($condition);
		//$b2c_condition_array = array('U.user_type', '=', B2C_USER, ' OR ', 'BD.created_by_id', '=', 0);
		
		//BT, CD, ID

		if(isset($condition) == true)
		{
			$offset = 0;
		}else{
			
			$offset = $offset;
		}


		if ($count) {
			
			//echo debug($condition);exit;
			$query = 'select count(distinct(BD.app_reference)) AS total_records from flight_booking_details BD
					left join user U on U.user_id = BD.created_by_id
					left join user_type UT on UT.origin = U.user_type
					join flight_booking_transaction_details as BT on BD.app_reference = BT.app_reference	
					where (U.user_type='.B2C_USER.' OR BD.created_by_id = 0) AND BD.domain_origin='.get_domain_auth_id().''.$condition;
			//echo debug($query);exit;
			
			$data = $this->db->query($query)->row_array();
			
			return $data['total_records'];

		} else {
			$this->load->library('booking_data_formatter');
			$response['status'] = SUCCESS_STATUS;
			$response['data'] = array();
			$booking_itinerary_details	= array();
			$booking_customer_details	= array();
			$booking_transaction_details = array();
			$cancellation_details = array();
			$payment_details = array();
			//Booking Details
			$bd_query = 'select BD.* ,U.user_name,U.first_name,U.last_name,U.user_type from flight_booking_details AS BD
					     left join user U on U.user_id = BD.created_by_id
					     left join user_type UT on UT.origin = U.user_type
					     join flight_booking_transaction_details as BT on BD.app_reference = BT.app_reference		
						 WHERE  (U.user_type='.B2C_USER.' OR BD.created_by_id = 0) AND BD.domain_origin='.get_domain_auth_id().' '.$condition.'
						 order by BD.created_datetime desc, BD.origin desc limit '.$offset.', '.$limit;		

						 
						 
			$booking_details	= $this->db->query($bd_query)->result_array();
			//echo debug($booking_details); 			exit;
			$app_reference_ids = $this->booking_data_formatter->implode_app_reference_ids($booking_details);
			if(empty($app_reference_ids) == false) {
				//Itinerary Details
				$id_query = 'select * from flight_booking_itinerary_details AS ID
							WHERE ID.app_reference IN ('.$app_reference_ids.')';
				//Transaction Details
				$td_query = 'select * from flight_booking_transaction_details AS TD
							WHERE TD.app_reference IN ('.$app_reference_ids.')';
				//Customer and Ticket Details
				$cd_query = 'select CD.*,FPTI.TicketId,FPTI.TicketNumber,FPTI.IssueDate,FPTI.Fare,FPTI.SegmentAdditionalInfo
							from flight_booking_passenger_details AS CD
							left join flight_passenger_ticket_info FPTI on CD.origin=FPTI.passenger_fk
							WHERE CD.flight_booking_transaction_details_fk IN
							(select TD.origin from flight_booking_transaction_details AS TD
							WHERE TD.app_reference IN ('.$app_reference_ids.'))';
				//Cancellation Details
				$cancellation_details_query = 'select FCD.*
						from flight_booking_passenger_details AS CD
						left join flight_cancellation_details AS FCD ON FCD.passenger_fk=CD.origin
						WHERE CD.flight_booking_transaction_details_fk IN
						(select TD.origin from flight_booking_transaction_details AS TD
						WHERE TD.app_reference IN ('.$app_reference_ids.'))';
				//$payment_details_query = '';
				$booking_itinerary_details	= $this->db->query($id_query)->result_array();
				$booking_customer_details	= $this->db->query($cd_query)->result_array();
				$booking_transaction_details = $this->db->query($td_query)->result_array();
				$cancellation_details = $this->db->query($cancellation_details_query)->result_array();
				//$payment_details = $this->db->query($payment_details_query)->result_array();
			}
	
			$response['data']['booking_details']			= $booking_details;
			$response['data']['booking_itinerary_details']	= $booking_itinerary_details;
			$response['data']['booking_transaction_details']	= $booking_transaction_details;
			$response['data']['booking_customer_details']	= $booking_customer_details;
			$response['data']['cancellation_details']	= $cancellation_details;
			//$response['data']['payment_details']	= $payment_details;
		//	debug($response); exit;
			return $response;
		}
	}	
	
	
	/**
	 * Sagar Wakchaure
	 * B2C Flight Report
	 * @param unknown $condition
	 * @param unknown $count
	 * @param unknown $offset
	 * @param unknown $limit
	 * $condition[] = array('U.user_typ', '=', B2C_USER, ' OR ', 'BD.created_by_i', '=', 0);
	 */
	function b2b_flight_report($condition=array(), $count=false, $offset=0, $limit=100000000000)
	{
		$condition = $this->custom_db->get_custom_condition($condition);
		//$b2c_condition_array = array('U.user_type', '=', B2C_USER, ' OR ', 'BD.created_by_id', '=', 0);
	
		//BT, CD, ID
//debug($condition); exit;
		if(isset($condition) == true)
		{
			$offset = 0;
		}else{
			$offset = $offset;
		}

		if ($count) {
				
			//echo debug($condition);exit;
			$query = 'select count(distinct(BD.app_reference)) AS total_records from flight_booking_details BD
					  join user U on U.user_id = BD.created_by_id
					  join flight_booking_transaction_details as BT on BD.app_reference = BT.app_reference						
					  where U.user_type='.B2B_USER.' AND BD.domain_origin='.get_domain_auth_id().''.$condition;
			
				
		
			$data = $this->db->query($query)->row_array();
			//echo debug($data);exit;
			return $data['total_records'];
		} else {
			$this->load->library('booking_data_formatter');
			$response['status'] = SUCCESS_STATUS;
			$response['data'] = array();
			$booking_itinerary_details	= array();
			$booking_customer_details	= array();
			$booking_transaction_details = array();
			$cancellation_details = array();
			$payment_details = array();
			//Booking Details
			$bd_query = 'select BD.*,U.agency_name,U.first_name,U.last_name from flight_booking_details AS BD
					      join user U on U.user_id = BD.created_by_id join flight_booking_transaction_details as BT on BD.app_reference = BT.app_reference					      
						  WHERE  U.user_type='.B2B_USER.' AND BD.domain_origin='.get_domain_auth_id().' '.$condition.'
						  order by BD.created_datetime desc, BD.origin desc limit '.$offset.', '.$limit;
						  
			//echo debug($bd_query);			
			//exit;
			
			$booking_details	= $this->db->query($bd_query)->result_array();
			//echo debug($booking_details);exit;
			$app_reference_ids = $this->booking_data_formatter->implode_app_reference_ids($booking_details);
			if(empty($app_reference_ids) == false) {
				//Itinerary Details
				$id_query = 'select * from flight_booking_itinerary_details AS ID
							WHERE ID.app_reference IN ('.$app_reference_ids.')';
				//Transaction Details
				$td_query = 'select * from flight_booking_transaction_details AS TD
							WHERE TD.app_reference IN ('.$app_reference_ids.')';
				
				//Customer and Ticket Details
				$cd_query = 'select DISTINCT CD.*,FPTI.TicketId,FPTI.TicketNumber,FPTI.IssueDate,FPTI.Fare,FPTI.SegmentAdditionalInfo
							from flight_booking_passenger_details AS CD
							left join flight_passenger_ticket_info FPTI on CD.origin=FPTI.passenger_fk
							WHERE CD.flight_booking_transaction_details_fk IN
							(select TD.origin from flight_booking_transaction_details AS TD
							WHERE TD.app_reference IN ('.$app_reference_ids.'))';
				//Cancellation Details
				$cancellation_details_query = 'select FCD.*
						from flight_booking_passenger_details AS CD
						left join flight_cancellation_details AS FCD ON FCD.passenger_fk=CD.origin
						WHERE CD.flight_booking_transaction_details_fk IN
						(select TD.origin from flight_booking_transaction_details AS TD
						WHERE TD.app_reference IN ('.$app_reference_ids.'))';
				//$payment_details_query = '';
				$booking_itinerary_details	= $this->db->query($id_query)->result_array();
				$booking_customer_details	= $this->db->query($cd_query)->result_array();
				$booking_transaction_details = $this->db->query($td_query)->result_array();
				$cancellation_details = $this->db->query($cancellation_details_query)->result_array();
				//$payment_details = $this->db->query($payment_details_query)->result_array();
			}
	
			$response['data']['booking_details']			= $booking_details;
			$response['data']['booking_itinerary_details']	= $booking_itinerary_details;
			$response['data']['booking_transaction_details']	= $booking_transaction_details;
			$response['data']['booking_customer_details']	= $booking_customer_details;
			$response['data']['cancellation_details']	= $cancellation_details;
			//$response['data']['payment_details']	= $payment_details;
			return $response;
		}
	}
	

	/**
	 * return all booking events
	 */
	function booking_events()
	{
		//BT, CD, ID
		$query = 'select * from flight_booking_details where domain_origin='.get_domain_auth_id();
		return $this->db->query($query)->result_array();
	}

	function get_monthly_booking_summary()
	{
		$query = 'select count(distinct(BD.app_reference)) AS total_booking, sum(TD.total_fare+TD.admin_markup+BD.convinence_amount) as monthly_payment, sum(TD.admin_markup+BD.convinence_amount) as monthly_earning, 
		MONTH(BD.created_datetime) as month_number 
		from flight_booking_details AS BD
		join flight_booking_transaction_details as TD on BD.app_reference=TD.app_reference
		where (YEAR(BD.created_datetime) BETWEEN '.date('Y').' AND '.date('Y', strtotime('+1 year')).') AND BD.domain_origin='.get_domain_auth_id().'
		GROUP BY YEAR(BD.created_datetime), MONTH(BD.created_datetime)';
		return $this->db->query($query)->result_array();
	}

	function monthly_search_history($year_start, $year_end)
	{
		$query = 'select count(*) AS total_search, MONTH(created_datetime) as month_number from search_flight_history where
		(YEAR(created_datetime) BETWEEN '.$year_start.' AND '.$year_end.') AND domain_origin='.get_domain_auth_id().' 
		AND search_type="'.META_AIRLINE_COURSE.'"
		GROUP BY YEAR(created_datetime), MONTH(created_datetime)';
		return $this->db->query($query)->result_array();
	}

	function top_search($year_start, $year_end)
	{
		$query = 'select count(*) AS total_search, concat(from_code, "-",to_code) label from search_flight_history where
		(YEAR(created_datetime) BETWEEN '.$year_start.' AND '.$year_end.') AND domain_origin='.get_domain_auth_id().' 
		AND search_type="'.META_AIRLINE_COURSE.'"
		GROUP BY CONCAT(from_code, to_code) order by count(*) desc, created_datetime desc limit 0, 15';
		return $this->db->query($query)->result_array();
	}
	/*
	 * Jaganath
	 * Update the Cancellation Details of the Passenger
	 */
	function update_pax_ticket_cancellation_details($ticket_cancellation_details, $pax_origin)
	{
		//1.Updating Passenger Status
		$booking_status = 'BOOKING_CANCELLED';
		$passenger_update_data = array();
		$passenger_update_data['status'] = $booking_status;
		$passenger_update_condition = array();
		$passenger_update_condition['origin'] = $pax_origin;
		$this->custom_db->update_record('flight_booking_passenger_details', $passenger_update_data, $passenger_update_condition);
		//2.Adding Cancellation Details
		$data = array();
		$cancellation_details = $ticket_cancellation_details['cancellation_details'];
		$data['RequestId'] = $cancellation_details['ChangeRequestId'];
		$data['ChangeRequestStatus'] = $cancellation_details['ChangeRequestStatus'];
		$data['statusDescription'] = $cancellation_details['StatusDescription'];
		$pax_details_exists = $this->custom_db->single_table_records('flight_cancellation_details', '*', array('passenger_fk' => $pax_origin));
		if($pax_details_exists['status'] == true) {
			//Update the Data
			$this->custom_db->update_record('flight_cancellation_details', $data, array('passenger_fk' => $pax_origin));
		} else {
			//Insert Data
			$data['passenger_fk'] = $pax_origin;
			$data['created_by_id'] = intval(@$this->entity_user_id);
			$data['created_datetime'] = date('Y-m-d H:i:s');
			$data['cancellation_requested_on'] = date('Y-m-d H:i:s');
			$this->custom_db->insert_record('flight_cancellation_details', $data);
		}
	}
	/**
	 * Update Flight Booking Transaction Status based on Passenger Ticket status
	 * @param unknown_type $transaction_origin
	 */
	public function update_flight_booking_transaction_cancel_status($transaction_origin)
	{
		$confirmed_passenger_exists = $this->custom_db->single_table_records('flight_booking_passenger_details', '*', array('flight_booking_transaction_details_fk' => $transaction_origin, 'status' => 'BOOKING_CONFIRMED'));
		if($confirmed_passenger_exists['status'] == false){
			//If all passenger cancelled the ticket for that particular transaction, then set the transaction status to  BOOKING_CANCELLED
			$transaction_update_data = array();
			$booking_status = 'BOOKING_CANCELLED';
			$transaction_update_data['status'] = $booking_status;
			$transaction_update_condition = array();
			$transaction_update_condition['origin'] = $transaction_origin;
			$this->custom_db->update_record('flight_booking_transaction_details', $transaction_update_data, $transaction_update_condition);
		}
	}
	/**
	 * Update Flight Booking Transaction Status based on Passenger Ticket status
	 * @param unknown_type $transaction_origin
	 */
	public function update_flight_booking_cancel_status($app_reference)
	{
		$confirmed_passenger_exists = $this->custom_db->single_table_records('flight_booking_passenger_details', '*', array('app_reference' => $app_reference, 'status' => 'BOOKING_CONFIRMED'));
		if($confirmed_passenger_exists['status'] == false){
			//If all passenger cancelled the ticket, then set the booking status to  BOOKING_CANCELLED
			$booking_update_data = array();
			$booking_status = 'BOOKING_CANCELLED';
			$booking_update_data['status'] = $booking_status;
			$booking_update_condition = array();
			$booking_update_condition['app_reference'] = $app_reference;
			$this->custom_db->update_record('flight_booking_details', $booking_update_data, $booking_update_condition);
		}
	}
	
	/**
	 * Check if destination are domestic
	 * @param string $from_loc Unique location code
	 * @param string $to_loc   Unique location code
	 */
	function is_domestic_flight($from_loc, $to_loc)
	{
		if(valid_array($from_loc) == true || valid_array($to_loc)) {//Multicity
			$airport_cities = array_merge($from_loc, $to_loc);
			$airport_cities = array_unique($airport_cities);
			$airport_city_codes = '';
			foreach($airport_cities as $k => $v){
				$airport_city_codes .= '"'.$v.'",';
			}
			$airport_city_codes = rtrim($airport_city_codes, ',');
			$query = 'SELECT count(*) total FROM flight_airport_list WHERE airport_code IN ('.$airport_city_codes.') AND country != "India"';
		} else {//Oneway/RoundWay
			$query = 'SELECT count(*) total FROM flight_airport_list WHERE airport_code IN ('.$this->db->escape($from_loc).','.$this->db->escape($to_loc).') AND country != "India"';
		}
		$data = $this->db->query($query)->row_array();
		if (intval($data['total']) > 0){
			return false;
		} else {
			return true;
		}
	
	}
	
	/**
	 * Sagar Wakchaure
	 * update the pnr details
	 * @param unknown $response
	 * @param unknown $app_reference
	 * @param unknown $booking_source
	 * @param unknown $booking_status
	 * @return string
	 */
	function update_pnr_details($response,$app_reference, $booking_source='',$booking_status=''){
		
		$return_response = FAILURE_STATUS;		
		$booking_details = $this->get_booking_details($app_reference, $booking_source, $booking_status);
		$table_data = $this->booking_data_formatter->format_flight_booking_data($booking_details, 'admin');	
		$booking_transaction_details = $table_data['data']['booking_details'][0]['booking_transaction_details'];
		$update_pnr_array = array();
		$update_itinerary_details = array();
		$update_ticket_info = array();
		
		//update flight_booking_transaction_details table and flight_passenger_ticket_info
		
		if ($booking_details['status'] == SUCCESS_STATUS && $response['status'] == SUCCESS_STATUS) {
			$i=0;
			foreach($booking_transaction_details as $key=>$transaction_detail_sub_data){				
				$update_pnr_array['pnr'] = $response['data']['BoookingTransaction'][$i]['PNR'];
				$update_pnr_array['book_id'] =$response['data']['BoookingTransaction'][$i]['BookingID'];
				$update_pnr_array['status'] =$response['data']['BoookingTransaction'][$i]['Status'];
				$sequence_no = $response['data']['BoookingTransaction'][$i]['SequenceNumber'];
				
				//update flight_booking_transaction_details
				$this->custom_db->update_record('flight_booking_transaction_details', $update_pnr_array, array('app_reference' =>$app_reference,'sequence_number'=>trim($sequence_no)));			  			  
			
				foreach($transaction_detail_sub_data['booking_customer_details'] as $k=>$booking_customer_data){
					$update_ticket_info['TicketId'] = $response['data']['BoookingTransaction'][$i]['BookingCustomer'][$k]['TicketId'];
					$update_ticket_info['TicketNumber'] = $response['data']['BoookingTransaction'][$i]['BookingCustomer'][$k]['TicketNumber'];			   	     
					
					//update flight_passenger_ticket_info
					$this->custom_db->update_record('flight_passenger_ticket_info', $update_ticket_info,array('passenger_fk' => $booking_customer_data['origin']));			    	
				}
				$i++;
				
				//update  status in flight_booking_passenger_details
				$this->custom_db->update_record('flight_booking_passenger_details',array('status'=>$update_pnr_array['status']) ,array('app_reference' => trim($app_reference)));
			}
			
			//update status in flight_booking_details
			if(isset($response['data']['MasterBookingStatus']) && !empty($response['data']['MasterBookingStatus'])){
				
				$this->custom_db->update_record('flight_booking_details', array('status'=>$response['data']['MasterBookingStatus']),array('app_reference' => $app_reference));			
			}
			
			//update flight_booking_itinerary_details table		
			foreach($booking_details['data']['booking_itinerary_details'] as $key=>$transaction_detail_sub_data){
					$update_itinerary_details['airline_pnr'] = $response['data']['BookingItineraryDetails'][$key]['AirlinePNR'];
					$from = $response['data']['BookingItineraryDetails'][$key]['FromAirlineCode'];
					$to = $response['data']['BookingItineraryDetails'][$key]['ToAirlineCode'];
					$departure_datetime = $response['data']['BookingItineraryDetails'][$key]['DepartureDatetime'];
					
					$this->custom_db->update_record('flight_booking_itinerary_details', $update_itinerary_details, 
					array('app_reference' =>$app_reference,'from_airport_code'=>trim($from),'to_airport_code'=>trim($to),'departure_datetime'=>trim($departure_datetime)));
			}
			
			$return_response = SUCCESS_STATUS;
		}
		return $return_response;
	}
	/**
	 Jaganath
	 * Returns Passenger Ticket Details based on the following parameteres
	 * @param $app_reference
	 * @param $passenger_origin
	 * @param $passenger_booking_status
	 */
	function get_passenger_ticket_info($app_reference, $passenger_origin, $passenger_booking_status='')
	{
		$response['status'] = FAILURE_STATUS;
		$response['data'] = array();
		$bd_query = 'select BD.*,DL.domain_name,DL.origin as domain_id,CC.country as domain_base_currency from flight_booking_details AS BD,domain_list AS DL
						join currency_converter CC on CC.id=DL.currency_converter_fk 
						WHERE DL.origin = BD.domain_origin AND BD.app_reference like ' . $this->db->escape ( $app_reference );
		//Customer and Ticket Details
		$cd_query = 'select FBTD.book_id,FBTD.pnr,FBTD.sequence_number,CD.*,FPTI.TicketId,FPTI.TicketNumber,FPTI.IssueDate,FPTI.Fare,FPTI.SegmentAdditionalInfo
						from flight_booking_passenger_details AS CD
						join flight_booking_transaction_details FBTD on CD.flight_booking_transaction_details_fk=FBTD.origin
						left join flight_passenger_ticket_info FPTI on CD.origin=FPTI.passenger_fk
						WHERE CD.app_reference="'.$app_reference.'" and CD.origin='.intval($passenger_origin).' and CD.status="'.$passenger_booking_status.'"';
		//Cancellation Details
		$cancellation_details_query = 'select FCD.*
						from flight_booking_passenger_details AS CD
						left join flight_passenger_ticket_info FPTI on CD.origin=FPTI.passenger_fk
						left join flight_cancellation_details AS FCD ON FCD.passenger_fk=CD.origin
						WHERE CD.app_reference="'.$app_reference.'" and CD.origin='.intval($passenger_origin).' and CD.status="'.$passenger_booking_status.'"';
		$response['data']['booking_details']			= $this->db->query($bd_query)->result_array();
		$response['data']['booking_customer_details']	= $this->db->query($cd_query)->result_array();
		$response['data']['cancellation_details']	= $this->db->query($cancellation_details_query)->result_array();
		if (valid_array($response['data']['booking_details']) == true && valid_array($response['data']['booking_customer_details']) == true) {
			$response['status'] = SUCCESS_STATUS;
		}
		return $response;
	}
	/**
	 * Jaganath
	 * Update Supplier Ticket Refund Details
	 * @param unknown_type $supplier_ticket_refund_details
	 */
	public function update_supplier_ticket_refund_details($passenger_origin, $supplier_ticket_refund_details)
	{
		$update_refund_details = array();
		$supplier_ticket_refund_details = $supplier_ticket_refund_details['RefundDetails'];
		$update_refund_details['ChangeRequestStatus'] = 			$supplier_ticket_refund_details['ChangeRequestStatus'];
		$update_refund_details['statusDescription'] = 				$supplier_ticket_refund_details['StatusDescription'];
		$update_refund_details['API_refund_status'] = 				$supplier_ticket_refund_details['RefundStatus'];
		$update_refund_details['API_RefundedAmount'] = 				floatval($supplier_ticket_refund_details['RefundedAmount']);
		$update_refund_details['API_CancellationCharge'] = 			floatval($supplier_ticket_refund_details['CancellationCharge']);
		$update_refund_details['API_ServiceTaxOnRefundAmount'] =	floatval($supplier_ticket_refund_details['ServiceTaxOnRefundAmount']);
		$update_refund_details['API_SwachhBharatCess'] = 			floatval($supplier_ticket_refund_details['SwachhBharatCess']);
		
		if($supplier_ticket_refund_details['RefundStatus'] == 'PROCESSED') {
			$update_refund_details['cancellation_processed_on'] = date('Y-m-d H:i:s');
		}
		$this->custom_db->update_record('flight_cancellation_details', $update_refund_details, array('passenger_fk' => intval($passenger_origin)));
	}
	function get_booked_user_details($app_reference)
	{
		$query = "select  BD.created_by_id,U.user_type from flight_booking_details as BD join user as U on U.user_id = BD.created_by_id where app_reference = '".$app_reference."'";
		return $this->db->query($query)->result_array();
	}
	/**
	 * Extraservices(Baggage,Meal and Seats) Price
	 * @param unknown_type $app_reference
	 */
	public function get_extra_services_total_price($app_reference)
	{
		$extra_service_total_price = 0;
		
		//get baggage price
		$baggage_total_price = $this->get_baggage_total_price($app_reference);
		
		//get meal price
		$meal_total_price = $this->get_meal_total_price($app_reference);
		
		//get seat price
		$seat_total_price = $this->get_seat_total_price($app_reference);
		
		//Addig all services price
		$extra_service_total_price = round(($baggage_total_price+$meal_total_price+$seat_total_price), 2);
		
		return $extra_service_total_price;
	}
	/**
	 * 
	 * Returns Baggage Total Price
	 * @param unknown_type $app_reference
	 */
	public function get_baggage_total_price($app_reference)
	{
		$query = 'select sum(FBG.price) as baggage_total_price
			from flight_booking_passenger_details FP
			left join flight_booking_baggage_details FBG on FP.origin=FBG.passenger_fk
			where FP.app_reference="'.$app_reference.'" group by FP.app_reference';
		$data = $this->db->query($query)->row_array();
		return floatval(@$data['baggage_total_price']);
	}
	/**
	 * 
	 * Returns Meal Total Price
	 * @param unknown_type $app_reference
	 */
	public function get_meal_total_price($app_reference)
	{
		$query = 'select sum(FML.price) as meal_total_price
			from flight_booking_passenger_details FP
			left join flight_booking_meal_details FML on FP.origin=FML.passenger_fk
			where FP.app_reference="'.$app_reference.'" group by FP.app_reference';
		$data = $this->db->query($query)->row_array();
		
		return floatval(@$data['meal_total_price']);
	}
	/**
	 * 
	 * Returns Seat Total Price
	 * @param unknown_type $app_reference
	 */
	public function get_seat_total_price($app_reference)
	{
		$query = 'select sum(FST.price) as seat_total_price
			from flight_booking_passenger_details FP
			left join flight_booking_seat_details FST on FP.origin=FST.passenger_fk
			where FP.app_reference="'.$app_reference.'" group by FP.app_reference';
		$data = $this->db->query($query)->row_array();
		
		return floatval(@$data['seat_total_price']);
	}
	/**
	 * Extraservices(Baggage,Meal and Seats) Price
	 * @param unknown_type $app_reference
	 */
	public function add_extra_service_price_to_published_fare($app_reference)
	{
		$transaction_data = $this->db->query('select * from flight_booking_transaction_details where app_reference="'.$app_reference.'" order by origin asc')->result_array();
		if(valid_array($transaction_data) == true){
			foreach ($transaction_data as $tr_k => $tr_v){
				$transaction_origin = $tr_v['origin'];
				$extra_service_totla_price = $this->transaction_wise_extra_service_total_price($transaction_origin);
				
				$update_data = array();
				$update_condition = array();
				$update_data['total_fare'] = $tr_v['total_fare']+$extra_service_totla_price;
				$update_condition['origin'] = $transaction_origin;
				$this->custom_db->update_record('flight_booking_transaction_details', $update_data, $update_condition);
			}
		}
	}
	/**
	 * Transaction-wise extra service total price
	 * @param unknown_type $transaction_origin
	 */
	public function transaction_wise_extra_service_total_price($transaction_origin)
	{
		$extra_service_totla_price = 0;
		//Baggage
		$baggage_price = $this->db->query('select sum(FBG.price) as baggage_total_price
											from flight_booking_passenger_details FP
											left join flight_booking_baggage_details FBG on FP.origin=FBG.passenger_fk
											where FP.flight_booking_transaction_details_fk='.$transaction_origin.' group by FP.flight_booking_transaction_details_fk')->row_array();
				
		//Meal
		$meal_price = $this->db->query('select sum(FML.price) as meal_total_price
											from flight_booking_passenger_details FP
											left join flight_booking_meal_details FML on FP.origin=FML.passenger_fk
											where FP.flight_booking_transaction_details_fk='.$transaction_origin.' group by FP.flight_booking_transaction_details_fk')->row_array();
		//Seat
		$seat_price = $this->db->query('select sum(FST.price) as seat_total_price
											from flight_booking_passenger_details FP
											left join flight_booking_seat_details FST on FP.origin=FST.passenger_fk
											where FP.flight_booking_transaction_details_fk='.$transaction_origin.' group by FP.flight_booking_transaction_details_fk')->row_array();
		
		$extra_service_totla_price = floatval(@$baggage_price['baggage_total_price']+@$meal_price['meal_total_price']+@$seat_price['seat_total_price']);
		
		return $extra_service_totla_price;
	}
	/////////Added by Jagannath
	function get_airline_list($search_chars, $search_type = '') {
	 	
		$spl_filter = '';
		if (strcasecmp ( $search_type, 'domestic' ) == 0) {
			$spl_filter .= ' AND is_domestic = "0"';
		}
		$raw_search_chars = $this->db->escape ( $search_chars );
		$r_search_chars = $this->db->escape ( $search_chars . '%' );
		$search_chars = $this->db->escape ( '%' . $search_chars . '%' );
		
		$query = 'Select * from airline_list where (name like ' . $search_chars . '
		OR code like ' . $search_chars .') ORDER BY name DESC
		,
		CASE
			WHEN	name	LIKE	' . $raw_search_chars . '	THEN 1
			WHEN	code	LIKE	' . $raw_search_chars . '	THEN 2
					
			WHEN	name	LIKE	' . $r_search_chars . '	THEN 3
			WHEN	code	LIKE	' . $r_search_chars . '	THEN 4
					
			WHEN	name	LIKE	' . $search_chars . '	THEN 5
			WHEN	code	LIKE	' . $search_chars . '	THEN 6

			ELSE 10 END
		LIMIT 0, 20';
		
		return $this->db->query ( $query )->result_array ();
	} 

	function all_flight_list( $data ){

		$cond = '';
		$flag = 1;
		if(!empty($data['dep_origin']) && isset($data['dep_origin'])){
			$cond .= ' AND origin like '.$this->db->escape('%'.$data['dep_origin'].'%');
			$flag = 0;
		}
		if(!empty($data['arrival_origin']) && isset($data['arrival_origin'])){
			$cond .= ' AND destination like '.$this->db->escape('%'.$data['arrival_origin'].'%');
			$flag = 0;
		}

		if(!empty($data['month']) && isset($data['month'])){
			$cond .= ' AND MONTH(dep_from_date) like '.$data['month']; 
			$cond .= ' AND MONTH(dep_to_date) like '.$data['month']; 
			$flag = 0;
		}

		if(!empty($data['year']) && isset($data['year'])){
			$cond .= ' AND YEAR(dep_from_date) like '.$data['year'];
			$cond .= ' AND YEAR(dep_to_date) like '.$data['year'];
			$flag = 0;
		}

		if($flag){
			$cond .= ' AND MONTH(dep_from_date) like '.date('m'); 
			$cond .= ' AND MONTH(dep_to_date) like '.date('m'); 


			$cond .= ' AND YEAR(dep_from_date) like '.date('Y');
			$cond .= ' AND YEAR(dep_to_date) like '.date('Y');
		}
// debug($cond);exit();
		$query = "SELECT * FROM flight_crs_segment_details where (active='1' or active='0') ". $cond." ORDER BY origin,destination,flight_num,carrier_code,class_type desc";
	//debug($query); exit;
		return $this->db->query ( $query )->result_array ();
	}
	function flight_list(){


// debug($cond);exit();
		$query = "SELECT * FROM flight_crs_segment_details where (active='1' or active='0')  ORDER BY origin,destination,flight_num,carrier_code,class_type desc";
	//debug($query); exit;
		return $this->db->query ( $query )->result_array ();
	}

	function get_booking_count(){
		$query = 'SELECT DISTINCT(fsid) FROM flight_crs_booking_details';
		return $this->db->query($query)->result_array();
	}

	/*function get_booking_count{
		$query = 'SELECT DISTINCT(fsid) FROM flight_crs_booking_details';
		return $this->db->query ( $query )->result_array ();
	}*/
	function flight_details( $fsid ){
		// $query = 'SELECT *,DATE_FORMAT(departure_from_date, "%d-%m-%Y") AS `departure_date_from`,DATE_FORMAT(departure_to_date, "%d-%m-%Y") AS `departure_date_to`,DATE_FORMAT(departure_time, "%H:%i") AS `departure_time`,DATE_FORMAT(arrival_time, "%H:%i") AS `arrival_time` FROM `flight_crs_details` WHERE `fsid`='.$fsid.' ORDER BY fdid, trip_type';

		$this->db->select('*')
     ->from('flight_crs_details')
     ->join('flight_crs_segment_details', 'flight_crs_segment_details.fsid = flight_crs_details.fsid')
     ->where('flight_crs_details.fsid', $fsid);
    return  $query = $this->db->get()->result_array();
      //debug($this->db->last_query());exit();

		


	}
	function flight_status($fsid,$status){
		$data = array ();
		$data ['active'] = $status;
		$condition ['fsid'] = $fsid;
		$this->custom_db->update_record ( 'flight_crs_segment_details', $data,$condition);
	}

	function update_per_date_flight_status($origin,$status,$status_type){
		$data = array ();
		$data [$status_type.'_status'] = $status;
		$condition ['origin'] = $origin;
		$this->custom_db->update_record ( 'crs_update_flight_details', $data,$condition);
	}

	function update_flight_data_per_date($data){
		error_reporting(0);
		$update_data = array();
		$update_data = $data;
		//debug($update_data);exit();
		$condition = array();
		$condition['origin'] = $update_data['origin'];

		unset($update_data['origin']);
		$res = $this->custom_db->update_record ( 'crs_update_flight_details', $update_data,$condition);
	
		//debug($this->db->last_query()); exit;
		return true;
	}

	

	/**
	 * Check PNR empty or not for flight crs
	 */
	function check_pnr_flight_crs($origin)
	{
		$cond=array(
				"origin" => $origin
		);
		$var = $GLOBALS['CI']->custom_db->single_table_records('crs_update_flight_details','*',$cond);

		if(valid_array($var['data']) && !empty($var['data'][0]['pnr']) ){
			return 1;
		}else{
			return 0;
		}
		//return $var['pnr'];
	}

	// Jagannath (update_flight_details)
	function update_flight_details($id)
	{
		$query ="SELECT * FROM `flight_crs_segment_details` where fsid = $id";
		//echo $query;
		$result = $this->db->query($query)->result_array();
		return $result;
	}

	function initial_update_flight_details ($id,$data)
	{
		$data = $data['flight_details'][0];
		$con['origin'] = $data['origin'];
		$con['destination'] = $data['destination'];
		$con['flight_num'] = $data['flight_num'];
		$con['carrier_code'] = $data['carrier_code'];
		$con['class_type'] = $data['class_type'];
		$con['fsid'] = $id;
		$fs_res = $this->custom_db->single_table_records('flight_crs_segment_details','*',$con);
		
	/*	$query ="SELECT * FROM `flight_crs_segment_details` where fsid = $id";
		
		$result = $this->db->query($query)->result_array();*/
		
		$fsid_list = array();
		foreach ($fs_res['data'] as $k => $d){ 
			$fsid_list[] = $d['fsid'];
			$condition = array();
			$condition['fsid'] = $d['fsid'];
			$fs_flight_res = $this->custom_db->single_table_records('crs_update_flight_details','*',$condition);
		//	$query = $this->db->query("SELECT * FROM crs_update_flight_details where fsid = ".$d['fsid']);
		//	$count = $query->num_rows();
			//debug($fs_flight_res); exit;
			if($fs_flight_res['status'] >0)
			{
	
			}
			else
			{  
		     	$update_details = $d;
				$dep_from_date = $update_details['dep_from_date'];
				$dep_to_date = $update_details['dep_to_date'];
				$begin = new DateTime($dep_from_date);
				$end = new DateTime($dep_to_date);
				$end = $end->modify( '+1 day' );
	
				$interval = new DateInterval('P1D');
				$daterange = new DatePeriod($begin, $interval ,$end);
				foreach($daterange as $date){
					// $date1 = $date->format('d-M-Y');
					$query = "INSERT into crs_update_flight_details (fsid, avail_date) 
					values('".$update_details['fsid']."','".$date->format('Y-m-d')."')";
					// echo $query;
					$this->db->query($query);
				}
			}
		}
		$return_data['fsid_list'] = $fsid_list;		
		return $return_data;
	}

	function get_airport_list($search_chars, $search_type = '') {
		/*$spl_filter = '';
		if (strcasecmp ( $search_type, 'domestic' ) == 0) {
			$spl_filter .= ' AND country = "india"';
		}*/
		$raw_search_chars = $this->db->escape ( $search_chars );
		$r_search_chars = $this->db->escape ( $search_chars . '%' );
		$search_chars = $this->db->escape ( '%' . $search_chars . '%' );
		
		$query = 'Select * from flight_crs_airport_list where (airport_name like ' . $search_chars . '
		OR airport_code like ' . $search_chars . ') 
		
		LIMIT 0, 20';
		// debug($query);exit();
		/*,
		CASE
			WHEN	airport_code	LIKE	' . $raw_search_chars . '	THEN 1
			WHEN	airport_name	LIKE	' . $raw_search_chars . '	THEN 2
			

			WHEN	airport_code	LIKE	' . $r_search_chars . '	THEN 4
			WHEN	airport_name	LIKE	' . $r_search_chars . '	THEN 5
			

			WHEN	airport_code	LIKE	' . $search_chars . '	THEN 7
			WHEN	airport_name	LIKE	' . $search_chars . '	THEN 8
			
			ELSE 10 END*/
		
		return $this->db->query ( $query );
	}
	function get_crs_airline_list($search_chars,$search_type=""){
		$raw_search_chars = $this->db->escape ( $search_chars );
		$r_search_chars = $this->db->escape ( $search_chars . '%' );
		$search_chars = $this->db->escape ( '%' . $search_chars . '%' );
		
		$query = 'Select * from flight_crs_airline_list where (airline_name like ' . $search_chars . '
		OR airline_code like ' . $search_chars . ') 
		
		LIMIT 0, 20';
		return $this->db->query ( $query );

	}
	
	function save_update_flight_details($post_params)
	{
		// echo "<pre>";
		// print_r($post_params);
		// exit;
		$fsid = $post_params['fsid1'];
		// echo $fsid;
		
		$query1 = $this->db->query("select * from crs_update_flight_details where fsid=$fsid");
		//echo "select * from crs_update_flight_details where fsid=$fsid";
		$rows = $query1->num_rows();
		if($rows>0)
		{
			$query2 = $this->db->query("delete from crs_update_flight_details where fsid = $fsid");
			//echo "delete from crs_update_flight_details where fsid = $fsid";
		}
		$count = count($post_params['fsid']);
		//echo $count;

		// 		$flight_segment_details['departure_time'] 	= date('H:i',strtotime($data['departure_time'][0]));
		for($i=0; $i<$count; $i++)
		{
			$query = "INSERT into crs_update_flight_details (fsid, avail_date, pnr, avail_seat, adult_base, adult_tax, child_base, child_tax) values('".$post_params['fsid'][$i]."','".$post_params['date'][$i]."','".$post_params['pnr'][$i]."','".$post_params['seat'][$i]."','".$post_params['adult_base'][$i]."','".$post_params['adult_tax'][$i]."','".$post_params['child_base'][$i]."','".$post_params['child_tax'][$i]."'	)";
			//echo $query;
			$this->db->query($query);
		}
		return true;

	}
	function crs_update_flight_details($fsid_list,$filter_data=array())
	{
		
		$condition = '';
// 		if(isset($filter_data['month']) and !empty($filter_data['month'])){
// 			$condition .= ' AND month(avail_date) = '.$filter_data['month'].'';
// 		}
// 		if(isset($filter_data['year']) and !empty($filter_data['year'])){
// 			$condition .= ' AND year(avail_date) = '.$filter_data['year'].'';
// 		}else{
// 			$condition .= ' AND year(avail_date) = year(curdate())';
// 		}

		$query ="SELECT * FROM `crs_update_flight_details` where fsid in(".$fsid_list.") ".$condition ." order by avail_date";
// 	debug($query); exit;
		return $this->db->query($query)->result_array();
	}
	function delete_update_flight_details($id)
	{

		$query = "delete from `crs_update_flight_details` where origin = $id";
		
		$this->db->query($query);	
		return true;
	}

	function update_seats_details($id,$seat,$pnr,$abasefare,$atax,$ibasefare,$itax)
	{
		$query = "update `flight_crs_segment_details` SET seats = $seat, pnr = '$pnr', adult_basefare = $abasefare, adult_tax = $atax, infant_basefare = $ibasefare, infant_tax = $itax  where fsid = $id";
		// echo $query; exit;
		$this->db->query($query);	
		return true;
	}


	  /*
     * Upload Offline Booking information on database
     */

    function offline_flight_book_new($flight_data, $app_reference) {
    	error_reporting(0);
        //debug($flight_data);exit;
        /* booking details */
        $onward_ticket = array();
    	$return_ticket = array();
        foreach($flight_data['pax_ticket_num_onward'] as $k => $v){
        	if(!empty($v)){
        		$onward_ticket[] = $v; 
        	}
        }
        $flight_data['pax_ticket_num_onward'] = $onward_ticket;
        if(valid_array($flight_data['pax_ticket_num_return'])){
        	 foreach($flight_data['pax_ticket_num_return'] as $k => $v){
	        	if(!empty($v)){
	        		$return_ticket[] = $v; 
	        	}
	        }

	        if($flight_data['is_lcc']=='lcc'){

	        	$flight_data['pax_ticket_num_return'] = $flight_data['airline_pnr_return'];
	        	//$flight_data['pax_ticket_num_onward'] = $flight_data['airline_pnr_onward'];
	        }

        }
       
        if(valid_array($return_ticket)){
        	$flight_data['pax_ticket_num_return'] = $return_ticket;
        }
        if($flight_data['is_lcc']=='lcc'){

        	$flight_data['pax_ticket_num_onward'] = $flight_data['airline_pnr_onward'];
        	//$flight_data['pax_ticket_num_onward'] = $flight_data['airline_pnr_onward'];
        }

        $status = empty($flight_data['status']) ? 'BOOKING_PENDING' : $flight_data['status'];

        $agent_id = $flight_data['agent_id'];


        if ($flight_data['booking_type'] == "international") {
            $ModuleType = "flight_int";
        } else {
            $ModuleType = "flight";
        }

        $domain_details = $this->domain_management_model->b2c_b2b_domain_list_ajax($flight_data['agent_id']);
       
        // debug($domain_details);exit;

        /* Hided By DINI Starts */
        //$cm = $this->domain_management_model->get_commission_details($domain_details['origin']);
       		
        //$cm = $this->get_master_commission_details($ModuleType);

        /* Hided By DINI Ends */
		
        $airline = $this->db_cache_api->get_airline_list($from = array('k' => 'code', 'v' => 'name'));

        $first_flight = 0;
        $last_flight = ($flight_data ['sect_num_onward'] - 1);
        $trp = 'onward';
        $trp1 = $flight_data['trip_type'] == 'circle' ? 'return' : 'onward';
        $trp_last = $flight_data['trip_type'] == 'circle' ? ($flight_data ['sect_num_return'] - 1) : $last_flight;
        /*$booking_details['domain_origin'] = $domain_details['origin'];
        $booking_details['currency'] = $domain_details['domain_base_currency'];*/
        $booking_details['domain_origin'] = 1;
        $booking_details['currency'] = 'INR';


        $booking_details['app_reference'] = $app_reference;
        $booking_details['trip_type'] = $flight_data['trip_type'];
        $booking_details['booking_source'] = $flight_data['suplier_id'];
        $booking_details['is_lcc'] = $flight_data ['is_lcc'] != 'gds' ? 1 : 0;
        $booking_details['phone'] = $flight_data ['passenger_phone'];
        $booking_details['alternate_number'] = $flight_data ['passenger_phone'];
        $booking_details['email'] = $flight_data ['passenger_email'];
        $booking_details['journey_start'] = db_current_datetime(trim($flight_data['dep_date_onward'][$first_flight] . ' ' . $flight_data['dep_time_onward'][$first_flight]));
        $booking_details['journey_end'] = db_current_datetime(trim($flight_data ['arr_date_' . $trp1][$trp_last] . ' ' . $flight_data['arr_time_' . $trp1][$trp_last]));
        $booking_details['journey_from'] = strtoupper($flight_data ['dep_loc_onward'][$first_flight]);
        $booking_details['journey_to'] = strtoupper($flight_data ['arr_loc_onward'][$last_flight]);
        $booking_details['from_loc'] = strtoupper($flight_data ['dep_loc_onward'][$first_flight]);
        $booking_details['to_loc'] = strtoupper($flight_data ['arr_loc_onward'][$last_flight]);
        $booking_details['payment_mode'] = 'PNHB1';
        $booking_details['attributes'] = '';
        $booking_details['created_by_id'] = $agent_id;
        $booking_details['created_datetime'] = date('Y-m-d H:i:s');
        
        /*Hided By DINI Starts*/
        //$booking_details['offline_supplier_name'] = @$flight_data['suplier_name'];
        /*Hided By DINI Ends*/

        $book_id = $this->custom_db->insert_record('flight_booking_details', $booking_details);
        $book_id = @$book_id['insert_id'];

        $pax_fare = array();
        $c = 0;
        
        foreach ($flight_data['pax_total_fare'] as $fk => $fv) {
            $pax_fare['onward']['basic'] = $fv;

            $pax_fare['onward']['yq'] = 0;

            $pax_fare['onward']['others'] = 0;

            if ($flight_data['trip_type'] == 'circle' && isset($flight_data['pax_basic_fare_return'][$fk])) {
                $pax_fare['return']['basic'] = $fv;

                $pax_fare['return']['yq'] = 0;

                $pax_fare['return']['others'] = 0;
            }
        }
        
        $c_on = strtoupper(@$flight_data['career_onward'][0]);
        $t[$c_on][0]['career'] = @$flight_data['career_onward'];
        $t[$c_on][0]['sect_num'] = @$flight_data['sect_num_onward'];
        $t[$c_on][0]['pax_count'] = array_sum($flight_data['pax_total_fare']);

        $f[$c_on]['basic'] = $flight_data['agent_buying_price'];
        $f[$c_on]['yq'] = 0;
        $f[$c_on]['others'] = 0;
        if ($flight_data['trip_type'] == 'circle' && valid_array(@$flight_data['career_return'])) {
            $c_rt = strtoupper(@$flight_data['career_return'][0]);
            $t[$c_rt][1]['career'] = @$flight_data['career_return'];
            $t[$c_rt][1]['sect_num'] = @$flight_data['sect_num_return'];
            $t[$c_rt][1]['pax_count'] = array_sum($flight_data['pax_total_fare']);

            $f[$c_rt]['basic'] = $flight_data['agent_buying_price'];
            $f[$c_rt]['yq'] = 0;
            $f[$c_rt]['others'] = 0;
        }
        // debug($t);exit;
        //foreach($t as $tk => $tv ){

        $api_total_display_fare = 0;
        $api_total_tax = 0;
        $api_total_fare = 0;
        $meal_and_baggage_fare = 0;
        $other_fare = 0;
        $basic_fare = 0;
        $fuel_charge = 0;
        $handling_charge = 0;
        $api_service_tax = 0;
        $agent_commission = 0;
        $api_agent_tds_on_commision = 0;
        $dist_commission = 0;
        $api_dist_tds_on_commision = 0;
        $admin_commission = 0;
        $admin_tds_on_commission = 0;
        $agent_markup = 0;
        $admin_markup = 0;
        $dist_markup = 0;
        $app_user_buying_price = 0;


        $price1 = array();
        $tot_agent_buying_price = 0;
        // debug($t);exit;
        foreach ($t as $tk => $tv) {
           
            $trpc = $tk = strtoupper($tk);


            // $hc = $flight_data['hc_comm'];
            $hc = 0;
            // $cm['tds_tax_details']['tds'] = 0;
            // $cm['tds_tax_details']['service_tax'] = 0;


            if(@$flight_data['admin_markup'] > 0){
                $service_tax = @$flight_data['admin_markup'] * 18/100;
            }
            else{
                $service_tax = 0;   
            }
            if($flight_data['trip_type'] == 'oneway'){
                $service_tax = $service_tax;
            }
            else{
               $service_tax = $service_tax / 2;
            }
            if($flight_data['trip_type'] == 'oneway'){
                $agent_comm = $flight_data['basic_comm'];
            }
            else{
                $agent_comm = $flight_data['basic_comm']; 
                $agent_comm = $agent_comm / 2;
            }
            if($flight_data['trip_type'] == 'oneway'){
                 $agent_tds_on_commission = $flight_data['basic_comm'] * 5 / 100;
            }
            else{
                $agent_tds_on_commission = $flight_data['basic_comm'] * 5 / 100;
                $agent_tds_on_commission = $agent_tds_on_commission / 2;
            }
           
            $dist_comm = 0;
            $dist_tds_on_commission = 0;
            $total = $f[$trpc]['basic'];
            $tot_markup = ( @$flight_data['agent_markup'] + @$flight_data['admin_markup'] );
            // $buying_price = $total + $hc + $service_tax + $tot_markup;
            $agent_buying_price = $f[$trpc]['basic'] + $agent_tds_on_commission + $f[$trpc]['others'] + $service_tax - $agent_comm;
            $buying_price = $f[$trpc]['basic'] + $f[$trpc]['others'] + $service_tax;
            // $agent_buying_price = $buying_price - $agent_comm + $agent_tds_on_commission - (@$flight_data['agent_markup']);

            $tot_agent_buying_price += $agent_buying_price;
            $price['api_total_display_fare'] = $buying_price;
            $price['total_breakup'] = array(
                //'api_total_tax'=> $f[$trpc]['others'] + $f[$trpc]['yq'] + $hc + $service_tax + $tot_markup,
                'api_total_tax' => $f[$trpc]['others'] + $f[$trpc]['yq'],
                'api_total_fare' => $f[$trpc]['basic'],
                'meal_and_baggage_fare' => 0
            );
            $price['price_breakup'] = array(
                'other_fare' => $f[$trpc]['others'] + $hc + $service_tax + $tot_markup,
                'basic_fare' => $f[$trpc]['basic'],
                'fuel_charge' => $f[$trpc]['yq'],
                'handling_charge' => $hc,
                'service_tax' => $service_tax,
                'meal_and_baggage_fare' => 0,
                'agent_commission' => $agent_comm,
                'agent_tds_on_commision' => $agent_tds_on_commission,
                'dist_commission' => $dist_comm,
                'dist_tds_on_commision' => $dist_tds_on_commission,
                'admin_commission' => 0,
                'admin_tds_on_commission' => 0,
                'agent_markup' => @$flight_data['agent_markup'],
                'admin_markup' => @$flight_data['admin_markup'],
                'dist_markup' => 0,
                'app_user_buying_price' => $agent_buying_price
            );



            $api_total_display_fare += $buying_price;
            //	$api_total_tax += $f[$trpc]['others'] + $f[$trpc]['yq'] + $hc + $service_tax + $tot_markup;
            $api_total_tax += $f[$trpc]['others'] + $f[$trpc]['yq'];
            $api_total_fare += $f[$trpc]['basic'];
            $meal_and_baggage_fare += 0;
            $other_fare += $f[$trpc]['others'];
            $basic_fare += $f[$trpc]['basic'];
            $fuel_charge += $f[$trpc]['yq'];
            $handling_charge += $hc;
            $api_service_tax += $service_tax;
            $agent_commission += $agent_comm;
            $api_agent_tds_on_commision += $agent_tds_on_commission;
            $dist_commission += $dist_comm;
            $api_dist_tds_on_commision += $dist_tds_on_commission;
            $admin_commission += 0;
            $admin_tds_on_commission += 0;
            $agent_markup += @$flight_data['agent_markup'];
            $admin_markup += @$flight_data['admin_markup'];
            $dist_markup += 0;
            $app_user_buying_price += $agent_buying_price;



            
            $transaction_details['app_reference'] = $app_reference;
            $transaction_details['source'] = $flight_data['suplier_id'];
            $transaction_details['pnr'] = strtoupper(!$booking_details['is_lcc'] ? $flight_data['gds_pnr_' . $trp][$first_flight] : $flight_data['airline_pnr_' . $trp][$first_flight]);
            $transaction_details['status'] = $status;
            $transaction_details['status_description'] = 'In Payment';
            $transaction_details['book_id'] = $book_id;

            /*Hided By DINI*/
           // $transaction_details['booking_source'] = $flight_data['suplier_id'];
            // if(isset($tv[0]['pax_count'])){
            //     $transaction_details['domain_markup'] = 0;

            // }
            // else if(isset($tv[1]['pax_count'])){
            //    $transaction_details['domain_markup'] = 0;

            // }
            /**/

            $transaction_details['ref_id'] = '';
            //$transaction_details['total_fare'] = $buying_price;
            $transaction_details['admin_commission'] = 0;
            $transaction_details['agent_commission'] = $agent_comm;
            $transaction_details['agent_tds'] = $agent_tds_on_commission;
            $transaction_details['admin_markup'] =  $flight_data['markup'];
            $transaction_details['total_fare'] =  $flight_data['purchase_price'];
            $transaction_details['attributes'] = '';
            $transaction_details['sequence_number'] = '';
           
            $flg = $this->custom_db->insert_record('flight_booking_transaction_details', $transaction_details);
            $flight_booking_transaction_details_fk = @$flg['insert_id'];
            foreach ($tv as $sk => $sv) {
                
                

                foreach ($sv['career'] as $ik => $iv) {
                    // debug($sv);
                    $segment_indicator = $ik+1;
                    //$ik = strtoupper($ik);
                    $from_airport_name = $this->db_cache_api->get_airport_city_name_new(array(
                        'airport_code' => $flight_data['dep_loc_' . $trp][$ik]
                            ));

                    $to_airport_name = $this->db_cache_api->get_airport_city_name_new(array(
                        'airport_code' => $flight_data['arr_loc_' . $trp][$ik]
                            ));
                    //$itenery_details['booking_source'] = $flight_data['suplier_id'];
                    $itenery_details['flight_booking_transaction_details_fk'] = $flight_booking_transaction_details_fk;
                    $itenery_details['app_reference'] = $app_reference;
                    $itenery_details['airline_pnr'] = strtoupper($flight_data['airline_pnr_' . $trp][$ik]);
                    $itenery_details['segment_indicator'] = $segment_indicator;
                    $itenery_details['airline_code'] = strtoupper($flight_data['career_' . $trp][$ik]);
                    $itenery_details['airline_name'] = isset($airline[strtoupper($flight_data['career_' . $trp][$ik])]) ? $airline[strtoupper($flight_data['career_' . $trp][$ik])] : strtoupper($flight_data['career_' . $trp][$ik]);
                    $itenery_details['flight_number'] = $flight_data['flight_num_' . $trp][$ik];
                    $itenery_details['fare_class'] = strtoupper($flight_data['booking_class_' . $trp][$ik]);
                    $itenery_details['from_airport_code'] = $from_airport_name->airport_code;
                    $itenery_details['from_airport_name'] = $from_airport_name->airport_name;
                    $itenery_details['to_airport_code'] = $to_airport_name->airport_code;
                    $itenery_details['to_airport_name'] = $to_airport_name->airport_name;
                    $itenery_details['departure_datetime'] = db_current_datetime(trim($flight_data['dep_date_' . $trp][$ik] . ' ' . $flight_data['dep_time_' . $trp][$ik]));
                    $itenery_details['arrival_datetime'] = db_current_datetime(trim($flight_data['arr_date_' . $trp][$ik] . ' ' . $flight_data['arr_time_' . $trp][$ik]));
                    $itenery_details['status'] = $status;
                    $itenery_details['operating_carrier'] = strtoupper($flight_data['career_' . $trp][$ik]);
                    $itenery_details['attributes'] = '';

                    $flg = $this->custom_db->insert_record('flight_booking_itinerary_details', $itenery_details);
                    // debug($itenery_details);
                }
                	$passenger_fk = array();

                foreach ($flight_data['pax_title'] as $pk => $pv) {

                    $customer_details['app_reference'] = $app_reference;
                    //$customer_details['pax_index'] = $pk;
                    //$customer_details['segment_indicator'] = $segment_indicator;
                    //$customer_details['passenger_type'] = $flight_data['pax_type'][$pk];
                    $customer_details['flight_booking_transaction_details_fk'] = $flight_booking_transaction_details_fk;
                    $customer_details['is_lead'] = ($pk == 0) ? 1 : 0;
                    $customer_details['title'] = $pv;
                    $customer_details['first_name'] = $flight_data['pax_first_name'][$pk];
                    $customer_details['middle_name'] = '';
                    $customer_details['last_name'] = $flight_data['pax_last_name'][$pk];
                    $customer_details['date_of_birth'] = '0000-00-00';
                    if ($pv == 1 || $pv == 4) {
                        $customer_details['gender'] = 'Male';
                    } else {
                        $customer_details['gender'] = 'Female';
                    }
                    $customer_details['passenger_nationality'] = 'IN';
                    $customer_details['passport_number'] = $flight_data['pax_passport_num'][$pk];
                    $customer_details['passport_issuing_country'] = '';
                    $customer_details['passport_expiry_date'] = db_current_datetime($flight_data['pax_pp_expiry'][$pk]);
                    //$customer_details['ff_no'] = $flight_data['pax_ff_num'][$pk];
                     if($flight_data['is_lcc']=='lcc'){

			        	$customer_details['ticket_no'] = strtoupper($flight_data['airline_pnr_'.$trp][0]);
			        }else{
			        	$customer_details['ticket_no'] = strtoupper($flight_data['pax_ticket_num_'.$trp][$pk]);
			        }

                    
                    $customer_details['status'] = $status;
                    $k = 0;
                    if ($flight_data['pax_type'][$pk] == 'Child') {
                        $k == 1;
                    } else if ($flight_data['pax_type'][$pk] == 'Infant') {
                        $k = 2;
                    }

                    $pax_basic = $flight_data['pax_total_fare'][$pk];
                    $pax_yq = 0;
                    $pax_other = 0;
                    $pax_total = $flight_data['pax_total_fare'][$pk];;

                    $attr['price_breakup'] = array('base_price' => $pax_basic, "yq" => $pax_yq, 'tax' => $pax_other, 'total_price' => $pax_total);
                    $customer_details['attributes'] = json_encode($attr);
                  
                    $flg = $this->custom_db->insert_record('flight_booking_passenger_details', $customer_details);
                    $passenger_fk[] = @$flg['insert_id'];

                    $pax_ticket_num_onward = $flight_data['pax_ticket_num_onward'];
                    $pax_basic_fare_onward = $flight_data['pax_total_fare'];
                    $pax_other_tax_onward  = array();
                    $pax_total_fare_onward = $flight_data['pax_total_fare'];
                    $pax_type_count_onward = $flight_data['pax_total_fare'];

                    $pax_ticket_num_return = $flight_data['pax_ticket_num_return'];
                    $pax_basic_fare_return = $flight_data['pax_total_fare'];
                    $pax_other_tax_return  = array();
                    $pax_total_fare_return = $flight_data['pax_total_fare'];
                    $pax_type_count_return = $flight_data['pax_total_fare'];
                    # Storing Ticket Details on flight_passenger_ticket_info
                
                }
                // debug($flight_data);
                 if($trp == 'onward'){
                    foreach ($pax_ticket_num_onward as $key => $value) {
                        if(@$flight_data['pax_type'][$key] == 'Adult'){
                            $basic_fare = $flight_data['pax_total_fare'][$key];
                            $tax = 0;
                            $total_price = $flight_data['pax_total_fare'][$key];
                        }
                        else if(@$flight_data['pax_type'][$key] == 'Child'){
                            $basic_fare = $flight_data['pax_total_fare'][$key];
                            $tax = 0;
                            $total_price = $flight_data['pax_total_fare'][$key];
                        }
                        else{
                            $basic_fare = $flight_data['pax_total_fare'][$key];
                            $tax = 0;
                            $total_price = $flight_data['pax_total_fare'][$key];
                        }

                        $data['TicketNumber'] = $value;
                        $data['passenger_fk'] = @$passenger_fk[$key];
                        $attr = array('BasePrice' => $basic_fare, 'Tax' => $tax, 'TotalPrice' => $total_price);
                      
                        $data['Fare'] = json_encode($attr);
                           // debug($data);
                        $insert_id = $this->custom_db->insert_record('flight_passenger_ticket_info', $data);
                       // return false;
                    }
                }
                if($trp == 'return'){
                    foreach ($pax_ticket_num_return as $key => $value) {
                             if($flight_data['pax_type'][$key] == 'Adult'){
                                $basic_fare = $flight_data['pax_total_fare'][$key];
                                $tax = 0;
                                $total_price = $flight_data['pax_total_fare'][$key];
                            }
                            else if($flight_data['pax_type'][$key] == 'Child'){
                                $basic_fare = $flight_data['pax_total_fare'][$key];
                                $tax = 0;
                                $total_price = $flight_data['pax_total_fare'][$key];
                            }
                            else{
                                $basic_fare = $flight_data['pax_total_fare'][$key];
                                $tax = 0;
                                $total_price = $flight_data['pax_total_fare'][$key];
                            }

                            $data['TicketNumber'] = $value;
                            $data['passenger_fk'] = $passenger_fk[$key];
                            $attr = array('BasePrice' => $basic_fare, 'Tax' => $tax, 'TotalPrice' => $total_price);
                            
                            $data['Fare'] = json_encode($attr);
                            // debug($data);
                            $insert_id = $this->custom_db->insert_record('flight_passenger_ticket_info', $data);
                           // return false;
                    }

                }
                
                $trp = 'return';
            }
        }

        /* $price1['api_total_display_fare'] = $api_total_display_fare;
          $price1['total_breakup'] = array(
          'api_total_tax'=> $api_total_tax,
          'api_total_fare'=> $api_total_fare,
          'meal_and_baggage_fare'=>$meal_and_baggage_fare
          );
          $price1['price_breakup'] = array(
          'other_fare'=> $other_fare,
          'basic_fare'=> $basic_fare,
          'fuel_charge'=> $fuel_charge,
          'handling_charge'=>$handling_charge,
          'service_tax' => $api_service_tax,
          'meal_and_baggage_fare'=>$meal_and_baggage_fare,
          'agent_commission' =>$agent_commission,
          'agent_tds_on_commision'=>$api_agent_tds_on_commision,
          'dist_commission' => $dist_commission,
          'dist_tds_on_commision' =>$api_dist_tds_on_commision,
          'admin_commission'=>$admin_commission,
          'admin_tds_on_commission'=>$admin_tds_on_commission,
          'agent_markup'=>$agent_markup,
          'admin_markup'=>$admin_markup,
          'dist_markup' =>$dist_markup,
          'app_user_buying_price'=>$app_user_buying_price
          );

          $up['total_price_attributes'] = json_encode($price1);
          $this->db->update( 'flight_booking_details', $up,array('app_reference'=>$app_reference) );
          debug($price1);
          exit;
         */

// exit;

        $this->load->model('domain_management_model');
        
        if (@$flight_booking_transaction_details_fk > 0 && $tot_agent_buying_price > 0) {



            $this->b2c_and_b2b_deduct_flight_booking_amount($app_reference, $domain_details[0]['user_id'],$domain_details[0]['user_type']);

            /* 	$this->domain_management_model->modify_user_balance ('b2b',$agent_id, -$tot_agent_buying_price);
              $this->domain_management_model->update_transaction_payment_status('flight', $app_reference, 'paid', $tot_agent_buying_price);
              $this->domain_management_model->save_transaction_details ( 'flight', $app_reference, -$tot_agent_buying_price, 0, 0, 'Ticket Booked Offline', $agent_id, false );
              $pnr = $flight_data['airline_pnr_onward'][0];
              if(!empty($pnr) && $status == 'BOOKING_CONFIRMED'){
              $air = $flight_data['career_onward'][0]. ' '.$booking_details['from_loc']. ' to '.$booking_details['to_loc'].' '.$flight_data['flight_num_onword'][0].' at '. date('H:i Y-m-d', strtotime($booking_details['journey_start']));
              $pax = $flight_data['first_name'][0].' '.$flight_data['last_name'][0];
              $msg = 'Ref No.: '.$app_reference.' is confirmed. Air: '.$air.', PNR: '.$pnr.', Pax: '.$pax;
              $mobile = $booking_details['phone'];
              $user_id = $booking_details['created_by_id'];

              if(!empty($mobile)){

              send_sms($msg,$user_id,$app_reference, $mobile);
              }
              send_sms($msg,$user_id,$app_reference);
              } */
        }

        $this->domain_management_model->create_track_log($app_reference, 'Offline Booking - Flight');
        //debug($flight_data);
    }

    /**
     * get agent commission details
     */
    function get_master_commission_details($ModuleType = '') {
        $ci = &get_instance();
        if ($ModuleType != '') {
            $tds_sql = 'SELECT * FROM `commission_master` where module_type="' . $ModuleType . '"';
        } else {
            $tds_sql = 'SELECT * FROM `commission_master` ';
        }

        $tds_result = $ci->db->query($tds_sql)->row_array();
        return array('tds_tax_details' => $tds_result);
    }


	   function offline_flight_book($flight_data, $app_reference) {
	   /*	 $app_reference = 'FB0702201819002935';
	   	 $flight_data['agent_id'] = '89';
	   	 $domain_details = $this->domain_management_model->b2c_b2b_domain_list_ajax($flight_data['agent_id']);

	   	 $this->b2c_and_b2b_deduct_flight_booking_amount($app_reference, $domain_details[0]['user_id'],$domain_details[0]['user_type']);
	 debug("b2c_and_b2b_deduct_flight_booking_amount"); exit;*/
	 
		$booked_seat_new = $flight_data['booked_seat_new'];
		$fdid_for_seat_update = $flight_data['fdid'];
		$fsid_for_seat_update = $flight_data['fsid'];
		
        /* booking details */
        $status = empty($flight_data['status']) ? 'BOOKING_PENDING' : $flight_data['status'];
        $agent_id = $flight_data['agent_id'];

        if ($flight_data['booking_type'] == "international") {
            $ModuleType = "flight_int";
        } else {
            $ModuleType = "flight";
        }
        //$domain_details = $this->domain_management_model->get_domain_details_domain_id($flight_data['agent_id']);
        $this->load->model('domain_management_model');
		$domain_details = $this->domain_management_model->b2c_b2b_domain_list_ajax($flight_data['agent_id']);

		//debug($domain_details); exit;
		//$domain_details = $this->domain_management_model->domain_list_ajax($page_data['agent_id']);
        //$cm = $this->domain_management_model->get_commission_details($domain_details['origin']);

        //$cm = $this->get_master_commission_details($ModuleType);
        $this->load->model('db_cache_api');
        $airline = $this->db_cache_api->get_airline_list($from = array('k' => 'code', 'v' => 'name'));
        #debug($airline);exit;
        $first_flight = 0;
        $last_flight = ($flight_data ['sect_num_onward'] - 1);
        $trp = 'onward';
        $trp1 = $flight_data['trip_type'] ='onward';
        //$trp_last = $flight_data['trip_type'] == 'circle' ? ($flight_data ['sect_num_return'] - 1) : $last_flight;
        $booking_details['domain_origin'] = 1;
        $booking_details['currency'] = 'INR';
        $booking_details['app_reference'] = $app_reference;
        $booking_details['booking_source'] = 'PTBSID0000000005';
        //$booking_details['is_lcc'] = $flight_data ['is_lcc'] != 'gds' ? 1 : 0;
        $booking_details['trip_type'] = 'oneway';
        $booking_details['phone'] = $flight_data ['passenger_phone'];
        $booking_details['alternate_number'] = $flight_data ['passenger_phone'];
        $booking_details['email'] = $flight_data ['passenger_email'];
        $booking_details['journey_start'] = db_current_datetime(trim($flight_data['dep_date_onward'][$first_flight] . ' ' . $flight_data['dep_time_onward'][$first_flight]));
        $booking_details['journey_end'] = db_current_datetime(trim($flight_data ['arr_date_' . $trp1][$trp_last] . ' ' . $flight_data['arr_time_' . $trp1][$trp_last]));
        $booking_details['journey_from'] = $flight_data ['origin_city'];
        $booking_details['from_loc'] = strtoupper($flight_data ['dep_loc_onward'][$first_flight]);
        $booking_details['to_loc'] = $flight_data ['destination_city'];
        $booking_details['journey_to'] = strtoupper($flight_data ['arr_loc_onward'][$last_flight]);
        $booking_details['payment_mode'] = 'PNHB1';
        $booking_details['attributes'] = '';
        $booking_details['created_by_id'] = $agent_id;
        $booking_details['created_datetime'] = date('Y-m-d H:i:s');
        //$booking_details['offline_supplier_name'] = $flight_data['suplier_name'];

        $book_id = $this->custom_db->insert_record('flight_booking_details', $booking_details);
        $book_id = @$book_id['insert_id'];

        $pax_fare = array();
        $c = 0;
        #debug($flight_data);die;
        foreach ($flight_data['pax_base_fare'] as $fk => $fv) {
            $pax_fare['onward']['basic'] +=$fv;
            $pax_fare['onward']['yq'] = @$pax_fare['onward']['yq'] + ($flight_data['pax_yq_onward'][$fk] * $flight_data['pax_type_count_onward'][$fk]);
            $pax_fare['onward']['others'] += $flight_data['pax_tax_fare'][$fk];
            if ($flight_data['trip_type'] == 'circle' && isset($flight_data['pax_basic_fare_return'][$fk])) {
                $pax_fare['return']['basic'] = @$pax_fare['return']['basic'] + ($flight_data['pax_basic_fare_return'][$fk] * $flight_data['pax_type_count_return'][$fk]);
                $pax_fare['return']['yq'] = @$pax_fare['return']['yq'] + ($flight_data['pax_yq_return'][$fk] * $flight_data['pax_type_count_return'][$fk]);
                $pax_fare['return']['others'] = @$pax_fare['return']['others'] + ($flight_data['pax_other_tax_return'][$fk] * $flight_data['pax_type_count_return'][$fk]);
            }
        }
		#debug($flight_data);die;
        $c_on = strtoupper(@$flight_data['career_onward'][0]);
        $t[$c_on][0]['career'] = @$flight_data['career_onward'];
        $t[$c_on][0]['pax_count'] = array_sum($flight_data['pax_type_count_onward']);

        $f[$c_on]['basic'] = $pax_fare['onward']['basic'];
        $f[$c_on]['yq'] = $pax_fare['onward']['yq'];
        $f[$c_on]['others'] = $pax_fare['onward']['others'];
        if ($flight_data['trip_type'] == 'circle' && valid_array(@$flight_data['career_return'])) {
            $c_rt = strtoupper(@$flight_data['career_return'][0]);
            $t[$c_rt][1]['career'] = @$flight_data['career_return'];
            $t[$c_rt][1]['pax_count'] = array_sum($flight_data['pax_type_count_return']);
            $f[$c_rt]['basic'] = intval(@$f[$c_rt]['basic']) + $pax_fare['return']['basic'];
            $f[$c_rt]['yq'] = intval(@$f[$c_rt]['yq']) + $pax_fare['return']['yq'];
            $f[$c_rt]['others'] = intval(@$f[$c_rt]['others']) + $pax_fare['return']['others'];
        }
#debug($flight_data);die;
        //foreach($t as $tk => $tv ){

        $api_total_display_fare = 0;
        $api_total_tax = 0;
        $api_total_fare = 0;
        $meal_and_baggage_fare = 0;
        $other_fare = 0;
        $basic_fare = 0;
        $fuel_charge = 0;
        $handling_charge = 0;
        $api_service_tax = 0;
        $agent_commission = 0;
        $api_agent_tds_on_commision = 0;
        $dist_commission = 0;
        $api_dist_tds_on_commision = 0;
        $admin_commission = 0;
        $admin_tds_on_commission = 0;
        $agent_markup = 0;
        $admin_markup = 0;
        $dist_markup = 0;
        $app_user_buying_price = 0;


        $price1 = array();
        $tot_agent_buying_price = 0;
        foreach ($t as $tk => $tv) {
            $trpc = $tk = strtoupper($tk);


            $hc = $flight_data['hc_comm'];

            $cm['tds_tax_details']['tds'] = 0;
            $cm['tds_tax_details']['service_tax'] = 0;


            $service_tax = ($f[$trpc]['basic'] * $cm['tds_tax_details']['service_tax']) / 100;
            $agent_comm = ($f[$trpc]['basic'] * $flight_data['basic_comm'] / 100) + ($f[$trpc]['yq'] * $flight_data['yq_comm'] / 100);
            $agent_tds_on_commission = $agent_comm * @$cm['tds_tax_details']['tds'] / 100;
            $dist_comm = 0;
            $dist_tds_on_commission = 0;
            $total = $f[$trpc]['basic'] + $f[$trpc]['yq'] + $f[$trpc]['others'];
            $tot_markup = ( @$flight_data['agent_markup'] + @$flight_data['admin_markup'] );
            $buying_price = $total + $hc + $service_tax + $tot_markup;
            $agent_buying_price = $buying_price - $agent_comm + $agent_tds_on_commission - (@$flight_data['agent_markup']);
            $tot_agent_buying_price += $agent_buying_price;



            $price['api_total_display_fare'] = $buying_price;
            ;
            $price['total_breakup'] = array(
                //'api_total_tax'=> $f[$trpc]['others'] + $f[$trpc]['yq'] + $hc + $service_tax + $tot_markup,
                'api_total_tax' => $f[$trpc]['others'] + $f[$trpc]['yq'],
                'api_total_fare' => $f[$trpc]['basic'],
                'meal_and_baggage_fare' => 0
            );
            $price['price_breakup'] = array(
                'other_fare' => $f[$trpc]['others'] + $hc + $service_tax + $tot_markup,
                'basic_fare' => $f[$trpc]['basic'],
                'fuel_charge' => $f[$trpc]['yq'],
                'handling_charge' => $hc,
                'service_tax' => $service_tax,
                'meal_and_baggage_fare' => 0,
                'agent_commission' => $agent_comm,
                'agent_tds_on_commision' => $agent_tds_on_commission,
                'dist_commission' => $dist_comm,
                'dist_tds_on_commision' => $dist_tds_on_commission,
                'admin_commission' => 0,
                'admin_tds_on_commission' => 0,
                'agent_markup' => @$flight_data['agent_markup'],
                'admin_markup' => @$flight_data['admin_markup'],
                'dist_markup' => 0,
                'app_user_buying_price' => $agent_buying_price
            );

            $api_total_display_fare += $buying_price;
            //	$api_total_tax += $f[$trpc]['others'] + $f[$trpc]['yq'] + $hc + $service_tax + $tot_markup;
            $api_total_tax += $f[$trpc]['others'] + $f[$trpc]['yq'];
            $api_total_fare += $f[$trpc]['basic'];
            $meal_and_baggage_fare += 0;
            $other_fare += $f[$trpc]['others'];
            $basic_fare += $f[$trpc]['basic'];
            $fuel_charge += $f[$trpc]['yq'];
            $handling_charge += $hc;
            $api_service_tax += $service_tax;
            $agent_commission += $agent_comm;
            $api_agent_tds_on_commision += $agent_tds_on_commission;
            $dist_commission += $dist_comm;
            $api_dist_tds_on_commision += $dist_tds_on_commission;
            $admin_commission += 0;
            $admin_tds_on_commission += 0;
            $agent_markup += @$flight_data['agent_markup'];
            $admin_markup += @$flight_data['admin_markup'];
            $dist_markup += 0;
            $app_user_buying_price += $agent_buying_price;



            //debug($price);exit;
            $transaction_details['app_reference'] = $app_reference;
            //$transaction_details['source'] = $flight_data['suplier_id'];
            $transaction_details['pnr'] = strtoupper($flight_data['airline_pnr_onward'][0]);
            $transaction_details['status'] = $status;
            $transaction_details['status_description'] = 'In Payment';
            $transaction_details['book_id'] = $book_id;
            //$transaction_details['booking_source'] = $flight_data['suplier_id'];
            $transaction_details['ref_id'] = '';
            $transaction_details['total_fare'] = $buying_price;
            $transaction_details['admin_commission'] = 0;
            $transaction_details['agent_commission'] = $agent_comm;
            //$transaction_details['domain_markup'] = @$flight_data['admin_markup'] * $tv['pax_count'];
            $transaction_details['attributes'] = '';
            $transaction_details['sequence_number'] = '';
            $flg = $this->custom_db->insert_record('flight_booking_transaction_details', $transaction_details);
            $flight_booking_transaction_details_fk = @$flg['insert_id'];

            foreach ($tv as $sk => $sv) {
                $segment_indicator = $sk;

                foreach ($sv['career'] as $ik => $iv) {
                    //$ik = strtoupper($ik);
                    $from_airport_name = $this->db_cache_api->get_airport_city_name(array(
                        'airport_code' => $flight_data['dep_loc_' . $trp][$ik]));
                    $to_airport_name = $this->db_cache_api->get_airport_city_name(array(
                        'airport_code' => $flight_data['arr_loc_' . $trp][$ik]));

                    //$itenery_details['booking_source'] = $flight_data['suplier_id'];
                    $itenery_details['flight_booking_transaction_details_fk'] = $flight_booking_transaction_details_fk;
                    $itenery_details['app_reference'] = $app_reference;
                    $itenery_details['airline_pnr'] = strtoupper($flight_data['airline_pnr_onward'][0]);
                    $itenery_details['segment_indicator'] = $segment_indicator;
                    $itenery_details['airline_code'] = strtoupper($flight_data['career_' . $trp][$ik]);
                    $itenery_details['airline_name'] = isset($airline[strtoupper($flight_data['career_' . $trp][$ik])]) ? $airline[strtoupper($flight_data['career_' . $trp][$ik])] : strtoupper($flight_data['career_' . $trp][$ik]);
                    $itenery_details['flight_number'] = $flight_data['flight_num_' . $trp][$ik];
                    $itenery_details['fare_class'] = strtoupper($flight_data['booking_class_' . $trp][$ik]);
                    $itenery_details['from_airport_code'] = $flight_data['dep_loc_' . $trp][$ik];
                    $itenery_details['from_airport_name'] = $from_airport_name;
                    $itenery_details['to_airport_code'] = $flight_data['arr_loc_' . $trp][$ik];
                    $itenery_details['to_airport_name'] = $to_airport_name;
                    $itenery_details['departure_datetime'] = db_current_datetime(trim($flight_data['dep_date_' . $trp][$ik] . ' ' . $flight_data['dep_time_' . $trp][$ik]));
                    $itenery_details['arrival_datetime'] = db_current_datetime(trim($flight_data['arr_date_' . $trp][$ik] . ' ' . $flight_data['arr_time_' . $trp][$ik]));
                    $itenery_details['status'] = $status;
                    $itenery_details['operating_carrier'] = strtoupper($flight_data['career_' . $trp][$ik]);
                    $itenery_details['attributes'] = '';

                    $flg = $this->custom_db->insert_record('flight_booking_itinerary_details', $itenery_details);
                    #debug($itenery_details);
                }

                foreach ($flight_data['pax_title'] as $pk => $pv) {

                    $customer_details['app_reference'] = $app_reference;
                    //$customer_details['pax_index'] = $pk;
                    //$customer_details['segment_indicator'] = $segment_indicator;
                    $customer_details['passenger_type'] = $flight_data['pax_type'][$pk];
                    $customer_details['flight_booking_transaction_details_fk'] = $flight_booking_transaction_details_fk;
                    $customer_details['is_lead'] = ($pk == 0) ? 1 : 0;
                    $customer_details['title'] = $pv;
                    $customer_details['first_name'] = strtoupper($flight_data['pax_first_name'][$pk]);
                    $customer_details['middle_name'] = '';
                    $customer_details['last_name'] = strtoupper($flight_data['pax_last_name'][$pk]);
                    $customer_details['date_of_birth'] = $flight_data['date_of_birth'][$pk];
                    if ($pv == 1 || $pv == 4) {
                        $customer_details['gender'] = 'Male';
                    } else {
                        $customer_details['gender'] = 'Female';
                    }
                    $customer_details['passenger_nationality'] = 'IN';
                    $customer_details['passport_number'] = $flight_data['pax_passport_num'][$pk];
                    $customer_details['passport_issuing_country'] = '';
                    $customer_details['passport_expiry_date'] = db_current_datetime($flight_data['pax_pp_expiry'][$pk]);
                    //$customer_details['ff_no'] = $flight_data['pax_ff_num'][$pk];
                    //$customer_details['ticket_no'] = $flight_data['pax_ticket_num_'.$trp][$pk];
                    $customer_details['status'] = $status;
                    $k = 0;
                    if ($flight_data['pax_type'][$pk] == 'Child') {
                        $k = 1;
                    } else if ($flight_data['pax_type'][$pk] == 'Infant') {
                        $k = 2;
                    }
                    $pax_basic = $flight_data['api_total_basic_fare'];
                    $pax_yq = $flight_data['pax_yq_' . $trp][$k];
                    $pax_other = 0;
                   // $pax_other = $flight_data['api_total_tax'];
                    $pax_total = $flight_data['api_total_selling_price'];
                    $pax_per_price = $flight_data['pax_base_fare'][$pk];

                    $attr['price_breakup'] = array('pax_per_price'=>$pax_per_price ,'base_price' => $pax_basic, "yq" => $pax_yq, 'tax' => $pax_other, 'total_price' => $pax_total);
                    $customer_details['attributes'] = json_encode($attr);
                    //debug($customer_details);exit;
                    $flg = $this->custom_db->insert_record('flight_booking_passenger_details', $customer_details);
                    $passenger_fk[] = @$flg['insert_id'];

                    $pax_ticket_num_onward[] = strtoupper($flight_data['airline_pnr_onward'][0]);
                    $pax_basic_fare_onward[] = $flight_data['pax_basic_fare_onward'];
                    $pax_other_tax_onward[] = $flight_data['pax_other_tax_onward'];
                    $pax_total_fare_onward[] = $flight_data['pax_total_fare_onward'];
                    # Storing Ticket Details on flight_passenger_ticket_info
                   
                }
                 foreach ($pax_ticket_num_onward as $key => $value) {
                        $data['TicketNumber'] = $value;
                        $data['passenger_fk'] = $passenger_fk[$key];
                        $attr = array('BasePrice' => $flight_data['api_total_basic_fare'], 'Tax' => $flight_data['api_total_tax'], 'TotalPrice' => $flight_data['api_total_selling_price']);
                        $data['Fare'] = json_encode($attr);
                        $insert_id = $this->custom_db->insert_record('flight_passenger_ticket_info', $data);
                       // return false;
                    }

                $trp = 'return';
            }
        }

        /* $price1['api_total_display_fare'] = $api_total_display_fare;
          $price1['total_breakup'] = array(
          'api_total_tax'=> $api_total_tax,
          'api_total_fare'=> $api_total_fare,
          'meal_and_baggage_fare'=>$meal_and_baggage_fare
          );
          $price1['price_breakup'] = array(
          'other_fare'=> $other_fare,
          'basic_fare'=> $basic_fare,
          'fuel_charge'=> $fuel_charge,
          'handling_charge'=>$handling_charge,
          'service_tax' => $api_service_tax,
          'meal_and_baggage_fare'=>$meal_and_baggage_fare,
          'agent_commission' =>$agent_commission,
          'agent_tds_on_commision'=>$api_agent_tds_on_commision,
          'dist_commission' => $dist_commission,
          'dist_tds_on_commision' =>$api_dist_tds_on_commision,
          'admin_commission'=>$admin_commission,
          'admin_tds_on_commission'=>$admin_tds_on_commission,
          'agent_markup'=>$agent_markup,
          'admin_markup'=>$admin_markup,
          'dist_markup' =>$dist_markup,
          'app_user_buying_price'=>$app_user_buying_price
          );

          $up['total_price_attributes'] = json_encode($price1);
          $this->db->update( 'flight_booking_details', $up,array('app_reference'=>$app_reference) );
          debug($price1);
          exit;
         */

        $flight_crs_booking_details['fsid'] = $fsid_for_seat_update;
   		$flight_crs_booking_details['fudid'] = $fdid_for_seat_update;
   		$flight_crs_booking_details['app_reference'] = $app_reference;
        $flight_crs_booking_details['booking_source'] = 'PTBSID0000000005';
        $flight_crs_booking_details['status'] = $status;
        $flight_crs_booking_details['agent_id'] = $agent_id;
		$this->custom_db->insert_record('flight_crs_booking_details', $flight_crs_booking_details);


        $this->load->model('domain_management_model');
        if (@$flight_booking_transaction_details_fk > 0 && $tot_agent_buying_price > 0) {

			$this->b2c_and_b2b_deduct_flight_booking_amount($app_reference, $domain_details[0]['user_id'],$domain_details[0]['user_type']);
            $this->update_flight_crs_seat_no($fdid_for_seat_update,$booked_seat_new);

            /* 	$this->domain_management_model->modify_user_balance ('b2b',$agent_id, -$tot_agent_buying_price);
              $this->domain_management_model->update_transaction_payment_status('flight', $app_reference, 'paid', $tot_agent_buying_price);
              $this->domain_management_model->save_transaction_details ( 'flight', $app_reference, -$tot_agent_buying_price, 0, 0, 'Ticket Booked Offline', $agent_id, false );
              $pnr = $flight_data['airline_pnr_onward'][0];
              if(!empty($pnr) && $status == 'BOOKING_CONFIRMED'){
              $air = $flight_data['career_onward'][0]. ' '.$booking_details['from_loc']. ' to '.$booking_details['to_loc'].' '.$flight_data['flight_num_onword'][0].' at '. date('H:i Y-m-d', strtotime($booking_details['journey_start']));
              $pax = $flight_data['first_name'][0].' '.$flight_data['last_name'][0];
              $msg = 'Ref No.: '.$app_reference.' is confirmed. Air: '.$air.', PNR: '.$pnr.', Pax: '.$pax;
              $mobile = $booking_details['phone'];
              $user_id = $booking_details['created_by_id'];

              if(!empty($mobile)){

              send_sms($msg,$user_id,$app_reference, $mobile);
              }
              send_sms($msg,$user_id,$app_reference);
              } */
        }

        $this->domain_management_model->create_track_log($app_reference, 'Offline Booking - Flight');
        //debug($flight_data);
    }

     public function b2c_and_b2b_deduct_flight_booking_amount($app_reference, $domain_origin,$user_type) {
          //echo $domain_origin;exit;
        $ci = & get_instance();
        $condition = array();
        $condition['app_reference'] = $app_reference;
        $condition['sequence_number'] = @$sequence_number;

        $data = $ci->db->query('select BD.currency,BD.currency_conversion_rate,BD.created_by_id,FT.* from flight_booking_details BD
						join flight_booking_transaction_details FT on BD.app_reference=FT.app_reference
						where FT.app_reference="' . trim($app_reference) . '"'
                )->row_array();
		//debug($data);die;
        if (valid_array($data) == true && in_array($data['status'], array('BOOKING_CONFIRMED')) == true) {//Balance Deduction only on Confirmed Booking
            $ci->load->library('booking_data_formatter');
            $transaction_details = $data;
            $agent_buying_price = $ci->booking_data_formatter->agent_buying_price($transaction_details);
		#debug($agent_buying_price);die;
            $agent_buying_price = $agent_buying_price[0];
            $domain_booking_attr = array();
            $domain_booking_attr['app_reference'] = $app_reference;
            $domain_booking_attr['transaction_type'] = 'flight';
            //Deduct Domain Balance
            #$ci->domain_management->debit_domain_balance($agent_buying_price, 'test', $domain_origin, $domain_booking_attr); //deduct the domain balance
            //Save to Transaction Log
            $balance=$this->domain_management_model->b2c_b2b_domain_list_ajax($data['created_by_id'],1);
           
            $cur_balance=$balance;
            //$cur_balance=$balance-$agent_buying_price;
            
          
            $domain_markup = $transaction_details['domain_markup'];
            $level_one_markup = 0;
            $agent_transaction_amount = $agent_buying_price - $domain_markup;
            $currency = $transaction_details['currency'];
            $currency_conversion_rate = $transaction_details['currency_conversion_rate'];
            $remarks = 'Flight transaction was successfully done.';
            $domain_markup=0;
            $currency="INR";
            $convinence=0;
            $discount=0;
            $ci->domain_management_model->save_transaction_details('flight', $app_reference, $agent_transaction_amount, $domain_markup, $level_one_markup, $remarks,$convinence, $discount, $currency, $currency_conversion_rate, $domain_origin,$user_type);
              $this->domain_management_model->domain_balance_update($cur_balance,$data['created_by_id'],$user_type,(-$agent_buying_price));
        }
    }



     public function deduct_flight_booking_amount($app_reference, $domain_origin) {
        //  echo $app_reference;exit;
        $ci = & get_instance();
        $condition = array();
        $condition['app_reference'] = $app_reference;
        $condition['sequence_number'] = @$sequence_number;

        $data = $ci->db->query('select BD.currency,BD.currency_conversion_rate,BD.created_by_id,FT.* from flight_booking_details BD
						join flight_booking_transaction_details FT on BD.app_reference=FT.app_reference
						where FT.app_reference="' . trim($app_reference) . '"'
                )->row_array();
		//debug($data);die;
        if (valid_array($data) == true && in_array($data['status'], array('BOOKING_CONFIRMED')) == true) {//Balance Deduction only on Confirmed Booking
            $ci->load->library('booking_data_formatter');
            $transaction_details = $data;
            $agent_buying_price = $ci->booking_data_formatter->agent_buying_price($transaction_details);
		#debug($agent_buying_price);die;
            $agent_buying_price = $agent_buying_price[0];
            $domain_booking_attr = array();
            $domain_booking_attr['app_reference'] = $app_reference;
            $domain_booking_attr['transaction_type'] = 'flight';
            //Deduct Domain Balance
            #$ci->domain_management->debit_domain_balance($agent_buying_price, 'test', $domain_origin, $domain_booking_attr); //deduct the domain balance
            //Save to Transaction Log
            $balance=$this->domain_management_model->domain_list_ajax($data['created_by_id']);
         //   debug($balance);
            $cur_balance=$balance-$agent_buying_price;
           //  debug($cur_balance);die;
            $this->domain_management_model->domain_balance_update($cur_balance,$data['created_by_id']);
            $domain_markup = $transaction_details['domain_markup'];
            $level_one_markup = 0;
            $agent_transaction_amount = $agent_buying_price - $domain_markup;
            $currency = $transaction_details['currency'];
            $currency_conversion_rate = $transaction_details['currency_conversion_rate'];
            $remarks = 'flight Transaction was Successfully done';
            $domain_markup=0;
            $currency="INR";
            $ci->domain_management_model->save_transaction_details('flight', $app_reference, $agent_transaction_amount, $domain_markup, $level_one_markup, $remarks, $currency, $currency_conversion_rate, $domain_origin);
        }
    }


    private function update_flight_crs_seat_no($fdid_for_seat_update,$booked_seat_new){
    	$data['booked_seat'] = $booked_seat_new;
    	$con['origin'] = $fdid_for_seat_update;
    	$this->custom_db->update_record('crs_update_flight_details', $data,$con);
    }

    //Dinesh 6 Feb 2018

    function update_passenger_details($origin,$param){
    	$data['first_name'] = strtoupper($param['first_name']);
    	$data['title'] = $param['title'];
    	$data['last_name'] = strtoupper($param['last_name']);
    	$data['mailing_status'] = strtoupper($param['mailing_status']);
    	$con['origin'] = $origin;
    	$result = $this->custom_db->update_record('flight_booking_passenger_details', $data,$con);
    	return $result;
    }

    //End Dinesh
	function flight_offline_cancel_request_details($condition = array()) {
		$condition = $this->custom_db->get_custom_condition($condition);
		$cancellation_details_query = 'select FCD.*,U.*, concat("{",group_concat(concat("\"",PCD.p_origin,"\":","\"",PCD.status,"\"")), "}") AS cancel_pax_details from flight_cancellation_passenger_details AS PCD join flight_cancellation_details AS FCD ON FCD.origin=PCD.fc_origin join user as U ON U.user_id=FCD.created_by_id where 1 '.$condition.' group by PCD.fc_origin order by FCD.origin DESC';

		//debug($cancellation_details_query ); exit;
		return $this->db->query ( $cancellation_details_query )->result_array ();
	}
	function flight_offline_cancel_details($cancellation_id = '') {
		$cancellation_details_query = 'select FCD.*, concat("{",group_concat(concat("\"",PCD.p_origin,"\":","\"",PCD.status,"\"")), "}") AS cancel_pax_details from flight_cancellation_passenger_details AS PCD join flight_cancellation_details AS FCD ON FCD.origin=PCD.fc_origin
						WHERE FCD.RequestId = ' . $this->db->escape ( $cancellation_id ) . ' group by PCD.fc_origin';
		// echo $cancellation_details_query;exit;
		return $this->db->query ( $cancellation_details_query )->row_array ();
	}
	function get_cancelled_booking_details($cancellation_id, $booking_status = 'BOOKING_CONFIRMED') {
		$response ['status'] = FAILURE_STATUS;
		$response ['data'] = array ();

		$response ['data'] ['offline_cancel'] = $this->flight_offline_cancel_details ( $cancellation_id );
		$app_reference = $response ['data'] ['offline_cancel'] ['app_reference'];
	
		$response ['data'] ['booking_details'] = $this->flight_booking_details ( $app_reference );
		
		if (valid_array ( $response ['data'] ['booking_details'] ) == true) {
			$response ['status'] = SUCCESS_STATUS;
			$response ['data'] ['booking_itinerary_details'] = $this->flight_itinerary_details ( $app_reference );
			$response ['data'] ['booking_transaction_details'] = $this->flight_transaction_details ( $app_reference, $booking_status );
			$response ['data'] ['booking_customer_details'] = $this->flight_pax_details ( $app_reference );
			$response ['data'] ['cancellation_details'] = $this->flight_cancellation_details ( $app_reference );
		}
		return $response;
	}


	/**
	 *
	 * @param unknown $app_reference        	
	 * @param string $booking_source        	
	 * @param string $booking_status        	
	 */
	function flight_booking_details($app_reference, $booking_source = '', $booking_status = '') {
		// Booking Details
		$bd_query = 'select * from flight_booking_details AS BD WHERE BD.app_reference like ' . $this->db->escape ( $app_reference );
		// if (empty ( $booking_source ) == false) {
		// $bd_query .= ' AND BD.booking_source = ' . $this->db->escape ( $booking_source );
		// }
		return $this->db->query ( $bd_query )->result_array ();
	}

	/**
	 *
	 * @param unknown $app_reference        	
	 */
	function flight_itinerary_details($app_reference) {
		// Itinerary Details
		$id_query = 'select * from flight_booking_itinerary_details AS ID WHERE ID.app_reference=' . $this->db->escape ( $app_reference );
		return $this->db->query ( $id_query )->result_array ();
	}


	/**
	 *
	 * @param unknown $app_reference        	
	 */
	function flight_transaction_details($app_reference, $booking_status) {
		// Transaction Details
		$td_query = 'select * from flight_booking_transaction_details AS CD WHERE CD.app_reference=' . $this->db->escape ( $app_reference );
		if (empty ( $booking_status ) == false) {
			$td_query .= ' AND CD.status = ' . $this->db->escape ( $booking_status );
		}
		return $this->db->query ( $td_query )->result_array ();
	}
	

	/**
	 *
	 * @param unknown $app_reference        	
	 */
	function flight_pax_details($app_reference) {
		// Customer and Ticket Details
		// $cd_query = 'select CD.* from flight_booking_passenger_details AS CD WHERE CD.app_reference =' . $this->db->escape ( $app_reference );
		$cd_query = 'select CD.*,concat("{",group_concat(concat("\"","\"",":{\"origin\":",CD.origin,",\"status\":\"",CD.status,"\"}")), "}") AS pax_details,FPTI.TicketId,FPTI.TicketNumber,FPTI.IssueDate,FPTI.Fare,FPTI.SegmentAdditionalInfo, CMD.passenger_fk as mp_origin,  group_concat(CMD.description) as mdescription,CBD.passenger_fk as bp_origin, group_concat(CBD.description) as bdescription, sum(CBD.price) as bfare, sum(CMD.price) as mfare from flight_booking_passenger_details AS CD left join flight_passenger_ticket_info FPTI on CD.origin=FPTI.passenger_fk LEFT JOIN flight_booking_meal_details AS CMD ON CMD.passenger_fk = CD.origin LEFT JOIN flight_booking_baggage_details AS CBD ON CBD.passenger_fk = CD.origin WHERE CD.app_reference =' . $this->db->escape ( $app_reference ) . 'group by CD.origin';
		return $this->db->query ( $cd_query )->result_array ();
	}



	/**
	 *
	 * @param unknown $app_reference        	
	 */
	function flight_cancellation_details($app_reference) {
		// return '';
		// Cancellation Details
		$cancellation_details_query = 'select FCD.*, concat("{",group_concat(concat("\"",CD.origin,"\"",":{\"segment\":",",\"status\":\"",FCD.refund_status,"\"}")), "}") AS pax_cancel_details from flight_booking_passenger_details AS CD
						join flight_cancellation_passenger_details AS PCD ON PCD.p_origin=CD.origin join flight_cancellation_details AS FCD ON FCD.origin=PCD.fc_origin
						WHERE FCD.app_reference = ' . $this->db->escape ( $app_reference );
		// echo $cancellation_details_query;exit;
		return $this->db->query ( $cancellation_details_query )->result_array ();
	}

#Dinesh
	function update_mailing_status($book_id, $mailing_status){
		$book__arr = explode(',', $book_id);

    	$data['mailing_status'] = $mailing_status;
    	//debug($mailing_status);exit;
    	foreach ($book__arr as $k__book_id => $v__book_id) {
    		//debug($v__book_id);echo "hhhhh";
    		$con['origin'] = $v__book_id;
    		$result = $this->custom_db->update_record('flight_booking_passenger_details', $data,$con);
    	}
    	
    	return array('status'=>true);
    }
    function delete_suppliers($id){
    	$this->db->where(array('origin'=>$id));
    	$this->db->delete('supplier');
    }

    function update_offline_cancel_request_data($form_data) {
		$this->db->where('app_reference',$form_data['app_reference']);
		$booking_details = $this->db->get('flight_booking_details')->result_array();
		$agent = array();
		$condition ['origin'] = $form_data ['_fcr_origin'];
		$condition ['RequestId'] = $form_data ['cancellation_id'];

		$data ['refund_status '] = $form_data ['status'];
		if ($data ['refund_status '] == 'PROCESSED') {
			$data ['API_RefundedAmount'] = $form_data ['amount_refund'];		
			$data ['API_CancellationCharge'] = $form_data ['cancellation_charge'];
		}
		$data ['cancellation_processed_on']  = db_current_datetime();
	    
		$flag = $this->custom_db->update_record ( 'flight_cancellation_details', $data, $condition );

		if ($flag) {
			
			$pax_data ['status'] = $form_data ['status'];
			
			$this->db->where_in ( 'p_origin', $form_data ['cancel_pax_origin'] )->update ( 'flight_cancellation_passenger_details', $pax_data, array('fc_origin'=>$form_data ['_fcr_origin']));
			
			if ($pax_data ['status'] == 'PROCESSED') {
				$flag = $this->db->where_in ( 'origin', $form_data ['cancel_pax_origin'] )->update ( 'flight_booking_passenger_details', array('status'=>'BOOKING_CANCELLED'));				
				
				if ($flag) {
					
					$pax_count = $this->db->where(array('app_reference'=>$form_data ['app_reference'],'status'=>'BOOKING_CONFIRMED'))->count_all_results('flight_booking_passenger_details');
				
					if($pax_count<=0){
						
						$this->db->where( 'app_reference', $form_data ['app_reference'] )->update ( 'flight_booking_transaction_details', array('status'=>'BOOKING_CANCELLED'));
						$this->db->where( 'app_reference', $form_data ['app_reference'] )->update ( 'flight_booking_details', array('status'=>'BOOKING_CANCELLED'));
					}
					
					$agent['created_by_id'] = $booking_details[0]['created_by_id'];
					//	debug($agent); exit;
					$this->db->where('user_id',$agent['created_by_id']);
					$user_details = $this->db->get('user')->result_array();
					
					if($user_details[0]['user_type'] == 4){
						$user_type = 'b2c';
					}else{
						$user_type = 'b2b';
					}
					$form_data ['amount_refund'] = $form_data ['amount_refund'];
					$form_data ['amount_refund_ref'] = $form_data ['amount_refund'];
					if(isset($agent['created_by_id'])) {
					    $this->load->model ( 'domain_management_model' );
					    if($booking_details[0]['payment_mode'] == 'PNHB1'){
							
							$this->domain_management_model->save_transaction_details_cancelled ( 'flight_cancel', $form_data ['app_reference'], $form_data ['amount_refund'], 0, 0, 'Flight Ticket Cancelled Amount Refunded', $agent ['created_by_id'], false,$user_type );
							$this->domain_management_model->modify_user_balance($user_type,$agent['created_by_id'],$form_data['amount_refund_ref'] );
							$this->domain_management_model->create_track_log($form_data['app_reference'],'Offline Cancellation Successull and Amount ('.$form_data ['amount_refund'].') refunded');
						}
        			}	 
				}
			} else {
				$this->load->model ( 'domain_management_model' );
				$this->domain_management_model->create_track_log($form_data['app_reference'],'Offline cancellation request not processed successfully');
			}	
		}
		//exit();
	}

	function update_pnr($params){
		$flight_itinerary_details_query = 'UPDATE flight_booking_itinerary_details SET airline_pnr = "'.$params['pnr'].'" WHERE app_reference = "'.$params['app_ref'].'"';
		$this->db->query($flight_itinerary_details_query);

		$flight_booking_transaction_details_query = 'UPDATE flight_booking_transaction_details SET pnr = "'.$params['pnr'].'" WHERE app_reference = "'.$params['app_ref'].'"';
		$this->db->query($flight_booking_transaction_details_query);

		$flight_passenger_query = 'SELECT origin FROM flight_booking_passenger_details WHERE app_reference = "'.$params['app_ref'].'"';
		$flight_passenger_result = $this->db->query($flight_passenger_query)->result_array();
		foreach ($flight_passenger_result as $pass_key => $pass_value) {
			$flight_passenger_ticket_info_query = 'UPDATE flight_passenger_ticket_info SET TicketId = "'.$params['pnr'].'", TicketNumber = "'.$params['pnr'].'" WHERE passenger_fk = "'.$pass_value['origin'].'"';
			echo $flight_passenger_ticket_info_query; 
			$this->db->query($flight_passenger_ticket_info_query);	
		}
	}
	function update_markup($params){
		$flight_booking_transaction_details_query = 'UPDATE flight_booking_transaction_details SET admin_markup = "'.$params['markup'].'" WHERE app_reference = "'.$params['app_ref'].'"';
		$this->db->query($flight_booking_transaction_details_query);
	}

	function update_dep_arr_date($params){
		$flight_booking_details_query = 'UPDATE flight_booking_details SET journey_start = "'.$params['dep_date'].'", journey_end = "'.$params['arr_date'].'" WHERE app_reference = "'.$params['app_ref'].'"';
		$this->db->query($flight_booking_details_query);
		$flight_booking_itinerary_details_query = 'UPDATE flight_booking_itinerary_details SET departure_datetime = "'.$params['dep_date'].'", arrival_datetime = "'.$params['arr_date'].'" WHERE app_reference = "'.$params['app_ref'].'"';
		$this->db->query($flight_booking_itinerary_details_query);
	}

	function flight_request_b2c_details() {
		$query = 'select group_request_id,refernce_no,trip_type,user_type,remarks,from_loc,to_loc,departure,return_date,class_type,name,email_id,contact_number,requested_on,adults,children,infants
				FROM group_request
       order by group_request_id DESC';
		//echo $query;exit;
		return $this->db->query ( $query )->result_array ();
	}



}
