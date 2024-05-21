<?php
class Package_Model extends CI_Model {
	public function __construct(){
		parent::__construct();
	}
	public function getAllPackages(){
		$this->db->select('*');
		$this->db->where('status', '1');
		$query = $this->db->get('package');
		if ( $query->num_rows > 0 ) {
			return $query->result();
		}else{
			return array();
		}
	}

	/**
	 *@param Top Destination Packages
	 */
	public function get_package_top_destination()
	{
		$this->db->select('*');
		$this->db->where('top_destination',ACTIVE);
		$query = $this->db->get('package');
		if ( $query->num_rows > 0 ) {
			$data['data'] = $query->result();
			$data['total'] = $query->num_rows;
			return $data;
		}else{
			return array('data' => '', 'total' => 0);
		}
	}
	public function getPageCaption($page_name) {
		$this->db->where('page_name', $page_name);
		return $this->db->get('page_captions');
	}
	public function get_contact(){
		$contact = $this->db->get('contact_details');
		return $contact->row();
	}
	/**
	 *get country name
	 **/
	public function getCountryName($id){
		$this->db->select("*");
		$this->db->from("country");
		$this->db->where('country_id',$id);
		$query=$this->db->get();
		if($query->num_rows()){
			return $query->row();
		}else{
			return array();
		}
	}

	/**
	 * get package itinerary
	 */
	public function getPackageItinerary($package_id){
		$this->db->select("*");
		$this->db->from("package_itinerary");
		$this->db->where('package_id',$package_id);
		$this->db->order_by('day','ASC');
		$query=$this->db->get();
		if($query->num_rows()){
			return $query->result();
		}else{
			return array();
		}
	}

	/**
	 * get package pricing policy
	 */
	public function getPackagePricePolicy($package_id){
		$this->db->select("*");
		$this->db->from("package_pricing_policy");
		$this->db->where('package_id',$package_id);
		$query=$this->db->get();
		if($query->num_rows()){
			return $query->row();
		}else{
			return array();
		}
	}

	/**
	 * get package traveller photos
	 */
	public function getTravellerPhotos($package_id){
		$this->db->select("*");
		$this->db->from("package_traveller_photos");
		$this->db->where('package_id',$package_id);
		$this->db->where('status','1');
		$query=$this->db->get();
		if($query->num_rows()){
			return $query->result();
		}else{
			return array();
		}
	}
	/*8
	 * get getPackageCancelPolicy
	 */
	public function getPackageCancelPolicy($package_id){
		$this->db->select("*");
		$this->db->from("package_cancellation");
		$this->db->where('package_id',$package_id);
		$query=$this->db->get();
		if($query->num_rows()){
			return $query->row();
		}else{
			return array();
		}
	}
	/**
	 * getPackage
	 */
	public function getPackage($package_id){
		$this->db->select("*");
		$this->db->from("package");
		$this->db->where('package_id',$package_id);
		$query=$this->db->get();
		if($query->num_rows()){
			return $query->row();
		}else{
			return array();
		}
	}
	public function getPackageCountries_new(){
    	$data = 'select C.name AS country_name, C.country_id as country_id FROM country C';
    
    	return $this->db->query($data)->result();
    	/*$this->db->select('package_country');
    	 $this->db->from('package'); 
    	 $this->db->group_by('package_country'); 
		$query = $this->db->get();
		if($query->num_rows()){
			return $query->result();
		}else{
			return array();
		}*/
    }

	public function saveEnquiry($data){
		$this->db->insert('package_enquiry',$data);
		return $this->db->insert_id();
	}

	public function getPackageCountries(){
		$data = 'select package_country, C.name AS country_name FROM package P, country C WHERE P.package_country=C.country_id';
    	return $this->db->query($data)->result();
	}
	public function getPackageTypes(){
		$this->db->select("*");
		$this->db->from("package_types");
		$query=$this->db->get();
		if($query->num_rows()){
			return $query->result();
		}else{
			return array();
		}
	}
	public function search($c,$p,$d,$b,$dmn_list_fk){
		$this->db->select("*");
		$this->db->from("package");
		$this->db->like('package_country', $c,'both');
		$this->db->like('package_type', $p,'both');
		if($d){
			$this->db->where($d);
		}else{
			$this->db->like('duration', $d,'both');
		}
		if($b){
			$this->db->where($b);
		}else{
			$this->db->like('price', $b,'both');
		}
		$this->db->where('domain_list_fk',$dmn_list_fk);
		$query=$this->db->get();
		//echo $this->db->last_query();
		//exit;
		if($query->num_rows()){
			return $query->result();
		}else{
			return array();
		}
	}


