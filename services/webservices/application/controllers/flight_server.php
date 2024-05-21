<?php
if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
include ("Serializer.php");
ini_set('memory_limit', '-1');
class Flight_Server extends CI_Controller {
	private $credential_type;
	private $domain_comm_pct = 0; // (initialise dynamically) Domain Commission on Commision of Provab
	//****API Credentail Variables*****//
	private $api_SiteName = '';
	private $api_AccountCode = '';
	private $api_UserName = '';
	private $api_Password = '';
	private $api_url = '';
	//****API Credentail Variables*****//
	public function __construct() {
		parent::__construct ();
		$this->load->library ( 'currency' );
		$this->load->model ( 'flight_model' );
		$this->load->model ( 'private_management_model' );
		$this->load->model ( 'Domain_Management_Model' );
	}
	/**
	 * TEST API Credentails
	 */
	private function set_test_api_credentails()
	{
		$this->api_SiteName = '';
		$this->api_AccountCode = '';
		$this->api_UserName = 'cosmos';
		$this->api_Password = 'cosmos@12';
		$this->api_url = 'http://api.tektravels.com/tboapi_v7/service.asmx?wsdl';
	}
	/**
	 * LIVE API Credentails
	 */
	private function set_live_api_credentails()
	{
		$this->api_SiteName = '';
		$this->api_AccountCode = '';
		$this->api_UserName = 'BLRA177';
		$this->api_Password = 'travel@*';
		$this->api_url = 'http://airapi.travelboutiqueonline.com/tboapi_v7/service.asmx?wsdl';
	}
	/**
	 * Compress and output data
	 * @param array $data
	 */
	private function output_compressed_data($data)
	{
		while (ob_get_level() > 0) { ob_end_clean() ; }
		ob_start("ob_gzhandler");
		ob_end_flush();
		exit;
	}
	
	//Required
	public function index() {
		
	}
	public function index1($id = '') {
		
		$res = $this->db->query ( 'select response from provab_api_response_history where origin=' . $id . '' )->result_array ();
		$result = json_decode ( $res [0] ['response'] );
		debug ( $result );
		
		$res = $this->db->query ( 'select request from provab_api_response_history where origin=' . $id . '' )->result_array ();
		$request = unserialize ( $res [0] ['request'] );
		debug ( $request );
	
	}
	
	private function is_valid_user($_header=array()) {
		
		$this->credential_type = $_header [0]->data ['system'];
		$domain_login = $this->user_model->domain_login ( $_header [0]->data ['DomainKey'], $_header [0]->data ['UserName'], $_header [0]->data ['Password'], $_header [0]->data ['system'] );
		//$this->custom_db->insert_record('test', array('test' => $this->db->last_query()));exit;
//	debug($domain_login);exit;
//		$domain_login = $this->user_model->domain_login ( $domain_key, $user_name, $password, $system );
		
		if ($domain_login ['status'] == SUCCESS_STATUS) {
			
			$domain_details = $domain_login ['data'];
			$flight_commission_query = 'select BFCD.* from b2b_flight_commission_details as BFCD
								where ((BFCD.domain_list_fk ='.intval($domain_details ['origin']).' and BFCD.type="specific")	OR BFCD.type="generic")
								group by BFCD.domain_list_fk
								order by BFCD.domain_list_fk desc';
			$flight_commission_details = $this->db->query($flight_commission_query)->row_array();
			
			$this->domain_comm_pct = $flight_commission_details['value'];
			//$this->domain_comm_pct = 100;
			
			return true;
		} else {
			// FIXME Create Library Function
			$response ['InvalidUser'] ['ProdType'] = "Flight";
			$response ['InvalidUser'] ['PaymentReferenceNumber'] = "0";
			$response ['InvalidUser'] ['Status'] ['StatusCode'] = "101"; // Provab Custom Status To Idetify Error
			$response ['InvalidUser'] ['Status'] ['Description'] = "Remote IO Error"; // Provab Custom Description To Idetify Error
			$response ['InvalidUser'] ['Status'] ['Category'] = "IU"; // API Provider Catagory
			$response ['InvalidUser'] ['RefId'] = "";
			
			return ( object ) $response;
		}
	}
	
	public function provab_api($request_type, $request, $_header) {
		
		if ($this->is_valid_user ( $_header )) {
			
			switch ($request_type) {
				
				case 'Search' :
					return $this->search ( $request_type, $request );
					break;
				
				case 'GetFareRule' :
					return $this->getfarerule ( $request_type, $request );
					break;
				
				case 'GetFareQuote' :
					return $this->getfarequote ( $request_type, $request );
					break;
				
				case 'Book' :
					return $this->book ( $request_type, $request );
					break;
				
				case 'Ticket' :
					return $this->ticket ( $request_type, $request );
					break;
				
				case 'GetBooking' :
					return $this->getbooking ( $request_type, $request );
					break;
				
				case 'AddBookingDetail' :
					return $this->addbookingdetail ( $request_type, $request );
					break;
				
				case 'SendChangeRequest' :
					return $this->sendchangerequest ( $request_type, $request );
					break;
				
				case 'GetChangeRequestStatus' :
					return $this->getchangerequeststatus ( $request_type, $request );
					break;
				
				case 'GetCalendarFare' :
					return $this->getcalendarfare ( $request_type, $request );
					break;
				
				default :
					return $this->get_flight_response ( $request_type, $request );
			}
			
		} else {
			
			// return valid xml response to user
		}
	}
	
