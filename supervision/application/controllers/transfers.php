<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 *
 * @package Provab - Provab Application
 * @subpackage Travel Portal
 * @author Arjun J<arjunjgowda260389@gmail.com> on 01-06-2015
 * @version V2
 */
class Transfers extends CI_Controller {
	public function __construct() {
		parent::__construct ();
		$this->load->model('transfers_model');
		$this->load->model('car_model');
	}
	public function view_packages_types() {
		$data ['package_view_data'] = $this->transfers_model->package_view_data_types ()->result ();

		// debug($data['package_view_data']); die();
		$this->template->view ( 'transfers/view_package_types', $data );
	}
	public function delete_package_type($id) {
		$this->transfers_model->delete_package_type ( $id );
		redirect ( 'transfers/view_packages_types' );
	}
	
	public function delete_traveller_img($pack_id,$img_id) {
		$this->transfers_model->delete_traveller_img ( $pack_id,$img_id );
		redirect ( 'transfers/images/'.$img_id.'/w' );
	}
	public function delete_package($id) {
		$this->transfers_model->delete_package ( $id );
		redirect ( 'transfers/view_with_price' );
	}
	public function add_package_type($id = '') {
		if ($id != '') {
			$data ['pack_data'] = $this->transfers_model->get_pack_id ( $id );
			$this->template->view ( 'transfers/add_package_type', $data );
		} else {
			$this->template->view ( 'transfers/add_package_type', $data );
		}
	}



	public function save_packages_type() {
		$pack_id = $this->input->post ( 'package_types_id' );
		$package_name = $this->input->post ( 'name' );
		$domain_list_fk = get_domain_auth_id ();
		// echo "<pre>";print_r($domain_list_fk);exit;
		$module_type="transfers";
		$add_package_data = array (
				'package_types_name' => $package_name,
				'domain_list_fk' => $domain_list_fk ,
				'module_type' => $module_type,
				'status' => 1 
		);

		$this->db->where("package_types_name",$package_name );
		$this->db->where("module_type",$module_type );
		$qur = $this->db->get ("package_types");
		$count=$qur->num_rows();
 
		if ($pack_id > 0) 
		{
			
			if($count<1)
			{
		  		 $this->transfers_model->update_package_type ( $add_package_data, $pack_id );
		  		 // $this->session->set_flashdata(array('message' => 'UL0014', 'type' => SUCCESS_MESSAGE));
		  		 $this->session->set_flashdata('message', UL0013);
			}
			else
			{
				// $this->session->set_flashdata(array('message' => 'UL0102', 'type' => ERROR_MESSAGE));
				$this->session->set_flashdata('message', UL0102);
			}
		} 
		else 
		{ 
			if($count<1)
			{
				$this->db->insert ("package_types",$add_package_data );
				$this->session->set_flashdata('message', UL0014);
				// $this->session->set_flashdata(array('message' => 'UL0014', 'type' => SUCCESS_MESSAGE));
			} 
			else
			{
				// $this->session->set_flashdata(array('message' => 'UL0102', 'type' => ERROR_MESSAGE));
				$this->session->set_flashdata('message', UL0102);
			}			
		}
		
		redirect ( 'transfers/view_packages_types' );
	}

	public function save_packages_type_old() {
		$pack_id = $this->input->post ( 'package_types_id' );
		$package_name = $this->input->post ( 'name' );
		$domain_list_fk = get_domain_auth_id ();
		$module_type="transfers";
		// echo "<pre>";print_r($domain_list_fk);exit;
		$add_package_data = array (
				'package_types_name' => $package_name,
				'domain_list_fk' => $domain_list_fk, 
				'module_type' => $module_type 
		);
		if ($pack_id > 0) {
			$this->transfers_model->update_package_type ( $add_package_data, $pack_id );
		} else {


			// debug($add_package_data);exit(); 
			$this->db->insert ( "package_types", $add_package_data );
		}
		redirect ( 'transfers/view_packages_types' );
	}
	public function add_with_price() 
	{
		// error_reporting(E_ALL);
		$data ['package_type_data'] = $this->transfers_model->package_view_data_types ()->result ();

		// debug($data ['package_type_data']);exit();
		$data ["country"] = $this->transfers_model->get_countries ();
		$this->template->view ( 'transfers/add_package', $data );
	}
	public function get_countries() {
		$this->db->limit ( 1000 );
		$this->db->order_by ( "name", "asc" );
		$qur = $this->db->get ( "country" );
		return $qur->result ();
	}
	public function add_package_new() {
		$photo = $_FILES;
		$cpt1 = count ( $_FILES ['photo'] ['name'] );
		if ($_FILES ['photo'] ['name'] != '') {
			$_FILES ['photo'] ['name'] = $photo ['photo'] ['name'];
			$_FILES ['photo'] ['type'] = $photo ['photo'] ['type'];
			$_FILES ['photo'] ['tmp_name'] = $photo ['photo'] ['tmp_name'];
			$_FILES ['photo'] ['error'] = $photo ['photo'] ['error'];
			$_FILES ['photo'] ['size'] = $photo ['photo'] ['size'];
			
			$string = $_FILES ['photo'] ['name'];
			$exe = explode(".",$string);
			$photo = date('dmYhis').'.'.$exe[1];
			// move_uploaded_file ( $_FILES ['photo'] ['tmp_name'], $this->template->domain_uploads_packages ( $_FILES ['photo'] ['name'] ) );

			$path=str_replace('/troogles', '', $this->template->domain_uploads_packages ($photo));


			move_uploaded_file ( $_FILES ['photo'] ['tmp_name'], $path );
		}

		$module_type="transfers";
		$end_date = $this->input->post ( 'tour_expire_date' );
		$start_date = $this->input->post ( 'tour_start_date' );
		$disn = $this->input->post ( 'disn' );
		$name = $this->input->post ( 'name' );
		$country = $this->input->post ( 'country' );
		$city = $this->input->post ( 'cityname' );
		$duration = $this->input->post ( 'duration' );
		$description = $this->input->post ( 'Description' );
		$location = $this->input->post ( 'location' );
		$state = $this->input->post ( 'state' );
		$deal = $this->input->post ( 'deal' );
		$homepage = $this->input->post ( 'homepage' );
		$rating = $this->input->post ( 'rating' );
		$tourss = $this->input->post ( 'tourss' );
		$with_price = $this->input->post ( 'with_price' );
		$p_price = $this->input->post ( 'p_price' );
		// itinerary
		$itinerarydesc = $this->input->post ( 'desc' );
		$days = $this->input->post ( 'days' );
		$place = $this->input->post ( 'place' );
		$price = $this->input->post ( 'price' );
		// price includes
		$includes = $this->input->post ( 'includes' );
		$excludes = $this->input->post ( 'excludes' );
		// cancellation
		$advance = $this->input->post ( 'advance' );
		$penality = $this->input->post ( 'penality' );
		// question and answer
		$no_questions = $this->input->post ( 'no_of_questions' );
		$quest = $this->input->post ( 'quest' );
		$answer = $this->input->post ( 'answer' );
		$sup_id = $this->session->userdata ( 'sup_id' );
		$sup_type = $this->session->userdata ( 'sup_type' );
		// price
		$sd = $this->input->post ( 'sd' );
		$ed = $this->input->post ( 'ed' );
		$adult = $this->input->post ( 'adult' );
		$child = $this->input->post ( 'child' );
		$pricee = $this->input->post ( 'pricee' );
		// deals
		$value = $this->input->post ( 'value' );
		$discount = $this->input->post ( 'discount' );
		$save = $this->input->post ( 'save' );
		$seats = $this->input->post ( 'seats' );
		$time = $this->input->post ( 'time' );
		$packageaw = $this->input->post ( 'a_wo_p' );
		$domain_list_fk = get_domain_auth_id ();
		
		$newpackage = array (
				'package_type' => $disn,
				'package_name' => ucwords($name),
				'supplier_id' => $sup_id,
				'price_includes' => $pricee,
				'package_country' => $country,
				'start_date' => $start_date,
				'end_date' => $end_date,
				'package_city' => $city,
				'package_location' => $location,
				'package_description' => $description,
				'deals' => $deal,
				'duration' => $duration,
				'image' => $photo,
				'no_que' => $no_questions,
				'home_page' => $homepage,
				'rating' => $rating,
				'tour_types' => $tourss,
				'price' => $p_price,
				'domain_list_fk' => $domain_list_fk,
				'module_type' => $module_type

		);
		$package = $this->transfers_model->add_new_package ( $newpackage );
		// debug($newpackage);exit;
		// print_r($country);exit;
		// exit();
		if ($package != 0) {
			$packcode = array (
					'package_code' => 'SKY' . $package 
			);
			$this->transfers_model->update_code_package ( $packcode, $package );
		}
		$img = $_FILES;
		$cp = count ( $_FILES ['image'] ['name'] );
		if ($_FILES ['image'] ['name'] [0] != '') {
			for($i = 0; $i < $duration; $i ++) {
				$_FILES ['image'] ['name'] = $img ['image'] ['name'] [$i];
				$_FILES ['image'] ['type'] = $img ['image'] ['type'] [$i];
				$_FILES ['image'] ['tmp_name'] = $img ['image'] ['tmp_name'] [$i];
				$_FILES ['image'] ['error'] = $img ['image'] ['error'] [$i];
				$_FILES ['image'] ['size'] = $img ['image'] ['size'] [$i];
				$itenary_imge = 'itenary-' . time () . $_FILES ['image'] ['name'];


				$path=str_replace('/troogles', '', $this->template->domain_uploads_packages ($itenary_imge));


				move_uploaded_file ( $_FILES ['image'] ['tmp_name'], $path );
				$image = $itenary_imge;
				$itinerary = array (
						'itinerary_description' => $itinerarydesc [$i],
						'day' => $days [$i],
						'place' => $place [$i],
						'itinerary_image' => $image,
						'package_id' => $package 
				);
				$itineraryid = $this->transfers_model->itinerary ( $itinerary );
			}
		}
		for($i = 0; $i < $no_questions; $i ++) {
			if (! empty ( $quest [$i] )) {
				$qus_ans = array (
						'question' => $quest [$i],
						'answer' => $answer [$i],
						'package_id' => $package,
						'user_id' => $sup_id,
						'usertype' => $sup_type 
				);
				$queans = $this->transfers_model->que_ans ( $qus_ans );
			}
		}
		$pricingpolicy = array (
				'package_id' => $package,
				'price_includes' => $includes,
				'price_excludes' => $excludes 
		);
		$policy = $this->transfers_model->pricing_policy ( $pricingpolicy );
		$cancellation = array (
				'package_id' => $package,
				'cancellation_advance' => $advance,
				'cancellation_penality' => $penality 
		);
		$cancel = $this->transfers_model->cancellation_penality ( $cancellation );
		if (! empty ( $value )) {
			$deals = array (
					'value' => $value,
					'discount' => $discount,
					'you_save' => $save,
					'seats' => $seats,
					'time' => $time,
					'package_id' => $package 
			);
			$deall = $this->transfers_model->deals ( $deals );
		}
		for($i = 0; $i < $duration; $i ++) {
			if (! empty ( $sd [$i] )) {
				$incl = array (
						'from_date' => $sd [$i],
						'to_date' => $ed [$i],
						'duration' => $duration,
						'adult_price' => $adult [$i],
						'child_price' => $child [$i],
						'package_id' => $package 
				);
				$in = $this->transfers_model->incl ( $incl );
			}
		}
		$tra = $_FILES;
		$c = count ( $_FILES ['traveller'] ['name'] );
		if ($_FILES ['traveller'] ['name'] [0] != '') {
			for($i = 0; $i < $c; $i ++) {
				$_FILES ['traveller'] ['name'] = $tra ['traveller'] ['name'] [$i];
				$_FILES ['traveller'] ['type'] = $tra ['traveller'] ['type'] [$i];
				$_FILES ['traveller'] ['tmp_name'] = $tra ['traveller'] ['tmp_name'] [$i];
				$_FILES ['traveller'] ['error'] = $tra ['traveller'] ['error'] [$i];
				$_FILES ['traveller'] ['size'] = $tra ['traveller'] ['size'] [$i];
				$name_of_traveller_img = 'traveller-' . time () . $_FILES ['traveller'] ['name'];

				$path=str_replace('/troogles', '', $this->template->domain_uploads_packages ($name_of_traveller_img));


				move_uploaded_file ( $_FILES ['traveller'] ['tmp_name'], $path );
				$traveller_img = $name_of_traveller_img;
				$sup_id = $this->entity_user_id;
				$traveller = array (
						'traveller_image' => $traveller_img,
						'user_id' => $sup_id,
						'package_id' => $package 
				);
				$travel = $this->transfers_model->travel_images ( $traveller );
			}
		}
		redirect ( 'transfers/view_with_price' );
	}
	public function view_without_price() {
		$data ['newpackage'] = $this->transfers_model->without_price ();
		$this->template->view ( 'transfers/view_without_price', $data );
	}
	public function view_with_price() {
		$data ['newpackage'] = $this->transfers_model->with_price ();

		// debug($data ['newpackage']);exit(	);
		$this->template->view ( 'transfers/view_with_price', $data );
	}
	public function update_deal_status($package_id, $deals) {
		$this->transfers_model->update_deal_status ( $package_id, $deals );
		if ($deals == '1') {
			$data ['package_id'] = $package_id;
			$this->template->view ( 'transfers/view_city_request', $data );
		} else if ($deals == '0') {
			$data ['package_id'] = $package_id;
			$this->transfers_model->delete_deal_id ( $package_id );
			redirect ( 'transfers/view_city_request', 'refresh' );
		} else {
			redirect ( 'transfers/view_deals', 'refresh' );
		}
	}
	public function update_status($package_id, $status) {
		$this->transfers_model->update_status ( $package_id, $status );
		$this->session->set_flashdata('message', UL0013);
		redirect ( 'transfers/view_with_price/', 'refresh' );
	}
	public function update_status_type($package_id, $status) {
		$this->transfers_model->update_status_type ( $package_id, $status );
		$this->session->set_flashdata('message', UL0013);
		redirect ( 'transfers/view_packages_types/', 'refresh' );
	}
	public function update_status_policy($package_id, $status) {
		$this->transfers_model->update_status_policy ( $package_id, $status );
		$this->session->set_flashdata('message', UL0013);
		redirect ( 'transfers/cancellation_policy/', 'refresh' );
	}
	public function read_enquiry($package_id) {
		$status=ACTIVE;
		$this->transfers_model->update_enquiry_status ( $package_id, $status );
		redirect ( 'transfers/enquiries/', 'refresh' );
	}
	
