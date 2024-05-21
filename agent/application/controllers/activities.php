<?php
if (! defined ( 'BASEPATH' ))
exit ( 'No direct script access allowed' );
//error_reporting(E_ALL);
class Activities extends CI_Controller {
	public function __construct() {
		parent::__construct ();
		$current_url = $_SERVER ['QUERY_STRING'] ? '?' . $_SERVER ['QUERY_STRING'] : '';
		$current_url = $this->config->site_url () . $this->uri->uri_string () . $current_url;
		$url = array (
				'continue' => $current_url 
		);
		$this->session->set_userdata ( $url );
		$this->helpMenuLink = "";
		$this->load->model ( 'Help_Model' );
		$this->helpMenuLink = $this->Help_Model->fetchHelpLinks ();
		$this->load->model ( 'Package_Model' );
		$this->load->model('Domain_Management_Model');
		$this->load->model('module_model');
	$this->load->model('activity_model');	
		$this->load->library('rewards');
		$this->load->model('sightseeing_model');
		$this->load->library('provab_mailer');
		// ini_set('display_errors', 1);
		// ini_set('display_startup_errors', 1);
		// error_reporting(E_ALL);
		// $this->load->library ('sightseeing/sightseeing_crs','sightseeing_lib');
	}

	/**
	 * get all tours
	 */
	public function index() {
		$data ['packages'] = $this->Package_Model->getAllPackages ();
		$data ['countries'] = $this->Package_Model->getPackageCountries ();
		$data ['package_types'] = $this->Package_Model->getPackageTypes ();
		if (! empty ( $data ['packages'] )) {
			$this->template->view ( 'activities/tours', $data );
		} else {
			redirect ();
		}
	}
	function change_transport_type()
	{
		$total_price = $this->input->post('total_price');
		$diff_price = $this->input->post('diff_price');
		$totalprice = $total_price + $diff_price;
		$totalPrice = isset($totalprice)? number_format($totalprice, 2):0;
		// debug($totalPrice);exit;
		echo $totalPrice;
		// $cancellation_policy = $GLOBALS['CI']->sightseeing_model->get_cancellation_policy($pkg_id,$travel_date)

	}
	/**
	 * get the package details
	 */

function pre_cancellation($app_reference, $booking_source, $status = '')
	{
$this->load->model ( 'activity_model' );
	 // debug($app_reference);exit();
		if (empty($app_reference) == false && empty($booking_source) == false) {
			// echo "in";die;
			$page_data = array();
			if($booking_source==PACKAGE_BOOKING_SOURCE){
// echo "in2";die;
					if($app_reference!=''){
						// echo "in3";die;
						$resp = $this->activity_model->activity_crs_cancel_request($app_reference);
						// echo $this->db->last_query();die;
						// debug($resp['status']);exit;
						// $resp2 = json_decode($resp,true);
						// echo "in";
						// debug($resp2);exit;
						if($resp['status']){
							/*sending mail to customer*/
							
							$booking_details = $this->activity_model->getBookingDetails($app_reference);
							// echo $this->db->last_query();die;
							$email = $booking_details[0]->email_id;
							// $voucher_details['other'] = $this->activity_model->get_voucher_details($app_reference);
							// debug($voucher_details['other'] );exit;
							
							// $this->load->library('provab_pdf');
							// $this->load->library('provab_mailer');
							// $create_pdf = new Provab_Pdf();
							// $mail_template = $this->template->isolated_view('voucher/hotels_voucher_cancel', $voucher_details);
							//$pdf = $create_pdf->create_pdf($mail_template,'');
							// $this->provab_mailer->send_mail($email, domain_name().' - Hotel Voucher',$mail_template);
							/*$this->load->library('provab_mailgun');
							$mail_status = $this->provab_mailgun->send_mail($email, 'Reservation Services -- Hotel Voucher',$mail_template );*/
							// echo '<script>alert("Hotel Booking Cancelled");window.close();</script>';
							// echo "end";die;
							redirect('report/activity');
						}
						
					}else{
						// echo "in22";die;
						 $response = ['status'=>0,"msg"=>"Error while cancelling package!!"];
						 echo json_encode($response); 
					}
					
			}else{
				// echo "out";die;
				redirect('security/log_event?event=Invalid Details');
			}
			
		} else {
			// echo "in44";die;
			redirect('security/log_event?event=Invalid Details');
		}
	}

function get_cancellation_policy_details()
	{
		$travel_date = $this->input->post('travel_date');
		$price_with_agent_markup = $this->input->post('price');
		$transfer_option = $this->input->post('transfer_option');
		$diff_price = $this->input->post('diff_price');
		$travel_date = trim ( date('Y-m-d',strtotime($travel_date)) );
		$pkg_id = $this->input->post('pkg_id');
		$date_duration = $this->input->post('date_duration');
		$activity_price = $this->custom_db->single_table_records('activity_price_management','*',array('activity_id'=>$pkg_id,'duration'=>$date_duration))['data'];
		// foreach ($activity_price as $key => $value) 
  	 //           {  
	 // 			$seasons_date = $value['duration'];
	 // 				$seasons_date_wise = explode(' - ', $seasons_date);
	 // 				$seasons1 = explode('/', $seasons_date_wise[0]);
	 // 				$fromSeasons=$seasons1[2].'-'.$seasons1[1].'-'.$seasons1[0];
	 // 				$seasons2 = explode('/', $seasons_date_wise[1]);
	 // 				$toSeasons=$seasons2[2].'-'.$seasons2[1].'-'.$seasons2[0];
	 // 				if( ($fromSeasons<=$travel_date)&&($toSeasons>=$travel_date)){
	 // 					$seasonsDateValue = $seasons_date_value;
	 // 					// $price_with_agent_markup = $value['']
	 // 					break;
	 					
	 // 				}
	 // 		}
		for($i=0;$i<count($activity_price);$i++){
		$transport = $activity_price[$i]['transfer_option'];
            if($transport=='W'){
              $transfer_desc='Without Transfers';
            }
            if($transport=='S'){
              $transfer_desc='Sharing Transfers';
            }
            if($transport=='P'){
              $transfer_desc='Private Transfers';
            }
            if($transport==$transfer_option){
              $status='selected';
            }else{$status='';}
          $transfer_type_option .= ' <option value="'.$transport.'" '.$status.' >'.$transfer_desc.'</option>';
          }
		$cancellation_policy = $GLOBALS['CI']->sightseeing_model->get_cancellation_policy($pkg_id,$travel_date);
		if(count($cancellation_policy)>0)
                {
                    $cancellation_available = 1;
                    for($i=0;$i<count($cancellation_policy);$i++)
                    {
                        if($number_of_days==$cancellation_policy[$i]['no_of_days'] || $number_of_days<$cancellation_policy[$i]['no_of_days'])
                        {
                        	  $startdate=date('Y-m-d', strtotime('-'.$cancellation_policy[$i]['no_of_days'].' days', strtotime($travel_date)));
                            if($cancellation_policy[$i]['charge_type']=='%')
                            {
                                $cancellation_amount = ($price_with_agent_markup*$cancellation_policy[$i]['amount'])/100;
                              //  $cancellation_details = 'Cancellation from today will charge NPR'.$cancellation_amount;
                                 $cancellation_details = 'Cancellation from '.$startdate.' will charge NPR'.$cancellation_amount;
                            }
                            else if($cancellation_policy[$i]['charge_type']=='Amount')
                            {
                                $cancellation_amount = $cancellation_policy[$i]['amount'];
                             //   $cancellation_details = 'Cancellation from today will charge NPR'.$cancellation_amount;
                                 $cancellation_details = 'Cancellation from '.$startdate.' will charge NPR'.$cancellation_amount;
                            }
                            break;      
                        }
                        else
                        { 
                            $k = count($cancellation_policy)-1;
                            if($i==$k)
                            {
                                $startdate=date('Y-m-d', strtotime('-'.$cancellation_policy[$i]['no_of_days'].' days', strtotime($travel_date)));
                                if($cancellation_policy[$i]['charge_type']=='%')
                                {
                                    $cancellation_amount = ($price_with_agent_markup*$cancellation_policy[$i]['amount'])/100;
                                    $cancellation_details = 'Cancellation from '.$startdate.' will charge NPR'.$cancellation_amount;
                                }
                                else if($cancellation_policy[$i]['charge_type']=='Amount')
                                {
                                    $cancellation_amount = $cancellation_policy[$i]['amount'];
                                    $cancellation_details = 'Cancellation from '.$startdate.' will charge NPR'.$cancellation_amount;
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

                echo $cancellation_details.'*'.$cancellation_available.'*'.$transfer_type_option;

	}
	public function details($package_id, $enquiry_staus=0) 
	{ 
		 //error_reporting(E_ALL);
	

		$safe_search_data = $this->sightseeing_model->get_safe_search_data($enquiry_staus,META_SIGHTSEEING_COURSE);
//	debug($safe_search_data['data']['destination_id']);die;
		//$this->load->model('activity_model');	
        $country_city_data = $this->activity_model->get_country_city($safe_search_data['data']['destination_id']);
		//	debug($country_city_data);die;
         $cntry_code = $country_city_data[0]['country_code'];
        $city_id = $safe_search_data['data']['destination_id'];

		$search_currency=$safe_search_data['data']['nationality'];
		$country_id = $GLOBALS['CI']->sightseeing_model->get_country_id( $search_currency );
        $total_pax=0;
        if($safe_search_data['status']){
        	if(isset($safe_search_data['data']['adult'])){
        		$total_pax+=$safe_search_data['data']['adult'];
        	}
        	if(isset($safe_search_data['data']['child'])){
        		$total_pax+=$safe_search_data['data']['child'];
        	}
        }
        // debug($total_pax);exit;
		$data['enquire_status'] = $enquiry_staus;
		$search_id=$this->uri->segment(4);
		$data ['package'] = $this->Package_Model->getPackage($package_id);

		$package_formate = $this->Package_Model->getPackageCRS($package_id);
// debug($package_formate);exit;
		foreach ($package_formate as $key => $value) 
            {  
	 			$seasons_date = json_decode($value->seasons_date);
	 			foreach ($seasons_date as $key => $seasons_date_value) {
	 				$seasons_date_wise = explode(' - ', $seasons_date_value);
	 				$seasons1 = explode('/', $seasons_date_wise[0]);
	 				$fromSeasons=$seasons1[2].'-'.$seasons1[1].'-'.$seasons1[0];
	 				$seasons2 = explode('/', $seasons_date_wise[1]);
	 				$toSeasons=$seasons2[2].'-'.$seasons2[1].'-'.$seasons2[0];
	 				$fromDate=date('Y-m-d',strtotime($safe_search_data['data']['from_date']));
	 				$toDate=date('Y-m-d',strtotime($safe_search_data['data']['to_date']));
	 				if( ($fromSeasons<=$fromDate)&&($toSeasons>=$fromDate) || ($fromSeasons<=$toDate)&&($toSeasons>=$toDate) || ($fromSeasons<=$fromDate)&&($toSeasons<=$toDate)&&($toSeasons>=$fromDate)){
	 					$seasonsDateValue = $seasons_date_value;
	 					break;
	 					
	 				}
	 			}
	 		}
		// debug($seasonsDateValue);exit;
		$data ['package_itinerary'] = $this->Package_Model->getPackageItinerary ( $package_id );
		$data ['package_price_policy'] = $this->Package_Model->getPackagePricePolicy ( $package_id );
		$data ['package_cancel_policy'] = $this->Package_Model->getPackageCancelPolicy ( $package_id );
		$data['activity_dates'] = $this->custom_db->single_table_records('activity_available_dates','*',array('activity_id'=>$package_id))['data'];
		$data['activity_price'] = $this->custom_db->single_table_records('activity_price_management','*',array('activity_id'=>$package_id))['data'];
		// debug($safe_search_data['data']['country_code']);exit;
		$data['sub_activity_list'] = $this->custom_db->single_table_records('sub_activity_timing','*',array('activity_id'=>$package_id))['data'];
		// debug($data);exit;
		// $data ['package_traveller_photos'] = $this->Package_Model->getTravellerPhotos ( $package_id );
		//$currency_obj = new Currency(array('module_type' => 'sightseeing','from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
		$currency_display= get_application_currency_preference();
		$currency_obj = new Currency(array('module_type' => 'sightseeing','from' => $currency_display, 'to' => get_application_currency_preference()));
	////	echo get_application_currency_preference();
	//	echo get_application_currency_preference();
	//	die;
		$data['currency_obj'] = $currency_obj;
		$search_result=array();
		if (! empty ( $package_formate )) 
		{	

			foreach ($package_formate as $key => $value) 
            {
        		$package_price = $GLOBALS['CI']->sightseeing_model->get_package_price_by_app_currency_nationality($value->package_id,$safe_search_data['data']['nationality']);
            			$transfer_price_ary = array();
						$total_without_transfers=0;
						$total_sharing_transfers=0;
						$total_private_transfers=0;
					foreach ($package_price as $key => $price) {

					$supplier = 'Voyage CRS';
					$product_id = $value->package_id;




				// 		$admin_markup_details = $this->activity_model->get_markup_for_admin ( $hd ['Price']['TotalDisplayFare'], $supplier, $cntry_code, $city_id, $product_id);
    //             $admin_price_details['Currency'] = 'NPR';
    //             $admin_price_details['TotalDisplayFare'] = $hd ['Price']['TotalDisplayFare']+$admin_markup_details;
    //             $admin_price_details['AgentCommission'] = 0;
    //             $admin_price_details['AgentTdsOnCommision'] = 0;
    //             $admin_price_details['NetFare'] = $hd ['Price']['TotalDisplayFare']+$admin_markup_details;
    //             $admin_price_details['ORG_AgentCommission'] = 0;
    //             $admin_price_details['ORG_TdsOnCommission'] = 0;
    //             $admin_price_details['ORG_NetFare'] = 0;
    //             $admin_price_details['ORG_AdminCommission'] = 0;
    //             $admin_price_details['_GST'] = 0;
    //             $admin_price_details['_Markup'] = $admin_markup_details;

				// 	// markup
				//   // $agent_price_details = $this->update_search_markup_currency ( $hd ['Price'], $cobj, $sid, $level_one, $current_domain );
    //             $search_level_markup_val = $safe_search_data_markup['data']['markup_value'];
    //             if($search_level_markup_val!='' || $search_level_markup_val!=0){
    //             $search_level_markup_typ = $safe_search_data_markup['data']['markup_type'];
    //             if($search_level_markup_typ=='plus'){
    //                 $search_level_markup = $search_level_markup_val;
    //             }else{
    //                 $search_level_markup = ($admin_price_details['TotalDisplayFare']/100) * $search_level_markup_val;
    //             }
    //             }else{
    //                 $search_level_markup = 0;
    //             }
    //             $agent_markup_details =  $this->activity_model->agent_markup ( $hd ['Price']['TotalDisplayFare'], $cntry_code, $city_id);
    //             $agent_price_details['Currency'] = 'NPR';
    //             $agent_price_details['TotalDisplayFare'] = $hd ['Price']['TotalDisplayFare']+$agent_markup_details+$search_level_markup;
    //             $agent_price_details['AgentCommission'] = 0;
    //             $agent_price_details['AgentTdsOnCommision'] = 0;
    //             $agent_price_details['NetFare'] = $hd ['Price']['TotalDisplayFare']+$agent_markup_details+$search_level_markup;
    //             $agent_price_details['ORG_AgentCommission'] = 0;
    //             $agent_price_details['ORG_TdsOnCommission'] = 0;
    //             $agent_price_details['ORG_NetFare'] = 0;
    //             $agent_price_details['ORG_AdminCommission'] = 0;
    //             $agent_price_details['_GST'] = 0;
    //             $agent_price_details['_Markup'] = $agent_markup_details+$search_level_markup;
				 

				//   debug($agent_price_details);
				//  exit();

				// $hd ['Price'] = $this->b2b_price_details($api_price_details, $admin_price_details, $agent_price_details, $cobj);







						if($price['transfer_option']=='W'){
							$adult_total_price = $price['adult_price']*$safe_search_data['data']['adult'];
							$child_total_price = $price['child_price']*$safe_search_data['data']['child'];
							$total_without_transfers = $adult_total_price+$child_total_price;
							$transfer_price_ary[] = array('transfer_option'=>$price['transfer_option'],'total_price'=>$total_without_transfers,'duration'=>$price['duration']);
						}
						if($price['transfer_option']=='S'){
							$adult_total_price = $price['adult_price']*$safe_search_data['data']['adult'];
							$child_total_price = $price['child_price']*$safe_search_data['data']['child'];
							$total_sharing_transfers = $adult_total_price+$child_total_price;
							$transfer_price_ary[] = array('transfer_option'=>$price['transfer_option'],'total_price'=>$total_sharing_transfers,'duration'=>$price['duration']);	
						}
						if($price['transfer_option']=='P'){
							$adult_total_price = $price['adult_price']*$safe_search_data['data']['adult'];
							$child_total_price = $price['child_price']*$safe_search_data['data']['child'];
							$total_private_transfers = $adult_total_price+$child_total_price;
							$transfer_price_ary[] = array('transfer_option'=>$price['transfer_option'],'total_price'=>$total_private_transfers,'duration'=>$price['duration']);
						}
						if($price['duration']==$seasonsDateValue){
							if($total_without_transfers>0){
							$package_price = $total_without_transfers; 
							$transfer_type = 'W';
							}else if($total_sharing_transfers>0){
								$package_price = $total_sharing_transfers; 
								$transfer_type = 'S';
							}else{
								$package_price = $total_private_transfers;
								$transfer_type = 'P';
							}
						}
					}
					

        		//$package_price = $GLOBALS['CI']->sightseeing_model->get_package_price_by_app_currency($value->package_id);
            	$key=$key.'crs';
                $search_result['data']['SSSearchResult']['SightSeeingResults'][$key]['ProductName']=$value->package_name;
                $search_result['data']['SSSearchResult']['SightSeeingResults'][$key]['ProductCode']=$value->package_id;
                $search_result['data']['SSSearchResult']['SightSeeingResults'][$key]['ImageUrl']=$GLOBALS['CI']->template->domain_upload_pckg_images($value->image);
                $search_result['data']['SSSearchResult']['SightSeeingResults'][$key]['ImageHisUrl']=$GLOBALS['CI']->template->domain_upload_pckg_images($value->image);
                $search_result['data']['SSSearchResult']['SightSeeingResults'][$key]['BookingEngineId']='ProvabCrs';
                $search_result['data']['SSSearchResult']['SightSeeingResults'][$key]['Promotion']='';
                $search_result['data']['SSSearchResult']['SightSeeingResults'][$key]['PromotionAmount']=0;
                $search_result['data']['SSSearchResult']['SightSeeingResults'][$key]['StarRating']=$value->rating;
                $search_result['data']['SSSearchResult']['SightSeeingResults'][$key]['DestinationName']=$value->package_location;
                $search_result['data']['SSSearchResult']['SightSeeingResults'][$key]['transport_price']=$transfer_price_ary;
                $search_result['data']['SSSearchResult']['SightSeeingResults'][$key]['transfer_type']=$transfer_type;
                $search_result['data']['SSSearchResult']['SightSeeingResults'][$key]['seasonsDateValue']=$seasonsDateValue;
                $search_result['data']['SSSearchResult']['SightSeeingResults'][$key]['Price']=array(
                                            'TotalDisplayFare'=>($package_price),
                                            'GSTPrice'=>0,
                                            'PriceBreakup'=>array(
                                                'AgentCommission'=>0,
                                                'AgentTdsOnCommision'=>0
                                            ),
                                            'Currency'=>admin_base_currency()
                );
                $search_result['data']['SSSearchResult']['SightSeeingResults'][$key]['Description']=$value->package_description;
                $search_result['data']['SSSearchResult']['SightSeeingResults'][$key]['Cancellation_available']='';
                $search_result['data']['SSSearchResult']['SightSeeingResults'][$key]['Cat_Ids']=array();
                $search_result['data']['SSSearchResult']['SightSeeingResults'][$key]['Sub_Cat_Ids']=array();
                $search_result['data']['SSSearchResult']['SightSeeingResults'][$key]['Supplier_Code']=$value->supplier_id;
                $search_result['data']['SSSearchResult']['SightSeeingResults'][$key]['Duration']=$value->duration;
                $search_result['data']['SSSearchResult']['SightSeeingResults'][$key]['Hours']=sprintf("%02d",$value->activity_duration_hour).':'.sprintf("%02d",$value->activity_duration_min).' Hrs';
                $search_result['data']['SSSearchResult']['SightSeeingResults'][$key]['ResultToken']=base64_encode(json_encode($value));
                $search_result['data']['SSSearchResult']['SightSeeingResults'][$key]['booking_source']=PROVAB_SIGHTSEEN_SOURCE_CRS;
            }
					
			$search_result['Status']=1;
			$search_result['Message']='';

			// debug($search_result['data']['SSSearchResult']['SightSeeingResults']);exit();
			  load_sightseen_lib(PROVAB_SIGHTSEEN_SOURCE_CRS);

			   //Converting API currency data to preferred currency
			  //debug(get_api_data_currency());exit();
           // $currency_obj = new Currency(array('module_type' => 'sightseeing', 'from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
            $currency_obj = new Currency(array('module_type' => 'sightseeing', 'from' => $currency_display, 'to' => get_application_currency_preference()));

 				
            
            $raw_sightseeing_result = $this->sightseeing_lib->search_data_in_preferred_currency($search_result, $currency_obj,'b2b');
           // debug($raw_sightseeing_result);exit;

             //$currency_obj = new Currency(array('module_type' => 'sightseeing', 'from' => get_application_currency_preference(), 'to' => get_application_currency_preference()));
            $currency_obj = new Currency(array('module_type' => 'sightseeing', 'from' =>$currency_display, 'to' => get_application_currency_preference()));
            $filters = array();    
          	$formated_data = $this->sightseeing_lib->format_search_response($raw_sightseeing_result['data'], $currency_obj, $search_id, 'b2b', $filters);
          	// debug($raw_sightseeing_result);exit;
 			$data['formated_data']=$formated_data['SSSearchResult']['SightSeeingResults'];
 			$data['currency_code']=$search_currency;
 			$data['package_id']=$package_id;
 			$data['search_data']=$safe_search_data['data'];
 			$data['key']=$key;
 			$data['booking_source']=$data['formated_data'][$key]['booking_source'];
 			$data['temp_token']=$this->module_model->serialize_temp_booking_record($data, 'SB');
 			$data['search_id']=$search_id;
 			// debug($data['package']->amenities);exit;
 			$amenities_det = $data['package']->amenities;
 			$amenities=json_decode($amenities_det);
 			// foreach($amenities as $k => $val)
    //             {
    //             	//debug($val);exit;
  		// 		 $data['amenities_list'] = $this->Package_Model->getamenities($val);
    //             }
              //  debug( $data['amenities_list']);
 			// debug( $data);exit;
			$this->template->view ( 'activities/tours_detail', $data );

		} else {
			redirect ( "activities/index" );
		}
	}
	public function detailsold($package_id, $enquiry_staus=0) 
	{ 
		
		$data['enquire_status'] = $enquiry_staus;
		$search_id=$this->uri->segment(4);
		$data ['package'] = $this->Package_Model->getPackage ( $package_id );
		// echo "string";exit;
		$package_formate = $this->Package_Model->getPackageCRS( $package_id ,$module_type="activity");
		// debug($package_formate);exit;
		$data ['package_itinerary'] = $this->Package_Model->getPackageItinerary ( $package_id );
		$data ['package_price_policy'] = $this->Package_Model->getPackagePricePolicy ( $package_id );
		$data ['package_cancel_policy'] = $this->Package_Model->getPackageCancelPolicy ( $package_id );
		$data ['package_traveller_photos'] = $this->Package_Model->getTravellerPhotos ( $package_id );
		$currency_obj = new Currency(array('module_type' => 'sightseeing','from' => ADMIN_BASE_CURRENCY_STATIC, 'to' => get_application_currency_preference()));
		$data['currency_obj'] = $currency_obj;
        $search_result=array();
		if (! empty ( $package_formate )) 
		{	

			foreach ($package_formate as $key => $value) 
            {
            	$key=$key.'crs';
                $search_result['data']['SSSearchResult']['SightSeeingResults'][$key]['ProductName']=$value->package_name;
                $search_result['data']['SSSearchResult']['SightSeeingResults'][$key]['ProductCode']=$value->package_id;
                $search_result['data']['SSSearchResult']['SightSeeingResults'][$key]['ImageUrl']=$GLOBALS['CI']->template->domain_upload_pckg_images($value->image);
                $search_result['data']['SSSearchResult']['SightSeeingResults'][$key]['ImageHisUrl']=$GLOBALS['CI']->template->domain_upload_pckg_images($value->image);
                $search_result['data']['SSSearchResult']['SightSeeingResults'][$key]['BookingEngineId']='ProvabCrs';
                $search_result['data']['SSSearchResult']['SightSeeingResults'][$key]['Promotion']='';
                $search_result['data']['SSSearchResult']['SightSeeingResults'][$key]['PromotionAmount']=0;
                $search_result['data']['SSSearchResult']['SightSeeingResults'][$key]['StarRating']=$value->rating;
                $search_result['data']['SSSearchResult']['SightSeeingResults'][$key]['DestinationName']=$value->package_location;
                $search_result['data']['SSSearchResult']['SightSeeingResults'][$key]['Price']=array(
                                            'TotalDisplayFare'=>$value->price,
                                            'GSTPrice'=>0,
                                            'PriceBreakup'=>array(
                                                'AgentCommission'=>0,
                                                'AgentTdsOnCommision'=>0
                                            ),
                                            'Currency'=>admin_base_currency()
                );
                $search_result['data']['SSSearchResult']['SightSeeingResults'][$key]['Description']=$value->package_description;
                $search_result['data']['SSSearchResult']['SightSeeingResults'][$key]['Cancellation_available']='';
                $search_result['data']['SSSearchResult']['SightSeeingResults'][$key]['Cat_Ids']=array();
                $search_result['data']['SSSearchResult']['SightSeeingResults'][$key]['Sub_Cat_Ids']=array();
                $search_result['data']['SSSearchResult']['SightSeeingResults'][$key]['Supplier_Code']=$value->supplier_id;
                $search_result['data']['SSSearchResult']['SightSeeingResults'][$key]['Duration']=$value->duration.' Days';
                $search_result['data']['SSSearchResult']['SightSeeingResults'][$key]['ResultToken']=base64_encode(json_encode($value));
                $search_result['data']['SSSearchResult']['SightSeeingResults'][$key]['booking_source']=PROVAB_SIGHTSEEN_SOURCE_CRS;
            }
					
			$search_result['Status']=1;
			$search_result['Message']='';
			load_sightseen_lib(PROVAB_SIGHTSEEN_SOURCE_CRS);
			$currency_obj = new Currency(array('module_type' => 'sightseeing', 'from' => ADMIN_BASE_CURRENCY_STATIC, 'to' => get_application_currency_preference()));
            $raw_sightseeing_result = $this->sightseeing_lib->search_data_in_preferred_currency($search_result, $currency_obj,'b2c');

            $currency_obj = new Currency(array('module_type' => 'sightseeing', 'from' => get_application_currency_preference(), 'to' => get_application_currency_preference()));

            $filters = array();                       
			$formated_data = $this->sightseeing_lib->format_search_response($raw_sightseeing_result['data'], $currency_obj, $search_id, 'b2c', $filters);
			$data['formated_data']=$formated_data['SSSearchResult']['SightSeeingResults'];
		//debug($data);exit();
			$this->template->view ( 'activities/activity_detail', $data );
		} else {
			redirect ( "activities/index" );
		}
	}


	public function details_11_01_2020($package_id, $enquiry_staus=0) 
	{
		$data['enquire_status'] = $enquiry_staus;
		$data ['package'] = $this->Package_Model->getPackage ( $package_id );
		$data ['package_itinerary'] = $this->Package_Model->getPackageItinerary ( $package_id );
		$data ['package_price_policy'] = $this->Package_Model->getPackagePricePolicy ( $package_id );
		$data ['package_cancel_policy'] = $this->Package_Model->getPackageCancelPolicy ( $package_id );
		$data ['package_traveller_photos'] = $this->Package_Model->getTravellerPhotos ( $package_id );
		$currency_obj = new Currency(array('module_type' => 'hotel','from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
		$data['currency_obj'] = $currency_obj;
		if (! empty ( $data ['package'] )) 
		{
			debug($data);die;
			$this->template->view ( 'activities/tours_detail', $data );
		} else {
			redirect ( "activities/index" );
		}
	}



	function format_search_response($sl, $cobj, $sid, $module = 'b2c', $fltr = array()) {
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
		$SightseeingResults = array ();
		// debug($fltr);
		// exit;
		// Creating closures to filter data
		$check_filters = function ($hd) use ($fltr) {
			if (

				(valid_array ( @$fltr ['cate'] ) == false ||
				(valid_array ( @$fltr ['cate'] ) == true && array_intersect($fltr ['cate'], $hd ['Cat_Ids']))) &&

				( isset($fltr['_sf']) == false || (valid_array ( @$fltr ['_sf'] ) == false || (valid_array ( @$fltr ['_sf'] ) == true && in_array ( $hd ['StarRating'], $fltr ['_sf'] ))) ) &&

				(@$fltr ['min_price'] <= roundoff_price ( $hd ['Price'] ['TotalDisplayFare'] ) && (@$fltr ['max_price'] != 0 && @$fltr ['max_price'] >= roundoff_price ( $hd ['Price'] ['TotalDisplayFare'] ))) &&

				(empty ( $fltr ['an_val'] ) == true || (empty ( $fltr ['an_val'] ) == false && stripos ( strtolower ( $hd ['ProductName'] ), (urldecode ( $fltr ['an_val'] )) ) > - 1)) &&

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
		foreach ( $sl ['SSSearchResult'] ['SightSeeingResults'] as $hr => $hd ) {
			$sc ++;
			// default values
			$hd ['StarRating'] = intval ( $hd ['StarRating'] );
			
			$api_price_details = $hd['Price'];
			
			if($module=='b2b'){
				$this->get_commission($hd['Price'],$cobj);
				$admin_price_details = $this->update_search_markup_currency ( $hd ['Price'], $cobj, $sid, true, false );
					// markup
				$agent_price_details = $this->update_search_markup_currency ( $hd ['Price'], $cobj, $sid, $level_one, $current_domain );

				$hd ['Price'] = $this->b2b_price_details($api_price_details, $admin_price_details, $agent_price_details, $cobj);
				

			}else{
					// markup
				$hd ['Price'] = $this->update_search_markup_currency ( $hd ['Price'], $cobj, $sid, $level_one, $current_domain, $module );
			}
		
			// filter after initializing default data and adding markup
			if (valid_array ( $fltr ) == true && $check_filters ( $hd ) == false) {
				continue;
			}
			$SightseeingResults [$hr] = $hd;
			$frc ++;
		}
		// SORTING STARTS
		if (isset ( $fltr ['sort_item'] ) == true && empty ( $fltr ['sort_item'] ) == false && isset ( $fltr ['sort_type'] ) == true && empty ( $fltr ['sort_type'] ) == false) {
			$sort_item = array ();
			foreach ( $SightseeingResults as $key => $row ) {
				if ($fltr ['sort_item'] == 'price') {
					$sort_item [$key] = roundoff_price ( $row ['Price'] ['TotalDisplayFare'] );
				} else if ($fltr ['sort_item'] == 'star') {
					$sort_item [$key] = roundoff_price ( $row ['StarRating'] );
				} else if ($fltr ['sort_item'] == 'name') {
					$sort_item [$key] = trim ( $row ['ProductName'] );
				}
			}
			if ($fltr ['sort_type'] == 'asc') {
				$sort_type = SORT_ASC;
			} else if ($fltr ['sort_type'] == 'desc') {
				$sort_type = SORT_DESC;
			}
			if (valid_array ( $sort_item ) == true && empty ( $sort_type ) == false) {
				array_multisort ( $sort_item, $sort_type, $SightseeingResults );
			}
		} // SORTING ENDS
		
		$sl ['SSSearchResult'] ['SightSeeingResults'] = $SightseeingResults;
		$sl ['source_result_count'] = $sc;
		$sl ['filter_result_count'] = $frc;
		
		return $sl;
	}





	public function enquiry() {
		$package_id = $this->input->post ( 'package_id' );
		$enquiry_reference_no = generate_holiday_reference_number('ZHI');
		if ($package_id !='') {
			$post_data = $this->input->post ();
			$enquiry_reference_no = ['enquiry_reference_no'=>$enquiry_reference_no];
            $data = array_merge($post_data, $enquiry_reference_no);
		    // debug($data);exit;	

			
			$package = $this->Package_Model->getPackage ( $package_id );

			$data ['phone'] = $data ['phone1'];
			$data ['place'] = $data ['place1'];
			$data ['message'] = $data ['message1'];

			unset($data ['phone1']);
			unset($data ['place1']);
			unset($data ['message1']); 

			$data ['package_name'] = $package->package_name;
			// $data ['enquiry_reference_no'] = $package->enquiry_reference_no;
			$data ['package_duration'] = $package->duration;
			$data ['package_type'] = $package->package_type;
			// $data ['with_or_without'] = $package->price_includes;
			// $data ['package_description'] =$package->package_description;
			$data ['ip_address'] = $this->session->userdata ( 'ip_address' );
			$data ['status'] = '0';
			$data ['date'] = date ( 'Y-m-d h:i:s' );
			$data ['domain_list_fk'] = get_domain_auth_id ();
			// debug($data);
			$result = $this->Package_Model->saveEnquiry ( $data );
			$data ['sucess'] = "Thank you for submitting your enquiry for this package, will get back to soon";
			redirect ( 'activities/details/' . $package_id.'/1' );
		} else {
			redirect ();
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
			$this->template->view('activities/search_result_page', array('hotel_search_params' => $safe_search_data['data'], 'active_booking_source' => $active_booking_source));
		} else {
			$this->template->view ( 'general/popup_redirect');
		}
	}

	public function search_old() {
		$data = $this->input->get ();
		$currency_obj = new Currency(array('module_type' => 'hotel','from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
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
			$this->template->view ( 'activities/tours', $data );
		} else {
			redirect ( 'activities/all_tours' );
		}
	}
	public function search() {
		
		$data = $this->input->get ();
		// debug($data);die;
		
		
		$currency_obj = new Currency(array('module_type' => 'hotel','from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
		// debug($currency_obj);die;
        //$daparturedate=$data ['departure_date'];
        
		//debug()
		//debug($data);exit;
		$daparturedate=@$data ['departure_date'];
        if(!empty($daparturedate)){
//$date = str_replace('/', '-', $daparturedate);
          $date1 =date('Y-m-d', strtotime($daparturedate));
          
	}
	else{
		$date1='';
		//echo "sooooooooooorrrajjjj";
          
	}
		if (! empty ( $data )) {
			$country = $data ['country'];
			$packagetype = $data ['package_type'];

			$daparturedate=@$data ['departure_date'];
			/*if ($data ['duration']) {
				$duration = explode ( '-', $data ['duration'] );
				if (count ( $duration ) > 1) {
					$duration = "duration between " . $duration ['0'] . " AND " . $duration ['1'];
				} else {
					$duration = "duration >" . $duration ['0'];
				}
			} else {
				$duration = $data ['duration'];
			}*/
			//echo 'herre'.$duration;exit;
			
			


			
			$domail_list_pk = get_domain_auth_id ();
			$data['currency_obj'] = $currency_obj;
			
			$data ['spackage_type'] = $packagetype;
			// debug($country);die;

			$data ['packages'] = $this->Package_Model->activitysearch ( $country, $packagetype,$domail_list_pk,$date1 );

			// debug($data);exit;
			$data ['caption'] = $this->Package_Model->getPageCaption ( 'tours_packages' )->row ();
			$data ['countries'] = $this->Package_Model->getPackageCountries ();
			$data ['package_types'] = $this->Package_Model->getPackageTypes ();
			$module_name='hotel';
		    $data['mark_up']=$this->Domain_Management_Model->get_markup($module_name);
			
			// debug($data['mark_up']);exit();
			$this->template->view ( 'activities/tours', $data );
		}
		
	
	}
	function interests(){

		$postedData  =$this->input->post('interestTypeId');
		$interestTypeId= json_decode(base64_decode($postedData));
		
		//$url = site_url().'/activities/search?country=all_country&package_type='.$interestTypeId.'&departure_date=';
				$url = site_url().'/activities/search?package_type='.$interestTypeId.'&country=&departure_date=';
		echo json_encode($url);
	}
	function destinations(){

		$postedData  =$this->input->post('destination_id');
		$destinationTypeId= json_decode(base64_decode($postedData));
		
		//$url = site_url().'/activities/search?country=all_country&package_type='.$interestTypeId.'&departure_date=';
				$url = site_url().'/activities/search?package_type=&country='.$destinationTypeId.'&departure_date=';
		echo json_encode($url);
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
			$this->template->view ( 'activities/tours', $data );
		} else {
			redirect ();
		}
	}

	public function book_packages() 
   {
   	    $post_params = $this->input->post();
        $booking_amount = base64_decode($post_params['booking_amount']);
    	$package_id=$post_params['package_id'];
        $data ['package'] = $this->Package_Model->getPackage ( $package_id );
        $data['no_adults'] = $post_params['no_adults'];
        $data['no_child'] = $post_params['no_child'];
        $data['no_infant'] = $post_params['no_infant'];
        $this->session->set_userdata( array('adult_count' => $post_params['no_adults']));
		$this->session->set_userdata( array('child_count' => $post_params['no_child']));
		$this->session->set_userdata( array('no_infant' => $post_params['no_infant']));
		$total_passengers=$post_params['no_adults']+$post_params['no_child']+$post_params['no_infant'];
		$currency_obj = new Currency(array('module_type' => 'sightseeing', 'from' => ADMIN_BASE_CURRENCY_STATIC, 'to' => get_application_currency_preference()));
		$pax_count = array(
			'adult'=>$data['no_adults'],
			'child'=>$data['no_child'],
			'infant'=>$data['no_infant'],
		);
		$pax_price = array(
			'adult'=>$data ['package']->price,
			'child'=>$data ['package']->child_price,
			'infant'=>$data ['package']->infant_price,
		);
		/////////////////////////////// MARKUP////////////////////////////////
		$passenger_count = array_sum($pax_count);
		$module_type = 'b2c_sightseeing';
        $markup_level = 'level_2';
        $markup = $this->private_management_model->fetch_markup_custom($module_type,$markup_level);
        
        //$data['total_amount'] = ($data['no_adults']*$data ['package']->price)+($data['no_child']*$data ['package']->child_price)+($data['no_infant']*$data ['package']->infant_price)+($markup['value']*$passenger_count);

        //$markup = isset($markup['value'])? get_converted_currency_value ( $currency_obj->force_currency_conversion ( $markup['value']) ):0; 
        $markup = $markup['value'];
		$convinence_fees_row = $this->private_management_model->get_convinence_fees('sightseeing');
		$adult_price = $pax_count['adult']*$pax_price['adult'];
		$child_price = 0;
		$infant_price = 0;
		if($pax_count['child']){
		$child_price = $data['no_child']*$pax_price['child'];	
		}if($pax_count['infant']){
		$infant_price = $data['no_infant']*$pax_price['infant'];	
		}
		/////////////////////////// GST //////////////////////////////////////
		$total_markup = $markup*$passenger_count;
		$gst = $this->private_management_model->fetch_gst($markup,'activities');
		$total_gst = $gst*$passenger_count;
		//$gst = isset($gst)? get_converted_currency_value ( $currency_obj->force_currency_conversion ( $gst) ):0;
		//////////////////////////// TOTAL AMOUNT WITHOUT CONVINENCE  ////////////////////////
		$total_amount_with_out_convinence_fee = $adult_price+$child_price+$infant_price+$total_markup+$total_gst;
		
		$activity_price = $adult_price+$child_price+$infant_price+$total_markup+$total_gst;
		// $activity_price = $adult_price+$child_price+$infant_price+$total_markup;
		// $activity_price = $adult_price+$child_price+$infant_price;
		
		$total_amount_with_out_convinence_fee = isset($total_amount_with_out_convinence_fee)? get_converted_currency_value ( $currency_obj->force_currency_conversion ( $total_amount_with_out_convinence_fee) ):0;
        // $total_amount_with_convinence_fee = $this->private_management_model->convinence_fee_calculation($data['total_amount'],$pax_price,$convinence_fees_row,$pax_count); // Dont delete
		
		/////////////////////////// CONVINENCE CALCULATION WITH MARK UP  ////////////////////////////
		$total_amount_with_convinence_fee = $this->private_management_model->convinence_fee_calculation_custom($activity_price,$convinence_fees_row,$currency_obj,$passenger_count);
		
		$total_amount_with_convinence_fee = isset($total_amount_with_convinence_fee)? get_converted_currency_value ( $currency_obj->force_currency_conversion ( $total_amount_with_convinence_fee) ):0;
		// debug($total_amount_with_convinence_fee); 
		// debug($total_amount_with_out_convinence_fee); exit();
        $convenience_fee = $total_amount_with_convinence_fee-$total_amount_with_out_convinence_fee;
        /////////////////////////////////////////////////////////////////////
		$data['currency'] = get_application_currency_preference();
		$data['date_of_travel'] = $post_params['date_of_travel'];
		$data['convenience_fee'] = $convenience_fee;
		$data['gst'] = $gst;
		$data['total_amount'] = $total_amount_with_out_convinence_fee;
		$data['grand_total'] = $total_amount_with_convinence_fee;
		$data ['activity_id'] =$package_id;
		////////////////////////////////////
		$this->template->view ( 'activities/booking', $data );
   	}
   	
 public function pre_booking() 
   {

		$post_data = $this->input->post();
		// debug($post_data);exit;
		$package_id 					=	$post_data['package_id'];
		$page_data['search_id']			=	$post_data['search_id'];
		$page_data['book_id']			=	$post_data['book_id'];   	  
		$page_data['temp_token']		=	$post_data['temp_token'];
		$page_data['date_of_travel']	=	$post_data['date_of_travel'];  
		$page_data['cancellationPolicy']	=	$post_data['cancellationPolicy'];
		$page_data['cancltnPlcy_avble']	=	$post_data['cancltnPlcy_avble'];   
		$page_data['package_id']		=	$package_id;
		$data['currency_code']			=	$post_data['currency_code'];
		$page_data['transport_type']			=	$post_data['transport_type'];
		$page_data['is_pickup_available']			=	$post_data['is_pickup_available'];
// debug($page_data['is_pickup_available']);die;
		$temp_booking_details			=	$this->module_model->unserialize_temp_booking_record($page_data['book_id'],$page_data['temp_token']);
		$page_data['package']			=	$temp_booking_details['book_attributes']['package'];
		$page_data["country"]			= 	$this->get_api_countries ();

		$page_data['currency_obj'] 		= 	new Currency(array('module_type' => 'sightseeing' ,'from' => $currency_code, 'to' => get_application_currency_preference()));

		$page_data["formated_data"]		=	$temp_booking_details['book_attributes']['formated_data'];
		$page_data['search_data']		=	$temp_booking_details['book_attributes']['search_data'];
		// debug($post_data);exit;
		$this->load->model('activity_model');
		$page_data['agent_balance'] = $this->activity_model->verify_agent_balance();
		$page_data['key']	= $key	=	$temp_booking_details['book_attributes']['key'];
		if($post_data['transport_type']!=$page_data["formated_data"][$key]['transfer_type'] || $post_data['seasonsDateValue']!=$post_data['seasonsDateValue_old']){
			$transfer_option = $page_data["formated_data"][$key]['transport_price'];
			for($i=0;$i<count($transfer_option);$i++){
				if(($transfer_option[$i]['transfer_option']==$post_data['transport_type']) && ($transfer_option[$i]['duration']==$post_data['seasonsDateValue'])){
					$val = $page_data["formated_data"][$key]['Price']['TotalDisplayFare'] - $page_data["formated_data"][$key]['Price']['_AdminBuying'];
					$page_data["formated_data"][$key]['Price']['_AdminBuying'] = $transfer_option[$i]['total_price'];
					$page_data["formated_data"][$key]['Price']['NetFare'] = $transfer_option[$i]['total_price']+$page_data["formated_data"][$key]['Price']['_AdminMarkup'];
					$page_data["formated_data"][$key]['Price']['_AgentBuying'] = $page_data["formated_data"][$key]['Price']['NetFare']+$page_data["formated_data"][$key]['Price']['_GST'];
					$val = $val+$transfer_option[$i]['total_price'];
					$page_data["formated_data"][$key]['Price']['TotalDisplayFare'] = $val;
				}
			}
		}
      	/*#############################FOR REWARDS SYSTEM #########################################*/
//	echo "test";
//	debug($page_data["formated_data"][$key]['Price']['_AgentBuying']);
				            $page_data ['total_price'] = ceil($page_data["formated_data"][$key]['Price']['_AgentBuying']);
						    $page_data['reward_usable'] = 0;
							$page_data['reward_earned'] = 0;
							$page_data ['total_price_with_rewards'] = 0;
						//	if(is_logged_in_user()){
									$user_id = $this->entity_user_id;
									//$data_rewards = $this->transaction->user_reward_details($user_id);
									$reward_values = $this->rewards->find_reward(META_SIGHTSEEING_COURSE,$page_data["formated_data"][$key]['Price']['_AgentBuying']);
								//	debug($reward_values);die;
									$reward_details = $this->rewards->get_reward_coversion_and_limit_details();	
									
									//$usable_rewards = $reward_values['usable_reward'];
									$usable_rewards = $this->rewards->usable_rewards($user_id,META_SIGHTSEEING_COURSE,$reward_values['usable_reward']);
									
									$page_data['reward_earned'] = $reward_values['earning_reward'];
								//	debug($reward_details);
								//	debug($usable_rewards);exit();
									 if($reward_values['usable_reward']<=$usable_rewards){
                                       $page_data['reward_usable'] = $reward_values['usable_reward'] ;
                                    }else{
                                        $page_data['reward_usable'] = 0;
                                    }
								//debug($page_data['reward_usable']);exit();
									if($page_data['reward_usable']){
										$reducing_amount = $this->rewards->find_reward_amount($page_data['reward_usable']);	
										$page_data ['total_price_with_rewards'] = $page_data["formated_data"][$key]['Price']['_AgentBuying']-$reducing_amount;
										$page_data['reducing_amount'] = round($reducing_amount);
									//	echo $page_data['reducing_amount'];
									///	die;
									}

						//	}

						/*#############################END #######################################################*/
   	  $this->template->view ( 'activities/pre_booking_traveler_details', $page_data );
   	  
		
   }
   // public function pre_booking($id) 
   public function pre_bookingold($id) 
   {
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
   	   $data = $this->input->post();

   	   $data['booking']=$data;
	   $package_formate = $this->Package_Model->getPackageCRS( $id ,$module_type="activity");
 	   $data ["country"] = $this->get_api_countries ();
 	   $data['activity_id'] = $id;
   	   // $currency_obj = new Currency(array('module_type' => 'sightseeing' ,'from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
   	   $currency_obj = new Currency(array('module_type' => 'sightseeing', 'from' => ADMIN_BASE_CURRENCY_STATIC, 'to' => get_application_currency_preference()));
   	   
	   $data['currency_obj'] = $currency_obj; 

	   $search_result=array();
		if (! empty ( $package_formate )) 
		{   
			foreach ($package_formate as $key => $value) 
            {
            	$key=$key.'crs';
                $search_result['data']['SSSearchResult']['SightSeeingResults'][$key]['ProductName']=$value->package_name;
                $search_result['data']['SSSearchResult']['SightSeeingResults'][$key]['ProductCode']=$value->package_id;
                $search_result['data']['SSSearchResult']['SightSeeingResults'][$key]['ImageUrl']=$GLOBALS['CI']->template->domain_upload_pckg_images($value->image);
                $search_result['data']['SSSearchResult']['SightSeeingResults'][$key]['ImageHisUrl']=$GLOBALS['CI']->template->domain_upload_pckg_images($value->image);
                $search_result['data']['SSSearchResult']['SightSeeingResults'][$key]['BookingEngineId']='ProvabCrs';
                $search_result['data']['SSSearchResult']['SightSeeingResults'][$key]['Promotion']='';
                $search_result['data']['SSSearchResult']['SightSeeingResults'][$key]['PromotionAmount']=0;
                $search_result['data']['SSSearchResult']['SightSeeingResults'][$key]['StarRating']=$value->rating;
                $search_result['data']['SSSearchResult']['SightSeeingResults'][$key]['DestinationName']=$value->package_location;
                $search_result['data']['SSSearchResult']['SightSeeingResults'][$key]['Price']=array(
                                            'TotalDisplayFare'=>$value->price,
                                            'GSTPrice'=>0,
                                            'PriceBreakup'=>array(
                                                'AgentCommission'=>0,
                                                'AgentTdsOnCommision'=>0
                                            ),
                                            'Currency'=>admin_base_currency()
                );
                $search_result['data']['SSSearchResult']['SightSeeingResults'][$key]['Description']=$value->package_description;
                $search_result['data']['SSSearchResult']['SightSeeingResults'][$key]['Cancellation_available']='';
                $search_result['data']['SSSearchResult']['SightSeeingResults'][$key]['Cat_Ids']=array();
                $search_result['data']['SSSearchResult']['SightSeeingResults'][$key]['Sub_Cat_Ids']=array();
                $search_result['data']['SSSearchResult']['SightSeeingResults'][$key]['Supplier_Code']=$value->supplier_id;
                $search_result['data']['SSSearchResult']['SightSeeingResults'][$key]['Duration']=$value->duration.' Days';
                $search_result['data']['SSSearchResult']['SightSeeingResults'][$key]['ResultToken']=base64_encode(json_encode($value));
                $search_result['data']['SSSearchResult']['SightSeeingResults'][$key]['booking_source']=PROVAB_SIGHTSEEN_SOURCE_CRS;
            }
					
			$search_result['Status']=1;
			$search_result['Message']='';
 

			load_sightseen_lib(PROVAB_SIGHTSEEN_SOURCE_CRS);

			//Converting API currency data to preferred currency
            // $currency_obj = new Currency(array('module_type' => 'sightseeing', 'from' => get_api_data_currency(), 'to' => get_application_currency_preference()));

           $currency_obj = new Currency ( array (
					'module_type' => 'sightseeing',
					'from' => ADMIN_BASE_CURRENCY_STATIC,
					'to' => get_application_default_currency () 
			)); 
            $raw_sightseeing_result = $this->sightseeing_lib->search_data_in_preferred_currency($search_result, $currency_obj,'b2c');            

           /*  $currency_obj = new Currency(array('module_type' => 'sightseeing', 'from' => get_application_currency_preference(), 'to' => get_application_currency_preference()));*/

			$currency_obj = new Currency ( array (
					'module_type' => 'sightseeing',
					'from' => ADMIN_BASE_CURRENCY_STATIC,
					'to' => get_application_default_currency () 
			)); 

            $filters = array();   

 
            //adding convenience fee
            $formated_data=$raw_sightseeing_result['data'];
           
             $total_amt=0; 
            if($formated_data['SSSearchResult']['SightSeeingResults'])
            {
            	$total_amt=$formated_data['SSSearchResult']['SightSeeingResults']['0crs']['Price']['TotalDisplayFare'];
            } 
         
            $page_data['convenience_fees']  = $currency_obj->convenience_fees($total_amt, $ageband_details); 

          $converted_con_fee = isset($page_data['convenience_fees'])? number_format(get_converted_currency_value ( $currency_obj->force_currency_conversion ( $page_data['convenience_fees'] ) ), 2):0; 

			
 		}
///////////////////////////////////ACTIVITY DETAILS///////////////////
 		$data ['package'] = $this->Package_Model->getPackage ( $id );
 		$total_passengers=$data['no_adults']+$data['no_child']+$data['no_infant'];
		$currency_obj = new Currency(array('module_type' => 'sightseeing', 'from' => ADMIN_BASE_CURRENCY_STATIC, 'to' => get_application_currency_preference()));
		$pax_count = array(
			'adult'=>$data['no_adults'],
			'child'=>$data['no_child'],
			'infant'=>$data['no_infant'],
		);
		$pax_price = array(
			'adult'=>$data ['package']->price,
			'child'=>$data ['package']->child_price,
			'infant'=>$data ['package']->infant_price,
		);
		$passenger_count = array_sum($pax_count);
		/////////////////////////////// MARKUP////////////////////////////////
		$passenger_count = array_sum($pax_count);
		$module_type = 'b2c_sightseeing';
        $markup_level = 'level_2';
        $markup = $this->private_management_model->fetch_markup_custom($module_type,$markup_level);
       // $data['total_amount'] = ($data['no_adults']*$data ['package']->price)+($data['no_child']*$data ['package']->child_price)+($data['no_infant']*$data ['package']->infant_price)+($markup['value']*$passenger_count);

        //$markup = isset($markup['value'])? get_converted_currency_value ( $currency_obj->force_currency_conversion ( $markup['value']) ):0; 
        $markup = $markup['value'];
		$convinence_fees_row = $this->private_management_model->get_convinence_fees('sightseeing');
		$adult_price = $pax_count['adult']*$pax_price['adult'];
		$child_price = 0;
		$infant_price = 0;
		if($pax_count['child']){
		$child_price = $data['no_child']*$pax_price['child'];	
		}if($pax_count['infant']){
		$infant_price = $data['no_infant']*$pax_price['infant'];	
		}
		/////////////////////////// GST //////////////////////////////////////
		$total_markup = $markup*$passenger_count;
		$gst = $this->private_management_model->fetch_gst($markup,'activities');
		$total_gst = $gst*$passenger_count;
		//$gst = isset($gst)? get_converted_currency_value ( $currency_obj->force_currency_conversion ( $gst) ):0;
		//////////////////////////// TOTAL AMOUNT WITHOUT CONVINENCE  ////////////////////////
		$activity_price = $total_amount_with_out_convinence_fee = $adult_price+$child_price+$infant_price+$total_markup+$total_gst;
		$total_amount_with_out_convinence_fee = isset($total_amount_with_out_convinence_fee)? get_converted_currency_value ( $currency_obj->force_currency_conversion ( $total_amount_with_out_convinence_fee) ):0;
        // $total_amount_with_convinence_fee = $this->private_management_model->convinence_fee_calculation($data['total_amount'],$pax_price,$convinence_fees_row,$pax_count); // Dont delete
		
		/////////////////////////// CONVINENCE CALCULATION WITH MARK UP  ////////////////////////////
		$total_amount_with_convinence_fee = $this->private_management_model->convinence_fee_calculation_custom($activity_price,$convinence_fees_row,$currency_obj,$passenger_count);
		$total_amount_with_convinence_fee = isset($total_amount_with_convinence_fee)? get_converted_currency_value ( $currency_obj->force_currency_conversion ( $total_amount_with_convinence_fee) ):0; 
        $convenience_fee = $total_amount_with_convinence_fee-$total_amount_with_out_convinence_fee;
        /////////////////////////////////////////////////////////////////////
		$data['date_of_travel'] = $data['date_of_travel'];
		$data['currency'] = get_application_currency_preference();
		$data['convenience_fee'] = $convenience_fee;
		$data['gst'] = $gst;
		$data['total_amount'] = $total_amount_with_out_convinence_fee;
		$data['grand_total'] = $total_amount_with_convinence_fee;
		$data ['activity_id'] =$id;
		$data['markup']=$markup;
		// $data['agent_markup']=$agent_markup;
        ////////////////////////////////////

		$data['total_pax_count'] = $data['no_adults']+$data['no_child']+$data['no_infant'];
 		//debug($data);exit();
        $data['formated_data']=$formated_data['SSSearchResult']['SightSeeingResults'];
		// debug($data);exit();
        $this->template->view ( 'activities/pre_booking_traveler_details', $data );  	  
		
   }

   public function pre_booking_11_01_2020($id) 
   {

   	  $data = $this->input->post();
   	  $data['booking']=$data;
   	  $data ['package'] = $this->Package_Model->getPackage( $id );
   	  // $data ['package']=$package;
   	  $data ["country"] = $this->get_api_countries ();
   	     

   	  $currency_obj = new Currency(array('module_type' => 'hotel' ,'from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
		$data['currency_obj'] = $currency_obj; 
   	  $this->template->view ( 'activities/pre_booking_traveler_details', $data );
   	  
		
   }


   public function enq_pre_booking($id, $app_reference) 
   { 
      $this->load->model('tours_model');
   	  $data = $this->input->post();
   	  $data['booking']=$data;
   	  $data ['package'] = $this->Package_Model->getPackage ( $id );
   	  $data ["country"] = $this->get_api_countries ();
   	  $where = ['app_reference'=>$app_reference];
   	  $data['app_reference']=$app_reference;
	  $data['quotation_details'] = $this->tours_model->activity_qoutation_price('package_booking_details', $where);
   	  // debug($data ['package']);exit;	  
   	  $this->template->view ( 'activities/pre_enq_booking_traveler_details', $data );
   	  
		
   }
	public function get_api_countries() {
		$this->db->limit ( 10000 );
		$this->db->order_by ( "name", "asc" );
		$qur = $this->db->get ( "api_country_list" );
		return $qur->result ();
	}
   public function get_countries() {
		$this->db->limit ( 10000 );
		$this->db->order_by ( "name", "asc" );
		$qur = $this->db->get ( "country" );
		return $qur->result ();
	}
	function pre_booking_itinary($id='') 
	{
 
		// error_reporting(E_ALL);
		$post_params = $this->input->post ();
		// debug($post_params);die;
		$promocode = @$post_params['promocode_val'];
		if ($promocode != "") {
			$promo_data = $this->domain_management_model->promocode_details($promocode);
			// debug($promo_data['value']);exit;
			$promocode_discount_val = @$promo_data['value'];
			$promocode_discount_type = @$promo_data['value_type'];
		} else {
			$promocode_discount_val = 0;
		}

		// debug($promo_data);exit();
		$book_datas = $this->module_model->unserialize_temp_booking_record($post_params['book_id'],$post_params['temp_token']);
		$post_params['booking_params']			=	$book_datas['book_attributes'];
		
		if (valid_array ( $post_params ) == false) {
			redirect ( base_url () );
		}
		//Setting Static Data - Jaganath
		$post_params['billing_city'] = 'Bangalore';
		$post_params['billing_zipcode'] = '560100';
		$post_params['billing_address_1'] = '2nd Floor, Venkatadri IT Park, HP Avenue,, Konnappana Agrahara, Electronic city';
		// Make sure token and temp token matches
		
	       $currency_obj = new Currency ( array (
					'module_type' => 'activity',
					'from' => get_application_currency_preference (),
					'to' => get_application_default_currency () 
			));
	       // debug($currency_obj);exit;
			$promo=0;
			$post_params['token'] = serialized_data($post_params);
			$post_params['promo_code'] = $promo;
			$temp_booking = $this->module_model->serialize_temp_booking_record ($post_params, SIGHTSEEING_BOOKING);
			// debug($temp_booking);exit;  

			// debug($post_params);die;
		
			$book_id = $temp_booking ['book_id'];
				$book_origin = $temp_booking ['temp_booking_origin'];
				$transport_type = $post_params ['transport_type'];
			if ($post_params ['booking_source'] == PROVAB_SIGHTSEEN_SOURCE_CRS) {
				$currency_obj = new Currency ( array (
						'module_type' => 'activity',
						'from' => admin_base_currency (),
						'to' => admin_base_currency () 
				) );
					
				//debug($currency_obj);exit;
				$amount = $post_params['agent_buying_price'];
				$total_amnt = $post_params['total'];
				$amount = floatval(preg_replace('/[^\d.]/', '', $amount));
				$currency = admin_base_currency();
				//debug($amount."====".$currency);exit;
			}


			// debug($amount);exit;
			$promocode_discount1=$post_params['prom_value'];
			$email= $post_params ['email'];
			$phone = $post_params ['phone'];
			$verification_amount = ($amount);
			$firstname = $post_params ['first_name'][0] . " " . $post_params ['last_name'][0];
			$book_id = $book_id;
			$productinfo = PROVAB_SIGHTSEEN_SOURCE_CRS;
			// debug($temp_booking);die;
			// debug($currency);exit;
			// check current balance before proceeding further
			$domain_balance_status = $this->domain_management_model->verify_current_balance ( $verification_amount, $currency );
			// debug($domain_balance_status);die;
		//debug($post_params);die;
		    $reward_point=0;
			$reward_amount=0;
			$reward_discount=0;
			if($post_params['redeem_points_post']=="1")
			{
				$reward_discount = $post_params['reducing_amount'];
				$reward_amount = $post_params['reducing_amount'];
				$reward_point = $post_params['reward_usable'];
				
			}
			$reward_earned = $post_params['reward_earned'];
			
			if($reward_discount >=1)
			{
				$verification_amount 	= number_format(($amount+$convenience_fees-$reward_discount),2);

			}
			else
			{
				$verification_amount 	= number_format(($amount+$convenience_fees-$promocode_discount),2);
			}
		$domain_balance_status = true;
				if ($domain_balance_status == true) {
			//echo $book_id;die;
				$booking_data = $this->module_model->unserialize_temp_booking_record ( $book_id, $book_origin );
				$key = $booking_data['book_attributes']['booking_params']['key'];
				 // debug($booking_data);die("$booking_data");
				$book_params = $booking_data['book_attributes'];
				$pack_id=@$post_params['pack_id'];
				$pax_count=@($post_params['no_adults']+ $post_params['no_child'] + $post_params['no_infant']);
				
				$convenience_fees=0;
				$promocode_discount=0;
				$convenience_fees = $booking_data['book_attributes']['booking_params']['formated_data'][$key]['Price']['ConvinienceFee'];
				$amnt_val = $amount- $convenience_fees;
            
				$post_params ['payment_method']=OFFLINE_PAYMENT;
				
				switch ($post_params ['payment_method']) {
					case ONLINE_PAYMENT :
	                $this->load->model('transaction');
	                $pg_currency_conversion_rate = $currency_obj->payment_gateway_currency_conversion_rate();
	                $this->transaction->create_payment_record($book_id, $amnt_val, $firstname, $email, $phone, $productinfo, $convenience_fees, $promocode_discount, $pg_currency_conversion_rate,$reward_point,$reward_amount,$reward_earned);
	                
                        redirect(base_url().'index.php/payment_gateway/payment/'.$book_id.'/'.$book_origin);                     
	                break;
	                case OFFLINE_PAYMENT :
	                $this->load->model('transaction');
	                $pg_currency_conversion_rate = $currency_obj->payment_gateway_currency_conversion_rate();
	                $this->transaction->create_payment_record($book_id, $amnt_val, $firstname, $email, $phone, $productinfo, $convenience_fees, $promocode_discount, $pg_currency_conversion_rate,$reward_point,$reward_amount,$reward_earned);
	                redirect(base_url().'index.php/activities/process_booking/'.$book_id.'/'.$book_origin.'/'.$transport_type);      
	                // redirect(base_url().'index.php/payment_gateway/payment/'.$book_id.'/'.$book_origin);                        
	                break;
					case PAY_AT_BANK :
						echo 'Under Construction - Remote IO Error';
						exit ();
						break;
				} 
			} else {
				echo "ghh-446";die;
				redirect ( base_url () . 'index.php/flight/exception?op=Amount Flight Booking&notification=insufficient_balance' );
			}
		}
	function pre_booking_itinaryold($id) 
	{
	  
		$post_params = $this->input->post ();
		// debug($post_params);exit('sudheer');
		$post_params['payment_method']="Ringgitpay";
		$pg_type="Ringgitpay";
		$post_params['total']=str_replace(',', '',  $post_params['total']);
		$promocode = @$post_params['promocode_val'];
		if ($promocode != "") {
			$promo_data = $this->domain_management_model->promocode_details($promocode);
			$promocode_discount_val = @$promo_data['value'];
			$promocode_discount_type = @$promo_data['value_type'];
		} else {
			$promocode_discount_val = 0;
		}
		
		 
		if (valid_array ( $post_params ) == false) {
			redirect ( base_url () );
		}

			// debug($post_params);die;
			// Setting Static Data - Jaganath
			// $post_params['billing_city'] = 'Bangalore';
			// $post_params['billing_zipcode'] = '560100';
			// $post_params['billing_address_1'] = '2nd Floor, Venkatadri IT Park, HP Avenue,, Konnappana Agrahara, Electronic city';
			// $post_params['convenience_fee'] = 0; //remove this  static value
			
		    // Make sure token and temp token matches
			$currency_obj = new Currency ( array (
					'module_type' => 'flight',
					'from' => get_application_currency_preference (),
					'to' => get_application_default_currency () 
			));
			$promo=0;
			$post_params['token'] = serialized_data($post_params);
			$post_params['promo_code'] = $promo;
		// echo "string";exit;
			$temp_booking = $this->module_model->serialize_temp_booking_record ($post_params, PACKAGE_BOOKING );
		
			$book_id = $temp_booking ['book_id'];
			$book_origin = $temp_booking ['temp_booking_origin'];
			if ($post_params ['booking_source'] == PACKAGE_BOOKING_SOURCE) {
				$currency_obj = new Currency ( array (
						'module_type' => 'flight',
						'from' => admin_base_currency (),
						'to' => admin_base_currency () 
				) );
				
				$amount = $post_params['total'];
				$currency = admin_base_currency();
			}

			// debug($amount);
			// debug($currency);
			// exit;

			$promocode_discount1=$post_params['prom_value'];
			$email= $post_params ['email'];
			$phone = $post_params ['phone'];
			$enquiry_reference_no = $post_params['enquiry_reference_no'];
			$verification_amount = ($amount);
			$firstname = $post_params ['first_name'] . " " . $post_params ['last_name'];
			$book_id = $book_id;
			$productinfo = META_PACKAGE_COURSE;
			//check current balance before proceeding further
			$domain_balance_status = $this->domain_management_model->verify_current_balance ( $verification_amount, $currency );
				if ($domain_balance_status == true) {
				
			// debug($book_id);
	debug($post_params);
			 exit;
				$booking_data = $this->module_model->unserialize_temp_booking_record ( $book_id, $book_origin );
				// debug($booking_data);exit('sudheer');
				$book_params = $booking_data['book_attributes'];
				$pack_id=@$post_params['pack_id'];
				$pax_count=@($post_params['no_adults']+ $post_params['no_child'] + $post_params['no_infant']);
			// echo "string";exit;
		// echo "string";exit;
			 //debug($book_id);exit;
				$data = $this->save_booking($promocode_discount_val, $book_id, $book_params,$pack_id,$pax_count,$currency_obj, $this->current_module);

				$convenience_fees=@$booking_data['book_attributes']['convenience_fees'];
				$promocode_discount=@$booking_data['book_attributes']['promocode_val'];
            	//echo $convenience_fees;exit();
				switch ($post_params ['payment_method']) {
					//case PAY_NOW_RING :
					case Ringgitpay :
						$this->load->model('transaction');
						$pg_currency_conversion_rate = $currency_obj->payment_gateway_currency_conversion_rate();
						$this->transaction->create_payment_record($book_id, $amount, $firstname, $email, $phone, $productinfo, $convenience_fees, $promocode_discount, $pg_currency_conversion_rate);
						// debug($pg_currency_conversion_rate); 
						// debug($book_id); 
						// debug($book_origin); 
						// debug($pg_type); 
						// exit;
						/* redirect(base_url().'index.php/payment_gateway/payment/'.$book_id.'/'.$book_origin.'/'.$pg_type);*/
						//debug("came");exit;
						// debug($book_id);
					//	 debug($$book_origin);exit();
						redirect(base_url().'index.php/activities/process_booking/'.$book_id.'/'.$book_origin);
						break;
					case PAY_AT_BANK :
						echo 'Under Construction - Remote IO Error';
						exit ();
						break;
				} 
			} else {
				//echo "ghh";die;
				redirect ( base_url () . 'index.php/flight/exception?op=Amount Flight Booking&notification=insufficient_balance' );
			}
		}
		function save_booking($promocode_discount_val, $app_booking_id, $book_params,$pack_id,$pax_coun,$currency_obj, $module='b2c')
	  {
	  //	error_reporting(E_ALL);
	 //debug($book_params);exit();
		
			
		$book_total_fare = array();
		$book_domain_markup = array();
		$book_level_one_markup = array();
		$master_transaction_status = 'BOOKING_INPROGRESS';
		$master_search_id = $book_params['package_id'];
		$package_details=$this->activity_model->getPackage($master_search_id);
		// debug($package_details);exit;
		$domain_origin = get_domain_auth_id();
		$app_reference = $app_booking_id;
		$booking_source = $book_params['booking_source'];
		$enquiry_reference_no = $book_params['token']['enquiry_reference_no'];
        // debug($booking_source);
        // debug($enquiry_reference_no);exit;
		//PASSENGER DATA UPDATE
		$total_pax_count =($book_params['booking_params']['search_data']['adult'] + $book_params['booking_params']['search_data']['child'] + $book_params['no_infant']);
		$pax_count = $total_pax_count;

        //PREFERRED TRANSACTION CURRENCY AND CURRENCY CONVERSION RATE 
		$transaction_currency = get_application_currency_preference();
		$application_currency = admin_base_currency();
		$currency_conversion_rate = $currency_obj->transaction_currency_conversion_rate();

	    $token = $book_params['token'];
		$master_booking_source = array();
		$currency = $currency_obj->to_currency;
		$deduction_cur_obj	= clone $currency_obj;
		//Storing Flight Details - Every Segment can repeate also
		$token_index=1;
		$commissionable_fare =  $token['total']-$token['prom_value'];
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
			$book_base_fare = $token['total'];
			$key = $book_params['booking_params']['key'];
			$book_total_fare = $book_params['booking_params']['formated_data'][$key]['Price']['TotalDisplayFare']+$book_params['booking_params']['formated_data'][$key]['Price']['ConvinienceFee'];
			
			//Need individual transaction price details
			//SAVE Transaction Details


				// $transaction_details=array(
				// 						'app_reference'=>$app_reference,
				// 						'status'=>$flight_booking_status,
				// 						'status_description'=>$flight_booking_status,
				// 						'book_id'=>$app_reference,
				// 						'source'=>'Activity CRS',
				// 						'ref_id'=>'',
				// 						'discount'=>0,
				// 						'total_fare'=>$book_total_fare,
				// 						'currency'=>$country_currency,
				// 						'attributes'=>'',
				// 							);
				// $this->activity_model->save_booking_transaction($transaction_details);


			// debug(get_domain_auth_id());exit();
			$transaction_insert_id = $this->activity_model->save_package_booking_transaction_details($promocode_discount_val, $app_reference, $transaction_status, $transaction_description, $pnr, $book_id, $source, $ref_id,
			json_encode($tranaction_attributes),$currency, $book_total_fare);
			// $transaction_insert_id = $transaction_insert_id['insert_id'];
			// debug($this->db->last_query());exit;
			
			//Saving Passenger Details
			$i = 0;
			for ($i=0; $i<$total_pax_count; $i++)
			{
				$passenger_type = 'Adult';
				$is_lead=($i==0)?1:0;
				$passenger_type_details=get_gender_by_value($book_params['name_title'][$i]);
				// debug($passenger_type_details);exit;
				$passenger_type=$passenger_type_details[1];
				$gender=$passenger_type_details[2];
				$first_name = $book_params['first_name'][$i];
				
				$last_name = $book_params['last_name'][$i];
				
				// $gender = get_enum_list('gender', $book_params['name_title'][$i]);

				// debug($passenger_type);debug($gender);exit();
				$passenger_nationality = $book_params['country'];
				$status = $master_transaction_status;
				$passenger_attributes = array(); 
				$flight_booking_transaction_details_fk = $transaction_insert_id;//Adding Transaction Details Origin 


				// debug($gender);exit();
				//SAVE Pax Details
				$pax_insert_id =  $this->activity_model->save_package_booking_passenger_details(
				  $app_reference,$passenger_type,$is_lead,$first_name,$last_name,$gender,$passenger_nationality,$status,
				json_encode($passenger_attributes), 
				$flight_booking_transaction_details_fk, $book_params['booking_params']['search_data']['adult'], $book_params['booking_params']['search_data']['child'], $book_params['booking_params']['search_data']['infant']);

				//Save passenger ticket information		
				
//debug($pax_insert_id);exit();
			}//Adding Pax Details Ends
		    $date_of_travel=date('Y-m-d', strtotime($book_params['token']['date_of_travel']));
			// debug($date_of_travel);exit;

			

		//Save Master Booking Details
		
		//debug($book_total_fare);exit;
		$phone = $book_params['phone'];
		$remarks = $book_params['remarks_user'];
		$alternate_number = '';
		$email = $book_params['email'];
		$payment_mode = $book_params['payment_method'];
		$created_by_id = intval(@$GLOBALS['CI']->entity_user_id);

		$passenger_country =$book_params['country'];
		
		$passenger_city = $book_params['billing_city'];

		$attributes = array('country' => $passenger_country, 'city' => $passenger_city, 'zipcode' => $book_params['billing_zipcode'], 'address' =>  $book_params['billing_address_1']);
		$flight_booking_status = $master_transaction_status;




		// debug($flight_booking_status);exit();
		//SAVE Booking Details
		// error_reporting(E_ALL);
		// debug($flight_booking_status);
		 //$this->Activity_Model->save_package_booking_details($domain_origin, $flight_booking_status,$app_reference,$enquiry_reference_no, $booking_source, $phone, $alternate_number, $email,$payment_mode, json_encode($attributes), $created_by_id,$transaction_currency,$currency_conversion_rate,$pack_id,$date_of_travel);
		$book_total_fare = floatval(preg_replace('/[^\d.]/', '', $book_total_fare));



		 $attributes = '';
		/* $travel_date=$book_params['date_of_travel'];
		 $location=$package_details->package_location;
		 $grade_code=$package_details->package_code;
		 $grade_desc=$package_details->package_description;*/
        // SAVE Booking Itinerary details
  //  $GLOBALS ['CI']->activity_model->save_booking_itinerary_details ( $app_reference, $location, $travel_date,$grade_code, $grade_desc, $flight_booking_status,$commissionable_fare,$admin_net_fare_markup,$admin_markup, $agent_markup, $currency, $attributes, @$book_total_fare,$agent_commission,$agent_tds,$admin_commission,$admin_tds,$api_raw_fare,$agent_buying_price, $gst_value);

		 // $this->Activity_Model->save_package_booking_details($domain_origin, $flight_booking_status,$app_reference,$booking_source,$phone, $alternate_number, $email,$payment_mode, json_encode($attributes), $created_by_id,$transaction_currency,$currency_conversion_rate,$pack_id,$date_of_travel,$book_total_fare);
		 $country_currency = agent_base_currency();
		 $emulate = $this->session->userdata('EMUL');
			if(!empty($emulate)){
				$emulate_booking = 1;
				$emulate_user = $emulate;
			}
			else{
				$emulate_booking = 0;
				$emulate_user = '';
			}
			// debug($emulate_booking);exit;
			if(isset($book_params['booking_params']['formated_data'])){
				$formated_data=$book_params['booking_params']['formated_data'][$key];
				$price_details=$formated_data['Price'];
			}
			// debug($formated_data);
			$total_markup=$price_details['_AgentMarkup']+$price_details['_AdminMarkup'];
			$tds_on_markup=($total_markup/100)*$price_details['_GST'];

			$total_fare=$price_details['_AgentBuying']+$price_details['ConvinienceFee']+$tds_on_markup;
// debug($book_params['pickup_location']);die;
			$book_params['pickup_location'] = $book_params['pickup_location'] == "" ? "-" : $book_params['pickup_location'];
			$booking_param=array(
								'app_reference'=>$app_reference,
								'domain_origin'=>$domain_origin,
								'booking_source'=>$book_params['booking_source'],
								'package_type'=>$formated_data['ProductCode'],
								'module_type'=>ACTIVITY_PACKAGE,
								'status'=>$flight_booking_status,
								'basic_fare'=>$price_details['_AdminBuying'],
								'admin_markup'=>$price_details['_AdminMarkup'],
								'agent_markup'=>$price_details['_AgentMarkup'],
								'gst_percentage'=>$price_details['_GST'],
								'admin_markup_tds'=>($price_details['_AdminMarkup']/100)*$price_details['_GST'],
								'agent_markup_tds'=>($price_details['_AgentMarkup']/100)*$price_details['_GST'],
								'convenience_fee'=>$price_details['ConvinienceFee'],
								'agent_buying_price'=>$price_details['_AgentBuying'],
								'total_fare'=>$price_details['TotalDisplayFare']+$price_details['ConvinienceFee'],
								'currency_code'=>$transaction_currency,
								'payment_status'=>PAYMENT_PENDING,
								'created_by_id'=>$this->entity_user_id,
								'created_datetime'=>date('Y-m-d h:i:s'),
								'booked_datetime'=>date('Y-m-d h:i:s'),
								'booked_by_id'=>$this->entity_user_id,
								'email'=>$book_params['email'],
								'departure_date'=>$book_params['date_of_travel'],
								'no_of_transfer'=>count($book_params['first_name']),
								'phone'=>$book_params['phone'],
								'pickup_location'=>$book_params['pickup_location'],
								'remarks'=>$book_params['remarks_user'],
								'payment_mode'=>$payment_mode,
								'date_of_travel'=>$book_params['date_of_travel'],
								'currency'=>$transaction_currency,
								'currency_conversion_rate'=>$currency_conversion_rate,
								'attributes' => json_encode($attributes),
								'cancellationPolicy'=>$book_params['cancellationPolicy'],
								'cancltnPlcy_avble'=>$book_params['cancltnPlcy_avble'],
								'emulate_booking'=>$emulate_booking,
								'emulate_user'=>$emulate_user,
								);
			 // debug('inserted');exit;
			$this->load->model('activity_model');
			$res=$this->activity_model->save_activity_booking_details($booking_param);

			$ref_id = $app_reference;
				$reference_data['activity_ref_no']=substr($ref_id,-6);
			    $reference_data['first_no']=substr($ref_id,3,1);
			    $reference_data['second_no']=substr($ref_id,4,1);
			    $reference_data['third_no']=substr($ref_id,5,1);
			    // debug($reference_data);exit;
			    $ins_data=$this->custom_db->insert_record('reference_id_activity',$reference_data);
				 // `activity_booking_transaction_details`(`origin`, `app_reference`, `pnr`, `status`, `status_description`, `book_id`, `source`, `ref_id`, `discount`, `total_fare`, `currency`, `attributes`) 
		 //debug($this->db->last_query());exit;

			// debug($flight_booking_status);exit();
		// echo 123;exit("end");

		/************** Update Convinence Fees And Other Details Start ******************/
		
		$response['fare'] = $book_total_fare;
		$response['status'] = $flight_booking_status;
		$response['status_description'] = $transaction_description;
		$response['name'] = $first_name;
		$response['phone'] = $phone; 

	    // debug($response);exit;
		return $response;
	}
		function save_bookingold($promocode_discount_val, $app_booking_id, $book_params,$pack_id,$pax_coun,$currency_obj, $module='b2c')
	    {
		
			// debug($promocode_discount_val);
			// debug($app_booking_id);
			// debug($book_params);
			// debug($pack_id);
			// debug($pax_coun);
			// debug($currency_obj);
			// exit('sudheer');
		$book_total_fare = array();
		$book_domain_markup = array();
		$book_level_one_markup = array();
		$master_transaction_status = 'BOOKING_INPROGRESS';
		$master_search_id = $book_params['pack_id'];

		$domain_origin = get_domain_auth_id();
		$app_reference = $app_booking_id;
	
		$booking_source = $book_params['token']['booking_source'];
		$enquiry_reference_no = $book_params['token']['enquiry_reference_no'];
        // debug($booking_source);
        // debug($enquiry_reference_no);exit;
		//PASSENGER DATA UPDATE
		$total_pax_count =($book_params['no_adults'] + $book_params['no_child'] + $book_params['no_infant']);
		$pax_count = $total_pax_count;

        //PREFERRED TRANSACTION CURRENCY AND CURRENCY CONVERSION RATE 
		$transaction_currency = get_application_currency_preference();
		$application_currency = admin_base_currency();
		$currency_conversion_rate = $currency_obj->transaction_currency_conversion_rate();

	    $token = $book_params['token'];
	    // debug($book_params);die;
	    $master_booking_source = array();
		// $currency = $currency_obj->to_currency; 
		$currency = get_application_currency_preference();  //Added Newly
		$deduction_cur_obj	= clone $currency_obj;
		//Storing Flight Details - Every Segment can repeate also
		$token_index=1;


		if(empty($token['prom_value']))
		{
			$token['prom_value']=0;
		}

		// debug($token['prom_value']);

		 $token['total']=str_replace(',', '',  $token['total']);
		 $commissionable_fare =  $token['total'];
		 // $commissionable_fare =  $token['total']-$token['prom_value'];
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
			///////////////////for gst and admin markup//////
			$package_type =  $this->Package_Model->getPackage ( $token['pack_id'] );
			$adult_price = $package_type->price;
			$aduchild_pricelt_price = $package_type->child_price;
			$infant_price = $package_type->infant_price;		
			$package_type = $package_type->module_type;

		    $tranaction_attributes['Fare'] = $token['total'];

			$tranaction_attributes['convenience_fee'] =$token['convenience_fees'];

			$tranaction_attributes['AgentCommission'] =$token['AgentCommission'];
			$tranaction_attributes['AgentTdsOnCommision'] =$token['AgentTdsOnCommision'];
			$tranaction_attributes['NetFare'] =$token['NetFare'];
			$tranaction_attributes['_GST'] =$token['gst'];
			$tranaction_attributes['_Markup'] =$token['admin_markup'];
			$tranaction_attributes['agent_Markup'] =$token['agent_markup'];
			$sequence_number = $token_index;
			//Transaction Log Details
			$ticket_trans_status_group[] = $transaction_status;
			$book_total_fare[]	= $token['total'];
			// debug($tranaction_attributes);exit;
			


			//Need individual transaction price details
			//SAVE Transaction Details


			// debug($token['total']);
			// debug($token['prom_value']);
			// debug($commissionable_fare);
			// exit();
			// debug($tranaction_attributes);exit();

			// echo "promocode_discount_val-".$promocode_discount_val."<br>";
			// echo "app_reference-".$app_reference."<br>";
			// echo "transaction_status-".$transaction_status."<br>";
			// echo "transaction_description-".$transaction_description."<br>";
			// echo "pnr-".$pnr."<br>";
			// echo "book_id-".$book_id."<br>";
			// echo "source-".$source."<br>";
			// echo "ref_id-".$ref_id."<br>";
			// echo "currency-".$currency."<br>";
			// echo "ref_id-".$ref_id."<br>";
			// echo "commissionable_fare-".$commissionable_fare;exit();


			$transaction_insert_id = $this->package_model->save_package_booking_transaction_details($promocode_discount_val, $app_reference, $transaction_status, $transaction_description, $pnr, $book_id, $source, $ref_id,
			json_encode($tranaction_attributes),$currency, $commissionable_fare);
			$transaction_insert_id = $transaction_insert_id['insert_id'];


			// debug($transaction_insert_id);exit();
			//Saving Passenger Details
			$i = 0;
			for ($i=0; $i<$total_pax_count; $i++)
			{
				$passenger_type = 'Adult';
				$is_lead =1;
				$first_name = $book_params['first_name'];
				
				$last_name = $book_params['last_name'];
				

				// debug($book_params['gender']);exit();
				$gender = get_enum_list('gender', $book_params['gender'][$i]);
				$passenger_nationality = $book_params['country'];
				$status = $master_transaction_status;
				$passenger_attributes = array(); 
				$flight_booking_transaction_details_fk = $transaction_insert_id;//Adding Transaction Details Origin 


				// debug($gender);exit();
				//SAVE Pax Details
				$pax_insert_id =  $this->Package_Model->save_package_booking_passenger_details($app_reference,$passenger_type,$is_lead,$first_name,$last_name,$gender,$passenger_nationality,$status,
				json_encode($passenger_attributes), 
				$flight_booking_transaction_details_fk, $book_params['no_adults'], $book_params['no_child'], $book_params['no_infant']);
				// debug($pax_insert_id);exit;

				//Save passenger ticket information		
				

			}//Adding Pax Details Ends
		    $date_of_travel=date('Y-m-d', strtotime($book_params['token']['date_of_travel']));
			// debug($date_of_travel);exit;

			

		//Save Master Booking Details
		$book_total_fare = $token['total'];
		$phone = $book_params['phone'];
		$alternate_number = '';
		$email = $book_params['email'];
		$payment_mode = $book_params['payment_method'];
		$created_by_id = intval(@$GLOBALS['CI']->entity_user_id);

		$passenger_country =$book_params['country'];
		
		$passenger_city = $book_params['billing_city'];

		$attributes = array('country' => $passenger_country, 'city' => $passenger_city, 'zipcode' => $book_params['billing_zipcode'], 'address' =>  $book_params['billing_address_1']);
		$flight_booking_status = $master_transaction_status;

		// debug($flight_booking_status);exit();
		//SAVE Booking Details
		// error_reporting(E_ALL);
		// debug($flight_booking_status);
			// echo "string";exit('sudheer');
		 $this->Package_Model->save_package_booking_details($domain_origin, $flight_booking_status,$app_reference,$enquiry_reference_no, $booking_source, $phone, $alternate_number, $email,$payment_mode, json_encode($attributes), $created_by_id,$transaction_currency,$currency_conversion_rate,$pack_id,$date_of_travel);

			// debug($flight_booking_status);exit();
		// echo 123;exit("end");

		/************** Update Convinence Fees And Other Details Start ******************/
		
		$response['fare'] = $book_total_fare;
		$response['status'] = $flight_booking_status;
		$response['status_description'] = $transaction_description;
		$response['name'] = $first_name;
		$response['phone'] = $phone; 
		// debug($response);exit('sudheer');
		return $response;
	}
	function process_booking($book_id, $temp_book_origin){

		//debug($book_id);
		//debug($temp_book_origin);exit;
		
		if($book_id != '' && $temp_book_origin != '' && intval($temp_book_origin) > 0){

			$page_data ['form_url'] = base_url () . 'index.php/activities/secure_booking';
			$page_data ['form_method'] = 'POST';
			$page_data ['form_params'] ['book_id'] = $book_id;
			$page_data ['form_params'] ['temp_book_origin'] = $temp_book_origin;

			$this->template->view('share/loader/booking_process_loader', $page_data);	

		}else{
			redirect(base_url().'index.php/flight/exception?op=Invalid request&notification=validation');
		}
		
	}
	function secure_booking() 
	{

		ini_set('max_execution_time', '0');
		// error_reporting(E_ALL);
		$this->load->model('activity_model');
		$post_data = $this->input->post();
		// debug($post_data);exit;
		$payment_status=FALSE;
		if(valid_array($post_data) == true && isset($post_data['book_id']) == true && isset($post_data['temp_book_origin']) == true &&
			empty($post_data['book_id']) == false && intval($post_data['temp_book_origin']) > 0){
			
			//verify payment status and continue
			$book_id = trim($post_data['book_id']);
			$temp_book_origin = intval($post_data['temp_book_origin']);
			// debug($book_id);debug($temp_book_origin);exit;
			// ***********************************GETTING PAYMENT FROM GATEWAY***************
			// ************************  uncomment if payment gateway integrated *******************************
			// $payment_status=SUCCESS_STATUS;
			$this->load->model('Transaction_Model');
			
			$booking_status = $this->Transaction_Model->get_payment_status($book_id);
			
			// if($booking_status['status'] !== 'accepted'){
			// 	redirect(base_url().'index.php/activities/exception?op=Payment Not Done&notification=validation');
			// }

			// ***********************************GETTING PAYMENT FROM GATEWAY***************

		} else{
			redirect(base_url().'index.php/activities/exception?op=InvalidBooking&notification=invalid');
		}
		
		$currency_obj = new Currency ( array (
					'module_type' => 'activity',
					'from' => get_application_currency_preference (),
					'to' => get_application_default_currency () 
			));

		//run booking request and do booking
		$temp_booking = $this->module_model->unserialize_temp_booking_record ( $book_id, $temp_book_origin );
		$verification_amount=$temp_booking['book_attributes']['total'];
		  $currency=admin_base_currency();
		  // debug($verification_amount."=======".$currency);exit;
		$domain_balance_status = $this->domain_management_model->verify_current_balance ( $verification_amount, $currency );
			//debug($temp_booking['booking_source']);die;
		load_sightseen_lib(PROVAB_SIGHTSEEN_SOURCE_CRS);
		//Delete the temp_booking record, after accessing
		
		// $this->module_model->delete_temp_booking_record ($book_id, $temp_book_origin);
		// echo "hello";exit;
		// debug($book_id);exit;

		$booking_data = $this->module_model->unserialize_temp_booking_record ( $book_id, $temp_book_origin );
		$key = $booking_data['book_attributes']['booking_params']['key'];
		if($post_data['transport_type']!=$booking_data['book_attributes']['booking_params']["formated_data"][$key]['transfer_type']){
			$transfer_option = $booking_data['book_attributes']['booking_params']["formated_data"][$key]['transport_price'];
			for($i=0;$i<count($transfer_option);$i++){
				if($transfer_option[$i]['transfer_option']==$post_data['transport_type']){
					$val = $booking_data['book_attributes']['booking_params']["formated_data"][$key]['Price']['TotalDisplayFare'] - $booking_data['book_attributes']['booking_params']["formated_data"][$key]['Price']['_AdminBuying'];
					$booking_data['book_attributes']['booking_params']["formated_data"][$key]['Price']['_AdminBuying'] = $transfer_option[$i]['total_price'];
					$booking_data['book_attributes']['booking_params']["formated_data"][$key]['Price']['NetFare'] = $transfer_option[$i]['total_price']+$booking_data['book_attributes']['booking_params']["formated_data"][$key]['Price']['_AdminMarkup'];
					$booking_data['book_attributes']['booking_params']["formated_data"][$key]['Price']['_AgentBuying'] = $booking_data['book_attributes']['booking_params']["formated_data"][$key]['Price']['NetFare']+$booking_data['book_attributes']['booking_params']["formated_data"][$key]['Price']['_GST'];
					$val = $val+$transfer_option[$i]['total_price'];
					$booking_data['book_attributes']['booking_params']["formated_data"][$key]['Price']['TotalDisplayFare'] = $val;



					$val = $booking_data['book_attributes']['booking_params']["formated_data"][$key]['Price']['TotalDisplayFare'] - $booking_data['book_attributes']['booking_params']["formated_data"][$key]['Price']['_AdminBuying'];
					$booking_data['book_attributes']['token']['booking_params']["formated_data"][$key]['Price']['_AdminBuying'] = $transfer_option[$i]['total_price'];
					$booking_data['book_attributes']['token']['booking_params']["formated_data"][$key]['Price']['NetFare'] = $transfer_option[$i]['total_price']+$booking_data['book_attributes']['booking_params']["formated_data"][$key]['Price']['_AdminMarkup'];
					$booking_data['book_attributes']['token']['booking_params']["formated_data"][$key]['Price']['_AgentBuying'] = $booking_data['book_attributes']['booking_params']["formated_data"][$key]['Price']['NetFare']+$booking_data['book_attributes']['booking_params']["formated_data"][$key]['Price']['_GST'];
					$val = $val+$transfer_option[$i]['total_price'];
					$booking_data['book_attributes']['token']['booking_params']["formated_data"][$key]['Price']['TotalDisplayFare'] = $val;
				}
			}
		}
		// debug($booking_data);exit;
		// $booking = $this->sightseeing_lib->process_booking($book_id, $temp_booking['book_attributes'], $module='b2c');
  //                       $booking = $this->sightseeing_lib->formate_booking_response_data($booking,$temp_booking);
		 
				 // debug($booking_data);die("$booking_data");
				$book_params = $booking_data['book_attributes'];
	//	debug($booking_data);
		//debug($post_params);
			//	die;
			  $pack_id=@$book_params['booking_params']['package_id']; 
				$pax_count=@($temp_booking['book_attributes']['booking_params']['search_data']['adult']+ $temp_booking['book_attributes']['booking_params']['search_data']['child'] + $temp_booking['book_attributes']['booking_params']['search_data']['infant']);

			$promocode_discount_val=0;
				//debug($this->current_module);exit;
			// $current_module=$this->current_module;
			$current_module='b2b';
	$data = $this->save_booking($promocode_discount_val, $book_id, $book_params,$pack_id,$pax_count,$currency_obj, $current_module);
				//debug($data);die("chk");

					$data['admin_markup']=0;
					$data['agent_markup']=0;
					$data['convinence']=0;
					$data['discount']=0;
					$data['currency_conversion_rate'] = $currency_obj->transaction_currency_conversion_rate();
					$data['transaction_currency'] = get_application_currency_preference();
					$data['fare']= floatval(preg_replace('/[^\d.]/', '', $data['fare']));
					$agent_paybleamount['amount'] = $data['fare'];

					$stat = $this->activity_model->change_confirm_status($book_id);
					//debug($verification_amount);exit;
					// $this->domain_management_model->save_transaction_details('activity', $book_id, $verification_amount, "0", "0", '0', '0', '0', $currency, $data['currency_conversion_rate'], $data['transaction_currency'] );
					//debug($this->db->last_query());exit;
				$this->domain_management_model->update_transaction_details('activity', $book_id, $data['fare'], $data['admin_markup'], $data['agent_markup'], $data['convinence'], $data['discount'],$data['transaction_currency'], $data['currency_conversion_rate'], 'crs_booking' );
				$payment_status=SUCCESS_STATUS;
		if($payment_status==SUCCESS_STATUS){		
			$booking_data = $this->module_model->unserialize_temp_booking_record ( $book_id, $temp_book_origin );
			// debug($booking_data);exit;
			$app_reference=$booking_data['book_id'];
			$booking_source=$booking_data['booking_source'];
			$booking_status='BOOKING_CONFIRMED';
			$this->activity_model->update_activity_payment_status($app_reference,$booking_source,$booking_status,'paid');

			//Accounting API
			$key= $booking_data ['book_attributes'] ['key'];
			// debug( $booking_data ['book_attributes'] ['key']);exit;
			$booking_data ['book_attributes'] ['formated_data'] [$key] ['CurrencyCode'] =$booking_data ['book_attributes'] ['currency_code'];
			//$this->accounting_api->process_service($book_id, $booking_data ['book_attributes'] ['formated_data'] [$key],'TransactionCRUD',PROVAB_SIGHTSEEN_SOURCE_CRS);
			// debug("hii");exit;
			if($temp_booking['book_attributes']['redeem_points_post'] == 1)
						{
							$this->rewards->update_after_booking($temp_booking,$book_id);
							$this->rewards->update_activity_rewards_details($temp_booking,$book_id);
						}
						else
						{
							$this->rewards->update_reward_earned_value($temp_booking,$book_id);
							$this->rewards->update_earned_rewards_details($temp_booking,$book_id,"activity_booking_details");
						}
			redirect ( base_url () . 'index.php/voucher/activity_crs/' . $app_reference . '/' . $booking_source . '/' . $booking_status . '/show_voucher' );
			}else{
				redirect(base_url().'index.php/activities/exception?op=Payment Not Done&notification=validation');
			}
		 
		}
	function secure_bookingold() 
	{

		$post_data = $this->input->post();
		// debug($post_data);
		if(valid_array($post_data) == true && isset($post_data['book_id']) == true && isset($post_data['temp_book_origin']) == true &&
			empty($post_data['book_id']) == false && intval($post_data['temp_book_origin']) > 0){
			
			//verify payment status and continue
			$book_id = trim($post_data['book_id']);
			$temp_book_origin = intval($post_data['temp_book_origin']);
			$this->load->model('transaction');
			$booking_status = $this->transaction->get_payment_status($book_id);
			//$booking_status['status'] = 'accepted';//remove this line once payment gateway added.
			// debug($booking_status);//exit;
			//Comment here for direct booking Jagannath B
			// if($booking_status['status'] !== 'accepted'){
			// 	redirect(base_url().'index.php/activities/exception?op=Payment Not Done&notification=validation');
			// }
		} else{
			redirect(base_url().'index.php/activities/exception?op=InvalidBooking&notification=invalid');
		}
		
		//run booking request and do booking
		$temp_booking = $this->module_model->unserialize_temp_booking_record ( $book_id, $temp_book_origin );
	    $book_id = $temp_booking['book_id'];
	   //Delete the temp_booking record, after accessing
		
		// $this->module_model->delete_temp_booking_record ($book_id, $temp_book_origin);
		// echo "hello";exit;
		// debug($book_id);exit;

		$stat = $this->Package_Model->change_confirm_status($book_id);
		 //debug($stat);exit();
			// echo "string";exit;
		$app_reference   = $book_id;
			$booking_source  = $temp_booking['booking_source'];
			$booking_status  = SUCCESS_STATUS;
			//echo $book_id."---,".$booking_source."---,".$booking_status;exit();
			// $this->sendmail_book($book_id,$booking_source,$booking_status);
// 			echo 1;die;
			$email = $temp_booking['book_attributes']['email'];
			$stat=1;
		if($stat == SUCCESS_STATUS){

			$app_reference   = $book_id;
			$booking_source  = $temp_booking['booking_source'];
			$booking_status  = SUCCESS_STATUS;
			//echo $book_id."---,".$booking_source."---,".$booking_status;exit();
			// $this->sendmail_book($book_id,$booking_source,$booking_status,$email);
			redirect ( base_url () . 'index.php/voucher/activity/' . $book_id . '/' . $temp_booking ['booking_source'] . '/' . $data ['status'] . '/show_voucher' );
		}
		else{
			//redirect(base_url().'index.php/activities/exception?op=Payment Not Done&notification=validation');
		}

	}

	// function secure_booking1() 
	// {
	// 		$book_id = 'PB16-103201-942122';
	// 		$temp_book_origin = '34';

	// 	$temp_booking = $this->module_model->unserialize_temp_booking_record ( $book_id, $temp_book_origin );

	// 	//debug($temp_booking);exit;
	// 	//$this->module_model->delete_temp_booking_record ($book_id, $temp_book_origin);
		
	

	// 	//$stat = $this->Package_Model->change_confirm_status($book_id);
	// 	$stat = SUCCESS_STATUS;


	// 	if($stat == SUCCESS_STATUS){
	// 		$app_reference   = $book_id;
	// 		$booking_source  = $temp_booking['booking_source'];
	// 		$booking_status  = SUCCESS_STATUS;
	// 		$this->load->model('user_model');
	// 		$status = $this->user_model->sms_checkpoint('booking');

	// 		if ($status) {
							
	// 			$sms_template = $this->provab_sms->booking_sms_tempalte ( META_PACKAGE_COURSE, $book_id, $temp_booking ['booking_source'] );
	// 			//debug($sms_template);die;
	// 			if ($sms_template ['status'] == true) {
	// 				foreach ( $sms_template ['sms_tempalte'] as $sms_k => $sms_v ) {
						
	// 					$sms_status = $this->provab_sms->send_msg ( $sms_template ['phone_number'], $sms_v );
	// 					 debug($sms_status);exit;
	// 				}
	// 			}
	// 		}
			
	// 		$this->sendmail($app_reference,$booking_source,$booking_status);
	// 		// echo "show_voucher";
	// 		redirect ( base_url () . 'index.php/voucher/package/' . $book_id . '/' . $temp_booking ['booking_source'] . '/' . $data ['status'] . '/show_voucher' );
	// 	}
	// 	else{
	// 		redirect(base_url().'index.php/activities/exception?op=Payment Not Done&notification=validation');
	// 	}

	// }
 

	function exception() {
		$module = META_PACKAGE_COURSE;
		$op = @$_GET ['op'];
		$notification = @$_GET ['notification'];
		$book_id = @$_GET ['book_id'];

		$eid = $this->module_model->log_exception ( $module, $op, $notification );
		// set ip log session before redirection
		$this->session->set_flashdata ( array (
				'log_ip_info' => true 
		) );
		redirect ( base_url () . 'index.php/activities/event_logger/' . $eid.'/'.$book_id );
	}

	function event_logger($eid = '',$book_id) {
// debug($book_id);exit();
		$log_ip_info = $this->session->flashdata ( 'log_ip_info' );
		$this->template->view ( 'activities/exception', array (
				'log_ip_info' => $log_ip_info,
				'eid' => $eid,
				'notification' => $notification,
				'book_id' => $book_id
		) );
	}

function sendmail($app_reference="",$booking_status="",$booking_source="",$email='')
	{

			$app_reference  = $app_reference;
			$booking_source = $booking_source;
			$booking_status = $booking_status;
	
		
		$booking_details = $this->Package_Model->get_activity_booking_details($app_reference, $booking_source, $booking_status);

// 		error_reporting(E_ALL);
		$domain_address = $this->custom_db->single_table_records ( 'domain_list','address,domain_logo',array('origin'=>get_domain_auth_id()));
		$page_data['address'] =$domain_address['data'][0]['address'];
		$page_data['logo'] = $domain_address['data'][0]['domain_logo'];
		$page_data['details'] = $booking_details;
		$domain_address = $this->custom_db->single_table_records ('domain_list','address,domain_logo,phone,domain_name',array('origin'=>get_domain_auth_id()));
		$page_data['data']['address'] =$domain_address['data'][0]['address'];
		$page_data['data']['logo'] = $domain_address['data'][0]['domain_logo'];
		$page_data['data']['phone'] = $domain_address['data'][0]['phone'];
		$page_data['data']['domainname'] = $domain_address['data'][0]['domain_name'];
		$this->load->library('provab_pdf');
		$create_pdf = new Provab_Pdf();
		
		$mail_template = $this->template->isolated_view('voucher/activity_voucher', $page_data);
		$mail_template_pdf=$this->template->isolated_view('voucher/package_pdf', $page_data);
		//debug($mail_template_pdf);
		$pdf=$create_pdf->create_pdf($mail_template_pdf,'');
        $this->load->library('provab_mailer');
		$response = $this->provab_mailer->send_mail($email, domain_name().' - Activity Ticket',$mail_template,$pdf);
	     //debug($response);exit();
	    $data['status'] = 1;
	
	    echo json_encode($data);
	
	}
	function sendmail_report()
	{

// 		echo "string";exit();
// 		ini_set('display_errors', 1);
// 		ini_set('display_startup_errors', 1);
// 		error_reporting(E_ALL);
		$post_data=$this->input->post();
		$app_reference=$post_data['app_reference'];
		$email=$post_data['email'];
		$booking_source=$post_data['booking_source'];
		$booking_status=$post_data['booking_status'];
		// if($this->input->post()){
		// 	$app_reference  = $this->input->post('app_reference');
		// 	$booking_source = $this->input->post('booking_source');
		// 	$booking_status = $this->input->post('booking_status');
		// }else{
			// $app_reference  = $app_reference;
			// $booking_source = $booking_source;
			// $booking_status = $booking_status;
		// }
		
		$booking_details = $this->Package_Model->get_activity_booking_details($app_reference, $booking_source, $booking_status);
		//debug($booking_details);exit();
		
		$domain_address = $this->custom_db->single_table_records ( 'domain_list','address,domain_logo',array('origin'=>get_domain_auth_id()));
		$page_data['address'] =$domain_address['data'][0]['address'];
		$page_data['logo'] = $domain_address['data'][0]['domain_logo'];
		$page_data['details'] = $booking_details;
		$domain_address = $this->custom_db->single_table_records ('domain_list','address,domain_logo,phone,domain_name',array('origin'=>get_domain_auth_id()));
		$page_data['data']['address'] =$domain_address['data'][0]['address'];
		$page_data['data']['logo'] = $domain_address['data'][0]['domain_logo'];
		$page_data['data']['phone'] = $domain_address['data'][0]['phone'];
		$page_data['data']['domainname'] = $domain_address['data'][0]['domain_name'];
		$this->load->library('provab_pdf');
		$create_pdf = new Provab_Pdf();
		
		$mail_template = $this->template->isolated_view('voucher/activity_voucher', $page_data);
		//$mail_template1 = $this->template->isolated_view('voucher/package_pdf', $page_data);
		//$pdf = $create_pdf->create_pdf($mail_template1,'');
		// $this->provab_mailer->send_mail($email, domain_name().' - Activity Ticket',$mail_template,$pdf);
		$this->provab_mailer->send_mail($email, domain_name().' - Activity Ticket',$mail_template);
		
		
	
	}
	function sendmail_book($app_reference="",$booking_source="",$booking_status="",$email='')
	{


		$app_reference  = $app_reference;
		$booking_source = $booking_source;
		$booking_status = $booking_status;
		$booking_details = $this->Package_Model->get_activity_booking_details($app_reference, $booking_source, $booking_status);
		$domain_address = $this->custom_db->single_table_records ( 'domain_list','address,domain_logo',array('origin'=>get_domain_auth_id()));
		$page_data['address'] =$domain_address['data'][0]['address'];
		$page_data['logo'] = $domain_address['data'][0]['domain_logo'];
		$page_data['details'] = $booking_details;
		$domain_address = $this->custom_db->single_table_records ('domain_list','address,domain_logo,phone,domain_name',array('origin'=>get_domain_auth_id()));
		$page_data['data']['address'] =$domain_address['data'][0]['address'];
		$page_data['data']['logo'] = $domain_address['data'][0]['domain_logo'];
		$page_data['data']['phone'] = $domain_address['data'][0]['phone'];
		$page_data['data']['domainname'] = $domain_address['data'][0]['domain_name'];
		$this->load->library('provab_pdf');
		$create_pdf = new Provab_Pdf();
		
		$mail_template = $this->template->isolated_view('voucher/activity_voucher', $page_data);
		$mail_template1 = $this->template->isolated_view('voucher/package_pdf', $page_data);
		$pdf = $create_pdf->create_pdf($mail_template1,'');
		// $this->provab_mailer->send_mail($email, domain_name().' - Activity Ticket',$mail_template,$pdf);
// 		debug($page_data);die;
		$type = $page_data['details']['booking_details'][0]['module_type'] == "transfers" ? "Transfer" : "Activity";
		//$email = $page_data['details']['booking_details'][0]['email'];
	
		$res = $this->provab_mailer->send_mail($email, domain_name().' - '.$type.' Ticket',$mail_template,$pdf);

	}

	function sendmail1()
	{ 



		//error_reporting(0);
		$app_reference ='PB18-175006-413802';
		$booking_source = 'PTBSID0000000005';
		$booking_status = 'BOOKING_CONFIRMED';

		
		//echo "jh"; exit();
		$this->load->library('provab_mailer');
		$this->load->model('Package_Model');
		$this->load->library('booking_data_formatter');
		if (empty($app_reference) == false) {
			$booking_details = $this->Package_Model->get_booking_details($app_reference, $booking_source, $booking_status);
			// debug($booking_details); exit;
			if ($booking_details['status'] == SUCCESS_STATUS) {
				// load_flight_lib(PROVAB_FLIGHT_BOOKING_SOURCE);
				//Assemble Booking Data
				// $assembled_booking_details = $this->booking_data_formatter->format_flight_booking_data($booking_details, 'b2c');
				// $page_data['data'] = $assembled_booking_details['data'];
				//$address = json_decode($booking_details['data']['booking_details']['0']['attributes'],true);
				// $address = "";
				// $page_data['data']['address'] = $address ;
				// $page_data['data']['logo'] = $assembled_booking_details['data']['booking_details']['0']['domain_logo'];
				//$email = $booking_details['booking_details'][0]['email'];
			     $email='sooraj.ev.provab@gmail.com';
				// debug($booking_details);exit;
				$pass_query = 'SELECT domain_logo,address FROM `domain_list`';

				$page_data['logo'] = $this->db->query($pass_query)->result_array();
				$address = $this->db->query($pass_query)->result_array();
				$address = $address[0]['address'];
				$page_data['address'] = $address;
				// debug($page_data['logo']); exit;
				$page_data['details'] = $booking_details;
				// debug($page_data['logo']); exit;
				$this->load->library('provab_pdf');
				$create_pdf = new Provab_Pdf();
				$mail_template = $this->template->isolated_view('voucher/package_pdf', $page_data);
				$pdf = $create_pdf->create_pdf($mail_template,'view');
				// debug($pdf); exit;
				$s = $this->provab_mailer->send_mail($email, domain_name().' E-Ticket',$mail_template,$pdf);
				debug($s); exit;
				//$s= $this->provab_mailer->send_mail($email, domain_name().' E-Ticket',$mail_template);
				//debug($s);
				//$s= $this->provab_mailer->send_mail($email, domain_name().' E-Ticket',$mail_template);
				//debug($s);
			}
		}
	}




}
