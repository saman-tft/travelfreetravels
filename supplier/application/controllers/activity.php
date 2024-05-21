<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 *
 * @package Provab - Provab Application
 * @subpackage Travel Portal
 * @author Balu A<balu.provab@gmail.com> on 01-06-2015
 * @version V2
 */
class Activity extends CI_Controller {
	public function __construct() {
		parent::__construct ();
		$this->load->model ( 'Activity_Model' );
	}
	public function view_packages_types() {
		$data ['package_view_data'] = $this->Activity_Model->package_view_data_types ()->result ();
		$package_excursion_type = $this->Activity_Model->get_excursion_type();
		foreach ($package_excursion_type as $key => $value) {
			$data['activity_types_id'][] = $value->activity_types_id;
		}
		// debug($data['activity_types_id']); die();
		$this->template->view ('activity/view_package_types', $data );
	}
	public function delete_package_type($id) {
		$this->Activity_Model->delete_package_type ( $id );
		redirect ( 'activity/view_packages_types' );
	}
	
	public function delete_traveller_img($pack_id,$img_id) {
		$this->Activity_Model->delete_traveller_img ( $pack_id,$img_id );
		redirect ( 'activity/images/'.$img_id.'/w' );
	}
	public function delete_package($id) {
		$this->Activity_Model->delete_package ( $id );
		$this->session->set_flashdata('message', UL0103);
		redirect ( 'activity/view_with_price_supplier' );
	}
	public function add_package_type($id = '') {
		if ($id != '') {
			$data ['id']=$id;
			$data ['pack_data'] = $this->Activity_Model->get_pack_id ( $id );
			$this->template->view ( 'activity/add_package_type', $data );
		} else {
			$this->template->view ( 'activity/add_package_type',$data );
		}
	}
	public function save_packages_type() {
		$pack_id = $this->input->post ( 'activity_types_id' );
		$package_name = $this->input->post ( 'name' );
		$domain_list_fk = get_domain_auth_id ();
		// echo "<pre>";print_r($domain_list_fk);exit;

		$this->db->where("activity_types_name",$package_name );
		$qur = $this->db->get ("activity_types");
		//echo $this->db->last_query();exit;
		$count=$qur->num_rows();
 


		if ($pack_id > 0) 
		{
			
			if($count<1)
			{
				$add_package_data = array (
					'activity_types_name' => $package_name,
					'domain_list_fk' => $domain_list_fk
				);

		  		$this->Activity_Model->update_package_type ( $add_package_data, $pack_id );
		  		 //$this->session->set_flashdata(array('message' => 'UL0014', 'type' => SUCCESS_MESSAGE));
		  		 $this->session->set_flashdata('message', UL0014);
			}
			else
			{
				//$this->session->set_flashdata(array('message' => 'UL0098', 'type' => ERROR_MESSAGE));
				$this->session->set_flashdata('message', UL0098);
			}
		} 
		else 
		{ 
			if($count<1)
			{
				$add_package_data = array (
					'activity_types_name' => $package_name,
					'domain_list_fk' => $domain_list_fk,
					'status' => 1,
					'created_by' => $this->entity_user_id  
				);

				$this->db->insert ( "activity_types", $add_package_data );
				//$this->session->set_flashdata(array('message' => 'UL0014', 'type' => SUCCESS_MESSAGE));
				$this->session->set_flashdata('message', UL0014);
			} 
			else
			{
				//$this->session->set_flashdata(array('message' => 'UL0098', 'type' => ERROR_MESSAGE));
				$this->session->set_flashdata('message', UL0098);
			}			
		}

		redirect ( 'activity/view_packages_types' );
	}
	public function add_with_price() {
		$status = 1;
		$data ['package_type_data'] = $this->Activity_Model->package_types($status)->result ();
		foreach ($data ["package_type_data"] as $key => $value) {
			$status = 1;
			$type_id = $value->activity_types_id;
			$data['sub_category'][] = $this->custom_db->single_table_records('activity_sub_category','*',array('activity_type_id'=>$type_id,'status'=>1))['data'];
		}
		// echo $this->db->last_query();exit;
	//	debug($data['sub_category']);exit;
		$data ["country"] = $this->Activity_Model->get_countries ();
		
		$currency_list  = $this->Activity_Model->get_countries_currency();

		$data ["themes"] = $this->custom_db->single_table_records('activity_subtheme','*',array('status'=>1))['data'];
		
		foreach ($data ["themes"] as $key => $value) {
			$status = 1;
			$theme_id = $value['id'];
			$data['sub_themes'][] = $this->custom_db->single_table_records('sub_theme_activity','*',array('activity_theme_id'=>$theme_id,'status'=>1))['data'];
		}
		//debug($data ["themes"]);die;
		$data ["amenities"] = $this->custom_db->single_table_records('activity_amenties','*',array('status'=>1))['data'];
		$data ['nationality_group'] = $this->Activity_Model->nationality_group_data();
//	 debug($data['nationality_group']);exit;
  	    $condition = '1';
		$health_instructions = $this->Activity_Model->health_instructions($condition ); 
        $data['health_instructions'] = $health_instructions;
		$data ["currency"]=$currency_list;
		$this->template->view ( 'activity/add_package', $data );
	}
	public function get_countries() {
		$this->db->limit ( 1000 );
		$this->db->order_by ( "name", "asc" );
		$qur = $this->db->get ( "country" );
		return $qur->result ();
	}
	/*public function add_package_new() {

		debug($_POST);exit;
		$photo = $_FILES;
		$cpt1 = count ( $_FILES ['photo'] ['name'] );
		if ($_FILES ['photo'] ['name'] != '') {
			$_FILES ['photo'] ['name'] = $photo ['photo'] ['name'];
			$_FILES ['photo'] ['type'] = $photo ['photo'] ['type'];
			$_FILES ['photo'] ['tmp_name'] = $photo ['photo'] ['tmp_name'];
			$_FILES ['photo'] ['error'] = $photo ['photo'] ['error'];
			$_FILES ['photo'] ['size'] = $photo ['photo'] ['size'];
			
			move_uploaded_file ( $_FILES ['photo'] ['tmp_name'], $this->template->domain_uploads_packages ( $_FILES ['photo'] ['name'] ) );
			$photo = $_FILES ['photo'] ['name'];
		}
		
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
		$top_destination = $this->input->post ( 'top_destination' );
		$newpackage = array (
				'package_type' => $disn,
				'package_name' => $name,
				'supplier_id' => $sup_id,
				'price_includes' => $pricee,
				'package_country' => $country,
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
				'top_destination' => $top_destination,
				'domain_list_fk' => $domain_list_fk 
		);
		// echo "<pre>"; print_r($newpackage);exit;
		// print_r($country);exit;
		$package = $this->Activity_Model->add_new_package ( $newpackage );
		if ($package != 0) {
			$packcode = array (
					'package_code' => 'SKY' . $package 
			);
			$this->Activity_Model->update_code_package ( $packcode, $package );
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
				move_uploaded_file ( $_FILES ['image'] ['tmp_name'], $this->template->domain_uploads_packages ( $itenary_imge ) );
				$image = $itenary_imge;
				$itinerary = array (
						'itinerary_description' => $itinerarydesc [$i],
						'day' => $days [$i],
						'place' => $place [$i],
						'itinerary_image' => $image,
						'package_id' => $package 
				);
				$itineraryid = $this->Activity_Model->itinerary ( $itinerary );
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
				$queans = $this->Activity_Model->que_ans ( $qus_ans );
			}
		}
		$pricingpolicy = array (
				'package_id' => $package,
				'price_includes' => $includes,
				'price_excludes' => $excludes 
		);
		$policy = $this->Activity_Model->pricing_policy ( $pricingpolicy );
		$cancellation = array (
				'package_id' => $package,
				'cancellation_advance' => $advance,
				'cancellation_penality' => $penality 
		);
		$cancel = $this->Activity_Model->cancellation_penality ( $cancellation );
		if (! empty ( $value )) {
			$deals = array (
					'value' => $value,
					'discount' => $discount,
					'you_save' => $save,
					'seats' => $seats,
					'time' => $time,
					'package_id' => $package 
			);
			$deall = $this->Activity_Model->deals ( $deals );
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
				$in = $this->Activity_Model->incl ( $incl );
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
				move_uploaded_file ( $_FILES ['traveller'] ['tmp_name'], $this->template->domain_uploads_packages ( $name_of_traveller_img ) );
				$traveller_img = $name_of_traveller_img;
				$sup_id = $this->entity_user_id;
				$traveller = array (
						'traveller_image' => $traveller_img,
						'user_id' => $sup_id,
						'package_id' => $package 
				);
				$travel = $this->Activity_Model->travel_images ( $traveller );
			}
		}
		redirect ( 'supplier/view_with_price' );
	}*/
	public function add_package_new($excursion_id='') {
		// debug($_POST);exit;
		// $gallery =  $this->input->post ( 'input_hidden_field' ) ;
		$t_c = $this->input->post();
		$gallery=json_decode($t_c['input_hidden_field'][0],true);
		$path = realpath ( '../extras' ).'/custom/'.CURRENT_DOMAIN_KEY.'/uploads/activity/';
		$photo_all = $_FILES;
		$gallery_images = array();
      if($gallery!= ''){
        foreach ($_FILES as $f_key => $f_value){ 
          if(isset($_FILES[$f_key]) && !empty($_FILES[$f_key]['name'][0])){
		 if(isset($gallery )){
          foreach ($gallery as $r_k => $r_v){
            foreach ($_FILES[$f_key]['name'] as $f_r_k => $f_r_v){
              if($_FILES[$f_key]['name'][$f_r_k]==$gallery[$r_k]){
                $_FILES ['photos'] ['name'] = $_FILES[$f_key]['name'][$f_r_k];
                $_FILES ['photos'] ['type'] = $_FILES[$f_key]['type'][$f_r_k];
                $_FILES ['photos'] ['tmp_name'] = $_FILES[$f_key]['tmp_name'][$f_r_k];
                $_FILES ['photos'] ['error'] = $_FILES[$f_key]['error'][$f_r_k];
                $_FILES ['photos'] ['size'] = $_FILES[$f_key]['size'][$f_r_k];
			
				$doc_name = time () .''. $_FILES ['photos'] ['name'];
            	$documents_name= $doc_name;
            	$gallery_images[]= $doc_name;
			// debug($photo_all);exit();
				// debug($_FILES ['photo'] ['tmp_name']);exit();
				// move_uploaded_file ( $_FILES ['photo'] ['tmp_name'], $this->template->domain_uploads_packages ( $_FILES ['photo'] ['name'] ) );
				// echo $this->template->domain_uploads_activity ($photo);exit;
				move_uploaded_file ( $_FILES ['photos'] ['tmp_name'], $path.$doc_name );
              }
            }
          }
          }
          }
          }
          }else{
          	$grly_images=$t_c['hidden_gallery'];
          	for($i=0;$i<count($grly_images);$i++)
          	{
          		$gallery_images[$i]= $grly_images[$i];
          	}
          	
          }
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
			// echo $this->template->domain_uploads_activity ($photo);exit;
			move_uploaded_file ( $_FILES ['photo'] ['tmp_name'], $path.$photo );
		}else{
			$photo = $t_c['hidephoto'];
		}
		$photo_ticket = $_FILES;
		$cpt3 = count ( $_FILES ['image'] ['name'] );
		if (!empty($_FILES ['image'] ['name'])) {
		if ($_FILES ['image'] ['name'] != '') {
			$_FILES ['image'] ['name'] = $photo_ticket ['image'] ['name'];
			$_FILES ['image'] ['type'] = $photo_ticket ['image'] ['type'];
			$_FILES ['image'] ['tmp_name'] = $photo_ticket ['image'] ['tmp_name'];
			$_FILES ['image'] ['error'] = $photo_ticket ['image'] ['error'];
			$_FILES ['image'] ['size'] = $photo_ticket ['image'] ['size'];
			
			$string_ticket = $_FILES ['image'] ['name'];
			$exe_ticket = explode(".",$string_ticket);
			$photo_ticket = date('dmYhis').'.'.$exe_ticket[1];
			// move_uploaded_file ( $_FILES ['photo'] ['tmp_name'], $this->template->domain_uploads_packages ( $_FILES ['photo'] ['name'] ) );
			// echo $this->template->domain_uploads_activity ($photo);exit;
			move_uploaded_file ( $_FILES ['image'] ['tmp_name'], $path.$photo_ticket );
		}
		}else{
			$photo_ticket = $t_c['offer_ticket_image'];
		}
		$doc_image=json_encode($gallery_images);
		// $doc_image=json_encode($t_c['input_hidden_field']);
		// $end_date = $this->input->post ( 'tour_expire_date' );
		$start_date = $this->input->post ( 'tour_start_date' );
		$publish_date = $this->input->post ( 'tour_publish_date' );
		$disn = $this->input->post ( 'disn' );
		$name = $this->input->post ( 'name' );
		$supplier_name = $this->input->post ( 'supplier_name' );
		$booking_availbale = $this->input->post ( 'booking_availbale' );
		$country = $this->input->post ( 'country' );
		$city = $this->input->post ( 'cityname_old' );
		$duration = $this->input->post ( 'duration' );
		$description = $this->input->post ( 'Description' );
		$location = $this->input->post ( 'cityname_old' );
		$address = $this->input->post ( 'activity_address' );
		$latitude = $this->input->post ( 'latitude' );
		$longitude = $this->input->post ( 'longitude' );
		$bok_no_day = $this->input->post ( 'bok_no_day' );
		$amenities_ary = $this->input->post ( 'amenities' );
		$penality = $this->input->post ( 'penality' );
		$amenities_ary = array_values(array_filter($amenities_ary));
		if(isset($amenities_ary) && !empty($amenities_ary)){
		$amenities = json_encode($amenities_ary);
		}
		$health_restrictn_ary = $this->input->post ( 'health_restrictn' );
		$health_restrictn_ary = array_values(array_filter($health_restrictn_ary));
		if(isset($health_restrictn_ary) && !empty($health_restrictn_ary)){
		$health_restrictn = json_encode($health_restrictn_ary);
		}
		$hlth_restrctn_detls_ary = $this->input->post ( 'hlth_restrctn_detls' );
		$hlth_restrctn_detls_ary = array_values(array_filter($hlth_restrctn_detls_ary));
		if(isset($hlth_restrctn_detls_ary) && !empty($hlth_restrctn_detls_ary)){
		$hlth_restrctn_detls = json_encode($hlth_restrctn_detls_ary);
		}
		$sharing_transport = $this->input->post ( 'sharing_transport' );
		$with_transport = $this->input->post ( 'with_transport' );
		$private_challenging = $this->input->post ( 'private_challenging' );
		$difficulty_easy = $this->input->post ( 'difficulty_easy' );
		$other_difficulty = $this->input->post ( 'other_difficulty' );
		$min_pax = $this->input->post ( 'min_pax' );
		$max_pax = $this->input->post ( 'max_pax' );
		$state = $this->input->post ( 'state' );
		$deal = $this->input->post ( 'deal' );
		$homepage = $this->input->post ( 'homepage' );
		$rating = $this->input->post ( 'rating' );
		$choose_theme = $this->input->post ( 'choose_theme' );
		$main_themes = $this->input->post ( 'choose_main_theme' );
		$theme_ids = $this->input->post ( 'theme_ids' );
		$with_price = $this->input->post ( 'with_price' );
		$day_plan = $this->input->post ( 'day_plan' );
		$half_day_type = $this->input->post ( 'half_day_type' );
		$activity_duration_hour = $this->input->post ( 'activity_duration_hour' );
		$activity_duration_min = $this->input->post ( 'activity_duration_min' );
		// $add_time = date("H:i", strtotime($this->input->post ( 'add_time' )));
		// $add_time_min = $this->hoursToMinutes($add_time);
		$add_time = array_values(array_filter($this->input->post ('add_time')));
		if(isset($add_time) && !empty($add_time)){
		$add_time_min = json_encode($add_time);
		}
		$pickup_time = array_values(array_filter($this->input->post ('pickup_time')));
		if(isset($pickup_time) && !empty($pickup_time)){
		$pickup_time_min = json_encode($pickup_time);
		}

