<?php session_start();
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
class Seasons extends CI_Controller {	

	public function __construct(){
		parent::__construct();	
		
		$this->load->model('seasons_model');
		$this->load->model('Roomrate_Model');
		$this->load->model('hotels_model');
		
	}

	
   function get_tax_data($hotel_id){
		$options = '';		
		if(!empty($hotel_id)){
			$result = $this->Seasons_Model->get_tax_list_by_hotel($hotel_id);
			if($result!=''){
			foreach($result as $row){
				$options .= '<option value="'.$row->hotel_tax_type_id.'">'.$row->tax_type_name.'</option>';				
			}}		
		}
		echo $options;exit;
	}
	
	public function index($hotel_id){ print_r($hotel_id); exit();
		$hotels 						= $this->hotels_model->getHomePageSettings();
		$hotels['seasons_list'] 	   	= $this->seasons_model->get_seasons_list("",$hotel_id,$hotels);
		$this->template->view('hotel/seasons/seasons_list',$hotels);
	}
	
	public function seasons_list($hotel_id1){ 
		$hotel_id 	= json_decode(base64_decode($hotel_id1));
		if($hotel_id != ''){

			$hotels 						= $this->hotels_model->getHomePageSettings();
			$hotels['seasons_list'] 	   	= $this->seasons_model->get_seasons_list("",$hotel_id,$hotels);
			// echo $$hotel_id;exit("ok");
			$hotels['hotel_id1'] 	   		= $hotel_id1;
			$hotels['hotel_id'] 	   		= $hotel_id;
			$wizard_status=$this->hotels_model->get_wizard($hotel_id);
			$wizard_status = $wizard_status[0]['wizard_status'] ;
			$wiz=explode(',', $wizard_status);
				foreach ($wiz as $wkey => $wvalue) {
					$w[]=$wvalue;
				}
			$hotels['wizard_status']=$w;

			$hotels['hotels_list'] 		= $this->hotels_model->get_hotel_crs_list($hotel_id, $hotels);
			$hotel_details=json_decode(json_encode($hotels['hotels_list'][0]),true);
			$hotels['hotel_name']=$hotel_details['hotel_name'];
			// debug($hotels);exit;
			$this->template->view('hotel/seasons/seasons_list',$hotels);
		}else{
			redirect('hotels/hotel_crs_list','refresh');
		}
	}
	
	function add_seasons($hotel_id1){ 
		$hotel_id 	= json_decode(base64_decode($hotel_id1));
		if($hotel_id != ''){
			if(count($_POST) > 0){
			  
			  $this->form_validation->set_rules('hotel_details_id', 'Hotel', 'callback_check_drop');
			  $this->form_validation->set_rules('room_type', 'Room Type', 'required');
			  $this->form_validation->set_rules('seasons_name', 'Season Name', 'required|min_length[2]|max_length[50]');
			  $this->form_validation->set_rules('seasons_date_range', 'Season Date Range', 'required|min_length[23]|max_length[23]');			  
			  $this->form_validation->set_rules('minimum_stays', 'Minimum Stays', 'required|numeric|min_length[1]|max_length[2]');
			  /*
			  $this->form_validation->set_rules('cancellation_from', 'From Days', 'callback_check_value');					  
			  $this->form_validation->set_rules('cancellation_to', 'To Days', 'callback_check_value');					  
			  $this->form_validation->set_rules('cancellation_nightcharge', 'Night', 'callback_check_charge');*/					  
			  //$this->form_validation->set_rules('cancellation_percentage', 'Percentage', 'callback_check_charge');					  

			  if ($this->form_validation->run() == FALSE) { //Validation false					 	                    					       

			  // debug("as");    exit();
			  	$hotels 						= $this->hotels_model->getHomePageSettings();
				$hotels['settings'] 			= $this->hotels_model->get_hotel_settings_list();
				$hotels['hotel_id1'] 	   		= $hotel_id1;
				$hotels['hotel_id']				= $hotel_id;
				$hotels['hotels_list'] 			= $this->hotels_model->get_hotel_crs_list("", $hotels);
			  	$hotels['hotel_details_id'] = $this->input->post('hotel_details_id');
			  	//$hotels['room_type'] = $this->input->post('room_type');
			  	$hotels['seasons_name'] = $this->input->post('seasons_name');
			  	$hotels['seasons_date_range'] = $this->input->post('seasons_date_range');
			  	$hotels['minimum_stays'] = $this->input->post('minimum_stays');			  	
			  	//$hotels['season_type'] = $this->input->post('season_type');			  	
			  	//$hotels['cutoff'] = $this->input->post('cutoff');	
			  	//$hotels['status'] = $this->input->post('status');		  	

			  	//$hotels['cancellation_from'] = $this->input->post('cancellation_from');		  	
			  	//$hotels['cancellation_to'] = $this->input->post('cancellation_to');		  	
			  	//$hotels['cancellation_nightcharge'] = $this->input->post('cancellation_nightcharge');		  	
			  	//$hotels['cancellation_percentage'] = $this->input->post('cancellation_percentage');		  	
			  	
			  	
				//echo "<pre>";print_r($hotels);
				// debug($hotel_id);exit;
				$this->hotels_model->update_wizard_status($hotel_id,'step4');

				$this->template->view('hotel/seasons/add_seasons',$hotels);
			  }else{
				//try{
					
					$this->seasons_model->add_seasons($_POST);
			  		// debug($_POST);exit();
					$this->hotels_model->update_wizard_status($hotel_id,'step4');
					redirect('seasons/seasons_list/'.$hotel_id1,'refresh');
				/*} catch(Exception $e) {
					$this->General_Model->rollback_transaction();
					return $e;
				}*/
			 }	
			}else{
				$hotels 						= $this->hotels_model->getHomePageSettings();
				$hotels['settings'] 			= $this->hotels_model->get_hotel_settings_list();
				$hotels['hotel_id1'] 	   		= $hotel_id1;
				$hotels['hotel_id']				= $hotel_id;
				$data = $this->db->query("select contract_expire_date from hotel_details where hotel_details_id ='".$hotel_id."'")->result();
				$hotels['contract_expire_date'] = $data[0]->contract_expire_date;
				$hotels['hotels_list'] 			= $this->hotels_model->get_hotel_crs_list("", $hotels);

				// debug($hotels);exit();
				$this->template->view('hotel/seasons/add_seasons',$hotels);
			}
		}else{
			redirect('hotels/hotel_crs_list','refresh');
		}
	} 
	
