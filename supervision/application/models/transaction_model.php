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
	 * Return Convenience Fees
	 */
	function get_convenience_fees()
	{
		$data = $this->custom_db->single_table_records('convenience_fees');
		return $data['data'];
	}
	/**
	 * Update Convinence Fees
	 * @param $data array having list of data to be updated
	 */
	function update_convenience_fees($data)
	{
		foreach ($data['origin'] as $k => $v) {
			if (intval($v) > 0) {
				$cond['origin'] = intval($v);
			} else {
				continue;
			}
			$row['value'] = $data['value'][$k];
			$row['value_type'] = $data['value_type_'.$v];
			$row['per_pax'] = $data['per_pax_'.$v];
			$row['convenience_fee_currency'] = get_application_default_currency();
			$this->custom_db->update_record('convenience_fees', $row, $cond);
		}
	}
	
	function create_new_convenience_fees($pg){
		$data = array();
		$data['module'] = $pg;
		$data['per_pax'] = 0;
		$this->db->insert('convenience_fees', $data);
		$num_inserts = $this->db->affected_rows();
		if (intval($num_inserts) > 0) {

			$data = array('status' => QUERY_SUCCESS, 'insert_id' => $this->db->insert_id());
		}else{
			$data = array('status' => 0, 'insert_id' => NULL);
		}

		return $data;
	}
//changes added following three new functions for convenience fees 
	function insert_record($table_name, $data)

	{

		$this->db->insert($table_name, $data);

		$num_inserts = $this->db->affected_rows();

		if (intval($num_inserts) > 0) {

			$data = array('status' => QUERY_SUCCESS, 'insert_id' => $this->db->insert_id());
		} else {

			redirect('general/redirect_login?op=C');
		}

		return $data;
	}

	function delete_conv_modules($condition = '')

	{

		$status = '';

		if (valid_array($condition)) {

			$this->db->delete('convenience_fees', $condition);

			$status = QUERY_SUCCESS;
		} else {

			redirect(base_url() . 'index.php/utilities/convenience_fees');
		}

		return $status;
	}
public function get_payment_mode_app($app_ref) { 
	    $this->db->where( 'app_reference',$app_ref);
		$q= $this->db->get ( 'agentpaymode' );
		return $q;
	}
	/**
	 *
	 */
	function logs($condition=array(), $count=false, $offset=0, $limit=100000000000)
	{	

		$condition = $this->custom_db->get_custom_condition($condition);
		//BT, CD, ID
		if (is_domain_user()) {
			if ($count) {
				$query = 'select count(*) as total_records from transaction_log TL LEFT JOIN user U ON TL.transaction_owner_id = U.user_id where domain_origin = '.get_domain_auth_id().' '.$condition;
				$data = $this->db->query($query)->row_array();
				return $data['total_records'];
			} else {
			    
			    // same with agent changed INR to USD
				$query = 'select "USD" as currency, TL.system_transaction_id,TL.transaction_type,TL.domain_origin,TL.app_reference,
				TL.fare,TL.domain_markup as admin_markup,TL.domain_markup as profit,TL.level_one_markup as agent_markup,TL.convinence_fees as convinence_amount,TL.promocode_discount as discount,
				TL.remarks,TL.created_datetime,TL.transaction_owner_id, concat(U.first_name, " ", U.last_name) as username, agency_name as agent_name  
				from transaction_log TL LEFT JOIN user U ON TL.transaction_owner_id=U.user_id where TL.domain_origin='.get_domain_auth_id().' '.$condition.' 
				order by TL.origin desc limit '.$offset.', '.$limit;
				return $this->db->query($query)->result_array();
			}
		} else {
			if ($count) {
				$query = 'select count(*) as total_records from transaction_log TL LEFT JOIN user U ON TL.transaction_owner_id=U.user_id where 1 = 1 '.$condition;
				$data = $this->db->query($query)->row_array();
				return $data['total_records'];
			} else {
				$query = 'select TL.system_transaction_id,TL.transaction_type,TL.domain_origin,TL.app_reference,
				TL.fare,TL.domain_markup as admin_markup,TL.domain_markup as profit,TL.level_one_markup as agent_markup,TL.convinence_fees as convinence_amount,TL.promocode_discount as discount,
				TL.remarks,TL.created_datetime, concat(U.first_name, " ", U.last_name) as username, agency_name as agent_name 
				from transaction_log TL LEFT JOIN user U ON TL.transaction_owner_id=U.user_id where 1=1 '.$condition.' order by TL.origin desc limit '.$offset.', '.$limit;
				return $this->db->query($query)->result_array();
			}
		}
	}
}
