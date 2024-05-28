<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
ini_set('max_execution_time', 300);

/**
 *
 * @package Provab
 * @subpackage Flight
 * @author Balu A<balu.provab@gmail.com>
 * @version V1
 */
//error_reporting(0);
class Flight extends CI_Controller
{

    private $current_module;
    public function __construct()
    {
        parent::__construct();
        $this->load->library('rewards');
        // $this->output->enable_profiler(TRUE);
        $this->load->model('flight_model');
        $this->load->model('user_model'); // we need to load user model to access provab sms library
        $this->load->library('provab_sms'); // we need this provab_sms library to send sms.
        $this->current_module = $this->config->item('current_module');
    }
    /**
     * FIXME : REMOVE THIS - Balu A
     */
    function booking_summary()
    {
        $this->template->view('flight/booking_summary');
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
                $search_params['country'] = $safe_search_data['country']; // for next and previous day button.
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
        //echo $this->db->last_query();die
        //unset($active_booking_source[0]);

        $s_data = $safe_search_data['data'];
        if (valid_array($active_booking_source) == true and $safe_search_data['status'] == true) {
            if (($s_data['trip_type'] == 'circle') && ($s_data['is_domestic'] == true)) {
                $new_active_bs = array();
                foreach ($active_booking_source as $bk => $bv) {
                    if ($bv['source_id'] != AMADEUS_FLIGHT_BOOKING_SOURCE) {
                        $new_active_bs[] = $bv;
                    }
                }
                $active_booking_source = $new_active_bs;
            }
            $safe_search_data['data']['search_id'] = abs($search_id);
            $page_params = array(
                'flight_search_params' => $safe_search_data['data'],
                'active_booking_source' => $active_booking_source
            );
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
     * Here we need to run booking based on api
     * View Page for booking
     */
    function booking($search_id)
    {
        $pre_booking_params = $this->input->post();

        $search_hash = $pre_booking_params['search_hash'];

        load_flight_lib($pre_booking_params['booking_source']);
        $safe_search_data = $this->flight_lib->search_data($search_id);

        $safe_search_data['data']['search_id'] = intval($search_id);

        $token = $this->flight_lib->unserialized_token($pre_booking_params['token'], $pre_booking_params['token_key']);

        if ($token['status'] == SUCCESS_STATUS) {
            $pre_booking_params['token'] = $token['data']['token'];
        }

        if (isset($pre_booking_params['booking_source']) == true && $safe_search_data['status'] == true) {
            //Balu A - Check Travel is Domestic or International
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
            if ($pre_booking_params['booking_source'] == PLAZMA_BOOKING_SOURCE) {
                $markupmodule = 'plazmaflight';
            } else {
                $markupmodule = 'flight';
            }
            $currency_obj = new Currency(array(
                'module_type' => $markupmodule,
                'from' => get_application_currency_preference(),
                'to' => get_application_currency_preference()
            ));
            // We will load different page for different API providers... As we have dependency on API for Flight details
            $page_data['search_data'] = $safe_search_data['data'];
            //Need to fill pax details by default if user has already logged in
            $this->load->model('user_model');
            $page_data['pax_details'] = array();
            $agent_details = $this->user_model->get_current_user_details();

            $page_data['agent_address'] = $agent_details[0]['address'];
            $page_data['pax_details'] = $agent_details;

            //Not to show cache data in browser
            header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
            header("Cache-Control: post-check=0, pre-check=0", false);
            header("Pragma: no-cache");
            $pre_booking_params['safe_search_data'] = $safe_search_data;
            //changes start for nationality country code
            $nationalityCodeQuery = $this->custom_db->single_table_records('api_country_list', '*', array('nationality' => $safe_search_data['data']['country']))['data'][0];

            $page_data['nationality_code'] = $nationalityCodeQuery['origin'];
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
                    //	$quote_update = $this->fare_quote_booking ( $pre_booking_params );

                    if ($quote_update['status'] == FAILURE_STATUS) {
                        redirect(base_url() . 'index.php/flight/exception?op=Remote IO error @ Session Expiry&notification=session');
                    } else {
                        $pre_booking_params = $quote_update['data'];
                        //Get Extra Services
                        $extra_services = $this->get_extra_services($pre_booking_params);
                        if ($extra_services['status'] == SUCCESS_STATUS) {
                            $page_data['extra_services'] = $extra_services['data'];
                        } else {
                            $page_data['extra_services'] = array();
                        }
                    }


                    // Load View
                    $page_data['booking_source'] = $pre_booking_params['booking_source'];
                    /*$page_data ['ProvabAuthKey'] = $pre_booking_provabauthkey;*/
                    $page_data['pre_booking_params'] = $pre_booking_params;
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

                    //Extracting Segment Summary and Fare Details
                    $updated_flight_details = $pre_booking_params['token'];
                    unset($updated_flight_details[0][0]);
                    unset($updated_flight_details[1][0]);
                    //debug($currency_obj);exit;
                    $flight_details = array();
                    $is_price_Changed = false;

                    foreach ($updated_flight_details as $k => $v) {
                        //TODO: Implement this using old and new price
                        /*if($is_price_Changed == false && $v['IsPriceChanged'] == true) {
							$is_price_Changed = true;
						}*/


                        $temp_flight_details = $this->flight_lib->extract_flight_segment_fare_details($v, $currency_obj, $search_id, $this->current_module);


                        unset($temp_flight_details[0]['BookingType']); //Not needed in Next page
                        $flight_details[$k] = $temp_flight_details[0];
                    }

                    //Merge the Segment Details and Fare Details For Printing Purpose
                    $flight_pre_booking_summary = $this->flight_lib->merge_flight_segment_fare_details($flight_details);
                    //       debug($temp_flight_details);die;       
                    $pre_booking_params['token'] = $flight_details;
                    $page_data['pre_booking_params'] = $pre_booking_params;
                    $page_data['pre_booking_summery'] = $flight_pre_booking_summary;
                    $page_data['is_price_Changed'] = $is_price_Changed;
                    $Domain_record = $this->custom_db->single_table_records('domain_list', '*');
                    $page_data['active_data'] = $Domain_record['data'][0];
                    $temp_record = $this->custom_db->get_phone_code_list();
                    $page_data['phone_code'] = $temp_record;

                    $state_list = $this->custom_db->single_table_records('state_list', '*');
                    $state_list_array = array();
                    foreach ($state_list['data'] as $key => $state) {
                        $state_list_array[$state['en_name']] = $state['en_name'];
                    }
                    $page_data['state_list'] = $state_list_array;

                    /*
						session expiry time calculation 
					*/

                    $page_data['session_expiry_details'] = $this->flight_lib->set_flight_search_session_expiry(true, $search_hash);

                    //Pusing ExtraService details to pre_booking_params array()
                    $page_data['pre_booking_params']['extra_services'] = $extra_services;
                    /*#############################FOR REWARDS SYSTEM #########################################*/

                    $page_data['total_price'] = round($flight_pre_booking_summary['FareDetails']['api_PriceDetails']['BaseFare'] + $flight_pre_booking_summary['FareDetails']['api_PriceDetails']['Tax'], 2);
                    //debug($page_data ['total_price']);die;
                    $page_data['reward_usable'] = 0;
                    $page_data['reward_earned'] = 0;
                    $page_data['total_price_with_rewards'] = 0;
                    if (is_logged_in_user()) {
                        $user_id = $this->entity_user_id;
                        //$data_rewards = $this->transaction->user_reward_details($user_id);
                        $currency_obj_r = new Currency(array('module_type' => 'flight', 'from' => get_application_display_currency_preference(), 'to' => UNIVERSAL_DEFAULT_CURRENCY));
                        $reward_values = $this->rewards->find_reward(META_AIRLINE_COURSE, get_converted_currency_value($currency_obj_r->force_currency_conversion($page_data['total_price'])));
                        //	debug($reward_values);die;
                        $reward_details = $this->rewards->get_reward_coversion_and_limit_details();
                        //debug($this->rewards)
                        //$usable_rewards = $reward_values['usable_reward'];
                        $usable_rewards = $this->rewards->usable_rewards($user_id, META_AIRLINE_COURSE, $reward_values['usable_reward']);
                        //	echo "test";
                        //debug($usable_rewards);die;
                        $page_data['reward_earned'] = $reward_values['earning_reward'];
                        //debug($usable_rewards);exit();
                        if ($reward_values['usable_reward'] <= $usable_rewards) {
                            $page_data['reward_usable'] = $reward_values['usable_reward'];
                        } else {
                            $page_data['reward_usable'] = 0;
                        }
                        //	debug($page_data['reward_usable']);exit();
                        if ($page_data['reward_usable']) {
                            $reducing_amount = $this->rewards->find_reward_amount($page_data['reward_usable']);
                            //	$reducing_amount = round($reducing_amount*$page_data['converted_currency_rate'],2);
                            $page_data['total_price_with_rewards'] = $page_data['total_price'] - $reducing_amount;
                            $page_data['reducing_amount'] = $reducing_amount;
                        }
                    }
                    if ($_SERVER['REMOTE_ADDR'] == "223.187.117.154") {
                        //	debug($page_data);die;
                    }
                    /*#############################END #######################################################*/
                    //exit;
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
                    //               echo $this->db->last_query();
                    //debug($temp_record);die;
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
                        $currency_obj_r = new Currency(array('module_type' => 'flight', 'from' => get_application_display_currency_preference(), 'to' => UNIVERSAL_DEFAULT_CURRENCY));
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
                            //   $reducing_amount = round($reducing_amount*$page_data['converted_currency_rate'],2);
                            $page_data['total_price_with_rewards'] = $page_data['total_price'] - $reducing_amount;
                            $page_data['reducing_amount'] = $reducing_amount;
                        }
                    }
                    $this->template->view('flight/tbo/amadeus_booking_page', $page_data);
                    break;
            }
        } else {
            // redirect(base_url());
        }
    }

    /**
     * Fare Quote Booking
     * This will be used for TBO LCC carrier
     */
    private function fare_quote_booking($flight_booking_details, $api_name = 'tmx')
    {

        $fare_quote_details = $this->flight_lib->fare_quote_details($flight_booking_details);
        if ($api_name == 'plazma') {
            $fare_quote_details['data']['token'][0] = unserialized_data($fare_quote_details['data']['search_access_key'][0]);
        }
        if ($fare_quote_details['status'] == SUCCESS_STATUS && valid_array($fare_quote_details) == true) {
            //Converting API currency data to preferred currency

            if ($api_name == 'amadeus') {
                $currency_obj = new Currency(array('module_type' => 'flight', 'from' => amadeus_get_api_data_currency(), 'to' => get_application_currency_preference()));
            } else {
                $currency_obj = new Currency(array('module_type' => 'flight', 'from' => provab_get_api_data_currency(), 'to' => get_application_currency_preference()));
            }
            if ($api_name == 'plazma') {
                $currency_obj = new Currency(array('module_type' => 'flight', 'from' => 'NPR', 'to' => get_application_currency_preference()));
            }
            // $fare_quote_details = $this->flight_lib->farequote_data_in_preferred_currency($fare_quote_details, $currency_obj);
            //changes changed for b2b search
            $fare_quote_details = $this->flight_lib->farequote_data_in_preferred_currency_b2b($fare_quote_details, $currency_obj);
        }
        return $fare_quote_details;
    }
    private function fare_quote_bookingdineshold($flight_booking_details)
    {
        $fare_quote_details =  $this->flight_lib->fare_quote_details($flight_booking_details);
        //  debug($fare_quote_details);exit;
        if ($fare_quote_details['status'] == SUCCESS_STATUS) {
            //Converting API currency data to preferred currency
            $currency_obj = new Currency(array('module_type' => 'flight', 'from' => amadeus_get_api_data_currency(), 'to' => get_application_currency_preference()));
            //changes added new funciton for b2b search
            // 			$fare_quote_details = $this->flight_lib->farequote_data_in_preferred_currency($fare_quote_details, $currency_obj);
            $fare_quote_details = $this->flight_lib->farequote_data_in_preferred_currency_b2b($fare_quote_details, $currency_obj);
        }
        return $fare_quote_details;
    }
    /**
     * Get Extra Services
     */
    private function get_extra_services($flight_booking_details)
    {
        $extra_service_details =  $this->flight_lib->get_extra_services($flight_booking_details);
        return $extra_service_details;
    }
    function getSelectedInsuranceFullDetails($selectedPlans, $availablePlans){
        if($selectedPlans[0]['type'] == 'Family'){
            $planType = 'familyPlans';
            $planId[] = $selectedPlans[0]['planId'];
        }else{
            $planType = 'perPassengerPlans';
            foreach($selectedPlans as $k=>$v){
                $planId[] = $v['PlanId'];
            }
// debug($availablePlans);die;
foreach($planId as $plan_k=>$plan_v){
            foreach($availablePlans[$planType] as $a_k=>$a_v){
                foreach($a_v as $k=>$v){
                  
                        // debug();
                        // die;
                        if($plan_v == $v['PlanCode']){
                            // $responsePlans[key($availablePlans[$planType])][] = $v;
                            $responsePlans[$plan_k]['PlanDetails'] = $v;
                            // $reponsePlans
                            // $responseArray
                        }
                    }
                }
            }


        }
    }
    /**
     * Balu A
     * Secure Booking of FLIGHT
     * 255 single adult static booking request 2310
     * 261 double room static booking request 2308
     *
     * Process booking no view page
     */
    function pre_booking($search_id)
    {
        if (CURRENT_DOMAIN_KEY == 'TMX1004111597231027') {
            debug("blocked");
            exit;
        }
        $post_params = $this->input->post();
        if (valid_array($post_params) == false) {
            redirect(base_url());
        }

        // $this->custom_db->generate_static_response(json_encode($post_params));
        // Insert To temp_booking and proceed
        /* $post_params = $this->flight_model->get_static_response($static_search_result_id); */

        //Setting Static Data - Balu A
        $post_params['billing_city'] = 'Bangalore';
        $post_params['billing_zipcode'] = '560100';

        // Make sure token and temp token matches
        $valid_temp_token = unserialized_data($post_params['token'], $post_params['token_key']);
        if ($valid_temp_token != false) {
            load_flight_lib($post_params['booking_source']);
            $amount = 0;
            $currency = '';
            /****Convert Display currency to Application default currency***/
            //After converting to default currency, storing in temp_booking
            $post_params['token'] = unserialized_data($post_params['token']);

            if ($post_params['booking_source'] == PROVAB_FLIGHT_BOOKING_SOURCE) {
                $currency_obj = new Currency(array(
                    'module_type' => 'flight',
                    'from' => get_application_currency_preference(),
                    'to' => admin_base_currency()
                ));
            } else {
                $currency_obj = new Currency(array(
                    'module_type' => 'flight',
                    'from' => get_application_currency_preference(),
                    'to' => admin_base_currency()
                ));
            }
            if ($post_params['booking_source'] == PLAZMA_BOOKING_SOURCE) {
                $currency_obj = new Currency(array(
                    'module_type' => 'plazmaflight',
                    'from' => get_application_currency_preference(),
                    'to' => admin_base_currency()
                ));
            }
            $post_params['token']['token'] = $this->flight_lib->convert_token_to_application_currency($post_params['token']['token'], $currency_obj, $this->current_module);

            //Convert to Extra Services to application currency
            if (isset($post_params['token']['extra_services']) == true) {
                $post_params['token']['extra_services'] = $this->flight_lib->convert_extra_services_to_application_currency($post_params['token']['extra_services'], $currency_obj);

                //Get Extra Service Price
                $extra_service_price = $this->extra_service_price($post_params);
            } else {
                $extra_service_price = 0;
            }

            $post_params['token'] = serialized_data($post_params['token']);

            //Reindex Passport Month
            // $post_params['passenger_passport_expiry_month'] = $this->flight_lib->reindex_passport_expiry_month($post_params['passenger_passport_expiry_month'], $search_id);
            // if else condition for citizenship id consideration
            foreach ($post_params['identification_type'] as $k => $v) {
                if ($v === 'citizenship') {
                    $post_params['passenger_passport_number'][$k] =  $post_params['passenger_citizenship_number'][$k];
                    $post_params['passenger_passport_issuing_country'][$k] =  $post_params['passenger_citizenship_issuing_country'][$k];
                    $post_params['passenger_passport_expiry_year'][$k] =  $post_params['passenger_citizenship_expiry_year'][$k];
                    $post_params['passenger_passport_expiry_month'][$k] =  $post_params['passenger_citizenship_expiry_month'][$k];
                    $post_params['passenger_passport_expiry_day'][$k] =  $post_params['passenger_citizenship_expiry_day'][$k];
                }
            }
            // debug($post_params);die;
            $temp_booking = $this->module_model->serialize_temp_booking_record($post_params, FLIGHT_BOOKING);
$insuranceAmount = 0;
            $book_id = $temp_booking['book_id'];
            $book_origin = $temp_booking['temp_booking_origin'];
            if ($post_params['booking_source'] == PROVAB_FLIGHT_BOOKING_SOURCE  || $post_params['booking_source'] == PROVAB_AEROCRS_BOOKING_SOURCE || $post_params['booking_source'] == PLAZMA_BOOKING_SOURCE || $post_params['booking_source'] == AMADEUS_FLIGHT_BOOKING_SOURCE) {

                if ($post_params['booking_source'] == PROVAB_FLIGHT_BOOKING_SOURCE) {
                    $currency_obj = new Currency(array(
                        'module_type' => 'flight',
                        'from' => admin_base_currency(),
                        'to' => admin_base_currency()
                    ));
                } else {
                    $currency_obj = new Currency(array(
                        'module_type' => 'flight',
                        'from' => admin_base_currency(),
                        'to' => admin_base_currency()
                    ));
                }




                if ($post_params['booking_source'] == PLAZMA_BOOKING_SOURCE) {
                    $currency_obj = new Currency(array(
                        'module_type' => 'plazmaflight',
                        'from' => admin_base_currency(),
                        'to' => admin_base_currency()
                    ));
                }
                $temp_token = unserialized_data($post_params['token']);
           
                $total_seg_cnt = 0;
                foreach ($temp_token['token'][0]['SegmentDetails'] as $segk => $segv) {
                    $total_seg_cnt += count($segv);
                }
                if($post_params['isInsured'] == 1 && $post_params['insuranceId'] != NULL && $post_params['insuranceId'] != ''){
                    $finalPlanDetails = [];
                    $insurancePlansDetails= $this->custom_db->single_table_records('plan_retirement', '*', array('id'=>$post_params['insuranceId']));
                    if($insurancePlansDetails['status'] == 1){
                        $availableInsurancePlanDetails=json_decode($insurancePlansDetails['data'][0]['message'], true);
                        $selectedPlanDetails = json_decode($post_params['selectedPlansJson'], true);
                        foreach($post_params['identification_type'] as $k=>$v){
                            $selectedPlanDetails[$k]['passengerDetails']['name'] = $selectedPlanDetails[$k]['passenger']; 
                            $selectedPlanDetails[$k]['passengerDetails']['identificationType'] = $post_params['identification_type'][$k];
                            $selectedPlanDetails[$k]['passengerDetails']['identificationNumber'] = $post_params['passenger_passport_number'][$k];
                            $passengerDetails[] = $selectedPlanDetails[$k]['passengerDetails'];
                          
                        }
                        $formattedArray['searchId'] = $search_id;
                        $formattedArray['passengerDetails'] = $passengerDetails;
                        $formattedArray['bookingPassengerDetails']['name'] = $selectedPlanDetails[0]['passenger']; 
                         $formattedArray['bookingPassengerDetails']['email'] = $post_params['billing_email']; 
                         $formattedArray['bookingPassengerDetails']['phoneNumber'] = $post_params['passenger_contact']; 
                         $searchData = $this->flight_model->get_safe_search_data($search_id);
                         $formattedArray['searchData'] = $searchData['data'];
                         $formattedArray['SegmentDetails'] = $temp_token['token'][0]['SegmentDetails'];
                        //  debug($selectedPlanDetails);
                         $allAvailableInsurancePlans = json_decode($insurancePlansDetails['data'][0]['message'], true);
                        //  debug($allAvailableInsurancePlans);
                        //  die;
                          foreach($selectedPlanDetails as $k=>$v){
                            if($v['type'] == "Individual"){
                                $planType = "perPassengerPlans";
                            }else{
                                $planType = "familyPlans";
                            }
                            $planCategory = $v['planType'];
// $string = "Plans"

// debug($formattedArray);die;
                            foreach($allAvailableInsurancePlans[$planType][$planCategory.'Plans'] as $a_k=>$a_v){

                                if($v['planId']== $a_v['PlanCode']){
                                    $finalPlanDetails[$formattedArray['passengerDetails'][$k]['name']] = $a_v;

                                    $insuranceAmount += (int) $a_v['TotalPremiumAmount'];
                                    break;
                                }

                            }
                         }
                        $formattedArray['planDetails'] = $finalPlanDetails;
                        $formattedArray['totalPrice'] = $insuranceAmount;
                        $updateData['message'] = json_encode($formattedArray, true);
                        $updateData['app_reference'] = $book_id;
                        $updateData['sortcode'] = 1;
                        $updateCondition['id'] = $post_params['insuranceId'];
                         $upateStatus = $this->custom_db->update_record('plan_retirement', $updateData,$updateCondition);

                        //  die("here");
                        // $this->getSelectedInsuranceFullDetails($selectedPlanDetails, $availableInsurancePlanDetails);
        
                    }else{
                        redirect(base_url() . 'index.php/flight/exception?op=Invalid Insurance Details&notification="Insurance Detils Not Found"');
                    }
                    
        
                    // $totalInsuranceAmount = $this->insurance_model->getTotalInsurancePrice($selectedPlans);
                }
                if ($post_params['booking_source'] == AMADEUS_FLIGHT_BOOKING_SOURCE) {
                    $discount_details = discount_details();
                }
                $formattedArray['searchId'] = $search_id;
                $formattedArray['SegmentDetails'] = $temp_token['token'][0]['SegmentDetails']; 
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
                $amount = $fare_details['_AgentBuying'] - $segment_discount;
                //debug($amount);exit;
                //Adding Extra Service Price to booking amount
                $amount += $extra_service_price;
                $actual_fare = $fare_details['_CustomerBuying']; //send actual fare for checking as requested by client
                $currency = $fare_details['Currency'];
            }

            $email = $post_params['billing_email'];
            $phone = $post_params['passenger_contact'];
            $pgi_amount = $amount;
            $firstname = $post_params['first_name']['0'] . " " . $post_params['last_name']['0'];
            $book_id = $book_id;
            $productinfo = META_AIRLINE_COURSE;
            //check current balance before proceeding further
            $agent_paybleamount = $currency_obj->get_agent_paybleamount($amount);

            //If its Hold Ticket then dont check the agent balance
            if (isset($post_params['ticket_method']) == true && $post_params['ticket_method'] == 'hold_ticket') {
                //If its Hold Ticket then dont check the agent balance
                $domain_balance_status = SUCCESS_STATUS;
            } else {
                $domain_balance_status = $this->domain_management_model->verify_current_balance($agent_paybleamount['amount'], $agent_paybleamount['currency']);
            }
            $this->custom_db->update_record('plan_retirement', array('app_reference' => $book_id), array('id' => $post_params['insurance_id']));
            // echo PAYU_PGI; exit;
            if ($domain_balance_status == true) {
                //Save the Booking Data
                $booking_data = $this->module_model->unserialize_temp_booking_record($book_id, $book_origin);

                $book_params = $booking_data['book_attributes'];

                $book_params['segment_discount'] = $segment_discount;
                $book_params['ori_segment_discount'] = $ori_segment_discount;

                $data = $this->flight_lib->save_booking($book_id, $book_params, $currency_obj, $this->current_module);

                $reward_point = 0;
                $reward_amount = 0;
                $reward_discount = 0;
                //    debug($post_params['redeem_points_post']);die;
                if ($post_params['redeem_points_post'] == "1") {
                    $reward_discount = $post_params['reducing_amount'];
                    $reward_amount = $post_params['reducing_amount'];
                    $reward_point = $post_params['reward_usable'];
                }
                if ($reward_discount >= 1) {
                    //  debug($reward_discount);die;
                    $amount    = number_format(($amount - $reward_discount), 2);
                }
                $pgi_amount += $insuranceAmount;
                $reward_earned = $post_params['reward_earned'];
                switch ($post_params['payment_method']) {
                    case PAY_NOW:
                        $this->load->model('transaction');
                        $pg_currency_conversion_rate = $currency_obj->payment_gateway_currency_conversion_rate();
                        $this->transaction->create_payment_record($book_id, $pgi_amount, $firstname, $email, $phone, $productinfo, 0, 0, $pg_currency_conversion_rate, $reward_point, $reward_amount, $reward_earned);

                        redirect(base_url() . 'index.php/payment_gateway/payment/' . $book_id . '/' . $book_origin);
                        break;
                    case PAY_AT_BANK:
                        echo 'Under Construction - Remote IO Error';
                        exit();
                        break;
                }
            } else {
                redirect(base_url() . 'index.php/flight/exception?op=Amount Flight Booking&notification=insufficient_balance');
            }
        }
        redirect(base_url() . 'index.php/flight/exception?op=Remote IO error @ FLIGHT Booking&notification=validation');
    }
    /**
     * Calculates Selected Extra service Price
     * @param unknown_type $post_params
     */
    private function extra_service_price($post_params)
    {
        $extra_service_details = $this->flight_lib->extract_extra_service_details($post_params);

        //Bagggage Price
        $baggage_index = 0;
        $baggage_price = 0;
        while (isset($post_params["baggage_$baggage_index"]) == true) {
            foreach ($post_params["baggage_$baggage_index"] as $bag_k => $bag_v) {
                if (isset($extra_service_details['ExtraServiceDetails']['Baggage'][$bag_v])) {
                    $baggage_price += $extra_service_details['ExtraServiceDetails']['Baggage'][$bag_v]['Price'];
                }
            }
            $baggage_index++;
        }

        //Meal Price
        $meal_index = 0;
        $meal_price = 0;
        while (isset($post_params["meal_$meal_index"]) == true) {
            foreach ($post_params["meal_$meal_index"] as $meal_k => $meal_v) {
                if (isset($extra_service_details['ExtraServiceDetails']['Meals'][$meal_v])) {
                    $meal_price += $extra_service_details['ExtraServiceDetails']['Meals'][$meal_v]['Price'];
                }
            }
            $meal_index++;
        }

        //Seat Price
        $seat_index = 0;
        $seat_price = 0;
        while (isset($post_params["seat_$seat_index"]) == true) {
            foreach ($post_params["seat_$seat_index"] as $seat_k => $seat_v) {
                if (isset($extra_service_details['ExtraServiceDetails']['Seat'][$seat_v])) {
                    $seat_price += $extra_service_details['ExtraServiceDetails']['Seat'][$seat_v]['Price'];
                }
            }
            $seat_index++;
        }

        $extra_service_total_price = ($baggage_price + $meal_price + $seat_price);

        return $extra_service_total_price;
    }
    /*
		process booking in backend until show loader 
	*/
    function process_booking($book_id, $temp_book_origin)
    {
        if (CURRENT_DOMAIN_KEY == 'TMX1004111597231027') {
            debug("blocked");
            exit;
        }
        if ($book_id != '' && $temp_book_origin != '' && intval($temp_book_origin) > 0) {

            $page_data['form_url'] = base_url() . 'index.php/flight/secure_booking';
            $page_data['form_method'] = 'POST';
            $page_data['form_params']['book_id'] = $book_id;
            $page_data['form_params']['temp_book_origin'] = $temp_book_origin;
            $this->template->view('share/loader/booking_process_loader', $page_data);
        } else {
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

        if (CURRENT_DOMAIN_KEY == 'TMX1004111597231027') {
            debug("blocked");
            exit;
        }
        $post_data = $this->input->post();

        if (
            valid_array($post_data) == true && isset($post_data['book_id']) == true && isset($post_data['temp_book_origin']) == true &&
            empty($post_data['book_id']) == false && intval($post_data['temp_book_origin']) > 0
        ) {
            //verify payment status and continue
            $book_id = trim($post_data['book_id']);
            $temp_book_origin = intval($post_data['temp_book_origin']);

        } else {
            echo "faile71";
            die;
            redirect(base_url() . 'index.php/flight/exception?op=InvalidBooking&notification=invalid');
        }
        
        // run booking request and do booking
        $temp_booking = $this->module_model->unserialize_temp_booking_record($book_id, $temp_book_origin);
        $insuranceDetails = $this->custom_db->single_table_records('plan_retirement','message,sortcode', array('app_reference'=>$book_id));
        // debug($insuranceDetails);
        $insuranceDetails = json_decode($insuranceDetails['data'][0]['message'], true);
        // $insuranceAmount = $insuranceDetails[''];
        // debug($insuranceDetails);die;
        $insuranceTotalAmount = $insuranceDetails['totalPrice'];
        //Delete the temp_booking record, after accessing
        //$this->module_model->delete_temp_booking_record ($book_id, $temp_book_origin);

        load_flight_lib($temp_booking['booking_source']);
        // 
        if ($temp_booking['booking_source'] == PROVAB_FLIGHT_BOOKING_SOURCE || $temp_booking['booking_source'] == PROVAB_AEROCRS_BOOKING_SOURCE  || $temp_booking['booking_source'] == PLAZMA_BOOKING_SOURCE || $temp_booking['booking_source'] == AMADEUS_FLIGHT_BOOKING_SOURCE) {
            $currency_obj = new Currency(array(
                'module_type' => 'flight',
                'from' => admin_base_currency(),
                'to' => admin_base_currency()
            ));
            if ($temp_booking['booking_source'] == PLAZMA_BOOKING_SOURCE) {
                $currency_obj = new Currency(array(
                    'module_type' => 'plazmaflight',
                    'from' => admin_base_currency(),
                    'to' => admin_base_currency()
                ));
            }
            $flight_details = $temp_booking['book_attributes']['token']['token'];
            $flight_booking_summary = $this->flight_lib->merge_flight_segment_fare_details($flight_details);
            $fare_details = $flight_booking_summary['FareDetails'][$this->current_module . '_PriceDetails'];
            $total_booking_price = $fare_details['_AgentBuying']+$insuranceTotalAmount;
            $currency = $fare_details['Currency'];
        }
        // verify payment status and continue
        // Flight_Model::lock_tables();
        $agent_paybleamount = $currency_obj->get_agent_paybleamount($total_booking_price);

        //If its Hold Ticket then dont check the agent balance
        if (isset($temp_booking['book_attributes']['ticket_method']) == true && $temp_booking['book_attributes']['ticket_method'] == 'hold_ticket') {
            //If its Hold Ticket then dont check the agent balance
            $domain_balance_status = SUCCESS_STATUS;
        } else {
            $domain_balance_status = $this->domain_management_model->verify_current_balance($agent_paybleamount['amount'], $agent_paybleamount['currency']);
        }

        //debug($temp_booking);die;
        if ($domain_balance_status) {
            if ($temp_booking != false) {
                switch ($temp_booking['booking_source']) {
                    case PROVAB_FLIGHT_BOOKING_SOURCE:
                    case PROVAB_AEROCRS_BOOKING_SOURCE:
                    case PLAZMA_BOOKING_SOURCE:
                    case AMADEUS_FLIGHT_BOOKING_SOURCE:
                        try {
                            //debug($flight_lib);die;

                            $booking = $this->flight_lib->process_booking($book_id, $temp_booking['book_attributes']);
                            // debug($booking );die;
                        } catch (Exception $e) {

                            $booking['status'] = BOOKING_ERROR;
                        }
                        // Update booking based on booking status and book id
                        break;
                }
                // $this->ConfirmPurchase();
                $updateCondition['app_reference'] = $book_id;
                $bookingId = $array['data']['ticket']['TicketDetails'][0]['CommitBooking']['BookingDetails']['BookingId'];
                $updateData['source'] = $insuranceTotalAmount;
                $this->custom_db->update_record('flight_booking_transaction_details', $updateData, $updateCondition);
                $this->ConfirmPurchase($insuranceDetails, $insuranceTotalAmount, $bookingId);
                //Failed booking logs in separate file, FIXME ---------------------------
                if (in_array($booking['status'], array(SUCCESS_STATUS, BOOKING_CONFIRMED, BOOKING_PENDING, BOOKING_FAILED, BOOKING_ERROR, BOOKING_HOLD, FAILURE_STATUS)) == true) {
                    $currency_obj = new Currency(array(
                        'module_type' => 'flight',
                        'from' => admin_base_currency(),
                        'to' => admin_base_currency()
                    ));
                    if ($temp_booking['booking_source'] == PLAZMA_BOOKING_SOURCE) {
                        $currency_obj = new Currency(array(
                            'module_type' => 'plazmaflight',
                            'from' => admin_base_currency(),
                            'to' => admin_base_currency()
                        ));
                    }
                    $booking['data']['booking_params']['currency_obj'] = $currency_obj;
                    //Update the booking Details
                    $ticket_details = @$booking['data']['ticket'];
                    $ticket_details['master_booking_status'] = $booking['status'];
                    //Updating Booking Details
                    $data = $this->flight_lib->update_booking_details($book_id, $booking['data']['booking_params'], $ticket_details, $this->current_module);
                    //	echo "sdfe";
                    if (in_array($booking['status'], array(SUCCESS_STATUS, BOOKING_CONFIRMED, BOOKING_PENDING, BOOKING_ERROR)) == true) {

                        //debug( $booking );
                        //	debug($temp_booking['book_attributes']);die;
                        //	ebug($data);die;

                        //changes removed following reducing amount whatever it was and only added segment discount
                        // $red = $temp_booking['book_attributes']['segdis'] + $temp_booking['book_attributes']['reducing_amount'];
                        $red = $temp_booking['book_attributes']['segdis'];
                        $ftot = ($temp_booking['book_attributes']['token']['token'][0]['FareDetails']['b2b_PriceDetails']['_BaseFare'] + round($temp_booking['book_attributes']['token']['token'][0]['FareDetails']['b2b_PriceDetails']['_TaxSum'])) - $red;

                        $this->domain_management_model->update_transaction_detailsflighting('flight', $book_id, $ftot, $data['admin_markup'], $data['agent_markup'], $data['convinence'], $data['discount'], $data['transaction_currency'], $data['currency_conversion_rate']);
                    }


                    if (in_array($data['status'], array(
                        'BOOKING_CONFIRMED',
                        'BOOKING_PENDING',
                        'BOOKING_HOLD'
                    ))) {

                        // Sms config & Checkpoint
                        /* if (active_sms_checkpoint ( 'booking' )) {
						$msg = "Dear " . $data ['name'] . " Thank you for Booking your ticket with us.Ticket Details will be sent to your email id";
						$msg = urlencode ( $msg );
						$sms_status = $this->provab_sms->send_msg ( $data ['phone'], $msg );
						// return $sms_status;
						} */
                        $this->session->set_userdata(array($book_id => '1'));


                        if ($temp_booking['book_attributes']['redeem_points_post'] == 1) {
                            $this->rewards->update_after_booking($temp_booking, $book_id);
                            $this->rewards->update_flight_rewards_details($temp_booking, $book_id);
                        } else {
                            $this->rewards->update_reward_earned_value($temp_booking, $book_id);
                            $this->rewards->update_earned_rewards_details($temp_booking, $book_id, "flight_booking_details");
                        }


                        redirect(base_url() . 'index.php/voucher/flight/' . $book_id . '/' . $temp_booking['booking_source'] . '/' . $data['status'] . '/show_voucher');
                    } else {
                        redirect(base_url() . 'index.php/flight/exception?op=booking_exception&notification=' . $booking['message']);
                    }
                } else {
                    echo "failed1";
                    die;
                    redirect(base_url() . 'index.php/flight/exception?op=booking_exception&notification=' . $booking['message']);
                }
            }
            // release table lock
            Flight_Model::release_locked_tables();
        } else {
            // release table lock
            Flight_Model::release_locked_tables();
            //echo base_url () . 'index.php/flight/exception?op=Remote IO error @ Insufficient&notification=validation';
            exit();
        }
        // redirect(base_url().'index.php/flight/exception?op=Remote IO error @ FLIGHT Secure Booking&notification=validation');
    }
    function ConfirmPurchase($insuranceDetails, $amount, $pnr)
    {
        $authHeader = $this->getAuthHeader();
        $searchId = $insuranceDetails['searchId'];
        $segmentDetails = $insuranceDetails['SegmentDetails'];
        $header = $this->getArkoHeader($searchId, $amount, $pnr);
        $currentFlightInformation = $this->formatFlightInformationToXML($searchId, $segmentDetails);
        $request = '<?xml version="1.0" encoding="utf-8"?>
        <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xmlns:xsd="http://www.w3.org/2001/XMLSchema"
        xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
        <soap:Body>
        <ConfirmPurchase xmlns="http://ZEUSTravelInsuranceGateway/WebServices">
        <GenericRequest>'
        . $authHeader .
        $header['request'] .
        '<ContactDetails>
        <ContactPerson>Lawrence Yeoh</ContactPerson>
        <Address1>9, JALAN 32/63D, SRI DAMAI</Address1>
        <Address2>BUKIT RIMAU, SEKSYEN 32</Address2>
        <Address3/>
        <HomePhoneNum/>
        <MobilePhoneNum>60132210101</MobilePhoneNum>
        <OtherPhoneNum/>
        <PostCode>40460</PostCode>
        <City>SHAH ALAM</City>
        <State>SELANGOR</State>
        <Country>MALAYSIA</Country>
        <EmailAddress>chichern.yeoh@tuneinsurance.com</EmailAddress>
        </ContactDetails>
        <Flights>
        <Flight>
        <DepartCountryCode>AE</DepartCountryCode>
        <DepartStationCode>SHJ</DepartStationCode>
        <ArrivalCountryCode>RU</ArrivalCountryCode>
        <ArrivalStationCode>ODS</ArrivalStationCode>
        <DepartAirlineCode>G9</DepartAirlineCode>
        <DepartDateTime>2024-11-01 09:05:00</DepartDateTime>
        <ReturnAirlineCode>G9</ReturnAirlineCode>
        <ReturnDateTime>2024-11-29 13:05:00</ReturnDateTime>
        <DepartFlightNo>293</DepartFlightNo>
        <ReturnFlightNo>294</ReturnFlightNo>
        </Flight>
        </Flights>
        <Passengers>
        <Passenger>
        <IsInfant>1</IsInfant><FirstName>Edward</FirstName>
        <LastName>Yeoh</LastName>
        <Gender>Male</Gender>
        <DOB>2024-01-01 00:00:00</DOB>
        <Age>0</Age>
        <IdentityType>Passport</IdentityType>
        <IdentityNo>A12341230</IdentityNo>
        <IsQualified>false</IsQualified>
        <Nationality>MY</Nationality>
        <CountryOfResidence>AE</CountryOfResidence>
        <SelectedPlanCode>AEINT2WAYADVANCED</SelectedPlanCode>
        <SelectedSSRFeeCode>INSC</SelectedSSRFeeCode>
        <CurrencyCode>AED</CurrencyCode>
        <PassengerPremiumAmount>0.00</PassengerPremiumAmount>
        </Passenger>
        <Passenger>
        <IsInfant>0</IsInfant>
        <FirstName>Eleanor</FirstName>
        <LastName>Yeoh</LastName>
        <Gender>Female</Gender>
        <DOB>2010-01-01 00:00:00</DOB>
        <Age>3</Age>
        <IdentityType>Passport</IdentityType>
        <IdentityNo>A12341231</IdentityNo>
        <IsQualified>false</IsQualified>
        <Nationality>MY</Nationality>
        <CountryOfResidence>AE</CountryOfResidence>
        <SelectedPlanCode>AEINT2WAYADVANCED</SelectedPlanCode>
        <SelectedSSRFeeCode>INSC</SelectedSSRFeeCode>
        <CurrencyCode>AED</CurrencyCode>
        <PassengerPremiumAmount>40.00</PassengerPremiumAmount>
        </Passenger>
        <Passenger>
        <IsInfant>0</IsInfant>
        <FirstName>Lawrence</FirstName>
        <LastName>Yeoh</LastName>
        <Gender>Male</Gender>
        <DOB>1979-10-16 00:00:00</DOB>
        <Age>34</Age>
        <IdentityType>Passport</IdentityType>
        <IdentityNo>A12341232</IdentityNo>
        <IsQualified>false</IsQualified>
        <Nationality>MY</Nationality>
        <CountryOfResidence>AE</CountryOfResidence>
        <SelectedPlanCode>AEINT2WAYADVANCED</SelectedPlanCode>
        <SelectedSSRFeeCode>INSC</SelectedSSRFeeCode>
        <CurrencyCode>AED</CurrencyCode>
        <PassengerPremiumAmount>60.00</PassengerPremiumAmount></Passenger>
        <Passenger>
        <IsInfant>0</IsInfant>
        <FirstName>Sue</FirstName>
        <LastName>Soo</LastName>
        <Gender>Female</Gender>
        <DOB>1979-06-23 00:00:00</DOB>
        <Age>34</Age>
        <IdentityType>Passport</IdentityType>
        <IdentityNo>A12341233</IdentityNo>
        <IsQualified>false</IsQualified>
        <Nationality>MY</Nationality>
        <CountryOfResidence>AE</CountryOfResidence>
        <SelectedPlanCode>AEINT2WAYADVANCED</SelectedPlanCode><SelectedSSRFeeCode>INSC</SelectedSSRFeeCode>
        <CurrencyCode>AED</CurrencyCode>
        <PassengerPremiumAmount>60.00</PassengerPremiumAmount>
        </Passenger>
        </Passengers>
        </GenericRequest>
        </ConfirmPurchase>
        </soap:Body>
        </soap:Envelope>';
        $request_url = "https://uat-tpe.tune2protect.com/ZeusAPI/Zeus.asmx";
        $username = PROTECT_TEST_USERNAME;
        $password = PROTECT_TEST_PASSWORD;
        $ch = curl_init($request_url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $res = curl_exec($ch);
        if (curl_errno($ch)) {
            // this would be your first hint that something went wrong
            die('Couldn\'t send request: ' . curl_error($ch));
        } else {
            // check the HTTP status code of the request
            $resultStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($resultStatus == 200) {
                debug($res);die;
            } else {


                die('Request failed: HTTP status code: ' . $resultStatus);
            }
        }
        curl_close($ch);
    }

    /**
     * Balu A
     * Process booking on hold - pay at bank
     * Issue Ticket Later
     */
    function booking_on_hold($book_id)
    {
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

                $assembled_booking_details = $this->booking_data_formatter->format_flight_booking_data($booking_details, 'b2b');
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
                $master_booking_details = $this->booking_data_formatter->format_flight_booking_data($master_booking_details, 'b2b');
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
     * Displays Cancellation Ticket Details
     */
    public function ticket_cancellation_details()
    {
        $get_data = $this->input->get();
        if (isset($get_data['app_reference']) == true && isset($get_data['booking_source']) == true && isset($get_data['status']) == true) {
            $app_reference = trim($get_data['app_reference']);
            $booking_source = trim($get_data['booking_source']);
            $status = trim($get_data['status']);
            $booking_details = $this->flight_model->get_booking_details($app_reference, $booking_source, $status);
            if ($booking_details['status'] == SUCCESS_STATUS) {
                $this->load->library('booking_data_formatter');
                $booking_details = $this->booking_data_formatter->format_flight_booking_data($booking_details, $this->config->item('current_module'));
                $page_data = array();
                $page_data['booking_data'] = $booking_details['data'];
                $this->template->view('flight/ticket_cancellation_details', $page_data);
            } else {
                redirect(base_url());
            }
        } else {
            redirect(base_url());
        }
    }
    /**
     * Balu A
     * Displays Ticket cancellation Refund details
     */
    public function cancellation_refund_details()
    {
        $get_data = $this->input->get();
        if (isset($get_data['app_reference']) == true && isset($get_data['booking_source']) == true && isset($get_data['passenger_status']) == true && $get_data['passenger_status'] == 'BOOKING_CANCELLED' && isset($get_data['passenger_origin']) == true && intval($get_data['passenger_origin']) > 0) {
            $app_reference = trim($get_data['app_reference']);
            $booking_source = trim($get_data['booking_source']);
            $passenger_origin = trim($get_data['passenger_origin']);
            $passenger_status = trim($get_data['passenger_status']);
            $booking_details = $this->flight_model->get_passenger_ticket_info($app_reference, $passenger_origin, $passenger_status);
            if ($booking_details['status'] == SUCCESS_STATUS) {
                $page_data = array();
                $page_data['booking_data'] = $booking_details['data'];
                $this->template->view('flight/cancellation_refund_details', $page_data);
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
    function exception()
    {
        $module = META_AIRLINE_COURSE;
        $op = @$_GET['op'];
        $notification = @$_GET['notification'];
        // echo $notification;exit;
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
        // echo $message;exit;
        $exception = $this->module_model->flight_log_exception($module, $op, $message);

        $exception = base64_encode(json_encode($exception));
        // debug($exception);exit;
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
    /**
     * Test booking for sending to PayuMoney to show the Issue
     */
    function test_booking()
    {

        $book_id = trim('FB28-155801-537125');
        $book_origin = 665;
        redirect('transaction/payment/' . $book_id . '/' . $book_origin);
    }
    function test_post_data()
    {
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
            //	$message = $this->CI->email->print_debugger();
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
    //returns the basic auth header
    //params: none
    private function getAuthHeader(): String
    {
        $authHeader = '<web:Authentication>
                    <web:Username>' . PROTECT_TEST_USERNAME . '</web:Username>
                    <web:Password>' . PROTECT_TEST_PASSWORD . '</web:Password>
                 </web:Authentication>';
        return $authHeader;
    }
    public function getCountryDetailsFromCityName($cityName = '')
    {

        $query = 'SELECT * FROM api_city_list as ACL JOIN api_country_list as ACL2 where ACL.destination="' . $cityName . '" and ACL.country = ACL2.origin';
        $countryDetails = $this->db->query($query)->result_array()[0];
        return $countryDetails;
    }

    private function formatFlightInformationToXML($searchId, $segmentDetails = array())
    {
        $searchData = $this->flight_model->get_safe_search_data($searchId);
        $departureCityName = $segmentDetails[0][0]['OriginDetails']['CityName'];
        $departureCountryDetails = $this->getCountryDetailsFromCityName($departureCityName);
        $departureCountryCode = $departureCountryDetails['iso_country_code'];
        $arrivalCityName = $segmentDetails[0][0]['DestinationDetails']['CityName'];
        $arrivalCountryDetails = $this->getCountryDetailsFromCityName($arrivalCityName);
        $arrivalCountryCode = $arrivalCountryDetails['iso_country_code'];
        $departureDateTime = $segmentDetails[0][0]['OriginDetails']['DateTime'];
        $arrivalDateTime =  $segmentDetails[0][0]['DestinationDetails']['DateTime'];
        $departureAirlineCode = $segmentDetails[0][0]['AirlineDetails']['AirlineCode'];
        $departureFlightNumber = $segmentDetails[0][0]['AirlineDetails']['FlightNumber'];

        // $request = "<web:Flights>
        //     <web:DepartCountryCode>$departureCountryCode</web:DepartCountryCode>
        //     <web:DepartStationCode></web:DepartStationCode>
        //     <web:ArrivalCountryCode>$arrivalCountryCode</web:ArrivalCountryCode>
        //     <web:ArrivalStationCode></web:ArrivalStationCode>
        //     <web:DepartAirlineCode>$departureAirlineCode</web:DepartAirlineCode>
        //     <web:DepartDateTime>$departureDateTime</web:DepartDateTime>
        //     <web:ReturnAirlineCode></web:ReturnAirlineCode>
        //     <web:ReturnDateTime></web:ReturnDateTime>
        //     <web:DepartFlightNo>$departureFlightNumber</web:DepartFlightNo>
        //     <web:ReturnFlightNo></web:ReturnFlightNo>
        //  </web:Flights>";
        $request = '
    <web:Flights>
    <web:DepartCountryCode>AE</web:DepartCountryCode>
    <web:DepartStationCode></web:DepartStationCode>
    <web:ArrivalCountryCode>IN</web:ArrivalCountryCode>
    <web:ArrivalStationCode></web:ArrivalStationCode>
    <web:DepartAirlineCode>AI</web:DepartAirlineCode>
    <web:DepartDateTime>2025-01-01 06:30:00</web:DepartDateTime>
    <web:ReturnAirlineCode></web:ReturnAirlineCode>
    <web:ReturnDateTime></web:ReturnDateTime>
    <web:DepartFlightNo>638</web:DepartFlightNo>
    <web:ReturnFlightNo></web:ReturnFlightNo>
 </web:Flights>';

        return $request;
    }
    private function get_GetAvailablePlansOTAWithRiders_Request($searchId = '', $segmentDetails = array()): array
    {

        if ($searchId != NULL && $searchId != '' && $segmentDetails != NULL && $segmentDetails != '') {
            $response['status'] = 1;
            $authHeader = $this->getAuthHeader();
            $header = $this->getHeader($searchId);
            $currentFlightInformation = $this->formatFlightInformationToXML($searchId, $segmentDetails);
            $response['request'] = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:web="http://ZEUSTravelInsuranceGateway/WebServices">
        <soapenv:Header/>
        <soapenv:Body>
            <web:GetAvailablePlansOTAWithRiders>
                <web:GenericRequestOTALite>'
                . $authHeader .
                $header['request'] .
                $currentFlightInformation
                . '</web:GenericRequestOTALite>
        </web:GetAvailablePlansOTAWithRiders>
     </soapenv:Body>
  </soapenv:Envelope>';
        } else {
            //no search id and unauthorized access
            $response['status'] = 0;
            $response['message'] = 'Invalid Search Id';
        }
        return $response;
    }
    // returns final portion of the header
    //params: search id => String
    private function getHeader($searchId = ''): array
    {
        if ($searchId != NULL && $searchId != '') {
            $searchData = $this->flight_model->get_safe_search_data($searchId);
            if ($searchData['status'] == 1 && isset($searchData['data'])) {
                //get current currency
                $currentCurrency = get_application_currency_preference();

                //IF number of adults,children,infants is blank 0 is assigned
                $numberOfAdults = ($searchData['data']['adult_config'] == 0 || $searchData['data']['adult_config'] == NULL || $searchData['data']['adult_config'] == '') ? 0 : $searchData['data']['adult_config'];
                $numberOfChildren = ($searchData['data']['child_config'] == 0 || $searchData['data']['child_config'] == NULL || $searchData['data']['child_config'] == '') ? 0 : $searchData['data']['child_config'];
                $numberOfInfants = ($searchData['data']['infant_config'] == 0 || $searchData['data']['infant_config'] == NULL || $searchData['data']['infant_config'] == '') ? 0 : $searchData['data']['infant_config'];

                //prepare the request to return
                $response['request'] = "<web:Header>
            <web:Channel>" . PROTECT_TEST_CHANNEL_CODE . "</web:Channel>
            <web:Currency>AED</web:Currency>
            <web:CountryCode>EN</web:CountryCode>
            <web:CultureCode>EN</web:CultureCode>
            <web:TotalAdults>$numberOfAdults</web:TotalAdults>
            <web:TotalChild>$numberOfChildren</web:TotalChild>
            <web:TotalInfants>$numberOfInfants </web:TotalInfants>
         </web:Header>";

                $response['status'] = 1;
            } else {
                //search data not found
                $response['status'] = 0;
                $response['message'] = "No record with the search id was found";
            }
        } else {
            //null search id
            $response['status'] = 0;
            $response['message'] = "Invalid search id";
        }
        return $response;
    }
    // <Header>
    // <Channel>IBE</Channel>
    // <ItineraryID/>
    // <PNR>29288258</PNR>
    // <PolicyNo/>
    // <PurchaseDate>2013-10-19 13:00:00</PurchaseDate>
    // <SSRFeeCode>INSC</SSRFeeCode>
    // <FeeDescription/>
    // <Currency>AED</Currency>
    // <TotalPremium>160.00</TotalPremium>
    // <CountryCode>AE</CountryCode>
    // <CultureCode>EN</CultureCode>
    // <TotalAdults>2</TotalAdults>
    // <TotalChild>1</TotalChild>
    // <TotalInfants>1</TotalInfants>
    // </Header>
    
    private function getArkoHeader($searchId = '', $totalPrice, $pnr): array
    {
        if ($searchId != NULL && $searchId != '') {
            $searchData = $this->flight_model->get_safe_search_data($searchId);
            if ($searchData['status'] == 1 && isset($searchData['data'])) {
                //get current currency
                $currentCurrency = get_application_currency_preference();

                //IF number of adults,children,infants is blank 0 is assigned
                $numberOfAdults = ($searchData['data']['adult_config'] == 0 || $searchData['data']['adult_config'] == NULL || $searchData['data']['adult_config'] == '') ? 0 : $searchData['data']['adult_config'];
                $numberOfChildren = ($searchData['data']['child_config'] == 0 || $searchData['data']['child_config'] == NULL || $searchData['data']['child_config'] == '') ? 0 : $searchData['data']['child_config'];
                $numberOfInfants = ($searchData['data']['infant_config'] == 0 || $searchData['data']['infant_config'] == NULL || $searchData['data']['infant_config'] == '') ? 0 : $searchData['data']['infant_config'];

                //prepare the request to return
                $response['request'] = "<web:Header>
            <web:Channel>" . PROTECT_TEST_CHANNEL_CODE . "</web:Channel>
            <web:ItineraryID><web:ItineraryID>
            <web:PNR>$pnr</web:PNR>
            <web:PolicyNo/>
            <web:PurchaseDate>2024-05-23 18:00:00</web:PurchaseDate>
            <web:Currency>AED</web:Currency>
            <TotalPremium>$totalPrice</TotalPremium>
            <web:CountryCode>EN</web:CountryCode>
            <web:CultureCode>EN</web:CultureCode>
            <web:TotalAdults>$numberOfAdults</web:TotalAdults>
            <web:TotalChild>$numberOfChildren</web:TotalChild>
            <web:TotalInfants>$numberOfInfants </web:TotalInfants>
         </web:Header>";

                $response['status'] = 1;
            } else {
                //search data not found
                $response['status'] = 0;
                $response['message'] = "No record with the search id was found";
            }
        } else {
            //null search id
            $response['status'] = 0;
            $response['message'] = "Invalid search id";
        }
        return $response;
    }
    // $testFile = fopen("newfile.xml", "w") or die("Unable to open file!");
    // fwrite($testFile, $request['request']);
    // fclose($testFile);
    //Function: Get available insurance plans 
    // Params: search id => string
    public function GetAvailablePlansOTAWithRiders($searchId = '', $SegmentDetails = '')
    {
        error_reporting(E_ALL);
        if ($searchId != NULL && $searchId != '' && $SegmentDetails != NULL && $SegmentDetails != '') {
            $segmentDetails = json_decode(base64_decode($SegmentDetails), true);
            if (count($segmentDetails) > 0 && valid_array($segmentDetails) == true) {
                $request = $this->get_GetAvailablePlansOTAWithRiders_Request($searchId, $segmentDetails);
                $request_url = "https://uat-tpe.tune2protect.com/ZeusAPI/Zeus.asmx";
                $username = PROTECT_TEST_USERNAME;
                $password = PROTECT_TEST_PASSWORD;
                $ch = curl_init($request_url);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
                curl_setopt($ch, CURLOPT_HEADER, 1);
                curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
                curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $request['request']);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                $res = curl_exec($ch);
                if (curl_errno($ch)) {
                    // this would be your first hint that something went wrong
                    die('Couldn\'t send request: ' . curl_error($ch));
                } else {
                    // check the HTTP status code of the request
                    $resultStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    if ($resultStatus == 200) {

                        // Convert XML to SimpleXMLElement object
                        // Assuming $response is your soap api response

                        $xmlStart = strpos($res, '<?xml');

                        $xmlPart = substr($res, $xmlStart);
                        $soapResponse = $xmlPart;
                        $xml = simplexml_load_string($soapResponse);
                        $responseArray = $this->xml2array($xml->asXML());
                        // Initialize arrays to hold categorized data
                        $goldPlans = [];
                        $silverPlans = [];
                        $platinumPlans = [];

                        // Extract Available Plans
                        $availablePlans = $responseArray['soap:Envelope']['soap:Body']['GetAvailablePlansOTAWithRidersResponse']['GenericResponse']['AvailablePlans']['AvailablePlan'];
                        // debug($availablePlans);die;

                        foreach ($availablePlans as $plan) {
                            if (strpos($plan['PlanTitle'], 'Gold') == true) {
                                $goldPlans[] = $plan;
                            } elseif (strpos($plan['PlanTitle'], 'Silver') == true) {
                                $silverPlans[] = $plan;
                            } elseif (strpos($plan['PlanTitle'], 'Platinum') == true) {
                                $platinumPlans[] = $plan;
                            }
                        }
                        // debug($availablePlans);die;
                        // Initialize arrays to hold categorized data based on PlanPremiumChargeType
                        $goldPlansPerPassenger = [];
                        $goldPlansOther = [];
                        $silverPlansPerPassenger = [];
                        $silverPlansOther = [];
                        $platinumPlansPerPassenger = [];
                        $platinumPlansOther = [];

                        // Further categorize gold and silver plans based on PlanPremiumChargeType
                        foreach ($goldPlans as $plan) {
                            if ($plan['PlanPremiumChargeType'] === 'PerPassenger') {
                                $goldPlansPerPassenger[] = $plan;
                            } else {
                                $goldPlansOther[] = $plan;
                            }
                        }
                        foreach ($silverPlans as $plan) {
                            if ($plan['PlanPremiumChargeType'] === 'PerPassenger') {
                                $silverPlansPerPassenger[] = $plan;
                            } else {
                                $silverPlansOther[] = $plan;
                            }
                        }

                        foreach ($platinumPlans as $plan) {
                            if ($plan['PlanPremiumChargeType'] === 'PerPassenger') {
                                $platinumPlansPerPassenger[] = $plan;
                            } else {
                                $platinumPlansOther[] = $plan;
                            }
                        }
                        // debug($silverPlansPerPassenger);
                        // die;
                        $silverPlansPerPassengerForView = [];
                        $goldPlansPerPassengerForView = [];
                        $platinumPlansPerPassengerForView = [];
                        $silverPlansOtherForView = [];
                        $goldPlansOtherForView = [];
                        $platinumPlansOtherForView = [];



                        foreach ($silverPlansPerPassenger as $key=>$plan) {
                            // debug($plan['PlanPricingBreakdown']['PricingBreakdown']['PremiumBreakdown']['PremiumCharges']['Charges']['Charge'][0]['AmountValue']);die;
                            $gender = ($plan['PlanPricingBreakdown']['PricingBreakdown']['Gender'] === 'B') ? "Both" : (($plan['PlanPricingBreakdown']['PricingBreakdown']['Gender'] === 'M') ? "Male" : "Female");
                            $silverPlansPerPassengerForView[$key]['PlanCode'] = $plan['PlanCode'];
                            $silverPlansPerPassengerForView[$key]['PlanTitle'] = $plan['PlanTitle'];
                            $silverPlansPerPassengerForView[$key]['planType'] = "silver";

                            $silverPlansPerPassengerForView[$key]['CurrencyCode'] = $plan['CurrencyCode'];
                            $silverPlansPerPassengerForView[$key]['TotalPremiumAmount'] = $plan['TotalPremiumAmount'];
                            $silverPlansPerPassengerForView[$key]['PlanContent'] = $plan['PlanContent'];
                            $silverPlansPerPassengerForView[$key]['MinAge'] = $plan['PlanPricingBreakdown']['PricingBreakdown']['MinAge'];
                            $silverPlansPerPassengerForView[$key]['MaxAge'] = $plan['PlanPricingBreakdown']['PricingBreakdown']['MaxAge'];
                            $silverPlansPerPassengerForView[$key]['Gender'] = $gender;
                            $silverPlansPerPassengerForView[$key]['BaseInsurancePrice'] = $plan['PlanPricingBreakdown']['PricingBreakdown']['PremiumBreakdown']['PremiumCharges']['Charges']['Charge'][0]['AmountValue'];
                            $silverPlansPerPassengerForView[$key]['VATDetails']['Amount'] = $plan['PlanPricingBreakdown']['PricingBreakdown']['PremiumBreakdown']['PremiumCharges']['Charges']['Charge'][1]['AmountValue'];
                            $silverPlansPerPassengerForView[$key]['VATDetails']['Percentage'] = $plan['PlanPricingBreakdown']['PricingBreakdown']['PremiumBreakdown']['PremiumCharges']['Charges']['Charge'][1]['PercentageValue'];
                        }
                       
                        foreach ($goldPlansPerPassenger as $key=>$plan) {
                            // debug($plan['PlanPricingBreakdown']['PricingBreakdown']['PremiumBreakdown']['PremiumCharges']['Charges']['Charge'][0]['AmountValue']);die;
                            $gender = ($plan['PlanPricingBreakdown']['PricingBreakdown']['Gender'] === 'B') ? "Both" : (($plan['PlanPricingBreakdown']['PricingBreakdown']['Gender'] === 'M') ? "Male" : "Female");
                            $goldPlansPerPassengerForView[$key]['PlanCode'] = $plan['PlanCode'];
                            $goldPlansPerPassengerForView[$key]['PlanTitle'] = $plan['PlanTitle'];
                            $goldPlansPerPassengerForView[$key]['planType'] = "gold";

                            $goldPlansPerPassengerForView[$key]['CurrencyCode'] = $plan['CurrencyCode'];
                            $goldPlansPerPassengerForView[$key]['TotalPremiumAmount'] = $plan['TotalPremiumAmount'];
                            $goldPlansPerPassengerForView[$key]['PlanContent'] = $plan['PlanContent'];
                            $goldPlansPerPassengerForView[$key]['MinAge'] = $plan['PlanPricingBreakdown']['PricingBreakdown']['MinAge'];
                            $goldPlansPerPassengerForView[$key]['MaxAge'] = $plan['PlanPricingBreakdown']['PricingBreakdown']['MaxAge'];
                            $goldPlansPerPassengerForView[$key]['Gender'] = $gender;
                            $goldPlansPerPassengerForView[$key]['BaseInsurancePrice'] = $plan['PlanPricingBreakdown']['PricingBreakdown']['PremiumBreakdown']['PremiumCharges']['Charges']['Charge'][0]['AmountValue'];
                            $goldPlansPerPassengerForView[$key]['VATDetails']['Amount'] = $plan['PlanPricingBreakdown']['PricingBreakdown']['PremiumBreakdown']['PremiumCharges']['Charges']['Charge'][1]['AmountValue'];
                            $goldPlansPerPassengerForView[$key]['VATDetails']['Percentage'] = $plan['PlanPricingBreakdown']['PricingBreakdown']['PremiumBreakdown']['PremiumCharges']['Charges']['Charge'][1]['PercentageValue'];
                        }
                        foreach ($platinumPlansPerPassenger as $key=>$plan) {
                            // debug($plan['PlanPricingBreakdown']['PricingBreakdown']['PremiumBreakdown']['PremiumCharges']['Charges']['Charge'][0]['AmountValue']);die;
                            $gender = ($plan['PlanPricingBreakdown']['PricingBreakdown']['Gender'] === 'B') ? "Both" : (($plan['PlanPricingBreakdown']['PricingBreakdown']['Gender'] === 'M') ? "Male" : "Female");
                            $platinumPlansPerPassengerForView[$key]['PlanCode'] = $plan['PlanCode'];
                            $platinumPlansPerPassengerForView[$key]['PlanTitle'] = $plan['PlanTitle'];
                            $platinumPlansPerPassengerForView[$key]['planType'] = "platinum";

                            $platinumPlansPerPassengerForView[$key]['CurrencyCode'] = $plan['CurrencyCode'];
                            $platinumPlansPerPassengerForView[$key]['TotalPremiumAmount'] = $plan['TotalPremiumAmount'];
                            $platinumPlansPerPassengerForView[$key]['PlanContent'] = $plan['PlanContent'];
                            $platinumPlansPerPassengerForView[$key]['MinAge'] = $plan['PlanPricingBreakdown']['PricingBreakdown']['MinAge'];
                            $platinumPlansPerPassengerForView[$key]['MaxAge'] = $plan['PlanPricingBreakdown']['PricingBreakdown']['MaxAge'];
                            $platinumPlansPerPassengerForView[$key]['Gender'] = $gender;
                            $platinumPlansPerPassengerForView['BaseInsurancePrice'] = $plan['PlanPricingBreakdown']['PricingBreakdown']['PremiumBreakdown']['PremiumCharges']['Charges']['Charge'][0]['AmountValue'];
                            $platinumPlansPerPassengerForView[$key]['VATDetails']['Amount'] = $plan['PlanPricingBreakdown']['PricingBreakdown']['PremiumBreakdown']['PremiumCharges']['Charges']['Charge'][1]['AmountValue'];
                            $platinumPlansPerPassengerForView[$key]['VATDetails']['Percentage'] = $plan['PlanPricingBreakdown']['PricingBreakdown']['PremiumBreakdown']['PremiumCharges']['Charges']['Charge'][1]['PercentageValue'];
                        }
                        foreach ($silverPlansOther as $key=>$plan) {
                            // debug($plan['PlanPricingBreakdown']['PricingBreakdown']['PremiumBreakdown']['PremiumCharges']['Charges']['Charge'][0]['AmountValue']);die;
                            $gender = ($plan['PlanPricingBreakdown']['PricingBreakdown']['Gender'] === 'B') ? "Both" : (($plan['PlanPricingBreakdown']['PricingBreakdown']['Gender'] === 'M') ? "Male" : "Female");
                            $silverPlansOtherForView[$key]['PlanCode'] = $plan['PlanCode'];
                            $silverPlansOtherForView[$key]['PlanTitle'] = $plan['PlanTitle'];
                            $silverPlansOtherForView[$key]['CurrencyCode'] = $plan['CurrencyCode'];
                            $silverPlansOtherForView[$key]['TotalPremiumAmount'] = $plan['TotalPremiumAmount'];
                            $silverPlansOtherForView[$key]['PlanContent'] = $plan['PlanContent'];
                            $silverPlansOtherForView[$key]['MinAge'] = $plan['PlanPricingBreakdown']['PricingBreakdown']['MinAge'];
                            $silverPlansOtherForView[$key]['MaxAge'] = $plan['PlanPricingBreakdown']['PricingBreakdown']['MaxAge'];
                            $silverPlansOtherForView[$key]['Gender'] = $gender;
                            $silverPlansOtherForView[$key]['BaseInsurancePrice'] = $plan['PlanPricingBreakdown']['PricingBreakdown']['PremiumBreakdown']['PremiumCharges']['Charges']['Charge'][0]['AmountValue'];
                            $silverPlansOtherForView[$key]['VATDetails']['Amount'] = $plan['PlanPricingBreakdown']['PricingBreakdown']['PremiumBreakdown']['PremiumCharges']['Charges']['Charge'][1]['AmountValue'];
                            $silverPlansOtherForView[$key]['VATDetails']['Percentage'] = $plan['PlanPricingBreakdown']['PricingBreakdown']['PremiumBreakdown']['PremiumCharges']['Charges']['Charge'][1]['PercentageValue'];
                        }
                        foreach ($goldPlansOther as $key=>$plan) {
                            // debug($plan['PlanPricingBreakdown']['PricingBreakdown']['PremiumBreakdown']['PremiumCharges']['Charges']['Charge'][0]['AmountValue']);die;
                            $gender = ($plan['PlanPricingBreakdown']['PricingBreakdown']['Gender'] === 'B') ? "Both" : (($plan['PlanPricingBreakdown']['PricingBreakdown']['Gender'] === 'M') ? "Male" : "Female");
                            $goldPlansOtherForView[$key]['PlanCode'] = $plan['PlanCode'];
                            $goldPlansOtherForView[$key]['PlanTitle'] = $plan['PlanTitle'];
                            $goldPlansOtherForView[$key]['CurrencyCode'] = $plan['CurrencyCode'];
                            $goldPlansOtherForView[$key]['TotalPremiumAmount'] = $plan['TotalPremiumAmount'];
                            $goldPlansOtherForView[$key]['PlanContent'] = $plan['PlanContent'];
                            $goldPlansOtherForView[$key]['MinAge'] = $plan['PlanPricingBreakdown']['PricingBreakdown']['MinAge'];
                            $goldPlansOtherForView[$key]['MaxAge'] = $plan['PlanPricingBreakdown']['PricingBreakdown']['MaxAge'];
                            $goldPlansOtherForView[$key]['Gender'] = $gender;
                            $goldPlansOtherForView[$key]['BaseInsurancePrice'] = $plan['PlanPricingBreakdown']['PricingBreakdown']['PremiumBreakdown']['PremiumCharges']['Charges']['Charge'][0]['AmountValue'];
                            $goldPlansOtherForView[$key]['VATDetails']['Amount'] = $plan['PlanPricingBreakdown']['PricingBreakdown']['PremiumBreakdown']['PremiumCharges']['Charges']['Charge'][1]['AmountValue'];
                            $goldPlansOtherForView[$key]['VATDetails']['Percentage'] = $plan['PlanPricingBreakdown']['PricingBreakdown']['PremiumBreakdown']['PremiumCharges']['Charges']['Charge'][1]['PercentageValue'];
                        }
                        foreach ($platinumPlansOther as $key=>$plan) {
                            // debug($plan['PlanPricingBreakdown']['PricingBreakdown']['PremiumBreakdown']['PremiumCharges']['Charges']['Charge'][0]['AmountValue']);die;
                            $gender = ($plan['PlanPricingBreakdown']['PricingBreakdown']['Gender'] === 'B') ? "Both" : (($plan['PlanPricingBreakdown']['PricingBreakdown']['Gender'] === 'M') ? "Male" : "Female");
                            $platinumPlansOtherForView[$key]['PlanCode'] = $plan['PlanCode'];
                            $platinumPlansOtherForView[$key]['PlanTitle'] = $plan['PlanTitle'];
                            $platinumPlansOtherForView[$key]['CurrencyCode'] = $plan['CurrencyCode'];
                            $platinumPlansOtherForView[$key]['TotalPremiumAmount'] = $plan['TotalPremiumAmount'];
                            $platinumPlansOtherForView[$key]['PlanContent'] = $plan['PlanContent'];
                            $platinumPlansOtherForView[$key]['MinAge'] = $plan['PlanPricingBreakdown']['PricingBreakdown']['MinAge'];
                            $platinumPlansOtherForView[$key]['MaxAge'] = $plan['PlanPricingBreakdown']['PricingBreakdown']['MaxAge'];
                            $platinumPlansOtherForView[$key]['Gender'] = $gender;
                            $platinumPlansOtherForView[$key]['BaseInsurancePrice'] = $plan['PlanPricingBreakdown']['PricingBreakdown']['PremiumBreakdown']['PremiumCharges']['Charges']['Charge'][0]['AmountValue'];
                            $platinumPlansOtherForView[$key]['VATDetails']['Amount'] = $plan['PlanPricingBreakdown']['PricingBreakdown']['PremiumBreakdown']['PremiumCharges']['Charges']['Charge'][1]['AmountValue'];
                            $platinumPlansOtherForView[$key]['VATDetails']['Percentage'] = $plan['PlanPricingBreakdown']['PricingBreakdown']['PremiumBreakdown']['PremiumCharges']['Charges']['Charge'][1]['PercentageValue'];
                        }

                        $sortedPlans = [
                            'perPassengerPlans' => [
                                'silverPlans' => $silverPlansPerPassenger,
                                'goldPlans' => $goldPlansPerPassenger,
                                'platinumPlans' => $platinumPlansPerPassenger
                            ],
                            'familyPlans' => [
                                'silverPlans' => $silverPlansOther,
                                'goldPlans' => $goldPlansOther,
                                'platinumPlans' => $platinumPlansOther
                            ]
                        ];
                        $sortedPlans = json_encode($sortedPlans, true);
                        $insertionRecord = $this->custom_db->insert_record('plan_retirement', array('message' => $sortedPlans));
                        $id = $insertionRecord['insert_id'];
                        //store in database later kun table ma haalney ho
                        // debug($platinumPlansPerPassengerForView);
                        // debug($platinumPlansOtherForView);
                        // die;

                        // plans for view
                        $sortedPlansforView = [
                            'id' => $id,
                            'perPassengerPlans' => [
                                'silverPlans' => $silverPlansPerPassengerForView,
                                'goldPlans' => $goldPlansPerPassengerForView,
                                'platinumPlans' => $platinumPlansPerPassengerForView
                            ],
                            'familyPlans' => [
                                'silverPlans' => $silverPlansOtherForView,
                                'goldPlans' => $goldPlansOtherForView,
                                'platinumPlans' => $platinumPlansOtherForView
                            ]
                        ];
                        // debug($sortedPlansforView);die;
                        $encodedPlans = json_encode($sortedPlansforView, true);
                        // debug($encodedPlans);die;
                        header('Content-Type: application/json');


                        echo $encodedPlans;

                        // debug($sortedPlans);die;

                        // // Output categorized data based on PlanPremiumChargeType
                        // echo "Gold Plans - Per Passenger:\n";
                        // foreach ($goldPlansPerPassenger as $plan) {
                        //     echo "Plan Code: " . $plan['PlanCode'] . "\n";
                        //     echo "Title: " . $plan['PlanTitle'] . "\n";
                        //     echo "Description: " . $plan['PlanDesc'] . "\n";
                        //     echo "Total Premium Amount: " . $plan['TotalPremiumAmount'] . "\n";
                        //     echo "Plan Type: " . $plan['PlanType'] . "\n\n";
                        // }

                        // echo "Gold Plans - Other:\n";
                        // foreach ($goldPlansOther as $plan) {
                        //     echo "Plan Code: " . $plan['PlanCode'] . "\n";
                        //     echo "Title: " . $plan['PlanTitle'] . "\n";
                        //     echo "Description: " . $plan['PlanDesc'] . "\n";
                        //     echo "Total Premium Amount: " . $plan['TotalPremiumAmount'] . "\n";
                        //     echo "Plan Type: " . $plan['PlanType'] . "\n\n";
                        // }

                        // echo "Silver Plans - Per Passenger:\n";
                        // foreach ($silverPlansPerPassenger as $plan) {
                        //     echo "Plan Code: " . $plan['PlanCode'] . "\n";
                        //     echo "Title: " . $plan['PlanTitle'] . "\n";
                        //     echo "Description: " . $plan['PlanDesc'] . "\n";
                        //     echo "Total Premium Amount: " . $plan['TotalPremiumAmount'] . "\n";
                        //     echo "Plan Type: " . $plan['PlanType'] . "\n\n";
                        // }

                        // echo "Silver Plans - Other:\n";
                        // foreach ($silverPlansOther as $plan) {
                        //     echo "Plan Code: " . $plan['PlanCode'] . "\n";
                        //     echo "Title: " . $plan['PlanTitle'] . "\n";
                        //     echo "Description: " . $plan['PlanDesc'] . "\n";
                        //     echo "Total Premium Amount: " . $plan['TotalPremiumAmount'] . "\n";
                        //     echo "Plan Type: " . $plan['PlanType'] . "\n\n";
                        // }

                        //to here
                        // debug($silverPlans);die;
                        // $xml_string = simplexml_load_string($xmlPart);
                        // var_dump($xml_string);
                        // die;
                        //  $testFile = fopen("newfile.xml", "w") or die("Unable to open file!");
                        //     fwrite($testFile, $xmlPart);
                        //     fclose($testFile);

                        // die("what");

                        //     $testFile = fopen("newfile.xml", "w") or die("Unable to open file!");
                        //     fwrite($testFile, $xmlPart);
                        //     fclose($testFile);

                        //                         $xmlResponse = $xmlPart;

                        // Assuming $rawApiResponse contains the raw API response

                        // Split the raw API response into individual plan entries

                        // debug($silverPlans);
                        // debug($platinumPlans);
                        // debug($cancellationPlans);
                        // die;
                        // Now you have arrays containing plan details divided into categories
                        // You can further process or display these arrays as needed
                        // For example, you can iterate through each array and extract specific details for display


                    } else {


                        die('Request failed: HTTP status code: ' . $resultStatus);
                    }
                }
                curl_close($ch);
            } else {
                // segment details not proper
            }
        } else {
            // no search id unauth access
        }
    }

    function xml2array($xmlStr, $get_attributes = 1, $priority = 'tag')
    {

        $contents = "";
        if (!function_exists('xml_parser_create')) {
            return array();
        }
        $parser = xml_parser_create('');

        xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8");
        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
        xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
        xml_parse_into_struct($parser, trim($xmlStr), $xml_values);
        xml_parser_free($parser);
        if (!$xml_values)
            return;
        $xml_array = array();
        $parents = array();
        $opened_tags = array();
        $arr = array();
        $current = &$xml_array;
        $repeated_tag_index = array();
        foreach ($xml_values as $data) {
            unset($attributes, $value);

            extract($data);
            $result = array();
            $attributes_data = array();
            if (isset($value)) {
                if ($priority == 'tag')
                    $result = $value;
                else
                    $result['value'] = $value;
            }
            if (isset($attributes) and $get_attributes) {
                foreach ($attributes as $attr => $val) {
                    if ($priority == 'tag')
                        $attributes_data[$attr] = $val;
                    else
                        $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
                }
            }
            if ($type == "open") {
                $parent[$level - 1] = &$current;
                if (!is_array($current) or (!in_array($tag, array_keys($current)))) {
                    $current[$tag] = $result;
                    if ($attributes_data)
                        $current[$tag . '_attr'] = $attributes_data;
                    $repeated_tag_index[$tag . '_' . $level] = 1;
                    $current = &$current[$tag];
                } else {
                    if (isset($current[$tag][0])) {
                        $current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
                        $repeated_tag_index[$tag . '_' . $level]++;
                    } else {
                        $current[$tag] = array(
                            $current[$tag],
                            $result
                        );
                        $repeated_tag_index[$tag . '_' . $level] = 2;
                        if (isset($current[$tag . '_attr'])) {
                            $current[$tag]['0_attr'] = $current[$tag . '_attr'];
                            unset($current[$tag . '_attr']);
                        }
                    }
                    $last_item_index = $repeated_tag_index[$tag . '_' . $level] - 1;
                    $current = &$current[$tag][$last_item_index];
                }
            } elseif ($type == "complete") {
                if (!isset($current[$tag])) {
                    $current[$tag] = $result;
                    $repeated_tag_index[$tag . '_' . $level] = 1;
                    if ($priority == 'tag' and $attributes_data)
                        $current[$tag . '_attr'] = $attributes_data;
                } else {
                    if (isset($current[$tag][0]) and is_array($current[$tag])) {
                        $current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
                        if ($priority == 'tag' and $get_attributes and $attributes_data) {
                            $current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
                        }
                        $repeated_tag_index[$tag . '_' . $level]++;
                    } else {
                        $current[$tag] = array(
                            $current[$tag],
                            $result
                        );
                        $repeated_tag_index[$tag . '_' . $level] = 1;
                        if ($priority == 'tag' and $get_attributes) {
                            if (isset($current[$tag . '_attr'])) {
                                $current[$tag]['0_attr'] = $current[$tag . '_attr'];
                                unset($current[$tag . '_attr']);
                            }
                            if ($attributes_data) {
                                $current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
                            }
                        }
                        $repeated_tag_index[$tag . '_' . $level]++; //0 and 1 index is already taken
                    }
                }
            } elseif ($type == 'close') {
                $current = &$parent[$level - 1];
            }
        }
        //echo "<pre>"; print_r($xml_array); echo "</pre>";  die();
        return ($xml_array);
    }

    public function parse_soap_response($soap_response)
    {
        // Load XML parser library
        // $this->load->library('xmlparser');

        // Parse the SOAP response into an array
        //     $response_array = $this->xmlparser->parse($soap_response);
        // debug($response_array);die;
        // Extract plan information according to categories
        $plans = array();
        if (isset($response_array['soap:Envelope']['soap:Body']['GetAvailablePlansOTAWithRidersResponse']['GenericResponse']['AvailablePlans']['AvailablePlan'])) {
            $available_plans = $response_array['soap:Envelope']['soap:Body']['GetAvailablePlansOTAWithRidersResponse']['GenericResponse']['AvailablePlans']['AvailablePlan'];

            foreach ($available_plans as $plan) {
                // Extract plan details
                $plan_code = $plan['PlanCode'];
                $plan_title = $plan['PlanTitle'];
                $plan_desc = $plan['PlanDesc'];
                $premium_amount = $plan['TotalPremiumAmount'];

                // You can add more fields as needed

                // Store the plan details
                $plans[$plan_code] = array(
                    'title' => $plan_title,
                    'description' => $plan_desc,
                    'premium_amount' => $premium_amount,
                    // Add more fields here
                );
            }
        }

        return $plans;
    }
}
