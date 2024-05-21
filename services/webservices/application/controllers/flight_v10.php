<?php if (! defined ( 'BASEPATH' ))	exit ( 'No direct script access allowed' );
class Flight_V10 extends CI_Controller {
	//TODO
	/**
	 * 1.Validate the Request Based On Method
	 */
	private static $credential_type;//live/test
	private static $track_log_id;//Track Log ID
	private static $AppReference;//App Reference-- Booking Reference Id
	private $domain_commission_percentage = 0;//Domain Commission on Commision of Provab
	private $domain_id = 0;//domain origin
	private $course_version = FLIGHT_VERSION_1;//course version
	function __construct()
	{
		parent::__construct();
		$this->load->library('flight/tbo_private');
		$this->load->library('booking_data_formatter');
		$this->load->library('domain_management');
		$this->load->library('provab_mailer');
		$this->load->library('Exception_Logger', array('meta_course' => META_AIRLINE_COURSE));
	}
	public function TestConnection($parameter='')
	{
		echo 'TestConnection';exit;
		$this->exception_logger->log_exception('TEST12121211', 'TESTMODE', 'Testing', 'LOG DETAILS');
		$this->tbo_private->test();
		echo 'Flight Server Connected';exit;
		//$this->add_test_data('Flight Server Connected');
		$data['result'] = $parameter;
		echo json_encode($data, true);
		exit;
	}

	public function test_cache_data()
	{
		$this->tbo_private->cache_api_response('test', array('test_data'));
		//$this->tbo_private->read_cache_data();
	}
	public function is_valid_user($authentication_details = array())
	{
		////Enable for testing
		/*$UserName = 'test';
		$Password = 'password';
		$DomainKey = 'wKuTTCM3wqbBLoLkMHp8VIvyA';
		$System = 'test';*/
		//debug($authentication_details);exit;
		$UserName = $authentication_details['HTTP_X_USERNAME'];
		$Password = $authentication_details['HTTP_X_PASSWORD'];
		$DomainKey = $authentication_details['HTTP_X_DOMAINKEY'];
		$System = $authentication_details['HTTP_X_SYSTEM'];
                $SERVER_IP=$authentication_details['REMOTE_ADDR'];
		
		$domain_user_details = array();
		$domain_user_details['System'] = $System;
		$domain_user_details['DomainKey'] = $DomainKey;
		$domain_user_details['UserName'] = $UserName;
		$domain_user_details['Password'] = $Password;
                $domain_user_details['SERVER_ADDR'] = $SERVER_IP;
                
                $domain_user_details_DB = array();
		$domain_user_details_DB['System'] = $System;
		$domain_user_details_DB['DomainKey'] = $DomainKey;
		$domain_user_details_DB['UserName'] = $UserName;
		$domain_user_details_DB['Password'] = $Password;
                $domain_user_details_DB['SERVER_ADDR'] = $SERVER_IP;
                $domain_user_details_DB['Header'] = json_encode($authentication_details);
                
                
           $this->custom_db->insert_record('customer_ip_address',$domain_user_details_DB);
                
                
		$course = META_AIRLINE_COURSE;
		$domin_details = $this->domain_management->validate_domain($domain_user_details);
		if($domin_details['status'] == SUCCESS_STATUS) {
			//Checking Domain Version
			$domain_course_version = $this->domain_management->validate_domain_course_version($course, $this->course_version, $domin_details['data']);
			if($domain_course_version['status'] == SUCCESS_STATUS){
				$this->domain_commission_percentage = $this->domain_management->get_flight_commission($domin_details['data']['domain_origin']);//Assiging Domain Flight Commission
				$this->domain_id = intval($domin_details['data']['domain_origin']);
				self::$credential_type = $domain_user_details['System'];
				self::$track_log_id = PROJECT_PREFIX.'-DOMAIN-'.$this->domain_id.'-'.time();
			} else {
				$domin_details = $domain_course_version;
			}
		}
		return $domin_details;
	}
	/*/**
	 * Return Environment Live/Test
	 */
	public static function get_credential_type()
	{
		return self::$credential_type;
	}
	/**
	 * Setting Booking AppReference
	 */
	private static function set_app_reference($AppReference)
	{
		self::$AppReference = $AppReference;
	}
	/**
	 * Getting Booking AppReference
	 */
	public static function get_app_reference()
	{
		return self::$AppReference;
	}
	/**
	 * Handles Flight Requests
	 * @param unknown_type $request_type
	 * @param unknown_type $request
	 * @param unknown_type $_header
	 */
	public function provab_api($request_type)
	{
			
		$request = file_get_contents ("php://input");
		$headers_info = $_SERVER;
		//Vlaidte All Requests
		$is_valid_domain = $this->is_valid_user($headers_info);
		if($is_valid_domain['status'] == true) {
			$request = json_decode($request, true);
			$this->store_api_request($request_type, $request);//Store the Request Details
			switch ($request_type) {
				case 'Authenticate' :
					$response = $this->Authenticate ($request);
					break;
				case 'Search' :
					$response = $this->Search ($request );
					break;
				case 'FareRule' :
					$response = $this->FareRule ($request );
					break;
				case 'FareQuote' :
					$response = $this->FareQuote ($request );
					break;
				case 'Book' :
					$response = $this->Book ($request );
					break;
				case 'Ticket' :
					$response = $this->Ticket ($request );
					break;
				case 'GetBookingDetails' :
					$response = $this->GetBookingDetails ($request );
					break;
				case 'SendChangeRequest' ;
					$response = $this->SendChangeRequest($request);
					break;
				case 'GetChangeRequestStatus' ;
					$response = $this->GetChangeRequestStatus($request);
					break;
				case 'GetCalendarFare' :
					$response = $this->GetCalendarFare ($request );
					break;
				case 'UpdateCalendarFareOfDay' :
					$response = $this->UpdateCalendarFareOfDay ($request );
					break;
			   case 'UpdatePNR' :
				    $response = $this->UpdatePNR($request );
					break;
				case 'TicketRefundDetails' :
				$response = $this->TicketRefundDetails($request );
				break;
				case 'IssueHoldTicket' :
				$response = $this->IssueHoldTicket($request );
				break;
				default:
					$response['Status'] = FAILURE_STATUS;
					$response['Message'] = 'Invalid Service';
			}
		} else {
			//Invalid Domain User
			$response['Status'] = FAILURE_STATUS;
			$response['Message'] = $is_valid_domain['message'];
		}
		if($response['Status'] == SUCCESS_STATUS) {
			$data['Status'] = $response['Status'];
			$data['Message'] = $response['Message'];
			$data[$request_type] = $response['data'];
		} else {
			$data['Status'] = $response['Status'];
			$data['Message'] = $response['Message'];
		}
		$this->output_compressed_data($data);
	}
	/**
	 * Compress and output data
	 * @param array $data
	 */
	private function output_compressed_data($data)
	{
		while (ob_get_level() > 0) { ob_end_clean() ; }
		ob_start("ob_gzhandler");
		header('Content-type:application/json');
		echo json_encode($data);
		ob_end_flush();
		exit;
	}
	/**
	 * Returns Booking Transaction Amount
	 * @param unknown_type $booking_fare_details
	 */
	private function get_booking_transaction_amount($booking_fare_details)
	{
		$domain_currency_conversion = false;
		$Fare = $booking_fare_details['Fare'];
		$FareBreakdown = $booking_fare_details['FareBreakdown'];
		$JourneyAttributes = $booking_fare_details['JourneyAttributes'];
		$fare_details = $this->update_fare_markup_commission($Fare, $FareBreakdown, $JourneyAttributes, $domain_currency_conversion);
		$booking_transaction_amount = floatval($fare_details['FareDetails']['OfferedFare']+$fare_details['FareDetails']['TdsOnCommission']+
												$fare_details['FareDetails']['TdsOnPLB']+$fare_details['FareDetails']['TdsOnIncentive']);
		return $booking_transaction_amount;
	}
	/**
	 * Authenticate
	 * @param unknown_type $request
	 */
	private function Authenticate($request)
	{
		$data = $this->tbo_private->Authenticate($request);
		return $data;
	}
	/**
	 * Search
	 * @param unknown_type $request
	 */
	private function Search($request)
	{
		$core_data = $this->tbo_private->Search($request);//Normal Call
		$search_data = $core_data;
		$update_search_data = array();
		$search_type = META_AIRLINE_COURSE;
		if($search_data['Status'] == SUCCESS_STATUS) {
			$search_data = $search_data['data']['SearchResult'];
			$flight_data = $search_data['Flights'];
			$updated_flight_data = array();
			$journey_attributes = array();
			$journey_attributes['IsDomestic'] = $search_data['IsDomestic'];
			$journey_attributes['RoundTrip'] = $search_data['RoundTrip'];
			$journey_attributes['MultiCity'] = $search_data['MultiCity'];
			if($search_data['MultiCity'] == true) {
				$journey_attributes['MultiCitySegmentCount'] = $search_data['MultiCitySegmentCount'];
			}
			$journey_attributes['PassengerConfig'] = $search_data['PassengerConfig'];
			foreach($flight_data as $k => $v) {
				$updated_flight_data[$k] = $this->update_search_data($v, $journey_attributes);
			}
			$core_data['data']['SearchResult']['Flights'] = $updated_flight_data;//Assigning the Updated Data
		}	
		$this->custom_db->insert_record ( 'search_flight_history', array (
				'domain_origin' => get_domain_auth_id (),
				'search_type' => $search_type,
				'from_location' => $from_location,
				'to_location' => $to_location,
				'from_code' => $from_code,
				'to_code' => $to_code,
				'trip_type' => $trip_type,
				'journey_date' => $journey_date,
				'total_pax' => $total_pax,
				'created_by_id' => '0',
				'created_datetime' => date ( 'Y-m-d H:i:s' ) 
		) );	
		return $core_data;
	}
	/**
	 * Updates the search data
	 */
	private function update_search_data($FlightList, $journey_attributes)
	{
		$updated_list = array();
		foreach($FlightList as $k => $v){
			$update_fare = $this->update_fare_markup_commission($v['FareDetails'], $v['PassengerFareBreakdown'], $journey_attributes);
			$v['FareDetails'] = $update_fare['FareDetails'];
			$v['PassengerFareBreakdown'] = $update_fare['PassengerFareBreakdown'];
			$updated_list[$k] = $v;
		}
		return $updated_list;
	}