	public function search($request_type, $request) {
		
		if ($request ['Search'] ['request'] ['Type'] == "OneWay") {
			$trip_type = "oneway";
		} else if ($request ['Search'] ['request'] ['Type'] == "Return") {
			$trip_type = "roundway";
		} else if ($request ['Search'] ['request'] ['Type'] == "Multiway") {
			$trip_type = "multistop";
		} else {
			
		}
		
		$search_type = META_AIRLINE_COURSE; // Static Data and created_by_id is also static
		
		$total_pax = $request ['Search'] ['request'] ['AdultCount'] + $request ['Search'] ['request'] ['ChildCount'] + $request ['Search'] ['request'] ['InfantCount'] + $request ['Search'] ['request'] ['SeniorCount'];
		
		$this->custom_db->insert_record ( 'search_flight_history', array (
				'domain_origin' => get_domain_auth_id (),
				'search_type' => $search_type,
				'from_location' => $request ['Search'] ['request'] ['Origin'],
				'to_location' => $request ['Search'] ['request'] ['Destination'],
				'from_code' => $request ['Search'] ['request'] ['Origin'],
				'to_code' => $request ['Search'] ['request'] ['Destination'],
				'trip_type' => $trip_type,
				'journey_date' => $request ['Search'] ['request'] ['DepartureDate'],
				'total_pax' => $total_pax,
				'created_by_id' => '0',
				'created_datetime' => date ( 'Y-m-d H:i:s' ) 
		) );
		
		$result = $this->get_flight_response ( $request_type, $request );
		if ($result['SearchResult']['Status']['StatusCode'] == 2) {
			$cache_file_name = time().rand().rand().rand().rand();
			
			$search_auth_cache = array();
			
			foreach ( $result['SearchResult']['Result']['WSResult'] as $k => $v ) {
				
				$provab_auth_key = $cache_file_name.'___'.$k;
				//$search_auth_cache [$k] = $v;
				$search_auth_cache [$k] = $v;//Please dont change, Taking the Original TBO Response --- Jaganath
				$search_auth_cache [$k]['ProvabAuthKey'] =  $provab_auth_key;//jaganath
							
				$result['SearchResult']['Result']['WSResult'][$k]['ProvabAuthKey'] = $provab_auth_key;
				
				$total_fare = $v['Fare']['BaseFare'] + $v['Fare']['Tax'] + $v['Fare']['AdditionalTxnFee'] + $v['Fare']['AirTransFee'] + $v['Fare']['OtherCharges'] + $v['Fare']['ServiceTax'];
				
				$way_count = $this->get_way_count ($request ['Search'] ['request'] ['Type'],  $request ['Search'] ['request'] ['Origin'], $request ['Search'] ['request'] ['Destination']);
				$multiplier = $way_count * $total_pax;
				
				$total_markup = $this->add_markup ( $total_fare, $multiplier ) - $total_fare;  // Total Markup
				
				$agent_commision = $this->agent_commision_amount ( $v['Fare']['AgentCommission'] );  // Agent Commission
				
				$plb_earned = $this->agent_commision_amount ( $v['Fare']['PLBEarned'] );  //Agent Plb Earned
				
				$total_tbo_commision = $v['Fare']['AgentCommission'] + $v['Fare']['PLBEarned'];     // Total Commision Given By TBO
				
				$tax = $v['Fare']['Tax'] + $total_markup;
				
				
				
				
				
				$result['SearchResult']['Result']['WSResult'][$k]['Fare']['AgentCommission'] = $agent_commision;
				$result['SearchResult']['Result']['WSResult'][$k]['Fare']['TdsOnCommission'] = $agent_commision / 10;
				$result['SearchResult']['Result']['WSResult'][$k]['Fare']['PLBEarned'] = $plb_earned;
				$result['SearchResult']['Result']['WSResult'][$k]['Fare']['TdsOnPLB'] = $plb_earned / 10;
				$result['SearchResult']['Result']['WSResult'][$k]['Fare']['Tax'] = sprintf ( "%.2f", $tax );
				$result['SearchResult']['Result']['WSResult'][$k]['Fare']['PublishedPrice'] = sprintf ( "%.2f", $v['Fare']['PublishedPrice'] + $total_markup);
				$result['SearchResult']['Result']['WSResult'][$k]['Fare']['OfferedFare'] = sprintf ( "%.2f", $v['Fare']['OfferedFare'] + $total_markup + ($total_tbo_commision - ($agent_commision + $plb_earned)));
				
				
				// FIXME ARJUN
				
				if (isset ( $result['SearchResult']['Result']['WSResult'][$k]['FareBreakdown']['WSPTCFare']['Tax'] )) {
					
					unset ( $result['SearchResult']['Result']['WSResult'][$k]['FareBreakdown']['WSPTCFare']['Tax'] );
				} else {
					
					foreach ( $result['SearchResult']['Result']['WSResult'][$k]['FareBreakdown']['WSPTCFare'] as $key => $value ) {
						
						unset ( $result['SearchResult']['Result']['WSResult'][$k]['FareBreakdown']['WSPTCFare'][$key]['Tax'] );
					}
				}
				
				unset ( $result['SearchResult']['Result']['WSResult'][$k]['Fare']['ChargeBU']);
			}
			$this->store_json($cache_file_name, $search_auth_cache);
			$response = $result;
		} else {
			$response ['SearchResult'] ['ProdType'] = "Flight";
			$response ['SearchResult'] ['PaymentReferenceNumber'] = "0";
			$response ['SearchResult'] ['Status'] ['StatusCode'] = "104"; // Provab Custom Status To Idetify Error
			$response ['SearchResult'] ['Status'] ['Description'] = "Remote IO Error"; // Provab Custom Description To Idetify Error
			$response ['SearchResult'] ['Status'] ['Category'] = "SR"; // API Provider Catagory
			$response ['SearchResult'] ['RefId'] = "";
			
		}
		
		$this->custom_db->insert_record('provab_api_request_history', array('request_type'=> 'Search Result', 'header'=>'Search Result', 'request'=>json_encode($result, true), 'created_datetime'=>date('Y-m-d H:i:s')));
		
		return (object)$response;
	}
	
	public function objectToArray($d) {
		if (is_object ( $d )) {
			$d = get_object_vars ( $d );
		}
		
		if (is_array ( $d )) {
			return array_map ( array (
					$this,
					'objectToArray' 
			), $d );
		} else {
			return $d;
		}
	}
	
	public function getfarerule($request_type, $request) {
		
		$provab_auth_key = $request ['GetFareRule'] ['fareRuleRequest'] ['Result'] ['ProvabAuthKey'];
		
		$provab_auth_key = explode('___', $provab_auth_key);
		
		$provab_auth_data = $this->readfile($provab_auth_key[0]);
		$key = $provab_auth_key[1];
		
		$provab_auth_data = json_decode($provab_auth_data, true);
		
		$request ['GetFareRule'] ['fareRuleRequest'] ['Result'] = $provab_auth_data[$key];
		
		//$request ['GetFareRule'] ['fareRuleRequest'] ['Result'] = $this->objectToArray ( json_decode ( base64_decode ( $request ['GetFareRule'] ['fareRuleRequest'] ['Result'] ['ProvabAuthKey'] ) ) );
		
		unset ( $request ['GetFareRule'] ['fareRuleRequest'] ['Result'] ['ProvabAuthKey'] );
		
		$result = $this->get_flight_response ( $request_type, $request );
		if ($result['GetFareRuleResult']['Status']['StatusCode'] == 2) {
			
			$response = $result;
		} else {
			
			$response ['GetFareRuleResult'] ['ProdType'] = "Flight";
			$response ['GetFareRuleResult'] ['PaymentReferenceNumber'] = "0";
			$response ['GetFareRuleResult'] ['Status'] ['StatusCode'] = "106"; // Provab Custom Status To Idetify Error
			$response ['GetFareRuleResult'] ['Status'] ['Description'] = "Remote IO Error"; // Provab Custom Description To Idetify Error
			$response ['GetFareRuleResult'] ['Status'] ['Category'] = "FQ"; // API Provider Catagory
			$response ['GetFareRuleResult'] ['RefId'] = "";
		}
		return ( object ) $response;
	}
	
