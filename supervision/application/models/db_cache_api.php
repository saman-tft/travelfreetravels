<?php
/**
 * Library which has cache functions to get data
 *
 * @package    Provab Application
 * @subpackage Travel Portal
 * @author     Balu A<balu.provab@gmail.com>
 * @version    V2
 */
Class Db_Cache_Api extends CI_Model
{
	function __construct()
	{
		$this->load->helper('custom/db_api');
	}

	private $cache;
	/**
	 * Balu A
	 * get the country details
	 * @param array $from array('k' => 'id', 'v' => 'name')
	 * @param array $condition
	 */
	function get_country_list($from=array('k' => 'origin', 'v' => 'name'), $condition=array('name !=' => ''))
	{
		//Balu A
		return magical_converter($from, $this->cache[$this->set_country_list($condition)]);
	}

	function get_state_list($from=array('k' => 'origin', 'v' => 'en_name'), $condition=array('en_name !=' => ''))
	{
            
		return magical_converter($from, $this->cache[$this->set_state_list($condition)]);
                
	}
	function set_state_list($condition) {
		$hash_key = hash('md5', __CLASS__.'api_state_list'.json_encode($condition));
		if (isset($this->cache[$hash_key]) == false) {
			$this->cache[$hash_key] = $this->custom_db->single_table_records('api_state_list', '*', $condition, 0, 100000000, array('en_name' => 'ASC'));
		}
		return $hash_key;
	}
	function get_country_code_list_profile($from=array('k' => 'country_code', 'v' => array('name', 'country_code')), $condition=array('name !=' => ''))
	{
		return $this->cache[$this->set_country_list($condition)];
		// return magical_converter($from, $this->cache[$this->set_country_list($condition)]);
	}

	/**
	 * set the country details
	 * @param array $condition
	 */
	function set_country_list($condition) {
		$hash_key = hash('md5', __CLASS__.'api_country_list'.json_encode($condition));
		if (isset($this->cache[$hash_key]) == false) {
			$this->cache[$hash_key] = $this->custom_db->single_table_records('api_country_list', '*', $condition, 0, 100000000, array('name' => 'ASC'));
		}
		return $hash_key;
	}

	/**
	 * Balu A
	 * get the country details
	 * @param array $from array('k' => 'id', 'v' => 'name')
	 * @param array $condition
	 */
	function get_current_balance()
	{
		//Balu A
		return $this->cache[$this->set_current_balance()];
	}

	/**
	 * set the country details
	 * @param array $condition
	 */
	function set_current_balance() {
		$hash_key = hash('md5', __CLASS__.'domain_list_balance');
		if (isset($this->cache[$hash_key]) == false) {
			$domain_id = intval(get_domain_auth_id());
			$domain_details = $this->custom_db->single_table_records('domain_list', 'balance', array('origin' => $domain_id));
			$this->cache[$hash_key] = array('value' => $domain_details['data'][0]['balance']);
		}
		return $hash_key;
	}
	/**
	 * Balu A
	 * get the Admin Base Currency
	 */
	function get_admin_base_currency()
	{
		return $this->cache[$this->set_admin_base_currency()];
	}

	/**
	 * Set the Admin Base Currency
	 * @param array $condition
	 */
	function set_admin_base_currency() {
		$hash_key = hash('md5', __CLASS__.'admin_base_currency');
		if (isset($this->cache[$hash_key]) == false) {
			$domain_id = intval(get_domain_auth_id());
			$query = 'select CC.country as base_currency 
					from domain_list DL
					JOIN currency_converter CC on CC.id=DL.currency_converter_fk
					where origin='.$domain_id;
			$domain_details = $this->db->query($query)->row_array();
			$this->cache[$hash_key] = $domain_details['base_currency'];
		}
		return $hash_key;
	}

	/**
	 * get the postal code details
	 * @param array $from array('k' => 'id', 'v' => 'name')
	 * @param array $condition
	 */

	function get_postal_code_list($from=array('k' => 'origin', 'v' => array('name', 'country_code')), $condition=array('name !=' => ''))
	{
		return magical_converter($from, $this->cache[$this->set_country_list($condition)]);
	}

	/**
	 * get the postal code details
	 * @param array $from array('k' => 'id', 'v' => 'name')
	 * @param array $condition
	 */

	function get_country_code_list($from=array('k' => 'country_code', 'v' => array('name', 'country_code')), $condition=array('name !=' => ''))
	{
		return magical_converter($from, $this->cache[$this->set_country_list($condition)]);
	}
	function get_crs_type($from=array('k' => 'origin', 'v' => array('module_name')), $condition=array('status'=>ACTIVE))
	{
		 
		return magical_converter($from, $this->cache[$this->set_crs_type($condition)]);
	}
	function set_crs_type($condition) {
		$hash_key = hash('md5', __CLASS__.'user_type'.json_encode($condition));
		if (isset($this->cache[$hash_key]) == false) {
			$this->cache[$hash_key] = $this->custom_db->single_table_records('crs_modules', '*', $condition, 0, 100000000, array('module_name' => 'ASC'));
			
		}
		return $hash_key;
	}

	/**
	 * get the user type details
	 * @param array $from array('k' => 'id', 'v' => 'name')
	 * @param array $condition
	 */
	/*function get_user_type($from=array('k' => 'origin', 'v' => array('user_type')), $condition=array('user_type !=' => '', 'origin !=' =>  ADMIN))
	{
		//FIXME
		if((isset($_GET['domain_origin']) == true && intval($_GET['domain_origin']) >0) ||
		(isset($_GET['uid']) == true && intval($_GET['uid']) >0) &&
		$this->entity_user_id == intval($_GET['uid'])) {
			//DOMAIN ADMIN CREATION BY PROVAB ADMIN (GET ONLY USER TYPE ADMIN) (OR) EDIT THEIR ACCOUNT
			//checking if it is Superadmin or Sub admin

			if($this->entity_user_type==ADMIN){
				$condition = array('user_type !=' => '', 'origin =' =>  ADMIN);
			}elseif ($this->entity_user_type==SUB_ADMIN) {
				$condition = array('user_type !=' => '', 'origin =' =>  SUB_ADMIN);
			}
			
		} else if(get_domain_auth_id() > 0) {
			//DOMAIN USERS CREATION BY DOMAIN ADMIN (GET ALL USER TYPES EXCEPT ADMIN USER TYPE)
			$condition = array('user_type !=' => '', 'origin !=' =>  ADMIN);
		}
		return magical_converter($from, $this->cache[$this->set_user_type($condition)]);
	}*/
	function get_user_type($user_type = null, $from=array('k' => 'origin', 'v' => array('user_type')), $condition=array('user_type !=' => '', 'origin !=' =>  ADMIN))
	{
		$user_type = ($user_type != null ? $user_type : ADMIN);

		// debug($user_type);
		if((isset($_GET['domain_origin']) == true && intval($_GET['domain_origin']) >0) ||
		(isset($_GET['uid']) == true && intval($_GET['uid']) >0) &&
		$this->entity_user_id == intval($_GET['uid'])) {
			//DOMAIN ADMIN CREATION BY PROVAB ADMIN (GET ONLY USER TYPE ADMIN) (OR) EDIT THEIR ACCOUNT
			$condition = array('user_type !=' => '', 'origin =' =>  $user_type);
		} else if(get_domain_auth_id() > 0) {
			//DOMAIN USERS CREATION BY DOMAIN ADMIN (GET ALL USER TYPES EXCEPT ADMIN USER TYPE)
			if($user_type != null)
			{
				if($user_type !=ADMIN)
				{

					$condition['origin'] = $user_type;
				}
			}
				
			else{
				$condition['origin !='] = ADMIN;
				

				$condition['user_type !='] = '';
			} 
				
		}
		// debug($condition);die;
		return magical_converter($from, $this->cache[$this->set_user_type($condition)]);
	}
	/**
	 * set the country details
	 * @param array $condition
	 */
	function set_user_type($condition) {
		// debug($condition);;exit;
		$cc= $this->custom_db->single_table_records('user_type', '*', $condition, 0, 100000000, array('origin' => 'ASC'));
		// echo $this->db->last_query();
		// debug($cc);exit;
		$hash_key = hash('md5', __CLASS__.'user_type'.json_encode($condition));
		if (isset($this->cache[$hash_key]) == false) {
			$this->cache[$hash_key] = $this->custom_db->single_table_records('user_type', '*', $condition, 0, 100000000, array('origin' => 'ASC'));
			
		}
		return $hash_key;
	}

	/**
	 * get the continent details
	 * @param array $from array('k' => 'origin', 'v' => 'name')
	 * @param array $condition
	 */
	function get_continent_list($from=array('k' => 'origin', 'v' => 'name'), $condition=array('name !=' => ''))
	{
		return magical_converter($from, $this->cache[$this->set_continent_list($condition)]);
	}

	/**
	 * set the continet details
	 * @param array $condition
	 */
	function set_continent_list($condition) {
		$hash_key = hash('md5', __CLASS__.'api_continent_list'.json_encode($condition));
		if (isset($this->cache[$hash_key]) == false) {
			$this->cache[$hash_key] = $this->custom_db->single_table_records('api_continent_list', '*', $condition);
		}
		return $hash_key;
	}

	/**
	 * get the city details
	 * @param array $from array('k' => 'id', 'v' => 'destination')
	 * @param array $condition
	 */
	function get_city_list($from=array('k' => 'origin', 'v' => 'destination'), $condition=array('destination !=' => ''))
	{
		return magical_converter($from, $this->cache[$this->set_city_list($condition)]);
	}

	/**
	 * set the city details
	 * @param array $condition
	 */
	function set_city_list($condition) {
		$hash_key = hash('md5', __CLASS__.'api_city_list'.json_encode($condition));
		if (isset($this->cache[$hash_key]) == false) {
			$this->cache[$hash_key] = $this->custom_db->single_table_records('api_city_list', '*', $condition);
		}
		return $hash_key;
	}
	/**
	 * 	Get Course Type
	 */
	function get_course_type($from=array('k' => 'origin', 'v' => 'name'), $condition=array('name !=' => ''))
	{
		return magical_converter($from, $this->cache[$this->set_course_type($condition)]);
	}

	/**
	 * get course type list
	 */
	function course_type_list($condition)
	{
		return $this->cache[$this->set_course_type($condition)];
	}

	/**
	 * set the course details
	 * @param array $condition
	 */
	function set_course_type($condition) {
		$hash_key = hash('md5', __CLASS__.'meta_course_list'.json_encode($condition));
		if (isset($this->cache[$hash_key]) == false) {
			$this->cache[$hash_key] = $this->custom_db->single_table_records('meta_course_list', '*', $condition, 0, 100000000, array('priority_number' => 'ASC'));
		}
		return $hash_key;
	}

	/**
	 * 	Get booking source Type
	 */
	function get_booking_source($from=array('k' => 'origin', 'v' => 'name'), $condition=array('name !=' => ''))
	{
		return magical_converter($from, $this->cache[$this->set_booking_source($condition)]);
	}

	/**
	 * set the booking source details
	 * @param array $condition
	 */
	function set_booking_source($condition) {
		$hash_key = hash('md5', __CLASS__.'booking_source'.json_encode($condition));
		if (isset($this->cache[$hash_key]) == false) {
			$this->cache[$hash_key] = $this->custom_db->single_table_records('booking_source', '*', $condition);
		}
		return $hash_key;
	}

	/**
	 * 	Get Currencies
	 */
	function get_currency($from=array('k' => 'id', 'v' => 'country'), $condition=array('country !=' => ''))
	{
		return magical_converter($from, $this->cache[$this->set_currency($condition)]);
	}

	/**
	 * set theCurrency details
	 * @param array $condition
	 */
	function set_currency($condition) {
		$hash_key = hash('md5', __CLASS__.'currency_converter'.json_encode($condition));
		if (isset($this->cache[$hash_key]) == false) {
			$this->cache[$hash_key] = $this->custom_db->single_table_records('currency_converter', '*', $condition, 0, 100000000, array('country' => 'ASC'));
		}
		return $hash_key;
	}

	/**
	 * 	Get Active Banks
	 */
	function get_active_bank_list($from=array('k' => 'origin', 'v' => array('en_bank_name', 'account_number')), $condition=array('status' => ACTIVE))
	{
		return magical_converter($from, $this->cache[$this->set_active_bank_list($condition)]);
	}

	/**
	 * set Active Banks details
	 * @param array $condition
	 */
	function set_active_bank_list($condition) {
		$hash_key = hash('md5', __CLASS__.'bank_payment_details'.json_encode($condition));
		if (isset($this->cache[$hash_key]) == false) {
			$this->cache[$hash_key] = $this->custom_db->single_table_records('bank_payment_details', '*', $condition, 0, 100000000, array('en_bank_name' => 'ASC'));
		}
		return $hash_key;
	}

	/**
	 * 	Get airport code
	 */
	function get_airport_code_list()
	{
		$airport_code_list = $this->cache[$this->set_airport_code_list()];
		$code_list = array();
		if (valid_array($airport_code_list['data'])) {
			foreach ($airport_code_list['data'] as $k => $v) {
				$code_list[$v['city'].':('.$v['code'].')'] = $v['city'].'('.$v['code'].')';
			}
		}
		return $code_list;
	}

	/**
	 * set airport details
	 * @param array $condition
	 */
	function set_airport_code_list() {
		$hash_key = hash('md5', __CLASS__.'city_code_list');
		if (isset($this->cache[$hash_key]) == false) {
			$this->cache[$hash_key] = $this->custom_db->single_table_records('city_code_list', '*', array(), 0, 100000000, array('priority_list' => 'DESC','city' => 'ASC'));
		}
		return $hash_key;
	}
	
	/**
	 * 	Get airport code
	 */
	function get_active_social_network_list()
	{
		$data_list = '';
		$raw_data = '';
		return $this->cache[$this->set_active_social_network_list()];
	}

	/**
	 * set airport details
	 * @param array $condition
	 */
	function set_active_social_network_list() {
		$hash_key = hash('md5', 'social_login');
		if (isset($this->cache[$hash_key]) == false) {
			$data = $this->custom_db->single_table_records('social_login', '*', array('domain_origin' => get_domain_auth_id()), 0, 10);
			$data_list = array();
			foreach ($data['data'] as $k => $v) {
				$data_list[$v['social_login_name']] = $v;
			}
			$this->cache[$hash_key] = $data_list;
		}
		return $hash_key;
	}

	function get_group_list($from = array('k' => 'origin', 'v' => 'name'), $condition = array()) {
		// Arjun J Gowda

		// echo $condition;
		return magical_converter ( $from, $this->cache [$this->set_group_list ( $condition )] );
	}
	/**
	 * set the group details
	 * 
	 * @param array $condition        	
	 */
	function set_group_list($condition) {
		$hash_key = hash ( 'md5', __CLASS__ . 'user_groups' . json_encode ( $condition ) );
		if (isset ( $this->cache [$hash_key] ) == false) {
			$this->cache [$hash_key] = $this->custom_db->single_table_records ( 'user_groups', '*', $condition, 0, 100000000, array (
					'origin' => 'ASC' 
			) );
		}
		return $hash_key;
	}

	/**
	 * Phaneesh Hegde
	 * get the distributor details
	 * 
	 * @param array $from=array('k'
	 *        	=> 'user_id', 'v' => array('agency_name', 'uuid'))
	 * @param array $condition        	
	 */
	function get_distributor_list($from = array('k' => 'user_id', 'v' => array( 'uuid','agency_name')), $condition = array('user_type' => DIST_USER)) {
		// Arjun J Gowda
		return magical_converter ( $from, $this->cache [$this->set_distributor_list ( $condition )] );
	}


	/**
	 * set the distributor details
	 * 
	 * @param array $condition        	
	 */
	function set_distributor_list($condition) {
		$hash_key = hash ( 'md5', __CLASS__ . 'user' . json_encode ( $condition ) );
		if (isset ( $this->cache [$hash_key] ) == false) {
			// debug($condition);exit;
			$res ['data'] = $this->db->join ( 'dist_user_details', 'dist_user_details.user_oid = user.user_id' )->where_in ( 'user.creation_source', [ 
					'superadmin',
					'admin',
					'subadmin' 
			] )->get_where ( 'user', $condition )->result_array ();
			$res ['status'] = SUCCESS_STATUS;
			$this->cache [$hash_key] = $res;
		}
		return $hash_key;
	}

	/**
	 * Balu A
	 * get the airline details
	 * @param array $from array('k' => 'id', 'v' => 'name')
	 * @param array $condition
	 */
	function get_airline_list($from=array('k' => 'code', 'v' => 'name'), $condition=array('name !=' => ''))
	{
		//Balu A
		return magical_converter($from, $this->cache[$this->set_airline_list($condition)]);
	}

	/**
	 * set the Airline details
	 * @param array $condition
	 */
	function set_airline_list($condition) {
		$hash_key = hash('md5', __CLASS__.'airline_list'.json_encode($condition));
		if (isset($this->cache[$hash_key]) == false) {
			$this->cache[$hash_key] = $this->custom_db->single_table_records('airline_list', '*', $condition, 0, 100000000, array('name' => 'ASC'));
		}
		return $hash_key;
	}

	/*
	 * Return Agent List with Agent ID
	 */
	function get_agent_list_with_id($from=array('k' => 'user_id', 'v' => 'agency_name'), $condition=array('agency_name !=' => '', 'user_type' => B2B_USER))
	{
		return magical_converter($from, $this->cache[$this->set_agent_list($condition)]);
	}
	function get_agent_list_with_id_user($from=array('k' => 'user_id', 'v' => 'agency_name'), $condition=array('agency_name !=' => '', 'user_type' => B2B_USER))
	{
		return magical_converter_b2b($from, $this->cache[$this->set_agent_list_user($condition)]);
	}

	/**
	 * set the country details
	 * @param array $condition
	 */
	function set_agent_list($condition) {
		$hash_key = hash('md5', __CLASS__.'agent_list'.json_encode($condition));
		if (isset($this->cache[$hash_key]) == false) {
			$this->cache[$hash_key] = $this->custom_db->single_table_records('user', '*', $condition, 0, 100000000, array('agency_name' => 'ASC'));
		}
		return $hash_key;
	}
	function set_agent_list_user($condition) {
		$hash_key = hash('md5', __CLASS__.'agent_list'.json_encode($condition));
		if (isset($this->cache[$hash_key]) == false) {
			$this->cache[$hash_key]  = $this->custom_db->single_table_records('user', '*', $condition, 0, 100000000, array('agency_name' => 'ASC'));
		}
		return $hash_key;
	}
	function country_list_iso(){
	    // for promocode country list 
		$country_list = $this->custom_db->single_table_records ( 'api_country_master', 'country_name,origin,iso_country_code', array (
				'country_name !=' => '' 
		), 0, 1000, array (
				'country_name' => 'ASC' 
		) );
		if ($country_list ['status'] == SUCCESS_STATUS) {
			$page_data = magical_converter ( array (
					'k' => 'iso_country_code',
					'v' => 'country_name' 
			), $country_list );
		}
		// added this foreach block
		 foreach ($page_data as $p_k => $p_v) {
            $page_data[$p_k] = $p_v . ' (' . $p_k . ')';
        }
		return $page_data;
	}
	// added these 2 new functions
	function airport_list_iso()
    {
        // for promocode city list 
        $airport_list = $this->custom_db->single_table_records('flight_airport_list', 'airport_city,airport_code,country,origin', array(
            'airport_city !=' => ''
        ), 0, 100000, array(
            'airport_city' => 'ASC'
        ));
        if ($airport_list['status'] == SUCCESS_STATUS) {
            $page_data = magical_converter(array(
                'k' => 'airport_code',
                'v' => 'airport_city'
            ), $airport_list);
        }
        foreach ($page_data as $p_k => $p_v) {
            $page_data[$p_k] = $p_v . ' (' . $p_k . ')';
        }
        return $page_data;
    }
    function airport_list_iso_dynamic($country_code)
    {
        // for promocode city list 
        $airport_list = $this->custom_db->single_table_records('flight_airport_list', 'airport_city,airport_code,country,origin', array(
            'airport_city !=' => '',
            'CountryCode =' => $country_code
        ), 0, 100000, array(
            'airport_city' => 'ASC'
        ));
        if ($airport_list['status'] == SUCCESS_STATUS) {
            $page_data = magical_converter(array(
                'k' => 'airport_code',
                'v' => 'airport_city'
            ), $airport_list);
        }
        foreach ($page_data as $p_k => $p_v) {
            $page_data[$p_k] = $p_v . ' (' . $p_k . ')';
        }
        return $page_data;
    }
}