		// $add_time_min = $this->input->post ( 'add_time' );
		// $pickup_time_min = $this->input->post ( 'pickup_time' );
		// $pickup_time_min = $this->hoursToMinutes($add_time);
		 // debug($add_time_min);exit;
    	$no_of_time_repeat = $this->input->post ( 'no_of_time_repeat' );
		// itinerary
		$itinerarydesc = $this->input->post ( 'desc' );
		$sub_category_desc_ary = $this->input->post ( 'sub_category_desc' );
		$sub_category_desc_ary = array_values(array_filter($sub_category_desc_ary));
		if(isset($sub_category_desc_ary) && !empty($sub_category_desc_ary)){
		$sub_category_desc = json_encode($sub_category_desc_ary);
		}
		$days = $this->input->post ( 'days' );
		$place = $this->input->post ( 'place' );
		$price = $this->input->post ( 'price' );
		// price includes
		$includes = $this->input->post ( 'includes' );
		$excludes = $this->input->post ( 'excludes' );
		// cancellation
		$advance = $this->input->post ( 'advance' );
		$penality = $this->input->post ( 'penality' );
		$contact_email = $this->input->post ( 'contact_email' );
		$contact_address = $this->input->post ( 'contact_address' );
		$exclusion_policy = $this->input->post ( 'exclusion_policy' );
		$inclusion_policy = $this->input->post ( 'inclusion_policy' );
		$infor_travellers = $this->input->post ( 'infor_travellers' );
		$refundable = $this->input->post ( 'refundable' );
		$offer_ticket = $this->input->post ( 'offer_ticket' );
		$ticket_description = $this->input->post ( 'ticket_description' );
		$adult_age = $this->input->post ( 'adult_age' );
		$adult_age_min = $this->input->post ( 'adult_age_min' );
		$adult_age_max = $this->input->post ( 'adult_age_max' );
		$infant_age = $this->input->post ( 'infant_age' );
		$infant_age_min = $this->input->post ( 'infant_age_min' );
		$infant_age_max = $this->input->post ( 'infant_age_max' );
		$child_age = $this->input->post ( 'child_age' );
		$child_age_min = $this->input->post ( 'child_age_min' );
		$child_age_max = $this->input->post ( 'child_age_max' );
		$senior_age = $this->input->post ( 'senior_age' );
		$senior_age_min = $this->input->post ( 'senior_age_min' );
		$senior_age_max = $this->input->post ( 'senior_age_max' );
		$traveller_country = $this->input->post ( 'traveller_country' );
		$traveller_cityname = $this->input->post ( 'traveller_cityname' );
		$traveller_pickup = $this->input->post ( 'traveller_pickup' );
		$meeting_instruction = $this->input->post ( 'meeting_instruction' );

		// question and answer
		$no_questions = $this->input->post ( 'no_of_questions' );
		$quest = $this->input->post ( 'quest' );
		$answer = $this->input->post ( 'answer' );
		$sup_id = $this->entity_user_id;
		$sup_type = $this->session->userdata ( 'sup_type' );
		// price
		$nationality_ary = $this->input->post ( 'nationality' );
		$nationality = $nationality_ary[0];
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

		$nationality=$this->input->post ( 'nationality' );
		$price=$this->input->post ( 'p_price' );
		$price_shift_from=date("H:i", strtotime($this->input->post ('price_shift_from')));
		$price_shift_to=date("H:i", strtotime($this->input->post ('price_shift_to')));
		$seasons_date_range=$this->input->post ( 'seasons_date_range' );
		$season_array = array_values(array_filter($seasons_date_range));
	//	debug($season_array);//exit;
		if(isset($season_array) && !empty($season_array)){
		$seasons_date = json_encode($season_array);
		}
		$start_time = array_values(array_filter($this->input->post ('start_time')));
		if(isset($start_time) && !empty($start_time)){
		$start_time_ary = json_encode($start_time);
		}
		$end_time = array_values(array_filter($this->input->post ('end_time')));
		if(isset($end_time) && !empty($end_time)){
		$end_time_ary = json_encode($end_time);
		}
		 //debug($driver_shift_from);
		$shift_from= $this->hoursToMinutes($price_shift_from);
		//debug($params['driver_shift_from']);exit;
		$shift_to = $this->hoursToMinutes($price_shift_to);

