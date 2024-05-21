<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 *
 * @package    Provab
 * @subpackage Bus
 * @author     Balu A<balu.provab@gmail.com>
 * @version    V1
 */
class Bus extends CI_Controller {

    private $current_module;

    public function __construct() {
        parent::__construct();
        //we need to activate bus api which are active for current domain and load those libraries
        $this->index();
        $this->load->model('bus_model');
        $this->load->model('user_model'); // we need to load user model to access provab sms library
        $this->load->library('provab_sms'); // we need this provab_sms library to send sms.
        $this->current_module = $this->config->item('current_module');
    }

    /**
     * index page of application will be loaded here
     */
    function index() {
        
    }

    /**
     *  Balu A
     * Load bus Search Result
     * @param number $search_id unique number which identifies search criteria given by user at the time of searching
     */
    function search($search_id) {
        $safe_search_data = $this->bus_model->get_safe_search_data($search_id);
        // debug($safe_search_data);exit;
        // Get all the busses bookings source which are active
        $active_booking_source = $this->bus_model->active_booking_source();
        if ($safe_search_data['status'] == true and valid_array($active_booking_source) == true) {
            $safe_search_data['data']['search_id'] = abs($search_id);
            
            $this->template->view('bus/search_result_page', array('bus_search_params' => $safe_search_data['data'], 'active_booking_source' => $active_booking_source));
        } else {
            $this->template->view('general/popup_redirect');
        }
    }

    /**
     * Balu A
     * @param int $search_id
     * @param date $date
     */
    function prev_next_day_search($search_id, $new_search_date) {
        $current_date = date('d-m-Y');
        if (intval($search_id) > 0 && strtotime($new_search_date) >= strtotime($current_date)) {
            $safe_search_data = $this->bus_model->get_safe_search_data($search_id);
            if ($safe_search_data['status'] == true) {
                $search_params = array();
                $search_params['bus_station_from'] = trim($safe_search_data['data']['bus_station_from']);
                $search_params['from_station_id'] = '';
                $search_params['bus_station_to'] = trim($safe_search_data['data']['bus_station_to']);
                $search_params['to_station_id'] = '';
                $search_params['bus_date_1'] = date('d-m-Y', strtotime($new_search_date));
                redirect(base_url() . 'index.php/general/pre_bus_search?' . http_build_query($search_params));
            } else {
                $this->template->view('general/popup_redirect');
            }
        } else {
            $this->template->view('general/popup_redirect');
        }
    }