	public function getfarequote($request_type, $request) 
	{	
		$provab_auth_key = $request ['GetFareQuote'] ['fareQuoteRequest'] ['Result'] [0]['ProvabAuthKey'];
		
		$provab_auth_key = explode('___', $provab_auth_key);
		$provab_auth_data = $this->readfile($provab_auth_key[0]);
		$key = $provab_auth_key[1];
		
		$provab_auth_data = json_decode($provab_auth_data, true);
		
		$request ['GetFareQuote'] ['fareQuoteRequest'] ['Result'] [0] = $provab_auth_data[$key];
		
		
		
		unset ( $request ['GetFareQuote'] ['fareQuoteRequest'] ['Result'] [0] ['ProvabAuthKey'] );
		$result = $this->get_flight_response ( $request_type, $request );
		if ($result['GetFareQuoteResult']['Status']['StatusCode'] == 3) {
			//***************Store the FareQuote Data in File -- Jaganath**************//
			$cache_file_name = time().rand().rand().rand().rand();
			$provab_auth_key = $cache_file_name.'___0';//Dont Remove
			$fareqoute_auth_cache = array();
			$fareqoute_auth_cache[0] = $result['GetFareQuoteResult']['Result'];
			$fareqoute_auth_cache[0]['ProvabAuthKey'] = $provab_auth_key;
			$this->store_json($cache_file_name, $fareqoute_auth_cache);
			//***************Store the FareQuote Data in File**************//
			$total_pax=0;
			if(isset($result['GetFareQuoteResult']['Result']['FareBreakdown']['WSPTCFare']['PassengerCount'])){
				$total_pax = $result['GetFareQuoteResult']['Result']['FareBreakdown']['WSPTCFare']['PassengerCount'];
			} else {
				foreach($result['GetFareQuoteResult']['Result']['FareBreakdown']['WSPTCFare']['PassengerCount'] as $k=>$v){
					$total_pax += $v['PassengerCount'];
				}
			}
			$total_fare = $result['GetFareQuoteResult']['Result']['Fare']['BaseFare'] + $result['GetFareQuoteResult']['Result']['Fare']['Tax'] + $result['GetFareQuoteResult']['Result']['Fare']['AdditionalTxnFee'] + $result['GetFareQuoteResult']['Result']['Fare']['AirTransFee'] + $result['GetFareQuoteResult']['Result']['Fare']['OtherCharges'] + $result['GetFareQuoteResult']['Result']['Fare']['ServiceTax'];
			$total_markup = $this->add_markup ( $total_fare , $total_pax) - $total_fare;
			
			$agent_commision = $this->agent_commision_amount ( $result['GetFareQuoteResult']['Result']['Fare']['AgentCommission'] );  // Commision Earned By Agent
			$plb_earned = $this->agent_commision_amount ($result['GetFareQuoteResult']['Result']['Fare']['PLBEarned']); // PlB Earned By Agent
			
			$total_tbo_commision = $result['GetFareQuoteResult']['Result']['Fare']['AgentCommission'] + $result['GetFareQuoteResult']['Result']['Fare']['PLBEarned'];     // Total Commision Given By TBO
			
			
			$result['GetFareQuoteResult']['Result']['Fare']['Tax'] = $result['GetFareQuoteResult']['Result']['Fare']['Tax'] + $total_markup;
			$result['GetFareQuoteResult']['Result']['Fare']['AgentCommission'] = $agent_commision;
			$result['GetFareQuoteResult']['Result']['Fare']['TdsOnCommission'] = ($agent_commision) / 10;
			$result['GetFareQuoteResult']['Result']['Fare']['PLBEarned'] = $plb_earned;
			$result['GetFareQuoteResult']['Result']['Fare']['TdsOnPLB'] = ($plb_earned) / 10;
			$result['GetFareQuoteResult']['Result']['Fare']['PublishedPrice'] = $result['GetFareQuoteResult']['Result']['Fare']['PublishedPrice'] + $total_markup;
			$result['GetFareQuoteResult']['Result']['Fare']['OfferedFare'] = $result['GetFareQuoteResult']['Result']['Fare']['OfferedFare'] + $total_markup + ($total_tbo_commision - ($agent_commision + $plb_earned));
			$result['GetFareQuoteResult']['Result']['ProvabAuthKey'] = $provab_auth_key;//Assigning Provab Auth Key
			$this->custom_db->insert_record('provab_api_request_history', array('request_type'=> 'Farequote Breakdwn', 'header'=>'Farequote Breakdwn', 'request'=>json_encode(array('of'=>$result['GetFareQuoteResult']['Result']['Fare']['OfferedFare'], 'markup'=>$total_markup, 'total_tbo_commision'=>$total_tbo_commision, 'agent_commision'=>$agent_commision, 'plb_earned'=>$plb_earned), true), 'created_datetime'=>date('Y-m-d H:i:s')));
			
			if (isset ( $result['GetFareQuoteResult']['Result']['FareBreakdown']['WSPTCFare']['Tax'] )) {
				unset ( $result['GetFareQuoteResult']['Result']['FareBreakdown']['WSPTCFare']['Tax'] );
			} else {
				
				foreach ( $result['GetFareQuoteResult']['Result']['FareBreakdown']['WSPTCFare'] as $key => $value ) {
					
					unset ( $result['GetFareQuoteResult']['Result']['FareBreakdown']['WSPTCFare'][$key]['Tax'] );
				}
			}
			unset ( $result['GetFareQuoteResult']['Result']['Fare']['ChargeBU']);
			
			$this->custom_db->insert_record('provab_api_request_history', array('request_type'=> 'Farequote Result', 'header'=>'Farequote Result', 'request'=>json_encode($result, true), 'created_datetime'=>date('Y-m-d H:i:s')));
			$response = $result;
		} else {
			
			$response ['GetFareQuoteResult'] ['ProdType'] = "Flight";
			$response ['GetFareQuoteResult'] ['PaymentReferenceNumber'] = "0";
			$response ['GetFareQuoteResult'] ['Status'] ['StatusCode'] = "105"; // Provab Custom Status To Idetify Error
			$response ['GetFareQuoteResult'] ['Status'] ['Description'] = "Remote IO Error"; // Provab Custom Description To Idetify Error
			$response ['GetFareQuoteResult'] ['Status'] ['Category'] = "FQ"; // API Provider Catagory
			$response ['GetFareQuoteResult'] ['RefId'] = "";
		}
		return ( object ) $response;
	}
	
	public function book($request_type, $request) {
		
		$response = array ();
		
		if (isset ( $request ['Book'] ['bookRequest'] ['ProvabAuthKey'] )) {
			
			//$provab_auth_data = json_decode ( base64_decode ( $request ['Book'] ['bookRequest'] ['ProvabAuthKey'] ) );
			
			$provab_auth_key = $request ['Book'] ['bookRequest'] ['ProvabAuthKey'];
			$provab_auth_data = $this->format_auth_cache_data($provab_auth_key);
			if(isset($provab_auth_data['ProvabAuthKey']) == true) {
				unset($provab_auth_data['ProvabAuthKey']);
			}
			$flight_base_fare = $request ['Book'] ['bookRequest'] ['Fare'] ['BaseFare']; // BaseFare of the Flight
			$flight_tax = $request ['Book'] ['bookRequest'] ['Fare'] ['Tax'];
			
			$AdditionalTxnFee = $request ['Book'] ['bookRequest'] ['Fare'] ['AdditionalTxnFee'];
			$AirlineTransFee = $request ['Book'] ['bookRequest'] ['Fare'] ['AirTransFee'];
			$OtherCharges = $request ['Book'] ['bookRequest'] ['Fare'] ['OtherCharges'];
			$ServiceTax = $request ['Book'] ['bookRequest'] ['Fare'] ['ServiceTax'];
			
			$total_tax = $flight_tax + $AdditionalTxnFee + $AirlineTransFee + $OtherCharges + $ServiceTax;
			/*
			$request ['Book'] ['bookRequest'] ['Fare'] ['BaseFare'] = sprintf ( "%.2f", $provab_auth_data->Fare->BaseFare ); // Formatting the request to decimal
			$request ['Book'] ['bookRequest'] ['Fare'] ['Tax'] = sprintf ( "%.2f", $provab_auth_data->Fare->Tax );
			*/
			// formath the request to decimal points.
			/*
			 * foreach ($request ['Book'] ['bookRequest']['Passenger']['WSPassenger'] as $k=>$v){
			 *
			 * $request ['Book'] ['bookRequest']['Passenger']['WSPassenger'][$k]['Fare']['BaseFare']=sprintf("%.2f", $v['Fare']['BaseFare']);
			 * $request ['Book'] ['bookRequest']['Passenger']['WSPassenger'][$k]['Fare']['Tax']=sprintf("%.2f", $v['Fare']['Tax']);
			 *
			 *
			 * }
			 *
			 */
			// Jaganath
			
			$request ['Book'] ['bookRequest'] ['Passenger'] ['WSPassenger'] = $this->WSPassenger ( $request ['Book'] ['bookRequest'] ['Passenger'] ['WSPassenger'], $provab_auth_data );
			
			$request ['Book'] ['bookRequest'] ['Fare'] = $provab_auth_data['Fare'];
			
			foreach($provab_auth_data['Fare'] as $key=>$value){
				
				if(in_array($key, array('BaseFare', 'Tax', 'AgentCommission', 'TdsOnCommission', 'IncentiveEarned', 'TdsOnIncentive', 'PLBEarned', 'TdsOnPLB', 'PublishedPrice', 'OtherCharges', 'OfferedFare'))){
					
					$request ['Book'] ['bookRequest'] ['Fare'][$key] = sprintf("%.2f", $value);
				}
				if($key == 'ChargeBU') {
					//$request ['Book'] ['bookRequest'] ['Fare']->$key = null;
					//*****Converting ChargeBU to Proper Format(As required for Booking Request) -- Jaganath*********//
					$temp_chargebu[] = (array)($request ['Book'] ['bookRequest'] ['Fare'][$key]);
					$request ['Book'] ['bookRequest'] ['Fare'][$key] = json_encode($temp_chargebu);
				}
			}
			unset ( $request ['Book'] ['bookRequest'] ['ProvabAuthKey'] ); // Unset ProvabAuthKey for final request.
		} else {
			
			exit;
		}
		$price_details = $this->currency->get_currency ( intval ( $total_tax ) );
		if ($this->user_model->get_balance ( ($price_details['default_value'] + $flight_base_fare) , $this->credential_type)) { // Non Lcc Filght fare with markup
			
			$this->custom_db->insert_record('provab_api_request_history', array('request_type'=> 'Test', 'header'=>'Test Costructor', 'request'=>json_encode($request), 'created_datetime'=>date('Y-m-d H:i:s')));
			$result = $this->get_flight_response ( $request_type, $request );
			$this->add_test_data($result, true);//Remove Later
			$response = $result;
			/*
			 * if($result->BookResult->Status->StatusCode == 5){ // check the status(confirm with api provider for this)
			 *
			 * } else {
			 *
			 * }
			 */
		} else {
			
			$response ['BookResult'] ['ProdType'] = "Flight";
			$response ['BookResult'] ['PaymentReferenceNumber'] = "0";
			$response ['BookResult'] ['Status'] ['StatusCode'] = "105"; // Provab Custom Status To Idetify Error
			$response ['BookResult'] ['Status'] ['Description'] = "Remote IO Error"; // Provab Custom Description To Idetify Error
			$response ['BookResult'] ['Status'] ['Category'] = "BK"; // API Provider Catagory
			$response ['BookResult'] ['RefId'] = "";
			
			
			// Show Insufficent Balance Message.
		}
		return ( object ) $response;
	}
	
