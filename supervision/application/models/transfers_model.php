<?php
/**
 * @package    Provab Application
 * @subpackage Travel Portal
 * @author     Arjun J<arjunjgowda260389@gmail.com>
 * @version    V2
 */
class Transfers_Model extends CI_Model {
	public function __construct() {
		parent::__construct ();
	}

	public function package_view_data_types() {
	    $this->db->where ( "module_type", 'transfers' );
	    $this->db->order_by ( "package_types_id", "desc" );
		$q= $this->db->get ( 'package_types' );
		//echo $this->db->last_query();exit;
		return $q;
	}

	public function get_weekdays() { 
	    // $this->db->where ( "module_type", 'transfers' );
		$q= $this->db->get ( 'config_weekdays' );
		return $q;
	}
 	public function record_activation($table,$id,$status,$table_id_name='id') {
		$query = "update ".$table." set status='$status' where ".$table_id_name."='$id'";
		$result = $this->db->query($query);
	    return  $result;
	}
	function check_nationality_duplicate($tours_continent,$package_name){
	   $this->db->select("*");
	   $this->db->from("all_nationality_country");
	   $this->db->where('name',$package_name);
	   $this->db->where('continent',$tours_continent);
	   $this->db->where('module','transfers');
	   $res = $this->db->get()->result_array(); 
	   return $res;
	}
 

	function get_active_city($origin,$select_city=''){
		$city = $this->custom_db->single_table_records('car_active_country_city_master','*',array('country_id'=>$origin));
		$sHtml = "";
		
		$sHtml .= '<option value="">Select City</option>';
		if ($city['status']==1) {
			foreach ( $city['data'] as $key => $value ) {
				$select = '';
				if($select_city){
					if($select_city==$value['origin']){
						$select = "selected=selected";
					}
				}
				$sHtml .= '<option value="' . $value['origin']. '" '.$select.'>' . $value['city_name'] . '</option>';
			}
		}
		
		echo json_encode ( array (
				'result' => $sHtml 
		) );
		exit ();
	}