    /**
     *  Balu A
     * Passenger Details page for final bookings
     * Here we need to run booking based on api
     */
    function booking($search_id) {
        $pre_booking_params = $this->input->post();
        
        $safe_search_data = $this->bus_model->get_safe_search_data($search_id);
        $safe_search_data['data']['search_id'] = abs($search_id);
        $page_data['active_payment_options'] = $this->module_model->get_active_payment_module_list();
        if (isset($pre_booking_params['booking_source']) == true) {
            $currency_obj = new Currency(array('module_type' => 'bus', 'from' => get_application_currency_preference(), 'to' => get_application_currency_preference()));
            //We will load different page for different API providers... As we have dependency on API for bus details page
            $page_data['search_data'] = $safe_search_data['data'];
            load_bus_lib($pre_booking_params['booking_source']);
            //Need to fill pax details by default if user has already logged in
            $this->load->model('user_model');
            $page_data['pax_details'] = array();

            //Not to show cache data in browser
            header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
            header("Cache-Control: post-check=0, pre-check=0", false);
            header("Pragma: no-cache");

            if ($pre_booking_params['booking_source'] == PROVAB_BUS_BOOKING_SOURCE && isset($pre_booking_params['route_schedule_id']) == true and isset($pre_booking_params['pickup_id']) == true and
                    count($pre_booking_params['seat']) > 0 and $safe_search_data['status'] == true) {
                $pre_booking_params['token'] = unserialized_data($pre_booking_params['token'], $pre_booking_params['token_key']);
                $bus_details = $pre_booking_params['token'];
              //  debug($bus_details['Layout']);exit;
                //$bus_details = $this->bus_lib->get_bus_details($pre_booking_params['route_schedule_id'], $pre_booking_params['journey_date']);
                if ($pre_booking_params['token'] != false && valid_array($bus_details)) {
                    //Index seat numbers
                    $indexed_seats = $GLOBALS ['CI']->bus_lib->index_seat_number(force_multple_data_format($bus_details ['Layout'] ['SeatDetails'] ['clsSeat']));
                    
                    //Filter only selected seats
                    $selected_seats = array();
                    $total_fare = 0;
                    $markup_total_fare = 0;
                    $domain_total_fare = 0;
                    $domain_currency_obj = $currency_obj;
                    $page_data['domain_currency_obj'] = $domain_currency_obj;
                    $fare_breakdown = array();
                    foreach ($pre_booking_params['seat'] as $ssk => $ssv) {
                       
                        $cur_seat_attr = $indexed_seats[$ssv];
                        // debug($cur_seat_attr);exit;
                        $total_fare += $cur_seat_attr ['total_fare'];
                        // echo $total_fare;exit;
                        // total currency to customer
                        // $temp_currency = $currency_obj->get_currency($cur_seat_attr ['Fare'], true, false, true);
                        // currency to be deducted from domain
                        
                        // $domain_currency = $domain_currency_obj->get_currency($cur_seat_attr ['Net'] ['Fare'], true, false, false);
                        
                        $cur_seat_attr ['Markup_Fare'] = $temp_currency ['default_value'];
                        $markup_total_fare += $cur_seat_attr ['Fare'];
                        $domain_total_fare += $cur_seat_attr ['Fare'];

                        $selected_seats[$ssv] = $cur_seat_attr;

                        //fare breakdown
                        
                        $this->bus_lib->fare_breakdown_summary($fare_breakdown, $cur_seat_attr);
                    }
                    
                    $page_data['summary'] = $fare_breakdown;
                    $page_data['default_currency'] = $temp_currency['default_currency'];
                    $page_data['default_currency_symbol'] = $domain_currency_obj->get_currency_symbol($page_data['default_currency']);
                    $page_data['total_fare'] = $total_fare;
                    $page_data['markup_total_fare'] = $markup_total_fare;
                    $page_data['domain_total_fare'] = $domain_total_fare;

                    $bus_details ['Layout'] ['SeatDetails'] ['clsSeat'] = $selected_seats;
                    $bus_details ['Pickup'] ['clsPickup'] = $GLOBALS ['CI']->bus_lib->index_pickup_number(force_multple_data_format(@$bus_details ['result']['Pickups']));
                    $bus_details ['Drop'] ['clsDrop'] = $GLOBALS ['CI']->bus_lib->index_drop_number(force_multple_data_format(@$bus_details ['result']['Dropoffs']));
                    $bus_details ['CancellationCharges'] ['clsCancellationCharge'] = force_multple_data_format($bus_details ['result'] ['Canc']);
                    // debug($bus_details);exit;
                    //----------- page data
                    $page_data['details'] = $bus_details;
                    $page_data['pre_booking_params'] = $pre_booking_params;
                    $page_data['pre_booking_params']['default_currency'] = admin_base_currency();
                    $page_data['iso_country_list'] = $this->db_cache_api->get_iso_country_list();
                    $page_data['country_list'] = $this->db_cache_api->get_country_list();
                    $page_data['currency_obj'] = $currency_obj;
                    //Summarize Price
                    //$page_data['price_summary'] = '';

                    $page_data['pax_title_enum'] = get_enum_list('title');
                    $gender_enum = get_enum_list('gender');
                    // TRAVELYAARI does not support others gender so we need to unset this
                    unset($gender_enum [3]);
                    $page_data['gender_enum'] = $gender_enum;
                    $Domain_record = $this->custom_db->single_table_records('domain_list', '*');
                    $page_data['active_data'] = $Domain_record['data'][0];
                    $temp_record = $this->custom_db->get_phone_code_list();
                    $page_data['phone_code'] =$temp_record;
                    
                    $this->template->view('bus/travelyaari/travelyaari_booking_page', $page_data);
                }
            } else {
                redirect(base_url());
            }
        } else {
            redirect(base_url());
        }
    }

