<?php
require_once BASEPATH . 'libraries/viatortransfer/Common_api_transferv1.php';
class Common_Transferv1 {
	/**
	 * Url to be used for combined flight booking - only for domestic round way
	 *
	 * @param number $search_id        	
	 */
	static function combined_booking_url($search_id) {
		return Common_Api_Sightseeing::pre_booking_url ( $search_id );
	}
	
	/**
	 * Data gets saved in list so remember to use correct source value
	 *
	 * @param string $source
	 *        	source of the data - will be used as key while saving
	 * @param string $value
	 *        	value which has to be cached - pass json
	 */
	static function insert_record($key, $value) {
		$ci = & get_instance ();
		
		$index = $ci->redis_server->store_list ( $key, $value );
		return array (
				'access_key' => $key . DB_SAFE_SEPARATOR . $index . DB_SAFE_SEPARATOR . random_string () . random_string (),
				'index' => $index 
		);
	}
	
	/**
	 */
	static function read_record($key, $offset = -1, $limit = -1) {
		$ci = & get_instance ();
		return $ci->redis_server->read_list ( $key, $offset, $limit );
	}
	
	/**
	 * Cache the data
	 *
	 * @param string $key        	
	 * @param value $value        	
	 * @return array[]
	 */
	static function insert_string($key, $value) {
		$ci = & get_instance ();
		$ci->redis_server->store_string ( $key, $value );
	}
	
	/**
	 * read data from cache
	 *
	 * @param string $key        	
	 * @param number $offset        	
	 * @param number $limit        	
	 */
	static function read_string($key) {
		$ci = & get_instance ();
		return $ci->redis_server->read_string ( $key );
	}
	/**
	 * update cache key by saving data in cache to be accessed in next page and get markup up update
	 *
	 * @param array $product_list        	
	 */
	public function update_markup_and_insert_cache_key_to_token($product_list, $carry_cache_key, $search_id) 
	{
		// debug($activity_list);exit;
		$ci = & get_instance ();
		$search_data = $ci->transferv1_model->get_safe_search_data( $search_id );
		$search_data = $search_data ['data'];
		$domain_id = get_domain_auth_id();
		

		$commission_percentage = $ci->domain_management->get_viator_transfer_commission($domain_id);
		
		
		foreach ( $product_list as $j_activity => & $j_activity_list ) {
			// $multiplier = $j_activity_list['modalities'][0]['duration'];
			$multiplier = 1;
			$temp_token = array_values(unserialized_data($j_activity_list['ResultToken']));
			
			$booking_source = $temp_token[0]['booking_source'];
			
			$cache_data = $j_activity_list;
			
			//Cache the Data
			$access_data = Common_Transferv1::insert_record ( $carry_cache_key, json_encode ( $cache_data ) );
			//Assiging the Cache Key
			$product_list[$j_activity]['ResultToken'] = $access_data ['access_key'];
			
			//Update the Markup and Commission
			$this->update_fare_markup_commission($j_activity_list['Price'], $multiplier, $commission_percentage, true, $booking_source);
		}
		return $product_list;
	}
	/**
	 * 
	 * Cache Block Tourdata
	 * @param unknown_type $room_list
	 * @param unknown_type $carry_cache_key
	 */
	public function cache_block_tour_data($block_room_data, $carry_cache_key, $search_id)
	{
		//error_reporting(E_ALL);
		$ci = & get_instance ();
		$currency_obj = new Currency(array('from' => get_application_default_currency() , 'to' => domain_base_currency()));
		$multiplier = 1;
		$domain_currency_conversion = true;
		$domain_id = get_domain_auth_id();
		// debug($activity_list);exit;
		$commission_percentage = $ci->domain_management->get_viator_transfer_commission($domain_id);
		//debug($block_room_data);exit;
		if(valid_array($block_room_data)){

			$temp_token = array_values(unserialized_data($block_room_data['BlockTourId']));

			$booking_source = $temp_token[0]['booking_source'];
			
			$cache_data = array();
			$cache_data['BlockTourId'] = $block_room_data['BlockTourId'];
			
			$access_data = Common_Transferv1::insert_record ( $carry_cache_key, json_encode ( $cache_data ) );
			$block_room_data['BlockTourId'] = $access_data ['access_key'];
			
			$BlockTripResult = $block_room_data;
			$this->update_fare_markup_commission($BlockTripResult['Price'], $multiplier, $commission_percentage, true, $booking_source);
		}
		
		//$block_room_data['BlockTripResult'] = $BlockTripResult;
		//debug($block_room_data);exit;
		return $BlockTripResult;
	}

