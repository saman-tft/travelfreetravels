<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 *
 * @package Provab - Provab Application
 * @subpackage Travel Portal
 * @author Balu A<balu.provab@gmail.com>
 * @version V2
 */
class Cms extends CI_Controller {
	public function __construct() {
		parent::__construct ();
		$this->load->model ( 'module_model' );
	}
	/**
	 * Manage Hotel Top Destinations
	 */
	function hotel_top_destinations($offset = 0) {
		// Search Params(Country And City)
		// CMS - Image(On Home Page)
		// error_reporting(E_ALL);
		$page_data = array ();
		$post_data = $this->input->post ();
		$get_data = $this->input->get ();
		// debug($get_data);
		// echo "SDSDSD";
		// debug($post_data);
		// die;
		$mprice=$post_data['mprice'];
		if (valid_array ( $post_data ) == true) {
			$city_origin = $post_data ['city'];
			$imagename = $post_data ['imagename'];
			//debug($post_data);die;
			// FILE UPLOAD
			if ((valid_array ( $_FILES ) == true and $_FILES ['top_destination'] ['error'] == 0 and $_FILES ['top_destination'] ['size'] > 0 )|| isset($imagename)) {
				//die("1");
				//$config ['upload_path'] = $this->template->domain_image_full_path ();

				$config ['upload_path'] = $this->template->domain_image_upload_path ();
				
				if(empty($imagename)){
				$temp_file_name = $_FILES ['top_destination'] ['name'];
				$config ['allowed_types'] = '*';
				$config ['file_name'] = 'top-dest-hotel-' . $city_origin;
				$config ['max_size'] = '1000000';
				$config ['max_width'] = '';
				$config ['max_height'] = '';
				$config ['remove_spaces'] = false;
				// UPDATE
				$temp_record = $this->custom_db->single_table_records ( 'all_api_city_master', 'image', array (
						'origin' => $city_origin 
				) );
				$top_destination_image = $temp_record ['data'] [0] ['image'];
				// DELETE OLD FILES
				if (empty ( $top_destination_image ) == false) {
					//$temp_top_destination_image = $this->template->domain_image_full_path ( $top_destination_image ); // GETTING FILE PATH

					$config ['upload_path'] = $this->template->domain_image_upload_path ($top_destination_image);


					if (file_exists ( $temp_top_destination_image )) {
						unlink ( $temp_top_destination_image );
					}
				}
				// UPLOAD IMAGE
				$this->load->library ( 'upload', $config );
				$this->upload->initialize ( $config );
				if (! $this->upload->do_upload ( 'top_destination' )) {
					echo $this->upload->display_errors ();
				} else {
					$image_data = $this->upload->data ();
				}

			}else{

				$image_data ['file_name'] = $imagename;
			}
				// debug($image_data);exit;
				$this->custom_db->update_record ( 'all_api_city_master', array (
						'top_destination' => ACTIVE,
						'mprice' => $mprice,
						'image' => $image_data ['file_name'] 
				), array (
						'origin' => $city_origin 
				) );
				set_update_message ();
			}
			refresh ();
		}

		else{
			//die("2");
// debug($get_data);
// die;
			if(valid_array($get_data)){
				$list = $this->custom_db->single_table_records ( 'all_api_city_master', '*', array (
						'origin' => $get_data['id'] 
				) );
				// debug($list);
				
			}
			$data_list = $this->custom_db->single_table_records ( 'all_api_city_master', '*', array (
				'origin' => $get_data['id'] 
		) );
			
			// debug($filter);
			$page_data ['data_list'] = $data_list ['data']; 
			$page_data ['id'] = $get_data['id'];
			// debug($page_data);
			// die;
			// $this->template->view ( 'cms/hotel_top_destinations', $page_data);
		}
		$filter = array (
				'top_destination' => ACTIVE 
		);
		$country_list = $this->custom_db->single_table_records ( 'api_country_master', 'country_name,origin,iso_country_code', array (
				'country_name !=' => '' 
		), 0, 1000, array (
				'country_name' => 'ASC' 
		) );
		$data_list = $this->custom_db->single_table_records ( 'all_api_city_master', '*', $filter, 0, 100000, array (
				'date_time' => 'DESC'
		) );
		// debug($this->db->last_query());exit();
		//debug($data_list);exit;
		if ($country_list ['status'] == SUCCESS_STATUS) {
			$page_data ['country_list'] = magical_converter ( array (
					'k' => 'iso_country_code',
					'v' => 'country_name' 
			), $country_list );
		}
		if(!isset($get_data['id']))
		$page_data ['data_list'] = @$data_list ['data'];
	// debug($page_data);
	// die;
		$this->template->view ( 'cms/hotel_top_destinations', $page_data );
	}
	/*
	 * Deactivate Top Destination
	 */
	function deactivate_top_destination($origin) {
		$status = INACTIVE;
		$info = $this->module_model->update_top_destination ( $status, $origin );
		redirect ( base_url () . 'index.php/cms/hotel_top_destinations' );
	}
	/**
	 * Manage Bus Top Destinations
	 */
	function bus_top_destinations($offset = 0) {
		// Search Params(Country And City)
		// CMS - Image(On Home Page)
		$page_data = array ();
		$post_data = $this->input->post ();
		if (valid_array ( $post_data ) == true) {
			$city_origin = $post_data ['city'];
			// FILE UPLOAD
			if (valid_array ( $_FILES ) == true and $_FILES ['top_destination'] ['error'] == 0 and $_FILES ['top_destination'] ['size'] > 0) {
				$config ['upload_path'] = $this->template->domain_image_upload_path ();
				$temp_file_name = $_FILES ['top_destination'] ['name'];
				$config ['allowed_types'] = '*';
				$config ['file_name'] = 'top-dest-bus-' . $city_origin;
				$config ['max_size'] = '1000000';
				$config ['max_width'] = '';
				$config ['max_height'] = '';
				$config ['remove_spaces'] = false;
				// UPDATE
				$temp_record = $this->custom_db->single_table_records ( 'bus_stations_new', 'image', array (
						'origin' => $city_origin 
				) );
				$top_destination_image = $temp_record ['data'] [0] ['image'];
				// DELETE OLD FILES
				if (empty ( $top_destination_image ) == false) {
					$temp_top_destination_image = $this->template->domain_image_full_path ( $top_destination_image ); // GETTING FILE PATH
					if (file_exists ( $temp_top_destination_image )) {
						unlink ( $temp_top_destination_image );
					}
				}
				// UPLOAD IMAGE
				$this->load->library ( 'upload', $config );
				$this->upload->initialize ( $config );
				if (! $this->upload->do_upload ( 'top_destination' )) {
					echo $this->upload->display_errors ();
				} else {
					$image_data = $this->upload->data ();
				}
				// debug($image_data);exit;
				$this->custom_db->update_record ( 'bus_stations_new', array (
						'top_destination' => ACTIVE,
						'image' => $image_data ['file_name'] 
				), array (
						'origin' => $city_origin 
				) );
				set_update_message ();
			}
			refresh ();
		}
		$filter = array (
				'top_destination' => ACTIVE 
		);
		$bus_list = $this->custom_db->single_table_records ( 'bus_stations_new', 'name,origin', array (
				'name !=' => '' 
		), 0, 10000, array (
				'name' => 'ASC' 
		) );
		// debug($bus_list);exit;
		$data_list = $this->custom_db->single_table_records ( 'bus_stations_new', '*', $filter, 0, 100000, array (
				'top_destination' => 'DESC',
				'name' => 'ASC' 
		) );
		
		if ($bus_list ['status'] == SUCCESS_STATUS) {
			$page_data ['bus_list'] = magical_converter ( array (
					'k' => 'origin',
					'v' => 'name' 
			), $bus_list );
		}
		// echo $this->db->last_query();exit;
		$page_data ['data_list'] = @$data_list ['data'];
		$this->template->view ( 'cms/bus_top_destinations', $page_data );
	}
	/**
	 * Deactivate Top Bus Destination
	 */
	function deactivate_bus_top_destination($origin) {
		$status = INACTIVE;
		$info = $this->module_model->update_bus_top_destination ( $status, $origin );
		redirect ( base_url () . 'cms/bus_top_destinations' );
	}
	
