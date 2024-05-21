<?php
require_once 'abstract_management_model.php';
/**
 * @package    current domain Application
 * @subpackage Travel Portal
 * @author     Balu A<balu.provab@gmail.com>
 * @version    V2
 */
Class Domain_Management_Model extends Abstract_Management_Model
{
	private $airline_markup;
	private $hotel_markup;
	private $bus_markup;
	private $sightseeing_markup;
	private $transfers_markup;
	var $verify_domain_balance;

	function __construct() {
		parent::__construct('level_4');
		$this->verify_domain_balance = $this->config->item('verify_domain_balance');
	}

	/**
	 * Balu A
	 * Get markup based on different modules
	 * @return array('value' => 0, 'type' => '')
	 */
	function get_markup($module_name)
	{

		$markup_data = '';
		switch ($module_name) {
			case 'flight' : $markup_data = $this->airline_markup();
			break;
			case 'hotel' : $markup_data = $this->hotel_markup();
			break;
			case 'hotelcrs' : $markup_data = $this->hotelcrs_markup();
			break;
			case 'bus' : $markup_data = $this->bus_markup();
			break;
			case 'sightseeing':$markup_data = $this->sightseeing_markup();
			break;
			case 'car' : $markup_data = $this->car_markup();
			break;
			case 'privatecar' : $markup_data = $this->carcrs_markup();
			break;
			case 'transferv1':$markup_data = $this->transfer_markup();
			break;
		}
		// debug($markup_data);exit;
		return $markup_data;
	}
	function addHolidayCrsMarkup(& $total_fare , $holiday_crs_markup,$currency_obj)
	{
//debug($currency_obj);exit;
	// 	debug($total_fare);
	// 	debug($holiday_crs_markup);
	// 	debug($currency_obj);exit();
		$markup_value = 0; 
		if (isset($holiday_crs_markup['generic_markup_list'][0])) {
			$markup_list = $holiday_crs_markup['generic_markup_list'][0];
			if($markup_list['value_type'] == 'percentage'){
				$markup_value = (($total_fare / 100) * $markup_list ['value']);
			}else{
				$temp_conversion = $currency_obj->getConversionRate ( false, $markup_list ['markup_currency'], $this->to_currency );
				$markup_value = (($markup_list ['value'] * $temp_conversion));
			}
		}
		// echo $markup_value;exit;
		return $markup_value;
	}
	/**
	 * Balu A
	 * Manage domain markup for current domain - Domain wise and module wise
	 */
	function get_agent_airline_markup_details()
	{
		if (empty($this->airline_markup) == true) {
			$response['specific_markup_list'] = $this->specific_airline_markup_list('b2b_flight');
			$response['generic_markup_list'] = $this->generic_domain_markup('b2b_flight');
			$this->airline_markup = $response;
		} else {
			$response = $this->airline_markup;
		}
		return $response;
	}
	/**
	 * Balu A
	 * Manage domain markup for current domain - Domain wise and module wise
	 */
	function airline_markup()
	{
		if (empty($this->airline_markup) == true) {
			$response['specific_markup_list'] = $this->specific_airline_markup('b2b_flight');
			$response['generic_markup_list'] = $this->generic_domain_markup('b2b_flight');
			$this->airline_markup = $response;
		} else {
			$response = $this->airline_markup;
		}
		return $response;
	}

	/**
	 * Balu A
	 * Manage domain markup for current domain - Domain wise and module wise
	 */
	function hotel_markup()
	{
		if (empty($this->hotel_markup) == true) {
			$response['specific_markup_list'] = '';
			$response['generic_markup_list'] = $this->generic_domain_markup('b2b_hotel');
			$this->hotel_markup = $response;
		} else {
			$response = $this->hotel_markup;
		}
		return $response;
	}
	function hotelcrs_markup()
	{
		if (empty($this->hotel_markup) == true) {
			$response['specific_markup_list'] = '';
			$response['generic_markup_list'] = $this->generic_domain_markup('b2b_hotelcrs');
			$this->hotel_markup = $response;
		} else {
			$response = $this->hotel_markup;
		}
		return $response;
	}
	
	/**
	 * Elavarasi
	 * Manage domain markup for current domain - Domain wise and module wise
	 */
	function sightseeing_markup()
	{
		if (empty($this->sightseeing_markup) == true) {
			$response['specific_markup_list'] = '';
			$response['generic_markup_list'] = $this->generic_domain_markup('b2b_sightseeing');
			$this->sightseeing_markup = $response;
		} else {
			$response = $this->sightseeing_markup;
		}
		return $response;
	}
	/**
	 * Elavarasi
	 * Manage domain markup for current domain - Domain wise and module wise
	 */
	function transfer_markup()
	{
		if (empty($this->transfers_markup) == true) {
			$response['specific_markup_list'] = '';
			$response['generic_markup_list'] = $this->generic_domain_markup('b2b_transferv1');
			$this->transfers_markup = $response;
		} else {
			$response = $this->transfers_markup;
		}
		return $response;
	}

	/**
	 * Anitha G
	 * Manage domain markup for current domain - Domain wise and module wise
	 */
	function car_markup()
	{
		if (empty($this->car_markup) == true) {
			$response['specific_markup_list'] = '';
			$response['generic_markup_list'] = $this->generic_domain_markup('b2b_car');
			$this->car_markup = $response;
		} else {
			$response = $this->car_markup;
		}
		return $response;
	}
		function carcrs_markup()
	{
		if (empty($this->car_markup) == true) {
			$response['specific_markup_list'] = '';
			$response['generic_markup_list'] = $this->generic_domain_markup('b2b_carcrs');
		//	echo "dfd";
		//	debug($response);die;
			$this->car_markup = $response;
		} else {
			$response = $this->car_markup;
		}
		return $response;
	}
	/**
	 * Balu A
	 * Manage domain markup for current domain - Domain wise and module wise
	 */
	function bus_markup()
	{
		if (empty($this->bus_markup) == true) {
			$response['specific_markup_list'] = '';
			$response['generic_markup_list'] = $this->generic_domain_markup('b2b_bus');
			$this->bus_markup = $response;
		} else {
			$response = $this->bus_markup;
		}
		return $response;
	}

	/**
	 * Balu A
	 * Get generic markup based on the module type
	 * @param $module_type
	 * @param $markup_level
	 */
	function generic_domain_markup($module_type)
	{
		$query = 'SELECT ML.origin AS markup_origin, ML.type AS markup_type, ML.reference_id, ML.value, ML.value_type,  ML.markup_currency AS markup_currency
		FROM markup_list AS ML where ML.module_type = "'.$module_type.'" and
		ML.markup_level = "'.$this->markup_level.'" and ML.type="generic" and ML.domain_list_fk='.get_domain_auth_id().' and ML.user_oid='.$this->entity_user_id;
	//	if ($_SERVER['REMOTE_ADDR'] == '192.168.0.88') {
//		 echo $query;exit;
	//	}
		$generic_data_list = $this->db->query($query)->result_array();
		return $generic_data_list;
	}
	function generic_domain_markuphotel($module_type)
	{
		$query = 'SELECT ML.origin AS markup_origin, ML.type AS markup_type, ML.reference_id, ML.value, ML.value_type,  ML.markup_currency AS markup_currency
		FROM markup_list AS ML where ML.module_type = "'.$module_type.'" and
		ML.markup_level = "level_3" and ML.type="generic" and ML.domain_list_fk='.get_domain_auth_id().' and ML.user_oid=0';
	//	if ($_SERVER['REMOTE_ADDR'] == '192.168.0.88') {
		//	 echo $query;exit;
	//	}
		$generic_data_list = $this->db->query($query)->result_array();
		return $generic_data_list;
	}
	/**
	 * Get specific markup based on module type
	 * @param string $module_type	Name of the module for which the markup has to be returned
	 * @param string $markup_level	Level of markup
	 */
	function specific_airline_markup_list($module_type)
	{
		$sub_query = 'SELECT AL.origin
		FROM airline_list AS AL 
		JOIN markup_list AS ML ON
		ML.module_type = "'.$module_type.'" and ML.markup_level = "'.$this->markup_level.'" and AL.origin=ML.reference_id and ML.type="specific"
		and ML.domain_list_fk != 0  and ML.domain_list_fk='.get_domain_auth_id().' and ML.user_oid='.$this->entity_user_id;

		$query = 'SELECT AL.origin AS airline_origin, AL.name AS airline_name, AL.code AS airline_code,
		ML.origin AS markup_origin, ML.type AS markup_type, ML.reference_id, ML.value, ML.value_type
		FROM airline_list AS AL LEFT JOIN markup_list AS ML ON
		ML.module_type = "'.$module_type.'" and ML.markup_level = "'.$this->markup_level.'" and AL.origin=ML.reference_id and ML.type="specific"
		and ML.domain_list_fk != 0  and ML.domain_list_fk='.get_domain_auth_id().' and ML.user_oid='.$this->entity_user_id.' 
		where (AL.has_specific_markup='.ACTIVE.' OR AL.origin in ('.$sub_query.')) order by AL.name ASC';
		$specific_data_list = $this->db->query($query)->result_array();
		return $specific_data_list;
	}
	/**FIXME: B2B-Airline is pending-----Balu A
	 * Get specific markup based on module type
	 * @param string $module_type	Name of the module for which the markup has to be returned
	 * @param string $markup_level	Level of markup
	 */
	function specific_airline_markup($module_type)
	{
		$markup_list = '';
		$query = 'SELECT AL.origin AS airline_origin, AL.name AS airline_name, AL.code AS airline_code,
		ML.origin AS markup_origin, ML.type AS markup_type, ML.reference_id, ML.value, ML.value_type, ML.markup_currency AS markup_currency
		FROM airline_list AS AL JOIN markup_list AS ML where ML.value != "" and
		ML.module_type = "'.$module_type.'" and ML.markup_level = "'.$this->markup_level.'" and AL.origin=ML.reference_id and ML.type="specific"
		and ML.domain_list_fk != 0  and ML.domain_list_fk='.get_domain_auth_id().' and ML.user_oid='.$this->entity_user_id.' order by AL.name ASC';
		$specific_data_list = $this->db->query($query)->result_array();
		if (valid_array($specific_data_list)) {
			foreach ($specific_data_list as $__k => $__v) {
				$markup_list[$__v['airline_code']] = $__v;
			}
		}
		return $markup_list;
	}
	/**
	 * Get Details based on Airline Code
	 * @param string $module_type	Name of the module for which the markup has to be returned
	 * @param string $markup_level	Level of markup
	 */
	function individual_airline_markup_details($module_type, $airline_code)
	{
		$query = 'SELECT ML.origin as markup_list_origin,AL.origin as airline_list_origin
		FROM airline_list AS AL 
		LEFT JOIN markup_list AS ML ON
		ML.module_type = "'.$module_type.'" and ML.markup_level = "'.$this->markup_level.'" and AL.origin=ML.reference_id and ML.type="specific"
		and ML.domain_list_fk != 0  and ML.domain_list_fk='.get_domain_auth_id().' and ML.user_oid='.$this->entity_user_id.' where AL.code="'.$airline_code.'"';
		$specific_data_list = $this->db->query($query)->row_array();
		return $specific_data_list;
	}
	function test_tables($id){
		$query ='SELECT * FROM master_transaction_details AS MD
				LEFT JOIN flight_booking_details AS FD ON MD.created_by_id = FD.created_by_id
				LEFT JOIN hotel_booking_details AS HD ON MD.created_by_id = HD.created_by_id
				LEFT JOIN bus_booking_details AS BD ON MD.created_by_id = BD.created_by_id
				WHERE MD.created_by_id ="'.$id.'" AND FD.created_by_id ="'.$id.'" AND HD.created_by_id ="'.$id.'"AND BD.created_by_id ="'.$id.'"';
		return $this->db->query($query)->result_array();
	}

	/**
	 * save master transaction details request
	 * @param array $details
	 */
	function save_master_transaction_details($details, $type='')
	{
		$system_transaction_id = 'DEP-'.$this->entity_user_id.time();
		$amount = $details['amount'];
		$master_transaction_details['system_transaction_id'] = $system_transaction_id;
		$master_transaction_details['domain_list_fk'] = get_domain_auth_id();
		$master_transaction_details['transaction_type'] = $details['transaction_type'];
		$master_transaction_details['amount'] = $amount;
		$master_transaction_details['currency'] = $details['currency'];
		$master_transaction_details['currency_conversion_rate'] = $details['conversion_value'];
		$master_transaction_details['date_of_transaction'] = date('Y-m-d', strtotime($details['date_of_transaction']));
		$master_transaction_details['bank'] = $details['bank'];
		$master_transaction_details['branch'] = $details['branch'];
		$master_transaction_details['deposited_branch'] = @$details['deposited_branch'];
		$master_transaction_details['transaction_number'] = isset($details['transaction_number']) ? $details['transaction_number'] : 'N/A';
		$master_transaction_details['status'] = 'pending';
		$master_transaction_details['type'] = 'b2b';
		$master_transaction_details['remarks'] = $details['remarks'];
		$master_transaction_details['created_datetime'] = db_current_datetime();
		$master_transaction_details['created_by_id'] = $this->entity_user_id;
		$master_transaction_details['user_oid'] = $this->entity_user_id;
		$insert_id = $this->custom_db->insert_record('master_transaction_details', $master_transaction_details);
		$insert_id = $insert_id['insert_id'];
		$notification_users = $this->user_model->get_admin_user_id();
		if((!empty($type) == true) && ($type =='Credit')){
			$remarks = 'Credit Limit Request:'.$amount.' '.get_application_default_currency().'('.$this->agency_name.')';
			$this->application_logger->credit_limit_request($remarks, array('system_transaction_id' => $system_transaction_id), $notification_users);
		}
		else{
			$remarks = 'Deposit Request:'.$amount.' '.get_application_default_currency().'('.$this->agency_name.')';
			$this->application_logger->balance_deposit_request($remarks, array('system_transaction_id' => $system_transaction_id), $notification_users);
		}
		return $insert_id;
	}

	/**
	 * Master Transaction Request List
	 */
	function master_transaction_request_list($data_list_filt = '',$type ='')
	{
		// echo $type;exit;
		$data_list_cond = '';
		if (valid_array($data_list_filt) == true) {
			$data_list_cond = $this->custom_db->get_custom_condition($data_list_filt);
		}
		if(!empty($type))
		{
			$query = "select * from master_transaction_details MTD where MTD.type='b2b' and MTD.created_by_id=".$this->db->escape($this->entity_user_id)." and transaction_type='".$type."' ".$data_list_cond." order by origin DESC";

		}
		else{
			$query = "select * from master_transaction_details MTD where MTD.type='b2b' and MTD.user_oid=".$this->db->escape($this->entity_user_id)." and transaction_type!='Credit' ".$data_list_cond." order by origin DESC";
		}
		//debug($data_list_cond);exit;
		// echo $query;exit;
		return $this->db->query($query)->result_array();
	}
	/**
	 * filter_account_ledger based on range of dates
	 */
	function filter_account_ledger($search_data){
		$from = $search_data['from']." 00:00:00";
		$to = $search_data['to']." 23:59:59";
		$query = 'select * from master_transaction_details where type="b2b" and created_by_id='.$this->db->escape($this->entity_user_id).' and created_datetime BETWEEN "'.$from.'" AND "'.$to.'" order by origin DESC';
		return $this->db->query($query)->result_array();
	}

	/**
	 * Check if the Booking Amount is allowed on Client Domain
	 */
	function verify_current_balance($amount, $currency)
	{
		$status = FAILURE_STATUS;
		if ($this->verify_domain_balance == true) {
			if ($amount > 0) {
				$query = 'SELECT BU.balance, BU.credit_limit, BU.due_amount, CC.country as currency, CC.value as conversion_value
							from user as U
							JOIN b2b_user_details as BU ON U.user_id=BU.user_oid
							JOIN domain_list as DL ON U.domain_list_fk = DL.origin
							JOIN currency_converter CC ON CC.id=BU.currency_converter_fk
							WHERE U.status='.ACTIVE.' and U.user_id='.intval($this->entity_user_id).' and 
							DL.status='.ACTIVE.' and DL.origin='.$this->db->escape(get_domain_auth_id()).' and DL.domain_key = '.$this->db->escape(get_domain_key());
				$balance_record = $this->db->query($query)->row_array();
				// echo $query;exit;
				// debug($balance_record);exit;
				if ($currency == $balance_record['currency']) {
					// Due is always stored with -ve symbol
                    $balance = $balance_record['balance'] + floatval($balance_record ['credit_limit']) + floatval($balance_record ['due_amount']);
					// $balance = $balance_record['balance'];
					if ($balance >= $amount) {
						$status = SUCCESS_STATUS;
					} else {
						//Notify User about current balance problem
						//FIXME - send email, notification for less balance to domain admin and current domain admin
					}
				} else {
					echo 'Under Construction--Currency mismatch';
					exit;
				}
			}
		} else {
			$status = SUCCESS_STATUS;
		}
		return $status;
	}

	/**
	 *
	 * @param $fare
	 * @param $domain_markup
	 * @param $level_one_markup this is 0 as default as it is not mandatory to keep level one markup
	 */
	function update_transaction_details($transaction_type, $app_reference, $fare, $domain_markup=0, $level_one_markup=0,$convinence=0, $discount=0, $currency='INR', $currency_conversion_rate=1)
	{
		$status = FAILURE_STATUS;
		$remarks = $transaction_type.' Transaction was Successfully done';
		$amount = $this->agent_buying_price($transaction_type, $app_reference);
		$notification_users = $this->user_model->get_admin_user_id();
		$action_query_string = array('app_reference' => $app_reference, 'type' => $transaction_type, 'module' => $this->config->item('current_module'));
		if ($this->verify_domain_balance == true) {
			//$amount = floatval($fare+$domain_markup);
			if ($amount > 0) {
				
				//Log transaction details
				$this->save_transaction_details($transaction_type, $app_reference, $amount, $domain_markup, $level_one_markup, $remarks, $convinence, $discount, $currency, $currency_conversion_rate);

				$this->update_agent_balance((-$amount));
				//$this->save_transaction_details($transaction_type, $app_reference, $fare, $domain_markup, $level_one_markup, $remarks);
				$this->application_logger->transaction_status($remarks.'('.$amount.')', $action_query_string, $notification_users);
			}
		} else {
			$this->save_transaction_details($transaction_type, $app_reference, $amount, $domain_markup, $level_one_markup, $remarks, $convinence, $discount, $currency, $currency_conversion_rate);
			$this->application_logger->transaction_status($remarks.'('.$amount.')', $action_query_string, $notification_users);
			$status = SUCCESS_STATUS;
		}
		return $status;
	}
	/**
	 * FIXME: check it
	 * Balu A
	 */
	function agent_buying_price($transaction_type, $app_reference)
	{
		$this->load->library('booking_data_formatter');
		switch($transaction_type) {
			case 'flight':
				$this->load->model('flight_model');
				$booking_data = $this->flight_model->get_booking_details($app_reference, '');
				$booking_data = $this->booking_data_formatter->format_flight_booking_data($booking_data, 'b2b');
				$amount = $booking_data['data']['booking_details'][0]['agent_buying_price'];
				break;
			case 'hotel':
				$this->load->model('hotel_model');
				$booking_data = $this->hotel_model->get_booking_details($app_reference, '');
				$booking_data = $this->booking_data_formatter->format_hotel_booking_data($booking_data, 'b2b');
				$amount = $booking_data['data']['booking_details'][0]['agent_buying_price'];
				break;
			case 'bus':
				$this->load->model('bus_model');
				$booking_data = $this->bus_model->get_booking_details($app_reference, '');
				$booking_data = $this->booking_data_formatter->format_bus_booking_data($booking_data, 'b2b');
				$amount = $booking_data['data']['booking_details'][0]['agent_buying_price'];
				break;
			case 'car':
				$this->load->model('car_model');
				$booking_data = $this->car_model->get_booking_details($app_reference, '');
				$booking_data = $this->booking_data_formatter->format_car_booking_datas($booking_data, 'b2b');
                                
				$amount = $booking_data['data']['booking_details'][0]['agent_buying_price'];
				break;
			case 'sightseeing':
				$this->load->model('sightseeing_model');
				$booking_data = $this->sightseeing_model->get_booking_details($app_reference, '');
				$booking_data = $this->booking_data_formatter->format_sightseeing_booking_data($booking_data, 'b2b');
                                
				$amount = $booking_data['data']['booking_details'][0]['agent_buying_price'];
				break;
			case 'transferv1':
			    $this->load->model('transferv1_model');
				$booking_data = $this->transferv1_model->get_booking_details($app_reference, '');
				$booking_data = $this->booking_data_formatter->format_transferv1_booking_data($booking_data, 'b2b');
                                
				$amount = $booking_data['data']['booking_details'][0]['agent_buying_price'];
				break;
				case 'holiday':
				$this->load->model('tours_model');

				
				$booking_data = $this->tours_model->get_booking_details($app_reference, '');
				// debug($booking_data);exit;     
				$booking_data = $this->booking_data_formatter->format_holiday_booking_data($booking_data, 'b2b');
                   	// debug($booking_data);exit;            
				$amount = $booking_data['data']['booking_details'][0]['basic_fare'];
				//$amount = ($booking_data['data']['booking_details'][0]['product_total_price'])-($booking_data['data']['booking_details'][0]['agent_tds']);
				// debug($amount);exit;
				break;

		}
		return floatval($amount);
	}
	/**
	 * Update Balance of Agent
	 * @param number $amount Amount to be added or deducted
	 */
	function update_agent_balance($amount)
	{
		$current_balance = 0;
		$cond = array('user_oid' => intval($this->entity_user_id));
		$details = $this->custom_db->single_table_records('b2b_user_details', 'balance,due_amount,credit_limit', $cond);
		if ($details['status'] == true) {
			$details ['data'] [0] ['balance'] = $current_balance = ($details ['data'] [0] ['balance'] + $amount);
                if ($details ['data'] [0] ['balance'] < 0) {
					$details ['data'] [0] ['due_amount'] += $details ['data'] [0] ['balance'];
                    $details ['data'] [0] ['balance'] = 0;
                }
                // debug($details);exit;
			// $details['data'][0]['balance'] = $current_balance = ($details['data'][0]['balance'] + $amount);
			$this->custom_db->update_record('b2b_user_details', $details['data'][0], $cond);
			$this->balance_notification($current_balance);
		}
		return $current_balance;
	}

	/**
	 * Balu A
	 * if less than limit then send notification
	 */
	function balance_notification($current_balance)
	{
		$condition = array('agent_fk' => intval($this->entity_user_id));
		$details = $this->custom_db->single_table_records('agent_balance_alert_details', '*', $condition);
		if ($details['status'] == true) {
			$threshold_amount = $details['data'][0]['threshold_amount'];
			$mobile_number = trim($details['data'][0]['mobile_number']);
			$email_id = trim($details['data'][0]['email_id']);
			$enable_sms_notification = $details['data'][0]['enable_sms_notification'];
			$enable_email_notification = $details['data'][0]['enable_email_notification'];
			if($current_balance <= $threshold_amount) {
				//FIXME:Send Notification
				//SMS ALERT
				if($enable_sms_notification == ACTIVE && empty($mobile_number) == false) {
					//Send SMS Alert for Low Balance
				}
				//EMAIL NOTIFICATION
				if($enable_email_notification == ACTIVE && empty($email_id) == false) {
					//Send Email Notification for Low Balance
					$subject = $this->agency_name.'- Low Balance Alert';
					$message = 'Dear '.$this->entity_name.'<br/> <h1>Your Agent Balance is Low.</h1><br/><h2>Agent Balance as on '.date("Y-m-d h:i:sa").'is : '.COURSE_LIST_DEFAULT_CURRENCY_VALUE.' '.$threshold_amount.'/-</h2><h3>Please Recharge Your Account to enjoy UnInterrupted Bookings. :)</h3>';
					$this->load->library('provab_mailer');
					$mail_status = $this->provab_mailer->send_mail($email_id, $subject, $message);
				}
			}
		}
	}

	/**
	 * Save transaction logging for security purpose
	 * @param string $transaction_type
	 * @param string $app_reference
	 * @param number $fare
	 * @param number $domain_markup
	 * @param number $level_one_markup
	 * @param string $remarks
	 */
	function save_transaction_details($transaction_type, $app_reference, $fare, $domain_markup, $level_one_markup, $remarks, $convinence=0, $discount=0, $currency='USD', $currency_conversion_rate=1, $transaction_owner_id = 0)
	{	

		$transaction_owner_id = intval ( intval($transaction_owner_id) > 0 ? $transaction_owner_id : $this->entity_user_id);

		$transaction_log['system_transaction_id']	= date('Ymd-His').'-S-'.rand(1, 10000);
		$transaction_log['transaction_type']		= $transaction_type;
		$transaction_log['domain_origin']			= get_domain_auth_id();
		$transaction_log['app_reference']			= $app_reference;
		$transaction_log['fare']					= ($fare-$domain_markup);//net fare
		$transaction_log['level_one_markup']		= $level_one_markup;
		$transaction_log['domain_markup']			= $domain_markup;
		$transaction_log['remarks']					= $remarks;
		$transaction_log ['transaction_owner_id'] 	= $transaction_owner_id;
		$transaction_log['created_by_id']			= intval($this->entity_user_id) ;
		$transaction_log['created_datetime']		= date('Y-m-d H:i:s', time());
		
		$transaction_log['convinence_fees']			= $convinence;
		$transaction_log['promocode_discount']		= $discount;
		$transaction_log['currency']				= $currency;
		$transaction_log['currency_conversion_rate']= $currency_conversion_rate;
		
		
		
		//Opening and Closing Balance
		$total_transaction_amount = ($fare);
		$opening_closing_balance_details = $this->get_opening_closing_balance($transaction_owner_id, $total_transaction_amount);
		$transaction_log['opening_balance'] = $opening_closing_balance_details['opening_balance'];
		$transaction_log['closing_balance'] = $opening_closing_balance_details['closing_balance'];
		
		$this->custom_db->insert_record('transaction_log', $transaction_log);
	}

	/**
	 * Get Opening and Closing Balance Details
	 */
	function get_opening_closing_balance($user_oid, $total_transaction_amount)
	{
		$total_transaction_amount = floatval($total_transaction_amount);
		//Get current agent balance
		$query = 'SELECT balance AS closing_balance FROM b2b_user_details WHERE user_oid = '.intval($user_oid);
		$current_balance_details = $this->db->query($query)->row_array();
		$opening_balance = 			$current_balance_details['closing_balance'];
		$total_transaction_amount =	($total_transaction_amount) < 0 ? abs($total_transaction_amount) : -($total_transaction_amount);//if -Ve, convert to +Ve and ViceVersa
		$closing_balance = ($opening_balance+$total_transaction_amount);//Closing Balance
		$data['opening_balance'] = round(floatval($opening_balance), 4);
		$data['closing_balance'] = round(floatval($closing_balance), 4);
		return $data;
	}

	/**
	 * Balu A
	 * Flight Commission details
	 */
	function flight_commission_details()
	{
		$response['status'] = SUCCESS_STATUS;
		$response['data'] = array();
		$flight_commission_query = 'select BFCD.* from b2b_flight_commission_details as BFCD
								where BFCD.domain_list_fk ='.get_domain_auth_id().' 
								and ((BFCD.agent_fk='.intval($this->entity_user_id).' and BFCD.type="specific")	OR BFCD.type="generic")
								group by BFCD.agent_fk
								order by BFCD.agent_fk desc';
		$response['data']['flight_commission_details']		= $this->db->query($flight_commission_query)->result_array();
                
		return $response;
	}
	/**
	 * Balu A
	 * Bus Commission details
	 */
	function bus_commission_details()
	{
		$response['status'] = SUCCESS_STATUS;
		$response['data'] = array();
		$bus_commission_query = 'select BBCD.* from b2b_bus_commission_details as BBCD
								where BBCD.domain_list_fk ='.get_domain_auth_id().' 
								and ((BBCD.agent_fk='.intval($this->entity_user_id).' and BBCD.type="specific")	OR BBCD.type="generic")
								group by BBCD.agent_fk
								order by BBCD.agent_fk desc';
		$response['data']['bus_commission_details']		= $this->db->query($bus_commission_query)->result_array();
		return $response;
	}
	/**
	 * Elavarasi 
	 * Sightseeing Commission details
	 */
	function sightseeing_commission_details()
	{
		$response['status'] = SUCCESS_STATUS;
		$response['data'] = array();
		$bus_commission_query = 'select BSCD.* from b2b_sightseeing_commission_details as BSCD
								where BSCD.domain_list_fk ='.get_domain_auth_id().' 
								and ((BSCD.agent_fk='.intval($this->entity_user_id).' and BSCD.type="specific")	OR BSCD.type="generic")
								group by BSCD.agent_fk
								order by BSCD.agent_fk desc';
		$response['data']['sightseeing_commission_details']		= $this->db->query($bus_commission_query)->result_array();
		return $response;
	}
	/**
	 * Transfers 
	 * Transfers Commission details
	 */
	function transfer_commission_details()
	{
		$response['status'] = SUCCESS_STATUS;
		$response['data'] = array();
		$bus_commission_query = 'select BTCD.* from b2b_transfer_commission_details as BTCD
								where BTCD.domain_list_fk ='.get_domain_auth_id().' 
								and ((BTCD.agent_fk='.intval($this->entity_user_id).' and BTCD.type="specific")	OR BTCD.type="generic")
								group by BTCD.agent_fk
								order by BTCD.agent_fk desc';
		$response['data']['transfer_commission_details']		= $this->db->query($bus_commission_query)->result_array();
		return $response;
	}
	/**
	 * Balu A
	 * Bank Account Details
	 */
	function bank_account_details()
	{
		$query='SELECT BAD.* FROM bank_account_details BAD
		        JOIN user U on U.user_id=BAD.created_by_id
		        where BAD.domain_list_fk='.get_domain_auth_id() .' and BAD.status='.ACTIVE;
		$tmp_data = $this->db->query($query);
		if($tmp_data->num_rows()>0) {
			$tmp_data=$tmp_data->result_array();
			$data = array('status' => QUERY_SUCCESS, 'data' => $tmp_data);
		} else {
			$data = array('status' => QUERY_FAILURE);
		}
		return $data;
	}

	/**
	 * Agent Transaction Log
	 * @param unknown_type $condition
	 * @param unknown_type $count
	 * @param unknown_type $offset
	 * @param unknown_type $limit
	 */
	public function agent_account_ledger($condition=array(), $count=false, $offset=0, $limit=100000000000)
	{
		$data = array();
		$condition = $this->custom_db->get_custom_condition($condition);
		$agent_filter = '';
		$transaction_activated_from_date = '2017-01-10';//DONT REMOVE THIS CONDITION
		$agent_filter = ' AND U.user_id =' . $this->entity_user_id.' AND U.user_type ='.B2B_USER;
		
		if($count){
			$query = 'select count(*) as total_records from transaction_log TL 
						join user U on U.user_id=TL.transaction_owner_id
						where TL.origin>0 and date(TL.created_datetime)>= "'.$transaction_activated_from_date.'" '.$agent_filter.' '.$condition;
			$total_records = $this->db->query($query)->row_array();
			$data['total_records'] = $total_records['total_records'];
		} else{
			$query = 'SELECT U.agency_name,TL.*,
			 CASE TL.transaction_type
			   WHEN "flight" THEN 
			   					(select concat("LeadPax:", PD.first_name," ",PD.last_name, " PNR: ",group_concat(distinct(FTD. pnr))) as REF from flight_booking_transaction_details FTD,flight_booking_passenger_details PD
			   						WHERE FTD.app_reference = TL.app_reference and PD.app_reference = TL.app_reference 
			   						group by FTD.app_reference)
			   WHEN "hotel" THEN 
			   					(select concat("LeadPax:", PD.first_name," ",PD.last_name, " Booking ID: ",HTD.booking_id," Booking Ref.: ",HTD.booking_reference) as REF from hotel_booking_details HTD,hotel_booking_pax_details PD 
			   						WHERE HTD.app_reference = TL.app_reference and PD.app_reference = TL.app_reference 
			   						group by HTD.app_reference)
			   WHEN "bus" THEN 
			   					(select concat("LeadPax:", PD.name, " PNR.: ",BTD.pnr) as REF from bus_booking_details BTD,bus_booking_customer_details PD 
			   					WHERE BTD.app_reference = TL.app_reference and PD.app_reference = TL.app_reference 
			   					group by BTD.app_reference)
			   WHEN "transaction" THEN 
			   					(SELECT concat("Amount ",MTD.amount) as REF FROM `master_transaction_details` MTD WHERE MTD.`system_transaction_id` = TL.app_reference group by MTD.system_transaction_id)
			  END as "REF"
			FROM
			transaction_log TL 
			join user U on U.user_id = TL.transaction_owner_id 
			where 1=1 and date(TL.created_datetime)>= "'.$transaction_activated_from_date.'" '.$agent_filter.' '.$condition.' order by TL.created_datetime desc limit '.$offset.', '.$limit;
			$data['data'] = $this->db->query($query)->result_array();
		}
		return $data;
	}
}
