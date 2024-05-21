<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start();
// error_reporting(0);
class Roomrate extends CI_Controller {	

	public function __construct(){
		parent::__construct();	
		
		$this->load->model('roomrate_model');
		$this->load->model('seasons_model');
		$this->load->model('hotels_model');
		
	}

	//added
		function update_nationality($rate_id1)
		{
	    
	  //  debug($this->uri->segment(4));exit;
	  $redirection_url=$this->uri->segment(4);
	  $hotel_room_rate_id=$this->uri->segment(5);
	//  debug($hotel_room_rate_id);exit;
		$rate_id 	= json_decode(base64_decode($rate_id1));
		if($rate_id != ''){
			$this->roomrate_model->update_nationality($rate_id,$hotel_room_rate_id);
		}
		
		redirect('roomrate/list_room_rate/'.$redirection_url,'refresh');
	//	redirect('hotels/hotel_crs_list','refresh');
	}
	
	function get_room_data($hotel_id){
		
		$options = '';$options1 = '';		
		if($hotel_id != ''){
			$hotels 			= $this->hotels_model->getHomePageSettings();
			$result 			= $this->roomrate_model->get_room_list_by_hotel($hotel_id);
			if($result!=''){
			foreach($result as $row){ 
				$options .= '<option value="'.$row->hotel_room_details_id.'">'.$row->room_type_name.'</option>';				
			}
			}
				
			
			//~ $seasons_list 	   	= $this->Seasons_Model->get_seasons_list("",$hotel_id,$hotels);
			//~ if($seasons_list!=''){
			//~ foreach($seasons_list as $seasons){ 
				//~ $options1 .= '<option value="'.$seasons->seasons_details_id.'">'.$seasons->seasons_name.'</option>';				
			//~ }}		
		}
		echo $options;
		//~ echo json_encode(array(
            //~ 'options' 		=> $options,
            //~ 'options1' 		=> $options1
        //~ ));
		//echo $options;exit;
	}
	
	function get_room_data1($hotel_id){
		
		$options = '';$options1 = '';		
		if($hotel_id != ''){
			$hotels 			= $this->hotels_model->getHomePageSettings();
			$result 			= $this->roomrate_model->get_room_list_by_hotel($hotel_id);
			if($result!=''){
			foreach($result as $row){ 
				$options .= '<option value="'.$row->hotel_room_details_id.'">'.$row->room_type_name.'</option>';				
			}
			}
				
			
			$seasons_list 	   	= $this->seasons_model->get_seasons_list("",$hotel_id,$hotels);
			if($seasons_list!=''){
			foreach($seasons_list as $seasons){ 
				$options1 .= '<option value="'.$seasons->seasons_details_id.'">'.$seasons->seasons_name.'</option>';				
			}}		
		}
		
		echo json_encode(array(
            'options' 		=> $options,
            'options1' 		=> $options1
        ));
		//~ //echo $options;exit;
	}
	function get_season_date($id){
		//echo $id;exit;
		$result 			= $this->roomrate_model->get_season_date_by_seasonid($id);
		$from = $result->seasons_from_date;
		$new_fromdate = date("d/m/Y", strtotime($from));
		$to = $result->seasons_to_date;
		$new_todate = date("d/m/Y", strtotime($to));
		$finaloutput=$new_fromdate .' '.'-'.' '. $new_todate;
		echo json_encode($finaloutput);
	}
	
	function get_extra_bed($hotel_id){
		$options = '';		
		if(!empty($hotel_id)){
			$result = $this->roomrate_model->get_extra_bed($hotel_id);
			if($result!=''){
			foreach($result as $row){ 
				$options= $row->extra_bed;				
			}}		
		}
		echo $options;exit;
	}
	public function list_room_rate($room=""){
		

		$hotels 						= $this->hotels_model->getHomePageSettings();
		// debug($hotels);exit;
		//$hotels['room_rate_list'] 	   	= $this->Roomrate_Model->get_room_rate_list("", $hotels);
		if(!empty($room)){
		  //  debug("if");exit;
			$rooms  = base64_decode($room);
			// $rooms  = ($room);
			// echo $rooms;exit;
			$hotels['room_rate_list'] 	   	= $this->roomrate_model->new_get_room_rate_list_byHotelName("", $hotels,$rooms);
		} else {
		  //  debug("else");exit;
			$hotels['room_rate_list'] 	   	= $this->roomrate_model->new_get_room_rate_list("", $hotels);
		}
		$hotels['GET'] = $room;
		// debug($hotels);exit;
		$hotels_li=json_decode(json_encode($hotels['room_rate_list'][0]),true);
		$wizard_status=$this->hotels_model->get_wizard($hotels_li['hotel_details_id']);
		if (!isset($wizard_status[0]['wizard_status'])) {
			$wizard_status[0]['wizard_status'] = "step1,step2,step3,step4,step5";
		}
		// debug($wizard_status);die;
		
		$wizard_status = $wizard_status[0]['wizard_status'] ;
		$wiz=explode(',', $wizard_status);
			foreach ($wiz as $wkey => $wvalue) {
				$w[]=$wvalue;
			}
		$hotels['wizard_status']=$w;
		$hotels['hotel_name']=$rooms;
		$hotels['hotel_id']=$hotels_li['hotel_details_id'];
		// echo "<pre>";print_r($hotels);exit();
		//debug($hotels);exit;
		$this->template->view('hotel/roomrate/room_rate_list',$hotels);
	}

	public function hotel_list_room_rate($room=""){
		

		$hotels 						= $this->General_Model->getHomePageSettings();
		//$hotels['room_rate_list'] 	   	= $this->Roomrate_Model->get_room_rate_list("", $hotels);
		if(!empty($room)){
			$rooms  = base64_decode($room);
			//$rooms  = ($room);
			//echo $rooms;exit;
			$hotels['room_rate_list'] 	   	= $this->Roomrate_Model->new_hotel_get_room_rate_list_byHotelName("", $hotels,$rooms);
		} else {
			$hotels['room_rate_list'] 	   	= $this->Roomrate_Model->new_hotel_get_room_rate_list("", $hotels);
		}
		$hotels['GET'] = $room;
		//echo "<pre>";print_r($hotels);exit();
		$this->load->view('hotel/roomrate/hotel_room_rate_list',$hotels);
	}
	
