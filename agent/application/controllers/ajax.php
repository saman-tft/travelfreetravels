<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// ------------------------------------------------------------------------
/**
 * Controller for all ajax activities
 *
 * @package    Provab
 * @subpackage ajax loaders
 * @author     Balu A<balu.provab@gmail.com>
 * @version    V1
 */
// ------------------------------------------------------------------------

class Ajax extends CI_Controller
{
    private $current_module;
    public function __construct()
    {
        parent::__construct();
        if (is_ajax() == false) {
            //$this->index();
        }
        ob_start();
        $this->load->model('flight_model');
        $this->load->model('car_model');
        $this->load->model('hotel_model');
        $this->load->model('hotels_model');
        $this->load->model('sightseeing_model');
        $this->load->model('transferv1_model');
        $this->load->model('transfer_model');
        $this->load->model('private_Management_Model');
        $this->current_module = $this->config->item('current_module');
    }

    /**
     * index page of application will be loaded here
     */
    function index()
    {
    }

    /**
     * get city list based on country
     * @param $country_id
     * @param $default_select
     */
    function get_city_list($country_id = 0, $default_select = 0)
    {
        if (intval($country_id) != 0) {
            $condition = array('country' => $country_id);
            $order_by = array('destination' => 'asc');
            $option_list = $this->custom_db->single_table_records('api_city_list', 'origin as k, destination as v', $condition, 0, 1000000, $order_by);
            if (valid_array($option_list['data'])) {
                echo get_compressed_output(generate_options($option_list['data'], array($default_select)));
                exit;
            }
        }
    }
    function get_activity_city_list()
    {
        $this->load->model('activity_model');
        $term = $this->input->get('term'); //retrieve the search term that autocomplete sends
        $term = trim(strip_tags($term));
        $data_list = $this->activity_model->get_activity_city_list($term);
        if (valid_array($data_list) == false) {
            $data_list = $this->activity_model->get_activity_city_list('');
        }
        $suggestion_list = array();
        $result = [];
        foreach ($data_list as $city_list) {
            $suggestion_list['label'] = $city_list['city_name'] . ', ' . $city_list['country_name'] . '';
            $suggestion_list['value'] = hotel_suggestion_value($city_list['city_name'], $city_list['country_name']);
            $suggestion_list['id'] = $city_list['origin'];
            if (empty($city_list['top_destination']) == false) {
                $suggestion_list['category'] = 'Top cities';
                $suggestion_list['type'] = 'Top cities';
            } else {
                $suggestion_list['category'] = 'Search Results';
                $suggestion_list['type'] = 'Search Results';
            }
            if (intval($city_list['cache_hotels_count']) > 0) {
                $suggestion_list['count'] = $city_list['cache_hotels_count'];
            } else {
                $suggestion_list['count'] = 0;
            }
            $result[] = $suggestion_list;
        }
        $this->output_compressed_data($result);
    }
    /**
     *
     * @param $continent_id
     * @param $default_select
     * @param $zone_id
     */
    function get_country_list($continent_id = array(), $default_select = 0, $zone_id = 0)
    {
        $this->load->model('general_model');
        $continent_id = urldecode($continent_id);
        if (intval($continent_id) != 0) {
            $option_list = $this->general_model->get_country_list($continent_id, $zone_id);
            if (valid_array($option_list['data'])) {
                echo get_compressed_output(generate_options($option_list['data'], array($default_select)));
            }
        }
    }
    function transfer_list($search_id = '', $offset = 0)
    {
        // error_reporting(E_ALL);
        // echo $search_id; die;
        $response['data'] = '';
        // $response['msg'] = '';
        // $response['status'] = FAILURE_STATUS;
        $search_data = $this->input->get();
        $safe_search_data = $this->transfer_model->get_safe_search_data($search_data['search_id']);
        $safe_search_data['filters'] = $search_data['filters'];
        // debug(HOTELBED_TRANSFER_BOOKING_SOURCE);exit;
        // debug($search_data);
        // echo '<pre>';
        // debug($safe_search_data);exit;
        // exit();
        $booking_sorce = force_multple_data_format($search_data);
        // debug($booking_sorce);exit;
        if (check_transfer_crs_active()) {
            $booking_sorce[] = array(
                'booking_source' => PROVAB_TRANSFER_SOURCE_CRS,
                'search_id' => $search_data['search_id'],
                'op' => $search_data['op']
            );
        }

        // debug($booking_sorce);exit;
        $limit = $this->config->item('transfer_page_limit');
        $raw_sightseeing_result = array();
        foreach ($booking_sorce as $search_params) {
            if ($search_params['op'] == 'load' && intval($search_params['search_id']) > 0 && isset($search_params['booking_source']) == true) {

                load_transfer_lib($search_params['booking_source']);
                // debug(load_transfer_lib($search_params['booking_source']));exit;
                switch ($search_params['booking_source']) {
                    case HOTELBED_TRANSFER_BOOKING_SOURCE:
                        $raw_sightseeing_result = $this->transfer_lib->transfer_search_data(abs($search_params['search_id']));
                        // debug($raw_sightseeing_result);exit();
                        break;
                    case PROVAB_TRANSFER_SOURCE_CRS:
                        // $raw_sightseeing_result_detl = $raw_sightseeing_result;
                        $raw_sightseeing_result = $this->transfer_lib->get_transfer_list_crs($safe_search_data, $raw_sightseeing_result);

                        // debug($raw_sightseeing_result);exit;
                        break;
                }
            }
        }

        // debug($raw_sightseeing_result);exit;
        if ($raw_sightseeing_result['status']) {
            //Converting API currency data to preferred currency
            $currency_obj = new Currency(array('module_type' => 'transfers', 'from' => HOTELBED_API_CURRENCY, 'to' => get_application_currency_preference()));
            // error_reporting(E_ALL);
            // debug($raw_sightseeing_result); die;
            $raw_sightseeing_result = $this->transfer_lib->search_data_in_preferred_currency($raw_sightseeing_result, $currency_obj, 'b2b');
            // debug($raw_sightseeing_result); die;
            //$raw_sightseeing_result;

            $currency_obj = new Currency(array('module_type' => 'transfers', 'from' => get_application_currency_preference(), 'to' => get_application_currency_preference()));
            //debug($currency_obj); die;
            $filters = array();

            //Update currency and filter summary appended
            if (isset($search_data['filters']) == true and valid_array($search_data['filters']) == true) {
                $filters = $search_data['filters'];
            } else {
                $filters = array();
            }
            // debug($filters);
            $raw_sightseeing_result['data'] = $this->transfer_lib->format_search_response($raw_sightseeing_result['data'], $currency_obj, $search_data['search_id'], 'b2b', $filters, $search_data['booking_source']);

            // debug($raw_sightseeing_result['data']); exit;


            $source_result_count = $raw_sightseeing_result['data']['source_result_count'];
            $filter_result_count = $raw_sightseeing_result['data']['filter_result_count'];

            //debug($raw_hotel_list);exit;
            if (intval($offset) == 0) {
                //Need filters only if the data is being loaded first time
                // debug($raw_sightseeing_result['data']);exit;



                $filters = $this->transfer_lib->filter_summary($raw_sightseeing_result['data']);
                // debug($filters);exit;
                // $agent_base_currency = agent_base_currency();
                // echo $agent_base_currency;
                // $currency_obj_m = new Currency(array('module_type' => 'transfers', 'from' => $product['currency'], 'to' => $agent_base_currency));
                //$strCurrency_m  = $currency_obj_m->get_currency($product['Price']['TotalDisplayFare'], true, false, true, false, 1);

                $response['filters'] = $filters['data'];
            }
            if ($response['filters']['p']['max'] == "") {
                $response['filters']['p']['max'] = $response['filters']['p']['min'];
            }
            // debug($response['filters']);
            $attr['search_id'] = abs($search_data['search_id']);
            // debug($raw_sightseeing_result['data']);exit;
            $response['data'] = get_compressed_output(
                $this->template->isolated_view('transfer/hotelbeds_transfer_search_result', array(
                    'currency_obj' => $currency_obj, 'raw_transfer_list' => $raw_sightseeing_result['data'],
                    'search_id' => $search_data['search_id'], 'booking_source' => $search_data['booking_source'],
                    'attr' => $attr,
                    'enquiry_origin' => $enquiry_origin,
                    'search_params' => $safe_search_data['data']
                ))
            );
            $response['status'] = SUCCESS_STATUS;
            $response['total_result_count'] = $source_result_count;
            $response['filter_result_count'] = $filter_result_count;
            $response['offset'] = $offset + $limit;


            // $response['data'] = get_compressed_output(
            //     $this->template->isolated_view('transfer/hotelbed_search_result', array('currency_obj' => $currency_obj, 'raw_transfer_list' => $raw_sightseeing_result['data'],
            //         'search_id' => $search_data['search_id'], 'booking_source' => $search_data['booking_source'],
            //         'attr' => $attr,
            //         'enquiry_origin' => $enquiry_origin,
            //         'search_params' => $safe_search_data['data']
            //     )));
            // $response['status'] = SUCCESS_STATUS;
            // $response['total_result_count'] = $source_result_count;
            // $response['filter_result_count'] = $filter_result_count;
            // $response['offset'] = $offset + $limit;


        } else {
            $response['status'] = FAILURE_STATUS;
        }
        // exit;
        // debug($response);exit;
        $this->output_compressed_data($response);
    }
    function get_airport_transfer_code_list()
    {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        $result = array();
        $this->load->model('hotel_model');
        $this->load->model('transfer_model');
        $term = $this->input->get('term'); //retrieve the search term that autocomplete sends
        $term = trim(strip_tags($term));

        $airport_data_list = $this->transfer_model->get_airport_list($term)->result();

        // debug($airport_data_list);exit();

        if (valid_array($airport_data_list) == false) {
            $airport_data_list = $this->transfer_model->get_airport_list('$term')->result();
        }

        foreach ($airport_data_list as $airport) {
            $airport_result['label'] = $airport->airport_name . ' (' . $airport->airport_city . ')';
            $airport_result['id'] = $airport->airport_code;

            $airport_result['transfer_type'] = "ProductTransferTerminal";

            $airport_result['category'] = '';
            $airport_result['type'] = '';
            array_push($result, $airport_result);
        }
        $data_list = $this->transfer_model->get_hotels_list($term)->result();
        // debug($result);exit();

        if (valid_array($data_list) == false) {
            $data_list = $this->transfer_model->get_hotels_list('')->result();
        }

        foreach ($data_list as $hotel) {
            $transfer_result['label'] = $hotel->hotel_name . ' (' . $hotel->hotel_city . ')';
            $transfer_result['id'] = $hotel->hotel_code;

            $transfer_result['transfer_type'] = "ProductTransferHotel";

            $transfer_result['category'] = '';
            $transfer_result['type'] = '';
            array_push($result, $transfer_result);
        }

        $this->output_compressed_data($result);
    }
    /**
     *Get Location List
     */
    function location_list($limit = AUTO_SUGGESTION_LIMIT)
    {
        $chars = $_GET['term'];
        $list = $this->general_model->get_location_list($chars, $limit);
        $temp_list = array();
        if (valid_array($list) == true) {
            foreach ($list as $k => $v) {
                $temp_list[] = array('id' => $k, 'label' => $v['name'], 'value' => $v['origin']);
            }
        }
        $this->output_compressed_data($temp_list);
    }

    /**
     *Get Location List
     */
    function city_list($limit = AUTO_SUGGESTION_LIMIT)
    {
        $chars = $_GET['term'];
        $list = $this->general_model->get_city_list($chars, $limit);
        $temp_list = array();
        if (valid_array($list) == true) {
            foreach ($list as $k => $v) {
                $temp_list[] = array('id' => $k, 'label' => $v['name'], 'value' => $v['origin']);
            }
        }
        $this->output_compressed_data($temp_list);
    }

    /**
     * Balu A
     * @param unknown_type $currency_origin origin of currency - default to USD
     */
    function get_currency_value($currency_origin = 0)
    {
        $data = $this->custom_db->single_table_records('currency_converter', 'value', array('id' => intval($currency_origin)));
        if (valid_array($data['data'])) {
            $response = $data['data'][0]['value'];
        } else {
            $response = 1;
        }
        header('Content-type:application/json');
        echo json_encode(array('value' => $response));
        exit;
    }