	private function create_test($data) {
		$this->custom_db->insert_record ( 'test', array (
				'test' => json_encode ( $data ) 
		) );
	}
	
	/**
	 * '
	 * Assign the FareBreakdown for Each Passenger
	 */
	private function WSPassenger($passenger, $provab_auth_data) {
		$fare_break_down = ($provab_auth_data['FareBreakdown']['WSPTCFare']);
		$passenger_token = $this->Fare ( $fare_break_down );
		foreach ( $passenger as $k => $v ) {
			$passenger [$k] ['Fare'] = $passenger_token [$v ['Type']];
		}
		return $passenger;
	}
	/**
	 * Formats the Fare Request For Passenger-wise
	 *
	 * @param unknown_type $passenger_token        	
	 */
	function Fare($passenger_token) {
		
		$passenger_token = force_multple_data_format ( $passenger_token );
		$Fare = array ();
		foreach ( $passenger_token as $k => $v ) {
			$Fare [$v['PassengerType']] ['BaseFare'] = ($v['BaseFare'] / $v['PassengerCount']);
			$Fare [$v['PassengerType']] ['Tax'] = ($v['Tax'] / $v['PassengerCount']);
			$Fare [$v['PassengerType']] ['AirlineTransFee'] = ($v['AirlineTransFee'] / $v['PassengerCount']);
			$Fare [$v['PassengerType']] ['AdditionalTxnFee'] = ($v['AdditionalTxnFee'] / $v['PassengerCount']);
			$Fare [$v['PassengerType']] ['FuelSurcharge'] = ($v['FuelSurcharge'] / $v['PassengerCount']);
			$Fare [$v['PassengerType']] ['AgentServiceCharge'] = ($v['AgentServiceCharge'] / $v['PassengerCount']);
			$Fare [$v['PassengerType']] ['AgentConvienceCharges'] = ($v['AgentConvienceCharges'] / $v['PassengerCount']);
			$Fare [$v['PassengerType']] ['AgentCommission'] = 0.0;
			$Fare [$v['PassengerType']] ['TdsOnCommission'] = 0.0;
			$Fare [$v['PassengerType']] ['AirTransFee'] = 0.0;
			$Fare [$v['PassengerType']] ['OtherCharges'] = 0.0;
			$Fare [$v['PassengerType']] ['Discount'] = 0.0;
			$Fare [$v['PassengerType']] ['ServiceTax'] = 0.0;
			$Fare [$v['PassengerType']] ['Currency'] = null;
			$Fare [$v['PassengerType']] ['ChargeBU'] = null;
			$Fare [$v['PassengerType']] ['Markup'] = null;
			$Fare [$v['PassengerType']] ['PLB'] = 0.0;
			$Fare [$v['PassengerType']] ['TdsOnPLB'] = 0.0;
			$Fare [$v['PassengerType']] ['IncentiveEarned'] = 0.0;
			$Fare [$v['PassengerType']] ['TdsOnIncentive'] = 0.0;
			$Fare [$v['PassengerType']] ['OfferedFare'] = 0.0;
			$Fare [$v['PassengerType']] ['AgentServiceCharge'] = 0.0;
			$Fare [$v['PassengerType']] ['PLBEarned'] = 0.0;
			$Fare [$v['PassengerType']] ['ReverseHandlingCharge'] = 0.0;
			$Fare [$v['PassengerType']] ['PublishedPrice'] = 0.0;
			$Fare [$v['PassengerType']] ['TransactionFee'] = 0.0;
		}
		return $Fare;
	}
	
