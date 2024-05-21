<?php
class Roomrate_Model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    } 
    
    	function update_nationality($hotel_room_rate_info_id,$hotel_room_rate_id)
		{
		    //debug($hotel_room_rate_id);exit;
		  
	    $this->db->select('*');
	    $this->db->from('hotel_room_rate_info');
	    $this->db->where('hotel_room_rate_info_id',$hotel_room_rate_info_id);
	    $hotel_room_count = $this->db->get()->result_array();
	    
	    if($hotel_room_count[0]['hotel_details_id'])
	    {
    	    $this->db->select('*');
    	    $this->db->from('hotel_room_rate_info');
    	    $this->db->where('hotel_details_id',$hotel_room_count[0]['hotel_details_id']);
    	    $hotel_room_count_new = $this->db->get()->result_array(); 
    	  
    	     //taking all id in hotel_room_rate_info:
    	     $all_info_id=array();
    	     foreach($hotel_room_count_new as $hrcn)
    	     {
   	            $all_info_id[]=$hrcn['hotel_room_rate_info_id'];
    	     }
    	    
    	    foreach($all_info_id as $id)
    	    {
        	    $this->db->select('*');
        	    $this->db->from('hotel_room_rate');
        	    $this->db->where('hotel_rome_rate_info_id',$id);
        	    $hotel_room_price = $this->db->get()->result_array(); 
        	    
        	    foreach($hotel_room_price as $hrp)
        	    {
        	      
        	      	$data = array(
    					'default_nationality' => ''
    					);
            		$this->db->where('hotel_room_rate_id', $hrp['hotel_room_rate_id']);
            		$this->db->update('hotel_room_rate', $data);
        	    }
    	  
    	    }
    	    
    	    
    	    
	    }
	//debug("last");exit;
		$data = array(
    					'default_nationality' => 'default'
    					);
            		$this->db->where('hotel_room_rate_id', $hotel_room_rate_id);
            		$this->db->update('hotel_room_rate', $data);
	   
	}

    function get_room_list_by_hotel($hotel_id){
		$this->db->select('hr.*,ht.room_type_name,ht.extra_bed');
		$this->db->from('hotel_room_details hr');
	    $this->db->join('hotel_room_type ht', 'ht.hotel_room_type_id = hr.hotel_room_type_id');
	    if($hotel_id !=''){
			$this->db->where('hr.hotel_details_id', $hotel_id);
		}
	     if($this->session->userdata('lgm_supplier_admin_logged_in') == "Logged_In"){
			$this->db->join('hotel_details hd', 'hd.hotel_details_id = hr.hotel_details_id');
			$this->db->where('hd.hotel_added_by_supplier', $this->session->userdata('lgm_supplier_admin_id'));
		   }
		$query=$this->db->get();
		if($query->num_rows() ==''){
			return '';
		}else{
			return $query->result();
		}
	}
	
	function get_extra_bed($id = ''){
			$this->db->select('extra_bed');
			$this->db->from('hotel_room_type');
			if($id !='')
			$this->db->where('hotel_room_type_id',$id);
			$query = $this->db->get();
			if($query->num_rows() ==''){
			return '';
			}else{
				return $query->result();
			}
   }
  	function new_get_room_rate_list($id = "",$hotels,$room_rate_id_new=""){
  	   
	    /*debug($hotels);
	    debug($id);
	    exit;*/
	   // debug("came");
	    //debug($room_rate_id_new);exit;
		$this->db->select('*');
		$this->db->from('hotel_room_rate_info hrm');		
		if($hotels['supplier_rights'] == 1){
           $this->db->where('hrm.room_rate_added_by_supplier', $hotels['admin_id']);
		}
		if($id !='')
			$this->db->where('hrm.hotel_room_rate_info_id', $id);

		$this->db->join('hotel_room_type ht', 'ht.hotel_room_type_id = hrm.hotel_room_type_id');
		$this->db->join('hotel_details h', 'h.hotel_details_id = hrm.hotel_details_id');		
		$this->db->join('seasons_details sd', 'hrm.seasons_details_id = sd.seasons_details_id');
		$this->db->join('hotel_room_rate hrr','hrr.hotel_rome_rate_info_id = hrm.hotel_room_rate_info_id','left');
		$this->db->join('country_list_nationality cln','cln.country_list = hrr.country_list_nationality_id','left');
		
		
		$this->db->where('hrr.hotel_room_rate_id', $room_rate_id_new);
		
		$this->db->order_by('hrm.hotel_room_rate_info_id','desc');
		$query=$this->db->get();
	//	debug($query);exit;
		//debug($query);exit;
		if($query->num_rows() ==''){
			return '';
		}else{
			return $query->result();
		}
	}
	