		$weekdays=$this->input->post ( 'driver_shift_days_1' );
		if(isset($weekdays) && !empty($weekdays)){
		$week_days = json_encode($weekdays);
		}else{
			$week_days = 'N';
		}
//debug($week_days);exit;
		$domain_list_fk = get_domain_auth_id ();
		$newpackage = array (
				'package_type' => $disn,
				'package_name' => ucwords($name),
				'supplier_name' => $supplier_name,
				'price_includes' => $pricee,
				'package_country' => $country,
				'start_date' => $start_date,
				/*'end_date' => $end_date,*/
				'weekdays' => $week_days,
				'package_city' => $city,
				'package_location' => $location,
				'package_description' => $description,
				'deals' => $deal,
				'duration' => 1,
				'image' => $photo,
				'gallery_image' => $doc_image,
				'no_que' => $no_questions,
				'home_page' => $homepage,
				'rating' => $rating,
				'main_themes' => $main_themes,
				'theme_types' => $choose_theme,
				'theme_ids' => $theme_ids,
				'booking_availbale' => $booking_availbale,
				'day_plan' => $day_plan,
				'half_day_type' => $half_day_type,
				'activity_duration_hour' => $activity_duration_hour,
				'activity_duration_min' => $activity_duration_min,
				'no_of_time_repeat' => $no_of_time_repeat,
				'add_time' => $add_time_min,
				'domain_list_fk' => $domain_list_fk,
				'publish_date' => $publish_date,
				'start_time' => $start_time_ary,
				'end_time' => $end_time_ary,
				'seasons_date'=>$seasons_date,
				'contact_email'=>$contact_email,
				'contact_address'=>$contact_address,
				'exclusion_policy'=>$exclusion_policy,
				'inclusion_policy'=>$inclusion_policy,
				'infor_travellers'=>$infor_travellers,
				'refundable'=>$refundable,
				'offer_ticket'=>$offer_ticket,
				'image_ticket'=>$photo_ticket,
				// 'penality'=>$penality,
				'ticket_description'=>$ticket_description,
				'address'=>$address,
				'latitude'=>$latitude,
				'longitude'=>$longitude,
				'pickup_time'=>$pickup_time_min,
				'bok_no_day'=>$bok_no_day,
				'amenities'=>$amenities,
				'min_pax'=>$min_pax,
				'max_pax'=>$max_pax,
				'adult_age'=>$adult_age,
				'adult_age_min'=>$adult_age_min,
				'adult_age_max'=>$adult_age_max,
				'infant_age'=>$infant_age,
				'infant_age_min'=>$infant_age_min,
				'infant_age_max'=>$infant_age_max,
				'child_age'=>$child_age,
				'child_age_min'=>$child_age_min,
				'child_age_max'=>$child_age_max,
				'senior_age'=>$senior_age,
				'senior_age_min'=>$senior_age_min,
				'senior_age_max'=>$senior_age_max,
				'traveller_country'=>$traveller_country,
				'traveller_cityname'=>$traveller_cityname,
				'traveller_pickup'=>$traveller_pickup,
				'meeting_instruction'=>$meeting_instruction,
				'private_challenging'=>$private_challenging,
				'with_transport'=>$with_transport,
				'sharing_transport'=>$sharing_transport,
				'health_restrictn'=>$health_restrictn,
				'hlth_restrctn_detls'=>$hlth_restrctn_detls,
				'difficulty_easy'=>$difficulty_easy,
				'other_difficulty'=>$other_difficulty,
				// 'cancellation_penality'=>$penality


		);
		// echo "<pre>"; debug($difficulty_easy);exit;
		if(!empty($excursion_id)){
			$package = $this->Activity_Model->update_excursion_package ($excursion_id, $newpackage );
		}else{
			$newpackage['supplier_id']= $sup_id;
		$package = $this->Activity_Model->add_new_package ( $newpackage );
		if ($package != 0) {
			$packcode = array (
					'package_code' => 'SKY' . $package 
			);
			$this->Activity_Model->update_code_package ( $packcode, $package );
		}
		}
		// echo $this->db->last_query();exit;
		// print_r($country);exit;
		// exit();
		

		$num_of_cntrct_durtn = count($season_array);
		if($booking_availbale=='D'){
		for($i=0;$i<$num_of_cntrct_durtn;$i++){
			$j = $i+1;
		$booking_date_range=$this->input->post ( 'booking_date_range_'.$j);
		$booking_date_ary = array_values(array_filter($booking_date_range));
		if(isset($booking_date_ary) && !empty($booking_date_ary)){
		$booking_dates = json_encode($booking_date_ary);
		}
		$available_dates_ary = array (
				'activity_id' => $package,
				'duration' => $season_array[$i],
				'activity_booking_dates' => $booking_dates
				);	
		if(!empty($excursion_id)){
			$available_range_id=$this->input->post ( 'available_range_id_'.$j);
			if($available_range_id!=''){
			$available_dates_ary = $this->Activity_Model->update_available_dates( $available_dates_ary,$available_range_id );
			}else{
				$available_dates_ary = $this->Activity_Model->add_available_dates( $available_dates_ary );
			}
		
		}else{

		$available_dates_ary = $this->Activity_Model->add_available_dates( $available_dates_ary );
		}
			}
		}else{
			for($i=0;$i<$num_of_cntrct_durtn;$i++){
			$j = $i+1;
		$driver_shift_days=$this->input->post ( 'driver_shift_days_'.$j);
		$driver_shift_days_ary = array_values(array_filter($driver_shift_days));
		if(isset($driver_shift_days_ary) && !empty($driver_shift_days_ary)){
		$shift_days = json_encode($driver_shift_days_ary);
		}
		$available_dates_ary = array (
				'activity_id' => $package,
				'duration' => $season_array[$i],
				'shift_days_week' => $shift_days
				);	
		if(!empty($excursion_id)){
			$available_range_id=$this->input->post ( 'available_range_id_'.$j);
			if($available_range_id!=''){
			$available_dates_ary = $this->Activity_Model->update_available_dates( $available_dates_ary,$available_range_id );
			}else{
				$available_dates_ary = $this->Activity_Model->add_available_dates( $available_dates_ary );
			}
		
		}else{

		$available_dates_ary = $this->Activity_Model->add_available_dates( $available_dates_ary );
		}
			}
			
		}
		$filter_list=$this->input->post ( 'filter_list');
		if(!empty($filter_list)){
		$filter_list_array = array_values(array_filter($filter_list));
		if(isset($filter_list_array) && !empty($filter_list_array)){
		$sub_activities = json_encode($filter_list_array);
		}
		$sub_duration_hours = array_values($this->input->post ( 'sub_duration_hours' ));
		$sub_duration_mins = array_values($this->input->post ( 'sub_duration_mins' ));
		$num_of_sub_activities = count($filter_list_array);
		for($i=0;$i<$num_of_sub_activities;$i++){
			$j = $i+1;
		$sub_actvty = explode('|', $filter_list_array[$i]);	
		$sub_activities_ary = array (
				'activity_id' => $package,
				'sub_activity_id' => $sub_actvty[0],
				'sub_duration_hours' => $sub_duration_hours[$i],
				'sub_duration_mins' => $sub_duration_mins[$i]
				);	
		
		if(!empty($excursion_id)){
			$sub_activity_id=$this->input->post ( 'sub_activity_id_'.$j);
			if($sub_activity_id!=''){
			$available_dates_ary = $this->Activity_Model->update_sub_activities( $sub_activities_ary,$sub_activity_id );
			
			}else{
				$available_dates_ary = $this->Activity_Model->add_sub_activities( $sub_activities_ary );
			}
		
		}else{

		$available_dates_ary = $this->Activity_Model->add_sub_activities( $sub_activities_ary );
		}
			}
		}
		$itinerary = array (
						'itinerary_description' => $itinerarydesc [0],
						'sub_category_desc' => $sub_category_desc,
						'package_id' => $package 
				);
		if(!empty($excursion_id)){
			$activity_itinery_id=$this->input->post ( 'activity_itinery_id');
			$itineraryid = $this->Activity_Model->update_itinerary_details( $itinerary,$activity_itinery_id );
		}else{
			$itineraryid = $this->Activity_Model->itinerary( $itinerary );
		}
		
