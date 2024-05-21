<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
require_once BASEPATH . 'libraries/Common_Api_Grind.php';
/**
 *
 * @package Provab
 * @subpackage API
 * @author Chalapathi N <chalapathi.n@provabmail.com>
 * @version V1
 */

class Transfer_crs extends Common_Api_Grind {
	private $ClientId;
	private $UserName;
	private $Password;
	private $service_url;
	private $Url;
	private $LoginType = '2'; // (For API Users, value should be ‘2’)
	private $EndUserIp = '127.0.0.1';
	private $TokenId; // Token ID that needs to be echoed back in every subsequent request
	public $master_search_data;
	public $search_hash;
	public function __construct() {
		$this->CI = &get_instance ();
		$GLOBALS ['CI']->load->library ( 'Api_Interface' );
		$GLOBALS ['CI']->load->model ( 'sightseeing_model' );
		$GLOBALS ['CI']->load->model ( 'transfer_model' );
		$GLOBALS ['CI']->load->model ( 'transfers_model' );
		$this->TokenId = $GLOBALS ['CI']->session->userdata ( 'tb_auth_token' );
		$this->set_api_credentials ();
		// if found in session then token will not be replaced
		// $this->set_authenticate_token();
	}
	private function get_header() {
		

		$sightseeing_engine_system = $this->CI->sightseeing_engine_system;
		$user_name = $this->CI->sightseeing_engine_system. '_username';
        $password = $this->CI->sightseeing_engine_system. '_password';
		$response ['UserName'] = $this->CI->$user_name;
		$response ['Password'] = $this->CI->$password;
		$response ['DomainKey'] = $this->CI->domain_key;
		$response ['system'] = $sightseeing_engine_system;
		return $response;
	}
	private function set_api_credentials() {

		$sightseeing_engine_system = $this->CI->sightseeing_engine_system;
        $this->system = $sightseeing_engine_system;
        $user_name = $this->CI->sightseeing_engine_system. '_username';
        $password = $this->CI->sightseeing_engine_system. '_password';
        $this->UserName = $this->CI->$user_name;
        $this->Password = $this->CI->$password;
        $this->Url =  $this->CI->sightseeing_url;
        $this->ClientId = $this->CI->domain_key;

		// $this->UserName = 'test';
		// $this->Password = 'password'; // miles@123 for b2b
	}
	function credentials($service) {
		switch ($service) {
			case 'Authenticate' :				
				$this->service_url = $this->Url.'Authenticate';			
				break;
			case 'CategoryList':
				$this->service_url = $this->Url.'CategoryList';
				break;
			case 'Search':
				$this->service_url = $this->Url.'Search';
				break;
			case 'ProductDetails':
				$this->service_url = $this->Url.'ProductDetails';
				break;
			case 'TripList':
				$this->service_url = $this->Url.'TripList';
				break;
			case 'BlockTrip':
				$this->service_url = $this->Url.'BlockTrip';
				break;
			case 'Book':
				$this->service_url = $this->Url.'CommitBooking';
				break;
			case 'CancelBooking':
				$this->service_url = $this->Url.'CancelBooking';
				break;
			case 'UpdateHoldBooking':
				$this->service_url = $this->Url.'UpdateHoldBooking';
				break;
			case 'CancellationRefundDetails':
				$this->service_url = $this->Url.'CancellationRefundDetails';
				break;
		}
	}

	/**
	*Elavarasi
	* Get Category List
	*/
	public function get_category_list($Request){
		$header = $this->get_header ();
		$response ['data'] = array ();
		$response ['status'] = true;
		if($Request['city_id']){
			$cate_request = $this->get_category_req($Request);
			if($cate_request['status']==SUCCESS_STATUS){
				//echo $cate_request ['data'] ['service_url'];
				$api_reponse = $GLOBALS ['CI']->api_interface->get_json_response ( $cate_request ['data'] ['service_url'], $cate_request ['data'] ['request'], $header );
				
				if(valid_array($api_reponse['CategoryList']['CategoryList']) && $api_reponse['Status']==SUCCESS_STATUS){
					$response['data'] = $api_reponse['CategoryList'];
				}else{
					$response['status'] = false;
				}
			}else{
				$response['status'] = false;
			}
		}
		return $response;
	}
	/*
	*Elavarasi
	*GEt Cateogry list request
	*/
	private function get_category_req($Request){
		//$this->set_authenticate_token ( true );
		
		$response ['status'] = true;
		$response ['data'] = array ();		
		$req_ar  = array(
			'city_id'=>$Request['city_id'],
			'category_id'=>$Request['Select_cate_id']
			);
		$this->credentials('CategoryList');
		$response['data']['request'] = json_encode($req_ar);
		$response['data']['service_url'] =$this->service_url;	
		
		return $response;
	}
	/**
	*Elavarasi
	* Format sightseeing category List 
	*/
	public function format_category_response($category_list,$selected_cate_id){
		$response['status']  =false;
		$response['data']  = array();
		if($category_list){
			
			
			$cate_check_list ='<ul id="activity-cate-wrapper" class="cateul">';
			foreach ($category_list as $key => $value) {
				if($value['product_count']>=1){
					$cate_check_list .='<li>';
					$cate_check_list .='<div class="squaredThree">';

					$cate_check_list .='<input id="locSquaredThree'.$key.'" class="activity-cate" name="check" value="'.$value['category_id'].'" type="checkbox">';

					$cate_check_list .='<label for="locSquaredThree'.$key.'"></label>';

					$cate_check_list .='</div>';

					$cate_check_list .='<label class="lbllbl" for="locSquaredThree'.$key.'">'.$value['category_name'].'</label></li>';
				}
			

			}
			$cate_check_list .='</ul>';
		  $response['status']  =true;

		}else{
			
		}
		$cate_list = array();		
		$cate_list['cate_check_list']  = trim($cate_check_list);
		$response['data'] = $cate_list;
		return $response;
		
	}
	/**
	*Elavarasi
	* Format sightseeing category List 
	*/
	public function format_category_response_may22($category_list,$selected_cate_id){
		$response['status']  =false;
		$response['data']  = array();
		if($category_list){
			$cate_option_list = '';
			$cate_check_list = '';
			$cate_option_list .='<option value=0>Select Category</option>';
				if($selected_cate_id!=0){
					$selected_cate = $selected_cate_id;
				}else{
					$selected_cate =0;
				}

			foreach ($category_list as $key => $value) {
				$selected_text = '';
				$add_hight_light_class='';
				$aria_expand=false;	
				$add_class_in='';
				if($selected_cate>0){
					if($selected_cate==$value['category_id']){
						$selected_text = 'selected';
						$add_hight_light_class ='hightlight-cate';
						$aria_expand = true;
						$add_class_in='in';
					}
				}
				$cate_option_list .='<option value='.$value['category_id'].' '.$selected_text.'>'.$value['category_name'].'</option>';

				$cate_check_list .=' <div class="panel panel-default"><a class="btn cate-btn-click " id="cate_id_'.$value['category_id'].'" data-toggle="collapse" data-cate-id='.$value['category_id'].' data-parent="#accordion" data-target="#demo_'.$key.'">'.$value['category_name'].'</a>'; 

				$cate_check_list .=' <div id="demo_'.$key.'" class="panel-collapse collapse " aria-expanded='.$aria_expand.' >';
				$cate_check_list .='<ul class="subcatlist">';

					foreach ($value['sub_categories'] as $key_1 => $value_1) {
						$cate_check_list .='<li><button type="button" class="sub-list" id="sub_cate_'.$value_1['category_id'].'_'.$value_1['sub_category_id'].'" data-value='.$value_1['sub_category_id'].' data-cate='.$value_1['category_id'].' data-subcate='.$value_1['sub_category_id'].'><i class="fa fa-check" aria-hidden="true"></i>&nbsp;'.$value_1['sub_cate_name'].'</button></li>';
					}                            
                                      
                $cate_check_list .=' </ul></div></div></div><div class="clearfix"></div>';
                $response['status']  =true;
			}

			

		}else{
			$cate_option_list .='<option value=0>No Category</option>';
		}
		$cate_list = array();
		$cate_list['cate_option_list'] = trim($cate_option_list);
		$cate_list['cate_check_list']  = trim($cate_check_list);
		$response['data'] = $cate_list;
		return $response;
		
	}