	/**
	 * 
	 * Cache update_tourgrade_markup
	 * @param unknown_type $tourgrade list
	 * @param unknown_type $carry_cache_key
	 */
	public function update_tourgrade_markup($tour_list,$booking_source, $carry_cache_key, $search_id)
	{
		$ci = & get_instance ();
		$currency_obj = new Currency(array('from' => get_application_default_currency() , 'to' => domain_base_currency()));
		
		$multiplier =1;		
		$domain_id = get_domain_auth_id();
		// debug($activity_list);exit;
		$commission_percentage = $ci->domain_management->get_viator_transfer_commission($domain_id);
		
		if(valid_array($tour_list)){
			foreach ($tour_list as $rm_k => & $rm_v){

				$temp_token = array_values(unserialized_data($rm_v['TourUniqueId']));

				//$booking_source = $temp_token[0]['booking_source'];
				$access_data = Common_Transferv1::insert_record ( $carry_cache_key, json_encode ( $rm_v ) );
				$tour_list[$rm_k]['TourUniqueId'] = $access_data ['access_key'];
				
				//Add markup and convert to domain currency
				$this->update_fare_markup_commission($rm_v['Price'], $multiplier, $commission_percentage, true, $booking_source);
				
			}
		}
		
		return $tour_list;
	}

	/**
	 * Adding the Markup and Commission
	 */
	private function update_fare_markup_commission(& $FareDetails, $multiplier, $commission_percentage, $domain_currency_conversion, $booking_source, $OperatorCode='')
	{
		
		
		$multiplier = 1;       	
		$ci = & get_instance ();	
		$total_fare = $FareDetails['TotalDisplayFare'];	
		//debug($FareDetails);

		//$total_fare = $FareDetails['ProductPrice'];
		$currency_obj = new Currency ( array ('module_type' => 'b2c_viator_transfer','from' => get_application_default_currency (),'to' => get_application_default_currency ()) );		

		$markup_price = $currency_obj->get_currency($total_fare, true, true, false, $multiplier, $booking_source, VIATOR_TRANSFER_VERSION_1,$OperatorCode);

		$total_markup = ($markup_price['default_value']-$total_fare);
		$gst_price = 0;		
		$domain_id = get_domain_auth_id();
		$domain_currency_details = $ci->domain_management_model->get_domain_details($domain_id);
		$domain_currency = $domain_currency_details['domain_base_currency'];
		//echo $total_markup;
		
		if($domain_currency=='INR'){
			if($total_markup>0){
				$gst_price = ((18/100)*$total_markup);			
			}
			//echo $gst_price;
			if(isset($FareDetails['GSTPrice'])){
				$FareDetails['GSTPrice'] = $gst_price ;
			}
			//$gst_price = 0;		
		}

		//echo $total_markup;
		unset($FareDetails['NetFare']);
		//unset($FareDetails['GSTPrice']);
		
		if($domain_currency=='INR'){
			$FareDetails['TotalDisplayFare'] += $total_markup+$gst_price;
			$FareDetails['PriceBreakup']['AgentCommission'] = 		round($this->update_agent_commision($FareDetails['PriceBreakup']['AgentCommission'], $commission_percentage), 3);
			$FareDetails['PriceBreakup']['AgentTdsOnCommision'] = round($currency_obj->calculate_tds($FareDetails['PriceBreakup']['AgentCommission']), 3);

			$FareDetails['TotalDisplayFare'] = round($FareDetails['TotalDisplayFare'] );
			$FareDetails['PriceBreakup']['AgentCommission'] = round($FareDetails['PriceBreakup']['AgentCommission']);
			$FareDetails['PriceBreakup']['AgentTdsOnCommision'] = round($FareDetails['PriceBreakup']['AgentTdsOnCommision']);


		}else{
			$FareDetails['TotalDisplayFare'] += $total_markup+$gst_price;
			$FareDetails['PriceBreakup']['AgentCommission'] = 		round($this->update_agent_commision($FareDetails['PriceBreakup']['AgentCommission'], $commission_percentage), 3);
			$FareDetails['PriceBreakup']['AgentTdsOnCommision'] = round($currency_obj->calculate_tds($FareDetails['PriceBreakup']['AgentCommission']), 3);
		}

		
		
		// debug($FareDetails);
		// exit;
		//Converting Fare Object to Domain Currency
		$this->convert_to_domain_currency_object($FareDetails, $domain_currency_conversion);
	}
	/**
	 * update cache key by saving data in cache to be accessed in next page and get markup up update
	 *
	 * @param array $activity_details       	
	 */
	public function update_activity_details_markup($activity_details, $booking_source, $carry_cache_key, $search_id){
		$ci = & get_instance ();
		$update_price_list = array();
		$multiplier = 1;
		$domain_id = get_domain_auth_id();
		// debug($activity_list);exit;
		$commission_percentage = $ci->domain_management->get_viator_transfer_commission($domain_id);
		$activity_details = $activity_details;

		foreach ($activity_details['Product_Tourgrade'] as $key => $value) {
			$update_price_list[$key] = $value;

			$update_price_list[$key]['Price'] = $this->update_activity_details_markup_commission($value['Price'], $multiplier, $commission_percentage, true, $booking_source);
		}
		$access_data = Common_Transferv1::insert_record ( $carry_cache_key, json_encode ( $activity_details['ResultToken']) );
			//Assiging the Cache Key		

		$activity_details['ResultToken'] = $access_data ['access_key'];
		$activity_details['Product_Tourgrade'] = $update_price_list;

		return $activity_details;

	}
	/**
	 * update cache key by saving data in cache to be accessed in next page and get markup up update
	 *
	 * @param array $activity_details       	
	 */
	public function update_activity_details_price_markup($activity_details, $booking_source, $carry_cache_key, $search_id){
		$ci = & get_instance ();
		$update_price_list = array();
		$multiplier = 1;
		$domain_id = get_domain_auth_id();
		// debug($activity_list);exit;
		$commission_percentage = $ci->domain_management->get_viator_transfer_commission($domain_id);
			
		return $this->update_activity_details_markup_commission($activity_details, $multiplier, $commission_percentage, true, $booking_source);

	}
	