/*	function new_get_room_rate_list_old($id = "",$hotels){
		$this->db->select('*');
		$this->db->from('hotel_room_rate_info hrm');		
		if($hotels['supplier_rights'] == 1){
           $this->db->where('hrm.room_rate_added_by_supplier', $hotels['admin_id']);
		}
		if($id !='')
			$this->db->where('hrm.hotel_room_rate_info_id', $id);

		$this->db->join('hotel_room_type ht', 'ht.hotel_room_type_id = hrm.hotel_room_type_id');
		$this->db->join('hotel_details h', 'h.hotel_details_id = hrm.hotel_details_id');		
		$this->db->join('seasons_details sd', 'hrm.seasons_details_id = sd.seasons_details_id');
		$this->db->join('hotel_room_rate hrr','hrr.hotel_rome_rate_info_id = hrm.hotel_room_rate_info_id','left');
		$this->db->order_by('hrm.hotel_room_rate_info_id','desc');
		$query=$this->db->get();
		if($query->num_rows() ==''){
			return '';
		}else{
			return $query->result();
		}
	}
*/
	function new_hotel_get_room_rate_list($id = "",$hotels){
		$this->db->select('*');
		$this->db->from('hotel_room_rate_info_crs hrm');		
		if($hotels['supplier_rights'] == 1){
           $this->db->where('hrm.room_rate_added_by_supplier', $hotels['admin_id']);
		}
		if($id !='')
			$this->db->where('hrm.hotel_room_rate_info_id', $id);

		$this->db->join('hotel_room_type ht', 'ht.hotel_room_type_id = hrm.hotel_room_type_id');
		$this->db->join('hotel_details h', 'h.hotel_details_id = hrm.hotel_details_id');		
		
		$this->db->join('hotel_room_rate_crs hrr','hrr.hotel_rome_rate_info_id = hrm.hotel_room_rate_info_id','left');
		$this->db->order_by('hrm.hotel_room_rate_info_id','desc');
		$query=$this->db->get();
		if($query->num_rows() ==''){
			return '';
		}else{
			return $query->result();
		}
	}

		function new_get_room_rate_list_byHotelName($id = "",$hotels,$hotel_name)
		{ //echo str_replace('"', '', $hotel_name);exit;
			$this->db->select('*');
			$this->db->from('hotel_room_rate_info hrm');		
			if($hotels['supplier_rights'] == 1){
	           $this->db->where('hrm.room_rate_added_by_supplier', $hotels['admin_id']);
	       }
	       $this->db->where('h.hotel_name', str_replace('"', '', $hotel_name));
			if($id !='')
				$this->db->where('hrm.hotel_room_rate_info_id', $id);

			$this->db->join('hotel_room_type ht', 'ht.hotel_room_type_id = hrm.hotel_room_type_id');
			$this->db->join('hotel_details h', 'h.hotel_details_id = hrm.hotel_details_id');		
			$this->db->join('seasons_details sd', 'hrm.seasons_details_id = sd.seasons_details_id');
			$this->db->join('hotel_room_rate hrr','hrr.hotel_rome_rate_info_id = hrm.hotel_room_rate_info_id','left');
			$this->db->join('country_list_nationality cln','cln.country_list = hrr.country_list_nationality_id','left');
			$this->db->order_by('hrm.hotel_room_rate_info_id','desc');
			$query=$this->db->get();
			//echo $this->db->last_query();exit;
			if($query->num_rows() ==''){
				return '';
			}else{
				return $query->result(); 
			//$query->result();
			//echo $this->db->last_query();exit;
				
			}
		}

		function new_hotel_get_room_rate_list_byHotelName($id = "",$hotels,$hotel_name)
		{ //echo str_replace('"', '', $hotel_name);exit;
			$this->db->select('*');
			$this->db->from('hotel_room_rate_info_crs hrm');		
			if($hotels['supplier_rights'] == 1){
	           $this->db->where('hrm.room_rate_added_by_supplier', $hotels['admin_id']);
	       }
	       $this->db->where('h.hotel_name', str_replace('"', '', $hotel_name));
			if($id !='')
				$this->db->where('hrm.hotel_room_rate_info_id', $id);

			$this->db->join('hotel_room_type ht', 'ht.hotel_room_type_id = hrm.hotel_room_type_id');
			$this->db->join('hotel_details h', 'h.hotel_details_id = hrm.hotel_details_id');		
			
			$this->db->join('hotel_room_rate_crs hrr','hrr.hotel_rome_rate_info_id = hrm.hotel_room_rate_info_id','left');
			$this->db->order_by('hrm.hotel_room_rate_info_id','desc');
			$query=$this->db->get();
			//echo $this->db->last_query();exit;
			if($query->num_rows() ==''){
				return '';
			}else{
				return $query->result(); 
			//$query->result();
			//echo $this->db->last_query();exit;
				
			}
		}


	function new_get_blockout_date($id){
		$this->db->select('*');
		$this->db->where('hotel_roomrateinfo_id',$id);
		$this->db->order_by('hotel_blockout_id','asc');
		$query=$this->db->get('hotel_room_rate_block_out');
		//echo $this->db->last_query();
		if($query->num_rows() ==''){
			return '';
		}else{
			return $query->result();
		}	
	}
    function get_room_rate_list($id = '', $hotels){
		$this->db->select('hrm.*,ht.room_type_name,ht.extra_bed,h.hotel_name,t.gst,t.gst_green_tax,t.green_tax,t.sc_applicable,t.service_charge,t.sc_percentage,t.gst_markup,sd.seasons_name');
		$this->db->from('hotel_room_rate_info hrm');

        if($hotels['supplier_rights'] == 1){
           $this->db->where('hrm.room_rate_added_by_supplier', $hotels['admin_id']);
		}

		if($id !='')
			$this->db->where('hrm.hotel_room_rate_info_id', $id);
		$this->db->join('hotel_room_type ht', 'ht.hotel_room_type_id = hrm.hotel_room_type_id');
		$this->db->join('hotel_details h', 'h.hotel_details_id = hrm.hotel_details_id');
		$this->db->join('tax_rate_info t', 't.tax_rate_info_id = hrm.tax_rate_info_id');
		$this->db->join('seasons_details sd', 'hrm.seasons_details_id = sd.seasons_details_id');
		
		  if($this->session->userdata('lgm_supplier_admin_logged_in') == "Logged_In"){
			$this->db->where('h.hotel_added_by_supplier', $this->session->userdata('lgm_supplier_admin_id'));
		   }
		   
		$query=$this->db->get();
		if($query->num_rows() ==''){
			return '';
		}else{
			return $query->result();
		}
	}
	function crsCurrencyConversion($from_Currency)
	{
		$to_Currency = COURSE_LIST_DEFAULT_CURRENCY_VALUE;
		$url = "http://free.currencyconverterapi.com/api/v3/convert?q=".$from_Currency."_".$to_Currency."&compact=ultra";
		// echo $url;die;
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL,$url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    
	    $result = curl_exec($ch);
	    //$data = explode("1 US Dollar = ",$result);
	     
	    
	    //echo "data:<pre/>";print_r(json_decode($result, true));exit();
	    
	    $converted_currency1 = json_decode($result, true);
	    $converted_currency = $converted_currency1[$from_Currency."_".$to_Currency];
	    return $converted_currency;
	    //print_r($converted_currency);exit();
	}
		function new_add_room_rate($input){ 
	    //debug($input);exit;
	    
		$conversion_rate = $this->crsCurrencyConversion($input['currency']);
		//debug($input);die;
		if(!isset($input['gst']))
			$input['gst'] = "Exclusive";

		if(!isset($input['service_charge']))
			$input['service_charge'] = "Exclusive";	

		if(!isset($input['promotion']))
			$input['promotion'] = "";

		if(!isset($input['child_price_a']))
			$input['child_price_a'] = 0;
		if(!isset($input['child_price_b']))
			$input['child_price_b'] = 0;
		if(!isset($input['child_price_c']))
			$input['child_price_c'] = 0;
		if(!isset($input['child_price_d']))
			$input['child_price_d'] = 0;
		if(!isset($input['child_price_e']))
			$input['child_price_e'] = 0;
		if(!isset($input['extra_bed_price_total']))
			$input['extra_bed_price_total'] = 0;
		if(!isset($input['extra_bed_price']))
			$input['extra_bed_price'] = 0;

		if(!isset($input['child_extra_bed_price']))
			$input['child_extra_bed_price'] = 0;
		if(!isset($input['adult_extra_bed_price']))
			$input['adult_extra_bed_price'] = 0;

		if(!isset($input['week_dbl_price']))
			$input['week_dbl_price'] = 0;

		if(!isset($input['week_trp_price']))
			$input['week_trp_price'] = 0;

        if(!isset($input['currency']))
            $input['currency'] = 'AUD';

		/*if(!isset($input['week_quad_price']))
			$input['week_quad_price'] = 0;

		if(!isset($input['week_hex_price']))
			$input['week_hex_price'] = 0;*/

		if(!isset($input['week_bedroom_price']))
			$input['week_bedroom_price'] = 0;
		if(!isset($input['week_bedroom_adult_bf']))
			$input['week_bedroom_adult_bf'] = 0;
		if(!isset($input['week_bedroom_child_bf']))
			$input['week_bedroom_child_bf'] = 0;

		if(!isset($input['weekend_sgl_price']))
			$input['weekend_sgl_price'] = 0;
		if(!isset($input['weekend_single_adult_bf']))
			$input['weekend_single_adult_bf'] = 0;
		if(!isset($input['weekend_single_child_bf']))
			$input['weekend_single_child_bf'] = 0;		
		
		if(!isset($input['weekend_dbl_price']))
			$input['weekend_dbl_price'] = 0;
		if(!isset($input['weekend_double_adult_bf']))
			$input['weekend_double_adult_bf'] = 0;		
		if(!isset($input['weekend_double_child_bf']))
			$input['weekend_double_child_bf'] = 0;

		if(!isset($input['weekend_tpl_price']))
			$input['weekend_tpl_price'] = 0;
		if(!isset($input['weekend_triple_adult_bf']))
			$input['weekend_triple_adult_bf'] = 0;		
		if(!isset($input['weekend_triple_child_bf']))
			$input['weekend_triple_child_bf'] = 0;

		/*if(!isset($input['weekend_quad_price']))
			$input['weekend_quad_price'] = 0;
		if(!isset($input['weekend_quad_adult_bf']))
			$input['weekend_quad_adult_bf'] = 0;		
		if(!isset($input['weekend_quad_child_bf']))
			$input['weekend_quad_child_bf'] = 0;

		if(!isset($input['weekend_hex_price']))
			$input['weekend_hex_price'] = 0;
		if(!isset($input['weekend_hex_adult_bf']))
			$input['weekend_hex_adult_bf'] = 0;		
		if(!isset($input['weekend_hex_child_bf']))
			$input['weekend_hex_child_bf'] = 0;*/

		if(!isset($input['weekend_bedroom_price']))
			$input['weekend_bedroom_price'] = 0;
		if(!isset($input['weekend_bedroom_adult_bf']))
			$input['weekend_bedroom_adult_bf'] = 0;
		if(!isset($input['weekend_bedroom_child_bf']))
			$input['weekend_bedroom_child_bf'] = 0;		

		$excluded_country="";
		$included_country="";						
		foreach($input['include_country'] as $ammenities){
			$included_country .= $ammenities.',';
		}
		foreach($input['exclude_country'] as $ammenities){
			$excluded_country .= $ammenities.',';
		}

		$date_range[0] = $date_range[1] = '';
		if(isset($input['date_rane_rate']) && $input['date_rane_rate']!='')
			$date_range = explode(" - ",$input['date_rane_rate']);	 
			//My Code
			$fromorderdate = explode('/', $date_range[0]);
			
				$monthfrom = $fromorderdate[1];
				$dayfrom   = $fromorderdate[0];
				$yearfrom  = $fromorderdate[2];
		 	$fromdate = $monthfrom.'/'.$dayfrom.'/'.$yearfrom;

		 	$toorderdate = explode('/', trim($date_range[1]));
			
				$monthto = $toorderdate[1];
				$dayto   = $toorderdate[0];
				$yearto  = $toorderdate[2];
		 	$todate = $monthto.'/'.$dayto.'/'.$yearto;

			//End My code
			$date_range[0] = date_format(date_create(trim($fromdate)), 'Y-m-d');
			$date_range[1] = date_format(date_create(trim($todate)), 'Y-m-d'); 

			//echo '<pre>'; print_r($date_range[1]); exit();
			//$date_range[0] = date('Y-m-d',strtotime(trim($date_range[0])));
			//$date_range[1] = date('Y-m-d',strtotime(trim($date_range[1])));

			$insert_data = array(
							'seasons_details_id' 			=> $input['seasons_details_id'],
							'hotel_details_id' 				=> $input['hotel_details_id'],
							'hotel_room_type_id' 			=> $input['room_details_id'],							
							'from_date' 					=> $date_range[0],
							'to_date' 						=> $date_range[1],							
							'roomrate_status' 		  			    => 'ACTIVE',										
							//'room_promotion'				=> $input['promotion'],
							//'rate_type'						=> $input['room_rate_type'],
							//'room_rate_cancellation_policy'			=> $input['cancellation_policy'],
                            'currency'                  => COURSE_LIST_DEFAULT_CURRENCY_VALUE,
							'weekend_price'					=> round(($input['week_end_select'] * $conversion_rate), 2),
							//'included_country'				=> $included_country,
							//'excluded_country'				=> $excluded_country,
							'gst_tax'						=> $input['gst'],
							'service_tax'					=> $input['service_charge'],



						);
			

			if($input['supplier_rights'] == 1 ){
		 		$insert_data['room_rate_added_by_supplier'] =$this->session->userdata('lgm_supplier_admin_id') ;
			} else {
		 		$insert_data['room_rate_added_by_mgmt'] = $this->entity_user_id ;
			}	

			$query = $this->db->insert('hotel_room_rate_info',$insert_data);
			$hotel_room_rate_info_id = $this->db->insert_id();

			//added :starts
						
						$price_array=array();
			$price_array=$input['price'];
		//	debug($input['price']);exit;
			foreach($price_array as $pa)
			{



$insert_data = array(
						'hotel_rome_rate_info_id' => $hotel_room_rate_info_id,
					
							'room_child_price_a' => round(($pa['child_price_a'] ) , 2),
						'room_child_price_b' => round(($pa['child_price_b'] ) , 2),
						'room_child_price_c' => round(($input['child_price_c'] * $conversion_rate) , 2),
						'room_child_price_d' => round(($input['child_price_d'] * $conversion_rate) , 2),
						'room_child_price_e' => round(($input['child_price_e'] * $conversion_rate) , 2),
					/*	'child_extra_bed_price' => round(($input['child_extra_bed_price'] * $conversion_rate) , 2),
						'adult_extra_bed_price'	=> round(($input['adult_extra_bed_price'] * $conversion_rate) , 2),*/
							'child_extra_bed_price' => round(($pa['child_extra_bed_price'] ) , 2),
						'adult_extra_bed_price'	=> round(($pa['adult_extra_bed_price'] ) , 2),
				
						'single_room_price' => round(($pa['week_sgl_price'] ) , 2),
						'single_adult_bk' => $input['week_single_adult_bf'],
						'single_child_bk' => $input['week_single_child_bf'],
					
						'double_room_price' => round(($pa['week_dbl_price'] ) , 2),
						'double_adult_bk' => round(($input['week_double_adult_bf'] * $conversion_rate) , 2),
						'double_child_bk' => round(($input['week_double_child_bf'] * $conversion_rate) , 2),
						
						'triple_room_price' => round(($pa['week_trp_price'] ) , 2),
						'triple_adult_bk' => $input['week_trp_adult_bf'],
						'triple_child_bk' => $input['week_trp_child_bf'],
						
						'adult_room_bed_price' => round(($input['week_bedroom_price'] * $conversion_rate) , 2),
						'room_bed_adult_bk' => $input['week_bedroom_adult_bf'],
						'room_bed_child_bk' => $input['week_bedroom_child_bf'],
						'weekend_single_room_price' => round(($input['weekend_sgl_price'] * $conversion_rate) , 2),
  						'weekend_single_adult_bk'	=> $input['weekend_single_adult_bf'],	
  						'weekend_single_child_bk'	=> $input['weekend_single_child_bf'],
  						'weekend_double_room_price' => round(($input['weekend_dbl_price'] * $conversion_rate) , 2),
  						'weekend_double_adult_bk'	=> $input['weekend_double_adult_bf'], 
  						'weekend_double_child_bk'	=> $input['weekend_double_child_bf'],
  						'weekend_triple_room_price' => round(($input['weekend_tpl_price'] * $conversion_rate) , 2),
  						'weekend_triple_adult_bk'	=> $input['weekend_triple_adult_bf'], 
  						'weekend_triple_child_bk'	=> $input['weekend_triple_child_bf'],
						
  						'weekend_adult_room_bed_price' => round(($input['weekend_bedroom_price'] * $conversion_rate), 2),
  						'weekend_room_bed_adult_bk'	=> $input['weekend_bedroom_adult_bf'], 
  						'weekend_room_bed_child_bk'	=> $input['weekend_bedroom_child_bf'],
                        'currency'                  => COURSE_LIST_DEFAULT_CURRENCY_VALUE,
                        'country_list_nationality_id' =>$pa['currency'],
				);
			//debug($insert_data);exit;
		    $query = $this->db->insert('hotel_room_rate',$insert_data);

			$hotel_room_rate = $this->db->insert_id();




			//debug($pa['currency']);
				/*$price_data = array(
						'country_list_nationality_id' =>$pa['currency'],
						'child_price_a' =>$pa['child_price_a'],
						'child_price_b' =>$pa['child_price_b'],
						'week_sgl_price' =>$pa['week_sgl_price'],
						'week_dbl_price' =>$pa['week_dbl_price'],
						'week_trp_price' =>$pa['week_trp_price'],
						
					)*/
			}
			
			//added :ends
			
			
				//code to add DEFAULT add price:starts
		/*	if($input['default_price_checkbox'])
			{
			   // debug("if");
			   $insert_data_default = array(
						'hotel_rome_rate_info_id' => $hotel_room_rate_info_id,
					
						'room_child_price_a' => round(($input['default_child_price_a'] ) , 2),
						'room_child_price_b' => round(($input['default_child_price_b'] ) , 2),
						'room_child_price_c' => round(($input['child_price_c'] * $conversion_rate) , 2),
						'room_child_price_d' => round(($input['child_price_d'] * $conversion_rate) , 2),
				
							'child_extra_bed_price' => round(($input['default_child_extra_bed_price'] ) , 2),
						'adult_extra_bed_price'	=> round(($input['default_adult_extra_bed_price'] ) , 2),
				
						'single_room_price' => round(($input['default_week_sgl_price'] ) , 2),
						'single_adult_bk' => $input['week_single_adult_bf'],
						'single_child_bk' => $input['week_single_child_bf'],
					
						'double_room_price' => round(($input['default_week_dbl_price'] ) , 2),
						'double_adult_bk' => round(($input['week_double_adult_bf'] * $conversion_rate) , 2),
						'double_child_bk' => round(($input['week_double_child_bf'] * $conversion_rate) , 2),
						
						'triple_room_price' => round(($input['default_week_trp_price'] ) , 2),
						'triple_adult_bk' => $input['week_trp_adult_bf'],
						'triple_child_bk' => $input['week_trp_child_bf'],
						
						'adult_room_bed_price' => round(($input['week_bedroom_price'] * $conversion_rate) , 2),
						'room_bed_adult_bk' => $input['week_bedroom_adult_bf'],
						'room_bed_child_bk' => $input['week_bedroom_child_bf'],
						'weekend_single_room_price' => round(($input['weekend_sgl_price'] * $conversion_rate) , 2),
  						'weekend_single_adult_bk'	=> $input['weekend_single_adult_bf'],	
  						'weekend_single_child_bk'	=> $input['weekend_single_child_bf'],
  						'weekend_double_room_price' => round(($input['weekend_dbl_price'] * $conversion_rate) , 2),
  						'weekend_double_adult_bk'	=> $input['weekend_double_adult_bf'], 
  						'weekend_double_child_bk'	=> $input['weekend_double_child_bf'],
  						'weekend_triple_room_price' => round(($input['weekend_tpl_price'] * $conversion_rate) , 2),
  						'weekend_triple_adult_bk'	=> $input['weekend_triple_adult_bf'], 
  						'weekend_triple_child_bk'	=> $input['weekend_triple_child_bf'],
						
  						'weekend_adult_room_bed_price' => round(($input['weekend_bedroom_price'] * $conversion_rate), 2),
  						'weekend_room_bed_adult_bk'	=> $input['weekend_bedroom_adult_bf'], 
  						'weekend_room_bed_child_bk'	=> $input['weekend_bedroom_child_bf'],
                        'currency'                  => COURSE_LIST_DEFAULT_CURRENCY_VALUE,
                        //'country_list_nationality_id' =>"default",
                        'country_list_nationality_id' =>$input['default_country_name'],
                         'default_nationality' =>'default',
				);
			//debug($insert_data);exit;
		    $query = $this->db->insert('hotel_room_rate',$insert_data_default);

			$hotel_room_rate_default = $this->db->insert_id();
			    
			}
			else{
			    //debug("else");
			    
			}
			*/
			
			//code to add DEFAULT price:ends
		


	}
	function new_add_room_rate_old($input){ 
		$conversion_rate = $this->crsCurrencyConversion($input['currency']);
		//debug($input);die;
		if(!isset($input['gst']))
			$input['gst'] = "Exclusive";

		if(!isset($input['service_charge']))
			$input['service_charge'] = "Exclusive";	

		if(!isset($input['promotion']))
			$input['promotion'] = "";

		if(!isset($input['child_price_a']))
			$input['child_price_a'] = 0;
		if(!isset($input['child_price_b']))
			$input['child_price_b'] = 0;
		if(!isset($input['child_price_c']))
			$input['child_price_c'] = 0;
		if(!isset($input['child_price_d']))
			$input['child_price_d'] = 0;
		if(!isset($input['child_price_e']))
			$input['child_price_e'] = 0;
		if(!isset($input['extra_bed_price_total']))
			$input['extra_bed_price_total'] = 0;
		if(!isset($input['extra_bed_price']))
			$input['extra_bed_price'] = 0;

		if(!isset($input['child_extra_bed_price']))
			$input['child_extra_bed_price'] = 0;
		if(!isset($input['adult_extra_bed_price']))
			$input['adult_extra_bed_price'] = 0;

		if(!isset($input['week_dbl_price']))
			$input['week_dbl_price'] = 0;

		if(!isset($input['week_trp_price']))
			$input['week_trp_price'] = 0;

        if(!isset($input['currency']))
            $input['currency'] = 'AUD';

		/*if(!isset($input['week_quad_price']))
			$input['week_quad_price'] = 0;

		if(!isset($input['week_hex_price']))
			$input['week_hex_price'] = 0;*/

		if(!isset($input['week_bedroom_price']))
			$input['week_bedroom_price'] = 0;
		if(!isset($input['week_bedroom_adult_bf']))
			$input['week_bedroom_adult_bf'] = 0;
		if(!isset($input['week_bedroom_child_bf']))
			$input['week_bedroom_child_bf'] = 0;

		if(!isset($input['weekend_sgl_price']))
			$input['weekend_sgl_price'] = 0;
		if(!isset($input['weekend_single_adult_bf']))
			$input['weekend_single_adult_bf'] = 0;
		if(!isset($input['weekend_single_child_bf']))
			$input['weekend_single_child_bf'] = 0;		
		
		if(!isset($input['weekend_dbl_price']))
			$input['weekend_dbl_price'] = 0;
		if(!isset($input['weekend_double_adult_bf']))
			$input['weekend_double_adult_bf'] = 0;		
		if(!isset($input['weekend_double_child_bf']))
			$input['weekend_double_child_bf'] = 0;

		if(!isset($input['weekend_tpl_price']))
			$input['weekend_tpl_price'] = 0;
		if(!isset($input['weekend_triple_adult_bf']))
			$input['weekend_triple_adult_bf'] = 0;		
		if(!isset($input['weekend_triple_child_bf']))
			$input['weekend_triple_child_bf'] = 0;

		/*if(!isset($input['weekend_quad_price']))
			$input['weekend_quad_price'] = 0;
		if(!isset($input['weekend_quad_adult_bf']))
			$input['weekend_quad_adult_bf'] = 0;		
		if(!isset($input['weekend_quad_child_bf']))
			$input['weekend_quad_child_bf'] = 0;

		if(!isset($input['weekend_hex_price']))
			$input['weekend_hex_price'] = 0;
		if(!isset($input['weekend_hex_adult_bf']))
			$input['weekend_hex_adult_bf'] = 0;		
		if(!isset($input['weekend_hex_child_bf']))
			$input['weekend_hex_child_bf'] = 0;*/

		if(!isset($input['weekend_bedroom_price']))
			$input['weekend_bedroom_price'] = 0;
		if(!isset($input['weekend_bedroom_adult_bf']))
			$input['weekend_bedroom_adult_bf'] = 0;
		if(!isset($input['weekend_bedroom_child_bf']))
			$input['weekend_bedroom_child_bf'] = 0;		

		$excluded_country="";
		$included_country="";						
		foreach($input['include_country'] as $ammenities){
			$included_country .= $ammenities.',';
		}
		foreach($input['exclude_country'] as $ammenities){
			$excluded_country .= $ammenities.',';
		}

		$date_range[0] = $date_range[1] = '';
		if(isset($input['date_rane_rate']) && $input['date_rane_rate']!='')
			$date_range = explode(" - ",$input['date_rane_rate']);	 
			//My Code
			$fromorderdate = explode('/', $date_range[0]);
			
				$monthfrom = $fromorderdate[1];
				$dayfrom   = $fromorderdate[0];
				$yearfrom  = $fromorderdate[2];
		 	$fromdate = $monthfrom.'/'.$dayfrom.'/'.$yearfrom;

		 	$toorderdate = explode('/', trim($date_range[1]));
			
				$monthto = $toorderdate[1];
				$dayto   = $toorderdate[0];
				$yearto  = $toorderdate[2];
		 	$todate = $monthto.'/'.$dayto.'/'.$yearto;

			//End My code
			$date_range[0] = date_format(date_create(trim($fromdate)), 'Y-m-d');
			$date_range[1] = date_format(date_create(trim($todate)), 'Y-m-d'); 

			//echo '<pre>'; print_r($date_range[1]); exit();
			//$date_range[0] = date('Y-m-d',strtotime(trim($date_range[0])));
			//$date_range[1] = date('Y-m-d',strtotime(trim($date_range[1])));

			$insert_data = array(
							'seasons_details_id' 			=> $input['seasons_details_id'],
							'hotel_details_id' 				=> $input['hotel_details_id'],
							'hotel_room_type_id' 			=> $input['room_details_id'],							
							'from_date' 					=> $date_range[0],
							'to_date' 						=> $date_range[1],							
							'roomrate_status' 		  			    => 'ACTIVE',										
							//'room_promotion'				=> $input['promotion'],
							//'rate_type'						=> $input['room_rate_type'],
							//'room_rate_cancellation_policy'			=> $input['cancellation_policy'],
                            'currency'                  => COURSE_LIST_DEFAULT_CURRENCY_VALUE,
							'weekend_price'					=> round(($input['week_end_select'] * $conversion_rate), 2),
							//'included_country'				=> $included_country,
							//'excluded_country'				=> $excluded_country,
							'gst_tax'						=> $input['gst'],
							'service_tax'					=> $input['service_charge'],

						);
			

			if($input['supplier_rights'] == 1 ){
		 		$insert_data['room_rate_added_by_supplier'] =$this->session->userdata('lgm_supplier_admin_id') ;
			} else {
		 		$insert_data['room_rate_added_by_mgmt'] = $this->entity_user_id ;
			}	

			$query = $this->db->insert('hotel_room_rate_info',$insert_data);
			$hotel_room_rate_info_id = $this->db->insert_id();

			$insert_data = array(
						'hotel_rome_rate_info_id' => $hotel_room_rate_info_id,
						'room_child_price_a' => round(($input['child_price_a'] * $conversion_rate) , 2),
						'room_child_price_b' => round(($input['child_price_b'] * $conversion_rate) , 2),
						'room_child_price_c' => round(($input['child_price_c'] * $conversion_rate) , 2),
						'room_child_price_d' => round(($input['child_price_d'] * $conversion_rate) , 2),
						'room_child_price_e' => round(($input['child_price_e'] * $conversion_rate) , 2),
						'child_extra_bed_price' => round(($input['child_extra_bed_price'] * $conversion_rate) , 2),
						'adult_extra_bed_price'	=> round(($input['adult_extra_bed_price'] * $conversion_rate) , 2),
						'single_room_price' => round(($input['week_sgl_price'] * $conversion_rate) , 2),
						'single_adult_bk' => $input['week_single_adult_bf'],
						'single_child_bk' => $input['week_single_child_bf'],
						'double_room_price' => round(($input['week_dbl_price'] * $conversion_rate) , 2),
						'double_adult_bk' => round(($input['week_double_adult_bf'] * $conversion_rate) , 2),
						'double_child_bk' => round(($input['week_double_child_bf'] * $conversion_rate) , 2),
						'triple_room_price' => round(($input['week_trp_price'] * $conversion_rate) , 2),
						'triple_adult_bk' => $input['week_trp_adult_bf'],
						'triple_child_bk' => $input['week_trp_child_bf'],
						/*'quad_room_price' => $input['week_quad_price'],
						'quad_adult_bk' => $input['week_quad_adult_bf'],
						'quad_child_bk' => $input['week_quad_child_bf'],
						'hex_room_price' => $input['week_hex_price'],
						'hex_adult_bk' => $input['week_hex_adult_bf'],
						'hex_child_bk' => $input['week_hex_child_bf'],*/
						'adult_room_bed_price' => round(($input['week_bedroom_price'] * $conversion_rate) , 2),
						'room_bed_adult_bk' => $input['week_bedroom_adult_bf'],
						'room_bed_child_bk' => $input['week_bedroom_child_bf'],
						'weekend_single_room_price' => round(($input['weekend_sgl_price'] * $conversion_rate) , 2),
  						'weekend_single_adult_bk'	=> $input['weekend_single_adult_bf'],	
  						'weekend_single_child_bk'	=> $input['weekend_single_child_bf'],
  						'weekend_double_room_price' => round(($input['weekend_dbl_price'] * $conversion_rate) , 2),
  						'weekend_double_adult_bk'	=> $input['weekend_double_adult_bf'], 
  						'weekend_double_child_bk'	=> $input['weekend_double_child_bf'],
  						'weekend_triple_room_price' => round(($input['weekend_tpl_price'] * $conversion_rate) , 2),
  						'weekend_triple_adult_bk'	=> $input['weekend_triple_adult_bf'], 
  						'weekend_triple_child_bk'	=> $input['weekend_triple_child_bf'],
						/*'weekend_quad_room_price' => $input['weekend_quad_price'],
  						'weekend_quad_adult_bk'	=> $input['weekend_quad_adult_bf'], 
  						'weekend_quad_child_bk'	=> $input['weekend_quad_child_bf'],
						'weekend_hex_room_price' => $input['weekend_hex_price'],
  						'weekend_hex_adult_bk'	=> $input['weekend_hex_adult_bf'], 
  						'weekend_hex_child_bk'	=> $input['weekend_hex_child_bf'],*/
  						'weekend_adult_room_bed_price' => round(($input['weekend_bedroom_price'] * $conversion_rate), 2),
  						'weekend_room_bed_adult_bk'	=> $input['weekend_bedroom_adult_bf'], 
  						'weekend_room_bed_child_bk'	=> $input['weekend_bedroom_child_bf'],
                        'currency'                  => COURSE_LIST_DEFAULT_CURRENCY_VALUE
				);
		    $query = $this->db->insert('hotel_room_rate',$insert_data);
			$hotel_room_rate = $this->db->insert_id();
			

	}

	function add_room_rate($input){			
		
		if(!isset($input['child_price_a']))
			$input['child_price_a'] = 0;
		if(!isset($input['child_price_b']))
			$input['child_price_b'] = 0;
		if(!isset($input['child_price_c']))
			$input['child_price_c'] = 0;
		if(!isset($input['child_price_d']))
			$input['child_price_d'] = 0;
		if(!isset($input['child_price_e']))
			$input['child_price_e'] = 0;
		if(!isset($input['extra_bed_price_total']))
			$input['extra_bed_price_total'] = 0;
		if(!isset($input['extra_bed_price']))
			$input['extra_bed_price'] = 0;
		if(!isset($input['gst']))
			$input['gst'] = "Exclusive";
				
		if(!isset($input['green_tax']))
			$input['green_tax'] = "Exclusive";	

		if(!isset($input['service_charge']))
			$input['service_charge'] = "Exclusive";	
			
		if(!isset($input['gst_markup']))
			$input['gst_markup'] = "Inclusive";	
			
		if(!isset($input['gst_green_tax']))
			$input['gst_green_tax'] = "No";	
						
		if(!isset($input['sc_applicable']))
			$input['sc_applicable'] = "No";
			if(!isset($input['status']))
			$input['status'] = "INACTIVE";
			
		$date_range[0] = $date_range[1] = '';
		if(isset($input['date_rane_rate']) && $input['date_rane_rate']!='')
			$date_range = explode(" - ",$input['date_rane_rate']);	  
			$date_range[0] = date('Y-m-d',strtotime(trim($date_range[0])));
			$date_range[1] = date('Y-m-d',strtotime(trim($date_range[1])));

		$insert_data1 = array(
							'from_date' 				=> $date_range[0],
							'to_date' 					=> $date_range[1],
							'gst' 						=> $input['gst'],
							'gst_green_tax' 			=> $input['gst_green_tax'],
							'green_tax' 				=> $input['green_tax'],
							'sc_applicable' 			=> $input['sc_applicable'],
							'service_charge' 			=> $input['service_charge'],
							'sc_percentage' 			=> 0,//$input['sc_percentage'],			
							'gst_markup' 				=> $input['gst_markup'],			
							'status' 					=> $input['status'],
							'creation_date'				=> (date('Y-m-d H:i:s'))	
						);
		// echo '<pre/>';print_r($insert_data1);exit;
		$insert_id = $this->add_tax_rate($insert_data1);
				
		$insert_data = array(
							'seasons_details_id' 				=> $input['seasons_details_id'],
							'hotel_details_id' 				=> $input['hotel_details_id'],
							'hotel_room_type_id' 			=> $input['room_details_id'],
							'tax_rate_info_id'              => $insert_id,
							'from_date' 					=> $date_range[0],
							'to_date' 						=> $date_range[1],
							'adult_price' 					=> $input['adult_price'],
							'child_price_a' 				=> $input['child_price_a'],
							'child_price_b' 				=> $input['child_price_b'],
							'child_price_c' 				=> $input['child_price_c'],
							'child_price_d' 				=> $input['child_price_d'],
							'child_price_e' 				=> $input['child_price_e'],			
							'sgl_price' 					=> $input['sgl_price'],	
							'extra_bed_price' 				=> $input['extra_bed_price'],			
							'extra_bed_price_total' 		=> $input['extra_bed_price_total'],			
							'dbl_price' 					=> $input['dbl_price'],			
							'tpl_price' 					=> $input['tpl_price'],			
							'quad_price' 					=> $input['quad_price'],
							'status' 					=> $input['status'],			
							'hex_price' 					=> $input['hex_price'],		
							'room_promotion'						=> $input['promotion']
						);
		if($input['supplier_rights'] == 1 ){
		 $insert_data['room_rate_added_by_supplier'] =$this->session->userdata('lgm_supplier_admin_id') ;
		} else {
		 $insert_data['room_rate_added_by_mgmt'] = $this->session->userdata('provab_admin_id') ;
		}		
		// echo '<pre/>';print_r($insert_data);exit;
		try {		
			$query = $this->db->insert('hotel_room_rate_info',$insert_data);
			$hotel_room_rate_info_id = $this->db->insert_id();
			$this->General_Model->insert_log('10','add_room_rate',json_encode($insert_data),'Adding  Hotel room rate info Details to database','hotel_room_rate_info','hotel_room_rate_info_id',$hotel_room_rate_info_id);
		} catch(Exception $e) {
			 return $e;
		}
	}
	function add_tax_rate($insert_data){
		try {	
		$this->db->insert('tax_rate_info',$insert_data);
		$tax_rate_info_id = $this->db->insert_id();
		$this->General_Model->insert_log('12','add_tax_rate',json_encode($insert_data),'Adding  Tax to database','tax_rate_info','tax_rate_info_id',$tax_rate_info_id);
	} catch(Exception $e) {
		return $e;
	}
		return $tax_rate_info_id;
	}
	
