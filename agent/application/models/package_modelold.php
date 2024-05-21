<?php
class Package_Model extends CI_Model {
	public function __construct(){
		parent::__construct();
	}
	/*public function getAllPackages(){
		$this->db->select('*');
		$this->db->where('status', '1');
		$query = $this->db->get('package');
		if ( $query->num_rows > 0 ) {
			return $query->result();
		}else{
			return array();
		}
	}*/
	public function getAllPackages(){
		$this->db->select('*');
		$this->db->where('tours_itinerary.publish_status',ACTIVE);
		$this->db->where('tours.status',ACTIVE);
		$this->db->join('tours_itinerary', 'tours_itinerary.tour_id = tours.id', 'left');
		$query = $this->db->get('tours');
		if ( $query->num_rows > 0 ) {
	 		return $query->result();
		}else{
			return array();
		}		
	}
	public function getPackageCRS($package_id){
   		$this->db->select("*");
		$this->db->from("activity"); 
		$this->db->where('package_id',$package_id);
		$query=$this->db->get();
		if($query->num_rows()){
			return $query->result();
		}else{
			return array();
		}
	}
	public function getPackageTransfersCRS($package_id,$module_type){
   		$this->db->select("*");
		$this->db->from("package");
		$this->db->where('module_type',$module_type);
		$this->db->where('package_id',$package_id);
		$query=$this->db->get();
		if($query->num_rows()){
			return $query->result_array();
		}else{
			return array();
		}
   }
public function getnationalityTypes(){
		$this->db->select("currency");
		$this->db->from("tour_price_management");
		$query=$this->db->get();
		if($query->num_rows()){
			return $query->result();
		}else{
			return array();
		}
	}
	function get_supplier_details($id)
	{
		//BT, CD, ID
		$query = 'select user.user_id,user.first_name,user.last_name,user.email,user.phone from package  inner join user on package.supplier_id=user.user_id where package.package_id='.$id;
	
		return $this->db->query($query)->result_array();
	}
		public function update_pack_details($AppReference, $id)
	{
		$AppReference = trim($AppReference);
		$this->custom_db->update_record('package_booking_details', array('supplier_id' => $id), array('app_reference' => $AppReference));//later
	
	}
   function get_activity_booking_details($app_reference, $booking_source='', $booking_status='')
	{
		// echo "string";exit();
		$pass_query = 'select * from package_booking_passenger_details AS ID WHERE ID.app_reference='.$this->db->escape($app_reference).' order by origin asc';

		$tran_query = 'select * from package_booking_transaction_details AS ID WHERE ID.app_reference='.$this->db->escape($app_reference).' order by origin asc';
		// debug($tran_query);exit;

		$book_query = 'select * from package_booking_details AS ID WHERE ID.app_reference='.$this->db->escape($app_reference).' order by origin asc';

		$response['passenger_details']  = $this->db->query($pass_query)->result_array();

		$response['transaction_details']  = $this->db->query($tran_query)->result_array();
         // debug($response['transaction_details']);exit();
		$book_id = $response['transaction_details'][0]['book_id'];
        //debug($book_id);exit();

		$pack_details = "SELECT * FROM `package` WHERE package_id ='$book_id'";
$cancel_pack_details = "SELECT * FROM `package_cancellation` WHERE package_id ='$book_id'";
        $price_pack_details = "SELECT * FROM `package_pricing_policy` WHERE package_id ='$book_id'";
		$response['package_details']  = $this->db->query($pack_details)->result_array();

		$response['booking_details']  = $this->db->query($book_query)->result_array();
$response['cancellation_details']  = $this->db->query($cancel_pack_details)->result_array();
		
			$response['price_info']  = $this->db->query($price_pack_details)->result_array();
        $response['status'] = $response['transaction_details'][0]['status'];
        
        return $response;
	}
	 function change_confirm_status($book_id){
		$res = $this->custom_db->update_record('package_booking_details',array('status'=>'BOOKING_CONFIRMED'),array('app_reference'=>$book_id));

		$res1 = $this->custom_db->update_record('package_booking_transaction_details',array('status'=>'BOOKING_CONFIRMED'),array('app_reference'=>$book_id));

		$res2 = $this->custom_db->update_record('package_booking_passenger_details',array('status'=>'BOOKING_CONFIRMED'),array('app_reference'=>$book_id));

		if($res == QUERY_SUCCESS){
			return SUCCESS_STATUS;
		}
		else{
			return FAILURE_STATUS;
		}
	}
	function save_package_booking_details(
	$domain_origin, $status, $app_reference, $enquiry_reference_no, $booking_source, $phone, $alternate_number, $email,$payment_mode,	$attributes, $created_by_id, 
	$transaction_currency, $currency_conversion_rate,$pack_id,$date_of_travel)
	{

		$module_type="";
		if(!empty($pack_id))
		{
			$this->db->select("*");
	        $this->db->from("package");
	        $this->db->where('package_id',$pack_id);
	        $query=$this->db->get();
	        if($query->num_rows()>0){
	          $res= $query->result_array();
	          $module_type= $res[0]['module_type'];
	        }else{
	            $module_type="";
	        }
		}
		$data['domain_origin'] = $domain_origin;
		$data['status'] = $status;
		$data['app_reference'] = $app_reference;
		$data['enquiry_reference_no'] = $enquiry_reference_no;
		$data['booking_source'] = $booking_source;
		$data['phone'] = $phone;
		$data['package_type'] = $pack_id;
		$data['module_type'] = $module_type;
		$data['email'] = $email;
		$data['payment_mode'] = $payment_mode;
		$data['attributes'] = $attributes;
		$data['created_by_id'] = $created_by_id;
		$data['created_datetime'] = date('Y-m-d H:i:s');
		$data['date_of_travel'] = $date_of_travel;

		$data['currency'] = $transaction_currency;
		$data['currency_code'] = $transaction_currency;
		$data['currency_conversion_rate'] = $currency_conversion_rate;
		$data['user_type']=B2C_USER;

		$this->custom_db->insert_record('package_booking_details', $data);
		 // echo $this->custom_db->last_query();exit("ok");
		// debug($r);exit('sudheer');
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
	function save_package_booking_transaction_details( $promocode_discount_val,$app_reference, $transaction_status, $status_description, $pnr, $book_id, $source, $ref_id, $attributes,
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
	//	  debug($data);
	//	  exit;
		return $this->custom_db->insert_record('package_booking_transaction_details', $data);
	}
	function save_package_booking_passenger_details(
	$app_reference, $passenger_type, $is_lead, $first_name,$last_name,
	$gender, $passenger_nationality, $status,
	$attributes, $flight_booking_transaction_details_fk, $adult, $child, $infant)
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
		$data['infant'] = $infant;

		$sqlq = "insert into `package_booking_passenger_details` SET `app_reference`='".$data['app_reference']."',`flight_booking_transaction_details_fk`='".$data['flight_booking_transaction_details_fk']."',`passenger_type`='".$data['passenger_type']."',`is_lead`='".$data['is_lead']."',`first_name`='".$data['first_name']."',`last_name`='".$data['last_name']."',`gender`='".$data['gender']."',`passenger_nationality`='".$data['passenger_nationality']."',`status`='".$data['status']."',`attributes`='".$data['attributes']."',`adult`='".$data['adult']."',`child`='".$data['child']."',`infant`='".$data['infant']."'";

		
		
		//echo $this->db->last_query();die;
		//debug($data);exit("package_booking_passenger_details");

		return $this->db->query($sqlq);

		//return $this->custom_db->insert_record('package_booking_passenger_details', $data);
	}
	function save_tour_package_booking_details(
	$domain_origin, $flight_booking_status,$app_reference,$enquiry_reference_no, $booking_source, $phone, $alternate_number, $email,$payment_mode, $attributes, $created_by_id,$transaction_currency,$currency_conversion_rate,$pack_id,$date_of_travel)
	{

		//debug($phone);exit();
		// $package_id=$this->uri->segment(3);
		// //debug($package_id);exit();
		// $module_type="";
		// if(!empty($pack_id))
		// {
		// 	$this->db->select("*");
	 //        $this->db->from("package");
	 //        $this->db->where('package_id',$package_id);
	 //        $query=$this->db->get();
	 //        if($query->num_rows()>0){
	 //          $res= $query->result_array();
	 //          $module_type= $res[0]['module_type'];
	 //        }else{
	 //            $module_type="";
	 //        }
		// }
		$data['module_type'] = 'holiday';
		$data['domain_origin'] = $domain_origin;
		$data['status'] = $status;
		$data['app_reference'] = $app_reference;
		$data['booking_source'] = $booking_source;
		$data['phone'] = $phone;
		$data['package_type'] = $pack_id;
		
		$data['email'] = $email;
		
		$data['payment_mode'] = $payment_mode;
		$data['attributes'] = $attributes;
		$data['created_by_id'] = $created_by_id;
		$data['created_datetime'] = date('Y-m-d H:i:s');
		$data['date_of_travel'] = $date_of_travel;
		
		
		$data['currency'] = $transaction_currency;
		$data['currency_conversion_rate'] = $currency_conversion_rate;
        $dada['user_type']=B2B_USER;
        // debug($data);exit();
		$sqlq = "insert into `package_booking_details` SET `domain_origin`='".$data['domain_origin']."',`app_reference`='".$data['app_reference']."',`booking_source`='".$data['booking_source']."',`package_type`='".$data['package_type']."',`module_type`='".$data['module_type']."',`status`='".$data['status']."',`currency_code`='".$data['currency']."',`payment_status`='paid',`created_by_id`='".$data['created_by_id']."',`created_datetime`='".$data['created_datetime']."',`attributes`='".$data['attributes']."',`email`='".$data['email']."',`phone`='".$data['phone']."',`payment_mode`='".$data['payment_mode']."',`date_of_travel`='".$data['date_of_travel']."',`currency`='".$data['currency']."',`currency_conversion_rate`='".$data['currency_conversion_rate']."',`user_type`='".$dada['user_type']."'";


		$this->db->query($sqlq);

		//echo $sqlq;
		//debug($data);die("382");


		// $this->custom_db->insert_record('package_booking_details', $data);

		 
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
	/*public function getPackageCountries_new(){
    	$data = 'select C.name AS country_name, C.country_id as country_id FROM country C';
    
    	return $this->db->query($data)->result();
    	$this->db->select('package_country');
    	 $this->db->from('package'); 
    	 $this->db->group_by('package_country'); 
		$query = $this->db->get();
		if($query->num_rows()){
			return $query->result();
		}else{
			return array();
		}
    }*/
     public function getPackageCountries_new(){
    	$data = 'select C.name AS country_name, C.id as country_id FROM tours_country C';
    
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
	public function gerEnquiryPackages($user_id){
		$data = 'select * from package_enquiry WHERE user_id='.$user_id;
    	return $this->db->query($data)->result();
	}
	/*public function getPackageTypes(){
		$this->db->select("*");
		$this->db->from("package_types");
		$query=$this->db->get();
		if($query->num_rows()){
			return $query->result();
		}else{
			return array();
		}
	}*/
	public function getPackageTypes(){
    	$this->db->select("*");
    	$this->db->where('status',ACTIVE);
    	$this->db->from("tour_subtheme");
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
		function b2b_holiday_report($condition=array(), $count=false, $offset=0, $limit=100000000000)
	{


		$condition = $this->custom_db->get_custom_condition($condition);
		// debug($condition);exit();
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
			
			$query = 'select count(distinct(BD.app_reference)) AS total_records from package_booking_details BD
					 left join package P on P.package_id = BD.package_type
					left join user U on U.user_id = BD.created_by_id
					left join user_type UT on UT.origin = U.user_type
					join package_booking_transaction_details as BT on BD.app_reference = BT.app_reference	
					where BD.user_type=4 and BD.created_by_id='.$this->entity_user_id.' and BD.domain_origin='.get_domain_auth_id().''.$condition;
		
			
			$data = $this->db->query($query)->row_array();
			// debug($data);exit();
			// echo $this->db->last_query();exit();
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
						 WHERE BD.user_type=4 and BD.created_by_id='.$this->entity_user_id.' and BD.domain_origin='.get_domain_auth_id().' '.$condition.'
						 order by BD.created_datetime desc, BD.origin desc limit '.$offset.', '.$limit;		

					//	echo debug(	$bd_query);exit;
						 
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

}