		// echo $this->db->last_query();exit;
		// debug($booking_date_ary);exit;
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
				move_uploaded_file ( $_FILES ['image'] ['tmp_name'], $path );
				$image = $itenary_imge;
				
			}
		}
		// foreach($nationality as $key => $value)
		// {
		// 	$nat_price=$price[$key];
		// 	$nation=$nationality[$key];
		// 	$nationality_price=array(
		// 				'country_id'=>$nation,
		// 				'price'=>$nat_price,
		// 				'package_id' => $package 
		// 			);
		// 	//debug($nationality_price);
		// 	$nationality_price_id = $this->Activity_Model->nationality_price( $nationality_price );
		// }
	//	debug($itineraryid);exit;
		for($i = 0; $i < $no_questions; $i ++) {
			if (! empty ( $quest [$i] )) {
				$qus_ans = array (
						'question' => $quest [$i],
						'answer' => $answer [$i],
						'package_id' => $package,
						'user_id' => $sup_id,
						'usertype' => $sup_type 
				);
				$queans = $this->Activity_Model->que_ans ( $qus_ans );
			}
		}


		$this->Activity_Model->do_delete_row('activity_cancellation','package_id',$package);
		$this->Activity_Model->do_delete_row('activity_pricing_policy','package_id',$package);


		$pricingpolicy = array (
				'package_id' => $package,
				'price_includes' => $includes,
				'price_excludes' => $excludes 
		);

		$policy = $this->Activity_Model->pricing_policy ( $pricingpolicy );
		$cancellation = array (
				'package_id' => $package,
				'cancellation_advance' => $advance,
				'cancellation_penality' => $this->input->post('penality') 
		);
		$cancel = $this->Activity_Model->cancellation_penality ( $cancellation );
		if (! empty ( $value )) {
			$deals = array (
					'value' => $value,
					'discount' => $discount,
					'you_save' => $save,
					'seats' => $seats,
					'time' => $time,
					'package_id' => $package 
			);
			$deall = $this->Activity_Model->deals ( $deals );
		}
		if(!empty($excursion_id)){
			$transferOption = $this->Activity_Model->delete_activity_price( $excursion_id );
		}
		$num_of_cntrct_durtn = count($season_array);
		for($i=0;$i<$num_of_cntrct_durtn;$i++){
			$j = $i+1;
		$transfer_option=$this->input->post ( 'transfer_option_'.$j);
		$transfer_option_ary = array_values(array_filter($transfer_option));
		$p_price=$this->input->post ( 'p_price_'.$j);
		$p_price_ary = array_values(array_filter($p_price));
		$child_price=$this->input->post ( 'child_price_'.$j);
		$child_price_ary = array_values(array_filter($child_price));
		$infant_price=$this->input->post ( 'infant_price_'.$j);
		$infant_price_ary = array_values(array_filter($infant_price));
		$senior_price=$this->input->post ( 'senior_price_'.$j);
		$senior_price_ary = array_values(array_filter($senior_price));
		
		$count = count($transfer_option_ary);
		for($k=0;$k<$count;$k++){
		$transfer_option_array = array (
				'activity_id' => $package,
				'nationality_id' => $nationality[0],
				'duration' => $season_array[$i],
				'transfer_option' => $transfer_option_ary[$k],
				'adult_price' => $p_price_ary[$k],
				'child_price' => $child_price_ary[$k],
				'infant_price' => $infant_price_ary[$k],
				'senior_price' => $senior_price_ary[$k],
				);
		$transferOption = $this->Activity_Model->activity_price( $transfer_option_array );
		// echo $this->db->last_query();

		}
		}
		// exit;
		//debug($deall);exit;
		// for($i = 0; $i < $duration; $i ++) {
		// 	if (! empty ( $sd [$i] )) {
		// 		$incl = array (
		// 				'from_date' => $sd [$i],
		// 				'to_date' => $ed [$i],
		// 				'duration' => $duration,
		// 				'adult_price' => $adult [$i],
		// 				'child_price' => $child [$i],
		// 				'package_id' => $package 
		// 		);
		// 		$in = $this->Activity_Model->incl ( $incl );
		// 	}
		// }
		redirect ( 'activity/view_with_price_supplier' );
	}
	public function view_without_price() {
		$data ['newpackage'] = $this->Activity_Model->without_price ();
		$this->template->view ( 'activity/view_without_price', $data );
	}
	public function view_with_price() {
		$user_type = 1;
		$data ['newpackage'] = $this->Activity_Model->with_price ($user_type);
		//debug($data);exit;
		$data['utype']="admin";
		$this->template->view ( 'activity/view_with_price', $data );
	}
	public function view_with_price_staff() {
		$user_type = 9;
		$data ['newpackage'] = $this->Activity_Model->with_price ($user_type);
		//debug($data);exit;
		$data['utype']="staff";
		$this->template->view ( 'activity/view_with_price_staff', $data );
	}
	public function view_with_price_supplier() {
		$user_type = 8;
		$data ['newpackage'] = $this->Activity_Model->with_price ($user_type);
		//debug($data);exit;
		$data['utype']="supplier";
		$this->template->view ( 'activity/view_with_price_supplier', $data );
	}
	public function update_deal_status($package_id, $deals) {
		$this->Activity_Model->update_deal_status ( $package_id, $deals );
		if ($deals == '1') {
			$data ['package_id'] = $package_id;
			$this->template->view ( 'activity/view_city_request', $data );
		} else if ($deals == '0') {
			$data ['package_id'] = $package_id;
			$this->Activity_Model->delete_deal_id ( $package_id );
			redirect ( 'activity/view_city_request', 'refresh' );
		} else {
			redirect ( 'activity/view_deals', 'refresh' );
		}
	}
	public function update_status($package_id, $status,  $type='') {
		$this->Activity_Model->update_status ( $package_id, $status );
		if(!empty($type))
		{
			if($type=='admin'){$type='view_with_price';}
			else if($type=='staff'){$type='view_with_price_staff';}
			else if($type=='supplier'){$type='view_with_price_supplier';}
		}else{$type='view_with_price';}
		redirect ( 'activity/'.$type.'/', 'refresh' );
	}
	public function update_top_destination($package_id, $status) {
		$this->Activity_Model->update_top_destination ( $package_id, $status );
		redirect ( 'activity/view_with_price_supplier/', 'refresh' );
	}
	public function read_enquiry($package_id) {
		$status=ACTIVE;
		$this->Activity_Model->update_enquiry_status ( $package_id, $status );
		redirect ( 'activity/enquiries/', 'refresh' );
	}
	
	public function view_deals() {
		$data ['newpackage'] = $this->Activity_Model->get_supplier ();
		$this->template->view ( 'activity/activity', $data );
	}
	public function update_homepage_status2($package_id, $home_page) {
		$this->Activity_Model->update_homepage_status ( $package_id, $home_page );
		redirect ( 'activity/view_without_price/' . $package_id, 'refresh' );
	}
	public function edit_without_price($package_id) {
		$data ['packdata'] = $this->Activity_Model->get_package_id ( $package_id );
		$data ['price'] = $this->Activity_Model->get_price ( $package_id );
		$data ['countries'] = $this->Activity_Model->get_country_city_list ();
		// print_r($data);exit;
		$this->template->view ( 'activity/edit_without_price', $data );
	}
	public function edit_itinerary($package_id, $type='') {
		$data ['pack_data'] = $this->Activity_Model->get_itinerary_id ( $package_id );
		// print_r($data);die;
		$data ['package_id'] = $package_id;
		if(!empty($type)){
		$data ['type'] = $type;}
		$this->template->view ( 'activity/edit_itinerary', $data );
	}
	public function quesans_view($package_id) {
		$data ['que_ans'] = $this->Activity_Model->get_que_ans ( $package_id );
		$data ['package_id'] = $package_id;
		$this->template->view ( 'activity/quesans_view', $data );
	}
	public function enquiries() {
		$data ['enquiries'] = $this->Activity_Model->enquiries ();
		//debug($data);exit;
		$this->template->view ( 'activity/enquiries', $data );
	}
	public function delete_enquiry($id, $package_id) {
		$this->Activity_Model->delete_enquiry ( $id );
		redirect ( 'supplier/enquiries/' );
	}
	public function itinerary() {
		// print_r($itinerarydesc);exit;
		$itinerary = array (
				'itinerary_description' => $itinerarydesc,
				'day' => $days 
		);
		$it = $this->Activity_Model->itinerary ( $itinerary );
		if ($it) {
			redirect ( 'supplier/view_deals', 'refresh' );
		}
	}
	public function itinerary_loop($duration) {
		$data ['duration'] = $duration;
		echo $this->template->isolated_view ( 'activity/duration_itinerary', $data );
	}
	public function itinerary_loop1($questions) {
		$data ['questions'] = $questions;
		$this->template->isolated_view ( 'activity/question', $data );
	}
	public function itinerary_loop2($pricee) {
		$data ['pricee'] = $pricee;
		$this->template->isolated_view ( 'activity/withprice', $data );
	}
	public function get_crs_city($city_id) {
		$city = $this->Activity_Model->get_crs_city_list ( $city_id );
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
	public function get_sub_category($type_id,$package_id='',$type='') {
		$str='';
		$arr = $this->input->post ( 'arr' );
		$sub_category = $this->Activity_Model->get_sub_category_activity ( $type_id );
		// debug($arr);exit;
		$sHtml = "";
		$sHtml .= '<option value="NA">Select Sub Excursion Type</option>';
		if (! empty ( $sub_category )) {
			foreach ( $sub_category as $key => $value ) {
				if($type=='E'){
				  	$data['sub_activity_list'] = $this->custom_db->single_table_records('sub_activity_timing','*',array('activity_id'=>$package_id))['data'];
				  	// echo $this->db->last_query();exit;
				  	// debug($data['sub_activity_list']);exit;
				  	for($i=0;$i<count($data['sub_activity_list']);$i++){
				  		if($value->id==$data['sub_activity_list'][$i]['sub_activity_id']){
				  			$str = 'selected';
				  		}
				  	}
				  	}
				if(! empty($arr)){
				if (in_array($value->id, $arr))
				  {
				  }else{
				  	$sHtml .= '<option value="' . $value->id . '|' . $value->activity_sub_category . '" ' . $str . '>' . $value->activity_sub_category. ' </option>';
				  }
				}else{
				$sHtml .= '<option value="' . $value->id . '|' . $value->activity_sub_category . '" ' . $str . '>' . $value->activity_sub_category. '</option>';
	
				}
			}
		}
		if(count($arr) == count($sub_category))
			{ 
		echo json_encode ( array (
				'result' => 0 
		) );}else{
		echo json_encode ( array (
				'result' => $sHtml 
		) );}
		exit ();
	}
	public function get_tour($tour_id) {
		// echo "hi";
		// echo $tour_id;die;
		$tours = $this->Activity_Model->get_tour_list ( $tour_id );
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
	public function edit_with_price($package_id,$type='') {
		$status = 1;
		$data ['package_type_data'] = $this->Activity_Model->package_types($status)->result ();
		foreach ($data ["package_type_data"] as $key => $value) {
			$status = 1;
			$type_id = $value->activity_types_id;
			$data['sub_category'][] = $this->custom_db->single_table_records('activity_sub_category','*',array('activity_type_id'=>$type_id,'status'=>1))['data'];
		}
		$data['activity_dates'][] = $this->custom_db->single_table_records('activity_available_dates','*',array('activity_id'=>$package_id))['data'];
		$data['activity_price'] = $this->custom_db->single_table_records('activity_price_management','*',array('activity_id'=>$package_id))['data'];
		$data['sub_activity_list'] = $this->custom_db->single_table_records('sub_activity_timing','*',array('activity_id'=>$package_id))['data'];
		$data['activity_itinerary'] = $this->custom_db->single_table_records('activity_itinerary','*',array('package_id'=>$package_id))['data'];
		// debug($data['activity_itinerary']);exit;
		$data ['packdata'] = $this->Activity_Model->get_package_id ( $package_id );
		$data ['price'] = $this->Activity_Model->get_price ( $package_id );
		$data ['countries'] = $this->Activity_Model->get_country_city_list ();
		// debug($data ['packdata']->package_type);die;
		$data ['sub_category_details'] = $this->Activity_Model->get_sub_category_activity ($data ['packdata']->package_type);
		// debug($data ['sub_category_details']);die;
		if(!empty($type)){
		$data ['type'] = $type;}
		// echo $this->db->last_query();exit;
		// debug($data['sub_category']);exit;
		$data ["country"] = $this->Activity_Model->get_countries ();
		$currency_list  = $this->Activity_Model->get_countries_currency(); 
		$data ["themes"] = $this->custom_db->single_table_records('activity_subtheme','*',array('status'=>1))['data'];
		foreach ($data ["themes"] as $key => $value) {
			$status = 1;
			$theme_id = $value['id'];
			$data['sub_themes'][] = $this->custom_db->single_table_records('sub_theme_activity','*',array('activity_theme_id'=>$theme_id,'status'=>1))['data'];
		}
		$data ["amenities"] = $this->custom_db->single_table_records('activity_amenties','*',array('status'=>1))['data'];
		$data ['nationality_group'] = $this->Activity_Model->nationality_group_data ();
		// debug($data['nationality_group']);exit;
		$condition = '1';
		$health_instructions = $this->Activity_Model->health_instructions($condition); 
        $data['health_instructions'] = $health_instructions;
		// print_r($data);exit;
		$data ["currency"]=$currency_list;
		$can= $this->custom_db->single_table_records('activity_cancellation','cancellation_advance,cancellation_penality',array('package_id'=>$package_id))['data'];//$refundable_term;
		$can_count = count($can)-1;
		$data ["refundable_term"] = $can[$can_count]['cancellation_penality'];
		$data ["cancellation_advance"] = $can[$can_count]['cancellation_advance'];
		// debug($data);die;
		$this->template->view ( 'activity/edit_activity_details', $data );
	}
	public function view_activity_details($package_id,$type='') {
		$data ['package_type_data'] = $this->Activity_Model->package_view_data_types ()->result ();
		$data ['packdata'] = $this->Activity_Model->get_package_id ( $package_id );
		$data ['price'] = $this->Activity_Model->get_price ( $package_id );
		$data ['countries'] = $this->Activity_Model->get_country_city_list ();
		if(!empty($type)){
		$data ['type'] = $type;}
		// print_r($data);exit;
		$this->template->view ( 'activity/view_activity_details', $data );
	}
	public function update_package($package_id) {
		$name = $this->input->post ( 'name' );
		$location = $this->input->post ( 'location' );
		$description = $this->input->post ( 'Description' );
		$path = realpath ( '../extras' ).'/custom/'.CURRENT_DOMAIN_KEY.'/uploads/activity/';
		$photo = $_FILES;
		if ($_FILES ['photo'] ['name'] != '') {
			$_FILES ['photo'] ['name'] = $photo ['photo'] ['name'];
			$_FILES ['photo'] ['type'] = $photo ['photo'] ['type'];
			$_FILES ['photo'] ['tmp_name'] = $photo ['photo'] ['tmp_name'];
			$_FILES ['photo'] ['error'] = $photo ['photo'] ['error'];
			$_FILES ['photo'] ['size'] = $photo ['photo'] ['size'];
			$doc_name = $_FILES ['photo'] ['name'];
			move_uploaded_file ( $_FILES ['photo'] ['tmp_name'], $path.$doc_name );
			$photo = $_FILES ['photo'] ['name'];
		} else {
			$photo = $this->input->post ( 'hidephoto' );
		}
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

		$seasons_date_range=$this->input->post ( 'seasons_date_range' );
		$season_array = array_values(array_filter($seasons_date_range));
	//	debug($season_array);//exit;
	if(isset($season_array) && !empty($season_array)){
		$seasons_date = json_encode($season_array);
		}


		$weekdays=$this->input->post ( 'weekdays' );
		//debug($weekdays);exit;
		if(isset($weekdays) && !empty($weekdays)){
		$week_days = json_encode($weekdays);
		}
		$data = array (
				'package_name' => $name,
				'package_location' => $location,
				'package_description' => $description,
				'image' => $photo,
				'rating' => $rating,
				//'price' => $p_price ,
				'weekdays' => $week_days ,
				'seasons_date'=>$seasons_date

		);
		// debug($data);exit;
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
				$this->Activity_Model->update_edit_pri ( $package_id, $pri );
			}
		}
		// print_r($data);die;
		$this->Activity_Model->update_edit_package ( $package_id, $data );
		$this->Activity_Model->update_edit_policy ( $package_id, $policy );
		$this->Activity_Model->update_edit_can ( $package_id, $can );
		$this->Activity_Model->update_edit_dea ( $package_id, $dea );
		$this->session->set_flashdata('message', UL0013);
		if ($packtypew == "w") {
			redirect ( 'activity/view_with_price_supplier' );
		} elseif ($packtypew == 'wo') {
			redirect ( 'activity/view_without_price' );
		} else {
			redirect ( 'activity/view_deals' );
		}
		
		redirect ( 'activity/view_with_price_supplier' );
	}
	public function images($package_id, $type='') {
		if ($_FILES) {
			$path = realpath ( '../extras' ).'/custom/'.CURRENT_DOMAIN_KEY.'/uploads/activity/';
			$tra = $_FILES;
			$_FILES ['traveller'] ['name'] = $tra ['traveller'] ['name'];
			$_FILES ['traveller'] ['type'] = $tra ['traveller'] ['type'];
			$_FILES ['traveller'] ['tmp_name'] = $tra ['traveller'] ['tmp_name'];
			$_FILES ['traveller'] ['error'] = $tra ['traveller'] ['error'];
			$_FILES ['traveller'] ['size'] = $tra ['traveller'] ['size'];
			$name_of_traveller_img = 'traveller-' . time () . $_FILES ['traveller'] ['name'];
			move_uploaded_file ($_FILES ['traveller'] ['tmp_name'], $path.$name_of_traveller_img );
			// debug($path);
			// debug($this->template->domain_uploads_activity ( $name_of_traveller_img ));exit;
			$traveller_img = $name_of_traveller_img;
			$sup_id = $this->entity_user_id;
			$pckge_id = $this->input->post ( 'pckge_id' );
			$traveller = array (
					'traveller_image' => $traveller_img,
					'user_id' => $sup_id,
					'package_id' => $pckge_id 
			);
			$travel = $this->Activity_Model->travel_images ( $traveller );
			$this->session->set_flashdata('message', UL0013);
			redirect ( base_url () . 'activity/images/' . $package_id . '/w' );
		}
		$data ['traveller'] = $this->Activity_Model->get_image ( $package_id );
		$data ['package_id'] = $package_id;
		if(!empty($type)){
		$data ['type'] = $type;}
		$this->template->view ( 'activity/images', $data );
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
		$path = realpath ( '../extras' ).'/custom/'.CURRENT_DOMAIN_KEY.'/uploads/activity/';
		// print_r($cp);exit;
		for($i = 0; $i < $cp; $i ++) {
			
			if (! empty ( $_FILES ['imagelable' . $i] ['name'] )) {
				$_FILES ['imagelable'] ['name'] = $img ['imagelable' . $i] ['name'];
				$_FILES ['imagelable'] ['type'] = $img ['imagelable' . $i] ['type'];
				$_FILES ['imagelable'] ['tmp_name'] = $img ['imagelable' . $i] ['tmp_name'];
				$_FILES ['imagelable'] ['error'] = $img ['imagelable' . $i] ['error'];
				$_FILES ['imagelable'] ['size'] = $img ['imagelable' . $i] ['size'];
				move_uploaded_file ( $_FILES ['imagelable'] ['tmp_name'], $path.$_FILES ['imagelable'] ['name'] );
				$image = $this->template->domain_uploads_activity ( $_FILES ['imagelable' . $i] ['name'] );
			} else {
				$image = $hiddenimage [$i];
			}
			
			$data = array (
					'itinerary_description' => $itinerarydesc [$i],
					'place' => $place [$i],
					'day' => $days [$i],
					'itinerary_image' => $image 
			);
			
			$this->Activity_Model->update_itinerary ( $package_id, $itinerary_id [$i], $data );
			$this->session->set_flashdata('message', UL0013);
		}
		redirect ( 'activity/edit_itinerary/' . $package_id, 'refresh' );
	}
	public function view_enquiries($package_id,$type='') {
		$data ['enquiries'] = $this->Activity_Model->view_enqur ( $package_id );
		$data ['package_id'] = $package_id;
		$data ['type'] = $type;
		$this->template->view ( 'activity/enquiry_package', $data );
	}

	public function edit_price($package_id, $type='') {
		// error_reporting(E_ALL);
		$data ['price_data'] = $this->Activity_Model->get_price_ids( $package_id );
		// $currency_list  = $this->Activity_Model->get_currency_list(); 
		$currency_list  = $this->Activity_Model->get_countries_currency(); 
		$data ["currency"]=$currency_list;
		  //debug($data['currency']);die;
		$data ['package_id'] = $package_id;
		if(!empty($type)){
		$data ['type'] = $type;}
		 // debug($data ['price_data']);die;
		$this->template->view ( 'activity/edit_prices', $data );
	}
	public function add_price_new() {
		$package_id = $this->input->post ('pckge_id' );
		$nationality = $this->input->post ('nationality' );
		$currency_code = $this->input->post ('currency' );
		$p_price = $this->input->post ('p_price' );
		$child_price = $this->input->post ('child_price' );
		$child_price_grp2 = $this->input->post ('child_price_grp2' );
		//$currency_obj = new Currency(array('module_type' => 'Holiday','from' => $nationality , 'to' => 'AED')); 
		// debug($currency_code);exit();
		$currency_obj = new Currency(array('module_type' => 'Holiday','from' =>'AED'  , 'to' => $currency_code)); 
   		$converted_currency_rate = $currency_obj->getConversionRate(true);
   		$final_price = $p_price/$converted_currency_rate;
   		$final_price_child = $child_price/$converted_currency_rate;
		$data = array (
					'package_id' => $package_id,
					'country_id' => $nationality,
					'price' => $p_price,
					'child_price' => $child_price,
					'child_price_group2' => $child_price_grp2,
					'currency_code'=>$currency_code,
					'final_price'=>$final_price,
					'final_price_child'=>$final_price_child
				);
		$this->db->where("package_id",$package_id );
		$this->db->where("country_id",$nationality );
		$qur = $this->db->get ("activity_nationality_price"); 
		$count=$qur->num_rows();

		// debug($count);exit();
		if($count <= 0)
		{
			$nationality_price_id = $this->Activity_Model->nationality_price( $data );
			if($nationality_price_id)
			{
				// $this->session->set_flashdata(array('message' => 'UL0014', 'type' => SUCCESS_MESSAGE));
				$this->session->set_flashdata('message', UL0035);
				 redirect ( 'activity/edit_price/' . $package_id, 'refresh' );
			}
		}else
		{
			// $this->session->set_flashdata(array('message' => 'UL0098', 'type' => ERROR_MESSAGE));
			redirect ( 'activity/edit_price/' . $package_id, 'refresh' );
		}



	}
	public function delete_price($id,$package_id) {
		$this->Activity_Model->delete_price ( $id );
		redirect ( 'activity/edit_price/' . $package_id, 'refresh' );
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
		
		public function view_price_category() {
			// error_reporting(E_ALL);
		$data ['price_category_data'] = $this->Activity_Model->price_category_data ()->result ();
		$tours_country_name = $this->Activity_Model->tours_country_name();
     // debug($tours_country_name);exit;
      foreach ($tours_country_name as $key => $value) {
       $country_name[$value['id']]=$value['name'];
          }
       $data['tours_country_name']=$country_name;
       //debug($data['country_name']);exit;
		// print_r($data['package_view_data']); die();
		$this->template->view ('activity/view_price_category', $data );
	}

	
		public function add_price_category($id = '') {
			// error_reporting(E_All);
			$data ['tours_continent'] = $this->Activity_Model->get_tours_continent();
		if ($id != '') {
			$data ['id']=$id;

			$data ['pack_data'] = $this->Activity_Model->get_price_category_id ( $id );
			$contient=$data['pack_data'][0]->contient;//exit;
			$contry=$data['pack_data'][0]->country;
			//debug($contient);
			//$tours_continent = $this->Activity_Model->country_id($contry);
			$tours_continent = $this->Activity_Model->ajax_tours_continent($contient);
			$data['tours_continent_sel']=$tours_continent;
			//debug($this->db->last_query());exit;
			//debug($data['contry']);exit;
			$this->template->view ( 'activity/add_price_category', $data );
		} else {
			$this->template->view ( 'activity/add_price_category',$data );
		}
	}

	  public function ajax_tours_continent() {
    $data = $this->input->post();

    $tours_continent = $data['tours_continent'];      
    $tours_continent = $this->Activity_Model->ajax_tours_continent($tours_continent);         // debug($this->db->last_query());exit;
       // debug($tours_continent); exit; 
    foreach($tours_continent as $key => $value)
    {
      $options .=  '<option value="'.$value['id'].'">'.$value['name'].'</option>';
    } 
    echo $options;   
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
			'domain_list_fk' =>$domain_list_fk 
		);
		//debug($pack_id);exit;
		if($pack_id>0)
		{
			$price_cat = $this->Activity_Model->update_price_cat($price_data,$pack_id);
		}
		else
		{
			$price_cat = $this->Activity_Model->add_price_cat($price_data);

		}
		//debug($this->db->last_query());exit;

		if($price_cat)
		{
		  		 // $this->session->set_flashdata(array('message' => 'UL0014', 'type' => SUCCESS_MESSAGE));
			$this->session->set_flashdata('message', UL0014); 
		  		redirect ( 'activity/view_price_category' );
			}
			else
			{
				// $this->session->set_flashdata(array('message' => 'UL0098', 'type' => ERROR_MESSAGE));
				$this->session->set_flashdata('message', UL0098); 
			}
		 
	

		
	}

  public function get_currency_detls() {
    $post_data = $this->input->post();//
    $data ['price_data'] = $this->Activity_Model->get_price_ids( $post_data['pckge_id'], $post_data['country_id'] );
    if($data ['price_data'])
    {
    	echo 0;
    }
    else
    {
    $currency = $this->Activity_Model->get_country_name_currency($post_data['country_id']);
    echo($currency[0]->currency_code);
    }
    
  }
  public function multi_delete_cancellation_policy()
  {
  	$ids = $this->input->post();
  	if(!empty($ids))
  	{
  		$idss=implode(',', $ids['id']);
  	}
  	// debug($idss);
  	$idss1=str_replace(",", "','", $idss);
  	$query="delete from activity_cancellation_policy where id IN('$idss1')";
  	// echo $query;exit;
		$query=$this->db->query($query);
		echo 1;

        // $this->custom_db->delete_record('activity_cancellation_policy',array('id'=>$id));
        // redirect('activity/cancellation_policy');
      
  }
   public function cancellation_policy($id = 0 , $action = '')
  {
    //error_reporting(E_ALL);
    if($id)
    {
      if ($action == 'delete') 
      {
        $this->custom_db->delete_record('activity_cancellation_policy',array('id'=>$id));
        redirect('activity/cancellation_policy');
      }
      $page_data['tc_data'] =  $this->custom_db->single_table_records('activity_cancellation_policy','*',array('id'=>$id))['data'][0];
       $page_data['tc_data']['charge_details'] = $this->Activity_Model->get_activity_cancel_charge( $id );
    }
    $t_c = $this->input->post();
    $seasonality_from1 = explode('/', $t_c['seasonality_from']);
    $seasonality_from = $seasonality_from1[2].'-'.$seasonality_from1[1].'-'.$seasonality_from1[0];
    $seasonality_to1 = explode('/', $t_c['seasonality_to']);
    $seasonality_to = $seasonality_to1[2].'-'.$seasonality_to1[1].'-'.$seasonality_to1[0];  
    if($t_c)
    {
      if($t_c['id'])
      {
        $data=array(
          'activity_id'=>$t_c['activity_id'],
          'policy_name'=>$t_c['policy_name'],
          'start_date'=>$seasonality_from,
          'expiry_date'=>$seasonality_to,
          // 'amount'=>$t_c['amount'],
          // 'charge_type'=>$t_c['charge_type'],
          'description'=>$t_c['description']
          );
        $this->custom_db->update_record('activity_cancellation_policy',$data,array('id'=>$t_c['id']));
        $this->custom_db->delete_record('activity_cancellation_price',array('cancel_id'=>$t_c['id']));
        foreach ($t_c['dates'] as $key => $value) {
        $data_price_details=array(
          'cancel_id'=>$t_c['id'],
          'charge_type' => $value['charge_type'],
          'amount' => $value['amount'],
          'no_of_days' => $value['no_days']
          );
        // debug($data_price_details);exit;
        $this->custom_db->insert_record('activity_cancellation_price',$data_price_details);
        // echo $this->db->last_query();exit;
        }

      }
      else
      {
        $data=array(
          'activity_id'=>$t_c['activity_id'],
          'policy_name'=>$t_c['policy_name'],
          'start_date'=>$seasonality_from,
          'expiry_date'=>$seasonality_to,
          'created_by'=> $this->entity_user_id,
          'user_type'=> 1,
          // 'charge_type'=>$t_c['charge_type'],
          'description'=>$t_c['description']
          );
      

      $this->custom_db->delete_record('activity_cancellation_policy',array('policy_name'=>$t_c['policy_name'],'activity_id'=>$t_c['activity_id']));
    
      $cancel_id = $this->custom_db->insert_record('activity_cancellation_policy',$data);
      // debug($cancel_id['insert_id']);exit;
      foreach ($t_c['dates'] as $key => $value) {
        $data_price=array(
          'cancel_id'=>$cancel_id['insert_id'],
          'charge_type' => $value['charge_type'],
          'amount' => $value['amount'],
          'no_of_days' => $value['no_days']
          );
        // debug($data_price);exit;
        $this->custom_db->insert_record('activity_cancellation_price',$data_price);
        // echo $this->db->last_query();exit;
        }

      }

      redirect('activity/cancellation_policy');     
    }
    // debug($this->db->last_query()); exit;
    $page_data['transferdata'] =  $this->custom_db->single_table_records('activity_cancellation_policy','*',array('user_type'=>1),0,100000000,array('id'=>'desc'))['data'];
    $i=0;
  foreach($page_data['transferdata'] as $val)
  {

    $cancel_id = $val['id'];
    // debug($page_data['transferdata']);exit;
    $page_data['transferdata'][$i]['charge_details'] = $this->Activity_Model->get_activity_cancel_charge( $cancel_id );
    $i++;
  }
    // debug($page_data['transferdata']);exit;

    //$page_data=array();
  //debug($page_data);exit;
    $this->template->view('activity/cancellation_policy',$page_data);
  }


