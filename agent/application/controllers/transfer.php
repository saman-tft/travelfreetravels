<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Transfer extends CI_Controller {

    private $current_module;

    public function __construct() {
        parent::__construct();
        //we need to activate hotel api which are active for current domain and load those libraries
        $this->load->model('transfer_model');
        $this->load->library('social_network/facebook'); //Facebook Library to enable login button	
        	$this->load->library('rewards');
        $this->current_module = $this->config->item('current_module');
      //  $this->load->library('accounting_api');
    }

    /**
     * index page of application will be loaded here
     */
    function index() {
        //	echo number_format(0, 2, '.', '');
    }

    /**
     *  Balu A
     * Load Hotel Search Result
     * @param number $search_id unique number which identifies search criteria given by user at the time of searching
     */
    function search($search_id) {
        // error_reporting(E_ALL);
        // echo $search_id;exit;
        $safe_search_data = $this->transfer_model->get_safe_search_data($search_id);
        // Get all the hotels bookings source which are active
        $active_booking_source = $this->transfer_model->active_booking_source();
      
        if ($safe_search_data['status'] == true and valid_array($active_booking_source) == true) {
            $safe_search_data['data']['search_id'] = abs($search_id);
            $this->template->view('transfer/search_result_page', array('transfer_search_params' => $safe_search_data['data'], 'active_booking_source' => $active_booking_source));
        } else {
            $this->template->view('general/popup_redirect');
        }
    }
// function transfer_details_api($booking_source){
//         //error_reporting(0);
//       $params = $this->input->get();

//          //debug($params);exit;
      
//         $safe_search_data = $this->transfer_model->get_safe_search_data($params['search_id'],META_SIGHTSEEING_COURSE);
//         // debug($safe_search_data);exit;
//         $safe_search_data['data']['search_id'] = abs($params['search_id']);
//         $currency_obj = new Currency(array('module_type' => 'transfers','from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
//         // debug($currency_obj);exit();
//         $search_id = $params['search_id'];
//         if (isset($params['booking_source']) == true) {
//             //We will load different page for different API providers... As we have dependency on API for hotel details page
//             // debug($params['booking_source']);exit;
//             load_sightseen_lib($params['booking_source']);
//             if ($params['booking_source'] == HOTELBED_TRANSFER_BOOKING_SOURCE &&  isset($params['op']) == true and
//                 $params['op'] == 'get_details' and $safe_search_data['status'] == true) {

//                 $params['result_token'] = urldecode($params['result_token']);
//                 // debug($params['result_token']);
//             // debug('Shaik');exit;
//             $raw_product_deails = $this->transfer_lib->add_service_to_Cart($safe_search_data,$params,$module='b2b');

//                 // debug($raw_product_deails);
//                 // exit;

//             if ($raw_product_deails['status']) {

//                 if($raw_product_deails['Price']){

//                         //details data in preffered currency
                    

//                     $Price = $this->transfer_lib->search_data_in_preferred_currency($raw_product_deails['Price'],$currency_obj,'b2b');

//                     $currency_obj = new Currency(array('module_type' => 'transfers','from' => get_application_currency_preference(), 'to' => get_application_currency_preference()));

//                         //calculation Markup 
//                         // $raw_product_deails['data']['ProductDetails']['SSInfoResult']['Price'] = $this->sightseeing_lib->update_booking_markup_currency($Price,$currency_obj,$search_id, false, true, 'b2c');   
//                        // debug($raw_product_deails);
//                        // exit;

//                 }

//                 // debug($raw_product_deails);exit;
//                 $this->template->view('transfer/transfer_detail', array('currency_obj' => $currency_obj,'product_details' => $raw_product_deails, 'search_id'=>$search_id,'active_booking_source' => $params['booking_source'], 'params' => $params));
//             } else {

//                 $msg= $raw_product_deails['Message'];

//                 redirect(base_url().'index.php/transfer/exception?op='.$msg.'&notification=session');
//             }
//         } 
        
//             elseif($params['booking_source'] == PROVAB_SIGHTSEEN_SOURCE_CRS && isset($params['result_token']) == true and isset($params['op']) == true and
//             $params['op'] == 'get_details' and $safe_search_data['status'] == true)
//             {
//                 redirect(base_url('index.php/transfer/details/'.$params['product_code'].'/'.$params['search_id']));
//             }

//         else {
//             redirect(base_url());
//         }
//     } else {
//         redirect(base_url());
//     }
         
//     }
    function transfer_details_api($booking_source){
        //error_reporting(0); 
        $flight_array   = array();
        $get_info       = array();

        $params = $this->input->post();
        // debug($params);exit;
        if(!isset($params['booking_source']) && $params['booking_source'] == ""){
            $data                               = array();
            $get_info                           = $_GET;
            $data                               = json_decode(base64_decode($get_info['add_to_service']),1);
            foreach ($data as $key => $value) {
                $params[$key][0] = $value;
            }
            #debug($get_info); exit;
            $transfer                           = json_decode(base64_decode($get_info['transfer']),1);
            $params['currency']                 = $transfer['currency'];
            $params['avai_token']               = $transfer['avail_token'];
            $params['customer_waiting_time']    = $transfer['customer_waiting_time'];
            $params['booking_source']           = $booking_source;
            $params['search_id']                = $get_info['search_id'];
        }


        // debug($params); exit;

        if($params['currency'] != get_application_default_currency()){
            $params['currency']     = get_application_default_currency();
        }
        // debug($params); exit;

        $search_id = $params['search_id'];
        $safe_search_data = $this->transfer_model->get_safe_search_data($search_id);
        $total_pax = $safe_search_data['data']['adult']+$safe_search_data['data']['child'];
        $convenience_fees = $GLOBALS['CI']->transfer_model->get_convenience_fees();
                    if(!empty($convenience_fees)){
                        if($convenience_fees[0]['per_pax']!=0){
                            $convenience_fees_amt = $convenience_fees[0]['value']*$total_pax;
                        }else{
                            $convenience_fees_amt = $convenience_fees[0]['value'];
                        }

                    }
        $hotel_data_array = array();
        $safe_search_data['data']['search_id'] = abs($search_id);
        if(isset($safe_search_data['data']['from_transfer_type']) && $safe_search_data['data']['from_transfer_type']=='ProductTransferHotel')
        {
            $hotel_data_from = $this->transfer_model->get_searched_hotel_address($safe_search_data['data']['from_code']);
            $hotel_data_array['hotel_data_from']= $hotel_data_from[0];
        }

        if(isset($safe_search_data['data']['to_transfer_type']) && $safe_search_data['data']['to_transfer_type']=='ProductTransferHotel')
        {
            $hotel_data_to = $this->transfer_model->get_searched_hotel_address($safe_search_data['data']['to_code']);
            $hotel_data_array['hotel_data_to']= $hotel_data_to[0];
        }

        $hotel_data_array['search_data'] = $safe_search_data['data'];
        // debug($hotel_data_array);exit;
        $flight_array['depature_time_flight'] = $safe_search_data['data']['depature_time_flight'];
        if(isset($safe_search_data['data']['return_time_flight']))
        {
            $flight_array['return_time_flight'] = $safe_search_data['data']['return_time_flight'];
        }

        // debug($params['booking_source']);exit;
        $trip_type=$safe_search_data['data']['trip_type'];//exit;
        $currency_obj = new Currency(array('module_type' => 'transfers', 'from' => get_application_default_currency(), 'to' => get_application_default_currency()));
        // debug($currency_obj);exit;
        // debug($params['booking_source']);exit;
        if (isset($params['booking_source']) == true) {
            load_transfer_lib($params['booking_source']);
            if ($params['booking_source'] == HOTELBED_TRANSFER_BOOKING_SOURCE && isset($params['transfer_code']) == true) {
                //$params['transfer_code']  = urldecode($params['transfer_code']);

                /*add to cart API*/
                $raw_transfer_details = $this->transfer_lib->add_service_to_Cart($safe_search_data['data'], $params);
                // debug($raw_transfer_details); exit;

                if(isset($raw_transfer_details['status']) && $raw_transfer_details['status'] == true) {
                    //$fileName = BASEPATH . '../XMLs/transfer.json';
                    //file_put_contents($fileName,$raw_transfer_details['data']);
                    //For XML to Array convertion
                    foreach($raw_transfer_details['data'] as $d_key => $transfer_data) {
                        $xml = simplexml_load_string($transfer_data);
                        $transferjson = json_encode($xml);
                        $transfer[] = json_decode($transferjson,TRUE);
                    }

                    // debug($transfer);
                    // debug($params);
                    //  exit;
                    if(isset($transfer) && valid_array($transfer) && !isset($transfer[0]['ErrorList']) && !isset($transfer[1]['ErrorList']) && isset($transfer[0])){

                        // debug($transfer);exit();
                        $raw_transfer_book = $this->transfer_lib->formate_service_add_data($transfer,$params,$module='b2b');
                        // debug($raw_transfer_book);exit;
                        $total_amount = $raw_transfer_book['data']['total_amount'];
                        $raw_transfer_book['data']['sub_amount']=$raw_transfer_book['data']['sub_amount'];
                        $traveller_details = $this->user_model->get_current_user_details();
                        
                        $raw_transfer_book['data']['service_tax'] = $raw_transfer_book['data']['service_tax'];
                            
                        $tamt = $total_amount;

                        //Convenience Fee
                        #$c_fee= $this->transfer_model->get_cfee();
                        $convinence = 0;
                        $currency_obj = new Currency(array('module_type' => 'transfers', 'from' => get_application_default_currency(), 'to' => get_application_default_currency()));

                         // debug($currency_obj);exit();

                        $convinence = $currency_obj->convenience_fees($total_amount,$search_id);
                        // get_application_display_currency_preference
                        //$traveller_details = $this->user_model->get_current_user_details();
                        //debug($traveller_details);exit;
                        $raw_transfer_book['data']['convinence_fee']    = $convenience_fees_amt;
                        $raw_transfer_book['data']['total_amount']      =  $tamt; //round($total_amount+$service_amount);
                         // debug($raw_transfer_book); exit;
                        //save to cache
                        $this->transfer_lib->cache_transfer_details($raw_transfer_book['data']);
                        if(!empty($this->entity_country_code)){
                            $page_data['user_country_code'] = $this->entity_country_code;
                        }else{
                            $page_data['user_country_code'] = '';   
                        }
                        $total_cnt = count($raw_transfer_book['data']['purchase_details']);
                        $total_cnt=$total_cnt-1;
                         $purchase_details = $raw_transfer_book['data']['purchase_details'][$total_cnt];
                        $page_data['search_id'] =  $params['search_id'];
                        $page_data['booking_source'] = $params['booking_source'];
                        $page_data['traveller_details'] = $traveller_details;
                        $Domain_record = $this->custom_db->single_table_records('domain_list', '*');
                        $page_data['active_data'] =$Domain_record['data'][0];
                        $temp_record = $this->custom_db->single_table_records('api_country_list', '*');
                        $page_data['phone_code'] =$temp_record['data'];
                        $page_data['tax_service_sum']   = '';
                        $page_data['convenience_fees']  = $convenience_fees_amt;
                        $page_data['total_price']       = '';
                        $pre_booking_params['markup_price_summary'] = array(
                            'TotalDisplayFare'=>$raw_transfer_book['data']['total_amount'],
                            'NetFare'=>$raw_transfer_book['data']['total_amount'],
                            '_GST'=>$raw_transfer_book['data']['service_tax'],
                            '_Markup'=>$raw_transfer_book['data']['agent_markup']+$raw_transfer_book['data']['admin_markup']);
                        $pre_booking_params['ProductImage']=$purchase_details['image'];
                        $pre_booking_params['ProductName']=$purchase_details['vehicle_type'];
                        $pre_booking_params['transfer_info'] = $purchase_details['transfer_info'];
                        $pre_booking_params['pickup'] = $purchase_details['pickup_location_name'];
                        $pre_booking_params['destination'] = $purchase_details['destination_location_name'];
                        $pre_booking_params['transfer_type'] = $purchase_details['transfer_type'];
                        $pre_booking_params['cancellation_policies'] = $purchase_details['cancellation_policies'];
                        $pre_booking_params['adult_count'] = $raw_transfer_book['data']['adult_count'];
                        $pre_booking_params['child_count'] = $raw_transfer_book['data']['child_count'];
                        
                        // $pre_booking_params['GradeCode']='';
                        // $pre_booking_params['GradeDescription']='';
                        // $pre_booking_params['StarRating']='';
                        // $pre_booking_params['DeparturePointAddress']='';
                        // $pre_booking_params['DeparturePoint']='';
                        $pre_booking_params['Destination']='';
                        $page_data['pre_booking_params'] = $pre_booking_params;
                        $page_data['pre_booking_params']['default_currency'] = get_application_currency_preference();
                        $page_data['hotel_data_array']  = $hotel_data_array;
                        $page_data['iso_country_list']  = $this->db_cache_api->get_iso_country_list();
                        $page_data['country_list']      = $this->db_cache_api->get_country_list();
                        $currency_obj = new Currency(array('module_type' => 'transferv1', 'from' => get_application_currency_preference(), 'to' => get_application_currency_preference()));
                        $page_data['currency_obj']      = $currency_obj;
                        $page_data['raw_transfer_book'] = $raw_transfer_book['data'];
                        $page_data['trip_type'] = $trip_type;
                        $page_data['flight_time'] = $flight_array;
                        $page_data['hotel_data_array'] = $hotel_data_array;
                        $page_data['raw_transfer_book'] = $raw_transfer_book['data'];
                        $page_data['depature'] = $safe_search_data['data']['depature'];
                        $page_data['depature_time_flight']  = date("g:i A", strtotime($safe_search_data['data']['depature_time_flight']));
                        // debug($page_data);exit;
                        switch($params['booking_source']) {
                            case HOTELBED_TRANSFER_BOOKING_SOURCE :
                                /*$transfer_book = $this->template->isolated_view('transfer/viator_booking_page',
                                array('currency_obj' => $currency_obj, 'raw_transfer_book' => $raw_transfer_book['data'],
                                'trip_type' => $trip_type,'flight_time' => $flight_array,'hotel_data_array' => $hotel_data_array,
                                'search_id' => $params['search_id'], 'traveller_details' => $traveller_details, 'booking_source' => $params['booking_source']
                                )
                                );*/
                              //  $this->template->view('transfer/viator_booking_page',$page_data);
                                    
                                break;
                            default : exit;
                        }
                        //$time_line['view'] = microtime(true)-$time_line['view'];
                       // $response['data'] = get_compressed_output($transfer_book);
                        
                    // debug($page_data); exit;  
                        $response['status'] = SUCCESS_STATUS;
                        $this->template->view('transfer/viator_booking_page',$page_data);
                    }else {
                        redirect(base_url().'index.php/transfer/exception?op=Remote IO error - Cache has no data @ Insufficient&notification=validation');
                    }

                }
            }
             
        }else {
            redirect(base_url().'index.php/transfer/exception?op=Do not refresh the page &notification=data loss');
        }
         
    }
 function transfer_details()
    { 
        //error_reporting(E_ALL);
        $params = $this->input->get();

         // debug($params);exit;
      
        $safe_search_data = $this->transfer_model->get_safe_search_data($params['search_id'],META_TRANSFER_COURSE);
        // debug($safe_search_data);exit;
        $safe_search_data['data']['search_id'] = abs($params['search_id']);
        $currency_obj = new Currency(array('module_type' => 'transfers','from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
        // debug($currency_obj);exit();
        $search_id = $params['search_id'];
        if (isset($params['booking_source']) == true) {
            //We will load different page for different API providers... As we have dependency on API for hotel details page
            load_transfer_lib($params['booking_source']);
            if ($params['booking_source'] == HOTELBED_TRANSFER_BOOKING_SOURCE &&  isset($params['op']) == true and
                $params['op'] == 'get_details' and $safe_search_data['status'] == true) {

                $params['result_token'] = urldecode($params['result_token']);

            $raw_product_deails = $this->transfer_lib->get_product_details($safe_search_data,$params,$module='b2b');

                // debug($raw_product_deails);
                // exit;

            if ($raw_product_deails['status']) {

                if($raw_product_deails['Price']){

                        //details data in preffered currency
                    

                    $Price = $this->transfer_lib->details_data_in_preffered_currency($raw_product_deails['Price'],$currency_obj,'b2b');

                    $currency_obj = new Currency(array('module_type' => 'transfers','from' => get_application_currency_preference(), 'to' => get_application_currency_preference()));

                        //calculation Markup 
                        // $raw_product_deails['data']['ProductDetails']['SSInfoResult']['Price'] = $this->sightseeing_lib->update_booking_markup_currency($Price,$currency_obj,$search_id, false, true, 'b2c');   
                       // debug($raw_product_deails);
                       // exit;

                }

// debug($raw_product_deails);exit;
                $this->template->view('activity/hotelbed/activity_details', array('currency_obj' => $currency_obj,'product_details' => $raw_product_deails, 'search_id'=>$search_id,'active_booking_source' => $params['booking_source'], 'params' => $params));
            } else {

                $msg= $raw_product_deails['Message'];

                redirect(base_url().'index.php/transfer/exception?op='.$msg.'&notification=session');
            }
        } 
        
            elseif($params['booking_source'] == PROVAB_TRANSFER_SOURCE_CRS && isset($params['result_token']) == true and isset($params['op']) == true and
            $params['op'] == 'get_details' and $safe_search_data['status'] == true)
            {
                redirect(base_url('index.php/transfer/details/'.$params['price_id'].'/'.$params['search_id']));
            }

        else {
            redirect(base_url());
        }
    } else {
        redirect(base_url());
    }
}
        /**
     * Transfer Booking request
     * */
    // function pre_booking(){ 
    //     $params = $this->input->post();
    //     $params['billing_city'] = 'Bangalore';
    //     $params['billing_zipcode'] = '560100';
    //     $params['billing_address_1'] = '2nd Floor, Venkatadri IT Park, HP Avenue,, Konnappana Agrahara, Electronic city';
    //    // $search_id = $params['search_id'];
    //     $params['payment_method'] = 'PNHB1';
    //     //$promocode = @$params['promocode_val'];
    //     //$promo_data = $this->domain_management_model->promocode_details($promocode);
    //     //$promocode_discount_val = @$promo_data['value'];
    //     //$promocode_discount_type = @$promo_data['value_type'];
         
    //     /*$safe_search_data = $this->transfer_model->get_safe_search_data($search_id);
    //     $safe_search_data['data']['search_id'] = abs($search_id);*/
         
    //     //$transaction_id = PROJECT_PREFIX .'-'. TRANSFER_BOOKING . '-' . date ( 'dmHi' ) . '-' . rand ( 1000, 9999 ); // FIX ME - generate 25 character code
    //     #$transaction_id = 'TR'.  date ( 'dmYHi' ) .rand ( 1000, 9999 ); // FIX ME - generate 25 character code
        
    //     //debug($params);exit;
    //     $temp_booking = $this->module_model->serialize_temp_booking_record($params, TRANSFER_BOOKING);
    // //debug($temp_booking);exit;

    //     $book_id = $temp_booking['book_id'];
    //     $book_origin = $temp_booking['temp_booking_origin'];
    //     $params['book_id'] = $temp_booking['book_id'];
    //     $params['book_origin'] = $temp_booking['temp_booking_origin'];
    //     $trip=$params['triptype'];
    //     if (isset($params['token']) == true) {
    //         $token = unserialized_data($params['token'], $params['token_key']);
    //         //debug($token);exit;
    //         if ($params['booking_source'] == HOTELBED_TRANSFER_BOOKING_SOURCE) {
    //             /*$amount     = $this->hotel_lib->total_price($temp_token['markup_price_summary']);
    //              $currency = $temp_token['default_currency'];*/

    //             $amount   = $token['total_amount'];
    //             $currency_code = $token['currency_code'];
    //             $convenience_fees = $token['convinence_fee'];
    //         }
                
    //         //debug($params);
    //         //details for PGI
    //         $email = $params ['billing_email'];
    //         $phone = $params ['passenger_contact'];
    //         //$verification_amount = round(($amount+$convenience_fees-$promocode_discount),2);
    //         $verification_amount = round(($amount+$convenience_fees),2);
    //         //debug($verification_amount);exit;
    //         $firstname = $params['first_name']." ".$params['last_name'];
    //         $productinfo = META_TRANSFER_COURSE;
    //         //$amount = $params['transfers_total_amount'];
    //         //$currency_code = $params['currency_code'];
                

    //         //check current balance before proceeding further
    //         $domain_balance_status = $this->domain_management_model->verify_current_balance($verification_amount, $currency_code);
    //         //  debug($verification_amount);exit;
    //         $promocode_discount = 0;
    //         if ($domain_balance_status == true) {
    //             switch($params['payment_method']) {
    //                 case PAY_NOW :
                        
                    
    //                     $this->load->model('transaction');
    //                     $this->transaction->create_payment_record($book_id, $verification_amount.'00', $firstname, $email, $phone, $productinfo, $convenience_fees, $promocode_discount,$currency_code);

    //                     redirect('transfer/secure_booking/'.$book_id.'/'.$book_origin);
    //                     exit;
                            
    //                     $page_params ['form_url'] = base_url().'index.php/payment_gateway/pay/'.base64_encode($book_id).'/'.$book_origin;
    //                     $page_params ['form_method'] = 'POST';
    //                     $page_params ['form_params'] ['app_reference'] = $book_id;
                            
    //                     echo $this->template->isolated_view ( 'share/dynamic_js_form_submission', $page_params );
                            
    //                     exit;
    //                     #redirect('transfer/secure_booking/'.$book_id.'/'.$book_origin);
    //                     break;
    //                 case PAY_AT_BANK : echo 'Under Construction - Remote IO Error';exit;
    //                 break;
    //             }
    //             /*load_transfer_lib($params['booking_source']);
    //                 if ($params['booking_source'] == HB_TRANSFERS_BOOKING_SOURCE && isset($params['purchase_token']) == true) {
    //                 $params['purchase_token']   = urldecode($params['purchase_token']);
    //                 $raw_booking_details = $this->transfer_lib->process_booking($safe_search_data['data'], $params);

    //                 if(isset($raw_booking_details['status']) && $raw_booking_details['status'] == true) {
    //                 //For XML to Array convertion
                        
    //                 if(isset($transfer_book_response) && !empty($transfer_book_response)) {
    //                 $raw_transfer_book = $this->transfer_lib->formate_booking_response_data($transfer_book_response,$params);
    //                 }
    //                 }
    //                 }*/
    //         }else {
    //             redirect(base_url().'index.php/transfer/exception?op=Amount Transfer Booking&notification=insufficient_balance');
    //         }
    //     }else {
    //         redirect(base_url().'index.php/transfer/exception?op=Amount Transfer Booking&notification=insufficient_balance');
    //     }
    // }
    function pre_booking($id) 
    {
         //error_reporting(E_ALL);
        $post_params = $this->input->post ();
        // debug('Booking is blocked for testing purpose!!');die;
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
        
        
        if (valid_array ( $post_params ) == false) {
            redirect ( base_url () );
        }
        //Setting Static Data - Jaganath
        $post_params['search_id'] = $id;
        $post_params['billing_city'] = 'Bangalore';
        $post_params['billing_zipcode'] = '560100';
        $post_params['billing_address_1'] = '2nd Floor, Venkatadri IT Park, HP Avenue,, Konnappana Agrahara, Electronic city';
        // Make sure token and temp token matches
        
           $currency_obj = new Currency ( array (
                    'module_type' => 'transfers',
                    'from' => get_application_currency_preference (),
                    'to' => get_application_default_currency () 
            ));
           //debug($currency_obj);exit;
           if($post_params ['payment_method'] =='')
            {
              $post_params ['payment_method']=OFFLINE_PAYMENT;
            }
            $promo=0;
            $post_params['token'] = serialized_data($post_params);
            $post_params['promo_code'] = $promo;
            $temp_booking = $this->module_model->serialize_temp_booking_record ($post_params, TRANSFER_BOOKING);
            // debug($temp_booking);exit;  

            // debug($post_params);die;
            // debug($post_params ['booking_source']);exit;
            $book_id = $temp_booking ['book_id'];
                $book_origin = $temp_booking ['temp_booking_origin'];
            if ($post_params ['booking_source'] == PROVAB_TRANSFER_SOURCE_CRS) {
                $currency_obj = new Currency ( array (
                        'module_type' => 'transfers',
                        'from' => admin_base_currency (),
                        'to' => admin_base_currency () 
                ) );
                //debug($currency_obj);exit;
                 // debug($post_params['total_amount_val']);die;
                $amount = $post_params['total_amount_val'];
                // debug($amount);exit;
                $amount = floatval(preg_replace('/[^\d.]/', '', $amount));
                
                $currency = 'NPR';
                //debug($amount."====".$currency);exit;
            }


            // debug($amount);exit;
            $promocode_discount1=$post_params['prom_value'];
            // $email= $post_params ['email'];
            $email= $post_params ['billing_email'];
            // $phone = $post_params ['phone'];
                  
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

            $phone = $post_params ['passenger_contact'];
            $verification_amount = ($amount);
            $firstname = $post_params ['first_name'] [0]. " " . $post_params ['last_name'][0];
            $book_id = $book_id;
            // $productinfo = META_TRANSFER_COURSE;
            $productinfo = PROVAB_TRANSFER_SOURCE_CRS;
            // debug($temp_booking);die;
            // debug($verification_amount);exit;
            // check current balance before proceeding further
            // debug($verification_amount);
            // exit;
		$currency="NPR";
            $domain_balance_status = $this->domain_management_model->verify_current_balance ( $verification_amount, $currency );
           //  debug($domain_balance_status);die;
                if ($domain_balance_status == true) {
                // echo $book_id;die;
                $booking_data = $this->module_model->unserialize_temp_booking_record ( $book_id, $book_origin );
                 // debug($booking_data);die("$booking_data");
                $book_params = $booking_data['book_attributes'];
                $pack_id=@$post_params['pack_id'];
                $pax_count=@($post_params['no_adults']+ $post_params['no_child'] + $post_params['no_infant']);
                
                
                $convenience_fees=0;
                $promocode_discount=0;
            
                // debug($post_params); exit;
                switch ($post_params ['payment_method']) {
                    // case PAY_NOW :
                    //     $this->load->model('transaction');
                    //     $pg_currency_conversion_rate = $currency_obj->payment_gateway_currency_conversion_rate();
                    //     $this->transaction->create_payment_record($book_id, $amount, $firstname, $email, $phone, $productinfo, $convenience_fees, $promocode_discount1, $pg_currency_conversion_rate);
                    //     // debug($book_origin); 
                    //     // debug($book_id); exit;
                    //      // redirect(base_url().'index.php/payment_gateway/payment/'.$book_id.'/'.$book_origin);
                    //     redirect(base_url().'index.php/transfer/process_booking/'.$book_id.'/'.$book_origin);

                    //     break;
                        case ONLINE_PAYMENT :
                        $this->load->model('transaction');
                        $pg_currency_conversion_rate=1;
                        $this->transaction->create_payment_record($book_id, $amount, $firstname, $email, $phone, $productinfo, $convenience_fees, $promocode_discount1, $pg_currency_conversion_rate,$reward_point,$reward_amount,$reward_earned);
                        // debug($book_origin); 
                        // debug($book_id); exit;
                        redirect(base_url().'index.php/payment_gateway/payment/'.$book_id.'/'.$book_origin); 
                        break;
                        case OFFLINE_PAYMENT :
                        $this->load->model('transaction');
                        $pg_currency_conversion_rate = $currency_obj->payment_gateway_currency_conversion_rate();
                        $this->transaction->create_payment_record($book_id, $amount, $firstname, $email, $phone, $productinfo, $convenience_fees, $promocode_discount1, $pg_currency_conversion_rate,$reward_point,$reward_amount,$reward_earned);
                        // debug($book_origin); 
                        // debug($book_id); exit;
                         // redirect(base_url().'index.php/payment_gateway/payment/'.$book_id.'/'.$book_origin);
                        redirect(base_url().'index.php/transfer/process_booking/'.$book_id.'/'.$book_origin);                     
                        break;
                    case PAY_AT_BANK :
                        echo 'Under Construction - Remote IO Error';
                        exit ();
                        break;
                } 
            } else {
                echo "ghh-446";die;
                redirect ( base_url () . 'index.php/transfer/exception?op=Amount Transfer Booking&notification=insufficient_balance' );
            }
        }

    public function secure_booking($book_id,$temp_book_origin) {
		
        //exit('try later sorry');
        //run booking request and do booking
    
        
        $temp_booking = $this->module_model->unserialize_temp_booking_record($book_id,$temp_book_origin);
        // debug($temp_booking);exit;
    // 
        $search_id = $temp_booking['book_attributes']['search_id'];
        $safe_search_data = $this->transfer_model->get_safe_search_data($search_id);
        // debug($safe_search_data);exit;
        $from_transfer_type = @$safe_search_data['data']['from_transfer_type'];
        $to_transfer_type = @$safe_search_data['data']['to_transfer_type'];
		//debug($temp_booking['booking_source']);die;
		$temp_booking['booking_source']=PROVAB_TRANSFERV1_BOOKING_SOURCE;
        load_transfer_lib($temp_booking['booking_source']);
        
        $total_booking_price =$temp_booking['book_attributes']['token']['total_amount_val'];
        $currency = $temp_booking['book_attributes']['currency'];
        //also verify provab balance
        //check current balance before proceeding further
		$currency="NPR";
        $domain_balance_status = $this->domain_management_model->verify_current_balance($total_booking_price, $currency);
         // debug($domain_balance_status);exit;
        $total_amount_val = 0;
        
        if(isset($temp_booking['book_attributes'])){
            $book_attributes = $temp_booking['book_attributes'];
        //debug($book_attributes);exit;
            $service_tax = $book_attributes['token']['service_tax'];
            if(isset($book_attributes['total_amount']) && valid_array($book_attributes['total_amount'])){
                foreach($book_attributes['total_amount'] as $total_amount_k => $total_amount_v){
                    $total_amount_val = $total_amount_val + $total_amount_v;
                }
            }
                
            
            $data['fare'] = $total_amount_val+$service_tax;
            $data['admin_markup'] = $book_attributes['token']['admin_markup'];
            $data['agent_markup'] =$book_attributes['token']['agent_markup'];
                        $data['AgentNetRate'] =$book_attributes['token']['AgentNetRate'];
                        $data['AgentServiceTax'] =$book_attributes['token']['AgentServiceTax'];
                        $data['XMLPrice'] =$book_attributes['token']['XMLPrice'];
                        $data['AgentNetPrice'] =$book_attributes['token']['AgentNetRate'];
            $data['convinence'] = 0;
            $data['discount'] = 0;
        }
          $currency=admin_base_currency();
        $currency_obj = new Currency(array('module_type' => 'car', 'from' => get_application_default_currency(), 'to' => get_application_default_currency()));
        if ($domain_balance_status) {
            //lock table
            load_transfer_lib($temp_booking['booking_source']);
            if ($temp_booking != false) {
                switch ($temp_booking['booking_source']) {
                    case HOTELBED_TRANSFER_BOOKING_SOURCE :
                        //FIXME : COntinue from here - Booking request
                        $temp_booking['book_attributes']['horiizon_reference'] = $book_id;
                        $temp_booking['book_attributes']['from_transfer_type'] = $from_transfer_type;
                        $temp_booking['book_attributes']['to_transfer_type']   = $to_transfer_type;
                        $book_id_module = $book_id."**"."b2c";
                        $booking = $this->transfer_lib->process_booking($book_id_module, $temp_booking['book_attributes'], $module='b2c');
                        $booking = $this->transfer_lib->formate_booking_response_data($booking,$temp_booking);
                        if ($booking['status'] == SUCCESS_STATUS) {
                        // debug('1');exit;

                        
                        $promo_currency_obj = new Currency(array('module_type' => 'transferv1', 'from' => get_application_currency_preference(), 'to' => admin_base_currency()));
                        $booking['data']['currency_obj'] = $currency_obj;
                        $booking['data']['promo_currency_obj']=$promo_currency_obj;
                        $data = $this->transfer_lib->save_booking($book_id, $booking['data']);

                        //Save booking based on booking status and book id
                        $this->domain_management_model->update_transaction_details('transfer', $book_id, $data['fare'], $data['admin_markup'], $data['agent_markup'], $data['convinence'], $data['discount'] );

                     //   $this->accounting_api->process_service($book_id, $data,'TransactionCRUD',HOTELBED_TRANSFER_BOOKING_SOURCE);
                          	if($temp_booking['book_attributes']['redeem_points_post'] == 1)
						{
							$this->rewards->update_after_booking($temp_booking,$book_id);
							$this->rewards->update_transfers_rewards_details($temp_booking,$book_id);
						}
						else
						{
							$this->rewards->update_reward_earned_value($temp_booking,$book_id);
							$this->rewards->update_earned_rewards_details($temp_booking,$book_id,"transfer_booking_details");
						}
                        
                        redirect(base_url().'index.php/voucher/transferv1/'.$book_id.'/'.$temp_booking['booking_source'].'/'.$data['booking_status'].'/show_voucher');
                        } else {
                        // echo "else1";exit;
                        $msg = @$booking['data']['ErrorList']['Error']['DetailedMessage'];
                        redirect(base_url().'index.php/transfer/exception?op=booking_exception&notification='.$msg);
                        }
                        //debug($temp_booking);
                    
                        //Save booking based on booking status and book idPROVAB_TRANSFER_SOURCE_CRS
                        break;
                    case  PROVAB_TRANSFERV1_BOOKING_SOURCE:
                        $booking_data = $this->module_model->unserialize_temp_booking_record ( $book_id, $temp_book_origin );
                        // debug($booking_data);exit;
                        // debug($booking_data);die("$booking_data");

                        $booking_data['book_attributes']['date_of_travel']=$safe_search_data['data']['from_date'];
                        $booking_data['book_attributes']['no_adults']=$safe_search_data['data']['adult'];
                        $booking_data['book_attributes']['no_child']=$safe_search_data['data']['child'];
                        $book_params = $booking_data['book_attributes'];
                        $pack_id=@$book_params['price_id']; 
                        $pax_count=@($safe_search_data['data']['adult']+ $safe_search_data['data']['child']);
                        // debug($booking_data);exit;
                        $promocode_discount_val=0;
                        $current_module='b2b';

                        $data = $this->save_booking($promocode_discount_val, $book_id, $book_params,$pack_id,$pax_count,$currency_obj, $current_module, $search_id,$book_params['price_id']);
                        $data['admin_markup']=0;
                        $data['agent_markup']=0;
                        $data['convinence']=0;
                        $data['discount']=0;
                        $data['currency_conversion_rate'] = $currency_obj->transaction_currency_conversion_rate();
                        $data['transaction_currency'] = get_application_currency_preference();
                        $data['fare']= floatval(preg_replace('/[^\d.]/', '', $data['fare']));
                        $agent_paybleamount['amount'] = $data['fare'];
                        $agent_loyalty_amt = $data['fare'];
                        // debug(1);exit;
                        $stat = $this->transfer_model->change_confirm_status($book_id);

                       // $this->accounting_api->process_service($book_id, $data,'TransactionCRUD',PROVAB_TRANSFER_SOURCE_CRS);

                        // debug($data);
                        // exit;
                        // $this->domain_management_model->save_transaction_details('transfers', $book_id, $total_booking_price, "0", "0", '0', '0', '0', $currency, $data['currency_conversion_rate'], $data['transaction_currency'] );
                        //debug($this->db->last_query());exit;
                        $this->domain_management_model->update_transaction_details('transfers', $book_id, $data['fare'], $data['admin_markup'], $data['agent_markup'], $data['convinence'], $data['discount'],$data['transaction_currency'], $data['currency_conversion_rate'], 'crs_booking' );

                        if($stat == SUCCESS_STATUS)
                        {
                              // debug($GLOBALS['CI']->loyalty_program_on_off);exit;
                        
                            $app_reference   = $book_id;
                            $booking_source  = $temp_booking['booking_source'];
                            $booking_status  = SUCCESS_STATUS;
                         //debug($temp_booking['book_attributes']);die;
                            if($temp_booking['book_attributes']['redeem_points_post'] == 1)
						{
							$this->rewards->update_after_booking($temp_booking,$book_id);
							$this->rewards->update_transfers_rewards_details($temp_booking,$book_id);
						}
						else
						{
							$this->rewards->update_reward_earned_value($temp_booking,$book_id);
							$this->rewards->update_earned_rewards_details($temp_booking,$book_id,"transfer_booking_details");
						}
                            redirect ( base_url () . 'index.php/voucher/transfer_crs/' . $book_id . '/' . $temp_booking ['booking_source'] . '/' . $data ['status'] . '/show_voucher');
                        }
                        else{
                        //redirect(base_url().'index.php/activities/exception?op=Payment Not Done&notification=validation');
                        }
                        break;
                        
                        
                }
            }

            //release table lock
        }else {
            echo 'else2';exit;
            redirect(base_url().'index.php/transfer/exception?op=Remote IO error @ Insufficient&notification=validation');
        }
    }

//         function secure_booking() 
//     {
//         // error_reporting(E_ALL);
//         $post_data = $this->input->post();
//         // debug($post_data);exit;

//         if(valid_array($post_data) == true && isset($post_data['book_id']) == true && isset($post_data['temp_book_origin']) == true &&
//             empty($post_data['book_id']) == false && intval($post_data['temp_book_origin']) > 0){
            
//             //verify payment status and continue
//             $book_id = trim($post_data['book_id']);
//             $temp_book_origin = intval($post_data['temp_book_origin']);
//             $this->load->model('Transaction_Model');
            
//             $booking_status = $this->Transaction_Model->get_payment_status($book_id);
//             // debug($booking_status);exit;
//             //Comment here for direct booking Jagannath B
//             // if($booking_status['status'] !== 'accepted'){
//             //  redirect(base_url().'index.php/activities/exception?op=Payment Not Done&notification=validation');
//             // }
//         } else{
//             redirect(base_url().'index.php/activities/exception?op=InvalidBooking&notification=invalid');
//         }

//          $currency_obj = new Currency ( array (
//                     'module_type' => 'transfers',
//                     'from' => get_application_currency_preference (),
//                     'to' => get_application_default_currency () 
//             ));

//         //run booking request and do booking
//         $temp_booking = $this->module_model->unserialize_temp_booking_record ( $book_id, $temp_book_origin );
//         debug($temp_booking);exit;
//         $verification_amount=$temp_booking['book_attributes']['total'];
//           $currency=admin_base_currency();
//           //debug($verification_amount."=======".$currency);exit;
//         $domain_balance_status = $this->domain_management_model->verify_current_balance ( $verification_amount, $currency );
        
//         //Delete the temp_booking record, after accessing
        
//         // $this->module_model->delete_temp_booking_record ($book_id, $temp_book_origin);
//         // echo "hello";exit;
//         // debug($book_id);exit;

//         $booking_data = $this->module_model->unserialize_temp_booking_record ( $book_id, $temp_book_origin );
//          // debug($booking_data);exit;
//                  // debug($booking_data);die("$booking_data");
//                 $book_params = $booking_data['book_attributes'];
//               $pack_id=@$book_params['pack_id']; 
//                 $pax_count=@($temp_booking['book_attributes']['no_adults']+ $temp_booking['book_attributes']['no_child'] + $temp_booking['book_attributes']['no_infant']);

//             $promocode_discount_val=0;
//                 //debug($this->current_module);exit;
//             // $current_module=$this->current_module;
//             $current_module='b2b';
// debug($pax_count);die("chk");
//     $data = $this->save_booking($promocode_discount_val, $book_id, $book_params,$pack_id,$pax_count,$currency_obj, $current_module);
//                 debug($data);die("chk");

//                     $data['admin_markup']=0;
//                     $data['agent_markup']=0;
//                     $data['convinence']=0;
//                     $data['discount']=0;
//                     $data['currency_conversion_rate'] = $currency_obj->transaction_currency_conversion_rate();
//                     $data['transaction_currency'] = get_application_currency_preference();
//                     $data['fare']= floatval(preg_replace('/[^\d.]/', '', $data['fare']));
//                     $agent_paybleamount['amount'] = $data['fare'];

//                     $stat = $this->Activity_Model->change_confirm_status($book_id);
//                     //debug($verification_amount);exit;
//                     $this->domain_management_model->save_transaction_details('transfers', $book_id, $verification_amount, "0", "0", '0', '0', '0', $currency, $data['currency_conversion_rate'], $data['transaction_currency'] );
//                     //debug($this->db->last_query());exit;
//                 $this->domain_management_model->update_transaction_details('transfers', $book_id, $data['fare'], $data['admin_markup'], $data['agent_markup'], $data['convinence'], $data['discount'],$data['transaction_currency'], $data['currency_conversion_rate'], 'crs_booking' );
 
//         if($stat == SUCCESS_STATUS){
//             $app_reference   = $book_id;
//             $booking_source  = $temp_booking['booking_source'];
//             $booking_status  = SUCCESS_STATUS;
//             // debug($booking_status);exit;
//             // $this->load->model('user_model');
//             // $status = $this->user_model->sms_checkpoint('booking');

//             // if ($status) {
                            
//             //  $sms_template = $this->provab_sms->booking_sms_tempalte ( META_PACKAGE_COURSE, $book_id, $temp_booking ['booking_source'] );
//             //  //debug($sms_template);die;
//             //  if ($sms_template ['status'] == true) {
//             //      foreach ( $sms_template ['sms_tempalte'] as $sms_k => $sms_v ) {
                        
//             //          $sms_status = $this->provab_sms->send_msg ( $sms_template ['phone_number'], $sms_v );
//             //           //debug($sms_status);exit;
//             //      }
//             //  }
//             // }
//             //echo "in";
//             // $this->sendmail($app_reference,$booking_source,$booking_status);
//             // echo "show_voucher";exit;
//             //redirect ( base_url () . 'index.php/voucher/activity/' . $book_id . '/' . $temp_booking ['booking_source'] . '/' . $data ['status'] . '/show_voucher' );
//             redirect ( base_url () . 'index.php/voucher/activity_crs/' . $book_id . '/' . $temp_booking ['booking_source'] . '/' . $data ['status'] . '/show_voucher' );
//         }
//         else{
//             //redirect(base_url().'index.php/activities/exception?op=Payment Not Done&notification=validation');
//         }

//     }
    public function details($price_id, $enquiry_staus='') 
    { 
         //error_reporting(E_ALL);
        
        $safe_search_data = $this->transfer_model->get_safe_search_data($enquiry_staus,META_TRANSFER_COURSE);
        $search_currency=$safe_search_data['data']['nationality'];
        // debug($safe_search_data);exit;
        $data['enquire_status'] = $enquiry_staus;
        $search_id=$this->uri->segment(4);

        // $data ['package_cancel_policy'] = $this->Package_Model->getPackageCancelPolicy ( $package_id );
        $currency_obj = new Currency(array('module_type' => 'transfers','from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
        $data['currency_obj'] = $currency_obj;

        // debug($data['currency_obj']);exit();
// error_reporting(E_ALL);

        $search_result=array();
        if (! empty ( $safe_search_data )) 
        {   
            $markup_level = 'level_3';
            $admin_markup = $GLOBALS['CI']->transfer_model->get_admin_agent_markup($markup_level);
            $gst_percentage = $GLOBALS['CI']->transfer_model->get_admin_gst();
            $convenience_fees = $GLOBALS['CI']->transfer_model->get_convenience_fees();
            // debug($convenience_fees);exit;
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
                    $time_start = $this->hoursToMinutes($safe_search_data['data']['depature_time_flight']);
                    $time_in_12_hour_format  = date("g:i A", strtotime($safe_search_data['data']['depature_time_flight']));
                    $safe_search_data['data']['deptur_time'] = $time_start;
                    $nationality_code = $GLOBALS['CI']->transfer_model->get_country_id($safe_search_data['data']['native_country'])[0]->country_code;
                    $safe_search_data['data']['nationality_code'] = $nationality_code;
                    $packages = $GLOBALS['CI']->transfer_model->transfersearch($safe_search_data,$weekday,$price_id);
            foreach ($packages as $key => $value) 
            {  
                $markup_level_agent = 'level_4';
                $user_agent = $GLOBALS ['CI']->entity_user_id;
                $agent_markup = $GLOBALS['CI']->transfer_model->get_admin_agent_markup($markup_level_agent,$user_agent);
                // $package_price = $GLOBALS['CI']->sightseeing_model->get_package_price_by_app_currency($value->package_id);
                
                // if($package_price >0)
                
                // {
                $key=$key.'crs';
                $search_result['data']['SSSearchResult']['TransferResults'][$key]['id']=$value->id;
                $search_result['data']['SSSearchResult']['TransferResults'][$key]['price_id']=$value->price_id;
                $search_result['data']['SSSearchResult']['TransferResults'][$key]['time_in_12_hour_format']=$time_in_12_hour_format;
                $search_result['data']['SSSearchResult']['TransferResults'][$key]['transfer_name']=$value->transfer_name;
                $search_result['data']['SSSearchResult']['TransferResults'][$key]['package_types_name']=$value->package_types_name;
                $search_result['data']['SSSearchResult']['TransferResults'][$key]['ImageUrl']=$GLOBALS['CI']->template->domain_upload_pckg_images($value->image);
                $search_result['data']['SSSearchResult']['TransferResults'][$key]['ImageVehicleUrl']=$GLOBALS['CI']->template->domain_upload_pckg_images($value->vehicle_image);
                $search_result['data']['SSSearchResult']['TransferResults'][$key]['source']=$value->source;
                $search_result['data']['SSSearchResult']['TransferResults'][$key]['Distance']=$value->distance;
                $search_result['data']['SSSearchResult']['TransferResults'][$key]['from_date']=$safe_search_data['data']['from_date'];
                $search_result['data']['SSSearchResult']['TransferResults'][$key]['destination']=$value->destination;
                $search_result['data']['SSSearchResult']['TransferResults'][$key]['description']=$value->description;
                $search_result['data']['SSSearchResult']['TransferResults'][$key]['vehicle_id']=$value->vehicle_id;
                $search_result['data']['SSSearchResult']['TransferResults'][$key]['StarRating']=$value->rating;
                $search_result['data']['SSSearchResult']['TransferResults'][$key]['exclusive_ride']=$value->exclusive_ride;
                $search_result['data']['SSSearchResult']['TransferResults'][$key]['meetup_location']=$value->meetup_location;
                $search_result['data']['SSSearchResult']['TransferResults'][$key]['general_list_info']=$value->general_list_info;
                $search_result['data']['SSSearchResult']['TransferResults'][$key]['pick_up_info']=$value->pick_up_info;
                $search_result['data']['SSSearchResult']['TransferResults'][$key]['guidelines_list']=$value->guidelines_list;
                $search_result['data']['SSSearchResult']['TransferResults'][$key]['contact_address']=$value->contact_address;
                $search_result['data']['SSSearchResult']['TransferResults'][$key]['contact_email']=$value->contact_email;
                $search_result['data']['SSSearchResult']['TransferResults'][$key]['price_excludes']=$value->price_excludes;
                $search_result['data']['SSSearchResult']['TransferResults'][$key]['price_includes']=$value->price_includes;
                $search_result['data']['SSSearchResult']['TransferResults'][$key]['driver_id']=$value->driver_id;
                $search_result['data']['SSSearchResult']['TransferResults'][$key]['vehicle_name']=$value->vehicle_name;
                $search_result['data']['SSSearchResult']['TransferResults'][$key]['max_passenger']=$value->max_passenger;
                $search_result['data']['SSSearchResult']['TransferResults'][$key]['display_price']=$value->display_price;
                $agent_base_currency = 'NPR';
                if($agent_base_currency==$value->currency){
                    $transfer_price = $value->price;
                }else{
                    	$currency_obj_m = new Currency(array('module_type' => 'hotel', 'from' => get_application_currency_preference(), 'to' => get_application_currency_preference()));
					
               
                             $strCurrency_m  = $currency_obj_m->get_currency($value->price, true, false, true, false, 1);
                $transfer_price = $strCurrency_m['default_value'];
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
                $total_pax = $safe_search_data['data']['adult']+$safe_search_data['data']['child'];
                // $gst_amount = 0;
                $total_markup = $admin_markup_price+$admin_markup_price;
                $gst_amount = ($gst_percentage[0]['gst']*$total_markup)/100;
                $price_with_agent_markup = $transfer_price+$admin_markup_price+$agent_markup_price;
                $net_fare = $price_with_agent_markup+$gst_amount;
                // debug($net_fare);exit;
                $from_date = trim ( date('Y-m-d',strtotime($safe_search_data['data']['from_date'])) );
                $now = time(); // or your date as well
                $your_date = strtotime($from_date);
                $datediff = $your_date - $now;
                $number_of_days1 = round($datediff / (60 * 60 * 24));
                $number_of_days = $number_of_days1+1;
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
                                if($agent_base_currency=='NPR'){
                                    }else{
                                      $curncy = 'NPR';
                                    $currency_obj = new Currency(array('module_type' => 'country_wise_price','from' => $curncy, 'to' => $agent_base_currency)); 
                                    $converted_currency_rate = $currency_obj->getConversionRate(false,$curncy,$agent_base_currency);
                                    // debug($payed_amount);exit;
                                    $cancellation_amount=$cancellation_amount*$converted_currency_rate;
                                    }
                                $cancellation_details = 'Cancellation from today will charge '.$agent_base_currency.round(($cancellation_amount),2);
                            }
                            else if($cancellation_policy[$i]['charge_type']=='Amount')
                            {
                                $cancellation_amount = $cancellation_policy[$i]['amount'];
                                if($agent_base_currency=='NPR'){
                                    }else{
                                      $curncy = 'NPR';
                                    $currency_obj = new Currency(array('module_type' => 'country_wise_price','from' => $curncy, 'to' => $agent_base_currency)); 
                                    $converted_currency_rate = $currency_obj->getConversionRate(false,$curncy,$agent_base_currency);
                                    // debug($payed_amount);exit;
                                    $cancellation_amount=$cancellation_amount*$converted_currency_rate;
                                    }
                                $cancellation_details = 'Cancellation from today will charge '.$agent_base_currency.round(($cancellation_amount),2);
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
                                    if($agent_base_currency=='NPR'){
                                    }else{
                                      $curncy = 'NPR';
                                    $currency_obj = new Currency(array('module_type' => 'country_wise_price','from' => $curncy, 'to' => $agent_base_currency)); 
                                    $converted_currency_rate = $currency_obj->getConversionRate(false,$curncy,$agent_base_currency);
                                    // debug($payed_amount);exit;
                                    $cancellation_amount=$cancellation_amount*$converted_currency_rate;
                                    }
                                    $cancellation_details = 'Cancellation from '.$startdate.' will charge '.$agent_base_currency.round(($cancellation_amount),2);
                                }
                                else if($cancellation_policy[$i]['charge_type']=='Amount')
                                {
                                    $cancellation_amount = $cancellation_policy[$i]['amount'];
                                    if($agent_base_currency=='NPR'){
                                    }else{
                                      $curncy = 'NPR';
                                   $currency_obj = new Currency(array('module_type' => 'hotel', 'from' => get_application_currency_preference(), 'to' => get_application_currency_preference()));
                                    $converted_currency_rate = $currency_obj->getConversionRate(false,$curncy,$agent_base_currency);
                                    // debug($payed_amount);exit;
                                    $cancellation_amount=$cancellation_amount*$converted_currency_rate;
                                    }
                                    $cancellation_details = 'Cancellation from '.$startdate.' will charge '.$agent_base_currency.round(($cancellation_amount),2);
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
                $search_result['data']['SSSearchResult']['TransferResults'][$key]['cancellation_details']=$cancellation_details;
                $search_result['data']['SSSearchResult']['TransferResults'][$key]['Cancellation_available']=$cancellation_available;
                $search_result['data']['SSSearchResult']['TransferResults'][$key]['Price']=array(
                                            'TotalDisplayFare'=>$transfer_price,
                                            'GSTPrice'=>$gst_amount,
                                            'Tax'=>0,
                                            'Currency'=>admin_base_currency()
                );
                
                $search_result['data']['SSSearchResult']['TransferResults'][$key]['ResultToken']=base64_encode(json_encode($value));
                $search_result['data']['SSSearchResult']['TransferResults'][$key]['booking_source']=PROVAB_TRANSFER_SOURCE_CRS;

                // }
            }
                    
                    $search_result['Status']=1;
                    $search_result['Message']='';


        //    debug($search_result['data']['SSSearchResult']['TransferResults']);exit();
              load_transfer_lib(PROVAB_TRANSFER_SOURCE_CRS);

               //Converting API currency data to preferred currency
              //debug(get_api_data_currency());exit();
           // $currency_obj = new Currency(array('module_type' => 'sightseeing', 'from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
            $currency_obj = new Currency(array('module_type' => 'hotel', 'from' => get_application_currency_preference(), 'to' => get_application_currency_preference()));

                
            
            $raw_sightseeing_result = $this->transfer_lib->search_data_in_preferred_currency($search_result, $currency_obj,'b2b');
           // debug($raw_sightseeing_result);exit;

           $currency_obj = new Currency(array('module_type' => 'hotel', 'from' => get_application_currency_preference(), 'to' => get_application_currency_preference()));

// debug($currency_obj);exit();
            $filters = array();                       


            // debug($raw_sightseeing_result['data']);
            // debug( $currency_obj);
            // debug($search_id);
            // debug($filters); 
           $formated_data = $this->transfer_lib->format_search_response($raw_sightseeing_result['data'], $currency_obj, $search_id, 'b2b', $filters);


           // debug($formated_data);exit();
 

            $data['formated_data']=$formated_data['SSSearchResult']['TransferResults'];
            $data['formated_data']['data']= $safe_search_data['data'];
            $this->template->view ( 'transfer/transfer_detail', $data );
        } else {
            redirect ( "transfer/index" );
        }
    }
function booking() {//error_reporting(E_ALL);
 if($this->input->post()){
    $pre_booking_params = $this->input->post();

         // debug($pre_booking_params);
         // exit;

    $currency_obj = new Currency ( array (
        'module_type' => 'transfers',
        'from' => get_application_currency_preference (),
        'to' => get_application_default_currency () 
    ));
    
    $search_id = $pre_booking_params['search_id'];
    $safe_search_data = $this->transfer_model->get_safe_search_data($search_id ,META_TRANSFER_COURSE);

    $safe_search_data['data']['search_id'] = abs($search_id);
    $page_data['active_payment_options'] = $this->module_model->get_active_payment_module_list();

    $page_data['search_data'] = $safe_search_data['data'];

    $page_data['pax_details'] = "";
    $page_data['user_country_code'] = "";
    $page_data['booking_source'] = $pre_booking_params['booking_source'];
    $page_data['pre_booking_params'] = array();
    $page_data['iso_country_list'] = $this->db_cache_api->get_iso_country_list();
    $page_data['country_list'] = $this->db_cache_api->get_country_list();
    $page_data['active_data'] = array();
    $page_data['phone_code'] = array();
    $page_data['search_id'] = $search_id;
    $markup_level = 'level_3';
    $admin_markup = $GLOBALS['CI']->transfer_model->get_admin_agent_markup($markup_level);
    $markup_level_agent = 'level_4';
    $user_agent = $GLOBALS ['CI']->entity_user_id;
    $agent_markup = $GLOBALS['CI']->transfer_model->get_admin_agent_markup($markup_level_agent,$user_agent);
    $gst_percentage = $GLOBALS['CI']->transfer_model->get_admin_gst();
    $convenience_fees = $GLOBALS['CI']->transfer_model->get_convenience_fees();
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
    $time_start = $this->hoursToMinutes($safe_search_data['data']['depature_time_flight']);
    $time_in_12_hour_format  = date("g:i A", strtotime($safe_search_data['data']['depature_time_flight']));
    $safe_search_data['data']['deptur_time'] = $time_start;
    $nationality_code = $GLOBALS['CI']->transfer_model->get_country_id($safe_search_data['data']['native_country'])[0]->country_code;
    $safe_search_data['data']['nationality_code'] = $nationality_code;
    $page_data['product_details'] = $GLOBALS['CI']->transfer_model->transfersearch($safe_search_data,$weekday,$pre_booking_params['price_id']);
    // $page_data['product_details'] = $pre_booking_params['product_details'];

    $product_details = $page_data['product_details'];

     // debug($product_details);
     //     exit;


    //Get the country phone code 
    foreach ($product_details as $key => $value) {
        # code...
    
    $Domain_record = $this->custom_db->single_table_records('domain_list', '*');
    $page_data['active_data'] =$Domain_record['data'][0];
    $temp_record = $this->custom_db->single_table_records('api_country_list', '*');
    $page_data['phone_code'] =$temp_record['data'];

    $page_data['pre_booking_params']['default_currency'] = get_application_currency_preference();
    $page_data['currency_obj']      = $currency_obj;

    $page_data['pre_booking_params']['total_pax'] = $pre_booking_params['total_pax'];
    $page_data['pre_booking_params']['transfer_date'] = $pre_booking_params['from_date'];
    $page_data['pre_booking_params']['from_date'] = $pre_booking_params['from_date'];
    $page_data['pre_booking_params']['to_date'] = $pre_booking_params['to_date'];
    $page_data['pre_booking_params']['adult'] = $pre_booking_params['no_of_adult'];
    $page_data['pre_booking_params']['child'] = $pre_booking_params['no_of_child'];
     $page_data['pre_booking_params']['vat'] = $value->tax;;
    $page_data['pre_booking_params']['pickup_location'] = $pre_booking_params['pickup_location'];
    $page_data['pre_booking_params']['time_in_12_hour_format'] = $time_in_12_hour_format;

    // debug($pre_booking_params['pickup_location']);exit();
                $agent_base_currency = 'NPR';
                if($agent_base_currency==$value->currency){
                    $transfer_price = $value->price;
                }else{
                $currency_obj_m = new Currency(array('module_type' => 'hotel', 'from' => get_application_currency_preference(), 'to' => get_application_currency_preference())); 
                             $strCurrency_m  = $currency_obj_m->get_currency($value->price, true, false, true, false, 1);
                $transfer_price = $strCurrency_m['default_value'];
                }
                $supplier = 'Voyage CRS';
                $product_id = '';
                if(!empty($value->id)){
                        $product_id = $value->id;
                }
                // debug($transfer_price);exit;
                $search_level_markup_val = $safe_search_data['data']['markup_value'];
                if($search_level_markup_val!='' || $search_level_markup_val!=0){
                $search_level_markup_typ = $safe_search_data['data']['markup_type'];
                if($search_level_markup_typ=='plus'){
                    $search_level_markup = $search_level_markup_val;
                }else{
                    $search_level_markup = ($transfer_price/100) * $search_level_markup_val;
                }
                }else{
                    $search_level_markup = 0;
                }
                // debug($search_level_markup);exit;
                $country_city_data = $this->transfer_model->get_country_city($safe_search_data['data']['from_code']);
                $cntry_code = $country_city_data[0]['country_code'];
                $city_id = $country_city_data[0]['city_id'];
                $admin_markup_details = $this->transfer_model->get_markup_for_admin ( $transfer_price, $supplier, $cntry_code, $city_id, $product_id);
                $agent_markup_details = $this->transfer_model->get_markup_for_agent ( $transfer_price, $cntry_code, $city_id);
                $total_markup = $admin_markup_details+$agent_markup_details+$search_level_markup;
                $convenience_fees[0]['value']=0;
                if($convenience_fees[0]['value_type']=='percentage')
                {
                   $admin_convenience_fees = ($transfer_price*$convenience_fees[0]['value'])/100; 
                }
                else if($convenience_fees[0]['value_type']=='plus')
                {
                    $admin_convenience_fees = $convenience_fees[0]['value'];
                }
                else
                {
                    $admin_convenience_fees = 0;
                }
                $total_pax = $safe_search_data['data']['adult']+$safe_search_data['data']['child'];
                if($convenience_fees[0]['per_pax']==1)
                {
                  $total_convenience_fees = $admin_convenience_fees*$total_pax;
                }
                else 
                {
                  $total_convenience_fees = $admin_convenience_fees;
                }
                $gst_amount = ($gst_percentage[0]['gst']*$total_markup)/100;
                // debug($total_markup);exit;
                $agent_base_currency=  'NPR';
                if($agent_base_currency=='NPR'){
                }else{
                  $curncy = 'NPR';
                $currency_obj = new Currency(array('module_type' => 'country_wise_price','from' => get_application_currency_preference(), 'to' => get_application_currency_preference())); 
                $converted_currency_rate = $currency_obj->getConversionRate(false,$curncy,$agent_base_currency);
                $total_convenience_fees=$total_convenience_fees*$converted_currency_rate;
                $gst_amount = $gst_amount*$converted_currency_rate;
                }
                $page_data['pre_booking_params']['convenience_fees']=$total_convenience_fees;
                // $gst_amount = 0;
                $agent_buying_price = $transfer_price+$admin_markup_details+$gst_amount+$total_convenience_fees;                
                $page_data['pre_booking_params']['agent_buying_price']=$agent_buying_price;
                $page_data['agent_balance'] = $this->transfer_model->verify_agent_balance();
                $price_with_agent_markup = $transfer_price+$admin_markup_details+$agent_markup_details+$search_level_markup;
                // debug($transfer_price);debug($price_with_agent_markup);debug($admin_markup_details);debug($agent_markup_details);exit;
                $net_fare = $price_with_agent_markup+$gst_amount+$total_convenience_fees;
                $from_date = trim ( date('Y-m-d',strtotime($safe_search_data['data']['from_date'])) );
                $now = time(); // or your date as well
                $your_date = strtotime($from_date);
                $datediff = $your_date - $now;
                $number_of_days1 = round($datediff / (60 * 60 * 24));
                $number_of_days = $number_of_days1+1;
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
                                if($agent_base_currency=='NPR'){
                                    }else{
                                      $curncy = 'NPR';
                                    $currency_obj = new Currency(array('module_type' => 'country_wise_price','from' => $curncy, 'to' => $agent_base_currency)); 
                                    $converted_currency_rate = $currency_obj->getConversionRate(false,$curncy,$agent_base_currency);
                                    // debug($payed_amount);exit;
                                    $cancellation_amount=$cancellation_amount*$converted_currency_rate;
                                    }
                                $cancellation_details = 'Cancellation from today will charge '.$agent_base_currency.round(($cancellation_amount),2);
                            }
                            else if($cancellation_policy[$i]['charge_type']=='Amount')
                            {
                                $cancellation_amount = $cancellation_policy[$i]['amount'];
                                if($agent_base_currency=='NPR'){
                                    }else{
                                      $curncy = 'NPR';
                                    $currency_obj = new Currency(array('module_type' => 'country_wise_price','from' => $curncy, 'to' => $agent_base_currency)); 
                                    $converted_currency_rate = $currency_obj->getConversionRate(false,$curncy,$agent_base_currency);
                                    // debug($payed_amount);exit;
                                    $cancellation_amount=$cancellation_amount*$converted_currency_rate;
                                    }
                                $cancellation_details = 'Cancellation from today will charge '.$agent_base_currency.round(($cancellation_amount),2);
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
                                    if($agent_base_currency=='NPR'){
                                    }else{
                                      $curncy = 'NPR';
                                    $currency_obj = new Currency(array('module_type' => 'country_wise_price','from' => $curncy, 'to' => $agent_base_currency)); 
                                    $converted_currency_rate = $currency_obj->getConversionRate(false,$curncy,$agent_base_currency);
                                    // debug($payed_amount);exit;
                                    $cancellation_amount=$cancellation_amount*$converted_currency_rate;
                                    }
                                    $cancellation_details = 'Cancellation from '.$startdate.' will charge '.$agent_base_currency.round(($cancellation_amount),2);
                                }
                                else if($cancellation_policy[$i]['charge_type']=='Amount')
                                {
                                    $cancellation_amount = $cancellation_policy[$i]['amount'];
                                    if($agent_base_currency=='NPR'){
                                    }else{
                                      $curncy = 'NPR';
                                    $currency_obj = new Currency(array('module_type' => 'country_wise_price','from' => $curncy, 'to' => $agent_base_currency)); 
                                    $converted_currency_rate = $currency_obj->getConversionRate(false,$curncy,$agent_base_currency);
                                    // debug($payed_amount);exit;
                                    $cancellation_amount=$cancellation_amount*$converted_currency_rate;
                                    }
                                    $cancellation_details = 'Cancellation from '.$startdate.' will charge '.$agent_base_currency.round(($cancellation_amount),2);
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

                $page_data['pre_booking_params']['_SearchMarkup'] = $search_level_markup;
                $page_data['pre_booking_params']['cancellation_details']=$cancellation_details;
                $page_data['pre_booking_params']['cancellation_available']=$cancellation_available;
    $page_data['pre_booking_params']['ProductName'] = "";
    $page_data['pre_booking_params']['modalities'] = $pre_booking_params['modalities'];
    $page_data['pre_booking_params']['TM_Cancellation_Policy'] = $pre_booking_params['cancel_policy'];
    
    $page_data['pre_booking_params']['markup_price_summary'] = array();
    $page_data['pre_booking_params']['markup_price_summary']['_GST'] = $gst_amount;
    $page_data['pre_booking_params']['markup_price_summary']['TotalDisplayFare'] = $price_with_agent_markup;
    $page_data['pre_booking_params']['markup_price_summary']['NetFare'] = $net_fare;
    
    $page_data['pre_booking_params']['AgeBands'] = array(
        "bandId" => 1

    );
    $page_data['pre_booking_params']['AgeBands'][0] = array("bandId"=>1,"count"=>$pre_booking_params['no_of_adult']);
    $page_data['pre_booking_params']['AgeBands'][1] = array("bandId"=>2,"count"=>$pre_booking_params['no_of_child']);
    
    $page_data['pre_booking_params']['Price'] = $price_with_agent_markup;
    $traveller_details = $this->user_model->get_current_user_details();
    // debug($traveller_details);exit;
    $page_data['pre_booking_params']['traveller_details'] = $traveller_details;
    
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
    }

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



        // debug($page_data); exit();
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
	/*#############################FOR REWARDS SYSTEM #########################################*/
				            $page_data ['total_price'] = ceil($page_data['total_price']);
						    $page_data['reward_usable'] = 0;
							$page_data['reward_earned'] = 0;
							$page_data ['total_price_with_rewards'] = 0;
							if(is_logged_in_user()){
									$user_id = $this->entity_user_id;
									//$data_rewards = $this->transaction->user_reward_details($user_id);
									$reward_values = $this->rewards->find_reward(META_TRANSFERV1_COURSE,$agent_buying_price);
									
									$reward_details = $this->rewards->get_reward_coversion_and_limit_details();	
									
									//$usable_rewards = $reward_values['usable_reward'];
									$usable_rewards = $this->rewards->usable_rewards($user_id,META_TRANSFERV1_COURSE,$reward_values['usable_reward']);
									
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
                                        if($reducing_amount > $agent_buying_price){
                                            $reducing_amount = $agent_buying_price - 1;
                                        }
										$page_data ['total_price_with_rewards'] = $agent_buying_price-$reducing_amount;
										$page_data['reducing_amount'] = $reducing_amount;
									}

							}
						/*#############################END #######################################################*/

    $this->template->view('transfer/transfer_booking_page', $page_data);
}else{
    redirect(base_url().'index.php/transfer/exception?op=Remote IO error @ Transfers Booking.&notification=(Unexpected page refresh)');
}

}
function process_booking($book_id, $temp_book_origin){

        // debug($book_id);
        // debug($temp_book_origin);exit;
        
        if($book_id != '' && $temp_book_origin != '' && intval($temp_book_origin) > 0){

            $page_data ['form_url'] = base_url () . 'index.php/transfer/secure_booking/'.$book_id.'/'.$temp_book_origin;
            $page_data ['form_method'] = 'POST';
            $page_data ['form_params'] ['book_id'] = $book_id;
            $page_data ['form_params'] ['temp_book_origin'] = $temp_book_origin;

            $this->template->view('share/loader/booking_process_loader', $page_data);   

        }else{
            redirect(base_url().'index.php/transfer/exception?op=Invalid request&notification=validation');
        }
        
    }
    function save_booking($promocode_discount_val, $app_booking_id, $book_params,$pack_id,$pax_count,$currency_obj, $module='b2c',$search_id,$price_id)
      {
      //    error_reporting(E_ALL);
        // debug($book_params);exit();          
        $book_total_fare = array();
        $book_domain_markup = array();
        $book_level_one_markup = array();
        $master_transaction_status = 'BOOKING_INPROGRESS';
        $master_search_id = $book_params['pack_id'];
        // $package_details=$this->Activity_Model->getPackage($master_search_id);
        $safe_search_data = $this->transfer_model->get_safe_search_data($search_id ,META_TRANSFER_COURSE);
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
                    
                    $user_agent = $GLOBALS ['CI']->entity_user_id;
                    $gst_percentage = $GLOBALS['CI']->transfer_model->get_admin_gst();
                    // debug($agent_markup);exit;
                    $convenience_fees = $GLOBALS['CI']->transfer_model->get_convenience_fees(); 
                    $time_start = $this->hoursToMinutes($safe_search_data['data']['depature_time_flight']);
                    $safe_search_data['data']['deptur_time'] = $time_start;

                    $nationality_code = $GLOBALS['CI']->transfer_model->get_country_id($safe_search_data['data']['native_country'])[0]->country_code;
                    $safe_search_data['data']['nationality_code'] = $nationality_code;
        $package_details = $GLOBALS['CI']->transfer_model->transfersearch($safe_search_data,$weekday,$price_id);
        foreach ($package_details as $key => $value) {
            $transfer_id = $value->id;
             $transfer_name = $value->transfer_name;
             $driver_name = $value->driver_name;
             $driver_contact_number = $value->driver_contact_number;
             $price_id = $value->price_id;
             $vehicle_image = $value->vehicle_image;
             $distance = $value->distance;
             $agent_base_currency = 'NPR';
                $currency_obj_m = new Currency(array('module_type' => 'hotel', 'from' => get_application_currency_preference(), 'to' => get_application_currency_preference())); 
                             $strCurrency_m  = $currency_obj_m->get_currency($value->price, true, false, true, false, 1);
                $transfer_price = $strCurrency_m['default_value'];
                $supplier = 'Voyage CRS';
                $product_id = '';
                if(!empty($value->id)){
                        $product_id = $value->id;
                }
                // debug($transfer_price);exit;
                $search_level_markup_val = $safe_search_data['data']['markup_value'];
                if($search_level_markup_val!='' || $search_level_markup_val!=0){
                $search_level_markup_typ = $safe_search_data['data']['markup_type'];
                if($search_level_markup_typ=='plus'){
                    $search_level_markup = $search_level_markup_val;
                }else{
                    $search_level_markup = ($transfer_price/100) * $search_level_markup_val;
                }
                }else{
                    $search_level_markup = 0;
                }
                // debug($package_details);exit;
                $country_city_data = $GLOBALS['CI']->transfer_model->get_country_city($safe_search_data['data']['from_code']);
                $cntry_code = $country_city_data[0]['country_code'];
                $city_id = $country_city_data[0]['city_id'];
                $admin_markup_details = $GLOBALS['CI']->transfer_model->get_markup_for_admin ( $transfer_price, $supplier, $cntry_code, $city_id, $product_id);
                $agent_markup_details = $GLOBALS['CI']->transfer_model->get_markup_for_agent ( $transfer_price, $cntry_code, $city_id);
                $total_markup = $admin_markup_details+$agent_markup_details+$search_level_markup;
                if($convenience_fees[0]['value_type']=='percentage')
                {
                   $admin_convenience_fees = ($transfer_price*$convenience_fees[0]['value'])/100; 
                }
                else if($convenience_fees[0]['value_type']=='plus')
                {
                    $admin_convenience_fees = $convenience_fees[0]['value'];
                }
                else
                {
                    $admin_convenience_fees = 0;
                }
                $total_pax = $safe_search_data['data']['adult']+$safe_search_data['data']['child'];
                if($convenience_fees[0]['per_pax']==1)
                {
                  $total_convenience_fees = $admin_convenience_fees*$total_pax;
                }
                else 
                {
                  $total_convenience_fees = $admin_convenience_fees;
                }
                // $gst_amount = 0;
                $gst_value = ($gst_percentage[0]['gst']*$total_markup)/100;
                $admin_net_fare_markup = $transfer_price+$admin_markup_details+$agent_markup_details;
                $total_fare = $admin_net_fare_markup+$gst_value+$search_level_markup+$total_convenience_fees;
                $from_date = trim ( date('Y-m-d',strtotime($safe_search_data['data']['from_date'])) );
                $agent_buying_price = $transfer_price+$admin_markup_details+$gst_value+$total_convenience_fees;
                $now = time(); // or your date as well
                $your_date = strtotime($from_date);
                $datediff = $your_date - $now;
                $number_of_days1 = round($datediff / (60 * 60 * 24));
                $number_of_days = $number_of_days1+1;
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
                                $cancellation_amount = ($admin_net_fare_markup*$cancellation_policy[$i]['amount'])/100;
                                $cancellation_details = 'Cancellation from today will charge NPR'.round(($cancellation_amount),2);
                            }
                            else if($cancellation_policy[$i]['charge_type']=='Amount')
                            {
                                $cancellation_amount = $cancellation_policy[$i]['amount'];
                                $cancellation_details = 'Cancellation from today will charge NPR'.round(($cancellation_amount),2);
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
                                    $cancellation_amount = ($admin_net_fare_markup*$cancellation_policy[$i]['amount'])/100;
                                    $cancellation_details = 'Cancellation from '.$startdate.' will charge NPR'.round(($cancellation_amount),2);
                                }
                                else if($cancellation_policy[$i]['charge_type']=='Amount')
                                {
                                    $cancellation_amount = $cancellation_policy[$i]['amount'];
                                    $cancellation_details = 'Cancellation from '.$startdate.' will charge NPR'.round(($cancellation_amount),2);
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
                $search_result['data']['SSSearchResult']['TransferResults'][$key]['cancellation_details']=$cancellation_details;
                $search_result['data']['SSSearchResult']['TransferResults'][$key]['Cancellation_available']=$cancellation_available;
                // debug($agent_markup_price);exit;
         } 
        // debug($distance);exit;
        $domain_origin = get_domain_auth_id();
        $app_reference = $app_booking_id;
        $booking_source = $book_params['token']['booking_source'];
        $enquiry_reference_no = $book_params['token']['enquiry_reference_no'];
        $remarks_user = $book_params['token']['remarks_user'];
        // debug($remarks_user);exit;
        // debug($enquiry_reference_no);exit;
        //PASSENGER DATA UPDATE
        $total_pax_count = $pax_count;
        $pax_count = $pax_count;

        //PREFERRED TRANSACTION CURRENCY AND CURRENCY CONVERSION RATE 
        $transaction_currency = get_application_currency_preference();
        $application_currency = admin_base_currency();
        $currency_conversion_rate = $currency_obj->transaction_currency_conversion_rate();

        $token = $book_params['token'];
        // debug($token);exit;
        $master_booking_source = array();
        $currency = $currency_obj->to_currency;
        $deduction_cur_obj  = clone $currency_obj;
        //Storing Flight Details - Every Segment can repeate also
        $token_index=1;
        $commissionable_fare =  $token['total_amount_val']-$token['promo_actual_value'];
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
            $tranaction_attributes['Fare'] = $token['total_amount_val'];
            $sequence_number = $token_index;
            //Transaction Log Details
            $ticket_trans_status_group[] = $transaction_status;
            $book_total_fare[]  = $token['total_amount_val'];
            
            //Need individual transaction price details
            //SAVE Transaction Details
            // debug(get_domain_auth_id());exit();

            $transaction_insert_id = $this->transfer_model->save_package_booking_transaction_details($promocode_discount_val, $app_reference, $transaction_status, $transaction_description, $pnr, $book_id, $source, $ref_id,
            json_encode($tranaction_attributes),$currency, $commissionable_fare);
            $transaction_insert_id = $transaction_insert_id['insert_id'];
            // debug($this->db->last_query());exit;
         
            //Saving Passenger Details
            // debug($total_pax_count);exit();
            $i = 0;
            for ($i=0; $i<$total_pax_count; $i++)
            {
                $passenger_type = 'Adult';
                $is_lead =1;
                $first_name = $book_params['first_name'][0];
                // debug($first_name);exit;
                $last_name = $book_params['last_name'][0];
                
                // debug($book_params);exit();
                // $gender = get_enum_list('gender', $book_params['gender'][$i]);
                $passenger_nationality = $book_params['country_code'];
   
                $status = $master_transaction_status;
                $passenger_attributes = array(); 
                $flight_booking_transaction_details_fk = $transaction_insert_id;//Adding Transaction Details Origin 

                // debug(1);exit();
                //SAVE Pax Details
                $pax_insert_id =  $this->transfer_model->save_package_booking_passenger_details(
                  $app_reference,$passenger_type,$is_lead,$first_name,$last_name,$gender,$passenger_nationality,$status,
                json_encode($passenger_attributes), 
                $flight_booking_transaction_details_fk, $book_params['no_adults'], $book_params['no_child']);

                //Save passenger ticket information     
                
// debug($pax_insert_id);exit();
            }//Adding Pax Details Ends
            $date_of_travel=date('Y-m-d', strtotime($book_params['date_of_travel']));
            // debug($date_of_travel);exit;

        //Save Master Booking Details
        $book_total_fare = $token['total_amount_val'];
        // debug($date_of_travel);exit;
        $phone = $book_params['passenger_contact'];
        $alternate_number = '';
        $email = $book_params['billing_email'];
        $payment_mode = $book_params['payment_method'];
        $created_by_id = intval(@$GLOBALS['CI']->entity_user_id);

        $passenger_country =$book_params['billing_country'];
        
        $passenger_city = $book_params['billing_city'];

        $attributes = array('country' => $passenger_country, 'city' => $passenger_city, 'zipcode' => $book_params['billing_zipcode'], 'address' =>  $book_params['billing_address_1'],'Cancellation_available' =>$cancellation_available,'cancellation_details'=>$cancellation_details);
        // debug($attributes);exit;
        $transfer_booking_status = $master_transaction_status;

        // debug($transfer_booking_status);exit();
        //SAVE Booking Details
        // error_reporting(E_ALL);
        // debug($transfer_booking_status);
         
        $book_total_fare = floatval(preg_replace('/[^\d.]/', '', $book_total_fare));

         // $attributes = '';
         // debug($this->db->last_query());exit;
         if($admin_net_fare_markup==''){$admin_net_fare_markup=0; }
         if($admin_markup==''){$admin_markup=0; }
         if($total_fare==''){$total_fare=0; }
         if($gst_value==''){$gst_value=0; }
         $emulate = $this->session->userdata('EMUL');
            if(!empty($emulate)){
                $emulate_booking = 1;
                $emulate_user = $emulate;
            }
            else{
                $emulate_booking = 0;
                $emulate_user = '';
            }

        $this->transfer_model->save_booking_itinerary_details ( $app_reference, $safe_search_data['data']['to'], $date_of_travel,$transfer_name,$price_id,$vehicle_image,$distance, $transfer_booking_status,$transfer_price,$admin_net_fare_markup,$admin_markup_details, $agent_markup_details, $currency, json_encode ( $attributes ), @$book_total_fare,$agent_commission,$agent_tds,$admin_commission,$admin_tds,$api_raw_fare,$agent_buying_price, $gst_value, $total_convenience_fees, $search_level_markup, $total_fare,$time_start, $driver_name, $driver_contact_number, $safe_search_data['data']['from'], $remarks_user);
        // debug($this->db->last_query());exit;
        // echo 123;exit("end");
         $this->transfer_model->save_package_booking_details($domain_origin, $transfer_booking_status,$app_reference,$booking_source,$phone, $alternate_number, $email,$payment_mode, json_encode($attributes), $created_by_id,$transaction_currency,$currency_conversion_rate,$pack_id,$date_of_travel,$book_total_fare,$transfer_id,$emulate_booking,$emulate_user);
         // debug($this->db->last_query());exit;

            // debug($transfer_booking_status);exit();
        // echo 123;exit("end");

        /************** Update Convinence Fees And Other Details Start ******************/
        
        $response['fare'] = $book_total_fare;
        $response['status'] = $transfer_booking_status;
        $response['status_description'] = $transaction_description;
        $response['name'] = $first_name;
        $response['phone'] = $phone; 

        // debug($response);exit;
        return $response;
    }
    function get_transfer_city_list() {
        $this->load->model('transfer_model');
        $term = $this->input->get('term'); //retrieve the search term that autocomplete sends
        $term = trim(strip_tags($term));
        $data_list = $this->transfer_model->get_transfer_city_list($term);
        if (valid_array($data_list) == false) {
            $data_list = $this->transfer_model->get_transfer_city_list('');
        }
        $data = array();
        $result=[];
        foreach ($data_list as $city_list) {
            $data['label'] = $city_list['city_name'] . ', ' . $city_list['country_name'] . '';
            $data['value'] = hotel_suggestion_value($city_list['city_name'], $city_list['country_name']);
            $data['id'] = $city_list['origin'];
            if (empty($city_list['top_destination']) == false) {
                $data['category'] = 'Top cities';
                $data['type'] = 'Top cities';
            } else {
                $data['category'] = 'Search Results';
                $data['type'] = 'Search Results';
            }
            if (intval($city_list['cache_hotels_count']) > 0) {
                $data['count'] = $city_list['cache_hotels_count'];
            } else {
                $data['count'] = 0;
            }
            $result[] = $data;
        }
        echo json_encode($result);
        
    }

    // function pre_booking_api()
    // {
    //     exit('Booking has been blocked..!<a href="'.base_url().'">Go to Home</a>');
    // }

    function pre_booking_api()
    { 
        // exit('Booking has been blocked live environment..!<a href="'.base_url().'">Go to Home</a>');

        $params = $this->input->post();
        $params['billing_city'] = 'Bangalore';
        $params['billing_zipcode'] = '560100';
        $params['billing_address_1'] = '2nd Floor, Venkatadri IT Park, HP Avenue,, Konnappana Agrahara, Electronic city';
       // $search_id = $params['search_id'];
        // $params['payment_method'] = 'PNHB1';
        //$promocode = @$params['promocode_val'];
        //$promo_data = $this->domain_management_model->promocode_details($promocode);
        //$promocode_discount_val = @$promo_data['value'];
        //$promocode_discount_type = @$promo_data['value_type'];
         
        /*$safe_search_data = $this->transfer_model->get_safe_search_data($search_id);
        $safe_search_data['data']['search_id'] = abs($search_id);*/
         
        //$transaction_id = PROJECT_PREFIX .'-'. TRANSFER_BOOKING . '-' . date ( 'dmHi' ) . '-' . rand ( 1000, 9999 ); // FIX ME - generate 25 character code
        #$transaction_id = 'TR'.  date ( 'dmYHi' ) .rand ( 1000, 9999 ); // FIX ME - generate 25 character code
        
        //debug($params);exit;
        $temp_booking = $this->module_model->serialize_temp_booking_record($params, TRANSFER_BOOKING);
    //debug($temp_booking);exit;
        if($params ['payment_method'] =='')
            {
              $params ['payment_method']=OFFLINE_PAYMENT;
            }
        $book_id = $temp_booking['book_id'];
        $book_origin = $temp_booking['temp_booking_origin'];
        $params['book_id'] = $temp_booking['book_id'];
        $params['book_origin'] = $temp_booking['temp_booking_origin'];
        $trip=$params['triptype'];
        if (isset($params['token']) == true) {
            $token = unserialized_data($params['token'], $params['token_key']);
            // debug($token);exit;
            if ($params['booking_source'] == HOTELBED_TRANSFER_BOOKING_SOURCE) {
                /*$amount     = $this->hotel_lib->total_price($temp_token['markup_price_summary']);
                 $currency = $temp_token['default_currency'];*/

                $amount   = $token['total_amount'];
                $currency_code = $token['currency_code'];
                $convenience_fees = $token['convinence_fee'];
                $pg_currency_conversion_rate=1;
            }
                
            //debug($params);
            //details for PGI
            $email = $params ['billing_email'];
            $phone = $params ['passenger_contact'];
            //$verification_amount = round(($amount+$convenience_fees-$promocode_discount),2);
            $verification_amount = round(($amount+$convenience_fees),2);
            // debug($verification_amount);exit;
            $firstname = $params['first_name']." ".$params['last_name'];
            $productinfo = META_TRANSFER_COURSE;
            //$amount = $params['transfers_total_amount'];
            //$currency_code = $params['currency_code'];
                

            //check current balance before proceeding further
            $domain_balance_status = $this->domain_management_model->verify_current_balance($verification_amount, $currency_code);
            //  debug($verification_amount);exit;
            $promocode_discount = 0;

            // debug($params);exit;
            if ($domain_balance_status == true) {
                switch($params['payment_method']) {
                        // case PAY_NOW :
                        // // debug($verification_amount);exit;
                        // $this->load->model('transaction');
                        // $this->transaction->create_payment_record($book_id, $verification_amount, $firstname, $email, $phone, $productinfo, $convenience_fees, $promocode_discount,$pg_currency_conversion_rate);
                        //   redirect(base_url().'index.php/payment_gateway/payment/'.$book_id.'/'.$book_origin);  
                          case ONLINE_PAYMENT :
                        $this->load->model('transaction');
                        $this->transaction->create_payment_record($book_id, $verification_amount, $firstname, $email, $phone, $productinfo, $convenience_fees, $promocode_discount,$pg_currency_conversion_rate);
                          redirect(base_url().'index.php/payment_gateway/payment/'.$book_id.'/'.$book_origin); 
                        break;
                        case OFFLINE_PAYMENT :
                        // debug($book_id); exit;
                        $this->load->model('transaction');
                        $this->transaction->create_payment_record($book_id, $verification_amount, $firstname, $email, $phone, $productinfo, $convenience_fees, $promocode_discount,$pg_currency_conversion_rate);
                        // debug($book_origin); 
                        // debug($book_id); exit;
                         // redirect(base_url().'index.php/payment_gateway/payment/'.$book_id.'/'.$book_origin);
                        redirect(base_url().'transfer/secure_booking_api/'.$book_id.'/'.$book_origin);                     
                        break;  

                        /*redirect('transfer/secure_booking_api/'.$book_id.'/'.$book_origin);
                         exit;
                            
                        $page_params ['form_url'] = base_url().'index.php/payment_gateway/pay/'.base64_encode($book_id).'/'.$book_origin;
                        $page_params ['form_method'] = 'POST';
                        $page_params ['form_params'] ['app_reference'] = $book_id;
                            
                        echo $this->template->isolated_view ( 'share/dynamic_js_form_submission', $page_params );
                            
                        exit;*/
                        #redirect('transfer/secure_booking/'.$book_id.'/'.$book_origin);
                        // break;
                    case PAY_AT_BANK : echo 'Under Construction - Remote IO Error';exit;
                    break;
                }
                /*load_transfer_lib($params['booking_source']);
                    if ($params['booking_source'] == HB_TRANSFERS_BOOKING_SOURCE && isset($params['purchase_token']) == true) {
                    $params['purchase_token']   = urldecode($params['purchase_token']);
                    $raw_booking_details = $this->transfer_lib->process_booking($safe_search_data['data'], $params);

                    if(isset($raw_booking_details['status']) && $raw_booking_details['status'] == true) {
                    //For XML to Array convertion
                        
                    if(isset($transfer_book_response) && !empty($transfer_book_response)) {
                    $raw_transfer_book = $this->transfer_lib->formate_booking_response_data($transfer_book_response,$params);
                    }
                    }
                    }*/
            }else {
                redirect(base_url().'index.php/transfer/exception?op=Amount Transfer Booking&notification=insufficient_balance');
            }
        }else {
            redirect(base_url().'index.php/transfer/exception?op=Amount Transfer Booking&notification=insufficient_balance');
        }
    }

    public function secure_booking_api($book_id,$book_origin) {
        // exit('try later sorry');
        //run booking request and do booking
    
        
        $temp_booking = $this->module_model->unserialize_temp_booking_record($book_id,$book_origin);
        // debug($temp_booking);exit;
    
        $search_id = $temp_booking['book_attributes']['search_id'];
        $safe_search_data = $this->transfer_model->get_safe_search_data($search_id);
        //debug($safe_search_data);exit;
        $from_transfer_type = @$safe_search_data['data']['from_transfer_type'];
        $to_transfer_type = @$safe_search_data['data']['to_transfer_type'];
        load_transfer_lib($temp_booking['booking_source']);
        
        $total_booking_price =$temp_booking['book_attributes']['token']['AgentNetRate'];
        $currency = $temp_booking['book_attributes']['currency_code'];
        //also verify provab balance
        //check current balance before proceeding further
        $domain_balance_status = $this->domain_management_model->verify_current_balance($total_booking_price, $currency);
        // debug($domain_balance_status);exit;
        $total_amount_val = 0;
        
        if(isset($temp_booking['book_attributes'])){
            $book_attributes = $temp_booking['book_attributes'];
        // debug($book_attributes);exit;
            $service_tax = $book_attributes['token']['service_tax'];
            if(isset($book_attributes['total_amount']) && valid_array($book_attributes['total_amount'])){
                foreach($book_attributes['total_amount'] as $total_amount_k => $total_amount_v){
                    $total_amount_val = $total_amount_val + $total_amount_v;
                }
            }
                
            
            $data['fare'] = $total_amount_val+$service_tax;
            $data['admin_markup'] = $book_attributes['token']['admin_markup'];
            $data['agent_markup'] =$book_attributes['token']['agent_markup'];
                        $data['AgentNetRate'] =$book_attributes['token']['AgentNetRate'];
                        $data['AgentServiceTax'] =$book_attributes['token']['AgentServiceTax'];
                        $data['XMLPrice'] =$book_attributes['token']['XMLPrice'];
                        $data['AgentNetPrice'] =$book_attributes['token']['AgentNetRate'];
            $data['convinence'] = $book_attributes['token']['convinence_fee'];;
            $data['discount'] = 0;
        }
        // debug($domain_balance_status);exit;
        
        if ($domain_balance_status) {
            //lock table
            if ($temp_booking != false) {
                switch ($temp_booking['booking_source']) {
                    case HOTELBED_TRANSFER_BOOKING_SOURCE :
                        //FIXME : COntinue from here - Booking request
                        $temp_booking['book_attributes']['horiizon_reference'] = $book_id;
                        $temp_booking['book_attributes']['from_transfer_type'] = $from_transfer_type;
                        $temp_booking['book_attributes']['to_transfer_type']   = $to_transfer_type;
                        $book_id_module = $book_id."**"."b2c";
                        $booking = $this->transfer_lib->process_booking($book_id_module, $temp_booking['book_attributes'], $module='b2c');
                        // debug($booking);
                        // debug($temp_booking);
                    
                        //Save booking based on booking status and book id
                        break;
                }
                $booking = $this->transfer_lib->formate_booking_response_data($booking,$temp_booking);
               // debug($booking); die();
                if ($booking['status'] == SUCCESS_STATUS) {
                    

                    if($GLOBALS['CI']->loyalty_program_on_off)
                            {
                                // echo "dd";exit;
                                $this->load->model('loyalty_program_model');
                                $get_master_module_status=$this->loyalty_program_model->get_master_module_status('Transfer');
                                if($get_master_module_status['status']=='ENABLE')
                                {



                                    $current_user_id = $GLOBALS['CI']->entity_user_id;
                                    $get_agent_module_status=$this->loyalty_program_model->get_agent_module_status('Transfer',$current_user_id);
                                    // debug($current_user_id);
                                    if($get_agent_module_status)
                                    {
                                        if($get_agent_module_status['status'])
                                        {


                                            $agent_base_currency = 'NPR';
                                            if($agent_base_currency=="NPR")
                                            {
                                                $agent_loyalty_amt=$agent_loyalty_amt;
                                            }
                                            else
                                            {
                                                $this->load->model('domain_management_model');
                                                $currency_obj_m = new Currency(array('module_type' =>'hotel', 'from' => $agent_base_currency, 'to' =>'NPR'));
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
                                           
                                            $expiredate=$safe_search_data['data']['from_date'];
                                            $agentemailid=provab_encrypt($GLOBALS['CI']->entity_email);
                                            $agent_name=$GLOBALS['CI']->entity_name;
                                            $trans_data=array('agent_id'=>$current_user_id,'booking_type'=>'Transfer','booking_reference'=>$book_id,'reward_point'=>$reward_point,'expire_date'=>$expiredate,'created_date'=>date('Y-m-d'),'tr_type'=>'Credit','description'=>'transfer booking reward','status'=>$status);
                                            $this->loyalty_program_model->insert_transation($trans_data);
                                            $get_agent_total_reward=$this->loyalty_program_model->get_agent_total_reward($current_user_id);
                                            $reward_total=$get_agent_total_reward['t_reward']+$reward_point;
                                            $transfer=$get_agent_total_reward['transfer']+$reward_point;
                                            $up_data=array('t_reward'=>$reward_total,'transfer'=>$transfer);
                                            $this->loyalty_program_model->update_total_reward_point($current_user_id,$up_data);



                                        }
                                    }    
                                }
                            }
                
                    $currency_obj = new Currency(array('module_type' => 'car', 'from' => get_application_default_currency(), 'to' => get_application_default_currency()));
                    $promo_currency_obj = new Currency(array('module_type' => 'transferv1', 'from' => get_application_currency_preference(), 'to' => admin_base_currency()));
                    $booking['data']['currency_obj'] = $currency_obj;
                    $booking['data']['promo_currency_obj']=$promo_currency_obj;
                    $data = $this->transfer_lib->save_booking($book_id, $booking['data']);
                    //Save booking based on booking status and book id
                    $this->domain_management_model->update_transaction_details('transferv1', $book_id, $data['fare'], $data['admin_markup'], $data['agent_markup'], $data['convinence'], $data['discount'] );
                    //echo base_url().'index.php/voucher/transfer/'.$book_id.'/'.$temp_booking['booking_source'].'/BOOKING_CONFIRMED/email_voucher/'.$search_id.'/'.$email;exit;                    
                    //redirect(base_url().'index.php/voucher/transfer/'.$book_id.'/'.$temp_booking['booking_source'].'/BOOKING_CONFIRMED/email_voucher/'.$search_id.'/'.$email);
                    redirect(base_url().'index.php/voucher/transfers/'.$book_id.'/'.$temp_booking['booking_source'].'/'.$data['booking_status'].'/show_voucher');
                    // redirect(base_url().'index.php/voucher/transferv1/'.$book_id.'/'.$temp_booking['booking_source'].'/'.$data['booking_status'].'/show_voucher');
                } else {
                    // echo "else1";exit;
                    $msg = @$booking['data']['ErrorList']['Error']['DetailedMessage'];
                    redirect(base_url().'index.php/transfer/exception?op=booking_exception&notification='.$msg);
                }
            }
            //release table lock
        }else {
            echo 'else2';exit;
            redirect(base_url().'index.php/transfer/exception?op=Remote IO error @ Insufficient&notification=validation');
        }
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

    /**
     * Booking Cancellation
     */
    function pre_cancellation($app_reference, $booking_source)
    {
        if (empty($app_reference) == false && empty($booking_source) == false) {
            $page_data = array();
            $booking_details = $this->transfer_model->get_booking_details_transfer($app_reference, $booking_source);
            if ($booking_details['status'] == SUCCESS_STATUS) {
                $this->load->library('booking_data_formatter');
                //Assemble Booking Data
                $assembled_booking_details = $this->booking_data_formatter->format_transfer_booking_data_crs($booking_details, 'b2b');
                $page_data['data'] = $assembled_booking_details['data'];
                $this->template->view('transfer/pre_cancellation', $page_data);
            } else {
                redirect('security/log_event?event=Invalid Details');
            }
        } else {
            redirect('security/log_event?event=Invalid Details');
        }
    }
    /*
     * Process the Booking Cancellation
     * Full Booking Cancellation
     *
     */
    public function cancel_full_booking($app_reference)
  {
    // error_reporting(E_ALL);ini_set('display_error', 'on');
    $this->load->model('custom_db');
    $this->custom_db->update_record('transfer_booking_details',array('status'=>'BOOKING_CANCELLED','final_cancel_date'=>date("Y-m-d h:i:sa")),array('app_reference'=>$app_reference));  
    $this->custom_db->update_record('transfer_booking_itinerary_details',array('status'=>'BOOKING_CANCELLED'),array('app_reference'=>$app_reference)); 
    $this->custom_db->update_record('transfer_booking_transaction_details',array('status'=>'BOOKING_CANCELLED'),array('app_reference'=>$app_reference));   
    $this->custom_db->update_record('transfer_booking_passenger_details',array('status'=>'BOOKING_CANCELLED'),array('app_reference'=>$app_reference));
    $condition[]=array(
      'BD.app_reference','=','"'.$app_reference.'"'
      );
    $page_data['app_reference'] = $app_reference;
    $page_data['status'] = 'BOOKING_CANCELLED';
    $booking_details = $this->transfer_model->booking($condition);
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
  $this->template->view('transfer/cancellation_details',$page_data);
 }
    function cancel_booking($app_reference, $booking_source)
    {
        if(empty($app_reference) == false) {
            $get_params = $this->input->get();

            $master_booking_details = $this->transfer_model->get_booking_details($app_reference, $booking_source);
            // debug($master_booking_details);exit;
            if ($master_booking_details['status'] == SUCCESS_STATUS) {
                $this->load->library('booking_data_formatter');
                $master_booking_details = $this->booking_data_formatter->format_transfer_booking_data_crs($master_booking_details, 'b2b');
                $master_booking_details = $master_booking_details['data']['booking_details'][0];
                // debug($booking_source);exit;
                load_transfer_lib($booking_source);
                 // debug($master_booking_details);exit;
                $cancellation_details = $this->transfer_lib->cancel_booking($master_booking_details,$get_params);//Invoke Cancellation Methods
                if($cancellation_details['status'] == false) {
                    $query_string = '?error_msg='.$cancellation_details['msg'];
                } else {
                    $query_string = '';
                }
                redirect('transfer/cancellation_details/'.$app_reference.'/'.$booking_source.$query_string);
            } else {
                redirect('security/log_event?event=Invalid Details');
            }
        } else {
            redirect('security/log_event?event=Invalid Details');
        }
    }
     /**
     * Displays Cancellation Refund Details
     * @param unknown_type $app_reference
     * @param unknown_type $status
     */
    public function cancellation_refund_details()
    {
        $get_data = $this->input->get();
        if(isset($get_data['app_reference']) == true && isset($get_data['booking_source']) == true && isset($get_data['status']) == true && $get_data['status'] == 'BOOKING_CANCELLED'){
            $app_reference = trim($get_data['app_reference']);
            $booking_source = trim($get_data['booking_source']);
            $status = trim($get_data['status']);
            $booking_details = $this->transfer_model->get_booking_details($app_reference, $booking_source, $status);
            debug($booking_details);exit;
            if($booking_details['status'] == SUCCESS_STATUS){
                $page_data = array();
                $page_data['booking_data'] =        $booking_details['data'];
                $this->template->view('transfer/cancellation_refund_details', $page_data);
            } else {
                redirect(base_url());
            }
        } else {
            redirect(base_url());
        }
    }

    /**
     * Cancellation Details
     * @param $app_reference
     * @param $booking_source
     */
    function cancellation_details($app_reference, $booking_source)
    {
        if (empty($app_reference) == false && empty($booking_source) == false) {
            $master_booking_details = $GLOBALS['CI']->transfer_model->get_booking_details($app_reference, $booking_source);
            if ($master_booking_details['status'] == SUCCESS_STATUS) {
                $page_data = array();
                $this->load->library('booking_data_formatter');
                $master_booking_details = $this->booking_data_formatter->format_transfer_booking_data_crs($master_booking_details, 'b2b');
                $page_data['data'] = $master_booking_details['data'];
                $this->template->view('transfer/cancellation_details', $page_data);
            } else {
                redirect('security/log_event?event=Invalid Details');
            }
        } else {
            redirect('security/log_event?event=Invalid Details');
        }

    }
    


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

}
