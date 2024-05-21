<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 *
 * @package    Provab
 * @subpackage General
 * @author     Balu A<balu.provab@gmail.com>
 * @version    V1
 */
class General extends CI_Controller {
    public function __construct() {
        parent::__construct();
        
        $this->load->model('user_model');
        $this->load->model('Package_Model');
        $this->load->model('custom_db');
         $this->load->model('visa_model');
    }
    public function testtt(){
      $qq=$this->db->query('select * from all_api_city_master where city_name="Hyderabad"');
      $dd=$qq->result_array();
     
    }
    function test1() {
        $post = $this->input->post();
        $post['lang'] = 'hi';
        $this->session->set_userdata('lang', $post['lang']);
        
    }
    function checkmybooking(){

        $pagedata=array();
        $this->template->view('cms/checkmybooking', $page_data);
      
    }
    function processcheckbooking()
    {
       $data=$this->input->post();
       if($data['pnr']!="")
       {
            $query="select * from flight_booking_transaction_details where pnr='".$data['pnr']."'";
            $re_count=$this->db->query($query)->num_rows();
            if($re_count>0)
            {
                $row=$this->db->query($query)->result_array();
             //   debug($row);die;
                $book_id=$row[0]['app_reference'];
                 $voucherquery="select * from flight_booking_details where app_reference='".$book_id."'";
                  $voucherrow=$this->db->query($voucherquery)->result_array();
                $booking_source=$voucherrow[0]['booking_source'];
                $status=$row[0]['status'];
                redirect(base_url() . 'index.php/voucher/flight/' . $book_id . '/' . $booking_source. '/' . $status . '/show_voucher');
            }
           else
           {
             $this->session->set_flashdata(array('message' => 'AL009', 'type' => ERROR_MESSAGE));
             redirect('general/checkmybooking');
           }
      }

    }
    function saverescheduleflights()
    {
        $data=$this->input->post();
        $query="select * from flight_booking_transaction_details where pnr='".$data['ticketnumber']."'";
        $re_count=$this->db->query($query)->num_rows();
        $getdetail=$this->db->query($query)->result_array();
          $query2="select * from flight_booking_details where app_reference='".$getdetail[0]['app_reference']."'";
        $getdetail2=$this->db->query($query2)->result_array(); 
     //   debug($getdetail);die;
        if($re_count>0)
        {
        $data['date'] = date("Y-m-d", strtotime($data['date']));
        $data['created_at']=date('Y-m-d');
        $data['updated_at']=date('Y-m-d');
        $email=$data['email'];
        $fullname=$data['fullname'];
        unset($data['fullname']);
        unset($data['submit']);
        unset($data['email']);
        $this->custom_db->insert_record('reschedule_flight', $data);
        $this->load->library ( 'provab_mailer' );
        $data['fullname']=$fullname;
         if($getdetail2[0]['booking_source']=="PTBSID0000000002")
        {
            $data['module']='TMX';
        }
        else
        {
 $data['module']='Amadeus';
        }
        $mail_template = $this->template->isolated_view('cms/rescheduleflights_emailtemplate', $data);
        $res=$this->provab_mailer->send_mail($email,'Reschedule flight  '.$getdetail[0]['app_reference'].'///'.$data['module'].'///'.$data['ticketnumber'],$mail_template ,''); 
        $this->session->set_flashdata(array('message' => 'AL008', 'type' => SUCCESS_MESSAGE));
         redirect('general/rescheduleflights');
     }
     else
     {
         $this->session->set_flashdata(array('message' => 'AL009', 'type' => ERROR_MESSAGE));
         redirect('general/rescheduleflights');
     }
        


    }
   
    function savecanceltime()
    {
        $data=$this->input->post();
        $query="select * from flight_booking_transaction_details where pnr='".$data['PNR']."'";
        $re_count=$this->db->query($query)->num_rows();
       $getdetail=$this->db->query($query)->result_array(); 
        $query2="select * from flight_booking_details where app_reference='".$getdetail[0]['app_reference']."'";
        $getdetail2=$this->db->query($query2)->result_array(); 

    if($re_count>0)
        {
         $data['created_at']=date('Y-m-d');
        $data['updated_at']=date('Y-m-d');
         $email=$data['email'];
        unset($data['submit']);
         unset($data['email']);
        $this->custom_db->insert_record('cancellation_timeline_policy', $data);
        $this->load->library ( 'provab_mailer' );
        if($getdetail2[0]['booking_source']=="PTBSID0000000002")
        {
            $data['module']='TMX';
        }
        else
        {
 $data['module']='Amadeus';
        }
        $mail_template = $this->template->isolated_view('cms/canceltime_emailtemplate', $data);
         $res=$this->provab_mailer->send_mail($email,'CANCELLATION REQUEST  '.$getdetail[0]['app_reference'].'///'.$data['module'].'///'.$data['PNR'],$mail_template ,''); 
        $this->session->set_flashdata(array('message' => 'AL008', 'type' => SUCCESS_MESSAGE));
        redirect('general/canceltime');
        }
     else
     {
         $this->session->set_flashdata(array('message' => 'AL009', 'type' => ERROR_MESSAGE));
         redirect('general/rescheduleflights');
     }
    }
    /*
    function customersupport_old(){

        $pagedata=array();
          $this->template->view('cms/customersupport', $page_data);
      
    }
    */
    //  changes Added new function customersupport
     public function customersupport()
  {
    $page_data = array();
    $this->template->view('general/customersupport', $page_data);
  }
  //changes New function to handle customersupport functionality
  public function contactFormHandle()
  {
    $this->load->library('form_validation');
    $this->form_validation->set_rules('email', 'Email', 'valid_email|required|max_length[80]');
    $this->form_validation->set_rules('name', 'Name', 'xss_clean|required|min_length[2]|max_length[45]');
    $this->form_validation->set_rules('phone', 'Phone', 'xss_clean|required|min_length[10]|max_length[10]');
    $this->form_validation->set_rules('message', 'Message', 'xss_clean|required|min_length[20]|max_length[500]');
    if ($this->form_validation->run()) {
      $data = $this->input->post();
      $name = $data['name'];
      $email = $data['email'];
      $phoneNumber = $data['phone'];
      $message = $data['message'];
      $nameArray = explode(" ", $name);
      $lastName = $nameArray[1];

      $customer_data = [
        "module" => "Customer Support",
        "user_name" => $name,
        "user_lname" => $lastName ?? "Last Name",
        "user_email" => $email,
        "user_mobile" => $phoneNumber,
        "comment" => $message,
        "tour_id" => 1,
        "created"=> date('Y-m-d H:i:s')
      ];
      // debug($customer_data);
      // die;

      $creation = $this->custom_db->insert_record('general_user_review', $customer_data);
      if ($creation) {

        $this->session->set_flashdata("ContactSuccess", "Thank You for contacting us!We appreciate it.We'll reach out to you as soon as we can.");

      } else {
        $this->session->set_flashdata("ContactFailure", "Something went wrong. Please try again!");
      }


    } else {
      $validation_errors = array(
        'email' => form_error('email'),
        'name' => form_error('name'),
        'phone' => form_error('phone'),
        'message' => form_error('message')


      );
      $previousData = $this->input->post();

      $this->session->set_userdata("errors", $validation_errors);


      $this->session->set_userdata("previousData", $previousData);
      redirect(base_url() . "general/customersupport");



    }
    redirect(base_url() . "general/customersupport");

    //    header('content-type:application/json');
    // echo json_encode(array('data' => $data));
  } 
     function refundinfo(){
        $pagedata=array();

          $this->template->view('cms/refundinfo', $page_data);
      
    }
     function canceltime_emailtemplate(){
      $pagedata=array();
       echo  $this->template->isolated_view('cms/canceltime_emailtemplate', $page_data);
    }
     function rescheduleflights_emailtemplate(){
           $pagedata=array();
        echo   $this->template->isolated_view('cms/rescheduleflights_emailtemplate', $page_data);
      
    }
     function canceltime(){
      $pagedata=array();
        $this->template->view('cms/canceltime', $page_data);
    }
     function rescheduleflights(){
           $pagedata=array();
          $this->template->view('cms/rescheduleflights', $page_data);
      
    }
    function supplier_term(){
        
        $page_data['data_list'] = $this->custom_db->single_table_records('terms_condition_supplier', '*');
        $this->template->view('user/supplerterm', $page_data);
    }

