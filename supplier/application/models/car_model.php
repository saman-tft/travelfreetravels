<?php
/**
 * Library which has generic functions to get data
 *
 * @package    Provab Application
 * @subpackage Car Model
 * @author     Arjun J<arjunjgowda260389@gmail.com>
 * @version    V2
 */
Class Car_Model extends CI_Model
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
					from car_booking_details BD
					join car_booking_trip AS HBID on BD.booking_reference=HBID.booking_reference
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
			$bd_query = 'select * from car_booking_details AS BD 
						WHERE BD.domain_origin='.get_domain_auth_id().''.$condition.'
						order by BD.origin desc limit '.$offset.', '.$limit;
			$booking_details = $this->db->query($bd_query)->result_array();
			$app_reference_ids = $this->booking_data_formatter->implode_app_reference_ids($booking_details);
			if(empty($app_reference_ids) == false) {
				
				$cancellation_details_query = 'select * from hotel_cancellation_details AS HCD 
							WHERE HCD.app_reference IN ('.$app_reference_ids.')';
				
				$cancellation_details	= $this->db->query($cancellation_details_query)->result_array();
			}
			$response['data']['booking_details']			= $booking_details;
		
			$response['data']['cancellation_details']	= $cancellation_details;
			return $response;
		}
	}
		function b2c_carcrs_report($condition=array(), $count=false, $offset=0, $limit=100000000000)
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
			$query = 'select count(distinct(BD.app_reference)) as total_records
					from car_booking_details BD
					join car_booking_itinerary_details AS HBID on BD.app_reference=HBID.app_reference
					join payment_option_list AS POL on BD.payment_mode=POL.payment_category_code
					left join user as U on BD.created_by_id = U.user_id 
					where (U.user_type='.B2C_USER.' OR BD.created_by_id = 0) AND BD.booking_source="'.PROVAB_CAR_CRS_BOOKING_SOURCE.'" AND BD.domain_origin='.get_domain_auth_id().' '.$condition.'';

			$data = $this->db->query($query)->row_array();
			return $data['total_records'];
		} else {
			$this->load->library('booking_data_formatter');
			$response['status'] = SUCCESS_STATUS;
			$response['data'] = array();
			$booking_itinerary_details	= array();
			$booking_customer_details	= array();
			$cancellation_details = array();
			
			$bd_query = 'select BD.* ,U.user_name,U.first_name,U.last_name from car_booking_details AS BD
					     left join user U on BD.created_by_id =U.user_id 					     
						 WHERE  (U.user_type='.B2C_USER.' OR BD.created_by_id = 0) AND BD.booking_source="'.PROVAB_CAR_CRS_BOOKING_SOURCE.'" AND BD.domain_origin='.get_domain_auth_id().''.$condition.'						 
						 order by BD.created_datetime desc, BD.origin desc limit '.$offset.', '.$limit.'';

			$booking_details = $this->db->query($bd_query)->result_array();
			$app_reference_ids = $this->booking_data_formatter->implode_app_reference_ids($booking_details);
			if(empty($app_reference_ids) == false) {
				$id_query = 'select * from car_booking_itinerary_details AS ID 
							WHERE ID.app_reference IN ('.$app_reference_ids.')';
				$cd_query = 'select * from car_booking_pax_details AS CD 
							WHERE CD.app_reference IN ('.$app_reference_ids.')';
				$ex_query = 'select * from car_booking_extra_details AS CD 
							WHERE CD.app_reference IN ('.$app_reference_ids.')';
				$cancellation_details_query = 'select * from car_cancellation_details AS HCD 
							WHERE HCD.app_reference IN ('.$app_reference_ids.')';
				$booking_itinerary_details	= $this->db->query($id_query)->result_array();
				$booking_customer_details	= $this->db->query($cd_query)->result_array();
				$booking_extra_details	= $this->db->query($ex_query)->result_array();
				$cancellation_details	= $this->db->query($cancellation_details_query)->result_array();
			}
			$response['data']['booking_details']			= $booking_details;
			$response['data']['booking_itinerary_details']	= $booking_itinerary_details;
			$response['data']['booking_pax_details']	= $booking_customer_details;
			$response['data']['booking_extra_details']	= $booking_extra_details;
			$response['data']['cancellation_details']	= $cancellation_details;
			// debug($response);exit;
			return $response;
		}
	}
		function get_city_list($search_chars)

	{

		$search_chars = $this->db->escape(''.$search_chars.'%');

		$query = 'Select Country_ISO, origin, Country_Name_EN, City_ID, City_Name_EN, City_IATA as Airport_IATA from Car_City where City_Name_EN like '.$search_chars.'

		OR Country_Name_EN like '.$search_chars.' AND City_IATA !="" LIMIT 0, 10';

		

		return $this->db->query($query);

		

	}
		function get_airport_list($search_chars)

	{

		$raw_search_chars = $this->db->escape($search_chars);

		$r_search_chars = $this->db->escape($search_chars.'%');

		$search_chars = $this->db->escape('%'.$search_chars.'%');

		$query = 'Select * from car_airport where Airport_Name_EN like '.$search_chars.'

		OR Airport_IATA like '.$search_chars.' OR Country_ISO like '.$search_chars.'

		ORDER BY top_destination DESC,

		CASE

			WHEN	Airport_IATA	LIKE	'.$raw_search_chars.'	THEN 1

			WHEN	Airport_Name_EN	LIKE	'.$raw_search_chars.'	THEN 2

			WHEN	Country_ISO		LIKE	'.$raw_search_chars.'	THEN 3



			WHEN	Airport_IATA	LIKE	'.$r_search_chars.'	THEN 4

			WHEN	Airport_Name_EN	LIKE	'.$r_search_chars.'	THEN 5

			WHEN	Country_ISO		LIKE	'.$r_search_chars.'	THEN 6



			WHEN	Airport_IATA	LIKE	'.$search_chars.'	THEN 7

			WHEN	Airport_Name_EN	LIKE	'.$search_chars.'	THEN 8

			WHEN	Country_ISO		LIKE	'.$search_chars.'	THEN 9

			ELSE 10 END

		LIMIT 0, 20';

		

		return $this->db->query($query);

	}

	public function get_countries() {
		$this->db->limit ( 1000 );
		$this->db->order_by ( "name", "asc" );
		$qur = $this->db->get ( "country" );
		return $qur->result ();
	}

	public function get_crs_city_list($value) { 
		$this->db->where ( 'country_id',
		$value ); return $this->db->get ( 'car_active_country_city_master' )->result ();
	}