	/**
	 * Manage Flight Top Destinations
	 */
	function flight_top_destinations($offset = 0) {
		// Search Params(Country And City)
		// CMS - Image(On Home Page)
		$page_data = array ();
		$post_data = $this->input->post ();
		// debug($post_data);exit;
		if (valid_array ( $post_data ) == true) {
			$from_aiport_origin = $post_data ['from_airport'];
			$to_aiport_origin = $post_data ['to_airport'];
			if($from_aiport_origin == $to_aiport_origin){
				$page_data['message'] = 'From and To Airports must be different';
			}
			else{

				// FILE UPLOAD
			if (valid_array ( $_FILES ) == true and $_FILES ['top_destination'] ['error'] == 0 and $_FILES ['top_destination'] ['size'] > 0) {
				$config ['upload_path'] = $this->template->domain_image_upload_path ();
				$temp_file_name = $_FILES ['top_destination'] ['name'];
				$config ['allowed_types'] = '*';
				$config ['file_name'] = 'top-dest-fight-' . $from_aiport_origin;
				$config ['max_size'] = '1000000';
				$config ['max_width'] = '';
				$config ['max_height'] = '';
				$config ['remove_spaces'] = false;
				// UPDATE
				$from_temp_record = $this->custom_db->single_table_records ( 'flight_airport_list', '*', array (
						'origin' => $from_aiport_origin
				) );
				$to_temp_record = $this->custom_db->single_table_records ( 'flight_airport_list', '*', array (
						'origin' => $to_aiport_origin
				) );
				
				// $top_destination_image = $temp_record ['data'] [0] ['image'];
				$top_destination_image ='';
				// DELETE OLD FILES
				if (empty ( $top_destination_image ) == false) {
					$temp_top_destination_image = $this->template->domain_image_full_path ( $top_destination_image ); // GETTING FILE PATH
					if (file_exists ( $temp_top_destination_image )) {
						unlink ( $temp_top_destination_image );
					}
				}
				// UPLOAD IMAGE
				$this->load->library ( 'upload', $config );
				$this->upload->initialize ( $config );
				if (! $this->upload->do_upload ( 'top_destination' )) {
					echo $this->upload->display_errors ();
				} else {
					$image_data = $this->upload->data ();
				}
				
				$data['from_airport_name'] = $from_temp_record['data'][0]['airport_city'];
				$data['from_airport_code'] = $from_temp_record['data'][0]['airport_code'];
				$data['to_airport_code'] = $to_temp_record['data'][0]['airport_code'];
				$data['to_airport_name'] = $to_temp_record['data'][0]['airport_city'];
				$data['image'] = $image_data ['file_name'];
				$data['status'] = 1;
				// debug($data);exit;
				$this->custom_db->insert_record ( 'top_flight_destinations', $data );
				// debug($image_data);exit;
				
				set_update_message ();
				}
			}
			
			refresh ();
		}


		$flight_list = $this->custom_db->single_table_records ( 'flight_airport_list', 'airport_city,origin', array (
				'airport_city !=' => ''
		), 0, 10000, array (
				'airport_city' => 'ASC'
		) );
		$data_list = $this->custom_db->single_table_records ( 'top_flight_destinations', '*', '', 0, 100000, array (
				'origin' => 'ASC',
				
		) );
		//echo $this->db->last_query();exit;
		if ($flight_list ['status'] == SUCCESS_STATUS) {
			$page_data ['flight_list'] = magical_converter ( array (
					'k' => 'origin',
					'v' => 'airport_city'
			), $flight_list );
		}
		// debug($page_data);exit;
		$page_data ['data_list'] = @$data_list ['data'];
		$this->template->view ( 'cms/flight_top_destinations', $page_data );
	}
	/**
	 * Deactivate Top Bus Destination
	 */
	function deactivate_flight_top_destination($origin) {
		$status = INACTIVE;
		$info = $this->module_model->update_flight_top_destination ( $status, $origin );
		redirect ( base_url () . 'cms/flight_top_destinations' );
	}
	/**
	 * Deactivate Top Bus Destination
	 */
	function activate_flight_top_destination($origin) {
		$status = ACTIVE;
		$info = $this->module_model->update_flight_top_destination ( $status, $origin );
		redirect ( base_url () . 'cms/flight_top_destinations' );
	}
	/**
	 * Static Page Content
	 */
	function add_cms_page($id = '') {
		
		// privilege_handler('p54');
		$this->form_validation->set_message ( 'required', 'Required.' );
		
		// check for negative id
		valid_integer ( $id );
		
		// validation rules
		$post_data = $this->input->post ();
		// get data
		$cols = ' * ';
		if (valid_array ( $post_data ) == false) {
			if (intval ( $id ) > 0) {
				// edit data
				$tmp_data = $this->custom_db->single_table_records ( 'cms_pages', '', array (
						'page_id' => $id 
				) );
				// debug($tmp_data);exit;
				if (valid_array ( $tmp_data ['data'] [0] )) {
					$data ['page_title'] = $tmp_data ['data'] [0] ['page_title'];
					$data ['page_description'] = $tmp_data ['data'] [0] ['page_description'];
					$data ['page_seo_title'] = $tmp_data ['data'] [0] ['page_seo_title'];
					$data ['page_seo_keyword'] = $tmp_data ['data'] [0] ['page_seo_keyword'];
					$data ['page_seo_description'] = $tmp_data ['data'] [0] ['page_seo_description'];
					$data ['page_position'] = $tmp_data ['data'] [0] ['page_position'];
				} else {
					redirect ( 'cms/add_cms_page' );
				}
			}
		} elseif (valid_array ( $post_data )) {
			$this->form_validation->set_rules ( 'page_title', 'Page Title', 'required' );
			$this->form_validation->set_rules ( 'page_description', 'Page Description', 'required' );
			$this->form_validation->set_rules ( 'page_seo_title', 'Page SEO Title', 'required' );
			$this->form_validation->set_rules ( 'page_seo_keyword', 'Page SEO Keyword', 'required' );
			$this->form_validation->set_rules ( 'page_seo_description', 'Page SEO Description', 'required' );
			$this->form_validation->set_rules ( 'page_position', 'Page Position', 'required' );
			
			$data ['page_title'] = $title = $this->input->post ( 'page_title' );
			$data ['page_description'] = $this->input->post ( 'page_description' );
			$data ['page_seo_title'] = $this->input->post ( 'page_seo_title' );
			$data ['page_seo_keyword'] = $this->input->post ( 'page_seo_keyword' );
			$data ['page_seo_description'] = $this->input->post ( 'page_seo_description' );
			$data ['page_position'] = $this->input->post ( 'page_position' );
			$data ['page_label'] = $this->uniqueLabel(substr($title, 0,100));
			//debug($data);exit;
			if ($this->form_validation->run ()) {
				// add / update data
				if (intval ( $id ) > 0) {
					$this->custom_db->update_record ( 'cms_pages', $data, array (
							'page_id' => $id 
					) );
				} else {
					$this->custom_db->insert_record ( 'cms_pages', $data );
				}
				redirect ( 'cms/add_cms_page' );
			}
		}
		$data ['ID'] = $id;
		// get all sub admin
		$tmp_data = $this->custom_db->single_table_records ( 'cms_pages', $cols );
		$data ['sub_admin'] = '';
		$data ['sub_admin'] = $tmp_data ['data'];
		$this->template->view ( 'cms/add_cms_page', $data );
	}
	/**
	 * Status update of Static Page Content
	 */
	function cms_status($id = '', $status = 'D') {
		if ($id > 0) {
			if (strcmp ( $status, 'D' ) == 0) {
				$status = 0;
			} else {
				$status = 1;
			}
			
			$this->custom_db->update_record ( 'cms_pages', array (
					'page_status' => $status 
			), array (
					'page_id' => $id 
			) );
		}
		redirect ( 'cms/add_cms_page' );
	}
	public function uniqueLabel($string) {
		//Lower case everything
		$string = strtolower($string);
		//Make alphanumeric (removes all other characters)
		$string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
		//Clean up multiple dashes or whitespaces
		$string = preg_replace("/[\s-]+/", " ", $string);
		//Convert whitespaces and underscore to dash
		$string = preg_replace("/[\s_]/", "-", $string);
		return $string;
	}
//Adding the Headings in Home Page
	function add_home_page_heading(){
		$post_data = $this->input->post();
		$get_data = $this->input->get();
		// debug($get_data);exit;
		
		if(valid_array($post_data)){
			
			$data['title'] = ucwords($post_data['header_title']);
			$data['status'] = ACTIVE;
			$list = $this->custom_db->single_table_records ( 'home_page_headings', '*', array (
						'origin' => $get_data['origin'] 
				) );
			$head_data = $this->custom_db->single_table_records ( 'home_page_headings', '*', array (
						'title' => $post_data['header_title']
				) );
			if($list['status'] == FAILURE_STATUS){
				if($head_data['status'] == FAILURE_STATUS){
					$insert_id = $this->custom_db->insert_record ( 'home_page_headings', $data );
				}
				else{
					redirect ( 'cms/add_home_page_heading/Duplicate Title' );
				}

			}
			else{
				if(!empty($get_data) && $get_data['origin'] > 0){
					$this->custom_db->update_record ( 'home_page_headings', array (
								'title' => $post_data['header_title']), array (
								'origin' => $get_data['origin']
						) );
				}	
				
			}
			redirect ( 'cms/home_page_headings' );
		}
		else{
			$page_data = array();
			if(valid_array($get_data)){
				$list = $this->custom_db->single_table_records ( 'home_page_headings', '*', array (
						'origin' => $get_data['origin'] 
				) );
				$page_data['title'] = $list['data'][0]['title'];
			}
			$this->template->view ( 'cms/add_home_page_heading', $page_data);
		}
		
	}
	
