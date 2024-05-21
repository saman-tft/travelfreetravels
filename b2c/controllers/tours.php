<?php
if (! defined ( 'BASEPATH' ))
exit ( 'No direct script access allowed' );
class Tours extends CI_Controller {
	public function __construct() {
		parent::__construct ();
		$current_url = $_SERVER ['QUERY_STRING'] ? '?' . $_SERVER ['QUERY_STRING'] : '';
		$current_url = $this->config->site_url () . $this->uri->uri_string () . $current_url;
		$url = array (
				'continue' => $current_url 
		);
		$this->session->set_userdata ( $url );
		$this->helpMenuLink = "";
			$this->load->library('rewards');
		$this->load->model ( 'Help_Model' );
		$this->helpMenuLink = $this->Help_Model->fetchHelpLinks ();
		$this->load->model ( 'Package_Model' );
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

		 echo json_encode( array(
		 	    'grandtotal' =>round(($adultPrice*$adultCount)+($childprice*$childCount)+($infantprice*$infantCount)+$Markup+$gst_value+$convenience_fees+$gst_value_conv),
		        'totalprice' =>round(($adultPrice*$adultCount)+($childprice*$childCount)+($infantprice*$infantCount)+$Markup+$gst_value+$gst_value_conv)
		     ));
	}

	/**
	 * get all tours
	 */
	public function index() {
		$data ['packages'] = $this->Package_Model->getAllPackages ();
		$data ['countries'] = $this->Package_Model->getPackageCountries ();
		$data ['package_types'] = $this->Package_Model->getPackageTypes ();
		if (! empty ( $data ['packages'] )) {
			$this->template->view ( 'holiday/tours', $data );
		} else {
			redirect ();
		}
	}
	
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
		
		
//		echo "asdtester".$tour_id;
	//	debug($page_data ['tour_price'] );die;
  //  $n3=$this->db->get_where('all_nationality_country',array('name'=>$roomprice->nationality));
	//	$natprice = $n3->row();
//	$nps=explode(',',$natprice->include_countryCodes);
	//	$page_data['currency_obj'] = new Currency(array('module_type' => 'Holiday','from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
		
