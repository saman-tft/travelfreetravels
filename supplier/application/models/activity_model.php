<?php
/**
 * @package    Provab Application
 * @subpackage Travel Portal
 * @author     Balu A<balu.provab@gmail.com>
 * @version    V2
 */
class Activity_Model extends CI_Model {
	public function __construct() {
		parent::__construct ();
	}
	public function package_view_data_types($status='') {
		if(!empty($status)){
			$this->db->where( "status", 1 );
		}
		return $this->db->order_by("activity_types_id", "DESC")->get ( 'activity_types' );
	}
	public function package_types($status) {
		$query = ' select * from activity_types WHERE activity_types_id  IN (select activity_type_id from activity_sub_category) and status='.$status.'';
		return $this->db->query($query);
	}
	public function get_countries() {
		$this->db->limit ( 1000 );
		$this->db->order_by ( "name", "asc" );
		$qur = $this->db->get ( "country" );
		return $qur->result ();
	}
	public function package_type_data() {
		return $this->db->get ( 'activity_types' );
	}
	public function add_new_package($newpackage) {
		$this->db->insert ( 'activity', $newpackage );
		return $this->db->insert_id ();
	}
	public function update_excursion_package($package_id, $package) {
		$this->db->where ( 'package_id', $package_id );
		 $this->db->update ( 'activity', $package );
		 return $package_id;
	}
	public function add_available_dates($available_dates_ary) {
		$this->db->insert ( 'activity_available_dates', $available_dates_ary );
		// echo $this->db->last_query();exit;
		// return $this->db->insert_id ();
	}
	public function update_available_dates($season_array,$range_id) {
		// debug($available_dates_ary);exit;
		$this->db->where ( 'id', $range_id );
		return $this->db->update ( 'activity_available_dates', $season_array );
	}
	public function update_sub_activities( $sub_activities_ary,$sub_activity_id ) {
		$this->db->where ( 'id', $sub_activity_id );
		return $this->db->update ( 'sub_activity_timing', $sub_activities_ary );
	}
	public function update_itinerary_details($itinerary,$itinery_id) {
		$this->db->where ( 'iti_id', $itinery_id );
		return $this->db->update ( 'activity_itinerary', $itinerary );
	}
	public function delete_activity_price( $package_id) {
		$this->db->where ( 'activity_id', $package_id );
		$this->db->delete ( 'activity_price_management');
	}
	public function activity_price($transfer_option_array) {
		$this->db->insert ( 'activity_price_management', $transfer_option_array );
		// echo $this->db->last_query();exit;
	}
	public function add_sub_activities($sub_activities_ary) {
		$this->db->insert ( 'sub_activity_timing', $sub_activities_ary );
		// echo $this->db->last_query();exit;
		// return $this->db->insert_id ();
	}
	public function update_code_package($packcode, $package) {
		$this->db->where ( 'package_id', $package );
		return $this->db->update ( 'activity', $packcode );
	}
	public function itinerary($itinerary) {
		$this->db->insert ( 'activity_itinerary', $itinerary );
		return $this->db->insert_id ();
	}
	public function que_ans($que_ans) {
		$this->db->insert ( 'activity_que_ans', $que_ans );
		return $this->db->insert_id ();
	}
	public function pricing_policy($pricingpolicy) {
		$this->db->insert ( 'activity_pricing_policy', $pricingpolicy );
		return $this->db->insert_id ();
	}
	public function cancellation_penality($cancellation) {
		$this->db->insert ( 'activity_cancellation', $cancellation );
		return $this->db->insert_id ();
	}
	public function do_delete_row($table,$column,$id){
	    $this->db->where($column, $id);
	    $this->db->delete($table);
	    return true;
	}
	public function deals($deals) {
		$this->db->insert ( 'activity_deals', $deals );
		return $this->db->insert_id ();
	}
	public function travel_images($traveller) {
		$this->db->insert ( 'activity_traveller_photos', $traveller );
		return $this->db->insert_id ();
	}
	public function without_price() {
		$this->db->where ( 'supplier_id', $this->session->userdata ( 'sup_id' ) );
		$this->db->where ( "price_includes", '0' );
		$this->db->where ( "deals", '0' );
		$q = $this->db->get ( "package" );
		if ($q->num_rows () > 0) {
			return $q->result ();
		}
		return array ();
	}
	public function get_supplier() {
		$this->db->where ( 'supplier_id', $this->session->userdata ( 'sup_id' ) );
		$this->db->where ( "deals", '1' );
		$this->db->where ( "price_includes", '0' );
		$q = $this->db->get ( "package" );
		if ($q->num_rows () > 0) {
			return $q->result ();
		}
		return array ();
	}
	public function update_status_answer($id, $status) {
		$data = array (
				'status' => $status 
		);
		// $where = "package_id = " . $package_id;
		$where = "id = " . $id;
		// $where = "qid = " . $qid;
		if ($this->db->update ( 'package_answers', $data, $where )) {
			return $status;
		} else {
			return '0';
		}
	}
	public function update_status($id, $status) {
		$data = array (
				'status' => $status 
		);
		$where = "package_id = " . $id;
		if ($this->db->update ( 'activity', $data, $where )) {
			return $status;
		} else {
			return '0';
		}
	}
	public function update_top_destination($id, $status) {
		$data = array (
				'top_destination' => $status 
		);
		$where = "package_id = " . $id;
		if ($this->db->update ( 'package', $data, $where )) {
			return $status;
		} else {
			return '0';
		}
	}
	public function update_enquiry_status($id, $status) {
		$data = array (
				'enquiry_status' => $status
		);
		$where = "id = " . $id;
		if ($this->db->update ( 'package_enquiry', $data, $where )) {
			return $status;
		} else {
			return '0';
		}
	}
	public function update_homepage_status($package, $home_page) {
		$data = array (
				'home_page' => $home_page 
		);
		
		$where = "package_id = " . $package;
		// $where = "img_id = " . $img_id;
		if ($this->db->update ( 'activity', $data, $where )) {
			return true;
		} else {
			return false;
		}
	}
	public function get_package_id($package_id) {
		$this->db->select ( "*" );
		$this->db->from ( "activity" );
		$this->db->join ( 'activity_cancellation', 'activity_cancellation.package_id = activity.package_id' );
		$this->db->join ( 'activity_pricing_policy', 'activity_pricing_policy.package_id = activity.package_id' );
		$this->db->where ( 'activity.package_id', $package_id );
		return $this->db->get ()->row ();
	}
	public function get_price($package_id) {
		$this->db->from ( 'activity_duration' );
		$this->db->where ( 'package_id', $package_id );
		$query = $this->db->get ();
		if ($query->num_rows > 0) {
			
			return $query->row ();
		}
		return false;
	}
	public function get_country_city_list() {
		$this->db->select ( '*' )->from ( 'country' );
		$query = $this->db->get ();
		
		if ($query->num_rows > 0) {
			
			return $query->result ();
		}
		return false;
	}
	public function get_itinerary_id($package_id) {
		$this->db->from ( 'activity_itinerary' );
		$this->db->where ( 'package_id', $package_id );
		$query = $this->db->get ();
		if ($query->num_rows > 0) {
			
			return $query->result ();
		}
		return false;
	}
	public function get_que_ans($package_id) {
		$this->db->from ( 'activity_que_ans' );
		$this->db->where ( 'package_id', $package_id );
		$query = $this->db->get ();
		
		if ($query->num_rows > 0) {
			
			return $query->result ();
		}
		return false;
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
	public function with_price($user_type='') 
	{
 
		$this->db->select('a.*,u.first_name,u.last_name'); 
	    $this->db->from('activity a');
	    $this->db->join('user u', 'u.user_id = a.supplier_id', 'left');
	    $this->db->order_by('a.package_id','desc');
	    if(!empty($user_type)) 
	    {
	    $this->db->where('u.user_type',$user_type);
		}
	    $query = $this->db->get();
	   if ($query->num_rows () > 0) 
	   {
			return $query->result ();
		} 
		return array ();	

	}
	public function get_country_name($id) {
		$this->db->select ( 'name' );
		$this->db->where ( 'country_id', $id );
		return $this->db->get ( 'country' )->row ();
	}
	public function enquiries() {
		$this->db->from ( 'package_enquiry' );
		$this->db->join ( 'activity', 'activity.package_id=package_enquiry.package_id' );
		$this->db->order_by ( 'id', "desc" );
		$query = $this->db->get ();
		if ($query->num_rows > 0) {
			return $query->result ();
		}
		return false;
	}
	public function get_crs_city_list($value) {
		$this->db->where ( 'country', $value );
		return $this->db->get ( 'crs_city' )->result ();
	}
	public function get_sub_category_activity($type_id) {
		$this->db->where ( 'activity_type_id', $type_id );
		$this->db->where ( 'status',1);
		return $this->db->get ( 'activity_sub_category' )->result ();
	}
	public function get_tour_list($value) {
		$this->db->where ( 'activity_types_id', $value );
		return $this->db->get ( 'activity_types' )->result ();
	}
	public function update_edit_package($package_id, $data) {
		$where = "package_id = " . $package_id;
		if ($this->db->update ( 'activity', $data, $where )) {
			//debug($this->db->last_query());exit;
			return true;
		} else {
			return false;
		}
	}
	public function update_edit_policy($package_id, $policy) {
		$where = "package_id = " . $package_id;
		if ($this->db->update ( 'activity_pricing_policy', $policy, $where )) {
			return true;
		} else {
			return false;
		}
	}
	public function update_edit_can($package_id, $can) {
		$where = "package_id = " . $package_id;
		if ($this->db->update ( 'activity_cancellation', $can, $where )) {
			return true;
		} else {
			return false;
		}
	}
	public function update_edit_dea($package_id, $dea) {
		$where = "package_id = " . $package_id;
		if ($this->db->update ( 'activity_deals', $dea, $where )) {
			return true;
		} else {
			return false;
		}
	}
	public function update_edit_pri($package_id, $pri) {
		$where = "package_id = " . $package_id;
		if ($this->db->update ( 'activity_duration', $pri, $where )) {
			return true;
		} else {
			return false;
		}
	}
	public function get_image($package_id) {
		$this->db->from ( 'activity_traveller_photos' );
		$this->db->where ( 'package_id', $package_id );
		$query = $this->db->get ();
		if ($query->num_rows > 0) {
			
			return $query->result ();
		}
		return false;
	}
	public function update_itinerary($package, $itinerary_id, $data) {
		$where = "package_id = " . $package;
		$where = "iti_id = " . $itinerary_id;
		if ($this->db->update ( 'activity_itinerary', $data, $where )) {
			return true;
		} else {
			return false;
		}
	}
	public function delete_traveller_img($pack_id,$img_id) {
		$this->db->where ( 'package_id', $img_id );
		$this->db->where ( 'img_id', $pack_id );
		$this->db->delete ( 'activity_traveller_photos' );
	}
	public function view_enqur($package_id) {
		$this->db->from ( 'package_enquiry' );
		$this->db->where ( 'package_id', $package_id );
		$query = $this->db->get ();
		if ($query->num_rows > 0) {
			
			return $query->result ();
		}
	}
	public function delete_enquiry($id) {
		$this->db->where ( 'id', $id );
		$this->db->delete ( 'package_enquiry' );
	}
	public function delete_package_type($id) {
		$this->db->where ( 'activity_types_id', $id );
		$this->db->delete ( 'activity_types' );
	}
	public function delete_package($id) {
		$this->db->where ( 'package_id', $id );
		$this->db->delete ( 'activity' );
	}
	public function get_pack_id($id) {
		$this->db->select ( '*' );
		$this->db->where ( 'activity_types_id', $id );
		return $this->db->get ( 'activity_types' )->result ();
	}
	public function update_package_type($add_package_data, $id) {
		$this->db->where ( 'activity_types_id', $id );
		$this->db->update ( 'activity_types', $add_package_data );
	}


	public function nationality_price($nationality_price) {
		$this->db->insert ( 'activity_nationality_price', $nationality_price );
		return $this->db->insert_id ();
	}

		public function get_price_ids($package_id, $country_id='') {
		$this->db->from ( 'activity_nationality_price' );
		if(!empty($country_id))
		{
			$this->db->where ( 'country_id', $country_id );
		}
		// $this->db->join ( 'country', 'country.country_id=activity_nationality_price.country_id' );
		$this->db->where ( 'package_id', $package_id );
		$query = $this->db->get ();
		if ($query->num_rows > 0) {
			
			return $query->result ();
		}
		return false;
	}

		public function delete_price($id) {
		$this->db->where ( 'id', $id );
		$this->db->delete ( 'activity_nationality_price' );
	}



	public function get_currency_list()
	{
		$this->db->select('*');
		$this->db->where('status',1);
		$query = $this->db->get('currency_converter');
		if ( $query->num_rows > 0 ) {
	 		return $query->result_array();
		}else{
			return array();
		}	
	}
	public function get_tours_continent()
	{

		$this->db->select('*');
		$this->db->where('status',1);
		$this->db->order_by ( "name", "asc" );
		$query = $this->db->get('tours_continent');
		if ( $query->num_rows > 0 ) {
	 		return $query->result_array();
		}else{
			return array();
		}	
		
	}

	public function ajax_tours_continent($tours_continent)
	{

		$this->db->select('*');
		$this->db->where('continent',$tours_continent);
		$this->db->order_by ( "name", "asc" );
		$query = $this->db->get('tours_country');
		if ( $query->num_rows > 0 ) {
	 		return $query->result_array();
		}else{
			return array();
		}	
		
	}
		public function country_id($tours_continent)
	{

		$this->db->select('*');
		$this->db->where('id',$tours_continent);
		$this->db->order_by ( "name", "asc" );
		$query = $this->db->get('tours_country');
		if ( $query->num_rows > 0 ) {
	 		return $query->result_array();
		}else{
			return array();
		}	
		
	}
	// public function add_price_cat($newpprice) {
	// 	$this->db->insert ('price_category', $newpprice );
	// 	return $this->db->insert_id ();
	// }
	// 	public function update_price_cat($newpprice, $id) {
	// 	$this->db->where ( 'id', $id );
	// 	return $this->db->update ( 'price_category', $newpprice );
	// }
	public function price_category_data() {
		$this->db->select ( 'price_category.*,tours_continent.name as cont_name' );
		$this->db->join ( 'tours_continent', 'tours_continent.id = price_category.contient' );
		$this->db->order_by('price_category.id','desc');
		return $this->db->get( 'price_category' );

	}
	public function get_price_category_id($id) {
		$this->db->select ( '*' );
		$this->db->where ( 'id', $id );
		return $this->db->get ( 'price_category' )->result ();
	}
	
	public function tours_country_name()
	{
		$this->db->select ( '*' );
		$this->db->order_by ( "name", "asc" );
		return $this->db->get ( 'tours_country' )->result_array ();
	}
	function staff_holiday_report($condition=array(), $count=false, $offset=0, $limit=100000000000)
	{

		$condition = $this->custom_db->get_custom_condition($condition);
		//$b2c_condition_array = array('U.user_type', '=', B2C_USER, ' OR ', 'BD.created_by_id', '=', 0);
		
		//BT, CD, ID


		 
		 // debug($condition);exit();

		if(isset($condition) == true)
		{
			$offset = 0;
		}else{
			
			$offset = $offset;
		}


		if ($count) {


  
			$query = 'select count(distinct(BD.app_reference)) AS total_records from activity_booking_details BD
					left join user U on U.user_id = BD.created_by_id
					left join user_type UT on UT.origin = U.user_type
					join activity_booking_transaction_details as BT on BD.app_reference = BT.app_reference	
					where (U.user_type='.STAFF.' OR BD.created_by_id = 0) AND BD.domain_origin='.get_domain_auth_id().''.$condition;
			 //debug($query);exit;
			
			$data = $this->db->query($query)->row_array();
			// debug($data); exit;
			return $data['total_records'];

		} else {
			$this->load->library('booking_data_formatter');
			$response['status'] = SUCCESS_STATUS;
			$response['data'] = array();
			$booking_itinerary_details	= array();
			$booking_customer_details	= array();
			$booking_transaction_details = array();
			$cancellation_details = array();
			$payment_details = array();
			//Booking Details
			$bd_query = 'select BD.* ,P.package_name ,U.user_name,U.first_name,U.last_name from activity_booking_details AS BD
					     left join user U on U.user_id = BD.created_by_id
					     left join user_type UT on UT.origin = U.user_type
					     left join activity P on BD.package_type = P.package_id
					     join activity_booking_transaction_details as BT on BD.app_reference = BT.app_reference		
						 WHERE  (U.user_type='.STAFF.' OR BD.created_by_id = 0) AND BD.domain_origin='.get_domain_auth_id().' '.$condition.'
						 order by BD.created_datetime desc, BD.origin desc limit '.$offset.', '.$limit;		

					
						 
			$booking_details	= $this->db->query($bd_query)->result_array();
			//echo debug($bd_query); 			exit;
			$app_reference_ids = $this->booking_data_formatter->implode_app_reference_ids($booking_details);
			if(empty($app_reference_ids) == false) {
				//Itinerary Details
				$id_query = 'select * from `activity_booking_passenger_details`  AS ID
							WHERE ID.app_reference IN ('.$app_reference_ids.')';
				//Transaction Details
				$td_query = 'select * from activity_booking_transaction_details AS TD
							WHERE TD.app_reference IN ('.$app_reference_ids.')';
				//Customer and Ticket Details
				// $cd_query = 'select CD.*,FPTI.TicketId,FPTI.TicketNumber,FPTI.IssueDate,FPTI.Fare,FPTI.SegmentAdditionalInfo
				// 			from flight_booking_passenger_details AS CD
				// 			left join flight_passenger_ticket_info FPTI on CD.origin=FPTI.passenger_fk
				// 			WHERE CD.flight_booking_transaction_details_fk IN
				// 			(select TD.origin from flight_booking_transaction_details AS TD
				// 			WHERE TD.app_reference IN ('.$app_reference_ids.'))';
				//Cancellation Details
				// $cancellation_details_query = 'select FCD.*
				// 		from flight_booking_passenger_details AS CD
				// 		left join flight_cancellation_details AS FCD ON FCD.passenger_fk=CD.origin
				// 		WHERE CD.flight_booking_transaction_details_fk IN
				// 		(select TD.origin from flight_booking_transaction_details AS TD
				// 		WHERE TD.app_reference IN ('.$app_reference_ids.'))';
				//$payment_details_query = '';
				$booking_itinerary_details	= $this->db->query($id_query)->result_array();
				//$booking_customer_details	= $this->db->query($cd_query)->result_array();
				$booking_transaction_details = $this->db->query($td_query)->result_array();
				//$cancellation_details = $this->db->query($cancellation_details_query)->result_array();
				//$payment_details = $this->db->query($payment_details_query)->result_array();
			}
	
			$response['data']['booking_details']			= $booking_details;
			$response['data']['booking_itinerary_details']	= $booking_itinerary_details;
			$response['data']['booking_transaction_details']	= $booking_transaction_details;
			// $response['data']['booking_customer_details']	= $booking_customer_details;
			// $response['data']['cancellation_details']	= $cancellation_details;
			//$response['data']['payment_details']	= $payment_details;
			return $response;
		}
	}
	function b2c_holiday_report($condition=array(), $count=false, $offset=0, $limit=100000000000)
	{

		$condition = $this->custom_db->get_custom_condition($condition);
		//$b2c_condition_array = array('U.user_type', '=', B2C_USER, ' OR ', 'BD.created_by_id', '=', 0);
		
		//BT, CD, ID


		 
		 // debug($condition);exit();

		/*if(isset($condition) == true)
		{
			$offset = 0;
		}else{
			
			$offset = $offset;
		}*/


		if ($count) {


  
			$query = 'select count(distinct(BD.app_reference)) AS total_records from activity_booking_details BD
					left join user U on U.user_id = BD.created_by_id
					left join user_type UT on UT.origin = U.user_type
					join activity_booking_transaction_details as BT on BD.app_reference = BT.app_reference	
					where (U.user_type='.B2B_USER.' ) AND BD.domain_origin='.get_domain_auth_id().''.$condition;
			 //debug($query);exit;
			
			$data = $this->db->query($query)->row_array();
			// debug($data); exit;
			return $data['total_records'];

		} else {
			$this->load->library('booking_data_formatter');
			$response['status'] = SUCCESS_STATUS;
			$response['data'] = array();
			$booking_itinerary_details	= array();
			$booking_customer_details	= array();
			$booking_transaction_details = array();
			$cancellation_details = array();
			$payment_details = array();
			//Booking Details
			$bd_query = 'select BD.* ,U.user_name,U.first_name,U.last_name,U.user_type,U.agent_staff,U.agent_staff_id,U.agency_name from activity_booking_details AS BD
					     left join user U on U.user_id = BD.created_by_id
					     left join user_type UT on UT.origin = U.user_type
					     join activity_booking_transaction_details as BT on BD.app_reference = BT.app_reference		
						 WHERE  (U.user_type='.B2B_USER.' ) AND BD.domain_origin='.get_domain_auth_id().' '.$condition.'
						 order by BD.created_datetime desc, BD.origin desc limit '.$offset.', '.$limit;		

				//echo $bd_query;die;	
						 
			$booking_details	= $this->db->query($bd_query)->result_array();
			//echo debug($bd_query); 			exit;
			$app_reference_ids = $this->booking_data_formatter->implode_app_reference_ids($booking_details);
			if(empty($app_reference_ids) == false) {
				//Itinerary Details
				$id_query = 'select * from `activity_booking_passenger_details`  AS ID
							WHERE ID.app_reference IN ('.$app_reference_ids.')';
				//Transaction Details
				$td_query = 'select * from activity_booking_transaction_details AS TD
							WHERE TD.app_reference IN ('.$app_reference_ids.')';
				//Customer and Ticket Details
				// $cd_query = 'select CD.*,FPTI.TicketId,FPTI.TicketNumber,FPTI.IssueDate,FPTI.Fare,FPTI.SegmentAdditionalInfo
				// 			from flight_booking_passenger_details AS CD
				// 			left join flight_passenger_ticket_info FPTI on CD.origin=FPTI.passenger_fk
				// 			WHERE CD.flight_booking_transaction_details_fk IN
				// 			(select TD.origin from flight_booking_transaction_details AS TD
				// 			WHERE TD.app_reference IN ('.$app_reference_ids.'))';
				//Cancellation Details
				// $cancellation_details_query = 'select FCD.*
				// 		from flight_booking_passenger_details AS CD
				// 		left join flight_cancellation_details AS FCD ON FCD.passenger_fk=CD.origin
				// 		WHERE CD.flight_booking_transaction_details_fk IN
				// 		(select TD.origin from flight_booking_transaction_details AS TD
				// 		WHERE TD.app_reference IN ('.$app_reference_ids.'))';
				//$payment_details_query = '';
				$booking_itinerary_details	= $this->db->query($id_query)->result_array();
				//$booking_customer_details	= $this->db->query($cd_query)->result_array();
				$booking_transaction_details = $this->db->query($td_query)->result_array();
				//$cancellation_details = $this->db->query($cancellation_details_query)->result_array();
				//$payment_details = $this->db->query($payment_details_query)->result_array();
			}
	
			$response['data']['booking_details']			= $booking_details;
			$response['data']['booking_itinerary_details']	= $booking_itinerary_details;
			$response['data']['booking_transaction_details']	= $booking_transaction_details;
			// $response['data']['booking_customer_details']	= $booking_customer_details;
			// $response['data']['cancellation_details']	= $cancellation_details;
			//$response['data']['payment_details']	= $payment_details;
			return $response;
		}
	}
	function b2c_holidaycrs_report($condition=array(), $count=false, $offset=0, $limit=100000000000)
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
		$query = 'select count(distinct(BD.app_reference)) AS total_records from activity_booking_details BD
					left join user U on U.user_id = BD.created_by_id					
					left join activity ATV on ATV.package_id = BD.package_type
					join activity_booking_transaction_details as BT on BD.app_reference = BT.app_reference	
					where ATV.supplier_id='.$GLOBALS['CI']->entity_user_id.' AND BD.domain_origin='.get_domain_auth_id().''.$condition;
			$data = $this->db->query($query)->row_array();
			return $data['total_records'];
		} else {
			$this->load->library('booking_data_formatter');
			$response['status'] = SUCCESS_STATUS;
			$response['data'] = array();
			$booking_itinerary_details	= array();
			$booking_customer_details	= array();
			$cancellation_details = array();
			$bd_query = 'select BD.* ,BT.*,U.agency_name,U.first_name,U.last_name,ATV.package_name,ATV.package_location from activity_booking_details AS BD
					     left join user U on BD.created_by_id =U.user_id
					     left join activity ATV on ATV.package_id = BD.package_type 
					     join activity_booking_transaction_details as BT on BD.app_reference = BT.app_reference						     
						 WHERE ATV.supplier_id='.$GLOBALS['CI']->entity_user_id.'  AND BD.domain_origin='.get_domain_auth_id().' '.$condition.'
						 order by BD.created_datetime desc, BD.origin desc limit '.$offset.', '.$limit;
			$booking_details = $this->db->query($bd_query)->result_array();
			$app_reference_ids = $this->booking_data_formatter->implode_app_reference_ids($booking_details);
			if(empty($app_reference_ids) == false) {
				/*$id_query = 'select * from sightseeing_booking_itinerary_details AS ID 
							WHERE ID.app_reference IN ('.$app_reference_ids.')';*/
				$cd_query = 'select * from activity_booking_passenger_details AS CD 
							WHERE CD.app_reference IN ('.$app_reference_ids.')';
				/*$cancellation_details_query = 'select * from sightseeing_cancellation_details AS HCD 
							WHERE HCD.app_reference IN ('.$app_reference_ids.')';*/
				// $booking_itinerary_details	= $this->db->query($id_query)->result_array();
				$booking_customer_details	= $this->db->query($cd_query)->result_array();
				// $cancellation_details	= $this->db->query($cancellation_details_query)->result_array();
			}
			$response['data']['booking_details']			= $booking_details;
			// $response['data']['booking_itinerary_details']	= $booking_itinerary_details;
			$response['data']['booking_customer_details']	= $booking_customer_details;
			// $response['data']['cancellation_details']	= $cancellation_details;
			return $response;
		}
	}
	 function emulate_user($user_id){

    	 $query = 'select first_name,last_name,user_id,agency_name from user where  user_id = "'.$user_id.'"  ';
    	   $emulate_details = $this->db->query($query)->row_array();
    	     $response['data']['emulate_details'] = $emulate_details;
    	      return $response;

    }
    
	function emulate_b2c_holiday_report($condition=array(), $count=false, $offset=0, $limit=100000000000)
	{

		$condition = $this->custom_db->get_custom_condition($condition);
		//$b2c_condition_array = array('U.user_type', '=', B2C_USER, ' OR ', 'BD.created_by_id', '=', 0);
		
		//BT, CD, ID


		 
		 // debug($condition);exit();

		if(isset($condition) == true)
		{
			$offset = 0;
		}else{
			
			$offset = $offset;
		}


		if ($count) {


  
			$query = 'select count(distinct(BD.app_reference)) AS total_records from activity_booking_details BD
					left join user U on U.user_id = BD.created_by_id
					left join user_type UT on UT.origin = U.user_type
					join activity_booking_transaction_details as BT on BD.app_reference = BT.app_reference	
					where (U.user_type='.B2B_USER.' OR BD.created_by_id = 0) AND BD.emulate_booking = 1 AND BD.domain_origin='.get_domain_auth_id().''.$condition;
			 //debug($query);exit;
			
			$data = $this->db->query($query)->row_array();
			// debug($data); exit;
			return $data['total_records'];

		} else {
			$this->load->library('booking_data_formatter');
			$response['status'] = SUCCESS_STATUS;
			$response['data'] = array();
			$booking_itinerary_details	= array();
			$booking_customer_details	= array();
			$booking_transaction_details = array();
			$cancellation_details = array();
			$payment_details = array();
			//Booking Details
			$bd_query = 'select BD.* ,U.user_name,U.first_name,U.last_name from activity_booking_details AS BD
					     left join user U on U.user_id = BD.created_by_id
					     left join user_type UT on UT.origin = U.user_type
					     join activity_booking_transaction_details as BT on BD.app_reference = BT.app_reference		
						 WHERE  (U.user_type='.B2B_USER.' OR BD.created_by_id = 0) AND BD.emulate_booking = 1 AND BD.domain_origin='.get_domain_auth_id().' '.$condition.'
						 order by BD.created_datetime desc, BD.origin desc limit '.$offset.', '.$limit;		

					
						 
			$booking_details	= $this->db->query($bd_query)->result_array();
			//echo debug($bd_query); 			exit;
			$app_reference_ids = $this->booking_data_formatter->implode_app_reference_ids($booking_details);
			if(empty($app_reference_ids) == false) {
				//Itinerary Details
				$id_query = 'select * from `activity_booking_passenger_details`  AS ID
							WHERE ID.app_reference IN ('.$app_reference_ids.')';
				//Transaction Details
				$td_query = 'select * from activity_booking_transaction_details AS TD
							WHERE TD.app_reference IN ('.$app_reference_ids.')';
				//Customer and Ticket Details
				// $cd_query = 'select CD.*,FPTI.TicketId,FPTI.TicketNumber,FPTI.IssueDate,FPTI.Fare,FPTI.SegmentAdditionalInfo
				// 			from flight_booking_passenger_details AS CD
				// 			left join flight_passenger_ticket_info FPTI on CD.origin=FPTI.passenger_fk
				// 			WHERE CD.flight_booking_transaction_details_fk IN
				// 			(select TD.origin from flight_booking_transaction_details AS TD
				// 			WHERE TD.app_reference IN ('.$app_reference_ids.'))';
				//Cancellation Details
				// $cancellation_details_query = 'select FCD.*
				// 		from flight_booking_passenger_details AS CD
				// 		left join flight_cancellation_details AS FCD ON FCD.passenger_fk=CD.origin
				// 		WHERE CD.flight_booking_transaction_details_fk IN
				// 		(select TD.origin from flight_booking_transaction_details AS TD
				// 		WHERE TD.app_reference IN ('.$app_reference_ids.'))';
				//$payment_details_query = '';
				$booking_itinerary_details	= $this->db->query($id_query)->result_array();
				//$booking_customer_details	= $this->db->query($cd_query)->result_array();
				$booking_transaction_details = $this->db->query($td_query)->result_array();
				//$cancellation_details = $this->db->query($cancellation_details_query)->result_array();
				//$payment_details = $this->db->query($payment_details_query)->result_array();
			}
	
			$response['data']['booking_details']			= $booking_details;
			$response['data']['booking_itinerary_details']	= $booking_itinerary_details;
			$response['data']['booking_transaction_details']	= $booking_transaction_details;
			// $response['data']['booking_customer_details']	= $booking_customer_details;
			// $response['data']['cancellation_details']	= $cancellation_details;
			//$response['data']['payment_details']	= $payment_details;
			return $response;
		}
	}

		public function activity_cancelation($table, $data, $where){
				debug($table);exit;
					$query = $this->db->set($data)
					->where($where)
					->update($table);
					debug($this->db->last_query());exit;
					return TRUE;	
		}

		public function 	activity_book_cancelation($table, $data, $where){
					$query = $this->db->set($data)
					->where($where)
					->update($table);
					// ->update('package_booking_details');
					return TRUE;	
		}
		function b2c_holiday_report_filter($condition=array(), $count=false, $offset=0, $limit=100000000000)
	{

		//$condition = $this->custom_db->get_custom_condition($condition);
		//$b2c_condition_array = array('U.user_type', '=', B2C_USER, ' OR ', 'BD.created_by_id', '=', 0);
		
		//BT, CD, ID


		 
		 // debug($condition);exit();

		if(isset($condition) == true)
		{
			$offset = 0;
		}else{
			
			$offset = $offset;
		}


		if ($count) {


  
			$query = 'select count(distinct(BD.app_reference)) AS total_records from activity_booking_details BD
					left join user U on U.user_id = BD.created_by_id
					left join user_type UT on UT.origin = U.user_type
					join activity_booking_transaction_details as BT on BD.app_reference = BT.app_reference	
					where (U.user_type='.B2B_USER.' OR BD.created_by_id = 0) AND BD.domain_origin='.get_domain_auth_id().' AND '.$condition;
			// debug($query);exit;
			
			$data = $this->db->query($query)->row_array();
			return $data['total_records'];

		} else {
			$this->load->library('booking_data_formatter');
			$response['status'] = SUCCESS_STATUS;
			$response['data'] = array();
			$booking_itinerary_details	= array();
			$booking_customer_details	= array();
			$booking_transaction_details = array();
			$cancellation_details = array();
			$payment_details = array();
			//Booking Details
			$bd_query = 'select BD.* ,U.user_name,U.first_name,U.last_name from activity_booking_details AS BD
					     left join user U on U.user_id = BD.created_by_id
					     left join user_type UT on UT.origin = U.user_type
					     join activity_booking_transaction_details as BT on BD.app_reference = BT.app_reference		
						 WHERE  (U.user_type='.B2B_USER.' OR BD.created_by_id = 0) AND BD.domain_origin='.get_domain_auth_id().' AND '.$condition.'
						 order by BD.created_datetime desc, BD.origin desc limit '.$offset.', '.$limit;		

					
						 
			$booking_details	= $this->db->query($bd_query)->result_array();
			//echo debug($bd_query); 			exit;
			$app_reference_ids = $this->booking_data_formatter->implode_app_reference_ids($booking_details);
			if(empty($app_reference_ids) == false) {
				//Itinerary Details
				$id_query = 'select * from `activity_booking_passenger_details`  AS ID
							WHERE ID.app_reference IN ('.$app_reference_ids.')';
				//Transaction Details
				$td_query = 'select * from activity_booking_transaction_details AS TD
							WHERE TD.app_reference IN ('.$app_reference_ids.')';
				//Customer and Ticket Details
				// $cd_query = 'select CD.*,FPTI.TicketId,FPTI.TicketNumber,FPTI.IssueDate,FPTI.Fare,FPTI.SegmentAdditionalInfo
				// 			from flight_booking_passenger_details AS CD
				// 			left join flight_passenger_ticket_info FPTI on CD.origin=FPTI.passenger_fk
				// 			WHERE CD.flight_booking_transaction_details_fk IN
				// 			(select TD.origin from flight_booking_transaction_details AS TD
				// 			WHERE TD.app_reference IN ('.$app_reference_ids.'))';
				//Cancellation Details
				// $cancellation_details_query = 'select FCD.*
				// 		from flight_booking_passenger_details AS CD
				// 		left join flight_cancellation_details AS FCD ON FCD.passenger_fk=CD.origin
				// 		WHERE CD.flight_booking_transaction_details_fk IN
				// 		(select TD.origin from flight_booking_transaction_details AS TD
				// 		WHERE TD.app_reference IN ('.$app_reference_ids.'))';
				//$payment_details_query = '';
				$booking_itinerary_details	= $this->db->query($id_query)->result_array();
				//$booking_customer_details	= $this->db->query($cd_query)->result_array();
				$booking_transaction_details = $this->db->query($td_query)->result_array();
				//$cancellation_details = $this->db->query($cancellation_details_query)->result_array();
				//$payment_details = $this->db->query($payment_details_query)->result_array();
			}
	
			$response['data']['booking_details']			= $booking_details;
			$response['data']['booking_itinerary_details']	= $booking_itinerary_details;
			$response['data']['booking_transaction_details']	= $booking_transaction_details;
			// $response['data']['booking_customer_details']	= $booking_customer_details;
			// $response['data']['cancellation_details']	= $cancellation_details;
			//$response['data']['payment_details']	= $payment_details;
			return $response;
		}
	}

	function emulated_b2c_holiday_report_filter($condition=array(), $count=false, $offset=0, $limit=100000000000)
	{

		//$condition = $this->custom_db->get_custom_condition($condition);
		//$b2c_condition_array = array('U.user_type', '=', B2C_USER, ' OR ', 'BD.created_by_id', '=', 0);
		
		//BT, CD, ID


		 
		 // debug($condition);exit();

		if(isset($condition) == true)
		{
			$offset = 0;
		}else{
			
			$offset = $offset;
		}


		if ($count) {


  
			$query = 'select count(distinct(BD.app_reference)) AS total_records from activity_booking_details BD
					left join user U on U.user_id = BD.created_by_id
					left join user_type UT on UT.origin = U.user_type
					join activity_booking_transaction_details as BT on BD.app_reference = BT.app_reference	
					where (U.user_type='.B2B_USER.' OR BD.created_by_id = 0) AND BD.emulate_booking = 1 AND BD.domain_origin='.get_domain_auth_id().' AND '.$condition;
			// debug($query);exit;
			
			$data = $this->db->query($query)->row_array();
			return $data['total_records'];

		} else {
			$this->load->library('booking_data_formatter');
			$response['status'] = SUCCESS_STATUS;
			$response['data'] = array();
			$booking_itinerary_details	= array();
			$booking_customer_details	= array();
			$booking_transaction_details = array();
			$cancellation_details = array();
			$payment_details = array();
			//Booking Details
			$bd_query = 'select BD.* ,U.user_name,U.first_name,U.last_name from activity_booking_details AS BD
					     left join user U on U.user_id = BD.created_by_id
					     left join user_type UT on UT.origin = U.user_type
					     join activity_booking_transaction_details as BT on BD.app_reference = BT.app_reference		
						 WHERE  (U.user_type='.B2B_USER.' OR BD.created_by_id = 0) AND BD.emulate_booking = 1 AND BD.domain_origin='.get_domain_auth_id().' AND '.$condition.'
						 order by BD.created_datetime desc, BD.origin desc limit '.$offset.', '.$limit;		

					
						 
			$booking_details	= $this->db->query($bd_query)->result_array();
			//echo debug($bd_query); 			exit;
			$app_reference_ids = $this->booking_data_formatter->implode_app_reference_ids($booking_details);
			if(empty($app_reference_ids) == false) {
				//Itinerary Details
				$id_query = 'select * from `activity_booking_passenger_details`  AS ID
							WHERE ID.app_reference IN ('.$app_reference_ids.')';
				//Transaction Details
				$td_query = 'select * from activity_booking_transaction_details AS TD
							WHERE TD.app_reference IN ('.$app_reference_ids.')';
				//Customer and Ticket Details
				// $cd_query = 'select CD.*,FPTI.TicketId,FPTI.TicketNumber,FPTI.IssueDate,FPTI.Fare,FPTI.SegmentAdditionalInfo
				// 			from flight_booking_passenger_details AS CD
				// 			left join flight_passenger_ticket_info FPTI on CD.origin=FPTI.passenger_fk
				// 			WHERE CD.flight_booking_transaction_details_fk IN
				// 			(select TD.origin from flight_booking_transaction_details AS TD
				// 			WHERE TD.app_reference IN ('.$app_reference_ids.'))';
				//Cancellation Details
				// $cancellation_details_query = 'select FCD.*
				// 		from flight_booking_passenger_details AS CD
				// 		left join flight_cancellation_details AS FCD ON FCD.passenger_fk=CD.origin
				// 		WHERE CD.flight_booking_transaction_details_fk IN
				// 		(select TD.origin from flight_booking_transaction_details AS TD
				// 		WHERE TD.app_reference IN ('.$app_reference_ids.'))';
				//$payment_details_query = '';
				$booking_itinerary_details	= $this->db->query($id_query)->result_array();
				//$booking_customer_details	= $this->db->query($cd_query)->result_array();
				$booking_transaction_details = $this->db->query($td_query)->result_array();
				//$cancellation_details = $this->db->query($cancellation_details_query)->result_array();
				//$payment_details = $this->db->query($payment_details_query)->result_array();
			}
	
			$response['data']['booking_details']			= $booking_details;
			$response['data']['booking_itinerary_details']	= $booking_itinerary_details;
			$response['data']['booking_transaction_details']	= $booking_transaction_details;
			// $response['data']['booking_customer_details']	= $booking_customer_details;
			// $response['data']['cancellation_details']	= $cancellation_details;
			//$response['data']['payment_details']	= $payment_details;
			return $response;
		}
	}
	function staff_holiday_report_filter($condition=array(), $count=false, $offset=0, $limit=100000000000)
	{

		//$condition = $this->custom_db->get_custom_condition($condition);
		//$b2c_condition_array = array('U.user_type', '=', B2C_USER, ' OR ', 'BD.created_by_id', '=', 0);
		
		//BT, CD, ID


		 
		 // debug($condition);exit();

		if(isset($condition) == true)
		{
			$offset = 0;
		}else{
			
			$offset = $offset;
		}


		if ($count) {


  
			$query = 'select count(distinct(BD.app_reference)) AS total_records from activity_booking_details BD
					left join user U on U.user_id = BD.created_by_id
					left join user_type UT on UT.origin = U.user_type
					join activity_booking_transaction_details as BT on BD.app_reference = BT.app_reference	
					where (U.user_type='.STAFF.' OR BD.created_by_id = 0) AND BD.domain_origin='.get_domain_auth_id().' AND '.$condition;
			// debug($query);exit;
			
			$data = $this->db->query($query)->row_array();
			return $data['total_records'];

		} else {
			$this->load->library('booking_data_formatter');
			$response['status'] = SUCCESS_STATUS;
			$response['data'] = array();
			$booking_itinerary_details	= array();
			$booking_customer_details	= array();
			$booking_transaction_details = array();
			$cancellation_details = array();
			$payment_details = array();
			//Booking Details
			$bd_query = 'select BD.* ,U.user_name,U.first_name,U.last_name from activity_booking_details AS BD
					     left join user U on U.user_id = BD.created_by_id
					     left join user_type UT on UT.origin = U.user_type
					     join activity_booking_transaction_details as BT on BD.app_reference = BT.app_reference		
						 WHERE  (U.user_type='.STAFF.' OR BD.created_by_id = 0) AND BD.domain_origin='.get_domain_auth_id().' AND '.$condition.'
						 order by BD.created_datetime desc, BD.origin desc limit '.$offset.', '.$limit;		

					
						 
			$booking_details	= $this->db->query($bd_query)->result_array();
			//echo debug($bd_query); 			exit;
			$app_reference_ids = $this->booking_data_formatter->implode_app_reference_ids($booking_details);
			if(empty($app_reference_ids) == false) {
				//Itinerary Details
				$id_query = 'select * from `activity_booking_passenger_details`  AS ID
							WHERE ID.app_reference IN ('.$app_reference_ids.')';
				//Transaction Details
				$td_query = 'select * from activity_booking_transaction_details AS TD
							WHERE TD.app_reference IN ('.$app_reference_ids.')';
				//Customer and Ticket Details
				// $cd_query = 'select CD.*,FPTI.TicketId,FPTI.TicketNumber,FPTI.IssueDate,FPTI.Fare,FPTI.SegmentAdditionalInfo
				// 			from flight_booking_passenger_details AS CD
				// 			left join flight_passenger_ticket_info FPTI on CD.origin=FPTI.passenger_fk
				// 			WHERE CD.flight_booking_transaction_details_fk IN
				// 			(select TD.origin from flight_booking_transaction_details AS TD
				// 			WHERE TD.app_reference IN ('.$app_reference_ids.'))';
				//Cancellation Details
				// $cancellation_details_query = 'select FCD.*
				// 		from flight_booking_passenger_details AS CD
				// 		left join flight_cancellation_details AS FCD ON FCD.passenger_fk=CD.origin
				// 		WHERE CD.flight_booking_transaction_details_fk IN
				// 		(select TD.origin from flight_booking_transaction_details AS TD
				// 		WHERE TD.app_reference IN ('.$app_reference_ids.'))';
				//$payment_details_query = '';
				$booking_itinerary_details	= $this->db->query($id_query)->result_array();
				//$booking_customer_details	= $this->db->query($cd_query)->result_array();
				$booking_transaction_details = $this->db->query($td_query)->result_array();
				//$cancellation_details = $this->db->query($cancellation_details_query)->result_array();
				//$payment_details = $this->db->query($payment_details_query)->result_array();
			}
	
			$response['data']['booking_details']			= $booking_details;
			$response['data']['booking_itinerary_details']	= $booking_itinerary_details;
			$response['data']['booking_transaction_details']	= $booking_transaction_details;
			// $response['data']['booking_customer_details']	= $booking_customer_details;
			// $response['data']['cancellation_details']	= $cancellation_details;
			//$response['data']['payment_details']	= $payment_details;
			return $response;
		}
	}

	function get_booking_details($app_reference, $booking_source, $booking_status='')
    {
    	if($booking_status!=''){
    	$booking_status=$booking_status;
	    }else{
	    	$booking_status="BOOKING_CONFIRMED";
	    }
        $response['status'] = FAILURE_STATUS;
        $response['data'] = array();

        if($booking_source==HOTELBED_ACTIVITIES_BOOKING_SOURCE)
        {
        	    $bd_query = 'select * from sightseeing_booking_details AS BD WHERE BD.app_reference like '.$this->db->escape($app_reference);
        if (empty($booking_source) == false) {
            $bd_query .= '  AND BD.booking_source = '.$this->db->escape($booking_source);
        }
        if (empty($booking_status) == false) {
            $bd_query .= ' AND BD.status = '.$this->db->escape($booking_status);
        }
        $id_query = 'select * from sightseeing_booking_itinerary_details AS ID WHERE ID.app_reference='.$this->db->escape($app_reference);
        $cd_query = 'select * from sightseeing_booking_pax_details AS CD WHERE CD.app_reference='.$this->db->escape($app_reference);
        $cancellation_details_query = 'select HCD.* from sightseeing_cancellation_details AS HCD WHERE HCD.app_reference='.$this->db->escape($app_reference);
        $response['data']['booking_details']            = $this->db->query($bd_query)->result_array();
       // debug($this->db->last_query());exit;
        $response['data']['booking_itinerary_details']  = $this->db->query($id_query)->result_array();
        $response['data']['booking_customer_details']   = $this->db->query($cd_query)->result_array();
        $response['data']['cancellation_details']   = $this->db->query($cancellation_details_query)->result_array();

// echo $bd_query;exit();
        // debug($response);exit();
        if (valid_array($response['data']['booking_details']) == true and valid_array($response['data']['booking_itinerary_details']) == true and valid_array($response['data']['booking_customer_details']) == true) {
            $response['status'] = SUCCESS_STATUS;
        }

        }
        else
        {
        	 $bd_query = 'select * from activity_booking_details AS BD WHERE BD.app_reference like '.$this->db->escape($app_reference);
        if (empty($booking_source) == false) {
            $bd_query .= '  AND BD.booking_source = '.$this->db->escape($booking_source);
        }
        if (empty($booking_status) == false) {
            $bd_query .= ' AND BD.status = '.$this->db->escape($booking_status);
        }
        $id_query = 'select * from sightseeing_booking_itinerary_details AS ID WHERE ID.app_reference='.$this->db->escape($app_reference);
        $cd_query = 'select * from activity_booking_passenger_details AS CD WHERE CD.app_reference='.$this->db->escape($app_reference);
        $cancellation_details_query = 'select HCD.* from sightseeing_cancellation_details AS HCD WHERE HCD.app_reference='.$this->db->escape($app_reference);
        $response['data']['booking_details']            = $this->db->query($bd_query)->result_array();

        $response['data']['booking_itinerary_details']  = $this->db->query($id_query)->result_array();

        $response['data']['booking_customer_details']   = $this->db->query($cd_query)->result_array();
        $response['data']['cancellation_details']   = $this->db->query($cancellation_details_query)->result_array();


        	if (valid_array($response['data']['booking_details']) == true and  valid_array($response['data']['booking_customer_details']) == true) {
            $response['status'] = SUCCESS_STATUS;
        }
        }
    


        // debug($response);exit();
        return $response;
    }


    function get_booking_details_activity($app_reference, $booking_source, $booking_status='')
    {
    	if($booking_status!=''){
    	$booking_status=$booking_status;
	    }else{
	    	$booking_status="BOOKING_CONFIRMED";
	    }
        $response['status'] = FAILURE_STATUS;
        $response['data'] = array();

       
        
        	 $bd_query = 'select * from activity_booking_details AS BD WHERE BD.app_reference like '.$this->db->escape($app_reference);
        if (empty($booking_source) == false) {
            $bd_query .= '  AND BD.booking_source = '.$this->db->escape($booking_source);
        }
        if (empty($booking_status) == false) {
            $bd_query .= ' AND BD.status = '.$this->db->escape($booking_status);
        }
       // $id_query = 'select * from sightseeing_booking_itinerary_details AS ID WHERE ID.app_reference='.$this->db->escape($app_reference);
        $cd_query = 'select * from activity_booking_passenger_details AS CD WHERE CD.app_reference='.$this->db->escape($app_reference);
       // $cancellation_details_query = 'select HCD.* from sightseeing_cancellation_details AS HCD WHERE HCD.app_reference='.$this->db->escape($app_reference);
        $response['data']['booking_details']            = $this->db->query($bd_query)->result_array();
        //debug($this->db->last_query());
		//debug($response['data']['booking_details']);exit;

        //$response['data']['booking_itinerary_details']  = $this->db->query($id_query)->result_array();

        $response['data']['booking_customer_details']   = $this->db->query($cd_query)->result_array();
       // $response['data']['cancellation_details']   = $this->db->query($cancellation_details_query)->result_array();


        	if (valid_array($response['data']['booking_details']) == true and  valid_array($response['data']['booking_customer_details']) == true) {
            $response['status'] = SUCCESS_STATUS;
        }
        
    


        // debug($response);exit();
        return $response;
    }
    	public function getPackage($package_id){
		$this->db->select("*");
		$this->db->from("activity");
		$this->db->where('package_id',$package_id);
		$query=$this->db->get();
		if($query->num_rows()){
			return $query->row();
		}else{
			return array();
		}
	}

	function b2b_activity_report($condition=array(), $count=false, $offset=0, $limit=100000000000)
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
		$query = 'select count(distinct(BD.app_reference)) AS total_records from activity_booking_details BD
					left join user U on U.user_id = BD.created_by_id
					left join user_type UT on UT.origin = U.user_type
					join activity_booking_transaction_details as BT on BD.app_reference = BT.app_reference	
					where (U.user_type='.B2B_USER.' OR BD.created_by_id = 0) AND BD.domain_origin='.get_domain_auth_id().''.$condition;
			$data = $this->db->query($query)->row_array();
			return $data['total_records'];
		} else {
			$this->load->library('booking_data_formatter');
			$response['status'] = SUCCESS_STATUS;
			$response['data'] = array();
			$booking_itinerary_details	= array();
			$booking_customer_details	= array();
			$cancellation_details = array();
			$bd_query = 'select BD.* ,U.agency_name,U.first_name,U.last_name from sightseeing_booking_details AS BD
					     left join user U on BD.created_by_id =U.user_id 					     
						 WHERE  U.user_type='.B2B_USER.' AND BD.domain_origin='.get_domain_auth_id().' '.$condition.'
						 order by BD.created_datetime desc, BD.origin desc limit '.$offset.', '.$limit;
			$booking_details = $this->db->query($bd_query)->result_array();
			$app_reference_ids = $this->booking_data_formatter->implode_app_reference_ids($booking_details);
			if(empty($app_reference_ids) == false) {
				$id_query = 'select * from sightseeing_booking_itinerary_details AS ID 
							WHERE ID.app_reference IN ('.$app_reference_ids.')';
				$cd_query = 'select * from sightseeing_booking_pax_details AS CD 
							WHERE CD.app_reference IN ('.$app_reference_ids.')';
				$cancellation_details_query = 'select * from sightseeing_cancellation_details AS HCD 
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

	public function get_countries_currency() {
		$this->db->limit ( 1000 );
		$this->db->order_by ( "en_name", "asc" );
		$qur = $this->db->get ( "country_list" );
		return $qur->result ();
	}

	public function get_country_name_currency($id) {
		$this->db->where ( "country_list",$id );
		$qur = $this->db->get ( "country_list" );
		return $qur->result ();
	}
	public function get_activity_cancel_charge($id) {
		$this->db->select ( "*" );
		$this->db->from ( "activity_cancellation_price" );
		$this->db->where ( 'cancel_id', $id );
		$query = $this->db->get ();
		if ($query->num_rows > 0) {
			
			return $query->result ();
		}
	}
	public function check_seasonality_details($tran_id ,$seasonality_from,$seasonality_to='') {
		$this->db->select('*');
		$this->db->from('activity_cancellation');
		$this->db->where('v_id',$tran_id);
		if($seasonality_to!='')
		{
		$this->db->where('start_date <=',$seasonality_to);
		$this->db->where('expiry_date >=',$seasonality_to);
		}else{
		$this->db->where('start_date <=',$seasonality_from);
		$this->db->where('expiry_date >=',$seasonality_from);
		}
		$res = $this->db->get();
		return $res->result();
	}
	public function activity_subtheme()
	{
		$query = 'select * from activity_subtheme order by id desc'; 
		$result = $this->db->query($query);
	    return  $result->result_array();
	}
	public function activity_amenties()
	{
		$query = 'select * from activity_amenties order by id desc'; 
		$result = $this->db->query($query);
	    return  $result->result_array();
	}
	public function health_instructions($condition='')
	{
		$where = '';
		if(!empty($condition)){
			$where = 'where status=1';
		}
		$query = 'select * from health_instructions '.$where.' order by id desc'; 
		$result = $this->db->query($query);
	    return  $result->result_array();
	}
	public function query_run($query) {
		$result = $this->db->query($query);
		if(!$result)
		{
			return FALSE;		
		}else
		{
			return TRUE;
		}
	}
	public function record_activation($table,$id,$status) {
		$query = "update ".$table." set status='$status' where id='$id'";
		$result = $this->db->query($query);
	    return  $result;
	}
	public function record_delete($table,$id) {
		$query = "delete from ".$table." where id='$id'";
		$result = $this->db->query($query);
		// echo $this->db->query();exit;
	    return  $result;
	}
	public function get_theme_details($id) {
		$this->db->select ( "a.*,b.*" );
		$this->db->from ( "activity_subtheme as a" );
		$this->db->join ( 'sub_theme_activity as b', 'a.id = b.activity_theme_id','left' );
		$this->db->where('b.activity_theme_id',$id);
		$result = $this->db->get();
		return  $result->result_array();
	}
	public function get_sub_type_details($id) {
		$this->db->select ( "a.*,b.*" );
		$this->db->from ( "activity_types as a" );
		$this->db->join ( 'activity_sub_category as b', 'a.activity_types_id = b.activity_type_id','left' );
		$this->db->where('b.activity_type_id',$id);
		$result = $this->db->get();
		// echo $this->db->last_query();exit;
		return  $result->result_array();
	}
	public function get_excursion_type() {
	$query = ' select activity_types.activity_types_id from activity_types WHERE activity_types_id NOT IN (select activity_type_id from activity_sub_category)';
	$excursion_type	= $this->db->query($query)->result();
	return $excursion_type;
	}
	public function get_excursion_subtheme() {
	$query = ' select activity_subtheme.id from activity_subtheme WHERE id NOT IN (select activity_theme_id from sub_theme_activity)';
	$sub_theme	= $this->db->query($query)->result();
	return $sub_theme;
	}

	//Nationality Master 

	public function nationality_region()
	{
		$query = 'select * from all_nationality_region where created_by='.$this->entity_user_id.' and module="activity" order by name';
		$result = $this->db->query($query);
     	return  $result->result_array();
	}
	public function check_region_exist_all($tours_continent)
	{
		
		$this->db->select('*');
		$this->db->where('name',$tours_continent);
		$this->db->where('module','activity');
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
	//	$this->db->where('nc.created_by',$this->entity_user_id);
		$this->db->where('nc.module','activity');
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
		$query = 'select * from all_nationality_region where status = 1 and module="activity" and created_by='.$this->entity_user_id.' order by name'; 
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
		$this->db->where('module','activity');
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
		$this->db->where('module','activity');
		//$this->db->where('created_by',$this->entity_user_id);
		$query = $this->db->get('all_nationality_country');
	//	echo $this->db->last_query();die;
		if ( $query->num_rows > 0 ) {
			return $query->result();
		}else{
			return array();
		}	

		

	}
	//Nationality Master End
	
	function get_monthly_booking_summary($condition=array())
	{

		$condition[] = array('BD.module_type', '=', '"activity"');



		//Balu A
		$condition = $this->custom_db->get_custom_condition($condition);
		$query = 'select count(distinct(BD.app_reference)) AS total_booking, 
				sum(SBID.total_fare+BD.admin_markup+BD.agent_markup) as monthly_payment, sum(BD.admin_markup) as monthly_earning, 
				MONTH(BD.created_datetime) as month_number 
				from activity_booking_details AS BD
				left join user U on U.user_id = BD.created_by_id
				left join user_type UT on UT.origin = U.user_type
				join activity_booking_transaction_details AS SBID on BD.app_reference=SBID.app_reference
				where (U.user_type='.B2B_USER.' OR BD.created_by_id = 0) and (YEAR(BD.created_datetime) BETWEEN '.date('Y').' AND '.date('Y', strtotime('+1 year')).')  and BD.domain_origin='.get_domain_auth_id().' '.$condition.'
				GROUP BY YEAR(BD.created_datetime), 
				MONTH(BD.created_datetime)';

				//echo $query;exit; 
		return $this->db->query($query)->result_array();
	}



	public function change_publish_status($id, $status) {
		$data = array (
				'status' => $status 
		);
		$where = "package_id = " . $id;
		if ($this->db->update ( 'activity', $data, $where )) {
			return $status;
		} else {
			return '0';
		}
	}
	public function published_status_approve($id, $status) {
		$data = array (
				'approval_status' => $status,
		);

		if($status==2)
		{
			$data = array (
				'approval_status' => $status,
				'status'=>'0'
		);
		}

		$where = "package_id = " . $id;
		if ($this->db->update ( 'activity', $data, $where )) {
			return $status;
		} else {
			return '0';
		}
	}

}