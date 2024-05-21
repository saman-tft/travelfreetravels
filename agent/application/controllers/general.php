<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * @package    Provab
 * @subpackage General
 * @author     Balu A<balu.provab@gmail.com>
 * @version    V1
 */

class General extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		//$this->output->enable_profiler(TRUE);
		$this->load->model('user_model');
		$this->load->model('Package_Model');
		$this->load->model('custom_db');
		$this->load->model('module_model');
	}

	/**
	 * index page of application will be loaded here
	 */
	function index($default_view='')
	{

		if (is_logged_in_user()) {
			//$this->load->view('dashboard/reminder');
			redirect('menu/index');
		} else {
			//show login
			echo $this->template->view('general/login',$data = array());
		}
	}
	function savecanceltime()
	{
    $data=$this->input->post();
		$this->custom_db->insert_record('cancellation_timeline_policy',$data);
		redirect('general/canceltime');

	}
	function saverescheduleflights()
	{
		
		
		$data=$this->input->post();
		
		$this->custom_db->insert_record('reschedule_flights',$data);
		redirect('general/rescheduleflights');
	}

	/**
	 * Set Search id in cookie
	 */
	private function save_search_cookie($module, $search_id)
	{
		$sparam = array();
		$sparam = $this->input->cookie('sparam', TRUE);
		if (empty($sparam) == false) {
			$sparam = unserialize($sparam);
		}
		$sparam[$module] = $search_id;

		$cookie = array(
			'name' => 'sparam',
			'value' => serialize($sparam),
			'expire' => '86500',
			'path' => PROJECT_COOKIE_PATH
		);
		$this->input->set_cookie($cookie);
	}
	function pre_transfer_search($search_id = '') {
        //Global search Data
        //debug($this->session);exit;
        $search_id = $this->save_pre_search(META_TRANSFER_COURSE);
        $this->save_search_cookie(META_TRANSFER_COURSE, $search_id);

        //Analytics
        $this->load->model('transfer_model');
        $search_params = $this->input->get();
        
       $this->transfer_model->save_search_data($search_params, META_TRANSFER_COURSE);
// debug($_SERVER['QUERY_STRING']);exit();
        redirect('transfer/search/' . $search_id . '?' . $_SERVER['QUERY_STRING']);
    }
 function pre_activity_search($search_id=''){
  //echo "asd";die;
	    $search_id = $this->save_pre_search(META_SIGHTSEEING_COURSE);
	    // echo $search_id; die;
	 
	    $this->save_search_cookie(META_SIGHTSEEING_COURSE, $search_id);
	    //Analytics
	    $this->load->model('activity_model');
	    $search_params = $this->input->get();
	        // debug($search_params);
	        // exit;
	
	    $this->activity_model->save_search_data($search_params, META_SIGHTSEEING_COURSE);
	   
	    redirect('activity/search/'.$search_id.'?'.$_SERVER['QUERY_STRING']);
	  }
	/**
	 * Pre Search For Flight
	 */
	function pre_flight_search($search_id='')
	{
		//Global search Data
		$search_id = $this->save_pre_search(META_AIRLINE_COURSE);
		$this->save_search_cookie(META_AIRLINE_COURSE, $search_id);
		//Analytics
		$this->load->model('flight_model');
		$search_params = $this->input->get();
		$this->flight_model->save_search_data($search_params, META_AIRLINE_COURSE);

		redirect('flight/search/'.$search_id.'?'.$_SERVER['QUERY_STRING']);
	}

	/**
	 * Pre Search For Hotel
	 */
	function pre_hotel_search($search_id='')
	{
		//debug($_GET);exit;
		//Global search Data
		$search_id = $this->save_pre_search(META_ACCOMODATION_COURSE);
		$this->save_search_cookie(META_ACCOMODATION_COURSE, $search_id);
		//Analytics
		$this->load->model('hotel_model');
		$search_params = $this->input->get();
	//	debug($search_params);die;
		$this->hotel_model->save_search_data($search_params, META_ACCOMODATION_COURSE);

		redirect('hotel/search/'.$search_id.'?'.$_SERVER['QUERY_STRING']);
	}
	function pre_hotel_crs_search($search_id = '') {
        //Global search Data
        $search_id = $this->save_pre_search(META_ACCOMODATION_COURSE);
        $this->save_search_cookie(META_ACCOMODATION_COURSE, $search_id);

        //Analytics
        $this->load->model('hotel_model');
        $search_params = $this->input->get();
        $search_params['adult'][1]=0;
          $search_params['adult'][2]=0;
    //  debug($search_params);exit;
        $this->hotel_model->save_search_data($search_params, META_ACCOMODATION_COURSE);

        redirect('villas_apartment/hotel_crs_search/' . $search_id . '?' . $_SERVER['QUERY_STRING']);
    }

	/**
	  * Pre Search for SightSeen
	  */
   function pre_sight_seen_search($search_id=''){

	    $search_id = $this->save_pre_search(META_SIGHTSEEING_COURSE);
	    $this->save_search_cookie(META_SIGHTSEEING_COURSE, $search_id);
	    //Analytics
	    $this->load->model('sightseeing_model');
	    $search_params = $this->input->get();
	    
	    $this->sightseeing_model->save_search_data($search_params, META_SIGHTSEEING_COURSE);
	    
	    redirect('sightseeing/search/'.$search_id.'?'.$_SERVER['QUERY_STRING']);
	}
	/*
	  *Pre Transfer Search
	  */
	    function pre_transferv1_search($search_id=''){
	    $search_id = $this->save_pre_search(META_TRANSFERV1_COURSE);
	    $this->save_search_cookie(META_TRANSFERV1_COURSE, $search_id);
	    //Analytics
	    $this->load->model('transferv1_model');
	    $search_params = $this->input->get();
	    
	    $this->transferv1_model->save_search_data($search_params, META_TRANSFERV1_COURSE);
	    
	    redirect('transferv1/search/'.$search_id.'?'.$_SERVER['QUERY_STRING'].'&booking_source='.PROVAB_TRANSFERV1_BOOKING_SOURCE);
	  }
	   
  function pre_transfercrsv1_search($search_id=''){
    $search_id = $this->save_pre_search(META_TRANSFERV1_COURSE);
    $this->save_search_cookie(META_TRANSFERV1_COURSE, $search_id);
    //Analytics
    $this->load->model('transferv1_model');
    $search_params = $this->input->get();
    
    $this->transferv1_model->save_search_data($search_params, META_TRANSFERV1_COURSE);
    
    redirect('privatetransfer/search/'.$search_id.'?'.$_SERVER['QUERY_STRING'].'&booking_source='.PROVAB_TRANSFERV1_SOURCE_CRS);
  }
	/**
	 * Pre Search For Bus
	 */
	function pre_bus_search($search_id='')
	{
		//Global search Data
		$search_id = $this->save_pre_search(META_BUS_COURSE);
		$this->save_search_cookie(META_BUS_COURSE, $search_id);
		//Analytics
		$this->load->model('bus_model');
		$search_params = $this->input->get();
		$this->bus_model->save_search_data($search_params, META_BUS_COURSE);

		redirect('bus/search/'.$search_id.'?'.$_SERVER['QUERY_STRING']);
	}
	 /**
     * Pre Search For Car
     */
    function pre_car_search($search_id = '') {
        $search_params = $this->input->get();
        // debug($search_params);exit;
        //Global search Data
        $search_id = $this->save_pre_search(META_CAR_COURSE);
        $this->save_search_cookie(META_CAR_COURSE, $search_id);

        //Analytics
        $this->load->model('car_model');
        $this->car_model->save_search_data($search_params, META_CAR_COURSE);
        redirect('car/search/' . $search_id . '?' . $_SERVER['QUERY_STRING']);
    }
        function precarcrssearch($search_id = '') {
        $search_params = $this->input->get();
        // debug($search_params);exit;
        //Global search Data
        $search_id = $this->save_pre_search(META_CAR_COURSE);
        $this->save_search_cookie(META_CAR_COURSE, $search_id);

        //Analytics
        $this->load->model('car_model');
        $this->car_model->save_search_data($search_params, META_CAR_COURSE);
        redirect('privatecar/search/' . $search_id . '?' . $_SERVER['QUERY_STRING'].'&booking_source=PTBCSID00000000017');
    }
	/**
	 * Pre Search For Packages
	 */
	function pre_package_search($search_id='')
	{
		//Global search Data
		$search_id = $this->save_pre_search(META_PACKAGE_COURSE);
		redirect('tours/search'.$search_id.'?'.$_SERVER['QUERY_STRING']);
	}

	/**
	 * Pre Search used to save the data
	 *
	 */
	private function save_pre_search($search_type)
	{
		//Save data
		$search_params = $this->input->get();
		if($search_type==META_ACCOMODATION_COURSE)
		{
		  if(count($search_params['adult'])==3)
		  {
		  $search_params['adult'][1]=0;
          $search_params['adult'][2]=0;
		  }
		}
		$search_data = json_encode($search_params);
		$insert_id = $this->custom_db->insert_record('search_history', array('search_type' => $search_type, 'search_data' => $search_data, 'created_datetime' => date('Y-m-d H:i:s')));
		
		return $insert_id['insert_id'];
	}

	/**
	 * Logout function for logout from account and unset all the session variables
	 */
	function initilize_logout() {
		if (is_logged_in_user()) {
			$this->user_model->update_login_manager($this->session->userdata(LOGIN_POINTER));
			$this->session->unset_userdata(array(AUTH_USER_POINTER => '',LOGIN_POINTER => '', DOMAIN_AUTH_ID => '', DOMAIN_KEY => ''));
			redirect('general/index');
		}
	}
	/**
	 * oops page of application will be loaded here
	 */
	public function ooops()
	{
		$this->template->view('utilities/404.php');
	}

	/*
	 *
	 *Email Subscribtion
	 *
	 */

	public function email_subscription()
	{
		$data = $this->input->get();

		$mail = $data['email'];
		$domain_key = get_domain_auth_id();
		$inserted_id = $this->user_model->email_subscribtion($mail,$domain_key);
		if(isset($inserted_id) && $inserted_id != "already")
		{
			echo "success";
		}elseif($inserted_id=="already"){
			echo "already";
		}else{
			echo "failed";
		}


	}
	/**
	 * Booking Not Allowed Popup
	 */
	function booking_not_allowed()
	{
		$this->template->view('general/booking_not_allowed');
	}
	public function save_keep_email(){
    // $post_data=$this->input->post();
    // echo 'herer I am';exit;
    $data = $this->input->post();
    
   //debug($data);exit;
    if ($data['email_id']){
      
      
     
      $data ['status'] = '0';
      $data ['subscribed_date'] = date ( 'Y-m-d H:i:s' );
    
      $data ['domain_list_fk'] = get_domain_auth_id ();
      // debug($data);exit; 
      $this->load->model('module_model');
      $result = $this->module_model->save_keep_self_email( $data );
      if($result){
        
        $status = true;
        $message = "Thank you for submitting your email id";
        header('content-type:application/json');
        echo json_encode(array('status' => $status, 'message' => $message));
        exit;
     
      
      }
      else
      {
          $status = false;
        $message = "Email id already exist";
        header('content-type:application/json');
        echo json_encode(array('status' => $status, 'message' => $message));
        exit;
      }
      
      
    }
  }
	public function test($app_reference)
	{
		$this->load->model('flight_model');
		$this->load->library('booking_data_formatter');
		$booking_data = $this->flight_model->get_booking_details($app_reference, '');
		$booking_data = $this->booking_data_formatter->format_flight_booking_data($booking_data, 'b2b');
		$amount = $booking_data['data']['booking_details'][0]['agent_buying_price'];
		
	}
	function pre_holiday_search($search_id = '') {
		// Global search Data
		//die;
		// debug(META_PACKAGE_COURSE);exit();
		//debug($search_id);exit;
		$search_id = $this->save_pre_search ( META_PACKAGE_COURSE );
		// debug($search_id);
		$this->save_search_cookie ( META_PACKAGE_COURSE, $search_id );
		
		// Analytics
		$this->load->model ( 'tours_model' );
		$search_params = $this->input->get ();
		// debug($search_params);exit;
		$this->tours_model->save_search_data ( $search_params, META_PACKAGE_COURSE );
		
		redirect ( 'tours/search/' . $search_id . '?' . $_SERVER ['QUERY_STRING'] );
	}
	// public function testemail(){
 //    // echo "ff";exit;
 //    $email="no_reply@alkhaleej.tours";
 //    $this->load->library('provab_mailer');
 //    $mail_template="test email bichitra11";
 //    $res=$this->provab_mailer->send_mail($email, domain_name().' - sdFlight Ticket',$mail_template);
 //    //debug($res);

 //  }
  
  function cms($page_position='Bottom', $id,$test)
	{
	  // echo $id;
		if($id > 0) {
			$data = $this->custom_db->single_table_records('cms_pages','page_title,page_description,page_seo_title,page_seo_keyword,page_seo_description', array('page_id' => $id,  'page_status' => 1));
			//echo $this->db->last_query();
			//debug($data);die;
			$this->template->view('cms/cms',$data);

		} else {
			redirect('general/index');
		}
	}
}