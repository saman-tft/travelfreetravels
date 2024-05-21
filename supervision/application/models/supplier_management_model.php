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
	function supplier_price_report($condition = array(), $count = false, $offset = 0, $limit = 100000000000)
	{
		$response ['status'] = SUCCESS_STATUS;
		$response ['data'] = array ();	

		$condition = $this->custom_db->get_custom_condition ( $condition );

		if ($count) 
		{
			$query = 'select COUNT(*) AS total_records from tour_booking_details AS BD LEFT JOIN user as U on U.user_id=BD.created_by_id WHERE BD.supplier_id !="" ' . $condition;
			$data = $this->db->query ( $query )->row_array ();
			return $data ['total_records'];
		}
		else 
		{
			$bd_query = 'select BD.supplier_id,sum(BD.basic_fare) as total_supplier_price,sum(BD.markup) as total_admin_markup,U.uuid,U.email,U.first_name,U.last_name from tour_booking_details AS BD LEFT JOIN user as U on U.user_id=BD.supplier_id  WHERE BD.supplier_id !="" ' . $condition . ' group by BD.supplier_id order by BD.origin desc ';
	
			$response ['data'] = $this->db->query ( $bd_query )->result_array ();

			return $response;
		}
	}
	function hotel_supplier_price_report($condition = array(), $count = false, $offset = 0, $limit = 100000000000)
	{
		$response ['status'] = SUCCESS_STATUS;
		$response ['data'] = array ();	

		$condition = $this->custom_db->get_custom_condition ( $condition );

		if ($count) 
		{
			$query = 'select COUNT(*) AS total_records from hotel_booking_details AS BH LEFT JOIN user as U on U.user_id=BH.created_by_id WHERE BH.supplier_id !="" ' . $condition;
		
			$data = $this->db->query ( $query )->row_array ();
			return $data ['total_records'];
		}
		else 
		{
			$bd_query = 'select BH.supplier_id,sum(BD.total_fare) as total_supplier_price,sum(BD.admin_markup) as total_admin_markup,sum(BD.agent_markup) as total_agent_markup,U.uuid,U.email,U.first_name,U.last_name from hotel_booking_itinerary_details AS BD LEFT JOIN hotel_booking_details as BH on BH.app_reference=BD.app_reference  LEFT JOIN  user as U on U.user_id=BH.supplier_id  WHERE BH.supplier_id !="" ' . $condition . ' group by BH.supplier_id order by BH.origin';
		
			$response ['data'] = $this->db->query ( $bd_query )->result_array ();

			return $response;
		}
	}
		function supplier_holidaycrs_report($condition=array(), $count=false, $offset=0, $limit=100000000000)
	{

		$condition = $this->custom_db->get_custom_condition($condition);
		//$b2c_condition_array = array('U.user_type', '=', B2C_USER, ' OR ', 'BD.created_by_id', '=', 0);
		
		//BT, CD, ID


		 
		 // debug($condition);exit();

		/*if(isset($condition) == true)
		{
			$offset = 0;
		}else{
			
			$offset = $offset;
		}*/


		if ($count) {


  
			$query = 'select count(distinct(BD.app_reference)) AS total_records from activity_booking_details BD
					left join user U on U.user_id = BD.created_by_id
					left join user_type UT on UT.origin = U.user_type
					join activity_booking_transaction_details as BT on BD.app_reference = BT.app_reference	
					where  BD.domain_origin='.get_domain_auth_id().''.$condition;
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
			
			
			$bd_query = 'select BD.* ,sum(BD.basic_fare) as total_supplier_price,U.user_name,U.first_name,U.last_name,U.user_type,U.email,U.agent_staff,U.agent_staff_id,U.uuid,U.agency_name from activity_booking_details AS BD
					     right join user U on U.user_id = BD.created_by_id
					     right join user_type UT on UT.origin = U.user_type
					     right join activity_booking_transaction_details as BT on BD.app_reference = BT.app_reference		
						 WHERE   BD.domain_origin='.get_domain_auth_id().' '.$condition.'
						 order by  BD.origin desc';		

					//echo $bd_query;
					
						 
			$response ['data1'] 	= $this->db->query($bd_query)->result_array();
			$response ['data']=array();
		for($i=0;$i<count($response ['data1']);$i++)
		{
		    if($response ['data1'][$i]['app_reference']!="")
		    {
		        
		        array_push(	$response ['data'],$response['data1'][$i]);
		    }
		}
		
			return $response;
		}
	}
	function activities_supplier_payable_details($condition = array(), $count = false, $offset = 0, $limit = 100000000000)
	{
		$response ['status'] = SUCCESS_STATUS;
		$response ['data'] = array ();	
		$condition = $this->custom_db->get_custom_condition ( $condition );
       	$query = 'select BD.* ,sum(BD.basic_fare) as total_payable,U.user_name,U.first_name,U.last_name,U.user_type,U.email,U.agent_staff,U.agent_staff_id,U.uuid,U.agency_name from activity_booking_details AS BD
					     left join user U on U.user_id = BD.created_by_id
					     left join user_type UT on UT.origin = U.user_type
					     join activity_booking_transaction_details as BT on BD.app_reference = BT.app_reference		
						 WHERE   BD.domain_origin='.get_domain_auth_id().' '.$condition;
		//$query = 'select SUM(BD.basic_fare) AS total_payable,SUM(BD.markup) as total_earning from tour_booking_details AS BD LEFT JOIN user as U on U.user_id=BD.created_by_id WHERE BD.supplier_id !="" ' . $condition;
	//	echo $query ;die;
		$data = $this->db->query ( $query )->row_array ();
		return $data;
	}
	function transfer_supplier_price_report($condition = array(), $count = false, $offset = 0, $limit = 100000000000)
	{
		$response ['status'] = SUCCESS_STATUS;
		$response ['data'] = array ();	

		$condition = $this->custom_db->get_custom_condition ( $condition );

		if ($count) 
		{
			$query = 'select COUNT(*) AS total_records from transfer_booking_details AS BH LEFT JOIN user as U on U.user_id=BH.created_by_id WHERE BH.supplier_id !="" ' . $condition;
			$data = $this->db->query ( $query )->row_array ();
			return $data ['total_records'];
		}
		else 
		{
			$bd_query = 'select BH.supplier_id,sum(BH.basic_fare) as total_supplier_price,U.uuid,U.email,U.first_name,U.last_name from transfer_booking_transaction_details AS BD LEFT JOIN transfer_booking_details as BH on BH.app_reference=BD.app_reference  LEFT JOIN  user as U on U.user_id=BH.supplier_id  WHERE BH.supplier_id !="" ' . $condition . ' group by BH.supplier_id order by BH.origin desc ';
			
			$response ['data'] = $this->db->query ( $bd_query )->result_array ();

			return $response;
		}
	}
	function supplier_report($condition = array(), $count = false, $offset = 0, $limit = 100000000000)
	{
		$response ['status'] = SUCCESS_STATUS;
		$response ['data'] = array ();	
		$condition = $this->custom_db->get_custom_condition ( $condition );

		if ($count) 
		{
			$query = 'select COUNT(*) AS total_records from tour_booking_details AS BD LEFT JOIN user as U on U.user_id=BD.created_by_id WHERE BD.supplier_id !="" ' . $condition;
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
//debug();die;
				$id_query = 'select TI.*,T.package_name from tours_enquiry AS TI LEFT JOIN tours AS T ON TI.tour_id=T.id WHERE TI.enquiry_reference_no="'.$value['enquiry_reference_no'].'" order by TI.id desc ';
				$enquiry_details = $this->db->query ( $id_query )->result_array ();
				$result[$app_reference]['enquiry_details'] = $enquiry_details[0];

				$tour_id = $enquiry_details[0]['tour_id'];
				
				if(count($enquiry_details)<1){
					$tour_id = $attributes['tour_id_book'];
				}
				
				$td_query = 'select T.*,TC.name AS country_name from tours AS T LEFT JOIN tours_country AS TC ON TC.id=T.tours_country WHERE T.id="'.$value['tours_id'].'" ';
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
		//	debug($response);die;
			return $response;
		}
	}
	function supplier_payable_details($condition = array(), $count = false, $offset = 0, $limit = 100000000000)
	{
		$response ['status'] = SUCCESS_STATUS;
		$response ['data'] = array ();	
		$condition = $this->custom_db->get_custom_condition ( $condition );

		$query = 'select SUM(BD.basic_fare) AS total_payable,SUM(BD.markup) as total_earning from tour_booking_details AS BD LEFT JOIN user as U on U.user_id=BD.created_by_id WHERE BD.supplier_id !="" ' . $condition;
	//	echo $query ;die;
		$data = $this->db->query ( $query )->row_array ();
		return $data;
	}
    function transfer_supplier_payable_details($condition = array(), $count = false, $offset = 0, $limit = 100000000000)
	{
		$response ['status'] = SUCCESS_STATUS;
		$response ['data'] = array ();	
		$condition = $this->custom_db->get_custom_condition ( $condition );
       	$query = 'select BH.supplier_id,sum(BH.basic_fare) as total_payable,U.uuid,U.email,U.first_name,U.last_name from transfer_booking_transaction_details AS BD LEFT JOIN transfer_booking_details as BH on BH.app_reference=BD.app_reference  LEFT JOIN  user as U on U.user_id=BH.supplier_id  WHERE BH.supplier_id !="" ' . $condition;
		//$query = 'select SUM(BD.basic_fare) AS total_payable,SUM(BD.markup) as total_earning from tour_booking_details AS BD LEFT JOIN user as U on U.user_id=BD.created_by_id WHERE BD.supplier_id !="" ' . $condition;
	//	echo $query ;die;
		$data = $this->db->query ( $query )->row_array ();
		return $data;
	}
	function hotel_supplier_payable_details($condition = array(), $count = false, $offset = 0, $limit = 100000000000)
	{
		$response ['status'] = SUCCESS_STATUS;
		$response ['data'] = array ();	
		$condition = $this->custom_db->get_custom_condition ( $condition );
        $query ='select BH.supplier_id,sum(BD.total_fare) as total_supplier_price,sum(BD.admin_markup) as total_admin_markup,sum(BD.agent_markup) as total_agent_markup,U.uuid,U.email,U.first_name,U.last_name from hotel_booking_itinerary_details AS BD LEFT JOIN hotel_booking_details as BH on BH.app_reference=BD.app_reference  LEFT JOIN  user as U on U.user_id=BH.supplier_id  WHERE BH.supplier_id !="" ' . $condition;
		//$query = 'select SUM(BD.basic_fare) AS total_payable,SUM(BD.markup) as total_earning from tour_booking_details AS BD LEFT JOIN user as U on U.user_id=BD.created_by_id WHERE BD.supplier_id !="" ' . $condition;
	//	echo $query ;die;
		$data = $this->db->query ( $query )->row_array ();
		return $data;
	}
	function supplier_amount_payment_status($data)
	{
		
		if((!empty($data['supplier_name'])) && (!empty($data['month'])) && (!empty($data['year'])))
		{
			if($data['supplier_name'] !="ALL" && $data['month'] !="All" && $data['year'] !="All")
			{
				$this->db->where('supplier_id',$data['supplier_name']);
				$this->db->where('payment_for_month',$data['month']);
				$this->db->where('payment_for_year',$data['year']);
					$this->db->where('module','Tour');
				$get=$this->db->get('supplier_payment_details');
				
				if($get->num_rows() >=1)
				{
					$response ['status'] = SUCCESS_STATUS;
					$response ['Message'] ="Paid";
					$response ['data'] = $get ->result_array();
				}
				else
				{
					$response ['status'] = "0";
					$response ['Message'] ="Not Paid";
				}
				return $response;
			}
			
		}
		else
		{
			return "";
		}
	}
	function transfer_supplier_amount_payment_status($data)
	{
		
		if((!empty($data['supplier_name'])) && (!empty($data['month'])) && (!empty($data['year'])))
		{
			if($data['supplier_name'] !="ALL" && $data['month'] !="All" && $data['year'] !="All")
			{
				$this->db->where('supplier_id',$data['supplier_name']);
				$this->db->where('payment_for_month',$data['month']);
				$this->db->where('payment_for_year',$data['year']);
					$this->db->where('module','Transfer');
				$get=$this->db->get('supplier_payment_details');
				
				if($get->num_rows() >=1)
				{
					$response ['status'] = SUCCESS_STATUS;
					$response ['Message'] ="Paid";
					$response ['data'] = $get ->result_array();
				}
				else
				{
					$response ['status'] = "0";
					$response ['Message'] ="Not Paid";
				}
				return $response;
			}
			
		}
		else
		{
			return "";
		}
	}
	function activities_supplier_amount_payment_status($data)
	{
		
		if((!empty($data['supplier_name'])) && (!empty($data['month'])) && (!empty($data['year'])))
		{
			if($data['supplier_name'] !="ALL" && $data['month'] !="All" && $data['year'] !="All")
			{
				$this->db->where('supplier_id',$data['supplier_name']);
				$this->db->where('payment_for_month',$data['month']);
				$this->db->where('module','activities');
				$this->db->where('payment_for_year',$data['year']);
				$get=$this->db->get('supplier_payment_details');
				
				if($get->num_rows() >=1)
				{
					$response ['status'] = SUCCESS_STATUS;
					$response ['Message'] ="Paid";
					$response ['data'] = $get ->result_array();
				}
				else
				{
					$response ['status'] = "0";
					$response ['Message'] ="Not Paid";
				}
				return $response;
			}
			
		}
		else
		{
			return "";
		}
	}
	function hotel_supplier_amount_payment_status($data)
	{
		
		if((!empty($data['supplier_name'])) && (!empty($data['month'])) && (!empty($data['year'])))
		{
			if($data['supplier_name'] !="ALL" && $data['month'] !="All" && $data['year'] !="All")
			{
				$this->db->where('supplier_id',$data['supplier_name']);
				$this->db->where('payment_for_month',$data['month']);
				$this->db->where('module','hotel');
				$this->db->where('payment_for_year',$data['year']);
				$get=$this->db->get('supplier_payment_details');
			
				if($get->num_rows() >=1)
				{
					$response ['status'] = SUCCESS_STATUS;
					$response ['Message'] ="Paid";
					$response ['data'] = $get ->result_array();
				}
				else
				{
					$response ['status'] = "0";
					$response ['Message'] ="Not Paid";
				}
				return $response;
			}
			
		}
		else
		{
			return "";
		}
	}
	function get_supplier_price($supplier_id,$paying_month,$paying_year)
	{
		$this->db->where('supplier_id',$supplier_id);
		$this->db->where('payment_for_month',$paying_month);
		$this->db->where('payment_for_year',$paying_year);
		$get=$this->db->get('supplier_payment_details');
		if($get->num_rows() == 0)
		{
		  
			$query = 'select SUM(BD.basic_fare) AS total_payable from tour_booking_details AS BD LEFT JOIN user as U on U.user_id=BD.created_by_id WHERE BD.supplier_id="'.$supplier_id.'" AND MONTH(BD.created_datetime)="'.$paying_month.'" AND YEAR(BD.created_datetime)="'.$paying_year.'"';
			$query_result=$this->db->query($query)->result_array();
			return $query_result;
			
		}
		else
		{
			return false;
		}
	}
	function get_transfer_supplier_price($supplier_id,$paying_month,$paying_year)
	{
		$this->db->where('supplier_id',$supplier_id);
		$this->db->where('payment_for_month',$paying_month);
		$this->db->where('payment_for_year',$paying_year);
		$get=$this->db->get('supplier_payment_details');
		if($get->num_rows() == 0)
		{
			$query = 'select BH.supplier_id,sum(BH.basic_fare) as total_payable,U.uuid,U.email,U.first_name,U.last_name from transfer_booking_transaction_details AS BD LEFT JOIN transfer_booking_details as BH on BH.app_reference=BD.app_reference  LEFT JOIN  user as U on U.user_id=BH.supplier_id WHERE BH.supplier_id="'.$supplier_id.'" AND MONTH(BH.created_datetime)="'.$paying_month.'" AND YEAR(BH.created_datetime)="'.$paying_year.'"';
			$query_result=$this->db->query($query)->result_array();
			return $query_result;
			
		}
		else
		{
			return false;
		}
	}
	function get_activity_supplier_price($supplier_id,$paying_month,$paying_year)
	{
		$this->db->where('supplier_id',$supplier_id);
		$this->db->where('payment_for_month',$paying_month);
		$this->db->where('payment_for_year',$paying_year);
		$get=$this->db->get('supplier_payment_details');
		if($get->num_rows() == 0)
		{
		    
			$query = 'select BD.* ,sum(BD.basic_fare) as total_payable,U.user_name,U.first_name,U.last_name,U.user_type,U.email,U.agent_staff,U.agent_staff_id,U.uuid,U.agency_name from activity_booking_details AS BD
					     left join user U on U.user_id = BD.created_by_id
					     left join user_type UT on UT.origin = U.user_type
					     join activity_booking_transaction_details as BT on BD.app_reference = BT.app_reference WHERE BD.supplier_id="'.$supplier_id.'" AND MONTH(BD.created_datetime)="'.$paying_month.'" AND YEAR(BD.created_datetime)="'.$paying_year.'"';
			$query_result=$this->db->query($query)->result_array();
			return $query_result;
			
		}
		else
		{
			return false;
		}
	}
	function get_hotel_supplier_price($supplier_id,$paying_month,$paying_year)
	{
		$this->db->where('supplier_id',$supplier_id);
		$this->db->where('payment_for_month',$paying_month);
		$this->db->where('payment_for_year',$paying_year);
		$get=$this->db->get('supplier_payment_details');
		if($get->num_rows() == 0)
		{
		    
			$query = 'select BH.supplier_id,sum(BD.total_fare) as total_payable,sum(BD.admin_markup) as total_admin_markup,sum(BD.agent_markup) as total_agent_markup,U.uuid,U.email,U.first_name,U.last_name from hotel_booking_itinerary_details AS BD LEFT JOIN hotel_booking_details as BH on BH.app_reference=BD.app_reference  LEFT JOIN  user as U on U.user_id=BH.supplier_id WHERE BH.supplier_id="'.$supplier_id.'" AND MONTH(BH.created_datetime)="'.$paying_month.'" AND YEAR(BH.created_datetime)="'.$paying_year.'"';
		
			$query_result=$this->db->query($query)->result_array();
			return $query_result;
			
		}
		else
		{
			return false;
		}
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
	function save_supplier_payment_details($details) 
	{
		if(!empty($details))
		{
		    
		    
			$supplier_id=$details['supplier_id'];
			$paying_month=$details['payment_for_month'];
			$paying_year=$details['payment_for_year'];
			$this->db->select('origin');
			$this->db->where('supplier_id',$supplier_id);
			$this->db->where('payment_for_month',$paying_month);
			$this->db->where('payment_for_year',$paying_year);
			$get=$this->db->get('supplier_payment_details');
			if($get->num_rows() == 0)
			{
				$insert_id = $this->custom_db->insert_record ( 'supplier_payment_details', $details );
				return  $this->db->insert_id();
			}
			else
			{
				return "";
			}
		}
	}
}