		$page_data['currency_obj'] = new Currency(array('module_type' => 'Holiday','from' => 'NPR', 'to' => get_application_currency_preference()));

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
	public function enquiry() {
		
		$data = $this->input->post ();
		$package_id = $data['package_id'];
	
		if (($package_id !='') && ($data['first_name']) && ($data['phone']) && ($data['email']) && ($data['place']) && ($data['message'])) {
			
			 $package = $this->tours_model->get_tour_details ( $package_id );
			
			$data ['package_name'] = $package['package_name'];
			$data ['package_duration'] = $package['duration'];
			$data ['package_type'] = $package['tour_type'];
			
			$data ['package_description'] = $package['package_description'];
			$data ['ip_address'] = $this->session->userdata ( 'ip_address' );
			$data ['status'] = '0';
			$data ['date'] = date ( 'Y-m-d H:i:s' );
			$data ['departure_date'] = date ('Y-m-d',strtotime($data ['departure_date']));
			$data ['domain_list_fk'] = get_domain_auth_id ();
			
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
			$s = $this->provab_mailer->send_mail ($eemail, 'Good Day! Your Tour Inquiry with TravelFreeTravel', $mail_template );
			}
			$status = true;
			$message = "Thank you for submitting your enquiry for this package, will get back to you soon!";
			header('content-type:application/json');
			echo json_encode(array('status' => $status, 'message' => $message));
			exit;
			
		} 
	}
	function temp_index($search_id)
	{
		$this->load->model('hotel_model');
		$safe_search_data = $this->hotel_model->get_safe_search_data($search_id);
		// Get all the hotels bookings source which are active
		$active_booking_source = $this->hotel_model->active_booking_source();
		if ($safe_search_data['status'] == true and valid_array($active_booking_source) == true) {
			$safe_search_data['data']['search_id'] = abs($search_id);
			$this->template->view('tours/search_result_page', array('hotel_search_params' => $safe_search_data['data'], 'active_booking_source' => $active_booking_source));
		} else {
			$this->template->view ( 'general/popup_redirect');
		}
	}

	public function searchold($search_id) {
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

						
			$searchResult = $this->custom_db->get_result_by_query($query);
			foreach ($searchResult as $key => $value) {
				
				$tours_itinerary_dw=$this->tours_model->get_tours_itinerary_dw($value->id);
				
				$searchResult[$key]->tours_itinerary_dw=$tours_itinerary_dw;
			}

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
			

			$page_data ['top_new_packages'] = json_decode(json_encode( $this->custom_db->get_result_by_query($query2)),1);
			$page_data ['tours_city_name']= $this->tours_model->tours_city_name();

			$theme_set = $this->tours_model->theme_set($page_data['searchResult']);

			$page_data ['theme_set']  = $theme_set;

			$page_data ['theme_name'] = $this->tours_model->theme_name($theme_set);

			$page_data ['region_set']   = $this->tours_model->region_set($query); 
			
			$page_data ['country_set']  = $this->tours_model->country_set($query);

			$page_data ['category_set'] = $this->tours_model->category_set($query);

			$page_data['duration_set'] = $this->tours_model->duration_set($page_data['searchResult']); 
			

			$page_data['currency_obj'] = new Currency(array('module_type' => 'Holiday','from' => ADMIN_BASE_CURRENCY_STATIC, 'to' => get_application_currency_preference()));

			
			$safe_search_data = $this->tours_model->get_safe_search_data($search_id);
			
			$page_data['tour_search_params'] = $safe_search_data['data'];
			
			
			$default_currency = get_application_default_currency();
 
			$page_data['default_currency'] = $default_currency;
			

			 $tours_crs_markup = $this->domain_management_model->get_markup('holiday');
			
			$page_data['tours_crs_markup']=$tours_crs_markup;
			
			$page_data ['countries'] = $this->Package_Model->getPackageCountries_new ();
			$page_data ['package_types'] = $this->Package_Model->getPackageTypes ();
			
			$offset=0;
			$record = RECORDS_RANGE_2;
			$get_url = $_SERVER['REQUEST_URI'];
			$values = parse_url($get_url);
			$host = explode('/',$values['path']);

			
			$this->template->view( 'holiday/result', $page_data );	
	}
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
					$geturlid=$host[4];
					
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
		//	debug($searchResult);die;
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
			
			$page_data['currency_obj'] = new Currency(array('module_type' => 'Holiday','from' => 'NPR', 'to' => get_application_currency_preference()));

			
			$safe_search_data = $this->tours_model->get_safe_search_data($search_id);
			
			$page_data['tour_search_params'] = $safe_search_data['data'];
			$default_currency = get_application_default_currency();
 
			$page_data['default_currency'] = $default_currency;
			

			 $tours_crs_markup = $this->domain_management_model->get_markup('holiday');
			
			$page_data['tours_crs_markup']=$tours_crs_markup;
			
			$page_data ['countries'] = $this->Package_Model->getPackageCountries_new ();
			$page_data ['package_types'] = $this->Package_Model->getPackageTypes2 ();
		//	debug($page_data ['package_types']);die;
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
         //   	 debug($page_data);die;
			$this->template->view( 'holiday/result', $page_data );
	
	}
	public function getmarkup_list(){
		$query=$this->db->query("select * from markup_list order by origin DESC");
		$result=$query->result_array();
		debug($result);
	}
	public function pre_booking($id='')
	{
	
	
	echo "BOOKING BLOCKED BY ADMIN";exit;
	    $post_data=$this->input->post();
	   
      
      	$search_id=$this->tours_model->save_search_data_booking($post_data);
	
      	
		$sim_quantity=$post_data['sim_quantity'];
		$sim_price=$post_data['sim_price'];
		$sim_total_price=$sim_quantity*$sim_price;
	
		$safe_search_data = $this->tours_model->get_search_data_new($search_id);
		
		
		
		
	//	debug($safe_search_data);die;
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
		
		
		  $sql = "SELECT *
FROM all_nationality_country
WHERE find_in_set('".$post_data['nationality']."',all_nationality_country.include_countryCodes) and module='tours'";
   $query =$this->db->query($sql);
    $natprice = $query->result();
    
			$currency_obj = new Currency(array('module_type' => 'Holiday','from' => $natprice[0]->currency, 'to' => get_application_currency_preference()));
		if($post_data['type_hotel']=='')
		{
			$post_data['type_hotel']='budget_hotel_price';
		}
		if($post_data['type_car']=='')
		{
			$post_data['type_car']='standard_car_price';
		}
		
		$price_sum  = $this->tours_model->tour_price($id);
		//	debug($price_sum);die;
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
$page_data['currency_sel']=$price_sum[0]['currency_sel'];
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
		 
		 
		 
		$convinence_fees_row = $this->private_management_model->get_convinence_fees('Holiday');

		$currency_obj = new Currency(array('module_type' => 'flight','from' => ADMIN_BASE_CURRENCY_STATIC, 'to' => get_application_currency_preference()));
		if($currency_obj->to_currency=='NPR'){
 			$get_balance = $total_price;
	 	}else{
	 		$get_balance =$total_price;
	 	}
    	$tours_crs_markup = $this->domain_management_model->get_markup('holiday');
    	$admin_markup_holidaylcrs = $this->domain_management_model->addHolidayCrsMarkup($get_balance, $tours_crs_markup,$currency_obj);
      	$min_price_1=str_replace(',','', $get_balance); 
      	$min_price=$min_price_1+$admin_markup_holidaylcrs;
 		 
    $currency_objholiday = new Currency(array('module_type' => 'Holiday','from' => ADMIN_BASE_CURRENCY_STATIC, 'to' => get_application_currency_preference()));
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

			$this->template->view('holiday/pre_booking',$page_data );	
		}
	
	
	}
	
	function pre_booking_holiday($id="")
	{
		error_reporting(E_ALL);
ini_set('display_errors', '1');
		$post_params = $this->input->post();
	//debug($post_params);die;
	//	$post_params['payment_method']="PNHB1";
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
		
				$currency_obj = new Currency ( array (
						'module_type' => 'holiday',
						'from' => get_application_currency_preference (),
						'to' => admin_base_currency () 
				));
			$convenience_fees  = $currency_obj->convenience_fees($amount,$id); //oldone 
	
			$total_passengers=$post_params['total_pax'];
		
		$currency_obj = new Currency ( array (
						'module_type' => 'holiday',
						'from' => get_application_currency_preference (),
						'to' => admin_base_currency () 
				));

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
//debug($post_params['payment_method']);die;
				switch($post_params['payment_method']) {
					case PAY_NOW :
						$this->load->model('transaction');
						$pg_currency_conversion_rate = $currency_obj->payment_gateway_currency_conversion_rate();
				//		debug($currency_obj);die;
					$amount=get_converted_currency_value ( $currency_obj->force_currency_conversion ($post_params['grand_total']))-$reward_discount-$convenience_fees;
				//	debug($amount);die;
						$this->transaction->create_payment_record($book_id, $amount, $firstname, $email, $phone, $productinfo, $convenience_fees, $post_params['promo_code_discount_val'], $pg_currency_conversion_rate,$reward_point,$reward_amount,$reward_earned);
//echo "resrs";die;
						//to insert the the tour_booking details
						$date =  date('Y');
			            $rand = rand(1,99);
			            $app_reference = $book_id;
			          
			         $currency_obj_new = new Currency(array(
			             'module_type' => 'Holiday',
			             'to' => ADMIN_BASE_CURRENCY_STATIC, 
			             'from' => ADMIN_BASE_CURRENCY_STATIC));
			             
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
									  'basic_fare'=>get_converted_currency_value ( $currency_obj_new->force_currency_conversion ( $post_params['tour_amount'])),
									  'markup'=>get_converted_currency_value ( $currency_obj_new->force_currency_conversion ( $post_params['markup'])),
									  'gst_value'=>$post_params['gst_value'],
									  'currency_code'=>get_application_currency_preference(),
									  'service_tax'=>'',
									  'discount'=>ADMIN_BASE_CURRENCY_STATIC,
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
					//redirect(base_url().'index.php/tours/process_booking/'.$book_id.'/'.$book_origin);
					redirect(base_url() . 'index.php/payment_gateway/payment/' . $book_id . '/' . $book_origin.'/fonepay');
                         			
						break;
					case PAY_AT_BANK : 
											$this->load->model('transaction');
						$pg_currency_conversion_rate = $currency_obj->payment_gateway_currency_conversion_rate();
				//		debug($currency_obj);die;
					$amount=get_converted_currency_value ( $currency_obj->force_currency_conversion ($post_params['grand_total']))-$reward_discount-$convenience_fees;
				//	debug($amount);die;
						$this->transaction->create_payment_record($book_id, $amount, $firstname, $email, $phone, $productinfo, $convenience_fees, $post_params['promo_code_discount_val'], $pg_currency_conversion_rate,$reward_point,$reward_amount,$reward_earned);
//echo "resrs";die;
						//to insert the the tour_booking details
						$date =  date('Y');
			            $rand = rand(1,99);
			            $app_reference = $book_id;
			          
			         $currency_obj_new = new Currency(array(
			             'module_type' => 'Holiday',
			             'to' => ADMIN_BASE_CURRENCY_STATIC, 
			             'from' => ADMIN_BASE_CURRENCY_STATIC));
			             
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
									  'basic_fare'=>get_converted_currency_value ( $currency_obj_new->force_currency_conversion ( $post_params['tour_amount'])),
									  'markup'=>get_converted_currency_value ( $currency_obj_new->force_currency_conversion ( $post_params['markup'])),
									  'gst_value'=>$post_params['gst_value'],
									  'currency_code'=>get_application_currency_preference(),
									  'service_tax'=>'',
									  'discount'=>ADMIN_BASE_CURRENCY_STATIC,
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
					//redirect(base_url().'index.php/tours/process_booking/'.$book_id.'/'.$book_origin);
					redirect(base_url() . 'index.php/payment_gateway/payment/' . $book_id . '/' . $book_origin.'/connect');
					break;
				}
			
		} else {
			    redirect(base_url().'index.php/tours/exception?op=Remote IO error @ Hotel Booking&notification=validation');
		}
	}
	function process_booking($book_id, $temp_book_origin,$validation_id,$r_amt){
		// if(ENVIRONMENT == 'testing')
        // {
        //      debug("blocked");exit;
        // }

		$query_for_validation  = $GLOBALS['CI']->custom_db->single_table_records("payment_gateway_details",'*',array("app_reference"=>$book_id));

		if($query_for_validation['data'][0]["payment_validation"] == $validation_id && $query_for_validation['data'][0]["amount"]==$r_amt){


		if($book_id != '' && $temp_book_origin != '' && intval($temp_book_origin) > 0){

			$page_data ['form_url'] = base_url () . 'index.php/tours/secure_booking_holiday';
			$page_data ['form_method'] = 'POST';
			$page_data ['form_params'] ['book_id'] = $book_id;
			$page_data ['form_params'] ['temp_book_origin'] = $temp_book_origin;
			$this->template->view('share/loader/booking_process_loader', $page_data);	

		}}else{
			redirect(base_url().'index.php/tours/exception?op=Invalid request&notification=validation');
		}
		
	}
	function secure_booking_holiday()
	{
		// if(ENVIRONMENT == 'testing')
        // {
        //      debug("blocked");exit;
        // }
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
		$query_for_validation  = $GLOBALS['CI']->custom_db->single_table_records("payment_gateway_details",'*',array("app_reference"=>$post_data['book_id']));
		
		if ( $query_for_validation['data'][0]['payment_validation'] == $post_data['validation_id'] && $query_for_validation['data'][0]['amount'] == $post_data['request_amount']) {
		

		$domain_balance_status = true;
		if ($domain_balance_status) {
			//lock table
			if ($temp_booking != false) {
				switch ($temp_booking['booking_source']) {
					case "qatar" :
						  $redeem_points_post=$temp_booking['book_attributes']['redeem_points_post'];
			$reward_usable=$temp_booking['book_attributes']['reward_usable'];
			$reward_earned=$temp_booking['book_attributes']['reward_earned'];
			$supplier_cur=$temp_booking['book_attributes']['currency_sel'];
			$total_price_with_rewards=$temp_booking['book_attributes']['total_price_with_rewards'];
			$reducing_amount=$temp_booking['book_attributes']['reducing_amount'];
						//Save booking based on booking status and book id
					$tour_booking_details = array(
						'payment_status'=>'paid',
						'supplier_value'=>$supplier_cur,
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
							if(isset( $temp_booking['book_attributes']['promo_code']))
					{
							$condition['promo_code'] = $temp_booking['book_attributes']['promo_code'];
								        $condition['status'] = 1;
								        $promo_code_res=$this->custom_db->single_table_records('promo_code_list', '*', $condition );
								        $promo_code=$promo_code_res['data'][0];
								$this->custom_db->update_record('promo_code_list',array('used_limit'=>$promo_code['used_limit']+1),array('origin'=>$promo_code['origin']));
								  if($promo_code['limit']==$promo_code['used_limit']+1)
								 {
								                  
								    $this->custom_db->update_record('promo_code_list',array('status'=>0),array('origin'=>$promo_code['origin']));
								 }
													}
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
		}} else {
			$ip_address = $_SERVER['REMOTE_ADDR'];
            $cond['app_reference'] = $post_data['booking_id'];
            $data['response_params'] = $ip_address;
            $this->custom_db->update_record('payment_gateway_details', $data, $cond);
			redirect(base_url().'index.php/hotel/exception?op=Remote IO error @ Insufficient&notification=validation');
		}
		
	}
	function package_user_rating() {
		$rate_data = explode ( ',', $_POST ['rate'] );
		$pkg_id = $rate_data [0];
		$rating = $rate_data [1];

		$arr_data = array (
				'package_id' => $pkg_id,
				'rating' => $rating 
		);
		$res = $this->Package_Model->add_user_rating ( $arr_data );
	}
	public function all_tours() {
		$data ['caption'] = $this->Package_Model->getPageCaption ( 'tours_packages' )->row ();
		$data ['packages'] = $this->Package_Model->getAllPackages ();
		$data ['countries'] = $this->Package_Model->getPackageCountries ();
		$data ['package_types'] = $this->Package_Model->getPackageTypes ();
		if (! empty ( $data ['packages'] )) {
			$this->template->view ( 'holiday/tours', $data );
		} else {
			redirect ();
		}
	}
}