	function add_room_rate($segment1=''){ 
		if(count($_POST) > 0){			
				// echo "<pre>";print_r($_POST);exit();
				$this->form_validation->set_rules('hotel_details_id', 'Hotel Id', 'required|numeric|callback_check_drop');

				$this->form_validation->set_rules('room_details_id', 'Room Type', 'required|numeric|callback_check_drop');
				$this->form_validation->set_rules('seasons_details_id', 'Season', 'required|numeric|callback_check_drop');
				
				$this->form_validation->set_rules('date_rane_rate', 'Date Range', 'callback_check_daterange');										
				
				/*
				$child_price_a = $this->input->post('child_price_a');
				if(isset($child_price_a)){
					$this->form_validation->set_rules('child_price_a', 'Child price', 'numeric|min_length[2]|max_length[10]');
				}	
				$child_price_b = $this->input->post('child_price_b');
				if(isset($child_price_b)){
					$this->form_validation->set_rules('child_price_b', 'Child price', 'numeric|min_length[2]|max_length[10]');
				}	
				$child_price_c = $this->input->post('child_price_c');
				if(isset($child_price_c)){
					$this->form_validation->set_rules('child_price_c', 'Child price', 'numeric|min_length[2]|max_length[10]');
				}	
				$child_price_d = $this->input->post('child_price_d');
				if(isset($child_price_d)){
					$this->form_validation->set_rules('child_price_d', 'Child price', 'numeric|min_length[2]|max_length[10]');
				}
				$child_price_e = $this->input->post('child_price_e');
				if(isset($child_price_e)){	
					$this->form_validation->set_rules('child_price_e', 'Child price', 'numeric|min_length[2]|max_length[10]');
				}					
				*/
				$child_bed = $this->input->post('child_extra_bed_price');
				
				if(isset($child_bed)) 
				{
					$this->form_validation->set_rules('child_extra_bed_price', 'Child bed price', 'numeric|min_length[2]|max_length[10]');
					$this->form_validation->set_rules('adult_extra_bed_price', 'Adult bed price', 'numeric|min_length[2]|max_length[10]');					
				}

				$this->form_validation->set_rules('week_sgl_price', 'Single Room Price', 'required|numeric|min_length[2]|max_length[10]');
				$this->form_validation->set_rules('week_dbl_price', 'Double Room Price', 'numeric|max_length[10]');
				$this->form_validation->set_rules('week_trp_price', 'Triple Room Price', 'numeric|max_length[10]');
				//$this->form_validation->set_rules('week_quad_price', 'Quad Room Price', 'numeric|max_length[10]');
				//$this->form_validation->set_rules('week_hex_price', 'Hex Room Price', 'numeric|max_length[10]');

				$week_bedroom_price = $this->input->post('week_bedroom_price');
				if(isset($week_bedroom_price)){
					$this->form_validation->set_rules('week_bedroom_price', 'Bed Room Price', 'numeric|max_length[10]');
				}

				if($this->input->post('week_end_select') == "1"){
					$this->form_validation->set_rules('weekend_sgl_price', 'Single Room Price', 'required|numeric|min_length[2]|max_length[10]');
  					$this->form_validation->set_rules('weekend_dbl_price', 'Double Room Price', 'numeric|max_length[10]');
  					$this->form_validation->set_rules('weekend_tpl_price', 'Triple Room Price', 'numeric|max_length[10]');
  					//$this->form_validation->set_rules('weekend_quad_price', 'Quad Room Price', 'numeric|max_length[10]');
  					//$this->form_validation->set_rules('weekend_hex_price', 'Hex Room Price', 'numeric|max_length[10]');
					$weekend_bedroom_price = $this->input->post('weekend_bedroom_price');
					if(isset($weekend_bedroom_price)){
						$this->form_validation->set_rules('weekend_bedroom_price', 'Bed Room Price', 'numeric|max_length[10]');				
					}
				}
//            echo "<pre>";print_r($_POST);exit();
//				debug($this->form_validation->run());
//				exit;



				 // try{

					// debug($_POST);exit();
					$this->roomrate_model->new_add_room_rate($_POST);
					

					//if(isset($_GET['room']) && !empty($_GET['room']))
					if(!empty($segment1))
					{
						//redirect('roomrate?room='.$this->input->get('room') ,'refresh');	
						// $this->hotels_model->update_wizard_status($hotel_id,'step5');
						redirect('roomrate/list_room_rate/'.$segment1 ,'refresh');
					} else {
						redirect('roomrate/list_room_rate','refresh');	
					}
							    	
			      /* } catch(Exception $e) {
				     //$this->General_Model->rollback_transaction();
				     return $e;
				} */    

		}else{
			$hotels 						= $this->hotels_model->getHomePageSettings();
			$hotels['hotels_list'] 	   		= $this->hotels_model->get_hotel_crs_list("", $hotels);
			//echo '<pre>'; print_r($hotels['hotels_list']); exit();
			// $hotels['seasons_list'] 	   	= $this->Seasons_Model->get_seasons_list("",$hotels);
			$hotels['settings'] 			= $this->hotels_model->get_hotel_settings_list();
			$hotels['country'] = $this->hotels_model->get_country_details_hotel("");	
			$hotels['GET'] = str_replace('"', '', (base64_decode($segment1)));
            $hotels['currency'] = $this->hotels_model->get_currency_list();
			//echo "<pre>";print_r($hotels['GET']);die;
			//added on 12-4-2020
			$hotels ["country_nationality"] = $this->hotels_model->get_country_list();
			$this->template->view('hotel/roomrate/room_rate',$hotels);
		}
	} 
	
		 public function get_currency($country_id){
 
 	    $data=$this->hotels_model->get_currency($country_id);
		// debug($data);exit;
		echo json_encode ( array (
				'result' => $data[0] 
		) );
		exit ();
 }
	
/*	function delete_roomrate($rate_id1){
		$rate_id 	= json_decode(base64_decode($rate_id1));
		if($rate_id != ''){
			$this->roomrate_model->delete_roomrate($rate_id);
		}
		redirect('hotels/hotel_crs_list','refresh');
	}*/
	function delete_roomrate($rate_id1){
	    
	  //  debug($this->uri->segment(4));exit;
	  $redirection_url=$this->uri->segment(4);
	  $hotel_room_rate_id=$this->uri->segment(5);
	//  debug($hotel_room_rate_id);exit;
		$rate_id 	= json_decode(base64_decode($rate_id1));
		if($rate_id != ''){
			$this->roomrate_model->delete_roomrate($rate_id,$hotel_room_rate_id);
		}
		
		redirect('roomrate/list_room_rate/'.$redirection_url,'refresh');
	//	redirect('hotels/hotel_crs_list','refresh');
	}


/*	function inactive_roomrate_old($rate_id1,$hotelname=''){
		$rate_id 	= json_decode(base64_decode($rate_id1));
		if($rate_id != ''){
			$this->roomrate_model->inactive_roomrate($rate_id);
		}
		if(isset($hotelname) && $hotelname!='')
			redirect('roomrate/list_room_rate/'.$hotelname,'refresh');
		else
			redirect('roomrate/list_room_rate','refresh');
	}*/
		function inactive_roomrate($rate_id1,$hotelname=''){
		$rate_id 	= json_decode(base64_decode($rate_id1));
	$hotel_room_rate_id=$this->uri->segment(5);
		if($rate_id != ''){
			$this->roomrate_model->inactive_roomrate($rate_id,$hotel_room_rate_id);
		}
		if(isset($hotelname) && $hotelname!='')
			redirect('roomrate/list_room_rate/'.$hotelname,'refresh');
		else
			redirect('roomrate/list_room_rate','refresh');
	}
	
/*	function active_roomrate($rate_id1,$hotelname=''){
		$rate_id 	= json_decode(base64_decode($rate_id1));
		if($rate_id != ''){
			$this->roomrate_model->active_roomrate($rate_id);
		}
		if(isset($hotelname) && $hotelname!='')
			redirect('roomrate/list_room_rate/'.$hotelname,'refresh');
		else
			redirect('roomrate/list_room_rate','refresh');
	}
	*/
	function active_roomrate($rate_id1,$hotelname=''){
		$rate_id 	= json_decode(base64_decode($rate_id1));
		$hotel_room_rate_id=$this->uri->segment(5);
		if($rate_id != ''){
			$this->roomrate_model->active_roomrate($rate_id,$hotel_room_rate_id);
		}
		if(isset($hotelname) && $hotelname!='')
			redirect('roomrate/list_room_rate/'.$hotelname,'refresh');
		else
			redirect('roomrate/list_room_rate','refresh');
	}
	