  /**
   * Sagar
   * update pnr details
   * @param unknown $request
   * @return string[]|unknown
   */
	function UpdatePNR($request){

		$response ['data'] = array ();
		$response ['Status'] = FAILURE_STATUS;
		$response ['Message'] = '';	

		if(isset($request['AppReference']) && !empty($request['AppReference'])){
				$this->load->model('flight_model');
				$page_data['data'] = $this->flight_model->check_details_update_pnr($request['AppReference']);
				$resulted_data   = $this->booking_data_formatter->format_flight_booking_data($page_data['data'],'admin');			
				if($page_data['data']['status'] == SUCCESS_STATUS){
					$response ['Status'] = SUCCESS_STATUS;
					$response ['data']['AppReference'] = $resulted_data['data']['booking_details'][0]['app_reference'];
					$response ['data']['MasterBookingStatus'] = $resulted_data['data']['booking_details'][0]['status'];
					$get_transaction_details =   $this->format_flight_booking_transaction_details($resulted_data['data']['booking_details'][0]['booking_transaction_details']);
					$get_response_booking_itinerary = $this->format_booking_itinerary_details($resulted_data['data']['booking_details'][0]['booking_itinerary_details']);				
					
					$response ['data']['UpdatePNR']['BoookingTransaction'] = $get_transaction_details['BookingTransaction'];
					$response ['data']['UpdatePNR']['BookingItineraryDetails'] = $get_response_booking_itinerary;
				
				}
			
		}
		//$this->add_test_data($response,true);
		return $response;	
	}
/*
 *Sagar Wakchaure
 *format format_flight_booking_transaction_details
 *
 */
 function format_flight_booking_transaction_details($data = array()){	
      $response['BookingTransaction'] = array();
      $cust_array = array(); 
      foreach ($data as $key => $value) {
      	$response['BookingTransaction'][$key]['PNR'] = $value['pnr'];
      	$response['BookingTransaction'][$key]['BookingID'] = $value['book_id'];
      	$response['BookingTransaction'][$key]['SequenceNumber'] = $value['sequence_number'];
      	$response['BookingTransaction'][$key]['Status'] = $value['status'];     	 
      	  foreach ($value['booking_customer_details'] as $k=>$b_d){
      	  	$cust_array['cust'][$k]['TicketId'] = $b_d['TicketId'];
      	  	$cust_array['cust'][$k]['Status'] = $b_d['status'];
      	  	$cust_array['cust'][$k]['TicketNumber'] = $b_d['TicketNumber'];    	  	
      	  }
      	  $response['BookingTransaction'][$key]['BookingCustomer'] = $cust_array['cust'];
      	 
      	  
      }
      return $response;
 }
 

 
 /**
  * format format_booking_itinerary_details
  * @param array $data
  * @return unknown
  */
 function format_booking_itinerary_details($data = array()){
 
 	$response = array();
 	foreach ($data as $key => $value) {
 		$response[$key]['AirlinePNR'] = $value['airline_pnr'];
 		$response[$key]['FromAirlineCode'] = $value['from_airport_code'];
 		$response[$key]['ToAirlineCode'] = $value['to_airport_code'];
 		$response[$key]['DepartureDatetime'] = $value['departure_datetime'];
 	}
 	return $response;
 }