/*	function inactive_roomrate($hotel_room_rate_info_id){		
		$data = array(
					'roomrate_status' => 'INACTIVE'
					);
		$this->db->where('hotel_room_rate_info_id', $hotel_room_rate_info_id);
		$this->db->update('hotel_room_rate_info', $data);
		
	}*/
	function inactive_roomrate($hotel_room_rate_info_id,$hotel_room_rate_id){		
		$data = array(
					'roomrate_status' => 'INACTIVE'
					);
		$this->db->where('hotel_room_rate_info_id', $hotel_room_rate_info_id);
		$this->db->update('hotel_room_rate_info', $data);
		
		//status inactive for hotel_room_rate table
			$data = array(
					'hotel_room_rate_status' => 'INACTIVE'
					);
		$this->db->where('hotel_room_rate_id', $hotel_room_rate_id);
		$this->db->update('hotel_room_rate', $data);
		
	}
	
/*	function active_roomrate($hotel_room_rate_info_id){
		$data = array(
					'roomrate_status' => 'ACTIVE'
					);
		$this->db->where('hotel_room_rate_info_id', $hotel_room_rate_info_id);
		$this->db->update('hotel_room_rate_info', $data);
		
	}*/
		function active_roomrate($hotel_room_rate_info_id,$hotel_room_rate_id){
		$data = array(
					'roomrate_status' => 'ACTIVE'
					);
		$this->db->where('hotel_room_rate_info_id', $hotel_room_rate_info_id);
		$this->db->update('hotel_room_rate_info', $data);
		
			//status inactive for hotel_room_rate table
			$data = array(
					'hotel_room_rate_status' => 'ACTIVE'
					);
		$this->db->where('hotel_room_rate_id', $hotel_room_rate_id);
		$this->db->update('hotel_room_rate', $data);
		
	}
