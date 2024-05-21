<?php
require_once 'abstract_management_model.php';
/**
 * @package    Provab Application
 * @subpackage Travel Portal
 * @author     Nikhil Das s 
 * @version    V2
 */
Class Rewards_Model extends Abstract_Management_Model
{
	function __construct() {
		parent::__construct('level_2');
	}
function get_b2c_user($user_type,  $count =false, $offset=0, $limit=100000000000)
{
	//FIXME
	if($count){
		$query = "SELECT * From user Where user_type='$user_type' and status='1'";
		//debug($query); die;
		$specific_data_list = $this->db->query($query)->result_array();
		return $specific_data_list;
		
	}else{
		$query = "SELECT * From user Where user_type='$user_type' and status='1' limit $offset, $limit";
		//debug($query); die;
		$specific_data_list = $this->db->query($query)->result_array();
		return $specific_data_list;
	}
		
	
	
}
 function get_reward_conversion()
{
	//FIXME
	$query = 'SELECT * FROM rewards';
	$specific_data_list = $this->db->query($query)->result_array();
	return $specific_data_list;
}

function insert_reward_ranges($data){
	$this->db->insert('reward_range',$data);

}
function update_reward_ranges($condition, $data){
	if(!empty($condition)){
		return $this->db->update('reward_range', $data, $condition);
	}
}
function update_wallet_setting($id,$data)
{
    $this->db->where('wallet-id',$id);

$this->db->update('wallet_setting', $data);
}
public function reward_range($data){
   //debug($data); exit;
	$count = count($data['reward_point_from']);

		$module_wise_reward =  array(
			   		'flight'=>META_AIRLINE_COURSE,
			   		'hotel'=>META_ACCOMODATION_COURSE,
			   		'car'=>META_CAR_COURSE,
			   		'activity'=>META_SIGHTSEEING_COURSE,
			   		'transfers'=>META_TRANSFERV1_COURSE,
			   		'holidays'=>META_PACKAGE_COURSE,
			   );

		for($i=0;$i<$count;$i++)
		{
        	$data_insert = array(
				'reward_from'=>$data['reward_point_from'][$i],
				'reward_to'=>$data['reward_point_to'][$i],
				'reward_value'=>$data['reward_percentage'][$i],
				'reward_getting'=>$data['reward_getting'][$i],
				'created_date'=>Date('Y-m-d'),
				'module'=>$module_wise_reward[$data['module']]
			);
			
			if(isset($data['id'][$i]) && $module_wise_reward[$data['module']]== $data['module_old'][$i] )
			{
			    
       			$condition = array('id'=>$data['id'][$i],);
				$re = $this->update_reward_ranges($condition, $data_insert);
	   		}
	   		else
	   		{
                
	   			$re = $this->insert_reward_ranges($data_insert);
	   		}
	    }
	    
	   if($re){
	   		return TRUE;
	   }else{
	   		return FALSE;
	   }
	}
public function get_reward_range($module)
{
	$this->db->where(array('module'=>$module));
	$this->db->select('*');
	$query = $this->db->get('reward_range');
	
	if($query->num_rows()){
		return $query->result_array();
	}else{
		return FALSE;
	}
}
public function delete_wallet_setting($id){

	$this->db->where(array('wallet-id'=>$id));
	if($this->db->delete('wallet_setting')){
		return TRUE;
	}else{
		return FALSE;
	}
}
public function delete_reward_range($id){

	$this->db->where(array('id'=>$id));
	if($this->db->delete('reward_range')){
		return TRUE;
	}else{
		return FALSE;
	}
}
}