	/**
	*Elavarasi
	*Get Sightseeing result based on destination id
	*/
	public function get_transfer_list_crs($safe_search_data,$search_result)
	{
		$this->CI->load->driver ( 'cache' );
		$response['data'] = array();
		$response['status'] = false;
		$header = $this->get_header();
		$cache_search = false;		
		$search_data = $this->search_data($safe_search_data['data']);	
		// debug($search_data);exit;
		$search_hash = $this->search_hash;
		$cache_contents = '';
		if ($cache_search) {
			$cache_contents = $this->CI->cache->file->get( $search_hash );
		}

		//$cache_contents = '';
		if($safe_search_data['data']){
			if ($cache_search === false || ($cache_search === true && empty ( $cache_contents ) == true)) {
				$search_request = $this->get_transfer_list_request($safe_search_data['data']);
 
				if($search_request['status']==SUCCESS_STATUS)
				{
// 					$date = '15-12-2016';
// $nameOfDay = date('D', strtotime($date));
// echo $nameOfDay;
				 

                	$total_pax = $safe_search_data['data']['adult']+$safe_search_data['data']['child'];
					$markup_level = 'level_2';
					$admin_markup = $GLOBALS['CI']->transfer_model->get_admin_agent_markup($markup_level);
					$markup_level_agent = 'level_4';
	            	$user_agent = $GLOBALS ['CI']->entity_user_id;
					$agent_markup = $GLOBALS['CI']->transfer_model->get_admin_agent_markup($markup_level_agent,$user_agent);
					$gst_percentage = $GLOBALS['CI']->transfer_model->get_admin_gst();

					$convenience_fees = $GLOBALS['CI']->transfer_model->get_convenience_fees();
					$con_type=$convenience_fees[0]['value_type'];
					
					if(!empty($convenience_fees)){
						if($convenience_fees[0]['per_pax']!=0){
							if($convenience_fees[0]['value_type']=="percentage")
							{
$convenience_fees_amt = ($convenience_fees[0]['value']/100);
							}
							else
							{
$convenience_fees_amt = $convenience_fees[0]['value'];
							}
							
						}else{
							$convenience_fees_amt = $convenience_fees[0]['value'];
						}

					}
		//	debug($convenience_fees_amt );die;
					$weekday_name = date('D', strtotime($safe_search_data['data']['from_date']));
					if($weekday_name == 'Mon'){
						$weekday = '1';
					}
					if($weekday_name == 'Tue'){
						$weekday = '2';
					}
					if($weekday_name == 'Wed'){
						$weekday = '3';
					}
					if($weekday_name == 'Thu'){
						$weekday = '4';
					}
					if($weekday_name == 'Fri'){
						$weekday = '5';
					}
					if($weekday_name == 'Sat'){
						$weekday = '6';
					}
					if($weekday_name == 'Sun'){
						$weekday = '7';
					}
// debug($safe_search_data);exit;
					$time_start = $this->hoursToMinutes($safe_search_data['data']['depature_time_flight']);
					$safe_search_data['data']['deptur_time'] = $time_start;
					$nationality_code = $GLOBALS['CI']->transfer_model->get_country_id($safe_search_data['data']['native_country'])[0]->country_code;
					$safe_search_data['data']['nationality_code'] = $nationality_code;
					$packages = $GLOBALS['CI']->transfer_model->transfersearch($safe_search_data,$weekday);
            //	 debug($packages);exit;
            foreach ($packages as $key => $value) 
            { 
					$s=0;
				$map_timing = $GLOBALS['CI']->transfer_model->get_map_timing($value->id,$value->date_range_id);	
				// debug($map_timing);exit;
				foreach ($map_timing as $key => $timing) {
            	$time_travel = $safe_search_data['data']['deptur_time'];
            	$shift_time_from = $timing->shift_time_from;
            	$shift_time_to = $timing->shift_time_to;
            	$map_time = $timing->shift_to_time;
            	$shift_from_min = $value->shift_from_min;
            	$shift_to_min = $value->shift_to_min;
            	$price_time = $value->price_time;
            	// debug($value);debug($time_travel);exit;
            	// if($value->price_id==160){
            	
            	if(($time_travel>=$shift_time_from) && ($time_travel<$shift_time_to) && ($time_travel>=$shift_from_min) && ($time_travel<$shift_to_min)){
            		$s++;
            	}else if(($time_travel>=$shift_time_from) && ($map_time=='N') && ($time_travel>=$shift_from_min) && ($time_travel<$shift_to_min)){
            		$s++;
           		
            	}else if(($time_travel>=$shift_time_from) && ($time_travel<$shift_time_to) && ($time_travel>=$shift_from_min) && ($price_time=='N')){
            		$s++;
            	}else if(($time_travel>=$shift_time_from) && ($time_travel<1430) && ($time_travel>=$shift_from_min) && ($price_time=='N') && ($map_time=='N')){
            		$s++;
            	}else if(($time_travel>=$shift_time_from) && ($time_travel<1430) && ($time_travel>=$shift_from_min) && ($time_travel<$shift_to_min) && ($price_time=='S') && ($map_time=='N')){
            		$s++;
            	}else if(($time_travel>=$shift_time_from) && ($time_travel<$shift_time_to) && ($time_travel<1430) && ($time_travel>=$shift_from_min) && ($price_time=='N') && ($map_time=='S')){
            		$s++;
            	}else if(($time_travel<$shift_time_to) && ($time_travel<$shift_to_min ) && ($price_time=='N') && ($map_time=='N')){
            		$s++;
            	}else if(($time_travel>=$shift_time_from) && ($time_travel<$shift_time_to) && ($time_travel<$shift_to_min ) && ($price_time=='N') && ($map_time=='S')){
            		$s++;
            	}else if(($time_travel>=$shift_from_min) && ($time_travel<$shift_time_to) && ($time_travel<$shift_to_min ) && ($price_time=='S') && ($map_time=='N')){
            		$s++;
            	}else{
            	}
				if($s!=0){ break; }	
				}
// debug(1);exit;
            	if($s!=0){
	 			$from_date = trim ( date('Y-m-d',strtotime($safe_search_data['data']['from_date'])) );
	 			$now = time(); // or your date as well
				$your_date = strtotime($from_date);
				$datediff = $your_date - $now;
				$number_of_days1 = round($datediff / (60 * 60 * 24));
				$number_of_days = $number_of_days1+1;
				$agent_base_currency = agent_base_currency();
			//	debug($value);
				if($agent_base_currency==$value->currency){
					$transfer_price = $value->price;
				}else{
			
				     $currency_obj_m = new Currency(array('module_type' => 'transferv1', 'from' => get_application_currency_preference(), 'to' => get_application_currency_preference()));
							 $strCurrency_m  = $currency_obj_m->get_currency($value->price, true, false, true, false, 1);
				$transfer_price = $strCurrency_m['default_value'];
				//debug($transfer_price);exit;
				}
				
                if($admin_markup[0]['value_type']=='percentage')
                {
                    $admin_markup_price = ($transfer_price*$admin_markup[0]['value'])/100;
                }
                else if($admin_markup[0]['value_type']=='plus')
                {
                    $admin_markup_price = $admin_markup[0]['value'];
                }
                else
                {
                    $admin_markup_price = 0;
                }
                if($agent_markup[0]['value_type']=='percentage')
                {
                    $agent_markup_price = ($transfer_price*$agent_markup[0]['value'])/100;
                }
                else if($agent_markup[0]['value_type']=='plus')
                {
                    $agent_markup_price = $agent_markup[0]['value'];
                }
                else
                {
                    $agent_markup_price = 0;
                }
                // debug($agent_markup_price);exit;
                // $gst_amount = 0;
                $total_markup = $admin_markup_price+$agent_markup_price;
                $gst_amount = ($gst_percentage[0]['gst']*$total_markup)/100;
                // debug($gst_amount);exit;
                $price_with_agent_markup = $transfer_price+$admin_markup_price+$agent_markup_price;
          //     debug($convenience_fees_amt);
              if($con_type="percentage")
               {
               	$convenience_fees_amt=($price_with_agent_markup+$gst_amount)*$convenience_fees_amt;

 $net_fare = $price_with_agent_markup+$gst_amount;
 //debug(	$net_fare);die;
               }
               else
               {
 $net_fare = $price_with_agent_markup+$gst_amount;
               }
               
               // debug($net_fare);exit;
                $cancellation_policy = $GLOBALS['CI']->transfer_model->get_cancellation_policy($value->id,$from_date);
				// debug($cancellation_policy[0]);exit;
				// date('Y-m-d', strtotime('-5 days', strtotime('2008-12-02')));
				if(count($cancellation_policy)>0)
				{
					$cancellation_available = 1;
					for($i=0;$i<count($cancellation_policy);$i++)
					{
						if($number_of_days==$cancellation_policy[$i]['no_of_days'] || $number_of_days<$cancellation_policy[$i]['no_of_days'])
						{
							if($cancellation_policy[$i]['charge_type']=='%')
			                {
			                    $cancellation_amount = ($price_with_agent_markup*$cancellation_policy[$i]['amount'])/100;
			                    $cancellation_details = 'Cancelation from today will charge NPR'.$cancellation_amount;
			                }
			                else if($cancellation_policy[$i]['charge_type']=='Amount')
			                {
			                    $cancellation_amount = $cancellation_policy[$i]['amount'];
			                    $cancellation_details = 'Cancelation from today will charge NPR'.$cancellation_amount;
			                }
			                break;		
						}
						else
						{ 
							$k = count($cancellation_policy)-1;
							if($i==$k)
							{
								$startdate=date('Y-m-d', strtotime('-'.$cancellation_policy[$i]['no_of_days'].' days', strtotime($from_date)));
								if($cancellation_policy[$i]['charge_type']=='%')
				                {
				                    $cancellation_amount = ($price_with_agent_markup*$cancellation_policy[$i]['amount'])/100;
				                    $cancellation_details = 'Cancelation from '.$startdate.' will charge NPR'.$cancellation_amount;
				                }
				                else if($cancellation_policy[$i]['charge_type']=='Amount')
				                {
				                    $cancellation_amount = $cancellation_policy[$i]['amount'];
				                    $cancellation_details = 'Cancelation from '.$startdate.' will charge NPR'.$cancellation_amount;
				                }
							}
								
						}
					}
				}
				else
				{
					$cancellation_details = 'Non-Refundable';
					$cancellation_available = 0;
				}
				// debug($safe_search_data);
				// echo '<pre>';
				// debug($safe_search_data['filters']['min_price']);
				// echo '<pre>';
				if( ($safe_search_data['filters']['min_price']>$net_fare) && (!empty($safe_search_data['filters']['min_price'])))
				{
					
				}else if( ($safe_search_data['filters']['max_price']<$net_fare) && (!empty($safe_search_data['filters']['max_price'])))
				{
					
				}
				else{
					$key=$key.'crs';
                $search_result['data']['SSSearchResult']['TransferResults'][$key]['id']=$value->id;
                $search_result['data']['SSSearchResult']['TransferResults'][$key]['price_id']=$value->price_id;
                $search_result['data']['SSSearchResult']['TransferResults'][$key]['transfer_name']=$value->transfer_name;
                $search_result['data']['SSSearchResult']['TransferResults'][$key]['ImageUrl']=$GLOBALS['CI']->template->domain_upload_pckg_images($value->image);
                $search_result['data']['SSSearchResult']['TransferResults'][$key]['ImageVehicleUrl']=$GLOBALS['CI']->template->domain_upload_pckg_images($value->vehicle_image);
                $search_result['data']['SSSearchResult']['TransferResults'][$key]['source']=$value->source;
                $search_result['data']['SSSearchResult']['TransferResults'][$key]['destination']=$value->destination;
                $search_result['data']['SSSearchResult']['TransferResults'][$key]['vehicle_id']=$value->vehicle_id;
                $search_result['data']['SSSearchResult']['TransferResults'][$key]['StarRating']=$value->rating;
				$search_result['data']['SSSearchResult']['TransferResults'][$key]['exclusive_ride']=$value->exclusive_ride;
                $search_result['data']['SSSearchResult']['TransferResults'][$key]['meetup_location']=$value->meetup_location;
                $search_result['data']['SSSearchResult']['TransferResults'][$key]['general_list_info']=$value->general_list_info;
                $search_result['data']['SSSearchResult']['TransferResults'][$key]['pick_up_info']=$value->pick_up_info;
                $search_result['data']['SSSearchResult']['TransferResults'][$key]['guidelines_list']=$value->guidelines_list;
                $search_result['data']['SSSearchResult']['TransferResults'][$key]['driver_id']=$value->driver_id;
                $search_result['data']['SSSearchResult']['TransferResults'][$key]['vehicle_name']=$value->vehicle_name;
                $search_result['data']['SSSearchResult']['TransferResults'][$key]['max_passenger']=$value->max_passenger;
                $search_result['data']['SSSearchResult']['TransferResults'][$key]['display_price']=$value->display_price;
                $search_result['data']['SSSearchResult']['TransferResults'][$key]['convenience_fees']=$value->convenience_fees;
				$search_result['data']['SSSearchResult']['TransferResults'][$key]['cancellation_details']=$cancellation_details;
				$search_result['data']['SSSearchResult']['TransferResults'][$key]['Cancellation_available']=$cancellation_available;
                $search_result['data']['SSSearchResult']['TransferResults'][$key]['price_with_agent_markup']=$value->display_price;
                $search_result['data']['SSSearchResult']['TransferResults'][$key]['display_price']=$net_fare;
                $search_result['data']['SSSearchResult']['TransferResults'][$key]['TotalPax']=$total_pax;
                $search_result['data']['SSSearchResult']['TransferResults'][$key]['Price']=array(
                                            'TotalDisplayFare'=>$transfer_price,
                                            'NetFare'=>$net_fare,
                                            'GSTPrice'=>$gst_amount,
                                            'Tax'=>0,
                                            'Currency'=>admin_base_currency()
                );
                
                $search_result['data']['SSSearchResult']['TransferResults'][$key]['ResultToken']=base64_encode(json_encode($value));
                $search_result['data']['SSSearchResult']['TransferResults'][$key]['booking_source']=PROVAB_TRANSFER_SOURCE_CRS;

				}
				
				// echo $this->db->last_query();exit;
				// debug($cancellation_policy);exit;
				
				// debug(1);exit;
			}
            	

            }
					
					$search_result['Status']=1;
					$search_result['Message']='';
// debug($search_result);die;
					
					$GLOBALS['CI']->custom_db->generate_static_response(json_encode($search_response));

					if($this->valid_response($search_result['Status'])){
						if($this->valid_search_result($search_result)){
					// debug($search_result);die;

							$response['data'] =$search_result['data'];
							$response['status'] = SUCCESS_STATUS;
							
							if ($cache_search) {
								
								$cache_exp = $this->CI->config->item ( 'cache_transfer_search_ttl' );
								$this->CI->cache->file->save ( $search_hash, $response ['data'], $cache_exp );
							}
							

						}else{
							$response['Message'] = $search_result['Message'];
						}
					}else{
						$response['Message'] = $search_result['Message'];
					}
					
				}
			}else{
				//read from cache
				//echo "cache";exit;
				$response ['data'] = $cache_contents;
				$response['status'] = SUCCESS_STATUS;
			}
		}
		// debug($response);exit;
		return $response;

	}
	/*
	*Elavarasi
	* Form Sightseeing request
	*/
	public function get_transfer_list_request($safe_search_data){
		// debug($filter_data);
		// exit;
		
		$response = array();
		$request = array();
		// $request['city_id'] = $safe_search_data['destination_id'];
		if($safe_search_data['from_date']){
			$request['start_date'] = date('Y-m-d',$safe_search_data['from_date']);
		}else{
			$request['start_date'] ='';
		}
		if($safe_search_data['to_date']){
			$request['end_date'] = date('Y-m-d',$safe_search_data['to_date']);	
		}else{
			$request['end_date'] = '';
		}	
		$request['from'] = $safe_search_data['from'];
	    $request['to'] = $safe_search_data['to'];
	    $request['from_code'] = $safe_search_data['from_code'];
	    $request['to_code'] = $safe_search_data['to_code'];
	    $request['from_transfer_type'] = $safe_search_data['from_transfer_type'];
	    $request['to_transfer_type'] = $safe_search_data['to_transfer_type'];
	    $request['adult'] = $safe_search_data['adult'];
	    $request['child'] = $safe_search_data['child'];
	    $request['depature_time_flight'] = $safe_search_data['depature_time_flight']; 
	    $request['depature'] = $safe_search_data['depature'];
	    $request['adult_ages'] = $safe_search_data['adult_ages'][0];
	    $request['trip_type'] = $safe_search_data['trip_type'];
	    if($safe_search_data['trip_type']=='circle')
	    {
	    	$request['return_time_flight'] = $safe_search_data['return_time_flight'];
	    	$request['return'] = $safe_search_data['return'];
	    }
		// $request['cat_id'] = $safe_search_data['category_id'];
		//$request['sub_cat_id'] = $filter_data['sub_cate_id'];
		//$request['cat_id'] = 0;
		// $request['sub_cat_id'] = 0;
		/*if($filter_data['price_sort']){
			$request['sort_order'] = $filter_data['price_sort'];	
		}else{
			$request['sort_order'] = "TOP_SELLERS";
		}	*/	
		$request['sort_order'] = "TOP_SELLERS";

		//$request['text'] = $filter_data['tour_name'];
		$request['text'] = '';
		//$this->search_hash = md5 ( serialized_data ( $request) );
		// debug($request);
		// exit;
		$response['request'] = json_encode($request);
		$this->credentials('Search');
		$response['service_url'] = $this->service_url;
		$response['status'] = true;
		// debug($response);exit;
		return $response;

		
	}
	/**
	*Elavarasi
	*Get Product Details
	*/
	public function get_product_details($get_params,$params='',$module=''){
		// debug($get_params);exit;
error_reporting('E_ALL');
		$header = $this->get_header ();
		$response ['data'] = array ();
		$response ['status'] = false;
		$product_details_request = $this->get_product_details_req($get_params);		
		

		if ($product_details_request ['status']) {
			// get the response for hotel details
			$product_details_response = $GLOBALS ['CI']->api_interface->get_json_response ( $product_details_request ['data'] ['service_url'], $product_details_request ['data'] ['request'], $header );
			// debug($product_details_response);
			// exit;
			$GLOBALS ['CI']->custom_db->generate_static_response ( json_encode ( $product_details_response ));
			
			if ($this->valid_product_details ( $product_details_response )) {
				$response ['data'] = $product_details_response;
				$response ['status'] = true;
			} else {
				$response ['data'] = $product_details_response;
				$response['Message'] = $product_details_response['Message'];
			}
		}		

		return $response;
	}
	/*
	*Format Product details request
	*/
	private function get_product_details_req($get_params){		
		$response['status'] = false;
		$response['data'] = array();
		$request = array();
		if($get_params['product_code']&&$get_params['result_token']){
			$response['status'] = true;
			$request['ProductCode'] = $get_params['product_code'];
			$request['ResultToken'] = $get_params['result_token'];
			$response['data']['request'] = json_encode($request);
			$this->credentials('ProductDetails');
			$response['data']['service_url'] = $this->service_url;
		}
		
		return $response;
	}

	/**
	*Elavarasi
	*Get Tourgrade List
	*/
	public function get_tourgrade_list($get_params){
		$header  = $this->get_header();
		$response['status'] = FAILURE_STATUS;
		$response['data']  = array();
		if($get_params['product_code']){
			$tourgrade_request = $this->get_tourgrade_list_req($get_params);
			if($tourgrade_request['status']==SUCCESS_STATUS){
				$tougrade_response = $GLOBALS ['CI']->api_interface->get_json_response ( $tourgrade_request ['data'] ['service_url'], $tourgrade_request ['data'] ['request'], $header );
				// debug($tougrade_response);
				// exit;
				
				$GLOBALS ['CI']->custom_db->generate_static_response ( json_encode ( $tougrade_response ));
				if($this->valid_product_tourgrade($tougrade_response)){
					$response['status'] = SUCCESS_STATUS;
					$response['data'] = $tougrade_response['TripList'];
				}else{
					$response['Message'] = $tougrade_response['Message'];
				}
			}else{
				$response['Message'] = "Invalid Trip Request";
			}
		}

		return $response;
	}
	/*Get Tourgrade request*/
	private function get_tourgrade_list_req($product_list){
		$response['status'] = true;
		$request = array();

		//debug($product_list);
		// exit;
		$request['ProductCode'] = $product_list['product_code'];
		if(!empty($product_list['get_year'])&&!empty($product_list['get_month'])&&!empty($product_list['get_date'])){

			//echo "dfsdf";
			$date = $product_list['get_year'].'-'.$product_list['get_month'].'-'.$product_list['get_date'];
		}else{
			//echo "els";
			/*$split_string = explode('-',$product_list['select_year']);
			$year = $split_string[0];
			$month = $split_string[1];
			$day = $product_list['select_month'];
			$date = $year.'-'.$month.'-'.$day;*/
		}		
		$request['BookingDate'] = trim($date);
		
		$age_band_details_arr = array('Adult','Youth','Senior','Child','Infant');
		
		$agen_band = array();
		$i=0;
		foreach ($age_band_details_arr as $key => $value) {			
			if(isset($product_list[$value.'_Band_ID'])&&isset($product_list['no_of_'.$value])){
				$agen_band[$i]['bandId'] = 	$product_list[$value.'_Band_ID'];
				$agen_band[$i]['count'] = $product_list['no_of_'.$value];
				$i++;
			}
		}
		$request['ResultToken'] = $product_list['ResultToken'];		
		$request['ageBands'] =$agen_band;
		$this->credentials('TripList');	

		// debug($request);
		// exit;
		$response['data']['request'] = json_encode($request);
		$response['data']['service_url'] =$this->service_url; 
		
		return $response;
	}

	/**
	 * Elavarasi
	 * Block Trip Before Going for payment and showing final booking page to user - Viator Rule
	 * 
	 * @param array $pre_booking_params
	 *        	All the necessary data required in block trip request - fetched from triplist and tourgrade  Request
	 */
	function block_trip($pre_booking_params) {

		$header = $this->get_header ();
		$response ['status'] = false;
		$response ['data'] = array ();
		//$search_data = $this->search_data ( $pre_booking_params ['search_id'] );
		$run_block_trip_request = true;
		$block_room_request_count = 0;
		//$pre_booking_params ['search_data'] = $search_data ['data'];
		$block_trip_request = $this->get_block_trip_request ( $pre_booking_params );
		//debug($pre_booking_params);
		$application_default_currency = admin_base_currency ();

		if ($block_trip_request ['status'] == ACTIVE) {
			//while ( $run_block_trip_request ) {

				$GLOBALS ['CI']->custom_db->generate_static_response ( json_encode ( $block_trip_request ['data'] ['request'] ) );
				$block_trip_response = $GLOBALS ['CI']->api_interface->get_json_response ( $block_trip_request ['data'] ['service_url'], $block_trip_request ['data'] ['request'], $header );

				$GLOBALS ['CI']->custom_db->generate_static_response ( json_encode ( $block_trip_response ) );
				

				if ($this->valid_response ( $block_trip_response ['Status']) == false) {
					$run_block_trip_request = false;
					$response ['status'] = false; // Indication for room block
					if(empty($block_trip_response['Message'])==false){
						$response ['data'] ['msg'] = $block_trip_response['Message'];
					}else{
						$response ['data'] ['msg'] = 'Some Problem Occured. Please Search Again to continue';
					}
					
				} else {

					$response ['status'] = SUCCESS_STATUS;
					$block_trip_response = $block_trip_response['BlockTrip'];
					
					$block_request =json_decode($block_trip_request ['data'] ['request'],true);
					$block_trip_response['AgeBands'] = $block_request['ageBands'];
				}
				
			//}
			
			$response ['data'] ['BlockTrip'] = $block_trip_response;
			
		}
		
		return $response;
	}
		