	function edit_roomrate($rate_id1,$room_rate_id_new="")
	{ 
		$mixed 	= json_decode(base64_decode($rate_id1));
		//echo '<pre>';print_r($mixed);exit;
		$rate_id = $mixed[1];
		if($rate_id != ''){			
			$hotels 						= $this->hotels_model->getHomePageSettings();
			$hotels['hotels_list'] 	   		= $this->hotels_model->get_hotel_crs_list("", $hotels);
			//$hotels['room_rate_list'] 	   	= $this->Roomrate_Model->get_room_rate_list($rate_id, $hotels);
		//	$hotels['room_rate_list'] 	   	= $this->roomrate_model->new_get_room_rate_list($rate_id, $hotels);
		$hotels['room_rate_list'] 	   	= $this->roomrate_model->new_get_room_rate_list($rate_id, $hotels,$room_rate_id_new);
			$hotels['settings'] 			= $this->hotels_model->get_hotel_settings_list();
			//$hotels['block_out_date']  		= $this->Roomrate_Model->new_get_blockout_date($rate_id);

			$hotels['country'] = $this->hotels_model->get_country_details_hotel("");	
			$hotels['room_rate_id'] = $rate_id;
			$hotels['hotel_details_id'] = $hotels['room_rate_list'][0]->hotel_details_id;
			$hotels['room_details_id'] = $hotels['room_rate_list'][0]->hotel_room_type_id;
			$hotels['seasons_details_id'] = $hotels['room_rate_list'][0]->seasons_details_id;
			$hotels['cancellation_policy'] = $hotels['room_rate_list'][0]->room_rate_cancellation_policy;
			$hotels['room_rate_type'] = $hotels['room_rate_list'][0]->rate_type;
			$hotels['week_end_select'] = $hotels['room_rate_list'][0]->weekend_price;
			$hotels['date_rane_rate'] =  date('d/m/Y',strtotime($hotels['room_rate_list'][0]->from_date))." - ".date('d/m/Y',strtotime($hotels['room_rate_list'][0]->to_date)) ;
			$hotels['promotion'] = $hotels['room_rate_list'][0]->room_promotion;
			$hotels['include_country'] = explode(",",$hotels['room_rate_list'][0]->included_country);
			$hotels['exclude_country'] = explode(",",$hotels['room_rate_list'][0]->excluded_country);
			//debug($hotels);die;
				$hotels['room_rate_id_new'] = $room_rate_id_new;

			if($hotels['block_out_date'] != ""){
				$hotels['block_out_date_rane_rate'] = array();
				$hotels['block_out_date_id'] = array();
				for($loop =0;$loop < sizeof($hotels['block_out_date']);$loop++ ){
					$hotels['block_out_date_rane_rate'][$loop] = date('m/d/Y',strtotime($hotels['block_out_date'][$loop]->from_date))." - ".date('m/d/Y',strtotime($hotels['block_out_date'][$loop]->to_date)) ;					
					$hotels['hotel_blockout_id'][$loop] = $hotels['block_out_date'][$loop]->hotel_blockout_id;
				}
			}

					$hotels['gst'] = $hotels['room_rate_list'][0]->gst_tax;
					$hotels['service_charge'] = $hotels['room_rate_list'][0]->service_tax;
					$hotels['child_price'] = $hotels['room_rate_list'][0]->room_child_price_a."_".$hotels['room_rate_list'][0]->room_child_price_b."_".$hotels['room_rate_list'][0]->room_child_price_c."_".$hotels['room_rate_list'][0]->room_child_price_d."_".$hotels['room_rate_list'][0]->room_child_price_e;
					$hotels['child_extra_bed_price'] = $hotels['room_rate_list'][0]->child_extra_bed_price;
					$hotels['adult_extra_bed_price'] = $hotels['room_rate_list'][0]->adult_extra_bed_price;

					$hotels['week_sgl_price'] = $hotels['room_rate_list'][0]->single_room_price;														
					$hotels['week_single_adult_bf'] = $hotels['room_rate_list'][0]->single_adult_bk;
					$hotels['week_single_child_bf'] = $hotels['room_rate_list'][0]->single_child_bk;

					$hotels['week_dbl_price'] = $hotels['room_rate_list'][0]->double_room_price;														
					$hotels['week_double_child_bf'] = $hotels['room_rate_list'][0]->double_child_bk;
					$hotels['week_double_adult_bf'] = $hotels['room_rate_list'][0]->double_adult_bk;

					$hotels['week_trp_price'] = $hotels['room_rate_list'][0]->triple_room_price;														
					$hotels['week_trp_adult_bf'] = $hotels['room_rate_list'][0]->triple_adult_bk;
					$hotels['week_trp_child_bf'] = $hotels['room_rate_list'][0]->triple_child_bk;

					/*$hotels['week_quad_price'] = $hotels['room_rate_list'][0]->quad_room_price;														
					$hotels['week_quad_adult_bf'] = $hotels['room_rate_list'][0]->quad_adult_bk;
					$hotels['week_quad_child_bf'] = $hotels['room_rate_list'][0]->quad_child_bk;

					$hotels['week_hex_price'] = $hotels['room_rate_list'][0]->hex_room_price;														
					$hotels['week_hex_adult_bf'] = $hotels['room_rate_list'][0]->hex_adult_bk;
					$hotels['week_hex_child_bf'] = $hotels['room_rate_list'][0]->hex_child_bk;*/
					
					$hotels['week_bedroom_price'] = $hotels['room_rate_list'][0]->adult_room_bed_price;
					$hotels['week_bedroom_adult_bf'] = $hotels['room_rate_list'][0]->room_bed_adult_bk;
					$hotels['week_bedroom_child_bf'] = $hotels['room_rate_list'][0]->room_bed_child_bk;
					

					$hotels['weekend_sgl_price'] = $hotels['room_rate_list'][0]->weekend_single_room_price;														
					$hotels['weekend_single_adult_bf'] = $hotels['room_rate_list'][0]->weekend_single_adult_bk;
					$hotels['weekend_single_child_bf'] = $hotels['room_rate_list'][0]->weekend_single_child_bk;

					$hotels['weekend_dbl_price'] = $hotels['room_rate_list'][0]->weekend_double_room_price;														
					$hotels['weekend_double_adult_bf'] = $hotels['room_rate_list'][0]->weekend_double_adult_bk;
					$hotels['weekend_double_child_bf'] = $hotels['room_rate_list'][0]->weekend_double_child_bk;

					$hotels['weekend_tpl_price'] = $hotels['room_rate_list'][0]->weekend_triple_room_price;														
					$hotels['weekend_triple_adult_bf'] = $hotels['room_rate_list'][0]->weekend_triple_room_price;
					$hotels['weekend_triple_child_bf'] = $hotels['room_rate_list'][0]->weekend_triple_child_bk;

					/*$hotels['weekend_quad_price'] = $hotels['room_rate_list'][0]->weekend_quad_room_price;														
					$hotels['weekend_quad_adult_bf'] = $hotels['room_rate_list'][0]->weekend_quad_adult_bk;
					$hotels['weekend_quad_child_bf'] = $hotels['room_rate_list'][0]->weekend_quad_child_bk;

					$hotels['weekend_hex_price'] = $hotels['room_rate_list'][0]->weekend_hex_room_price;														
					$hotels['weekend_hex_adult_bf'] = $hotels['room_rate_list'][0]->weekend_hex_adult_bk;
					$hotels['weekend_hex_child_bf'] = $hotels['room_rate_list'][0]->weekend_hex_child_bk;*/

					$hotels['weekend_bedroom_price'] = $hotels['room_rate_list'][0]->weekend_adult_room_bed_price;														
					$hotels['weekend_bedroom_adult_bf'] = $hotels['room_rate_list'][0]->weekend_room_bed_adult_bk;
					$hotels['weekend_bedroom_child_bf'] = $hotels['room_rate_list'][0]->weekend_room_bed_child_bk;
					$hotels['last_index'] = $mixed[0];
					$hotels['country_list_nationality_id'] = $hotels['room_rate_list'][0]->country_list_nationality_id;
							$hotels['country_name'] = $hotels['room_rate_list'][0]->country_name;
            $hotels['currency'] = $this->hotels_model->get_currency_list();
				//	echo '<pre/>';print_r($hotels['block_out_date']); print_r($hotels); exit;
           // debug($hotels);die;
					//$this->load->view('hotel/roomrate/edit_room_rate',$hotels);
					$this->template->view('hotel/roomrate/new_edit_room_rate',$hotels);
		}else{
			redirect('hotels/hotel_crs_list','refresh');
		}
	}
	