	function format_auth_cache_data($provab_auth_key)
	{
		$provab_auth_key = explode('___', $provab_auth_key);
		$provab_auth_data = $this->readfile($provab_auth_key[0]);
		$provab_auth_data = json_decode($provab_auth_data, true);
		$provab_auth_data = $provab_auth_data[$provab_auth_key[1]];
		return $provab_auth_data;
	}
	public function ticket($request_type, $request) {
		
		$this->custom_db->insert_record('provab_api_request_history', array('request_type'=> 'Test', 'header'=>'Test Test Test', 'request'=>'Test', 'created_datetime'=>date('Y-m-d H:i:s')));
		$flight_total_base_fare = $flight_base_fare = $flight_tax = $AdditionalTxnFee = $AirlineTransFee = $OtherCharges = $ServiceTax = $total_fare = $total_tax = $total_segment_fare = $total_segment_tax = 0;
		$tbo_total_fare = 0;
		foreach ( $request ['Ticket'] as $k => $v ) {
			
			$flight_base_fare = $v ['wsTicketRequest'] ['Fare'] ['BaseFare'];
			$flight_total_base_fare += $flight_base_fare; // Calculate Total Base Fare of the Flight
			$flight_tax = $v ['wsTicketRequest'] ['Fare'] ['Tax'];
			$AdditionalTxnFee = $v ['wsTicketRequest'] ['Fare'] ['AdditionalTxnFee'];
			$AirlineTransFee = $v ['wsTicketRequest'] ['Fare'] ['AirTransFee'];
			$OtherCharges = $v ['wsTicketRequest'] ['Fare'] ['OtherCharges'];
			$ServiceTax = $v ['wsTicketRequest'] ['Fare'] ['ServiceTax'];
			
			$total_segment_fare = $flight_base_fare + $flight_tax + $AdditionalTxnFee + $AirlineTransFee + $OtherCharges + $ServiceTax;
			$total_segment_tax = $total_segment_fare - $flight_base_fare;
			
			$total_fare += $total_segment_fare; // Total Fare including Tax
			
			$total_tax += $total_segment_tax; // Total Tax including all Taxes
			
			if (isset ( $request ['Ticket'] [$k] ['wsTicketRequest'] ['ProvabAuthKey'] )) {
				
				//$provab_auth_data = json_decode ( base64_decode ( $v ['wsTicketRequest'] ['ProvabAuthKey'] ) );//OLD
				//Extracting Provab Authentication Data From File
				
				
				
				$provab_auth_key = $v['wsTicketRequest'] ['ProvabAuthKey'];
				$provab_auth_data = $this->format_auth_cache_data($provab_auth_key);
				$lcc_value = $provab_auth_data['IsLcc'];
				$tbo_base_fare = $provab_auth_data['Fare']['BaseFare'];
				$tbo_tax_fare = $provab_auth_data['Fare']['Tax'];
				$tbo_additionaltxnfee = $provab_auth_data['Fare']['AdditionalTxnFee'];
				$tbo_airlinetransfee = $provab_auth_data['Fare']['AirTransFee'];
				$tbo_othercharges = $provab_auth_data['Fare']['OtherCharges'];
				$tbo_servicetax = $provab_auth_data['Fare']['ServiceTax'];
				
				
				$tbo_fare = $tbo_base_fare + $tbo_tax_fare + $tbo_additionaltxnfee + $tbo_airlinetransfee + $tbo_othercharges + $tbo_servicetax;
				
				$tbo_total_fare += $tbo_fare;
				// unset ( $request['Ticket'][$k]['wsTicketRequest'] ['ProvabAuthKey'] ); // Unset ProvabAuthKey for final request.
			}
		}
		if ($this->user_model->get_balance ( $total_fare , $this->credential_type)) { // Ticket Amount checking
			$response = array ();
			
			$app_reference = 'PB-' . time () . rand ( 1, 50000 ); // Unique Refrence Number
			
			foreach ( $request ['Ticket'] as $k => $v ) { // run ticket method in loop as it will help for roundtrip(domestic)
				//$provab_auth_data = json_decode ( base64_decode ( $v ['wsTicketRequest'] ['ProvabAuthKey'] ) );
				//Extracting Provab Authentication Data From File
				$provab_auth_key = $v ['wsTicketRequest'] ['ProvabAuthKey'];
				$provab_auth_data = $this->format_auth_cache_data($provab_auth_key);
				if(isset($request ['Ticket'][$k]['wsTicketRequest']['ProvabAuthKey'])) {
					unset($request ['Ticket'][$k]['wsTicketRequest']['ProvabAuthKey']);
				}
				$request ['Ticket'][$k]['wsTicketRequest'] ['Passenger'] ['WSPassenger'] = $this->WSPassenger ( $v ['wsTicketRequest'] ['Passenger'] ['WSPassenger'], $provab_auth_data );
				$flight_base_fare = $v ['wsTicketRequest'] ['Fare'] ['BaseFare'];
				$flight_tax = $v ['wsTicketRequest'] ['Fare'] ['Tax'];
				$AdditionalTxnFee = $v ['wsTicketRequest'] ['Fare'] ['AdditionalTxnFee'];
				$AirlineTransFee = $v ['wsTicketRequest'] ['Fare'] ['AirTransFee'];
				$OtherCharges = $v ['wsTicketRequest'] ['Fare'] ['OtherCharges'];
				$ServiceTax = $v ['wsTicketRequest'] ['Fare'] ['ServiceTax'];
				unset ($request ['Ticket'] [$k] ['wsTicketRequest'] ['ProvabAuthKey'] );                       // Unset ProvabAuthKey For Final Request
				
				$agent_comm_amt = $v['wsTicketRequest']['Fare']['AgentCommission'] + $v['wsTicketRequest']['Fare']['PLBEarned'] - $v['wsTicketRequest']['Fare']['TdsOnCommission'] - $v['wsTicketRequest']['Fare']['TdsOnPLB'];  // Need to Give To Agent
				
				$per_trans_total_fare = $flight_base_fare + $flight_tax + $AdditionalTxnFee + $AirlineTransFee + $OtherCharges + $ServiceTax;
				$per_trans_total_tax = $per_trans_total_fare - $flight_base_fare;
				
				
				$ticket_request ['Ticket'] = $request ['Ticket'] [$k];
				$result = $this->get_flight_response ( $request_type, $ticket_request );
				$response ['TicketResult'] [$k] = $result['TicketResult'];
				$this->add_test_data($response, true);//Remove Later
				/* store the response in the file */
				
				
				if ($result['TicketResult']['Status']['StatusCode'] == 14 xor $result['TicketResult']['Status']['StatusCode'] == 9 xor $result['TicketResult']['Status']['StatusCode'] == 5) { // Flight Ticket Success Status(check with Api Provider for status code=5)
					
					$this->user_model->update_balance ( -($per_trans_total_fare - $agent_comm_amt), $this->credential_type); // Price With Markup
					
					$flight_booking_passenger_status = "BOOKING_CONFIRMED";
					$flight_booking_details_status = "BOOKING_CONFIRMED";
					$flight_booking_transaction_status = "BOOKING_CONFIRMED";
					$flight_booking_itinerary_status = $result['TicketResult']['Status']['Description'];
				} else {
					
					$flight_booking_passenger_status = "BOOKING_FAILED";
					$flight_booking_details_status = "BOOKING_FAILED";
					$flight_booking_transaction_status = "BOOKING_FAILED";
					$flight_booking_itinerary_status = $result['TicketResult']['Status']['Description'];
					
					$response ['TicketResult'] [$k]['PNR'] = "";
					$response ['TicketResult'] [$k]['BookingId'] = "";
					$response ['TicketResult'] [$k]['SSRDenied'] = "";
					$response ['TicketResult'] [$k]['ProdType'] = "Flight";
					$response ['TicketResult'] [$k]['PaymentReferenceNumber'] = "0";
					$response ['TicketResult'] [$k]['Status']['StatusCode'] = "102"; // Provab Custom Status To Idetify Error
					$response ['TicketResult'] [$k]['Status']['Description'] = "Remote IO Error"; // Provab Custom Description To Idetify Error
					$response ['TicketResult'] [$k]['Status']['Category'] = "TK"; // API Provider Catagory
					$response ['TicketResult'] [$k]['RefId'] = "";
					
					
					// Send booking failed xml to user
				}
				
				if ($k == 0) {
					
					$i = 0; // To choose for Lead
					
					foreach ( $request ['Ticket'] [0] ['wsTicketRequest'] ['Passenger'] ['WSPassenger'] as $__k => $__v ) {
						
						// Passenger Saving details.
						$passenger_type = $__v ['Type'];
						
						if ($i == 0) {
							$is_lead = '1';
						} else {
							$is_lead = '0';
						}
						
						$title = $__v ['Title'];
						$first_name = $__v ['FirstName'];
						$middle_name = '';
						$last_name = $__v ['LastName'];
						$date_of_birth = $__v ['DateOfBirth'];
						$gender = $__v ['Gender'];
						$passenger_nationality = $__v ['Country'];
						$passport_number = $__v ['PassportNumber'];
						$passport_issuing_country = $__v ['Country'];
						$passport_expiry_date = $__v ['PassportExpiry'];
						$status = $flight_booking_passenger_status;
						$attributes = '';
						
						$this->flight_model->save_flight_booking_passenger_details ( $app_reference, $passenger_type, $is_lead, $title, $first_name, $middle_name, $last_name, $date_of_birth, $gender, $passenger_nationality, $passport_number, $passport_issuing_country, $passport_expiry_date, $status, serialize ( $attributes ) );
						
						$i ++;
					}
					
					$domain_origin = get_domain_auth_id ();
					$flight_booking_status = $flight_booking_details_status;
					$booking_source = TBO_FLIGHT_BOOKING_SOURCE;
					
					if (isset ( $request ['Ticket'] [$k] ['wsTicketRequest'] ['ProvabAuthKey'] )) {
						//Extracting Provab Authentication Data From File
						$provab_auth_key = $v['wsTicketRequest'] ['ProvabAuthKey'];
						$provab_auth_data = $this->format_auth_cache_data($provab_auth_key);
						$is_lcc = $provab_auth_data['IsLcc'];
					} else {
						$is_lcc = '1'; // Static Value $lcc_value
					}
					
					$book_total_fare = $tbo_total_fare;
					
					// Need To Swap The Value for Markup
					
					$book_domain_markup = '0'; // Static Value As Domian Markup is Zero
					$book_level_one_markup =  $total_fare - $tbo_total_fare; // Provab Markup
					
					$currency = 'INR'; // Static Value
					
					$phone = $request ['Ticket'] [0] ['wsTicketRequest'] ['Passenger'] ['WSPassenger'] ['0'] ['Phone'];
					$alternate_number = ''; // Static Value No Alternate number from request
					$email = $request ['Ticket'] [0] ['wsTicketRequest'] ['Passenger'] ['WSPassenger'] ['0'] ['Email'];
					
					if (isset ( $request ['Ticket'] [0] ['wsTicketRequest'] ['Segment'] ['WSSegment'] [0] ['DepTIme'] )) {
						$journey_start = $request ['Ticket'] [0] ['wsTicketRequest'] ['Segment'] ['WSSegment'] [0] ['DepTIme'];
					} else {
						$journey_start = $request ['Ticket'] [0] ['wsTicketRequest'] ['Segment'] ['WSSegment'] ['DepTIme'];
					}
					
					if (isset ( $request ['Ticket'] [0] ['wsTicketRequest'] ['Segment'] ['WSSegment'] [0] ['ArrTime'] )) {
						$journey_end = $request ['Ticket'] [0] ['wsTicketRequest'] ['Segment'] ['WSSegment'] [0] ['ArrTime'];
					} else {
						$journey_end = $request ['Ticket'] [0] ['wsTicketRequest'] ['Segment'] ['WSSegment'] ['ArrTime'];
					}
					
					$journey_from = $request ['Ticket'] [0] ['wsTicketRequest'] ['Origin'];
					
					$journey_to = $request ['Ticket'] [0] ['wsTicketRequest'] ['Destination'];
					
					$payment_mode = 'PNHB1'; // Static Value
					$attributes = '';
					$created_by_id = '0'; // Static Value
					
					$this->flight_model->save_flight_booking_details ( $domain_origin, $flight_booking_status, $app_reference, $booking_source, $is_lcc, $book_total_fare, $book_domain_markup, $book_level_one_markup, $currency, $phone, $alternate_number, $email, $journey_start, $journey_end, $journey_from, $journey_to, $payment_mode, serialize ( $attributes ), $created_by_id );
				}
				
				$transaction_status = $flight_booking_transaction_status;
				$transaction_description = $result['TicketResult']['Status']['Description'];
				$pnr = $result['TicketResult']['PNR'];
				$book_id = $result['TicketResult']['BookingId'];
				$source = $request ['Ticket'] [$k] ['wsTicketRequest'] ['Source'];
				$ref_id = $result['TicketResult']['RefId'];
				$attributes = '';
				$sequence_number = '0'; // Static Value
				
				$this->flight_model->save_flight_booking_transaction_details ( $app_reference, $transaction_status, $transaction_description, $pnr, $book_id, $source, $ref_id, serialize ( $attributes ), $sequence_number );
				
				// This is used for multiple Segment
				if (isset ( $request ['Ticket'] [$k] ['wsTicketRequest'] ['Segment'] ['WSSegment'] [0] ['SegmentIndicator'] )) {
					
					foreach ( $request ['Ticket'] [$k] ['wsTicketRequest'] ['Segment'] ['WSSegment'] as $key => $value ) {
						
						$segment_indicator = $value ['SegmentIndicator'];
						$airline_code = $value ['Airline'] ['AirlineCode'];
						$airline_name = $value ['Airline'] ['AirlineName'];
						$flight_number = $value ['FlightNumber'];
						$fare_class = $value ['FareClass'];
						$from_airport_code = $value ['Origin'] ['AirportCode'];
						$from_airport_name = $value ['Origin'] ['AirportName'];
						$to_airport_code = $value ['Destination'] ['AirportCode'];
						$to_airport_name = $value ['Destination'] ['AirportName'];
						$departure_datetime = $value ['DepTIme'];
						$arrival_datetime = $value ['ArrTime'];
						$iti_status = $flight_booking_itinerary_status;
						$operating_carrier = $value ['OperatingCarrier'];
						$attributes = '';
						
						$this->flight_model->save_flight_booking_itinerary_details ( $app_reference, $segment_indicator, $airline_code, $airline_name, $flight_number, $fare_class, $from_airport_code, $from_airport_name, $to_airport_code, $to_airport_name, $departure_datetime, $arrival_datetime, $iti_status, $operating_carrier, serialize ( $attributes ) );
					}
				} else {
					
					$segment_indicator = $request ['Ticket'] [$k] ['wsTicketRequest'] ['Segment'] ['WSSegment'] ['SegmentIndicator'];
					$airline_code = $request ['Ticket'] [$k] ['wsTicketRequest'] ['Segment'] ['WSSegment'] ['Airline'] ['AirlineCode'];
					$airline_name = $request ['Ticket'] [$k] ['wsTicketRequest'] ['Segment'] ['WSSegment'] ['Airline'] ['AirlineName'];
					$flight_number = $request ['Ticket'] [$k] ['wsTicketRequest'] ['Segment'] ['WSSegment'] ['FlightNumber'];
					$fare_class = $request ['Ticket'] [$k] ['wsTicketRequest'] ['Segment'] ['WSSegment'] ['FareClass'];
					$from_airport_code = $request ['Ticket'] [$k] ['wsTicketRequest'] ['Segment'] ['WSSegment'] ['Origin'] ['AirportCode'];
					$from_airport_name = $request ['Ticket'] [$k] ['wsTicketRequest'] ['Segment'] ['WSSegment'] ['Origin'] ['AirportName'];
					$to_airport_code = $request ['Ticket'] [$k] ['wsTicketRequest'] ['Segment'] ['WSSegment'] ['Destination'] ['AirportCode'];
					$to_airport_name = $request ['Ticket'] [$k] ['wsTicketRequest'] ['Segment'] ['WSSegment'] ['Destination'] ['AirportName'];
					$departure_datetime = $request ['Ticket'] [$k] ['wsTicketRequest'] ['Segment'] ['WSSegment'] ['DepTIme'];
					$arrival_datetime = $request ['Ticket'] [$k] ['wsTicketRequest'] ['Segment'] ['WSSegment'] ['ArrTime'];
					$iti_status = $flight_booking_itinerary_status;
					$operating_carrier = $request ['Ticket'] [$k] ['wsTicketRequest'] ['Segment'] ['WSSegment'] ['OperatingCarrier'];
					$attributes = '';
					
					$this->flight_model->save_flight_booking_itinerary_details ( $app_reference, $segment_indicator, $airline_code, $airline_name, $flight_number, $fare_class, $from_airport_code, $from_airport_name, $to_airport_code, $to_airport_name, $departure_datetime, $arrival_datetime, $iti_status, $operating_carrier, serialize ( $attributes ) );
				}
				
				$book_level_one_markup = 20; // Static Value
				$remarks = 'flight Transaction was Successfully done';
				$book_total_fare = "100"; // Static Value
				
				$this->domain_management_model->save_transaction_details ( 'flight', $app_reference, $book_total_fare, '0', $book_level_one_markup, $remarks );
			}
		} else {
			
			$response ['TicketResult'] ['PNR'] = "";
			$response ['TicketResult'] ['BookingId'] = "";
			$response ['TicketResult'] ['SSRDenied'] = "";
			$response ['TicketResult'] ['ProdType'] = "Flight";
			$response ['TicketResult'] ['PaymentReferenceNumber'] = "0";
			$response ['TicketResult'] ['Status'] ['StatusCode'] = "103"; // Provab Custom Status To Idetify Error
			$response ['TicketResult'] ['Status'] ['Description'] = "Remote IO Error"; // Provab Custom Description To Idetify Error
			$response ['TicketResult'] ['Status'] ['Category'] = "TK"; // API Provider Catagory
			$response ['TicketResult'] ['RefId'] = "";
			
			// Show Insufficent Balance Message To User using XML
		}
		return ( object ) $response;
	}
	