	public function view_deals() {
		$data ['newpackage'] = $this->transfers_model->get_supplier ();
		$this->template->view ( 'transfers/suppliers', $data );
	}
	public function update_homepage_status2($package_id, $home_page) {
		$this->transfers_model->update_homepage_status ( $package_id, $home_page );
		redirect ( 'transfers/view_without_price/' . $package_id, 'refresh' );
	}
	public function edit_without_price($package_id) {
		$data ['packdata'] = $this->transfers_model->get_package_id ( $package_id );
		$data ['price'] = $this->transfers_model->get_price ( $package_id );
		$data ['countries'] = $this->transfers_model->get_country_city_list ();
		// print_r($data);exit;
		$this->template->view ( 'transfers/edit_without_price', $data );
	}
	public function edit_itinerary($package_id) {
		$data ['pack_data'] = $this->transfers_model->get_itinerary_id ( $package_id );
		// print_r($data);die;
		$data ['package_id'] = $package_id;
		$this->template->view ( 'transfers/edit_itinerary', $data );
	}
	public function quesans_view($package_id) {
		$data ['que_ans'] = $this->transfers_model->get_que_ans ( $package_id );
		$data ['package_id'] = $package_id;
		$this->template->view ( 'transfers/quesans_view', $data );
	}
	public function enquiries() {
		$data ['enquiries'] = $this->transfers_model->enquiries ();
		//debug($data);exit;
		$this->template->view ( 'transfers/enquiries', $data );
	}
	public function delete_enquiry($id, $package_id) {
		$this->transfers_model->delete_enquiry ( $id );
		redirect ( 'transfers/enquiries/' );
	}
	public function itinerary() {
		// print_r($itinerarydesc);exit;
		$itinerary = array (
				'itinerary_description' => $itinerarydesc,
				'day' => $days 
		);
		$it = $this->transfers_model->itinerary ( $itinerary );
		if ($it) {
			redirect ( 'transfers/view_deals', 'refresh' );
		}
	}
	public function itinerary_loop($duration) {
		$data ['duration'] = $duration;
		echo $this->template->isolated_view ( 'transfers/duration_itinerary', $data );
	}
	public function itinerary_loop1($questions) {
		$data ['questions'] = $questions;
		$this->template->isolated_view ( 'transfers/question', $data );
	}
	public function itinerary_loop2($pricee) {
		$data ['pricee'] = $pricee;
		$this->template->isolated_view ( 'transfers/withprice', $data );
	}
	public function get_crs_city($city_id) {
		$city = $this->transfers_model->get_crs_city_list ( $city_id );
		$sHtml = "";
		$sHtml .= '<option value="">Select City</option>';
		if (! empty ( $city )) {
			foreach ( $city as $key => $value ) {

				$sHtml .= '<option value="' . $value->city . '">' . $value->city . '</option>';
			}
		}
		
		echo json_encode ( array (
				'result' => $sHtml 
		) );
		exit ();
	}
	public function get_tour($tour_id) {
		// echo "hi";
		// echo $tour_id;die;
		$tours = $this->transfers_model->get_tour_list ( $tour_id );
		$sHtml = "";
		$sHtml .= '<option value="">Select Tours</option>';
		if (! empty ( $tours )) {
			// debug($tours);exit;
			foreach ( $tours as $key => $value ) {
				$sHtml .= '<option value="' . $value->package_types_id . '">' . $value->package_types_name . '</option>';
			}
		}
		echo json_encode ( array (
				'result' => $sHtml 
		) );
		exit ();
	}
	public function edit_with_price($package_id) {
		$data ['packdata'] = $this->transfers_model->get_package_id ( $package_id );
		$data ['price'] = $this->transfers_model->get_price ( $package_id );
		$data ['countries'] = $this->transfers_model->get_country_city_list ();
		// print_r($data);exit;
		$this->template->view ( 'transfers/edit_with_price', $data );
	}
	public function update_package($package_id) {
		$name = $this->input->post ( 'name' );
		$location = $this->input->post ( 'location' );
		$description = $this->input->post ( 'Description' );
		$photo = $_FILES;
		if ($_FILES ['photo'] ['name'] != '') {

		$config['upload_path']          = realpath ( '../extras' ).'/custom/'.CURRENT_DOMAIN_KEY.'/uploads/packages/';
            $config['allowed_types']        = 'jpg|png|jpeg';
            $config['encrypt_name']         = true;
            $this->load->library('upload', $config);
			if ( ! $this->upload->do_upload('photo'))
			{
				echo $this->upload->display_errors();die;
			}
			$photo = $this->upload->data()['file_name'];
		} else {
			$photo = $this->input->post ( 'hidephoto' );
		}
		// debug($this->input->post());die;
		$end_date = $this->input->post ( 'tour_expire_date' );
		$start_date = $this->input->post ( 'tour_start_date' );
		$includes = $this->input->post ( 'includes' );
		$excludes = $this->input->post ( 'excludes' );
		$price = $this->input->post ( 'price' );
		$advance = $this->input->post ( 'advance' );
		$penality = $this->input->post ( 'penality' );
		$value = $this->input->post ( 'value' );
		$discount = $this->input->post ( 'discount' );
		$save = $this->input->post ( 'save' );
		$seats = $this->input->post ( 'seats' );
		$time = $this->input->post ( 'time' );
		$sd = $this->input->post ( 'sd' );
		$ed = $this->input->post ( 'ed' );
		$adult = $this->input->post ( 'adult' );
		$child = $this->input->post ( 'child' );
		$packtypew = $this->input->post ( 'w_wo_d' );
		$rating = $this->input->post ( 'rating' );
		$p_price = $this->input->post ( 'p_price' );
		$data = array (
				'package_name' => ucwords($name),
				'start_date' => $start_date,
				'end_date' => $end_date,
				'package_location' => $location,
				'package_description' => $description,
				'image' => $photo,
				'rating' => $rating,
				'price' => $p_price 
		);
		// print_r($data);exit;
		$policy = array (
				'price_includes' => $includes,
				'price_excludes' => $excludes 
		);
		$can = array (
				'cancellation_advance' => $advance,
				'cancellation_penality' => $penality 
		);
		$dea = array (
				'value' => $value,
				'discount' => $discount,
				'you_save' => $save,
				'seats' => $seats,
				'time' => $time 
		);
		if (! empty ( $sd )) {
			for($i = 0; $i < count ( $sd ); $i ++) {
				$pri = array (
						'from_date' => $sd [$i],
						'to_date' => $ed [$i],
						'adult_price' => $adult [$i],
						'child_price' => $child [$i] 
				);
				$this->transfers_model->update_edit_pri ( $package_id, $pri );
			}
		}
		// print_r($data);die;
		$this->transfers_model->update_edit_package ( $package_id, $data );
		$this->transfers_model->update_edit_policy ( $package_id, $policy );
		$this->transfers_model->update_edit_can ( $package_id, $can );
		$this->transfers_model->update_edit_dea ( $package_id, $dea );
		
		if ($packtypew == "w") {
			redirect ( 'transfers/view_with_price' );
		} elseif ($packtypew == 'wo') {
			redirect ( 'transfers/view_without_price' );
		} else {
			redirect ( 'transfers/view_deals' );
		}
		
		redirect ( 'transfers/view_with_price' );
	}
	public function images($package_id) {

		// error_reporting(E_ALL);

		if ($_FILES) {
			$tra = $_FILES;
			$_FILES ['traveller'] ['name'] = $tra ['traveller'] ['name'];
			$_FILES ['traveller'] ['type'] = $tra ['traveller'] ['type'];
			$_FILES ['traveller'] ['tmp_name'] = $tra ['traveller'] ['tmp_name'];
			$_FILES ['traveller'] ['error'] = $tra ['traveller'] ['error'];
			$_FILES ['traveller'] ['size'] = $tra ['traveller'] ['size'];
			$name_of_traveller_img = 'traveller-' . time () . $_FILES ['traveller'] ['name'];

				$path=str_replace('/troogles', '', $this->template->domain_uploads_packages ($name_of_traveller_img));


			move_uploaded_file ( $_FILES ['traveller'] ['tmp_name'], $path );
			$traveller_img = $name_of_traveller_img;
			$sup_id = $this->entity_user_id;
			$pckge_id = $this->input->post ( 'pckge_id' );
			$traveller = array (
					'traveller_image' => $traveller_img,
					'user_id' => $sup_id,
					'package_id' => $pckge_id 
			);
			$travel = $this->transfers_model->travel_images ( $traveller );

			// debug($package_id);exit();
		//	redirect ( base_url () . 'index.php/transfers/images/'. $package_id .'/w' );
		}
		$data ['traveller'] = $this->transfers_model->get_image ( $package_id );
		$data ['package_id'] = $package_id;
		$this->template->view ( 'transfers/images', $data );
	}
	public function update_itinerary() {
		$package_id = $this->input->post ( 'package_id' );
		$itinerary_id = $this->input->post ( 'itinerary_id' );
		$itinerarydesc = $this->input->post ( 'desc' );
		$days = $this->input->post ( 'days' );
		$place = $this->input->post ( 'place' );
		$hiddenimage = $this->input->post ( 'hiddenimage' );
		// print_r($hiddenimage);
		// exit;
		$img = $_FILES;
		$cp = count ( $itinerary_id );
		// print_r($cp);exit;
		for($i = 0; $i < $cp; $i ++) {
			
			if (! empty ( $_FILES ['imagelable' . $i] ['name'] )) {
				$_FILES ['imagelable'] ['name'] = $img ['imagelable' . $i] ['name'];
				$_FILES ['imagelable'] ['type'] = $img ['imagelable' . $i] ['type'];
				$_FILES ['imagelable'] ['tmp_name'] = $img ['imagelable' . $i] ['tmp_name'];
				$_FILES ['imagelable'] ['error'] = $img ['imagelable' . $i] ['error'];
				$_FILES ['imagelable'] ['size'] = $img ['imagelable' . $i] ['size'];

				$path=str_replace('/troogles', '', $this->template->domain_uploads_packages ($name_of_traveller_img));

				move_uploaded_file ( $_FILES ['imagelable'] ['tmp_name'], $path );
				$image = $this->template->domain_uploads_packages ( $_FILES ['imagelable' . $i] ['name'] );
			} else {
				$image = $hiddenimage [$i];
			}
			
			$data = array (
					'itinerary_description' => $itinerarydesc [$i],
					'place' => $place [$i],
					'day' => $days [$i],
					'itinerary_image' => $image 
			);
			
			$this->transfers_model->update_itinerary ( $package_id, $itinerary_id [$i], $data );
		}
		redirect ( 'transfers/edit_itinerary/' . $package_id, 'refresh' );
	}
	public function view_enquiries($package_id) {
		$data ['enquiries'] = $this->transfers_model->view_enqur ( $package_id );
		$data ['package_id'] = $package_id;
		$this->template->view ( 'transfers/enquiry_package', $data );
	}
	/*************car supplier details******/
	public function add_car_supplier(){

		$data ["country"] = $this->transfers_model->get_countries ();
		
		$data["branch_user_list"] = $this->car_model->get_branch_user_list(array());
		$this->template->view ( 'car/add_supplier', $data );
	}
	public function add_supplier($id=0){
		$post_params = $this->input->post();		
// debug($post_params);die;
		if(valid_array($post_params)){
			// debug($post_params);die;
			$insert_arr = array();
			$insert_arr['agency_name'] = $post_params['supplier_name'];
			$insert_arr['branch_id'] = $post_params['branch_id']; 
			$insert_arr['first_name'] = $post_params['supplier_name']; 
			//$insert_arr['supplier_id'] = $post_params['branch_id']; 
			//$insert_arr['agent_name'] = $post_params['reg_no'];
			$insert_arr['email'] = $post_params['email'];
			$insert_arr['user_name']=$post_params['email'];
			$insert_arr['phone'] = $post_params['phone_no'];
			//$insert_arr['pan_number'] = $post_params['pan_no'];
			//$insert_arr['aadhar_no'] = $post_params['adhar_no'];
			//$insert_arr['country'] = $post_params['country'];
			$insert_arr['country_name'] = $post_params['country'];
			$insert_arr['city'] = $post_params['cityname'];
			$insert_arr['location'] = $post_params['location'];
			$insert_arr['address'] = $post_params['address'];
			$insert_arr['password'] = md5($post_params['password']);
			$photo = $_FILES;
			$cpt1 = count ( $_FILES ['photo'] ['name'] );
			if ($_FILES ['photo'] ['name'] != '') {
				$_FILES ['photo'] ['name'] = $photo ['photo'] ['name'];
				$_FILES ['photo'] ['type'] = $photo ['photo'] ['type'];
				$_FILES ['photo'] ['tmp_name'] = $photo ['photo'] ['tmp_name'];
				$_FILES ['photo'] ['error'] = $photo ['photo'] ['error'];
				$_FILES ['photo'] ['size'] = $photo ['photo'] ['size'];
				

				move_uploaded_file ( $_FILES ['photo'] ['tmp_name'], $this->template->domain_image_full_path ( $_FILES ['photo'] ['name'] ) );
				
				// if(move_uploaded_file ( $_FILES ['photo'] ['tmp_name'], $this->template->domain_image_upload_path().$_FILES ['photo'] ['name'] ) ){
				// 	$photo = $_FILES ['photo'] ['name'];
				// 	$insert_arr['image'] = $photo;
					
				// }
				// else{
				// 	$photo = $_FILES ['photo'] ['name'];
				// 	$insert_arr['image'] = 'no_image';
				// }
				
				//domain_uploads_car
				 $photo = $_FILES ['photo'] ['name'];
				 $insert_arr['image'] = $photo;
			}
			



			// debug($id);exit();
			if($id>0){
				// echo "in if";
				// debug($insert_arr);die;
                unset($insert_arr['email']);
                unset($insert_arr['password']);
				$this->custom_db->update_record('user',$insert_arr,array('user_id'=>$id));
			}else{
				// echo "out else";



				// debug($GLOBALS['CI']->entity_user_type);
				// debug(CAR_BRANCH_USER);exit;
				// if($GLOBALS['CI']->entity_user_type == CAR_BRANCH_USER)
				$insert_arr['branch_id'] = $this->entity_user_id;
				$insert_arr['created_by_id'] = $this->entity_user_id;
                $insert_arr['created_datetime'] = date ( 'Y-m-d H:i:s' );
				$insert_arr['user_type']  =CAR_SUPPLIER;
				$insert_arr['uuid'] = time().rand(1, 1000);
				$insert_arr['status'] = ACTIVE;
				$insert_arr['domain_list_fk'] = get_domain_auth_id();
				// debug($insert_arr);die;
				$r=$this->custom_db->insert_record('user',$insert_arr);
				// debug($r);die;
			}
			

			$data ["country"] = $this->transfers_model->get_countries ();
		if($GLOBALS['CI']->entity_user_type == ADMIN)
			// echo "sad";
			redirect(base_url().'index.php/transfers/all_car_supplier_list');
		else
			// echo "d";
			redirect(base_url().'index.php/transfers/car_supplier_list');
		}else{
			 // echo "out";die;
			if($id>0){
				redirect(base_url().'index.php/transfers/all_car_supplier_list/'.$id);
			}
		}
	}