	function delete_seasons($hotel_id1,$rate_id1){
		$rate_id 	= json_decode(base64_decode($rate_id1));
		$hotel_id 	= json_decode(base64_decode($hotel_id1));
		if($rate_id != ''){
			$this->seasons_model->delete_seasons($rate_id);
		}
		redirect('seasons/seasons_list/'.$hotel_id1,'refresh');
	}

	function inactive_seasons($hotel_id1,$rate_id1){
		$rate_id 	= json_decode(base64_decode($rate_id1));
		if($rate_id != ''){
			$this->seasons_model->inactive_seasons($rate_id);
		}
		redirect('seasons/seasons_list/'.$hotel_id1,'refresh');
	}
	
	function active_seasons($hotel_id1,$rate_id1){
		$rate_id 	= json_decode(base64_decode($rate_id1));
		$hotel_id 	= json_decode(base64_decode($hotel_id1));
		if($rate_id != ''){
			$this->seasons_model->active_seasons($rate_id);
		}
		redirect('seasons/seasons_list/'.$hotel_id1,'refresh');
	}
	
	function edit_seasons($hotel_id1,$rate_id1)
	{ //error_reporting(E_ALL);
		$rate_id 	= json_decode(base64_decode($rate_id1));
		$hotel_id 	= json_decode(base64_decode($hotel_id1));
		if($rate_id != ''){
			$hotels 						= $this->hotels_model->getHomePageSettings();
			$hotels['seasons_list'] 	   	= $this->seasons_model->get_seasons_list($rate_id,$hotel_id,$hotels);
			$hotels['hotels_list'] 			= $this->hotels_model->get_hotel_crs_list("", $hotels);
			$hotels['hotel_id1'] 	   		= $hotel_id1;
			$hotels['settings'] 			= $this->hotels_model->get_hotel_settings_list();
			$hotels['cancellation_policy']   = $this->seasons_model->get_cancellation_policy($rate_id,$hotel_id);
			//echo"<pre>";print_r($hotels['cancellation_policy']);exit();
			$this->template->view('hotel/seasons/edit_seasons',$hotels);
		}else{
			redirect('seasons/seasons_list/'.$hotel_id1,'refresh');
		}
	}
	
