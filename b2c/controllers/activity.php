<?php
error_reporting(0);
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Activity extends CI_Controller {

    private $current_module;

    public function __construct() {
        parent::__construct();
        //we need to activate hotel api which are active for current domain and load those libraries
        $this->load->model('activity_model');
        $this->load->model('visa_model');
        $this->load->model ( 'Package_Model' );
      //  $this->load->library('social_network/facebook'); //Facebook Library to enable login button		
        $this->current_module = $this->config->item('current_module');
        //$this->load->library('accounting_api');
    }

    /**
     * index page of application will be loaded here
     */
    function index() {
        //	echo number_format(0, 2, '.', '');
    }

    
    //function search($search_id) {
    function search($search_id){

        $enquiry_origin = '';
        if(isset($_GET['enquiry_origin'])){
            $enquiry_origin = $_GET['enquiry_origin'];
        }
        // debug(META_SIGHTSEEING_COURSE);exit();
        $safe_search_data = $this->activity_model->get_safe_search_data($search_id,META_SIGHTSEEING_COURSE);

        // debug($safe_search_data);exit();
        // Get all the hotels bookings source which are active
       // $data["country"] = $this->visa_model->get_countries();
        $active_booking_source = $this->activity_model->active_booking_source();
        // debug($active_booking_source);exit;
        if ($safe_search_data['status'] == true and valid_array($active_booking_source) == true) {
            $safe_search_data['data']['search_id'] = abs($search_id);
            
            	$data["country"] = $this->custom_db->single_table_records('api_country_list', '*')['data'];
            	// $data['holiday_data'] = $this->custom_db->single_table_records('tours_country', '*')[data]; 
                 //	debug($data);die;
            $this->template->view('activity/search_result_page', array('sight_seen_search_params' => $safe_search_data['data'],
               'activity_search_params' => $safe_search_data['data'], 
                'active_booking_source' => $active_booking_source,
                'holiday_data' => $data,
                'enquiry_origin' => $enquiry_origin));
        } else {
            $this->template->view( 'general/popup_redirect');
        }
    }

    function activity_details()
    { //error_reporting(E_ALL);
        $params = $this->input->get();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
         // debug($params);exit;
      
        $safe_search_data = $this->activity_model->get_safe_search_data($params['search_id'],META_SIGHTSEEING_COURSE);
        // debug($safe_search_data);exit;
        $safe_search_data['data']['search_id'] = abs($params['search_id']);
        $currency_obj = new Currency(array('module_type' => 'sightseeing','from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
        // debug($currency_obj);exit();
        $search_id = $params['search_id'];
        if (isset($params['booking_source']) == true) {
            //We will load different page for different API providers... As we have dependency on API for hotel details page

            // $params['booking_source']= 'PTBSID00000000012';
            
            // debug($params);
            // debug(HOTELBED_ACTIVITIES_BOOKING_SOURCE);
            // debug(PROVAB_SIGHTSEEN_SOURCE_CRS);
            // exit;
            load_sightseen_lib($params['booking_source']);
            if ($params['booking_source'] == HOTELBED_ACTIVITIES_BOOKING_SOURCE &&  isset($params['op']) == true and
                $params['op'] == 'get_details' and $safe_search_data['status'] == true) {

                $params['result_token'] = urldecode($params['result_token']);
                // debug($params['result_token']);
            // debug('Shaik');exit;
            $raw_product_deails = $this->sightseeing_lib->get_product_details($safe_search_data,$params,$module='b2b');

                // debug($raw_product_deails);
                //     exit;

            if ($raw_product_deails['status']) {

                if($raw_product_deails['Price']){

                        //details data in preffered currency
                    

                    $Price = $this->sightseeing_lib->details_data_in_preffered_currency($raw_product_deails['Price'],$currency_obj,'b2b');
                    // debug($Price);exit;
                    $currency_obj = new Currency(array('module_type' => 'sightseeing','from' => get_application_currency_preference(), 'to' => get_application_currency_preference()));

                 


                }

                  // debug($raw_product_deails);exit;
                $this->template->view('activity/hotelbed/activity_details', array('currency_obj' => $currency_obj,'product_details' => $raw_product_deails, 'search_id'=>$search_id,'active_booking_source' => $params['booking_source'], 'params' => $params));
            } else {

                $msg= $raw_product_deails['Message'];

                //redirect(base_url().'index.php/sightseeing/exception?op='.$msg.'&notification=session');
            }
        } 
        
            elseif($params['booking_source'] == PROVAB_SIGHTSEEN_SOURCE_CRS && isset($params['result_token']) == true and isset($params['op']) == true and
            $params['op'] == 'get_details' and $safe_search_data['status'] == true)
            {

                // debug($params);
                redirect(base_url('index.php/activities/details/'.$params['product_code'].'/'.$params['search_id']));
            }

        else {
            redirect(base_url());
        }
    } else {
        redirect(base_url());
    }
}

function booking() {//error_reporting(E_ALL);
 if($this->input->post()){
    $pre_booking_params = $this->input->post();

         // debug($pre_booking_params);
         // exit;

    $currency_obj = new Currency ( array (
        'module_type' => 'transferv1',
        'from' => get_application_currency_preference (),
        'to' => get_application_default_currency () 
    ));
    
    $search_id = $pre_booking_params['search_id'];
    $safe_search_data = $this->activity_model->get_safe_search_data($search_id ,META_SIGHTSEEING_COURSE);

    $safe_search_data['data']['search_id'] = abs($search_id);
    $page_data['active_payment_options'] = $this->module_model->get_active_payment_module_list();

    $page_data['search_data'] = $safe_search_data['data'];

    $page_data['pax_details'] = "";
    $page_data['user_country_code'] = "";
    $page_data['booking_source'] = HOTELBED_ACTIVITIES_BOOKING_SOURCE;
    $page_data['pre_booking_params'] = array();
    $page_data['iso_country_list'] = $this->db_cache_api->get_iso_country_list();
    $page_data['country_list'] = $this->db_cache_api->get_country_list();
    $page_data['active_data'] = array();
    $page_data['phone_code'] = array();
    $page_data['search_id'] = $search_id;
    $page_data['product_details'] = $pre_booking_params['product_details'];

    $product_details = json_decode(base64_decode($pre_booking_params['product_details']),true);

     // debug($product_details);
     //     exit;


    //Get the country phone code 
    $Domain_record = $this->custom_db->single_table_records('domain_list', '*');
    $page_data['active_data'] =$Domain_record['data'][0];
    $temp_record = $this->custom_db->single_table_records('api_country_list', '*');
    $page_data['phone_code'] =$temp_record['data'];

    $page_data['pre_booking_params']['default_currency'] = get_application_currency_preference();
    $page_data['currency_obj']      = $currency_obj;

    $page_data['pre_booking_params']['total_pax'] = $pre_booking_params['total_pax'];
    $page_data['pre_booking_params']['activity_date'] = $pre_booking_params['activity_date'];
    $page_data['pre_booking_params']['from_date'] = $pre_booking_params['from_date'];
    $page_data['pre_booking_params']['to_date'] = $pre_booking_params['to_date'];
    $page_data['pre_booking_params']['adult'] = $pre_booking_params['no_of_adult'];
    $page_data['pre_booking_params']['child'] = $pre_booking_params['no_of_child'];
     $page_data['pre_booking_params']['vat'] = base64_decode($pre_booking_params['vat']);
    $page_data['pre_booking_params']['pickup_location'] = $pre_booking_params['pickup_location'];

    // debug($pre_booking_params['pickup_location']);exit();

    $page_data['pre_booking_params']['ProductName'] = "";
    $page_data['pre_booking_params']['modalities'] = $pre_booking_params['modalities'];
    $page_data['pre_booking_params']['TM_Cancellation_Policy'] = $pre_booking_params['cancel_policy'];
    
    $page_data['pre_booking_params']['markup_price_summary'] = array();
    $page_data['pre_booking_params']['markup_price_summary']['_GST'] = 0;
    $page_data['pre_booking_params']['markup_price_summary']['TotalDisplayFare'] = $product_details['Price']['TotalDisplayFare'];
    $page_data['pre_booking_params']['markup_price_summary']['NetFare'] = $product_details['Price']['TotalDisplayFare'];
    
    $page_data['pre_booking_params']['AgeBands'] = array(
        "bandId" => 1

    );
    $page_data['pre_booking_params']['AgeBands'][0] = array("bandId"=>1,"count"=>$pre_booking_params['no_of_adult']);
    $page_data['pre_booking_params']['AgeBands'][1] = array("bandId"=>2,"count"=>$pre_booking_params['no_of_child']);
    
    $page_data['pre_booking_params']['Price'] = $product_details['Price'];

    
    // $page_data['pre_booking_params']['Price']['Currency'] = 'INR';
    // $page_data['pre_booking_params']['Price']['TotalDisplayFare'] = $product_details['Price']['TotalDisplayFare'];
    // $page_data['pre_booking_params']['Price']['AgentCommission'] = 0;
    // $page_data['pre_booking_params']['Price']['AgentTdsOnCommision'] = 0;
    // $page_data['pre_booking_params']['Price']['NetFare'] = 0;

    $page_data['pre_booking_params']['API_Price']['Currency'] = 'INR';

    $page_data['pre_booking_params']['API_Price']['TotalDisplayFare'] = 0;
    $page_data['pre_booking_params']['API_Price']['AgentCommission'] = 0;
    $page_data['pre_booking_params']['API_Price']['AgentTdsOnCommision'] = 0;
    $page_data['pre_booking_params']['API_Price']['NetFare'] = 0;

    $page_data['pre_booking_params']['price_summary'] = array(
        "TotalDisplayFare" => 0,
        "NetFare" => 0,
        "_Markup" => 0
    );
    $page_data['traveller_details'] = $this->user_model->get_current_user_details();

    // debug($page_data);exit;
    // $currency_obj = new Currency ( array (
    //         'module_type' => 'sightseeing',
    //         'from' => admin_base_currency (),
    //         'to' => admin_base_currency () 
    //     ) );
    //     ******** Convinence Fees Start *******
    //     $search_data = $temp_token['AgeBands'];

    //     $convenience_fees = $currency_obj->convenience_fees($amount, $search_data);
    //     $page_data['pre_booking_params']['convenience_fees'] = $convenience_fees;


     // $page_data['pre_booking_params']['markup_price_summary'] => array(
     //    "TotalDisplayFare" => 0,
     //    "NetFare" => 0,
     //    "_GST" => 0,
     //    "_Markup" => 0
     // );

    // $page_data['pre_booking_params']['API_TM_Price'] => array();
    // $page_data['pre_booking_params']['API_TM_Price']['TotalDisplayFare'] = 0;
    // $page_data['pre_booking_params']['API_TM_Price']['GSTPrice'] = 0;
    // $page_data['pre_booking_params']['API_TM_Price']['Currency'] = 'INR';
    // $page_data['pre_booking_params']['API_TM_Price']['PriceBreakup'] = array();  
    // $page_data['pre_booking_params']['API_TM_Price']['PriceBreakup']['AgentCommission'] = 0;
    // $page_data['pre_booking_params']['API_TM_Price']['PriceBreakup']['AgentTdsOnCommision'] = 0;



        //debug($page_data); exit();
        //echo 'hi';exit;
        // $input_rate_key = @$data['rateKey'];
        // $safe_search_data = $this->activity_model->get_safe_search_data($search_id);
        // $search_from = $safe_search_data['data']['from_date'];
        // $search_to = $safe_search_data['data']['to_date'];
        // $modalty_name = $data['hidmodality_name'];

    //$input_rate_key = "fghfghfg";
        // $search_from = $safe_search_data['data']['from_date'];
        // $search_to = $safe_search_data['data']['to_date'];
        // $modalty_name = $data['hidmodality_name'];

    $this->template->view('activity/hotelbed/activity_booking_page', $page_data);
}else{
    redirect(base_url().'index.php/sightseeing/exception?op=Remote IO error @ Activities Booking.&notification=(Unexpected page refresh)');
}

}



// function  pre_booking($search_id) 
//     {
//         exit('Booking has been blocked..!<a href="'.base_url().'">Go to Home</a>');
//     }
// _29_04_2020

function pre_booking($search_id) {

    echo "BOOKING BLOCKED BY ADMIN";exit;
    
    // error_reporting(E_ALL);
    $post_params = $this->input->post();

// exit('Booking has been blocked..!<a href="'.base_url().'">Go to Home</a>');
// debug($post_params);exit();
    // $post_params['billing_city'] = 'Beirut';
    // $post_params['billing_zipcode'] = '560100';
    // $post_params['billing_address_1'] = 'Ground floor, Tina center,Ain elmrayse, Beirut, Lebanon.';
    $post_params['search_id'] = $search_id;

        //Make sure token and temp token matches
    $valid_temp_token = unserialized_data($post_params['token'], $post_params['token_key']);

    if ($valid_temp_token != false) {

        load_sightseen_lib($post_params['booking_source']);
        /****Convert Display currency to Application default currency***/
            //After converting to default currency, storing in temp_booking
        $post_params['token'] = unserialized_data($post_params['token']);
        $currency_obj = new Currency ( array (
            'module_type' => 'transferv1',
            'from' => get_application_currency_preference (),
            'to' => get_application_default_currency () 
        ));
        //debug($post_params['token']);
        $post_params['token'] = $this->sightseeing_lib->convert_token_to_application_currency($post_params['token'], $currency_obj, $this->current_module);
            // debug($post_params['token']);
            // exit;
        $post_params['token'] = serialized_data($post_params['token']);

        $temp_token = unserialized_data($post_params['token']);
            //Insert To temp_booking and proceed
        $temp_booking = $this->module_model->serialize_temp_booking_record($post_params, SIGHTSEEING_BOOKING);

        /*echo 'serialize(value)';
        debug($temp_booking); exit;*/
        $book_id = $temp_booking['book_id'];
        $book_origin = $temp_booking['temp_booking_origin'];

            // debug($post_params['booking_source']);
            // exit;
        $balance = agent_current_application_balance();
        //debug($balance['value']);exit;
        if ($post_params['booking_source'] == HOTELBED_ACTIVITIES_BOOKING_SOURCE) {
           
            $amount   = $this->sightseeing_lib->total_price($temp_token['Price']);
            $currency = $temp_token['default_currency'];
        }
       
        $currency_obj = new Currency ( array (
            'module_type' => 'sightseeing',
            'from' => admin_base_currency (),
            'to' => admin_base_currency () 
        ) );
        /********* Convinence Fees Start ********/
        $search_data = $temp_token['AgeBands'];

        $convenience_fees = $currency_obj->convenience_fees($amount, $search_data);
        /********* Convinence Fees End ********/

        /********* Promocode Start ********/
            //$promocode_discount = $post_params['promo_code_discount_val'];
        $promocode_discount = $post_params['promo_actual_value'];
        /********* Promocode End ********/

            //details for PGI

        $email = $post_params ['billing_email'];
        $phone = $post_params ['passenger_contact'];
        $verification_amount = roundoff_number($amount);
        
            //$verification_amount = roundoff_number($amount);
        $firstname = $post_params ['first_name'] ['0'];
        $productinfo = META_SIGHTSEEING_COURSE;
        //check current balance before proceeding further
        $domain_balance_status = $this->domain_management_model->verify_current_balance($verification_amount, $currency);
        //debug($domain_balance_status); die;   
        //debug($post_params['payment_method']);exit;
   //      debug(PAY_NOW);
   //      debug(OFFLINE_PAYMENT);

 		// debug($post_params);
   //      exit;
        if ($domain_balance_status == true) {
            switch($post_params['payment_method']) {
                case ONLINE_PAYMENT :
                $this->load->model('transaction');
                $pg_currency_conversion_rate = $currency_obj->payment_gateway_currency_conversion_rate();
                $this->transaction->create_payment_record($book_id, $verification_amount, $firstname, $email, $phone, $productinfo, $convenience_fees, $promocode_discount, $pg_currency_conversion_rate);
                redirect(base_url().'index.php/activity/process_booking/'.$book_id.'/'.$book_origin);      
                // redirect(base_url().'index.php/payment_gateway/payment/'.$book_id.'/'.$book_origin);                        
                break;
                case OFFLINE_PAYMENT :
                $this->load->model('transaction');
                $pg_currency_conversion_rate = $currency_obj->payment_gateway_currency_conversion_rate();
                $this->transaction->create_payment_record($book_id, $verification_amount, $firstname, $email, $phone, $productinfo, $convenience_fees, $promocode_discount, $pg_currency_conversion_rate);
                redirect(base_url().'index.php/activity/process_booking/'.$book_id.'/'.$book_origin);      
                // redirect(base_url().'index.php/payment_gateway/payment/'.$book_id.'/'.$book_origin);                        
                break;
                case PAY_AT_BANK : echo 'Under Construction - Remote IO Error';exit;
                break;
            }
        } else {
            redirect(base_url().'index.php/sightseeing/exception?op=Insufficient Amount Activities Booking&notification=insufficient_balance');
        }
    } else {
        redirect(base_url().'index.php/sightseeing/exception?op=Remote IO error @ Activities Booking&notification=validation');
    }
}

/*
        process booking in backend until show loader 
    */
        function process_booking($book_id, $temp_book_origin){
            
            if($book_id != '' && $temp_book_origin != '' && intval($temp_book_origin) > 0){

                $page_data ['form_url'] = base_url () . 'index.php/activity/secure_booking';
                $page_data ['form_method'] = 'POST';
                $page_data ['form_params'] ['book_id'] = $book_id;
                $page_data ['form_params'] ['temp_book_origin'] = $temp_book_origin;

                $this->template->view('share/loader/booking_process_loader', $page_data);   

            }else{
                redirect(base_url().'index.php/sightseeing/exception?op=Invalid request&notification=validation');
            }
            
        }

/**
     * Elavarasi
     *Do booking once payment is successfull - Payment Gateway
     *and issue voucher
     */
function secure_booking()
{
    //error_reporting(E_ALL);
    $post_data = $this->input->post();
    // debug($post_data);exit;
    if(valid_array($post_data) == true && isset($post_data['book_id']) == true && isset($post_data['temp_book_origin']) == true &&
        empty($post_data['book_id']) == false && intval($post_data['temp_book_origin']) > 0){
            //verify payment status and continue
        $book_id = trim($post_data['book_id']);
        $temp_book_origin = intval($post_data['temp_book_origin']);
    } else{
        redirect(base_url().'index.php/sightseeing/exception?op=InvalidBooking&notification=invalid');
    }       
        //run booking request and do booking
        $temp_booking = $this->module_model->unserialize_temp_booking_record($book_id, $temp_book_origin);
        // debug($temp_booking); die;
        $promo_currency_obj = new Currency(array('module_type' => 'sightseeing', 'from' => get_application_currency_preference(), 'to' => admin_base_currency()));

                //Delete the temp_booking record, after accessing
        $this->module_model->delete_temp_booking_record ($book_id, $temp_book_origin);
        load_sightseen_lib($temp_booking['booking_source']);
                //verify payment status and continue        

        $total_booking_price = $this->sightseeing_lib->total_price($temp_booking['book_attributes']['token']['markup_price_summary']);      


        $currency = $temp_booking['book_attributes']['token']['default_currency'];
                //also verify provab balance
                //check current balance before proceeding further
        $domain_balance_status = $this->domain_management_model->verify_current_balance($total_booking_price, $currency);
               
        // debug($domain_balance_status);exit();
if ($domain_balance_status) {
            //lock table


    // debug($temp_booking['booking_source']);exit();
    if ($temp_booking != false) {
        switch ($temp_booking['booking_source']) {
            case HOTELBED_ACTIVITIES_BOOKING_SOURCE :
                        //FIXME : COntinue from here - Booking request
            $booking = $this->sightseeing_lib->process_booking($book_id, $temp_booking['book_attributes'],$module='b2b');
                        //Save booking based on booking status and book id
            // echo 'Booking';
            // debug($booking); die;
            break;
        } 
// debug($booking); die;
                if ($booking['status'] == SUCCESS_STATUS) { //error_reporting(E_ALL);

                    $currency_obj = new Currency(array('module_type' => 'sightseeing', 'from' => admin_base_currency(), 'to' => admin_base_currency()));
                    $agent_commission=$this->domain_management_model->sightseeing_commission_details_get();
                   // $booking['data']['currency_obj'] = $currency_obj;
                   //  $booking['data']['promo_currency_obj'] = $promo_currency_obj;

                     //debug($booking);
                    // exit();
                    if($GLOBALS['CI']->loyalty_program_on_off)
                    {
                        $agent_loyalty_amt = $total_booking_price;
                        // echo "dd";exit;
                        $this->load->model('loyalty_program_model');
                        $get_master_module_status=$this->loyalty_program_model->get_master_module_status('Excursion');
                        if($get_master_module_status['status']=='ENABLE')
                        {



                            $current_user_id = $GLOBALS['CI']->entity_user_id;
                            $get_agent_module_status=$this->loyalty_program_model->get_agent_module_status('Excursion',$current_user_id);
                            if($get_agent_module_status)
                            {
                                if($get_agent_module_status['status'])
                                {

                                    $agent_base_currency = agent_base_currency();
                                    if($agent_base_currency=="AED")
                                    {
                                        $agent_loyalty_amt=$agent_loyalty_amt;
                                    }
                                    else
                                    {
                                        $this->load->model('domain_management_model');
                                        $currency_obj_m = new Currency(array('module_type' =>'hotel', 'from' => $agent_base_currency, 'to' =>'AED'));
                                        $strCurrency_m  = $currency_obj_m->get_currency($agent_loyalty_amt, true, false, true, false, 1);
                                        $amount=$strCurrency_m['default_value'];
                                        $agent_loyalty_amt=$amount;
                                    }


                                    $get_range_point=$this->loyalty_program_model->get_master_range_point($get_master_module_status['id'],$agent_loyalty_amt);
                                    if($get_range_point)
                                    {
                                        $get_range_point=$get_range_point;
                                    }
                                    else
                                    {
                                        $get_range_point=$get_master_module_status['defult_value'];
                                    }

                                    $check_user_amount=$this->loyalty_program_model->check_user_amount($current_user_id);
                                    if($check_user_amount){
                                        $status=1;
                                    }
                                    else
                                    {
                                       $status=0; 
                                    }
                                    $reward_point=$get_range_point;
                                   
                                    $expiredate=$this->loyalty_program_model->get_activity_checkout_date($book_id);
                                    $agentemailid=provab_encrypt($GLOBALS['CI']->entity_email);
                                    $agent_name=$GLOBALS['CI']->entity_name;

                                    $trans_data=array('agent_id'=>$current_user_id,'booking_type'=>'activities','booking_reference'=>$book_id,'reward_point'=>$reward_point,'expire_date'=>$expiredate,'created_date'=>date('Y-m-d'),'tr_type'=>'Credit','description'=>'Excursion booking reward','status'=>$status);


                                    $this->loyalty_program_model->insert_transation($trans_data);
                                    $get_agent_total_reward=$this->loyalty_program_model->get_agent_total_reward($current_user_id);
                                    $reward_total=$get_agent_total_reward['t_reward']+$reward_point;
                                    $transfer=$get_agent_total_reward['activities']+$reward_point;
                                    $up_data=array('t_reward'=>$reward_total,'activities'=>$transfer);
                                    $this->loyalty_program_model->update_total_reward_point($current_user_id,$up_data);



                                }
                            }    
                        }
                    }
                    

                    $booking['data']['booking_params'] = $temp_booking['book_attributes'];
                    $booking['data']['currency_obj']  = $currency_obj;  
                    $booking['data']['agent_commission']  = $agent_commission;  
                    // debug( $booking); die;
                    //Save booking based on booking status and book id
                    $data = $this->sightseeing_lib->save_booking($book_id, $booking['data'],$module='b2b');
    
                    // $data['booking_status']=1;
                    //debug($data['fare']);exit("agf");
                    $this->domain_management_model->update_transaction_details('sightseeing', $book_id, $data['fare'], $data['admin_markup'], $data['agent_markup'], $data['convinence'], $data['discount'],$data['transaction_currency'], $data['currency_conversion_rate'] );
                    

                    $this->accounting_api->process_service($book_id, $data,'TransactionCRUD',HOTELBED_ACTIVITIES_BOOKING_SOURCE);
                    
                    // debug($data['booking_status']);exit();
                    redirect(base_url().'index.php/voucher/activity/'.$book_id.'/'.$temp_booking['booking_source'].'/'.$data['booking_status'].'/show_voucher');
                } else {
                    redirect(base_url().'index.php/sightseeing/exception?op=booking_exception&notification='.$booking['Message']);
                }
            }
            //release table lock
        } else {
            redirect(base_url().'index.php/sightseeing/exception?op=Remote IO error @ Insufficient&notification=validation');
        }
        //redirect(base_url().'index.php/hotel/exception?op=Remote IO error @ Hotel Secure Booking&notification=validation');
    }

}