	function update_room_rate($rate_id1){
		$rate_id 	= json_decode(base64_decode($rate_id1));
		if($rate_id != ''){
			if(count($_POST) > 0){

				//echo "<pre>";print_r($_POST);exit();
				$this->form_validation->set_rules('hotel_details_id', 'Hotel Id', 'required|numeric|callback_check_drop');
				$this->form_validation->set_rules('room_details_id', 'Room Type', 'required|numeric|callback_check_drop');
				$this->form_validation->set_rules('seasons_details_id', 'Season', 'required|numeric|callback_check_drop');
				//$this->form_validation->set_rules('room_rate_type', 'Rate Type', 'required|numeric|callback_check_drop');

				
				$this->form_validation->set_rules('date_rane_rate', 'Date Range', 'callback_check_daterange');										
				
				/*
				$child_price_a = $this->input->post('child_price_a');
				if(isset($child_price_a)){
					$this->form_validation->set_rules('child_price_a', 'Child price', 'numeric|min_length[2]|max_length[10]');
				}	
				$child_price_b = $this->input->post('child_price_b');
				if(isset($child_price_b)){
					$this->form_validation->set_rules('child_price_b', 'Child price', 'numeric|min_length[2]|max_length[10]');
				}	
				$child_price_c = $this->input->post('child_price_c');
				if(isset($child_price_c)){
					$this->form_validation->set_rules('child_price_c', 'Child price', 'numeric|min_length[2]|max_length[10]');
				}	
				$child_price_d = $this->input->post('child_price_d');
				if(isset($child_price_d)){
					$this->form_validation->set_rules('child_price_d', 'Child price', 'numeric|min_length[2]|max_length[10]');
				}
				$child_price_e = $this->input->post('child_price_e');
				if(isset($child_price_e)){	
					$this->form_validation->set_rules('child_price_e', 'Child price', 'numeric|min_length[2]|max_length[10]');
				}					
				*/

				$returnval = $this->hotels_model->get_table_data('extra_bed', 'hotel_room_type', 'hotel_room_type_id', $this->input->post('room_details_id'));
				if ($returnval != "") {
         			foreach ($returnval as $rowval) {          			         			
         				if($rowval->extra_bed == "Available"){							
							$this->form_validation->set_rules('child_extra_bed_price', 'Child bed price', 'numeric|required|max_length[10]|callback_check_textval');
							$this->form_validation->set_rules('adult_extra_bed_price', 'Adult bed price', 'numeric|required|max_length[10]|callback_check_textval');
						}
					}
				}

				$this->form_validation->set_rules('week_sgl_price', 'Single Room Price', 'required|numeric|min_length[2]|max_length[10]');
				$this->form_validation->set_rules('week_dbl_price', 'Double Room Price', 'numeric|max_length[10]');
				$this->form_validation->set_rules('week_trp_price', 'Triple Room Price', 'numeric|max_length[10]');
				//$this->form_validation->set_rules('week_quad_price', 'Quad Room Price', 'numeric|max_length[10]');
				//$this->form_validation->set_rules('week_hex_price', 'Hex Room Price', 'numeric|max_length[10]');

				$week_bedroom_price = $this->input->post('week_bedroom_price');
				if(isset($week_bedroom_price)){
					$this->form_validation->set_rules('week_bedroom_price', 'Bed Room Price', 'numeric|max_length[10]');
				}

				if($this->input->post('week_end_select') == "1"){
					$this->form_validation->set_rules('weekend_sgl_price', 'Single Room Price', 'required|numeric|min_length[2]|max_length[10]');
  					$this->form_validation->set_rules('weekend_dbl_price', 'Double Room Price', 'numeric|max_length[10]');
  					$this->form_validation->set_rules('weekend_tpl_price', 'Triple Room Price', 'numeric|max_length[10]');
  					//$this->form_validation->set_rules('weekend_quad_price', 'Quad Room Price', 'numeric|max_length[10]');
  					//$this->form_validation->set_rules('weekend_hex_price', 'Hex Room Price', 'numeric|max_length[10]');
					$weekend_bedroom_price = $this->input->post('weekend_bedroom_price');
					if(isset($weekend_bedroom_price)){
						$this->form_validation->set_rules('weekend_bedroom_price', 'Bed Room Price', 'numeric|max_length[10]');				
					}
				}



					//try {										
						$this->roomrate_model->new_update_room_rate($_POST,$rate_id);
						
						$res=$this->custom_db->single_table_records('hotel_details','hotel_name',array('hotel_details_id'=>$_POST['hotel_details_id']));
						if($res['status']){
							$hotel_name='"'.$res['data'][0]['hotel_name'].'"';
							isset($res['data'][0]['hotel_name']) ? $redirect = 'roomrate/list_room_rate/'.base64_encode($hotel_name) : $redirect = 'roomrate/list_room_rate';
						}else{
							$redirect = 'roomrate/list_room_rate';
						}
			    		
						if(isset($_POST['lindex']) && !empty($_POST['lindex']))
						{
							$redirect .='/'.$this->input->post('lindex');
						}
						redirect($redirect,'refresh');
					/*}catch(Exception $e) {
						$this->General_Model->rollback_transaction();
						return $e;
					}*/

			}else{
				/*$hotels 						= $this->General_Model->getHomePageSettings();
				$hotels['hotels_list'] 	   		= $this->hotels_model->get_hotel_crs_list();
				$hotels['settings'] 			= $this->hotels_model->get_hotel_settings_list();
				$this->load->view('hotel/roomrate/room_rate',$hotels);*/

				redirect('roomrate/list_room_rate','refresh');
			}
		}else{
			redirect('roomrate/list_room_rate','refresh');
		}
	}
  
  function get_room_daterange($hotel_id,$room_details_id) {
  
  	 $room_detials  =  $this->hotels_model->get_daterange_by_room_type($hotel_id,$room_details_id);
  
	if($room_detials != ''){
		$date_range = $room_detials->from_date." - ".$room_detials->to_date;
	}
	
	echo $date_range; exit;
  }


  function get_extra_bed_avail($room_type_id = ""){
  	$extra_bed = "no";
  	$room_count = "0";
  	$no_of_room = "";
  	if($room_type_id != ""){
  		$returnval = $this->hotels_model->get_table_data('extra_bed,adult', 'hotel_room_type', 'hotel_room_type_id', $room_type_id);
  		if ($returnval != "") {
         foreach ($returnval as $rowval) { 
         	$extra_bed = "no";
         	if($rowval->extra_bed == "NotAvailable"){
         		$extra_bed = "no";
         	}
         	if($rowval->extra_bed == "Available"){
         		$extra_bed = "yes";
         	}
         	if($rowval->adult > 2){
         		$room_count = $rowval->adult/2;         		
         	}
         }//for	
  	    }//if
    }//if
    echo json_encode(array('extra_bed'=> $extra_bed,'no_of_room'=> $room_count));
    //echo $extra_bed;

  }

  function check_drop($val){
		if($val == "0" || $val == ""){
			$this->form_validation->set_message('check_drop', 'Please select %s');
            return false;	
		}
  }

  function check_include_country($inval){
  	$exval =  $this->input->post('exclude_country');  	  	  	
  	if($inval != "" && $exval != ""){
  		$this->form_validation->set_message('check_include_country', 'Please select the countries in either Include or Exclude');
        return false;	
  	}
  }

  function check_blockout_date($val,$loop1){  	    
   	if(sizeof($val) > 0){
  	  for($loop =0 ;$loop < sizeof($val); $loop++){
  	  	if(strlen($val[$loop]) != 23 ){  	  		
  	  		$this->form_validation->set_message('check_blockout_date', 'Please select the proper %s');
        	return false;				
  	  	}
  	  }
  	}  	  	
  }

  function check_daterange($val){
  	if($val == ""){
  		$this->form_validation->set_message('check_daterange', 'Please select the %s');
        return false;					
  	}
  	elseif(strlen($val) != 23 ){  		
  	  $this->form_validation->set_message('check_daterange', 'Please select the proper %s');
      return false;				
  	}
  }