	/*
	 * This method is used to send change request of Ticketed Booking. Request can
	 * send either for cancellation or for change request. SendChangeRequest can be
	 * send for whole booking cancellation or for single passenger cancellation or
	 * multiple passenger cancellation. All TicketId's must be sent in case of full
	 * booking cancellation.
	 */
	public function sendchangerequest($request_type, $request) {
		$result = $this->get_flight_response ( $request_type, $request );
		
		return (object)$result;
	}
	/*
	 * This Method is used for get the status of canceled ticket
	 * 
	 */
	public function getchangerequeststatus($request_type, $request) {
		
		$result = $this->get_flight_response ( $request_type, $request );
		
		return (object)$result;
	}
	
	public function getcalendarfare($request_type, $request) {
		
		$result = $this->get_flight_response ( $request_type, $request );
		//$result->GetCalendarFareResult->CheapestFareOfEntireMonth->Tax = $this->currency->get_currency ( intval ( $result->GetCalendarFareResult->CheapestFareOfEntireMonth->Tax ) ) ['default_value'];
		$price_details = $this->currency->get_currency ( intval ( $result['GetCalendarFareResult']['CheapestFareOfEntireMonth']['Tax'] ) );
		$result['GetCalendarFareResult']['CheapestFareOfEntireMonth']['Tax'] = $price_details['default_value'];
		
		foreach ( $result['LowestFareOfDayInMonth']['WSLowestFareOfDayInMonth'] as $k => $v ) {
			
			if (isset ( $v ['Tax'] )) {
				$temp_price_details = $this->currency->get_currency ( intval ( $v->Tax ) );
				$result['LowestFareOfDayInMonth']['WSLowestFareOfDayInMonth'][$k]['Tax'] = $temp_price_details['default_value'];
			}
		}
		
		return (object)$result;
		
	}
	