    public function all_car_supplier_list($id=0){

        //$supplier_list = $this->custom_db->single_table_records('car_supplier_list','*');

        $supplier_list = $this->car_model->get_supplier_list(0,'');

        // debug($supplier_list);exit();
        if($id>0){
            $edit_data = $this->car_model->get_supplier_list($id,'');
            $data = $edit_data[0];
        }
// debug($supplier_list);die;
        foreach($supplier_list as $key=>$list){
            $supplier_list[$key]['branchuser_name']=$this->car_model->get_branch_user_name_on_id($list['branch_id'])['agency_name'];
            // debug($supplier_list[$key]['branchuser_name']);
        }
        // debug($supplier_list);die;
// echo $this->db->last_query();die;
        // debug($supplier_list);die;
        $total_row_count = 0;
        if($supplier_list){

            $data['table_data'] = $supplier_list;
            $total_row_count = count($data['table_data']);
        }else{
            $data['table_data'] = array();
        }
        // debug($data);die;
        $this->load->library('pagination');
        if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
        $config['base_url'] = base_url().'index.php/transfers/car_supplier_list/';
        $config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
        $config['total_rows'] = $total_row_count;
        $config['per_page'] = RECORDS_RANGE_1;
        // $this->pagination->initialize($config);

        $data ["country"] = $this->car_model->get_active_country_list ();
// echo $this->db->last_query();die;
        // debug($data);die;
        $data ["country"] = $data ["country"]['data'];
        $data['ID'] = $id;
// debug($data);die;
        $data["branch_user_list"] = $this->car_model->get_branch_user_list(array());
// echo $this->db->last_query();die;
// debug($data["branch_user_list"]);die;

        // echo $this->db->last_query();die;
        $this->template->view ( 'car/supplier_list', $data );

    }

	public function car_supplier_list($id=0){

		//$supplier_list = $this->custom_db->single_table_records('car_supplier_list','*');
// debug($id);debug($this->entity_user_id);die;
		if($GLOBALS['CI']->entity_user_type == ADMIN){
			// echo "in";die;
			$supplier_list = $this->car_model->get_supplier_list(0,'');	
			// echo $this->db->last_query();die;
			$edit_data = $this->car_model->get_supplier_list($id,'');
			// echo $this->db->last_query();die;
			// debug($edit_data);die;
			$data = $edit_data[0];

	
		}
		/*else if($GLOBALS['CI']->entity_user_type == CAR_BRANCH_USER)
{
	// echo "out";die;
		$supplier_list = $this->car_model->get_supplier_list(0,$this->entity_branch_id);	
		// debug($supplier_list);die;
		if($id>0){
			$edit_data = $this->car_model->get_supplier_list($id,$this->entity_user_id);
			$data = $edit_data[0];
		}
	}*/
	else
	{
		$supplier_list = $this->car_model->get_supplier_list(0,$this->entity_user_id);	
		// debug($supplier_list);die;
		if($id>0){
			$edit_data = $this->car_model->get_supplier_list($id,$this->entity_user_id);
			$data = $edit_data[0];
		}
	}
	// debug($supplier_list);die;
	
        // debug($supplier_list);die;
// echo $this->db->last_query();die;
        // debug($supplier_list);die;
        
// debug($supplier_list);die;
	
		// echo $this->db->last_query();die;
		$total_row_count = 0;
		if($supplier_list){

		 	$data['table_data'] = $supplier_list;
		 	$total_row_count = count($data['table_data']);
		}else{
			$data['table_data'] = array();
		}	
		// debug($data);die;
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/transfers/car_supplier_list/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$config['total_rows'] = $total_row_count;
		$config['per_page'] = RECORDS_RANGE_1;
		// $this->pagination->initialize($config);

		$data ["country"] = $this->car_model->get_active_country_list ();

		$data ["country"] = $data ["country"]['data'];
		$data['ID'] = $id;

		$data["branch_user_list"] = $this->car_model->get_branch_user_list(array());
		// debug($data);die;
		// echo $this->db->last_query();die;
		$this->template->view ( 'car/supplier_list', $data );

	}
		/**
	 * Activate User Account
	 */
	function activate_supplier($user_id)
	{
		$cond['user_id'] = intval($user_id);		
		$data['status'] = ACTIVE;
		$driver_con['supplier_id'] = $user_id;
		if($this->custom_db->update_record('user',$data,$cond) && $this->custom_db->update_record('user',$data,$driver_con) && $this->custom_db->update_record('car_details',$data,$driver_con)){			
			echo "1";
		}else{
			echo "0";
		}

		exit;
		/*redirect(base_url().'user/user_management?filter=user_type&q='.$info['data']['user_type']);*/
	}

	/**
	 * Deactiavte User Account
	 */
	function deactivate_supplier($user_id)
	{
		$cond['user_id'] = intval($user_id);		
		$data['status'] = INACTIVE;
		$driver_con['supplier_id'] = $user_id;
		if($this->custom_db->update_record('user',$data,$cond) && $this->custom_db->update_record('user',$data,$driver_con) && $this->custom_db->update_record('car_details',$data,$driver_con)){

			echo "1";
		}else{
			echo "0";
		}
		exit;
		/*redirect(base_url().'user/user_management?filter=user_type&q='.$info['data']['user_type']);*/
	}
	function get_active_city($origin,$select_city=''){
		// echo "fsdfds";exit;
		$city = $this->custom_db->single_table_records('car_active_country_city_master','*',array('country_id'=>$origin));
		$sHtml = "";
		
		$sHtml .= '<option value="">Select City</option>';
		if ($city['status']==1) {
			foreach ( $city['data'] as $key => $value ) {
				$select = '';
				if($select_city){
					if($select_city==$value['origin']){
						$select = "selected=selected";
					}
				}
				$sHtml .= '<option value="' . $value['origin']. '" '.$select.'>' . $value['city_name'] . '</option>';
			}
		}
		
		echo json_encode ( array (
				'result' => $sHtml 
		) );
		exit ();
	}

    public function send_booking_link($redirect=true)
    {
        //error_reporting(E_ALL);
        $post_data=$this->input->post();
       // debug($post_data);exit();

        //calculation for counting

        $post_data['adult_price'] = round($post_data['adult_price']);
        $post_data['child_price'] = round($post_data['child_price']);
        $post_data['infant_price'] = round($post_data['infant_price']);
        if($post_data['adult_price']!='' || $post_data['child_price']!=''|| $post_data['infant_price']){
            $post_data['total'] = ($post_data['adult_price']+$post_data['child_price']+$post_data['infant_price']);
        }


        //end

            $post_data['departure_date']=date('Y-m-d',strtotime($post_data['departure_date']));
            $enquiry_data = $post_data;

        $enquiry_data['tour_id'] = ($post_data['tour_id'])? $post_data['tour_id'] : $enquiry_data['tour_id'];

        $quote_reference = generate_holiday_reference_number('ZVQ');
        
        $tours_quotation_log_data=array();
        $tours_quotation_log_data['quote_reference']=$quote_reference;
        $tours_quotation_log_data['tour_id']=$enquiry_data['tour_id'];
        $tours_quotation_log_data['first_name']=$enquiry_data['name'];
        $tours_quotation_log_data['email']=$enquiry_data['email'];
        $tours_quotation_log_data['phone']=$enquiry_data['phone'];
        $tours_quotation_log_data['en_note'] =  $post_data['en_note'];
        $tours_quotation_log_data['quoted_price']=round($post_data['total']);
        $tours_quotation_log_data['currency_code']=get_application_currency_preference();
        $tours_quotation_log_data['user_attributes']=json_encode($post_data);
        $tours_quotation_log_data['created_by_id']=$this->entity_user_id;
        $tours_quotation_log_data['created_datetime']=date('Y-m-d H:i:s');

        $this->custom_db->insert_record('tours_quotation_log',$tours_quotation_log_data);
        $ex_data['en_note'] = $post_data['en_note'];
        if($post_data['quote_type']=='request_quote'){
            # debug($enquiry_data['email']); exit('xxx');
            if(!empty($enquiry_data['email']))
            {
                $ex_data['name']=$enquiry_data['name'];
                #debug($ex_data['name']); exit;
                $res = $this->voucher($enquiry_data['tour_id'],'mail','mail',$quote_reference,'',$enquiry_data['email'],'redirect',$ex_data);

            }
        }else{
//            if($post_data['enquiry_reference_no']){
//                $tours_enquiry = $this->custom_db->get_result_by_query('SELECT * FROM tours_enquiry WHERE enquiry_reference_no = "'.$post_data['enquiry_reference_no'].'" ');
//                if($tours_enquiry){
//                    $tours_enquiry = json_decode(json_encode($tours_enquiry),1);
//                    $post_data['tour_id'] = $tours_enquiry[0]['tour_id'];
//                    $post_data['departure_date'] = $tours_enquiry[0]['departure_date'];
//                }
//            }
            $app_reference = generate_holiday_reference_number('ZVZ');
            $tour_booking_details_data=array();
//            $tour_booking_details_data['enquiry_reference_no']=$post_data['enquiry_reference_no'];
            $tour_booking_details_data['app_reference']=$app_reference;
            $tour_booking_details_data['status']='PROCESSING';
            $tour_booking_details_data['basic_fare']=round($post_data['total']);
            $tour_booking_details_data['currency_code']=$post_data['currency'];
            $tour_booking_details_data['payment_status']='unpaid';
            $tour_booking_details_data['created_datetime']=date('Y-m-d H:i:s');
            $tour_booking_details_data['created_by_id']=$this->entity_user_id;
            $tour_booking_details_data['attributes']=json_encode($post_data);

            $this->custom_db->insert_record('tour_booking_details',$tour_booking_details_data);
            $booking_url = base_url().'index.php/tours/pre_booking/'.$app_reference;
            $booking_url = str_replace('supervision/', '', $booking_url);
            if(!empty($enquiry_data['email']))
            {
                $ex_data['booking_url']=$booking_url;
                $ex_data['name']=$enquiry_data['name'];
                $res = $this->voucher($enquiry_data['tour_id'],'mail','mail','',$app_reference,$enquiry_data['email'],'redirect',$ex_data);
            }
        }

        // set_update_message ();
        $this->session->set_flashdata('message', UL0013);
        if(1){
            $this->load->library('user_agent');
            if ($this->agent->is_referral())
            {
                redirect ( $this->agent->referrer());
            }else{
                redirect ( base_url () . 'index.php/tours/tours_enquiry');
            }
        }
    }

