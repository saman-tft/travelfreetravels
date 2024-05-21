<?php
require_once 'abstract_management_model.php';
/**
 * @package    Provab Application
 * @subpackage Travel Portal
 * @author     Balu A<balu.provab@gmail.com>
 * @version    V2
 */
Class Transaction_Model extends CI_Model
{
	/**
	 *
	 */
		public function get_payment_status($app_reference)
	{
		//debug($app_reference);exit;
		$this->db->select('status');
		$this->db->where('app_reference =', trim($app_reference));
 		$this->db->from('payment_gateway_details');
 		return $this->db->get()->row_array();
	}
	function logs($condition=array(), $count=false, $offset=0, $limit=100000000000)
	{	

		$condition = $this->custom_db->get_custom_condition($condition);
		//BT, CD, ID
		if (is_domain_user()) {
			if ($count) {
				$query = 'select count(*) as total_records from transaction_log TL where TL.domain_origin='.get_domain_auth_id().' AND  TL.transaction_owner_id ='.$GLOBALS['CI']->entity_user_id.' '.$condition;
				$data = $this->db->query($query)->row_array();
				return $data['total_records'];
			} else {
			    
			    // for usd i changed in reports used usd instead of INR  
				$query = 'select "USD" as currency, TL.system_transaction_id,TL.transaction_type,TL.domain_origin,TL.app_reference,
				TL.fare,TL.domain_markup as admin_markup,TL.domain_markup as profit,TL.level_one_markup as agent_markup,TL.convinence_fees as convinence_amount,TL.promocode_discount as discount,
				TL.remarks,TL.created_datetime,TL.transaction_owner_id, concat(U.first_name, " ", U.last_name) as username , agency_name as agent_name  
				from transaction_log TL LEFT JOIN user U ON TL.transaction_owner_id = U.user_id where TL.domain_origin='.get_domain_auth_id().' AND TL.transaction_owner_id ='.$GLOBALS['CI']->entity_user_id.' '.$condition.'
				order by TL.origin desc limit '.$offset.', '.$limit;
				return $this->db->query($query)->result_array();
			}
		} else {
			if ($count) {
				$query = 'select count(*) as total_records from transaction_log TL where TL.transaction_owner_id ='.$GLOBALS['CI']->entity_user_id.' '.$condition;
				$data = $this->db->query($query)->row_array();
				return $data['total_records'];
			} else {
				$query = 'select TL.system_transaction_id,TL.transaction_type,TL.domain_origin,TL.app_reference,
				TL.fare,TL.domain_markup as admin_markup,TL.domain_markup as profit,TL.level_one_markup as agent_markup,TL.convinence_fees as convinence_amount,TL.promocode_discount as discount,
				TL.remarks,TL.created_datetime, concat(U.first_name, " ", U.last_name) as username , agency_name as agent_name  
				from transaction_log TL LEFT JOIN user U ON TL.transaction_owner_id=U.user_id
				WHERE TL.transaction_owner_id ='.$GLOBALS['CI']->entity_user_id.' '.$condition.' order by TL.origin desc limit '.$offset.', '.$limit;
				return $this->db->query($query)->result_array();
			}
		}
	}


	//three new functions for b2b topup
	public function save_validation($validation_id = ''){
	if($validation_id !=  ''){
	    	$agentEmail  = $this->session->userdata('username') ?? '';
	    $userCondition['email'] = provab_encrypt($agentEmail);
						$userCondition['status'] = ACTIVE;
						$userCondition['user_type'] = B2B_USER;
						$user_record = $this->custom_db->single_table_records('user', 'email, password, user_id, first_name, last_name, phone, agency_name', $userCondition);
							if ($user_record['status'] == true and valid_array($user_record['data']) == true) {
						    
							$userInfo = $user_record['data'][0];
									$data['name'] = $userInfo['first_name'] . ' '. $userInfo['last_name'];
											$data['email'] = provab_encrypt($agentEmail);
							$data['company_name'] = $userInfo['agency_name'];
							$data['phone'] = $userInfo['phone'];
							$data['created_date'] = date("Y-m-d H:i:s");
							
							}else{
							    throw new Exception("Unable to find a user");
							}
		$data['refernce_code'] = $validation_id;
			$remarks['topupStatus'] = TOPUP_INITIATED;
			$remarks = json_encode($remarks);
		$data['remarks'] = $remarks;
		try {
			$status = $this->custom_db->insert_record('offline_payment', $data);
		} catch (Exception $e) {
			throw new Exception("Couldn't create validation id record");
			$status['status'] = 0;
			$status['insert_id'] = 0;
		}
	}else{
		$status['status'] = 0;
		$status['insert_id'] = 0;
	}
	return $status;
	

}

public function update_validation($data= array(),$condition = array()){
	if(count($data) > 0){
		try {
			$status = $this->custom_db->update_record('offline_payment', $data , $condition);
		} catch (Exception $e) {
			throw new Exception("Couldn't update amount");
			$status['status'] = 0;
			$status['update_id'] = 0;
		}
	}else{
		$status['status'] = 0;
		$status['update_id'] = 0;
	}
	return $status;
	

}
public function updateBalance($validationId = '', $amount = '' , $id = ''){
	if($validationId != '' && $amount != '' && $id != ''){
		$tempTopupData = $this->custom_db->single_table_records ( 'offline_payment', '', array (
			'refernce_code' => $validationId 
	));
	if (empty($tempTopupData) == false and valid_array($tempTopupData) == true) {
		$condition = array('user_oid'=> $id);
		$updateData['balance'] = $amount;
		$updateStatus = $this->custom_db->update_record('b2b_user_details', $updateData, $condition);
	}
	return $updateStatus;

	}
}
}