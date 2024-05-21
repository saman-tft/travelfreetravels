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
	
	/**
	 * Active Notification Count
	 */
	function active_notifications_count()
	{
		$get_data = $this->input->get();
		$response ['status'] = SUCCESS_STATUS;
		$response ['data'] = array ();
		$response ['msg'] = '';
		//DeActive the Notification
		if(isset($get_data['deactive_notification']) == true && $get_data['deactive_notification'] == 1){
			$this->application_logger->disable_active_event_notification();
		}
		$condition = array();
		$active_notifications_count = $this->application_logger->active_notifications_count ($condition);
		$response['data']['active_notifications_count'] = intval($active_notifications_count);
		header ( 'Content-type:application/json' );
		echo json_encode ( $response );
		exit ();
	}
	/**
	 * Balu A
	 * Notification Alerts
	 */
	function events_notification()
	{
		$response ['status'] = FAILURE_STATUS;
		$response ['data'] = array ();
		$response ['msg'] = '';
		$oe_start = 0;
		$event_limit = 10;
		$notification_list = $this->application_logger->get_events_notification ($oe_start, $event_limit);
		
		if (valid_array ( $notification_list ) == true) {
				$page_data['list'] = $notification_list;
				$response['data']['notification_list'] = get_compressed_output ( $this->template->isolated_view ( 'utilities/events_notification',$page_data));
				$response['status'] = SUCCESS_STATUS;
				
			}
		header ( 'Content-type:application/json' );
		echo json_encode ( $response );
		exit ();
	}
	/**
	 * All Notification List
	 */
	function notification_list($offset=0)
	{
		$page_data = array();
		$condition = array();
		$total_records = $this->application_logger->get_events_notification($offset, RECORDS_RANGE_3, $condition,true);
		$page_data['list'] = $this->application_logger->get_events_notification($offset, RECORDS_RANGE_3, $condition, false);
		//--------PAGINATION-------------//
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/utilities/notification_list/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$config['total_rows'] = $total_records->total;
		$config['per_page'] = RECORDS_RANGE_3;
		$this->pagination->initialize($config);
		$page_data['total_rows'] = $total_records->total;
		$this->template->view('utilities/notification_list', $page_data);
	}
}