	public function addbookingdetail($request_type, $request) {
		
		$result = $this->get_flight_response ( $request_type, $request );
		
		return (object)$result;
		
	}
	
	public function getbooking($request_type, $request) {
		
		$domain_details=$this->custom_db->single_table_records('domain_list', '*', array('origin'=> '1'));
		
		$result = $this->get_flight_response ( $request_type, $request );
		$total_fare = $result['GetBookingResult']['Fare']['Tax'] + $result['GetBookingResult']['Fare']['BaseFare'] + $result['GetBookingResult']['Fare']['OtherCharges'] + $result['GetBookingResult']['Fare']['ServiceTax'] + $result['GetBookingResult']['Fare']['AdditionalTxnFee'] + $result['GetBookingResult']['Fare']['AirTransFee'];
		
		$total_markup = $this->add_markup($total_fare) - $total_fare;
		
		
		$result['GetBookingResult']['Fare']['Tax'] = $result['GetBookingResult']['Fare']['Tax'] + $total_markup;
		$result['GetBookingResult']['Fare']['PublishedPrice'] = $result['GetBookingResult']['Fare']['PublishedPrice'] + $total_markup;
		$result['GetBookingResult']['Fare']['OfferedFare'] = $result['GetBookingResult']['Fare']['OfferedFare'] + $total_markup;
		
		if(isset($result['GetBookingResult']['Passenger']['WSPassenger']['Fare'])){
			
			$total_fare = $result['GetBookingResult']['Passenger']['WSPassenger']['Fare']['BaseFare'] + $result['GetBookingResult']['Passenger']['WSPassenger']['Fare']['Tax'] + $result['GetBookingResult']['Passenger']['WSPassenger']['Fare']['ServiceTax'] + $result['GetBookingResult']['Passenger']['WSPassenger']['Fare']['AdditionalTxnFee'] + $result['GetBookingResult']['Passenger']['WSPassenger']['Fare']['AirTransFee'] + $result['GetBookingResult']['Passenger']['WSPassenger']['Fare']['OtherCharges'];
			
			$total_markup = $this->add_markup($total_fare) - $total_fare;
			
			
			
			$result['GetBookingResult']['Passenger']['WSPassenger']['Fare']['Tax'] = $result['GetBookingResult']['Passenger']['WSPassenger']['Fare']['Tax'] + $total_markup;
			$result['GetBookingResult']['Passenger']['WSPassenger']['Fare']['TdsOnCommission'] = $this->agent_commision_amount($result['GetBookingResult']['Passenger']['WSPassenger']['Fare']['TdsOnCommission']);
			$result['GetBookingResult']['Passenger']['WSPassenger']['Fare']['PublishedPrice'] = $result['GetBookingResult']['Passenger']['WSPassenger']['Fare']['PublishedPrice'] + $total_markup;
			$result['GetBookingResult']['Passenger']['WSPassenger']['Fare']['OfferedFare'] = $result['GetBookingResult']['Passenger']['WSPassenger']['Fare']['OfferedFare'] + $total_markup;
			
			unset($result['GetBookingResult']['Passenger']['WSPassenger']['Fare']['ChargeBU']);
			
		} else {
			
			foreach($result['GetBookingResult']['Passenger']['WSPassenger'] as $k=>$v){
				
				$total_fare = $v['Fare']['BaseFare'] + $v['Fare']['Tax'] + $v['Fare']['ServiceTax'] + $v['Fare']['AdditionalTxnFee'] + $v['Fare']['AirTransFee'] + $v['Fare']['OtherCharges'];
			
				$total_markup = $this->add_markup($total_fare) - $total_fare;
			
				$result['GetBookingResult']['Passenger']['WSPassenger'][$k]['Fare']['Tax'] = $v['Fare']['Tax'] + $total_markup;
				$result['GetBookingResult']['Passenger']['WSPassenger'][$k]['Fare']['TdsOnCommission'] = $this->agent_commision_amount($v['Fare']['TdsOnCommission']);
				$result['GetBookingResult']['Passenger']['WSPassenger'][$k]['Fare']['PublishedPrice'] = $v['Fare']['PublishedPrice'] + $total_markup;
				$result['GetBookingResult']['Passenger']['WSPassenger'][$k]['Fare']['OfferedFare'] = $v['Fare']['OfferedFare'] + $total_markup;
				
				
				unset($result['GetBookingResult']['Passenger']['WSPassenger'][$k]['Fare']['ChargeBU']);
			}
			
		}
		
		$result['GetBookingResult']['Agency']['Name'] = $domain_details['data'][0]['domain_name'];
		$result['GetBookingResult']['Agency']['Email'] = $domain_details['data'][0]['agent_email'];
		$result['GetBookingResult']['Agency']['Phone'] = $domain_details['data'][0]['agent_mobile'];
		
		unset($result['GetBookingResult']['Agency']['AddressLine1']);
		unset($result['GetBookingResult']['Agency']['AddressLine2']);
		unset($result['GetBookingResult']['Agency']['Fax']);
		unset($result['GetBookingResult']['Agency']['City']);
		unset($result['GetBookingResult']['Agency']['PIN']);
		
		return (object)$result;
	}
	