public function get_branchusers()
{
	$this->db->select('*');

$this->db->where(array('agency_name !='=> '','user_type'=>CAR_BRANCH_USER));

$this->db->from('user ');

 $this->db->distinct('branch_id');


$query = $this->db->get()->result_array();

}
public function get_branchusers_count()
{
	$this->db->select('*');

$this->db->where(array('agency_name !='=> '','user_type'=>CAR_BRANCH_USER));

$this->db->from('user ');

 $this->db->distinct('branch_id');


$query = $this->db->get();

}

//----------------------sdp ------- 
	public function get_supplier_list($id=0,$branch_id){
		//\ debug($id);
		// debug($branch_id);
		// die;
	
		$condition = '';
		

		if(!empty($branch_id))
		    $branch_id = ' AND branch_id='.$branch_id;


		if($id>0){
			$condition=' AND u.user_id='.$id;
		}
	


	// debug(SUPPLIER);exit();
		$query = "SELECT u.*,u.country_name as country_origin,c.city_name , cm.country_name FROM user as u LEFT JOIN car_active_country_city_master as c on  u.city= c.origin LEFT JOIN api_country_master as cm on cm.origin = c.country_id where u.country_name=c.country_id AND u.user_type=".SUPPLIER.$branch_id.$condition;
	// debug($query);die;
		$exeute_query = $this->db->query($query);
		$supplier_list =array();
		if($exeute_query->num_rows()!=''){
			$supplier_list = $exeute_query->result_array();
		}
		return $supplier_list;

	}

	public function get_branch_user_name_on_id($id){

        $query = "Select agency_name from user where user_id=".$id;
        $data = $this->db->query($query)->row_array();
        return $data;
    }

	public function get_branch_user_list($condition,$count=false,$offset=0,$limit=10000000,$id=''){

		$condition = $this->custom_db->get_custom_condition($condition);
		
		if(isset($condition) == true)
		{
			$offset = 0;
		}else{
			$offset = $offset;
		}
		// echo "in";die;
		if($count){
					// echo "in1";die;
			$query = "SELECT count(distinct(u.user_id)) as total_records FROM user as u LEFT JOIN car_active_country_city_master as c on  u.city= c.origin where u.country_name=c.country_id AND u.user_type=".CAR_BRANCH_USER.$condition;
			 // echo $query;die;
			$data = $this->db->query($query)->row_array();
			return $data['total_records'];
		}else{
					// echo "in3";die;
			/*Fixed for active car country and city list*/
			$query = "SELECT u.*, u.country_name as country_origin,c.city_name as city_name , ac.country_name FROM user as u LEFT JOIN car_active_country_city_master as c on u.city= c.origin LEFT JOIN api_country_master as ac on c.country_id=ac.origin  where u.country_name=c.country_id AND u.user_type=".CAR_BRANCH_USER.$condition;
			$exeute_query = $this->db->query($query);
			 // echo $this->db->last_query();die;
			// debug($exeute_query);die;
			$supplier_list =array();
			if($exeute_query->num_rows()!=''){
				$supplier_list = $exeute_query->result_array();
				// debug($supplier_list);die;
			}
			return $supplier_list;
		}
		
		

	}
	public function car_type_list(){
		$car_type_list = $this->custom_db->single_table_records('car_make','*');

		$type_arr = array();
		if($car_type_list['status']==1){
			return $car_type_list['data'];
		}
		return $type_arr;
	}



	public function car_list($id=0,$supplier_id=0){
		$extra = '';
		if (intval($id) > 0) {
			$extra = ' where CD.id='.$id;
		}
		if($extra!=''){
			$extra .=' AND CD.supplier_id='.$supplier_id;
		}else{
			$extra .= ' where CD.supplier_id='.$supplier_id;
		}
		$query = 'SELECT CD.id as car_id,CD.policy_type,CD.cancellation_day,CD.cancel_percentage,CD.no_of_car,CD.icon,CD.vehicle_no,CD.id, CA.car_features_id, CD.name,CD.localuse,CD.transfers,CD.outsource, CM.make_name, CT.transmission_name, CC.class_name, CD.car_make_id, CD.car_class_id, CD.car_transmission_id, CD.no_of_seats, CF.feature_name, CD.status,CD.fuel_type,CD.fuel_capacity,CD.weekend_price,CD.weekdays_price  FROM
		car_details AS CD LEFT JOIN car_make AS CM ON CM.id=CD.car_make_id LEFT JOIN car_class AS CC ON CC.id=CD.car_class_id LEFT JOIN car_transmission AS CT
		ON CT.id=CD.car_transmission_id LEFT JOIN car_attributes AS CA ON CD.id=CA.car_details_id LEFT JOIN car_features AS CF ON CA.car_features_id=CF.id'.$extra;
		$data = $this->db->query($query)->result_array();
		
		if (valid_array($data) == true) {
			foreach($data as $k => $v) {
				$response['data']['list'][$v['id']] = $v;
				$response['data']['feature'][$v['id']][] = $v['car_features_id'];
			}
			$response['status'] = true;
			
		} else {
			$response['status'] = false;
			$response['data'] = false;
		}
		return $response;


	}

	public function active_car_list($id=0,$supplier_id=0){
		$extra = '';
		if (intval($id) > 0) {
			$extra = ' where CD.id='.$id;
		}
		if($extra!=''){
			$extra .=' AND CD.supplier_id='.$supplier_id;
		}else{
			$extra .= ' where CD.supplier_id='.$supplier_id;
		}
		$query = 'SELECT CD.id as car_id,CD.policy_type,CD.cancellation_day,CD.cancel_percentage,CD.no_of_car,CD.icon,CD.vehicle_no,CD.id, CA.car_features_id, CD.name,CD.localuse,CD.transfers,CD.outsource, CM.make_name, CT.transmission_name, CC.class_name, CD.car_make_id, CD.car_class_id, CD.car_transmission_id, CD.no_of_seats, CF.feature_name, CD.status,CD.fuel_type,CD.fuel_capacity,CD.weekend_price,CD.weekdays_price  FROM
		car_details AS CD LEFT JOIN car_make AS CM ON CM.id=CD.car_make_id LEFT JOIN car_class AS CC ON CC.id=CD.car_class_id LEFT JOIN car_transmission AS CT
		ON CT.id=CD.car_transmission_id LEFT JOIN car_attributes AS CA ON CD.id=CA.car_details_id LEFT JOIN car_features AS CF ON CA.car_features_id=CF.id'.$extra.' AND CD.status=1';
		$data = $this->db->query($query)->result_array();
		
		if (valid_array($data) == true) {
			foreach($data as $k => $v) {
				$response['data']['list'][$v['id']] = $v;
				$response['data']['feature'][$v['id']][] = $v['car_features_id'];
			}
			$response['status'] = true;
			
		} else {
			$response['status'] = false;
			$response['data'] = false;
		}
		return $response;


	}

	public function car_class(){
		$car_class = $this->custom_db->single_table_records('car_class','*');
		$type_arr = array();
		if($car_class['status']==1){
			return $car_class['data'];
		}
		return $type_arr;
	}
	public function car_features(){
		$car_features = $this->custom_db->single_table_records('car_features','*');
		$type_arr = array();
		if($car_features['status']==1){
			return $car_features['data'];
		}
		return $type_arr;
	}
	public function car_transmission(){
		$car_transmission = $this->custom_db->single_table_records('car_transmission','*');
		$type_arr = array();
		if($car_transmission['status']==1){
			return $car_transmission['data'];
		}
		return $type_arr;
	}
	public function update_car_assets($data,$cond){
		
	}
	/*
	 * Get vehicle performance
	 * */
	function get_performance_list($id=0)
	{
	
		if($id==0)
		{
			$condition="";
			if($this->entity_user_type!=ADMIN)
			{
				$condition.=" where CS.`city_id`=".$this->entity_city_id;
			}
		}
		else
		{
			$condition=" where VP.`id`=".$id;
			if($this->entity_user_type!=ADMIN)
			{
				$condition.=" and CS.`city_id`=".$this->entity_city_id;
			}
		}
		
		$query = 'SELECT VP.`id`,VP.`description`, VP.`type`, VP.`cost`, VP.`item`, VP.`amount`, VP.`date`, VP.`vehicle_id` FROM `vehicle_performance` AS VP LEFT JOIN car_supplier_vehicle_management AS CS ON VP.`vehicle_id`=CS.id'.$condition;
		$data = $this->db->query($query)->result_array();
		if (is_array($data) == true) {
			$response['status'] = true;
			$response['data'] = $data;
		} else {
			$response['status'] = false;
			$response['data'] = false;
		}
		return $response;
	}
		/*
	 * Get vehicle inventory
	 * */
	function get_vehicle_inventory_admin($id=0,$branch_id,$vendor_id=0)
	{
		if($vendor_id==0)
		{
			if($id==0)
			{
				$condition='';
				if($this->entity_user_type!=CAR_BRANCH_USER)
				{
					$condition.=" where CSVM.`branch_id`=".$branch_id;
				}
			}
			else
			{
				$condition=' where CSVM.`id`='.$id;
				if($this->entity_user_type!=CAR_BRANCH_USER)
				{
					$condition.=" and CSVM.`branch_id`=".$branch_id;
				}
			}
			
		}else
		{
			$condition=' where CSVM.`vendor_id`='.$vendor_id;
			
			if($this->entity_user_type!=CAR_BRANCH_USER)
			{
				$condition.=" and CSVM.`branch_id`=".$branch_id;
			}
		}
		
		
		$query = 'SELECT distinct(CSVM.id) ,CSVM.no_of_doors,CSVM.	luggage, CD.`vehicle_no`, CSVM.`description`, CSVM.`status`, CSVM.`created_datetime`, CSVM.`owner`, CSVM.`vendor_id`, VL.agency_name, CSVM.`car_id`,CD.name as car_name, CSVM.`branch_id`,CSVM.`added_by`,CSVM.door_type,CSVM.car_reg_no,CSVM.engine_no,CSVM.chassis_no,CSVM.color,CSVM.mileage,CSVM.insurance_company,CSVM.insurance_begin,CSVM.insurance_end,CSVM.purchase_from,CSVM.purchase_amount,CSVM.purchase_date,CSVM.tax_end_date,CSVM.permit_type,CSVM.permit_date,CSVM.emission,CSVM.authorization,CSVM.fitness FROM `car_supplier_vehicle_management`as CSVM  LEFT JOIN car_details AS CD ON CSVM.`car_id`=CD.id LEFT JOIN user AS VL ON CSVM.`vendor_id`=VL.user_id'.$condition;
		
		
		$data = $this->db->query($query)->result_array();
		
		
		
		
		if (is_array($data) == true) {
			$response['status'] = true;
			$response['data'] = $data;
		} else {
			$response['status'] = false;
			$response['data'] = false;
		}
		
		return $response;
	}
	/*
	 * Get vehicle inventory
	 * */
	function get_vehicle_inventory($id=0,$vendor_id=0)
	{
		// debug($id);
		// debug($vendor_id);die;
		if($vendor_id==0)
		{
			if($id==0)
			{
				$condition='';
				if($this->entity_user_type!=CAR_BRANCH_USER)
				{
					$condition.=" where CSVM.`branch_id`=".$this->entity_branch_id;
				}
			}
			else
			{
				$condition=' where CSVM.`id`='.$id;
				if($this->entity_user_type!=CAR_BRANCH_USER)
				{
					$condition.=" and CSVM.`branch_id`=".$this->entity_branch_id;
				}
			}
			
		}else
		{
			$condition=' where CSVM.`vendor_id`='.$vendor_id;
			
			if($this->entity_user_type!=CAR_BRANCH_USER)
			{
				$condition.=" and CSVM.`branch_id`=".$this->entity_branch_id;
			}
		}
		
		// if($id>0 && $vendor_id>0)

// $condition.=" and CSVM.`car_id`=".$id;
// echo "in".$condition;die;
		$query = 'SELECT distinct(CSVM.id) ,CSVM.no_of_doors,CSVM.	luggage, CD.`vehicle_no`, CSVM.`description`, CSVM.`status`, CSVM.`created_datetime`, CSVM.`owner`, CSVM.`vendor_id`, VL.agency_name, CSVM.`car_id`,CD.name as car_name, CSVM.`branch_id`,CSVM.`added_by`,CSVM.door_type,CSVM.car_reg_no,CSVM.engine_no,CSVM.chassis_no,CSVM.color,CSVM.mileage,CSVM.insurance_company,CSVM.insurance_begin,CSVM.insurance_end,CSVM.purchase_from,CSVM.purchase_amount,CSVM.purchase_date,CSVM.tax_end_date,CSVM.permit_type,CSVM.permit_date,CSVM.emission,CSVM.authorization,CSVM.fitness FROM `car_supplier_vehicle_management`as CSVM  LEFT JOIN car_details AS CD ON CSVM.`car_id`=CD.id LEFT JOIN user AS VL ON CSVM.`vendor_id`=VL.user_id'.$condition;
	

		$data = $this->db->query($query)->result_array();
		// echo "model";
		// echo $this->db->last_query();die;
		// debug($data);die;
		
		
		if (is_array($data) == true) {
			$response['status'] = true;
			$response['data'] = $data;
		} else {
			$response['status'] = false;
			$response['data'] = false;
		}
		return $response;
	}
	

