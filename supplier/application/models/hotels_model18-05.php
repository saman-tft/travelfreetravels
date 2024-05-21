<?php
/**
 * Library which has generic functions to get data
 *
 * @package    Provab Application
 * @subpackage Hotel Model
 * @author     Arjun J<arjunjgowda260389@gmail.com>
 * @version    V2
 */
Class Hotels_Model extends CI_Model
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
//----------------------sdp ------- 


    
    function b2c_crs_hotel_report($condition=array(), $count=false, $offset=0, $limit=100000000000)
	  {
	  	//debug($condition);exit;
		$condition = $this->custom_db->get_custom_condition($condition);
		

		//BT, CD, ID
		if ($count) {
			$query = 'select count(distinct(BG.ref_id)) as total_records
					from booking_global BG
					join booking_hotel AS BH on BG.ref_id=BH.id
					join hotel_details AS HD on BG.hotel_details_id= HD.hotel_details_id  
					left join user as U on BG.user_id = U.user_id 
					where HD.hotel_type_id !='."'".VILLA."'".' AND (U.user_type='.B2C_USER.' OR BG.user_type = 0)'.' '.$condition.'';

			$data = $this->db->query($query)->row_array();
			return $data['total_records'];
		} else {
			$this->load->library('booking_data_formatter');
			$response['status'] = SUCCESS_STATUS;
			$response['data'] = array();
			$booking_itinerary_details	= array();
			$booking_customer_details	= array();
			$cancellation_details = array();


			

			
			$bd_query = 'select BG.*,BH.*,HD.city_details_id as hotel_location,U.email,U.first_name,U.last_name,BG.ref_id as app_reference from booking_global AS BG 
			             join booking_hotel AS BH on BG.ref_id=BH.id 
			             join hotel_details AS HD on BG.hotel_details_id= HD.hotel_details_id  
					     left join user U on BG.user_id =U.user_id 					     
						 WHERE HD.hotel_type_id !='."'".VILLA."'".' AND HD.hotel_added_by_mgmt='."'".$this->entity_user_id."'".'  AND (U.user_type='.B2C_USER.' OR BG.user_id = 0)'.$condition.'						 
						 order by BH.book_date desc, BH.id desc limit '.$offset.', '.$limit.'';

			$booking_details = $this->db->query($bd_query)->result_array();
			$app_reference_ids = $this->booking_data_formatter->implode_app_reference_ids($booking_details);
			/*if(empty($app_reference_ids) == false) {
				$id_query = 'select * from hotel_booking_itinerary_details AS ID 
							WHERE ID.app_reference IN ('.$app_reference_ids.')';
				$cd_query = 'select * from hotel_booking_pax_details AS CD 
							WHERE CD.app_reference IN ('.$app_reference_ids.')';
				$cancellation_details_query = 'select * from hotel_cancellation_details AS HCD 
							WHERE HCD.app_reference IN ('.$app_reference_ids.')';
				$booking_itinerary_details	= $this->db->query($id_query)->result_array();
				$booking_customer_details	= $this->db->query($cd_query)->result_array();
				$cancellation_details	= $this->db->query($cancellation_details_query)->result_array();
			}*/
			$response['data']['booking_details']			= $booking_details;
			$response['data']['booking_itinerary_details']	= $booking_itinerary_details;
			$response['data']['booking_customer_details']	= $booking_customer_details;
			$response['data']['cancellation_details']	= $cancellation_details;

			// debug($response['data']['booking_details']);exit();
			return $response;
		}
	}

	function b2b_crs_hotel_report($condition=array(), $count=false, $offset=0, $limit=100000000000)
	{
		// error_reporting(E_ALL);

		// debug( $count);exit("sa");
		$condition = $this->custom_db->get_custom_condition($condition);

		if(isset($condition) == true)
		{
			$offset = 0;
		}else{
			$offset = $offset;
		}

		//BT, CD, ID
		if ($count) {
		$query = 'select count(distinct(BG.ref_id)) as total_records
					from booking_global BG
					join booking_hotel AS BH on BG.ref_id=BH.id
					left join user as U on BG.user_id = U.user_id 
					where (U.user_type='.B2B_USER.')'.' '.$condition.'';
			$data = $this->db->query($query)->row_array();
			return $data['total_records'];
		} else {
			$this->load->library('booking_data_formatter');
			$response['status'] = SUCCESS_STATUS;
			$response['data'] = array();
			$booking_itinerary_details	= array();
			$booking_customer_details	= array();
			$cancellation_details = array();
		    $bd_query = 'select BG.*,BH.*,HD.city_details_id as hotel_location,U.email as user_name,U.agency_name,U.first_name,U.last_name,BG.ref_id as app_reference from booking_global AS BG 
			             join booking_hotel AS BH on BG.ref_id=BH.id 
			             join hotel_details AS HD on BG.hotel_details_id= HD.hotel_details_id  
					     left join user U on BG.user_id =U.user_id 					     
						 WHERE  (U.user_type='.B2B_USER.')'.$condition.'						 
						 order by BH.book_date desc, BH.id desc limit '.$offset.', '.$limit.'';
			//echo $bd_query;exit;

			$booking_details = $this->db->query($bd_query)->result_array();
			$app_reference_ids = $this->booking_data_formatter->implode_app_reference_ids($booking_details);
			/*if(empty($app_reference_ids) == false) {
				$id_query = 'select * from hotel_booking_itinerary_details AS ID 
							WHERE ID.app_reference IN ('.$app_reference_ids.')';
				$cd_query = 'select * from hotel_booking_pax_details AS CD 
							WHERE CD.app_reference IN ('.$app_reference_ids.')';
				$cancellation_details_query = 'select * from hotel_cancellation_details AS HCD 
							WHERE HCD.app_reference IN ('.$app_reference_ids.')';
				$booking_itinerary_details	= $this->db->query($id_query)->result_array();
				$booking_customer_details	= $this->db->query($cd_query)->result_array();
				$cancellation_details	= $this->db->query($cancellation_details_query)->result_array();
			}*/
			$response['data']['booking_details']			= $booking_details;
			$response['data']['booking_itinerary_details']	= $booking_itinerary_details;
			$response['data']['booking_customer_details']	= $booking_customer_details;
			$response['data']['cancellation_details']	= $cancellation_details;
			//debug($response);exit;
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
	function get_booking_crs_details($app_reference, $booking_source, $booking_status='')
	{
		$response['status'] = FAILURE_STATUS;
		$response['data'] = array();
		$bd_query = 'select *,ref_id as app_reference,HD.hotel_name,HD.hotel_address from booking_global AS BG JOIN hotel_details AS HD ON HD.hotel_details_id = BG.hotel_details_id WHERE BG.ref_id ='.$this->db->escape($app_reference);
		if (empty($booking_status) == false) {
			$bd_query .= ' WHERE BG.booking_status = '.$this->db->escape($booking_status);
		}

		$id_query = 'select * from booking_hotel AS BH WHERE BH.id='.$this->db->escape($app_reference);
		
		$cancellation_details_query = 'select HCD.* from hotel_cancellation_details_crs AS HCD WHERE HCD.hotel_cancellation_id='.$this->db->escape($app_reference);

		$response['data']['booking_details'] = $this->db->query($bd_query)->result_array();
		$response['data']['booking_itinerary_details']	= $this->db->query($id_query)->result_array();
        $response['data']['cancellation_details']	= $this->db->query($cancellation_details_query)->result_array();
		if (valid_array($response['data']['booking_details']) == true and valid_array($response['data']['booking_itinerary_details']) == true) {
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

	function get_monthly_booking_summary($condition=array())
	{
		//Jaganath
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
	 * Jaganath
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

	/*Hotel- CRS -Start*/
	//Hotel -CRS
	function get_ammenities_list_add($ammenities_id=''){
		$this->db->select('*');
		$this->db->from('hotel_amenities');
		$this->db->where('status', 'ACTIVE');
		if($ammenities_id !='')
			$this->db->where('hotel_amenities_id', $ammenities_id);

		$this->db->order_by("hotel_amenities_id","desc");
		$query=$this->db->get();
		if($query->num_rows() ==''){
			return '';
		}else{
			return $query->result();
		}
	}
	function get_ammenities_list($ammenities_id=''){
		$this->db->select('*');
		$this->db->from('hotel_amenities');
		if($ammenities_id !='')
			$this->db->where('hotel_amenities_id', $ammenities_id);

		$this->db->order_by("hotel_amenities_id","desc");
		$query=$this->db->get();
		if($query->num_rows() ==''){
			return '';
		}else{
			return $query->result();
		}
	}

    public function get_currency_list()
    {
        $this->db->select('*');
        //$this->db->where('name',$tours_continent);
        $query = $this->db->get('currency_converter');
        if ( $query->num_rows > 0 ) {
            return $query->result_array();
        }else{
            return array();
        }
    }


	function add_hotel_ammenity_details($input,$iconImage){
		if(!isset($input['status']))
			$input['status'] = "INACTIVE";
		$insert_data = array(
							'amenities_name' 		=> $input['hotel_ammenity_name'],
							'status' 				=> $input['status']
							// 'icon_image'			=> $icon_image		
						);			
		$this->db->where("amenities_name",$input['hotel_ammenity_name']);
		$qur = $this->db->get ("hotel_amenities");
		$count=$qur->num_rows();

		if($count==0){

		$this->db->insert('hotel_amenities',$insert_data);
		$id = $this->db->insert_id();
		return true;
		}else{

			return false;
		}
		
	}

	function inactive_hotel_ammenity($ammenity_id){
		$data = array(
					'status' => 'INACTIVE'
					);
		$this->db->where('hotel_amenities_id', $ammenity_id);
		$this->db->update('hotel_amenities', $data);
		
	}
	
	function active_hotel_ammenity($ammenity_id){
		$data = array(
					'status' => 'ACTIVE'
					);
		$this->db->where('hotel_amenities_id', $ammenity_id);
		$this->db->update('hotel_amenities', $data);
		
	}

	function update_hotel_ammenity($input, $ammenity_id){
		if(!isset($input['status']))
			$input['status'] = "INACTIVE";
		$update_data = array(
							'amenities_name' 			=> $input['hotel_ammenity_name'],
							'status' 					=> $input['status']					
						);	
		$this->db->where('hotel_amenities_id', $ammenity_id);
		$this->db->update('hotel_amenities', $update_data);
		
	}
	
	function delete_hotel_ammenity($ammenity_id){
		$this->db->where('hotel_amenities_id', $ammenity_id);
		$this->db->delete('hotel_amenities'); 
		
	}

	//room ammenities
function get_room_ammenities_list($ammenities_id=''){
		$this->db->select('*');
		$this->db->from('room_amenities');
		if($ammenities_id !='')
			$this->db->where('hotel_amenities_id', $ammenities_id);
		

		$this->db->order_by("hotel_amenities_id","desc");
		$query=$this->db->get();
		if($query->num_rows() ==''){
			return '';
		}else{
			return $query->result();
		}
	}




	function get_active_room_ammenities_list($ammenities_id=''){
		$this->db->select('*');
		$this->db->from('room_amenities');
		if($ammenities_id !='')
			$this->db->where('hotel_amenities_id', $ammenities_id);
		$this->db->where('status', 'ACTIVE');

		$this->db->order_by("hotel_amenities_id","desc");
		$query=$this->db->get();
		if($query->num_rows() ==''){
			return '';
		}else{
			return $query->result();
		}
	}


	function add_room_ammenity_details($input){
		if(!isset($input['status']))
			$input['status'] = "INACTIVE";
		$insert_data = array(
							'amenities_name' 		=> $input['hotel_ammenity_name'],
							'status' 				=> $input['status']					
						);			
		$this->db->where("amenities_name",$input['hotel_ammenity_name']);
		$qur = $this->db->get ("room_amenities");
		$count=$qur->num_rows();

		if($count==0){	

		$this->db->insert('room_amenities',$insert_data);
		$id = $this->db->insert_id();
		return true;
	}else{

		return false;
	}
		
	}

	function inactive_room_ammenity($ammenity_id){
		$data = array(
					'status' => 'INACTIVE'
					);
		$this->db->where('hotel_amenities_id', $ammenity_id);
		$this->db->update('room_amenities', $data);
		
	}
	
	function active_room_ammenity($ammenity_id){
		$data = array(
					'status' => 'ACTIVE'
					);
		$this->db->where('hotel_amenities_id', $ammenity_id);
		$this->db->update('room_amenities', $data);
		
	}

	function update_room_ammenity($input, $ammenity_id){
		if(!isset($input['status']))
			$input['status'] = "INACTIVE";
		$update_data = array(
							'amenities_name' 			=> $input['hotel_ammenity_name'],
							'status' 					=> $input['status']					
						);	
		$this->db->where('hotel_amenities_id', $ammenity_id);
		$this->db->update('room_amenities', $update_data);
		
	}
	
	function delete_room_ammenity($ammenity_id){
		$this->db->where('hotel_amenities_id', $ammenity_id);
		$this->db->delete('room_amenities'); 
		
	}
	 function get_hotel_types_list_add($hotel_type_id = ''){
		$this->db->select('*');
		$this->db->from('hotel_type');
    	if($hotel_type_id !=''){
			$this->db->where('hotel_type_id', $hotel_type_id);
    	}
    	$this->db->where('status', 'ACTIVE');
    	$this->db->order_by("hotel_type_id","desc");

		$query=$this->db->get();
		//echo $this->db->last_query();exit;
		if($query->num_rows() ==''){
			return '';
		}else{
			return $query->result();
		}
	}
	 function get_hotel_types_list($hotel_type_id = ''){
		$this->db->select('*');
		$this->db->from('hotel_type');
    	if($hotel_type_id !=''){
			$this->db->where('hotel_type_id', $hotel_type_id);
    	}
    	$this->db->order_by("hotel_type_id","desc");

		$query=$this->db->get();
		//echo $this->db->last_query();exit;
		if($query->num_rows() ==''){
			return '';
		}else{
			return $query->result();
		}
	}
	
	function add_hotel_type_details($input){
		if(!isset($input['status']))
			$input['status'] = "INACTIVE";
		$insert_data = array(
							'hotel_type_name' 		=> $input['hotel_type_name'],
							'status' 				=> $input['status']					
						);
		/*if($input['supplier_rights'] == 1 ){
		 $insert_data['hotel_type_added_by_supplier'] =$this->session->userdata('lgm_supplier_admin_id') ;
		} else {*/
		 //$insert_data['hotel_type_added_by_mgmt'] = $this->session->userdata('provab_admin_id') ;

		$insert_data['hotel_type_added_by_mgmt'] = '' ;
		//}				
		/*$this->db->insert('hotel_type',$insert_data);
		$id = $this->db->insert_id();*/
		//$this->General_Model->insert_log('10','add_hotel_type',json_encode($input),'adding the hotel Type','hotel_type','hotel_type_id',$id);


		$this->db->where("hotel_type_name",$input['hotel_type_name']);
		$qur = $this->db->get ("hotel_type");
		$count=$qur->num_rows();
		//echo $this->db->last_query();
		//echo $count;die;
		if($count == 0){
		$this->db->insert('hotel_type',$insert_data);
		$id = $this->db->insert_id();
		return true;
		}else{

			return false;
		}
		
	}
	
	function inactive_hotel_type($hotel_type_id){
		$data = array(
					'status' => 'INACTIVE'
					);
		$this->db->where('hotel_type_id', $hotel_type_id);
		$this->db->update('hotel_type', $data);
		
	}
	
	function active_hotel_type($hotel_type_id){
		$data = array(
					'status' => 'ACTIVE'
					);
		$this->db->where('hotel_type_id', $hotel_type_id);
		$this->db->update('hotel_type', $data);
		
	}
	
	function update_hotel_type($input, $hotel_type_id){
		if(!isset($input['status']))
			$input['status'] = "INACTIVE";
		$update_data = array(
							'hotel_type_name' 			=> $input['hotel_type_name'],
							'status' 					=> $input['status']					
						);	
		/*if($input['supplier_rights'] == 1 ){
		 $update_data['hotel_type_added_by_supplier'] =$this->session->userdata('lgm_supplier_admin_id') ;
		} else {
		 $update_data['hotel_type_added_by_mgmt'] = $this->session->userdata('provab_admin_id') ;
		}*/	
		$update_data['hotel_type_added_by_mgmt'] = '' ;
		$this->db->where('hotel_type_id', $hotel_type_id);
		$this->db->update('hotel_type', $update_data);
		
	}
	
	function delete_hotel_type($hotel_type_id){
		$this->db->where('hotel_type_id', $hotel_type_id);
		$this->db->delete('hotel_type'); 
		
	}

	//Hotel Type End

	function get_all_hotel_crs_list($hotel_id='', $hotel='')
	{		
		$this->db->select('h.*');
		//$this->db->select('h.*,destination_name');
		$this->db->from('hotel_details h');
		//$this->db->join('destination_details ds','ds.destination_id = h.country_id');
		//$this->db->join('country_details c','h.country_id = c.country_id');
		//$this->db->join('city_details d','h.city_details_id = d.city_details_id');
		
		/*if($hotel['supplier_rights'] == 1) {
			$this->db->where('h.hotel_added_by_supplier', $hotel['admin_id'] );	
			}*/
		$this->db->where('h.hotel_added_by_mgmt', $this->entity_user_id);
		if($hotel_id !='')
			$this->db->where('hotel_details_id', $hotel_id);

		$this->db->order_by("hotel_details_id","desc");
		$query=$this->db->get();
		// echo $this->db->last_query();exit;
		if($query->num_rows() ==''){
			return '';
		}else{
			$data['all_hotels'] =  $query->result();
			// echo $this->db->last_query();exit;
		}
		// echo"<pre>";print_r($data['all_hotels']);exit;
		if($data['all_hotels'] != ''){
		   for($i=0; $i< count($data['all_hotels']); $i++) {
		   	// /echo $data['all_hotels'][$i]->hotel_details_id;exit;
			   //$data['offer_list'][$i] 	= $this->Seasonaloffers_Model->get_offers_list("","",$data['all_hotels'][$i]->hotel_details_id);
		   }
		}
		return $data;
	}

	function show_hotel_ammenities($ammenities){
		$amenities='';
		$this->db->select('*');
		if($ammenities!='')
			$this->db->where('hotel_amenities_id in ('.$ammenities.')');
		$this->db->from('hotel_amenities');
		$amenity = $this->db->get();
		if($amenity->num_rows() ==''){ 
		} else {
			$res = $amenity->result();
			for($a=0; $a<count($res) ; $a++){
				$amenities .= $res[$a]->amenities_name.', ';
			} 
		}
		if($amenities!=''){
			$amenities = rtrim($amenities,", ");
		}
		return $amenities;
	}

	function inactive_hotel($hotel_id){
		$data = array(
					'status' => 'INACTIVE'
					);
		$this->db->where('hotel_details_id', $hotel_id);
		$this->db->update('hotel_details', $data);
		
	}
	
	function active_hotel($hotel_id){
		$data = array(
					'status' => 'ACTIVE'
					);
		$this->db->where('hotel_details_id', $hotel_id);
		$this->db->update('hotel_details', $data);
		
	}

	//Hotel-CRS END //

	public function getHomePageSettings() {
		//$general_settings =  $this->Settings_Model->get_settings_list(); 
	    // exit("12");
	    if($this->session->userdata('lgm_supplier_admin_logged_in') == "Logged_In") {
			$data['supplier_rights'] = 1 ;
			$data['supplier'] 		= $this->Supplierdashboard_Model->get_supplier_details();
	    }else {
			$data['supplier_rights'] = 0 ;	
	    }

	    // echo $data['supplier_rights'];exit(1);

	    if($data['supplier_rights'] == 1 ) {
			$data['admin_id'] =$this->session->userdata('lgm_supplier_admin_id') ;
		} else {
			$data['admin_id'] = $this->session->userdata('provab_admin_id') ;
		}		
		
			// exit("12");
		//$data['sidebar'] 	    = "sidebar-collapsed";
		$data['sidebar'] 	    = "";
		
		return $data;
	}

	//Adding Of Hotel
		function add_hotel($input, $thumb_image, $hotel_image,$supplier_id){
		 
        //$input['hotel_code'] = 'NASHO';	
        $input['expire_email_block'] = 'ACTIVE';	 
		$insert_data = $this->hotel_form_data($input,$thumb_image,$hotel_image,$supplier_id);
		/*if($input['supplier_rights'] == 1 ){
		 $insert_data['hotel_added_by_supplier'] =$this->session->userdata('lgm_supplier_admin_id') ;
		 $insert_data['added_by_type'] = "Supplier";
		} else {
		 $insert_data['hotel_added_by_mgmt'] = $this->session->userdata('provab_admin_id') ;
		 $insert_data['added_by_type'] = "Admin";
		}*/


		if ($this->session->userdata('lgm_supplier_admin_logged_in') == 'Logged_In') {
		$insert_data['hotel_added_by_supplier'] = $this->session->userdata('lgm_supplier_admin_id');
		$insert_data['added_by_type'] = "Supplier";
	}else{

		$insert_data['hotel_added_by_mgmt'] = $this->entity_user_id;
		
		$insert_data['added_by_type'] = "Supplier";
	}
		
		// debug($insert_data);exit();	
		
		//echo "<pre/>";print_r( $insert_data);exit;	 
		$this->db->insert('hotel_details',$insert_data);
		// $this->db->last_query();exit();
		$id = $this->db->insert_id();
		$this->update_wizard_status($id,'step1');
		if($insert_data['cancellation_status'] == 1){
			$cancellation_array = array('hotel_details_id'		   => $id,
										'cancellation_description' => $input['cancellation_dexcription'],
										'cancellaion_contition'	   => 1);
			$this->db->insert('hotel_cancellation',$cancellation_array);
			$cancellation_id = $this->db->insert_id();
			for ($c=0; $c < count($input['cancellation_from']); $c++) { 
				$array = array('hotel_cancellation_id' 				=> $cancellation_id,
							   'hotel_details_id'	   				=> $id,
							   //'from_day'			   				=> $input['cancellation_from'][$c],
							   //'cancellation_percentage'		    => $input['cancellation_percentage'][$c],
							   //'cancellation_nightcharge'		    => $input['cancellation_nightcharge'][$c]
							   );
				// echo "<pre/>";print_r($array);exit;
				$this->db->insert('hotel_cancellation_details',$array);
			}
		}
		
		return $id;
	}

	function hotel_form_data($post,$thumb_image,$hotel_image)
	{   
		//echo '<pre>sanjay'; print_r($post); exit();

			$orderdate = explode('/', $post['exclude_checkout_date']);
			
				$month = $orderdate[1];
				$day   = $orderdate[0];
				$year  = $orderdate[2];
		 	$expire_date = $month.'/'.$day.'/'.$year;
		 	//echo '<pre>'; print_r(); exit();
			if(!isset($post['status']))
			$post['status'] = "INACTIVE";

			//echo "<pre/>";print_r($post);exit;
		/*if(!isset($post['top_deals'])){
				$post['top_deals'] = 0;
				
			}else{
				//echo $event_title;exit;
				$post['top_deals'] = 1;
			}*/

			$hotel_ammenities='';
			foreach($post['ammenities'] as $ammenities){
				$hotel_ammenities .= $ammenities.',';
			}
			
			

			/*if($post['location_name'] != ''){
				$location_info = $post['location_name'];				
			}
			else{
				if($post['location_info'] != "select")
				  $location_info = $post['location_info'];
				else					
				  $location_info = "";	
			}*/
			//echo $hotel_image;exit;
			if($hotel_image == NULL){

				$hotel_image = "";
			}
		return $data = array(//'country_id' 				=> $post['countries_list'],
								 'hotel_type_id' 		=> $post['hotel_type'],
								 'hotel_name' 			=> ucwords($post['hotel_name']),
								 'hotel_code' 			=> 'SMR',//$post['hotel_code'],
								 'star_rating' 			=> $post['star_rating'],
								 'hotel_info' 			=> $post['hotel_info'],
								 'thumb_image'			=> $thumb_image,
								 'hotel_images'			=> $hotel_image,
								 //'hotel_image_url'  	=> $post['image_url'],
								 'location_info'		=> $post['location_info'], 
								 'hotel_amenities'		=> rtrim($hotel_ammenities, ","),
								 //'transfer_type'		=> rtrim($transfer_type, ","),
								 'hotel_address'		=> $post['hotel_address'],
								 'postal_code'			=> $post['postal_code'],
								 'phone_number'			=> $post['phone_number'],
								 'email'				=> $post['email'],
								 'latitude'				=> $post['latitude'],
								 'longitude'			=> $post['longitude'],
								 'contract_expire_date' => date('Y-m-d',strtotime($expire_date)),
								 //'min_stay_day'     => $post['min_stay_day'],
								 //'max_stay_day'     => $post['max_stay_day'],
								 //'position'			=> $post['position'], 
								 'status' 				=> $post['status'],
								 //'child_group_a'   		=> $post['child_group_a'],
								 //'child_group_b'   		=> $post['child_group_b'],
								 //'child_group_c' 		=> $post['child_group_c'],
								 //'child_group_d'	 	=> $post['child_group_d'],
								 //'child_group_e' 		=> $post['child_group_e'],
								// 'exc_checkin_time' 	=> $post['exclude_checkin_time'],
								// 'exc_checkintime_price' 	=> $post['exclude_checkintime_price'],
								// 'exc_checkout_time' 	=> $post['exclude_checkout_time'],
								// 'exc_checkintime_price' 	=> $post['exclude_checkouttime_price'],
								// 'hotel_complimentary'		=> $post['hotel_complimentary'],
								 'city_details_id' 			=> $post['city_name'],
								 'country_id' 				=> $post['country'],
								// 'top_deals' 				=> $post['top_deals'],
								 //'distance'					=> $post['distance'],
								 'expire_email_block' 		=> $post['expire_email_block'],
								 // 'wizard_status'				=> ,
								 'cancellation_status'		=> $post['hotel_cancellation']
						);	

			
	}

	function update_hotel($input, $hotel_id, $thumb_image, $hotel_image){ //echo '<pre>'; print_r($input); exit();
		if(!isset($input['status']))
			$input['status'] = "INACTIVE";

		$update_data =  $this->hotel_form_data($input,$thumb_image,$hotel_image);
		//debug($input); exit;
		 if($input['hotel_cancellation'] == 1){
		 	//echo "string";exit;
		 	$this->db->where('hotel_details_id',$hotel_id);
		 	$this->db->delete('hotel_cancellation_details_crs');
		 	
		 	$desc = $input['cancellation_dexcription'];
		 	$this->db->select('*');
		 	$this->db->from('hotel_cancellation');
		 	$this->db->where('hotel_details_id',$hotel_id);
		 	$hotel_canc = $this->db->get()->row();
		 	if(!empty($hotel_canc)){
		 		$this->db->where('hotel_details_id',$hotel_id);
			 	$this->db->set('cancellation_description',$desc);
			 	$this->db->update('hotel_cancellation');
			 }else{
			 	$h_can = array('cancellation_description' => $desc,
			 				   'hotel_details_id'        => $hotel_id);
			 	$this->db->insert('hotel_cancellation',$h_can);
			 	$ids = $this->db->insert_id();
			 	$input['hotel_cancellation_id'] = $ids;
			 }
		 	
		 	//echo $this->db->last_query();exit;
		 	for ($i=0; $i < count($input['cancellation_from']); $i++) { 
		 		$array = array('hotel_cancellation_id' 				=> $input['hotel_cancellation_id'],
							   'hotel_details_id'	   				=> $hotel_id,
							   //'from_day'			   				=> $input['cancellation_from'][$i],
							   //'cancellation_percentage'		    => $input['cancellation_percentage'][$i],
							   //'cancellation_nightcharge'		    => $input['cancellation_nightcharge'][$i]
							   );
				
				$this->db->insert('hotel_cancellation_details',$array);
		 	}
		 	
		 }
		$this->db->where('hotel_details_id', $hotel_id);						
		$this->db->update('hotel_details',$update_data);
		
	}

	function update_hotel_contact_details($post,$hotel_id){
		$update_data=array(				
				'secondary_mail_id'	=>$post['email'],
				'secondary_phone_no'=>$post['phone_number']
		);
		$this->db->where('hotel_details_id',$hotel_id);						
		$this->db->update('hotel_contact_details',$update_data);
	
	}

	function get_hotel_crs_list($hotel_id='', $hotel){	

		$this->db->select('h.*');
		//$this->db->select('h.*,destination_name');
		$this->db->from('hotel_details h');
		//$this->db->join('destination_details ds','ds.destination_id = h.country_id');

		//$this->db->join('country_details c','h.country_id = c.country_id');
		//$this->db->join('city_details d','h.city_details_id = d.city_details_id');
		if($hotel['supplier_rights'] == 1) {			
			$this->db->where('h.hotel_added_by_supplier', $hotel['admin_id'] );	
			}
		if($hotel_id !='')
			$this->db->where('hotel_details_id', $hotel_id);
		$this->db->order_by('hotel_name','asc'); 		
		$query=$this->db->get();
		//echo $this->db->last_query($query);		
		if($query->num_rows() ==''){
			return '';
		}else{
			return $query->result();
		}		
	}

	function get_hotel_settings_list($general_hotel_settings_id=''){
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

		public function get_country_details_hotel()
	{
		$this->db->select('*');
		$this->db->from('api_hotel_city_list');
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

	 function get_active_city_list_hotel($country_id,$status = '') {
    	//print_r($country_id); exit();
        $this->db->select('*');       
        $this->db->where('country_name',$country_id);  
       // / $this->db->where('')      
        $query = $this->db->get('api_hotel_city_list');
      //echo $this->db->last_query();
        if ($query->num_rows() == '') {
            return '';
        } else {
            return $query->result();
        }
    }

     function getCancellationDetails($hotel_id){
     	$this->db->select('*');
     	$this->db->from('hotel_cancellation as hc');
     	$this->db->join('hotel_cancellation_details as hcd','hcd.hotel_details_id = hc.hotel_details_id');
     	$this->db->where('hc.hotel_details_id',$hotel_id);
     	$query = $this->db->get();
     	if($query->num_rows > 0){
     		return $query->result();
     	}else{
     		return false;
     	}
     }

     function getCancellationDetailsDescription($hotel_id){
     	$this->db->select('*');
     	$this->db->from('hotel_cancellation as hc');
     	$this->db->join('hotel_cancellation as hcd','hcd.hotel_details_id = hc.hotel_details_id');
     	$this->db->where('hc.hotel_details_id',$hotel_id);
     	$query = $this->db->get();
     	if($query->num_rows > 0){
     		return $query->result();
     	}else{
     		return false;
     	}
     }

     function delete_hotel($hotel_id){
		$this->db->where('hotel_details_id', $hotel_id);
		$this->db->delete('hotel_details');
		
	}

	function manage_child_group($data, $hotel_id){
		$this->update_wizard_status($hotel_id,'step2');
	  $this->db->where('hotel_details_id', $hotel_id);
	  $this->db->update('hotel_details', $data); 
  }
   
	//End Of Adding Hotel
  
  //Hotel Room Types
  function get_hotel_room_types_list($hotel_id = '',$input,$status = ""){
		
		$this->db->select('hrt.*,h.hotel_name');
		$this->db->from('hotel_room_type hrt');		
		$this->db->join('hotel_details h', 'h.hotel_details_id = hrt.hotel_details_id');
		if($status != "")
		    $this->db->where('hrt.status',$status);	 
		if($hotel_id != '')
			$this->db->where('h.hotel_details_id', $hotel_id);
	/*		
		if($input['supplier_rights'] == 1 ){
			$this->db->where('hrt.supplier_details_id', $this->session->userdata('lgm_supplier_admin_id'));
		}
	*/
		$query=$this->db->get();
		// echo $this->db->last_query();exit;
		if($query->num_rows() ==''){
			return '';
		}else{
			return $query->result();
		}
	}

	function add_room_type_details($input, $hotel_id = "",$image_path = ""){	
	 
	  for($loop = 0 ;$loop < sizeof($mealtype_name);$loop++){
	  	echo $mealtype_name[$loop];
	  }
		if(!isset($input['status']))
			$input['status'] = "INACTIVE";

		// if(!isset($input['extra_bed']))
		// $input['extra_bed_count'] = "0";



		if($input['supplier_rights'] == 1 ){
            $input['supplier_details_id'] =$this->session->userdata('lgm_supplier_admin_id') ;
        } else {
            $input['supplier_details_id'] = $this->entity_user_id ;
            //$return_data = $this->get_table_data('supplier_details_id', 'supplier_details', 'admin_relation_id', $input['supplier_details_id']);
            /*$input['supplier_details_id'] = "";
            if ($return_data != "") {
             foreach ($return_data as $row) {
                $input['supplier_details_id'] = $row->supplier_details_id;
             }
            }*/
        }


		if(!isset($input['extra_bed'])){
			$input['extra_bed'] = "Available";
		}

		if(!isset($input['extra_bed_count'])){
			$input['extra_bed_count'] = 0;
		}

		if($hotel_id != "")
			$input['hotel_details_id'] = $hotel_id;

		$insert_data = array(
							'hotel_details_id' 		=> $input['hotel_details_id'],
							'room_type_name' 		=> $input['room_type_name'],
							'room_description' 		=> $input['room_description'],
							'adult' 				=> $input['adult'],
							'child' 				=> $input['child'],
							'no_of_rooms'			=> $input['rooms_tot_units'],
							'max_pax' 				=> $input['max_pax'],
							'extra_bed' 			=> $input['extra_bed'],
							'extra_bed_count' 		=> $input['extra_bed_count'],
							'supplier_details_id' 	=> $input['supplier_details_id'],
							'room_uploaded_image'   => $image_path,
							//'room_url_image'		=> $input['image_url'],
							'status' 				=> 'ACTIVE',//$input['status']					
						);
		
		$id = "";							
		$this->db->insert('hotel_room_type',$insert_data);
		$id = $this->db->insert_id();
		

		$room_amenities='';

		foreach($input['sel_ammenities'] as $ammenities){
				$room_amenities .= $ammenities.',';
		}

		/*if($input['chk_breakfast'] != ''){
			$input['chk_brekfast'] = 1;
		}else {
			$input['chk_brekfast'] = 0;
		}

		if($input['chk_lunch'] != ''){
			$input['chk_lunch'] = 1;
		}else{
			$input['chk_lunch'] = 0;
		}

		if($input['chk_dinner'] !=''){
			$input['chk_dinner'] = 1;
		} else {
		   $input['chk_dinner'] = 0;
		}	    	  

		if($input['chk_half'] != ''){
			$input['chk_half'] = 1;
		}else{
			$input['chk_half'] = 0;
		}
		if($input['chk_full'] !=''){
			$input['chk_full'] = 1;
		} else {
		   $input['chk_full'] = 0;
		}	    	  */
	  
		$update_data = array(								 
								 'hotel_room_type_id'       =>$id,
								 'room_name' 				=> 'NA',
								 'room_info' 				=> $input['room_info'],
								 'cancellation_policy' 		=> $input['cancellation_policy'],
								 'hotel_details_id' 		=> $input['hotel_details_id'],
								 //'checkin_time' 			=> $input['checkin_time'],
								 //'checkout_time' 			=> $input['checkout_time'],

								 /*'break_fast_price' 		=> $input['break_fast_p'],
								 'breakfast_price_flag'		=> $input['chk_brekfast'],
								 'dinner_price'				=> $input['dinner_p'],
								 'dinner_price_flag'		=> $input['chk_dinner'], 
								 'lunch_price'				=> $input['lunch_p'], 
								 'lucnh_price_flag'			=> $input['chk_lunch'],
								 'full_board_price'			=> $input['full_board_p'],
								 'full_board_flag'		    => $input['chk_full'], 
								 'half_board_price'			=> $input['half_board_p'], 
								 'half_board_flag'			=> $input['chk_half'],*/
								 'room_amenities'			=> rtrim($room_amenities, ","),
								 'status' 					=> 'ACTIVE',//$input['status'],
								 'creation_date'			=> (date('Y-m-d H:i:s'))
								 
							);	
		$this->db->insert('hotel_room_details',$update_data);
		// debug($update_data);exit();
		$id1 = $this->db->insert_id();
		
		/*if($input['oth_meals_flag'] != ''){	
						$others = '1';	   						 			 
	     for($loop = 0; $loop < sizeof($input['mealtype_name']);$loop++){
			   $data = array('room_details_id'=>$id,
			                 'meal_type_name' => $input['mealtype_name'][$loop],
			                 'oth_meals_flag' => $others,
			                 'mealtype_price' => $input['mealtype_price'][$loop]);			
			   
			  $this->db->insert('additional_meal_plan', $data);
		 }
		}  */
	    	  

		/*
	     if(count($input['oth_meals_flag']) > 0){
			$others = "";
			for($oth =0; $oth < count($input['oth_meals_flag']); $oth++){
			
				if($input['oth_meals_flag'][$oth] == 'on'){
					$others = 1;
				}else{
					$others = 0;
				}
			 
			   $data = array('room_details_id'=>$id,
			                 'meal_type_name' => $input['mealtype_name'][$oth],
			                 'oth_meals_flag' => $others,
			                 'mealtype_price' => $input['mealtype_price'][$oth]);
			  $this->db->insert('additional_meal_plan', $data);
			}
		  }
		  */	
		

		$update_data = array(
			 'hotel_room_details_id' 		=> $id,
   		 	 'hotel_details_id' 		=> $input['hotel_details_id'],
   		 	 'no_of_room' 				=> $input['rooms_tot_units'],
   		 	 'no_of_room_available'		=> $input['rooms_tot_units'],
   		 	 'no_of_room_booked'		=> '0',
			 'status' 					=> 'ACTIVE',
			 'creation_date'				=> (date('Y-m-d H:i:s'))
			);	
			// echo "<pre>sanjay"; print_r($update_data); exit();
			$this->db->insert('hotel_room_count_info',$update_data);
			$id2 = $this->db->insert_id();
			

			
		return 1;
		
	}

	function inactive_room_type($room_type_id){
		$data = array(
					'status' => 'INACTIVE'
					);
		$this->db->where('hotel_room_type_id', $room_type_id);
		$this->db->update('hotel_room_type', $data);
		

	}
	
	function active_room_type($room_type_id){
		$data = array(
					'status' => 'ACTIVE'
					);
		$this->db->where('hotel_room_type_id', $room_type_id);
		$this->db->update('hotel_room_type', $data);
		
	}

	function get_room_type_detail_count($room_type_id = ""){	

	    //$this->db->select('ht.*,hd.*,hc.no_of_room,hm.*');	    
	    $this->db->select('ht.*,hd.*,hc.no_of_room');	    
	    $this->db->from('hotel_room_type ht');
	    $this->db->join('hotel_room_details hd','hd.hotel_room_type_id = ht.hotel_room_type_id','left');
	    $this->db->join('hotel_room_count_info hc','hc.hotel_room_details_id = ht.hotel_room_type_id','left');
	    //$this->db->join('additional_meal_plan hm','hm.room_details_id = ht.hotel_room_type_id','left');

		//$this->db->where('ht.status', 'ACTIVE');
		if($room_type_id != '') {
	     $this->db->where('ht.hotel_room_type_id', $room_type_id);
		}
		$query=$this->db->get();
		//echo $this->db->last_query();
		if($query->num_rows() ==''){
			return '';
		}else{
			return $query->result();
		}		 		

	}

	function get_room_type_detail_booked_count($room_type_id = ""){
		$this->db->select('*');	    
	    $this->db->from('hotel_season_room_count_info');
	    //$this->db->join('hotel_room_details hd','hd.hotel_room_type_id = ht.hotel_room_type_id','left');
	    //$this->db->join('hotel_room_count_info hc','hc.hotel_room_details_id = ht.hotel_room_type_id','left');
	    //$this->db->join('additional_meal_plan hm','hm.room_details_id = ht.hotel_room_type_id','left');

		//$this->db->where('ht.status', 'ACTIVE');
		if($room_type_id != '') {
	     $this->db->where('hotel_room_type_id', $room_type_id);
		}
		$query=$this->db->get();
		//echo $this->db->last_query();
		if($query->num_rows() ==''){
			return '';
		}else{
			return $query->result();
		}		
	}

	function get_additional_meal_roomtype($room_type_id){
      $this->db->select('*');
      $this->db->where('room_details_id',$room_type_id);
      $query = $this->db->get('additional_meal_plan');
      if($query->num_rows() > 0){
      	return $query->result();
      }
      else{
      	return "";
      }

	}

	function get_hotel_id_roomtype($room_type_id){
		$this->db->select('hotel_details_id');
		$this->db->where('hotel_room_type_id',$room_type_id);
		$query = $this->db->get('hotel_room_type');
		if($query->num_rows() ==''){
			return '';
		}else{
			return $query->result();
		}

	}

	function get_table_data($field,$table,$filterfield,$filterdata){ //kames
        $this->db->select($field);
        $this->db->where($filterfield, $filterdata);
        $query=$this->db->get($table);
         
        if ($query->num_rows() >0) {
            return $query->result();
        }    
        else{
            return "";
        }
    }

    function update_room_type($input, $room_type_id,$image_path = ""){		
		// echo "<pre/>";print_r($input);exit();
		if(!isset($input['status']))
			$input['status'] = "INACTIVE";

		//if(!isset($input['extra_bed']))
		//	$input['extra_bed_count'] = "0";

		if($input['supplier_rights'] == 1 ){
            $input['supplier_details_id'] =$this->session->userdata('lgm_supplier_admin_id') ;
        } else {
            $input['supplier_details_id'] = $this->entity_user_id;
           // $return_data = $this->get_table_data('supplier_details_id', 'supplier_details', 'admin_relation_id', $input['supplier_details_id']);
            /*$input['supplier_details_id'] = "";
            if ($return_data != "") {
             foreach ($return_data as $row) {
                $input['supplier_details_id'] = $row->supplier_details_id;
             }
            }*/
        }
  	    if(!isset($input['extra_bed'])){
			$input['extra_bed'] = "Available";
		}

		if(!isset($input['extra_bed_count'])){
			$input['extra_bed_count'] = 0;
		}		

		$update_data = array(
							'hotel_details_id' 			=> $input['hotel_details_id'],
							'room_type_name' 			=> $input['room_type_name'],
							'room_description' 			=> $input['room_description'],
							'adult' 					=> $input['adult'],
							'child' 					=> $input['child'],
							'max_pax' 					=> $input['max_pax'],
							'extra_bed' 				=> $input['extra_bed'],
							'extra_bed_count' 			=> $input['extra_bed_count'],
							'supplier_details_id' 		=> $input['supplier_details_id'],
							'room_uploaded_image'       => $image_path,
							//'room_url_image'		    => $input['image_url']
							//'status' 					=> $input['status']					
						);	
		$this->db->where('hotel_room_type_id', $room_type_id);
		$this->db->update('hotel_room_type', $update_data);
		
		// debug($update_data);exit();
		$room_amenities='';
		foreach($input['sel_ammenities'] as $ammenities){
				$room_amenities .= $ammenities.',';
		}

		/*if($input['chk_breakfast'] != ''){
			$input['chk_brekfast'] = 1;
		}else {
			$input['chk_brekfast'] = 0;
		}  	  	  

		if($input['chk_lunch'] != ''){
			$input['chk_lunch'] = 1;
		}else{
			$input['chk_lunch'] = 0;
		}

		if($input['chk_dinner'] !=''){
			$input['chk_dinner'] = 1;
		} else {
		   $input['chk_dinner'] = 0;
		}	    	  

		if($input['chk_half'] != ''){
			$input['chk_half'] = 1;
		}else{
			$input['chk_half'] = 0;
		}

		if($input['chk_full'] !=''){
			$input['chk_full'] = 1;
		} else {
		   $input['chk_full'] = 0;
		}	    */

		$update_data = array(							 
								 'hotel_room_type_id'       => $room_type_id,
								 'hotel_details_id' 		=> $input['hotel_details_id'],
								 'room_name' 				=> 'NA',
								 'room_info' 				=> $input['room_info'],
								 'cancellation_policy' 		=> $input['cancellation_policy'],								
								// 'checkin_time' 			=> $input['checkin_time'],
								// 'checkout_time' 			=> $input['checkout_time'],
								/* 'break_fast_price' 		=> $input['break_fast_p'],
								 'breakfast_price_flag'		=> $input['chk_brekfast'],
								 'dinner_price'				=> $input['dinner_p'],
								 'dinner_price_flag'		=> $input['chk_dinner'], 
								 'lunch_price'				=> $input['lunch_p'], 
								 'lucnh_price_flag'			=> $input['chk_lunch'],
								 'full_board_price'			=> $input['full_board_p'],
								 'full_board_flag'		    => $input['chk_full'], 
								 'half_board_price'			=> $input['half_board_p'], 
								 'half_board_flag'			=> $input['chk_half'],*/
								 'room_amenities'			=> rtrim($room_amenities, ","),
								 //'status' 					=> $input['status'],
								 'creation_date'			=> (date('Y-m-d H:i:s'))								 
							);	

		$return_data = $this->get_table_data('hotel_room_type_id', 'hotel_room_details', 'hotel_room_type_id', $room_type_id);
		// debug($return_data);exit();
         $supplier = "";
          if ($return_data != "") {
            foreach ($return_data as $row) {
                $supplier = $row->hotel_room_type_id;
            }
          }

          //echo $supplier."hotel_room_details";
        

        if($supplier == ""){
        	$this->db->insert('hotel_room_details',$update_data);	
        }
        else{
			$this->db->where('hotel_room_type_id',$room_type_id);
			$this->db->update('hotel_room_details',$update_data);	
		}
		//echo "<pre>Hotel Model: "; print_r($input); exit;

		/*if($input['oth_meals_flag'] != ''){
			 for($loop = 0; $loop < sizeof($input['mealtype_name']);$loop++){
			   $data = array(
			   	             'room_details_id'=>$room_type_id,
			                 'meal_type_name' => $input['mealtype_name'][$loop],
			                 'oth_meals_flag' => '1',
			                 'mealtype_price' => $input['mealtype_price'][$loop]
			                 );	
			                 
			  if($input['meal_type_id'][$loop] != ""){
			  	$this->db->where('meal_type_id',$input['meal_type_id'][$loop]);
			  	$this->db->update('additional_meal_plan',$data);
			  }			                 			   
			  else{

			    $this->db->insert('additional_meal_plan', $data);
			 } 
		 }
		}	
		elseif($input['oth_meals_flag'] == ''){
			 $this->db->where('room_details_id',$room_type_id);
			 $this->db->delete('additional_meal_plan');
		}	*/
		

		$update_data = array(
			 'hotel_room_details_id' => $room_type_id,
			 'hotel_details_id'      => $input['hotel_details_id'],
   		 	 'no_of_room' 				=> $input['rooms_tot_units'],
			 //'status' 					=> $input['status'],
			 'creation_date'				=> (date('Y-m-d H:i:s'))
			);				

		$return_data = $this->get_table_data('hotel_room_details_id', 'hotel_room_count_info', 'hotel_room_details_id', $room_type_id);
         $supplier = "";
          if ($return_data != "") {
            foreach ($return_data as $row) {
                $supplier = $row->hotel_room_details_id;
            }
          }    

            
         
        //echo "<br>".$supplier."hotel_room_count_info";
         //$this->load->model('Seasons_model');
          //$this->Seasons_model->update_season_room_count_row('aa');
        
        if($supplier == ""){
		    $this->db->insert('hotel_room_count_info',$update_data); 
		     $hotel_room_count_info_insert_id = $this->db->insert_id();        	
        }else{ 	
			$this->db->where('hotel_room_details_id',$room_type_id);
			$this->db->update('hotel_room_count_info',$update_data);
			$hotel_room_count_info_insert_id = $this->db->last_query();  
		}
		$update_season_room_count_row_data = array(
						'hotel_details_id' => $input['hotel_details_id'],
						'hotel_room_type_id' => $room_type_id,
						'no_of_room' => $input['rooms_tot_units'],
						'hotel_room_count_info_id' => $room_type_id,
						'no_of_room_available' => $input['rooms_tot_units'],
						'adult' => $input['adult'],
						'child' => $input['child'],
						'status' => $input['status'],

					);		
		$this->load->model('Seasons_model');
        $this->Seasons_model->update_season_room_count_row($update_season_room_count_row_data);	
		  
	}
  //End of hotel Room Types
	//Images
	function upload_image_lgm($image_info, $module, $old_image_name=''){
		
		$image_info_name  = $old_image_name;
	
		if(!empty($image_info['thumb_image']['name'])){	
			if(is_uploaded_file($image_info['thumb_image']['tmp_name'])) {
				if($image_info_name !=''){
					$oldImage = "uploads/".$module."/".$image_info_name;
					unlink($oldImage);
				}
				 
				$image_type = explode("/",$image_info['thumb_image']['type']);
				if($image_type[0] == "image"){				
					$sourcePath = $image_info['thumb_image']['tmp_name'];
					$img_Name	= time().$image_info['thumb_image']['name'];
					$targetPath = "uploads/".$module."/".$img_Name;
					// echo $targetPath;die;
					if(move_uploaded_file($sourcePath,$targetPath)){
						$image_info_name = $img_Name;
					}
				}
			}				
		}
		return $image_info_name;
	}

	function upload_images_all($image_info, $module, $name, $old_image_name='',$id){
		$user_profile_name  = $old_image_name;
		if(!empty($image_info[$name]['name'])){	
			if(is_uploaded_file($image_info[$name]['tmp_name'][$id])) {
				$image_type = explode("/",$image_info[$name]['type'][$id]);
				if($image_type[0] == "image"){
					if($user_profile_name !=''){
						$oldImage = "uploads/".$module."/".$user_profile_name;
						unlink($oldImage);
					}			
					$sourcePath = $image_info[$name]['tmp_name'][$id];
					$img_Name	= time().$image_info[$name]['name'][$id];
					$targetPath = "uploads/".$module."/".$img_Name;
					if(move_uploaded_file($sourcePath,$targetPath)){
						$user_profile_name = $img_Name;
					}
				}
			}				
		}
		return $user_profile_name;
	}

	  function get_child_group($hotel_id)
  {
	   $this->db->select('child_group_a, child_group_b, child_group_c, child_group_d, child_group_e');
	   $this->db->from('hotel_details');
	   $this->db->where('hotel_details_id', $hotel_id);
	   $query=$this->db->get();
	    //~ echo $this->db->last_query($query);exit;
		if($query->num_rows() ==''){
			return '';
		}else{
			return $query->result();
		}
  }



  function get_contatc_list($hotel_id){
		$this->db->select('*');
		$this->db->from('hotel_contact_details');
		if($hotel_id !='')
			$this->db->where('hotel_details_id', $hotel_id);
		$query=$this->db->get();

		if($query->num_rows() ==''){
			return '';
		}else{
			return $query->row();
		}
	}

	function get_room_list($hotel_id){
		
		$this->db->select('hr.*,ht.room_type_name');
		$this->db->from('hotel_room_details hr');
		if($hotel_id !=''){
			$this->db->where('hr.hotel_details_id', $hotel_id);
		}
		$this->db->join('hotel_room_type ht', 'ht.hotel_room_type_id = hr.hotel_room_type_id');
		$query=$this->db->get();
		//echo $this->db->last_query();exit();
		if($query->num_rows() ==''){
			return '';
		}else{
			return $query->result();
		}
	}

	function get_room_count_info($hotel_id){
		//$this->db->select('hr.*,ht.room_name,hrr.room_type_name');
		$this->db->select('hr.*,hrr.room_type_name');
		$this->db->from('hotel_room_count_info hr');
		if($hotel_id !='')
			$this->db->where('hr.hotel_details_id', $hotel_id);
		//$this->db->join('hotel_room_details ht', 'ht.hotel_room_details_id = hr.hotel_room_details_id');
		//$this->db->join('hotel_room_type hrr', 'ht.hotel_room_type_id = hrr.hotel_room_type_id');
		$this->db->join('hotel_room_type hrr', 'hr.hotel_room_details_id = hrr.hotel_room_type_id');
		$query=$this->db->get();
		
		//echo $this->db->last_query();exit();
		if($query->num_rows() ==''){
			return '';
		}else{
			return $query->result();
		}
	}

	function get_room_rate_info($hotel_id){
		$this->db->select('hr.*,hty.room_type_name');
		$this->db->from('hotel_room_rate_info hr');
		if($hotel_id !='')
			$this->db->where('hr.hotel_details_id', $hotel_id);
		// $this->db->join('hotel_room_details ht', 'ht.hotel_room_details_id = hr.hotel_room_details_id');
		$this->db->join('hotel_room_type hty', 'hty.hotel_room_type_id = hr.hotel_room_type_id');
		$query=$this->db->get();
		if($query->num_rows() ==''){
			return '';
		}else{
			return $query->result();
		}
	}
	function delete_hotel_image($hotel_id,$hotel_image){
		//echo $hotel_image;
		$hotel_image = str_replace('%20', ' ',$hotel_image);
		$this->db->select('hd.hotel_images');
		$this->db->from('hotel_details hd');
		if($hotel_id !='')
			$this->db->where('hd.hotel_details_id', $hotel_id);
		$query=$this->db->get();
		if($query->num_rows() > 0){
			$hotel_images =  $query->result();
			$img_array = $hotel_images[0]->hotel_images;
			$hotel_images_array = explode(',',$img_array);
			$key = array_search($hotel_image,$hotel_images_array);
			
			if(($key===null)==false){
				unset($hotel_images_array[$key]);
			}
			$new_image_data = implode(',',$hotel_images_array);
			$new_data = ["hotel_images"=>$new_image_data];
			$this->db->where('hotel_details_id',$hotel_id);
			$this->db->update('hotel_details',$new_data);
			$image_location = 'uploads/hotel_images/'.$hotel_image;
			if(file_exists($image_location)){
				unlink($image_location);
			}
		}
	}
	function hotel_crs_cancel($app_reference){
		$response = ['status'=>0,"msg"=>"Error while cancelling room!!"];
		$booking_ref = $app_reference;

		$sql_count = "select bh.id from booking_hotel AS bh JOIN booking_global AS bg ON(bh.id=bg.ref_id) where bg.parent_pnr='".$booking_ref."'";
		//debug($sql_count);exit;
		$query = $this->db->query($sql_count);
		if($query->num_rows() > 0){
			$sql_update = "UPDATE booking_global SET `booking_status`='CANCELLED' WHERE parent_pnr ='".$booking_ref."'";
			$query_update = $this->db->query($sql_update);
			if($this->db->affected_rows() > 0){
				$response['status']=1;
				$response['msg'] = "Hotel booking was cancelled successfully.";
				return json_encode($response); 
			}else{
				return json_encode($response); 
			}
        }else{
        	//else
           return json_encode($response); 
        }
	}
	public function getBookingDetails($parent_pnr){
        $this->db->select('*');
        $this->db->where('parent_pnr',$parent_pnr);
        $query = $this->db->get('booking_global');
        if($query->num_rows() > 0){
        return $query->result();
        }
    }
	function get_voucher_details($parent_pnr){
        $this->db->select('*');
        $this->db->from('booking_global');
        $this->db->join('booking_hotel', 'booking_hotel.id = booking_global.ref_id');
        $this->db->where('booking_global.parent_pnr',$parent_pnr);
        $this->db->where('booking_global.module','HOTEL');
        return $this->db->get()->result();
        //$this->db->where('')
    }
    public function hotel_crs_booking_count_total(){
		$this->db->select('bg.*');
        $this->db->from('booking_global bg');
        $this->db->where('bg.module','HOTEL');
        $query = $this->db->get();
        $crs_count = $query->num_rows;
        return $crs_count;
	}
	//End Images

	//Hotel Type Start


function get_active_room_types_list($room_type_id = ''){
		$this->db->select('*');
		$this->db->from('room_type');
    	if($room_type_id !=''){
			$this->db->where('room_type_id', $room_type_id);
			$this->db->where('status', 'ACTIVE');
    	}
    	$this->db->where('status', 'ACTIVE');
    	$this->db->order_by("room_type_id","desc");

		$query=$this->db->get();
		//echo $this->db->last_query();exit;
		if($query->num_rows() ==''){
			return '';
		}else{
			return $query->result();
		}
	}



	 function get_room_types_list($room_type_id = ''){
		$this->db->select('*');
		$this->db->from('room_type');
    	if($room_type_id !=''){
			$this->db->where('room_type_id', $room_type_id);
			//$this->db->where('status', 'ACTIVE');
    	}
    	$this->db->order_by("room_type_id","desc");

		$query=$this->db->get();
		//echo $this->db->last_query();exit;
		if($query->num_rows() ==''){
			return '';
		}else{
			return $query->result();
		}
	}

	function add_room_types_details($input){
		if(!isset($input['status']))
			$input['status'] = "INACTIVE";
		$insert_data = array(
							'room_type_name' 		=> $input['room_type_name'],
							'status' 				=> $input['status']					
						);
		/*if($input['supplier_rights'] == 1 ){
		 $insert_data['room_type_added_by_supplier'] =$this->session->userdata('lgm_supplier_admin_id') ;
		} else {*/
		 //$insert_data['room_type_added_by_mgmt'] = $this->session->userdata('provab_admin_id') ;

		$insert_data['room_type_added_by_mgmt'] = '' ;
		//}				
		/*$this->db->insert('room_type',$insert_data);
		$id = $this->db->insert_id();*/
		//$this->General_Model->insert_log('10','add_room_type',json_encode($input),'adding the hotel Type','room_type','room_type_id',$id);

		$this->db->where("room_type_name",$input['room_type_name']);
		$qur = $this->db->get ("room_type");
		$count=$qur->num_rows();

		if($count==0){			
		$this->db->insert('room_type',$insert_data);
		$id = $this->db->insert_id();
		return true;
		}else{
		return false;
		}
	}
	
	function inactive_room_types($room_type_id){
		$data = array(
					'status' => 'INACTIVE'
					);
		$this->db->where('room_type_id', $room_type_id);
		$this->db->update('room_type', $data);
		
	}
	
	function active_room_types($room_type_id){
		$data = array(
					'status' => 'ACTIVE'
					);
		$this->db->where('room_type_id', $room_type_id);
		$this->db->update('room_type', $data);
		
	}
	
	function update_room_types($input, $room_type_id){
		if(!isset($input['status']))
			$input['status'] = "INACTIVE";
		$update_data = array(
							'room_type_name' 			=> $input['room_type_name'],
							'status' 					=> $input['status']					
						);	
		/*if($input['supplier_rights'] == 1 ){
		 $update_data['room_type_added_by_supplier'] =$this->session->userdata('lgm_supplier_admin_id') ;
		} else {
		 $update_data['room_type_added_by_mgmt'] = $this->session->userdata('provab_admin_id') ;
		}*/	
		$update_data['room_type_added_by_mgmt'] = '' ;
		$this->db->where('room_type_id', $room_type_id);
		$this->db->update('room_type', $update_data);
		
	}
	
	function delete_room_types($room_type_id){
		$this->db->where('room_type_id', $room_type_id);
		$this->db->delete('room_type'); 
		
	}

	function update_wizard_status($hotel_id='2',$status='step1')
	{

		return $this->db->query('
			update hotel_details set wizard_status =  
			CASE  
                WHEN wizard_status = \'\' THEN \''.$status.'\'
                WHEN wizard_status is null THEN \''.$status.'\'
                WHEN find_in_set(\''.$status.'\', wizard_status) THEN wizard_status
                ELSE concat(wizard_status, \',\', \''.$status.'\')
            END 
            WHERE hotel_details_id = '.$hotel_id.'
		');
	}

	function get_wizard($hotel_id){
		$this->db->select('wizard_status');
		$this->db->from('hotel_details');
		$this->db->where('hotel_details_id', $hotel_id);
		return $this->db->get()->result_array(); 
	}

    // public function hotels_enquiry()
    // {
    //     $this->db->order_by('id','desc');
    //     $query = $this->db->get("hotels_enquiry");
    //     if($query->num_rows > 0)
    //     {
    //         $result['tours_enquiry']=$query->result_array();
    //     }
    //     else
    //     {
    //         $result['tours_enquiry']= array();
    //     }
    //     foreach ($result['tours_enquiry'] as $key => $value) {
    //         $tp_query = 'select *  from tour_price_management WHERE tour_id='.$value['tour_id'].' AND from_date<="'.$value['departure_date'].'" AND to_date>="'.$value['departure_date'].'"  ';
    //         $tours_price_details = $this->db->query ( $tp_query )->result_array ();
    //         $result['tours_enquiry'][$key]['price'] = $tours_price_details[0]['final_airliner_price'];

    //         $tp_query1 = 'select *  from tour_booking_details WHERE enquiry_reference_no="'.$value['enquiry_reference_no'].'"';
    //         $tours_new_price_details = $this->db->query ( $tp_query1 )->result_array ();
    //         // debug($tours_price_details);die;
    //         $result['tours_enquiry'][$key]['price'] = $tours_price_details[0]['airliner_price'];
    //         $result['tours_enquiry'][$key]['updated_price'] = $tours_new_price_details[0]['final_airliner_price'];
    //         $result['tours_enquiry'][$key]['currency'] = $tours_price_details[0]['currency'];
    //     }
    //     return $result;
    // }


    public function update_remark($table,$data,$where){
		$query = $this->db->set($data)
							->where($where)
							->update($table);
							return TRUE;				
	}

    public function hotels_enquiry($condition)
    {
        /*if($condition['status'] && strtolower($condition['status']) != 'all'){
            $this->db->where('status',$condition['status']);
        }
        if($condition['phone']){
            $this->db->where('phone',$condition['phone']);
        }
        if($condition['email']){
            $this->db->where('email',$condition['email']);
        }
        if($condition['tour_id']){
            $this->db->where('tour_id',$condition['tour_id']);
        }
        if($condition['common_date']){
            $this->db->where('common_date',$condition['common_date']);
        }*/
        $this->db->order_by('id','desc');
        $query = $this->db->get("hotels_enquiry");
        if($query->num_rows > 0)
        {
            $result['hotels_enquiry']=$query->result_array();
            //debug($result);die;
        }
        else
        {
            $result['hotels_enquiry']= array();
        }

        foreach ($result['tours_enquiry'] as $key => $value) {
			$tp_query = 'select *  from tour_price_management WHERE tour_id='.$value['tour_id'].' AND from_date<="'.$value['departure_date'].'" AND to_date>="'.$value['departure_date'].'"  ';
			$tours_price_details = $this->db->query ( $tp_query )->result_array ();
			$result['tours_enquiry'][$key]['price'] = $tours_price_details[0]['final_airliner_price'];
			
			$tp_query1 = 'select *  from tour_booking_details WHERE enquiry_reference_no="'.$value['enquiry_reference_no'].'"';
			$tours_new_price_details = $this->db->query ( $tp_query1 )->result_array ();
			// debug($tours_price_details);die;
			$result['tours_enquiry'][$key]['price'] = $tours_price_details[0]['airliner_price'];
			$result['tours_enquiry'][$key]['updated_price'] = $tours_new_price_details[0]['final_airliner_price'];
			$result['tours_enquiry'][$key]['currency'] = $tours_price_details[0]['currency'];
   }


   //      foreach ($result['tours_enquiry'] as $key => $value) {
   //          $tp_query = 'select *  from tour_price_management WHERE tour_id='.$value['tour_id'].' AND from_date<="'.$value['departure_date'].'" AND to_date>="'.$value['departure_date'].'"  ';
   //          $tours_price_details = $this->db->query ( $tp_query )->result_array ();
   //          $result['tours_enquiry'][$key]['price'] = $tours_price_details[0]['final_airliner_price'];
            
   //          $tp_query1 = 'select *  from tour_booking_details WHERE enquiry_reference_no="'.$value['enquiry_reference_no'].'"';
   //          $tours_new_price_details = $this->db->query ( $tp_query1 )->result_array ();
   //          // debug($tours_price_details);die;
   //          $result['tours_enquiry'][$key]['price'] = $tours_price_details[0]['airliner_price'];
   //          $result['tours_enquiry'][$key]['updated_price'] = $tours_new_price_details[0]['final_airliner_price'];
   //          $result['tours_enquiry'][$key]['currency'] = $tours_price_details[0]['currency'];
   // }
  // echo "in model";
   //echo $this->db->last_query();
   //debug($result);die;
            return $result;
    }



    public function tours_itinerary_all()
    {
        $query = "select * from tours_itinerary order by id asc"; //echo $query; exit;
        $exe   = mysql_query($query);
        while($fetch = mysql_fetch_assoc($exe))
        {
            $result[] = $fetch;
        }
        return $result;
    }


	/*Hotel - CRS - End*/
	
		public function get_country_list() {
		$this->db->limit ( 1000 );
		$this->db->order_by ( "country_name", "asc" );
		
		$qur = $this->db->get ( "country_list_nationality" );
	//	debug($qur->result ());exit;
		return $qur->result ();
	}
	public function get_currency($country_id ){
		$this->db->select ( "currency_code");
		$this->db->from ("country_list_nationality");
		$this->db->where ( 'country_list', $country_id );
		$qur = $this->db->get ();
		return $qur->result ();
	}
}