	function update_seasons($rate_id1,$hotel_id1){ 
		$rate_id 	= json_decode(base64_decode($rate_id1)); 
		$hotel_id 	= json_decode(base64_decode($hotel_id1));	
		if($rate_id != ''){ 
			if(count($_POST) > 0){			  
			  $this->form_validation->set_rules('hotel_details_id', 'Hotel', 'callback_check_drop');
			  $this->form_validation->set_rules('room_type', 'Room Type', 'callback_check_drop');
			  //$this->form_validation->set_rules('seasons_name', 'Season Name', 'required|min_length[2]|max_length[50]');
			  $this->form_validation->set_rules('seasons_date_range', 'Season Date Range', 'required|min_length[23]|max_length[23]');			  
			  $this->form_validation->set_rules('minimum_stays', 'Minimum Stays', 'required|numeric|min_length[1]|max_length[2]');
			  				  
			  //$this->form_validation->set_rules('cancellation_percentage', 'Percentage', 'callback_check_charge');					  
			
			  if ($this->form_validation->run() == FALSE) { //Validation false					 	                    					                      				    
			  	$hotels 						= $this->hotels_model->getHomePageSettings();
				$hotels['settings'] 			= $this->hotels_model->get_hotel_settings_list();
				$hotels['hotels_list'] 			= $this->hotels_model->get_hotel_crs_list("", $hotels);
				$hotels['hotel_id1'] 	   		= $hotel_id1;
				$hotels['hotel_id']				= $hotel_id;				
			  	$hotels['hotel_details_id'] = $this->input->post('hotel_details_id');
			  	$hotels['room_type'] = $this->input->post('room_type');
			  	$hotels['seasons_name'] = $this->input->post('seasons_name');
			  	$hotels['seasons_date_range'] = $this->input->post('seasons_date_range');
			  	$hotels['minimum_stays'] = $this->input->post('minimum_stays');			  	
			  	//$hotels['season_type'] = $this->input->post('season_type');			  	
			  	//$hotels['cutoff'] = $this->input->post('cutoff');	
			  	$hotels['season_detail_id'] = $rate_id;
			  	//$hotels['status'] = $this->input->post('status');
			  	/*$cancellation_from = $this->input->post('cancellation_from');		  	
			  	$cancellation_to = $this->input->post('cancellation_to');		  	
			  	$cancellation_nightcharge = $this->input->post('cancellation_nightcharge');		  	
			  	$cancellation_percentage = $this->input->post('cancellation_percentage');		  	
			  	$cancellation_policy_id = $this->input->post('cancellation_policy_id');		  	
			  	$policy = array();*/

			  	/*for($loop =0; $loop <count($cancellation_from); $loop++){
			  		$policy[$loop] = array(
			  			  'cancellation_from' => $cancellation_from[$loop],
			  			  'cancellation_to'  => $cancellation_to[$loop],
			  			  'cancellation_night_charge' => $cancellation_nightcharge[$loop],
			  			  'cancellation_percentage_charge' => $cancellation_percentage[$loop],
			  			  'cancellation_policy_id' => $cancellation_policy_id[$loop]
			  			);			  		
			  	}
			  	$hotels['policy'] = $policy;	*/		  	
				//echo "<pre>";print_r($hotels['policy']);
				$this->template->view('hotel/seasons/edit_seasons',$hotels);
			  }else{				
				//try{
					//print_r($_POST);exit();	
					
					$this->seasons_model->update_seasons($_POST,$rate_id);
					
					redirect('seasons/seasons_list/'.$hotel_id1,'refresh');
				/*} catch(Exception $e) {
			  		$this->General_Model->rollback_transaction();
			  		return $e;
				}*/
			 }//else	
			}else{
			  redirect('seasons/seasons_list/'.$hotel_id1,'refresh');
			}
		}else{
			redirect('seasons/seasons_list/'.$hotel_id1,'refresh');
		}
	} 	
	function get_room_type($hotel_id = "",$select = ""){	
		$options = '';
        $options = '<option value=0>Select</option>';
        if (($hotel_id) != "") {
                //$returnval = $this->General_Model->get_table_data('hotel_room_type_id,room_type_name', 'hotel_room_type', 'hotel_details_id', $hotel_id);
        	    $hotels 						= $this->hotels_model->getHomePageSettings();
        		$returnval = $this->hotels_model->get_hotel_room_types_list($hotel_id,$hotels,'ACTIVE');
                if ($returnval != "") {
                    foreach ($returnval as $rowval) {
                        $selected = $rowval->hotel_room_type_id == $select ? 'selected' : '';
                        $options .= '<option value="' . $rowval->hotel_room_type_id . '"' . $selected . '>' . $rowval->room_type_name . '</option>';
                    }//for  
                }//if                            
        }        
        echo $options;
	}

