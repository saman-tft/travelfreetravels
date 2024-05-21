<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * @package    Provab
 * @subpackage General
 * @author     Balu A<balu.provab@gmail.com>
 * @version    V1
 */
error_reporting(0);
// error_reporting(E_ALL);
class User extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		// $this->load->helper('url');
  //       if (isset($this->session->userdata['AUID'])==false)
  //       {
  //           redirect(base_url());
  //       }
		 $this->load->model('user_model');
		 $this->load->model('module_model');
		//$this->output->enable_profiler(TRUE);
	}

	function create_default_domain($domain_key_name='192.168.0.26')
	{
		include_once DOMAIN_CONFIG.'default_domain_configuration.php';
	}

	/**
	 * index page of application will be loaded here
	 */
	function index()
	{
		if (is_logged_in_user()) {
			redirect('menu/index');
		}
	}


	function create_subagent($offset=0)
	{
		// error_reporting(E_ALL);
		$page_data['form_data'] = $this->input->post();

		$this->current_page = new Provab_Page_Loader('user_management');
		// debug($page_data);
		// die;
		// echo "in";
		$get_data = $this->input->get();
		// debug($page_data);
					if(isset($get_data['domain_origin']) == true && intval($get_data['domain_origin']) >0) {
			$domain_origin = intval($get_data['domain_origin']);
		} else {
			$domain_origin = 0;
		}
		$condition = array();
		$page_data['eid'] = intval(@$get_data['eid']);
		// die;
		if (valid_array($page_data['form_data']) == false && intval(@$page_data['eid']) > 0) {
			// echo "in111";
			// die;
			/**
			 * EDIT DATA
			 */
			$e_condition[] = array('U.user_id', '=', $page_data['eid']);
			$edit_data = $this->user_model->get_domain_user_list($e_condition, false, 0, 1);
			if (valid_array( $edit_data) == true) {
				$page_data['form_data'] = $edit_data[0];
				$page_data['form_data']['email'] = provab_decrypt($page_data['form_data']['email']);
				$page_data['form_data']['uuid'] = provab_decrypt($page_data['form_data']['uuid']);
			} else {
				redirect('security/log_event?event=Invalid user edit');
			}
		} elseif (valid_array($page_data['form_data']) == true) {
			// echo "inwewe";
			// debug($page_data);
			// die;
			/** AUTOMATE VALIDATOR **/
			
			$page_data['form_data']['language_preference'] = 'english';
			$this->current_page->set_auto_validator();
			$this->load->library('form_validation');
			$this->form_validation->set_rules('title', 'Title', 'required');
			$this->form_validation->set_rules('first_name', 'First Name', 'required');
			$this->form_validation->set_rules('last_name', 'Last Name', 'required');
			$this->form_validation->set_rules('date_of_birth', 'Date of Birth', 'required');
			$this->form_validation->set_rules('country_code', 'Country Code', 'required');
			$this->form_validation->set_rules('phone', 'Phone No', 'required');
			$this->form_validation->set_rules('address', 'Address', 'required');
			$this->form_validation->set_rules('email', 'Email', 'valid_email|required|max_length[80]|callback_b2b2b_email_check');
			$this->form_validation->set_rules('password', 'Password','min_length[5]|max_length[45]|required|callback_valid_password');

			$this->form_validation->set_rules('confirm_password', 'Confirm','matches[password]|required');
			/*$this->form_validation->set_rules('password', 'Password', 'matches[password_c]|min_length[5]|max_length[45]|required|callback_valid_password');
			$this->form_validation->set_rules('password_c', 'Confirm');*/
			// debug($this->form_validation->run());exit;
			if ($this->form_validation->run()) {
				// echo "SasasDSD";
				// die;
				$image_data = array();
				// FILE UPLOAD
				if (valid_array ( $_FILES ) == true and $_FILES ['image'] ['error'] == 0 and $_FILES ['image'] ['size'] > 0) 
				{					
					$config ['upload_path'] = $this->template->domain_image_upload_path ();
					$temp_file_name = $_FILES ['image'] ['name'];
					$config['allowed_types'] = 'jpg|png|jpeg';
					$config['max_size'] = MAX_DOMAIN_LOGO_SIZE;
					$config['max_width']  = MAX_DOMAIN_LOGO_WIDTH;
					$config['max_height']  = MAX_DOMAIN_LOGO_HEIGHT;
					$config ['encrypt_name'] = true;
					// UPLOAD IMAGE
					$this->load->library ( 'upload', $config );
					$this->upload->initialize ( $config );
					if (!$this->upload->do_upload ('image')) 
					{
						$this->session->set_flashdata(array('message' => $this->upload->display_errors (), 'type' => FAILURE_MESSAGE, 'override_app_msg' => true));
						redirect('user/'.__FUNCTION__.'?'.$_SERVER['QUERY_STRING']);
					} 
					$image_data = $this->upload->data();
					// debug($image_data);die;					
				}
				$page_data['form_data']['image'] = (empty($image_data['file_name']) == false ? $image_data ['file_name'] : '');
				//LETS UNSET DATA WHICH ARE NOT NEEDED FOR DB
				unset($page_data['form_data']['FID']);

				if (intval($page_data['form_data']['user_id']) > 0) {
	
					//Update Data
					$this->custom_db->update_record('user', $page_data['form_data'], array('user_id' => $page_data['form_data']['user_id'], 'email' => $page_data['form_data']['email']));
					$this->application_logger->profile_update($this->entity_name, $this->entity_name.' Updated '.$page_data['form_data']['first_name'].' Profile Details', array('user_id' => $page_data['form_data']['user_id'], 'uuid' => $page_data['form_data']['uuid']));
					//set_update_message();
					$this->session->set_flashdata(array('message' =>'Successfully Updated', 'type' => SUCCESS_MESSAGE, 'override_app_msg' => true));
				} elseif (intval($page_data['form_data']['user_id']) == 0 || empty($page_data['form_data']['user_id'])) {
					// echo "in2";
					// die;
					//Insert Data
					//LETS UNSET DATA WHICH ARE NOT NEEDED FOR DB
					unset($page_data['form_data']['confirm_password']);
					$domain_list_fk = get_domain_auth_id();//DOMAIN USERS CREATION BY DOMAIN ADMIN
					$page_data['form_data']['domain_list_fk'] = $domain_list_fk;//DOMAIN ORIGIN
					$page_data['form_data']['email'] = provab_encrypt($page_data['form_data']['email']);

					$page_data['form_data']['user_name'] = $page_data['form_data']['email'];
					$page_data['form_data']['created_datetime'] = date('Y-m-d H:i:s');
					$page_data['form_data']['created_by_id'] = $this->entity_user_id;
					$page_data['form_data']['uuid'] = provab_encrypt(PROJECT_PREFIX.time());
					$page_data['form_data']['password'] = provab_encrypt(md5(trim($page_data['form_data']['password'])));
					// debug($page_data);
					// die;

					$insert_id = $this->custom_db->insert_record('user', $page_data['form_data']);
					/*  B2B User Details Records */
					/*get the admin currency*/
					$get_admin_currency = $this->custom_db->single_table_records('domain_list','currency_converter_fk',array('domain_key'=>CURRENT_DOMAIN_KEY));
					$page_data['b2b_data']['currency_converter_fk'] = $get_admin_currency['data'][0]['currency_converter_fk'];
					
					$page_data['b2b_data']['user_oid']=$insert_id['insert_id'];
					$page_data['b2b_data']['balance']=0;
					$page_data['b2b_data']['created_datetime']=date('Y-m-d H:i:s');
					$page_data['b2b_data']['created_by_id']=$this->entity_user_id;
					$this->custom_db->insert_record('b2b_user_details', $page_data['b2b_data']);
					/* B2B User Details Ends */
					$page_data['form_data']['email'] = provab_decrypt($page_data['form_data']['email']);
					$page_data['form_data']['uuid'] = provab_decrypt($page_data['form_data']['uuid']);

					$this->application_logger->registration($this->entity_name, $this->entity_name.' Registered '.$page_data['form_data']['email'].' From Admin Portal', $this->entity_user_id, array('user_id' => $insert_id['insert_id'], 'uuid' => $page_data['form_data']['uuid']));
					//set_insert_message();
					$this->session->set_flashdata(array('message' =>'Successfully Added', 'type' => SUCCESS_MESSAGE, 'override_app_msg' => true));
				} else {
					// echo "inb2";
					// die;
					redirect('security/log_event?event=User Invalid CRUD');
				}
				if(intval(@$get_data['eid']) > 0) {
					// echo "sds";
					// die;
					$temp_query_string = str_replace('&eid='.intval($get_data['eid']),'', $_SERVER['QUERY_STRING']);
				} else {
					// echo "ASAS";
					// die;
					$temp_query_string = $_SERVER['QUERY_STRING'];
				}
				// echo "WQWq";
				// die;
				redirect('user/'.__FUNCTION__.'?'.$temp_query_string);
			}
		}


		//IF DOMAIN ORIGIN IS SET, THEN GET ONLY THAT DOMAIN ADMIN DETAILS
		if (isset($get_data['user_status']) == true) {
			$condition[] = array('U.status', '=', $this->db->escape(intval($get_data['user_status'])));
			$condition[] = array('U.user_type', ' IN (', intval(3), ')');
		}
		if (isset($get_data['agency_name']) == true && empty($get_data['agency_name']) == false) {
			$condition[] = array('U.agency_name', ' like ', $this->db->escape('%'.$get_data['agency_name'].'%'));
		}
		if (isset($get_data['uuid']) == true && empty($get_data['uuid']) == false) {
			$condition[] = array('U.uuid', ' like ', $this->db->escape('%'.provab_encrypt($get_data['uuid']).'%'));
		}
		if (isset($get_data['pan_number']) == true && empty($get_data['pan_number']) == false) {
			$condition[] = array('U.pan_number', ' like ', $this->db->escape('%'.$get_data['pan_number'].'%'));
		}
		if (isset($get_data['email']) == true && empty($get_data['email']) == false) {
			$condition[] = array('U.email', ' like ', $this->db->escape('%'.provab_encrypt($get_data['email']).'%'));
		}
		if (isset($get_data['phone']) == true && empty($get_data['phone']) == false) {
			$condition[] = array('U.phone', ' like ', $this->db->escape('%'.$get_data['phone'].'%'));
		}
		if (isset($get_data['created_datetime_from']) == true && empty($get_data['created_datetime_from']) == false) {
			$condition[] = array('U.created_datetime', '>=', $this->db->escape(db_current_datetime($get_data['created_datetime_from'])));
		}
		if(isset($get_data['filter']) == true && $get_data['filter'] == 'search_agent' &&
			isset($get_data['filter_agency']) == true && empty($get_data['filter_agency']) == false) {
				$filter_agency = trim($get_data['filter_agency']);
				//Search Filter
				$condition[] = array('U.agency_name', ' like ', $this->db->escape('%'.$filter_agency.'%'));
		}
		
		//get domain country and city
		$temp_details = $this->custom_db->single_table_records('domain_list', '*', array('origin' => get_domain_auth_id()));		
		$page_data['form_data']['api_country_list'] = $temp_details['data'][0];
		$condition[] = array('U.user_type', ' IN (', SUB_AGENT, ')');

		/** TABLE PAGINATION */

// debug($condition);
// die;
		$page_data['table_data'] = $this->user_model->b2b_user_list($condition, false, $offset, RECORDS_RANGE_3);
		if(intval($domain_origin) > 0 && valid_array($page_data['table_data'])) {
			$page_data['domain_admin_exists'] = true;
		} else {
			$page_data['domain_admin_exists'] = false;
		}
		// debug($page_data['table_data']);exit;
		$total_records = $this->user_model->b2b_user_list($condition, true);
		
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/user/create_subagent/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$config['total_rows'] = $total_records->total;
		$config['per_page'] = RECORDS_RANGE_3;
		$this->pagination->initialize($config);
		$page_data['search_params'] = $get_data;
		$page_data['total_rows'] = $total_records->total;

		/** TABLE PAGINATION */
		//Get Online User Count
         // debug($page_data);exit;
		$this->template->view('user/user_management', $page_data);
	}