  function delete_blockout_date($hotel_blockout_id){
  	if($hotel_blockout_id != ""){
  		$this->Roomrate_Model->delete_blockout_date($hotel_blockout_id);
  	}
  }

  function check_textval($val){
  	 if($val <= 0){
  	 	$this->form_validation->set_message('check_textval', 'Please enter the proper %s');
        return false;					
  	 }
  }

  function hotel_add_room_rate($segment1=''){
		if(count($_POST) > 0){			
				//echo "<pre>";print_r($_POST);exit();
				$this->form_validation->set_rules('hotel_details_id', 'Hotel Id', 'required|numeric|callback_check_drop');
				$this->form_validation->set_rules('room_details_id', 'Room Type', 'required|numeric|callback_check_drop');
				//$this->form_validation->set_rules('seasons_details_id', 'Season', 'required|numeric|callback_check_drop');
				
				$this->form_validation->set_rules('date_rane_rate', 'Date Range', 'callback_check_daterange');										
				
				/*
				$child_price_a = $this->input->post('child_price_a');
				if(isset($child_price_a)){
					$this->form_validation->set_rules('child_price_a', 'Child price', 'numeric|min_length[2]|max_length[10]');
				}	
				$child_price_b = $this->input->post('child_price_b');
				if(isset($child_price_b)){
					$this->form_validation->set_rules('child_price_b', 'Child price', 'numeric|min_length[2]|max_length[10]');
				}	
				$child_price_c = $this->input->post('child_price_c');
				if(isset($child_price_c)){
					$this->form_validation->set_rules('child_price_c', 'Child price', 'numeric|min_length[2]|max_length[10]');
				}	
				$child_price_d = $this->input->post('child_price_d');
				if(isset($child_price_d)){
					$this->form_validation->set_rules('child_price_d', 'Child price', 'numeric|min_length[2]|max_length[10]');
				}
				$child_price_e = $this->input->post('child_price_e');
				if(isset($child_price_e)){	
					$this->form_validation->set_rules('child_price_e', 'Child price', 'numeric|min_length[2]|max_length[10]');
				}					
				*/
				$child_bed = $this->input->post('child_extra_bed_price');
				
				if(isset($child_bed)) 
				{
					$this->form_validation->set_rules('child_extra_bed_price', 'Child bed price', 'numeric|min_length[2]|max_length[10]');
					$this->form_validation->set_rules('adult_extra_bed_price', 'Adult bed price', 'numeric|min_length[2]|max_length[10]');					
				}

				$this->form_validation->set_rules('week_sgl_price', 'Single Room Price', 'required|numeric|min_length[2]|max_length[10]');
				$this->form_validation->set_rules('week_dbl_price', 'Double Room Price', 'numeric|max_length[10]');
				$this->form_validation->set_rules('week_trp_price', 'Triple Room Price', 'numeric|max_length[10]');
				$this->form_validation->set_rules('week_quad_price', 'Quad Room Price', 'numeric|max_length[10]');
				$this->form_validation->set_rules('week_hex_price', 'Hex Room Price', 'numeric|max_length[10]');

				$week_bedroom_price = $this->input->post('week_bedroom_price');
				if(isset($week_bedroom_price)){
					$this->form_validation->set_rules('week_bedroom_price', 'Bed Room Price', 'numeric|max_length[10]');
				}

				if($this->input->post('week_end_select') == "1"){
					$this->form_validation->set_rules('weekend_sgl_price', 'Single Room Price', 'required|numeric|min_length[2]|max_length[10]');
  					$this->form_validation->set_rules('weekend_dbl_price', 'Double Room Price', 'numeric|max_length[10]');
  					$this->form_validation->set_rules('weekend_tpl_price', 'Triple Room Price', 'numeric|max_length[10]');
  					$this->form_validation->set_rules('weekend_quad_price', 'Quad Room Price', 'numeric|max_length[10]');
  					$this->form_validation->set_rules('weekend_hex_price', 'Hex Room Price', 'numeric|max_length[10]');
					$weekend_bedroom_price = $this->input->post('weekend_bedroom_price');
					if(isset($weekend_bedroom_price)){
						$this->form_validation->set_rules('weekend_bedroom_price', 'Bed Room Price', 'numeric|max_length[10]');				
					}
				}	

				if($this->form_validation->run() == false)
				{
					$hotels 						= $this->General_Model->getHomePageSettings();
					$hotels['hotels_list'] 	   		= $this->hotels_model->get_hotel_crs_list("", $hotels);			
					$hotels['settings'] 			= $this->hotels_model->get_hotel_settings_list();
					$hotels['country'] = $this->General_Model->get_country_details("");	
					$hotels['hotel_details_id'] =$this->input->post('hotel_details_id');
					$hotels['room_details_id'] =$this->input->post('room_details_id');
					//$hotels['seasons_details_id'] =$this->input->post('seasons_details_id');
					$hotels['cancellation_policy'] =$this->input->post('cancellation_policy');				
					$hotels['room_rate_type'] =$this->input->post('room_rate_type');	
					$hotels['week_end_select'] =$this->input->post('week_end_select');	
					$hotels['date_rane_rate'] =$this->input->post('date_rane_rate');	
					//$hotels['promotion'] =$this->input->post('promotion');	
					//$hotels['include_country'] =$this->input->post('include_country');	
					//$hotels['exclude_country'] =$this->input->post('exclude_country');	
													
					$hotels['gst'] =$this->input->post('gst');	
					$hotels['service_charge'] = $this->input->post('service_charge');			

					if($this->input->post('child_price_a') != ""){
					 $hotels['child_price_a'] = $this->input->post('child_price_a');
					}
					else{
						$hotels['child_price_a'] = "";	
					}

					if($this->input->post('child_price_b') != ""){
					 $hotels['child_price_b'] = $this->input->post('child_price_b');
					}
					else{
						$hotels['child_price_b'] = "";	
					}

					if($this->input->post('child_price_c') != ""){
					 $hotels['child_price_c'] = $this->input->post('child_price_c');
					}
					else{
						$hotels['child_price_c'] = "";	
					}

					if($this->input->post('child_price_d') != ""){
					 $hotels['child_price_d'] = $this->input->post('child_price_d');
					}
					else{
						$hotels['child_price_d'] = "";	
					}

					if($this->input->post('child_price_e') != ""){
					 $hotels['child_price_e'] = $this->input->post('child_price_e');
					}
					else{
						$hotels['child_price_e'] = "";	
					}

					$hotels['child_price'] = $hotels['child_price_a']."_".$hotels['child_price_b']."_".$hotels['child_price_c']."_".$hotels['child_price_d']."_".$hotels['child_price_e'];					 
					if($this->input->post('child_extra_bed_price') != ""){
						$hotels['child_extra_bed_price'] = $this->input->post('child_extra_bed_price');
						$hotels['adult_extra_bed_price'] = $this->input->post('adult_extra_bed_price');
					}	
					$hotels['week_sgl_price'] = $this->input->post('week_sgl_price');														
					$hotels['week_single_adult_bf'] = $this->input->post('week_single_adult_bf');
					$hotels['week_single_child_bf'] = $this->input->post('week_single_child_bf');

					$hotels['week_dbl_price'] = $this->input->post('week_dbl_price');														
					$hotels['week_double_child_bf'] = $this->input->post('week_double_child_bf');
					$hotels['week_double_adult_bf'] = $this->input->post('week_double_adult_bf');

					$hotels['week_trp_price'] = $this->input->post('week_trp_price');														
					$hotels['week_trp_adult_bf'] = $this->input->post('week_trp_adult_bf');
					$hotels['week_trp_child_bf'] = $this->input->post('week_trp_child_bf');

					$hotels['week_quad_price'] = $this->input->post('week_quad_price');														
					$hotels['week_quad_adult_bf'] = $this->input->post('week_quad_adult_bf');
					$hotels['week_quad_child_bf'] = $this->input->post('week_quad_child_bf');

					$hotels['week_hex_price'] = $this->input->post('week_hex_price');														
					$hotels['week_hex_adult_bf'] = $this->input->post('week_hex_adult_bf');
					$hotels['week_hex_child_bf'] = $this->input->post('week_hex_child_bf');

					$week_bedroom_price = $this->input->post('week_bedroom_price');
					if(isset($week_bedroom_price)) {
						$hotels['week_bedroom_price'] = $this->input->post('week_bedroom_price');														
						$hotels['week_bedroom_adult_bf'] = $this->input->post('week_bedroom_adult_bf');
						$hotels['week_bedroom_child_bf'] = $this->input->post('week_bedroom_child_bf');
					}

					$hotels['weekend_sgl_price'] = $this->input->post('weekend_sgl_price');														
					$hotels['weekend_single_adult_bf'] = $this->input->post('weekend_single_adult_bf');
					$hotels['weekend_single_child_bf'] = $this->input->post('weekend_single_child_bf');

					$hotels['weekend_dbl_price'] = $this->input->post('weekend_dbl_price');														
					$hotels['weekend_double_adult_bf'] = $this->input->post('weekend_double_adult_bf');
					$hotels['weekend_double_child_bf'] = $this->input->post('weekend_double_child_bf');

					$hotels['weekend_tpl_price'] = $this->input->post('weekend_tpl_price');														
					$hotels['weekend_triple_adult_bf'] = $this->input->post('weekend_triple_adult_bf');
					$hotels['weekend_triple_child_bf'] = $this->input->post('weekend_triple_child_bf');

					$hotels['weekend_quad_price'] = $this->input->post('weekend_quad_price');														
					$hotels['weekend_quad_adult_bf'] = $this->input->post('weekend_quad_adult_bf');
					$hotels['weekend_quad_child_bf'] = $this->input->post('weekend_quad_child_bf');

					$hotels['weekend_hex_price'] = $this->input->post('weekend_hex_price');														
					$hotels['weekend_hex_adult_bf'] = $this->input->post('weekend_hex_adult_bf');
					$hotels['weekend_hex_child_bf'] = $this->input->post('weekend_hex_child_bf');

					$weekend_bedroom_price = $this->input->post('weekend_bedroom_price');
					
					if(isset($weekend_bedroom_price))
					{
						$hotels['weekend_bedroom_price'] = $this->input->post('weekend_bedroom_price');														
						$hotels['weekend_bedroom_adult_bf'] = $this->input->post('weekend_bedroom_adult_bf');
						$hotels['weekend_bedroom_child_bf'] = $this->input->post('weekend_bedroom_child_bf');
					}				

					//echo "<pre>";print_r($hotels['GET']);
					$hotels['GET'] = str_replace('"', '', (base64_decode($segment1)));
					//echo "<pre>";print_r($hotels['GET']);
					$this->load->view('hotel/roomrate/hotel_room_rate',$hotels);					
				}
				else{
				  try{	
					$this->General_Model->begin_transaction();
					$this->Roomrate_Model->new_hotel_add_room_rate($_POST);
					$this->General_Model->commit_transaction();
					
					//if(isset($_GET['room']) && !empty($_GET['room']))
					if(!empty($segment1))
					{
						//redirect('roomrate?room='.$this->input->get('room') ,'refresh');	
						
						redirect('roomrate/hotel_list_room_rate/'.$segment1 ,'refresh');
					} else {
						redirect('roomrate/hotel_list_room_rate','refresh');	
					}
							    	
			       } catch(Exception $e) {
				     $this->General_Model->rollback_transaction();
				     return $e;
				}     
			}
		}else{
			$hotels 						= $this->General_Model->getHomePageSettings();
			$hotels['hotels_list'] 	   		= $this->hotels_model->get_hotel_crs_list("", $hotels);
			// $hotels['seasons_list'] 	   	= $this->Seasons_Model->get_seasons_list("",$hotels);
			$hotels['settings'] 			= $this->hotels_model->get_hotel_settings_list();
			$hotels['country'] = $this->General_Model->get_country_details("");	
			$hotels['GET'] = str_replace('"', '', (base64_decode($segment1)));		
			//echo "<pre>";print_r($hotels['GET']);
			$this->load->view('hotel/roomrate/hotel_room_rate',$hotels);
		}
	} 