    /**
     *  Balu A
     * Secure Booking of bus
     * 2879 single adult static booking request 2500
     * 261 double room static booking request 2308
     */
    function pre_booking($search_id = 2500, $static_search_result_id = 2879) {
        $post_params = $this->input->post();
       
        $this->custom_db->generate_static_response(json_encode($post_params));
        //Insert To temp_booking and proceed
        /* $post_params = $this->bus_model->get_static_response($static_search_result_id); */
        //Make sure token and temp token matches
        $valid_temp_token = unserialized_data($post_params['token'], $post_params['token_key']);
        
        if ($valid_temp_token != false) {
            load_bus_lib($post_params['booking_source']);
            /*             * **Convert Display currency to Application default currency** */
            //After converting to default currency, storing in temp_booking
            $post_params['token'] = unserialized_data($post_params['token']);
            $currency_obj = new Currency(array(
                'module_type' => 'bus',
                'from' => get_application_currency_preference(),
                'to' => admin_base_currency()
            ));
            
            $post_params['token'] = $this->bus_lib->convert_token_to_application_currency($post_params['token'], $currency_obj, $this->current_module);
            $post_params['token'] = serialized_data($post_params['token']);
            $temp_token = unserialized_data($post_params['token']);

            
            if ($post_params['booking_source'] == PROVAB_BUS_BOOKING_SOURCE) {
                $amount = $temp_token['seat_attr']['domain_deduction_fare'];
                $currency = $temp_token['seat_attr']['default_currency'];
            }
            $Markup = $amount - $temp_token['seat_attr']['total_price_summary'];
            //Adding GST
            $gst_value = 0;
            if($Markup > 0 ){
                $gst_details = $GLOBALS['CI']->custom_db->single_table_records('gst_master', '*', array('module' => 'bus'));
                if($gst_details['status'] == true){
                    if($gst_details['data'][0]['gst'] > 0){
                        $gst_value = ($Markup/100) * $gst_details['data'][0]['gst'];
                    }
                }
            }
            //check current balance before proceeding further
            $agent_paybleamount = $currency_obj->get_agent_paybleamount($amount);

            $agent_paybleamount1 = roundoff_number($agent_paybleamount['amount'] + $gst_value);
         
            $domain_balance_status = $this->domain_management_model->verify_current_balance($agent_paybleamount1, $agent_paybleamount['currency']);
            if ($domain_balance_status == true) {
                //Block Seats
                //run block and then booking request
                $post_params['token'] = $temp_token;
                $block_status = $this->bus_lib->block_seats($search_id, $post_params);
                if ($block_status['status'] == SUCCESS_STATUS) {
                    $post_params['block_key'] = $block_status['data']['result']['HoldKey'];
                    $post_params['block_data'] = $block_status['data']['result']['Passenger'];
                    //update seat block details and continue
                    $post_params['token'] = serialized_data($post_params['token']);
                    $temp_booking = $this->module_model->serialize_temp_booking_record($post_params, BUS_BOOKING);

                    $book_id = $temp_booking['book_id'];
                    $book_origin = $temp_booking['temp_booking_origin'];

                    //details for PGI
                    $email = $post_params ['billing_email'];
                    $phone = $post_params ['passenger_contact'];
                    $pgi_amount = $amount;
                    $firstname = $post_params ['contact_name'] ['0'];
                    $productinfo = META_BUS_COURSE;

                    switch ($post_params['payment_method']) {
                        case PAY_NOW :
                            $this->load->model('transaction');
                            $pg_currency_conversion_rate = $currency_obj->payment_gateway_currency_conversion_rate();
                            $this->transaction->create_payment_record($book_id, $pgi_amount, $firstname, $email, $phone, $productinfo, 0, 0, $pg_currency_conversion_rate, $data['transaction_currency'], $data['currency_conversion_rate']);
                            redirect(base_url() . 'index.php/payment_gateway/payment/' . $book_id . '/' . $book_origin);
                            break;
                        case PAY_AT_BANK : echo 'Under Construction - Remote IO Error';
                            exit;
                            break;
                    }
                } else {
                    redirect(base_url() . 'index.php/bus/exception?op=seat_block&notification=' . $block_status['msg']);
                }
            } else {
                redirect(base_url() . 'index.php/bus/exception?op=booking_balance&notification=insufficient balance');
            }
        }

        redirect(base_url() . 'index.php/bus/exception?op=validation_hack&notification=Remote IO error @ Bus Booking');
    }

    /*
      process booking in backend until show loader
     */

    function process_booking($book_id, $temp_book_origin) {

        if ($book_id != '' && $temp_book_origin != '' && intval($temp_book_origin) > 0) {

            $page_data ['form_url'] = base_url() . 'index.php/bus/secure_booking';
            $page_data ['form_method'] = 'POST';
            $page_data ['form_params'] ['book_id'] = $book_id;
            $page_data ['form_params'] ['temp_book_origin'] = $temp_book_origin;

            $this->template->view('share/loader/booking_process_loader', $page_data);
        } else {
            redirect(base_url() . 'index.php/bus/exception?op=Invalid request&notification=validation');
        }
    }