	public function get_countries() {
		$this->db->limit ( 1000 );
		$this->db->order_by ( "name", "asc" );
		
		$qur = $this->db->get ( "country" );
		return $qur->result ();
	}
	public function package_type_data() {
		return $this->db->get ( 'package_types' );
	}
	public function add_new_package($newpackage) {
		//debug($newpackage);exit();
		$this->db->insert ( 'package', $newpackage );
		// echo $this->db->last_query();exit();
		return  $this->db->insert_id ();
	}
	public function update_code_package($packcode, $package) {
		$this->db->where ( 'package_id', $package );
		return $this->db->update ( 'package', $packcode );
	}
	public function itinerary($itinerary) {
		$this->db->insert ( 'package_itinerary', $itinerary );
		return $this->db->insert_id ();
	}
	public function que_ans($que_ans) {
		$this->db->insert ( 'package_que_ans', $que_ans );
		return $this->db->insert_id ();
	}
	public function pricing_policy($pricingpolicy) {
		$this->db->insert ( 'package_pricing_policy', $pricingpolicy );
		return $this->db->insert_id ();
	}
	public function cancellation_penality($cancellation) {
		$this->db->insert ( 'package_cancellation', $cancellation );
		return $this->db->insert_id ();
	}
	public function deals($deals) {
		$this->db->insert ( 'package_deals', $deals );
		return $this->db->insert_id ();
	}
	public function travel_images($traveller) {
		$this->db->insert ( 'package_traveller_photos', $traveller );
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
		if ($this->db->update ( 'package', $data, $where )) {
			return $status;
		} else {
			return '0';
		}
	}
	public function update_status_type($id, $status) {
		$data = array (
				'status' => $status 
		);
		$where = "package_types_id = " . $id;
		if ($this->db->update ( 'package_types', $data, $where )) {
			return $status;
		} else {
			return '0';
		}
	}
	public function update_status_policy($id, $status) {
		$data = array (
				'status' => $status 
		);
		$where = "id = " . $id;
		if ($this->db->update ( 'transfer_cancellation', $data, $where )) {
			return $status;
		} else {
			return '0';
		}
	}
	public function update_approval_status($approve_status,$transfer_id) {
		$data = array (
				'admin_approve_status' => $approve_status 
		);
		$where = "id = " . $transfer_id;
		if ($this->db->update ( 'transfer_info', $data, $where )) {
			return $status;
		} else {
			return '0';
		}
	}
	public function update_enquiry_status($id, $status) {
		$data = array (
				'status' => $status 
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
		if ($this->db->update ( 'package', $data, $where )) {
			return true;
		} else {
			return false;
		}
	}
	public function get_package_id($package_id) {
		$this->db->select ( "*" );
		$this->db->from ( "package" );
		$this->db->join ( 'package_cancellation', 'package_cancellation.package_id = package.package_id' );
		$this->db->join ( 'package_pricing_policy', 'package_pricing_policy.package_id = package.package_id' );
		$this->db->where ( 'package.package_id', $package_id );
		return $this->db->get ()->row ();
	}
	public function get_price($package_id) {
		$this->db->from ( 'package_duration' );
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
		$this->db->from ( 'package_itinerary' );
		$this->db->where ( 'package_id', $package_id );
		$query = $this->db->get ();
		if ($query->num_rows > 0) {
			
			return $query->result ();
		}
		return false;
	}
	public function get_que_ans($package_id) {
		$this->db->from ( 'package_que_ans' );
		$this->db->where ( 'package_id', $package_id );
		$query = $this->db->get ();
		
		if ($query->num_rows > 0) {
			
			return $query->result ();
		}
		return false;
	}
	public function with_price() {
		$q = $this->db->query ( "select * from package where module_type='transfers' order by package_id DESC" );
		if ($q->num_rows () > 0) {
			return $q->result ();
		}
		return array ();
	}
	public function get_country_name($id) {
		$this->db->select ( 'name' );
		$this->db->where ( 'country_id', $id );
		return $this->db->get ( 'country' )->row ();
	}
	/*
	 * 
	 * processing holiday package
	 * 
	 * */
	public function enquiries() {
			
		$this->db->select('PE.id,PE.package_id,PE.pax,PE.first_name,PE.enquiry_reference_no,PE.last_name,PE.email,PE.phone,PE.address,PE.date,PE.status AS enquiry_status,
		                    PE.package_type,PE.package_name,PE.package_duration,PE.nationality,PE.with_or_without,
		                    PE.package_description,PE.ip_address,PE.place,PE.message,PE.domain_list_fk,
		                    P.package_code,P.supplier_id,P.tour_types,P.package_tour_code,
		                    P.duration,P.image,P.package_country,P.package_state,P.package_city,P.package_location,P.price_includes,
		                    P.deals,P.no_que,P.home_page,P.rating,P.price,P.display,P.top_destination,P.package_id,P.status AS package_status');
	   
	    $this->db->from ( 'package_enquiry AS PE' );		
		$this->db->join ( 'package AS P', 'P.package_id=PE.package_id' );
		$this->db->order_by ( 'PE.id', "desc" );
		$query = $this->db->get ();
		if ($query->num_rows > 0) {
			return $query->result ();
		}
		return false;
	
	}
	
	public function get_crs_city_list($value) { 
		$this->db->where ( 'country',
		$value ); return $this->db->get ( 'crs_city' )->result ();
	}

	 public function get_tour_list($value) { $this->db->where ( 'package_types_id',
	$value ); return $this->db->get ( 'package_types' )->result (); } public
	function update_edit_package($package_id, $data) { $where = "package_id =
	" . $package_id; if ($this->db->update ( 'package', $data, $where )) {
	return true; } else { return false; } } public function
	update_edit_policy($package_id, $policy) { $where = "package_id = " .
	$package_id; if ($this->db->update ( 'package_pricing_policy', $policy,
	$where )) { return true; } else { return false; } } public function
	update_edit_can($package_id, $can) { $where = "package_id = " .
	$package_id; if ($this->db->update ( 'package_cancellation', $can, $where
	)) { return true; } else { return false; } } public function
	update_edit_dea($package_id, $dea) { $where = "package_id = " .
	$package_id; if ($this->db->update ( 'package_deals', $dea, $where )) {
	return true; } else { return false; } } public function
	update_edit_pri($package_id, $pri) { $where = "package_id = " .
	$package_id; if ($this->db->update ( 'package_duration', $pri, $where )) {
	return true; } else { return false; } } public function
	get_image($package_id) { $this->db->from ( 'package_traveller_photos' );
	$this->db->where ( 'package_id', $package_id ); $query = $this->db->get
	(); if ($query->num_rows > 0) {
			
			return $query->result ();
		}
		return false;
	}
	public function update_itinerary($package, $itinerary_id, $data) {
		$where = "package_id = " . $package;
		$where = "iti_id = " . $itinerary_id;
		if ($this->db->update ( 'package_itinerary', $data, $where )) {
			return true;
		} else {
			return false;
		}
	}
	public function delete_traveller_img($pack_id, $img_id) {
		$this->db->where ( 'package_id', $img_id );
		$this->db->where ( 'img_id', $pack_id );
		$this->db->delete ( 'package_traveller_photos' );
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
		$this->db->where ( 'package_types_id', $id );
		$this->db->delete ( 'package_types' );
	}
	public function delete_package($id) {
		$this->db->where ( 'package_id', $id );
		$this->db->delete ( 'package' );
	}
	public function get_pack_id($id) {
		$this->db->select ( '*' );
		$this->db->where ( 'package_types_id', $id );
		return $this->db->get ( 'package_types' )->result ();
	}
	public function update_package_type($add_package_data, $id) {
		$this->db->where ( 'package_types_id', $id );
		$this->db->update ( 'package_types', $add_package_data );
	}


	///new Code ...
	public function add_new_transfer($data) {
		$this->db->insert ( 'transfer_info', $data );
		return  $this->db->insert_id ();
	}
    public function transfer_list($user_type) {
		$q = $this->db->query ( "select a.*,U.first_name,U.last_name from transfer_info as a join user U on U.user_id = a.created_by_id where a.user_type=".$user_type." order by a.id DESC" );
		if ($q->num_rows () > 0) {
			return $q->result ();
		}
		return array ();
	}

	public function get_transfer_data($id) {
		$this->db->select ( "*" );
		$this->db->from ( "transfer_info" );
		$this->db->where ( 'transfer_info.id', $id );
		return $this->db->get ()->row ();
	}

	public function get_transfer_driver_data($id) {
		$this->db->select ( "*" );
		$this->db->from ( "transfer_driver_info" );
		$this->db->where ( 'transfer_driver_info.reference_id', $id );
		$query = $this->db->get ();
		if ($query->num_rows > 0) {
			
			return $query->result ();
		}
		// debug( $this->db->get ()->row (););exit;
		// return $this->db->get ()->row ();
	}

	public function get_transfer_price_data($id) {
		$this->db->select ( "*" );
		$this->db->from ( "transfer_price_info" );
		$this->db->where ( 'transfer_price_info.reference_id', $id );
		$query = $this->db->get ();
		if ($query->num_rows > 0) {
			
			return $query->result ();
		}
	}

	public function update_transfer_data($data, $id) {
		$this->db->where ( 'id', $id );
		return $this->db->update ( 'transfer_info', $data );
	}
    
    public function delete_transfer($id) {
		$this->db->where ( 'id', $id );
		$this->db->delete ( 'transfer_info' );
	}

	public function transfer_driver_details($data) {
		$this->db->insert ( 'transfer_driver_info', $data );
		return  $this->db->insert_id ();
	}

	public function transfer_price_details($data) {
		$this->db->insert ( 'transfer_price_info', $data );
		return  $this->db->insert_id ();
	}

	public function update_transfer_driver_data($data, $id) {
		$this->db->where ( 'id', $id );
		return $this->db->update ( 'transfer_driver_info', $data );
	}

	public function update_transfer_price_data($data, $id) {
		$this->db->where ( 'id', $id );
		return $this->db->update ( 'transfer_price_info', $data );
	}
//transfer_vehicle_info
	public function get_transfer_vehicle_data($id) {
		$this->db->select ( "*" );
		$this->db->from ( "transfer_vehicle_info" );
		$this->db->where ( 'transfer_vehicle_info.id', $id );
		return $this->db->get ()->row ();
	}

	public function add_transfer_vehicle($data) {
		$this->db->insert ( 'transfer_vehicle_info', $data );
		return  $this->db->insert_id ();
	}

	  public function transfer_vehicle_list() {
		$q = $this->db->query ( "select * from transfer_vehicle_info where created_by=".$GLOBALS['CI']->entity_user_id." order by id DESC" );
		if ($q->num_rows () > 0) {
			return $q->result ();
		}
		return array ();
	}

	  public function delete_transfer_vehicle($id) {
		$this->db->where ( 'id', $id );
		$this->db->delete ( 'transfer_vehicle_info' );
	}

	public function update_transfer_vehicle_data($data, $id) {
		$this->db->where ( 'id', $id );
		return $this->db->update ( 'transfer_vehicle_info', $data );
	}

	public function update_vehicle_status($id, $status) {
		$data = array (
				'status' => $status 
		);
		$where = "id = " . $id;
		if ($this->db->update ( 'transfer_vehicle_info', $data, $where )) {
			return $status;
		} else {
			return '0';
		}
	}

	//transfer_driver_info
	public function get_transfer_driver($id) {
		$this->db->select ( "*" );
		$this->db->from ( "transfer_driver_info" );
		$this->db->where ( 'transfer_driver_info.id', $id );
		return $this->db->get ()->row ();
	}
	public function get_all_vehicle_data() {
		$this->db->select ( "*" );
		$this->db->from ( "transfer_vehicle_info" );
		$this->db->where ( 'status','ACTIVE' );
		$query = $this->db->get ();
		if ($query->num_rows > 0) {
			
			return $query->result ();
		}
	}
	public function get_all_driver_data() {
		$this->db->select ( "*" );
		$this->db->from ( "transfer_driver_info" );
		$this->db->where ( 'status','ACTIVE' );
		$query = $this->db->get ();
		if ($query->num_rows > 0) {
			
			return $query->result ();
		}
	}

	public function add_transfer_driver($data) {
		$this->db->insert ( 'transfer_driver_info', $data );
		return  $this->db->insert_id ();
	}

	  public function transfer_driver_list() {
		$q = $this->db->query ( "select *,country_list.country_name from transfer_driver_info INNER JOIN country_list ON country_list.country_list=transfer_driver_info.country order by id DESC" );
		if ($q->num_rows () > 0) {
			return $q->result ();
		}
		return array ();
	}

	  public function delete_transfer_driver($id) {
		$this->db->where ( 'id', $id );
		$this->db->delete ( 'transfer_driver_info' );
	}

	public function update_transfer_driver($data, $id) {
		$this->db->where ( 'id', $id );
		return $this->db->update ( 'transfer_driver_info', $data );
	}

	public function update_driver_status($id, $status) {
		$data = array (
				'status' => $status 
		);
		$where = "id = " . $id;
		if ($this->db->update ( 'transfer_driver_info', $data, $where )) {
			return $status;
		} else {
			return '0';
		}
	}

	//Add vehicle driver
	public function add_map_vehicle_driver($data) {
		$this->db->insert ( 'transfer_map_vehicle_driver', $data );
		return  $this->db->insert_id ();
	}
	public function update_map_vehicle_driver($data,$id) {
     $this->db->where ( 'id', $id );
    $this->db->update ( 'transfer_map_vehicle_driver', $data );
    return  $id;
  }

	public function get_vehicle_driver_data($id) {
		$this->db->select ( "*,VD.id as id,V.id as vehicle_id,D.id as driver_id,D.driver_name,V.vehicle_name");
		$this->db->from ( "transfer_map_vehicle_driver as VD" );
		$this->db->where ( 'VD.reference_id', $id );
		$this->db->join ( 'transfer_driver_info AS D', 'D.id=VD.driver_id' );
		$this->db->join ( 'transfer_vehicle_info AS V', 'V.id=VD.vehicle_id' );
		$this->db->order_by ( 'VD.day', "asc" );
		// return $this->db->get ()->row ();
		$query = $this->db->get ();
		if ($query->num_rows > 0) {
			
			return $query->result ();
		}
	}
	function get_driver_details($params, $shift_time_start, $shift_time_end){
		// $this->db->select ( "*");
		// $this->db->from ( "transfer_driver_info" );
		// $this->db->where ( 'created_by', $GLOBALS['CI']->entity_user_id );
		// $this->db->where ( 'driver_shift_from <=', $shift_time_start );
		// $this->db->where ( 'driver_shift_to >=', $shift_time_end );
		// // return $this->db->get ()->row ();
		// $query = $this->db->get ();
		// if ($query->num_rows > 0) {
			
		// 	return $query->result ();
		// }
	$query = ' select c.* from transfer_driver_info as c WHERE id NOT IN (select a.driver_id from transfer_map_vehicle_driver as a join transfer_duration as b on b.id=a.date_range_id where (b.start_date <= '.$params['start_date'].' and b.expiry_date >='.$params['end_date'].' ) OR (b.start_date >= '.$params['start_date'].' and b.expiry_date <='.$params['end_date'].') and shift_time_from <= "'.$shift_time_start.'" and shift_time_to >= '.$shift_time_end.' ) and driver_shift_from <= "'.$shift_time_start.'" and driver_shift_to >= '.$shift_time_end.' and created_by = '.$GLOBALS['CI']->entity_user_id.' and status="ACTIVE" ';
	$result= $this->db->query($query)->result();
	return $result;

	}
	function get_vehicle_details($params, $shift_time_start, $shift_time_end){
		$query = ' select c.id,c.vehicle_type,c.vehicle_name from transfer_vehicle_info as c WHERE id NOT IN (select a.vehicle_id from transfer_map_vehicle_driver as a join transfer_duration as b on b.id=a.date_range_id where (b.start_date <= '.$params['start_date'].' and b.expiry_date >='.$params['end_date'].' ) OR (b.start_date >= '.$params['start_date'].' and b.expiry_date <='.$params['end_date'].') and shift_time_from <= "'.$shift_time_start.'" and shift_time_to >= '.$shift_time_end.' ) and created_by = '.$GLOBALS['CI']->entity_user_id.' and status="ACTIVE" ';
	$result= $this->db->query($query)->result();
	return $result;
	}
	public function update_transfer_status($id, $status) {
		$data = array (
				'status' => $status 
		);
		$where = "id = " . $id;
		if ($this->db->update ( 'transfer_info', $data, $where )) {
			return $status;
		} else {
			return '0';
		}
	}

	public function get_currency($country_id ){
		$this->db->select ( "currency_code");
		$this->db->from ("country_list");
		$this->db->where ( 'country_list', $country_id );
		$qur = $this->db->get ();
		return $qur->result ();
	}

	public function get_country_list() {
		$this->db->limit ( 1000 );
		$this->db->order_by ( "country_name", "asc" );
		
		$qur = $this->db->get ( "country_list" );
		return $qur->result ();
	}

	public function get_transfer_price_nationality($id) {
		$this->db->select ( "nationality_group,nationality_group_name,date_range" );
		$this->db->from ( "transfer_price_info" );
		$this->db->where ( 'transfer_price_info.reference_id', $id );
	//	$this->db->group_by ('date_range');
		$query = $this->db->get ();
		// echo $this->db->last_query();exit;
		if ($query->num_rows > 0) {
			
			return $query->result ();
		}
	}

	 public function delete_mapping_vehicle($id) {
        $data = array('id'=> $id);
        $this->db->delete('transfer_map_vehicle_driver', $data);
        $this->db->affected_rows();
        return true;
    }

    public function get_mappping_vehicle($id ){
		$this->db->select ( "*,transfer_map_vehicle_driver.id as c_id");
		$this->db->from ("transfer_map_vehicle_driver");
		$this->db->join ( 'transfer_duration', 'transfer_duration.id=transfer_map_vehicle_driver.date_range_id' );
		$this->db->where ( 'transfer_map_vehicle_driver.id', $id );
		$qur = $this->db->get ();
		//echo $this->db->last_query();exit;
		return $qur->result ();
	}


///get Airport list...........

	public function getAirportcitylist($key){

          if(strlen($key)==3){
			$this->db->select('city_code');
            $this->db->where('city_code',$key);
            $this->db->limit('1'); 
			$query = $this->db->get('iata_airport_list');   
			/*if(strlen($key)>3){
             $where="airport_name like '".$key."%'";
             $query = $this->db->query('select * from iata_airport_list where '.$where);   
			}*/

		 /* debug($this->db->last_query()); exit; */
          if($query->num_rows() > 0){
            return $query->result();
          }
	  }
    }

    public function getcountrylist($id)
 {
$this->db->select('name');
           $this->db->where('origin',$id);
           $query = $this->db->get('api_country_list'); 
           return $query->result();
 }
    public function getAirportcodelist($key){
          if(strlen($key)==3){
       $this->db->select('*');
           $this->db->where('city_code',$key);
           $query = $this->db->get('api_city_list');   
           }
            if(strlen($key)>3){
             $where="destination like '".$key."%'";
             $query = $this->db->query('select * from api_city_list where '.$where);   
      }
            #echo $this->db->last_query();
         if($query->num_rows() > 0){
            return $query->result();
          } 
    }

     public function getAirportlist($key){
          $sqlDestinations ="select airport_name,airport_id,city_code,airport_code,country,airport_city from iata_airport_list where city_code='$key'";
          $query = $this->db->query($sqlDestinations);
          if($query->num_rows() > 0){
            return $query->result();
          }
    }

    //multiple dates
    public function add_transfer_dates($data) {
		$this->db->insert ( 'transfer_duration', $data );
		return  $this->db->insert_id ();
	}

	public function update_transfer_dates($data, $id) {
		$this->db->where ( 'id', $id );
		return $this->db->update ( 'transfer_duration', $data );
	}

		public function get_transfer_dates($id) {
		$this->db->select ( "*" );
		$this->db->from ( "transfer_duration" );
		$this->db->where ( 'transfer_duration.reference_id', $id );
		$query = $this->db->get ();
		if ($query->num_rows > 0) {
			
			return $query->result ();
		}
	}
	public function get_transfer_cancel_charge($id) {
		$this->db->select ( "*" );
		$this->db->from ( "transfer_cancellation_price" );
		$this->db->where ( 'cancel_id', $id );
		$query = $this->db->get ();
		if ($query->num_rows > 0) {
			
			return $query->result ();
		}
	}
	public function check_seasonality_details($tran_id ,$seasonality_from,$seasonality_to='') {
		$this->db->select('*');
		$this->db->from('transfer_cancellation');
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
		// echo $this->db->last_query();exit;
		return $res->result();
	}
	function get_transfer_city_list($search_chars)
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
	  function filter_booking_reportcrs($search_filter_condition = '', $count=false, $offset=0, $limit=100000000000)
    {

    	// echo "";exit;
        if(empty($search_filter_condition) == false) {
            $search_filter_condition = ' and'.$search_filter_condition;
        }
        //BT, CD, ID
        if ($count) {
            $query = 'select count(distinct(BD.app_reference)) as total_records 
                    from  transfer_booking_details BD
                    join  transfer_booking_itinerary_details AS HBID on BD.app_reference=HBID.app_reference
                    join payment_option_list AS POL on BD.payment_mode=POL.payment_category_code 
                    where BD.domain_origin='.get_domain_auth_id().' and BD.created_by_id ='.$GLOBALS['CI']->entity_user_id.' '.$search_filter_condition;
            $data = $this->db->query($query)->row_array();
            return $data['total_records'];
        } else {
            $this->load->library('booking_data_formatter');
            $response['status'] = SUCCESS_STATUS;
            $response['data'] = array();
            $booking_itinerary_details  = array();
            $booking_customer_details   = array();
            $bd_query = 'select * from transfer_booking_details AS BD 
                        WHERE BD.domain_origin='.get_domain_auth_id().' and BD.created_by_id ='.$GLOBALS['CI']->entity_user_id.' '.$search_filter_condition.'
                        order by BD.origin desc limit '.$offset.', '.$limit;
            $booking_details = $this->db->query($bd_query)->result_array();
            $app_reference_ids = $this->booking_data_formatter->implode_app_reference_ids($booking_details);
            if(empty($app_reference_ids) == false) {
                $id_query = 'select * from transfer_booking_itinerary_details AS ID 
                            WHERE ID.app_reference IN ('.$app_reference_ids.')';
                $cd_query = 'select * from  transfer_booking_passenger_details AS CD 
                            WHERE  CD.app_reference IN ('.$app_reference_ids.') ';

                // $cancellation_details_query = 'select HCD.* from    transfer_cancellation_details AS HCD WHERE HCD.app_reference='.$this->db->escape($app_reference_ids);

                $booking_itinerary_details  = $this->db->query($id_query)->result_array();
                $booking_customer_details   = $this->db->query($cd_query)->result_array();
            }
            $response['data']['booking_details']            = $booking_details;
            $response['data']['booking_itinerary_details']  = $booking_itinerary_details;
            $response['data']['booking_customer_details']   = $booking_customer_details;
            // $response['data']['cancellation_details']   = $this->db->query($cancellation_details_query)->result_array();
            return $response;
        }
    }
    function filter_booking_report($search_filter_condition = '', $count=false, $offset=0, $limit=100000000000)
    {

    	// echo "";exit;
        if(empty($search_filter_condition) == false) {
            $search_filter_condition = ' and'.$search_filter_condition;
        }
        //BT, CD, ID
        if ($count) {
            $query = 'select count(distinct(BD.app_reference)) as total_records 
                    from  transfer_booking_details BD
                    join  transfer_booking_itinerary_details AS HBID on BD.app_reference=HBID.app_reference
                    join payment_option_list AS POL on BD.payment_mode=POL.payment_category_code 
                    where BD.domain_origin='.get_domain_auth_id().' and BD.created_by_id ='.$GLOBALS['CI']->entity_user_id.' '.$search_filter_condition;
            $data = $this->db->query($query)->row_array();
            return $data['total_records'];
        } else {
            $this->load->library('booking_data_formatter');
            $response['status'] = SUCCESS_STATUS;
            $response['data'] = array();
            $booking_itinerary_details  = array();
            $booking_customer_details   = array();
            $bd_query = 'select * from transfer_booking_details AS BD 
                        WHERE BD.domain_origin='.get_domain_auth_id().' and BD.created_by_id ='.$GLOBALS['CI']->entity_user_id.' '.$search_filter_condition.'
                        order by BD.origin desc limit '.$offset.', '.$limit;
            $booking_details = $this->db->query($bd_query)->result_array();
            $app_reference_ids = $this->booking_data_formatter->implode_app_reference_ids($booking_details);
            if(empty($app_reference_ids) == false) {
                $id_query = 'select * from transfer_booking_itinerary_details AS ID 
                            WHERE ID.app_reference IN ('.$app_reference_ids.')';
                $cd_query = 'select * from  transfer_booking_passenger_details AS CD 
                            WHERE  CD.app_reference IN ('.$app_reference_ids.') ';

                // $cancellation_details_query = 'select HCD.* from    transfer_cancellation_details AS HCD WHERE HCD.app_reference='.$this->db->escape($app_reference_ids);

                $booking_itinerary_details  = $this->db->query($id_query)->result_array();
                $booking_customer_details   = $this->db->query($cd_query)->result_array();
            }
            $response['data']['booking_details']            = $booking_details;
            $response['data']['booking_itinerary_details']  = $booking_itinerary_details;
            $response['data']['booking_customer_details']   = $booking_customer_details;
            // $response['data']['cancellation_details']   = $this->db->query($cancellation_details_query)->result_array();
            return $response;
        }
    }
    function filter_emulate_booking_report($search_filter_condition = '', $count=false, $offset=0, $limit=100000000000)
    {

    	// echo "";exit;
        if(empty($search_filter_condition) == false) {
            $search_filter_condition = ' and'.$search_filter_condition;
        }
        //BT, CD, ID
        if ($count) {
            $query = 'select count(distinct(BD.app_reference)) as total_records 
                    from  transfer_booking_details BD
                    join  transfer_booking_itinerary_details AS HBID on BD.app_reference=HBID.app_reference
                    join payment_option_list AS POL on BD.payment_mode=POL.payment_category_code 
                    where BD.domain_origin='.get_domain_auth_id().' and BD.emulate_booking = 1 and BD.created_by_id ='.$GLOBALS['CI']->entity_user_id.' '.$search_filter_condition;
            $data = $this->db->query($query)->row_array();
            //debug($data);exit;
            return $data['total_records'];
        } else {
            $this->load->library('booking_data_formatter');
            $response['status'] = SUCCESS_STATUS;
            $response['data'] = array();
            $booking_itinerary_details  = array();
            $booking_customer_details   = array();
            $bd_query = 'select * from transfer_booking_details AS BD 
                        WHERE BD.domain_origin='.get_domain_auth_id().' and BD.emulate_booking = 1 and BD.created_by_id ='.$GLOBALS['CI']->entity_user_id.' '.$search_filter_condition.'
                        order by BD.origin desc limit '.$offset.', '.$limit;
            $booking_details = $this->db->query($bd_query)->result_array();
            $app_reference_ids = $this->booking_data_formatter->implode_app_reference_ids($booking_details);
            if(empty($app_reference_ids) == false) {
                $id_query = 'select * from transfer_booking_itinerary_details AS ID 
                            WHERE ID.app_reference IN ('.$app_reference_ids.')';
                $cd_query = 'select * from  transfer_booking_passenger_details AS CD 
                            WHERE  CD.app_reference IN ('.$app_reference_ids.') ';

                // $cancellation_details_query = 'select HCD.* from    transfer_cancellation_details AS HCD WHERE HCD.app_reference='.$this->db->escape($app_reference_ids);

                $booking_itinerary_details  = $this->db->query($id_query)->result_array();
                $booking_customer_details   = $this->db->query($cd_query)->result_array();
            }
            $response['data']['booking_details']            = $booking_details;
            $response['data']['booking_itinerary_details']  = $booking_itinerary_details;
            $response['data']['booking_customer_details']   = $booking_customer_details;
            // $response['data']['cancellation_details']   = $this->db->query($cancellation_details_query)->result_array();
            return $response;
        }
    }



     function b2b_transfers_crs_report_filter($search_filter_condition = '', $count=false, $offset=0, $limit=100000000000)
    {
        if(empty($search_filter_condition) == false) {
            $search_filter_condition = ' and'.$search_filter_condition;
        }
        //BT, CD, ID
        if ($count) {
            $query = 'select count(distinct(BD.app_reference)) as total_records 
                    from  transfer_booking_details BD
                    join  transfer_booking_itinerary_details AS HBID on BD.app_reference=HBID.app_reference
                    join payment_option_list AS POL on BD.payment_mode=POL.payment_category_code 
                    where BD.domain_origin='.get_domain_auth_id().' and BD.created_by_id ='.$GLOBALS['CI']->entity_user_id.' '.$search_filter_condition;
            $data = $this->db->query($query)->row_array();
            return $data['total_records'];
        } else {
            $this->load->library('booking_data_formatter');
            $response['status'] = SUCCESS_STATUS;
            $response['data'] = array();
            $booking_itinerary_details  = array();
            $booking_customer_details   = array();
            $bd_query = 'select * from transfer_booking_details AS BD 
                        WHERE BD.domain_origin='.get_domain_auth_id().' and BD.created_by_id ='.$GLOBALS['CI']->entity_user_id.' '.$search_filter_condition.'
                        order by BD.origin desc limit '.$offset.', '.$limit;
            $booking_details = $this->db->query($bd_query)->result_array();
            $app_reference_ids = $this->booking_data_formatter->implode_app_reference_ids($booking_details);
            if(empty($app_reference_ids) == false) {
                $id_query = 'select * from transfer_booking_itinerary_details AS ID 
                            WHERE ID.app_reference IN ('.$app_reference_ids.')';
                $cd_query = 'select * from  transfer_booking_passenger_details AS CD 
                            WHERE  CD.app_reference IN ('.$app_reference_ids.') ';

                // $cancellation_details_query = 'select HCD.* from    transfer_cancellation_details AS HCD WHERE HCD.app_reference='.$this->db->escape($app_reference_ids);

                $booking_itinerary_details  = $this->db->query($id_query)->result_array();
                $booking_customer_details   = $this->db->query($cd_query)->result_array();
            }
            $response['data']['booking_details']            = $booking_details;
            $response['data']['booking_itinerary_details']  = $booking_itinerary_details;
            $response['data']['booking_customer_details']   = $booking_customer_details;
            // $response['data']['cancellation_details']   = $this->db->query($cancellation_details_query)->result_array();
            return $response;
        }
    }
    function booking($condition=array(), $count=false, $offset=0, $limit=100000000000)
    {
    	// echo "cc";exit;
        $condition = $this->custom_db->get_custom_condition($condition);
        //BT, CD, ID
        if ($count) {
            $query = 'select count(distinct(BD.app_reference)) as total_records 
                    from transfer_booking_details BD
                    join transfer_booking_itinerary_details AS HBID on BD.app_reference=HBID.app_reference
                    left join user U on U.user_id = BD.created_by_id
					left join user_type UT on UT.origin = U.user_type
                    
                    where (U.user_type='.B2B_USER.' OR BD.created_by_id = 0) and  BD.domain_origin='.get_domain_auth_id().' '.$condition;
            // echo $query;exit;
                    // join payment_option_list AS POL on BD.payment_mode=POL.payment_category_code 
            $data = $this->db->query($query)->row_array();
            // debug($data);exit;
            return $data['total_records'];
        } else {
            $this->load->library('booking_data_formatter');
            $response['status'] = SUCCESS_STATUS;
            $response['data'] = array();
            $booking_itinerary_details  = array();
            $booking_customer_details   = array();
            $cancellation_details = array();
            $bd_query = 'select *,BD.created_datetime as created_datetime,U.first_name,U.last_name,U.user_type,U.agent_staff,U.agent_staff_id,U.agency_name from transfer_booking_details AS BD 
           				 left join user U on U.user_id = BD.created_by_id
							left join user_type UT on UT.origin = U.user_type
                        WHERE (U.user_type='.B2B_USER.' OR BD.created_by_id = 0) and BD.domain_origin='.get_domain_auth_id().' and BD.module_type = "transfers" '.$condition.'
                        order by BD.origin desc limit '.$offset.', '.$limit;
            $booking_details = $this->db->query($bd_query)->result_array();
            $app_reference_ids = $this->booking_data_formatter->implode_app_reference_ids($booking_details);
            if(empty($app_reference_ids) == false) {
                $id_query = 'select * from transfer_booking_itinerary_details AS ID 
                            WHERE ID.app_reference IN ('.$app_reference_ids.')';
                $cd_query = 'select * from  transfer_booking_passenger_details AS CD 
                            WHERE  CD.app_reference IN ('.$app_reference_ids.') ';
                // $cancellation_details_query = 'select * from transfer_cancellation_details AS HCD 
                //             WHERE HCD.app_reference IN ('.$app_reference_ids.')';
                $booking_itinerary_details  = $this->db->query($id_query)->result_array();
                $booking_customer_details   = $this->db->query($cd_query)->result_array();
                // $cancellation_details   = $this->db->query($cancellation_details_query)->result_array();
            }
            $response['data']['booking_details']            = $booking_details;
            $response['data']['booking_itinerary_details']  = $booking_itinerary_details;
            $response['data']['booking_customer_details']   = $booking_customer_details;
            // $response['data']['cancellation_details']   = $cancellation_details;
            // debug($booking_details);exit;
            return $response;
        }
    }
 function bookingcrs($condition=array(), $count=false, $offset=0, $limit=100000000000)
    {
    	// echo "cc";exit;
        $condition = $this->custom_db->get_custom_condition($condition);
        //BT, CD, ID
        if ($count) {
            $query = 'select count(distinct(BD.app_reference)) as total_records 
                    from transfer_booking_details BD
                    join transfer_booking_itinerary_details AS HBID on BD.app_reference=HBID.app_reference
                    left join user U on U.user_id = BD.created_by_id
					left join user_type UT on UT.origin = U.user_type
                    
                    where (U.user_type='.B2C_USER.' OR BD.created_by_id = 0) and  BD.domain_origin='.get_domain_auth_id().' '.$condition;
            // echo $query;exit;
                    // join payment_option_list AS POL on BD.payment_mode=POL.payment_category_code 
            $data = $this->db->query($query)->row_array();
            // debug($data);exit;
            return $data['total_records'];
        } else {
            $this->load->library('booking_data_formatter');
            $response['status'] = SUCCESS_STATUS;
            $response['data'] = array();
            $booking_itinerary_details  = array();
            $booking_customer_details   = array();
            $cancellation_details = array();
            $bd_query = 'select *,BD.created_datetime as created_datetime,U.first_name,U.last_name,U.user_type,U.agent_staff,U.agent_staff_id,U.agency_name from transfer_booking_details AS BD 
           				 left join user U on U.user_id = BD.created_by_id
							left join user_type UT on UT.origin = U.user_type
                        WHERE (U.user_type='.B2C_USER.' OR BD.created_by_id = 0) and BD.domain_origin='.get_domain_auth_id().' and BD.module_type = "transfers" '.$condition.'
                        order by BD.origin desc limit '.$offset.', '.$limit;
            $booking_details = $this->db->query($bd_query)->result_array();
            $app_reference_ids = $this->booking_data_formatter->implode_app_reference_ids($booking_details);
            if(empty($app_reference_ids) == false) {
                $id_query = 'select * from transfer_booking_itinerary_details AS ID 
                            WHERE ID.app_reference IN ('.$app_reference_ids.')';
                $cd_query = 'select * from  transfer_booking_passenger_details AS CD 
                            WHERE  CD.app_reference IN ('.$app_reference_ids.') ';
                // $cancellation_details_query = 'select * from transfer_cancellation_details AS HCD 
                //             WHERE HCD.app_reference IN ('.$app_reference_ids.')';
                $booking_itinerary_details  = $this->db->query($id_query)->result_array();
                $booking_customer_details   = $this->db->query($cd_query)->result_array();
                // $cancellation_details   = $this->db->query($cancellation_details_query)->result_array();
            }
            $response['data']['booking_details']            = $booking_details;
            $response['data']['booking_itinerary_details']  = $booking_itinerary_details;
            $response['data']['booking_customer_details']   = $booking_customer_details;
            // $response['data']['cancellation_details']   = $cancellation_details;
            // debug($booking_details);exit;
            return $response;
        }
    }
    function emulate_user($user_id){

    	 $query = 'select first_name,last_name,user_id,agency_name from user where  user_id = "'.$user_id.'"  ';
    	   $emulate_details = $this->db->query($query)->row_array();
    	     $response['data']['emulate_details'] = $emulate_details;
    	      return $response;

    }
    function emulate_booking($condition=array(), $count=false, $offset=0, $limit=100000000000)
    {
    	// echo "cc";exit;
        $condition = $this->custom_db->get_custom_condition($condition);
        //BT, CD, ID
        if ($count) {
            $query = 'select count(distinct(BD.app_reference)) as total_records 
                    from transfer_booking_details BD
                    join transfer_booking_itinerary_details AS HBID on BD.app_reference=HBID.app_reference
                    left join user U on U.user_id = BD.created_by_id
					left join user_type UT on UT.origin = U.user_type
                    
                    where (U.user_type='.B2B_USER.' OR BD.created_by_id = 0) and BD.emulate_booking = 1 and BD.domain_origin='.get_domain_auth_id().' '.$condition;
            // echo $query;exit;
                    // join payment_option_list AS POL on BD.payment_mode=POL.payment_category_code 
            $data = $this->db->query($query)->row_array();
            // debug($data);exit;
            return $data['total_records'];
        } else {
            $this->load->library('booking_data_formatter');
            $response['status'] = SUCCESS_STATUS;
            $response['data'] = array();
            $booking_itinerary_details  = array();
            $booking_customer_details   = array();
            $cancellation_details = array();
            $bd_query = 'select *,BD.created_datetime as created_datetime from transfer_booking_details AS BD 
           				 left join user U on U.user_id = BD.created_by_id
							left join user_type UT on UT.origin = U.user_type
                        WHERE (U.user_type='.B2B_USER.' OR BD.created_by_id = 0) and BD.domain_origin='.get_domain_auth_id().' and BD.module_type = "transfers" and BD.emulate_booking = 1 '.$condition.'
                        order by BD.origin desc limit '.$offset.', '.$limit;
            $booking_details = $this->db->query($bd_query)->result_array();
            $app_reference_ids = $this->booking_data_formatter->implode_app_reference_ids($booking_details);
            if(empty($app_reference_ids) == false) {
                $id_query = 'select * from transfer_booking_itinerary_details AS ID 
                            WHERE ID.app_reference IN ('.$app_reference_ids.')';
                $cd_query = 'select * from  transfer_booking_passenger_details AS CD 
                            WHERE  CD.app_reference IN ('.$app_reference_ids.') ';
                // $cancellation_details_query = 'select * from transfer_cancellation_details AS HCD 
                //             WHERE HCD.app_reference IN ('.$app_reference_ids.')';
                $booking_itinerary_details  = $this->db->query($id_query)->result_array();
                $booking_customer_details   = $this->db->query($cd_query)->result_array();
                // $cancellation_details   = $this->db->query($cancellation_details_query)->result_array();
            }
            $response['data']['booking_details']            = $booking_details;
            $response['data']['booking_itinerary_details']  = $booking_itinerary_details;
            $response['data']['booking_customer_details']   = $booking_customer_details;
            // $response['data']['cancellation_details']   = $cancellation_details;
            // debug($booking_details);exit;
            return $response;
        }
    }
    function booking_staff($condition=array(), $count=false, $offset=0, $limit=100000000000)
    {
    	// echo "cc";exit;
        $condition = $this->custom_db->get_custom_condition($condition);
        //BT, CD, ID
        if ($count) {
     //        $query = 'select count(distinct(BD.app_reference)) as total_records 
     //                from transfer_booking_details BD
     //                join transfer_booking_itinerary_details AS HBID on BD.app_reference=HBID.app_reference
     //                left join user U on U.user_id = BD.created_by_id
					// left join user_type UT on UT.origin = U.user_type
     //                join payment_option_list AS POL on BD.payment_mode=POL.payment_category_code 
     //                where (U.user_type='.STAFF.' OR BD.created_by_id = 0) and  BD.domain_origin='.get_domain_auth_id().' '.$condition;
	         $query = 'select count(distinct(BD.app_reference)) as total_records 
	        from transfer_booking_details BD
	        join transfer_booking_itinerary_details AS HBID on BD.app_reference=HBID.app_reference
	        left join user U on U.user_id = BD.created_by_id
			left join user_type UT on UT.origin = U.user_type
	        where (U.user_type='.STAFF.' OR BD.created_by_id = 0) and  BD.domain_origin='.get_domain_auth_id().' '.$condition;
            // echo $query;exit;
            $data = $this->db->query($query)->row_array();
            // debug($data);exit;
            return $data['total_records'];
        } else {
            $this->load->library('booking_data_formatter');
            $response['status'] = SUCCESS_STATUS;
            $response['data'] = array();
            $booking_itinerary_details  = array();
            $booking_customer_details   = array();
            $cancellation_details = array();
            $bd_query = 'select * from transfer_booking_details AS BD 
           				 left join user U on U.user_id = BD.created_by_id
							left join user_type UT on UT.origin = U.user_type
                        WHERE (U.user_type='.STAFF.' OR BD.created_by_id = 0) and BD.domain_origin='.get_domain_auth_id().' and BD.module_type = "transfers" '.$condition.'
                        order by BD.origin desc limit '.$offset.', '.$limit;
            $booking_details = $this->db->query($bd_query)->result_array();
            $app_reference_ids = $this->booking_data_formatter->implode_app_reference_ids($booking_details);
            if(empty($app_reference_ids) == false) {
                $id_query = 'select * from transfer_booking_itinerary_details AS ID 
                            WHERE ID.app_reference IN ('.$app_reference_ids.')';
                $cd_query = 'select * from  transfer_booking_passenger_details AS CD 
                            WHERE  CD.app_reference IN ('.$app_reference_ids.') ';
                // $cancellation_details_query = 'select * from transfer_cancellation_details AS HCD 
                //             WHERE HCD.app_reference IN ('.$app_reference_ids.')';
                $booking_itinerary_details  = $this->db->query($id_query)->result_array();
                $booking_customer_details   = $this->db->query($cd_query)->result_array();
                // $cancellation_details   = $this->db->query($cancellation_details_query)->result_array();
            }
            $response['data']['booking_details']            = $booking_details;
            $response['data']['booking_itinerary_details']  = $booking_itinerary_details;
            $response['data']['booking_customer_details']   = $booking_customer_details;
            // $response['data']['cancellation_details']   = $cancellation_details;
            return $response;
        }
    }
    function get_booking_details_transfer($app_reference, $booking_source, $booking_status='')
    {
    	// $booking_status="BOOKING_CONFIRMED";
        $response['status'] = FAILURE_STATUS;
        $response['data'] = array();

       
        
        	 $bd_query = 'select * from transfer_booking_details AS BD WHERE BD.app_reference like '.$this->db->escape($app_reference);
        if (empty($booking_source) == false) {
            $bd_query .= '  AND BD.booking_source = '.$this->db->escape($booking_source);
        }
        // if (empty($booking_status) == false) {
        //     $bd_query .= ' AND BD.status = '.$this->db->escape($booking_status);
        // }
        $id_query = 'select * from transfer_booking_itinerary_details AS ID WHERE ID.app_reference='.$this->db->escape($app_reference);
        $cd_query = 'select * from transfer_booking_passenger_details AS CD WHERE CD.app_reference='.$this->db->escape($app_reference);
        // $cancellation_details_query = 'select HCD.* from sightseeing_cancellation_details AS HCD WHERE HCD.app_reference='.$this->db->escape($app_reference);
        $response['data']['booking_details']            = $this->db->query($bd_query)->result_array();
        //debug($this->db->last_query());
		//debug($response['data']['booking_details']);exit;

        $response['data']['booking_itinerary_details']  = $this->db->query($id_query)->result_array();

        $response['data']['booking_customer_details']   = $this->db->query($cd_query)->result_array();
        // $response['data']['cancellation_details']   = $this->db->query($cancellation_details_query)->result_array();
// debug($response);exit;

        	if (valid_array($response['data']['booking_details']) == true and  valid_array($response['data']['booking_customer_details']) == true) {
            $response['status'] = SUCCESS_STATUS;
        }
        
     // debug($response);exit();
        return $response;
    }
    function get_transfer_details($app_reference)
    {
    	$qry = "select transfer_name,distance,vehicle_image from transfer_booking_itinerary_details where app_reference = '$app_reference'";
		$query=$this->db->query($qry);
		return $query->result();
    }
    public function price_category_data() {
		$this->db->select ( 'transfer_price_category.*,tours_continent.name as cont_name' );
		$this->db->join ( 'tours_continent', 'tours_continent.id = transfer_price_category.contient' );
		return $this->db->get( 'transfer_price_category' );

	}
	public function nationality_group_data() {
		$this->db->select ( '*' );
		$this->db->where ( 'status', 1 );
		$this->db->where ( 'module', 'transfers' );
		$this->db->where ( 'created_by', $GLOBALS['CI']->entity_user_id );
		return $this->db->get ( 'all_nationality_country' )->result ();

	}
	public function tours_country_name()
	{
		$query = "select * from tours_country order by name"; //echo $query; exit;
		$result = $this->db->query($query);
     	return  $result->result_array();
	}
	public function get_tours_continent()
	{
		$query = 'select * from tours_continent where status = 1 order by name'; 
		$result = $this->db->query($query);
     	return  $result->result_array();
	}
	public function get_price_category_id($id) {
		$this->db->select ( '*' );
		$this->db->where ( 'id', $id );
		return $this->db->get ( 'transfer_price_category' )->result ();
	}
	public function ajax_tours_continent($tours_continent)
	{
		$query = "select * from tours_country where continent='$tours_continent' order by name"; 
		$result = $this->db->query($query);
	    return  $result->result_array();
	}
	// public function update_price_cat($newpprice, $id) {
	// 	$this->db->where ( 'id', $id );
	// 	return $this->db->update ( 'transfer_price_category', $newpprice );
	// }
	// public function add_price_cat($newpprice) {
	// 	$this->db->insert ('transfer_price_category', $newpprice );
	// 	return $this->db->insert_id ();
	// }
	function get_airport_list($search_chars,$country_code='')
	{
		$raw_search_chars = $this->db->escape($search_chars);
		$r_search_chars = $this->db->escape($search_chars.'%');
		$search_chars = $this->db->escape('%'.$search_chars.'%');
		if(!empty($country_code)){
			$where = 'and CountryCode="'.$country_code.'" ';
		}
		$query = 'Select * from flight_airport_list where airport_city like '.$search_chars.' '.$where.'
		OR airport_code like '.$search_chars.' '.$where.' OR country like '.$search_chars.' '.$where.'
		ORDER BY top_destination DESC,
		CASE
			WHEN	airport_code	LIKE	'.$raw_search_chars.'	THEN 1
			WHEN	airport_city	LIKE	'.$raw_search_chars.'	THEN 2
			WHEN	country			LIKE	'.$raw_search_chars.'	THEN 3

			WHEN	airport_code	LIKE	'.$r_search_chars.'	THEN 4
			WHEN	airport_city	LIKE	'.$r_search_chars.'	THEN 5
			WHEN	country			LIKE	'.$r_search_chars.'	THEN 6

			WHEN	airport_code	LIKE	'.$search_chars.'	THEN 7
			WHEN	airport_city	LIKE	'.$search_chars.'	THEN 8
			WHEN	country			LIKE	'.$search_chars.'	THEN 9
			ELSE 10 END
		LIMIT 0, 20';
		// debug($query);exit;
		return $this->db->query($query);

	}
	function get_hotels_list($search_chars,$country_code='')
	{
		if(!empty($country_code)){
			$where = 'and country_code="'.$country_code.'"';
		}
		$search_chars = $this->db->escape('%'.$search_chars.'%');
		$query = 'Select hotel_name, hotel_city, hotel_code, origin,country_code from hb_hotel_details where hotel_city like '.$search_chars.' '.$where.'
		OR hotel_name like '.$search_chars.' '.$where.' OR hotel_code like '.$search_chars.' '.$where.' LIMIT 0, 20';
		
		return $this->db->query($query);
		//return $data;
	}

	//Nationality Master
	public function nationality_region()
	{
		$query = 'select * from all_nationality_region where created_by='.$this->entity_user_id.' and module="transfers" order by name';
		$result = $this->db->query($query);
     	return  $result->result_array();
	}
	public function check_region_exist_all($tours_continent)
	{
		
		$this->db->select('*');
		$this->db->where('name',$tours_continent);
		$this->db->where('module','transfers');
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
		$this->db->where('nc.created_by',$this->entity_user_id);
		$this->db->where('nc.module','transfers');
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
		$query = 'select * from all_nationality_region where status = 1 and module="transfers" and created_by='.$this->entity_user_id.' order by name'; 
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
		$this->db->where('module','transfers');
		$this->db->where('created_by',$this->entity_user_id);
		$query = $this->db->get('all_nationality_country');
		if ( $query->num_rows > 0 ) {
			return $query->result();
		}else{
			return array();
		}		
	}
	public function record_delete($table,$id) {
		$query = "delete from ".$table." where id='$id'";
		$result = $this->db->query($query);
		// echo $this->db->last_query();exit;
	    return  $result;
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
		/*$exe   = mysql_query($query);
		if(!$exe) { die(mysql_error());}
		else{ return true;}*/

	}
	public function record_delete_countries($table,$id) {
		$query = "delete from ".$table." where origin='$id'";
		$result = $this->db->query($query);
		// echo $this->db->last_query();exit;
	    return  $result;
	}
	//Nationality Master End

	//Booking Cancellation 
	public function update_cancellation_details($AppReference, $cancellation_details)
	{
		$AppReference = trim($AppReference);
		$booking_status = 'BOOKING_CANCELLED';
		//1. Add Cancellation details
		$this->update_cancellation_refund_details($AppReference, $cancellation_details);
		//2. Update Master Booking Status
		// $this->custom_db->update_record('transfer_booking_details', array('status' => $booking_status), array('app_reference' => $AppReference, 'origin' =>'>0'));
		$query = "update transfer_booking_details set status='$booking_status' where app_reference='".$AppReference."' and origin>0";
		$this->db->query($query);
		//3.Update Itinerary Status
		$query1 = "update transfer_booking_itinerary_details set status='$booking_status' where app_reference='".$AppReference."' and origin>0";
		$this->db->query($query1);
		$query2 = "update transfer_booking_passenger_details set status='$booking_status' where app_reference='".$AppReference."' and origin>0";
		$this->db->query($query2);
		// $this->custom_db->update_record('transfer_booking_itinerary_details', array('status' => $booking_status), array('app_reference' => $AppReference));//later
	}
	public function update_cancellation_refund_details($AppReference, $cancellation_details)
	{
		

		$transfer_cancellation_details = array();
		$transfer_cancellation_details['app_reference'] = 				$AppReference;
		$transfer_cancellation_details['ChangeRequestId'] = 			$cancellation_details['ChangeRequestId'];
		$transfer_cancellation_details['ChangeRequestStatus'] = 		$cancellation_details['ChangeRequestStatus'];
		$transfer_cancellation_details['status_description'] = 		$cancellation_details['StatusDescription'];
		
		$transfer_cancellation_details['API_RefundedAmount'] = 		@$cancellation_details['RefundedAmount'];
		$transfer_cancellation_details['API_CancellationCharge'] = 	@$cancellation_details['CancellationCharge'];

		$transfer_cancellation_details['currency'] = admin_base_currency();
		
		if($cancellation_details['ChangeRequestStatus'] == 3){
			$transfer_cancellation_details['cancellation_processed_on'] =	date('Y-m-d H:i:s');
		}
		$cancel_details_exists = $this->custom_db->single_table_records('transfer_cancellation_details', '*', array('app_reference' => $AppReference));
		if($cancel_details_exists['status'] == true) {
			//Update the Data
			unset($transfer_cancellation_details['app_reference']);
			$this->custom_db->update_record('transfer_cancellation_details', $transfer_cancellation_details, array('app_reference' => $AppReference));
		} else {
			//Insert Data
			$transfer_cancellation_details['created_by_id'] = 				(int)@$this->entity_user_id;
			$transfer_cancellation_details['created_datetime'] = 			date('Y-m-d H:i:s');
			$data['cancellation_requested_on'] = date('Y-m-d H:i:s');
			$this->custom_db->insert_record('transfer_cancellation_details',$transfer_cancellation_details);
		}
	}

	function get_monthly_booking_summary($condition=array())
	{
		//Balu A
		$condition = $this->custom_db->get_custom_condition($condition);
		$query = 'select count(distinct(BD.app_reference)) AS total_booking, 
				sum(SBID.total_fare+SBID.admin_markup+SBID.agent_markup) as monthly_payment, sum(SBID.admin_markup) as monthly_earning, 
				MONTH(BD.created_datetime) as month_number 
				from transfer_booking_details AS BD
				join transfer_booking_itinerary_details AS SBID on BD.app_reference=SBID.app_reference
				where (YEAR(BD.created_datetime) BETWEEN '.date('Y').' AND '.date('Y', strtotime('+1 year')).')  and BD.domain_origin='.get_domain_auth_id().' '.$condition.'
				GROUP BY YEAR(BD.created_datetime), 
				MONTH(BD.created_datetime)';
		return $this->db->query($query)->result_array();
	}

}