	function inactive_hotel_roomrate($rate_id1,$hotelname=''){
		$rate_id 	= json_decode(base64_decode($rate_id1));
		if($rate_id != ''){
			$this->Roomrate_Model->inactive_hotel_roomrate($rate_id);
		}
		if(isset($hotelname) && $hotelname!='')
			redirect('roomrate/hotel_list_room_rate/'.$hotelname,'refresh');
		else
			redirect('roomrate/hotel_list_room_rate','refresh');
	}
	
	function active_hotel_roomrate($rate_id1,$hotelname=''){
		$rate_id 	= json_decode(base64_decode($rate_id1));
		if($rate_id != ''){
			$this->Roomrate_Model->active_hotel_roomrate($rate_id);
		}
		if(isset($hotelname) && $hotelname!='')
			redirect('roomrate/hotel_list_room_rate/'.$hotelname,'refresh');
		else
			redirect('roomrate/hotel_list_room_rate','refresh');
	}

	function delete_hotel_roomrate($rate_id1){
		$rate_id 	= json_decode(base64_decode($rate_id1));
		if($rate_id != ''){
			$this->Roomrate_Model->delete_hotel_roomrate($rate_id);
		}
		redirect('hotel/hotel_crs_list','refresh');
	}

	function edit_hotel_roomrate($rate_id1)
	{
		$mixed 	= json_decode(base64_decode($rate_id1));//echo '<pre>';print_r($mixed);exit;
		$rate_id = $mixed[1];
		if($rate_id != ''){			
			$hotels 						= $this->General_Model->getHomePageSettings();
			$hotels['hotels_list'] 	   		= $this->hotels_model->get_hotel_crs_list("", $hotels);
			//$hotels['room_rate_list'] 	   	= $this->Roomrate_Model->get_room_rate_list($rate_id, $hotels);
			$hotels['room_rate_list'] 	   	= $this->Roomrate_Model->new_hotel_get_room_rate_list($rate_id, $hotels);
			//echo '<pre>'; print_r($hotels['room_rate_list'][0]); exit();
			$hotels['settings'] 			= $this->hotels_model->get_hotel_settings_list();
			//$hotels['block_out_date']  		= $this->Roomrate_Model->new_get_blockout_date($rate_id);

			$hotels['country'] = $this->General_Model->get_country_details("");	
			$hotels['room_rate_id'] = $rate_id;
			$hotels['hotel_details_id'] = $hotels['room_rate_list'][0]->hotel_details_id;
			$hotels['room_details_id'] = $hotels['room_rate_list'][0]->hotel_room_type_id;
			$hotels['week_end_select'] = $hotels['room_rate_list'][0]->weekend_price;
			$hotels['date_rane_rate'] =  date('m/d/Y',strtotime($hotels['room_rate_list'][0]->from_date))." - ".date('m/d/Y',strtotime($hotels['room_rate_list'][0]->to_date)) ;
			

					$hotels['gst'] = $hotels['room_rate_list'][0]->gst_tax;
					$hotels['service_charge'] = $hotels['room_rate_list'][0]->service_tax;
					$hotels['child_price'] = $hotels['room_rate_list'][0]->room_child_price_a."_".$hotels['room_rate_list'][0]->room_child_price_b."_".$hotels['room_rate_list'][0]->room_child_price_c."_".$hotels['room_rate_list'][0]->room_child_price_d."_".$hotels['room_rate_list'][0]->room_child_price_e;
					$hotels['child_extra_bed_price'] = $hotels['room_rate_list'][0]->child_extra_bed_price;
					$hotels['adult_extra_bed_price'] = $hotels['room_rate_list'][0]->adult_extra_bed_price;

					$hotels['week_sgl_price'] = $hotels['room_rate_list'][0]->single_room_price;														
					$hotels['week_single_adult_bf'] = $hotels['room_rate_list'][0]->single_adult_bk;
					$hotels['week_single_child_bf'] = $hotels['room_rate_list'][0]->single_child_bk;

					$hotels['week_dbl_price'] = $hotels['room_rate_list'][0]->double_room_price;														
					$hotels['week_double_child_bf'] = $hotels['room_rate_list'][0]->double_child_bk;
					$hotels['week_double_adult_bf'] = $hotels['room_rate_list'][0]->double_adult_bk;

					$hotels['week_trp_price'] = $hotels['room_rate_list'][0]->triple_room_price;														
					$hotels['week_trp_adult_bf'] = $hotels['room_rate_list'][0]->triple_adult_bk;
					$hotels['week_trp_child_bf'] = $hotels['room_rate_list'][0]->triple_child_bk;

					$hotels['week_quad_price'] = $hotels['room_rate_list'][0]->quad_room_price;														
					$hotels['week_quad_adult_bf'] = $hotels['room_rate_list'][0]->quad_adult_bk;
					$hotels['week_quad_child_bf'] = $hotels['room_rate_list'][0]->quad_child_bk;

					$hotels['week_hex_price'] = $hotels['room_rate_list'][0]->hex_room_price;														
					$hotels['week_hex_adult_bf'] = $hotels['room_rate_list'][0]->hex_adult_bk;
					$hotels['week_hex_child_bf'] = $hotels['room_rate_list'][0]->hex_child_bk;
					
					$hotels['week_bedroom_price'] = $hotels['room_rate_list'][0]->adult_room_bed_price;
					$hotels['week_bedroom_adult_bf'] = $hotels['room_rate_list'][0]->room_bed_adult_bk;
					$hotels['week_bedroom_child_bf'] = $hotels['room_rate_list'][0]->room_bed_child_bk;
					

					$hotels['weekend_sgl_price'] = $hotels['room_rate_list'][0]->weekend_single_room_price;														
					$hotels['weekend_single_adult_bf'] = $hotels['room_rate_list'][0]->weekend_single_adult_bk;
					$hotels['weekend_single_child_bf'] = $hotels['room_rate_list'][0]->weekend_single_child_bk;

					$hotels['weekend_dbl_price'] = $hotels['room_rate_list'][0]->weekend_double_room_price;														
					$hotels['weekend_double_adult_bf'] = $hotels['room_rate_list'][0]->weekend_double_adult_bk;
					$hotels['weekend_double_child_bf'] = $hotels['room_rate_list'][0]->weekend_double_child_bk;

					$hotels['weekend_tpl_price'] = $hotels['room_rate_list'][0]->weekend_triple_room_price;														
					$hotels['weekend_triple_adult_bf'] = $hotels['room_rate_list'][0]->weekend_triple_room_price;
					$hotels['weekend_triple_child_bf'] = $hotels['room_rate_list'][0]->weekend_triple_child_bk;

					$hotels['weekend_quad_price'] = $hotels['room_rate_list'][0]->weekend_quad_room_price;														
					$hotels['weekend_quad_adult_bf'] = $hotels['room_rate_list'][0]->weekend_quad_adult_bk;
					$hotels['weekend_quad_child_bf'] = $hotels['room_rate_list'][0]->weekend_quad_child_bk;

					$hotels['weekend_hex_price'] = $hotels['room_rate_list'][0]->weekend_hex_room_price;														
					$hotels['weekend_hex_adult_bf'] = $hotels['room_rate_list'][0]->weekend_hex_adult_bk;
					$hotels['weekend_hex_child_bf'] = $hotels['room_rate_list'][0]->weekend_hex_child_bk;

					$hotels['weekend_bedroom_price'] = $hotels['room_rate_list'][0]->weekend_adult_room_bed_price;														
					$hotels['weekend_bedroom_adult_bf'] = $hotels['room_rate_list'][0]->weekend_room_bed_adult_bk;
					$hotels['weekend_bedroom_child_bf'] = $hotels['room_rate_list'][0]->weekend_room_bed_child_bk;
					$hotels['last_index'] = $mixed[0];
					//echo '<pre/>';print_r($hotels['block_out_date']); print_r($hotels); exit;
					//$this->load->view('hotel/roomrate/edit_room_rate',$hotels);
                    $hotels['currency'] = $this->hotels_model->get_currency_list();
					$this->load->view('hotel/roomrate/new_hotel_edit_room_rate',$hotels);
		}else{
			redirect('roomrate','refresh');
		}
	}