	/**
	 * Elavarasi
	 *
	 * get trip block request
	 * 
	 * @param array $booking_parameters        	
	 */
	private function get_block_trip_request($booking_params) {
		$age_band = json_decode(base64_decode($booking_params['age_band']),true);	
		$response ['status'] = true;
		$response ['data'] = array ();
		$request = array();
		$request['ProductCode'] = $booking_params['product_code'];
		$request['GradeCode'] = $booking_params['grade_code'];
		$request['BookingDate'] = $booking_params['booking_date'];
		$request['ResultToken'] = $booking_params['tour_uniq_id'];
		$request['ageBands'] = $age_band;
		$response ['data'] ['request'] = json_encode ( $request );
		$this->credentials ( 'BlockTrip' );
		$response ['data'] ['service_url'] = $this->service_url;
		return $response;
	}


	/**

	 * Balu A
	 *
	 * This will get the "TokenId" and refresh token id
	 * Keeping static as this should work for all the objects
	 * 
	 * @param boolean $override_token
	 *        	to decide if the token has to be overriden in case if token has to be refreshed
	 */
	public function set_authenticate_token($override_token = false) {
		$header = $this->get_header ();
		if (empty ( $this->TokenId ) == true || $override_token == true) {
			$this->credentials ( 'Authenticate' );
			$service_url = $this->service_url;
			$request ['ClientId'] = $this->ClientId;
			$request ['UserName'] = $this->UserName;
			$request ['Password'] = $this->Password;
			$request ['LoginType'] = $this->LoginType;
			$request ['EndUserIp'] = $this->EndUserIp;
			$GLOBALS ['CI']->custom_db->generate_static_response ( json_encode ( $request ) );
			$response = $GLOBALS ['CI']->api_interface->get_json_response ( $service_url, json_encode ( $request ), $header );
			
			$GLOBALS ['CI']->custom_db->generate_static_response ( json_encode ( $response ) );
			if (valid_array ( $response ) == true && empty ( $response ['Status'] ) == false && $response ['Status'] == ACTIVE) {
				// validate response and create session
				$authenticate_token = $response ['TokenId'];
				$GLOBALS ['CI']->session->set_userdata ( array (
						'tb_auth_token' => $authenticate_token 
				) );
				$this->TokenId = $authenticate_token;
			} else {
				// FIXME : handle all the failure conditions
				// redirect(base_url());
			}
		}
	}
	
	/**
	 * Balu A
	 *
	 * TBO auth token will be returned
	 */
	public function get_authenticate_token() {
		return $GLOBALS ['CI']->session->userdata ( 'tb_auth_token' );
	}
	
	
	/**
	 * Elavarasi
	 * Form Book Request
	 */
	function get_book_request($booking_params, $booking_id) {
		
		
		$response =array();
		$request = array();
		$request['AppReference'] = trim($booking_id);
		$request['BlockTourId'] = trim($booking_params['BlockTourId']);
		$passenger_details =array();
		
		foreach ($booking_params['passenger_type'] as $key => $value) {
			$passenger_details[$key]['Title'] = $booking_params['name_title'][$key];
			$passenger_details[$key]['FirstName'] = $booking_params['first_name'][$key];
			$passenger_details[$key]['LastName'] = $booking_params['last_name'][$key];
			$passenger_details[$key]['Phoneno'] = $booking_params['passenger_contact'];
			$passenger_details[$key]['Email'] = $booking_params['billing_email'];
			$passenger_details[$key]['PaxType'] = $booking_params['passenger_type'][$key];
			if($key==0){
				$passenger_details[$key]['LeadPassenger'] = 1;	
			}else{
				$passenger_details[$key]['LeadPassenger'] = 0;
			}
		}

		$request['PassengerDetails'] = $passenger_details;
		$bookingQuestions = array();
		
		//debug($booking_params['question_Id']);
		//debug($booking_params['question']);
		// debug($booking_params);
		// exit;
		
		if(isset($booking_params['weight']) || isset($booking_params['height'])){
			if($booking_params['weight'] || $booking_params['height']){

				$i = 0;
				foreach ($booking_params['pax_question'] as $q_key => $q_value) {
					
					if(is_array($q_value)){
						foreach ($q_value as $key => $value) {
							$bookingQuestions[$i]['id'] = $q_key;
							if($q_key==2){
								$bookingQuestions[$i]['answer'] = $value." ".@$booking_params['height'];
							}elseif ($q_key==23) {
								$bookingQuestions[$i]['answer'] = $value." ".@$booking_params['weight'];
							}else{
								$bookingQuestions[$i]['answer'] = $value;
							}
							
							$i++;
						}
					}

					//$bookingQuestions[$q_key]['id'] = $q_value;
					//$bookingQuestions[$q_key]['answer'] = $booking_params['question'][0][$q_key]." ".$booking_params['weight'];
					
				}
			}
		}else{
			#debug($booking_params['pax_question']);
			$paxQuestion = array();
			if(isset($booking_params['pax_question'])){
				$i =0;
				if($booking_params['pax_question']){
					 foreach ($booking_params['pax_question'] as $d_key => $d_value) {
					 	
					 	foreach ($d_value as $ex_key => $ex_value) {
					 		$paxQuestion[$i]['id'] = $d_key;
					 		$paxQuestion[$i]['answer'] = $ex_value;
					 		$i++;
					 	}
					 }
				}
			}
			
			if(isset($booking_params['question_Id'])){
				if($booking_params['question_Id']){
					foreach ($booking_params['question_Id'] as $q_key => $q_value) {
						//debug($q_value);
						$bookingQuestions[$q_key]['id'] = $q_value[0];
						$bookingQuestions[$q_key]['answer'] = $booking_params['question'][$q_key][0];
					}
				}
			}
			$bookingQuestions = array_merge($bookingQuestions,$paxQuestion);
		}		
		
		
		$ProductDetails=array();
		$ProductDetails['ProductCode'] = $booking_params['token']['product_code'];
		$ProductDetails['BookingDate'] = $booking_params['token']['booking_date'];
		$ProductDetails['GradeCode'] = $booking_params['token']['grade_code'];
		if($booking_params['token']['HotelPickup']){

			if($booking_params['hotelPickupId']=='notListed'){
				$ProductDetails['pickupPoint'] = $booking_params['hotelPickup_name'];
			}else{
				$ProductDetails['pickupPoint'] = $booking_params['hotel_pickup_list_name'];
			}
			$ProductDetails['hotelId'] = $booking_params['hotelPickupId'];
			
		}else{
			$ProductDetails['hotelId'] =''; 	
			$ProductDetails['pickupPoint'] = '';
		}
		$request['ProductDetails'] = $ProductDetails;
		$request['BookingQuestions'] = $bookingQuestions;
		// debug($request);
		// exit;
		$response ['data'] ['request'] = json_encode ( $request );
		$this->credentials ( 'Book' );
		$response['data']['status'] = true;
		$response ['data'] ['service_url'] = $this->service_url;

		return $response;
	}
	/**
	 * Elavarasi
	 * Cancellation Request
	 */
	private function cancel_booking_request_params($app_reference,$cancel_code,$cancel_description) {
		$response['status'] = true;
		$response['data'] = array ();
		$request['AppReference'] = trim ( $app_reference );	
		$request['CancelCode'] = $cancel_code;
		$request['CancelDescription'] = $cancel_description;
		$response['data']['request'] = json_encode ( $request );
		$this->credentials('CancelBooking');
		$response['data']['service_url'] = $this->service_url;

		return $response;
	}
	/**
	 * Jagnath
	 * Cancellation Refund Details
	 */
	private function cancellation_refund_request_params($ChangeRequestId, $app_reference) {
		$response ['status'] = true;
		$response ['data'] = array ();
		$request ['AppReference'] = trim ( $app_reference );
		$request ['ChangeRequestId'] = $ChangeRequestId;
		$response ['data'] ['request'] = json_encode ( $request );
		$this->credentials ( 'CancellationRefundDetails' );
		$response ['data'] ['service_url'] = $this->service_url;
		return $response;
	}
	
	/**
	 * Converts API data currency to preferred currency
	 * Elavarasi
	 * 
	 * @param unknown_type $search_result        	
	 * @param unknown_type $currency_obj        	
	 */
	public function search_data_in_preferred_currency($search_result, $currency_obj,$module='b2c') {
		$currency = agent_base_currency();
		$transfer = $search_result ['data'] ['SSSearchResult'] ['TransferResults'];
		$transfer_list = array ();
		foreach ( $transfer as $hk => $hv ) {
			$transfer_list [$hk] = $hv;
			$transfer_list [$hk] ['Price'] = $this->module_preferred_currency_fare_object ( $hv ['Price'], $currency_obj,$currency,$module );
		}
		$search_result ['data'] ['TransferResults'] ['PreferredCurrency'] = get_application_currency_preference ();
		$search_result ['data'] ['SSSearchResult'] ['TransferResults'] = $transfer_list;
		return $search_result;
	}

	/*
	*Elavarasi
	* Details data in preffered currency
	*/
	public function details_data_in_preffered_currency($fare_details,$currency_obj,$module='b2c'){

		return $this->module_preferred_currency_fare_object($fare_details,$currency_obj,'',$module);
	}
	/**
	 * Elavarasi
	 * 
	 * @param unknown_type $fare_details        	
	 * @param unknown_type $currency_obj        	
	 */
	private function preferred_currency_fare_object($fare_details, $currency_obj, $default_currency = '',$module='b2c') {
		$price_details = array ();
		$admin_commission = 0;
		$admin_tdson_commission = 0;
		$agent_commission = 0;
		$agent_tdson_commission = 0;
		$org_commission = 0;
		$or_tdson_commission = 0;
		$admin_profit = 0;
		$show_net_fare = 0;
		
		if(isset($fare_details['PriceBreakup'])){
			$admin_commission = $fare_details['PriceBreakup']['AgentCommission'];
			$agent_tds =$fare_details['PriceBreakup']['AgentTdsOnCommision'];
		}else{
			$admin_commission = $fare_details['AgentCommission'];
			$agent_tds =$fare_details['AgentTdsOnCommision'];
		}
		if($module=='b2c'){
			$net_fare = ($fare_details['TotalDisplayFare']-$admin_commission+$agent_tds);			
			$admin_commission = $admin_commission;
			$admin_tdson_commission =$agent_tds;
			
		}else{
			//for b2b users
			//Updating Commission
			$this->get_commission($fare_details, $currency_obj);
			
			$agent_commission = $fare_details['AgentCommission'];
			$agent_tdson_commission = $fare_details['TdsOnCommission'];

			$admin_profit = $fare_details['ORG_AdminCommission'];

			$show_net_fare = $fare_details['NetFare'];
			$net_fare =$fare_details['TotalDisplayFare'];
			$admin_commission = $admin_commission;
			$admin_tdson_commission = $agent_tds;
		}
	
		$price_details ['Currency'] = $fare_details['Currency'];

		$price_details ['TotalDisplayFare'] = $net_fare;

		$price_details['AdminCommission'] =  $admin_commission;

		$price_details['AdminTdsonCommission'] = $admin_tdson_commission;

		$price_details['AgentCommission'] = $agent_commission;

		$price_details['AgentTdsOnCommision'] = $agent_tdson_commission;
		$price_details['AdminCommProfit'] = $admin_profit;
		$price_details ['NetFare'] = $show_net_fare;


		//$price_details ['GSTPrice'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['GSTPrice'] ) );
		return $price_details;
	}

