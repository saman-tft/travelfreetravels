<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once BASEPATH . 'libraries/flight/Common_api_flight.php';
class Flight_crs_data extends Common_api_flight {

	protected $source_code = PROVAB_FLIGHT_CRS_BOOKING_SOURCE;
	protected $token;
	protected $ClientId;
	protected $UserName;
	protected $Password;
	protected $system;			//test/live   -   System to which we have to connect in web service
	protected $Url;
	private $service_url;
	private $TokenId;//	Token ID that needs to be echoed back in every subsequent request
	protected $ins_token_file;
	private $CI;
	private $commission = array();
	var $master_search_data;
	var $search_hash;//search

	public function __construct()
	{
		parent::__construct("Flight","flight_crs");
		$this->CI = &get_instance();
		$this->CI->load->library('Api_Interface');
		$this->CI->load->model('flight_model');
		$this->CI->load->model('custom_db');
		$this->set_api_credentials();
		error_reporting(0);
// 		ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

}	private function set_api_credentials()
	{

	/*	$flight_engine_system = $this->CI->config->item('flight_engine_system');
		$this->system = $flight_engine_system;
		$this->UserName = $this->CI->config->item($flight_engine_system.'_username');
		$this->Password =  $this->CI->config->item($flight_engine_system.'_password');
		$this->Url = $this->CI->config->item('flight_url');
		$this->ClientId = $this->CI->config->item('domain_key');*/
		//$this->UserName = 'test';
		//$this->Password = 'password'; // miles@123 for b2b

	}
	/**
	 *
	 * @param int $search_id        	
	 */
	public function search_data($search_id) {
		
		// debug($search_id); exit;
		$response ['status'] = true;
		$response ['data'] = array ();
		if (empty ( $this->master_search_data ) == true and valid_array ( $this->master_search_data ) == false) {
			$clean_search_details = $this->CI->flight_model->get_safe_search_data ( $search_id );
			$is_roundtrip  = false;
			$is_multicity  = false;
			if ($clean_search_details ['status'] == true) {
				$response ['status'] = true;
				$response ['data'] = $clean_search_details ['data'];
				// 28/12/2014 00:00:00 - date format
				if ($clean_search_details ['data'] ['trip_type'] == 'multicity') {
					$response ['data'] ['from'] = $clean_search_details ['data'] ['from'];
					$response ['data'] ['to'] = $clean_search_details ['data'] ['to'];
					$response ['data'] ['from_city'] = $clean_search_details ['data'] ['from'];
					$response ['data'] ['to_city'] = $clean_search_details ['data'] ['to'];
					$response ['data'] ['depature'] = $clean_search_details ['data'] ['depature'];
					$response ['data'] ['return'] = $clean_search_details ['data'] ['depature'];
				} else {
					$response ['data'] ['from'] = substr ( chop ( substr ( $clean_search_details ['data'] ['from'], - 5 ), ')' ), - 3 );
					$response ['data'] ['to'] = substr ( chop ( substr ( $clean_search_details ['data'] ['to'], - 5 ), ')' ), - 3 );
					$response ['data'] ['from_city'] = $clean_search_details ['data'] ['from'];
					$response ['data'] ['to_city'] = $clean_search_details ['data'] ['to'];
					$response ['data'] ['depature'] = date ( "Y-m-d", strtotime ( $clean_search_details ['data'] ['depature'] ) ) . 'T00:00:00';
					$response ['data'] ['return'] = date ( "Y-m-d", strtotime ( $clean_search_details ['data'] ['depature'] ) ) . 'T00:00:00';
				}
				
				switch ($clean_search_details ['data'] ['trip_type']) {
					
					case 'oneway' :
						$response ['data'] ['type'] = 'OneWay';
						break;
					
					case 'circle' :
						$response ['data'] ['type'] = 'Return';
						$response ['data'] ['return'] = date ( "Y-m-d", strtotime ( $clean_search_details ['data'] ['return'] ) ) . 'T00:00:00';
						$is_roundtrip = true;
						break;
					case 'multicity' :
						$response ['data'] ['type'] = 'MultiCity';
						$is_multicity  = true;
						break;
					case 'gdsspecial' :
						$response ['data'] ['type'] = 'GDS Special';
						$response ['data'] ['return'] = date ( "Y-m-d", strtotime ( $clean_search_details ['data'] ['return'] ) ) . 'T00:00:00';
						break;
					
					default :
						$response ['data'] ['type'] = 'OneWay';
				}
				$response ['data'] ['adult'] = $clean_search_details ['data'] ['adult_config'];
				$response ['data'] ['child'] = $clean_search_details ['data'] ['child_config'];
				$response ['data'] ['infant'] = $clean_search_details ['data'] ['infant_config'];
				$response['data']['total_passenger'] = intval($clean_search_details['data']['adult_config']+$clean_search_details['data']['child_config']+$clean_search_details['data']['infant_config']);
				$response ['data'] ['v_class'] = $clean_search_details ['data'] ['v_class'];
				$response ['data'] ['carrier'] = implode ( $clean_search_details ['data'] ['carrier'] );
				$response['data']['is_roundtrip'] = $is_roundtrip;
				$response['data']['is_multicity'] = $is_multicity;
				$this->master_search_data = $response ['data'];
			} else {
				$response ['status'] = false;
			}
		} else {
			$response ['data'] = $this->master_search_data;
		}
		$this->search_hash = md5 ( serialized_data ( $response ['data'] ) );
		// debug($response);exit();
		return $response;
	}


	function make_domestic_flight_req($cust_date,$total_seat,$origin,$destination,$strClass, $module="b2c"){
		$query_ho = '';
		$query_ho  = "SELECT 
						fcsd.fsid, 
						fcsd.is_domestic, 
						fcsd.origin, 
						fcsd.destination,
						fcsd.dep_to_date, 
						
						fcsd.dep_from_date, 
						
						fcsd.flight_num, 
						fcsd.carrier_code, 
						fcsd.airline_name, 
						fcsd.class_type, 
						fcsd.actual_basefare, 
						fcsd.tax, 
						fcsd.s_tax, 
						fcsd.s_charge, 
						fcsd.t_discount, 
						fcsd.no_of_stops, 
						fcsd.origin_city, 
						fcsd.destination_city, 
						fcsd.active, 
						fcsd.update_time, 
						fcsd.dep_from_date_1, 
						fcsd.crs_currency, 
						fcsd.trip_type, 
						cufd.arr_time as arrival_time, 
						cufd.dep_time as departure_time, 
						cufd.avail_date ,
						cufd.adult_base AS adult_basefare, 
						cufd.adult_tax, 
						cufd.child_base AS infant_basefare, 
						cufd.child_tax AS infant_tax, 
						cufd.avail_seat AS seats, 
						cufd.pnr
					FROM flight_crs_segment_details fcsd 
						INNER JOIN flight_crs_details fcd on(fcsd.fsid = fcd.fsid)
						INNER JOIN crs_update_flight_details cufd on(fcsd.fsid = cufd.fsid)
					where '$cust_date' between DATE(fcsd.dep_from_date) and DATE(fcsd.dep_to_date) 
							AND fcsd.active = '1'
							AND fcsd.origin = '$origin' 
							AND fcsd.destination = '$destination' 
							AND cufd.".$module."_status = '1'
							AND cufd.avail_date ='$cust_date' 

							AND cufd.avail_seat>='$total_seat' 
							AND fcsd.trip_type=0
							AND cufd.pnr != ''";
	
		if($strClass !='All'){
			$query_ho .= " AND fcsd.class_type='$strClass'";
		}

		return $query_ho;
	}


	function flight_availability_req($search_data,$module) {
		
	 
		$module = $module;
		$response ['status'] = false;

		if(valid_array($search_data)) {
			
			$segment_type 	= (($search_data['is_domestic'] == 0 || $search_data['is_domestic'] == '')?1:0);

			// if($search_data['is_domestic']=='' || $search_data['is_domestic']==0)
			// {
			// 	$segment_type=0;
			// }
			// else
			// {
			// 	$segment_type=1;
			// }

				 
			// if($se)
			$trip_type 		= isset($search_data['trip_type']) && strcmp($search_data['trip_type'],'oneway') == 0 ? 'O':'R';
			$origin 		= $search_data['from'];
			$destination 	= $search_data['to'];
			// $date 			= date('m/d/Y',strtotime($search_data['depature']));
			$arr_dep_date	= explode("T",$search_data['depature']);
			$dep_date 		= $arr_dep_date[0];

			$arr_ret_date	= explode("T",$search_data['return']);
			$ret_date 		= $arr_ret_date[0];
			
			$adults 		= $search_data['adult'];
			$childs 		= $search_data['child'];
			$infants 		= $search_data['infant'];
			$strClass 		= trim($search_data['v_class']);
			$strTripType    = $search_data['trip_type'];
			$strIsDomstic   = $search_data['is_domestic'];
			$fare_type 		= 'N'; // N for normal R for Roundtrip special
			$host_access 	= 'Y';
			$total_seat = 0;
			$total_seat = $adults+$childs;

			if(true){
				if($strTripType == "circle" && $strIsDomstic == false){
					// echo "string";exit();
					$round_int_query = '';
					$round_int_query = $this->make_search_query($dep_date,$origin,$destination,$segment_type,$strClass,$total_seat,$strTripType,$ret_date,$module);
					// debug($round_int_query);exit();


					$flight_result = $this->CI->custom_db->custon_query_run($round_int_query);
                    $arrFltDtls['onward_hdrdata'] = $flight_result;
                    // debug($flight_result);exit();
					//onward Details- start
					if(!empty($arrFltDtls['onward_hdrdata'])) {
					$round_flifgt_details_int_query = '';
					$round_flifgt_details_int_query = $this->make_flight_details_query($dep_date,$origin,$destination,$segment_type,$strClass,"oneway",$ret_date,$module,$total_seat);
					$round_baggage_int_query = $this->make_flight_baggage_details_query($dep_date,$origin,$destination,$segment_type,$strClass,"oneway",$ret_date,$module);
					$flight_result = $this->CI->custom_db->custon_query_run($round_flifgt_details_int_query);
					$baggage_details = $this->CI->custom_db->custon_query_run($round_baggage_int_query);
					
					$arrFltDtls['onward_dtldata'] = $flight_result;
					$arrFltDtls['onward_dtldata']['baggage'] = $baggage_details;
					} else {
						$arrFltDtls['onward_dtldata'] = array();
					}

				}else{
					// echo "else";exit();
					
					$query_oneway = '';
					$query_oneway = $this->make_search_query($dep_date,$origin,$destination,$segment_type,$strClass,$total_seat,$strTripType,'',$module); 



					$flight_result = $this->CI->custom_db->custon_query_run($query_oneway);
            
				
					$fsid=$flight_result[0]['fsid'];
					$arrFltDtls['onward_hdrdata'] = $flight_result;
				// 	debug($arrFltDtls);exit();

				    $query_return = '';
					$query_return = $this->make_search_query($ret_date,$destination,$origin,$segment_type,$strClass,$total_seat,$strTripType,'',$module);
					$flight_result = $this->CI->custom_db->custon_query_run($query_return);
					// debug($flight_result);exit();
					$arrFltDtls['return_hdrdata'] = $flight_result;
					// debug($query_return);exit();

					//onward Details- start
					if(!empty($arrFltDtls['onward_hdrdata'])) {
					$onward_fcd_query ='';
					$onward_fcd_query = $this->make_flight_details_query($dep_date,$origin,$destination,$segment_type,$strClass,"oneway",'',$module,$total_seat);

					$round_baggage_int_query = $this->make_flight_baggage_details_query($dep_date,$origin,$destination,$segment_type,$strClass,"oneway",$ret_date,$module,$fsid);
					$flight_result = $this->CI->custom_db->custon_query_run($onward_fcd_query);
					// debug($flight_result);exit();

					$baggage_details = array();
				// 	$baggage_details = $this->CI->custom_db->custon_query_run($round_baggage_int_query);

					// debug($flight_result);exit();

						// if ($_SERVER['REMOTE_ADDR'] == '42.109.144.215')
      //                   { 
      //                        debug($flight_result);exit();
      //                   }
      
					$arrFltDtls['onward_dtldata'] = $flight_result;
					$arrFltDtls['onward_dtldata']['baggage'] = $baggage_details;
					   //  debug($arrFltDtls);exit();

					} else {
						$arrFltDtls['onward_dtldata'] = array();
					}
					// debug($arrFltDtls['onward_dtldata']);exit();
					//return Details- start
					if(!empty($arrFltDtls['return_hdrdata'])) {
					$return_fcd_query ='';	
					$return_fcd_query = $this->make_flight_details_query($ret_date,$destination,$origin,$segment_type,$strClass,"oneway",'',$module,$total_seat);

					$flight_result = $this->CI->custom_db->custon_query_run($return_fcd_query);
					$arrFltDtls['return_dtldata'] = $flight_result;
					} else {
						$arrFltDtls['return_dtldata'] = array();
					}

					// debug($arrFltDtls);exit();
				}
			}

			$response ['status'] = true;
			$response ['data'] = $arrFltDtls;
		}
		return $response;
	}