function check_seasonality()
  {
    $tran_id = $this->input->post('tran_id');
    $seasonality_from = $this->input->post('seasonality_from');
     $seasonality_details =  $this->Activity_Model->check_seasonality_details($tran_id ,$seasonality_from);
     if($seasonality_details)
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
    $seasonality_from = $this->input->post('seasonality_from');
    $seasonality_to = $this->input->post('seasonality_to');
     $seasonality_details =  $this->Activity_Model->check_seasonality_details($tran_id ,$seasonality_from,$seasonality_to);
     if($seasonality_details)
     {
      echo "Already policy for this period";
     }
     else
     {
      echo 1;
     }
  }
  function manage_activity_details()
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
          $this->Activity_Model->update_status ( $checkval1, $status );
          $this->session->set_flashdata('message', UL0013); 
          break;
          case 'activate' :
          $status = 1;
          $this->Activity_Model->update_status ( $checkval1, $status );
          $this->session->set_flashdata('message', UL0013);
            break;
          case 'delete' :
            $this->Activity_Model->delete_package($checkval1);
            $this->session->set_flashdata('message', UL0099);
            break;
              
        }
      }
    }
      
  }
  function manage_activity_types()
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
          $data['status'] = $status;
          $this->custom_db->update_record('activity_types',$data,array('activity_types_id'=>$checkval1));
          $this->session->set_flashdata('message', UL0013);
          break;
          case 'activate' :
          $status = 1;
          $data['status'] = $status;
          $this->custom_db->update_record('activity_types',$data,array('activity_types_id'=>$checkval1));
          $this->session->set_flashdata('message', UL0013);
            break;
          case 'delete' :
            $this->Activity_Model->delete_package_type($checkval1);
            $this->session->set_flashdata('message', UL0099);
            break;
              
        }
      }
    }
      
    // $this->template->view('voucher/visa_voucher', $page_data);
  }
  function manage_activity_cancellation()
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
          $this->Activity_Model->update_status ( $checkval1, $status );
          $this->session->set_flashdata('message', UL0013); 
          break;
          case 'activate' :
          $status = 1;
          $this->Activity_Model->update_status ( $checkval1, $status );
          $this->session->set_flashdata('message', UL0013);
            break;
          case 'delete' :
          $this->custom_db->delete_record('activity_cancellation_policy',array('id'=>$checkval1));
          $this->session->set_flashdata('message', UL0099);
            break;
              
        }
      }
    }
      
    // $this->template->view('voucher/visa_voucher', $page_data);
  }
   public function activity_subtheme() {   
       $page_data['activity_subtheme'] = $this->Activity_Model->activity_subtheme();
    //debug($tour_subtheme); exit;
       $sub_theme = $this->Activity_Model->get_excursion_subtheme();
      foreach ($sub_theme as $key => $value) {
			$page_data['sub_theme_list'][] = $value->id;
		}
		// debug($data['sub_theme_list']);exit;
       $this->template->view('activity/activity_subtheme',$page_data);
     }
     public function activity_amenties() {   
       $activity_amenties = $this->Activity_Model->activity_amenties();
    //debug($tour_subtheme); exit;    
       $page_data['activity_amenties'] = $activity_amenties;
       $this->template->view('activity/activity_amenties',$page_data);
     }

     public function activity_subtheme_save() {
      $data = $this->input->post();
    //debug($data); exit;
      $activity_subtheme   = sql_injection($data['activity_subtheme']);
      $data ["theme_details"] = $this->custom_db->single_table_records('activity_subtheme','*',array('activity_subtheme'=>$activity_subtheme,'created_by'=>$this->entity_user_id))['data'];
      // debug($data ["sub_category_details"]);exit;
      // echo $this->db->last_query();exit;
      // debug($cnt);exit;
      if(count($data ["theme_details"])>0){
      	$this->session->set_flashdata('message',UL0098 );
      	redirect('activity/activity_subtheme/'); 
      }else{
      $query = "insert into activity_subtheme set activity_subtheme='$activity_subtheme', status=1,created_by='".$GLOBALS['CI']->entity_user_id."' ";        
        //echo $query; //exit;
      $return = $this->Activity_Model->query_run($query);
      if($return)
        {   $this->session->set_flashdata('message', UL0014);
    		redirect('activity/activity_subtheme/'); }
      else
        { echo $return; exit; }     
        }         
    }
    public function activity_amenties_save() {
      $data = $this->input->post();
    //debug($data); exit;
      $activity_amenties   = sql_injection($data['activity_amenties']);
      $data ["amenties_details"] = $this->custom_db->single_table_records('activity_amenties','*',array('activity_amenties'=>$activity_amenties,'created_by'=>$this->entity_user_id))['data'];
      // debug($data ["sub_category_details"]);exit;
      // echo $this->db->last_query();exit;
      // debug($cnt);exit;
      if(count($data ["amenties_details"])>0){
      	$this->session->set_flashdata('message',UL0098 );
      	redirect('activity/activity_amenties/'); 
      }else{
      $query = "insert into activity_amenties set activity_amenties='$activity_amenties', status=1,created_by='".$GLOBALS['CI']->entity_user_id."' ";        
        //echo $query; //exit;
      $return = $this->Activity_Model->query_run($query);
      if($return)
        {   $this->session->set_flashdata('message', UL0014);
    		redirect('activity/activity_amenties/'); }
      else
        { echo $return; exit; }
        }              
    }
    public function activation_activity_type($id,$status) {
      // $return = $this->Activity_Model->record_activation('activity_types',$id,$status);
      $data['status'] = $status;
      $this->custom_db->update_record('activity_types',$data,array('activity_types_id'=>$id));
      $this->session->set_flashdata('message', UL0013);
      redirect('activity/view_packages_types');
    }
    public function activation_activity_subtheme($id,$status) {
      $return = $this->Activity_Model->record_activation('activity_subtheme',$id,$status);
      if($return){redirect('activity/activity_subtheme');} 
      else { echo $return;} 
    }
    public function activation_activity_amenties($id,$status) {
      $return = $this->Activity_Model->record_activation('activity_amenties',$id,$status);
      if($return){redirect('activity/activity_amenties');} 
      else { echo $return;} 
    }
    public function activation_subtheme($sub_theme_id,$status,$theme_id) {
      $return = $this->Activity_Model->record_activation('sub_theme_activity',$sub_theme_id,$status);
      if($return){redirect('activity/add_subtheme/'.$theme_id);} 
      else { echo $return;} 
    }
    public function activation_sub_activity($sub_activity_id,$status,$id) {
      $return = $this->Activity_Model->record_activation('activity_sub_category',$sub_activity_id,$status);
      if($return){redirect('activity/add_sub_category/'.$id);} 
      else { echo $return;} 
    }
    public function delete_activity_subtheme($id) {
      $return = $this->Activity_Model->record_delete('activity_subtheme',$id);
      $this->session->set_flashdata('message', UL0099);
      redirect('activity/activity_subtheme/');
    }
    public function delete_activity_amenties($id) {
      $return = $this->Activity_Model->record_delete('activity_amenties',$id);
      $this->session->set_flashdata('message', UL0099);
      redirect('activity/activity_amenties/');
    }
    public function delete_subtheme($id, $theme_id) {
      $return = $this->Activity_Model->record_delete('sub_theme_activity',$id);
      $this->session->set_flashdata('message', UL0099);
      redirect('activity/add_subtheme/'.$theme_id);
    }
    public function delete_sub_activity($id, $activity_id) {
      $return = $this->Activity_Model->record_delete('activity_sub_category',$id);
      $this->session->set_flashdata('message', UL0099);
      redirect('activity/add_sub_category/'.$activity_id);
    }
    public function edit_activity_subtheme($id) {
      $activity_subtheme_details = $this->Activity_Model->table_record_details('activity_subtheme',$id);
    // debug($activity_subtheme_details); exit;      
      $page_data['activity_subtheme_details'] = $activity_subtheme_details;
    // debug($page_data); exit;
      $this->template->view('activity/edit_activity_subtheme',$page_data);
    }
    public function edit_sub_activity($sub_id,$id) {
    	$data ['id']=$sub_id;
    	$data ['activity_id']=$id;
		$data ['pack_data'] = $this->Activity_Model->get_pack_id ( $id );
		// $data ["sub_category_details"] = $this->custom_db->single_table_records('activity_sub_category','*',array('activity_type_id'=>$id,'id'=>$sub_id)))['data'];
		$data ["sub_category_details"] = $this->custom_db->single_table_records('activity_sub_category','*',array('id'=>$sub_id),0,100000000,array('id'=>'desc'))['data'];
		// debug($page_data ["sub_category_details"] );exit;
		$this->template->view ( 'activity/edit_sub_category', $data );
    }
    public function edit_activity_amenties($id) {
      $activity_amenties_details = $this->Activity_Model->table_record_details('activity_amenties',$id);
    // debug($activity_subtheme_details); exit;      
      $page_data['activity_amenties_details'] = $activity_amenties_details;
    // debug($page_data); exit;
      $this->template->view('activity/edit_activity_amenties',$page_data);
    }
    public function add_subtheme($id) {
      $activity_theme_details = $this->Activity_Model->table_record_details('activity_subtheme',$id);
      $page_data ["subtheme_details"] = $this->custom_db->single_table_records('sub_theme_activity','*',array('activity_theme_id'=>$id,'created_by'=>$this->entity_user_id),0,100000000,array('id'=>'desc'))['data'];
    // debug($page_data ["subtheme_details"]); exit;      
      $page_data['activity_theme_details'] = $activity_theme_details;
    // debug($page_data); exit;
      $this->template->view('activity/add_subtheme',$page_data);
    }
    public function edit_activity_subtheme_save() {
      $data = $this->input->post();
    // debug($data); exit;
      $id             = $data['id'];
      $activity_subtheme  = sql_injection($data['activity_subtheme']);
      $data ["sub_theme_details"] = $this->custom_db->single_table_records('sub_theme_activity','*',array('sub_theme'=>$activity_subtheme),0,100000000,array('id'=>'desc'))['data'];
      // debug($data ["sub_theme_details"]);exit;
      // echo $this->db->last_query();exit;
      // debug($cnt);exit;
      if(count($data ["sub_theme_details"])>0){
      	$this->session->set_flashdata('message',UL0098 );
      }else{
      $query = "update activity_subtheme set activity_subtheme='$activity_subtheme' where id='$id'";        
        //echo $query; //exit;
      $return = $this->Activity_Model->query_run($query);
      $this->session->set_flashdata('message', UL0013);
  		}
      redirect('activity/edit_activity_subtheme/'.$id); 
                    
    }
    public function edit_activity_amenties_save() {
      $data = $this->input->post();
    // debug($data); exit;
      $id             = $data['id'];
      $activity_amenties  = sql_injection($data['activity_amenties']);
      $data ["amenties_details"] = $this->custom_db->single_table_records('activity_amenties','*',array('activity_amenties'=>$activity_amenties))['data'];
      if(count($data ["amenties_details"])>0){
      	$this->session->set_flashdata('message',UL0098 );
      }else{
      $query = "update activity_amenties set activity_amenties='$activity_amenties' where id='$id'";
      $return = $this->Activity_Model->query_run($query);
      $this->session->set_flashdata('message', UL0013);
  		}
      redirect('activity/edit_activity_amenties/'.$id); 
                    
    }
    public function add_subtheme_save() {
      $data = $this->input->post();
    // debug($data); exit;
      $id             = $data['id'];
      $add_subtheme             = $data['add_subtheme'];
      $activity_subtheme  = sql_injection($data['activity_subtheme']);
      $data ["sub_theme_details"] = $this->custom_db->single_table_records('sub_theme_activity','*',array('activity_theme_id'=>$id,'sub_theme'=>$add_subtheme,'created_by'=>$this->entity_user_id),0,100000000,array('id'=>'desc'))['data'];
      // debug($data ["sub_category_details"]);exit;
      // echo $this->db->last_query();exit;
      // debug($cnt);exit;
      if(count($data ["sub_theme_details"])>0){
      	$this->session->set_flashdata('message',UL0098 );
      }else{
      $query = "insert into sub_theme_activity set activity_theme_id='$id', sub_theme='$add_subtheme', status=1,created_by = '".$GLOBALS['CI']->entity_user_id."'  ";        
        //echo $query; //exit;
      $return = $this->Activity_Model->query_run($query);
      $this->session->set_flashdata('message', UL0014);
  	  }
      redirect('activity/add_subtheme/'.$id); 
                    
    }
    public function add_sub_category($id) {
     if ($id != '') {
			$data ['id']=$id;
			$data ['pack_data'] = $this->Activity_Model->get_pack_id ( $id );
			$data ["sub_category_details"] = $this->custom_db->single_table_records('activity_sub_category','*',array('activity_type_id'=>$id,'created_by'=>$this->entity_user_id),0,100000000,array('id'=>'desc'))['data'];
			// debug($page_data ["sub_category_details"] );exit;
			$this->template->view ( 'activity/add_sub_category', $data );
		} else {
			$this->template->view ( 'activity/add_sub_category',$data );
		}
    }
     public function add_sub_category_save() {
      $data = $this->input->post();
    // debug($data); exit;
      $id             = $data['id'];
      $activity_type             = $data['activity_type'];
      $add_subcategory  = sql_injection($data['add_subcategory']);
      $data ["sub_category_details"] = $this->custom_db->single_table_records('activity_sub_category','*',array('activity_type_id'=>$id,'activity_sub_category'=>$add_subcategory,'created_by'=>$this->entity_user_id))['data'];
      // $cnt = count($data ["sub_category_details"]);
      // echo $this->db->last_query();exit;
      // debug($cnt);exit;
      if(count($data ["sub_category_details"])>0){
      	$status = 'Sub Category Already Exist';
      	$this->session->set_flashdata('message',UL0098 );
      }else{
      $query = "insert into activity_sub_category set activity_type_id='$id', activity_sub_category='$add_subcategory', status=1, created_by = '".$GLOBALS['CI']->entity_user_id."'  ";        
        //echo $query; //exit;
      $return = $this->Activity_Model->query_run($query);
      $this->session->set_flashdata('message', UL0014);
  		}
      redirect('activity/add_sub_category/'.$id); 
                    
    }
     public function edit_sub_category_save() {
      $data = $this->input->post();

    // debug($data); exit;
      $id             = $data['id'];
      $activity_id             = $data['activity_id'];
      $activity_type             = $data['activity_type'];
      $add_subcategory  = sql_injection($data['add_subcategory']);
      $query = "update activity_sub_category set activity_sub_category='$add_subcategory' where id='$id'";        
      $return = $this->Activity_Model->query_run($query);
      $this->session->set_flashdata('message', UL0013);
      redirect('activity/add_sub_category/'.$activity_id); 
                    
    }