    /**
     *  Balu A
     * Do booking once payment is successfull - Payment Gateway
     * and issue voucher
     * BB19-133522-532376/45
     */
    function secure_booking()
    {
        // error_reporting(E_ALL);
        $post_data = $this->input->post();

        if (valid_array($post_data) == true && isset($post_data['book_id']) == true && isset($post_data['temp_book_origin']) == true &&
                empty($post_data['book_id']) == false && intval($post_data['temp_book_origin']) > 0) {
            //verify payment status and continue
            $book_id = trim($post_data['book_id']);
            $temp_book_origin = intval($post_data['temp_book_origin']);
        }
         else {
            redirect(base_url() . 'index.php/bus/exception?op=InvalidBooking&notification=invalid');
        }
        //run booking request and do booking
        $temp_booking = $this->module_model->unserialize_temp_booking_record($book_id, $temp_book_origin);
        
        //Delete the temp_booking record, after accessing
        $this->module_model->delete_temp_booking_record($book_id, $temp_book_origin);
        load_bus_lib($temp_booking['booking_source']);
        //verify payment status and continue
        $amount = $temp_booking['book_attributes']['token']['seat_attr']['domain_deduction_fare'];
        $currency = $temp_booking['book_attributes']['token']['seat_attr']['default_currency'];
        $currency_obj = new Currency(array('module_type' => 'bus', 'from' => admin_base_currency(), 'to' => admin_base_currency()));
        //also verify provab balance
        //check current balance before proceeding further
        $agent_paybleamount = $currency_obj->get_agent_paybleamount($amount);
        
        $domain_balance_status = $this->domain_management_model->verify_current_balance($agent_paybleamount['amount'], $agent_paybleamount['currency']);



        if ($domain_balance_status) {
            //lock table
            if ($temp_booking != false) {
                switch ($temp_booking['booking_source']) {
                    case PROVAB_BUS_BOOKING_SOURCE :
                        $booking = $this->bus_lib->process_booking($book_id, $temp_booking['book_attributes']);
                        break;
                }
                // debug($booking);exit;
                if ($booking['status'] == SUCCESS_STATUS) {
                    $get_bus_details = $this->bus_lib->get_booking_details($booking, $temp_booking['booking_source']);

                    $bookings['data']['result'] = $get_bus_details['data']['result']['GetBookingDetails'];
                    $bookings['data']['result']['ticket_details'] = $booking['data']['result']['ticket_details'];
                    $bookings['data']['temp_booking_cache'] = $temp_booking;
                    // debug($bookings);exit;
                    $currency_obj = new Currency(array('module_type' => 'bus', 'from' => admin_base_currency(), 'to' => admin_base_currency()));
                    $bookings['data']['currency_obj'] = $currency_obj;
                    //Save booking based on booking status and book id
                    $data = $this->bus_lib->save_booking($book_id, $bookings['data'], 'b2b');
                    $this->domain_management_model->update_transaction_details('bus', $book_id, $data['fare'], $data['domain_markup'], $data['level_one_markup'], @$data['convinence'], @$data['discount'], $data['transaction_currency'], $data['currency_conversion_rate']);
                   
                    //deduct balance and continue
                    // Sms config & Checkpoint
                    /* if(active_sms_checkpoint('booking'))
                      {
                      $msg = "Dear ".$data['name']." Thank you for Booking your ticket with us.Ticket Details will be sent to your email id";
                      //echo $msg;exit;
                      $msg = urlencode($msg);
                      $sms_status = $this->provab_sms->send_msg($data['phone'],$msg);
                      //return $sms_status;
                      } */
                    //sms config ends here,

                    redirect(base_url() . 'index.php/voucher/bus/' . $book_id . '/' . $temp_booking['booking_source'] . '/BOOKING_CONFIRMED/show_voucher');
                } else {
                    redirect(base_url() . 'index.php/bus/exception?op=booking_exception&notification=' . $booking['msg']);
                }
            }
            //release table lock
        } else {
            redirect(base_url() . 'index.php/bus/exception?op=Remote IO error @ Insufficient&notification=validation');
        }
        redirect(base_url() . 'index.php/bus/exception?op=Remote IO error @ bus Secure Booking&notification=validation');
    }

    /**
     *  Balu A
     * Process booking on hold - pay at bank
     */
    function booking_on_hold($book_id) {
        
    }

    /**
     * Balu A
     */
    function pre_cancellation($app_reference, $booking_source) {
        if (empty($app_reference) == false && empty($booking_source) == false) {
            $page_data = array();
            $booking_details = $this->bus_model->get_booking_details($app_reference, $booking_source);
            if ($booking_details['status'] == SUCCESS_STATUS) {
                $this->load->library('booking_data_formatter');
                //Assemble Booking Data
                $assembled_booking_details = $this->booking_data_formatter->format_bus_booking_data($booking_details, 'b2b');
                $page_data['data'] = $assembled_booking_details['data'];
                $this->template->view('bus/pre_cancellation', $page_data);
            } else {
                redirect('security/log_event?event=Invalid Details');
            }
        } else {
            redirect('security/log_event?event=Invalid Details');
        }
    }

