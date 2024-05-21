<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



/**

 *

 * @package    Provab

 * @subpackage Transfers

 * @author     Elavarasi<elavarasi.k@provabmial.com>

 * @version    V1

 */

//error_reporting(E_ALL);

class Privatetransfer extends CI_Controller {

	private $current_module;

	public function __construct()

	{

		parent::__construct();

		//we need to activate transfer api which are active for current domain and load those libraries

		$this->load->model('transferv1_model');

		$this->load->model('Package_Model');

		$this->load->library('social_network/facebook');//Facebook Library to enable login button		

		//$this->output->enable_profiler(TRUE);

		$this->current_module = $this->config->item('current_module');

	}



	/**

	 * index page of application will be loaded here

	 */

	function index()

	{

		//	echo number_format(0, 2, '.', '');

	}



	/**

	 * Elavarasi

	 * Load Transfers Search Result

	 * @param number $search_id unique number which identifies search criteria given by user at the time of searching

	 */

	function search($search_id)

	{	

		$safe_search_data = $this->transferv1_model->get_safe_search_data($search_id,META_TRANSFERV1_COURSE);

		

		// Get all the hotels bookings source which are active

		$active_booking_source = $this->transferv1_model->active_booking_source();

		

		if ($safe_search_data['status'] == true and valid_array($active_booking_source) == true) {

			$safe_search_data['data']['search_id'] = abs($search_id);

			$this->template->view('private-transfer/search_result_page', array('sight_seen_search_params' => $safe_search_data['data'], 'active_booking_source' => $active_booking_source));

		} else {

			$this->template->view ( 'general/popup_redirect');

		}

	}



	/**

	 *  Elavarasi

	 * Get Product Details

	 */

	function transfer_details_old()

	{
		// echo "string";exit;
		$params = $this->input->get();


		$safe_search_data = $this->transferv1_model->get_safe_search_data($params['search_id'],META_TRANSFERV1_COURSE);

		$safe_search_data['data']['search_id'] = abs($params['search_id']);

		$currency_obj = new Currency(array('module_type' => 'transferv1','from' => get_api_data_currency(), 'to' => get_application_currency_preference()));

		$search_id = $params['search_id'];

		if (isset($params['booking_source']) == true) {

			//We will load different page for different API providers... As we have dependency on API for hotel details page

			load_transferv1_lib($params['booking_source']);
		// debug($safe_search_data);exit;

			if ($params['booking_source'] == PROVAB_TRANSFERV1_BOOKING_SOURCE && isset($params['result_token']) == true and isset($params['op']) == true and

			$params['op'] == 'get_details' and $safe_search_data['status'] == true) {



				$params['result_token']	= urldecode($params['result_token']);





				$raw_product_deails = $this->transferv1_lib->get_product_details($params);

				// debug($raw_product_deails);

				// exit;

				if ($raw_product_deails['status']) {

					

					

					 $calendar_availability_date = $this->enable_calendar_availability($raw_product_deails['data']['ProductDetails']['TransferInfoResult']['Product_available_date']);



					

					$raw_product_deails['data']['ProductDetails']['TransferInfoResult']['Calendar_available_date'] = $calendar_availability_date;



					if($raw_product_deails['data']['ProductDetails']['TransferInfoResult']['Price']){

						//details data in preffered currency

						$Price = $this->transferv1_lib->details_data_in_preffered_currency($raw_product_deails['data']['ProductDetails']['TransferInfoResult']['Price'],$currency_obj,'b2c');



						$currency_obj = new Currency(array('module_type' => 'transferv1','from' => get_application_currency_preference(), 'to' => get_application_currency_preference()));



						//calculation Markup 

						$raw_product_deails['data']['ProductDetails']['TransferInfoResult']['Price'] = $this->transferv1_lib->update_booking_markup_currency($Price,$currency_obj,$search_id, false, true, 'b2c');						



					}

					

					$this->template->view('transferv1/viator/sightseeing_details', array('currency_obj' => $currency_obj, 'product_details' => $raw_product_deails['data']['ProductDetails']['TransferInfoResult'], 'search_params' => $safe_search_data['data'], 'search_id'=>$search_id,'active_booking_source' => $params['booking_source'], 'params' => $params));

				} else {

					

					$msg= $raw_product_deails['Message'];



					redirect(base_url().'index.php/transferv1/exception?op='.$msg.'&notification=session');

				}

			} else {

				redirect(base_url());

			}

		} else {

			redirect(base_url());

		}

	}