function manage_activity_details_theme()
    {
    $operation = $this->input->post("operation");
    $checkval=$this->input->post("checkval");
    $theme_tbl=$this->input->post("theme_tbl");
    // debug($operation);debug($checkval);exit;
    if(!empty($checkval))
    {
      for($i=0;$i<sizeof($checkval);$i++)
      {
      $checkval1=$checkval[$i];
      
        switch ($operation) {
          case 'deactivate' :
          $status = 0;
          
          $this->Activity_Model->record_activation($theme_tbl,$checkval1,$status);
          $this->session->set_flashdata('message', UL0013);
          $message = 'Successfully Deactivated';
          break;
          case 'activate' :
          $status = 1;
         $this->Activity_Model->record_activation($theme_tbl,$checkval1,$status);
         $this->session->set_flashdata('message', UL0013);
          $message = 'Successfully Activated';
            break;
          case 'delete' :
              // echo "d";exit;


              $query = "delete from ".$theme_tbl." where id='$checkval1'";              
              $result = $this->db->query($query);       
              $this->session->set_flashdata('message', UL0099);       
             $message = 'Successfully Deleted';
            break;
              
        }
      }
    }
      echo $message;
    // $this->template->view('voucher/visa_voucher', $page_data);
  }
  public function health_instructions() {   
       $health_instructions = $this->Activity_Model->health_instructions();
    //debug($tour_subtheme); exit;    
       $page_data['health_instructions'] = $health_instructions;
       $this->template->view('activity/health_instructions',$page_data);
     }
     public function health_instructions_save() {
      $data = $this->input->post();
    // debug($data); exit;
      $health_instructions   = sql_injection($data['health_instructions']);
      $query = "insert into health_instructions set health_instructions='$health_instructions', status=1,created_by = '".$GLOBALS['CI']->entity_user_id."' ";        
        //echo $query; //exit;
      $return = $this->Activity_Model->query_run($query);
      if($return)
        {   $this->session->set_flashdata('message', UL0014);
    		redirect('activity/health_instructions/'); }
      else
        { echo $return; exit; }              
    }
    public function activation_health_instructions($id,$status) {
      $return = $this->Activity_Model->record_activation('health_instructions',$id,$status);
      if($return){redirect('activity/health_instructions');} 
      else { echo $return;} 
    }
    public function delete_health_instructions($id) {
      $return = $this->Activity_Model->record_delete('health_instructions',$id);
      $this->session->set_flashdata('message', UL0099);
      redirect('activity/health_instructions/');
    }
    public function edit_health_instructions($id) {
      $health_instructions_details = $this->Activity_Model->table_record_details('health_instructions',$id);
    // debug($activity_subtheme_details); exit;      
      $page_data['health_instructions_details'] = $health_instructions_details;
    // debug($page_data); exit;
      $this->template->view('activity/edit_health_instructions',$page_data);
    }
    public function edit_health_instructions_save() {
      $data = $this->input->post();
    // debug($data); exit;
      $id             = $data['id'];
      $health_instructions  = sql_injection($data['health_instructions']);
      $query = "update health_instructions set health_instructions='$health_instructions' where id='$id'";        
        //echo $query; //exit;
      $return = $this->Activity_Model->query_run($query);
      $this->session->set_flashdata('message', UL0013);
      redirect('activity/edit_health_instructions/'.$id); 
                    
    }

