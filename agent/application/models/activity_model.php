<?php
class Activity_Model extends CI_Model {
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
		//debug($data);exit;
		$this->db->insert('activity_enquiry',$data);
		// echo $this->db->last_query();exit;
		return $this->db->insert_id();
	}

	public function getPackageCountries(){
		$data = 'select package_country, C.name AS country_name FROM package P, country C WHERE P.package_country=C.country_id';
    	return $this->db->query($data)->result();
	}
	public function gerEnquiryPackages($user_id){
		$data = 'select * from activity_enquiry WHERE user_id='.$user_id;
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
		function save_package_booking_transaction_details($promocode_discount_val, $app_reference, $transaction_status, $status_description, $pnr, $book_id, $source, $ref_id, $attributes,
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

		// $this->db->insert('activity_booking_transaction_details',$data);
		// echo $this->db->last_query();exit;
		// debug($data);die("in save_package_booking_transaction_details ");
		return $this->custom_db->insert_record('activity_booking_transaction_details', $data);
	}

	function save_package_booking_passenger_details(
	$app_reference, $passenger_type, $is_lead, $first_name,$last_name,
	$gender, $passenger_nationality, $status,
	$attributes, $flight_booking_transaction_details_fk, $adult, $child, $infant)
	{
		$data['app_reference'] = $app_reference;
		$data['activity_booking_transaction_details_fk'] = $flight_booking_transaction_details_fk;
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
		$data['infant'] = 0;

		$sqlq = "insert into `activity_booking_passenger_details` SET `app_reference`='".$data['app_reference']."',`activity_booking_transaction_details_fk`='".$data['activity_booking_transaction_details_fk']."',`passenger_type`='".$data['passenger_type']."',`is_lead`='".$data['is_lead']."',`first_name`='".$data['first_name']."',`last_name`='".$data['last_name']."',`gender`='".$data['gender']."',`passenger_nationality`='".$data['passenger_nationality']."',`status`='".$data['status']."',`attributes`='".$data['attributes']."',`adult`='".$data['adult']."',`child`='".$data['child']."',`infant`='".$data['infant']."'";

		
		
		// debug($this->db->last_query());die;
		//debug($data);exit("package_booking_passenger_details");

		return $this->db->query($sqlq);

		//return $this->custom_db->insert_record('package_booking_passenger_details', $data);
	}

	function save_activity_booking_details($booking_params=array()){

		// debug($booking_params);exit;
		$booking_params['currency_code']='NPR';
			$booking_params['currency']='NPR';
		return $this->custom_db->insert_record('activity_booking_details',$booking_params);
	}

	function save_activity_passenger_booking_details($passenger_details=array()){			
		return $this->db->insert_batch('activity_booking_passenger_details', $passenger_details); 
	}
	function update_activity_payment_status($app_reference,$booking_source,$booking_status,$payment_status){		
		$this->db->query("UPDATE `activity_booking_details` SET `payment_status`='$payment_status' , status='$booking_status' WHERE `app_reference`='$app_reference'"); 

		$this->db->query("UPDATE `activity_booking_passenger_details` SET `status`='$booking_status' WHERE `app_reference`='$app_reference'");
		$this->db->query("UPDATE `activity_booking_transaction_details` SET `status`='$booking_status' WHERE `app_reference`='$app_reference'");

	}

	function save_booking_transaction($transaction_details=array()){	
		return $this->custom_db->insert_record('activity_booking_transaction_details', $transaction_details); 
	}
	

	function save_package_booking_details(
	$domain_origin, $status, $app_reference, $booking_source, $phone, $alternate_number, $email,$payment_mode,	$attributes, $created_by_id, 
	$transaction_currency, $currency_conversion_rate,$pack_id,$date_of_travel,$amount='')
	{
		//$data['module_type'] = 'holiday';
		$data['module_type'] = 'activity';
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
		$data['basic_fare']=$amount;
		
		
		$data['currency'] = $transaction_currency;
		$data['currency_conversion_rate'] = $currency_conversion_rate;

//debug($data['basic_fare']);exit;
		//$sqlq = "insert into `activity_booking_details` SET `domain_origin`='".$data['domain_origin']."',`app_reference`='".$data['app_reference']."',`booking_source`='".$data['booking_source']."',`package_type`='".$data['package_type']."',`module_type`='".$data['module_type']."',`status`='".$data['status']."',`currency_code`='".$data['currency']."',`payment_status`='paid',`created_by_id`='".$data['created_by_id']."',`created_datetime`='".$data['created_datetime']."',`attributes`='".$data['attributes']."',`email`='".$data['email']."',`phone`='".$data['phone']."',`payment_mode`='".$data['payment_mode']."',`date_of_travel`='".$data['date_of_travel']."',`currency`='".$data['currency']."',`currency_conversion_rate`='".$data['currency_conversion_rate']."'";
		//$this->db->query($sqlq);

		$this->custom_db->insert_record('activity_booking_details', $data);
//debug($this->db->last_query());exit;

		//echo $sqlq;
		//debug($data);die("382");


		// $this->custom_db->insert_record('package_booking_details', $data);

		 
	}


	function change_confirm_status($book_id){
		$res = $this->custom_db->update_record('activity_booking_details',array('status'=>'BOOKING_CONFIRMED'),array('app_reference'=>$book_id));
		//debug($this->db->last_query());exit;
		$res1 = $this->custom_db->update_record('activity_booking_transaction_details',array('status'=>'BOOKING_CONFIRMED'),array('app_reference'=>$book_id));

		$res2 = $this->custom_db->update_record('activity_booking_passenger_details',array('status'=>'BOOKING_CONFIRMED'),array('app_reference'=>$book_id));
		// debug(QUERY_SUCCESS);
		// debug($res);exit();
		if($res == QUERY_SUCCESS){
			return SUCCESS_STATUS;
		}
		else{
			return FAILURE_STATUS;
		}
	}

	function get_activity_booking_details($app_reference, $booking_source='', $booking_status='')
	{
		// echo "in";die;
		$pass_query = 'select * from activity_booking_passenger_details AS ID WHERE ID.app_reference='.$this->db->escape($app_reference).' order by origin asc';

		$tran_query = 'select * from activity_booking_transaction_details AS ID WHERE ID.app_reference='.$this->db->escape($app_reference).' order by origin asc';

		$book_query = 'select * from activity_booking_details AS ID WHERE ID.app_reference='.$this->db->escape($app_reference).' order by origin asc';

		$response['passenger_details']  = $this->db->query($pass_query)->result_array();

		$response['transaction_details']  = $this->db->query($tran_query)->result_array();

		$book_id = $response['transaction_details'][0]['book_id'];

		$pack_details = "SELECT * FROM `activity` WHERE package_id =".$book_id;

		$response['package_details']  = $this->db->query($pack_details)->result_array();

		$response['booking_details']  = $this->db->query($book_query)->result_array();

// debug($response);die;
		// $response['status'] = SUCCESS_STATUS;
$response['status'] = $response['transaction_details'][0]['status'];
// debug($response);die;
		return $response;
	
	}


	//HotelBed Integration Code Starts

	function get_activity_city_list($search_chars)
    {
        $raw_search_chars = $this->db->escape($search_chars);
        if(empty($search_chars)==false){
            $r_search_chars = $this->db->escape($search_chars.'%');
            $search_chars = $this->db->escape($search_chars.'%');
        }else{
            $r_search_chars = $this->db->escape($search_chars);
            $search_chars = $this->db->escape($search_chars);
        }
        
        $query = 'Select cm.country_name,cm.city_name,cm.origin,cm.country_code from all_api_city_master_hb as cm where  cm.city_name like '.$search_chars.' 
                ORDER BY cm.cache_hotels_count desc, CASE
            WHEN    cm.city_name    LIKE    '.$raw_search_chars.'   THEN 1
            WHEN    cm.city_name    LIKE    '.$r_search_chars.' THEN 2  
            WHEN    cm.city_name    LIKE    '.$search_chars.'   THEN 3
            ELSE 4 END, cm.cache_hotels_count desc LIMIT 0, 30
        ';  
        return $this->db->query($query)->result_array();
    }

    

	public function getCityCode($origin){
                    $this->db->where('origin',$origin);
        $result = $this->db->get('all_api_city_master_hb')->row();
        // debug($result);exit();
        return $result;
    }

     /**
     * get search data and validate it
     */
    function get_safe_search_data($search_id,$module_id)
    {

    	// debug($search_id);
    	// debug($module_id);exit;

        $search_data = $this->get_search_data($search_id,$module_id);

        // debug($search_data);exit;
        $success = true;
        $clean_search = array();
        if ($search_data != false){
            //validate
            $temp_search_data = json_decode($search_data['search_data'], true);         
            if(isset($temp_search_data['activity_from']))
            {
	            $clean_search['from_date'] = @$temp_search_data['activity_from'];
            }else
            {
           		$clean_search['from_date'] = @$temp_search_data['from_date'];         	
            }


            if(isset($temp_search_data['activity_to']))
            {
	            $clean_search['to_date'] = @$temp_search_data['activity_to'];
            }else
            {
           		$clean_search['to_date'] = @$temp_search_data['to_date'];   	
            }

            if(isset($temp_search_data['city']))
            {
	           $clean_search['destination'] = $temp_search_data['city'];
            }else
            {
           		$clean_search['destination'] = $temp_search_data['destination'];   	
            }


 			if(isset($temp_search_data['activity_destination']))
            {
	           $clean_search['destination_id'] = $temp_search_data['activity_destination'];
            }else
            {
       		  $clean_search['destination_id'] = @$temp_search_data['destination_id'];  	
            } 
            if(isset($temp_search_data['nationality']))
            {
	           $clean_search['nationality'] = $temp_search_data['nationality'];
            }else
            {
       		  $clean_search['nationality'] = @$temp_search_data['nationality'];  	
            }
            $clean_search['child'] = $temp_search_data['child'];
            $clean_search['adult'] = $temp_search_data['adult'];
           if (isset($temp_search_data['markup_type']) == true) {
                $clean_search['markup_type'] = $temp_search_data['markup_type'];
            }

            if (isset($temp_search_data['markup_value']) == true) {
                $clean_search['markup_value'] = $temp_search_data['markup_value'];
            }

            if (isset($temp_search_data['markup_currency']) == true) {
                $clean_search['markup_currency'] = $temp_search_data['markup_currency'];
            }
            //for search level Markup
          
           if(empty($temp_search_data['category_id'])==true){
                $clean_search['category_id'] = 0;
           }else{
            $clean_search['category_id'] = $temp_search_data['category_id'];
           }
            
            
        } else {
            $success = false;
        }
       
       // debug($clean_search);
            // exit;
        return array('status' => $success, 'data' => $clean_search);
    }


    /**
     * get search data without doing any validation
     * @param $search_id
     */
    function get_search_data($search_id)
    {
        if (empty($this->master_search_data)) {
            $search_data = $this->custom_db->single_table_records('search_history', '*', array('search_type' => META_SIGHTSEEING_COURSE, 'origin' => $search_id));
            if ($search_data['status'] == true) {
                $this->master_search_data = $search_data['data'][0];
            } else {
                return false;
            }
        }

       // debug($this->master_search_data); die;
        return $this->master_search_data;
    }


     /**
     * SAve search data for future use - Analytics
     * @param array $params
     */
    function save_search_data($search_data, $type)
    {
	//	echo "asdsad";die;
        $data['domain_origin'] = get_domain_auth_id();
        $data['search_type'] = $type;
        $data['created_by_id'] = intval(@$this->entity_user_id);
        $data['created_datetime'] = date('Y-m-d H:i:s');
        $data['destination_name '] =trim(explode('(',$search_data['city'])[0]);
        // $data['destination_id'] = $search_data['destination_id'];
        $data['destination_id'] = $search_data['activity_destination'];
        $data['no_of_adult'] = $search_data['adult'];
        $data['no_of_child'] = $search_data['child'];       
        $data['nationality'] = $search_data['nationality'];       
        if($search_data['activity_from']){
             $data['from_date'] = date('Y-m-d',strtotime($search_data['activity_from']));     
        }else{
             $data['from_date'] = date('Y-m-d');
        }
        if($search_data['activity_to']){
            $data['to_date'] = date('Y-m-d',strtotime($search_data['activity_to']));    
        }else{
            $data['to_date'] = date('Y-m-d');
        }        
        if(isset($search_data['category_id'])){
            $data['category_id'] = $search_data['category_id'];    
        }
        // debug($data);exit;
        $this->custom_db->insert_record('search_sightseen_history', $data);
    }


    /**
     * get all the booking source which are active for current domain
     */
    function active_booking_source($course_id=META_SIGHTSEEING_COURSE)
    {
        $query = 'select BS.source_id, BS.origin from meta_course_list AS MCL, booking_source AS BS, activity_source_map AS ASM WHERE
        MCL.origin=ASM.meta_course_list_fk and ASM.booking_source_fk=BS.origin and MCL.course_id='.$this->db->escape($course_id).'
        and BS.booking_engine_status='.ACTIVE.' AND MCL.status='.ACTIVE.' AND ASM.status="active"';
        // echo $query;exit();
        return $this->db->query($query)->result_array();
    }


   public  function save_booking_details($domain_origin, $status, $app_reference, $booking_source, $booking_id, $booking_reference, $confirmation_reference, $product_name, $star_rating, $product_code,$grade_code,$grade_desc,$phone_number, $alternate_number, $email, $travel_date,$payment_mode,$attributes,$created_by_id, $transaction_currency, $currency_conversion_rate )
    {
        $data['domain_origin'] = $domain_origin;
        $data['status'] = $status;
        $data['app_reference'] = $app_reference;
        $data['booking_source'] = $booking_source;
        $data['booking_id'] = $booking_id;
        $data['booking_reference'] = $booking_reference;
        $data['confirmation_reference'] = $confirmation_reference;
        $data['product_name'] = $product_name;
        $data['star_rating'] = $star_rating;
        $data['product_code'] = $product_code;
        $data['phone_number'] = $phone_number;
        $data['alternate_number'] = $alternate_number;
        $data['email'] = $email;
        $data['grade_code'] = $grade_code;
        $data['travel_date'] = $travel_date;
        $data['grade_desc'] = $grade_desc;
        $data['payment_mode'] = $payment_mode;
        $data['attributes'] = $attributes;
        $data['created_by_id'] = $created_by_id;
        $data['created_datetime'] = date('Y-m-d H:i:s');        
        $data['currency'] = $transaction_currency;
        $data['currency_conversion_rate'] = $currency_conversion_rate;
        // $data['remarks_user'] = $remarks_user;       
        
       // debug( $data);exit();
        $status = $this->custom_db->insert_record('sightseeing_booking_details', $data);
        return $status;
    }  


    function save_booking_pax_details($title,$app_reference,$first_name,$last_name, $phone, $email, $pax_type,$status)
    {        
        $data['app_reference'] = $app_reference;
        $data['title'] = $title;
        $data['first_name'] = $first_name;      
        $data['last_name'] = $last_name;
        $data['phone'] = $phone;
        $data['email'] = $email;
        $data['pax_type'] = $pax_type;        
        $data['status'] = $status;
      
        $status = $this->custom_db->insert_record('sightseeing_booking_pax_details', $data);
        
        return $status;
    }

    /**
     *
     * @param string $app_reference
     * @param string $location
     
     * @param date   $travel_date
     * @param string $grade_desc
     * @param string $grade_code
     * @param string $status
     * 
     * @param string $attributes
     */
     function save_booking_itinerary_details($app_reference, $location, $travel_date,$grade_code, $grade_desc, $status, $total_fare,$admin_net_fare_markup,$admin_markup, $agent_markup, $currency, $attributes,$book_total_fare,$agent_commission,$agent_tds,$admin_com,$admin_tds,$api_raw_fare,$agent_buying_price, $gst='')
    {
        $data['app_reference'] = $app_reference;
        $data['location'] = $location;
        $data['travel_date'] = $travel_date;
        $data['grade_desc'] = $grade_desc;
        $data['grade_code'] = $grade_code;
        $data['status'] = $status;        
        $data['total_fare'] = $total_fare;
        $data['admin_net_markup'] = $admin_net_fare_markup;
        $data['admin_markup    '] = $admin_markup;
        $data['agent_markup'] = $agent_markup;
        $data['currency'] = $currency;
        $data['attributes'] = $attributes;
        $data['agent_commission'] = $agent_commission;
        $data['agent_tds'] = $agent_tds;
        $data['admin_commission'] = $admin_com;
        $data['admin_tds'] = $admin_tds;
        $data['api_raw_fare'] = $api_raw_fare;
        $data['agent_buying_price'] =$agent_buying_price;
        $data['gst'] = $gst;
        $status = $this->custom_db->insert_record('sightseeing_booking_itinerary_details', $data);
        return $status;
    }

      /**
     * Return Booking Details based on the app_reference passed
     * @param $app_reference
     * @param $booking_source
     * @param $booking_status
     */

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
        $id_query = 'select * from sightseeing_booking_itinerary_details AS ID WHERE ID.app_reference='.$this->db->escape($app_reference);
        $cd_query = 'select * from activity_booking_passenger_details AS CD WHERE CD.app_reference='.$this->db->escape($app_reference);
        $cancellation_details_query = 'select HCD.* from sightseeing_cancellation_details AS HCD WHERE HCD.app_reference='.$this->db->escape($app_reference);
        $response['data']['booking_details']            = $this->db->query($bd_query)->result_array();
        //debug($this->db->last_query());
		//debug($response['data']['booking_details']);exit;

        $response['data']['booking_itinerary_details']  = $this->db->query($id_query)->result_array();
        // debug($id_query);exit;
        $response['data']['booking_customer_details']   = $this->db->query($cd_query)->result_array();
        $response['data']['cancellation_details']   = $this->db->query($cancellation_details_query)->result_array();

// debug($response['data']['booking_details']);exit;
        if (valid_array($response['data']['booking_details']) == true and  valid_array($response['data']['booking_customer_details']) == true) {
            $response['status'] = SUCCESS_STATUS;
        }
        
    


        // debug($response);exit();
        return $response;
    }
    public function get_package_id($package_id) {
		$this->db->select ( "*" );
		$this->db->from ( "activity" );
		$this->db->join ( 'activity_cancellation', 'activity_cancellation.package_id = activity.package_id' );
		$this->db->join ( 'activity_pricing_policy', 'activity_pricing_policy.package_id = activity.package_id' );
		$this->db->where ( 'activity.package_id', $package_id );
		return $this->db->get ()->row ();
	}