	function transfer_details()
	{
		$params = $this->input->get();
		// debug($params);exit;
		$safe_search_data = $this->transferv1_model->get_safe_search_data($params['search_id'],META_TRANSFERV1_COURSE);

		// debug( $safe_search_data['status'] );exit();
		$safe_search_data['data']['search_id'] = abs($params['search_id']);
		$currency_obj = new Currency(array('module_type' => 'transferv1','from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
		$search_id = $params['search_id'];
		if (isset($params['booking_source']) == true) {
			//We will load different page for different API providers... As we have dependency on API for hotel details page
			load_transferv1_lib($params['booking_source']);
			if ($params['booking_source'] == PROVAB_TRANSFERV1_BOOKING_SOURCE && isset($params['result_token']) == true and isset($params['op']) == true and
			$params['op'] == 'get_details' and $safe_search_data['status'] == true) {

				$params['result_token']	= urldecode($params['result_token']);


				$raw_product_deails = $this->transferv1_lib->get_product_details($params);
				// debug($raw_product_deails);
				// exit;
				if ($raw_product_deails['status']) {
					
					
					 $calendar_availability_date = $this->enable_calendar_availability($raw_product_deails['data']['ProductDetails']['TransferInfoResult']['Product_available_date']);

					
					$raw_product_deails['data']['ProductDetails']['TransferInfoResult']['Calendar_available_date'] = $calendar_availability_date;

					if($raw_product_deails['data']['ProductDetails']['TransferInfoResult']['Price']){
						//details data in preffered currency
						$Price = $this->transferv1_lib->details_data_in_preffered_currency($raw_product_deails['data']['ProductDetails']['TransferInfoResult']['Price'],$currency_obj,'b2c');

						$currency_obj = new Currency(array('module_type' => 'transferv1','from' => get_application_currency_preference(), 'to' => get_application_currency_preference()));

						//calculation Markup 
						$raw_product_deails['data']['ProductDetails']['TransferInfoResult']['Price'] = $this->transferv1_lib->update_booking_markup_currency($Price,$currency_obj,$search_id, false, true, 'b2c');						

					}
					
					$this->template->view('transferv1/viator/sightseeing_details', array('currency_obj' => $currency_obj, 'product_details' => $raw_product_deails['data']['ProductDetails']['TransferInfoResult'], 'search_params' => $safe_search_data['data'], 'search_id'=>$search_id,'active_booking_source' => $params['booking_source'], 'params' => $params));
				} else {
					
					$msg= $raw_product_deails['Message'];

					redirect(base_url().'index.php/transferv1/exception?op='.$msg.'&notification=session');
				}
			} elseif($params['booking_source'] == PROVAB_TRANSFERV1_SOURCE_CRS && isset($params['result_token']) == true and isset($params['op']) == true and
			$params['op'] == 'get_details' and $safe_search_data['status'] == true)
			{
				
				// debug($params);exit();
				redirect(base_url('index.php/privatetransfer/details/'.$params['product_code'].'/'.$params['search_id']));
			}
		} else {
			redirect(base_url());
		}
	}


	public function details($package_id, $enquiry_staus=0) {

		$data['enquire_status'] = $enquiry_staus;
		$search_id=$this->uri->segment(4);
		// debug($data);
		// debug($search_id);exit;


		$data ['package'] = $this->transferv1_model->getPackage ( $package_id );
		$package_formate = $this->Package_Model->getPackageTransfersCRS( $package_id ,$module_type="transfers");	


		$data ['package_itinerary'] = $this->transferv1_model->getPackageItinerary ( $package_id );
		$data ['package_price_policy'] = $this->transferv1_model->getPackagePricePolicy ( $package_id );
		$data ['package_cancel_policy'] = $this->transferv1_model->getPackageCancelPolicy ( $package_id );

		
		$data ['package_traveller_photos'] = $this->transferv1_model->getTravellerPhotos ( $package_id );
		// debug($data ['package_traveller_photos'] );exit();
		// echo "string";exit;
		$currency_obj = new Currency(array('module_type' => 'transferv1','from' => admin_base_currency(), 'to' => get_application_currency_preference()));
		$data['currency_obj'] = $currency_obj;	

		// exit(PROVAB_TRANSFERV1_SOURCE_CRS);
		// error_reporting(E_ALL);
		// echo "string";exit('sudheer');
		$search_result=array();
		if (! empty ( $package_formate )) 
		{
			// debug($package_formate);exit();
			foreach ($package_formate as $key => $value) 
			{						
			 
			$key=$key.'crs';
			$search_result['data']['TransferSearchResult']['TransferResults'][$key]['ProductName']=$value['package_name'];
			$search_result['data']['TransferSearchResult']['TransferResults'][$key]['ProductCode']=$value['package_id'];
			$search_result['data']['TransferSearchResult']['TransferResults'][$key]['ImageUrl']=$GLOBALS['CI']->template->domain_upload_pckg_images($value['image']);
			$search_result['data']['TransferSearchResult']['TransferResults'][$key]['ImageHisUrl']=$GLOBALS['CI']->template->domain_upload_pckg_images($value['image']);
			$search_result['data']['TransferSearchResult']['TransferResults'][$key]['BookingEngineId']='ProvabCrs';
			$search_result['data']['TransferSearchResult']['TransferResults'][$key]['Promotion']='';
			$search_result['data']['TransferSearchResult']['TransferResults'][$key]['PromotionAmount']=0;
			$search_result['data']['TransferSearchResult']['TransferResults'][$key]['StarRating']=$value['rating'];
			$search_result['data']['TransferSearchResult']['TransferResults'][$key]['DestinationName']=$value['package_location'];
			$search_result['data']['TransferSearchResult']['TransferResults'][$key]['Price']=array(
			'TotalDisplayFare'=>$value['price'],
			'GSTPrice'=>0,
			'PriceBreakup'=>array(
			'AgentCommission'=>0,
			'AgentTdsOnCommision'=>0
			),
			'Currency'=>admin_base_currency()
			);
			$search_result['data']['TransferSearchResult']['TransferResults'][$key]['Description']=$value['package_description'];
			$search_result['data']['TransferSearchResult']['TransferResults'][$key]['Cancellation_available']='';
			$search_result['data']['TransferSearchResult']['TransferResults'][$key]['Cat_Ids']=array();
			$search_result['data']['TransferSearchResult']['TransferResults'][$key]['Sub_Cat_Ids']=array();
			$search_result['data']['TransferSearchResult']['TransferResults'][$key]['Supplier_Code']=$value['supplier_id'];
			$search_result['data']['TransferSearchResult']['TransferResults'][$key]['Duration']=$value['duration'].' Days';
			$search_result['data']['TransferSearchResult']['TransferResults'][$key]['ResultToken']=base64_encode(json_encode($value));
			$search_result['data']['TransferSearchResult']['TransferResults'][$key]['booking_source']=PROVAB_TRANSFERV1_SOURCE_CRS;
			}


			$search_result['Status']=1;
			$search_result['Message']=''; 
		}

 		load_transferv1_lib(PROVAB_TRANSFERV1_SOURCE_CRS);
		$currency_obj = new Currency(array('module_type' => 'transferv1', 'from' => get_api_data_currency(), 'to' => get_application_currency_preference()));

		// debug($search_result);exit();
        $raw_sightseeing_result = $this->transferv1_lib_crs->search_data_in_preferred_currency($search_result, $currency_obj,'b2c');
             // debug($raw_sightseeing_result['data']);exit();          
                         //Display 
        $currency_obj = new Currency(array('module_type' => 'transferv1', 'from' => get_application_currency_preference(), 'to' => get_application_currency_preference()));

        $filters = array();

        // echo "string";exit;
                       
        $formated_data = $this->transferv1_lib_crs->format_search_response($raw_sightseeing_result['data'], $currency_obj, $search_id, 'b2c', $filters); 

        // debug($data);exit;
        $data['formated_data']=$formated_data['TransferSearchResult']['TransferResults'];

		
		if (! empty ( $data ['package'] )) {
			// debug($data);die;
			$this->template->view ( 'activities/activity_detail', $data );
		} else {
			redirect ( "activities/index" );
		}
	}

	/**

	*Elavarasi

	*Enable Particular day only in calendar

	*/

	public function enable_calendar_availability($calendar_availability_date){

		

		if(valid_array($calendar_availability_date)){

			$available_str=array();

			 foreach ($calendar_availability_date as $m_key => $m_value) {

			 	$avail_month_str = $m_key;

			 	

			 	foreach ($m_value as $d_key => $d_value) {

			 		//j- to remove 0 from date, n to remove 0 from month

			 		$date_str = $avail_month_str.'-'.$d_value;

			 		$available_str[] = date('j-n-Y',strtotime($date_str));

			 		

			 	}

			 }

			

			return $available_str;

		}else{

			return '';

		}

	}

	/**

	*Elavarasi

	*Getting Available Tourgrade Based on Passenger Mix

	*/

	public function select_tourgrade(){

		$post_params = $this->input->post();

		// debug($post_params);

		// exit;

		//debug($get_params)

		$response['data'] = '';

        $response['msg'] = '';

        $response['status'] = FAILURE_STATUS;

		$safe_search_data = $this->transferv1_model->get_safe_search_data($post_params['search_id'],META_TRANSFERV1_COURSE);

		//debug($get_params);exit;

		if(trim($post_params['product_code'])!=''){



			load_transferv1_lib($post_params['booking_source']);

			if(trim($post_params['op']=='check_tourgrade')&&$post_params['product_code']!=''){



					$search_product_tourgrade = $this->transferv1_lib->get_tourgrade_list($post_params);



					$currency_obj = new Currency(array('module_type' => 'transferv1','from' => get_api_data_currency(), 'to' => get_application_currency_preference()));



					

					if($search_product_tourgrade['status']){



						$module_currency_obj = new Currency(array('module_type' => 'transferv1','from' => get_application_currency_preference(), 'to' => get_application_currency_preference()));



						$raw_product_list = $this->transferv1_lib->tourgrade_in_preferred_currency($search_product_tourgrade['data'], $currency_obj,$module_currency_obj,'b2c');

						

						$search_product_tourgrade['data'] = $raw_product_list;

						$response['data'] = get_compressed_output($this->template->isolated_view('transferv1/viator/select_tourgrade',

							array('tourgrade_list'=>$search_product_tourgrade['data'],'search_params'=>$post_params,

								'currency_obj'=>$currency_obj,

								'booking_source'=>$post_params['booking_source'])));

						$response['msg'] = 'success';

						$response['status'] = SUCCESS_STATUS;

						

					}else{

						$search_product_tourgrade['Message'] = $search_product_tourgrade['Message'];

						

						if(!valid_array($search_product_tourgrade['data'])){

							

							$response['msg'] = $search_product_tourgrade['Message'] ;

						

						}

					}

					 

			}else{

				

			}

		}else{

			

		}

		 $this->output_compressed_data($response);

		

	}

	/**

     * Compress and output data

     * @param array $data

     */

    private function output_compressed_data($data) {





        while (ob_get_level() > 0) {

            ob_end_clean();

        }

        ob_start("ob_gzhandler");

        header('Content-type:application/json');

        echo json_encode($data);

        ob_end_flush();

        exit;

    }

	/**

	*Ealvarasi check the available date for the product

	**/

	public function select_date(){

		$post_params = $this->input->post();



		$selected_date = trim($post_params['selected_date']);

		//echo $selected_date;exit;

		//debug($get_params);

		if(!empty($selected_date)){

			

			$product_get_booking_date = json_decode(base64_decode($post_params['available_date']),true);

			if(valid_array($product_get_booking_date)){



				$selected_date_details = $product_get_booking_date;

				//debug($selected_date_details);exit;

				$options = '';



				//debug($selected_date_details[$selected_date]);exit;

				foreach ($selected_date_details[$selected_date] as $key => $value) {

					$selected = '';

					if(isset($post_params['s_date'])){



						if($value==$post_params['s_date']){

							$selected =' selected';

						}

					}

					

					$options .='<option value='.$value.' '.$selected.' >'.($value).'</option>';

				}

				

				echo $options;

				exit;

				

			}

		}

	}

	/**

	 *  Elavarasi

	 * Passenger Details page for final bookings

	 * Here we need to run booking based on api

	 */

	function booking()

	{

		

		$pre_booking_params = $this->input->post();

		

		$search_id = $pre_booking_params['search_id'];

		$safe_search_data = $this->transferv1_model->get_safe_search_data($pre_booking_params['search_id'],META_TRANSFERV1_COURSE);



		$safe_search_data['data']['search_id'] = abs($search_id);

		$page_data['active_payment_options'] = $this->module_model->get_active_payment_module_list();

		

		if(valid_array($_POST)){

			$token_post['token_data'] = json_encode($pre_booking_params);

			$con_search['origin'] = $search_id;

			$this->custom_db->update_record('search_history',$token_post,$con_search);

		}else{



			$token_query = 'select token_data from search_history where origin='.$search_id;

			$token_data = $this->db->query($token_query)->result_array();

			//debug($token_data);exit("pp");

			$pre_booking_params = json_decode($token_data[0]['token_data'],true);

		}

		if (isset($pre_booking_params['booking_source']) == true) {

			

			//We will load different page for different API providers... As we have dependency on API for tourgrade details page

			$page_data['search_data'] = $safe_search_data['data'];

			load_transferv1_lib($pre_booking_params['booking_source']);

			//Need to fill pax details by default if user has already logged in

			$this->load->model('user_model');

			$page_data['pax_details'] = $this->user_model->get_current_user_details();



			header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");

			header("Cache-Control: post-check=0, pre-check=0", false);

			header("Pragma: no-cache");



			if ($pre_booking_params['booking_source'] == PROVAB_TRANSFERV1_BOOKING_SOURCE && isset($pre_booking_params['tour_uniq_id']) == true and

			isset($pre_booking_params['op']) == true and $pre_booking_params['op'] == 'block_trip' and $safe_search_data['status'] == true)

			{

				

				if ($pre_booking_params['tour_uniq_id'] != false) {



					$trip_block_details = $this->transferv1_lib->block_trip($pre_booking_params);

					//debug($trip_block_details);



					if ($trip_block_details['status'] == false) {

						redirect(base_url().'index.php/transferv1/exception?op='.$trip_block_details['data']['msg']);

					}

					//Converting API currency data to preferred currency

					$currency_obj = new Currency(array('module_type' => 'transferv1','from' => get_api_data_currency(), 'to' => get_application_currency_preference()));

					

					$trip_block_details = $this->transferv1_lib->tripblock_data_in_preferred_currency($trip_block_details, $currency_obj,'b2c');



					

					//Display

					$currency_obj = new Currency(array('module_type' => 'transferv1', 'from' => get_application_currency_preference(), 'to' => get_application_currency_preference()));

					



					$cancel_currency_obj = new Currency(array('module_type' => 'transferv1','from' => get_api_data_currency(), 'to' => get_application_currency_preference()));



					$pre_booking_params = $this->transferv1_lib->update_block_details($trip_block_details['data']['BlockTrip'], $pre_booking_params,$currency_obj,'b2c');					

					

					// debug($pre_booking_params);

					// exit;

					/*

					 * Update Markup

					 */					

					$pre_booking_params['markup_price_summary'] = $this->transferv1_lib->update_booking_markup_currency($pre_booking_params['price_summary'], $currency_obj, $safe_search_data['data']['search_id'], false, true, 'b2c');

					

					

					// debug($pre_booking_params['markup_price_summary'] );

					// exit;

					if ($trip_block_details['status'] == SUCCESS_STATUS) {

						if(!empty($this->entity_country_code)){

							$page_data['user_country_code'] = $this->entity_country_code;

						}

						else{

							$page_data['user_country_code'] = '';	

						}

						$page_data['booking_source'] = $pre_booking_params['booking_source'];

						$page_data['pre_booking_params'] = $pre_booking_params;

						$page_data['pre_booking_params']['default_currency'] = get_application_currency_preference();

						

						$page_data['iso_country_list']	= $this->db_cache_api->get_iso_country_list();

						$page_data['country_list']		= $this->db_cache_api->get_country_list();

						$page_data['currency_obj']		= $currency_obj;

						$page_data['total_price']		= $this->transferv1_lib->total_price($pre_booking_params['markup_price_summary']);

						$currency_obj = new Currency ( array (

							'module_type' => 'transferv1',

							'from' => get_application_currency_preference (),

							'to' => admin_base_currency () 

						) );

						$page_data['total_fare_converted'] = get_converted_currency_value($currency_obj->force_currency_conversion($page_data['total_price']));

						//calculate convience fees by pax wise

						$ageband_details = $trip_block_details['data']['BlockTrip']['AgeBands'];



						$page_data['convenience_fees']  = $currency_obj->convenience_fees($page_data['total_price'], $ageband_details);

						

						$page_data['tax_service_sum']	=  $this->transferv1_lib->tax_service_sum($pre_booking_params['markup_price_summary'], $pre_booking_params['price_summary']);



						//Traveller Details

						$page_data['traveller_details'] = $this->user_model->get_user_traveller_details();

						//Get the country phone code 

						$Domain_record = $this->custom_db->single_table_records('domain_list', '*');

						$page_data['active_data'] =$Domain_record['data'][0];

						$temp_record = $this->custom_db->single_table_records('api_country_list', '*');

						$page_data['phone_code'] =$temp_record['data'];

						

						$page_data['search_id'] = $search_id;

						$this->template->view('transferv1/viator/viator_booking_page', $page_data);

					}

				} else {

					redirect(base_url().'index.php/transferv1/exception?op=Data Modification&notification=Data modified while transfer(Invalid Data received while validating tokens)');

				}

			} else {



				redirect(base_url());

			}

		} else {

			redirect(base_url());

		}

	}



	/**

	 *  Elavarasi

	 * sending for booking	 

	 */

	function pre_booking($search_id)

	{

		

		$post_params = $this->input->post();

		// debug($post_params);exit;

		$post_params['billing_city'] = 'Bangalore';

		$post_params['billing_zipcode'] = '560100';

		$post_params['billing_address_1'] = '2nd Floor, Venkatadri IT Park, HP Avenue,, Konnappana Agrahara, Electronic city';

		



		//Make sure token and temp token matches

		$valid_temp_token = unserialized_data($post_params['token'], $post_params['token_key']);

		

		if ($valid_temp_token != false) {



			load_transferv1_lib($post_params['booking_source']);

			/****Convert Display currency to Application default currency***/

			//After converting to default currency, storing in temp_booking

			$post_params['token'] = unserialized_data($post_params['token']);

			

			$currency_obj = new Currency ( array (

						'module_type' => 'transferv1',

						'from' => get_application_currency_preference (),

						'to' => get_application_default_currency () 

				));



			#debug($post_params['token']);

			$post_params['token'] = $this->transferv1_lib->convert_token_to_application_currency($post_params['token'], $currency_obj, $this->current_module);

			// debug($post_params['token']);

			// exit;

			$post_params['token'] = serialized_data($post_params['token']);



			$temp_token = unserialized_data($post_params['token']);

			//Insert To temp_booking and proceed

			$temp_booking = $this->module_model->serialize_temp_booking_record($post_params, TRANSFER_BOOKING);

			$book_id = $temp_booking['book_id'];

			$book_origin = $temp_booking['temp_booking_origin'];

			

			// debug($temp_token);

			// exit;



			if ($post_params['booking_source'] == PROVAB_TRANSFERV1_BOOKING_SOURCE) {

				$amount	  = $this->transferv1_lib->total_price($temp_token['markup_price_summary']);

				$currency = $temp_token['default_currency'];

			}

			$currency_obj = new Currency ( array (

						'module_type' => 'transferv1',

						'from' => admin_base_currency (),

						'to' => admin_base_currency () 

			) );

			/********* Convinence Fees Start ********/

			$search_data = $temp_token['AgeBands'];

			

			$convenience_fees = $currency_obj->convenience_fees($amount, $search_data);

			/********* Convinence Fees End ********/

			 	

			/********* Promocode Start ********/

			$promocode_discount = $post_params['promo_actual_value'];

			/********* Promocode End ********/



			//details for PGI

			

			$email = $post_params ['billing_email'];

			$phone = $post_params ['passenger_contact'];

//			$verification_amount = roundoff_number($amount+$convenience_fees-$promocode_discount);

			$verification_amount = roundoff_number($amount);

			$firstname = $post_params ['first_name'] ['0'];

			$productinfo = META_TRANSFERV1_COURSE;

			//check current balance before proceeding further

			$domain_balance_status = $this->domain_management_model->verify_current_balance($verification_amount, $currency);			

			if ($domain_balance_status == true) {

				switch($post_params['payment_method']) {

					case PAY_NOW :

						$this->load->model('transaction');

						$pg_currency_conversion_rate = $currency_obj->payment_gateway_currency_conversion_rate();

						$this->transaction->create_payment_record($book_id, $verification_amount, $firstname, $email, $phone, $productinfo, $convenience_fees, $promocode_discount, $pg_currency_conversion_rate);

						

						   redirect(base_url().'index.php/payment_gateway/payment/'.$book_id.'/'.$book_origin);						

						

						

					case PAY_AT_BANK : echo 'Under Construction - Remote IO Error';exit;

					break;

				}

			} else {

				redirect(base_url().'index.php/transferv1/exception?op=Amount Transfers Booking&notification=insufficient_balance');

			}

		} else {

			redirect(base_url().'index.php/transferv1/exception?op=Remote IO error @ Transfers Booking&notification=validation');

		}

	}





	/*

		process booking in backend until show loader 

	*/

	function process_booking($book_id, $temp_book_origin){

		

		if($book_id != '' && $temp_book_origin != '' && intval($temp_book_origin) > 0){



			$page_data ['form_url'] = base_url () . 'index.php/transferv1/secure_booking';

			$page_data ['form_method'] = 'POST';

			$page_data ['form_params'] ['book_id'] = $book_id;

			$page_data ['form_params'] ['temp_book_origin'] = $temp_book_origin;



			$this->template->view('share/loader/booking_process_loader', $page_data);	



		}else{

			redirect(base_url().'index.php/transferv1/exception?op=Invalid request&notification=validation');

		}

		

	}



	/**

	 * Elavarasi

	 *Do booking once payment is successfull - Payment Gateway

	 *and issue voucher

	 */

	function secure_booking()

	{

           // echo "BOOKING BLOCKED BY ADMIN";exit;

		$post_data = $this->input->post();

		

		//debug($post_data);exit;

		if(valid_array($post_data) == true && isset($post_data['book_id']) == true && isset($post_data['temp_book_origin']) == true &&

			empty($post_data['book_id']) == false && intval($post_data['temp_book_origin']) > 0){

			//verify payment status and continue

			$book_id = trim($post_data['book_id']);

			$temp_book_origin = intval($post_data['temp_book_origin']);

			$this->load->model('transaction');

			$booking_status = $this->transaction->get_payment_status($book_id);

			

			if($booking_status['status'] !== 'accepted'){

				redirect(base_url().'index.php/transferv1/exception?op=Payment Not Done&notification=validation');

			}

		} else{

			redirect(base_url().'index.php/transferv1/exception?op=InvalidBooking&notification=invalid');

		}		

		//run booking request and do booking

		$temp_booking = $this->module_model->unserialize_temp_booking_record($book_id, $temp_book_origin);

		// debug($temp_booking);exit;

		//Delete the temp_booking record, after accessing

		$this->module_model->delete_temp_booking_record ($book_id, $temp_book_origin);

		load_transferv1_lib($temp_booking['booking_source']);

		//verify payment status and continue		



		$total_booking_price = $this->transferv1_lib->total_price($temp_booking['book_attributes']['token']['markup_price_summary']);		

		// debug($temp_booking);

		// exit;

		$currency = $temp_booking['book_attributes']['token']['default_currency'];

		//also verify provab balance

		//check current balance before proceeding further

		$domain_balance_status = $this->domain_management_model->verify_current_balance($total_booking_price, $currency);

		// debug($temp_booking);exit;



		if ($domain_balance_status) {

			//lock table

			if ($temp_booking != false) {

				switch ($temp_booking['booking_source']) {

					case PROVAB_TRANSFERV1_BOOKING_SOURCE :

					

						//FIXME : COntinue from here - Booking request

						$booking = $this->transferv1_lib->process_booking($book_id, $temp_booking['book_attributes']);

						//Save booking based on booking status and book id

						break;

				}



				if ($booking['status'] == SUCCESS_STATUS) {



					$currency_obj = new Currency(array('module_type' => 'transferv1', 'from' => admin_base_currency(), 'to' => admin_base_currency()));

					$promo_currency_obj = new Currency(array('module_type' => 'transferv1', 'from' => get_application_currency_preference(), 'to' => admin_base_currency()));

					$booking['data']['currency_obj'] = $currency_obj;

					$booking['data']['promo_currency_obj']=$promo_currency_obj;

					//Save booking based on booking status and book id

					$data = $this->transferv1_lib->save_booking($book_id, $booking['data']);

					

					$this->domain_management_model->update_transaction_details('transferv1', $book_id, $data['fare'], $data['admin_markup'], $data['agent_markup'], $data['convinence'], $data['discount'],$data['transaction_currency'], $data['currency_conversion_rate'] );



					redirect(base_url().'index.php/voucher/transferv1/'.$book_id.'/'.$temp_booking['booking_source'].'/'.$data['booking_status'].'/show_voucher');

				} else {

					redirect(base_url().'index.php/transferv1/exception?op=booking_exception&notification='.$booking['Message']);

				}

			}

			//release table lock

		} else {

			redirect(base_url().'index.php/transferv1/exception?op=Remote IO error @ Insufficient&notification=validation');

		}

		//redirect(base_url().'index.php/hotel/exception?op=Remote IO error @ Hotel Secure Booking&notification=validation');

	}



	function test(){

		$currency_obj = new Currency(array('module_type' => 'sightseeing', 'from' => admin_base_currency(), 'to' => admin_base_currency()));

		debug($currency_obj);

	}



	

	/**

	 * Elavarasi

	 */

	function pre_cancellation($app_reference, $booking_source)

	{

		if (empty($app_reference) == false && empty($booking_source) == false) {

			$page_data = array();

			$booking_details = $this->transferv1_model->get_booking_details($app_reference, $booking_source);

			if ($booking_details['status'] == SUCCESS_STATUS) {

				$this->load->library('booking_data_formatter');

				//Assemble Booking Data

				$assembled_booking_details = $this->booking_data_formatter->format_transferv1_booking_data($booking_details, 'b2c');

				$page_data['data'] = $assembled_booking_details['data'];

				$this->template->view('transferv1/pre_cancellation', $page_data);

			} else {

				redirect('security/log_event?event=Invalid Details');

			}

		} else {

			redirect('security/log_event?event=Invalid Details');

		}

	}

	/*

	 * Elavarasi

	 * Process the Booking Cancellation

	 * Full Booking Cancellation

	 *

	 */

	function cancel_booking($app_reference, $booking_source)

	{

		if(empty($app_reference) == false) {

			$get_params = $this->input->get();



			$master_booking_details = $this->transferv1_model->get_booking_details($app_reference, $booking_source);

			if ($master_booking_details['status'] == SUCCESS_STATUS) {

				$this->load->library('booking_data_formatter');

				$master_booking_details = $this->booking_data_formatter->format_sightseeing_booking_data($master_booking_details, 'b2c');

				$master_booking_details = $master_booking_details['data']['booking_details'][0];

				load_sightseen_lib($booking_source);

				$cancellation_details = $this->sightseeing_lib->cancel_booking($master_booking_details,$get_params);//Invoke Cancellation Methods

				if($cancellation_details['status'] == false) {

					$query_string = '?error_msg='.$cancellation_details['msg'];

				} else {

					$query_string = '';

				}

				redirect('sightseeing/cancellation_details/'.$app_reference.'/'.$booking_source.$query_string);

			} else {

				redirect('security/log_event?event=Invalid Details');

			}

		} else {

			redirect('security/log_event?event=Invalid Details');

		}

	}

	/**

	 * Elavarasi

	 * Cancellation Details

	 * @param $app_reference

	 * @param $booking_source

	 */

	function cancellation_details($app_reference, $booking_source)

	{

		if (empty($app_reference) == false && empty($booking_source) == false) {

			$master_booking_details = $GLOBALS['CI']->transferv1_model->get_booking_details($app_reference, $booking_source);

			if ($master_booking_details['status'] == SUCCESS_STATUS) {

				$page_data = array();

				$this->load->library('booking_data_formatter');

				$master_booking_details = $this->booking_data_formatter->format_sightseeing_booking_data($master_booking_details, 'b2c');

				$page_data['data'] = $master_booking_details['data'];

				$this->template->view('sightseeing/cancellation_details', $page_data);

			} else {

				redirect('security/log_event?event=Invalid Details');

			}

		} else {

			redirect('security/log_event?event=Invalid Details');

		}



	}



	/**

	 * Elavarasi

	 */

	function exception($redirect=true)

	{

		$module = META_TRANSFERV1_COURSE;

		$op = (empty($_GET['op']) == true ? '' : $_GET['op']);

		$notification = (empty($_GET['notification']) == true ? '' : $_GET['notification']);

		if($op == 'Some Problem Occured. Please Search Again to continue'){

			$op = 'Some Problem Occured. ';

		}

		if($notification=='In Sufficiant Balance'){

		

			$notification = 'In Sufficiant Balance For Transfers';

		}



		$eid = $this->module_model->log_exception($module, $op, $notification);



		//set ip log session before redirection

		$this->session->set_flashdata(array('log_ip_info' => true));

	

		if($redirect){

			redirect(base_url().'index.php/transferv1/event_logger/'.$eid);

		}

		

	}

	function event_logger($eid='')

	{

		

		$log_ip_info = $this->session->flashdata('log_ip_info');

		$exception_data  = $this->custom_db->single_table_records('exception_logger','*',array('exception_id'=>$eid),0,1);

		$exception=$exception_data['data'][0];

		$this->template->view('transferv1/exception', array('log_ip_info' => $log_ip_info, 'eid' => $eid,'exception'=>$exception));

	}

	public function book_packages() 
   {
      
   	    $post_params = $this->input->post();
        $booking_amount = base64_decode($post_params['booking_amount']);
    	$package_id=$post_params['package_id'];
   	    // debug($booking_amount);
   	    // debug($package_id);
   	    // exit;
        $data ['package'] = $this->Package_Model->getPackage ( $package_id );
        // debug($data ['package']);exit();
        $data['module_type'] = $data['package']->module_type;
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
		$module_type = 'b2c_transferv1';
        $markup_level = 'level_2';
        $markup = $this->private_management_model->fetch_markup_custom($module_type,$markup_level);
   		// echo "string";exit;
        // debug($pax_count);
        // debug($pax_price);exit();
        //$data['total_amount'] = ($data['no_adults']*$data ['package']->price)+($data['no_child']*$data ['package']->child_price)+($data['no_infant']*$data ['package']->infant_price)+($markup['value']*$passenger_count);

        //$markup = isset($markup['value'])? get_converted_currency_value ( $currency_obj->force_currency_conversion ( $markup['value']) ):0; 
        $markup = $markup['value'];
		$convinence_fees_row = $this->private_management_model->get_convinence_fees('transferv1');
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
		$gst = $this->private_management_model->fetch_gst($markup,'transfer');
		$total_gst = $gst*$passenger_count;
		//$gst = isset($gst)? get_converted_currency_value ( $currency_obj->force_currency_conversion ( $gst) ):0;
		//////////////////////////// TOTAL AMOUNT WITHOUT CONVINENCE  ////////////////////////
		$total_amount_with_out_convinence_fee = $adult_price+$child_price+$infant_price+$total_markup+$total_gst;
		$activity_price = $adult_price+$child_price+$infant_price+$total_markup+$total_gst;
		// debug($total_amount_with_out_convinence_fee);
		// $activity_price = $adult_price+$child_price+$infant_price+$total_markup;
		// $activity_price = $adult_price+$child_price+$infant_price;
		
		$total_amount_with_out_convinence_fee = isset($total_amount_with_out_convinence_fee)? get_converted_currency_value ( $currency_obj->force_currency_conversion ( $total_amount_with_out_convinence_fee) ):0;
		// debug($total_amount_with_out_convinence_fee);
		// exit;
        // $total_amount_with_convinence_fee = $this->private_management_model->convinence_fee_calculation($data['total_amount'],$pax_price,$convinence_fees_row,$pax_count); // Dont delete
		
		/////////////////////////// CONVINENCE CALCULATION WITH MARK UP  ////////////////////////////
		$total_amount_with_convinence_fee = $this->private_management_model->convinence_fee_calculation_custom($activity_price,$convinence_fees_row,$currency_obj,$passenger_count);
		// echo "string";exit;
		
		$total_amount_with_convinence_fee = isset($total_amount_with_convinence_fee)? get_converted_currency_value ( $currency_obj->force_currency_conversion ( $total_amount_with_convinence_fee) ):0;
		// debug($total_amount_with_convinence_fee); 
        $convenience_fee = $total_amount_with_convinence_fee-$total_amount_with_out_convinence_fee;
        /////////////////////////////////////////////////////////////////////
		$data['currency'] = get_application_currency_preference();
		$data['convenience_fee'] = $convenience_fee;
		$data['gst'] = $gst;
		$data['total_amount'] = $total_amount_with_out_convinence_fee;
		$data['grand_total'] = $total_amount_with_convinence_fee;
		$data ['activity_id'] =$package_id;
		// debug($data); exit();
		////////////////////////////////////
		$this->template->view ( 'activities/booking', $data );
   	}

public function pre_booking_crs($id) 
   {
   	  $data = $this->input->post();
   	  $data['booking']=$data;
   	  $data ['package'] = $this->Package_Model->getPackage( $id );

   	  $package_formate = $this->Package_Model->getPackageTransfersCRS( $id ,$module_type="transfers");


   	  // $data ['package']=$package;
   	  $data ["country"] = $this->get_api_countries();
   	     
   	// echo "string";exit;

   	  $currency_obj = new Currency(array('module_type' => 'transferv1' ,'from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
		$data['currency_obj'] = $currency_obj;



	 $search_result=array();
   	  // debug($data);exit;
	 $search_id=0;
		if (! empty ( $package_formate )) 
		{
			// debug($package_formate);exit();
			foreach ($package_formate as $key => $value) 
			{						
			 
			$key=$key.'crs';
			$search_result['data']['TransferSearchResult']['TransferResults'][$key]['ProductName']=$value['package_name'];
			$search_result['data']['TransferSearchResult']['TransferResults'][$key]['ProductCode']=$value['package_id'];
			$search_result['data']['TransferSearchResult']['TransferResults'][$key]['ImageUrl']=$GLOBALS['CI']->template->domain_upload_pckg_images($value['image']);
			$search_result['data']['TransferSearchResult']['TransferResults'][$key]['ImageHisUrl']=$GLOBALS['CI']->template->domain_upload_pckg_images($value['image']);
			$search_result['data']['TransferSearchResult']['TransferResults'][$key]['BookingEngineId']='ProvabCrs';
			$search_result['data']['TransferSearchResult']['TransferResults'][$key]['Promotion']='';
			$search_result['data']['TransferSearchResult']['TransferResults'][$key]['PromotionAmount']=0;
			$search_result['data']['TransferSearchResult']['TransferResults'][$key]['StarRating']=$value['rating'];
			$search_result['data']['TransferSearchResult']['TransferResults'][$key]['DestinationName']=$value['package_location'];
			$search_result['data']['TransferSearchResult']['TransferResults'][$key]['Price']=array(
			'TotalDisplayFare'=>$value['price'],
			'GSTPrice'=>0,
			'PriceBreakup'=>array(
			'AgentCommission'=>0,
			'AgentTdsOnCommision'=>0
			),
			'Currency'=>admin_base_currency()
			);
			$search_result['data']['TransferSearchResult']['TransferResults'][$key]['Description']=$value['package_description'];
			$search_result['data']['TransferSearchResult']['TransferResults'][$key]['Cancellation_available']='';
			$search_result['data']['TransferSearchResult']['TransferResults'][$key]['Cat_Ids']=array();
			$search_result['data']['TransferSearchResult']['TransferResults'][$key]['Sub_Cat_Ids']=array();
			$search_result['data']['TransferSearchResult']['TransferResults'][$key]['Supplier_Code']=$value['supplier_id'];
			$search_result['data']['TransferSearchResult']['TransferResults'][$key]['Duration']=$value['duration'].' Days';
			$search_result['data']['TransferSearchResult']['TransferResults'][$key]['ResultToken']=base64_encode(json_encode($value));
			$search_result['data']['TransferSearchResult']['TransferResults'][$key]['booking_source']=PROVAB_TRANSFERV1_SOURCE_CRS;
			}


			$search_result['Status']=1;
			$search_result['Message']=''; 
			// debug($search_result);exit;
		}

 		load_transferv1_lib(PROVAB_TRANSFERV1_SOURCE_CRS);
		$currency_obj = new Currency(array('module_type' => 'transferv1', 'from' => get_api_data_currency(), 'to' => get_application_currency_preference()));

		// debug($search_result);exit();
        $raw_sightseeing_result = $this->transferv1_lib_crs->search_data_in_preferred_currency($search_result, $currency_obj,'b2c');
                         //Display 
        $currency_obj = new Currency(array('module_type' => 'transferv1', 'from' => get_application_currency_preference(), 'to' => get_application_currency_preference()));

        $filters = array();
                       
        $formated_data = $this->transferv1_lib_crs->format_search_response($raw_sightseeing_result['data'], $currency_obj, $search_id, 'b2c', $filters); 

       // debug($formated_data);exit;
        ///////////////////////////////////TRANSFER DETAILS///////////////////
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
		$module_type = 'b2c_transferv1';
        $markup_level = 'level_2';
        $markup = $this->private_management_model->fetch_markup_custom($module_type,$markup_level);
       // $data['total_amount'] = ($data['no_adults']*$data ['package']->price)+($data['no_child']*$data ['package']->child_price)+($data['no_infant']*$data ['package']->infant_price)+($markup['value']*$passenger_count);

        //$markup = isset($markup['value'])? get_converted_currency_value ( $currency_obj->force_currency_conversion ( $markup['value']) ):0; 
        $markup = $markup['value'];
		$convinence_fees_row = $this->private_management_model->get_convinence_fees('transferv1');
		// debug($convinence_fees_row);exit();
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
		$gst = $this->private_management_model->fetch_gst($markup,'transfer');
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
        // $data['formated_data']=$formated_data['SSSearchResult']['SightSeeingResults'];
        $data['formated_data']=$formated_data['TransferSearchResult']['TransferResults'];
// debug($_POST);die;

             // debug($data);exit();          
   	  $this->template->view ( 'activities/pre_booking_traveler_details', $data );
   	  
		
   }

   public function get_api_countries() {
		$this->db->limit ( 10000 );
		$this->db->order_by ( "name", "asc" );
		$qur = $this->db->get ( "api_country_list" );
		return $qur->result ();
	}

}