	/**
	 * Adding the Markup
	 */
	private function update_activity_details_markup_commission($FareDetails, $multiplier, $commission_percentage, $domain_currency_conversion, $booking_source, $OperatorCode='')
	{
		
		$multiplier = 1;
       	
		$ci = & get_instance ();
		$total_fare = $FareDetails['TotalDisplayFare'];
		
		$currency_obj = new Currency ( array ('module_type' => 'currency_obj','from' => get_application_default_currency (),'to' => get_application_default_currency ()) );
     	//debug($currency_obj);exit;
		$markup_price = $currency_obj->get_currency($total_fare, true, true, false, $multiplier, $booking_source, VIATOR_TRANSFER_VERSION_1,$OperatorCode);
		
		$total_markup = ($markup_price['default_value']-$total_fare);
		

		$gst_price = 0;		
		$domain_id = get_domain_auth_id();
		$domain_currency_details = $ci->domain_management_model->get_domain_details($domain_id);
		$domain_currency = $domain_currency_details['domain_base_currency'];
		if($domain_currency=='INR'){
			if($total_markup>0){
				$gst_price = ((18/100)*$total_markup);			
			}
			//echo $gst_price;
			if(isset($FareDetails['GSTPrice'])){
				$FareDetails['GSTPrice'] = $gst_price ;
			}
			//$gst_price = 0;		
		}		
		unset($FareDetails['NetFare']);
		if($domain_currency=='INR'){
			$FareDetails['TotalDisplayFare'] += $total_markup+$gst_price;
			$FareDetails['PriceBreakup']['AgentCommission'] = 		round($this->update_agent_commision($FareDetails['PriceBreakup']['AgentCommission'], $commission_percentage), 3);
			$FareDetails['PriceBreakup']['AgentTdsOnCommision'] = 		round($currency_obj->calculate_tds($FareDetails['PriceBreakup']['AgentCommission']), 3);
			$FareDetails['TotalDisplayFare']  = round($FareDetails['TotalDisplayFare'] );
			$FareDetails['PriceBreakup']['AgentCommission'] = round($FareDetails['PriceBreakup']['AgentCommission']);
			$FareDetails['PriceBreakup']['AgentTdsOnCommision'] = round($FareDetails['PriceBreakup']['AgentTdsOnCommision']);

		}else{
			$FareDetails['TotalDisplayFare'] += $total_markup+$gst_price;
			$FareDetails['PriceBreakup']['AgentCommission'] = 		round($this->update_agent_commision($FareDetails['PriceBreakup']['AgentCommission'], $commission_percentage), 3);
			$FareDetails['PriceBreakup']['AgentTdsOnCommision'] = 		round($currency_obj->calculate_tds($FareDetails['PriceBreakup']['AgentCommission']), 3);
		
		}
		
		//Converting Fare Object to Domain Currency
		return $this->details_convert_to_domain_currency_object($FareDetails, $domain_currency_conversion);
	}