	function update_hotel_room_rate($rate_id1){
		$rate_id 	= json_decode(base64_decode($rate_id1));
		if($rate_id != ''){
			if(count($_POST) > 0){

				//echo "<pre>";print_r($_POST);exit();
				$this->form_validation->set_rules('hotel_details_id', 'Hotel Id', 'required|numeric|callback_check_drop');
				$this->form_validation->set_rules('room_details_id', 'Room Type', 'required|numeric|callback_check_drop');
				
				$this->form_validation->set_rules('date_rane_rate', 'Date Range', 'callback_check_daterange');										
				
				/*
				$child_price_a = $this->input->post('child_price_a');
				if(isset($child_price_a)){
					$this->form_validation->set_rules('child_price_a', 'Child price', 'numeric|min_length[2]|max_length[10]');
				}	
				$child_price_b = $this->input->post('child_price_b');
				if(isset($child_price_b)){
					$this->form_validation->set_rules('child_price_b', 'Child price', 'numeric|min_length[2]|max_length[10]');
				}	
				$child_price_c = $this->input->post('child_price_c');
				if(isset($child_price_c)){
					$this->form_validation->set_rules('child_price_c', 'Child price', 'numeric|min_length[2]|max_length[10]');
				}	
				$child_price_d = $this->input->post('child_price_d');
				if(isset($child_price_d)){
					$this->form_validation->set_rules('child_price_d', 'Child price', 'numeric|min_length[2]|max_length[10]');
				}
				$child_price_e = $this->input->post('child_price_e');
				if(isset($child_price_e)){	
					$this->form_validation->set_rules('child_price_e', 'Child price', 'numeric|min_length[2]|max_length[10]');
				}					
				*/

				$returnval = $this->General_Model->get_table_data('extra_bed', 'hotel_room_type', 'hotel_room_type_id', $this->input->post('room_details_id'));
				if ($returnval != "") {
         			foreach ($returnval as $rowval) {          			         			
         				if($rowval->extra_bed == "Available"){							
							$this->form_validation->set_rules('child_extra_bed_price', 'Child bed price', 'numeric|required|max_length[10]|callback_check_textval');
							$this->form_validation->set_rules('adult_extra_bed_price', 'Adult bed price', 'numeric|required|max_length[10]|callback_check_textval');
						}
					}
				}

				$this->form_validation->set_rules('week_sgl_price', 'Single Room Price', 'required|numeric|min_length[2]|max_length[10]');
				$this->form_validation->set_rules('week_dbl_price', 'Double Room Price', 'numeric|max_length[10]');
				$this->form_validation->set_rules('week_trp_price', 'Triple Room Price', 'numeric|max_length[10]');
				$this->form_validation->set_rules('week_quad_price', 'Quad Room Price', 'numeric|max_length[10]');
				$this->form_validation->set_rules('week_hex_price', 'Hex Room Price', 'numeric|max_length[10]');

				$week_bedroom_price = $this->input->post('week_bedroom_price');
				if(isset($week_bedroom_price)){
					$this->form_validation->set_rules('week_bedroom_price', 'Bed Room Price', 'numeric|max_length[10]');
				}

				if($this->input->post('week_end_select') == "1"){
					$this->form_validation->set_rules('weekend_sgl_price', 'Single Room Price', 'required|numeric|min_length[2]|max_length[10]');
  					$this->form_validation->set_rules('weekend_dbl_price', 'Double Room Price', 'numeric|max_length[10]');
  					$this->form_validation->set_rules('weekend_tpl_price', 'Triple Room Price', 'numeric|max_length[10]');
  					$this->form_validation->set_rules('weekend_quad_price', 'Quad Room Price', 'numeric|max_length[10]');
  					$this->form_validation->set_rules('weekend_hex_price', 'Hex Room Price', 'numeric|max_length[10]');
					$weekend_bedroom_price = $this->input->post('weekend_bedroom_price');
					if(isset($weekend_bedroom_price)){
						$this->form_validation->set_rules('weekend_bedroom_price', 'Bed Room Price', 'numeric|max_length[10]');				
					}
				}	

				if($this->form_validation->run() == false)
				{
					$hotels 						= $this->General_Model->getHomePageSettings();
					$hotels['hotels_list'] 	   		= $this->hotels_model->get_hotel_crs_list("", $hotels);			
					$hotels['settings'] 			= $this->hotels_model->get_hotel_settings_list();
					$hotels['room_rate_id'] = $rate_id;
					$hotels['country'] = $this->General_Model->get_country_details("");	
					$hotels['hotel_details_id'] =$this->input->post('hotel_details_id');
					$hotels['room_details_id'] =$this->input->post('room_details_id');
					
					
					//$hotels['room_rate_type'] =$this->input->post('room_rate_type');	
					$hotels['week_end_select'] =$this->input->post('week_end_select');	
					$hotels['date_rane_rate'] =$this->input->post('date_rane_rate');	
					$hotels['gst'] =$this->input->post('gst');	
					$hotels['service_charge'] = $this->input->post('service_charge');			

					if($this->input->post('child_price_a') != ""){
					 $hotels['child_price_a'] = $this->input->post('child_price_a');
					}
					else{
						$hotels['child_price_a'] = "";	
					}

					if($this->input->post('child_price_b') != ""){
					 $hotels['child_price_b'] = $this->input->post('child_price_b');
					}
					else{
						$hotels['child_price_b'] = "";	
					}

					if($this->input->post('child_price_c') != ""){
					 $hotels['child_price_c'] = $this->input->post('child_price_c');
					}
					else{
						$hotels['child_price_c'] = "";	
					}

					if($this->input->post('child_price_d') != ""){
					 $hotels['child_price_d'] = $this->input->post('child_price_d');
					}
					else{
						$hotels['child_price_d'] = "";	
					}

					if($this->input->post('child_price_e') != ""){
					 $hotels['child_price_e'] = $this->input->post('child_price_e');
					}
					else{
						$hotels['child_price_e'] = "";	
					}

					$hotels['child_price'] = $hotels['child_price_a']."_".$hotels['child_price_b']."_".$hotels['child_price_c']."_".$hotels['child_price_d']."_".$hotels['child_price_e'];					 
					if($this->input->post('child_extra_bed_price') != ""){
						$hotels['child_extra_bed_price'] = $this->input->post('child_extra_bed_price');
						$hotels['adult_extra_bed_price'] = $this->input->post('adult_extra_bed_price');
					}	
					$hotels['week_sgl_price'] = $this->input->post('week_sgl_price');														
					$hotels['week_single_adult_bf'] = $this->input->post('week_single_adult_bf');
					$hotels['week_single_child_bf'] = $this->input->post('week_single_child_bf');

					$hotels['week_dbl_price'] = $this->input->post('week_dbl_price');														
					$hotels['week_double_child_bf'] = $this->input->post('week_double_child_bf');
					$hotels['week_double_adult_bf'] = $this->input->post('week_double_adult_bf');

					$hotels['week_trp_price'] = $this->input->post('week_trp_price');														
					$hotels['week_trp_adult_bf'] = $this->input->post('week_trp_adult_bf');
					$hotels['week_trp_child_bf'] = $this->input->post('week_trp_child_bf');

					$hotels['week_quad_price'] = $this->input->post('week_quad_price');														
					$hotels['week_quad_adult_bf'] = $this->input->post('week_quad_adult_bf');
					$hotels['week_quad_child_bf'] = $this->input->post('week_quad_child_bf');

					$hotels['week_hex_price'] = $this->input->post('week_hex_price');														
					$hotels['week_hex_adult_bf'] = $this->input->post('week_hex_adult_bf');
					$hotels['week_hex_child_bf'] = $this->input->post('week_hex_child_bf');

					$week_bedroom_price = $this->input->post('week_bedroom_price');
					if(isset($week_bedroom_price)) {
						$hotels['week_bedroom_price'] = $this->input->post('week_bedroom_price');														
						$hotels['week_bedroom_adult_bf'] = $this->input->post('week_bedroom_adult_bf');
						$hotels['week_bedroom_child_bf'] = $this->input->post('week_bedroom_child_bf');
					}

					$hotels['weekend_sgl_price'] = $this->input->post('weekend_sgl_price');														
					$hotels['weekend_single_adult_bf'] = $this->input->post('weekend_single_adult_bf');
					$hotels['weekend_single_child_bf'] = $this->input->post('weekend_single_child_bf');

					$hotels['weekend_dbl_price'] = $this->input->post('weekend_dbl_price');														
					$hotels['weekend_double_adult_bf'] = $this->input->post('weekend_double_adult_bf');
					$hotels['weekend_double_child_bf'] = $this->input->post('weekend_double_child_bf');

					$hotels['weekend_tpl_price'] = $this->input->post('weekend_tpl_price');														
					$hotels['weekend_triple_adult_bf'] = $this->input->post('weekend_triple_adult_bf');
					$hotels['weekend_triple_child_bf'] = $this->input->post('weekend_triple_child_bf');

					$hotels['weekend_quad_price'] = $this->input->post('weekend_quad_price');														
					$hotels['weekend_quad_adult_bf'] = $this->input->post('weekend_quad_adult_bf');
					$hotels['weekend_quad_child_bf'] = $this->input->post('weekend_quad_child_bf');

					$hotels['weekend_hex_price'] = $this->input->post('weekend_hex_price');														
					$hotels['weekend_hex_adult_bf'] = $this->input->post('weekend_hex_adult_bf');
					$hotels['weekend_hex_child_bf'] = $this->input->post('weekend_hex_child_bf');

					$weekend_bedroom_price = $this->input->post('weekend_bedroom_price');
					if(isset($weekend_bedroom_price)){
						$hotels['weekend_bedroom_price'] = $this->input->post('weekend_bedroom_price');														
						$hotels['weekend_bedroom_adult_bf'] = $this->input->post('weekend_bedroom_adult_bf');
						$hotels['weekend_bedroom_child_bf'] = $this->input->post('weekend_bedroom_child_bf');
					}				

					//echo "<pre>";print_r($hotels);
					$this->load->view('hotel/roomrate/new_hotel_edit_room_rate',$hotels);					
				}
				else{
					try {										
			    		$this->General_Model->begin_transaction();
						$this->Roomrate_Model->new_hotel_update_room_rate($_POST,$rate_id);
						$this->General_Model->commit_transaction();
						
						$redirect = 'roomrate/hotel_list_room_rate';
						
						if(isset($_POST['lindex']) && !empty($_POST['lindex']))
						{
							$redirect .='/'.$this->input->post('lindex');
						}
						redirect($redirect,'refresh');
					}catch(Exception $e) {
						$this->General_Model->rollback_transaction();
						return $e;
					}
				}	
			}else{
				/*$hotels 						= $this->General_Model->getHomePageSettings();
				$hotels['hotels_list'] 	   		= $this->hotels_model->get_hotel_crs_list();
				$hotels['settings'] 			= $this->hotels_model->get_hotel_settings_list();
				$this->load->view('hotel/roomrate/room_rate',$hotels);*/

				redirect('roomrate/hotel_list_room_rate','refresh');
			}
		}else{
			redirect('roomrate/hotel_list_room_rate','refresh');
		}
	}

}
