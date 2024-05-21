<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');
	require_once BASEPATH . 'libraries/Common_Api_Grind.php';

	/**
	 * @package Provab
	 * @subpackage API
	 * @author Chandrasekar
	 * @version V1
	 */
	class Hotelbeds_Transfer extends Common_Api_Grind {

		private $ClientId;
		private $UserName;
		private $Password;
		private $Url;
		private $LoginType = '2'; // (For API Users, value should be ‘2’)
		private $EndUserIp = '127.0.0.1';
		private $TokenId; // Token ID that needs to be echoed back in every subsequent request
		public $master_search_data;
		public $search_hash;
		protected $ins_token_file;
		protected $medium_image_base_url;
		protected $small_image_base_url;
		protected $service_url;
		protected $signature;
		protected $api_online_key;
		protected $json_api_header;
		protected $xml_api_header;
		protected $api_data_currency = 'EUR';
		protected $source_code = HOTELBED_TRANSFER_BOOKING_SOURCE;

		public function __construct() {
			parent::__construct(META_TRANSFERS_COURSE, $this->source_code);
			$this->CI = &get_instance();
			$GLOBALS ['CI']->load->library('Api_Interface');
			//$GLOBALS ['CI']->load->library ( 'converter' );
			$GLOBALS ['CI']->load->model('transfer_model');
			$GLOBALS ['CI']->load->model ( 'transferv1_model' );
			$GLOBALS ['CI']->load->model ( 'domain_management_model' );
			$GLOBALS ['CI']->load->model('custom_db');
			$this->set_api_credentials();
		}

		private function set_api_credentials() {
	        $engine_system 		= $this->CI->config->item ( 'transfer_engine_system' );
	        $this->system 		= $engine_system;
	        $this->config		= $this->CI->config->item ( 'hb_transfer_' . $engine_system );
	        $this->service_url 	= $this->config['api_url'];
	        $this->username 	= $this->config['user_id'];
	        $this->password 	= $this->config['password']; 

	        // debug($this->CI->config);exit();
		}

	    function is_b2c(){
	        $this->CI = &get_instance ();
	        $arrSessData = $this->CI->session->all_userdata();
	        // debug($arrSessData);exit;
	        $is_b2c = 0;
	        if(isset($arrSessData['loged_type']) && $arrSessData['loged_type'] == "b2c"){
	            $is_b2c = 1;
	        }
	        return $is_b2c;
	    }		

		/**
		 * Header to be used for hotebeds - X API Version
		 */
		private function xml_header() {
			$this->xml_api_header = array('Api-Key: ' . $this->api_online_key,
				'X-Signature: ' . $this->signature,
				'X-Originating-Ip: '.PUBLIC_IP,
				'Content-Type: application/xml',
				'Accept: application/xml'
			);
			// debug($this->xml_api_header);exit();
			return $this->xml_api_header;
		}

		/**
		 * get transfer search request details
		 *
		 * @param array $search_params
		 *          data to be used while searching of transfer
		 */
		public function transfer_search_data($search_id) {
			//error_reporting(E_ALL);
			$search_data = $this->search_data($search_id);

			// debug($search_data);exit;
			$transfer_search_request = $this->transfer_search_request($search_data);
			// debug($transfer_search_request); 
			if ($_SERVER['REMOTE_ADDR'] == "14.141.47.106") {
				//echo "xml_request";debug($transfer_search_request);
			}
			if ($transfer_search_request['status'] == SUCCESS_STATUS) {
				$this->CI->custom_db->generate_static_response_hb($transfer_search_request['request'], 'transfer search request', 'hotelbeds_transfer');
				/*   $path = FCPATH . "all_log_files/TransferValuedAvailRQ_" . date('Y_m_d_H_i_s') . ".xml";
				 $fp = fopen($path, "wb");
				 fwrite($fp, $transfer_search_request['request']);
				 fclose($fp); */
			}

			$status = true;

			if ($transfer_search_request['status']) {
				$url = $this->service_url;
				// $url = 'http://api.interface-xml.com/appservices/http/FrontendService';
                $strIsb2c = $this->is_b2c();
                $strFpath = "";
                if($strIsb2c == 0){
                    $strFpath = "../";
                }

                if(true){
	                $path = $strFpath."all_xml_logs/transfer/hb/search/SearchRQ_".$search_id.".xml";
	                $fp = fopen($path,"wb");fwrite($fp,$transfer_search_request['request']);fclose($fp);

	                // debug($url);
	                // debug($transfer_search_request);exit;
	                // debug($this->xml_header());exit();
	              
					$transfer_search_response = $GLOBALS ['CI']->api_interface->xml_post_request($url, $transfer_search_request['request'], $this->xml_header());

					// debug($transfer_search_response);exit;
				
					if ($_SERVER['REMOTE_ADDR'] == "14.141.47.106") {
						// echo "xml_response";debug($transfer_search_response);exit;
					}

	                $path = $strFpath."all_xml_logs/transfer/hb/search/SearchRS_".$search_id.".xml";
	                $fp = fopen($path,"wb");fwrite($fp,$transfer_search_response);fclose($fp);
	                //exit;
	            }else{
	            	$transfer_search_response = file_get_contents($strFpath."all_xml_logs/transfer/hb/search/SearchRS_".$search_id.".xml");
	            }

				/*  $path = FCPATH . "all_log_files/TransferValuedAvailRS_" . date('Y_m_d_H_i_s') . ".xml";
				 $fp = fopen($path, "wb");
				 fwrite($fp, $transfer_search_response);
				 fclose($fp); */

				// debug($transfer_search_response);exit;
				$this->CI->custom_db->generate_static_response_hb($transfer_search_response, 'transfer search response', 'hotelbeds_transfer');
				$transfer_search_response = simplexml_load_string($transfer_search_response);
				// debug($transfer_search_response);exit;

				$waiting_time_array = array();
				$u = 0;
				$strCurrency = "";
				foreach ($transfer_search_response->ServiceTransfer as $servicetransfer => $servicetransfer_data) {
					if($strCurrency == ""){
						$strCurrency = $servicetransfer_data->Currency->attributes();
					}
					if (isset($servicetransfer_data->TransferInfo->TransferSpecificContent->MaximumWaitingTimeSupplierDomestic)) {
						foreach ($servicetransfer_data->TransferInfo->TransferSpecificContent->MaximumWaitingTimeSupplierDomestic->attributes() as $domestic_tag => $domestic_val) {

							$waiting_time_array[$u]['Domestic'][$domestic_tag] = (string) $domestic_val;
						}
					}
					if (isset($servicetransfer_data->TransferInfo->TransferSpecificContent->MaximumWaitingTimeSupplierInternational)) {
						foreach ($servicetransfer_data->TransferInfo->TransferSpecificContent->MaximumWaitingTimeSupplierInternational->attributes() as $international_tag => $international_val) {

							$waiting_time_array[$u]['International'][$international_tag] = (string) $international_val;
						}
					}

					if (isset($servicetransfer_data->TransferInfo->TransferSpecificContent->MaximumWaitingTime)) {
						foreach ($servicetransfer_data->TransferInfo->TransferSpecificContent->MaximumWaitingTime->attributes() as $customer_tag => $customer_val) {

							$waiting_time_array[$u]['Customer'][$customer_tag] = (string) $customer_val;
						}
					}
					$u++;
				}

				if ($_SERVER['REMOTE_ADDR'] == "192.168.0.26") {
					//echo "array_response";debug($transfer_search_response);exit;
				}
				$transfer_search_response = json_encode($transfer_search_response);
				//debug($transfer_search_response);exit;
				$transfer_search_response = json_decode($transfer_search_response, TRUE);
				//debug($transfer_search_response);exit;
				$transfer_search_response['waiting_time'] = $waiting_time_array;
				if (isset($transfer_search_response['ServiceTransfer']) && valid_array($transfer_search_response['ServiceTransfer'])) {
					$status = true;
					$transfer_data = $this->format_transfer_response($transfer_search_response);
					//debug($transfer_data);exit;
					$response['data']['SSSearchResult']['TransferResults']   = $transfer_data;
					$response['currency'] 	= $strCurrency;
				} else {
					$status = false;
				}
			}

			$response['status'] = $status;
			// debug($response);exit;
			return $response;
		}

		/*
		format response to tmx format
		*/

		public function format_transfer_response($transfer_response){
			//debug($transfer_response['ServiceTransfer']);exit;
			foreach ($transfer_response['ServiceTransfer'] as $key => $response) {

			$add_to_service_array['transfer_code'] = $response['TransferInfo']['Code'];
			$add_to_service_array['transfer_type_code'] = $response['TransferInfo']['Type']['@attributes']['code'];
			$add_to_service_array['vehicle_type_code'] = $response['TransferInfo']['VehicleType']['@attributes']['code'];
			$add_to_service_array['adult_count'] = $response['Paxes']['AdultCount'];
			$add_to_service_array['child_count'] = $response['Paxes']['ChildCount'];
			$add_to_service_array['transfer_type'] = $response['@attributes']['transferType'];
			$add_to_service_array['name'] = $response['ContractList']['Contract']['Name'];
			$add_to_service_array['incoming_office_code'] = $response['ContractList']['Contract']['IncomingOffice']['@attributes']['code'];
			$add_to_service_array['from_date'] = $response['DateFrom']['@attributes']['date'];
			$add_to_service_array['from_date_time'] = $response['DateFrom']['@attributes']['time'];
			$add_to_service_array['pickup_location_code'] = $response['PickupLocation']['Code'];
			$add_to_service_array['pickup_location_name'] = $response['PickupLocation']['Name'];
			$add_to_service_array['pickup_location_transfer_zone'] = $response['PickupLocation']['TransferZone']['Code'];

			$add_to_service_array['destin_location_code'] = $response['DestinationLocation']['Code'];
			$add_to_service_array['destin_location_name'] = $response['DestinationLocation']['Name'];
			$add_to_service_array['destin_location_transfer_zone'] = $response['DestinationLocation']['TransferZone']['Code'];

				$transfer_results[] = Array
                                (
                                    'ProductName' 	=> '',
                                    'ProductCode' 	=> '',
                                    'ImageUrl'		=> $response['TransferInfo']['ImageList']['Image'][0]['Url'],
                                    'ImageHisUrl' 	=> $response['TransferInfo']['ImageList']['Image'][0]['Url'],
                                    'BookingEngineId' => '',
                                    'Promotion' => '',
                                    'PromotionAmount' => 0,
                                    'StarRating' => 0,
                                    'ReviewCount' => 0,
                                    'DestinationName' => $response['DestinationLocation']['Name'],
                                    'Price' => Array
                                        (
                                            'TotalDisplayFare' => $response['TotalAmount'],
                                            'GSTPrice' => 0,
                                            'PriceBreakup' => Array
                                                (
                                                    'AgentCommission' => 0,
                                                    'AgentTdsOnCommision' => 0,
                                                ),

                                            'Currency' => $response['Currency']['@attributes']['code'],
                                        ),

                                    'Description' => implode(',',$response['TransferInfo']['DescriptionList']['Description']),
                                    'Cancellation_available' => 0,
                                    'Cat_Ids' => Array(),
                                    'Sub_Cat_Ids' => Array(),
                                    'Supplier_Code' => '',
                                    'Duration' => '',
                                    'ResultToken' => '',
                                    'TotalPax'=>array_sum($response['Paxes']),
                                    'ProductSpecifications'=>$response['ProductSpecifications'],
                                    'TransferPickupInformation'=>$response['TransferPickupInformation'],
                                    'GenericTransferGuidelinesList'=>$response['TransferInfo']['TransferSpecificContent']['GenericTransferGuidelinesList']['TransferBulletPoint'],
                                    'avail_token' => $response['@attributes']['availToken'],
                                    'currency' => $response['Currency']['@attributes']['code'],
                                    'add_to_service_array'=>$add_to_service_array
                                    
                                );
			}
//debug($transfer_results);exit;
			return $transfer_results;
		}

		/**
	 * Converts API data currency to preferred currency
	 * Elavarasi
	 * 
	 * @param unknown_type $search_result        	
	 * @param unknown_type $currency_obj        	
	 */
	public function search_data_in_preferred_currency($search_result, $currency_obj,$module='b2c') {

		$sightseeing = $search_result ['data'] ['SSSearchResult'] ['TransferResults'];
		$sightseeing_list = array ();
		foreach ( $sightseeing as $hk => $hv ) {
			$sightseeing_list [$hk] = $hv;
			$sightseeing_list [$hk] ['Price'] = $this->module_preferred_currency_fare_object ( $hv ['Price'], $currency_obj,'',$module );
		}
		$search_result ['data'] ['SSSearchResult'] ['PreferredCurrency'] = get_application_currency_preference ();
		$search_result ['data'] ['SSSearchResult'] ['TransferResults'] = $sightseeing_list;
		return $search_result;
	}
	public function details_data_in_preffered_currency($fare_details,$currency_obj,$module='b2c'){
        return $this->module_preferred_currency_fare_object($fare_details,$currency_obj,'',$module);
    }

	/**
	 * Elavarasi
	 * 
	 * @param unknown_type $fare_details        	
	 * @param unknown_type $currency_obj        	
	 */
	private function module_preferred_currency_fare_object($fare_details, $currency_obj, $default_currency = '',$module='b2c') {

		#debug($fare_details);
		$admin_commission = 0;
		$admin_tdson_commission = 0;
		$agent_commission = 0;
		$agent_tdson_commission = 0;
		$org_commission = 0;
		$or_tdson_commission = 0;
		$admin_profit = 0;
		$show_net_fare = 0;

		if(isset($fare_details['PriceBreakup'])){
			$admin_commission = $fare_details['PriceBreakup']['AgentCommission'];
			$admin_tds =$fare_details['PriceBreakup']['AgentTdsOnCommision'];
		}else{
			$admin_commission = $fare_details['AgentCommission'];
			$admin_tds =$fare_details['AgentTdsOnCommision'];
		}

		if($module=='b2c'){

			$net_fare = $fare_details['TotalDisplayFare']-$admin_commission+$admin_tds;		
			$agent_commission = $admin_commission;
			$agent_tdson_commission =$admin_tds;
			
			
		}else{
			//for b2b users
			//Updating Commission			
			// debug($fare_details);
			// exit;
			$agent_commission = $fare_details['PriceBreakup']['AgentCommission'];
			$agent_tdson_commission = $fare_details['PriceBreakup']['AgentTdsOnCommision'];			
			$net_fare =$fare_details['TotalDisplayFare'];
			#$admin_commission = $admin_commission;
			#$admin_tdson_commission = $agent_tds;
		}
		//echo $net_fare;
		$price_details = array ();
		$price_details ['Currency'] = empty ( $default_currency ) == false ? $default_currency : get_application_currency_preference ();

	

		$price_details ['TotalDisplayFare'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $net_fare) );

		$price_details['AgentCommission'] =get_converted_currency_value ( $currency_obj->force_currency_conversion ( $agent_commission) );

		$price_details['AgentTdsOnCommision'] =get_converted_currency_value ( $currency_obj->force_currency_conversion ( $agent_tdson_commission) );
		//$price_details['AdminCommProfit'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $admin_profit) );
		if($module=='b2c'){
			$show_net_fare = $price_details ['TotalDisplayFare'];
			//$price_details ['TotalDisplayFare'] = $show_net_fare;
		}else{
			$show_net_fare = ($price_details ['TotalDisplayFare']-$price_details['AgentCommission']);	
		}
		
		$price_details ['NetFare'] =$show_net_fare;

		//$price_details ['GSTPrice'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['GSTPrice'] ) );		
		#debug($price_details);
		#exit;
		return $price_details;
	}



		/**
		 *
		 * @param int $search_id
		 */
		public function search_data($search_id) {
			$response ['status'] = true;
			$response ['data'] = array();
			if (empty($this->master_search_data) == true and valid_array($this->master_search_data) == false) {
				$search_data = $clean_search_details = $GLOBALS ['CI']->transfer_model->get_safe_search_data($search_id);


				// debug($search_data);exit();
				if (isset($search_data) && !empty($search_data)) {
					$response ['status'] 						= true;
					$response ['data'] 							= $search_data ['data'];
					$response ['data']['transfer_from'] 		= $search_data['data']['from_code'];
					$response ['data']['transfer_to'] 			= $search_data['data']['to_code'];
					$response ['data']['adult'] 				= $search_data['data']['adult'];
					$response ['data']['child'] 				= $search_data['data']['child'];
					$response ['data']['depature'] 				= date('Ymd', strtotime($search_data['data']['depature']));
					$response ['data']['depart_time'] 			= $search_data['data']['depature_time'];
					if (isset($search_data['data']['return'])) {
						$response ['data']['return'] 			= date('Ymd', strtotime($search_data['data']['return']));
						$response ['data']['return_time'] 		= $search_data['data']['return_time'];
					}
					$response ['data']['from_transfer_type'] 	= $search_data['data']['from_transfer_type'];
					$response ['data']['to_transfer_type'] 		= $search_data['data']['to_transfer_type'];
					$response ['data']['trip_type'] 			= $search_data['data']['trip_type'];
				} else {
					$response ['status'] = false;
				}
			} else {
				$response ['data'] = $this->master_search_data;
			}
			$this->search_hash = md5(serialized_data($response ['data']));
			return $response;
		}

		/*
		 * create search request for list
		 * */

		function transfer_search_request($search_data) {
			// error_reporting(E_ALL);
			$request = '';
			$transfer_from = '';
			$transfer_to = '';
			$adult = '';
			$child = '';
			$from_transfer_type = '';
			$to_transfer_type = '';
			$depature = '';
			$return = '';
			//debug($search_params);exit;
			//  $search_data = $clean_search_details = $GLOBALS ['CI']->transfer_model->get_safe_search_data ( $search_params );

			if (isset($search_data) && !empty($search_data)) {
				$transfer_from = $search_data['data']['from_code'];
				$transfer_to = $search_data['data']['to_code'];
				$adult = $search_data['data']['adult'];
				$child = $search_data['data']['child'];
				$depature = date('Ymd', strtotime($search_data['data']['depature']));
				 // $search_data['data']['depature_time'] = '0900';// FIXME
				 $search_data['data']['depature_time'] =str_replace(':', '', $search_data['data']['depature_time_flight']);// FIXED
				$depart_time = $search_data['data']['depature_time'];

				if (isset($search_data['data']['return'])) {
					$return = date('Ymd', strtotime($search_data['data']['return']));
					//$search_data['data']['return_time'] = '0900';// FIXME
					$return_time = $search_data['data']['return_time'];
				}
				$from_transfer_type = $search_data['data']['from_transfer_type'];
				$to_transfer_type = $search_data['data']['to_transfer_type'];
				$trip_type = $search_data['data']['trip_type'];
			}
			//$this->username = 'INTERAVIA315070';
			//$this->password = 'INTERAVIA315070';
			//debug($depart_time);exit;
			$request = '<TransferValuedAvailRQ xmlns="http://api.interface-xml.com/schemas/2005/06/messages" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" echoToken="DummyEchoToken" sessionId="DummySessionId" xsi:schemaLocation="http://api.interface-xml.com/schemas/2005/06/messages TransferValuedAvailRQ.xsd" version="2013/12">
                    <Language>ENG</Language>
                    <Credentials>
                        <User>' . $this->username . '</User>
                        <Password>' . $this->password . '</Password>
                    </Credentials>
                    <AvailData type="IN">
                        <ServiceDate date="' . $depature . '" time="' . $depart_time . '"/>
                        <Occupancy>
                            <AdultCount>' . $adult . '</AdultCount>
                            <ChildCount>' . $child . '</ChildCount>';
			$request .= '<GuestList>';
			foreach ($search_data['data']['adult_ages'] as $a_key => $adult_age) {
				$request .= '<Customer type="AD">
                                                <Age>' . $adult_age . '</Age>
                                             </Customer>';
			}
			if (isset($search_data["data"]["child_ages"]) && valid_array($search_data['data']['child_ages'])) {
				foreach ($search_data['data']['child_ages'] as $c_key => $child_age) {
					$request .= '<Customer type="CH">
                                                        <Age>' . $child_age . '</Age>
                                                    </Customer>';
				}
			}
			// $transfer_from = 'BLR';
			// $transfer_to = 'BLR';
			$request .= '</GuestList>';
			$request .= '</Occupancy>
                        <PickupLocation xsi:type="' . $from_transfer_type . '">
                            <Code>' . $transfer_from . '</Code>';
			if ($from_transfer_type === 'ProductTransferTerminal') {
				$request.= '<DateTime date="' . $depature . '" time="' . $depart_time . '"/>';
			}
			$request.= '</PickupLocation>
                        <DestinationLocation xsi:type="' . $to_transfer_type . '">
                        <Code>' . $transfer_to . '</Code>';
			if ($to_transfer_type === 'ProductTransferTerminal') {
				$request.= '<DateTime date="' . $depature . '" time="' . $depart_time . '"/>';
			}
			$request.= '</DestinationLocation>
                        </AvailData>';
			/* For Return trip */
			if ($trip_type === 'circle') {
				$request.= '<AvailData type="OUT">
                        <ServiceDate date="' . $return . '" time="' . $return_time . '"/>
                        <Occupancy>
                            <AdultCount>' . $adult . '</AdultCount>
                            <ChildCount>' . $child . '</ChildCount>';
				if (isset($search_data["data"]["child_ages"]) && valid_array($search_data['data']['child_ages'])) {
					$request .= '<GuestList>';
					foreach ($search_data['data']['child_ages'] as $c_key => $child_age) {
						$request .= '<Customer type="CH">
                                                        <Age>' . $child_age . '</Age>
                                                    </Customer>';
					}
					$request .= '</GuestList>';
				}
				$request .= '</Occupancy>
                        <PickupLocation xsi:type="' . $to_transfer_type . '">
                            <Code>' . $transfer_to . '</Code>';
				if ($to_transfer_type === 'ProductTransferTerminal') {
					$request.= '<DateTime date="' . $return . '" time="' . $return_time . '"/>';
				}
				$request.= '</PickupLocation>
                                    <DestinationLocation xsi:type="' . $from_transfer_type . '">
                                    <Code>' . $transfer_from . '</Code>';
				if ($from_transfer_type === 'ProductTransferTerminal') {
					$request.= '<DateTime date="' . $return . '" time="' . $return_time . '"/>';
				}
				$request.= '</DestinationLocation>
                                        </AvailData>';
			}
			$request.= '<ReturnContents>Y</ReturnContents>
                    </TransferValuedAvailRQ>';

			//header("Content-type: text/xml");
			//header('Content-Type: application/json');
			//print_r($request); exit;

			$response['request'] = $request;
			$response['status'] = true;
			// debug($response);exit;
			return $response;
		}

		function built_formatted_array($serviceTransfer, $search_id = '', $module, $waiting_time = array(), $currencyobj, $currency_obj_m, $markupData) { //debug($serviceTransfer);
			//error_reporting(E_ALL);
			$response = array();

			if (!empty($waiting_time) && valid_array($waiting_time)) {
				if (isset($waiting_time['Customer'])) {
					$response['customer_waiting_time'] = $waiting_time['Customer']['time'];
				}
			}

			$response['avail_token'] = $serviceTransfer['@attributes']['availToken'];
			$response['name'] = $serviceTransfer['ContractList']['Contract']['Name'];
			$response['service_type_name'] = $serviceTransfer['@attributes']['transferType'];

			$total_amt = $serviceTransfer['TotalAmount'];
			//debug($total_amt);exit;
			//$currency_obj = new Currency(array('module_type' => 'transfer', 'from' => $serviceTransfer['Currency']['@attributes']['code'], 'to' => get_application_display_currency_preference()));
			$currency_obj = $currencyobj;
			$deduction_cur_obj = clone $currency_obj;
			$arrConvAmt   = $currency_obj->get_currency(1, true, false, true, 1);
			$strConvcur   = $arrConvAmt['default_currency'];
			$currency_symbol = $currency_obj->get_currency_symbol($strConvcur);
			
			//debug($currency_obj);exit;
			//  echo  $module;exit;
			if ($module == 'b2c') {
				//Convert to default currency and add mark up
				if(false){
					$markup_total_fare = $currency_obj->get_currency($total_amt, true, false, true, false, 1); // (ON Total PRICE ONLY)
					$ded_total_fare = $deduction_cur_obj->get_currency($total_amt, false, false, false, false, 1); // (ON Total PRICE ONLY)
					$admin_markup = sprintf("%.2f", $markup_total_fare ['default_value'] - $ded_total_fare ['default_value']);
					$response['admin_markup'] = $admin_markup;
					$mark_currency = $markup_total_fare['default_currency'];
					$tax = $GLOBALS ['CI']->transfer_model->get_tax();
					$service_tax = ($tax['data']['service_tax']);
					$service_amount = ($markup_total_fare['default_value'] * $service_tax) / 100;

					$response['service_tax'] 	= $service_amount;
					$response['total_amout'] 	= ($markup_total_fare['default_value'] + $service_amount);
					$response['API_Price'] 		= $ded_total_fare['default_value'];
					$response['currency'] 		= $markup_total_fare['default_currency'];
				}else{
					$strAdminMarkup = 0;
					// debug($currency_obj_m);exit;
					$arrTotConvAmt          = $currency_obj->get_currency(1, false, false, false, false, 1);
					$strTotConvAmt      	= $arrTotConvAmt['default_value'];
					// echo $total_amt;
					// echo $strTotConvAmt;exit;
					$strTotalAmtFor_c		= ($total_amt*$strTotConvAmt);
					if(valid_array($markupData)){
						if($markupData['value_type'] == "plus"){
							$strCurrency_m  = $currency_obj_m->get_currency(1, false, false, false, false, 1);
							$strConAmt      = $strCurrency_m['default_value'];
							//debug($strCurrency_m);exit;
							$strAdminMarkup = $markupData['value']*$strConAmt;
						}else{
							$strMVal = $markupData['value'];
							$strAdminMarkup = ($strTotalAmtFor_c*$strMVal)/100;
						}
					}
					$service_amount 		  = 0;
					$response['admin_markup'] = $strAdminMarkup;					
					$strTotalAmt = $strTotalAmtFor_c + $strAdminMarkup;

					$response['service_tax'] 	= $service_amount;
					$response['total_amout'] 	= round($strTotalAmt,2);
					$response['API_Price'] 		= $strTotalAmt;
					$response['currency'] 		= get_application_display_currency_preference();
					$response['currency_symbol'] 				= $currency_symbol;						
				}
			} else {

				if(false){
					$net_rate = $currency_obj->get_currency($total_amt, false, false, false, false, 1);

					$markup_total_fare = $currency_obj->get_currency($total_amt, true, false, true, false, 1); // (ON Total PRICE ONLY)

					$admin_markup = sprintf("%.2f", $markup_total_fare ['default_value'] - $net_rate ['default_value']);
					$response['admin_markup'] = $admin_markup;

					// $tax = $GLOBALS ['CI']->transfer_model->get_tax();
					$tax = array();
					if (isset($tax['data']['service_tax'])) {
						$service_tax = $tax['data']['service_tax'];
						$service_amount = (($markup_total_fare['default_value'] + $admin_markup) * $service_tax) / 100;
					} else {
						$service_amount = 0;
					}
				}else{
					// debug($markupData);exit;
					$strAdminMarkup = 0;
					// debug($currency_obj_m);exit;
					$arrTotConvAmt              = $currency_obj->get_currency(1, false, false, false, false, 1);
					$strTotConvAmt      		= $arrTotConvAmt['default_value'];
					$strTotalAmtFor_c		= ($total_amt*$strTotConvAmt);
					if(valid_array($markupData)){
						if($markupData['value_type'] == "plus"){
							$strCurrency_m  = $currency_obj_m->get_currency(1, false, false, false, false, 1);
							$strConAmt      = $strCurrency_m['default_value'];
							//debug($strCurrency_m);exit;
							$strAdminMarkup = $markupData['value']*$strConAmt;
						}else{
							$strMVal = $markupData['value'];
							$strAdminMarkup = ($strTotalAmtFor_c*$strMVal)/100;
						}
					}
					$service_amount 		  = 0;
					$response['admin_markup'] = round($strAdminMarkup,2);					
				}



				if(false){

					$agent_markup = $GLOBALS ['CI']->transfer_model->generic_domain_markup_agent('Transfer');

					if (valid_array($agent_markup)) {
						if (isset($agent_markup[0]['value_type']) && $agent_markup[0]['value_type'] == "percentage") {
							$response['agent_markup'] = (($markup_total_fare['default_value'] / 100) * $agent_markup[0]['value']);
							$total_amout = $markup_total_fare['default_value'] + (($markup_total_fare['default_value'] / 100) * $agent_markup[0]['value']);
						} else {
							$response['agent_markup'] = $agent_markup[0]['value'];
							$total_amout = $markup_total_fare['default_value'] + $agent_markup[0]['value'];
						}
					} else {
						$total_amout = $markup_total_fare['default_value'];
					}
					$AgentServiceTax = ($response['agent_markup'] * $service_tax) / 100;

					$response['AgentServiceTax'] = $AgentServiceTax;
					$response['service_tax'] = $service_amount;
					$response['total_amout'] = round($total_amout + $service_amount + $AgentServiceTax);
					$response['CustomerPayableAmount'] = round($total_amout + $service_amount + $AgentServiceTax);
					$response['AgentNetRate'] = ($total_amout + $service_amount) - ($response['agent_markup']);
					$response['currency'] = $markup_total_fare['default_currency'];

				}else{
					$response['agent_markup'] 	= 0;
					$AgentServiceTax 			= 0;

					$strTotalAmt = $strTotalAmtFor_c + $strAdminMarkup;
					// echo $strTotalAmt."===".$total_amt."-".$strAdminMarkup;exit;
					$response['AgentServiceTax'] 		= $AgentServiceTax;
					$response['service_tax'] 			= $service_amount;
					$response['total_amout'] 			= round($strTotalAmt,2);
					$response['CustomerPayableAmount'] 	= round($strTotalAmt,2);
					$response['AgentNetRate'] 			= ($strTotalAmt + $service_amount) - ($response['agent_markup']);
					$response['currency'] 				= get_application_display_currency_preference();					
					$response['currency_symbol'] 				= $currency_symbol;	
				}

			}

			$response['passengers'] 	= $serviceTransfer['Paxes']['AdultCount'] + $serviceTransfer['Paxes']['ChildCount'];
			$response['service_type'] 	= $serviceTransfer['ProductSpecifications']['MasterServiceType']['@attributes']['name'];
			$response['vehicle_type'] 	= $serviceTransfer['ProductSpecifications']['MasterVehicleType']['@attributes']['name'];
			//debug($response); exit;
			/* image medium size */
			$image = '';
			if (isset($serviceTransfer['TransferInfo']['ImageList']['Image']) && valid_array($serviceTransfer['TransferInfo']['ImageList']['Image'])) {
				foreach ($serviceTransfer['TransferInfo']['ImageList']['Image'] as $iKey => $images) {

					if ($images['Type'] == 'M') {
						$image = str_replace('http:', 'https:', $images['Url']);
					}
				}
			}
			$add_to_service_array['transfer_code'] = $serviceTransfer['TransferInfo']['Code'];
			$add_to_service_array['transfer_type_code'] = $serviceTransfer['TransferInfo']['Type']['@attributes']['code'];
			$add_to_service_array['vehicle_type_code'] = $serviceTransfer['TransferInfo']['VehicleType']['@attributes']['code'];
			$add_to_service_array['adult_count'] = $serviceTransfer['Paxes']['AdultCount'];
			$add_to_service_array['child_count'] = $serviceTransfer['Paxes']['ChildCount'];
			$add_to_service_array['transfer_type'] = $serviceTransfer['@attributes']['transferType'];
			$add_to_service_array['name'] = $response['name'];
			$add_to_service_array['incoming_office_code'] = $serviceTransfer['ContractList']['Contract']['IncomingOffice']['@attributes']['code'];
			$add_to_service_array['from_date'] = $serviceTransfer['DateFrom']['@attributes']['date'];
			$add_to_service_array['from_date_time'] = $serviceTransfer['DateFrom']['@attributes']['time'];
			$add_to_service_array['pickup_location_code'] = $serviceTransfer['PickupLocation']['Code'];
			$add_to_service_array['pickup_location_name'] = $serviceTransfer['PickupLocation']['Name'];
			$add_to_service_array['pickup_location_transfer_zone'] = $serviceTransfer['PickupLocation']['TransferZone']['Code'];

			$add_to_service_array['destin_location_code'] = $serviceTransfer['DestinationLocation']['Code'];
			$add_to_service_array['destin_location_name'] = $serviceTransfer['DestinationLocation']['Name'];
			$add_to_service_array['destin_location_transfer_zone'] = $serviceTransfer['DestinationLocation']['TransferZone']['Code'];



			/* For transfer info */
			if (isset($serviceTransfer['TransferInfo']['DescriptionList']['Description']) && valid_array($serviceTransfer['TransferInfo']['DescriptionList']['Description'])) {
				$description = array();
				foreach ($serviceTransfer['TransferInfo']['DescriptionList']['Description'] as $dKey => $Description) {
					$description[] = $Description;
				}
			}
			$response['description'] = $description;
			$response['image'] = $image;
			$response['product_type'] = $serviceTransfer['ProductSpecifications']['MasterProductType']['@attributes']['name'];
			$response['bullet_point'] = $serviceTransfer['TransferInfo']['TransferSpecificContent']['GenericTransferGuidelinesList']['TransferBulletPoint'][0]['DetailedDescription'];
			/* For guidelines list */
			if (isset($serviceTransfer['TransferInfo']['TransferSpecificContent']['GenericTransferGuidelinesList']['TransferBulletPoint']) && valid_array($serviceTransfer['TransferInfo']['TransferSpecificContent']['GenericTransferGuidelinesList']['TransferBulletPoint'])) {
				$bullet_point = array();
				foreach ($serviceTransfer['TransferInfo']['TransferSpecificContent']['GenericTransferGuidelinesList']['TransferBulletPoint'] as $disKey => $discription) {
					$bullet_point[$disKey]['description'] = $serviceTransfer['TransferInfo']['TransferSpecificContent']['GenericTransferGuidelinesList']['TransferBulletPoint'][$disKey]['Description'];
					$bullet_point[$disKey]['ddescription'] = $serviceTransfer['TransferInfo']['TransferSpecificContent']['GenericTransferGuidelinesList']['TransferBulletPoint'][$disKey]['DetailedDescription'];
				}
			}
			$response['bullet_point'] = $bullet_point;
			$waiting_time_supplier_domestic = '';
			$waiting_timein_supplier_domestic = '';
			$waiting_time_supplier_international = '';
			$waiting_timein_supplier_international = '';
			if (isset($serviceTransfer['TransferInfo']['TransferSpecificContent']['GenericTransferGuidelinesList']['MaximumWaitingTimeSupplierDomestic']) && !empty($serviceTransfer['TransferInfo']['TransferSpecificContent']['GenericTransferGuidelinesList']['MaximumWaitingTimeSupplierDomestic'])) {
				if (isset($serviceTransfer['TransferInfo']['TransferSpecificContent']['GenericTransferGuidelinesList']['MaximumWaitingTimeSupplierDomestic']['@attributes']['time']))
					$waiting_time_supplier_domestic = $serviceTransfer['TransferInfo']['TransferSpecificContent']['GenericTransferGuidelinesList']['MaximumWaitingTimeSupplierDomestic']['@attributes']['time'];
					if (isset($serviceTransfer['TransferInfo']['TransferSpecificContent']['GenericTransferGuidelinesList']['MaximumWaitingTimeSupplierDomestic']))
						$waiting_timein_supplier_domestic = $serviceTransfer['TransferInfo']['TransferSpecificContent']['GenericTransferGuidelinesList']['MaximumWaitingTimeSupplierDomestic'];
			}
			if (isset($serviceTransfer['TransferInfo']['TransferSpecificContent']['GenericTransferGuidelinesList']['MaximumWaitingTimeSupplierInternational']) && !empty($serviceTransfer['TransferInfo']['TransferSpecificContent']['GenericTransferGuidelinesList']['MaximumWaitingTimeSupplierDomestic'])) {
				if (isset($serviceTransfer['TransferInfo']['TransferSpecificContent']['GenericTransferGuidelinesList']['MaximumWaitingTimeSupplierInternational']['@attributes']['time']))
					$waiting_time_supplier_international = $serviceTransfer['TransferInfo']['TransferSpecificContent']['GenericTransferGuidelinesList']['MaximumWaitingTimeSupplierInternational']['@attributes']['time'];
					if (isset($serviceTransfer['TransferInfo']['TransferSpecificContent']['GenericTransferGuidelinesList']['MaximumWaitingTimeSupplierInternational']))
						$waiting_timein_supplier_international = $serviceTransfer['TransferInfo']['TransferSpecificContent']['GenericTransferGuidelinesList']['MaximumWaitingTimeSupplierInternational'];
			}
			/* For luggage info */
			if (isset($serviceTransfer['ProductSpecifications']['TransferGeneralInfoList']['TransferBulletPoint']) && valid_array($serviceTransfer['ProductSpecifications']['TransferGeneralInfoList']['TransferBulletPoint'])) {
				$luggage = array();
				foreach ($serviceTransfer['ProductSpecifications']['TransferGeneralInfoList']['TransferBulletPoint'] as $luKey => $ludiscription) {
					$luggage[$luKey]['description'] = $serviceTransfer['ProductSpecifications']['TransferGeneralInfoList']['TransferBulletPoint'][$luKey]['Description'];
				}
			}


			$response['waiting_time_supplier_domestic'] = $waiting_time_supplier_domestic;
			$response['waiting_timein_supplier_domestic'] = $waiting_timein_supplier_domestic;
			$response['waiting_time_supplier_international'] = $waiting_time_supplier_international;
			$response['waiting_timein_supplier_international'] = $waiting_timein_supplier_international;

			$response['add_to_service_array'] = $add_to_service_array;
			$response['luggage'] = $luggage;
			$response['pickup_information'] = $serviceTransfer['TransferPickupInformation']['Description'];
			$response['specific_transfer_info'] = @$serviceTransfer['TransferInfo']['TransferSpecificContent']['SpecificTransferInfoList']['TransferBulletPoint']['Description'];
			//$response['specific_transfer_info'] = @$serviceTransfer['TransferInfo']['TransferSpecificContent']['SpecificTransferInfoList']['TransferBulletPoint']['Description'];
			
			return $response;
		}

		/**
		 * Makrup for search result
		 * @param array $price_summary
		 * @param object $currency_obj
		 * @param boolean $level_one_markup
		 * @param boolean $current_domain_markup
		 * @param number $search_id
		 */
		function update_search_markup_currency(& $price_summary, & $currency_obj, $search_id, $level_one_markup = false, $current_domain_markup = true) {
			$search_data = $this->search_data($search_id);
			return $this->update_markup_currency($price_summary, $currency_obj, $multiplier = 1, $level_one_markup, $current_domain_markup);
		}

		/* format array and form combinations in case of round trip
		 * */

		function sortByOrder($a, $b) {
			return $a['total_amout'] - $b['total_amout'];
		}

		public function formate_list_data($transfer_data_list, $search_id = '', $module,$currency_obj) { 
			 #debug($transfer_data_list);exit('transfer_data_list');
			//exit;
			$response = array();
			$add_to_service_array = array();
			$in_key = 0;
			$out_key = 0;
			$hc = 0;
			$frc = 0;
			// debug($transfer_data_list);exit();
			if (isset($transfer_data_list['waiting_time']) && valid_array($transfer_data_list['waiting_time'])) {
				$waiting_time = $transfer_data_list['waiting_time'];
			}
			$ServiceTransfer_data = force_multple_data_format($transfer_data_list['ServiceTransfer']);
			$markupData    = $GLOBALS ['CI']->transfer_model->getMarkupData('Transfer',$module);
			$strCurrency_M = get_application_display_currency_preference();
			if(valid_array($markupData)){
				$strCurrency_M = $markupData[0]['markup_currency'];
			}
			$currency_obj_m = new Currency(array('module_type' => 'transfer', 'from' => $strCurrency_M, 'to' => get_application_display_currency_preference()));
			// debug($markupData);exit;
			if (valid_array($ServiceTransfer_data)) {
				foreach ($ServiceTransfer_data as $key => $serviceTransfer) {
					switch ($serviceTransfer['@attributes']['transferType']) {
						case 'IN' : $response['in'][$in_key] = $this->built_formatted_array($serviceTransfer, $search_id, $module, $waiting_time[$key],$currency_obj,$currency_obj_m, $markupData);
							$in_key++;
						break;
						case 'OUT': $response['out'][$out_key] = $this->built_formatted_array($serviceTransfer, $search_id, $module,$waiting_time[$key],$currency_obj,$currency_obj_m, $markupData);
							$out_key++;
						break;
						default: break;
					}
					$hc++;
					$frc++;
				}
			}
			
			
			/* order by total amount */
			if (isset($response['in']) && !empty($response['in'])) {
				usort($response['in'], 'sortByOrder');
			}
			if (isset($response['out']) && !empty($response['out'])) {
				usort($response['out'], 'sortByOrder');
			}

			/* form combinations for round trip */
			if ((isset($response['in']) && !empty($response['in'])) && (isset($response['out']) && !empty($response['out']))) {
				$first_val = key($response);
				if ($first_val == 'in')
					$transfer_combination = $this->form_transfer_combination($response['in'], $response['out']);
					else
						$transfer_combination = $this->form_transfer_combination($response['out'], $response['in']);
			}
			else if ((isset($response['in']) && !empty($response['in'])) && (!isset($response['out']) && empty($response['out']))) {
				$transfer_combination = $this->form_transfer_in($response['in']);
			} else {
				$transfer_combination = $this->form_transfer_in($response['out']);
			}

			$data['data'] = $transfer_combination;
			$data ['source_result_count'] = $hc;
			$data ['filter_result_count'] = $frc;
			return $data;
		}

		/*
		 * form combinations for transfer
		 * */

		function form_transfer_combination($onward, $outward) {
			$combination_array = array();
			for ($i = 0; $i < count($onward); $i++) {
				for ($j = 0; $j < count($outward); $j++) {
					$merge_array = array();
					$merge_array['details'][] = $onward[$i];
					$merge_array['details'][] = $outward[$j];
					$merge_array['total_amount'] = $onward[$i]['total_amout'] + $outward[$j]['total_amout'];
					$combination_array[] = $merge_array;
				}
			}
			return $combination_array;
		}

		/*
		 * for in transfer(oneway)
		 * */

		function form_transfer_in($onward) {
			$in_array_value = array();
			for ($i = 0; $i < count($onward); $i++) {
				$single_array = array();
				$single_array['details'][] = $onward[$i];
				$single_array['total_amount'] = $onward[$i]['total_amout'];
				$in_array_value[] = $single_array;
			}
			return $in_array_value;
		}

		/**
		 * get transfer details
		 */
		function add_service_to_Cart($search_params, $transfer_params) {
			$response ['data'] = array();
			$transfer_details_response_val = array();
			$status = true;
			$search_id = $search_params['search_id'];
			$transfer_details_request_val = $this->transfer_details_request($search_params, $transfer_params);
			// debug($response);exit;

			if ($transfer_details_request_val['status'] == SUCCESS_STATUS) {

				foreach ($transfer_details_request_val['request'] as $service_k => $service_d) {
					/*   $path = FCPATH . "all_log_files/ServiceAddRQ_" . $service_k . "_" . date('Y_m_d_H_i_s') . ".xml";
					 $fp = fopen($path, "wb");
					 fwrite($fp, $service_d);
					 fclose($fp); */
				}

				$transfer_details_request = json_encode($transfer_details_request_val['request']);
				$this->CI->custom_db->generate_static_response($transfer_details_request, 'transfer add_service_to_Cart request', 'hotelbeds_transfer');
			}
			if ($transfer_details_request_val['status']) {
				$url = $this->service_url;

                $strIsb2c = $this->is_b2c();
                $strFpath = "";
                if($strIsb2c == 0){
                    $strFpath = "../";
                }

                if(true){
	                $i=0;
	                $strTime = time();
					foreach ($transfer_details_request_val['request'] as $tdra => $add_to_cart_request) {
						if(true){
							$fileName = $search_id."_".$i."_".$strTime;
			                $path = $strFpath."all_xml_logs/transfer/hb/details/DetailsRQ_".$fileName.".xml";
			                // debug($add_to_cart_request); 
			                $fp = fopen($path,"wb");fwrite($fp,$add_to_cart_request);fclose($fp);


							$transfer_details_response_val[] = $strRespData = $GLOBALS ['CI']->api_interface->xml_post_request($url, $add_to_cart_request, $this->xml_header());
							

			                $path = $strFpath."all_xml_logs/transfer/hb/details/DetailsRS_".$fileName.".xml";
			                $fp = fopen($path,"wb");fwrite($fp,$strRespData);fclose($fp);
			                // debug($strRespData);exit;
							$i++;
						}
					}
				}else{
					// debug(1);exit;
					//error_reporting(E_ALL);
					// echo $strFpath."all_xml_logs/transfer/hb/details/DetailsRS_DetailsRS_335_0_1516001182.xml";exit;
					$transfer_details_response_val[] = file_get_contents($strFpath."all_xml_logs/transfer/hb/details/DetailsRS_344_0_1516075840.xml");
					//$transfer_details_response_val[] = file_get_contents($strFpath."all_xml_logs/transfer/hb/details/DetailsRS_335_1_1516001182.xml");
				}
				// debug($transfer_details_response_val);exit;
				if (isset($transfer_details_response_val) && !empty($transfer_details_response_val)) {
					$response['data'] = $transfer_details_response_val;

					foreach ($response['data'] as $service_rs_k => $service_rs_d) {
						/*   $path = FCPATH . "all_log_files/ServiceAddRS_" . $service_rs_k . "_" . date('Y_m_d_H_i_s') . ".xml";
						 $fp = fopen($path, "wb");
						 fwrite($fp, $service_rs_d);
						 fclose($fp); */
					}

					$transfer_details_response = json_encode($response['data']);

					$this->CI->custom_db->generate_static_response($transfer_details_response, 'transfer add_service_to_Cart response', 'hotelbeds_transfer');
					// debug($aaa);exit;
				} else {
					$status = false;
				}
			} else {
				$status = false;
			}

			$response['status'] = $status;
			return $response;
		}

		/*
		 * create request for transfer service add
		 * */

		function transfer_details_request($search_params, $transfer_params) {
			// debug($this->username); exit;

			$status = true;
			$response = array();
			$request_array = array();

			if (isset($transfer_params['transfer_code']) && valid_array($transfer_params['transfer_code'])) {
				foreach ($transfer_params['transfer_code'] as $tc_key => $trnfer_code) {
					$in_array[$tc_key]['transfer_code'] = $trnfer_code;
				}
			}

			if (isset($transfer_params['transfer_type_code']) && valid_array($transfer_params['transfer_type_code'])) {
				foreach ($transfer_params['transfer_type_code'] as $ttc_key => $trnfer_type_code) {
					$in_array[$ttc_key]['transfer_type_code'] = $trnfer_type_code;
				}
			}

			if (isset($transfer_params['vehicle_type_code']) && valid_array($transfer_params['vehicle_type_code'])) {
				foreach ($transfer_params['vehicle_type_code'] as $vtc_key => $vehicle_type_code) {
					$in_array[$vtc_key]['vehicle_type_code'] = $vehicle_type_code;
				}
			}

			if (isset($transfer_params['adult_count']) && valid_array($transfer_params['adult_count'])) {
				foreach ($transfer_params['adult_count'] as $ac_key => $adult_count) {
					$in_array[$ac_key]['adult_count'] = $adult_count;
				}
			}

			if (isset($transfer_params['child_count']) && valid_array($transfer_params['child_count'])) {
				foreach ($transfer_params['child_count'] as $cc_key => $child_count) {
					$in_array[$cc_key]['child_count'] = $child_count;
				}
			}

			if (isset($transfer_params['transfer_type']) && valid_array($transfer_params['transfer_type'])) {
				foreach ($transfer_params['transfer_type'] as $tt_key => $transfer_type) {
					$in_array[$tt_key]['transfer_type'] = $transfer_type;

					// debug($transfer_type);
					/*if (($transfer_type == 'IN' && $tt_key == 0) || ($transfer_type == 'OUT' && $tt_key == 0))
				   {
						$in_array[$tt_key]['from_transfer_type'] = $search_params['from_transfer_type'];
						$in_array[$tt_key]['to_transfer_type'] = $search_params['to_transfer_type'];
					} else {
						$in_array[$tt_key]['from_transfer_type'] = $search_params['to_transfer_type'];
						$in_array[$tt_key]['to_transfer_type'] = $search_params['from_transfer_type'];
					}*/
						
					$in_array[$tt_key]['from_transfer_type'] = $search_params['from_transfer_type'];
					$in_array[$tt_key]['to_transfer_type'] = $search_params['to_transfer_type'];

					// debug($in_array[$tt_key]['from_transfer_type']);
				}
			}

			if (isset($transfer_params['name']) && valid_array($transfer_params['name'])) {
				foreach ($transfer_params['name'] as $n_key => $name) {
					$in_array[$n_key]['name'] = $name;
				}
			}

			if (isset($transfer_params['incoming_office_code']) && valid_array($transfer_params['incoming_office_code'])) {
				foreach ($transfer_params['incoming_office_code'] as $ioc_key => $incoming_office_code) {
					$in_array[$ioc_key]['incoming_office_code'] = $incoming_office_code;
				}
			}

			if (isset($transfer_params['from_date']) && valid_array($transfer_params['from_date'])) {
				foreach ($transfer_params['from_date'] as $fd_key => $from_date) {
					$in_array[$fd_key]['from_date'] = $from_date;
				}
			}

			if (isset($transfer_params['from_date_time']) && valid_array($transfer_params['from_date_time'])) {
				foreach ($transfer_params['from_date_time'] as $fdt_key => $from_date_time) {
					$in_array[$fdt_key]['from_date_time'] = $from_date_time;
				}
			}

			if (isset($transfer_params['pickup_location_code']) && valid_array($transfer_params['pickup_location_code'])) {
				foreach ($transfer_params['pickup_location_code'] as $plc_key => $pickup_location_code) {
					$in_array[$plc_key]['pickup_location_code'] = $pickup_location_code;
				}
			}

			if (isset($transfer_params['pickup_location_name']) && valid_array($transfer_params['pickup_location_name'])) {
				foreach ($transfer_params['pickup_location_name'] as $pln_key => $pickup_location_name) {
					$in_array[$pln_key]['pickup_location_name'] = $pickup_location_name;
				}
			}

			if (isset($transfer_params['pickup_location_transfer_zone']) && valid_array($transfer_params['pickup_location_transfer_zone'])) {
				foreach ($transfer_params['pickup_location_transfer_zone'] as $pltz_key => $pickup_location_transfer_zone) {
					$in_array[$pltz_key]['pickup_location_transfer_zone'] = $pickup_location_transfer_zone;
				}
			}

			if (isset($transfer_params['destin_location_code']) && valid_array($transfer_params['destin_location_code'])) {
				foreach ($transfer_params['destin_location_code'] as $dlc_key => $destin_location_code) {
					$in_array[$dlc_key]['destin_location_code'] = $destin_location_code;
				}
			}

			if (isset($transfer_params['destin_location_name']) && valid_array($transfer_params['destin_location_name'])) {
				foreach ($transfer_params['destin_location_name'] as $dln_key => $destin_location_name) {
					$in_array[$dln_key]['destin_location_name'] = $destin_location_name;
				}
			}

			if (isset($transfer_params['destin_location_transfer_zone']) && valid_array($transfer_params['destin_location_transfer_zone'])) {
				foreach ($transfer_params['destin_location_transfer_zone'] as $dltz_key => $destin_location_transfer_zone) {
					$in_array[$dltz_key]['destin_location_transfer_zone'] = $destin_location_transfer_zone;
				}
			}

			$avail_token = $transfer_params['avai_token'];
			if (isset($in_array) && valid_array($in_array)) {
				foreach ($in_array as $in_key => $service) {
					// debug($service);exit;
					$request = '';
					$request = '<?xml version="1.0" encoding="UTF-8"?>';
					$request .= '<ServiceAddRQ xmlns="http://api.interface-xml.com/schemas/2005/06/messages" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://api.interface-xml.com/schemas/2005/06/messages ServiceAddRQ.xsd" version="2013/12">
            <Language>ENG</Language>
            <Credentials>
                <User>' . $this->username . '</User>
                <Password>' . $this->password . '</Password>
            </Credentials>';
					$request .= '<Service xsi:type="ServiceTransfer" transferType="' . $service['transfer_type'] . '" availToken="' . $avail_token . '">
                    <ContractList>
                        <Contract>
                            <Name>' . $service['name'] . '</Name>
                            <IncomingOffice code="' . $service['incoming_office_code'] . '"/>
                        </Contract>
                    </ContractList>
                    <DateFrom date="' . $service['from_date'] . '" time="' . $service['from_date_time'] . '"/>
                    <TransferInfo xsi:type="ProductTransfer">
                        <Code>' . $service['transfer_code'] . '</Code>
                        <Type code="' . $service['transfer_type_code'] . '"/>
                        <VehicleType code="' . $service['vehicle_type_code'] . '"/>
                    </TransferInfo>
                    <Paxes>
                        <AdultCount>' . $service['adult_count'] . '</AdultCount>
                        <ChildCount>' . $service['child_count'] . '</ChildCount>
                        <GuestList>';
					if (isset($search_params['adult_ages']) && valid_array($search_params['adult_ages'])) {
						foreach ($search_params['adult_ages'] as $a_key => $adult_age) {
							$request.= '<Customer type="AD">
                                    <Age>' . $adult_age . '</Age>
                                </Customer>';
						}
					}
					if (isset($search_params['child_ages']) && valid_array($search_params['child_ages'])) {
						foreach ($search_params['child_ages'] as $c_key => $child_age) {
							$request.= '<Customer type="CH">
                                    <Age>' . $child_age . '</Age>
                                </Customer>';
						}
					}

					// debug( $service['from_transfer_type'] );
					$request.= '</GuestList>
                    </Paxes>
                    <PickupLocation xsi:type="' . $service['from_transfer_type'] . '">
                        <Code>' . $service['pickup_location_code'] . '</Code>
                        <Name>' . $service['pickup_location_name'] . '</Name>
                        <TransferZone xsi:type="ProductZone">
                            <Code>' . $service['pickup_location_transfer_zone'] . '</Code>
                        </TransferZone>
                    </PickupLocation>
                    <DestinationLocation xsi:type="' . $service['to_transfer_type'] . '">
                        <Code>' . $service['destin_location_code'] . '</Code>
                        <Name>' . $service['destin_location_name'] . '</Name>
                        <TransferZone xsi:type="ProductZone">
                            <Code>' . $service['destin_location_transfer_zone'] . '</Code>
                        </TransferZone>
                    </DestinationLocation>
                    </Service>';
					$request .= '</ServiceAddRQ>';

					//header("Content-type: text/xml");
					// debug($request); exit();

					$request_array[] = $request;
				}

			}
// debug($request_array); exit();
			$response['request'] = $request_array;
			$response['status'] = true;

			return $response;
		}

		/*
		 * Formate service add request for transfer
		 * */

		public function formate_service_add_data($transfer_list, $transfer_params, $module) {
			 // debug( $transfer_params);exit;
			// error_reporting(E_ALL);
			$adult_age = '';
			$search_id = $transfer_params['search_id'];
			$service_response['search_id'] = $transfer_params['search_id'];
			$service_response['booking_source'] = $transfer_params['booking_source'];
			$total_amount = 0;
			$currency_code = '';
			$adult_count = '';
			$child_count = '';
			$adult_age_list = '';
			$child_age_list = '';
			$convinence = 0;
			$customer_payable_rate = 0;
			$agent_markup_val = 0;
			$admin_markup_val = 0;
			$sub_amount = 0;
			$XMlPriceWithAdminMarkup = 0;
			$XMLRate = 0;
			$service_amount_All = 0;
			$agent_markup_val_can = 0;
			$service_amount_Can = 0;
			$cancel_markup_value = 0;
			$TotalAmountWithMarkupCan = 0;
			$TotalCanPrice = 0;
			$AgentServiceTax = 0;
			$convenience_fee=0;


			$arrConvAmt='';
			$strConvcur='';
			//debug($transfer_list);exit;
			$safe_search_data = $GLOBALS['CI']->transfer_model->get_safe_search_data($transfer_params['search_id']);
			$country_city_data = $GLOBALS['CI']->transfer_model->get_country_city($safe_search_data['data']['from_code']);
			$cntry_code = $country_city_data[0]['country_code'];
			$city_id = $country_city_data[0]['city_id'];
			// debug($cntry_code);debug($city_id);exit;
			if (isset($transfer_list) && valid_array($transfer_list)) {
				$strCurrency_data = "";
				foreach ($transfer_list as $t_key => $transfer) {

					$total_amount_per_car = 0;
					$customer_payable_rate_per_car = 0;
					if (isset($transfer['Purchase']) && valid_array($transfer['Purchase'])) {

						$adult_count = @$transfer['Purchase']['ServiceList']['Service']['Paxes']['AdultCount'];
						$child_count = @$transfer['Purchase']['ServiceList']['Service']['Paxes']['ChildCount'];

						$service_response['purchase_details'][$t_key]['purchase_token'] = $transfer['Purchase']['@attributes']['purchaseToken'];
						$service_response['purchase_details'][$t_key]['time_to_expiration'] = $transfer['Purchase']['@attributes']['timeToExpiration'];
						$service_response['purchase_details'][$t_key]['agency_code'] = $transfer['Purchase']['Agency']['Code'];
						$service_response['purchase_details'][$t_key]['creation_user'] = $transfer['Purchase']['CreationUser'];
						$service_response['purchase_details'][$t_key]['transfer_type'] = @$transfer['Purchase']['ServiceList']['Service']['@attributes']['transferType'];
						$service_response['purchase_details'][$t_key]['SPUI'] = @$transfer['Purchase']['ServiceList']['Service']['@attributes']['SPUI'];
						$service_response['purchase_details'][$t_key]['currency'] = @$transfer['Purchase']['ServiceList']['Service']['Currency'];
						//  $service_response['purchase_details'][$t_key]['total_amount'] = @$transfer['Purchase']['ServiceList']['Service']['TotalAmount'];
						//debug($transfer['Purchase']['ServiceList']['Service']['TotalAmount']);
						// debug($transfer);exit;
						if($strCurrency_data == ""){
							$strCurrency_data = $transfer['Purchase']['Currency']['@attributes']['code'];
							// debug($strCurrency_data);exit;
							$currency_obj = new Currency(array('module_type' => 'transfers', 'from' => $transfer['Purchase']['Currency']['@attributes']['code'], 'to' => get_application_default_currency()));
						}



						$arrConvAmt   = $currency_obj->get_transfer_currency(1, true, false, true, 1);
						$strConvcur   = $arrConvAmt['default_currency'];
						// debug($arrConvAmt);exit;
						$currency_symbol = $currency_obj->get_currency_symbol($strConvcur);
						 // debug($transfer);exit;
						// debug(@$transfer['Purchase']['ServiceList']['Service']['SellingPrice']);exit;
						$cancel_cur_obj = clone $currency_obj;
						// $ded_total_fare = $cancel_cur_obj->get_transfer_currency(@$transfer['Purchase']['ServiceList']['Service']['SellingPrice'], true, false, true, 1);
						$product_id = '';
						$supplier = 'Beds Online';
						// debug($hd);exit;

						$search_level_markup_val = $safe_search_data['data']['markup_value'];
						if($search_level_markup_val!='' || $search_level_markup_val!=0){
						$search_level_markup_typ = $safe_search_data['data']['markup_type'];
						if($search_level_markup_typ=='plus'){
							$search_level_markup = $search_level_markup_val;
						}else{
							$search_level_markup = ($transfer['Purchase']['ServiceList']['Service']['SellingPrice']/100) * $search_level_markup_val;
						}
						}else{
							$search_level_markup = 0;
						}
						$admin_markup_details = $GLOBALS ['CI']->transfer_model->get_markup_for_admin ( $transfer['Purchase']['ServiceList']['Service']['SellingPrice'], $supplier, $cntry_code, $city_id, $product_id);
						$agent_markup = $GLOBALS ['CI']->transfer_model->get_markup_for_agent ( $transfer['Purchase']['ServiceList']['Service']['SellingPrice'], $cntry_code, $city_id); 
						$total_markup_admin_agent = $admin_markup_details+$agent_markup+$search_level_markup;
						$gst_percentage = $GLOBALS['CI']->transfer_model->get_admin_gst();
						// debug($gst_percentage);exit;
						$gst_amount = ($gst_percentage[0]['gst']*$total_markup_admin_agent)/100;
						// Changed by chalapathi [0] is added to selling price
						$ded_total_fare = $cancel_cur_obj->get_transfer_currency(@$transfer['Purchase']['ServiceList']['Service']['SellingPrice'], true, false, true, 1);

						$ded_total_fare['default_value']=$ded_total_fare['default_value']+$agent_markup;
						// debug(@$transfer['Purchase']['ServiceList']['Service']['SellingPrice'][0]);exit;
						// debug($ded_total_fare);exit;
						// $markupData    = $GLOBALS ['CI']->transfer_model->getMarkupData('b2b_transferv1',$module);
						// debug($markupData);exit;

				
				// $this->get_commission($hd['Price'],$cobj);
				$markupData = $GLOBALS ['CI']->transfer_model->admin_markup ( $transfer['Purchase']['ServiceList']['Service']['SellingPrice'], $supplier, $cntry_code, $city_id, $product_id,'Api');
				if($markupData[0]==''){
					$markupData[0] = $markupData;
				}
				// $agent_markup_details = $GLOBALS ['CI']->transfer_model->get_markup_for_agent ( $hd ['Price']['TotalDisplayFare'], $cntry_code, $city_id);

						// debug($markupData);exit;
						// $markupData1    = $GLOBALS ['CI']->domain_management_model->generic_domain_markup('b2b_transferv1');
						// debug($markupData1);exit;
						$strCurrency_M = get_application_display_currency_preference();
						$strAdminMarkup = 0;
						$strTotalAmtFor_c = $ded_total_fare['default_value'];
						if(valid_array($markupData)){
							$strCurrency_M 	= $markupData[0]['markup_currency'];
							
							// $currency_obj_m = new Currency(array('module_type' => 'car', 'from' => $strCurrency_M, 'to' => get_application_display_currency_preference()));
							
							$currency_obj_m = new Currency(array('module_type' => 'transfers', 'from' => $strCurrency_M, 'to' => get_application_display_currency_preference()));
							$strCurrency_m  = $currency_obj_m->get_currency(1, false, false, false, false, 1);
							// debug($strTotalAmtFor_c);exit;
							$strMarkupCurrency = $strCurrency_m['default_currency'];
							if($markupData[0]['value_type'] == "plus"){
								$strConAmt      = $strCurrency_m['default_value'];
								// debug($strTotalAmtFor_c);exit;
								$strAdminMarkup = $markupData[0]['value'];
							}else{
								$strMVal = $markupData[0]['value'];
								$strAdminMarkup = ($strTotalAmtFor_c*$strMVal)/100;
							}
						}


						// echo $strAdminMarkup;exit;

						$service_response['purchase_details'][$t_key]['selling_price'] = $ded_total_fare['default_value'];
						// debug($service_response['purchase_details'][$t_key]['selling_price']);
						// debug($cancel_cur_obj);
						// exit;
						$markup_total_fare = $cancel_cur_obj->get_currency(@$transfer['Purchase']['ServiceList']['Service']['SellingPrice'], true, false, true, false, 1);
						// debug($ded_total_fare);exit;
						//$XMlPriceWithAdminMarkup = $markup_total_fare['default_value'];
						//$ConvertedCurrency 		 = $markup_total_fare['default_currency'];
						$XMlPriceWithAdminMarkup = $strTotalAmtFor_c+$strAdminMarkup;
						$ConvertedCurrency 		 = $strMarkupCurrency;

						$XMLRate+=$ded_total_fare['default_value'];
						// debug($XMlPriceWithAdminMarkup);exit;
						// $admin_markup_val+= $markup_total_fare['default_value'] - $ded_total_fare['default_value'];
						$admin_markup_val+= $strAdminMarkup;
						  // debug($strAdminMarkup);exit;
						if ($module == 'b2b') {
							if(false){
								// debug($agent_markup);exit();
								$agent_markup = $GLOBALS ['CI']->transfer_model->agent_markup ( $transfer['Purchase']['ServiceList']['Service']['SellingPrice'], $cntry_code, $city_id, 'Api');
								// debug($agent_markup);exit;
								#Calcualting Admin Serive Tax

								$tax = $GLOBALS ['CI']->transfer_model->get_tax();
								if (isset($tax['data']['service_tax'])) {
									$service_tax = $tax['data']['service_tax'];

									$service_amount_All+= (($ded_total_fare['default_value'] + ($markup_total_fare['default_value'] - $ded_total_fare['default_value'])) * $service_tax) / 100;
								} else {
									$service_amount_All = 0;
								}
								// debug($service_amount_All);exit;
								if (valid_array($agent_markup)) {
									if (isset($agent_markup[0]['value_type']) && $agent_markup[0]['value_type'] == "percentage") {
										$agent_markup_val += ($XMlPriceWithAdminMarkup / 100) * $agent_markup[0]['value'];
										$AgentMarkupIndividual = ($XMlPriceWithAdminMarkup / 100) * $agent_markup[0]['value'];
										$TotalAmountWithMarkup = $XMlPriceWithAdminMarkup + (($XMlPriceWithAdminMarkup / 100) * $agent_markup[0]['value']);
									} else {
										$agent_markup_val += $agent_markup[0]['value'];
										$AgentMarkupIndividual = $agent_markup[0]['value'];
										$TotalAmountWithMarkup = $XMlPriceWithAdminMarkup + $agent_markup[0]['value'];
									}
								} else {
									$TotalAmountWithMarkup = $XMlPriceWithAdminMarkup;
								}

								# Calcualting Agent Serive tax

								$AgentServiceTax+= ($AgentMarkupIndividual * $service_tax) / 100;
								// debug($AgentServiceTax);debug($service_amount_All);debug($agent_markup_val);debug($XMlPriceWithAdminMarkup);debug($TotalAmountWithMarkup);exit;
							}else{


							// debug("2");exit;
								$service_amount_All = 0;
								$agent_markup_val  += 0;
								$AgentMarkupIndividual = 0;
								$TotalAmountWithMarkup = $XMlPriceWithAdminMarkup;
							}
							// debug($total_amount);exit;
							// debug($TotalAmountWithMarkup);

							$total_amount += ($TotalAmountWithMarkup);// original

							$total_amount = ($TotalAmountWithMarkup);
							// $total_amount_per_car = @$markup['value'] + $agent_markup_val;
							// debug($XMlPriceWithAdminMarkup);exit;
							$total_amount_per_car = $XMlPriceWithAdminMarkup;
						} else {
							$agent_markup_val = 0;
							if(false){
								$total_amount_per_car = @$markup['value'];
								$tax = $GLOBALS ['CI']->transfer_model->get_tax();

								if (isset($tax['data']['service_tax'])) {
									$service_tax = $tax['data']['service_tax'];
									$service_amount_All+= ($markup_total_fare['default_value'] * $service_tax) / 100;
								} else {
									$service_amount_All = 0;
								}
							}else{
								$service_amount_All = 0;
							}


							$customer_payable_rate+=$XMlPriceWithAdminMarkup;
							$total_amount += $XMlPriceWithAdminMarkup;
						}
							 // debug($total_amount); exit;
						$customer_payable_rate_per_car = $total_amount;
						$currency_code = $strMarkupCurrency;
						$service_response['purchase_details'][$t_key]['customer_payable_rate'] = $customer_payable_rate_per_car;
						$service_response['purchase_details'][$t_key]['total_amount'] = $customer_payable_rate_per_car;
						$sub_amount+=$customer_payable_rate_per_car;
						$service_response['purchase_details'][$t_key]['currency'] = $strConvcur;
						$service_response['purchase_details'][$t_key]['currency_symbol'] = $currency_symbol;
						//debug($transfer['Purchase']['ServiceList']['Service']); exit;
						//= @$transfer['Purchase']['Currency']['@attributes']['code'];
						//$cancel_currency_code = @$transfer['Purchase']['Currency']['@attributes']['code'];
						$cancel_val = @$transfer['Purchase']['ServiceList']['Service']['CancellationPolicies'];

						if (isset($cancel_val) && !empty($cancel_val)) {
							foreach ($cancel_val as $cancel_val_k => $cancel_val_v) {

								// $currency_cancel_obj = new Currency(array('module_type' => 'transfer', 'from' => $transfer['Purchase']['Currency']['@attributes']['code'], 'to' => get_application_default_currency()));
								$currency_cancel_obj 	= $currency_obj;
								$ded_cancel_obj 		= clone $currency_cancel_obj;
								$cancel_abount 			= $cancel_val_v['@attributes']['amount'];
								$dateFrom 				= $cancel_val_v['@attributes']['dateFrom'];
								$time = $cancel_val_v['@attributes']['time'];
								$TotalCanPrice = 0;
								$ded_total_fare_can = $ded_cancel_obj->get_currency(@$cancel_abount, false, false, false, false, 1);

								$markup_total_fare_can = $currency_cancel_obj->get_currency(@$cancel_abount, true, false, true, false, 1);
								$TotalCanPrice = $markup_total_fare_can['default_value'] + $strAdminMarkup;

								if ($module == 'b2c') {
									/* $converted_display_price = $cancel_cur_obj->force_currency_conversion($cancel_abount);
									 $currency_obj->getConversionRate(false, get_application_default_currency(), get_application_display_currency_preference());
									 $cancel_markup = $this->update_search_markup_currency($converted_display_price['default_value'], $currency_obj, false, true, $transfer_params['search_id']); */



								} else {
									/* $converted_display_price = $cancel_cur_obj->force_currency_conversion($cancel_abount);
									 $currency_obj->getConversionRate(false, get_application_default_currency(), get_application_display_currency_preference());
									 $cancel_markup = $this->update_search_markup_currency($converted_display_price['default_value'], $currency_obj, false, true, $transfer_params['search_id']);
									 $convinence = 0; */
									 // debug($agent_markup);exit;
									if (valid_array($agent_markup)) {
										if (isset($agent_markup[0]['value_type']) && $agent_markup[0]['value_type'] == "percentage") {
											$agent_markup_val_can = ($TotalCanPrice / 100) * $agent_markup[0]['value'];
											$TotalAmountWithMarkupCan = $agent_markup_val_can;
										} else {
											$agent_markup_val_can = $agent_markup[0]['value'];
											$TotalAmountWithMarkupCan = $agent_markup_val_can;
										}
									}

									$TotalCanPrice+=$TotalAmountWithMarkupCan;
								}

								if (isset($tax['data']['service_tax'])) {

									$service_tax_can = $tax['data']['service_tax'];
									$service_amount_Can = ($TotalCanPrice * $service_tax_can) / 100;

									$TotalCanPrice+=$service_amount_Can;
								} else {
									$service_amount_Can = 0;
									$TotalCanPrice+=$service_amount_Can;
								}

								$cancel_markup_value = $TotalCanPrice;
								#debug($currency_obj); exit;
								$convinence = $currency_obj->convenience_fees($TotalCanPrice,$search_id);
								$convinence_fee = ceil($convinence);
								$time = date('H:i', strtotime($time));
								$dateFrom = date('Y-m-d', strtotime($dateFrom));
								$service_response['purchase_details'][$t_key]['cancellation_policies'][$cancel_val_k]['@attributes']['amount'] = @$cancel_markup_value;
								$service_response['purchase_details'][$t_key]['cancellation_policies'][$cancel_val_k]['@attributes']['CancelAmount'] = @$cancel_abount;
								$service_response['purchase_details'][$t_key]['cancellation_policies'][$cancel_val_k]['@attributes']['CancelXMLCurrency'] = @$transfer['Purchase']['Currency']['@attributes']['code'];
								$service_response['purchase_details'][$t_key]['cancellation_policies'][$cancel_val_k]['@attributes']['dateFrom'] = $dateFrom;
								$service_response['purchase_details'][$t_key]['cancellation_policies'][$cancel_val_k]['@attributes']['time'] = $time;
								$service_response['purchase_details'][$t_key]['cancellation_policies'][$cancel_val_k]['@attributes']['cancel_currency_code'] = $ded_total_fare_can['default_currency'];
								$service_response['purchase_details'][$t_key]['cancellation_policies'][$cancel_val_k]['@attributes']['msg'] = "If cancellation done on or after " . $dateFrom . " " . $time . " , amount " . $ded_total_fare_can['default_currency'] . " " . @ceil($cancel_markup_value + $convinence_fee) . " will be charged.";

							}
						} else {
							$service_response['purchase_details'][$t_key]['cancellation_policies']['CancellationPolicy']['@attributes']['amount'] = 0;
							$service_response['purchase_details'][$t_key]['cancellation_policies']['CancellationPolicy']['@attributes']['CancelAmount'] = 0;
							$service_response['purchase_details'][$t_key]['cancellation_policies']['CancellationPolicy']['@attributes']['CancelXMLCurrency'] = '';
							$service_response['purchase_details'][$t_key]['cancellation_policies']['CancellationPolicy']['@attributes']['dateFrom'] = '';
							$service_response['purchase_details'][$t_key]['cancellation_policies']['CancellationPolicy']['@attributes']['time'] = '';
							$service_response['purchase_details'][$t_key]['cancellation_policies']['CancellationPolicy']['@attributes']['cancel_currency_code'] = "QAR";

							$service_response['purchase_details'][$t_key]['cancellation_policies']['CancellationPolicy']['@attributes']['msg'] = "Non-Refundable.";
						}
						//if($_SERVER['REMOTE_ADDR']=="192.168.0.40"){
						//  debug($service_response['purchase_details']);
						//exit;
						// }
						// debug($service_response['purchase_details'][$t_key]['cancellation_policies']);
						//debug($service_response['purchase_details'][$t_key]['cancellation_policies'][$cancel_val_k]['@attributes']);
						/* image medium size */
						$image = '';
						if (isset($transfer['Purchase']['ServiceList']['Service']['TransferInfo']['ImageList']['Image']) && valid_array($transfer['Purchase']['ServiceList']['Service']['TransferInfo']['ImageList']['Image'])) {
							foreach ($transfer['Purchase']['ServiceList']['Service']['TransferInfo']['ImageList']['Image'] as $iKey => $images) {
								if ($images['Type'] == 'M') {
									#$image = $images['Url'];
									$image = str_replace('http:', 'https:', $images['Url']);
								}
							}
						}
						$service_response['purchase_details'][$t_key]['image'] = $image;

						//$service_response['purchase_details'][$t_key]['adult_count'] = @$transfer['Purchase']['ServiceList']['Service']['Paxes']['AdultCount'];
						//$service_response['purchase_details'][$t_key]['child_count'] = @$transfer['Purchase']['ServiceList']['Service']['Paxes']['ChildCount'];
						if (isset($transfer['Purchase']['ServiceList']['Service']['Paxes']['GuestList']['Customer']) && valid_array($transfer['Purchase']['ServiceList']['Service']['Paxes']['GuestList']['Customer'])) {
							if (isset($transfer['Purchase']['ServiceList']['Service']['Paxes']['GuestList']['Customer'][0])) {

								foreach ($transfer['Purchase']['ServiceList']['Service']['Paxes']['GuestList']['Customer'] as $key => $value) {
									if (isset($value['@attributes']['type']) && $value['@attributes']['type'] == 'AD') {
										$ad_ageid = $value['CustomerId'];
										$adult_age[$key] = $value['Age'];
									}
									if (isset($value['@attributes']['type']) && $value['@attributes']['type'] == 'CH') {
										$ch_ageid = $value['CustomerId'];
										$child_age[$key] = $value['Age'];
									}
								}
							} else {
								if (isset($transfer['Purchase']['ServiceList']['Service']['Paxes']['GuestList']['Customer']['@attributes']['type']) && $transfer['Purchase']['ServiceList']['Service']['Paxes']['GuestList']['Customer']['@attributes']['type'] == 'AD') {
									$ad_ageid = $transfer['Purchase']['ServiceList']['Service']['Paxes']['GuestList']['Customer']['CustomerId'];
									$adult_age[0] = $transfer['Purchase']['ServiceList']['Service']['Paxes']['GuestList']['Customer']['Age'];
								}
							}
						}

						//$service_response['purchase_details'][$t_key]['adult_age_list'] = $adult_age;
						$adult_age_list = @$adult_age;
						$child_age_list = @$child_age;
						//$service_response['child_count'] = $transfer['Purchase']['ServiceList']['Service']['Paxes']['ChildCount'];
						$service_response['purchase_details'][$t_key]['pickup_location_code'] = @$transfer['Purchase']['ServiceList']['Service']['PickupLocation']['Code'];
						$service_response['purchase_details'][$t_key]['pickup_location_name'] = @$transfer['Purchase']['ServiceList']['Service']['PickupLocation']['Name'];
						$service_response['purchase_details'][$t_key]['destination_location_code'] = @$transfer['Purchase']['ServiceList']['Service']['DestinationLocation']['Code'];
						$service_response['purchase_details'][$t_key]['destination_location_name'] = @$transfer['Purchase']['ServiceList']['Service']['DestinationLocation']['Name'];
						$service_response['purchase_details'][$t_key]['vehicle_type'] = @$transfer['Purchase']['ServiceList']['Service']['ProductSpecifications']['MasterVehicleType']['@attributes']['name'];
						$service_response['purchase_details'][$t_key]['transfer_info'] = @$transfer['Purchase']['ServiceList']['Service']['TransferInfo']['DescriptionList']['Description'];

						$service_response['purchase_details'][$t_key]['transfer_pickup_date'] = @$transfer['Purchase']['ServiceList']['Service']['TransferPickupTime']['@attributes']['date'];
						$service_response['purchase_details'][$t_key]['transfer_pickup_time'] = @$transfer['Purchase']['ServiceList']['Service']['TransferPickupTime']['@attributes']['time'];

						if (isset($transfer['Purchase']['ServiceList']['Service']['ArrivalTravelInfo']) && valid_array($transfer['Purchase']['ServiceList']['Service']['ArrivalTravelInfo'])) {
							$date_time_str = $transfer['Purchase']['ServiceList']['Service']['ArrivalTravelInfo']['ArrivalInfo'];
						} else {
							$date_time_str = $transfer['Purchase']['ServiceList']['Service']['DepartureTravelInfo']['DepartInfo'];
						}
						$service_response['purchase_details'][$t_key]['from_date'] = @$date_time_str['DateTime']['@attributes']['date'];
						$service_response['purchase_details'][$t_key]['from_time'] = @$date_time_str['DateTime']['@attributes']['time'];
				}
			}
		}
		if (isset($transfer_params['customer_waiting_time'])) {
			$service_response['customer_waiting_time'] = $transfer_params['customer_waiting_time'];
		}
		 // debug(round($total_amount));exit;
		$service_response['admin_markup'] = $admin_markup_val;
		$service_response['XMLPrice'] = $XMLRate;
		$service_response['agent_markup'] = $agent_markup+$search_level_markup;
		$service_response['service_tax'] = $gst_amount;
		$service_response['AgentServiceTax'] = $AgentServiceTax;
		$service_response['total_amount'] = ($total_amount + $gst_amount +$search_level_markup);
		$service_response['AgentNetRate'] = (($XMLRate + $admin_markup_val + $service_amount_All+$search_level_markup));
		$service_response['currency_code'] = $strConvcur;
		$service_response['adult_count'] = $adult_count;
		$service_response['child_count'] = $child_count;
		$service_response['adult_age_list'] = $adult_age_list;
		$service_response['child_age_list'] = $child_age_list;
		$service_response['sub_amount'] = $total_amount;
		
		$data['data'] = $service_response;

		// debug($service_response); exit;
		return $data;
	}

	/*
	 * Transfers Pre-Booking
	 */

	public function process_booking($book_id_module, $transfer_params) {// FIXME $module
		//debug($transfer_params);exit;
		$strSearchID = $transfer_params['search_id'];
		$arr_book_id_module = explode("**", $book_id_module);
		$book_id = $arr_book_id_module[0];
		$module = $arr_book_id_module[1];
		$horiizons_booking_reference = $transfer_params['horiizon_reference'];

		// debug($transfer_params);
		$booking_request = $this->booking_xml_request($transfer_params);
		$url = $this->service_url;
        $strIsb2c = $this->is_b2c();
        $strFpath = "";
        if($strIsb2c == 0){
            $strFpath = "../";
        }
		foreach ($booking_request['request'] as $book_k => $book_data) {
			/*  $path = FCPATH . "all_log_files/PurchaseConfirmRQ_" . $book_k . "_" . date('Y_m_d_H_i_s') . ".xml";
			 $fp = fopen($path, "wb");
			 fwrite($fp, $book_data);
			 fclose($fp); */
		}

		$booking_request_json = json_encode($booking_request['request']);

		$this->CI->custom_db->generate_static_response($booking_request_json, 'transfer booking request', 'hotelbeds_transfer');
		$booking_responce = array();
		$status = true;
		// debug($booking_request);exit;	
		if (isset($booking_request['request']) && valid_array($booking_request['request'])) {
			$i=0;
			foreach ($booking_request['request'] as $key => $requset) {
				$strFileName = str_replace("**","_",$book_id_module)."_".$strSearchID."_".$i;
				if(true){
	                $path = $strFpath."all_xml_logs/transfer/hb/booking/BookingRQ_".$strFileName.".xml";
	                $fp = fopen($path,"wb");fwrite($fp,$requset);fclose($fp);

					///$booking_responce[] = $strResponse = $GLOBALS ['CI']->api_interface->xml_post_request($booking_request['url'], $requset, $this->xml_header());

					// $resp = '["<PurchaseConfirmRS xmlns=\"http:\/\/www.hotelbeds.com\/schemas\/2005\/06\/messages\" xmlns:xsi=\"http:\/\/www.w3.org\/2001\/XMLSchema-instance\" xsi:schemaLocation=\"http:\/\/www.hotelbeds.com\/schemas\/2005\/06\/messages PurchaseConfirmRS.xsd\" echoToken=\"DummyEchoToken\"><AuditData><ProcessTime>1809<\/ProcessTime><Timestamp>2019-09-21 12:42:07.513<\/Timestamp><RequestHost>10.222.30.41<\/RequestHost><ServerName>LIVE<\/ServerName><ServerId>01<\/ServerId><SchemaRelease>2005\/06<\/SchemaRelease><HydraCoreRelease>4.34.7.20190725#76#0#A+<\/HydraCoreRelease><HydraEnumerationsRelease>1#82cef08e2c0d#7c1<\/HydraEnumerationsRelease><MerlinRelease>0<\/MerlinRelease><\/AuditData><Purchase purchaseToken=\"60829979516\" timeToExpiration=\"1799652\"><Reference><FileNumber>181068<\/FileNumber><IncomingOffice code=\"272\"><\/IncomingOffice><\/Reference><Status>BOOKING<\/Status><Agency><Code>315070<\/Code><Branch>1<\/Branch><\/Agency><Language>ENG<\/Language><CreationDate date=\"20190921\"\/><CreationUser>INTERAVIA315070<\/CreationUser><Holder type=\"AD\"><Age>0<\/Age><Name>ANITHA<\/Name><LastName>FAAFFAFA<\/LastName><\/Holder><AgencyReference>DT - ONLINE<\/AgencyReference><ServiceList><Service xsi:type=\"ServiceTransfer\" transferType=\"IN\" SPUI=\"272#T#1\"><Reference><FileNumber>181068-T1<\/FileNumber><IncomingOffice code=\"272\"><\/IncomingOffice><\/Reference><Status>CONFIRMED<\/Status><ContractList><Contract><Name>PR PVTDOH 1920<\/Name><IncomingOffice code=\"272\"><\/IncomingOffice><Sequence>3074<\/Sequence><\/Contract><\/ContractList><Supplier name=\"HOTELBEDS SPAIN, S.L.U\" vatNumber=\"ESB28916765\"\/><CommentList><Comment type=\"INCOMING\">tetstst<\/Comment><\/CommentList><DateFrom date=\"20190928\"\/><Currency code=\"EUR\">Euro<\/Currency><TotalAmount>20.450<\/TotalAmount><SellingPrice mandatory=\"N\">20.450<\/SellingPrice><AdditionalCostList><AdditionalCost type=\"AG_COMMISSION\"><Price><Amount>0.000<\/Amount><\/Price><\/AdditionalCost><AdditionalCost type=\"COMMISSION_VAT\"><Price><Amount>0.000<\/Amount><\/Price><\/AdditionalCost><AdditionalCost type=\"COMMISSION_PCT\"><Price><Amount>0.000<\/Amount><\/Price><\/AdditionalCost><\/AdditionalCostList><ModificationPolicyList><ModificationPolicy>Cancellation<\/ModificationPolicy><ModificationPolicy>Confirmation<\/ModificationPolicy><ModificationPolicy>Modification<\/ModificationPolicy><\/ModificationPolicyList><TransferInfo xsi:type=\"ProductTransfer\"><Code>272#T#1<\/Code><DescriptionList><Description type=\"GENERAL\" languageCode=\"ENG\">Private hire with driver<\/Description><Description type=\"PRODUCT\" languageCode=\"ENG\">Standard product type<\/Description><Description type=\"VEHICLE\" languageCode=\"ENG\">Car<\/Description><\/DescriptionList><ImageList><Image><Type>S<\/Type><Url>http:\/\/media.activitiesbank.com\/giata\/transfers\/TRD\/small\/prvt-stnd-cr.png<\/Url><\/Image><Image><Type>M<\/Type><Url>http:\/\/media.activitiesbank.com\/giata\/transfers\/TRD\/medium\/prvt-stnd-cr.png<\/Url><\/Image><Image><Type>L<\/Type><Url>http:\/\/media.activitiesbank.com\/giata\/transfers\/TRD\/large\/prvt-stnd-cr.png<\/Url><\/Image><Image><Type>XL<\/Type><Url>http:\/\/media.activitiesbank.com\/giata\/transfers\/TRD\/extralarge\/prvt-stnd-cr.png<\/Url><\/Image><\/ImageList><Type code=\"P\"><\/Type><VehicleType code=\"U\"><\/VehicleType><TransferSpecificContent id=\"161\"><GenericTransferGuidelinesList><TransferBulletPoint id=\"VOUC\"><Description>VOUCHER <\/Description><DetailedDescription>Remember to bring a printed copy of this voucher and a valid photo ID with you.<\/DetailedDescription><\/TransferBulletPoint><TransferBulletPoint id=\"CBBS\"><Description>CHILDBOOSTER \/ BABY SEAT<\/Description><DetailedDescription>Child car seats and boosters are not included unless specified in your booking and can carry an extra cost. Should you need to book them, please contact your point of sale prior to travelling.<\/DetailedDescription><\/TransferBulletPoint><\/GenericTransferGuidelinesList><MaximumWaitingTime time=\"0\">minutes<\/MaximumWaitingTime><MaximumWaitingTimeSupplierDomestic time=\"60\">minutes<\/MaximumWaitingTimeSupplierDomestic><MaximumWaitingTimeSupplierInternational time=\"90\">minutes<\/MaximumWaitingTimeSupplierInternational><\/TransferSpecificContent><\/TransferInfo><Paxes><AdultCount>1<\/AdultCount><ChildCount>0<\/ChildCount><GuestList><Customer type=\"AD\"><CustomerId>1<\/CustomerId><Age>12<\/Age><Name>Anitha<\/Name><LastName>faaffafa<\/LastName><\/Customer><\/GuestList><\/Paxes><PickupLocation xsi:type=\"ProductTransferTerminal\"><Code>DOH<\/Code><Name>Doha, Hamad International Airport<\/Name><TransferZone xsi:type=\"ProductZone\"><Code>DOHA AIRPO<\/Code><\/TransferZone><Country code=\"QA\"><Name>Qatar<\/Name><\/Country><TerminalType>A<\/TerminalType><\/PickupLocation><DestinationLocation xsi:type=\"ProductTransferHotel\"><Code>59451<\/Code><Name>Mercure Grand Hotel Doha<\/Name><TransferZone xsi:type=\"ProductZone\"><Code>DOHA CITY<\/Code><\/TransferZone><\/DestinationLocation><RetailPrice>20.450<\/RetailPrice><ProductSpecifications><MasterServiceType code=\"PRVT\" name=\"Private\"\/><MasterProductType code=\"STND\" name=\"Standard\"\/><MasterVehicleType code=\"CR\" name=\"Car\"\/><TransferGeneralInfoList><TransferBulletPoint id=\"ER\" order=\"1\"><Description>Exclusive ride for you<\/Description><\/TransferBulletPoint><TransferBulletPoint id=\"DTDS\" order=\"2\"><Description>Door to door service<\/Description><\/TransferBulletPoint><TransferBulletPoint id=\"AV247\" order=\"3\"><Description>Available 24\/7<\/Description><\/TransferBulletPoint><TransferBulletPoint id=\"BA\" order=\"11\"><Description>1 piece of baggage allowed per person ( max.dimensions 158cm) length+width+height=158cm<\/Description><\/TransferBulletPoint><TransferBulletPoint id=\"BAHB\" order=\"12\"><Description>1 item of hand baggage allowed per person<\/Description><\/TransferBulletPoint><\/TransferGeneralInfoList><\/ProductSpecifications><TransferPickupTime date=\"20190928\" time=\"1330\"\/><TransferPickupInformation><Description>Once you have collected your luggage, the driver will be waiting at the Arrivals Hall near the information center with a sign with your name on it. If you are unable to locate the driver\/agent, please call TRAVEL DESIGNER WLL on +974 55255133\/+974 44412300 .Languages spoken at the call centre: English. Please do not leave the pick-up area without having contacted the agent\/driver first.<\/Description><\/TransferPickupInformation><ArrivalTravelInfo><DepartInfo xsi:type=\"ProductTransferTerminal\"><Code>DOH<\/Code><\/DepartInfo><ArrivalInfo xsi:type=\"ProductTransferTerminal\"><Code>DOH<\/Code><Name>Doha, Hamad International Airport<\/Name><DateTime date=\"20190928\" time=\"1330\"\/><Country code=\"QA\"><Name>Qatar<\/Name><\/Country><TerminalType>A<\/TerminalType><\/ArrivalInfo><TravelNumber>1234<\/TravelNumber><TravelCompanyName>tetst<\/TravelCompanyName><\/ArrivalTravelInfo><DepartureTravelInfo><\/DepartureTravelInfo><CancellationPolicies><CancellationPolicy amount=\"20.450\" dateFrom=\"20190926\" time=\"0000\"\/><\/CancellationPolicies><ContactInfoList><ContactInfo><Type>Assistance origin<\/Type><TimeFrom>0000<\/TimeFrom><TimeTo>2359<\/TimeTo><OperationDays>Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday<\/OperationDays><Language>ENG<\/Language><CountryCode>0<\/CountryCode><PhoneNumber>+34971211630<\/PhoneNumber><\/ContactInfo><ContactInfo><Type>Assistance destination<\/Type><TimeFrom>0000<\/TimeFrom><TimeTo>2359<\/TimeTo><OperationDays>Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday<\/OperationDays><Language>ENG<\/Language><CountryCode>0<\/CountryCode><PhoneNumber>+34971211630<\/PhoneNumber><\/ContactInfo><\/ContactInfoList><\/Service><\/ServiceList><Currency code=\"EUR\"><\/Currency><PaymentData><PaymentType code=\"P\"><\/PaymentType><InvoiceCompany><Code>CH1<\/Code><Name>HOTELBEDS SWITZERLAND AG<\/Name><RegistrationNumber>CHE425060629<\/RegistrationNumber><\/InvoiceCompany><Description>Name HOTELBEDS SWITZERLAND AG, Bank: JP Morgan(London) Account:GB22CHAS60924241463568,  SWIFT:CHASGB2L,  7 days prior to clients arrival (except group bookings with fixed days in advance at the time of the confirmation) . Please indicate our reference number when making payment. Thank you for your cooperation.<\/Description><\/PaymentData><TotalPrice>20.450<\/TotalPrice><PendingAmount>20.450<\/PendingAmount><\/Purchase><\/PurchaseConfirmRS>\n"]';
					// debug($url);exit;
					// debug($requset); 
					$booking_responce[] = $strResponse = $GLOBALS ['CI']->api_interface->xml_post_request($url, $requset, $this->xml_header());

					// $booking_responce[] = $strResponse = json_decode($resp);
					// debug($booking_responce);exit();
	                $path = $strFpath."all_xml_logs/transfer/hb/booking/BookingRS_".$strFileName.".xml";
	                $fp = fopen($path,"wb");fwrite($fp,$strResponse);fclose($fp);				
	            }else{
					$booking_responce[] = file_get_contents($strFpath."all_xml_logs/transfer/hb/booking/BookingRS_".$strFileName.".xml");
	            }
                $i++;
			}


// 			$booking_responce=Array
// (
//    "17082020-04-22 14:23:52.110103.39.132.70FORM012005/064.49.5.20190303#FO#0#A+1#5a1c4c57614f#4840344100BOOKING999941ENGTEST999940SSSDASSDDT - ONLINE344100-T1CONFIRMEDLPPVTBLR 202130793Euro125.240125.2400.0000.0000.000CancellationConfirmationModification270#T#1Private hire with driverPremium product typeMinivanShttp://media.stage.activitiesbank.com/giata/transfers/TRD/small/prvt-prm-mv.pngMhttp://media.stage.activitiesbank.com/giata/transfers/TRD/medium/prvt-prm-mv.pngLhttp://media.stage.activitiesbank.com/giata/transfers/TRD/large/prvt-prm-mv.pngXLhttp://media.stage.activitiesbank.com/giata/transfers/TRD/extralarge/prvt-prm-mv.pngVOUCHER Remember to bring a printed copy of this voucher and a valid photo ID with you.CHILDBOOSTER / BABY SEATChild car seats and boosters are not included unless specified in your booking and can carry an extra cost. Should you need to book them, please contact your point of sale prior to travelling.minutesminutes10112sssdassd118359Ramee Guestline Hotel BangaloreBLROUTBLRBengaluru, Bengaluru Int. AirportBLR AIRIndiaA125.240Exclusive ride for youDoor to door serviceMeet & Greet service1 item of hand baggage allowed per person1 piece of baggage allowed per person ( max.dimensions 158cm) length+width+height=158cmYou will be picked up at the hotel main lobby. For urgent assistance, please contact our local representative, Mr. Deepak Shrivastava at +91-9999846900. If you are unable to locate the driver, please call Le Passage India on +91 9999846900 . Languages spoken at the call centre:  English. Please do not leave the pick-up area without having contacted the agent/driver first. If the supplier doesn't answer the phone, please call our emergency telephone number listed at the bottom of the voucher before leaving the pick-up area.BLRBengaluru, Bengaluru Int. AirportIndiaAXXXUX1111Provab TechnosoftAssistance origin00002359Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,SundayENG0+34971211630Assistance destination00002359Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,SundayENG0+34971211630E14HOTELBEDS S.L.U.ESB57218372Name Hotelbeds, S.L.U, Bank: CITIBANK(Citigroup Centre, Canary Wharf, London, E14 5LB. United Kingdom) Account:ES6614740000150011935001,  SWIFT:CITIESMXXXX,  7 days prior to clients arrival (except group bookings with fixed days in advance at the time of the confirmation) . Please indicate our reference number when making payment. Thank you for your cooperation.125.240125.240"

// );
			// debug($booking_responce); exit;
			if (isset($booking_responce) && valid_array($booking_responce)) {

				foreach ($booking_responce as $book_rs_k => $book_rs_data) {
					/*  $path = FCPATH . "all_log_files/PurchaseConfirmRQ_" . $book_rs_k . "_" . date('Y_m_d_H_i_s') . ".xml";
					 $fp = fopen($path, "wb");
					 fwrite($fp, $book_rs_data);
					 fclose($fp); */
				}

				$booking_response_json = json_encode($booking_responce);
				$this->CI->custom_db->generate_static_response($booking_response_json, 'transfer booking response', 'hotelbeds_transfer');
			}
		}
		 

		// debug($booking_responce);exit;
		//print_r($booking_responce); exit;
		if (isset($booking_responce) && valid_array($booking_responce)) {
			$booking_respnce_Arr = array();
			$response_arr['data'] = array();
			$status = true;

			//$response['data'] = $booking_responce;

			foreach ($booking_responce as $br_key => $response) {

				// debug($response);

				// $xml = simplexml_load_string($response[0]);
				$xml = simplexml_load_string($response);
				$transferjson = json_encode($xml);
				$facilities_array = json_decode($transferjson, TRUE);

				#$fileName = BASEPATH . '../XMLs/facilities'.$br_key.'.json';
				#file_put_contents($fileName,$transferjson);
				$GLOBALS['CI']->private_management_model->provab_xml_logger('Book_Transfer', $book_id, 'Transfer', $booking_request['request'][$br_key], $transferjson);
				$transfer_book_response = json_decode($transferjson, TRUE);
				// debug($transfer_book_response); 
				/*if (isset($transfer_book_response['Purchase']) && valid_array($transfer_book_response['Purchase'])) {

					if (isset($transfer_params['token']['purchase_details'][$br_key]['cancellation_policies']['CancellationPolicy']['@attributes'])) {
						$cancellation_data = $transfer_params['token']['purchase_details'][$br_key]['cancellation_policies']['CancellationPolicy']['@attributes'];
					} else {
						$cancellation_data = $transfer_params['token']['purchase_details'][$br_key]['cancellation_policies'];
					}

					$this->save_transfer_booking($book_id, $transfer_book_response, $horiizons_booking_reference, $transfer_params, $module, $cancellation_data);
				} else {
					$status = false;
				}*/
				$booking_respnce_Arr[] = $transfer_book_response;
			}

			$response_arr['data'] = $booking_respnce_Arr;
		} else {
			$status = false;
		}
		$response_arr['status'] = $status;
		// debug($response_arr);exit();
		return $response_arr;
	}

	/*
	 * Booking request
	 * */

	private function booking_xml_request($transfer_params) {
		$search_data = $GLOBALS ['CI']->transfer_model->get_safe_search_data($transfer_params['search_id']);
		// debug($transfer_params);
		$search_id = $transfer_params['search_id'];
		$booking_source = $transfer_params['booking_source'];
		$arrival_from_loc = @$transfer_params['from_loc_id'];
		$travel_company_name = @$transfer_params['travel_company_name'];
		$travel_number = @$transfer_params['travel_number'];

		$travel_company_name = 'Voyage Tours';
		$travel_number = 'VT6532';

		$comment = @$transfer_params['comment'];
		$additional_comments = @$transfer_params['additional_comments'];
		$from_transfer_type = $search_data['data']['from_transfer_type'];
		$to_transfer_type = $search_data['data']['to_transfer_type'];
		$request_array = array();
		if (valid_array($transfer_params['purchase_token'])) {
			foreach ($transfer_params['purchase_token'] as $pt_key => $purchasetoken) {
				$request_array[$pt_key]['purchase_token'] = $purchasetoken;
				if ($pt_key == 0) {
					$request_array[$pt_key]['from_transfer_type'] = $from_transfer_type;
					$request_array[$pt_key]['to_transfer_type'] = $to_transfer_type;
				} else {
					$request_array[$pt_key]['from_transfer_type'] = $to_transfer_type;
					$request_array[$pt_key]['to_transfer_type'] = $from_transfer_type;
				}
			}
		}

		if (valid_array($transfer_params['time_to_expiration'])) {
			foreach ($transfer_params['time_to_expiration'] as $tte_key => $expiration_time) {
				$request_array[$tte_key]['time_to_expiration'] = $expiration_time;
			}
		}

		if (valid_array($transfer_params['agency_code'])) {
			foreach ($transfer_params['agency_code'] as $ac_key => $agency_code) {
				$request_array[$ac_key]['agency_code'] = $agency_code;
			}
		}

		if (valid_array($transfer_params['agency_code'])) {
			foreach ($transfer_params['agency_code'] as $ac_key => $agency_code) {
				$request_array[$ac_key]['agency_code'] = $agency_code;
				$request_array[$ac_key]['creation_user'] = $transfer_params['creation_user'];
			}
		}

		if (valid_array($transfer_params['transfer_type'])) {
			foreach ($transfer_params['transfer_type'] as $tt_key => $transfer_type) {
				$request_array[$tt_key]['transfer_type'] = $transfer_type;
				$request_array[$tt_key]['SPUI'] = $transfer_params['SPUI'];
				$request_array[$tt_key]['currency'] = $transfer_params['currency'];
			}
		}

		if (valid_array($transfer_params['total_amount'])) {
			foreach ($transfer_params['total_amount'] as $ta_key => $totalAmt) {
				$request_array[$ta_key]['total_amount'] = $totalAmt;
				$request_array[$ta_key]['adult_count'] = $transfer_params['adult_count'];
				$request_array[$ta_key]['child_count'] = $transfer_params['child_count'];
			}
		}

		if (valid_array($transfer_params['pickup_location_code'])) {
			foreach ($transfer_params['pickup_location_code'] as $plc_key => $pickup_loc_code) {
				$request_array[$plc_key]['pickup_location_code'] = $pickup_loc_code;
			}
		}

		if (valid_array($transfer_params['pickup_location_name'])) {
			foreach ($transfer_params['pickup_location_name'] as $pln_key => $pickup_loc_name) {
				$request_array[$pln_key]['pickup_location_name'] = $pickup_loc_name;
			}
		}

		if (valid_array($transfer_params['destination_location_code'])) {
			foreach ($transfer_params['destination_location_code'] as $dlc_key => $desti_loc_code) {
				$request_array[$dlc_key]['destination_location_code'] = $desti_loc_code;
			}
		}

		if (valid_array($transfer_params['from_date'])) {
			foreach ($transfer_params['from_date'] as $fd_key => $fromdate) {
				$request_array[$fd_key]['from_date'] = $fromdate;
			}
		}

		if (valid_array($transfer_params['from_time'])) {
			foreach ($transfer_params['from_time'] as $ft_key => $fromtime) {
				$request_array[$ft_key]['from_time'] = $fromtime;
			}
		}

		if (valid_array($transfer_params['destination_location_name'])) {
			foreach ($transfer_params['destination_location_name'] as $dln_key => $desti_loc_name) {
				$request_array[$dln_key]['destination_location_name'] = $desti_loc_name;
				$request_array[$dln_key]['currency_code'] = $transfer_params['currency_code'];
				$request_array[$dln_key]['adult_age_in_string'] = @$transfer_params['adult_age_in_string'];
				$request_array[$dln_key]['first_name'] = @$transfer_params['first_name'];
				$request_array[$dln_key]['last_name'] = @$transfer_params['last_name'];
				$request_array[$dln_key]['billing_address_1'] = @$transfer_params['billing_address_1'];
				$request_array[$dln_key]['billing_email'] = @$transfer_params['billing_email'];
				$request_array[$dln_key]['billing_country'] = @$transfer_params['billing_country'];
				$request_array[$dln_key]['passenger_contact'] = @$transfer_params['passenger_contact'];
				$request_array[$dln_key]['confirm'] = $transfer_params['confirm'];
				$request_array[$dln_key]['continue'] = $transfer_params['continue'];
			}
		}
		$booking_request = array();
		/* create XML request for booking */
		//  $safe_search_data = $this->transfer_model->get_safe_search_data($search_id);

		foreach ($request_array as $purchase_key => $purchase) {  //debug($arrival_from_loc);debug($purchase); exit;
			$request = '';
			$request .= '<PurchaseConfirmRQ echoToken="DummyEchoToken" xmlns="http://api.interface-xml.com/schemas/2005/06/messages" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://api.interface-xml.com/schemas/2005/06/messages PurchaseConfirmRQ.xsd" version="2013/12">';
			$request .= '<Language>ENG</Language>';
			$request .= '<Credentials>  <User>' . $this->username . '</User>    <Password>' . $this->password . '</Password>    </Credentials>';
			$request .= '<ConfirmationData purchaseToken="' . $purchase['purchase_token'] . '">';
			$request .= '<Holder type="AD">
                            <CustomerId>1</CustomerId>
                            <Name>' . $purchase['first_name'] . '</Name>
                            <LastName>' . $purchase['last_name'] . '</LastName>
                        </Holder>';
			$request .= '<AgencyReference>DT - Online</AgencyReference>';
			$request .= '<ConfirmationServiceDataList>';
			$request .= '<ServiceData SPUI="' . $purchase['SPUI'] . '" xsi:type="ConfirmationServiceDataTransfer">';
			$request .= '<TransferType>' . $purchase['transfer_type'] . '</TransferType>';
			$request .= '<CustomerList>';
			$paxesCnt = 1;
			foreach ($search_data['data']['adult_ages'] as $a_key => $adult_age) {
				$request .='<Customer type="AD">';
				$request .='<CustomerId>' . $paxesCnt . '</CustomerId>';
				$request .='<Age>' . $adult_age . '</Age>';
				if ($paxesCnt == 1) {
					$request .='<Name>' . $purchase['first_name'] . '</Name>
                            <LastName>' . $purchase['last_name'] . '</LastName>';
				}
				$request .='</Customer>';
				$paxesCnt++;
			}

			if (isset($search_data['data']['child_ages']) && valid_array($search_data['data']['child_ages'])) {
				foreach ($search_data['data']['child_ages'] as $c_key => $child_age) {
					$request .= '<Customer type="CH">
                                <CustomerId>' . $paxesCnt . '</CustomerId>
                                <Age>' . $child_age . '</Age>
                            </Customer>';
					$paxesCnt++;
				}
			}
			$request .= '</CustomerList>';
			/* Comment is static for now */
			// NEED TO FIX
			if (isset($comment) && !empty($comment)) {
				$request .= '<CommentList>
                            <Comment type="INCOMING">' . $comment . '</Comment>
                        </CommentList>';
			}
			//Arrival info
			//debug($purchase); exit;
			if ($purchase['transfer_type'] == 'IN') {
				$request .= '<ArrivalTravelInfo>';
				$request .= '<DepartInfo xsi:type="' . $purchase['from_transfer_type'] . '" />';

				$request .= '<ArrivalInfo xsi:type="' . $purchase['to_transfer_type'] . '">
                             <Code>' . $purchase['pickup_location_code'] . '</Code>
                             <Name>' . $purchase['pickup_location_name'] . '</Name>
                                <DateTime date="' . $purchase['from_date'] . '" time="' . $purchase['from_time'] . '"/>

                            </ArrivalInfo>';
				//FIX BELOW FLIGHT DETAILS
				$request .= '<TravelNumber>' . $travel_number . '</TravelNumber>';
				$request .= '<TravelCompanyName>' . $travel_company_name . '</TravelCompanyName >';

				//Departure info
				if (isset($additional_comments) && !empty($additional_comments)) {
					$request .= '<AdditionalComments>' . $additional_comments . '</AdditionalComments>';
				}
				$request .= '</ArrivalTravelInfo>';



			//	$request .= '<DepartureTravelInfo>';
			//	$request .= '<AdditionalComments>' . $purchase['destination_location_name'] . '</AdditionalComments>';
			//	$request .= '</DepartureTravelInfo>';
			} else {
				// FIX FLIGHT NO
				$request .= '<DepartureTravelInfo>
                                <DepartInfo xsi:type="' . $purchase['to_transfer_type'] . '">
                                    <Code>' . $purchase['destination_location_code'] . '</Code>
                                    <DateTime date="' . $purchase['from_date'] . '" time="' . $purchase['from_time'] . '"/>
                                </DepartInfo>
                                <TravelNumber>UX1111</TravelNumber>
                                <TravelCompanyName>Provab Technosoft</TravelCompanyName >
                            </DepartureTravelInfo>';
			}

			$request .= '</ServiceData>';
			$request .= '</ConfirmationServiceDataList>';

			$request .= '</ConfirmationData>
                </PurchaseConfirmRQ>';

			$booking_request[] = $request;
			$response['request'] = $booking_request;

			//header("Content-type: text/xml");
			// echo $request; exit;
				 
		}
		//echo $request; exit;
		$url = $this->service_url;
		$response['url'] = $url;
		return $response;
	}

	/**
	 * Get Filter Params - fliter_params
	 */
	function format_search_response($hl, $cobj, $sid, $module = 'b2c', $fltr = array()) {
		$level_one = true;
		$current_domain = true;
		if ($module == 'b2c') {
			$level_one = false;
			$current_domain = true;
		} else if ($module == 'b2b') {
			$level_one = true;
			$current_domain = true;
		}
		$h_count = 0;
		$activityResults = array();
		if (isset($fltr ['hl']) == true) {
			foreach ($fltr ['hl'] as $tk => $tv) {
				$fltr ['hl'] [urldecode($tk)] = strtolower(urldecode($tv));
			}
		}

		if (isset($fltr ['_car_Type']) && valid_array($fltr ['_car_Type'])) {
			$fltr ['_car_Type'] = array_map('strtolower', $fltr ['_car_Type']);
		}

		//debug($fltr ['_car_Type'] );exit;
		// Creating closures to filter data
		$check_filters = function ($hd) use($fltr) {
			//_acc type
			//debug($hd);exit;
			$any_facility = function ($cstr, $c_list) {
				foreach ($c_list as $k => $v) {
					if (stripos(($cstr), ($v)) > -1) {
						return true;
					}
				}
			};
			//debug($any_facility);exit;
			$amount_return = isset($hd['details'][1]['total_amout']) ? $hd['details'][1]['total_amout'] : 0;
			$total_price = $hd['details'][0]['total_amout'] + $amount_return;
			//debug($fltr);exit;
			if (
					(@$fltr ['min_price'] <= ceil($total_price) && (@$fltr ['max_price'] != 0 && @$fltr ['max_price'] >= floor($total_price))) &&
					(valid_array(@$fltr ['_car_Type']) == false ||
							(valid_array(@$fltr ['_car_Type']) == true && in_array(strtolower($hd['details'][0]['vehicle_type']), $fltr ['_car_Type']))
							)
					) {//echo 'm';exit;
						return true;
			} else {//echo 'n';exit;
				return false;
			}
		};

		$hc = 0;
		$frc = 0;
		// debug($hl);exit;
		$transferResults = array();
		foreach ($hl as $hr => $hd) {
			if (valid_array($hd)) {
				//  echo 'hi';
				$hc++;
				// markup
				//$price = $this->update_search_markup_currency ( $hd ['price'], $cobj, $sid, $level_one, $current_domain );
				//$hd ['price'] = $price['value'];
				//$hd ['currency'] = $price['currency'];
				//$hd ['price'] = $hd ['price'];
				// filter after initializing default data and adding markup
				if (valid_array($fltr) == true && $check_filters($hd) == false) {//echo 'df';
					continue;
				}
				$transferResults [$hr] = $hd;
				$frc++;
			}
			//debug($transferResults [$hr]);exit();
		}
		//echo 'cfv';   debug($transferResults);exit;
		$hl = $transferResults;

		$hl ['source_result_count'] = $hc;
		$hl ['filter_result_count'] = $frc;
		return $hl;
	}

	/**
	 * Get Filter Summary of the data list
	 *
	 * @param array $hl
	 */
	function filter_summary($hl) {
		// echo "hh";exit;
		$h_count = 0;
		$filt ['p'] ['max'] = false;
		$filt ['p'] ['min'] = false;
		$filt ['car_type'] = array();
		$filters = array();
		foreach ($hl as $hr => $hd) {
			// debug($hd);exit;
			if (isset($hd) && valid_array($hd)) {
				// filters
				$car_type = $hd['details'][0]['vehicle_type'];
				//debug($car_type);
				if (isset($car_type) && !empty($car_type)) {
					if (isset($filt['car_type'][$car_type]) == false) {
						$filt ['car_type'] [$car_type] ['c'] = 1;
						$filt ['car_type'] [$car_type] ['v'] = $car_type;
					} else {
						$filt ['car_type'] [$car_type] ['c']++;
					}
				}//debug($filt ['car_type']);
				$amount2 = isset($hd['details'][1]['total_amout']) ? $hd['details'][1]['total_amout'] : 0;
				$total_price = $hd['details'][0]['total_amout'] + $amount2;

				if (($filt ['p'] ['max'] != false && $filt ['p'] ['max'] < $total_price) || $filt ['p'] ['max'] == false) {
					$filt ['p'] ['max'] = roundoff_number($total_price);
				}
				/* if (($filt ['p'] ['min'] != false && $filt ['p'] ['min'] > $hd ['price']) || $filt ['p'] ['min'] == false) {
				 $filt ['p'] ['min'] = roundoff_number ( $hd ['price'] ['RoomPrice'] );
				 } */

				if (($filt ['p'] ['min'] != false && $filt ['p'] ['min'] > $total_price) || $filt ['p'] ['min'] == false) {
					$filt ['p'] ['min'] = floor($total_price);
				}
				//debug($filt);
				$filters ['data'] = $filt;
				$h_count++;
			}
		}//exit;
		// debug($filters);exit;
		ksort($filters ['data'] ['car_type']);
		$filters ['car_count'] = $h_count;

		return $filters;
	}


	/*
	 * save booking
	 * */

	private function save_transfer_booking($booking_id, $transfer_book_response, $horiizons_booking_reference, $transfer_params, $module = 'b2c', $cancellation_data) {


		$name_title = $transfer_params['name_title'];
		$total_amt_with_service_tax = $transfer_params['token']['total_amount'];

		//debug($transfer_params);debug($total_amt_with_service_tax);exit;
		$service_tax = $transfer_params['token']['service_tax'];

		$tot = $transfer_params['token']['total_amount'];

		$AgentNetPrice = $transfer_params['token']['AgentNetRate'];


		if ($module == "b2c") {
			$convinence_fee = $transfer_params['token']['convinence_fee'];
		} else {
			$convinence_fee = 0;
		}

		//debug($service_tax);exit;
		if (isset($transfer_params['token']['customer_waiting_time'])) {
			$customer_waiting_time = $transfer_params['token']['customer_waiting_time'];
		} else {
			$customer_waiting_time = '';
		}

		/*
		 * hb_transfer_booking_details,hb_transfer_contact_details, hb_transfer_paxes_details, hb_transfer_service_details
		 * */
		$CI = &get_instance();
		$image_url = '';
		$currency_obj = new Currency(array('module_type' => 'transfer', 'from' => get_application_default_currency(), 'to' => get_application_default_currency()));
		//debug($transfer_params);exit;
		// Need to return following data as this is needed to save the booking fare in the transaction details
		$response ['fare'] = $response ['domain_markup'] = $response ['level_one_markup'] = 0;
		$domain_origin = get_domain_auth_id();
		$master_search_id = $transfer_params['search_id'];

		if (isset($transfer_book_response['Purchase']) && valid_array($transfer_book_response['Purchase'])) {
			$purchase_details = $transfer_book_response['Purchase'];
			//  debug($purchase_details); exit;
			$purchase_token = $purchase_details['@attributes']['purchaseToken'];
			$time_to_expiration = $purchase_details['@attributes']['timeToExpiration'];
			$status = $purchase_details['Status'];

			$booking_reference_no = @$purchase_details['Reference']['FileNumber'];
			$incoming_office_code = @$purchase_details['Reference']['IncomingOffice']['@attributes']['code'];

			$agency_code = $purchase_details['Agency']['Code'];
			$agency_branch = $purchase_details['Agency']['Branch'];
			$creation_date = $purchase_details['CreationDate']['@attributes']['date'];
			$creation_user = $purchase_details['CreationUser'];
			$holder_type = $purchase_details['Holder']['@attributes']['type'];
			$holder_age = $purchase_details['Holder']['Age'];
			$holder_first_name = $purchase_details['Holder']['Name'];
			$holder_last_name = $purchase_details['Holder']['LastName'];
			$agency_reference = $purchase_details['AgencyReference'];
			/*
			 * services list
			 * */
			if (isset($purchase_details['ServiceList']['Service']) && valid_array($purchase_details['ServiceList']['Service'])) {
				$service_list = $purchase_details['ServiceList']['Service'];

				$transfer_type = $service_list['@attributes']['transferType'];
				$SPUI = $service_list['@attributes']['SPUI'];

				$service_booking_reference_no = @$service_list['Reference']['FileNumber'];
				$service_status = isset($service_list['Status']) && !empty($service_list['Status']) ? $service_list['Status'] : 'FAILED';

				/* contract list */
				$contract_list = $service_list['ContractList']['Contract'];
				$contract_name = $contract_list['Name'];
				$contract_sequence = $contract_list['Sequence'];

				$booking_detail_attribute['supplier'] = $service_list['Supplier'];

				$attributes['comment_list'] = @$service_list['CommentList'];
				$date_from = $service_list['DateFrom']['@attributes']['date'];
				$currency = $service_list['Currency'];
				$service_total_amount = $service_list['TotalAmount'];
				$service_selling_price = $service_list['SellingPrice'];

				$additional_cost_list = $service_list['AdditionalCostList']['AdditionalCost'];
				//$service_total_amount = $service_list['TotalAmount'];
				$modification_policy_list = $service_list['ModificationPolicyList'];

				$transfer_code = $service_list['TransferInfo']['Code'];
				$transfer_description_list = json_encode($service_list['TransferInfo']['DescriptionList']);

				/* save image url for voucher */

				if (isset($service_list['TransferInfo']['ImageList']['Image']) && valid_array($service_list['TransferInfo']['ImageList']['Image'])) {
					foreach ($service_list['TransferInfo']['ImageList']['Image'] as $i_key => $image_val) {
						if ($image_val['Type'] == 'M') {
							$image_url = $image_val['Url'];
						}
					}
				}

				$transfer_type_code = $service_list['TransferInfo']['Type']['@attributes']['code'];
				$transfer_vehicle_type = $service_list['TransferInfo']['VehicleType']['@attributes']['code'];

				$transfer_specific_content_id = @$service_list['TransferInfo']['TransferSpecificContent']['@attributes']['id'];
				$attributes['generic_transfer_guidelines_list'] = @$service_list['TransferInfo']['TransferSpecificContent']['GenericTransferGuidelinesList']['TransferBulletPoint'];
				$attributes['specific_transfer_info_list'] = @$service_list['TransferInfo']['TransferSpecificContent']['SpecificTransferInfoList']['TransferBulletPoint'];
				$attributes['maximum_waiting_time_supplier_domestic'] = @$service_list['TransferInfo']['TransferSpecificContent']['MaximumWaitingTimeSupplierDomestic'];
				$attributes['maximum_waiting_time_supplier_international'] = @$service_list['TransferInfo']['TransferSpecificContent']['MaximumWaitingTimeSupplierInternational'];

				$transfer_service_booking_attributes = json_encode($attributes);

				/* Paxes */
				$adult_count = $service_list['Paxes']['AdultCount'];
				$child_count = $service_list['Paxes']['ChildCount'];

				/* start transction */
				$CI->db->trans_start();

				/* customer list */
				if (isset($service_list['Paxes']['GuestList']['Customer']) && valid_array($service_list['Paxes']['GuestList']['Customer'])) {

					if (isset($service_list['Paxes']['GuestList']['Customer']['@attributes'])) {
						$customer_detail = $service_list['Paxes']['GuestList']['Customer'];
						$cust_type = @$customer_detail['@attributes']['type'];
						$cust_id = @$customer_detail['CustomerId'];
						$cust_age = @$customer_detail['Age'];
						$cust_first_name = @$customer_detail['Name'];
						$cust_last_name = @$customer_detail['LastName'];
						$cust_email = @$customer_detail['email'];
						$cust_phone_no = @$customer_detail['phone_no'];

						$services_paxes_details = array(
								'app_reference' => $booking_id,
								'booking_reference' => $booking_reference_no,
								'type' => $cust_type,
								'customer_id' => $cust_id,
								'age' => $cust_age,
								'first_name' => $cust_first_name,
								'last_name' => $cust_last_name,
								'email' => $cust_email,
								'phone_no' => $cust_phone_no,
						);

						$CI->db->insert('hb_transfer_paxes_details', $services_paxes_details);
					} else {
						foreach ($service_list['Paxes']['GuestList']['Customer'] as $cust_key => $customer_details) {

							$cust_type = @$customer_details['@attributes']['type'];
							$cust_id = @$customer_details['CustomerId'];
							$cust_age = @$customer_details['Age'];
							$cust_first_name = @$customer_details['Name'];
							$cust_last_name = @$customer_details['LastName'];
							$cust_email = @$customer_details['email'];
							$cust_phone_no = @$customer_details['phone_no'];

							$services_paxes_details = array(
									'app_reference' => $booking_id,
									'booking_reference' => $booking_reference_no,
									'type' => $cust_type,
									'customer_id' => $cust_id,
									'age' => $cust_age,
									'first_name' => $cust_first_name,
									'last_name' => $cust_last_name,
									'email' => $cust_email,
									'phone_no' => $cust_phone_no,
							);
							$CI->db->insert('hb_transfer_paxes_details', $services_paxes_details);
						}
					}
				}

				/* pickup location */
				$pickup_code = $service_list['PickupLocation']['Code'];
				$pickup_name = $service_list['PickupLocation']['Name'];
				$pickup_transfer_zone_code = $service_list['PickupLocation']['TransferZone']['Code'];
				$pickup_info = json_encode($service_list['PickupLocation']);


				/* destination location */
				$desti_code = $service_list['DestinationLocation']['Code'];
				$desti_name = $service_list['DestinationLocation']['Name'];
				$desti_transfer_zone_code = $service_list['DestinationLocation']['TransferZone']['Code'];
				$desti_info = json_encode($service_list['DestinationLocation']);

				$service_retail_price = $service_list['RetailPrice'];
				$product_specifications = json_encode($service_list['ProductSpecifications']);
				if (isset($service_list['TransferPickupTime']) && !empty($service_list['TransferPickupTime'])) {
					$transfer_pickup_time = json_encode($service_list['TransferPickupTime']['@attributes']);
				} else {
					$transfer_pickup_time = '';
				}
				$transfer_pickup_information = $service_list['TransferPickupInformation']['Description'];

				$ArrivalTravelInfo = json_encode($service_list['ArrivalTravelInfo']);

				$additional_comments = @$service_list['DepartureTravelInfo']['AdditionalComments'];
				$DepartureTravelInfo = json_encode($additional_comments);

				/* CancellationPolicies */

				/* Balu if (isset($service_list['CancellationPolicies']) && valid_array($service_list['CancellationPolicies'])) {

				foreach ($service_list['CancellationPolicies'] as $cncl_key => $policy) {
				if (isset($policy['@attributes']['dateFrom']) && !empty($policy['@attributes']['dateFrom'])) {
				$policy['@attributes']['dateFrom'] = date('Y-m-d', strtotime($policy['@attributes']['dateFrom']));
				}
				if (isset($policy['@attributes']['time']) && !empty($policy['@attributes']['time'])) {
				$policy['@attributes']['time'] = date('H:i', strtotime($policy['@attributes']['time']));

				}

				$cancel_total_amt = $policy['@attributes']['amount'];
				$currency_obj = new Currency(array('module_type' => 'transfer', 'from' => $purchase_details['Currency']['@attributes']['code'], 'to' => get_application_transaction_currency_preference()));
				$deduction_cur_obj = clone $currency_obj;

				if ($module == 'b2c') {
				//Convert to default currency and add mark up
				$cancel_total_fare = $currency_obj->get_currency($cancel_total_amt, true, false, true, 1); // (ON Total PRICE ONLY)
				//convience fee
				//$currency_obj->getConversionRate(false,get_application_transaction_currency_preference(),get_application_transaction_currency_preference());
				$cancel_convinence = $currency_obj->convenience_fees($cancel_total_fare['default_value'], $master_search_id);
				$cancel_convinence = number_format($cancel_convinence, 2);
				} else {
				// B2B Calculation
				$cancel_total_fare = $currency_obj->get_currency($cancel_total_amt, true, true, true, 1); // (ON Total PRICE ONLY)
				$cancel_convinence = 0;
				}

				$markup_cancel_total = $cancel_total_fare['default_value'] + $cancel_convinence; */

				//

				if (isset($cancellation_data['amount'])) {

					 
					$cancel_array = array(
							'app_reference' => $booking_id,
							'booking_reference' => $booking_reference_no,
							'amount' => @round($cancellation_data['amount']),
							'XMLNetRate' => $cancellation_data['CancelAmount'],
							'XMLCurrency' => $cancellation_data['CancelXMLCurrency'],
							'dateFrom' => @$cancellation_data['dateFrom'] . " " . @$cancellation_data['time'],
							'time' => @$cancellation_data['time'],
							'currency_code' => @$cancellation_data['cancel_currency_code'],
							'description' => @$cancellation_data['msg']
					);
					 

				} else {
					foreach ($cancellation_data as $cncl_key => $policy) {
						$cancel_array = array(
								'app_reference' => $booking_id,
								'booking_reference' => $booking_reference_no,
								'amount' => @round($policy['@attributes']['amount']),
								'XMLNetRate' => @$policy['@attributes']['CancelAmount'],
								'XMLCurrency' => @$policy['@attributes']['CancelXMLCurrency'],
								'dateFrom' => @$policy['@attributes']['dateFrom'] . " " . $policy['@attributes']['time'],
								'time' => @$policy['@attributes']['time'],
								'currency_code' => @$policy['@attributes']['cancel_currency_code'],
								'description' => @$policy['@attributes']['msg']
						);
					}
				}




				$CI->db->insert('hb_transfer_cancellation_policy', $cancel_array);
				 
				/* contact details */
				if (isset($service_list['ContactInfoList']['ContactInfo']) && valid_array($service_list['ContactInfoList']['ContactInfo'])) {
					foreach ($service_list['ContactInfoList']['ContactInfo'] as $cnct_key => $contact) {
						if (isset($contact['TimeFrom']) && !empty($contact['TimeFrom'])) {
							$contact['TimeFrom'] = date('H:i', strtotime($contact['TimeFrom']));
						}
						if (isset($contact['TimeTo']) && !empty($contact['TimeTo'])) {
							$contact['TimeTo'] = date('H:i', strtotime($contact['TimeTo']));
						}
						$contact_insert_array = array(
								'app_reference' => $booking_id,
								'booking_reference' => $booking_reference_no,
								'contact_Info_type' => $contact['Type'],
								'contact_info_time_from' => $contact['TimeFrom'],
								'contact_info_time_to' => $contact['TimeTo'],
								'operation_days' => $contact['OperationDays'],
								'language' => $contact['Language'],
								'country_code' => $contact['CountryCode'],
								'phone_number' => $contact['PhoneNumber']
						);

						$CI->db->insert('hb_transfer_contact_details', $contact_insert_array);
					}
				}

				if (isset($service_list['ArrivalTravelInfo']['ArrivalInfo']['DateTime'])) {
					$travel_date = date('Y-m-d', strtotime($service_list['ArrivalTravelInfo']['ArrivalInfo']['DateTime']['@attributes']['date']));
					$travel_time = date('H:i:s', strtotime($service_list['ArrivalTravelInfo']['ArrivalInfo']['DateTime']['@attributes']['time']));
					$transfer_date_time = $travel_date . " " . $travel_time;
				} elseif (isset($service_list['DepartureTravelInfo']['DepartInfo']['DateTime'])) {
					$travel_date = date('Y-m-d', strtotime($service_list['DepartureTravelInfo']['DepartInfo']['DateTime']['@attributes']['date']));
					$travel_time = date('H:i:s', strtotime($service_list['DepartureTravelInfo']['DepartInfo']['DateTime']['@attributes']['time']));
					$transfer_date_time = $travel_date . " " . $travel_time;
				}
				//echo gettype(strtotime($transfer_date_time)); exit;
				/* insert data into `hb_transfer_service_details` */

				$currency_obj = new Currency(array('module_type' => 'car', 'from' => $currency_code, 'to' => get_application_transaction_currency_preference()));
				$deduction_cur_obj = clone $currency_obj;

				if ($module == 'b2c') {
					//Convert to default currency and add mark up
					$transfer_total_fare = $currency_obj->get_currency($service_total_amount, true, false, true, 1); // (ON Total PRICE ONLY)
					//convience fee
					//$currency_obj->getConversionRate(false,get_application_transaction_currency_preference(),get_application_transaction_currency_preference());
					$transfer_fare = $currency_obj->convenience_fees($transfer_total_fare['default_value'], $master_search_id);
					$transfer_fare = $transfer_fare;
				} else {
					// B2B Calculation
					$transfer_total_fare = $currency_obj->get_currency($service_total_amount, true, true, true, 1); // (ON Total PRICE ONLY)
					$cancel_convinence = 0;
				}
				$transfer_total_fare = $transfer_total_fare['default_value'] + $transfer_fare;


				$service_insert_array = array(
						'app_reference' => $booking_id,
						'booking_reference' => $booking_reference_no,
						'transfer_type' => @$transfer_type,
						'service_reference_no' => @$service_booking_reference_no,
						'incoming_office_code' => $incoming_office_code,
						'service_status' => @$service_status,
						'attributes' => $transfer_service_booking_attributes,
						'date_from' => $transfer_date_time,
						'total_amount' => @$service_total_amount,
						'selling_price' => @$transfer_total_fare,
						'transfer_code' => @$transfer_code,
						'description_list' => @$transfer_description_list,
						'transfer_type_code' => @$transfer_type_code,
						'transfer_vehicletype_code' => @$transfer_vehicle_type,
						'transfer_specific_content' => @$transfer_specific_content_id,
						'adult_count' => @$adult_count,
						'child_count' => @$child_count,
						'product_specifications' => @$product_specifications,
						'transfer_pickup_time' => $transfer_pickup_time,
						'transfer_pickup_Information' => @$transfer_pickup_information,
						'arrival_travel_Info' => @$ArrivalTravelInfo,
						'departure_travel_Info' => @$DepartureTravelInfo,
						'image' => $image_url
				);



				$result = $CI->db->insert('hb_transfer_service_details', $service_insert_array);
			}

			/* PaymentData */
			$payment_type_code = @$purchase_details['PaymentData']['PaymentType']['@attributes']['code'];
			$payment_invoice_company_code = @$purchase_details['PaymentData']['InvoiceCompany']['Code'];
			$payment_invoice_company_name = @$purchase_details['PaymentData']['InvoiceCompany']['Name'];
			$payment_invoice_company_name_registration_no = @$purchase_details['PaymentData']['InvoiceCompany']['RegistrationNumber'];
			$payment_description = @$purchase_details['PaymentData']['Description'];

			$total_price = @$purchase_details['TotalPrice'];
			$pending_amount = @$purchase_details['PendingAmount'];
			$currency_code = @$purchase_details['Currency']['@attributes']['code'];
			$booking_details_insert_array = array(
					'app_reference' => $booking_id,
					'booking_reference' => $booking_reference_no,
					'booking_source' => HOTELBED_TRANSFER_BOOKING_SOURCE,
					'purchase_status' => $status,
					'creation_date' => $creation_date,
					'attributes_detail' => json_encode($booking_detail_attribute),
					'retail_price' => @$service_retail_price,
					'pickup_location_details' => $pickup_info,
					'destination_location_details' => $desti_info,
					'pickup_code' => @$pickup_code,
					'pickup_name' => @$pickup_name,
					'pickup_transfer_zone_code' => @$pickup_transfer_zone_code,
					'destination_code' => @$desti_code,
					'destination_name' => @$desti_name,
					'destination_transfer_zone' => @$desti_transfer_zone_code,
					'payment_type_code' => @$payment_type_code,
					'payment_invoice_company_code' => @$payment_invoice_company_code,
					'payment_invoice_company_name' => @$payment_invoice_company_name,
					'payment_invoice_company_name_registration_no' => @$payment_invoice_company_name_registration_no,
					'payment_description' => @$payment_description,
					'total_amount' => @$total_price,
					'pending_amount' => @$pending_amount
			);

			$CI->db->insert('hb_transfer_booking_details', $booking_details_insert_array);



			// Convinence_fees to be stored and discount
			$convinence = 0;
			$discount = 0;
			$convinence_value = 0;
			$convinence_type = 0;
			$convinence_per_pax = 0;



			$total_amt = $purchase_details['TotalPrice'];
			//          debug($total_amt); exit;
			$discount = 0;
			$currency_obj = new Currency(array('module_type' => 'car', 'from' => $currency_code, 'to' => get_application_transaction_currency_preference()));
			$deduction_cur_obj = clone $currency_obj;

			if ($module == 'b2c') {

				//Convert to default currency and add mark up
				$markup_total_fare = $currency_obj->get_currency($total_amt, true, false, true, 1); // (ON Total PRICE ONLY)
				$ded_total_fare = $deduction_cur_obj->get_currency($total_amt, true, true, false, 1); // (ON Total PRICE ONLY)
				$admin_markup = sprintf("%.2f", $markup_total_fare ['default_value'] - $ded_total_fare ['default_value']);
				//$currency_obj->getConversionRate(false,$currency_code,get_application_transaction_currency_preference());
				$total_amt = $currency_obj->get_currency($total_amt, false, false, false, 1);
				$agent_markup = 0;
				$mark_currency = $total_amt['default_currency'];

				//convience fee
				//$currency_obj->getConversionRate(false,get_application_transaction_currency_preference(),get_application_transaction_currency_preference());
				$convinence = $currency_obj->convenience_fees($markup_total_fare['default_value'], $master_search_id);
				$convinence = $convinence;
				$convinence_row = $currency_obj->get_convenience_fees();
				$convinence_value = $convinence_row ['value'];
				$convinence_type = $convinence_row ['type'];
				$convinence_per_pax = $convinence_row ['per_pax'];
			} else {

				// B2B Calculation
				$markup_total_fare = $currency_obj->get_currency($total_amt, true, true, true, 1); // (ON Total PRICE ONLY)
				$ded_total_fare = $deduction_cur_obj->get_currency($total_amt, true, false, true, 1); // (ON Total PRICE ONLY)
				$admin_markup = abs(sprintf("%.2f", $markup_total_fare ['default_value'] - $ded_total_fare ['default_value']));
				$currency_obj->getConversionRate(false, $currency_code, get_application_transaction_currency_preference());
				$total_amt = $currency_obj->get_currency($total_amt, false, false, false, 1);
				$agent_markup = abs(sprintf("%.2f", $ded_total_fare ['default_value'] - $total_amt['default_value']));
				$mark_currency = $total_amt['default_currency'];
				$convinence = 0;
			}


			$markup_total_fare_val = $markup_total_fare['default_value']; //+ $convinence;

			$admin_markup = $transfer_params['token']['admin_markup'];
			$agent_markup = $transfer_params['token']['agent_markup'];
			$AgentNetRate = $transfer_params['token']['AgentNetRate'];
			$AgentServiceTax = $transfer_params['token']['AgentServiceTax'];


			$total_markup_fare = $tot;

			// $total_markup_fare = $tot + $admin_markup + $agent_markup + $convinence_fee;
			//$total_amt['default_value'] + $admin_markup + $agent_markup;
			//exit;
			$created_by_id = intval(@$GLOBALS ['CI']->entity_user_id);
			$address = @$transfer_params['billing_address_1'] . ',' . @$transfer_params['address2'];

			$query = $CI->db->get_where('hb_transfer_booking_transction_details', array(
					'app_reference' => $booking_id
			));
			$count = $query->num_rows();



			switch (@$name_title) {
				case '1':
					$title = 'Mr';
					break;
				case '2':
					$title = 'Ms';
					break;
				case '3':
					$title = 'Miss';
					break;
				case '4':
					$title = 'Mstr';
					break;
				case '5':
					$title = 'Mrs';
					break;
				default:
					break;
			}


			if ($count === 0) {

				$transfer_booking_transction = array(
						'app_reference' => $booking_id,
						'domain_origin' => $domain_origin,
						'booking_source' => HOTELBED_TRANSFER_BOOKING_SOURCE,
						'status' => 'BOOKING_CONFIRMED',
						'creation_date' => $creation_date,
						'creation_time' => date("H:i:s"),
						'creation_user' => $creation_user,
						'created_by_id' => $created_by_id,
						'payment_mode' => @$transfer_params['payment_method'],
						'convinence_value' => $convinence_fee,
						'service_tax' => $service_tax,
						'AgentServiceTax' => $AgentServiceTax,
						'convinence_value_type' => 'plus', //  $convinence_type
						'convinence_per_pax' => $convinence_fee,
						'discount' => $discount,
						'total_amt' => ($total_markup_fare + $convinence_fee),
						'total_amt_with_service_tax' => $total_amt_with_service_tax,
						'total_markup_fare' => $total_markup_fare,
						'agent_markup' => $agent_markup,
						'markup_currency' => @$transfer_params['token']['currency_code'],
						'admin_markup' => $admin_markup,
						'convenience' => $convinence_fee,
						'net_total' => @$AgentNetRate,
						'AgentNetPrice' => @$AgentNetRate,
						'title' => $title,
						'first_name' => @$transfer_params['first_name'],
						'last_name' => @$transfer_params['last_name'],
						'address' => $address,
						'billing_email' => $transfer_params['billing_email'],
						'country' => @$transfer_params['billing_country'],
						'contact_no' => $transfer_params['passenger_contact'],
						'currency' => @$transfer_params['token']['currency_code'],
						'arrival_from' => @$transfer_params['transfer_from'],
						'arrival_loc_id' => @$transfer_params['from_loc_id'],
						'travel_company_name' => @$transfer_params['travel_company_name'],
						'travel_number' => @$transfer_params['travel_number'],
						'arrival_time' => @$transfer_params['travel_time'],
						'departure_from' => @$transfer_params['transfer_to'],
						'departure_loc_id' => @$transfer_params['to_loc_id'],
						'departure_travel_company_name' => @$transfer_params['arrival_travel_company_name'],
						'departure_travel_number' => @$transfer_params['arrival_travel_number'],
						'departure_time' => @$transfer_params['arrival_travel_time'],
						'comment' => @$transfer_params['comment'],
						'additional_comments' => @$transfer_params['additional_comments'],
						'customer_waiting_time' => $customer_waiting_time
				);

				$CI->db->insert('hb_transfer_booking_transction_details', $transfer_booking_transction);
			} else {
				$transction_details = $query->row();
				$total_amount_trnasc = $transction_details->total_amt;
				$total_markup_fare = $transction_details->total_markup_fare;
				$net_total = $transction_details->net_total + $purchase_details['TotalPrice'];
				$convenience = $transction_details->convenience;
				$transfer_booking_transction = array(
						'total_amt' => $total_amount_trnasc,
						'total_markup_fare' => $total_markup_fare,
						'net_total' => $net_total,
						'convenience' => $convinence_fee
				);

				$CI->db->where('app_reference', $booking_id);
				$CI->db->update('hb_transfer_booking_transction_details', $transfer_booking_transction);
			}
			/* transction completed */
			$CI->db->trans_complete();
		}
	}

	/*
	 * cache_transfer_details
	 * */

	public function cache_transfer_details(& $transfer_details) {
		$token = array();
		$this->ins_token_file = time() . rand(100, 10000);
		$trasfer_code = '';
		if (isset($transfer_details['purchase_details']) && valid_array($transfer_details['purchase_details'])) {
			foreach ($transfer_details['purchase_details'] as $td_key => $token) {
				$trasfer_code .= isset($token['purchase_token']) && !empty($token['purchase_token']) ? '_' . $token['purchase_token'] : $token['purchase_token'];
			}
		}

		$tkn_key = $trasfer_code;
		$this->push_token($transfer_details, $token, $tkn_key);
		$this->save_token($token);
	}

	/**
	 * adds token and token key to flight and push data to token for caching
	 * @param array $hotel_room_data    Flight for which token and token key has to be generated
	 * @param array $token  Token array for caching
	 * @param string $key   Key to be used for caching
	 */
	private function push_token(& $transfer_data, & $token, $key) {
		//push data inside token before adding token and key values
		$token[$key] = $transfer_data;

		//Adding token and token key
		$transfer_data['Token'] = serialized_data($this->ins_token_file . DB_SAFE_SEPARATOR . $key);
		$transfer_data['TokenKey'] = md5($transfer_data['Token']);
	}

	/**
	 * Save token and cache the data
	 * @param array $token
	 */
	private function save_token($token) {
		$file = DOMAIN_TMP_UPLOAD_DIR . $this->ins_token_file . '.json';
		file_put_contents($file, json_encode($token));
	}

	public function read_token($token_key) {
		$token_key = explode(DB_SAFE_SEPARATOR, unserialized_data($token_key));

		if (valid_array($token_key) == true) {
			$file = DOMAIN_TMP_UPLOAD_DIR . $token_key[0] . '.json'; //File name
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

	/*
	 public function formate_booking_response_data($transfer_booking_response,$transfer_params){
	 $booking_reponse['purchase_token'] = $transfer_booking_response['Purchase']['@attributes']['purchaseToken'];
	 $booking_reponse['purchase_token_expiration'] = $transfer_booking_response['Purchase']['@attributes']['timeToExpiration'];
	 $booking_reponse['file_number_1'] = $transfer_booking_response['Purchase']['Reference']['FileNumber'];
	 $booking_reponse['incoming_office_code'] = $transfer_booking_response['Purchase']['Reference']['IncomingOffice']['@attributes']['code'];
	 $booking_reponse['purchase_status'] = $transfer_booking_response['Purchase']['Status'];
	 $booking_reponse['agency_code'] = $transfer_booking_response['Purchase']['Agency']['Code'];
	 $booking_reponse['agency_branch'] = $transfer_booking_response['Purchase']['Agency']['Branch'];
	 $booking_reponse['language'] = $transfer_booking_response['Purchase']['Language'];
	 $booking_reponse['creation_date'] = $transfer_booking_response['Purchase']['CreationDate']['@attributes']['date'];
	 $booking_reponse['creation_user'] = $transfer_booking_response['Purchase']['CreationUser'];
	 $booking_reponse['holder_type'] = $transfer_booking_response['Purchase']['Holder']['@attributes']['type'];
	 $booking_reponse['holder_age'] = $transfer_booking_response['Purchase']['Holder']['Age'];
	 $booking_reponse['holder_first_name'] = $transfer_booking_response['Purchase']['Holder']['Name'];
	 $booking_reponse['holder_last_name'] = $transfer_booking_response['Purchase']['Holder']['LastName'];
	 $booking_reponse['agency_reference'] = $transfer_booking_response['Purchase']['AgencyReference'];
	 $booking_reponse['transfer_type'] = $transfer_booking_response['Purchase']['ServiceList']['Service']['@attributes']['transferType'];
	 $booking_reponse['SPUI'] = $transfer_booking_response['Purchase']['ServiceList']['Service']['@attributes']['SPUI'];
	 $booking_reponse['file_number_2'] = $transfer_booking_response['Purchase']['ServiceList']['Service']['Reference']['FileNumber'];
	 $booking_reponse['status'] = $transfer_booking_response['Purchase']['ServiceList']['Service']['Status'];
	 $booking_reponse['contract_name'] = $transfer_booking_response['Purchase']['ServiceList']['Service']['ContractList']['Contract']['Name'];
	 $booking_reponse['contract_sequence'] = $transfer_booking_response['Purchase']['ServiceList']['Service']['ContractList']['Contract']['Sequence'];
	 $booking_reponse['supplier_name'] = $transfer_booking_response['Purchase']['ServiceList']['Service']['Supplier']['@attributes']['name'];
	 $booking_reponse['supplier_vat_number'] = $transfer_booking_response['Purchase']['ServiceList']['Service']['Supplier']['@attributes']['vatNumber'];
	 $booking_reponse['comment_list'] = $transfer_booking_response['Purchase']['ServiceList']['Service']['CommentList']['Comment'];
	 $booking_reponse['date_from'] = $transfer_booking_response['Purchase']['ServiceList']['Service']['DateFrom']['@attributes']['date'];
	 $booking_reponse['currency'] = $transfer_booking_response['Purchase']['ServiceList']['Service']['Currency'];
	 $booking_reponse['total_amount'] = $transfer_booking_response['Purchase']['ServiceList']['Service']['TotalAmount'];
	 $booking_reponse['selling_price'] = $transfer_booking_response['Purchase']['ServiceList']['Service']['SellingPrice'];

	 // for Additional Cost List
	 //$booking_reponse['holder_type'] = $transfer_booking_response['Purchase']['Holder']['@attributes']['type'];
	 } */

	/*
	 * Cancel booking in transfer
	 */

	public function cancel_booking_old($booking_details) {
		//debug($booking_details); exit;
		$response ['data'] = array();
		$transfer_cancel_response_val = array();
		$response ['status'] = FAILURE_STATUS;
		$resposne ['msg'] = 'Remote IO Error';
		$BookingId = $booking_details ['booking_source'];
		$app_reference = $booking_details ['app_reference'];
		$status = $booking_details['status'];
		$strFpath = "";
        /*if($strIsb2c == 0){
            $strFpath = "../";
        }*/
		if ($status == true) {
			$transfer_cancel_val = $this->transfer_cancel_request($booking_details);
			if ($transfer_cancel_val['status']) {
				$url = $this->service_url;

				foreach ($transfer_cancel_val['request'] as $cancel_request_k => $cancel_request_v) {
					$transfer_cancel_response_val[$cancel_request_k] = $GLOBALS ['CI']->api_interface->xml_post_request($url, $cancel_request_v, $this->xml_header());
					// $transfer_cancel_response = $GLOBALS['CI']->custom_db->single_table_records('test','test',array('origin'=>1224));
					// $transfer_cancel_response_val[$cancel_request_k] = $transfer_cancel_response['data'][0]['test'];
					// debug($transfer_cancel_response);exit;
					// $transfer_cancel_response_val[$cancel_request_k] = json_decode($transfer_cancel_response['data'][0]['test'], true);
					// debug($transfer_cancel_response_val);exit;
					/*$path = FCPATH . "all_xml_logs/transfer/hb/cancel/TransferCancelRS_" . $cancel_request_k . date('Y_m_d_H_i_s') . ".xml";
					$fp = fopen($path, "wb");
					fwrite($fp, $transfer_cancel_response_val[$cancel_request_k]);
					fclose($fp);*/
					// $this->CI->custom_db->generate_static_response($transfer_cancel_response_val[$cancel_request_k], 'transfer cancel response', 'hotelbeds_transfer');
					// $path = $strFpath. "all_xml_logs/transfer/hb/cancel/TransferCancelRS_" . $cancel_request_k . date('Y_m_d_H_i_s') . ".xml";
	    			// $fp = fopen($path,"wb");fwrite($fp,$transfer_cancel_response_val[$cancel_request_k]);fclose($fp);
				}
				// debug($transfer_cancel_response_val);exit;
				if (isset($transfer_cancel_response_val) && !empty($transfer_cancel_response_val)) {
					$response['data'] = $transfer_cancel_response_val;
				} else {
					$status = false;
				}
			} else {
				$status = false;
			}
		} else {
			$response ['msg'] = $status;
		}
		$response['status'] = $status;
		return $response;
	}

	/*
	 * transfer cancel request
	 */

	public function transfer_cancel_request_old($booking_details) {
		$transfer_cancel = array();
		$request_array = array();
		$request = array();
		$response = array();
		if (isset($booking_details['booking_tarnsfers_service_details']) && valid_array($booking_details['booking_tarnsfers_service_details'])) {
			foreach ($booking_details['booking_tarnsfers_service_details'] as $service_details_k => $service_details_v) {
				$transfer_cancel[$service_details_k]['booking_no'] = $booking_details['booking_tarnsfers_service_details'][$service_details_k]['booking_reference'];
				$transfer_cancel[$service_details_k]['incoming_office_code'] = $booking_details['booking_tarnsfers_service_details'][$service_details_k]['incoming_office_code'];
			}
		}
		if (isset($transfer_cancel) && valid_array($transfer_cancel)) {
			foreach ($transfer_cancel as $transfer_cancel_key => $transfer_cancel_val) {
				$request = '<PurchaseCancelRQ echoToken="DummyEchoToken" type="C" xmlns="http://api.interface-xml.com/schemas/2005/06/messages" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://api.interface-xml.com/schemas/2005/06/messages PurchaseDetailRQ.xsd" version="2013/12">
                        <Language>ENG</Language>
                        <Credentials>
                        <User>' . $this->username . '</User>
                        <Password>' . $this->password . '</Password>
                        </Credentials>
                        <PurchaseReference>
                        <FileNumber>' . $transfer_cancel_val['booking_no'] . '</FileNumber>
                        <IncomingOffice code="' . $transfer_cancel_val['incoming_office_code'] . '"/>
                        </PurchaseReference>
                        </PurchaseCancelRQ>';
				$request_array[$transfer_cancel_key] = $request;
				//  header("Content-type: text/xml");
				//header('Content-Type: application/json');
				// print_r($request); exit;
			}
			$response['request'] = $request_array;
			$response['status'] = true;

			return $response;
		}
	}



	/*
	 * Cancel booking in transfer
	 */

	public function cancel_booking($booking_details) {
		// debug($booking_details); exit;
		$response ['data'] = array();
		$transfer_cancel_response_val = array();
		$response ['status'] = FAILURE_STATUS;
		$resposne ['msg'] = 'Remote IO Error';
		$BookingId = $booking_details ['booking_source'];
		$app_reference = $booking_details ['app_reference'];
		$status = $booking_details['status'];
		$strFpath = "";
        if($strIsb2c == 0){
            $strFpath = "../";
        }
		if ($status == true) {
			$transfer_cancel_val = $this->transfer_cancel_request($booking_details);
			
			$cancel_request_json = json_encode($transfer_cancel_val['request']);
			$this->CI->custom_db->generate_static_response($cancel_request_json, 'transfer cancel request', 'hotelbeds_transfer');
			// debug($transfer_cancel_val); exit;
			if ($transfer_cancel_val['status']) {
				$url = $this->service_url;

				foreach ($transfer_cancel_val['request'] as $cancel_request_k => $cancel_request_v) {
					$transfer_cancel_response_val[$cancel_request_k] = $GLOBALS ['CI']->api_interface->xml_post_request($url, $cancel_request_v, $this->xml_header());

					$this->CI->custom_db->generate_static_response(json_encode($transfer_cancel_response_val[$cancel_request_k]), 'transfer cancel response', 'hotelbeds_transfer');

					// $transfer_cancel_response = $GLOBALS['CI']->custom_db->single_table_records('test','test',array('origin'=>539));
					// $transfer_cancel_response_val[$cancel_request_k] = json_decode($transfer_cancel_response['data'][0]['test'],true);
					 // debug($transfer_cancel_response_val);exit;
					
					$path = $strFpath . "all_xml_logs/transfer/hb/cancel/TransferCancelRQ_" . $cancel_request_k . date('Y_m_d_H_i_s') . ".xml";
					// debug($path);
					$fp = fopen($path, "wb");
					fwrite($fp, $cancel_request_v);
					fclose($fp);
					

					 $this->CI->custom_db->generate_static_response($transfer_cancel_response_val[$cancel_request_k], 'transfer cancel response', 'hotelbeds_transfer');
					 $path = $strFpath. "all_xml_logs/transfer/hb/cancel/TransferCancelRS_" . $cancel_request_k . date('Y_m_d_H_i_s') . ".xml";
	    			 $fp = fopen($path,"wb");fwrite($fp,$transfer_cancel_response_val[$cancel_request_k]);fclose($fp);

				}
				// debug($transfer_cancel_response_val);exit;
				if (isset($transfer_cancel_response_val) && !empty($transfer_cancel_response_val)) {
					$response['data'] = $transfer_cancel_response_val;
				} else {
					$status = false;
				}
			} else {
				$status = false;
			}
		} else {
			$response ['msg'] = $status;
		}
		$response['status'] = $status;
		// debug($response); exit;
		return $response;
	}

	/*
	 * transfer cancel request
	 */

	public function transfer_cancel_request($booking_details) {
		$transfer_cancel = array();
		$request_array = array();
		$request = array();
		$response = array();

		$booking_attributes = json_decode($booking_details['attributes'],true);
		
		/*if (isset($booking_details['booking_tarnsfers_service_details']) && valid_array($booking_details['booking_tarnsfers_service_details'])) {
			foreach ($booking_details['booking_tarnsfers_service_details'] as $service_details_k => $service_details_v) {
				$transfer_cancel[$service_details_k]['booking_no'] = $booking_details['booking_tarnsfers_service_details'][$service_details_k]['booking_reference'];
				$transfer_cancel[$service_details_k]['incoming_office_code'] = $booking_details['booking_tarnsfers_service_details'][$service_details_k]['incoming_office_code'];
			}
		}*/

		// debug($booking_details);
		if(isset($booking_details) && valid_array($booking_details)){

			$resp=explode('-', $booking_details['booking_reference']);
			$transfer_cancel[] = array(
				'incoming_office_code' => $resp[0],
				'booking_no' => $resp[1]
			); 
		}
		// debug($transfer_cancel); exit;
		if (isset($transfer_cancel) && valid_array($transfer_cancel)) {
			foreach ($transfer_cancel as $transfer_cancel_key => $transfer_cancel_val) {
				$request = '<PurchaseCancelRQ echoToken="DummyEchoToken" type="C" xmlns="http://api.interface-xml.com/schemas/2005/06/messages" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://api.interface-xml.com/schemas/2005/06/messages PurchaseDetailRQ.xsd" version="2013/12">
                        <Language>ENG</Language>
                        <Credentials>
                        <User>' . $this->username . '</User>
                        <Password>' . $this->password . '</Password>
                        </Credentials>
                        <PurchaseReference>
                        <FileNumber>' . $transfer_cancel_val['booking_no'] . '</FileNumber>
                        <IncomingOffice code="' . $transfer_cancel_val['incoming_office_code'] . '"/>
                        </PurchaseReference>
                        </PurchaseCancelRQ>';
				$request_array[$transfer_cancel_key] = $request;
				//  header("Content-type: text/xml");
				//header('Content-Type: application/json');
				// print_r($request); exit;
			}
			$response['request'] = $request_array;
			$response['status'] = true;
			// debug($response); exit;
			return $response;
		}
	}


	/*
	 * Pre Cancel Transfer Data
	 */

	public function pre_cancel_booking($page_data) {

		$response ['data'] = array();
		$transfer_cancel_response_val = array();
		$response ['status'] = FAILURE_STATUS;
		$resposne ['msg'] = 'Remote IO Error';
		$BookingId = $page_data ['booking_source'];
		$app_reference = $page_data ['app_reference'];
		$status = $page_data['status'];

		$strFpath = "";
        if($strIsb2c == 0){
            $strFpath = "../";
        }

		if ($status == true) {
			$transfer_pre_cancel_val = $this->pre_cancel_request($page_data);
			// debug($transfer_pre_cancel_val);exit;
			if ($transfer_pre_cancel_val['status']) {
				$url = $this->service_url;
				foreach ($transfer_pre_cancel_val['request'] as $cancel_request_k => $cancel_request_v) {
					$transfer_pre_cancel_result[$cancel_request_k] = $GLOBALS ['CI']->api_interface->xml_post_request($url, $cancel_request_v, $this->xml_header());
					// debug($transfer_pre_cancel_result);exit;
					$this->CI->custom_db->generate_static_response($transfer_pre_cancel_result[$cancel_request_k], 'transfer pree cancel response', 'hotelbeds_transfer');
					/*$path = FCPATH . "all_xml_logs/transfer/hb/cancel/TransferPreCancelRS_" . $cancel_request_k . date('Y_m_d_H_i_s') . ".xml";
					$fp = fopen($path, "wb");
					fwrite($fp, $transfer_pre_cancel_result[$cancel_request_k]);
					fclose($fp);*/
					// $path = $strFpath. "all_xml_logs/transfer/hb/cancel/TransferPreCancelRS_" . $cancel_request_k . date('Y_m_d_H_i_s') . ".xml";
	    //             $fp = fopen($path,"wb");fwrite($fp,$transfer_pre_cancel_result[$cancel_request_k]);fclose($fp);
				}
				// debug($transfer_pre_cancel_result);exit;
				if (isset($transfer_pre_cancel_result) && !empty($transfer_pre_cancel_result)) {
					$response['data'] = $transfer_pre_cancel_result;
				} else {
					$status = false;
				}
			} else {
				$status = false;
			}
		}
		$response['status'] = $status;
		// debug($response);exit;
		return $response;
	}

	/*
	 * Pre Cancel Transfer Data
	 */

	public function pre_cancel_request($page_data) {

		$transfer_pre_cancel = array();
		$request_pre_array = array();
		$request = array();
		$response = array();
		if (isset($page_data['booking_tarnsfers_service_details']) && valid_array($page_data['booking_tarnsfers_service_details'])) {
			foreach ($page_data['booking_tarnsfers_service_details'] as $service_details_k => $service_details_v) {
				$transfer_pre_cancel[$service_details_k]['booking_no'] = $page_data['booking_tarnsfers_service_details'][$service_details_k]['booking_reference'];
				$transfer_pre_cancel[$service_details_k]['incoming_office_code'] = $page_data['booking_tarnsfers_service_details'][$service_details_k]['incoming_office_code'];
			}
		}
		if (isset($transfer_pre_cancel) && valid_array($transfer_pre_cancel)) {
			foreach ($transfer_pre_cancel as $transfer_pre_cancel_key => $transfer_pre_cancel_val) {
				$request = '<PurchaseCancelRQ echoToken="DummyEchoToken" type="V" xmlns="http://api.interface-xml.com/schemas/2005/06/messages" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://api.interface-xml.com/schemas/2005/06/messages PurchaseDetailRQ.xsd" version="2013/12">
                        <Language>ENG</Language>
                        <Credentials>
                        <User>' . $this->username . '</User>
                        <Password>' . $this->password . '</Password>
                        </Credentials>
                        <PurchaseReference>
                        <FileNumber>' . $transfer_pre_cancel_val['booking_no'] . '</FileNumber>
                        <IncomingOffice code="' . $transfer_pre_cancel_val['incoming_office_code'] . '"/>
                        </PurchaseReference>
                        </PurchaseCancelRQ>';
				//header("Content-type: text/xml");
				//header('Content-Type: application/json');
				//print_r($request); exit;

				$request_pre_array[$transfer_pre_cancel_key] = $request;
			}

			$response['request'] = $request_pre_array;
			$response['status'] = true;
			return $response;
		}
	}

	/*
	 * Formate and save Cancel Transfer Data
	 */

	public function formate_cancel_data($app_reference, $booking_source, $transfer) {
		//$CI = &get_instance();
		$result = array();
		foreach ($transfer as $transfer_key => $transfer_val) {
			$cancel_detail_val = array();
			if (isset($transfer_val['Purchase']) && !empty($transfer_val['Purchase'])) {
				//debug($transfer_val['Purchase']['Reference']['FileNumber']);
				$cancel_detail_val['app_reference'] = $app_reference;
				$cancel_detail_val['booking_source'] = $booking_source;
				if (isset($transfer_val['Purchase']['Reference']['FileNumber']) && !empty($transfer_val['Purchase']['Reference']['FileNumber'])) {
					$cancel_detail_val['booking_file_number'] = $transfer_val['Purchase']['Reference']['FileNumber'];
				}
				if (isset($transfer_val['Purchase']['Reference']['IncomingOffice']['@attributes']['code']) && !empty($transfer_val['Purchase']['Reference']['IncomingOffice']['@attributes']['code'])) {
					$cancel_detail_val['incoming_office_code'] = @$transfer_val['Purchase']['Reference']['IncomingOffice']['@attributes']['code'];
				}
				if (isset($transfer_val['Purchase']['Status']) && !empty($transfer_val['Purchase']['Status'])) {
					$cancel_detail_val['cancel_status'] = @$transfer_val['Purchase']['Status'];
				}
				if (isset($transfer_val['Purchase']['Agency']) && valid_array($transfer_val['Purchase']['Agency'])) {
					$cancel_detail_val['agency'] = json_encode($transfer_val['Purchase']['Agency']);
				}
				if (isset($transfer_val['Purchase']['CreationDate']['@attributes']['date']) && !empty($transfer_val['Purchase']['CreationDate']['@attributes']['date'])) {
					$cancel_detail_val['creation_date'] = $transfer_val['Purchase']['CreationDate']['@attributes']['date'];
				}
				if (isset($transfer_val['Purchase']['CreationUser']) && !empty($transfer_val['Purchase']['CreationUser'])) {
					$cancel_detail_val['creation_user'] = @$transfer_val['Purchase']['CreationUser'];
				}
				if (isset($transfer_val['Purchase']['Holder']) && valid_array($transfer_val['Purchase']['Holder'])) {
					$cancel_detail_val['holder'] = json_encode($transfer_val['Purchase']['Holder']);
				}
				if (isset($transfer_val['Purchase']['ServiceList']['Service']['CancellationPolicies']) && valid_array($transfer_val['Purchase']['ServiceList']['Service']['CancellationPolicies'])) {
					$cancel_detail_val['cancellation_policies'] = json_encode($transfer_val['Purchase']['ServiceList']['Service']['CancellationPolicies']);
				}
				if (isset($transfer_val['Purchase']['PaymentData']) && valid_array($transfer_val['Purchase']['PaymentData'])) {
					$cancel_detail_val['payment_data'] = json_encode($transfer_val['Purchase']['PaymentData']);
				}
				if (isset($transfer_val['Purchase']['TotalPrice']) && !empty($transfer_val['Purchase']['TotalPrice'])) {
					$cancel_detail_val['total_price'] = @$transfer_val['Purchase']['TotalPrice'];
				}
				if (isset($transfer_val['Currency']) && !empty($transfer_val['Currency'])) {
					$cancel_detail_val['currency'] = @$transfer_val['Currency']['@attributes']['code'];
				}
				if (isset($transfer_val['Amount']) && !empty($transfer_val['Amount'])) {
					$cancel_detail_val['cancellation_amount'] = @$transfer_val['Amount'];
				}
			}

			/* $created_by_id = intval ( @$GLOBALS ['CI']->entity_user_id );
			 $query = $CI->db->get_where('hb_transfer_booking_transction_details', array(
			 'app_reference' => $app_reference
			 ));
			 $count = $query->num_rows();

			 $transfer_booking_transction = array(
			 'app_reference' => $app_reference,
			 'domain_origin' => $domain_origin,
			 'booking_source' => PROVAB_TRANSFERS_BOOKING_SOURCE,
			 'status' => 'BOOKING_CONFIRMED',
			 'creation_date' => $creation_date,
			 //'creation_time' => date("H:i:s"),

			 );
			 $CI->db->insert('hb_transfer_booking_transction_details',$transfer_booking_transction);


			 //$transfer_val['Purchase']; */
			$data[$transfer_key] = $cancel_detail_val;
		}
		$result['data'] = $data;
		return $result;
		//debug($cancel_detail_val); exit;
	}

	/*
	 * Formate and save Cancel Transfer Data
	 */

	public function save_cancel_data($app_reference, $booking_source, $transfer_cancel_val, $master_booking_details = array()) {
		// debug($transfer_cancel_val);exit;
		$CI = &get_instance();
		$result = array();
		$msg = '';
		foreach ($transfer_cancel_val as $transfer_cancel_val_k => $transfer_cancel_val_v) {
			// debug($transfer_cancel_val_v);exit;
			$cancel_detail_val = array();
			if (isset($transfer_cancel_val_v['booking']) && !empty($transfer_cancel_val_v['booking'])) {
				//debug($transfer_cancel_val_v['Purchase']['Reference']['FileNumber']);
				if (isset($transfer_cancel_val_v['booking']['reference']) && !empty($$transfer_cancel_val_v['booking']['reference'])) {
					$booking_file_number = $transfer_cancel_val_v['booking']['reference'];
				}
				if (isset($transfer_cancel_val_v['Purchase']['Reference']['IncomingOffice']['@attributes']['code']) && !empty($transfer_cancel_val_v['Purchase']['Reference']['IncomingOffice']['@attributes']['code'])) {
					$incoming_office_code = @$transfer_cancel_val_v['Purchase']['Reference']['IncomingOffice']['@attributes']['code'];
				}
				if (isset($transfer_cancel_val_v['booking']['status']) && !empty($transfer_cancel_val_v['booking']['status'])) {
					$cancel_status = @$transfer_cancel_val_v['booking']['status'];
				}
				if (isset($transfer_cancel_val_v['Purchase']['Agency']) && valid_array($transfer_cancel_val_v['Purchase']['Agency'])) {
					$agency = json_encode($transfer_cancel_val_v['Purchase']['Agency']);
				}
				if (isset($transfer_cancel_val_v['booking']['creationDate']) && !empty($transfer_cancel_val_v['booking']['creationDate'])) {
					$creation_date = $transfer_cancel_val_v['booking']['creationDate'];
				}
				if (isset($transfer_cancel_val_v['booking']['clientReference']) && !empty($transfer_cancel_val_v['booking']['clientReference'])) {
					$creation_user = @$transfer_cancel_val_v['booking']['clientReference'];
				}
				if (isset($transfer_cancel_val_v['Purchase']['Holder']) && valid_array($transfer_cancel_val_v['Purchase']['Holder'])) {
					$holder = json_encode($transfer_cancel_val_v['Purchase']['Holder']);
				}
				if (isset($transfer_cancel_val_v['Purchase']['ServiceList']['Service']['CancellationPolicies']) && valid_array($transfer_cancel_val_v['Purchase']['ServiceList']['Service']['CancellationPolicies'])) {
					$cancellation_policies = json_encode($transfer_cancel_val_v['Purchase']['ServiceList']['Service']['CancellationPolicies']);
				}
				if (isset($transfer_cancel_val_v['Purchase']['PaymentData']) && valid_array($transfer_cancel_val_v['Purchase']['PaymentData'])) {
					$payment_data = json_encode($transfer_cancel_val_v['Purchase']['PaymentData']);
				}
				if (isset($transfer_cancel_val_v['Purchase']['TotalPrice']) && !empty($transfer_cancel_val_v['Purchase']['TotalPrice'])) {
					$total_price = @$transfer_cancel_val_v['Purchase']['TotalPrice'];
				}
				if (isset($transfer_cancel_val_v['booking']['currency']) && !empty($transfer_cancel_val_v['booking']['currency'])) {
					$currency = @$transfer_cancel_val_v['booking']['currency'];
				}
				if (isset($transfer_cancel_val_v['booking']['cancelValuationAmount']) && !empty($transfer_cancel_val_v['booking']['cancelValuationAmount'])) {
					$cancellation_amount = @$transfer_cancel_val_v['booking']['cancelValuationAmount'];
				}
			}



			$currency_obj = new Currency(array('module_type' => 'car', 'from' => $currency, 'to' => get_application_default_currency()));
			$cancel_cur_obj = clone $currency_obj;
			$APIAmountC = $currency_obj->get_currency(@$total_price, false, false, false, false, 1);
			$APICanAmount = $currency_obj->get_currency(@$cancellation_amount, false, false, false, false, 1);
			# Simple Calculation for Adding Admin Markup and Service Tax

			$CanRefundAmount = $APIAmountC['default_value'] + @$$master_booking_details['data']['booking_details'][0]['admin_markup'] + @$$master_booking_details['data']['booking_details'][0]['service_tax'];
			$CanCharges = $APICanAmount['default_value'];

			// echo $cancel_status;exit;
			$domain_origin = get_domain_auth_id();
			$created_by_id = intval(@$GLOBALS ['CI']->entity_user_id);
			$query = $CI->db->get_where('hb_transfer_cancellation_details', array(
					'app_reference' => $app_reference,
					'transfers_type' => $cancel_status
			));
			$count = $query->num_rows();
			if(isset($booking_file_number))
			{
				$booking_file_number = $booking_file_number;
			}else{
				$booking_file_number = "";
			}
			if ($count === 0) {
				$transfer_cancel_transction = array(
						'app_reference' => $app_reference,
						'domain_origin' => $domain_origin,
						'booking_reference' => @$booking_file_number,
						'booking_source' => HOTELBED_TRANSFER_BOOKING_SOURCE,
						'transfers_type' => @$transfer_cancel_val_k,
						'total_amount' => @$CanRefundAmount,
						'cancellation_amount' => @$CanCharges,
						'currency_code' => @$APICanAmount['default_currency'],
						'cancellation_status' => @$cancel_status,
						'cancellation_date' => @$creation_date,
						'creation_user' => @$creation_user,
						'holder' => @$holder,
						'payment_data' => @$payment_data,
						'agency' => @$agency,
						'incoming_office_code' => @$incoming_office_code,
						'cancellation_polices' => @$cancellation_policies,
						//  'XMLCanResponse' => json_encode($transfer_cancel_val),
						'cancellation_date_time' => date('Y-m-d H:i:s')
				);


				$CI->db->insert('hb_transfer_cancellation_details', $transfer_cancel_transction);
				//  debug($master_booking_details);
				//  debug($transfer_cancel_transction);exit;
				if ($CI->db->affected_rows() > 0) {
					$msg = true;
				} else {
					$msg = false;
				}
			} else {
				$booking_details_data = array('cancellation_status' => $cancel_status);
				$CI->db->update('hb_transfer_cancellation_details', $booking_details_data, array('app_reference' => $app_reference, 'booking_reference' => $booking_file_number));
				if ($CI->db->affected_rows() > 0) {
					$msg = true;
				} else {
					$msg = false;
				}
			}
			if ($msg == true) {
				$booking_details_data = array('purchase_status' => $cancel_status);
				$CI->db->update('hb_transfer_booking_details', $booking_details_data, array('app_reference' => $app_reference, 'booking_reference' => $booking_file_number));

				$booking_transction_data = array('status' => $cancel_status);
				$CI->db->update('hb_transfer_booking_transction_details', $booking_transction_data, array('app_reference' => $app_reference));

				$service_details_data = array('service_status' => $cancel_status);
				$CI->db->update('hb_transfer_service_details', $service_details_data, array('app_reference' => $app_reference, 'booking_reference' => $booking_file_number));
			}
		}
		$result['status'] = $cancel_status;
		$result['msg'] = $msg;
		return $result;
		//debug($cancel_detail_val); exit;
	}
	public function save_cancel_data_admin($app_reference, $booking_source, $transfer_cancel_val, $master_booking_details = array()) {
		$CI = &get_instance();
		$result = array();
		$msg = '';
		foreach ($transfer_cancel_val as $transfer_cancel_val_k => $transfer_cancel_val_v) {
			$cancel_detail_val = array();
			if (isset($transfer_cancel_val_v['Purchase']) && !empty($transfer_cancel_val_v['Purchase'])) {
				//debug($transfer_cancel_val_v['Purchase']['Reference']['FileNumber']);
				if (isset($transfer_cancel_val_v['Purchase']['Reference']['FileNumber']) && !empty($transfer_cancel_val_v['Purchase']['Reference']['FileNumber'])) {
					$booking_file_number = $transfer_cancel_val_v['Purchase']['Reference']['FileNumber'];
				}
				if (isset($transfer_cancel_val_v['Purchase']['Reference']['IncomingOffice']['@attributes']['code']) && !empty($transfer_cancel_val_v['Purchase']['Reference']['IncomingOffice']['@attributes']['code'])) {
					$incoming_office_code = @$transfer_cancel_val_v['Purchase']['Reference']['IncomingOffice']['@attributes']['code'];
				}
				if (isset($transfer_cancel_val_v['Purchase']['Status']) && !empty($transfer_cancel_val_v['Purchase']['Status'])) {
					$cancel_status = @$transfer_cancel_val_v['Purchase']['Status'];
				}
				if (isset($transfer_cancel_val_v['Purchase']['Agency']) && valid_array($transfer_cancel_val_v['Purchase']['Agency'])) {
					$agency = json_encode($transfer_cancel_val_v['Purchase']['Agency']);
				}
				if (isset($transfer_cancel_val_v['Purchase']['CreationDate']['@attributes']['date']) && !empty($transfer_cancel_val_v['Purchase']['CreationDate']['@attributes']['date'])) {
					$creation_date = $transfer_cancel_val_v['Purchase']['CreationDate']['@attributes']['date'];
				}
				if (isset($transfer_cancel_val_v['Purchase']['CreationUser']) && !empty($transfer_cancel_val_v['Purchase']['CreationUser'])) {
					$creation_user = @$transfer_cancel_val_v['Purchase']['CreationUser'];
				}
				if (isset($transfer_cancel_val_v['Purchase']['Holder']) && valid_array($transfer_cancel_val_v['Purchase']['Holder'])) {
					$holder = json_encode($transfer_cancel_val_v['Purchase']['Holder']);
				}
				if (isset($transfer_cancel_val_v['Purchase']['ServiceList']['Service']['CancellationPolicies']) && valid_array($transfer_cancel_val_v['Purchase']['ServiceList']['Service']['CancellationPolicies'])) {
					$cancellation_policies = json_encode($transfer_cancel_val_v['Purchase']['ServiceList']['Service']['CancellationPolicies']);
				}
				if (isset($transfer_cancel_val_v['Purchase']['PaymentData']) && valid_array($transfer_cancel_val_v['Purchase']['PaymentData'])) {
					$payment_data = json_encode($transfer_cancel_val_v['Purchase']['PaymentData']);
				}
				if (isset($transfer_cancel_val_v['Purchase']['TotalPrice']) && !empty($transfer_cancel_val_v['Purchase']['TotalPrice'])) {
					$total_price = @$transfer_cancel_val_v['Purchase']['TotalPrice'];
				}
				if (isset($transfer_cancel_val_v['Currency']) && !empty($transfer_cancel_val_v['Currency'])) {
					$currency = @$transfer_cancel_val_v['Currency']['@attributes']['code'];
				}
				if (isset($transfer_cancel_val_v['Amount']) && !empty($transfer_cancel_val_v['Amount'])) {
					$cancellation_amount = @$transfer_cancel_val_v['Amount'];
				}
			}



			$currency_obj = new Currency(array('module_type' => 'transfer', 'from' => $currency, 'to' => get_application_default_currency()));
			$cancel_cur_obj = clone $currency_obj;
			$APIAmountC = $currency_obj->get_currency(@$total_price, false, false, false, false, 1);
			$APICanAmount = $currency_obj->get_currency(@$cancellation_amount, false, false, false, false, 1);
			# Simple Calculation for Adding Admin Markup and Service Tax

			$CanRefundAmount = $APIAmountC['default_value'] + @$$master_booking_details['data']['booking_details'][0]['admin_markup'] + @$$master_booking_details['data']['booking_details'][0]['service_tax'];
			$CanCharges = $APICanAmount['default_value'];


			$domain_origin = get_domain_auth_id();
			$created_by_id = intval(@$GLOBALS ['CI']->entity_user_id);
			$query = $CI->db->get_where('hb_transfer_cancellation_details', array(
					'app_reference' => $app_reference,
					'transfers_type' => $cancel_status
			));
			$count = $query->num_rows();
			if(isset($booking_file_number))
			{
				$booking_file_number = $booking_file_number;
			}else{
				$booking_file_number = "";
			}
			if ($count === 0) {
				$transfer_cancel_transction = array(
						'app_reference' => $app_reference,
						'domain_origin' => $domain_origin,
						'booking_reference' => @$booking_file_number,
						'booking_source' => HOTELBED_TRANSFER_BOOKING_SOURCE,
						'transfers_type' => @$transfer_cancel_val_k,
						'total_amount' => @$CanRefundAmount,
						'cancellation_amount' => @$CanCharges,
						'currency_code' => @$APICanAmount['default_currency'],
						'cancellation_status' => @$cancel_status,
						'cancellation_date' => @$creation_date,
						'creation_user' => @$creation_user,
						'holder' => @$holder,
						'payment_data' => @$payment_data,
						'agency' => @$agency,
						'incoming_office_code' => @$incoming_office_code,
						'cancellation_polices' => @$cancellation_policies,
						//  'XMLCanResponse' => json_encode($transfer_cancel_val),
						'cancellation_date_time' => date('Y-m-d H:i:s')
				);


				$CI->db->insert('hb_transfer_cancellation_details', $transfer_cancel_transction);
				//  debug($master_booking_details);
				//  debug($transfer_cancel_transction);exit;
				if ($CI->db->affected_rows() > 0) {
					$msg = true;
				} else {
					$msg = false;
				}
			} else {
				$booking_details_data = array('cancellation_status' => $cancel_status);
				$CI->db->update('hb_transfer_cancellation_details', $booking_details_data, array('app_reference' => $app_reference, 'booking_reference' => $booking_file_number));
				if ($CI->db->affected_rows() > 0) {
					$msg = true;
				} else {
					$msg = false;
				}
			}
			if ($msg == true) {
				$booking_details_data = array('purchase_status' => $cancel_status);
				$CI->db->update('hb_transfer_booking_details', $booking_details_data, array('app_reference' => $app_reference, 'booking_reference' => $booking_file_number));

				$booking_transction_data = array('status' => $cancel_status);
				$CI->db->update('hb_transfer_booking_transction_details', $booking_transction_data, array('app_reference' => $app_reference));

				$service_details_data = array('service_status' => $cancel_status);
				$CI->db->update('hb_transfer_service_details', $service_details_data, array('app_reference' => $app_reference, 'booking_reference' => $booking_file_number));
			}
		}
		$result['status'] = $cancel_status;
		$result['msg'] = $msg;
		return $result;
		//debug($cancel_detail_val); exit;
	}

	/**
	 * update markup currency and return summary
	 */
	function update_markup_currency(& $price_summary, & $currency_obj, $no_of_nights = 1, $level_one_markup = false, $current_domain_markup = true) {
		$tax_service_sum = 0;
		$markup_summary = array();
		$temp_price = $currency_obj->get_currency($price_summary, true, $level_one_markup, $current_domain_markup, $no_of_nights);
		return array('value' => $temp_price ['default_value'], 'currency' => $temp_price['default_currency']);
	}

	public function total_price($price_summary) {
		return $price_summary ['NetFare'];
	}

	public function booking_url($search_id) {

	}

	public function formate_booking_response_data($response,$temp_booking){
		 
		
		$response_data = $response['data'][0];
		$purchase = $response_data['Purchase'];
		$transfer_response['status'] = $response['status'];
		$time_in_12_hour_format  = date("g:i A", strtotime($purchase['ServiceList']['Service']['TransferPickupTime']['@attributes']['time']));
		$transfer_data =  Array(
			'book_response' => Array(
                    'Status' => 1,
                    'Message' => '',
                    'CommitBooking' => Array
                        (
                            'BookingDetails' => Array
                                (
                                    'ConfirmationNo' => $purchase['Reference']['IncomingOffice']['@attributes']['code'].'-'.$purchase['Reference']['FileNumber'],
                                    'BookingRefNo' =>$purchase['Reference']['IncomingOffice']['@attributes']['code'].'-'.$purchase['Reference']['FileNumber'],
                                    'BookingId' => '',
                                    'booking_status' => 'BOOKING_CONFIRMED',
                                )

                        ),

                    'BookResult' => Array
                        (
                            'ConfirmationNo' => $purchase['Reference']['IncomingOffice']['@attributes']['code'].'-'.$purchase['Reference']['FileNumber'],
                            'BookingRefNo' => $purchase['Reference']['IncomingOffice']['@attributes']['code'].'-'.$purchase['Reference']['FileNumber'],
                            'BookingId' => '',
                            'booking_status' => 'BOOKING_CONFIRMED',
                        )
                    ),

            'booking_params'	=> Array(
                    'BlockTourId' => '',
                    'token' => Array
                        (
                            'booking_source' => $temp_booking['booking_source'],
                            'additional_info' => '',
                            'inclusions' => '',
                            'exclusions' => '',
                            'short_desc' => '',
                            'voucher_req' => '',
                            'search_id' => $temp_booking['book_attributes']['search_id'],
                            'product_code' => '',
                            'product_title' => '',
                            'grade_title' => '',
                            'grade_code' => '',
                            'grade_desc' => '',
                            'booking_date' => $purchase['ServiceList']['Service']['TransferPickupTime']['@attributes']['date'],
                            'booking_time' => $time_in_12_hour_format,
                            'tour_uniq_id' => '',
                            'age_band' => '',
                            'op' => '',
                            'BlockTourId' => '',
                            'ProductName' => $purchase['ServiceList']['Service']['TransferInfo']['DescriptionList']['Description'][2],
                            'ProductCode' => $purchase['ServiceList']['Service']['TransferInfo']['Code'],
                            'supplier' => $purchase['ServiceList']['Service']['Supplier']['@attributes']['name'],
                            'ProductImage' => $temp_booking['book_attributes']['token']['purchase_details'][0]['image'],
                            'GradeCode' => '',
                            'GradeDescription' => '',
                            'StarRating' => 0,
                            'Duration' => '',
                            'Destination' => $purchase['ServiceList']['Service']['DestinationLocation']['Name'],
                            'Source' => $purchase['ServiceList']['Service']['PickupLocation']['Name'],

                            'ProductSpecifications' => $purchase['ServiceList']['Service']['ProductSpecifications']['TransferGeneralInfoList']['TransferBulletPoint'],
                            'TransferPickupTime' => $purchase['ServiceList']['Service']['TransferPickupTime'],

                            'TransferPickupInformation' => $purchase['ServiceList']['Service']['TransferPickupInformation'],

                            'Paxes' => $purchase['ServiceList']['Service']['Paxes'],

                            'DeparturePoint' => $purchase['ServiceList']['Service']['DestinationLocation']['Name'],
                            'DeparturePointAddress' => '',
                            'SupplierName' => $purchase['ServiceList']['Service']['Supplier']['@attributes']['name'],
                            'SupplierPhoneNumber' => '',
                            'AgeBands' => Array(),
                            'BookingQuestions' => Array(),
                            'HotelPickup' => 0,
                            'Hotel_Pickup_Option' => Array(),
                            'HotelList' => Array(),
                            'Cancellation_available' => 1,
                            'TM_Cancellation_Charge' => Array
                                (
                                    '0' => Array
                                        (
                                            'ChargeType' => '',
                                            'Charge' => $purchase['ServiceList']['Service']['CancellationPolicies']['CancellationPolicy']['@attributes']['amount'],
                                            'FromDate' => date('d-m-Y',strtotime($purchase['ServiceList']['Service']['CancellationPolicies']['CancellationPolicy']['@attributes']['dateFrom'])),
                                            'ToDate' => '',
                                        )

                                ),
                            'TM_LastCancellation_date' => date('d-m-Y',strtotime($purchase['ServiceList']['Service']['CancellationPolicies']['CancellationPolicy']['@attributes']['dateFrom'])),
                            'Price' => Array
                                (
                                    'Currency' => $purchase['Currency']['@attributes']['code'],
                                    'TotalDisplayFare' => $purchase['TotalPrice'],
                                    'AgentCommission' => '',
                                    'AgentTdsOnCommision' => '',
                                    'NetFare' => '',
                                ),
                            'API_Price' => Array
                                (
                                    'Currency' => $purchase['Currency']['@attributes']['code'],
                                    'TotalDisplayFare' => $purchase['TotalPrice'],
                                    'AgentCommission' => 0,
                                    'AgentTdsOnCommision' => 0,
                                    'NetFare' => $purchase['TotalPrice'],
                                ),
                            'API_TM_Price' => Array
                                (
                                    'TotalDisplayFare' => 0,
                                    'GSTPrice' => 0,
                                    'PriceBreakup' => Array
                                        (
                                            'AgentCommission' => 0,
                                            'AgentTdsOnCommision' => 0,
                                        ),
                                    'Currency' => 'INR'
                                ),
                            'TM_Cancellation_Policy' => '',
                            'price_summary' => Array
                                (
                                    'TotalDisplayFare' => $purchase['TotalPrice'],
                                    'NetFare' => $purchase['TotalPrice'],
                                ),

                            'markup_price_summary' => Array
                                (
                                    'TotalDisplayFare' => 0,
                                    'NetFare' => 0
                                ),
                            'default_currency' => $purchase['Currency']['@attributes']['code'],
                            'convenience_fees' => 0
                        ),

                    'token_key' => $temp_booking['book_attributes']['token_key'],
                    'op' => 'book_flight',
                    'booking_source' => $temp_booking['booking_source'],
                    'promo_code_discount_val' => 0.00,
                    'promo_code' => '',
                    'promo_actual_value' => '',
                    'passenger_type' => Array
                        (
                            '0' => $temp_booking['book_attributes']['passenger_type'][0]
                        ),

                    'lead_passenger' => Array(),

                    'name_title' => Array
                        (
                            '0' => $temp_booking['book_attributes']['name_title']
                        ),

                    'first_name' => Array
                        (
                            '0' => $temp_booking['book_attributes']['first_name']
                        ),

                    'last_name' => Array
                        (
                            '0' => $temp_booking['book_attributes']['last_name']
                        ),

                    'hotelPickupId' => '',
                    'hotelPickup_name' => '',
                    'hotel_pickup_list_name' => '',
                    'question_Id' => Array(),
                    'question' => Array(),
                    'code' => '',
                    'module_type' => $temp_booking['book_attributes']['module_type'],
                    'total_amount_val' => $temp_booking['book_attributes']['total_amount_val'],
                    'convenience_fee' => $temp_booking['book_attributes']['token']['convinence_fee'],
                    'currency_symbol' => $temp_booking['book_attributes']['token']['purchase_details'][0]['currency_symbol'],
                    'currency' => 'AED',
                    'billing_country' => $temp_booking['book_attributes']['billing_country'],
                    'billing_city' => $temp_booking['book_attributes']['billing_city'],
                    'billing_zipcode' => $temp_booking['book_attributes']['billing_zipcode'],
                    'billing_address_1' => $temp_booking['book_attributes']['billing_address_1'],
                    'country_code' => 'Benin',
                    'passenger_contact' => $temp_booking['book_attributes']['passenger_contact'],
                    'billing_email' => $temp_booking['book_attributes']['billing_email'],
                    'tc' => $temp_booking['book_attributes']['tc'],
                    'payment_method' => $temp_booking['book_attributes']['payment_method'],
                ),

            'tour_book_request' => Array
                (
                    'AppReference' => $temp_booking['book_id'],
                    'BlockTourId' => '',
                    'PassengerDetails' => Array
                        (
                            '0' => Array
                                (
                                    'Title' => $temp_booking['book_attributes']['name_title'],
                                    'FirstName' => $temp_booking['book_attributes']['first_name'],
                                    'LastName' => $temp_booking['book_attributes']['last_name'],
                                    'Phoneno' => $temp_booking['book_attributes']['passenger_contact'],
                                    'Email' => $temp_booking['book_attributes']['billing_email'],
                                    'PaxType' => 1,
                                    'LeadPassenger' => 1,
                                )

                        ),
                    'ProductDetails' => Array
                        (
                            'ProductCode' => '',
                            'BookingDate' => '',
                            'GradeCode' => '',
                            'pickupPoint' => '',
                            'hotelId' => '',
                        ),
                    'BookingQuestions' => Array(),
                ),
            'tour_book_data' => Array
                (
                    'BlockTourId' => '',
                    'token' => Array
                        (
                            'booking_source' => $temp_booking['booking_source'],
                            'additional_info' => '',
                            'inclusions' => '',
                            'exclusions' => '',
                            'short_desc' => '',
                            'voucher_req' => '',
                            'search_id' => $temp_booking['book_attributes']['search_id'],
                            'product_code' => '',
                            'product_title' => '',
                            'grade_title' => '',
                            'grade_code' => '',
                            'grade_desc' => '',
                            'booking_date' => $purchase['ServiceList']['Service']['TransferPickupTime']['@attributes']['date'],
                            'booking_time' => $time_in_12_hour_format,
                            'tour_uniq_id' => '',
                            'age_band' => '',
                            'op' => '',
                            'BlockTourId' => '',
                            'ProductName' => $purchase['ServiceList']['Service']['TransferInfo']['DescriptionList']['Description'][2],
                            'ProductCode' => $purchase['ServiceList']['Service']['TransferInfo']['Code'],
                            'supplier' => $purchase['ServiceList']['Service']['Supplier']['@attributes']['name'],
                            'ProductCode' => '',
                            'ProductImage' => '',
                            'GradeCode' => '',
                            'GradeDescription' => '',
                            'StarRating' => 0,
                            'Duration' => '',
                            'Destination' => $purchase['ServiceList']['Service']['DestinationLocation']['Name'],
                            'Source' => $purchase['ServiceList']['Service']['PickupLocation']['Name'],
                            'DeparturePoint' => $purchase['ServiceList']['Service']['DestinationLocation']['Name'],
                            'DeparturePointAddress' => '',
                            'SupplierName' => $purchase['ServiceList']['Service']['Supplier']['@attributes']['name'],
                            'SupplierPhoneNumber' => '',
                            'AgeBands' => Array(),
                            'BookingQuestions' => Array(),
                            'HotelPickup' => 0,
                            'Hotel_Pickup_Option' => Array(),
                            'HotelList' => Array(),
                            'Cancellation_available' => 1,
                            'TM_Cancellation_Charge' => Array
                                (
                                    '0' => Array
                                        (
                                            'ChargeType' => '',
                                            'Charge' => $purchase['ServiceList']['Service']['CancellationPolicies']['CancellationPolicy']['@attributes']['amount'],
                                            'FromDate' => date('d-m-Y',strtotime($purchase['ServiceList']['Service']['CancellationPolicies']['CancellationPolicy']['@attributes']['dateFrom'])),
                                            'ToDate' => '',
                                        )

                                ),

                            'TM_LastCancellation_date' => date('d-m-Y',strtotime($purchase['ServiceList']['Service']['CancellationPolicies']['CancellationPolicy']['@attributes']['dateFrom'])),
                            'Price' => Array
                                (
                                    'Currency' => $purchase['ServiceList']['Service']['Currency']['@attributes']['code'],
                                    'TotalDisplayFare' => $purchase['ServiceList']['Service']['TotalPrice'],
                                    'AgentCommission' => '',
                                    'AgentTdsOnCommision' => '',
                                    'NetFare' => '',
                                ),
                            'API_Price' => Array
                                (
                                    'Currency' => $purchase['Currency']['@attributes']['code'],
                                    'TotalDisplayFare' => $purchase['TotalPrice'],
                                    'AgentCommission' => 0,
                                    'AgentTdsOnCommision' => 0,
                                    'NetFare' => $purchase['TotalPrice'],
                                ),
                            'API_TM_Price' => Array
                                (
                                    'TotalDisplayFare' => 0,
                                    'GSTPrice' => 0,
                                    'PriceBreakup' => Array
                                        (
                                            'AgentCommission' => 0,
                                            'AgentTdsOnCommision' => 0,
                                        ),
                                    'Currency' => 'INR'
                                ),
                            'TM_Cancellation_Policy' => '',
                            'price_summary' => Array
                                (
                                    'TotalDisplayFare' => $purchase['TotalPrice'],
                                    'NetFare' => $purchase['TotalPrice'],
                                ),

                            'markup_price_summary' => Array
                                (
                                    'TotalDisplayFare' => 0,
                                    'NetFare' => 0
                                ),
                            'default_currency' => $purchase['Currency']['@attributes']['code'],
                            'convenience_fees' => 0
                        ),
                    'token_key' => $temp_booking['book_attributes']['token_key'],
                    'op' => 'book_flight',
                    'booking_source' => $temp_booking['booking_source'],
                    'promo_code_discount_val' => 0.00,
                    'promo_code' => '',
                    'promo_actual_value' => '',
                    'passenger_type' => Array
                        (
                            '0' => $temp_booking['book_attributes']['passenger_type'][0]
                        ),

                    'lead_passenger' => Array(),

                    'name_title' => Array
                        (
                            '0' => $temp_booking['book_attributes']['name_title']
                        ),

                    'first_name' => Array
                        (
                            '0' => $temp_booking['book_attributes']['first_name']
                        ),

                    'last_name' => Array
                        (
                            '0' => $temp_booking['book_attributes']['last_name']
                        ),

                    'hotelPickupId' => '',
                    'hotelPickup_name' => '',
                    'hotel_pickup_list_name' => '',
                    'question_Id' => Array(),
                    'question' => Array(),
                    'code' => '',
                    'module_type' => $temp_booking['book_attributes']['module_type'],
                    'total_amount_val' => $temp_booking['book_attributes']['total_amount_val'],
                    'convenience_fee' => $temp_booking['book_attributes']['token']['convinence_fee'],
                    'currency_symbol' => $temp_booking['book_attributes']['token']['purchase_details'][0]['currency_symbol'],
                    'currency' => 'AED',
                    'billing_country' => $temp_booking['book_attributes']['billing_country'],
                    'billing_city' => $temp_booking['book_attributes']['billing_city'],
                    'billing_zipcode' => $temp_booking['book_attributes']['billing_zipcode'],
                    'billing_address_1' => $temp_booking['book_attributes']['billing_address_1'],
                    'country_code' => 'Benin',
                    'passenger_contact' => $temp_booking['book_attributes']['passenger_contact'],
                    'billing_email' => $temp_booking['book_attributes']['billing_email'],
                    'tc' => $temp_booking['book_attributes']['tc'],
                    'payment_method' => $temp_booking['book_attributes']['payment_method'],
                    'Price' => Array
                        (
                            'Price' => Array
                                (
                                    'Currency' => $purchase['Currency']['@attributes']['code'],
                                    'TotalDisplayFare' => $purchase['TotalPrice'],
                                    'AgentCommission' => 0,
                                    'AgentTdsOnCommision' => 0,
                                    'NetFare' => $purchase['TotalPrice'],
                                )
                        )

                )

        );

// debug($transfer_data);exit();
		$transfer_response['data'] = $transfer_data;
		return $transfer_response;
	}

	function save_booking($app_booking_id, $params, $module = 'b2b') {
		// Need to return following data as this is needed to save the booking fare in the transaction details
			// Need to return following data as this is needed to save the booking fare in the transaction details
		$response ['fare'] = $response ['domain_markup'] = $response ['level_one_markup'] = 0;

		$domain_origin = get_domain_auth_id ();
		$master_search_id = $params ['booking_params'] ['token'] ['search_id'];
		
		//$status = BOOKING_CONFIRMED;
		$app_reference = $app_booking_id;
		$booking_source = $params ['booking_params'] ['token'] ['booking_source'];
		
		#debug($params);
		$currency_obj = $params ['currency_obj'];
		$deduction_cur_obj = clone $currency_obj;
		$promo_currency_obj = $params['promo_currency_obj'];
		#debug($currency_obj);

		// debug($params);exit;

		// PREFERRED TRANSACTION CURRENCY AND CURRENCY CONVERSION RATE
		$transaction_currency = get_application_currency_preference ();
		$application_currency = admin_base_currency ();
		$currency_conversion_rate = $currency_obj->transaction_currency_conversion_rate ();
		
		$booking_id = $params ['book_response'] ['BookResult'] ['BookingId'];
		$booking_reference = $params ['book_response'] ['BookResult'] ['BookingRefNo'];

		$confirmation_reference = $params ['book_response'] ['BookResult'] ['ConfirmationNo'];
		$status =  $params ['book_response'] ['BookResult'] ['booking_status'];
		$multiplier  =1;

		//$book_total_fare = $params ['booking_params'] ['token'] ['price_summary'] ['TotalDisplayFare']; // (TAX+ROOM PRICE)


		$book_net_total_fare = $params ['booking_params'] ['token'] ['price_summary'] ['NetFare']; // (TAX+ROOM PRICE)
		
		$currency = $params ['booking_params'] ['token'] ['default_currency'];
		$product_name = $params ['booking_params'] ['token'] ['ProductName'];
		$star_rating = $params ['booking_params'] ['token'] ['StarRating'];
		$product_code = $params ['booking_params'] ['token'] ['ProductCode'];
		$phone_number = $params ['booking_params'] ['passenger_contact'];
		$alternate_number = 'NA';
		
		$travel_date = $params ['booking_params'] ['token'] ['booking_date'];
		$travel_time = $params ['booking_params'] ['token'] ['booking_time'];
		$payment_mode = $params ['booking_params'] ['payment_method'];
		
		$grade_code = $params ['booking_params'] ['token'] ['GradeCode'];
		$grade_desc = @$params ['booking_params'] ['token'] ['GradeDescription'];
		$convenience_fee = $params ['booking_params'] ['convenience_fee'];
		$supplier= $params ['booking_params'] ['token'] ['supplier'];
	// debug($convenience_fee);rexit;
		if(isset($params ['booking_params'] ['token']['search_params'])){
			if($params ['booking_params'] ['token']['search_params']){
				$search_params_list = json_decode(base64_decode($params ['booking_params'] ['token']['search_params']),true);

				$additonal_info = json_decode(base64_decode($search_params_list['additional_info']),true);
			
				$inclusions = json_decode(base64_decode($search_params_list['inclusions']),true);
				$exclusions = json_decode(base64_decode($search_params_list['exclusions']),true);
				$short_desc = base64_decode($search_params_list['short_desc']);
				$voucher_req = base64_decode($search_params_list['voucher_req']);


			}
		}else{
			$additonal_info = json_decode(base64_decode($params ['booking_params'] ['token'] ['additional_info']),true);
		
			$inclusions = json_decode(base64_decode($params ['booking_params'] ['token'] ['inclusions']),true);
			
			$exclusions = json_decode(base64_decode($params ['booking_params'] ['token'] ['exclusions']),true);
			

			$short_desc = base64_decode($params ['booking_params'] ['token'] ['short_desc']);

			$voucher_req = base64_decode($params['booking_params']['token']['voucher_req']);

		}
		// debug($params);exit;
		
		$email =$params ['booking_params']['billing_email'];
		// $city_name = $GLOBALS['CI']->db_cache_api->get_city_list(array('k' => 'origin', 'v' => 'destination'), array('origin' => $params['booking_params']['billing_city']));
		$attributes = array (
				'address' => @$params ['booking_params'] ['billing_address_1'],
				'billing_country' => @$country_name [$params ['booking_params'] ['billing_country']],
				// 'billing_city' => $city_name[$params['booking_params']['billing_city']],
				'billing_city' => @$params ['booking_params'] ['billing_city'],
				'billing_zipcode' => @$params ['booking_params'] ['billing_zipcode'],
				'ProductCode' => @$params ['booking_params'] ['token'] ['ProductCode'],
				'search_id' => @$params ['booking_params'] ['token'] ['search_id'],
				
				'ProductName' => @$params ['booking_params'] ['token'] ['ProductName'],
				'StarRating' => @$params ['booking_params'] ['token'] ['StarRating'],
				'ProductImage' => @$params ['booking_params'] ['token'] ['ProductImage'],
				'SupplierName' =>$params ['booking_params'] ['token'] ['supplier'],
				'SupplierPhoneNumber' =>$params ['booking_params'] ['token'] ['SupplierPhoneNumber'],

				'GradeCode' => @$params ['booking_params'] ['token'] ['GradeCode'],
				'GradeDescription'=>@$params['booking_params']['token']['GradeDescription'],
				'Destination' => @$params ['booking_params'] ['token'] ['Destination'],
				'Source' => @$params ['booking_params'] ['token'] ['Source'],

				'ProductSpecifications' => @$params ['booking_params'] ['token'] ['ProductSpecifications'],
				'TransferPickupTime' => @$params ['booking_params'] ['token'] ['TransferPickupTime'],
				'TransferPickupInformation' => @$params ['booking_params'] ['token'] ['TransferPickupInformation'],
				'Paxes' =>  @$params ['booking_params'] ['token']['Paxes'],
				
				'DeparturePoint'=>@$params['booking_params']['token']['DeparturePoint'],
				'DeparturePointAddress' => @$params ['booking_params'] ['token'] ['DeparturePointAddress'],
				'Duration'=>@$params['booking_params']['token']['Duration'],
				
				'Cancellation_available'=>@$params['booking_params']['token']['Cancellation_available'],
				'DeparturePointAddress'=>@$params['booking_params']['token']['DeparturePointAddress'],
				'TM_Cancellation_Charge'=>@$params['booking_params']['token']['TM_Cancellation_Charge'],
				'TM_LastCancellation_date'=>@$params['booking_params']['token']['TM_LastCancellation_date'],
				'TM_Cancellation_Policy'=>@$params['booking_params']['token']['TM_Cancellation_Policy'],
				'Additional_info'=>json_encode($additonal_info),
				'Inclusions'=>json_encode($inclusions),
				'Exclusions'=>json_encode($exclusions),
				'ShortDesc'=>$short_desc,
				'VoucherInfo'=>$voucher_req

		);
		$created_by_id = intval ( @$GLOBALS ['CI']->entity_user_id );
		// SAVE Booking details
		// debug($product_name);exit;
		$GLOBALS ['CI']->transferv1_model->save_booking_details ( $domain_origin, $status, $app_reference, $booking_source, $booking_id, $booking_reference, $confirmation_reference, $product_name, $star_rating, $product_code,$grade_code,$grade_desc,$phone_number, $alternate_number, $email, $travel_date,$payment_mode, json_encode ( $attributes ), $created_by_id, $transaction_currency, $currency_conversion_rate, $travel_time, $convenience_fee, $supplier );
		

		$Fare =  $params ['booking_params'] ['token']['API_Price'];

		//sdebug($Fare);

		$final_booking_price_details = $this->get_final_booking_price_details($Fare, $multiplier,$currency_obj, $deduction_cur_obj, $module, $master_search_id);
// debug($final_booking_price_details);exit;
		$book_total_fare = $commissionable_fare =$final_booking_price_details['commissionable_fare'];
		
		$base_fare = $final_booking_price_details['base_fare'];
		$org_base_fare = $final_booking_price_details['org_base_fare'];
		$total_fare = $trans_total_fare =$final_booking_price_details['trans_total_fare']+$convenience_fee;		
		$admin_markup =  $book_domain_markup = $final_booking_price_details['admin_markup'];
        $agent_markup = $final_booking_price_details['agent_markup'];
        $admin_commission = $final_booking_price_details['admin_commission'];
        $agent_commission = $final_booking_price_details['agent_commission'];
        $admin_tds = $final_booking_price_details['admin_tds'];
        $agent_tds = $final_booking_price_details['agent_tds'];
        if($module == 'b2c'){
        	$total_markup = $admin_markup;
        }
        else if($module == 'b2b'){
           $total_markup = $admin_markup+$agent_markup;
        }
       	//adding gst
       	$gst_value = 0;
       	if($total_markup > 0 ){
	        $gst_details = $GLOBALS['CI']->custom_db->single_table_records('gst_master', '*', array('module' => 'transfer'));
            if($gst_details['status'] == true){
                if($gst_details['data'][0]['gst'] > 0){
                    $gst_value = ($total_markup/100) * $gst_details['data'][0]['gst'];
                	$gst_value  = roundoff_number($gst_value);
                }
          	}
        }
      
        
		/*$book_total_fare = $params ['booking_params'] ['token'] ['price_summary'] ['TotalDisplayFare']; // (TAX+ROOM PRICE)

		$total_fare = $params ['booking_params'] ['token'] ['price_summary'] ['TotalDisplayFare'];		
		$agent_commission = $params ['booking_params'] ['token']['Price']['AgentCommission'];
		$agent_tds = $params ['booking_params'] ['token']['Price']['AgentTdsOnCommision'];

		$admin_commission = $params ['booking_params'] ['token']['Price']['AdminCommission'];
		$admin_tds = $params ['booking_params'] ['token']['Price']['AdminTdsonCommission'];*/


		$attributes = '';
		$location = $params ['tour_book_data'] ['token'] ['DeparturePoint'];
		$location_from = $params ['booking_params'] ['token'] ['Source'];

		$api_raw_fare = $params ['booking_params'] ['token']['Price']['TotalDisplayFare'];
		$agent_buying_price = $total_fare-$agent_markup;
		$admin_net_fare_markup = 0;
		// SAVE Booking Itinerary details
		$GLOBALS ['CI']->transferv1_model->save_booking_itinerary_details ( $app_reference, $location, $travel_date,$grade_code, $grade_desc, $status,$total_fare,$admin_net_fare_markup,$admin_markup, $agent_markup, $currency, $attributes, @$book_total_fare,$agent_commission,$agent_tds,$admin_commission,$admin_tds,$api_raw_fare,$agent_buying_price, $gst_value, $base_fare, $org_base_fare, $location_from, $convenience_fee);

		$TourPassengers = $params['tour_book_request']['PassengerDetails'];
		
		if (valid_array ( $TourPassengers ) == true) {
			foreach ( $TourPassengers as $passenger ) {
				$title = $passenger ['Title'];
				$first_name = $passenger ['FirstName'];
				//$middle_name = $passenger ['MiddleName'];
				$last_name = $passenger ['LastName'];
				$phone = $passenger ['Phoneno'];
				$email = $passenger ['Email'];
				$pax_type = $passenger ['PaxType'];
				
				$attributes = array ();
				
				// SAVE Booking Pax details
				$GLOBALS ['CI']->transferv1_model->save_booking_pax_details ( $title,$app_reference, $first_name,$last_name, $phone, $email, $pax_type,$status, serialize ( $attributes ) );
			}
		}
		
		/**
		 * ************ Update Convinence Fees And Other Details Start *****************
		 */
		// Convinence_fees to be stored and discount
		$convinence = 0;
		$discount = 0;
		$convinence_value = 0;
		$convinence_type = 0;
		$convinence_per_pax = 0;

		if ($module == 'b2c') {
			$pax_data = $params['booking_params']['token']['AgeBands'];
			$total_transaction_amount = $trans_total_fare+$book_domain_markup+$gst_value;
			
			$convinence = $currency_obj->convenience_fees ($total_transaction_amount ,$pax_data );

			$convinence_row = $currency_obj->get_convenience_fees ();
			$convinence_value = $convinence_row ['value'];
			$convinence_type = $convinence_row ['type'];
			$convinence_per_pax = $convinence_row ['per_pax']; 
			if($params['booking_params']['promo_actual_value']){
				$discount = get_converted_currency_value ( $promo_currency_obj->force_currency_conversion ( $params['booking_params']['promo_actual_value']) );
			}
			
			//$discount = @$params ['booking_params'] ['promo_code_discount_val'];
			$promo_code = @$params ['booking_params'] ['promo_code'];
		} elseif ($module == 'b2b') {
			$discount = 0;
			$promo_code='';
		}
		$GLOBALS ['CI']->load->model ( 'transaction' );
		// SAVE Booking convinence_discount_details details
		$GLOBALS ['CI']->transaction->update_convinence_discount_details ( 'transferv1_booking_details', $app_reference, $discount, $promo_code, $convinence, $convinence_value, $convinence_type, $convinence_per_pax );
		/**
		 * ************ Update Convinence Fees And Other Details End *****************
		 */		
		$response ['fare'] = $total_fare;
		$response ['admin_markup'] = $admin_markup;
		$response ['agent_markup'] = $agent_markup;
		$response ['convinence'] = $convinence;
		$response ['discount'] = $discount;
		$response ['transaction_currency'] = $transaction_currency;
		$response ['currency_conversion_rate'] = $currency_conversion_rate;
		//booking_status
		$response['booking_status'] = $status;

		// debug($response);exit;
		return $response;
	}

	private function get_final_booking_price_details($Fare, $multiplier,$currency_obj, $deduction_cur_obj, $module, $search_id='') {
		$safe_search_data = $GLOBALS['CI']->transfer_model->get_safe_search_data($search_id);
			$country_city_data = $GLOBALS['CI']->transfer_model->get_country_city($safe_search_data['data']['from_code']);
			$cntry_code = $country_city_data[0]['country_code'];
			$city_id = $country_city_data[0]['city_id'];

						$agent_base_currency = agent_base_currency();
						$currency_obj_m = new Currency(array('module_type' => 'transfers', 'from' => $Fare['Currency'], 'to' => $agent_base_currency));
							 $strCurrency_m  = $currency_obj_m->get_currency($Fare['TotalDisplayFare'], true, false, true, false, 1);
				$transfer_price = $strCurrency_m['default_value'];



						$arrConvAmt   = $currency_obj->get_transfer_currency(1, true, false, true, 1);
						$strConvcur   = $arrConvAmt['default_currency'];
						// debug($arrConvAmt);exit;
						$currency_symbol = $currency_obj->get_currency_symbol($strConvcur);
						 // debug($transfer);exit;
						// debug(@$transfer['Purchase']['ServiceList']['Service']['SellingPrice']);exit;
						$cancel_cur_obj = clone $currency_obj;
						// $ded_total_fare = $cancel_cur_obj->get_transfer_currency(@$transfer['Purchase']['ServiceList']['Service']['SellingPrice'], true, false, true, 1);
						$product_id = '';
						$supplier = 'Beds Online';
						// debug($hd);exit;

						$search_level_markup_val = $safe_search_data['data']['markup_value'];
						if($search_level_markup_val!='' || $search_level_markup_val!=0){
						$search_level_markup_typ = $safe_search_data['data']['markup_type'];
						if($search_level_markup_typ=='plus'){
							$search_level_markup = $search_level_markup_val;
						}else{
							$search_level_markup = ($transfer_price/100) * $search_level_markup_val;
						}
						}else{
							$search_level_markup = 0;
						}
						$admin_markup_details = $GLOBALS ['CI']->transfer_model->get_markup_for_admin ( $transfer_price, $supplier, $cntry_code, $city_id, $product_id);
						$agent_markup = $GLOBALS ['CI']->transfer_model->get_markup_for_agent ( $transfer_price, $cntry_code, $city_id); 
						$total_markup_admin_agent = $admin_markup_details+$agent_markup+$search_level_markup;
						$gst_percentage = $GLOBALS['CI']->transfer_model->get_admin_gst();
						
						$gst_amount = ($gst_percentage[0]['gst']*$total_markup_admin_agent)/100;
						// debug($admin_markup_details);debug($agent_markup);debug($search_level_markup);debug($gst_amount);debug($Fare['TotalDisplayFare']);
						// Changed by chalapathi [0] is added to selling price
						$ded_total_fare = $cancel_cur_obj->get_transfer_currency($Fare['TotalDisplayFare'], true, false, true, 1);

						$markupData = $GLOBALS ['CI']->transfer_model->admin_markup ( $Fare['TotalDisplayFare'], $supplier, $cntry_code, $city_id, $product_id,'Api');




        $data = array();
        // debug($Fare);exit;

        $core_agent_commision = ($Fare['TotalDisplayFare'] - $Fare['NetFare']);       	 
        $commissionable_fare = $Fare['TotalDisplayFare'];
        if ($module == 'b2c') {
        	
            $trans_total_fare = $this->total_price($Fare, false, $currency_obj);          

            $markup_total_fare = $currency_obj->get_currency($trans_total_fare, true, false, true, $multiplier);
            $ded_total_fare = $deduction_cur_obj->get_currency($trans_total_fare, true, true, false, $multiplier);
            $admin_markup = roundoff_number($markup_total_fare['default_value'] - $ded_total_fare['default_value']);
            $admin_commission = $Fare['AgentCommission'];
            $agent_markup = 0;
            $agent_commission = 0;
        } else {
            //B2B Calculation
         	 //debug($Fare);
            $trans_total_fare = $transfer_price;             
            $this->commission = $currency_obj->get_commission();
            //echo "commission";
            //debug($this->commission);

            $AgentCommission = $this->calculate_commission($core_agent_commision);
            //debug($AgentCommission);

            $admin_commission = roundoff_number($core_agent_commision - $AgentCommission); //calculate here
            $agent_commission = roundoff_number($AgentCommission);
            
            $admin_net_rate=($trans_total_fare-$agent_commission);
            //echo "admin_net_rate".$admin_net_rate.'<br/>';

            $markup_total_fare = $currency_obj->get_currency($admin_net_rate, true, true, false, $multiplier);
            
            // debug($markup_total_fare);exit;

            $admin_markup = $admin_markup_details;
            $agent_tds = $currency_obj->calculate_tds($agent_commission);
            //adding tds with net rate by ela
            $agent_net_rate=(($trans_total_fare + $admin_markup)-$agent_commission+$agent_tds);
            $ded_total_fare = $deduction_cur_obj->get_currency($agent_net_rate, true, false, true, $multiplier);
            $agent_markup = $agent_markup;
          
           
        }

        //TDS Calculation
        $admin_tds = $currency_obj->calculate_tds($admin_commission);
        $agent_tds = $currency_obj->calculate_tds($agent_commission);

        $data['commissionable_fare'] = $commissionable_fare;
        $data['org_base_fare'] = $Fare['TotalDisplayFare'];
        $data['base_fare'] = $transfer_price;
        $data['trans_total_fare'] = $trans_total_fare+$admin_markup+$agent_markup+$search_level_markup+$gst_amount;
        $data['admin_markup'] = $admin_markup;
        $data['agent_markup'] = $agent_markup+$search_level_markup;
        $data['admin_commission'] = $admin_commission;
        $data['agent_commission'] = $agent_commission;
        $data['admin_tds'] = $admin_tds;
        $data['agent_tds'] = $agent_tds;
        $data['gst'] = $gst_amount;
        // debug($data);
        // exit;
        return $data;
    }
    private function calculate_commission($agent_com) {
        $agent_com_row = $this->commission['admin_commission_list'];
        $b2b_comm = 0;
        if ($agent_com_row['value_type'] == 'percentage') {
            //%
            $b2b_comm = ($agent_com / 100) * $agent_com_row['value'];
        } else {
            //plus
            $b2b_comm = ($agent_com - $agent_com_row['value']);
        }       
        return roundoff_price($b2b_comm);
    }

}