    /*
     * Balu A
     * Process the Booking Cancellation
     * Full Booking Cancellation
     *
     */

    function cancel_booking($app_reference, $booking_source) {
        //echo 'Under Construction';exit;
        if (empty($app_reference) == false) {
            $master_booking_details = $this->bus_model->get_booking_details($app_reference, $booking_source);
            if ($master_booking_details['status'] == SUCCESS_STATUS) {
                $this->load->library('booking_data_formatter');
                $master_booking_details = $this->booking_data_formatter->format_bus_booking_data($master_booking_details, 'b2b');
                $master_booking_details = $master_booking_details['data']['booking_details'][0];
                $PNRNo = trim($master_booking_details['pnr']);
                $TicketNo = trim($master_booking_details['ticket']);
                $SetaNos = $master_booking_details['seat_numbers'];
                $booking_details = array();
                $booking_details['PNRNo'] = $PNRNo;
                $booking_details['TicketNo'] = $TicketNo;
                $booking_details['SeatNos'] = $SetaNos;
                $booking_details['booking_source'] = $master_booking_details['booking_source'];
                // debug($booking_details);exit;
                load_bus_lib($booking_source);
                $cancellation_details = $this->bus_lib->cancel_full_booking($booking_details, $app_reference); //Invoke Cancellation Methods
                // debug($cancellation_details);exit;
                if ($cancellation_details['status'] == true) {//IF Cancellation is Success
                    $cancellation_details = $this->bus_lib->save_cancellation_data($app_reference, $cancellation_details); //Save Cancellation Data
                }
                redirect('bus/cancellation_details/' . $app_reference . '/' . $booking_source);
            } else {
                redirect('security/log_event?event=Invalid Details');
            }
        } else {
            redirect('security/log_event?event=Invalid Details');
        }
    }

    /**
     * Balu A
     * Cancellation Details
     * @param $app_reference
     * @param $booking_source
     */
    function cancellation_details($app_reference, $booking_source) {
        if (empty($app_reference) == false && empty($booking_source) == false) {
            $master_booking_details = $GLOBALS['CI']->bus_model->get_booking_details($app_reference, $booking_source);
            if ($master_booking_details['status'] == SUCCESS_STATUS) {
                $page_data = array();
                $this->load->library('booking_data_formatter');
                $master_booking_details = $this->booking_data_formatter->format_bus_booking_data($master_booking_details, 'b2b');
                $page_data['data'] = $master_booking_details['data'];
                $this->template->view('bus/cancellation_details', $page_data);
            } else {
                redirect('security/log_event?event=Invalid Details');
            }
        } else {
            redirect('security/log_event?event=Invalid Details');
        }
    }

    /**
     * Balu A
     * Displays Cancellation Refund Details
     * @param unknown_type $app_reference
     * @param unknown_type $status
     */
    public function cancellation_refund_details() {
        $get_data = $this->input->get();
        if (isset($get_data['app_reference']) == true && isset($get_data['booking_source']) == true && isset($get_data['status']) == true && $get_data['status'] == 'BOOKING_CANCELLED') {
            $app_reference = trim($get_data['app_reference']);
            $booking_source = trim($get_data['booking_source']);
            $status = trim($get_data['status']);
            $booking_details = $this->bus_model->get_booking_details($app_reference, $booking_source, $status);
            if ($booking_details['status'] == SUCCESS_STATUS) {
                $page_data = array();
                $page_data['booking_data'] = $booking_details['data'];
                $this->template->view('bus/cancellation_refund_details', $page_data);
            } else {
                redirect(base_url());
            }
        } else {
            redirect(base_url());
        }
    }

    /**
     * Balu A
     */
   function exception() {
        $module = META_BUS_COURSE;
        $op = @$_GET['op'];
        $notification = @$_GET['notification'];
        // echo $notification;exit;
        $exception = $this->module_model->flight_log_exception ( $module, $op, $notification );
        
        $exception = base64_encode(json_encode($exception));
        // debug($exception);exit;
        // set ip log session before redirection
        $this->session->set_flashdata ( array (
                'log_ip_info' => true 
        ) );
        redirect ( base_url () . 'index.php/bus/event_logger/' . $exception );
    }

    function event_logger($exception = '') {
        $log_ip_info = $this->session->flashdata('log_ip_info');
        $this->template->view('bus/exception', array('log_ip_info' => $log_ip_info, 'exception' => $exception));
    }

}