	/**
	*convert Cancellation Policy for Block Tourgrade Sightseeing
	*/
	public  function update_fare_markup_cancel_policy(& $CancellationCharage, $multiplier, $commission_percentage, $domain_currency_conversion, $booking_source){
		$ci = & get_instance ();
		//Get booking_source_fk
		$booking_source_fk = $ci->custom_db->single_table_records('booking_source', 'origin', array('source_id' => trim($booking_source)));
		//TODO: calculate markup based on API
		//$booking_source_fk = $booking_source_fk['data'][0]['origin'];//later
		$booking_source_fk = '';//later
		//calculating Markup and commission
		$domain_id = get_domain_auth_id();
		$domain_currency_details = $ci->domain_management_model->get_domain_details($domain_id);
		$domain_currency = $domain_currency_details['domain_base_currency'];
		$currency_obj = new Currency ( array ('module_type' => 'b2c_viator_transfer','from' => get_application_default_currency (),'to' => get_application_default_currency ()) );
		
		foreach ($CancellationCharage as $key => $value) {
			if($value['Charge']!=0){
				$total_charge = $value['Charge'];		
				$markup_price = $currency_obj->get_currency($total_charge, true, true, false, $multiplier, $booking_source_fk);
				$total_markup = ($markup_price['default_value']-$total_charge);	
				
				//Calcuating 18% of GST for markup price
				$gst_price = 0;		
				if($domain_currency=='INR'){
					if($total_markup>0){
						$gst_price = round((18/100)*$total_markup);			
					}
					$cancellation_charge = $total_markup + $gst_price;
					$CancellationCharage[$key]['Charge'] +=$cancellation_charge;
					$CancellationCharage[$key]['Charge'] = ceil($CancellationCharage[$key]['Charge']);
					
				}else{
					$CancellationCharage[$key]['Charge'] +=$total_markup;
					$CancellationCharage[$key]['Charge'] = round($CancellationCharage[$key]['Charge'],2);
				}
				 $cancel_charge = $this->convert_to_domain_currency_object_cancel($CancellationCharage[$key], $domain_currency_conversion);
				$CancellationCharage[$key]['Charge'] = $cancel_charge;
			}	
			
		}
		return $CancellationCharage;
	}
	/**
	 * Convert Fare Object to Domain Currency
	 */
	private function convert_to_domain_currency_object_cancel(& $CancellationCharage, $domain_currency_conversion=true)
	{
		if($domain_currency_conversion == true){
			$domain_base_currency = domain_base_currency();
		} else {
			$domain_base_currency = get_application_default_currency();
		}		
		$currency_obj = new Currency(array('from' => get_application_default_currency() , 'to' => $domain_base_currency));
		//Converting the API Fare Currency to Domain Currency
		//FARE DETAILS
		$CancellationCharage['Currency'] = $domain_base_currency;
		
		return $CancellationCharage['Charge'] =get_converted_currency_value($currency_obj->force_currency_conversion($CancellationCharage['Charge']));	
	}	


