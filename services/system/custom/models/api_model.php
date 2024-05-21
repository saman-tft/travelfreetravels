<?php
/**
 * Library which has generic functions to get data
 *
 * @package    Provab Application
 * @subpackage Api_Model
 * @author     Arjun J<arjunjgowda260389@gmail.com>
 * @version    V2
 */
class Api_Model extends CI_Model {
	/**
	 * Get active configuration
	 * 
	 * @param string $module
	 *        	- Code of module for which booking api config has to be loaded
	 * @param string $api
	 *        	- API for which config has to be browsed // Array or String
	 */
	function active_config($module, $api) {
		// echo $api;exit;
		$source_filter = '';
		if (is_array ( $api ) == true) {
			// group to IN
			$tmp_api = '';
			foreach ( $api as $k => $v ) {
				$tmp_api .= $this->db->escape ( $v ) . ',';
			}
			$tmp_api = substr ( $tmp_api, 0, - 1 ); // remove last ,
			$source_filter = 'BS.source_id IN (' . $tmp_api . ')';
		} else {
			// Single value direct =
			$source_filter = 'BS.source_id = ' . $this->db->escape ( $api );
		}
		// Meta_course_list, booking_source, activity_source_map, api_config
		$query = 'SELECT AC.config, BS.source_id AS api,AC.remarks FROM meta_course_list MCL, booking_source BS, activity_source_map ASM, api_config AC
		WHERE MCL.origin=ASM.meta_course_list_fk AND ASM.booking_source_fk=BS.origin AND ASM.status=' . ACTIVE . ' AND MCL.status=' . ACTIVE . '
		AND BS.origin=AC.booking_source_fk AND MCL.status=' . ACTIVE . ' AND MCL.course_id = ' . $this->db->escape ( $module ) . '
		AND ' . $source_filter . ' AND AC.status=' . ACTIVE;
		// echo $query;exit;
		  if($_SERVER['REMOTE_ADDR'] == '192.168.0.87'){
		  		// echo $query;exit;
		  }
		// $this->insert_api_data();
		$result_arr = $this->db->query ( $query )->result_array ();
		// debug($result_arr);exit;
		// 
		if (valid_array ( $result_arr ) == true) {
			$resp = array ();
			foreach ( $result_arr as $k => $v ) {
				// $config = $this->decrypt_api_data($v ['config']);
				// debug($v ['config']);exit;
//  				$config = '{
//   "client_id": "11f8a648a4ed406e9556b8214af4c1c3",
//   "client_secret": "eyJhbGciOiJIUzUxMiJ9.eyJ0bWNJZCI6IjQ3MDYxOTkiLCJjbGllbnRJZCI6IjExZjhhNjQ4YTRlZDQwNmU5NTU2YjgyMTRhZjRjMWMzIn0.ErFJt1mvIOXcsVKG3IH387LTO9QT54ttP_IuRAXgWK3czHUbhLmm3B3DlZ2PPkHiW_ZyQ5ylQROmyVfDTVtPgw",
//   "url": "https: //api.fabhotels.com/public/v1/"
// }';
	
				// if($_SERVER['REMOTE_ADDR'] == '192.168.0.87'){
		  // 		debug($config);exit;
		  // }
				// $config = json_decode($config);
				// debug($v ['config']);exit;
				$resp [$v ['api']] = array (
						'config' => $v ['config'],
						'remarks' => $v ['remarks'], 
				);
			}
			return $resp;
		} else {
			return false;
		}
		exit;
	}
	