	function get_season_room_type_room($room_type_id,$select = ""){
		$options = ''; 
		//$adult_count = $child_count = ''
        $options = '<option value=0>Select</option>';
        if (($room_type_id) != "") {
        	//$returnval = $this->General_Model->get_table_data('seasons_details_id,seasons_name', 'seasons_details', 'hotel_room_type_id', $room_type_id);
        	 $returnval = $this->seasons_model->get_season_room_type($room_type_id,'ACTIVE');
        	 
        	 if ($returnval != "") {
                    foreach ($returnval as $rowval) { 
                        $selected = $rowval->seasons_details_id == $select ? 'selected' : ''; 
                        $options .= '<option value="' . $rowval->seasons_details_id . '"' . $selected . '>' . $rowval->seasons_name . '</option>';


                    }//for  
                }//if  

 	
        }	
        //$options = $room_type_id;
        
        
        //$data['adult_count'] = $adult_count;
        //$data['child_count'] = $child_count;
        echo $options;
        //echo $options;
	}

	function get_season_room_type($room_type_id,$select = ""){
		$options = ''; 
		//$adult_count = $child_count = ''
        $options .= "<option value='0'>Select</option>";
        if (($room_type_id) != "") {
        	//$returnval = $this->General_Model->get_table_data('seasons_details_id,seasons_name', 'seasons_details', 'hotel_room_type_id', $room_type_id);
        	 $returnval = $this->seasons_model->get_season_room_type($room_type_id,'ACTIVE');
        	 
        	 if ($returnval != "") {
                    foreach ($returnval as $rowval) { 
                        $selected = $rowval->seasons_details_id == $select ? 'selected' : ''; 
                        $options .= '<option value="' . $rowval->seasons_details_id . '"' . $selected . '>' . $rowval->seasons_name . '</option>';


                    }//for  
                }//if  
        }	
       $data['options'] = $options;
       echo json_encode($data);
	}


	function check_drop($val){
		if($val == "0" || $val == ""){
			$this->form_validation->set_message('check_drop', 'Please select %s');
            return false;	
		}
	}

	function check_type($val){
		if($val == "0" || $val == ""){
			$this->form_validation->set_message('check_type', 'Please select %s');
            return false;	
		}
		if($val == 2){
			$cutoff =$this->input->post('cutoff');
			if($cutoff == "" || $cutoff < 0 || $cutoff > 100){
				$this->form_validation->set_message('check_type', 'Please enter the proper cutoff days');
            	return false;	
			}
		}
	}

	function check_value($val){				
		for($loop =0 ;$loop < sizeof($val);$loop++){
		 if(!is_numeric($val[$loop])){
		   $this->form_validation->set_message('check_value', 'Please enter proper digit in %s');
           return false;		 		            		
		 }		
		 if($val[$loop] == "" || $val[$loop] < 0 || $val[$loop] > 1000){
		 		$this->form_validation->set_message('check_value', 'Please enter the proper (0 to 1000) digit %s');
            	return false;	
		 }			
		}
		
	}

	function check_charge($val){	
	error_reporting(E_ALL);			
		$night = $this->input->post('cancellation_nightcharge');
		$percentage = $this->input->post('cancellation_percentage');				
		for ($loop = 0; $loop < sizeof($night) ; $loop++) { 			
			/*if(($night[$loop] != "" && $percentage[$loop] != "") || ($night[$loop] == "" && $percentage[$loop] == "")) {							
				$this->form_validation->set_message('check_charge', 'Please enter no of night or charges');
            	return false;		
			}
			else{*/
			  if($night[$loop] != ""){	
			   if(!is_numeric($night[$loop])){
				$this->form_validation->set_message('check_charge', 'Please enter proper digit in Night');
            	return false;		 		            		
			   }	
			  }
			  if($percentage[$loop] != ""){	 
			   if(!is_numeric($percentage[$loop])){
				$this->form_validation->set_message('check_charge', 'Please enter proper digit in Percentage');
            	return false;		 		            		
			   }	
			  } 
			  if($night[$loop] < 0 || $night[$loop] > 1000){			  	
			 	$this->form_validation->set_message('check_charge', 'Please enter proper (0 to 1000) digit in Night');
            	return false;		 		            	
			  }		
			  if($percentage[$loop] < 0 || $percentage[$loop] > 100){			  	
			 	$this->form_validation->set_message('check_charge', 'Please enter proper (0 to 100) digit in Percentage');
            	return false;		 		            	
			  }		
			//}
		}
	}

	function delete_cancellation_policy($policy_id = ""){
		echo $policy_id;
		if($policy_id != ""){
		  $ks = $this->Seasons_Model->delete_cancellation_policy($policy_id);	
		  echo $ks;
		} 
	}
}