function get_vehicle($id=0,$car_id=0,$vendor_id=0)
	{
		// debug($id);
		// debug($car_id);
		// debug($vendor_id);die;
		if($vendor_id==0)
		{
			if($id==0)
			{
				$condition='';
				if($this->entity_user_type!=CAR_BRANCH_USER)
				{
					$condition.=" where CSVM.`branch_id`=".$this->entity_branch_id;
				}
			}
			else
			{
				$condition=' where CSVM.`id`='.$id;
				if($this->entity_user_type!=CAR_BRANCH_USER)
				{
					$condition.=" and CSVM.`branch_id`=".$this->entity_branch_id;
				}
			}
			
		}else
		{
			$condition=' where CSVM.`vendor_id`='.$vendor_id;
			
			if($this->entity_user_type!=CAR_BRANCH_USER)
			{
				$condition.=" and CSVM.`branch_id`=".$this->entity_branch_id;
			}
		}
		
		if($id>0 && $vendor_id>0)

$condition.=" and CSVM.`car_id`=".$car_id;
// echo "in".$condition;die;
		$query = 'SELECT distinct(CSVM.id) ,CSVM.no_of_doors,CSVM.luggage, CD.`vehicle_no`, CSVM.`description`, CSVM.`status`, CSVM.`created_datetime`, CSVM.`owner`, CSVM.`vendor_id`, VL.agency_name, CSVM.`car_id`,CD.name as car_name, CSVM.`branch_id`,CSVM.`added_by`,CSVM.door_type,CSVM.car_reg_no,CSVM.engine_no,CSVM.chassis_no,CSVM.color,CSVM.mileage,CSVM.insurance_company,CSVM.pickup_information,CSVM.luggage,CSVM.insurance_begin,CSVM.insurance_end,CSVM.purchase_from,CSVM.purchase_amount,CSVM.purchase_date,CSVM.tax_end_date,CSVM.permit_type,CSVM.permit_date,CSVM.emission,CSVM.authorization,CSVM.fitness FROM `car_supplier_vehicle_management`as CSVM  LEFT JOIN car_details AS CD ON CSVM.`car_id`=CD.id LEFT JOIN user AS VL ON CSVM.`vendor_id`=VL.user_id'.$condition;
	

		$data = $this->db->query($query)->result_array();
		// echo "model";
		// echo $this->db->last_query();die;
		// debug($data);die;
		
		
		if (is_array($data) == true) {
			$response['status'] = true;
			$response['data'] = $data;
		} else {
			$response['status'] = false;
			$response['data'] = false;
		}
		return $response;
	}
	


	/*
	 * Get Driver inventory
	 * */
	function get_driver_inventory($id=0,$vendor_id=0,$status='')
	{
		// debug($id);
		// debug($vendor_id);
		// die;
// echo $this->entity_branch_id."sa";exit;
// 
		// $this->entity_branch_id=1;
// 
        $bl_name = '';
        	if($vendor_id==0)
		{
			// echo 1; die();
			if($id==0)
			{
				// echo "in";die;
				$condition='';


				if($this->entity_user_type!=CAR_BRANCH_USER)
				{
                    if($this->entity_user_type==ADMIN)
                    {
                        $condition.="left join user as BL ON BL.user_id = VL.branch_id ";
                        $bl_name = 'BL.agency_name as branch_name,';
                    }
                    else
					    $condition.=" where DL.`branch_id`=".$this->entity_branch_id;

				}

			}
			else
			{
				// echo "out";die;
				// echo $this->entity_branch_id;die;
				$condition=' where DL.`driver_fk_id`='.$id;
				
				if($this->entity_user_type!=CAR_BRANCH_USER )
				{

					$condition.=" and DL.`branch_id`=".$this->entity_branch_id;
				}   //commented for driver update
				else
				$condition.=" and DL.`branch_id`=VL.`branch_id`";

			}
		}
		else
		{
			// echo "in1";die;
			$condition=' where DL.`vendor_id`='.$vendor_id;
			
			if($this->entity_user_type==CAR_BRANCH_USER )
			{
				$condition.=" and DL.`branch_id`=".$this->entity_branch_id;
			}
			
			// debug($condition);die;
		}
		
		// debug($condition);die;
		$s_condition = '';
		if($status){
			$s_condition .= " AND DL.status=1";
		}
	

	 // if($this->entity_user_type == ADMIN)
	 //  	$query = 'SELECT distinct DL.password,DL.full_city_name,DL.driver_photo,DL.email,DL.driver_photo,DL.cart_validity,DL.police_validity,DL.badge_validity,DL.license_no,DL.badge_no,DL.validity,DL.police_number,DL.cart_number,DL.`driver_fk_id` as driver_id, DL.`branch_id`,DL.`full_name`, DL.`owner`, DL.`vendor_id`,VL.`agency_name` as supplier_name,'.$bl_name.' DL.`phone`, DL.`address`,DL.current_location,DL.`country_id`,DL.status ,DL.city_name, DL.`country_id` FROM `driver_list` AS DL LEFT JOIN user as VL ON DL.vendor_id=VL.supplier_id '.$condition.' AND VL.user_id=DL.driver_fk_id '.$s_condition;

		// else
		// debug($condition);
	$query = 'SELECT distinct DL.password,DL.full_city_name,DL.driver_photo,DL.email,DL.driver_photo,DL.cart_validity,DL.police_validity,DL.badge_validity,DL.license_no,DL.badge_no,DL.validity,DL.police_number,DL.cart_number,DL.`driver_fk_id` as driver_id, DL.`branch_id`,DL.`full_name`, DL.`owner`, DL.`vendor_id`,VL.agency_name as supplier_name,'.$bl_name.' DL.`phone`, DL.`address`,DL.current_location,DL.`country_id`,DL.status ,DL.city_name, DL.`country_id` FROM `driver_list` AS DL LEFT JOIN user as VL ON DL.vendor_id=VL.
		user_id '.$condition.$s_condition;
	

		// echo $query;
		// exit;
		$data = $this->db->query($query)->result_array();
		// debug($data);die;
		if (is_array($data) == true) {
			$response['status'] = true;
			$response['data'] = $data;
		} else {
			$response['status'] = false;
			$response['data'] = false;
		}
		return $response;
	}
	public function map_car_driver($supplier_id,$branch_id){
		//map_car_driver_list
		$query = "SELECT c.vehicle_no,v.car_reg_no,c.id as car_id, d.driver_fk_id as driver_id, mp.origin as map_id, c.name as car_name,d.phone as driver_phone_number,d.full_name as driver_name,mp.available_status as a_status,mp.current_status as status,mp.current_location,mp.full_address  from car_details as c LEFT JOIN map_car_driver_list as mp on c.id= mp.car_id LEFT JOIN driver_list as d on d.driver_fk_id= mp.driver_id LEFT JOIN 
		car_supplier_vehicle_management as v  on c.id = v.car_id WHERE c.status = 1 and   c.supplier_id=".$supplier_id." ORDER BY car_name ASC";
		// echo $query;
		// exit;
		$data =$this->db->query($query)->result_array();
		$response['status'] = false;
		$response['data']= array();
		if(is_array($data)==true){
			$response['status'] = true;
			$response['data']  = $data;
		}
		return $response;
			 
	}
	public function get_active_country_city_list($id=0){
		
		$condition ='';
		if($id>0){
			$condition = " WHERE city.origin =".$id;
		}
		$query = "SELECT city.*,m.country_name from car_active_country_city_master as city JOIN api_country_master as m ON city.country_id = m.origin".$condition;
		$exeute_query = $this->db->query($query);
		$data['status'] =false;
		$data['data'] = array();
		if($exeute_query->num_rows()!=''){
			$data['status'] =true;
			$data['data'] = $exeute_query->result_array();
		}
		return $data;
	}
	public function get_active_country_list(){
		$query = "SELECT m.* from api_country_master as m JOIN car_active_country_city_master as c on m.origin = c.country_id where m.origin = c.country_id GROUP BY c.country_id";
		$exeute_query = $this->db->query($query);
		$data['status'] =false;
		$data['data'] = array();
		if($exeute_query->num_rows()!=''){
			$data['status'] =true;
			$data['data'] = $exeute_query->result_array();
		}
		return $data;
	}
	//Function to get trip details
	public function trip_details($condition,$count=false,$offset=0, $limit=100000000000){
		$condition = '';

		$f_condition = $this->custom_db->get_custom_condition($condition);
		if(isset($f_condition) == true)
		{
			$offset = 0;
		}else{
			$offset = $offset;
		}

		$login_id = $this->entity_user_id;
		$branch_id = $this->entity_branch_id;
		$user_type = $this->entity_user_type;

		//login_id will be supplier or branch user or driver or admin
		if($user_type==ADMIN){
			$condition = ' where u.created_by_id='.$login_id;
		}elseif($user_type==CAR_BRANCH_USER){
			$condition = ' where u.branch_id='.$login_id;
		}elseif($user_type==CAR_SUPPLIER){
			$condition = ' where u.supplier_id='.$login_id.' AND u.branch_id='.$branch_id;
		}elseif($user_type==CAR_DRIVER){
			$condition = ' where u.user_id='.$login_id;
		}
		if($count){
			$query ="SELECT count(t.origin) as total_records FROM driver_trip_table as t 
			LEFT JOIN car_details as c  ON t.car_id = c.id 			
			LEFT JOIN user  as u ON t.driver_id = u.user_id ".$condition." ".$f_condition."ORDER BY t.origin DESC";

			$data = $this->db->query($query)->row_array();
			return $data['total_records'];
		}else{
			$query = "SELECT t.*,u.agency_name as driver_name,u.phone as driver_phone,c.name as car_name,c.vehicle_no,c.supplier_id FROM driver_trip_table as t 
			LEFT JOIN car_details as c  ON t.car_id = c.id 		
			LEFT JOIN user  as u ON t.driver_id = u.user_id ".$condition." ".$f_condition."ORDER BY t.origin DESC limit ".$offset.",".$limit;
			// echo $query;
			// exit;
			$result_array= array();
				$exeute_query = $this->db->query($query);
				if($exeute_query->num_rows()!=''){
					$result_array = $exeute_query->result_array();
				}
				return $result_array;
			}
	}
	public function booking_trip_details($condition,$count=false,$offset=0, $limit=100000000000){


		$f_condition = $this->custom_db->get_custom_condition($condition);

		if(isset($f_condition) == true)
		{
			$offset = 0;
		}else{
			$offset = $offset;
		}
		

		$login_id = $this->entity_user_id;
		$branch_id = $this->entity_branch_id;
		$user_type = $this->entity_user_type;
		$join_condition = '';
		$select_cols = '';
		//login_id will be supplier or branch user or driver or admin
		$u_condition = '';
		if($user_type==ADMIN){
			$u_condition = ' AND  u.created_by_id='.$login_id;
		}elseif($user_type==CAR_BRANCH_USER){
			$join_condition ='
				LEFT JOIN user as u ON u.user_id =t.supplier_id';

			$select_cols = '*,u.agency_name as supplier_name,u.phone as supplier_phone ';
			$u_condition = ' AND u.user_type='.CAR_SUPPLIER.' AND  u.branch_id='.$login_id;
		}elseif($user_type==CAR_SUPPLIER){

			$select_cols= "*,t.origin as trip_origin,t.driver_id as trip_driver,du.agency_name as driver_name,du.phone as driver_phone_number,u.agency_name as supplier_name,u.phone as supplier_phone_number, mp.origin as map_id";
			$join_condition ='
				LEFT JOIN user as u ON u.user_id =t.supplier_id LEFT JOIN user as du  ON du.user_id=t.driver_id';
			$u_condition = ' AND u.user_id='.$login_id.' AND u.branch_id='.$branch_id;
		}elseif($user_type==CAR_DRIVER){
			$select_cols= "*,u.agency_name as driver_name,u.phone as driver_phone_number, mp.origin as map_id";

			$join_condition ='
				LEFT JOIN user as u ON u.user_id =t.driver_id';
			$u_condition = ' AND u.user_id='.$login_id;
		}
		if($count){
			$query ="SELECT count(b.app_reference) as total_records FROM car_booking_trip as t LEFT JOIN car_booking_details as b ON b.booking_reference = t.booking_reference 
				LEFT JOIN car_details as c ON c.id = t.car_id 
				LEFT JOIN map_car_driver_list as mp ON mp.car_id = t.car_id 
				".$join_condition." WHERE 1=1".$u_condition.' '.$f_condition." ORDER BY t.pickup_date DESC";

			
			$data = $this->db->query($query)->row_array();
			return $data['total_records'];
		}else{
			// $query = "SELECT t.*,u.agency_name as driver_name,u.phone as driver_phone,c.name as car_name,c.vehicle_no,c.supplier_id FROM car_booking_trip as t 
			// LEFT JOIN car_details as c  ON t.car_id = c.id 		
			// LEFT JOIN user  as u ON t.supplier_id = u.user_id ".$condition." ".$f_condition."ORDER BY t.origin DESC limit ".$offset.",".$limit;
			// echo $query;
			// exit;

			$BD_query = "SELECT ".$select_cols." FROM car_booking_trip as t LEFT JOIN car_booking_details as b ON t.booking_reference = b.booking_reference 
				LEFT JOIN car_details as c ON c.id = t.car_id 
				LEFT JOIN map_car_driver_list as mp ON mp.car_id = t.car_id 
				".$join_condition."
  WHERE 1=1".$u_condition.' '.$f_condition." ORDER BY t.pickup_date DESC,t.origin DESC limit ".$offset." , ".$limit;
  		// echo $BD_query;
  		// exit;
			$result_array= array();
				$exeute_query = $this->db->query($BD_query);
				if($exeute_query->num_rows()!=''){
					$result_array = $exeute_query->result_array();
				}
				// debug($result_array);
				// exit;
				return $result_array;
			}
	}

	public function get_branch_supplier($condition,$count=false,$offset=0, $limit=100000000000){

		if($count){
			$query = "select count(u.user_id) as total_records from user as u left join api_country_master as c ON c.origin=u.country_name 
left join user as su ON su.branch_id = u.user_id 
left join car_active_country_city_master as cs ON cs.origin = su.city
left join user as du on du.supplier_id = su.user_id
where 1=1 AND su.branch_id=u.user_id and cs.country_id = su.country_name and u.domain_list_fk=".get_domain_auth_id();
			// echo $query;
			$data = $this->db->query($query)->row_array();
			return $data['total_records'];
		
		}else{
			$query = "select u.user_id as b_d, u.agency_name as b_name,u.email as b_email,u.phone as b_phone,su.user_id as s_id,su.agency_name as s_name,cs.city_name as s_city_name,su.email as s_email,su.phone as s_phone,du.agency_name as d_name,du.email as d_email,du.phone as d_phone from user as u left join api_country_master as c ON c.origin=u.country_name 
left join user as su ON su.branch_id = u.user_id 
left join car_active_country_city_master as cs ON cs.origin = su.city
left join user as du on du.supplier_id = su.user_id
where 1=1 AND su.branch_id=u.user_id and cs.country_id = su.country_name and u.domain_list_fk=".get_domain_auth_id();
		// echo $query;
		$exeute_query = $this->db->query($query);
		$user_list= array();
		if($exeute_query->num_rows()!=''){
			$user_list = $exeute_query->result_array();
		}

		return $user_list;
		}
		
	}
	public function get_supplier_car_drivers($condition,$count=false,$offset=0, $limit=100000000000){

		$condition = '';
		if($this->entity_user_type==CAR_BRANCH_USER){
			$condition = "  branch_id=".$this->entity_user_id." AND  u.user_type=".CAR_SUPPLIER;
		}elseif($this->entity_user_type==ADMIN){

		}

		if($count){
			$query = "select count(user_id) as total_records from user as u left join car_active_country_city_master as c ON c.country_id = u.country_name left join api_country_master as a ON a.origin = c.country_id where  c.country_id = u.country_name AND c.origin = u.city AND ".$condition;
			$data = $this->db->query($query)->row_array();
			return $data['total_records'];
		}else{
			$query = "select *,a.country_name as s_country_name from user as u left join car_active_country_city_master as c ON c.country_id = u.country_name left join api_country_master as a ON a.origin = c.country_id where c.country_id = u.country_name AND c.origin = u.city AND ".$condition;
			$result_array= array();
				$exeute_query = $this->db->query($query);
				if($exeute_query->num_rows()!=''){
					$result_array = $exeute_query->result_array();
				}
				return $result_array;
		}
	}
		
	public function supplier_country($supplier_id){
		$data['status'] = false;
		$data['data'] = array();
		$query = "SELECT iso_country_code from user as u JOIN api_country_master as c ON u.country_name = c.origin WHERE u.user_id=".$supplier_id;
		$exeute_query= $this->db->query($query);
		if($exeute_query->num_rows()!=''){
			$data['status'] = true;
			$data['data'] = $exeute_query->result_array();	
		}
		return $data;
	}	
	function get_booking_details($app_reference, $booking_source, $booking_status='')
	{
		$response['status'] = FAILURE_STATUS;
		$response['data'] = array();
		$bd_query = 'select * from car_booking_details AS BD WHERE BD.app_reference like '.$this->db->escape($app_reference);
		if (empty($booking_source) == false) {
			$bd_query .= '	AND BD.booking_source = '.$this->db->escape($booking_source);
		}
		if (empty($booking_status) == false) {
			$bd_query .= ' AND BD.booking_status = '.$this->db->escape($booking_status);
		}
		
		$cancellation_details_query = 'select HCD.* from car_cancellation_details AS HCD WHERE HCD.app_reference='.$this->db->escape($app_reference);

		$response['data']['booking_details']			= $this->db->query($bd_query)->result_array();
	
		$response['data']['cancellation_details']	= $this->db->query($cancellation_details_query)->result_array();

		if (valid_array($response['data']['booking_details']) == true) {
			$response['status'] = SUCCESS_STATUS;
		}
		return $response;
	}
	function get_monthly_booking_summary($condition=array())
	{
		//Jaganath
		$condition = $this->custom_db->get_custom_condition($condition);
		// $query = 'select count(distinct(BD.app_reference)) AS total_booking, 
		// 		sum(BD.total_price+BD.admin_markup+BD.agent_markup) as monthly_payment, sum(BD.admin_markup) as monthly_earning, 
		// 		MONTH(BD.created_datetime) as month_number 
		// 		from car_booking_details AS BD
				
		// 		where (YEAR(BD.created_datetime) BETWEEN '.date('Y').' AND '.date('Y', strtotime('+1 year')).')  and BD.domain_origin='.get_domain_auth_id().' '.$condition.'
		// 		GROUP BY YEAR(BD.created_datetime), 
		// 		MONTH(BD.created_datetime)';
		$query = 'select count(distinct(BD.app_reference)) AS total_booking,  
				MONTH(BD.created_datetime) as month_number 
				from car_booking_details AS BD
				
				where (YEAR(BD.created_datetime) BETWEEN '.date('Y').' AND '.date('Y', strtotime('+1 year')).')  and BD.domain_origin='.get_domain_auth_id().' '.$condition.'
				GROUP BY YEAR(BD.created_datetime), 
				MONTH(BD.created_datetime)';
		
		return $this->db->query($query)->result_array();
	}