/*	function delete_roomrate($hotel_room_rate_info_id){
		$this->db->where('hotel_room_rate_info_id', $hotel_room_rate_info_id);
		$this->db->delete('hotel_room_rate_info');
		
	}*/
		function delete_roomrate($hotel_room_rate_info_id,$hotel_room_rate_id){
	  //  debug($hotel_room_rate_info_id);debug($hotel_room_rate_id);exit;
	    
	    /*	$this->db->where('hotel_room_rate_info_id', $hotel_room_rate_info_id);
		    $this->db->delete('hotel_room_rate_info');*/
	    
	    $this->db->select('*');
	    $this->db->from('hotel_room_rate');
	    $this->db->where('hotel_rome_rate_info_id',$hotel_room_rate_info_id);
	    $hotel_room_count = $this->db->get();
	    
	    if ($hotel_room_count->num_rows() ==1) 
	    {
	      //  debug("if");exit;
	        //deleting hotel_room_rate_info 
           	$this->db->where('hotel_room_rate_info_id', $hotel_room_rate_info_id);
		    $this->db->delete('hotel_room_rate_info');
        }    
        else{
            // debug("else");exit;
           //deleting hotel_room_rate
           $this->db->where('hotel_room_rate_id', $hotel_room_rate_id);
		    $this->db->delete('hotel_room_rate');
        }
	    
	   
	}


	function new_update_room_rate($input,$rate_id){
	  //  error_reporting(E_ALL);
	  //debug($input['country_list_nationality_id']);exit;
		//echo "<pre>";print_r($input);exit();
	//	debug($input);exit;
		if(!isset($input['gst']))
			$input['gst'] = "Exclusive";

		if(!isset($input['service_charge']))
			$input['service_charge'] = "Exclusive";	

		if(!isset($input['promotion']))
			$input['promotion'] = "";

		if(!isset($input['child_price_a']))
			$input['child_price_a'] = 0;
		if(!isset($input['child_price_b']))
			$input['child_price_b'] = 0;
		if(!isset($input['child_price_c']))
			$input['child_price_c'] = 0;
		if(!isset($input['child_price_d']))
			$input['child_price_d'] = 0;
		if(!isset($input['child_price_e']))
			$input['child_price_e'] = 0;
		if(!isset($input['extra_bed_price_total']))
			$input['extra_bed_price_total'] = 0;
		if(!isset($input['extra_bed_price']))
			$input['extra_bed_price'] = 0;

		if(!isset($input['child_extra_bed_price']))
			$input['child_extra_bed_price'] = 0;
		if(!isset($input['adult_extra_bed_price']))
			$input['adult_extra_bed_price'] = 0;

		$returnval = $this->get_table_data('extra_bed', 'hotel_room_type', 'hotel_room_type_id', $input['room_details_id']);
		 if ($returnval != "") {
         			foreach ($returnval as $rowval) {          			         			
         				if($rowval->extra_bed == "Available"){		
         				}
         				else{
         					$input['child_extra_bed_price'] = 0;
         					$input['adult_extra_bed_price'] = 0;		
         				}
         			}
         		}	


		if(!isset($input['week_dbl_price']))
			$input['week_dbl_price'] = 0;
		if(!isset($input['week_trp_price']))
			$input['week_trp_price'] = 0;
		/*if(!isset($input['week_quad_price']))
			$input['week_quad_price'] = 0;
		if(!isset($input['week_hex_price']))
			$input['week_hex_price'] = 0;*/

		if(!isset($input['week_bedroom_price']))
			$input['week_bedroom_price'] = 0;
		if(!isset($input['week_bedroom_adult_bf']))
			$input['week_bedroom_adult_bf'] = 0;
		if(!isset($input['week_bedroom_child_bf']))
			$input['week_bedroom_child_bf'] = 0;		

		if(!isset($input['weekend_sgl_price']))
			$input['weekend_sgl_price'] = 0;
		if(!isset($input['weekend_single_adult_bf']))
			$input['weekend_single_adult_bf'] = 0;
		if(!isset($input['weekend_single_child_bf']))
			$input['weekend_single_child_bf'] = 0;		
		if(!isset($input['weekend_dbl_price']))
			$input['weekend_dbl_price'] = 0;
		if(!isset($input['weekend_double_adult_bf']))
			$input['weekend_double_adult_bf'] = 0;		
		if(!isset($input['weekend_double_child_bf']))
			$input['weekend_double_child_bf'] = 0;

		if(!isset($input['weekend_tpl_price']))
			$input['weekend_tpl_price'] = 0;
		if(!isset($input['weekend_triple_adult_bf']))
			$input['weekend_triple_adult_bf'] = 0;
		if(!isset($input['weekend_triple_child_bf']))
			$input['weekend_triple_child_bf'] = 0;	

		/*if(!isset($input['weekend_quad_price']))
			$input['weekend_quad_price'] = 0;
		if(!isset($input['weekend_quad_adult_bf']))
			$input['weekend_quad_adult_bf'] = 0;
		if(!isset($input['weekend_quad_child_bf']))
			$input['weekend_quad_child_bf'] = 0;	

		if(!isset($input['weekend_hex_price']))
			$input['weekend_hex_price'] = 0;
		if(!isset($input['weekend_hex_adult_bf']))
			$input['weekend_hex_adult_bf'] = 0;
		if(!isset($input['weekend_hex_child_bf']))
			$input['weekend_hex_child_bf'] = 0;	*/

		if(!isset($input['weekend_bedroom_price']))
			$input['weekend_bedroom_price'] = 0;
		if(!isset($input['weekend_bedroom_adult_bf']))
			$input['weekend_bedroom_adult_bf'] = 0;
		if(!isset($input['weekend_bedroom_child_bf']))
			$input['weekend_bedroom_child_bf'] = 0;		

		$excluded_country="";
		$included_country="";						
		foreach($input['include_country'] as $ammenities){
			$included_country .= $ammenities.',';
		}
		foreach($input['exclude_country'] as $ammenities){
			$excluded_country .= $ammenities.',';
		}

		$date_range[0] = $date_range[1] = '';
		if(isset($input['date_rane_rate']) && $input['date_rane_rate']!='')
			$date_range = explode(" - ",$input['date_rane_rate']);	  

		   //My Code
			$fromorderdate = explode('/', $date_range[0]);
			
				$monthfrom = $fromorderdate[1];
				$dayfrom   = $fromorderdate[0];
				$yearfrom  = $fromorderdate[2];
		 	$fromdate = $monthfrom.'/'.$dayfrom.'/'.$yearfrom;

		 	$toorderdate = explode('/', trim($date_range[1]));
			
				$monthto = $toorderdate[1];
				$dayto   = $toorderdate[0];
				$yearto  = $toorderdate[2];
		 	$todate = $monthto.'/'.$dayto.'/'.$yearto;

			//End My code
			$date_range[0] = date_format(date_create(trim($fromdate)), 'Y-m-d');
			$date_range[1] = date_format(date_create(trim($todate)), 'Y-m-d'); 
			//$date_range[0] = date('Y-m-d',strtotime(trim($date_range[0])));
			//$date_range[1] = date('Y-m-d',strtotime(trim($date_range[1])));
			//print_r($date_range[1]); exit();
			$insert_data = array(
							'seasons_details_id' 			=> $input['seasons_details_id'],
							'hotel_details_id' 				=> $input['hotel_details_id'],
							'hotel_room_type_id' 			=> $input['room_details_id'],							
							'from_date' 					=> $date_range[0],
							'to_date' 						=> $date_range[1],							
							//'status' 		  			    => 'ACTIVE',										
							//'room_promotion'				=> $input['promotion'],
							//'rate_type'						=> $input['room_rate_type'],
							'room_rate_cancellation_policy'			=> $input['cancellation_policy'],
							'weekend_price'					=> $input['week_end_select'],
							//'included_country'				=> $included_country,
							//'excluded_country'				=> $excluded_country,
							'gst_tax'						=> $input['gst'],
							'service_tax'					=> $input['service_charge'],

						);
					//	debug($insert_data);exit;

			if($input['supplier_rights'] == 1 ){
		 		$insert_data['room_rate_added_by_supplier'] =$this->session->userdata('lgm_supplier_admin_id') ;
			} else {
		 		$insert_data['room_rate_added_by_mgmt'] = $this->entity_user_id;
			}	
			$this->db->where('hotel_room_rate_info_id', $rate_id);
			$this->db->update('hotel_room_rate_info',$insert_data);

			$insert_data = array(
						'hotel_rome_rate_info_id' => $rate_id,
						'room_child_price_a' => $input['child_price_a'],
						'room_child_price_b' => $input['child_price_b'],
						'room_child_price_c' => $input['child_price_c'],
						'room_child_price_d' => $input['child_price_d'],
						'room_child_price_e' => $input['child_price_e'],
						'child_extra_bed_price' => $input['child_extra_bed_price'],
						'adult_extra_bed_price'	=> $input['adult_extra_bed_price'],
						'single_room_price' => $input['week_sgl_price'],
						'single_adult_bk' => $input['week_single_adult_bf'],
						'single_child_bk' => $input['week_single_child_bf'],
						'double_room_price' => $input['week_dbl_price'],
						'double_adult_bk' => $input['week_double_adult_bf'],
						'double_child_bk' => $input['week_double_child_bf'],
						'triple_room_price' => $input['week_trp_price'],
						'triple_adult_bk' => $input['week_trp_adult_bf'],
						'triple_child_bk' => $input['week_trp_child_bf'],
						/*'quad_room_price' => $input['week_quad_price'],
						'quad_adult_bk' => $input['week_quad_adult_bf'],
						'quad_child_bk' => $input['week_quad_child_bf'],
						'hex_room_price' => $input['week_hex_price'],
						'hex_adult_bk' => $input['week_hex_adult_bf'],
						'hex_child_bk' => $input['week_hex_child_bf'],*/
						'adult_room_bed_price' => $input['week_bedroom_price'],
						'room_bed_adult_bk' => $input['week_bedroom_adult_bf'],
						'room_bed_child_bk' => $input['week_bedroom_child_bf'],
						'weekend_single_room_price' => $input['weekend_sgl_price'],
  						'weekend_single_adult_bk'	=> $input['weekend_single_adult_bf'],	
  						'weekend_single_child_bk'	=> $input['weekend_single_child_bf'],
  						'weekend_double_room_price' => $input['weekend_dbl_price'],
  						'weekend_double_adult_bk'	=> $input['weekend_double_adult_bf'], 
  						'weekend_double_child_bk'	=> $input['weekend_double_child_bf'],
  						'weekend_triple_room_price' => $input['weekend_tpl_price'],
  						'weekend_triple_adult_bk'	=> $input['weekend_triple_adult_bf'], 
  						'weekend_triple_child_bk'	=> $input['weekend_triple_child_bf'],
						/*'weekend_quad_room_price' => $input['weekend_quad_price'],
  						'weekend_quad_adult_bk'	=> $input['weekend_quad_adult_bf'], 
  						'weekend_quad_child_bk'	=> $input['weekend_quad_child_bf'],
						'weekend_hex_room_price' => $input['weekend_hex_price'],
  						'weekend_hex_adult_bk'	=> $input['weekend_hex_adult_bf'], 
  						'weekend_hex_child_bk'	=> $input['weekend_hex_child_bf'],*/
  						'weekend_adult_room_bed_price' => $input['weekend_bedroom_price'],
  						'weekend_room_bed_adult_bk'	=> $input['weekend_bedroom_adult_bf'], 
  						'weekend_room_bed_child_bk'	=> $input['weekend_bedroom_child_bf'],
				);
			
			
	  	/*	$returnval = $this->get_table_data('hotel_rome_rate_info_id', 'hotel_room_rate', 'hotel_rome_rate_info_id', $rate_id);*/
	  	
	  			$returnval = $this->get_table_data('hotel_rome_rate_info_id', 'hotel_room_rate', 'hotel_rome_rate_info_id', $rate_id,'$country_list_nationality_id',$country_list_nationality_id);
	  			
	  		//	debug($returnval);exit;
	  	//	debug($country_list_nationality_id);exit;
  			if ($returnval != "") {
  				$this->db->where('hotel_rome_rate_info_id', $rate_id);
  				$this->db->where('country_list_nationality_id', $input['country_list_nationality_id']);
  					$this->db->where('hotel_room_rate_id', $input['hotel_room_rate_id']);
  				
				$query = $this->db->update('hotel_room_rate',$insert_data);
			//	debug($this->db->last_query());exit;
  			}
  			else{
  			    $query = $this->db->insert('hotel_room_rate',$insert_data);
				$hotel_room_rate = $this->db->insert_id();
			}	

			
        }

        function get_table_data($field,$table,$filterfield,$filterdata){ //kames
        $this->db->select($field);
        $this->db->where($filterfield, $filterdata);
        $query=$this->db->get($table);
         
        if ($query->num_rows() >0) {
            return $query->result();
        }    
        else{
            return "";
        }
    }
	

	function new_update_room_rate_old($input,$rate_id){
		//echo "<pre>";print_r($input);exit();
		if(!isset($input['gst']))
			$input['gst'] = "Exclusive";

		if(!isset($input['service_charge']))
			$input['service_charge'] = "Exclusive";	

		if(!isset($input['promotion']))
			$input['promotion'] = "";

		if(!isset($input['child_price_a']))
			$input['child_price_a'] = 0;
		if(!isset($input['child_price_b']))
			$input['child_price_b'] = 0;
		if(!isset($input['child_price_c']))
			$input['child_price_c'] = 0;
		if(!isset($input['child_price_d']))
			$input['child_price_d'] = 0;
		if(!isset($input['child_price_e']))
			$input['child_price_e'] = 0;
		if(!isset($input['extra_bed_price_total']))
			$input['extra_bed_price_total'] = 0;
		if(!isset($input['extra_bed_price']))
			$input['extra_bed_price'] = 0;

		if(!isset($input['child_extra_bed_price']))
			$input['child_extra_bed_price'] = 0;
		if(!isset($input['adult_extra_bed_price']))
			$input['adult_extra_bed_price'] = 0;

		$returnval = $this->get_table_data('extra_bed', 'hotel_room_type', 'hotel_room_type_id', $input['room_details_id']);
		 if ($returnval != "") {
         			foreach ($returnval as $rowval) {          			         			
         				if($rowval->extra_bed == "Available"){		
         				}
         				else{
         					$input['child_extra_bed_price'] = 0;
         					$input['adult_extra_bed_price'] = 0;		
         				}
         			}
         		}	


		if(!isset($input['week_dbl_price']))
			$input['week_dbl_price'] = 0;
		if(!isset($input['week_trp_price']))
			$input['week_trp_price'] = 0;
		/*if(!isset($input['week_quad_price']))
			$input['week_quad_price'] = 0;
		if(!isset($input['week_hex_price']))
			$input['week_hex_price'] = 0;*/

		if(!isset($input['week_bedroom_price']))
			$input['week_bedroom_price'] = 0;
		if(!isset($input['week_bedroom_adult_bf']))
			$input['week_bedroom_adult_bf'] = 0;
		if(!isset($input['week_bedroom_child_bf']))
			$input['week_bedroom_child_bf'] = 0;		

		if(!isset($input['weekend_sgl_price']))
			$input['weekend_sgl_price'] = 0;
		if(!isset($input['weekend_single_adult_bf']))
			$input['weekend_single_adult_bf'] = 0;
		if(!isset($input['weekend_single_child_bf']))
			$input['weekend_single_child_bf'] = 0;		
		if(!isset($input['weekend_dbl_price']))
			$input['weekend_dbl_price'] = 0;
		if(!isset($input['weekend_double_adult_bf']))
			$input['weekend_double_adult_bf'] = 0;		
		if(!isset($input['weekend_double_child_bf']))
			$input['weekend_double_child_bf'] = 0;

		if(!isset($input['weekend_tpl_price']))
			$input['weekend_tpl_price'] = 0;
		if(!isset($input['weekend_triple_adult_bf']))
			$input['weekend_triple_adult_bf'] = 0;
		if(!isset($input['weekend_triple_child_bf']))
			$input['weekend_triple_child_bf'] = 0;	

		/*if(!isset($input['weekend_quad_price']))
			$input['weekend_quad_price'] = 0;
		if(!isset($input['weekend_quad_adult_bf']))
			$input['weekend_quad_adult_bf'] = 0;
		if(!isset($input['weekend_quad_child_bf']))
			$input['weekend_quad_child_bf'] = 0;	

		if(!isset($input['weekend_hex_price']))
			$input['weekend_hex_price'] = 0;
		if(!isset($input['weekend_hex_adult_bf']))
			$input['weekend_hex_adult_bf'] = 0;
		if(!isset($input['weekend_hex_child_bf']))
			$input['weekend_hex_child_bf'] = 0;	*/

		if(!isset($input['weekend_bedroom_price']))
			$input['weekend_bedroom_price'] = 0;
		if(!isset($input['weekend_bedroom_adult_bf']))
			$input['weekend_bedroom_adult_bf'] = 0;
		if(!isset($input['weekend_bedroom_child_bf']))
			$input['weekend_bedroom_child_bf'] = 0;		

		$excluded_country="";
		$included_country="";						
		foreach($input['include_country'] as $ammenities){
			$included_country .= $ammenities.',';
		}
		foreach($input['exclude_country'] as $ammenities){
			$excluded_country .= $ammenities.',';
		}

		$date_range[0] = $date_range[1] = '';
		if(isset($input['date_rane_rate']) && $input['date_rane_rate']!='')
			$date_range = explode(" - ",$input['date_rane_rate']);	  

		   //My Code
			$fromorderdate = explode('/', $date_range[0]);
			
				$monthfrom = $fromorderdate[1];
				$dayfrom   = $fromorderdate[0];
				$yearfrom  = $fromorderdate[2];
		 	$fromdate = $monthfrom.'/'.$dayfrom.'/'.$yearfrom;

		 	$toorderdate = explode('/', trim($date_range[1]));
			
				$monthto = $toorderdate[1];
				$dayto   = $toorderdate[0];
				$yearto  = $toorderdate[2];
		 	$todate = $monthto.'/'.$dayto.'/'.$yearto;

			//End My code
			$date_range[0] = date_format(date_create(trim($fromdate)), 'Y-m-d');
			$date_range[1] = date_format(date_create(trim($todate)), 'Y-m-d'); 
			//$date_range[0] = date('Y-m-d',strtotime(trim($date_range[0])));
			//$date_range[1] = date('Y-m-d',strtotime(trim($date_range[1])));
			//print_r($date_range[1]); exit();
			$insert_data = array(
							'seasons_details_id' 			=> $input['seasons_details_id'],
							'hotel_details_id' 				=> $input['hotel_details_id'],
							'hotel_room_type_id' 			=> $input['room_details_id'],							
							'from_date' 					=> $date_range[0],
							'to_date' 						=> $date_range[1],							
							//'status' 		  			    => 'ACTIVE',										
							//'room_promotion'				=> $input['promotion'],
							//'rate_type'						=> $input['room_rate_type'],
							'room_rate_cancellation_policy'			=> $input['cancellation_policy'],
							'weekend_price'					=> $input['week_end_select'],
							//'included_country'				=> $included_country,
							//'excluded_country'				=> $excluded_country,
							'gst_tax'						=> $input['gst'],
							'service_tax'					=> $input['service_charge'],

						);

			if($input['supplier_rights'] == 1 ){
		 		$insert_data['room_rate_added_by_supplier'] =$this->session->userdata('lgm_supplier_admin_id') ;
			} else {
		 		$insert_data['room_rate_added_by_mgmt'] = $this->entity_user_id;
			}	
			$this->db->where('hotel_room_rate_info_id', $rate_id);
			$this->db->update('hotel_room_rate_info',$insert_data);

			$insert_data = array(
						'hotel_rome_rate_info_id' => $rate_id,
						'room_child_price_a' => $input['child_price_a'],
						'room_child_price_b' => $input['child_price_b'],
						'room_child_price_c' => $input['child_price_c'],
						'room_child_price_d' => $input['child_price_d'],
						'room_child_price_e' => $input['child_price_e'],
						'child_extra_bed_price' => $input['child_extra_bed_price'],
						'adult_extra_bed_price'	=> $input['adult_extra_bed_price'],
						'single_room_price' => $input['week_sgl_price'],
						'single_adult_bk' => $input['week_single_adult_bf'],
						'single_child_bk' => $input['week_single_child_bf'],
						'double_room_price' => $input['week_dbl_price'],
						'double_adult_bk' => $input['week_double_adult_bf'],
						'double_child_bk' => $input['week_double_child_bf'],
						'triple_room_price' => $input['week_trp_price'],
						'triple_adult_bk' => $input['week_trp_adult_bf'],
						'triple_child_bk' => $input['week_trp_child_bf'],
						/*'quad_room_price' => $input['week_quad_price'],
						'quad_adult_bk' => $input['week_quad_adult_bf'],
						'quad_child_bk' => $input['week_quad_child_bf'],
						'hex_room_price' => $input['week_hex_price'],
						'hex_adult_bk' => $input['week_hex_adult_bf'],
						'hex_child_bk' => $input['week_hex_child_bf'],*/
						'adult_room_bed_price' => $input['week_bedroom_price'],
						'room_bed_adult_bk' => $input['week_bedroom_adult_bf'],
						'room_bed_child_bk' => $input['week_bedroom_child_bf'],
						'weekend_single_room_price' => $input['weekend_sgl_price'],
  						'weekend_single_adult_bk'	=> $input['weekend_single_adult_bf'],	
  						'weekend_single_child_bk'	=> $input['weekend_single_child_bf'],
  						'weekend_double_room_price' => $input['weekend_dbl_price'],
  						'weekend_double_adult_bk'	=> $input['weekend_double_adult_bf'], 
  						'weekend_double_child_bk'	=> $input['weekend_double_child_bf'],
  						'weekend_triple_room_price' => $input['weekend_tpl_price'],
  						'weekend_triple_adult_bk'	=> $input['weekend_triple_adult_bf'], 
  						'weekend_triple_child_bk'	=> $input['weekend_triple_child_bf'],
						/*'weekend_quad_room_price' => $input['weekend_quad_price'],
  						'weekend_quad_adult_bk'	=> $input['weekend_quad_adult_bf'], 
  						'weekend_quad_child_bk'	=> $input['weekend_quad_child_bf'],
						'weekend_hex_room_price' => $input['weekend_hex_price'],
  						'weekend_hex_adult_bk'	=> $input['weekend_hex_adult_bf'], 
  						'weekend_hex_child_bk'	=> $input['weekend_hex_child_bf'],*/
  						'weekend_adult_room_bed_price' => $input['weekend_bedroom_price'],
  						'weekend_room_bed_adult_bk'	=> $input['weekend_bedroom_adult_bf'], 
  						'weekend_room_bed_child_bk'	=> $input['weekend_bedroom_child_bf'],
				);
			
	  		$returnval = $this->get_table_data('hotel_rome_rate_info_id', 'hotel_room_rate', 'hotel_rome_rate_info_id', $rate_id);
  			if ($returnval != "") {
  				$this->db->where('hotel_rome_rate_info_id', $rate_id);
				$query = $this->db->update('hotel_room_rate',$insert_data);
  			}
  			else{
  			    $query = $this->db->insert('hotel_room_rate',$insert_data);
				$hotel_room_rate = $this->db->insert_id();
			}	

			
        }

        function get_table_data_old($field,$table,$filterfield,$filterdata){ //kames
        $this->db->select($field);
        $this->db->where($filterfield, $filterdata);
        $query=$this->db->get($table);
         
        if ($query->num_rows() >0) {
            return $query->result();
        }    
        else{
            return "";
        }
    }
	
	function update_room_rate($input,$rate_id){		
		if(!isset($input['child_price_a']))
			$input['child_price_a'] = 0;
		if(!isset($input['child_price_b']))
			$input['child_price_b'] = 0;
		if(!isset($input['child_price_c']))
			$input['child_price_c'] = 0;
		if(!isset($input['child_price_d']))
			$input['child_price_d'] = 0;
		if(!isset($input['child_price_e']))
			$input['child_price_e'] = 0;

		if(!isset($input['extra_bed_price_total']))
			$input['extra_bed_price_total'] = 0;
		if(!isset($input['extra_bed_price']))
			$input['extra_bed_price'] = 0;

		if(!isset($input['gst']))
			$input['gst'] = "Exclusive";
				
		if(!isset($input['green_tax']))
			$input['green_tax'] = "Inclusive";	

		if(!isset($input['service_charge']))
			$input['service_charge'] = "Exclusive";	
			
		if(!isset($input['gst_markup']))
			$input['gst_markup'] = "Inclusive";	
			
		if(!isset($input['gst_green_tax']))
			$input['gst_green_tax'] = "No";	
						
		if(!isset($input['sc_applicable']))
			$input['sc_applicable'] = "No";	
			
		if(!isset($input['status']))
			$input['status'] = "INACTIVE";
			
		$date_range[0] = $date_range[1] = '';
		if(isset($input['date_rane_rate']) && $input['date_rane_rate']!='')
			$date_range = explode(" - ",$input['date_rane_rate']);
			$date_range[0] = date('Y-m-d',strtotime(trim($date_range[0])));
			$date_range[1] = date('Y-m-d',strtotime(trim($date_range[1])));
    
		$insert_data1 = array(
							'from_date' 				=> $date_range[0],
							'to_date' 					=> $date_range[1],
							'gst' 						=> $input['gst'],
							'gst_green_tax' 			=> $input['gst_green_tax'],
							'green_tax' 				=> $input['green_tax'],
							'sc_applicable' 			=> $input['sc_applicable'],
							'service_charge' 			=> $input['service_charge'],
							'sc_percentage' 			=> 0,//$input['sc_percentage'],			
							'gst_markup' 				=> $input['gst_markup'],			
							'status' 					=> $input['status'],
							'creation_date'				=> (date('Y-m-d H:i:s'))	
						);
	
		$this->update_tax_rate($insert_data1,$input['tax_rate_info_id']);
		 
				
		$insert_data = array(
							'seasons_details_id' 			=> $input['seasons_details_id'],
							'hotel_details_id' 				=> $input['hotel_details_id'],
							'hotel_room_type_id' 			=> $input['room_details_id'],
							'from_date' 					=> $date_range[0],
							'to_date' 						=> $date_range[1],
							'adult_price' 					=> $input['adult_price'],
							'child_price_a' 				=> $input['child_price_a'],
							'child_price_b' 				=> $input['child_price_b'],
							'child_price_c' 				=> $input['child_price_c'],
							'child_price_d' 				=> $input['child_price_d'],
							'child_price_e' 				=> $input['child_price_e'],			
							'sgl_price' 					=> $input['sgl_price'],	
							'extra_bed_price' 				=> $input['extra_bed_price'],			
							'extra_bed_price_total' 					=> $input['extra_bed_price_total'],		
							'dbl_price' 					=> $input['dbl_price'],			
							'tpl_price' 					=> $input['tpl_price'],			
							'quad_price' 					=> $input['quad_price'],
							'status' 					=> $input['status'],			
							'hex_price' 					=> $input['hex_price'],	
							'room_promotion'						=> $input['promotion']		
						);	
		if($input['supplier_rights'] == 1 ){
		 $insert_data['room_rate_added_by_supplier'] =$this->session->userdata('lgm_supplier_admin_id') ;
		} else {
		 $insert_data['room_rate_added_by_mgmt'] = $this->session->userdata('provab_admin_id') ;
		}		
		
	
	
		try{		
			$this->db->where('hotel_room_rate_info_id', $rate_id);
			$this->db->update('hotel_room_rate_info', $insert_data);
			$this->General_Model->insert_log('10','update_room_rate',json_encode($insert_data),'updating Hotel room rate info Details to database','hotel_room_rate_info','hotel_room_rate_info_id',$rate_id);
		} catch(Exception $e) {
			return $e;
		}
	}
	function update_tax_rate($insert_data,$tax_rate_info_id){	
		try{				
			$this->db->where('tax_rate_info_id', $tax_rate_info_id);
			$this->db->update('tax_rate_info', $insert_data);
			$this->General_Model->insert_log('12','update_tax_rate',json_encode($insert_data),'updating  Tax house offer to database','tax_rate_info','tax_rate_info_id',$tax_rate_info_id);
		} catch(Exception $e) {
			return $e;
		}
	}
	
	function delete_blockout_date($hotel_blockout_id){
		$this->db->where('hotel_blockout_id',$hotel_blockout_id);
	    $this->db->delete('hotel_room_rate_block_out');
	    //echo $this->db->last_query();
	}
	function get_season_date_by_seasonid($id = ''){
			$this->db->select('seasons_from_date');
			$this->db->select('seasons_to_date');
			$this->db->from('seasons_details');
			if($id !='')
			$this->db->where('seasons_details_id',$id);
			$query = $this->db->get();
			if($query->num_rows() ==''){
			return '';
			}else{
				return $query->row();
			}
   }

   	function new_hotel_add_room_rate($input){ 
		if(!isset($input['gst']))
			$input['gst'] = "Exclusive";

		if(!isset($input['service_charge']))
			$input['service_charge'] = "Exclusive";	

		
		if(!isset($input['child_price_a']))
			$input['child_price_a'] = 0;
		if(!isset($input['child_price_b']))
			$input['child_price_b'] = 0;
		if(!isset($input['child_price_c']))
			$input['child_price_c'] = 0;
		if(!isset($input['child_price_d']))
			$input['child_price_d'] = 0;
		if(!isset($input['child_price_e']))
			$input['child_price_e'] = 0;
		if(!isset($input['extra_bed_price_total']))
			$input['extra_bed_price_total'] = 0;
		if(!isset($input['extra_bed_price']))
			$input['extra_bed_price'] = 0;

		if(!isset($input['child_extra_bed_price']))
			$input['child_extra_bed_price'] = 0;
		if(!isset($input['adult_extra_bed_price']))
			$input['adult_extra_bed_price'] = 0;

		if(!isset($input['week_dbl_price']))
			$input['week_dbl_price'] = 0;

		if(!isset($input['week_trp_price']))
			$input['week_trp_price'] = 0;

		if(!isset($input['week_quad_price']))
			$input['week_quad_price'] = 0;

		if(!isset($input['week_hex_price']))
			$input['week_hex_price'] = 0;

		if(!isset($input['week_bedroom_price']))
			$input['week_bedroom_price'] = 0;
		if(!isset($input['week_bedroom_adult_bf']))
			$input['week_bedroom_adult_bf'] = 0;
		if(!isset($input['week_bedroom_child_bf']))
			$input['week_bedroom_child_bf'] = 0;

		if(!isset($input['weekend_sgl_price']))
			$input['weekend_sgl_price'] = 0;
		if(!isset($input['weekend_single_adult_bf']))
			$input['weekend_single_adult_bf'] = 0;
		if(!isset($input['weekend_single_child_bf']))
			$input['weekend_single_child_bf'] = 0;		
		
		if(!isset($input['weekend_dbl_price']))
			$input['weekend_dbl_price'] = 0;
		if(!isset($input['weekend_double_adult_bf']))
			$input['weekend_double_adult_bf'] = 0;		
		if(!isset($input['weekend_double_child_bf']))
			$input['weekend_double_child_bf'] = 0;

		if(!isset($input['weekend_tpl_price']))
			$input['weekend_tpl_price'] = 0;
		if(!isset($input['weekend_triple_adult_bf']))
			$input['weekend_triple_adult_bf'] = 0;		
		if(!isset($input['weekend_triple_child_bf']))
			$input['weekend_triple_child_bf'] = 0;

		if(!isset($input['weekend_quad_price']))
			$input['weekend_quad_price'] = 0;
		if(!isset($input['weekend_quad_adult_bf']))
			$input['weekend_quad_adult_bf'] = 0;		
		if(!isset($input['weekend_quad_child_bf']))
			$input['weekend_quad_child_bf'] = 0;

		if(!isset($input['weekend_hex_price']))
			$input['weekend_hex_price'] = 0;
		if(!isset($input['weekend_hex_adult_bf']))
			$input['weekend_hex_adult_bf'] = 0;		
		if(!isset($input['weekend_hex_child_bf']))
			$input['weekend_hex_child_bf'] = 0;

		if(!isset($input['weekend_bedroom_price']))
			$input['weekend_bedroom_price'] = 0;
		if(!isset($input['weekend_bedroom_adult_bf']))
			$input['weekend_bedroom_adult_bf'] = 0;
		if(!isset($input['weekend_bedroom_child_bf']))
			$input['weekend_bedroom_child_bf'] = 0;		

		

		$date_range[0] = $date_range[1] = '';
		if(isset($input['date_rane_rate']) && $input['date_rane_rate']!='')
			$date_range = explode(" - ",$input['date_rane_rate']);	  
			$date_range[0] = date('Y-m-d',strtotime(trim($date_range[0])));
			$date_range[1] = date('Y-m-d',strtotime(trim($date_range[1])));

			$insert_data = array(
							//'seasons_details_id' 			=> $input['seasons_details_id'],
							'hotel_details_id' 				=> $input['hotel_details_id'],
							'hotel_room_type_id' 			=> $input['room_details_id'],							
							'from_date' 					=> $date_range[0],
							'to_date' 						=> $date_range[1],							
							'roomrate_status' 		  			    => 'ACTIVE',										
							//'room_promotion'				=> $input['promotion'],
							//'rate_type'						=> $input['room_rate_type'],
							//'room_rate_cancellation_policy'			=> $input['cancellation_policy'],
							'weekend_price'					=> $input['week_end_select'],
							//'included_country'				=> $included_country,
							//'excluded_country'				=> $excluded_country,
							'gst_tax'						=> $input['gst'],
							'service_tax'					=> $input['service_charge'],

						);
			

			if($input['supplier_rights'] == 1 ){
		 		$insert_data['room_rate_added_by_supplier'] =$this->session->userdata('lgm_supplier_admin_id') ;
			} else {
		 		$insert_data['room_rate_added_by_mgmt'] = $this->session->userdata('provab_admin_id') ;
			}	

			$query = $this->db->insert('hotel_room_rate_info_crs',$insert_data);
			$hotel_room_rate_info_id = $this->db->insert_id();

			$insert_data = array(
						'hotel_rome_rate_info_id' => $hotel_room_rate_info_id,
						'room_child_price_a' => $input['child_price_a'],
						'room_child_price_b' => $input['child_price_b'],
						'room_child_price_c' => $input['child_price_c'],
						'room_child_price_d' => $input['child_price_d'],
						'room_child_price_e' => $input['child_price_e'],
						'child_extra_bed_price' => $input['child_extra_bed_price'],
						'adult_extra_bed_price'	=> $input['adult_extra_bed_price'],
						'single_room_price' => $input['week_sgl_price'],
						'single_adult_bk' => $input['week_single_adult_bf'],
						'single_child_bk' => $input['week_single_child_bf'],
						'double_room_price' => $input['week_dbl_price'],
						'double_adult_bk' => $input['week_double_adult_bf'],
						'double_child_bk' => $input['week_double_child_bf'],
						'triple_room_price' => $input['week_trp_price'],
						'triple_adult_bk' => $input['week_trp_adult_bf'],
						'triple_child_bk' => $input['week_trp_child_bf'],
						'quad_room_price' => $input['week_quad_price'],
						'quad_adult_bk' => $input['week_quad_adult_bf'],
						'quad_child_bk' => $input['week_quad_child_bf'],
						'hex_room_price' => $input['week_hex_price'],
						'hex_adult_bk' => $input['week_hex_adult_bf'],
						'hex_child_bk' => $input['week_hex_child_bf'],
						'adult_room_bed_price' => $input['week_bedroom_price'],
						'room_bed_adult_bk' => $input['week_bedroom_adult_bf'],
						'room_bed_child_bk' => $input['week_bedroom_child_bf'],
						'weekend_single_room_price' => $input['weekend_sgl_price'],
  						'weekend_single_adult_bk'	=> $input['weekend_single_adult_bf'],	
  						'weekend_single_child_bk'	=> $input['weekend_single_child_bf'],
  						'weekend_double_room_price' => $input['weekend_dbl_price'],
  						'weekend_double_adult_bk'	=> $input['weekend_double_adult_bf'], 
  						'weekend_double_child_bk'	=> $input['weekend_double_child_bf'],
  						'weekend_triple_room_price' => $input['weekend_tpl_price'],
  						'weekend_triple_adult_bk'	=> $input['weekend_triple_adult_bf'], 
  						'weekend_triple_child_bk'	=> $input['weekend_triple_child_bf'],
						'weekend_quad_room_price' => $input['weekend_quad_price'],
  						'weekend_quad_adult_bk'	=> $input['weekend_quad_adult_bf'], 
  						'weekend_quad_child_bk'	=> $input['weekend_quad_child_bf'],
						'weekend_hex_room_price' => $input['weekend_hex_price'],
  						'weekend_hex_adult_bk'	=> $input['weekend_hex_adult_bf'], 
  						'weekend_hex_child_bk'	=> $input['weekend_hex_child_bf'],
  						'weekend_adult_room_bed_price' => $input['weekend_bedroom_price'],
  						'weekend_room_bed_adult_bk'	=> $input['weekend_bedroom_adult_bf'], 
  						'weekend_room_bed_child_bk'	=> $input['weekend_bedroom_child_bf'],
				);
		    $query = $this->db->insert('hotel_room_rate_crs',$insert_data);
			$hotel_room_rate = $this->db->insert_id();
			

	}

	function inactive_hotel_roomrate($hotel_room_rate_info_id){		
		$data = array(
					'roomrate_status' => 'INACTIVE'
					);
		$this->db->where('hotel_room_rate_info_id', $hotel_room_rate_info_id);
		$this->db->update('hotel_room_rate_info_crs', $data);
		$this->General_Model->insert_log('10','inactive_hotel_roomrate',json_encode($data),'updating Hotel room rate info Details status to active','hotel_room_rate_info','hotel_room_rate_info_crs',$hotel_room_rate_info_id);   
	}
	
	function active_hotel_roomrate($hotel_room_rate_info_id){
		$data = array(
					'roomrate_status' => 'ACTIVE'
					);
		$this->db->where('hotel_room_rate_info_id', $hotel_room_rate_info_id);
		$this->db->update('hotel_room_rate_info_crs', $data);
		$this->General_Model->insert_log('10','active_hotel_roomrate',json_encode($data),'updating Hotel room rate info Details status to inactive','hotel_room_rate_info','hotel_room_rate_info_crs',$hotel_room_rate_info_id);
	}

	function delete_hotel_roomrate($hotel_room_rate_info_id){
		$this->db->where('hotel_room_rate_info_id', $hotel_room_rate_info_id);
		$this->db->delete('hotel_room_rate_info_crs');
		$this->General_Model->insert_log('10','delete_hotel_roomrate',json_encode(array()),'deleting  Hotel room rate info Details from database','hotel_room_rate_info_crs','hotel_room_rate_info_id',$hotel_room_rate_info_id);
	}

	function new_hotel_update_room_rate($input,$rate_id){
		//echo "<pre>";print_r($input);exit();
		if(!isset($input['gst']))
			$input['gst'] = "Exclusive";

		if(!isset($input['service_charge']))
			$input['service_charge'] = "Exclusive";	

		if(!isset($input['child_price_a']))
			$input['child_price_a'] = 0;
		if(!isset($input['child_price_b']))
			$input['child_price_b'] = 0;
		if(!isset($input['child_price_c']))
			$input['child_price_c'] = 0;
		if(!isset($input['child_price_d']))
			$input['child_price_d'] = 0;
		if(!isset($input['child_price_e']))
			$input['child_price_e'] = 0;
		if(!isset($input['extra_bed_price_total']))
			$input['extra_bed_price_total'] = 0;
		if(!isset($input['extra_bed_price']))
			$input['extra_bed_price'] = 0;

		if(!isset($input['child_extra_bed_price']))
			$input['child_extra_bed_price'] = 0;
		if(!isset($input['adult_extra_bed_price']))
			$input['adult_extra_bed_price'] = 0;

		$returnval = $this->General_Model->get_table_data('extra_bed', 'hotel_room_type', 'hotel_room_type_id', $input['room_details_id']);
		 if ($returnval != "") {
         			foreach ($returnval as $rowval) {          			         			
         				if($rowval->extra_bed == "Available"){		
         				}
         				else{
         					$input['child_extra_bed_price'] = 0;
         					$input['adult_extra_bed_price'] = 0;		
         				}
         			}
         		}	


		if(!isset($input['week_dbl_price']))
			$input['week_dbl_price'] = 0;
		if(!isset($input['week_trp_price']))
			$input['week_trp_price'] = 0;
		if(!isset($input['week_quad_price']))
			$input['week_quad_price'] = 0;
		if(!isset($input['week_hex_price']))
			$input['week_hex_price'] = 0;

		if(!isset($input['week_bedroom_price']))
			$input['week_bedroom_price'] = 0;
		if(!isset($input['week_bedroom_adult_bf']))
			$input['week_bedroom_adult_bf'] = 0;
		if(!isset($input['week_bedroom_child_bf']))
			$input['week_bedroom_child_bf'] = 0;		

		if(!isset($input['weekend_sgl_price']))
			$input['weekend_sgl_price'] = 0;
		if(!isset($input['weekend_single_adult_bf']))
			$input['weekend_single_adult_bf'] = 0;
		if(!isset($input['weekend_single_child_bf']))
			$input['weekend_single_child_bf'] = 0;		
		if(!isset($input['weekend_dbl_price']))
			$input['weekend_dbl_price'] = 0;
		if(!isset($input['weekend_double_adult_bf']))
			$input['weekend_double_adult_bf'] = 0;		
		if(!isset($input['weekend_double_child_bf']))
			$input['weekend_double_child_bf'] = 0;

		if(!isset($input['weekend_tpl_price']))
			$input['weekend_tpl_price'] = 0;
		if(!isset($input['weekend_triple_adult_bf']))
			$input['weekend_triple_adult_bf'] = 0;
		if(!isset($input['weekend_triple_child_bf']))
			$input['weekend_triple_child_bf'] = 0;	

		if(!isset($input['weekend_quad_price']))
			$input['weekend_quad_price'] = 0;
		if(!isset($input['weekend_quad_adult_bf']))
			$input['weekend_quad_adult_bf'] = 0;
		if(!isset($input['weekend_quad_child_bf']))
			$input['weekend_quad_child_bf'] = 0;	

		if(!isset($input['weekend_hex_price']))
			$input['weekend_hex_price'] = 0;
		if(!isset($input['weekend_hex_adult_bf']))
			$input['weekend_hex_adult_bf'] = 0;
		if(!isset($input['weekend_hex_child_bf']))
			$input['weekend_hex_child_bf'] = 0;	

		if(!isset($input['weekend_bedroom_price']))
			$input['weekend_bedroom_price'] = 0;
		if(!isset($input['weekend_bedroom_adult_bf']))
			$input['weekend_bedroom_adult_bf'] = 0;
		if(!isset($input['weekend_bedroom_child_bf']))
			$input['weekend_bedroom_child_bf'] = 0;		


		$date_range[0] = $date_range[1] = '';
		if(isset($input['date_rane_rate']) && $input['date_rane_rate']!='')
			$date_range = explode(" - ",$input['date_rane_rate']);	  
			$date_range[0] = date('Y-m-d',strtotime(trim($date_range[0])));
			$date_range[1] = date('Y-m-d',strtotime(trim($date_range[1])));

			$insert_data = array(
							
							'hotel_details_id' 				=> $input['hotel_details_id'],
							'hotel_room_type_id' 			=> $input['room_details_id'],							
							'from_date' 					=> $date_range[0],
							'to_date' 						=> $date_range[1],							
							//'status' 		  			    => 'ACTIVE',										
							
							
							'room_rate_cancellation_policy'			=> $input['cancellation_policy'],
							'weekend_price'					=> $input['week_end_select'],
							
							'gst_tax'						=> $input['gst'],
							'service_tax'					=> $input['service_charge'],

						);

			if($input['supplier_rights'] == 1 ){
		 		$insert_data['room_rate_added_by_supplier'] =$this->session->userdata('lgm_supplier_admin_id') ;
			} else {
		 		$insert_data['room_rate_added_by_mgmt'] = $this->session->userdata('provab_admin_id') ;
			}	
			$this->db->where('hotel_room_rate_info_id', $rate_id);
			$this->db->update('hotel_room_rate_info_crs',$insert_data);

			$insert_data = array(
						'hotel_rome_rate_info_id' => $rate_id,
						'room_child_price_a' => $input['child_price_a'],
						'room_child_price_b' => $input['child_price_b'],
						'room_child_price_c' => $input['child_price_c'],
						'room_child_price_d' => $input['child_price_d'],
						'room_child_price_e' => $input['child_price_e'],
						'child_extra_bed_price' => $input['child_extra_bed_price'],
						'adult_extra_bed_price'	=> $input['adult_extra_bed_price'],
						'single_room_price' => $input['week_sgl_price'],
						'single_adult_bk' => $input['week_single_adult_bf'],
						'single_child_bk' => $input['week_single_child_bf'],
						'double_room_price' => $input['week_dbl_price'],
						'double_adult_bk' => $input['week_double_adult_bf'],
						'double_child_bk' => $input['week_double_child_bf'],
						'triple_room_price' => $input['week_trp_price'],
						'triple_adult_bk' => $input['week_trp_adult_bf'],
						'triple_child_bk' => $input['week_trp_child_bf'],
						'quad_room_price' => $input['week_quad_price'],
						'quad_adult_bk' => $input['week_quad_adult_bf'],
						'quad_child_bk' => $input['week_quad_child_bf'],
						'hex_room_price' => $input['week_hex_price'],
						'hex_adult_bk' => $input['week_hex_adult_bf'],
						'hex_child_bk' => $input['week_hex_child_bf'],
						'adult_room_bed_price' => $input['week_bedroom_price'],
						'room_bed_adult_bk' => $input['week_bedroom_adult_bf'],
						'room_bed_child_bk' => $input['week_bedroom_child_bf'],
						'weekend_single_room_price' => $input['weekend_sgl_price'],
  						'weekend_single_adult_bk'	=> $input['weekend_single_adult_bf'],	
  						'weekend_single_child_bk'	=> $input['weekend_single_child_bf'],
  						'weekend_double_room_price' => $input['weekend_dbl_price'],
  						'weekend_double_adult_bk'	=> $input['weekend_double_adult_bf'], 
  						'weekend_double_child_bk'	=> $input['weekend_double_child_bf'],
  						'weekend_triple_room_price' => $input['weekend_tpl_price'],
  						'weekend_triple_adult_bk'	=> $input['weekend_triple_adult_bf'], 
  						'weekend_triple_child_bk'	=> $input['weekend_triple_child_bf'],
						'weekend_quad_room_price' => $input['weekend_quad_price'],
  						'weekend_quad_adult_bk'	=> $input['weekend_quad_adult_bf'], 
  						'weekend_quad_child_bk'	=> $input['weekend_quad_child_bf'],
						'weekend_hex_room_price' => $input['weekend_hex_price'],
  						'weekend_hex_adult_bk'	=> $input['weekend_hex_adult_bf'], 
  						'weekend_hex_child_bk'	=> $input['weekend_hex_child_bf'],
  						'weekend_adult_room_bed_price' => $input['weekend_bedroom_price'],
  						'weekend_room_bed_adult_bk'	=> $input['weekend_bedroom_adult_bf'], 
  						'weekend_room_bed_child_bk'	=> $input['weekend_bedroom_child_bf'],
				);

//echo '<pre>'; print_r($insert_data); exit();
			
	  		$returnval = $this->General_Model->get_table_data('hotel_rome_rate_info_id', 'hotel_room_rate', 'hotel_rome_rate_info_id', $rate_id);
  			if ($returnval != "") {
  				$this->db->where('hotel_rome_rate_info_id', $rate_id);
				$query = $this->db->update('hotel_room_rate_crs',$insert_data);
  			}
  			else{
  			    $query = $this->db->insert('hotel_room_rate_crs',$insert_data);
				$hotel_room_rate = $this->db->insert_id();
			}	

			
        }
	
}
