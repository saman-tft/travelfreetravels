<?php

class tours_model extends CI_Model {
	public function __construct() {
		parent::__construct ();
		$this->load->model('custom_db');
	}
public function nationality_region()
	{
		$query = 'select * from all_nationality_region where  module="tours" order by name';
		$result = $this->db->query($query);
     	return  $result->result_array();
	}
	
	public function check_region_exist_all($tours_continent)
	{
		
		$this->db->select('*');
		$this->db->where('name',$tours_continent);
		$this->db->where('module','tours');
		$this->db->where('created_by',$this->entity_user_id);
		$query = $this->db->get('all_nationality_region');
		if ( $query->num_rows > 0 ) {
			return $query->result();
		}else{
			return array();
		}		
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
	
		$this->db->where('nc.module','tours');
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
		$query = 'select * from all_nationality_region where status = 1 and module="tours" and created_by='.$this->entity_user_id.' order by name'; 
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
		$this->db->where('module','tours');
		$this->db->where('created_by',$this->entity_user_id);
		$query = $this->db->get('all_nationality_country');
		if ( $query->num_rows > 0 ) {
			return $query->result();
		}else{
			return array();
		}	

		

	}
	function tour_booking_report($condition = array(), $count = false, $offset = 0, $limit = 100000000000) {
		$response ['status'] = SUCCESS_STATUS;
		$response ['data'] = array ();		
		if (! empty ( $condition )) {
			$condition_static = 'TB.status="PENDING" ';
			$condition = $condition_static.$this->custom_db->get_custom_condition ( $condition );
		} else {
			$condition = 'TB.status="PENDING" ';
		}	

		// BT, CD, ID
		if ($count) {
			$query = 'select count(distinct(TB.enquiry_reference_no)) AS total_records from tour_booking_details AS TB WHERE ' . $condition;
			$data = $this->db->query ( $query )->row_array ();
			return $data ['total_records'];
		} else {			
			// Booking Details
			// $bd_query = 'select TB.*,TI.* from tour_booking_details AS TB join holiday_package_pax_details as PD using(enquiry_reference_no) join tours_enquiry as TI using(enquiry_reference_no) WHERE ' . $condition . ' order by TB.origin desc limit ' . $limit;
			// debug($condition); exit('');
			$bd_query = 'select TB.* from tour_booking_details AS TB  WHERE ' . $condition . ' order by TB.origin desc limit ' . $limit;
			$booking_details = $this->db->query ( $bd_query )->result_array ();
			$result=array();
			foreach ($booking_details as $value) {
				$result[$value['enquiry_reference_no']]['booking_details'] = $value;

				$id_query = 'select TI.*,T.package_name from tours_enquiry AS TI LEFT JOIN tours AS T ON TI.tour_id=T.id WHERE TI.enquiry_reference_no="'.$value['enquiry_reference_no'].'" order by TI.id desc ';
				$enquiry_details = $this->db->query ( $id_query )->result_array ();
				$result[$value['enquiry_reference_no']]['enquiry_details'] = $enquiry_details[0];

				$td_query = 'select T.*,TC.name AS country_name from tours AS T LEFT JOIN tours_country AS TC ON TC.id=T.tours_country WHERE T.id="'.$enquiry_details[0]['tour_id'].'" ';
				$tours_details = $this->db->query ( $td_query )->result_array ();
				$result[$value['enquiry_reference_no']]['tours_details'] = $tours_details[0];
				$tc_query = 'select CityName AS city_name from tours_city WHERE id IN ('.$tours_details[0]['tours_city'].') ';
				$tours_city_details = $this->db->query ( $tc_query )->result_array ();
				$result[$value['enquiry_reference_no']]['tours_details']['city_name'] = array_column($tours_city_details, 'city_name');
				$tp_query = 'select *  from tour_price_management WHERE tour_id='.$enquiry_details[0]['tour_id'].' AND from_date<="'.$enquiry_details[0]['departure_date'].'" AND to_date>="'.$enquiry_details[0]['departure_date'].'"  ';
				$tours_price_details = $this->db->query ( $tp_query )->result_array ();
				$result[$value['enquiry_reference_no']]['tours_details']['price'] = $tours_price_details[0]['final_airliner_price'];
				$result[$value['enquiry_reference_no']]['tours_details']['currency'] = $tours_price_details[0]['currency'];

				$pd_query = 'select * from holiday_package_pax_details WHERE app_reference="'.$value['app_reference'].'" order by origin asc ';
				$pax_details = $this->db->query ( $pd_query )->result_array ();
				$result[$value['enquiry_reference_no']]['pax_details'] = $pax_details;

				/*$adult_count_query = 'select COUNT(*) AS total_records from holiday_package_pax_details WHERE app_reference="'.$value['app_reference'].'" AND pax_type="adult" ';
				$adult_count = $this->db->query ( $adult_count_query )->row_array ();
				$result[$value['enquiry_reference_no']]['pax_details']['adult_count'] = $adult_count ['total_records'];

				$child_count_query = 'select COUNT(*) AS total_records from holiday_package_pax_details WHERE app_reference="'.$value['app_reference'].'" AND pax_type="child" ';
				$child_count = $this->db->query ( $child_count_query )->row_array ();
				$result[$value['enquiry_reference_no']]['pax_details']['child_count'] = $child_count ['total_records'];

				$infant_count_query = 'select COUNT(*) AS total_records from holiday_package_pax_details WHERE app_reference="'.$value['app_reference'].'" AND pax_type="infant" ';
				$infant_count = $this->db->query ( $infant_count_query )->row_array ();
				$result[$value['enquiry_reference_no']]['pax_details']['infant_count'] = $infant_count ['total_records'];*/
			}
			$response ['data'] = $result;
			// debug($response); exit('');
			return $response;
		}
	}

	function update_top_tour__home($status,$origin)
	{
		$data = array(
				'home_page_status' => $status
		);
		$this->db->where('id', $origin);
		$this->db->update('tours', $data);
		//debug($this->db->last_query());exit;
	}

