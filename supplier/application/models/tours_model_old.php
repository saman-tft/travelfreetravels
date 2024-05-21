<?php

class tours_model extends CI_Model {
	public function __construct() {
		parent::__construct ();
		$this->load->model('custom_db');		
	}
	public function tours_continent()
	{
		//$query = 'select * from tours_continent order by name'; //echo $query; exit;
		$query = "select distinct tours_continent.id, tours_continent.name, tours.tours_continent from tours
        inner join tours_continent on tours.tours_continent=tours_continent.id";
		$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[] = $fetch;
		}
		return $result;
	}
	public function tours_country_name()
	{
		//$query = "select * from tours_country order by name"; //echo $query; exit;
		$query = "select distinct tours_country.id, tours_country.name, tours.tours_country from tours
        inner join tours_country on tours.tours_country=tours_country.id";
		$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[$fetch['id']] = $fetch['name'];
		}
		return $result;
	}
	public function enquiry_user_details($enquiry_reference_no)
	{
		 $query = 'select te.*,t.id,t.package_name from tours_enquiry te join tours t on(te.tour_id=t.id) where enquiry_reference_no='."'".$enquiry_reference_no."'";
      
        $data = $this->db->query($query)->result_array()[0];
	 	return $data;
	}
	public function tours_country_name_modify($holiday_region)
	{
		//$query = "select * from tours_country order by name"; //echo $query; exit;
		if($holiday_region=='all')
		{
           $query = "select distinct tours_country.id, tours_country.name, tours.tours_country from tours_country
           inner join tours on tours.tours_country=tours_country.id";
		}
		else
		{
           $query = "select distinct tours_country.id, tours_country.name, tours.tours_country from tours_country
           inner join tours on tours.tours_country=tours_country.id and tours_country.continent='$holiday_region'";
		}
		//echo $query;exit;
		$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[$fetch['id']] = $fetch['name'];
		}
		return $result;
	}	
	public function tour_type()
	{
		$query = 'select * from tour_type order by tour_type_name'; //echo $query; exit;
		$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[] = $fetch;
		}
		return $result;
	}   
    public function tour_subtheme()
	{
		$query = 'select * from tour_subtheme order by tour_subtheme'; //echo $query; exit;
		$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[] = $fetch;
		}
		return $result;
	}
	public function getSearchResult($query)
	{
		$result= array();
		$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[] = $fetch;
		}
		return $result;
	}
	public function tours_continent_name()
	{
		$query = "select * from tours_continent order by name"; //echo $query; exit;
		$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[$fetch['id']] = $fetch['name'];
		}
		return $result;
	}
	public function ajax_tours_continent($tours_continent)
	{
		if($tours_continent=='all')
		{
        //$query = "select * from tours_country order by name"; //echo $query; exit;	
        $query = "select distinct tours_country.id, tours_country.name, tours.tours_country from tours_country
        inner join tours on tours.tours_country=tours_country.id";	
		}
		else
		{
		//$query = "select * from tours_country where continent='$tours_continent' order by name"; //echo $query; exit;	
		$query = "select distinct tours_country.id, tours_country.name, tours.tours_country from tours_country
        inner join tours on tours.tours_country=tours_country.id and tours_country.continent='$tours_continent'";	       			
		}
		$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[] = $fetch;
		}
		return $result;
	}
	public function confirmed_dep_date_list($query)
	{
		$in  = '';
		$exe = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			if($in==''){ $in = "'".$fetch['id']."'"; }
			else{ $in = $in.",'".$fetch['id']."'"; } 
		} 
		$today = date('Y-m-d');
		$query = "select tour_id,dep_date from tours_itinerary where publish_status=1 and tour_id in (".$in.") and (dep_date='".$today."' or dep_date>'".$today."') order by dep_date"; //echo $query; exit;
		$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[$fetch['tour_id']][] = $fetch['dep_date'];
		}
		return $result;
	}
	public function confirmed_dep_date_list_x($top_holiday_ids)
	{
		$in=implode(',',$top_holiday_ids);
		$today = date('Y-m-d');
		$query = "select tour_id,dep_date from tours_itinerary where publish_status=1 and tour_id in (".$in.") and (dep_date='".$today."' or dep_date>'".$today."') order by dep_date"; //echo $query; exit;
		$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[$fetch['tour_id']][] = $fetch['dep_date'];
		}
		return $result;
	}
	public function top_new_packages()
	{
		$query = "select * from tours where status=1 order by id desc limit 10"; //echo $query; exit;			
		$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[] = $fetch;
		}
		return $result;
	}	
	public function tours_city_name()
	{
		$query = "select * from tours_city order by CityName"; //echo $query; exit;
		$exe   = mysql_query($query) or die(mysql_error());
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[$fetch['id']] = $fetch['CityName'];
		}
		//debug($result); exit;
		return $result;
	}
	public function theme_set($query)
	{
		$themeArray = array();
		$exe = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			if($fetch['theme']){
				$theme = $fetch['theme'];
				$theme = explode(',',$theme);
				$themeArray = array_merge($themeArray,$theme);
			}
		} 
		$themeArray = array_unique($themeArray); 
		return $themeArray;
	}
	public function theme_name($theme_set)
	{
		foreach ($theme_set as $k => $v) { 
			$query = "select * from tour_subtheme where id='$v'";
			// $exe   = mysql_query($query);
			// $fetch = mysql_fetch_assoc($exe);
		$result = $this->db->query($query);	
			$fetch = $result->result_array();		
			$theme_name[$v] = $fetch['tour_subtheme'];
		}
		return $theme_name;
	}
	public function tours_itinerary($tour_id,$dep_date)
	{
		$query = "select * from tours_itinerary where tour_id='$tour_id' and publish_status=1 order by dep_date asc";
		// $exe   = mysql_query($query);
		// $num   = mysql_num_rows($exe);
		// $fetch = mysql_fetch_assoc($exe);
		$result = $this->db->query($query);	
		$fetch = json_decode(json_encode($result->result()),1);
		return $fetch[0];
	}
	public function tours_itinerary_dw($tour_id)
	{
		$query = "select * from tours_itinerary_dw where tour_id='$tour_id' order by id asc"; //echo $query; exit;
		$exe   = mysql_query($query);
		$num   = mysql_num_rows($exe);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[] = $fetch;
		}
		return $result;
	}


      public function tours_details($tour_id)
	{
		$query = "select * from tours where id='$tour_id' order by id asc"; //echo $query; exit;
		$exe   = mysql_query($query);
		$num   = mysql_num_rows($exe);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[] = $fetch;
		}
		return $result;
	}

	public function tours_itinerary_wd($tour_id)
	{
		$query = "select * from tours_itinerary where tour_id='$tour_id' "; //echo $query; exit;
		$exe   = mysql_query($query);
		$num   = mysql_num_rows($exe);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[] = $fetch;
		}
		return $result;
	}
	public function tours_date_price($tour_id)
	{
		$query = "select * from tours_itinerary where tour_id='$tour_id' order by dep_date"; //echo $query; exit;
		$exe   = mysql_query($query);
		$num   = mysql_num_rows($exe);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[] = $fetch;
		}
		return $result;
	}
	public function query_run($query) { //echo $query; exit;
		$exe   = mysql_query($query);
        if(!$exe) { die(mysql_error());}
        else{ return true;}
	}
	public function region_set($query)
	{
		// echo $query; exit;
		$in  = '';
		$exe = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$id[] = $fetch['tours_continent'];			
		} 
		$id = array_unique($id);

		foreach ($id as $k => $v) {
			if($in==''){ $in = "'".$v."'"; }
			else{ $in = $in.",'".$v."'"; } 
		} 
		$today = date('Y-m-d');
		$query = "select * from tours_continent where id in (".$in.") order by name asc"; //echo $query; exit;
		$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[$fetch['id']] = $fetch['name'];
		}
		return $result;
	}
	public function country_set($query)
	{
		$in  = '';
		$exe = mysql_query($query);
		$tours_countryArray = array();
		while($fetch = mysql_fetch_assoc($exe))
		{
			if($fetch['tours_country']){
				$tours_country = $fetch['tours_country'];
				$tours_country = explode(',',$tours_country);
				$tours_countryArray = array_merge($tours_countryArray,$tours_country);
			}
		} 
		$tours_countryArray = array_unique($tours_countryArray);
		// echo $this->db->last_query();die;
		$in = implode(',',$tours_countryArray);
		// debug($tours_countryArray);die;
		$query = "select * from tours_country where id in (".$in.") order by name asc"; 
		// echo $query; 
		$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[$fetch['id']] = $fetch['name'];
		}
		
		foreach($result as $key => $value) {
			
			$value=strtolower($value);
			$result[$key]=$value;
}
$result = array_unique($result);
foreach($result as $key => $value) {
			
			$value=ucfirst($value);
			$result[$key]=$value;
}
	
		return $result;
	}
	public function category_set($query)
	{
		// echo $query; exit;
		$in  = '';
		$tour_typeArray  = array();
		$exe = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			// $id[] = $fetch['tour_type'];			
			if($fetch['tour_type']){
				$tour_type = $fetch['tour_type'];
				$tour_type = explode(',',$tour_type);
				$tour_typeArray = array_merge($tour_typeArray,$tour_type);
			}
		} 
		$tour_typeArray = array_unique($tour_typeArray);
		// debug($tour_typeArray); exit('');
		$in = implode(',',$tour_typeArray);
		$today = date('Y-m-d');
		$query = "select * from tour_type where id in (".$in.") order by tour_type_name asc"; //echo $query; exit;
		$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[$fetch['id']] = $fetch['tour_type_name'];
		}
		return $result;
	}
	/**
	 * @author  Bishnu
	 */
	public function duration_set($value)
	{
		$duration_arr = [];
		foreach ($value as $tour) {
			array_push($duration_arr, $tour['duration']);
		}
		return array_unique($duration_arr);
	}
	public function perfect_holidays()
	{
		$query = "select * from tours where status=1 and perfect_holidays=1 order by rand() limit 5"; //echo $query; exit;
		$exe   = mysql_query($query);
		$num   = mysql_num_rows($exe);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[] = $fetch;
		}
		return $result;
	}
	

	









	public function tour_destinations()
	{
		$query = 'select * from tour_destinations order by destination'; //echo $query; exit;
		$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[] = $fetch;
		}
		return $result;
	}

	public function get_tour_destinations()
	{
		$query = 'select id,destination from tour_destinations order by destination'; //echo $query; exit;
		$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[$fetch['id']] = $fetch['destination'];
		}
		return $result;
	}
	public function tour_destinations_details($id)
	{
		$query = "select * from tour_destinations where id='$id'"; //echo $query; exit;
		$exe   = mysql_query($query);
		$fetch = mysql_fetch_assoc($exe);
		return $fetch;
	}
	public function add_tour_destination_save($query) {
		//debug($query);exit;
        $exe   = mysql_query($query);
        if(!$exe) { die(mysql_error());}
        else{ return true;}
	}
	public function delete_tour_destination($id) {
		$query = "delete from tour_destinations where id='$id'";
        $exe   = mysql_query($query);
        if(!$exe) { die(mysql_error());}
        else{ return true;}
	}
	public function tour_destination_details($id)
	{
		$query = "select * from tour_destinations where id='$id'";
        $exe   = mysql_query($query);
        $fetch = mysql_fetch_assoc($exe);
		return $fetch;
	}
	public function edit_tour_destination_save($query) {
		//debug($query);exit;
		$exe   = mysql_query($query);
        if(!$exe) { die(mysql_error());}
        else{ return true;}
	}
	public function activation_tour_destination($id,$status) {
		//echo 'model add_tour_destination_save';
		//debug($data);exit;
		$query = "update tour_destinations set status='$status' where id='$id'";
        $exe   = mysql_query($query);
        if(!$exe) { die(mysql_error());}
        else{ return true;}
	}
	public function AUTO_INCREMENT($table) {
		$HTTP_HOST = '192.168.0.63';
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
        }
	}
	public function add_tour_save($query) {
		$exe   = mysql_query($query);
        if(!$exe) { die(mysql_error());}
        else{ return true;}
	}
	public function tour_list() {
		$query = 'select * from tours order by id desc'; //echo $query; exit;
		$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[] = $fetch;
		}
		return $result;
	}
	public function activation_tour_package($query) {
		$exe   = mysql_query($query);
        if(!$exe) { die(mysql_error());}
        else{ return true;}
	}
	public function delete_tour_package($id) {
		// delete_tour_visited_cities delete_tour_dep_date
		$query = "delete from tours where id='$id'";
		$query1 = "delete from tour_visited_cities where tour_id='$id'";
		$query2 = "delete from tour_dep_dates where tour_id='$id'";
		$exe1   = mysql_query($query1);
		$exe2   = mysql_query($query2);
        $exe   = mysql_query($query);
        if(!$exe) { die(mysql_error());}
        else{ return true;}
	}	
	public function tour_dep_dates($tour_id) {
		$query = "select * from tour_dep_dates where tour_id='$tour_id' order by dep_date asc"; //echo $query; exit;
		$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[] = $fetch;
		}
		return $result;
	}
	public function tour_data($tour_id) {
		$query = "select * from tours where id='$tour_id'";
        $exe   = mysql_query($query);
        if(!$exe) { die(mysql_error());}
        else
        { 
        	$fetch = mysql_fetch_assoc($exe);
        	return $fetch;
        }
	}
	public function package_data($package_id) {
		$query = "select * from tours where package_id='$package_id'";
        $exe   = mysql_query($query);
        if(!$exe) { die(mysql_error());}
        else
        { 
        	$fetch = mysql_fetch_assoc($exe);
        	return $fetch;
        }
	}
	public function tour_dep_date_save($query) {
		$exe   = mysql_query($query);
        if(!$exe) { die(mysql_error());}
        else{ return true;}
	}
	public function delete_tour_dep_date($id) {
		$query = "delete from tour_dep_dates where id='$id'";
        $exe   = mysql_query($query);
        if(!$exe) { die(mysql_error());}
        else{ return true;}
	}
	public function tour_visited_cities($tour_id) {
		$query = "select * from tour_visited_cities where tour_id='$tour_id'"; //echo $query; exit;
		$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[] = $fetch;
		}
		return $result;
	}
	public function tour_visited_cities_save($query) {
		$exe   = mysql_query($query);
        if(!$exe) { die(mysql_error());}
        else{ return true;}
	}
	public function delete_tour_visited_cities($id) {
		$query = "delete from tour_visited_cities where id='$id'";
        $exe   = mysql_query($query);
        if(!$exe) { die(mysql_error());}
        else{ return true;}
	}
	public function tour_visited_cities_details($id) {
		$query = "select * from tour_visited_cities where id='$id'"; //echo $query; exit;
		$exe   = mysql_query($query);
		$fetch = mysql_fetch_assoc($exe);
		return $fetch;
	}
	public function edit_tour_visited_cities_save($query) {
		$exe   = mysql_query($query);
        if(!$exe) { die(mysql_error());}
        else{ return true;}
	}
	
	public function top_tour_destinations()
	{
		$query = 'select * from tour_destinations order by cms_status desc'; //echo $query; exit;
		$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[] = $fetch;
		}
		return $result;
	}
	public function tour_dep_dates_list($tour_id)
	{
		$query = "select * from tour_dep_dates where tour_id='$tour_id' order by dep_date asc"; //echo $query; exit;
		$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[] = $fetch;
		}
		return $result;
	}
	public function tour_visited_cities_list($tour_id)
	{
		$query = "select * from tour_visited_cities where tour_id='$tour_id' order by id asc"; //echo $query; exit;
		$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[] = $fetch;
		}
		return $result;
	}
	
	public function tour_visited_cities_all()
	{
		$query = "select * from tour_visited_cities order by id asc"; //echo $query; exit;
		$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[$fetch['id']] = $fetch['city'];
		}
		return $result;
	}
	public function tour_dep_dates_list_all()
	{
		$query = "select * from tour_dep_dates order by dep_date asc"; //echo $query; exit;
		$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[$fetch['tour_id']][] = $fetch['dep_date'];
		}
		return $result;
	}
	public function tour_dep_dates_list_published()
	{
		$query = "select * from tours_itinerary where publish_status=1 order by dep_date asc"; //echo $query; exit;
		$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[$fetch['tour_id']][] = $fetch['dep_date'];
		}
		return $result;
	}
	public function check_tour_dep_dates($tour_id,$dep_date)
	{
		$query = "select * from tour_dep_dates where tour_id='$tour_id' and dep_date='$dep_date'"; //echo $query; exit;
		$exe   = mysql_query($query);
		$num   = mysql_num_rows($exe);
		return $num;
	}
	public function ajax_tour_publish($query)
	{
		$exe   = mysql_query($query);
		$num   = mysql_num_rows($exe);
		return $num;
	}
	public function tour_date_list()
	{
		$query = "select * from tours_itinerary order by tour_id asc"; //echo $query; exit;
		$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[] = $fetch;
		}
		return $result;
	}
	public function tours_enquiry()
	{
		$query = "select * from tours_enquiry order by id desc"; //echo $query; exit;
		$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[] = $fetch;
		}
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
	
	public function tour_type_details($id)
	{
		$query = "select * from tour_type where id='$id'"; //echo $query; exit;
		$exe   = mysql_query($query);
		$fetch = mysql_fetch_assoc($exe);
		return $fetch;
	}
	public function tour_inclusions()
	{
		$query = 'select * from tour_inclusions order by id desc'; //echo $query; exit;
		$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[] = $fetch;
		}
		return $result;
	}
	public function record_activation($table,$id,$status) {
		$query = "update ".$table." set status='$status' where id='$id'";
        $exe   = mysql_query($query);
        if(!$exe) { die(mysql_error());}
        else{ return true;}
	}
	public function record_delete($table,$id) {
		$query = "delete from ".$table." where id='$id'";
        $exe   = mysql_query($query);
        if(!$exe) { die(mysql_error());}
        else{ return true;}
	}
	public function table_record_details($table,$id)
	{
		$query = "select * from ".$table." where id='$id'"; //echo $query; exit;
		$exe   = mysql_query($query);
		$fetch = mysql_fetch_assoc($exe);
		return $fetch;
	}
	public function table_records($table,$order_by,$order)
	{
		$query = 'select * from '.$table.$order_by.$order; //echo $query; exit;
		$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[] = $fetch;
		}
		return $result;
	}
	
	public function tour_activity()
	{
		$query = 'select * from tour_activity order by tour_activity'; //echo $query; exit;
		$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[] = $fetch;
		}
		return $result;
	}

	
	public function ajax_tours_country($tours_country)
	{

		$query = "select * from tours_city where country_id='$tours_country' order by CityName"; //echo $query; exit;
		//echo $query; exit();
		$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[] = $fetch;
		}
		return $result;
	}
	
	
	public function tours_continent_country($tour_id)
	{
		$query = "select * from tours where id='$tour_id'"; // echo $query; exit;
		$exe   = mysql_query($query);
		$fetch = mysql_fetch_assoc($exe);

		$query = "select * from tours_country where continent='".$fetch['tours_continent']."' order by name"; // echo $query; exit;
		$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[] = $fetch;
		}
		return $result;
	}
	public function tours_country_city($tour_id)
	{
		$query = "select * from tours where id='$tour_id'"; // echo $query; exit;
		$exe   = mysql_query($query);
		$fetch = mysql_fetch_assoc($exe);

		$query = "select * from tours_city where country_id='".$fetch['tours_country']."' order by CityName	"; // echo $query; exit;
		$exe   = mysql_query($query);
		while($fetch = mysql_fetch_assoc($exe))
		{
			$result[] = $fetch;
		}
		return $result;
	}

	public function tour_price($tour_id)
	{  
		// $query = "select TPM.airliner_price AS pricing, TPM.value_type,TPM.markup,TPM.calculated_markup, TPM.tour_id, TPM.from_date, TPM.to_date,";
		// $qulery.= "OM.occupancy_name ";
		// $query.= "from tour_price_management AS TPM ";
		// $query.= "LEFT JOIN occupancy_managment AS OM ON OM.id = TPM.occupancy ";
		$query = "select * from tour_price_management";

		$query.= " WHERE tour_id = ".$tour_id;
		$query.= " AND to_date > '".date('Y-m-d')."' ORDER BY from_date ASC";
		//$query.= "  ORDER BY from_date ASC";
        $result = $this->custom_db->get_result_by_query($query);

        //debug($result);exit();
		$result = json_decode(json_encode($result),true);

		return $result;
	}
	
	public function get_top_package_desination_title()
	{
		$query = 'select * from top_package_desination_title '; //echo $query; exit;
		$exe   = mysql_query($query);
		while($fetch = mysql_fetch_row($exe))
		{
			$result = $fetch;
		}
		return $result;
	}
	
	function get_tour_city_package_list($search_chars,$return_query = false) {
		$raw_search_chars = $this->db->escape ( $search_chars );
		$r_search_chars = $this->db->escape ( $search_chars);
		
			//$query = "select * from tours_city join tours ON find_in_set(tours_city.id,tours.tours_city) > 0 where tours_city.CityName like ".$search_chars."";
			//$query = "SELECT * FROM tours t1 where find_in_set(lower(".$search_chars."), lower((select GROUP_CONCAT(tc1.name SEPARATOR ', ') from tours_country tc1 where tc1.id in (t1.tours_country)))) > 0 or t1.tours_country = '' or find_in_set(lower(".$search_chars."), lower((select GROUP_CONCAT(tc2.CityName SEPARATOR ', ') from tours_city tc2 where tc2.id in (t1.tours_city)))) > 0";
			// $query = "SELECT * FROM tours t1 where find_in_set(lower(".$search_chars."), lower((select GROUP_CONCAT(tc1.name SEPARATOR ', ') from tours_country tc1 where tc1.id in (t1.tours_country)))) > 0 or t1.tours_country = '' or lower(".$search_chars.") like CONCAT('%',lower((select GROUP_CONCAT(tc2.CityName SEPARATOR ', ') from tours_city tc2 where tc2.id in (t1.tours_city))), '%')";

			// Removed search cases(According to client)
				// $search_chars = $this->db->escape ('%'.$term.'%');
				// OR t1.package_description  LIKE '.$search_chars.'
			$search_chars = $this->db->escape ($search_chars);
				
			$query = 'SELECT t1.* FROM tours AS t1 
			LEFT JOIN tours_country_wise AS tcw1 ON t1.id=tcw1.tour_id 
			LEFT JOIN tours_city_wise AS tcw2 ON t1.id=tcw2.tour_id 
			LEFT JOIN tours_country AS tc1 ON tcw1.country_id=tc1.id 
			LEFT JOIN tours_city AS tc2 ON tcw2.city_id=tc2.id 
			LEFT JOIN tours_continent AS tc3 ON t1.tours_continent=tc3.id 
			LEFT JOIN tours_itinerary AS ti ON ti.tour_id=t1.id 
			LEFT JOIN tour_price_management AS tpm ON tpm.tour_id=t1.id 

			WHERE (tc1.name LIKE \'%'.str_replace("'", '', $search_chars).'%\' OR tc2.CityName LIKE \'%'.str_replace("'", '', $search_chars).'%\' OR tc3.name  LIKE \'%'.str_replace("'", '', $search_chars).'%\' OR t1.package_name LIKE \'%'.str_replace("'", '', $search_chars).'%\'  ) 
			AND t1.expire_date>"'.date('Y-m-d').'" 
			AND ti.publish_status=1 
			AND t1.status=1
			AND tpm.to_date>"'.date('Y-m-d').'"

			GROUP BY t1.id  order by t1.id DESC';
			//debug($query);
			$res = $this->db->query ( $query )->result_array ();
			// debug($res);exit;
			if($return_query){
			$result['query']  = $query;
			$result['data']  = $res;
			}else{
				$result  = $res;
			}
			return $result;
		}
		
	//}
	function get_tour_city_list($search_chars){
	$raw_search_chars = $this->db->escape ( $search_chars );
	$r_search_chars = $this->db->escape ( $search_chars);
	$search_chars = $this->db->escape ($search_chars.'%');
	
	$query = 'Select * from api_city_list acty inner join api_country_list ac on ac.origin=acty.country where acty.destination like ' . $search_chars . '
		OR ac.name like ' . $search_chars . '
		
		LIMIT 0, 20';
		
		return $this->db->query ( $query );
}
function insert_pax_details($app_reference,$data){
	// debug($data);exit;
	$tour_booking_details_data = array();
	if(is_logged_in_user()){
		$tour_booking_details_data['booked_by_id']=$this->entity_user_id;
	}	
	$tour_booking_details_data['booked_datetime']=date('Y-m-d H:i:s');
	$tour_booking_details_data['user_attributes']=json_encode($data);
	$tour_booking_details_data['email']=$data['email'];
	$this->custom_db->update_record ( 'tour_booking_details', $tour_booking_details_data , array('app_reference'=>$app_reference) );
	$this->custom_db->delete_record ('holiday_package_pax_details',array('app_reference'=>$app_reference));
	foreach ( $data ['passenger_type'] as $pk => $pv ) {
		$pax_details ['app_reference'] = $app_reference;
		$pax_details ['pax_type'] = $data['passenger_type'][$pk];;
		$pax_details ['pax_title'] = $data['name_title'][$pk];
		$pax_details ['pax_first_name'] = $data['first_name'][$pk];
		$pax_details ['pax_middle_name'] = $data['middle_name'][$pk];
		$pax_details ['pax_last_name'] = $data['last_name'][$pk];
		$pax_details ['pax_dob'] = date('Y-m-d',$data['date_of_birth'][$pk]);
		//debug($pax_details);exit();
		$this->custom_db->insert_record ( 'holiday_package_pax_details', $pax_details );
	}
	return $this->booking(array(array('app_reference','=','"'.$app_reference.'"')));
}


//addede by hemanth

function insert_pax_details_trvl($app_reference,$data){
	// debug($data);exit;
	$tour_booking_details_data = array();
	if(is_logged_in_user()){
		$tour_booking_details_data['booked_by_id']=$this->entity_user_id;
	}	
	$tour_booking_details_data['booked_datetime']=date('Y-m-d H:i:s');
	$tour_booking_details_data['user_attributes']=json_encode($data);
	$tour_booking_details_data['email']=$data['billing_email'];
	$this->custom_db->update_record ( 'tour_booking_details', $tour_booking_details_data , array('app_reference'=>$app_reference) );
	$this->custom_db->delete_record ('holiday_package_pax_details',array('app_reference'=>$app_reference));
	foreach ( $data ['passenger_type'] as $pk => $pv ) {
		$pax_details ['app_reference'] = $app_reference;
		$pax_details ['pax_type'] = $pv;
		$pax_details ['pax_title'] = $data['name_title'][$pk];
		$pax_details ['pax_first_name'] = $data['first_name'][$pk];
		$pax_details ['pax_middle_name'] = $data['middle_name'][$pk];
		$pax_details ['pax_last_name'] = $data['last_name'][$pk];
		$pax_details ['pax_dob'] = date('Y-m-d',$data['date_of_birth'][$pk]);
		$this->custom_db->insert_record ( 'holiday_package_pax_details', $pax_details );
	}
	return $this->booking(array(array('app_reference','=','"'.$app_reference.'"')));
}

//end
	public function tour_details($tour_id,$departure_date)
	{
		$result=array();
		$td_query = 'select T.*,TC.name AS country_name from tours AS T LEFT JOIN tours_country AS TC ON TC.id=T.tours_country WHERE T.id="'.$tour_id.'" ';
		$tours_details = $this->db->query ( $td_query )->result_array ();
		$result['tours_details'] = $tours_details[0];
		if(!empty($tours_details[0]['tours_city'])){
			$tc_query = 'select CityName AS city_name from tours_city WHERE id IN ('.$tours_details[0]['tours_city'].') ';
			$tours_city_details = $this->db->query ( $tc_query )->result_array ();
			$result['tours_details']['city_name'] = array_column($tours_city_details, 'city_name');
		}
		if(!empty($tours_details[0]['tour_id']) && !empty($tours_details[0]['departure_date']) ){
			$tp_query = 'select *  from tour_price_management WHERE tour_id='.$tour_id.' AND from_date<="'.$departure_date.'" AND to_date>="'.$departure_date.'"  ';
			$tours_price_details = $this->db->query ( $tp_query )->result_array ();
			$result['tours_details']['price'] = $tours_price_details[0]['final_airliner_price'];
			$result['tours_details']['currency'] = $tours_price_details[0]['currency'];
		}
		return $result;
	}
	function booking_original($condition = array(), $count = false, $offset = 0, $limit = 100000000000) {
		$response ['status'] = SUCCESS_STATUS;
		$response ['data'] = array ();		
		$condition = $condition_static.$this->custom_db->get_custom_condition ( $condition );
		if ($count) {
			$query = 'select COUNT(*) AS total_records from tour_booking_details AS BD WHERE 1=1 ' . $condition;
			$data = $this->db->query ( $query )->row_array ();
			return $data ['total_records'];
		} else {		
			$bd_query = 'select BD.* from tour_booking_details AS BD  WHERE 1=1 ' . $condition . ' order by BD.origin desc limit ' . $limit;
			$booking_details = $this->db->query ( $bd_query )->result_array ();
			$result=array();
			foreach ($booking_details as $value) {
				$app_reference = $value['app_reference'];
				$attributes=$value['attributes'];
				$attributes=json_decode($attributes,true);
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
			}
			// debug($result); exit('');
			$response ['data'] = $result;
			return $response;
		}
	}


function booking($condition = array(), $count = false, $offset = 0, $limit = 100000000000,$module='b2c') {
	// error_reporting(E_ALL);
		$response ['status'] = SUCCESS_STATUS;
		$response ['data'] = array ();		
		$condition = $condition_static.$this->custom_db->get_custom_condition ( $condition );
		if ($count) {
			if($module=='b2c')
			$query = 'select COUNT(*) AS total_records from package_booking_details AS BD WHERE 1=1 ' . $condition.' AND BD.created_by_id=0';
		else
			$query = 'select COUNT(*) AS total_records from package_booking_details AS BD WHERE 1=1 ' . $condition.' AND BD.created_by_id!=0';
			$data = $this->db->query ( $query )->row_array ();
			return $data ['total_records'];
		} else {		

			$pack_attr=array();
$pack_apps=array();
// debug($pack_attr);
// die;
if($module=='b2c')
			$bd_query = 'select BD.* from package_booking_details AS BD where 1=1 '. $condition . ' AND BD.created_by_id=0 order by BD.origin desc limit ' . $limit;
		else
			$bd_query = 'select BD.* from package_booking_details AS BD where 1=1 '. $condition . ' AND BD.created_by_id!=0 order by BD.origin desc limit ' . $limit;
			// debug($bd_query);
			// die;
			$booking_details = $this->db->query ( $bd_query )->result_array ();
$rr=0;
foreach($booking_details as $key=>$value)
{

	$p=json_decode($value['attributes'],1);
	if(isset($p['package_id']))
	{
		$pack_attr[$key]=$p['package_id'];

	}

    $pack_apps[$rr]=$value['app_reference'];
		$rr++;
}
$pack_attr=implode(',',$pack_attr);
$pack_apps=implode(',',$pack_apps);
$pay_data=array();


$pg_query = 'select BD.* from payment_gateway_details AS BD ';
$pg_data=$this->db->query($pg_query)->result_array();
// debug($pg_data);
// die;
$rr=0;
foreach($pg_data as $t=>$m)
{
	// debug($m);
	// debug($pack_apps[$rr]);
	// die;
	if($m['app_reference']==$pack_apps[$rr])
	{
		$pay_data[$rr]=$t;
$rr++;
	}

}
// debug($pay_data);
// die;
    $select="Select * from package as P1 join package_itinerary as P2 on P1.package_id=P2.package_id and P1.package_id IN(".$pack_attr.") ";
    // debug($select); exit('');
    $ddata=$this->db->query($select)->result_array();
    // debug($ddata);
    // die;
    $response['package_details']=$ddata;
    $select="Select * from package_cancellation where package_id IN(".$pack_attr.")";
    
    $ddata=$this->db->query($select)->result_array();
    $response['cancel_details']=$ddata;
			 
			
			
			$response ['data'] = $booking_details;
			// debug($response); exit('');
			return $response;
		}
	}


	/*
     * Jebeen.M
     * holiday Booking 
     *
     */

function get_booking_details($app_reference,$booking_source, $status ) {
	// debug($app_reference);
	// die;
	// error_reporting(E_ALL);
		$response ['status'] = SUCCESS_STATUS;
		$response ['data'] = array ();	
		if(isset($condition))	
		$condition = $condition_static.$this->custom_db->get_custom_condition ( $condition );
		if (isset($count)) {
			$query = 'select COUNT(*) AS total_records from package_booking_details AS BD WHERE BD.app_reference='.'"'.$app_reference.'"';
			$data = $this->db->query ( $query )->row_array ();
			return $data ['total_records'];
		} else {		

			
			$bd_query = 'select BD.* from package_booking_details AS BD where BD.app_reference='.'"'.$app_reference.'"'.' order by BD.origin desc';
			
			$booking_details = $this->db->query ( $bd_query )->result_array ();
			// debug($booking_details);
			// die;
			$pack_id=json_decode($booking_details[0]['attributes'],1);
// debug($booking_details);
// die;
$p_id=$pack_id['package_id'];
// debug($p_id);
// die;
    $select="Select * from package as P1 join package_itinerary as P2 on P1.package_id=P2.package_id and P1.package_id =".$p_id;
    // debug($select); exit('');
    $ddata=$this->db->query($select)->result_array();
    // debug($ddata);
    // die;
    $response['package_details']=$ddata;
    $select="Select * from package_cancellation where package_id =".$p_id;
    
    $ddata=$this->db->query($select)->result_array();
    $response['cancel_details']=$ddata;
			 
			
			
			$response ['data'] = $booking_details;
			// debug($response); exit('');
			return $response;
		}
	}



function booking_tour($condition = array(), $count = false, $offset = 0, $limit = 100000000000) {

		$response ['status'] = SUCCESS_STATUS;
		$response ['data'] = array ();
		$tour_id = $condition[0][2]; 
		$tour_id = str_replace('"', '', $tour_id);
		$condition_static ='';
		// debug($condition);
		$condition = $condition_static.$this->custom_db->get_custom_condition ( $condition );
		$app_reference = HOLIDAY_BOOKING;
		$query = 'select *  from package WHERE 1=1 ' . $condition;
		// debug($query);
		// die;
		$data  = $this->db->query ( $query )->row_array ();
		$result[$app_reference]['tours_details'] = $data;
		$tp_query = 'select *  from tour_price_management WHERE tour_id='.$tour_id.'';
					$tours_price_details = $this->db->query ( $tp_query )->result_array ();
					//debug($tours_price_details);exit();
					$result[$app_reference]['tours_details']['price'] = $tours_price_details[0]['adult_airliner_price'];
					$result[$app_reference]['tours_details']['currency'] = $tours_price_details[0]['currency'];

					 $min_adult = $tour_price_changed[0]['adult_airliner_price'];
			//to find the minimum amount
			$min_adult = $tours_price_details[0]['adult_airliner_price'] ;		 
    foreach ($tours_price_details as $key => $value) {

      if($min_adult>=$value['adult_airliner_price']){
        $min_adult = $value['adult_airliner_price'];
        $key_min = $key; 
      }

    }
    $min_price_adult = $min_adult;
    $min_price_child = $tours_price_details[$key_min]['child_airliner_price'];

    $result[$app_reference]['tours_details']['price'] = ($min_price_adult*$this->session->userdata('adult_count'))+($min_price_child*$this->session->userdata('child_count'));

		$response ['data'] = $result;
		return $response;
					
		// debug($data);exit();	

			 
		//debug($condition);exit();
	}


	function user_booking($condition = array(), $count = false, $offset = 0, $limit = 100000000000) {
		$response ['status'] = SUCCESS_STATUS;
		$response ['data'] = array ();		
		$condition[] = array('BD.booked_by_id','=',$GLOBALS['CI']->entity_user_id);
		$condition = $condition_static.$this->custom_db->get_custom_condition ( $condition );
		if ($count) {
			$query = 'select COUNT(*) AS total_records from tour_booking_details AS BD WHERE 1=1 ' . $condition;
			$data = $this->db->query ( $query )->row_array ();
			//debug($this->db->last_query()); exit('');
			return $data ['total_records'];
		} else {		
			$bd_query = 'select BD.* from tour_booking_details AS BD  WHERE 1=1 ' . $condition . ' order by BD.origin desc limit ' . $limit;
			$booking_details = $this->db->query ( $bd_query )->result_array ();
			$result=array();
			foreach ($booking_details as $value) {
				$app_reference = $value['app_reference'];
				$attributes=$value['attributes'];
				$attributes=json_decode($attributes,true);
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
			}
			// debug($result); exit('');
			$response ['data'] = $result;
			return $response;
		}
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

	// /*
	// function : To get package
 //    param    : fromdate,todate,duration,adult count,child count in array  	
 //    return   : Array()	
	// */
	// public function get_packeges($data){

	// 	$this->db->where(array('id'=>182));
	// 	$this->db->select('T.id,T.package_name,T.duration,TPM.to_date,TPM.from_date, TPM.adult_airliner_price, TPM.child_airliner_price');
	// 	$this->db->from('tour_price_management as TPM');
	// 	$this->db->join('');
	
	// 	debug($this->db->last_query($query));exit();
	// 	//TPM.to_date,TPM.from_date, TPM.adult_airliner_price, TPM.child_airliner_price
 //    }

	/**
	 * get search data and validate it
	 */
	function get_safe_search_data($search_id)
	{
		$search_data = $this->get_search_data($search_id);
		// debug($search_id);
		// die;
		$success = true;
		$clean_search = '';
		if ($search_data != false) {
			//validate
			//debug($search_data);exit;
			$temp_search_data = json_decode($search_data['search_data'], true);
			//debug($temp_search_data);exit;
			$clean_search = $this->clean_search_data($temp_search_data);
			$success = $clean_search['status'];
			$clean_search = $clean_search['data'];
		} else {
			$success = false;
		}
		return array('status' => $success, 'data' => $clean_search);
	}

	/**
	 * get search data without doing any validation
	 * 
	 * @param
	 *        	$search_id
	 */
	function get_search_data($search_id) {
		if (empty ( $this->master_search_data )) {
			$search_data = $this->custom_db->single_table_records ( 'search_history', '*', array (
					'search_type' => META_PACKAGE_COURSE,
					'origin' => $search_id 
			) );
			if ($search_data ['status'] == true) {
				$this->master_search_data = $search_data ['data'] [0];
			} else {
				return false;
			}
		}
		return $this->master_search_data;
	}
	/**
	 * Clean up search data
	 */
	function clean_search_data($temp_search_data) {
		//debug($temp_search_data); exit;
		$success = true;
		// make sure dates are correct
		if ((strtotime ( $temp_search_data ['hotel_checkin'] ) > time () && strtotime ( $temp_search_data ['hotel_checkout'] ) > time ()) || date ( 'Y-m-d', strtotime ( $temp_search_data ['hotel_checkin'] ) ) == date ( 'Y-m-d' )) {
			// if (strtotime($temp_search_data['hotel_checkin']) > strtotime($temp_search_data['hotel_checkout'])) {
			// Swap dates if not correctly set
			$clean_search ['from_date'] = $temp_search_data ['hotel_checkin'];
			$clean_search ['to_date'] = $temp_search_data ['hotel_checkout'];
			/*
			 * } else {
			 * $clean_search['from_date'] = $temp_search_data['hotel_checkout'];
			 * $clean_search['to_date'] = $temp_search_data['hotel_checkin'];
			 * }
			 */
			$clean_search ['no_of_nights'] = abs ( get_date_difference ( $clean_search ['from_date'], $clean_search ['to_date'] ) );
		} else {
			$success = false;
		}
		// city name and country name
		
		if (isset ( $temp_search_data ['hotel_destination'] ) == true) {
			$clean_search ['hotel_destination'] = $temp_search_data ['hotel_destination'];
		}
		if (isset ( $temp_search_data ['city'] ) == true) {
			$clean_search ['location'] = $temp_search_data ['city'];
			$temp_location = explode ( '(', $temp_search_data ['city'] );
			$clean_search ['city_name'] = trim ( $temp_location [0] );
			if (isset ( $temp_location [1] ) == true) {
				// Pop will get last element in the array since element patterns can repeat
				$clean_search ['country_name'] = trim ( array_pop ( $temp_location ), '() ' );
			} else {
				$clean_search ['country_name'] = '';
			}
		} else {
			$success = false;
		}
		
		// Occupancy
		if (isset ( $temp_search_data ['rooms'] ) == true) {
			$clean_search ['room_count'] = abs ( $temp_search_data ['rooms'] );
		} else {
			$success = false;
		}
		if (isset ( $temp_search_data ['adult'] ) == true) {
			$clean_search ['adult_config'] = $temp_search_data ['adult'];
		} else {
			$success = false;
		}
		
		if (isset ( $temp_search_data ['child'] ) == true) {
			$clean_search ['child_config'] = $temp_search_data ['child'];
		}
		
		if (valid_array ( $temp_search_data ['child'] )) {
			foreach ( $temp_search_data ['child'] as $tc_k => $tc_v ) {
				if (intval ( $tc_v ) > 0) {
					$child_age_index = $tc_v;
					foreach ( $temp_search_data ['childAge_' . ($tc_k + 1)] as $ic_k => $ic_v ) {
						if($ic_k < $child_age_index){
							$clean_search ['child_age'] [] = $ic_v;
						}
					}
				}
			}
		}
		
		if (strtolower ( $clean_search ['country_name'] ) == 'india') {
			$clean_search ['is_domestic'] = true;
		} else {
			$clean_search ['is_domestic'] = false;
		}
		return array (
				'data' => $clean_search,
				'status' => $success 
		);
	}
	/**
	 * get all the booking source which are active for current domain
	 */
	function active_booking_source() {
		$query = 'select BS.source_id, BS.origin from meta_course_list AS MCL, booking_source AS BS, activity_source_map AS ASM WHERE
		MCL.origin=ASM.meta_course_list_fk and ASM.booking_source_fk=BS.origin and MCL.course_id=' . $this->db->escape ( META_PACKAGE_COURSE ) . '
		and BS.booking_engine_status=' . ACTIVE . ' AND MCL.status=' . ACTIVE . ' AND ASM.status="active"';
		//echo $query;die;
		return $this->db->query ( $query )->result_array ();
	}

	public function search_combi($book_id)
	{
		$query = 'select * from comb_booking_details where hotel_book_id="'.$book_id.'"'; 
		// echo $query; exit;
		$tour_list = $this->db->query ( $query )->result_array ();
		// debug($tour_list);exit;
		if(valid_array($tour_list) && isset($tour_list[0]['id']) && !empty($tour_list))
		{
			$result['app_reference']=$tour_list[0]['tour_book_id'];
			$result['status']=1;
		}else{
			$result['status']=0;
		}
		return $result;
	}



/**
	 * SAve search data for future use - Analytics
	 * 
	 * @param array $params        	
	 */
	function save_search_data($search_data, $type) {
		$data ['domain_origin'] = get_domain_auth_id ();
		$data ['search_type'] = $type;
		$data ['created_by_id'] = intval ( @$this->entity_user_id );
		$data ['created_datetime'] = date ( 'Y-m-d H:i:s' );
		
		$temp_location = explode ( '(', $search_data ['city'] );
		$data ['city'] = trim ( $temp_location [0] );
		if (isset ( $temp_location [1] ) == true) {
			$data ['country'] = trim ( $temp_location [1], '() ' );
		} else {
			$data ['country'] = '';
		}
		$data ['check_in'] = date ( 'Y-m-d', strtotime ( $search_data ['hotel_checkin'] ) );
		$data ['nights'] = abs ( get_date_difference ( $search_data ['hotel_checkin'], $search_data ['hotel_checkout'] ) );
		$data ['rooms'] = 0;//$search_data ['rooms'];
		$data ['total_pax'] = array_sum ( $search_data ['adult'] ) + array_sum ( $search_data ['child'] );
		$this->custom_db->insert_record ( 'search_holiday_history', $data );
	}
// Arjun code's for New Enquiry Form
	public function create_new_enquiry($table, $data){
		$query = $this->db->insert($table, $data);
			if ($query) {
				return true;
			}
	}


	public function check_enquiry($table, $where){
			$query = $this->db->
					select('*')
					->where($where)
					->get($table);
					return TRUE;
	}

	

}
