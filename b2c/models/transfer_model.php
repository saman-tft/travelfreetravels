<?php
/**
 * Library which has generic functions to get data
 * @package    Provab Application
 * @subpackage Transfer Model
 * @author     Chandrasekar T<chandrasekar.provab@gmail.com>
 * @version    V1
 */
Class Transfer_Model extends CI_Model
{
    /**
	 * get search data and validate it
	 */
	function get_safe_search_data($search_id)
	{
		$search_data = $this->get_search_data($search_id);
		// debug($search_data);exit;
		$success = true;
		$clean_search = '';
		if ($search_data != false) {
			//validate
			$temp_search_data = json_decode($search_data['search_data'], true);
			// debug($temp_search_data);exit;
			$clean_search = $this->clean_search_data($temp_search_data);
			$clean_search['data']['search_id'] = $search_data['origin'];
			 // debug($clean_search);exit;
			$success = $clean_search['status'];
			$clean_search = $clean_search['data'];
		} else {
			$success = false;
		}
		$response = array('status' => $success, 'data' => $clean_search);
		// debug($response);exit;
		return $response;
	}
	 function bookingcrs($condition=array(), $count=false, $offset=0, $limit=100000000000)
    {
    	// echo "cc";exit;
        $condition = $this->custom_db->get_custom_condition($condition);
        //BT, CD, ID
        if ($count) {
            $query = 'select count(distinct(BD.app_reference)) as total_records 
                    from transfer_booking_details BD
                    join transfer_booking_itinerary_details AS HBID on BD.app_reference=HBID.app_reference
                    left join user U on U.user_id = BD.created_by_id
					left join user_type UT on UT.origin = U.user_type
                    
                    where (U.user_type='.B2C_USER.' OR BD.created_by_id = 0) and  BD.domain_origin='.get_domain_auth_id().' '.$condition;
            // echo $query;exit;
                    // join payment_option_list AS POL on BD.payment_mode=POL.payment_category_code 
            $data = $this->db->query($query)->row_array();
            // debug($data);exit;
            return $data['total_records'];
        } else {
            $this->load->library('booking_data_formatter');
            $response['status'] = SUCCESS_STATUS;
            $response['data'] = array();
            $booking_itinerary_details  = array();
            $booking_customer_details   = array();
            $cancellation_details = array();
            $bd_query = 'select *,BD.created_datetime as created_datetime,U.first_name,U.last_name,U.user_type,U.agent_staff,U.agent_staff_id,U.agency_name from transfer_booking_details AS BD 
           				 left join user U on U.user_id = BD.created_by_id
							left join user_type UT on UT.origin = U.user_type
                        WHERE (U.user_type='.B2C_USER.' OR BD.created_by_id = 0) and BD.domain_origin='.get_domain_auth_id().' and BD.module_type = "transfers" '.$condition.'
                        order by BD.origin desc limit '.$offset.', '.$limit;
            $booking_details = $this->db->query($bd_query)->result_array();
            $app_reference_ids = $this->booking_data_formatter->implode_app_reference_ids($booking_details);
            if(empty($app_reference_ids) == false) {
                $id_query = 'select * from transfer_booking_itinerary_details AS ID 
                            WHERE ID.app_reference IN ('.$app_reference_ids.')';
                $cd_query = 'select * from  transfer_booking_passenger_details AS CD 
                            WHERE  CD.app_reference IN ('.$app_reference_ids.') ';
                // $cancellation_details_query = 'select * from transfer_cancellation_details AS HCD 
                //             WHERE HCD.app_reference IN ('.$app_reference_ids.')';
                $booking_itinerary_details  = $this->db->query($id_query)->result_array();
                $booking_customer_details   = $this->db->query($cd_query)->result_array();
                // $cancellation_details   = $this->db->query($cancellation_details_query)->result_array();
            }
            $response['data']['booking_details']            = $booking_details;
            $response['data']['booking_itinerary_details']  = $booking_itinerary_details;
            $response['data']['booking_customer_details']   = $booking_customer_details;
            // $response['data']['cancellation_details']   = $cancellation_details;
            // debug($booking_details);exit;
            return $response;
        }
    }
	
	/**
	 * Badri
	 * get service tax and TDs
	 * 
	 */
	function get_tax() {
		// echo $this->db->last_query();exit;
		$response ['data'] = array ();
	
		// $q=$this->db->query('select tds,service_tax from commission_master where module_type="transfer"' )->result_array();
		$q=$this->db->query('select api_value from b2b_transfer_commission_details where 1=1' )->result_array();
		// echo $this->db->last_query();exit;
		// $response ['data']['tds']=$q[0]['tds'];
		$response ['data']['service_tax']=$q[0]['api_value'];
		return $response;
	}
	function get_cfee() {
	
		$result=array();
		$qry = "select * from convenience_fees where module = 'transfer'";
		$query=$this->db->query($qry);
	
		foreach ($query->result_array() as $row)
		{
			$result[]=$row;
		}
	
		return $result;
	}
    /**
	 * get search data without doing any validation
	 * @param $search_id
	 */
	function get_search_data($search_id)
	{
		if (empty($this->master_search_data)) {
			$search_data = $this->custom_db->single_table_records('search_history', '*', array('origin' => $search_id, 'search_type' => META_TRANSFER_COURSE));
			if ($search_data['status'] == true) {
				$this->master_search_data = $search_data['data'][0];
			} else {
				return false;
			}
		}
		return $this->master_search_data;
	}
	
	/**
	 * hotel address
	 * @param $hotel_id
	 */
	function get_searched_hotel_address($hotel_id)
	{
		$query = 'Select hotel_name, hotel_city, hotel_code,address,postal_code, origin from hb_hotel_details where hotel_code='.$hotel_id;
		
		return $this->db->query($query)->result_array();
	}
	
	
	/**
	 * Save search data for future use - Analytics
	 * @param array $params
	 */
	function save_search_data($search_data, $type)
	{	

		// debug($search_data);
		$data['domain_origin'] = get_domain_auth_id();
		$data['search_type'] = $type;
		$data['created_by_id'] = intval(@$this->entity_user_id);
		$data['created_datetime'] = date('Y-m-d H:i:s');
		$data['from_location'] = $search_data['from_transfer_type'];
		$data['to_location'] = $search_data['to_transfer_type'];
		$data['from_code'] = $search_data['from_loc_id'];
		$data['to_code'] = $search_data['to_loc_id'];
		$data['from_location_name'] = $search_data['transfer_from'];
		$data['to_location_name'] = $search_data['transfer_to'];
		$data['adult'] = $search_data['adult'];
		$data['child'] = $search_data['child'];
		$data['departure_date'] = date('Y-m-d H:m',strtotime($search_data['depature']));
		
		if(isset($search_data['adult_ages']) && valid_array($search_data['adult_ages'])) {
			$data['adult_ages'] = json_encode($search_data['adult_ages']);
		} 
		if(isset($search_data['child_ages']) && valid_array($search_data['child_ages'])) {
			$data['child_ages'] = json_encode($search_data['child_ages']);
		}
		if(isset($search_data['return'])){
			$data['return_date'] = date('Y-m-d H:m',strtotime($search_data['return']));
		}
		$data['trip_type'] =  $search_data['transfer_type'];

		// debug($data);exit();
		$this->custom_db->insert_record('search_transfer_history', $data);
	}
	
	/**
	 * get all the booking source which are active for current domain
	 */
	function transfer_booking_source()
	{
		$query = 'select BS.source_id, BS.origin from meta_course_list AS MCL, booking_source AS BS, activity_source_map AS ASM WHERE
		MCL.origin=ASM.meta_course_list_fk and ASM.booking_source_fk=BS.origin and MCL.course_id='.$this->db->escape(META_TRANSFERS_COURSE).'
		and BS.booking_engine_status='.ACTIVE.' AND MCL.status='.ACTIVE.' AND ASM.status="active"';
		return $this->db->query($query)->result_array();
	}

	 /**
     * get all the booking source which are active for current domain
     */
    function active_booking_source() {
        $query = 'select BS.source_id, BS.origin from meta_course_list AS MCL, booking_source AS BS, activity_source_map AS ASM WHERE
		MCL.origin=ASM.meta_course_list_fk and ASM.booking_source_fk=BS.origin and MCL.course_id=' . $this->db->escape(META_TRANSFER_COURSE) . '
		and BS.booking_engine_status=' . ACTIVE . ' AND MCL.status=' . ACTIVE . ' AND ASM.status="active"';
     //   echo $query;exit();
        return $this->db->query($query)->result_array();
    }

	function top_transfer_location($Limit){
//		$filter = array('status'=>1);
		$filter = array();
        $result = $this->custom_db->single_table_records('transfer_location_details', '*', $filter, 0, $Limit, array('origin' => 'desc',));
        return @$result['data'];
	}
	/**
	 * Clean up search data
	 */
	function clean_search_data($temp_search_data)
	{  
		$success = true;
		$clean_search['from'] = $temp_search_data['transfer_from'];
		$clean_search['to'] = $temp_search_data['transfer_to'];
		
	     if((strtotime($temp_search_data['depature']) > time()) || date('Y-m-d',strtotime($temp_search_data['depature'])) == date('Y-m-d')) {
			$clean_search['from_date'] = $temp_search_data['depature'];
		}else {
			$success = false;
		}
		if(isset($temp_search_data['return']) && strtotime($temp_search_data['return']) > time()) {
			$clean_search['to_date'] = $temp_search_data['return'];
		}
		$clean_search['native_country'] = $temp_search_data['native_country'];
		$clean_search['nationality_code'] = $temp_search_data['native_country'];
		$clean_search['from_code'] = $temp_search_data['from_loc_id'];
		$clean_search['to_code'] = $temp_search_data['to_loc_id'];
		$clean_search['from_transfer_type'] = $temp_search_data['from_transfer_type'];
		$clean_search['to_transfer_type'] = $temp_search_data['to_transfer_type'];
		$clean_search['adult'] = $temp_search_data['adult'];
		$clean_search['child'] = $temp_search_data['child'];
		$depature_date_time = explode(" ", $temp_search_data['depature']);
		//debug($depature_date_time);exit;
		$clean_search['depature_time_flight'] = $depature_date_time[1];
		if(isset($depature_date_time[0]))
		$clean_search['depature'] = $depature_date_time[0];
		if(isset($depature_date_time[1]))
		$clean_search['depature_time'] = preg_replace('/[^A-Za-z0-9\-]/', '', $depature_date_time[1]);
		
		if(isset($temp_search_data['adult_ages']) && valid_array($temp_search_data['adult_ages'])) {
			$clean_search['adult_ages']	= $temp_search_data['adult_ages'];
		}
		
		if(isset($temp_search_data['child_ages']) && valid_array($temp_search_data['child_ages'])) {
			$clean_search['child_ages']	= $temp_search_data['child_ages'];
		}
		
		if(isset($temp_search_data['return'])){
			$return_date_time = explode(" ", $temp_search_data['return']);
			$clean_search['return_time_flight'] = $return_date_time[1];
			if(isset($return_date_time[0]))
			$clean_search['return'] = $return_date_time[0];
			if(isset($return_date_time[1]))
			$clean_search['return_time'] = preg_replace('/[^A-Za-z0-9\-]/', '', $return_date_time[1]);
			
		}
		if (isset($temp_search_data['markup_type']) == true) {
                $clean_search['markup_type'] = $temp_search_data['markup_type'];
            }

            if (isset($temp_search_data['markup_value']) == true) {
                $clean_search['markup_value'] = $temp_search_data['markup_value'];
            }

            if (isset($temp_search_data['markup_currency']) == true) {
                $clean_search['markup_currency'] = $temp_search_data['markup_currency'];
            }
		$clean_search['trip_type'] =  $temp_search_data['transfer_type']; //debug($clean_search); exit;
		return array('data' => $clean_search, 'status' => $success);
	}
	
    /**
	 * Get airport list
	 * 
	 */
	function get_airport_list($search_chars)
	{
		$raw_search_chars = $this->db->escape($search_chars);
		$r_search_chars = $this->db->escape($search_chars.'%');
		$search_chars = $this->db->escape('%'.$search_chars.'%');
		
		$query = 'Select * from flight_airport_list where airport_city like '.$search_chars.'
		OR airport_code like '.$search_chars.' OR country like '.$search_chars.'
		ORDER BY top_destination DESC,
		CASE
			WHEN	airport_code	LIKE	'.$raw_search_chars.'	THEN 1
			WHEN	airport_city	LIKE	'.$raw_search_chars.'	THEN 2
			WHEN	country			LIKE	'.$raw_search_chars.'	THEN 3

			WHEN	airport_code	LIKE	'.$r_search_chars.'	THEN 4
			WHEN	airport_city	LIKE	'.$r_search_chars.'	THEN 5
			WHEN	country			LIKE	'.$r_search_chars.'	THEN 6

			WHEN	airport_code	LIKE	'.$search_chars.'	THEN 7
			WHEN	airport_city	LIKE	'.$search_chars.'	THEN 8
			WHEN	country			LIKE	'.$search_chars.'	THEN 9
			ELSE 10 END
		LIMIT 0, 20';
		//debug($query);exit;
		return $this->db->query($query);

	}
	
    /**
	 * Get hotel list
	 * 
	 */
	function get_hotels_list($search_chars)
	{
		$search_chars = $this->db->escape('%'.$search_chars.'%');
		$query = 'Select hotel_name, hotel_city, hotel_code, origin from hb_hotel_details where hotel_city like '.$search_chars.'
		OR hotel_name like '.$search_chars.' OR hotel_code like '.$search_chars.' LIMIT 0, 20';
		
		return $this->db->query($query);
		//return $data;
	}
	
	/**
	 * Get hotel list
	 * 
	 */
	function get_airline_list($search_chars)
	{
		$search_chars = $this->db->escape('%'.$search_chars.'%');
		$query = 'Select code, name from airline_list where name like '.$search_chars.' LIMIT 0, 20';
		
		return $this->db->query($query);
		//return $data;
	}
	public function get_transfer_data($id='') {
		$this->db->select ( "*" );
		$this->db->from ( "transfer_info" );
		$this->db->where ( 'transfer_info.id', $id );
		return $this->db->get ()->row ();
	}
	/**
	 * Return Booking Details based on the app_reference passed
	 * @param $app_reference
	 * @param $booking_source
	 * @param $booking_status
	 */
	function get_booking_details($app_reference, $booking_source, $booking_status='')
	{
		$response['status'] = FAILURE_STATUS;
		$response['data'] = array();
		$booking_cancellation_details = array();
		//$transfer_query = 'select * from hb_transfer_booking_details BD WHERE BD.app_reference like '.$this->db->escape($app_reference);
		
		if(($booking_status == 'CONFIRMED') ||($booking_status == 'BOOKING_CONFIRMED'))
		{
			$booking_status = 'BOOKING_CONFIRMED';
		}
		
		$td_query = 'select * from hb_transfer_booking_transction_details TD WHERE TD.app_reference like '.$this->db->escape($app_reference);
		if (empty($booking_source) == false) {
			$td_query .= '	AND TD.booking_source = '.$this->db->escape($booking_source);
		}
		if (empty($booking_status) == false) {
			$td_query .= ' AND TD.status = '.$this->db->escape($booking_status);
		}
		
		//$In_Out_id = "select booking_reference,transfer_type from hb_transfer_service_details WHERE app_reference=".$this->db->escape($app_reference);
	    //debug($booking_id[0]['booking_reference']); exit;	
		
		$id_query = 'select * from hb_transfer_contact_details CD WHERE CD.app_reference='.$this->db->escape($app_reference);
		$bd_query = 'select * from hb_transfer_booking_details BD WHERE BD.app_reference like '.$this->db->escape($app_reference);
		$cd_query = 'select * from hb_transfer_paxes_details PD WHERE PD.app_reference='.$this->db->escape($app_reference);
		$sd_query = 'select * from hb_transfer_service_details SD WHERE SD.app_reference='.$this->db->escape($app_reference);
		
	    //$td_query = 'select * from hb_transfer_booking_transction_details TD WHERE TD.app_reference='.$this->db->escape($app_reference);	
		$tcd_query = 'select TCD.* from hb_transfer_cancellation_policy TCD WHERE TCD.app_reference='.$this->db->escape($app_reference); 
       // debug($tcd_query);exit;
		$response['data']['booking_transction_details']	= $this->db->query($td_query)->result_array();
		$response['data']['booking_service_details']	= $this->db->query($sd_query)->result_array();
		$response['data']['booking_transfer_details']   = $this->db->query($bd_query)->result_array();
		$response['data']['booking_contact_details']	= $this->db->query($id_query)->result_array();
		$response['data']['booking_customer_details']	= $this->db->query($cd_query)->result_array();
	 	$booking_cancellation_details = $this->db->query($tcd_query)->result_array();
	 	if(isset($booking_cancellation_details) && !empty($booking_cancellation_details))
	 	$response['data']['booking_cancellation_details'] = $this->db->query($tcd_query)->result_array(); 
		
	 	if (valid_array($response['data']['booking_transction_details']) == true and valid_array($response['data']['booking_contact_details']) == true and valid_array($response['data']['booking_customer_details']) == true and valid_array($response['data']['booking_service_details']) == true ) {
			$response['status'] = SUCCESS_STATUS;	  
		}
        //debug($response); exit;
        
		return $response;
	}
	
	/**
	 * Save payment data for future use - Transfers
	 * @param array $params
	 */
	function save_payment_details($params, $book_id)
	{	$request_params = array();
		$this->db->from('payment_gateway_details')
            ->where('app_reference', $book_id);
    	$rs = $this->db->get();
	    if ($rs->num_rows() == '0' ){
	    	if(isset($params) && valid_array($params)){
	    		$request_params['name'] = $params['first_name']." ".$params['last_name'];
	    		$request_params['billing_email'] = $params['billing_email'];
	    		$request_params['passenger_contact'] = $params['passenger_contact'];
	    		$request_params['book_id'] = $params['book_id'];
	    		$request_params['booking_source'] = $params['booking_source'];
	    		$request_params['creation_user'] = $params['creation_user'];
	    		$request_params['SPUI'] = $params['SPUI'];
	    		$request_params['adult_count'] = $params['adult_count'];
	    		$request_params['child_count'] = $params['child_count'];
	    		$request_params['book_origin_id'] = $params['book_origin'];
	    		$request_params['currency_code'] = $params['currency_code'];
	    	foreach($params['transfer_type'] as $transfer_k => $transfer_v){	
		    		$request_params[$transfer_v]['agency_code'] = $params['agency_code'][$transfer_k];
		    		$request_params[$transfer_v]['transfer_amount'] = $params['total_amount'][$transfer_k];
		    		$request_params[$transfer_v]['pickup_location_name'] = $params['pickup_location_name'][$transfer_k];
		    		$request_params[$transfer_v]['destination_location_name'] = $params['destination_location_name'][$transfer_k];
		    		$request_params[$transfer_v]['vehicle_type'] = $params['vehicle_type'][$transfer_k];
		    		$request_params[$transfer_v]['from_date'] = $params['from_date'][$transfer_k];
		    		$request_params[$transfer_v]['from_time'] = $params['from_time'][$transfer_k];
	    		}
	    		
	    	}
			$data['domain_origin'] = get_domain_auth_id();
			$data['app_reference'] = $book_id;
			$data['status'] = 'pending';
			$data['amount'] = $params['transfers_total_amount'];
			$data['currency'] = $params['currency_code'];
			$data['request_params'] = json_encode($request_params);
			$data['created_datetime'] = date("Y-m-d H:i:sa");
				if($this->db->insert('payment_gateway_details', $data))
				{
				    return true;   // to the controller
				}
		}
	}
	
	/**
	 * Update payment status for future use - Transfers
	 * @param array $params
	 */
	function update_payment_details($app_reference_id, $payment_params)
	{   
		$data = array();
		$data['response_params'] = json_encode($payment_params);
		$data['status'] = 'accepted';
	    $this->db->update('payment_gateway_details', $data, array('app_reference' => $app_reference_id));
		//return true;
	}
   /**
	 * return booking list
	 */
	function booking($condition=array(), $count=false, $offset=0, $limit=100000000000)
    {
        $condition = $this->custom_db->get_custom_condition($condition);
        // debug($condition);exit;
        //BT, CD, ID
        if ($count) {
            $query = 'select count(distinct(BD.app_reference)) as total_records 
                    from transfer_booking_details BD
                    join transfer_booking_itinerary_details AS HBID on BD.app_reference=HBID.app_reference
                    where BD.domain_origin='.get_domain_auth_id().' '.$condition;
            // echo $query;
                    
                    // join payment_option_list AS POL on BD.payment_mode=POL.payment_category_code 
            $data = $this->db->query($query)->row_array();
            // debug($data);exit;
            return $data['total_records'];
        } else {
            $this->load->library('booking_data_formatter');
            $response['status'] = SUCCESS_STATUS;
            $response['data'] = array();
            $booking_itinerary_details  = array();
            $booking_customer_details   = array();
            $cancellation_details = array();
            $bd_query = 'select * from transfer_booking_details AS BD 
                        WHERE BD.domain_origin='.get_domain_auth_id().' '.$condition.'
                        order by BD.origin desc limit '.$offset.', '.$limit;
                       // echo $bd_query;exit;
            $booking_details = $this->db->query($bd_query)->result_array();
            $app_reference_ids = $this->booking_data_formatter->implode_app_reference_ids($booking_details);
            if(empty($app_reference_ids) == false) {
                $id_query = 'select * from transfer_booking_itinerary_details AS ID 
                            WHERE ID.app_reference IN ('.$app_reference_ids.')';
                $cd_query = 'select * from  transfer_booking_passenger_details AS CD 
                            WHERE  CD.app_reference IN ('.$app_reference_ids.') ';
                // $cancellation_details_query = 'select * from transfer_cancellation_details AS HCD 
                //             WHERE HCD.app_reference IN ('.$app_reference_ids.')';
                $booking_itinerary_details  = $this->db->query($id_query)->result_array();
                $booking_customer_details   = $this->db->query($cd_query)->result_array();
                // $cancellation_details   = $this->db->query($cancellation_details_query)->result_array();
            }
            $response['data']['booking_details']            = $booking_details;
            $response['data']['booking_itinerary_details']  = $booking_itinerary_details;
            $response['data']['booking_customer_details']   = $booking_customer_details;
            // $response['data']['cancellation_details']   = $cancellation_details;
            return $response;
        }
    }

    function emulate_booking($condition=array(), $count=false, $offset=0, $limit=100000000000)
    {
        $condition = $this->custom_db->get_custom_condition($condition);
        // debug($condition);exit;
        //BT, CD, ID
        if ($count) {
            $query = 'select count(distinct(BD.app_reference)) as total_records 
                    from transfer_booking_details BD
                    join transfer_booking_itinerary_details AS HBID on BD.app_reference=HBID.app_reference
                    where BD.emulate_booking=1 and BD.domain_origin='.get_domain_auth_id().' and BD.created_by_id ='.$GLOBALS['CI']->entity_user_id.' '.$condition;
            // echo $query;exit;
                    
                    // join payment_option_list AS POL on BD.payment_mode=POL.payment_category_code 
            $data = $this->db->query($query)->row_array();
            // debug($data);exit;
            return $data['total_records'];
        } else {
            $this->load->library('booking_data_formatter');
            $response['status'] = SUCCESS_STATUS;
            $response['data'] = array();
            $booking_itinerary_details  = array();
            $booking_customer_details   = array();
            $cancellation_details = array();
            $bd_query = 'select * from transfer_booking_details AS BD 
                        WHERE BD.emulate_booking = 1 and BD.domain_origin='.get_domain_auth_id().' and BD.created_by_id ='.$GLOBALS['CI']->entity_user_id.' '.$condition.'
                        order by BD.origin desc limit '.$offset.', '.$limit;
            $booking_details = $this->db->query($bd_query)->result_array();
            $app_reference_ids = $this->booking_data_formatter->implode_app_reference_ids($booking_details);
            if(empty($app_reference_ids) == false) {
                $id_query = 'select * from transfer_booking_itinerary_details AS ID 
                            WHERE ID.app_reference IN ('.$app_reference_ids.')';
                $cd_query = 'select * from  transfer_booking_passenger_details AS CD 
                            WHERE  CD.app_reference IN ('.$app_reference_ids.') ';
                // $cancellation_details_query = 'select * from transfer_cancellation_details AS HCD 
                //             WHERE HCD.app_reference IN ('.$app_reference_ids.')';
                $booking_itinerary_details  = $this->db->query($id_query)->result_array();
                $booking_customer_details   = $this->db->query($cd_query)->result_array();
                // $cancellation_details   = $this->db->query($cancellation_details_query)->result_array();
            }
            $response['data']['booking_details']            = $booking_details;
            $response['data']['booking_itinerary_details']  = $booking_itinerary_details;
            $response['data']['booking_customer_details']   = $booking_customer_details;
            // $response['data']['cancellation_details']   = $cancellation_details;
            return $response;
        }
    }
        
        function booking_guest($PNR,$Email)
	{
		
		$this->load->library('booking_data_formatter');
	        $td_query1 = 'select * from hb_transfer_booking_details where (booking_reference="'.$PNR.'" || app_reference="'.$PNR.'")';
		$booking_details1 = $this->db->query($td_query1)->result_array(); 
               
                $td_query = 'select * from hb_transfer_booking_transction_details where app_reference="'.$booking_details1[0]['app_reference'].'" && billing_email="'.$Email.'"';
                $booking_details = $this->db->query($td_query)->result_array(); 
                
		$app_reference_ids = $this->booking_data_formatter->implode_app_reference_ids($booking_details);
		if(empty($app_reference_ids) == false) {
		$id_query = 'select * from hb_transfer_contact_details CD WHERE CD.app_reference IN ('.$app_reference_ids.')';
		$bd_query = 'select * from hb_transfer_booking_details BD WHERE BD.app_reference IN ('.$app_reference_ids.')';
		$cd_query = 'select * from hb_transfer_paxes_details PD WHERE PD.app_reference IN ('.$app_reference_ids.')';
		$sd_query = 'select * from hb_transfer_service_details SD WHERE SD.app_reference IN('.$app_reference_ids.')';
		$tcd_query = 'select TCD.* from hb_transfer_cancellation_policy TCD WHERE TCD.app_reference IN('.$app_reference_ids.')'; 
		
		//debug($id_query);
		$booking_contact_details = $this->db->query($id_query)->result_array();
	        $booking_customer_details = $this->db->query($cd_query)->result_array();
		$booking_service_details = $this->db->query($sd_query)->result_array();
		$booking_transfer_details = $this->db->query($bd_query)->result_array();
		$booking_cancellation_details = $this->db->query($tcd_query)->result_array();
		}
		$response['data']['booking_transction_details']	= $booking_details;
		$response['data']['booking_service_details']	= $booking_service_details;
		$response['data']['booking_transfer_details']   = $booking_transfer_details;
		$response['data']['booking_contact_details']	= $booking_contact_details;
		$response['data']['booking_customer_details']	= $booking_customer_details;
		if(isset($booking_cancellation_details) && !empty($booking_cancellation_details))
		$response['data']['booking_cancellation_details']	= $booking_cancellation_details;
	 
        
		return $response;
	}
	
	function getMarkupData($markMod,$module){
		// echo $markMod.",".$module;exit;
		$strLevel = "level_3";
		$query = "select origin, value, value_type, markup_currency from markup_list where type='generic' and module_type='$markMod' and markup_level='$strLevel' ";
		$result = $this->db->query($query)->result_array();
		return $result;
	}	
	public function get_country_id($id) {
		$this->db->where ( "country_list",$id );
		$qur = $this->db->get ( "country_list" );
		return $qur->result ();
	}
	public function get_map_timing($id,$date_range_id) {
		$this->db->where ( "reference_id",$id );
		$this->db->where ( "date_range_id",$date_range_id );
		$qur = $this->db->get ( "transfer_map_vehicle_driver" );
		return $qur->result ();
	}
	 public function transfersearch($data,$weekday,$price_id='')
     {       
     	$pax = $data['data']['adult']+$data['data']['child'];
     	$dept_time= date("g:i A", strtotime($data['data']['depature_time_flight']));
     	$from_date=date('Y-m-d',strtotime($data['data']['from_date']));
     	// $from_date=date('Y-m-d',strtotime($data['data']['from_date']));
     	// $from_date=date('Y-m-d',strtotime($data['data']['from_date']));
        $this->db->select("a.id,a.transfer_name,a.source,a.destination,a.distance,a.duration,a.image,a.rating,a.description,a.exclusive_ride,a.contact_address,a.contact_email,a.price_excludes,a.price_includes,a.meetup_location,a.general_list_info,a.pick_up_info,a.guidelines_list,a.created_by_id,c.vehicle_id,c.driver_id,e.vehicle_name,e.vehicle_image,e.max_passenger,d.id as price_id,d.price,d.display_price,d.currency,f.package_types_name,h.driver_name,h.contact_number as driver_contact_number,c.shift_time_from,c.shift_time_to,c.shift_to_time,d.shift_from_min,d.shift_to_min,d.shift_to_time as price_time,c.date_range_id");
		$this->db->from ( "transfer_info as a" );
		$this->db->join ( 'transfer_duration as b', 'b.reference_id=a.id' );
		$this->db->join ( 'transfer_map_vehicle_driver as c', 'c.reference_id=a.id and c.date_range_id=b.id' );
		$this->db->join ( 'transfer_price_info as d','d.reference_id=a.id');
		$this->db->join ( 'transfer_vehicle_info as e','e.id=c.vehicle_id');
		$this->db->join ( 'transfer_driver_info as h','h.id=c.driver_id');
		$this->db->join ( 'package_types as f','f.package_types_id=a.transfer_type');
		$this->db->join ( 'all_nationality_country as g','g.origin=d.nationality_group');
		$this->db->where('find_in_set("'.$data['data']['nationality_code'].'", g.include_countryCodes)');
		// $this->db->where("a.start_date<=",$from_date);
		// $this->db->where("a.expiry_date>=",$from_date);
		// $this->db->where("c.shift_time_from <=",$data['data']['deptur_time']);
		// $this->db->where("c.shift_to_time >",$data['data']['deptur_time']);
		// $this->db->where("d.shift_from_min <=",$data['data']['deptur_time']);
		// $this->db->where("d.shift_to_time >",$data['data']['deptur_time']);
		$this->db->where("a.source",$data['data']['from']);
		$this->db->where("a.status",'ACTIVE');
		$this->db->where("a.destination",$data['data']['to']);
		$this->db->where("e.max_passenger >=",$pax);
		// $where = "FIND_IN_SET('".$data['data']['nationality_code']."', g.country)"; 
        // $this->db->where($where);
        $this->db->where("d.date_from <=",$from_date);
        $this->db->where("d.date_to >=",$from_date);
        $this->db->where("b.start_date <=",$from_date);
        $this->db->where("b.expiry_date >=",$from_date);
		// $this->db->where("c.date",$from_date);
		// $this->db->where("d.shift_day",$weekday);
		$where = "FIND_IN_SET('".$weekday."', d.shift_day)"; 
        $this->db->where($where);
		// $this->db->where("d.country",$data['data']['native_country']);	
		$this->db->where("f.module_type",'transfers');
		if(!empty($price_id))
		{
			$this->db->where("d.id",$price_id);
		}
		$this->db->group_by ( 'd.id');
		$query = $this->db->get ();
		//echo $this->db->last_query();exit;
		if($query->num_rows){
				
				return $query->result ();

		}
    }
    function save_package_booking_transaction_details($promocode_discount_val, $app_reference, $transaction_status, $status_description, $pnr, $book_id, $source, $ref_id, $attributes,
	 $currency, $total_fare)
	{
		$data['app_reference'] = $app_reference;
		$data['status'] = $transaction_status;
		$data['status_description'] = $status_description;
		$data['pnr'] = $pnr;
		$data['book_id'] = $book_id;
		$data['source'] = $source;
		$data['ref_id'] = $ref_id;
		$data['attributes'] = $attributes;
		

		$data['discount'] = $promocode_discount_val;
		$data['total_fare'] = ($total_fare - $promocode_discount_val);
		
		$data['currency'] = $currency;

		
		// debug($data);die("in save_package_booking_transaction_details ");
		return $this->custom_db->insert_record('transfer_booking_transaction_details', $data);
	}
	function save_package_booking_passenger_details(
	$app_reference, $passenger_type, $is_lead, $first_name,$last_name,
	$gender, $passenger_nationality, $status,
	$attributes, $flight_booking_transaction_details_fk, $adult, $child)
	{
		$data['app_reference'] = $app_reference;
		$data['flight_booking_transaction_details_fk'] = $flight_booking_transaction_details_fk;
		$data['passenger_type'] = $passenger_type;
		$data['is_lead'] = $is_lead;
		
		$data['first_name'] = $first_name;
		
		$data['last_name'] = $last_name;
		
		$data['gender'] = $gender;
		$data['passenger_nationality'] = $passenger_nationality;
		
		$data['status'] = $status;
		$data['attributes'] = $attributes;
		
		$data['adult'] = $adult;
		$data['child'] = $child;

		$sqlq = "insert into `transfer_booking_passenger_details` SET `app_reference`='".$data['app_reference']."',`flight_booking_transaction_details_fk`='".$data['flight_booking_transaction_details_fk']."',`passenger_type`='".$data['passenger_type']."',`is_lead`='".$data['is_lead']."',`first_name`='".$data['first_name']."',`last_name`='".$data['last_name']."',`gender`='".$data['gender']."',`passenger_nationality`='".$data['passenger_nationality']."',`status`='".$data['status']."',`attributes`='".$data['attributes']."',`adult`='".$data['adult']."',`child`='".$data['child']."'";

		
		
		// debug($this->db->last_query());die;
		//debug($data);exit("package_booking_passenger_details");

		return $this->db->query($sqlq);

		//return $this->custom_db->insert_record('package_booking_passenger_details', $data);
	}
	function save_package_booking_details(
	$domain_origin, $status, $app_reference, $booking_source, $phone, $alternate_number, $email,$payment_mode,	$attributes, $created_by_id, 
	$transaction_currency, $currency_conversion_rate,$pack_id,$date_of_travel,$amount='', $transfer_id='',$emulate_booking='',$emulate_user='')
	{
		//$data['module_type'] = 'holiday';
		$data['module_type'] = 'transfers';
		$data['domain_origin'] = $domain_origin;
		$data['status'] = $status;
		$data['app_reference'] = $app_reference;
		$data['booking_source'] = $booking_source;
		$data['phone'] = $phone;
		$data['package_type'] = $pack_id;
		$data['email'] = $email;
		$data['tours_id'] = $transfer_id;
		$data['payment_mode'] = $payment_mode;
		$data['attributes'] = $attributes;
		$data['created_by_id'] = $created_by_id;
		$data['created_datetime'] = date('Y-m-d H:i:s');
		$data['date_of_travel'] = $date_of_travel;
		$data['basic_fare']=$amount;
		$data['emulate_booking'] = $emulate_booking;
		$data['emulate_user']=$emulate_user;
		
		
		$data['currency'] = $transaction_currency;
		$data['currency_conversion_rate'] = $currency_conversion_rate;
	// debug($data);exit;
		$this->custom_db->insert_record('transfer_booking_details', $data);
// debug($this->db->last_query());exit;
		//echo $sqlq;
		//debug($data);die("382");


		// $this->custom_db->insert_record('package_booking_details', $data);

		 
	}
	function change_confirm_status($book_id){
		$res = $this->custom_db->update_record('transfer_booking_details',array('status'=>'BOOKING_CONFIRMED','payment_status'=>'paid'),array('app_reference'=>$book_id));
		// debug($this->db->last_query());exit;
		$res1 = $this->custom_db->update_record('transfer_booking_transaction_details',array('status'=>'BOOKING_CONFIRMED'),array('app_reference'=>$book_id));

		$res2 = $this->custom_db->update_record('transfer_booking_passenger_details',array('status'=>'BOOKING_CONFIRMED'),array('app_reference'=>$book_id));
		// debug(QUERY_SUCCESS);
		// debug($res);exit();
		if($res == QUERY_SUCCESS){
			return SUCCESS_STATUS;
		}
		else{
			return FAILURE_STATUS;
		}
	}
	function get_booking_details_transfer($app_reference, $booking_source, $booking_status='')
    {
    	if($booking_status=='BOOKING_CANCELLED' ){
    	$booking_status=$booking_status;	
    	}else{
    	$booking_status="BOOKING_CONFIRMED";
    	}
        $response['status'] = FAILURE_STATUS;
        $response['data'] = array();

       
        
        	 $bd_query = 'select * from transfer_booking_details AS BD WHERE BD.app_reference like '.$this->db->escape($app_reference);
        if (empty($booking_source) == false) {
            $bd_query .= '  AND BD.booking_source = '.$this->db->escape($booking_source);
        }
        if (empty($booking_status) == false) {
            $bd_query .= ' AND BD.status = '.$this->db->escape($booking_status);
        }
        $id_query = 'select * from transfer_booking_itinerary_details AS ID WHERE ID.app_reference='.$this->db->escape($app_reference);
        $cd_query = 'select * from transfer_booking_passenger_details AS CD WHERE CD.app_reference='.$this->db->escape($app_reference);
        // $cancellation_details_query = 'select HCD.* from sightseeing_cancellation_details AS HCD WHERE HCD.app_reference='.$this->db->escape($app_reference);
        $response['data']['booking_details']            = $this->db->query($bd_query)->result_array();
        //debug($this->db->last_query());
		//debug($response['data']['booking_details']);exit;

        $response['data']['booking_itinerary_details']  = $this->db->query($id_query)->result_array();

        $response['data']['booking_customer_details']   = $this->db->query($cd_query)->result_array();
        // $response['data']['cancellation_details']   = $this->db->query($cancellation_details_query)->result_array();


        	if (valid_array($response['data']['booking_details']) == true and  valid_array($response['data']['booking_customer_details']) == true) {
            $response['status'] = SUCCESS_STATUS;
        }
        
     // debug($response);exit();
        return $response;
    }
    function save_booking_itinerary_details($app_reference, $location, $travel_date,$transfer_name,$price_id,$vehicle_image,$distance, $status, $transfer_price,$admin_net_fare_markup,$admin_markup, $agent_markup, $currency, $attributes,$book_total_fare,$agent_commission,$agent_tds,$admin_com,$admin_tds,$api_raw_fare,$agent_buying_price, $gst='', $convenience_fee, $search_level_markup, $total_fare, $time_start, $driver_name, $driver_contact_number, $travel_from, $remarks_user)
    {
        $data['app_reference'] = $app_reference;
        $data['travel_from'] = $travel_from;
        $data['location'] = $location;
        $data['travel_date'] = $travel_date;
        $data['transfer_name'] = $transfer_name;
        $data['price_id'] = $price_id;
        $data['vehicle_image'] = $vehicle_image;
        $data['driver_name'] = $driver_name;
        $data['driver_contact_number'] = $driver_contact_number;
        $data['travel_time'] = $time_start;
        $data['distance'] = $distance;
        $data['status'] = $status;        
        $data['total_fare'] = $transfer_price;
        $data['admin_net_markup'] = $admin_net_fare_markup;
        $data['admin_markup    '] = $admin_markup;
        $data['agent_markup'] = $agent_markup;
        $data['currency'] = $currency;
        $data['attributes'] = $attributes;
        $data['agent_commission'] = 13;
        $data['agent_tds'] = 1;
        $data['admin_commission'] = 1;
        $data['admin_tds'] = 1;
        $data['api_raw_fare'] = 1;
        $data['agent_buying_price'] = $agent_buying_price;
        $data['gst'] = $gst;        
        $data['convenience_fee'] = $convenience_fee;        
        $data['search_level_markup'] = $search_level_markup;        
        $data['grand_total'] = $total_fare;
        $data['customer_remarks'] = $remarks_user;
        // debug($data);exit;
        $status = $this->custom_db->insert_record('transfer_booking_itinerary_details', $data);
        // debug($this->db->last_query());exit;
        return $status;
    }
    function get_transfer_details($app_reference)
    {
    	$qry = "select transfer_name,distance,vehicle_image from transfer_booking_itinerary_details where app_reference = '$app_reference'";
		$query=$this->db->query($qry);
		return $query->result();
    }
    function filter_booking_report($search_filter_condition = '', $count=false, $offset=0, $limit=100000000000)
    {
        if(empty($search_filter_condition) == false) {
            $search_filter_condition = ' and'.$search_filter_condition;
        }
        
        //BT, CD, ID
        if ($count) {
            $query = 'select count(distinct(BD.app_reference)) as total_records 
                    from  transfer_booking_details BD
                    join  transfer_booking_itinerary_details AS HBID on BD.app_reference=HBID.app_reference where BD.emulate_booking = 0 and BD.domain_origin='.get_domain_auth_id().' and BD.created_by_id ='.$GLOBALS['CI']->entity_user_id.' '.$search_filter_condition;
            $data = $this->db->query($query)->row_array();
            return $data['total_records'];
        } else {
            $this->load->library('booking_data_formatter');
            $response['status'] = SUCCESS_STATUS;
            $response['data'] = array();
            $booking_itinerary_details  = array();
            $booking_customer_details   = array();
            $bd_query = 'select * from transfer_booking_details AS BD 
                        WHERE BD.emulate_booking = 0 and BD.domain_origin='.get_domain_auth_id().' and BD.created_by_id ='.$GLOBALS['CI']->entity_user_id.' '.$search_filter_condition.'
                        order by BD.origin desc limit '.$offset.', '.$limit;
            $booking_details = $this->db->query($bd_query)->result_array();
            $bd_query_count = 'select count(distinct(BD.app_reference)) as total_records from transfer_booking_details AS BD 
                        WHERE BD.domain_origin='.get_domain_auth_id().' and BD.created_by_id ='.$GLOBALS['CI']->entity_user_id.' '.$search_filter_condition.'
                        order by BD.origin desc';
            $booking_details_count = $this->db->query($bd_query_count)->result_array();


            $app_reference_ids = $this->booking_data_formatter->implode_app_reference_ids($booking_details);
            if(empty($app_reference_ids) == false) {
                $id_query = 'select * from transfer_booking_itinerary_details AS ID 
                            WHERE ID.app_reference IN ('.$app_reference_ids.')';
                $cd_query = 'select * from  transfer_booking_passenger_details AS CD 
                            WHERE  CD.app_reference IN ('.$app_reference_ids.') ';

                // $cancellation_details_query = 'select HCD.* from    transfer_cancellation_details AS HCD WHERE HCD.app_reference='.$this->db->escape($app_reference_ids);

                $booking_itinerary_details  = $this->db->query($id_query)->result_array();
                $booking_customer_details   = $this->db->query($cd_query)->result_array();
            }
            $response['data']['count']            = $booking_details_count;
            $response['data']['booking_details']            = $booking_details;
            $response['data']['booking_itinerary_details']  = $booking_itinerary_details;
            $response['data']['booking_customer_details']   = $booking_customer_details;
            // $response['data']['cancellation_details']   = $this->db->query($cancellation_details_query)->result_array();
            return $response;
        }
    }

    function emulate_filter_booking_report($search_filter_condition = '', $count=false, $offset=0, $limit=100000000000)
    {
        if(empty($search_filter_condition) == false) {
            $search_filter_condition = ' and'.$search_filter_condition;
        }
        //BT, CD, ID
        if ($count) {
            $query = 'select count(distinct(BD.app_reference)) as total_records 
                    from  transfer_booking_details BD
                    join  transfer_booking_itinerary_details AS HBID on BD.app_reference=HBID.app_reference
                    where BD.emulate_booking=1 and BD.domain_origin='.get_domain_auth_id().' and BD.created_by_id ='.$GLOBALS['CI']->entity_user_id.' '.$search_filter_condition;
            $data = $this->db->query($query)->row_array();
            return $data['total_records'];
        } else {
            $this->load->library('booking_data_formatter');
            $response['status'] = SUCCESS_STATUS;
            $response['data'] = array();
            $booking_itinerary_details  = array();
            $booking_customer_details   = array();
            $bd_query = 'select * from transfer_booking_details AS BD 
                        WHERE BD.domain_origin='.get_domain_auth_id().' and BD.emulate_booking=1 and BD.created_by_id ='.$GLOBALS['CI']->entity_user_id.' '.$search_filter_condition.'
                        order by BD.origin desc limit '.$offset.', '.$limit;
                        //echo $bd_query ;exit;
            $booking_details = $this->db->query($bd_query)->result_array();
            $bd_query_count = 'select count(distinct(BD.app_reference)) as total_records from transfer_booking_details AS BD 
                        WHERE BD.domain_origin='.get_domain_auth_id().' and BD.emulate_booking=1 and BD.created_by_id ='.$GLOBALS['CI']->entity_user_id.' '.$search_filter_condition.'
                        order by BD.origin desc';
            $booking_details_count = $this->db->query($bd_query_count)->result_array();


            $app_reference_ids = $this->booking_data_formatter->implode_app_reference_ids($booking_details);
            if(empty($app_reference_ids) == false) {
                $id_query = 'select * from transfer_booking_itinerary_details AS ID 
                            WHERE ID.app_reference IN ('.$app_reference_ids.')';
                $cd_query = 'select * from  transfer_booking_passenger_details AS CD 
                            WHERE  CD.app_reference IN ('.$app_reference_ids.') ';

                // $cancellation_details_query = 'select HCD.* from    transfer_cancellation_details AS HCD WHERE HCD.app_reference='.$this->db->escape($app_reference_ids);

                $booking_itinerary_details  = $this->db->query($id_query)->result_array();
                $booking_customer_details   = $this->db->query($cd_query)->result_array();
            }
            $response['data']['count']            = $booking_details_count;
            $response['data']['booking_details']            = $booking_details;
            $response['data']['booking_itinerary_details']  = $booking_itinerary_details;
            $response['data']['booking_customer_details']   = $booking_customer_details;
            // $response['data']['cancellation_details']   = $this->db->query($cancellation_details_query)->result_array();
            return $response;
        }
    }
    public function get_admin_agent_markup($markup_level='',$user_id=0,$reference_id=0,$type='generic',$module_type='b2c_transferv1'){
if(!isset($user_id))
{
	$user_id=0;
}
		$str = "select * from markup_list where type='".$type."' and module_type='$module_type' and  markup_level='$markup_level' and reference_id=".$reference_id." and user_oid=".$user_id;
       // echo $str.'<br/>';
        $execute = $this->db->query($str);
        if($execute->num_rows()!=''){
            return $execute->result_array();
        }else{
            return array();
        }
    }

    function get_admin_gst()
    {
    	$module = "transfer";
    	$str = "select * from gst_master where module='".$module."' ";
       // echo $str.'<br/>';
        $execute = $this->db->query($str);
        if($execute->num_rows()!=''){
            return $execute->result_array();
        }else{
            return array();
        }
    }
    function get_convenience_fees()
    {
    	$module = "transfers";
    	$str = "select * from convenience_fees where module='".$module."' ";
       // echo $str.'<br/>';
        $execute = $this->db->query($str);
        if($execute->num_rows()!=''){
            return $execute->result_array();
        }else{
            return array();
        }
    }
    public function get_cancellation_policy($tran_id ,$travel_date) {
		$this->db->select('a.start_date,a.expiry_date,b.*');
		$this->db->from('transfer_cancellation as a');
		$this->db->join('transfer_cancellation_price as b','a.id = b.cancel_id');
		$this->db->where('a.v_id',$tran_id);
		$this->db->where('a.start_date <=',$travel_date);
		$this->db->where('a.status =',1);
		$this->db->where('a.expiry_date >=',$travel_date);
		$this->db->order_by("b.no_of_days", "asc");
		$res = $this->db->get();
		// echo $this->db->last_query();exit;
		return $res->result_array();
	}
	function get_transfer_city_list($search_chars)
    {
        $raw_search_chars = $this->db->escape($search_chars);
        if(empty($search_chars)==false){
            $r_search_chars = $this->db->escape($search_chars.'%');
            $search_chars = $this->db->escape($search_chars.'%');
        }else{
            $r_search_chars = $this->db->escape($search_chars);
            $search_chars = $this->db->escape($search_chars);
        }
        
        $query = 'Select cm.country_name,cm.city_name,cm.origin,cm.country_code from all_api_city_master_hb as cm where  cm.city_name like '.$search_chars.' 
                ORDER BY cm.cache_hotels_count desc, CASE
            WHEN    cm.city_name    LIKE    '.$raw_search_chars.'   THEN 1
            WHEN    cm.city_name    LIKE    '.$r_search_chars.' THEN 2  
            WHEN    cm.city_name    LIKE    '.$search_chars.'   THEN 3
            ELSE 4 END, cm.cache_hotels_count desc LIMIT 0, 30
        ';  
        return $this->db->query($query)->result_array();
    }
    public function get_markup_for_admin($min_price, $supplier, $cntry_code, $city_id='', $product_id=''){
			$markup_value=0;
			$admin_markup=$this->admin_markup($min_price, $supplier, $cntry_code, $city_id, $product_id);
			$markup_value +=$admin_markup;
			return $markup_value;
	}
	 public function get_markup_for_agent($min_price, $cntry_code='', $city_id=''){
			$markup_value=0;
			$agent_markup=$this->agent_markup($min_price, $cntry_code, $city_id);
			$markup_value +=$agent_markup;
			// echo $agent_markup;exit;
			return $markup_value;
	}
    public function admin_markup($min_price, $supplier, $cntry_code, $city_id='', $product_id='',$module_api=''){
				$markup_ary = array();
				$where=""; 
				// $product_id=$package_details['package_id'];\
			  	if($cntry_code)
	 			{ 
	 				$where .=" and ML.country='$cntry_code'";
	 			}
	 			if($city_id!='')
	 			{ 
	 				$where .=" and ML.city='$city_id'";
	 			}
	 			if($product_id!='')
	 			{ 
	 				$where .=" and ML.product_id='$product_id'";
	 			}
	 			if($supplier!='')
	 			{ 
	 				$where .=" and ML.supplier='".$supplier."'";
	 			}
				$query = 'SELECT
				ML.origin AS markup_origin, ML.value, ML.value_type,  ML.markup_currency AS markup_currency
				FROM domain_list AS DL JOIN markup_list AS ML where ML.value != "" and
				ML.module_type = "b2c_transferv1" and ML.markup_level = "level_2"'; 
				
				//debug($query);  exit;
				$specific_data_list = $this->db->query($query)->result_array();
				if($specific_data_list){
					if($module_api=='Api'){
						return $specific_data_list;
					}
					switch ($specific_data_list[0]['value_type']) {
	                    case 'percentage' :
	                        //Just need to calculate percentage of the values
	                        $markup_value = (($min_price / 100) * $specific_data_list[0]['value']);
	                        $original_markup = $specific_data_list[0]['value'];
	                        $markup_type = 'Percentage';
	                               break;
	                    case 'plus' :
	                        $original_markup = $specific_data_list[0]['value'];
	                        //convert value to required currency and then add the value
	                        // $temp_conversion = $this->currency_conversion_value(false, $__iv['markup_currency'], $this->to_currency);
	                        $markup_value =$specific_data_list[0]['value'];
	                        $markup_type = 'Plus';
	                        break;
                	}
                	return $markup_value;
				}
				else
				{
					$where=""; 
				// $product_id=$package_details['package_id'];
				// echo $product_id;exit;
			  	if($cntry_code)
	 			{ 
	 				$where .=" and ML.country='$cntry_code'";
	 			}
	 			if($supplier!='')
	 			{ 
	 				$where .=" and ML.supplier='".$supplier."'";
	 			}
				$query = 'SELECT
				ML.origin AS markup_origin, ML.value, ML.value_type,  ML.markup_currency AS markup_currency
				FROM domain_list AS DL JOIN markup_list AS ML where ML.value != "" and
				ML.module_type = "b2b_transferv1" and ML.markup_level = "level_3"  and ML.product_id is NULL and ML.city="0" and DL.origin=ML.domain_list_fk and ML.domain_list_fk != 0 and ML.reference_id=0 and ML.domain_list_fk = '.get_domain_auth_id().' '.$where.'order by DL.created_datetime DESC'; 
				// debug($query);  exit;
				$specific_data_list = $this->db->query($query)->result_array();
				// debug($specific_data_list);exit;
				if($specific_data_list){
					if($module_api=='Api'){
						return $specific_data_list;
					}
					switch ($specific_data_list[0]['value_type']) {
	                    case 'percentage' :
	                        //Just need to calculate percentage of the values
	                        $markup_value = (($min_price / 100) * $specific_data_list[0]['value']);
	                        $original_markup = $specific_data_list[0]['value'];
	                        $markup_type = 'Percentage';
	                        break;
	                    case 'plus' :
	                        $original_markup = $specific_data_list[0]['value'];
	                        //convert value to required currency and then add the value
	                        // $temp_conversion = $this->currency_conversion_value(false, $__iv['markup_currency'], $this->to_currency);
	                        $markup_value =$specific_data_list[0]['value'];
	                        $markup_type = 'Plus';
	                        break;
                	}
                	return $markup_value;
				}else{
					$query = 'SELECT
					ML.origin AS markup_origin, ML.value, ML.value_type,  ML.markup_currency AS markup_currency
					FROM domain_list AS DL JOIN markup_list AS ML where ML.value != "" and
					ML.module_type = "b2b_transferv1" and ML.markup_level = "level_3" and DL.origin=ML.domain_list_fk and ML.domain_list_fk != 0 and ML.reference_id=0 and ML.domain_list_fk = '.get_domain_auth_id().' and ML.product_id is NULL and ML.supplier="'.$supplier.'" and ML.city="0" and ML.country=" " order by DL.created_datetime DESC';
					// debug($query);  exit;
					$specific_data_list1 = $this->db->query($query)->row_array();
					// debug($specific_data_list1);exit;
					if($specific_data_list1){
						if($module_api=='Api'){
						return $specific_data_list1;
						}
						switch ($specific_data_list1['value_type']) {
		                    case 'percentage' :
		                        //Just need to calculate percentage of the values
		                        $markup_value = (($min_price / 100) * $specific_data_list1['value']);
		                        $original_markup = $specific_data_list1['value'];
		                        $markup_type = 'Percentage';
		                        break;
		                    case 'plus' :
		                        $original_markup = $specific_data_list1['value'];
		                        //convert value to required currency and then add the value
		                        // $temp_conversion = $this->currency_conversion_value(false, $__iv['markup_currency'], $this->to_currency);
		                        $markup_value =$specific_data_list1['value'];
		                        $markup_type = 'Plus';
		                        break;
	                	}
	                	return $markup_value;
					}
					else
					{
						return 0;
					}
				}
				}
	}
	public function agent_markup($min_price, $cntry_code='', $city_id='', $module_api=''){
		
				$markup_ary = array();
				$where=""; 
				// $product_id=$package_details['package_id'];\
			  	if($cntry_code)
	 			{ 
	 				$where .=" and ML.country='$cntry_code'";
	 			}
	 			if($city_id!='')
	 			{ 
	 				$where .=" and ML.city='$city_id'";
	 			}
		if(!isset($GLOBALS['CI']->entity_user_id))
			{
			$GLOBALS['CI']->entity_user_id=0;
		}
				$query = 'SELECT
				ML.origin AS markup_origin, ML.value, ML.value_type,  ML.markup_currency AS markup_currency
				FROM domain_list AS DL JOIN markup_list AS ML where ML.value != "" and
				ML.module_type = "b2b_transferv1" and ML.markup_level = "level_4" and DL.origin=ML.domain_list_fk and ML.domain_list_fk != 0 and ML.reference_id=0 and ML.user_oid ='.$GLOBALS['CI']->entity_user_id.' and ML.domain_list_fk = '.get_domain_auth_id().' '.$where.'order by DL.created_datetime DESC'; 
				// debug($module_api);  exit;
				$specific_data_list = $this->db->query($query)->result_array();
				// debug($specific_data_list);exit;
				if($specific_data_list){
					if($module_api=='Api'){
						return $specific_data_list;
						}
					switch ($specific_data_list[0]['value_type']) {
	                    case 'percentage' :
	                        //Just need to calculate percentage of the values
	                        $markup_value = (($min_price / 100) * $specific_data_list[0]['value']);
	                        $original_markup = $specific_data_list[0]['value'];
	                        $markup_type = 'Percentage';
	                        break;
	                    case 'plus' :
	                        $original_markup = $specific_data_list[0]['value'];
	                        //convert value to required currency and then add the value
	                        // $temp_conversion = $this->currency_conversion_value(false, $__iv['markup_currency'], $this->to_currency);
	                        $markup_value =$specific_data_list[0]['value'];
	                        $markup_type = 'Plus';
	                        break;
                	}
                	return $markup_value;
				}
				else
				{
					$where=""; 
				// $product_id=$package_details['package_id'];
				// echo $product_id;exit;
			  	if($city_id!='')
	 			{ 
	 				$where .=" and ML.city='$city_id'";
	 			}
				$query = 'SELECT
				ML.origin AS markup_origin, ML.value, ML.value_type,  ML.markup_currency AS markup_currency
				FROM domain_list AS DL JOIN markup_list AS ML where ML.value != "" and
				ML.module_type = "b2b_transferv1" and ML.markup_level = "level_4"  and ML.country=" " and DL.origin=ML.domain_list_fk and ML.domain_list_fk != 0 and ML.reference_id=0 and ML.user_oid ='.$GLOBALS['CI']->entity_user_id.' and ML.domain_list_fk = '.get_domain_auth_id().' '.$where.'order by DL.created_datetime DESC'; 
				// debug($query);  exit;
				$specific_data_list = $this->db->query($query)->result_array();
				// debug($specific_data_list);exit;
				if($specific_data_list){
					if($module_api=='Api'){
						return $specific_data_list;
						}
					switch ($specific_data_list[0]['value_type']) {
	                    case 'percentage' :
	                        //Just need to calculate percentage of the values
	                        $markup_value = (($min_price / 100) * $specific_data_list[0]['value']);
	                        $original_markup = $specific_data_list[0]['value'];
	                        $markup_type = 'Percentage';
	                        break;
	                    case 'plus' :
	                        $original_markup = $specific_data_list[0]['value'];
	                        //convert value to required currency and then add the value
	                        // $temp_conversion = $this->currency_conversion_value(false, $__iv['markup_currency'], $this->to_currency);
	                        $markup_value =$specific_data_list[0]['value'];
	                        $markup_type = 'Plus';
	                        break;
                	}
                	return $markup_value;
				}else{

				$where=""; 
				// $product_id=$package_details['package_id'];
				// echo $product_id;exit;
			  	if($cntry_code)
	 			{ 
	 				$where .=" and ML.country='$cntry_code'";
	 			}
				$query = 'SELECT
				ML.origin AS markup_origin, ML.value, ML.value_type,  ML.markup_currency AS markup_currency
				FROM domain_list AS DL JOIN markup_list AS ML where ML.value != "" and
				ML.module_type = "b2b_transferv1" and ML.markup_level = "level_4"  and ML.city="0" and DL.origin=ML.domain_list_fk and ML.domain_list_fk != 0 and ML.reference_id=0 and ML.user_oid ='.$GLOBALS['CI']->entity_user_id.' and ML.domain_list_fk = '.get_domain_auth_id().' '.$where.'order by DL.created_datetime DESC'; 
				// debug($query);  exit;
				$specific_data_list = $this->db->query($query)->result_array();
				// debug($specific_data_list);exit;
				if($specific_data_list){
					if($module_api=='Api'){
						return $specific_data_list;
						}
					switch ($specific_data_list[0]['value_type']) {
	                    case 'percentage' :
	                        //Just need to calculate percentage of the values
	                        $markup_value = (($min_price / 100) * $specific_data_list[0]['value']);
	                        $original_markup = $specific_data_list[0]['value'];
	                        $markup_type = 'Percentage';
	                        break;
	                    case 'plus' :
	                        $original_markup = $specific_data_list[0]['value'];
	                        //convert value to required currency and then add the value
	                        // $temp_conversion = $this->currency_conversion_value(false, $__iv['markup_currency'], $this->to_currency);
	                        $markup_value =$specific_data_list[0]['value'];
	                        $markup_type = 'Plus';
	                        break;
                	}
                	return $markup_value;
				}else{


					$query = 'SELECT
					ML.origin AS markup_origin, ML.value, ML.value_type,  ML.markup_currency AS markup_currency
					FROM domain_list AS DL JOIN markup_list AS ML where ML.value != "" and
					ML.module_type = "b2b_transferv1" and ML.markup_level = "level_4" and DL.origin=ML.domain_list_fk and ML.domain_list_fk != 0 and ML.reference_id=0 and ML.user_oid ='.$GLOBALS['CI']->entity_user_id.' and  ML.domain_list_fk = '.get_domain_auth_id().' and ML.city="0" and ML.country=" " order by DL.created_datetime DESC ';
					// debug($query);  exit;
					$specific_data_list1 = $this->db->query($query)->row_array();
					// debug($specific_data_list1);exit;
					if($specific_data_list1){
						if($module_api=='Api'){
						return $specific_data_list1;
						}
						switch ($specific_data_list1['value_type']) {
		                    case 'percentage' :
		                        //Just need to calculate percentage of the values
		                        $markup_value = (($min_price / 100) * $specific_data_list1['value']);
		                        $original_markup = $specific_data_list1['value'];
		                        $markup_type = 'Percentage';
		                        break;
		                    case 'plus' :
		                        $original_markup = $specific_data_list1['value'];
		                        //convert value to required currency and then add the value
		                        // $temp_conversion = $this->currency_conversion_value(false, $__iv['markup_currency'], $this->to_currency);
		                        $markup_value =$specific_data_list1['value'];
		                        $markup_type = 'Plus';
		                        break;
	                	}
	                	return $markup_value;
					}
					else
					{
						return 0;
					}
				}
				}
				}
	}
	function get_country_city($from_code)
	{
		$query = 'Select origin as city_id,CountryCode as country_code from flight_airport_list where airport_code = "'.$from_code.'"';
		// debug($query);exit;
		$data_list = $this->db->query($query)->result_array();
		if(empty($data_list)){
			$query = 'Select b.origin as city_id,b.CountryCode as country_code from hb_hotel_details as a join flight_airport_list as b on a.country_code = b.CountryCode,a.hotel_city = b.airport_city  where a.hotel_code = '.$from_code.'';
			$data_list = $this->db->query($query)->result_array();
		}
		return $data_list;

	}
	function verify_agent_balance()
	{
				$query = 'SELECT BU.balance, BU.credit_limit, BU.due_amount, CC.country as currency, CC.value as conversion_value
							from user as U
							JOIN b2b_user_details as BU ON U.user_id=BU.user_oid
							JOIN domain_list as DL ON U.domain_list_fk = DL.origin
							JOIN currency_converter CC ON CC.id=BU.currency_converter_fk
							WHERE U.status='.ACTIVE.' and U.user_id='.intval($this->entity_user_id).' and 
							DL.status='.ACTIVE.' and DL.origin='.$this->db->escape(get_domain_auth_id()).' and DL.domain_key = '.$this->db->escape(get_domain_key());
							// debug($query);exit;
				$balance_record = $this->db->query($query)->row_array();
				;
                    $balance = $balance_record['balance'] + floatval($balance_record ['credit_limit']) + floatval($balance_record ['due_amount']);
		return $balance;
	}

	function get_monthly_booking_summary($condition=array())
    {
        //Balu A
       // $condition = $this->custom_db->get_custom_condition($condition);
        $query = 'select count(distinct(BD.app_reference)) AS total_booking, 
                sum(SBID.total_fare+SBID.agent_markup) as monthly_payment, sum(SBID.agent_markup) as monthly_earning, 
                MONTH(BD.created_datetime) as month_number 
                from transfer_booking_details AS BD
                join transfer_booking_itinerary_details AS SBID on BD.app_reference=SBID.app_reference
                where (YEAR(BD.created_datetime) BETWEEN '.date('Y').' AND '.date('Y', strtotime('+1 year')).')  and BD.domain_origin='.get_domain_auth_id().' AND BD.created_by_id = '.$GLOBALS['CI']->entity_user_id.'
                GROUP BY YEAR(BD.created_datetime), 
                MONTH(BD.created_datetime)';
               // echo $query; exit;
        return $this->db->query($query)->result_array();
    }
    function get_agent_ids($uid)
	{
		$user_id = $uid;
		$query = 'select user_id from user where agent_staff_id='.$user_id.' and agent_staff=1';
		$agent_details = $this->db->query($query)->result_array();
		return $agent_details;
	}

}