	/**
	 * Returns Booking Transaction Amount Details
	 * @param unknown_type $core_price_details
	 */
		/**
	 * Returns Booking Transaction Amount Details
	 * @param unknown_type $core_price_details
	 */
	public function final_booking_transaction_fare_details($core_price_details, $search_id, $booking_source)
	{
		$ci = & get_instance();		
		$multiplier = 1;		
		$domain_id = get_domain_auth_id();
		$domain_id = get_domain_auth_id();
		$commission_percentage = $ci->domain_management->get_viator_transfer_commission($domain_id);
		$domain_currency_conversion = false;
		
		$core_total_price = 0;
		$updated_total_price = 0;	
		$core_commission = 0;
		$markup_gst_price=0;
		$core_commission_on_tds =0;
		$updated_markup_gst_price = 0;
		$api_net_fare = 0;

		$core_comm_total_price = $core_price_details['Price']['TotalDisplayFare'];
		
		$core_commission = $core_price_details['Price']['PriceBreakup']['AgentCommission'];
		$core_commission_on_tds = $core_price_details['Price']['PriceBreakup']['AgentTdsOnCommision'];
		
		$api_net_fare = $core_price_details['Price']['NetFare'];
		
		$this->update_fare_markup_commission($core_price_details['Price'], $multiplier, $commission_percentage, $domain_currency_conversion, $booking_source);

		
		
		if(isset($core_price_details['Price']['GSTPrice'])){
			$markup_gst_price = $core_price_details['Price']['GSTPrice'];	
		}			
		$agent_commission = $core_price_details['Price']['PriceBreakup']['AgentCommission'];
		$agent_tds = $core_price_details['Price']['PriceBreakup']['AgentTdsOnCommision'];
		$admin_commission = $core_commission -$agent_commission;
		$admin_tds = ($core_commission_on_tds-$agent_tds);
					
		$updated_comm_total_price = $core_price_details['Price']['TotalDisplayFare'];

		$admin_markup = ($updated_comm_total_price - $core_comm_total_price);		
		//Storing GST price
		/*Start*/
		$gst_price = $markup_gst_price;
		//$ss_markup_price = ($updated_comm_total_price - $markup_gst_price);
		$admin_markup_gst = ($admin_markup - $gst_price);


		/*End*/
		//Fare Breakups
		$final_booking_transaction_fare_details['PriceBreakup'] =$core_price_details['Price'];
		$final_booking_transaction_fare_details['Price'] = array();
		$final_booking_transaction_fare_details['Price']['total_fare'] = $core_comm_total_price;
		$final_booking_transaction_fare_details['Price']['net_fare'] = $api_net_fare;
		$final_booking_transaction_fare_details['Price']['admin_commission'] = $admin_commission;
		$final_booking_transaction_fare_details['Price']['agent_commission'] = $agent_commission;
		$final_booking_transaction_fare_details['Price']['admin_tds'] = $admin_tds;
		$final_booking_transaction_fare_details['Price']['agent_tds'] = $agent_tds;
		$final_booking_transaction_fare_details['Price']['admin_markup'] = round($admin_markup, 1);
		$final_booking_transaction_fare_details['Price']['gst'] = round($gst_price, 1);
		//$final_booking_transaction_fare_details['Price']['ss_markup_price'] = round($ss_markup_price, 1);
		$final_booking_transaction_fare_details['Price']['admin_markup_gst'] = round($admin_markup_gst, 1);

		//Client Buying Price
		$final_booking_transaction_fare_details['Price']['client_buying_price'] = floatval($core_comm_total_price-$agent_commission+$agent_tds);//admin markup is already included in fare
		
		
		return $final_booking_transaction_fare_details;
	}