function manage_activity_health_instructions()
    {
    $operation = $this->input->post("operation");
    $checkval=$this->input->post("checkval");
    // debug($operation);debug($checkval);exit;
    if(!empty($checkval))
    {
      for($i=0;$i<sizeof($checkval);$i++)
      {
      $checkval1=$checkval[$i];
      
        switch ($operation) {
          case 'deactivate' :
          $status = 0;
          
          $this->Activity_Model->record_activation('health_instructions',$checkval1,$status);
          $message = 'Successfully Deactivated';
          break;
          case 'activate' :
          $status = 1;
         $this->Activity_Model->record_activation('health_instructions',$checkval1,$status);
          $message = 'Successfully Activated';
            break;
          case 'delete' :
              // echo "d";exit;


              $query = "delete from health_instructions where id='$checkval1'";              
              $result = $this->db->query($query);              
             $message = 'Successfully Deleted';
            break;
              
        }
      }
    }
      echo $message;
    // $this->template->view('voucher/visa_voucher', $page_data);
  }
  function get_sub_theme($id){
  	 $page_data['id'] = $id;
  	 $page_data['get_theme_details'] = $this->Activity_Model->get_theme_details($id);
  	 // debug($page_data);exit;
  	 echo $this->template->isolated_view('activity/subthemes_activity',$page_data); 
  }
   function get_activity_type($id){
  	 $page_data['id'] = $id;
  	 // debug($page_data);exit;
  	 $data['sub_category'] = $this->Activity_Model->get_sub_type_details($id);
  	 // echo $this->db->last_query();exit;
  	 // debug($data['sub_category']);exit;
  	 echo $this->template->isolated_view('activity/subtype_activity',$data); 
  }
  function repeated_no_time()
    {
    $hms = $this->input->post("hms");
    $hms = date("H:i", strtotime($hms));
    $tim_min = $this->hours_minutes($hms);
    echo $tim_min;
	}