    /*
	 *
	 * Flight(Airport) auto suggest
	 *
	 */
    function get_airport_code_list()
    {
        $term = $this->input->get('term'); //retrieve the search term that autocomplete sends
        $term = trim(strip_tags($term));
        $result = array();
        $flagpath = DOMAIN_LAG_IMAGE_DIR . '/';

        $__airports = $this->flight_model->get_airport_list($term)->result();
        if (valid_array($__airports) == false) {
            $__airports = $this->flight_model->get_airport_list('')->result();
        }
        $airports = array();
        foreach ($__airports as $airport) {
            $airports['label'] = $airport->airport_city . ', ' . $airport->country . ' (' . $airport->airport_code . ')';
            $airports['value'] = $airport->airport_city . ' (' . $airport->airport_code . ')';
            $airports['id'] = $airport->origin;
            $airports['country_code'] = $flagpath . strtolower($airport->CountryCode) . '.png';
            if (empty($airport->top_destination) == false) {
                $airports['category'] = 'Top cities';
                $airports['type'] = 'Top cities';
            } else {
                $airports['category'] = 'Search Results';
                $airports['type'] = 'Search Results';
            }

            array_push($result, $airports);
        }
        $this->output_compressed_data($result);
    }
    function privatecar_list($offset = 0)
    {

        $response['data'] = '';
        $response['msg'] = '';
        $response['status'] = FAILURE_STATUS;
        $search_params = $this->input->get();
        // debug($search_params);die;
        $limit = $this->config->item('car_per_page_limit');
        // debug($search_params);exit;
        if ($search_params['op'] == 'load' && intval($search_params['search_id']) > 0 && isset($search_params['booking_source']) == true) {
            load_car_lib($search_params['booking_source']);
            switch ($search_params['booking_source']) {
                case PROVAB_CAR_BOOKING_SOURCE:
                    //getting search params from table
                    $safe_search_data = $this->car_model->get_safe_search_data($search_params['search_id']);
                    //Meaning hotels are loaded first time
                    $raw_car_list = $this->car_lib->get_car_list(abs($search_params['search_id']));
                    // debug($raw_car_list);
                    if ($raw_car_list['status']) {
                        //Converting API currency data to preferred currency

                        $currency_obj = new Currency(array('module_type' => 'car', 'from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
                        $raw_car_list = $this->car_lib->search_data_in_preferred_currency($raw_car_list, $currency_obj, $search_params['search_id']);

                        //Display 
                        $currency_obj = new Currency(array('module_type' => 'car', 'from' => get_application_currency_preference(), 'to' => get_application_currency_preference()));
                        // debug($currency_obj);exit;
                        //Update currency and filter summary appended
                        if (isset($search_params['filters']) == true and valid_array($search_params['filters']) == true) {
                            $filters = $search_params['filters'];
                        } else {
                            $filters = array();
                        }
                        //debug($raw_hotel_list);exit;
                        $raw_car_list['data'] = $this->car_lib->format_search_response($raw_car_list['data'], $currency_obj, $search_params['search_id'], 'b2b', $filters);
                        // debug($raw_car_list);exit;
                        $source_result_count = $raw_car_list['data']['source_result_count'];
                        $filter_result_count = $raw_car_list['data']['filter_result_count'];
                        //debug($raw_hotel_list);exit;
                        if (intval($offset) == 0) {
                            //Need filters only if the data is being loaded first time
                            $filters = $this->car_lib->filter_summary($raw_car_list['data']);
                            $response['filters'] = $filters['data'];
                        }
                        // debug($raw_car_list['data']);exit;
                        $raw_car_list['data'] = $this->car_lib->get_page_data($raw_car_list['data'], $offset, $limit);

                        $attr['search_id'] = abs($search_params['search_id']);


                        $response['data'] = get_compressed_output(
                            $this->template->isolated_view('car/car_search_result_page', array(
                                'currency_obj' => $currency_obj, 'raw_car_list' => $raw_car_list['data'],
                                'search_id' => $search_params['search_id'], 'booking_source' => $search_params['booking_source'],
                                'attr' => $attr,
                                'search_params' => $safe_search_data
                            ))
                        );
                        $response['status'] = SUCCESS_STATUS;
                        $response['total_result_count'] = $source_result_count;
                        $response['filter_result_count'] = $filter_result_count;
                        $response['offset'] = $offset + $limit;
                    }
                    break;
                case PROVAB_CAR_CRS_BOOKING_SOURCE:
                    //getting search params from table
                    $safe_search_data = $this->car_model->get_safe_search_data($search_params['search_id']);
                    //Meaning hotels are loaded first time
                    $raw_car_list = $this->car_lib->get_car_list(abs($search_params['search_id']));
                    // debug($raw_car_list);
                    if ($raw_car_list['status']) {
                        //Converting API currency data to preferred currency

                        $currency_obj = new Currency(array('module_type' => 'privatecar', 'from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
                        $raw_car_list = $this->car_lib->search_data_in_preferred_currency($raw_car_list, $currency_obj, $search_params['search_id']);

                        //Display 
                        $currency_obj = new Currency(array('module_type' => 'privatecar', 'from' => get_application_currency_preference(), 'to' => get_application_currency_preference()));
                        // debug($currency_obj);exit;
                        //Update currency and filter summary appended
                        if (isset($search_params['filters']) == true and valid_array($search_params['filters']) == true) {
                            $filters = $search_params['filters'];
                        } else {
                            $filters = array();
                        }
                        //debug($raw_hotel_list);exit;
                        $raw_car_list['data'] = $this->car_lib->format_search_response($raw_car_list['data'], $currency_obj, $search_params['search_id'], 'b2b', $filters);
                        // debug($raw_car_list);exit;
                        $source_result_count = $raw_car_list['data']['source_result_count'];
                        $filter_result_count = $raw_car_list['data']['filter_result_count'];
                        //debug($raw_hotel_list);exit;
                        if (intval($offset) == 0) {
                            //Need filters only if the data is being loaded first time
                            $filters = $this->car_lib->filter_summary($raw_car_list['data']);
                            $response['filters'] = $filters['data'];
                        }
                        // debug($raw_car_list['data']);exit;
                        $raw_car_list['data'] = $this->car_lib->get_page_data($raw_car_list['data'], $offset, $limit);

                        $attr['search_id'] = abs($search_params['search_id']);


                        $response['data'] = get_compressed_output(
                            $this->template->isolated_view('privatecar/car_search_result_page', array(
                                'currency_obj' => $currency_obj, 'raw_car_list' => $raw_car_list['data'],
                                'search_id' => $search_params['search_id'], 'booking_source' => $search_params['booking_source'],
                                'attr' => $attr,
                                'search_params' => $safe_search_data
                            ))
                        );
                        $response['status'] = SUCCESS_STATUS;
                        $response['total_result_count'] = $source_result_count;
                        $response['filter_result_count'] = $filter_result_count;
                        $response['offset'] = $offset + $limit;
                    }
                    break;
            }
        }
        $this->output_compressed_data($response);
    }
    /*
     *
     * Car(Airport) auto suggest
     *
     */
    function get_airport_city_list()
    {
        $term = $this->input->get('term'); //retrieve the search term that autocomplete sends
        $term = trim(strip_tags($term));
        $result = array();

        $__airports = $this->car_model->get_airport_list($term)->result();
        if (valid_array($__airports) == false) {
            $__airports = $this->car_model->get_airport_list('')->result();
        }
        // debug($__airports);exit;
        $airports = array();
        foreach ($__airports as $airport) {
            $airports['label'] = $airport->Airport_Name_EN . ',' . $airport->Country_Name_EN;
            // $airports['value'] = $airport->airport_city . ' (' . $airport->airport_code . ')';
            $airports['id'] = $airport->origin;
            $airports['airport_code'] = $airport->Airport_IATA;
            $airports['country_id'] = $airport->Country_ISO;
            $airports['category'] = 'Search Results';
            $airports['type'] = 'Search Results';
            array_push($result, $airports);
        }

        $city_list = $this->car_model->get_city_list($term)->result();
        if (valid_array($city_list) == false) {
            $city_list = $this->car_model->get_city_list('')->result();
        }
        foreach ($city_list as $city) { //debug($city_list);exit;
            if ($city->City_ID != "") {
                $city_result['label'] = $city->City_Name_EN . ' City/Downtown,' . $city->Country_Name_EN;
                $city_result['id'] = $city->origin;
                $city_result['airport_code'] = $city->Airport_IATA;
                $city_result['country_id'] = $city->Country_ISO;
                if (empty($city->top_destination) == false) {
                    $city_result['category'] = 'Top cities';
                    $city_result['type'] = 'Top cities';
                } else {
                    $city_result['category'] = 'Search Results';
                    $city_result['type'] = 'Search Results';
                }
                array_push($result, $city_result);
            }
        }
        // debug($result);exit;   
        $this->output_compressed_data($result);
    }
    /*
	 *
	 * Hotels City auto suggest
	 *
	 */
    function get_hotel_city_list()
    {
        $this->load->model('hotel_model');
        $term = $this->input->get('term'); //retrieve the search term that autocomplete sends
        $term = trim(strip_tags($term));
        load_hotel_lib(PROVAB_RESVOYAGE_BOOKING_SOURCE);
        $dataxml_list = $this->hotel_lib->get_destination($term);
        $data_list = $this->hotel_model->get_hotel_city_list($term);
        if (valid_array($data_list) == false) {
            $data_list = $this->hotel_model->get_hotel_city_list('');
        }
        // $data_list=array_merge($data_list,$dataxml_list);
        $suggestion_list = array();
        $result = array();
        foreach ($data_list as $city_list) {
            $suggestion_list['label'] = $city_list['city_name'] . ', ' . $city_list['country_name'] . '';
            $suggestion_list['value'] = hotel_suggestion_value($city_list['city_name'], $city_list['country_name'], $city_list['stateprovince']);
            $suggestion_list['id'] = $city_list['origin'];
            if (empty($city_list['top_destination']) == false) {
                $suggestion_list['category'] = 'Top cities';
                $suggestion_list['type'] = 'Top cities';
            } else {
                $suggestion_list['category'] = 'Search Results';
                $suggestion_list['type'] = 'Search Results';
            }
            if (intval($city_list['cache_hotels_count']) > 0) {
                $suggestion_list['count'] = $city_list['cache_hotels_count'];
            } else {
                $suggestion_list['count'] = 0;
            }
            $result[] = $suggestion_list;
        }
        $this->output_compressed_data($result);
    }

    /**
     * Auto Suggestion for bus stations
     */
    function bus_stations()
    {
        $this->load->model('bus_model');
        $term = $this->input->get('term'); //retrieve the search term that autocomplete sends
        $term = trim(strip_tags($term));
        $data_list = $this->bus_model->get_bus_station_list($term);
        if (valid_array($data_list) == false) {
            $data_list = $this->bus_model->get_bus_station_list('');
        }
        $suggestion_list = array();
        $result = array();
        foreach ($data_list as $city_list) {
            $suggestion_list['label'] = $city_list['name'];
            $suggestion_list['value'] = $city_list['name'];
            $suggestion_list['id'] = $city_list['origin'];
            if (empty($city_list['top_destination']) == false) {
                $suggestion_list['category'] = 'Top cities';
                $suggestion_list['type'] = 'Top cities';
            } else {
                $suggestion_list['category'] = 'Search Results';
                $suggestion_list['type'] = 'Search Results';
            }
            $result[] = $suggestion_list;
        }
        $this->output_compressed_data($result);
    }
    function hotel_list($offset = 0)
    {

        $response['data'] = '';
        $response['msg'] = '';
        $response['status'] = FAILURE_STATUS;
        $search_params = $this->input->get();
        $this->load->model('hotel_model');
        //debug($search_params);exit('list');
        $limit = $this->config->item('hotel_per_page_limit');
        $active_booking_source = $this->hotel_model->active_booking_source();
        $api_list = array();
        $crs_list = array();
        if ($search_params['op'] == 'load' && intval($search_params['search_id']) > 0 && isset($search_params['booking_source']) == true) {
            foreach ($active_booking_source as $key => $source) {
                $booking_source_hotel = $source['source_id'];
                //$booking_source_hotel = $search_params['booking_source'];
                // debug($booking_source_hotel );die;
                load_hotel_lib($booking_source_hotel);
                switch ($booking_source_hotel) {
                    case PROVAB_HOTEL_BOOKING_SOURCE:
                        //getting search params from table
                        $safe_search_data = $this->hotel_model->get_safe_search_data($search_params['search_id']);

                        //Meaning hotels are loaded first time
                        $raw_hotel_list = $this->hotel_lib->get_hotel_list(abs($search_params['search_id']), $search_params);
                        //debug($search_params);exit;
                        // if ($raw_hotel_list['status']) {
                        //Converting API currency data to preferred currency
                        $currency_obj = new Currency(array('module_type' => 'hotel', 'from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
                        $raw_hotel_list = $this->hotel_lib->search_data_in_preferred_currency($raw_hotel_list, $currency_obj, $search_params['search_id']);
                        // debug($raw_hotel_list);exit;
                        //Display 

                        for ($i = 0; $i < count($raw_hotel_list['data']['HotelSearchResult']['HotelResults']); $i++) {
                            $raw_hotel_list['data']['HotelSearchResult']['HotelResults'][$i]['booking_source'] = PROVAB_HOTEL_BOOKING_SOURCE;
                        }

                        $api_list = $raw_hotel_list['data']['HotelSearchResult']['HotelResults'];

                        //   debug($api_list);die;
                        $module = "hotel";
                        break;
                    case CRS_HOTEL_BOOKING_SOURCE:
                        // echo "asd";die;
                        $raw_hotel_list = $this->hotel_lib->get_agent_hotel_list(abs($search_params['search_id']));
                        // debug($raw_hotel_list);die('sudheer');
                        $currency_obj = new Currency(array('module_type' => 'hotel', 'from' => get_application_currency_preference, 'to' => get_application_currency_preference()));
                        $raw_hotel_list = $this->hotel_lib->search_data_in_preferred_currency($raw_hotel_list, $currency_obj);
                        $crs_list = $raw_hotel_list['data']['HotelSearchResult']['HotelResults'];

                        // load_hotel_lib(PROVAB_HOTEL_BOOKING_SOURCE);
                        break;
                }
            }
            load_hotel_lib($search_params['booking_source']);
            $raw_hotel_list['data']['HotelSearchResult']['HotelResults'] = array_merge($api_list, $crs_list);
            $currency_obj = new Currency(array('module_type' => 'hotel', 'from' => get_application_currency_preference(), 'to' => get_application_currency_preference()));
            //Update currency and filter summary appended
            if (isset($search_params['filters']) == true and valid_array($search_params['filters']) == true) {
                $filters = $search_params['filters'];
            } else {
                $filters = array();
            }
            //      debug($raw_hotel_list['data']['HotelSearchResult']['HotelResults']);die;
            array_multisort(array_column($raw_hotel_list['data']['HotelSearchResult']['HotelResults'], 'StarRating'), SORT_DESC, $raw_hotel_list['data']['HotelSearchResult']['HotelResults']);
            // debug($raw_hotel_list);exit;
            $raw_hotel_list['data'] = $this->hotel_lib->format_search_response($raw_hotel_list['data'], $currency_obj, $search_params['search_id'], 'b2b', $filters);





            $source_result_count = $raw_hotel_list['data']['source_result_count'];
            $filter_result_count = $raw_hotel_list['data']['filter_result_count'];
            //debug($raw_hotel_list);exit;
            if (intval($offset) == 0) {
                //Need filters only if the data is being loaded first time
                $filters = $this->hotel_lib->filter_summary($raw_hotel_list['data']);
                $response['filters'] = $filters['data'];
            }
            //debug($raw_hotel_list['data']);exit;
            // $raw_hotel_list['data'] = $this->hotel_lib->get_page_data($raw_hotel_list['data'], $offset, $limit);

            $attr['search_id'] = abs($search_params['search_id']);

            // debug($raw_hotel_list['data']);die;
            // debug($raw_hotel_list);exit;
            $response['data'] = get_compressed_output(
                $this->template->isolated_view('hotel/tbo/tbo_search_result', array(
                    'currency_obj' => $currency_obj, 'raw_hotel_list' => $raw_hotel_list['data'],
                    'search_id' => $search_params['search_id'], 'booking_source' => $search_params['booking_source'],
                    'attr' => $attr,
                    'search_params' => $safe_search_data
                ))
            );

            $response['page_reload'] = $raw_hotel_list['page_reload'];
            $response['total_result_count'] = $source_result_count;
            $response['filter_result_count'] = $filter_result_count;
            $response['offset'] = $offset + $limit;

            if (($raw_hotel_list['status'] == false && $raw_hotel_list['data']['refresh_flag'] == 1)) {
                $response['status'] = FAILURE_STATUS;
                $response['request_count'] = $raw_hotel_list['data']['refresh_flag'];
            } else {
                $response['status'] = SUCCESS_STATUS;
                $response['request_count'] = $raw_hotel_list['data']['refresh_flag'];
            }
        }

        $this->output_compressed_data($response);
    }



    /**
     * Load hotels from different source
     */
    function hotel_listold($offset = 0)
    {

        $response['data'] = '';
        $response['msg'] = '';
        $response['status'] = FAILURE_STATUS;
        $search_params = $this->input->get();
        $limit = $this->config->item('hotel_per_page_limit');

        if ($search_params['op'] == 'load' && intval($search_params['search_id']) > 0 && isset($search_params['booking_source']) == true) {
            load_hotel_lib($search_params['booking_source']);
            switch ($search_params['booking_source']) {
                case PROVAB_HOTEL_BOOKING_SOURCE:
                    //Meaning hotels are loaded first time
                    $raw_hotel_list = $this->hotel_lib->get_hotel_list(abs($search_params['search_id']));

                    if ($raw_hotel_list['status']) {
                        //Converting API currency data to preferred currency
                        $currency_obj = new Currency(array('module_type' => 'hotel', 'from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
                        $raw_hotel_list = $this->hotel_lib->search_data_in_preferred_currency($raw_hotel_list, $currency_obj, $search_params['search_id']);

                        //Display 
                        $currency_obj = new Currency(array('module_type' => 'hotel', 'from' => get_application_currency_preference(), 'to' => get_application_currency_preference()));
                        //Update currency and filter summary appended
                        if (isset($search_params['filters']) == true and valid_array($search_params['filters']) == true) {
                            $filters = $search_params['filters'];
                        } else {
                            $filters = array();
                        }


                        $raw_hotel_list['data'] = $this->hotel_lib->format_search_response($raw_hotel_list['data'], $currency_obj, $search_params['search_id'], 'b2b', $filters);
                        // debug("ff");exit;
                        $source_result_count = $raw_hotel_list['data']['source_result_count'];
                        $filter_result_count = $raw_hotel_list['data']['filter_result_count'];

                        if (intval($offset) == 0) {
                            //Need filters only if the data is being loaded first time
                            $filters = $this->hotel_lib->filter_summary($raw_hotel_list['data']);
                            $response['filters'] = $filters['data'];
                        }
                        // debug("ff");exit;
                        $raw_hotel_list['data'] = $this->hotel_lib->get_page_data($raw_hotel_list['data'], $offset, $limit);

                        $attr['search_id'] = abs($search_params['search_id']);
                        //debug($raw_hotel_list);exit;

                        $response['data'] = get_compressed_output(
                            $this->template->isolated_view(
                                'hotel/tbo/tbo_search_result',
                                array(
                                    'currency_obj' => $currency_obj, 'raw_hotel_list' => $raw_hotel_list['data'],
                                    'search_id' => $search_params['search_id'], 'booking_source' => $search_params['booking_source'],
                                    'attr' => $attr,
                                    'search_params' => $safe_search_data
                                )
                            )
                        );
                        $response['status'] = SUCCESS_STATUS;
                        $response['total_result_count'] = $source_result_count;
                        $response['filter_result_count'] = $filter_result_count;
                        $response['offset'] = $offset + $limit;
                    }
                    break;
            }
        }
        $this->output_compressed_data($response);
    }
    /** Anitha G
     * Load car from different source
     */


    /**
     * Load hotels from different source
     */
    function car_list($offset = 0)
    {

        $response['data'] = '';
        $response['msg'] = '';
        $response['status'] = FAILURE_STATUS;
        $search_params = $this->input->get();

        $limit = $this->config->item('car_per_page_limit');
        // debug($search_params);exit;
        if ($search_params['op'] == 'load' && intval($search_params['search_id']) > 0 && isset($search_params['booking_source']) == true) {
            load_car_lib($search_params['booking_source']);
            switch ($search_params['booking_source']) {
                case PROVAB_CAR_BOOKING_SOURCE:
                    //getting search params from table
                    $safe_search_data = $this->car_model->get_safe_search_data($search_params['search_id']);
                    //Meaning hotels are loaded first time
                    $raw_car_list = $this->car_lib->get_car_list(abs($search_params['search_id']));
                    // debug($raw_car_list);
                    if ($raw_car_list['status']) {
                        //Converting API currency data to preferred currency

                        $currency_obj = new Currency(array('module_type' => 'car', 'from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
                        $raw_car_list = $this->car_lib->search_data_in_preferred_currency($raw_car_list, $currency_obj, $search_params['search_id']);

                        //Display 
                        $currency_obj = new Currency(array('module_type' => 'car', 'from' => get_application_currency_preference(), 'to' => get_application_currency_preference()));
                        // debug($currency_obj);exit;
                        //Update currency and filter summary appended
                        if (isset($search_params['filters']) == true and valid_array($search_params['filters']) == true) {
                            $filters = $search_params['filters'];
                        } else {
                            $filters = array();
                        }
                        //debug($raw_hotel_list);exit;
                        $raw_car_list['data'] = $this->car_lib->format_search_response($raw_car_list['data'], $currency_obj, $search_params['search_id'], 'b2b', $filters);
                        // debug($raw_car_list);exit;
                        $source_result_count = $raw_car_list['data']['source_result_count'];
                        $filter_result_count = $raw_car_list['data']['filter_result_count'];
                        //debug($raw_hotel_list);exit;
                        if (intval($offset) == 0) {
                            //Need filters only if the data is being loaded first time
                            $filters = $this->car_lib->filter_summary($raw_car_list['data']);
                            $response['filters'] = $filters['data'];
                        }
                        // debug($raw_car_list['data']);exit;
                        $raw_car_list['data'] = $this->car_lib->get_page_data($raw_car_list['data'], $offset, $limit);

                        $attr['search_id'] = abs($search_params['search_id']);


                        $response['data'] = get_compressed_output(
                            $this->template->isolated_view('car/car_search_result_page', array(
                                'currency_obj' => $currency_obj, 'raw_car_list' => $raw_car_list['data'],
                                'search_id' => $search_params['search_id'], 'booking_source' => $search_params['booking_source'],
                                'attr' => $attr,
                                'search_params' => $safe_search_data
                            ))
                        );
                        $response['status'] = SUCCESS_STATUS;
                        $response['total_result_count'] = $source_result_count;
                        $response['filter_result_count'] = $filter_result_count;
                        $response['offset'] = $offset + $limit;
                    }
                    break;
            }
        }
        $this->output_compressed_data($response);
    }
    /**
     * Compress and output data
     * @param array $data
     */
    private function output_compressed_data($data)
    {
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
     * Compress and output data
     * @param array $data
     */
    private function output_compressed_data_flight($data)
    {

        while (ob_get_level() > 0) {
            ob_end_clean();
        }
        ob_start("ob_gzhandler");
        ini_set("memory_limit", "-1");
        set_time_limit(0);
        header('Content-type:application/json');
        echo  json_encode($data, JSON_UNESCAPED_SLASHES);
        ob_end_flush();
        exit;
    }
    /**
     * Get Sightsseeing Category List
     */
    function get_ss_category_list()
    {
        $get_params = $this->input->get();
        if ($get_params) {
            if ($get_params['city_id']) {

                load_sightseen_lib(PROVAB_SIGHTSEEN_BOOKING_SOURCE);
                $select_cate_id = 0;
                if (isset($get_params['Select_cate_id'])) {
                    $select_cate_id = $get_params['Select_cate_id'];
                } else {
                    $get_params['Select_cate_id'] = 0;
                }

                $category_list = $this->sightseeing_lib->get_category_list($get_params);
                if ($category_list['status'] == SUCCESS_STATUS) {

                    $cate_response = $this->sightseeing_lib->format_category_response($category_list['data']['CategoryList'], $select_cate_id);

                    if ($cate_response['status'] == SUCCESS_STATUS) {
                        echo json_encode($cate_response['data']);
                        exit;
                    }
                } else {
                    echo "0";
                    exit;
                }
            } else {
                echo "0";
                exit;
            }
        } else {
            echo "0";
            exit;
        }
    }

    public function activity_list($offset = 0)
    {

        $search_params = $this->input->get();
        //   debug($search_params); exit;
        $safe_search_data = $this->sightseeing_model->get_safe_search_data($search_params['search_id'], META_SIGHTSEEING_COURSE);
        // debug($safe_search_data);exit;

        $booking_sorce = force_multple_data_format($search_params);
        //debug($booking_sorce);exit;

        // debug($booking_sorce);exit;
        $limit = $this->config->item('sightseeing_page_limit');
        $raw_sightseeing_result = array();
        // debug($booking_sorce);exit;

        foreach ($booking_sorce as $search_data) {
            // debug($raw_sightseeing_result);

            if ($search_data['op'] == 'load' && intval($search_data['search_id']) > 0 && isset($search_data['booking_source']) == true) {
                $search_data['booking_source'] = PROVAB_SIGHTSEEN_SOURCE_CRS;
                load_sightseen_lib($search_data['booking_source']);
                switch ($search_data['booking_source']) {

                    case HOTELBED_ACTIVITIES_BOOKING_SOURCE:
                        debug(2);
                        exit;
                        // debug($raw_sightseeing_result);              
                        // $raw_sightseeing_result = $this->sightseeing_lib->get_sightseeing_list($safe_search_data);
                        $raw_sightseeing_result = $this->sightseeing_lib->get_activity_list($raw_sightseeing_result, $safe_search_data, $search_data, $module = 'b2b');
                        // debug($raw_sightseeing_result); exit;
                        break;
                    case PROVAB_SIGHTSEEN_SOURCE_CRS:

                        $raw_sightseeing_result = $this->sightseeing_lib->get_sightseeing_list($safe_search_data, $raw_sightseeing_result);
                        //debug($raw_sightseeing_result);die;
                        //               if($_SERVER['REMOTE_ADDR'] == "1.39.148.50")
                        // {
                        // 	debug($raw_sightseeing_result);die;
                        // }
                        // debug($raw_sightseeing_result);die;
                        // debug("ggg");

                        break;
                }
            }
        }
        //  debug($raw_sightseeing_result);exit;
        if ($raw_sightseeing_result['status']) {
            //Converting API currency data to preferred currency
            $currency_obj = new Currency(array('module_type' => 'sightseeing', 'from' => HOTELBED_API_CURRENCY, 'to' => get_application_currency_preference()));
            // error_reporting(E_ALL);
            //debug($currency_obj); die;
            $raw_sightseeing_result = $this->sightseeing_lib->search_data_in_preferred_currency($raw_sightseeing_result, $currency_obj, 'b2c');

            //$raw_sightseeing_result;
            // debug($raw_sightseeing_result);exit;
            $currency_obj = new Currency(array('module_type' => 'sightseeing', 'from' => get_application_currency_preference(), 'to' => get_application_currency_preference()));
            //debug($currency_obj); die;
            $filters = array();

            //Update currency and filter summary appended
            if (isset($search_params['filters']) == true and valid_array($search_params['filters']) == true) {
                $filters = $search_params['filters'];
            } else {
                $filters = array();
            }
            $raw_sightseeing_result['data'] = $this->sightseeing_lib->format_search_response($raw_sightseeing_result['data'], $currency_obj, $search_params['search_id'], 'b2b', $filters);

            // debug($raw_sightseeing_result['data']); exit;


            $source_result_count = $raw_sightseeing_result['data']['source_result_count'];
            $filter_result_count = $raw_sightseeing_result['data']['filter_result_count'];

            //debug($raw_hotel_list);exit;
            if (intval($offset) == 0) {
                //Need filters only if the data is being loaded first time
                $filters = $this->sightseeing_lib->filter_summary($raw_sightseeing_result['data']);
                $response['filters'] = $filters['data'];
            }
            if ($response['filters']['p']['max'] == "") {
                $response['filters']['p']['max'] = $response['filters']['p']['min'];
            }
            $attr['search_id'] = abs($search_params['search_id']);
            // debug($raw_sightseeing_result);exit;
            $response['data'] = get_compressed_output(
                $this->template->isolated_view('activity/hotelbed/hotelbed_search_result', array(
                    'currency_obj' => $currency_obj, 'raw_sightseeing_list' => $raw_sightseeing_result['data'],
                    'search_id' => $search_params['search_id'], 'booking_source' => $search_params['booking_source'],
                    'attr' => $attr,
                    'enquiry_origin' => $enquiry_origin,
                    'search_params' => $safe_search_data['data']
                ))
            );
            $response['status'] = SUCCESS_STATUS;
            $response['total_result_count'] = $source_result_count;
            $response['filter_result_count'] = $filter_result_count;
            $response['offset'] = $offset + $limit;
        } else {
            $response['status'] = FAILURE_STATUS;
        }

        $this->output_compressed_data($response);
    }
    /**
     *Elavarasi Get Sightseeing product list
     */
    public function sightseeing_list($offset = 0)
    {
        $search_params = $this->input->get();
        // debug($search_params);
        // exit;
        $safe_search_data = $this->sightseeing_model->get_safe_search_data($search_params['search_id'], META_SIGHTSEEING_COURSE);

        $limit = $this->config->item('sightseeing_page_limit');

        if ($search_params['op'] == 'load' && intval($search_params['search_id']) > 0 && isset($search_params['booking_source']) == true) {
            load_sightseen_lib($search_params['booking_source']);
            switch ($search_params['booking_source']) {

                case PROVAB_SIGHTSEEN_BOOKING_SOURCE:
                    if (isset($search_params['cate_id'])) {
                        $category_id = $search_params['cate_id'];
                    } else {
                        // if($safe_search_data['data']['category_id']){
                        //     $category_id = $safe_search_data['data']['category_id'];        
                        // }else{

                        // }
                        $category_id = 0;
                    }
                    if (isset($search_params['sub_cate'])) {
                        $sub_cate_id = $search_params['sub_cate'];
                    } else {
                        $sub_cate_id = 0;
                    }
                    if (isset($search_params['price_sort'])) {
                        $price_sort = $search_params['price_sort'];
                    } else {
                        $price_sort = '';
                    }
                    if (isset($search_params['tour_name'])) {
                        $tour_name = $search_params['tour_name'];
                    } else {
                        $tour_name = '';
                    }
                    if (isset($search_params['action'])) {
                        if ($search_params['action'] == 'reset') {
                            $category_id = 0;
                            $sub_cate_id = 0;
                            $price_sort = '';
                            $tour_name = '';
                            //  $safe_search_data['category_id'] = 0;
                        }
                    }

                    $search_data['category_id'] = $category_id;
                    $search_data['sub_cate_id'] = $sub_cate_id;
                    $search_data['price_sort'] = $price_sort;
                    $search_data['tour_name'] = $tour_name;
                    $raw_sightseeing_result = $this->sightseeing_lib->get_sightseeing_list($safe_search_data, $search_data);

                    if ($raw_sightseeing_result['status']) {
                        //Converting API currency data to preferred currency
                        $currency_obj = new Currency(array('module_type' => 'sightseeing', 'from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
                        $raw_sightseeing_result = $this->sightseeing_lib->search_data_in_preferred_currency($raw_sightseeing_result, $currency_obj, 'b2b');


                        //Display 
                        $currency_obj = new Currency(array('module_type' => 'sightseeing', 'from' => get_application_currency_preference(), 'to' => get_application_currency_preference()));


                        //Update currency and filter summary appended
                        if (isset($search_params['filters']) == true and valid_array($search_params['filters']) == true) {
                            $filters = $search_params['filters'];
                        } else {
                            $filters = array();
                        }
                        //debug($raw_hotel_list);exit;
                        $raw_sightseeing_result['data'] = $this->sightseeing_lib->format_search_response($raw_sightseeing_result['data'], $currency_obj, $search_params['search_id'], 'b2b', $filters);


                        $source_result_count = $raw_sightseeing_result['data']['source_result_count'];
                        $filter_result_count = $raw_sightseeing_result['data']['filter_result_count'];
                        //debug($raw_hotel_list);exit;
                        if (intval($offset) == 0) {
                            //Need filters only if the data is being loaded first time
                            $filters = $this->sightseeing_lib->filter_summary($raw_sightseeing_result['data']);
                            $response['filters'] = $filters['data'];
                        }
                        //debug($raw_hotel_list['data']);exit;

                        // $raw_sightseeing_result['data'] = $this->sightseeing_lib->get_page_data($raw_sightseeing_result['data'], $offset, $limit);

                        $attr['search_id'] = abs($search_params['search_id']);
                        // debug($raw_sightseeing_result);exit;
                        $response['data'] = get_compressed_output(
                            $this->template->isolated_view('sightseeing/viator/viator_search_result', array(
                                'currency_obj' => $currency_obj, 'raw_sightseeing_list' => $raw_sightseeing_result['data'],
                                'search_id' => $search_params['search_id'], 'booking_source' => $search_params['booking_source'],
                                'attr' => $attr,
                                'search_params' => $safe_search_data['data']
                            ))
                        );
                        $response['status'] = SUCCESS_STATUS;
                        $response['total_result_count'] = $source_result_count;
                        $response['filter_result_count'] = $filter_result_count;
                        $response['offset'] = $offset + $limit;
                    } else {
                        $response['status'] = FAILURE_STATUS;
                    }
                    break;
            }
        }
        $this->output_compressed_data($response);
    }
    public function transferv1_list($offset = 0)
    {

        $search_params = $this->input->get();
        $search_get = $this->input->get();
        // debug($safe_search_data);exit;   
        $safe_search_data = $this->transferv1_model->get_safe_search_data($search_params['search_id'], META_TRANSFERV1_COURSE);

        $booking_sorce = force_multple_data_format($search_params);
        if (check_transfer_crs_active()) {
            $booking_sorce[] = array(
                'booking_source' => PROVAB_TRANSFERV1_SOURCE_CRS,
                'search_id' => $search_params['search_id'],
                'op' => $search_params['op']
            );
        }

        $limit = $this->config->item('transferv1_page_limit');
        $raw_sightseeing_result = array();
        // echo PROVAB_TRANSFERV1_SOURCE_CRS;
        // debug($booking_sorce);die;




        $search_params['booking_source'] = $search_get['booking_source'];
        load_transferv1_lib($search_params['booking_source']);
        // echo $search_params['booking_source'];exit;
        switch ($search_params['booking_source']) {

            case PROVAB_TRANSFERV1_BOOKING_SOURCE:
                // debug($i);
                $raw_sightseeing_result = $this->transferv1_lib->get_transfer_list($safe_search_data);
                $currency_obj = new Currency(array('module_type' => 'transferv1', 'from' => get_api_data_currency(), 'to' => get_application_currency_preference()));

                $raw_sightseeing_result = $this->transferv1_lib->search_data_in_preferred_currency($raw_sightseeing_result, $currency_obj, 'b2c');



                break;

            case PROVAB_TRANSFERV1_SOURCE_CRS:
                // echo "string";exit('sudheer');               

                // $currency_obj_crs = new Currency(array('module_type' => 'transferv1', 'from' => ADMIN_BASE_CURRENCY_STATIC, 'to' => get_application_currency_preference()));
                $raw_sightseeing_result = array();
                $raw_sightseeing_result = $this->transferv1_lib_crs->get_transfer_list_crs($safe_search_data, $raw_sightseeing_result);

                $currency_obj = new Currency(array('module_type' => 'transferv1', 'from' => get_application_currency_preference(), 'to' => get_application_currency_preference()));
                load_transferv1_lib(PROVAB_TRANSFERV1_BOOKING_SOURCE);
                $raw_sightseeing_result = $this->transferv1_lib->search_data_in_preferred_currency($raw_sightseeing_result, $currency_obj, 'b2b');
                // echo "string";exit;    
                break;
        }



        if ($raw_sightseeing_result['status'] == 1 ||  $raw_sightseeing_result['status'] == 1) {
            $raw_sightseeing_result['status'] = 1;
        } else {
            $raw_sightseeing_result['status'] = 0;
        }
        //ini_set('display_errors', 1);
        //ini_set('display_startup_errors', 1);
        //error_reporting(E_ALL);debug($raw_sightseeing_result);   exit(); 
        //$raw_sightseeing_result['data']['TransferSearchResult']['TransferResults'] =$raw_sightseeing_result_api['data']['TransferSearchResult']['TransferResults'],$raw_sightseeing_result_crs['data']['TransferSearchResult']['TransferResults']);

        // debug($raw_sightseeing_result);exit;
        if ($raw_sightseeing_result['status']) {
            // echo "string";exit();
            //Converting API currency data to preferred currency
            // $currency_obj = new Currency(array('module_type' => 'transferv1', 'from' => get_api_data_currency(), 'to' => get_application_currency_preference()));

            // $raw_sightseeing_result = $this->transferv1_lib->search_data_in_preferred_currency($raw_sightseeing_result, $currency_obj,'b2b');



            // debug($raw_sightseeing_result);

            //Display 
            $currency_obj = new Currency(array('module_type' => 'transferv1', 'from' => get_application_currency_preference(), 'to' => get_application_currency_preference()));
            // debug($currency_obj);exit;
            $filters = array();
            //Update currency and filter summary appended
            if (isset($search_params['filters']) == true and valid_array($search_params['filters']) == true) {
                $filters = $search_params['filters'];
            } else {
                $filters = array();
            }
            // debug($this->transferv1_lib);exit;
            $raw_sightseeing_result['data'] = $this->transferv1_lib->format_search_response($raw_sightseeing_result['data'], $currency_obj, $search_params['search_id'], 'b2b', $filters);
            // debug($raw_sightseeing_result);exit;
            $source_result_count = $raw_sightseeing_result['data']['source_result_count'];
            $filter_result_count = $raw_sightseeing_result['data']['filter_result_count'];
            //debug($raw_hotel_list);exit;
            if (intval($offset) == 0) {
                //Need filters only if the data is being loaded first time
                $filters = $this->transferv1_lib->filter_summary($raw_sightseeing_result['data']);
                $response['filters'] = $filters['data'];
            }

            $attr['search_id'] = abs($search_params['search_id']);
            // echo "string";exit;
            $response['data'] = get_compressed_output(
                $this->template->isolated_view('transferv1/viator/viator_search_result', array(
                    'currency_obj' => $currency_obj, 'raw_sightseeing_list' => $raw_sightseeing_result['data'],
                    'search_id' => $search_params['search_id'], 'booking_source' => $search_params['booking_source'],
                    'attr' => $attr,
                    'search_params' => $safe_search_data['data']
                ))
            );
            // debug($response);exit;
            $response['status'] = SUCCESS_STATUS;
            $response['total_result_count'] = $source_result_count;
            $response['filter_result_count'] = $filter_result_count;
            $response['offset'] = $offset + $limit;
        } else {
            $response['status'] = FAILURE_STATUS;
        }

        // debug($response);exit();

        $this->output_compressed_data($response);
    }
    /**
     *Elavarasi Get Transfer product list
     */
    public function transferv1_listold($offset = 0)
    {
        $search_params = $this->input->get();
        // debug($search_params);
        // exit;
        $safe_search_data = $this->transferv1_model->get_safe_search_data($search_params['search_id'], META_TRANSFERV1_COURSE);

        $limit = $this->config->item('transferv1_page_limit');

        if ($search_params['op'] == 'load' && intval($search_params['search_id']) > 0 && isset($search_params['booking_source']) == true) {
            load_transferv1_lib($search_params['booking_source']);

            switch ($search_params['booking_source']) {

                case PROVAB_TRANSFERV1_BOOKING_SOURCE:

                    $raw_sightseeing_result = $this->transferv1_lib->get_transfer_list($safe_search_data);
                    // debug($raw_sightseeing_result);exit;
                    if ($raw_sightseeing_result['status']) {
                        //Converting API currency data to preferred currency
                        $currency_obj = new Currency(array('module_type' => 'transferv1', 'from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
                        $raw_sightseeing_result = $this->transferv1_lib->search_data_in_preferred_currency($raw_sightseeing_result, $currency_obj, 'b2b');

                        // debug($raw_sightseeing_result);
                        // exit;
                        //Display 
                        $currency_obj = new Currency(array('module_type' => 'transferv1', 'from' => get_application_currency_preference(), 'to' => get_application_currency_preference()));

                        $filters = array();

                        //Update currency and filter summary appended
                        if (isset($search_params['filters']) == true and valid_array($search_params['filters']) == true) {
                            $filters = $search_params['filters'];
                        } else {
                            $filters = array();
                        }
                        //debug($raw_hotel_list);exit;
                        $raw_sightseeing_result['data'] = $this->transferv1_lib->format_search_response($raw_sightseeing_result['data'], $currency_obj, $search_params['search_id'], 'b2b', $filters);
                        // debug($raw_sightseeing_result['data']);exit;

                        $source_result_count = $raw_sightseeing_result['data']['source_result_count'];
                        $filter_result_count = $raw_sightseeing_result['data']['filter_result_count'];
                        //debug($raw_hotel_list);exit;
                        if (intval($offset) == 0) {
                            //Need filters only if the data is being loaded first time
                            $filters = $this->transferv1_lib->filter_summary($raw_sightseeing_result['data']);
                            $response['filters'] = $filters['data'];
                        }

                        // debug("vvv");exit;
                        // error_reporting(E_ALL);
                        $attr['search_id'] = abs($search_params['search_id']);
                        // debug("vvv");exit;
                        $response['data'] = get_compressed_output(
                            $this->template->isolated_view('transferv1/viator/viator_search_result', array(
                                'currency_obj' => $currency_obj, 'raw_sightseeing_list' => $raw_sightseeing_result['data'],
                                'search_id' => $search_params['search_id'], 'booking_source' => $search_params['booking_source'],
                                'attr' => $attr,
                                'search_params' => $safe_search_data['data']
                            ))
                        );
                        $response['status'] = SUCCESS_STATUS;
                        $response['total_result_count'] = $source_result_count;
                        $response['filter_result_count'] = $filter_result_count;
                        $response['offset'] = $offset + $limit;
                    } else {
                        $response['status'] = FAILURE_STATUS;
                    }
                    break;
            }
        }
        $this->output_compressed_data($response);
    }

    /*
     *
     * Sightseeing AutoSuggest List
     *
     */

    function get_sightseen_city_list()
    {

        $this->load->model('sightseeing_model');
        $term = $this->input->get('term'); //retrieve the search term that autocomplete sends
        $term = trim(strip_tags($term));
        $data_list = $this->sightseeing_model->get_sightseen_city_list($term);
        if (valid_array($data_list) == false) {
            $data_list = $this->sightseeing_model->get_sightseen_city_list('');
        }
        $suggestion_list = array();
        $result = array();
        foreach ($data_list as $city_list) {
            $suggestion_list['label'] = $city_list['city_name'];

            $suggestion_list['value'] = $city_list['city_name'];

            //  $suggestion_list['value'] = hotel_suggestion_value($city_list['city_name'], $city_list['country_name']);
            $suggestion_list['id'] = $city_list['origin'];
            if (empty($city_list['top_destination']) == false) {
                $suggestion_list['category'] = 'Top cities';
                $suggestion_list['type'] = 'Top cities';
            } else {
                $suggestion_list['category'] = 'Location list';
                $suggestion_list['type'] = 'Location list';
            }

            $suggestion_list['count'] = 0;
            $result[] = $suggestion_list;
        }
        $this->output_compressed_data($result);
    }

    function bus_list()
    {
        $response['data'] = '';
        $response['msg'] = '';
        $response['status'] = FAILURE_STATUS;
        $search_params = $this->input->get();
        $this->load->model('bus_model');
        $search_data = $this->bus_model->get_search_data($search_params['search_id']);

        $search_data = json_decode($search_data['search_data'], true);
        if ($search_params['op'] == 'load' && intval($search_params['search_id']) > 0 && isset($search_params['booking_source']) == true) {
            load_bus_lib($search_params['booking_source']);

            switch ($search_params['booking_source']) {
                case PROVAB_BUS_BOOKING_SOURCE:
                    $raw_bus_list = $this->bus_lib->get_bus_list(abs($search_params['search_id']));
                    // debug($raw_bus_list);exit; 
                    $from_id = @$raw_bus_list['data']['result'][0]['From'];
                    $to_id = @$raw_bus_list['data']['result'][0]['To'];
                    $form_data = $this->bus_model->get_bus_station_data($search_data['from_station_id']);
                    $to_data = $this->bus_model->get_bus_station_data($search_data['to_station_id']);
                    $search_data_city = array(
                        'from_id' => $form_data->station_id,
                        'to_id' => $to_data->station_id
                    );

                    if ($raw_bus_list['status']) {
                        //Converting API currency data to preferred currency
                        $currency_obj = new Currency(array('module_type' => 'bus', 'from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
                        $raw_bus_list = $this->bus_lib->search_data_in_preferred_currency($raw_bus_list, $currency_obj);
                        // debug($raw_bus_list);exit; 
                        $formatted_search_data = $this->bus_lib->format_search_response($raw_bus_list, $currency_obj, $search_params['search_id'], 'bus', 'B2B');
                        // debug($formatted_search_data);exit;
                        //Display Bus List
                        $currency_obj = new Currency(array('module_type' => 'bus', 'from' => get_application_currency_preference(), 'to' => get_application_currency_preference()));
                        $raw_bus_list = force_multple_data_format($raw_bus_list['data']['result']);

                        //Update commission
                        // $raw_bus_list = $this->bus_lib->update_bus_search_commission($raw_bus_list, $currency_obj);
                        // echo 'herre';

                        $response['data'] = get_compressed_output(
                            $this->template->isolated_view(
                                'bus/travelyaari/travelyaari_search_result',
                                array('currency_obj' => $currency_obj, 'raw_bus_list' => $formatted_search_data, 'search_id' => $search_params['search_id'], 'booking_source' => $search_params['booking_source'], 'search_data_city' => $search_data_city)
                            )
                        );
                        $response['status'] = SUCCESS_STATUS;
                    }
                    break;
            }
        }
        $this->output_compressed_data($response);
    }
    /**
     * Load hotels from different source
     */
    function bus_list_old()
    {
        $response['data'] = '';
        $response['msg'] = '';
        $response['status'] = FAILURE_STATUS;
        $search_params = $this->input->get();
        /*$search_params['op'] = 'load';
		 $search_params['search_id'] = 2461;
		 $search_params['booking_source'] = PROVAB_BUS_BOOKING_SOURCE;*/
        if ($search_params['op'] == 'load' && intval($search_params['search_id']) > 0 && isset($search_params['booking_source']) == true) {
            load_bus_lib($search_params['booking_source']);
            switch ($search_params['booking_source']) {
                case PROVAB_BUS_BOOKING_SOURCE:
                    $raw_bus_list = $this->bus_lib->get_bus_list(abs($search_params['search_id']));
                    if ($raw_bus_list['status']) {
                        //Converting API currency data to preferred currency
                        $currency_obj = new Currency(array('module_type' => 'bus', 'from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
                        $raw_bus_list = $this->bus_lib->search_data_in_preferred_currency($raw_bus_list, $currency_obj);
                        //Display Bus List
                        $currency_obj = new Currency(array('module_type' => 'bus', 'from' => get_application_currency_preference(), 'to' => get_application_currency_preference()));
                        $raw_bus_list = force_multple_data_format($raw_bus_list['data']['result']);
                        //Update commission
                        $raw_bus_list = $this->bus_lib->update_bus_search_commission($raw_bus_list, $currency_obj);
                        $response['data'] = get_compressed_output(
                            $this->template->isolated_view(
                                'bus/travelyaari/travelyaari_search_result',
                                array(
                                    'currency_obj' => $currency_obj, 'raw_bus_list' => $raw_bus_list,
                                    'search_id' => $search_params['search_id'], 'booking_source' => $search_params['booking_source']
                                )
                            )
                        );
                        $response['status'] = SUCCESS_STATUS;
                    }
                    break;
            }
        }
        $this->output_compressed_data($response);
    }

    function get_bus_information()
    {
        $response['data'] = 'No Details Found';
        $response['status'] = false;
        //check params
        $params = $this->input->post();
        /*$params['booking_source'] = 'PTBSID3377337777';
		 $params['journey_date'] = '2015-08-26T23:00:00';
		 $params['route_code'] = '215-9-3-10-23:00';
		 $params['route_schedule_id'] = '22579952';
		 $params['search_id'] = '2471';*/
        if (empty($params['booking_source']) == false and empty($params['search_id']) == false and intval($params['search_id']) > 0) {
            load_bus_lib($params['booking_source']);
            switch ($params['booking_source']) {
                case PROVAB_BUS_BOOKING_SOURCE:
                    $currency_obj = new Currency(array('module_type' => 'bus', 'from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
                    $details = $this->bus_lib->get_bus_information($params['route_schedule_id'], $params['journey_date']);
                    if ($details['status'] == SUCCESS_STATUS) {
                        $response['stauts'] = SUCCESS_STATUS;
                        $page_data['search_id'] = $params['search_id'];
                        $page_data['details'] = @$details['data']['result'];
                        $page_data['currency_obj'] = $currency_obj;
                        $response['data'] = get_compressed_output($this->template->isolated_view('bus/travelyaari/travelyaari_bus_info', $page_data));
                        $response['status'] = SUCCESS_STATUS;
                    }
                    break;
            }
        }
        $this->output_compressed_data($response);
    }

    /**
     * Get Bus Booking List
     */
    function get_bus_details($filter_boarding_points = false)
    {
        error_reporting(0);
        $this->load->model('bus_model');
        $response['data'] = 'No Details Found !! Try Later';
        $response['status'] = false;
        //check params
        $params = $this->input->post();
        $params = explode('*', $params['route_schedule_id']);

        $params['booking_source'] = $params[3];
        $params['search_id'] = $params[1];
        $params['route_schedule_id'] = $params[0];
        $params['route_code'] =  $params[2];
        $search_data = $this->bus_model->get_search_data($params['search_id']);
        // debug($params);exit;
        $search_data = json_decode($search_data['search_data'], true);
        $form_data = $this->bus_model->get_bus_station_data($search_data['from_station_id']);
        $to_data = $this->bus_model->get_bus_station_data($search_data['to_station_id']);

        if (empty($params['booking_source']) == false and empty($params['search_id']) == false and intval($params['search_id']) > 0) {
            load_bus_lib($params['booking_source']);
            switch ($params['booking_source']) {
                case PROVAB_BUS_BOOKING_SOURCE:
                    $bus_info_data = $this->bus_lib->get_route_details($params['search_id'], $params['route_schedule_id'], $params[2]);
                    $bus_info_data['bus_data']['Form_id'] = $form_data->station_id;
                    $bus_info_data['bus_data']['To_id'] = $to_data->station_id;

                    $params['journey_date'] = $bus_info_data['bus_data']['DepartureTime'];
                    $params['ResultToken'] = $bus_info_data['bus_data']['ResultToken'];
                    $details = $this->bus_lib->get_bus_details($params['route_schedule_id'], $params['journey_date'], $params['route_code'], $params['ResultToken'], $params['booking_source']);


                    if ($details['status'] == SUCCESS_STATUS) {
                        //Converting API currency data to preferred currency
                        $currency_obj = new Currency(array(
                            'module_type' => 'bus',
                            'from' => get_api_data_currency(),
                            'to' => get_application_currency_preference()
                        ));
                        $details = $this->bus_lib->seatdetails_in_preferred_currency_b2b($details, $bus_info_data['bus_data'], $currency_obj);

                        $formatted_seat = $this->bus_lib->seat_layout_format($details['data']['result']['result']['value'], $currency_obj, 'bus', 'B2B');
                        $details['data']['result']['result']['value'] = $formatted_seat;
                        // debug($details);exit;
                        //Display Bus Details
                        $currency_obj = new Currency(array('module_type' => 'bus', 'from' => get_application_currency_preference(), 'to' => get_application_currency_preference()));
                        $response['stauts'] = SUCCESS_STATUS;
                        $page_data['search_id'] = $params['search_id'];
                        $page_data['ResultToken'] = $params['ResultToken'];

                        $page_data['details'] = $details['data']['result'];
                        $page_data['currency_obj'] = $currency_obj;
                        // debug($page_data);exit;                        
                        if ($filter_boarding_points == false) {
                            $response['data'] = get_compressed_output($this->template->isolated_view('bus/travelyaari/travelyaari_bus_details', $page_data));
                        } else {
                            $response['data'] = get_compressed_output($this->template->isolated_view('bus/travelyaari/travelyaari_boarding_details', $page_data));
                        }
                        $response['status'] = SUCCESS_STATUS;
                    }
                    break;
            }
        }
        $this->output_compressed_data($response);
    }
    function get_room_details()
    {

        $response['data'] = '';
        $response['msg'] = '';
        $response['status'] = FAILURE_STATUS;
        $params = $this->input->post();
        //debug($params);exit;
        ini_set('memory_limit', '250M');
        if ($params['op'] == 'get_room_details' && intval($params['search_id']) > 0 && isset($params['booking_source']) == true) {
            $application_preferred_currency = get_application_currency_preference();
            $application_default_currency = get_application_currency_preference();
            load_hotel_lib($params['booking_source']);
            $this->hotel_lib->search_data($params['search_id']);
            $attr['search_id'] = intval($params['search_id']);
            switch ($params['booking_source']) {
                case PROVAB_HOTEL_BOOKING_SOURCE:
                    //    echo "idio";die;
                    $raw_room_list = $this->hotel_lib->get_room_list(urldecode($params['ResultIndex']));
                    $safe_search_data = $this->hotel_model->get_safe_search_data($params['search_id']);
                    // debug($raw_room_list);exit;
                    if ($raw_room_list['status']) {
                        //Converting API currency data to preferred currency
                        $currency_obj = new Currency(array('module_type' => 'hotel', 'from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
                        $raw_room_list = $this->hotel_lib->roomlist_in_preferred_currency($raw_room_list, $currency_obj, $params['search_id']);

                        //Display
                        $currency_obj = new Currency(array('module_type' => 'hotel', 'from' => $application_default_currency, 'to' => $application_preferred_currency));
                        //debug($raw_room_list);exit;
                        $response['data'] = get_compressed_output(
                            $this->template->isolated_view(
                                'hotel/tbo/tbo_room_list',
                                array(
                                    'currency_obj' => $currency_obj,
                                    'params' => $params, 'raw_room_list' => $raw_room_list['data'],
                                    'hotel_search_params' => $safe_search_data['data'],
                                    'application_preferred_currency' => $application_preferred_currency,
                                    'application_default_currency' => $application_default_currency,
                                    'attr' => $attr
                                )
                            )
                        );
                        $response['status'] = SUCCESS_STATUS;
                    }
                    break;
                case CRS_HOTEL_BOOKING_SOURCE:
                    // echo "idiso";die;
                    $raw_room_list = $this->hotel_lib->get_room_list(urldecode($params['ResultIndex']), urldecode($params['search_id']));
                    // debug($raw_room_list);die;
                    // $this->load->model('hotel_model');
                    //  debug($params['search_id']);die;
                    $safe_search_data = $this->hotel_model->get_safe_search_data($params['search_id']);
                    if ($raw_room_list['status']) {
                        //Converting API currency data to preferred currency
                        $currency_obj = new Currency(array('module_type' => 'hotel', 'from' => ADMIN_BASE_CURRENCY_STATIC, 'to' => get_application_currency_preference()));
                        $raw_room_list = $this->hotel_lib->roomlist_in_preferred_currency($raw_room_list, $currency_obj, $params['search_id'], 'b2b');
                        //Display
                        $currency_obj = new Currency(array('module_type' => 'hotel', 'from' => $application_default_currency, 'to' => $application_preferred_currency));
                        $response['data'] = get_compressed_output(
                            $this->template->isolated_view(
                                'hotel/tbo/tbo_room_list',
                                array(
                                    'currency_obj' => $currency_obj,
                                    'params' => $params, 'raw_room_list' => $raw_room_list['data'],
                                    'hotel_search_params' => $safe_search_data['data'],
                                    'application_preferred_currency' => $application_preferred_currency,
                                    'application_default_currency' => $application_default_currency,
                                    'attr' => $attr
                                )
                            )
                        );
                        $response['status'] = SUCCESS_STATUS;
                    }
                    break;
                case REZLIVE_HOTEL:
                    // echo "idiso";die;
                    //debug($params);die();
                    $raw_room_list = $this->hotel_lib->get_room_list($params);

                    //debug($raw_room_list);die('123');
                    $safe_search_data = $this->hotel_model->get_safe_search_data($params['search_id']);
                    if ($raw_room_list['status']) {
                        //Converting API currency data to preferred currency
                        $currency_obj = new Currency(array('module_type' => 'hotel', 'from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
                        //debug($currency_obj);die('+++');
                        $raw_room_list = $this->hotel_lib->roomlist_in_preferred_currency($raw_room_list, $currency_obj, $params['search_id'], 'b2c');
                        //Display
                        $currency_obj = new Currency(array('module_type' => 'hotel', 'from' => $application_default_currency, 'to' => $application_preferred_currency));
                        //debug($raw_room_list);die('+++');
                        $response['data'] = get_compressed_output(
                            $this->template->isolated_view(
                                'hotel/tbo/tbo_room_list',
                                array(
                                    'currency_obj' => $currency_obj,
                                    'params' => $params, 'raw_room_list' => $raw_room_list['data'],
                                    'hotel_search_params' => $safe_search_data['data'],
                                    'application_preferred_currency' => $application_preferred_currency,
                                    'application_default_currency' => $application_default_currency,
                                    'attr' => $attr
                                )
                            )
                        );
                        $response['status'] = SUCCESS_STATUS;
                    }
                    break;
            }
        }
        $this->output_compressed_data($response);
    }

    /**
     * Load hotels from different source
     */
    function get_room_detailsold()
    {
        $response['data'] = '';
        $response['msg'] = '';
        $response['status'] = FAILURE_STATUS;
        $params = $this->input->post();

        /*$params['HotelCode'] = '1000002306';
		 $params['ResultIndex'] = 28;
		 $params['booking_source'] = PROVAB_HOTEL_BOOKING_SOURCE;
		 $params['TraceId'] = '	c064afbd-dc5b-43e0-909f-50b8d9efdd3d';
		 $params['op'] = 'get_room_details';
		 $params['search_id'] = 2290;*/

        if ($params['op'] == 'get_room_details' && intval($params['search_id']) > 0 && isset($params['booking_source']) == true) {
            $application_preferred_currency = get_application_currency_preference();
            $application_default_currency = get_application_currency_preference();
            load_hotel_lib($params['booking_source']);
            $this->hotel_lib->search_data($params['search_id']);
            $attr['search_id'] = intval($params['search_id']);
            switch ($params['booking_source']) {
                case PROVAB_HOTEL_BOOKING_SOURCE:
                    $raw_room_list = $this->hotel_lib->get_room_list(urldecode($params['ResultIndex']));
                    $safe_search_data = $this->hotel_model->get_safe_search_data($params['search_id']);
                    if ($raw_room_list['status']) {
                        //Converting API currency data to preferred currency
                        $currency_obj = new Currency(array('module_type' => 'hotel', 'from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
                        $raw_room_list = $this->hotel_lib->roomlist_in_preferred_currency($raw_room_list, $currency_obj, $params['search_id'], 'b2b');
                        //Display
                        $currency_obj = new Currency(array('module_type' => 'hotel', 'from' => $application_default_currency, 'to' => $application_preferred_currency));
                        $response['data'] = get_compressed_output(
                            $this->template->isolated_view(
                                'hotel/tbo/tbo_room_list',
                                array(
                                    'currency_obj' => $currency_obj,
                                    'params' => $params, 'raw_room_list' => $raw_room_list['data'],
                                    'hotel_search_params' => $safe_search_data['data'],
                                    'application_preferred_currency' => $application_preferred_currency,
                                    'application_default_currency' => $application_default_currency,
                                    'attr' => $attr
                                )
                            )
                        );
                        $response['status'] = SUCCESS_STATUS;
                    }
                    break;
            }
        }
        $this->output_compressed_data($response);
    }


    /**
     * Load Flight from different source
     * 2339 - one way - bangalore to goa
     * 2341 - one way bangalore to dubai
     */
    function flight_list($search_id = '')
    {

        //$response['data'] = '';
        //$response['msg'] = '';
        //$response['status'] = FAILURE_STATUS;
        $search_params = $this->input->get();
        $page_params['search_id'] = $search_params['search_id'];
        if ($search_params['op'] == 'load' && intval($search_params['search_id']) > 0 && isset($search_params['booking_source']) == true) {
            load_flight_lib($search_params['booking_source']);
            switch ($search_params['booking_source']) {
                case PROVAB_FLIGHT_BOOKING_SOURCE:

                    $formatted_search_data['data']['Flights'][0] = array();

                    $raw_flight_list = $this->flight_lib->get_flight_list(abs($search_params['search_id']));
                    // debug($raw_flight_list);exit;
                    if ($raw_flight_list['status']) {
                        //View Data
                        $raw_search_result = $raw_flight_list['data']['Search']['FlightDataList'];

                        //Converting API currency data to preferred currency
                        $currency_obj = new Currency(array('module_type' => 'flight', 'from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
                        //changes added new b2b function for agent search
                        // 			$raw_search_result = $this->flight_lib->search_data_in_preferred_currency($raw_search_result, $currency_obj);

                        $raw_search_result = $this->flight_lib->search_data_in_preferred_currency_b2b($raw_search_result, $currency_obj);

                        //Display
                        $currency_obj = new Currency(array('module_type' => 'flight', 'from' => get_application_currency_preference(), 'to' => get_application_currency_preference()));

                        $formatted_search_data = $this->flight_lib->format_search_response($raw_search_result, $currency_obj, $search_params['search_id'], $this->current_module, $raw_flight_list['from_cache'], $raw_flight_list['search_hash']);
                    }
                    $price = array_column($formatted_search_data['data']['Flights'][0], 'shortprice');
                    if (isset($formatted_search_data['data']['Flights'][1])) {

                        $price = array_column($formatted_search_data['data']['Flights'][1], 'shortprice');
                        array_multisort($price, SORT_ASC, $formatted_search_data['data']['Flights'][1]);
                    }


                    $raw_flight_list['data'] = $formatted_search_data['data'];
                    $route_count = count($raw_flight_list['data']['Flights']);
                    $domestic_round_way_flight = $raw_flight_list['data']['JourneySummary']['IsDomesticRoundway'];
                    if (($route_count > 0)) {
                        $attr['search_id'] = abs($search_params['search_id']);
                        $page_params = array(
                            'raw_flight_list' => $raw_flight_list['data'],
                            'search_id' => $search_params['search_id'],
                            'booking_url' => base_url() . 'index.php/flight/booking/' . intval($search_params['search_id']),
                            'booking_source' => $search_params['booking_source'],
                            'cabin_class' => $raw_flight_list['cabin_class'],
                            'trip_type' => $this->flight_lib->master_search_data['trip_type'],
                            'attr' => $attr,
                            'route_count' => $route_count,
                            'IsDomestic' => $raw_flight_list['data']['JourneySummary']['IsDomestic']
                        );
                        if ($this->flight_lib->master_search_data['trip_type'] == "circle") {
                            $domestic_round_way_flight = true;
                        }
                        $page_params['domestic_round_way_flight'] = $domestic_round_way_flight;






                        if (count($raw_flight_list['data']) > 0) {
                            $page_view_data = $this->template->isolated_view('flight/tbo/tbo_col2x_search_result', $page_params);
                            $response['data'] = get_compressed_output($page_view_data);
                            $response['status'] = SUCCESS_STATUS;
                        }
                        /*
								session expiry start time and search hash 
							*/
                        $response['session_expiry_details'] = $formatted_search_data['session_expiry_details'];
                        //  if($page_params['IsDomestic']==true && $this->flight_lib->master_search_data['trip_type']=="circle")
                        //    {
                        //      $response=array();
                        //    }
                    }
                    break;
                case PLAZMA_BOOKING_SOURCE:


                    $formatted_search_data['data']['Flights'][0] = array();

                    //changes added module name for b2b search
                    $raw_flight_list = $this->flight_lib->get_flight_list(abs($search_params['search_id']), 'b2b');
                    // debug($raw_flight_list);exit;
                    if ($raw_flight_list['status']) {
                        //View Data
                        $raw_search_result = $raw_flight_list['data']['Search']['FlightDataList'];

                        //Converting API currency data to preferred currency
                        $currency_obj = new Currency(array('module_type' => 'plazmaflight', 'from' => 'NPR', 'to' => get_application_currency_preference()));
                        //changes added new function for b2b search
                        // 			$raw_search_result = $this->flight_lib->search_data_in_preferred_currency($raw_search_result, $currency_obj);

                        $raw_search_result = $this->flight_lib->search_data_in_preferred_currency_b2b($raw_search_result, $currency_obj);
                        // debug($raw_search_result);exit;
                        //Display
                        $currency_obj = new Currency(array('module_type' => 'plazmaflight', 'from' => get_application_currency_preference(), 'to' => get_application_currency_preference()));

                        $formatted_search_data = $this->flight_lib->format_search_response($raw_search_result, $currency_obj, $search_params['search_id'], $this->current_module, $raw_flight_list['from_cache'], $raw_flight_list['search_hash']);
                    }
                    //	echo "asdw";die;
                    //debug($formatted_search_data );
                    //debug($raw_flight_list['data']);die;
                    //echo  "count".count($fformatted_search_data['data']['Flights']);die;
                    if (count($formatted_search_data['data']['Flights']) > 0) {
                        $price = array_column($formatted_search_data['data']['Flights'][0], 'shortprice');
                        if (isset($formatted_search_data['data']['Flights'][1])) {

                            $price = array_column($formatted_search_data['data']['Flights'][1], 'shortprice');
                            array_multisort($price, SORT_ASC, $formatted_search_data['data']['Flights'][1]);
                        }


                        $raw_flight_list['data'] = $formatted_search_data['data'];
                        if ($this->flight_lib->master_search_data['trip_type'] == "circle") {
                            if (isset($raw_flight_list['data']['Flights'][0])) {
                                $route_count = count($raw_flight_list['data']['Flights']);
                            } else {
                                $route_count = 0;
                            }
                        } else {
                            if (isset($raw_flight_list['data']['Flights'][0])) {
                                $route_count = count($raw_flight_list['data']['Flights']);
                            } else {
                                $route_count = 0;
                            }
                        }
                        //	$route_count = count($raw_flight_list['data']['Flights']);
                        $domestic_round_way_flight = $raw_flight_list['data']['JourneySummary']['IsDomesticRoundway'];
                        if (($route_count > 0)) {
                            $attr['search_id'] = abs($search_params['search_id']);
                            $page_params = array(
                                'raw_flight_list' => $raw_flight_list['data'],
                                'search_id' => $search_params['search_id'],
                                'booking_url' => base_url() . 'index.php/flight/booking/' . intval($search_params['search_id']),
                                'booking_source' => $search_params['booking_source'],
                                'cabin_class' => $raw_flight_list['cabin_class'],
                                'trip_type' => $this->flight_lib->master_search_data['trip_type'],
                                'attr' => $attr,
                                'route_count' => $route_count,
                                'IsDomestic' => $raw_flight_list['data']['JourneySummary']['IsDomestic']
                            );
                            if ($this->flight_lib->master_search_data['trip_type'] == "circle") {
                                $domestic_round_way_flight = true;
                            }
                            $page_params['domestic_round_way_flight'] = $domestic_round_way_flight;

                            //debug( $page_params);die;

                            if ($this->flight_lib->master_search_data['trip_type'] != "multicity") {
                                //	echo "asdw";die;
                                $page_view_data = $this->template->isolated_view('flight/tbo/tbo_col2x_search_result', $page_params);
                                $response['data'] = get_compressed_output($page_view_data);
                                $response['status'] = SUCCESS_STATUS;
                                $response['session_expiry_details'] = $formatted_search_data['session_expiry_details'];
                            }
                        }

                        /*
								session expiry start time and search hash 
							*/
                    }
                    break;
                case AMADEUS_FLIGHT_BOOKING_SOURCE:

                    $raw_flight_list = $this->flight_lib->get_flight_list(abs($search_params['search_id']));

                    $formatted_search_data['data']['Flights'][0] = array();
                    if ($raw_flight_list['status']) {
                        //View Data
                        $raw_search_result = $raw_flight_list['data']['Search']['FlightDataList'];

                        //Converting API currency data to preferred currency
                        $currency_obj = new Currency(array('module_type' => 'flight', 'from' => amadeus_get_api_data_currency(), 'to' => get_application_currency_preference()));

                        $raw_search_result = $this->flight_lib->search_data_in_preferred_currency($raw_search_result, $currency_obj);

                        //Display
                        $currency_obj = new Currency(array('module_type' => 'flight', 'from' => get_application_currency_preference(), 'to' => get_application_currency_preference()));

                        $formatted_search_data = $this->flight_lib->format_search_response($raw_search_result, $currency_obj, $search_params['search_id'], $this->current_module, $raw_flight_list['from_cache'], $raw_flight_list['search_hash']);
                    }
                    if ($_SERVER['REMOTE_ADDR'] == "106.203.17.103") {



                        //  debug($formatted_search_data);die;
                    }
                    /*if(valid_array($flight_crs_formatted_search_data['data']['Flights'][0])){
                        $formatted_search_data['booking_url'] = $flight_crs_formatted_search_data['booking_url'];
                        $formatted_search_data['data']['JourneySummary'] = $flight_crs_formatted_search_data['data']['JourneySummary'];
                                
                        $formatted_search_data['data']['Flights'][0] = array_merge($formatted_search_data['data']['Flights'][0],$flight_crs_formatted_search_data['data']['Flights'][0]);
                    }
                    $price=array_column($formatted_search_data['data']['Flights'][0],'shortprice');
                    
                    array_multisort($price,SORT_ASC,$formatted_search_data['data']['Flights'][0]);
                    if(isset($formatted_search_data['data']['Flights'][1])){
                    
                    $price=array_column($formatted_search_data['data']['Flights'][1],'shortprice');
                    array_multisort($price,SORT_ASC,$formatted_search_data['data']['Flights'][1]);
                    }*/
                    $raw_flight_list['data'] = $formatted_search_data['data'];
                    $route_count = count($raw_flight_list['data']['Flights']);
                    $domestic_round_way_flight = $raw_flight_list['data']['JourneySummary']['IsDomesticRoundway'];
                    if (($route_count > 0)) { //later enable above condition
                        $attr['search_id'] = abs($search_params['search_id']);
                        $page_params = array(
                            'raw_flight_list' => $raw_flight_list['data'],
                            'search_id' => $search_params['search_id'],
                            'booking_url' => base_url() . 'index.php/flight/booking/' . intval($search_params['search_id']),
                            'booking_source' => $search_params['booking_source'],
                            'cabin_class' => $raw_flight_list['cabin_class'],
                            'trip_type' => $this->flight_lib->master_search_data['trip_type'],
                            'attr' => $attr,
                            'route_count' => $route_count,
                            'IsDomestic' => $raw_flight_list['data']['JourneySummary']['IsDomestic']
                        );
                        $page_params['domestic_round_way_flight'] = $domestic_round_way_flight;
                        $page_view_data = $this->template->isolated_view('flight/tbo/amadeus_col2x_search_result', $page_params);
                        $response['data'] = get_compressed_output($page_view_data);
                        $response['status'] = SUCCESS_STATUS;

                        $response['session_expiry_details'] = $formatted_search_data['session_expiry_details'];
                        //debug($page_params);die;
                        if ($this->flight_lib->master_search_data['trip_type'] == "circle" && $raw_flight_list['data']['JourneySummary']['IsDomestic'] == true) {
                            $response = array();
                        }
                    }
                    break;
            }
        }
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        $this->output_compressed_data_flight($response);
    }

    /**
     * Get Data For Fare Calendar
     * @param string $booking_source
     */
    function puls_minus_days_fare_list($booking_source)
    {
        $response['data'] = array();
        $response['status'] = FAILURE_STATUS;

        $params = $this->input->get();
        load_flight_lib($booking_source);
        $search_data = $this->flight_lib->search_data(intval($params['search_id']));
        if ($search_data['status'] == SUCCESS_STATUS) {
            $date_array = array();
            $departure_date = $search_data['data']['depature'];
            $departure_date = strtotime(subtract_days_from_date(3, $departure_date));
            if (time() >= $departure_date) {
                $date_array[] = date('Y-m-d', strtotime(add_days_to_date(1)));
            } else {
                $date_array[] = date('Y-m-d', $departure_date);
            }
            $date_array[] = date('Y-m', strtotime($departure_date[0] . ' +1 month')) . '-1';
            //Get Current Month And Next Month
            $day_fare_list = array();
            foreach ($date_array as $k => $v) {
                $search_data['data']['depature'] = $v;
                $search = $this->flight_lib->calendar_safe_search_data($search_data['data']);
                if (valid_array($search) == true) {
                    switch ($booking_source) {
                        case PROVAB_FLIGHT_BOOKING_SOURCE:
                            $raw_fare_list = $this->flight_lib->get_fare_list($search);
                            if ($raw_fare_list['status']) {
                                $fare_calendar_list = $this->flight_lib->format_cheap_fare_list($raw_fare_list['data']);
                                if ($fare_calendar_list['status'] == SUCCESS_STATUS) {
                                    $response['data']['departure'] = $search['depature'];
                                    $calendar_events = $this->get_fare_calendar_events($fare_calendar_list['data'], $raw_fare_list['data']['TraceId']);
                                    $day_fare_list = array_merge($day_fare_list, $calendar_events);
                                    $response['status'] = SUCCESS_STATUS;
                                } else {
                                    $response['msg'] = 'Not Available!!! Please Try Later!!!!';
                                }
                            }
                            break;
                    }
                }
            }
            $response['data']['day_fare_list'] = $day_fare_list;
        }
        $this->output_compressed_data($response);
    }

    /**
     * get fare list for calendar search - FLIGHT
     */
    function fare_list($booking_source)
    {
        /*$options = array('location' => 'http://192.168.0.63/soap/server1.php',
		 'uri' => 'http://192.168.0.63/soap/');
		 $api = new SoapClient(NULL, $options);
		 echo "<pre>"; print_r($api->hello()); exit;*/

        $response['data'] = '';
        $response['msg'] = '';
        $response['status'] = FAILURE_STATUS;
        $search_params = $this->input->get();
        load_flight_lib($booking_source);
        $search_params = $this->flight_lib->calendar_safe_search_data($search_params);
        if (valid_array($search_params) == true) {
            switch ($booking_source) {
                case PROVAB_FLIGHT_BOOKING_SOURCE:
                    $raw_fare_list = $this->flight_lib->get_fare_list($search_params);
                    if ($raw_fare_list['status']) {
                        $fare_calendar_list = $this->flight_lib->format_cheap_fare_list($raw_fare_list['data']);
                        if ($fare_calendar_list['status'] == SUCCESS_STATUS) {
                            $response['data']['departure'] = $search_params['depature'];
                            $calendar_events = $this->get_fare_calendar_events($fare_calendar_list['data'], $raw_fare_list['data']['GetCalendarFareResult']['SessionId']);
                            $response['data']['day_fare_list'] = $calendar_events;
                            $response['status'] = SUCCESS_STATUS;
                        } else {
                            $response['msg'] = 'Not Available!!! Please Try Later!!!!';
                        }
                    }
                    break;
            }
        }
        $this->output_compressed_data($response);
    }

    /**
     * Calendar Event Object
     * @param $title
     * @param $start
     * @param $tip
     * @param $href
     * @param $event_date
     * @param $session_id
     * @param $add_class
     */
    private function get_calendar_event_obj($title = '', $start = '', $tip = '', $add_class = '', $href = '', $event_date = '', $session_id = '', $data_id = '')
    {
        $event_obj = array();
        if (empty($data_id) == false) {
            $event_obj['data_id'] = $data_id;
        } else {
            $event_obj['data_id'] = '';
        }

        if (empty($title) == false) {
            $event_obj['title'] = $title;
        } else {
            $event_obj['title'] = '';
        }
        //start
        if (empty($start) == false) {
            $event_obj['start'] = $start;
            $event_obj['start_label'] = date('M d', strtotime($start));
        } else {
            $event_obj['start'] = '';
        }
        //tip
        if (empty($tip) == false) {
            $event_obj['tip'] = $tip;
        } else {
            $event_obj['tip'] = '';
        }
        //href
        if (empty($href) == false) {
            $event_obj['href'] = $href;
        } else {
            $event_obj['href'] = '';
        }
        //event_date
        if (empty($event_date) == false) {
            $event_obj['event_date'] = $event_date;
        }
        //session_id
        if (empty($session_id) == false) {
            $event_obj['session_id'] = $session_id;
        }
        //add_class
        if (empty($add_class) == false) {
            $event_obj['add_class'] = $add_class;
        } else {
            $event_obj['add_class'] = '';
        }
        return $event_obj;
    }

    function day_fare_list($booking_source)
    {
        $response['data'] = '';
        $response['msg'] = '';
        $response['status'] = FAILURE_STATUS;
        $search_params = $this->input->get();
        load_flight_lib($booking_source);
        $safe_search_params = $this->flight_lib->calendar_day_fare_safe_search_data($search_params);
        if ($safe_search_params['status'] == SUCCESS_STATUS) {
            switch ($booking_source) {
                case PROVAB_FLIGHT_BOOKING_SOURCE:
                    $raw_day_fare_list = $this->flight_lib->get_day_fare($search_params);
                    if ($raw_day_fare_list['status']) {
                        $fare_calendar_list = $this->flight_lib->format_day_fare_list($raw_day_fare_list['data']);
                        if ($fare_calendar_list['status'] == SUCCESS_STATUS) {
                            $calendar_events = $this->get_fare_calendar_events($fare_calendar_list['data'], '');
                            $response['data']['day_fare_list'] = $calendar_events;
                            $response['data']['departure'] = $search_params['depature'];
                            $response['status'] = SUCCESS_STATUS;
                        } else {
                            $response['msg'] = 'Not Available!!! Please Try Later!!!!';
                        }
                    }
                    break;
            }
        }
        $this->output_compressed_data($response);
    }

    private function get_fare_calendar_events($events, $session_id = '')
    {
        $currency_obj = new Currency(array('module_type' => 'flight', 'from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
        $index = 0;
        $calendar_events = array();
        foreach ($events as $k => $day_fare) {
            if (valid_array($day_fare) == true) {
                $fare_object = array('BaseFare' => $day_fare['BaseFare']);
                $BaseFare = $this->flight_lib->update_markup_currency($fare_object, $currency_obj);
                $tax = $currency_obj->get_currency($day_fare['tax'], false);
                $day_fare['price'] = floor($BaseFare['BaseFare'] + $day_fare['tax']);
                $event_obj = $this->get_calendar_event_obj(
                    $currency_obj->get_currency_symbol(get_application_currency_preference()) . ' ' . $day_fare['price'],
                    $k,
                    $day_fare['airline_name'] . '-' . $day_fare['airline_code'],
                    'search-day-fare',
                    '',
                    $day_fare['departure'],
                    '',
                    $day_fare['airline_code']
                );
                $calendar_events[$index] = $event_obj;
            } else {
                $event_obj = $this->get_calendar_event_obj(
                    'Update',
                    $k,
                    'Current Cheapest Fare Not Available. Click To Get Latest Fare.',
                    'update-day-fare',
                    '',
                    $k,
                    $session_id,
                    ''
                );
                $calendar_events[$index] = $event_obj;
            }
            $index++;
        }
        return $calendar_events;
    }

    /**
     * Get Fare Details
     */
    function get_fare_details()
    {
        $response['status'] = false;
        $response['data'] = '';
        $response['msg'] = '<i class="fa fa-warning text-danger"></i> Fare Details Not Available';
        $params = $this->input->post();

        load_flight_lib($params['booking_source']);
        $data_access_key = $params['data_access_key'];
        $params['data_access_key'] = unserialized_data($params['data_access_key']);
        if (empty($params['data_access_key']) == false) {
            switch ($params['booking_source']) {
                case PROVAB_FLIGHT_BOOKING_SOURCE:
                    $params['data_access_key'] = $this->flight_lib->read_token($data_access_key);
                    $data = $this->flight_lib->get_fare_details($params['data_access_key'], $params['search_access_key']);
                    if ($data['status'] == SUCCESS_STATUS) {
                        $response['status']    = SUCCESS_STATUS;
                        $response['data']    = $this->template->isolated_view('flight/tbo/fare_details', array('fare_rules' => $data['data']));
                        $response['msg']    = 'Fare Details Available';
                    }
            }
        }
        $this->output_compressed_data($response);
    }

    function get_combined_booking_from()
    {
        $response['status']    = FAILURE_STATUS;
        $response['data']    = array();
        $params = $this->input->post();
        if (empty($params['search_id']) == false && empty($params['trip_way_1']) == false && empty($params['trip_way_2']) == false) {
            $tmp_trip_way_1    = json_decode($params['trip_way_1'], true);
            $tmp_trip_way_2    = json_decode($params['trip_way_2'], true);
            $search_id    = $params['search_id'];
            foreach ($tmp_trip_way_1 as $___v) {
                $trip_way_1[$___v['name']] = $___v['value'];
            }
            foreach ($tmp_trip_way_2 as $___v) {
                $trip_way_2[$___v['name']] = $___v['value'];
            }
            $booking_source = $trip_way_1['booking_source'];
            switch ($booking_source) {
                case PROVAB_FLIGHT_BOOKING_SOURCE:
                    load_flight_lib(PROVAB_FLIGHT_BOOKING_SOURCE);
                    $response['data']['booking_url']    = $this->flight_lib->booking_url(intval($params['search_id']));
                    $response['data']['form_content']    = $this->flight_lib->get_form_content($trip_way_1, $trip_way_2);
                    $response['status']                    = SUCCESS_STATUS;
                    break;

                case PLAZMA_BOOKING_SOURCE:
                    load_flight_lib(PLAZMA_BOOKING_SOURCE);
                    $response['data']['booking_url']    = $this->flight_lib->booking_url(intval($params['search_id']));
                    $response['data']['form_content']    = $this->flight_lib->get_form_content($trip_way_1, $trip_way_2);
                    $response['status']                    = SUCCESS_STATUS;
                    break;
            }
        }
        $this->output_compressed_data($response);
    }

    /**
     *
     */
    function log_event_ip_info($eid)
    {
        $params = $this->input->post();
        if (empty($eid) == false) {
            $this->custom_db->update_record('exception_logger', array('client_info' => serialize($params)), array('exception_id' => $eid));
        }
    }
    //---------------------------------------------------------------- Booking Events Starts
    /**
     * Load Booking Events of all the modules
     */
    function booking_events()
    {
        $status = true;
        $data = array();
        $calendar_events = array();
        $condition = array(array('BD.created_datetime', '>=', $this->db->escape(date('Y-m-d', strtotime(subtract_days_from_date(90)))))); //of last 30 days only
        if (is_active_bus_module()) {
            $calendar_events = array_merge($calendar_events, $this->bus_booking_events($condition));
        }
        if (is_active_hotel_module()) {
            $calendar_events = array_merge($calendar_events, $this->hotel_booking_events($condition));
        }
        if (is_active_airline_module()) {
            $calendar_events = array_merge($calendar_events, $this->flight_booking_events($condition));
        }

        if (is_active_sightseeing_module()) {

            $calendar_events = array_merge($calendar_events, $this->sightseeing_booking_events($condition));
        }
        if (is_active_transferv1_module()) {

            $calendar_events = array_merge($calendar_events, $this->transfers_booking_events($condition));
        }
        if (is_active_package_module()) {
            $calendar_events = array_merge($calendar_events, $this->holiday_booking_events($condition));
        }
        if (is_active_car_module()) {
            $calendar_events = array_merge($calendar_events, $this->car_booking_events($condition));
        }


        // debug($calendar_events);exit;
        header('content-type:application/json');
        echo json_encode(array('status' => $status, 'data' => $calendar_events));
        exit;
    }

    /**
     * Hotel Booking Events Summary
     * @param array $condition
     */

    private function holiday_booking_events($condition)
    {
        $this->load->model('tours_model');
        $data_list = $this->tours_model->booking_for_dash_board($condition);
        // debug($data_list);exit;
        /*$this->load->library('booking_data_formatter');
		$table_data = $this->booking_data_formatter->format_holiday_booking_data($data_list, 'b2c');*/
        $booking_details = $data_list;
        // debug($booking_details);exit;
        $calendar_events = array();
        if (valid_array($booking_details) == true) {
            $key = 0;
            foreach ($booking_details as $k => $v) {
                $bok_src = json_decode($v['attributes'], true);
                $booking_source = $bok_src['booking_source'];
                $calendar_events[$key]['title'] = $v['app_reference'] . '-' . $v['status'];
                $calendar_events[$key]['start'] = $v['created_datetime'];
                $calendar_events[$key]['tip'] = $v['app_reference'] . '-Departure From:' . $v['departure_date'] . '-' . $v['status'] . '- Click To View More Details';
                $calendar_events[$key]['href'] = holiday_voucher_url($v['app_reference'], $booking_source);
                if ($v['status'] == "BOOKING_CANCELLED") {
                    $calendar_events[$key]['add_class'] = 'hand-cursor event-hand htlbkngcanc

';
                } else {


                    $calendar_events[$key]['add_class'] = 'hand-cursor event-hand hotel-booking';
                }
                $key++;
            }
        }

        // debug($calendar_events);exit;
        return $calendar_events;
    }
    private function car_booking_events($condition)
    {
        // debug("gg");exit;
        $this->load->model('car_model');
        $data_list = $this->car_model->booking($condition);
        // debug($data_list);exit;
        /*$this->load->library('booking_data_formatter');
		$table_data = $this->booking_data_formatter->format_hotel_booking_data($data_list, 'b2c');*/
        // debug($data_list);exit;
        $booking_details = $table_data['data']['booking_details'];
        $calendar_events = array();
        if (valid_array($booking_details) == true) {
            $key = 0;
            foreach ($booking_details as $k => $v) {
                $calendar_events[$key]['title'] = $v['app_reference'] . '-' . $v['status'];
                $calendar_events[$key]['start'] = $v['created_datetime'];
                $calendar_events[$key]['tip'] = $v['app_reference'] . '-From:' . $v['car_from_date'] . ', To:' . $v['car_to_date'] . '-' . $v['status'] . '- Click To View More Details';
                $calendar_events[$key]['href'] = car_voucher_url($v['app_reference'], $v['booking_source'], $v['status']);
                if ($v['status'] == "BOOKING_CANCELLED") {
                    $calendar_events[$key]['add_class'] = 'hand-cursor event-hand htlbkngcanc

';
                } else {


                    $calendar_events[$key]['add_class'] = 'hand-cursor event-hand hotel-booking';
                }
                $key++;
            }
        }
        return $calendar_events;
    }




    private function hotel_booking_events($condition)
    {
        $this->load->model('hotel_model');
        $data_list = $this->hotel_model->booking($condition);
        $this->load->library('booking_data_formatter');
        $table_data = $this->booking_data_formatter->format_hotel_booking_data($data_list, 'b2b');
        $booking_details = $table_data['data']['booking_details'];
        $calendar_events = array();
        if (valid_array($booking_details) == true) {
            $key = 0;
            foreach ($booking_details as $k => $v) {
                $calendar_events[$key]['title'] = $v['app_reference'] . '-' . $v['status'];
                $calendar_events[$key]['start'] = $v['created_datetime'];
                $calendar_events[$key]['tip'] = $v['app_reference'] . '-PNR:' . $v['confirmation_reference'] . '-From:' . $v['hotel_check_in'] . ', To:' . $v['hotel_check_out'] . '-' . $v['status'] . '- Click To View More Details';
                $calendar_events[$key]['href'] = hotel_voucher_url($v['app_reference'], $v['booking_source'], $v['status']);
                if ($v['status'] == "BOOKING_CANCELLED") {
                    $calendar_events[$key]['add_class'] = 'hand-cursor event-hand htlbkngcanc

';
                } else {


                    $calendar_events[$key]['add_class'] = 'hand-cursor event-hand hotel-booking';
                }
                $key++;
            }
        }
        return $calendar_events;
    }

    /**
     * Flight Booking Events Summary
     * @param array $condition
     */
    private function flight_booking_events($condition)
    {
        $this->load->model('flight_model');
        $data_list = $this->flight_model->booking($condition);
        $this->load->library('booking_data_formatter');
        $table_data = $this->booking_data_formatter->format_flight_booking_data($data_list, 'b2b');
        $booking_details = $table_data['data']['booking_details'];
        $calendar_events = array();
        if (valid_array($booking_details) == true) {
            $key = 0;
            foreach ($booking_details as $k => $v) {
                $calendar_events[$key]['title'] = $v['app_reference'] . '-' . $v['status'];
                $calendar_events[$key]['start'] = $v['created_datetime'];
                $calendar_events[$key]['tip'] = $v['app_reference'] . ',From:' . $v['journey_from'] . ', To:' . $v['journey_to'] . '-' . $v['status'] . '- Click To View More Details';
                $calendar_events[$key]['href'] = flight_voucher_url($v['app_reference'], $v['booking_source'], $v['status']);
                if ($v['status'] == "BOOKING_CANCELLED") {
                    $calendar_events[$key]['add_class'] = 'hand-cursor event-hand htlbkngcanc

';
                } else {


                    $calendar_events[$key]['add_class'] = 'hand-cursor event-hand hotel-booking';
                }
                $key++;
            }
        }

        return $calendar_events;
    }

    /**
     * Sightseeing Booking Events Summary
     * @param array $condition
     */
    private function sightseeing_booking_events($condition)
    {
        $this->load->model('sightseeing_model');
        $data_list = $this->sightseeing_model->booking($condition);
        $this->load->library('booking_data_formatter');
        $table_data = $this->booking_data_formatter->format_sightseeing_booking_data($data_list, 'b2b');
        $booking_details = $table_data['data']['booking_details'];
        $calendar_events = array();
        if (valid_array($booking_details) == true) {
            $key = 0;
            foreach ($booking_details as $k => $v) {
                $calendar_events[$key]['title'] = $v['app_reference'] . '-' . $v['status'];
                $calendar_events[$key]['start'] = $v['created_datetime'];
                $calendar_events[$key]['tip'] = $v['app_reference'] . '-PNR:' . $v['confirmation_reference'] . '-From:' . $v['destination_name'] . ', Travel Date:' . $v['travel_date'] . '-' . $v['status'] . '- Click To View More Details';
                $calendar_events[$key]['href'] = sightseeing_voucher_url($v['app_reference'], $v['booking_source'], $v['status']);
                $calendar_events[$key]['add_class'] = 'hand-cursor event-hand sightseeing-booking';
                $key++;
            }
        }

        return $calendar_events;
    }
    /**
     * Transfers Booking Events Summary
     * @param array $condition
     */
    private function transfers_booking_events($condition)
    {
        $this->load->model('transferv1_model');
        $data_list = $this->transferv1_model->booking($condition);
        $this->load->library('booking_data_formatter');
        $table_data = $this->booking_data_formatter->format_transferv1_booking_data($data_list, 'b2b');
        $booking_details = $table_data['data']['booking_details'];
        $calendar_events = array();

        if (valid_array($booking_details) == true) {
            $key = 0;
            foreach ($booking_details as $k => $v) {
                $calendar_events[$key]['title'] = $v['app_reference'] . '-' . $v['status'];
                $calendar_events[$key]['start'] = $v['created_datetime'];
                $calendar_events[$key]['tip'] = $v['app_reference'] . '-PNR:' . $v['confirmation_reference'] . '-From:' . $v['destination_name'] . ', Travel Date:' . $v['travel_date'] . '-' . $v['status'] . '- Click To View More Details';
                $calendar_events[$key]['href'] = transfers_voucher_url($v['app_reference'], $v['booking_source'], $v['status']);
                if ($v['status'] == "BOOKING_CANCELLED") {
                    $calendar_events[$key]['add_class'] = 'hand-cursor event-hand htlbkngcanc

';
                } else {


                    $calendar_events[$key]['add_class'] = 'hand-cursor event-hand hotel-booking';
                }
                //$calendar_events[$k]['prepend_element'] = '<i class="fa fa-bus"></i>';
                $key++;
            }
        }
        return $calendar_events;
    }

    /**
     * Bus Booking Events Summary
     * @param array $condition
     */
    private function bus_booking_events($condition)
    {
        $this->load->model('bus_model');
        $data_list = $this->bus_model->booking($condition);
        $this->load->library('booking_data_formatter');
        $table_data = $this->booking_data_formatter->format_bus_booking_data($data_list, 'b2b');
        $booking_details = $table_data['data']['booking_details'];
        $calendar_events = array();
        if (valid_array($booking_details) == true) {
            $key = 0;
            foreach ($booking_details as $k => $v) {
                $calendar_events[$key]['title'] = $v['app_reference'] . '-' . $v['status'];
                $calendar_events[$key]['start'] = $v['created_datetime'];
                $calendar_events[$key]['tip'] = $v['app_reference'] . '-PNR:' . $v['pnr'] . '-From:' . $v['departure_from'] . ', To:' . $v['arrival_to'] . '-' . $v['status'] . '- Click To View More Details';
                $calendar_events[$key]['href'] = bus_voucher_url($v['app_reference'], $v['booking_source'], $v['status']);
                $calendar_events[$key]['add_class'] = 'hand-cursor event-hand bus-booking';
                //$calendar_events[$k]['prepend_element'] = '<i class="fa fa-bus"></i>';
                $key++;
            }
        }
        return $calendar_events;
    }
    //---------------------------------------------------------------- Booking Events End
    /**
     * Balu A
     *
     */
    function auto_suggest_booking_id()
    {
        $get_data = $this->input->get();
        if (valid_array($get_data) == true && empty($get_data['term']) == false && empty($get_data['module']) == false) {
            $this->load->model('report_model');
            $module = trim($get_data['module']);
            $chars = $get_data['term'];
            switch ($module) {
                case PROVAB_FLIGHT_BOOKING_SOURCE:
                    $list = $this->report_model->auto_suggest_flight_booking_id($chars);
                    break;
                case PROVAB_HOTEL_BOOKING_SOURCE:
                    $list = $this->report_model->auto_suggest_hotel_booking_id($chars);
                    break;
                case PROVAB_BUS_BOOKING_SOURCE:
                    $list = $this->report_model->auto_suggest_bus_booking_id($chars);
                    break;
            }
            $temp_list = array();
            if (valid_array($list) == true) {
                foreach ($list as $k => $v) {
                    $temp_list[] = array(
                        'id' => $k,
                        'label' => $v['app_reference'],
                        'value' => $v['app_reference']
                    );
                }
            }
            $this->output_compressed_data($temp_list);
        }
    }
    /**
     * Jagnaath
     * Get Bank Branches
     */
    function get_bank_branches($bank_origin)
    {
        if (intval($bank_origin) > 0) {
            $data['status'] = false;
            $data['branches'] = false;
            $branch_details = $this->custom_db->single_table_records('bank_account_details', 'origin, en_branch_name, account_number', array('origin' => intval($bank_origin), 'status' => ACTIVE));
            if ($branch_details['status'] == true) {
                $data['status'] = true;
                $data['branch'] = $branch_details['data'][0]['en_branch_name'];
                $data['account_number'] = $branch_details['data'][0]['account_number'];
            }
        }
        $this->output_compressed_data($data);
    }
    /**
     * Get Hotel Images by HotelCode
     */
    function get_hotel_images()
    {
        $post_params = $this->input->post();
        if ($post_params['hotel_code']) {
            //debug($post_params['hotel_code']);exit;
            switch ($post_params['booking_source']) {

                case PROVAB_HOTEL_BOOKING_SOURCE:
                    load_hotel_lib($post_params['booking_source']);
                    $raw_hotel_images = $this->hotel_lib->get_hotel_images($post_params['hotel_code']);

                    //debug($raw_hotel_images);exit;
                    if ($raw_hotel_images['status'] == true) {
                        $this->hotel_model->add_hotel_images($post_params['search_id'], $raw_hotel_images['data'], $post_params['hotel_code']);
                        $response['data'] = get_compressed_output(
                            $this->template->isolated_view(
                                'hotel/tbo/tbo_hotel_images',
                                array(
                                    'hotel_images' => $raw_hotel_images, 'HotelCode' => $post_params['hotel_code'], 'HotelName' => $post_params['Hotel_name']
                                )
                            )
                        );
                    }

                    break;
            }
            $this->output_compressed_data($response);
        }
        exit;
    }
    /**
     *Get Cancellation Policy based on Cancellation policy code
     *
     */
    function get_cancellation_policy_old()
    {
        $get_params = $this->input->get();

        $application_preferred_currency = get_application_currency_preference();
        $application_default_currency = get_application_currency_preference();
        $currency_obj = new Currency(array('module_type' => 'hotel', 'from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
        $room_price = $get_params['room_price'];
        //debug($get_params);exit;
        if (isset($get_params['booking_source']) && !empty($get_params['booking_source'])) {
            load_hotel_lib($get_params['booking_source']);

            if ($get_params['today_cancel_date'] == false) {
                if (isset($get_params['policy_code']) && !empty($get_params['policy_code'])) {
                    $safe_search_data = $this->hotel_model->get_safe_search_data($get_params['tb_search_id']);
                    $get_params['no_of_nights'] = $safe_search_data['data']['no_of_nights'];
                    $get_params['room_count'] = $safe_search_data['data']['room_count'];
                    $get_params['check_in'] = $safe_search_data['data']['from_date'];
                    $cancellation_details = $this->hotel_lib->get_cancellation_details($get_params);


                    $cancellatio_details = $cancellation_details['GetCancellationPolicy']['policy'][0]['policy'];
                    $policy_string = '';
                    $cancel_string = '';
                    $cancel_count = count($cancellatio_details);
                    //$cancel_reverse = $cancellatio_details; 
                    $cancel_reverse = $this->hotel_lib->php_arrayUnique($cancellatio_details, 'Charge');
                    //debug($cancellatio_details);exit;
                    $cancellatio_details = $this->hotel_lib->php_arrayUnique($cancellatio_details, 'Charge');
                    foreach ($cancellatio_details as $key => $value) {
                        $amount = 0;
                        $policy_string = '';
                        if ($value['Charge'] == 0) {
                            $policy_string .= 'No cancellation charges, if cancelled before ' . date('d M Y', strtotime($value['ToDate']));
                        } else {
                            if ($value['Charge'] != 0) {
                                if (isset($cancel_reverse[$key + 1])) {
                                    if ($value['ChargeType'] == 1) {
                                        $amount =  $currency_obj->get_currency_symbol($currency_obj->to_currency) . " " . get_converted_currency_value($currency_obj->force_currency_conversion(round($value['Charge'])));
                                    } elseif ($value['ChargeType'] == 2) {
                                        $amount = $currency_obj->get_currency_symbol($currency_obj->to_currency) . " " . $room_price;
                                    }
                                    $current_date = date('Y-m-d');
                                    $cancell_date = date('Y-m-d', strtotime($value['FromDate']));
                                    if ($cancell_date > $current_date) {
                                        //$value['FromDate'] = date('Y-m-d');
                                        $policy_string .= 'Cancellations made after ' . date('d M Y', strtotime($value['FromDate'])) . ' to ' . date('d M Y', strtotime($value['ToDate'])) . ', would be charged ' . $amount;
                                    }
                                    //$policy_string .='Cancellations made after '.date('d M Y',strtotime($value['FromDate'])).' to '.date('d M Y',strtotime($value['ToDate'])).', would be charged '.$amount;

                                } else {
                                    if ($value['ChargeType'] == 1) {
                                        $amount =  $currency_obj->get_currency_symbol($currency_obj->to_currency) . " " . get_converted_currency_value($currency_obj->force_currency_conversion(round($value['Charge'])));
                                    } elseif ($value['ChargeType'] == 2) {
                                        $amount = $currency_obj->get_currency_symbol($currency_obj->to_currency) . " " . $room_price;
                                    }
                                    $current_date = date('Y-m-d');
                                    $cancell_date = date('Y-m-d', strtotime($value['FromDate']));
                                    if ($cancell_date > $current_date) {
                                        $value['FromDate'] = $value['FromDate'];
                                    } else {
                                        $value['FromDate'] = date('Y-m-d');
                                    }
                                    $policy_string .= 'Cancellations made after ' . date('d M Y', strtotime($value['FromDate'])) . ', or no-show, would be charged ' . $amount;
                                }
                            }
                        }
                        $cancel_string .= $policy_string . '<br/> ';
                    }

                    echo $cancel_string;
                    //echo $cancellation_details['GetCancellationPolicy']['policy'][0];
                } else {
                    $cancel_string = '';
                    $cancellation_policy_details = json_decode(base64_decode($get_params['policy_details']));
                    //debug($cancellation_policy_details);
                    $cancel_count = count($cancellation_policy_details);
                    $cancellation_policy_details = json_decode(json_encode($cancellation_policy_details), True);
                    $cancel_reverse = $this->hotel_lib->php_arrayUnique(array_reverse($cancellation_policy_details), 'Charge');
                    //$cancel_reverse = array_reverse($cancellation_policy_details);

                    //debug($cancel_reverse);						
                    $cancellation_policy_details = $this->hotel_lib->php_arrayUnique(array_reverse($cancellation_policy_details), 'Charge');

                    if ($cancellation_policy_details) {
                        //$cancellation_policy_details = array_reverse($cancellation_policy_details);
                        foreach ($cancellation_policy_details as $key => $value) {
                            $policy_string = '';
                            if ($value['Charge'] == 0) {
                                $policy_string .= 'No cancellation charges, if cancelled before ' . date('d M Y', strtotime($value['ToDate']));
                            } else {
                                if (isset($cancel_reverse[$key + 1])) {
                                    if ($value['ChargeType'] == 1) {
                                        $amount = $currency_obj->get_currency_symbol($currency_obj->to_currency) . "  " . $value['Charge'];
                                    } elseif ($value['ChargeType'] == 2) {
                                        $amount = $currency_obj->get_currency_symbol($currency_obj->to_currency) . "  " . $room_price;
                                    }
                                    $current_date = date('Y-m-d');
                                    $cancell_date = date('Y-m-d', strtotime($value['FromDate']));
                                    if ($cancell_date > $current_date) {
                                        $policy_string .= 'Cancellations made after ' . date('d M Y', strtotime($value['FromDate'])) . ' to ' . date('d M Y', strtotime($value['ToDate'])) . ', would be charged ' . $amount;
                                    }
                                } else {
                                    if ($value['ChargeType'] == 1) {
                                        $amount = $currency_obj->get_currency_symbol($currency_obj->to_currency) . "  " . $value['Charge'];
                                    } elseif ($value['ChargeType'] == 2) {
                                        $amount = $currency_obj->get_currency_symbol($currency_obj->to_currency) . "  " . $room_price;
                                    }
                                    $current_date = date('Y-m-d');
                                    $cancell_date = date('Y-m-d', strtotime($value['FromDate']));
                                    if ($cancell_date > $current_date) {
                                        $value['FromDate'] = $value['FromDate'];
                                    } else {
                                        $value['FromDate'] = date('Y-m-d');
                                    }
                                    $policy_string .= 'Cancellations made after ' . date('d M Y', strtotime($value['FromDate'])) . ', or no-show, would be charged ' . $amount;
                                }
                            }

                            $cancel_string .= $policy_string . '<br/>';
                        }
                    } else {
                        $cancel_string = 'This rate is non-refundable. If you cancel this booking you will not be refunded any of the payment.';
                    }


                    echo $cancel_string;
                }
            } else {
                echo "This rate is non-refundable. If you cancel this booking you will not be refunded any of the payment.";
            }
        } else {
            echo "This rate is non-refundable. If you cancel this booking you will not be refunded any of the payment.";
        }
        exit;
    }
    function get_cancellation_policy()
    {
        $get_params = $this->input->get();
        //debug($get_params);exit;
        $application_preferred_currency = get_application_currency_preference();
        $application_default_currency = get_application_currency_preference();
        $currency_obj = new Currency(array('module_type' => 'hotel', 'from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
        $room_price = $get_params['room_price'];
        //debug($get_params);exit;
        if (isset($get_params['booking_source']) && !empty($get_params['booking_source'])) {
            load_hotel_lib($get_params['booking_source']);

            if ($get_params['today_cancel_date'] == false) {
                if (isset($get_params['policy_code']) && !empty($get_params['policy_code'])) {
                    $safe_search_data = $this->hotel_model->get_safe_search_data($get_params['tb_search_id']);
                    $get_params['no_of_nights'] = $safe_search_data['data']['no_of_nights'];
                    $get_params['room_count'] = $safe_search_data['data']['room_count'];
                    $get_params['check_in'] = $safe_search_data['data']['from_date'];
                    $cancellation_details = $this->hotel_lib->get_cancellation_details($get_params);
                    $cancellatio_details = $cancellation_details['GetCancellationPolicy']['policy'][0]['policy'];

                    $policy_string = '';
                    $cancel_string = '';
                    $cancel_count = count($cancellatio_details);
                    //$cancel_reverse = $cancellatio_details; 
                    $cancel_reverse = $this->hotel_lib->php_arrayUnique($cancellatio_details, 'Charge');
                    //debug($cancellatio_details);exit;
                    $cancellatio_details = $this->hotel_lib->php_arrayUnique($cancellatio_details, 'Charge');
                    foreach ($cancellatio_details as $key => $value) {

                        $value['Charge'] = $this->hotel_lib->update_cancellation_markup_currency($value['Charge'], $currency_obj, $get_params['search_id']);
                        $amount = 0;
                        $policy_string = '';
                        if ($value['Charge'] == 0) {
                            $policy_string .= 'No cancellation charges, if cancelled before ' . date('d M Y', strtotime($value['ToDate']));
                        } else {
                            if ($value['Charge'] != 0) {
                                if (isset($cancel_reverse[$key + 1])) {
                                    if ($value['ChargeType'] == 1) {
                                        $amount =  $currency_obj->get_currency_symbol($currency_obj->to_currency) . " " . round($value['Charge']);
                                    } elseif ($value['ChargeType'] == 2) {
                                        $amount = $currency_obj->get_currency_symbol($currency_obj->to_currency) . " " . $room_price;
                                    }
                                    $current_date = date('Y-m-d');
                                    $cancell_date = date('Y-m-d', strtotime($value['FromDate']));
                                    if ($cancell_date > $current_date) {
                                        //$value['FromDate'] = date('Y-m-d');
                                        $policy_string .= 'Cancellations made after ' . date('d M Y', strtotime($value['FromDate'])) . ' to ' . date('d M Y', strtotime($value['ToDate'])) . ', would be charged ' . $amount;
                                    }
                                    //$policy_string .='Cancellations made after '.date('d M Y',strtotime($value['FromDate'])).' to '.date('d M Y',strtotime($value['ToDate'])).', would be charged '.$amount;

                                } else {
                                    if ($value['ChargeType'] == 1) {
                                        $amount =  $currency_obj->get_currency_symbol($currency_obj->to_currency) . " " . round($value['Charge']);
                                    } elseif ($value['ChargeType'] == 2) {
                                        $amount = $currency_obj->get_currency_symbol($currency_obj->to_currency) . " " . $room_price;
                                    }
                                    $current_date = date('Y-m-d');
                                    $cancell_date = date('Y-m-d', strtotime($value['FromDate']));
                                    if ($cancell_date > $current_date) {
                                        $value['FromDate'] = $value['FromDate'];
                                    } else {
                                        $value['FromDate'] = date('Y-m-d');
                                    }
                                    $policy_string .= 'Cancellations made after ' . date('d M Y', strtotime($value['FromDate'])) . ', or no-show, would be charged ' . $amount;
                                }
                            }
                        }
                        $cancel_string .= $policy_string . '<br/> ';
                    }

                    echo $cancel_string;
                    //echo $cancellation_details['GetCancellationPolicy']['policy'][0];
                } else {
                    $cancel_string = '';
                    $cancellation_policy_details = json_decode(base64_decode($get_params['policy_details']));
                    //debug($cancellation_policy_details);
                    $cancel_count = count($cancellation_policy_details);
                    $cancellation_policy_details = json_decode(json_encode($cancellation_policy_details), True);
                    $cancel_reverse = $this->hotel_lib->php_arrayUnique(array_reverse($cancellation_policy_details), 'Charge');
                    //$cancel_reverse = array_reverse($cancellation_policy_details);

                    //debug($cancel_reverse);						
                    $cancellation_policy_details = $this->hotel_lib->php_arrayUnique(array_reverse($cancellation_policy_details), 'Charge');

                    if ($cancellation_policy_details) {
                        //$cancellation_policy_details = array_reverse($cancellation_policy_details);
                        foreach ($cancellation_policy_details as $key => $value) {
                            $policy_string = '';
                            if ($value['Charge'] == 0) {
                                $policy_string .= 'No cancellation charges, if cancelled before ' . date('d M Y', strtotime($value['ToDate']));
                            } else {
                                if (isset($cancel_reverse[$key + 1])) {
                                    if ($value['ChargeType'] == 1) {
                                        $amount = $currency_obj->get_currency_symbol($currency_obj->to_currency) . "  " . $value['Charge'];
                                    } elseif ($value['ChargeType'] == 2) {
                                        $amount = $currency_obj->get_currency_symbol($currency_obj->to_currency) . "  " . $room_price;
                                    }
                                    $current_date = date('Y-m-d');
                                    $cancell_date = date('Y-m-d', strtotime($value['FromDate']));
                                    if ($cancell_date > $current_date) {
                                        $policy_string .= 'Cancellations made after ' . date('d M Y', strtotime($value['FromDate'])) . ' to ' . date('d M Y', strtotime($value['ToDate'])) . ', would be charged ' . $amount;
                                    }
                                } else {
                                    if ($value['ChargeType'] == 1) {
                                        $amount = $currency_obj->get_currency_symbol($currency_obj->to_currency) . "  " . $value['Charge'];
                                    } elseif ($value['ChargeType'] == 2) {
                                        $amount = $currency_obj->get_currency_symbol($currency_obj->to_currency) . "  " . $room_price;
                                    }
                                    $current_date = date('Y-m-d');
                                    $cancell_date = date('Y-m-d', strtotime($value['FromDate']));
                                    if ($cancell_date > $current_date) {
                                        $value['FromDate'] = $value['FromDate'];
                                    } else {
                                        $value['FromDate'] = date('Y-m-d');
                                    }
                                    $policy_string .= 'Cancellations made after ' . date('d M Y', strtotime($value['FromDate'])) . ', or no-show, would be charged ' . $amount;
                                }
                            }

                            $cancel_string .= $policy_string . '<br/>';
                        }
                    } else {
                        $cancel_string = 'This rate is non-refundable. If you cancel this booking you will not be refunded any of the payment.';
                    }


                    echo $cancel_string;
                }
            } else {
                echo "This rate is non-refundable. If you cancel this booking you will not be refunded any of the payment.";
            }
        } else {
            echo "This rate is non-refundable. If you cancel this booking you will not be refunded any of the payment.";
        }
        exit;
    }
    /**
     *Load hotels for map
     */
    function get_all_hotel_list()
    {
        $response['data'] = '';
        $response['msg'] = '';
        $response['status'] = FAILURE_STATUS;
        $search_params = $this->input->get();
        $limit = $this->config->item('hotel_per_page_limit');
        if ($search_params['op'] == 'load' && intval($search_params['search_id']) > 0 && isset($search_params['booking_source']) == true) {
            load_hotel_lib($search_params['booking_source']);
            switch ($search_params['booking_source']) {
                case PROVAB_HOTEL_BOOKING_SOURCE:
                    //getting search params from table
                    $safe_search_data = $this->hotel_model->get_safe_search_data($search_params['search_id']);
                    //Meaning hotels are loaded first time
                    $raw_hotel_list = $this->hotel_lib->get_hotel_list(abs($search_params['search_id']));
                    //debug($raw_hotel_list);exit;
                    if ($raw_hotel_list['status']) {
                        //Converting API currency data to preferred currency
                        $currency_obj = new Currency(array('module_type' => 'hotel', 'from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
                        $raw_hotel_list = $this->hotel_lib->search_data_in_preferred_currency($raw_hotel_list, $currency_obj);
                        //Display 
                        $currency_obj = new Currency(array('module_type' => 'hotel', 'from' => get_application_currency_preference(), 'to' => get_application_currency_preference()));
                        //Update currency and filter summary appended
                        if (isset($search_params['filters']) == true and valid_array($search_params['filters']) == true) {
                            $filters = $search_params['filters'];
                        } else {
                            $filters = array();
                        }
                        $attr['search_id'] = abs($search_params['search_id']);
                        $raw_hotel_search_result = array();
                        $i = 0;
                        $counter = 0;
                        if ($max_lat == 0) {
                            $max_lat = $min_lat = 0;
                        }

                        if ($max_lon == 0) {
                            $max_lon = $min_lon = 0;
                        }
                        if ($raw_hotel_list['data']['HotelSearchResult']) {
                            foreach ($raw_hotel_list['data']['HotelSearchResult']['HotelResults'] as $key => $value) {
                                $raw_hotel_search_result[$i] = $value;
                                $raw_hotel_search_result[$i]['MResultToken'] = urlencode($value['ResultToken']);
                                $lat = $value['Latitude'];
                                $lon = $value['Longitude'];
                                if (($lat != '') && ($counter < 1)) {
                                    $max_lat = $min_lat = $lat;
                                }
                                if (($lon != '')) {
                                    $counter++;
                                    $max_lon = $min_lon = $lon;
                                }

                                $i++;
                            }
                            $raw_hotel_list['data']['HotelSearchResult']['max_lat']  = $max_lat;
                            $raw_hotel_list['data']['HotelSearchResult']['max_lon']  = $max_lon;
                        }
                        $raw_hotel_list['data']['HotelSearchResult']['HotelResults'] = $raw_hotel_search_result;
                        //debug($raw_hotel_list);exit;
                        $response['data'] = $raw_hotel_list['data'];
                        $response['status'] = SUCCESS_STATUS;
                    }
                    break;
            }
        }
        $this->output_compressed_data($response);
    }
    /**
     * sagar 
     * Get All Cities
     */
    public function get_city_lists()
    {
        $country_id = $this->input->post('country_id');
        $get_resulted_data =  $this->custom_db->single_table_records('api_city_list', '*', array('country' => $country_id), 0, 100000000, array('destination' => 'asc'));
        if (!empty($get_resulted_data['data'])) {
            $html = "<option value=''>Select City</option>";
            foreach ($get_resulted_data['data'] as  $get_resulted_data_sub) {

                $html = $html . "<option value=" . $get_resulted_data_sub['origin'] . ">" . $get_resulted_data_sub['destination'] . "</option>";
            }
        } else {
            $html = "<option value=''>No City Found</option>";
        }
        echo $html;
        exit;
    }

    function user_traveller_details()
    {
        $term = $this->input->get('term'); //retrieve the search term that autocomplete sends
        $term = trim($term);
        $result = array();
        $this->load->model('user_model');
        $traveller_details = $this->user_model->user_traveller_details($term)->result();
        $travllers_data = array();
        foreach ($traveller_details as $traveller) {
            $travllers_data['category'] = 'Travellers';
            $travllers_data['id'] = $traveller->origin;
            $travllers_data['label'] = trim($traveller->first_name . ' ' . $traveller->last_name);
            $travllers_data['value'] = trim($traveller->first_name);
            $travllers_data['first_name'] = trim($traveller->first_name);
            $travllers_data['last_name'] = trim($traveller->last_name);
            $travllers_data['date_of_birth'] = date('Y-m-d', strtotime(trim($traveller->date_of_birth)));
            $travllers_data['email'] = trim($traveller->email);
            $travllers_data['passport_user_name'] = trim($traveller->passport_user_name);
            $travllers_data['passport_nationality'] = trim($traveller->passport_nationality);
            $travllers_data['passport_expiry_day'] = trim($traveller->passport_expiry_day);
            $travllers_data['passport_expiry_month'] = trim($traveller->passport_expiry_month);
            $travllers_data['passport_expiry_year'] = trim($traveller->passport_expiry_year);
            $travllers_data['passport_number'] = trim($traveller->passport_number);
            $travllers_data['passport_issuing_country'] = trim($traveller->passport_issuing_country);
            array_push($result, $travllers_data);
        }
        $this->output_compressed_data($result);
    }
    public function send_flight_details_mail()
    {
        $params = $this->input->post();


        //$cc=json_encode($params);
        //  $dd['flightdetails']=$cc;
        //$this->custom_db->insert_record('test22', $dd);


        $flight_details = json_decode($params['flightdetails'], true);
        // debug($flight_details['Token']);
        load_flight_lib($params['booking_source']);
        $data_access_key = $flight_details['Token'];
        $params['data_access_key'] = unserialized_data($flight_details['Token']);
        // debug($params['data_access_key']);
        $email = $params['email'];
        if (empty($params['data_access_key']) == false) {
            switch ($params['booking_source']) {
                case PROVAB_FLIGHT_BOOKING_SOURCE:
                    $params['data_access_key'] = $this->flight_lib->read_token($data_access_key);
                    $data = $this->flight_lib->get_fare_details($params['data_access_key'], $params['search_access_key']);
                    $data['msg'] = '<i class="fa fa-warning text-danger"></i> Fare Details Not Available';
                    if ($data['status'] == SUCCESS_STATUS) {

                        $data['msg'] = 'Fare Details Available';
                    }
                    $page_data['flight_details'] = $flight_details;
                    $page_data['fare_rules'] = $data;
                    // debug($page_data['fare_rules']);
                    // $this->template->view('flight/flight_details_template', $page_data);
                    $mail_template = $this->template->isolated_view('flight/flight_details_template', $page_data);
                    $this->load->library('provab_mailer');
                    //$this->provab_mailer->send_mail('sagar@provab.com', 'New-Agent Registered', $mail_template);
                    $subject = 'Flight Details-www.' . $_SERVER['HTTP_HOST'];
                    $mail_status = $this->provab_mailer->send_mail($email, $subject, $mail_template);
                    // debug($mail_status);
                    echo TRUE;
                    die;
                case PLAZMA_BOOKING_SOURCE:
                    $params['data_access_key'] = $this->flight_lib->read_token($data_access_key);
                    $data = $this->flight_lib->get_fare_details($params['data_access_key'], $params['search_access_key']);
                    $data['msg'] = '<i class="fa fa-warning text-danger"></i> Fare Details Not Available';
                    if ($data['status'] == SUCCESS_STATUS) {

                        $data['msg'] = 'Fare Details Available';
                    }
                    $page_data['flight_details'] = $flight_details;
                    $page_data['fare_rules'] = $data;
                    // debug($page_data['fare_rules']);
                    // $this->template->view('flight/flight_details_template', $page_data);
                    $mail_template = $this->template->isolated_view('flight/flight_details_template', $page_data);
                    $this->load->library('provab_mailer');
                    //$this->provab_mailer->send_mail('sagar@provab.com', 'New-Agent Registered', $mail_template);
                    $subject = 'Flight Details-.' . $_SERVER['HTTP_HOST'];
                    $mail_status = $this->provab_mailer->send_mail($email, $subject, $mail_template);
                    // debug($mail_status);
                    echo TRUE;
                    die;
                case AMADEUS_FLIGHT_BOOKING_SOURCE:
                    $params['data_access_key'] = $this->flight_lib->read_token($data_access_key);
                    $data = $this->flight_lib->get_fare_details($params['data_access_key'], $params['search_access_key']);
                    $data['msg'] = '<i class="fa fa-warning text-danger"></i> Fare Details Not Available';
                    if ($data['status'] == SUCCESS_STATUS) {

                        $data['msg'] = 'Fare Details Available';
                    }
                    $page_data['flight_details'] = $flight_details;
                    $page_data['fare_rules'] = $data;
                    // debug($page_data['fare_rules']);
                    // $this->template->view('flight/flight_details_template', $page_data);
                    $mail_template = $this->template->isolated_view('flight/flight_details_template', $page_data);
                    $this->load->library('provab_mailer');
                    //$this->provab_mailer->send_mail('sagar@provab.com', 'New-Agent Registered', $mail_template);
                    $subject = 'Flight Details-www.' . $_SERVER['HTTP_HOST'];
                    $mail_status = $this->provab_mailer->send_mail($email, $subject, $mail_template);
                    // debug($mail_status);
                    echo TRUE;
                    die;
            }
        } else {
            echo FALSE;
        }
    }
    function get_holiday_city_list()
    {

        $this->load->model('tours_model');
        $term = $this->input->get('term'); //retrieve the search term that autocomplete sends
        $term = trim(strip_tags($term));
        $data_list = $this->tours_model->get_holiday_city_list($term);
        if (valid_array($data_list) == false) {
            $data_list = $this->sightseeing_model->get_holiday_city_list('');
        }
        $suggestion_list = array();
        $result = array();
        foreach ($data_list as $city_list) {
            $suggestion_list['label'] = $city_list['city_name'];

            $suggestion_list['value'] = $city_list['city_name'];

            //  $suggestion_list['value'] = hotel_suggestion_value($city_list['city_name'], $city_list['country_name']);
            $suggestion_list['id'] = $city_list['id'];
            if (empty($city_list['top_destination']) == false) {
                $suggestion_list['category'] = 'Top cities';
                $suggestion_list['type'] = 'Top cities';
            } else {
                $suggestion_list['category'] = 'Location list';
                $suggestion_list['type'] = 'Location list';
            }

            $suggestion_list['count'] = 0;
            $result[] = $suggestion_list;
        }
        $this->output_compressed_data($result);
    }
    public function hotel_list_check_amenities()
    {

        $response['data'] = array();
        $response['msg'] = array();
        $response['status'] = FAILURE_STATUS;
        $search_params = $this->input->get();

        // debug($search_params);exit;
        $limit = $this->config->item('hotel_per_page_limit');
        $wifi_count = 0;
        $break_fast_count = 0;
        $parking_count = 0;
        $swim_pool = 0;
        if ($search_params['op'] == 'load' && intval($search_params['search_id']) > 0 && isset($search_params['booking_source']) == true) {
            load_hotel_lib($search_params['booking_source']);
            switch ($search_params['booking_source']) {
                case PROVAB_HOTEL_BOOKING_SOURCE:
                    //getting search params from table
                    $safe_search_data = $this->hotel_model->get_safe_search_data($search_params['search_id']);

                    //Meaning hotels are loaded first time
                    $raw_hotel_list = $this->hotel_lib->get_hotel_list(abs($search_params['search_id']), $search_params);
                    // debug($raw_hotel_list);exit;

                    if ($raw_hotel_list['status']) {
                        // debug($raw_hotel_list['data']['HotelSearchResult']['HotelResults'][0]['HotelAmenities']);
                        foreach ($raw_hotel_list['data']['HotelSearchResult']['HotelResults'] as $key => $value) {
                            if ($value['HotelAmenities'] != "") {

                                // debug("ff");exit;
                                if (isset($value['HotelAmenities']) && valid_array($value['HotelAmenities'])) {

                                    $wi_fi_searchparmas = 'Wi';
                                    $wi_search = ucwords('wi-');
                                    $wi_fi_small = 'wifi';
                                    if ($this->hotel_lib->searchParams($value['HotelAmenities'], $wi_fi_searchparmas)) {
                                        $wifi_count++;
                                    } elseif ($this->hotel_lib->searchParams($value['HotelAmenities'], $wi_search)) {
                                        $wifi_count++;
                                    } elseif ($this->hotel_lib->searchParams($value['HotelAmenities'], $wi_fi_small)) {
                                        $wifi_count++;
                                    }



                                    $breakfast_smal = 'breakfast';
                                    $breakfast = 'Breakfast';
                                    if ($this->hotel_lib->searchParams($value['HotelAmenities'], $breakfast_smal)) {
                                        $break_fast_count++;
                                    } elseif ($this->hotel_lib->searchParams($value['HotelAmenities'], $breakfast)) {
                                        $break_fast_count++;
                                    }



                                    $parking = 'parking';
                                    $park = 'park';
                                    if ($this->hotel_lib->searchParams($value['HotelAmenities'], $parking)) {
                                        $parking_count++;
                                    } elseif ($this->hotel_lib->searchParams($value['HotelAmenities'], $park)) {
                                        $parking_count++;
                                    }



                                    $pool = 'pool';
                                    $swim = 'Swim';
                                    if ($this->hotel_lib->searchParams($value['HotelAmenities'], $pool)) {
                                        $swim_pool++;
                                    } elseif ($this->hotel_lib->searchParams($value['HotelAmenities'], $swim)) {
                                        $swim_pool++;
                                    }
                                }
                            }
                        }
                    }



                    break;
            }
        }
        $response['status'] = SUCCESS_STATUS;
        $dd = array('wifi_count' => $wifi_count, 'break_fast_count' => $break_fast_count, 'parking_count' => $parking_count, 'swim_pool' => $swim_pool);
        $response['data'] = $dd;
        $this->output_compressed_data($response);
    }
}