	/*
	 * save sightseeing  details
	*/
	function save_sightseen_booking($tour_data, $passenger_details, $app_reference, $booking_source, $search_id)
	{
		$ci = & get_instance();
		$data['status'] = SUCCESS_STATUS;
		$data['message'] = '';
		//$porceed_to_save = $this->is_duplicate_ss_booking($app_reference);
		
		$porceed_to_save['status'] = SUCCESS_STATUS;
		// debug($porceed_to_save);exit;
		if($porceed_to_save['status'] != SUCCESS_STATUS){
			$data['status'] = $porceed_to_save['status'];
			$data['message'] = $porceed_to_save['message'];

		} else {
			$search_data = $ci->transferv1_model->get_safe_search_data ( $search_id );
			
			$search_data = $search_data['data'];
			$fare_details = $tour_data['Price'];
			//$tour_details = $tour_data['RoomPriceBreakup'];
			$master_booking_status = 'BOOKING_INPROGRESS';	
			
			
			//Save to Master table
			$domain_origin = get_domain_auth_id();
			$tour_booking_status = $master_booking_status;
			$currency = domain_base_currency();
			$currency_obj = new Currency(array('module_type' => 'b2c_viator_transfer'));
			$currency_conversion_rate = $currency_obj->get_domain_currency_conversion_rate();
			
			//Price Details
			$book_total_fare = $fare_details['total_fare'];
			$book_total_net_fare = $fare_details['net_fare'];
			$book_domain_markup = $fare_details['admin_markup'];
			$book_level_one_markup = 0;
			$book_gst_price = $fare_details['gst'];
			$admin_commission =$fare_details['admin_commission']; 
			$agent_commission = $fare_details['agent_commission'];
			$admin_tds = $fare_details['admin_tds'];
			$agent_tds = $fare_details['agent_tds'];

			//$book_hotel_markup_price = $fare_details['ss_markup_price'];
			$book_markup_gst = $fare_details['admin_markup_gst'];
			
			//Product Details
			
			$product_name = $tour_data['ResultToken']['ProductName'];
			$star_rating = $tour_data['ResultToken']['StarRating'];
			$product_code = $tour_data['ResultToken']['product_code'];
			$phone_number = $passenger_details[0]['Phoneno'];
			$alternate_number = '';
			$travel_date = date('Y-m-d',strtotime($tour_data['ProductDetails']['BookingDate']));

			$grade_code = $tour_data['ResultToken']['grade_code'];
			$grade_description  = $tour_data['ResultToken']['BlockTourDetails']['GradeDescription'];

			$email = $passenger_details[0]['Email'];
			
			$attributes['destination_id'] =$search_data['destination_id'];
			$attributes['duration'] = $tour_data['ResultToken']['Duration'];
			$attributes['GradeDescription'] = $tour_data['ResultToken']['BlockTourDetails']['GradeDescription'];
			$attributes['DeparturePoint'] = $tour_data['ResultToken']['BlockTourDetails']['DeparturePoint'];

			$image = $tour_data['ResultToken']['ProductImage'];
			$booking_id = '';
			$booking_reference = '';
			$confirmation_reference = '';

			$destination_name = $search_data['destination_name'];
			$payment_mode = 'PNHB1';
			$created_by_id = 0;
			
			//Maaster Hotel Table
			$ci->transferv1_model->save_booking_details ( $domain_origin, $tour_booking_status, $app_reference, $booking_source, $booking_id, $booking_reference, $confirmation_reference, $book_total_fare, $book_domain_markup, $book_level_one_markup, $currency, $product_name, $star_rating, $product_code, $phone_number, $alternate_number, $email, $travel_date, $grade_code,$image,$destination_name,$grade_description,$payment_mode, json_encode($attributes), $created_by_id,$currency_conversion_rate, VIATOR_TRANSFER_VERSION_1,$book_gst_price,$book_markup_gst,$admin_commission,$agent_commission,$admin_tds,$agent_tds,$book_total_net_fare);
		
			//Save Passenger Details
			foreach($passenger_details as $pax_k => $passenger_v) {
				//$title = $passenger_v['Title'];
					$first_name = $passenger_v['FirstName'];
					$middle_name = empty($passenger_v['MiddleName']) == true ? $passenger_v['LastName'] : $passenger_v['MiddleName'];
					$last_name = $passenger_v['LastName'];
					$phone = $passenger_v['Phoneno'];
					$pax_type = $passenger_v['PaxType'];
					if ($pax_type == "1") {
						$pax_type = "Adult";
					} else if($pax_type=="2") {
						$pax_type = "Child";
					}elseif ($pax_type=="3") {
						$pax_type = "Infant";
					}elseif ($pax_type=="5") {
						$pax_type = "Senior";
					}
					elseif ($pax_type=="4") {
						$pax_type = "Youth";
					}else{
						$pax_type="None";
					}	
					$ci->transferv1_model->save_booking_pax_details ( $app_reference,$first_name, $middle_name, $last_name, $phone, $email, $pax_type,$tour_booking_status);
			}
		}
		return $data;
	}