function user_management($offset=0)
	{
		$page_data['form_data'] = $this->input->post();
		$get_data = $this->input->get();
		// debug($page_data);
		// debug($get_data);exit;
		$condition = array();
		//CHECKING DOMAIN ORIGIN SET OR NOT
		if(isset($get_data['domain_origin']) == true && intval($get_data['domain_origin']) >0) {
			$domain_origin = intval($get_data['domain_origin']);
		} else {
			$domain_origin = 0;
		}

		$page_data['eid'] = intval(@$get_data['eid']);
		if (valid_array($page_data['form_data']) == false && intval(@$page_data['eid']) > 0) {
			/**
			 * EDIT DATA
			 */
			$edit_data = $this->custom_db->single_table_records('user', '*', array('user_id' => $page_data['eid']));
			if (valid_array( $edit_data['data']) == true) {
				$page_data['form_data'] = $edit_data['data'][0];
				$page_data['form_data']['email'] = provab_decrypt($page_data['form_data']['email']);
				// debug($page_data);die;

			} else {
				redirect('security/log_event?event=Invalid user edit');
			}
		} 
		elseif (valid_array($page_data['form_data']) == true) {


			/** AUTOMATE VALIDATOR **/
			$page_data['form_data']['language_preference'] = 'english';
			$this->current_page->set_auto_validator();
			$this->load->library('form_validation');
			// $this->form_validation->set_rules('password', 'New Password', 'matches[confirm_password]|min_length[5]|max_length[45]|required|callback_valid_password');
			// $this->form_validation->set_rules('confirm_password', 'Confirm');
			if ($this->form_validation->run()) {
				// debug($page_data);
				// echo "in";
				// die;
				//LETS UNSET DATA WHICH ARE NOT NEEDED FOR DB
				unset($page_data['form_data']['FID']);
				if (intval($page_data['form_data']['user_id']) > 0) {
					$page_data['form_data']['email'] = provab_encrypt($page_data['form_data']['email']);
					//Update Data
					$page_data['form_data']['date_of_birth']=date("Y-m-d", strtotime($page_data['form_data']['date_of_birth']) );
					// debug($page_data);
					// die;
					$this->custom_db->update_record('user', $page_data['form_data'], array('user_id' => $page_data['form_data']['user_id'], 'email' => $page_data['form_data']['email']));
					// echo $this->db->last_query();exit;
					$this->application_logger->profile_update($this->entity_name, $this->entity_name.' Updated '.$page_data['form_data']['first_name'].' Profile Details', array('user_id' => $page_data['form_data']['user_id'], 'uuid' => $page_data['form_data']['uuid']));
// $this->session->set_flashdata('feedback', 'Success message for client to see');
// 					echo $this->session->flashdata('feedback');
					//set_update_message();
					$this->session->set_flashdata(array('message' => 'AL004', 'type' => SUCCESS_MESSAGE));
				} elseif (intval($page_data['form_data']['user_id']) == 0) {
					//Insert Data
					//LETS UNSET DATA WHICH ARE NOT NEEDED FOR D
					// debug($page_data);
					unset($page_data['form_data']['confirm_password']);
					if(intval($domain_origin) > 0) {
						$domain_list_fk = $domain_origin;//DOMAIN ADMIN CREATION BY PROVAB ADMIN
					} else if(get_domain_auth_id() > 0) {
						$domain_list_fk = get_domain_auth_id();//DOMAIN USERS CREATION BY DOMAIN ADMIN
					} else {
						$domain_list_fk = 0;
					}

					$page_data['form_data']['date_of_birth']=date("Y-m-d", strtotime($page_data['form_data']['date_of_birth']) );
					$page_data['form_data']['domain_list_fk'] = $domain_list_fk;//DOMAIN ORIGIN
					$page_data['form_data']['created_datetime'] = date('Y-m-d H:i:s');
					$page_data['form_data']['created_by_id'] = $this->entity_user_id;
					$page_data['form_data']['uuid'] = provab_encrypt(PROJECT_PREFIX.time());
					$page_data['form_data']['email'] = provab_encrypt($page_data['form_data']['email']);
					$page_data['form_data']['user_name'] =$page_data['form_data']['email'];

					$page_data['form_data']['password'] = provab_encrypt(md5(trim($page_data['form_data']['password'])));
					// debug($page_data);die;
					$insert_id = $this->custom_db->insert_record('user', $page_data['form_data']);
					/*  B2B User Details Records */
					if($page_data['form_data']['user_type']==SUB_AGENT){
						$page_data['b2b_data']['user_oid']=$insert_id['insert_id'];
						$page_data['b2b_data']['balance']=0;
						$page_data['b2b_data']['created_datetime']=date('Y-m-d H:i:s');
						$page_data['b2b_data']['created_by_id']=$this->entity_user_id;
						$this->custom_db->insert_record('b2b_user_details', $page_data['b2b_data']);
					}
					/* B2B User Details Ends */
					$page_data['form_data']['uuid'] = provab_decrypt($page_data['form_data']['uuid']);
					$page_data['form_data']['email'] = provab_decrypt($page_data['form_data']['email']);

					$this->application_logger->registration($this->entity_name, $this->entity_name.' Registered '.$page_data['form_data']['email'].' From Admin Portal', $this->entity_user_id, array('user_id' => $insert_id['insert_id'], 'uuid' => $page_data['form_data']['uuid']));
					set_insert_message();
					
					// $this->session->set_flashdata(array('message' => 'UL0014', 'type' => SUCCESS_MESSAGE));
				} else {
					redirect('security/log_event?event=User Invalid CRUD');
				}
				if(intval(@$get_data['eid']) > 0) {
					$temp_query_string = str_replace('&eid='.intval($get_data['eid']),'', $_SERVER['QUERY_STRING']);
					$eid='&eid='.intval($get_data['eid']);
				} else {
					$temp_query_string = $_SERVER['QUERY_STRING'];
					$eid='';

				}
				redirect('user/'.__FUNCTION__);
			}
		}
		

		// debug($get_data);exit;
		//IF DOMAIN ORIGIN IS SET, THEN GET ONLY THAT DOMAIN ADMIN DETAILS
		if(intval($domain_origin) > 0) {
			$condition = array(array('U.domain_list_fk' ,'=', $domain_origin),
			array('U.user_type', '=', ADMIN)
			);
		}
		else if(valid_array($get_data) == true) {
			$condition = array();
			if (isset($get_data['user_status']) == true) {
				$condition[] = array('U.status', '=', $this->db->escape(intval($get_data['user_status'])));
				$condition[] = array('U.user_type', ' IN (', intval(2), ')');
			}
			if (isset($get_data['uuid']) == true && empty($get_data['uuid']) == false) {
				$condition[] = array('U.uuid', ' like ', $this->db->escape('%'.provab_encrypt($get_data['uuid']).'%'));
			}
			if (isset($get_data['email']) == true && empty($get_data['email']) == false) {
				$condition[] = array('U.email', ' like ', $this->db->escape('%'.provab_encrypt($get_data['email']).'%'));
			}
			if (isset($get_data['phone']) == true && empty($get_data['phone']) == false) {
				$condition[] = array('U.phone', ' like ', $this->db->escape('%'.$get_data['phone'].'%'));
			}
			if (isset($get_data['created_datetime_from']) == true && empty($get_data['created_datetime_from']) == false) {
				$condition[] = array('U.created_datetime', '>=', $this->db->escape(db_current_datetime($get_data['created_datetime_from'])));
			}
			if(isset($get_data['filter']) == true && isset($get_data['q']) == true) {
				
				$condition = array();
				if (isset($get_data['user_status']) == true) {

					$condition[] = array('U.status', '=', intval($get_data['user_status']));
					$page_data['user_status'] = intval($get_data['user_status']);
				}
				switch ($get_data['filter']) {
					case 'user_type':
						//Get Users Based on User Types(Active/Inactive Users)
						if(intval($get_data['q']) > 0) {
							$condition[] = array('U.user_type', ' IN (', intval($get_data['q']), ')');
						}
						break;
				}
			}

		}
		// echo "error";die;
		// debug($condition);exit;
		/** TABLE PAGINATION */
		
		$total_records = $this->user_model->b2b_user_list($condition, true);
		$page_data['table_data'] = $this->user_model->b2b_user_list($condition, false, $offset);

		//CHECKING DOMAIN ADMIN EXISTS, IF EXISTS DISABLE ADD FORM IN THE VIEW
		if(intval($domain_origin) > 0 && valid_array($page_data['table_data'])) {
			$page_data['domain_admin_exists'] = true;
		} else {
			$page_data['domain_admin_exists'] = false;
		}
		$page=array();
		foreach($page_data['table_data'] as $k=>$v)
		{
			if($v['user_type']=='Sub Agent')
			{
				
				$page[$k]=$v;
			}
		}
		// debug($page);die;
		// debug($page);
		// die;
		$total_records=$config['total_rows']=count($page);
		$page_data['table_data']=$page;
		$this->load->library('pagination');
		$config['base_url'] = base_url().'index.php/general/user/';
		$config['total_rows'] = $total_records->total;
		$config['per_page'] = RECORDS_RANGE_1;
		$this->pagination->initialize($config);
		$page_data['search_params'] = $get_data;
		/** TABLE PAGINATION */
		//Get Online User Count
		$title[0]=$page_data['form_data']['title'];
		$country_code[0]=$page_data['form_data']['country_code'];
		$user_type[0]=$page_data['form_data']['user_type'];
		// debug($title);
		$page_data['form_data']['title']=$title;
		$page_data['form_data']['country_code']=$country_code;
		$page_data['form_data']['user_type']=$user_type;
// debug($page_data['form_data']);
// die;
		$this->template->view('user/user_management', $page_data);
	}
