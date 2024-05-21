<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * @package    Provab - Provab Application
 * @subpackage Travel Portal
 * @author     Balu A<balu.provab@gmail.com>
 * @version    V2
 */

class Utilities extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
	}

	function currency_converter($value=0, $id=0)
	{
		if (intval($id) > 0 && intval($value) > -1) {
			$data['value'] = $value;
			$this->custom_db->update_record('currency_converter', $data, array('id' => $id));
		} else {
			$currency_data = $this->custom_db->single_table_records('currency_converter');
			$data['converter'] = $currency_data['data'];
			$this->template->view('utilities/currency_converter', $data);
		}
	}

	function auto_currency_converter()
	{
		$data_set = $this->custom_db->single_table_records('currency_converter');
		if ($data_set['status'] == true) {
			$from = COURSE_LIST_DEFAULT_CURRENCY_VALUE;
			$data['date_time'] = date('Y-m-d H:i:s');
			foreach ($data_set['data'] as $k => $v) {
				$url = 'http://download.finance.yahoo.com/d/quotes.csv?s='.$v['country'].$from.'=X&f=nl1';
				$handle = fopen($url, 'r');
				if ($handle) {
					$currency_data = fgetcsv($handle);
					fclose($handle);
				}
				if ($currency_data != '') {
					if (isset($currency_data[0]) == true and empty($currency_data[0]) == false and isset($currency_data[1]) == true and empty($currency_data[1]) == false) {
						$data['value'] = $currency_data[1];
						$this->custom_db->update_record('currency_converter', $data, array('id' => $v['id']));
					}
				}
			}
		}
		redirect('utilities/currency_converter');
	}

	/**
	 * Load All Events Of Trip Calendar
	 */
	function trip_calendar()
	{
		$this->template->view('utilities/trip_calendar');
	}

	function app_settings()
	{
		$this->template->view('utilities/app_settings');
	}

	/**
	 * Show time line to user previous one month - Load Last one month by default
	 */
	function timeline()
	{
		$this->template->view('utilities/timeline');
	}

	/**
	 * Get All The Events Between Two Dates
	 */
	function timeline_rack()
	{
		$response['status'] = FAILURE_STATUS;
		$response['data'] = array();
		$response['msg'] = '';
		$params = $this->input->get();
		$oe_start = intval($params['oe_start']);
		$event_limit = intval($params['oe_limit']);
		if ($oe_start > -1 and $event_limit > -1) {
			//Older Events
			$oe_list = $this->application_logger->get_events($oe_start, $event_limit);
			if (valid_array($oe_list) == true) {
				$response['oe_list'] = get_compressed_output($this->template->isolated_view('utilities/core_timeline', array('list' => $oe_list)));
				$response['status'] = SUCCESS_STATUS;
			}
		}
		header('Content-type:application/json');
		echo json_encode($response);
		exit;
	}

	/**
	 * Get All The Events Between Two Dates
	 */
	function latest_timeline_events()
	{
		session_write_close();//This is needed as it helps remove session locks
		$response['status'] = FAILURE_STATUS;
		$response['data'] = array();
		$response['msg'] = '';
		$waiting_for_new_event = true;
		$params = $this->input->get();
		$last_event_id = intval($params['last_event_id']);
		if ($last_event_id > -1) {
			$cond = array(array('TL.origin', '>', $last_event_id));
			//Older Events
			while ($response['status'] == false) {
				$os_list = $this->application_logger->get_events(0, 10000000000, $cond);
				if (valid_array($os_list) == true) {
					$response['oa_list'] = get_compressed_output($this->template->isolated_view('utilities/core_timeline', array('list' => $os_list)));
					$response['status'] = SUCCESS_STATUS;
				} else {
					sleep(3);
				}
			}
		}
		header('Content-type:application/json');
		echo json_encode($response);
		exit;
	}
	
	/**
	 * Set Preferred currency to be used in the application
	 * @param unknown_type $currency
	 */
	function set_preferred_currency($currency)
	{
		
		$this->load->library('user_agent');
		if ($this->agent->is_referral()){
		    $url=$this->agent->referrer();
		}
		else
		{
			$url=base_url();
		}
		
		$this->session->set_userdata(array('currency' => $currency));
		header('Content-type:application/json');
		
		$curr_symbol = $this->currency->get_currency_symbol($currency);
		
		
		redirect($url);
		
	}

	function setpreferredcurrency($currency)
	{
		//echo $url;die;
		$this->load->library('user_agent');
		
		if ($this->agent->is_referral()){
		    $url=$this->agent->referrer();
		}
		else
		{
			$url=base_url();
		}
		
		$this->session->set_userdata(array('currency' => $currency));
		header('Content-type:application/json');
		
		$curr_symbol = $this->currency->get_currency_symbol($currency);
		
		
		redirect($url);
		
	}
	
	
	
	
	
	function change_currency_based_on_ip(){
		$currency = $this->session->userdata('currency');
	    $ip  = '14.141.47.106';
		
		$ch = curl_init();	
		
		curl_setopt($ch, CURLOPT_URL, "http://www.geoplugin.net/php.gp?ip=$ip");		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		curl_close($ch);		
		$get_resulted_data = unserialize($output);
		
		if(empty($this->session->userdata('currency'))){		
			$this->set_preferred_currency($get_resulted_data['geoplugin_currencyCode']);
		}
		
	}
}