	function add_user_rating($arr_data)
	{
		$pkg_id=$arr_data['package_id'];
		$res=$this->db->insert('package_rating',$arr_data);

		if($res==true){
			 
			$this->db->select('rating');
			$this->db->where('package_id',$pkg_id);
			$res1=$this->db->get('package_rating');
			if($res1->num_rows()>0)
			{  // print_r($res1);
				$tot_no=count($res1->result());
				$results=$res1->result();
				//   sum=0;
				foreach($results as $r)
				{
					$sum+=$r->rating;
				}
				$rating=$sum/$tot_no;

				$da=array('rating'=> ceil($rating));
				$this->db->where('package_id',$pkg_id);
				$this->db->update('package',$da);

			}
			 
		}

	}

	public function tour_data($tour_id) {

		$query = "select * from package_itinerary as pi join package as p on pi.package_id=p.package_id where pi.package_id='$tour_id'";
        $exe   = $this->db->query($query);
       if($exe->num_rows()>0)
       {
       	$data=$exe->result_array();
       	return $data;
       }
	}

	function save_holiday($table,$data)
	{
		// error_reporting(E_ALL);
$this->db->insert('package_booking_details',$data);
    if($this->db->affected_rows() != 1) 
    return 'false' ;
 else
 return 'true';



// $query="insert into package_booking_details values($data['domain_origin'],$data['status'],$data['app_reference'],$data['booking_source'],$data['package_type'],$data['phone'],$data['email'],$data['date_of_travel'],$data['payment_mode'],$data['currency'],$data['creates_by_id'],$data['created_datetime'])";
// $res=$this->db->query($query);
	}

	function get_monthly_booking_summary($condition=array())
	{
		//Jaganath
		$condition = $this->custom_db->get_custom_condition($condition);
		$query = 'select count(distinct(BD.app_reference)) AS total_booking, 
				PBTD.total_fare as monthly_payment, 
				MONTH(BD.created_datetime) as month_number 
				from package_booking_details AS BD
				join  package_booking_transaction_details AS PBTD on BD.app_reference=PBTD.app_reference
				where (YEAR(BD.created_datetime) BETWEEN '.date('Y').' AND '.date('Y', strtotime('+1 year')).')  and BD.domain_origin='.get_domain_auth_id().' '.$condition.'
				GROUP BY YEAR(BD.created_datetime), 
				MONTH(BD.created_datetime)';
		return $this->db->query($query)->result_array();
	}




	function b2c_holiday_report($condition=array(), $count=false, $offset=0, $limit=100000000000)
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