function privilege_management()
	{
		$post_data['form_data'] = $this->input->post();
		$privillege = implode(",",$post_data['form_data']['user_previlages']);
		$get_data = $this->input->get();
		// debug($get_data);
		// exit;
		$uid = $post_data['form_data']['user_id'];
		if(!isset($uid)){
			$uid =$get_data['eid'];	
		}
		
		// debug($post_data);die();
		$privillege_data = $this->custom_db->single_table_records('sub_admin_privillege_list', '*', array('uid' => $uid));
		// debug($privillege_data);
		if(valid_array($post_data['form_data']['user_previlages'])==true)
		{ 
		$privillege = implode(",",$post_data['form_data']['user_previlages']);
			
			// debug($privillege_data);
			//  exit;
			if($privillege_data['status'] == 0){
				$this->custom_db->insert_record('sub_admin_privillege_list', array('uid' => $uid,'privilleges' => $privillege));
				// set_insert_message();
				$this->session->set_flashdata(array('message' => 'AL004', 'type' => SUCCESS_MESSAGE));
			}
			else{
				$update_privillege_record["privilleges"] =$privillege;
				// debug($update_privillege_record);exit;
				$this->custom_db->update_record('sub_admin_privillege_list', $update_privillege_record, array('uid' => intval($uid)));
				$privillege_data = $this->custom_db->single_table_records('sub_admin_privillege_list', '*', array('uid' => $uid));
				//set_update_message();
				$this->session->set_flashdata(array('message' => 'AL004', 'type' => SUCCESS_MESSAGE));
				
				
			}	
			redirect(base_url().'index.php/user/create_subagent');	
		}
		// debug($privillege_data);
		// exit;
		$page_data['activated_privilleges'] =explode(",", $privillege_data['data'][0]['privilleges']);
		$privillge_list = $this->custom_db->single_table_records('privilleges_list', '*', array('status'=>'ACTIVE'),0,100000000,array());
		
		$page_data['privilage_list']=$privillge_list['data'];

		foreach ($privillge_list['data'] as $key => $privillege) { 
			if( in_array($privillege['id'],$page_data['activated_privilleges'])){
				$page_data['privilage_list'][$key]['checked'] = "checked=checked";
			}else{
				$page_data['privilage_list'][$key]['checked'] = "";
			}
		}

		$page_data['uid']=$get_data['eid'];
		$page_data['user_status']=$get_data['user_status'];
		// debug($page_data);
		// exit;
		$this->template->view('user/privilage', $page_data);
		// debug($get_data);exit;
	}
	function create_subagent_old(){

		$this->current_page = new Provab_Page_Loader('user_management');
		$this->template->view('user/user_management', $page_data);
	}

	/**
	 * Generate my account view to user
	 */
	function activate_account($user_id, $uuid)
	{
		$cond['user_id'] = intval($user_id);
		$cond['uuid'] = $uuid;
		$data['status'] = ACTIVE;
		$info = $this->user_model->update_user_data($data, $cond);
		if ($info['status'] == SUCCESS_STATUS) {
			$task = 'activate';
			$this->account_status($info,$task);
		}
		exit;
		/*redirect(base_url().'user/user_management?filter=user_type&q='.$info['data']['user_type']);*/
	}

	/**
	 * Deactiavte User Account
	 */
	function deactivate_account($user_id, $uuid)
	{
		$cond['user_id'] = intval($user_id);
		$cond['uuid'] = $uuid;
		$data['status'] = INACTIVE;
		$info = $this->user_model->update_user_data($data, $cond);
		if ($info['status'] == SUCCESS_STATUS) {
			$task = 'deactivate';
			$this->account_status($info,$task);
		}
		exit;
		/*redirect(base_url().'user/user_management?filter=user_type&q='.$info['data']['user_type']);*/
	}

	function account()
	{
		// error_reporting(E_ALL);
		$page_data['form_data'] = $this->input->post();
		$get_data = $this->input->get();

		// debug($page_data);
		// debug($get_data);
		// die;
		/**
		 * USE USER PAGE FOR MY ACCOUNT
		 * @var unknown_type
		 */
		$this->user_page = new Provab_Page_Loader('user_management');
		if (isset($get_data['uid']) == true) {
			$get_data['uid'] = intval($get_data['uid']);

			if (valid_array($page_data['form_data']) == false) {
			// debug($get_data['uid']);
				/*** EDIT DATA ***/
				$cond = array(array('U.user_id', '=', intval($get_data['uid'])));
				//old $edit_data = $this->user_model->get_user_details($cond);
				$s="select * from user where user_id='".$get_data['uid']."'";
				$edit_data=$this->db->query($s)->result_array(); //new
				// debug(provab_decrypt($edit_data[0]['password']));exit();
				$email= provab_decrypt($edit_data[0]['email']);
				$user_name= provab_decrypt($edit_data[0]['user_name']);
				$uuid= provab_decrypt($edit_data[0]['uuid']);

				$edit_data[0]['email']=$email;
				$edit_data[0]['user_name']=$user_name;
				$edit_data[0]['uuid']=$uuid;
				// debug($edit_data);exit();
				if (valid_array($edit_data) == true) {
					$page_data['form_data'] = $edit_data[0];
				} else {
					redirect('security/log_event');
				}
				
			} elseif (valid_array($page_data['form_data']) == true && (check_default_edit_privilege($get_data['uid']) || super_privilege())) {
				/** AUTOMATE VALIDATOR **/

				$page_data['form_data']['language_preference'] = 'english';
				//$this->user_page->set_auto_validator();
				$this->form_validation->set_rules('title', 'Title', 'trim|required|min_length[1]|max_length[4]');
				$this->form_validation->set_rules('first_name', 'First Name', 'trim|required|min_length[2]|max_length[45]|xss_clean');
				$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required|min_length[1]|max_length[45]|xss_clean');
				$this->form_validation->set_rules('country_code', 'Country Code', 'trim|required|min_length[1]|max_length[3]');
				$this->form_validation->set_rules('phone', 'Mobile Number', 'trim|required|min_length[8]|max_length[15]|numeric');
				$this->form_validation->set_rules('address', 'Address', 'trim|required|min_length[5]|max_length[500]|xss_clean');
				$this->form_validation->set_rules('date_of_birth', 'Date of Birth', 'trim|min_length[5]|xss_clean');
				$this->form_validation->set_rules('user_id', 'UserId', 'trim|min_length[1]|max_length[10]|numeric');
				if ($this->form_validation->run()) {
					if (intval($get_data['uid']) === intval($page_data['form_data']['user_id']) && intval($page_data['form_data']['user_id']) > 0) {

						//Application Logger
						$notification_users = $this->user_model->get_admin_user_id();
						$remarks = $page_data['form_data']['first_name'].' Updated Profile Details';
						$action_query_string = array();
						$action_query_string['user_id'] = $this->entity_user_id;
						$action_query_string['uuid'] = $this->entity_uuid;
						$action_query_string['user_type'] = CORPORATE_USER;
						$this->application_logger->profile_update($page_data['form_data']['first_name'], $remarks, $action_query_string, array(), $this->entity_user_id, $notification_users);

						//Update Data -- LETS UNSET POSTED DATA
						unset($page_data['form_data']['FID']);
						unset($page_data['form_data']['email']);
						unset($page_data['form_data']['uuid']);
						$user_id = intval($page_data['form_data']['user_id']);
						unset($page_data['form_data']['user_id']);
						$page_data['form_data']['date_of_birth'] = date('Y-m-d', strtotime($page_data['form_data']['date_of_birth']));
						

					$page_data['form_data']['pan_no'] = $page_data['form_data']['company_reg_id'];
					$page_data['form_data']['gst_no'] = $page_data['form_data']['travel_licence_no']; 
					$page_data['form_data']['pin_code'] = $page_data['form_data']['postal_code']; 
					$page_data['form_data']['comp_website_link'] = $page_data['form_data']['comp_website_link'];  
					$page_data['form_data']['comp_email'] = $page_data['form_data']['comp_email']; 

					unset($page_data['form_data']['company_reg_id']);
					unset($page_data['form_data']['travel_licence_no']);
					unset($page_data['form_data']['postal_code']); 

 					// debug($page_data['form_data']);exit();
						$this->custom_db->update_record('user', $page_data['form_data'], array('user_id' => $user_id));
						//set_update_message();
						$this->session->set_flashdata(array('message' => 'AL00334', 'type' => SUCCESS_MESSAGE));
						//FILE UPLOAD
						if (valid_array($_FILES) == true and $_FILES['image']['error'] == 0 and $_FILES['image']['size'] > 0) {
							$config['upload_path'] = $this->template->domain_image_upload_path();
							$config['allowed_types'] = '*';
							$config['file_name'] = time();
							$config['max_size'] = '1000000';
							$config['max_width']  = '';
							$config['max_height']  = '';
							$config['remove_spaces']  = false;
							//UPDATE
							$temp_record = $this->custom_db->single_table_records('user', 'image', array('user_id' => $user_id));
							$icon = $temp_record['data'][0]['image'];
							//DELETE OLD FILES
							if (empty($icon) == false) {
								$temp_profile_image = $this->template->domain_image_full_path($icon);//GETTING FILE PATH
								if (file_exists($temp_profile_image)) {
									unlink($temp_profile_image);
								}
							}
							//UPLOAD IMAGE
							$this->load->library('upload', $config);
							if ( ! $this->upload->do_upload('image')) {
								echo $this->upload->display_errors();
							} else {
								$image_data =  $this->upload->data();
							}
							$this->custom_db->update_record('user', array('image' => $image_data['file_name']), array('user_id' => $user_id));
						}
						if (valid_array($_FILES) == true and $_FILES['logo']['error'] == 0 and $_FILES['logo']['size'] > 0) {
							if( function_exists( "check_mime_image_type" ) ) {
							    if ( !check_mime_image_type( $_FILES['logo']['tmp_name'] ) ) {
							    	echo "Please select the image files only (gif|jpg|png|jpeg)"; exit;
							    }
							}

							$config['upload_path'] = $this->template->domain_image_upload_path();
							$config['allowed_types'] = 'gif|jpg|png|jpeg';
							$config['file_name'] = time();
							$config['encrypt_name'] =TRUE;
							$config['max_size'] = MAX_DOMAIN_LOGO_SIZE;
							$config['max_width']  = MAX_DOMAIN_LOGO_WIDTH;
							$config['max_height']  = MAX_DOMAIN_LOGO_HEIGHT;
							$config['remove_spaces']  = false;
							//UPDATE
							$temp_record = $this->custom_db->single_table_records('corporate_user_details', 'logo', array('user_oid' => $user_id));
							$icon = $temp_record['data'][0]['logo'];
							//DELETE OLD FILES
							if (empty($icon) == false) {
								$temp_profile_image = $this->template->domain_image_full_path($icon);//GETTING FILE PATH
								if (file_exists($temp_profile_image)) {
									unlink($temp_profile_image);
								}
							}
							//UPLOAD IMAGE
							$this->load->library('upload', $config);
							if ( ! $this->upload->do_upload('logo')) {
								$message = $this->upload->display_errors();
								if($message == '<p>The filetype you are attempting to upload is not allowed.</p>'){
									$this->session->set_flashdata(array('message' => 'AL005', 'type' => FAILURE_MESSAGE));
								}
							} else {
								$image_data =  $this->upload->data();
							}
							$this->custom_db->update_record('corporate_user_details', array('logo' => @$image_data['file_name']), array('user_oid' => $user_id));
						}
						refresh();
					} else {
						redirect('security/log_event');
					}
				}
			}
			// echo $mobile_code;exit;
			$page_data['country_code_list'] = $this->db_cache_api->get_country_code_list();
			$country_code = $this->db_cache_api->get_country_code_list_profile();
			$mobile_code = $this->db_cache_api->get_mobile_code($page_data['form_data']['country_code']);
			// debug(12);exit();
			$page_data['mobile_code'] = $mobile_code;
			$phone_code_array = array();
			foreach($country_code['data'] as $c_key => $c_value){
				$phone_code_array[$c_value['origin']] = $c_value['name'].' '.$c_value['country_code'];
				
			}
			// debug($phone_code_array);exit;
			$page_data['phone_code_array'] = $phone_code_array;
			$page_data['country_list'] = $this->db_cache_api->get_country_list();
			$page_data['state_list'] = $this->db_cache_api->get_state_list();



			// debug($page_data);
			// die;
			$this->template->view('user/account', $page_data);
		} else {
			redirect('security/log_event');
		}
	}
	/**
	 * Agent Registration
	 */