	public function store_json($file_name, $data) {
		$file = fopen ( FLIGHT_SEARCH_LOG . $file_name . '.json', "w" );
		fwrite ( $file, json_encode($data, true) );
		
		fclose ( $file );
		
	}
	
	private function get_flight_response($request_type, $request) 
	{
		if ($this->credential_type == 'test') {
			$this->set_test_api_credentails();
		} else if ($this->credential_type == 'live') {
			$this->set_live_api_credentails();
		} 
		$header = array (
				'SiteName' => $this->api_SiteName,
				'AccountCode' => $this->api_AccountCode,
				'UserName' => $this->api_UserName,
				'Password' => $this->api_Password 
		);
		$client = new SoapClient ($this->api_url);
		$_header [] = new SoapHeader ( "http://192.168.0.170/TT/BookingAPI", 'AuthenticationData', $header, "" );
		try {
			$result = $client->__call ( $request_type, $request, array (), $_header );
			
			if($request_type !='Search'){
				$this->custom_db->insert_record ( 'provab_api_response_history', array (
					'request_type' => $request_type,
					'request' => serialize ( $request ),
					'response' => json_encode ( $result ),
					'created_datetime' => date ( 'Y-m-d H:i:s' ) 
				) );
			
			}
			$result = $this->convert_object_to_array($result);
			return $result;
		} catch ( Exception $e ) {
			return $error = $e->getMessage ();
		}
	}
	public function get_balance($balance = 0, $currency = 'INR') {
		$amount = $balance;
		/*
		 * can be used as webservice
		 * if ($this->input->post ( 'amount' )) {
		 * $amount = $this->input->post ( 'amount' );
		 * }
		 */
		$domain_balance = $this->domain_management_model->verify_current_balance ( $amount, $currency );
		return $domain_balance;
	}
	public function update_balance($amount) {
		$this->private_management_model->update_domain_balance ( get_domain_auth_id (), (- $amount) );
	}
	public function json_to_xml($json) {
		$serializer = new XML_Serializer ();
		$obj = json_decode ( $json );
		
		if ($serializer->serialize ( $obj )) {
			return $serializer->getSerializedData ();
		} else {
			return null;
		}
	}
	
	public function readfile($filename){
		
		$myfile = fopen(FLIGHT_SEARCH_LOG.$filename.'.json', "r") or die("Unable to open file!");
		$data = fread($myfile,filesize(FLIGHT_SEARCH_LOG.$filename.'.json'));
		fclose($myfile);	
		
		return $data;
	}
	
	public function convertToObject($array) {
		
        $object = new stdClass();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $value = $this->convertToObject($value);
            }
            $object->$key = $value;
        }
        return $object;
    }
	
	private function markup_details() {
		
		$markup_details = $this->private_management_model->get_markup ( 'b2c_flight' );
		// debug($markup_details); exit;
		
		$response ['status'] = false;
		if (is_array ( $markup_details )) {
			
			if (count ( $markup_details ['specific_markup_list'] ) && is_array ( $markup_details ['specific_markup_list'] )) {
				
				$response ['status'] = true;
				$response ['markup_type'] = $markup_details ['specific_markup_list'] [0] ['value_type'];
				$response ['markup_value'] = $markup_details ['specific_markup_list'] [0] ['value'];
			} else if (count ( $markup_details ['generic_markup_list'] ) && is_array ( $markup_details ['generic_markup_list'] )) {
				
				$response ['status'] = true;
				$response ['markup_type'] = $markup_details ['generic_markup_list'] [0] ['value_type'];
				$response ['markup_value'] = $markup_details ['generic_markup_list'] [0] ['value'];
			} else {
				
				$response ['status'] = false;
			}
		}
		
		return $response;
	}
	private function add_markup($val, $multiplier = 1) {
		$markup_data = $this->markup_details ();
		
		if ($markup_data ['status']) {
			
			switch ($markup_data ['markup_type']) {
				
				case 'percentage' :
					return $val + ($markup_data ['markup_value'] * $val) / (100);
					break;
				
				case 'plus' :
					return $val + ($markup_data ['markup_value'] * $multiplier);
					break;
				
				default :
			}
		} else {
			return $val;
		}
	}
	private function remove_markup($val) {
		$markup_data = $this->markup_details ();
		
		if ($markup_data ['status']) {
			
			switch ($markup_data ['markup_type']) {
				
				case 'percentage' :
					return (100 * $val) / (100 + $markup_data ['markup_value']);
					break;
				
				case 'plus' :
					return $val - $markup_data ['markup_value'];
					break;
				
				default :
			}
		} else {
			return $val;
		}
	}
	private function agent_commision_amount($amount) {
		return (($amount * $this->domain_comm_pct) / 100);
	}
	private function agent_commision_percent($amount, $base_fare) {
		$agent_commision = $this->agent_commision_amount ( $amount );
		
		return (($agent_commision * 100) / ($base_fare));
	}
	private function get_way_count($way_type, $Origin, $Destination) {
		// $request['Type']
		$is_domestic = $this->is_domestic_flight($Origin, $Destination);
		$way_count = 1;
		if ($way_type == "OneWay") {
			$way_count = 1;
		} else if ($way_type == "Return" && $is_domestic == true) {//Domestic Round way
			$way_count = 1;
		} else if ($way_type == "Return" && $is_domestic == false) {//International Round way
			$way_count = 2;
		} else {
			echo 'multicity'; // FIXME
		}
		return $way_count;
	}
		/**
	 * Check if destination are domestic
	 * @param string $from_loc Unique location code
	 * @param string $to_loc   Unique location code
	 */
	function is_domestic_flight($from_loc, $to_loc)
	{
		$query = 'SELECT count(*) total FROM flight_airport_list WHERE airport_code IN ('.$this->db->escape($from_loc).','.$this->db->escape($to_loc).') AND country != "India"';
		$data = $this->db->query($query)->row_array();
		if (intval($data['total']) > 0){
			return false;
		} else {
			return true;
		}

	}
	
	public function add_test_data($data, $enable_json = false)
	{
		if($enable_json == true) {
			$data = json_encode($data);
		}
		$this->custom_db->insert_record('test', array('test' => $data));
	}
	public function get_test_data($origin = 0, $enable_json = false)
	{
		$data = $this->custom_db->single_table_records('test', '*', array('origin' => intval($origin)));
		if($enable_json == true) {
			return json_decode($data['data'][0]['test'], true);
		} else {
			return $data['data'][0]['test'];
		}
	}
	function convert_object_to_array($object_data)
	{
		return json_decode(json_encode($object_data), true);
	}
}
// when in non-wsdl mode the uri option must be specified
$options = array (
		'uri' => 'http://192.168.0.63/provab/webservices/flight_server' 
);
// create a new SOAP server
$server = new SoapServer ( NULL, $options );
// attach the API class to the SOAP Server
$server->setClass ( 'Flight_Server' );
// start the SOAP requests handler
$server->handle ();