    public function voucher($tour_id,$operation='show_broucher',$mail = 'no-mail',$quotation_id = '',$app_reference = '',$email = '',$redirect = '',$ex_data = array())
    {
        $page_data['tour_id'] = $tour_id;
        $this->load->model('tours_model');
        $page_data['menu'] = false;
        // debug( $page_data['tour_id']);exit;
        $where = ['package_id'=>$tour_id];
        $page_data ['tour_data']            = $this->tours_model->package_data('package', $where);
        // debug($page_data ['tour_data']); exit('I am ready');
        $page_data ['tours_itinerary']      = $this->tours_model->tours_itinerary($tour_id,$dep_date);
        $page_data ['tours_itinerary_dw']   = $this->tours_model->tours_itinerary_dw($tour_id,$dep_date);
        $page_data ['tours_itinerary_wd']   = $this->tours_model->tours_itinerary_dw($tour_id);
        $page_data ['tours_date_price']     = $this->tours_model->tours_date_price($tour_id);
        $tour_data = $this->custom_db->get_result_by_query("select group_concat(airliner_price) pricing, group_concat(occupancy) occ, group_concat(markup) markup ,tour_id, from_date, to_date , currency from tour_price_management where tour_id = ".$tour_id." group by from_date, to_date ");
        $page_data['tour_price'] = json_decode(json_encode($tour_data),true);
         
        $tour_cities =  $page_data['tour_data']['tours_city'];
        $tour_cities_array = json_decode($tour_cities);
        foreach ($tour_cities_array as $t_city) {
            $query_x = "select * from tours_city where id='$t_city'";
            $exe_x   = mysql_query($query_x);
            $visited_city[] = mysql_fetch_assoc($exe_x);
        }
        $page_data['visited_city'] = $visited_city;
        if ($quotation_id!='') {
            $quotation_details = $this->tours_model->quotation_details($quotation_id);
            if ($quotation_details['status']==1) {
                $page_data['quotation_details'] = $quotation_details['data'];
            }
        }
        if ($app_reference!='') {
            $booking_details = $this->tours_model->booking_details($app_reference);
            if ($booking_details['status']==1) {
                $page_data['booking_details'] = $booking_details['data'];
            }
        }

        if($mail == 'mail') {
            $operation="mail";
            if($this->input->post('email')){
                $email = $this->input->post('email');
            }
        }
        switch ($operation) {
            case 'show_broucher' :
                $page_data['menu'] = true;
                $this->template->view('tours/broucher',$page_data);
                break;
            case 'show_pdf' :
                $get_view = $this->template->isolated_view ( 'tours/broucher_pdf',$page_data );

                $this->load->library ( 'provab_pdf' );
                $this->provab_pdf->create_pdf ( $get_view, 'D');
                break;
            case 'mail' :
            // debug($ex_data['booking_url']);
            // debug($page_data);die;
               // $mail_template_mail =$this->template->isolated_view('tours/activity_broucher',$page_data);
                $mail_template =$this->template->isolated_view('tours/activity_broucher_pdf',$page_data);
                // echo $mail_template;die;
                $this->load->library ( 'provab_pdf' );
                $this->load->library ( 'provab_mailer' );
                $pdf = $this->provab_pdf->create_pdf($mail_template,'F');
                if(count($ex_data)>0){
                    $message = '<strong>Dear '.$ex_data['name'].',<br><br></strong>';
                    if($ex_data['booking_url']){
                        $message .= '<b>Please find the Link below.</b><br><a href="'.$ex_data['booking_url'].'" target="_blank"><h3>Click here to Book</h3></a>';
                    }
                }
                $res = $this->provab_mailer->send_mail($email, 'Broucher', $message.$mail_template,$pdf);
                if($redirect != ''){
                    return true;
                }else{
                    redirect(base_url().'tours/voucher/'.$tour_id,'refresh');
                }
                break;
        }
    }



    //New Code For transfer....@neha


   	public function add_transfer($transfer_id='') 
		{
			$data ['package_type_data'] = $this->transfers_model->package_view_data_types ()->result ();
			$data ['weekdays_name'] = $this->transfers_model->get_weekdays ()->result ();
			$data ["country"] = $this->transfers_model->get_country_list ();
			if(isset($transfer_id) && !empty($transfer_id)){
				$data['transfer_data'] = $this->transfers_model->get_transfer_data ( $transfer_id );
				$data['transfer_dates_data'] = $this->transfers_model->get_transfer_dates ( $transfer_id );
				$data['vehicle_data'] = $this->transfers_model->get_vehicle_driver_data ( $transfer_id );
				$data['all_vehicle_data'] = $this->transfers_model->get_all_vehicle_data ();
				$data['all_driver_data'] = $this->transfers_model->get_all_driver_data ();
				 //debug($data['all_driver_data']);exit;
				$data['price_nationality'] = $this->transfers_model->get_transfer_price_nationality ( $transfer_id );
				$data['price_data'] = $this->transfers_model->get_transfer_price_data ( $transfer_id );
			}

			$data ['nationality_group_data'] = $this->transfers_model->nationality_group_data ();
		//	debug($data);exit;
			
			
     		$this->template->view ( 'transfers/add_transfer_formcontrol', $data );
	}



	// public function add_transfer_details_old() {
	// 	$params = $this->input->post();
		
	// 	$photo = $_FILES['transfer_image'];
	// 	if ($_FILES ['transfer_image'] ['name'] != '') {

	// 	    $config['upload_path']          = $this->template->domain_uploads_packages ();
 //            $config['allowed_types']        = 'jpg|png|jpeg';
 //            $config['max_size']             = 1024;
 //            $config['max_width']            = 1024;
 //            $config['max_height']           = 768;
 //            $config['encrypt_name']         = true;
 //            $this->load->library('upload', $config);
	// 		if ( ! $this->upload->do_upload('transfer_image'))
	// 		{
	// 			echo $this->upload->display_errors();die;
	// 		}
	// 		$photo = $this->upload->data()['file_name'];
	// 	} 

	// 	$vehicle_image = $_FILES['vehicle_image'];
	// 	if ($_FILES ['vehicle_image'] ['name'] != '') {

	// 	    $config['upload_path']          = $this->template->domain_uploads_packages ();
 //            $config['allowed_types']        = 'jpg|png|jpeg';
 //            $config['max_size']             = 1024;
 //            $config['max_width']            = 1024;
 //            $config['max_height']           = 768;
 //            $config['encrypt_name']         = true;
 //            $this->load->library('upload', $config);
	// 		if ( ! $this->upload->do_upload('vehicle_image'))
	// 		{
	// 			echo $this->upload->display_errors();die;
	// 		}
	// 		$vehicle_image = $this->upload->data()['file_name'];
	// 	} 
	// 	if(isset($params['equipment']) && !empty($params['equipment'])){
	// 		$equipment = json_encode($params['equipment']);
	// 	}

	// 	$post_data = array (
	// 			'transfer_type' => $params['transfer_type'],
	// 			'transfer_name' => $params['transfer_name'],
	// 			'source' => $params['source'],
	// 			'destination' => $params['destination'],
	// 			'start_date' => $params['start_date'],
	// 			'expiry_date' => $params['expire_date'],
	// 			// 'pickup_time_form' => $params['pick_up_time_from'],
	// 			'description' => $params['description'],
	// 			'rating' => $params['rating'],
	// 			'vehicle_type' => $params['vehicle_type'],
	// 			// 'vehicle_type_code' => $params['vehicle_type_code'],
	// 			'vehicle_name' => $params['vehicle_name'],
	// 			'vehicle_number' => $params['vehicle_number'],
	// 			'equipment' => $equipment,
	// 			'max_passenger' => $params['max_passenger'],
	// 			'max_luggage' => $params['max_luggage'],
	// 			'driver_name' => $params['driver_name'],
	// 			'driver_shift_from' => $params['driver_shift_from'],
	// 			'driver_shift_to' => $params['driver_shift_to'],
	// 			'driver_license' => $params['driver_license'],
	// 			'price' => $params['price'],
	// 			'price_includes' => $params['includes'],
	// 			'price_excludes' => $params['excludes'],
	// 			'cancellation_advance' => $params['cancellation_advance'],
	// 			'cancellation_penalty' => $params['cancellation_penality'],
	// 			'image' => $photo,
	// 			'vehicle_image' => $vehicle_image,
	// 			'status' => 'INACTIVE',

	// 	);

	// 	$transfer_id = $this->transfers_model->add_new_transfer($post_data);
	// 	redirect ( 'transfers/view_with_price' );
	// }


	public function add_transfer_details($id='') {
		$params = $this->input->post();

		// $sup_id = $this->session->userdata ( 'sup_id' );
		// $sup_type = $this->session->userdata ( 'sup_type' );
// 
		// debug($params);exit;
		
		$photo = $_FILES['transfer_image'];
		if ($_FILES ['transfer_image'] ['name'] != '') {

		    $config['upload_path']          = realpath ( '../extras' ).'/custom/'.CURRENT_DOMAIN_KEY.'/uploads/packages/';
            $config['allowed_types']        = 'jpg|png|jpeg';
            $config['encrypt_name']         = true;
            $this->load->library('upload', $config);
			if ( ! $this->upload->do_upload('transfer_image'))
			{
				echo $this->upload->display_errors();die;
			}
			$photo = $this->upload->data()['file_name'];
		}  else{
			$photo = $params['transfer_image'];
		}

		
		if(isset($params['equipment']) && !empty($params['equipment'])){
			$equipment = json_encode($params['equipment']);
		}

		if(isset($params['driver_shift_days']) && !empty($params['driver_shift_days'])){
			$driver_shift_days = json_encode($params['driver_shift_days']);
		}

		$post_data = array (
				'transfer_type' => $params['transfer_type'],
				'transfer_name' => $params['transfer_name'],
				'source' => $params['source'],
				'destination' => $params['destination'],
				'country_code' => $params['country_code'],
				'city' => $params['city'],
				'origin' => $params['origin'],
				'airport_code' => $params['airport_code'],
				'distance' => $params['distance'],
				'duration' => $params['time_duration'],
				// 'start_date' => $params['start_date'],
				// 'expiry_date' => $params['expire_date'],
				// 'no_days' => $params['no_days'],
				'driver_shift_days' => $driver_shift_days,
				'description' => $params['description'],
				'rating' => $params['rating'],
				'image' => $photo,
				'user_type' => 1
				
				// 'status' => 'INACTIVE',

		);

		
		if(isset($id) && !empty($id)){
			$transfer_id= $id;
			 $this->transfers_model->update_transfer_data($post_data,$id);
		}else{
			$post_data['status'] = 'INACTIVE';
			$post_data['created_by_id'] = $this->entity_user_id;
			$transfer_id = $this->transfers_model->add_new_transfer($post_data);
			
		}
		//dates saving..........
	
		if(isset($transfer_id) && !empty($transfer_id)){
			foreach ($params['dates'] as $key => $dvalue) {
				$start_date = date('Y-m-d',strtotime($dvalue['start_date']));
				$exp_date = date('Y-m-d',strtotime($dvalue['expire_date']));
				$data = array (
					'reference_id' => $transfer_id,
					'start_date' => $start_date,
					'expiry_date' => $exp_date,
					'no_days' => $dvalue['no_days']

			     );
				// debug($dvalue);exit;
				if(isset($dvalue['id']) && !empty($dvalue['id'])){
					$this->transfers_model->update_transfer_dates($data,$dvalue['id']);
				}else{
					$id = $this->transfers_model->add_transfer_dates($data);
				}
			}
		}

		redirect ( 'transfers/add_transfer/'.$transfer_id );
	}

	

	public function view_transfer_list() {
		$user_type = 1;
		$data ['data_list'] = $this->transfers_model->transfer_list ($user_type);
		$i=0;

		foreach($data ['data_list'] as $val)
		{

			$transfer_id = $val->id;
			$data['data_list'][$i]->start_date = $this->transfers_model->get_transfer_dates ( $transfer_id );
			$i++;
		}
		// debug($data['data_list']);exit;
		
     	$this->template->view ( 'transfers/view_transfer_list', $data );
	}
	public function view_transfer_list_supplier() {
		$user_type = 8;
		$data ['data_list'] = $this->transfers_model->transfer_list($user_type);
		$i=0;

		foreach($data ['data_list'] as $val)
		{

			$transfer_id = $val->id;
			$data['data_list'][$i]->start_date = $this->transfers_model->get_transfer_dates( $transfer_id );
			$i++;
		}
		// debug($data['data_list']);exit;
		
     	$this->template->view ( 'transfers/view_transfer_list_supplier', $data );
	}
	public function view_transfer_list_staff() {
		$user_type = 9;
		$data ['data_list'] = $this->transfers_model->transfer_list($user_type);
		$i=0;

		foreach($data ['data_list'] as $val)
		{

			$transfer_id = $val->id;
			$data['data_list'][$i]->start_date = $this->transfers_model->get_transfer_dates( $transfer_id );
			$i++;
		}
		// debug($data['data_list']);exit;
		
     	$this->template->view ( 'transfers/view_transfer_list_staff', $data );
	}
	public function edit_transfer_formcontrol($transfer_id) {
		$data['transfer_data'] = $this->transfers_model->get_transfer_data ( $transfer_id );
		$this->template->view ( 'transfers/edit_transfer_formcontrol', $data );
	}

	public function edit_vehicle_details($transfer_id) {
		$data['transfer_data'] = $this->transfers_model->get_transfer_data ( $transfer_id );
		$data['transfer_vehicle_data'] = $this->transfers_model->get_transfer_driver_data ( $transfer_id );
		// $data['transfer_price_data'] = $this->transfers_model->get_transfer_price_data ( $reference_id );
		
		$this->template->view ( 'transfers/edit_vehicle_details', $data );
	}

