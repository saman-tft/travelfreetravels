<?php
/**
 * Library which has generic functions to get data
 *
 * @package    Provab Application
 * @subpackage Flight Model
 * @author     Balu A<balu.provab@gmail.com>
 * @version    V2
 */
Class Flight_Model extends CI_Model
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
		$id_query = 'select * from flight_booking_itinerary_details AS ID WHERE ID.app_reference='.$this->db->escape($app_reference).' order by origin ASC';
		//Transaction Details
		$td_query = 'select * from flight_booking_transaction_details AS CD WHERE CD.app_reference='.$this->db->escape($app_reference);
		//Customer and Ticket Details
		$cd_query = 'select distinct CD.*,FPTI.api_passenger_origin,FPTI.TicketId,FPTI.TicketNumber,FPTI.IssueDate,FPTI.Fare,FPTI.SegmentAdditionalInfo
						from flight_booking_passenger_details AS CD
						left join flight_passenger_ticket_info FPTI on CD.origin=FPTI.passenger_fk
						WHERE CD.flight_booking_transaction_details_fk IN 
						(select TD.origin from flight_booking_transaction_details AS TD 
						WHERE TD.app_reference ='.$this->db->escape($app_reference).')';
		//Cancellation Details
		$cancellation_details_query = 'select FCD.*
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

		// if(isset($condition) == true)
		// {
		// 	$offset = 0;
		// }else{
			
		// 	$offset = $offset;
		// }


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
			$bd_query = 'select BD.* ,U.user_name,U.first_name,U.last_name from flight_booking_details AS BD
					     left join user U on U.user_id = BD.created_by_id
					     left join user_type UT on UT.origin = U.user_type
					     join flight_booking_transaction_details as BT on BD.app_reference = BT.app_reference		
						 WHERE  (U.user_type='.B2C_USER.' OR BD.created_by_id = 0) AND BD.domain_origin='.get_domain_auth_id().' '.$condition.'
						 order by BD.created_datetime desc, BD.origin desc limit '.$offset.', '.$limit;		

						 
						 
			$booking_details	= $this->db->query($bd_query)->result_array();
			//echo debug($bd_query); 			exit;
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
				$payment_details_query = 'select * from  payment_gateway_details AS PD
							WHERE PD.app_reference IN ('.$app_reference_ids.')';
				$booking_itinerary_details	= $this->db->query($id_query)->result_array();
				$booking_customer_details	= $this->db->query($cd_query)->result_array();
				$booking_transaction_details = $this->db->query($td_query)->result_array();
				$cancellation_details = $this->db->query($cancellation_details_query)->result_array();
				$payment_details = $this->db->query($payment_details_query)->result_array();
			}
	
			$response['data']['booking_details']			= $booking_details;
			$response['data']['booking_itinerary_details']	= $booking_itinerary_details;
			$response['data']['booking_transaction_details']	= $booking_transaction_details;
			$response['data']['booking_customer_details']	= $booking_customer_details;
			$response['data']['cancellation_details']	= $cancellation_details;
			$response['data']['payment_details']	= $payment_details;
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

		// if(isset($condition) == true)
		// {
		// 	$offset = 0;
		// }else{
		// 	$offset = $offset;
		// }

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
	 * Balu A
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
	 Balu A
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
	 * Balu A
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
	function booking_cancel($condition=array(), $count=false, $offset=0, $limit=100000000000)
	{
		$condition = $this->custom_db->get_custom_condition($condition);
		//$b2c_condition_array = array('U.user_type', '=', B2C_USER, ' OR ', 'BD.created_by_id', '=', 0);
		
		//BT, CD, ID

		// if(isset($condition) == true)
		// {
		// 	$offset = 0;
		// }else{
			
		// 	$offset = $offset;
		// }


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
			$bd_query = 'select BD.* ,U.user_name,U.first_name,U.last_name from flight_booking_details AS BD
					     left join user U on U.user_id = BD.created_by_id
					     left join user_type UT on UT.origin = U.user_type
					     join flight_booking_transaction_details as BT on BD.app_reference = BT.app_reference		
						 WHERE  (U.user_type='.B2B_USER.' OR U.user_type='.B2C_USER.' OR BD.created_by_id = 0) AND BD.domain_origin='.get_domain_auth_id().' '.$condition.'
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
				// echo $cancellation_details_query;exit;
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
	// added for flight_cancellation_details
	function add_flight_cancellation_details($pax_origin)
	{
		//1.Adding Cancellation Details
		$data = array();
		$data['RequestId'] = 1;
		$data['ChangeRequestStatus'] = 1;
		$data['statusDescription'] = 'Unassigned';
		//Insert Data
		$data['passenger_fk'] = $pax_origin;
		$data['created_by_id'] = intval(@$this->entity_user_id);
		$data['created_datetime'] = date('Y-m-d H:i:s');
		$data['cancellation_requested_on'] = date('Y-m-d H:i:s');
		$this->custom_db->insert_record('flight_cancellation_details', $data);
	}	
}
