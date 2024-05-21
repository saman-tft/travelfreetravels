<?php
class Loyalty_program_model extends CI_Model {
	public function __construct(){
		parent::__construct();
		error_reporting(E_ALL);
	}
	
	
	public function get_reward_all_list($condition){
		// echo "dd";exit;
		$condition = $this->custom_db->get_custom_condition($condition);

		$query="select reward_point_total.*,user.email,user.agency_name,user.phone from reward_point_total LEFT JOIN user ON reward_point_total.agent_id = user.user_id 
		where 1=1 $condition";
		// echo $query;exit;
		$query=$this->db->query($query);
		$result = $query->result_array();
		// debug($result);exit;
		return $result;
	}
	public function get_hold_transation($condition=array(), $count=false, $offset=0, $limit=10000000000, $order_by=array()){
		// echo "dd";exit;
		$condition = $this->custom_db->get_custom_condition($condition);

		$query="select reward_point_transaction.*,user.email,user.agency_name,user.phone from reward_point_transaction LEFT JOIN user ON reward_point_transaction.agent_id = user.user_id 
		where reward_point_transaction.status=1 $condition limit $limit offset $offset";
		// echo $query;exit;
		$query=$this->db->query($query);
		$result = $query->result_array();
		// debug($result);exit;
		return $result;
	}
	public function get_all_currency_list($condition=array(), $count=false, $offset=0, $limit=10000000000, $order_by=array()){
		// echo "dd";exit;
		$condition = $this->custom_db->get_custom_condition($condition);

		$query="select loyalty_currency_rate.*,country_list.country_name from loyalty_currency_rate left join country_list on loyalty_currency_rate.country=country_list.currency_code
		where 1=1 $condition limit $limit offset $offset";
		// echo $query;exit;
		$query=$this->db->query($query);
		$result = $query->result_array();
		// debug($result);exit;
		return $result;
	}
	public function get_all_currency_list_cnt($condition){
		$condition = $this->custom_db->get_custom_condition($condition);

		$query="select count(*) as cnt from loyalty_currency_rate left join country_list on loyalty_currency_rate.country=country_list.currency_code where 1=1 $condition";
		$query=$this->db->query($query);
		$result = $query->row_array();
		return $result['cnt'];
	}
	public function get_all_currency_country_list(){
		

		$query="select * from country_list";
		
		$query=$this->db->query($query);
		$result = $query->result_array();
		// debug($result);exit;
		return $result;
	}
	public function get_currency_details($id){
		$query="select * from currency_converter where country='$id'";
		
		$query=$this->db->query($query);
		$result = $query->row_array();
		// debug($result);exit;
		return $result;
	}
	public function check_currency_details($id){
		$query="select * from loyalty_currency_rate where country='$id'";
		
		$query=$this->db->query($query);
		if($query->num_rows() >0){
			return FALSE;
		}
		else{
			return TRUE;
		}
	}
	public function get_all_recharge_request_pending($condition=array(), $count=false, $offset=0, $limit=10000000000, $order_by=array()){
		// echo "dd";exit;
		// $condition = $this->custom_db->get_custom_condition($condition);

		$query="select loyalty_redeem_request.*,user.email,user.agency_name,user.phone from loyalty_redeem_request 
		LEFT JOIN user ON loyalty_redeem_request.agent_id = user.user_id
		where loyalty_redeem_request.status=0 order by loyalty_redeem_request.id desc limit $limit offset $offset";
		// echo $query;exit;
		$query=$this->db->query($query);
		$result = $query->result_array();
		$responce=array();
		foreach ($result as $key => $value) {
			$proid=json_decode($value['product_id']);
			$pid=implode(",",$proid);
			$pid1= str_replace(',',"','", $pid);
			$pid2="'".$pid1."'";

				$query="select GROUP_CONCAT(name) as name from loyalty_product where id IN($pid2)";
		// echo $query;exit;
			$name="";
		$query=$this->db->query($query);
		$result1 = $query->row_array();
		$value['proname']=$result1;
		$responce[]=array('redemelist'=>$value);



		}
		// debug($result);exit;
		return $responce;
	}
	public function get_all_recharge_approved_pending($condition=array(), $count=false, $offset=0, $limit=10000000000, $order_by=array()){
		// echo "dd";exit;
		// $condition = $this->custom_db->get_custom_condition($condition);

		$query="select loyalty_redeem_request.*,user.email,user.agency_name,user.phone from loyalty_redeem_request 
		LEFT JOIN user ON loyalty_redeem_request.agent_id = user.user_id
		where loyalty_redeem_request.status=1 order by loyalty_redeem_request.id desc limit $limit offset $offset";
		// echo $query;exit;
		$query=$this->db->query($query);
		$result = $query->result_array();
		$responce=array();
		foreach ($result as $key => $value) {
			$proid=json_decode($value['product_id']);
			$pid=implode(",",$proid);
			$pid1= str_replace(',',"','", $pid);
			$pid2="'".$pid1."'";

				$query="select GROUP_CONCAT(name) as name from loyalty_product where id IN($pid2)";
		// echo $query;exit;
			$name="";
		$query=$this->db->query($query);
		$result1 = $query->row_array();
		$value['proname']=$result1;
		$responce[]=array('redemelist'=>$value);



		}
		// debug($result);exit;
		return $responce;
	}
	public function get_product_list(){
		// echo "dd";exit;
		//$condition = $this->custom_db->get_custom_condition($condition);

		$query="select * from loyalty_product";
		// echo $query;exit;
		$query=$this->db->query($query);
		$result = $query->result_array();
		// debug($result);exit;
		return $result;
	}
	public function get_module_range($id){
		// echo "dd";exit;
		//$condition = $this->custom_db->get_custom_condition($condition);

		$query="select * from loyalty_master_module_range_reward where module_id='$id'";
		// echo $query;exit;
		$query=$this->db->query($query);
		if($query->num_rows() >0){
		$result = $query->result_array();
		// debug($result);exit;
		return $result;
		}
		else{
			return FALSE;
		}
	}
	public function get_transaction_details($id){
		// echo "dd";exit;
		//$condition = $this->custom_db->get_custom_condition($condition);

		$query="select * from reward_point_transaction where id='$id'";
		// echo $query;exit;
		$query=$this->db->query($query);
		if($query->num_rows() >0){
		$result = $query->row_array();
		// debug($result);exit;
		return $result;
		}
		else{
			return FALSE;
		}
	}
	public function get_transaction_all($id){
		// echo $id;exit;
		$id=str_replace(",","','", $id);
		$id="'".$id."'";
		// echo "dd";exit;
		//$condition = $this->custom_db->get_custom_condition($condition);

		$query="select * from reward_point_transaction where id IN($id)";
		// echo $query;exit;
		$query=$this->db->query($query);
		if($query->num_rows() >0){
			$result = $query->result_array();
			// debug($result);exit;
			return $result;
		}
		else
		{
			return FALSE;
		}
	}
	public function cron_get_transaction_all(){
		// echo $id;exit;
		
		// echo "dd";exit;
		//$condition = $this->custom_db->get_custom_condition($condition);
		$dat=date('Y-m-d');

		$query="select * from reward_point_transaction where expire_date <'".$dat."' and status=0";
		// echo $query;exit;
		$query=$this->db->query($query);
		
		if($query->num_rows() >0){
			$result = $query->result_array();
			// debug($result);exit;
			return $result;
		}
		else
		{
			return FALSE;
		}
	}
	public function get_reward_point_total($agent_id){
		// echo $id;exit;
		
		// echo "dd";exit;
		//$condition = $this->custom_db->get_custom_condition($condition);
		$dat=date('Y-m-d');
		
		$query="select * from reward_point_total where agent_id ='".$agent_id."'";
		// echo $query;exit;
		$query=$this->db->query($query);
		
		if($query->num_rows() >0){
			$result = $query->row_array();
			// debug($result);exit;
			return $result;
		}
		else
		{
			return FALSE;
		}
	}
	public function save_range_point($post_data){
			$data=array('module_id'=>$post_data['module_id'],'start_range'=>$post_data['start_range'],'end_range'=>$post_data['end_range'],'point'=>$post_data['point']);
			$this->db->insert('loyalty_master_module_range_reward',$data);
	}
	public function check_range_point($post_data){


			$query="select * from loyalty_master_module_range_reward where module_id=".$post_data['module_id']." and start_range =".$post_data['start_range']." and end_range =".$post_data['end_range']."";
		// echo $query;exit;
		$query=$this->db->query($query);
		if($query->num_rows() >0){
		$result = $query->row_array();
		// debug($result);exit;
		return FALSE;
		}
		else{
			return TRUE;
		}

			$data=array('module_id'=>$post_data['module_id'],'start_range'=>$post_data['start_range'],'end_range'=>$post_data['end_range'],'point'=>$post_data['point']);
			$this->db->insert('loyalty_master_module_range_reward',$data);
	}
	public function get_edit_product($id){
		// echo "dd";exit;
		//$condition = $this->custom_db->get_custom_condition($condition);

		$query="select * from loyalty_product where id='$id'";
		// echo $query;exit;
		$query=$this->db->query($query);
		if($query->num_rows() >0){
		$result = $query->row_array();
		// debug($result);exit;
		return $result;
		}
		else{
			return FALSE;
		}
	}
	public function get_product_list_cnt(){
		// echo "dd";exit;
		//$condition = $this->custom_db->get_custom_condition($condition);

		$query="select count(*) as cnt from loyalty_product";
		// echo $query;exit;
		$query=$this->db->query($query);
		$result = $query->row_array();
		// debug($result);exit;
		return $result['cnt'];
	}
	public function get_all_curency(){
		

		$query="select * from currency_converter";
		
		$query=$this->db->query($query);
		$result = $query->result_array();
		
		return $result;
	}
	public function check_name($get_data)
	{
		if($get_data['id'] !="")
		{
			$query="select name from loyalty_product where name='".$get_data['name']."' and id !=".$get_data['id']."";
			// echo $query;exit;
			$query=$this->db->query($query);
			if($query->num_rows() >0){

				return TRUE;
			}
			else
			{
				return FALSE;
			}
		}
		else
		{
			$query="select name from loyalty_product where name='".$get_data['name']."'";
			// echo $query;exit;
			$query=$this->db->query($query);
			if($query->num_rows() >0){

				return TRUE;
			}
			else
			{
				return FALSE;
			}
		}
	}
	public function update_product($get_data){
		// debug($get_data);exit;
		$cn="";
		if($get_data['country'] !=""){
			$cn=implode(",",$get_data['country']);

		}
		$data=array('name'=>$get_data['name'],'point'=>$get_data['point'],'description'=>$get_data['description'],'country'=>$cn,'type'=>$get_data['type']);
		if($get_data['image'] !=""){
			$data['image']=$get_data['image'];
		}
		if($get_data['id'] !="")
		{
			
			$this->db->where('id',$get_data['id']);
			$this->db->update('loyalty_product',$data);
		}
		else
		{
			
			
			$this->db->insert('loyalty_product',$data);
		}
	}
	public function get_total_reward_all_list(){
		// echo "dd";exit;
		// $condition = $this->custom_db->get_custom_condition($condition);

		$query="select sum(hotel) as thotel,sum(transfer) as ttransfer,sum(holidays) as holidays,sum(activities) as tactivities,sum(visa) as tvisa from reward_point_total";
		// echo $query;exit;
		$query=$this->db->query($query);
		if($query->num_rows() >0){

			$result = $query->row_array();
			return $result;
		}
		else
		{
			return FALSE;
		}
		// debug($result);exit;
		
	}
	public function get_reward_all_list_cnt($condition){
		$condition = $this->custom_db->get_custom_condition($condition);

		$query="select count(reward_point_total.agent_id) as cnt from reward_point_total LEFT JOIN user ON reward_point_total.agent_id = user.user_id  where 1=1 $condition";
		$query=$this->db->query($query);
		$result = $query->row_array();
		return $result['cnt'];
	}
	public function check_agent_loyalty_program($agent_id)
	{
		$query="select loyalty_program from user where user_id='$agent_id'";
		$query=$this->db->query($query);
		if($query->num_rows() >0)
		{
			$result = $query->row_array();

			return $result['loyalty_program'];
		}
		else
		{
			return FALSE;
		}
		
	}
	public function get_master_module_status($type)
	{
		$query="select status,id,defult_value from module_status where module_name='$type'";
		$query=$this->db->query($query);
		$result = $query->row_array();
		return $result;
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
	public function get_hold_transation_cnt($condition){
		$condition = $this->custom_db->get_custom_condition($condition);

		$query="select count(*) as cnt from reward_point_transaction LEFT JOIN user ON reward_point_transaction.agent_id = user.user_id 
		where reward_point_transaction.status=1 $condition";
		$query=$this->db->query($query);
		$result = $query->row_array();
		return $result['cnt'];
	}
	public function get_all_recharge_request_pending_cnt(){
		// $condition = $this->custom_db->get_custom_condition($condition);

		$query="select count(*) as cnt from loyalty_redeem_request where status=0";
		$query=$this->db->query($query);
		$result = $query->row_array();
		return $result['cnt'];
	}
	public function get_all_recharge_request_approved_cnt(){
		// $condition = $this->custom_db->get_custom_condition($condition);

		$query="select count(*) as cnt from loyalty_redeem_request where status=1";
		$query=$this->db->query($query);
		$result = $query->row_array();
		return $result['cnt'];
	}
	public function get_redeem_details($id){
			$query="select * from loyalty_redeem_request where id='$id'";
		$query=$this->db->query($query);
		$result = $query->row_array();
		return $result;
	}
	public function get_agent_currency($id){
		$query="select country,rate from loyalty_currency_rate left join b2b_user_details on loyalty_currency_rate.currency_id=b2b_user_details.currency_converter_fk where b2b_user_details.user_oid='$id'";
		$query=$this->db->query($query);
		$result = $query->row_array();
		return $result;
	}
	public function check_hotel_booking_status($id){
		$query="select status from hotel_booking_details where app_reference='$id'";
		$query=$this->db->query($query);


		if($query->num_rows() >0)
		{

			$result = $query->row_array();
			if($result['status']=='BOOKING_CONFIRMED')
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
			$query="select status from ch_hotel_booking_details where reservation_id='$id'";
			$query=$this->db->query($query);
			if($query->num_rows() >0)
			{
				$result = $query->row_array();
				if($result['status']=='BOOKING_CONFIRMED')
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


		
		
		
	}
	public function check_transfer_booking_status($id){
		// debug($id);exit;
		$query="select status from transfer_booking_details where app_reference='$id'";
		$query=$this->db->query($query);
		
		// debug($result);exit;
		if($query->num_rows() >0)
		{
			$result = $query->row_array();
			if($result['status']=='BOOKING_CONFIRMED'){
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
	public function check_holiday_booking_status($id){
		$query="select status from tour_booking_details where app_reference='$id'";
		$query=$this->db->query($query);
		if($query->num_rows() >0)
		{
			$result = $query->row_array();
			if($result['status']=='BOOKING_CONFIRMED')
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
	public function check_activities_booking_status($id){
		$query="select status from activity_booking_details where app_reference='$id'";
		$query=$this->db->query($query);


		if($query->num_rows() >0)
		{

			$result = $query->row_array();
			if($result['status']=='BOOKING_CONFIRMED')
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
			$query="select status from sightseeing_booking_details where app_reference='$id'";
			$query=$this->db->query($query);
			if($query->num_rows() >0)
			{
				$result = $query->row_array();
				if($result['status']=='BOOKING_CONFIRMED')
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
		
	}
		


}
