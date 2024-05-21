<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tours extends CI_Controller {
	public function __construct(){
        parent::__construct();       
        $current_url = $_SERVER['QUERY_STRING'] ? '?'.$_SERVER['QUERY_STRING'] : '';
        $current_url = $this->config->site_url().$this->uri->uri_string(). $current_url;
        $url =  array(
            'continue' => $current_url,
        );
        $this->session->set_userdata($url);
        $this->helpMenuLink = "";
        $this->load->model('Help_Model');
		$this->helpMenuLink = $this->Help_Model->fetchHelpLinks();			
			$this->load->library('rewards');
		$this->load->model('Package_Model');	
        $this->load->model ( 'tours_model' );	
		
    }
    public function tourprice_change_adult_child(){
		// error_reporting(E_ALL);
		$data = $this->input->post ();
		// debug($data['total_price']);

		$currency_obj = new Currency(array('module_type' => 'Holiday','from' => get_api_data_currency(), 'to' => get_application_currency_preference()));


		
		$adultPrice=$data['adultPrice'];
		$childprice=$data['childprice'];
		$infantprice=$data['infantprice'];
		$adultCount=$data['adultCount'];
		$childCount=$data['childCount'];
		$infantCount=$data['infantCount'];
		// debug($data);
		$totalprice=$childprice+$adultPrice;
		// debug($totalprice);exit;
		$tours_crs_markup =$this->domain_management_model->get_markup('holiday');
		$admin_markup_holidaylcrs = $this->domain_management_model->addHolidayCrsMarkup($adultPrice, $tours_crs_markup,$currency_obj);

		 // debug($admin_markup_holidaylcrs);exit;
		  
		 $gst_value = 0;
		 $Markup_adult=0;
		 $Markup_child=0;
		 // $Markup_adult=$admin_markup_holidaylcrs;
		 $Markup_adult=$admin_markup_holidaylcrs*$adultCount;
		 if($childCount >0){
		 	$admin_markup_holidaylcrs_child = $this->domain_management_model->addHolidayCrsMarkup($childprice, $tours_crs_markup,$currency_obj);
		 	$Markup_child=$admin_markup_holidaylcrs_child*$childCount;
		 }

		 $Markup=$Markup_adult+$Markup_child;
		 // debug($Markup);exit;
              //adding gst
            if($Markup > 0 ){
                $gst_details = $this->custom_db->single_table_records('gst_master', '*', array('module' => 'holiday'));
                if($gst_details['status'] == true){
                    if($gst_details['data'][0]['gst'] > 0){
                        
                        $gst_value = ($Markup/100) * $gst_details['data'][0]['gst'];
                    }
                }

             }
             // debug($adultPrice+$childprice+$Markup+$gst_value);exit;
          //    $convenience_fees  = $currency_obj->convenience_fees(($adultPrice*$adultCount)+($childprice*$childCount)+$Markup+$gst_value,1);

              $gst_value_conv=0;
		      if($convenience_fees > 0 )
		      {
		              $gst_details = $GLOBALS['CI']->custom_db->single_table_records('gst_master', '*', array('module' => 'holiday'));
		              // debug($gst_details);exit;
		              if($gst_details['status'] == true){
		                  if($gst_details['data'][0]['gst'] > 0){
		                     
		                     
		                     $gst_value_conv = ($convenience_fees/100) * $gst_details['data'][0]['gst'];
		                  }
		              }
		      }
		      // echo json_encode( array('adultPrice' =>($adultPrice*$adultCount),'childprice'=>($childprice*$childCount),'Markup'=>$Markup,'gst_value'=>$gst_value,'convenience_fees'=>$convenience_fees,'gst_value_conv'=>$gst_value_conv));

		 echo json_encode( array('grandtotal' =>round(($adultPrice*$adultCount)+($childprice*$childCount)+($infantprice*$infantCount)+$Markup+$gst_value+$convenience_fees+$gst_value_conv)));
	}

    public function tourprice_change_adult_childold(){
		// error_reporting(E_ALL);
		$data = $this->input->post ();
		// debug($data['total_price']);

		$currency_obj = new Currency(array('module_type' => 'Holiday','from' => get_api_data_currency(), 'to' => get_application_currency_preference()));


		
		$adultPrice=$data['adultPrice'];
		$childprice=$data['childprice'];
		$adultCount=$data['adultCount'];
		$childCount=$data['childCount'];
		// debug($data);
		$totalprice=$childprice+$adultPrice;
		// debug($totalprice);exit;
		$tours_crs_markup =$this->domain_management_model->get_markup('holiday');
		$admin_markup_holidaylcrs = $this->domain_management_model->addHolidayCrsMarkup($adultPrice, $tours_crs_markup,$currency_obj);

		 // debug($admin_markup_holidaylcrs);exit;
		  
		 $gst_value = 0;
		 $Markup_adult=0;
		 $Markup_child=0;
		 // $Markup_adult=$admin_markup_holidaylcrs;
		 $Markup_adult=$admin_markup_holidaylcrs*$adultCount;
		 if($childCount >0){
		 	$admin_markup_holidaylcrs_child = $this->domain_management_model->addHolidayCrsMarkup($childprice, $tours_crs_markup,$currency_obj);
		 	$Markup_child=$admin_markup_holidaylcrs_child*$childCount;
		 }

		 $Markup=$Markup_adult+$Markup_child;
		 // debug($Markup);exit;
              //adding gst
            if($Markup > 0 ){
                $gst_details = $this->custom_db->single_table_records('gst_master', '*', array('module' => 'holiday'));
                if($gst_details['status'] == true){
                    if($gst_details['data'][0]['gst'] > 0){
                        
                        $gst_value = ($Markup/100) * $gst_details['data'][0]['gst'];
                    }
                }

             }
             // debug($adultPrice+$childprice+$Markup+$gst_value);exit;
              $convenience_fees  = $currency_obj->convenience_fees(($adultPrice*$adultCount)+($childprice*$childCount)+$Markup+$gst_value,1);

              $gst_value_conv=0;
		      if($convenience_fees > 0 )
		      {
		              $gst_details = $GLOBALS['CI']->custom_db->single_table_records('gst_master', '*', array('module' => 'holiday'));
		              // debug($gst_details);exit;
		              if($gst_details['status'] == true){
		                  if($gst_details['data'][0]['gst'] > 0){
		                     
		                     
		                     $gst_value_conv = ($convenience_fees/100) * $gst_details['data'][0]['gst'];
		                  }
		              }
		      }
		      // echo json_encode( array('adultPrice' =>($adultPrice*$adultCount),'childprice'=>($childprice*$childCount),'Markup'=>$Markup,'gst_value'=>$gst_value,'convenience_fees'=>$convenience_fees,'gst_value_conv'=>$gst_value_conv));

		 echo json_encode( array('grandtotal' =>round(($adultPrice*$adultCount)+($childprice*$childCount)+$Markup+$gst_value+$convenience_fees+$gst_value_conv)));
	}

    /**
    * get all tours
    **/
    public function index(){        
        $data['packages'] = $this->Package_Model->getAllPackages();
        $data['countries'] = $this->Package_Model->getPackageCountries();
        $data['package_types'] = $this->Package_Model->getPackageTypes();
        if(!empty($data['packages'])){            
            $this->template->view('holiday/tours', $data);
        }else{
            redirect();
        }
    }
    /**
     * get the package details
     */
    /*public function details($package_id){
       
        $data['package'] = $this->Package_Model->getPackage($package_id);
        $data['package_itinerary'] = $this->Package_Model->getPackageItinerary($package_id);
        $data['package_price_policy'] = $this->Package_Model->getPackagePricePolicy($package_id);
        $data['package_cancel_policy'] = $this->Package_Model->getPackageCancelPolicy($package_id);
        $data['package_traveller_photos'] = $this->Package_Model->getTravellerPhotos($package_id);
        if(!empty($data['package'])){
            $this->template->view('holiday/tours_detail', $data);
        }else{
            redirect("tours/");
        }
    }*/
    	public function details($tour_id,$dep_date="") {



		if($tour_id=='ZH04217746'){
			$tour_id='MTI5';
		}
		if($tour_id=='ZH04216811'){
			$tour_id='Mzk=';
		}
		
		$tour_id = base64_decode($tour_id);

		$page_data ['tour_data'] = $this->tours_model->tour_data($tour_id)[0];
	
		$page_data ['tours_itinerary'] = $this->tours_model->tours_itinerary($tour_id,$dep_date);
	
		$page_data ['tours_itinerary_dw'] = $this->tours_model->tours_itinerary_dw($tour_id,$dep_date);

		$page_data ['tours_itinerary_wd'] = $this->tours_model->tours_itinerary_dw($tour_id);

		$page_data ['tours_date_price'] = $this->tours_model->tours_date_price($tour_id);
		
		$page_data ['tours_continent'] = $this->tours_model->tours_continent();
		$page_data ['tours_country'] = $this->tours_model->tours_country_name();
		$page_data ['tour_type'] = $this->tours_model->tour_type();
		$page_data ['tour_theme'] = $this->tours_model->tour_subtheme();
		$page_data ['tours_continent_name'] = $this->tours_model->tours_continent_name();
		$page_data ['country_code'] = $this->db_cache_api->get_country_code_list();
		$page_data ['tour_price']  = $this->tours_model->tour_price($tour_id);

	//	$page_data['currency_obj'] = new Currency(array('module_type' => 'Holiday','from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
		
		$page_data['currency_obj'] = new Currency(array('module_type' => 'Holiday','from' =>'NPR', 'to' => get_application_currency_preference()));

			$default_currency = get_application_default_currency();

			$page_data['default_currency'] = $default_currency;

		$page_data ['tour_dep_dates_list'] = $this->tours_model->tour_dep_dates_list($tour_id);		
		
		$min_adult = $page_data['tour_price'][0];
		foreach ($page_data['tour_price'] as $key => $value) {

			if($min_adult>$value['adult_price']){
				$min_adult = $value['adult_price'];
				$key_min = $key; 
			}
			
		}
		$page_data['min_price_adult'] = $min_adult;
		$page_data['min_price_child'] = $page_data['tour_price'][$key_min]['child_price']; 
		
		$page_data['date_array'] = $date_array;
		$page_data ['tours_city_name']  = $this->tours_model->tours_city_name();	
		$all_post['required'] = array(
			'created_by' => '',
			'booking_source' => '',
			'module' => 'holiday',
			'module_id' => '',
			'title' => '',
			'address' => '',
			'tour_id' => $tour_id,
			'tours_itinerary_id' => $page_data['tours_itinerary']['id'],
			);
		if(is_logged_in_user()) {
			$user_details = array(
				'user_name' => $this->entity_name,
				'user_email' => $this->entity_email,
				'user_mobile' => $this->entity_phone,
				);
			$review_field = array(
				'exist_user1' => 'checked="checked"',
				'exist_user0' => '',
				'previously_booked1'=>'',
				'previously_booked0'=>'checked="checked"',
				'exist_user_field'=>array(
					'open_tag' => '<fieldset disabled="disabled">',
					'close_tag' => '</fieldset>'
					),
			);
			$created_by = $this->entity_user_id;
		}else{
			$review_field = array(
				'exist_user1' => '',
				'exist_user0' => 'checked="checked"',
				'previously_booked1'=>'',
				'previously_booked0'=>'checked="checked"',
				'exist_user_field'=>array(
					'open_tag' => '',
					'close_tag' => ''
					),
			);
			$created_by = NULL;
		}
		$all_post['review_field'] = $review_field;
		$all_post['user_details'] = $user_details;
		$page_data['post_review_data'] = $all_post; 

		 $tours_crs_markup =$this->domain_management_model->get_markup('holiday');

		 $page_data['tours_crs_markup']=$tours_crs_markup;

        $this->template->view('holiday/details',$page_data);
	}
    	public function detailsoldagensts($tour_id,$dep_date="") {

		if($tour_id=='ZH04217746'){
			$tour_id='MTI5';
		}
		if($tour_id=='ZH04216811'){
			$tour_id='Mzk=';
		}
		
		$tour_id = base64_decode($tour_id);

		$page_data ['tour_data'] = $this->tours_model->tour_data($tour_id)[0];
	
		$page_data ['tours_itinerary'] = $this->tours_model->tours_itinerary($tour_id,$dep_date);
	
		$page_data ['tours_itinerary_dw'] = $this->tours_model->tours_itinerary_dw($tour_id,$dep_date);

		$page_data ['tours_itinerary_wd'] = $this->tours_model->tours_itinerary_dw($tour_id);

		$page_data ['tours_date_price'] = $this->tours_model->tours_date_price($tour_id);
		
		$page_data ['tours_continent'] = $this->tours_model->tours_continent();
		$page_data ['tours_country'] = $this->tours_model->tours_country_name();
		$page_data ['tour_type'] = $this->tours_model->tour_type();
		$page_data ['tour_theme'] = $this->tours_model->tour_subtheme();
		$page_data ['tours_continent_name'] = $this->tours_model->tours_continent_name();
		$page_data ['country_code'] = $this->db_cache_api->get_country_code_list();
		$page_data ['tour_price']  = $this->tours_model->tour_price($tour_id);

		$page_data['currency_obj'] = new Currency(array('module_type' => 'Holiday','from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
		
		$page_data['currency_obj'] = new Currency(array('module_type' => 'Holiday','from' => ADMIN_BASE_CURRENCY_STATIC, 'to' => get_application_currency_preference()));

			$default_currency = get_application_default_currency();

			$page_data['default_currency'] = $default_currency;

		$page_data ['tour_dep_dates_list'] = $this->tours_model->tour_dep_dates_list($tour_id);		
		
		$min_adult = $page_data['tour_price'][0];
		foreach ($page_data['tour_price'] as $key => $value) {

			if($min_adult>$value['adult_price']){
				$min_adult = $value['adult_price'];
				$key_min = $key; 
			}
			
		}
		$page_data['min_price_adult'] = $min_adult;
		$page_data['min_price_child'] = $page_data['tour_price'][$key_min]['child_price']; 
		
		$page_data['date_array'] = $date_array;
		$page_data ['tours_city_name']  = $this->tours_model->tours_city_name();	
		$all_post['required'] = array(
			'created_by' => '',
			'booking_source' => '',
			'module' => 'holiday',
			'module_id' => '',
			'title' => '',
			'address' => '',
			'tour_id' => $tour_id,
			'tours_itinerary_id' => $page_data['tours_itinerary']['id'],
			);
		if(is_logged_in_user()) {
			$user_details = array(
				'user_name' => $this->entity_name,
				'user_email' => $this->entity_email,
				'user_mobile' => $this->entity_phone,
				);
			$review_field = array(
				'exist_user1' => 'checked="checked"',
				'exist_user0' => '',
				'previously_booked1'=>'',
				'previously_booked0'=>'checked="checked"',
				'exist_user_field'=>array(
					'open_tag' => '<fieldset disabled="disabled">',
					'close_tag' => '</fieldset>'
					),
			);
			$created_by = $this->entity_user_id;
		}else{
			$review_field = array(
				'exist_user1' => '',
				'exist_user0' => 'checked="checked"',
				'previously_booked1'=>'',
				'previously_booked0'=>'checked="checked"',
				'exist_user_field'=>array(
					'open_tag' => '',
					'close_tag' => ''
					),
			);
			$created_by = NULL;
		}
		$all_post['review_field'] = $review_field;
		$all_post['user_details'] = $user_details;
		$page_data['post_review_data'] = $all_post; 

		 $tours_crs_markup =$this->domain_management_model->get_markup('holiday');

		 $page_data['tours_crs_markup']=$tours_crs_markup;

        $this->template->view('holiday/details',$page_data);
	}
    public function detailsold($tour_id,$dep_date="") {
        // error_reporting(E_ALL);
        //debug($tour_id);
       // debug($dep_date);
        //exit;
      /*  if (preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $tour_id))
        {
            debug("true");
         
        } else 
        {
              debug("else");
           //return FALSE;
        }*/
        if ( base64_encode(base64_decode($tour_id)) === $tour_id){
           // echo '$data is valid';
            $tour_id = base64_decode($tour_id);
        } else {
            //echo '$data is NOT valid';
                $tour_id = ($tour_id);
        }
       
       // debug($tour_id); exit;
        
        
        
        
        $page_data ['tour_data'] = $this->tours_model->tour_data($tour_id)[0];
    /*  debug(  $page_data ['tour_data'] );
        exit;*/
        $page_data ['tours_itinerary'] = $this->tours_model->tours_itinerary($tour_id,$dep_date);
        
        $page_data ['tours_itinerary_dw'] = $this->tours_model->tours_itinerary_dw($tour_id,$dep_date);
    
        $page_data ['tours_itinerary_wd'] = $this->tours_model->tours_itinerary_dw($tour_id);
        
        $page_data ['tours_date_price'] = $this->tours_model->tours_date_price($tour_id);
        
        $page_data ['tours_continent'] = $this->tours_model->tours_continent();
        $page_data ['tours_country'] = $this->tours_model->tours_country_name();
        $page_data ['tour_type'] = $this->tours_model->tour_type();
        $page_data ['tour_theme'] = $this->tours_model->tour_subtheme();
        $page_data ['tours_continent_name'] = $this->tours_model->tours_continent_name();
        $page_data ['country_code'] = $this->db_cache_api->get_country_code_list();
        $page_data ['tour_price']  = $this->tours_model->tour_price($tour_id);
        

//       debug($page_data ['tour_data']);die;


        //debug($page_data['tour_price']);exit('--tourprice');

        $page_data ['tour_dep_dates_list'] = $this->tours_model->tour_dep_dates_list($tour_id);     
        // $tour_price_change = $this->change_all_price_to_required_currency($page_data['tour_price']);
        // $page_data ['tour_price_changed']    = $tour_price_change;       
        // if(valid_array($page_data ['tour_price_changed'])){
        //  $min_numbers = array_column($page_data ['tour_price_changed'], 'changed_price');
        //  $page_data['min_price'] = min($min_numbers);
        // }
        // foreach ($page_data ['tour_dep_dates_list'] as  $date_value) {
        //  $date_array[] = $date_value['dep_date'];
        // }

        //debug($page_data['tour_price']);exit();
        $min_adult = $page_data['tour_price'][0];
        foreach ($page_data['tour_price'] as $key => $value) {

            if($min_adult>$value['adult_price']){
                $min_adult = $value['adult_price'];
                $key_min = $key; 
            }
            
        }
        
        $page_data['min_price_adult'] = $min_adult;
        $page_data['min_price_child'] = $page_data['tour_price'][$key_min]['child_price']; 
        //debug($page_data['tour_price'][$key_min]['child_price']);exit();
        //debug($min_adult);exit();
        $page_data['date_array'] = $date_array;
        $page_data ['tours_city_name']  = $this->tours_model->tours_city_name();    
        $all_post['required'] = array(
            'created_by' => '',
            'booking_source' => '',
            'module' => 'holiday',
            'module_id' => '',
            'title' => '',
            'address' => '',
            'tour_id' => $tour_id,
            'tours_itinerary_id' => $page_data['tours_itinerary']['id'],
            );
        if(is_logged_in_user()) {
            $user_details = array(
                'user_name' => $this->entity_name,
                'user_email' => $this->entity_email,
                'user_mobile' => $this->entity_phone,
                );
            $review_field = array(
                'exist_user1' => 'checked="checked"',
                'exist_user0' => '',
                'previously_booked1'=>'',
                'previously_booked0'=>'checked="checked"',
                'exist_user_field'=>array(
                    'open_tag' => '<fieldset disabled="disabled">',
                    'close_tag' => '</fieldset>'
                    ),
            );
            $created_by = $this->entity_user_id;
        }else{
            $review_field = array(
                'exist_user1' => '',
                'exist_user0' => 'checked="checked"',
                'previously_booked1'=>'',
                'previously_booked0'=>'checked="checked"',
                'exist_user_field'=>array(
                    'open_tag' => '',
                    'close_tag' => ''
                    ),
            );
            $created_by = NULL;
        }
        $all_post['review_field'] = $review_field;
        $all_post['user_details'] = $user_details;
        $page_data['post_review_data'] = $all_post; 
            
        $page_data['currency_obj'] = new Currency(array('module_type' => 'Holiday','from' => get_application_default_currency(), 'to' => get_application_currency_preference()));
    //added
            $tours_crs_markup_agent = $this->private_management_model->get_markup('holiday');
            $tours_crs_markup_admin = $this->private_management_model->get_markup_admin('holiday');
            $page_data['tours_crs_markup_agent']=$tours_crs_markup_agent;
            $page_data['tours_crs_markup_admin']=$tours_crs_markup_admin;
            
        // debug($page_data);die;   
        $this->template->view('holiday/details',$page_data);
    }
    public function enquiry() {
        // echo 'herer I am';exit;
        $data = $this->input->post ();
        $package_id = $data['package_id'];
    
        if (($package_id !='') && ($data['first_name']) && ($data['phone']) && ($data['email']) && ($data['place']) && ($data['message'])) {
            // $package = $this->Package_Model->getPackage ( $package_id );
             $package = $this->tours_model->get_tour_details ( $package_id );
             // debug($package);exit;
            $data ['package_name'] = $package['package_name'];
            $data ['package_duration'] = $package['duration'];
            $data ['package_type'] = $package['tour_type'];
            // $data ['with_or_without'] = $package->price_includes;
            $data ['package_description'] = $package['package_description'];
            $data ['ip_address'] = $this->session->userdata ( 'ip_address' );
            $data ['status'] = '0';
            $data ['date'] = date ( 'Y-m-d H:i:s' );
            $data ['departure_date'] = date ('Y-m-d',strtotime($data ['departure_date']));
            $data ['domain_list_fk'] = get_domain_auth_id ();
            // debug($data);exit;   
            $result = $this->Package_Model->saveEnquiry ( $data );
            if($result){
                $eemail=$data['email'];
                $tours_enquiry_data['departure_date'] = $data['departure_date'];
                $tours_enquiry_data['message'] = $data['message'];
                $tours_enquiry_data['phone'] = $data['phone'];
                $tours_enquiry_data['email'] = $data['email'];
                $tours_enquiry_data['place'] = $data['place'];
                $tours_enquiry_data['first_name'] = $data['first_name'];
            
            $mail_template = $this->template->isolated_view ( 'holiday/inquery_template', $tours_enquiry_data);
            $this->load->library ( 'provab_mailer' );
            $s = $this->provab_mailer->send_mail ($eemail, 'Good Day! Your Tour Inquiry with QuaQua', $mail_template );
            }
            $status = true;
            $message = "Thank you for submitting your enquiry for this package, will get back to soon";
            header('content-type:application/json');
            echo json_encode(array('status' => $status, 'message' => $message));
            exit;
            
        } 
    }
    	public function pre_booking($id='')
	{
	

	    $post_data=$this->input->post();
     
      	$search_id=$this->tours_model->save_search_data_booking($post_data);
	
      	
		$sim_quantity=$post_data['sim_quantity'];
		$sim_price=$post_data['sim_price'];
		$sim_total_price=$sim_quantity*$sim_price;
	
		$safe_search_data = $this->tours_model->get_search_data_new($search_id);
		///	debug($safe_search_data);die;
		$holiday_checkin=$safe_search_data['date_of_travel'];
		
		$total_passengers=$post_data['no_adults']+$post_data['no_child'];
		
		$condition[]=array('id','=','"'.$id.'"');
		$condition[]=array('status','=',1);
		$pax_count=array(
                    'adult_count'=>$safe_search_data['no_adults'],
                    'child_count'=>$safe_search_data['no_child'],
                    'infant_count'=>$safe_search_data['no_infant']
                );
		
		$get_pax_count=$pax_count['adult_count']+$pax_count['child_count']+$pax_count['infant_count'];
		$booking_data=$this->tours_model->booking_tour($condition,'','','',$pax_count,$post_data['nationality']);
//debug($booking_data);die;
///die;	
	
		  $sql = "SELECT *
FROM all_nationality_country
WHERE find_in_set('".$post_data['nationality']."',all_nationality_country.include_countryCodes) and module='tours'";
   $query =$this->db->query($sql);
    $natprice = $query->result();
    
			$currency_obj = new Currency(array('module_type' => 'Holiday','from' =>'NPR', 'to' => get_application_currency_preference()));
		if($post_data['type_hotel']=='')
		{
			$post_data['type_hotel']='budget_hotel_price';
		}
		if($post_data['type_car']=='')
		{
			$post_data['type_car']='standard_car_price';
		}
		
		$price_sum  = $this->tours_model->tour_price($id);
			
		$hotel_price=$price_sum[0][$post_data['type_hotel']]*$post_data['no_of_room'];
		$car_price=$price_sum[0][$post_data['type_car']]*$post_data['no_of_transfer'];
		
		$package_name = $booking_data['data'][HOLIDAY_BOOKING]['tours_details']['package_name'];
		$banner_image = $booking_data['data'][HOLIDAY_BOOKING]['tours_details']['banner_image'];
		$banner_image = base_url().'extras/custom/'.CURRENT_DOMAIN_KEY.'/images/'.$banner_image;
		$total_price = $booking_data['data'][HOLIDAY_BOOKING]['tours_details']['price'];

		$total_price=$total_price+$hotel_price+$car_price;
		
		$default_currency = get_application_default_currency();
		$convenience_fees = 0;
		$safe_search_data['data']['search_id'] = abs($search_id);
		$page_data['active_payment_options'] = $this->module_model->get_active_payment_module_list();
		$page_data['search_data'] = $safe_search_data['data'];
        $page_data['pax_details'] = "";
        $page_data['user_country_code']="";
        
         //Get the country phone code 
        $Domain_record = $this->custom_db->single_table_records('domain_list', '*');

        $page_data['active_data'] = $Domain_record['data'][0];
    

         if (!empty($this->entity_country_code)) {
          

            $page_data['user_country_code'] = $this->entity_country_code;
        } else {
            $page_data['user_country_code'] = $Domain_record['data'][0]['phone_code'];
            
        }
        
        $page_data['booking_source'] =  HOLIDAY_BOOKING_SOURCE;
        $page_data['pre_booking_params'] = array(
        				'booking_source'    =>HOLIDAY_BOOKING_SOURCE,
        				'search_id'         =>"",
        				'ResultIndex'	    =>'',
        				'op'			    =>'', //block_room
        				'GuestNationality'  =>'QA',
        				'HotelName'		    =>$package_name,
        				'StarRating'	    =>0,
        				'HotelImage'        =>$banner_image,
        				'HotelAddress'	    =>"",
        				'CancellationPolicy'=>"",
        				'token'				=>"",
        				'token_key'			=>"",
        				'BlockRoomId'		=>"",
        				'token'				=>"",
        				"price_token"       =>"",
        				"HotelCode"			=>"",
        				"RoomTypeName"		=>"",
        				"Boarding_details"	=>"",
        				"LastCancellationDate"=>"",
        				"CancellationPolicy_API"=>"",
        				"TM_Cancellation_Charge"=>"",
        				"CancellationPolicy_API"=>"",
        				"price_summary"=>"",
        				"markup_price_summary"=>"",
        				"default_currency"=>$default_currency,
        				'holiday_checkin'=>$holiday_checkin,
        				'no_of_transfer'=>$post_data['no_of_transfer'],
        				'no_of_room'=>$post_data['no_of_room'],
        				'no_of_extrabed'=>$post_data['no_of_extrabed'],
        				'no_of_nights_holiday'=>$no_of_nights_holiday,
        				'sim_quantity'=>$sim_quantity,
        				'sim_price'=>$sim_price,
        				'total_price'=>$total_price,
        				'sim_total_price'=>$sim_total_price

        	);


        $page_data['iso_country_list']	= $this->db_cache_api->get_iso_country_list();
        $page_data['country_list']		= $this->db_cache_api->get_country_list();
		
		//$currency_obj = new Currency(array('module_type' => 'Holiday','from' => 'INR', 'to' => get_application_currency_preference()));
		$currency_obj = new Currency(array('module_type' => 'Holiday','from' => 'NPR', 'to' => get_application_currency_preference()));
		
		$page_data['currency_obj']		= $currency_obj;
		$page_data['total_price']		= $total_price;
		// $this->load->model('private_management_model');
		//$convinence_fees_row = $this->private_management_model->get_convinence_fees('Holiday');

		$currency_obj = new Currency(array('module_type' => 'flight','from' =>'NPR', 'to' => get_application_currency_preference()));
		if($currency_obj->to_currency=='NPR'){
 			$get_balance = $total_price;
	 	}else{
	 		$get_balance = $currency_obj->getConversionRate() * $total_price;
	 	}
    	$tours_crs_markup = $this->domain_management_model->get_markup('holiday');
    	$admin_markup_holidaylcrs = $this->domain_management_model->addHolidayCrsMarkup($get_balance, $tours_crs_markup,$currency_obj);
      	$min_price_1=str_replace(',','', $get_balance); 
      	$min_price=$min_price_1+$admin_markup_holidaylcrs;
 		 
    $currency_objholiday = new Currency(array('module_type' => 'Holiday','from' => 'NPR', 'to' => get_application_currency_preference()));
 		if($convinence_fees_row['per_pax']==1 && $convinence_fees_row['type'] == 'plus'){
        if($convinence_fees_row['value']!=0)
              {
                  $convenience_fees  = $currency_objholiday->convenience_fees_set($min_price,$id,$get_pax_count);

              }

      }else{
       $get_pax_count = 1;
       $convenience_fees  = $currency_objholiday->convenience_fees_set($min_price,$id,$get_pax_count);

      }


		$page_data['convenience_fees']	=   $convenience_fees;
		
	    $page_data['tax_service_sum']		= "";
	    $page_data['traveller_details']     ="";
	    $page_data['active_data']			="";
	    $page_data['tour_id']			    =$id;

		$query1 = 'select duration, expire_date from tours where id='.$id; 
		
			$page_data['duration_night'] = $this->db->query($query1)->result_array()[0];
	
		$temp_record = $this->custom_db->single_table_records('api_country_list', '*');
		$page_data['phone_code'] =$temp_record['data'];

		 $tours_crs_markup = $this->domain_management_model->get_markup('holiday');
		 $page_data['tours_crs_markup']=$tours_crs_markup;
		 $page_data['id']=$id;
		 $page_data['safe_search_data']=$safe_search_data;
				// debug($page_data['booking_details']); exit; 
			/*#############################FOR REWARDS SYSTEM #########################################*/
//debug($);die;
			            $page_data['total_price'] = ceil($page_data['total_price'] );
			           
					    $page_data['reward_usable'] = 0;
						$page_data['reward_earned'] = 0;
						$page_data['total_price_with_rewards'] = 0;
						if(is_logged_in_user()){
								$user_id = $this->entity_user_id;
								
								$reward_values = $this->rewards->find_reward(META_PACKAGE_COURSE,$page_data ['total_price']);
								// debug($reward_values);die;
								$reward_details = $this->rewards->get_reward_coversion_and_limit_details();	
								
								$usable_rewards = $this->rewards->usable_rewards($user_id,META_CAR_COURSE,$reward_values['usable_reward']);
								
								$page_data['reward_earned'] = $reward_values['earning_reward'];
								//debug($usable_rewards);exit();
								 if($reward_values['usable_reward']<=$usable_rewards){
                                       $page_data['reward_usable'] = $reward_values['usable_reward'] ;
                                    }else{
                                        $page_data['reward_usable'] = 0;
                                    }
								//debug($page_data['reward_usable']);exit();
								if($page_data['reward_usable']){
									$reducing_amount = $this->rewards->find_reward_amount($page_data['reward_usable']);	
									$page_data ['total_price_with_rewards'] = $page_data ['total_price']-$reducing_amount;
									$page_data['reducing_amount'] = $reducing_amount;
								}

						}
						
					/*#############################END #######################################################*/

		if ($id=='') {
			redirect(base_url(),'refresh');
		}else{
		   // debug($page_data);die;
			$this->template->view('holiday/pre_booking',$page_data );	
		}
	
	
	}
    public function pre_bookingold($id)
    {

        //error_reporting(E_ALL);
        
        //hotel format starts
         //debug($this->input->post());exit;
        $post_data=$this->input->post();
        // debug($post_data);exit;
        $search_id=$this->tours_model->save_search_data_booking($post_data);
        $sim_quantity=$post_data['sim_quantity'];
        $sim_price=$post_data['sim_price'];
        $sim_total_price=$sim_quantity*$sim_price;

        /*$this->session->set_userdata( array('adult_count' => $post_data['no_adults']));
        $this->session->set_userdata( array('child_count' => $post_data['no_child']));
        $this->session->set_userdata( array('holiday_checkin' => $post_data['date_of_travel']));
        $search_id = $this->session->userdata('holiday_search_id');
        $no_of_nights_holiday = $this->session->userdata('no_of_nights_holiday');
        $holiday_checkin = $this->session->userdata('holiday_checkin');*/

        //debug($holiday_checkin);exit();
        $safe_search_data = $this->tours_model->get_search_data_new($search_id);
        $holiday_checkin=$safe_search_data['date_of_travel'];
        // debug($safe_search_data);exit;
        $total_passengers=$post_data['no_adults']+$post_data['no_child'];
        //debug($safe_search_data);exit();
        //debug($safe_search_data);exit();
        //for taking the package details
        $condition[]=array('id','=','"'.$id.'"');
        $condition[]=array('status','=',1);
      		$pax_count=array(
                    'adult_count'=>$safe_search_data['no_adults'],
                    'child_count'=>$safe_search_data['no_child'],
                    'infant_count'=>$safe_search_data['no_infant']
                );
		
		$get_pax_count=$pax_count['adult_count']+$pax_count['child_count']+$pax_count['infant_count'];
		$booking_data=$this->tours_model->booking_tour($condition,'','','',$pax_count,$post_data['radios']);
        //debug($booking_data);exit();

        if($post_data['type_hotel']=='')
        {
            $post_data['type_hotel']='budget_hotel_price';
        }
        if($post_data['type_car']=='')
        {
            $post_data['type_car']='standard_car_price';
        }
        // debug($post_data);
        $price_sum  = $this->tours_model->tour_price($id);
        /*$hotel_price=$price_sum[0][$post_data['type_hotel']]*$total_passengers;
        $car_price=$price_sum[0][$post_data['type_car']]*$total_passengers;*/
        $hotel_price=$price_sum[0][$post_data['type_hotel']]*$post_data['no_of_room'];
        $car_price=$price_sum[0][$post_data['type_car']]*$post_data['no_of_transfer'];
        // debug($price_sum[0][$post_data['type_car']]);exit;
        $package_name = $booking_data['data'][HOLIDAY_BOOKING]['tours_details']['package_name'];
        $banner_image = $booking_data['data'][HOLIDAY_BOOKING]['tours_details']['banner_image'];

       $burl=str_replace('agent/', '', base_url());

        $banner_image = $burl.'extras/custom/'.CURRENT_DOMAIN_KEY.'/images/'.$banner_image;
        $total_price = $booking_data['data'][HOLIDAY_BOOKING]['tours_details']['price'];
        /*debug($total_price);
        debug($hotel_price);
        debug($car_price);die;*/
        $total_price=$total_price+$hotel_price+$car_price;
         //debug($total_price);exit('123');
        $default_currency = get_application_default_currency();
        $convenience_fees = 0;
        $safe_search_data['data']['search_id'] = abs($search_id);
        $page_data['active_payment_options'] = $this->module_model->get_active_payment_module_list();
        $page_data['search_data'] = $safe_search_data['data'];
        $page_data['pax_details'] = "";
        $page_data['user_country_code']="";
        $page_data['booking_source'] =  HOLIDAY_BOOKING_SOURCE;
        $page_data['pre_booking_params'] = array(
                        'booking_source'    =>HOLIDAY_BOOKING_SOURCE,
                        'search_id'         =>$search_id,
                        'ResultIndex'       =>'',
                        'op'                =>'', //block_room
                        'GuestNationality'  =>'QA',
                        'HotelName'         =>$package_name,
                        'StarRating'        =>0,
                        'HotelImage'        =>$banner_image,
                        'HotelAddress'      =>"",
                        'CancellationPolicy'=>"",
                        'token'             =>"",
                        'token_key'         =>"",
                        'BlockRoomId'       =>"",
                        'token'             =>"",
                        "price_token"       =>"",
                        "HotelCode"         =>"",
                        "RoomTypeName"      =>"",
                        "Boarding_details"  =>"",
                        "LastCancellationDate"=>"",
                        "CancellationPolicy_API"=>"",
                        "TM_Cancellation_Charge"=>"",
                        "CancellationPolicy_API"=>"",
                        "price_summary"=>"",
                        "markup_price_summary"=>"",
                        "default_currency"=>$default_currency,
                        'holiday_checkin'=>$holiday_checkin,
                        'no_of_transfer'=>$post_data['no_of_transfer'],
                        'no_of_room'=>$post_data['no_of_room'],
                        'no_of_extrabed'=>$post_data['no_of_extrabed'],
                        'no_of_nights_holiday'=>$no_of_nights_holiday,
                        'sim_quantity'=>$sim_quantity,
                        'sim_price'=>$sim_price,
                        'sim_total_price'=>$sim_total_price

            );

       // debug($page_data['pre_booking_params']);exit();

        $page_data['iso_country_list']  = $this->db_cache_api->get_iso_country_list();
        $page_data['country_list']      = $this->db_cache_api->get_country_list();
        
        $page_data['currency_obj']      = $currency_obj;
    //  debug($currency_obj);exit;
        $page_data['total_price']       = $total_price;
        //debug($page_data['total_price']);exit();
        $page_data['convenience_fees']      = $convenience_fees;
        $page_data['tax_service_sum']       = "";
        $page_data['traveller_details']     ="";
        $page_data['active_data']           ="";
        $page_data['tour_id']               =$id;
        $page_data['id']=$id;


        $query1 = 'select duration, expire_date from tours where id='.$id; //echo $query; exit;
        $page_data['duration_night'] = $this->db->query($query1)->result_array()[0];
        /*$exe   = mysql_query($query1);
        $page_data['duration_night'] = mysql_fetch_assoc($exe);*/
        //  $page_data['duration_night'] = $result_query;

        //debug($page_data['duration_night']);exit();
        $temp_record = $this->custom_db->single_table_records('api_country_list', '*');
                        $page_data['phone_code'] =$temp_record['data'];
                        
    
    //added :starts             
        $page_data['currency_obj'] = new Currency(array('module_type' => 'Holiday','from' =>'NPR', 'to' => get_application_currency_preference()));
        $tours_crs_markup_agent = $this->private_management_model->get_markup('holiday');
        $tours_crs_markup_admin = $this->private_management_model->get_markup_admin('holiday');
        $page_data['tours_crs_markup_agent']=$tours_crs_markup_agent;
        $page_data['tours_crs_markup_admin']=$tours_crs_markup_admin;
        $page_data['safe_search_data']=$safe_search_data;
    //added :ends
    if($this->entity_country_code)
    {
        $page_data['user_country_code']=$this->entity_country_code;
    }

    //debug($this->entity_country_code);exit;
                        
                    //  debug($page_data);exit;
                        
              	/*#############################FOR REWARDS SYSTEM #########################################*/
//debug($);die;
			            $page_data['total_price'] = ceil($page_data['total_price'] );
			           
					    $page_data['reward_usable'] = 0;
						$page_data['reward_earned'] = 0;
						$page_data['total_price_with_rewards'] = 0;
						if(is_logged_in_user()){
								$user_id = $this->entity_user_id;
								
								$reward_values = $this->rewards->find_reward(META_PACKAGE_COURSE,$page_data ['total_price']);
							//	 debug($reward_values);die;
								$reward_details = $this->rewards->get_reward_coversion_and_limit_details();	
								
								$usable_rewards = $this->rewards->usable_rewards($user_id,META_CAR_COURSE,$reward_values['usable_reward']);
								
								$page_data['reward_earned'] = $reward_values['earning_reward'];
								//debug($usable_rewards);exit();
								if($reward_details[0]['reward_min']<=$usable_rewards && $reward_details[0]['reward_max']>=$usable_rewards){
									$page_data['reward_usable'] = ceil($usable_rewards);
								}else{
									$page_data['reward_usable'] = 0;
								}
								//debug($page_data['reward_usable']);exit();
								if($page_data['reward_usable']){
									$reducing_amount = $this->rewards->find_reward_amount($page_data['reward_usable']);	
									$page_data ['total_price_with_rewards'] = $page_data ['total_price']-$reducing_amount;
									$page_data['reducing_amount'] = $reducing_amount;
								}

						}
						
					/*#############################END #######################################################*/
          
                        
        if($id=='')
        {
            redirect(base_url(),'refresh');
        }
        else
        {
            debug($page_data);die;
            $this->template->view('holiday/pre_booking',$page_data );   
        }
    }

	function pre_booking_holiday($id="")
	{
		
		$post_params = $this->input->post();
	//	debug($post_params);die;
		$post_params['payment_method']="PNHB1";
		$pg_type="PNHB1";

		 $amount=$post_params['tour_amount'];
		
		$valid_temp_token = "qatar";
		if ($valid_temp_token != false) {
			
			$post_params['booking_source'] = HOLIDAY_BOOKING_SOURCE;
			
			/****Convert Display currency to Application default currency***/
			$currency_obj = new Currency ( array (
						'module_type' => 'holiday',
						'from' => get_application_currency_preference (),
						'to' => admin_base_currency () 
				));
			
			//Insert To temp_booking and proceed
			
			$temp_booking = $this->module_model->serialize_temp_booking_record($post_params, HOLIDAY_BOOKING);
			
			$book_id = $temp_booking['book_id'];
			$book_origin = $temp_booking['temp_booking_origin'];
		
			$currency_obj = new Currency(array('module_type' => 'Holiday','from' => ADMIN_BASE_CURRENCY_STATIC, 'to' => get_application_currency_preference()));
			$convenience_fees  = $currency_obj->convenience_fees($amount,$id); //oldone 
	
			$total_passengers=$post_params['total_pax'];
		
		$currency_obj = new Currency(array('module_type' => 'Holiday','from' => ADMIN_BASE_CURRENCY_STATIC, 'to' => get_application_currency_preference()));
		//newone $convenience_fees  = $currency_obj->convenience_fees($amount,$id); 
		$convenience_fees  = $post_params['tax_total']; //newone

        if($convenience_fees > 0 )
        {
                $gst_details = $GLOBALS['CI']->custom_db->single_table_records('gst_master', '*', array('module' => 'holiday'));
                // debug($gst_details);exit;
                if($gst_details['status'] == true){
                    if($gst_details['data'][0]['gst'] > 0){
                       
                       
                       $gst_value_conv = ($convenience_fees/100) * $gst_details['data'][0]['gst'];
                    }
                }
        }
        if($gst_value_conv > 0)
        {
        	$amount +=$gst_value_conv;
        }
		
			/********* Promocode End ********/
			//details for PGI
            // added for wallet transaction
			$promocode_discount = 0;
			$user_wallet_balance = 0;
			$total = floatval($post_params['tour_amount']) + floatval($post_params['convenience_fee']);
			if (is_logged_in_user()){
				if(isset($post_params['promo_wallet'])){
					
					$promocode_discount="";
				}
			}else{
				$promocode_discount = $post_params['promo_code_discount_val'];	
			}
			// code ended wallet transaction
			$email = $post_params ['billing_email'];
			$phone = $post_params ['passenger_contact'];
			$enquiry_reference_no = $post_params ['enquiry_reference_no'];
			$verification_amount = roundoff_number($amount+$convenience_fees-$promocode_discount);
			$firstname = $post_params ['first_name'] ['0'];
			$productinfo = META_PACKAGE_COURSE;
            //check current balance before proceeding further

			$verification_amount=number_format((float)$verification_amount, 2, '.', '');
				if($post_params['redeem_points_post'] == "1")
		{
			$reward_discount = $post_params['reducing_amount'];
			$reward_amount = $post_params['reducing_amount'];
			$reward_point = $post_params['reward_usable'];
		}
			$reward_earned = $post_params['reward_earned'];

		if($reward_discount >=1)
		{
			$verification_amount 	= number_format(($amount-$reward_discount),2);
		}
		else
		{
			$verification_amount 	= number_format(($amount-$promocode_discount),2);
		}

				switch($post_params['payment_method']) {
					case PAY_NOW :
						$this->load->model('transaction');
						$pg_currency_conversion_rate = $currency_obj->payment_gateway_currency_conversion_rate();
					//	echo $reward_earned;die;
						$this->transaction->create_payment_record($book_id, $amount, $firstname, $email, $phone, $productinfo, $convenience_fees, $promocode_discount, $pg_currency_conversion_rate,$reward_point,$reward_amount,$reward_earned);
//echo "resrs";die;
						//to insert the the tour_booking details
						$date =  date('Y');
			            $rand = rand(1,99);
			            $app_reference = $book_id;
			          
			         $currency_obj_new = new Currency(array(
			             'module_type' => 'Holiday',
			             'to' => ADMIN_BASE_CURRENCY_STATIC, 
			             'from' => get_application_currency_preference()));
			             
			              $aed_basic_price=get_converted_currency_value ( $currency_obj_new->force_currency_conversion ( $post_params['tour_amount'] ) );
			              $aed_markup=get_converted_currency_value ( $currency_obj_new->force_currency_conversion ($post_params['markup'] ) );
			              $aed_gst_value=get_converted_currency_value ( $currency_obj_new->force_currency_conversion ( $post_params['gst_value'] ) );

			              $aed_gst_value_conv=get_converted_currency_value ( $currency_obj_new->force_currency_conversion ( $gst_value_conv ) );

			              $aed_discount=get_converted_currency_value ( $currency_obj_new->force_currency_conversion ( $post_params['promo_code_discount_val'] ) );
			             $aed_convenience_fee=get_converted_currency_value ( $currency_obj_new->force_currency_conversion ( $post_params['convenience_fee'] ) );
			             
			             $aed_array=array();
			             $aed_array=array(
			                 'aed_basic_price'=>$aed_basic_price,
			                 'aed_markup'=>$aed_markup+$aed_gst_value,
			                 'aed_gst_value'=>$aed_gst_value_conv,
			                 'aed_discount'=>$aed_discount,
			                 'aed_convenience_fee'=>$aed_convenience_fee
			              
			                 );
			             
			              //added:ends
						$tour_details = array(
							          'enquiry_reference_no'=>$enquiry_reference_no,
							          'app_reference'=>$app_reference,
									  'status'=>"PROCESSING",
									  'remarks'=>'',
									  'basic_fare'=>$post_params['tour_amount'],
									  'markup'=>$post_params['markup'],
									  'gst_value'=>$post_params['gst_value'],
									  'currency_code'=>get_application_currency_preference(),
									  'service_tax'=>'',
									  'discount'=>$post_params['promo_code_discount_val'],
									  'promocode'=>$post_params['promo_code'],
									  'payment_status'=>'unpaid',
									  'created_by_id' =>'',
									  'created_datetime'=>date('Y-m-d H:i:s'),
									  'booked_datetime'=>date('Y-m-d H:i:s'),
									  'booked_by_id'=>'',
									  'attributes'=>json_encode($post_params),
									  'user_attributes'=>"",
									  'email'=>$post_params['billing_email'],
									  'tours_id'=>$post_params['tour_id'],
									  'departure_date'=>$post_params['departure_date'],
									  'no_of_transfer'=>$post_params['no_of_transfer'],
									  'no_of_room'=>$post_params['no_of_room'],
									  'no_of_extrabed'=>$post_params['no_of_extrabed'],
									  'aed_array'=>json_encode($aed_array),
									  'user_type'=>B2C_USER
						);


                          if($this->db->insert('tour_booking_details',$tour_details))
                          {
                          	
                           $return_data=$this->tours_model->insert_pax_details($app_reference,$post_params);

						}
					redirect(base_url().'index.php/tours/process_booking/'.$book_id.'/'.$book_origin);
					///	redirect(base_url() . 'index.php/payment_gateway/payment/' . $book_id . '/' . $book_origin);
                         			
						break;
					case PAY_AT_BANK : echo 'Under Construction - Remote IO Error';exit;
					break;
				}
			
		} else {
			    redirect(base_url().'index.php/tours/exception?op=Remote IO error @ Hotel Booking&notification=validation');
		}
	}
    function pre_booking_holidayold($id)
    {
       
            $post_params['payment_method']="Ringgitpay";
                $pg_type="Ringgitpay";
            $post_params = $this->input->post();
        
   


        $amount = $post_params['tour_amount'];
        // $currency ='MYR';
        // debug($amount );
        $valid_temp_token = "qatar";
        if ($valid_temp_token != false) {
            //load_hotel_lib($post_params['booking_source']);
            $post_params['booking_source'] = HOLIDAY_BOOKING_SOURCE;
            

            // debug(HOLIDAY_BOOKING_SOURCE);exit();
            /****Convert Display currency to Application default currency***/
            $currency_obj = new Currency ( array (
                        'module_type' => 'holiday',
                        'from' => get_application_currency_preference (),
                        'to' => admin_base_currency () 
                ));
            
            //Insert To temp_booking and proceed
            $temp_booking = $this->module_model->serialize_temp_booking_record($post_params, HOLIDAY_BOOKING);
            
        //debug($temp_booking);exit;


            $book_id = $temp_booking['book_id'];
            $book_origin = $temp_booking['temp_booking_origin'];
        
        
            $currency_obj = new Currency ( array (
                        'module_type' => 'holiday',
                        'from' => admin_base_currency (),
                        'to' => admin_base_currency () 
            ) );


                // debug($currency_obj);exit();
            // $currency ='MYR';
            // debug($currency);
            /********* Convinence Fees Start ********/

            // debug($amount);exit();
            //$convenience_fees = $currency_obj->convenience_fees($amount, $search_id);

            // debug($convenience_fees);exit();
            /********* Convinence Fees End ********/
                
            /********* Promocode Start ********/
            //$promocode_discount = $post_params['promo_code_discount_val'];
            /********* Promocode End ********/
            //details for PGI
            // added for wallet transaction
            $promocode_discount = 0;
            $user_wallet_balance = 0;
            $total = floatval($post_params['total_amount_val']) + floatval($post_params['convenience_fee']);
            if (is_logged_in_user()){
                if(isset($post_params['promo_wallet'])){
                    
                    $promocode_discount="";
                }
            }else{
                $promocode_discount = $post_params['promo_code_discount_val'];  
            }
            // code ended wallet transaction
            $email = $post_params ['billing_email'];
            $phone = $post_params ['passenger_contact'];
            $verification_amount = roundoff_number($amount+$convenience_fees-$promocode_discount);
 
            $firstname = $post_params ['first_name'] ['0'];
            $productinfo = META_PACKAGE_COURSE;
            //check current balance before proceeding further
            //$currency="INR";
            $currency = COURSE_LIST_DEFAULT_CURRENCY_VALUE;
             //debug($verification_amount); debug($currency);exit();
            // debug($currency);
             // debug($currency);exit;
            $domain_balance_status = $this->domain_management_model->verify_current_balance($verification_amount, $currency);
        // debug($domain_balance_status);exit;
            
            if ($domain_balance_status == true) 
            {
                // echo "dd";exit;

                // error_reporting(E_ALL);
                $booking_data = $this->module_model->unserialize_temp_booking_record ( $book_id, $book_origin );
                // debug($book_id);exit;
                // debug($booking_data);exit;
              // debug($booking_data);die;
               // echo $this->db->last_query();die("gg");
               // debug($post_params);die;
                 $book_params = $booking_data['book_attributes'];
                $pack_id=$post_params['tour_id'];
                $pax_count=($post_params['no_of_child']+ $post_params['no_of_adult']);

                $data = $this->save_booking($book_id, $book_params,$pack_id,$pax_count,$currency_obj, $this->current_module);
              // debug($data);exit;


                $data['admin_markup']=0;
                $data['agent_markup']=0;
                $data['convinence']=0;
                $data['discount']=0;
               $data['currency_conversion_rate'] = $currency_obj->transaction_currency_conversion_rate();
               $data['transaction_currency'] = get_application_currency_preference();
               $agent_paybleamount['amount'] = $data['fare'];
               // debug($data);die;
                //comment below section to stop balance deduction
                $data['fare']= $data['fare']-$post_params['agent_markup'];
               // debug( $data['fare']);exit;
             

              

                    //debug($post_params['payment_method']);exit;
                        // echo "dd";

               // debug($post_params);exit;

	if($post_params['redeem_points_post'] == "1")
		{
			$reward_discount = $post_params['reducing_amount'];
			$reward_amount = $post_params['reducing_amount'];
			$reward_point = $post_params['reward_usable'];
		}
			$reward_earned = $post_params['reward_earned'];

		if($reward_discount >=1)
		{
			$verification_amount 	= number_format(($amount-$reward_discount),2);
		}
		else
		{
			$verification_amount 	= number_format(($amount-$promocode_discount),2);
		}

                switch($post_params['payment_method']) {
                    case PAY_NOW :
                        // debug("f");exit;
                        $this->load->model('transaction');
                        $pg_currency_conversion_rate = $currency_obj->payment_gateway_currency_conversion_rate();
                        $this->transaction->create_payment_record($book_id, $amount, $firstname, $email, $phone, $productinfo, $convenience_fees, $promocode_discount, $pg_currency_conversion_rate,$reward_point,$reward_amount,$reward_earned);

                        //to insert the the tour_booking details
                        $date =  date('Y');
                        $rand = rand(1,99);
                        $app_reference = $book_id;
                      //  debug("came");
                        
                         $aed_array=array();
                         $aed_array=array(
                             'aed_basic_price'=>$post_params['tour_amount'],
                             'aed_markup'=>$post_params['markup'],
                             'aed_gst_value'=>$post_params['gst_value'],
                             'aed_discount'=>$post_params['promo_code_discount_val'],
                             'aed_convenience_fee'=>$post_params['convenience_fee']
                          
                             );
                             
                            // debug($aed_array);exit;

                        // debug($app_reference);exit;
                        // debug($post_params);exit;
                        $tour_details = array('app_reference'=>$app_reference,
                                      'status'=>"PROCESSING",
                                      'remarks'=>'',
                                      'basic_fare'=>$post_params['tour_amount'],
                                      'currency_code'=>get_application_currency_preference(),
                                      'service_tax'=>'',
                                      'discount'=>$post_params['promo_code_discount_val'],
                                      'promocode'=>$post_params['promo_code'],
                                      'payment_status'=>'unpaid',
                                      'created_by_id' =>'',
                                      'created_datetime'=>date('Y-m-d H:i:s'),
                                      'booked_datetime'=>date('Y-m-d H:i:s'),
                                      'booked_by_id'=>'',
                                      'attributes'=>json_encode($post_params),
                                      'user_attributes'=>"",
                                      'email'=>$post_params['billing_email'],
                                      'tours_id'=>$post_params['tour_id'],
                                      'departure_date'=>$post_params['departure_date'],
                                      'no_of_transfer'=>$post_params['no_of_transfer'],
                                      'no_of_room'=>$post_params['no_of_room'],
                                      'no_of_extrabed'=>$post_params['no_of_extrabed'],
                                       'markup'=>$post_params['markup'],
                                      'gst_value'=>$post_params['gst_value'],
                                        'aed_array'=>json_encode($aed_array),
                                        'user_type'=>B2B_USER
                        );

                     // debug($tour_details);exit;
                          if($this->db->insert('tour_booking_details',$tour_details))
                          {
                            // debug($post_params);exit();
                           $return_data=$this->tours_model->insert_pax_details($app_reference,$post_params);
                           // debug($return_data);exit;
                            }
                             $this->domain_management_model->update_transaction_details('holiday', $book_id, $data['fare'], $data['admin_markup'], $data['agent_markup'], $data['convinence'], $data['discount'],$data['transaction_currency'], $data['currency_conversion_rate']);
                         //this is for test
                         // debug($book_id);debug($book_origin);exit;
                        redirect(base_url().'index.php/tours/process_booking/'.$book_id.'/'.$book_origin);
                         //this is payment getway process
                        // redirect(base_url().'index.php/payment_gateway/payment/'.$book_id.'/'.$book_origin);                     
                        break;
                    case PAY_AT_BANK : echo 'Under Construction - Remote IO Error';exit;
                    break;
                }
            } else {
                redirect(base_url().'index.php/tours/exception?op=Amount Hotel Booking&notification=insufficient_balance');
            }
        } else {
                redirect(base_url().'index.php/tours/exception?op=Remote IO error @ Hotel Booking&notification=validation');
        }
    }
    function process_booking($book_id, $temp_book_origin){
        // debug("safs");exit;
        
        if($book_id != '' && $temp_book_origin != '' && intval($temp_book_origin) > 0){

            $page_data ['form_url'] = base_url () . 'index.php/tours/secure_booking_holiday';
            $page_data ['form_method'] = 'POST';
            $page_data ['form_params'] ['book_id'] = $book_id;
            $page_data ['form_params'] ['temp_book_origin'] = $temp_book_origin;
            $this->template->view('share/loader/booking_process_loader', $page_data);   

        }else{
            redirect(base_url().'index.php/tours/exception?op=Invalid request&notification=validation');
        }
        
    }
    	function secure_booking_holiday()
	{
		if(ENVIRONMENT == 'testing')
        {
             debug("blocked");exit;
        }
		$post_data = $this->input->post();
		$app_reference =$post_data['book_id'];
		
		if(valid_array($post_data) == true && isset($post_data['book_id']) == true && isset($post_data['temp_book_origin']) == true &&
			empty($post_data['book_id']) == false && intval($post_data['temp_book_origin']) > 0){
			//verify payment status and continue
			$book_id = trim($post_data['book_id']);
			$temp_book_origin = intval($post_data['temp_book_origin']);
			$this->load->model('transaction');
			$booking_status = $this->transaction->get_payment_status($book_id);
			
			//if ($booking_status['status'] !== 'accepted') {
		//	redirect(base_url().'index.php/tours/exception?op=Payment Not Done&notification=validation');
           // }
            		
		} else{
			redirect(base_url().'index.php/tours/exception?op=InvalidBooking&notification=invalid');
		}		
		//run booking request and do booking
		$temp_booking = $this->module_model->unserialize_temp_booking_record($book_id, $temp_book_origin);
		
		$temp_booking['booking_source'] = 'qatar';
		$domain_balance_status = true;
		if ($domain_balance_status) {
			//lock table
			if ($temp_booking != false) {
				switch ($temp_booking['booking_source']) {
					case "qatar" :
						  $redeem_points_post=$temp_booking['book_attributes']['redeem_points_post'];
			$reward_usable=$temp_booking['book_attributes']['reward_usable'];
			$reward_earned=$temp_booking['book_attributes']['reward_earned'];
			$total_price_with_rewards=$temp_booking['book_attributes']['total_price_with_rewards'];
			$reducing_amount=$temp_booking['book_attributes']['reducing_amount'];
						//Save booking based on booking status and book id
					$tour_booking_details = array(
						'payment_status'=>'paid',
						'status'=>'BOOKING_CONFIRMED',					
'reward_amount'=>$reducing_amount,
				'reward_points'=>$reward_usable,
				'reward_earned'=>$reward_earned,
						);


					$booking['status'] = true;
					
					if($this->custom_db->update_record('tour_booking_details',$tour_booking_details,array('app_reference'=>$app_reference))){
						$booking['status'] =SUCCESS_STATUS;
					    }
					
					if($this->custom_db->update_record('package_booking_details',$tour_booking_details,array('app_reference'=>$app_reference))){
						$booking['status'] =SUCCESS_STATUS;
					    }

						
					$tour_booking_details = array(
					'payment_status'=>'paid',
					'status'=>'BOOKING_CONFIRMED',					
	
					); 

					if($this->custom_db->update_record('package_booking_details',$tour_booking_details,array('app_reference'=>$app_reference)))
					{
						$booking['status'] =SUCCESS_STATUS;
					} 

					$tour_booking_details = array( 
					'status'=>'BOOKING_CONFIRMED'					

					); 
											
					if($this->custom_db->update_record('package_booking_transaction_details',$tour_booking_details,array('app_reference'=>$app_reference)))
					{
						$booking['status'] =SUCCESS_STATUS;
					} 

					if($this->custom_db->update_record('package_booking_passenger_details',$tour_booking_details,array('app_reference'=>$app_reference)))
					{
						$booking['status'] =SUCCESS_STATUS;
					} 


				}


				
				if ($booking['status'] == SUCCESS_STATUS) {
					
					$remarks ='holiday Transaction was Successfully done';
					$amount=$temp_booking['book_attributes']['total_amount_val'];
					$domain_markup=$temp_booking['book_attributes']['markup'];
					$level_one_markup=$temp_booking['book_attributes']['markup'];
					$gst=$temp_booking['book_attributes']['gst_value'];
					$convinence=$temp_booking['book_attributes']['convenience_fee'];
					$discount=$temp_booking['book_attributes']['promo_code_discount_val'];
					$currency=$temp_booking['book_attributes']['currency'];
					$action_query_string = array('app_reference' => $app_reference, 'type' =>'Holiday', 'module' => $this->config->item('current_module'));
					$this->load->model("user_model");
					$notification_users = $this->user_model->get_admin_user_id();
					
					$currency_obj = new Currency ( array (
						'module_type' => 'holiday',
						'from' => get_application_currency_preference (),
						'to' => admin_base_currency () 
						));
			        
			       
			        $currency_conversion_rate = $currency_obj->transaction_currency_conversion_rate();
					
					$this->load->model('domain_management_model');
					$this->domain_management_model->save_transaction_details('holiday', $app_reference, $amount, $domain_markup, $level_one_markup, $convinence, $discount, $remarks, $currency, $currency_conversion_rate, $gst);

					$this->application_logger->transaction_status($remarks.'('.$amount.')', $action_query_string, $notification_users);
	      
			if(is_logged_in_user())
			{
				
				$book_attributes=array("reward_earned"=>$reward_earned,"reward_usable"=>$reward_usable,"reducing_amount"=>$reducing_amount);

				$data=array("booking_source"=>META_PACKAGE_COURSE,"book_attributes"=>$book_attributes);
//debug($book_attributes);die;
				if($redeem_points_post =="1")
				{
				     //echo "test1";die;
					$this->rewards->update_after_booking($data,$book_id);
				}
				else
				{
				   // echo "test2";die;
					$this->rewards->update_reward_earned_value($data,$book_id);
				}
			}
					$this->session->set_userdata(array($app_reference=>'1'));
				redirect(base_url().'index.php/voucher/holiday/'.$app_reference.'/BOOKING_CONFIRMED/show_voucher/mail','refresh');
				} else { 

					redirect(base_url().'index.php/tours/exception?op=booking_exception&notification='.$booking['msg']);
				}
			}
			//release table lock
		} else {
			redirect(base_url().'index.php/hotel/exception?op=Remote IO error @ Insufficient&notification=validation');
		}
		
	}
    function secure_booking_holidayoldagent()
    {
        $post_data = $this->input->post();
        $app_reference =$post_data['book_id'];
        // debug($post_data);exit;
        if(valid_array($post_data) == true && isset($post_data['book_id']) == true && isset($post_data['temp_book_origin']) == true &&
            empty($post_data['book_id']) == false && intval($post_data['temp_book_origin']) > 0){
            //verify payment status and continue
            // debug($post_data['book_id']);exit;
            $book_id = trim($post_data['book_id']);
            $temp_book_origin = intval($post_data['temp_book_origin']);
            $this->load->model('transaction');
            $booking_status = $this->transaction->get_payment_status($book_id);
            // debug($booking_status);exit;
            //debug($post_data);exit;
            // if($booking_status['status'] !== 'accepted'){
            //  redirect(base_url().'index.php/hotel/exception?op=Payment Not Done&notification=validation');
         //    }
        } else{
            redirect(base_url().'index.php/tours/exception?op=InvalidBooking&notification=invalid');
        }  
        // debug("gg");   exit;  
        //run booking request and do booking
        $temp_booking = $this->module_model->unserialize_temp_booking_record($book_id, $temp_book_origin);

        //Delete the temp_booking record, after accessing
        // $this->module_model->delete_temp_booking_record ($book_id, $temp_book_origin);
        //load_hotel_lib($temp_booking['booking_source']);
        //verify payment status and continue
        //debug($temp_booking);exit();
        //debug($temp_booking['book_attributes']['total_amount']);exit();
        //$total_booking_price = $this->hotel_lib->total_price($temp_booking['book_attributes']['token']['markup_price_summary']);
        //$total_booking_price = "";
    //  $currency = $temp_booking['book_attributes']['token']['default_currency'];
        //also verify provab balance
        //check current balance before proceeding further
        //$domain_balance_status = $this->domain_management_model->verify_current_balance($total_booking_price, $currency);
        //debug($temp_booking);exit();
        $temp_booking['booking_source'] = 'qatar';
        // debug($temp_booking);exit;
        $domain_balance_status = true;
        if ($domain_balance_status) {
            //lock table
            if ($temp_booking != false) {
                switch ($temp_booking['booking_source']) {
                    case "qatar" :
                          $redeem_points_post=$temp_booking['book_attributes']['redeem_points_post'];
			$reward_usable=$temp_booking['book_attributes']['reward_usable'];
			$reward_earned=$temp_booking['book_attributes']['reward_earned'];
			$total_price_with_rewards=$temp_booking['book_attributes']['total_price_with_rewards'];
			$reducing_amount=$temp_booking['book_attributes']['reducing_amount'];
                        //FIXME : COntinue from here - Booking request
                        // $booking = $this->hotel_lib->process_booking($book_id, $temp_booking['book_attributes']);
                        //Save booking based on booking status and book id
                    $tour_booking_details = array(
                        'payment_status'=>'paid',
                        'status'=>'BOOKING_CONFIRMED',
                        'reward_amount'=>$reducing_amount,
				'reward_points'=>$reward_usable,
				'reward_earned'=>$reward_earned,

                        );
                    $booking['status'] = true;
                    //debug($tour_booking_details);exit();
                    if($this->custom_db->update_record('tour_booking_details',$tour_booking_details,array('app_reference'=>$app_reference))){
                        $booking['status'] =SUCCESS_STATUS;
                        }
                    
                        break;
                }
            //  debug($booking['status']);exit('');
                if ($booking['status'] == SUCCESS_STATUS) {
                    
                $this->session->set_userdata(array($app_reference=>'1'));
                	$book_attributes=array("reward_earned"=>$reward_earned,"reward_usable"=>$reward_usable,"reducing_amount"=>$reducing_amount);

				$data=array("booking_source"=>META_PACKAGE_COURSE,"book_attributes"=>$book_attributes);
//debug($book_attributes);die;
				if($redeem_points_post =="1")
				{
				     //echo "test1";die;
					$this->rewards->update_after_booking($data,$book_id);
				}
				else
				{
				   // echo "test2";die;
					$this->rewards->update_reward_earned_value($data,$book_id);
				}
            redirect(base_url().'index.php/voucher/holiday/'.$app_reference.'/BOOKING_CONFIRMED/show_voucher/mail','refresh');
                } else { 

                    redirect(base_url().'index.php/tours/exception?op=booking_exception&notification='.$booking['msg']);
                }
            }
            //release table lock
        } else {
            redirect(base_url().'index.php/hotel/exception?op=Remote IO error @ Insufficient&notification=validation');
        }
        
    }
    function save_booking($app_booking_id, $book_params,$pack_id,$pax_coun,$currency_obj, $module='b2b')
    {     
           
        $book_total_fare = array();
        $book_domain_markup = array();
        $book_level_one_markup = array();
        $master_transaction_status = 'BOOKING_INPROGRESS';
        $master_search_id = $book_params['pack_id'];

        $domain_origin = get_domain_auth_id();
        $app_reference = $app_booking_id;
        $booking_source = $book_params['token']['booking_source'];

        //PASSENGER DATA UPDATE
        $total_pax_count =($book_params['adult'] + $book_params['child']+$book_params['infant']);
        $pax_count = $total_pax_count;

        //PREFERRED TRANSACTION CURRENCY AND CURRENCY CONVERSION RATE 
        $transaction_currency = get_application_currency_preference();
        $application_currency = admin_base_currency();
        $currency_conversion_rate = $currency_obj->transaction_currency_conversion_rate();

        $token = $book_params['tour_amount'];
        //debug($book_params);die;
        $master_booking_source = array();
        $currency = $currency_obj->to_currency;
        $deduction_cur_obj  = clone $currency_obj;
        //Storing Flight Details - Every Segment can repeate also
        // debug($token);die;
        $token_index=1;
        $commissionable_fare =  $token;
            $pnr = '';
            $book_id = '';
            $source = '';
            $ref_id = '';
            $transaction_status = 0;
            $GetBookingResult = array();
            $transaction_description = '';
            $getbooking_StatusCode = '';
            $getbooking_Description = '';
            $getbooking_Category = '';
            $WSTicket = array();
            $WSFareRule = array();
            //Saving Flight Transaction Details
            $tranaction_attributes = array();
            $pnr = '';
            $book_id = $pack_id;
            $source = 'Provab';
            $ref_id = '';
            $transaction_status = $master_transaction_status;
            $transaction_description = '';
            //Get Booking Details
            $getbooking_status_details = '';
            $getbooking_StatusCode = '';
            $getbooking_Description = '';
            $getbooking_Category = '';
            $tranaction_attributes['Fare'] = $token['total'];
            $sequence_number = $token_index;
            //Transaction Log Details
            $ticket_trans_status_group[] = $transaction_status;
            $book_total_fare[]  = $token['total'];
            
            //Need individual transaction price details
            //SAVE Transaction Details



$transaction_insert_id = $this->Package_Model->save_package_booking_transaction_details($app_reference, $transaction_status, $transaction_description, $pnr, $book_id, $source, $ref_id,json_encode($tranaction_attributes),$currency, $commissionable_fare);
// debug($transaction_insert_id);die;

$transaction_insert_id = $transaction_insert_id['insert_id'];

            //Saving Passenger Details
            $i = 0;
            // for ($i=0; $i<$total_pax_count; $i++)
            // {
                $passenger_type = 'Adult';
                $is_lead =1;
                $first_name = $book_params['first_name'][0];
                $last_name = $book_params['last_name'][0];
                $gender = strtoupper($book_params['gender']);
                $passenger_nationality=$book_params['billing_country'];
                $status = $master_transaction_status;
                $passenger_attributes = $book_params; 
                $flight_booking_transaction_details_fk = $transaction_insert_id;//Adding Transaction Details Origin 
                //SAVE Pax Details

$pax_insert_id = $this->Package_Model->save_package_booking_passenger_details(
                  $app_reference,$passenger_type,$is_lead,$first_name,$last_name,$gender,$passenger_nationality,$status,
                json_encode($passenger_attributes),$flight_booking_transaction_details_fk,$book_params['no_of_adult'],$book_params['no_of_child'],0);
//die("982chk");
//die("chk1");
//echo $this->db->last_query();die;


                //Save passenger ticket information     
        
 //debug($pax_insert_id);die;       

            //}//Adding Pax Details Ends
    //debug($book_params);die;

            $date_of_travel=date('Y-m-d', strtotime($book_params['departure_date']));
            // debug($date_of_travel);exit;

             //debug($book_params);die;

        //Save Master Booking Details
        $book_total_fare = $book_params['tour_amount'];
        $phone = $book_params['passenger_contact'];
        $alternate_number = '';
        $email = $book_params['billing_email'];
        $payment_mode = $book_params['payment_method'];

        $payment_mode = 'agent';

        $adult = $book_params['adult'];
        $child = $book_params['child'];
        $infant =$book_params['infant'];
        $created_by_id = intval(@$GLOBALS['CI']->entity_user_id);

        $passenger_country =$book_params['billing_country'];
        
        $passenger_city = $book_params['billing_city'];
        $booking_source = $book_params['booking_source'];

        $attributes = array('country' => $passenger_country, 'city' => $passenger_city, 'zipcode' => $book_params['billing_zipcode'], 'address' =>  $book_params['billing_address_1']);
        $package_booking_status = $master_transaction_status;
        //SAVE Booking Details
        //echo $pack_id;die;
        $this->Package_Model->save_tour_package_booking_details(
        $domain_origin, $package_booking_status, $app_reference, $booking_source, $phone, $alternate_number, $email,
        $payment_mode, json_encode($attributes), $created_by_id,
         $transaction_currency, $currency_conversion_rate,$pack_id,$date_of_travel,$adult,$child,$infant);


        /************** Update Convinence Fees And Other Details Start ******************/
        
        $response['fare'] = $book_total_fare;
        $response['status'] = $package_booking_status;
        $response['status_description'] = $transaction_description;
        $response['name'] = $first_name;
        $response['phone'] = $phone;
        $data['transaction_currency'] = $transaction_currency;
        $data['currency_conversion_rate'] = $currency_conversion_rate;
         //debug($response); exit;
            
        return $response;
    }

   /* public function search(){
            $data = $this->input->get();
                $currency_obj = new Currency(array('module_type' => 'hotel','from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
            if(!empty($data)){
                    $country = $data['country'];
                    $packagetype = $data['package_type'];
                    if($data['duration']){
                        $duration = explode('-', $data['duration']);
                        if(count($duration)>1){
                            $duration = "duration between ".$duration['0']." AND ".$duration['1'];
                        }else{
                             $duration = "duration >".$duration['0'];
                        }
                    }else{
                        $duration = $data['duration'];
                    }
                    if($data['budget']){
                        $budget = explode('-', $data['budget']);

                        if(count($budget)>1){
                            $budget = "price between ".$budget['0']." AND ".$budget['1'];
                        }else if($budget[0]){
                            $budget = "price >".$budget['0'];
                        }
                    }else{
                        $budget = $data['budget'];
                    }
                    $domail_list_pk = get_domain_auth_id();
                    $data['currency_obj'] = $currency_obj;
                    $data['scountry'] = $country;
                    $data['spackage_type'] = $packagetype;
                    $data['sduration'] = $data['duration'];
                    $data['sbudget'] = $data['budget'];
                    $data['packages'] = $this->Package_Model->search($country,$packagetype,$duration,$budget,$domail_list_pk,$domail_list_pk);
                    $data['caption'] = $this->Package_Model->getPageCaption('tours_packages')->row();
                    $data['countries'] = $this->Package_Model->getPackageCountries();
                    $data['package_types'] = $this->Package_Model->getPackageTypes();
                    $this->template->view('holiday/tours', $data);

                    
            }else{
                redirect('tours/all_tours');
            }
    }*/
    	public function search($search_id) {
    	
		$data = $this->input->get ();
		
		if(false){
				
 					$query = "select t1.*, t1.id as id, t1.inclusions_checks as inclusions_checks from tours t1 LEFT JOIN tour_visited_cities tv on (tv.tour_id = t1.id) 
 						LEFT JOIN tours_itinerary on tours_itinerary.tour_id = t1.id  						
 					where  t1.status=1 AND tours_itinerary.publish_status =1 AND t1.expire_date>'".date('Y-m-d')."' 
 					group by t1.id order by t1.id DESC";
 					// LEFT JOIN tour_price_management tp on (t1.id = tp.tour_id) # ,min(tp.airliner_price) as min_price
 						
			}else{
			    
				$country = $data ['city'];
				$packagetype = $data ['package_type'];
				$where="";
				if ($data ['duration']) {
					$duration = explode ( '-', $data ['duration'] );
					if (count ( $duration ) > 1) {
						$where .= " and t1.duration between " . $duration ['0'] . " AND " . $duration ['1'];
					} else {
						$where .= " and t1.duration >" . $duration ['0'];
					}
				}
				
				
				if($country !="")
				{
					$country = $this->db->escape ('%'.$country.'%');
					
					$where .=" and (tc2.CityName LIKE $country OR tc3.name  LIKE $country OR t1.package_name LIKE $country)";
					
				}
				if($packagetype !="")
				{
					$where .=' and t1.tour_type like "%'.$packagetype.'%"';
				}
				
				
					$query_count='SELECT *, t1.id as id, t1.inclusions_checks as inclusions_checks FROM tours AS t1 
					LEFT JOIN tours_country_wise AS tcw1 ON t1.id=tcw1.tour_id 
					LEFT JOIN tours_city_wise AS tcw2 ON t1.id=tcw2.tour_id 
					LEFT JOIN tours_country AS tc1 ON tcw1.country_id=tc1.id 
					LEFT JOIN tours_city AS tc2 ON tcw2.city_id=tc2.id 
					LEFT JOIN tours_continent AS tc3 ON t1.tours_continent=tc3.id 
					LEFT JOIN tours_itinerary AS ti ON ti.tour_id=t1.id 
					LEFT JOIN tour_visited_cities tvc ON (tvc.tour_id = t1.id) 
					WHERE ti.publish_status=1  and t1.status=1 
					AND t1.expire_date>"'.date('Y-m-d').'" '.$where.'
					
					GROUP BY t1.id  order by t1.id DESC';

					$get_url = $_SERVER['REQUEST_URI'];
					$values = parse_url($get_url);
					$host = explode('/',$values['path']);
					$geturlid=$host[5];
					
					$getsearchhis = "SELECT * FROM search_history WHERE origin = '$search_id' ";
					$searchhisresult = $this->db->query($getsearchhis)->result_array();
					
				if($geturlid=='') 
				{
					$offset=0;
					$record = RECORDS_RANGE_2;
				}

					if($geturlid=='20' || $geturlid=='40' || $geturlid=='60' || $geturlid=='80' || $geturlid=='100' || $geturlid=='120' || $geturlid=='140' || $geturlid=='160' || $geturlid=='180' || $geturlid=='200' || $geturlid=='220' || $geturlid=='240' || $geturlid=='260' || $geturlid=='280' || $geturlid=='300') 
					{
						$offset=$geturlid;
						$record = RECORDS_RANGE_2;
					}else{
						$offset=0;
						$record = RECORDS_RANGE_2;
					}

					$query = 'SELECT *, t1.id as id, t1.inclusions_checks as inclusions_checks FROM tours AS t1 
					LEFT JOIN tours_country_wise AS tcw1 ON t1.id=tcw1.tour_id 
					LEFT JOIN tours_city_wise AS tcw2 ON t1.id=tcw2.tour_id 
					LEFT JOIN tours_country AS tc1 ON tcw1.country_id=tc1.id 
					LEFT JOIN tours_city AS tc2 ON tcw2.city_id=tc2.id 
					LEFT JOIN tours_continent AS tc3 ON t1.tours_continent=tc3.id 
					LEFT JOIN tours_itinerary AS ti ON ti.tour_id=t1.id 
					LEFT JOIN tour_visited_cities tvc ON (tvc.tour_id = t1.id) 
					WHERE ti.publish_status=1  and t1.status=1 
					AND t1.expire_date>"'.date('Y-m-d').'" '.$where.'
					
					GROUP BY t1.id  order by t1.id DESC limit '.$offset.', '.$record;
				
				}

							
			$searchResult = $this->custom_db->get_result_by_query($query);
			foreach ($searchResult as $key => $value) {
				
				$tours_itinerary_dw=$this->tours_model->get_tours_itinerary_dw($value->id);
				
				$searchResult[$key]->tours_itinerary_dw=$tours_itinerary_dw;
			}
			 
			$page_data['searchResult'] = json_decode(json_encode($searchResult),1);

			$searchResultcount = $this->custom_db->get_result_by_query($query_count);
			foreach ($searchResultcount as $key => $value) {
				
				$tours_itinerary_dw=$this->tours_model->get_tours_itinerary_dw($value->id);
				
				$searchResultcount[$key]->tours_itinerary_dw=$tours_itinerary_dw;
			}

			$page_data['searchResultcount'] = json_decode(json_encode($searchResultcount),1);

			$page_data['country_code'] = $this->db_cache_api->get_country_code_list();
		
			$page_data['searchParam']      = $data;
				$page_data ['tours_continent'] = $this->tours_model->tours_continent();
				
			$page_data ['tours_country']   = $this->tours_model->tours_country_name();
			$page_data ['tours_country_name_modify']   = $this->tours_model->tours_country_name_modify($holiday_region);


			$page_data ['tour_type']       = $this->tours_model->tour_type();
			$page_data ['tour_theme']      = $this->tours_model->tour_subtheme();
			$page_data ['tours_continent_name']   = $this->tours_model->tours_continent_name();
	
			$query2 = "select t1.*, t1.id as id, t1.inclusions_checks as inclusions_checks ,min(tp.airliner_price) as min_price
 						from tours t1 LEFT JOIN tour_visited_cities tv on (tv.tour_id = t1.id) 
 						LEFT JOIN tours_itinerary on tours_itinerary.tour_id = t1.id 
 						LEFT JOIN tour_price_management tp on (t1.id = tp.tour_id)
 					where  t1.status=1 AND tours_itinerary.publish_status =1 AND tp.Type='".$data['radio']."' AND t1.expire_date>'".date('Y-m-d')."' 
 					group by t1.id order by t1.id DESC limit 0,12";
			
			
			$page_data ['top_new_packages'] = json_decode(json_encode( $this->custom_db->get_result_by_query($query2)),1);
			$page_data ['tours_city_name']= $this->tours_model->tours_city_name();


			$theme_set = $this->tours_model->theme_set($page_data['searchResult']);

			$page_data ['theme_set']  = $theme_set;

			$page_data ['theme_name'] = $this->tours_model->theme_name($theme_set);

			$page_data ['region_set']   = $this->tours_model->region_set($query); 
			
			$page_data ['country_set']  = $this->tours_model->country_set($query);

			$page_data ['category_set'] = $this->tours_model->category_set($query);

			$page_data['duration_set'] = $this->tours_model->duration_set($page_data['searchResult']); 
			
			$page_data['currency_obj'] = new Currency(array('module_type' => 'Holiday','from' =>'NPR', 'to' => get_application_currency_preference()));

			
			$safe_search_data = $this->tours_model->get_safe_search_data($search_id);
			
			$page_data['tour_search_params'] = $safe_search_data['data'];
			
			$default_currency = get_application_default_currency();
 
			$page_data['default_currency'] = $default_currency;
			

			 $tours_crs_markup = $this->domain_management_model->get_markup('holiday');
			
			$page_data['tours_crs_markup']=$tours_crs_markup;
			
			$page_data ['countries'] = $this->Package_Model->getPackageCountries_new ();
			$page_data ['package_types'] = $this->Package_Model->getPackageTypes2 ();
			
			$offset=0;
			$record = RECORDS_RANGE_2;
			$get_url = $_SERVER['REQUEST_URI'];
			$values = parse_url($get_url);
			$host = explode('/',$values['path']);
			
			$get_data = $this->input->get();

			$total_records = ($page_data['searchResultcount'])? count($page_data['searchResultcount']) : 0;
			$this->load->library('pagination');
			if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
			
			$config['base_url'] = base_url().'index.php/tours/search/';
			$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
			$page_data['total_rows'] = $config['total_rows'] = $total_records;
			$config['per_page'] = RECORDS_RANGE_2;
			$this->pagination->initialize($config);
			$page_data['total_records'] = $config['total_rows'];
			$page_data['search_params'] = $get_data;
				$data["country"] = $this->custom_db->single_table_records('api_country_list', '*')[data];
            	 $page_data['holiday_data'] = $data; 
            	 
			$this->template->view( 'holiday/result', $page_data );
	
	}
    public function searchold($search_id) {
        $data = $this->input->get ();
        // error_reporting(E_ALL);


        if(false){
                //debug("if");
            /*$query = "select *, t1.id as id, t1.inclusions_checks as inclusions_checks ,min(tp.airliner_price) as min_price
                        from tours t1 join tour_visited_cities tv on (tv.tour_id = t1.id) 
                        join tours_itinerary on tours_itinerary.tour_id = t1.id 
                        join tour_price_management tp on (t1.id = tp.tour_id)
                    where  t1.status=1 AND tours_itinerary.publish_status =1 AND t1.expire_date>'".date('Y-m-d')."' 
                    group by t1.id order by t1.id DESC";*/
                    $query = "select t1.*, t1.id as id, t1.inclusions_checks as inclusions_checks from tours t1 LEFT JOIN tour_visited_cities tv on (tv.tour_id = t1.id) 
                        LEFT JOIN tours_itinerary on tours_itinerary.tour_id = t1.id                        
                    where  t1.status=1 AND tours_itinerary.publish_status =1 AND t1.expire_date>'".date('Y-m-d')."' 
                    group by t1.id order by t1.id DESC";
                    // LEFT JOIN tour_price_management tp on (t1.id = tp.tour_id) # ,min(tp.airliner_price) as min_price
                        
            }else{
                ///debug("else");

                /*$term = $data['city']; 
                $term = trim ( strip_tags ( $term ) );
                // Removed search cases(According to client)
                // $search_chars = $this->db->escape ('%'.$term.'%');
                // OR t1.package_description  LIKE '.$search_chars.'
                $search_chars = $this->db->escape ($term);*/
                //debug(strlen($search_chars));exit;
                $country = $data ['country'];
                $packagetype = $data ['package_type'];
                $where="";
                if ($data ['duration']) {
                    $duration = explode ( '-', $data ['duration'] );
                    if (count ( $duration ) > 1) {
                        $where .= " and t1.duration between " . $duration ['0'] . " AND " . $duration ['1'];
                    } else {
                        $where .= " and t1.duration >" . $duration ['0'];
                    }
                }
                //echo 'herre'.$duration;exit;
                
                
                if($country !="")
                {
                    $country = $this->db->escape ('%'.$country.'%');
                    // echo $country;exit;
                    // $where .=' and tc1.name="'.$country.'"';
                    $where .=" and (tc1.name LIKE $country OR tc2.CityName LIKE $country OR tc3.name  LIKE $country OR t1.package_name LIKE $country)";
                    // echo 
                }
                if($packagetype !="")
                {
                    $where .=' and t1.tour_type like "%'.$packagetype.'%"';
                }
                
                // debug($where);exit;
 
                


                    $query = 'SELECT *, t1.id as id, t1.inclusions_checks as inclusions_checks FROM tours AS t1 
                    LEFT JOIN tours_country_wise AS tcw1 ON t1.id=tcw1.tour_id 
                    LEFT JOIN tours_city_wise AS tcw2 ON t1.id=tcw2.tour_id 
                    LEFT JOIN tours_country AS tc1 ON tcw1.country_id=tc1.id 
                    LEFT JOIN tours_city AS tc2 ON tcw2.city_id=tc2.id 
                    LEFT JOIN tours_continent AS tc3 ON t1.tours_continent=tc3.id 
                    LEFT JOIN tours_itinerary AS ti ON ti.tour_id=t1.id 
                    LEFT JOIN tour_visited_cities tvc ON (tvc.tour_id = t1.id) 
                    WHERE ti.publish_status=1  and t1.status=1 
                    AND t1.expire_date>"'.date('Y-m-d').'" '.$where.'
                    
                    GROUP BY t1.id  order by t1.id DESC';
                
                }

                // echo $query;exit;

                // AND tc2.CountryName='.$search_chars.'
                // LEFT JOIN tour_price_management tpm ON (t1.id = tpm.tour_id)  #,min(tpm.airliner_price) as min_price

            // debug($query); exit('');
            // $searchResult = $this->tours_model->getSearchResult($query);         
            $searchResult = $this->custom_db->get_result_by_query($query);
             // debug($searchResult); exit('');         
            $page_data['searchResult'] = json_decode(json_encode($searchResult),1);

            $page_data['country_code'] = $this->db_cache_api->get_country_code_list();
        
            $page_data['searchParam']      = $data;
                $page_data ['tours_continent'] = $this->tours_model->tours_continent();
                    //debug($page_data ['tours_continent']);exit;
                
            $page_data ['tours_country']   = $this->tours_model->tours_country_name();
            $page_data ['tours_country_name_modify']   = $this->tours_model->tours_country_name_modify($holiday_region);


            $page_data ['tour_type']       = $this->tours_model->tour_type();
            $page_data ['tour_theme']      = $this->tours_model->tour_subtheme();
            $page_data ['tours_continent_name']   = $this->tours_model->tours_continent_name();
            $query2 = "select t1.*, t1.id as id, t1.inclusions_checks as inclusions_checks ,min(tp.airliner_price) as min_price
                        from tours t1 LEFT JOIN tour_visited_cities tv on (tv.tour_id = t1.id) 
                        LEFT JOIN tours_itinerary on tours_itinerary.tour_id = t1.id 
                        LEFT JOIN tour_price_management tp on (t1.id = tp.tour_id)
                    where  t1.status=1 AND tours_itinerary.publish_status =1 AND t1.expire_date>'".date('Y-m-d')."' 
                    group by t1.id order by t1.id DESC limit 0,12";
            // $page_data ['top_new_packages'] = $this->tours_model->getSearchResult($query2);
            $page_data ['top_new_packages'] = json_decode(json_encode( $this->custom_db->get_result_by_query($query2)),1);
            $page_data ['tours_city_name']= $this->tours_model->tours_city_name();


            /*if(!empty($searchResult)){
                $page_data ['confirmed_dep_date_list']   = $this->tours_model->confirmed_dep_date_list($query);
            }*/
            $theme_set = $this->tours_model->theme_set($page_data['searchResult']);

            $page_data ['theme_set']  = $theme_set;

            $page_data ['theme_name'] = $this->tours_model->theme_name($theme_set);

            $page_data ['region_set']   = $this->tours_model->region_set($query); 
            //debug("cominmg");exit;
            $page_data ['country_set']  = $this->tours_model->country_set($query);

            $page_data ['category_set'] = $this->tours_model->category_set($query);

            $page_data['duration_set'] = $this->tours_model->duration_set($page_data['searchResult']); 
            //debug("cominmg");exit; 



           $page_data['currency_obj'] = new Currency(array('module_type' => 'Holiday','from' => get_application_default_currency(), 'to' => get_application_default_currency()));

            //$page_data['currency_obj'] = new Currency(array('module_type' => 'Holiday','from' => get_application_default_currency(), 'to' => get_application_display_currency_preference()));
            //debug($page_data['currency_obj']);die;
            $safe_search_data = $this->tours_model->get_safe_search_data($search_id);
            // debug($safe_search_data);exit;
            $page_data['tour_search_params'] = $safe_search_data['data'];
            // debug($page_data['tour_search_params']);exit;
            // $page_data['tour_search_params']['city_id'] = $city_data[0]['id'];
            
            $default_currency = get_application_default_currency();
 
            $page_data['default_currency'] = $default_currency;
            

             $tours_crs_markup = $this->private_management_model->get_markup('holiday');
             //debug($tours_crs_markup);exit;
            $page_data['tours_crs_markup']=$tours_crs_markup;
            // debug($page_data); 
            // debug($admin_markup_holidaylcrs); 
             //exit('');
            //debug($page_data);exit;
            $page_data ['countries'] = $this->Package_Model->getPackageCountries_new ();
            $page_data ['package_types'] = $this->Package_Model->getPackageTypes ();
            $this->template->view( 'holiday/result', $page_data );




        /*$currency_obj = new Currency(array('module_type' => 'hotel','from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
        // /debug($data);exit;
        if (! empty ( $data )) {
            $country = $data ['country'];
            $packagetype = $data ['package_type'];
            if ($data ['duration']) {
                $duration = explode ( '-', $data ['duration'] );
                if (count ( $duration ) > 1) {
                    $duration = "duration between " . $duration ['0'] . " AND " . $duration ['1'];
                } else {
                    $duration = "duration >" . $duration ['0'];
                }
            } else {
                $duration = $data ['duration'];
            }
            //echo 'herre'.$duration;exit;
            
            if ($data ['budget']) {
                $budget = explode ( '-', $data ['budget'] );
                //$currecny_val = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $budget['0'] ) );
                //echo $currecny_val;exit;
                if (count ( $budget ) > 1) {
                    $budget = "price between " . $budget ['0'] . " AND " . $budget ['1'];
                } else if ($budget [0]) {
                    $budget = "price >" . $budget ['0'];
                }
            } else {
                $budget = $data ['budget'];
            }
            
            $domail_list_pk = get_domain_auth_id ();
            $data['currency_obj'] = $currency_obj;
            $data ['scountry'] = $country;
            $data ['spackage_type'] = $packagetype;
            $data ['sduration'] = $data ['duration'];
            $data ['sbudget'] = $data ['budget'];
            $data ['packages'] = $this->Package_Model->search ( $country, $packagetype, $duration, $budget, $domail_list_pk, $domail_list_pk );
            $data ['caption'] = $this->Package_Model->getPageCaption ( 'tours_packages' )->row ();
            $data ['countries'] = $this->Package_Model->getPackageCountries ();
            $data ['package_types'] = $this->Package_Model->getPackageTypes ();
            //debug($data);exit;
            $this->template->view ( 'holiday/tours', $data );*/
        /*} else {
            redirect ( 'tours/all_tours' );
        }*/
    }
    
    
			function package_user_rating()
			 {  
				$rate_data=explode(',',$_POST['rate']);
				$pkg_id=$rate_data[0];
				$rating=$rate_data[1];

				$arr_data=array(
					'package_id'=> $pkg_id,
					'rating'=> $rating
				);
				$res=$this->Package_Model->add_user_rating($arr_data);
			  }


       public function all_tours(){
        $data['caption'] = $this->Package_Model->getPageCaption('tours_packages')->row();
        $data['packages'] = $this->Package_Model->getAllPackages();
        $data['countries'] = $this->Package_Model->getPackageCountries();
        $data['package_types'] = $this->Package_Model->getPackageTypes();
        if(!empty($data['packages'])){
            $this->template->view('holiday/tours', $data);
        }else{
            redirect();
        }
    }
    
    
}