function hours_minutes($hours) 
{ 
    $minutes = 0; 
    if (strpos($hours, ':') !== false) 
    { 
        // Split hours and minutes. 
        list($hours, $minutes) = explode(':', $hours); 
    } 
    return $hours * 60 + $minutes; 
} 
function repeated_end_time()
    {
    $duration_hr = $this->input->post("duration_hr");
    $duration_min = $this->input->post("duration_min");
    $start_date = $this->input->post("start_date");
    $hms = $duration_hr.':'.$duration_min;
    $tim_min = $this->hours_minutes($hms);
    $start_date1 = date("H:i", strtotime($start_date));
    $startDate = $this->hours_minutes($start_date1);
    $result = $startDate+$tim_min;
    // debug($tim_min);debug($start_date);debug($result);exit;
    echo $result;
	}
  function repeated_duration()
    {
    $dur_hr = $this->input->post("dur_hr");
    $dur_min = $this->input->post("dur_min");
    // debug($dur_hr);debug($dur_min);exit;
    $hms = $dur_hr.':'.$dur_min;
    $tim_min = $this->hours_minutes($hms);
    echo $tim_min;
	}
	function repeated_duration_sub()
    {
    $arr = $this->input->post("arr");
    $total_time = 0;
    for ($i=0; $i < count($arr) ; $i++) { 
    	$time_hr = $arr[$i];
    	$tim_min = $this->hours_minutes($time_hr);
    	$total_time += $tim_min;
    }
    echo $total_time;
	}
	 //Nationality Master

  function nationality_region()
  {
     $nationality_region = $this->Activity_Model->nationality_region();
     // debug($nationality_region); exit;    
     $page_data['nationality_region'] = $nationality_region;
     $this->template->view('activity/nationality/nationality_region',$page_data);
  }
  function region_save()
  {
     $data = $this->input->post();
      $tour_region   = sql_injection($data['tour_region']);
      $check_availibility = $this->Activity_Model->check_region_exist_all($tour_region);
      if(!$check_availibility)
      {
        $query = "insert into all_nationality_region set name='$tour_region', status=1, module='activity', created_by=".$this->entity_user_id." ";        
            //echo $query; //exit;
        $return = $this->Activity_Model->query_run($query);
        if($return)
          {   $this->session->set_flashdata('message', UL0014);
        redirect('activity/nationality_region'); }
        else
          { echo $return; exit; } 
      }
      else
      {
       $this->session->set_flashdata('region_msg','Region is already exist');
       redirect('activity/nationality_region');
     }

  }
  public function activation_nationality_region($id,$status) {
    $return = $this->Activity_Model->record_activation('all_nationality_region',$id,$status);
    // debug($return);exit();
    if($return){redirect('activity/nationality_region');} 
    else { echo $return;} 
  }
  public function edit_nationality_region($id)
  {
     $region_details = $this->Activity_Model->table_record_details('all_nationality_region',$id);

      $page_data['region_details'] = $region_details;
        // debug($page_data); exit;
      $this->template->view('activity/nationality/edit_nationality_region',$page_data);
  }


  public function edit_nationality_region_save() {
    $data = $this->input->post();
      //debug($data); exit;
    $id             = $data['id'];
    $tour_region  = sql_injection($data['tour_region']);
    $query = "update all_nationality_region set name='$tour_region' where id='$id'";        
          //echo $query; //exit;
    $return = $this->Activity_Model->query_run($query);
    if($return)
      {   
      $this->session->set_flashdata('message', UL0013);
      redirect('activity/edit_nationality_region/'.$id); }
    else
      { echo $return; exit; }              
  }


  public function delete_nationality_region($id) {
    $return = $this->Activity_Model->record_delete('all_nationality_region',$id);
    if($return){
      $this->session->set_flashdata('message', UL0099);
      redirect('activity/nationality_region');} 
    else { echo $return;} 
  }
  public function view_notionality_country() 
    {
        
      $page_data ['notionality_country'] = $this->Activity_Model->get_nationalityCountryList();
       // debug($page_data);exit;
      $this->template->view ('activity/nationality/view_notionality_country', $page_data );
    }


  public function nationality_country($id = '') {
      //error_reporting(E_All);
      $page_data ['nationality_regions'] = $this->Activity_Model->get_nationality_regions();
      $page_data['country_list']=$this->Activity_Model->get_hb_country_list();
    $page_data ['edit_notionality_country']='';
      // debug($data ['tours_continent']);exit();
      if ($id != '') 
      {
        $page_data ['id']=$id;
        $page_data ['edit_notionality_country'] = $this->Activity_Model->get_nationalityCountryList($id);
        // debug($page_data ['edit_notionality_country']);exit;
        $this->template->view ( 'activity/nationality/notionality_country', $page_data );
      } else {
        $this->template->view ( 'activity/nationality/notionality_country',$page_data );
      }
    }

 
    public function save_nationalityCountries() 
    {
     
         $data = $this->input->post();
        
         $pack_id=$data['pack_id'];
         $hb_country_list=$this->Activity_Model->get_hb_country_list();

         $except_countryIds =array();
         $except_countryCodes =array();
         $except_countryNames  =array();

         $include_countryIds =array();
         $include_countryCodes =array();
         $include_countryNames =array();
         // debug($data['tours_country']);
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
           'module' => 'activity', 
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
        $repeat_nationality = $this->Activity_Model->check_nationality_duplicate($tours_continent,$package_name);
       if(empty($repeat_nationality)){
       if($pack_id>0)
       {
         $price_cat = $this->Activity_Model->update_price_cat($data_ins,$pack_id);
       }
       else
       {
         $price_cat = $this->Activity_Model->add_price_cat($data_ins);

       }
      // debug($this->db->last_query());exit;

       if($price_cat)
       {
           $this->session->set_flashdata(array('message' => 'UL0014', 'type' => SUCCESS_MESSAGE));
           redirect ( 'activity/view_notionality_country' );
       }
       else
       {
           $this->session->set_flashdata(array('message' => 'UL0098', 'type' => ERROR_MESSAGE));
       }
     }else{
      $this->session->set_flashdata(array('message' => 'UL0098', 'type' => ERROR_MESSAGE));redirect ( 'activity/view_notionality_country' );
     }
 }


// Nationality Masters Code ends

     function pre_cancellation($app_reference, $booking_source='',$status,$module='')
  {
    $condition = array();
    if (empty($app_reference) == false && empty($booking_source) == false) {
      $page_data = array();
      $booking_details = $this->Activity_Model->get_booking_details_activity($app_reference, $booking_source);
      // debug($booking_details);exit;
      if ($booking_details['status'] == SUCCESS_STATUS) {
        $this->load->library('booking_data_formatter');
        //Assemble Booking Data
        $assembled_booking_details = $this->booking_data_formatter->format_activity_booking_data($booking_details,$this->current_module);
        $package_id = $assembled_booking_details['data']['booking_details'][0]['package_type'];
        // debug($assembled_booking_details);exit;
        $table_data = $this->Activity_Model->get_package_id($package_id);
        // debug($table_data);exit;
        $page_data['data'] = $assembled_booking_details['data'];
        $page_data['data']['activity_info'] = $table_data;
        $page_data['module'] = $module;
        $this->template->view('activity/pre_cancellation', $page_data);
      } else {
        redirect('security/log_event?event=Invalid Details');
      }
    } else {
      redirect('security/log_event?event=Invalid Details');
    }
  }
 public function cancel_full_booking($app_reference)
  {
    // error_reporting(E_ALL);ini_set('display_error', 'on');
    $this->load->model('custom_db');
    $this->custom_db->update_record('activity_booking_details',array('status'=>'CANCELLED','final_cancel_date'=>date("Y-m-d h:i:sa")),array('app_reference'=>$app_reference));  
    $this->custom_db->update_record('activity_booking_passenger_details',array('status'=>'CANCELLED'),array('app_reference'=>$app_reference)); 
    $this->custom_db->update_record('activity_booking_transaction_details',array('status'=>'CANCELLED'),array('app_reference'=>$app_reference));   
    $condition[]=array(
      'BD.app_reference','=','"'.$app_reference.'"'
      );
    $page_data['app_reference'] = $app_reference;
    $page_data['status'] = 'CANCELLED';
    $booking_details = $this->Activity_Model->b2c_holiday_report($condition);
    $this->load->library ( 'provab_mailer' );
    foreach ($booking_details['data'] as $key => $data) {
     $enquiry_reference_no=$key;
   }
   $voucher_data = $data;
   $attributes = json_decode($data['booking_details']['attributes'], true);
   $user_attributes = json_decode($data['booking_details']['user_attributes'], true);
   // $voucher_data ['activity_itinerary_dw']   = $this->Activity_Model->tours_itinerary_dw($attributes['tour_id'],$attributes['departure_date']);
   $email = $user_attributes['email'];
   $voucher_data['menu'] = false;
   $sdata['app_reference'] = $app_reference;
   // debug($voucher_data);die('false');
   // $sdata['app_reference'] = $voucher_data['booking_details']['app_reference'];
  // $sdata['user_name'] = ucwords($voucher_data['pax_details'][0]['pax_first_name']);
  // debug($sdata);die('false');

   // $mail_template =$this->template->isolated_view('voucher/finalcancellationtemplate',$sdata);  Uncomment this line after mail configuration done  

   // die('30');
    // echo $mail_template; exit();
  /* $this->load->library ( 'provab_pdf' );
   $pdf = $this->provab_pdf->create_pdf($mail_template,'F', $app_reference);*/
   // echo $pdf;die;
   // debug($email);die;
   // $email = 'pankajprovab212@gmail.com';


   // $email_subject = "Your booking with Voyages - Booking Reservation Code ".$sdata['app_reference']." has been cancelled.";
   // $this->provab_mailer->send_mail(21, $email, $email_subject, $mail_template,false); Uncomment this 2 lines after mail configuration done  


   // debug($app_reference);die;
  $this->template->view('activity/cancellation_details',$page_data);
 }




	public function change_publish_status() 
	{
		$package_id = $this->input->post('id');
		$status = $this->input->post('status');
		$this->Activity_Model->change_publish_status ( $package_id, $status );
		
	}



	public function published_status_approve() 
	{
		$package_id = $this->input->post('id');
		$status = $this->input->post('status');
		$this->Activity_Model->published_status_approve ( $package_id, $status );
		
	}




}