<?php
class Loyalty_program_model extends CI_Model {
	public function __construct(){
		parent::__construct();
	}
	
	
	public function get_reward_point($current_user_id){

		// $condition = $this->custom_db->get_custom_condition($condition);
		$query="select t_reward from reward_point_total where agent_id='$current_user_id'";
		$query=$this->db->query($query);
		$result = $query->row_array();
		return $result;
	}
	public function get_master_module_status($type)
	{
		$query="select status,id,defult_value from module_status where module_name='$type'";
		$query=$this->db->query($query);
		$result = $query->row_array();
		return $result;
	}
	public function get_master_range_point($id,$amt)
	{
		$query="SELECT * FROM loyalty_master_module_range_reward where module_id='$id' and end_range >='$amt' and start_range <='$amt'";
		$query=$this->db->query($query);
		if($query->num_rows() >0)
		{
			$result = $query->row_array();
			return $result['point'];
		}
		else
		{
			return FALSE;
		}
	}
	public function check_user_amount($agent_id){
		$query="SELECT due_amount FROM b2b_user_details where user_oid='$agent_id'";
		$query=$this->db->query($query);
		if($query->num_rows() >0)
		{
			$result = $query->row_array();
			if($result['due_amount'] >0)
			{
				return TRUE;
			}
			else
			{
				return FALSE;
			}
		}
		else
		{
			return FALSE;
		}
	}
	public function get_agent_module_status($type,$current_user_id)
	{
		
		$query="select status from user_loyalty_program where module_type='$type' and user_id='$current_user_id'";
		$query1=$this->db->query($query);
		if($query1->num_rows() >0){
			$result = $query1->row_array();
			return $result;
		}
		else
		{
			return FALSE;
		}
	}
	public function calculate_visa_amount($book_id){
		$query="select * from visa_data where ref_no='$book_id'";
		$amount=0;
		$query1=$this->db->query($query);
		if($query1->num_rows() >0){
			$result = $query1->result_array();
			foreach ($result as $key => $value) {
				$amt=$value['total_fare']-$value['agent_markup'];
				$amount +=$amt;
			}
			return $amount;
		}
		else
		{
			return $amount;
		}

	}
	public function get_agent_point($current_user_id)
	{
		$query="select t_reward,t_redeem,rhotel,rholiday,ractivities,rtransfer,rvisa,visa,hotel,transfer,holidays,activities from reward_point_total where agent_id='$current_user_id'";
		$query=$this->db->query($query);
		if($query->num_rows() >0){
			$result = $query->row_array();
			return $result;
		}
		else
		{
			return FALSE;
		}
	}
	public function insert_transation($data)
	{
		$this->db->insert('reward_point_transaction',$data);
	}
	public function get_agent_total_reward($current_user_id){
		$query="select * from reward_point_total where agent_id='$current_user_id'";
		$query=$this->db->query($query);
		if($query->num_rows() >0){
			$result = $query->row_array();
			return $result;
		}
		else
		{
			$dd=array('agent_id'=>$current_user_id,'t_reward'=>0,'t_redeem'=>0,'visa'=>0,'rvisa'=>0,'rholiday'=>0,'ractivities'=>0,'rhotel'=>0,'rtransfer'=>0,'hotel'=>0,'transfer'=>0,'holidays'=>0,'activities'=>0,'updated_date'=>date('Y-m-d'));
			$this->db->insert('reward_point_total',$dd);
			// echo $this->db->last_query();exit;
			return $dd;
		}
		
	}
	public function update_total_reward_point($current_user_id,$up_data){
		$this->db->where('agent_id',$current_user_id);
		$this->db->update('reward_point_total',$up_data);

	}
	public function module_conversion_value($type){
		$query="select * from module_status where module_name='$type'";
		$query=$this->db->query($query);
		if($query->num_rows() >0){
			$result = $query->row_array();
			return $result['conversion_value'];
		}
		else
		{
			return FALSE;
		}
	}
	public function get_hotel_checkout_date($booking_id){
		$query="select hotel_check_out from hotel_booking_details where app_reference='$booking_id'";
		$query=$this->db->query($query);
		if($query->num_rows() >0){
			$result = $query->row_array();
			return $result['hotel_check_out'];
		}
		else
		{
			return date('Y-m-d');
		}
	}
	public function get_activity_checkout_date($booking_id){
		$query="select travel_date from sightseeing_booking_details where app_reference='$booking_id'";
		$query=$this->db->query($query);
		if($query->num_rows() >0){
			$result = $query->row_array();
			return $result['travel_date'];
		}
		else
		{
			return date('Y-m-d');
		}
	}
	public function get_holiday_checkout_date($booking_id){
		$query="select departure_date from tour_booking_details where app_reference='$booking_id'";
		$query=$this->db->query($query);
		if($query->num_rows() >0){
			$result = $query->row_array();
			return $result['departure_date'];
		}
		else
		{
			return date('Y-m-d');
		}
	}
	public function get_product_list($amount,$contry_code){
		$query="select * from loyalty_product where point <='$amount' and (country='' or country LIKE '%$contry_code%')";
		$query=$this->db->query($query);
		if($query->num_rows() >0)
		{
			$result = $query->result_array();
			return $result;
		}
		else
		{
			return FALSE;
		}
	}
	public function get_product_details($id){
		$query="select * from loyalty_product where id='$id'";
		$query=$this->db->query($query);
		if($query->num_rows() >0)
		{
			$result = $query->row_array();
			return $result;
		}
		else
		{
			return FALSE;
		}
	}





}
