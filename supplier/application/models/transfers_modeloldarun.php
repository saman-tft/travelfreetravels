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
		$q= $this->db->get ( 'package_types' );
		return $q;
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
		// debug($newpackage);exit();
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
	public function with_price() 
	{
		 
		$q = $this->db->query ( 'select * from package where supplier_id="'.$this->entity_user_id.'" and  module_type="transfers" order by package_id DESC' );


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
}