function b2c_car_report($condition=array(), $count=false, $offset=0, $limit=100000000000)
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
			$query = 'select count(distinct(BD.app_reference)) as total_records
					from car_booking_details BD
					join car_booking_trip AS HBID on BD.booking_reference=HBID.booking_reference
					join payment_option_list AS POL on BD.payment_mode=POL.payment_category_code
					left join user as U on BD.created_by_id = U.user_id 
					where (U.user_type='.B2C_USER.' OR BD.created_by_id = 0) AND BD.domain_origin='.get_domain_auth_id().' '.$condition;


			$data = $this->db->query($query)->row_array();
			return $data['total_records'];
		} else {
			$this->load->library('booking_data_formatter');
			$response['status'] = SUCCESS_STATUS;
			$response['data'] = array();
			$booking_itinerary_details	= array();
			$booking_customer_details	= array();
			$cancellation_details = array();
			
			$bd_query = 'select BD.* ,U.user_name,U.first_name as user_first_name,U.last_name as user_last_name from car_booking_details AS BD
					     left join user U on BD.created_by_id =U.user_id 					     
						 WHERE  (U.user_type='.B2C_USER.' OR BD.created_by_id = 0) AND BD.domain_origin='.get_domain_auth_id().''.$condition.'						 
						 order by BD.created_datetime desc, BD.origin desc limit '.$offset.', '.$limit.'';

			$booking_details = $this->db->query($bd_query)->result_array();
			$app_reference_ids = $this->booking_data_formatter->implode_app_reference_ids($booking_details);
			if(empty($app_reference_ids) == false) {
				
				$cancellation_details_query = 'select * from hotel_cancellation_details AS HCD 
							WHERE HCD.app_reference IN ('.$app_reference_ids.')';
				
				$cancellation_details	= $this->db->query($cancellation_details_query)->result_array();
			}
			$response['data']['booking_details']			= $booking_details;
			
			$response['data']['cancellation_details']	= $cancellation_details;
			return $response;
		}
	}

	function b2b_car_report($condition=array(), $count=false, $offset=0, $limit=100000000000)
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
			$query = 'select count(distinct(BD.app_reference)) as total_records
					from car_booking_details BD
					join car_booking_trip AS HBID on BD.booking_reference=HBID.booking_reference
					join payment_option_list AS POL on BD.payment_mode=POL.payment_category_code
					left join user as U on BD.created_by_id = U.user_id 
					where (U.user_type='.B2B_USER.' OR BD.created_by_id = 0) AND BD.domain_origin='.get_domain_auth_id().' '.$condition.'';

			$data = $this->db->query($query)->row_array();
			return $data['total_records'];
		} else {
			$this->load->library('booking_data_formatter');
			$response['status'] = SUCCESS_STATUS;
			$response['data'] = array();
			$booking_itinerary_details	= array();
			$booking_customer_details	= array();
			$cancellation_details = array();
			
			$bd_query = 'select BD.* ,U.user_name,U.first_name as user_first_name,U.last_name as user_last_name from car_booking_details AS BD
					     left join user U on BD.created_by_id =U.user_id 					     
						 WHERE  (U.user_type='.B2B_USER.' OR BD.created_by_id = 0) AND BD.domain_origin='.get_domain_auth_id().''.$condition.'						 
						 order by BD.created_datetime desc, BD.origin desc limit '.$offset.', '.$limit.'';

			$booking_details = $this->db->query($bd_query)->result_array();
			$app_reference_ids = $this->booking_data_formatter->implode_app_reference_ids($booking_details);
			if(empty($app_reference_ids) == false) {
				
				$cancellation_details_query = 'select * from hotel_cancellation_details AS HCD 
							WHERE HCD.app_reference IN ('.$app_reference_ids.')';
				
				$cancellation_details	= $this->db->query($cancellation_details_query)->result_array();
			}
			$response['data']['booking_details']			= $booking_details;
			
			$response['data']['cancellation_details']	= $cancellation_details;
			return $response;
		}
	}