	/**
	 * Elavarasi
	 * 
	 * @param unknown_type $fare_details        	
	 * @param unknown_type $currency_obj        	
	 */
	private function blockgrade_preferred_currency_fare_object($fare_details, $currency_obj, $default_currency = '',$module='b2c') {
		$price_details = array ();
		$admin_commission = 0;
		$admin_tdson_commission = 0;
		$agent_commission = 0;
		$agent_tdson_commission = 0;
		$org_commission = 0;
		$or_tdson_commission = 0;
		$admin_profit = 0;
		$show_net_fare = 0;
		
		
		if(isset($fare_details['PriceBreakup'])){
			$admin_commission = $fare_details['PriceBreakup']['AgentCommission'];
			$agent_tds =$fare_details['PriceBreakup']['AgentTdsOnCommision'];
		}else{
			$admin_commission = $fare_details['AgentCommission'];
			$agent_tds =$fare_details['AgentTdsOnCommision'];
		}
		if($module=='b2c'){
			$net_fare = ($fare_details['TotalDisplayFare']-$admin_commission+$agent_tds);			
			$admin_commission = $admin_commission;
			$admin_tdson_commission =$agent_tds;
			
		}else{
			//for b2b users
			//Updating Commission
			$this->get_commission($fare_details, $currency_obj);
			
			$agent_commission = $fare_details['AgentCommission'];
			$agent_tdson_commission = $fare_details['TdsOnCommission'];

			$admin_profit = $fare_details['ORG_AdminCommission'];

			$show_net_fare = $fare_details['NetFare'];
			$net_fare =$fare_details['TotalDisplayFare'];
			$admin_commission = $admin_commission;
			$admin_tdson_commission = $agent_tds;
		}
	
		$price_details ['Currency'] = empty ( $default_currency ) == false ? $default_currency : get_application_currency_preference ();

		$price_details ['TotalDisplayFare'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $net_fare) );
		$price_details['AdminCommission'] =  	get_converted_currency_value ( $currency_obj->force_currency_conversion ( $admin_commission) );
		$price_details['AdminTdsonCommission'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $admin_tdson_commission) );
		$price_details['AgentCommission'] =get_converted_currency_value ( $currency_obj->force_currency_conversion ( $agent_commission) );
		$price_details['AgentTdsOnCommision'] =get_converted_currency_value ( $currency_obj->force_currency_conversion ( $agent_tdson_commission) );
		$price_details['AdminCommProfit'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $admin_profit) );

		$price_details ['NetFare'] = 	get_converted_currency_value ( $currency_obj->force_currency_conversion ( $show_net_fare) );

		// debug($price_details);
		// exit;
		//$price_details ['GSTPrice'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['GSTPrice'] ) );
		return $price_details;
	}

		/**
	 * Elavarasi
	 * 
	 * @param unknown_type $fare_details        	
	 * @param unknown_type $currency_obj        	
	 */
	private function b2c_raw_api_price_calculation($fare_details,$default_currency = '',$module='b2c') {

		
		$admin_commission = 0;
		$admin_tdson_commission = 0;
		$agent_commission = 0;
		$agent_tdson_commission = 0;
		$org_commission = 0;
		$or_tdson_commission = 0;
		$admin_profit = 0;
		$show_net_fare = 0;
		if(isset($fare_details['PriceBreakup'])){
			$admin_commission = $fare_details['PriceBreakup']['AgentCommission'];
			$admin_tds =$fare_details['PriceBreakup']['AgentTdsOnCommision'];
		}else{
			$admin_commission = $fare_details['AgentCommission'];
			$admin_tds =$fare_details['AgentTdsOnCommision'];
		}
		if($module=='b2c'){
			$net_fare = ($fare_details['TotalDisplayFare']-$admin_commission+$admin_tds);			
			$agent_commission = $admin_commission;
			$agent_tdson_commission =$admin_tds;
			
		}else{
			//for b2b users			
			$agent_commission = $fare_details['PriceBreakup']['AgentCommission'];
			$agent_tdson_commission = $fare_details['PriceBreakup']['AgentTdsOnCommision'];			
			$net_fare =$fare_details['TotalDisplayFare'];			
		}		
		$price_details = array ();
		$price_details ['Currency'] = empty ( $default_currency ) == false ? $default_currency : get_application_currency_preference ();
		$price_details ['TotalDisplayFare'] = $net_fare;		
		$price_details['AgentCommission'] =$agent_commission;
		$price_details['AgentTdsOnCommision'] =$agent_tdson_commission;	
		if($module=='b2c'){
			$show_net_fare = $price_details ['TotalDisplayFare'];
		}else{
			$show_net_fare = ($price_details ['TotalDisplayFare']-$price_details['AgentCommission']);	
		}		
		$price_details ['NetFare'] =$show_net_fare;	
		return $price_details;
	}

	/**
	 * Elavarasi
	 * 
	 * @param unknown_type $fare_details        	
	 * @param unknown_type $currency_obj        	
	 */
	private function module_preferred_currency_fare_object($fare_details, $currency_obj, $default_currency = '',$module='b2c') {		
		$admin_commission = 0;
		$admin_tdson_commission = 0;
		$agent_commission = 0;
		$agent_tdson_commission = 0;
		$org_commission = 0;
		$or_tdson_commission = 0;
		$admin_profit = 0;
		$show_net_fare = 0;
		if(isset($fare_details['PriceBreakup'])){
			$admin_commission = $fare_details['PriceBreakup']['AgentCommission'];
			$admin_tds =$fare_details['PriceBreakup']['AgentTdsOnCommision'];
		}else{
			$admin_commission = $fare_details['AgentCommission'];
			$admin_tds =$fare_details['AgentTdsOnCommision'];
		}
		if($module=='b2c'){

			$net_fare = ($fare_details['TotalDisplayFare']-$admin_commission+$admin_tds);			
			$agent_commission = $admin_commission;
			$agent_tdson_commission =$admin_tds;
			
		}else{
			//for b2b users
			//Updating Commission			
			// debug($fare_details);
			// exit;
			$agent_commission = $fare_details['PriceBreakup']['AgentCommission'];
			$agent_tdson_commission = $fare_details['PriceBreakup']['AgentTdsOnCommision'];			
			$net_fare =$fare_details['TotalDisplayFare'];
			#$admin_commission = $admin_commission;
			#$admin_tdson_commission = $agent_tds;
		}
		
		$price_details = array ();
		// $price_details ['Currency'] = 'INR';
		// debug($default_currency );exit;
		$price_details ['Currency'] = empty ( $default_currency ) == false ? $default_currency : get_application_currency_preference ();

		$price_details ['TotalDisplayFare'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $net_fare) );

		$price_details['AgentCommission'] =get_converted_currency_value ( $currency_obj->force_currency_conversion ( $agent_commission) );

		$price_details['AgentTdsOnCommision'] =get_converted_currency_value ( $currency_obj->force_currency_conversion ( $agent_tdson_commission) );
		//$price_details['AdminCommProfit'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $admin_profit) );
		if($module=='b2c'){
			$show_net_fare = $price_details ['TotalDisplayFare'];
		}else{
			$show_net_fare = ($price_details ['TotalDisplayFare']-$price_details['AgentCommission']);	
		}		
		$price_details ['NetFare'] = $show_net_fare;	
		return $price_details;
	}
	function update_commission_markup_module_wise($Price,$currency_obj){
		$api_price_details = $Price;
		
		#debug($Price);
		$this->get_commission($Price,$currency_obj);
		#echo "commm";
		#debug($Price);
		$admin_price_details = $this->update_booking_markup_currency($Price,$currency_obj,1,true,false);
		#echo "*******admin*******";
		#debug($admin_price_details);

		$agent_price_details = $this->update_booking_markup_currency($Price,$currency_obj,1,true,true);
		#echo "******agent******";
		#debug($agent_price_details);
		$Markup_Price = $this->b2b_price_details($api_price_details,$admin_price_details,$agent_price_details,$currency_obj);
		#echo "*******Final*****";
		#debug($Markup_Price);
		#exit;
		return $Markup_Price;
	}
    /**
     * Get admin Commission details
     */
    function get_commission(&$fare_details, & $currency_obj) {

    	
        $this->commission = $currency_obj->get_commission();      
        

        if (valid_array($this->commission) == true && intval($this->commission['admin_commission_list']['value']) > 0) {
            //update commission
            //$bus_row = array(); Preserving Row data before calculation
            $core_agent_commision = ($fare_details['TotalDisplayFare'] - $fare_details['NetFare']);

            $com = $this->calculate_commission($core_agent_commision);
           
            $this->set_b2b_comm_tag($fare_details, $com, $currency_obj);
        } else {
            //update commission
            $this->set_b2b_comm_tag($fare_details, 0, $currency_obj);
        }
    }

    /**
     * Add custom commission tag for b2b only
     * @param array		s$v
     * @param number	$b2b_com
     */
    function set_b2b_comm_tag(& $v, $b2b_com = 0, $currency_obj) {

    	
        $v['ORG_AgentCommission'] = $v['AgentCommission'];
        $v['ORG_TdsOnCommission'] = $v['AgentTdsOnCommision'];
        $v['ORG_NetFare'] = $v['NetFare'];

        //$admin_com = $v['AgentCommission'] - $b2b_com;
        $core_agent_commision = ($v['TotalDisplayFare']-$v['NetFare']);
        $admin_com = $core_agent_commision - $b2b_com;
      	$v['ORG_AdminCommission'] =$admin_com;
        //$v['OfferedFare'] = $v['TotalDisplayFare'] + $admin_com;
        $v['AgentCommission'] = $b2b_com;
        $v['AgentTdsOnCommision'] = $currency_obj->calculate_tds($b2b_com);
        $v['NetFare'] = $v['NetFare'] + $admin_com;

    }
     /**
     *
     * @param array $api_price_details
     * @param array $admin_price_details
     * @param array $agent_price_details
     * @return number
     */
      function b2b_price_details_search($api_price_details, $admin_price_details, $agent_price_details, $currency_obj, $total_pax=1) {

    	
    	$convenience_fees = $GLOBALS['CI']->transfer_model->get_convenience_fees();
					if(!empty($convenience_fees)){
						if($convenience_fees[0]['per_pax']!=0){
							if($convenience_fees[0]['value_type']=="percentage")
							{
$convenience_fees_amt = ($convenience_fees[0]['value']/100);
							}
							else
							{
$convenience_fees_amt = $convenience_fees[0]['value'];
							}
						}else{
							$convenience_fees_amt = $convenience_fees[0]['value'];
						}

					}

        #$total_price['BaseFare'] = $api_price_details['BaseFare'];
        $total_price['_CustomerBuying'] = $agent_price_details['TotalDisplayFare'];
        $total_price['TotalDisplayFare'] =  $total_price['_CustomerBuying'] ;
        $total_price['_Commission'] = round($agent_price_details['TotalDisplayFare'] - $agent_price_details['NetFare'], 3);
        $total_price['_tdsCommission'] = $currency_obj->calculate_tds($total_price['_Commission']); //Includes TDS ON PLB AND COMMISSION


        $_AgentBuying = $admin_price_details['NetFare'];

        $total_price['NetFare'] = $_AgentBuying;

        $total_price['_AdminBuying'] = $api_price_details['NetFare'];
 $currency_obj_marky =new Currency(array('module_type' => 'transfers', 'from' =>'NPR', 'to' => get_application_currency_preference()));
        $total_price['_AgentMarkup'] = $total_price['_Markup'] = $agent_price_details['_Markup'];
        $total_price['_AdminMarkup'] = round(get_converted_currency_value ( $currency_obj_marky->force_currency_conversion ($admin_price_details['_Markup'])));
        $total_price['_OrgAdminMarkup'] =round(get_converted_currency_value ( $currency_obj_marky->force_currency_conversion ($admin_price_details['_Markup'])));
        $total_markup = round(get_converted_currency_value ( $currency_obj_marky->force_currency_conversion ($admin_price_details['_Markup'])))+ $agent_price_details['_Markup']+$agent_price_details['_SearchMarkup'];
        
        // echo $total_markup ;exit;
        $gst_value = 0;
        if($total_markup > 0 ){
            // $gst_details = $GLOBALS['CI']->custom_db->single_table_records('gst_master', '*', array('module' => 'transfers'));
            $gst_details = $GLOBALS['CI']->transfer_model->get_admin_gst();
            // debug($gst_details);exit;
            if(!empty($gst_details)){
                if($gst_details[0]['gst'] > 0){
                    $gst_value = ($total_markup/100) * $gst_details[0]['gst'];
                    $gst_value  = roundoff_number($gst_value);
                }
            }
        }

        $total_price['_AgentEarning'] = $total_price['_Commission'] + $total_price['_Markup'] - $total_price['_tdsCommission'];

        $total_price['_TaxSum'] = 0;

        $total_price['_AgentBuying'] = $admin_price_details['NetFare']+$total_price['_tdsCommission']+$gst_value;
        $total_price['_TotalPayable'] = $total_price['_AgentBuying'];
        $total_price['_CustomerBuying'] = $agent_price_details['TotalDisplayFare']+$gst_value;
        if($convenience_fees[0]['value_type']=="percentage")
							{
$convenience_fees_amt = ($agent_price_details['ORG_NetFare']+$total_markup)*$convenience_fees_amt;
							}
							
        $total_price['TotalDisplayFare'] = $agent_price_details['ORG_NetFare']+$total_markup+$gst_value;
        $total_price['_GST'] = $gst_value;
        $total_price['_SearchMarkup'] = $agent_price_details['_SearchMarkup'];
        $total_price['_Convenience_fees'] = $convenience_fees_amt;

  //     debug($total_price);die;

        return $total_price;
    }
    function b2b_price_details($api_price_details, $admin_price_details, $agent_price_details, $currency_obj, $total_pax=1) {

    	
    	$convenience_fees = $GLOBALS['CI']->transfer_model->get_convenience_fees();
					if(!empty($convenience_fees)){
						if($convenience_fees[0]['per_pax']!=0){
							if($convenience_fees[0]['value_type']=="percentage")
							{
$convenience_fees_amt = ($convenience_fees[0]['value']/100);
							}
							else
							{
$convenience_fees_amt = $convenience_fees[0]['value'];
							}
						}else{
							$convenience_fees_amt = $convenience_fees[0]['value'];
						}

					}

        #$total_price['BaseFare'] = $api_price_details['BaseFare'];
        $total_price['_CustomerBuying'] = $agent_price_details['TotalDisplayFare'];
        $total_price['TotalDisplayFare'] =  $total_price['_CustomerBuying'] ;
        $total_price['_Commission'] = round($agent_price_details['TotalDisplayFare'] - $agent_price_details['NetFare'], 3);
        $total_price['_tdsCommission'] = $currency_obj->calculate_tds($total_price['_Commission']); //Includes TDS ON PLB AND COMMISSION


        $_AgentBuying = $admin_price_details['NetFare'];

        $total_price['NetFare'] = $_AgentBuying;

        $total_price['_AdminBuying'] = $api_price_details['NetFare'];

     $currency_obj_marky =new Currency(array('module_type' => 'transfers', 'from' =>'NPR', 'to' => get_application_currency_preference()));
        $total_price['_AgentMarkup'] = $total_price['_Markup'] = $agent_price_details['_Markup'];
        $total_price['_AdminMarkup'] = round(get_converted_currency_value ( $currency_obj_marky->force_currency_conversion ($admin_price_details['_Markup'])));
        $total_price['_OrgAdminMarkup'] =round(get_converted_currency_value ( $currency_obj_marky->force_currency_conversion ($admin_price_details['_Markup'])));
        $total_markup = round(get_converted_currency_value ( $currency_obj_marky->force_currency_conversion ($admin_price_details['_Markup'])))+ $agent_price_details['_Markup']+$agent_price_details['_SearchMarkup'];
        // echo $total_markup ;exit;
        $gst_value = 0;
        if($total_markup > 0 ){
            // $gst_details = $GLOBALS['CI']->custom_db->single_table_records('gst_master', '*', array('module' => 'transfers'));
            $gst_details = $GLOBALS['CI']->transfer_model->get_admin_gst();
            // debug($gst_details);exit;
            if(!empty($gst_details)){
                if($gst_details[0]['gst'] > 0){
                    $gst_value = ($total_markup/100) * $gst_details[0]['gst'];
                    $gst_value  = roundoff_number($gst_value);
                }
            }
        }

        $total_price['_AgentEarning'] = $total_price['_Commission'] + $total_price['_Markup'] - $total_price['_tdsCommission'];

        $total_price['_TaxSum'] = 0;

        $total_price['_AgentBuying'] = $admin_price_details['NetFare']+$total_price['_tdsCommission']+$gst_value;
        $total_price['_TotalPayable'] = $total_price['_AgentBuying'];
        $total_price['_CustomerBuying'] = $agent_price_details['TotalDisplayFare']+$gst_value;
        if($convenience_fees[0]['value_type']=="percentage")
							{
$convenience_fees_amt = ($agent_price_details['ORG_NetFare']+$total_markup)*$convenience_fees_amt;
							}
							
        $total_price['TotalDisplayFare'] = $agent_price_details['ORG_NetFare']+$total_markup+$gst_value+$convenience_fees_amt;
        $total_price['_GST'] = $gst_value;
        $total_price['_SearchMarkup'] = $agent_price_details['_SearchMarkup'];
        $total_price['_Convenience_fees'] = $convenience_fees_amt;

        // debug($total_price);
        // exit;
        return $total_price;
    }
    /**
     *Calculate commission
     */
    private function calculate_commission($agent_com) {
        $agent_com_row = $this->commission['admin_commission_list'];
        $b2b_comm = 0;
        if ($agent_com_row['value_type'] == 'percentage') {
            //%
            $b2b_comm = ($agent_com / 100) * $agent_com_row['value'];
        } else {
            //plus
            $b2b_comm = ($agent_com - $agent_com_row['value']);
        }       
        return roundoff_price($b2b_comm);
    }

	/**
	 * Elavarasi
	 * Converts Display currency to application currency
	 * 
	 * @param unknown_type $fare_details        	
	 * @param unknown_type $currency_obj        	
	 * @param unknown_type $module        	
	 */
	public function convert_token_to_application_currency($token, $currency_obj, $module) {
		$master_token = array ();
		

		$price_summary = array ();
		$markup_price_summary = array ();
		
		$Price = $this->convert_api_preferred_currency_fare_object($token['Price'],$currency_obj,admin_base_currency(),$module);
		
		
		$API_Price = $this->convert_api_preferred_currency_fare_object($token['API_Price'],$currency_obj,admin_base_currency(),$module);
		
		// Price Summary
		$price_summary = $this->preferred_currency_price_summary ( $token ['price_summary'], $currency_obj );
		// Markup Price Summary
		$markup_price_summary = $this->preferred_currency_price_summary ( $token ['markup_price_summary'], $currency_obj );
		// Assigning the Converted Data
		$master_token = $token;
		$master_token ['Price'] = $Price;
		$master_token ['API_Price'] = $API_Price;
		$master_token ['price_summary'] = $price_summary;
		$master_token ['markup_price_summary'] = $markup_price_summary;
		$master_token ['default_currency'] = admin_base_currency ();
		if(isset($token ['convenience_fees'])){
			$master_token ['convenience_fees'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $token ['convenience_fees'] ) ); // check this	
		}else{
			$master_token ['convenience_fees'] = 0;
		}
		
		return $master_token;
	}
	/**
	 * Balu A
	 * Converts Price summary to application curency
	 * 
	 * @param unknown_type $fare_details        	
	 * @param unknown_type $currency_obj        	
	 */
	private function preferred_currency_price_summary($fare_details, $currency_obj) {
		
		$price_details = array ();
		
		$price_details ['TotalDisplayFare'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['TotalDisplayFare'] ) );
		
		$price_details ['NetFare'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $fare_details ['NetFare'] ) );

		return $price_details;
	}

	/**
	 * Elavarasi
	 * 
	 * @param unknown_type $trip_list        	
	 * @param unknown_type $currency_obj        	
	 */
	public function tourgrade_in_preferred_currency($trip_list, $currency_obj,$module_currency_obj,$module) {
		$application_currency_preference = get_application_currency_preference ();
		$tour_trip_details = $trip_list['Trip_list'];
		$tour_trip_result = array ();

		$level_one = true;	
		$current_domain = true;
		if ($module == 'b2c') {
			$level_one = false;
			$current_domain = true;
		} else if ($module == 'b2b') {
			$level_one = true;
			$current_domain = true;
		}
		foreach ( $tour_trip_details as $tr_k => $tr_v ) {
			$tour_trip_result [$tr_k] = $tr_v;			
			// Price
			$API_raw_price = $tr_v ['Price'];
			$Price = $this->module_preferred_currency_fare_object ( $tr_v ['Price'], $currency_obj,'',$module );//not converting here 
			$tour_trip_result [$tr_k] ['API_raw_price'] = $API_raw_price;
			//debug($Price);
			$api_price_details = $Price;
			#debug($api_price_details);
			if($module=='b2b'){
				
				$this->get_commission($Price,$module_currency_obj);
				#debug($Price);

				$admin_price_details = $this->update_tourgrade_markup_currency($Price,$module_currency_obj
					,true,false);//converting price
				#debug($admin_price_details);
				
				$agent_price_details = $this->update_tourgrade_markup_currency($Price,$module_currency_obj,true,true);

				#debug($agent_price_details);

				$Markup_Price = $this->b2b_price_details($api_price_details,$admin_price_details,$agent_price_details,$module_currency_obj);				

			}else{
					//calculating markup price
				$Markup_Price = $this->update_tourgrade_markup_currency($Price,$module_currency_obj,$level_one,$current_domain,'b2c');
			}
					
			$tour_trip_result [$tr_k] ['Price'] = $Markup_Price;
					
		}

		$trip_list['Trip_list'] = $tour_trip_result;
		return $trip_list;
	}
	/**
	 * Elavarasi
	 * 
	 * @param unknown_type $block_trip_data        	
	 * @param unknown_type $currency_obj        	
	 */
	public function tripblock_data_in_preferred_currency($block_trip_data, $currency_obj,$module) {
		$application_currency_preference = get_application_currency_preference ();
		
		$level_one = true;	
		$current_domain = true;
		if ($module == 'b2c') {
			$level_one = false;
			$current_domain = true;
		} else if ($module == 'b2b') {
			$level_one = true;
			$current_domain = true;
		}

		$tour_trip_details = $block_trip_data ['data']['BlockTrip'] ['BlockTripResult'];
		$API_raw_price = $tour_trip_details['Price'];//FRom APi Price
		$Converted_Price = $this->module_preferred_currency_fare_object ($API_raw_price, $currency_obj,$application_currency_preference,$module );//updatin currecny price
		$cancellation_charge = array();
		//Updating Cancellation policy
		foreach ($tour_trip_details['TM_Cancellation_Charge'] as $key => $value) {
			
			if($value['ChargeType']!=2){
				$cancellation_charge [$key] = $value;
				$cancellation_charge [$key] ['Currency'] = $application_currency_preference;
				$cancellation_charge [$key] ['Charge'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $value ['Charge'] ) );
			}else{
				$cancellation_charge [$key] = $value;
			}
		}
		
		$block_trip_data ['data'] ['BlockTrip'] ['BlockTripResult']['Price'] = $Converted_Price;
		$block_trip_data ['data'] ['BlockTrip'] ['BlockTripResult']['API_raw_price'] = $this->convert_api_preferred_currency_fare_object ($API_raw_price, $currency_obj,$application_currency_preference,$module );

		$block_trip_data ['data'] ['BlockTrip'] ['BlockTripResult']['API_TM_raw_price']  = $API_raw_price;

		$block_trip_data ['data'] ['BlockTrip'] ['BlockTripResult']['TM_Cancellation_Charge'] = $cancellation_charge;

		return $block_trip_data;
	}
	/**
	 * Elavarasi
	 * 
	 * @param unknown_type $fare_details        	
	 * @param unknown_type $currency_obj        	
	 */
	private function convert_api_preferred_currency_fare_object($fare_details, $currency_obj, $default_currency = '',$module='b2c') {

		
		$admin_commission = 0;
		$admin_tdson_commission = 0;
		$agent_commission = 0;
		$agent_tdson_commission = 0;
		$org_commission = 0;
		$or_tdson_commission = 0;
		$admin_profit = 0;
		$show_net_fare = 0;		
		if(isset($fare_details['PriceBreakup'])){
			$admin_commission = $fare_details['PriceBreakup']['AgentCommission'];
			$admin_tds =$fare_details['PriceBreakup']['AgentTdsOnCommision'];
		}else{
			$admin_commission = $fare_details['AgentCommission'];
			$admin_tds =$fare_details['AgentTdsOnCommision'];
		}
		if($module=='b2c'){
			$net_fare = $fare_details['TotalDisplayFare'];
			$agent_commission = $admin_commission;
			$agent_tdson_commission =$admin_tds;		
			
		}else{
			//for b2b users
			//Updating Commission
			$agent_commission =$admin_commission;
			$agent_tdson_commission = $admin_tds;

			$net_fare =$fare_details['TotalDisplayFare'];		
		}
		//echo $net_fare;
		$price_details = array ();
		$price_details ['Currency'] = empty ( $default_currency ) == false ? $default_currency : get_application_currency_preference ();
		$price_details ['TotalDisplayFare'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $net_fare) );

		$price_details['AgentCommission'] =get_converted_currency_value ( $currency_obj->force_currency_conversion ( $agent_commission) );

		$price_details['AgentTdsOnCommision'] =get_converted_currency_value ( $currency_obj->force_currency_conversion ( $agent_tdson_commission) );		
		if($module=='b2c'){
			$show_net_fare = $price_details ['TotalDisplayFare']-$price_details['AgentCommission']+$price_details['AgentTdsOnCommision'];
			//$price_details ['TotalDisplayFare'] = $show_net_fare;
		}else{
			$show_net_fare = ($price_details ['TotalDisplayFare']-$price_details['AgentCommission']);	
		}		
		$price_details ['NetFare'] =$show_net_fare;

		return $price_details;
	}
	/**
	 *
	 * @param array $booking_params        	
	 */
	function process_booking($book_id, $booking_params,$module='b2c') {
		// debug($booking_params);exit;
		$header = $this->get_header ();
		$response ['status'] = FAILURE_STATUS;
		$response ['data'] = array ();		
		$book_request = $this->get_book_request ( $booking_params, $book_id );
		// debug($book_request);exit;
		if($book_request['data']['status']){
			$GLOBALS ['CI']->custom_db->generate_static_response ( $book_request ['data'] ['request'] ); // release this
			
     		$book_response = $GLOBALS ['CI']->api_interface->get_json_response ( $book_request ['data'] ['service_url'], $book_request ['data'] ['request'], $header ); 
     	
			$GLOBALS ['CI']->custom_db->generate_static_response ( json_encode ( $book_response ) );			
			
			$api_book_response_status = $book_response['Status'];
			
			/**
			 * PROVAB LOGGER *
			 */
			$GLOBALS ['CI']->private_management_model->provab_xml_logger ( 'Book_Sightseeing', $book_id, 'sightseeing', $book_request ['data'] ['request'], json_encode ( $book_response ) );
			// validate response
			if ($this->valid_response ( $api_book_response_status )) {

				$book_response['BookResult'] = $book_response['CommitBooking']['BookingDetails'];
				$response ['status'] = SUCCESS_STATUS;
				$response ['data'] ['book_response'] = $book_response;
				$response ['data'] ['booking_params'] = $booking_params;
				
				// Convert Room Book Data in Application Currency
				$block_data_array = $book_request ['data'] ['request'];
				$tour_book_data = json_decode ( $block_data_array, true );
				$response['data']['tour_book_request'] =$tour_book_data; 
				$response ['data'] ['tour_book_data'] = $this->convert_tripbook_data_to_application_currency ( $booking_params,$module );
			}else{
				$response['Message'] = $book_response['Message'];
			}
		}else{
			$response['Message'] = "Invalid Client Booking Request";
		}
		
		return $response;
	}
	/**
     * Returns Final Price Details For the booking
     * @param unknown_type $Fare
     * @param unknown_type $multiplier
     * @param unknown_type $specific_markup_config
     * @param unknown_type $currency_obj
     * @param unknown_type $deduction_cur_obj
     * @param unknown_type $module
     */
    private function get_final_booking_price_details($Fare, $multiplier,$currency_obj, $deduction_cur_obj, $module) {
        $data = array();
        $core_agent_commision = ($Fare['TotalDisplayFare'] - $Fare['NetFare']);       
        $commissionable_fare = $Fare['TotalDisplayFare'];
        if ($module == 'b2c') {
        	
            $trans_total_fare = $this->total_price($Fare, false, $currency_obj);          

            $markup_total_fare = $currency_obj->get_currency($trans_total_fare, true, false, true, $multiplier);
            $ded_total_fare = $deduction_cur_obj->get_currency($trans_total_fare, true, true, false, $multiplier);
            $admin_markup = roundoff_price($markup_total_fare['default_value'] - $ded_total_fare['default_value']);
            $admin_commission = $Fare['AgentCommission'];
            $agent_markup = 0;
            $agent_commission = 0;
        } else {
            //B2B Calculation
         # debug($Fare);
            $trans_total_fare = $Fare['TotalDisplayFare'];             
            $this->commission = $currency_obj->get_commission();
            #echo "commission";
           # debug($this->commission);

            $AgentCommission = $this->calculate_commission($core_agent_commision);
            #debug($AgentCommission);

            $admin_commission = roundoff_price($core_agent_commision - $AgentCommission); //calculate here
            $agent_commission = roundoff_price($AgentCommission);
            
            $admin_net_rate=($trans_total_fare-$agent_commission);
            #echo "admin_net_rate".$admin_net_rate.'<br/>';

            $markup_total_fare = $currency_obj->get_currency($admin_net_rate, true, true, false, $multiplier);
            
            #debug($markup_total_fare);

            $admin_markup = abs($markup_total_fare['default_value'] - $admin_net_rate);
            $agent_tds = $currency_obj->calculate_tds($agent_commission);
            //adding tds with net rate by ela
            $agent_net_rate=(($trans_total_fare + $admin_markup)-$agent_commission+$agent_tds);
            $ded_total_fare = $deduction_cur_obj->get_currency($agent_net_rate, true, false, true, $multiplier);
            $agent_markup = roundoff_price($ded_total_fare['default_value'] - $agent_net_rate);
          
           
        }
        //TDS Calculation
        $admin_tds = $currency_obj->calculate_tds($admin_commission);
        $agent_tds = $currency_obj->calculate_tds($agent_commission);

        $data['commissionable_fare'] = $commissionable_fare;
        $data['trans_total_fare'] = $trans_total_fare;
        $data['admin_markup'] = $admin_markup;
        $data['agent_markup'] = $agent_markup;
        $data['admin_commission'] = $admin_commission;
        $data['agent_commission'] = $agent_commission;
        $data['admin_tds'] = $admin_tds;
        $data['agent_tds'] = $agent_tds;
       //  debug($data);
       // exit;
        return $data;
    }

	/**
	 * Reference number generated for booking from application
	 * 
	 * @param
	 *        	$app_booking_id
	 * @param
	 *        	$params
	 */
	function save_booking($app_booking_id, $params, $module = 'b2c') {
		// Need to return following data as this is needed to save the booking fare in the transaction details
			// Need to return following data as this is needed to save the booking fare in the transaction details
		$response ['fare'] = $response ['domain_markup'] = $response ['level_one_markup'] = 0;

		$domain_origin = get_domain_auth_id ();
		$master_search_id = $params ['booking_params'] ['token'] ['search_id'];
		
		//$status = BOOKING_CONFIRMED;
		$app_reference = $app_booking_id;
		$booking_source = $params ['booking_params'] ['token'] ['booking_source'];
		
		#debug($params);
		$currency_obj = $params ['currency_obj'];
		$promo_currency_obj = $params['promo_currency_obj'];
		$deduction_cur_obj = clone $currency_obj;

		#debug($currency_obj);

		#debug($params)

		// PREFERRED TRANSACTION CURRENCY AND CURRENCY CONVERSION RATE
		$transaction_currency = get_application_currency_preference ();
		$application_currency = admin_base_currency ();
		$currency_conversion_rate = $currency_obj->transaction_currency_conversion_rate ();
		
		$booking_id = $params ['book_response'] ['BookResult'] ['BookingId'];
		$booking_reference = $params ['book_response'] ['BookResult'] ['BookingRefNo'];

		$confirmation_reference = $params ['book_response'] ['BookResult'] ['ConfirmationNo'];
		$status =  $params ['book_response'] ['BookResult'] ['booking_status'];
		$multiplier  =1;

		//$book_total_fare = $params ['booking_params'] ['token'] ['price_summary'] ['TotalDisplayFare']; // (TAX+ROOM PRICE)


		$book_net_total_fare = $params ['booking_params'] ['token'] ['price_summary'] ['NetFare']; // (TAX+ROOM PRICE)
		

		$currency = $params ['booking_params'] ['token'] ['default_currency'];
		$product_name = $params ['booking_params'] ['token'] ['ProductName'];
		$star_rating = $params ['booking_params'] ['token'] ['StarRating'];
		$product_code = $params ['booking_params'] ['token'] ['ProductCode'];
		$phone_number = $params ['booking_params'] ['passenger_contact'];
		$phone_code = $params ['booking_params'] ['phone_country_code'];
		$alternate_number = 'NA';
		
		$travel_date = $params ['booking_params'] ['token'] ['booking_date'];
		$payment_mode = $params ['booking_params'] ['payment_method'];
		
		$grade_code = $params ['booking_params'] ['token'] ['GradeCode'];
		$grade_desc = @$params ['booking_params'] ['token'] ['GradeDescription'];

	
		if(isset($params ['booking_params'] ['token']['search_params'])){
			if($params ['booking_params'] ['token']['search_params']){
				$search_params_list = json_decode(base64_decode($params ['booking_params'] ['token']['search_params']),true);

				$additonal_info = json_decode(base64_decode($search_params_list['additional_info']),true);
			
				$inclusions = json_decode(base64_decode($search_params_list['inclusions']),true);
				$exclusions = json_decode(base64_decode($search_params_list['exclusions']),true);
				$short_desc = base64_decode($search_params_list['short_desc']);
				$voucher_req = base64_decode($search_params_list['voucher_req']);


			}
		}else{
			$additonal_info = json_decode(base64_decode($params ['booking_params'] ['token'] ['additional_info']),true);
		
			$inclusions = json_decode(base64_decode($params ['booking_params'] ['token'] ['inclusions']),true);
			
			$exclusions = json_decode(base64_decode($params ['booking_params'] ['token'] ['exclusions']),true);
			

			$short_desc = base64_decode($params ['booking_params'] ['token'] ['short_desc']);

			$voucher_req = base64_decode($params['booking_params']['token']['voucher_req']);

		}
		
		
		$email =$params ['booking_params']['billing_email'];
		// $city_name = $GLOBALS['CI']->db_cache_api->get_city_list(array('k' => 'origin', 'v' => 'destination'), array('origin' => $params['booking_params']['billing_city']));
		$attributes = array (
				'address' => @$params ['booking_params'] ['billing_address_1'],
				'billing_country' => @$country_name [$params ['booking_params'] ['billing_country']],
				// 'billing_city' => $city_name[$params['booking_params']['billing_city']],
				'billing_city' => @$params ['booking_params'] ['billing_city'],
				'billing_zipcode' => @$params ['booking_params'] ['billing_zipcode'],
				'ProductCode' => @$params ['booking_params'] ['token'] ['ProductCode'],
				'search_id' => @$params ['booking_params'] ['token'] ['search_id'],
				
				'ProductName' => @$params ['booking_params'] ['token'] ['ProductName'],
				'StarRating' => @$params ['booking_params'] ['token'] ['StarRating'],
				'ProductImage' => @$params ['booking_params'] ['token'] ['ProductImage'],
				'SupplierName' =>$params ['booking_params'] ['token'] ['SupplierName'],
				'SupplierPhoneNumber' =>$params ['booking_params'] ['token'] ['SupplierPhoneNumber'],

				'GradeCode' => @$params ['booking_params'] ['token'] ['GradeCode'],
				'GradeDescription'=>@$params['booking_params']['token']['GradeDescription'],
				'Destination' => @$params ['booking_params'] ['token'] ['Destination'],
				'DeparturePoint'=>@$params['booking_params']['token']['DeparturePoint'],
				'DeparturePointAddress' => @$params ['booking_params'] ['token'] ['DeparturePointAddress'],
				'Duration'=>@$params['booking_params']['token']['Duration'],
				
				'Cancellation_available'=>@$params['booking_params']['token']['Cancellation_available'],
				'DeparturePointAddress'=>@$params['booking_params']['token']['DeparturePointAddress'],
				'TM_Cancellation_Charge'=>@$params['booking_params']['token']['TM_Cancellation_Charge'],
				'TM_LastCancellation_date'=>@$params['booking_params']['token']['TM_LastCancellation_date'],
				'TM_Cancellation_Policy'=>@$params['booking_params']['token']['TM_Cancellation_Policy'],
				'Additional_info'=>json_encode($additonal_info),
				'Inclusions'=>json_encode($inclusions),
				'Exclusions'=>json_encode($exclusions),
				'ShortDesc'=>$short_desc,
				'VoucherInfo'=>$voucher_req

		);
		$created_by_id = intval ( @$GLOBALS ['CI']->entity_user_id );
		// SAVE Booking details
		$GLOBALS ['CI']->sightseeing_model->save_booking_details ( $domain_origin, $status, $app_reference, $booking_source, $booking_id, $booking_reference, $confirmation_reference, $product_name, $star_rating, $product_code,$grade_code,$grade_desc,$phone_number, $alternate_number, $email, $travel_date,$payment_mode, json_encode ( $attributes ), $created_by_id, $transaction_currency, $currency_conversion_rate, $phone_code );
		

		$Fare =  $params ['booking_params'] ['token']['API_Price'];

		$final_booking_price_details = $this->get_final_booking_price_details($Fare, $multiplier,$currency_obj, $deduction_cur_obj, $module);

		$book_total_fare = $commissionable_fare =$final_booking_price_details['commissionable_fare'];

		$total_fare = $trans_total_fare =$final_booking_price_details['trans_total_fare'];		
		$admin_markup =  $book_domain_markup = $final_booking_price_details['admin_markup'];
        $agent_markup = $final_booking_price_details['agent_markup'];
        $admin_commission = $final_booking_price_details['admin_commission'];
        $agent_commission = $final_booking_price_details['agent_commission'];
        $admin_tds = $final_booking_price_details['admin_tds'];
        $agent_tds = $final_booking_price_details['agent_tds'];
        if($module == 'b2c'){
        	$total_markup = $admin_markup;
        }
        else if($module == 'b2b'){
           $total_markup = $admin_markup+$agent_markup;
        }
        //adding gst
       	$gst_value = 0;
       	if($total_markup > 0 ){
            $gst_details = $GLOBALS['CI']->custom_db->single_table_records('gst_master', '*', array('module' => 'activities'));
            if($gst_details['status'] == true){
                if($gst_details['data'][0]['gst'] > 0){
                    $gst_value = ($total_markup/100) * $gst_details['data'][0]['gst'];
                	$gst_value  = roundoff_price($gst_value);
                }
            }
    	}
       

		/*$book_total_fare = $params ['booking_params'] ['token'] ['price_summary'] ['TotalDisplayFare']; // (TAX+ROOM PRICE)

		$total_fare = $params ['booking_params'] ['token'] ['price_summary'] ['TotalDisplayFare'];		
		$agent_commission = $params ['booking_params'] ['token']['Price']['AgentCommission'];
		$agent_tds = $params ['booking_params'] ['token']['Price']['AgentTdsOnCommision'];

		$admin_commission = $params ['booking_params'] ['token']['Price']['AdminCommission'];
		$admin_tds = $params ['booking_params'] ['token']['Price']['AdminTdsonCommission'];*/


		$attributes = '';
		$location = $params ['booking_params'] ['token'] ['Destination'];

		$api_raw_fare = $params ['booking_params'] ['token']['Price']['TotalDisplayFare'];
		$agent_buying_price = $params['booking_params']['token']['markup_price_summary']['NetFare'];
		$admin_net_fare_markup = 0;
		// SAVE Booking Itinerary details
		$GLOBALS ['CI']->sightseeing_model->save_booking_itinerary_details ( $app_reference, $location, $travel_date,$grade_code, $grade_desc, $status,$commissionable_fare,$admin_net_fare_markup,$admin_markup, $agent_markup, $currency, $attributes, @$book_total_fare,$agent_commission,$agent_tds,$admin_commission,$admin_tds,$api_raw_fare,$agent_buying_price, $gst_value);

		$TourPassengers = $params['tour_book_request']['PassengerDetails'];
		
		if (valid_array ( $TourPassengers ) == true) {
			$ik=0;
			foreach ( $TourPassengers as $passenger ) {
				$title = $passenger ['Title'];
				$first_name = $passenger ['FirstName'];
				if($ik==0)
				{
					$pax_fname=$first_name;
				}
				$ik=$ik+1;
				//$middle_name = $passenger ['MiddleName'];
				$last_name = $passenger ['LastName'];
				$phone = $passenger ['Phoneno'];
				$email = $passenger ['Email'];
				$pax_type = $passenger ['PaxType'];
				
				$attributes = array ();
				
				// SAVE Booking Pax details
				$GLOBALS ['CI']->sightseeing_model->save_booking_pax_details ($title,$app_reference, $first_name,$last_name, $phone, $email, $pax_type,$status, serialize ( $attributes ) );
			}
		}
		
		/**
		 * ************ Update Convinence Fees And Other Details Start *****************
		 */
		// Convinence_fees to be stored and discount
		$convinence = 0;
		$discount = 0;
		$convinence_value = 0;
		$convinence_type = 0;
		$convinence_per_pax = 0;
		

		if ($module == 'b2c') {
			$pax_data = $params['booking_params']['token']['AgeBands'];
			$total_transaction_amount = $trans_total_fare+$book_domain_markup+$gst_value;
			$convinence = $currency_obj->convenience_fees ($total_transaction_amount ,$pax_data );

			$convinence_row = $currency_obj->get_convenience_fees ();
			$convinence_value = $convinence_row ['value'];
			$convinence_type = $convinence_row ['type'];
			$convinence_per_pax = $convinence_row ['per_pax']; 
			//$discount = @$params ['booking_params'] ['promo_code_discount_val'];

			if($params['booking_params']['promo_actual_value']){
				$discount = get_converted_currency_value ( $promo_currency_obj->force_currency_conversion ( $params['booking_params']['promo_actual_value']) );
			}
			
			$promo_code = @$params ['booking_params'] ['promo_code'];
		} elseif ($module == 'b2b') {
			$discount = 0;
			$promo_code='';
		}
		$GLOBALS ['CI']->load->model ( 'transaction' );
		// SAVE Booking convinence_discount_details details
		
		$GLOBALS ['CI']->transaction->update_convinence_discount_details ( 'transfer_booking_details', $app_reference, $discount, $promo_code, $convinence, $convinence_value, $convinence_type, $convinence_per_pax );
		/**
		 * ************ Update Convinence Fees And Other Details End *****************
		 */		
		$response ['fare'] = $total_fare;
		$response ['name'] = $pax_fname;
		$response ['phone'] = $phone;
		$response ['admin_markup'] = $admin_markup;
		$response ['agent_markup'] = $agent_markup;
		$response ['convinence'] = $convinence;
		$response ['discount'] = $discount;
		$response ['transaction_currency'] = $transaction_currency;
		$response ['currency_conversion_rate'] = $currency_conversion_rate;
		//booking_status
		$response['booking_status'] = $status;
		return $response;
	}
	/**
	 * Balu A
	 * Convert Room Book Data in Application Currency
	 * 
	 * @param
	 *        	$currency_obj
	 */
	private function convert_tripbook_data_to_application_currency($tour_book_data,$module='b2c') {
		$application_default_currency = admin_base_currency ();
		$currency_obj = new Currency ( array (
				'module_type' => 'sightseeing',
				'from' => get_api_data_currency (),
				'to' => admin_base_currency () 
		) );
		$master_trip_book_data = array ();
		$TourTripDetails = array();
		
		$TourTripDetails['Price'] = $this->module_preferred_currency_fare_object ( $tour_book_data['token']['Price'], $currency_obj, $application_default_currency,$module );
		$master_trip_book_data = $tour_book_data;
		$master_trip_book_data ['Price'] = $TourTripDetails;
		return $master_trip_book_data;
	}
	
	/**
	 * Elavarasi
	 * Cancellation Request Status
	 */
	function get_cancellation_refund_details($ChangeRequestId, $app_reference) {
		$header = $this->get_header ();
		$response ['data'] = array ();
		$response ['status'] = FAILURE_STATUS;
		$resposne ['msg'] = 'Remote IO Error';
		$api_request = $this->cancellation_refund_request_params ( $ChangeRequestId, $app_reference );
		if ($api_request ['status']) {
			$api_response = $GLOBALS ['CI']->api_interface->get_json_response ( $api_request ['data'] ['service_url'], $api_request ['data'] ['request'], $header );
			
			if (valid_array ( $api_response ) == true && isset ( $api_response ['Status'] ) == true && $api_response ['Status'] == SUCCESS_STATUS) {
				$currency_obj = new Currency(array('module_type' => 'sightseeing', 'from' => get_api_data_currency(), 'to' => admin_base_currency()));

				$response ['data'] = $this->convert_cancelletion_refund_details($currency_obj,$api_response ['RefundDetails']);
				$response ['status'] = SUCCESS_STATUS;
			} else {
				$resposne ['msg'] = @$api_response ['Message'];
			}
		}
		return $response;
	}
	/**
	*Elavarasi
	*@param currency obj TMX currency to application currency
	*@param fare_details TMX refund details
	*/
	private function convert_cancelletion_refund_details($currency_obj,$refund_details){

		$price_details = array ();		
		$price_details ['ChangeRequestId'] = $refund_details['ChangeRequestId'];
		$price_details ['ChangeRequestStatus'] = $refund_details['ChangeRequestStatus'];
		$price_details ['StatusDescription'] = $refund_details['StatusDescription'];
		$price_details ['RefundedAmount'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $refund_details ['RefundedAmount'] ) );		
		$price_details ['CancellationCharge'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( $refund_details ['CancellationCharge'] ) );
		return $price_details;
	}
	/**
	 * Sawood
	 * check and return status is success or not
	 * 
	 * @param unknown_type $response_status        	
	 */
	function valid_book_response($response_status) {
		$status = false;		
		return $status;
	}
	
	/**
	 * Balu A
	 * check and return status is success or not
	 * 
	 * @param unknown_type $response_status        	
	 */
	function valid_response($response_status) {
		$status = true;
		if ($response_status != SUCCESS_STATUS) {
			$status = false;
		}
		return $status;
	}
	
	/**
	 * Elavarasi
	 * check if the product response which is received from server is valid or not
	 * 
	 * @param $product_details
	 */
	private function valid_product_details($product_details) {
		$status = false;
		if ($product_details ['Status'] == ACTIVE) {
			if(valid_array($product_details['ProductDetails']['SSInfoResult'])){
				$status = true;	
			}
			
		}
		return $status;
	}
	
		/**
	 * Elavarasi
	 * check if the product response which is received from server is valid or not
	 * 
	 * @param $product_details
	 */
	private function valid_product_tourgrade($tourgrade_details) {
		$status = false;

		if ($tourgrade_details ['Status'] == SUCCESS_STATUS) {
			if(valid_array($tourgrade_details['TripList']['Trip_list'])){
				$status = true;	
			}
			
		}
		return $status;
	}


	/**
	 * Elavarasi
	 * check if the search response is valid or not
	 * 
	 * @param array $search_result
	 * search result response to be validated
	 */
	private function valid_search_result($search_result) {
		if(valid_array($search_result['data']['SSSearchResult']['TransferResults'])&&$search_result['Status']==SUCCESS_STATUS){
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 * Elavarasi
	 * Update and return price details
	 */
	public function update_block_details($trip_details, $booking_parameters,$cancel_currency_obj,$module='b2c') {
		
		//echo get_application_currency_preference();
		$booking_parameters ['BlockTourId'] = $trip_details['BlockTripResult'] ['BlockTourId'];
		$booking_parameters ['ProductName'] = $trip_details['BlockTripResult']['ProductName'];
		$booking_parameters ['ProductCode'] = $trip_details['BlockTripResult']['ProductCode'];
		$booking_parameters ['ProductImage'] = $trip_details['BlockTripResult']['ProductImage'];

		$booking_parameters ['GradeCode'] = $trip_details['BlockTripResult']['GradeCode'];
		$booking_parameters ['GradeDescription'] = $trip_details['BlockTripResult']['GradeDescription'];

		$booking_parameters['StarRating'] = $trip_details['BlockTripResult']['StarRating'];
		$booking_parameters['Duration'] = $trip_details['BlockTripResult']['Duration'];
		$booking_parameters['Destination'] = $trip_details['BlockTripResult']['Destination'];
		$booking_parameters ['DeparturePoint'] = $trip_details['BlockTripResult']['DeparturePoint'];

		$booking_parameters['DeparturePointAddress']  = $trip_details['BlockTripResult']['DeparturePointAddress'];

		$booking_parameters['SupplierName'] = $trip_details['BlockTripResult']['SupplierName'];
		$booking_parameters['SupplierPhoneNumber'] = $trip_details['BlockTripResult']['SupplierPhoneNumber'];

		$booking_parameters['AgeBands'] = $trip_details['AgeBands'];
		$booking_parameters['BookingQuestions'] = $trip_details['BlockTripResult']['BookingQuestions'];
		$booking_parameters['HotelPickup'] = $trip_details['BlockTripResult']['HotelPickup'];
		$booking_parameters['Hotel_Pickup_Option'] = $trip_details['BlockTripResult']['hotel_pikcup_option'];

		$booking_parameters['HotelList'] = $trip_details['BlockTripResult']['HotelList'];
		$booking_parameters['Cancellation_available'] = $trip_details['BlockTripResult']['Cancellation_available'];
		$booking_parameters['TM_Cancellation_Charge'] = $trip_details['BlockTripResult']['TM_Cancellation_Charge'];
		$booking_parameters['TM_LastCancellation_date'] = $trip_details['BlockTripResult']['TM_LastCancellation_date'];
		$policy_string = '';

		$Trip_price = $trip_details['BlockTripResult']['Price']['TotalDisplayFare'];

		$level_one = true;	
		$current_domain = true;
		if ($module == 'b2c') {
			$level_one = false;
			$current_domain = true;
		} else if ($module == 'b2b') {
			$level_one = true;
			$current_domain = true;
		}

		if($module=='b2b'){

			$b2b_price_summary = $this->update_commission_markup_module_wise($trip_details['BlockTripResult']['Price'],$cancel_currency_obj);
			$markup_cancellation_price = roundoff_price($b2b_price_summary['TotalDisplayFare']);
			
		}else{
			$markup_cancellation_price = $this->update_cancellation_markup_currency($Trip_price,$cancel_currency_obj,$level_one,$current_domain);
		}
		
		
		// calculate markup for cancellation policy


		$booking_parameters['Price'] = $trip_details['BlockTripResult']['Price'];
		
		$booking_parameters['API_Price']  = $trip_details['BlockTripResult']['API_raw_price'];
		$booking_parameters['API_TM_Price']  = $trip_details['BlockTripResult']['API_TM_raw_price'];	
		
		if($booking_parameters['TM_Cancellation_Charge']){
			foreach ($booking_parameters['TM_Cancellation_Charge']  as $key => $value) {

				if($value['Charge']==0){
						 $policy_string .='No cancellation charges, if cancelled before '.date('d M Y',strtotime($value['ToDate'])).'<br/>';
						
				}else{
						if($value['ChargeType']!=2){
								$amount =  $cancel_currency_obj->get_currency_symbol($cancel_currency_obj->to_currency)." ".$value['Charge'];
						}else{
							$amount =  $cancel_currency_obj->get_currency_symbol($cancel_currency_obj->to_currency)." ".$markup_cancellation_price;
						}
						$current_date = date('Y-m-d');
						$cancell_date = date('Y-m-d',strtotime($value['FromDate']));
						if($cancell_date >$current_date){
							$value['FromDate'] = $value['FromDate'];

							$policy_string .=' Cancellations made after '.date('d M Y',strtotime($value['FromDate'])).', or no-show, would be charged '.$amount;
						}else{
							$value['FromDate'] = date('Y-m-d');
							$policy_string  .='This rate is non-refundable. If you cancel this booking you will not be refunded any of the payment.';
						}
				}

			}
		}
		$booking_parameters['TM_Cancellation_Policy'] = $policy_string;
		$booking_parameters['price_summary'] = viator_summary_trip_combination ( $trip_details ['BlockTripResult']['Price'] );
		// debug($booking_parameters);
		// exit;
		return $booking_parameters;	
	}
	/**
	*Update Markup currency for Cancellation Charge
	*/
	function update_cancellation_markup_currency(&$cancel_charge,&$currency_obj,$level_one_markup=false,$current_domain_markup=true){
		$multiplier = 1;
		$to_convert_currency = $currency_obj->to_currency;
		$temp_price = $currency_obj->get_currency ( $cancel_charge, true, $level_one_markup, $current_domain_markup, $multiplier );
				
		if($to_convert_currency=='INR'){
			return roundoff_price($temp_price['default_value']);	
		}else{
			return $temp_price['default_value'];
		}
	}
	
	
	/**
	 * Markup for search result
	 * 
	 * @param array $price_summary        	
	 * @param object $currency_obj        	
	 * @param number $search_id        	
	 */
	function update_search_markup_currency(& $price_summary, & $currency_obj, $search_id, $level_one_markup = false, $current_domain_markup = true, $module='') {		
		$multiplier = 1;

		// debug($search_id);exit;
		if(isset($search_id))
		{ 
			$this->CI->product_search_id = $search_id;
			$this->CI->product_supplier = 'Beds Online';
		}
		return $this->update_markup_currency ( $price_summary, $currency_obj, $multiplier, $level_one_markup, $current_domain_markup, $module );
	}
	
	
	/**
	 * Markup for Tourgrade List
	 * 
	 * @param array $price_summary        	
	 * @param object $currency_obj        	
	 * @param number $search_id        	
	 */
	function update_tourgrade_markup_currency(& $price_summary, & $currency_obj, $level_one_markup = false, $current_domain_markup = true, $module='') {		
		$multiplier = 1;		
		return $this->update_markup_currency ( $price_summary, $currency_obj, $multiplier, $level_one_markup, $current_domain_markup, $module );
	}
	
	/**
	 * Markup for Booking Page List
	 * 
	 * @param array $price_summary        	
	 * @param object $currency_obj        	
	 * @param number $search_id        	
	 */
	function update_booking_markup_currency(& $price_summary, & $currency_obj, $search_id, $level_one_markup = false, $current_domain_markup = true, $module='') {

		if(isset($search_id))
		{ 
			$this->CI->product_search_id = $search_id;
			$this->CI->product_supplier = 'Beds Online';
		} 
		return $this->update_search_markup_currency ( $price_summary, $currency_obj, $search_id, $level_one_markup, $current_domain_markup, $module );
	}
	
	/**
	 * update markup currency and return summary
	 * $attr needed to calculate number of nights markup when its plus based markup
	 */
	function update_markup_currency(& $price_summary, & $currency_obj, $no_of_nights = 1, $level_one_markup = false, $current_domain_markup = true, $module='') {
		$tax_service_sum = 0;
		/* $tax_service_sum = $this->tax_service_sum($price_summary); */
		// Remove Tax and Service Tax While Adding markup	
		// debug($price_summary);exit;
		$tax_removal_list = array ();
		$markup_list = array (
				'NetFare'	
				
		);		
		
		$to_convert_currency = $currency_obj->to_currency;
		$markup_summary = array ();
		foreach ( $price_summary as $__k => $__v ) {
			
			if($__k !='Currency'){
				$ref_cur = $currency_obj->force_currency_conversion ( $__v ); // Passing Value By Reference so dont remove it!!!

				$price_summary [$__k] = $ref_cur ['default_value']; // If you dont understand then go and study "Passing value by reference"	
			}else{

				$price_summary [$__k] = $__v; // If you dont understand then go and study "Passing value by reference"
			}
			
			if (in_array ( $__k, $markup_list )) {
				$temp_price = $currency_obj->get_currency ( $__v, true, $level_one_markup, $current_domain_markup, $no_of_nights );


			} else {
				if($__k!='Currency'){
					$temp_price = $currency_obj->force_currency_conversion ( $__v );

				}else{
					$temp_price ['default_value'] = $__v;
				}
				
			}
			// adding service tax and tax to total
			// debug($temp_price);exit;
			if (in_array ( $__k, $tax_removal_list )) {
				if($to_convert_currency =='INR'){
					$markup_summary [$__k] = roundoff_price($temp_price ['default_value'] + $tax_service_sum);	
				}else{
					$markup_summary [$__k] = ($temp_price ['default_value'] + $tax_service_sum);
				}
			} else {

				if($to_convert_currency=='INR'){
					//$markup_summary [$__k] = roundoff_price($temp_price ['default_value']);	
					$markup_summary [$__k] = ($temp_price ['default_value']);
					//debug($markup_summary);exit;

				}else{
					$markup_summary [$__k] = ($temp_price ['default_value']);
				}
			}
			//debug($markup_summary);exit;
			
		}		
		 //Markup
        //PublishedFare

       


        $Markup = 0;
        $price_summary['_Markup'] = 0;
        if (isset($markup_summary['NetFare'])) {
        	// echo "ff";exit;
        	// echo $markup_summary['NetFare'];
        	// echo $price_summary['NetFare'];
            $Markup = $markup_summary['NetFare'] - $price_summary['NetFare'];
            if($to_convert_currency=='INR'){
            	$markup_summary['TotalDisplayFare'] = roundoff_price($markup_summary['TotalDisplayFare'] + $Markup);
            	//debug($markup_summary);
            }else{
            	$markup_summary['TotalDisplayFare'] = $markup_summary['TotalDisplayFare'] + $Markup;
            }
            
        }
        // debug($Markup);exit;
       // debug($markup_summary);exit;
        $gst_value = 0;
        if($module == 'b2c'){
        	//adding gst
	      	if($Markup > 0 ){
	            $gst_details = $GLOBALS['CI']->custom_db->single_table_records('gst_master', '*', array('module' => 'activities'));
	             
	            if($gst_details['status'] == true){
	                if($gst_details['data'][0]['gst'] > 0){
	                    $gst_value = ($Markup/100) * $gst_details['data'][0]['gst'];
	                }
	            }
	        }

	    }

        $markup_summary['_GST'] = $gst_value;
        $markup_summary['TotalDisplayFare'] = roundoff_price($markup_summary['TotalDisplayFare']+$gst_value);
       // debug($markup_summary['TotalDisplayFare']);exit;
        $markup_summary['NetFare'] = roundoff_price($markup_summary['NetFare']+$gst_value);
     	$markup_summary['_Markup'] = $Markup;
     	 // debug($markup_summary);exit;
		return $markup_summary;
	}
	
	/**
	 * Tax price is the price for which markup should not be added
	 */
	function tax_service_sum($markup_price_summary, $api_price_summary) {
		// sum of tax and service ;
		return ($markup_price_summary ['TotalDisplayFare'] - $api_price_summary ['NetFare']);
	}
	
	/**
	 * calculate and return total price details
	 */
	function total_price($price_summary,$module='b2c') {
		
		return $price_summary ['NetFare'];
		
	}
	
	/**
	 * Balu A
	 * 
	 * @param
	 *        	$ChangeRequestStatus
	 */
	private function ChangeRequestStatusDescription($ChangeRequestStatus) {
		$status_description = '';
		switch ($ChangeRequestStatus) {
			case 0 :
				$status_description = 'NotSet';
				break;
			case 1 :
				$status_description = 'Pending';
				break;
			case 2 :
				$status_description = 'InProgress';
				break;
			case 3 :
				$status_description = 'Processed';
				break;
			case 4 :
				$status_description = 'Rejected';
				break;
		}
		return $status_description;
	}
	
	/**
	 * Get Filter Params - fliter_params
	 */
	function format_search_response($sl, $cobj, $sid, $module = 'b2c', $fltr = array(),$booking_source='') {
		$safe_search_data = $GLOBALS['CI']->transfer_model->get_safe_search_data($sid);
		
		$country_city_data = $GLOBALS['CI']->transfer_model->get_country_city($safe_search_data['data']['from_code']);
		$cntry_code = $country_city_data[0]['country_code'];
		$city_id = $country_city_data[0]['city_id'];
		$total_pax = $safe_search_data['data']['adult']+$safe_search_data['data']['child'];
		$level_one = true;	
		$current_domain = true;
		if ($module == 'b2c') {
			$level_one = false;
			$current_domain = true;
		} else if ($module == 'b2b') {
			$level_one = true;
			$current_domain = true;
		}
		$h_count = 0;
		$TransferResults = array ();
		// debug($sl); exit;
		// Creating closures to filter data
		$check_filters = function ($hd) use ($fltr) {

				
			if (
				(valid_array ( @$fltr ['cate'] ) == false ||
				(valid_array ( @$fltr ['cate'] ) == true && array_intersect($fltr ['cate'], $hd ['Cat_Ids']))) &&

				( isset($fltr['_sf']) == false || (valid_array ( @$fltr ['_sf'] ) == false || (valid_array ( @$fltr ['_sf'] ) == true && in_array ( $hd ['StarRating'], $fltr ['_sf'] ))) ) &&

				(@$fltr ['min_price'] <= roundoff_price ( $hd ['Price'] ['TotalDisplayFare'] ) && (@$fltr ['max_price'] != 0 && @$fltr ['max_price'] >= roundoff_price ($hd ['Price'] ['TotalDisplayFare'] ))) &&

				(empty ( $fltr ['an_val'] ) == true || (empty ( $fltr ['an_val'] ) == false && stripos ( strtolower ( $hd ['transfer_name'] ), (urldecode ( $fltr ['an_val'] )) ) > - 1)) &&

				(( string ) $fltr ['dis'] == 'false' || empty ( $hd ['Promotion'] ) == false)

			)

			{

				return true;
			} else {
				return false;
			}
		};
		
		$sc = 0;
		$frc = 0;
 	// debug($sl);exit;
		foreach ( $sl ['SSSearchResult'] ['TransferResults'] as $hr => $hd ) {
			$sc ++;
			// default values
			$hd ['StarRating'] = intval ($hd ['StarRating']);
			
			$api_price_details = $hd['Price'];

			// debug($hd);exit;
			if($module=='b2b'){
				
				// echo "ccc";exit;
				if($hd ['Currency'] !="NPR")
				{
					$currency_obj_m = new Currency(array('module_type' => 'transfers', 'from' => $hd['currency'], 'to' => $agent_base_currency));
	            	$strCurrency_m  = $currency_obj_m->get_currency($hd ['Price'] ['TotalDisplayFare'], true, false, true, false, 1);
	            	$rescurency = $strCurrency_m['default_value'];
	            	$hd ['Price'] ['TotalDisplayFare']=$rescurency;
	            	$hd ['Price'] ['NetFare']=$rescurency;
				}
				$supplier = 'Voyage CRS';
				if(!empty($hd['booking_source'])){
					if($hd['booking_source'] == PTBSID00000000013){
						$supplier = 'Voyage CRS';
					}
				}else{
					$supplier = 'Beds Online';
				}
				$product_id = '';
				if(!empty($hd['id'])){
						$product_id = $hd['id'];
				}	
				// debug($hd);exit;

				$search_level_markup_val = $safe_search_data['data']['markup_value'];
				if($search_level_markup_val!='' || $search_level_markup_val!=0){
				$search_level_markup_typ = $safe_search_data['data']['markup_type'];
				if($search_level_markup_typ=='plus'){
					$search_level_markup = $search_level_markup_val;
				}else{
					$search_level_markup = ($hd ['Price']['TotalDisplayFare']/100) * $search_level_markup_val;
				}
				}else{
					$search_level_markup = 0;
				}
				$this->get_commission($hd['Price'],$cobj);
				$admin_markup_details = $GLOBALS ['CI']->transfer_model->get_markup_for_admin ( $hd ['Price']['TotalDisplayFare'], $supplier, $cntry_code, $city_id, $product_id);
		//		echo $GLOBALS ['CI']->db->last_query();
		//		debug($admin_markup_details);die;
				$agent_markup_details = $GLOBALS ['CI']->transfer_model->get_markup_for_agent ( $hd ['Price']['TotalDisplayFare'], $cntry_code, $city_id);
				$admin_price_details = $this->update_search_markup_currency ( $hd ['Price'], $cobj, $sid, true, false );
					// markup
				$admin_price_details['TotalDisplayFare'] =round($hd ['Price']['TotalDisplayFare']+$admin_markup_details);
				$admin_price_details['NetFare'] = round($hd ['Price']['TotalDisplayFare']+$admin_markup_details);
				$admin_price_details['_Markup'] = $admin_markup_details;
				$admin_price_details['_SearchMarkup'] = $search_level_markup;
				// debug($admin_price_details);
				$agent_price_details = $this->update_search_markup_currency ( $hd ['Price'], $cobj, $sid, $level_one, $current_domain );
		//	echo $GLOBALS ['CI']->db->last_query();
//debug($agent_price_details);die;
				$agent_price_details['TotalDisplayFare'] = $hd ['Price']['TotalDisplayFare']+$agent_markup_details;
				$agent_price_details['NetFare'] = $hd ['Price']['TotalDisplayFare']+$agent_markup_details;
				$agent_price_details['_Markup'] = $agent_markup_details;
				$agent_price_details['_SearchMarkup'] = $search_level_markup;
				$hd ['Price'] = $this->b2b_price_details_search($api_price_details, $admin_price_details, $agent_price_details, $cobj, $total_pax);
				$hd ['Price']['TotalDisplayFare']=round($hd ['Price']['TotalDisplayFare']);
				
				// debug($agent_price_details);exit;

			}else{
					// markup
				//debug($current_domain);exit;
				$hd ['Price'] = $this->update_search_markup_currency ( $hd ['Price'], $cobj, $sid, $level_one, $current_domain, $module );
			//	debug($hd ['Price']);die;
			}
		
			// filter after initializing default data and adding markup
			if (valid_array ( $fltr ) == true && $check_filters ( $hd ) == false) {
				continue;
			}
			$TransferResults [$hr] = $hd;
			$frc ++;
		}
		// debug($fltr);exit;
		// SORTING STARTS
		if (isset ( $fltr ['sort_item'] ) == true && empty ( $fltr ['sort_item'] ) == false && isset ( $fltr ['sort_type'] ) == true && empty ( $fltr ['sort_type'] ) == false) {
			$sort_item = array ();
			// debug($TransferResults);exit;
			foreach ( $TransferResults as $key => $row ) {
				if ($fltr ['sort_item'] == 'price') {
					$sort_item [$key] = roundoff_price ( $row ['Price'] ['TotalDisplayFare'] );
				} else if ($fltr ['sort_item'] == 'star') {
					$sort_item [$key] = roundoff_price ( $row ['StarRating'] );
				} else if ($fltr ['sort_item'] == 'name') {
					$sort_item [$key] = trim ( $row ['transfer_name'] );
				}
			}
			if ($fltr ['sort_type'] == 'asc') {
				$sort_type = SORT_ASC;
			} else if ($fltr ['sort_type'] == 'desc') {
				$sort_type = SORT_DESC;
			}
			if (valid_array ( $sort_item ) == true && empty ( $sort_type ) == false) {
				array_multisort ( $sort_item, $sort_type, $TransferResults );
			}
		} // SORTING ENDS
		
		$sl ['SSSearchResult'] ['TransferResults'] = $TransferResults;
		$sl ['source_result_count'] = $sc;
		$sl ['filter_result_count'] = $frc;
		// debug($sl);exit;
		return $sl;
	}
	function format_search_response_detail($sl, $cobj, $sid, $module = 'b2c', $fltr = array(),$booking_source='') {
		$safe_search_data = $GLOBALS['CI']->transfer_model->get_safe_search_data($sid);
		
		$country_city_data = $GLOBALS['CI']->transfer_model->get_country_city($safe_search_data['data']['from_code']);
		$cntry_code = $country_city_data[0]['country_code'];
		$city_id = $country_city_data[0]['city_id'];
		$total_pax = $safe_search_data['data']['adult']+$safe_search_data['data']['child'];
		$level_one = true;	
		$current_domain = true;
		if ($module == 'b2c') {
			$level_one = false;
			$current_domain = true;
		} else if ($module == 'b2b') {
			$level_one = true;
			$current_domain = true;
		}
		$h_count = 0;
		$TransferResults = array ();
		// debug($sl); exit;
		// Creating closures to filter data
		$check_filters = function ($hd) use ($fltr) {

				
			if (
				(valid_array ( @$fltr ['cate'] ) == false ||
				(valid_array ( @$fltr ['cate'] ) == true && array_intersect($fltr ['cate'], $hd ['Cat_Ids']))) &&

				( isset($fltr['_sf']) == false || (valid_array ( @$fltr ['_sf'] ) == false || (valid_array ( @$fltr ['_sf'] ) == true && in_array ( $hd ['StarRating'], $fltr ['_sf'] ))) ) &&

				(@$fltr ['min_price'] <= roundoff_price ( $hd ['Price'] ['TotalDisplayFare'] ) && (@$fltr ['max_price'] != 0 && @$fltr ['max_price'] >= roundoff_price ($hd ['Price'] ['TotalDisplayFare'] ))) &&

				(empty ( $fltr ['an_val'] ) == true || (empty ( $fltr ['an_val'] ) == false && stripos ( strtolower ( $hd ['transfer_name'] ), (urldecode ( $fltr ['an_val'] )) ) > - 1)) &&

				(( string ) $fltr ['dis'] == 'false' || empty ( $hd ['Promotion'] ) == false)

			)

			{

				return true;
			} else {
				return false;
			}
		};
		
		$sc = 0;
		$frc = 0;
 	// debug($sl);exit;
		foreach ( $sl ['SSSearchResult'] ['TransferResults'] as $hr => $hd ) {
			$sc ++;
			// default values
			$hd ['StarRating'] = intval ($hd ['StarRating']);
			
			$api_price_details = $hd['Price'];

			// debug($hd);exit;
			if($module=='b2b'){
				
				// echo "ccc";exit;
				if($hd ['Currency'] !="NPR")
				{
					$currency_obj_m = new Currency(array('module_type' => 'transfers', 'from' => $hd['currency'], 'to' => $agent_base_currency));
	            	$strCurrency_m  = $currency_obj_m->get_currency($hd ['Price'] ['TotalDisplayFare'], true, false, true, false, 1);
	            	$rescurency = $strCurrency_m['default_value'];
	            	$hd ['Price'] ['TotalDisplayFare']=$rescurency;
	            	$hd ['Price'] ['NetFare']=$rescurency;
				}
				$supplier = 'Voyage CRS';
				if(!empty($hd['booking_source'])){
					if($hd['booking_source'] == PTBSID00000000013){
						$supplier = 'Voyage CRS';
					}
				}else{
					$supplier = 'Beds Online';
				}
				$product_id = '';
				if(!empty($hd['id'])){
						$product_id = $hd['id'];
				}	
				// debug($hd);exit;

				$search_level_markup_val = $safe_search_data['data']['markup_value'];
				if($search_level_markup_val!='' || $search_level_markup_val!=0){
				$search_level_markup_typ = $safe_search_data['data']['markup_type'];
				if($search_level_markup_typ=='plus'){
					$search_level_markup = $search_level_markup_val;
				}else{
					$search_level_markup = ($hd ['Price']['TotalDisplayFare']/100) * $search_level_markup_val;
				}
				}else{
					$search_level_markup = 0;
				}
				$this->get_commission($hd['Price'],$cobj);
				$admin_markup_details = $GLOBALS ['CI']->transfer_model->get_markup_for_admin ( $hd ['Price']['TotalDisplayFare'], $supplier, $cntry_code, $city_id, $product_id);
			//	debug($admin_markup_details);die;
				$agent_markup_details = $GLOBALS ['CI']->transfer_model->get_markup_for_agent ( $hd ['Price']['TotalDisplayFare'], $cntry_code, $city_id);
				$admin_price_details = $this->update_search_markup_currency ( $hd ['Price'], $cobj, $sid, true, false );
					// markup
				$admin_price_details['TotalDisplayFare'] = $hd ['Price']['TotalDisplayFare']+$admin_markup_details;
				$admin_price_details['NetFare'] = $hd ['Price']['TotalDisplayFare']+$admin_markup_details;
				$admin_price_details['_Markup'] = $admin_markup_details;
				$admin_price_details['_SearchMarkup'] = $search_level_markup;
				// debug($admin_price_details);
				$agent_price_details = $this->update_search_markup_currency ( $hd ['Price'], $cobj, $sid, $level_one, $current_domain );
				$agent_price_details['TotalDisplayFare'] = $hd ['Price']['TotalDisplayFare']+$agent_markup_details;
				$agent_price_details['NetFare'] = $hd ['Price']['TotalDisplayFare']+$agent_markup_details;
				$agent_price_details['_Markup'] = $agent_markup_details;
				$agent_price_details['_SearchMarkup'] = $search_level_markup;
				$hd ['Price'] = $this->b2b_price_details($api_price_details, $admin_price_details, $agent_price_details, $cobj, $total_pax);
				$hd ['Price']['TotalDisplayFare']=round($hd ['Price']['TotalDisplayFare']);
				//debug($hd ['Price']);die;
				// debug($agent_price_details);exit;

			}else{
					// markup
				//debug($current_domain);exit;
				$hd ['Price'] = $this->update_search_markup_currency ( $hd ['Price'], $cobj, $sid, $level_one, $current_domain, $module );
			//	debug($hd ['Price']);die;
			}
		
			// filter after initializing default data and adding markup
			if (valid_array ( $fltr ) == true && $check_filters ( $hd ) == false) {
				continue;
			}
			$TransferResults [$hr] = $hd;
			$frc ++;
		}
		// debug($fltr);exit;
		// SORTING STARTS
		if (isset ( $fltr ['sort_item'] ) == true && empty ( $fltr ['sort_item'] ) == false && isset ( $fltr ['sort_type'] ) == true && empty ( $fltr ['sort_type'] ) == false) {
			$sort_item = array ();
			// debug($TransferResults);exit;
			foreach ( $TransferResults as $key => $row ) {
				if ($fltr ['sort_item'] == 'price') {
					$sort_item [$key] = roundoff_price ( $row ['Price'] ['TotalDisplayFare'] );
				} else if ($fltr ['sort_item'] == 'star') {
					$sort_item [$key] = roundoff_price ( $row ['StarRating'] );
				} else if ($fltr ['sort_item'] == 'name') {
					$sort_item [$key] = trim ( $row ['transfer_name'] );
				}
			}
			if ($fltr ['sort_type'] == 'asc') {
				$sort_type = SORT_ASC;
			} else if ($fltr ['sort_type'] == 'desc') {
				$sort_type = SORT_DESC;
			}
			if (valid_array ( $sort_item ) == true && empty ( $sort_type ) == false) {
				array_multisort ( $sort_item, $sort_type, $TransferResults );
			}
		} // SORTING ENDS
		
		$sl ['SSSearchResult'] ['TransferResults'] = $TransferResults;
		$sl ['source_result_count'] = $sc;
		$sl ['filter_result_count'] = $frc;
		// debug($sl);exit;
		return $sl;
	}
	
	/**
	 * Break data into pages
	 * 
	 * @param
	 *        	$data
	 * @param
	 *        	$offset
	 * @param
	 *        	$limit
	 */
	function get_page_data($sl, $offset, $limit) {

		$sl ['SSSearchResult'] ['TransferResults'] = array_slice ( $sl ['SSSearchResult'] ['TransferResults'], $offset, $limit );
		return $sl;
	}
	
	/**
	 * Get Filter Summary of the data list
	 * 
	 * @param array $sl        	
	 */
	function filter_summary($sl) {
		$s_count = 0;
		$filt ['p'] ['max'] = false;
		$filt ['p'] ['min'] = false;
		// debug($sl);exit;
		$filt ['star'] = array ();
		$filters = array ();
		foreach ( $sl ['SSSearchResult'] ['TransferResults'] as $hr => $hd ) {
			// filters
			/*if($hd['booking_source']==PROVAB_TRANSFER_SOURCE_CRS)
			{
            $transfer_price = $hd ['Price'] ['TotalDisplayFare'];
        	}else
        	{
			$agent_base_currency = agent_base_currency();
                $currency_obj_m = new Currency(array('module_type' => 'transfers', 'from' => $hd['currency'], 'to' => $agent_base_currency));
            $strCurrency_m  = $currency_obj_m->get_currency($hd ['Price'] ['TotalDisplayFare'], true, false, true, false, 1);
            $transfer_price = $strCurrency_m['default_value'];
            // debug($hd['currency']);exit;

        	}*/
            // debug($transfer_price);
			$StarRating = intval (@$hd ['StarRating']);			
			
			if (isset ( $filt ['star'] [$StarRating] ) == false) {
				$filt ['star'] [$StarRating] ['c'] = 1;
				$filt ['star'] [$StarRating] ['v'] = $StarRating;
			} else {
				$filt ['star'] [$StarRating] ['c'] ++;
			}			
			if (($filt ['p'] ['max'] != false && $filt ['p'] ['max'] < $hd ['Price'] ['TotalDisplayFare']) || $filt ['p'] ['max'] == false) {
				$filt ['p'] ['max'] = roundoff_price ( $hd ['Price'] ['TotalDisplayFare'] );
			}
			if (($filt ['p'] ['min'] != false && $filt ['p'] ['min'] > $hd ['Price'] ['TotalDisplayFare']) || $filt ['p'] ['min'] == false) {
				$filt ['p'] ['min'] = roundoff_price ( $hd ['Price'] ['TotalDisplayFare'] );
			}			
			if (($filt ['p'] ['min'] != false && $filt ['p'] ['min'] > $hd ['Price'] ['TotalDisplayFare']) || $filt ['p'] ['min'] == false) {
				$filt ['p'] ['min'] = $hd ['Price'] ['TotalDisplayFare'];
			}		
			
			$filters ['data'] = $filt;
			$s_count ++;
		}		
		$filters ['sightseeing_count'] = $s_count;
		// debug($filters);
		// exit;
		return $filters;
	}
	/**
	*Get Sightseeing Pending Booking Status
	*/
	public function get_sightseeing_booking_status($app_reference){
		$header = $this->get_header ();
		$response ['data'] = array ();
		$response ['status'] = true;
		//UpdateHoldBooking
		$this->credentials('UpdateHoldBooking');
		 $service_url = $this->service_url;
		 if($app_reference !=''){
		 	$get_hold_booking_request = array('app_reference'=>$app_reference);

		 	$request = json_encode($get_hold_booking_request);
		 	$get_hb_status = $GLOBALS['CI']->api_interface->get_json_response ( $service_url,$request, $header );
		 	if($get_hb_status['Status']==true){		 		
		 		//update booking status
		 		
		 		$booking_id = $get_hb_status['UpdateHoldBooking']['data']['booking_id'];
		 		$update_data['status'] = 'BOOKING_CONFIRMED';
		 		$update_data['booking_reference'] = $booking_id;
		 		$update_data['confirmation_reference'] = $booking_id;

		 		$this->CI->custom_db->update_record('transfer_booking_details',$update_data,array('app_reference'=>$app_reference));
		 		$update_ite_data['status'] = 'BOOKING_CONFIRMED';
		 		$this->CI->custom_db->update_record('sightseeing_booking_itinerary_details',$update_ite_data,array('app_reference'=>$app_reference));
		 		$this->CI->custom_db->update_record('sightseeing_booking_pax_details',$update_ite_data,array('app_reference'=>$app_reference));

		 		$response ['data'] = array('booking_reference'=>$booking_id);
		 		$response['status'] = true;
		 		
		 	}else{
		 		$response['status'] = false;
		 	}
		 }
		 return $response;

	}
	function booking_url($search_id) {
		
	}
	//Elavarasi
	public function search_data($safe_search_data) {

// debug($safe_search_data);exit;
		$response ['status'] = true;
		$response ['data'] = array ();
		if (empty ( $this->master_search_data ) == true and valid_array ( $this->master_search_data ) == false) {
			if ($safe_search_data['to'] !='') {
				$response['status'] = true;
				$response['data']['from'] = $safe_search_data['from'];
			    $response['data']['to'] = $safe_search_data['to'];
			    $response['data']['from_date'] = $safe_search_data['from_date'];
			    $response['data']['from_code'] = $safe_search_data['from_code'];
			    $response['data']['to_code'] = $safe_search_data['to_code'];
			    $response['data']['from_transfer_type'] = $safe_search_data['from_transfer_type'];
			    $response['data']['to_transfer_type'] = $safe_search_data['to_transfer_type'];
			    $response['data']['adult'] = $safe_search_data['adult'];
			    $response['data']['child'] = $safe_search_data['child'];
			    $response['data']['depature_time_flight'] = $safe_search_data['depature_time_flight']; 
			    $response['data']['depature'] = $safe_search_data['depature'];
			    $response['data']['adult_ages'] = $safe_search_data['adult_ages'][0];
			    $response['data']['trip_type'] = $safe_search_data['trip_type'];
			    if($safe_search_data['trip_type']=='circle')
			    {
			    	$response['data']['to_date'] = $safe_search_data['to_date'];
			    	$response['data']['return_time_flight'] = $safe_search_data['return_time_flight'];
			    	$response['data']['return'] = $safe_search_data['return'];
			    }
				$this->master_search_data = $response ['data'];
			} else {
				$response ['status'] = false;
			}
		} else {
			$response ['data'] = $this->master_search_data;
		}		
		$this->search_hash = md5 ( serialized_data ( $response ['data'] ) );
		// debug($response);exit;
		return $response;
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
	function cancel_booking($booking_details,$cancel_params)
    {
        $header = $this->get_header ();
        $response ['data'] = array ();
        $response ['status'] = FAILURE_STATUS;
        $resposne ['msg'] = 'Remote IO Error';
        $BookingId = $booking_details ['booking_id'];
        $app_reference = $booking_details ['app_reference'];
    
        
                $currency_obj = new Currency(array('module_type' => 'transferv1', 'from' => get_api_data_currency(), 'to' => admin_base_currency()));

                // Save Cancellation Details//Converting to application currency
                $transfer_cancellation_details = array ();		
				$transfer_cancellation_details ['ChangeRequestId'] = 0;
				$transfer_cancellation_details ['ChangeRequestStatus'] = 1;
				$transfer_cancellation_details ['StatusDescription'] = 0;
				$transfer_cancellation_details ['RefundedAmount'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( 1 ) );		
				$transfer_cancellation_details ['CancellationCharge'] = get_converted_currency_value ( $currency_obj->force_currency_conversion ( 1 ) );
				$transfer_cancellation_details ['status'] = 1;
				// debug($transfer_cancellation_details);exit;
                $GLOBALS ['CI']->transfers_model->update_cancellation_details ( $app_reference, $transfer_cancellation_details );
                $response ['status'] = SUCCESS_STATUS;
                
                $response ['msg'] = $cancel_booking_response['Message'];
            
        return $response;
    }
}