	/**
	 * return active api config for one api only
	 * 
	 * @param string $module
	 *        	- Code of module for which booking api config has to be loaded
	 * @param string $api
	 *        	- API for which config has to be browsed // Array or String
	 */
	function active_api_config($module, $api) {
		$data = $this->active_config ( $module, $api );
       
		if ($data != FAILURE_STATUS) {
			return $data [$api];
		} else {
			return false;
		}
	}
	/**
	 * 
	 * Set API Session ID
	 * @param unknown_type $booking_source_fk
	 */
	public function update_api_session_id($booking_source, $session_id)
	{
		$booking_source_details = $this->db->query('select origin from booking_source where source_id="'.trim($booking_source).'"')->row_array();
		$booking_source_fk = $booking_source_details['origin'];
		$this->custom_db->update_record('api_session_id', array('session_id' => trim($session_id), 'last_updated_datetime' => db_current_datetime()), array('booking_source_fk' => intval($booking_source_fk)));
	}
	/**
	 * 
	 * Return API Session ID
	 * @param unknown_type $booking_source_fk
	 */
	public function get_api_session_id($booking_source, $session_expiry_time)
	{
		$session_id_details = $this->db->query('select ASI.session_id from api_session_id ASI
							join booking_source BS on BS.origin=ASI.booking_source_fk 
							where BS.source_id="'.$booking_source.'" and (ASI.last_updated_datetime + INTERVAL '.intval($session_expiry_time).' MINUTE) >= "'.db_current_datetime().'"')->row_array();
		if(isset($session_id_details['session_id']) == true && empty($session_id_details['session_id']) == false){
			return $session_id_details['session_id'];
		}
	}
	/**
	 * Stores Client Requests
	 */
	public  function store_client_request($request_type='', $request='')
	{
		//TODO:$this->inactive_cache_services
		if($request_type !=''){
			if(is_array($request)) {
				$request = json_encode($request);
			}
			$provab_api_request_history = array();
			$provab_api_request_history['request_type'] = $request_type;
                        //$provab_api_request_history['TraceLogs'] =  $this->session->userdata('session_id');
			$provab_api_request_history['request'] = $request;
			$provab_api_request_history['domain_origin'] = get_domain_auth_id();
			$provab_api_request_history['created_datetime'] = date('Y-m-d H:i:s');
			
			return $this->custom_db->insert_record('provab_api_request_history',$provab_api_request_history);
		}
	}
        
        /**
	 * Stores Client return response
	 */
        public function store_client_return_response($request_type='', $request='', $response='')
	{
		//TODO:$this->inactive_cache_services
		if($request_type !=''){
			if(is_array($request)) {
				$request = json_encode($request);
			}
                        if(is_array($response)) {
				$response = json_encode($response);
			}
                        $provab_api_request_history = array();
			$provab_api_request_history['request_type'] = $request_type;
                        $provab_api_request_history['request'] = $request;
                        $provab_api_request_history['response'] = $response;
			$provab_api_request_history['domain_origin'] = get_domain_auth_id();
			$provab_api_request_history['created_datetime'] = date('Y-m-d H:i:s');
			return $this->custom_db->insert_record('provab_api_return_response_history',$provab_api_request_history);
		}
	}
	/**
	 * Stores API Requests
	 */
	public  function store_api_request($request_type, $request, $remarks, $server_info='',$search_id='')
	{
            
		
		//TODO:$this->inactive_cache_services
		if($request_type !=''){
			if(is_array($request)) {
				$response = json_encode($request);
			}

			$server_info = '';
			if(is_array($server_info)) {
				$server_info = json_encode($server_info);
			}


			$provab_api_response_history = array();
			$provab_api_response_history['server_info'] = $server_info;
			$provab_api_response_history['request_type'] = $request_type;
			$provab_api_response_history['request'] = $request;
			$provab_api_response_history['remarks'] = $remarks;
                        $provab_api_response_history['domain_origin'] = get_domain_auth_id();
                        $provab_api_response_history['search_id'] = $search_id;
			$provab_api_response_history['created_datetime'] = date('Y-m-d H:i:s');
                        //$provab_api_response_history['booking_source'] = $booking_sources;
			//debug($provab_api_response_history);exit;
			return $this->custom_db->insert_record('provab_api_response_history',$provab_api_response_history);
		}
	}
	/**
	 * Stores Travelport API Requests
	 */
	public function store_api_request_booking($request_type, $request, $response, $remarks){
		//TODO:$this->inactive_cache_services
		if($request_type !=''){
			if(is_array($request)) {
				$response = json_encode($request);
			}
			$provab_api_response_history = array();
			$provab_api_response_history['request_type'] = $request_type;
			$provab_api_response_history['request'] = $request;
			$provab_api_response_history['response'] = $response;
			$provab_api_response_history['remarks'] = $remarks;
                        $provab_api_response_history['domain_origin'] = get_domain_auth_id();
			$provab_api_response_history['created_datetime'] = date('Y-m-d H:i:s');
			return $this->custom_db->insert_record('provab_api_response_history',$provab_api_response_history);
		}
	}
	/**
	 * Stores API Requests
	 */
	public  function update_api_response($response, $origin, $totaltime=0)
	{
		//TODO:$this->inactive_cache_services
		if(intval($origin) > 0){
			if(is_array($response)) {
				$response = json_encode($response);
			}
			$provab_api_response_history = array();
			$provab_api_response_history['response'] = $response;
                        $provab_api_response_history['flight_api_response'] = $totaltime;
			$provab_api_response_history['response_updated_time'] = date('Y-m-d H:i:s');
                        //debug($provab_api_response_history);
                         if($_SERVER['REMOTE_ADDR']=="192.168.0.87") {
                                             // debug($provab_api_response_history);
                                          }
			$this->custom_db->update_record('provab_api_response_history',$provab_api_response_history, array('origin' => intval($origin)));
		}
	}
	/**
	 * Checks Cache is enabled for Service 
	 * Enter description here ...
	 */
	public function inactive_client_cache_services($service_name)
	{
		$inactive_cache = array('SEARCH', 'GETCALENDARFARE', 'FARERULE');
		//$inactive_cache = array();
		if(in_array(strtoupper($service_name), $inactive_cache) == true){
			return true;
		} else {
			return false;
		}
	}
	
	public function inactive_api_cache_services()
	{
		
	}
	public function insert_credit_card(){
		$card_data = '{
		  "card_number": 376540317621002,
		  "bank_country_code": "IN",
		  "bank_name": "AMERICAN EXPRESS",
		  "exp_date": "2024-03",
		  "card_name": "VINAY PRAKASH SHUKLA",
		  "card_type": "AX"
		}';
		$card_data = credit_encrypt($card_data);

		$credit_card_data['data'] = $card_data;
		$credit_card_data['booking_source'] = 'PTBSID0000000007';
		$this->custom_db->insert_record('credit_card',$credit_card_data);
		$credit_card_info = $this->custom_db->single_table_records('credit_card', '*', array('booking_source' => 'PTBSID0000000007'));
		if($credit_card_info['status'] == true){
			$credit_card_info = credit_decrypt($credit_card_info['data'][0]['data']);
			
		}
		debug($credit_card_info);exit;
		credit_encrypt($card_data);
		exit;
	}
	public function insert_api_data(){
		// $this->dec_insert_api_data();
		$output = false;
    	$encrypt_method = "AES-256-CBC";
		$api_data = $this->custom_db->single_table_records('api_config_prod_org', '*', array('origin' => 20));
// debug($api_data);exit;
		$secret_iv = SEC_VALUE;
		
		if($api_data['status'] == true){
			foreach($api_data['data'] as $data){
				
				if(!empty($data['config'])){
					
					$md5_key = MD5_VALUE;
					$encrypt_key = ENCRYPT_KEY;
					$decrypt_password = $this->db->query("SELECT AES_DECRYPT($encrypt_key,SHA2('".$md5_key."',512)) AS decrypt_data");
					$db_data = $decrypt_password->row();
					$secret_key = trim($db_data->decrypt_data);	
				    $key = hash('sha256', $secret_key);
				   	$iv = substr(hash('sha256', $secret_iv), 0, 16);
				   	$output = openssl_encrypt($data['config'], $encrypt_method, $key, 0, $iv);
				    $output = base64_encode($output);
				 	$decrypt = openssl_decrypt(base64_decode($output), $encrypt_method, $key, 0, $iv);
				
					$api_config_data['booking_source_fk'] = $data['booking_source_fk'];
					$api_config_data['system'] = $data['system'];
					$api_config_data['config'] = $output;
					$api_config_data['status'] = $data['status'];
					$api_config_data['remarks'] = $data['remarks'];
						 // debug($api_config_data);
					// $this->custom_db->update_record('api_config_test_enc',$api_config_data, array('origin' => 15) );
					
					
					$this->custom_db->insert_record('api_config_prod_org_enc',$api_config_data);
					echo $this->db->last_query();exit;
				}
			}
			exit;
		}
		exit;
	}
	public function decrypt_api_data($string){
		$secret_iv = SEC_VALUE;
		
		$output = false;
    	$encrypt_method = "AES-256-CBC";
		if(!empty($string)){
			$md5_key = MD5_VALUE;
			$encrypt_key = ENCRYPT_KEY;
			$decrypt_password = $this->db->query("SELECT AES_DECRYPT($encrypt_key,SHA2('".$md5_key."',512)) AS decrypt_data");
			
			$db_data = $decrypt_password->row();
			$secret_key = trim($db_data->decrypt_data);	
			$key = hash('sha256', $secret_key);
		    $iv = substr(hash('sha256', $secret_iv), 0, 16);
		   	$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
			return $output;
		}
	}
}