function agentRegister()
	{

		$page_data['form_data'] = $this->input->post();
		// debug($page_data);die;
		if(valid_array($page_data['form_data']) == true){
			
			$page_data['form_data']['language_preference'] = 'english';
			$this->form_validation->set_rules('company_name', 'Company', 'trim|required|min_length[2]|max_length[45]|xss_clean');
			$this->form_validation->set_rules('title', 'Title', 'trim|required|min_length[1]|max_length[4]');
			$this->form_validation->set_rules('first_name', 'FirstName', 'trim|required|min_length[2]|max_length[45]|xss_clean');
			$this->form_validation->set_rules('last_name', 'LastName', 'trim|required|min_length[1]|max_length[45]|xss_clean');
			$this->form_validation->set_rules('email', 'Email', 'valid_email|required|max_length[80]|callback_useremail_check');
			$this->form_validation->set_rules('user_name', 'Username', 'valid_email|required|max_length[80]|callback_username_check');
			$this->form_validation->set_rules('password', 'Password', 'matches[password_c]|min_length[5]|max_length[45]|required|callback_valid_password');
			$this->form_validation->set_rules('password_c', 'Confirm');
			$this->form_validation->set_rules('country_code', 'CountryCode', 'trim|required|min_length[1]|max_length[6]');
			$this->form_validation->set_rules('phone', 'Mobile', 'trim|required|min_length[8]|max_length[10]|numeric');
			$this->form_validation->set_rules('office_phone', 'Phone', 'trim|required|min_length[7]|max_length[10]|numeric');
			$this->form_validation->set_rules('address', 'Address', 'trim|required|max_length[500]|xss_clean');
			$this->form_validation->set_rules('city', 'City Name', 'trim|required');
			$this->form_validation->set_rules('country', 'Country Name', 'trim|required');
			$this->form_validation->set_rules('term_condition', 'Term And condition', 'trim|required');
			$this->form_validation->set_rules('pin_code', 'Pincode', 'trim|required');
			$this->form_validation->set_rules('pan_number', 'Company Reg No', 'trim|required');
			$this->form_validation->set_rules('pan_holdername', 'Travel Licence Number', 'trim|required');
			$this->form_validation->set_rules('comp_website_link', 'Website Link', 'trim|required');
			$this->form_validation->set_rules('comp_website_link', 'Website Link', 'trim|required');
			$this->form_validation->set_rules('comp_email', 'Company Email', 'trim|required');

			
			if ($this->form_validation->run()) {

			$image_data = array();
				// FILE UPLOAD
				if (valid_array ( $_FILES ) == true and $_FILES ['panimage'] ['error'] == 0 and $_FILES ['gstimage'] ['error'] == 0 and $_FILES ['panimage'] ['size'] > 0 and $_FILES ['gstimage'] ['size'] > 0) {
					$img_name = 'panimage-'.time();
					if( function_exists( "check_mime_image_type" ) ) {
					    if (!check_mime_image_type( $_FILES['panimage']['tmp_name'])  && !check_mime_image_type( $_FILES['gstimage']['tmp_name']) ) {
					    	echo "Please select the image files only (gif|jpg|png|jpeg)"; exit;
					    }
					}
					$config ['upload_path'] = $this->template->domain_pan_upload_path ();
					$temp_panfile_name = $_FILES ['panimage'] ['name'];
					$temp_gstfile_name = $_FILES ['gstimage'] ['name'];
					$config['allowed_types'] = 'gif|jpg|png|jpeg';
					$config ['file_name'] ='IMG-'.$img_name;
					$config['max_size'] = MAX_DOMAIN_LOGO_SIZE;
					$config['max_width']  = MAX_DOMAIN_LOGO_WIDTH;
					$config['max_height']  = MAX_DOMAIN_LOGO_HEIGHT;
					$config ['remove_spaces'] = false;
					// UPLOAD IMAGE
					$this->load->library ( 'upload', $config );
					$this->upload->initialize ( $config );
					if (! $this->upload->do_upload ( 'panimage' )) {
						echo $this->upload->display_errors ();
					} else {
						$image_data = $this->upload->data ();
					}

					if (! $this->upload->do_upload ( 'gstimage' )) {
						echo $this->upload->display_errors ();
					} else {
						$image_data1 = $this->upload->data ();
					}
				}
				$page_data['form_data']['panimage'] = (empty($image_data ['file_name']) == false ? $image_data ['file_name'] : '');
				$page_data['form_data']['gstimage'] = (empty($image_data1 ['file_name']) == false ? $image_data1 ['file_name'] : '');


// debug($page_data);
// die;
					//unset($page_data['form_data']['password_c']);
					$page_data_arr['form_data']['uuid'] =provab_encrypt(PROJECT_PREFIX.time());
					$page_data_arr['form_data']['password'] =  provab_encrypt(md5(trim($page_data['form_data']['password'])));
					$page_data_arr['form_data']['title'] = $page_data['form_data']['title'];
					$page_data_arr['form_data']['user_type'] = B2B_USER;
					$page_data_arr['form_data']['created_datetime'] = date("Y-m-d h:i:sa");
					$page_data_arr['form_data']['domain_list_fk'] = intval(get_domain_auth_id());
					$page_data_arr['form_data']['status'] = FAILURE_STATUS;
					$page_data_arr['form_data']['first_name'] = $page_data['form_data']['first_name'];
					$page_data_arr['form_data']['last_name'] = $page_data['form_data']['last_name'];
					$page_data_arr['form_data']['country_code'] =$page_data['form_data']['country_code'];

					$page_data_arr['form_data']['phone'] =$page_data['form_data']['phone'];
					$page_data_arr['form_data']['email'] =provab_encrypt(trim($page_data['form_data']['email']));
					$page_data_arr['form_data']['agency_name'] =$page_data['form_data']['company_name'];
					$page_data_arr['form_data']['pan_number'] = @$page_data['form_data']['pan_number'];
					$page_data_arr['form_data']['pan_holdername'] = @$page_data['form_data']['pan_holdername'];
					$page_data_arr['form_data']['address'] =$page_data['form_data']['address'];

					$page_data_arr['form_data']['country_name'] =$page_data['form_data']['country'];
					$page_data_arr['form_data']['city'] =$page_data['form_data']['city'];
					$page_data_arr['form_data']['state'] =@$page_data['form_data']['state'];
					$page_data_arr['form_data']['pin_code'] =$page_data['form_data']['pin_code'];
					$page_data_arr['form_data']['office_phone'] =$page_data['form_data']['office_phone'];

					$page_data_arr['form_data']['user_name'] =provab_encrypt($page_data['form_data']['user_name']);
					$page_data_arr['form_data']['creation_source'] = 'portal';
					$page_data_arr['form_data']['terms_conditions'] = 1;
					$page_data_arr['form_data']['created_by_id'] = 0;
					$page_data_arr['form_data']['pan_no'] = $page_data['form_data']['pan_number'];
					$page_data_arr['form_data']['gst_no'] = $page_data['form_data']['pan_holdername'];
					$page_data_arr['form_data']['panimage'] = $page_data['form_data']['panimage'];
					$page_data_arr['form_data']['gstimage'] = $page_data['form_data']['gstimage'];
					$insert_id = $this->custom_db->insert_record('user', $page_data_arr['form_data']);
					$insert_id = $insert_id['insert_id'];
					//B2B User Details					
					//get the admin currency
					$b2b_user_details = array();
					$get_admin_currency = $this->custom_db->single_table_records('domain_list','currency_converter_fk',array('domain_key'=>CURRENT_DOMAIN_KEY));
					$b2b_user_details['currency_converter_fk'] = $get_admin_currency['data'][0]['currency_converter_fk'];
					
					
					$image = '';
					$b2b_user_details['user_oid'] = $insert_id;
					$b2b_user_details['logo'] = $image;
					$b2b_user_details['balance'] = 0;
					$b2b_user_details['created_datetime'] = $page_data_arr['form_data']['created_datetime'];
					$this->custom_db->insert_record('b2b_user_details', $b2b_user_details);
					
					$page_data_arr['form_data']['password'] = $page_data['form_data']['password'];//Dont remove
					$data['agent'] = $page_data_arr['form_data'];
					$mail_template = $this->template->isolated_view('agent/agent_template', $data);
					
					$email = provab_decrypt($page_data_arr['form_data']['email']);
					$this->load->library('provab_mailer');
					//$this->provab_mailer->send_mail('sagar@provab.com', 'New-Agent Registered', $mail_template);
					$subject = 'Agent Registration Acknowledgment-www.'.$_SERVER['HTTP_HOST'];
					$mail_status = $this->provab_mailer->send_mail($email, $subject, $mail_template);
					// $data['message'] = $banner;
					
					//Application Logger
					$remarks = $email.' Has Registered From Agent Portal';
					$notification_users = $this->user_model->get_admin_user_id();
					$action_query_string = array();
					$action_query_string['user_id'] = $insert_id;
					$action_query_string['uuid'] = provab_decrypt($page_data_arr['form_data']['uuid']);
					$action_query_string['user_type'] = B2B_USER;
					
					$this->application_logger->registration($email, $remarks, $insert_id, $action_query_string, array(), $notification_users);
					//$smss =	$this->test_sms($page_data['form_data']['first_name'], $page_data['form_data']['phone']);
					
					$this->session->set_flashdata(array('message' => ' Congratulations!! You are successfully registered as an Agent. Admin will activate your account soon.', 'type' => SUCCESS_MESSAGE, 'override_app_msg' => true));
					redirect('user/agentRegister/show');
			}
		}
		$data['message'] = @$banner;
		$temp_record = $this->custom_db->single_table_records('domain_list', '*');
		$data['active_data'] =$temp_record['data'][0];

		$temp_record = $this->custom_db->single_table_records('api_country_list', '*');
		$data['phone_code'] =$temp_record['data'];
		$city_record = $this->custom_db->single_table_records('api_city_list', 'destination',array('country'=>$data['active_data']['api_country_list_fk']));
		$data['city_list'] =$city_record['data'][0];
		$data['country_code_list'] = $this->db_cache_api->get_country_code_list();
		$country_code = $this->db_cache_api->get_country_code_list_profile();
		// debug($country_code);exit;
		$phone_code_array = array();
		foreach($country_code['data'] as $c_key => $c_value){
			$phone_code_array[$c_value['origin']] = $c_value['name'].' '.$c_value['country_code'];
			// debug($phone_code_array);exit;
			
		}
		// debug($phone_code_array);exit;
		$data['phone_code_array'] = $phone_code_array;

		$data['country_list'] = $this->db_cache_api->get_country_list();
		$data['state_list'] = $this->db_cache_api->get_state_list();
		// debug($data['state_list']);die;
		// echo "csdsdsdd";
		// debug($data);die;
		$this->template->view('agent/agent_register', $data);
	}


	
	function agentRegister_old()
	{
		
		$page_data['form_data'] = $this->input->post();
// debug($page_data);
// die;
		if(valid_array($page_data['form_data']) == true){
			
			$page_data['form_data']['language_preference'] = 'english';
			$this->form_validation->set_rules('company_name', 'Company', 'trim|required|min_length[2]|max_length[45]|xss_clean');
			$this->form_validation->set_rules('title', 'Title', 'trim|required|min_length[1]|max_length[4]');
			$this->form_validation->set_rules('first_name', 'FirstName', 'trim|required|min_length[2]|max_length[45]|xss_clean');
			$this->form_validation->set_rules('last_name', 'LastName', 'trim|required|min_length[1]|max_length[45]|xss_clean');
			$this->form_validation->set_rules('email', 'Email', 'valid_email|required|max_length[80]|callback_useremail_check');
			$this->form_validation->set_rules('user_name', 'Username', 'valid_email|required|max_length[80]|callback_username_check');
			$this->form_validation->set_rules('password', 'Password', 'matches[password_c]|min_length[5]|max_length[45]|required|callback_valid_password');
			$this->form_validation->set_rules('password_c', 'Confirm');
			$this->form_validation->set_rules('country_code', 'CountryCode', 'trim|required|min_length[1]|max_length[6]');
			$this->form_validation->set_rules('phone', 'Mobile', 'trim|required|min_length[7]|max_length[10]|numeric');
			$this->form_validation->set_rules('office_phone', 'Phone', 'trim|required|min_length[7]|max_length[15]|numeric');
			$this->form_validation->set_rules('address', 'Address', 'trim|required|max_length[500]|xss_clean');
			$this->form_validation->set_rules('city', 'City Name', 'trim|required');
			$this->form_validation->set_rules('country', 'Country Name', 'trim|required');
			$this->form_validation->set_rules('term_condition', 'Term And condition', 'trim|required');
			$this->form_validation->set_rules('pin_code', 'Pincode', 'trim|required');
			
			if ($this->form_validation->run()) {
				// echo "in";
					//unset($page_data['form_data']['password_c']);
					$page_data_arr['form_data']['uuid'] =provab_encrypt(PROJECT_PREFIX.time());
					$page_data_arr['form_data']['password'] =  provab_encrypt(md5(trim($page_data['form_data']['password'])));
					$page_data_arr['form_data']['title'] = $page_data['form_data']['title'];
					$page_data_arr['form_data']['user_type'] = B2B_USER;
					$page_data_arr['form_data']['created_datetime'] = date("Y-m-d h:i:sa");
					$page_data_arr['form_data']['domain_list_fk'] = intval(get_domain_auth_id());
					$page_data_arr['form_data']['status'] = FAILURE_STATUS;
					$page_data_arr['form_data']['first_name'] = $page_data['form_data']['first_name'];
					$page_data_arr['form_data']['last_name'] = $page_data['form_data']['last_name'];
					$page_data_arr['form_data']['country_code'] =$page_data['form_data']['country_code'];

					$page_data_arr['form_data']['phone'] =$page_data['form_data']['phone'];
					$page_data_arr['form_data']['email'] =provab_encrypt(trim($page_data['form_data']['email']));
					$page_data_arr['form_data']['agency_name'] =$page_data['form_data']['company_name'];
					$page_data_arr['form_data']['pan_number'] = @$page_data['form_data']['pan_number'];
					$page_data_arr['form_data']['pan_holdername'] = @$page_data['form_data']['pan_holdername'];
					$page_data_arr['form_data']['address'] =$page_data['form_data']['address'];

					$page_data_arr['form_data']['country_name'] =$page_data['form_data']['country'];
					$page_data_arr['form_data']['city'] =$page_data['form_data']['city'];
					$page_data_arr['form_data']['pin_code'] =$page_data['form_data']['pin_code'];
					$page_data_arr['form_data']['office_phone'] =$page_data['form_data']['office_phone'];

					$page_data_arr['form_data']['user_name'] =provab_encrypt($page_data['form_data']['user_name']);
					$page_data_arr['form_data']['creation_source'] = 'portal';
					$page_data_arr['form_data']['terms_conditions'] = 1;
					$page_data_arr['form_data']['created_by_id'] = 0;
					// debug($page_data);
					// die;
					$insert_id = $this->custom_db->insert_record('user', $page_data_arr['form_data']);
					$insert_id = $insert_id['insert_id'];
					//B2B User Details					
					//get the admin currency
					$b2b_user_details = array();
					$get_admin_currency = $this->custom_db->single_table_records('domain_list','currency_converter_fk',array('domain_key'=>CURRENT_DOMAIN_KEY));
					$b2b_user_details['currency_converter_fk'] = $get_admin_currency['data'][0]['currency_converter_fk'];
					
					
					$image = '';
					$b2b_user_details['user_oid'] = $insert_id;
					$b2b_user_details['logo'] = $image;
					$b2b_user_details['balance'] = 0;
					$b2b_user_details['created_datetime'] = $page_data_arr['form_data']['created_datetime'];
					$this->custom_db->insert_record('b2b_user_details', $b2b_user_details);
					
					$page_data_arr['form_data']['password'] = $page_data['form_data']['password'];//Dont remove
					$data['agent'] = $page_data_arr['form_data'];
					$mail_template = $this->template->isolated_view('agent/agent_template', $data);
					// debug($b2b_user_details);
					$email = provab_decrypt($page_data_arr['form_data']['email']);
					$this->load->library('provab_mailer');
					//$this->provab_mailer->send_mail('sagar@provab.com', 'New-Agent Registered', $mail_template);
					$subject = 'Agent Registration Acknowledgment-www.'.$_SERVER['HTTP_HOST'];
					$mail_status = $this->provab_mailer->send_mail($email, $subject, $mail_template);
					// $data['message'] = $banner;
					// debug($subject);
					//Application Logger
					// $remarks = $email.' Has Registered From Agent Portal';
					// $notification_users = $this->user_model->get_admin_user_id();
					// debug($notification_users);
					// $action_query_string = array();
					// $action_query_string['user_id'] = $insert_id;
					// $action_query_string['uuid'] = provab_decrypt($page_data_arr['form_data']['uuid']);
					// $action_query_string['user_type'] = B2B_USER;
					// echo "in";
					// debug($email);

					// $this->application_logger->registration($email, $remarks, $insert_id, $action_query_string, array(), $notification_users);
					
					$this->session->set_flashdata(array('message' => ' Congratulations!! You are successfully registered as an Agent. Admin will activate your account soon', 'type' => SUCCESS_MESSAGE, 'override_app_msg' => true));
					// echo "in";
					// die;
					redirect('user/agentRegister/show');
			}
			// echo "in111";
			// die;
		}
		// echo "SDSD";
		// die;
		$data['message'] = @$banner;
		$temp_record = $this->custom_db->single_table_records('domain_list', '*');
		$data['active_data'] =$temp_record['data'][0];

		$temp_record = $this->custom_db->single_table_records('api_country_list', '*');
		$data['phone_code'] =$temp_record['data'];
		$city_record = $this->custom_db->single_table_records('api_city_list', 'destination',array('country'=>$data['active_data']['api_country_list_fk']));
		$data['city_list'] =$city_record['data'][0];
		$data['country_code_list'] = $this->db_cache_api->get_country_code_list();
		$country_code = $this->db_cache_api->get_country_code_list_profile();
		// debug($country_code);exit;
		$phone_code_array = array();
		foreach($country_code['data'] as $c_key => $c_value){
			$phone_code_array[$c_value['origin']] = $c_value['name'].' '.$c_value['country_code'];
			// debug($phone_code_array);exit;
			
		}
		// debug($phone_code_array);exit;
		$data['phone_code_array'] = $phone_code_array;

		$data['country_list'] = $this->db_cache_api->get_country_list();
		// debug($data['phone_code_array']);
		// echo "csdsdsdd";
		// debug($data);die;
		$this->template->view('agent/agent_register', $data);
	}
	public function username_check($name)
	{
		$condition['user_name'] = provab_encrypt($name);
		$condition['user_type'] = B2B_USER;
		$condition['domain_list_fk'] = intval(get_domain_auth_id());
		$data = $this->custom_db->single_table_records('user', 'user_id', $condition);
		if ($data['status'] == SUCCESS_STATUS and valid_array($data['data']) == true) {
			$this->form_validation->set_message(__FUNCTION__, $name.' Is Not Available!!!');
			return FALSE;
		} else {
			return TRUE;
		}
	}
	
	public function useremail_check($name)
	{
		$condition['email'] = provab_encrypt($name);
		$condition['user_type'] = B2B_USER;
		$condition['domain_list_fk'] = intval(get_domain_auth_id());
		$data = $this->custom_db->single_table_records('user', 'user_id', $condition);
		if ($data['status'] == SUCCESS_STATUS and valid_array($data['data']) == true) {
			$this->form_validation->set_message(__FUNCTION__, $name.' Is Not Available!!!');
			return FALSE;
		} else {
			return TRUE;
		}
	}
	public function b2b2b_email_check($name)
	{
		$condition['email'] = provab_encrypt($name);
		$condition['user_type'] = SUB_AGENT;
		$condition['domain_list_fk'] = intval(get_domain_auth_id());
		$data = $this->custom_db->single_table_records('user', 'user_id', $condition);
		if ($data['status'] == SUCCESS_STATUS and valid_array($data['data']) == true) {
			$this->form_validation->set_message(__FUNCTION__, $name.' Is Not Available!!!');
			return FALSE;
		} else {
			return TRUE;
		}
	}

	/**
	 * Logout function for logout from account and unset all the session variables
	 */
	function initilize_logout(){
		if (is_logged_in_user()) {
			$this->general_model->update_login_manager($this->session->userdata(LOGIN_POINTER));
			$this->session->unset_userdata(array(AUTH_USER_POINTER => '',LOGIN_POINTER => '') );
			// added by nithin for unseting the email username
			$this->session->unset_userdata('mail_user');
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

	/**
	 * Function to Change the Password of a User
	 */
	public function change_password()
	{
		$data=array();
		$get_data = $this->input->get();
		if(isset($get_data['uid'])) {
			$user_id = $get_data['uid'];
		} else {
			redirect("general/initilize_logout");
		}
		$page_data['form_data'] = $this->input->post();	
		if(valid_array($page_data['form_data'])==TRUE) {
			// $this->current_page->set_auto_validator();	
			$this->load->library('form_validation');
			$this->form_validation->set_rules('current_password', 'Current Password', 'required|min_length[5]|max_length[45]|callback_password_check');
			$this->form_validation->set_rules('new_password', 'New Password', 'matches[confirm_password]|min_length[5]|max_length[45]|required|callback_valid_password');
			$this->form_validation->set_rules('confirm_password', 'Confirm', 'callback_check_new_password');					
			if ($this->form_validation->run()) {				
				$table_name="user";
				/** Checking New Password and Old Password Are Same OR Not **/
				$condition['password'] = provab_encrypt(md5(trim($this->input->post('current_password'))));
				$condition['user_id'] = $user_id;				
				$check_pwd = $this->custom_db->single_table_records($table_name,'password',$condition);
				if($check_pwd['status'] == true) {
					$condition['password'] = provab_encrypt(md5(trim($this->input->post('current_password'))));
					$condition['user_id'] = $user_id;
					$data['password'] = provab_encrypt(md5(trim($this->input->post('new_password'))));
					$update_res=$this->custom_db->update_record($table_name, $data, $condition);				
					if($update_res)	{
						$this->session->set_flashdata(array('message' => 'Password Changed Successfully', 'type' => SUCCESS_MESSAGE, 'override_app_msg' => true));
						refresh();
					} else 
					{
						$this->session->set_flashdata(array('message' => 'Invalid Current Password', 'type' => ERROR_MESSAGE, 'override_app_msg' => true));
						refresh();
						/*$data['msg'] = 'UL0011';
						 $data['type'] = ERROR_MESSAGE;*/
					}
				} 
				else {
					$this->session->set_flashdata(array('message' => 'Current Password is Wrong', 'type'=>WARNING_MESSAGE, 'override_app_msg' => true));
					refresh();
					//redirect('general/change_password?uid='.urlencode($get_data['uid']));
				}
			}
		}
		$this->template->view('user/change_password', $data);
	}

	/**
	 * Manage Domain Logo
	 * Balu A (25-05-2015) - 26-05-2015
	 */
	function domain_logo()
	{
		$post_data = $this->input->post();
		if(valid_array($post_data) == true && isset($post_data['origin']) == true) {
			$GLOBALS['CI']->template->domain_images();
			if(intval($post_data['origin']) == get_domain_auth_id() && get_domain_auth_id() > 0) {
				$domain_origin = get_domain_auth_id();
				//FILE UPLOAD
				if (valid_array($_FILES) == true and $_FILES['domain_logo']['error'] == 0 and $_FILES['domain_logo']['size'] > 0) {
					
					if( function_exists( "check_mime_image_type" ) ) {
					    if ( !check_mime_image_type( $_FILES['domain_logo']['tmp_name'] ) ) {
					    	echo "Please select the image files only (gif|jpg|png|jpeg)"; exit;
					    }
					}
					$config['upload_path'] = $this->template->domain_image_upload_path();
					$temp_file_name = $_FILES['domain_logo']['name'];
					$config['allowed_types'] = 'gif|jpg|png|jpeg';
					$config['file_name'] = get_domain_key().$temp_file_name;
					$config['max_size'] = MAX_DOMAIN_LOGO_SIZE;
					$config['max_width']  = MAX_DOMAIN_LOGO_WIDTH;
					$config['max_height']  = MAX_DOMAIN_LOGO_HEIGHT;
					$config['remove_spaces']  = false;
					//UPDATE
					$temp_record = $this->custom_db->single_table_records('b2b_user_details', 'logo', array('user_oid' => intval($this->entity_user_id)));
					$domain_logo = $temp_record['data'][0]['logo'];
					//DELETE OLD FILES
					if (empty($domain_logo) == false) {
						$temp_domain_logo = $this->template->domain_image_full_path($domain_logo);//GETTING FILE PATH
						if (file_exists($temp_domain_logo)) {
							unlink($temp_domain_logo);
						}
					}
					//UPLOAD IMAGE
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					if ( ! $this->upload->do_upload('domain_logo')) {
						echo $this->upload->display_errors();
					} else {
						$image_data =  $this->upload->data();
					}
					$this->custom_db->update_record('b2b_user_details', array('logo' => @$image_data['file_name']), array('user_oid' => intval($this->entity_user_id)));
				}
				refresh();
			}

		}
		$temp_details = $this->custom_db->single_table_records('b2b_user_details', 'logo', array('user_oid' => intval($this->entity_user_id)));
		if($temp_details['status'] == true) {
			$page_data['domain_logo'] = $temp_details['data'][0]['logo'];
		} else {
			$page_data['domain_logo'] = '';
		}
		$this->template->view('user/domain_logo', $page_data);
	}
	
	function get_city_data()
	{
		echo 'hi'; die;
		$country_id = $this->input->post('country_id');
		$city_list = $this->custom_db->single_table_records('api_city_list', '*', array('country' => $country_id),0,100000000,array('destination'=>'asc'));
		$options ='';
		$city_list = $city_list['data'];
		foreach ($city_list as $city) {
			$options .="<option value=".$city['origin'].">".$city['destination']."</option>";
		}
		print_r($options);
	}
	/**
	 * Validate the password
	 *
	 * @param string $password
	 *
	 * @return bool
	 */
	public function valid_password($password)
	{
		$password = trim($password);
		$regex_lowercase = '/[a-z]/';
		$regex_uppercase = '/[A-Z]/';
		$regex_number = '/[0-9]/';
		$regex_special = '/[!@#$%^&*()\-_=+{};:,<.>§~]/';
		if (empty($password))
		{
			$this->form_validation->set_message('valid_password', 'The Password field is required.');
			return FALSE;
		}
		if (preg_match_all($regex_lowercase, $password) < 1)
		{
			$this->form_validation->set_message('valid_password', 'The Password field must be at least one lowercase letter.');
			return FALSE;
		}
		if (preg_match_all($regex_uppercase, $password) < 1)
		{
			$this->form_validation->set_message('valid_password', 'The Password field must be at least one uppercase letter.');
			return FALSE;
		}
		if (preg_match_all($regex_number, $password) < 1)
		{
			$this->form_validation->set_message('valid_password', 'The Password field must have at least one number.');
			return FALSE;
		}
		if (preg_match_all($regex_special, $password) < 1)
		{
			$this->form_validation->set_message('valid_password', 'The Password field must have at least one special character.' . ' ' . htmlentities('!@#$%^&*()\-_=+{};:,<.>§~'));
			return FALSE;
		}
		if (strlen($password) < 5)
		{
			$this->form_validation->set_message('valid_password', 'The Password field must be at least 5 characters in length.');
			return FALSE;
		}
		if (strlen($password) > 32)
		{
			$this->form_validation->set_message('valid_password', 'The Password field cannot exceed 32 characters in length.');
			return FALSE;
		}
		return TRUE;
	}
	function test_sms($name, $phone)
	{
		$data ['name'] = $name;
		$data ['phone'] = $phone;
		// Sms config & Checkpoint
		if (active_sms_checkpoint ( 'registration' )) 
		{
			$text = "Dear ".$data ['name'].", Thank you for registering with Alkhaleej Tours. Your account will be activated soon.";			
			$url = "http://www.smsalert.co.in/api/push.json?apikey=5cd41e9565119&sender=Vivanc&mobileno=".$data['phone']."&text=".urlencode($text)."&shortenurl=1";
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$output = curl_exec($ch);
			curl_close($ch);
			return true;
		}
	}

		function emailvalidate()
		{

			

		$email= $_POST['email'];

		 
		$s="select * from user where user_type='8' AND email=".'"'.provab_encrypt($email).'"';
		$data=$this->db->query($s)->result_array();
		if(count($data)>0)
		{
		$res=0;
		$msg='exists';
		}
		else
		{
		$res=1;
		$msg='unique';
		}

		echo $msg;
		die;
		}



	function supplierRegister()
	{
// 		ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
		
		$page_data['form_data'] = $this->input->post();
		 
		 // $crs_type=implode(',', $page_data ['form_data']['user_type']);
		$crs_type=$page_data ['form_data']['user_type'][0];

		 unset($page_data ['form_data']['user_type']);
		// debug($page_data);exit;
		 
		 	$service_type=$page_data['form_data']['service_type'];
		 	$service_type = implode(',',$service_type);
//debug($service_type);exit;
			if(valid_array($page_data['form_data']) == true){
				//die(provab_encrypt($page_data['form_data']['email']));
				$emaill = provab_encrypt($page_data['form_data']['email']);


				$this->db->select('*');
				$this->db->where('email',$emaill);
				$this->db->where('user_type',8);
				$query = $this->db->get('user');

				$num = $query->num_rows();
				
				if($num > 0){


				$this->session->set_flashdata(array('error_message' => 'Email id already registered.', 'type' => ERROR_MESSAGE, 'override_app_msg' => true));
		
				redirect('user/supplierRegister/show');
				die;

				}


			$page_data['form_data']['language_preference'] = 'english';
			$this->form_validation->set_rules('company_name', 'Company', 'trim|required|min_length[2]|max_length[50]');
			$this->form_validation->set_rules('title', 'Title', 'trim|required|min_length[1]|max_length[4]');
			$this->form_validation->set_rules('first_name', 'FirstName', 'trim|required|min_length[2]|max_length[45]|xss_clean');
			$this->form_validation->set_rules('last_name', 'LastName', 'trim|required|min_length[1]|max_length[45]|xss_clean');
			$this->form_validation->set_rules('email', 'Email', 'valid_email|required|max_length[80]|callback_useremail_check');
			$this->form_validation->set_rules('user_name', 'Username', 'valid_email|required|max_length[80]|callback_username_check');
			$this->form_validation->set_rules('password', 'Password', 'matches[password_c]|min_length[5]|max_length[45]|required|callback_valid_password');
			$this->form_validation->set_rules('password_c', 'Confirm');
			$this->form_validation->set_rules('country_code', 'CountryCode', 'trim|required|min_length[1]|max_length[6]');
			$this->form_validation->set_rules('phone', 'Mobile', 'trim|required|min_length[2]|max_length[15]|numeric');
			$this->form_validation->set_rules('office_phone', 'Phone', 'trim|required|min_length[2]|max_length[15]|numeric');
			$this->form_validation->set_rules('address', 'Address', 'trim|required|max_length[500]|xss_clean');
			$this->form_validation->set_rules('city', 'City Name', 'trim|required');
			$this->form_validation->set_rules('country', 'Country Name', 'trim|required');
			$this->form_validation->set_rules('term_condition', 'Term And condition', 'trim|required');
			$this->form_validation->set_rules('pin_code', 'Pincode', 'trim|required');
			$this->form_validation->set_rules('user_type[]','Privileges', 'required');
			$this->form_validation->set_rules('pan_number','Company Reg No ', 'required');
			$this->form_validation->set_rules('comp_website_link','Company website link ', 'required');
			$this->form_validation->set_rules('comp_email','Company email ', 'required');
			$this->form_validation->set_rules('pan_holdername','Travel Licence ', 'trim');
		//	echo "asd".$this->form_validation->run();die;
			if (true) {
			//	echo "hjhj";die;
			$image_data = array();
				// FILE UPLOAD
				if (valid_array ( $_FILES ) == true and $_FILES ['panimage'] ['error'] == 0 and $_FILES ['gstimage'] ['error'] == 0 and $_FILES ['panimage'] ['size'] > 0 and $_FILES ['gstimage'] ['size'] > 0) {
					$img_name = 'doc_image-'.time();
					/*if( function_exists( "check_mime_image_type" ) ) {
					    if (!check_mime_image_type( $_FILES['panimage']['tmp_name'])  && !check_mime_image_type( $_FILES['gstimage']['tmp_name']) ) {
					    	echo "Please select the image files only (gif|jpg|png|jpeg)"; exit;
					    }
					}*/
					$config ['upload_path'] = $this->template->domain_image_upload_path ();
					// debug($config ['upload_path']);exit();
					$temp_panfile_name = $_FILES ['panimage'] ['name'];
					$temp_gstfile_name = $_FILES ['gstimage'] ['name'];
					$temp_Drivefile_name = $_FILES ['UploadDriver']['name'];
					$config['allowed_types'] = 'gif|jpg|png|jpeg|docx|doc|txt|pdf';
					$config ['file_name'] ='IMG-'.$img_name;
					// $config['max_size'] = MAX_DOMAIN_LOGO_SIZE;
					// $config['max_width']  = MAX_DOMAIN_LOGO_WIDTH;
					// $config['max_height']  = MAX_DOMAIN_LOGO_HEIGHT;
					$config ['remove_spaces'] = false;
					// UPLOAD IMAGE
					$this->load->library ( 'upload', $config );
					$this->upload->initialize ( $config );
					if (! $this->upload->do_upload ( 'panimage' )) {
						echo $this->upload->display_errors ();
					} else {
						$image_data = $this->upload->data ();
					}

					if (! $this->upload->do_upload ( 'gstimage' )) {
						echo $this->upload->display_errors ();
					} else {
						$image_data1 = $this->upload->data ();
					}
					if (! $this->upload->do_upload ( 'UploadDriver' )) {
						echo $this->upload->display_errors ();
					} else {
						$image_data2 = $this->upload->data ();
					}
				}
				$page_data['form_data']['panimage'] = (empty($image_data ['file_name']) == false ? $image_data ['file_name'] : '');
				$page_data['form_data']['gstimage'] = (empty($image_data1 ['file_name']) == false ? $image_data1 ['file_name'] : '');
                $page_data['form_data']['driveimage'] = (empty($image_data2 ['file_name']) == false ? $image_data2 ['file_name'] : '');

// debug($page_data);
// die;
					//unset($page_data['form_data']['password_c']);
					$page_data_arr['form_data']['uuid'] =provab_encrypt(PROJECT_PREFIX.time());
					$page_data_arr['form_data']['password'] =  provab_encrypt(md5(trim($page_data['form_data']['password'])));
					$page_data_arr['form_data']['title'] = $page_data['form_data']['title'];
					$page_data_arr['form_data']['user_type'] = SUPPLIER;
					$page_data_arr['form_data']['created_datetime'] = date("Y-m-d h:i:sa");
					$page_data_arr['form_data']['domain_list_fk'] = intval(get_domain_auth_id());
					$page_data_arr['form_data']['status'] = FAILURE_STATUS;
					$page_data_arr['form_data']['first_name'] = $page_data['form_data']['first_name'];
					$page_data_arr['form_data']['last_name'] = $page_data['form_data']['last_name'];
					$page_data_arr['form_data']['country_code'] =$page_data['form_data']['country_code'];
					$page_data_arr['form_data']['ofc_country_code'] =$page_data['form_data']['ofc_country_code'];

					$page_data_arr['form_data']['state_txt'] =$page_data['form_data']['state'];

					$page_data_arr['form_data']['phone'] =$page_data['form_data']['phone'];
					$page_data_arr['form_data']['email'] =provab_encrypt(trim($page_data['form_data']['email']));
					$page_data_arr['form_data']['agency_name'] =$page_data['form_data']['company_name'];
					$page_data_arr['form_data']['pan_number'] = @$page_data['form_data']['pan_number'];
					$page_data_arr['form_data']['pan_holdername'] = @$page_data['form_data']['pan_holdername'];
					$page_data_arr['form_data']['address'] =$page_data['form_data']['address'];
                	$page_data_arr['form_data']['company_type'] = @$page_data['form_data']['demo02'];
					$page_data_arr['form_data']['country_name'] =$page_data['form_data']['country'];
					$page_data_arr['form_data']['city'] =$page_data['form_data']['city'];
					$page_data_arr['form_data']['state'] =@$page_data['form_data']['state'];
					$page_data_arr['form_data']['pin_code'] =$page_data['form_data']['pin_code'];
					$page_data_arr['form_data']['office_phone'] =$page_data['form_data']['office_phone'];

					$page_data_arr['form_data']['user_name'] =provab_encrypt($page_data['form_data']['user_name']);
					$page_data_arr['form_data']['creation_source'] = 'portal';
					$page_data_arr['form_data']['terms_conditions'] = 1;
					$page_data_arr['form_data']['created_by_id'] = 0;
					$page_data_arr['form_data']['pan_no'] = $page_data['form_data']['pan_number'];
					$page_data_arr['form_data']['gst_no'] = $page_data['form_data']['pan_holdername'];
					$page_data_arr['form_data']['panimage'] = $page_data['form_data']['panimage'];
					$page_data_arr['form_data']['gstimage'] = $page_data['form_data']['gstimage'];
	                $page_data_arr['form_data']['driveimage'] = @$page_data['form_data']['driveimage'];
					$page_data_arr['form_data']['comp_website_link'] = $page_data['form_data']['comp_website_link'];
					$page_data_arr['form_data']['comp_email'] = $page_data['form_data']['comp_email'];

					$page_data_arr['form_data']['service_type'] = $service_type;
					$page_data_arr['form_data']['tour_company_name'] = $page_data['form_data']['tour_company_name'];
					$page_data_arr['form_data']['tour_authorised_person'] = $page_data['form_data']['tour_authorised_person'];
					$page_data_arr['form_data']['tour_contact_person'] = $page_data['form_data']['tour_contact_person'];
					$page_data_arr['form_data']['tour_supplier_site'] = $page_data['form_data']['tour_supplier_site'];
					$page_data_arr['form_data']['tour_country'] = $page_data['form_data']['tour_country'];
					$page_data_arr['form_data']['tour_business_type'] = $page_data['form_data']['tour_business_type'];
					
					$page_data_arr['form_data']['hotel_company_name'] = $page_data['form_data']['hotel_company_name'];
					$page_data_arr['form_data']['hotel_authorised_person'] = $page_data['form_data']['hotel_authorised_person'];
					$page_data_arr['form_data']['hotel_contact_person'] = $page_data['form_data']['hotel_contact_person'];
					$page_data_arr['form_data']['hotel_star_rating'] = $page_data['form_data']['hotel_star_rating'];
					$page_data_arr['form_data']['hotel_num_room'] = $page_data['form_data']['hotel_num_room'];
					$page_data_arr['form_data']['hotel_supplier_site'] = $page_data['form_data']['hotel_supplier_site'];
					$page_data_arr['form_data']['hotel_country'] = $page_data['form_data']['hotel_country'];
					$page_data_arr['form_data']['hotel_business_type'] = $page_data['form_data']['hotel_business_type'];


					$page_data_arr['form_data']['transfer_company_name'] = $page_data['form_data']['transfer_company_name'];
					$page_data_arr['form_data']['transfer_authorised_person'] = $page_data['form_data']['transfer_authorised_person'];
					$page_data_arr['form_data']['transfer_contact_person'] = $page_data['form_data']['transfer_contact_person'];
					$page_data_arr['form_data']['transfer_supplier_site'] = $page_data['form_data']['transfer_supplier_site'];
					$page_data_arr['form_data']['transfer_country'] = $page_data['form_data']['transfer_country'];
					$page_data_arr['form_data']['transfer_business_type'] = $page_data['form_data']['transfer_business_type'];

					$page_data_arr['form_data']['car_company_name'] = $page_data['form_data']['car_company_name'];
					$page_data_arr['form_data']['car_authorised_person'] = $page_data['form_data']['car_authorised_person'];
					$page_data_arr['form_data']['car_contact_person'] = $page_data['form_data']['car_contact_person'];
					$page_data_arr['form_data']['car_supplier_site'] = $page_data['form_data']['car_supplier_site'];
					$page_data_arr['form_data']['car_country'] = $page_data['form_data']['car_country'];
					$page_data_arr['form_data']['car_business_type'] = $page_data['form_data']['car_business_type'];


					$page_data_arr['form_data']['jet_company_name'] = $page_data['form_data']['jet_company_name'];
					$page_data_arr['form_data']['jet_authorised_person'] = $page_data['form_data']['jet_authorised_person'];
					$page_data_arr['form_data']['jet_contact_person'] = $page_data['form_data']['jet_contact_person'];
					$page_data_arr['form_data']['jet_supplier_site'] = $page_data['form_data']['jet_supplier_site'];
					$page_data_arr['form_data']['jet_country'] = $page_data['form_data']['jet_country'];
					$page_data_arr['form_data']['jet_business_type'] = $page_data['form_data']['jet_business_type'];

					$page_data_arr['form_data']['tour_operator'] = $page_data['form_data']['tour_operator'];
					$page_data_arr['form_data']['transfer_type'] = $page_data['form_data']['transfer_type'];
					$page_data_arr['form_data']['transfer_quantity'] = $page_data['form_data']['transfer_quantity'];
					$page_data_arr['form_data']['car_type'] = $page_data['form_data']['car_type'];
					$page_data_arr['form_data']['car_quantity'] = $page_data['form_data']['car_quantity'];
					$page_data_arr['form_data']['jet_type'] = $page_data['form_data']['jet_type'];
					$page_data_arr['form_data']['jet_quantity'] = $page_data['form_data']['jet_quantity'];

					
				 //debug($page_data_arr['form_data']);exit();
					$insert_id = $this->custom_db->insert_record('user', $page_data_arr['form_data']);

					// debug(	$insert_id);
					// debug($this->db->last_query());exit;
					$insert_id = $insert_id['insert_id'];
					$data=array(
									'supplier_id'=>$insert_id,
									'supplier_privailage'=>$crs_type); 	
					// debug($data);exit();
					if($insert_id >0)
					{						
						$data=array(
									'supplier_id'=>$insert_id,
									'supplier_privailage'=>$crs_type); 					

						$this->custom_db->insert_record ( 'supplier_crs_privilage', $data);
					}


					

					//SUPPLIER User Details					
					//get the admin currency
					/*$b2b_user_details = array();
					$get_admin_currency = $this->custom_db->single_table_records('domain_list','currency_converter_fk',array('domain_key'=>CURRENT_DOMAIN_KEY));
					$b2b_user_details['currency_converter_fk'] = $get_admin_currency['data'][0]['currency_converter_fk'];
					
					
					$image = '';
					$b2b_user_details['user_oid'] = $insert_id;
					$b2b_user_details['logo'] = $image;
					$b2b_user_details['balance'] = 0;
					$b2b_user_details['created_datetime'] = $page_data_arr['form_data']['created_datetime'];
					$this->custom_db->insert_record('b2b_user_details', $b2b_user_details);*/
					    $original = $insert_id;
					    //debug($original);exit();
						$encoded_data = rand(100,999).base64_encode($original);
					//	$url = base_url().'index.php/general/activate_account_status?origin='.$encoded_data;
						//$data['activation_link'] = $url;
					$page_data_arr['form_data']['password'] = $page_data['form_data']['password'];//Dont remove
					$data['agent'] = $page_data_arr['form_data'];
					//$mail_template = $this->template->isolated_view('user/agent_template', $data);
					
					$email = provab_decrypt($page_data_arr['form_data']['email']);
					$this->load->library('provab_mailer');
					// $this->provab_mailer->send_mail('chalapathi.provab@gmail.com', 'New-Supplier Registered', $mail_template);
					$subject = 'Supplier Registration Acknowledgment-'.$_SERVER['HTTP_HOST'];
					//$mail_status = $this->provab_mailer->send_mail($email, $subject, $mail_template);

					// $data['message'] = $banner;



					// debug($mail_template);exit;
					
					//Application Logger
					$remarks = $email.' Has Registered From Supplier Portal';
					$notification_users = $this->user_model->get_admin_user_id();
					$action_query_string = array();
					$action_query_string['user_id'] = $insert_id;
					$action_query_string['uuid'] = provab_decrypt($page_data_arr['form_data']['uuid']);
					$action_query_string['user_type'] = SUPPLIER;
					
					$this->application_logger->registration($email, $remarks, $insert_id, $action_query_string, array(), $notification_users);
					// $smss =	$this->test_sms($page_data['form_data']['first_name'], $page_data['form_data']['phone']);
					$this->session->set_flashdata('message', 'Congratulations!! You are successfully registered as an Supplier. Admin will activate your account soon.');
					//$this->session->set_flashdata(array('message' => ' Congratulations!! You are successfully registered as an Supplier. Admin will activate your account soon.', 'type' => SUCCESS_MESSAGE, 'override_app_msg' => true));
					redirect('user/supplierRegister/show');
			}
		}
		
		$data['message'] = @$banner;
		$temp_record = $this->custom_db->single_table_records('domain_list', '*');
		$data['active_data'] =$temp_record['data'][0];

		$temp_record = $this->custom_db->single_table_records('api_country_list', '*');
		$data['phone_code'] =$temp_record['data'];
		$city_record = $this->custom_db->single_table_records('api_city_list', 'destination',array('country'=>$data['active_data']['api_country_list_fk']));
		$data['city_list'] =$city_record['data'][0];
		$data['country_code_list'] = $this->db_cache_api->get_country_code_list();
		$country_code = $this->db_cache_api->get_country_code_list_profile();
		// debug($country_code);exit;
		$phone_code_array = array();
		foreach($country_code['data'] as $c_key => $c_value){
			// $phone_code_array[$c_value['origin']] = $c_value['name'].' '.$c_value['country_code'];
			$phone_code_array[$c_value['country_code']] = $c_value['name'].' '.$c_value['country_code'];
			// debug($phone_code_array);exit;
			
		}
		// debug($phone_code_array);exit;
		$data['phone_code_array'] = $phone_code_array;

		$data['country_list'] = $this->db_cache_api->get_country_list();
		$data['state_list'] = $this->db_cache_api->get_state_list();
		// debug($data['state_list']);die;
		// echo "csdsdsdd";
		// debug($data);die;


		$data['supplier_crs_privilage'] = $this->custom_db->single_table_records('crs_modules'); 
		$this->template->view('user/supplier_register', $data);
	}
//supplier Register

	function supplierRegisterolder()
	{
		
		$page_data['form_data'] = $this->input->post();
		 
		 // $crs_type=implode(',', $page_data ['form_data']['user_type']);
		$crs_type=$page_data ['form_data']['user_type'][0];

		 unset($page_data ['form_data']['user_type']);
		 // debug($page_data);
		 // exit();

			if(valid_array($page_data['form_data']) == true){
				//die(provab_encrypt($page_data['form_data']['email']));
				$emaill = provab_encrypt($page_data['form_data']['email']);


				$this->db->select('*');
				$this->db->where('email',$emaill);
				$this->db->where('user_type',8);
				$query = $this->db->get('user');

				$num = $query->num_rows();
				
				if($num > 0){


				$this->session->set_flashdata(array('error_message' => 'Email id already registered.', 'type' => ERROR_MESSAGE, 'override_app_msg' => true));
		
				redirect('user/supplierRegister/show');
				die;

				}


			$page_data['form_data']['language_preference'] = 'english';
			$this->form_validation->set_rules('company_name', 'Company', 'trim|required|min_length[2]|max_length[50]');
			$this->form_validation->set_rules('title', 'Title', 'trim|required|min_length[1]|max_length[4]');
			$this->form_validation->set_rules('first_name', 'FirstName', 'trim|required|min_length[2]|max_length[45]|xss_clean');
			$this->form_validation->set_rules('last_name', 'LastName', 'trim|required|min_length[1]|max_length[45]|xss_clean');
			$this->form_validation->set_rules('email', 'Email', 'valid_email|required|max_length[80]|callback_useremail_check');
			$this->form_validation->set_rules('user_name', 'Username', 'valid_email|required|max_length[80]|callback_username_check');
			$this->form_validation->set_rules('password', 'Password', 'matches[password_c]|min_length[5]|max_length[45]|required|callback_valid_password');
			$this->form_validation->set_rules('password_c', 'Confirm');
			$this->form_validation->set_rules('country_code', 'CountryCode', 'trim|required|min_length[1]|max_length[6]');
			$this->form_validation->set_rules('phone', 'Mobile', 'trim|required|min_length[2]|max_length[15]|numeric');
			$this->form_validation->set_rules('office_phone', 'Phone', 'trim|required|min_length[2]|max_length[15]|numeric');
			$this->form_validation->set_rules('address', 'Address', 'trim|required|max_length[500]|xss_clean');
			$this->form_validation->set_rules('city', 'City Name', 'trim|required');
			$this->form_validation->set_rules('country', 'Country Name', 'trim|required');
			$this->form_validation->set_rules('term_condition', 'Term And condition', 'trim|required');
			$this->form_validation->set_rules('pin_code', 'Pincode', 'trim|required');
			$this->form_validation->set_rules('user_type[]','Privileges', 'required');
			$this->form_validation->set_rules('pan_number','Company Reg No ', 'required');
			$this->form_validation->set_rules('comp_website_link','Company website link ', 'required');
			$this->form_validation->set_rules('comp_email','Company email ', 'required');
			$this->form_validation->set_rules('pan_holdername','Travel Licence ', 'trim');
		//	echo "asd".$this->form_validation->run();die;
			if (true) {
			//	echo "hjhj";die;
			$image_data = array();
				// FILE UPLOAD
				if (valid_array ( $_FILES ) == true and $_FILES ['panimage'] ['error'] == 0 and $_FILES ['gstimage'] ['error'] == 0 and $_FILES ['panimage'] ['size'] > 0 and $_FILES ['gstimage'] ['size'] > 0) {
					$img_name = 'doc_image-'.time();
					/*if( function_exists( "check_mime_image_type" ) ) {
					    if (!check_mime_image_type( $_FILES['panimage']['tmp_name'])  && !check_mime_image_type( $_FILES['gstimage']['tmp_name']) ) {
					    	echo "Please select the image files only (gif|jpg|png|jpeg)"; exit;
					    }
					}*/
					$config ['upload_path'] = $this->template->domain_image_upload_path ();
					// debug($config ['upload_path']);exit();
					$temp_panfile_name = $_FILES ['panimage'] ['name'];
					$temp_gstfile_name = $_FILES ['gstimage'] ['name'];
					$temp_Drivefile_name = $_FILES ['UploadDriver']['name'];
					$config['allowed_types'] = 'gif|jpg|png|jpeg|docx|doc|txt|pdf';
					$config ['file_name'] ='IMG-'.$img_name;
					// $config['max_size'] = MAX_DOMAIN_LOGO_SIZE;
					// $config['max_width']  = MAX_DOMAIN_LOGO_WIDTH;
					// $config['max_height']  = MAX_DOMAIN_LOGO_HEIGHT;
					$config ['remove_spaces'] = false;
					// UPLOAD IMAGE
					$this->load->library ( 'upload', $config );
					$this->upload->initialize ( $config );
					if (! $this->upload->do_upload ( 'panimage' )) {
						echo $this->upload->display_errors ();
					} else {
						$image_data = $this->upload->data ();
					}

					if (! $this->upload->do_upload ( 'gstimage' )) {
						echo $this->upload->display_errors ();
					} else {
						$image_data1 = $this->upload->data ();
					}
					if (! $this->upload->do_upload ( 'UploadDriver' )) {
						echo $this->upload->display_errors ();
					} else {
						$image_data2 = $this->upload->data ();
					}
				}
				$page_data['form_data']['panimage'] = (empty($image_data ['file_name']) == false ? $image_data ['file_name'] : '');
				$page_data['form_data']['gstimage'] = (empty($image_data1 ['file_name']) == false ? $image_data1 ['file_name'] : '');
                $page_data['form_data']['driveimage'] = (empty($image_data2 ['file_name']) == false ? $image_data2 ['file_name'] : '');

// debug($page_data);
// die;
					//unset($page_data['form_data']['password_c']);
					$page_data_arr['form_data']['uuid'] =provab_encrypt(PROJECT_PREFIX.time());
					$page_data_arr['form_data']['password'] =  provab_encrypt(md5(trim($page_data['form_data']['password'])));
					$page_data_arr['form_data']['title'] = $page_data['form_data']['title'];
					$page_data_arr['form_data']['user_type'] = SUPPLIER;
					$page_data_arr['form_data']['created_datetime'] = date("Y-m-d h:i:sa");
					$page_data_arr['form_data']['domain_list_fk'] = intval(get_domain_auth_id());
					$page_data_arr['form_data']['status'] = FAILURE_STATUS;
					$page_data_arr['form_data']['first_name'] = $page_data['form_data']['first_name'];
					$page_data_arr['form_data']['last_name'] = $page_data['form_data']['last_name'];
					$page_data_arr['form_data']['country_code'] =$page_data['form_data']['country_code'];
					$page_data_arr['form_data']['ofc_country_code'] =$page_data['form_data']['ofc_country_code'];

					$page_data_arr['form_data']['state_txt'] =$page_data['form_data']['state'];

					$page_data_arr['form_data']['phone'] =$page_data['form_data']['phone'];
					$page_data_arr['form_data']['email'] =provab_encrypt(trim($page_data['form_data']['email']));
					$page_data_arr['form_data']['agency_name'] =$page_data['form_data']['company_name'];
					$page_data_arr['form_data']['pan_number'] = @$page_data['form_data']['pan_number'];
					$page_data_arr['form_data']['pan_holdername'] = @$page_data['form_data']['pan_holdername'];
					$page_data_arr['form_data']['address'] =$page_data['form_data']['address'];
                	$page_data_arr['form_data']['company_type'] = @$page_data['form_data']['demo02'];
					$page_data_arr['form_data']['country_name'] =$page_data['form_data']['country'];
					$page_data_arr['form_data']['city'] =$page_data['form_data']['city'];
					$page_data_arr['form_data']['state'] =@$page_data['form_data']['state'];
					$page_data_arr['form_data']['pin_code'] =$page_data['form_data']['pin_code'];
					$page_data_arr['form_data']['office_phone'] =$page_data['form_data']['office_phone'];

					$page_data_arr['form_data']['user_name'] =provab_encrypt($page_data['form_data']['user_name']);
					$page_data_arr['form_data']['creation_source'] = 'portal';
					$page_data_arr['form_data']['terms_conditions'] = 1;
					$page_data_arr['form_data']['created_by_id'] = 0;
					$page_data_arr['form_data']['pan_no'] = $page_data['form_data']['pan_number'];
					$page_data_arr['form_data']['gst_no'] = $page_data['form_data']['pan_holdername'];
					$page_data_arr['form_data']['panimage'] = $page_data['form_data']['panimage'];
					$page_data_arr['form_data']['gstimage'] = $page_data['form_data']['gstimage'];
	                $page_data_arr['form_data']['driveimage'] = @$page_data['form_data']['driveimage'];
					$page_data_arr['form_data']['comp_website_link'] = $page_data['form_data']['comp_website_link'];

					$page_data_arr['form_data']['comp_email'] = $page_data['form_data']['comp_email'];


				 //debug($page_data_arr['form_data']);exit();
					$insert_id = $this->custom_db->insert_record('user', $page_data_arr['form_data']);
					//debug(	$insert_id);die;
					$insert_id = $insert_id['insert_id'];
					$data=array(
									'supplier_id'=>$insert_id,
									'supplier_privailage'=>$crs_type); 	
					// debug($data);exit();
					if($insert_id >0)
					{						
						$data=array(
									'supplier_id'=>$insert_id,
									'supplier_privailage'=>$crs_type); 					

						$this->custom_db->insert_record ( 'supplier_crs_privilage', $data);
					}


					

					//SUPPLIER User Details					
					//get the admin currency
					/*$b2b_user_details = array();
					$get_admin_currency = $this->custom_db->single_table_records('domain_list','currency_converter_fk',array('domain_key'=>CURRENT_DOMAIN_KEY));
					$b2b_user_details['currency_converter_fk'] = $get_admin_currency['data'][0]['currency_converter_fk'];
					
					
					$image = '';
					$b2b_user_details['user_oid'] = $insert_id;
					$b2b_user_details['logo'] = $image;
					$b2b_user_details['balance'] = 0;
					$b2b_user_details['created_datetime'] = $page_data_arr['form_data']['created_datetime'];
					$this->custom_db->insert_record('b2b_user_details', $b2b_user_details);*/
					    $original = $insert_id;
					    //debug($original);exit();
						$encoded_data = rand(100,999).base64_encode($original);
					//	$url = base_url().'index.php/general/activate_account_status?origin='.$encoded_data;
						//$data['activation_link'] = $url;
					$page_data_arr['form_data']['password'] = $page_data['form_data']['password'];//Dont remove
					$data['agent'] = $page_data_arr['form_data'];
					//$mail_template = $this->template->isolated_view('user/agent_template', $data);
					
					$email = provab_decrypt($page_data_arr['form_data']['email']);
					$this->load->library('provab_mailer');
					// $this->provab_mailer->send_mail('chalapathi.provab@gmail.com', 'New-Supplier Registered', $mail_template);
					$subject = 'Supplier Registration Acknowledgment-'.$_SERVER['HTTP_HOST'];
					//$mail_status = $this->provab_mailer->send_mail($email, $subject, $mail_template);

					// $data['message'] = $banner;



					// debug($mail_template);exit;
					
					//Application Logger
					$remarks = $email.' Has Registered From Supplier Portal';
					$notification_users = $this->user_model->get_admin_user_id();
					$action_query_string = array();
					$action_query_string['user_id'] = $insert_id;
					$action_query_string['uuid'] = provab_decrypt($page_data_arr['form_data']['uuid']);
					$action_query_string['user_type'] = SUPPLIER;
					
					$this->application_logger->registration($email, $remarks, $insert_id, $action_query_string, array(), $notification_users);
					// $smss =	$this->test_sms($page_data['form_data']['first_name'], $page_data['form_data']['phone']);
					$this->session->set_flashdata('message', 'Congratulations!! You are successfully registered as an Supplier. Admin will activate your account soon.');
					//$this->session->set_flashdata(array('message' => ' Congratulations!! You are successfully registered as an Supplier. Admin will activate your account soon.', 'type' => SUCCESS_MESSAGE, 'override_app_msg' => true));
					redirect('user/supplierRegister/show');
			}
		}
		
		$data['message'] = @$banner;
		$temp_record = $this->custom_db->single_table_records('domain_list', '*');
		$data['active_data'] =$temp_record['data'][0];

		$temp_record = $this->custom_db->single_table_records('api_country_list', '*');
		$data['phone_code'] =$temp_record['data'];
		$city_record = $this->custom_db->single_table_records('api_city_list', 'destination',array('country'=>$data['active_data']['api_country_list_fk']));
		$data['city_list'] =$city_record['data'][0];
		$data['country_code_list'] = $this->db_cache_api->get_country_code_list();
		$country_code = $this->db_cache_api->get_country_code_list_profile();
		// debug($country_code);exit;
		$phone_code_array = array();
		foreach($country_code['data'] as $c_key => $c_value){
			// $phone_code_array[$c_value['origin']] = $c_value['name'].' '.$c_value['country_code'];
			$phone_code_array[$c_value['country_code']] = $c_value['name'].' '.$c_value['country_code'];
			// debug($phone_code_array);exit;
			
		}
		// debug($phone_code_array);exit;
		$data['phone_code_array'] = $phone_code_array;

		$data['country_list'] = $this->db_cache_api->get_country_list();
		$data['state_list'] = $this->db_cache_api->get_state_list();
		// debug($data['state_list']);die;
		// echo "csdsdsdd";
		// debug($data);die;


		$data['supplier_crs_privilage'] = $this->custom_db->single_table_records('crs_modules'); 
		$this->template->view('user/supplier_register', $data);
	}

	
	   public function activate_account_status() {
        $origin = $this->input->get('origin');
        $unsecure = substr($origin, 3);
        $secure_id = base64_decode($unsecure);
        $user_data = $this->custom_db->single_table_records('user','*',array('user_id' => $secure_id, 'email_activation'=> 1));
        if($user_data['status'] == 0) {
          $status = ACTIVE;
          $email_activation = 1;
          $this->user_model->activate_account_status($status, $email_activation, $secure_id);
        } else {
          $this->session->set_flashdata(array('message' => 'AL009', 'type' => ERROR_MESSAGE));
          //$this->session->set_flashdata('Expired', 'FAILURE_MESSAGE', false, 'Expired');
          redirect ( base_url () );
        }
        //debug($user_data);die;
        
        redirect(base_url());
    }
	   

}