	public function edit_price_details($transfer_id) {
		$data['transfer_data'] = $this->transfers_model->get_transfer_data ( $transfer_id );
		$data ['weekdays_name'] = $this->transfers_model->get_weekdays ()->result ();
		$data['transfer_price_data'] = $this->transfers_model->get_transfer_price_data ( $transfer_id );
		
		$this->template->view ( 'transfers/edit_price_details', $data );
	}

	public function edit_transfer_details($id) {
		$params = $this->input->post();
		// debug($params);exit;
		
		$photo = $params['transfer_image'];
		if ($_FILES ['transfer_image'] ['name'] != '') {

		    $config['upload_path']          = realpath ( '../extras' ).'/custom/'.CURRENT_DOMAIN_KEY.'/uploads/packages/';
            $config['allowed_types']        = 'jpg|png|jpeg';
            $config['encrypt_name']         = true;
            $this->load->library('upload', $config);
			if ( ! $this->upload->do_upload('transfer_image'))
			{
				echo $this->upload->display_errors();die;
			}
			$photo = $this->upload->data()['file_name'];
		} 

		// $vehicle_image = $params['vehicle_image'];
		// if ($_FILES ['vehicle_image'] ['name'] != '') {

		//     $config['upload_path']          = $this->template->domain_uploads_packages ();
  //           $config['allowed_types']        = 'jpg|png|jpeg';
  //           $config['max_size']             = 1024;
  //           $config['max_width']            = 1024;
  //           $config['max_height']           = 768;
  //           $config['encrypt_name']         = true;
  //           $this->load->library('upload', $config);
		// 	if ( ! $this->upload->do_upload('vehicle_image'))
		// 	{
		// 		echo $this->upload->display_errors();die;
		// 	}
		// 	$vehicle_image_data = $this->upload->data()['file_name'];
		// } 
		// if(isset($params['equipment']) && !empty($params['equipment'])){
		// 	$equipment = json_encode($params['equipment']);
		// }
		$post_data = array (
				
				'transfer_name' => $params['transfer_name'],
				'source' => $params['source'],
				'destination' => $params['destination'],
				'start_date' => $params['start_date'],
				'expiry_date' => $params['expiry_date'],
				'description' => $params['description'],
				'rating' => $params['rating'],
				'price_includes' => $params['includes'],
				'price_excludes' => $params['excludes'],
				'cancellation_advance' => $params['advance'],
				'cancellation_penalty' => $params['penality'],
				'image' => $photo
				// 'vehicle_image' => $vehicle_image
		
		);
		
		$transfer_id = $this->transfers_model->update_transfer_data($post_data,$id);
		redirect ( 'transfers/view_transfer_list' );
	}


	public function edit_transfer_vehicle_details($id) {
		$params = $this->input->post();
		// debug($_FILES);exit;
		// debug($params);exit;
	
		$vehicle_image = $_FILES['vehicle_image'];
		if ($_FILES ['vehicle_image'] ['name'] != '') {

		    $config['upload_path']          = realpath ( '../extras' ).'/custom/'.CURRENT_DOMAIN_KEY.'/uploads/packages/';
            $config['allowed_types']        = 'jpg|png|jpeg';
            $config['encrypt_name']         = true;
            $this->load->library('upload', $config);
			if ( ! $this->upload->do_upload('vehicle_image'))
			{
				echo $this->upload->display_errors();die;
			}
			$vehicle_image = $this->upload->data()['file_name'];
		} else{
			$vehicle_image = $params['vehicle_image'];
		}
		if(isset($params['equipment']) && !empty($params['equipment'])){
			$equipment = json_encode($params['equipment']);
		}
		// debug($photo);
		// debug($vehicle_image);
		$post_data = array (
				
				'vehicle_type' => $params['vehicle_type'],
				// 'vehicle_type_code' => $params['vehicle_type_code'],
				'vehicle_name' => $params['vehicle_name'],
				'vehicle_number' => $params['vehicle_number'],
				'equipment' => $equipment,
				'max_passenger' => $params['max_passenger'],
				'max_luggage' => $params['max_luggage'],
				'vehicle_image'=>$vehicle_image
				
		);
		// debug($post_data);exit;
		$transfer_id = $this->transfers_model->update_transfer_data($post_data,$id);

		if(isset($params['driver']) && !empty($params['driver'])){
			// debug($params['driver']);exit;
	        foreach ($params['driver'] as $key => $driver_info) {
				$vehicle_id = $driver_info['id'];
				$num = $key;
				// debug($_FILES);exit;
				if(isset($vehicle_id)){

				$driver_info['driver_shift_days'] = json_encode($driver_info['driver_shift_days']);

				$driver_license = $_FILES['driver_license_'.$num.'']['name'];
				if ($_FILES ['driver_license_'.$num.''] ['name'] != '') {

				    $config['upload_path']          = realpath ( '../extras' ).'/custom/'.CURRENT_DOMAIN_KEY.'/uploads/packages/';
		            $config['allowed_types']        = '*';
		            $config['encrypt_name']         = true;
		            $this->load->library('upload', $config);
					if ( ! $this->upload->do_upload('driver_license_'.$num.''))
					{
						echo $this->upload->display_errors();die;
					}
					$driver_license = $this->upload->data()['file_name'];
				} else{
					$driver_license = $driver_info['driver_license'];
				}

				$driver_info['driver_license']=$driver_license;
				$driver_info['reference_id']=$id;
				// debug($driver_info);exit;
				$this->transfers_model->update_transfer_driver_data($driver_info,$vehicle_id);
			    }else{
			    	// debug($num);
			    	// debug($_FILES);exit;
			    	
				$driver_info['reference_id'] = $id;
				$driver_info['driver_shift_days'] = json_encode($driver_info['driver_shift_days']);
				
				$driver_license = $_FILES['driver_license_'.$num.''];
				if ($_FILES ['driver_license_'.$num.''] ['name'] != '') {

				    $config['upload_path']          = realpath ( '../extras' ).'/custom/'.CURRENT_DOMAIN_KEY.'/uploads/packages/';
		            $config['allowed_types']        = '*';
		            $config['encrypt_name']         = true;
		            $this->load->library('upload', $config);
					if ( ! $this->upload->do_upload('driver_license_'.$num.''))
					{
						echo $this->upload->display_errors();die;
					}
					$driver_license = $this->upload->data()['file_name'];
				} 
				// debug($driver_license);exit;
				$driver_info['driver_license']=$driver_license;
				// debug($driver_info);exit;
				$this->transfers_model->transfer_driver_details($driver_info);
			}
			}
		}



		redirect ( 'transfers/view_transfer_list' );
	}


	public function edit_transfer_price_details($transfer_id) {
		$params = $this->input->post();
	
		if(isset($params['price']) && !empty($params['price'])){
			foreach ($params['price'] as $key => $price_info) {
				if(isset($price_info['id']) && !empty($price_info['id'])){
					$this->transfers_model->update_transfer_price_data($price_info,$price_info['id']);
				}else{
					$price_info['reference_id'] = $transfer_id;
					$this->transfers_model->transfer_price_details($price_info);
				}
			}
		}



		redirect ( 'transfers/view_transfer_list' );
	}


	public function delete_transfer($id) {
		$this->transfers_model->delete_transfer ( $id );
		redirect ( 'transfers/view_transfer_list' );
	}

//Transfer vehicle ...............................

	public function transfer_vehicle($id='') {
		$data = [];
		if(isset($id) && !empty($id)){
			$data['data'] = $this->transfers_model->get_transfer_vehicle_data ( $id );
		}
		// debug($data);exit;
		$this->template->view ( 'transfers/transfer_vehicle_formcontrol', $data );
	}


	public function add_transfer_vehicle($id='') {
		$params = $this->input->post();
		// debug($params);exit;
		$vehicle_image = $_FILES['vehicle_image'];
		if ($_FILES ['vehicle_image'] ['name'] != '') {

		    $config['upload_path']          = realpath ( '../extras' ).'/custom/'.CURRENT_DOMAIN_KEY.'/uploads/packages/';
            $config['allowed_types']        = 'jpg|png|jpeg';
            $config['encrypt_name']         = true;
            $this->load->library('upload', $config);
			if ( ! $this->upload->do_upload('vehicle_image'))
			{
				echo $this->upload->display_errors();die;
			}
			$vehicle_image = $this->upload->data()['file_name'];
		}  else{
			$vehicle_image = $params['vehicle_image'];
		}


		if(isset($params['equipment']) && !empty($params['equipment'])){
			$equipment = json_encode($params['equipment']);
		}

		$post_data = array (
				'vehicle_type' => $params['vehicle_type'],
				'vehicle_name' => $params['vehicle_name'],
				'vehicle_number' => $params['vehicle_number'],
				'equipment' => $equipment,
				'max_passenger' => $params['max_passenger'],
				'max_luggage' => $params['max_luggage'],
				'reg_number' => $params['reg_number'],
				'engine_number' => $params['engine_number'],
				'color' => $params['color'],
				'mileage' => $params['mileage'],
				'transmission' => $params['transmission'],
				'fuel_type' => $params['fuel_type'],
				'emision' => $params['emision'],
				'insurance_company' => $params['insurance_company'],
				'insurance_begin_date' => $params['insurance_begin_date'],
				'insurance_end_date' => $params['insurance_end_date'],
				'vehicle_image' => $vehicle_image,
				'created_by'=> $this->entity_user_id,
          		'user_type'=> 1
				// 'status' => 'INACTIVE',

		);
		// debug($post_data);exit;
		if(isset($id) && !empty($id)){
			$this->transfers_model->update_transfer_vehicle_data($post_data,$id);
		}else{
			$post_data['status'] = 'INACTIVE';
			$transfer_id = $this->transfers_model->add_transfer_vehicle($post_data);
		}
		redirect ( 'transfers/view_vehicle_list' );
	}

	public function view_vehicle_list() {
		$data ['data_list'] = $this->transfers_model->transfer_vehicle_list ();
     	$this->template->view ( 'transfers/view_transfer_vehicle_list', $data );
	}

	public function delete_transfer_vehicle($id) {
		$this->transfers_model->delete_transfer_vehicle( $id );
		redirect ( 'transfers/view_vehicle_list' );
	}

	public function update_vehicle_status($package_id, $status) {
		$this->transfers_model->update_vehicle_status ( $package_id, $status );
		redirect ( 'transfers/view_vehicle_list/', 'refresh' );
	}

	//Transfer Driver ...............................

	public function transfer_driver($id='') {
		$data = [];
		$data ["country"] = $this->transfers_model->get_country_list ();
		if(isset($id) && !empty($id)){
			$data['data'] = $this->transfers_model->get_transfer_driver ( $id );
		}
		// debug($data);exit;
		$this->template->view ( 'transfers/transfer_driver_formcontrol', $data );
	}


	public function add_transfer_driver($id='') {
		$params = $this->input->post();
		// debug($params);exit;
		$driver_photo = $_FILES['driver_photo'];
		if ($_FILES ['driver_photo'] ['name'] != '') {

		    $config['upload_path']          = realpath ( '../extras' ).'/custom/'.CURRENT_DOMAIN_KEY.'/uploads/packages/';
            $config['allowed_types']        = 'jpg|png|jpeg';
            $config['encrypt_name']         = true;
            $this->load->library('upload', $config);
			if ( ! $this->upload->do_upload('driver_photo'))
			{
				echo $this->upload->display_errors();die;
			}
			$driver_photo = $this->upload->data()['file_name'];
		}  else{
			$driver_photo = $params['driver_photo'];
		}

		$license_image = $_FILES['license_image'];
		if ($_FILES ['license_image'] ['name'] != '') {

		    $config['upload_path']          = realpath ( '../extras' ).'/custom/'.CURRENT_DOMAIN_KEY.'/uploads/packages/';
            $config['allowed_types']        = 'jpg|png|jpeg';
            $config['encrypt_name']         = true;
            $this->load->library('upload', $config);
			if ( ! $this->upload->do_upload('license_image'))
			{
				echo $this->upload->display_errors();die;
			}
			$license_image = $this->upload->data()['file_name'];
		}  else{
			$license_image = $params['license_image'];
		}


		$insurance_file = $_FILES['insurance_file'];
		if ($_FILES ['insurance_file'] ['name'] != '') {

		    $config['upload_path']          = realpath ( '../extras' ).'/custom/'.CURRENT_DOMAIN_KEY.'/uploads/packages/';
            $config['allowed_types']        = '*';
            $config['encrypt_name']         = true;
            $this->load->library('upload', $config);
			if ( ! $this->upload->do_upload('insurance_file'))
			{
				echo $this->upload->display_errors();die;
			}
			$insurance_file = $this->upload->data()['file_name'];
		}  else{
			$insurance_file = $params['insurance_file'];
		}


		$driver_id_proof = $_FILES['driver_id_proof'];
		if ($_FILES ['driver_id_proof'] ['name'] != '') {

		    $config['upload_path']          = realpath ( '../extras' ).'/custom/'.CURRENT_DOMAIN_KEY.'/uploads/packages/';
            $config['allowed_types']        = '*';
            $config['encrypt_name']         = true;
            $this->load->library('upload', $config);
			if ( ! $this->upload->do_upload('driver_id_proof'))
			{
				echo $this->upload->display_errors();die;
			}
			$driver_id_proof = $this->upload->data()['file_name'];
		}  else{
			$driver_id_proof = $params['driver_id_proof'];
		}


// debug("hiiii");exit;

		if(isset($params['driver_shift_days']) && !empty($params['driver_shift_days'])){
			$driver_shift_days = json_encode($params['driver_shift_days']);
			//debug($driver_shift_days);exit;
		}

		$driver_shift_from = date("H:i", strtotime($params['driver_shift_from']));
		$driver_shift_to = date("H:i", strtotime($params['driver_shift_to']));
		 //debug($driver_shift_from);
		$params['driver_shift_from'] = $this->hoursToMinutes($driver_shift_from);
		$params['driver_shift_to'] = $this->hoursToMinutes($driver_shift_to);

		if($params['driver_shift_from']>=$params['driver_shift_to']){
			$params['shift_to_time'] = 'N';
		}else{
			$params['shift_to_time']= 'S';
		}

		$post_data = array (
				'driver_name' => $params['driver_name'],
				'contact_number' => $params['contact_number'],
				'email' => $params['email'],
				'country' => $params['country'],
				'city' => $params['city'],
				'location' => $params['location'],
				'address' => $params['address'],
				'driver_shift_days' => $driver_shift_days,
				'driver_shift_from' => $params['driver_shift_from'],
				'driver_shift_to' => $params['driver_shift_to'],
				'shift_to_time' => $params['shift_to_time'],
				'license_number' => $params['license_number'],
				'license_validity' => $params['license_validity'],
				'display_cart_number' => $params['display_cart_number'],
				'display_cart_validity' => $params['display_cart_validity'],
				'insurance_number' => $params['insurance_number'],
				'insurance_file' => $insurance_file,
				'driver_id_proof' => $driver_id_proof,
				'driver_photo' => $driver_photo,
				'license_image' => $license_image,
				'created_by'=> $this->entity_user_id,
          		'user_type'=> 1
				// 'status' => 'INACTIVE',

		);
		// debug($post_data);exit;
		if(isset($id) && !empty($id)){
			$this->transfers_model->update_transfer_driver($post_data,$id);
		}else{
			// $post_data['status'] = 'INACTIVE';
			$transfer_id = $this->transfers_model->add_transfer_driver($post_data);
		}
		redirect ( 'transfers/view_driver_list' );
	}