	/**
	 * FareRule
	 * @param unknown_type $request
	 */
	private function FareRule($request)
	{
		$data = $this->tbo_private->FareRule($request);
		return $data;
	}
	/**
	 * FareQuote
	 * @param unknown_type $request
	 */
	private function FareQuote($request)
	{
		$this->domain_management_model->create_track_log (self::$track_log_id, 'FareQuote - Started - Flight' );
		$core_data = $this->tbo_private->FareQuote($request);
		$farequote_data = $core_data;
		if($farequote_data['Status'] == true) {
			$journey_attributes = $farequote_data['ProvabData']['JourneyAttributes'];
			$farequote_data = $farequote_data['data']['UpdatedFlightFareDetails'];
			$flight_data = $farequote_data['FlightDetails'];
			$updated_data = array();
			foreach($flight_data as $k => $v){
				$update_fare = $this->update_fare_markup_commission($v['FareDetails'], $v['PassengerFareBreakdown'], $journey_attributes);
				$v['FareDetails'] = $update_fare['FareDetails'];
				$v['PassengerFareBreakdown'] = $update_fare['PassengerFareBreakdown'];
				$updated_data[$k] = $v;
			}
			$core_data['data']['UpdatedFlightFareDetails']['FlightDetails'] = $updated_data;//Assigning the Updated Data
		}
		$comments = array('FareQuote - Completed - Flight', $core_data);
		$comments = json_encode($comments);
		$this->domain_management_model->create_track_log (self::$track_log_id, $comments);
		return $core_data;
	}
	/**
	 * Book Method
	 * @param unknown_type $request
	 */
	private function Book($request)
	{
		
		$data['Status'] = FAILURE_STATUS;
		$data['data'] = '';
		$data['Message'] = '';
		//FIXME: Update Fare Details
		$this->domain_management_model->create_track_log (self::$track_log_id, 'Book - Started - Flight' );
		$ProvabAuthKey = $request['ProvabAuthKey'];
		$AppReference = trim(@$request['AppReference']);
		$SequenceNumber = trim(@$request['SequenceNumber']);
		//FOLLOWING PROCESS IN BOOK MEHOD
		//1.Checking ProvabAuthKey
		//2.Verify Domain Balance
		//3.Saving data
		//4.API CALL
		$booking_fare_details = $this->tbo_private->extract_booking_fare_details($ProvabAuthKey);
		if($booking_fare_details['Status'] == SUCCESS_STATUS) {//Checking ProvabAuthKey
				$booking_fare_details = $booking_fare_details['data'];
				$booking_transaction_amount = $this->get_booking_transaction_amount($booking_fare_details);
				if($this->domain_management->verify_domain_balance($booking_transaction_amount, self::$credential_type) == SUCCESS_STATUS) {//Verify Domain Balance
					//If Not set add the App Reference
					if(empty($AppReference) == true){
						$AppReference = 'PB-'.time().rand(1, 50000);// Unique Refrence Number
					}
					$this->set_app_reference($AppReference);
					//Save the Booking
					$booking_save_status = $this->save_booking($request, $AppReference);
					if($booking_save_status['Status'] == SUCCESS_STATUS){//Checking Duplicate Booking
						//API CALL
						$data = $this->tbo_private->Book($request);
						$this->update_gds_pnr($data, $AppReference, $SequenceNumber);
						
						//Notification
						$this->booking_not_confirmed_notification($AppReference, $SequenceNumber, 'BOOK');
					} else {
						//IF BOOKING ALREADY EXISTS WITH SAME APP REFERENCE
						$data = $booking_save_status;
					}//Checking Duplicate Booking Ends
					
				} else {
					$data['Message'] = 'In Sufficiant Balance';
				}//Verify Domain Balance Ends
		} else {
			$data['Message'] = $booking_fare_details['Message'];
		}//Checking ProvabAuthKey Ends
		
		//Track Log
		$comments = array('Book - Completed - Flight', $data);
		$comments = json_encode($comments);
		$this->domain_management_model->create_track_log (self::$track_log_id, $comments);
		
		return $data;
	}
	/**
	 * Ticket Method
	 * @param $request
	 */
	private function Ticket($request)
	{
		$data['Status'] = FAILURE_STATUS;
		$data['data'] = '';
		$data['Message'] = '';
		//TODO: change the balance deduction process
		$this->domain_management_model->create_track_log (self::$track_log_id, 'Ticket - Started - Flight' );
		$ProvabAuthKey = $request['ProvabAuthKey'];
		$AppReference = trim(@$request['AppReference']);
		$SequenceNumber = trim(@$request['SequenceNumber']);
		//FOLLOWING PROCESS IN BOOK MEHOD
		//1.Checking ProvabAuthKey
		//2.Verify Domain Balance
		//3.Saving data
		//4.API CALL
		$booking_fare_details = $this->tbo_private->extract_booking_fare_details($ProvabAuthKey);
		if($booking_fare_details['Status'] == SUCCESS_STATUS) {//Checking ProvabAuthKey
				$booking_fare_details = $booking_fare_details['data'];
				$booking_transaction_amount = $this->get_booking_transaction_amount($booking_fare_details);
				if($this->domain_management->verify_domain_balance($booking_transaction_amount, self::$credential_type) == SUCCESS_STATUS) {//Verify Domain Balance
					//If Not set add the App Reference
					if(empty($AppReference) == true){
						$AppReference = 'PB-'.time().rand(1, 50000);// Unique Refrence Number
					}
					$this->set_app_reference($AppReference);
					//Save the Booking
					$booking_flight_details = $this->tbo_private->extract_booking_flight_details($ProvabAuthKey);
					if($booking_flight_details['data']['IsLCC'] == true){
						//For Non-LCC Flights, Data is saved in Book Method
						$booking_save_status = $this->save_booking($request, $AppReference);
					} else {
						$booking_save_status['Status'] = SUCCESS_STATUS;
					}
					if($booking_save_status['Status'] == SUCCESS_STATUS){//Checking Duplicate Booking
						try {
							//API CALL
							$data = $this->tbo_private->Ticket($request);
							if($data['Status'] != SUCCESS_STATUS) {
								$data['Status'] = BOOKING_FAILED;
							}
						} catch (Exception $e) {
							$data['Status'] = BOOKING_ERROR;
						}
                                                
                                                
						if($data['Status'] == BOOKING_ERROR || $data['Status'] == SUCCESS_STATUS) {
							//Only On Success Status OR Network Error Balance will be deducted
							$domain_booking_attr = array();
							$domain_booking_attr['app_reference'] = $AppReference;
							$domain_booking_attr['transaction_type'] = 'flight';
							$this->domain_management->debit_domain_balance($booking_transaction_amount, self::$credential_type, get_domain_auth_id(), $domain_booking_attr);//deduct the domain balance
						}
                                                
                                                
                                                $temp_confirm = array();
                                                $temp_confirm['app_reference'] = $AppReference;
                                                $temp_confirm['SequenceNumber'] = $SequenceNumber;//FIXME:What data has to be stored here?
                                                $temp_confirm['status'] = $data['Status'];
                                                $temp_confirm['desc'] = json_encode($data);
                                                $temp_confirm['created_datetime'] = date('Y-m-d H:i:s');
                                                $temp_confirm['function_details'] = "Ticket";
                                                $this->custom_db->insert_record('temp_confirmation',$temp_confirm);
                                                
                                                
                                                
						//Update the Booking details and Status
						$this->update_booking_details($data, $AppReference, $SequenceNumber);
						if($data['Status'] == SUCCESS_STATUS) {
							//Updating the fare details
							$api_ticket_details = $data['data']['TicketDetails'];
							$api_fare_details = $api_ticket_details['FareDetails'];
							$JourneyAttributes = $this->get_booked_journey_attributes($api_ticket_details);
							//Updateing the Fare detsil
							$update_fare_details = $this->update_fare_markup_commission($api_fare_details, array(), $JourneyAttributes, true);
							$data['data']['TicketDetails']['FareDetails'] = $update_fare_details['FareDetails'];
						}
						//Notification
						$this->booking_not_confirmed_notification($AppReference, $SequenceNumber);
					} else {
						//IF BOOKING ALREADY EXISTS WITH SAME APP REFERENCE
						$data = $booking_save_status;
					}//Checking Duplicate Booking Ends
					
				} else {
					$data['Message'] = 'In Sufficiant Balance';
				}//Verify Domain Balance Ends
		} else {
			$data['Message'] = $booking_fare_details['Message'];
		}//Checking ProvabAuthKey Ends
		
		//Track Log
		$comments = array('Ticket - Completed - Flight', $data);
		$comments = json_encode($comments);
		$this->domain_management_model->create_track_log (self::$track_log_id, $comments);
		
		return $data;
	}
	/**
	 * Booking Details
	 * @param $request
	 */
	private function GetBookingDetails($request)
	{
		$this->domain_management_model->create_track_log (self::$track_log_id, 'GetBookingDetails - Started - Flight' );
		$data = $this->tbo_private->GetBookingDetails($request);
		//Updating the fare details
		$api_booking_details = $data['data']['FlightItinerary'];
		$api_fare_details = $api_booking_details['FareDetails'];
		$JourneyAttributes = $this->get_booked_journey_attributes($api_booking_details);
		//Updateing the Fare detsil
		$update_fare_details = $this->update_fare_markup_commission($api_fare_details, array(), $JourneyAttributes, true);
		$data['data']['FlightItinerary']['FareDetails'] = $update_fare_details['FareDetails'];
		//Track Log
		$comments = array('GetBookingDetails - Completed - Flight', $data);
		$comments = json_encode($comments);
		$this->domain_management_model->create_track_log (self::$track_log_id, $comments);
		
		return $data;
	}
	/**
	 * Send the cancellation Request
	 * @param unknown_type $request
	 */
	private function SendChangeRequest($request)
	{
		$this->domain_management_model->create_track_log (self::$track_log_id, 'SendChangeRequest - Started - Flight' );
		if(isset($request['AppReference']) == true && empty($request['AppReference']) == false){
			$AppReference = 	trim($request['AppReference']);
			$SequenceNumber = 	trim($request['SequenceNumber']);
			$BookingId = 		trim($request['BookingId']);
			$PNR = 				trim($request['PNR']);
			//Update the Old Booking Appreference
			$domain_origin = get_domain_auth_id();
			$this->flight_model->update_old_booking_app_reference($AppReference, $BookingId, $PNR, $domain_origin);
			//API CALL
			$data = $this->tbo_private->SendChangeRequest($request);
			
			if($data['Status'] == SUCCESS_STATUS){
				//Sending Notifications
				$booking_details = $this->get_flight_booking_transaction_details($AppReference, $SequenceNumber);
				
				if($booking_details['status'] == SUCCESS_STATUS){
					$booking_details = $booking_details['data'];
					$master_booking_details  =$booking_details['booking_details'][0];
					$domain_name = $master_booking_details['domain_name'];
					$booking_transaction_details  =$booking_details['booking_transaction_details'][0];
					$booking_customer_details  =$booking_details['booking_customer_details'];
					
					$ticket_ids = $request['TicketId'];
					$passenger_ticket_details = $this->get_cancellation_reequested_pax_details($booking_customer_details, $ticket_ids);
						
					$ticket_cancel_request = array();
					$ticket_cancel_request['domain_name'] = $domain_name;
					$ticket_cancel_request['booking_transaction_details'] = $booking_transaction_details;
					$ticket_cancel_request['passenger_ticket_details'] = $passenger_ticket_details;
					
					//Send SMS to tmx support team
					$sms_template = $this->load->view('flight/ticket_cancel_request_sms_template', $ticket_cancel_request,true);
					send_alert_sms($sms_template);
					
					//Send mail to tmx support team
					$mail_template = $this->load->view('flight/ticket_cancel_request_template', $ticket_cancel_request,true);
					
					$email = $this->config->item('alert_email_id');
					$subject = ucfirst($domain_name).' - Flight Ticket Cancellation Request';
					$this->load->library('provab_mailer');
					
					$this->provab_mailer->send_mail($email, $subject,$mail_template);
				}
			}
			
		} else{
			$data['Status'] = FAILURE_STATUS;
			$data['Message'] = 'Invalid Parameters';
		}
		//Track Log
		$comments = array('SendChangeRequest - Completed - Flight', $data);
		$comments = json_encode($comments);
		$this->domain_management_model->create_track_log (self::$track_log_id, $comments);
		return $data;
	}
		/**
	 * 
	 * Cancellation Requested Passenger Details
	 * @param unknown_type $booking_customer_details
	 * @param unknown_type $passenger_origins
	 */
	private function get_cancellation_reequested_pax_details($booking_customer_details, $ticket_ids)
	{
		$cancellation_reequested_pax_details = array();
		//Indexing passenger origin with status
		$index_passenger_orign = array();
		foreach ($booking_customer_details as $pax_k => $pax_v){
			$index_passenger_orign[$pax_v['TicketId']] = $pax_v;
		}
		foreach ($ticket_ids as $k => $v){
			if(isset($index_passenger_orign[$v]) == true){
				$cancellation_reequested_pax_details[$k] = $index_passenger_orign[$v];
			}
		}
		return $cancellation_reequested_pax_details;
	}
	/**
	 * Get Cancellation Request Status
	 * @param unknown_type $request
	 */
	private function GetChangeRequestStatus($request)
	{
		$this->domain_management_model->create_track_log (self::$track_log_id, 'GetChangeRequestStatus - Started - Flight' );
		$cancellation_details = array();
		$data = array();
		$AppReference = 	trim($request['AppReference']);
		$SequenceNumber = 	trim($request['SequenceNumber']);
		$BookingId = 		trim($request['BookingId']);
		$PNR = 				trim($request['PNR']);
		$TicketId = 		trim($request['TicketId']);
		$ChangeRequestId =	trim($request['ChangeRequestId']);
		$cancellation_details = $this->tbo_private->GetChangeRequestStatus($request);
		if($cancellation_details['Status'] == SUCCESS_STATUS){
			$temp_cancel_details = $cancellation_details['data']['TicketCancellationtDetails'];
			$cancellation_details['data']['TicketCancellationtDetails']['StatusDescription'] = $this->get_cancellation_status_description($temp_cancel_details['ChangeRequestStatus']);
			$this->flight_model->update_cancellation_details($AppReference, $SequenceNumber, $BookingId, $PNR, $TicketId, $ChangeRequestId, $cancellation_details);
			//Assigning Required data
			$TicketCancellationtDetails['TicketId'] = 				$TicketId;
			$TicketCancellationtDetails['ChangeRequestId'] = 		$cancellation_details['data']['TicketCancellationtDetails']['ChangeRequestId'];
			$TicketCancellationtDetails['ChangeRequestStatus'] =	$cancellation_details['data']['TicketCancellationtDetails']['ChangeRequestStatus'];
			$TicketCancellationtDetails['StatusDescription'] = 		$cancellation_details['data']['TicketCancellationtDetails']['StatusDescription'];
			$data['Status'] = $cancellation_details['Status'];
			$data['Message'] = $cancellation_details['Message'];
			$data['data']['TicketCancellationtDetails'] = $TicketCancellationtDetails;
		} else{
			$data = $cancellation_details;
		}
		//Track Log
		$comments = array('GetChangeRequestStatus - Completed - Flight', $data);
		$comments = json_encode($comments);
		$this->domain_management_model->create_track_log (self::$track_log_id, $comments);
		return $data;
	}
	/**
	 * Returns Cancellation status description
	 */
	private function get_cancellation_status_description($ChangeRequestStatus)
	{
		$description = '';
		//NotSet = 0,Unassigned = 1,Assigned = 2,Acknowledged = 3,Completed = 4,Rejected = 5,Closed = 6,Pending = 7,Other = 8
		switch($ChangeRequestStatus){
			case 1: $description = 'Unassigned';
				break;
			case 2: $description = 'Assigned';
				break;
			case 3: $description = 'Acknowledged';
				break;
			case 4: $description = 'Completed';
				break;
			case 5: $description = 'Rejected';
				break;
			case 6: $description = 'Closed';
				break;
			case 7: $description = 'Pending';
				break;
			case 7: $description = 'Other';
				break;
			default:$description = 'NotSet';
		}
		return $description;
	}
	/**
	 * Jaganath
	 * @param unknown_type $request
	 */
	private function TicketRefundDetails($request)
	{
		$data = array();
		$data['Status'] = FAILURE_STATUS;
		$data['Message'] = '';
		$app_reference = $request['AppReference'];
		$sequence_number = $request['SequenceNumber'];
		$booking_id = $request['BookingId'];
		$pnr = $request['PNR'];
		$ticket_id= $request['TicketId'];
		$change_request_id = $request['ChangeRequestId'];
		$booking_details = $this->flight_model->get_passenger_ticket_info($app_reference, $sequence_number, $booking_id, $pnr, $ticket_id);
		if($booking_details['status'] == true){
			$booking_details = $booking_details['data'];
			$master_booking_details = $booking_details['booking_details'][0];
			$booking_customer_details = $booking_details['booking_customer_details'][0];
			$cancellation_details = $booking_details['cancellation_details'][0];
			$currency_conversion_rate = $cancellation_details['currency_conversion_rate'];
			$TicketRefundDetails = array();
			$TicketRefundDetails['AppReference'] = $master_booking_details['app_reference'];
			$TicketRefundDetails['TicketId'] = $booking_customer_details['TicketId'];
			$TicketRefundDetails['ChangeRequestId'] = $cancellation_details['RequestId'];
			$TicketRefundDetails['ChangeRequestStatus'] = $cancellation_details['ChangeRequestStatus'];
			$TicketRefundDetails['StatusDescription'] = $cancellation_details['statusDescription'];
			$TicketRefundDetails['RefundStatus'] = $cancellation_details['refund_status'];
			$TicketRefundDetails['RefundedAmount'] = 	($cancellation_details['refund_amount']*$currency_conversion_rate);
			$TicketRefundDetails['CancellationCharge'] = ($cancellation_details['cancellation_charge']*$currency_conversion_rate);
			$TicketRefundDetails['ServiceTaxOnRefundAmount'] = ($cancellation_details['service_tax_on_refund_amount']*$currency_conversion_rate);
			$TicketRefundDetails['SwachhBharatCess'] = ($cancellation_details['swachh_bharat_cess']*$currency_conversion_rate);
			$data['Status'] = SUCCESS_STATUS;
			$data['data']['RefundDetails'] = $TicketRefundDetails;
		}
		return $data;
	}	
	/**
	 * Calendar Fare
	 * @param unknown_type $request
	 */
	private function GetCalendarFare($request)
	{
		$data = $this->tbo_private->GetCalendarFare($request);
		if($data['Status'] == SUCCESS_STATUS){
			$data['data']['CalendarFareDetails'] = $this->update_calendarfare_currency($data['data']['CalendarFareDetails']);
		}
		return $data;
	}
	private function UpdateCalendarFareOfDay($request)
	{
		//FIXME: Update Fare Details
		$data = $this->tbo_private->UpdateCalendarFareOfDay($request);
		if($data['Status'] == SUCCESS_STATUS){
			$data['data']['CalendarFareDetails'] = $this->update_calendarfare_currency($data['data']['CalendarFareDetails']);
		}
		return $data;
	}
	/**
	 * Converts CalendarFare details to Domain Currency
	 * @param unknown_type $FareDetails
	 */
	function update_calendarfare_currency($FareDetails)
	{
		$CalendarFareDetails = array();
		$currency_obj = new Currency(array('from' => get_application_default_currency() , 'to' => domain_base_currency()));
		foreach($FareDetails as $k => $v){
			//Converting the API Fare Currency to Domain Currency
			$CalendarFareDetails[$k] = $v;
			$CalendarFareDetails[$k]['Fare'] = 			get_converted_currency_value($currency_obj->force_currency_conversion($v['Fare']));
			$CalendarFareDetails[$k]['BaseFare'] = 		get_converted_currency_value($currency_obj->force_currency_conversion($v['BaseFare']));
			$CalendarFareDetails[$k]['Tax'] = 			get_converted_currency_value($currency_obj->force_currency_conversion($v['Tax']));
			$CalendarFareDetails[$k]['OtherCharges'] = 	get_converted_currency_value($currency_obj->force_currency_conversion($v['OtherCharges']));
			$CalendarFareDetails[$k]['FuelSurcharge'] =	get_converted_currency_value($currency_obj->force_currency_conversion($v['FuelSurcharge']));
		}
		return $CalendarFareDetails;
	}
	/**
	 * Booked Journey Details
	 */
	public function get_booked_journey_attributes($booking_details)
	{
		$journey_attributes = array();
		$RoundTrip = false;
		$MultiCity = false;
		$MultiCitySegmentCount = 0;
		
		$temp_segments = $booking_details['SegmentDetails'];
		$last_temp_segment = end(end($temp_segments));
		$TripIndicator = $last_temp_segment['TripIndicator'];
		if(count($temp_segments) >= 2 && $TripIndicator == 1) {
			$MultiCity = true;
			$MultiCitySegmentCount = count($temp_segments);
		} else if(count($temp_segments) == 2 && $TripIndicator == 2) {
			$RoundTrip = true;//International Roundway
		}
		//Passenger Count
		$passenger_config = array();
		$TotalPassenger = 0;
		$passenger_config['Adult'] = 0;
		$passenger_config['Child'] = 0;
		$passenger_config['Infant'] = 0;
		$TotalPassenger = count($booking_details['PassengerDetails']);
		$passenger_config['TotalPassenger'] = $TotalPassenger;
		$journey_attributes['IsDomestic'] = $this->is_domestic_journey($temp_segments);
		$journey_attributes['RoundTrip'] = $RoundTrip;
		$journey_attributes['MultiCity'] = $MultiCity;
		$journey_attributes['MultiCitySegmentCount'] = $MultiCitySegmentCount;
		$journey_attributes['PassengerConfig'] = $passenger_config;
		return $journey_attributes;
	}
	/**
	 * Checks Is Domestic Journey
	 * Enter description here ...
	 */
	private function is_domestic_journey($segments)
	{
		$IsDomestic = true;
		foreach ($segments as $fp_k => $fp_v){
			foreach ($fp_v as $sg_k => $sg_v){
				$from_loc =	$sg_v['OriginDetails']['AirportCode'];
				$to_loc = 	$sg_v['DestinationDetails']['AirportCode'];
				$is_domestic = $this->flight_model->is_domestic_flight($from_loc, $to_loc);
				if($is_domestic == false){//If International
					$IsDomestic = false;
					return $IsDomestic;
				}
			}
		}
		return $IsDomestic;
	}
	/**
	 * Jaganath
	 * Checks is it a duplcaite booking
	 * @param unknown_type $request
	 */
	private function is_duplicate_booking($request)
	{
		$data['Status'] = SUCCESS_STATUS;
		$data['Message'] = '';
		$AppReference = trim(@$request['AppReference']);
		$SequenceNumber = intval(@$request['SequenceNumber']);
		$flight_booking_details = $this->custom_db->single_table_records('flight_booking_transaction_details', '*', array('app_reference' => $AppReference, 'sequence_number' => $SequenceNumber));
		
		if($flight_booking_details['status'] == true && valid_array($flight_booking_details['data'][0]) == true){
			$flight_booking_details = $flight_booking_details['data'][0];
			$pnr = trim($flight_booking_details['pnr']);
			if(empty($pnr) == false){
				$Message = 'Booking Already Done with PNR: '.$pnr;
			} else {
				$Message = 'Duplicate Booking Not Allowed';
			}
			$data['Status'] = FAILURE_STATUS;
			$data['Message'] = $Message;
		}
		return $data;
	}
	/**
	 * Save Booking Details
	 */
	private function save_booking($booking_params, $app_reference)
	{
		$data['Status'] = SUCCESS_STATUS;
		$data['Message'] = '';
		$porceed_to_save = $this->is_duplicate_booking($booking_params);
		if($porceed_to_save['Status'] != SUCCESS_STATUS){
			$data['Status'] = $porceed_to_save['Status'];
			$data['Message'] = $porceed_to_save['Message'];
		} else {
			$ProvabAuthKey = $booking_params['ProvabAuthKey'];
			//Extracting the Flight Booking details based on ProvabAuth Key
			$booking_flight_details = $this->tbo_private->extract_booking_flight_details($ProvabAuthKey);
			$domain_origin = get_domain_auth_id ();
			$booking_flight_details = $booking_flight_details['data'];
			$passenger_details = $booking_params['Passengers'];
			$app_reference = trim($app_reference);
			$booking_source = TBO_FLIGHT_BOOKING_SOURCE;
			$master_booking_status = 'BOOKING_INPROGRESS';
			$flight_booking_status = $master_booking_status;
			$transaction_status = $master_booking_status;
			$transaction_description = '';
			//Extracting the Booking Details
			
			//$booking_id = $ticket_details['BookingId'];
			//$pnr = $ticket_details['PNR'];
			$currency = domain_base_currency();
			$currency_obj = new Currency(array('module_type' => 'b2c_flight'));
			$currency_conversion_rate = $currency_obj->get_domain_currency_conversion_rate();
			
			$is_lcc = $booking_flight_details['IsLCC'];
			$source = $booking_flight_details['Source'];
			$api_fare_details = $booking_flight_details['FareDetails'];
			$core_segment_details = $booking_flight_details['SegmentDetails'];
			
			$segment_details = $booking_flight_details['SegmentDetails'][0];
			//Fare Details
			//Calculation
			$JourneyAttributes = $booking_flight_details['JourneyAttributes'];
			$total_fare = $api_fare_details['PublishedFare'];
			$multiplier = $this->get_markup_multiplier($JourneyAttributes);
			$markup_price = $currency_obj->get_currency($total_fare, true, true, false, $multiplier);
			$domain_markup = ($markup_price['default_value']-$total_fare);
			$level_one_markup = 0;
			//Commission and TDS
			$core_commision = ($api_fare_details['PublishedFare']-$api_fare_details['OfferedFare']);
			$agent_commission = (float)$this->update_agent_commision($core_commision);
			$admin_commission = (float)($core_commision-$agent_commission);
			
			$agent_tds = (float)$currency_obj->calculate_tds($agent_commission);
			$admin_tds = (float)$currency_obj->calculate_tds($admin_commission);
			//Contact Details
			$phone = $passenger_details[0]['ContactNo'];
			$alternate_number = '';
			$email = $passenger_details[0]['Email'];
			//Journey Details
			$destination_details = end($segment_details);
			$last_segment_details = end(end($core_segment_details));
			//debug($last_segment_details);exit;
			$journey_start = $segment_details[0]['OriginDetails']['DepartureTime'];
			$journey_end = $last_segment_details['DestinationDetails']['ArrivalTime'];
			$journey_from = $segment_details[0]['OriginDetails']['CityCode'];
			$journey_to = $destination_details['DestinationDetails']['CityCode'];
			$payment_mode = 'PNHB1';
			$ref_id = 0;
			$booking_details_attributes = array('JourneyAttributes' => $JourneyAttributes);
			$created_by_id = 0;
			$transaction_details_attributes = '';
			//Booking Details
			$this->flight_model->save_flight_booking_details ( $domain_origin, $flight_booking_status, $app_reference, $booking_source, $is_lcc, $currency, $phone, $alternate_number, $email, $journey_start, $journey_end, $journey_from, $journey_to, $payment_mode, json_encode($booking_details_attributes), $created_by_id,$currency_conversion_rate, FLIGHT_VERSION_1);
			//Transaction Details
			if (isset($booking_params['SequenceNumber']) === false) {
				$sequence_number = 0;
			} else {
				$sequence_number = $booking_params['SequenceNumber'];
			}
			$pnr = '';
			$booking_id = '';
			$transaction_insert_id = $this->flight_model->save_flight_booking_transaction_details ( $app_reference, $transaction_status, $transaction_description, $pnr, $booking_id, $source, $ref_id, 
									json_encode($transaction_details_attributes), $sequence_number, $total_fare, $domain_markup, $admin_commission, $agent_commission, $admin_tds, $agent_tds);
			$flight_booking_transaction_details_fk = $transaction_insert_id['insert_id'];
			//Passenger Details
			foreach($passenger_details as $pax_k => $pax_v) {
				$passenger_type = $pax_v['PaxType'];
				$is_lead = $pax_v['IsLeadPax'];
				$title = $pax_v['Title'];
				$first_name = $pax_v['FirstName'];
				$middle_name = '';
				$last_name = $pax_v['LastName'];
				$date_of_birth = $pax_v['DateOfBirth'];
				$gender = ($pax_v['Gender'] == 1 ? 'Male': 'Female');
				$passenger_nationality = $pax_v['CountryName'];
				$passport_number = $pax_v['PassportNumber'];
				$passport_issuing_country = '';
				$passport_expiry_date = $pax_v['PassportExpiry'];
				$status = $master_booking_status;
				//Attributes
				$passenger_attributes = array();
				$passenger_insert_id = $this->flight_model->save_flight_booking_passenger_details($app_reference, $passenger_type, $is_lead, $title, $first_name, $middle_name, $last_name, $date_of_birth, $gender, $passenger_nationality, $passport_number, $passport_issuing_country, $passport_expiry_date, 
				$status, json_encode($passenger_attributes), $flight_booking_transaction_details_fk);
				//Passenger Ticket Details
				$passenger_fk = $passenger_insert_id['insert_id'];
				$GLOBALS['CI']->flight_model->save_passenger_ticket_info($passenger_fk);
			}
			//Itinerary(Segment) Details
			foreach ($core_segment_details as $segment_k => $segment_v) {
				foreach($segment_v as $ws_key => $ws_val) {
					$AirlineDetails = $ws_val['AirlineDetails'];
					$OriginDetails = $ws_val['OriginDetails'];
					$DestinationDetails = $ws_val['DestinationDetails'];
					$segment_indicator = $ws_val['SegmentIndicator'];
					$airline_code = 		$AirlineDetails['AirlineCode'];
					$airline_name = 		$AirlineDetails['AirlineName'];
					$flight_number = 		$AirlineDetails['FlightNumber'];
					$fare_class = 			$AirlineDetails['FareClass'];
					$operating_carrier =	$AirlineDetails['OperatingCarrier'];
					$from_airport_code = 	$OriginDetails['AirportCode'];
					$from_airport_name = 	$OriginDetails['AirportName'];
					$to_airport_code = 		$DestinationDetails['AirportCode'];
					$to_airport_name = 		$DestinationDetails['AirportName'];
					$departure_datetime = 	$OriginDetails['DepartureTime'];
					$arrival_datetime = 	$DestinationDetails['ArrivalTime'];
					$iti_status = 			'';
					//Attributes
					$itinerary_attributes['TripIndicator'] = $ws_val['TripIndicator'];
					$itinerary_attributes['AirlinePNR'] = @$ws_val['AirlinePNR'];
					$itinerary_attributes['SegmentDuration'] = $ws_val['SegmentDuration'];
					$itinerary_attributes['Craft'] = $ws_val['Craft'];
					$itinerary_attributes['IsETicketEligible'] = $ws_val['IsETicketEligible'];
					$itinerary_attributes = $itinerary_attributes;
					$this->flight_model->save_flight_booking_itinerary_details( $app_reference, $segment_indicator, $airline_code, $airline_name, $flight_number, $fare_class, $from_airport_code, $from_airport_name, $to_airport_code, $to_airport_name, $departure_datetime, $arrival_datetime, $iti_status, $operating_carrier, json_encode($itinerary_attributes));
				}
			}
		}
		return $data;
	}
	/**
	 * Returns Markup Multiplier
	 */
	function get_markup_multiplier($JourneyAttributes)
	{
		$TotalPassenger = $JourneyAttributes['PassengerConfig']['TotalPassenger'];
		if(@$JourneyAttributes['IsDomestic'] == false && $JourneyAttributes['RoundTrip'] == true) {//FIXME: in FareQuote Method(International Roundway)
			//International Roundway
			$segment_count = 2;
		} else if($JourneyAttributes['MultiCity'] == true){
			//MultiCity
			$segment_count = $JourneyAttributes['MultiCitySegmentCount'];
		} else {
			$segment_count = 1;
		}
		$multiplier = ($segment_count*$TotalPassenger);
		return $multiplier;
	}
	/**
	 * Updates the GDS PNR and Booking Status to BOOKING_HOLD	 
	 */
	function update_gds_pnr($booking_details, $app_reference, $SequenceNumber)
	{
		
		if($data['Status'] = SUCCESS_STATUS) {
			$master_booking_status = 'BOOKING_HOLD';
			$ticket_details = array();
			$ticket_details = $booking_details;
			$ticket_details['data']['TicketDetails'] = $booking_details['data']['BookingDetails'];
			unset($ticket_details['BookingDetails']);
			$updated_details = $this->update_flight_booking_data($ticket_details, $app_reference, $SequenceNumber, $master_booking_status);
		} else if($data['Status'] != SUCCESS_STATUS) {
			//Updating the Booking status only on failure in Book Method
			$this->update_booking_details($booking_details, $app_reference);
		}
	}
	/**
	 * Update Booking Details
	 */
	private function update_booking_details($booking_details, $app_reference, $SequenceNumber = 0)
	{
		if($booking_details['Status'] == SUCCESS_STATUS){
			$master_booking_status = 'BOOKING_CONFIRMED';
			$updated_details = $this->update_flight_booking_data($booking_details, $app_reference, $SequenceNumber, $master_booking_status);
			//Updating the Transaction Details
			$agent_transaction_amount =	$updated_details['agent_transaction_amount'];
			$domain_markup = 			$updated_details['domain_markup'];
			$level_one_markup = 		$updated_details['level_one_markup'];
			$currency = 				$updated_details['currency'];
			$currency_conversion_rate =	$updated_details['currency_conversion_rate'];
			$remarks = 					'flight Transaction was Successfully done';
			$this->domain_management_model->save_transaction_details ( 'flight', $app_reference, $agent_transaction_amount, $domain_markup, $level_one_markup, $remarks, $currency, $currency_conversion_rate);
		} else {
			$master_booking_details = $GLOBALS['CI']->custom_db->single_table_records('flight_booking_details', 'status', array('app_reference' => trim($app_reference)));
			if($master_booking_details['status'] == true && in_array($master_booking_details['data'][0]['status'], array('BOOKING_HOLD')) == false){
				//On Failure Booking update the Booking Status to BOOKING_FAILED
				$master_booking_status = 'BOOKING_FAILED';
				$update_condition['app_reference'] =	$app_reference;
                                
                                # Update Condition for flight_booking_transaction_details using $SequenceNumber
                                $update_condition_fbtd['app_reference'] =	$app_reference;
                                $update_condition_fbtd['sequence_number'] =	$SequenceNumber;
                                
                                
				$update_data['status'] = 				$master_booking_status;
				//1.flight_booking_details
				$GLOBALS['CI']->custom_db->update_record('flight_booking_details', $update_data, $update_condition);
				//2.flight_booking_transaction_details
				$GLOBALS['CI']->custom_db->update_record('flight_booking_transaction_details', $update_data, $update_condition_fbtd);
				//3.flight_booking_passenger_details
				$GLOBALS['CI']->custom_db->update_record('flight_booking_passenger_details', $update_data, $update_condition);
			}
		}
	}
	/**
	 * Update flight booking data
	 * @param unknown_type $booking_details
	 * @param unknown_type $app_reference
	 * @param unknown_type $SequenceNumber
	 */
	function update_flight_booking_data($booking_details, $app_reference, $SequenceNumber,$master_booking_status = 'BOOKING_HOLD')
	{
		$data = array();
		$data['status'] = FAILURE_STATUS;
		if($booking_details['Status'] == SUCCESS_STATUS){
			$data['status'] = SUCCESS_STATUS;
			//On Success Booking update the Booking Details and Booking Status
			$ticket_details = $booking_details['data']['TicketDetails'];
			//Extracting the Booking Details
			$booking_id = $ticket_details['BookingId'];
			$pnr = $ticket_details['PNR'];
			$api_fare_details = $ticket_details['FareDetails'];
			$passenger_details = $ticket_details['PassengerDetails'];
			$segment_details = $ticket_details['SegmentDetails'][0];
			$currency = domain_base_currency();
			$currency_obj = new Currency(array('module_type' => 'b2c_flight'));
			//Fare Details
			//Calculation
			$JourneyAttributes = $this->get_booked_journey_attributes($ticket_details);
			$total_fare = $api_fare_details['PublishedFare'];
			$multiplier = $this->get_markup_multiplier($JourneyAttributes);
			$markup_price = $currency_obj->get_currency($total_fare, true, true, false, $multiplier);
			$domain_markup = ($markup_price['default_value']-$total_fare);
			$level_one_markup = 0;
			//Commission and TDS
			$core_commision = ($api_fare_details['PublishedFare']-$api_fare_details['OfferedFare']);
			$agent_commission = (float)$this->update_agent_commision($core_commision);
			$admin_commission = (float)($core_commision-$agent_commission);
			
			$agent_tds = (float)$currency_obj->calculate_tds($agent_commission);
			$admin_tds = (float)$currency_obj->calculate_tds($admin_commission);
			
			//1.flight booking details
			$GLOBALS['CI']->custom_db->update_record('flight_booking_details', array('status' => $master_booking_status), array('app_reference' => $app_reference));
			//2.Transaction Details
			$get_transaction_details_condition = array();
			$get_transaction_details_condition['app_reference'] = $app_reference;
			$get_transaction_details_condition['sequence_number'] = $SequenceNumber;
			$transaction_details_data = $GLOBALS['CI']->custom_db->single_table_records('flight_booking_transaction_details', '*', $get_transaction_details_condition);
			$transaction_details_origin = $transaction_details_data['data'][0]['origin'];
			$update_transaction_condition = array();
			$update_transaction_data = array();
			$update_transaction_condition['origin'] = $transaction_details_origin;
			$update_transaction_data['pnr'] = $pnr;
			$update_transaction_data['book_id'] = $booking_id;
			$update_transaction_data['status'] = $master_booking_status;
			$update_transaction_data['total_fare'] = $total_fare;
			$update_transaction_data['domain_markup'] = $domain_markup;
			
			$update_transaction_data['admin_commission'] = $admin_commission;
			$update_transaction_data['agent_commission'] = $agent_commission;
			$update_transaction_data['admin_tds'] = $admin_tds;
			$update_transaction_data['agent_tds'] = $agent_tds;
			$GLOBALS['CI']->custom_db->update_record('flight_booking_transaction_details', $update_transaction_data, $update_transaction_condition);
			
			//3.flight_booking_passenger_details
			$update_passenger_condition = array();
			$update_passenger_data = array();
			$update_passenger_condition['flight_booking_transaction_details_fk'] = $transaction_details_origin;
			$update_passenger_data['status'] = $master_booking_status;
			$GLOBALS['CI']->custom_db->update_record('flight_booking_passenger_details', $update_passenger_data, $update_passenger_condition);
			
			//4.Insert: Add Ticket details to flight_passenger_ticket_info
			$get_passenger_details_condition = array();
			$get_passenger_details_condition['flight_booking_transaction_details_fk'] = $transaction_details_origin;
			$passenger_details_data = $GLOBALS['CI']->custom_db->single_table_records('flight_booking_passenger_details', 'origin', $get_passenger_details_condition);
			$passenger_origins = group_array_column($passenger_details_data['data'], 'origin');
			foreach($passenger_details as $pax_k => $pax_v){
				$pax_ticket_details = $pax_v['Ticket'];
				if(valid_array($pax_ticket_details) == true){
					$passenger_fk = intval(array_shift($passenger_origins));
					$TicketId = $pax_ticket_details['TicketId'];
					$TicketNumber = $pax_ticket_details['TicketNumber'];
					$IssueDate = $pax_ticket_details['IssueDate'];
					$Fare = json_encode($pax_v['FareDetails']);
					$SegmentAdditionalInfo = json_encode($pax_v['SegmentAdditionalInfo']);
					$ValidatingAirline = $pax_ticket_details['ValidatingAirline'];
					$CorporateCode = '';
					$TourCode = '';
					$Endorsement = '';
					$Remarks = $pax_ticket_details['Remarks'];
					$ServiceFeeDisplayType = $pax_ticket_details['ServiceFeeDisplayType'];
					//SAVE PAX Ticket Details
					$GLOBALS['CI']->flight_model->update_passenger_ticket_info($passenger_fk, $TicketId, $TicketNumber, $IssueDate, $Fare,
					$SegmentAdditionalInfo,	$ValidatingAirline, $CorporateCode, $TourCode, $Endorsement, $Remarks, $ServiceFeeDisplayType);
				}
			}
			//5.Itinerary(Segment) Details
			//updating the AirlinePNR based on app_refrence, source, destination and departure date
			foreach ($segment_details as $segment_k => $segment_v) {
				$OriginDetails = $segment_v['OriginDetails'];
				$DestinationDetails = $segment_v['DestinationDetails'];
				//itinerary condition for update
				$update_itinerary_condition = array();
				$update_itinerary_condition['app_reference'] = 			$app_reference;
				$update_itinerary_condition['from_airport_code'] =		$OriginDetails['AirportCode'];
				$update_itinerary_condition['to_airport_code'] = 		$DestinationDetails['AirportCode'];
				$update_itinerary_condition['departure_datetime'] =	date('Y-m-d H:i:s', strtotime($OriginDetails['DepartureTime']));
				//itinerary updated data
				$update_itinerary_data = array();
				$update_itinerary_data['airline_pnr'] = @$segment_v['AirlinePNR'];
				$GLOBALS['CI']->custom_db->update_record('flight_booking_itinerary_details', $update_itinerary_data, $update_itinerary_condition);
			}
			$currency_conversion_rate = $currency_obj->get_domain_currency_conversion_rate();
			$agent_transaction_amount = ($total_fare+$agent_tds-$agent_commission);
			
			$data['agent_transaction_amount'] =	$agent_transaction_amount;
			$data['domain_markup'] =			$domain_markup;
			$data['level_one_markup'] =			$level_one_markup;
			$data['currency'] =					$currency;
			$data['currency_conversion_rate'] =	$currency_conversion_rate;
                        
                        
$temp_confirm = array();
$temp_confirm['app_reference'] = $app_reference;
$temp_confirm['SequenceNumber'] = $SequenceNumber;//FIXME:What data has to be stored here?
$temp_confirm['status'] = $master_booking_status;
$temp_confirm['desc'] = json_encode($data);
$temp_confirm['created_datetime'] = date('Y-m-d H:i:s');
$temp_confirm['function_details'] = "UPDATE";
$this->custom_db->insert_record('temp_confirmation',$temp_confirm);
                        
		}
		return $data;
	}
	/**
	 * Adding the Markup and Commission
	 */
	private function update_fare_markup_commission($FareDetails, $PassengerFareBreakdown = array(), $journey_attributes=array(), $domain_currency_conversion = true)
	{
		$data = array();
		//Fare Details
		$UpdatedFareDetails = array();
		//calculating Markup and commission
		$total_fare = $FareDetails['OfferedFare'];
		$TotalPassenger = $journey_attributes['PassengerConfig']['TotalPassenger'];
		$segment_count = 1;
		
		if(@$journey_attributes['IsDomestic'] == false && $journey_attributes['RoundTrip'] == true) {//FIXME: in FareQuote Method(International Roundway)
			//International Roundway
			$segment_count = 2;
		} else if($journey_attributes['MultiCity'] == true){
			//MultiCity
			$segment_count = $journey_attributes['MultiCitySegmentCount'];
		}
		$multiplier = ($segment_count*$TotalPassenger);
		$currency_obj = new Currency ( array ('module_type' => 'b2c_flight','from' => get_application_default_currency (),'to' => get_application_default_currency ()) );
		$markup_price = $currency_obj->get_currency($total_fare, true, true, false, $multiplier);
		$total_markup = ($markup_price['default_value']-$total_fare);
		
		$Tax = $this->update_tax($FareDetails['Tax'], $total_markup);
		$PublishedFare = $this->update_published_fare($FareDetails['PublishedFare'], $total_markup);
		
		$AgentCommission = $this->update_agent_commision($FareDetails['AgentCommission']);
		$PLBEarned = $this->update_agent_commision($FareDetails['PLBEarned']);
		$IncentiveEarned = $this->update_agent_commision($FareDetails['IncentiveEarned']);
		//$total_tbo_commision = $FareDetails['AgentCommission'] + $FareDetails['PLBEarned']; // Total Commision Given By TBO
		$total_tbo_commision = ($FareDetails['PublishedFare'] - $FareDetails['OfferedFare']); // Total Commision Given By TBO
		$OfferedFare = $this->update_offered_fare($FareDetails['OfferedFare'], $total_markup, $total_tbo_commision, $AgentCommission, $PLBEarned, $IncentiveEarned);
		
		$UpdatedFareDetails['Currency'] = 				$FareDetails['Currency'];
		$UpdatedFareDetails['BaseFare'] = 				$FareDetails['BaseFare'];
		$UpdatedFareDetails['Tax'] = 					$Tax;
		$UpdatedFareDetails['YQTax'] = 					$FareDetails['YQTax'];
		$UpdatedFareDetails['AdditionalTxnFeeOfrd'] = 	$FareDetails['AdditionalTxnFeeOfrd'];
		$UpdatedFareDetails['AdditionalTxnFeePub'] = 	$FareDetails['AdditionalTxnFeePub'];
		$UpdatedFareDetails['OtherCharges'] = 			$FareDetails['OtherCharges'];
		$UpdatedFareDetails['Discount'] = 				$FareDetails['Discount'];
		$UpdatedFareDetails['PublishedFare'] = 			$PublishedFare;
		$UpdatedFareDetails['AgentCommission'] = 		$AgentCommission;
		$UpdatedFareDetails['PLBEarned'] = 				$PLBEarned;
		$UpdatedFareDetails['IncentiveEarned'] = 		$IncentiveEarned;
		$UpdatedFareDetails['OfferedFare'] = 			$OfferedFare;
		$UpdatedFareDetails['TdsOnCommission'] = 		$currency_obj->calculate_tds($AgentCommission);
		$UpdatedFareDetails['TdsOnPLB'] = 				$currency_obj->calculate_tds($PLBEarned);
		$UpdatedFareDetails['TdsOnIncentive'] = 		$currency_obj->calculate_tds($IncentiveEarned);
		$UpdatedFareDetails['ServiceFee'] = 			$FareDetails['ServiceFee'];
		$UpdatedFareDetails['TotalBaggageCharges'] = 	$FareDetails['TotalBaggageCharges'];
		$UpdatedFareDetails['TotalMealCharges'] = 		$FareDetails['TotalMealCharges'];
		$UpdatedFareDetails['TotalSeatCharges'] = 		$FareDetails['TotalSeatCharges'];
		
		//Passenger Breakdown details
		$UpdatedFareBreakdown = array();
		if(valid_array($PassengerFareBreakdown) == true) {
			foreach($PassengerFareBreakdown as $k => $v){
				$UpdatedFareBreakdown[$k]['PassengerType'] = $v['PassengerType'];
				$UpdatedFareBreakdown[$k]['Count'] = $v['Count'];
				$UpdatedFareBreakdown[$k]['BaseFare'] = $v['BaseFare'];
			}
		}
		$data['FareDetails'] = $UpdatedFareDetails;
		if(valid_array($UpdatedFareBreakdown)) {
			$data['PassengerFareBreakdown'] = $UpdatedFareBreakdown;
		}
		$data = $this->convert_to_domain_currency_object($data, $domain_currency_conversion);
		return $data;
	}
	/**
	 * Convert Fare Object to Domain Currency
	 */
	private function convert_to_domain_currency_object($price_details, $domain_currency_conversion=true)
	{
		$master_price_details = array();
		$FareDetails = array();
		$PassengerFareBreakdown = array();
		if($domain_currency_conversion == true){
			$domain_base_currency = domain_base_currency();
		} else {
			$domain_base_currency = get_application_default_currency();
		}
		
		$core_fare = $price_details['FareDetails'];
		$core_passenger_breakdown =  @$price_details['PassengerFareBreakdown'];
		$currency_obj = new Currency(array('from' => get_application_default_currency() , 'to' => $domain_base_currency));
		//Converting the API Fare Currency to Domain Currency
		//FARE DETAILS
		$FareDetails['Currency'] = 				$domain_base_currency;
		$FareDetails['BaseFare'] = 				get_converted_currency_value($currency_obj->force_currency_conversion($core_fare['BaseFare']));
		$FareDetails['Tax'] = 					get_converted_currency_value($currency_obj->force_currency_conversion($core_fare['Tax']));
		$FareDetails['YQTax'] = 				get_converted_currency_value($currency_obj->force_currency_conversion($core_fare['YQTax']));
		$FareDetails['AdditionalTxnFeeOfrd'] =	get_converted_currency_value($currency_obj->force_currency_conversion($core_fare['AdditionalTxnFeeOfrd']));
		$FareDetails['AdditionalTxnFeePub'] = 	get_converted_currency_value($currency_obj->force_currency_conversion($core_fare['AdditionalTxnFeePub']));
		$FareDetails['OtherCharges'] = 			get_converted_currency_value($currency_obj->force_currency_conversion($core_fare['OtherCharges']));
		$FareDetails['Discount'] = 				get_converted_currency_value($currency_obj->force_currency_conversion($core_fare['Discount']));
		$FareDetails['PublishedFare'] = 		get_converted_currency_value($currency_obj->force_currency_conversion($core_fare['PublishedFare']));
		$FareDetails['AgentCommission'] = 		get_converted_currency_value($currency_obj->force_currency_conversion($core_fare['AgentCommission']));
		$FareDetails['PLBEarned'] = 			get_converted_currency_value($currency_obj->force_currency_conversion($core_fare['PLBEarned']));
		$FareDetails['IncentiveEarned'] =		get_converted_currency_value($currency_obj->force_currency_conversion($core_fare['IncentiveEarned']));
		$FareDetails['OfferedFare'] = 			get_converted_currency_value($currency_obj->force_currency_conversion($core_fare['OfferedFare']));
		$FareDetails['TdsOnCommission'] = 		get_converted_currency_value($currency_obj->force_currency_conversion($core_fare['TdsOnCommission']));
		$FareDetails['TdsOnPLB'] = 				get_converted_currency_value($currency_obj->force_currency_conversion($core_fare['TdsOnPLB']));
		$FareDetails['TdsOnIncentive'] = 		get_converted_currency_value($currency_obj->force_currency_conversion($core_fare['TdsOnIncentive']));
		$FareDetails['ServiceFee'] = 			get_converted_currency_value($currency_obj->force_currency_conversion($core_fare['ServiceFee']));
		$FareDetails['TotalBaggageCharges'] = 	get_converted_currency_value($currency_obj->force_currency_conversion($core_fare['TotalBaggageCharges']));
		$FareDetails['TotalMealCharges'] = 		get_converted_currency_value($currency_obj->force_currency_conversion($core_fare['TotalMealCharges']));
		$FareDetails['TotalSeatCharges'] = 		get_converted_currency_value($currency_obj->force_currency_conversion($core_fare['TotalSeatCharges']));
		
		//PASSENGER BREAKDOWN
		if(valid_array($core_passenger_breakdown) == true){
			foreach($core_passenger_breakdown as $pk => $pv){
				$PassengerFareBreakdown[$pk] = 				$pv;
				$PassengerFareBreakdown[$pk]['BaseFare'] =	get_converted_currency_value($currency_obj->force_currency_conversion($pv['BaseFare']));
			}
		}
		$master_price_details['FareDetails'] = $FareDetails;
		$master_price_details['PassengerFareBreakdown'] = $PassengerFareBreakdown;
		return $master_price_details;
	}
	/**
	 * Update the Tax
	 * @param unknown_type $Tax
	 * @param unknown_type $total_markup
	 */
	private function update_tax($Tax, $total_markup)
	{
		return ($Tax+$total_markup);
	}
	/**
	 * Update the Published Fare
	 * @param unknown_type $PublishedFare
	 * @param unknown_type $total_markup
	 */
	private function update_published_fare($PublishedFare, $total_markup)
	{
		return ($PublishedFare+$total_markup);
	}
	/**
	 * Update the Offererd Fare
	 * @param $OfferedFare
	 * @param $total_markup
	 * @param $total_tbo_commision
	 * @param $agent_commision
	 * @param $plb_earned
	 */
	private function update_offered_fare($OfferedFare, $total_markup, $total_tbo_commision, $agent_commision, $plb_earned, $incentive_earned=0)
	{
		return $OfferedFare + $total_markup + ($total_tbo_commision - ($agent_commision + $plb_earned+$incentive_earned));
	}
	/**
	 * FIXME: do it for plus and percentage
	 * Updates Agents Commission
	 * @param unknown_type $amount
	 */
	private function update_agent_commision($amount)
	{
		return (($amount * $this->domain_commission_percentage) / 100);
	}
	/**
	 * Stores API Requests
	 */
	private function store_api_request($request_type='', $request='')
	{
		$provab_api_request_history = array();
		$provab_api_request_history['request_type'] = $request_type;
		$provab_api_request_history['header'] = $request_type;//FIXME:What data has to be stored here?
		$provab_api_request_history['request'] = json_encode($request);
		$provab_api_request_history['created_datetime'] = date('Y-m-d H:i:s');
		$this->custom_db->insert_record('provab_api_request_history',$provab_api_request_history);
	}
	public function add_test_data($data, $enable_json = false) 
	{
		if ($enable_json == true) {
			$data = json_encode ( $data );
		}
		$this->custom_db->insert_record ( 'test', array (
				'test' => $data 
		) );
	}
	public function get_test_data($origin = 0, $enable_json = false) 
	{
		$data = $this->custom_db->single_table_records ( 'test', '*', array (
				'origin' => intval ( $origin ) 
		) );
		if ($enable_json == true) {
			return json_decode ( $data ['data'] [0] ['test'], true );
		} else {
			return $data ['data'] [0] ['test'];
		}
	}
	private function format_special_return_search_request($request_params)
	{
		$data['Status'] = SUCCESS_STATUS;
		$data['request'] = '';
		$data['Message'] = '';
		$ApiToken = array();
		$ApiToken['TokenId'] = $request_params['ApiToken']['TokenId'];
		$ApiToken['EndUserIp'] = $request_params['ApiToken']['EndUserIp'];
		$request_data ['ApiToken'] = $ApiToken;
		$request_data ['AdultCount'] = $request_params['AdultCount'];
		$request_data ['ChildCount'] = $request_params['ChildCount'];
		$request_data ['InfantCount'] = $request_params['InfantCount'];
		$request_data ['DirectFlight'] = false;
		$request_data ['OneStopFlight'] = false;
		//SEGMENT DATA
		$segment_data = $request_params['Segments'];
		$request_data ['JourneyType'] = 'specialreturn';
		$request_data ['Sources'] = array('6E', 'SG', 'G8');
		$request_data ['PreferredAirlines'] = $request_params ['PreferredAirlines'];
		$request_data ['Segments'] = $segment_data;
		return $request_data;
	}

