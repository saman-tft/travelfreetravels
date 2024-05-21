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

		//BT, CD, ID

		if ($count) {

			$query = 'select count(*) as total_records from transaction_log where domain_origin='.get_domain_auth_id().' and created_by_id='.$this->entity_user_id;

			$data = $this->db->query($query)->row_array();

			return $data['total_records'];

		} else {

			$query = 'select  currency, TL.system_transaction_id,TL.transaction_type,TL.domain_origin,TL.app_reference,

				TL.fare,TL.domain_markup as admin_markup,TL.level_one_markup as agent_markup,TL.convinence_fees as convinence_amount,TL.promocode_discount as discount,

				TL.remarks,TL.created_datetime,concat(U.first_name, " ", U.last_name) as username 

			from transaction_log TL 

			LEFT JOIN user U ON TL.created_by_id=U.user_id where TL.domain_origin='.get_domain_auth_id().' and TL.created_by_id='.$this->entity_user_id.'

			order by TL.origin desc limit '.$offset.', '.$limit;

			return $this->db->query($query)->result_array();

		}

	}

}