	function booking($condition = array(), $count = false, $offset = 0, $limit = 100000000000) {		
		// debug("gg");exit;
		$response ['status'] = SUCCESS_STATUS;
		$response ['data'] = array ();

		$condition = $this->custom_db->get_custom_condition ( $condition );
		//debug($condition);exit();
		if ($count) {
		   // debug("if");exit;
			// $query = 'select COUNT(*) AS total_records from tour_booking_details AS BD WHERE 1=1 AND booked_by_id="0" ' . $condition;
			$query = 'select COUNT(*) AS total_records from tour_booking_details AS BD WHERE 1=1 AND user_type="'.B2C_USER.'" ' . $condition;
			$data = $this->db->query ( $query )->row_array ();

			// debug($data);
			// debug($query);exit();
			return $data ['total_records'];
		} else {		
		//	$bd_query = 'select BD.* from tour_booking_details AS BD  WHERE 1=1 AND booked_by_id="0"' . $condition . ' order by BD.origin desc limit ' . $limit;
			// $bd_query = 'select BD.* from tour_booking_details AS BD  WHERE 1=1 AND booked_by_id="0"' . $condition . ' order by BD.origin desc limit ' . $limit;
			$bd_query = 'select BD.* from tour_booking_details AS BD  WHERE 1=1 AND user_type="'.B2C_USER.'"' . $condition . ' order by BD.origin desc limit '.$offset.','.$limit;
		
			$booking_details = $this->db->query ( $bd_query )->result_array ();
			//debug($bd_query);exit;
			$result=array();
			foreach ($booking_details as $value) {
				$app_reference = $value['app_reference'];
				$user_attributes=$value['user_attributes'];
				$user_attributes=json_decode($user_attributes,true);
				$attributes=$value['attributes'];
				$attributes=json_decode($attributes,true);
				$c_query = 'SELECT name AS country_name FROM api_country_list WHERE iso_country_code="'.$user_attributes['country'].'"';
				$country_name = $this->db->query ( $c_query )->result_array ();
				$user_attributes['country_name'] = $country_name[0]['country_name'];
				$value['user_attributes']=json_encode($user_attributes);
				$result[$app_reference]['booking_details'] = $value;

				$id_query = 'select TI.*,T.package_name from tours_enquiry AS TI LEFT JOIN tours AS T ON TI.tour_id=T.id WHERE TI.enquiry_reference_no="'.$value['enquiry_reference_no'].'" order by TI.id desc ';
				$enquiry_details = $this->db->query ( $id_query )->result_array ();
				$result[$app_reference]['enquiry_details'] = $enquiry_details[0];

				$tour_id = $enquiry_details[0]['tour_id'];
				if(count($enquiry_details)<1){
					$tour_id = $attributes['tour_id'];
				}

				$td_query = 'select T.*,TC.name AS country_name from tours AS T LEFT JOIN tours_country AS TC ON TC.id=T.tours_country WHERE T.id="'.$tour_id.'" ';
				$tours_details = $this->db->query ( $td_query )->result_array ();
				// debug($tours_details);exit;
				$result[$app_reference]['tours_details'] = $tours_details[0];
				if(!empty($tours_details[0]['tours_city'])){
					$tc_query = 'select CityName AS city_name from tours_city WHERE id IN ('.$tours_details[0]['tours_city'].') ';
					$tours_city_details = $this->db->query ( $tc_query )->result_array ();
					$result[$app_reference]['tours_details']['city_name'] = array_column($tours_city_details, 'city_name');
				}
				if(!empty($tours_details[0]['tour_id']) && !empty($tours_details[0]['departure_date']) ){
					$tp_query = 'select *  from tour_price_management WHERE tour_id='.$tour_id.' AND from_date<="'.$enquiry_details[0]['departure_date'].'" AND to_date>="'.$enquiry_details[0]['departure_date'].'"  ';
					$tours_price_details = $this->db->query ( $tp_query )->result_array ();
					$result[$app_reference]['tours_details']['price'] = $tours_price_details[0]['final_airliner_price'];
					$result[$app_reference]['tours_details']['currency'] = $tours_price_details[0]['currency'];
				}
				$pd_query = 'select * from holiday_package_pax_details WHERE app_reference="'.$value['app_reference'].'" order by origin asc '; 
				$pax_details = $this->db->query ( $pd_query )->result_array ();

				$pgd_query = 'select * from payment_gateway_details WHERE app_reference="'.$value['app_reference'].'" order by origin asc '; 
				$payment_details = $this->db->query ( $pgd_query )->result_array ();

				$result[$app_reference]['payment_details'] = $payment_details;
				$result[$app_reference]['pax_details'] = $pax_details;
				//to fetch the pricemanagment
				// $this->where(array('tours.app_reference'=>$value['app_reference']));
				// $this->db->select('');
				// $this->db->get('tour_price_management');
			}
			$response ['data'] = $result;
			// debug($result);exit();
			return $response;
		}
	}
	function booking_for_dash_board($condition = array(), $count = false, $offset = 0, $limit = 100000000000) {		
		// debug("gg");exit;
		$response ['status'] = SUCCESS_STATUS;
		$response ['data'] = array ();

		$condition = $this->custom_db->get_custom_condition ( $condition );
		//debug($condition);exit();
		if ($count) {
		   // debug("if");exit;
			// $query = 'select COUNT(*) AS total_records from tour_booking_details AS BD WHERE 1=1 AND booked_by_id="0" ' . $condition;
			$query = 'select COUNT(*) AS total_records from tour_booking_details AS BD WHERE 1=1 AND user_type="'.B2C_USER.'" ' . $condition;
			$data = $this->db->query ( $query )->row_array ();

			// debug($data);
			// debug($query);exit();
			return $data ['total_records'];
		} else {		
		//	$bd_query = 'select BD.* from tour_booking_details AS BD  WHERE 1=1 AND booked_by_id="0"' . $condition . ' order by BD.origin desc limit ' . $limit;
			// $bd_query = 'select BD.* from tour_booking_details AS BD  WHERE 1=1 AND booked_by_id="0"' . $condition . ' order by BD.origin desc limit ' . $limit;
			$bd_query = 'select BD.* from tour_booking_details AS BD  WHERE 1=1 AND user_type="'.B2C_USER.'"' . $condition . ' order by BD.origin desc limit ' . $limit;
		
			$booking_details = $this->db->query ( $bd_query )->result_array ();
			//debug($bd_query);exit;
			
			//debug($result);exit();
			return $booking_details;
		}
	}
	function get_monthly_booking_summary($condition=array())
	{
		// debug("vv");exit;
		//Balu A
		$condition = $this->custom_db->get_custom_condition($condition);
		$query = 'select *,MONTH(created_datetime) as month_number from tour_booking_details
				where (YEAR(created_datetime) BETWEEN '.date('Y').' AND '.date('Y', strtotime('+1 year')).') 
				GROUP BY YEAR(created_datetime), 
				MONTH(created_datetime)';
		// echo 	$query;exit;	
		$result=$this->db->query($query)->result_array();
		$total_booking=count($result);
		$responce=array();
		foreach ($result as $key => $value) {
			$aed_attributes=json_decode($value['aed_array']);
			$base_fare=0.00;
            if(isset($aed_attributes->aed_basic_price))
            {
                $base_fare=($aed_attributes->aed_basic_price);
                $base_fare=str_replace(",", "", $base_fare);
                $base_fare=round($base_fare);
            }
            $discount_value=0.00;
            if(isset($aed_attributes->aed_discount))
            {
                $discount_value=($aed_attributes->aed_discount);
                $discount_value=str_replace(",", "", $discount_value);
            }
            $total_fare=($base_fare+$aed_attributes->aed_convenience_fee)-$discount_value;
			$markup=0.00;
            if(isset($aed_attributes->aed_markup))
            {
                $markup=($aed_attributes->aed_markup);
                $markup=str_replace(",", "", $markup);
                $markup=round($markup);
                $markup=number_format($markup,2);
            }
            $monthly_payment=$total_fare+$markup;
            $monthly_earning=$markup;
            $dd=array('total_booking'=>$total_booking,'month_number'=>$value['month_number'],'monthly_payment'=>$monthly_payment,'monthly_earning'=>$monthly_earning);
            $responce[]=$dd;
		}
		return $responce;
		// debug($responce);exit;
	}
	function get_monthly_booking_summaryss($condition=array())
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
		$query = 'select count(*) AS total_search, MONTH(created_datetime) as month_number from search_holiday_history where
		(YEAR(created_datetime) BETWEEN '.$year_start.' AND '.$year_end.') AND domain_origin='.get_domain_auth_id().' 
		AND search_type="'.META_PACKAGE_COURSE.'"
		GROUP BY YEAR(created_datetime), MONTH(created_datetime)';
		return $this->db->query($query)->result_array();
	}
	function top_search($year_start, $year_end)
	{
		$query = 'select count(*) AS total_search, CONCAT(country) label from search_holiday_history where
		(YEAR(created_datetime) BETWEEN '.$year_start.' AND '.$year_end.') AND domain_origin='.get_domain_auth_id().' 
		AND search_type="'.META_PACKAGE_COURSE.'"
		GROUP BY CONCAT(country) order by count(*) desc, created_datetime desc limit 0, 15';
		return $this->db->query($query)->result_array();
	}