	//activating or deactivating the home page headers
	function home_page_headings(){
		$page_data = array ();
		$data_list = $this->custom_db->single_table_records ( 'home_page_headings', '*', '', 0, 100000 );
		$page_data ['data_list'] = @$data_list ['data'];
		$this->template->view ( 'cms/home_page_headings', $page_data );
	}
	/**
	 * Activate home page header
	 */
	function activate_heading($origin)
	{
		$info = $this->custom_db->update_record ( 'home_page_headings', array (
						'status' => ACTIVE), array (
						'origin' => $origin 
				) );
		exit;
	}
	/**
	 * DeActivate home page header
	 */
	function deactivate_heading($origin)
	{
		
		$info = $this->custom_db->update_record ( 'home_page_headings', array (
						'status' => INACTIVE), array (
						'origin' => $origin 
				) );
		exit;
	}
	/*why choose us home page*/
	function why_choose_us(){
		$page_data = array ();
		$data_list = $this->custom_db->single_table_records ( 'why_choose_us', '*', '', 0, 100000 );
		$page_data ['data_list'] = @$data_list ['data'];
		// debug($page_data);exit;
		$this->template->view ( 'cms/why_choose_us', $page_data);
	}
	function add_why_choose_us(){
		$post_data = $this->input->post();
		$get_data = $this->input->get();
		// debug($get_data);exit;
		if(valid_array($post_data)){
			// debug($post_data);exit;
			$data['title'] = ucwords($post_data['header_title']);
			$data['icon'] = $post_data['header_icon'];
			$data['status'] = ACTIVE;
			$list = $this->custom_db->single_table_records ( 'why_choose_us', '*', array (
						'origin' => $get_data['origin']
				) );
			$why_choose_data = $this->custom_db->single_table_records ( 'why_choose_us', '*', array (
						'title' => $post_data['header_title'],
						'icon' => $post_data['header_icon']
				) );
				if($list['status'] == FAILURE_STATUS ){
					if($why_choose_data['status'] == FAILURE_STATUS){
						$insert_id = $this->custom_db->insert_record ( 'why_choose_us', $data );
					}
					else{
						redirect ( 'cms/add_why_choose_us/Duplicate Title' );
					}
				}
				else{
				// debug($get_data);exit;
				if(!empty($get_data) && valid_array($get_data)){
					$this->custom_db->update_record ( 'why_choose_us', array (
						'title' => ucwords($post_data['header_title']),
						'icon' => $post_data['header_icon'] 
					), array (
						'origin' => $get_data['origin'] 
					) );
				}
				
			}
			// debug($insert_id);exit;
			redirect ( 'cms/why_choose_us' );
		}
		else{
			$page_data = array();
			if(valid_array($get_data)){
				$list = $this->custom_db->single_table_records ( 'why_choose_us', '*', array (
						'origin' => $get_data['origin'] 
				) );
				$page_data['title'] = $list['data'][0]['title'];
				$page_data['icon'] = $list['data'][0]['icon'];
			}
			$this->template->view ( 'cms/add_why_choose_us', $page_data);
		}
	}
	function activate_why_choose($origin){
		$info = $this->custom_db->update_record ( 'why_choose_us', array (
						'status' => ACTIVE), array (
						'origin' => $origin 
				) );

		exit;
	}
	function deactivate_why_choose($origin){
		$info = $this->custom_db->update_record ( 'why_choose_us', array (
						'status' => INACTIVE), array (
						'origin' => $origin 
				) );
		exit;
	}
	function top_airlines(){
		$page_data = array ();
		$data_list = $this->custom_db->single_table_records ( 'top_airlines', '*', '', 0, 100000 );
		$page_data ['data_list'] = @$data_list ['data'];
		// debug($page_data);exit;
		$this->template->view ( 'cms/top_airlines', $page_data);
	}
	function add_top_airlines(){

		$post_data = $this->input->post();
		$get_data = $this->input->get();
		// debug($get_data);
		// debug($post_data);exit;
		if(valid_array($post_data)){
			
			$data['airline_name'] = ucwords($post_data['airline_name']);
			$data['status'] = ACTIVE;
			$data['description']=$post_data['description'];

			if (valid_array($_FILES) == true and $_FILES['airline_logo']['error'] == 0 and $_FILES['airline_logo']['size'] > 0) {
					if( function_exists( "check_mime_image_type" ) ) {
					    if ( !check_mime_image_type( $_FILES['airline_logo']['tmp_name'] ) ) {
					    	echo "Please select the image files only (gif|jpg|png|jpeg)"; exit;
					    }
					}
					$config['upload_path'] = $this->template->domain_top_airline_upload_path();
					$temp_file_name = $_FILES['airline_logo']['name'];
					$config['allowed_types'] = 'gif|jpg|png|jpeg';
					$config['file_name'] = get_domain_key().$temp_file_name;
					$config['max_size'] = '1000000';
					$config['max_width']  = '';
					$config['max_height']  = '';
					$config['remove_spaces']  = false;
					// echo $config['upload_path'];exit;
					//UPLOAD IMAGE
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					if ( ! $this->upload->do_upload('airline_logo')) {
						echo $this->upload->display_errors();
					} else {
						$image_data =  $this->upload->data();
						// $data['logo'] = @$image_data['file_name'];
						$data['logo'] = $config['file_name'];
					}
	                /*UPDATING IMAGE */
					
				}
				$data['logo'] = $config['file_name'];
				// debug($data);exit();
			$list = $this->custom_db->single_table_records ( 'top_airlines', '*', array (
						'origin' => $get_data['origin']
					) );
			// debug($list);exit();
			if($list['status'] == FAILURE_STATUS ){
				$insert_id = $this->custom_db->insert_record ( 'top_airlines', $data );
				
			}
			else{
				if(isset($data['logo'])){
					$logo = $data['logo'];
				}
				else{
					$logo = $list['data'][0]['logo'];
					
				}
			    // debug($post_data);exit;
				if(!empty($get_data) && valid_array($get_data)){
					$this->custom_db->update_record ( 'top_airlines', array (
						'airline_name' => ucwords($post_data['airline_name']),
						'logo' => $logo ,
						'description' => $data['description']
					), array (
						'origin' => $get_data['origin'] 
					) );
				}
				
			}
			// debug($insert_id);exit;
			redirect ( 'cms/top_airlines' );
		}
		else{

// debug($get_data);
// die;
			if(valid_array($get_data)){
				$list = $this->custom_db->single_table_records ( 'top_airlines', '*', array (
						'origin' => $get_data['origin'] 
				) );
				// debug($list);exit;
				$page_data['airline_name'] = $list['data'][0]['airline_name'];
				$page_data['logo'] = $list['data'][0]['logo'];
				$page_data['description'] = $list['data'][0]['description'];
			}
			

			// debug($page_data);
			// die;
			
		}
		$page_data['airline_list'] = $this->custom_db->single_table_records ( 'airline_list', '*', '' );
		$this->template->view ( 'cms/add_top_airlines', $page_data);
	}
	function activate_top_airline($origin){
		$info = $this->custom_db->update_record ( 'top_airlines', array (
						'status' => ACTIVE), array (
						'origin' => $origin 
				) );

		exit;
	}
	function deactivate_top_airline($origin){
		$info = $this->custom_db->update_record ( 'top_airlines', array (
						'status' => INACTIVE), array (
						'origin' => $origin 
				) );
		exit;
	}
	/*Tour Styles on Home Page*/
	function tour_styles(){
		$page_data = array ();
		$data_list = $this->custom_db->single_table_records ( 'tour_styles', '*', '', 0, 100000 );
		$page_data ['data_list'] = @$data_list ['data'];
		// debug($page_data);exit;
		$this->template->view ( 'cms/tour_styles', $page_data);
	}
	function add_tour_styles(){

		$post_data = $this->input->post();
		$get_data = $this->input->get();
		// debug($post_data);exit;
		if(valid_array($post_data)){
			$destination_data = $this->custom_db->single_table_records ( 'api_sightseeing_destination_list', '*', array (
						'origin' => $post_data['destination']
			 	) );
			$category_data = $this->custom_db->single_table_records ( 'activity_category_list', '*', array (
						'category_id' => $post_data['category']
			 	) );
			$data['destination_name'] = $destination_data['data'][0]['destination_name'];
			$data['destination_id'] = $destination_data['data'][0]['destination_id'];
			$data['category_name'] = $category_data['data'][0]['category_name'];
			$data['category_id'] = $category_data['data'][0]['category_id'];	
			$data['status'] = ACTIVE;
			// debug($_FILES);exit;
			if (valid_array($_FILES) == true and $_FILES['image']['error'] == 0 and $_FILES['image']['size'] > 0) {
					if( function_exists( "check_mime_image_type" ) ) {
					    if ( !check_mime_image_type( $_FILES['top_destination']['tmp_name'] ) ) {
					    	echo "Please select the image files only (gif|jpg|png|jpeg)"; exit;
					    }
					}
					$config['upload_path'] = $this->template->domain_tour_style_upload_path();
					$temp_file_name = $_FILES['image']['name'];
					$config['allowed_types'] = 'gif|jpg|png|jpeg';
					$config['file_name'] = get_domain_key().$temp_file_name;
					$config['max_size'] = '1000000';
					$config['max_width']  = '';
					$config['max_height']  = '';
					$config['remove_spaces']  = false;
					// echo $config['upload_path'];exit;
					//UPLOAD IMAGE
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					if ( ! $this->upload->do_upload('image')) {
						echo $this->upload->display_errors();
					} else {
						$image_data =  $this->upload->data();
						$data['image'] = @$image_data['file_name'];
					}
	                /*UPDATING IMAGE */
					
				}
				// debug($data);exit;
			$list = $this->custom_db->single_table_records ( 'tour_styles', '*', array (
						'origin' => $get_data['origin']
					) );

			if($list['status'] == FAILURE_STATUS ){
				$insert_id = $this->custom_db->insert_record ( 'tour_styles', $data );
				
			}
			else{
				if(isset($data['image'])){
					$image = $data['image'];
				}
				else{
					$image = $list['data'][0]['image'];
					
				}
				// debug($get_data);exit;
				if(!empty($get_data) && valid_array($get_data)){
					$this->custom_db->update_record ( 'tour_styles', array (
						'destination_name' => $data['destination_name'],
						'destination_id' => $data['destination_id'],
						'category_name' => $data['category_name'],
						'category_id' => $data['category_id'],
						'image' => $image 
					), array (
						'origin' => $get_data['origin'] 
					) );
				}
				
			}
			// debug($insert_id);exit;
			redirect ( 'cms/tour_styles' );
		}
		else{
			
			if(valid_array($get_data)){
				$list = $this->custom_db->single_table_records ( 'tour_styles', '*', array (
						'origin' => $get_data['origin'] 
				) );
				
				$page_data['destination_id'] = $list['data'][0]['origin'];
				$page_data['category_id'] = $list['data'][0]['category_id'];
				$page_data['image'] = $list['data'][0]['image'];
			}
			$page_data['destination_list'] = $this->custom_db->single_table_records ( 'api_sightseeing_destination_list', '*', '' );
			$page_data['category_list'] = $this->custom_db->single_table_records ( 'activity_category_list', '*', '' );

			$this->template->view ( 'cms/add_tour_styles', $page_data);
		}
	}
	function activate_tour_style($origin){
		$info = $this->custom_db->update_record ( 'tour_styles', array (
						'status' => ACTIVE), array (
						'origin' => $origin 
				) );

		exit;
	}
	function deactivate_tour_style($origin){
		$info = $this->custom_db->update_record ( 'tour_styles', array (
						'status' => INACTIVE), array (
						'origin' => $origin 
				) );
		exit;
	}
	function add_contact_address(){
		$post_data = $this->input->post();
		// debug($post_data);exit;
		if(valid_array($post_data)){
			$this->custom_db->update_record ( 'domain_list', array (
						'address' => $post_data['address'],
						'phone' => $post_data['phone'],
						'email' => $post_data['email'],
						), array (
						'origin' => $post_data['domain_id'] 
				) );
			$this->session->set_flashdata(array('message' => 'UL0013', 'type' => SUCCESS_MESSAGE));
			refresh();
		
		}
		$domain_data = $this->custom_db->single_table_records ( 'domain_list', '*', '' );
		
		// debug($footer_data);exit;
		$page_data['address'] = $domain_data['data'][0]['address'];
		$page_data['domain_id'] = $domain_data['data'][0]['origin'];
		$page_data['email'] = $domain_data['data'][0]['email'];
		$page_data['phone'] = $domain_data['data'][0]['phone'];
		
		$this->template->view('cms/add_contact_address', $page_data);
	}
	public function delete_heading($origin) {
		$this->custom_db->delete_record ( 'home_page_headings', array ('origin' => $origin));
		redirect ( 'cms/home_page_headings' );
	}
	public function delete_why_choose($origin) {
		$this->custom_db->delete_record ( 'why_choose_us', array ('origin' => $origin));
		redirect ( 'cms/why_choose_us' );
	}
	public function delete_top_airline($origin) {
		$this->custom_db->delete_record ( 'top_airlines', array ('origin' => $origin));
		redirect ( 'cms/top_airlines' );
	}
	public function delete_tour_styles($origin) {
		$this->custom_db->delete_record ( 'tour_styles', array ('origin' => $origin));
		redirect ( 'cms/tour_styles' );
	}

}