<?php
require_once 'abstract_management_model.php';
/**
 *
 * @package Provab Application
 * @subpackage Travel Portal
 * @author Arjun J<arjunjgowda260389@gmail.com>
 * @version V2
 */
class Supplier_management_model extends Abstract_Management_Model {
	function __construct() {
		parent::__construct ( 'level_2' );
	}

	function supplier_report($condition = array(), $count = false, $offset = 0, $limit = 100000000000)
	{
		$response ['status'] = SUCCESS_STATUS;
		$response ['data'] = array ();	
		$condition = $this->custom_db->get_custom_condition ( $condition );

		if ($count) 
		{
			$query = 'select COUNT(*) AS total_records from tour_booking_details AS BD LEFT JOIN user as U on U.user_id=BD.created_by_id WHERE supplier_id !="" ' . $condition;
			
			$data = $this->db->query ( $query )->row_array ();

			return $data ['total_records'];
		}
		else 
		{
			
			$bd_query = 'select BD.*,U.uuid,U.email,U.first_name,U.last_name from tour_booking_details AS BD LEFT JOIN user as U on U.user_id=BD.created_by_id  WHERE BD.supplier_id !="" ' . $condition . ' order by BD.origin desc limit ' . $limit;
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
					$tour_id = $attributes['tour_id_book'];
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
			$response ['data'] = $result;
			return $response;
		}
	}
	function supplier_payable_details($condition = array(), $count = false, $offset = 0, $limit = 100000000000)
	{
		$response ['status'] = SUCCESS_STATUS;
		$response ['data'] = array ();	
		$condition = $this->custom_db->get_custom_condition ( $condition );

		$query = 'select SUM(supplier_price) AS total_payable,SUM(admin_markup) as total_earning from tour_booking_details AS BD LEFT JOIN user as U on U.user_id=BD.created_by_id WHERE supplier_id !="" ' . $condition;
			
		$data = $this->db->query ( $query )->row_array ();
		return $data;
	}
	function supplier_payment_details($condition = array()) 
	{
		$response ['status'] = SUCCESS_STATUS;
		$response ['data'] = array ();	

		$condition = $this->custom_db->get_custom_condition ( $condition );

		$query = 'select BD.*,U.uuid,U.email,U.first_name,U.last_name from supplier_payment_details AS BD LEFT JOIN user as U on U.user_id=BD.supplier_id WHERE 1=1 ' . $condition;
			
		$data = $this->db->query ( $query )->result_array ();

		return $data;

	}
	function supplier_amount_payment_status($data)
	{
		
		if((!empty($data['month'])) && (!empty($data['year'])))
		{
			if($data['month'] !="All" && $data['year'] !="All")
			{
				$this->db->where('supplier_id',$this->entity_user_id);
				$this->db->where('payment_for_month',$data['month']);
				$this->db->where('payment_for_year',$data['year']);
				$get=$this->db->get('supplier_payment_details');
				
				if($get->num_rows() >=1)
				{
					$response ['status'] = SUCCESS_STATUS;
					$response ['Message'] ="Payment Done";
					$response ['data'] = $get ->result_array();
				}
				else
				{
					$response ['status'] = "0";
					$response ['Message'] ="Payment Not Done";
				}
				return $response;
			}
		}
		else
		{
			return "";
		}
	}
}