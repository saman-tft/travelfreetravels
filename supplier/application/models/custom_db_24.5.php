<?php
/**
 * Library which has generic functions to get data
 *
 * @package    Provab Application
 * @subpackage Travel Portal
 * @author     Balu A<balu.provab@gmail.com>
 * @version    V2
 */
Class Custom_Db extends CI_Model
{
	/**
	 * get records from table using basic select
	 *
	 * @param $table	 Name of table from which records has to be fetched
	 * @param $cols		 group of columns to be selected
	 * @param $condition condition to be used to the records
	 * @param $offset	 offset to be used while fetching records
	 * @param $limit	 number of records to be fetched
	 * @param $order_by	 order in which the records has to be fetched
	 */
	function single_table_records($table, $cols='*', $condition=array(), $offset=0, $limit=100000000, $order_by=array())
	{
		$data = '';
		if (empty($table) == false and is_string($table) == true) {
			if (valid_array($order_by)) {
				foreach ($order_by as $k => $v) {
					$this->db->order_by($k, $v);
				}
			}
			if (valid_array($condition) == false) {
				$condition = array();
			}
			$tmp_data = $this->db->select($cols)->get_where($table, $condition, $limit, $offset);
			// debug($tmp_data->num_rows());
			// die;
			if($tmp_data->num_rows()>0) {
				$tmp_data=$tmp_data->result_array();
				$data = array('status' => QUERY_SUCCESS, 'data' => $tmp_data);
			} else {
				$data = array('status' => QUERY_FAILURE);
			}
		} else {
			redirect('general/redirect_login?op=R');
		}
		//echo $this->db->last_query();
		// debug($data);
		// die;
		return $data;
	}

	/**
	 * get records from different tables using cross join
	 *
	 * @param array $tables        array having list of tables to be joined
	 * @param string $cols	       group of columns to be selected
	 * @param array $joincondition join condition to be used to join tables
	 * @param array $condition     condition to be used to the records
	 * @param number $offset       offset to be used while fetching records
	 * @param number $limit        number of records to be fetched
	 * @param array $order_by      order in which the records has to be fetched
	 * Ex. multiple_table_cross_records(array('modules','api'), 'api_id', array('modules.pk' => 'api.module_fk'), array('modules.status' => ACTIVE, 'api.status' => ACTIVE));
	 */
	function multiple_table_cross_records($tables=array(), $cols='*', $joincondition=array(), $condition=array(), $offset=0, $limit=1000, $order_by=array()){
		$data = '';
		if (valid_array($tables) && valid_array($joincondition)) {
			if (valid_array($order_by)) {
				foreach ($order_by as $k => $v) {
					$this->db->order_by($k, $v);
				}
			}
			for($i=1;$i<count($tables);$i++){
				foreach ($joincondition as $ck => $cv) {
					$this->db->join($tables[$i], $ck."=".$cv);
				}
			}
			$tmp_data = $this->db->select($cols)->get_where($tables[0], $condition, $limit, $offset)->result_array();
			//echo $this->db->last_query(); exit;
			$data = array('status' => QUERY_SUCCESS, 'data' => $tmp_data);
		} else {
			redirect('general/redirect_login?op=R');
		}
		return $data;
	}

	/*
	 *this will insert the data into database and create new record
	 *
	 *@param string $table_name name of the table to which the data has to be inserted
	 *@param array  $data       data which has to be inserted into database
	 *
	 *@return array has status of insertion and insert id
	 */
	function insert_record ($table_name, $data)
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



public function insert_corporate_user($data){
			// $query = $this->db->insert($table_name,$data);
			// echo $this->db->last_query($query);exit;
			// if ($query) {
			// 	return true;
			// }

	 $this->db->insert('user', $data);
	   // echo $this->db->last_query($query);exit;
		$num_inserts = $this->db->affected_rows();
		if (intval($num_inserts) > 0) {
			$data = array('status' => QUERY_SUCCESS, 'insert_id' => $this->db->insert_id());
		} else {
			redirect('general/redirect_login?op=C');
		}
		return $data;
	}
	/*
	 *this will insert the data into database and create new record
	 *
	 *@param string $table_name name of the table to which the data has to be inserted
	 *@param array  $data       data which has to be inserted into database
	 *
	 *@return array has status of insertion and insert id
	 */
	function update_record ($table_name='', $data='', $condition='')
	{
		$status = '';
		if (valid_array($data) == true and valid_array($condition)) {
			$this->db->update($table_name, $data, $condition);
			if($this->db->affected_rows()>0) {
				$status = QUERY_SUCCESS;
			} else {
				$status = QUERY_FAILURE;
			}

			//echo $this->db->last_query();exit;
		} else {
			redirect('general/redirect_login?op=U');
		}
		return $status;
	}

	/*
	 *this will delete data from database
	 *
	 *@param string $table_name name of the table to which the data has to be inserted
	 *@param array  $condition  condition for deleting data
	 *
	 *@return array has status of insertion and insert id
	 */
	function delete_record($table_name='',  $condition='')
	{
		$status = '';
		if (valid_array($condition)) {
			$this->db->delete($table_name, $condition);
			$status = QUERY_SUCCESS;
		} else {
			redirect('general/redirect_login?op=D');
		}
		return $status;
	}

	function generate_static_response($data)
	{
		$insert_id = $this->custom_db->insert_record('test', array('test' => $data));
		return $insert_id['insert_id'];
	}

	/**
	 * form sql condition for the ip array
	 * @param $cond Condition is array of array with each array having 3 params('col', 'comparision', 'value')
	 */
	function get_custom_condition($cond)
	{
		$sql = ' AND ';
		if (valid_array($cond) == true) {
			foreach ($cond as $k => $v) {
				$sql .= $v[0].' '.$v[1].' '.$v[2].' AND ';
			}
		}
		$sql = rtrim($sql, ' AND ');
		return $sql;
	}
}