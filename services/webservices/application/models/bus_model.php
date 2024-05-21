<?php
/**
 * Library which has generic functions to get data
 *
 * @package    Provab Application
 * @subpackage Bus Model
 * @author     Arjun J<arjunjgowda260389@gmail.com>
 * @version    V2
 */
Class Bus_Model extends CI_Model
{
	private $master_search_data;
	/*
	 *
	 * Get Bus Station List
	 *
	 */
	function get_bus_station_list($query)
	{
		$this->db->like('name', $query);
		$this->db->limit(15);
		return $this->db->get('bus_stations')->result_array();
	}
	function get_bus_station_data($id)
    {
        $query = 'Select * from bus_stations where station_id ='.$id;
    
        return $this->db->query($query)->row();
    }
	/**
	 * get all the booking source which are active for current domain
	 */
	function active_booking_source()
	{
		$query = 'select BS.source_id, BS.origin from meta_course_list AS MCL, booking_source AS BS, activity_source_map AS ASM WHERE
		MCL.origin=ASM.meta_course_list_fk and ASM.booking_source_fk=BS.origin and MCL.course_id='.$this->db->escape(META_BUS_COURSE).'
		and BS.booking_engine_status='.ACTIVE.' AND MCL.status='.ACTIVE.' AND ASM.status="active"';
		return $this->db->query($query)->result_array();
	}
	/**
	 * return booking list
	 */
	function booking($condition=array(), $count=false, $offset=0, $limit=100000000000)
	{
		//$condition = $this->custom_db->get_custom_condition($condition);
		//BT, CD, ID
		if ($count) {
			$query = 'select count(*) as total_records from bus_booking_details BD where domain_origin='.get_domain_auth_id().' AND BD.created_by_id ='.$GLOBALS['CI']->entity_user_id;
			$data = $this->db->query($query)->row_array();
			return $data['total_records'];
		} else {
			$cols = 'BD.*, count(CD.app_reference) as total_passengers,
				(CD.name) name,
				group_concat(CD.seat_no separator "'.DB_SAFE_SEPARATOR.'") seat_no,
				ID.departure_datetime as departure_datetime,
				ID.arrival_datetime as arrival_datetime,
				ID.departure_from as departure_from,
				ID.arrival_to as arrival_to,
				ID.operator as operator,
				POL.name as payment_name';
			$query = 'select '.$cols.' from bus_booking_details AS BD, bus_booking_customer_details AS CD, bus_booking_itinerary_details AS ID
				,payment_option_list as POL where POL.payment_category_code=BD.payment_mode AND BD.app_reference=CD.app_reference AND BD.app_reference=ID.app_reference AND BD.domain_origin='.get_domain_auth_id().'
				AND BD.created_by_id ='.$GLOBALS['CI']->entity_user_id.' group by BD.app_reference, CD.app_reference order by BD.origin desc, ID.origin, CD.origin limit '.$offset.', '.$limit;
			return $this->db->query($query)->result_array();
		}
	}

	/**
	 * get search data and validate it
	 */
	function get_safe_search_data($search_id)
	{
		$search_data = $this->get_search_data($search_id);
		$status = true;
		$clean_search = '';
		if ($search_data != false) {
			//validate
			$temp_search_data = json_decode($search_data['search_data'], true);
			if (strtotime($temp_search_data['bus_date_1']) > time()) {
				$clean_search['bus_date_1'] = $temp_search_data['bus_date_1'];
			} else {
				$status = false;
			}

			if (empty($temp_search_data['bus_date_2']) == true) {
				$clean_search['trip_type'] = 'One Way';
			} elseif (strtotime($temp_search_data['bus_date_2']) > time()) {
				$clean_search['trip_type'] = 'Round Way';
			} else {
				$status = false;
			}

			if (empty($temp_search_data['bus_station_from']) == true || empty($temp_search_data['bus_station_to']) == true) {
				$status = false;
			} else {
				$clean_search['bus_station_from'] = $temp_search_data['bus_station_from'];
				$clean_search['bus_station_to'] = $temp_search_data['bus_station_to'];
			}

			return array('status' => $status, 'data' => $clean_search);
		}
	}

	/**
	 * get search data without doing any validation
	 * @param $search_id
	 */
	function get_search_data($search_id)
	{
		if (empty($this->master_search_data)) {
			$search_data = $this->custom_db->single_table_records('search_history', '*', array('search_type' => META_BUS_COURSE, 'origin' => $search_id));
			if ($search_data['status'] == true) {
				$this->master_search_data = $search_data['data'][0];
			} else {
				return false;
			}
		}
		return $this->master_search_data;
	}
	  /**
     * get search data and validate it
     */
    function get_safe_search_bus_data($search_id) {

        $search_data = $this->get_search_data_bus_histroy($search_id);

        $status = true;
        $clean_search = '';
        if ($search_data != false) {
            return array('status' => $status, 'data' => $search_data);
        }
    }

    /**
     * get search data without doing any validation
     * @param $search_id
     */
    function get_search_data_bus_histroy($search_id) {
        if (empty($this->master_search_data)) {
            $search_data = $this->custom_db->single_table_records('search_history', '*', array('search_type' => META_BUS_COURSE, 'origin' => $search_id));


            if ($search_data['status'] == true) {

                $this->master_search_data = $search_data['data'][0];
            } else {
                return false;
            }
        }
        return $this->master_search_data;
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
	 * @param string $data - serialized data to be cached
	 */
	function set_auth_token($data)
	{
		$this->custom_db->insert_record('temp_cache', array('domain_list_fk' => get_domain_auth_id(), 'type' => 'travelyaari', 'data' => $data, 'created_datetime' => date('Y-m-d H:i:s')));
	}

	/**
	 *
	 * @param number $domain_origin
	 * @param number $status
	 * @param string $app_reference
	 * @param string $booking_source
	 * @param string $pnr
	 * @param string $ticket
	 * @param string $transaction
	 * @param number $total_fare
	 * @param number $domain_markup
	 * @param number $level_one_markup
	 * @param string $currency
	 * @param number $phone_number
	 * @param number $alternate_number
	 * @param number $created_by_id
	 */
	function save_booking_details($domain_origin, $status, $app_reference, $booking_source, $pnr, $ticket, $transaction, $total_fare, $domain_markup, $level_one_markup, $currency, $phone_number, $alternate_number,$payment_mode, $created_by_id,$currency_conversion_rate, $pass_email)
	{
		$data['domain_origin'] = $domain_origin;
		$data['status'] = $status;
		$data['app_reference'] = $app_reference;
		$data['booking_source'] = $booking_source;
		$data['pnr'] = $pnr;
		$data['ticket'] = $ticket;
		$data['transaction'] = $transaction;
		$data['total_fare'] = $total_fare;
		$data['domain_markup'] = $domain_markup;
		$data['level_one_markup'] = $level_one_markup;
		$data['currency'] = $currency;
		$data['phone_number'] = $phone_number;
		$data['email'] = $pass_email;
		$data['alternate_number'] = $alternate_number;
		$data['payment_mode'] = $payment_mode;
		$data['created_by_id'] = $created_by_id;
		$data['created_datetime'] = date('Y-m-d H:i:s');
		$data['currency_conversion_rate'] = $currency_conversion_rate;
		$status = $this->custom_db->insert_record('bus_booking_details', $data);
		return $status;
	}

	/**
	 *
	 * @param $bus_booking_origin
	 * @param $name
	 * @param $age
	 * @param $gender
	 * @param $seat_no
	 * @param $fare
	 * @param $status
	 * @param $seat_type
	 * @param $is_ac_seat
	 */
	function save_booking_customer_details($app_reference, $name, $age, $gender, $seat_no, $fare, $status, $seat_type, $is_ac_seat, $admin_commission, $agent_commission, $admin_markup, $agent_markup,$admin_tds,$agent_tds, $agent_gst)
	{
		$data['app_reference'] = $app_reference;
		$data['name'] = $name;
		$data['age'] = $age;
		$data['gender'] = $gender;
		$data['seat_no'] = $seat_no;
		$data['fare'] = $fare;
		$data['status'] = $status;
		$data['seat_type'] = $seat_type;
		$data['is_ac_seat'] = $is_ac_seat;
		$data['admin_commission'] = $admin_commission;
		$data['agent_commission'] = $agent_commission;
		$data['admin_markup'] = $admin_markup;
		$data['agent_markup'] = $agent_markup;
		
		$data['admin_tds'] = $admin_tds;
		$data['agent_tds'] = $agent_tds;
		$data['agent_gst'] = $agent_gst;
		
		$status = $this->custom_db->insert_record('bus_booking_customer_details', $data);
		return $status;
	}

	/**
	 *
	 * @param $bus_booking_origin
	 * @param $departure_datetime
	 * @param $arrival_datetime
	 * @param $departure_from
	 * @param $arrival_to
	 * @param $boarding_from
	 * @param $dropping_at
	 * @param $bus_type
	 * @param $operator
	 * @param $attributes
	 */
	function save_booking_itinerary_details($app_reference, $journey_datetime, $departure_datetime, $arrival_datetime, $departure_from, $arrival_to, $boarding_from, $dropping_at, $bus_type, $operator, $attributes)
	{
		$data['app_reference'] = $app_reference;
		$data['journey_datetime'] = $journey_datetime;
		$data['departure_datetime'] = $departure_datetime;
		$data['arrival_datetime'] = $arrival_datetime;
		$data['departure_from'] = $departure_from;
		$data['arrival_to'] = $arrival_to;
		$data['boarding_from'] = $boarding_from;
		$data['dropping_at'] = $dropping_at;
		$data['bus_type'] = $bus_type;
		$data['operator'] = $operator;
		$data['attributes'] = serialize($attributes);
		$status = $this->custom_db->insert_record('bus_booking_itinerary_details', $data);
		return $status;
	}

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
		$response['data']['booking_details']			= $this->db->query($bd_query)->row_array();
		$response['data']['booking_itinerary_details']	= $this->db->query($id_query)->result_array();
		$response['data']['booking_customer_details']	= $this->db->query($cd_query)->result_array();
		if (valid_array($response['data']['booking_details']) == true and valid_array($response['data']['booking_itinerary_details']) == true and valid_array($response['data']['booking_customer_details']) == true) {
			$response['status'] = SUCCESS_STATUS;
		}
		return $response;
	}

	/**
	 *
	 */
	function get_static_response($token_id)
	{
		$static_response = $this->custom_db->single_table_records('test', '*', array('origin' => intval($token_id)));
		return json_decode($static_response['data'][0]['test'], true);
	}

	/**
	 * SAve search data for future use - Analytics
	 * @param array $params
	 */
	function save_search_data($search_data, $type)
	{
		$data['domain_origin'] = get_domain_auth_id();
		$data['search_type'] = $type;
		$data['created_by_id'] = intval(@$this->entity_user_id);
		$data['created_datetime'] = date('Y-m-d H:i:s');
		$data['from_station'] = $search_data['bus_station_from'];
		$data['to_station'] = $search_data['bus_station_to'];
		$data['trip_type'] = (empty($search_data['bus_date_2']) == true ? 'oneway' : 'round');
		$data['journey_date'] = date('Y-m-d', strtotime($search_data['bus_date_1']));
		$this->custom_db->insert_record('search_bus_history', $data);
	}
	
    /**
     * SAve search data for future use - Analytics
     * @param array $params
     */
    function save_search_history_data($search_data) {

        $data['status'] = SUCCESS_STATUS;
        $cache_key = $this->redis_server->generate_cache_key();

        $search_history_data['domain_origin'] = get_domain_auth_id();
        //$search_history_data['search_type'] = $type;

        $search_history_data['created_datetime'] = db_current_datetime();
        $search_history_data['search_type'] = META_BUS_COURSE;
        $search_history_data['cache_key'] = $cache_key;
        $search_history_data['search_data'] = json_encode($search_data);
        $insert_data = $this->custom_db->insert_record('search_history', $search_history_data);
        if ($insert_data['status'] == QUERY_SUCCESS) {

            $data['cache_key'] = $cache_key;
            $data['search_id'] = $insert_data['insert_id'];
        } else {
            $data['status'] = FAILURE_STATUS;
        }

        return $data;
    }

	function bus_deals($id){
		$cols = 'DL.domain_name,BDS.value_type,BDS.value,BDS.api_value';
		$query = 'select '.$cols.' from domain_list As DL left join  bus_deal_sheet as BDS on DL.origin = BDS.domain_id where DL.origin ='.$id;
		//echo $query;exit;
		return $this->db->query($query)->result_array();
	}
	/**
	 * Jaganath
	 * @param $id
	 */
	function bus_commission_details($id){
		$cols = 'DL.domain_name,BBCD.value, BBCD.api_value,BBCD.value_type';
		$query = 'select '.$cols.' from domain_list DL
					left join b2b_bus_commission_details as BBCD on DL.origin = BBCD.domain_list_fk 
				where DL.origin ='.intval($id);
		return $this->db->query($query)->result_array();
	}
	/**
	 * Update the Refund details
	 * @param unknown_type $app_reference
	 * @param unknown_type $refund_status
	 * @param unknown_type $refund_amount
	 */
	function update_refund_details($app_reference, $refund_status, $refund_amount, $cancel_charge_percentage)
	{
		$refund_details = array();
		$refund_details['refund_amount'] =				floatval($refund_amount);
		$refund_details['cancel_charge_percentage'] =	floatval($cancel_charge_percentage);
		$refund_details['refund_status'] = 				$refund_status;
		$refund_details['refund_payment_mode'] = 		'online';
		$refund_details['refund_date'] = 				date('Y-m-d H:i:s');
		$this->custom_db->update_record('bus_cancellation_details', $refund_details, array('app_reference'=> $app_reference));
	}
	/**
	 * Update the Old Booking App Reference
	 */
	public function update_old_booking_app_reference($new_app_reference, $tciket_number, $pnr, $domain_origin)
	{
		$new_booking_data = $this->db->query('select * from bus_booking_details where app_reference='.$this->db->escape($new_app_reference))->row_array();
		if(valid_array($new_booking_data)==true){
			$update_app_reference = false;
		} else{
			$update_app_reference = true;
		}
		if($update_app_reference == true){//If its old booking update AppReference
			//Get master Details
			$master_booking_details = $this->db->query('select BBD.app_reference,BBD.booking_source from bus_booking_details BBD
								where BBD.domain_origin='.intval($domain_origin).' and BBD.ticket='.$this->db->escape($tciket_number).' 
									and BBD.pnr='.$this->db->escape($pnr))->row_array();
			$old_app_reference = trim($master_booking_details['app_reference']);
			$booking_source = trim($master_booking_details['booking_source']);
			//$booking_details = $this->get_booking_details($master_app_reference, $booking_source);
			//UPDATE DATA
			$update_data['app_reference'] = $new_app_reference;
			//UPDATE CONDITIOn
			$update_condition['app_reference'] = $old_app_reference;
			//1.update bus_booking_details
			$this->custom_db->update_record('bus_booking_details', $update_data, $update_condition);
			//2.update bus_booking_itinerary_details
			$this->custom_db->update_record('bus_booking_itinerary_details', $update_data, $update_condition);
			//3.update bus_booking_customer_details
			$this->custom_db->update_record('bus_booking_customer_details', $update_data, $update_condition);
			//4.update transaction_log
			$this->custom_db->update_record('transaction_log', $update_data, $update_condition);
		}
	}
	 public function get_bus_data($app_reference){
        $cd_query = 'select * from bus_booking_customer_details AS CD WHERE CD.app_reference=' . $this->db->escape($app_reference);
        return $this->db->query($cd_query)->result_array();
    }
}
