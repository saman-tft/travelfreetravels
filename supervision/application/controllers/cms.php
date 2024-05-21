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
	 
	 
	 
	 function rescheduleflights(){
           $page_data=array();
           $query="select * from reschedule_flight order by rfid desc";
           $page_data['datas']=$this->db->query($query)->result_array();;
           $this->template->view('cms/rescheduleflights',$page_data);
	 }

	 function cancellationtimeline(){
	 	  $page_data=array();
	 	  $query="select * from cancellation_timeline_policy order by tpid desc";
           $page_data['datas']=$this->db->query($query)->result_array();;
           $this->template->view('cms/cancellationtimeline',$page_data);
	 }
	 
	function activityv1_top_destinations($idv='',$offset = 0) {
		// Search Params(Country And City)
		// CMS - Image(On Home Page)
		error_reporting(0);
		$page_data = array ();
		$post_data = $this->input->post ();
	//	debug($post_data);die;
		if (valid_array ( $post_data ) == true) {
			$city_origin = $post_data ['city'];
			// FILE UPLOAD
			$upload_path = realpath ( '../extras' ).'/custom/'.CURRENT_DOMAIN_KEY.'/images/';
			if (valid_array ( $_FILES ) == true and $_FILES ['top_destination'] ['error'] == 0 and $_FILES ['top_destination'] ['size'] > 0) {
				$config ['upload_path'] = $this->template->domain_image_upload_path ();
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
					$temp_top_destination_image = $upload_path.$top_destination_image; // GETTING FILE PATH
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
				}
		//	 debug($image_data);exit;
		
				if ($idv!=0) {

if($image_data ['file_name']=="")
{
   $image_data ['file_name']=$this->input->post('dbimage');
}
					$this->custom_db->update_record ( 'all_api_city_master', array (
								'top_destination' => ACTIVE,
								'nationality' =>$this->input->post('country'),
								'url' =>$this->input->post('url'),
								'perfect_package' => INACTIVE,
								'image' =>$image_data ['file_name']  
						), array (
								'origin' => $this->input->post('origin') 
						) );
					//echo $this->db->last_query();die;
					$this->session->set_flashdata('message', UL0013);
					redirect ( base_url () . 'cms/activityv1_top_destinations' );
				}
				else
				{
						$this->custom_db->update_record ( 'all_api_city_master', array (
								'top_destination' => ACTIVE,
								'nationality' =>$this->input->post('country'),
								'url' =>$this->input->post('url'),
								'perfect_package' => INACTIVE,
								'image' => $image_data ['file_name'] 
						), array (
								'origin' => $city_origin 
						) );
						$this->session->set_flashdata('message', UL0013);
						refresh ();
					}
				
			
			
		}
		$filter = array (
				'top_destination' => ACTIVE,
				'perfect_package' => INACTIVE
		);

		$country_list = $this->custom_db->single_table_records ( 'api_country_master', 'country_name,origin,iso_country_code', array (
				'country_name !=' => '' 
		), 0, 1000, array (
				'country_name' => 'ASC' 
		) );
//		echo "tester".$id;
		if ($idv!=0) {
$page_data['idv']=$idv;
		$page_data['data'] = $this->custom_db->single_table_records('all_api_city_master','*',array('origin'=>$idv))['data'][0];
	//	debug($page_data['data'] );die;
		$city_data = $this->custom_db->single_table_records('crs_city','*',array('country_name'=>$page_data['data']['country_name']));
			$page_data['get_city_data'] = $this->custom_db->single_table_records('crs_city','*',array('country_name'=>$page_data['data']['country_name'],'city'=>$page_data['data']['city_name']))['data'][0]['id'];
		$page_data ['city_data'] = magical_converter ( array (
					'k' => 'id',
					'v' => 'city' 
			), $city_data );
	//echo $this->db->last_query();
//debug($page_data['city_data']);die;
}

		$data_list = $this->custom_db->single_table_records ( 'all_api_city_master', '*', $filter, 0, 100000, array (
				'top_destination' => 'DESC',
				'city_name' => 'ASC' 
		) );
		//debug($this->db->last_query());exit;
		//debug($data_list);exit;
		if ($country_list ['status'] == SUCCESS_STATUS) {
			$page_data ['country_list'] = magical_converter ( array (
					'k' => 'iso_country_code',
					'v' => 'country_name' 
			), $country_list );
		}
		
		$page_data ['data_list'] = @$data_list ['data'];
		
		
		
		
		$this->template->view ( 'cms/hotel_top_destinations', $page_data );
	}
	/**
	 * Manage Perfect Hotels Packages
	 */
	function hotel_perfect_packages($offset = 0) {
	    // Search Params(Country And City)
		// CMS - Image(On Home Page)
		error_reporting(0);
		$page_data = array ();
		$post_data = $this->input->post ();
		if (valid_array ( $post_data ) == true) {
			$city_origin = $post_data ['city'];
			// FILE UPLOAD
			$upload_path = realpath ( '../extras' ).'/custom/'.CURRENT_DOMAIN_KEY.'/images/';
			if (valid_array ( $_FILES ) == true and $_FILES ['top_destination'] ['error'] == 0 and $_FILES ['top_destination'] ['size'] > 0) {
				$config ['upload_path'] = $this->template->domain_image_upload_path ();
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
					$temp_top_destination_image = $upload_path.$top_destination_image; // GETTING FILE PATH
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
				$this->custom_db->update_record ( 'all_api_city_master', array (
						'top_destination' => INACTIVE,
						'perfect_package' => ACTIVE,
						'image' => $image_data ['file_name'] 
				), array (
						'origin' => $city_origin 
				) );
				$this->session->set_flashdata('message', UL0013);
			}
			refresh ();
		}
		$filter = array (
				'top_destination' => INACTIVE,
				'perfect_package' => ACTIVE 
		);
		$country_list = $this->custom_db->single_table_records ( 'api_country_master', 'country_name,origin,iso_country_code', array (
				'country_name !=' => '' 
		), 0, 1000, array (
				'country_name' => 'ASC' 
		) );
		$data_list = $this->custom_db->single_table_records ( 'all_api_city_master', '*', $filter, 0, 100000, array (
				'perfect_package' => 'DESC',
				'city_name' => 'ASC' 
		) );
		//debug($this->db->last_query());exit;
		//debug($data_list);exit;
		if ($country_list ['status'] == SUCCESS_STATUS) {
			$page_data ['country_list'] = magical_converter ( array (
					'k' => 'iso_country_code',
					'v' => 'country_name' 
			), $country_list );
		}
		
		$page_data ['data_list'] = @$data_list ['data'];
		$this->template->view ( 'cms/hotel_perfect_packages', $page_data );
	    
	}
	function activity_top_destinations($offset = 0) {
		// Search Params(Country And City)
		// CMS - Image(On Home Page)
		error_reporting(0);
		$page_data = array ();
		$post_data = $this->input->post ();
		if (valid_array ( $post_data ) == true) {
			$city_origin = $post_data ['country'];
			// FILE UPLOAD
			$upload_path = realpath ( '../extras' ).'/custom/'.CURRENT_DOMAIN_KEY.'/images/';
			// echo $this->template->domain_image_full_path ();exit;
			if (valid_array ( $_FILES ) == true and $_FILES ['top_destination'] ['error'] == 0 and $_FILES ['top_destination'] ['size'] > 0) {
				// $config ['upload_path'] = $this->template->domain_image_full_path ();
				$config ['upload_path'] = $upload_path;
				$temp_file_name = $_FILES ['top_destination'] ['name'];
				$config ['allowed_types'] = '*';
				$config ['file_name'] = 'top-dest-activity-' . $city_origin;
				$config ['max_size'] = '1000000';
				$config ['max_width'] = '';
				$config ['max_height'] = '';
				$config ['remove_spaces'] = false;
				// UPDATE
				$temp_record = $this->custom_db->single_table_records ( 'api_sightseeing_destination_list', 'image_activity', array (
						'origin' => $city_origin 
				) );
				$top_destination_image = $temp_record ['data'] [0] ['image_activity'];
				// DELETE OLD FILES
				if (empty ( $top_destination_image ) == false) {
					$temp_top_destination_image = $upload_path.$top_destination_image; // GETTING FILE PATH
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
				// echo $config ['upload_path'];
				// exit;
				// debug($image_data);exit;

				$this->custom_db->update_record ( 'api_sightseeing_destination_list', array (
						'top_destination_activity' => ACTIVE,
						'activity_perfect_package' => INACTIVE,
						'image_activity' => $image_data ['file_name'] 
				), array (
						'origin' => $city_origin 
				) );
				
				$this->session->set_flashdata('message', UL0013);
			}
			refresh ();
		}
		$filter = array (
				'top_destination_activity' => ACTIVE 
		);
		$country_list = $this->custom_db->single_table_records( 'api_sightseeing_destination_list', 'destination_name,destination_id,origin', array (
				'destination_name !=' => '' 
		), 0, 1000000000, array (
				'destination_name' => 'ASC' 
		) );
		// debug($country_list);exit;
		$data_list = $this->custom_db->single_table_records ( 'api_sightseeing_destination_list', '*', $filter, 0, 100000, array (
				'top_destination_activity' => 'DESC',
				'destination_name' => 'ASC' 
		) );
		// debug($country_list);exit;
		/*if ($country_list ['status'] == SUCCESS_STATUS) {
			$page_data ['country_list'] = magical_converter ( array (
					'k' => 'destination_id',
					'v' => 'destination_name' 
			), $country_list );
		}*/
		$page_data ['country_list']=$country_list['data'];
		$page_data ['data_list'] = @$data_list ['data'];
		// debug($page_data ['data_list']);exit;
		$this->template->view ( 'cms/top_destination_activity', $page_data );
	}
	/**
	 * for Perfect Activities Packages
	 */
	 function activity_perfect_packages($offset = 0){
	     // Search Params(Country And City)
		// CMS - Image(On Home Page)
		error_reporting(0);
		$page_data = array ();
		$post_data = $this->input->post ();
		if (valid_array ( $post_data ) == true) {
			$city_origin = $post_data ['country'];
			// FILE UPLOAD
			$upload_path = realpath ( '../extras' ).'/custom/'.CURRENT_DOMAIN_KEY.'/images/';
			// echo $this->template->domain_image_full_path ();exit;
			if (valid_array ( $_FILES ) == true and $_FILES ['top_destination'] ['error'] == 0 and $_FILES ['top_destination'] ['size'] > 0) {
				// $config ['upload_path'] = $this->template->domain_image_full_path ();
				$config ['upload_path'] = $upload_path;
				$temp_file_name = $_FILES ['top_destination'] ['name'];
				$config ['allowed_types'] = '*';
				$config ['file_name'] = 'top-dest-activity-' . $city_origin;
				$config ['max_size'] = '1000000';
				$config ['max_width'] = '';
				$config ['max_height'] = '';
				$config ['remove_spaces'] = false;
				// UPDATE
				$temp_record = $this->custom_db->single_table_records ( 'api_sightseeing_destination_list', 'image_activity', array (
						'origin' => $city_origin 
				) );
				$top_destination_image = $temp_record ['data'] [0] ['image_activity'];
				// DELETE OLD FILES
				if (empty ( $top_destination_image ) == false) {
					$temp_top_destination_image = $upload_path.$top_destination_image; // GETTING FILE PATH
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
				// echo $config ['upload_path'];
				// exit;
				// debug($image_data);exit;

				$this->custom_db->update_record ( 'api_sightseeing_destination_list', array (
						'top_destination_activity' => INACTIVE,
						'activity_perfect_package'=> ACTIVE,
						'image_activity' => $image_data ['file_name'] 
				), array (
						'origin' => $city_origin 
				) );
				
				$this->session->set_flashdata('message', UL0013);
			}
			refresh ();
		}
		$filter = array (
				'activity_perfect_package' => ACTIVE 
		);
		$country_list = $this->custom_db->single_table_records( 'api_sightseeing_destination_list', 'destination_name,destination_id,origin', array (
				'destination_name !=' => '' 
		), 0, 1000000000, array (
				'destination_name' => 'ASC' 
		) );
		// debug($country_list);exit;
		$data_list = $this->custom_db->single_table_records ( 'api_sightseeing_destination_list', '*', $filter, 0, 100000, array (
				'activity_perfect_package' => 'DESC',
				'destination_name' => 'ASC' 
		) );
		// debug($country_list);exit;
		/*if ($country_list ['status'] == SUCCESS_STATUS) {
			$page_data ['country_list'] = magical_converter ( array (
					'k' => 'destination_id',
					'v' => 'destination_name' 
			), $country_list );
		}*/
		$page_data ['country_list']=$country_list['data'];
		$page_data ['data_list'] = @$data_list ['data'];
		// debug($page_data ['data_list']);exit;
		$this->template->view ( 'cms/activity_perfect_packages', $page_data );
	 }
	/*
	 * Deactivate Top Destination
	 */
	function deactivate_top_destination($origin) {
		$status = INACTIVE;
		$info = $this->module_model->update_top_destination ( $status, $origin );
		redirect ( base_url () . 'cms/activityv1_top_destinations' );
	}
	function delete_top_destination($origin) {
		$status = INACTIVE;
		$info = $this->module_model->delete_top_destination ( $status, $origin );
		redirect ( base_url () . 'cms/activityv1_top_destinations' );
	}
	/*
	 * Delete hotel perfect packages
	 */
	function delete_hotel_perfect_packages($origin) {
		$status = INACTIVE;
		$info = $this->module_model->delete_top_destination ( $status, $origin );
		redirect ( base_url () . 'cms/hotel_perfect_packages' );
	}
	/*
	 * Deactivate hotel perfect packages
	 */
	 function deactivate_hotel_perfect_packages($origin) {
		$status = INACTIVE;
		$info = $this->module_model->update_hotel_perfect_packages( $status, $origin );
		redirect ( base_url () . 'cms/hotel_perfect_packages' );
	}
	/*
	 * Activate hotel perfect packages
	 */
	 function activate_hotel_perfect_packages($origin) {
		$status = ACTIVE;
		$info = $this->module_model->update_hotel_perfect_packages( $status, $origin );
		redirect ( base_url () . 'cms/hotel_perfect_packages' );
	}
	function deactivate_top_destination_home($origin) {
		$status = INACTIVE;
		$info = $this->module_model->update_top_destination_home( $status, $origin );
		redirect ( base_url () . 'cms/activityv1_top_destinations' );
	}
	function activate_top_destination_home($origin) {
		$status = ACTIVE;
		$info = $this->module_model->update_top_destination_home( $status, $origin );
		redirect ( base_url () . 'cms/activityv1_top_destinations' );
	}
	function deactivate_top_destination_activity($origin) {
		$status = INACTIVE;
		$info = $this->module_model->update_top_destination_activity ( $status, $origin );
		redirect ( base_url () . 'cms/activity_top_destinations' );
	}
	function deactivate_top_destination_activity_home($origin) {
		$status = INACTIVE;
		$info = $this->module_model->update_top_destination_activity_home( $status, $origin );
		redirect ( base_url () . 'cms/activity_top_destinations' );
	}
	function activate_top_destination_activity_home($origin) {
		$status = ACTIVE;
		$info = $this->module_model->update_top_destination_activity_home( $status, $origin );
		redirect ( base_url () . 'cms/activity_top_destinations' );
	}
	/**
	 * for Activity Perfect Packages
	 * active deactive delete(remove)
	 */
	function deactivate_activity_perfect_packages($origin) {
		$status = INACTIVE;
		$info = $this->module_model->update_activity_perfect_packages( $status, $origin );
		redirect ( base_url () . 'cms/activity_perfect_packages' );
	}
	function activate_activity_perfect_packages($origin) {
		$status = ACTIVE;
		$info = $this->module_model->update_activity_perfect_packages( $status, $origin );
		redirect ( base_url () . 'cms/activity_perfect_packages' );
	}
	function delete_activity_perfect_packages($origin) {
		$status = INACTIVE;
		$info = $this->module_model->delete_activity_perfect_packages( $status, $origin );
		redirect ( base_url () . 'cms/activity_perfect_packages' );
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
			$upload_path = realpath ( '../extras' ).'/custom/'.CURRENT_DOMAIN_KEY.'/images/';
			if (valid_array ( $_FILES ) == true and $_FILES ['top_destination'] ['error'] == 0 and $_FILES ['top_destination'] ['size'] > 0) {
				$config ['upload_path'] = $upload_path;
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
					$temp_top_destination_image = $upload_path.$top_destination_image; // GETTING FILE PATH
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
				$this->session->set_flashdata('message', UL0013);
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
	* Manage Flight perfect package
	*/
	function flight_perfect_package($offset = 0){
	    // Search Params(Country And City)
		// CMS - Image(On Home Page)
		$page_data = array ();
		$post_data = $this->input->post ();
		//debug($post_data);exit;
		if (valid_array ( $post_data ) == true) {
			$temp_location = explode('(', $post_data ['from_airport']);

			//debug($temp_location);
			if (isset($temp_location[0]) == true) {
				$from_city = trim($temp_location[1]);
				$from_code = trim($temp_location[0], '() ');
			} else {
				$from_city = '';
				$from_code = '';
			}
			//debug($from_city);exit;
			$temp_location = explode('(', $post_data ['to_airport']);
			//debug($temp_location);exit;
			if (isset($temp_location[0]) == true) {
				$to_city = trim($temp_location[1]);
				$to_code = trim($temp_location[0], '() ');
			} else {
				$to_code = '';
				$to_city = '';
			}
			// debug($from_city);
			// debug($to_city);exit;
$check_availability= $this->custom_db->single_table_records ( 'top_flight_destinations', '*', array (
					'from_airport_code' => $from_code, 'to_airport_code'=>$to_code
				) );
$from_city_name= $this->custom_db->single_table_records ( 'flight_airport_list', '*', array (
					'origin' => $from_code
				) );
$to_city_name= $this->custom_db->single_table_records ( 'flight_airport_list', '*', array (
					'origin' => $to_code
				) );
$start_city_name = $from_city_name['data'][0]['airport_city'];
$end_city_name=$to_city_name['data'][0]['airport_city'];
$start_city_code=$from_city_name['data'][0]['airport_code'];
$end_city_code=$to_city_name['data'][0]['airport_code'];

			if($from_code == $to_code){
				$page_data['message'] = 'From and To Airports must be different';
			}
            else if($check_availability['status']==TRUE)
            {
               $page_data['message'] = 'This routes is available, Please select another routes';
            }
			else{

				// FILE UPLOAD
				$upload_path = realpath ( '../extras' ).'/custom/'.CURRENT_DOMAIN_KEY.'/images/';
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
				// $from_temp_record = $this->custom_db->single_table_records ( 'flight_airport_list', '*', array (
				// 		'origin' => $from_aiport_origin
				// ) );
				// $to_temp_record = $this->custom_db->single_table_records ( 'flight_airport_list', '*', array (
				// 		'origin' => $to_aiport_origin
				// ) );
				
				// $top_destination_image = $temp_record ['data'] [0] ['image'];
				$top_destination_image ='';
				// DELETE OLD FILES
				if (empty ( $top_destination_image ) == false) {
					$temp_top_destination_image =  $upload_path.$top_destination_image; // GETTING FILE PATH
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
				
				 // debug($start_city_name);
				 // debug($end_city_name);
				
				$data['from_airport_name'] = $start_city_name;
				$data['from_airport_code'] = $start_city_code;
				$data['to_airport_code'] = $end_city_code;
				$data['to_airport_name'] = $end_city_name;
				$data['image'] = $image_data ['file_name'];
				$data['perfect_package'] = 1;
				$data['home_status'] = 0;
				$data['status'] = 0;
				// debug($data);exit;
				$this->custom_db->insert_record ( 'top_flight_destinations', $data );
				// debug($image_data);exit;
				
				$this->session->set_flashdata('message', UL0013);
				}
			}
			
			//refresh ();
		}


		$flight_list = $this->custom_db->single_table_records ( 'flight_airport_list', 'airport_city,origin', array (
				'airport_city !=' => ''
		), 0, 10000, array (
				'airport_city' => 'ASC'
		) );
		$data_list = $this->custom_db->single_table_records ( 'top_flight_destinations', '*', array('perfect_package' => 1), 0, 100000, array (
				'origin' => 'ASC',
				
		) );
		//echo $this->db->last_query();exit;
		//debug($flight_list);exit;
		if ($flight_list ['status'] == SUCCESS_STATUS) {
			$page_data ['flight_list'] = magical_converter ( array (
					'k' => 'origin',
					'v' => 'airport_city'
			), $flight_list );
		}
		// debug($page_data);exit;
		$page_data ['data_list'] = @$data_list ['data'];
		//debug($page_data);exit;
		$this->template->view ( 'cms/flight_perfect_package', $page_data );
	}
	/**
	 * Manage Flight Top Destinations
	 */
	function flight_top_destinations($id='',$offset = 0) {
	
		$page_data = array ();
		$post_data = $this->input->post ();
			
		if (valid_array ( $post_data ) == true) {
		    
		    
		    
			$temp_location = explode('(', $post_data ['from_airport1']);
			$temp_location[1]=str_replace(")","",$temp_location[1]);

			

			if (isset($temp_location[0]) == true) {
				$from_city = trim($temp_location[0]);
				$from_code = trim($temp_location[1], '() ');
			} else {
				$from_city = '';
				$from_code = '';
			}
				
			
			$temp_location = explode('(', $post_data ['to_airport2']);
				$temp_location[1]=str_replace(")","",$temp_location[1]);
				
				
		
			if (isset($temp_location[0]) == true) {
				$to_city = trim($temp_location[0]);
				$to_code = trim($temp_location[1], '() ');
			} else {
				$to_code = '';
				$to_city = '';
			}
		
		
$check_availability= $this->custom_db->single_table_records ( 'top_flight_destinations', '*', array (
					'from_airport_code' => $from_code, 'to_airport_code'=>$to_code
				) );

	
$from_city_name= $this->custom_db->single_table_records ( 'flight_airport_list', '*', array (
					'airport_code' => $from_code
				) );
$to_city_name= $this->custom_db->single_table_records ( 'flight_airport_list', '*', array (
					'airport_code' => $to_code
				) );
$start_city_name = $from_city_name['data'][0]['airport_city'];
$end_city_name=$to_city_name['data'][0]['airport_city'];
$start_city_code=$from_city_name['data'][0]['airport_code'];
$end_city_code=$to_city_name['data'][0]['airport_code'];

			if($from_code == $to_code){
				$page_data['message'] = 'From and To Airports must be different';
			}
            else if($check_availability['status']==TRUE && $post_data['file_update']!=1)
            {
               $page_data['message'] = 'This routes is available, Please select another routes';
            }
			else{


					// FILE UPLOAD
				$upload_path = realpath ( '../extras' ).'/custom/'.CURRENT_DOMAIN_KEY.'/images/';
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
				// $from_temp_record = $this->custom_db->single_table_records ( 'flight_airport_list', '*', array (
				// 		'origin' => $from_aiport_origin
				// ) );
				// $to_temp_record = $this->custom_db->single_table_records ( 'flight_airport_list', '*', array (
				// 		'origin' => $to_aiport_origin
				// ) );
				
				// $top_destination_image = $temp_record ['data'] [0] ['image'];
				$top_destination_image ='';
				// DELETE OLD FILES
				if (empty ( $top_destination_image ) == false) {
					$temp_top_destination_image =  $upload_path.$top_destination_image; // GETTING FILE PATH
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
				
					}
					 // debug($start_city_name);
				 // debug($end_city_name);
				
				$data['from_airport_name'] = $start_city_name;
				$data['from_airport_code'] = $start_city_code;
				$data['to_airport_code'] = $end_city_code;
				$data['to_airport_name'] = $end_city_name;
				$data['image'] = $image_data ['file_name'];
				$data['status'] = 1;
				$data['perfect_package'] = 0;
			
				
				
								//	debug($data);die;
				
				if ($id == 0) {
				$this->custom_db->insert_record ( 'top_flight_destinations', $data );
			}
			else
			{
		//	debug($post_data);
				//$insert_data['updated_at'] = date('Y-m-d H:i:s');
				if(empty($data['image']))
				{
				//	echo "hi";
				//	debug( $_FILES);
				$data['image'] = $post_data['top_destination_name'];
			}
//debug($data);die;
				$this->custom_db->update_record('top_flight_destinations',$data,array('origin'=>$id));
			}

			//	 debug($image_data);exit;
				
				$this->session->set_flashdata('message', UL0013);

					redirect ( base_url () . 'cms/flight_top_destinations' );
			
			}
			
			//refresh ();
		}
		
if ($id!=0) {
		$page_data['data'] = $this->custom_db->single_table_records('top_flight_destinations','*',array('origin'=>$id))['data'][0];
}

//debug($page_data['data']);die;
		$flight_list = $this->custom_db->single_table_records ( 'flight_airport_list', 'airport_city,origin', array (
				'airport_city !=' => ''
		), 0, 10000, array (
				'airport_city' => 'ASC'
		) );
		$data_list = $this->custom_db->single_table_records ( 'top_flight_destinations', '*', array('perfect_package' => 0), 0, 100000, array (
				'origin' => 'ASC',
				
		) );
		//echo $this->db->last_query();exit;
		//debug($flight_list);exit;
		if ($flight_list ['status'] == SUCCESS_STATUS) {
			$page_data ['flight_list'] = magical_converter ( array (
					'k' => 'origin',
					'v' => 'airport_city'
			), $flight_list );
		}
		// debug($page_data);exit;
		
		
		
		$page_data ['data_list'] = @$data_list ['data'];
		//debug($page_data);exit;
		$this->template->view ( 'cms/flight_top_destinations', $page_data );
	}
	function transfercms_top_destinations($offset = 0) {
		// Search Params(Country And City)
		// CMS - Image(On Home Page)
		$page_data = array ();
		$post_data = $this->input->post ();
//	debug($post_data);
		if (valid_array ( $post_data ) == true) {


	    
			$temp_location = explode('(', $post_data ['from_airport']);
			$temp_location[1]=str_replace(")","",$temp_location[1]);
			

			if (isset($temp_location[0]) == true) {
				$from_city = trim($temp_location[0]);
				$from_code = trim($temp_location[1], '() ');
			} else {
				$from_city = '';
				$from_code = '';
			}
				
			
			$temp_location = explode('(', $post_data ['to_airport']);
				$temp_location[1]=str_replace(")","",$temp_location[1]);
				
				
		
			if (isset($temp_location[0]) == true) {
				$to_city = trim($temp_location[0]);
				$to_code = trim($temp_location[1], '() ');
			} else {
				$to_code = '';
				$to_city = '';
			}
	
$check_availability= $this->custom_db->single_table_records ( 'top_transfercms_destinations', '*', array (
					'from_airport_code' => $from_code, 'to_airport_code'=>$to_code
				) );
$from_city_name= $this->custom_db->single_table_records ( 'flight_airport_list', '*', array (
					'airport_code' => $from_code
				) );
$to_city_name= $this->custom_db->single_table_records ( 'flight_airport_list', '*', array (
					'airport_code' => $to_code
				) );
	
	
	
	
$start_city_name = $from_city_name['data'][0]['airport_city'];
$end_city_name=$to_city_name['data'][0]['airport_city'];
$start_city_code=$from_city_name['data'][0]['airport_code'];
$end_city_code=$to_city_name['data'][0]['airport_code'];

			if($from_code == $to_code){
				$page_data['message'] = 'From and To Airports must be different';
			}
            else if($check_availability['status']==TRUE)
            {
               $page_data['message'] = 'This routes is available, Please select another routes';
            }
			else{

				// FILE UPLOAD
				$upload_path = realpath ( '../extras' ).'/custom/'.CURRENT_DOMAIN_KEY.'/images/';
			if (valid_array ( $_FILES ) == true and $_FILES ['top_destination'] ['error'] == 0 and $_FILES ['top_destination'] ['size'] > 0) {
				$config ['upload_path'] = $this->template->domain_image_upload_path ();
				$temp_file_name = $_FILES ['top_destination'] ['name'];
				$config ['allowed_types'] = '*';
				$config ['file_name'] = 'top-dest-fight-' . $start_city_name;
			//	$config ['max_size'] = '1000000';
				$config ['max_width'] = '';
				$config ['max_height'] = '';
				$config ['remove_spaces'] = false;
			//	debug($config );die;
				// UPDATE
				// $from_temp_record = $this->custom_db->single_table_records ( 'flight_airport_list', '*', array (
				// 		'origin' => $from_aiport_origin
				// ) );
				// $to_temp_record = $this->custom_db->single_table_records ( 'flight_airport_list', '*', array (
				// 		'origin' => $to_aiport_origin
				// ) );
				
				// $top_destination_image = $temp_record ['data'] [0] ['image'];
				$top_destination_image ='';
				// DELETE OLD FILES
			//	debug( $config);
			//	echo  $upload_path.$temp_file_name;
				if (empty ( $top_destination_image ) == false) {
					$temp_top_destination_image =  $upload_path.$top_destination_image; // GETTING FILE PATH
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
				
				 // debug($start_city_name);
				 // debug($end_city_name);
				
				$data['from_airport_name'] = $start_city_name;
				$data['nationality'] = $this->input->post('country');
				$data['from_airport_code'] = $start_city_code;
				$data['to_airport_code'] = $end_city_code;
				$data['to_airport_name'] = $end_city_name;
				$data['image'] = $image_data ['file_name'];
				$data['status'] = 1;
				$data['perfect_package'] = 0;
				// debug($data);exit;
				$this->custom_db->insert_record ( 'top_transfercms_destinations', $data );
				// debug($image_data);exit;
				
				$this->session->set_flashdata('message', UL0013);
				}
			}
			
			//refresh ();
		}


		$flight_list = $this->custom_db->single_table_records ( 'flight_airport_list', 'airport_city,origin', array (
				'airport_city !=' => ''
		), 0, 10000, array (
				'airport_city' => 'ASC'
		) );
		$data_list = $this->custom_db->single_table_records ( 'top_transfercms_destinations', '*', array('perfect_package' => 0), 0, 100000, array (
				'origin' => 'ASC',
				
		) );
			$country_list = $this->custom_db->single_table_records ( 'api_country_master', 'country_name,origin,iso_country_code', array (
				'country_name !=' => '' 
		), 0, 1000, array (
				'country_name' => 'ASC' 
		) );
			if ($country_list ['status'] == SUCCESS_STATUS) {
			$page_data ['country_list'] = magical_converter ( array (
					'k' => 'iso_country_code',
					'v' => 'country_name' 
			), $country_list );
		}
		//echo $this->db->last_query();exit;
		//debug($flight_list);exit;
		if ($flight_list ['status'] == SUCCESS_STATUS) {
			$page_data ['flight_list'] = magical_converter ( array (
					'k' => 'origin',
					'v' => 'airport_city'
			), $flight_list );
		}
		// debug($page_data);exit;
		$page_data ['data_list'] = @$data_list ['data'];
		//debug($page_data);exit;
		$this->template->view ( 'cms/transcms_top_destinations', $page_data );
	}
	function deactivate_transfercms_top_destination($origin) {
		//$status = INACTIVE;
		$status = 0;
		$info = $this->module_model->update_transfercms_top_destination ( $status, $origin );
		redirect ( base_url () . 'cms/transfercms_top_destinations' );
	}
	function deactivate_transfercms_top_destination_home($origin) {
		//$status = INACTIVE;
		$status = 0;
		$info = $this->module_model->update_transfercms_top_destination_home ( $status, $origin );
		redirect ( base_url () . 'cms/transfercms_top_destinations' );
	}
	
	function activate_transfercms_top_destination($origin) {
		//$status = ACTIVE;
		$status = 1;
		$info = $this->module_model->update_transfercms_top_destination ( $status, $origin );
		redirect ( base_url () . 'cms/transfercms_top_destinations' );
	}
	function activate_transfercms_top_destination_home($origin) {
		//$status = ACTIVE;
		$status = 1;
		$info = $this->module_model->update_transfercms_top_destination_home($status, $origin );
		//debug("hrere");exit;
		redirect ( base_url () . 'cms/transfercms_top_destinations' );
	}
	function delete_transfercms_top_destination($origin) {
		// echo $origin;exit;
		$this->custom_db->delete_record ( 'top_transfercms_destinations', array ('origin' => $origin));
		// echo $this->db->last_query();exit;
		redirect ('index.php/cms/transfercms_top_destinations' );
	}
	/**
	 * Deactivate Top Bus Destination
	 */
	function deactivate_flight_top_destination($origin) {
		//$status = INACTIVE;
		$status = 0;
		$info = $this->module_model->update_flight_top_destination ( $status, $origin );
		redirect ( base_url () . 'cms/flight_top_destinations' );
	}
	function deactivate_flight_top_destination_home($origin) {
		//$status = INACTIVE;
		$status = 0;
		$info = $this->module_model->update_flight_top_destination_home ( $status, $origin );
		redirect ( base_url () . 'cms/flight_top_destinations' );
	}
	/**
	 * Deactivate Top Bus Destination
	 */
	function activate_flight_top_destination($origin) {
		//$status = ACTIVE;
		$status = 1;
		$info = $this->module_model->update_flight_top_destination ( $status, $origin );
		redirect ( base_url () . 'cms/flight_top_destinations' );
	}
	function activate_flight_top_destination_home($origin) {
		//$status = ACTIVE;
		$status = 1;
		$info = $this->module_model->update_flight_top_destination_home($status, $origin );
		//debug("hrere");exit;
		redirect ( base_url () . 'cms/flight_top_destinations' );
	}
	/**
	 * Deactivate flight perfect package activate or deactivate
	 */
	function deactivate_perfect_packages_status($origin) {
		//$status = INACTIVE;
		$status = 0;
		$info = $this->module_model->update_flight_perfect_packages_status( $status, $origin );
		redirect ( base_url () . 'cms/flight_perfect_package' );
	}
	function activate_perfect_packages_status($origin) {
		//$status = ACTIVE;
		$status = 1;
		$info = $this->module_model->update_flight_perfect_packages_status($status, $origin );
		//debug("hrere");exit;
		redirect ( base_url () . 'cms/flight_perfect_package' );
	}
	function delete_flight_perfect_packages($origin) {
		// echo $origin;exit;
		$this->custom_db->delete_record ( 'top_flight_destinations', array ('origin' => $origin));
		// echo $this->db->last_query();exit;
		redirect ( 'cms/flight_perfect_package' );
	}
	/*
	Delete flight top destination
	*/
	function delete_flight_top_destination($origin) {
		// echo $origin;exit;
		$this->custom_db->delete_record ( 'top_flight_destinations', array ('origin' => $origin));
		// echo $this->db->last_query();exit;
		redirect ( 'cms/flight_top_destinations' );
	}
	
	
	/**
	 * Static Page Content
	 */
	function add_cms_page($id = '') {
		
		// privilege_handler('p54');
		$this->form_validation->set_message ( 'required', 'Required.' );
		$id=$_POST['page_id'];
		// check for negative id
		valid_integer ( $id );
		
		// validation rules
		$post_data = $this->input->post ();
		// get data
		$cols = ' * ';
		if (!isset($post_data ['page_title'])) {
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
					$data ['page_url'] = $tmp_data ['data'] [0] ['page_url'];
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
			$data ['page_url'] = $this->input->post ( 'page_url' );
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
	/*
	Delete CMS page
	*/
	function delete_cms_page($page_id){
		$this->custom_db->delete_record ( 'cms_pages', array ('page_id' => $page_id));
		redirect ( 'cms/add_cms_page' );
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
		$query="select * from why_choose_us where status=".ACTIVE;
		$re_count=$this->db->query($query)->num_rows();
		if($re_count <=3)
		{
			$info = $this->custom_db->update_record ( 'why_choose_us', array (
						'status' => ACTIVE), array (
						'origin' => $origin 
				) );
			echo 1;
		}
		else
		{
			echo 2;
		}

		

		exit;
	}
	function deactivate_why_choose($origin){
		$info = $this->custom_db->update_record ( 'why_choose_us', array (
						'status' => INACTIVE), array (
						'origin' => $origin 
				) );
		exit;
	}
	function investment_chart(){
		$page_data = array ();
		$data_list = $this->custom_db->single_table_records ( 'investment_chart', '*', '', 0, 100000 );
		$page_data ['data_list'] = @$data_list ['data'];
		// debug($page_data);exit;
		$this->template->view ( 'cms/investment_chart', $page_data);
	}
	function add_investment_chart(){
		$post_data = $this->input->post();
		$get_data = $this->input->get();
		// debug($get_data);exit;
		if(valid_array($post_data)){
			// debug($post_data);exit;
			$data['package_title'] = $post_data['package_title'];
			$data['invest'] = $post_data['invest'];
			$data['monthly_income'] = $post_data['monthly_income'];
			$data['yearly_profits'] = $post_data['yearly_profits'];
			$data['deposit_returns'] = $post_data['deposit_returns'];
			$data['months_profit'] = $post_data['months_profit'];
			$data['status'] = ACTIVE;
			$list = $this->custom_db->single_table_records ( 'investment_chart', '*', array (
						'origin' => $get_data['origin']
				) );
			$why_choose_data = $this->custom_db->single_table_records ( 'investment_chart', '*', array (
						'package_title' => $post_data['package_title'],
						'invest' => $post_data['invest'],
						'monthly_income' => $post_data['monthly_income'],
						'yearly_profits' => $post_data['yearly_profits'],
						'deposit_returns' => $post_data['deposit_returns'],
						'months_profit' => $post_data['months_profit']
				) );
				if($list['status'] == FAILURE_STATUS ){
					if($why_choose_data['status'] == FAILURE_STATUS){
						$insert_id = $this->custom_db->insert_record ( 'investment_chart', $data );
					}
					else{
						redirect ( 'cms/add_investment_chart/Duplicate Title' );
					}
				}
				else{
				// debug($get_data);exit;
				if(!empty($get_data) && valid_array($get_data)){
					$this->custom_db->update_record ( 'investment_chart', array (
						'package_title' => $post_data['package_title'],
						'invest' => $post_data['invest'],
						'monthly_income' => $post_data['monthly_income'],
						'yearly_profits' => $post_data['yearly_profits'],
						'deposit_returns' => $post_data['deposit_returns'],
						'months_profit' => $post_data['months_profit'] 
					), array (
						'origin' => $get_data['origin'] 
					) );
				}
				
			}
			// debug($insert_id);exit;
			redirect ( 'cms/investment_chart' );
		}
		else{
			$page_data = array();
			if(valid_array($get_data)){
				$list = $this->custom_db->single_table_records ( 'investment_chart', '*', array (
						'origin' => $get_data['origin'] 
				) );
				$page_data['package_title'] = $list['data'][0]['package_title'];
				$page_data['invest'] = $list['data'][0]['invest'];
				$page_data['monthly_income'] = $list['data'][0]['monthly_income'];
				$page_data['yearly_profits'] = $list['data'][0]['yearly_profits'];
				$page_data['deposit_returns'] = $list['data'][0]['deposit_returns'];
				$page_data['months_profit'] = $list['data'][0]['months_profit'];
			}
			$this->template->view ( 'cms/add_investment_chart', $page_data);
		}
	}
	function activate_investment_chart($origin){
		$query="select * from investment_chart where status=".ACTIVE;
		$re_count=$this->db->query($query)->num_rows();
		if($re_count <=3)
		{
			$info = $this->custom_db->update_record ( 'investment_chart', array (
						'status' => ACTIVE), array (
						'origin' => $origin 
				) );
			echo 1;
		}
		else
		{
			echo 2;
		}

		

		exit;
	}
	function deactivate_investment_chart($origin){
		$info = $this->custom_db->update_record ( 'investment_chart', array (
						'status' => INACTIVE), array (
						'origin' => $origin 
				) );
		exit;
	}
	public function delete_investment_chart($origin) {
		$this->custom_db->delete_record ( 'investment_chart', array ('origin' => $origin));
		redirect ( 'cms/investment_chart' );
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
		// debug($post_data);exit;
		if(valid_array($post_data)){
			$data['airline_name'] = ucwords($post_data['airline_name']);
			$data['status'] = ACTIVE;

			if (valid_array($_FILES) == true and $_FILES['airline_logo']['error'] == 0 and $_FILES['airline_logo']['size'] > 0) {
					if( function_exists( "check_mime_image_type" ) ) {
					    if ( !check_mime_image_type( $_FILES['top_destination']['tmp_name'] ) ) {
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
						$data['logo'] = @$image_data['file_name'];
					}
	                /*UPDATING IMAGE */
					
				}
			$list = $this->custom_db->single_table_records ( 'top_airlines', '*', array (
						'origin' => $get_data['origin']
					) );

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
				// debug($get_data);exit;
				if(!empty($get_data) && valid_array($get_data)){
					$this->custom_db->update_record ( 'top_airlines', array (
						'airline_name' => ucwords($post_data['airline_name']),
						'logo' => $logo 
					), array (
						'origin' => $get_data['origin'] 
					) );
				}
				
			}
			// debug($insert_id);exit;
			redirect ( 'cms/top_airlines' );
		}
		else{
			if(valid_array($get_data)){
				$list = $this->custom_db->single_table_records ( 'top_airlines', '*', array (
						'origin' => $get_data['origin'] 
				) );
				// debug($list);exit;
				$page_data['airline_name'] = $list['data'][0]['airline_name'];
				$page_data['logo'] = $list['data'][0]['logo'];
			}
			$page_data['airline_list'] = $this->custom_db->single_table_records ( 'airline_list', '*', '' );
			$this->template->view ( 'cms/add_top_airlines', $page_data);
		}
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

	function add_voucher_content(){
		$post_data = $this->input->post();
		 //debug($post_data);exit;
		if(valid_array($post_data)){
			$this->custom_db->update_record ( 'voucher_details', array (
						'note' => $post_data['note'],
						'phone' => $post_data['phone'],
						'email' => $post_data['email'],
						), array (
						'id' => $post_data['id'] 
				) );
			$this->session->set_flashdata(array('message' => 'UL0013', 'type' => SUCCESS_MESSAGE));
			refresh();
		
		}
		$domain_data = $this->custom_db->single_table_records ( 'voucher_details', '*', '' );
		
		// debug($footer_data);exit;
		$page_data['note'] = $domain_data['data'][0]['note'];
		$page_data['id'] = $domain_data['data'][0]['id'];
		$page_data['email'] = $domain_data['data'][0]['email'];
		$page_data['phone'] = $domain_data['data'][0]['phone'];
		
		$this->template->view('cms/add_voucher_details', $page_data);
	}

	function contact_us(){
		 $post_data = $this->input->post();
		// // debug($post_data);exit;
		
		if(isset($post_data['branch_name'])){
		     $upload_path = realpath ( '../extras' ).'/custom/'.CURRENT_DOMAIN_KEY.'/images/';
		     $branches=array();
		     if(is_array($post_data['branch_name'])){
		         //debug($post_data);
		         //debug($_FILES['branch_flag']);exit();
		         $images = array();
		         
		         foreach($post_data['branch_name'] as $key => $branch){
		             $branches[$key]['branch_name'] = $post_data['branch_name'][$key];
		             $branches[$key]['branch_phone'] = $post_data['branch_phone'][$key];
		             $branches[$key]['branch_order'] = $post_data['branch_order'][$key];
		             $branches[$key]['branch_address'] = $post_data['branch_address'][$key];
		             
		             
		             
            		$branch_name= str_replace(' ', '-', $post_data['branch_name'][$key]);
            		$branch_flag_image="0";	
            		if($_FILES ['branch_flag']['name'][$key] != ""){
                        $_FILES['images[]']['name']= $_FILES ['branch_flag']['name'][$key];
                        $_FILES['images[]']['type']= $_FILES ['branch_flag']['type'][$key];
                        $_FILES['images[]']['tmp_name']= $_FILES ['branch_flag']['tmp_name'][$key];
                        $_FILES['images[]']['error']= $_FILES ['branch_flag']['error'][$key];
                        $_FILES['images[]']['size']= $_FILES ['branch_flag']['size'][$key];
            
                		if (valid_array ( $_FILES ) == true and $_FILES['images[]']['error'] == 0 and $_FILES['images[]']['size'] > 0) {
                				// $config ['upload_path'] = $this->template->domain_image_full_path ();
                				$config ['upload_path'] = $upload_path;
                				$img_name = '-'.time();
                				$temp_file_name = $_FILES['images[]']['name'];
                				
                				$config ['allowed_types'] = '*';
                				$config ['file_name'] = $branch_name.'-' . $img_name;
                				$config ['max_size'] = '1000000';
                				$config ['max_width'] = '';
                				$config ['max_height'] = '';
                				$config ['remove_spaces'] = false;
                				// UPDATE
                				
                				// UPLOAD IMAGE
                				$this->load->library ( 'upload', $config );
                				$this->upload->initialize ( $config );
                				if (! $this->upload->do_upload ('images[]')) {
                					echo $this->upload->display_errors ();
                				} else {
                					$image_data = $this->upload->data ();
                				}
                				
                				$branch_flag_image = $image_data ['file_name'];
                				
                		}
                		$branches[$key]['branch_flag'] = $branch_flag_image;
            		}else{
            		    $branches[$key]['branch_flag'] = $post_data['hide_flag_name'][$key];
            		}
            		 
		             
		         }
		     }
		     $branch_details= json_encode($branches);
		     //debug($branches);exit;
		     
		     //branches_detail
		 }
		if(valid_array($post_data)){
			$this->custom_db->update_record ( 'contact_us_details', array (
						'address1' => $post_data['address1'],
						// 'address2' => $post_data['address2'],
						'phone1' => $post_data['phone1'],
						'phone2' => $post_data['phone2'],
						'phone3' => $post_data['phone3'],
						'email1' => $post_data['email1'],
						'email2' => $post_data['email2'],
						'email3' => $post_data['email3'],
						'branches_detail' => $branch_details,
						), array (
						'id' => $post_data['id'] 
				) );
			$this->session->set_flashdata(array('message' => 'UL0013', 'type' => SUCCESS_MESSAGE));
			refresh();
		
		}
		 $domain_data = $this->custom_db->single_table_records ( 'contact_us_details', '*', '' );
		
		// // debug($footer_data);exit;
		 $page_data['address1'] = $domain_data['data'][0]['address1'];
		 //$page_data['address2'] = $domain_data['data'][0]['address2'];
		  $page_data['phone1'] = $domain_data['data'][0]['phone1'];
		   $page_data['phone2'] = $domain_data['data'][0]['phone2'];
		   $page_data['phone3'] = $domain_data['data'][0]['phone3'];
		   $page_data['email1'] = $domain_data['data'][0]['email1'];
		   $page_data['email2'] = $domain_data['data'][0]['email2'];
		   $page_data['email3'] = $domain_data['data'][0]['email3'];
		 $page_data['id'] = $domain_data['data'][0]['id'];
		 $page_data['branches_detail'] = $domain_data['data'][0]['branches_detail'];
		 
		
		
		$this->template->view('cms/contact_us_details', $page_data);
	}
	function plan_retirement(){
		$post_data = $this->input->post();
		// debug($post_data);exit;
		$domain_data = $this->custom_db->single_table_records ( 'plan_retirement', '*', '', 0, 100000, array (
				'id' => 'DESC'	 
		) );
		$page_data['plan_retirement'] = $domain_data['data'];
		
		$this->template->view('cms/plan_retirement', $page_data);
	}
	function add_invester()
	{
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
        
      }
      // debug($phone_code_array);exit;
      $domain_origin = get_domain_auth_id();
      $data['phone_code_array'] = $phone_code_array;
      $data['country_list'] = $this->db_cache_api->get_country_list();
		$this->template->view('cms/add_invester', $data);
	}
	function add_invester_details()
	{
		$page_data = $this->input->post();
		//debug($page_data);exit;
		//debug($_FILES);exit;
		$id_image_data = array();
		$passport_image_data = array();
		// FILE UPLOAD
		if (valid_array ( $_FILES ) == true and $_FILES ['passid'] ['error'] == 0 and $_FILES ['passid'] ['size'] > 0) {
			$img_name = '-'.time();
			$config ['upload_path'] = $this->template->domain_image_upload_path ();
			$temp_file_name1 = $_FILES ['passid'] ['name'];
			$config ['allowed_types'] = '*';
			$config ['file_name'] ='IMG'.$img_name;
			$config ['max_size'] = '1000000';
			$config ['max_width'] = '';
			$config ['max_height'] = '';
			$config ['remove_spaces'] = false;
			// UPLOAD IMAGE
			$this->load->library ( 'upload', $config );
			$this->upload->initialize ( $config );
			if (! $this->upload->do_upload ( 'passid' )) {
				echo $this->upload->display_errors ();
			} else {
				$id_image_data = $this->upload->data ();
			}
		}
		if (valid_array ( $_FILES ) == true and $_FILES ['passcopy'] ['error'] == 0 and $_FILES ['passcopy'] ['size'] > 0) {
			$img_name = '-'.time();
			$config2 ['upload_path'] = $this->template->domain_image_upload_path ();
			$temp_file_name2 = $_FILES ['passcopy'] ['name'];
			$config2 ['allowed_types'] = '*';
			$config2 ['file_name'] ='IMG'.$img_name;
			$config2 ['max_size'] = '1000000';
			$config2 ['max_width'] = '';
			$config2 ['max_height'] = '';
			$config2 ['remove_spaces'] = false;
			// UPLOAD IMAGE
			$this->load->library ('upload', $config2);
			$this->upload->initialize ($config2);
			if (! $this->upload->do_upload ('passcopy')) {
				echo $this->upload->display_errors();
			} else {
				$passport_image_data = $this->upload->data ();
			}
		}
		$page_data['passid'] = $id_image_data['file_name'];
		$page_data['passcopy'] = $passport_image_data['file_name'];
		// $page_data['payment_method'] = PAY_NOW;	
		// $page_data['get_package'] = ltrim($page_data['packselect'], 'USD ');
		// debug($page_data);exit;
		$get_users = $this->user_model->get_plan_retirement($page_data);
		$module = PLAN_RETIREMENT;
        $book_id = $module.date('d-His').'-'.rand(1,1000000);
        $user_app_reference['app_reference'] = $book_id;
        $this->custom_db->update_record('plan_retirement', $user_app_reference, array('id' => $get_users['insert_id']));
		$this->session->set_flashdata(array('message' => 'UL0014', 'type' => SUCCESS_MESSAGE));
		redirect ( 'cms/plan_retirement' );
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
		$this->custom_db->delete_record ( 'why_choose_us', array ('origin' => $origin));
		redirect ( 'cms/top_airlines' );
	}
	public function delete_tour_styles($origin) {
		$this->custom_db->delete_record ( 'tour_styles', array ('origin' => $origin));
		redirect ( 'cms/tour_styles' );
	}
        public function seo(){
if(isset($_POST['id']))
{
      $page_data = array ();
		$id=$_POST['id'];
		$filter = ['id'=>$id];
		$data_list = $this->custom_db->single_table_records ( 'seo', '*', $filter, 0, 100000 );
		$page_data ['data_list'] = @$data_list ['data'];
		$this->template->view ( 'cms/seo_edit', $page_data );
} else { 
		$data['data_list'] = $this->custom_db->single_table_records('seo');
		$this->template->view ( 'cms/seo', $data );
}
	}

	public function edit_seo(){
		$page_data = array ();
		$id=$_POST['id'];
		$filter = ['id'=>$id];
		$data_list = $this->custom_db->single_table_records ( 'seo', '*', $filter, 0, 100000 );
		$page_data ['data_list'] = @$data_list ['data'];
		$this->template->view ( 'cms/seo_edit', $page_data );

	}
	public function update_seo_action_flight(){
		$insert_data = [];
		$post_data = $this->input->post();
		 //debug($post_data);exit;
		$BID = $post_data['BID'];
		if(valid_array($post_data) == true) {

			//POST DATA formating to update
			$insert_data = array('description'=>$post_data['description'],'title'=>$post_data['title'],'keyword'=>$post_data['keyword']);
		}
		/*UPDATING OTHER FIELDS*/
		$this->custom_db->update_record('seo_flight',$insert_data,array('id' => $BID));
		$this->seo_flight();
	}
	public function update_seo_action_hotel(){
		$insert_data = [];
		$post_data = $this->input->post();
		 //debug($post_data);exit;
		$BID = $post_data['BID'];
		if(valid_array($post_data) == true) {

			//POST DATA formating to update
			$insert_data = array('description'=>$post_data['description'],'title'=>$post_data['title'],'keyword'=>$post_data['keyword']);
		}
		/*UPDATING OTHER FIELDS*/
		$this->custom_db->update_record('seo_hotel',$insert_data,array('id' => $BID));
		$this->seo_hotel();
	}
	
	

	public function update_seo_action(){
		$insert_data = [];
		$post_data = $this->input->post();
		// debug($post_data);exit;
		$BID = $post_data['BID'];
		if(valid_array($post_data) == true) {

			//POST DATA formating to update
			$insert_data = array('description'=>$post_data['description'],'title'=>$post_data['title'],'keyword'=>$post_data['keyword']);
		}
		/*UPDATING OTHER FIELDS*/
		$this->custom_db->update_record('seo',$insert_data,array('id' => $BID));
		$this->seo();
	}
	
	function seo_flight(){
	    	$data['data_list'] = $this->custom_db->single_table_records('seo_flight');
	    	//debug($data['data_list']);die;
		  $this->template->view ( 'cms/seo_flight', $data );
	}
	function seo_hotel(){
	    	$data['data_list'] = $this->custom_db->single_table_records('seo_hotel');
	    	//debug($data['data_list']);die;
		  $this->template->view ( 'cms/seo_hotel', $data );
	}
	function edit_seo_flight(){
	    $page_data = array ();
		//debug($_POST[id]);die;
		$data_list = $this->custom_db->single_table_records ( 'seo_flight', '*', array('id' => $_POST['id']) );
		// debug($data_list);exit;
		$page_data ['data_list'] = @$data_list ['data'];
		$this->template->view ( 'cms/seo_edit_flight', $page_data );
	}
	function edit_seo_hotel(){
	    $page_data = array ();
		//debug($_POST[id]);die;
		$data_list = $this->custom_db->single_table_records ( 'seo_hotel', '*', array('id' => $_POST['id']) );
		// debug($data_list);exit;
		$page_data ['data_list'] = @$data_list ['data'];
		$this->template->view ( 'cms/seo_edit_hotel', $page_data );
	}
	/* Terms and conditions for all modules voucher page */
	function terms_conditions(){
		$data['data_list'] = $this->custom_db->single_table_records('terms_conditions');
		$this->template->view ( 'cms/terms_conditions', $data );
	}
		function terms_conditions_supplier(){
		$data['data_list'] = $this->custom_db->single_table_records('terms_condition_supplier');
		$this->template->view ( 'cms/terms_conditions_supplier', $data );
	}
	public function edit_terms_conditions($origin){
		//debug($origin);die;
		$page_data = array ();
		//debug($origin);die;
		$data_list = $this->custom_db->single_table_records ( 'terms_conditions', '*', array('origin' => $origin) );
		// debug($data_list);exit;
		$page_data ['data_list'] = @$data_list ['data'];
		$this->template->view ( 'cms/terms_conditions_edit', $page_data );

	}
		public function edit_terms_conditions_supplier($origin){
		$page_data = array ();
		//echo $origin;die;
		$data_list = $this->custom_db->single_table_records ( 'terms_condition_supplier', '*', array('id' => $origin) );
		// debug($data_list);exit;
		$page_data ['data_list'] = @$data_list ['data'];
		//debug($page_data);die;
		$this->template->view ( 'cms/terms_conditions_edit_supplier', $page_data );

	}
	public function update_terms_action($id){
		$post_data = $this->input->post();
		// debug($post_data);exit;
		
		if(valid_array($post_data) == true) {
			//POST DATA formating to update
			$insert_data = array('description'=>$post_data['description']);
			// debug($insert_data);exit;
			$this->custom_db->update_record('terms_conditions',$insert_data,array('origin' => $id));
		}
		redirect('cms/terms_conditions');
		
		
	}
	public function update_terms_action_supplier($id){
	  //debug($_REQUEST);die;
		$post_data = $this->input->post();
		//$post_data1 = $this->input->get();
		//debug($post_data);exit;
		
		if(valid_array($post_data) == true) {
			//POST DATA formating to update
			$insert_data = array('terms_n_conditions'=>$post_data['description']);
			 //debug($insert_data);exit;
			$this->custom_db->update_record('terms_condition_supplier',$insert_data,array('id' => $id));
			//echo $this->db->last_query();die;
		}
		redirect('cms/terms_conditions_supplier');
		
		
	}

	function transfers_top_destinations($offset = 0) {
		// Search Params(Country And City)
		// CMS - Image(On Home Page)
		error_reporting(0);
		$page_data = array ();
		$post_data = $this->input->post ();
		if (valid_array ( $post_data ) == true) {
			$city_origin = $post_data ['country'];
			// FILE UPLOAD
			$upload_path = realpath ( '../extras' ).'/custom/'.CURRENT_DOMAIN_KEY.'/images/';
			// echo $this->template->domain_image_full_path ();exit;
			if (valid_array ( $_FILES ) == true and $_FILES ['top_destination'] ['error'] == 0 and $_FILES ['top_destination'] ['size'] > 0) {
				// $config ['upload_path'] = $this->template->domain_image_full_path ();
				$config ['upload_path'] = $upload_path;
				$temp_file_name = $_FILES ['top_destination'] ['name'];
				$config ['allowed_types'] = '*';
				$config ['file_name'] = 'top-dest-activity-' . $city_origin;
				$config ['max_size'] = '1000000';
				$config ['max_width'] = '';
				$config ['max_height'] = '';
				$config ['remove_spaces'] = false;
				// UPDATE
				$temp_record = $this->custom_db->single_table_records ( 'api_sightseeing_destination_list', 'image_transfers', array (
						'origin' => $city_origin 
				) );
				$top_destination_image = $temp_record ['data'] [0] ['image_activity'];
				// DELETE OLD FILES
				if (empty ( $top_destination_image ) == false) {
					$temp_top_destination_image = $upload_path.$top_destination_image; // GETTING FILE PATH
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
				// echo $config ['upload_path'];
				// exit;
				// debug($image_data);exit;

				$this->custom_db->update_record ( 'api_sightseeing_destination_list', array (
						'top_destination_transfers' => ACTIVE,
						'transfer_perfect_package' => INACTIVE,
						'image_transfers' => $image_data ['file_name'] 
				), array (
						'origin' => $city_origin 
				) );
				
				$this->session->set_flashdata('message', UL0013);
			}
			refresh ();
		}
		$filter = array (
				'top_destination_transfers' => ACTIVE 
		);
		$country_list = $this->custom_db->single_table_records( 'api_sightseeing_destination_list', 'destination_name,destination_id,origin', array (
				'destination_name !=' => '' 
		), 0, 1000000000, array (
				'destination_name' => 'ASC' 
		) );
		// debug($country_list);exit;
		$data_list = $this->custom_db->single_table_records ( 'api_sightseeing_destination_list', '*', $filter, 0, 100000, array (
				'top_destination_transfers' => 'DESC',
				'destination_name' => 'ASC' 
		) );
		// debug($country_list);exit;
		/*if ($country_list ['status'] == SUCCESS_STATUS) {
			$page_data ['country_list'] = magical_converter ( array (
					'k' => 'destination_id',
					'v' => 'destination_name' 
			), $country_list );
		}*/ 
		$page_data ['country_list']=$country_list['data'];
		$page_data ['data_list'] = @$data_list ['data'];
		// debug($page_data ['data_list']);exit;
		$this->template->view ( 'cms/top_destination_transfers', $page_data );
	}
	/**
	 *  for transfer perfect packages
	 */
	 
	function transfers_perfect_packages($offset = 0){
	
	    // Search Params(Country And City)
		// CMS - Image(On Home Page)
		error_reporting(0);
		$page_data = array ();
		$post_data = $this->input->post ();
		if (valid_array ( $post_data ) == true) {
			$city_origin = $post_data ['country'];
			// FILE UPLOAD
			$upload_path = realpath ( '../extras' ).'/custom/'.CURRENT_DOMAIN_KEY.'/images/';
			// echo $this->template->domain_image_full_path ();exit;
			if (valid_array ( $_FILES ) == true and $_FILES ['top_destination'] ['error'] == 0 and $_FILES ['top_destination'] ['size'] > 0) {
				// $config ['upload_path'] = $this->template->domain_image_full_path ();
				$config ['upload_path'] = $upload_path;
				$temp_file_name = $_FILES ['top_destination'] ['name'];
				$config ['allowed_types'] = '*';
				$config ['file_name'] = 'top-dest-activity-' . $city_origin;
				$config ['max_size'] = '1000000';
				$config ['max_width'] = '';
				$config ['max_height'] = '';
				$config ['remove_spaces'] = false;
				// UPDATE
				$temp_record = $this->custom_db->single_table_records ( 'api_sightseeing_destination_list', 'image_transfers', array (
						'origin' => $city_origin 
				) );
				$top_destination_image = $temp_record ['data'] [0] ['image_activity'];
				// DELETE OLD FILES
				if (empty ( $top_destination_image ) == false) {
					$temp_top_destination_image = $upload_path.$top_destination_image; // GETTING FILE PATH
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
				// echo $config ['upload_path'];
				// exit;
				// debug($image_data);exit;

				$this->custom_db->update_record ( 'api_sightseeing_destination_list', array (
						'top_destination_transfers' => INACTIVE,
						'transfer_perfect_package' => ACTIVE,
						'image_transfers' => $image_data ['file_name'] 
				), array (
						'origin' => $city_origin 
				) );
				
				$this->session->set_flashdata('message', UL0013);
			}
			refresh ();
		}
		$filter = array (
				'transfer_perfect_package' => ACTIVE 
		);
		$country_list = $this->custom_db->single_table_records( 'api_sightseeing_destination_list', 'destination_name,destination_id,origin', array (
				'destination_name !=' => '' 
		), 0, 1000000000, array (
				'destination_name' => 'ASC' 
		) );
		// debug($country_list);exit;
		$data_list = $this->custom_db->single_table_records ( 'api_sightseeing_destination_list', '*', $filter, 0, 100000, array (
				'transfer_perfect_package' => 'DESC',
				'destination_name' => 'ASC' 
		) );
		// debug($country_list);exit;
		/*if ($country_list ['status'] == SUCCESS_STATUS) {
			$page_data ['country_list'] = magical_converter ( array (
					'k' => 'destination_id',
					'v' => 'destination_name' 
			), $country_list );
		}*/ 
		$page_data ['country_list']=$country_list['data'];
		$page_data ['data_list'] = @$data_list ['data'];
		// debug($page_data ['data_list']);exit;
		$this->template->view ( 'cms/transfers_perfect_packages', $page_data );
	}
	function deactivate_top_destination_car($origin) {
		$status = INACTIVE;
		$info = $this->module_model->update_top_destination_car ( $status, $origin );
		redirect ( base_url () . 'cms/car_top_destinations' );
	}

	function deactivate_top_inner_destination_car($origin) {
		$status = INACTIVE;
		$info = $this->module_model->update_top_inner_destination_car ( $status, $origin );
		redirect ( base_url () . 'cms/car_inner_top_destinations' );
	}
	
	/**
	 * for car perfect packages activate deactivate delete
	 */
	 function deactivate_car_perfect_packages($origin) {
		$status = INACTIVE;
		$info = $this->module_model->update_car_perfect_packages ( $status, $origin );
		redirect ( base_url () . 'cms/car_perfect_packages' );
	}
	function activate_car_perfect_packages($origin) {
		$status = ACTIVE;
		$info = $this->module_model->update_car_perfect_packages ( $status, $origin );
		redirect ( base_url () . 'cms/car_perfect_packages' );
	}
	function delete_car_perfect_packages($origin) {
		$status = INACTIVE;
		$info = $this->module_model->delete_car_perfect_packages ( $status, $origin );
		redirect ( base_url () . 'cms/car_perfect_packages' );
	}

	function deactivate_top_destination_transfers($origin) {
		$status = INACTIVE;
		$info = $this->module_model->update_top_destination_transfers ( $status, $origin );
		redirect ( base_url () . 'cms/transfers_top_destinations' );
	}
	function deactivate_top_destination_transfers_home($origin) {
		$status = INACTIVE;
		$info = $this->module_model->update_top_destination_transfers_home( $status, $origin );
		redirect ( base_url () . 'cms/transfers_top_destinations' );
	}
	function activate_top_destination_transfers_home($origin) {
		$status = ACTIVE;
		$info = $this->module_model->update_top_destination_transfers_home( $status, $origin );
		redirect ( base_url () . 'cms/transfers_top_destinations' );
	}
	/**
	 * for transfer perfect package
	 */
	 function delete_transfer_perfect_packages($origin) {
		$status = INACTIVE;
		$info = $this->module_model->delete_transfer_perfect_packages( $status, $origin );
		redirect ( base_url () . 'cms/transfers_perfect_packages' );
	}
	 function deactivate_transfer_perfect_package($origin) {
		$status = INACTIVE;
		$info = $this->module_model->update_transfer_perfect_packages_status( $status, $origin );
		redirect ( base_url () . 'cms/transfers_perfect_packages' );
	}
	function activate_transfer_perfect_package($origin) {
		$status = ACTIVE;
		$info = $this->module_model->update_transfer_perfect_packages_status( $status, $origin );
		redirect ( base_url () . 'cms/transfers_perfect_packages' );
	}
	function activate_top_advers_home($origin) {
		$status = ACTIVE;
		$info = $this->module_model->update_top_advers_home( $status, $origin );
		redirect ( base_url () . 'cms/adv_banner' );
	}
	function deactivate_top_advers_home($origin) {
		$status = INACTIVE;
		$info = $this->module_model->update_top_advers_home( $status, $origin );
		redirect ( base_url () . 'cms/adv_banner' );
	}
	public  function car_top_destinations($offset = 0) {
		// Search Params(Country And City)
		// CMS - Image(On Home Page)
		
		$page_data = array ();
		$post_data = $this->input->post ();
		if (valid_array ( $post_data ) == true) {
			$city_origin = $post_data ['country'];
			// FILE UPLOAD
			$upload_path = realpath ( '../extras' ).'/custom/'.CURRENT_DOMAIN_KEY.'/images/';
			// echo $this->template->domain_image_full_path ();exit;
			if (valid_array ( $_FILES ) == true and $_FILES ['top_destination'] ['error'] == 0 and $_FILES ['top_destination'] ['size'] > 0) {
				// $config ['upload_path'] = $this->template->domain_image_full_path ();
				$config ['upload_path'] = $upload_path;
				$temp_file_name = $_FILES ['top_destination'] ['name'];
				$config ['allowed_types'] = '*';
				$config ['file_name'] = 'top-dest-activity-' . $city_origin;
				$config ['max_size'] = '1000000';
				$config ['max_width'] = '';
				$config ['max_height'] = '';
				$config ['remove_spaces'] = false;
				// UPDATE
				$temp_record = $this->custom_db->single_table_records ( 'Car_Airport', 'image', array (
						'origin' => $city_origin 
				) );
				$top_destination_image = $temp_record ['data'] [0] ['image'];
				// DELETE OLD FILES
				if (empty ( $top_destination_image ) == false) {
					$temp_top_destination_image = $upload_path.$top_destination_image; // GETTING FILE PATH
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
				// echo $config ['upload_path'];
				// exit;
				// debug($image_data);exit;
				
				$this->custom_db->update_record ( 'Car_Airport', array (
						'top_destination' => ACTIVE,
						'image' => $image_data ['file_name'] 
				), array (
						'origin' => $city_origin 
				) );
				
				$this->session->set_flashdata('message', UL0013);
			}
			refresh ();
		}
		$filter = array (
				'top_destination' => ACTIVE 
		);
		// $country_list = $this->custom_db->single_table_records( 'Car_Airport', 'Country_Name_EN,Airport_ID,origin', array (
		// 		'top_destination !=' => '2' 
		// ), 0, 1000000000, array (
		// 		'Country_Name_EN' => 'ASC' 
		// ) );
		$country_list = $this->custom_db->single_table_records( 'Car_Airport', 'Airport_Name_EN,Airport_ID,origin', array (
				'top_destination !=' => '2' 
		), 0, 1000000000, array (
				'Airport_Name_EN' => 'ASC' 
		) );
		 //debug($country_list);exit;
		$data_list = $this->custom_db->single_table_records ( 'Car_Airport', '*', $filter, 0, 100000, array (
				'top_destination' => 'DESC'
				 
		) );
		
		$page_data ['country_list']=$country_list['data'];
		$page_data ['data_list'] = @$data_list ['data'];
		//debug($page_data ['data_list']);exit;

		//echo "heeee";die;
		$this->template->view ( 'cms/top_destination_car', $page_data );
	}


    public  function car_inner_top_destinations($offset = 0) {
		// Search Params(Country And City)
		// CMS - Image(On Home Page)
		
		$page_data = array ();
		$post_data = $this->input->post ();
		if (valid_array ( $post_data ) == true) {
			$city_origin = $post_data ['country'];
			// FILE UPLOAD
			$upload_path = realpath ( '../extras' ).'/custom/'.CURRENT_DOMAIN_KEY.'/images/';
			// echo $this->template->domain_image_full_path ();exit;
			if (valid_array ( $_FILES ) == true and $_FILES ['top_destination'] ['error'] == 0 and $_FILES ['top_destination'] ['size'] > 0) {
				// $config ['upload_path'] = $this->template->domain_image_full_path ();
				$config ['upload_path'] = $upload_path;
				$temp_file_name = $_FILES ['top_destination'] ['name'];
				$config ['allowed_types'] = '*';
				$config ['file_name'] = 'top-dest-activity1-' . $city_origin;
				$config ['max_size'] = '1000000';
				$config ['max_width'] = '';
				$config ['max_height'] = '';
				$config ['remove_spaces'] = false;

				// UPDATE
				$temp_record = $this->custom_db->single_table_records ( 'Car_Airport', 'image2', array (
						'origin' => $city_origin 
				) );

				$top_destination_image = $temp_record ['data'] [0] ['image2'];
				// DELETE OLD FILES
				if (empty ( $top_destination_image ) == false) {
					$temp_top_destination_image = $upload_path.$top_destination_image; // GETTING FILE PATH
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
				// echo $config ['upload_path'];
				// exit;
				// debug($image_data);exit;
				
				$this->custom_db->update_record ( 'Car_Airport', array (
						'car_inner_top_destination' => ACTIVE,
						'image2' => $image_data ['file_name'] 
				), array (
						'origin' => $city_origin 
				) );
				//print_r($this->db->last_query());exit;
				$this->session->set_flashdata('message', UL0013);
			}
			refresh ();
		}
		$filter = array (
				'car_inner_top_destination' => ACTIVE 
		);
		// $country_list = $this->custom_db->single_table_records( 'Car_Airport', 'Country_Name_EN,Airport_ID,origin', array (
		// 		'top_destination !=' => '2' 
		// ), 0, 1000000000, array (
		// 		'Country_Name_EN' => 'ASC' 
		// ) );
		$country_list = $this->custom_db->single_table_records( 'Car_Airport', 'Airport_Name_EN,Airport_ID,origin', array (
				'car_inner_top_destination !=' => '2' 
		), 0, 1000000000, array (
				'Airport_Name_EN' => 'ASC' 
		) );
		 //debug($country_list);exit;
		$data_list = $this->custom_db->single_table_records ( 'Car_Airport', '*', $filter, 0, 100000, array (
				'car_inner_top_destination' => 'DESC'
				 
		) );
		
		$page_data ['country_list']=$country_list['data'];
		$page_data ['data_list'] = @$data_list ['data'];
		//debug($page_data ['data_list']);exit;

		//echo "heeee";die;
		$this->template->view ( 'cms/top__inner_destination_car', $page_data );
	}
	/**
	 * for car perfect package
	 */
	 
	public function car_perfect_packages($offset = 0) {
		// Search Params(Country And City)
		// CMS - Image(On Home Page)
		
		$page_data = array ();
		$post_data = $this->input->post ();
		if (valid_array ( $post_data ) == true) {
			$city_origin = $post_data ['country'];
			// FILE UPLOAD
			$upload_path = realpath ( '../extras' ).'/custom/'.CURRENT_DOMAIN_KEY.'/images/';
			// echo $this->template->domain_image_full_path ();exit;
			if (valid_array ( $_FILES ) == true and $_FILES ['top_destination'] ['error'] == 0 and $_FILES ['top_destination'] ['size'] > 0) {
				// $config ['upload_path'] = $this->template->domain_image_full_path ();
				$config ['upload_path'] = $upload_path;
				$temp_file_name = $_FILES ['top_destination'] ['name'];
				$config ['allowed_types'] = '*';
				$config ['file_name'] = 'top-dest-activity1-' . $city_origin;
				$config ['max_size'] = '1000000';
				$config ['max_width'] = '';
				$config ['max_height'] = '';
				$config ['remove_spaces'] = false;

				// UPDATE
				$temp_record = $this->custom_db->single_table_records ( 'Car_Airport', 'image2', array (
						'origin' => $city_origin 
				) );

				$top_destination_image = $temp_record ['data'] [0] ['image2'];
				// DELETE OLD FILES
				if (empty ( $top_destination_image ) == false) {
					$temp_top_destination_image = $upload_path.$top_destination_image; // GETTING FILE PATH
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
				// echo $config ['upload_path'];
				// exit;
				// debug($image_data);exit;
				
				$this->custom_db->update_record ( 'Car_Airport', array (
						'car_perfect_package' => ACTIVE,
						'image2' => $image_data ['file_name'] 
				), array (
						'origin' => $city_origin 
				) );
				//print_r($this->db->last_query());exit;
				$this->session->set_flashdata('message', UL0013);
			}
			refresh ();
		}
		$filter = array (
				'car_perfect_package' => ACTIVE
		);
		// $country_list = $this->custom_db->single_table_records( 'Car_Airport', 'Country_Name_EN,Airport_ID,origin', array (
		// 		'top_destination !=' => '2' 
		// ), 0, 1000000000, array (
		// 		'Country_Name_EN' => 'ASC' 
		// ) );
		$country_list = $this->custom_db->single_table_records( 'Car_Airport', 'Airport_Name_EN,Airport_ID,origin', array (
				'car_perfect_package !=' => '2' 
		), 0, 1000000000, array (
				'Airport_Name_EN' => 'ASC' 
		) );
		 //debug($country_list);exit;
		$data_list = $this->custom_db->single_table_records ( 'Car_Airport', '*', $filter, 0, 100000, array (
				'car_perfect_package' => 'DESC'
				 
		) );
		
		$page_data ['country_list']=$country_list['data'];
		$page_data ['data_list'] = @$data_list ['data'];
		//debug($page_data ['data_list']);exit;

		//echo "heeee";die;
		$this->template->view ( 'cms/car_perfect_packages', $page_data );
	}

	public function about_us(){


		$page_data = array ();
		$post_data = $this->input->post ();
		//debug($post_data);die;
		if (valid_array ( $post_data ) == true) {
			$city_origin = $post_data ['country'];
			// FILE UPLOAD
			$upload_path = realpath ( '../extras' ).'/custom/'.CURRENT_DOMAIN_KEY.'/images/';
			// echo $this->template->domain_image_full_path ();exit;
			if (valid_array ( $_FILES ) == true and $_FILES ['top_destination'] ['error'] == 0 and $_FILES ['top_destination'] ['size'] > 0) {
				// $config ['upload_path'] = $this->template->domain_image_full_path ();
				$config ['upload_path'] = $upload_path;
				$temp_file_name = $_FILES ['top_destination'] ['name'];
				$config ['allowed_types'] = '*';
				$config ['file_name'] = 'top-dest-activity-' . $city_origin;
				$config ['max_size'] = '10000000';
				$config ['max_width'] = '';
				$config ['max_height'] = '';
				$config ['remove_spaces'] = false;
				// UPDATE
				$temp_record = $this->custom_db->single_table_records ( 'Car_Airport', 'image', array (
						'origin' => $city_origin 
				) );
				$top_destination_image = $temp_record ['data'] [0] ['image'];
				// DELETE OLD FILES
				if (empty ( $top_destination_image ) == false) {
					$temp_top_destination_image = $upload_path.$top_destination_image; // GETTING FILE PATH
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
			//debug($_POST);die;
				$this->custom_db->insert_record ( 'about_us', array (
						'text	'=>$_POST['flight_text'],
						'module'=>$_POST['module'],
						'about_order'=>$_POST['about_order'],
						'image' => $image_data ['file_name'] 
				));
				
				$this->session->set_flashdata('message', UL0013);
			}
			refresh ();
		}
		
		

		/*$filter = array (
				'status' => ACTIVE 
		);*/
		$data_list = $this->custom_db->single_table_records ( 'about_us', '*', '', 0, 100000, array (
				'about_order' => 'ASC'
				 
		) );
		
		$page_data ['data_list']=$data_list['data'];
		//debug($page_data ['country_list']);

		 $this->template->view ( 'cms/aboutus', $page_data );
	}

	function update_about_us(){
		$insert_data = [];
		$post_data = $this->input->post();
		//debug($post_data);exit;
		//debug($_FILES);exit;
		$BID = $post_data['BID'];
		if(valid_array($post_data) == true) {
			$city_origin = $post_data ['country'];
			$upload_path = realpath ( '../extras' ).'/custom/'.CURRENT_DOMAIN_KEY.'/images/';
			//POST DATA formating to update
			$insert_data = array('text'=>$post_data['flight_text'],'module'=>$post_data['module'],'about_order'=>$post_data['about_order']);

			//FILE UPLOAD
			if (valid_array ( $_FILES ) == true and $_FILES ['top_destination'] ['error'] == 0 and $_FILES ['top_destination'] ['size'] > 0) {
				// if( function_exists( "check_mime_image_type" ) ) {
				//     if ( !check_mime_image_type( $_FILES['banner_image']['tmp_name'] ) ) {
				//     	echo "Please select the image files only (gif|jpg|png|jpeg)"; exit;
				//     }
				// }
				$config ['upload_path'] = $upload_path;
				$temp_file_name = $_FILES ['top_destination'] ['name'];
				$config ['allowed_types'] = '*';
				$config ['file_name'] = 'top-dest-activity-' . $city_origin;
				$config ['max_size'] = '10000000';
				$config ['max_width'] = '';
				$config ['max_height'] = '';
				$config ['remove_spaces'] = false;
				//UPDATE
				$temp_record = $this->custom_db->single_table_records ( 'Car_Airport', 'image', array (
						'origin' => $city_origin 
				) );
				//debug($temp_record);exit;
				$top_destination_image = $temp_record ['data'] [0] ['image'];
				//DELETE OLD FILES
				if (empty ( $top_destination_image ) == false) {
					$temp_top_destination_image = $upload_path.$top_destination_image; // GETTING FILE PATH
					if (file_exists ( $temp_top_destination_image )) {
						unlink ( $temp_top_destination_image );
					}
				}
				//echo $temp_banner_image;exit;
				//debug($config);exit;
				//echo $temp_banner_image;exit;
				//UPLOAD IMAGE
				$this->load->library ( 'upload', $config );
				$this->upload->initialize ( $config );
				if (! $this->upload->do_upload ( 'top_destination' )) {
					echo $this->upload->display_errors ();
				} else {
					$image_data = $this->upload->data ();
				}
				//debug($image_data);exit;
				/*UPDATING IMAGE */
				$this->custom_db->update_record('about_us', array('image' => $image_data['file_name']),array('id' => $BID));
			}
			//refresh();
		}
		/*UPDATING OTHER FIELDS*/
		//debug($insert_data);exit;
		$this->custom_db->update_record('about_us',$insert_data,array('id' => $BID));
		redirect ( base_url () . 'cms/about_us' );
	}






	public function adv_banner(){

		
		$page_data = array ();
		$post_data = $this->input->post ();

		if (valid_array ( $post_data ) == true) {
			$city_origin = $post_data ['country'];
			// FILE UPLOAD
			$upload_path = realpath ( '../extras' ).'/custom/'.CURRENT_DOMAIN_KEY.'/images/';
			// echo $this->template->domain_image_full_path ();exit;
			if (valid_array ( $_FILES ) == true and $_FILES ['top_destination'] ['error'] == 0 and $_FILES ['top_destination'] ['size'] > 0) {
				// $config ['upload_path'] = $this->template->domain_image_full_path ();
				$config ['upload_path'] = $upload_path;
				$temp_file_name = $_FILES ['top_destination'] ['name'];
				$config ['allowed_types'] = '*';
				$config ['file_name'] = 'top-dest-activity-' . $city_origin;
				$config ['max_size'] = '10000000';
				$config ['max_width'] = '';
				$config ['max_height'] = '';
				$config ['remove_spaces'] = false;
				// UPDATE
				$temp_record = $this->custom_db->single_table_records ( 'Car_Airport', 'image', array (
						'origin' => $city_origin 
				) );
				$top_destination_image = $temp_record ['data'] [0] ['image'];
				// DELETE OLD FILES
				if (empty ( $top_destination_image ) == false) {
					$temp_top_destination_image = $upload_path.$top_destination_image; // GETTING FILE PATH
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
			//debug($_POST);die;
				$this->custom_db->insert_record ( 'adv_banner', array (
						'adv_text'=>$_POST['textbanner'],
						'module'=>$_POST['module'],
						'image' => $image_data ['file_name'] 
				));
				
				$this->session->set_flashdata('message', UL0013);
			}
			refresh ();
		}
		
		

		/*$filter = array (
				'status' => ACTIVE 
		);*/
		$data_list = $this->custom_db->single_table_records ( 'adv_banner', '*', '', 0, 100000, array (
				'id' => 'DESC'
				 
		) );
		
		$page_data ['data_list']=$data_list['data'];
		//debug($page_data ['country_list']);die;


			 $this->template->view ( 'cms/advbanner', $page_data );

	}

	function delete_banner($origin) {
		// echo $origin;exit;
		$this->custom_db->delete_record ( 'adv_banner', array ('id' => $origin));
		// echo $this->db->last_query();exit;
		redirect ( 'cms/adv_banner' );
	}
	function delete_about_us($origin) {
		// echo $origin;exit;
		$this->custom_db->delete_record ( 'about_us', array ('id' => $origin));
		// echo $this->db->last_query();exit;
		redirect ( 'cms/about_us' );
	}
	function edit_about_us($id){
		$page_data = array ();
		$filter = ['id'=>$id];
		$data_list = $this->custom_db->single_table_records ( 'about_us', '*', $filter, 0, 100000 );
		$page_data ['data_list'] = @$data_list ['data'];
		$this->template->view ( 'cms/about_us_edit', $page_data );

	}
	function delete_subs($origin) {
		// echo $origin;exit;
		$this->custom_db->delete_record ( 'subs_banner', array ('id' => $origin));
		// echo $this->db->last_query();exit;
		redirect ( 'cms/subscribe_banner' );
	}
	public function subscribe_banner()
	{

		$page_data = array ();
		$post_data = $this->input->post ();

		if (valid_array ( $post_data ) == true) {
			$city_origin = $post_data ['country'];
			// FILE UPLOAD
			$upload_path = realpath ( '../extras' ).'/custom/'.CURRENT_DOMAIN_KEY.'/images/';
			// echo $this->template->domain_image_full_path ();exit;
			if (valid_array ( $_FILES ) == true and $_FILES ['top_destination'] ['error'] == 0 and $_FILES ['top_destination'] ['size'] > 0) {
				// $config ['upload_path'] = $this->template->domain_image_full_path ();
				$config ['upload_path'] = $upload_path;
				$temp_file_name = $_FILES ['top_destination'] ['name'];
				$config ['allowed_types'] = '*';
				$config ['file_name'] = 'top-dest-activity-' . $city_origin;
				$config ['max_size'] = '10000000';
				$config ['max_width'] = '';
				$config ['max_height'] = '';
				$config ['remove_spaces'] = false;
				// UPDATE
				$temp_record = $this->custom_db->single_table_records ( 'Car_Airport', 'image', array (
						'origin' => $city_origin 
				) );
				$top_destination_image = $temp_record ['data'] [0] ['image'];
				// DELETE OLD FILES
				if (empty ( $top_destination_image ) == false) {
					$temp_top_destination_image = $upload_path.$top_destination_image; // GETTING FILE PATH
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
			//debug($_POST);die;
				$this->custom_db->insert_record ( 'subs_banner', array (
						'subsbanner'=>$_POST['textbanner'],
						'subs_message'=>$_POST['link'],
						'image' => $image_data ['file_name'] 
				));
				
				$this->session->set_flashdata('message', UL0013);
			}
			refresh ();
		}
		
		

		/*$filter = array (
				'status' => ACTIVE 
		);*/
		$data_list = $this->custom_db->single_table_records ( 'subs_banner', '*', '', 0, 100000, array (
				'id' => 'DESC'
				 
		) );
		
		$page_data ['data_list']=$data_list['data'];
		//debug($page_data ['country_list']);die;


			 $this->template->view ( 'cms/subsbanner', $page_data );
	}
	function deactivate_addbanner($origin) {
		$status = INACTIVE;
		$info = $this->module_model->update_addbannering ( $status, $origin );
		redirect ( base_url () . 'cms/adv_banner' );
	}

	function edit_ad_banner($id){
		$page_data = array ();
		$filter = ['id'=>$id];
		$data_list = $this->custom_db->single_table_records ( 'adv_banner', '*', $filter, 0, 100000 );
		$page_data ['data_list'] = @$data_list ['data'];
		//debug($page_data);
		$this->template->view ( 'cms/ad_banner_edit', $page_data );

	}

	function update_ad_banner_action(){
		$insert_data = [];
		$post_data = $this->input->post();
		//debug($post_data);exit;
		//debug($_FILES);exit;
		$BID = $post_data['BID'];
		if(valid_array($post_data) == true) {
			$city_origin = $post_data ['country'];
			$upload_path = realpath ( '../extras' ).'/custom/'.CURRENT_DOMAIN_KEY.'/images/';
			//POST DATA formating to update
			$insert_data = array('adv_text'=>$post_data['textbanner'],'module'=>$post_data['module']);

			//FILE UPLOAD
			if (valid_array ( $_FILES ) == true and $_FILES ['top_destination'] ['error'] == 0 and $_FILES ['top_destination'] ['size'] > 0) {
				// if( function_exists( "check_mime_image_type" ) ) {
				//     if ( !check_mime_image_type( $_FILES['banner_image']['tmp_name'] ) ) {
				//     	echo "Please select the image files only (gif|jpg|png|jpeg)"; exit;
				//     }
				// }
				$config ['upload_path'] = $upload_path;
				$temp_file_name = $_FILES ['top_destination'] ['name'];
				$config ['allowed_types'] = '*';
				$config ['file_name'] = 'top-dest-activity-' . $city_origin;
				$config ['max_size'] = '10000000';
				$config ['max_width'] = '';
				$config ['max_height'] = '';
				$config ['remove_spaces'] = false;
				//UPDATE
				$temp_record = $this->custom_db->single_table_records ( 'Car_Airport', 'image', array (
						'origin' => $city_origin 
				) );
				//debug($temp_record);exit;
				$top_destination_image = $temp_record ['data'] [0] ['image'];
				//DELETE OLD FILES
				if (empty ( $top_destination_image ) == false) {
					$temp_top_destination_image = $upload_path.$top_destination_image; // GETTING FILE PATH
					if (file_exists ( $temp_top_destination_image )) {
						unlink ( $temp_top_destination_image );
					}
				}
				//echo $temp_banner_image;exit;
				//debug($config);exit;
				//echo $temp_banner_image;exit;
				//UPLOAD IMAGE
				$this->load->library ( 'upload', $config );
				$this->upload->initialize ( $config );
				if (! $this->upload->do_upload ( 'top_destination' )) {
					echo $this->upload->display_errors ();
				} else {
					$image_data = $this->upload->data ();
				}
				//debug($image_data);exit;
				/*UPDATING IMAGE */
				$this->custom_db->update_record('adv_banner', array('image' => $image_data['file_name']),array('id' => $BID));
			}
			//refresh();
		}
		/*UPDATING OTHER FIELDS*/
		//debug($insert_data);exit;
		$this->custom_db->update_record('adv_banner',$insert_data,array('id' => $BID));
		redirect ( base_url () . 'cms/adv_banner' );
	}

	public function general_review() {
		//debug("here");exit;
    $page_data['reviews'] = $this->module_model->general_reviews();
   
  //  debug($page_data); exit;
    $this->template->view('cms/general_reviews',$page_data);          
  }
  public function ajax_tour_publish() {
//   	ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
  $data = $this->input->post();
   //debug($data); exit('');
  $tour_id        = sql_injection($data['id']);
  $publish_status = sql_injection($data['publish_status']);
  // debug($publish_status);
  // debug($tour_id);exit;
 $message = array();
 //$query  = "update general_user_review set status='$publish_status' where origin='$tour_id'";

 $return=$this->custom_db->update_record('general_user_review', array('status' => $publish_status),array('origin' => $tour_id));
 if($return)
 {
  $message= "Thanks! Updated Successfully.";
}else{
  $message= "Sorry|| some techinal .";
}

echo json_encode($message); exit(); 
}

public function delete_general_review($id) {
    //$query = "delete from general_user_review where origin='$id'";
    $return = $this->db->delete('general_user_review', array('origin' => $id)); 
    if($return){redirect('cms/general_review');} 
    else { echo $return;} 
  }


}