	/****
	** Issue Hold Ticket 
	** Jeeva
	****/

	function IssueHoldTicket()
	{
		$postdata = file_get_contents("php://input");
		$post_data = json_decode($postdata,true);
		//debug($post_data); die;
		$app_reference = $post_data['AppReference'];
		$sequence_number = $post_data['SequenceNumber'];
		$ticket_id = '';
		$booking_id = $post_data['BookingId'];
		$pnr = $post_data['Pnr'];
		$response ['data'] = array ();
		$response ['Status'] = FAILURE_STATUS;
		$response ['Message'] = '';	
		$booking_details = $this->custom_db->single_table_records('flight_booking_transaction_details','*',array('app_reference'=>$app_reference,'pnr'=>$pnr,'sequence_number'=>$sequence_number,'status'=>'BOOKING_HOLD'));

		if(valid_array($booking_details) && $booking_details['status'] == SUCCESS_STATUS && $booking_details['data'][0]['hold_ticket_req_status'] == INACTIVE)
		{
			$get_booking_details  = $this->flight_model->get_booking_details($app_reference);
			$master_booking_details  = $get_booking_details['data']['booking_details'][0];
			$passenger_details = $get_booking_details['data']['booking_customer_details'][0];
			
			$fare_details = $booking_details['data'][0];
			$amount = $this->domain_management->agent_buying_price($fare_details);
			$total_amount = $amount[0];
			$domain_booking_attr['app_reference'] =$app_reference;
			$domain_booking_attr['transaction_type'] = "Flight";
			//Deduct Domain Balance
			$deduct_domain_balance = $this->domain_management->debit_domain_balance($total_amount, self::$credential_type, get_domain_auth_id(), $domain_booking_attr);//deduct the domain balance
			
			//Log the Transaction
			$agent_transaction_amount =	($total_amount-$fare_details['domain_markup']);
			$domain_markup = 			$fare_details['domain_markup'];
			$level_one_markup = 		0;
			$currency = 				$master_booking_details['currency'];
			$currency_conversion_rate =	$master_booking_details['currency_conversion_rate'];
			$remarks = 					'flight Transaction was Successfully done';
			$this->domain_management_model->save_transaction_details ( 'flight', $app_reference, $agent_transaction_amount, $domain_markup, $level_one_markup, $remarks, $currency, $currency_conversion_rate);
						
			//Update Issue Hold Ticket Status In Booking Transaction Details
			$update_issue_ticket_req_status = $this->custom_db->update_record('flight_booking_transaction_details',array('hold_ticket_req_status'=>ACTIVE),array('app_reference'=>$app_reference,'pnr' => $pnr));
			
			$get_domain_name = $this->custom_db->single_table_records('domain_list','domain_name',array('origin'=>get_domain_auth_id()));
			$domain_name = $get_domain_name['data'][0]['domain_name'];			
			$post_data['status'] = $booking_details['status'];
			$post_data['travel_date'] = date("d M Y",strtotime($master_booking_details['journey_start'])).", ".date("H:i",strtotime($master_booking_details['journey_start']));
			$post_data['leade_pax_name'] = $passenger_details;
			$post_data['domain_name'] = $domain_name;
			$post_data['booking_api_name'] = 'TBO';
			$post_data['BookingID'] = $booking_id;
			$post_data['PNR'] = $pnr;
			//Send SMS to tmx support team
			$sms_template = $this->load->view('voucher/ticket_hold_sms', $post_data,true);
			send_alert_sms($sms_template);
			
			//Send mail to tmx support team
			$mail_template = $this->load->view('voucher/ticket_hold', $post_data,true);
			$Email = $this->config->item('alert_email_id');
			$this->provab_mailer->send_mail($Email, $domain_name.' - Confirm Hold Ticket',$mail_template);
			$response['Status'] = SUCCESS_STATUS;
			$response ['Message'] = '';	
			
		}else{
			$response ['Status'] = FAILURE_STATUS;
		}		
		return $response;
	}
	/**
	 * Read Individual booking details - dont use it to generate table
	 * @param $app_reference
	 * @param $booking_source
	 * @param $booking_status
	 */
	private function get_flight_booking_transaction_details($app_reference, $sequence_number)
	{
		$response['status'] = FAILURE_STATUS;
		$response['data'] = array();
		//Booking Details
		$bd_query = 'select BD.*,DL.domain_name,DL.origin as domain_id from flight_booking_details AS BD,domain_list AS DL WHERE DL.origin = BD.domain_origin AND BD.app_reference like ' . $this->db->escape ( $app_reference );
		if (empty($booking_status) == false) {
			$bd_query .= ' AND BD.status = '.$this->db->escape($booking_status);
		}
		//Transaction Details
		$td_query = 'select TD.*,CAST(TD.status AS UNSIGNED) as status_code,BS.name as booking_api_name from flight_booking_transaction_details AS TD 
					left join booking_source BS on BS.source_id=TD.booking_source
					WHERE TD.app_reference='.$this->db->escape($app_reference).' AND TD.sequence_number='.intval($sequence_number);
		if (empty($booking_source) == false) {
			$td_query .= '	AND TD.booking_source = '.$this->db->escape($booking_source);
		}
		$booking_transaction_details	= $this->db->query($td_query)->result_array();
		$flight_booking_transaction_details_origin = intval(@$booking_transaction_details[0]['origin']);
		
		//Customer and Ticket Details
		$cd_query = 'select CD.*,FPTI.TicketId,FPTI.TicketNumber,FPTI.IssueDate,FPTI.Fare,FPTI.SegmentAdditionalInfo
						from flight_booking_passenger_details AS CD
						left join flight_passenger_ticket_info FPTI on CD.origin=FPTI.passenger_fk
						WHERE CD.flight_booking_transaction_details_fk='.$flight_booking_transaction_details_origin;
		//Cancellation Details
		$cancellation_details_query = 'select FCD.*
						from flight_booking_passenger_details AS CD
						left join flight_cancellation_details AS FCD ON FCD.passenger_fk=CD.origin
						WHERE CD.flight_booking_transaction_details_fk='.$flight_booking_transaction_details_origin;
	
		$response['data']['booking_details']			= $this->db->query($bd_query)->result_array();
		$response['data']['booking_transaction_details']	= $booking_transaction_details;
		$response['data']['booking_customer_details']	= $this->db->query($cd_query)->result_array();
		$response['data']['cancellation_details']	= $this->db->query($cancellation_details_query)->result_array();
		if (valid_array($response['data']['booking_details']) == true and valid_array($response['data']['booking_transaction_details']) == true and valid_array($response['data']['booking_customer_details']) == true) {
			$response['status'] = SUCCESS_STATUS;
		}
		return $response;
	}
	/**
	 * Send Notification if booking is not confirmed
	 */
	private function booking_not_confirmed_notification($app_reference, $sequence_number, $service_name='')
	{
		//Send Notification, If Booking Failed
		$booking_details = $this->get_flight_booking_transaction_details($app_reference, $sequence_number);
		$booking_details = $booking_details['data'];
		$master_booking_details  =$booking_details['booking_details'][0];
		$booking_transaction_details  =$booking_details['booking_transaction_details'][0];
		
		$send_notification = false;
		if(empty($service_name) == false && strtoupper($service_name) == 'BOOK'){
			if($booking_transaction_details['status'] != 'BOOKING_HOLD'){
				$send_notification = true;
			}
		} else{
			if($booking_transaction_details['status'] != 'BOOKING_CONFIRMED'){
				$send_notification = true;
			}
		}
		if($send_notification == true){
			$domain_name = $master_booking_details['domain_name'];
			$booking_failed_template = array();
			$booking_failed_template['domain_name'] = $domain_name;
			$booking_failed_template['booking_transaction_details'] = $booking_transaction_details;
			
			//Send SMS to tmx support team
			$sms_template = $this->load->view('flight/booking_failed_sms_template', $booking_failed_template,true);
			send_alert_sms($sms_template);
			
			//Send Mail
			$mail_template = $this->load->view('flight/booking_failed_mail_template', $booking_failed_template,true);
			$Email = $this->config->item('alert_email_id');
			//Send mail
			$this->load->library('provab_mailer');
			$this->provab_mailer->send_mail($Email, $domain_name.' - Booking '.booking_status_label_text($booking_transaction_details['status']).' Status', $mail_template);
		}
	}
}