	public function view_driver_list() {
		$data ['data_list'] = $this->transfers_model->transfer_driver_list ();
     	$this->template->view ( 'transfers/view_transfer_driver_list', $data );
	}

	public function delete_transfer_driver($id) {
		$this->transfers_model->delete_transfer_driver( $id );
		redirect ( 'transfers/view_driver_list' );
	}

	public function update_driver_status($package_id, $status) {
		$this->transfers_model->update_driver_status ( $package_id, $status );
		redirect ( 'transfers/view_driver_list/', 'refresh' );
	}

	public function get_vehicle_list(){
        $params = $this->input->post();
		// $req = $_REQUSET[];
		$avlble_weekdys = json_encode($params['avlble_weekdys']);
		$shift_time_start = $this->hoursToMinutes($params['shift_time_start']);
		$shift_time_end = $this->hoursToMinutes($params['shift_time_end']);
		
		// $query_vh = "SELECT `id`, `vehicle_type`, `vehicle_name` FROM `transfer_vehicle_info` INNER JOIN transfer_map_vehicle_driver ON transfer_map_vehicle_driver.vehicle_id=transfer_vehicle_info.id  WHERE status='ACTIVE' ORDER BY vehicle_name ASC";
		// $query_vh = "SELECT `transfer_vehicle_info`.`id`, `vehicle_type`, `vehicle_name` FROM `transfer_vehicle_info` JOIN `transfer_map_vehicle_driver` ON `transfer_map_vehicle_driver`.`vehicle_id`=`transfer_vehicle_info`.`id`  WHERE `transfer_vehicle_info`.`status`='ACTIVE' GROUP BY `transfer_vehicle_info`.`id` ORDER BY vehicle_name ASC";

		$vehicle_details = $this->transfers_model->get_vehicle_details($params, $shift_time_start, $shift_time_end );
		// $query_vh = "
		// 		SELECT `id`, `vehicle_type`, `vehicle_name` 
		// 		FROM `transfer_vehicle_info` AS vehicle
		// 		WHERE NOT EXISTS (
		// 		  SELECT *
		// 		  FROM `transfer_map_vehicle_driver`
		// 		  WHERE `transfer_map_vehicle_driver`.`day`='".$params['day']."' AND  
		// 		  		`transfer_map_vehicle_driver`.`date`='".$params['date']."' AND
		// 		  		".$shift_time_start."  BETWEEN `transfer_map_vehicle_driver`.`shift_time_from` AND `transfer_map_vehicle_driver`.`shift_time_to`
		// 		)";

		// debug($query_vh);
	
		$data['vehicle'] = $vehicle_details;
	
		// $query_dr = "SELECT `id`,  `driver_name` FROM `transfer_driver_info` WHERE status='ACTIVE'
		// AND NOT EXISTS (
		// 		  SELECT *
		// 		  FROM `transfer_driver_info`
		// 		  WHERE  ".$shift_time_start."  BETWEEN `transfer_driver_info`.`driver_shift_from` AND `transfer_driver_info`.`driver_shift_to`  AND '".$weekday."' IN (`transfer_driver_info`.`driver_shift_days`)
		// 		)

		//  ORDER BY driver_name ASC";
		$driver = array();
		$driver_details = $this->transfers_model->get_driver_details($params, $shift_time_start, $shift_time_end  );
		$j=0;
		foreach ($driver_details as $key => $val) {
			$driver_shift = json_decode($val->driver_shift_days);
			$cnt = 0;
			for ($i=0; $i <count($params['avlble_weekdys']) ; $i++) { 
				if(in_array($params['avlble_weekdys'][$i], $driver_shift))
				{
					$cnt++;
				}
			}
			if($cnt==count($params['avlble_weekdys'])){
				$driver[$j]['id'] = $val->id;
				$driver[$j]['driver_name'] = $val->driver_name;
			}
			$j++;
		}
		//  	$query_dr = "SELECT `id`,  `driver_name` FROM `transfer_driver_info` WHERE status='ACTIVE'
		// AND ".$shift_time_start."  BETWEEN `transfer_driver_info`.`driver_shift_from` AND `transfer_driver_info`.`driver_shift_to`  

		//  ORDER BY driver_name ASC";
	
		$data['driver'] =$driver;
		// debug($data);exit;
		//echo $this->db->last_query();exit;
		echo json_encode ( array (
				'result' => $data 
		) );
		exit ();
		// return json_encode($response);
			 
	}

	public function add_vehicle_driver($id){

		$params = $this->input->post();
		$shift_from = date("H:i", strtotime($params['shift_from']));
		$shift_to = date("H:i", strtotime($params['shift_to']));
		//debug($params);exit;
		$params['shift_from'] = $this->hoursToMinutes($shift_from);
		$params['shift_to'] = $this->hoursToMinutes($shift_to);
		if($params['shift_from']>=$params['shift_to'])
		{
			$params['shift_to_time'] = 'N';
		}else{
			$params['shift_to_time']= 'S';
		}



			$post_data = array (
						'reference_id' => $params['reference_id'],
						'date_range_id' => $params['date_range_id'],
						'shift_time_from' => $params['shift_from'],
						'shift_time_to' => $params['shift_to'],
						'shift_to_time' => $params['shift_to_time'],
						'vehicle_id' => $params['vehicle'],
						'driver_id' => $params['driver'],
						'availability' => '1',
						'status' => 'INACTIVE'

				);

		 //debug($post_data);exit;
		if($params['update_id']!='')
      {
         
        $transfer_id = $this->transfers_model->update_map_vehicle_driver($post_data,$params['update_id']);
      }
      else
      {
      	$chech_status =  $this->custom_db->single_table_records('transfer_map_vehicle_driver','count(*) as cnt',array('reference_id'=>$params['reference_id'],'date_range_id'=>$params['date_range_id'],'shift_time_from'=>$params['shift_from'],'shift_time_to'=>$params['shift_to']))['data'][0];
      	if($chech_status['cnt']==0){
         $transfer_id = $this->transfers_model->add_map_vehicle_driver($post_data);
      	}else{
      		$this->session->set_flashdata('message', UL0098);
      	}
      }
		redirect ( 'transfers/add_transfer/'.$id.'/vehicle', 'refresh' );

	}

	public function add_tranfer_price($id){
		$params = $this->input->post();
		// debug($params);exit;
		if(isset($params['price']) && !empty($params['price'])){
			foreach ($params['price'] as $key => $price_info) {
				$price_details = array();
				 // if(!empty($params['interested_'.$key])){
					// $price_info['default_nationality'] = 1;
				 // }
				 // else{
				 // 	$price_info['default_nationality'] = 0;
				 // }
				 // $currency_obj = new Currency(array('module_type' => 'country_wise_price','from' => $price_info['currency'], 'to' => 'AED')); 
			  //    $get_currency_symbol = ($this->session->userdata('currency') != '') ? $this->CI->session->userdata('currency') : 'AED';
			  //    $current_currency_symbol = $currency_obj->get_currency_symbol($get_currency_symbol);
			  //    $converted_currency_rate = $currency_obj->getConversionRate(false);
			  //    $price_info['display_price_aed'] = $converted_currency_rate*$price_info['display_price'];
			  //    $price_info['price_aed'] = $converted_currency_rate*$price_info['price'];
				$price_info_ary      = implode(',',$price_info['shift_day']);
				$price_details['shift_day'] = $price_info_ary;
				$price_details['nationality_group'] = $price_info['country'];
				$price_details['nationality_group_name'] = $price_info['country_name'];
				$price_details['date_range'] = $price_info['date_range_price'];
				$price_details['date_from'] = $price_info['date_from'];
				$price_details['date_to'] = $price_info['date_to'];
				$price_details['shift_from'] = $price_info['shift_from'];
				$price_details['shift_to'] = $price_info['shift_to'];
				$shiftFrom = date("H:i", strtotime($price_info['shift_from']));
				$shiftTo = date("H:i", strtotime($price_info['shift_to']));
				$shift_from_min = $this->hoursToMinutes($shiftFrom);
				$shift_to_min = $this->hoursToMinutes($shiftTo);
				$price_details['shift_from_min'] = $shift_from_min;
				$price_details['shift_to_min'] = $shift_to_min;
				if($shift_from_min>=$shift_to_min){
					$price_details['shift_to_time'] = 'N';
				}else{
					$price_details['shift_to_time']= 'S';
				}

				$price_details['price'] = $price_info['price'];
				$price_details['currency'] = 'AED';
				// debug($price_details);exit;
				if(isset($price_info['id']) && !empty($price_info['id'])){
					$this->transfers_model->update_transfer_price_data($price_details,$price_info['id']);
					// echo $this->db->last_query();exit;
				}else{
					$price_details['reference_id'] = $id;
					$this->transfers_model->transfer_price_details($price_details);
				}
			}
		}
		redirect ( 'transfers/add_transfer/'.$id.'/price_manage', 'refresh' );

	}
	public function delete_transfer_price(){
		$price_id = $this->input->post('price_id');
		$return = $this->transfers_model->record_delete('transfer_price_info',$price_id);
	    if($return){
	      $this->session->set_flashdata('message', UL0099);
	    }
	    else { echo $return;} 
		// redirect ( 'transfers/add_transfer/'.$id.'/price_manage', 'refresh' );

	}


	public function add_rate_card($id){
		$params = $this->input->post();
		$post_data = array (
			'price_includes' => $params['includes'],
			'price_excludes' => $params['excludes'],
			'cancellation_advance' => $params['cancellation_advance'],
			'cancellation_penalty' => $params['cancellation_penality'],
			'exclusive_ride' => $params['exclusive_ride'],
			'meetup_location' => $params['meetup_location'],
			'general_list_info' => $params['general_list_info'],
			'pick_up_info' => $params['pick_up_info'],
			'guidelines_list' => $params['guidelines_list'],
			'contact_address' => $params['contact_address'],
			'contact_email' => $params['contact_email'],
		);
		// debug($post_data);exit;
		$transfer_id = $this->transfers_model->update_transfer_data($post_data,$id);
		redirect ( 'transfers/view_transfer_list' );

	}

	public function update_transfer_status($package_id, $status) {
		$this->transfers_model->update_transfer_status ( $package_id, $status );
		redirect ( 'transfers/view_transfer_list/', 'refresh' );
	}



public function delete_transfer_details($id) {
		$this->transfers_model->delete_transfer_details( $id );
		redirect ( 'transfers/view_transfer_list' );
	}


 public function get_currency($country_id){
 	// debug($country_id);exit;
 	    $data=$this->transfers_model->get_currency($country_id);
		// debug($data);exit;
		echo json_encode ( array (
				'result' => $data[0] 
		) );
		exit ();
 }


 public function check_vehicle_mapping(){
        $params = $this->input->post();
        $shift_time_start = $this->hoursToMinutes($params['shift_time_start']);
		$query_vh = "
				  SELECT `transfer_map_vehicle_driver`.`id`
				  FROM `transfer_map_vehicle_driver`
				  WHERE 
				  		`transfer_map_vehicle_driver`.`reference_id`='".$params['reference_id']."' AND  
				  		`transfer_map_vehicle_driver`.`day`='".$params['day']."' AND  
				  		`transfer_map_vehicle_driver`.`date` ='".$params['date']."' AND
						".$shift_time_start."  BETWEEN `transfer_map_vehicle_driver`.`shift_time_from` AND `transfer_map_vehicle_driver`.`shift_time_to`
				";
		$data =$this->db->query($query_vh)->result_array();
		// echo $this->db->last_query();exit;
	
		$count = count($data);

			//debug($count);exit;
			if($count<1){
				$response = json_encode ( array (
				'status' => true
		        ) );
			}else{
				$response = json_encode ( array (
				'result' => false 
	        	) );
			}
		echo $response;
		// exit ();
		// return json_encode($response);
			 
	}