function b2b_booking($condition = array(), $count = false, $offset = 0, $limit = 100000000000) {		
		$response ['status'] = SUCCESS_STATUS;
		$response ['data'] = array ();

		$condition = $this->custom_db->get_custom_condition ( $condition );
		//debug($condition);exit();
		if ($count) {
			// $query = 'select COUNT(*) AS total_records from tour_booking_details AS BD WHERE 1=1 AND booked_by_id!="0" ' . $condition;
$query = 'select COUNT(*) AS total_records from tour_booking_details AS BD WHERE 1=1 AND user_type="3" ' . $condition;
			$data = $this->db->query ( $query )->row_array ();

			// debug($data);
			// debug($query);exit();
			return $data ['total_records'];
		} else {	
		    
		    
			$bd_query = 'select BD.* from tour_booking_details AS BD  WHERE 1=1 AND user_type="3"' . $condition . ' order by BD.origin desc limit '.$offset.','.$limit;
	
	
	
$booking_details = $this->db->query ( $bd_query )->result_array ();
			$result=array();
			foreach ($booking_details as $value) {
				$app_reference = $value['app_reference'];
				$user_attributes=$value['user_attributes'];
				$user_attributes=json_decode($user_attributes,true);
				$attributes=$value['attributes'];
				$attributes=json_decode($attributes,true);
				$c_query = 'SELECT name AS country_name FROM api_country_list WHERE iso_country_code="'.$user_attributes['country'].'"';
				$country_name = $this->db->query ( $c_query )->result_array ();
				$user_attributes['country_name'] = $country_name[0]['country_name'];
				$value['user_attributes']=json_encode($user_attributes);
				$result[$app_reference]['booking_details'] = $value;

				$id_query = 'select TI.*,T.package_name from tours_enquiry AS TI LEFT JOIN tours AS T ON TI.tour_id=T.id WHERE TI.enquiry_reference_no="'.$value['enquiry_reference_no'].'" order by TI.id desc ';
				$enquiry_details = $this->db->query ( $id_query )->result_array ();
				$result[$app_reference]['enquiry_details'] = $enquiry_details[0];

				$tour_id = $enquiry_details[0]['tour_id'];
				if(count($enquiry_details)<1){
					$tour_id = $attributes['tour_id'];
				}

				$td_query = 'select T.*,TC.name AS country_name from tours AS T LEFT JOIN tours_country AS TC ON TC.id=T.tours_country WHERE T.id="'.$tour_id.'" ';
				$tours_details = $this->db->query ( $td_query )->result_array ();
				$result[$app_reference]['tours_details'] = $tours_details[0];
				if(!empty($tours_details[0]['tours_city'])){
					$tc_query = 'select CityName AS city_name from tours_city WHERE id IN ('.$tours_details[0]['tours_city'].') ';
					$tours_city_details = $this->db->query ( $tc_query )->result_array ();
					$result[$app_reference]['tours_details']['city_name'] = array_column($tours_city_details, 'city_name');
				}
				if(!empty($tours_details[0]['tour_id']) && !empty($tours_details[0]['departure_date']) ){
					$tp_query = 'select *  from tour_price_management WHERE tour_id='.$tour_id.' AND from_date<="'.$enquiry_details[0]['departure_date'].'" AND to_date>="'.$enquiry_details[0]['departure_date'].'"  ';
					$tours_price_details = $this->db->query ( $tp_query )->result_array ();
					$result[$app_reference]['tours_details']['price'] = $tours_price_details[0]['final_airliner_price'];
					$result[$app_reference]['tours_details']['currency'] = $tours_price_details[0]['currency'];
				}
				$pd_query = 'select * from holiday_package_pax_details WHERE app_reference="'.$value['app_reference'].'" order by origin asc '; 
				$pax_details = $this->db->query ( $pd_query )->result_array ();
				$result[$app_reference]['pax_details'] = $pax_details;
				//to fetch the pricemanagment
				// $this->where(array('tours.app_reference'=>$value['app_reference']));
				// $this->db->select('');
				// $this->db->get('tour_price_management');
			}
			$response ['data'] = $result;
			return $response;
		}
	}
	public function tour_destinations()
	{
		$query = 'select * from tour_destinations order by destination'; //echo $query; exit;
		/*$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[] = $fetch;
		}*/
		$result = $this->db->query ( $query )->result_array ();
		return $result;
	}
	public function get_tour_destinations()
	{
		//debug("came");exit;
		$query = 'select id,destination from tour_destinations order by destination'; //echo $query; exit;
		/*$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[$fetch['id']] = $fetch['destination'];
		}*/
		$result = $this->db->query ( $query )->result_array ();
		foreach($result as $res)
		{
			$result[$res['id']] = $res['destination'];
		}
		return $result;
	}
	public function tour_destinations_details($id)
	{
		$query = "select * from tour_destinations where id='$id'"; //echo $query; exit;
		/*$exe   = mysql_query($query);
		$fetch = mysql_fetch_assoc($exe);*/
		$fetch = $this->db->query ( $query )->result_array ();
		return $fetch;
	}
	public function add_tour_destination_save($query) {
		//debug($query);exit;
		//$exe   = mysql_query($query);
		$exe = $this->db->query ( $query );
		if(!$exe) { die(mysql_error());}
		else{ return true;}
	}
	public function delete_tour_destination($id) {
		$query = "delete from tour_destinations where id='$id'";
		//$exe   = mysql_query($query);
		$exe = $this->db->query ( $query );
		if(!$exe) { die(mysql_error());}
		else{ return true;}
	}
	public function tour_destination_details($id)
	{
		$query = "select * from tour_destinations where id='$id'";
		/*$exe   = mysql_query($query);
		$fetch = mysql_fetch_assoc($exe);*/
		$fetch = $this->db->query ( $query )->result_array ();
		return $fetch;
	}
	public function edit_tour_destination_save($query) {
		//debug($query);exit;
		//$exe   = mysql_query($query);
		$exe = $this->db->query ( $query );
		if(!$exe) { die(mysql_error());}
		else{ return true;}
	}
	public function activation_tour_destination($id,$status) {
		//echo 'model add_tour_destination_save';
		//debug($data);exit;
		$query = "update tour_destinations set status='$status' where id='$id'";
		//$exe   = mysql_query($query);
		$exe = $this->db->query ( $query );
		if(!$exe) { die(mysql_error());}
		else{ return true;}
	}
	public function AUTO_INCREMENT($table) {
		/*$HTTP_HOST = '192.168.0.63';
        if(($_SERVER['HTTP_HOST']==$HTTP_HOST) || ($_SERVER['HTTP_HOST']=='localhost'))
	    {
				$db = 'neptune';	 
	    }
	    else
	    {
				$db = 'developm_airlinersv2';
        } 
		$query    = "SELECT AUTO_INCREMENT FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA='$db' AND TABLE_NAME= '$table'";	
        $exe      = mysql_query($query);
        if(!$exe) { die(mysql_error());}
        else
        { 
           $fetch          = mysql_fetch_array($exe);
	       $AUTO_INCREMENT = $fetch['AUTO_INCREMENT'];
	       return $AUTO_INCREMENT; 
	      }*/
	      $auto_increment  = rand(1000,9999);
	      return $auto_increment; 
	     }
	     public function add_tour_save($query) {
	     	//$exe   = mysql_query($query);
	     	$exe = $this->db->query ( $query )
	     	;
	     	if(!$exe) { die(mysql_error());}
	     	else{ return true;}
	     }
	     public function tour_list() {
	     	
	     	// error_reporting(E_ALL);
	     	//debug("came");
	     	//$query = 'select * from tours where admin_approve_status = 1 AND agent_id IS NULL AND status_delete != "1" order by id desc'; 
	     	$query = "select * from tours where admin_approve_status = '1'  AND status_delete != '1' order by id desc";
	     	
	     	/*$exe   = mysql_query($query); 
	     	while($fetch = mysql_fetch_assoc($exe))
	     	{
	     		$result[] = $fetch;
	     	}*/

	     	// $mysqli = new mysqli('localhost','root','','wise_travel_new');
	     	// //if ($mysqli->connect_errno) echo "Error - Failed to connect to MySQL: " . $mysqli->connect_error;

	     	// //$exe   = mysql_query($conn,$query); 
	     	// $result = $mysqli->query($query);
	     	// while($fetch = $result->fetch_assoc())
	     	// {
	     	// 	$result[] = $fetch;
	     	// }

	     	$result = $this->db->query ( $query )->result_array();
	     	//debug($result);exit;

	     	return $result;
	     }

	public function tour_list_pending() {
		$query = 'select * from tours where agent_request_status = 1 and admin_approve_status = 0 AND status_delete != "1" order by id desc'; //echo $query; exit;
	// /echo $query; exit();
		/*$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[] = $fetch;
		}*/
		$result = $this->db->query ( $query )->result_array ();
		return $result;
	}
	public function activation_tour_package($query) {
		//$exe   = mysql_query($query);
		$exe = $this->db->query ( $query );
		if(!$exe) { die(mysql_error());}
		else{ return true;}
	}
		public function delete_tour_package($id) {
			// debug("test");exit;
		// delete_tour_visited_cities delete_tour_dep_date
		// $query = "delete from tours where id='$id'";
		$query1 = 'delete from tour_visited_cities where tour_id='.$id;
		
		$query2 = 'delete from tour_dep_dates where tour_id='.$id;
		// debug("test");exit;
		$this->db->query($query2);
		$this->db->query($query1);
		// $exe1   = mysql_query($query1);
		// $exe2   = mysql_query($query2);
		// $exe   = mysql_query($query);
		// if(!$exe) { die(mysql_error());}
		// else{ return true;}
		$query = 'delete from tours where id='.$id;
		$result = $this->db->query($query);
		if(!$result)
		{
			return FALSE;		
		}else
		{
			return TRUE;
		}
	}	
	public function tour_dep_dates($tour_id) {
		$query = "select * from tour_dep_dates where tour_id='$tour_id' order by dep_date asc"; //echo $query; exit;
		/*$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			@$result[] = $fetch;
		}*/
		$result = $this->db->query ( $query )->result_array ();
		return @$result;
	}
	public function tour_data($tour_id) {
		//debug($tour_id);exit;
		$query = "select * from tours where id='$tour_id'";
		/*$exe   = mysql_query($query);
		if(!$exe) { die(mysql_error());}
		else
		{ 
			$fetch = mysql_fetch_assoc($exe);
			return $fetch;
		}*/
	//	echo $query;die;
		$result = $this->db->query ( $query )->result_array ();
		return $result;
	}

	public function holiday_tour_data($table,$where){
		$query = $this->db->
					select('*')
					->where($where)
					->get($table);
					return $query->result();
					// echo last_query($query);exit;
		// return $where;
	}
	public function holiday_tour_data_new($table,$where){
		$query = $this->db->
					select('*')
					->where($where)
					->get($table);
					return $query->row_array();
					// echo last_query($query);exit;
		// return $where;
	}

	public function activity_tour_data($table,$where){
		$query = $this->db->
					select('*')
					->where($where)
					->get($table);
					return $query->result();
					// echo last_query($query);exit;
		// return $where;
	}


	public function activity_package_data($table,$where){
		$query = $this->db->
					select('*')
					->where($where)
					->get($table);
					return $query->result();
					// echo last_query($query);exit;
		// return $where;
	}


	
	public function package_data($package_id) {
		$query = "select * from tours where id='$package_id'";
		/*$exe   = mysql_query($query);
		if(!$exe) { die(mysql_error());}
		else
		{ 
			$fetch = mysql_fetch_assoc($exe);
			return $fetch;
		}*/
		$result = $this->db->query($query)->result_array();
		return $result;
	}

	
	public function tour_dep_date_save($query) {
		//debug($query);exit;
		//$exe   = mysql_query($query);
		//$exe = $this->db->query($query)->row();
		$exe = $this->db->query($query);
		//debug($exe );exit;
		if(!$exe) { die(mysql_error());}
		else{ return true;}
	}
	public function delete_tour_dep_date($id) {
		$query = "delete from tour_dep_dates where id='$id'";
		//$exe   = mysql_query($query);
		$exe = $this->db->query ( $query );
		if(!$exe) { die(mysql_error());}
		else{ return true;}
	}
	public function tour_visited_cities($tour_id) {
		$query = "select * from tour_visited_cities where tour_id='$tour_id'";
		 // echo $query; exit;
		/*$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[] = $fetch;
		}*/
		$result = $this->db->query ( $query )->result_array ();
		return $result;
	}
	public function tour_visited_cities_save($query) {
		//$exe   = mysql_query($query);
		$exe = $this->db->query ( $query );
		if(!$exe) { die(mysql_error());}
		else{ return true;}
	}
	public function delete_tour_visited_cities($id) {
		$query = "delete from tour_visited_cities where id='$id'";
		$result = $this->db->query($query);
		if($result)
		{
			return true;
		}
			
		
			else
			{
				false;
			}
		/*$exe   = mysql_query($query);
		if(!$exe) { die(mysql_error());}
		else{ return true;}*/
	}
	public function tour_visited_cities_details($id) {
		$query = "select * from tour_visited_cities where id='$id'"; //echo $query; exit;
		/*$exe   = mysql_query($query);
		$fetch = mysql_fetch_assoc($exe);*/
		$fetch = $this->db->query ( $query )->result_array ();
		return $fetch;
	}
	public function edit_tour_visited_cities_save($query) {
		//$exe   = mysql_query($query);
		$exe = $this->db->query ( $query );
		if(!$exe) { die(mysql_error());}
		else{ return true;}
	}
	public function query_run($query) {
		//$exe   = mysql_query($query);
		$exe = $this->db->query ( $query );
		if(!$exe) { die(mysql_error());}
		else{ return true;}
	}
	public function top_tour_destinations()
	{
		$query = 'select * from tour_destinations order by cms_status desc'; //echo $query; exit;
		/*$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[] = $fetch;
		}*/
		$result = $this->db->query ( $query )->result_array ();
		return $result;
	}
	public function tour_dep_dates_list($tour_id)
	{
		$query = "select * from tour_dep_dates where tour_id='$tour_id' order by dep_date asc"; //echo $query; exit;
		/*$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[] = $fetch;
		}*/
		$result = $this->db->query ( $query )->result_array ();
		return $result;
	}
	public function tour_visited_cities_list($tour_id)
	{
		$query = "select * from tour_visited_cities where tour_id='$tour_id' order by id asc"; //echo $query; exit;
		/*$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[] = $fetch;
		}*/
		$result = $this->db->query ( $query )->result_array ();
		return $result;
	}
	public function tours_itinerary($tour_id,$dep_date)
	{
		$query = "select * from tours_itinerary where tour_id='$tour_id'"; //echo $query; exit;
		/*$exe   = mysql_query($query);
		$num   = mysql_num_rows($exe);
		$fetch = mysql_fetch_assoc($exe);*/
		$fetch = $this->db->query ( $query )->result_array ();
		return $fetch; exit;
	}
	public function tour_visited_cities_all()
	{
		$query = "select * from tour_visited_cities order by id asc"; //echo $query; exit;
		/*$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[$fetch['id']] = $fetch['city'];
		}*/
		$result = $this->db->query ( $query )->result_array ();
		return $result;
	}
	public function tour_dep_dates_list_all()
	{
		$query = "select * from tour_dep_dates order by dep_date asc"; //echo $query; exit;
		/*$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[$fetch['tour_id']][] = $fetch['dep_date'];
		}*/
		$result = $this->db->query ( $query )->result_array ();
		return $result;
	}
	public function tour_dep_dates_list_published()
	{
		$query = "select * from tours_itinerary where publish_status=1 order by dep_date asc"; //echo $query; exit;
		/*$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[$fetch['tour_id']][] = $fetch['dep_date'];
			//$result[$fetch['tour_id']][] = $fetch;
		}*/
		$result = $this->db->query ( $query )->result_array ();
		return $result;
	}
	public function tour_dep_dates_list_published_wd()
	{
		$query = "select * from tours_itinerary  order by dep_date asc"; //echo $query; exit;
		/*$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			//$result[$fetch['tour_id']][] = $fetch['dep_date'];
			$result[$fetch['tour_id']][] = $fetch;
		}*/
		$result = $this->db->query ( $query )->result_array ();
		foreach($result as $res1)
		{
			$result[$res1['tour_id']][] = $res1['dep_date'];
			$result[$res1['tour_id']][] = $res1;
		}
		return $result;
	}
	public function check_tour_dep_dates($tour_id,$dep_date)
	{

		$query = "select * from tour_dep_dates where tour_id='$tour_id' and dep_date='$dep_date'"; //echo $query; exit;
		//debug($tour_id);debug($dep_date);debug($query);exit;	
		/*$exe   = mysql_query($query);
		$num   = mysql_num_rows($exe);*/
		$result = $this->db->query ( $query )->result_array ();
		//debug($query);exit;
		$num=count($result);
		return $num;
	}
	public function ajax_tour_publish($query)
	{
		//$exe   = mysql_query($query);
		//$num   = mysql_num_rows($exe);
		$result = $this->db->query ( $query )->result_array ();
		$num=count($result);
		return $num;
	}

	public function ajax_tour_publish_1($query)
	{
		/*$exe   = mysql_query($query);
		$num   = mysql_num_rows($exe);*/
		$result = $this->db->query ( $query )->result_array ();
		$num=count($result);
		return $num;
	}
	public function tour_date_list()
	{
		$query = "select * from tours_itinerary order by tour_id asc"; //echo $query; exit;
		/*$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[] = $fetch;
		}*/
		$result = $this->db->query ( $query )->result_array ();
		return $result;
	}
	public function tours_enquiry($condition)
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
		$query = $this->db->get("tours_enquiry");
		if($query->num_rows > 0)
		{
			$result['tours_enquiry']=$query->result_array();
			//debug($result);die;
		}
		else
		{
			$result['tours_enquiry']= array();
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
  // echo "in model";
   //echo $this->db->last_query();
   //debug($result);die;
			return $result;
	}



	
	public function tours_itinerary_all()
	{
		$query = "select * from tours_itinerary order by id asc"; //echo $query; exit;
		// $exe   = mysql_query($query);
		// while($fetch = mysql_fetch_assoc($exe))
		// {
		// 	$result[] = $fetch;
		// }
		$result = $this->db->query ( $query )->result_array ();
		return $result;
	}
	public function tour_type()
	{
		$query = 'select * from tour_type order by id desc'; //echo $query; exit;
		/*$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[] = $fetch;
		}*/
		$result = $this->db->query ( $query )->result_array ();
		return $result;
	}

	public function get_tour_type()
	{
		$query = 'select * from tour_type  where status = 1 order by tour_type_name '; //echo $query; exit;
		/*$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[] = $fetch;
		}*/
	//	debug($query); 
		//debug($result); exit();
		$result = $this->db->query ( $query )->result_array ();
		return $result;
	}
	public function tour_type_details($id)
	{
		$query = "select * from tour_type where id='$id'"; //echo $query; exit;
	/*	$exe   = mysql_query($query);
		$fetch = mysql_fetch_assoc($exe);*/
		$fetch = $this->db->query ( $query )->result_array ();
		return $fetch;
	}
	public function tour_inclusions()
	{
		$query = 'select * from tour_inclusions order by id desc'; //echo $query; exit;
		/*$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[] = $fetch;
		}*/
		$result = $this->db->query ( $query )->result_array ();
		return $result;
	}
	public function record_activation($table,$id,$status) {
		$query = "update ".$table." set status='$status' where id='$id'";
		//$exe   = mysql_query($query);
		$exe = $this->db->query ( $query );
		if(!$exe) { die(mysql_error());}
		else{ return true;}
	}
	public function record_delete($table,$id) {
		$query = "delete from ".$table." where id='$id'";
		//$exe   = mysql_query($query);
		$exe = $this->db->query($query);

		if(!$exe) { die(mysql_error());}
		else{ return true;}
	}
	public function table_record_detailsold($table,$id)
	{
		$query = "select * from ".$table." where id='$id'"; //echo $query; exit;
		/*$exe   = mysql_query($query);
		$fetch = mysql_fetch_assoc($exe);*/
		$fetch = $this->db->query ( $query )->result_array ();
		return $fetch;
	}
	public function table_records($table,$order_by,$order)
	{
		$query = 'select * from '.$table.$order_by.$order; //echo $query; exit;
		/*$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[] = $fetch;
		}*/
		$result = $this->db->query ( $query )->result_array ();
		return $result;
	}
	public function tour_subtheme()
	{
		$query = 'select * from tour_subtheme order by tour_subtheme'; //echo $query; exit;
		/*$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[] = $fetch;
		}*/
		$result = $this->db->query ( $query )->result_array ();
		return $result;
	}

	public function get_tour_subtheme()
	{
		$query = 'select * from tour_subtheme where status = 1 order by tour_subtheme'; //echo $query; exit;
		/*$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[] = $fetch;
		}*/
		$result = $this->db->query ( $query )->result_array ();
		return $result;
	}
	public function tour_activity()
	{
		$query = 'select * from tour_activity order by tour_activity'; //echo $query; exit;
		/*$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[] = $fetch;
		}*/
		$result = $this->db->query ( $query )->result_array ();
		return $result;
	}
	public function tours_continent()
	{
		$query = 'select * from tours_continent order by name'; //echo $query; exit;
		/*$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[] = $fetch;
		}*/
		$result = $this->db->query ( $query )->result_array ();
		return $result;
	}

	public function get_tours_continent()
	{
		$query = 'select * from tours_continent where status = 1 order by name'; //echo $query; exit;
		/*$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[] = $fetch;

		}*/
		// debug($result);exit();
		$result = $this->db->query ( $query )->result_array ();
		return $result;
	}
	public function ajax_tours_continent($tours_continent)
	{
		//debug("sfgf");exit;
		$query = "select * from tours_country where continent='$tours_continent'  AND status='1' order by name"; //echo $query; exit;
		/*$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[] = $fetch;
		}*/
		$result = $this->db->query ( $query )->result_array ();
		return $result;
	}
	public function ajax_tours_country($tours_country)
	{
		$query = "select * from tours_city where country_id='$tours_country' group by CityName order by CityName"; //echo $query; exit;
		/*$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[] = $fetch;
		}*/
		$result = $this->db->query ( $query )->result_array ();
		return $result;
	}
	public function tours_city_name()
	{
		$query = "select * from tours_city order by CityName"; //echo $query; exit;
		/*$exe   = mysql_query($query) or die(mysql_error());
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[$fetch['id']] = $fetch['CityName'];
		}*/
		$result1 = $this->db->query ( $query )->result_array ();
		foreach($result1 as $res1)
		{
			$result[$res1['id']] = $res1['CityName'];
		}
		//debug($result); exit;
		return $result;
	}
	public function tours_country_name()
	{
		$query = "select * from tours_country order by name"; //echo $query; exit;
		/*$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[$fetch['id']] = $fetch['name'];
		}*/
		$result = $this->db->query ( $query )->result_array ();
		foreach($result as $res1)
		{
			$result[$res1['id']] = $res1['name'];
		}
		return $result;
	}
	public function tours_continent_country($tour_id)
	{
		$query = "select * from tours where id='$tour_id'"; // echo $query; exit;
		/*$exe   = mysql_query($query);
		$fetch = mysql_fetch_assoc($exe);*/
		$fetch = $this->db->query ( $query )->result_array ();

		$query = "select * from tours_country where continent='".$fetch['tours_continent']."' order by name"; // echo $query; exit;
		/*$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[] = $fetch;
		}*/
		$result = $this->db->query ( $query )->result_array ();
		return $result;
	}
	public function tours_country_city($tour_id)
	{
		$query = "select * from tours where id='$tour_id'"; // echo $query; exit;
		/*$exe   = mysql_query($query);
		$fetch = mysql_fetch_assoc($exe);*/
		$fetch = $this->db->query ( $query )->result_array ();

		$query = "select * from tours_city where country_id='".$fetch['tours_country']."' order by CityName	"; // echo $query; exit;
		/*$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[] = $fetch;
		}*/
		$result = $this->db->query ( $query )->result_array ();
		return $result;
	}
	public function tours_itinerary_dw($tour_id,$dep_date)
	{
		$query = "select * from tours_itinerary_dw where tour_id='$tour_id' order by id asc"; //echo $query; exit;
		/*$exe   = mysql_query($query);
		$num   = mysql_num_rows($exe);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[] = $fetch;
		}*/
		$result = $this->db->query ( $query )->result_array ();
		return $result;
	}
	public function tours_itinerary_wd($tour_id)
	{
		$query = "select * from tours_itinerary where tour_id='$tour_id' "; //echo $query; exit;
		/*$exe   = mysql_query($query);
		$num   = mysql_num_rows($exe);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[] = $fetch;
		}*/
		$result = $this->db->query ( $query )->result_array ();
		return $result;
	}
	public function reviews()
	{
		$query = "select * from user_review where module='holiday' order by origin desc"; //echo $query; exit;
		/*$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[] = $fetch;
		}*/
		$result = $this->db->query ( $query )->result_array ();
		return $result;
	}
	public function hotel_reviews()
	{
		$query = "select * from user_review where module='hotel' order by origin desc"; //echo $query; exit;
		/*$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[] = $fetch;
		}*/
		$result = $this->db->query ( $query )->result_array ();
		return $result;
	}

	public function tour_region()
	{
		$query = 'select * from tours_continent order by name'; //echo $query; exit;
		/*$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[] = $fetch;
		}*/
		$result = $this->db->query ( $query )->result_array ();
		return $result;
	}

	public function check_region_exist($tours_continent)
	{
		
		$this->db->select('*');
		$this->db->where('name',$tours_continent);
		$query = $this->db->get('tours_continent');
		if ( $query->num_rows > 0 ) {
			return $query->result();
		}else{
			return array();
		}		
	}
	
	public function enquiry_user_details($enquiry_reference_no)
	{
		$this->db->select('*');
		$this->db->where('enquiry_reference_no',$enquiry_reference_no);
		$query = $this->db->get('tours_enquiry');
		if ( $query->num_rows > 0 ) {
			return $query->result();
		}else{
			return array();
		}		
	}


	public function activity_enquiry_user_details($enquiry_reference_no)
	{
		$this->db->select('*');
		$this->db->where('enquiry_reference_no',$enquiry_reference_no);
		$query = $this->db->get('package_enquiry');
		if ( $query->num_rows > 0 ) {
			return $query->result();
		}else{
			return array();
		}	
	}

	public function tour_country()
	{
		$query = 'select  tours_country.*,tours_country.name as country_name,tours_continent.name as continent_name from tours_country join tours_continent on tours_country.continent = tours_continent.id order by tours_country.name'; //echo $query; exit;
		/*$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[] = $fetch;
		}*/
		$result = $this->db->query ( $query )->result_array ();
		return $result;
	}

	public function approve_package($p_id)
	{
		$query = "update tours set admin_approve_status = 1 where id='$p_id'";
      //echo $query; exit();
		//$exe   = mysql_query($query);
		$exe = $this->db->query ( $query );
		if(!$exe) { die(mysql_error());}
		else{ return true;}
	}
	
	public function check_exist_tc() 
	{
		$query = 'select * from holiday_terms_n_condition '; //echo $query; exit;
	//	echo $query;exit();
		/*$exe   = mysql_query($query);
		while($fetch = mysql_fetch_row($exe))
		{
			$result[] = $fetch;
		}*/
		$result = $this->db->query ( $query )->result_array ();
		return $result;
	}

	public function update_tc($data)
	{
	//debug($data);exit;
		
		//$tc = $data[0][1];
		$update_data = array('terms_n_conditions' => $data['terms_n_conditions'],
			'cancellation_policy' => $data['cancellation_policy']
			);

	//	$query = "update holiday_terms_n_condition set terms_n_conditions = '$data' where id=1";
    //echo $query; exit();
		$exe   =      $this->db->update('holiday_terms_n_condition', $update_data);
		//debug($this->db->last_query()); exit;
		if(!$exe) { die(mysql_error());}
		else{ return true;}
	}
	
	public function get_holiday_tc()
	{
		$query = 'select * from terms_n_condition where module_name = "Holiday" '; //echo $query; exit;
	//	echo $query;exit();
		/*$exe   = mysql_query($query);
		while($fetch = mysql_fetch_array($exe))
		{
			$result[] = $fetch;
		}*/
		$result = $this->db->query ( $query )->result_array ();
		return $result;
	}

	public function get_package_id($package_name)
	{
		$query = 'select * from tours where package_name like \'%'.$package_name.'%\' '; //echo $query; exit;
		//echo $query;exit();
		/*$exe   = mysql_query($query);
		while($fetch = mysql_fetch_array($exe))
		{
			$result[] = $fetch;
		}*/
		$result = $this->db->query ( $query )->result_array ();
		return $result;
	}

	public function get_occupancy()
	{
		$this->db->select('*');
		$query = $this->db->get('occupancy_managment');
		if ( $query->num_rows > 0 ) {
			return $query->result_array();
		}else{
			return array();
		}		
	}

	public function get_price_details($id)
	{
		
		$this->db->select('*');
		$this->db->where('tour_id',$id);
		$query = $this->db->get('tour_price_management');
		if ( $query->num_rows > 0 ) {
			return $query->result_array();
		}else{
			return array();
		}	
		/*$query = 'select * from tour_price_management join tours on tours.id = tour_price_management.tour_id  where tour_price_management.id = '$id'' ; //echo $query; exit;
		echo $query; exit();
		$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[] = $fetch;
		}
		return $result;*/
	}

	public function get_price_details_single($id)
	{
		
		$this->db->select('*');
		$this->db->where('id',$id);
		$query = $this->db->get('tour_price_management');
		if ( $query->num_rows > 0 ) {
			return $query->result_array();
		}else{
			return array();
		}	
		
	}

	public function tours_date_price($tour_id)
	{
		$query = "select * from tours_itinerary where tour_id='$tour_id' order by dep_date"; //echo $query; exit;
		/*$exe   = mysql_query($query);
		$num   = mysql_num_rows($exe);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[] = $fetch;
		}*/
		$result = $this->db->query ( $query )->result_array ();
		return $result;
	}

    public function check_price_avilability($from,$to,$tour_id,$nationality)
	//public function check_price_avilability($from,$to,$occupancy,$tour_id)
	{
		//$query = "select * from tour_price_management where tour_id='$tour_id'  order by dep_date"; //echo $query; exit;
		//$query = "select * from tour_price_management where (('".$from."' between from_date and to_date) or ('".$to."' between from_date and to_date)) AND tour_id = '".$tour_id."' AND occupancy = '".$occupancy."'";
		$query = "select * from tour_price_management where (('".$from."' between from_date and to_date) or ('".$to."' between from_date and to_date)) AND tour_id = '".$tour_id."' AND nationality = '".$nationality."'";
	/*	
		echo $query; exit();*/
		/*$exe   = mysql_query($query);
		$num   = mysql_num_rows($exe);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[] = $fetch;
		}*/
		$result = $this->db->query ( $query )->result_array ();
		return $result;
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

	public function update_tours_images($image_data, $deleteid) {
		$this->db->where ( 'id', $deleteid );
		$this->db->update ( 'tours', $image_data );
	}

	public function delete_tour_price($id)
	{
		
		$query = "delete from tour_price_management where id='$id'";
      // echo $query;exit();
		//$exe   = mysql_query($query);
		$exe = $this->db->query ( $query );
		if(!$exe) { die(mysql_error());}
		else{ return true;}
	}

	public function delete_occupancy_managment($id)
	{
		
		$query = "delete from occupancy_managment where id='$id'";
      // echo $query;exit();
		//$exe   = mysql_query($query);
		$exe = $this->db->query ( $query );
		if(!$exe) { die(mysql_error());}
		else{ return true;}
	}

	

	public function tour_data_temp($tour_id) {
		$query = "select * from tours_temp where id='$tour_id'";
		/*$exe   = mysql_query($query);
		if(!$exe) { die(mysql_error());}
		else
		{ 
			$fetch = mysql_fetch_assoc($exe);
			return $fetch;
		}*/
		$result = $this->db->query ( $query )->result_array ();
		return $result;
	}
	public function booking_details($app_reference)
	{
		$response ['status'] = QUERY_FAILURE;
		$response ['data'] = array ();
		$bd_query = 'select BD.* from tour_booking_details AS BD  WHERE app_reference="'.$app_reference.'" ';
		$booking_details = $this->db->query ( $bd_query )->result_array ();
		if(valid_array($booking_details) && count($booking_details)>0){
			$response ['status'] = QUERY_SUCCESS;
			$response ['data'] = $booking_details[0];
		}
		return $response;
	}


   public function activity_booking_details($app_reference)
	{
		$response ['status'] = QUERY_FAILURE;
		$response ['data'] = array ();
		$bd_query = 'select BD.* from package_booking_details AS BD  WHERE app_reference="'.$app_reference.'" ';
		$booking_details = $this->db->query ( $bd_query )->result_array ();
		if(valid_array($booking_details) && count($booking_details)>0){
			$response ['status'] = QUERY_SUCCESS;
			$response ['data'] = $booking_details[0];
		}
		return $response;
	}



	public function quotation_details($quote_reference)
	{
		$response ['status'] = QUERY_FAILURE;
		$response ['data'] = array ();
		$quote_query = 'select * from tours_quotation_log  WHERE quote_reference="'.$quote_reference.'" ';
		$quotation_details = $this->db->query ( $quote_query )->result_array ();
		if(valid_array($quotation_details) && count($quotation_details)>0){
			$response ['status'] = QUERY_SUCCESS;
			$response ['data'] = $quotation_details[0];
		}
		return $response;
	}
	public function fetch_price(){

        $res = $this->db->select('final_airliner_price,calculated_markup,id,sessional_price,currency');
		$query = $this->db->get('tour_price_management');
		//debug($query->result_array());exit();
		//echo $this->db->last_query();
		return  $query->result_array();
		
	}

	public function update_final_price($id,$data){
		//echo $id.",".$data);exit;
		$this->db->where(array('id'=>$id));
		if($this->db->update('tour_price_management',$data)){
			return TRUE;
		}else{
			return FALSE;
		}
		// echo  $this->db->last_query();exit;

	}

	
}