/*

    function get_booking_details($app_reference, $booking_source, $booking_status='')
    {
    	$booking_status="BOOKING_CONFIRMED";
        $response['status'] = FAILURE_STATUS;
        $response['data'] = array();
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

// echo $bd_query;exit();
       // debug($response);exit();
       // if (valid_array($response['data']['booking_details']) == true and valid_array($response['data']['booking_itinerary_details']) == true and valid_array($response['data']['booking_customer_details']) == true) {
        	if (valid_array($response['data']['booking_details']) == true and  valid_array($response['data']['booking_customer_details']) == true) {
            $response['status'] = SUCCESS_STATUS;
        }


        // debug($response);exit();
        return $response;
    }*/

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


		if ($count) {


  
			$query = 'select count(distinct(BD.app_reference)) AS total_records from activity_booking_details BD
					left join user U on U.user_id = BD.created_by_id
					left join user_type UT on UT.origin = U.user_type
					join activity_booking_transaction_details as BT on BD.app_reference = BT.app_reference	
					where (U.user_type='.B2B_USER.') AND BD.created_by_id='.$this->entity_user_id.' AND BD.domain_origin='.get_domain_auth_id().''.$condition;
			// debug($query);exit;
			
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
						 WHERE (U.user_type='.B2B_USER.') AND  BD.created_by_id='.$this->entity_user_id.' AND BD.domain_origin='.get_domain_auth_id().' '.$condition.'
						 order by BD.created_datetime desc, BD.origin desc limit '.$offset.', '.$limit;		

		//		echo $bd_query;exit;
						 
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
					where BD.emulate_booking = 1 and (U.user_type='.B2B_USER.' AND BD.created_by_id = '.intval($this->entity_user_id).') AND BD.domain_origin='.get_domain_auth_id().''.$condition;
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
						 WHERE BD.emulate_booking = 1 and (U.user_type='.B2B_USER.' AND BD.created_by_id = '.intval($this->entity_user_id).') AND BD.domain_origin='.get_domain_auth_id().' '.$condition.'
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
					where BD.emulate_booking = 0 and (U.user_type='.B2B_USER.' OR BD.created_by_id = 0) AND BD.domain_origin='.get_domain_auth_id().' AND '.$condition;
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
						 WHERE BD.emulate_booking = 0 and (U.user_type='.B2B_USER.' OR BD.created_by_id = 0) AND BD.domain_origin='.get_domain_auth_id().' AND '.$condition.'
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

	function emulate_b2c_holiday_report_filter($condition=array(), $count=false, $offset=0, $limit=100000000000)
	{

		//$condition = $this->custom_db->get_custom_condition($condition);
		//$b2c_condition_array = array('U.user_type', '=', B2C_USER, ' OR ', 'BD.created_by_id', '=', 0);
		
		//BT, CD, ID


		 
		 //debug($condition);exit();

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
			//debug($query);exit;
			
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
			//echo $bd_query; exit;
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
	function get_agent_info($user_id){
		$query = 'select U.*,BU.logo,CNT.iso_country_code from user AS U
					      join  b2b_user_details BU on U.user_id = BU.user_oid
				          join  currency_converter CUC on CUC.id = BU.currency_converter_fk
				          join  api_country_list CNT on CNT.origin = U.country_name
						  WHERE  U.user_type='.B2B_USER.' AND U.user_id='.$user_id;
						  // echo $query;exit;
		return $this->db->query($query)->result_array();
			
	}
	function get_reference_info()
	{
		$this->db->select("activity_ref_no,first_no,second_no,third_no");
		$this->db->from("reference_id_activity");
		$this->db->limit(1);
		$this->db->order_by('id',"DESC");
		$query = $this->db->get();
		// echo $this->db->last_query();exit;
		$result = $query->result();
		return $result;
	}
	public function get_country_code($country_name) {
		$this->db->where ( "country_name",$country_name );
		$qur = $this->db->get ( "country_list" );
		return $qur->result ();
	}
	public function get_country_currency($country_code) {
		$this->db->where ( "country_code",$country_code );
		$qur = $this->db->get ( "country_list" );
		return $qur->result ();
	}
	function get_country_city($dest_id)
    {
		//debug($dest_id);die;
        $query = 'Select cm.country_code from all_api_city_master_hb as cm where  cm.origin = '.$dest_id.'';  
        return $this->db->query($query)->result_array();
    }
    public function get_markup_for_admin($min_price, $supplier, $cntry_code, $city_id='', $product_id=''){
			$markup_value=0;
			$admin_markup=$this->admin_markup($min_price, $supplier, $cntry_code, $city_id, $product_id);
			$markup_value +=$admin_markup;
			return $markup_value;
	}
	public function admin_markup($min_price, $supplier, $cntry_code, $city_id='', $product_id=''){
				$markup_ary = array();
				$where=""; 
				// $product_id=$package_details['package_id'];\
			  	if($cntry_code)
	 			{ 
	 				$where .=" and ML.country='$cntry_code'";
	 			}
	 			if($city_id!='')
	 			{ 
	 				$where .=" and ML.city='$city_id'";
	 			}
	 			if($product_id!='')
	 			{ 
	 				$where .=" and ML.product_id='$product_id'";
	 			}
	 			if($supplier!='')
	 			{ 
	 				$where .=" and ML.supplier='".$supplier."'";
	 			}
				$query = 'SELECT
				ML.origin AS markup_origin, ML.value, ML.value_type,  ML.markup_currency AS markup_currency
				FROM domain_list AS DL JOIN markup_list AS ML where ML.value != "" and
				ML.module_type = "b2b_sightseeing" and ML.markup_level = "level_3"'; 
				// debug($query);  exit;
				$specific_data_list = $this->db->query($query)->result_array();
				// debug($specific_data_list);exit;
				if($specific_data_list){
					switch ($specific_data_list[0]['value_type']) {
	                    case 'percentage' :
	                        //Just need to calculate percentage of the values
	                        $markup_value = (($min_price / 100) * $specific_data_list[0]['value']);
	                        $original_markup = $specific_data_list[0]['value'];
	                        $markup_type = 'Percentage';
	                        break;
	                    case 'plus' :
	                        $original_markup = $specific_data_list[0]['value'];
	                        //convert value to required currency and then add the value
	                        // $temp_conversion = $this->currency_conversion_value(false, $__iv['markup_currency'], $this->to_currency);
	                        $markup_value =$specific_data_list[0]['value'];
	                        $markup_type = 'Plus';
	                        break;
                	}
                	return $markup_value;
				}
				else
				{
					$where=""; 
				// $product_id=$package_details['package_id'];
				// echo $product_id;exit;
			  	if($cntry_code)
	 			{ 
	 				$where .=" and ML.country='$cntry_code'";
	 			}
	 			if($supplier!='')
	 			{ 
	 				$where .=" and ML.supplier='".$supplier."'";
	 			}
	 			if($product_id!='')
	 			{ 
	 				$where .=" and ML.product_id='$product_id'";
	 			}
				$query = 'SELECT
				ML.origin AS markup_origin, ML.value, ML.value_type,  ML.markup_currency AS markup_currency
				FROM domain_list AS DL JOIN markup_list AS ML where ML.value != "" and
				ML.module_type = "b2b_sightseeing" and ML.markup_level = "level_3"   and ML.city="0" and DL.origin=ML.domain_list_fk and ML.domain_list_fk != 0 and ML.reference_id=0 and ML.domain_list_fk = '.get_domain_auth_id().' '.$where.'order by DL.created_datetime DESC'; 
				// debug($query);  exit;
				$specific_data_list = $this->db->query($query)->result_array();
				// debug($specific_data_list);exit;
				if($specific_data_list){
					switch ($specific_data_list[0]['value_type']) {
	                    case 'percentage' :
	                        //Just need to calculate percentage of the values
	                        $markup_value = (($min_price / 100) * $specific_data_list[0]['value']);
	                        $original_markup = $specific_data_list[0]['value'];
	                        $markup_type = 'Percentage';
	                        break;
	                    case 'plus' :
	                        $original_markup = $specific_data_list[0]['value'];
	                        //convert value to required currency and then add the value
	                        // $temp_conversion = $this->currency_conversion_value(false, $__iv['markup_currency'], $this->to_currency);
	                        $markup_value =$specific_data_list[0]['value'];
	                        $markup_type = 'Plus';
	                        break;
                	}
                	return $markup_value;
				}else{
					$where=""; 
				// $product_id=$package_details['package_id'];
				// echo $product_id;exit;
				if($product_id!='')
	 			{ 
	 				$where .=" and ML.product_id='$product_id'";
	 			}
	 			if($supplier!='')
	 			{ 
	 				$where .=" and ML.supplier='".$supplier."'";
	 			}
				$query = 'SELECT
				ML.origin AS markup_origin, ML.value, ML.value_type,  ML.markup_currency AS markup_currency
				FROM domain_list AS DL JOIN markup_list AS ML where ML.value != "" and
				ML.module_type = "b2b_sightseeing" and ML.markup_level = "level_3"  and  ML.city="0"and ML.country=" " and DL.origin=ML.domain_list_fk and ML.domain_list_fk != 0 and ML.reference_id=0 and ML.domain_list_fk = '.get_domain_auth_id().' '.$where.'order by DL.created_datetime DESC'; 
				// debug($query);  exit;
				$specific_data_list = $this->db->query($query)->result_array();
				// debug($specific_data_list);exit;
				if($specific_data_list){
					switch ($specific_data_list[0]['value_type']) {
	                    case 'percentage' :
	                        //Just need to calculate percentage of the values
	                        $markup_value = (($min_price / 100) * $specific_data_list[0]['value']);
	                        $original_markup = $specific_data_list[0]['value'];
	                        $markup_type = 'Percentage';
	                        break;
	                    case 'plus' :
	                        $original_markup = $specific_data_list[0]['value'];
	                        //convert value to required currency and then add the value
	                        // $temp_conversion = $this->currency_conversion_value(false, $__iv['markup_currency'], $this->to_currency);
	                        $markup_value =$specific_data_list[0]['value'];
	                        $markup_type = 'Plus';
	                        break;
                	}
                	return $markup_value;
				}else{
					$where=""; 
				// $product_id=$package_details['package_id'];
				// echo $product_id;exit;
			  	if($cntry_code)
	 			{ 
	 				$where .=" and ML.country='$cntry_code'";
	 			}
	 			if($supplier!='')
	 			{ 
	 				$where .=" and ML.supplier='".$supplier."'";
	 			}
	 			if($product_id!='')
	 			{ 
	 				$where .=" and ML.product_id='$product_id'";
	 			}
				$query = 'SELECT
				ML.origin AS markup_origin, ML.value, ML.value_type,  ML.markup_currency AS markup_currency
				FROM domain_list AS DL JOIN markup_list AS ML where ML.value != "" and
				ML.module_type = "b2b_sightseeing" and ML.markup_level = "level_3"  and ML.product_id is NULL and ML.city="0" and DL.origin=ML.domain_list_fk and ML.domain_list_fk != 0 and ML.reference_id=0 and ML.domain_list_fk = '.get_domain_auth_id().' '.$where.'order by DL.created_datetime DESC'; 
				// debug($query);  exit;
				$specific_data_list = $this->db->query($query)->result_array();
				// debug($specific_data_list);exit;
				if($specific_data_list){
					switch ($specific_data_list[0]['value_type']) {
	                    case 'percentage' :
	                        //Just need to calculate percentage of the values
	                        $markup_value = (($min_price / 100) * $specific_data_list[0]['value']);
	                        $original_markup = $specific_data_list[0]['value'];
	                        $markup_type = 'Percentage';
	                        break;
	                    case 'plus' :
	                        $original_markup = $specific_data_list[0]['value'];
	                        //convert value to required currency and then add the value
	                        // $temp_conversion = $this->currency_conversion_value(false, $__iv['markup_currency'], $this->to_currency);
	                        $markup_value =$specific_data_list[0]['value'];
	                        $markup_type = 'Plus';
	                        break;
                	}
                	return $markup_value;
				}else{
					$query = 'SELECT
					ML.origin AS markup_origin, ML.value, ML.value_type,  ML.markup_currency AS markup_currency
					FROM domain_list AS DL JOIN markup_list AS ML where ML.value != "" and
					ML.module_type = "b2b_sightseeing" and ML.markup_level = "level_3" and DL.origin=ML.domain_list_fk and ML.domain_list_fk != 0 and ML.reference_id=0 and ML.domain_list_fk = '.get_domain_auth_id().' and ML.product_id is NULL and ML.supplier="'.$supplier.'" and ML.city="0" and ML.country=" " order by DL.created_datetime DESC';
					// debug($query);  exit;
					$specific_data_list1 = $this->db->query($query)->row_array();
					// debug($specific_data_list1);exit;
					if($specific_data_list1){
						switch ($specific_data_list1['value_type']) {
		                    case 'percentage' :
		                        //Just need to calculate percentage of the values
		                        $markup_value = (($min_price / 100) * $specific_data_list1['value']);
		                        $original_markup = $specific_data_list1['value'];
		                        $markup_type = 'Percentage';
		                        break;
		                    case 'plus' :
		                        $original_markup = $specific_data_list1['value'];
		                        //convert value to required currency and then add the value
		                        // $temp_conversion = $this->currency_conversion_value(false, $__iv['markup_currency'], $this->to_currency);
		                        $markup_value =$specific_data_list1['value'];
		                        $markup_type = 'Plus';
		                        break;
	                	}
	                	return $markup_value;
					}
					else
					{
						return 0;
					}

				}
				}
				}
				}
	}
	function verify_agent_balance()
	{
				$query = 'SELECT BU.balance, BU.credit_limit, BU.due_amount, CC.country as currency, CC.value as conversion_value
							from user as U
							JOIN b2b_user_details as BU ON U.user_id=BU.user_oid
							JOIN domain_list as DL ON U.domain_list_fk = DL.origin
							JOIN currency_converter CC ON CC.id=BU.currency_converter_fk
							WHERE U.status='.ACTIVE.' and U.user_id='.intval($this->entity_user_id).' and 
							DL.status='.ACTIVE.' and DL.origin='.$this->db->escape(get_domain_auth_id()).' and DL.domain_key = '.$this->db->escape(get_domain_key());
							// debug($query);exit;
				$balance_record = $this->db->query($query)->row_array();
				;
                    $balance = $balance_record['balance'] + floatval($balance_record ['credit_limit']) + floatval($balance_record ['due_amount']);
		return $balance;
	}
	function agent_markup($price, $cntry_code='', $city_id=''){

		
		$markup_ary = array();
				$where=""; 
				// $product_id=$package_details['package_id'];\
			  	if($cntry_code)
	 			{ 
	 				$where .=" and ML.country='$cntry_code'";
	 			}
	 			if($city_id!='')
	 			{ 
	 				$where .=" and ML.city='$city_id'";
	 			}
	 			$query = 'SELECT ML.origin AS markup_origin, ML.type AS markup_type, ML.reference_id, ML.value, ML.value_type,  ML.markup_currency AS markup_currency,ML.country,ML.city,ML.product_id,act.package_name
	 			FROM markup_list AS ML 
	 			LEFT JOIN activity act ON ML.product_id=act.package_id
	 			where ML.module_type = "b2b_sightseeing" and
	 			ML.markup_level = "level_4"';
	 			$specific_data_list = $this->db->query($query)->result_array();
			//	debug($query);exit;
				if($specific_data_list){
					switch ($specific_data_list[0]['value_type']) {
	                    case 'percentage' :
	                        //Just need to calculate percentage of the values
	                        $markup_value = (($price / 100) * $specific_data_list[0]['value']);
	                        $original_markup = $specific_data_list[0]['value'];
	                        $markup_type = 'Percentage';
	                        break;
	                    case 'plus' :
	                        $original_markup = $specific_data_list[0]['value'];
	                        //convert value to required currency and then add the value
	                        // $temp_conversion = $this->currency_conversion_value(false, $__iv['markup_currency'], $this->to_currency);
	                        $markup_value =$specific_data_list[0]['value'];
	                        $markup_type = 'Plus';
	                        break;
                	}
                	return $markup_value;
				}else{
					$where="";
					if($cntry_code)
		 			{ 
		 				$where .=" and ML.country='$cntry_code'";
		 			}
		 			$where .=" and ML.city ='0'";
		 			$query = 'SELECT ML.origin AS markup_origin, ML.type AS markup_type, ML.reference_id, ML.value, ML.value_type,  ML.markup_currency AS markup_currency,ML.country,ML.city,ML.product_id,act.package_name
				FROM markup_list AS ML 
				LEFT JOIN activity act ON ML.product_id=act.package_id
				where ML.module_type = "b2b_sightseeing" and
				ML.markup_level = "level_4" and ML.type="generic" and ML.domain_list_fk='.get_domain_auth_id().' and ML.user_oid='.$this->entity_user_id.' '.$where;
				$specific_data_list = $this->db->query($query)->result_array();
				// debug($specific_data_list);exit;
				// debug($query);exit;
				if($specific_data_list){
					switch ($specific_data_list[0]['value_type']) {
	                    case 'percentage' :
	                        //Just need to calculate percentage of the values
	                        $markup_value = (($price / 100) * $specific_data_list[0]['value']);
	                        $original_markup = $specific_data_list[0]['value'];
	                        $markup_type = 'Percentage';
	                        break;
	                    case 'plus' :
	                        $original_markup = $specific_data_list[0]['value'];
	                        //convert value to required currency and then add the value
	                        // $temp_conversion = $this->currency_conversion_value(false, $__iv['markup_currency'], $this->to_currency);
	                        $markup_value =$specific_data_list[0]['value'];
	                        $markup_type = 'Plus';
	                        break;
                	}
                	return $markup_value;
				}else{
					$where=""; 
				// $product_id=$package_details['package_id'];\
	 			if($city_id!='')
	 			{ 
	 				$where .=" and ML.city='$city_id'";
	 			}
	 			$where .=" and ML.country=' '";
	 			$query = 'SELECT ML.origin AS markup_origin, ML.type AS markup_type, ML.reference_id, ML.value, ML.value_type,  ML.markup_currency AS markup_currency,ML.country,ML.city,ML.product_id,act.package_name
				FROM markup_list AS ML 
				LEFT JOIN activity act ON ML.product_id=act.package_id
				where ML.module_type = "b2b_sightseeing" and 
				ML.markup_level = "level_4" and ML.type="generic" and ML.domain_list_fk='.get_domain_auth_id().' and ML.user_oid='.$this->entity_user_id.' '.$where;
				$specific_data_list = $this->db->query($query)->result_array();
				// debug($specific_data_list);exit;
				if($specific_data_list){
					switch ($specific_data_list[0]['value_type']) {
	                    case 'percentage' :
	                        //Just need to calculate percentage of the values
	                        $markup_value = (($price / 100) * $specific_data_list[0]['value']);
	                        $original_markup = $specific_data_list[0]['value'];
	                        $markup_type = 'Percentage';
	                        break;
	                    case 'plus' :
	                        $original_markup = $specific_data_list[0]['value'];
	                        //convert value to required currency and then add the value
	                        // $temp_conversion = $this->currency_conversion_value(false, $__iv['markup_currency'], $this->to_currency);
	                        $markup_value =$specific_data_list[0]['value'];
	                        $markup_type = 'Plus';
	                        break;
                	}
                	return $markup_value;
				}else{
					$where .=" and ML.country=' '";
					$where .=" and ML.city ='0'";
					$query = 'SELECT ML.origin AS markup_origin, ML.type AS markup_type, ML.reference_id, ML.value, ML.value_type,  ML.markup_currency AS markup_currency,ML.country,ML.city,ML.product_id,act.package_name
				FROM markup_list AS ML 
				LEFT JOIN activity act ON ML.product_id=act.package_id
				where ML.module_type = "b2b_sightseeing" and
				ML.markup_level = "level_4" and ML.type="generic" and ML.domain_list_fk='.get_domain_auth_id().' and ML.user_oid='.$this->entity_user_id;
				
				$specific_data_list = $this->db->query($query)->result_array();
				if($specific_data_list){
					switch ($specific_data_list[0]['value_type']) {
	                    case 'percentage' :
	                        //Just need to calculate percentage of the values
	                        $markup_value = (($price / 100) * $specific_data_list[0]['value']);
	                        $original_markup = $specific_data_list[0]['value'];
	                        $markup_type = 'Percentage';
	                        break;
	                    case 'plus' :
	                        $original_markup = $specific_data_list[0]['value'];
	                        //convert value to required currency and then add the value
	                        // $temp_conversion = $this->currency_conversion_value(false, $__iv['markup_currency'], $this->to_currency);
	                        $markup_value =$specific_data_list[0]['value'];
	                        $markup_type = 'Plus';
	                        break;
                	}
                	return $markup_value;
				}else{
						return 0;	
				}
				}
				}
				}
	}
	

function get_monthly_booking_summary($condition=array())
    {
    	
        //Balu A
     
      //debug($condition);exit;
        $query = 'select count(distinct(BD.app_reference)) AS total_booking, 
                sum(SBID.total_fare+BD.agent_markup) as monthly_payment, sum(BD.agent_markup) as monthly_earning, 
                MONTH(BD.created_datetime) as month_number 
                from activity_booking_details AS BD
                join activity_booking_transaction_details AS SBID on BD.app_reference=SBID.app_reference
                where (YEAR(BD.created_datetime) BETWEEN '.date('Y').' AND '.date('Y', strtotime('+1 year')).')  and BD.domain_origin='.get_domain_auth_id().' AND BD.created_by_id = '.intval($this->entity_user_id).'
                GROUP BY YEAR(BD.created_datetime), 
                MONTH(BD.created_datetime) and BD.module_type = "activity"';
        return $this->db->query($query)->result_array();
    }
}