	public function get_mappping_vehicle($id){
		$data=$this->transfers_model->get_mappping_vehicle($id);
		echo json_encode ( array (
				'result' => $data[0] 
		) );
		exit ();
	}

	public function delete_mapping_vehicle($id,$map_id) {
		$this->transfers_model->delete_mapping_vehicle( $map_id );
		redirect ( 'transfers/add_transfer/'.$id.'/vehicle', 'refresh' );
	}

	// public function hoursToMinutes($hours)
	// 	{
	// 		$minutes = 0;
	// 	    if (strpos($hours, ':') !== false)
	// 	    {
	// 	        list($hours, $minutes) = explode(':', $hours,2);
	// 	    }

	// 	    return $hours * 60 + $minutes;
	// 	}
//Get Source and destinataion airport list...........@neha

	 public function get_airport_loaction_suggestions(){
            $term = trim(strip_tags($this->input->get('term'))); 
            $rsa1 = $this->transfers_model->getAirportcodelist($term);
            
if(count($rsa1)!=0){
         for ($i=0; $i < count($rsa1); $i++) {   
         $country_name=$this->transfers_model->getcountrylist($rsa1[$i]->country); 
           $country_name=$country_name[0]->name;
                          $data['label']  = $rsa1[$i]->destination." (".$rsa1[$i]->city_code.")         ".$country_name ;    
                          $data['value']  = $rsa1[$i]->destination.' ('.$rsa1[$i]->city_code.')';
                          $data['id']  = $rsa1[$i]->origin;
                      $results[]=$data;
                    }     
            echo json_encode($results);
        }else{
                $results=array("label"=>"no records");
            echo json_encode($results); 
            }
        
    }


  public function cancellation_policy($id = 0 , $action = '')
  {
    if($id)
    {
      if ($action == 'delete') 
      {
        $this->custom_db->delete_record('transfer_cancellation',array('id'=>$id));
        redirect('transfers/cancellation_policy');
      }
      $page_data['tc_data'] =  $this->custom_db->single_table_records('transfer_cancellation','*',array('id'=>$id))['data'][0];
       $page_data['tc_data']['charge_details'] = $this->transfers_model->get_transfer_cancel_charge( $id );
    }
    $t_c = $this->input->post();
    $seasonality_from1 = explode('/', $t_c['seasonality_from']);
    $seasonality_from = $seasonality_from1[2].'-'.$seasonality_from1[1].'-'.$seasonality_from1[0];
    $seasonality_to1 = explode('/', $t_c['seasonality_to']);
    $seasonality_to = $seasonality_to1[2].'-'.$seasonality_to1[1].'-'.$seasonality_to1[0];     
    // debug($t_c);exit;
    if($t_c)
    {
      if($t_c['id'])
      {
        $data=array(
          'v_id'=>$t_c['transfer_id'],
          'policy_name'=>$t_c['policy_name'],
          'start_date'=>$seasonality_from,
          'expiry_date'=>$seasonality_to,
          // 'amount'=>$t_c['amount'],
          // 'charge_type'=>$t_c['charge_type'],
          'description'=>$t_c['description']
          );
        $this->custom_db->update_record('transfer_cancellation',$data,array('id'=>$t_c['id']));
        $this->custom_db->delete_record('transfer_cancellation_price',array('cancel_id'=>$t_c['id']));
        // debug($t_c['dates']);exit;
        foreach ($t_c['dates'] as $key => $value) {
      	$data_price_details=array(
          'cancel_id'=>$t_c['id'],
          'charge_type' => $value['charge_type'],
          'amount' => $value['amount'],
          'no_of_days' => $value['no_days']
          );
      	// debug($data_price_details);exit;
      	$this->custom_db->insert_record('transfer_cancellation_price',$data_price_details);
      	// echo $this->db->last_query();exit;
      	}

      }
      else
      {
        $data=array(
          'v_id'=>$t_c['transfer_id'],
          'policy_name'=>$t_c['policy_name'],
          'start_date'=>$seasonality_from,
          'expiry_date'=>$seasonality_to,
          'created_by'=> $this->entity_user_id,
          'user_type'=> 1,
          // 'charge_type'=>$t_c['charge_type'],
          'description'=>$t_c['description']
          );
      

      $this->custom_db->delete_record('transfer_cancellation',array('policy_name'=>$t_c['policy_name'],'v_id'=>$t_c['transfer_id']));
    
      $cancel_id = $this->custom_db->insert_record('transfer_cancellation',$data);
      // debug($cancel_id['insert_id']);exit;
      foreach ($t_c['dates'] as $key => $value) {
      	$data_price=array(
          'cancel_id'=>$cancel_id['insert_id'],
          'charge_type' => $value['charge_type'],
          'amount' => $value['amount'],
          'no_of_days' => $value['no_days']
          );
      	// debug($data_price);exit;
      	$this->custom_db->insert_record('transfer_cancellation_price',$data_price);
      	// echo $this->db->last_query();exit;
      	}

      }

      redirect('transfers/cancellation_policy');     
    }
    // debug($this->db->last_query()); exit;
    $page_data['transferdata'] =  $this->custom_db->single_table_records('transfer_cancellation','*',array('user_type'=>1),0,100000000,array('id'=>'desc'))['data'];
    $i=0;
	foreach($page_data['transferdata'] as $val)
	{

		$cancel_id = $val['id'];
		// debug($page_data['transferdata']);exit;
		$page_data['transferdata'][$i]['charge_details'] = $this->transfers_model->get_transfer_cancel_charge( $cancel_id );
		$i++;
	}
    // debug($page_data['transferdata']);exit;

    //$page_data=array();
    $this->template->view('transfers/cancellation_policy',$page_data);
  }
  function check_seasonality()
  {
  	$tran_id = $this->input->post('tran_id');
  	$from = $this->input->post('seasonality_from');
  	$seasonality_from1 = explode('/', $from);
    $seasonality_from = $seasonality_from1[2].'-'.$seasonality_from1[1].'-'.$seasonality_from1[0];
  	 $seasonality_details =  $this->transfers_model->check_seasonality_details($tran_id ,$seasonality_from);
  	 if(count($seasonality_details)>0)
  	 {
  	 	echo "Already policy for this period";
  	 }
  	 else
  	 {
  	 	echo 1;
  	 }
  }
  function check_seasonality_detls()
  {
  	$tran_id = $this->input->post('tran_id');
  	$from = $this->input->post('seasonality_from');
  	$to = $this->input->post('seasonality_to');
  	$seasonality_from1 = explode('/', $from);
    $seasonality_from = $seasonality_from1[2].'-'.$seasonality_from1[1].'-'.$seasonality_from1[0];
    $seasonality_to1 = explode('/', $to);
    $seasonality_to = $seasonality_to1[2].'-'.$seasonality_to1[1].'-'.$seasonality_to1[0];
  	 $seasonality_details =  $this->transfers_model->check_seasonality_details($tran_id ,$seasonality_from,$seasonality_to);
  	 if($seasonality_details)
  	 {
  	 	echo "Already policy for this period";
  	 }
  	 else
  	 {
  	 	echo 1;
  	 }
  }
  function hoursToMinutes($hours) 
{ 
    $minutes = 0; 
    if (strpos($hours, ':') !== false) 
    { 
        // Split hours and minutes. 
        list($hours, $minutes) = explode(':', $hours); 
    } 
    return $hours * 60 + $minutes; 
} 
function get_transfer_city_list($country_code='') {
        // $this->load->model('transfers_model');
        // $term = $this->input->get('term'); //retrieve the search term that autocomplete sends
        // $term = trim(strip_tags($term));
        // $data_list = $this->transfers_model->get_transfer_city_list($term);
        // if (valid_array($data_list) == false) {
        //     $data_list = $this->transfers_model->get_transfer_city_list('');
        // }
        // $data = array();
        // $result=[];
        // foreach ($data_list as $city_list) {
        //     $data['label'] = $city_list['city_name'] . ', ' . $city_list['country_name'] . '';
        //     $data['value'] = hotel_suggestion_value($city_list['city_name'], $city_list['country_name']);
        //     $data['id'] = $city_list['origin'];
        //     if (empty($city_list['top_destination']) == false) {
        //         $data['category'] = 'Top cities';
        //         $data['type'] = 'Top cities';
        //     } else {
        //         $data['category'] = 'Search Results';
        //         $data['type'] = 'Search Results';
        //     }
        //     if (intval($city_list['cache_hotels_count']) > 0) {
        //         $data['count'] = $city_list['cache_hotels_count'];
        //     } else {
        //         $data['count'] = 0;
        //     }
        //     $result[] = $data;
        // }
        // echo json_encode($result);

		$result = array();
        $this->load->model('transfers_model');
        $term = $this->input->get('term'); //retrieve the search term that autocomplete sends
        // $countrycode = $this->input->post('country_code'); 
        $term = trim(strip_tags($term));
        
        $airport_data_list = $this->transfers_model->get_airport_list($term,$country_code)->result();

        // debug($airport_data_list);exit();

        if (valid_array($airport_data_list) == false) {
            $airport_data_list = $this->transfers_model->get_airport_list('$term',$country_code)->result();
        }

        foreach ($airport_data_list as $airport) {
            $airport_result['label'] = $airport->airport_name . ' (' . $airport->airport_city . ')';
            $airport_result['id'] = $airport->airport_code;

            $airport_result['transfer_type'] = "ProductTransferTerminal";

            $airport_result ['category'] = '';
            $airport_result ['country_code'] = $airport->CountryCode;
            $airport_result ['city'] = $airport->airport_city;
            $airport_result ['origin'] = $airport->origin;
            $airport_result ['type'] = '';
            array_push($result, $airport_result);
        }
        $data_list = $this->transfers_model->get_hotels_list($term,$country_code)->result();
// debug($data_list);exit();
        
        if (valid_array($data_list) == false) {
            $data_list = $this->transfers_model->get_hotels_list('',$country_code)->result();
        }

        foreach ($data_list as $hotel) {
            $transfer_result['label'] = $hotel->hotel_name . ' (' . $hotel->hotel_city . ')';
            $transfer_result['id'] = $hotel->hotel_code;

            $transfer_result['transfer_type'] = "ProductTransferHotel";

            $transfer_result['category'] = '';
            $transfer_result['type'] = '';
            $airport_result ['country_code'] = $hotel->country_code;
            $airport_result ['city'] = $hotel->hotel_city;
            $airport_result ['origin'] = $hotel->origin;
            array_push($result, $transfer_result);
        }
// debug($result);exit;
        echo json_encode($result);
        // $this->output_compressed_data($result);
        
    }

    public function view_price_category() {
      // error_reporting(E_ALL);
    $data ['price_category_data'] = $this->transfers_model->price_category_data ()->result ();
    $tours_country_name = $this->transfers_model->tours_country_name();
     // debug($tours_country_name);exit;
      foreach ($tours_country_name as $key => $value) {
       $country_name[$value['id']]=$value['name'];
          }
       $data['tours_country_name']=$country_name;
       //debug($data['country_name']);exit;
    // print_r($data['package_view_data']); die();
      // debug($data);exit;
    $this->template->view ('transfers/view_price_category', $data );
  }

    public function add_price_category($id = '') {
      //error_reporting(E_All);
      $data ['tours_continent'] = $this->transfers_model->get_tours_continent();
    if ($id != '') {
      $data ['id']=$id;

      $data ['pack_data'] = $this->transfers_model->get_price_category_id ( $id );
      $contient=$data['pack_data'][0]->contient;//exit;
      $contry=$data['pack_data'][0]->country;
      //debug($contient);
      //$tours_continent = $this->Activity_Model->country_id($contry);
      $tours_continent = $this->transfers_model->ajax_tours_continent($contient);
      $data['tours_continent_sel']=$tours_continent;
      //debug($this->db->last_query());exit;
      //debug($data['contry']);exit;
      $this->template->view ( 'transfers/add_price_category', $data );
    } else {
      $this->template->view ( 'transfers/add_price_category',$data );
    }
  }