    /**
     * index page of application will be loaded here
     */
    function indexold($default_view = '') {
      $master_module_list = $GLOBALS ['CI']->config->item('master_module_list');
      $default_vie=$GLOBALS ['CI']->uri->segment(1);
      
      if($default_vie=="")
      {
        $default_vie="flights";
      }
      foreach ($master_module_list as $k => $v)
      {
          if($default_vie==$v)
          {
            $dft_v=$k;
          } 
      }

        /* Package Data */
        $data['caption'] = $this->Package_Model->getPageCaption('tours_packages')->row();
        $data['packages'] = $this->Package_Model->getAllPackages();
        $data['countries'] = $this->Package_Model->getPackageCountries_new();
        $data['package_types'] = $this->Package_Model->getPackageTypes();
        /* Banner_Images */
        $de_view = ($GLOBALS ['CI']->uri->segment(1))?$GLOBALS ['CI']->uri->segment(1) :'flights';
        $domain_origin = get_domain_auth_id();
        $page_data['banner_images'] = $this->custom_db->single_table_records('banner_images', '*', array('added_by' => $domain_origin, 'status' => '1','module' => $de_view), '', '100000000', array('banner_order' => 'ASC'));
        $page_data['countrylist'] = $this->custom_db->single_table_records('api_country_list', '*');
        /* Package Data */
        
        $page_data['default_view'] = @$dft_v;
        $page_data['holiday_data'] = $data; //Package Data

        if (is_active_airline_module()) {
            $this->load->model('flight_model');
             $page_data['top_destination_flight'] = $this->flight_model->flight_top_destinations();
             $page_data['second_flight_destination_package'] = $this->Package_Model->flight_get_package_top_destination();
        }
        if (is_active_bus_module()) {
            $this->load->model('bus_model');
        }
        if (is_active_hotel_module()) {
            $this->load->model('hotel_model');
            $page_data['top_destination_hotel'] = $this->hotel_model->hotel_top_destinations();
            $page_data['second_hotel_destination_package'] = $this->Package_Model->hotel_get_package_top_destination();
           
        }
        if (is_active_sightseeing_module()) {
            $filter = array (
                'top_destination_activity' => ACTIVE 
            );
           
            $data_list = $this->custom_db->single_table_records ( 'api_sightseeing_destination_list', '*', $filter, 0, 100000, array (
                'top_destination_activity' => 'DESC',
                'destination_name' => 'ASC' 
            ) );
            $page_data['top_destination_activity'] =  @$data_list ['data'];
             $page_data['second_activity_destination_package'] = $this->Package_Model->activity_get_package_top_destination();
       
        }
        if (is_active_sightseeing_module()) {
            $filter = array (
                'top_destination_transfers' => ACTIVE 
            );
           
            $data_list = $this->custom_db->single_table_records ( 'api_sightseeing_destination_list', '*', $filter, 0, 100000, array (
                'top_destination_transfers' => 'DESC',
                'destination_name' => 'ASC' 
            ) );
            $page_data['top_destination_tranfers'] =  @$data_list ['data'];
            $page_data['second_transfer_destination_package'] = $this->Package_Model->transfer_get_package_top_destination();
          
        }

         if (is_active_car_module()) {
          $this->load->model('car_model');

          $filter = array (
        'top_destination' => ACTIVE 
    );
          $data_list = $this->custom_db->single_table_records ( 'Car_Airport', '*', $filter, 0, 100000, array (
        'top_destination' => 'DESC'
         
    ) );
           $page_data['second_car_destination_package'] = $this->Package_Model->car_get_package_top_destination();
         }

         $page_data['top_destination_car']=$data_list['data'];
    
        if (is_active_package_module()) {
            $this->load->model('package_model');
            $top_package = $this->package_model->get_package_top_destination();
            $page_data['top_destination_package'] = $top_package['data'];
          
            $page_data['total'] = $top_package['total'];
        }
        
        $currency_obj = new Currency(array('module_type' => 'hotel', 'from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
        $page_data['currency_obj'] = $currency_obj;
        $getSlideImages = $page_data['banner_images']['data'];
        
        $slideImageArray = array();

        foreach ($getSlideImages as $k) {
            
          $slideImageArray[] = array('image' => base_url(). 'extras/system/template_list/template_v1/images/' . $k['image'], 'title' => $k['title'], 'description' => $k['subtitle']);
        }
        $page_data['slideImageJson'] = $slideImageArray;

        $page_data['flight_promo'] = $this->custom_db->single_table_records('promo_code_list','*',array('module' => 'flight', 'status' => 1),'','10',array('origin' => 'ASC'));
         $page_data['transfers_promo'] = $this->custom_db->single_table_records('promo_code_list','*',array('module' => 'transfers', 'status' => 1),'','10',array('origin' => 'ASC'));
          $page_data['activity_promo'] = $this->custom_db->single_table_records('promo_code_list','*',array('module' => 'activities', 'status' => 1),'','10',array('origin' => 'ASC'));  
          $page_data['car_promo'] = $this->custom_db->single_table_records('promo_code_list','*',array('module' => 'car', 'status' => 1),'','10',array('origin' => 'ASC'));  
         
    //for hotel promo
    $page_data['hotel_promo'] = $this->custom_db->single_table_records('promo_code_list','*',array('module' => 'hotel', 'status' => 1),'','10',array('origin' => 'ASC'));      
    //for bus promo
    $page_data['bus_promo'] = $this->custom_db->single_table_records('promo_code_list','*',array('module' => 'bus', 'status' => 1),'','10',array('origin' => 'ASC'));     

        $page_data['promo_code_list'] = $get_promocode_list;

        //for getting the headings
        $headings = $this->custom_db->single_table_records('home_page_headings', '*', array('status' => '1'));
        //top airlines
        $top_airlines = $this->custom_db->single_table_records('top_airlines', '*', array('status' => '1'));
        //tour styles
        $tour_styles = $this->custom_db->single_table_records('tour_styles', '*', array('status' => '1'));
        //domain data
        $domain_data = $this->custom_db->single_table_records('domain_list', '*', array('status' => '1'));
     
        $headings_array = array();
        if($headings['status'] == true){
           foreach($headings['data'] as $heading){
            $headings_array[] = $heading['title'];
          }
        }
        $features = $this->custom_db->single_table_records('why_choose_us', '*', array('status' => '1'));
        $page_data['headings'] = $headings_array;
        $page_data['top_airlines'] = $top_airlines;
        $page_data['features'] = $features;
      
        $page_data['tour_styles'] = $tour_styles;
        $page_data['domain_data'] = $domain_data;
        $sparam = $this->input->cookie('recentparam', TRUE);
        $flight_history=unserialize($sparam);
        $return_list=array();
        if(empty($flight_history) ==false)
        {
          $ser_id_list1=implode(',',$flight_history);
          $this->load->model('flight_model');
       
          $return_list= $this->flight_model->select_recent_search_history($ser_id_list1);
          
        }
        $page_data['search_history_data'] = $return_list;
        
          $data_list = $this->custom_db->single_table_records ( 'adv_banner', '*', array('module' => $de_view), 0, 100000, array (
        'id' => 'DESC'
         
    ) );

          $page_data['adv_banner']=$data_list['data'];
           
        $this->template->view('general/index', $page_data);
    }
	function pre_activity_search($search_id=''){
  //echo "asd";die;
	    $search_id = $this->save_pre_search(META_SIGHTSEEING_COURSE);
	    // echo $search_id; die;
	 
	    $this->save_search_cookie(META_SIGHTSEEING_COURSE, $search_id);
	    //Analytics
	    $this->load->model('activity_model');
	    $search_params = $this->input->get();
	        // debug($search_params);
	        // exit;
	
	    $this->activity_model->save_search_data($search_params, META_SIGHTSEEING_COURSE);
	   
	    redirect('activity/search/'.$search_id.'?'.$_SERVER['QUERY_STRING']);
	  }
        function index($default_view = 'home')
    {
    
    
   // $this->session->set_flashdata(array('message' => 'AL0040', 'type' => SUCCESS_MESSAGE));
        $geturl=$this->uri->segment(1);
     
    
        if($geturl == ''){
            $geturl='home';
        }
       
    
            if($default_vie=="")
            {
                $default_vie="flights";
            }
            foreach ($master_module_list as $k => $v)
            {
                
                if($default_vie==$v)
                {
                    $dft_v=$k;
                }
                
            }
            $data['caption'] = $this->Package_Model->getPageCaption('tours_packages')->row();
            $data['packages'] = $this->Package_Model->getAllPackages();
            $data['countries'] = $this->Package_Model->getPackageCountries_new();
            $data['package_types'] = $this->Package_Model->getPackageTypes();
            $data['package_typestour'] = $this->Package_Model->getPackageTypes2();
           //   debug( $data['package_typestour']);die;
            /* Banner_Images */
    $data["country"] = $this->custom_db->single_table_records('api_country_list', '*')[data];
            $de_view = ($GLOBALS ['CI']->uri->segment(1))?$GLOBALS ['CI']->uri->segment(1) :'flights';
            $domain_origin = get_domain_auth_id();
            $page_data['banner_images'] = $this->custom_db->single_table_records('banner_images', '*', array('added_by' => $domain_origin, 'status' => '1','module' => 'home_page'), '', '100000000', array('banner_order' => 'ASC'));
    
    
            $page_data['countrylist'] = $this->custom_db->single_table_records('api_country_list', '*');
            /* Package Data */
    
            $page_data['default_view'] = @$dft_v;
            $page_data['holiday_data'] = $data; //Package Data
        
            if (is_active_airline_module()) {
                $this->load->model('flight_model');
                $page_data['top_destination_flight'] = $this->flight_model->flight_top_destinations_home();
     
    
     
                $page_data['flight_perfect_packages'] = $this->flight_model->flight_perfect_packages_home();
                $page_data['second_flight_destination_package'] = $this->Package_Model->flight_get_package_top_destination();
                
            }
        if($_SERVER['REMOTE_ADDR']=="106.211.247.207")
            {
              
              
              
              
            }
            $page_data['top_destination_transfercms'] = $this->flight_model->transfer_top_destinations_home();
            if (is_active_bus_module()) {
                $this->load->model('bus_model');
            }
            if (true) {
                $this->load->model('hotel_model');
                $page_data['top_destination_hotel'] = $this->hotel_model->hotel_top_destinations_home();
                $page_data['second_hotel_destination_package'] = $this->Package_Model->hotel_get_package_top_destination();
            
            }
            if (true) {
                $this->load->model('hotel_model');
                $page_data['top_destination_hotel'] = $this->hotel_model->hotel_top_destinations_home();
                $page_data['second_hotel_destination_package'] = $this->Package_Model->hotel_get_package_top_destination();
            
            }
            if (is_active_sightseeing_module()) {
                $filter = array ('home_status' => ACTIVE);
            
                $data_list = $this->custom_db->single_table_records ( 'api_sightseeing_destination_list', '*', $filter, 0, 100000, array ('top_destination_activity' => 'DESC','destination_name' => 'ASC'));
                $page_data['top_destination_activity'] =  @$data_list ['data'];
                $page_data['second_activity_destination_package'] = $this->Package_Model->activity_get_package_top_destination();
            
            }
            if (is_active_sightseeing_module()) {
                $filter = array (
                    'home_status_transfer' => ACTIVE 
                );
            
                $data_list = $this->custom_db->single_table_records ( 'api_sightseeing_destination_list', '*', $filter, 0, 100000, array ('top_destination_transfers' => 'DESC','destination_name' => 'ASC'));
                $page_data['top_destination_tranfers'] =  @$data_list ['data'];
                $page_data['second_transfer_destination_package'] = $this->Package_Model->transfer_get_package_top_destination();
                
            }
    
            if (is_active_car_module()) {
                $this->load->model('car_model');
    
                $filter = array ('top_destination' => ACTIVE);
                $data_list = $this->custom_db->single_table_records ( 'Car_Airport', '*', $filter, 0, 100000, array (
                'top_destination' => 'DESC') );
                $page_data['second_car_destination_package'] = $this->Package_Model->car_get_package_top_destination_home();
            }
    
            $page_data['top_destination_car']=$data_list['data'];
    
            if (is_active_package_module()) {
                $this->load->model('package_model');
                $top_package = $this->package_model->get_package_top_destination_home();
                $page_data['top_destination_package'] = $top_package['data'];
            
                $page_data['total'] = $top_package['total'];
            }
        
            $currency_obj = new Currency(array('module_type' => 'hotel', 'from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
            $page_data['currency_obj'] = $currency_obj;
            $getSlideImages = $page_data['banner_images']['data'];
            
            $slideImageArray = array();
    
            foreach ($getSlideImages as $k) {
            
                $slideImageArray[] = array('image' => base_url(). 'extras/system/template_list/template_v1/images/' . $k['image'], 'title' => $k['title'], 'description' => $k['subtitle']);
            }
            $page_data['slideImageJson'] = $slideImageArray;
    
            $page_data['flight_promo'] = $this->custom_db->single_table_records('promo_code_list','*',array('module' => 'flight', 'status' => 1),'','10',array('origin' => 'ASC'));
            $page_data['transfers_promo'] = $this->custom_db->single_table_records('promo_code_list','*',array('module' => 'transfers', 'status' => 1),'','10',array('origin' => 'ASC'));
            $page_data['activity_promo'] = $this->custom_db->single_table_records('promo_code_list','*',array('module' => 'activities', 'status' => 1),'','10',array('origin' => 'ASC'));  
            $page_data['car_promo'] = $this->custom_db->single_table_records('promo_code_list','*',array('module' => 'car', 'status' => 1),'','10',array('origin' => 'ASC'));  
    
        
            //for hotel promo
            $page_data['hotel_promo'] = $this->custom_db->single_table_records('promo_code_list','*',array('module' => 'hotel', 'status' => 1),'','10',array('origin' => 'ASC'));      
            //for bus promo
            $page_data['bus_promo'] = $this->custom_db->single_table_records('promo_code_list','*',array('module' => 'bus', 'status' => 1),'','10',array('origin' => 'ASC'));  
            $page_data['promocode_all'] = $this->custom_db->single_table_records('promo_code_list','*',array('display_home_page' =>'Yes','status' => 1));   
             //debug( $page_data['promocode_all']);die;
            $page_data['promo_code_list'] = $get_promocode_list;
    
            //for getting the headings
            $headings = $this->custom_db->single_table_records('home_page_headings', '*', array('status' => '1'));
            //top airlines
            $top_airlines = $this->custom_db->single_table_records('top_airlines', '*', array('status' => '1'));
            //tour styles
            $tour_styles = $this->custom_db->single_table_records('tour_styles', '*', array('status' => '1'));
            //domain data
            $domain_data = $this->custom_db->single_table_records('domain_list', '*', array('status' => '1'));
    
            $headings_array = array();
            if($headings['status'] == true){
                foreach($headings['data'] as $heading){
                    $headings_array[] = $heading['title'];
                }
            }
    
            $features = $this->custom_db->single_table_records('why_choose_us', '*', array('status' => '1'));
            $page_data['headings'] = $headings_array;
            $page_data['top_airlines'] = $top_airlines;
            $page_data['features'] = $features;
        
            $page_data['tour_styles'] = $tour_styles;
            $page_data['domain_data'] = $domain_data;
            $sparam = $this->input->cookie('recentparam', TRUE);
            $flight_history=unserialize($sparam);
            $return_list=array();
            if(empty($flight_history) ==false)
            {
                $ser_id_list1=implode(',',$flight_history);
                $this->load->model('flight_model');
                $return_list= $this->flight_model->select_recent_search_history($ser_id_list1);
            }
            $page_data['search_history_data'] = $return_list;
    
            $data_list = $this->custom_db->single_table_records ('adv_banner','*', array('home_status' => '1'));
            $page_data['adv_banner']=$data_list['data'];
            $page_data['homepage_hotel_crs_display'] = $this->hotel_model->homepage_hotel_crs_display();
            $page_data['homepage_hotelApts_crs_display'] = $this->hotel_model->homepage_hotel_crs_display2();
            $geturl=$GLOBALS ['CI']->uri->segment(1);
            $dummy=array();
			$dummy["country"] = $this->visa_model->get_countries();
          /*  for($i=0;$i<count($page_data['countrylist']['data']);$i++)
            {
                   $cond = array('country_code' => $page_data['countrylist']['data'][$i]['iso_country_code']);
                   $updet=array(
                       'nationality'=>$page_data['countrylist']['data'][$i]['nationality'],
                   );
                   $this->custom_db->update_record('country_list', $updet, $cond);
            }*/
			$page_data['visa_country_data'] = $dummy;
			
          
            $page_data['refercode']=$_GET['refercode'];
           
            if($geturl == "flight_crs"){
    
                $this->template->view('flight_crs/index', $page_data);
            }else{
                $this->template->view('general/home', $page_data);
            }
    //      debug($page_data['homepage_hotel_crs_display']);exit;
       
    
    }
    function indexoldestapril($default_view = 'home')
    {



      $geturl=$this->uri->segment(1);
      // $view=$this->uri->segment(3);
     // debug($geturl);

      if($geturl == ''){
        $geturl='home';
      }
      if($geturl!="home")
      {

      $master_module_list = $GLOBALS ['CI']->config->item('master_module_list');
      $default_vie=$GLOBALS ['CI']->uri->segment(1);
      // debug($default_vie);exit();
      
      if($default_vie=="")
      {
        $default_vie="flights";
      }
      foreach ($master_module_list as $k => $v)
      {
        
          if($default_vie==$v)
          {
            $dft_v=$k;
          }
        
      }
        /* Package Data */
        $data['caption'] = $this->Package_Model->getPageCaption('tours_packages')->row();
        $data['packages'] = $this->Package_Model->getAllPackages();
        $data['countries'] = $this->Package_Model->getPackageCountries_new();
        $data['package_types'] = $this->Package_Model->getPackageTypes();

        /* Banner_Images */
        $de_view = ($GLOBALS ['CI']->uri->segment(1))?$GLOBALS ['CI']->uri->segment(1) :'flights';
        if($de_view=="villasapartment")
        {
            $de_view="VillasApts";
        }
        $domain_origin = get_domain_auth_id();
        $page_data['banner_images'] = $this->custom_db->single_table_records('banner_images', '*', array('added_by' => $domain_origin, 'status' => '1','module' => $de_view), '', '100000000', array('banner_order' => 'ASC'));
      //  debug($page_data);die;
        
        $page_data['countrylist'] = $this->custom_db->single_table_records('api_country_list', '*');
        /* Package Data */
        $page_data['default_view'] = @$dft_v;
        $page_data['holiday_data'] = $data; //Package Data

        if (is_active_airline_module()) {
            $this->load->model('flight_model');
             $page_data['top_destination_flight'] = $this->flight_model->flight_top_destinations();
             $page_data['flight_perfect_packages'] = $this->flight_model->flight_perfect_packages();
             $page_data['second_flight_destination_package'] = $this->Package_Model->flight_get_package_top_destination();
        }
        if (is_active_bus_module()) {
            $this->load->model('bus_model');
        }
        /*if (true) {
            $this->load->model('hotel_model');
            $page_data['top_destination_hotel'] = $this->hotel_model->hotel_top_destinations();
            $page_data['second_hotel_destination_package'] = $this->Package_Model->hotel_get_package_top_destination();
       
        }*/
         if (true) {
            $this->load->model('hotel_model');
            $page_data['top_destination_hotel'] = $this->hotel_model->hotel_top_destinations();
            $page_data['hotel_perfect_packages'] = $this->hotel_model->hotel_perfect_packages();
            $page_data['second_hotel_destination_package'] = $this->Package_Model->hotel_get_package_top_destination();
       
        }
        if (is_active_sightseeing_module()) {
            $filter = array (
                'top_destination_activity' => ACTIVE 
            );
           
            $data_list = $this->custom_db->single_table_records ( 'api_sightseeing_destination_list', '*', $filter, 0, 100000, array (
                'top_destination_activity' => 'DESC',
                'destination_name' => 'ASC' 
            ) );
            $page_data['top_destination_activity'] =  @$data_list ['data'];
             $page_data['second_activity_destination_package'] = $this->Package_Model->activity_get_package_top_destination();
             
            $filter3 = array (
                'activity_perfect_package' => ACTIVE,
                'activity_perfect_packages_status' => ACTIVE
            );
            $data_list_activity_packages = $this->custom_db->single_table_records ( 'api_sightseeing_destination_list', '*', $filter3, 0, 100000, array (
                'activity_perfect_package' => 'DESC',
                'destination_name' => 'ASC' 
            ));
            $page_data['perfect_activity_packages'] = @$data_list_activity_packages['data'];
            
       
        }
        if (is_active_sightseeing_module()) {
            $filter = array (
                'top_destination_transfers' => ACTIVE 
            );
           
            $data_list = $this->custom_db->single_table_records ( 'api_sightseeing_destination_list', '*', $filter, 0, 100000, array (
                'top_destination_transfers' => 'DESC',
                'destination_name' => 'ASC' 
            ) );
            $page_data['top_destination_tranfers'] =  @$data_list ['data'];
            
            $filter2 = array (
                    'transfer_perfect_package' => ACTIVE,
                    'transfer_perfect_packages_status' => ACTIVE
                );
            $data_list_transfers_packages = $this->custom_db->single_table_records ( 'api_sightseeing_destination_list', '*', $filter2, 0, 100000, array (
                    'transfer_perfect_package' => 'DESC',
                    'destination_name' => 'ASC' 
            ));
                
            $page_data['perfect_transfers_packages'] = @$data_list_transfers_packages['data'];
            
            $page_data['second_transfer_destination_package'] = $this->Package_Model->transfer_get_package_top_destination();
           
        }

         if (is_active_car_module()) {
            $this->load->model('car_model');
            
            $filter = array ('car_inner_top_destination' => ACTIVE );
            $data_list = $this->custom_db->single_table_records ( 'Car_Airport', '*', $filter, 0, 100000, array ('car_inner_top_destination' => 'DESC'));
            
            //This code added to bring view images in same format @start
            foreach ($data_list['data'] as $CarTopKey => $CarTopValue) {
                $data_list['data'][$CarTopKey]['image']=$CarTopValue['image2'];
            }
            //@end
            $page_data['second_car_destination_package'] = $this->Package_Model->car_get_package_top_destination();
            
            $perfect_car_filter = array ('car_perfect_package' => ACTIVE,'car_perfect_packages_status' => ACTIVE );
            
            $perfect_car_data_list = $this->custom_db->single_table_records ( 'Car_Airport', '*', $perfect_car_filter, 0, 100000, array ('car_perfect_package' => 'DESC') );
            
            foreach ($perfect_car_data_list['data'] as $perfect_CarTopKey => $perfect_CarTopValue) {
                $perfect_car_data_list['data'][$perfect_CarTopKey]['image']=$perfect_CarTopValue['image2'];
            }
            $page_data['perfect_car_packages']=$perfect_car_data_list['data'];
         }

         $page_data['top_destination_car']=$data_list['data'];
    
        if (is_active_package_module()) {
            $this->load->model('package_model');
            $top_package = $this->package_model->get_package_top_destination();
            $page_data['top_destination_package'] = $top_package['data'];
            
            $page_data['total'] = $top_package['total'];
        }
        
        $currency_obj = new Currency(array('module_type' => 'hotel', 'from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
        $page_data['currency_obj'] = $currency_obj;
        $getSlideImages = $page_data['banner_images']['data'];
       
        $slideImageArray = array();

        foreach ($getSlideImages as $k) {
            
          $slideImageArray[] = array('image' => base_url(). 'extras/system/template_list/template_v1/images/' . $k['image'], 'title' => $k['title'], 'description' => $k['subtitle']);
        }
        $page_data['slideImageJson'] = $slideImageArray;
      
        $page_data['flight_promo'] = $this->custom_db->single_table_records('promo_code_list','*',array('module' => 'flight', 'status' => 1,'module_status' => 1),'','10',array('origin' => 'ASC'));
         $page_data['transfers_promo'] = $this->custom_db->single_table_records('promo_code_list','*',array('module' => 'transfers', 'status' => 1,'module_status' => 1),'','10',array('origin' => 'ASC'));
          $page_data['activity_promo'] = $this->custom_db->single_table_records('promo_code_list','*',array('module' => 'activities', 'status' => 1,'module_status' => 1),'','10',array('origin' => 'ASC'));  
          $page_data['car_promo'] = $this->custom_db->single_table_records('promo_code_list','*',array('module' => 'car', 'status' => 1,'module_status' => 1),'','10',array('origin' => 'ASC'));  
          //Added By Karthick 10-06-2021 @Start
          $page_data['holiday_promo'] = $this->custom_db->single_table_records('promo_code_list','*',array('module' => 'holiday', 'status' => 1,'module_status' => 1),'','10',array('origin' => 'ASC'));
          //@end  
          
    //for hotel promo
    $page_data['hotel_promo'] = $this->custom_db->single_table_records('promo_code_list','*',array('module' => 'hotel', 'status' => 1,'module_status' => 1),'','10',array('origin' => 'ASC'));      
    //for bus promo
    $page_data['bus_promo'] = $this->custom_db->single_table_records('promo_code_list','*',array('module' => 'bus', 'status' => 1),'','10',array('origin' => 'ASC'));     
 $page_data['promocode_all'] = $this->custom_db->single_table_records('promo_code_list','*',array('home_status' => 1));  
        $page_data['promo_code_list'] = $get_promocode_list;

        //for getting the headings
        $headings = $this->custom_db->single_table_records('home_page_headings', '*', array('status' => '1'));
        //top airlines
        $top_airlines = $this->custom_db->single_table_records('top_airlines', '*', array('status' => '1'));
        //tour styles
        $tour_styles = $this->custom_db->single_table_records('tour_styles', '*', array('status' => '1'));
        //domain data
        $domain_data = $this->custom_db->single_table_records('domain_list', '*', array('status' => '1'));
     
        $headings_array = array();
        if($headings['status'] == true){
           foreach($headings['data'] as $heading){
            $headings_array[] = $heading['title'];
          }
        }
        $features = $this->custom_db->single_table_records('why_choose_us', '*', array('status' => '1'));
        $page_data['headings'] = $headings_array;
        $page_data['top_airlines'] = $top_airlines;
        $page_data['features'] = $features;
        
        $page_data['tour_styles'] = $tour_styles;
        $page_data['domain_data'] = $domain_data;
        $sparam = $this->input->cookie('recentparam', TRUE);
        $flight_history=unserialize($sparam);
        $return_list=array();
        if(empty($flight_history) ==false)
        {
          $ser_id_list1=implode(',',$flight_history);
          $this->load->model('flight_model');
          
          $return_list= $this->flight_model->select_recent_search_history($ser_id_list1);
          
        }
        $page_data['search_history_data'] = $return_list;
        
          $data_list = $this->custom_db->single_table_records ( 'adv_banner', '*', array('module' => $de_view), 0, 100000, array (
        'id' => 'DESC'
         
    ) );
//debug($data_list);die;
          $page_data['adv_banner']=$data_list['data'];
          //  debug($page_data['banner_images'] );die;
          $page_data['top_villa_display'] = $this->hotel_model->villa_display();
          $page_data['top_Apts_display'] = $this->hotel_model->villa_display2();
         $page_data['top_resort_display'] = $this->hotel_model->resort_display();
          $page_data['top_cabin_display'] = $this->hotel_model->cabin_display();
          $page_data['acco_hotel_crs_display'] = $this->hotel_model->acco_hotel_crs_display();
            $page_data['second_holiday_destination_package'] = $this->Package_Model->flight_get_package_top_destination();
          //debug($page_data['top_villa_display'] );die;
      if($default_vie=="privatejet")
      {
        // debug($page_data);die;
        $this->template->view('flight_crs/index', $page_data);
      }else{
        $this->template->view('general/index', $page_data);
       }
        
      } else {
   
      if($default_vie=="")
      {
        $default_vie="flights";
      }
      foreach ($master_module_list as $k => $v)
      {
        
          if($default_vie==$v)
          {
            $dft_v=$k;
          }

        
      }
    $data['caption'] = $this->Package_Model->getPageCaption('tours_packages')->row();
        $data['packages'] = $this->Package_Model->getAllPackages();
        $data['countries'] = $this->Package_Model->getPackageCountries_new();
        $data['package_types'] = $this->Package_Model->getPackageTypes();
        /* Banner_Images */

        $de_view = ($GLOBALS ['CI']->uri->segment(1))?$GLOBALS ['CI']->uri->segment(1) :'flights';
        $domain_origin = get_domain_auth_id();
        $page_data['banner_images'] = $this->custom_db->single_table_records('banner_images', '*', array('added_by' => $domain_origin, 'status' => '1','module' => 'home_page'), '', '100000000', array('banner_order' => 'ASC'));
       
     
        $page_data['countrylist'] = $this->custom_db->single_table_records('api_country_list', '*');
        /* Package Data */
      
        $page_data['default_view'] = @$dft_v;
        $page_data['holiday_data'] = $data; //Package Data
        
        if (is_active_airline_module()) {
            $this->load->model('flight_model');
             $page_data['top_destination_flight'] = $this->flight_model->flight_top_destinations_home();
             $page_data['second_flight_destination_package'] = $this->Package_Model->flight_get_package_top_destination();
            
        }
        if (is_active_bus_module()) {
            $this->load->model('bus_model');
        }
        if (true) {
            $this->load->model('hotel_model');
            $page_data['top_destination_hotel'] = $this->hotel_model->hotel_top_destinations_home();
            $page_data['flight_perfect_packages'] = $this->flight_model->flight_perfect_packages_home();
            $page_data['second_hotel_destination_package'] = $this->Package_Model->hotel_get_package_top_destination();
           
        }
        if (true) {
            $this->load->model('hotel_model');
            $page_data['top_destination_hotel'] = $this->hotel_model->hotel_top_destinations_home();
            $page_data['second_hotel_destination_package'] = $this->Package_Model->hotel_get_package_top_destination();
           
        }
        if (is_active_sightseeing_module()) {
            $filter = array (
                'home_status' => ACTIVE 
            );
           
            $data_list = $this->custom_db->single_table_records ( 'api_sightseeing_destination_list', '*', $filter, 0, 100000, array (
                'top_destination_activity' => 'DESC',
                'destination_name' => 'ASC' 
            ) );
            $page_data['top_destination_activity'] =  @$data_list ['data'];
             $page_data['second_activity_destination_package'] = $this->Package_Model->activity_get_package_top_destination();
        
        }
        if (is_active_sightseeing_module()) {
            $filter = array (
                'home_status_transfer' => ACTIVE 
            );
           
            $data_list = $this->custom_db->single_table_records ( 'api_sightseeing_destination_list', '*', $filter, 0, 100000, array (
                'top_destination_transfers' => 'DESC',
                'destination_name' => 'ASC' 
            ) );
            $page_data['top_destination_tranfers'] =  @$data_list ['data'];
            $page_data['second_transfer_destination_package'] = $this->Package_Model->transfer_get_package_top_destination();
             
        }

         if (is_active_car_module()) {
          $this->load->model('car_model');

          $filter = array (
        'top_destination' => ACTIVE 
    );
          $data_list = $this->custom_db->single_table_records ( 'Car_Airport', '*', $filter, 0, 100000, array (
        'top_destination' => 'DESC'
         
    ) );
           $page_data['second_car_destination_package'] = $this->Package_Model->car_get_package_top_destination_home();
         }

         $page_data['top_destination_car']=$data_list['data'];
   
        if (is_active_package_module()) {
            $this->load->model('package_model');
            $top_package = $this->package_model->get_package_top_destination_home();
            $page_data['top_destination_package'] = $top_package['data'];
           
            $page_data['total'] = $top_package['total'];
        }
        
        $currency_obj = new Currency(array('module_type' => 'hotel', 'from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
        $page_data['currency_obj'] = $currency_obj;
        $getSlideImages = $page_data['banner_images']['data'];
        
        $slideImageArray = array();

        foreach ($getSlideImages as $k) {
          
          $slideImageArray[] = array('image' => base_url(). 'extras/system/template_list/template_v1/images/' . $k['image'], 'title' => $k['title'], 'description' => $k['subtitle']);
        }
        $page_data['slideImageJson'] = $slideImageArray;

        $page_data['flight_promo'] = $this->custom_db->single_table_records('promo_code_list','*',array('module' => 'flight', 'status' => 1),'','10',array('origin' => 'ASC'));
         $page_data['transfers_promo'] = $this->custom_db->single_table_records('promo_code_list','*',array('module' => 'transfers', 'status' => 1),'','10',array('origin' => 'ASC'));
          $page_data['activity_promo'] = $this->custom_db->single_table_records('promo_code_list','*',array('module' => 'activities', 'status' => 1),'','10',array('origin' => 'ASC'));  
          $page_data['car_promo'] = $this->custom_db->single_table_records('promo_code_list','*',array('module' => 'car', 'status' => 1),'','10',array('origin' => 'ASC'));  

         
    //for hotel promo
    $page_data['hotel_promo'] = $this->custom_db->single_table_records('promo_code_list','*',array('module' => 'hotel', 'status' => 1),'','10',array('origin' => 'ASC'));      
    //for bus promo
    $page_data['bus_promo'] = $this->custom_db->single_table_records('promo_code_list','*',array('module' => 'bus', 'status' => 1),'','10',array('origin' => 'ASC'));  
    $page_data['promocode_all'] = $this->custom_db->single_table_records('promo_code_list','*',array('home_status' => 1));   
 //  debug( $page_data['promocode_all']);die;
        $page_data['promo_code_list'] = $get_promocode_list;

        //for getting the headings
        $headings = $this->custom_db->single_table_records('home_page_headings', '*', array('status' => '1'));
        //top airlines
        $top_airlines = $this->custom_db->single_table_records('top_airlines', '*', array('status' => '1'));
        //tour styles
        $tour_styles = $this->custom_db->single_table_records('tour_styles', '*', array('status' => '1'));
        //domain data
        $domain_data = $this->custom_db->single_table_records('domain_list', '*', array('status' => '1'));
     
        $headings_array = array();
        if($headings['status'] == true){
           foreach($headings['data'] as $heading){
            $headings_array[] = $heading['title'];
          }
        }

        $features = $this->custom_db->single_table_records('why_choose_us', '*', array('status' => '1'));
        $page_data['headings'] = $headings_array;
        $page_data['top_airlines'] = $top_airlines;
        $page_data['features'] = $features;
       
        $page_data['tour_styles'] = $tour_styles;
        $page_data['domain_data'] = $domain_data;
        $sparam = $this->input->cookie('recentparam', TRUE);
        $flight_history=unserialize($sparam);
        $return_list=array();
        if(empty($flight_history) ==false)
        {
          $ser_id_list1=implode(',',$flight_history);
          $this->load->model('flight_model');
          $return_list= $this->flight_model->select_recent_search_history($ser_id_list1);
        }
        $page_data['search_history_data'] = $return_list;
        
          $data_list = $this->custom_db->single_table_records ('adv_banner','*', array('home_status' => '1'));
          $page_data['adv_banner']=$data_list['data'];
          $page_data['homepage_hotel_crs_display'] = $this->hotel_model->homepage_hotel_crs_display();
           $page_data['homepage_hotelApts_crs_display'] = $this->hotel_model->homepage_hotel_crs_display2();
           $geturl=$GLOBALS ['CI']->uri->segment(1);
            // debug($geturl);exit();
        if($geturl == "flight_crs"){

            $this->template->view('flight_crs/index', $page_data);
        }else{
            $this->template->view('general/home', $page_data);
        }
   //      debug($page_data['homepage_hotel_crs_display']);exit;
        
      } 
    }

    function investors(){
     
      $temp_record = $this->custom_db->single_table_records('domain_list', '*');
      $data['active_data'] =$temp_record['data'][0];

      $temp_record = $this->custom_db->single_table_records('api_country_list', '*');
      $data['phone_code'] =$temp_record['data'];
      $city_record = $this->custom_db->single_table_records('api_city_list', 'destination',array('country'=>$data['active_data']['api_country_list_fk']));
      $data['city_list'] =$city_record['data'][0];
      $data['country_code_list'] = $this->db_cache_api->get_country_code_list();
      $country_code = $this->db_cache_api->get_country_code_list_profile();
      
      $phone_code_array = array();
      foreach($country_code['data'] as $c_key => $c_value){
        $phone_code_array[$c_value['origin']] = $c_value['name'].' '.$c_value['country_code'];
        
      }
      
      $domain_origin = get_domain_auth_id();
      $data['banner_images'] = $this->custom_db->single_table_records('banner_images', '*', array('added_by' => $domain_origin, 'status' => '1','module' => 'investor'), '', '100000000', array('banner_order' => 'ASC'));
      $getSlideImages = $data['banner_images']['data'];
        
        $slideImageArray = array();

        foreach ($getSlideImages as $k) {
          
          $slideImageArray[] = array('image' => base_url(). 'extras/system/template_list/template_v1/images/' . $k['image'], 'title' => $k['title'], 'description' => $k['subtitle']);
        }
      
      $data['slideImageJson'] = $slideImageArray;
      $data['phone_code_array'] = $phone_code_array;
      $data['country_list'] = $this->db_cache_api->get_country_list();
     
      $this->template->view('general/investors', $data); 
    }


    function home(){
      redirect(base_url().'index.php'); 
    }
    function blog(){
      $blog = array();
      $this->template->view('general/blog', $blog); 
    }
    function blog_inner(){
      $blog_inner = array();
      $this->template->view('general/blog_inner', $blog_inner); 
    }

    function gallery(){
      $temp_record = $this->custom_db->single_table_records('domain_list', '*');
      $data['active_data'] =$temp_record['data'][0];

      $temp_record = $this->custom_db->single_table_records('api_country_list', '*');
      $data['phone_code'] =$temp_record['data'];
      $city_record = $this->custom_db->single_table_records('api_city_list', 'destination',array('country'=>$data['active_data']['api_country_list_fk']));
      $data['city_list'] =$city_record['data'][0];
      $data['country_code_list'] = $this->db_cache_api->get_country_code_list();
      $country_code = $this->db_cache_api->get_country_code_list_profile();
     
      $phone_code_array = array();
      foreach($country_code['data'] as $c_key => $c_value){
        $phone_code_array[$c_value['origin']] = $c_value['name'].' '.$c_value['country_code'];
        
      }
      
      $domain_origin = get_domain_auth_id();
      $data['banner_images'] = $this->custom_db->single_table_records('banner_images', '*', array('added_by' => $domain_origin, 'status' => '1','module' => 'gallery_image'), '', '100000000', array('banner_order' => 'ASC'));
      $getSlideImages = $data['banner_images']['data'];
        
        $slideImageArray = array();

        foreach ($getSlideImages as $k) {
          
          $slideImageArray[] = array('image' => base_url(). 'extras/system/template_list/template_v1/images/' . $k['image'], 'title' => $k['title'], 'description' => $k['subtitle']);
        }
   
      $data['slideImageJson'] = $slideImageArray;
      $data['phone_code_array'] = $phone_code_array;
      $data['country_list'] = $this->db_cache_api->get_country_list();
      $this->template->view('general/gallery', $data); 
    }
    function gallery_video(){
      $temp_record = $this->custom_db->single_table_records('domain_list', '*');
      $data['active_data'] =$temp_record['data'][0];

      $temp_record = $this->custom_db->single_table_records('api_country_list', '*');
      $data['phone_code'] =$temp_record['data'];
      $city_record = $this->custom_db->single_table_records('api_city_list', 'destination',array('country'=>$data['active_data']['api_country_list_fk']));
      $data['city_list'] =$city_record['data'][0];
      $data['country_code_list'] = $this->db_cache_api->get_country_code_list();
      $country_code = $this->db_cache_api->get_country_code_list_profile();
     
      $phone_code_array = array();
      foreach($country_code['data'] as $c_key => $c_value){
        $phone_code_array[$c_value['origin']] = $c_value['name'].' '.$c_value['country_code'];
        
      }
      
      $domain_origin = get_domain_auth_id();
      $data['banner_images'] = $this->custom_db->single_table_records('banner_images', '*', array('added_by' => $domain_origin, 'status' => '1','module' => 'gallery_video'), '', '100000000', array('banner_order' => 'ASC'));
      $getSlideImages = $data['banner_images']['data'];
        
        $slideImageArray = array();

        foreach ($getSlideImages as $k) {
          
          $slideImageArray[] = array('image' => base_url(). 'extras/system/template_list/template_v1/images/' . $k['image'], 'title' => $k['title'], 'description' => $k['subtitle']);
        }
     
      $data['slideImageJson'] = $slideImageArray;
      $data['phone_code_array'] = $phone_code_array;
      $data['country_list'] = $this->db_cache_api->get_country_list();
      $this->template->view('general/gallery_video', $data); 
    }
    function home_page(){
        /* Package Data */
        $data['caption'] = $this->Package_Model->getPageCaption('tours_packages')->row();
        $data['packages'] = $this->Package_Model->getAllPackages();
        $data['countries'] = $this->Package_Model->getPackageCountries_new();
        $data['package_types'] = $this->Package_Model->getPackageTypes();
        /* Banner_Images */
        $de_view = ($GLOBALS ['CI']->uri->segment(1))?$GLOBALS ['CI']->uri->segment(1) :'flights';
        $domain_origin = get_domain_auth_id();
        $page_data['banner_images'] = $this->custom_db->single_table_records('banner_images', '*', array('added_by' => $domain_origin, 'status' => '1','module' => 'home_page'), '', '100000000', array('banner_order' => 'ASC'));
        $page_data['countrylist'] = $this->custom_db->single_table_records('api_country_list', '*');
        /* Package Data */
       
        $page_data['default_view'] = @$dft_v;
        $page_data['holiday_data'] = $data; //Package Data

        if (is_active_airline_module()) {
            $this->load->model('flight_model');
             $page_data['top_destination_flight'] = $this->flight_model->flight_top_destinations();
             $page_data['second_flight_destination_package'] = $this->Package_Model->flight_get_package_top_destination();
        }
        if (is_active_bus_module()) {
            $this->load->model('bus_model');
        }
        if (is_active_hotel_module()) {
            $this->load->model('hotel_model');
            $page_data['top_destination_hotel'] = $this->hotel_model->hotel_top_destinations();
            $page_data['second_hotel_destination_package'] = $this->Package_Model->hotel_get_package_top_destination();      
        }
        if (is_active_sightseeing_module()) {
            $filter = array (
                'top_destination_activity' => ACTIVE 
            );
           
            $data_list = $this->custom_db->single_table_records ( 'api_sightseeing_destination_list', '*', $filter, 0, 100000, array (
                'top_destination_activity' => 'DESC',
                'destination_name' => 'ASC' 
            ) );
            $page_data['top_destination_activity'] =  @$data_list ['data'];
             $page_data['second_activity_destination_package'] = $this->Package_Model->activity_get_package_top_destination();
           
        }
        if (is_active_sightseeing_module()) {
            $filter = array (
                'top_destination_transfers' => ACTIVE 
            );
           
            $data_list = $this->custom_db->single_table_records ( 'api_sightseeing_destination_list', '*', $filter, 0, 100000, array (
                'top_destination_transfers' => 'DESC',
                'destination_name' => 'ASC' 
            ) );
            $page_data['top_destination_tranfers'] =  @$data_list ['data'];
            $page_data['second_transfer_destination_package'] = $this->Package_Model->transfer_get_package_top_destination(); 
        }
       
         if (is_active_car_module()) {
          $this->load->model('car_model');

          $filter = array (
        'top_destination' => ACTIVE 
    );
          $data_list = $this->custom_db->single_table_records ( 'Car_Airport', '*', $filter, 0, 100000, array (
        'top_destination' => 'DESC'
         
    ) );
           $page_data['second_car_destination_package'] = $this->Package_Model->car_get_package_top_destination();
         }

         $page_data['top_destination_car']=$data_list['data'];
        
        if (is_active_package_module()) {
            $this->load->model('package_model');
            $top_package = $this->package_model->get_package_top_destination();
            $page_data['top_destination_package'] = $top_package['data'];
           
            $page_data['total'] = $top_package['total'];
        }
        
        $currency_obj = new Currency(array('module_type' => 'hotel', 'from' => get_api_data_currency(), 'to' => get_application_currency_preference()));
        $page_data['currency_obj'] = $currency_obj;
        $getSlideImages = $page_data['banner_images']['data'];
        
        $slideImageArray = array();

        foreach ($getSlideImages as $k) {
          
          $slideImageArray[] = array('image' => base_url(). 'extras/system/template_list/template_v1/images/' . $k['image'], 'title' => $k['title'], 'description' => $k['subtitle']);
        }
        $page_data['slideImageJson'] = $slideImageArray;
      
        $page_data['flight_promo'] = $this->custom_db->single_table_records('promo_code_list','*',array('module' => 'flight', 'status' => 1),'','10',array('origin' => 'ASC'));
         $page_data['transfers_promo'] = $this->custom_db->single_table_records('promo_code_list','*',array('module' => 'transfers', 'status' => 1),'','10',array('origin' => 'ASC'));
          $page_data['activity_promo'] = $this->custom_db->single_table_records('promo_code_list','*',array('module' => 'activities', 'status' => 1),'','10',array('origin' => 'ASC'));  
          $page_data['car_promo'] = $this->custom_db->single_table_records('promo_code_list','*',array('module' => 'car', 'status' => 1),'','10',array('origin' => 'ASC'));  
          
    //for hotel promo
    $page_data['hotel_promo'] = $this->custom_db->single_table_records('promo_code_list','*',array('module' => 'hotel', 'status' => 1),'','10',array('origin' => 'ASC'));      
    //for bus promo
    $page_data['bus_promo'] = $this->custom_db->single_table_records('promo_code_list','*',array('module' => 'bus', 'status' => 1),'','10',array('origin' => 'ASC'));  
    $page_data['promocode_all'] = $this->custom_db->single_table_records('promo_code_list','*',array('status' => 1));   
        $page_data['promo_code_list'] = $get_promocode_list;

        //for getting the headings
        $headings = $this->custom_db->single_table_records('home_page_headings', '*', array('status' => '1'));
        //top airlines
        $top_airlines = $this->custom_db->single_table_records('top_airlines', '*', array('status' => '1'));
        //tour styles
        $tour_styles = $this->custom_db->single_table_records('tour_styles', '*', array('status' => '1'));
        //domain data
        $domain_data = $this->custom_db->single_table_records('domain_list', '*', array('status' => '1'));
     
        $headings_array = array();
        if($headings['status'] == true){
           foreach($headings['data'] as $heading){
            $headings_array[] = $heading['title'];
          }
        }
        $features = $this->custom_db->single_table_records('why_choose_us', '*', array('status' => '1'));
        $page_data['headings'] = $headings_array;
        $page_data['top_airlines'] = $top_airlines;
        $page_data['features'] = $features;
       
        $page_data['tour_styles'] = $tour_styles;
        $page_data['domain_data'] = $domain_data;
        $sparam = $this->input->cookie('recentparam', TRUE);
        $flight_history=unserialize($sparam);
        $return_list=array();
        if(empty($flight_history) ==false)
        {
          $ser_id_list1=implode(',',$flight_history);
          $this->load->model('flight_model');
          
          $return_list= $this->flight_model->select_recent_search_history($ser_id_list1);
         
        }
        $page_data['search_history_data'] = $return_list;
        
          $data_list = $this->custom_db->single_table_records ('adv_banner','*', array('status' => '1'));
  
          $page_data['adv_banner']=$data_list['data'];
          
      $this->template->view('general/home', $page_data); 
    }
    function contactus(){
      $contact = array();
      $this->template->view('general/contactus', $contact); 
    }

    /**
     * Set Search id in cookie
     */

    private function save_search_cookie($module, $search_id) {
        $sparam = array();
        $sparam = $this->input->cookie('sparam', TRUE);
        if (empty($sparam) == false) {
            $sparam = unserialize($sparam);
        }
        $sparam[$module] = $search_id;

        $cookie = array(
            'name' => 'sparam',
            'value' => serialize($sparam),
            'expire' => '86500',
            'path' => PROJECT_COOKIE_PATH
        );
        $this->input->set_cookie($cookie);
    }

    /**
     * Pre Search For Flight
     */
    function pre_flight_search($search_id = '') {
        $search_params = $this->input->get();
        //Global search Data
        $search_id = $this->save_pre_search(META_AIRLINE_COURSE);
        $this->save_search_cookie(META_AIRLINE_COURSE, $search_id);
        
        if($search_params['trip_type'] !='multicity')
        {
          $this->save_search_flight_recent_history($search_params,$search_id);
        }
        
        //Analytics
        $this->load->model('flight_model');
        $this->flight_model->save_search_data($search_params, META_AIRLINE_COURSE);
        redirect('index.php/flight/search/' . $search_id . '?' . $_SERVER['QUERY_STRING']);
    }


 function pre_flight_search_crs($search_id = '') {
        $search_params = $this->input->get();

        //Global search Data
        $search_id = $this->save_pre_search(META_AIRLINE_COURSE);
        $this->save_search_cookie(META_AIRLINE_COURSE, $search_id);
        
        if($search_params['trip_type'] !='multicity')
        {
          $this->save_search_flight_recent_history($search_params,$search_id);
        }
        
        //Analytics
        $this->load->model('flight_model');
        $this->flight_model->save_search_data($search_params, META_AIRLINE_COURSE);
        redirect('index.php/flight_crs/search/' . $search_id . '?' . $_SERVER['QUERY_STRING']);
    }


 function preflightsearchpost() {
        $search_params = $this->input->post();
        $search_params['trip_type'] = 'oneway';
        $search_params['from'] = base64_decode($from);
        $search_params['to'] = base64_decode($to);
        $search_params['from_loc_id'] = $from_loc_id;
        $search_params['to_loc_id'] = $to_loc_id;
        $search_params['depature'] = $depature;
        $search_params['adult'] = 1;
        $search_params['child'] = 0;
        $search_params['infant'] = 0;
        $search_params['v_class'] = 'Economy';
        $search_params['carrier'] = '';
        //Global search Data
        $search_id = $this->save_pre_search(META_AIRLINE_COURSE,$search_params);
        $this->save_search_cookie(META_AIRLINE_COURSE, $search_id);
        
        if($search_params['trip_type'] !='multicity')
        {
          $this->save_search_flight_recent_history($search_params,$search_id);
        }
        //Analytics
        $this->load->model('flight_model');
        $this->flight_model->save_search_data($search_params, META_AIRLINE_COURSE);
        redirect('index.php/flight/search/' . $search_id );
    }
     function preflightsearch($form,$from_loc_id,$to,$to_loc_id,$depature,$search_id = '') {
        //$search_params = $this->input->get();
        $search_params['trip_type'] = 'oneway';
        $search_params['from'] = base64_decode($form);
        $search_params['from_loc_id'] = $from_loc_id;
        $search_params['to'] = base64_decode($to);
        $search_params['to_loc_id'] = $to_loc_id;
        $search_params['depature'] = $depature;
        $search_params['adult'] = 1;
        $search_params['child'] = 0;
        $search_params['infant'] = 0;
        $search_params['v_class'] = 'Economy';
        $search_params['carrier'] = array('0'=>'');
        $search_params['search_flight'] = 'search';

        //Global search Data
        $search_id = $this->save_pre_search_flight(META_AIRLINE_COURSE,$search_params);
        $this->save_search_cookie(META_AIRLINE_COURSE, $search_id);
        // if($search_params['trip_type'] !='multicity')
        // {
        //   $this->save_search_flight_recent_history($search_params,$search_id);
        // }
        //Analytics
        $this->load->model('flight_model');
        $this->flight_model->save_search_data($search_params, META_AIRLINE_COURSE);
        redirect('index.php/flight/search/' . $search_id );
    }

    
    
    private function save_pre_search_flight($search_type,$search_params) {
        //Save data
        //$search_params = $this->input->get();

        $search_data = json_encode($search_params);
        $insert_id = $this->custom_db->insert_record('search_history', array('search_type' => $search_type, 'search_data' => $search_data, 'created_datetime' => date('Y-m-d H:i:s')));
        return $insert_id['insert_id'];
    }
    
    function pre_flight_search_history($search_id = '') {
        $search_params = $this->input->get();
        $search_params['carrier']=urldecode($search_params['carrier']);
        $search_params['carrier']=json_decode($search_params['carrier']);
        //Global search Data
        $search_id = $this->save_pre_search(META_AIRLINE_COURSE);
        $this->save_search_cookie(META_AIRLINE_COURSE, $search_id);
        
        if($search_params['trip_type'] !='multicity')
        {
          $this->save_search_flight_recent_history($search_params,$search_id);
        }
        //Analytics
        $this->load->model('flight_model');
        $this->flight_model->save_search_data($search_params, META_AIRLINE_COURSE);
        redirect('index.php/flight/search/' . $search_id . '?' . $_SERVER['QUERY_STRING']);
    }







    public function save_search_flight_recent_history($search_params,$search_id){

        $search_ids=array();
        
        $sparam = $this->input->cookie('recentparam', TRUE);
        
        if(empty($sparam) == false)
        {
          
          $ser_id_list=unserialize($sparam);
          $ser_id_list1=implode(',',$ser_id_list);
          $this->load->model('flight_model');
         
          $return_list= $this->flight_model->select_recent_search_history($ser_id_list1);
          $status=TRUE;
          foreach ($return_list as $key => $value) {
            
            $result_data=json_decode($value['search_data'],TRUE);
            if($result_data['from_loc_id']==$search_params['from_loc_id'] && $result_data['to_loc_id']==$search_params['to_loc_id'])
            {
              $status=FALSE;
            }

          }
         
          if($status)
          {
            $search_ids=$ser_id_list;
            $search_ids[]=$search_id;
          }
          else
          {
              $search_ids=$ser_id_list;
          }          
        }
        else
        {
          $search_ids[]=$search_id;
        }
       
        if(count($search_ids) >4){
          array_shift($search_ids);
         
        }
        
        $cookie = array(
            'name' => 'recentparam',
            'value' => serialize($search_ids),
            'expire' => '86500',
           
        );
        $this->input->set_cookie($cookie);

        $sparam = $this->input->cookie('recentparam', TRUE);
        $spm=unserialize($sparam);
         
        

    }
    /**
     * Pre Search For Car
     */
    function pre_car_search($search_id = '') {
        $search_params = $this->input->get();
       // debug($search_params);exit;
        //Global search Data
        $search_id = $this->save_pre_search(META_CAR_COURSE);
        $this->save_search_cookie(META_CAR_COURSE, $search_id);

        //Analytics
        $this->load->model('car_model');
        $this->car_model->save_search_data($search_params, META_CAR_COURSE);
      //  echo 'index.php/car/search/' . $search_id . '?' . $_SERVER['QUERY_STRING'];die;
        redirect('index.php/car/search/' . $search_id . '?' . $_SERVER['QUERY_STRING']);
    }
 function precarsearch($search_id = '') {
        $search_params = $this->input->get();
       // debug($search_params);exit;
        //Global search Data
        $search_id = $this->save_pre_search(META_CAR_COURSE);
        $this->save_search_cookie(META_CAR_COURSE, $search_id);

        //Analytics
        $this->load->model('car_model');
        $this->car_model->save_search_data($search_params, META_CAR_COURSE);
      //  echo 'index.php/car/search/' . $search_id . '?' . $_SERVER['QUERY_STRING'];die;
        redirect('index.php/car/search/' . $search_id . '?' . $_SERVER['QUERY_STRING']);
    }
     function precarcrssearch($search_id = '') {
        $search_params = $this->input->get();
       // debug($search_params);exit;
        //Global search Data
        $search_id = $this->save_pre_search(META_CAR_COURSE);
        $this->save_search_cookie(META_CAR_COURSE, $search_id);

        //Analytics
        $this->load->model('car_model');
        $this->car_model->save_search_data($search_params, META_CAR_COURSE);
       // echo 'index.php/car/search/' . $search_id . '?' . $_SERVER['QUERY_STRING'];die;
        redirect('index.php/privatecar/search/' . $search_id . '?' . $_SERVER['QUERY_STRING'].'&booking_source=PTBCSID00000000017');
    }
    function precarsearchold($from,$origin,$car_from_code,$to,$car_to,$car_to_code,$ddate,$tdate,$search_id = '') {
    
        $search_params['car_from'] =base64_decode($from);
        $search_params['from_loc_id'] = $origin;
        $search_params['car_from_loc_code'] =$car_from_code;
        $search_params['car_to'] =base64_decode($to);
        $search_params['to_loc_id'] =$car_to;
        $search_params['car_to_loc_code'] =$car_to_code;
        $search_params['depature'] =$ddate;
        $search_params['depature_time'] = '09:00';
        $search_params['return'] =$tdate;
        $search_params['return_time'] = '10:30';
        $search_params['driver_age'] = '35';
        $search_params['country'] = 'IN';
        $search_params['search_flight'] = 'search';

        //debug($search_params);
        //Global search Data
        $search_id = $this->save_pre_search(META_CAR_COURSE,$search_params);
        $this->save_search_cookie(META_CAR_COURSE, $search_id);

        //Analytics
        $this->load->model('car_model');
        $this->car_model->save_search_data($search_params, META_CAR_COURSE);
     //   echo 'index.php/car/search/' . $search_id . '?' .'car_from='.$search_params['car_from'].'&from_loc_id='.$search_params['from_loc_id'].'&car_from_loc_code='.$search_params['car_from_loc_code'].'&car_to='.$search_params['car_to'].'&to_loc_id='.$search_params['car_to_loc_code'].'&car_to_loc_code='.$search_params['car_to_loc_code'].'&depature='.$search_params['depature'].'&depature_time='.$search_params['depature_time'].'&return='.$search_params['return'].'&return_time='.$search_params['return_time'].'&driver_age='.$search_params['driver_age'].'&country='.$search_params['country'].'&search_flight=search';die;
        redirect('index.php/car/search/' . $search_id . '?' .'car_from='.$search_params['car_from'].'&from_loc_id='.$search_params['from_loc_id'].'&car_from_loc_code='.$search_params['car_from_loc_code'].'&car_to='.$search_params['car_to'].'&to_loc_id='.$search_params['car_to_loc_code'].'&car_to_loc_code='.$search_params['car_to_loc_code'].'&depature='.$search_params['depature'].'&depature_time='.$search_params['depature_time'].'&return='.$search_params['return'].'&return_time='.$search_params['return_time'].'&driver_age='.$search_params['driver_age'].'&country='.$search_params['country'].'&search_flight=search');
    }
    function pre_hotel_search($search_id = '') {
        //Global search Data
        $search_id = $this->save_pre_search(META_ACCOMODATION_COURSE);
        $this->save_search_cookie(META_ACCOMODATION_COURSE, $search_id);

        //Analytics
        $this->load->model('hotel_model');
        $search_params = $this->input->get();
       debug($search_params);exit;
        $this->hotel_model->save_search_data($search_params, META_ACCOMODATION_COURSE);
//echo 'index.php/hotel/search/' . $search_id . '?' . $_SERVER['QUERY_STRING'];die;
        redirect('index.php/hotel/search/' . $search_id . '?' . $_SERVER['QUERY_STRING']);
    }
 function prehotelsearch($search_id = '') {
        //Global search Data
        $search_id = $this->save_pre_search(META_ACCOMODATION_COURSE);
        $this->save_search_cookie(META_ACCOMODATION_COURSE, $search_id);

        //Analytics
        $this->load->model('hotel_model');
        $search_params = $this->input->get();
     //  debug($search_params);exit;
  //   echo $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    // die;
        $this->hotel_model->save_search_data($search_params, META_ACCOMODATION_COURSE);
//echo 'index.php/hotel/search/' . $search_id . '?' . $_SERVER['QUERY_STRING'];die;
        redirect('index.php/hotel/search/' . $search_id . '?' . $_SERVER['QUERY_STRING']);
    }
function prehotelcrssearch($search_id = '') {
        //Global search Data
        $search_id = $this->save_pre_search(META_ACCOMODATION_COURSE);
        $this->save_search_cookie(META_ACCOMODATION_COURSE, $search_id);

        //Analytics
        $this->load->model('hotel_model');
        $search_params = $this->input->get();
       //debug($search_params);exit;
        $this->hotel_model->save_search_data($search_params, META_ACCOMODATION_COURSE);

        redirect('index.php/villasapartment/hotel_crs_search/' . $search_id . '?' . $_SERVER['QUERY_STRING']);
    }
     function pre_hotel_crs_search($search_id = '') {
        //Global search Data
        $search_id = $this->save_pre_search(META_ACCOMODATION_COURSE);
        $this->save_search_cookie(META_ACCOMODATION_COURSE, $search_id);

        //Analytics
        $this->load->model('hotel_model');
        $search_params = $this->input->get();
       //debug($search_params);exit;
        $this->hotel_model->save_search_data($search_params, META_ACCOMODATION_COURSE);

        redirect('index.php/villas_apartment/hotel_crs_search/' . $search_id . '?' . $_SERVER['QUERY_STRING']);
    }

    /**
     * Pre Search For Bus
     */
    function pre_bus_search($search_id = '') {
        //Global search Data
        $search_id = $this->save_pre_search(META_BUS_COURSE);
        $this->save_search_cookie(META_BUS_COURSE, $search_id);

        //Analytics
        $this->load->model('bus_model');
        $search_params = $this->input->get();
      
        $this->bus_model->save_search_data($search_params, META_BUS_COURSE);

        redirect('bus/search/' . $search_id . '?' . $_SERVER['QUERY_STRING']);
    }

    /**
     * Pre Search For Packages
     */
    function pre_package_search($search_id = '') {
        //Global search Data
        $search_id = $this->save_pre_search(META_PACKAGE_COURSE);
        redirect('index.php/tours/search' . $search_id . '?' . $_SERVER['QUERY_STRING']);
    }

    /**
  * Pre Search for SightSeen
  */
  function pre_sight_seen_search($search_id=''){

    $search_id = $this->save_pre_search(META_SIGHTSEEING_COURSE);
    $this->save_search_cookie(META_SIGHTSEEING_COURSE, $search_id);
    //Analytics
    $this->load->model('sightseeing_model');
    $search_params = $this->input->get();
   
    $this->sightseeing_model->save_search_data($search_params, META_SIGHTSEEING_COURSE);
    
    redirect('index.php/sightseeing/search/'.$search_id.'?'.$_SERVER['QUERY_STRING']);
  }

  function presightseensearch($form,$destination_id,$category_id,$search_id=''){

    $search_params['from'] =  $form;
    $search_params['destination_id'] = $destination_id;
    $search_params['category_id'] = $category_id;

    $search_id = $this->save_pre_search_post(META_SIGHTSEEING_COURSE,$search_params);
    $this->save_search_cookie(META_SIGHTSEEING_COURSE, $search_id);
    //Analytics
    $this->load->model('sightseeing_model');
    //$search_params = $this->input->post();
   
    $this->sightseeing_model->save_search_data($search_params, META_SIGHTSEEING_COURSE);
    
    redirect('index.php/sightseeing/search/'.$search_id);
  }


  /*
  *Pre Transfer Search
  */
  function pre_transferv1_search($search_id=''){
    $search_id = $this->save_pre_search(META_TRANSFERV1_COURSE);
    $this->save_search_cookie(META_TRANSFERV1_COURSE, $search_id);
    //Analytics
    $this->load->model('transferv1_model');
    $search_params = $this->input->get();
    
    $this->transferv1_model->save_search_data($search_params, META_TRANSFERV1_COURSE);
    
    redirect('index.php/transferv1/search/'.$search_id.'?'.$_SERVER['QUERY_STRING'].'&booking_source='.PROVAB_TRANSFERV1_BOOKING_SOURCE);
  }
  function pre_transfercrsv1_search($search_id=''){
    $search_id = $this->save_pre_search(META_TRANSFERV1_COURSE);
    $this->save_search_cookie(META_TRANSFERV1_COURSE, $search_id);
    //Analytics
    $this->load->model('transferv1_model');
    $search_params = $this->input->get();
    
    $this->transferv1_model->save_search_data($search_params, META_TRANSFERV1_COURSE);
    
    redirect('index.php/privatetransfer/search/'.$search_id.'?'.$_SERVER['QUERY_STRING'].'&booking_source='.PROVAB_TRANSFERV1_SOURCE_CRS);
  }

  function pretransferv1search($form,$destination_id,$category_id,$search_id=''){
    $search_params['from'] =  $form;
    $search_params['destination_id'] = $destination_id;
    $search_params['category_id'] = $category_id;

    $search_id = $this->save_pre_search_post(META_TRANSFERV1_COURSE,$search_params);
    $this->save_search_cookie(META_TRANSFERV1_COURSE, $search_id);
    //Analytics
    $this->load->model('transferv1_model');
    //$search_params = $this->input->get();
    
    $this->transferv1_model->save_search_data($search_params, META_TRANSFERV1_COURSE);
    
    redirect('index.php/transferv1/search/'.$search_id);
  }

    /**
     * Pre Search For Transfer
     */
    function pre_transfer_search($search_id = '') {
        //Global search Data
        $search_id = $this->save_pre_search(META_TRANSFER_COURSE);
        $this->save_search_cookie(META_TRANSFER_COURSE, $search_id);

        //Analytics
        $this->load->model('transfer_model');
        $search_params = $this->input->get();

       $this->transfer_model->save_search_data($search_params, META_TRANSFER_COURSE);

        redirect('index.php/transfer/search/' . $search_id . '?' . $_SERVER['QUERY_STRING']);
    }
    
    
    /**
     * Pre Search used to save the data
     *
     */
    private function save_pre_search($search_type) {
        //Save data
        $search_params = $this->input->get();
      //  debug($search_params);die;
	if($search_type==META_ACCOMODATION_COURSE)
		{
		  if(count($search_params['adult'])==3)
		  {
		  $search_params['adult'][1]=0;
          $search_params['adult'][2]=0;
		  }
		}
        $search_data = json_encode($search_params);
        $insert_id = $this->custom_db->insert_record('search_history', array('search_type' => $search_type, 'search_data' => $search_data, 'created_datetime' => date('Y-m-d H:i:s')));
        return $insert_id['insert_id'];
    }

     private function save_pre_search_post($search_type,$search_params) {
        //Save data
       
        $search_data = json_encode($search_params);
        $insert_id = $this->custom_db->insert_record('search_history', array('search_type' => $search_type, 'search_data' => $search_data, 'created_datetime' => date('Y-m-d H:i:s')));
        return $insert_id['insert_id'];
    }


    /**
     * oops page of application will be loaded here
     */
    public function ooops() {
      echo   $this->template->isolated_view('utilities/404.php');
            redirect(base_url().'ooops_404'); 

    }
    public function error_404() {
      echo   $this->template->isolated_view('utilities/404.php');
    }
    public function ooops_404() {
      $this->template->view('utilities/404.php');
    }
    public function nodata() {
        $this->template->view('utilities/404nodata.php');
    }
//replaced older function with new for account activation
    /*
     * Activating User Account.
     * Account get activated only when the url is clicked from the account_activation_mail
     */
function activate_account_status()
  {
    $origin = $this->input->get('origin');
    $unsecure = substr($origin, 3);
    $secure_id = base64_decode($unsecure);
    $status = ACTIVE;
    $this->user_model->activate_account_status($status, $secure_id);
    $success['data'] = "Your account has successfully been activated. Welcome aboard to Travel Free Travels. We hope you enjoy your journey with us. Thank You for registering with us.";
    $this->load->view('success-message', $success);
  }
//old function
    function activate_account_status_old() {
        $origin = $this->input->get('origin');
        $unsecure = substr($origin, 3);
        $secure_id = base64_decode($unsecure);
        $status = ACTIVE;
        $this->user_model->activate_account_status($status, $secure_id);
        redirect(base_url());
    }

    /**
     * Email Subscribtion
     *
     */
    public function email_subscription() {
        $data = $this->input->post();

        $mail = $data['subEmail'];
        $domain_key = get_domain_auth_id();
        $inserted_id = $this->user_model->email_subscribtion($mail, $domain_key);
        if (isset($inserted_id) && $inserted_id != "already") {
            $this->application_logger->email_subscription($mail);
            $pdata['status'] = 1;
            echo json_encode($pdata);
        } elseif ($inserted_id == "already") {
            $pdata['status'] = 0;
            echo json_encode($pdata);
        } else {
            $pdata['status'] = 2;
            echo json_encode($pdata);
        }
    }

    function cms($page_label) {




        if (isset($page_label)) {
            $data = $this->custom_db->single_table_records('cms_pages', 'page_title,page_description,page_seo_title,page_seo_keyword,page_seo_description', array('page_label' => $page_label,'page_status' => 1));

            $this->template->view('cms/cms', $data);
        } else {
            redirect('general/index');
        }
    }

    function offline_payment() {
        $params = $this->input->post();
        $gotback = $this->user_model->offline_payment_insert($params);
        $url = base_url() . 'index.php/general/offline_approve/' . $gotback['refernce_code'];
       

        print_r(json_encode($gotback['refernce_code']));
    }

    function offline_approve($code) {
        $result['data'] = $this->user_model->offline_approval($code);
        $this->template->view('general/pay', $result);
    }

    /**
     * Booking Not Allowed Popup
     */
    function booking_not_allowed() {
        $this->template->view('general/booking_not_allowed');
    }

    function test() {
        echo 'test function';
    }

  function update_citylist()
  {
    $total= 80;
    for($num=0;$num<=$total;$num++){
      $city_response = file_get_contents(FCPATH."test-export-2017-2-27/destinations-".$num.".json");
     
      $city_list = json_decode($city_response,true);
      
      foreach ($city_list as $key => $value) {
        $insert_list['country_code'] = $value['country'];
        $insert_list['city_name'] = html_entity_decode($value['name']);
        $insert_list['city_code'] = $value['code'];
        $insert_list['parent_code'] = $value['parent'];
        $insert_list['latitude']  = $value['latitude'];
        $insert_list['longitude'] = $value['longitude'];
        $this->custom_db->insert_record('hotelspro_citylist',$insert_list);
      }
    }
      
  }

  //get contact_us_details
  function contact_us_details()
  {
        $page_data = $this->input->post();
        $insert_list['custname'] = $page_data['custname'];
        $insert_list['email'] = $page_data['email'];
        $insert_list['phone'] = $page_data['phone'];
        $insert_list['message'] = $page_data['message'];
        $this->custom_db->insert_record('contact_us',$insert_list);
        $this->template->view('general/contactus', $page_data);
  }

    //get promocode
    private function get_promocode_list(){
      $promocode_arr = array();
      $date = date('Y-m-d');
      $list= $this->custom_db->single_table_records('promo_code_list','*',array('status'=>ACTIVE,'display_home_page' => 'Yes','expiry_date >=' => $date));
      if($list['status']==true){
        $promocode_arr = $list['data'];
      }
      return $promocode_arr;
      }
    public function insert_api_data(){

    $output = false;
    $encrypt_method = "AES-256-CBC";
    $api_data = $this->custom_db->single_table_records('email_configuration', '*');
    $secret_iv = PROVAB_SECRET_IV;
   
    if($api_data['status'] == true){
      foreach($api_data['data'] as $data){
        if(!empty($data['username'])){
          $md5_key = PROVAB_MD5_SECRET;
          $encrypt_key = PROVAB_ENC_KEY;
          $decrypt_password = $this->db->query("SELECT AES_DECRYPT($encrypt_key,SHA2('".$md5_key."',512)) AS decrypt_data");
          
          $db_data = $decrypt_password->row();
         
          $secret_key = trim($db_data->decrypt_data); 
          $key = hash('sha256', $secret_key);
          $iv = substr(hash('sha256', $secret_iv), 0, 16);
          $username = openssl_encrypt($data['username'], $encrypt_method, $key, 0, $iv);
          $username = base64_encode($username);

          $password = openssl_encrypt($data['password'], $encrypt_method, $key, 0, $iv);
          $password = base64_encode($password);

          $host = openssl_encrypt($data['host'], $encrypt_method, $key, 0, $iv);
          $host = base64_encode($host);

          $cc = openssl_encrypt($data['cc'], $encrypt_method, $key, 0, $iv);
          $cc = base64_encode($cc);

          $port = openssl_encrypt($data['port'], $encrypt_method, $key, 0, $iv);
          $port = base64_encode($port);

          $bcc = openssl_encrypt($data['bcc'], $encrypt_method, $key, 0, $iv);
          $bcc = base64_encode($bcc);
        
          $api_config_data['from'] = $data['from'];
          $api_config_data['domain_origin'] = $data['domain_origin'];
          $api_config_data['username'] = $username;
          $api_config_data['password'] = $password;
          $api_config_data['host'] = $host;
          $api_config_data['cc'] = $cc;
          $api_config_data['port'] = $port;
          $api_config_data['bcc'] = $bcc;
          $api_config_data['status'] = $data['status'];
          
          $this->custom_db->insert_record('email_configuration_new',$api_config_data);
          
        }
      }
    }
    exit;
  }
  public function insert_api_urls(){
    
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $api_urls = $this->custom_db->single_table_records('api_urls', '*');
   
    $secret_iv = PROVAB_SECRET_IV;
    
    if($api_urls['status'] == true){
      foreach($api_urls['data'] as $data){
        
        if(!empty($data)){
          $md5_key = PROVAB_MD5_SECRET;
          $encrypt_key = PROVAB_ENC_KEY;
          $decrypt_password = $this->db->query("SELECT AES_DECRYPT($encrypt_key,SHA2('".$md5_key."',512)) AS decrypt_data");
          
          $db_data = $decrypt_password->row();
         
          $secret_key = trim($db_data->decrypt_data); 
          $key = hash('sha256', $secret_key);
          $iv = substr(hash('sha256', $secret_iv), 0, 16);
          $api_urls_data = openssl_encrypt($data['urls'], $encrypt_method, $key, 0, $iv);
          $urls_data = base64_encode($api_urls_data);
          $api_data['system'] = $data['system'];
          $api_data['urls'] = $urls_data;
         
          $this->custom_db->insert_record('api_urls_new',$api_data);
        }
      }
    }
  }
  public function decrypt_api_urls(){
    
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $api_urls = $this->custom_db->single_table_records('api_urls_new', '*');
   
    $secret_iv = PROVAB_SECRET_IV;
    
    if($api_urls['status'] == true){
      foreach($api_urls['data'] as $data){
        
        if(!empty($data)){
          $md5_key = PROVAB_MD5_SECRET;
          $encrypt_key = PROVAB_ENC_KEY;
          $decrypt_password = $this->db->query("SELECT AES_DECRYPT($encrypt_key,SHA2('".$md5_key."',512)) AS decrypt_data");
          
          $db_data = $decrypt_password->row();
         
          $secret_key = trim($db_data->decrypt_data); 
          $key = hash('sha256', $secret_key);
          $iv = substr(hash('sha256', $secret_iv), 0, 16);
          $urls = openssl_decrypt(base64_decode($data['urls']), $encrypt_method, $key, 0, $iv);
          debug($urls);exit;
        }
      }
    }
  }

  function pre_holiday_search($search_id = '') {
    // Global search Data
    
    $search_id = $this->save_pre_search ( META_PACKAGE_COURSE );
   
    $this->save_search_cookie ( META_PACKAGE_COURSE, $search_id );
    
    // Analytics
    $this->load->model ( 'tours_model' );
    $search_params = $this->input->get ();
  //  debug($search_params);die;
    $this->tours_model->save_search_data ( $search_params, META_PACKAGE_COURSE );
    
    redirect ( 'index.php/tours/search/' . $search_id . '?' . $_SERVER ['QUERY_STRING'] );
  }
  public function testjwt11(){
    error_reporting(E_ALL);
    $this->load->library('creatorjwt');

    $token="eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJzaXZhQHF1YXF1YS5jb20iLCJzY29wZXMiOlsiUk9MRV9BU1NPQ0lBVEUiLCJST0xFX0FTU09DSUFURSJdLCJ1c2VySWQiOjEyOTksInByb3ZpZGVyIjoiaW50ZXJuYWwiLCJpc3MiOiJodHRwczovL3d3dy5xdWFxdWEuY29tIiwiaWF0IjoxNTk1MjMxMzY0LCJleHAiOjE1OTY1MjczNjR9.xyWgRoj5cgdRMJX2u4SI1QL_K24xBHvTs9wWJ_mU2z1tCBuh2BGpU5BjItydT1pLfJZjLY42rCQVt2g9_q0Lkw";
    
    $dd=$this->creatorjwt->decode_token($token);

  }
  public function generatejwt(){
    error_reporting(0);
    $this->load->library('Creatorjwt');

    $payload = json_encode([
                'user_id' => 1,
                'role' => 'admin',
                'exp' => 1593828222
            ]);
    $dd=$this->creatorjwt->generate_token($payload);

  }
  public function save_enquiry(){
    $data = $this->input->post ();
    
    if (($data['tname']) && ($data['fromplace']) && ($data['toplace'])  && ($data['email']) && ($data['phone']) && ($data['buget']) && ($data['duration']) && ($data['message'])){
      
      $data ['ip_address'] = $this->session->userdata ( 'ip_address' );
      $data ['status'] = '0';
      $data ['date'] = date ( 'Y-m-d H:i:s' );
      if($data ['departure_date'] !="")
      {

      $data ['departure_date'] = date ('Y-m-d',strtotime($data ['departure_date']));
      }
      else
      {

      $data ['departure_date'] = "";
      }
      $data ['domain_list_fk'] = get_domain_auth_id ();
      
      $result = $this->Package_Model->savegeneralEnquiry( $data );
      if($result){
        $eemail=$data['email'];
        $tours_enquiry_data['first_name'] = $data['first_name'];
      
      $mail_template = $this->template->isolated_view ( 'holiday/inquery_template', $tours_enquiry_data);
      $this->load->library ( 'provab_mailer' );
      $s = $this->provab_mailer->send_mail ($eemail, 'Good Day! Your Tour Inquiry with Alkhaleej', $mail_template );
      }
      $status = true;
      $message = "Thank you for submitting your enquiry, will get back to soon!";
      header('content-type:application/json');
      echo json_encode(array('status' => $status, 'message' => $message));
      exit;
      
    }
  }
  public function save_keep_email(){
    $data = $this->input->post ();
    
    if ($data['email_id']){
      
      $data ['status'] = '0';
      $data ['subscribed_date'] = date ( 'Y-m-d H:i:s' );
    
      $data ['domain_list_fk'] = get_domain_auth_id ();
      
      $this->load->model('module_model');
      $result = $this->module_model->save_keep_self_email( $data );
      if($result){
        
        $status = true;
        $message = "Thank you for submitting your email id";
        header('content-type:application/json');
        echo json_encode(array('status' => $status, 'message' => $message));
        exit;
      
      }
      else
      {
          $status = false;
        $message = "Email id already exist";
        header('content-type:application/json');
        echo json_encode(array('status' => $status, 'message' => $message));
        exit;
      }
      
      
    }
  }

  public function testapichange(){
    $this->db->query("update api_urls_new SET status = 1 where id=1");
    $this->db->query("update api_urls_new SET status = 0 where id=2");

    $this->db->where('origin',1);
    $this->db->set('domain_webiste','https://bookings.quaqua.com/');
    $this->db->set('domain_key','TMX1512291534825461');
    $this->db->update('domain_list');
  }
public function liveapichange(){
    $this->db->query("update api_urls_new SET status = 0 where id=1");
    $this->db->query("update api_urls_new SET status = 1 where id=2");

    $this->db->where('origin',1);
    $this->db->set('domain_key','TMX1004111597231027');
    $this->db->set('domain_webiste','https://bookings.quaqua.com/');
    $this->db->update('domain_list');
  }

  public function tetsupdaterecord(){
    $query="select * from all_api_city_master where state_status=0 limit 1000";
    $query2="select * from tbo_city_list";
    $result=$this->db->query($query)->result_array();
    $result2=$this->db->query($query2)->result_array();
   
    if(!empty($result)){
      foreach ($result as $key => $value) {
          foreach ($result2 as $key1 => $value1) {/*cityid*/
            if($value['tbo_city_id']==$value1['cityid'])
            {
              $this->db->where('origin',$value['origin']);
              $this->db->set('stateprovince',$value1['stateprovince']);
              $this->db->set('state_status',1);
              $this->db->update('all_api_city_master');
            }

          }
        
      }
    }
  }
  
  public function change_admin_nam(){

    $email = provab_encrypt(trim('veera@quaqua.com'));    
    $password = provab_encrypt(md5(trim('Veera@2020')));
     $this->db->where('user_id',80);
    $this->db->set('email',$email);
    $this->db->set('user_name',$email);   
    $this->db->update('user');
    
  }

  
  public function about_us () { 
     $data_list1 = $this->custom_db->single_table_records ( 'about_us', '*', '', 0, 100000, array (
        'about_order' => 'ASC'
         
    ) );
     $page_data['adt_us']=$data_list1['data'];
     $this->template->view('general/aboutus',$page_data);
    }
    
    public function coming_soon1(){
          $this->template->view('general/comingsoon',$page_data);
    }
    
     public function crs_from_loc(){
          $data_list = $this->custom_db->single_table_records ( 'flight_crs_airport_list', '*');
          return $data_list;
    }

    public function reviews($strStatus="")
    {
        // debug($strStatus);exit;
        //debug("dfdf");exit;
        if($strStatus !=""){
            if($strStatus==1){
              
                $img_url = $GLOBALS['CI']->template->template_images('tick.png');
                $page_data['message']='<div class="modal fade bs-example-modal-lg" id="success-box-modal1" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"><div class="modal-dialog modal-lg" style="width: 500px;
    margin-top: 13%;"><div class="modal-content"><div class="modal-header" style="background: #16acdf !important;"><h4 class="modal-title" style="color: #ffffff !important;line-height: 20px;font-size: 18px;text-align: center;">Thank You</h4>
                      <button type="button" style="margin-top: -28px;
    font-size: 34px;" class="close" data-dismiss="modal"aria-label="Close"><span aria-hidden="true">&times;</span></button></div><div class="modal-body"><img style="text-align: center; margin: 10px auto; display: block;" src="'.$img_url.'"><div class="confirm-span" style="text-align: center;">Your review has been submitted successfully!</div></div></div></div></div>';
                      $page_data['count_status'] = 1;
            }else{
                $page_data['message']='<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Opps!</strong> Your review failed...</div>';
                $page_data['count_status'] = 0;
            }
        }
// debug($page_data['message']);exit;
        // debug($page_data);exit;
        $page_data['firstName']  = "";
        $page_data['lastName']  = "";
        $page_data['userName']  = "";
        $page_data['userEmail'] = "";
        $page_data['userPcode'] = "";
        $page_data['userPhone'] = "";
        $page_data['user_phone_country']= "";
        if(isset($this->entity_user_id)){
            $strUsrId = $this->entity_user_id;
            $query  = "SELECT user_id, uuid, email, first_name, last_name, concat(first_name,' ',last_name) as username, phone ";
            $query .= "from user where user_id='$strUsrId' ";
            $UsrData = $this->custom_db->get_result_by_query($query);
            //debug($UsrData);exit();
            $page_data['firstName']  = $UsrData[0]->first_name;
            $page_data['lastName']  = $UsrData[0]->last_name;
            $page_data['userName']  = $UsrData[0]->username;
            $page_data['userEmail'] = $UsrData[0]->email;
            $page_data['userPcode'] = $UsrData[0]->phone_code;
            $page_data['userPhone'] = $UsrData[0]->phone;
            $page_data['user_phone_country']    = $UsrData[0]->phone_country;
            
        }
        $page_data['country_code'] = $this->db_cache_api->get_country_code_list();

        // debug($page_data);exit;
        $this->template->view('general/reviews',$page_data);
    }

    function set_post_general_review()
    {
        //debug(error_reporting(E_ALL)); exit;
        if($this->input->post())
        {
            $all_post = $this->input->post(); //debug($all_post); exit;
            $all_post['created'] = date('Y-m-d H:i');
            $all_post['user_IP'] = $_SERVER['REMOTE_ADDR'];
            //debug($all_post); exit(); 
            /*if($all_post['module'] == 'holiday'){
                $condition = array('id' => $all_post['tour_id']);
                $tours = $this->Custom_Db->single_table_records('tours','*',$condition);
                $all_post['title'] = $tours['data'][0]['package_name'];
                $all_post['address'] = "N/A";
                //$all_post['title'] = $tours['data'][0]['package_name'];
                //debug(); exit;
            }*/
            /*$this->provab_mailer->send_mail($email, domain_name() . ' - Hotel Ticket', $mail_template, "");*/
            //$mail_template = $this->template->isolated_view ( 'user/post_reveiw_registration_template', $all_post );
            //$this->provab_mailer->send_mail ( $all_post['user_email'], ' Review', $mail_template );
            //echo $mail_template; exit;
            //$this->provab_mailer->send_mail($all_post['user_email'], 'Review', $mail_template);
            // debug($all_post);exit;
            $res = $this->custom_db->insert_record('general_user_review',$all_post);

            redirect('general/reviews/'.$res['status']);
            
        }
    }
    //added for new login
    public function newpage()
    {

    if (is_logged_in_user() == false) {
      $this->load->view('external-login');
    } else {
      return redirect(base_url('/'));
    }
    }
    public function newpage_old()
    {
        $this->template->view('newpages/newpage');
    }
     //changes added a new function to load the promocode view
 
  public function promocodes()
  {
    $pagedata = array();
    $this->template->view('general/promocodes', $page_data);
  }
   
   //changes end of added a new function to load the promocode view

    /*public function connectips(){
      $page_data=array();
        $this->template->view('cms/test', $page_data);
    }*/
  
}