	function make_search_query($dep_date,$origin,$destination,$segment_type,$strClass,$total_seat,$strTripType="oneway",$ret_date='',$module="b2c"){

		// echo "string";exit();
		$date_con = '';
		if($strTripType=="circle" && $segment_type==false && !empty($ret_date)){
			$date_con = " DATE(FCSD.dep_from_date) = '$dep_date' and DATE(FCSD.dep_to_date) = '$ret_date' and FCSD.trip_type=1 ";

		}else{
			$date_con = " '$dep_date' between (DATE(FCSD.dep_from_date) and DATE(FCSD.dep_to_date)) and FCSD.trip_type=0 ";
		}
		// debug($date_con);exit();
		$oneway_query = '';
		// $module = 'b2c';
		$oneway_query = "select FCSD.fsid, 
								FCSD.is_domestic, 
								FCSD.origin, 
								FCSD.destination,
								FCSD.dep_to_date, 
								FCSD.dep_from_date, 
								FCSD.flight_num, 
								FCSD.carrier_code, 
								FCSD.airline_name, 
								FCSD.class_type, 
								FCSD.actual_basefare, 
								FCSD.tax, 
								FCSD.s_tax, 
								FCSD.s_charge, 
								FCSD.t_discount, 
								FCSD.no_of_stops, 
								FCSD.origin_city, 
								FCSD.destination_city, 
								FCSD.active, 
								FCSD.update_time, 
								FCSD.dep_from_date_1, 
								FCSD.crs_currency, 
								FCSD.trip_type, 
								FCSD.fare_rules,
								FCSD.baggage, 
								FCSD.checkin_baggage, 
								FCSD.meals, 
								FCSD.extra, 
								FCSD.dep_terminal, 
								FCSD.arr_terminal, 
								
								CUFD.origin as fdid,

								CUFD.avail_date,
								CUFD.avail_seat AS seats, 
								CUFD.pnr,
								CUFD.avail_seat, 
								CUFD.booked_seat, 
								CUFD.dep_time as departure_time,
								CUFD.arr_time as arrival_time, 
								
								CUFD.adult_selling AS adult_basefare, 
								CUFD.adult_tax, 
								CUFD.child_selling AS child_basefare, 
								CUFD.child_tax AS child_tax,
								CUFD.infant_selling AS infant_basefare, 
								CUFD.infant_tax AS infant_tax 

						 from flight_crs_segment_details FCSD
						 	  join crs_update_flight_details CUFD using(fsid)
						 where $date_con 
						 		and FCSD.active = '1'
						 		AND CUFD.".$module."_status = '1'
						 		and CUFD.avail_date = '$dep_date' 
						 		and (CUFD.avail_seat - CUFD.booked_seat) >= '$total_seat' 
						 		and FCSD.origin = '$origin' 
						 		and FCSD.destination='$destination' 
						 		and FCSD.is_domestic='$segment_type'
						 		AND CUFD.pnr != '' 


						 ";
						 //and CUFD.avail_date = '$dep_date' 
						 //and FCSD.is_domestic='$segment_type'
						 if($strClass !='All'){
							$oneway_query .= "and FCSD.class_type='$strClass' ";
						}
							$oneway_query .= "GROUP BY FCSD.fsid";
					//$oneway_query .= ' order by FCSD.fsid ';
				// 	debug($oneway_query);exit();
					// debug($this->db->last_query());exit();
						// echo "string";exit('33');
		return $oneway_query;
	}
	function make_flight_details_query($dep_date,$origin,$destination,$segment_type,$strClass,$strTripType="oneway",$ret_date='',$module="b2c",$total_seat){
		// echo "string";exit();
		$date_con = '';
		$trip_type = 0;
		if($strTripType=="circle" && $segment_type==false && !empty($ret_date)){
			$date_con = " DATE(dep_from_date) = '$dep_date' and DATE(dep_to_date) = '$ret_date' and FCSD.trip_type=1 ";
			$trip_type = 1;

		}else{
			$date_con = " '$dep_date' between DATE(FCSD.dep_from_date) and DATE(FCSD.dep_to_date) and FCSD.trip_type=0 ";
			$trip_type = 0;

		}

		$fcd_query = '';
		$fcd_query = "select DISTINCT FCS.*
					  from flight_crs_details FCS
					  join crs_update_flight_details CUFD using(fsid)
					  where FCS.fsid in (select FCSD.fsid from flight_crs_segment_details FCSD
					  				where $date_con  
					  					AND CUFD.".$module."_status = '1'
					                    and CUFD.avail_date = '$dep_date' 
					                    and (CUFD.avail_seat - CUFD.booked_seat) >= '$total_seat' 

					  					AND CUFD.pnr != '' 
					  					and FCSD.origin = '$origin' 
					  					and FCSD.destination='$destination' 
					  					and FCSD.is_domestic='$segment_type'
					  					and FCSD.active='1'
					  ";
					  //and CUFD.avail_date = '$dep_date' 
					 if($strClass !='All'){
						$fcd_query .= "and FCSD.class_type='$strClass' ";
					}
					$fcd_query .= " ) and FCS.trip_type=$trip_type order by fdid ";
		// debug($fcd_query);exit();			
		return $fcd_query;

	}
	function make_flight_baggage_details_query($dep_date,$origin,$destination,$segment_type,$strClass,$strTripType="oneway",$ret_date='',$module="b2c",$fsid){
		// echo "string";exit();
		// debug($fsid);exit();
		//debug($destination);exit();
		$date_con = '';
		$trip_type = 0;
		if($strTripType=="circle" && $segment_type==false && !empty($ret_date)){
			$date_con = " DATE(dep_from_date) = '$dep_date' and DATE(dep_to_date) = '$ret_date' and FCSD.trip_type=1 ";
			$trip_type = 1;

		}else{
			$date_con = " '$dep_date' between DATE(FCSD.dep_from_date) and DATE(FCSD.dep_to_date) and FCSD.trip_type=0 ";
			$trip_type = 0;

		}

		$fcd_query = '';
		$fcd_query = "select DISTINCT FCSD.baggage,FCSD.checkin_baggage,FCSD.meals,FCSD.extra
					  from flight_crs_segment_details FCSD
					  
					  join crs_update_flight_details CUFD using(fsid)
					  join flight_crs_details FCS
					  where FCS.fsid in (select FCSD.fsid from flight_crs_segment_details FCSD
					  				where $date_con  
					  					AND CUFD.".$module."_status = '1'
					  					
					  					AND CUFD.pnr != '' 
					  					and FCSD.origin = '$origin' 
					  					and FCSD.destination='$destination' 
					  					and FCSD.is_domestic='$segment_type'
					  					
					  					and FCSD.active='1'
					  ";
					 
					  //and CUFD.avail_date = '$dep_date' 
					 if($strClass !='All'){
						$fcd_query .= "and FCSD.class_type='$strClass' ";
					}
					$fcd_query .= " ) and  FCSD.fsid='$fsid' and FCS.trip_type=$trip_type order by fdid ";
		// debug($fcd_query);exit();			
		return $fcd_query;

	}

	/**
	 * flight search request
	 *
	 * @param $search_id unique
	 *        	id which identifies search details
	 */
	function get_flight_list($search_id,$module) {
		
		$module = $module;
		$response ['data'] = array ();
		$response ['status'] = SUCCESS_STATUS;
		
		/* get search criteria based on search id */
		$search_data = $this->search_data ( $search_id );

		//$header_info = $this->get_header ();
		// debug($search_data);exit;

		// generate unique searchid string to enable caching
		$cache_search = $this->CI->config->item ( 'cache_flight_search' );
		$search_hash = $this->search_hash;
		
		if ($cache_search) {
			$cache_contents = $this->CI->cache->file->get ( $search_hash );
		}
		             
		if ($search_data ['status'] == SUCCESS_STATUS) {
			if ($cache_search == FALSE || ($cache_search === true && empty ( $cache_contents ) == true)) {
				//echo "here";exit;	
				// Flight search request

				// debug($search_data); exit;
				 
				$flight_search_request = $this->flight_availability_req ( $search_data ['data'],$module );
		
				if ($flight_search_request ['status'] = SUCCESS_STATUS) {
					// echo "Hi"; exit;
					//$this->CI->custom_db->generate_static_response (json_encode($search_data ['data']), 'flight CRS search request', 'Flight_crs' );
					//$this->CI->custom_db->generate_static_response (json_encode($flight_search_request), 'flight CRS search response', 'Flight_crs' );
					// error_reporting(E_ALL);
					try {
						// debug($flight_search_request);exit;
						if (is_array( $flight_search_request )) {
							$clean_format_data = $this->format_search_data_response ( $search_data ['data'], $flight_search_request);
				// 			debug($clean_format_data); exit;
	


                   
							if ($clean_format_data ['data']) {
								$response ['status'] = SUCCESS_STATUS;
								$response ['data']   = $clean_format_data['data'];
							} else {
								$response ['status'] = FAILURE_STATUS;
								$response ['data']   = array();
							}
						} else {
							$response ['status'] = FAILURE_STATUS;
							$response ['data']   = array();
						}
					} // catch exception
					catch ( Exception $e ) {
						$response ['status'] = FAILURE_STATUS;
						$response ['data']   = array();
					}
				}			

				if ($response ['status'] == SUCCESS_STATUS) {
					if ($cache_search) {
						$cache_exp = $this->CI->config->item ( 'cache_flight_search_ttl' );
						$this->CI->cache->file->save ( $search_hash, $response ['data'], $cache_exp );
					}
				}
			} else {
				$response ['data'] = $cache_contents;
			}
		} else {
			$response ['status'] = FAILURE_STATUS;
			$response ['data']   = array();
		}
		// debug($response);exit;
				
		return $response; 
	}
	
	function format_search_data_response( $search_data, $flight_search_res){
			

		$strTripTyp = $search_data['trip_type'];
		$strIsDomst = $search_data['is_domestic'];
		// debug($strIsDomst); exit;
		$strStatus  = $flight_search_res['status'];
		$arrResp['status'] = FAILURE_STATUS;
		if($strStatus == SUCCESS_STATUS){
			$arrResp['status'] = SUCCESS_STATUS;
			$arrFlightData = $flight_search_res['data'];
			// debug($arrFlightData); exit;
			$arrOnwardData = $this->getFlights($search_data, $arrFlightData['onward_dtldata'], $arrFlightData['onward_hdrdata'],'Onward');
		
			// debug($arrOnwardData); exit;
			// $arrOnwardData = array();
			$arrResp['data'] = array('Status' => SUCCESS_STATUS, 'Message' => '', 'Search' => array());
			$arrResp['data']['Search']['FlightDataList']['JourneyList'][0] = array();
			// $arrRespdata_Search_flight_data_list_journey_list =& $arrResp['data']['Search']['flight_data_list']['journey_list'];
			$arrResp['status'] = '1';
			$arrResp['search_hash'] = '';
			$arrResp['from_cache'] = '';
			$arrResp['cabin_class'] = 'Economy';
			// debug($arrOnwardData);exit;
			$arrResp['data']['Search']['FlightDataList']['JourneyList'][0] = $arrOnwardData;
 			//debug($arrFlightData);exit;
			if($strTripTyp == "circle" && $strIsDomst == 1){
				// echo "here";exit;
				$arrReturnData = $this->getFlights($search_data, $arrFlightData['return_dtldata'], $arrFlightData['return_hdrdata'],'Return');
				//debug($arrReturnData);exit;
				$arrResp['data']['Search']['FlightDataList']['JourneyList'][1] = $arrReturnData;
			}
				
			 //debug($arrResp); exit;
		}
		//debug($arrResp);exit;
		//exit;
		return $arrResp;
	}

	function getFlights($search_data,$arrOnwardData,$arrHdrData,$strType){
		

		$strAdult  = $search_data['adult'];
		$strChild  = $search_data['child'];
		$strInfant = $search_data['infant'];

		$arrFlgihtData = array();
		$CI = & get_instance ();
		$arrData = array();
		$strTrip_Type   = $search_data['trip_type'];
		$str_isdomestic = $search_data['is_domestic'];
 		if(!empty($arrOnwardData)){
			$arrHdr_Data = $this->getHdrData($arrHdrData);
			for($o=0; $o<count($arrOnwardData); $o++){
				// 		debug($arrHdr_Data);exit();

				$strFsID = $arrOnwardData[$o]['fsid'];
				$strFdID = $arrHdr_Data[$strFsID]['fdid'];
				$strTripType = $arrOnwardData[$o]['trip_type'];
				if($strTrip_Type == "circle" && $str_isdomestic == false){
					$TripType    = (($strTripType==0)?"Onward":"Return");
				}else{
					$TripType    = $strType;
				}

				$origin_code 							= $arrOnwardData[$o]['origin'];
				if($TripType == "Return"){
					$strOrgDate 						= $arrOnwardData[$o]['departure_from_date'];
					$arrOrgDate[0]						= $strOrgDate;			
					$return 						    = $search_data['return'];
					$return							    = explode("T",$return);	
					$strDepDateTime 					=  $return[0]." ".$arrHdrData[$o]['departure_time'];
					$arrFlData['Origin']['DateTime'] 	=  $strDepDateTime;	
					$strArrDateTime 						=  $return[0]." ".$arrHdrData[$o]['arrival_time'];
					$arrFlData['Destination']['DateTime'] 	=  $strArrDateTime;
				}
				else{
					$strOrgDate 						= $search_data['depature'];
					$arrOrgDate							= explode("T",$strOrgDate);
					}
				$arrFlt_summary['Origin']['loc']   		= $origin_code;
				$strOrgDate_M 							= date("m",$strOrgDate);
				$org_loc_details = $CI->db_cache_api->get_airport_details ($origin_code, $strOrgDate_M);
			    $strOrgDtlsTZ    = $org_loc_details['timezone_offset'];
				
				$dest_code 							 	= $arrOnwardData[$o]['destination'];
				if($TripType == "Onward"){
					
					$strArrivalDate 					= $this->getArrivalDate($arrOrgDate[0],$arrOnwardData[$o]['arrival_time'],$arrOnwardData[$o]['departure_time']);
					$strDepDateTime 					=  $arrOrgDate[0]." ".$arrHdrData[$o]['departure_time'];	
					
					$strArrDateTime 						=  $strArrivalDate." ".$arrHdrData[$o]['arrival_time'];
					$arrFlData['Origin']['DateTime'] 	    =   $strArrDateTime;
					$arrFlData['Destination']['DateTime'] 	=  $strDepDateTime;
					
					$arrFlData['baggage'] 					=  $arrOnwardData['baggage'];
					
				}else{
					$strArrivalDate 					= $arrOnwardData[$o]['departure_to_date'];
				}
				$strArrDate_M 							= date("m",$strArrivalDate);
				
				$des_loc_details = $CI->db_cache_api->get_airport_details ($dest_code, $strArrDate_M);
				$strDestDtlsTZ   = $des_loc_details['timezone_offset'];


				// $arrFlData['journey_number'] 		=  $strType;
				$arrFlData['journey_number'] 		=  $TripType;
				$arrFlData['origin']['loc'] 		=  $origin_code;
				$arrFlData['Origin']['AirportCode'] =  $origin_code;
				$arrFlData['Origin']['AirportName'] =  $org_loc_details['airport_city'];
				// $arrFlData['Origin']['AirportCode'] =  $org_loc_details['airport_city'];
				$arrFlData['Origin']['fsid'] 		=  $strFsID;
				$arrFlData['Origin']['fdid'] 		=  $strFdID;

				$arrFlData['Origin']['CityName'] 	=  $org_loc_details['airport_city'];
				

				$arrFlData['Origin']['date'] 		=  $arrOrgDate[0];
				$arrFlData['Origin']['time'] 		=  $arrHdrData[$o]['departure_time'];
				// $arrFlData['Origin']['fdtv'] 		=  "";
				$arrFlData['Origin']['FDTV'] 		=  "1620884700";
				$arrFlData['Destination']['loc'] 		=  $arrOnwardData[$o]['destination'];
				$arrFlData['Destination']['CityName'] 		=  $des_loc_details['airport_city'];
				

				$strArrDateTime 						=  $strArrivalDate." ".$arrHdrData[$o]['arrival_time'];

				// $arrFlData['Destination']['datetime'] 	=  $strArrDateTime;
				// $arrFlData['Destination']['date'] 		=  $strArrivalDate;
				$arrFlData['Destination']['time'] 		=  $arrHdrData[$o]['arrival_time'];
				// $arrFlData['Destination']['fdtv'] 		=  "";
				$arrFlData['Destination']['AirportCode'] 		=  $arrOnwardData[$o]['destination'];
				// $arrFlData['Destination']['CityName'] 		=  $des_loc_details['airport_city'];
				$arrFlData['Destination']['AirportName'] 		=  $des_loc_details['airport_city'];
				
				
				// $arrFlData['Destination']['DateTime'] 	=  $strArrDateTime;
				
				$arrFlData['Destination']['FATV'] 		=  "1620867600";
				

				$departure_dt_tz = $strDepDateTime.$strOrgDtlsTZ;
				$arrival_dt_tz   = $strArrDateTime.$strDestDtlsTZ;
				// echo $departure_dt_tz."<br/>";
				// echo $arrival_dt_tz."<br/>";
				$duration = calculate_duration ( $departure_dt_tz, $arrival_dt_tz ); // seconds
				//debug($duration);exit;

				$arrFlData['duration_seconds'] 		=  $duration;
				$arrFlData['Duration'] 				=  get_time_duration_label ( $duration );
				$arrFlData['OperatorCode'] 		=  $arrOnwardData[$o]['airline_name'];

				$arrFlData['DisplayOperatorCode'] = '';
				$arrFlData['OperatorName'] 		=  $arrOnwardData[$o]['airline_name'];
				$arrFlData['FlightNumber'] 		=  $arrOnwardData[$o]['flight_num'];
				$arrFlData['CabinClass'] 			=  $arrOnwardData[$o]['class_type'];
				$arrFlData['Attr']				= array("AvailableSeats" => 0);
				// debug($arrFlData);exit();
				$arrData[$strFsID][] = $arrFlData;
				// $i++;
			}
			if(!empty($arrData)){
				$strFlCnt = 0;
				if(valid_array($arrData)){
					foreach($arrData as $FKey=>$F_Val){
						//debug($FKey);exit();
						if($FKey>0){
						$arrFlight_Details_details = array();
						$arrFlight_Details_summary = array();
						for($fv=0; $fv<count($F_Val); $fv++){
						    //$arrFlt_summary = array();
							//debug($FKey);
							// debug(count($F_Val)); exit();
							$FVal     = $F_Val[$fv];
							// debug($FVal);exit;
							$arrFirst = $FVal;
							$arrEnd   = $FVal;
				// 			debug($arrHdr_Data[$FKey]);exit;
							$strFsId  = $FKey;
				// 			debug($arrFirst); 
							$arrDataH = $arrHdr_Data[$FKey];

							//price calculation /////////////////////
							
							$arrHdr_Data[$FKey]['reference_pnr'] = [$FKey];
							$fare = $this->price_calculation_crs($arrHdr_Data[$FKey],$search_data);
						    $arrFlight_fare['fare'][0] = $fare[0];
							// $arrFlight_fare['fare'][0] = $arrFlight_fare['price'];

							$arrFlight_fare['PaxWise']['Adult']  = $strAdult;
							$arrFlight_fare['PaxWise']['Child']  = $strChild;
							$arrFlight_fare['PaxWise']['Infant']  = $strInfant;
							$arrFlight_fare['PaxWise'][0]['Taxes']  = $strTotalTax;


							// debug($arrEnd);exit;
							$strDepDateTime = $arrFirst['Origin']['DateTime'];
							$strArrDateTime = $arrEnd['Destination']['DateTime'];
							$strJrnyJNumber   						= ($FVal['journey_number'] == "Onward")?0:1;
							$arrFlt_summary['journey_number'] 		= $strJrnyJNumber;
							$origin_code 							= $arrFirst['Origin']['AirportCode'];
							$strOrgDate 							= $arrFirst['Origin']['date'];
							$arrFlt_summary['origin']['loc']   		= $origin_code;
							$strOrgDate_M 							= date("m",$strOrgDate);
						//	error_reporting(E_ALL);
							$org_loc_details = $CI->db_cache_api->get_airport_details ($origin_code, $strOrgDate_M);
					//debug($this->CI->db->last_query());exit('ggg');
							$arrFlt_summary['origin']['city']  		= $org_loc_details['airport_city'];
							$arrFlt_summary['origin']['datetime']  	= $strDepDateTime;
							$arrFlt_summary['origin']['date']  	 	= $strOrgDate;
							$arrFlt_summary['origin']['time']  	 	= $arrFirst['Origin']['time'];
							$arrFlt_summary['origin']['fdtv']  	 	= "";

							$dest_code 							 	= $arrEnd['Destination']['AirportCode'];
							$strArrivalDate 						= $arrEnd['Destination']['DateTime'];
							$strArrDate_M 							= date("m",$strArrivalDate);
							$des_loc_details = $CI->db_cache_api->get_airport_details ($dest_code, $strArrDate_M);

							$arrFlt_summary['destination']['loc']   		= $arrEnd['Destination']['AirportCode'];
							$arrFlt_summary['destination']['city']  		= $des_loc_details['airport_city'];
							$arrFlt_summary['destination']['datetime']  	= $strArrDateTime;
							$arrFlt_summary['destination']['date']  		= $arrEnd['Destination']['DateTime'];
							$arrFlt_summary['destination']['time']  		= $arrEnd['Destination']['time'];
							$arrFlt_summary['destination']['fdtv']  		= "";

							$strOrgDtlsTZ    = $org_loc_details['timezone_offset'];
							$strDestDtlsTZ   = $des_loc_details['timezone_offset'];

							$departure_dt_tz = $strDepDateTime.$strOrgDtlsTZ;
							$arrival_dt_tz   = $strArrDateTime.$strDestDtlsTZ;
							$duration = calculate_duration ( $departure_dt_tz, $arrival_dt_tz ); // seconds
						

							$arrFlt_summary['operator_code']  			= $arrHdr_Data[$FKey]['OperatorCode'];
							$arrFlt_summary['DisplayOperatorCode']  	= $arrHdr_Data[$FKey]['OperatorCode'];
							$arrFlt_summary['OperatorName'] 			= $arrHdr_Data[$FKey]['OperatorName'];
							$arrFlt_summary['FlightNumber'] 			= $arrHdr_Data[$FKey]['FlightNumber'];
							$arrFlt_summary['CabinClass'] 				= $arrHdr_Data[$FKey]['CabinClass'];
							$arrFlt_summary['fare_class'] 				= "";
							$arrFlt_summary['no_of_stops'] 				= $arrHdr_Data[$FKey]['no_of_stops'];
							$arrFlt_summary['is_leg'] 					= ($arrHdr_Data[$FKey]['no_of_stops'] == 0)?0:1;
							$arrFlt_summary['cabin_bag'] 				= "";
							$arrFlt_summary['hand_bag'] 				= "";
							
							$arrFlt_summary['duration_seconds'] 	= $duration;
							$arrFlt_summary['Duration'] 				= get_time_duration_label ( $duration );
							$bag['baggage'][0]['baggage']=$arrHdr_Data[$FKey]['baggage'];
							$bag['baggage'][0]['checkin_baggage']=$arrHdr_Data[$FKey]['checkin_baggage'];
							$bag['baggage'][0]['meals']=$arrHdr_Data[$FKey]['meals'];
							$bag['baggage'][0]['extra']=$arrHdr_Data[$FKey]['extra'];
							$FVal['baggage'] = $bag['baggage'];
							$FVal['fare_rule'][0]['fare_rules']=$arrHdr_Data[$FKey]['fare_rules'];
							$FVal['terminal'][0]['dep_terminal']=$arrHdr_Data[$FKey]['dep_terminal'];
							$FVal['terminal'][0]['arr_terminal']=$arrHdr_Data[$FKey]['arr_terminal'];
							$arrFlight_Details_details[$strJrnyJNumber][] = $FVal;
				// 			$arrFlight_Details_details[$strJrnyJNumber][0] = $bag;
							if($strTrip_Type == "circle" && $str_isdomestic == false){
								$arrFlight_Details_summary[] = $arrFlt_summary;
							}else{
								$arrFlight_Details_summary[$strJrnyJNumber] = $arrFlt_summary;
							}
							// debug($arrFlight_Details_details); exit;
							//debug($arrFlgihtData);
							// debug($FVal); exit;
						}

						// exit;
						$arrFlgihtData[$strFlCnt] = $arrFlight_fare;
				// 		debug($arrFlight_Details_summary); exit;
						$arrFlgihtData[$strFlCnt]['flight_details']['details']	= $arrFlight_Details_details;
						$arrFlgihtData[$strFlCnt]['flight_details']['summary']	= $arrFlight_Details_summary;
						$arrFlgihtData[$strFlCnt]['token'] 			= serialized_data($arrFlgihtData);
						$arrFlgihtData[$strFlCnt]['token_key'] 		= md5($arrFlgihtData[$strFlCnt]['token']);
						$arrFlgihtData[$strFlCnt]['booking_source'] = PROVAB_FLIGHT_CRS_BOOKING_SOURCE;

						$strFlCnt++;
						}
					}					
				}
				
			}
// 			debug($arrFlgihtData);exit;
		}
		return $arrFlgihtData;
	}

	function price_calculation_crs($data,$search_data){

		// $arrFlt_summary['reference_pnr'] 				= $FKey;

		 // debug($search_data);exit();
		$infant_data  = array();
		$child_data  = array();
		$adult_price  = $search_data['adult_config']*($data['adult_basefare']+$data['adult_tax']);
		$child_price  = $search_data['child_config']*($data['child_basefare']+$data['child_tax']);
		$infant_price = $search_data['infant_config']*($data['infant_basefare']+$data['infant_tax']);
		$markup = 0; // need to add markup
		$data['reference_pnr'] = str_replace("[", '', $data['reference_pnr']);
		$data['reference_pnr'] = str_replace("]", '', $data['reference_pnr']);

		
		$api_total_display_fare =$adult_price+$child_price+$infant_price+$markup;
		$api_total_display_fare_withoutmarkup = $api_total_display_fare-$markup;
		// debug($search_data['infant_config']);exit();
		if($search_data['infant_config']){
			// $infant_data = array (
   //                                                  'BasePrice' => $search_data['infant_config']*$data['infant_basefare']+$markup,
   //                                                  'Tax' => $search_data['infant_config']*$data['infant_tax'],
   //                                                  'TotalPrice' => $search_data['infant_config']*$data['infant_basefare']+$search_data['infant_config']*$data['infant_tax']+$markup,
   //                                                  'PassengerCount' => $search_data['infant_config']
   //                                              );

				$infant_data = array (
                                                    'BasePrice' => $data['infant_basefare']+$markup,
                                                    'Tax' => $data['infant_tax'],
                                                    'Sub_total' => $data['infant_tax']+$data['infant_basefare']+$markup,
                                                   'TotalPrice' => $search_data['infant_config']*$data['infant_basefare']+$search_data['infant_config']*$data['infant_tax']+$markup,
                                                    'PassengerCount' => $search_data['infant_config']
                                                );
		}else{
			$data['infant_basefare'] = 0;
			$data['infant_tax'] = 0;
		}

		if($search_data['child_config']){
			// $child_data = array (
			// 	   				'BasePrice' => $search_data['child_config']*$data['child_basefare']+$markup,
   //                                                  'Tax' => $search_data['child_config']*$data['child_tax'],
   //                                                  'TotalPrice' => $search_data['child_config']*$data['child_basefare']+$search_data['child_config']*$data['child_tax']+$markup,
   //                                                  'PassengerCount' => $search_data['child_config']
   //                                              );
					$child_data = array (
				   				'BasePrice' => $data['child_basefare']+$markup,
                                                    'Tax' => $data['child_tax'],
                                                    'Sub_total' => $data['child_tax']+$data['child_basefare']+$markup,
                                                    'TotalPrice' => $search_data['child_config']*$data['child_basefare']+$search_data['child_config']*$data['child_tax']+$markup,
                                                    'PassengerCount' => $search_data['child_config']
                                                );
		}else{
			$data['child_basefare'] = 0;
			$data['child_tax'] = 0;
		}

		$api_total_fare = $search_data['adult_config']*$data['adult_basefare']+$search_data['child_config']*$data['child_basefare']+$search_data['infant_config']*$data['infant_basefare'];
		$api_total_tax = $search_data['adult_config']*$data['adult_tax']+$search_data['child_config']*$data['child_tax']+$search_data['infant_config']*$data['infant_tax'];

			$fare =  array
                        (
                           array
                                (
                                    'publicFareBasisCodes' => array
                                        (
                                            array
                                                (
                                                    'publicFareBasisCodes_value' => 0
                                                )

                                        ),

                                    'api_currency' => APP_CURRENCY,
                                    'reference_pnr'=> $data['reference_pnr'][0],
                                    'api_total_display_fare' => $api_total_display_fare,
                                    'api_total_display_fare_withoutmarkup' => $api_total_display_fare_withoutmarkup,
                                    'total_breakup' => array
                                        (
                                            'api_total_fare' => $api_total_fare+$markup,
                                            'api_total_tax' => $api_total_tax,
                                            'api_total_fare_publish' => 0,
                                            'api_total_ax_publish' => 0
                                        ),

                                    'api_total_display_fare_normal' => 0,
                                    'pax_breakup' => array
                                       (
                                            // 'ADT' => array
                                            //     (
                                            //         'BasePrice' => $search_data['adult_config']*$data['adult_basefare']+$markup,
                                            //         'Tax' => $search_data['adult_config']*$data['adult_tax'],
                                            //         'TotalPrice' => $search_data['adult_config']*$data['adult_basefare']+$search_data['adult_config']*$data['adult_tax']+$markup,
                                            //         'PassengerCount' => $search_data['adult_config']
                                            //     ),

                                                 'ADT' => array
                                                (

                                                    'BasePrice' => $data['adult_basefare']+$markup,
                                                    'Tax' => $data['adult_tax'],
                                                    'Sub_total'=>$data['adult_basefare']+$data['adult_tax']+$markup,
                                                  'TotalPrice' => $search_data['adult_config']*$data['adult_basefare']+$search_data['adult_config']*$data['adult_tax']+$markup,
                                                    'PassengerCount' => $search_data['adult_config']
                                                ),
                                                'CHD' => $child_data,
                                                'INF' => $infant_data

                                        

                                        )
                                   )
                            );

                    return $fare;
	}

	function getArrivalDate($strDepDate, $strAriveTime, $strDepTime){
		//$strAriveTime = "01:39:00";
		$strTimeTaken = $strAriveTime-$strDepTime;
		//echo $strAriveTime."-".$strDepTime." = ".$strTimeTaken;exit;
		$strArrivalDate = $strDepDate;
		if($strTimeTaken <= 0){
			$strArrivalDate = date('Y-m-d', strtotime($strDepDate . ' +1 day'));
		}
		return $strArrivalDate;
	}

	function getHdrData($arrHdrData){
		if(!empty($arrHdrData)){
			$arrData = array();
			foreach($arrHdrData as $DKey=>$DVal){
				$strFsID = $DVal['fsid'];
				$arrData[$strFsID] = $DVal;
			}
		}
		return @$arrData;
		// debug($arrHdrData);exit;
	}

	function format_search_data_response_BAK( $search_data, $flight_search_res){
		//debug($flight_search_res);exit;
		$strStatus = $flight_search_res['status'];
		$arrResp['status'] = FAILURE_STATUS;
		if($strStatus == SUCCESS_STATUS){
			$arrResp['status'] = SUCCESS_STATUS;
			$arrFltData = $flight_search_res['data'];
			$arrFsid 	= array();
			for($i=0; $i<count($arrFltData); $i++){
				$arrJourneyData  = $arrFltData[$i];
				//debug($arrJourneyData);exit;
				$arrFsId = array();
				$arrRespData[$i] = array();
				$jrnyNo = 0;
				$arrFsID = array();
				for($seg=0; $seg<count($arrJourneyData); $seg++){
					$strFsId = $arrJourneyData[$seg]['fsid'];
					//$arrRespData[$i][$strFsId][] = $arrJourneyData[$seg]['fdid'];
					$arrFsID[$strFsId][] = "";
					if(!in_array($strFsId, $arrFsid)){
						$arrFsid[] = $strFsId;
					}
					//$arrFlgihtDetails = array();
					$arrFlgihtDetails[$strFsId][]['journey_number'] = ($i == 0)?"Onward":"Return";

					
					$arrFlt_summary[$strFsId] = array();
					$arrFlt_summary[$strFsId]['journey_number'] 		= $jrnyNo;
					$arrFlt_summary[$strFsId]['origin']['loc']   		= $arrJourneyData[$seg]['hdrOrigin'];
					$arrFlt_summary[$strFsId]['origin']['city']  		= $arrJourneyData[$seg]['hdrOrigin'];
					$arrFlt_summary[$strFsId]['origin']['datetime']  	= $search_data['depature']."-".$arrJourneyData[$seg]['departure_time'];
					$arrFlt_summary[$strFsId]['origin']['date']  	 	= $search_data['depature'];
					$arrFlt_summary[$strFsId]['origin']['time']  	 	= $arrJourneyData[$seg]['departure_time'];
					$arrFlt_summary[$strFsId]['origin']['fdtv']  	 	= "";

					$arrFlt_summary[$strFsId]['destination']['loc']   		= $arrJourneyData[$seg]['hdrDest'];
					$arrFlt_summary[$strFsId]['destination']['city']  		= $arrJourneyData[$seg]['hdrDest'];
					$arrFlt_summary[$strFsId]['destination']['datetime']  	= $search_data['depature']."-".$arrJourneyData[$seg]['arrival_time'];
					$arrFlt_summary[$strFsId]['destination']['date']  		= $search_data['depature'];
					$arrFlt_summary[$strFsId]['destination']['time']  		= $arrJourneyData[$seg]['arrival_time'];
					$arrFlt_summary[$strFsId]['destination']['fdtv']  		= "";

					$arrFlt_summary[$strFsId]['operator_code']  			= $arrJourneyData[$seg]['carrier_code'];
					$arrFlt_summary[$strFsId]['display_operator_code']  	= "";
					$arrFlt_summary[$strFsId]['operator_name'] 				= $arrJourneyData[$seg]['airline_name'];
					$arrFlt_summary[$strFsId]['flight_number'] 				= $arrJourneyData[$seg]['flight_num'];
					$arrFlt_summary[$strFsId]['cabin_class'] 				= $arrJourneyData[$seg]['class_type'];
					$arrFlt_summary[$strFsId]['fare_class'] 				= "";
					$arrFlt_summary[$strFsId]['no_of_stops'] 				= (count($arrFsID[$strFsId]) == 1)?0:count($arrFsID[$strFsId])-1;
					$arrFlt_summary[$strFsId]['is_leg'] 					= (count($arrFsID[$strFsId]) == 1)?0:1;
					$arrFlt_summary[$strFsId]['cabin_bag'] 					= "";
					$arrFlt_summary[$strFsId]['hand_bag'] 					= "";
					$arrFlt_summary[$strFsId]['duration_seconds'] 			= $arrJourneyData[$seg]['departure_time']."--".$arrJourneyData[$seg]['arrival_time'];
					$arrFlt_summary[$strFsId]['duration'] 					= $arrJourneyData[$seg]['departure_time']."--".$arrJourneyData[$seg]['arrival_time'];					

					$arrFlightSegmentDtls['summary'][0] = @$arrFlt_summary[$strFsId];

					

					$arrFlightSegmentDtls['details'] = $arrFlgihtDetails[$strFsId];
					$arrRespData[$i][$strFsId]['flight_details'] = $arrFlightSegmentDtls;
				}
				$arrData = $arrRespData[$i];
				/*$arrNewData = array();
				foreach ($arrData as $arrKey => $arrVal) {
					$arrNewData[] = $arrVal;
				}*/
				debug($arrData);exit;
				$arrResp['data'][$i] = $arrData;
			}
			debug($arrResp);exit;
		}
		exit;
		return $arrRespData;
	}

	/**
	 * Update markup currency for price object of flight
	 *
	 * @param object $price_summary
	 * @param object $currency_obj
	 */
	function update_markup_currency(& $price_summary, & $currency_obj, $level_one_markup=false, $current_domain_markup=true, $multiplier=1, $specific_markup_config = array())
	{
		$markup_list = array('OfferedFare');
		$markup_summary = array();
		foreach ($price_summary as $__k => $__v) {
			if (is_numeric($__v) == true) {
				$ref_cur = $currency_obj->force_currency_conversion($__v);	//Passing Value By Reference so dont remove it!!!
				$price_summary[$__k] = $ref_cur['default_value'];			//If you dont understand then go and study "Passing value by reference"

				if (in_array($__k, $markup_list)) {
					$temp_price = $currency_obj->get_currency($__v, true, $level_one_markup, $current_domain_markup, $multiplier, $specific_markup_config);
				} elseif (is_array($__v) == false) {
					$temp_price = $currency_obj->force_currency_conversion($__v);
				} else {
					$temp_price['default_value'] = $__v;
				}
				$markup_summary[$__k] = $temp_price['default_value'];
			}
		}

		//Markup
		//PublishedFare
		$Markup = 0;
		$price_summary['_Markup'] = 0;
		if (isset($markup_summary['OfferedFare'])) {
			$Markup = $markup_summary['OfferedFare'] - $price_summary['OfferedFare'];
			$markup_summary['PublishedFare'] = $markup_summary['PublishedFare'] + $Markup;
		}
		$markup_summary['_Markup'] = $Markup;
		return $markup_summary;
	}

	

/**
 * get total price from summary object
 *
 * @param object $price_summary
 */
function total_price($price_summary, $retain_commission=false, $currency_obj = '')
	{
		
		$com = 0;
		$com_tds = 0;
		if ($retain_commission == false) {
			$com = 0;
			$com_tds += floatval($currency_obj->calculate_tds($price_summary['AgentCommission']));
			$com_tds += floatval($currency_obj->calculate_tds(@$price_summary['PLBEarned']));
			$com_tds += floatval($currency_obj->calculate_tds(@$price_summary['IncentiveEarned']));
		} else {
			$com += floatval(@$price_summary['AgentCommission']);
			$com += floatval(@$price_summary['PLBEarned']);
			$com += floatval(@$price_summary['IncentiveEarned']);
			$com_tds = 0;
		}
		return (floatval(@$price_summary['OfferedFare'])+$com+$com_tds);
	}
/**
 * Process booking
 *
 * @param string $book_id
 * @param array $booking_params
 *        	Needed as token is not saved in database
 */
// function process_booking($book_id, $temp_booking) {}


/**
	 * booking_url to be used
	 */
	function booking_url($search_id)
	{
		return base_url().'index.php/flight/booking/'.intval($search_id);
	}

	///////////Done by Jagannath
	public function search_data_in_preferred_currency($search_result, $currency_obj)
	{
		// echo "Hii";
		// exit;
		$flights = $search_result['JourneyList'];
		$flight_list = array();
		foreach($flights as $fk => $fv){
			foreach($fv as $list_k => $list_v){
				//debug($list_v); exit;
				$flight_list[$fk][$list_k] = $list_v;
				$flight_list[$fk][$list_k]['FareDetails'] = $this->preferred_currency_fare_object($list_v['Price'], $currency_obj);
				// debug($flight_list[$fk][$list_k]['FareDetails']); exit;
				$flight_list[$fk][$list_k]['PassengerFareBreakdown'] = $this->preferred_currency_paxwise_breakup_object($list_v, $currency_obj);
				// debug($flight_list[$fk][$list_k]['PassengerFareBreakdown']); exit;
			}
		}
		$search_result['JourneyList'] = $flight_list;
		return $search_result;
	}

	private function preferred_currency_fare_object($fare_details, $currency_obj, $default_currency = '')
	{
		if(isset($fare_details['TotalDisplayFare']) == true && isset($fare_details['PriceBreakup']) == true){
			$base_fare = 				$fare_details['PriceBreakup']['BasicFare'];
			$tax = 						$fare_details['PriceBreakup']['Tax'];
			$published_fare = 			$fare_details['TotalDisplayFare'];
			$agent_commission = 		$fare_details['PriceBreakup']['AgentCommission'];
			$agent_tds_on_commission =	$fare_details['PriceBreakup']['AgentTdsOnCommision'];
		} else {
			$base_fare = 				$fare_details['BaseFare'];
			$tax = 						$fare_details['Tax'];
			$published_fare = 			$fare_details['PublishedFare'];
			$agent_commission = 		$fare_details['AgentCommission'];
			$agent_tds_on_commission =	$fare_details['AgentTdsOnCommision'];
		}
		$FareDetails = array();
		$FareDetails['Currency'] = 				empty($default_currency) == false ? $default_currency : get_application_currency_preference();
		$FareDetails['BaseFare'] = 				get_converted_currency_value($currency_obj->force_currency_conversion($base_fare));
		$FareDetails['Tax'] = 					get_converted_currency_value($currency_obj->force_currency_conversion($tax));
		$FareDetails['PublishedFare'] = 		get_converted_currency_value($currency_obj->force_currency_conversion($published_fare));
		$FareDetails['AgentCommission'] = 		get_converted_currency_value($currency_obj->force_currency_conversion($agent_commission));
		$FareDetails['AgentTdsOnCommision'] = 	get_converted_currency_value($currency_obj->force_currency_conversion($agent_tds_on_commission));
		$OfferedFare = 							($FareDetails['PublishedFare']-$FareDetails['AgentCommission']);
		$FareDetails['OfferedFare'] = 			$OfferedFare;
		return $FareDetails;
	}
	public function preferred_currency_paxwise_breakup_object($fare_details, $currency_obj)
	{
		//error_reporting(E_ALL);
		//debug($fare_details); exit;
		$PassengerFareBreakdown = array();
		/*foreach($fare_details as $k => $v) {
			$PassengerFareBreakdown[$k] = $v;
			if(isset($v['BasePrice'])){
				$base_fare = $PassengerFareBreakdown[$k]['BasePrice'];
				unset($PassengerFareBreakdown[$k]['BasePrice']);
			} else {
				$base_fare = $PassengerFareBreakdown[$k]['BaseFare'];
			}
			$PassengerFareBreakdown[$k]['BaseFare'] = get_converted_currency_value($currency_obj->force_currency_conversion($base_fare));
			
		}*/
		//foreach($fare_details as $k => $v) {
			if(isset($fare_details['PaxWise']['Adult']) &&  $fare_details['PaxWise']['Adult'] >= 1){
				$PassengerCount =$fare_details['PaxWise']['Adult'];

				if(isset($fare_details['price'])){
					$base_fare = $fare_details['price']['total_breakup']['api_total_fare'];
					$tax = $fare_details['price']['total_breakup']['api_total_tax'];
					$total_fare = $fare_details['price']['api_total_display_fare'];
					//unset($PassengerFareBreakdown[$k]['BasePrice']);

					$PassengerFareBreakdown['ADT']['Tax'] = $tax;
					$PassengerFareBreakdown['ADT']['TotalPrice'] = $total_fare;

					$PassengerFareBreakdown['ADT']['PassengerCount'] = $PassengerCount;

					$PassengerFareBreakdown['ADT']['BaseFare'] = $base_fare;
				}

			}
			if(isset($fare_details['PaxWise']['Child']) && $fare_details['PaxWise']['Child'] >= 1){
				$PassengerCount =$fare_details['PaxWise']['Adult'];
				if(isset($fare_details['price'])){
					$base_fare = $fare_details['total_breakup']['api_total_fare'];
					$tax = $fare_details['total_breakup']['api_total_tax'];
					$total_fare = $fare_details['api_total_display_fare'];
					//unset($PassengerFareBreakdown[$k]['BasePrice']);

					$PassengerFareBreakdown['CHD']['Tax'] = $tax;
					$PassengerFareBreakdown['CHD']['TotalPrice'] = $total_fare;

					$PassengerFareBreakdown['CHD']['PassengerCount'] = $PassengerCount;

					$PassengerFareBreakdown['CHD']['BaseFare'] = $base_fare;
				}
			}
			if(isset($fare_details['PaxWise']['Infant']) && $fare_details['PaxWise']['Infant'] >= 1){
				$PassengerCount =$fare_details['PaxWise']['Adult'];
				if(isset($fare_details['price'])){
					$base_fare = $fare_details['total_breakup']['api_total_fare'];
					$tax = $fare_details['total_breakup']['api_total_tax'];
					$total_fare = $fare_details['api_total_display_fare'];
					//unset($PassengerFareBreakdown[$k]['BasePrice']);

					$PassengerFareBreakdown['INF']['Tax'] = $tax;
					$PassengerFareBreakdown['INF']['TotalPrice'] = $total_fare;

					$PassengerFareBreakdown['INF']['PassengerCount'] = $PassengerCount;

					$PassengerFareBreakdown['INF']['BaseFare'] = $base_fare;
				}
			}
			
			
		//}
		//debug($PassengerFareBreakdown); exit;
		return $PassengerFareBreakdown;
	}
	public function format_search_response($search_result, $currency_obj, $search_id, $module, $from_cache = false, $search_hash = '')
	{
		// debug($search_result); exit;
		
		$formatted_search_data = array();
		$journey_summary = $this->extract_journey_details($search_id);
		//Flight List
		// debug($journey_summary); exit;
		$flights = $search_result['JourneyList'];
		// debug($flights); exit;
		$formatted_flight_list = array();
		$ins_token = true;
		$formatted_flight_list = $this->extract_flight_details($flights, $currency_obj, $search_id, $module, $ins_token);
		// echo "extract_flight_details"; exit;
		// debug($formatted_flight_list); exit;
		//Assigning the Data
		$formatted_search_data['booking_url'] = $this->booking_url(intval($search_id));
		// debug($formatted_search_data['booking_url']); exit; Done
		//$formatted_search_data['data']['JourneySummary'] = $journey_summary;
		// debug($formatted_flight_list); exit;
		$formatted_search_data['data']['Flights'] = $formatted_flight_list;

		// set session expiry time
		$session_expiry_details = $this->set_flight_search_session_expiry($from_cache, $search_hash);
		// debug($session_expiry_details); exit;
		$formatted_search_data['session_expiry_details'] = $session_expiry_details;
		// debug($formatted_search_data); exit;
		return $formatted_search_data;
	}
	private function extract_journey_details($search_id)
	{
		$search_data = $this->search_data($search_id);
		$search_data = $search_data['data'];
		// debug($search_data); exit;
		$PassengerConfig = array();
		$PassengerConfig['Adult'] = intval($search_data['adult']);
		$PassengerConfig['Child'] = intval($search_data['child']);
		$PassengerConfig['Infant'] = intval($search_data['infant']);
		$PassengerConfig['TotalPassenger'] = intval($search_data['total_passenger']);
		//Journey Summary
		$journey_summary = array();
		
		$origin = is_array($search_data['from']) ? $search_data['from'][0] : $search_data['from'];
		$destination = is_array($search_data['to']) ? end($search_data['to']): $search_data['to'];
	
		
		$journey_summary['Origin'] = $origin;
		$journey_summary['Destination'] = $destination;
		
		$journey_summary['IsDomestic'] = $search_data['is_domestic'];
		$journey_summary['RoundTrip'] = $search_data['is_roundtrip'];
		$journey_summary['MultiCity'] = $search_data['is_multicity'];
		
		$journey_summary['PassengerConfig'] = $PassengerConfig;
		//debug($search_data); exit;
		// changed by badri
		if($journey_summary['IsDomestic'] == true && $journey_summary['RoundTrip'] == true) {
			$is_domestic_roundway = true;
		} else {
			$is_domestic_roundway = false;
		}
		$journey_summary['IsDomesticRoundway'] = $is_domestic_roundway;
		return $journey_summary;
	}
	public function extract_flight_details($flights, $currency_obj, $search_id, $module,$ins_token=false)
	{
		// echo "Hi";
		$formatted_flight_list = array();
		//Token Details
		$token = array();//This will be stored in local file so less data gets transmitted
		$this->ins_token_file = time().rand(100, 10000);
		// echo "Hi";
		foreach($flights as $fk => $fv){
			$formatted_flight_list[$fk] = $this->extract_flight_segment_fare_details($fv, $currency_obj, $search_id, $module,$ins_token, $token, $fk);
		}
		// echo "extract_flight_segment_fare_details"; exit;
		$ins_token === true ? $this->save_token($token) : '';
		// debug($formatted_flight_list); exit;
		return $formatted_flight_list;
	}

	public function extract_flight_segment_fare_details($flights, $currency_obj, $search_id, $module,$ins_token = false, & $token = array(), $flight_index = 0)
	{
		//debug($flights);exit;
		$flights = force_multple_data_format($flights);
		//debug($flights);exit;
		// echo "Hii"; exit;
		$flight_list = array();
		foreach($flights as $list_k => $list_v){
			//debug($list_v);
			//Pushing data into the Token
			if ($ins_token === true) {
				$tkn_key = $flight_index.$list_k;
				$this->push_token($list_v, $token, $tkn_key);
			}

			$flight_list[$list_k]['AirlineRemark'] = 'Provab Special Fare. Non Flexi / Non Refundable';
			//$flight_list[$list_k]['AirlineRemark'] = $this->filter_airline_remark(@$list_v['Attr']['AirlineRemark'], $module);
			// echo "Hi"; exit;
			$flight_list[$list_k]['FareDetails'] = $this->get_fare_object($list_v, $currency_obj, $search_id, $module);
			//debug($flight_list[$list_k]['FareDetails']); 
			$flight_list[$list_k]['PassengerFareBreakdown'] = $list_v['PassengerFareBreakdown'];
			// debug($list_v); exit;
			$segments = $this->extract_segment_details($list_v['flight_details']['details']);
			// debug($segments); exit;
			$flight_list[$list_k]['SegmentSummary'] = $segments['segment_summary'];
			$flight_list[$list_k]['SegmentDetails'] = $segments['segment_full_details'];
			// debug($list_v); exit;
			$flight_list[$list_k]['ProvabAuthKey'] = $list_v['token'];
            
			$flight_list[$list_k]['booking_source'] = PROVAB_FLIGHT_CRS_BOOKING_SOURCE;
			
			//Hold Ticket
			if(isset($list_v['HoldTicket']) == true){
				$hold_ticket = $list_v['HoldTicket'];
			} else {
				$hold_ticket = false;
			}
			$flight_list[$list_k]['HoldTicket'] = $hold_ticket;
			
			if(isset($list_v['Token']) == true){
				$flight_list[$list_k]['Token'] = $list_v['Token'];
			}
			if(isset($list_v['TokenKey']) == true){
				$flight_list[$list_k]['TokenKey'] = $list_v['TokenKey'];
			}
			// $flight_list[$list_k]['Attr'] = $list_v['Attr'];
			$flight_list[$list_k]['Attr'] = array("IsRefundable"=>0, "AirlineRemark"=>'');
		}
		//exit;
		// debug($flight_list); exit;
		return $flight_list;
	}
	private function push_token(& $flight, & $token, $key)
	{
		//push data inside token before adding token and key values
		$token[$key] = $flight;

		//Adding token and token key
		$flight['Token'] = serialized_data($this->ins_token_file.DB_SAFE_SEPARATOR.$key);
		$flight['TokenKey'] = md5($flight['Token']);
	}
	private function filter_airline_remark($AirlineRemark, $module)
	{
		$filtered_airline_remark = '';
		if($module == 'b2c'){
			if(preg_match_all('~\b(special|bag|meal|meals)\b~i', $AirlineRemark) == true && preg_match_all('~\b(Series|operated|commissionable)\b~i', $AirlineRemark) == false){
				$filtered_airline_remark = $AirlineRemark;
			}
		} else if($module == 'b2b'){
			if(preg_match_all('~\b(special|bag|meal|meals)\b~i', $AirlineRemark) == true && preg_match_all('~\b(Series|operated)\b~i', $AirlineRemark) == false){
				$filtered_airline_remark = $AirlineRemark;
			}
		}
		return $filtered_airline_remark;
	}
		private function get_fare_object($flight_details, $currency_obj, $search_id, $module)
	{
		// echo "Hi"; exit;
		// debug($flight_details); exit;	
		// error_reporting(E_ALL);
		$FareDetails = array();
		$b2c_price_details = array();
		$b2b_fare_details = array();

		// $api_price_details = $flight_details['FareDetails'];
		$api_price_details = array(
			"Currency" => $flight_details['price']['api_currency'],
			"BaseFare" => $flight_details['price']['total_breakup']['api_total_fare'],
			"Tax" => $flight_details['price']['total_breakup']['api_total_tax'],
			"PublishedFare" => $flight_details['price']['total_breakup']['api_total_tax'] + $flight_details['price']['total_breakup']['api_total_fare'],
			"AgentCommission" => 0,
			"AgentTdsOnCommision" =>0,
			"OfferedFare" => $flight_details['price']['total_breakup']['api_total_tax'] + $flight_details['price']['total_breakup']['api_total_fare']
		);
		// debug($api_price_details); exit;
		$api_price_details1 = $flight_details['price'];
		$currency_symbol = $currency_obj->get_currency_symbol($currency_obj->to_currency);
		//SPECIFIC MARKUP CONFIG DETAILS
		$specific_markup_config = array();
		$specific_markup_config = $this->get_airline_specific_markup_config($flight_details['flight_details']['details']);//Get the Airline code for setting airline-wise markup
		//Updating the Commission
		// echo "Hi"; exit; Done
		if ($module == 'b2c') {
			//B2C
			$admin_price_details = $this->update_search_markup_currency($flight_details['price'], $currency_obj, false, true, $search_id, $specific_markup_config);//B2c:DONT CHANGE
			// echo "Hii"; exit;
			// debug($admin_price_details); exit;
			// $o_Total_Tax	= ($this->tax_service_sum($admin_price_details, $flight_details['FareDetails']));
			// echo "Tax servixe sum"; exit;
			// $o_Total_Fare	= ($this->total_price($admin_price_details, false, $currency_obj));
			// echo "total_price"; exit;
			$b2c_price_details['BaseFare'] = $api_price_details1['total_breakup']['api_total_fare'];
			// $b2c_price_details['TotalTax'] = $o_Total_Tax;
			$b2c_price_details['TotalTax'] = $api_price_details1['total_breakup']['api_total_tax'];
			$b2c_price_details['TotalFare'] = $api_price_details1['api_total_display_fare'];
			// $b2c_price_details['TotalFare'] = $o_Total_Fare;
			$b2c_price_details['Currency'] = $api_price_details1['api_currency'];
			$b2c_price_details['CurrencySymbol'] = $currency_symbol;
			$FareDetails['b2c_PriceDetails'] = $b2c_price_details;//B2C PRICE DETAILS
		} else if ($module == 'b2b') {
			//B2B
			//Updating the Commission
			//$this->get_commission($flight_details, $currency_obj);
			// debug($res); exit;
			// debug($flight_details['FareDetails']); exit;
			//$admin_price_details = $this->update_search_markup_currency($flight_details['FareDetails'], $currency_obj, true, false, $search_id, $specific_markup_config);//B2B:DONT CHANGE
			// debug($admin_price_details); exit;
			// debug($flight_details['FareDetails']); exit;
			$agent_price_details = $this->update_search_markup_currency($flight_details['FareDetails'], $currency_obj, true, true, $search_id, $specific_markup_config);
			$b2b_price_details = $this->b2b_price_details($api_price_details, $admin_price_details, $agent_price_details, $currency_obj);
			$b2b_price_details['Currency'] = $api_price_details['Currency'];
			$b2b_price_details['CurrencySymbol'] = $currency_symbol;
			$b2b_price_details['_Markup'] = 0;
			$FareDetails['b2b_PriceDetails'] = $b2b_price_details;//B2B PRICE DETAILS
			// debug($b2b_price_details); exit;
		}
		$FareDetails['api_PriceDetails'] = $api_price_details;//API PRICE DETAILS
		// debug($FareDetails); exit;
		return $FareDetails;
	}
	public function get_airline_specific_markup_config($segment_details)
	{
		$specific_markup_config = array();
		if(isset($segment_details[0][0]['OperatorCode'])){
			$airline_code = $segment_details[0][0]['OperatorCode'];
		} else {
			$airline_code = $segment_details[0][0]['AirlineDetails']['AirlineCode'];
		}
		$category = 'airline_wise';
		$specific_markup_config[] = array('category' => $category, 'ref_id' => $airline_code);
		return $specific_markup_config;
	}
	function update_search_markup_currency(& $price_summary, & $currency_obj, $level_one_markup=false, $current_domain_markup=true, $search_id=0, $specific_markup_config = array())
	{
		// debug($price_summary); exit;
		if (intval($search_id) > 0) {
			$search_data = $this->search_data($search_id);
		}

		$total_pax = intval($this->master_search_data['adult_config'] + $this->master_search_data['child_config'] + $this->master_search_data['infant_config']);
		$trip_type = $this->master_search_data['trip_type'];

		$way_count = $this->way_multiplier($this->master_search_data['trip_type'], $this->master_search_data['is_domestic'], $search_id);
		// echo "Hii"; exit;
		$multiplier = ($total_pax*$way_count);
		// debug($price_summary); exit;
		//  
		return $this->update_markup_currency($price_summary, $currency_obj, $level_one_markup, $current_domain_markup, $multiplier, $specific_markup_config);
		// debug($resul); exit;
	}
	private function way_multiplier($way_type, $domestic, $search_id=0)
	{
		$way_count = 0;
		if($way_type == 'multicity'){
			$search_data = $this->search_data($search_id);
			$way_count = intval(count($search_data['data']['from']));
		}else if ($way_type == 'oneway' || $domestic == true) {
			$way_count = 1;
		} else {
			$way_count = 2;
		}
		return $way_count;
	}
	function tax_service_sum($markup_price_summary, $api_price_summary, $retain_commission=false)
	{
		//AirlineTransFee - Not Available
		//sum of tax and service ;
		if ($retain_commission == true) {
			$commission = 0;
			$commission_tds = 0;
		} else {
			$commission = $markup_price_summary['AgentCommission'];
			$commission_tds = $markup_price_summary['AgentTdsOnCommision'];
		}
		$markup_price = 0;
		$markup_price = $markup_price_summary['OfferedFare'] - $api_price_summary['OfferedFare'];
		return ((floatval($markup_price + @$markup_price_summary['AdditionalTxnFee'])+floatval(@$markup_price_summary['Tax'])+floatval(@$markup_price_summary['OtherCharges'])+floatval(@$markup_price_summary['ServiceTax'])) - $commission + $commission_tds);
	}
	public function extract_segment_details($segment_details)
	{
		// debug($segment_details); exit();
		$segment_summary = array();
		$segment_full_details = array();
		// debug($segment_details); exit;
		foreach($segment_details as $seg_k => $seg_v) {
			$this->update_segment_details($seg_v);
			//Segment Summry
			$OriginDetails = $seg_v[0]['Origin'];
			$AirlineDetails = $seg_v[0]['AirlineDetails'];
			$OriginDetails['_DateTime'] = local_time($OriginDetails['DateTime']);
			$OriginDetails['_Date'] = local_date_new($OriginDetails['DateTime']);
			$last_segment_details = end($seg_v);
			$DestinationDetails = $last_segment_details['Destination'];
			$DestinationDetails['_DateTime'] = local_time($DestinationDetails['DateTime']);
			$DestinationDetails['_Date'] = local_date_new($DestinationDetails['DateTime']);
			$total_stops = (count($seg_v)-1);
			$total_duaration = $this->segment_total_duration($seg_v);
			$segment_summary[$seg_k]['AirlineDetails'] = $AirlineDetails;
			$segment_summary[$seg_k]['Origin'] = $OriginDetails;
			$segment_summary[$seg_k]['Destination'] = $DestinationDetails;
			$segment_summary[$seg_k]['TotalStops'] = $total_stops;
			$segment_summary[$seg_k]['TotalDuaration'] = $total_duaration;
			//Segment Details
			foreach($seg_v as $seg_details_k => $seg_details_v){
				//Origin Details
				$AirlineDetails = $seg_details_v['AirlineDetails'];
				$OriginDetails = $seg_details_v['Origin'];
				
				$OriginDetails['_DateTime'] = local_time($OriginDetails['DateTime']);
				$OriginDetails['_Date'] = local_date_new($OriginDetails['DateTime']);
				//Destination Details
				$DestinationDetails = $seg_details_v['Destination'];
				$DestinationDetails['_DateTime'] = local_time($DestinationDetails['DateTime']);
				$DestinationDetails['_Date'] = local_date_new($DestinationDetails['DateTime']);
				$SegmentDuration = get_time_duration_label($seg_details_v['SegmentDuration']*60);//Converting into seconds
				
				if(isset($seg_v[$seg_details_k+1]) == true) {
					$next_seg_info = $seg_v[$seg_details_k+1];
					$WaitingTime = (get_time_duration_label(calculate_duration($seg_details_v['Destination']['DateTime'], $next_seg_info['Origin']['DateTime'])));
				}
				$Baggage = '';
				$CabinBaggage = '';
				if(valid_array($seg_details_v['Attr']) == true){
					$Baggage = @$seg_details_v['Attr']['Baggage'];
					$CabinBaggage = @$seg_details_v['Attr']['CabinBaggage'];
					if(isset($seg_details_v['Attr']['AvailableSeats'])){
						$segment_full_details[$seg_k][$seg_details_k]['AvailableSeats'] = $seg_details_v['Attr']['AvailableSeats'];
					}
				}
				$segment_full_details[$seg_k][$seg_details_k]['Baggage'] = $Baggage;
				$segment_full_details[$seg_k][$seg_details_k]['CabinBaggage'] = $CabinBaggage;
				$segment_full_details[$seg_k][$seg_details_k]['AirlineDetails'] = $AirlineDetails;
				$segment_full_details[$seg_k][$seg_details_k]['OriginDetails'] = $OriginDetails;
				$segment_full_details[$seg_k][$seg_details_k]['DestinationDetails'] = $DestinationDetails;
				$segment_full_details[$seg_k][$seg_details_k]['SegmentDuration'] = $SegmentDuration;
				$segment_full_details[$seg_k][$seg_details_k]['WaitingTime'] = '';
				if(isset($WaitingTime) == true) {
					$segment_full_details[$seg_k][$seg_details_k]['WaitingTime'] = $WaitingTime;
				}
			}
				
		}
		$data['segment_summary'] = $segment_summary;
		$data['segment_full_details'] = $segment_full_details;
		return $data;
	}

	private function update_segment_details(& $segments)
	{
		foreach($segments as $k => & $v){
			$v['SegmentDuration'] = $this->flight_segment_duration($v['Origin']['AirportCode'], $v['Destination']['AirportCode'], $v['Origin']['DateTime'], $v['Destination']['DateTime']);
			$AirlineDetails = array();
			$AirlineDetails['AirlineCode'] = $v['OperatorCode'];
			$AirlineDetails['AirlineName'] = $v['OperatorName'];
			$AirlineDetails['FlightNumber'] = $v['FlightNumber'];
			$AirlineDetails['FareClass'] = $v['CabinClass'];
			unset($v['OperatorCode'], $v['OperatorName'], $v['FlightNumber'], $v['CabinClass'], $v['DisplayOperatorCode']);
			$v['AirlineDetails'] = $AirlineDetails;
		}
	}
	private function flight_segment_duration($departure_airport_code, $arrival_airport_code, $departure_datetime, $arrival_datetime)
	{
		$departure_datetime = date('Y-m-d H:i:s', strtotime($departure_datetime));
		$arrival_datetime = date('Y-m-d H:i:s', strtotime($arrival_datetime));
		//Get TimeZone of Departure and Arrival Airport
		$departure_timezone_offset = $this->get_airport_timezone_offset($departure_airport_code, $departure_datetime);
		$arrival_timezone_offset = $this->get_airport_timezone_offset($arrival_airport_code, $arrival_datetime);
		//Converting TimeZone to Minutes
		$departure_timezone_offset = $this->convert_timezone_offset_to_minutes($departure_timezone_offset);
		$arrival_timezone_offset = $this->convert_timezone_offset_to_minutes($arrival_timezone_offset);
		//Getting Total time difference between 2 airports
		$timezone_offset = ($departure_timezone_offset-$arrival_timezone_offset);
		//Calculating Total Duration Time
		$segment_duration = calculate_duration($departure_datetime,$arrival_datetime);
		//Converting into minutes
		$segment_duration = ($segment_duration)/60;//Converting int minutes
		//Updating the total duration with time zone offset difference
		$segment_duration = ($segment_duration+$timezone_offset);
		return $segment_duration;
	}
	private function get_airport_timezone_offset($airport_code,$journey_date)
	{
		//FIXME: cache the data
		$journey_month = date('m', strtotime($journey_date));
		$query = 'select FAL.airport_code,FAT.start_month,FAT.end_month,FAT.timezone_offset from flight_airport_list FAL
					join flight_airport_timezone_offset FAT on FAT.flight_airport_list_fk=FAL.origin
					where airport_code = "'.$airport_code.'" and (start_month<='.$journey_month.' or end_month>='.$journey_month.')
					order by 
					CASE
					WHEN start_month	= '.$journey_month.' THEN 1
		            WHEN end_month	= '.$journey_month.' THEN 2
					ELSE 3 END';
		$timezone_offset = $this->CI->db->query($query)->result_array();
		return $timezone_offset[0]['timezone_offset'];
	}
		private function convert_timezone_offset_to_minutes($timezone_offset)
	{
		$add_mode_sign = $timezone_offset[0];
		$time_zone_details = explode(':', $timezone_offset);
		$hours = abs(intval($time_zone_details[0]));
		$minutes = abs(intval($time_zone_details[1]));
		$minutes = $hours * 60  + $minutes;
		$minutes = ($add_mode_sign.$minutes);
		return $minutes;
	}
	private function segment_total_duration($segments)
	{
		$total_duration = 0;
		foreach($segments as $k => $v){
			$total_duration += $v['SegmentDuration'];
			//adding waiting time
			if(isset($segments[$k+1]['Origin']) == true) {
				$total_duration += $this->wating_segment_time($v['Destination']['AirportCode'], 
															$segments[$k+1]['Origin']['AirportCode'] , 
															$v['Destination']['DateTime'], 
															$segments[$k+1]['Origin']['DateTime']);
			}
		}
		$total_duration = ($total_duration*60);//Converting into seconds
		return get_time_duration_label($total_duration);
	}
	private function wating_segment_time($arrival_airport_city, $departure_airport_city, $arrival_datetime, $departure_datetime)
	{
		$departure_datetime = date('Y-m-d H:i:s', strtotime($departure_datetime));
		$arrival_datetime = date('Y-m-d H:i:s', strtotime($arrival_datetime));
		//Get TimeZone of Departure and Arrival Airport
		$departure_timezone_offset = $GLOBALS['CI']->flight_model->get_airport_timezone_offset($departure_airport_city, $departure_datetime);
		$arrival_timezone_offset = $GLOBALS['CI']->flight_model->get_airport_timezone_offset($arrival_airport_city, $arrival_datetime);
		//Converting TimeZone to Minutes
		$departure_timezone_offset = $this->convert_timezone_offset_to_minutes($departure_timezone_offset);
		$arrival_timezone_offset = $this->convert_timezone_offset_to_minutes($arrival_timezone_offset);
		//Getting Total time difference between 2 airports
		$timezone_offset = ($arrival_timezone_offset-$departure_timezone_offset);
		//Calculating the Waiting time between 2 segments
		$current_segment_arr = strtotime($arrival_datetime);
		$next_segment_dep = strtotime($departure_datetime);
		$segment_waiting_time = ($next_segment_dep - $current_segment_arr);
		
		//Converting into minutes
		$segment_waiting_time = ($segment_waiting_time)/60;//Converting into minutes
		//Updating the total duration with time zone offset difference
		$segment_waiting_time = ($segment_waiting_time+$timezone_offset);
		return $segment_waiting_time;
	}

	private function save_token($token)
	{
		$file = DOMAIN_TMP_UPLOAD_DIR.$this->ins_token_file.'.json';
		file_put_contents($file, json_encode($token));
	}

	function set_flight_search_session_expiry($from_cache = true, $search_hash)
	{
		$response = array();
		if($from_cache == false){
			$GLOBALS['CI']->session->set_userdata(array($search_hash => date("Y-m-d H:i:s")));
			$response['session_start_time'] = $GLOBALS ['CI']->config->item ('flight_search_session_expiry_period');
		}else{
			$start_time = $GLOBALS['CI']->session->userdata($search_hash);
			$current_time = date("Y-m-d H:i:s");
			$diff = strtotime($current_time) - strtotime($start_time);
			$response['session_start_time'] = $GLOBALS ['CI']->config->item ('flight_search_session_expiry_period') - $diff;
		}
		$response['search_hash'] = $search_hash;
		return $response;

	}

	function get_commission(& $__trip_flight, & $currency_obj)
	{
		//$res = $currency_obj->get_commission();
		 // debug($__trip_flight); exit;
		$this->commission = $currency_obj->get_commission();
		if (valid_array($this->commission) == true && intval($this->commission['admin_commission_list']['value']) > 0) {
			//update commission
			//$bus_row = array(); Preserving Row data before calculation
			// $core_agent_commision = ($__trip_flight['FareDetails']['PublishedFare']-$__trip_flight['FareDetails']['OfferedFare']);
			$core_agent_commision = ($__trip_flight['price']['api_total_display_fare']-$__trip_flight['price']['api_total_display_fare']);
			// debug($core_agent_commision); exit;
			$com = $this->calculate_commission($core_agent_commision);
			// debug($__trip_flight['FareDetails']); exit;
			$this->set_b2b_comm_tag($__trip_flight['FareDetails'], $com, $currency_obj);
		} else {
			//update commission
			$this->set_b2b_comm_tag($__trip_flight['FareDetails'], 0, $currency_obj);
		}
	}

	private function calculate_commission($agent_com)
	{
		$agent_com_row = $this->commission['admin_commission_list'];
		// debug($agent_com_row); exit();
		$b2b_comm = 0;
		if ($agent_com_row['value_type'] == 'percentage') {
			//%
			$b2b_comm = ($agent_com/100)*$agent_com_row['value'];
		} else {
			//plus
			$b2b_comm = ($agent_com-$agent_com_row['value']);
		}
		return number_format($b2b_comm, 2, '.', '');
	}

	function set_b2b_comm_tag(& $v, $b2b_com=0, $currency_obj)
	{
		$v['ORG_AgentCommission'] = $v['AgentCommission'];
		$v['ORG_TdsOnCommission'] = $v['AgentTdsOnCommision'];
		$v['ORG_OfferedFare'] = $v['OfferedFare'];
		
		//$admin_com = $v['AgentCommission'] - $b2b_com;
		$core_agent_commision = ($v['PublishedFare']-$v['OfferedFare']);
		$admin_com = $core_agent_commision - $b2b_com;
		
		$v['OfferedFare'] = $v['OfferedFare']+$admin_com;
		$v['AgentCommission'] = $b2b_com;
		$v['TdsOnCommission'] = $currency_obj->calculate_tds($core_agent_commision);
	}

	function b2b_price_details($api_price_details, $admin_price_details, $agent_price_details, $currency_obj)
	{
		
		// debug($api_price_details); exit();
		$total_price['BaseFare']	= $api_price_details['BaseFare'];
		// $total_price['_CustomerBuying']	= $agent_price_details['PublishedFare']; COMM
		$total_price['_CustomerBuying']	= $api_price_details['BaseFare']+$api_price_details['Tax'];
		// $total_price['_AgentBuying']	= $admin_price_details['OfferedFare']; COMM
		$total_price['_AgentBuying']	= $api_price_details['BaseFare'];
		// $total_price['_AdminBuying']	= $api_price_details['OfferedFare']; COMM
		$total_price['_AdminBuying']	= $api_price_details['BaseFare'];
		// $total_price['_AgentMarkup']	= $total_price['_Markup'] = $agent_price_details['OfferedFare'] - $admin_price_details['OfferedFare']; COMM
		$total_price['_AgentMarkup']	= 0;
		// $total_price['_AdminMarkup']	= ($total_price['_AgentBuying'] - $total_price['_AdminBuying']); COMM
		$total_price['_AdminMarkup']	= 0;
		// $total_price['_Commission']		= round($agent_price_details['PublishedFare'] - $agent_price_details['OfferedFare'], 3); COMM
		$total_price['_Commission']		= 0;
		$total_price['_tdsCommission']	= $currency_obj->calculate_tds($total_price['_Commission']);//Includes TDS ON PLB AND COMMISSION COMM
		$total_price['_tdsCommission']	= 0;
		// $total_price['_AgentEarning']	= $total_price['_Commission']+$total_price['_Markup'] - $total_price['_tdsCommission']; COMM
		$total_price['_AgentEarning']	= 0;
		// $total_price['_TaxSum']			= $agent_price_details['PublishedFare'] - $agent_price_details['BaseFare']; COMM
		$total_price['_TaxSum']			= $api_price_details['Tax'];
		// $total_price['_BaseFare']		= $agent_price_details['BaseFare']; COMM
		$total_price['_BaseFare']		= $api_price_details['BaseFare'];
		// $total_price['_TotalPayable']	= $total_price['_AgentBuying']+$total_price['_tdsCommission']; COMM
		$total_price['_TotalPayable']	= $api_price_details['BaseFare']+$api_price_details['Tax'];
		// $total_price['TotalFare']	= $total_price['_AgentBuying']+$total_price['_tdsCommission']+$total_price['_CustomerBuying'];
		// debug($total_price); exit;
		return $total_price;
	}

	function booking_form($isDomestic, $token='', $token_key='', $search_access_key='', $promotional_plan_type='', $booking_source = PROVAB_FLIGHT_CRS_BOOKING_SOURCE)
	{
		$booking_form = '';

		$booking_form .= '<input type="hidden" name="is_domestic" class="" value="'.$isDomestic.'">';
		$booking_form .= '<input type="hidden" name="token[]" class="token data-access-key" value="'.$token.'">';
		$booking_form .= '<input type="hidden" name="token_key[]" class="token_key" value="'.$token_key.'">';
	//	$booking_form .= '<input type="hidden" name="search_access_key[]" class="search-access-key" value="'.$search_access_key.'">';
		$booking_form .= '<input type="hidden" name="promotional_plan_type[]" class="promotional-plan-type" value="'.$promotional_plan_type.'">';
		
		if (empty($booking_source) == false) {
			$booking_form .= '<input type="hidden" name="booking_source" class="booking-source" value="'.$booking_source.'">';
		}
		return $booking_form;
	}

	public function unserialized_token($token, $token_key)
	{
		$response['data'] = array();
		$response['status'] = true;
		foreach($token as $___k => $___v) {
			$tmp_tkn = $this->read_token($___v);
			if ($tmp_tkn != false) {
				$response['data']['token'][$___k] = $tmp_tkn;
				$response['data']['token_key'] = $token_key[$___k];
			} else {
				$response['data']['token'][$___k] = false;
			}

			if ($response['status'] == true) {
				if ($response['data']['token'][$___k] == false) {
					$response['status'] = false;
				}
			}
		}

		return $response;
	}

	public function read_token($token_key)
	{
		$token_key = explode(DB_SAFE_SEPARATOR, unserialized_data($token_key));
		if (valid_array($token_key) == true) {
			$file = DOMAIN_TMP_UPLOAD_DIR.$token_key[0].'.json';//File name
			$index = $token_key[1]; // access key

			if (file_exists($file) == true) {
				$token_content = file_get_contents($file);
				if (empty($token_content) == false) {
					$token = json_decode($token_content, true);
					if (valid_array($token) == true && isset($token[$index]) == true) {
						return $token[$index];
					} else {
						return false;
						echo 'Token data not found';
						exit;
					}
				} else {
					return false;
					echo 'Invalid File access';
					exit;
				}
			} else {
				return false;
				echo 'Invalid Token access';
				exit;
			}
		} else {
			return false;
			echo 'Invalid Token passed';
			exit;
		}
	}

	function fare_quote_details($flight_booking_details)
	{	
		// debug($flight_booking_details); exit;
		// $response['status'] = SUCCESS_STATUS; // update
		// extract($flight_booking_details);
		// $unique_search_access_key = array_unique($flight_booking_details['search_access_key']);
		// if (count($unique_search_access_key) == 1) {
		// 	//single request - all search except domestic round way uses this
		// 	if (count($flight_booking_details['search_access_key']) == 1) {
		// 		$tmp_token = $this->run_fare_quote(array($flight_booking_details['token'][0]), $flight_booking_details['search_access_key'][0]);
				
		// 		$this->update_fare_quote_details($flight_booking_details, 0, $tmp_token['data'], $tmp_token['status'], $response);
		// 	} elseif (count($flight_booking_details['search_access_key']) == 2) {
		// 		//(domestic and round)
		// 		//---Merge both and send single key
		// 		echo 'Under Construction - Arjun';
		// 		exit;
		// 	}
		// } else {
		// 	//multiple request - domestic round way uses this T1 - R1, T2 - R2
		// 	foreach ($flight_booking_details['token'] as $___k => $___v) {
		// 		if ($response['status'] == SUCCESS_STATUS) {
		// 			//If LCC THEN RUN ELSE JUST UPDATE SAME VALUE IF NEEDED
		// 			$tmp_token = $this->run_fare_quote(array($___v), $flight_booking_details['search_access_key'][$___k]);
		// 			$this->update_fare_quote_details($flight_booking_details, $___k, $tmp_token['data'], $tmp_token['status'], $response);

		// 		}
		// 	}

		// }

		// //Update response with the data returned - $flight_booking_details
		// $response['data']	= $flight_booking_details;

		// if (count($unique_search_access_key) != 1) {
		// 	/*foreach($response['token'] as $k=>$v){

		// 	$response['data']['token'][$k]['ProvabAuthKey']=$v['ProvabAuthKey'];
		// 	$response['ProvabAuthKey']="return";   // remove this for testing
		// 	}*/
		// 	unset($response['token']);
		// }
		// // debug($response); exit;
		$flight_booking_details['status'] = 1;
		return $flight_booking_details;

	}

	public function farequote_data_in_preferred_currency($fare_quote_details, $currency_obj)
	{
		$flight_quote = $fare_quote_details['data']['token'];
		$flight_quote_data = array();
		foreach($flight_quote as $fk => $fv){
			$flight_quote_data[$fk] = $fv;
			$flight_quote_data[$fk]['FareDetails'] = $this->preferred_currency_fare_object($fv['Price'], $currency_obj);
			$flight_quote_data[$fk]['PassengerFareBreakdown'] = $this->preferred_currency_paxwise_breakup_object($fv['Price']['PassengerBreakup'], $currency_obj);
			unset($flight_quote_data[$fk]['Price']);
		}
		$fare_quote_details['data']['token'] = $flight_quote_data;
		return $fare_quote_details;
	}

	public function merge_flight_segment_fare_details($flight_details)
	{
		$flight_pre_booking_summery = array();
		$PassengerFareBreakdown = array();
		$SegmentDetails = array();
		$SegmentSummary = array();
		$FareDetails = $this->merge_fare_details($flight_details);
		$PassengerFareBreakdown = $this->merge_passenger_fare_break_down($flight_details);
		$SegmentDetails = $this->merge_segment_details($flight_details);
		$SegmentSummary = $this->merge_segment_summary($flight_details);

		$flight_pre_booking_summery['FareDetails'] = $FareDetails;
		$flight_pre_booking_summery['PassengerFareBreakdown'] = $PassengerFareBreakdown;
		$flight_pre_booking_summery['SegmentDetails'] = $SegmentDetails;
		$flight_pre_booking_summery['SegmentSummary'] = $SegmentSummary;
		$flight_pre_booking_summery['HoldTicket'] = $flight_details[0]['HoldTicket'];
		return $flight_pre_booking_summery;
	}


	public function merge_fare_details($flight_details)
	{
		$FareDetails = array();
		$temp_fare_details = group_array_column($flight_details, 'FareDetails');
		$APIPriceDetails = array_merge_numeric_values(group_array_column($temp_fare_details, 'api_PriceDetails'));
		if(isset($temp_fare_details[0]['b2c_PriceDetails']) == true) {//B2C
			$B2CPriceDetails = array_merge_numeric_values(group_array_column($temp_fare_details, 'b2c_PriceDetails'));
			$FareDetails['b2c_PriceDetails'] = $B2CPriceDetails;
		} elseif (isset($temp_fare_details[0]['b2b_PriceDetails']) == true) {//B2B
			$B2BPriceDetails = array_merge_numeric_values(group_array_column($temp_fare_details, 'b2b_PriceDetails'));
			$FareDetails['b2b_PriceDetails'] = $B2BPriceDetails;
		}
		$FareDetails['api_PriceDetails'] = $APIPriceDetails;
		return $FareDetails;
	}

	public function merge_passenger_fare_break_down($flight_details)
	{
		$PassengerFareBreakdown = array();
		$tmp_fare_breakdown = group_array_column($flight_details, 'PassengerFareBreakdown');
		foreach($tmp_fare_breakdown as $k => $v) {
			foreach($v as $pax_k => $pax_v) {
				$pax_type = $pax_k;
				if(isset($PassengerFareBreakdown[$pax_type]) == false) {
					$PassengerFareBreakdown[$pax_type]['PassengerType'] = $pax_type;
					$PassengerFareBreakdown[$pax_type]['Count'] = $pax_v['PassengerCount'];
					$PassengerFareBreakdown[$pax_type]['BaseFare'] = $pax_v['BaseFare'];
				} else {
					$PassengerFareBreakdown[$pax_type]['BaseFare'] += $pax_v['BaseFare'];
				}
			}
		}
		return $PassengerFareBreakdown;
	}
	/**
	 * Merges Flight Segment Details
	 * @param unknown_type $flight_details
	 */
	public function merge_segment_details($flight_details)
	{
		$SegmentDetails = array();
		foreach($flight_details as $k => $v){
			$SegmentDetails = array_merge($SegmentDetails, $v['SegmentDetails']);
		}
		return $SegmentDetails;
	}
	/**
	 * Merges Flight Segment Summery
	 * @param unknown_type $flight_details
	 */
	public function merge_segment_summary($flight_details)
	{
		$SegmentSummary = array();
		foreach($flight_details as $k => $v){
			$SegmentSummary = array_merge($SegmentSummary, $v['SegmentSummary']);
		}
		return $SegmentSummary;
	}

	public function convert_token_to_application_currency($token, $currency_obj, $module)
	{
		$token_details = $token;
		$token = array();
		$application_default_currency = admin_base_currency();
		foreach($token_details as $tk => $tv) {
			$token[$tk] = $tv;
			$temp_fare_details = $tv['FareDetails'];
			//Fare Details
			$FareDetails = array();
			if($module == 'b2c') {
			$PriceDetails = $temp_fare_details[$module.'_PriceDetails'];
			
			$FareDetails['b2c_PriceDetails']['BaseFare'] = 			get_converted_currency_value($currency_obj->force_currency_conversion($PriceDetails['BaseFare']));
			$FareDetails['b2c_PriceDetails']['TotalTax'] = 			get_converted_currency_value($currency_obj->force_currency_conversion($PriceDetails['TotalTax']));
			$FareDetails['b2c_PriceDetails']['TotalFare'] = 		get_converted_currency_value($currency_obj->force_currency_conversion($PriceDetails['TotalFare']));
			$FareDetails['b2c_PriceDetails']['Currency'] = 			$application_default_currency;
			$FareDetails['b2c_PriceDetails']['CurrencySymbol'] =	$currency_obj->get_currency_symbol($currency_obj->to_currency);
			} else if($module == 'b2b') {
				$PriceDetails = $temp_fare_details[$module.'_PriceDetails'];
				
				$FareDetails['b2b_PriceDetails']['BaseFare'] = 			get_converted_currency_value($currency_obj->force_currency_conversion($PriceDetails['BaseFare']));
				$FareDetails['b2b_PriceDetails']['_CustomerBuying'] =	get_converted_currency_value($currency_obj->force_currency_conversion($PriceDetails['_CustomerBuying']));
				$FareDetails['b2b_PriceDetails']['_AgentBuying'] = 		get_converted_currency_value($currency_obj->force_currency_conversion($PriceDetails['_AgentBuying']));
				$FareDetails['b2b_PriceDetails']['_AdminBuying'] = 		get_converted_currency_value($currency_obj->force_currency_conversion($PriceDetails['_AdminBuying']));
				$FareDetails['b2b_PriceDetails']['_Markup'] = 			get_converted_currency_value($currency_obj->force_currency_conversion($PriceDetails['_Markup']));
				$FareDetails['b2b_PriceDetails']['_AgentMarkup'] = 		get_converted_currency_value($currency_obj->force_currency_conversion($PriceDetails['_AgentMarkup']));
				$FareDetails['b2b_PriceDetails']['_AdminMarkup'] = 		get_converted_currency_value($currency_obj->force_currency_conversion($PriceDetails['_AdminMarkup']));
				$FareDetails['b2b_PriceDetails']['_Commission'] = 		get_converted_currency_value($currency_obj->force_currency_conversion($PriceDetails['_Commission']));
				$FareDetails['b2b_PriceDetails']['_tdsCommission'] = 	get_converted_currency_value($currency_obj->force_currency_conversion($PriceDetails['_tdsCommission']));
				$FareDetails['b2b_PriceDetails']['_AgentEarning'] = 	get_converted_currency_value($currency_obj->force_currency_conversion($PriceDetails['_AgentEarning']));
				$FareDetails['b2b_PriceDetails']['_TaxSum'] = 			get_converted_currency_value($currency_obj->force_currency_conversion($PriceDetails['_TaxSum']));
				$FareDetails['b2b_PriceDetails']['_BaseFare'] = 		get_converted_currency_value($currency_obj->force_currency_conversion($PriceDetails['_BaseFare']));
				$FareDetails['b2b_PriceDetails']['_TotalPayable'] = 	get_converted_currency_value($currency_obj->force_currency_conversion($PriceDetails['_TotalPayable']));
				$FareDetails['b2b_PriceDetails']['Currency'] = 			$application_default_currency;
				$FareDetails['b2b_PriceDetails']['CurrencySymbol'] = 	$currency_obj->get_currency_symbol($currency_obj->to_currency);
			}
			
			$FareDetails['api_PriceDetails'] = $this->preferred_currency_fare_object($temp_fare_details['api_PriceDetails'], $currency_obj, $application_default_currency);
			$token[$tk]['FareDetails'] = $FareDetails;
			//Passenger Breakdown
			$token[$tk]['PassengerFareBreakdown'] = $this->preferred_currency_paxwise_breakup_object($tv['PassengerFareBreakdown'], $currency_obj);
		}
		return $token;
	}

	public function reindex_passport_expiry_month($passenger_passport_expiry_month, $search_id)
	{
		$safe_search_data = $this->search_data ( $search_id );
		$is_domestic = $safe_search_data['data']['is_domestic'];
		if($is_domestic == false){
			foreach($passenger_passport_expiry_month as $k => $v){
				$passenger_passport_expiry_month[$k] = ($v+1);
			}
		}
		return $passenger_passport_expiry_month;
	}

	function save_booking($app_booking_id, $book_params, $currency_obj, $module='b2c')
	{

		//Need to return following data as this is needed to save the booking fare in the transaction details
		// debug($book_params); exit;
		// $t_pax_count = count($book_params['passenger_type']);
		// $p_count = $total_pax_count;
		// debug("1700"); exit;
		// debug($p_count); exit;
		// debug($book_params); exit;
		// $type_for_crs = count($book_params['token']['token']);
		// // debug($type_for_crs); exit;
		// $fsid_for_oneway = $book_params['token']['token'][0]['SegmentSummary'][0]['OriginDetails']['fsid'];
		// // debug($fsid_for_oneway); exit;
		// $fsid_for_roundway = $book_params['token']['token'][1]['SegmentSummary'][1]['OriginDetails']['fsid'];
		// // debug($fsid_for_roundway); exit;
		
		// $c_is_domestic = $book_params['token']['is_domestic'];
		// if($c_is_domestic != '' && $type_for_crs == 1)
		// {
		// 	//debug("hii"); exit;
		// 	$c_details = $book_params['token']['token'][0]['SegmentSummary'];
		// 	//$c_origin = current($c_details)['OriginDetails']['AirportCode'];
		// 	//$c_destination = end($c_details)['DestinationDetails']['AirportCode'];
		// 	$c_date = current($c_details)['OriginDetails']['_Date'];
		// 	$c_date1 = date("Y-m-d", strtotime($c_date));
		// 	//$c_acode = current($c_details)['AirlineDetails']['AirlineCode'];
		// 	//$c_aname = current($c_details)['AirlineDetails']['AirlineName'];
		// 	//$c_fnum = current($c_details)['AirlineDetails']['FlightNumber'];
		// 	$c_fsid = $GLOBALS['CI']->flight_model->get_fsid_for_pnr_oneway($c_date1,$fsid_for_oneway);
		// 	$c_pnr = $c_fsid['pnr'];
		// 	$pnr1 = $c_pnr;
		// }
		// if($c_is_domestic != '' && $type_for_crs == 2)
		// {
		// 	//Onward pnr
		// 	$c_details = $book_params['token']['token'][0]['SegmentSummary'];
		// 	$c_date = current($c_details)['OriginDetails']['_Date'];
		// 	$c_date1 = date("Y-m-d", strtotime($c_date));
		// 	$c_fsid = $GLOBALS['CI']->flight_model->get_fsid_for_pnr_twoway_onward($c_date1,$fsid_for_oneway);
		// 	$c_pnr = $c_fsid['pnr'];
		// 	$pnr1 = $c_pnr;
		// 	debug($pnr1);
		// 	//Return pnr
		// 	$c_details1 = $book_params['token']['token'][1]['SegmentSummary'];
		// 	$c_date1 = current($c_details1)['OriginDetails']['_Date'];
		// 	$c_date11 = date("Y-m-d", strtotime($c_date1));
		// 	$c_fsid1 = $GLOBALS['CI']->flight_model->get_fsid_for_pnr_twoway_return($c_date11,$fsid_for_roundway);
		// 	$c_pnr1 = $c_fsid1['pnr'];
		// 	$pnr2 = $c_pnr1;
		// 	debug($pnr2); 
		// }
		// else{
		// 	$c_details = $book_params['token']['token'][0]['SegmentSummary'];
		// 	$c_date = current($c_details)['OriginDetails']['_Date'];
		// 	$c_date1 = date("Y-m-d", strtotime($c_date));
		// 	$c_fsid = $GLOBALS['CI']->flight_model->get_fsid_for_pnr_int($c_date1,$fsid_for_oneway);
		// 	$c_pnr = $c_fsid['pnr'];
		// 	$pnr1 = $c_pnr;
		// 	// $pnr = '';
		// }
		
		$response['fare'] = $response['domain_markup'] = $response['level_one_markup'] = 0;
		$book_total_fare = array();
		$book_domain_markup = array();
		$book_level_one_markup = array();
		$master_transaction_status = 'BOOKING_HOLD';
		$master_search_id = $book_params['search_id'];

		$domain_origin = get_domain_auth_id();
		$app_reference = $app_booking_id;

		$booking_source = $book_params['booking_source'];

		//PASSENGER DATA UPDATE
		$total_pax_count = count($book_params['passenger_type']);
		$pax_count = $total_pax_count;
		// debug("1711"); exit;
		//Extract ExtraService Details
		$extra_service_details = $this->extract_extra_service_details($book_params);
		// debug($extra_service_details); exit();
		//PREFERRED TRANSACTION CURRENCY AND CURRENCY CONVERSION RATE 
		$transaction_currency = get_application_currency_preference();
		$application_currency = admin_base_currency();
		$currency_conversion_rate = $currency_obj->transaction_currency_conversion_rate();
		//********************** only for calculation
		$safe_search_data = $this->search_data($master_search_id);
		$safe_search_data = $safe_search_data['data'];
		$safe_search_data['is_domestic_one_way_flight'] = false;
		$from_to_trip_type = $safe_search_data['trip_type'];
		if(strtolower($from_to_trip_type) == 'multicity') {
			$from_loc = $safe_search_data['from'][0];
			$to_loc = end($safe_search_data['to']);
			$journey_from = $safe_search_data['from_city'][0];
			$journey_to = end($safe_search_data['to_city']);
		} else {
			$from_loc = $safe_search_data['from'];
			$to_loc = $safe_search_data['to'];
			$journey_from = $safe_search_data['from_city'];
			$journey_to = $safe_search_data['to_city'];
		}
		$safe_search_data['is_domestic_one_way_flight'] = $GLOBALS['CI']->flight_model->is_domestic_flight($from_loc, $to_loc);
		if ($safe_search_data['is_domestic_one_way_flight'] == false && strtolower($from_to_trip_type) == 'circle') {
			$multiplier = $pax_count * 2;//Multiply with 2 for international round way
		} else if(strtolower($from_to_trip_type) == 'multicity'){
			$multiplier = $pax_count * count($safe_search_data['from']);
		} else {
			$multiplier = $pax_count;
		}
		$token = $book_params['token']['token'];
		
		//********************* only for calculation
		$master_booking_source = array();
		$currency = $currency_obj->to_currency;
		$deduction_cur_obj	= clone $currency_obj;
		//Storing Flight Details - Every Segment can repeate also
		// debug($book_params); exit;
		// debug($token); exit;
		$segment_summary = array();

		foreach ($token['FlightDetails']['Details'] as $token_index => $token_value) {
			
			$segment_details = $token['flight_details'];
			$segment_summary[$token_index] = $token['flight_details']['summary'][$token_index];
			$Fare = $token['price'];
			$tmp_domain_markup = 0;
			$tmp_level_one_markup = 0;
			$itinerary_price	= $Fare['total_breakup']['api_total_fare'];
			
			//Calculation is different for b2b and b2c
			//Specific Markup Config
			$specific_markup_config = array();
			// debug("1762"); exit;
			$specific_markup_config = $this->get_airline_specific_markup_config($segment_details);
			// debug($specific_markup_config); exit;
			//Get the Airline code for setting airline-wise markup
			// debug("1768"); exit;
			$final_booking_price_details = $this->get_final_booking_price_details($Fare, $multiplier, $specific_markup_config, $currency_obj, $deduction_cur_obj, $module);
			// debug($final_booking_price_details); exit;
			//$commissionable_fare = $final_booking_price_details['commissionable_fare'];
			$commissionable_fare = $Fare['total_breakup']['api_total_fare'];
			$trans_total_fare = $Fare['api_total_display_fare'];
			//$admin_markup = $final_booking_price_details['admin_markup'];
			$admin_markup =  $Fare['total_breakup']['api_total_tax'];
			$agent_markup = $final_booking_price_details['agent_markup'];
			$admin_commission = $final_booking_price_details['admin_commission'];
			$agent_commission = $final_booking_price_details['agent_commission'];
			$admin_tds = $final_booking_price_details['admin_tds'];
			$agent_tds = $final_booking_price_details['agent_tds'];
			
			//**************Ticketing For Each Token START
			//Following Variables are used to save Transaction and Pax Ticket Details
			
			$book_id = '';
			$source = '';
			$ref_id = '';
			$transaction_status = 0;
			$GetBookingResult = array();
			$transaction_description = '';
			$getbooking_StatusCode = '';
			$getbooking_Description = '';
			$getbooking_Category = '';
			$WSTicket = array();
			$WSFareRule = array();
			//Saving Flight Transaction Details
			$tranaction_attributes = array();
			$pnr = '';
			$book_id = '';
			//$source = $this->get_tbo_source_name($token_value['Source']);
			$source = '';
			$ref_id = '';
			$transaction_status = $master_transaction_status;
			//debug($transaction_status); exit;
			$transaction_description = '';
			//Get Booking Details
			$getbooking_status_details = '';
			$getbooking_StatusCode = '';
			$getbooking_Description = '';
			$getbooking_Category = '';
			$tranaction_attributes['Fare'] = $Fare;
			$sequence_number = $token_index;
			//Transaction Log Details
			$ticket_trans_status_group[] = $transaction_status;
			$book_total_fare[]	= $trans_total_fare;
			$book_domain_markup[]	= $admin_markup;
			$book_level_one_markup[] = $agent_markup;
			//Need individual transaction price details
			//SAVE Transaction Details
			// exit;
			// debug(${"pnr".$x});
			// $x = 1;
			// debug($x);
			// debug($pnr1);${"pnr".$x}
			// debug($pnr2);
			$transaction_insert_id = $GLOBALS['CI']->flight_model->save_flight_booking_transaction_details(
			$app_reference, $transaction_status, $transaction_description, $pnr, $book_id, $source, $ref_id,
			json_encode($tranaction_attributes), $sequence_number, $currency, $commissionable_fare, $admin_markup, $agent_markup,
			$admin_commission, $agent_commission,
			$getbooking_StatusCode, $getbooking_Description, $getbooking_Category,
			$admin_tds, $agent_tds
			);
			
			// $x = $x + 1;
			// debug($x); exit;
		//	debug($transaction_insert_id); 
			$transaction_insert_id = $transaction_insert_id['insert_id'];
			
			//Saving Passenger Details
			$i = 0;
			for ($i=0; $i<$total_pax_count; $i++)
			{
				$passenger_type = $book_params['passenger_type'][$i];
				$is_lead = $book_params['lead_passenger'][$i];
				$title = get_enum_list('title', $book_params['name_title'][$i]);
				$first_name = $book_params['first_name'][$i];
				$middle_name = '';//$book_params['middle_name'][$i];
				$last_name = $book_params['last_name'][$i];
				$date_of_birth = $book_params['date_of_birth'][$i];
				$gender = get_enum_list('gender', $book_params['gender'][$i]);

				$passenger_nationality_id = intval($book_params['passenger_nationality'][$i]);
				$passport_issuing_country_id = intval($book_params['passenger_passport_issuing_country'][$i]);
				$passenger_nationality = $GLOBALS['CI']->db_cache_api->get_country_list(array('k' => 'origin', 'v' => 'name'), array('origin' => $passenger_nationality_id));
				$passport_issuing_country = $GLOBALS['CI']->db_cache_api->get_country_list(array('k' => 'origin', 'v' => 'name'), array('origin' => $passport_issuing_country_id));

				$passenger_nationality = isset($passenger_nationality[$passenger_nationality_id]) ? $passenger_nationality[$passenger_nationality_id] : '';
				$passport_issuing_country = isset($passport_issuing_country[$passport_issuing_country_id]) ? $passport_issuing_country[$passport_issuing_country_id] : '';

				$passport_number = $book_params['passenger_passport_number'][$i];
				$passport_expiry_date = $book_params['passenger_passport_expiry_year'][$i].'-'.$book_params['passenger_passport_expiry_month'][$i].'-'.$book_params['passenger_passport_expiry_day'][$i];
				//$status = 'BOOKING_CONFIRMED';//Check it
				$status = $master_transaction_status;
				$passenger_attributes = array();
				
				
				$flight_booking_transaction_details_fk = $transaction_insert_id;//Adding Transaction Details Origin
				//SAVE Pax Details
				$pax_insert_id = $GLOBALS['CI']->flight_model->save_flight_booking_passenger_details(
				$app_reference, $passenger_type, $is_lead, $title, $first_name, $middle_name, $last_name, $date_of_birth,
				$gender, $passenger_nationality, $passport_number, $passport_issuing_country, $passport_expiry_date, $status,
				json_encode($passenger_attributes), $flight_booking_transaction_details_fk);
				
				//Save passenger ticket information
				$passenger_ticket_info = $GLOBALS['CI']->flight_model->save_passenger_ticket_info($pax_insert_id['insert_id']);
			}//Adding Pax Details Ends
				
			//Saving Segment Details
			
			foreach($segment_details as $seg_k => $seg_v) {
				$curr_segment_indicator = 1;

				foreach($seg_v as $ws_key => $ws_val) {

					$FareRestriction = '';
					$FareBasisCode = '';
					$FareRuleDetail = '';
					$airline_pnr = '';
					//$AirlineDetails = $ws_val['AirlineDetails'];
					$OriginDetails = $ws_val['origin'];
					$DestinationDetails = $ws_val['destination'];
					//$segment_indicator = $ws_val['SegmentIndicator'];
					$segment_indicator = ($curr_segment_indicator++);
					
					$airline_code = $ws_val['operator_code'];
					$airline_name = $ws_val['operator_name'];
					$flight_number = $ws_val['flight_number'];
					$fare_class = $ws_val['cabin_class'];
					$from_airport_code = $OriginDetails['loc'];
					$from_airport_name = $OriginDetails['city'];
					$to_airport_code = $DestinationDetails['loc'];
					$to_airport_name = $DestinationDetails['city'];
					$departure_datetime = date('Y-m-d H:i:s', strtotime($OriginDetails['datetime']));
					$arrival_datetime = date('Y-m-d H:i:s', strtotime($DestinationDetails['datetime']));
					$iti_status = '';
					$operating_carrier = $ws_val['display_operator_code'];
					$attributes = array('craft' => @$ws_val['Craft'], 'ws_val' => $ws_val);
					//SAVE ITINERARY
					$GLOBALS['CI']->flight_model->save_flight_booking_itinerary_details(
					$app_reference, $segment_indicator, $airline_code, $airline_name, $flight_number, $fare_class, $from_airport_code, $from_airport_name,
					$to_airport_code, $to_airport_name, $departure_datetime, $arrival_datetime, $iti_status, $operating_carrier, json_encode($attributes),
					$FareRestriction, $FareBasisCode, $FareRuleDetail, $airline_pnr);
				}
			}//End Of Segments Loop
			
		}//End Of Token Loop
		// exit;
		//Save Master Booking Details
		$book_total_fare = array_sum($book_total_fare);
		$book_domain_markup = array_sum($book_domain_markup);
		$book_level_one_markup = array_sum($book_level_one_markup);

		$phone = $book_params['passenger_contact'];
		$alternate_number = '';
		$email = $book_params['billing_email'];
		$start = $token[0];
		$end = end($token);
		
		$journey_start = $segment_summary[0][0]['OriginDetails']['DateTime'];
		$journey_start = date('Y-m-d H:i:s', strtotime($journey_start));
		$journey_end = end(end($segment_summary));
		$journey_end = $journey_end['DestinationDetails']['DateTime'];
		$journey_end = date('Y-m-d H:i:s', strtotime($journey_end));
		$payment_mode = $book_params['payment_method'];
		$created_by_id = intval(@$GLOBALS['CI']->entity_user_id);

		$passenger_country_id = intval($book_params['billing_country']);
		//$passenger_city_id = intval($book_params['billing_city']);
		$passenger_country = $GLOBALS['CI']->db_cache_api->get_country_list(array('k' => 'origin', 'v' => 'name'), array('origin' => $passenger_country_id));
		//$passenger_city = $GLOBALS['CI']->db_cache_api->get_city_list(array('k' => 'origin', 'v' => 'destination'), array('origin' => $passenger_city_id));

		$passenger_country = isset($passenger_country[$passenger_country_id]) ? $passenger_country[$passenger_country_id] : '';
		//$passenger_city = isset($passenger_city[$passenger_city_id]) ? $passenger_city[$passenger_city_id] : '';
		$passenger_city = $book_params['billing_city'];

		$attributes = array('country' => $passenger_country, 'city' => $passenger_city, 'zipcode' => $book_params['billing_zipcode'], 'address' =>  $book_params['billing_address_1']);
		$flight_booking_status = $master_transaction_status;
		//SAVE Booking Details
		$cabin_class='NULL';
		$GLOBALS['CI']->flight_model->save_flight_booking_details(
		$domain_origin, $flight_booking_status, $app_reference,$cabin_class, $booking_source, $phone, $alternate_number, $email,
		$journey_start, $journey_end, $journey_from, $journey_to, $payment_mode, json_encode($attributes), $created_by_id,
		$from_loc, $to_loc, $from_to_trip_type, $transaction_currency, $currency_conversion_rate
		);
		
		//Save Passenger Baggage Details
		if(isset($extra_service_details['ExtraServiceDetails']['Baggage']) == true && valid_array($extra_service_details['ExtraServiceDetails']['Baggage']) == true){
			$this->save_passenger_baggage_info($app_reference, $book_params, $extra_service_details['ExtraServiceDetails']['Baggage']);
		}
		
		
		//Save Passenger Meals Details
		if(isset($extra_service_details['ExtraServiceDetails']['Meals']) == true && valid_array($extra_service_details['ExtraServiceDetails']['Meals']) == true){
			$this->save_passenger_meal_info($app_reference, $book_params, $extra_service_details['ExtraServiceDetails']['Meals']);
		}
		
		//Save Passenger Meals Details
		if(isset($extra_service_details['ExtraServiceDetails']['Seat']) == true && valid_array($extra_service_details['ExtraServiceDetails']['Seat']) == true){
			$this->save_passenger_seat_info($app_reference, $book_params, $extra_service_details['ExtraServiceDetails']['Seat']);
		}
		
		//Meal Preference
		if(isset($extra_service_details['ExtraServiceDetails']['MealPreference']) == true && valid_array($extra_service_details['ExtraServiceDetails']['MealPreference']) == true){
			$this->save_passenger_meal_preference($app_reference, $book_params, $extra_service_details['ExtraServiceDetails']['MealPreference']);
		}
		
		//Seat Preference
		if(isset($extra_service_details['ExtraServiceDetails']['SeatPreference']) == true && valid_array($extra_service_details['ExtraServiceDetails']['SeatPreference']) == true){
			$this->save_passenger_seat_preference($app_reference, $book_params, $extra_service_details['ExtraServiceDetails']['SeatPreference']);
		}
		
		//Add Extra Service Price to published price
		$GLOBALS['CI']->flight_model->add_extra_service_price_to_published_fare($app_reference);
		
		//Adding Extra services Total Price
		$extra_services_total_price = $GLOBALS['CI']->flight_model->get_extra_services_total_price($app_reference);
		$book_total_fare += $extra_services_total_price;
		
		/************** Update Convinence Fees And Other Details Start ******************/
		//Convinence_fees to be stored and discount
		$convinence = 0;
		$discount = 0;
		$convinence_value = 0;
		$convinence_type = 0;
		$convinence_type = 0;
		if ($module == 'b2c') {
			$total_transaction_amount = $book_total_fare+$book_domain_markup;
			$convinence = $currency_obj->convenience_fees($total_transaction_amount, $master_search_id);
			$convinence_row = $currency_obj->get_convenience_fees();
			$convinence_value = $convinence_row['value'];
			$convinence_type = $convinence_row['type'];
			$convinence_per_pax = $convinence_row['per_pax'];
			$discount = $book_params['promo_code_discount_val'];
			$promo_code = $book_params['promo_code'];
		} elseif ($module == 'b2b') {
			$total_transaction_amount = $book_total_fare+$book_domain_markup;
			if($book_params['payment_mode'] == 1){
				$convinence = $currency_obj->convenience_fees($total_transaction_amount, $master_search_id);
				$convinence_row = $currency_obj->get_convenience_fees();
				$convinence_value = $convinence_row['value'];
				$convinence_type = $convinence_row['type'];
				$convinence_per_pax = $convinence_row['per_pax'];
			}else{
				$convinence_per_pax = 0;	
			}
			$discount = 0;
			
		}
		$GLOBALS['CI']->load->model('transaction');
		//SAVE Convinience and Discount Details
		$GLOBALS['CI']->transaction->update_convinence_discount_details('flight_booking_details', $app_reference, $discount, $promo_code, $convinence, $convinence_value, $convinence_type, $convinence_per_pax);
		/************** Update Convinence Fees And Other Details End ******************/

		/**
		 * Data to be returned after transaction is saved completely
		 */
		
		$response['fare'] = $book_total_fare;
		$response['admin_markup'] = $book_domain_markup;
		$response['agent_markup'] = $book_level_one_markup;
		$response['convinence'] = $convinence;
		$response['discount'] = $discount;

		$response['status'] = $flight_booking_status;
		$response['status_description'] = $transaction_description;
		$response['name'] = $first_name;
		$response['phone'] = $phone;
		// debug($response); exit;
		return $response;
	}

	public function extract_extra_service_details($book_params)
	{
		$extra_services = array();
		if(isset($book_params['token']['extra_services']) && isset($book_params['token']['extra_services']['status']) == true && $book_params['token']['extra_services']['status'] == SUCCESS_STATUS
			&& isset($book_params['token']['extra_services']['data']['ExtraServiceDetails']) == true && valid_array($book_params['token']['extra_services']['data']['ExtraServiceDetails']) == true){
				
				$ExtraServiceDetails = $book_params['token']['extra_services']['data']['ExtraServiceDetails'];
				
				//re-index baggage details with BaggageId
				$reindexed_baggage = array();
				if(isset($ExtraServiceDetails['Baggage']) == true && valid_array($ExtraServiceDetails['Baggage']) == true){
					$Baggage = $ExtraServiceDetails['Baggage'];
					foreach($Baggage as $ob_k => $ob_v){
						foreach ($ob_v as $bk => $bv){
							$reindexed_baggage[$bv['BaggageId']] = $bv;
						}
					}
				}
				
				//re-index meal details with MealId
				$reindexed_meal = array();
				if(isset($ExtraServiceDetails['Meals']) == true && valid_array($ExtraServiceDetails['Meals']) == true){
					$Meals = $ExtraServiceDetails['Meals'];
					foreach($Meals as $om_k => $om_v){
						foreach ($om_v as $mk => $mv){
							$reindexed_meal[$mv['MealId']] = $mv;
						}
					}
				}
				//re-index seat details with SeatId
				$reindexed_seat = array();
				if(isset($ExtraServiceDetails['Seat']) == true && valid_array($ExtraServiceDetails['Seat']) == true){
					$Seat = $ExtraServiceDetails['Seat'];
					foreach($Seat as $os_k => $os_v){
						foreach ($os_v as $sk => $sv){
							foreach($sv as $seat_index => $seat_value){
								$reindexed_seat[$seat_value['SeatId']] = $seat_value;
							}
						}
					}
				}
				//Meal Preference - re-index meal details with MealId
				$reindexed_meal_pref = array();
				if(isset($ExtraServiceDetails['MealPreference']) == true && valid_array($ExtraServiceDetails['MealPreference']) == true){
					$Meals = $ExtraServiceDetails['MealPreference'];
					foreach($Meals as $om_k => $om_v){
						foreach ($om_v as $mk => $mv){
							$reindexed_meal_pref[$mv['MealId']] = $mv;
						}
					}
				}
				//Seat Preference - re-index seat details with SeatId
				$reindexed_seat_pref = array();
				if(isset($ExtraServiceDetails['SeatPreference']) == true && valid_array($ExtraServiceDetails['SeatPreference']) == true){
					$Seats = $ExtraServiceDetails['SeatPreference'];
					foreach($Seats as $os_k => $os_v){
						foreach ($os_v as $sk => $sv){
							$reindexed_seat_pref[$sv['SeatId']] = $sv;
						}
					}
				}
				
				//Assigning the values
				if(valid_array($reindexed_baggage) == true){
					$extra_services['ExtraServiceDetails']['Baggage'] = $reindexed_baggage;
				}
				if(valid_array($reindexed_meal) == true){
					$extra_services['ExtraServiceDetails']['Meals'] = $reindexed_meal;
				}
				if(valid_array($reindexed_seat) == true){
					$extra_services['ExtraServiceDetails']['Seat'] = $reindexed_seat;
				}
				if(valid_array($reindexed_meal_pref) == true){
					$extra_services['ExtraServiceDetails']['MealPreference'] = $reindexed_meal_pref;
				}
				if(valid_array($reindexed_seat_pref) == true){
					$extra_services['ExtraServiceDetails']['SeatPreference'] = $reindexed_seat_pref;
				}
		}
		
		return $extra_services;
	}

	private function get_final_booking_price_details($Fare, $multiplier, $specific_markup_config, $currency_obj, $deduction_cur_obj, $module)
	{
		$data = array();
		$core_agent_commision = ($Fare['PublishedFare']-$Fare['OfferedFare']);
		$commissionable_fare = $Fare['PublishedFare'];
		if ($module == 'b2c') {				
			$trans_total_fare = $this->total_price($Fare, false, $currency_obj);
			$markup_total_fare	= $currency_obj->get_currency($trans_total_fare, true, false, true, $multiplier, $specific_markup_config);
			$ded_total_fare		= $deduction_cur_obj->get_currency($trans_total_fare, true, true, false, $multiplier, $specific_markup_config);
			$admin_markup = roundoff_number($markup_total_fare['default_value']-$ded_total_fare['default_value']);
			$admin_commission = $core_agent_commision;
			$agent_markup = 0;
			$agent_commission = 0;
			
		} else {
			//B2B Calculation
			//Markup
			$trans_total_fare = $Fare['PublishedFare'];
			$markup_total_fare	= $currency_obj->get_currency($trans_total_fare, true, true, true, $multiplier, $specific_markup_config);
			$ded_total_fare		= $deduction_cur_obj->get_currency($trans_total_fare, true, false, true, $multiplier, $specific_markup_config);
			$admin_markup = abs($markup_total_fare['default_value']-$ded_total_fare['default_value']);
			$agent_markup = roundoff_number($ded_total_fare['default_value']-$trans_total_fare);
			//Commission
			$this->commission = $currency_obj->get_commission();
			$AgentCommission = $this->calculate_commission($core_agent_commision);
			$admin_commission = roundoff_number($core_agent_commision-$AgentCommission);//calculate here
			$agent_commission = roundoff_number($AgentCommission);
		}
		//TDS Calculation
		$admin_tds = $currency_obj->calculate_tds($admin_commission);
		$agent_tds = $currency_obj->calculate_tds($agent_commission);
		
		$data['commissionable_fare'] = $commissionable_fare;
		$data['trans_total_fare'] = $trans_total_fare;
		$data['admin_markup'] = $admin_markup;
		$data['agent_markup'] = $agent_markup;
		$data['admin_commission'] = $admin_commission;
		$data['agent_commission'] = $agent_commission;
		$data['admin_tds'] = $admin_tds;
		$data['agent_tds'] = $agent_tds;
		return $data;
	}

	private function save_passenger_baggage_info($app_reference, $book_params, $baggage_details)
	{
		$stored_booking_details = $GLOBALS['CI']->flight_model->get_booking_details($app_reference);
		$GLOBALS['CI']->load->library('booking_data_formatter');
		$booking_details = $GLOBALS['CI']->booking_data_formatter->format_flight_booking_data($stored_booking_details, $GLOBALS['CI']->config->item('current_module'));
		$booking_details = $booking_details['data']['booking_details']['0'];
		$booking_transaction_details = $booking_details['booking_transaction_details'];
		
		$baggage_index = 0;
		while(isset($book_params["baggage_$baggage_index"]) == true){
			foreach($booking_transaction_details as $tr_k => $tr_v){
				if(count($booking_transaction_details) == 2){
					if($tr_k == 0){
						$journy_type = 'onward_journey';
					} else {
						$journy_type = 'return_journey';
					}
				} else {
					$journy_type = 'full_journey';
				}
				
				//
				foreach ($book_params["baggage_$baggage_index"] as $bag_k => $bag_v){
					
					if(empty($bag_v) == false&& isset($baggage_details[$bag_v]) == true && $baggage_details[$bag_v]['JourneyType'] == $journy_type){
						$passenger_fk = 		$tr_v['booking_customer_details'][$bag_k]['origin'];
						$from_airport_code =	$baggage_details[$bag_v]['Origin'];
						$to_airport_code = 		$baggage_details[$bag_v]['Destination'];
						$description = 			$baggage_details[$bag_v]['Weight'];
						$price = 				$baggage_details[$bag_v]['Price'];
						$code = 				$baggage_details[$bag_v]['Code'];
						
						//Save passenger baggage information
						$GLOBALS['CI']->flight_model->save_passenger_baggage_info($passenger_fk, $from_airport_code, $to_airport_code, $description, $price, $code);
					}
				}
			}
			$baggage_index++;
		}
	}

	private function save_passenger_meal_info($app_reference, $book_params, $meal_details)
	{	
		$stored_booking_details = $GLOBALS['CI']->flight_model->get_booking_details($app_reference);
		$GLOBALS['CI']->load->library('booking_data_formatter');
		$booking_details = $GLOBALS['CI']->booking_data_formatter->format_flight_booking_data($stored_booking_details, $GLOBALS['CI']->config->item('current_module'));
		$booking_details = $booking_details['data']['booking_details']['0'];
		$booking_transaction_details = $booking_details['booking_transaction_details'];
		
		$meal_index = 0;
		while(isset($book_params["meal_$meal_index"]) == true){
			foreach($booking_transaction_details as $tr_k => $tr_v){
				if(count($booking_transaction_details) == 2){
					if($tr_k == 0){
						$journy_type = 'onward_journey';
					} else {
						$journy_type = 'return_journey';
					}
				} else {
					$journy_type = 'full_journey';
				}
				
				//
				foreach ($book_params["meal_$meal_index"] as $meal_k => $meal_v){
					
					if(empty($meal_v) == false&& isset($meal_details[$meal_v]) == true && $meal_details[$meal_v]['JourneyType'] == $journy_type){
						$passenger_fk = 		$tr_v['booking_customer_details'][$meal_k]['origin'];
						$from_airport_code =	$meal_details[$meal_v]['Origin'];
						$to_airport_code = 		$meal_details[$meal_v]['Destination'];
						$description = 			$meal_details[$meal_v]['Description'];
						$price = 				$meal_details[$meal_v]['Price'];
						$code = 				$meal_details[$meal_v]['Code'];
						//Save passenger meal information
						$GLOBALS['CI']->flight_model->save_passenger_meals_info($passenger_fk, $from_airport_code, $to_airport_code, $description, $price, $code);
					}
				}
			}
			$meal_index++;
		}
	}

	private function save_passenger_seat_info($app_reference, $book_params, $seat_details)
	{
		$stored_booking_details = $GLOBALS['CI']->flight_model->get_booking_details($app_reference);
		$GLOBALS['CI']->load->library('booking_data_formatter');
		$booking_details = $GLOBALS['CI']->booking_data_formatter->format_flight_booking_data($stored_booking_details, $GLOBALS['CI']->config->item('current_module'));
		$booking_details = $booking_details['data']['booking_details']['0'];
		$booking_transaction_details = $booking_details['booking_transaction_details'];
		
		$seat_index = 0;
		while(isset($book_params["seat_$seat_index"]) == true){
			foreach($booking_transaction_details as $tr_k => $tr_v){
				if(count($booking_transaction_details) == 2){
					if($tr_k == 0){
						$journy_type = 'onward_journey';
					} else {
						$journy_type = 'return_journey';
					}
				} else {
					$journy_type = 'full_journey';
				}
				
				//
				foreach ($book_params["seat_$seat_index"] as $seat_k => $seat_v){
					
					if(empty($seat_v) == false&& isset($seat_details[$seat_v]) == true && $seat_details[$seat_v]['JourneyType'] == $journy_type){
						
						$passenger_fk = 		$tr_v['booking_customer_details'][$seat_k]['origin'];
						$from_airport_code =	$seat_details[$seat_v]['Origin'];
						$to_airport_code = 		$seat_details[$seat_v]['Destination'];
						$description = 			'';
						$price = 				$seat_details[$seat_v]['Price'];
						$code = 				$seat_details[$seat_v]['SeatNumber'];
						$airline_code = 		$seat_details[$seat_v]['AirlineCode'];
						$flight_number = 		$seat_details[$seat_v]['FlightNumber'];
						
						//Save passenger seat information
						$GLOBALS['CI']->flight_model->save_passenger_seat_info($passenger_fk, $from_airport_code, $to_airport_code, $description, $price, $code, 'dynamic', $airline_code, $flight_number);
					}
				}
			}
			$seat_index++;
		}
	}

	private function save_passenger_meal_preference($app_reference, $book_params, $meal_details)
	{
		$stored_booking_details = $GLOBALS['CI']->flight_model->get_booking_details($app_reference);
		$GLOBALS['CI']->load->library('booking_data_formatter');
		$booking_details = $GLOBALS['CI']->booking_data_formatter->format_flight_booking_data($stored_booking_details, $GLOBALS['CI']->config->item('current_module'));
		$booking_details = $booking_details['data']['booking_details']['0'];
		$booking_transaction_details = $booking_details['booking_transaction_details'];
		
		$meal_index = 0;
		while(isset($book_params["meal_pref$meal_index"]) == true){
			foreach($booking_transaction_details as $tr_k => $tr_v){
				if(count($booking_transaction_details) == 2){
					if($tr_k == 0){
						$journy_type = 'onward_journey';
					} else {
						$journy_type = 'return_journey';
					}
				} else {
					$journy_type = 'full_journey';
				}
				
				//
				foreach ($book_params["meal_pref$meal_index"] as $meal_k => $meal_v){
					
					if(empty($meal_v) == false&& isset($meal_details[$meal_v]) == true && $meal_details[$meal_v]['JourneyType'] == $journy_type){
						$passenger_fk = 		$tr_v['booking_customer_details'][$meal_k]['origin'];
						$from_airport_code =	$meal_details[$meal_v]['Origin'];
						$to_airport_code = 		$meal_details[$meal_v]['Destination'];
						$description = 			$meal_details[$meal_v]['Description'];
						$price = 				0;
						$code = 				$meal_details[$meal_v]['Code'];
						//Save passenger meal information
						$GLOBALS['CI']->flight_model->save_passenger_meals_info($passenger_fk, $from_airport_code, $to_airport_code, $description, $price, $code, 'static');
					}
				}
			}
			$meal_index++;
		}
	}

	private function save_passenger_seat_preference($app_reference, $book_params, $seat_details)
	{
		$stored_booking_details = $GLOBALS['CI']->flight_model->get_booking_details($app_reference);
		$GLOBALS['CI']->load->library('booking_data_formatter');
		$booking_details = $GLOBALS['CI']->booking_data_formatter->format_flight_booking_data($stored_booking_details, $GLOBALS['CI']->config->item('current_module'));
		$booking_details = $booking_details['data']['booking_details']['0'];
		$booking_transaction_details = $booking_details['booking_transaction_details'];
		
		$seat_index = 0;
		while(isset($book_params["seat_pref$seat_index"]) == true){
			foreach($booking_transaction_details as $tr_k => $tr_v){
				if(count($booking_transaction_details) == 2){
					if($tr_k == 0){
						$journy_type = 'onward_journey';
					} else {
						$journy_type = 'return_journey';
					}
				} else {
					$journy_type = 'full_journey';
				}
				
				//
				foreach ($book_params["seat_pref$seat_index"] as $seat_k => $seat_v){
					
					if(empty($seat_v) == false&& isset($seat_details[$seat_v]) == true && $seat_details[$seat_v]['JourneyType'] == $journy_type){
						$passenger_fk = 		$tr_v['booking_customer_details'][$seat_k]['origin'];
						$from_airport_code =	$seat_details[$seat_v]['Origin'];
						$to_airport_code = 		$seat_details[$seat_v]['Destination'];
						$description = 			$seat_details[$seat_v]['Description'];
						$price = 				0;
						$code = 				$seat_details[$seat_v]['Code'];
						//Save passenger seat information
						$GLOBALS['CI']->flight_model->save_passenger_seat_info($passenger_fk, $from_airport_code, $to_airport_code, $description, $price, $code, 'static');
					}
				}
			}
			$seat_index++;
		}
	}

	public function process_booking($book_id, $booking_params)
	{
	//	debug($booking_params);exit();
		//Adding SequenceNumber
		// debug($booking_params); exit; pnr not coming
		foreach($booking_params['token']['token'] as $k => $v) {
			$booking_params['token']['token'][$k]['SequenceNumber'] = $k;
		}
		$response['status'] = BOOKING_CONFIRMED;
		$wrapper_token = $booking_params['token'];
		$book_response = array();
	//$book_response = $this->book_flight($book_id, $booking_params);
		//debug($book_response); exit;
		$book_response['status'] = BOOKING_CONFIRMED;
		if ($book_response['status'] == FAILURE_STATUS) {
			$response['status'] = FAILURE_STATUS;
			$response['message'] = $book_response['message'];
		} else {
			$ticket_response = $book_response;
			$response['status'] = $ticket_response['status'];
		}
		//Extracting Response
		$response['data']['ticket']['TicketDetails'] = @$ticket_response['data'];
		$response['data']['book_id'] = $book_id;
		$response['data']['booking_params'] = $booking_params;
	//	debug($response);
		return $response;
	}

	function book_flight($book_id, $booking_params)
	{
		// debug($booking_params); exit;
		$response['status'] = FAILURE_STATUS;
		$booking_response = array();
		$token_wrapper = $booking_params['token'];
		$op = $booking_params['op'];
		//check ONE WAY - Domestic / Intl & ROUND WAY - Intl - Run Once
		$unique_search_access_key = array_unique($token_wrapper['search_access_key']);
		// debug($unique_search_access_key); exit;
		if (count($unique_search_access_key) == 1) { // Single session is one request
			if (count($token_wrapper['search_access_key']) == 1) {
				// debug("2380"); exit; Data coming till here
				//Extract Passenger Information
				$passenger = $this->extract_passenger_info($booking_params, $token_wrapper['token'][0]['SequenceNumber']);
				// debug($passenger); exit; Coming
				if(isset($booking_params['ticket_method']) &&  $booking_params['ticket_method'] === 'hold_ticket'){//HOLD TICKET
					$tmp_res = $this->run_hold_booking($op, $book_id, $token_wrapper['token'][0], $passenger, $token_wrapper['search_access_key'][0]);
				} else {//DIRECT TICKETING
					$tmp_res = $this->run_commit_booking($op, $book_id, $token_wrapper['token'][0], $passenger, $token_wrapper['search_access_key'][0]);
					// debug($tmp_res); exit;
				}
				

				// if ($this->valid_flight_booking_status($tmp_res['status']) == true) {
				if (($tmp_res['status']) == true) {
					$booking_response[] = $tmp_res['data'];
					$response['status'] = $tmp_res['status'];
				} else {
					$response['message'] = $tmp_res['message'];
					$response['status'] = FAILURE_STATUS;
				}
			}
		} else { // multiple request is two request
			//Domestic Round - Run Twice
			foreach ($token_wrapper['token'] as $___k => $___v) {
				//Extract Passenger Information
				// debug("2402"); exit;
				$passenger = $this->extract_passenger_info($booking_params, $___v['SequenceNumber']);
				// debug($passenger); exit;
				$tmp_resp = $this->run_commit_booking($op, $book_id, $___v, $passenger, $token_wrapper['search_access_key'][$___k]);
				if ($this->valid_flight_booking_status($tmp_resp['status']) == true) {
					
					$booking_response[$___k] = $tmp_resp['data'];
					
					if($response['status'] != BOOKING_CONFIRMED){
						$response['status'] = $tmp_resp['status'];
					}
				} else {
					$booking_response[$___k]['Status'] = $tmp_resp['status'];
					$booking_response[$___k]['Message'] = $tmp_resp['message'];
					$response['message'] = @$booking_response[$___k]['message'];
					if ($this->valid_flight_booking_status($response['status']) == false) {//Even if one booking is Hold/Success, return the status as Hold/Success
						$response['status'] = FAILURE_STATUS;
					}
					break;
				}
			}
		}
		$response['data'] = $booking_response;
		return $response;
	}

	private function extract_passenger_info($booking_params, $SequenceNumber)
	{	
		// debug($booking_params); exit();
		$extra_service_details = $this->extract_extra_service_details($booking_params);
		// debug($extra_service_details); exit; coming
		$country_list = $GLOBALS['CI']->db_cache_api->get_country_list(array('k' => 'origin', 'v' => 'iso_country_code'));
		//$city_list = $GLOBALS['CI']->db_cache_api->get_city_list();
		$passenger['lead_passenger']		= $booking_params['lead_passenger'];
		foreach ($booking_params['name_title'] as $__k => $__v) {
			$passenger['name_title'][$__k]	= @get_enum_list('title', $__v);
		}
		$passenger['first_name']			= $booking_params['first_name'];
		//$passenger['middle_name']			= $booking_params['middle_name'];
		$passenger['last_name']				= $booking_params['last_name'];
		$passenger['date_of_birth']			= $booking_params['date_of_birth'];
		foreach ($booking_params['passenger_type'] as $__k => $__v) {
			$passenger['passenger_type'][$__k]		= $this->pax_type($__v);
		}
		foreach ($booking_params['gender'] as $__k => $__v) {
			$gender		= (isset($__v) ? get_enum_list('gender', $__v) : '');
			$passenger['gender'][$__k] = $this->gender_type($gender);
		}
		foreach ($booking_params['passenger_nationality'] as $__k => $__v) {
			$passenger['passenger_nationality'][$__k]	= (isset($country_list[$__v]) ? $country_list[$__v] : '');
		}

		foreach ($booking_params['passenger_passport_issuing_country'] as $__k => $__v) {
			$passenger['passenger_passport_issuing_country'][$__k]	= (isset($country_list[$__v]) ? $country_list[$__v] : '');
		}
		//$passenger['passport_number'] = $booking_params['passenger_passport_number'];
		$passenger['passport_number'] = preg_replace('/\s+/', '', $booking_params['passenger_passport_number']);
		
		
		foreach ($passenger['passport_number'] as $__k => $__v) {
			if (empty($__v) == false) {
				//FIXME
				$pass_date = strtotime($booking_params['passenger_passport_expiry_year'][$__k].'-'.$booking_params['passenger_passport_expiry_month'][$__k].'-'.$booking_params['passenger_passport_expiry_day'][$__k]);
				$passenger['passport_expiry_date'][$__k]	= date('Y-m-d', $pass_date);
			} else {
				$passenger['passport_expiry_date'][$__k]	= '';
			}
		}
	
			if($SequenceNumber == 0){
				$journy_type = array('full_journey', 'onward_journey');
			} else {
				$journy_type = array('return_journey');
			}
				
		//Baggage
		if(isset($extra_service_details['ExtraServiceDetails']['Baggage']) == true && valid_array($extra_service_details['ExtraServiceDetails']['Baggage']) == true){
			$Baggage = $extra_service_details['ExtraServiceDetails']['Baggage'];
			
			foreach ($booking_params['first_name'] as $__k => $__v) {
				$baggage_index = 0;
				$passenger_baggage = array();
				
				while(isset($booking_params["baggage_$baggage_index"]) == true){
					if(isset($booking_params["baggage_$baggage_index"][$__k]) == true && empty($booking_params["baggage_$baggage_index"][$__k]) == false
					&& in_array($Baggage[$booking_params["baggage_$baggage_index"][$__k]]['JourneyType'], $journy_type) == true){
						
						$passenger_baggage[] = $booking_params["baggage_$baggage_index"][$__k];
					}
					$baggage_index++;
				}//while ends
				
				if(valid_array($passenger_baggage) == true){
					$passenger['baggage'][$__k]	= $passenger_baggage;
				}
			}
		}//Baggage ends
		
		
		//Meals
		if(isset($extra_service_details['ExtraServiceDetails']['Meals']) == true && valid_array($extra_service_details['ExtraServiceDetails']['Meals']) == true){
			$Meals = $extra_service_details['ExtraServiceDetails']['Meals'];
			
			foreach ($booking_params['first_name'] as $__k => $__v) {
				$meal_index = 0;
				$passenger_meal = array();
				while(isset($booking_params["meal_$meal_index"]) == true){
					if(isset($booking_params["meal_$meal_index"][$__k]) == true && empty($booking_params["meal_$meal_index"][$__k]) == false
					&& in_array($Meals[$booking_params["meal_$meal_index"][$__k]]['JourneyType'], $journy_type) == true){
						$passenger_meal[] = $booking_params["meal_$meal_index"][$__k];
					}
					$meal_index++;
				}
				if(valid_array($passenger_meal) == true){
					$passenger['meal'][$__k]	= $passenger_meal;
				}
			}
		}//Meal ends
		
		//Meals Preference
		if(isset($extra_service_details['ExtraServiceDetails']['MealPreference']) == true && valid_array($extra_service_details['ExtraServiceDetails']['MealPreference']) == true){
			$Meals = $extra_service_details['ExtraServiceDetails']['MealPreference'];
			
			foreach ($booking_params['first_name'] as $__k => $__v) {
				$meal_index = 0;
				$passenger_meal_pref = array();
				while(isset($booking_params["meal_pref$meal_index"]) == true){
					if(isset($booking_params["meal_pref$meal_index"][$__k]) == true && empty($booking_params["meal_pref$meal_index"][$__k]) == false
					&& in_array($Meals[$booking_params["meal_pref$meal_index"][$__k]]['JourneyType'], $journy_type) == true){
						$passenger_meal_pref[] = $booking_params["meal_pref$meal_index"][$__k];
					}
					$meal_index++;
				}
				if(valid_array($passenger_meal_pref) == true){
					$passenger['meal'][$__k]	= $passenger_meal_pref;
				}
			}
		}//Meal Preference ends
		
		//Seat
		if(isset($extra_service_details['ExtraServiceDetails']['Seat']) == true && valid_array($extra_service_details['ExtraServiceDetails']['Seat']) == true){
			$Seat = $extra_service_details['ExtraServiceDetails']['Seat'];
			
			foreach ($booking_params['first_name'] as $__k => $__v) {
				$seat_index = 0;
				$passenger_seat = array();
				while(isset($booking_params["seat_$seat_index"]) == true){
					if(isset($booking_params["seat_$seat_index"][$__k]) == true && empty($booking_params["seat_$seat_index"][$__k]) == false
					&& in_array($Seat[$booking_params["seat_$seat_index"][$__k]]['JourneyType'], $journy_type) == true){
						$passenger_seat[] = $booking_params["seat_$seat_index"][$__k];
					}
					$seat_index++;
				}
				if(valid_array($passenger_seat) == true){
					$passenger['seat'][$__k]	= $passenger_seat;
				}
			}
		}//Seat ends
		
		//Seat Preference
		if(isset($extra_service_details['ExtraServiceDetails']['SeatPreference']) == true && valid_array($extra_service_details['ExtraServiceDetails']['SeatPreference']) == true){
			$SeatPreference = $extra_service_details['ExtraServiceDetails']['SeatPreference'];
			
			foreach ($booking_params['first_name'] as $__k => $__v) {
				$seat_index = 0;
				$passenger_seat_pref = array();
				while(isset($booking_params["seat_pref$seat_index"]) == true){
					if(isset($booking_params["seat_pref$seat_index"][$__k]) == true && empty($booking_params["seat_pref$seat_index"][$__k]) == false
					&& in_array($SeatPreference[$booking_params["seat_pref$seat_index"][$__k]]['JourneyType'], $journy_type) == true){
						$passenger_seat_pref[] = $booking_params["seat_pref$seat_index"][$__k];
					}
					$seat_index++;
				}
				if(valid_array($passenger_seat_pref) == true){
					$passenger['seat'][$__k]	= $passenger_seat_pref;
				}
			}
		}//Seat Preference ends
		
		$passenger['billing_country'] = $country_list[$booking_params['billing_country']];
		$passenger['billing_country_name'] = 'India';//FIXME: Make it Dynamic
		//$passenger['billing_city'] = $city_list[$booking_params['billing_city']];
		$passenger['billing_city'] = $booking_params['billing_city'];
		$passenger['billing_zipcode'] = $booking_params['billing_zipcode'];
		$passenger['billing_email'] = $booking_params['billing_email'];
		$passenger['billing_address_1'] = $booking_params['billing_address_1'];
		$passenger['passenger_contact'] = $booking_params['passenger_contact'];
		$passenger['st'] = 'BOOKING_PENDING';
		// debug($passenger); exit;
		return $passenger;
	}

	function run_hold_booking($op, $book_id, $token, $passenger, $search_access_key)
	{
		$response['data'] = array();
		$response['status'] = FAILURE_STATUS;
		$response['message'] = '';
		$SequenceNumber = $token['SequenceNumber'];
		$booking_params['Passenger']			= $this->WSPassenger($passenger);

		//Prova Auth key
		$booking_params['ProvabAuthKey']		= $token['ProvabAuthKey'];
		$booking_params['SequenceNumber']		= $SequenceNumber;
		$api_request = $this->hold_booking_request($booking_params, $book_id);
		//get data
		if ($api_request['status']) {
			$header_info = $this->get_header();
			
			$this->CI->custom_db->generate_static_response(json_encode($api_request['data']['request']));
			
			$api_response = $this->CI->api_interface->get_json_response($api_request['data']['service_url'], $api_request['data']['request'], $header_info);
			$this->CI->custom_db->generate_static_response(json_encode($api_response));
			
			/*$static_id = 	1198;
			$api_response = $this->CI->flight_model->get_static_response($static_id);//378*/
			
			if ($this->valid_commit_booking_response($api_response) == true) {
				$api_response['CommitBooking'] = $api_response['HoldTicket'];
				unset($api_response['HoldTicket']);
				$api_response['CommitBooking']['BookingDetails']['Price'] = $this->convert_bookingdata_to_application_currency($api_response['CommitBooking']['BookingDetails']['Price']);
				
				$response['data'] = $api_response;
				$response['status'] = $api_response['Status'];
			} else {
				$response['message'] = @$api_response['Message'];
				$response['status'] = FAILURE_STATUS;
			}
		}
		/** PROVAB LOGGER **/
		$GLOBALS['CI']->private_management_model->provab_xml_logger('Hold Booking', $book_id, 'flight', json_encode($api_request['data']), json_encode($api_response));
		return $response;
	}

	private function WSPassenger($passenger)
	{
		$tmp_passenger = array();
		$total_pax_count = count($passenger['passenger_type']);
		$i = 0;
		for ($i=0; $i<$total_pax_count; $i++)
		{
			$tmp_passenger[$i]['IsLeadPax'] = $passenger['lead_passenger'][$i];
			$tmp_passenger[$i]['Title'] = $passenger['name_title'][$i];
			$tmp_passenger[$i]['FirstName'] = ((strlen($passenger['first_name'][$i])<2) ? str_repeat($passenger['first_name'][$i], 2) : $passenger['first_name'][$i]);
			$tmp_passenger[$i]['LastName'] = ((strlen($passenger['last_name'][$i])<2)   ? str_repeat($passenger['last_name'][$i], 2)  : $passenger['last_name'][$i]);
			$tmp_passenger[$i]['PaxType'] = $passenger['passenger_type'][$i];
			$tmp_passenger[$i]['Gender'] = $passenger['gender'][$i];
			$tmp_passenger[$i]['DateOfBirth'] = date('Y-m-d', strtotime($passenger['date_of_birth'][$i]));

			if (empty($passenger['passport_number'][$i]) == false and empty($passenger['passport_expiry_date'][$i]) == false) {
				$tmp_passenger[$i]['PassportNumber'] = $passenger['passport_number'][$i];
				$tmp_passenger[$i]['PassportExpiry'] = $passenger['passport_expiry_date'][$i];
			} else {
				$tmp_passenger[$i]['PassportNumber'] = '';
				$tmp_passenger[$i]['PassportExpiry'] = null;
			}

			$tmp_passenger[$i]['CountryCode'] = $passenger['passenger_nationality'][$i];
			$tmp_passenger[$i]['CountryName'] = $passenger['billing_country_name'];
			$tmp_passenger[$i]['ContactNo'] = $passenger['passenger_contact'];
			$tmp_passenger[$i]['City'] = $passenger['billing_city'];
			$tmp_passenger[$i]['PinCode'] = $passenger['billing_zipcode'];
				
			$tmp_passenger[$i]['AddressLine1'] = $passenger['billing_address_1'];
			$tmp_passenger[$i]['AddressLine2'] = $passenger['billing_address_1'];
			$tmp_passenger[$i]['Email'] = $passenger['billing_email'];
			
			
			//Baggage
			if(isset($passenger['baggage'][$i]) == true && valid_array($passenger['baggage'][$i]) == true){
				$tmp_passenger[$i]['BaggageId'] = $passenger['baggage'][$i];
			}
			
			//Meals
			if(isset($passenger['meal'][$i]) == true && valid_array($passenger['meal'][$i]) == true){
				$tmp_passenger[$i]['MealId'] = $passenger['meal'][$i];
			}
			
			//Seat
			if(isset($passenger['seat'][$i]) == true && valid_array($passenger['seat'][$i]) == true){
				$tmp_passenger[$i]['SeatId'] = $passenger['seat'][$i];
			}
		}
		
		return $tmp_passenger;
	}

	private function hold_booking_request($booking_params, $app_reference)
	{
		$response['status']	= SUCCESS_STATUS;
		$response['data']	= array();
		$request_params = array();
		//$this->credentials('HoldTicket'); Runs on API so commented for custom CRS
		$request_params['AppReference'] = trim($app_reference);
		$request_params['SequenceNumber'] = $booking_params['SequenceNumber'];
		$request_params['ResultToken'] = $booking_params['ProvabAuthKey'];
		$request_params['Passengers'] = $booking_params['Passenger'];
		$response['data']['request']		= json_encode($request_params);
		$response['data']['service_url']		= $this->service_url;
		return $response;
	}

	function run_commit_booking($op, $book_id, $token, $passenger, $search_access_key)
	{
		$response['data'] = array();
		$response['status'] = FAILURE_STATUS;
		$response['message'] = '';
		$SequenceNumber = $token['SequenceNumber'];
		$booking_params['Passenger']			= $this->WSPassenger($passenger);
		// debug($booking_params['Passenger']); exit(); coming
		//Prova Auth key
		$booking_params['ProvabAuthKey']		= $token['ProvabAuthKey'];
		$booking_params['SequenceNumber']		= $SequenceNumber;
		$api_request = $this->commit_booking_request($booking_params, $book_id);
		// debug($api_request); exit;
		$api_request['status'] = true;
		
		//get data
		// if ($api_request['status']) {
		// 	//$header_info = $this->get_header();
			
		// 	$this->CI->custom_db->generate_static_response(json_encode($api_request['data']['request']));
			
		// 	$api_response = $this->CI->api_interface->get_json_response($api_request['data']['service_url'], $api_request['data']['request'], $header_info);
		// 	$this->CI->custom_db->generate_static_response(json_encode($api_response));
			
		// 	//$static_id = 	378;
		// 	//$api_response = $this->CI->flight_model->get_static_response($static_id);//378
			
		// 	if ($this->valid_commit_booking_response($api_response) == true) {
		// 		$api_response['CommitBooking']['BookingDetails']['Price'] = $this->convert_bookingdata_to_application_currency($api_response['CommitBooking']['BookingDetails']['Price']);
		// 		$response['data'] = $api_response;
		// 		$response['status'] = $api_response['Status'];
		// 	} else {
		// 		$response['message'] = @$api_response['Message'];
		// 		$response['status'] = FAILURE_STATUS;
		// 	}
		// }
		// /** PROVAB LOGGER **/
		// $GLOBALS['CI']->private_management_model->provab_xml_logger('Commit Booking', $book_id, 'flight', json_encode($api_request['data']), json_encode($api_response));
		$response['message'] = '';
		$response['status'] = SUCCESS_STATUS;
		// $response['st'] = 'BOOKING_HOLD';
		return $response;
	}

	private function commit_booking_request($booking_params, $app_reference)
	{
		$response['status']	= SUCCESS_STATUS;
		$response['data']	= array();
		$request_params = array();
		//$this->credentials('CommitBooking');
		$request_params['AppReference'] = trim($app_reference);
		$request_params['SequenceNumber'] = $booking_params['SequenceNumber'];
		$request_params['ResultToken'] = $booking_params['ProvabAuthKey'];
		$request_params['Passengers'] = $booking_params['Passenger'];
		$response['data']['request']		= json_encode($request_params);
		
		//$response['data']['service_url']		= $this->service_url;
		
		return $response;
	}

	private function gender_type($pax_type)
	{
		switch (strtoupper($pax_type))
		{
			case 'MALE' : $pax_type = "1";
			break;
			case 'FEMALE' : $pax_type = "2";
		}
		return $pax_type;
	}

	private function pax_type($pax_type)
	{
		switch (strtoupper($pax_type))
		{
			case 'ADULT' : $pax_type = "1";
			break;
			case 'CHILD' : $pax_type = "2";
			break;
			case 'INFANT' : $pax_type = "3";
			break;
		}
		return $pax_type;
	}
	public function update_flightcrs_seat_details($book_id, $book_params)
    {
        $saved_booking_data = $GLOBALS['CI']->flight_model->get_booking_details($book_id);

		$passenger_details = $saved_booking_data['data']['booking_customer_details'];
		if($saved_booking_data['status'] == false) {
			$response['status'] = BOOKING_ERROR;
			$response['msg'] = 'No Data Found';
			return $response;
		}


    	$c_date = $book_params['book_attributes']['token'][0]['FlightDetails']['Details'][0][0]['Origin']['date'];
		$c_date1 = date("Y-m-d", strtotime($c_date));
		$fsid_for_oneway = $book_params['book_attributes']['token'][0]['FlightDetails']['Details'][0][0]['Origin']['fsid'];
		$fdid_for_oneway = $book_params['book_attributes']['token'][0]['FlightDetails']['Details'][0][0]['Origin']['fdid'];

		$c_fsid = $GLOBALS['CI']->flight_model->get_fsid_for_pnr_int($c_date1,$fsid_for_oneway);
		
		$pax_arr = array_count_values($book_params['book_attributes']['passenger_type']);
		
		$c_pnr = $c_fsid['pnr'];
		$fudid = array($fdid_for_oneway);
		
		$total_pax_count = $pax_arr['Adult']+$pax_arr['Child'];
	    $pax_count = $total_pax_count;
	
		$seat_status_query = $GLOBALS['CI']->flight_model->update_crs_seat($fdid_for_oneway, $pax_count, $fsid_for_oneway);
		$fsid = array($fsid_for_oneway);
			
		if (isset($GLOBALS['CI']->entity_user_id) == true and intval($GLOBALS['CI']->entity_user_id) > 0) {
			$agent_id = $GLOBALS['CI']->entity_user_id;
		} else {
			$cus_email = $saved_booking_data['data']['booking_details'][0]['email'];
			$user = $GLOBALS['CI']->flight_model->get_user($cus_email);
			$agent_id = $user[0]['user_id'];
		}
		$crs_array = array();
		$status = $this->status_code_value(SUCCESS_STATUS);

		for($i=0;$i<count($fsid);$i++){
			$crs_array = array(
								'fsid' => $fsid[$i],
								'fudid' => $fudid[$i],
								'app_reference' => $saved_booking_data['data']['booking_details'][0]['app_reference'],
								'booking_source' => $saved_booking_data['data']['booking_details'][0]['booking_source'],
								'status' => $status,
								'agent_id' => $agent_id,
								'created_date_time' => date('Y-m-d H:i:s')
								);
			$GLOBALS['CI']->flight_model->save_flight_crs_booking_details($crs_array);
		}
		
			
			
    }
	public function update_booking_details($book_id, $book_params, $ticket_details, $module='b2c')
	{
		$response = array();
		$book_total_fare = array();
		$book_domain_markup = array();
		$book_level_one_markup = array();
		
		$app_reference = $book_id;
		// debug($book_params); exit;
		
		$master_search_id = $book_params['search_id'];
	
		$master_transaction_status = $this->status_code_value(SUCCESS_STATUS);
	
		$saved_booking_data = $GLOBALS['CI']->flight_model->get_booking_details($book_id);

		$passenger_details = $saved_booking_data['data']['booking_customer_details'];
		if($saved_booking_data['status'] == false) {
			$response['status'] = BOOKING_ERROR;
			$response['msg'] = 'No Data Found';
			return $response;
		}

		//Extracting the Saved data
		$s_master_data = $saved_booking_data['data']['booking_details'][0];
		$s_booking_itinerary_details = $saved_booking_data['data']['booking_itinerary_details'];
		$s_booking_transaction_details = $saved_booking_data['data']['booking_transaction_details'];

		$s_booking_customer_details = $saved_booking_data['data']['booking_customer_details'];
		$first_name = $s_booking_customer_details[0]['first_name'];
		$phone = $s_master_data['phone'];
		$current_master_booking_status = $s_master_data['status'];
		//Extracting the Origins

		$transaction_origins = group_array_column($s_booking_transaction_details, 'origin');
		// debug($transaction_origins); exit;
		$passenger_origins = group_array_column($s_booking_customer_details, 'origin');
		$itinerary_origins = group_array_column($s_booking_itinerary_details, 'origin');

		#debug($saved_booking_data);
		#debug($itinerary_origins);
		//Indexing the data with origin
		$indexed_transaction_details = array();
		foreach($s_booking_transaction_details as $s_tk => $s_tv){
			$indexed_transaction_details[$s_tv['origin']] = $s_tv;
		}
		/*$itinerary_details = $indexed_transaction_details[$s_tv['origin']];
		$itinary_update_condition = array('origin'=>$itinerary_origins[0]);*/

		#debug($itinary_update_condition);

		//1.Update : flight_booking_details
		$flight_master_booking_status = $master_transaction_status;
		// changed status to confirmed
		// $flight_master_booking_status = 1;
		$GLOBALS['CI']->custom_db->update_record('flight_booking_details', array('status' => $master_transaction_status), array('app_reference' => $app_reference));

		$total_pax_count = count($book_params['passenger_type']);
		$pax_count = $total_pax_count;
		// debug($pax_count); exit;
		/////////////////////////
		//update pnr start Jagannath B

		$type_for_crs = count($book_params['token']['token']);
		// debug($type_for_crs); exit;
		$fsid_for_oneway = $book_params['token']['token'][0]['SegmentSummary'][0]['OriginDetails']['fsid'];
		// debug($fsid_for_oneway); exit;
		$fsid_for_roundway = $book_params['token']['token'][1]['SegmentSummary'][1]['OriginDetails']['fsid'];
		// debug($fsid_for_roundway); exit;
		
		$c_is_domestic = $book_params['token']['is_domestic'];

		$status = $this->status_code_value(SUCCESS_STATUS);

		if($c_is_domestic != '' && $type_for_crs == 1)
		{
			//debug("hii"); exit;
			$c_details = $book_params['token']['token'][0]['SegmentSummary'];
			//$c_origin = current($c_details)['OriginDetails']['AirportCode'];
			//$c_destination = end($c_details)['DestinationDetails']['AirportCode'];
			$c_date = current($c_details)['OriginDetails']['_Date'];
			$c_date1 = date("Y-m-d", strtotime($c_date));
			//$c_acode = current($c_details)['AirlineDetails']['AirlineCode'];
			//$c_aname = current($c_details)['AirlineDetails']['AirlineName'];
			//$c_fnum = current($c_details)['AirlineDetails']['FlightNumber'];
			$c_fsid = $GLOBALS['CI']->flight_model->get_fsid_for_pnr_oneway($c_date1,$fsid_for_oneway);
			
			$c_pnr = $c_fsid['pnr'];
			$fudid = array($c_fsid['fudid']);

			$pnr1 = strtoupper($c_pnr);
			$pnr = array($pnr1);
			$pnr_latest = strtoupper($pnr1);
			$update_transaction_condition = array();
			$update_transaction_data = array();
			$update_transaction_condition['origin'] = $transaction_origins[0];
			$update_transaction_data['pnr'] = strtoupper($pnr1);
			$update_transaction_data['status'] = $status;

			$GLOBALS['CI']->custom_db->update_record('flight_booking_transaction_details', $update_transaction_data, $update_transaction_condition);
			//debug($update_transaction_condition); exit;
			$update_itenerary_data = array('airline_pnr' => $pnr_latest);
			//$itinerary_origins
			for($i=0;$i<count($itinerary_origins);$i++){
				$GLOBALS['CI']->custom_db->update_record('flight_booking_itinerary_details',$update_itenerary_data,array('origin' => $itinerary_origins[$i]));
			}
		}

		if($c_is_domestic != '' && $type_for_crs == 2)
		{
			//Onward pnr
			$c_details = $book_params['token']['token'][0]['SegmentSummary'];
			$c_date = current($c_details)['OriginDetails']['_Date'];
			$c_date1 = date("Y-m-d", strtotime($c_date));
			$c_fsid = $GLOBALS['CI']->flight_model->get_fsid_for_pnr_twoway_onward($c_date1,$fsid_for_oneway);
			$c_pnr = $c_fsid['pnr'];
			$fudid1 = $c_fsid['fudid'];
			$pnr1 = strtoupper($c_pnr);
			$pnr_latest = strtoupper($pnr2);
			// debug($pnr1);
				$update_transaction_condition = array();
				$update_transaction_data = array();
				$update_transaction_condition['origin'] = $transaction_origins[0];
				$update_transaction_data['pnr'] = strtoupper($pnr1);
				$update_transaction_data['status'] = $status;
									
				$GLOBALS['CI']->custom_db->update_record('flight_booking_transaction_details', $update_transaction_data, $update_transaction_condition);

				$GLOBALS['CI']->custom_db->update_record('flight_booking_transaction_details', $update_transaction_data, $update_transaction_condition);
				$update_itenerary_data = array('airline_pnr' => $pnr1);

			//Return pnr
			$c_details1 = $book_params['token']['token'][1]['SegmentSummary'];
			$c_date1 = current($c_details1)['OriginDetails']['_Date'];
			$c_date11 = date("Y-m-d", strtotime($c_date1));
			$c_fsid1 = $GLOBALS['CI']->flight_model->get_fsid_for_pnr_twoway_return($c_date11,$fsid_for_roundway);
			$c_pnr1 = $c_fsid1['pnr'];
			$fudid2 = $c_fsid1['fudid'];
			$pnr2 = strtoupper($c_pnr1);
			$array_pnr = array($pnr1,$pnr2);
			$fudid = array($fudid1,$fudid2);
			$pnr = array($pnr1,$pnr2);
			$pnr_latest = strtoupper($pnr2);
			// debug($pnr2); 
				$update_transaction_condition = array();
				$update_transaction_data = array();
				$update_transaction_condition['origin'] = $transaction_origins[1];
				$update_transaction_data['pnr'] = strtoupper($pnr2);
				$update_transaction_data['status'] = $status;
									
				$GLOBALS['CI']->custom_db->update_record('flight_booking_transaction_details', $update_transaction_data, $update_transaction_condition);
				/*$update_itenerary_data = array('airline_pnr' => $pnr2);

				$update_itenerary_data = array('airline_pnr' => $pnr_latest)*/;

			//$itinerary_origins
			for($i=0;$i<count($itinerary_origins);$i++){
				$GLOBALS['CI']->custom_db->update_record('flight_booking_itinerary_details',array('airline_pnr' => $array_pnr[$i]),array('origin' => $itinerary_origins[$i]));
			}

				/*$GLOBALS['CI']->custom_db->update_record('flight_booking_itinerary_details',$update_itenerary_data,$itinary_update_condition);*/	

		}
		else{
			
			$c_details = $book_params['token']['token'][0]['SegmentSummary'];
			$c_date = current($c_details)['OriginDetails']['_Date'];
			$c_date1 = date("Y-m-d", strtotime($c_date));
			$c_fsid = $GLOBALS['CI']->flight_model->get_fsid_for_pnr_int($c_date1,$fsid_for_oneway);
			$c_pnr = $c_fsid['pnr'];
			$fudid = array($c_fsid['fudid']);
			$pnr1 = strtoupper($c_pnr);
			$pnr = array($pnr1);
			$pnr_latest = strtoupper($pnr1);
				$update_transaction_condition = array();
				$update_transaction_data = array();
				$update_transaction_condition['origin'] = $transaction_origins[0];
				$update_transaction_data['pnr'] = strtoupper($pnr1);
				$update_transaction_data['status'] = $status;
									
				$GLOBALS['CI']->custom_db->update_record('flight_booking_transaction_details', $update_transaction_data, $update_transaction_condition);

				$update_itenerary_data = array('airline_pnr' => $pnr1);

				for($i=0;$i<count($itinerary_origins);$i++){
					$GLOBALS['CI']->custom_db->update_record('flight_booking_itinerary_details',$update_itenerary_data,array('origin' => $itinerary_origins[$i]));
				}

				//$GLOBALS['CI']->custom_db->update_record('flight_booking_itinerary_details',$update_itenerary_data,$itinary_update_condition);
			// $pnr = '';
		}
		
		///////////////////////////
		////////////////////////////////////////////////////////////
		//Seat deduct Jagannath B
		// debug($book_params); exit;
		$is_domestic_crs = $book_params['token']['is_domestic'];
		$crs_count_ways = count($book_params['token']['token']);

		if($is_domestic_crs == '')
		{
			//$airline_code=$saved_booking_data['data']['booking_itinerary_details'][0]['airline_code'];
			//$airline_name=$saved_booking_data['data']['booking_itinerary_details'][0]['airline_name'];
			//$flight_number=$saved_booking_data['data']['booking_itinerary_details'][0]['flight_number'];
			//$from_airport_code=$saved_booking_data['data']['booking_itinerary_details'][0]['from_airport_code'];
			//$to_airport_code=$saved_booking_data['data']['booking_itinerary_details'][0]['to_airport_code'];
			// debug($saved_booking_data); exit;
			$departure_datetime=$saved_booking_data['data']['booking_details'][0]['journey_start'];
			// debug($departure_datetime); exit;
	    	//$dat = Date('y-m-d',$departure_datetime);
	    	$dt = new DateTime($departure_datetime);
			$dat = $dt->format('Y-m-d');

			$departure_datetime1=$saved_booking_data['data']['booking_details'][0]['journey_end'];
			// debug($departure_datetime); exit;
	    	//$dat = Date('y-m-d',$departure_datetime);
	    	$dt1 = new DateTime($departure_datetime1);
			$dat1 = $dt1->format('Y-m-d');

			// debug($book_params); exit;
	    	$fsid = $book_params['token']['token'][0]['SegmentSummary'][0]['OriginDetails']['fsid'];
	    	
	    	// debug($fsid); exit;

	    	$seat_status_query = $GLOBALS['CI']->flight_model->update_crs_seat1($dat, $pax_count, $fsid, $dat1);
	    	$fsid = array($fsid);

    	}
    	if($is_domestic_crs == 1 && $crs_count_ways == 2){
   
    		$onward_datetime = $book_params['token']['token'][0]['SegmentSummary'][0]['OriginDetails']['DateTime'];
    		$dt = new DateTime($onward_datetime);
			$dat = $dt->format('Y-m-d');
			$fsid = $book_params['token']['token'][0]['SegmentSummary'][0]['OriginDetails']['fsid'];
			$seat_status_query = $GLOBALS['CI']->flight_model->update_crs_seat_do($dat, $pax_count, $fsid);

			$retu_datetime = $book_params['token']['token'][1]['SegmentSummary'][1]['OriginDetails']['DateTime'];
    		$dt1 = new DateTime($retu_datetime);
			$dat1 = $dt1->format('Y-m-d');
			$fsid1 = $book_params['token']['token'][1]['SegmentSummary'][1]['OriginDetails']['fsid'];
			
			$seat_status_query = $GLOBALS['CI']->flight_model->update_crs_seat_dr($dat1, $pax_count, $fsid1);
			$fsid = array($fsid,$fsid1);
    	}
    	if($is_domestic_crs == 1 && $crs_count_ways == 1){
    		$onward_datetime = $book_params['token']['token'][0]['SegmentSummary'][0]['OriginDetails']['DateTime'];
    		$dt = new DateTime($onward_datetime);
			$dat = $dt->format('Y-m-d');
			$fsid = $book_params['token']['token'][0]['SegmentSummary'][0]['OriginDetails']['fsid'];
			
			$seat_status_query = $GLOBALS['CI']->flight_model->update_crs_seat_done($dat, $pax_count, $fsid);
			$fsid = array($fsid);
    	}

    	if (isset($GLOBALS['CI']->entity_user_id) == true and intval($GLOBALS['CI']->entity_user_id) > 0) {
			$agent_id = $GLOBALS['CI']->entity_user_id;
		} else {
			$cus_email = $saved_booking_data['data']['booking_details'][0]['email'];
			$user = $GLOBALS['CI']->flight_model->get_user($cus_email);
			$agent_id = $user[0]['user_id'];
		}
		
		$itineary_details = $saved_booking_data['data']['booking_itinerary_details'];
		$transcation_details = $saved_booking_data['data']['booking_transaction_details'];
		
		$crs_array = array();
		for($i=0;$i<count($fsid);$i++){
			$crs_array = array(
								'fsid' => $fsid[$i],
								'fudid' => $fudid[$i],
								'app_reference' => $saved_booking_data['data']['booking_details'][0]['app_reference'],
								'booking_source' => $saved_booking_data['data']['booking_details'][0]['booking_source'],
								'status' => $status,
								'agent_id' => $agent_id,
								'created_date_time' => date('Y-m-d H:i:s')
								);
			$GLOBALS['CI']->flight_model->save_flight_crs_booking_details($crs_array);
		}
		

		for($k=0;$k<count($itineary_details);$k++){

			$price_details = json_decode($transcation_details[$k]['attributes'],true);
			$per_passenger_fare = $price_details['Fare']['BaseFare']/count($passenger_details);
			$per_passenger_tax = $price_details['Fare']['Tax']/count($passenger_details);
			$passenger_price = array('price_breakup'=>array('pax_per_price'=>$per_passenger_fare,'pax_per_tax'=>$per_passenger_tax,'base_price' => $price_details['Fare']['BaseFare'],'tax' => $price_details['Fare']['Tax'], 'total_price' => $price_details['Fare']['PublishedFare']));

			$passenger_price = json_encode($passenger_price);
			$update_passenger_data = array();

			$trans_id = $transcation_details[$k]['origin'];

			foreach($passenger_details as $pax_k => $pax_v){

				if($trans_id == $pax_v['flight_booking_transaction_details_fk']){

					$itinary_update_condition = array('passenger_fk' => $pax_v['origin']);
					$update_passenger_data['TicketId'] = $pnr[$k];
					$update_passenger_data['TicketNumber'] = $pnr[$k];
					
					$GLOBALS['CI']->custom_db->update_record('flight_passenger_ticket_info',$update_passenger_data,$itinary_update_condition);

					$GLOBALS['CI']->custom_db->update_record('flight_booking_passenger_details',array('status'=>$status, 'attributes' => $passenger_price),array('origin' => $pax_v['origin']));
				}

			}
		}

		
		
		
		
		
		




    	//seat update ends
    	////////////////////////////////////////////////////////////////////
		//********************** only for calculation
		$safe_search_data = $this->search_data($master_search_id);
		$safe_search_data = $safe_search_data['data'];
		$from_loc = $safe_search_data['from'];
		$to_loc = $safe_search_data['to'];
		$safe_search_data['is_domestic_one_way_flight'] = false;
		$from_to_trip_type = $safe_search_data['trip_type'];
		
		$safe_search_data['is_domestic_one_way_flight'] = $GLOBALS['CI']->flight_model->is_domestic_flight($from_loc, $to_loc);
		if ($safe_search_data['is_domestic_one_way_flight'] == false && strtolower($from_to_trip_type) == 'circle') {
			$multiplier = $pax_count * 2;//Multiply with 2 for international round way
		} else if(strtolower($from_to_trip_type) == 'multicity'){
			$multiplier = $pax_count * count($safe_search_data['from']);
		} else {
			$multiplier = $pax_count;
		}
		//********************* only for calculation
		$currency_obj		= $book_params['currency_obj'];
		$currency = $currency_obj->to_currency;
		$deduction_cur_obj	= clone $currency_obj;
		//PREFERRED TRANSACTION CURRENCY AND CURRENCY CONVERSION RATE 
		$transaction_currency = get_application_currency_preference();
		$application_currency = admin_base_currency();
		$currency_conversion_rate = $currency_obj->transaction_currency_conversion_rate();
		
		if(valid_array($ticket_details) == true) {
			//Ticket Loop Starts
			foreach ($ticket_details as $ticket_index => $ticket_value) {
				$transaction_details_origin = intval($transaction_origins[$ticket_index]);
				
				if (($ticket_value['Status']) == true) {//IF Ticket is HOLD/CONFIRMED
					$status = $this->status_code_value(SUCCESS_STATUS);
					//$status = $this->status_code_value($ticket_value['Status']);
					$ticket_value = $ticket_value['CommitBooking']['BookingDetails'];
					
					$api_booking_id = $ticket_value['BookingId'];
					$pnr = $ticket_value['PNR'];
					$Fare = $ticket_value['Price']['FareDetails'];
					$PassengerFareBreakdown = $ticket_value['Price']['PassengerFareBreakdown'];
					$segment_details = $ticket_value['JourneyList']['FlightDetails']['Details'];
					$passenger_details = $ticket_value['PassengerDetails'];
					
					$tmp_domain_markup = 0;
					$tmp_level_one_markup = 0;
					$itinerary_price	= $Fare['BaseFare'];
					//Calculation is different for b2b and b2c
					//Specific Markup Config
					$specific_markup_config = array();
					$specific_markup_config = $this->get_airline_specific_markup_config($segment_details);//Get the Airline code for setting airline-wise markup
					
					$final_booking_price_details = $this->get_final_booking_price_details($Fare, $multiplier, $specific_markup_config, $currency_obj, $deduction_cur_obj, $module);
					$commissionable_fare = $final_booking_price_details['commissionable_fare'];
					$trans_total_fare = $final_booking_price_details['trans_total_fare'];
					$admin_markup = $final_booking_price_details['admin_markup'];
					$agent_markup = $final_booking_price_details['agent_markup'];
					$admin_commission = $final_booking_price_details['admin_commission'];
					$agent_commission = $final_booking_price_details['agent_commission'];
					$admin_tds = $final_booking_price_details['admin_tds'];
					$agent_tds = $final_booking_price_details['agent_tds'];
					
					
					//2.Update : flight_booking_transaction_details
					// debug("2926"); exit;
					$update_transaction_condition = array();
					$update_transaction_data = array();
					$update_transaction_condition['origin'] = $transaction_details_origin;
					$update_transaction_data['pnr'] = $pnr;
					$update_transaction_data['book_id'] = $api_booking_id;
					$update_transaction_data['status'] = $status;
					$update_transaction_data['total_fare'] = $commissionable_fare;
					$update_transaction_data['admin_commission'] = $admin_commission;
					$update_transaction_data['agent_commission'] = $agent_commission;
					$update_transaction_data['admin_tds'] = $admin_tds;
					$update_transaction_data['agent_tds'] = $agent_tds;
					$update_transaction_data['admin_markup'] = $admin_markup;
					$update_transaction_data['agent_markup'] = $agent_markup;
					//For Transaction Log
					$book_total_fare[]	= $trans_total_fare;
					$book_domain_markup[]	= $admin_markup;
					$book_level_one_markup[] = $agent_markup;
					
					$GLOBALS['CI']->custom_db->update_record('flight_booking_transaction_details', $update_transaction_data, $update_transaction_condition);
	
					//3.Update: flight_booking_passenger_details
					$update_passenger_condition = array();
					$update_passenger_data = array();
					$update_passenger_condition['flight_booking_transaction_details_fk'] = $transaction_details_origin;
					$update_passenger_data['status'] = $master_transaction_status;
					$GLOBALS['CI']->custom_db->update_record('flight_booking_passenger_details', $update_passenger_data, $update_passenger_condition);
	
					//4.Update Ticket details to flight_passenger_ticket_info
					$single_pax_fare_breakup = $this->get_single_pax_fare_breakup($PassengerFareBreakdown);

					foreach($passenger_details as $pax_k => $pax_v){
						$passenger_fk = intval(array_shift($passenger_origins));
						/*$TicketId = $pax_v['PassengerId'];
						$TicketNumber = $pax_v['TicketNumber'];*/
						$TicketId = $pnr_latest;
						$TicketNumber = $pnr_latest;
						$IssueDate = '';
						$Fare = json_encode($single_pax_fare_breakup[$pax_v['PassengerType']]);
						$SegmentAdditionalInfo = '';
						$ValidatingAirline = '';
						$CorporateCode = '';
						$TourCode = '';
						$Endorsement = '';
						$Remarks = '';
						$ServiceFeeDisplayType = '';
						//SAVE PAX Ticket Details
						$GLOBALS['CI']->flight_model->update_passenger_ticket_info($passenger_fk, $TicketId, $TicketNumber, $IssueDate, $Fare,
						$SegmentAdditionalInfo,	$ValidatingAirline, $CorporateCode, $TourCode, $Endorsement, $Remarks, $ServiceFeeDisplayType);
					}
					//5. Update :flight_booking_itinerary_details
					foreach($segment_details as $seg_k => $seg_v) {
						foreach($seg_v as $ws_key => $ws_val) {
							$update_segment_condition = array();
							$update_segement_data = array();
							$update_segment_condition['origin'] = intval(array_shift($itinerary_origins));
							$update_segement_data['airline_pnr'] = $pnr_latest;
							$attributes = array();
							$attributes['departure_terminal'] = $ws_val['Origin']['Terminal'];
							$attributes['arrival_terminal'] = $ws_val['Destination']['Terminal'];
							$attributes['CabinClass'] = $ws_val['CabinClass'];
							$attributes['Attr'] = $ws_val['Attr'];
							
							$update_segement_data['attributes'] = json_encode($attributes);
							$update_segement_data['status'] = '';
							
							$update_segement_data['FareRestriction'] = '';
							$update_segement_data['FareBasisCode'] = '';
							$update_segement_data['FareRuleDetail'] = '';
							
							$GLOBALS['CI']->custom_db->update_record('flight_booking_itinerary_details', $update_segement_data, $update_segment_condition);
						}
					}
				} else {//IF Ticket is Failed
					$GLOBALS['CI']->flight_model->update_flight_booking_transaction_failure_status($app_reference, $transaction_details_origin);
					//For Transaction Log
					$book_total_fare[]	= $indexed_transaction_details[$transaction_details_origin]['total_fare'];
					$book_domain_markup[]	= $indexed_transaction_details[$transaction_details_origin]['admin_markup'];
					$book_level_one_markup[] = $indexed_transaction_details[$transaction_details_origin]['agent_markup'];
				}
			}//Ticket Loop Ends
		} else {
			foreach ($indexed_transaction_details as $itd_k => $itd_v){
				$transaction_details_origin = $itd_v['origin'];
				$GLOBALS['CI']->flight_model->update_flight_booking_transaction_failure_status_custom($app_reference, $transaction_details_origin);
				
				$book_total_fare[]	= $itd_v['total_fare'];
				$book_domain_markup[]	= $itd_v['admin_markup'];
				$book_level_one_markup[] = $itd_v['agent_markup'];
			}
		}
		
		
		/**
		 * Data to be returned after transaction is saved completely
		 */
		$transaction_description = '';
		$book_total_fare = array_sum($book_total_fare);
		$book_domain_markup = array_sum($book_domain_markup);
		$book_level_one_markup = array_sum($book_level_one_markup);
		$discount = 0;
		
		
		//Adding Extra services Total Price
		// $extra_services_total_price = $GLOBALS['CI']->flight_model->get_extra_services_total_price($app_reference);
		// $book_total_fare += $extra_services_total_price;
		
		if($module == 'b2c') {
			$total_transaction_amount = $book_total_fare+$book_domain_markup;
			$convinence = $currency_obj->convenience_fees($total_transaction_amount, $master_search_id);
		} else {
			$convinence = 0;
		}
		$response['fare'] = $book_total_fare;
		$response['admin_markup'] = $book_domain_markup;
		$response['agent_markup'] = $book_level_one_markup;
		$response['convinence'] = $convinence;
		$response['discount'] = $discount;

		$response['status'] = $flight_master_booking_status;
		$response['status_description'] = $transaction_description;
		$response['name'] = $first_name;
		$response['phone'] = $phone;
		$response['transaction_currency'] = $transaction_currency;
		$response['currency_conversion_rate'] = $currency_conversion_rate;
		// debug($response);exit();
		return $response;
	}

	private function status_code_value($status_code)
	{
		switch ($status_code){
			case BOOKING_CONFIRMED:
			case SUCCESS_STATUS:
				 $status_value = 'BOOKING_CONFIRMED';
				 break;
			case BOOKING_HOLD:
			 	$status_value = 'BOOKING_HOLD';
			 	break;
			 default:
			 	$status_value = 'BOOKING_FAILED';
		}
		return $status_value;
	}

	private function get_single_pax_fare_breakup($passenger_fare_breakdown)
	{
		$single_pax_fare_breakup = array();
		foreach ($passenger_fare_breakdown as $k => $v){
			$PassengerCount = $v['PassengerCount'];
			$single_pax_fare_breakup[$k]['BaseFare'] = 	($v['BaseFare']/$PassengerCount);
			$single_pax_fare_breakup[$k]['Tax'] = 			($v['Tax']/$PassengerCount);
			$single_pax_fare_breakup[$k]['TotalPrice'] =	($v['TotalPrice']/$PassengerCount);
		}
		return $single_pax_fare_breakup;
	}

	function get_form_content($form_1, $form_2)
	{
		// error_reporting();
		// debug($form_1); exit;
		$booking_form = '';
		$lcc = (($form_1['is_lcc[]'] == true || $form_2['is_lcc[]'] == true) ? true : false);
		//booking_type - decide it based on f1 is_lcc and f2 is_lcc
		$booking_type = $this->get_booking_type($lcc);
		$booking_form .= $this->booking_form(true, $form_1['token[]'], $form_1['token_key[]'], $form_1['search_access_key[]']);
		$booking_form .= $this->booking_form(true, $form_2['token[]'], $form_2['token_key[]'], $form_2['search_access_key[]']);
		return $booking_form;
	}

	// function get_form_content2($form_1)
	// {
	// 	$booking_form = '';
	// 	$lcc = (($form_1['is_lcc[]'] == true || $form_2['is_lcc[]'] == true) ? true : false);
	// 	//booking_type - decide it based on f1 is_lcc and f2 is_lcc
	// 	$booking_type = $this->get_booking_type($lcc);
	// 	$booking_form .= $this->booking_form(true, $form_1['token[]'], $form_1['token_key[]'], $form_1['search_access_key[]']);
	// 	//$booking_form .= $this->booking_form(true, $form_2['token[]'], $form_2['token_key[]'], $form_2['search_access_key[]']);
	// 	return $booking_form;
	// }

	function get_booking_type($is_lcc)
	{
		if ($is_lcc) {
			return LCC_BOOKING;
		} else {
			return NON_LCC_BOOKING;
		}
	}

	function calendar_safe_search_data(){

		
		$response['data']['day_fare_list'] = array();
		$response['data']['status'] = 1;

		return $response;
	}


 ////////////////////FOR CHNAGING NST STRUCTURE(NEPTUNE)///////////////////
 // public function format_neptune_structure($flight_list, $ip_search_params)
 //    { 
 //    		error_reporting(0);

 //    		debug($flight_list);exit();
 //            $cnt = 0;
 //            $pflight_list = $flight_list['data']['Flights'][0]; 
 //            $privatepflight_list = (isset($flight_list['data']['Search']['FlightDataList']['privatejourney_list'][0])) ? $flight_list['data']['Search']['FlightDataList']['privatejourney_list'][0] : array();
 //              $pasoflight_list = array();
 //            	foreach ($pflight_list as $key => $flight_details) {
                
 //                	$flight_pcode = '';
 //                	$flight_pnumber = '';
 //                	$summary = array();
 //                	$fare_basis = '';

 //                	$details = $flight_details['SegmentDetails'][0];
 //                	// debug($details);exit();
 //                    // $temp = $details[0]['DestinationDetails']['DateTime'];
 //                    // $details[0]['DestinationDetails']['DateTime'] = $details[0]['OriginDetails']['DateTime'];
 //                    // $details[0]['OriginDetails']['DateTime'] = $temp; 

 //                    $stops_count = count($details);
 //                    // debug($stops_count);exit();
 //                    $segments        = $details;
 //                    $first_flight    = reset($segments);
 //                    $last_flight     = end($segments);
 //                    $strSumaryKey    = 0;
 //                    //$details[0] = $details;
 //                    $fare_basis = 100;
 //                    // debug($details);exit();
 //                    $summary[$strSumaryKey]['Origin']['loc']            = $details[0]['OriginDetails']['AirportCode'];
 //                    $summary[$strSumaryKey]['Origin']['city']           = $details[0]['OriginDetails']['CityName'];
 //                    $summary[$strSumaryKey]['Origin']['datetime']       = $details[0]['OriginDetails']['DateTime'];
 //                    $summary[$strSumaryKey]['Origin']['date']           = date('d M Y',strtotime($details[0]['OriginDetails']['DateTime']));
	// 				$summary[$strSumaryKey]['Origin']['time']           = date('H:i',strtotime($details[0]['OriginDetails']['DateTime']));
 //                    $summary[$strSumaryKey]['Origin']['fdtv']           = $details[0]['OriginDetails']['FDTV'];


 //                    $summary[$strSumaryKey]['Destination']['loc']       = $details[$stops_count-1]['DestinationDetails']['AirportCode'];
 //                    $summary[$strSumaryKey]['Destination']['city']      = $details[$stops_count-1]['DestinationDetails']['CityName'];
 //                    $summary[$strSumaryKey]['Destination']['datetime']  = $details[$stops_count-1]['DestinationDetails']['DateTime'];
 //                    $summary[$strSumaryKey]['Destination']['date']      = date('d M Y',strtotime($details[$stops_count-1]['DestinationDetails']['DateTime']));
 //                    $summary[$strSumaryKey]['Destination']['time']      = date('H:i',strtotime($details[$stops_count-1]['Destination']['DateTime']));
 //                    $summary[$strSumaryKey]['Destination']['fdtv']      = $details[$stops_count-1]['DestinationDetails']['FATV'];
 //                    $summary[$strSumaryKey]['operator_code']            = $details[0]['OperatorCode'];
 //                    $flight_pcode = $summary[$strSumaryKey]['display_operator_code']    = $details[0]['OperatorCode'];
 //                    $summary[$strSumaryKey]['operator_name']            = $details[0]['OperatorName'];
 //                    $summary[$strSumaryKey]['class']['name'] = $summary[$strSumaryKey]['cabin_class']               = $details[0]['CabinClass'];
 //                    $flight_pnumber = $summary[$strSumaryKey]['flight_number']          = $details[0]['FlightNumber'];
 //                    $summary[$strSumaryKey]['no_of_stops']              = COUNT($segments) - 1;
 //                    $summary[$strSumaryKey]['duration_seconds']         = $this->provab_duration_calculation_second($details[0]['OriginDetails']['DateTime'], $details[$stops_count-1]['DestinationDetails']['DateTime']);
	// 				$summary[$strSumaryKey]['duration']                 = $this->provab_duration_calculation($details[0]['OriginDetails']['DateTime'], $details[$stops_count-1]['DestinationDetails']['DateTime']);
	// 				$summary[$strSumaryKey]['Meal']                     = "";
 //                    $summary[$strSumaryKey]['Meal_description']         = '';
 //                    $summary[$strSumaryKey]['SeatsRemaining']           = $details[0]['Attr']['AvailableSeats'];
 //                    $summary[$strSumaryKey]['Weight_Allowance']         = $details[0]['Attr']['Baggage'];
 //                    // debug($summary);exit();

 //                    $strDetails[$strSumaryKey]                          = $this->flight_pdetails($segments);


 //                   	$pflight_list_arr[$key][0]['Flight_details']['Details']       = $summary;
 //                    $pflight_list_arr[$key][0]['Flight_details']['Details']       = $strDetails;

 //                    $pflight_list_arr[$key][0]['price']                           = $this->get_pflight_price($flight_details, $flight_pcode, $flight_pnumber, $privatepflight_list, $fare_basis);
 //                    $pflight_list_arr[$key][0]['fare'][$key]                      = $this->get_pflight_price($flight_details, $flight_pcode, $flight_pnumber, $privatepflight_list, $fare_basis);
 //                   	$flightDetailsCount = count($flight_details['FlightDetails']['Details']);
 //                    if($flightDetailsCount > 1){
                        
 //                    for ($i=1; $i < $flightDetailsCount; $i++) { 
                            
 //                    $details = $flight_details['FlightDetails']['Details'][$i];
 //                         $stops_count = count($details);
	// 				$segments        = $details;
 //                    $first_flight    = reset($segments);
 //                    $last_flight     = end($segments);
 //                    $strSumaryKey    = $i;
 //                    $fare_basis = $flight_details['Price']['TotalDisplayFare'];
 //                    $summary[$strSumaryKey]['Origin']['loc']            = $details[0]['OriginDetails']['AirportCode'];
 //                    $summary[$strSumaryKey]['Origin']['city']           = $details[0]['OriginDetails']['CityName'];
 //                    $summary[$strSumaryKey]['Origin']['datetime']       = $details[0]['OriginDetails']['DateTime'];
 //                    $summary[$strSumaryKey]['Origin']['date']           = date('d M Y',strtotime($details[0]['OriginDetails']['DateTime']));
 //                    $summary[$strSumaryKey]['Origin']['time']           = date('H:i',strtotime($details[0]['OriginDetails']['DateTime']));
 //                    $summary[$strSumaryKey]['Origin']['fdtv']           = $details[0]['OriginDetails']['FDTV'];
 //                    $summary[$strSumaryKey]['Destination']['loc']       = $details[$stops_count-1]['DestinationDetails']['AirportCode'];
 //                    $summary[$strSumaryKey]['Destination']['city']      = $details[$stops_count-1]['DestinationDetails']['CityName'];
 //                    $summary[$strSumaryKey]['Destination']['datetime']  = $details[$stops_count-1]['OriginDetails']['DateTime'];
 //                    $summary[$strSumaryKey]['Destination']['date']      = date('d M Y',strtotime($details[$stops_count-1]['Destination']['DateTime']));
 //                    $summary[$strSumaryKey]['Destination']['time']      = date('H:i',strtotime($details[$stops_count-1]['Destination']['DateTime']));
 //                    $summary[$strSumaryKey]['Destination']['fdtv']      = $details[$stops_count-1]['Destination']['FATV'];
 //                    $summary[$strSumaryKey]['operator_code']            = $details[0]['OperatorCode'];
 //                    $flight_pcode = $summary[$strSumaryKey]['display_operator_code']    = $details[0]['OperatorCode'];
 //                    $summary[$strSumaryKey]['operator_name']            = $details[0]['OperatorName'];
 //                    $summary[$strSumaryKey]['class']['name'] = $summary[$strSumaryKey]['cabin_class']               = $details[0]['CabinClass'];
 //                    $flight_pnumber = $summary[$strSumaryKey]['flight_number']          = $details[0]['FlightNumber'];
 //                    $summary[$strSumaryKey]['no_of_stops']              = COUNT($segments) - 1;
 //                    $summary[$strSumaryKey]['duration_seconds']         = $this->provab_duration_calculation_second($details[0]['OriginDetails']['DateTime'], $details[$stops_count-1]['DestinationDetails']['DateTime']);
 //                    $summary[$strSumaryKey]['duration']                 = $this->provab_duration_calculation($details[0]['OriginDetails']['DateTime'], $details[$stops_count-1]['DestinationDetails']['DateTime']);
 //                    $summary[$strSumaryKey]['Meal']                     = "";
 //                    $summary[$strSumaryKey]['Meal_description']         = '';
 //                    $summary[$strSumaryKey]['SeatsRemaining']           = $details[0]['Attr']['AvailableSeats'];
 //                    $summary[$strSumaryKey]['Weight_Allowance']         = $details[0]['Attr']['Baggage'];
 //                    $strDetails[$strSumaryKey]                          = $this->flight_pdetails($segments);

 //                        // $pflight_list_arr[$key][0]['flight_details']['summary']       = $summary;
 //                        // $pflight_list_arr[$key][0]['Flight_details']['Details']       = $strDetails;

 //                        $pflight_list_arr[$key][0]['Flight_details']['Details']       = $summary;
 //                        $pflight_list_arr[$key][0]['Flight_details']['Details']       = $strDetails;
                        
 //                    }
 //                }
 //            }
 //            $psflight_list = $pflight_list_arr;
	// 		debug($psflight_list);exit();
	// 		return $psflight_list;
 //    }

	  ////////////////////FOR CHNAGING NST STRUCTURE(NEPTUNE)///////////////////
     public function format_neptune_structure($flight_list, $ip_search_params)
    { 
            error_reporting(0);
            $cnt = 0;
       		// debug($flight_list);exit();
            $pflight_list = $flight_list['data']['Search']['FlightDataList']['JourneyList'][0]; //[0]
            // debug($pflight_list);exit();

            $privatepflight_list = (isset($flight_list['data']['Search']['FlightDataList']['privatejourney_list'][0])) ? $flight_list['data']['Search']['FlightDataList']['privatejourney_list'][0] : array();

              
            
            $pasoflight_list = array();
              foreach ($pflight_list as $key => $flight_details) {
                // debug($flight_details);exit();
                $flight_pcode = '';
                $flight_pnumber = '';
                $summary = array();
                $fare_basis = '';
                	// debug($flight_details['flight_details']['details'][0]);exit();
                    $flight_details['FlightDetails']['Details'][0] = $flight_details['flight_details']['details'][0];
                    $details = $flight_details['FlightDetails']['Details'][0];
                    // debug($details);exit();
                    $temp = $details[0]['Destination']['DateTime'];
                    $details[0]['Destination']['DateTime'] = $details[0]['Origin']['DateTime'];
                    $details[0]['Origin']['DateTime'] = $temp;     
                    $details[0]['OperatorName'] = get_airline_name_custom($details[0]['OperatorName']);

                    $stops_count = count($details);
                    $segments        = $details;
                    $first_flight    = reset($segments);
                    $last_flight     = end($segments);
                    $strSumaryKey    = 0;
                    // debug($flight_details['price']['api_total_display_fare']);exit();
                    // $fare_basis = $flight_details['price']['api_total_display_fare'];
                    // debug($fare_basis);exit();
                    $summary[$strSumaryKey]['origin']['loc']            = $details[0]['Origin']['AirportCode'];
                    $summary[$strSumaryKey]['origin']['city']           = $details[0]['Origin']['CityName'];
                    $summary[$strSumaryKey]['origin']['datetime']       = $details[0]['Origin']['DateTime'];
                    $summary[$strSumaryKey]['origin']['date']           = date('d M Y',strtotime($details[0]['Origin']['DateTime']));
                    // debug($summary[$strSumaryKey]['origin']['date']);exit();
                    //debug(substr($first_flight['DepartureTime'], 0, -7));exit;
                    $summary[$strSumaryKey]['origin']['time']           = date('H:i',strtotime($details[0]['Origin']['DateTime']));
                    $summary[$strSumaryKey]['origin']['fdtv']           = $details[0]['Origin']['FDTV'];
                    //debug($ArrivalTime_explode);die;
                    $summary[$strSumaryKey]['destination']['loc']       = $details[$stops_count-1]['Destination']['AirportCode'];
                    $summary[$strSumaryKey]['destination']['city']      = $details[$stops_count-1]['Destination']['CityName'];
                    $summary[$strSumaryKey]['destination']['datetime']  = $details[$stops_count-1]['Destination']['DateTime'];
                    $summary[$strSumaryKey]['destination']['date']      = date('d M Y',strtotime($details[$stops_count-1]['Destination']['DateTime']));
                    //debug(substr($last_flight['ArrivalTime'], 0, -7));die;
                    $summary[$strSumaryKey]['destination']['time']      = date('H:i',strtotime($details[$stops_count-1]['Destination']['DateTime']));
                    $summary[$strSumaryKey]['destination']['fdtv']      = $details[$stops_count-1]['Destination']['FATV'];

                    // $details[0]['OperatorCode'] = get_airline_name_custom($details[0]['OperatorCode']);
                    
                    $summary[$strSumaryKey]['operator_code']            = $details[0]['OperatorCode'];
                    $flight_pcode = $summary[$strSumaryKey]['display_operator_code']    = $details[0]['OperatorCode'];
                    $summary[$strSumaryKey]['operator_name']            = $details[0]['OperatorName'];
                    $summary[$strSumaryKey]['class']['name'] = $summary[$strSumaryKey]['cabin_class']               = $details[0]['CabinClass'];
                    $flight_pnumber = $summary[$strSumaryKey]['flight_number']          = $details[0]['FlightNumber'];
                    $summary[$strSumaryKey]['no_of_stops']              = COUNT($segments) - 1;
                    // debug($summary);die('4277');
                    $summary[$strSumaryKey]['duration_seconds']         = $this->provab_duration_calculation_second($details[0]['Origin']['DateTime'], $details[$stops_count-1]['Destination']['DateTime']);
                    $summary[$strSumaryKey]['duration']                 = $this->provab_duration_calculation($details[0]['Origin']['DateTime'], $details[$stops_count-1]['Destination']['DateTime']);
                    $summary[$strSumaryKey]['Meal']                     = "";
                    $summary[$strSumaryKey]['Meal_description']         = '';
                    $summary[$strSumaryKey]['SeatsRemaining']           = $details[0]['Attr']['AvailableSeats'];
                    $summary[$strSumaryKey]['Weight_Allowance']         = $details[0]['Attr']['Baggage'];
                    // debug($summary);die('4277');
                    $strDetails[$strSumaryKey]                          = $this->flight_pdetails($segments);
                    
                        $attr = array
                        (
                            'IsRefundable' =>true,
                            'AirlineRemark' => 'No Data'
                        );
                        // debug($attr);exit();
                    $pflight_list_arr[$key][0]['FlightDetails']['Details'][0]       = $details;


                    ///////////////////////// need  to change //////////////
                    // debug($flight_details['fare'][0]);exit();
                   $price = array
                        (
                            'Currency' => 'AED',
                            'TotalDisplayFare' => $flight_details['fare'][0]['api_total_display_fare'],
                            'PriceBreakup' => array
                                (
                                    'BasicFare' => $flight_details['fare'][0]['total_breakup']['api_total_fare'],
                                    'Tax' => $flight_details['fare'][0]['total_breakup']['api_total_tax'],
                                    'AgentCommission' => 0,
                                    'AgentTdsOnCommision' => 0
                                ),

                            'PassengerBreakup' => $flight_details['fare'][0]['pax_breakup']

                        );
                        // debug($price);exit();
                    ///////////////////////// end //////////////////////////


                   
                    $pflight_list_arr[$key][0]['Price']       = $price;
                    $pflight_list_arr[$key][0]['Attr']        = $attr;
                    $pflight_list_arr[$key][0]['ResultToken']  = '3a4236d449e57cdebbc183f6e98e193b*_*1*_*J0Oq7t2kOQqTjraX';
                     $pflight_list_arr[$key][0]['flight_details']['summary']       = $summary;
                    
                    $pflight_list_arr[$key][0]['flight_details']['details']       = $strDetails;
                    // debug($pflight_list_arr[$key][0]['FlightDetails']['Details']);exit();
                    
                        // debug($fare);exit();

                     $pflight_list_arr[$key][0]['price'] = $flight_details['fare'][0];
                     $pflight_list_arr[$key][0]['fare'] = $flight_details['fare'];
                    //  debug($pflight_list_arr[$key][0]['fare']);exit;
                    // debug($pflight_list_arr);exit();
                    // $pflight_list_arr[$key][0]['price']                           = $this->get_pflight_price($flight_details, $flight_pcode, $flight_pnumber, $privatepflight_list, $fare_basis);
                    // $pflight_list_arr[$key][0]['fare'][$key]                      = $this->get_pflight_price($flight_details, $flight_pcode, $flight_pnumber, $privatepflight_list, $fare_basis);
                    
                    $flightDetailsCount = count($flight_details['FlightDetails']['Details']);
                    if($flightDetailsCount > 1){
                        
                        for ($i=1; $i < $flightDetailsCount; $i++) { 
                            
                     $details = $flight_details['FlightDetails']['Details'][$i];
                         $stops_count = count($details);


                    $segments        = $details;
                    $first_flight    = reset($segments);
                    $last_flight     = end($segments);
                    $strSumaryKey    = $i;
                    $fare_basis = $flight_details['Price']['TotalDisplayFare'];
                    $summary[$strSumaryKey]['origin']['loc']            = $details[0]['Origin']['AirportCode'];
                    $summary[$strSumaryKey]['origin']['city']           = $details[0]['Origin']['CityName'];
                    $summary[$strSumaryKey]['origin']['datetime']       = $details[0]['Origin']['DateTime'];
                    $summary[$strSumaryKey]['origin']['date']           = date('d M Y',strtotime($details[0]['Origin']['DateTime']));
                    $summary[$strSumaryKey]['origin']['time']           = date('H:i',strtotime($details[0]['Origin']['DateTime']));
                    $summary[$strSumaryKey]['origin']['fdtv']           = $details[0]['Origin']['FDTV'];
                    $summary[$strSumaryKey]['destination']['loc']       = $details[$stops_count-1]['Destination']['AirportCode'];
                    $summary[$strSumaryKey]['destination']['city']      = $details[$stops_count-1]['Destination']['CityName'];
                    $summary[$strSumaryKey]['destination']['datetime']  = $details[$stops_count-1]['Origin']['DateTime'];
                    $summary[$strSumaryKey]['destination']['date']      = date('d M Y',strtotime($details[$stops_count-1]['Destination']['DateTime']));
                    $summary[$strSumaryKey]['destination']['time']      = date('H:i',strtotime($details[$stops_count-1]['Destination']['DateTime']));
                    $summary[$strSumaryKey]['destination']['fdtv']      = $details[$stops_count-1]['Destination']['FATV'];
                    $summary[$strSumaryKey]['operator_code']            = $details[0]['OperatorCode'];
                    $flight_pcode = $summary[$strSumaryKey]['display_operator_code']    = $details[0]['OperatorCode'];
                    $summary[$strSumaryKey]['operator_name']            = $details[0]['OperatorName'];
                    $summary[$strSumaryKey]['class']['name'] = $summary[$strSumaryKey]['cabin_class']               = $details[0]['CabinClass'];
                    $flight_pnumber = $summary[$strSumaryKey]['flight_number']          = $details[0]['FlightNumber'];
                    $summary[$strSumaryKey]['no_of_stops']              = COUNT($segments) - 1;
                    $summary[$strSumaryKey]['duration_seconds']         = $this->provab_duration_calculation_second($details[0]['Origin']['DateTime'], $details[$stops_count-1]['Destination']['DateTime']);
                    $summary[$strSumaryKey]['duration']                 = $this->provab_duration_calculation($details[0]['Origin']['DateTime'], $details[$stops_count-1]['Destination']['DateTime']);
                    $summary[$strSumaryKey]['Meal']                     = "";
                    $summary[$strSumaryKey]['Meal_description']         = '';
                    $summary[$strSumaryKey]['SeatsRemaining']           = $details[0]['Attr']['AvailableSeats'];
                    $summary[$strSumaryKey]['Weight_Allowance']         = $details[0]['Attr']['Baggage'];
                    $strDetails[$strSumaryKey]                          = $this->flight_pdetails($segments);
                        $pflight_list_arr[$key][0]['FlightDetails']['summary']       = $summary;
                        $pflight_list_arr[$key][0]['FlightDetails']['Details']       = $strDetails;
                        
                    }
                }
                
                
            }

            // debug( $pflight_list_arr);exit();
            $psflight_list['data']['flight_data_list']['journey_list'][0] = $pflight_list_arr;
            return $psflight_list;
    }
    

    public function provab_duration_calculation($DepartureTime, $ArrivalTime)
    { 
        
       $date_a   = new DateTime($ArrivalTime);
        $date_b   = new DateTime($DepartureTime);
        $interval   = date_diff($date_a, $date_b);
        $hour     = $interval->format('%h');
        $min    = $interval->format('%i');
        $day1   = $interval->format('%d');
        $dur_in_min = ((($hour * 60) + $min) + (($Changeclock0 * 60) + $Changeclock1));
        $hour     = FLOOR($dur_in_min / 60);
        $min    = $dur_in_min%60;
        // echo '<pre/>';print_r($interval);
        if($hour<0){  $hour=((24)+($hour)); $day1     -= 1;
        }else{
          $day1     += floor(((($hour * 60) + $min) / 1440));
        }
        if($min<0){ $min=((60)+($min)); }
        $hours    = floor((($hour * 60) + $min) / 60);
        $minutes  = ((($hour * 60) + $min) % 60);
        
        if($hours>24){ $hours=floor($hours % 24);}
        if ($day1 > 0){
            if($hours == 24){
                $hours = 0;
            }
            
          $dur=$day1."d ".$hours."h ".$minutes."m";
        } else {
          $dur=$hours."h ".$minutes."m";
        }
        return $dur;
    }


     public function get_pflight_price($pflight_list, $flight_code, $flight_number, $private_flight, $fare_basis)
    {
    	// debug($pflight_list);
        $myfare = array();
        $myfare['publicFareBasisCodes'][0]['publicFareBasisCodes_value'] = '';
        $myfare['api_currency'] = $pflight_list['price']['api_currency'];
        $myfare['api_total_display_fare'] = $pflight_list['price']['api_total_display_fare'];
        $myfare['api_total_display_fare_withoutmarkup'] = $pflight_list['price']['api_total_fare'];
        $myfare['total_breakup']['api_total_fare'] = $pflight_list['price']['pricebreakup']['api_total_fare'];
        $myfare['total_breakup']['api_total_tax'] = $pflight_list['price']['pricebreakup']['api_total_tax'];
        $myfare['api_total_display_fare_normal'] = 0;
        $myfare['total_breakup']['api_total_fare_publish'] = 0;
        $myfare['total_breakup']['api_total_tax_publish'] = 0;
        $myfare['pax_breakup'] = $pflight_list['price']['passengerbreakup'];
        // debug($myfare);exit();
        return $myfare;
    }

    //   public function flight_pdetails($pflight_list)
    // {
    // 	// debug($pflight_list);exit();
    //     // debug($pflight_list['OriginDetails']['CityName']);exit();
    //     $SegmentCount =count($pflight_list);
    //     // debug($SegmentCount);
    //     $summary = array();
    //      foreach ($pflight_list as $key => $flight_details) {
    //      	// debug($flight_details);exit();
    //         $summary[$key]['origin']['loc'] = $flight_details['OriginDetails']['AirportCode'];
    //         $summary[$key]['origin']['city'] = $flight_details['OriginDetails']['CityName'];
    //         $summary[$key]['origin']['datetime'] = $flight_details['OriginDetails']['DateTime'];
    //         $summary[$key]['origin']['date'] = date('d M Y',strtotime(substr($flight_details['OriginDetails']['DateTime'], 0, -8)));
    //         $summary[$key]['origin']['time'] = date('H:i',strtotime($flight_details['OriginDetails']['DateTime']));
           
    //         $summary[$key]['origin']['fdtv'] = strtotime($flight_details['OriginDetails']['DateTime']);

    //         $summary[$key]['destination']['loc']        = $flight_details['DestinationDetails']['AirportCode'];
    //         $summary[$key]['destination']['city']       = $flight_details['DestinationDetails']['CityName'];
    //         $summary[$key]['destination']['datetime']   = $flight_details['DestinationDetails']['DateTime'];
    //         $summary[$key]['destination']['date'] = date('d M Y',strtotime(substr($flight_details['DestinationDetails']['DateTime'], 0, -8)));
    //         $summary[$key]['destination']['time'] = date('H:i',strtotime($flight_details['DestinationDetails']['DateTime']));
    //         $summary[$key]['destination']['fdtv'] = strtotime($flight_details['DestinationDetails']['DateTime']);

    //         $summary[$key]['operator_code'] =$flight_details['OperatorCode'];
    //         $summary[$key]['display_operator_code'] = $flight_details['OperatorCode'];
    //         $summary[$key]['operator_name'] = $flight_details['OperatorName'];
    //         $summary[$key]['class']['name'] = $summary[$key]['cabin_class'] = $flight_details['CabinClass'];
    //         $summary[$key]['flight_number'] = $flight_details['FlightNumber'];
    //         $summary[$key]['no_of_stops'] = 0;

    //         $summary[$key]['duration_seconds'] = $this->provab_duration_calculation_second($flight_details['OriginDetails']['DateTime'], $flight_details['DestinationDetails']['DateTime']);
            
    //         $summary[$key]['duration'] = 
    //             $this->provab_duration_calculation($flight_details['OriginDetails']['DateTime'], $flight_details['DestinationDetails']['DateTime']);

    //         if($SegmentCount > 1 && $key < ($SegmentCount-1)){
    //         $summary[$key]['WaitingTime'] = 
    //             $this->provab_duration_calculation($flight_details['DestinationDetails']['DateTime'],$pflight_list[$key+1]['OriginDetails']['DateTime']);
    //         }



    //         $summary[$key]['Meal'] = "";
    //         $summary[$key]['Meal_description'] = '';
    //         $summary[$key]['SeatsRemaining'] = $flight_details['Attr']['AvailableSeats'];
    //         $summary[$key]['Weight_Allowance'] = $flight_details['Attr']['Baggage'];
    //         // debug($summary);exit();

    //     }
    //         // echo "stringw";exit();
       
    //     // debug($summary);exit();
    //     return $summary;
    // }


      public function flight_pdetails($pflight_list)
    {
        $SegmentCount =count($pflight_list);
        $summary = array();
        foreach ($pflight_list as $key => $flight_details) {

            // debug($flight_details);exit();
             
             // $temp = $flight_details['Origin']['DateTime'];
             // $flight_details['Origin']['DateTime'] = $flight_details['Destination']['DateTime'];
             // $flight_details['Destination']['DateTime'] = $temp;
            // $temp = date('H:i',strtotime($flight_details['Origin']['DateTime']));
            // $summary[$key]['destination']['time']
            
            $summary[$key]['origin']['loc'] = $flight_details['Origin']['AirportCode'];
            $summary[$key]['origin']['city'] = $flight_details['Origin']['CityName'];
            $summary[$key]['origin']['datetime'] = $flight_details['Origin']['DateTime'];
            $summary[$key]['origin']['date'] = date('d M Y',strtotime($flight_details['Origin']['DateTime']));
            $summary[$key]['origin']['time'] = date('H:i',strtotime($flight_details['Origin']['DateTime']));
            $summary[$key]['origin']['fdtv'] = strtotime($flight_details['Origin']['DateTime']);
            $summary[$key]['destination']['loc']        = $flight_details['Destination']['AirportCode'];
            $summary[$key]['destination']['city']       = $flight_details['Destination']['CityName'];
            $summary[$key]['destination']['datetime']   = $flight_details['Destination']['DateTime'];
            $summary[$key]['destination']['date'] = date('d M Y',strtotime($flight_details['Destination']['DateTime']));
            $summary[$key]['destination']['time'] = date('H:i',strtotime($flight_details['Destination']['DateTime']));
            $summary[$key]['destination']['fdtv'] = strtotime($flight_details['Destination']['DateTime']);
             $summary[$key]['operator_code'] =$flight_details['OperatorCode'];
            $summary[$key]['display_operator_code'] = $flight_details['OperatorCode'];
            $summary[$key]['operator_name'] = $flight_details['OperatorName'];
            $summary[$key]['class']['name'] = $summary[$key]['cabin_class'] = $flight_details['CabinClass'];
            $summary[$key]['flight_number'] = $flight_details['FlightNumber'];
            $summary[$key]['no_of_stops'] = 0;
            $summary[$key]['duration_seconds'] = $this->provab_duration_calculation_second($flight_details['Origin']['DateTime'], $flight_details['Destination']['DateTime']);
            $summary[$key]['duration'] = 
                $this->provab_duration_calculation($flight_details['Origin']['DateTime'], $flight_details['Destination']['DateTime']);
            if($SegmentCount > 1 && $key < ($SegmentCount-1)){
             $summary[$key]['WaitingTime'] = 
                $this->provab_duration_calculation($flight_details['Destination']['DateTime'],$pflight_list[$key+1]['Origin']['DateTime']);
            }

            $summary[$key]['Meal'] = "";
            $summary[$key]['Meal_description'] = '';
            $summary[$key]['SeatsRemaining'] = $flight_details['Attr']['AvailableSeats'];
            $summary[$key]['Weight_Allowance'] = $flight_details['Attr']['Baggage'];
        }
        // debug($summary);exit();
        return $summary;
    }

    public function provab_duration_calculation_second($DepartureTime, $ArrivalTime)
    {
        $ArrivalDateTime = strtotime($ArrivalTime);
        $DepartureDateTime = strtotime($DepartureTime);
        $seconds = $ArrivalDateTime - $DepartureDateTime;
        return $seconds;
    }

    public function update_pnr_booking_details($book_id, $pnr_no)
    {
    	$update_itenerary_data = array('airline_pnr' => $pnr_no);
    	// if ($_SERVER['REMOTE_ADDR'] == '223.227.55.238')
    	// debug($update_itenerary_data);debug($book_id);exit;
    	$GLOBALS['CI']->custom_db->update_record('flight_booking_itinerary_details',$update_itenerary_data,array('app_reference' => $book_id));
    	return true;
     }
    

}