    public function save_price_category() {
      //error_reporting(E_ALL);
      $data = $this->input->post();
      //debug($data);exit;
    $pack_id = $this->input->post ( 'activity_types_id' );
    $tours_continent = $this->input->post ( 'tours_continent' );
    $package_name = $this->input->post ( 'name' );
    $tours_country = $data['tours_country'];
    $tours_country      = implode(',',$tours_country);
    $domain_list_fk = get_domain_auth_id ();
    // echo "<pre>";print_r($domain_list_fk);exit;
    $price_data=array(
      'price_category_name' => $package_name, 
      'contient' =>$tours_continent , 
      'country' => $tours_country,
      'created_by_id' => $this->entity_user_id,
      'user_type' => 1, 
      'domain_list_fk' =>$domain_list_fk 
    );
    //debug($pack_id);exit;
    if($pack_id>0)
    {
      $price_cat = $this->transfers_model->update_price_cat($price_data,$pack_id);
    }
    else
    {
      $price_cat = $this->transfers_model->add_price_cat($price_data);

    }
    //debug($this->db->last_query());exit;

    if($price_cat)
    {
           // $this->session->set_flashdata(array('message' => 'UL0014', 'type' => SUCCESS_MESSAGE));
    	$this->session->set_flashdata('message', UL0014);
          redirect ( 'transfers/view_price_category' );
      }
      else
      {
        // $this->session->set_flashdata(array('message' => 'UL0098', 'type' => ERROR_MESSAGE));
        $this->session->set_flashdata('message', UL0098);
      }
     
  

    
  }
  function manage_transfers_details()
  {
    $operation = $this->input->post("operation");
    $checkval=$this->input->post("checkval");

    if(!empty($checkval))
    {
      for($i=0;$i<sizeof($checkval);$i++)
      {
      $checkval1=$checkval[$i];
      
        switch ($operation) {
          case 'deactivate' :
          $status = 0;
          $this->transfers_model->update_transfer_status ( $checkval1, $status ); 
          $this->session->set_flashdata('message', UL0013);
          break;
          case 'activate' :
          $status = 1;
          $this->transfers_model->update_transfer_status ( $checkval1, $status );
          $this->session->set_flashdata('message', UL0013);
          break;
          case 'delete' :
          $this->transfers_model->delete_transfer($checkval1);
          $this->session->set_flashdata('message', UL0013);
          break;
              
        }
      }
    }
      
    // $this->template->view('voucher/visa_voucher', $page_data);
  }
  public function activation_transfers($id,$status,$theme_tbl,$redrct_page,$table_id_name) {
      $return = $this->transfers_model->record_activation($theme_tbl,$id,$status,$table_id_name);
      if($return){redirect('transfers/'.$redrct_page);} 
      else { echo $return;} 
    }
  function manage_transfers_all_details()
  {
    $operation = $this->input->post("operation");
    $checkval=$this->input->post("checkval");
    $theme_tbl=$this->input->post("theme_tbl");
    $table_id_name=$this->input->post("id");
    // debug($operation);debug($checkval);exit;
    if(!empty($checkval))
    {
      for($i=0;$i<sizeof($checkval);$i++)
      {
      $checkval1=$checkval[$i];
      
        switch ($operation) {
			case 'deactivate' :
			$status = 0;
			$this->transfers_model->record_activation($theme_tbl,$checkval1,$status,$table_id_name);
			// echo $this->db->last_query();exit;
			$this->session->set_flashdata('message', UL0013);
			break;
			case 'activate' :
			$status = 1;
			$this->transfers_model->record_activation($theme_tbl,$checkval1,$status,$table_id_name);
			$this->session->set_flashdata('message', UL0013);
			break;
			case 'delete' :
			$query = "delete from ".$theme_tbl." where ".$table_id_name."='$checkval1'";              
			$result = $this->db->query($query);              
			$this->session->set_flashdata('message', UL0099);
			break;
              
        }
      }
    }
  }
  function manage_supplier_approval()
  {
    $approve_status = $this->input->post("approve_status");
    $transfer_id=$this->input->post("transfer_id");
    $this->transfers_model->update_approval_status($approve_status,$transfer_id);
			// echo $this->db->last_query();exit;
	$this->session->set_flashdata('message', UL0013);
    
  }
  function get_driver_shift_days($id){
  	 $data['shift_days'] = $this->custom_db->single_table_records('transfer_driver_info','driver_name,driver_shift_days',array('id'=>$id))['data'][0];
  	 echo $this->template->isolated_view('transfers/driver_shift_days',$data); 
  }
  //Nationality Master

  function nationality_region()
  {
     $nationality_region = $this->transfers_model->nationality_region();
     // debug($nationality_region); exit;    
     $page_data['nationality_region'] = $nationality_region;
     $this->template->view('transfers/nationality/nationality_region',$page_data);
  }
  function region_save()
  {
     $data = $this->input->post();
      $tour_region   = sql_injection($data['tour_region']);
      $check_availibility = $this->transfers_model->check_region_exist_all($tour_region);
      if(!$check_availibility)
      {
        $query = "insert into all_nationality_region set name='$tour_region', status=1, module='transfers', created_by=".$this->entity_user_id." ";        
            //echo $query; //exit;
        $return = $this->transfers_model->query_run($query);
        if($return)
          {   $this->session->set_flashdata('message', UL0014);
        redirect('transfers/nationality_region'); }
        else
          { echo $return; exit; } 
      }
      else
      {
       $this->session->set_flashdata('region_msg','Region is already exist');
       redirect('transfers/nationality_region');
     }

  }
  public function activation_nationality_region($id,$status) {
    $return = $this->transfers_model->record_activation('all_nationality_region',$id,$status);
    // debug($return);exit();
    if($return){redirect('transfers/nationality_region');} 
    else { echo $return;} 
  }
  public function edit_nationality_region($id)
  {
     $region_details = $this->transfers_model->table_record_details('all_nationality_region',$id);

      $page_data['region_details'] = $region_details;
        // debug($page_data); exit;
      $this->template->view('transfers/nationality/edit_nationality_region',$page_data);
  }


  public function edit_nationality_region_save() {
    $data = $this->input->post();
      //debug($data); exit;
    $id             = $data['id'];
    $tour_region  = sql_injection($data['tour_region']);
    $query = "update all_nationality_region set name='$tour_region' where id='$id'";        
          //echo $query; //exit;
    $return = $this->transfers_model->query_run($query);
    if($return)
      {   
      $this->session->set_flashdata('message', UL0013);
      redirect('transfers/edit_nationality_region/'.$id); }
    else
      { echo $return; exit; }              
  }


  public function delete_nationality_region($id) {
    $return = $this->transfers_model->record_delete('all_nationality_region',$id);
    if($return){
      $this->session->set_flashdata('message', UL0099);
      redirect('transfers/nationality_region');} 
    else { echo $return;} 
  }
  public function delete_nationality_countries($id) {
    $return = $this->transfers_model->record_delete_countries('all_nationality_country',$id);
    if($return){
      $this->session->set_flashdata('message', UL0099);
      redirect('transfers/view_notionality_country');} 
    else { echo $return;} 
  }
  public function view_notionality_country() 
    {
        
      $page_data ['notionality_country'] = $this->transfers_model->get_nationalityCountryList();
       // debug($page_data);exit;
      $this->template->view ('transfers/nationality/view_notionality_country', $page_data );
    }


  public function nationality_country($id = '') {
      //error_reporting(E_All);
      $page_data ['nationality_regions'] = $this->transfers_model->get_nationality_regions();
      $page_data['country_list']=$this->transfers_model->get_hb_country_list();
    $page_data ['edit_notionality_country']='';
      // debug($data ['tours_continent']);exit();
      if ($id != '') 
      {
        $page_data ['id']=$id;
        $page_data ['edit_notionality_country'] = $this->transfers_model->get_nationalityCountryList($id);
        // debug($page_data ['edit_notionality_country']);exit;
        $this->template->view ( 'transfers/nationality/notionality_country', $page_data );
      } else {
        $this->template->view ( 'transfers/nationality/notionality_country',$page_data );
      }
    }

 
    public function save_nationalityCountries() 
    {
     
         $data = $this->input->post();
        
         $pack_id=$data['pack_id'];
         $hb_country_list=$this->transfers_model->get_hb_country_list();

         $except_countryIds =array();
         $except_countryCodes =array();
         $except_countryNames  =array();

         $include_countryIds =array();
         $include_countryCodes =array();
         $include_countryNames =array();
         // debug($data);exit;
         $raw_except_countries=[];
         foreach ($hb_country_list as $value)
         {
            foreach ($data['tours_country'] as $key => $val)
            {
              if($val == $value['origin']) 
              {               
                array_push($include_countryIds,$value['origin']);
                array_push($include_countryCodes,$value['country_code']);
                array_push($include_countryNames ,$value['country_name']);                 
              }
              else
              { 
                $raw_except_countries [$value['origin']] ['origin']=$value['origin'];
                $raw_except_countries [$value['origin']] ['country_code']=$value['country_code'];
                $raw_except_countries [$value['origin']] ['country_name']=$value['country_name'];

              }
            }
         }

         if(count($data['tours_country']>0)){
              foreach ($data['tours_country'] as  $value) {
                if(isset($raw_except_countries [$value])){
                  unset($raw_except_countries [$value]);
                }
              }
         }
        $except_countryIds   = array_column($raw_except_countries, 'origin');
        $except_countryCodes = array_column($raw_except_countries, 'country_code');
        $except_countryNames = array_column($raw_except_countries, 'country_name');        

         
     $except_countryIds      = implode(',',array_unique($except_countryIds));
     $except_countryCodes      = implode(',',array_unique($except_countryCodes));
     $except_countryNames      = implode(',',array_unique($except_countryNames));   
 
       $include_countryIds      = implode(',',array_unique($include_countryIds));
     $include_countryCodes      = implode(',',array_unique($include_countryCodes));
     $include_countryNames      = implode(',',array_unique($include_countryNames));
    
 
 
       $tours_continent = $this->input->post ( 'tours_continent' );
       $package_name = $this->input->post ( 'name' );
 
     
       $data_ins=array(
           'name' => $package_name,
           'module' => 'transfers', 
           'continent' =>$tours_continent, 
           'except_countryIds' => $except_countryIds, 
           'except_countryCodes' => $except_countryCodes, 
           'except_countryNames' =>$except_countryNames,
           'include_countryIds' => $include_countryIds, 
           'include_countryCodes' => $include_countryCodes, 
           'include_countryNames' =>$include_countryNames,
           'created_by' =>$this->entity_user_id,      
           'created_datetime'=>date('Y-m-d H:m:s'),
           'status'=>1
       );
       

       // debug($data_ins);exit();
       $repeat_nationality = $this->transfers_model->check_nationality_duplicate($tours_continent,$package_name);
       if(empty($repeat_nationality)){
       if($pack_id>0)
       {
         $price_cat = $this->transfers_model->update_price_cat($data_ins,$pack_id);
       }
       else
       {
         $price_cat = $this->transfers_model->add_price_cat($data_ins);

       }
      // debug($this->db->last_query());exit;

       if($price_cat)
       {
           $this->session->set_flashdata(array('message' => 'UL0014', 'type' => SUCCESS_MESSAGE));
           redirect ( 'transfers/view_notionality_country' );
       }
       else
       {
           $this->session->set_flashdata(array('message' => 'UL0098', 'type' => ERROR_MESSAGE));
       }
     }else{
      $this->session->set_flashdata(array('message' => 'UL0098', 'type' => ERROR_MESSAGE));redirect ( 'transfers/view_notionality_country' );
     }
 }
// Nationality Masters Code ends
//CRS booking cancellation start
function pre_cancellation($app_reference, $booking_source,$status,$module='')
	{
		if (empty($app_reference) == false && empty($booking_source) == false) {
			$page_data = array();
			$booking_details = $this->transfers_model->get_booking_details_transfer($app_reference, $booking_source);
			// debug($booking_details);exit;
			if ($booking_details['status'] == SUCCESS_STATUS) {
				$this->load->library('booking_data_formatter');
				//Assemble Booking Data
				$assembled_booking_details = $this->booking_data_formatter->format_transfer_booking_data_crs($booking_details,$this->current_module);
				// debug($assembled_booking_details);exit;
				$page_data['data'] = $assembled_booking_details['data'];
				$page_data['module'] = $module;
				$this->template->view('transfers/pre_cancellation', $page_data);
			} else {
				redirect('security/log_event?event=Invalid Details');
			}
		} else {
			redirect('security/log_event?event=Invalid Details');
		}
	}
	function cancel_booking($app_reference, $booking_source)
	{
		if(empty($app_reference) == false) {
			$get_params = $this->input->get();
			$master_booking_details = $this->transfers_model->get_booking_details_transfer($app_reference, $booking_source);
			if ($master_booking_details['status'] == SUCCESS_STATUS) {
				$this->load->library('booking_data_formatter');
				$master_booking_details = $this->booking_data_formatter->format_transfer_booking_data_crs($master_booking_details, 'b2b');
				

				$master_booking_details = $master_booking_details['data']['booking_details'][0];
				load_transfer_lib($booking_source);
				$cancellation_details = $this->transfer_lib->cancel_booking($master_booking_details,$get_params);//Invoke Cancellation Methods

				if($cancellation_details['status'] == false) {
					$query_string = '?error_msg='.$cancellation_details['msg'];
				} else {
					$query_string = '';
				}
				redirect('transfers/cancellation_details/'.$app_reference.'/'.$booking_source.$query_string);
			} else {
				redirect('security/log_event?event=Invalid Details');
			}
		} else {
			redirect('security/log_event?event=Invalid Details');
		}
	}
	function cancellation_details($app_reference, $booking_source)
	{
		if (empty($app_reference) == false && empty($booking_source) == false) {
			$master_booking_details = $GLOBALS['CI']->transfers_model->get_booking_details_transfer($app_reference, $booking_source);
			if ($master_booking_details['status'] == SUCCESS_STATUS) {
				$page_data = array();
				$this->load->library('booking_data_formatter');
				$master_booking_details = $this->booking_data_formatter->format_transfer_booking_data_crs($master_booking_details, 'b2c');
				$page_data['data'] = $master_booking_details['data'];
				$this->template->view('transfers/cancellation_details', $page_data);
			} else {
				redirect('security/log_event?event=Invalid Details');
			}
		} else {
			redirect('security/log_event?event=Invalid Details');
		}
	}
}