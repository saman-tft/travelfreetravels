<?php
/**
 * Library which has generic functions to get data
 *
 * @package    Provab Application
 * @subpackage Hotel Model
 * @author     Balu A<balu.provab@gmail.com> 
 * @version    V2
 */
Class Hotel_Model extends CI_Model
{
	private $master_search_data;
	/**
	 * return booking list
	 */
	function booking($condition=array(), $count=false, $offset=0, $limit=100000000000)
	{
		$condition = $this->custom_db->get_custom_condition($condition);
		//BT, CD, ID
		if ($count) {
			$query = 'select count(distinct(BD.app_reference)) as total_records 
					from hotel_booking_details BD
					join hotel_booking_itinerary_details AS HBID on BD.app_reference=HBID.app_reference
					join payment_option_list AS POL on BD.payment_mode=POL.payment_category_code 
					where BD.domain_origin='.get_domain_auth_id().' '.$condition;
			$data = $this->db->query($query)->row_array();
			return $data['total_records'];
		} else {
			$this->load->library('booking_data_formatter');
			$response['status'] = SUCCESS_STATUS;
			$response['data'] = array();
			$booking_itinerary_details	= array();
			$booking_customer_details	= array();
			$cancellation_details = array();
			$bd_query = 'select * from hotel_booking_details AS BD 
						WHERE BD.domain_origin='.get_domain_auth_id().''.$condition.'
						order by BD.origin desc limit '.$offset.', '.$limit;
			$booking_details = $this->db->query($bd_query)->result_array();
			$app_reference_ids = $this->booking_data_formatter->implode_app_reference_ids($booking_details);
			if(empty($app_reference_ids) == false) {
				$id_query = 'select * from hotel_booking_itinerary_details AS ID 
							WHERE ID.app_reference IN ('.$app_reference_ids.')';
				$cd_query = 'select * from hotel_booking_pax_details AS CD 
							WHERE CD.app_reference IN ('.$app_reference_ids.')';
				$cancellation_details_query = 'select * from hotel_cancellation_details AS HCD 
							WHERE HCD.app_reference IN ('.$app_reference_ids.')';
				$booking_itinerary_details	= $this->db->query($id_query)->result_array();
				$booking_customer_details	= $this->db->query($cd_query)->result_array();
				$cancellation_details	= $this->db->query($cancellation_details_query)->result_array();
			}
			$response['data']['booking_details']			= $booking_details;
			$response['data']['booking_itinerary_details']	= $booking_itinerary_details;
			$response['data']['booking_customer_details']	= $booking_customer_details;
			$response['data']['cancellation_details']	= $cancellation_details;
			return $response;
		}
	}
		public function nationality_region()
	{
		$query = 'select * from all_nationality_region where  module="hotel" order by name';
		$result = $this->db->query($query);
     	return  $result->result_array();
	}
		public function record_delete($table,$id) {
		$query = "delete from ".$table." where id='$id'";
		$result = $this->db->query($query);
		// echo $this->db->query();exit;
	    return  $result;
	}
	public function record_delete2($table,$id) {
		$query = "delete from ".$table." where origin='$id'";
		$result = $this->db->query($query);
		// echo $this->db->query();exit;
	    return  $result;
	}
	function check_nationality_duplicate($tours_continent,$package_name){
	   $this->db->select("*");
	   $this->db->from("all_nationality_country");
	   $this->db->where('name',$package_name);
	   $this->db->where('continent',$tours_continent);
	   $this->db->where('module','activity');
	   $res = $this->db->get()->result_array(); 
	   return $res;
	}
	public function check_region_exist_all($tours_continent)
	{
		
		$this->db->select('*');
		$this->db->where('name',$tours_continent);
		$this->db->where('module','hotel');
		$this->db->where('created_by',$this->entity_user_id);
		$query = $this->db->get('all_nationality_region');
		if ( $query->num_rows > 0 ) {
			return $query->result();
		}else{
			return array();
		}		
	}
	public function table_record_details($table,$id)
	{
		$query = "select * from ".$table." where id='$id'";  
		$result = $this->db->query($query);
	    return  $result->row_array();
	}
	public function get_nationalityCountryList($id='')
	{
		$this->db->select('nc.*,nr.name as regionName');
		$this->db->from('all_nationality_country nc');    
		if($id !=''){
			$this->db->where('nc.origin',$id);
		}
	
		$this->db->where('nc.module','hotel');
		$this->db->join('all_nationality_region  nr','nc.continent=nr.id');  
  		$this->db->order_by('nc.origin','desc'); 
		$query = $this->db->get(); 
 
			   // debug($query->result_array());exit();
		if($query->num_rows() > 0)
		{
			 return $query->result_array();
		}
		else
		{
			return '';
		} 
	}
	public function get_nationality_regions()
	{
		$query = 'select * from all_nationality_region where status = 1 and module="hotel" and created_by='.$this->entity_user_id.' order by name'; 
		$result = $this->db->query($query);
     	return  $result->result_array();
		
	}
	function get_hb_country_list()
   {
	   $sql="SELECT country_name,origin,city_name,country_code FROM all_api_city_master_hb group by country_name";
	   $rs=$this->db->query($sql); 
		if($rs->num_rows() ==''){
			return '';
		}
		else
		{
			return $rs->result_array();
		} 
   }

	public function add_price_cat($data) 
	{
		$this->db->insert ('all_nationality_country', $data );
		return $this->db->insert_id ();
	}
	public function update_price_cat($newpprice, $id) 
	{
		$this->db->where ( 'origin', $id );
		return $this->db->update ( 'all_nationality_country', $newpprice );
	}
	public function get_nationality_group()
	{
		
		$this->db->select('*');
		$this->db->where('module','hotel');
		$this->db->where('created_by',$this->entity_user_id);
		$query = $this->db->get('all_nationality_country');
		if ( $query->num_rows > 0 ) {
			return $query->result();
		}else{
			return array();
		}		
	}
	public function nationality_group_data() {
		$this->db->select('*');
		$this->db->where ( 'status', 1 );
		$this->db->where('module','hotel');
	//	$this->db->where('created_by',$this->entity_user_id);
		$query = $this->db->get('all_nationality_country');
	//	debug($query->result());die;
		if ( $query->num_rows > 0 ) {
			return $query->result();
		}else{
			return array();
		}	

		

	}
		function crsbooking($condition=array(), $count=false, $offset=0, $limit=100000000000)
	{
		$condition = $this->custom_db->get_custom_condition($condition);
		//BT, CD, ID
		if ($count) {
			$query = 'select count(distinct(BD.app_reference)) as total_records 
					from hotel_booking_details BD
					join hotel_booking_itinerary_details AS HBID on BD.app_reference=HBID.app_reference
					join payment_option_list AS POL on BD.payment_mode=POL.payment_category_code 
					where BD.booking_source="PTBSID0000000011" AND BD.domain_origin='.get_domain_auth_id().' '.$condition;
			$data = $this->db->query($query)->row_array();
			return $data['total_records'];
		} else {
			$this->load->library('booking_data_formatter');
			$response['status'] = SUCCESS_STATUS;
			$response['data'] = array();
			$booking_itinerary_details	= array();
			$booking_customer_details	= array();
			$cancellation_details = array();
			$bd_query = 'select * from hotel_booking_details AS BD 
						WHERE BD.domain_origin='.get_domain_auth_id().''.$condition.'
						order by BD.origin desc limit '.$offset.', '.$limit;
			$booking_details = $this->db->query($bd_query)->result_array();
			$app_reference_ids = $this->booking_data_formatter->implode_app_reference_ids($booking_details);
			if(empty($app_reference_ids) == false) {
				$id_query = 'select * from hotel_booking_itinerary_details AS ID 
							WHERE ID.app_reference IN ('.$app_reference_ids.')';
				$cd_query = 'select * from hotel_booking_pax_details AS CD 
							WHERE CD.app_reference IN ('.$app_reference_ids.')';
				$cancellation_details_query = 'select * from hotel_cancellation_details AS HCD 
							WHERE HCD.app_reference IN ('.$app_reference_ids.')';
				$booking_itinerary_details	= $this->db->query($id_query)->result_array();
				$booking_customer_details	= $this->db->query($cd_query)->result_array();
				$cancellation_details	= $this->db->query($cancellation_details_query)->result_array();
			}
			$response['data']['booking_details']			= $booking_details;
			$response['data']['booking_itinerary_details']	= $booking_itinerary_details;
			$response['data']['booking_customer_details']	= $booking_customer_details;
			$response['data']['cancellation_details']	= $cancellation_details;
			return $response;
		}
	}
	public function view_exclusion_types() { 
	  
		$q= $this->db->get ( 'hotel_allowed' );
		return $q;
	}
	public function get_cat_id($id) {
		$this->db->select ( '*' );
		$this->db->where ( 'allowID', $id );
		return $this->db->get ( 'hotel_allowed' )->result ();
	}
	public function update_exclusion_type($add_package_data, $id) {
		$this->db->where ( 'allowID', $id );
		$this->db->update ( 'hotel_allowed', $add_package_data );
	}
	public function delete_exclusion_types($id) {
		$this->db->where ( 'allowID', $id );
		$this->db->delete ( 'hotel_allowed' );
	}
	 function get_inactive_count()
    {
        $bd_query = "select * from crs_hotel_details where status='INACTIVE'";
		
		$booking_details = $this->db->query ( $bd_query )->result_array ();
		
		return count($booking_details);
    }
//----------------------sdp ------- 

function b2c_hotel_report($condition=array(), $count=false, $offset=0, $limit=100000000000)
	{
		$condition = $this->custom_db->get_custom_condition($condition);
		
		if(isset($condition) == true)
		{
			$offset = 0;
		}else{
			$offset = $offset;
		}

		//BT, CD, ID
		if ($count) {
			$query = 'select count(distinct(BD.app_reference)) as total_records
					from hotel_booking_details BD
					join hotel_booking_itinerary_details AS HBID on BD.app_reference=HBID.app_reference
					join payment_option_list AS POL on BD.payment_mode=POL.payment_category_code
					left join user as U on BD.created_by_id = U.user_id 
					where (U.user_type='.B2C_USER.' OR BD.created_by_id = 0) AND BD.domain_origin='.get_domain_auth_id().' '.$condition.'';

			$data = $this->db->query($query)->row_array();
			return $data['total_records'];
		} else {
			$this->load->library('booking_data_formatter');
			$response['status'] = SUCCESS_STATUS;
			$response['data'] = array();
			$booking_itinerary_details	= array();
			$booking_customer_details	= array();
			$cancellation_details = array();
			
			$bd_query = 'select BD.* ,U.user_name,U.first_name,U.last_name from hotel_booking_details AS BD
					     left join user U on BD.created_by_id =U.user_id 					     
						 WHERE  (U.user_type='.B2C_USER.' OR BD.created_by_id = 0) AND BD.domain_origin='.get_domain_auth_id().''.$condition.'						 
						 order by BD.created_datetime desc, BD.origin desc limit '.$offset.', '.$limit.'';

			$booking_details = $this->db->query($bd_query)->result_array();
			$app_reference_ids = $this->booking_data_formatter->implode_app_reference_ids($booking_details);
			if(empty($app_reference_ids) == false) {
				$id_query = 'select * from hotel_booking_itinerary_details AS ID 
							WHERE ID.app_reference IN ('.$app_reference_ids.')';
				$cd_query = 'select * from hotel_booking_pax_details AS CD 
							WHERE CD.app_reference IN ('.$app_reference_ids.')';
				$cancellation_details_query = 'select * from hotel_cancellation_details AS HCD 
							WHERE HCD.app_reference IN ('.$app_reference_ids.')';
				$payment_details_query = 'select * from  payment_gateway_details AS PD
							WHERE PD.app_reference IN ('.$app_reference_ids.')';

				$booking_itinerary_details	= $this->db->query($id_query)->result_array();
				$booking_customer_details	= $this->db->query($cd_query)->result_array();
				$cancellation_details	= $this->db->query($cancellation_details_query)->result_array();
				$payment_details = $this->db->query($payment_details_query)->result_array();
			}
			$response['data']['booking_details']			= $booking_details;
			$response['data']['booking_itinerary_details']	= $booking_itinerary_details;
			$response['data']['booking_customer_details']	= $booking_customer_details;
			$response['data']['cancellation_details']	= $cancellation_details;
			$response['data']['payment_details']	= $payment_details;
		
			return $response;
		}
	}
	public function update_refund_status($add_package_data, $id) {
		$this->db->where ( 'app_reference', $id );
		$this->db->update ( 'hotel_booking_details', $add_package_data );
	}
	function b2c_hotel_report_all_invoice($user_type='',$condition=array(), $count=false, $offset=0, $limit=100000000000)
	{
		$condition = $this->custom_db->get_custom_condition($condition);
		
		if(isset($condition) == true)
		{
			$offset = 0;
		}else{
			$offset = $offset;
		}

		//BT, CD, ID
		
			$this->load->library('booking_data_formatter');
			$response['status'] = SUCCESS_STATUS;
			$response['data'] = array();
			$booking_itinerary_details	= array();
			$booking_customer_details	= array();
			$cancellation_details = array();
			if($user_type==B2C_USER)
			{
				$bd_query = 'select BD.* ,U.user_name,U.first_name,U.last_name from hotel_booking_details AS BD
					     left join user U on BD.created_by_id =U.user_id 					     
						 WHERE  (U.user_type='.B2C_USER.' OR BD.created_by_id = 0) AND BD.domain_origin='.get_domain_auth_id().''.$condition.'						 
						 order by BD.created_datetime desc, BD.origin desc limit '.$offset.', '.$limit.'';
			}
			else
			{
				$bd_query = 'select BD.* ,U.agency_name,U.first_name,U.last_name from hotel_booking_details AS BD
					     left join user U on BD.created_by_id =U.user_id 					     
						 WHERE  U.user_type='.B2B_USER.' AND BD.domain_origin='.get_domain_auth_id().' '.$condition.'
						 order by BD.created_datetime desc, BD.origin desc limit '.$offset.', '.$limit;
			}
			

			$booking_details = $this->db->query($bd_query)->result_array();
			$app_reference_ids = $this->booking_data_formatter->implode_app_reference_ids($booking_details);
			
		
			return $booking_details;
		
	}
	function b2b_hotelcrs_report($condition=array(), $count=false, $offset=0, $limit=100000000000, $b_status='')
	{
		$condition = $this->custom_db->get_custom_condition($condition);

		if(isset($condition) == true)
		{
			$offset = 0;
		}else{
			$offset = $offset;
		}
		if($b_status == "confirmed_cancelled"){
			$condition = 'AND (BD.status = '.$this->db->escape('BOOKING_CONFIRMED').' OR BD.status = '.$this->db->escape('BOOKING_CANCELLED').')';
		}
$booking_source='PTBSID0000000011';
		//BT, CD, ID
		if ($count) {
			$query = "select count(distinct(BD.app_reference)) as total_records 
					from hotel_booking_details BD
					join hotel_booking_itinerary_details AS HBID on BD.app_reference=HBID.app_reference
					join payment_option_list AS POL on BD.payment_mode=POL.payment_category_code 
					left join user as U on BD.created_by_id = U.user_id  where U.user_type=".B2B_USER." AND  BD.booking_source='".$booking_source."' AND BD.domain_origin=".get_domain_auth_id()." ".$condition;
			$data = $this->db->query($query)->row_array();
			return $data['total_records'];
		} else {
			$this->load->library('booking_data_formatter');
			$response['status'] = SUCCESS_STATUS;
			$response['data'] = array();
			$booking_itinerary_details	= array();
			$booking_customer_details	= array();
			$cancellation_details = array();
			$bd_query = "select BD.* ,U.agency_name,U.first_name,U.last_name, U.uuid AS agency_id, BS.name AS supp_name from hotel_booking_details AS BD
					     left join user U on BD.created_by_id =U.user_id
					     left join booking_source BS on BS.source_id = BD.booking_source 				     
						 WHERE  U.user_type=".B2B_USER." AND BD.booking_source='".$booking_source."' AND BD.domain_origin=".get_domain_auth_id()." ".$condition."
						 order by BD.created_datetime desc, BD.origin desc limit ".$offset.", ".$limit;
			$booking_details = $this->db->query($bd_query)->result_array();
			$app_reference_ids = $this->booking_data_formatter->implode_app_reference_ids($booking_details);
			if(empty($app_reference_ids) == false) {
				$id_query = 'select * from hotel_booking_itinerary_details AS ID 
							WHERE ID.app_reference IN ('.$app_reference_ids.')';
				$cd_query = 'select * from hotel_booking_pax_details AS CD 
							WHERE CD.app_reference IN ('.$app_reference_ids.')';
				$cancellation_details_query = 'select * from hotel_cancellation_details AS HCD 
							WHERE HCD.app_reference IN ('.$app_reference_ids.')';
				$booking_itinerary_details	= $this->db->query($id_query)->result_array();
				$booking_customer_details	= $this->db->query($cd_query)->result_array();
				$cancellation_details	= $this->db->query($cancellation_details_query)->result_array();
			}
			$response['data']['booking_details']			= $booking_details;
			$response['data']['booking_itinerary_details']	= $booking_itinerary_details;
			$response['data']['booking_customer_details']	= $booking_customer_details;
			$response['data']['cancellation_details']	= $cancellation_details;
			return $response;
		}
	}
function b2c_hotelcrs_report($condition=array(), $count=false, $offset=0, $limit=100000000000)
	{
		$condition = $this->custom_db->get_custom_condition($condition);
		
		if(isset($condition) == true)
		{
			$offset = 0;
		}else{
			$offset = $offset;
		}
$booking_source='PTBSID0000000011';
		//BT, CD, ID
		if ($count) {
			
			$query = "select count(distinct(BD.app_reference)) as total_records
					from hotel_booking_details BD
					join hotel_booking_itinerary_details AS HBID on BD.app_reference=HBID.app_reference
					left join user as U on BD.created_by_id = U.user_id 
					where (U.user_type='.B2C_USER.' OR BD.created_by_id=0 OR BD.created_by_id!=0) AND BD.booking_source='".$booking_source."'  AND  BD.domain_origin=".get_domain_auth_id()." ".$condition."";

			$data = $this->db->query($query)->row_array();
			return $data['total_records'];
		} else {
			$this->load->library('booking_data_formatter');
			$response['status'] = SUCCESS_STATUS;
			$response['data'] = array();
			$booking_itinerary_details	= array();
			$booking_customer_details	= array();
			$cancellation_details = array();
			
			$bd_query = "select BD.* ,U.user_name,U.first_name,U.last_name from hotel_booking_details AS BD
					     left join user U on BD.created_by_id =U.user_id 					     
						 WHERE  (U.user_type='.B2C_USER.' OR BD.created_by_id=0 OR BD.created_by_id!=0) AND BD.booking_source='".$booking_source."' AND  BD.domain_origin=".get_domain_auth_id()."".$condition."						 
						 order by BD.created_datetime desc, BD.origin desc limit ".$offset.", ".$limit."";

			$booking_details = $this->db->query($bd_query)->result_array();
			$app_reference_ids = $this->booking_data_formatter->implode_app_reference_ids($booking_details);
			if(empty($app_reference_ids) == false) {
				$id_query = 'select * from hotel_booking_itinerary_details AS ID 
							WHERE ID.app_reference IN ('.$app_reference_ids.')';
				$cd_query = 'select * from hotel_booking_pax_details AS CD 
							WHERE CD.app_reference IN ('.$app_reference_ids.')';
				$cancellation_details_query = 'select * from hotel_cancellation_details AS HCD 
							WHERE HCD.app_reference IN ('.$app_reference_ids.')';
				$payment_details_query = 'select * from  payment_gateway_details AS PD
							WHERE PD.app_reference IN ('.$app_reference_ids.')';

				$booking_itinerary_details	= $this->db->query($id_query)->result_array();
				$booking_customer_details	= $this->db->query($cd_query)->result_array();
				$cancellation_details	= $this->db->query($cancellation_details_query)->result_array();
				$payment_details = $this->db->query($payment_details_query)->result_array();
			}
			$response['data']['booking_details']			= $booking_details;
			$response['data']['booking_itinerary_details']	= $booking_itinerary_details;
			$response['data']['booking_customer_details']	= $booking_customer_details;
			$response['data']['cancellation_details']	= $cancellation_details;
			$response['data']['payment_details']	= $payment_details;
		
			return $response;
		}
	}
function b2b_hotel_report($condition=array(), $count=false, $offset=0, $limit=100000000000)
	{
	   
		$condition = $this->custom_db->get_custom_condition($condition);

		if(isset($condition) == true)
		{
			$offset = 0;
		}else{
			$offset = $offset;
		}

		//BT, CD, ID
		if ($count) {
			$query = 'select count(distinct(BD.app_reference)) as total_records 
					from hotel_booking_details BD
					join hotel_booking_itinerary_details AS HBID on BD.app_reference=HBID.app_reference
					join payment_option_list AS POL on BD.payment_mode=POL.payment_category_code 
					left join user as U on BD.created_by_id = U.user_id  where U.user_type='.B2B_USER.' AND BD.domain_origin='.get_domain_auth_id().' '.$condition;
			$data = $this->db->query($query)->row_array();
			return $data['total_records'];
		} else {
			$this->load->library('booking_data_formatter');
			$response['status'] = SUCCESS_STATUS;
			$response['data'] = array();
			$booking_itinerary_details	= array();
			$booking_customer_details	= array();
			$cancellation_details = array();
			$bd_query = 'select BD.* ,U.agency_name,U.first_name,U.last_name from hotel_booking_details AS BD
					     left join user U on BD.created_by_id =U.user_id 					     
						 WHERE  U.user_type='.B2B_USER.' AND BD.domain_origin='.get_domain_auth_id().' '.$condition.'
						 order by BD.created_datetime desc, BD.origin desc limit '.$offset.', '.$limit;
			$booking_details = $this->db->query($bd_query)->result_array();
			$app_reference_ids = $this->booking_data_formatter->implode_app_reference_ids($booking_details);
			if(empty($app_reference_ids) == false) {
				$id_query = 'select * from hotel_booking_itinerary_details AS ID 
							WHERE ID.app_reference IN ('.$app_reference_ids.')';
				$cd_query = 'select * from hotel_booking_pax_details AS CD 
							WHERE CD.app_reference IN ('.$app_reference_ids.')';
				$cancellation_details_query = 'select * from hotel_cancellation_details AS HCD 
							WHERE HCD.app_reference IN ('.$app_reference_ids.')';
				$booking_itinerary_details	= $this->db->query($id_query)->result_array();
				$booking_customer_details	= $this->db->query($cd_query)->result_array();
				$cancellation_details	= $this->db->query($cancellation_details_query)->result_array();
			}
			$response['data']['booking_details']			= $booking_details;
			$response['data']['booking_itinerary_details']	= $booking_itinerary_details;
			$response['data']['booking_customer_details']	= $booking_customer_details;
			$response['data']['cancellation_details']	= $cancellation_details;
			return $response;
		}
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
		$bd_query = 'select * from hotel_booking_details AS BD WHERE BD.app_reference like '.$this->db->escape($app_reference);
		if (empty($booking_source) == false) {
			$bd_query .= '	AND BD.booking_source = '.$this->db->escape($booking_source);
		}
		if (empty($booking_status) == false) {
			$bd_query .= ' AND BD.status = '.$this->db->escape($booking_status);
		}
		$id_query = 'select * from hotel_booking_itinerary_details AS ID WHERE ID.app_reference='.$this->db->escape($app_reference);
		$cd_query = 'select * from hotel_booking_pax_details AS CD WHERE CD.app_reference='.$this->db->escape($app_reference);
		$cancellation_details_query = 'select HCD.* from hotel_cancellation_details AS HCD WHERE HCD.app_reference='.$this->db->escape($app_reference);
		$response['data']['booking_details']			= $this->db->query($bd_query)->result_array();
		$response['data']['booking_itinerary_details']	= $this->db->query($id_query)->result_array();
		$response['data']['booking_customer_details']	= $this->db->query($cd_query)->result_array();
		$response['data']['cancellation_details']	= $this->db->query($cancellation_details_query)->result_array();
		if (valid_array($response['data']['booking_details']) == true and valid_array($response['data']['booking_itinerary_details']) == true and valid_array($response['data']['booking_customer_details']) == true) {
			$response['status'] = SUCCESS_STATUS;
		}
		return $response;
	}

	/**
	 * return all booking events
	 */
	function booking_events()
	{
		//BT, CD, ID
		$query = 'select * from hotel_booking_details where domain_origin='.get_domain_auth_id();
		return $this->db->query($query)->result_array();
	}
		function get_supplier_details($id)
	{
		//BT, CD, ID
		$query = 'select user.user_id,user.first_name,user.last_name,user.email,user.phone from crs_hotel_details  inner join user on crs_hotel_details.created_by=user.user_id where crs_hotel_details.id='.$id;
	//	echo $query;die;
		return $this->db->query($query)->result_array();
	}

	function get_monthly_booking_summary($condition=array())
	{
		//Balu A
		$condition = $this->custom_db->get_custom_condition($condition);
		$query = 'select count(distinct(BD.app_reference)) AS total_booking, 
				sum(HBID.total_fare+HBID.admin_markup+HBID.agent_markup) as monthly_payment, sum(HBID.admin_markup) as monthly_earning, 
				MONTH(BD.created_datetime) as month_number 
				from hotel_booking_details AS BD
				join hotel_booking_itinerary_details AS HBID on BD.app_reference=HBID.app_reference
				where (YEAR(BD.created_datetime) BETWEEN '.date('Y').' AND '.date('Y', strtotime('+1 year')).')  and BD.domain_origin='.get_domain_auth_id().' '.$condition.'
				GROUP BY YEAR(BD.created_datetime), 
				MONTH(BD.created_datetime)';
		return $this->db->query($query)->result_array();
	}

	function monthly_search_history($year_start, $year_end)
	{
		$query = 'select count(*) AS total_search, MONTH(created_datetime) as month_number from search_hotel_history where
		(YEAR(created_datetime) BETWEEN '.$year_start.' AND '.$year_end.') AND domain_origin='.get_domain_auth_id().' 
		AND search_type="'.META_ACCOMODATION_COURSE.'"
		GROUP BY YEAR(created_datetime), MONTH(created_datetime)';
		return $this->db->query($query)->result_array();
	}

	function top_search($year_start, $year_end)
	{
		$query = 'select count(*) AS total_search, CONCAT(country,"-",city) label from search_hotel_history where
		(YEAR(created_datetime) BETWEEN '.$year_start.' AND '.$year_end.') AND domain_origin='.get_domain_auth_id().' 
		AND search_type="'.META_ACCOMODATION_COURSE.'"
		GROUP BY CONCAT(country, city) order by count(*) desc, created_datetime desc limit 0, 15';
		return $this->db->query($query)->result_array();
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
	 * Balu A
	 * Update Cancellation details and Status
	 * @param $AppReference
	 * @param $cancellation_details
	 */
	public function update_cancellation_details($AppReference, $cancellation_details)
	{
		$AppReference = trim($AppReference);
		$booking_status = 'BOOKING_CANCELLED';
		//1. Add Cancellation details
		$this->update_cancellation_refund_details($AppReference, $cancellation_details);
		//2. Update Master Booking Status
		$this->custom_db->update_record('hotel_booking_details', array('status' => $booking_status), array('app_reference' => $AppReference));//later
		//3.Update Itinerary Status
		$this->custom_db->update_record('hotel_booking_itinerary_details', array('status' => $booking_status), array('app_reference' => $AppReference));//later
	}
	/**
	 * Add Cancellation details
	 * @param unknown_type $AppReference
	 * @param unknown_type $cancellation_details
	 */
	public function update_cancellation_refund_details($AppReference, $cancellation_details)
	{
		$hotel_cancellation_details = array();
		$hotel_cancellation_details['app_reference'] = 				$AppReference;
		$hotel_cancellation_details['ChangeRequestId'] = 			$cancellation_details['ChangeRequestId'];
		$hotel_cancellation_details['ChangeRequestStatus'] = 		$cancellation_details['ChangeRequestStatus'];
		$hotel_cancellation_details['status_description'] = 		$cancellation_details['StatusDescription'];
		$hotel_cancellation_details['API_RefundedAmount'] = 		@$cancellation_details['RefundedAmount'];
		$hotel_cancellation_details['API_CancellationCharge'] = 	@$cancellation_details['CancellationCharge'];
		if($cancellation_details['ChangeRequestStatus'] == 3){
			$hotel_cancellation_details['cancellation_processed_on'] =	date('Y-m-d H:i:s');
		}
		$cancel_details_exists = $this->custom_db->single_table_records('hotel_cancellation_details', '*', array('app_reference' => $AppReference));
		if($cancel_details_exists['status'] == true) {
			//Update the Data
			unset($hotel_cancellation_details['app_reference']);
			$this->custom_db->update_record('hotel_cancellation_details', $hotel_cancellation_details, array('app_reference' => $AppReference));
		} else {
			//Insert Data
			$hotel_cancellation_details['created_by_id'] = 				(int)@$this->entity_user_id;
			$hotel_cancellation_details['created_datetime'] = 			date('Y-m-d H:i:s');
			$data['cancellation_requested_on'] = date('Y-m-d H:i:s');
			$this->custom_db->insert_record('hotel_cancellation_details',$hotel_cancellation_details);
		}
	}
		function get_all_hotel_crs_listcount()
		{
			$this->db->select('*');
		$this->db->from('crs_hotel_details');
		$res = $this->db->order_by("id","DESC")->get()->result();
	//	debug($this->db->last_query());die;
		
		return $res;
		}
	function get_all_hotel_crs_list($offset=0,$limit)
	{
		//error_reporting(E_ALL);
		

		$this->db->select('*');
		$this->db->from('crs_hotel_details');
		$res = $this->db->order_by("id","DESC")->limit($limit,$offset)->get()->result();
	//	debug($this->db->last_query());die;
		
		return $res;
	}
	function inactive_hotel($hotel_id){
		$data = array(
					'status' => 'INACTIVE'
					);
		$this->db->where('id', $hotel_id);
		$this->db->update('crs_hotel_details', $data);
		
	}
	function active_hotel($hotel_id){
		$data = array(
					'status' => 'ACTIVE'
					);
		$this->db->where('id', $hotel_id);
		$this->db->update('crs_hotel_details', $data);
		
	}
	function get_hotel_data($hotel_id=0)
	{
		return $this->db->get_where('crs_hotel_details',array('id'=>$hotel_id));
	}
	function get_hotel_types_list($id='')
	{
		if($id!='')
		{
			$this->db->where('id',$id);
		}
		return $this->db->get('crs_hotel_type')->result();
	}
	public function get_country_details_hotel()
	{
		$this->db->select('*');
		$this->db->from('all_api_city_master');
		//$this->db->where('country','India');
		$this->db->order_by('country_name');
		$this->db->group_by('country_name');
		$query=$this->db->get();
		if($query->num_rows() ==''){
			return '';
		}else{
			return $query->result();
		}
	}
	function get_ammenities_list($id='')
	{
		if($id!='')
		{
			$this->db->where('id',$id);
		}
		return $this->db->get('crs_hotel_amenities')->result();
	}
	function get_allowed_list($id='')
	{
		if($id!='')
		{
			$this->db->where('allowID',$id);
		}
		return $this->db->get('hotel_allowed')->result();
	}
	function get_hotel_settings_list($general_hotel_settings_id='')
	{
		$this->db->select('*');
		$this->db->from('general_hotel_settings');
		if($general_hotel_settings_id !='')
			$this->db->where('general_hotel_settings_id', $general_hotel_settings_id);
		$query=$this->db->get();
		if($query->num_rows() ==''){
			return '';
		}else{
			return $query->result();
		}
	}
	function season_list($id)
	{
		return $this->db->get_where('seasons_details',array('hotel_details_id'=>$id));
	}
	function inactive_seasons($id="")
    	{
    		return $this->db->update('seasons_details',array('status'=>'INACTIVE'),array('seasons_details_id'=>$id));
    	}
    function active_seasons($id="")
    	{
    		return $this->db->update('seasons_details',array('status'=>'ACTIVE'),array('seasons_details_id'=>$id));
    	}
    function season_data($id)
	{
		return $this->db->get_where('seasons_details',array('seasons_details_id'=>$id));
	}
	function get_crs_room_list($hotel_id,$room_type_id='',$board_type='')
	{
		$this->db->select('r.*,t.name,b.id as boardtypeid,b.name as boardname');
		$this->db->from('crs_room_details r');
		$this->db->join('crs_room_type t','t.id=r.room_type_id');
		$this->db->join('crs_board_type b','b.id = r.board_type','left');
		$this->db->where('r.hotel_id',$hotel_id);
		if($room_type_id>0){
			$this->db->where('r.room_type_id',$room_type_id);
		}if($board_type>0){
			$this->db->where('r.board_type',$board_type);
		}
		//echo $this->db->last_query();exit;
		return $this->db->get()->result();	
	}
	function inactive_room_ammenity($ammenity_id){
		$data = array(
					'status' => 'INACTIVE'
					);
		$this->db->where('id', $ammenity_id);
		$this->db->update('crs_room_amenities', $data);
		
	}
	function active_room_ammenity($ammenity_id){
		$data = array(
					'status' => 'ACTIVE'
					);
		$this->db->where('id', $ammenity_id);
		$this->db->update('crs_room_amenities', $data);
		
	}
	function inactive_room($id)
	{
			$data = array(
					'status' => 'INACTIVE'
					);
		$this->db->where('id', $id);
		$this->db->update('crs_room_details', $data);
	}
	function active_room($id)
	{
			$data = array(
					'status' => 'ACTIVE'
					);
		$this->db->where('id', $id);
		$this->db->update('crs_room_details', $data);
	}
	function get_room_types_list($id='')
	{
		if($id!='')
		{
			$this->db->where('id',$id);
		}
		return $this->db->get('crs_room_type')->result();
	}
	function get_board_types_list($id='')
	{
		if($id!='')
		{
			$this->db->where('id',$id);
		}
		return $this->db->get('crs_board_type')->result();
	}
	function board_types_list($id='')
	{
		$this->db->where('status','ACTIVE');
		return $this->db->get('crs_board_type')->result();
	}
	function get_room_ammenities_list($id='')
	{
		if($id!='')
		{
			$this->db->where('id',$id);
		}
		return $this->db->get('crs_room_amenities')->result();
	}
	function get_crs_room($id)
	{
		return $this->db->get_where('crs_room_details',array('id'=>$id));
	}
	function room_price_list($hotel_id=0,$room_id=0)
	{
// 		ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
		return $this->db->select('crs_room_price.*,seasons_details.seasons_name,seasons_details.seasons_from_date,seasons_details.seasons_to_date')
		->from('crs_room_price')
		->join('seasons_details',"seasons_details.seasons_details_id = crs_room_price.season",'left')
		->where(array('hotel_id'=>$hotel_id,'room_id'=>$room_id))->get();

// 		$query ='select crs_room_price.*,crs_room_details.*,seasons_details.seasons_name,seasons_details.seasons_from_date,seasons_details.seasons_to_date from crs_room_price join seasons_details on seasons_details.seasons_details_id = crs_room_price.season left join crs_room_details on crs_room_price.hotel_id = crs_room_details.hotel_id 
// where (crs_room_price.hotel_id='.$hotel_id.' AND crs_room_price.room_id='.$room_id.')';

// //debug($query);exit;
// 			$data = $this->db->query($query)->row_array();
// 			return $data;
		
	}
	function inactive_room_price($id){
		$data = array(
					'status' => 'INACTIVE'
					);
		$this->db->where('id', $id);
		$this->db->update('crs_room_price', $data);
		
	}
	function active_room_price($id){
		$data = array(
					'status' => 'ACTIVE'
					);
		$this->db->where('id', $id);
		$this->db->update('crs_room_price', $data);
		
	}
	function room_price_single($id)
	{
		return $this->db->get_where('crs_room_price',array('id'=>$id));
	}
	function room_cancellation_list($hotel_id=0,$room_id=0)
	{
		return $this->db->get_where('crs_cancellation_policy',array('hotel_id'=>$hotel_id,'room_id'=>$room_id));
	}
	function inactive_room_cancellation($id){
		$data = array(
					'status' => 'INACTIVE'
					);
		$this->db->where('id', $id);
		$this->db->update('crs_cancellation_policy', $data);
// 		echo $this->db->last_query();die;
		
	}
	function active_room_cancellation($id){
		$data = array(
					'status' => 'ACTIVE'
					);
		$this->db->where('id', $id);
		$this->db->update('crs_cancellation_policy', $data);
		
	}
	function room_cancellation_data($id)
	{
		return $this->db->get_where('crs_cancellation_policy',array('id'=>$id));
	}
	function save_hotel_data($data)
	{//print_r($data); exit();
			if($this->db->insert('crs_hotel_details',$data))
			{
				return	$this->db->insert_id();
			}
			return	false;
	}
	function get_active_city_list_hotel($country_id,$status = '') {
    	//print_r($country_id); exit();
    	$country_id_new = str_replace('%20',' ', $country_id);
    //	debug($country_id);
	//debug($country_id_new);exit;
        $this->db->select('*');       
        //$this->db->where('country_name',$country_id); 
         $this->db->where('country_name',$country_id_new); 
		$this->db->order_by('city_name');
       // / $this->db->where('')      
        $query = $this->db->get('all_api_city_master');
      //echo $this->db->last_query();
        if ($query->num_rows() == '') {
            return '';
        } else {
            return $query->result();
        }
    }
    function get_hotel_images($hotel_id=0)
	{
		return $this->db->get_where('crs_hotel_images',array('hotel_id'=>$hotel_id))->result();	
	}
	function insert_hotel_image($data)
	{
			if($this->db->insert('crs_hotel_images',$data))
			{
				return	TRUE;
			}
			return	false;
	}
	function save_room_details_data($data)
	{
		if($this->db->insert('crs_room_details',$data))
		{
			return	true;
		}
		return	false;
	}
		function update_room_details_data($data,$id)
	{
		if($this->db->update('crs_room_details',$data,['id'=>$id]))
		{
			return	true;
		}
		return	false;
	}
	function save_cancellation_policy_data($data)
	{
		if($this->db->insert('crs_cancellation_policy',$data))
			{
				return	true;
			}
			return	false;
	}
	function update_cancellation_policy_data($data,$id)
	{
		if($this->db->update('crs_cancellation_policy',$data,['id'=>$id]))
			{
				return	true;
			}
			return	false;
	}
	function insert_season($data)
	{
		return $this->db->insert('seasons_details',$data);
	}
	function update_season($data,$id)
	{
		return $this->db->update('seasons_details',$data,array('seasons_details_id'=>$id));
	}
	function delete_seasons($id="")
	{
		return $this->db->delete('seasons_details',array('seasons_details_id'=>$id));
	}
	function save_room_price_data($data)
	{
		if($this->db->insert('crs_room_price',$data))
		{
			return	true;
		}
		return	false;
	}
	function update_room_price_data($data,$id)
	{
		if($this->db->update('crs_room_price',$data,['id'=>$id]))
		{
			return	true;
		}
		return	false;
	}
	function update_hotel_data($data,$id)
	{//debug($id);exit;
			if($this->db->update('crs_hotel_details',$data,['id'=>$id]))
			{
				return	$this->db->insert_id();
			}
			return	false;
	}
	function add_hotel_type_details($data)
	{
		if($this->db->insert('crs_hotel_type',$data))
		{
			return TRUE;
		}
		return false;
	}
	function inactive_hotel_type($hotel_type_id)
	{
		$data = array(
					'status' => 'INACTIVE'
					);
		$this->db->where('id', $hotel_type_id);
		$this->db->update('crs_hotel_type', $data);		
	}
	function active_hotel_type($hotel_type_id)
	{
		$data = array(
					'status' => 'ACTIVE'
					);
		$this->db->where('id', $hotel_type_id);
		$this->db->update('crs_hotel_type', $data);
		
	}
	function update_hotel_type($input, $hotel_type_id){
		if(!isset($input['status']))
			$input['status'] = "INACTIVE";
		$update_data = array(
							'name' 			=> $input['hotel_type_name'],
							'status' 					=> $input['status']					
						);	
		$this->db->where('id', $hotel_type_id);
		$this->db->update('crs_hotel_type', $update_data);
	}
	function inactive_room_types($room_type_id){
		$data = array(
					'status' => 'INACTIVE'
					);
		$this->db->where('id', $room_type_id);
		$this->db->update('crs_room_type', $data);
		
	}
	function active_room_types($room_type_id){
		$data = array(
					'status' => 'ACTIVE'
					);
		$this->db->where('id', $room_type_id);
		$this->db->update('crs_room_type', $data);
		
	}
	function inactive_board_types($board_type_id){
		$data = array(
					'status' => 'INACTIVE'
					);
		$this->db->where('id', $board_type_id);
		$this->db->update('crs_board_type', $data);
		
	}
	function active_board_types($board_type_id){
		$data = array(
					'status' => 'ACTIVE'
					);
		$this->db->where('id', $board_type_id);
		$this->db->update('crs_board_type', $data);
		
	}
	function update_room_types($input, $room_type_id)
	{
		if(!isset($input['status']))
			$input['status'] = "INACTIVE";
		$update_data = array(
							'name' 			=> $input['room_type_name'],
							'status' 					=> $input['status']					
						);	
		$this->db->where('id', $room_type_id);
		$this->db->update('crs_room_type', $update_data);		
	}
	function update_board_types($input, $board_type_id)
	{
		if(!isset($input['status']))
			$input['status'] = "INACTIVE";
		$update_data = array(
							'name' 			=> $input['board_type_name'],
							'status' 					=> $input['status']					
						);	
		$this->db->where('id', $board_type_id);
		$this->db->update('crs_board_type', $update_data);		
	}
	function add_room_type_details($data)
	{
		if($this->db->insert('crs_room_type',$data))
		{
			return TRUE;
		}
		return false;
	}
	function add_board_type_details($data)
	{
		if($this->db->insert('crs_board_type',$data))
		{
			return TRUE;
		}
		return false;
	}
	function add_hotel_ammenity_details($data)
	{
		if($this->db->insert('crs_hotel_amenities',$data))
		{
			return TRUE;
		}
		return false;
	}
	function inactive_hotel_ammenity($ammenity_id){
		$data = array(
					'status' => 'INACTIVE'
					);
		$this->db->where('id', $ammenity_id);
		$this->db->update('crs_hotel_amenities', $data);
		
	}
	function active_hotel_ammenity($ammenity_id){
		$data = array(
					'status' => 'ACTIVE'
					);
		$this->db->where('id', $ammenity_id);
		$this->db->update('crs_hotel_amenities', $data);
		
	}
	function update_hotel_ammenity($input, $ammenity_id){
		//if(!isset($input['status']))
			//$input['status'] = "INACTIVE";
		$update_data = array(
							'name' 			=> $input['hotel_ammenity_name'],
							'status' 					=> $input['status']					
						);	
	//debug($update_data);exit;
		$this->db->where('id', $ammenity_id);
		$this->db->update('crs_hotel_amenities', $update_data);
		
	}
	function add_room_ammenity_details($data)
	{
		if($this->db->insert('crs_room_amenities',$data))
		{
			return true;
		}
		return false;		
	}
	function update_room_ammenity($input, $ammenity_id){
		// if(!isset($input['status']))
		// 	$input['status'] = "INACTIVE";
		$update_data = array(
							'name' 			=> $input['hotel_ammenity_name'],
							'status' 					=> $input['status']					
						);	
		$this->db->where('id', $ammenity_id);
		$this->db->update('crs_room_amenities', $update_data);
		
	}

	public function query_run($query) {
		//$exe   = mysql_query($query);
		$exe = $this->db->query ( $query );
		if(!$exe) { die(mysql_error());}
		else{ return true;}
	}
	public function ajax_hotel_publish_1($query)
	{
		/*$exe   = mysql_query($query);
		$num   = mysql_num_rows($exe);*/
		$result = $this->db->query ( $query )->result_array ();
		$num=count($result);
		return $num;
	}
	public function ajax_hotel_details($query)
	{
		/*$exe   = mysql_query($query);
		$num   = mysql_num_rows($exe);*/
		$result = $this->db->query ( $query )->result_array ();
		//$num=count($result);
		return $result;
	}
}
