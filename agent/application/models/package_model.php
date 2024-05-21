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
	 public function getPackageTypes2(){

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
	/**
	 *@param Top Destination Packages
	 */
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
		$this->db->from("activity_itinerary");
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
		$this->db->from("activity_pricing_policy");
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
		$this->db->from("activity_traveller_photos");
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
		$this->db->from("activity_cancellation");
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
		$this->db->from("activity");
		$this->db->where('package_id',$package_id);
		$query=$this->db->get();
		if($query->num_rows()){
			return $query->row();
		}else{
			return array();
		}
	}


    public function getamenities($val){
   		$this->db->select("activity_amenties");
		$this->db->from("activity_amenties"); 
		$this->db->where('id',$val);
		$query=$this->db->get();
		if($query->num_rows()){
			return $query->result();
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
	public function gerEnquiryPackages($user_id){
		$data = 'select * from package_enquiry WHERE user_id='.$user_id;
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

}