			// debug($count);exit();
		if ($count) {
			
			// echo debug($condition);exit;
			$query = 'select count(distinct(BD.app_reference)) AS total_records from package_booking_details BD
					 left join package P on P.package_id = BD.package_type
					left join user U on U.user_id = BD.created_by_id
					left join user_type UT on UT.origin = U.user_type
					join package_booking_transaction_details as BT on BD.app_reference = BT.app_reference	
					where P.supplier_id='."'".$this->entity_user_id."'".' AND (U.user_type='.B2C_USER.' OR BD.created_by_id = 0)  AND  BD.domain_origin='.get_domain_auth_id().''.$condition;
			// echo debug($query);exit;
			
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
			$bd_query = 'select P.package_name,P.module_type,BD.*,U.user_name,U.first_name,U.last_name from package_booking_details AS BD
					     left join package P on P.package_id = BD.package_type
					     left join user U on U.user_id = BD.created_by_id
					     left join user_type UT on UT.origin = U.user_type
					     join package_booking_transaction_details as BT on BD.app_reference = BT.app_reference		
						 WHERE  P.supplier_id='."'".$this->entity_user_id."'".' AND (U.user_type='.B2C_USER.' OR BD.created_by_id = 0) AND BD.domain_origin='.get_domain_auth_id().' '.$condition.'
						 order by BD.created_datetime desc, BD.origin desc limit '.$offset.', '.$limit;		

					
						 
			$booking_details	= $this->db->query($bd_query)->result_array();
			// debug($booking_details);		exit;
			$app_reference_ids = $this->booking_data_formatter->implode_app_reference_ids($booking_details);
			if(empty($app_reference_ids) == false) {
				//Itinerary Details
				$id_query = 'select * from `package_booking_passenger_details`  AS ID
							WHERE ID.app_reference IN ('.$app_reference_ids.')';
				//Transaction Details
				$td_query = 'select * from package_booking_transaction_details AS TD
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
		function b2b_package_report($condition=array(), $count=false, $offset=0, $limit=100000000000)
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

			// debug($count);exit();
		if ($count) {
			
			// echo debug($condition);exit;
			$query = 'select count(distinct(BD.app_reference)) AS total_records from package_booking_details BD
					 left join package P on P.package_id = BD.package_type
					left join user U on U.user_id = BD.created_by_id
					left join user_type UT on UT.origin = U.user_type
					join package_booking_transaction_details as BT on BD.app_reference = BT.app_reference	
					where P.supplier_id='."'".$this->entity_user_id."'".' AND U.user_type='.B2B_USER.' AND  BD.domain_origin='.get_domain_auth_id().''.$condition;
			// echo debug($query);exit;
			
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
			$bd_query = 'select P.package_name,P.module_type,BD.*,U.user_name,U.first_name,U.last_name from package_booking_details AS BD
					     left join package P on P.package_id = BD.package_type
					     left join user U on U.user_id = BD.created_by_id
					     left join user_type UT on UT.origin = U.user_type
					     join package_booking_transaction_details as BT on BD.app_reference = BT.app_reference		
						 WHERE  P.supplier_id='."'".$this->entity_user_id."'".' AND U.user_type='.B2B_USER.' AND BD.domain_origin='.get_domain_auth_id().' '.$condition.'
						 order by BD.created_datetime desc, BD.origin desc limit '.$offset.', '.$limit;		

					
						 
			$booking_details	= $this->db->query($bd_query)->result_array();
			// debug($booking_details);		exit;
			$app_reference_ids = $this->booking_data_formatter->implode_app_reference_ids($booking_details);
			if(empty($app_reference_ids) == false) {
				//Itinerary Details
				$id_query = 'select * from `package_booking_passenger_details`  AS ID
							WHERE ID.app_reference IN ('.$app_reference_ids.')';
				//Transaction Details
				$td_query = 'select * from package_booking_transaction_details AS TD
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
			function b2b_transfer_crs_report($condition=array(), $count=false, $offset=0, $limit=100000000000)
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

			// debug($count);exit();
		if ($count) {
			
			// echo debug($condition);exit;
			$query = 'select count(distinct(BD.app_reference)) AS total_records from package_booking_details BD
					 left join package P on P.package_id = BD.package_type
					left join user U on U.user_id = BD.created_by_id
					left join user_type UT on UT.origin = U.user_type
					join package_booking_transaction_details as BT on BD.app_reference = BT.app_reference	
					where P.supplier_id='."'".$this->entity_user_id."'".' AND U.user_type='.B2B_USER.' AND  BD.domain_origin='.get_domain_auth_id().''.$condition;
			// echo debug($query);exit;
			
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
			$bd_query = 'select P.package_name,P.module_type,BD.*,U.user_name,U.first_name,U.last_name from package_booking_details AS BD
					     left join package P on P.package_id = BD.package_type
					     left join user U on U.user_id = BD.created_by_id
					     left join user_type UT on UT.origin = U.user_type
					     join package_booking_transaction_details as BT on BD.app_reference = BT.app_reference		
						 WHERE  P.supplier_id='."'".$this->entity_user_id."'".' AND U.user_type='.B2B_USER.' AND BD.domain_origin='.get_domain_auth_id().' '.$condition.'
						 order by BD.created_datetime desc, BD.origin desc limit '.$offset.', '.$limit;		

					
						 
			$booking_details	= $this->db->query($bd_query)->result_array();
			// debug($booking_details);		exit;
			$app_reference_ids = $this->booking_data_formatter->implode_app_reference_ids($booking_details);
			if(empty($app_reference_ids) == false) {
				//Itinerary Details
				$id_query = 'select * from `package_booking_passenger_details`  AS ID
							WHERE ID.app_reference IN ('.$app_reference_ids.')';
				//Transaction Details
				$td_query = 'select * from package_booking_transaction_details AS TD
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



	function get_booking_details($app_reference, $booking_source='', $booking_status='')
	{
		$pass_query = 'select * from package_booking_passenger_details AS ID WHERE ID.app_reference='.$this->db->escape($app_reference).' order by origin asc';

		$tran_query = 'select * from package_booking_transaction_details AS ID WHERE ID.app_reference='.$this->db->escape($app_reference).' order by origin asc';

		$book_query = 'select * from package_booking_details AS ID WHERE ID.app_reference='.$this->db->escape($app_reference).' order by origin asc';

		$response['passenger_details']  = $this->db->query($pass_query)->result_array();

		$response['transaction_details']  = $this->db->query($tran_query)->result_array();

		$book_id = $response['transaction_details'][0]['book_id'];

		$pack_details = "SELECT * FROM `package` WHERE package_id =".$book_id;

		$response['package_details']  = $this->db->query($pack_details)->result_array();

		$response['booking_details']  = $this->db->query($book_query)->result_array();

		$response['status'] = SUCCESS_STATUS;

		return $response;
	}



	function b2b_holiday_report_old($condition=array(), $count=false, $offset=0, $limit=100000000000)
	{
		$condition = $this->custom_db->get_custom_condition($condition);
		//$b2c_condition_array = array('U.user_type', '=', B2C_USER, ' OR ', 'BD.created_by_id', '=', 0);
	
		//BT, CD, ID

		if(isset($condition) == true)
		{
			$offset = 0;
		}else{
			$offset = $offset;
		}

		if ($count) {
				
			//echo debug($condition);exit;
			$query = 'select count(distinct(BD.app_reference)) AS total_records from package_booking_details BD
					  join user U on U.user_id = BD.created_by_id
					  join package_booking_transaction_details as BT on BD.app_reference = BT.app_reference						
					  where U.user_type='.B2B_USER.' AND BD.domain_origin='.get_domain_auth_id().''.$condition;
			
				
		
			$data = $this->db->query($query)->row_array();
			//echo debug($data);exit;
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
			$bd_query = 'select BD.*,U.agency_name,U.first_name,U.last_name from package_booking_details AS BD
					      join user U on U.user_id = BD.created_by_id join package_booking_transaction_details as BT on BD.app_reference = BT.app_reference					      
						  WHERE  U.user_type='.B2B_USER.' AND BD.domain_origin='.get_domain_auth_id().' '.$condition.'
						  order by BD.created_datetime desc, BD.origin desc limit '.$offset.', '.$limit;
						  
			//echo debug($bd_query);			
			//exit;
			
			$booking_details	= $this->db->query($bd_query)->result_array();
			//echo debug($booking_details);exit;
			$app_reference_ids = $this->booking_data_formatter->implode_app_reference_ids($booking_details);
			if(empty($app_reference_ids) == false) {
				//Itinerary Details
				$id_query = 'select * from package_booking_passenger_details AS ID
							WHERE ID.app_reference IN ('.$app_reference_ids.')';
				//Transaction Details
				$td_query = 'select * from package_booking_transaction_details AS TD
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
					$query = $this->db->set($data)
					->where($where)
					->update($table);
					return TRUE;	
		}

		public function activity_book_cancelation($table, $data, $where){
					$query = $this->db->set($data)
					->where($where)
					->update($table);
					// ->update('package_booking_details');
					return TRUE;	
		}

}