	/**
	 * Convert Fare Object to Domain Currency
	 */
	private function details_convert_to_domain_currency_object($FareDetails, $domain_currency_conversion=true)
	{
		if($domain_currency_conversion == true){
			$domain_base_currency = domain_base_currency();
		} else {
			$domain_base_currency = get_application_default_currency();
		}
		$currency_obj = new Currency(array('from' => get_application_default_currency() , 'to' => $domain_base_currency));			
		//Converting the API Fare Currency to Domain Currency
		//FARE DETAILS
		$TotalDisplayFare =	$FareDetails['TotalDisplayFare'];		
		$PriceBreakup = 	$FareDetails['PriceBreakup'];		
		$FareDetails['Currency'] = $domain_base_currency;
		$FareDetails['TotalDisplayFare'] = 						get_converted_currency_value($currency_obj->force_currency_conversion($TotalDisplayFare));		

		$FareDetails['PriceBreakup']['AgentCommission'] = 		get_converted_currency_value($currency_obj->force_currency_conversion($PriceBreakup['AgentCommission']));
		$FareDetails['PriceBreakup']['AgentTdsOnCommision'] = 	get_converted_currency_value($currency_obj->force_currency_conversion($PriceBreakup['AgentTdsOnCommision']));
		return $FareDetails;
	}
	/**
	 * Convert Fare Object to Domain Currency
	 */
	private function convert_to_domain_currency_object(& $FareDetails, $domain_currency_conversion=true)
	{
		if($domain_currency_conversion == true){
			$domain_base_currency = domain_base_currency();
		} else {
			$domain_base_currency = get_application_default_currency();
		}
		$currency_obj = new Currency(array('from' => get_application_default_currency() , 'to' => $domain_base_currency));
		$TotalDisplayFare =	$FareDetails['TotalDisplayFare'];
		
		$PriceBreakup = 	$FareDetails['PriceBreakup'];
		
		$FareDetails['Currency'] = $domain_base_currency;
		$FareDetails['TotalDisplayFare'] = 						get_converted_currency_value($currency_obj->force_currency_conversion($TotalDisplayFare));	

		$FareDetails['PriceBreakup']['AgentCommission'] = 		get_converted_currency_value($currency_obj->force_currency_conversion($PriceBreakup['AgentCommission']));
		$FareDetails['PriceBreakup']['AgentTdsOnCommision'] = 	get_converted_currency_value($currency_obj->force_currency_conversion($PriceBreakup['AgentTdsOnCommision']));
	}
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $app_reference
	 * @param unknown_type $sequence_number
	 */
	public function deduct_sightseeing_booking_amount($app_reference)
	{
		$ci = & get_instance();
		$app_reference = trim($app_reference);
		$data = $ci->db->query('select BD.* from viatortransfer_booking_details BD
								where BD.app_reference="'.$app_reference.'"')->row_array();
		if(valid_array($data) == true && in_array($data['status'], array('BOOKING_CONFIRMED')) == true){//Balance Deduction only on Confirmed Booking
			$agent_buying_price = ($data['total_fare']-$data['agent_commission']+$data['agent_tds']+$data['domain_markup']);
			$domain_booking_attr = array();
			$domain_booking_attr['app_reference'] = $app_reference;
			$domain_booking_attr['transaction_type'] = 'viator_transfer';
			//Deduct Domain Balance
			$ci->domain_management->debit_domain_balance($agent_buying_price, transferv1::get_credential_type(), get_domain_auth_id(), $domain_booking_attr);//deduct the domain balance
			//Save to Transaction Log
			$domain_markup = $data['domain_markup'];
			$level_one_markup = 0;
			$agent_transaction_amount = $agent_buying_price-$domain_markup;
			$currency = $data['currency'];
			$currency_conversion_rate = $data['currency_conversion_rate'];
			$remarks = 'Viator Transfer Transaction was Successfully done';
			$ci->domain_management_model->save_transaction_details ( 'viator_transfer', $app_reference, $agent_transaction_amount, $domain_markup, $level_one_markup, $remarks, $currency, $currency_conversion_rate);
		}
	}
	/**
	 * Elavarasi
	 * Checks is it a duplcaite hotel booking
	 */
	private function is_duplicate_ss_booking($app_reference)
	{
		$ci = & get_instance();
		$data['status'] = SUCCESS_STATUS;
		$data['message'] = '';
		$ss_booking_details = $ci->custom_db->single_table_records('viatortransfer_booking_details', '*', array('app_reference' => trim($app_reference)));
		
		if($ss_booking_details['status'] == true && valid_array($ss_booking_details['data'][0]) == true){
			$ss_booking_details = $ss_booking_details['data'][0];
			$Message = 'Duplicate Booking Not Allowed';
			$data['status'] = FAILURE_STATUS;
			$data['message'] = $Message;
		}
		return $data;
	}
	/**
	 * Get Booked Sightseeing Details
	 * @param unknown_type $app_reference
	 */
	public function get_ss_booking_transaction_details($app_reference)
	{
		$ci = & get_instance();
		$data['status'] = FAILURE_STATUS;
		$data['data'] = '';
		$data['message'] = '';
		
		$foramtted_booking_details = array();
		$app_reference = trim($app_reference);
		$booking_details = $ci->custom_db->single_table_records('viatortransfer_booking_details', '*', array('app_reference' => $app_reference));
		
		//below code edit by elavarasi
		if($booking_details['status'] == SUCCESS_STATUS && ($booking_details['data'][0]['status'] == 'BOOKING_CONFIRMED' || $booking_details['data'][0]['status'] == 'BOOKING_HOLD' )){
			$booking_details = $booking_details['data'][0];
			//Formate the data
			$foramtted_booking_details['ConfirmationNo'] = $booking_details['item_id'];
			$foramtted_booking_details['BookingRefNo'] = $booking_details['item_id'];
			$foramtted_booking_details['BookingId'] = $booking_details['itinerary_id'];
			

			//Below code added by ela
			$foramtted_booking_details['booking_status'] = $booking_details['status'];
			$data['status'] = SUCCESS_STATUS;
			$data['data']['BookingDetails'] = $foramtted_booking_details;
		} else {
			$data['message'] = 'Invalid Request';
		}
		return $data;
	}
	/**
	 * Elavarasi
	 * Update Cancellation Refund details
	 * @param unknown_type $cancellation_details
	 * @param unknown_type $app_reference
	 */
	public function update_domain_cancellation_refund_details($cancellation_details, $app_reference)
	{
		$ci = & get_instance ();
		$SSChangeRequestStatusResult = array();
		//Adding Travelomatix Cancellation Charges
		
		$upadted_cancellation_details = $this->add_cancellation_charge($cancellation_details);

		//debug($upadted_cancellation_details);
		$RefundedAmount = floatval(@$upadted_cancellation_details['RefundedAmount']);
		$CancellationCharge = floatval(@$upadted_cancellation_details['CancellationCharge']);
		//echo $RefundedAmount;
	//	echo $CancellationCharge;
		//Crdeting Refund Amount to Domain Balance
		$cancelltion_domain_attr = array();
		$cancelltion_domain_attr['app_reference'] = $app_reference;
		$cancelltion_domain_attr['transaction_type'] = 'viator_transfer';
		
		$ci->domain_management->credit_domain_balance($RefundedAmount, transferv1::get_credential_type(), get_domain_auth_id(), $cancelltion_domain_attr);
		//Adding Refund Details
		$domain_base_currency = domain_base_currency();
		$currency_obj = new Currency(array('from' => get_application_default_currency() , 'to' => $domain_base_currency));
		$currency_conversion_rate = $currency_obj->get_domain_currency_conversion_rate();
		$refund_status = 'PROCESSED';
		$ci->transferv1_model->update_refund_details($app_reference, $refund_status, $RefundedAmount,$CancellationCharge, $domain_base_currency, $currency_conversion_rate);
		//Saving Refund details in transaction log
		$fare = -($RefundedAmount);//dont remove: converting to negative
		$domain_markup=0;
		$level_one_markup=0;
		$remarks = 'Viator Transfer Refund was Successfully done';

		//echo $fare;
		$ci->domain_management_model->save_transaction_details ( 'viator_transfer', $app_reference, $fare, $domain_markup, $level_one_markup, $remarks, $domain_base_currency, $currency_conversion_rate);
		//Converting the API Fare Currency to Domain Currency
		//Assigning the cancellation data
		$RefundedAmount = 					get_converted_currency_value($currency_obj->force_currency_conversion($RefundedAmount));
		$CancellationCharge = 					get_converted_currency_value($currency_obj->force_currency_conversion($CancellationCharge));
		$SSChangeRequestStatusResult = $cancellation_details;
		$SSChangeRequestStatusResult['RefundedAmount'] = $RefundedAmount;
		$SSChangeRequestStatusResult['CancellationCharge'] = $CancellationCharge;

		
		return $SSChangeRequestStatusResult;
	}
	/**
	 * Add cancellation charge
	 * TODO: add Travelomatix cancellation charges
	 */
	private function add_cancellation_charge($cancellation_details)
	{
		$upadted_cancellation_details = array();
		$upadted_cancellation_details['RefundedAmount'] =		floatval(@$cancellation_details['RefundedAmount']);
		$upadted_cancellation_details['CancellationCharge'] =	floatval(@$cancellation_details['CancellationCharge']);
		return $upadted_cancellation_details;
	}

	/**
	 * FIXME: do it for plus and percentage
	 * Updates Agents Commission
	 * @param unknown_type $amount
	 */
	private function update_agent_commision($amount, $commission_percentage)
	{
		return (($amount * $commission_percentage) / 100);
	}
}
	