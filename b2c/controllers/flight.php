<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 *
 * @package Provab
 * @subpackage Flight
 * @author Balu A<balu.provab@gmail.com>
 * @version V1
 */
class Flight extends CI_Controller
{

    private $current_module;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('rewards');
        $this->load->model('flight_model');
        $this->load->model('user_model'); // we need to load user model to access provab sms library
        $this->load->library('provab_sms'); // we need this provab_sms library to send sms.
        $this->load->library('social_network/facebook'); //Facebook Library to enable share button
        $this->current_module = $this->config->item('current_module');
        $this->load->library('provab_mailer');
    }

    /**
     * App Validation and reset of data
     */
    function pre_calendar_fare_search()
    {
        $params = $this->input->get();
        $safe_search_data = $this->flight_model->calendar_safe_search_data($params);
        //Need to check if its domestic travel
        $from_loc = $safe_search_data['from_loc'];
        $to_loc = $safe_search_data['to_loc'];
        $safe_search_data['is_domestic_one_way_flight'] = false;

        $safe_search_data['is_domestic_one_way_flight'] = $this->flight_model->is_domestic_flight($from_loc, $to_loc);
        if ($safe_search_data['is_domestic_one_way_flight'] == false) {
            $page_params['from'] = '';
            $page_params['to'] = '';
        } else {
            $page_params['from'] = $safe_search_data['from'];
            $page_params['to'] = $safe_search_data['to'];
        }

        $page_params['depature'] = $safe_search_data['depature'];
        $page_params['carrier'] = $safe_search_data['carrier'];
        $page_params['adult'] = $safe_search_data['adult'];
        redirect(base_url() . 'index.php/flight/calendar_fare?' . http_build_query($page_params));
    }

    /**
     * Airfare calendar
     */
    function calendar_fare()
    {
        $params = $this->input->get();
        $active_booking_source = $this->flight_model->active_booking_source();
        if (valid_array($active_booking_source) == true) {
            $safe_search_data = $this->flight_model->calendar_safe_search_data($params);
            $page_params = array(
                'flight_search_params' => $safe_search_data,
                'active_booking_source' => $active_booking_source
            );
            $page_params['from_currency'] = get_application_default_currency();
            $page_params['to_currency'] = get_application_currency_preference();
            $this->template->view('flight/calendar_fare_result', $page_params);
        }
    }

    /**
     * Jaganaath
     */
    function add_days_todate()
    {

        $get_data = $this->input->get();

        $this->session->set_userdata('oldsearchid', $get_data['search_id']);

        if (isset($get_data['search_id']) == true && intval($get_data['search_id']) > 0 && isset($get_data['new_date']) == true && empty($get_data['new_date']) == false) {
            $search_id = intval($get_data['search_id']);
            $new_date = trim($get_data['new_date']);
            $safe_search_data = $this->flight_model->get_safe_search_data($search_id);

            $day_diff = get_date_difference($safe_search_data['data']['depature'], $new_date);
            if (valid_array($safe_search_data) == true && $safe_search_data['status'] == true) {
                $safe_search_data = $safe_search_data['data'];
                $search_params = array();
                $search_params['trip_type'] = trim($safe_search_data['trip_type']);
                $search_params['from'] = trim($safe_search_data['from']);
                $search_params['to'] = trim($safe_search_data['to']);
                $search_params['depature'] = date('d-m-Y', strtotime($new_date)); //Adding new Date
                if (isset($safe_search_data['return'])) {
                    $search_params['return'] = add_days_to_date($day_diff, $safe_search_data['return']); //Check it
                }
                $search_params['adult'] = intval($safe_search_data['adult_config']);
                $search_params['child'] = intval($safe_search_data['child_config']);
                $search_params['infant'] = intval($safe_search_data['infant_config']);
                $search_params['search_flight'] = 'search';
                $search_params['v_class'] = trim($safe_search_data['v_class']);
                $search_params['carrier'] = $safe_search_data['carrier'];
                $search_params['country'] = $safe_search_data['country']; //for next day and previous day button

                redirect(base_url() . 'index.php/general/pre_flight_search/?' . http_build_query($search_params));
            } else {
                $this->template->view('general/popup_redirect');
            }
        } else {
            $this->template->view('general/popup_redirect');
        }
    }

    /**
     * Balu A
     * Search Request from Fare Calendar
     */
    function pre_fare_search_result()
    {
        $get_data = $this->input->get();
        if (
            isset($get_data['from']) == true && empty($get_data['from']) == false &&
            isset($get_data['to']) == true && empty($get_data['to']) == false &&
            isset($get_data['depature']) == true && empty($get_data['depature']) == false
        ) {
            $from = trim($get_data['from']);
            $to = trim($get_data['to']);
            $depature = trim($get_data['depature']);
            $from_loc_details = $this->custom_db->single_table_records('flight_airport_list', '*', array('airport_code' => $from));
            $to_loc_details = $this->custom_db->single_table_records('flight_airport_list', '*', array('airport_code' => $to));
            if ($from_loc_details['status'] == true && $to_loc_details['status'] == true) {
                $depature = date('Y-m-d', strtotime($depature));
                $airport_code = trim($from_loc_details['data'][0]['airport_code']);
                $airport_city = trim($from_loc_details['data'][0]['airport_city']);
                $from = $airport_city . ' (' . $airport_code . ')';
                //To
                $airport_code = trim($to_loc_details['data'][0]['airport_code']);
                $airport_city = trim($to_loc_details['data'][0]['airport_city']);
                $to = $airport_city . ' (' . $airport_code . ')';

                //Forming Search Request
                $search_params = array();
                $search_params['trip_type'] = 'oneway';
                $search_params['from'] = $from;
                $search_params['to'] = $to;
                $search_params['depature'] = $depature;
                $search_params['adult'] = 1;
                $search_params['child'] = 0;
                $search_params['infant'] = 0;
                $search_params['search_flight'] = 'search';
                $search_params['v_class'] = 'Economy';
                $search_params['carrier'] = array('');
                redirect(base_url() . 'index.php/general/pre_flight_search/?' . http_build_query($search_params));
            } else {
                $this->template->view('general/popup_redirect');
            }
        } else {
            $this->template->view('general/popup_redirect');
        }
    }

    /**
     * Search Result
     * @param number $search_id
     */
    function search($search_id)
    {

        $safe_search_data = $this->flight_model->get_safe_search_data($search_id);
        // Get all the FLIGHT bookings source which are active
        $active_booking_source = $this->flight_model->active_booking_source();
        //   echo $this->db->last_query();
        //  debug($active_booking_source);die;
        $s_data = $safe_search_data['data'];
        if (($s_data['trip_type'] == 'circle') && ($s_data['is_domestic'] == true)) {
            $new_active_bs = array();
            foreach ($active_booking_source as $bk => $bv) {
                if ($bv['source_id'] != AMADEUS_FLIGHT_BOOKING_SOURCE) {
                    $new_active_bs[] = $bv;
                }
            }
            $active_booking_source = $new_active_bs;
        }
        if (valid_array($active_booking_source) == true and $safe_search_data['status'] == true) {
            $safe_search_data['data']['search_id'] = abs($search_id);
            $page_params = array(
                'flight_search_params' => $safe_search_data['data'],
                'active_booking_source' => $active_booking_source
            );

            if ($_SERVER['REMOTE_ADDR'] == "106.203.17.103") {
                //      debug($page_params);die;
            }
            $page_params['from_currency'] = get_application_default_currency();
            $page_params['to_currency'] = get_application_currency_preference();
            //Need to check if its domestic travel
            $from_loc = $safe_search_data['data']['from_loc'];
            $to_loc = $safe_search_data['data']['to_loc'];
            $page_params['is_domestic_one_way_flight'] = false;
            if ($safe_search_data['data']['trip_type'] == 'oneway') {
                $page_params['is_domestic_one_way_flight'] = $this->flight_model->is_domestic_flight($from_loc, $to_loc);
            }
            $page_params['airline_list'] = $this->db_cache_api->get_airline_code_list(); //Balu A
            $this->template->view('flight/search_result_page', $page_params);
        } else {
            if ($safe_search_data['status'] == true) {
                $this->template->view('general/popup_redirect');
            } else {
                $this->template->view('flight/exception');
            }
        }
    }

    /**
     * Balu A
     * Passenger Details page for final bookings
     * Here we need to run farequote/booking based on api
     * View Page for booking
     */
    function booking($search_id)
    {


        $pre_booking_params = $this->input->post();
        //debug(  $pre_booking_params);die;

        $search_hash = $pre_booking_params['search_hash'];

        //load_flight_lib($pre_booking_params ['booking_source']);
        load_flight_lib($pre_booking_params['booking_source']);

        $safe_search_data = $this->flight_lib->search_data($search_id);

        $safe_search_data['data']['search_id'] = intval($search_id);
        $token = $this->flight_lib->unserialized_token($pre_booking_params['token'], $pre_booking_params['token_key']);

        $safe_search_data['status'] = true; //plazma

        //changes start for nationality country code
        $nationalityCodeQuery = $this->custom_db->single_table_records('api_country_list', '*', array('nationality' => $safe_search_data['data']['country']))['data'][0];

        $page_data['nationality_code'] = $nationalityCodeQuery['origin'] != '' ? $nationalityCodeQuery['origin'] : 0;

        //upto here
        // }
        // debug($safe_search_data);die;
        if ($token['status'] == SUCCESS_STATUS) {
            $pre_booking_params['token'] = $token['data']['token'];
        }
        if (isset($pre_booking_params['booking_source']) == true && $safe_search_data['status'] == true) {

            $from_loc = $safe_search_data['data']['from_loc'];
            $to_loc = $safe_search_data['data']['to_loc'];

            //new if else condition for citizenship verification in case of nep-ind sector
            if (($safe_search_data['data']['country'] == "Nepalese" || $safe_search_data['data']['country'] == "Indian") && $safe_search_data['data']['trip_type'] != "multicity") {
                $page_data['isNepInd'] = $this->isSectorNepalIndia($from_loc, $to_loc);
            } else {
                $page_data['isNepInd'] = false;
            }
            $safe_search_data['data']['is_domestic_flight'] = $this->flight_model->is_domestic_flight($from_loc, $to_loc, $safe_search_data['data']['country']);
            if ($pre_booking_params['booking_source'] == PLAZMA_BOOKING_SOURCE) {
                $safe_search_data['data']['is_domestic_flight'] = 1;
            }
            $page_data['active_payment_options'] = $this->module_model->get_active_payment_module_list();
            $page_data['search_data'] = $safe_search_data['data'];
            // We will load different page for different API providers... As we have dependency on API for Flight details
            $page_data['search_data'] = $safe_search_data['data'];
            //Need to fill pax details by default if user has already logged in
            $this->load->model('user_model');
            $page_data['pax_details'] = $this->user_model->get_current_user_details();

            //Not to show cache data in browser
            header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
            header("Cache-Control: post-check=0, pre-check=0", false);
            header("Pragma: no-cache");
            $pre_booking_params['safe_search_data'] = $safe_search_data;

            switch ($pre_booking_params['booking_source']) {
                case PROVAB_FLIGHT_BOOKING_SOURCE:
                case PROVAB_AEROCRS_BOOKING_SOURCE:
                case PLAZMA_BOOKING_SOURCE:

                    // upate fare details
                    if ($pre_booking_params['booking_source'] != PLAZMA_BOOKING_SOURCE) {
                        $quote_update = $this->fare_quote_booking($pre_booking_params);
                    } else {

                        $quote_update = $this->fare_quote_booking($pre_booking_params, 'plazma');
                    }
                    //debug($quote_update);die;

                    //debug($token );die;
                    if ($quote_update['status'] == FAILURE_STATUS) {
                        //     echo "sdfe";die;
                        redirect(base_url() . 'index.php/flight/exception?op=Remote IO error @ Session Expiry&notification=session');
                    } else {
                        $pre_booking_params = $quote_update['data'];

                        //Get Extra Services
                        if ($pre_booking_params['booking_source'] != PLAZMA_BOOKING_SOURCE) {
                            $extra_services = $this->get_extra_services($pre_booking_params);
                            if ($extra_services['status'] == SUCCESS_STATUS) {
                                $page_data['extra_services'] = $extra_services['data'];
                            } else {
                                $page_data['extra_services'] = array();
                            }
                        } else {
                            $extra_services = array();
                            $extra_services['status'] = FAILURE_STATUS;
                            if ($extra_services['status'] == SUCCESS_STATUS) {
                                $page_data['extra_services'] = $extra_services['data'];
                            } else {
                                $page_data['extra_services'] = array();
                            }
                        }
                    }

                    if ($pre_booking_params['booking_source'] == PLAZMA_BOOKING_SOURCE) {
                        $currency_obj = new Currency(array(
                            'module_type' => 'plazmaflight',
                            'from' => get_application_currency_preference(),
                            'to' => get_application_currency_preference()
                        ));
                    } else {
                        $currency_obj = new Currency(array(
                            'module_type' => 'flight',
                            'from' => get_application_currency_preference(),
                            'to' => get_application_currency_preference()
                        ));
                    }

                    // Load View
                    $page_data['booking_source'] = $pre_booking_params['booking_source'];
                    $page_data['pre_booking_params']['default_currency'] = get_application_default_currency();
                    $page_data['iso_country_list'] = $this->db_cache_api->get_iso_country_code();

                    $page_data['country_list'] = $this->db_cache_api->get_iso_country_code();
                    foreach ($page_data['country_list'] as $key => $value) {
                        if ($value == $safe_search_data['data']['country']) {
                            $page_data['passnationality'] = $key;
                            $new_phone_code = $key;
                        }
                    }
                    // debug($safe_search_data ['data']);
                    //debug( $page_data ['passnationality']);die;
                    $page_data['currency_obj'] = $currency_obj;
                    //Traveller Details
                    $page_data['traveller_details'] = $this->user_model->get_user_traveller_details();
                    //Extracting Segment Summary and Fare Details

                    $updated_flight_details = $pre_booking_params['token'];
                    // changes: removed two lines below to remove round trip error
                    unset($updated_flight_details[0][0]);
                    unset($updated_flight_details[1][0]);
                    // echo json_encode( $updated_flight_details);die;
                    //  debug($currency_obj,);die;
                    $is_price_Changed = false;
                    $flight_details = array();

                    foreach ($updated_flight_details as $k => $v) {
                        if ($pre_booking_params['booking_source'] != PLAZMA_BOOKING_SOURCE) {

                            $temp_flight_details = $this->flight_lib->extract_flight_segment_fare_details2($v, $currency_obj, $search_id, $this->current_module);
                            // echo "test";die;

                        } else {
                            //debug($currency_obj);
                            $temp_flight_details = $this->flight_lib->extract_flight_segment_fare_details($v, $currency_obj, $search_id, $this->current_module);
                            // debug($temp_flight_details );die;
                        }
                        unset($temp_flight_details[0]['BookingType']); //Not needed in Next page
                        $flight_details[$k] = $temp_flight_details[0];
                    }

                    //Merge the Segment Details and Fare Details For Printing Purpose
                    $flight_pre_booking_summary = $this->flight_lib->merge_flight_segment_fare_details($flight_details);
                    /// debug($flight_pre_booking_summary);die;
                    $pre_booking_params['token'] = $flight_details;
                    $page_data['pre_booking_params'] = $pre_booking_params;
                    $page_data['pre_booking_summery'] = $flight_pre_booking_summary;
                    $TotalPrice = $flight_pre_booking_summary['FareDetails'][$this->current_module . '_PriceDetails']['TotalFare'];
                    // debug($currency_obj);
                    $page_data['convenience_fees'] = $currency_obj->convenience_fees($TotalPrice, $search_id);
                    // debug($page_data['convenience_fees']);die;
                    $page_data['is_price_Changed'] = $is_price_Changed;

                    //Get the country phone code 
                    $Domain_record = $this->custom_db->single_table_records('domain_list', '*');

                    $page_data['active_data'] = $Domain_record['data'][0];
                    $temp_record = $this->custom_db->get_phone_code_list();

                    //   echo $this->db->last_query();
                    //debug($Domain_record);die;
                    $page_data['phone_code'] = $temp_record;
                    //debug($new_phone_code);die;
                    if (!empty($this->entity_country_code)) {

                        $page_data['user_country_code'] = $this->entity_country_code;
                    } else {
                        $page_data['user_country_code'] = $Domain_record['data'][0]['phone_code'];
                    }
                    $convinence_fees_row = $this->private_management_model->get_convinence_fees('flight', $search_id);

                    $page_data['org_convience_fee'] = 0;
                    if (valid_array($convinence_fees_row)) {
                        if ($convinence_fees_row['type'] == 'percentage') {
                            $page_data['org_convience_fee'] = $convinence_fees_row['value'];
                        }
                    }
                    $page_data['convenience_fees_orginal'] = $convinence_fees_row;
                    $state_list = $this->custom_db->single_table_records('state_list', '*');
                    $state_list_array = array();
                    foreach ($state_list['data'] as $key => $state) {
                        $state_list_array[$state['en_name']] = $state['en_name'];
                    }
                    $page_data['state_list'] = $state_list_array;


                    //session expiry time calculation
                    $page_data['session_expiry_details'] = $this->flight_lib->set_flight_search_session_expiry(true, $search_hash);

                    //Pusing ExtraService details to pre_booking_params array()
                    $page_data['pre_booking_params']['extra_services'] = $extra_services;
                    $temp = $this->custom_db->single_table_records('insurance');

                    $page_data['insurance'] = $temp['data'][0];
                    /*#############################FOR REWARDS SYSTEM #########################################*/
                    //debug($flight_pre_booking_summary);die;
                    $page_data['total_price'] = round($flight_pre_booking_summary['FareDetails']['api_PriceDetails']['BaseFare'] + $flight_pre_booking_summary['FareDetails']['api_PriceDetails']['Tax'], 2);
                    // debug($page_data ['total_price']);die;
                    $page_data['reward_usable'] = 0;
                    $page_data['reward_earned'] = 0;
                    $page_data['total_price_with_rewards'] = 0;
                    if (is_logged_in_user()) {
                        $user_id = $this->entity_user_id;
                        //$data_rewards = $this->transaction->user_reward_details($user_id);
                        $currency_obj_r = new Currency(array('module_type' => 'flight', 'from' => UNIVERSAL_DEFAULT_CURRENCY, 'to' => get_application_display_currency_preference()));


                        $reward_values = $this->rewards->find_reward(META_AIRLINE_COURSE, get_converted_currency_value($currency_obj_r->force_currency_conversion($page_data['total_price'])));
                        //  debug($reward_values);die;
                        $reward_details = $this->rewards->get_reward_coversion_and_limit_details();
                        //debug($this->rewards)
                        //$usable_rewards = $reward_values['usable_reward'];
                        $usable_rewards = $this->rewards->usable_rewards($user_id, META_AIRLINE_COURSE, $reward_values['usable_reward']);
                        //  echo "test";
                        //debug($usable_rewards);die;
                        $page_data['reward_earned'] = $reward_values['earning_reward'];
                        //debug($usable_rewards);exit();
                        if ($reward_values['usable_reward'] <= $usable_rewards) {
                            $page_data['reward_usable'] = $reward_values['usable_reward'];
                        } else {
                            $page_data['reward_usable'] = 0;
                        }
                        //  debug($page_data['reward_usable']);exit();
                        if ($page_data['reward_usable']) {
                            $reducing_amount = $this->rewards->find_reward_amount($page_data['reward_usable']);
                            //  $reducing_amount = round($reducing_amount*$page_data['converted_currency_rate'],2);
                            $page_data['total_price_with_rewards'] = $page_data['total_price'] - get_converted_currency_value($currency_obj_r->force_currency_conversion($reducing_amount));
                            $page_data['reducing_amount'] = get_converted_currency_value($currency_obj_r->force_currency_conversion($reducing_amount));
                        }
                    }

                    $this->template->view('flight/tbo/tbo_booking_page', $page_data);
                    break;

                case AMADEUS_FLIGHT_BOOKING_SOURCE:
                    // upate fare details
                    $quote_update = $this->fare_quote_booking($pre_booking_params, 'amadeus');

                    if ($quote_update['status'] == FAILURE_STATUS) {
                        redirect(base_url() . 'index.php/flight/exception?op=Remote IO error @ Session Expiry&notification=session');
                    } else {
                        $pre_booking_params = $quote_update['data'];
                        //Get Extra Services
                        //$extra_services = $this->get_extra_services($pre_booking_params);
                        $extra_services = array();
                        $extra_services['status'] = FAILURE_STATUS;
                        if ($extra_services['status'] == SUCCESS_STATUS) {
                            $page_data['extra_services'] = $extra_services['data'];
                        } else {
                            $page_data['extra_services'] = array();
                        }
                    }
                    $currency_obj = new Currency(array(
                        'module_type' => 'flight',
                        'from' => get_application_currency_preference(),
                        'to' => get_application_currency_preference()
                    ));
                    // Load View
                    $page_data['booking_source'] = $pre_booking_params['booking_source'];
                    $page_data['pre_booking_params']['default_currency'] = get_application_default_currency();
                    $page_data['iso_country_list'] = $this->db_cache_api->get_iso_country_code();

                    $page_data['country_list'] = $this->db_cache_api->get_iso_country_code();
                    foreach ($page_data['country_list'] as $key => $value) {
                        if ($value == $safe_search_data['data']['country']) {
                            $page_data['passnationality'] = $key;
                            $new_phone_code = $key;
                        }
                    }
                    $page_data['currency_obj'] = $currency_obj;
                    //Traveller Details
                    $page_data['traveller_details'] = $this->user_model->get_user_traveller_details();
                    //Extracting Segment Summary and Fare Details

                    $updated_flight_details = $pre_booking_params['token'];
                    $is_price_Changed = false;
                    $flight_details = array();
                    foreach ($updated_flight_details as $k => $v) {

                        $temp_flight_details = $this->flight_lib->extract_flight_segment_fare_details($v, $currency_obj, $search_id, $this->current_module);
                        unset($temp_flight_details[0]['BookingType']); //Not needed in Next page
                        $flight_details[$k] = $temp_flight_details[0];
                    }

                    //changes start for baggage
                    $baggage_arr = json_decode(base64_decode($pre_booking_params['baggage_arr']));
                    foreach ($flight_details[0]['SegmentDetails'] as $fl_k => $fl_v) {
                        foreach ($flight_details[0]['SegmentDetails'][$fl_k] as $k => $v) {
                            $flight_details[0]['SegmentDetails'][$fl_k][$k]['Baggage'] = $baggage_arr[$fl_k][$k]->Baggage;
                            $flight_details[0]['SegmentDetails'][$fl_k][$k]['CabinBaggage'] = $baggage_arr[$fl_k][$k]->CabinBaggage;
                        }
                    }
                    //changes end for baggage
                    //Merge the Segment Details and Fare Details For Printing Purpose
                    $flight_pre_booking_summary = $this->flight_lib->merge_flight_segment_fare_details($flight_details);
                    /// debug($flight_pre_booking_summary);die;
                    $pre_booking_params['token'] = $flight_details;
                    $page_data['pre_booking_params'] = $pre_booking_params;
                    $page_data['pre_booking_summery'] = $flight_pre_booking_summary;
                    $TotalPrice = $flight_pre_booking_summary['FareDetails'][$this->current_module . '_PriceDetails']['TotalFare'];

                    $page_data['convenience_fees'] = $currency_obj->convenience_fees($TotalPrice, $search_id);

                    $page_data['is_price_Changed'] = $is_price_Changed;

                    //Get the country phone code 
                    $Domain_record = $this->custom_db->single_table_records('domain_list', '*');

                    $page_data['active_data'] = $Domain_record['data'][0];
                    $temp_record = $this->custom_db->get_phone_code_list();

                    $page_data['phone_code'] = $temp_record;

                    if (!empty($this->entity_country_code)) {

                        $page_data['user_country_code'] = $this->entity_country_code;
                    } else {
                        $page_data['user_country_code'] = $Domain_record['data'][0]['phone_code'];
                    }
                    $convinence_fees_row = $this->private_management_model->get_convinence_fees('flight', $search_id);

                    $page_data['org_convience_fee'] = 0;
                    if (valid_array($convinence_fees_row)) {
                        if ($convinence_fees_row['type'] == 'percentage') {
                            $page_data['org_convience_fee'] = $convinence_fees_row['value'];
                        }
                    }
                    $page_data['convenience_fees_orginal'] = $convinence_fees_row;
                    $state_list = $this->custom_db->single_table_records('state_list', '*');
                    $state_list_array = array();
                    foreach ($state_list['data'] as $key => $state) {
                        $state_list_array[$state['en_name']] = $state['en_name'];
                    }
                    $page_data['state_list'] = $state_list_array;


                    //session expiry time calculation
                    $page_data['session_expiry_details'] = $this->flight_lib->set_flight_search_session_expiry(true, $search_hash);

                    //Pusing ExtraService details to pre_booking_params array()
                    $page_data['pre_booking_params']['extra_services'] = $extra_services;
                    $temp = $this->custom_db->single_table_records('insurance');

                    $page_data['insurance'] = $temp['data'][0];
                    /*#############################FOR REWARDS SYSTEM #########################################*/

                    $page_data['total_price'] = round($flight_pre_booking_summary['FareDetails']['api_PriceDetails']['BaseFare'] + $flight_pre_booking_summary['FareDetails']['api_PriceDetails']['Tax'], 2);
                    //debug($page_data ['total_price']);die;
                    $page_data['reward_usable'] = 0;
                    $page_data['reward_earned'] = 0;
                    $page_data['total_price_with_rewards'] = 0;
                    if (is_logged_in_user()) {
                        $user_id = $this->entity_user_id;
                        //$data_rewards = $this->transaction->user_reward_details($user_id);
                        $currency_obj_r = new Currency(array('module_type' => 'flight', 'from' => UNIVERSAL_DEFAULT_CURRENCY, 'to' => get_application_display_currency_preference()));

                        $reward_values = $this->rewards->find_reward(META_AIRLINE_COURSE, get_converted_currency_value($currency_obj_r->force_currency_conversion($page_data['total_price'])));
                        //debug($reward_values);die;
                        $reward_details = $this->rewards->get_reward_coversion_and_limit_details();
                        //   debug($reward_details);
                        //$usable_rewards = $reward_values['usable_reward'];
                        $usable_rewards = $this->rewards->usable_rewards($user_id, META_AIRLINE_COURSE, $reward_values['usable_reward']);
                        //  echo "test";
                        //   debug($usable_rewards);
                        $page_data['reward_earned'] = $reward_values['earning_reward'];


                        if ($reward_values['usable_reward'] <= $usable_rewards) {
                            $page_data['reward_usable'] = $reward_values['usable_reward'];
                        } else {
                            $page_data['reward_usable'] = 0;
                        }
                        //  debug($page_data['reward_usable']);exit();
                        if ($page_data['reward_usable']) {
                            $reducing_amount = $this->rewards->find_reward_amount($page_data['reward_usable']);

                            //  $reducing_amount = round($reducing_amount*$page_data['converted_currency_rate'],2);
                            $page_data['total_price_with_rewards'] = $page_data['total_price'] - get_converted_currency_value($currency_obj_r->force_currency_conversion($reducing_amount));
                            $page_data['reducing_amount'] = get_converted_currency_value($currency_obj_r->force_currency_conversion($reducing_amount));
                        }
                    }
                    $this->template->view('flight/tbo/amadeus_booking_page', $page_data);
                    break;
            }
        } else {
            redirect(base_url());
        }
    }

    /**
     * Fare Quote Booking 
     */
    private function fare_quote_booking($flight_booking_details, $api_name = 'tmx')
    {

        if($api_name != "amadeus"){
            $fare_quote_details = $this->flight_lib->fare_quote_details($flight_booking_details, 'b2c');
            }else{
                $fare_quote_details = $this->flight_lib->fare_quote_details($flight_booking_details, $flight_booking_details['safe_search_data']);
            } 
       
        if ($api_name == 'plazma') {
            $fare_quote_details['data']['token'][0] = unserialized_data($fare_quote_details['data']['search_access_key'][0]);
            // $fare_quote_details['data']['token'][1]=unserialized_data($fare_quote_details['data']['search_access_key'][1]);
        }
        // debug( $fare_quote_details);die;
        if ($fare_quote_details['status'] == SUCCESS_STATUS && valid_array($fare_quote_details) == true) {
            //Converting API currency data to preferred currency

            if ($api_name == 'amadeus') {
                $currency_obj = new Currency(array('module_type' => 'flight', 'from' => amadeus_get_api_data_currency(), 'to' => get_application_currency_preference()));
            } else {

                //$currency_obj = new Currency(array('module_type' => 'flight', 'from' => provab_get_api_data_currency(), 'to' => get_application_currency_preference()));  


                // added by avinash since the same amount is coming in npn no need to add
                $currency_obj = new Currency(array('module_type' => 'flight', 'from' => get_application_currency_preference(), 'to' => get_application_currency_preference()));
                // debug( $fare_quote_details);die;

            }
            if ($api_name == 'plazma') {
                $currency_obj = new Currency(array('module_type' => 'flight', 'from' => 'NPR', 'to' => get_application_currency_preference()));
            }
            $fare_quote_details = $this->flight_lib->farequote_data_in_preferred_currency($fare_quote_details, $currency_obj);
        }
        //debug($fare_quote_details);die;
        return $fare_quote_details;
    }

    /**
     * Get Extra Services
     */
    private function get_extra_services($flight_booking_details)
    {
        $extra_service_details = $this->flight_lib->get_extra_services($flight_booking_details);
        return $extra_service_details;
    }
    // changes create this function, the calculations here are moved from pre_booking()
    function pay_calculations($search_id, $post_params)
    {
        // Make sure token and temp token matches
        $valid_temp_token = unserialized_data($post_params['token'], $post_params['token_key']);
        if ($valid_temp_token != false) {

            load_flight_lib($post_params['booking_source']);
            $amount = 0;
            $currency = '';
            /*             * **Convert Display currency to Application default currency** */
            //After converting to default currency, storing in temp_booking
            $post_params['token'] = unserialized_data($post_params['token']);
            $gst = 0;
            $gst = @$post_params['token']['token'][0]['FareDetails']['b2c_PriceDetails']['_GST'];

            if ($post_params['booking_source'] == PROVAB_FLIGHT_BOOKING_SOURCE) {
                $currency_obj = new Currency(array(
                    'module_type' => 'flight',
                    'from' => get_application_currency_preference(),
                    'to' => admin_base_currency()
                ));
                $from_currency = $post_params['token']['token'][0]['FareDetails']['b2c_PriceDetails']['Currency'];
                $conv_currency_obj = new Currency(array(
                    'module_type' => 'flight',
                    'from' => get_application_currency_preference(),
                    'to' => admin_base_currency()
                ));
            } else {
                $from_currency = $post_params['token']['token'][0]['FareDetails']['b2c_PriceDetails']['Currency'];
                $currency_obj = new Currency(array(
                    'module_type' => 'flight',
                    'from' => $from_currency,
                    'to' => get_application_default_currency()
                ));

                $conv_currency_obj = new Currency(array(
                    'module_type' => 'flight',
                    'from' => get_application_default_currency(),
                    'to' => $from_currency
                ));
            }
            if ($post_params['booking_source'] == PLAZMA_BOOKING_SOURCE) {
                $currency_obj = new Currency(array(
                    'module_type' => 'plazmaflight',
                    'from' => 'NPR',
                    'to' => get_application_default_currency()
                ));
                $from_currency = $post_params['token']['token'][0]['FareDetails']['b2c_PriceDetails']['Currency'];
                $conv_currency_obj = new Currency(array(
                    'module_type' => 'plazmaflight',
                    'from' => get_application_default_currency(),
                    'to' => $from_currency
                ));
            }
            if ($currency_obj->conversion_cache[get_application_currency_preference() . get_application_default_currency()] != 1) {
                $conv_price_details = $post_params['token']['token'][0]['FareDetails']['api_PriceDetails'];
            }
            $post_params['token']['token'] = $this->flight_lib->convert_token_to_application_currency($post_params['token']['token'], $currency_obj, $this->current_module);
            if ($_SERVER["REMOTE_ADDR"] == "117.197.185.28") {
            }

            if ($currency_obj->conversion_cache[get_application_currency_preference() . get_application_default_currency()] != 1) {
                $post_params['token']['token'][0]['FareDetails']['conv_price_details'] = $conv_price_details;
            }
            //Convert to Extra Services to application currency
            if (isset($post_params['token']['extra_services']) == true) {
                $post_params['token']['extra_services'] = $this->flight_lib->convert_extra_services_to_application_currency($post_params['token']['extra_services'], $currency_obj);
            }

            $post_params['token'] = serialized_data($post_params['token']);

            //Reindex Passport Month
            // $post_params['passenger_passport_expiry_month'] = $this->flight_lib->reindex_passport_expiry_month($post_params['passenger_passport_expiry_month'], $search_id);
            //foreach loop for considering citizenship id as well
            foreach ($post_params['identification_type'] as $k => $v) {
                if ($v === 'citizenship') {
                    $post_params['passenger_passport_number'][$k] =  $post_params['passenger_citizenship_number'][$k];
                    $post_params['passenger_passport_issuing_country'][$k] =  $post_params['passenger_citizenship_issuing_country'][$k];
                    $post_params['passenger_passport_expiry_year'][$k] =  $post_params['passenger_citizenship_expiry_year'][$k];
                    $post_params['passenger_passport_expiry_month'][$k] =  $post_params['passenger_citizenship_expiry_month'][$k];
                    $post_params['passenger_passport_expiry_day'][$k] =  $post_params['passenger_citizenship_expiry_day'][$k];
                }
            }

            $temp_booking = $this->module_model->serialize_temp_booking_record($post_params, FLIGHT_BOOKING);
            $book_id = $temp_booking['book_id'];
            $book_origin = $temp_booking['temp_booking_origin'];

            if ($post_params['booking_source'] == PROVAB_FLIGHT_BOOKING_SOURCE || $post_params['booking_source'] == PROVAB_AEROCRS_BOOKING_SOURCE || $post_params['booking_source'] == PLAZMA_BOOKING_SOURCE || $post_params['booking_source'] == AMADEUS_FLIGHT_BOOKING_SOURCE) {

                if ($post_params['booking_source'] == PROVAB_FLIGHT_BOOKING_SOURCE) {
                    $currency_obj = new Currency(array(
                        'module_type' => 'flight',
                        'from' => get_application_currency_preference(),
                        'to' => admin_base_currency()
                    ));
                    $pg_currency_obj = new Currency(array(
                        'module_type' => 'flight',
                        'from' => get_application_currency_preference(),
                        'to' => admin_base_currency()
                    ));

                    $pg_currency_conversion_rate = $pg_currency_obj->conversion_cache[get_application_currency_preference() . admin_base_currency()];
                } else {
                    $currency_obj = new Currency(array(
                        'module_type' => 'flight',
                        'from' => amadeus_get_api_data_currency(),
                        'to' => admin_base_currency()
                    ));

                    $pg_currency_obj = new Currency(array(
                        'module_type' => 'flight',
                        'from' => get_application_currency_preference(),
                        'to' => admin_base_currency()
                    ));
                    $pg_currency_conversion_rate = $pg_currency_obj->conversion_cache[get_application_currency_preference() . admin_base_currency()];
                }
                if ($post_params['booking_source'] == PLAZMA_BOOKING_SOURCE) {
                    $currency_obj = new Currency(array(
                        'module_type' => 'plazmaflight',
                        'from' => 'NPR',
                        'to' => admin_base_currency()
                    ));
                    $pg_currency_obj = new Currency(array(
                        'module_type' => 'plazmaflight',
                        'from' => get_application_currency_preference(),
                        'to' => admin_base_currency()
                    ));

                    $pg_currency_conversion_rate = $pg_currency_obj->conversion_cache[get_application_currency_preference() . admin_base_currency()];
                }
                $temp_token = unserialized_data($post_params['token']);

                $total_seg_cnt = 0;
                foreach ($temp_token['token'][0]['SegmentDetails'] as $segk => $segv) {
                    $total_seg_cnt += count($segv);
                }
                if ($post_params['booking_source'] == AMADEUS_FLIGHT_BOOKING_SOURCE) {
                    $discount_details = discount_details();
                }

                $discount = array();
                $segment_discount = 0;
                if ($discount_details['status'] == SUCCESS_STATUS) {
                    $discount = $discount_details['data'];
                    $segment_discount = $total_seg_cnt * $discount['value'];
                    $ori_segment_discount = $total_seg_cnt * $discount['original_value'];
                }

                $flight_details = $temp_token['token'];
                $flight_booking_summary = $this->flight_lib->merge_flight_segment_fare_details($flight_details);
                $fare_details = $flight_booking_summary['FareDetails'][$this->current_module . '_PriceDetails'];
                $api_price_details = $flight_booking_summary['FareDetails']['api_PriceDetails'];
                $amount = $post_params['total_amount_val'] - $segment_discount;
                $currency = $fare_details['CurrencySymbol'];
            }

            $promocode_discount = $post_params['promo_code_discount_val'];
            /*             * ******* Promocode End ******* */

            $email = $post_params['billing_email'];
            $phone = $post_params['passenger_contact'];
            $firstname = $post_params['first_name']['0'] . " " . $post_params['last_name']['0'];
            $book_id = $book_id;
            $productinfo = META_AIRLINE_COURSE;

            //Save the Booking Data
            $booking_data = $this->module_model->unserialize_temp_booking_record($book_id, $book_origin);
            $book_params = $booking_data['book_attributes'];
            $book_params['segment_discount'] = $segment_discount;
            $book_params['ori_segment_discount'] = $ori_segment_discount;

            if ($post_params['booking_source'] == PROVAB_FLIGHT_BOOKING_SOURCE) {
            }
            $data = $this->flight_lib->save_booking($book_id, $book_params, $currency_obj, $this->current_module);
            //Add Extra Service Price to Booking Amount
            $extra_services_total_price = $this->flight_model->get_extra_services_total_price($book_id);
            $amount += $extra_services_total_price;

            $convenience_fees = $conv_currency_obj->convenience_fees($amount, $search_id);

            $gst_conv_fee = 0;
            if ($convenience_fees > 0) {
                $gst_details = $GLOBALS['CI']->custom_db->single_table_records('gst_master', '*', array('module' => 'flight'));

                if ($gst_details['status'] == true) {
                    if ($gst_details['data'][0]['gst'] > 0) {

                        $gst_conv_fee = ($convenience_fees / 100) * $gst_details['data'][0]['gst'];
                    }
                }
            }
            $amount += $gst_conv_fee;
            # Add GST Amount on payment gateway
            # Get Insurance Amount
            $temp = $this->custom_db->single_table_records('insurance');

            $insurance_amount = 0;

            $reward_point = 0;
            $reward_amount = 0;
            $reward_discount = 0;
            if ($post_params['redeem_points_post'] == "1") {
                $reward_discount = $post_params['reducing_amount'];
                $reward_amount = $post_params['reducing_amount'];
                $reward_point = $post_params['reward_usable'];
            }

            if ($reward_discount >= 1) {
                $amount = number_format(($amount - $reward_discount), 2);
            }
            $reward_earned = $post_params['reward_earned'];
            // change 1 for session expiry added following line and added new fields session_start_time, enable_session_expiry, session_module in the new data array
            // $session_expiry_details = $this->flight_lib->set_flight_search_session_expiry(true, $post_params['search_hash']);
            $new_data = [
                'convenience_fees' => $convenience_fees,
                'book_id' => $book_id,
                'book_origin' => $book_origin,
                'firstname' => $firstname,
                'email' => $email,
                'phone' => $phone,
                'productinfo' => $productinfo,
                'promocode_discount' => $promocode_discount,
                'pg_currency_conversion_rate' => $pg_currency_conversion_rate,
                'reward_point' => $reward_point,
                'reward_amount' => $reward_amount,
                'reward_earned' => $reward_earned,
                // 'session_start_time'=>$session_expiry_details['session_start_time'],
                // 'enable_session_expiry' => true,
                // 'session_module' => 'flight'
            ];
            $post_params = array_merge($post_params, $new_data);
            // debug($post_params);
            return $post_params;
        }
    }

    // changes: create this function
    function make_secure_payment($search_id)
    {
        $post_params = $this->input->post();
        // debug($post_params);
        if (valid_array($post_params) == false) {
            redirect(base_url());
        }
        $post_params = $this->pay_calculations($search_id, $post_params);
        // debug($post_params);die;
        $post_params_array['post_params'] = $post_params;
        $this->template->view('flight/tbo/make_secure_payment', $post_params_array);
    }
    //changed the pre_booking to pre_booking_changed
    // changes: modifications to this function, compare with previous one
    function pre_booking($search_id)
    {
        $post_params_input = $this->input->post();
        $post_params = (array)json_decode($post_params_input['proceed-form-data']);
        if (valid_array($post_params) == false) {
            redirect(base_url());
        }
        $convenience_fees = $post_params['convenience_fees'];
        $book_id = $post_params['book_id'];
        $book_origin = $post_params['book_origin'];
        $firstname = $post_params['firstname'];
        $email = $post_params['email'];
        $phone = $post_params['phone'];
        $productinfo = $post_params['productinfo'];
        $promocode_discount = $post_params['promocode_discount'];
        $pg_currency_conversion_rate = $post_params['pg_currency_conversion_rate'];
        $reward_point = $post_params['reward_point'];
        $reward_amount = $post_params['reward_amount'];
        $reward_earned = $post_params['reward_earned'];
        //changes start for convenience fee
        $pgConvFee = $post_params['pg_convenience'];
        $data['status_description'] = "$pgConvFee";
        $this->custom_db->update_record("flight_booking_transaction_details", $data, array("app_reference" => $book_id));
        //changes end for convenience fee
        // debug($post_params);die;
        switch ($post_params['payment_method']) {
            case PAY_NOW:
                $this->load->model('transaction');
                if ($post_params['booking_source'] == PROVAB_FLIGHT_BOOKING_SOURCE || $post_params['booking_source'] == PLAZMA_BOOKING_SOURCE) {
                    $amount = $post_params['total_amount_val'];
                    $convenience_fees = $post_params['convenience_fee'];
                    // debug($convenience_fees);die;
                } else {
                    $amount = $post_params['total_amount_val'] - $convenience_fees;
                    // debug($convenience_fees);die;
                }
                $this->transaction->create_payment_record($book_id, $amount, $firstname, $email, $phone, $productinfo, $convenience_fees, $promocode_discount, $pg_currency_conversion_rate, $reward_point, $reward_amount, $reward_earned);

                redirect(base_url() . 'index.php/payment_gateway/payment/' . $book_id . '/' . $book_origin . '/fonepay');
                break;
            case PAY_AT_BANK:
                $this->load->model('transaction');
                if ($post_params['booking_source'] == PROVAB_FLIGHT_BOOKING_SOURCE || $post_params['booking_source'] == PLAZMA_BOOKING_SOURCE) {
                    $amount = $post_params['total_amount_val'];
                    $convenience_fees = $post_params['convenience_fee'];
                } else {
                    $amount = $post_params['total_amount_val'] - $convenience_fees;
                }
                $this->transaction->create_payment_record($book_id, $amount, $firstname, $email, $phone, $productinfo, $convenience_fees, $promocode_discount, $pg_currency_conversion_rate, $reward_point, $reward_amount, $reward_earned);

                redirect(base_url() . 'index.php/payment_gateway/payment/' . $book_id . '/' . $book_origin . '/connect');
                break;
            case PAY_WITH_ESEWA:
                $this->load->model('transaction');
                if ($post_params['booking_source'] == PROVAB_FLIGHT_BOOKING_SOURCE || $post_params['booking_source'] == PLAZMA_BOOKING_SOURCE) {
                    $amount = $post_params['total_amount_val'];
                    $convenience_fees = $post_params['convenience_fee'];
                } else {
                    $amount = $post_params['total_amount_val'] - $convenience_fees;
                }
                $this->transaction->create_payment_record($book_id, $amount, $firstname, $email, $phone, $productinfo, $convenience_fees, $promocode_discount, $pg_currency_conversion_rate, $reward_point, $reward_amount, $reward_earned);
                redirect(base_url() . 'index.php/payment_gateway/payment/' . $book_id . '/' . $book_origin . '/esewa');
                break;
            case PAY_WITH_KHALTI:
                $this->load->model('transaction');
                if ($post_params['booking_source'] == PROVAB_FLIGHT_BOOKING_SOURCE || $post_params['booking_source'] == PLAZMA_BOOKING_SOURCE) {
                    $amount = $post_params['total_amount_val'];
                    $convenience_fees = $post_params['convenience_fee'];
                } else {
                    $amount = $post_params['total_amount_val'] - $convenience_fees;
                }
                $this->transaction->create_payment_record($book_id, $amount, $firstname, $email, $phone, $productinfo, $convenience_fees, $promocode_discount, $pg_currency_conversion_rate, $reward_point, $reward_amount, $reward_earned);
                redirect(base_url() . 'index.php/payment_gateway/payment/' . $book_id . '/' . $book_origin . '/khalti');
                break;

                //changes added a new case for nic asia
            case PAY_WITH_NICA:
                $this->load->model('transaction');
                if ($post_params['booking_source'] == PROVAB_FLIGHT_BOOKING_SOURCE || $post_params['booking_source'] == PLAZMA_BOOKING_SOURCE) {
                    $amount = $post_params['total_amount_val'];
                    $convenience_fees = $post_params['convenience_fee'];
                } else {
                    $amount = $post_params['total_amount_val'] - $convenience_fees;
                }
                $this->transaction->create_payment_record($book_id, $amount, $firstname, $email, $phone, $productinfo, $convenience_fees, $promocode_discount, $pg_currency_conversion_rate, $reward_point, $reward_amount, $reward_earned);
                redirect(base_url() . 'index.php/payment_gateway/payment/' . $book_id . '/' . $book_origin . '/nica');
                break;
        }
        redirect(base_url() . 'index.php/flight/exception?op=Remote IO error @ FLIGHT Booking&notification=validation');
    }

    /**
     * Balu A
     * Secure Booking of FLIGHT
     * Process booking no view page
     */
    function pre_booking_changed($search_id)
    {

        $post_params = $this->input->post();
        //debug($post_params);DIE;
        if (valid_array($post_params) == false) {
            redirect(base_url());
        }

        // Make sure token and temp token matches
        $valid_temp_token = unserialized_data($post_params['token'], $post_params['token_key']);

        if ($valid_temp_token != false) {

            load_flight_lib($post_params['booking_source']);
            $amount = 0;
            $currency = '';
            /*             * **Convert Display currency to Application default currency** */
            //After converting to default currency, storing in temp_booking
            $post_params['token'] = unserialized_data($post_params['token']);

            $gst = 0;

            $gst = @$post_params['token']['token'][0]['FareDetails']['b2c_PriceDetails']['_GST'];

            if ($post_params['booking_source'] == PROVAB_FLIGHT_BOOKING_SOURCE) {
                $currency_obj = new Currency(array(
                    'module_type' => 'flight',
                    'from' => get_application_currency_preference(),
                    'to' => admin_base_currency()
                ));
                $from_currency = $post_params['token']['token'][0]['FareDetails']['b2c_PriceDetails']['Currency'];
                $conv_currency_obj = new Currency(array(
                    'module_type' => 'flight',
                    'from' => get_application_currency_preference(),
                    'to' => admin_base_currency()
                ));
            } else {
                $from_currency = $post_params['token']['token'][0]['FareDetails']['b2c_PriceDetails']['Currency'];
                $currency_obj = new Currency(array(
                    'module_type' => 'flight',
                    'from' => $from_currency,
                    'to' => get_application_default_currency()
                ));

                $conv_currency_obj = new Currency(array(
                    'module_type' => 'flight',
                    'from' => get_application_default_currency(),
                    'to' => $from_currency
                ));
            }
            if ($post_params['booking_source'] == PLAZMA_BOOKING_SOURCE) {
                $currency_obj = new Currency(array(
                    'module_type' => 'plazmaflight',
                    'from' => 'NPR',
                    'to' => get_application_default_currency()
                ));
                $from_currency = $post_params['token']['token'][0]['FareDetails']['b2c_PriceDetails']['Currency'];
                $conv_currency_obj = new Currency(array(
                    'module_type' => 'plazmaflight',
                    'from' => get_application_default_currency(),
                    'to' => $from_currency
                ));
            }
            if ($currency_obj->conversion_cache[get_application_currency_preference() . get_application_default_currency()] != 1) {
                $conv_price_details = $post_params['token']['token'][0]['FareDetails']['api_PriceDetails'];
            }
            $post_params['token']['token'] = $this->flight_lib->convert_token_to_application_currency($post_params['token']['token'], $currency_obj, $this->current_module);
            if ($_SERVER["REMOTE_ADDR"] == "117.197.185.28") {
                // debug($currency_obj);
                //  debug( $post_params['token']['token'] );die;
            }

            if ($currency_obj->conversion_cache[get_application_currency_preference() . get_application_default_currency()] != 1) {
                $post_params['token']['token'][0]['FareDetails']['conv_price_details'] = $conv_price_details;
            }
            //Convert to Extra Services to application currency
            if (isset($post_params['token']['extra_services']) == true) {
                $post_params['token']['extra_services'] = $this->flight_lib->convert_extra_services_to_application_currency($post_params['token']['extra_services'], $currency_obj);
            }

            $post_params['token'] = serialized_data($post_params['token']);

            //Reindex Passport Month
            $post_params['passenger_passport_expiry_month'] = $this->flight_lib->reindex_passport_expiry_month($post_params['passenger_passport_expiry_month'], $search_id);

            $temp_booking = $this->module_model->serialize_temp_booking_record($post_params, FLIGHT_BOOKING);
            $book_id = $temp_booking['book_id'];
            $book_origin = $temp_booking['temp_booking_origin'];

            if ($post_params['booking_source'] == PROVAB_FLIGHT_BOOKING_SOURCE || $post_params['booking_source'] == PROVAB_AEROCRS_BOOKING_SOURCE || $post_params['booking_source'] == PLAZMA_BOOKING_SOURCE || $post_params['booking_source'] == AMADEUS_FLIGHT_BOOKING_SOURCE) {

                if ($post_params['booking_source'] == PROVAB_FLIGHT_BOOKING_SOURCE) {
                    $currency_obj = new Currency(array(
                        'module_type' => 'flight',
                        'from' => get_application_currency_preference(),
                        'to' => admin_base_currency()
                    ));
                    $pg_currency_obj = new Currency(array(
                        'module_type' => 'flight',
                        'from' => get_application_currency_preference(),
                        'to' => admin_base_currency()
                    ));

                    $pg_currency_conversion_rate = $pg_currency_obj->conversion_cache[get_application_currency_preference() . admin_base_currency()];
                } else {
                    $currency_obj = new Currency(array(
                        'module_type' => 'flight',
                        'from' => amadeus_get_api_data_currency(),
                        'to' => admin_base_currency()
                    ));

                    $pg_currency_obj = new Currency(array(
                        'module_type' => 'flight',
                        'from' => get_application_currency_preference(),
                        'to' => admin_base_currency()
                    ));
                    $pg_currency_conversion_rate = $pg_currency_obj->conversion_cache[get_application_currency_preference() . admin_base_currency()];
                }
                if ($post_params['booking_source'] == PLAZMA_BOOKING_SOURCE) {
                    $currency_obj = new Currency(array(
                        'module_type' => 'plazmaflight',
                        'from' => 'NPR',
                        'to' => admin_base_currency()
                    ));
                    $pg_currency_obj = new Currency(array(
                        'module_type' => 'plazmaflight',
                        'from' => get_application_currency_preference(),
                        'to' => admin_base_currency()
                    ));

                    $pg_currency_conversion_rate = $pg_currency_obj->conversion_cache[get_application_currency_preference() . admin_base_currency()];
                }
                $temp_token = unserialized_data($post_params['token']);

                $total_seg_cnt = 0;
                foreach ($temp_token['token'][0]['SegmentDetails'] as $segk => $segv) {
                    $total_seg_cnt += count($segv);
                }
                if ($post_params['booking_source'] == AMADEUS_FLIGHT_BOOKING_SOURCE) {
                    $discount_details = discount_details();
                }

                $discount = array();
                $segment_discount = 0;
                if ($discount_details['status'] == SUCCESS_STATUS) {
                    $discount = $discount_details['data'];
                    $segment_discount = $total_seg_cnt * $discount['value'];
                    $ori_segment_discount = $total_seg_cnt * $discount['original_value'];
                }

                $flight_details = $temp_token['token'];
                $flight_booking_summary = $this->flight_lib->merge_flight_segment_fare_details($flight_details);
                $fare_details = $flight_booking_summary['FareDetails'][$this->current_module . '_PriceDetails'];
                //    debug($fare_details );
                $api_price_details = $flight_booking_summary['FareDetails']['api_PriceDetails'];

                //$amount = $post_params['total_amount_val'] - $segment_discount;
                $amount = $post_params['total_amount_val'] - $segment_discount;

                $currency = $fare_details['CurrencySymbol'];
            }

            $promocode_discount = $post_params['promo_code_discount_val'];
            /*             * ******* Promocode End ******* */

            $email = $post_params['billing_email'];
            $phone = $post_params['passenger_contact'];
            $firstname = $post_params['first_name']['0'] . " " . $post_params['last_name']['0'];
            $book_id = $book_id;
            $productinfo = META_AIRLINE_COURSE;

            //Save the Booking Data
            $booking_data = $this->module_model->unserialize_temp_booking_record($book_id, $book_origin);
            $book_params = $booking_data['book_attributes'];
            $book_params['segment_discount'] = $segment_discount;
            $book_params['ori_segment_discount'] = $ori_segment_discount;

            if ($post_params['booking_source'] == PROVAB_FLIGHT_BOOKING_SOURCE) {
                /*      $save_currency_obj = new Currency(array(
                    'module_type' => 'flight',
                    'from' =>'NPR',
                    'to' => 'NPR'
                    ));
$currency_obj=$save_currency_obj;*/
            }
            $data = $this->flight_lib->save_booking($book_id, $book_params, $currency_obj, $this->current_module);

            //Add Extra Service Price to Booking Amount
            $extra_services_total_price = $this->flight_model->get_extra_services_total_price($book_id);
            $amount += $extra_services_total_price;

            $convenience_fees = $conv_currency_obj->convenience_fees($amount, $search_id);

            $gst_conv_fee = 0;
            if ($convenience_fees > 0) {
                $gst_details = $GLOBALS['CI']->custom_db->single_table_records('gst_master', '*', array('module' => 'flight'));

                if ($gst_details['status'] == true) {
                    if ($gst_details['data'][0]['gst'] > 0) {

                        $gst_conv_fee = ($convenience_fees / 100) * $gst_details['data'][0]['gst'];
                    }
                }
            }
            $amount += $gst_conv_fee;

            # Add GST Amount on payment gateway

            # Get Insurance Amount
            $temp = $this->custom_db->single_table_records('insurance');

            $insurance_amount = 0;

            $reward_point = 0;
            $reward_amount = 0;
            $reward_discount = 0;
            if ($post_params['redeem_points_post'] == "1") {
                $reward_discount = $post_params['reducing_amount'];
                $reward_amount = $post_params['reducing_amount'];
                $reward_point = $post_params['reward_usable'];
            }

            if ($reward_discount >= 1) {
                //  debug($reward_discount);die;
                // debug($amount);
                //exit;
                // debug($amount);die;
                $amount    = number_format(($amount - $reward_discount), 2);
            }

            //     debug( $segment_discount);
            //debug($promocode_discount);
            //debug( $amount);die;
            $reward_earned = $post_params['reward_earned'];
            //  debug($post_params);die;
            switch ($post_params['payment_method']) {
                case PAY_NOW:
                    $this->load->model('transaction');
                    //  DEBUG($post_params ['booking_source']);DIE;
                    if ($post_params['booking_source'] == PROVAB_FLIGHT_BOOKING_SOURCE || $post_params['booking_source'] == PLAZMA_BOOKING_SOURCE) {
                        //debug($post_params);

                        $amount = $post_params['total_amount_val'];
                        $convenience_fees = $post_params['convenience_fee'];
                        // debug($post_params['convenience_fee']);die;
                        /* if( $post_params ['booking_source'] == PLAZMA_BOOKING_SOURCE)
                       {
                         $amount=$post_params['total_amount_val']+$post_params['convenience_fee'];
                         debug($amount);die;
                       }*/
                        //debug($promocode_discount);

                    } else {
                        //$amount=$amount-$convenience_fees;

                        //   $amount=$amount-$convenience_fees+$segment_discount;
                        $amount = $post_params['total_amount_val'] - $convenience_fees;;
                    }
                    //   debug($post_params);
                    //    debug($amount);
                    //debug($convenience_fees);
                    //  die;
                    //$pg_currency_conversion_rate = $currency_obj->payment_gateway_currency_conversion_rate();

                    $this->transaction->create_payment_record($book_id, $amount, $firstname, $email, $phone, $productinfo, $convenience_fees, $promocode_discount, $pg_currency_conversion_rate, $reward_point, $reward_amount, $reward_earned);

                    redirect(base_url() . 'index.php/payment_gateway/payment/' . $book_id . '/' . $book_origin . '/fonepay');

                    //  redirect(base_url().'index.php/general/newpage');


                    break;
                case PAY_AT_BANK:
                    $this->load->model('transaction');
                    //  DEBUG($post_params ['booking_source']);DIE;
                    if ($post_params['booking_source'] == PROVAB_FLIGHT_BOOKING_SOURCE || $post_params['booking_source'] == PLAZMA_BOOKING_SOURCE) {
                        //debug($post_params);

                        $amount = $post_params['total_amount_val'];
                        $convenience_fees = $post_params['convenience_fee'];
                        // debug($post_params['convenience_fee']);die;
                        /* if( $post_params ['booking_source'] == PLAZMA_BOOKING_SOURCE)
                       {
                         $amount=$post_params['total_amount_val']+$post_params['convenience_fee'];
                         debug($amount);die;
                       }*/
                        //debug($promocode_discount);

                    } else {
                        //$amount=$amount-$convenience_fees;

                        //   $amount=$amount-$convenience_fees+$segment_discount;
                        $amount = $post_params['total_amount_val'] - $convenience_fees;;
                    }
                    //   debug($post_params);
                    //    debug($amount);
                    //debug($convenience_fees);
                    //  die;
                    //$pg_currency_conversion_rate = $currency_obj->payment_gateway_currency_conversion_rate();

                    $this->transaction->create_payment_record($book_id, $amount, $firstname, $email, $phone, $productinfo, $convenience_fees, $promocode_discount, $pg_currency_conversion_rate, $reward_point, $reward_amount, $reward_earned);

                    redirect(base_url() . 'index.php/payment_gateway/payment/' . $book_id . '/' . $book_origin . '/connect');

                    //redirect(base_url().'index.php/general/newpage');


                    break;
            }
        }
        redirect(base_url() . 'index.php/flight/exception?op=Remote IO error @ FLIGHT Booking&notification=validation');
    }

    /* review page */

    public function review_passengers($app_reference = '', $book_origin = '')
    {
        $page_data['app_reference'] = $app_reference;
        $page_data['book_origin'] = $book_origin;

        $this->load->model('flight_model');
        $this->load->library('booking_data_formatter');
        if (empty($app_reference) == false) {

            $booking_details = $this->flight_model->get_booking_details($app_reference, $booking_source, $booking_status);
            if ($booking_details['status'] == SUCCESS_STATUS) {
                load_flight_lib(PROVAB_FLIGHT_BOOKING_SOURCE);
                $assembled_booking_details = $this->booking_data_formatter->format_flight_booking_data($booking_details, 'b2c');

                $page_data['data'] = $assembled_booking_details['data'];
                $address = json_decode($booking_details['data']['booking_details']['0']['attributes'], true);
                $page_data['data']['address'] = $address['address'];

                $page_data['data']['logo'] = $assembled_booking_details['data']['booking_details']['0']['domain_logo'];
                $page_data['data']['email'] = $booking_details['data']['booking_details']['0']['email'];
                $page_data['country_list'] = $this->db_cache_api->get_iso_country_code();
                if (!empty($this->entity_country_code)) {
                    $page_data['user_country_code'] = $this->entity_country_code;
                } else {
                    $page_data['user_country_code'] = '92';
                }
                $page_data['phone_code'] = $this->custom_db->get_phone_code_list();
                $this->template->view('flight/review_passangers_details', $page_data);
            }
        }
    }

    function edit_pax()
    {
        $params = $this->input->post();

        if (count($params)) {
            $id = $params["origin"];
            $app_reference = $params["app_reference"];
            if (!$params['is_domestic']) {
                $passport_issuing_country = $GLOBALS['CI']->db_cache_api->get_country_list(array('k' => 'origin', 'v' => 'name'), array('origin' => $params['passenger_passport_issuing_country']));
                $params['passport_issuing_country'] = $passport_issuing_country[$params['passenger_passport_issuing_country']];
                $expiry_date = $params["date"][0] . "-" . $params["date"][1] . "-" . $params["date"][2];
                $params["passport_expiry_date"] = $expiry_date;
                $update_data['passport_expiry_date'] = $expiry_date;
                $update_data['passport_number'] = $params['passport_number'];
            }
            $update_data['origin'] = $params['origin'];
            $update_data['app_reference'] = $params['app_reference'];
            $update_data['first_name'] = $params['first_name'];
            $update_data['last_name'] = $params['last_name'];
            $update_data['date_of_birth'] = $params['date_of_birth'];


            $this->flight_model->update_pax_details($update_data, $id);
            redirect("flight/review_passengers/" . $app_reference);
        }
    }

    function edit_booking_details()
    {
        $params = $this->input->post();
        if (count($params)) {
            $id = $params["origin"];
            $app_reference = $params["app_reference"];
            $update_data["email"] = $params["email"];
            $update_data["phone"] = $params["phone"];
            $this->flight_model->update_booking_details($update_data, $id);
            redirect("flight/review_passengers/" . $app_reference);
        }
    }

    /*
      process booking in backend until show loader
     */

    function process_booking($book_id, $temp_book_origin, $validation_id, $r_amt)
    {

        /* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(0);*/
        // debug("Reached");die;

        $query_for_validation  = $GLOBALS['CI']->custom_db->single_table_records("payment_gateway_details", '*', array("app_reference" => $book_id));
        // debug($query_for_validation);die;

        if ($query_for_validation['data'][0]['payment_mode'] == 'connect'  || $query_for_validation['data'][0]['payment_mode'] == 'khalti') {
            // please note here multiplying by 100 since connect ips takes money in paisa  for validation process else transaction will decline
            $query_for_validation['data'][0]['amount'] = $query_for_validation['data'][0]['amount'] * 100;
        } else {
            $query_for_validation['data'][0]['amount'] = $query_for_validation['data'][0]['amount'];
        }

        if ($query_for_validation['data'][0]["payment_validation"] == $validation_id && $query_for_validation['data'][0]["amount"] == $r_amt) { //yaa changes cha
            //echo "test here";die;
            if ($book_id != '' && $temp_book_origin != '' && intval($temp_book_origin) > 0) {

                $page_data['form_url'] = base_url() . 'index.php/flight/secure_booking';
                $page_data['form_method'] = 'POST';
                $page_data['form_params']['book_id'] = $book_id;
                $page_data['form_params']['temp_book_origin'] = $temp_book_origin;
                $page_data['form_params']['validation_id'] = $validation_id;
                $page_data['form_params']['request_amount'] = $r_amt;
                $this->template->view('share/loader/booking_process_loader', $page_data);
            }
        } else {
            //echo "need to check ";die;
            redirect(base_url() . 'index.php/flight/exception?op=Invalid request&notification=validation');
        }
    }

    /**
     * Balu A
     * Do booking once payment is successfull - Payment Gateway
     * and issue voucher
     */
    function secure_booking()
    {

        // echo "BOOKING BLOCKED BY ADMIN PLEASE CONTACT ADMIN FOR BOOKING ..";exit;
        $post_data = $this->input->post();

        if (
            valid_array($post_data) == true && isset($post_data['book_id']) == true && isset($post_data['temp_book_origin']) == true &&
            empty($post_data['book_id']) == false && intval($post_data['temp_book_origin']) > 0
        ) {
            //verify payment status and continue
            $book_id = trim($post_data['book_id']);
            $temp_book_origin = intval($post_data['temp_book_origin']);
            $this->load->model('transaction');

            $booking_status = $this->transaction->get_payment_status($book_id);

            // if ($booking_status['status'] != 'accepted') {
            //  redirect(base_url() . 'index.php/flight/exception?op=Payment Failed&notification=Payment Failed');
            // }
        } else {
            redirect(base_url() . 'index.php/flight/exception?op=InvalidBooking&notification=invalid');
        }
        //die("coming herere");
        //run booking request and do booking
        $temp_booking = $this->module_model->unserialize_temp_booking_record($book_id, $temp_book_origin);
        $this->module_model->delete_temp_booking_record($book_id, $temp_book_origin);

        load_trawelltag_lib(PROVAB_INSURANCE_BOOKING_SOURCE);

        load_flight_lib($temp_booking['booking_source']);
        if ($temp_booking['booking_source'] == PROVAB_FLIGHT_BOOKING_SOURCE  || $temp_booking['booking_source'] == PROVAB_AEROCRS_BOOKING_SOURCE || $temp_booking['booking_source'] == PLAZMA_BOOKING_SOURCE || $temp_booking['booking_source'] == AMADEUS_FLIGHT_BOOKING_SOURCE) {
            $currency_obj = new Currency(array(
                'module_type' => 'flight',
                'from' => admin_base_currency(),
                'to' => admin_base_currency()
            ));
            $flight_details = $temp_booking['book_attributes']['token']['token'];
            $flight_booking_summary = $this->flight_lib->merge_flight_segment_fare_details($flight_details);
            $fare_details = $flight_booking_summary['FareDetails'][$this->current_module . '_PriceDetails'];
            $currency = $fare_details['Currency'];
        }

        // verify payment status and continue
        $query_for_validation  = $GLOBALS['CI']->custom_db->single_table_records("payment_gateway_details", '*', array("app_reference" => $post_data['book_id']));
        if ($query_for_validation['data'][0]['payment_mode'] == 'connect'  || $query_for_validation['data'][0]['payment_mode'] == 'khalti') {
            // please note here multiplying by 100 since connect ips takes money in paisa  for validation process else transaction will decline
            $query_for_validation['data'][0]['amount'] = $query_for_validation['data'][0]['amount'] * 100;
        } else {
            $query_for_validation['data'][0]['amount'] = $query_for_validation['data'][0]['amount'];
        }

        if ($query_for_validation['data'][0]['payment_validation'] == $post_data['validation_id'] && $query_for_validation['data'][0]['amount'] == $post_data['request_amount']) { // yaa changes cha

            switch ($temp_booking['booking_source']) {
                case PROVAB_FLIGHT_BOOKING_SOURCE:
                case PROVAB_AEROCRS_BOOKING_SOURCE:
                case PLAZMA_BOOKING_SOURCE:
                case AMADEUS_FLIGHT_BOOKING_SOURCE:
                    if ($temp_booking['booking_source'] != PLAZMA_BOOKING_SOURCE) {
                        try {
                            $booking = $this->flight_lib->process_booking($book_id, $temp_booking['book_attributes']);
                        } catch (Exception $e) {
                            $booking['status'] = BOOKING_ERROR;
                        }
                    } else {
                        $booking = $this->flight_lib->process_booking($book_id, $temp_booking['book_attributes']);
                        // debug($booking);die;
                    }
                    // Save booking based on booking status and book id
                    break;
            }
            if (in_array($booking['status'], array(SUCCESS_STATUS, BOOKING_CONFIRMED, BOOKING_PENDING, BOOKING_FAILED, BOOKING_ERROR, BOOKING_HOLD, FAILURE_STATUS)) == true) {
                $currency_obj = new Currency(array(
                    'module_type' => 'flight',
                    'from' => provab_get_api_data_currency(),
                    'to' => admin_base_currency()
                ));
                //////////Rewards point update///////////////
                //debug($temp_booking);die;

                ///////////End rewards update////////////////
                $booking['data']['booking_params']['currency_obj'] = $currency_obj;
                //Update the booking Details
                $ticket_details = @$booking['data']['ticket'];
                $ticket_details['master_booking_status'] = $booking['status'];
                if (isset($booking['data']['booking_params']['promo_code']) && $booking['status'] == SUCCESS_STATUS) {

                    $condition['promo_code'] = $booking['data']['booking_params']['promo_code'];
                    $condition['status'] = 1;
                    $promo_code_res = $this->custom_db->single_table_records('promo_code_list', '*', $condition);
                    $promo_code = $promo_code_res['data'][0];
                    $this->custom_db->update_record('promo_code_list', array('used_limit' => $promo_code['used_limit'] + 1), array('origin' => $promo_code['origin']));

                    /* if($promo_code['used_limit']+1 == 10)
                    
 {
                  
    $this->custom_db->update_record('promo_code_list',array('status'=>0),array('origin'=>$promo_code['origin']));
 }*/
                    // $this->custom_db->update_record('promo_code_list',array('status'=>0),array('origin'=>$promo_code['origin']));
                }
                $data = $this->flight_lib->update_booking_details($book_id, $booking['data']['booking_params'], $ticket_details, $this->current_module);

                //Update Transaction Details
                $this->domain_management_model->update_transaction_details('flight', $book_id, $data['fare'], $data['admin_markup'], $data['agent_markup'], $data['convinence'], $data['discount'], $data['transaction_currency'], $data['currency_conversion_rate']);
                if (is_logged_in_user()) {
                    //changes commented following lines in this condition for rewards section

                    // if ($temp_booking['book_attributes']['redeem_points_post'] == 1) {
                    $this->rewards->update_after_booking($temp_booking, $book_id);
                    $this->rewards->update_flight_rewards_details($temp_booking, $book_id);
                    // } else {
                    //     $this->rewards->update_reward_earned_value($temp_booking, $book_id);
                    //     $this->rewards->update_earned_rewards_details($temp_booking, $book_id, "flight_booking_details");
                    // }
                }
                if (in_array($data['status'], array(
                    'BOOKING_CONFIRMED',
                    'BOOKING_PENDING',
                    'BOOKING_HOLD'
                ))) {

                    $this->session->set_userdata(array($book_id => '1'));

                    redirect(base_url() . 'index.php/voucher/flight/' . $book_id . '/' . $temp_booking['booking_source'] . '/' . $data['status'] . '/show_voucher');
                } else {
                    redirect(base_url() . 'index.php/flight/exception?op=booking_exception&notification=' . $booking['message']);
                }
            } else {
                redirect(base_url() . 'index.php/flight/exception?op=booking_exception&notification=' . $booking['message']);
            }
        } else {

            $ip_address = $_SERVER['REMOTE_ADDR'];
            $cond['app_reference'] = $post_data['booking_id'];
            $data['response_params'] = $ip_address;
            $this->custom_db->update_record('payment_gateway_details', $data, $cond);
            redirect(base_url() . 'index.php/hotel/exception?op=Remote IO error @ Insufficient&notification=validation');
        }
    }

    /**
     * Balu A
     * Process booking on hold - pay at bank
     * Issue Ticket Later
     */
    function booking_on_hold($book_id)
    {

        load_trawelltag_lib(PROVAB_INSURANCE_BOOKING_SOURCE);

        $response = array();
        $response = $this->trawelltag->create_policy($response);

        $response['app_reference'] = 'FB-4251-45782-24578';
        $response['travel_date'] = '2019-04-11';
        $save_details = $this->flight_model->save_insurance_details($response);
    }

    /**
     * Balu A
     */
    function pre_cancellation($app_reference, $booking_source)
    {
        if (empty($app_reference) == false && empty($booking_source) == false) {
            $page_data = array();
            $booking_details = $this->flight_model->get_booking_details($app_reference, $booking_source);
            if ($booking_details['status'] == SUCCESS_STATUS) {
                $this->load->library('booking_data_formatter');
                //Assemble Booking Data
                $assembled_booking_details = $this->booking_data_formatter->format_flight_booking_data($booking_details, $this->current_module);
                $page_data['data'] = $assembled_booking_details['data'];
                $this->template->view('flight/pre_cancellation', $page_data);
            } else {
                redirect('security/log_event?event=Invalid Details');
            }
        } else {
            redirect('security/log_event?event=Invalid Details');
        }
    }

    /**
     * Balu A
     * @param $app_reference
     */
    function cancel_booking()
    {
        $post_data = $this->input->post();
        if (
            isset($post_data['app_reference']) == true && isset($post_data['booking_source']) == true && isset($post_data['transaction_origin']) == true &&
            valid_array($post_data['transaction_origin']) == true && isset($post_data['passenger_origin']) == true && valid_array($post_data['passenger_origin']) == true
        ) {
            $app_reference = trim($post_data['app_reference']);
            $booking_source = trim($post_data['booking_source']);
            $transaction_origin = $post_data['transaction_origin'];
            $passenger_origin = $post_data['passenger_origin'];

            $booking_details = $GLOBALS['CI']->flight_model->get_booking_details($app_reference, $booking_source);
            if ($booking_details['status'] == SUCCESS_STATUS) {
                load_flight_lib($booking_source);
                //Formatting the Data
                $this->load->library('booking_data_formatter');
                $booking_details = $this->booking_data_formatter->format_flight_booking_data($booking_details, $this->current_module);
                $booking_details = $booking_details['data'];
                //Grouping the Passenger Ticket Ids
                $grouped_passenger_ticket_details = $this->flight_lib->group_cancellation_passenger_ticket_id($booking_details, $passenger_origin);
                $passenger_origin = $grouped_passenger_ticket_details['passenger_origin'];
                $passenger_ticket_id = $grouped_passenger_ticket_details['passenger_ticket_id'];

                $cancellation_details = $this->flight_lib->cancel_booking($booking_details, $passenger_origin, $passenger_ticket_id);

                redirect('flight/cancellation_details/' . $app_reference . '/' . $booking_source . '/' . $cancellation_details['status']);
            } else {
                redirect('security/log_event?event=Invalid Details');
            }
        } else {
            redirect('security/log_event?event=Invalid Details');
        }
    }

    function cancellation_details($app_reference, $booking_source, $cancellation_status)
    {
        if (empty($app_reference) == false && empty($booking_source) == false) {
            $master_booking_details = $GLOBALS['CI']->flight_model->get_booking_details($app_reference, $booking_source);
            if ($master_booking_details['status'] == SUCCESS_STATUS) {
                $page_data = array();
                $this->load->library('booking_data_formatter');
                $master_booking_details = $this->booking_data_formatter->format_flight_booking_data($master_booking_details, $this->current_module);
                $page_data['data'] = $master_booking_details['data'];
                $page_data['cancellation_status'] = $cancellation_status;
                $this->template->view('flight/cancellation_details', $page_data);
            } else {
                redirect('security/log_event?event=Invalid Details');
            }
        } else {
            redirect('security/log_event?event=Invalid Details');
        }
    }

    /**
     * Balu A
     */
    function exception()
    {
        $module = META_AIRLINE_COURSE;
        $op = @$_GET['op'];
        $notification = @$_GET['notification'];

        if ($notification == 'Booking is already done for the same criteria for PNR') {
            $message = 'Please add another criteria and try again';
        } else if ($notification == 'SEAT NOT AVAILABLE' || $notification == 'seat no available') {
            $message = 'Please book another flight and try again';
        } else if ($notification == 'Sell Failure') {
            $message = 'Please try again for the same criteria';
        } else if ($notification == 'The requested class of service is sold out.') {
            $message = 'Please try another booking';
        } else if ($notification == 'Supplier Interaction Failed while adding Pax Details. Reason: 18|Presentation|Fusion DSC found an exception !\n\tThe data does not match the maximum length: \n\tFor data element: freetext\n\tData length should be at least 1 and at most 70\n\tCurrent position in buffer') {
            $message = 'Please add more than 2 characters in the name field and try agian';
        } else if ($notification == 'Agency do not have enough balance.') {
            $message = 'Please add balance and try again';
        } else if ($notification == 'Invalid CommitBooking Request') {
            $message = 'Session is Expired. Please try again';
        } else if ($notification == 'session') {
            $message = 'Session is Expired. Please try again';
        } else {
            $message = $notification . ' Please try again';
        }

        $exception = $this->module_model->flight_log_exception($module, $op, $message);

        $exception = base64_encode(json_encode($exception));

        // set ip log session before redirection
        $this->session->set_flashdata(array(
            'log_ip_info' => true
        ));
        redirect(base_url() . 'index.php/flight/event_logger/' . $exception);
    }

    function event_logger($exception = '')
    {
        $log_ip_info = $this->session->flashdata('log_ip_info');
        $this->template->view('flight/exception', array(
            'log_ip_info' => $log_ip_info,
            'exception' => $exception
        ));
    }

    function test_server()
    {
        $data = $this->custom_db->single_table_records('test', '*', array('origin' => 851));
        $response = json_decode($data['data'][0]['test'], true);
    }

    function mail_send_voucher($app_reference, $booking_source, $booking_status, $module)
    {
        send_email($app_reference, $booking_source, $booking_status, $module);
    }

    public function testuser()
    {
        $page_data['pax_details'] = $this->user_model->get_current_user_details();
        $i = 1;
        foreach ($page_data['pax_details'] as $key => $value) {
            if ($i != 1) {

                $this->db->where('user_id', $value['user_id']);
                $this->db->delete('user_profile_name');
            }
            $i++;
        }
    }

    function sendmail($app_reference = '', $booking_source = '', $booking_status = '')
    {

        //$app_reference='FB02-164658-457458';$booking_source='PTBSID0000000002';$booking_status='BOOKING_CONFIRMED';
        $this->load->library('provab_mailer');
        $this->load->model('flight_model');
        $this->load->library('booking_data_formatter');
        $booking_details = $this->flight_model->get_booking_details($app_reference, $booking_source, $booking_status);
        //debug($booking_details);die;
        if (in_array($booking['status'], array(SUCCESS_STATUS, BOOKING_CONFIRMED, BOOKING_PENDING, BOOKING_FAILED, BOOKING_ERROR, BOOKING_HOLD, FAILURE_STATUS, BOOKING_FAILED)) == true) {
            load_flight_lib(PROVAB_FLIGHT_BOOKING_SOURCE);
            $assembled_booking_details = $this->booking_data_formatter->format_flight_booking_data($booking_details, 'b2c');
            $page_data['data'] = $assembled_booking_details['data'];
            $address = json_decode($booking_details['data']['booking_details']['0']['attributes'], true);
            $page_data['data']['address'] = $address['address'];
            $page_data['data']['logo'] = $assembled_booking_details['data']['booking_details']['0']['domain_logo'];
            $email = $booking_details['data']['booking_details']['0']['email'];
            //$email = 'avinash2058.provab@gmail.com';
            $mail_template = $this->template->isolated_view('voucher/flight_voucher', $page_data);
            $this->load->library('provab_pdf');
            $create_pdf = new Provab_Pdf();
            $mail_template_pdf = $this->template->isolated_view('voucher/flight_pdf', $page_data);
            $pdf = $create_pdf->create_pdf_investor($mail_template_pdf, 'F');
            //debug($pdf);die;
            $ss = $this->provab_mailer->send_mail($email, domain_name() . ' - Flight Ticket', $mail_template, $pdf);
            //   $message = $this->CI->email->print_debugger();
            //debug($ss);
            //debug($message);die;
        }
    }

    //new function for verifying if the departure and destination cities is in NEP or IND
    function isSectorNepalIndia($from, $to)
    {
        $CI = &get_instance();
        if (is_array($to) && is_array($from)) {
            $from = $from[0];
            $to = end($to);
        }
        $from = strtolower($from);
        $to = strtolower($to);
        $sectors = $CI->db->query('SELECT `airport_code` FROM  flight_airport_list fal where country = "India" or country = "Nepal"')->result_array();
        foreach ($sectors as $k => $v) {
            $sectorArray[] = strtolower($v['airport_code']);
        }

        if (in_array($to, $sectorArray) && in_array($from, $sectorArray)) {
            return true;
        }
        return false;
    }


}