function b2b_hotel_report($condition=array(), $count=false, $offset=0, $limit=100000000000)
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
			$query = 'select count(distinct(BD.app_reference)) as total_records 
					from hotel_booking_details BD
					join hotel_booking_itinerary_details AS HBID on BD.app_reference=HBID.app_reference
					join payment_option_list AS POL on BD.payment_mode=POL.payment_category_code 
					left join user as U on BD.created_by_id = U.user_id  where U.user_type='.B2B_USER.' AND BD.domain_origin='.get_domain_auth_id().' '.$condition;
			$data = $this->db->query($query)->row_array();
			return $data['total_records'];
		} else {
			$this->load->library('booking_data_formatter');
			$response['status'] = SUCCESS_STATUS;
			$response['data'] = array();
			$booking_itinerary_details	= array();
			$booking_customer_details	= array();
			$cancellation_details = array();
			$bd_query = 'select BD.* ,U.agency_name,U.first_name,U.last_name from hotel_booking_details AS BD
					     left join user U on BD.created_by_id =U.user_id 					     
						 WHERE  U.user_type='.B2B_USER.' AND BD.domain_origin='.get_domain_auth_id().' '.$condition.'
						 order by BD.created_datetime desc, BD.origin desc limit '.$offset.', '.$limit;
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
	public function update_cancellation_details($AppReference, $cancellation_details,$booking_attributes=array(),$cancel_items=array())
	{	

		$AppReference = trim($AppReference);
		$booking_status = 'BOOKING_CANCELLED';
		//1. Add Cancellation details
		$this->update_cancellation_refund_details($AppReference, $cancellation_details,$booking_attributes,$cancel_items);
		
	}
	public function update_cancellation_refund_details($AppReference, $cancellation_details,$booking_attributes=array(),$cancel_items=array())
	{
		
		$car_cancellation_details = array();
		$booking_details = json_decode($booking_attributes,true);
		$total_price = $cancel_items['total_price'];
		$cancell_charge= $total_price;
		$refund_amount = 0;
		$change_request = 2;
		if($booking_details['policy_type']){
			$current_date = date('Y-m-d');
			$pickup_date = date('Y-m-d',strtotime("-".$booking_details['cancellation_day']." day",strtotime($cancel_items['pickup_date'])));
			if($current_date>=$pickup_date){
				$change_request=2;
				$cancell_charge = $total_price;

			}else{
				$cancel_price = ($booking_details['cancel_percentage']/100)*$total_price;
				$refund_amount = abs($total_price-$cancel_price);

			}

		}else{
			$cancell_charge = $total_price;
			$change_request=3;
		}
		$status_description = 'Inprogress';
		if($change_request==2){
			$status_description = 'Inprogress';
		}elseif ($change_request==3) {
			$status_description = 'Processed';
		}
		$car_cancellation_details['app_reference'] = 				$AppReference;
		$car_cancellation_details['ChangeRequestStatus'] =$change_request;

		$car_cancellation_details['status_description'] =$status_description;

		$car_cancellation_details['API_RefundedAmount'] = $refund_amount;

		$car_cancellation_details['API_CancellationCharge'] = $cancell_charge;
		$car_cancellation_details['currency'] = $cancel_items['currency'];
		if($car_cancellation_details['ChangeRequestStatus'] == 3){
			$car_cancellation_details['cancellation_processed_on'] =date('Y-m-d H:i:s');
		}
		$cancel_details_exists = $this->custom_db->single_table_records('car_cancellation_details', '*', array('app_reference' => $AppReference));
		if($cancel_details_exists['status'] == true) {
			//Update the Data
			unset($car_cancellation_details['app_reference']);
			$this->custom_db->update_record('car_cancellation_details', $car_cancellation_details, array('app_reference' => $AppReference));
		} else {
			//Insert Data
			$car_cancellation_details['created_by_id'] = 				(int)@$this->entity_user_id;
			$car_cancellation_details['created_datetime'] = 			date('Y-m-d H:i:s');
			$data['cancellation_requested_on'] = date('Y-m-d H:i:s');
			$this->custom_db->insert_record('car_cancellation_details',$car_cancellation_details);
			
		}
	}
	public function cancel_car_booking($request){
		// debug($request);die;
		$response['status'] = false;
		$response['data']  = array();
		if($request['booking_status']=='BOOKING_CONFIRMED'){
			$condition = array();
			$condition['booking_reference'] = $request['booking_reference'];
			$condition['car_id'] = $request['car_id'];
			$condition['pickup_date'] = $request['trip_start_date'];

			$check_if_booking_exists = $this->custom_db->single_table_records('car_booking_trip','*',$condition);
			if($check_if_booking_exists['status']==1){
				$update_data = array();
				$update_data['booking_status'] ='BOOKING_CANCELLED';
				if($this->custom_db->update_record('car_booking_trip',$update_data,$condition)){

					$b_condition = array();

					$booking_details['app_reference'] = $request['app_reference'];
					if($this->custom_db->update_record('car_booking_details',$update_data,$booking_details)){
						$response['data']['status'] = "BOOKING_CANCELLED";
						$response['data']['booking_reference'] = $request['booking_reference'];
						$response['status'] = true;
					}else{
						$response['data']['error'] = "Booking table not updated";
					}
				} 

			}else{
				$response['data']['error'] = "No Records Found";
			}
			
		}
		return $response;
	}

	public function check_duplicate($email,$table_name){
       
       $this->db->select('*');
       $this->db->from('user');
	   $this->db->where('email', $email );
	   $query = $this->db->get();
       if ( $query->num_rows() > 0 )
       {
         return TRUE;
       }else{
       	return FLASE;
       }
      
	}
}
