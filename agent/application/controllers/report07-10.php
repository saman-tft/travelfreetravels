<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * @package    Provab - Provab Application
 * @subpackage Travel Portal
 * @author     Balu A<balu.provab@gmail.com>
 * @version    V2
 */

class Report extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('bus_model');
		$this->load->model('hotel_model');
		$this->load->model('flight_model');
		$this->load->model('package_model');
		$this->load->model('car_model');
		$this->load->library('booking_data_formatter');
		$this->load->model('sightseeing_model');
		$this->load->model('transferv1_model');

	}

	function monthly_booking_report()
	{
		$this->template->view('report/monthly_booking_report');
	}
	function index(){
		$this->flight($offset=0);
	}
		public function holiday($module_type='b2c',$offset = 0)
	{
		$condition = array ();
		$get_data = $this->input->get ();
		
		if (! (isset ( $get_data ['created_datetime_from'] ) || isset ( $get_data ['created_datetime_to'] ))) {
			$get_data ['created_datetime_from'] = date ( 'd-m-Y' );
			$get_data ['created_datetime_to'] = date ( 'd-m-Y' );
		}		
		if (valid_array ( $get_data ) == true) {
			$from_date = trim ( @$get_data ['created_datetime_from'] );
			$to_date = trim ( @$get_data ['created_datetime_to'] );
			if (empty ( $from_date ) == false && empty ( $to_date ) == false) {
				$valid_dates = auto_swipe_dates ( $from_date, $to_date );
				$from_date = $valid_dates ['from_date'];
				$to_date = $valid_dates ['to_date'];
			}
			if (empty ( $from_date ) == false) {
				$condition [] = array (
						'BD.created_datetime',
						'>=',
						$this->db->escape ( db_current_datetime ( $from_date . ' 00:00:00' ) ) 
				);
			}
			if (empty ( $to_date ) == false) {
				$condition [] = array (
						'BD.created_datetime',
						'<=',
						$this->db->escape ( db_current_datetime ( $to_date . ' 23:59:59' ) ) 
				);
			}
			if (empty ( $get_data ['status'] ) == false && strtolower ( $get_data ['status'] ) != 'all') {
				$condition [] = array (
						'BD.status',
						'=',
						$this->db->escape ( $get_data ['status'] ) 
				);
			}			
			if (empty ( $get_data ['phone'] ) == false) {
				$condition [] = array (
						'BD.phone_number',
						' like ',
						$this->db->escape ( '%' . trim ( $get_data ['phone'] ) . '%' ) 
				);
			}			
			if (empty ( $get_data ['email'] ) == false) {
				$condition [] = array (
						'BD.email',
						' like ',
						$this->db->escape ( '%' . trim ( $get_data ['email'] ) . '%' ) 
				);
			}			
			if (empty ( $get_data ['app_reference'] ) == false) {
				$condition [] = array (
						'BD.app_reference',
						' like ',
						$this->db->escape ( '%' . trim ( $get_data ['app_reference'] ) . '%' ) 
				);
			}
			$page_data ['from_date'] = $from_date;
			$page_data ['to_date'] = $to_date;
		}
		$condition [] = array(
			'BD.status ',
			'IN ',
			'("BOOKING_CONFIRMED","CANCELLED","CANCELLATION_IN_PROCESS")',
			);
		/*if ($this->check_operation ( $offset )) {
			$op_data = $this->hotel_model->booking ( $condition );
			$op_data = $this->booking_data_formatter->format_hotel_booking_data ( $op_data, 'b2c' );
			$col = array (
					'app_reference' => 'Application Reference',
					'status' => 'Status',
					'confirmation_reference' => 'Confirmation Reference',
					'fare' => 'Fare',
					'grand_total' => 'Total Fare',
					'payment_mode' => 'Payment Mode',
					'voucher_date' => 'BookedOn' 
			);			
			$this->perform_operation ( $offset, $op_data ['data'] ['booking_details'], $col, 'Hotel Booking Report' );
		}*/
		$offset = intval ( $offset );		
		$this->load->model('tours_model');
		$total_records = $this->tours_model->booking ( $condition, true );
		$table_data = $this->tours_model->booking ( $condition, false, $offset, RECORDS_RANGE_5 );


		// debug(RECORDS_RANGE_5);exit();
		$page_data ['table_data'] = $table_data ['data'];
		$x = count ( $table_data );
		$this->load->library ( 'pagination' );
		if (count ( $_GET ) > 0)
			$config ['suffix'] = '?' . http_build_query ( $_GET, '', "&" );
		$config ['base_url'] = base_url () . 'index.php/report/holiday/';
		$config ['first_url'] = $config ['base_url'] . '?' . http_build_query ( $_GET );
		$page_data ['total_rows'] = $config ['total_rows'] = $total_records;
		$config ['per_page'] = RECORDS_RANGE_5;
		$this->pagination->initialize ( $config );
		/**
		 * TABLE PAGINATION
		 */
		$page_data ['total_records'] = $config ['total_rows'];
		$page_data ['search_params'] = $get_data;
		$page_data ['status_options'] = get_enum_list ( 'booking_status_options' );
		$page_data['active_column_list'] = $this->custom_db->single_table_records ( 'report_column_setting','column_name',array('module_name'=>'holiday','module_type'=>$module_type) )['data'];		
		$page_data['module_type'] = $module_type;
		$page_data['user_type']=4;
		// debug($page_data);die;
		$this->template->view ( 'report/holiday', $page_data );	
	}
function privatetransfers()
	{ 
		// echo "string";exit;
		// error_reporting(E_ALL);
		// echo '<h4>Under Working</h4>';
		$current_user_id = $GLOBALS['CI']->entity_user_id;
		$get_data = $this->input->get();
		// debug($get_data); exit();
		//debug($get_data); die;
		$condition = array();

		$filter_data = $this->format_basic_search_filters();
		$page_data['from_date'] = $filter_data['from_date'];
		$page_data['to_date'] = $filter_data['to_date'];
		$condition = $filter_data['filter_condition'];
		// $condition[] = array('U.user_type', '=', B2C_USER, ' OR ', 'BD.created_by_id');
		$condition[] = array('BD.module_type', '=', '"transfers"');

		// debug($condition);exit();
		// debug(RECORDS_RANGE_2);
		$total_records = $this->package_model->b2b_holiday_report($condition, true);		
		// debug($total_records);exit();
		$table_data = $this->package_model->b2b_holiday_report($condition, false, $offset, RECORDS_RANGE_2);
		// debug($table_data);exit();
		$table_data = $this->booking_data_formatter->format_flight_booking_data($table_data, 'b2c', false);
		// debug($table_data); exit;


		$page_data['table_data'] = $table_data['data'];
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/b2c_transfers_crs_report/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		$page_data['search_params'] = $get_data;
		$page_data['status_options'] = get_enum_list('booking_status_options');
		$page_data['module_type']="Transfer";
		// debug($page_data['table_data']); exit;
		$this->template->view('report/b2b_report_package', $page_data);
	}
function package($offset=0)
	{
		// error_reporting(E_ALL);
		$current_user_id = $GLOBALS['CI']->entity_user_id;
	//debug($current_user_id);exit();
		$condition = array();
		$get_data = $this->input->get();

		$filter_data = $this->format_basic_search_filters("holiday");
		$page_data['from_date'] = $filter_data['from_date'];
		$page_data['to_date'] = $filter_data['to_date'];
		$condition = $filter_data['filter_condition'];

		$this->load->model('tours_model');

		// debug($get_data);
		// die;
		$total_records = $this->tours_model->booking($condition,true);	
		// debug($total_records); die;
		$table_data = $this->tours_model->booking($condition,false, $offset, RECORDS_RANGE_2);
			// debug($table_data); exit;
		
		$page_data['table_data'] = $table_data;
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/package/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		//debug($page_data);exit;
		$page_data['search_params'] = $get_data;
		$page_data['status_options'] = get_enum_list('booking_status_options');
		// debug($page_data);
		// die;
		$this->template->view('report/b2c_report_holiday', $page_data);
	}
	public function export_confirmed_booking_holiday_report($op = '') {
        $this->load->model('tours_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('flight');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        $condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CONFIRMED'));

		$holiday_booking_data = $this->tours_model->booking ( $condition, false, 0, 2000);
        // debug($holiday_booking_data);exit;
       
        // $flight_booking_data = $flight_booking_data['data']['booking_details'];

		$holiday_booking_data=$holiday_booking_data['data'];

        $export_data = array();
        // debug($holiday_booking_data['data']);exit;
        $i=1;
        foreach ($holiday_booking_data as $k => $v) {
           // debug($v);exit;
           $pax_name="";
           	if($v['pax_details']){
                       foreach ($v['pax_details'] as  $value){ ?>

                        <?php

                         
                         $pax_name .= $value['pax_last_name']." ".$value['pax_first_name'].",";
                         
                      		}
                          
                         }

            $book_attr = json_decode($v['booking_details']['attributes'],TRUE);     
            $attributes=$v['booking_details']['attributes'];

            $attributes=json_decode($attributes,true);

            $aed_attributes=json_decode($v['booking_details']['aed_array']);
                         // debug($aed_attributes);exit;
                $base_fare=0.00;
                if(isset($aed_attributes->aed_basic_price))
                {
                    $base_fare=($aed_attributes->aed_basic_price);
                    $base_fare=str_replace(",", "", $base_fare);
                }
                

                 $markup=0.00;
                if(isset($aed_attributes->aed_markup))
                {
                    $markup=($aed_attributes->aed_markup);
                    $markup=str_replace(",", "", $markup);
                }

                $gst_value=0.00;
                if(isset($aed_attributes->aed_gst_value))
                {
                    $gst_value=($aed_attributes->aed_gst_value);
                    $gst_value=str_replace(",", "", $gst_value);
                }
                $discount_value=0.00;
                if(isset($aed_attributes->aed_discount))
                {
                    $discount_value=($aed_attributes->aed_discount);
                    $discount_value=str_replace(",", "", $discount_value);
                }
                $total_fare=($base_fare+$aed_attributes->aed_convenience_fee)-$discount_value;
            	$package_price=$base_fare-$markup-$gst_value;
            	$package_price=round($package_price);
                $package_price=isset($package_price)? number_format($package_price , 2):0;
                if($v['booking_details']['discount']){
                      // echo number_format($data['booking_details']['discount'],2);
                     // echo isset($data['booking_details']['discount'])? number_format(get_converted_currency_value ( $currency_obj->force_currency_conversion ( $data['booking_details']['discount'] ) ), 2):0;
                      $discount=round($discount_value);  
                    }else{
                      $discount= "NA";
                    }
                     $markup=round($markup);
                    $markup=number_format($markup,2);

                    $conveince_fee=round($aed_attributes->aed_convenience_fee);
                    $conveince_fee=isset($conveince_fee)? number_format($conveince_fee,2):0;
                     $gst_value=round($gst_value);
                    $gst_value= number_format($gst_value,2);
                    $base_fare=round($base_fare);
                	$base_fare=number_format($base_fare, 2);
                	$total=round($total_fare);
             if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['app_reference'] = $v['booking_details']['app_reference'];
			$export_data[$k]['package_name'] = $v['tours_details']['package_name'];
			$export_data[$k]['billing_email'] = $book_attr['billing_email'];
			$export_data[$k]['passenger_contact'] = $book_attr['passenger_contact'];
            $export_data[$k]['lead_pax_name'] = $pax_name;
            $export_data[$k]['departure_date'] =$attributes['departure_date'];
            $export_data[$k]['duration'] = $v['tours_details']['duration'];
            $export_data[$k]['package_price'] = $package_price;
            $export_data[$k]['discount'] = $discount;
            $export_data[$k]['markup'] = $markup;
            $export_data[$k]['conveince_fee'] = $conveince_fee;
           	$export_data[$k]['gst_value'] = $gst_value;
           	$export_data[$k]['base_fare'] = $base_fare;
           	$export_data[$k]['total'] = number_format($total,2);
           	
           	$export_data[$k]['booked_datetime'] = changeDateFormat($v['booking_details']['booked_datetime']);
           	$export_data[$k]['Online'] = "Online";
           
           	
        }
        // debug($export_data);exit;
        if ($op == 'excel') { // excel export
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'Reservation Code',
                    'c1' => 'Package Name',
                    'd1' => 'Email',
                    'e1' => 'Phone',
                    'f1' => 'Passanger Name',
                    'g1' => 'Departure Date',
                    'h1' => 'Number Of Days',
                    'i1' => 'Package Price',
                    'j1' => 'Promocode Amount',
                    'k1' => 'Markup',
                    'l1' => 'Convenience Fee',
                    'm1' => 'VAT',
					'n1' => 'Total Fare',
                    'o1' => 'Grand Total',                    
                    'p1' => 'BookedOn',
                    'q1' => 'Billing Type',
                    
                );
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'app_reference',
                    'c' => 'package_name',
                    'd' => 'billing_email',
                    'e' => 'passenger_contact',
                    'f' => 'lead_pax_name',
                    'g' => 'departure_date',
                    'h' => 'duration',
                    'i' => 'package_price',
                    'j' => 'discount',
                    'k' => 'markup',
                    'l' => 'conveince_fee',
                    'm' => 'gst_value',
                    'n' => 'base_fare',
                    'o' => 'total',                    
                    'p' => 'booked_datetime',
                    'q' => 'Online',
                    
                    
                );
           
            $excel_sheet_properties = array(
                'title' => 'Confirmed_Booking_holidayReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'Confirmed_Booking_holidayReport',
                'sheet_title' => 'Confirmed_Booking_holidayReport'
            );

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties);
        }
        else if ($op == 'csv') { // excel export
        	// echo "dd";exit;
            // debug($export_data);exit;

        	$headings = array("Sl. No.",'Reservation Code','Package Name','Email','Phone','Passanger Name','Departure Date','Number Of Days','Package Price','Promocode Amount','Markup','Convenience Fee','VAT','Total Fare','Grand Total','BookedOn','Billing Type'); 

           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
            $this->provab_csv->csv_export($headings,'holiday Confirmed Booking Report', $export_data);
           
           
			
			
        }
        else if($op == 'pdf')
        {
        	$this->load->library ( 'provab_pdf' );
  			$this->load->library ( 'provab_mailer' ); 
  			$create_pdf = new Provab_Pdf();
  			$pdf_data['export_data']=$holiday_booking_data;
  			// debug($pdf_data['export_data']);exit;
  			$mail_template =$this->template->isolated_view('report/b2c_report_holiday_pdf',$pdf_data);
  			$this->provab_pdf->create_pdf($mail_template);


        }
    }

     /*
     * For Cancelled Booking
     * Export AirlineReport details to Excel Format or PDF
     */

    public function export_cancelled_booking_holiday_report($op = '') {
        $this->load->model('flight_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('flight');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        $condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CANCELLED'));

        $holiday_booking_data = $this->tours_model->booking ( $condition, false, 0, 2000);
        // debug($holiday_booking_data);exit;
       
        // $flight_booking_data = $flight_booking_data['data']['booking_details'];

		$holiday_booking_data=$holiday_booking_data['data'];

        $export_data = array();
        // debug($holiday_booking_data['data']);exit;
        $i=1;
        foreach ($holiday_booking_data as $k => $v) {
           // debug($v);exit;
           $pax_name="";
           	if($v['pax_details']){
                       foreach ($v['pax_details'] as  $value){ ?>

                        <?php

                         
                         $pax_name .= $value['pax_last_name']." ".$value['pax_first_name'].",";
                         
                      		}
                          
                         }

            $book_attr = json_decode($v['booking_details']['attributes'],TRUE);     
            $attributes=$v['booking_details']['attributes'];

            $attributes=json_decode($attributes,true);

            $aed_attributes=json_decode($v['booking_details']['aed_array']);
                         // debug($aed_attributes);exit;
                $base_fare=0.00;
                if(isset($aed_attributes->aed_basic_price))
                {
                    $base_fare=($aed_attributes->aed_basic_price);
                    $base_fare=str_replace(",", "", $base_fare);
                }
                

                 $markup=0.00;
                if(isset($aed_attributes->aed_markup))
                {
                    $markup=($aed_attributes->aed_markup);
                    $markup=str_replace(",", "", $markup);
                }

                $gst_value=0.00;
                if(isset($aed_attributes->aed_gst_value))
                {
                    $gst_value=($aed_attributes->aed_gst_value);
                    $gst_value=str_replace(",", "", $gst_value);
                }
                $discount_value=0.00;
                if(isset($aed_attributes->aed_discount))
                {
                    $discount_value=($aed_attributes->aed_discount);
                    $discount_value=str_replace(",", "", $discount_value);
                }
                $total_fare=($base_fare+$aed_attributes->aed_convenience_fee)-$discount_value;
            	$package_price=$base_fare-$markup-$gst_value;
            	$package_price=round($package_price);
                $package_price=isset($package_price)? number_format($package_price , 2):0;
                if($v['booking_details']['discount']){
                      // echo number_format($data['booking_details']['discount'],2);
                     // echo isset($data['booking_details']['discount'])? number_format(get_converted_currency_value ( $currency_obj->force_currency_conversion ( $data['booking_details']['discount'] ) ), 2):0;
                      $discount=round($discount_value);  
                    }else{
                      $discount= "NA";
                    }
                     $markup=round($markup);
                    $markup=number_format($markup,2);

                    $conveince_fee=round($aed_attributes->aed_convenience_fee);
                    $conveince_fee=isset($conveince_fee)? number_format($conveince_fee,2):0;
                     $gst_value=round($gst_value);
                    $gst_value= number_format($gst_value,2);
                    $base_fare=round($base_fare);
                	$base_fare=number_format($base_fare, 2);
                	$total=round($total_fare);
                	if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['app_reference'] = $v['booking_details']['app_reference'];
			$export_data[$k]['package_name'] = $v['tours_details']['package_name'];
			$export_data[$k]['billing_email'] = $book_attr['billing_email'];
			$export_data[$k]['passenger_contact'] = $book_attr['passenger_contact'];
            $export_data[$k]['lead_pax_name'] = $pax_name;
            $export_data[$k]['departure_date'] =$attributes['departure_date'];
            $export_data[$k]['duration'] = $v['tours_details']['duration'];
            $export_data[$k]['package_price'] = $package_price;
            $export_data[$k]['discount'] = $discount;
            $export_data[$k]['markup'] = $markup;
            $export_data[$k]['conveince_fee'] = $conveince_fee;
           	$export_data[$k]['gst_value'] = $gst_value;
           	$export_data[$k]['base_fare'] = $base_fare;
           	$export_data[$k]['total'] = number_format($total,2);
           	
           	$export_data[$k]['booked_datetime'] = changeDateFormat($v['booking_details']['booked_datetime']);
           	$export_data[$k]['Online'] = "Online";
           
           	
        }
        // debug($export_data);exit;
        if ($op == 'excel') { // excel export
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'Reservation Code',
                    'c1' => 'Package Name',
                    'd1' => 'Email',
                    'e1' => 'Phone',
                    'f1' => 'Passanger Name',
                    'g1' => 'Departure Date',
                    'h1' => 'Number Of Days',
                    'i1' => 'Package Price',
                    'j1' => 'Promocode Amount',
                    'k1' => 'Markup',
                    'l1' => 'Convenience Fee',
                    'm1' => 'VAT',
					'n1' => 'Total Fare',
                    'o1' => 'Grand Total',                    
                    'p1' => 'BookedOn',
                    'q1' => 'Billing Type',
                    
                );
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'app_reference',
                    'c' => 'package_name',
                    'd' => 'billing_email',
                    'e' => 'passenger_contact',
                    'f' => 'lead_pax_name',
                    'g' => 'departure_date',
                    'h' => 'duration',
                    'i' => 'package_price',
                    'j' => 'discount',
                    'k' => 'markup',
                    'l' => 'conveince_fee',
                    'm' => 'gst_value',
                    'n' => 'base_fare',
                    'o' => 'total',                    
                    'p' => 'booked_datetime',
                    'q' => 'Online',
                    
                    
                );
           
            $excel_sheet_properties = array(
                'title' => 'Cancelled_Booking_holidayReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'Cancelled_Booking_holidayReport',
                'sheet_title' => 'Cancelled_Booking_holidayReport'
            );

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties);
        }
        else if ($op == 'csv') { // excel export
        	// echo "dd";exit;
            // debug($export_data);exit;


               $headings = array("Sl. No.",'Reservation Code','Package Name','Email','Phone','Passanger Name','Departure Date','Number Of Days','Package Price','Promocode Amount','Markup','Convenience Fee','VAT','Total Fare','Grand Total','BookedOn','Billing Type'); 

           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
            $this->provab_csv->csv_export($headings,'holiday Cancelled Booking Report', $export_data);
           
           
			
			
        }
        else if($op == 'pdf')
        {
        	$this->load->library ( 'provab_pdf' );
  			$this->load->library ( 'provab_mailer' ); 
  			$create_pdf = new Provab_Pdf();
  			$pdf_data['export_data']=$holiday_booking_data;
  			// debug($pdf_data['export_data']);exit;
  			$mail_template =$this->template->isolated_view('report/b2c_report_holiday_pdf',$pdf_data);
  			$this->provab_pdf->create_pdf($mail_template);


        }
    }
function bus($offset=0)
{
		$get_data = $this->input->get();
		$page_data = array();
		$condition = array();
		$filter_data = $this->format_basic_search_filters();
		$page_data['from_date'] = $filter_data['from_date'];
		$page_data['to_date'] = $filter_data['to_date'];
		$condition = $filter_data['filter_condition'];
		if(isset($get_data['filter_report_data']) == true && empty($get_data['filter_report_data']) == false) {
			$filter_report_data = trim($get_data['filter_report_data']);
			$search_filter_condition = '(BD.app_reference like "%'.$filter_report_data.'%" OR BD.pnr like "%'.$filter_report_data.'%")';
			$total_records = $this->bus_model->filter_booking_report($search_filter_condition, true);
			$table_data = $this->bus_model->filter_booking_report($search_filter_condition, false, $offset, RECORDS_RANGE_2);
		} else {
			$total_records = $this->bus_model->booking($condition, true);
			$table_data = $this->bus_model->booking($condition, false, $offset, RECORDS_RANGE_2);
		}
		$table_data = $this->booking_data_formatter->format_bus_booking_data($table_data, 'b2b');
		$page_data['table_data'] = $table_data['data'];
		/** TABLE PAGINATION */
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/bus/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		$page_data['customer_email'] = $this->entity_email;
		$this->template->view('report/bus', $page_data);
	}
	public function export_confirmed_booking_bus_report($op = '') {
        $this->load->model('bus_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('bus');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        $condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CONFIRMED'));

        $bus_booking_data = $this->bus_model->booking($condition, false, 0, 2000); //Maximum 500 Data Can be exported at time
        $bus_booking_data = $this->booking_data_formatter->format_bus_booking_data($bus_booking_data, 'b2b');
        $bus_booking_data = $bus_booking_data['data']['booking_details'];



        $export_data = array();
        //debug($bus_booking_data);exit;
        $i=1;
        foreach ($bus_booking_data as $k => $v) {
           if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['app_reference'] = $v['app_reference'];
			$export_data[$k]['lead_pax_name'] = $v['lead_pax_name'];
            $export_data[$k]['lead_pax_email'] = $v['lead_pax_email'];
            $export_data[$k]['lead_pax_phone_number'] = $v['lead_pax_phone_number'];
            $export_data[$k]['Pnr'] = $v['pnr'];
            $export_data[$k]['operator'] = $v['operator'];
            $export_data[$k]['from'] = $v['departure_from'];
            $export_data[$k]['to'] = $v['arrival_to'];
           	$export_data[$k]['bus_type'] = $v['bus_type'];
           	$export_data[$k]['Comm.Fare'] = $v['fare'];
           	$export_data[$k]['commission'] = $v['admin_commission'];
           	$export_data[$k]['TDS'] = $v['admin_tds'];
           	$export_data[$k]['NetFare'] = $v['admin_buying_price'];
           	$export_data[$k]['convinence_amount'] = $v['convinence_amount'];
           	$export_data[$k]['Markup'] = $v['admin_markup'];
           	$export_data[$k]['gst'] = $v['gst'];
           	$export_data[$k]['Discount'] = $v['discount'];
           	$export_data[$k]['grand_total'] = $v['grand_total'];
           	$export_data[$k]['Travel date'] = $v['journey_datetime'];
           	$export_data[$k]['booked_date'] = date('d-m-Y', strtotime($v['booked_date']));
           	        		
           	
        }
        //debug($export_data[$k]['Payment Status']);exit;
        if ($op == 'excel') { // excel export
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'app_reference',
                    'c1' => 'Lead Pax Name',
                    'd1' => 'Lead Pax Email',
                    'e1' => 'Lead Pax Phone',
                    'f1' => 'Pnr',
                    'g1' => 'operator',
                    'h1' => 'From',
                    'i1' => 'To',
                    'j1' => 'Seat Type',
                    'k1' => 'commision Fare',
                    'l1' => 'commission',
                    'm1' => 'Tds',
					'n1' => 'Net Fare',
                    'o1' => 'Conivence Fee',
                    'p1' => 'Markup',
                    'q1' => 'GST',
                    'r1' => 'Discount',
                    's1' => 'Total Fare',
                    't1' => 'Travel date',
                    'u1' => 'Booked On',
                   
                );
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'app_reference',
                    'c' => 'lead_pax_name',
                    'd' => 'lead_pax_email',
                    'e' => 'lead_pax_phone_number',
                    'f' => 'Pnr',
                    'g' => 'operator',
                    'h' => 'from',
                    'i' => 'to',
                    'j' => 'bus_type',
                    'k' => 'Comm.Fare',
                    'l' => 'commission',
                    'm' => 'TDS',
                    'n' => 'NetFare',
                    'o' => 'convinence_amount',
                    'p' => 'Markup',
                    'q' => 'gst',
                    'r' => 'Discount',
                    's' => 'grand_total',
                  	't' => 'Travel date',
                    'u' => 'booked_date',
                                        
                );
           
            $excel_sheet_properties = array(
                'title' => 'Confirmed_Booking_BusReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'Confirmed_Booking_BusReport',
                'sheet_title' => 'Confirmed_Booking_BusReport'
            );

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties);
        }
        else if ($op == 'csv') { // excel export
        	// echo "dd";exit;
            	
               $headings = array("Sl. No.","app_reference","Lead Pax Name","Lead Pax Email","Lead Pax Phone","Pnr","operator","From","To","Seat Type","commision Fare","commission","Tds","NetFare","convinence_amount","Markup","GST","Discount","Total Fare","Travel date","Booked On"); 
           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
           $this->provab_csv->csv_export($headings,'Confirmed_Booking_BusReport', $export_data);
           
           
			
			
        }
        else if($op == 'pdf')
        {
        	$this->load->library ( 'provab_pdf' );
  			
  			$create_pdf = new Provab_Pdf();
  			$pdf_data['export_data']=$bus_booking_data;
  			// debug($pdf_data['export_data']);exit;
  			$mail_template =$this->template->isolated_view('report/b2c_report_bus_pdf',$pdf_data);
  			$this->provab_pdf->create_pdf($mail_template);
  			


        } 
    }
    public function export_cancelled_booking_bus_report($op = '') {
        $this->load->model('bus_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('bus');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        $condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CANCELLED'));

        $bus_booking_data = $this->bus_model->booking($condition, false, 0, 2000); //Maximum 500 Data Can be exported at time
        $bus_booking_data = $this->booking_data_formatter->format_bus_booking_data($bus_booking_data, 'b2b');
        $bus_booking_data = $bus_booking_data['data']['booking_details'];



        $export_data = array();
        //debug($bus_booking_data);exit;
        $i=1;
        foreach ($bus_booking_data as $k => $v) {
           if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['app_reference'] = $v['app_reference'];
			$export_data[$k]['lead_pax_name'] = $v['lead_pax_name'];
            $export_data[$k]['lead_pax_email'] = $v['lead_pax_email'];
            $export_data[$k]['lead_pax_phone_number'] = $v['lead_pax_phone_number'];
            $export_data[$k]['Pnr'] = $v['pnr'];
            $export_data[$k]['operator'] = $v['operator'];
            $export_data[$k]['from'] = $v['departure_from'];
            $export_data[$k]['to'] = $v['arrival_to'];
           	$export_data[$k]['bus_type'] = $v['bus_type'];
           	$export_data[$k]['Comm.Fare'] = $v['fare'];
           	$export_data[$k]['commission'] = $v['admin_commission'];
           	$export_data[$k]['TDS'] = $v['admin_tds'];
           	$export_data[$k]['NetFare'] = $v['admin_buying_price'];
           	$export_data[$k]['convinence_amount'] = $v['convinence_amount'];
           	$export_data[$k]['Markup'] = $v['admin_markup'];
           	$export_data[$k]['gst'] = $v['gst'];
           	$export_data[$k]['Discount'] = $v['discount'];
           	$export_data[$k]['grand_total'] = $v['grand_total'];
           	$export_data[$k]['Travel date'] = $v['journey_datetime'];
           	$export_data[$k]['booked_date'] = date('d-m-Y', strtotime($v['booked_date']));
           	        		
           	
        }
        //debug($export_data[$k]['Payment Status']);exit;
        if ($op == 'excel') { // excel export
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'app_reference',
                    'c1' => 'Lead Pax Name',
                    'd1' => 'Lead Pax Email',
                    'e1' => 'Lead Pax Phone',
                    'f1' => 'Pnr',
                    'g1' => 'operator',
                    'h1' => 'From',
                    'i1' => 'To',
                    'j1' => 'Seat Type',
                    'k1' => 'commision Fare',
                    'l1' => 'commission',
                    'm1' => 'Tds',
					'n1' => 'Net Fare',
                    'o1' => 'Conivence Fee',
                    'p1' => 'Markup',
                    'q1' => 'GST',
                    'r1' => 'Discount',
                    's1' => 'Total Fare',
                    't1' => 'Travel date',
                    'u1' => 'Booked On',
                   
                );
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'app_reference',
                    'c' => 'lead_pax_name',
                    'd' => 'lead_pax_email',
                    'e' => 'lead_pax_phone_number',
                    'f' => 'Pnr',
                    'g' => 'operator',
                    'h' => 'from',
                    'i' => 'to',
                    'j' => 'bus_type',
                    'k' => 'Comm.Fare',
                    'l' => 'commission',
                    'm' => 'TDS',
                    'n' => 'NetFare',
                    'o' => 'convinence_amount',
                    'p' => 'Markup',
                    'q' => 'gst',
                    'r' => 'Discount',
                    's' => 'grand_total',
                  	't' => 'Travel date',
                    'u' => 'booked_date',
                                        
                );
           
            $excel_sheet_properties = array(
                'title' => 'Cancelled_Booking_BusReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'Cancelled_Booking_BusReport',
                'sheet_title' => 'Cancelled_Booking_BusReport'
            );

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties);
        }
        else if ($op == 'csv') { // excel export
        	// echo "dd";exit;
           
               $headings = array("Sl. No.","app_reference","Lead Pax Name","Lead Pax Email","Lead Pax Phone","Pnr","operator","From","To","Seat Type","commision Fare","commission","Tds","NetFare","convinence_amount","Markup","GST","Discount","Total Fare","Travel date","Booked On"); 
           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
           $this->provab_csv->csv_export($headings,'Cancelled_Booking_BusReport', $export_data);
           
           
			
			
        }
        else if($op == 'pdf')
        {
        	$this->load->library ( 'provab_pdf' );
  			
  			$create_pdf = new Provab_Pdf();
  			$pdf_data['export_data']=$bus_booking_data;
  			// debug($pdf_data['export_data']);exit;
  			$mail_template =$this->template->isolated_view('report/b2c_report_bus_pdf',$pdf_data);
  			$this->provab_pdf->create_pdf($mail_template);
  			


        }
    }
	function villasapartment($offset=0)
	{
		$get_data = $this->input->get();
		$page_data = array();
		$condition = array();
		$filter_data = $this->format_basic_search_filters();
		$page_data['from_date'] = $filter_data['from_date'];
		$page_data['to_date'] = $filter_data['to_date'];
		$condition = $filter_data['filter_condition'];
		if(isset($get_data['filter_report_data']) == true && empty($get_data['filter_report_data']) == false) {
			$filter_report_data = trim($get_data['filter_report_data']);
			$search_filter_condition = '(BD.app_reference like "%'.$filter_report_data.'%" OR BD.confirmation_reference like "%'.$filter_report_data.'%")';
			$total_records = $this->hotel_model->filter_booking_report($search_filter_condition, true);
			$table_data = $this->hotel_model->filter_booking_report($search_filter_condition, false, $offset, RECORDS_RANGE_2);
		} else {
			$total_records = $this->hotel_model->booking($condition, true);
			$table_data = $this->hotel_model->booking($condition, false, $offset, RECORDS_RANGE_2);
		}

		$table_data = $this->booking_data_formatter->format_hotel_booking_data($table_data, 'b2b');
		$page_data['table_data'] = $table_data['data'];
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/hotel/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		//debug($page_data);exit;
		$this->template->view('report/hotel', $page_data);
	}
	function hotel($offset=0)
	{
		$get_data = $this->input->get();
		$page_data = array();
		$condition = array();
		$filter_data = $this->format_basic_search_filters();
		$page_data['from_date'] = $filter_data['from_date'];
		$page_data['to_date'] = $filter_data['to_date'];
		$condition = $filter_data['filter_condition'];
		if(isset($get_data['filter_report_data']) == true && empty($get_data['filter_report_data']) == false) {
			$filter_report_data = trim($get_data['filter_report_data']);
			$search_filter_condition = '(BD.app_reference like "%'.$filter_report_data.'%" OR BD.confirmation_reference like "%'.$filter_report_data.'%")';
			$total_records = $this->hotel_model->filter_booking_report($search_filter_condition, true);
			$table_data = $this->hotel_model->filter_booking_report($search_filter_condition, false, $offset, RECORDS_RANGE_2);
		} else {
			$total_records = $this->hotel_model->booking($condition, true);
			$table_data = $this->hotel_model->booking($condition, false, $offset, RECORDS_RANGE_2);
		}

		$table_data = $this->booking_data_formatter->format_hotel_booking_data($table_data, 'b2b');
		$page_data['table_data'] = $table_data['data'];
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/hotel/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		//debug($page_data);exit;
		$this->template->view('report/hotel', $page_data);
	}
	public function export_confirmed_booking_hotel_report($op = '') {
        $this->load->model('hotel_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('hotel');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        $condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CONFIRMED'));

        $hotel_booking_data = $this->hotel_model->booking($condition, false, 0, 2000); //Maximum 500 Data Can be exported at time
        $hotel_booking_data = $this->booking_data_formatter->format_hotel_booking_data($hotel_booking_data,'b2b');
        $hotel_booking_data = $hotel_booking_data['data']['booking_details'];



        $export_data = array();
        // debug($hotel_booking_data);exit;
        $i=1;
        foreach ($hotel_booking_data as $k => $v) {
           if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['Reference No'] = $v['app_reference'];
			$export_data[$k]['Confirmation_Reference'] = $v['confirmation_reference'];
			$export_data[$k]['lead_pax_name'] = $v['lead_pax_name'];
            $export_data[$k]['lead_pax_email'] = $v['lead_pax_email'];
            $export_data[$k]['lead_pax_phone_number'] = $v['lead_pax_phone_number'];
            $export_data[$k]['Hotel Name'] = $v['hotel_name'];
            $export_data[$k]['No.of rooms'] = $v['total_rooms'];
            $export_data[$k]['No.of Adult'] = $v['adult_count'];
            $export_data[$k]['No.of Child'] = $v['child_count'];
           	$export_data[$k]['city'] = $v['hotel_location'];
           	$export_data[$k]['check_in'] = $v['hotel_check_in'];
           	$export_data[$k]['check_out'] = $v['hotel_check_out'];
           	$export_data[$k]['commission_fare'] = $v['fare'];
           	$export_data[$k]['TDS'] = $v['TDS'];
           	$export_data[$k]['Admin_markup'] = $v['admin_markup'];
           	$export_data[$k]['convinence_amount'] = $v['convinence_amount'];
           	$export_data[$k]['gst'] = $v['gst'];
           	$export_data[$k]['Discount'] = $v['discount'];
           	$export_data[$k]['grand_total'] = $v['grand_total'];
           	$export_data[$k]['booked_on'] = date('d-m-Y', strtotime($v['voucher_date']));
           	        		
           	
        }
        // debug($hotel_booking_data);exit;
		//debug($export_data[$k]['Payment Status']);exit;
        if ($op == 'excel') { // excel export
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'Reference No',
                    'c1' => 'Lead Pax Name',
                    'd1' => 'Lead Pax Email',
                    'e1' => 'Lead Pax Phone',
                    'f1' => 'Confirmation_Reference',
                    'g1' => 'Hotel Name',
                    'h1' => 'No.of rooms',
                    'i1' => 'No.of Adult',
                    'j1' => 'No.of Child',
                    'k1' => 'city',
                    'l1' => 'check_in',
                    'm1' => 'check_out',
					'n1' => 'Commission Fare',
                    'o1' => 'TDS',
                    'p1' => 'Admin Markup',
                    'q1' => 'GST',
                    'r1' => 'convinence Fee',
                    's1' => 'Discount',
                    't1' => 'Grand Total',
                    'u1' => 'Booked On',
                   
                );
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'Reference No',
                    'c' => 'lead_pax_name',
                    'd' => 'lead_pax_email',
                    'e' => 'lead_pax_phone_number',
                    'f' => 'Confirmation_Reference',
                    'g' => 'Hotel Name',
                    'h' => 'No.of rooms',
                    'i' => 'No.of Adult',
                    'j' => 'No.of Child',
                    'k' => 'city',
                    'l' => 'check_in',
                    'm' => 'check_out',
                    'n' => 'commission_fare',
                    'o' => 'TDS',
                    'p' => 'Admin_markup',
                    'q' => 'convinence_amount',
                    'r' => 'gst',
                    's' => 'Discount',
                  	't' => 'grand_total',
                    'u' => 'booked_on',
                                        
                );
           
            $excel_sheet_properties = array(
                'title' => 'Confirmed_Booking_HotelReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'Confirmed_Booking_HotelReport',
                'sheet_title' => 'Confirmed_Booking_HotelReport'
            );

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties);
        }
        else if ($op == 'csv') { // excel export
        	// echo "dd";exit;
            
               $headings = array("Sl. No.","Reference No","Lead Pax Name","Lead Pax Email","Lead Pax Phone","Confirmation_Reference","Hotel Name","No.of rooms","No.of Adult","No.of Child","city","check_in","check_out","Commission Fare","TDS","Admin Markup","GST","convinence Fee","Discount","Grand Total","Booked On"); 
           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
            $this->provab_csv->csv_export($headings,'Hotel Confirmed Booking Report', $export_data);
           
           
			
			
        }
        else if($op == 'pdf')
        {
        	$this->load->library ( 'provab_pdf' );
  			$this->load->library ( 'provab_mailer' ); 
  			$create_pdf = new Provab_Pdf();
  			$pdf_data['export_data']=$hotel_booking_data;
  			// debug($pdf_data['export_data']);exit;
  			$mail_template =$this->template->isolated_view('report/b2c_report_hotel_pdf',$pdf_data);
  			$this->provab_pdf->create_pdf($mail_template);


        } 
    }
    public function export_cancelled_booking_hotel_report($op = '') {
        $this->load->model('hotel_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('hotel');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        $condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CANCELLED'));

        $hotel_booking_data = $this->hotel_model->booking($condition, false, 0, 2000); //Maximum 500 Data Can be exported at time
        $hotel_booking_data = $this->booking_data_formatter->format_hotel_booking_data($hotel_booking_data, 'b2b');
        $hotel_booking_data = $hotel_booking_data['data']['booking_details'];



        $export_data = array();
        //debug($hotel_booking_data);exit;
        $i=1;
        foreach ($hotel_booking_data as $k => $v) {
           if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['Reference No'] = $v['app_reference'];
			$export_data[$k]['Confirmation_Reference'] = $v['confirmation_reference'];
			$export_data[$k]['lead_pax_name'] = $v['lead_pax_name'];
            $export_data[$k]['lead_pax_email'] = $v['lead_pax_email'];
            $export_data[$k]['lead_pax_phone_number'] = $v['lead_pax_phone_number'];
            $export_data[$k]['Hotel Name'] = $v['hotel_name'];
            $export_data[$k]['No.of rooms'] = $v['total_rooms'];
            $export_data[$k]['No.of Adult'] = $v['adult_count'];
            $export_data[$k]['No.of Child'] = $v['child_count'];
           	$export_data[$k]['city'] = $v['hotel_location'];
           	$export_data[$k]['check_in'] = $v['hotel_check_in'];
           	$export_data[$k]['check_out'] = $v['hotel_check_out'];
           	$export_data[$k]['commission_fare'] = $v['fare'];
           	$export_data[$k]['TDS'] = $v['TDS'];
           	$export_data[$k]['Admin_markup'] = $v['admin_markup'];
           	$export_data[$k]['convinence_amount'] = $v['convinence_amount'];
           	$export_data[$k]['gst'] = $v['gst'];
           	$export_data[$k]['Discount'] = $v['discount'];
           	$export_data[$k]['grand_total'] = $v['grand_total'];
           	$export_data[$k]['booked_on'] = date('d-m-Y', strtotime($v['voucher_date']));
           	        		
           	
        }
		//debug($export_data[$k]['Payment Status']);exit;
        if ($op == 'excel') { // excel export
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'Reference No',
                    'c1' => 'Lead Pax Name',
                    'd1' => 'Lead Pax Email',
                    'e1' => 'Lead Pax Phone',
                    'f1' => 'Confirmation_Reference',
                    'g1' => 'Hotel Name',
                    'h1' => 'No.of rooms',
                    'i1' => 'No.of Adult',
                    'j1' => 'No.of Child',
                    'k1' => 'city',
                    'l1' => 'check_in',
                    'm1' => 'check_out',
					'n1' => 'Commission Fare',
                    'o1' => 'TDS',
                    'p1' => 'Admin Markup',
                    'q1' => 'GST',
                    'r1' => 'convinence Fee',
                    's1' => 'Discount',
                    't1' => 'Grand Total',
                    'u1' => 'Booked On',
                   
                );
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'Reference No',
                    'c' => 'lead_pax_name',
                    'd' => 'lead_pax_email',
                    'e' => 'lead_pax_phone_number',
                    'f' => 'Confirmation_Reference',
                    'g' => 'Hotel Name',
                    'h' => 'No.of rooms',
                    'i' => 'No.of Adult',
                    'j' => 'No.of Child',
                    'k' => 'city',
                    'l' => 'check_in',
                    'm' => 'check_out',
                    'n' => 'commission_fare',
                    'o' => 'TDS',
                    'p' => 'Admin_markup',
                    'q' => 'convinence_amount',
                    'r' => 'gst',
                    's' => 'Discount',
                  	't' => 'grand_total',
                    'u' => 'booked_on',
                                        
                );
           
            $excel_sheet_properties = array(
                'title' => 'Cancelled_Booking_HotelReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'Cancelled_Booking_HotelReport',
                'sheet_title' => 'Cancelled_Booking_HotelReport'
            );

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties);
        }
         else if ($op == 'csv') { // excel export
        	// echo "dd";exit;
            
               $headings = array("Sl. No.","Reference No","Lead Pax Name","Lead Pax Email","Lead Pax Phone","Confirmation_Reference","Hotel Name","No.of rooms","No.of Adult","No.of Child","city","check_in","check_out","Commission Fare","TDS","Admin Markup","GST","convinence Fee","Discount","Grand Total","Booked On"); 
           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
            $this->provab_csv->csv_export($headings,'Hotel cancelled Booking Report', $export_data);
           
           
			
			
        }
        else if($op == 'pdf')
        {
        	$this->load->library ( 'provab_pdf' );
  			$this->load->library ( 'provab_mailer' ); 
  			$create_pdf = new Provab_Pdf();
  			$pdf_data['export_data']=$hotel_booking_data;
  			// debug($pdf_data['export_data']);exit;
  			$mail_template =$this->template->isolated_view('report/b2c_report_hotel_pdf',$pdf_data);
  			$this->provab_pdf->create_pdf($mail_template);


        }
    }

	/**
	 * Flight Report
	 * @param $offset
	 */
	function flight($offset=0)
	{
		$get_data = $this->input->get();
		$page_data = array();
		$condition = array();
		$filter_data = $this->format_basic_search_filters();
		$page_data['from_date'] = $filter_data['from_date'];
		$page_data['to_date'] = $filter_data['to_date'];
		$condition = $filter_data['filter_condition'];
		if(isset($get_data['filter_report_data']) == true && empty($get_data['filter_report_data']) == false) {
			$filter_report_data = trim($get_data['filter_report_data']);
			$search_filter_condition = '(TD.app_reference like "%'.$filter_report_data.'%" OR TD.pnr like "%'.$filter_report_data.'%")';
			$total_records = $this->flight_model->filter_booking_report($search_filter_condition, true);
			$table_data = $this->flight_model->filter_booking_report($search_filter_condition);
		} else {
			$total_records = $this->flight_model->booking($condition, true);
			$table_data = $this->flight_model->booking($condition, false, $offset, RECORDS_RANGE_2);
		}
		$table_data = $this->booking_data_formatter->format_flight_booking_data($table_data, 'b2b');
		$page_data['table_data'] = $table_data['data'];
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/flight/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		$this->template->view('report/airline', $page_data);
	}
	public function export_confirmed_booking_airline_report($op = '') {
        $this->load->model('flight_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('flight');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        $condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CONFIRMED'));

        $flight_booking_data = $this->flight_model->booking($condition, false, 0, 2000); //Maximum 500 Data Can be exported at time
        $flight_booking_data = $this->booking_data_formatter->format_flight_booking_data($flight_booking_data, 'b2b');
        $flight_booking_data = $flight_booking_data['data']['booking_details'];

        // debug($flight_booking_data);exit;

        $export_data = array();
        // debug($flight_booking_data);exit;
        $i=1;
        foreach ($flight_booking_data as $k => $v) {
           if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['app_reference'] = $v['app_reference'];
            $export_data[$k]['lead_pax_name'] = $v['lead_pax_name'];
            $export_data[$k]['lead_pax_email'] = $v['lead_pax_email'];
            $export_data[$k]['lead_pax_phone_number'] = $v['lead_pax_phone_number'];
            $export_data[$k]['gds_pnr'] = $v['pnr'];
            $export_data[$k]['airline_pnr'] = $v['booking_itinerary_details'][0]['airline_pnr'];
            $export_data[$k]['airline_code'] = $v['booking_itinerary_details'][0]['airline_code'];
            $export_data[$k]['journey_from'] = $v['journey_from'];
           	$export_data[$k]['journey_to'] = $v['journey_to'];
           	$export_data[$k]['journey_start'] = $v['journey_start'];
           	$export_data[$k]['journey_end'] = $v['journey_end'];
           	$export_data[$k]['commission_fare'] = $v['fare'];
           	$export_data[$k]['commission'] = $v['net_commission'];
           	$export_data[$k]['tds'] = $v['net_commission_tds'];
           	$export_data[$k]['net_fare'] = $v['net_fare'];
           	$export_data[$k]['gst'] = $v['gst'];
           	$export_data[$k]['convinence_amount'] = $v['convinence_amount'];
           	$export_data[$k]['discount'] = $v['discount'];
           	$export_data[$k]['grand_total'] = $v['grand_total'];
           	$export_data[$k]['booked_date'] = date('d-m-Y', strtotime($v['booked_date']));
           	
        }

        if ($op == 'excel') { // excel export
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'Appreference',
                    'c1' => 'Lead Pax Name',
                    'd1' => 'Lead Pax Email',
                    'e1' => 'Lead Pax Phone',
                    'f1' => 'GDS PNR',
                    'g1' => 'Airline PNR',
                    'h1' => 'Airline Code',
                    'i1' => 'From',
                    'j1' => 'To',
                    'k1' => 'Form Date',
                    'l1' => 'To Date',
                    'm1' => 'Commission Fare',
					'n1' => 'Commission',
                    'o1' => 'TDS',
                    'p1' => 'Net Fare',
                    'q1' => 'GST',
                    'r1' => 'Convinence Amount',
                    's1' => 'Discount',
                    't1' => 'Customer Paid',
                    'u1' => 'Booked Date',
                );
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'app_reference',
                    'c' => 'lead_pax_name',
                    'd' => 'lead_pax_email',
                    'e' => 'lead_pax_phone_number',
                    'f' => 'gds_pnr',
                    'g' => 'airline_pnr',
                    'h' => 'airline_code',
                    'i' => 'journey_from',
                    'j' => 'journey_to',
                    'k' => 'journey_start',
                    'l' => 'journey_end',
                    'm' => 'commission_fare',
                    'n' => 'commission',
                    'o' => 'tds',
                    'p' => 'net_fare',
                    'q' => 'gst',
                    'r' => 'convinence_amount',
                    's' => 'discount',
                    't' => 'grand_total',
                    'u' => 'booked_date',
                    
                );
           
            $excel_sheet_properties = array(
                'title' => 'Confirmed_Booking_AirlineReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'Confirmed_Booking_AirlineReport',
                'sheet_title' => 'Confirmed_Booking_AirlineReport'
            );

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties);
        }
        else if ($op == 'csv') { // excel export
        	// echo "dd";exit;
            
               $headings = array("Sl. No.","Appreference","Lead Pax Name","Lead Pax Email","Lead Pax Phone","GDS PNR","Airline PNR","Airline Code","From","To","Form Date","To Date","Commission Fare","Commission","TDS","Net Fare","GST","Convinence Amount","Discount","Customer Paid","Booked Date"); 
           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
            $this->provab_csv->csv_export($headings,'Airline Confirmed Booking Report', $export_data);
           
           
			
			
        }
        else if($op == 'pdf')
        {
        	$this->load->library ( 'provab_pdf' );
  			$this->load->library ( 'provab_mailer' ); 
  			$create_pdf = new Provab_Pdf();
  			$pdf_data['export_data']=$flight_booking_data;
  			// debug($pdf_data['export_data']);exit;
  			$mail_template =$this->template->isolated_view('report/b2c_report_airline_pdf',$pdf_data);
  			$this->provab_pdf->create_pdf($mail_template);


        }
    }
    public function export_cancelled_booking_airline_report($op = '') {
        $this->load->model('flight_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('flight');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        $condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CANCELLED'));

        $flight_booking_data = $this->flight_model->booking($condition, false, 0, 2000); //Maximum 500 Data Can be exported at time
        $flight_booking_data = $this->booking_data_formatter->format_flight_booking_data($flight_booking_data,  'b2b');
        $flight_booking_data = $flight_booking_data['data']['booking_details'];



        $export_data = array();
        // debug($flight_booking_data);exit;
        $i=1;
        foreach ($flight_booking_data as $k => $v) {
           if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['app_reference'] = $v['app_reference'];
            $export_data[$k]['lead_pax_name'] = $v['lead_pax_name'];
            $export_data[$k]['lead_pax_email'] = $v['lead_pax_email'];
            $export_data[$k]['lead_pax_phone_number'] = $v['lead_pax_phone_number'];
            $export_data[$k]['gds_pnr'] = $v['pnr'];
            $export_data[$k]['airline_pnr'] = $v['booking_itinerary_details'][0]['airline_pnr'];
            $export_data[$k]['airline_code'] = $v['booking_itinerary_details'][0]['airline_code'];
            $export_data[$k]['journey_from'] = $v['journey_from'];
           	$export_data[$k]['journey_to'] = $v['journey_to'];
           	$export_data[$k]['journey_start'] = $v['journey_start'];
           	$export_data[$k]['journey_end'] = $v['journey_end'];
           	$export_data[$k]['commission_fare'] = $v['fare'];
           	$export_data[$k]['commission'] = $v['net_commission'];
           	$export_data[$k]['tds'] = $v['net_commission_tds'];
           	$export_data[$k]['net_fare'] = $v['net_fare'];
           	$export_data[$k]['gst'] = $v['gst'];
           	$export_data[$k]['convinence_amount'] = $v['convinence_amount'];
           	$export_data[$k]['discount'] = $v['discount'];
           	$export_data[$k]['grand_total'] = $v['grand_total'];
           	$export_data[$k]['booked_date'] = date('d-m-Y', strtotime($v['booked_date']));
           	
        }

        if ($op == 'excel') { // excel export
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'Appreference',
                    'c1' => 'Lead Pax Name',
                    'd1' => 'Lead Pax Email',
                    'e1' => 'Lead Pax Phone',
                    'f1' => 'GDS PNR',
                    'g1' => 'Airline PNR',
                    'h1' => 'Airline Code',
                    'i1' => 'From',
                    'j1' => 'To',
                    'k1' => 'Form Date',
                    'l1' => 'To Date',
                    'm1' => 'Commission Fare',
					'n1' => 'Commission',
                    'o1' => 'TDS',
                    'p1' => 'Net Fare',
                    'q1' => 'GST',
                    'r1' => 'Convinence Amount',
                    's1' => 'Discount',
                    't1' => 'Customer Paid',
                    'u1' => 'Booked Date',
                );
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'app_reference',
                    'c' => 'lead_pax_name',
                    'd' => 'lead_pax_email',
                    'e' => 'lead_pax_phone_number',
                    'f' => 'gds_pnr',
                    'g' => 'airline_pnr',
                    'h' => 'airline_code',
                    'i' => 'journey_from',
                    'j' => 'journey_to',
                    'k' => 'journey_start',
                    'l' => 'journey_end',
                    'm' => 'commission_fare',
                    'n' => 'commission',
                    'o' => 'tds',
                    'p' => 'net_fare',
                    'q' => 'gst',
                    'r' => 'convinence_amount',
                    's' => 'discount',
                    't' => 'grand_total',
                    'u' => 'booked_date',
                    
                );
           
            $excel_sheet_properties = array(
                'title' => 'Confirmed_Booking_AirlineReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'Confirmed_Booking_AirlineReport',
                'sheet_title' => 'Confirmed_Booking_AirlineReport'
            );

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties);
        }
        else if ($op == 'csv') { // excel export
        	// echo "dd";exit;
            
               $headings = array("Sl. No.","Appreference","Lead Pax Name","Lead Pax Email","Lead Pax Phone","GDS PNR","Airline PNR","Airline Code","From","To","Form Date","To Date","Commission Fare","Commission","TDS","Net Fare","GST","Convinence Amount","Discount","Customer Paid","Booked Date"); 
           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
            $this->provab_csv->csv_export($headings,'Airline Cancelled Booking Report', $export_data);
           
           
			
			
        }
        else if($op == 'pdf')
        {
        	$this->load->library ( 'provab_pdf' );
  			$this->load->library ( 'provab_mailer' ); 
  			$create_pdf = new Provab_Pdf();
  			$pdf_data['export_data']=$flight_booking_data;
  			// debug($pdf_data['export_data']);exit;
  			$mail_template =$this->template->isolated_view('report/b2c_report_airline_pdf',$pdf_data);
  			$this->provab_pdf->create_pdf($mail_template);


        }
    }

	/************************************** CAR REPORT STARTS ***********************************/
	/**
	 * Cae Report
	 * @param $offset
	 */
	function car($offset=0)
	{
		validate_user_login();
		$condition = array();
		$total_records = $this->car_model->booking($condition, true);
		$table_data = $this->car_model->booking($condition, false, $offset, RECORDS_RANGE_2);
		$table_data = $this->booking_data_formatter->format_car_booking_datas($table_data, 'b2c');
		$page_data['table_data'] = $table_data['data'];
		/** TABLE PAGINATION */
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/car/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		$page_data['customer_email'] = $this->entity_email;
		// debug($page_data);exit;
		$this->template->view('report/car', $page_data);
	}
	public function export_confirmed_booking_car_report($op = '') {
        $this->load->model('bus_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('bus');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        $condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CONFIRMED'));

        $bus_booking_data = $this->car_model->booking($condition, false, 0, 2000); //Maximum 500 Data Can be exported at time
        $bus_booking_data = $this->booking_data_formatter->format_bus_booking_data($bus_booking_data, 'b2c');
        $bus_booking_data = $bus_booking_data['data']['booking_details'];



        $export_data = array();
        //debug($bus_booking_data);exit;
        $i=1;
        foreach ($bus_booking_data as $k => $v) {
        	if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
           
			$export_data[$k]['app_reference'] = $v['app_reference'];
			$export_data[$k]['lead_pax_name'] = $v['lead_pax_name'];
            $export_data[$k]['lead_pax_email'] = $v['lead_pax_email'];
            $export_data[$k]['lead_pax_phone_number'] = $v['lead_pax_phone_number'];
            $export_data[$k]['Pnr'] = $v['pnr'];
            $export_data[$k]['operator'] = $v['operator'];
            $export_data[$k]['from'] = $v['departure_from'];
            $export_data[$k]['to'] = $v['arrival_to'];
           	$export_data[$k]['bus_type'] = $v['bus_type'];
           	$export_data[$k]['Comm.Fare'] = $v['fare'];
           	$export_data[$k]['commission'] = $v['admin_commission'];
           	$export_data[$k]['TDS'] = $v['admin_tds'];
           	$export_data[$k]['NetFare'] = $v['admin_buying_price'];
           	$export_data[$k]['convinence_amount'] = $v['convinence_amount'];
           	$export_data[$k]['Markup'] = $v['admin_markup'];
           	$export_data[$k]['gst'] = $v['gst'];
           	$export_data[$k]['Discount'] = $v['discount'];
           	$export_data[$k]['grand_total'] = $v['grand_total'];
           	$export_data[$k]['Travel date'] = $v['journey_datetime'];
           	$export_data[$k]['booked_date'] = date('d-m-Y', strtotime($v['booked_date']));
           	        		
           	
        }
        //debug($export_data[$k]['Payment Status']);exit;
        if ($op == 'excel') { // excel export
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'app_reference',
                    'c1' => 'Lead Pax Name',
                    'd1' => 'Lead Pax Email',
                    'e1' => 'Lead Pax Phone',
                    'f1' => 'Pnr',
                    'g1' => 'operator',
                    'h1' => 'From',
                    'i1' => 'To',
                    'j1' => 'Seat Type',
                    'k1' => 'commision Fare',
                    'l1' => 'commission',
                    'm1' => 'Tds',
					'n1' => 'Net Fare',
                    'o1' => 'Conivence Fee',
                    'p1' => 'Markup',
                    'q1' => 'GST',
                    'r1' => 'Discount',
                    's1' => 'Total Fare',
                    't1' => 'Travel date',
                    'u1' => 'Booked On',
                   
                );
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'app_reference',
                    'c' => 'lead_pax_name',
                    'd' => 'lead_pax_email',
                    'e' => 'lead_pax_phone_number',
                    'f' => 'Pnr',
                    'g' => 'operator',
                    'h' => 'from',
                    'i' => 'to',
                    'j' => 'bus_type',
                    'k' => 'Comm.Fare',
                    'l' => 'commission',
                    'm' => 'TDS',
                    'n' => 'NetFare',
                    'o' => 'convinence_amount',
                    'p' => 'Markup',
                    'q' => 'gst',
                    'r' => 'Discount',
                    's' => 'grand_total',
                  	't' => 'Travel date',
                    'u' => 'booked_date',
                                        
                );
           
            $excel_sheet_properties = array(
                'title' => 'Confirmed_Booking_BusReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'Confirmed_Booking_BusReport',
                'sheet_title' => 'Confirmed_Booking_BusReport'
            );

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties);
        }
        else if ($op == 'csv') { // excel export
        	// echo "dd";exit;
            
               $headings = array("Sl. No.","app_reference","Lead Pax Name","Lead Pax Email","Lead Pax Phone","Pnr","operator","From","To","Seat Type","commision Fare","commission","Tds","NetFare","convinence_amount","Markup","GST","Discount","Total Fare","Travel date","Booked On"); 
           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
           $this->provab_csv->csv_export($headings,'Confirmed_Booking_BusReport', $export_data);
           
           
			
			
        }
        else if($op == 'pdf')
        {
        	$this->load->library ( 'provab_pdf' );
  			
  			$create_pdf = new Provab_Pdf();
  			$pdf_data['export_data']=$bus_booking_data;
  			// debug($pdf_data['export_data']);exit;
  			$mail_template =$this->template->isolated_view('report/b2c_report_bus_pdf',$pdf_data);
  			$this->provab_pdf->create_pdf($mail_template);
  			


        } 
    }
    public function export_cancelled_booking_car_report($op = '') {
        $this->load->model('bus_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('bus');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        $condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CANCELLED'));

        $bus_booking_data = $this->car_model->booking($condition, false, 0, 2000); //Maximum 500 Data Can be exported at time
        $bus_booking_data = $this->booking_data_formatter->format_bus_booking_data($bus_booking_data, 'b2c');
        $bus_booking_data = $bus_booking_data['data']['booking_details'];



        $export_data = array();
        //debug($bus_booking_data);exit;
        $i=1;
        foreach ($bus_booking_data as $k => $v) {
           if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['app_reference'] = $v['app_reference'];
			$export_data[$k]['lead_pax_name'] = $v['lead_pax_name'];
            $export_data[$k]['lead_pax_email'] = $v['lead_pax_email'];
            $export_data[$k]['lead_pax_phone_number'] = $v['lead_pax_phone_number'];
            $export_data[$k]['Pnr'] = $v['pnr'];
            $export_data[$k]['operator'] = $v['operator'];
            $export_data[$k]['from'] = $v['departure_from'];
            $export_data[$k]['to'] = $v['arrival_to'];
           	$export_data[$k]['bus_type'] = $v['bus_type'];
           	$export_data[$k]['Comm.Fare'] = $v['fare'];
           	$export_data[$k]['commission'] = $v['admin_commission'];
           	$export_data[$k]['TDS'] = $v['admin_tds'];
           	$export_data[$k]['NetFare'] = $v['admin_buying_price'];
           	$export_data[$k]['convinence_amount'] = $v['convinence_amount'];
           	$export_data[$k]['Markup'] = $v['admin_markup'];
           	$export_data[$k]['gst'] = $v['gst'];
           	$export_data[$k]['Discount'] = $v['discount'];
           	$export_data[$k]['grand_total'] = $v['grand_total'];
           	$export_data[$k]['Travel date'] = $v['journey_datetime'];
           	$export_data[$k]['booked_date'] = date('d-m-Y', strtotime($v['booked_date']));
           	        		
           	
        }
        //debug($export_data[$k]['Payment Status']);exit;
        if ($op == 'excel') { // excel export
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'app_reference',
                    'c1' => 'Lead Pax Name',
                    'd1' => 'Lead Pax Email',
                    'e1' => 'Lead Pax Phone',
                    'f1' => 'Pnr',
                    'g1' => 'operator',
                    'h1' => 'From',
                    'i1' => 'To',
                    'j1' => 'Seat Type',
                    'k1' => 'commision Fare',
                    'l1' => 'commission',
                    'm1' => 'Tds',
					'n1' => 'Net Fare',
                    'o1' => 'Conivence Fee',
                    'p1' => 'Markup',
                    'q1' => 'GST',
                    'r1' => 'Discount',
                    's1' => 'Total Fare',
                    't1' => 'Travel date',
                    'u1' => 'Booked On',
                   
                );
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'app_reference',
                    'c' => 'lead_pax_name',
                    'd' => 'lead_pax_email',
                    'e' => 'lead_pax_phone_number',
                    'f' => 'Pnr',
                    'g' => 'operator',
                    'h' => 'from',
                    'i' => 'to',
                    'j' => 'bus_type',
                    'k' => 'Comm.Fare',
                    'l' => 'commission',
                    'm' => 'TDS',
                    'n' => 'NetFare',
                    'o' => 'convinence_amount',
                    'p' => 'Markup',
                    'q' => 'gst',
                    'r' => 'Discount',
                    's' => 'grand_total',
                  	't' => 'Travel date',
                    'u' => 'booked_date',
                                        
                );
           
            $excel_sheet_properties = array(
                'title' => 'Cancelled_Booking_BusReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'Cancelled_Booking_BusReport',
                'sheet_title' => 'Cancelled_Booking_BusReport'
            );

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties);
        }
        else if ($op == 'csv') { // excel export
        	// echo "dd";exit;
            
               $headings = array("Sl. No.","app_reference","Lead Pax Name","Lead Pax Email","Lead Pax Phone","Pnr","operator","From","To","Seat Type","commision Fare","commission","Tds","NetFare","convinence_amount","Markup","GST","Discount","Total Fare","Travel date","Booked On"); 
           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
           $this->provab_csv->csv_export($headings,'Cancelled_Booking_BusReport', $export_data);
           
           
			
			
        }
        else if($op == 'pdf')
        {
        	$this->load->library ( 'provab_pdf' );
  			
  			$create_pdf = new Provab_Pdf();
  			$pdf_data['export_data']=$bus_booking_data;
  			// debug($pdf_data['export_data']);exit;
  			$mail_template =$this->template->isolated_view('report/b2c_report_bus_pdf',$pdf_data);
  			$this->provab_pdf->create_pdf($mail_template);
  			


        }
    }
	/**
	 * Sightseeing Report
	 * @param $offset
	 */
	function activities($offset=0)
	{
		$get_data = $this->input->get();
		$page_data = array();
		$condition = array();
		$filter_data = $this->format_basic_search_filters();
		$page_data['from_date'] = $filter_data['from_date'];
		$page_data['to_date'] = $filter_data['to_date'];
		$condition = $filter_data['filter_condition'];
		if(isset($get_data['filter_report_data']) == true && empty($get_data['filter_report_data']) == false) {
			$filter_report_data = trim($get_data['filter_report_data']);
			$search_filter_condition = '(BD.app_reference like "%'.$filter_report_data.'%" OR BD.booking_reference like "%'.$filter_report_data.'%")';
			$total_records = $this->sightseeing_model->filter_booking_report($search_filter_condition, true);
			$table_data = $this->sightseeing_model->filter_booking_report($search_filter_condition);
		} else {
			$total_records = $this->sightseeing_model->booking($condition, true);
			$table_data = $this->sightseeing_model->booking($condition, false, $offset, RECORDS_RANGE_2);
		}
		$table_data = $this->booking_data_formatter->format_sightseeing_booking_data($table_data, 'b2b');

		$page_data['table_data'] = $table_data['data'];
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/sightseeing/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		$this->template->view('report/sightseeing', $page_data);
	}
	public function export_confirmed_booking_activities_report($op = '') {
        $this->load->model('sightseeing_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('activities');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        $condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CONFIRMED'));

        $activites_booking_data = $this->sightseeing_model->booking($condition, false, 0, 2000); //Maximum 500 Data Can be exported at time
        $activites_booking_data = $this->booking_data_formatter->format_sightseeing_booking_data($activites_booking_data,'b2b');
        $activites_booking_data = $activites_booking_data['data']['booking_details'];



        $export_data = array();
        //debug($activites_booking_data);exit;
        $i=1;
        foreach ($activites_booking_data as $k => $v) {
           if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['app_reference'] = $v['app_reference'];
			$export_data[$k]['confirmation_reference'] = $v['confirmation_reference'];
			$export_data[$k]['lead_pax_name'] = $v['lead_pax_name'];
            $export_data[$k]['lead_pax_email'] = $v['lead_pax_email'];
            $export_data[$k]['lead_pax_phone_number'] = $v['lead_pax_phone_number'];
            $export_data[$k]['product_name'] = $v['product_name'];
            $export_data[$k]['No of Adults'] = $v['adult_count'];
            $export_data[$k]['No of Child'] = $v['child_count'];
            $export_data[$k]['No of youth'] = $v['youth_count'];
            $export_data[$k]['No of Senior'] = $v['senior_count'];
            $export_data[$k]['No of infant'] = $v['infant_count'];
            $export_data[$k]['location'] = $v['cutomer_city'];
            //$export_data[$k]['created_datetime'] = $v['created_datetime'];
            $export_data[$k]['travel_date'] = $v['travel_date'];
           	//$export_data[$k]['currency'] = $v['currency'];
           	$export_data[$k]['Comm_Fare'] = $v['fare'];
           	$export_data[$k]['commission'] = $v['admin_commission'];
           	$export_data[$k]['admin_tds'] = $v['admin_tds'];
           	$export_data[$k]['net_fare'] = $v['admin_net_fare'];
           //	$export_data[$k]['admin_profit'] = $v['admin_commission'];
           	$export_data[$k]['admin_markup'] = $v['admin_markup'];
           	$export_data[$k]['convinence_amount'] = $v['convinence_amount'];
           	$export_data[$k]['gst'] = $v['gst'];
           	$export_data[$k]['Discount'] = $v['discount'];
           	$export_data[$k]['amount'] = $v['grand_total'];
           	$export_data[$k]['Booked_on'] = $v['voucher_date'];
           //	$export_data[$k]['grand_total'] = $v['grand_total'];
           	        		
           	
        }
		//debug($export_data[$k]['booked_date']);exit;
        if ($op == 'excel') { // excel export
        	//error_reporting(E_ALL);
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'APP reference',
                    'c1' => 'Confirmation_Reference',
                    'd1' => 'Lead Pax Name',
                    'e1' => 'Lead Pax Email',
                    'f1' => 'Lead Pax Phone Number',
                    'g1' => 'Product Name',
                    'h1' => 'No of Adults',
                    'i1' => 'No of Child',
                    'j1' => 'No of youth',
                    'k1' => 'No of Senior',
                    'l1' => 'No of infant',
                    'm1' => 'City',
					'n1' => 'Travel Date',
                   //'o1' => 'Currency',
                    'p1' => 'Commission Fare',
                    'q1' => 'Commission',
                    'r1' => 'Tds',
                    's1' => 'Admin NetFare',
                    't1' => 'Admin Markup',
                    'u1' => 'Convinence amount',
                    'v1' => 'GST',
                    'w1' => 'Discount',
                   'x1' => 'Customer Paid amount',
                    'y1' => 'Booked On',
                   
                );
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'app_reference',
                    'c' => 'confirmation_reference',
                    'd' => 'lead_pax_name',
                    'e' => 'lead_pax_email',
                    'f' => 'lead_pax_phone_number',
                    'g' => 'product_name',
                    'h' => 'No of Adults',
                    'i' => 'No of Child',
                    'j' => 'No of youth',
                    'k' => 'No of Senior',
                    'l' => 'No of infant',
                    'm' => 'location',
                    'n' => 'travel_date',
                   // 'o' => 'currency',
                    'p' => 'Comm_Fare',
                    'q' => 'commission',
                    'r' => 'admin_tds',
                    's' => 'net_fare',
                  	't' => 'admin_markup',
                    'u' => 'convinence_amount',
                    'v' => 'gst',
                    'w' => 'Discount',
                   'x' => 'amount',
                   'y' => 'Booked_on',
                                        
                );
           
            $excel_sheet_properties = array(
                'title' => 'Confirmed_Booking_activitesReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'Confirmed_Booking_activitesReport',
                'sheet_title' => 'Confirmed_Booking_activitesReport'
            );

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties);
        }
        else if ($op == 'csv') { // excel export
            // echo "dd";exit;
            
               $headings = array("Sl. No.","APP reference","Confirmation_Reference","Lead Pax Name","Lead Pax Email","Lead Pax Phone","Product Name","No of Adults","No of Child","No of youth","No of Senior","No of infant","City","Travel Date","Commission Fare","Commission",'Tds','Admin NetFare','Admin Markup','Convinence amount','GST','Discount','Customer Paid amount','Booked On'); 
           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
           $this->provab_csv->csv_export($headings,'Confirmed_Booking_activitesReport', $export_data);
           
           
            
            
        }
        else if($op == 'pdf')
        {
            $this->load->library ( 'provab_pdf' );
            
            $create_pdf = new Provab_Pdf();
            $pdf_data['export_data']=$activites_booking_data;
            // debug($pdf_data['export_data']);exit;
            $mail_template =$this->template->isolated_view('report/b2c_report_activities_pdf',$pdf_data);
            $this->provab_pdf->create_pdf($mail_template);
            


        } 
    }
    public function export_cancelled_booking_activities_report($op = '') {
        $this->load->model('sightseeing_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('activities');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        $condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CANCELLED'));

        $activites_booking_data = $this->sightseeing_model->booking($condition, false, 0, 2000); //Maximum 500 Data Can be exported at time
        $activites_booking_data = $this->booking_data_formatter->format_sightseeing_booking_data($activites_booking_data, 'b2b');
        $activites_booking_data = $activites_booking_data['data']['booking_details'];



        $export_data = array();
        //debug($activites_booking_data);exit;
        $i=1;
        foreach ($activites_booking_data as $k => $v) {
           if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['app_reference'] = $v['app_reference'];
			$export_data[$k]['confirmation_reference'] = $v['confirmation_reference'];
			$export_data[$k]['lead_pax_name'] = $v['lead_pax_name'];
            $export_data[$k]['lead_pax_email'] = $v['lead_pax_email'];
            $export_data[$k]['lead_pax_phone_number'] = $v['lead_pax_phone_number'];
            $export_data[$k]['product_name'] = $v['product_name'];
            $export_data[$k]['No of Adults'] = $v['adult_count'];
            $export_data[$k]['No of Child'] = $v['child_count'];
            $export_data[$k]['No of youth'] = $v['youth_count'];
            $export_data[$k]['No of Senior'] = $v['senior_count'];
            $export_data[$k]['No of infant'] = $v['infant_count'];
            $export_data[$k]['location'] = $v['cutomer_city'];
            //$export_data[$k]['created_datetime'] = $v['created_datetime'];
            $export_data[$k]['travel_date'] = $v['travel_date'];
           //	$export_data[$k]['currency'] = $v['currency'];
           	$export_data[$k]['Comm_Fare'] = $v['fare'];
           	$export_data[$k]['commission'] = $v['admin_commission'];
           	$export_data[$k]['admin_tds'] = $v['admin_tds'];
           	$export_data[$k]['net_fare'] = $v['admin_net_fare'];
           //	$export_data[$k]['admin_profit'] = $v['admin_commission'];
           	$export_data[$k]['admin_markup'] = $v['admin_markup'];
           	$export_data[$k]['convinence_amount'] = $v['convinence_amount'];
           	$export_data[$k]['gst'] = $v['gst'];
           	$export_data[$k]['Discount'] = $v['discount'];
           	$export_data[$k]['amount'] = $v['grand_total'];
           	$export_data[$k]['Booked_on'] = $v['voucher_date'];
           //	$export_data[$k]['grand_total'] = $v['grand_total'];
           	        		
           	
        }
		//debug($export_data[$k]['booked_date']);exit;
        if ($op == 'excel') { // excel export
        	//error_reporting(E_ALL);
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'APP reference',
                    'c1' => 'Confirmation_Reference',
                    'd1' => 'Lead Pax Name',
                    'e1' => 'Lead Pax Email',
                    'f1' => 'Lead Pax Phone Number',
                    'g1' => 'Product Name',
                    'h1' => 'No of Adults',
                    'i1' => 'No of Child',
                    'j1' => 'No of youth',
                    'k1' => 'No of Senior',
                    'l1' => 'No of infant',
                    'm1' => 'City',
					'n1' => 'Travel Date',
                   // 'o1' => 'Currency',
                    'p1' => 'Commission Fare',
                    'q1' => 'Commission',
                    'r1' => 'Tds',
                    's1' => 'Admin NetFare',
                    't1' => 'Admin Markup',
                    'u1' => 'Convinence amount',
                    'v1' => 'GST',
                    'w1' => 'Discount',
                   'x1' => 'Customer Paid amount',
                    'y1' => 'Booked On',
                   
                );
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'app_reference',
                    'c' => 'confirmation_reference',
                    'd' => 'lead_pax_name',
                    'e' => 'lead_pax_email',
                    'f' => 'lead_pax_phone_number',
                    'g' => 'product_name',
                    'h' => 'No of Adults',
                    'i' => 'No of Child',
                    'j' => 'No of youth',
                    'k' => 'No of Senior',
                    'l' => 'No of infant',
                    'm' => 'location',
                    'n' => 'travel_date',
                    //'o' => 'currency',
                    'p' => 'Comm_Fare',
                    'q' => 'commission',
                    'r' => 'admin_tds',
                    's' => 'net_fare',
                  	't' => 'admin_markup',
                    'u' => 'convinence_amount',
                    'v' => 'gst',
                    'w' => 'Discount',
                   'x' => 'amount',
                   'y' => 'Booked_on',
                                        
                );
           
            $excel_sheet_properties = array(
                'title' => 'Cancelled_Booking_activitesReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'Cancelled_Booking_activitesReport',
                'sheet_title' => 'Cancelled_Booking_activitesReport'
            );

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties);
        }
        else if ($op == 'csv') { // excel export
            // echo "dd";exit;
            
               $headings = array("Sl. No.","APP reference","Confirmation_Reference","Lead Pax Name","Lead Pax Email","Lead Pax Phone","Product Name","No of Adults","No of Child","No of youth","No of Senior","No of infant","City","Travel Date","Commission Fare","Commission",'Tds','Admin NetFare','Admin Markup','Convinence amount','GST','Discount','Customer Paid amount','Booked On'); 
           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
           $this->provab_csv->csv_export($headings,'Cancelled_Booking_activitesReport', $export_data);
           
           
            
            
        }
        else if($op == 'pdf')
        {
            $this->load->library ( 'provab_pdf' );
            
            $create_pdf = new Provab_Pdf();
            $pdf_data['export_data']=$activites_booking_data;
            // debug($pdf_data['export_data']);exit;
            $mail_template =$this->template->isolated_view('report/b2c_report_activities_pdf',$pdf_data);
            $this->provab_pdf->create_pdf($mail_template);
            


        } 
    }
	/**
	 * Transfers Report
	 * @param $offset
	 */
	function transfers($offset=0)
	{
		$get_data = $this->input->get();
		$page_data = array();
		$condition = array();
		$filter_data = $this->format_basic_search_filters();
		$page_data['from_date'] = $filter_data['from_date'];
		$page_data['to_date'] = $filter_data['to_date'];
		$condition = $filter_data['filter_condition'];
		if(isset($get_data['filter_report_data']) == true && empty($get_data['filter_report_data']) == false) {
			$filter_report_data = trim($get_data['filter_report_data']);
			$search_filter_condition = '(BD.app_reference like "%'.$filter_report_data.'%" OR BD.booking_reference like "%'.$filter_report_data.'%")';
			$total_records = $this->transferv1_model->filter_booking_report($search_filter_condition, true);
			$table_data = $this->transferv1_model->filter_booking_report($search_filter_condition);
		} else {
			$total_records = $this->transferv1_model->booking($condition, true);
			$table_data = $this->transferv1_model->booking($condition, false, $offset, RECORDS_RANGE_2);
		}

		$table_data = $this->booking_data_formatter->format_transferv1_booking_data($table_data, 'b2b');

		$page_data['table_data'] = $table_data['data'];
		$this->load->library('pagination');
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['base_url'] = base_url().'index.php/report/transfers/';
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
		$page_data['total_rows'] = $config['total_rows'] = $total_records;
		$config['per_page'] = RECORDS_RANGE_2;
		$this->pagination->initialize($config);
		/** TABLE PAGINATION */
		$page_data['total_records'] = $config['total_rows'];
		$this->template->view('report/transfers', $page_data);
	}
	public function export_confirmed_booking_transfer_report($op = '') {
        $this->load->model('transferv1_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('transfers');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        $condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CONFIRMED'));

        $transfer_booking_data = $this->transferv1_model->booking($condition, false, 0, 2000); //Maximum 500 Data Can be exported at time
        $transfer_booking_data = $this->booking_data_formatter->format_transferv1_booking_data($transfer_booking_data, 'b2b');
        $transfer_booking_data = $transfer_booking_data['data']['booking_details'];



        $export_data = array();
        //debug($transfer_booking_data);exit;
        $i=1;
        foreach ($transfer_booking_data as $k => $v) {
           if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['app_reference'] = $v['app_reference'];
			$export_data[$k]['confirmation_reference'] = $v['confirmation_reference'];
			$export_data[$k]['lead_pax_name'] = $v['lead_pax_name'];
            $export_data[$k]['lead_pax_email'] = $v['lead_pax_email'];
            $export_data[$k]['lead_pax_phone_number'] = $v['lead_pax_phone_number'];
            $export_data[$k]['product_name'] = $v['product_name'];
            $export_data[$k]['grade_desc'] = $v['grade_desc'];
            $export_data[$k]['travel_date'] = $v['travel_date'];
           	$export_data[$k]['NO of adult_count'] = $v['adult_count'];
           	$export_data[$k]['NO of child_count'] = $v['child_count'];
           	$export_data[$k]['NO of youth_count'] = $v['youth_count'];
           	$export_data[$k]['NO of senior_count'] = $v['senior_count'];
           	$export_data[$k]['NO of infant_count'] = $v['infant_count'];
           	$export_data[$k]['Comm.Fare'] = $v['fare'];
           	$export_data[$k]['commission'] = $v['admin_commission'];
           	$export_data[$k]['tds'] = $v['net_commission_tds'];
           	$export_data[$k]['admin_net_fare'] = $v['admin_net_fare'];
           	$export_data[$k]['admin_markup'] = $v['admin_markup'];
           	$export_data[$k]['convinence_amount'] = $v['convinence_amount'];
           	$export_data[$k]['gst'] = $v['gst'];
           	$export_data[$k]['Discount'] = $v['discount'];
           	$export_data[$k]['grand_total'] = $v['grand_total'];
           	$export_data[$k]['Travel date'] = $v['journey_datetime'];
           	$export_data[$k]['booked_date'] = date('d-m-Y', strtotime($v['voucher_date']));
           	        		
           	
        }
			//debug($export_data[$k]['booked_date']);exit;
        if ($op == 'excel') { // excel export
        	//error_reporting(E_ALL);
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'APP reference',
                    'c1' => 'Lead Pax Name',
                    'd1' => 'Lead Pax Email',
                    'e1' => 'Lead Pax Phone',
                    'f1' => 'confirmation reference',
                    'g1' => 'product name',
                    'h1' => 'No of Adult',
                    'i1' => 'No of Child',
                    'j1' => 'No of youth',
                    'k1' => 'No of senior',
                    'l1' => 'No of infant',
                    'm1' => 'City',
					'n1' => 'Travel Date',
                    'o1' => 'Commission Fare',
                    'p1' => 'Commission',
                    'q1' => 'TDS',
                    'r1' => 'Admin NetFare',
                    's1' => 'Admin Markup',
                    't1' => 'GST',
                    'u1' => 'Discount',
                    'v1' => 'Total Fare',
                    'w1' =>'Convinence Fee',
                    'x1'=> 'Booked On',
                    
                );
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'app_reference',
                    'c' => 'lead_pax_name',
                    'd' => 'lead_pax_email',
                    'e' => 'lead_pax_phone_number',
                    'f' => 'confirmation_reference',
                    'g' => 'product_name',
                    'h' => 'NO of adult_count',
                    'i' => 'NO of child_count',
                    'j' => 'NO of youth_count',
                    'k' => 'NO of senior_count',
                    'l' => 'NO of infant_count',
                    'm' => 'grade_desc',
                    'n' => 'travel_date',
                    'o' => 'Comm.Fare',
                    'p' => 'commission',
                    'q' => 'tds',
                    'r' => 'admin_net_fare',
                    's' => 'admin_markup',
                  	't' => 'gst',
                    'u' => 'Discount',
                    'v' => 'grand_total',
                    'w' => 'convinence_amount',
                    'x' => 'booked_date',
                                        
                );
           
            $excel_sheet_properties = array(
                'title' => 'Confirmed_Booking_transferReport_' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'Confirmed_Booking_transferReport',
                'sheet_title' => 'Confirmed_Booking_transferReport'
            );

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties);
        }
        else if ($op == 'csv') { // excel export
            // echo "dd";exit;
            
               $headings = array('Sl. No.','APP reference','Lead Pax Name','Lead Pax Email','Lead Pax Phone','confirmation reference','product name','No of Adult','No of Child','No of youth','No of senior','No of infant','City','Travel Date','Commission Fare','Commission','TDS','Admin NetFare','Admin Markup','GST','Discount','Total Fare','Convinence Fee','Booked On'); 
           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
           $this->provab_csv->csv_export($headings,'Confirmed_Booking_transferReport', $export_data);
           
           
            
            
        }
        else if($op == 'pdf')
        {
            $this->load->library ( 'provab_pdf' );
            
            $create_pdf = new Provab_Pdf();
            $pdf_data['export_data']=$transfer_booking_data;
            // debug($pdf_data['export_data']);exit;
            $mail_template =$this->template->isolated_view('report/b2c_report_transferReport_pdf',$pdf_data);
            $this->provab_pdf->create_pdf($mail_template);
            


        } 
    }
    public function export_cancelled_booking_transfer_report($op = '') {
        $this->load->model('transferv1_model');
        $get_data = $this->input->get();
        $condition = array();
        //From-Date and To-Date
        $from_date = trim(@$get_data['created_datetime_from']);
        $to_date = trim(@$get_data['created_datetime_to']);

        $filter_data = $this->format_basic_search_filters('transfers');
        $condition = $filter_data['filter_condition'];

        //Unset the Status Filter
        if (valid_array($condition) == true) {
            foreach ($condition as $ck => $cv) {

                if ($cv[0] == 'BD.status') {
                    unset($condition[$ck]);
                }
            }
        }

        //Adding Confirmed Status Filter
        $condition[] = array('BD.status', '=', $this->db->escape('BOOKING_CANCELLED'));

        $transfer_booking_data = $this->transferv1_model->booking($condition, false, 0, 2000); //Maximum 500 Data Can be exported at time
        $transfer_booking_data = $this->booking_data_formatter->format_transferv1_booking_data($transfer_booking_data, 'b2b');
        $transfer_booking_data = $transfer_booking_data['data']['booking_details'];



        $export_data = array();
        //debug($transfer_booking_data);exit;
        $i=1;
        foreach ($transfer_booking_data as $k => $v) {
           if ($op == 'csv') {
             	$export_data[$k]['sr_no'] =$i;
             }
             $i++;
			$export_data[$k]['app_reference'] = $v['app_reference'];
			$export_data[$k]['confirmation_reference'] = $v['confirmation_reference'];
			$export_data[$k]['lead_pax_name'] = $v['lead_pax_name'];
            $export_data[$k]['lead_pax_email'] = $v['lead_pax_email'];
            $export_data[$k]['lead_pax_phone_number'] = $v['lead_pax_phone_number'];
            $export_data[$k]['product_name'] = $v['product_name'];
            $export_data[$k]['grade_desc'] = $v['grade_desc'];
            $export_data[$k]['travel_date'] = $v['travel_date'];
           	$export_data[$k]['NO of adult_count'] = $v['adult_count'];
           	$export_data[$k]['NO of child_count'] = $v['child_count'];
           	$export_data[$k]['NO of youth_count'] = $v['youth_count'];
           	$export_data[$k]['NO of senior_count'] = $v['senior_count'];
           	$export_data[$k]['NO of infant_count'] = $v['infant_count'];
           	$export_data[$k]['Comm.Fare'] = $v['fare'];
           	$export_data[$k]['commission'] = $v['admin_commission'];
           	$export_data[$k]['tds'] = $v['net_commission_tds'];
           	$export_data[$k]['admin_net_fare'] = $v['admin_net_fare'];
           	$export_data[$k]['admin_markup'] = $v['admin_markup'];
           	$export_data[$k]['convinence_amount'] = $v['convinence_amount'];
           	$export_data[$k]['gst'] = $v['gst'];
           	$export_data[$k]['Discount'] = $v['discount'];
           	$export_data[$k]['grand_total'] = $v['grand_total'];
           	$export_data[$k]['Travel date'] = $v['journey_datetime'];
           	$export_data[$k]['booked_date'] = date('d-m-Y', strtotime($v['voucher_date']));
           	        		
           	
        }
		//debug($export_data[$k]['booked_date']);exit;
        if ($op == 'excel') { // excel export
        	//error_reporting(E_ALL);
           $headings = array('a1' => 'Sl. No.',
                    'b1' => 'APP reference',
                    'c1' => 'Lead Pax Name',
                    'd1' => 'Lead Pax Email',
                    'e1' => 'Lead Pax Phone',
                    'f1' => 'confirmation reference',
                    'g1' => 'product name',
                    'h1' => 'No of Adult',
                    'i1' => 'No of Child',
                    'j1' => 'No of youth',
                    'k1' => 'No of senior',
                    'l1' => 'No of infant',
                    'm1' => 'City',
					'n1' => 'Travel Date',
                    'o1' => 'Commission Fare',
                    'p1' => 'Commission',
                    'q1' => 'TDS',
                    'r1' => 'Admin NetFare',
                    's1' => 'Admin Markup',
                    't1' => 'GST',
                    'u1' => 'Discount',
                    'v1' => 'Total Fare',
                    'w1' =>'Convinence Fee',
                    'x1'=> 'Booked On',
                    );
                // field names in data set 
                $fields = array('a' => '', // empty for sl. no.
                    'b' => 'app_reference',
                    'c' => 'lead_pax_name',
                    'd' => 'lead_pax_email',
                    'e' => 'lead_pax_phone_number',
                    'f' => 'confirmation_reference',
                    'g' => 'product_name',
                    'h' => 'NO of adult_count',
                    'i' => 'NO of child_count',
                    'j' => 'NO of youth_count',
                    'k' => 'NO of senior_count',
                    'l' => 'NO of infant_count',
                    'm' => 'grade_desc',
                    'n' => 'travel_date',
                    'o' => 'Comm.Fare',
                    'p' => 'commission',
                    'q' => 'tds',
                    'r' => 'admin_net_fare',
                    's' => 'admin_markup',
                  	't' => 'gst',
                    'u' => 'Discount',
                    'v' => 'grand_total',
                    'w' => 'convinence_amount',
                    'x' => 'booked_date',
                                        
                );
           
           
            $excel_sheet_properties = array(
                'title' => 'Cancelled_Booking_transferReport' . date('d-M-Y'),
                'creator' => 'Accentria Solutions',
                'description' => 'Cancelled_Booking_transferReport',
                'sheet_title' => 'Cancelled_Booking_transferReport'
            );

            $this->load->library('provab_excel'); // we need this provab_excel library to export excel.
            $this->provab_excel->excel_export($headings, $fields, $export_data, $excel_sheet_properties);
        }
        else if ($op == 'csv') { // excel export
            // echo "dd";exit;
            
               $headings = array('Sl. No.','APP reference','Lead Pax Name','Lead Pax Email','Lead Pax Phone','confirmation reference','product name','No of Adult','No of Child','No of youth','No of senior','No of infant','City','Travel Date','Commission Fare','Commission','TDS','Admin NetFare','Admin Markup','GST','Discount','Total Fare','Convinence Fee','Booked On'); 
           
           

            $this->load->library('provab_csv'); // we need this provab_excel library to export excel.
            // echo "dd";exit;
           $this->provab_csv->csv_export($headings,'Cancelled_Booking_transferReport', $export_data);
           
           
            
            
        }
        else if($op == 'pdf')
        {
            $this->load->library ( 'provab_pdf' );
            
            $create_pdf = new Provab_Pdf();
            $pdf_data['export_data']=$transfer_booking_data;
            // debug($pdf_data['export_data']);exit;
            $mail_template =$this->template->isolated_view('report/b2c_report_transferReport_pdf',$pdf_data);
            $this->provab_pdf->create_pdf($mail_template);
            


        } 
    }
	
	/**
	 * Balu A
	 */
	private function format_basic_search_filters()
	{
		$get_data = $this->input->get();
		if(valid_array($get_data) == true) {
			$filter_condition = array();
			//From-Date and To-Date
			$from_date = trim(@$get_data['from_date']);
			$to_date = trim(@$get_data['to_date']);
			//Auto swipe date
			if(empty($from_date) == false && empty($to_date) == false)
			{
				$valid_dates = auto_swipe_dates($from_date, $to_date);
				$from_date = $valid_dates['from_date'];
				$to_date = $valid_dates['to_date'];
			}
			if(empty($from_date) == false) {
				$filter_condition[] = array('DATE(BD.created_datetime)', '>=', '"'.date('Y-m-d', strtotime($from_date)).'"');
			}
			if(empty($to_date) == false) {
				$filter_condition[] = array('DATE(BD.created_datetime)', '<=', '"'.date('Y-m-d', strtotime($to_date)).'"');
			}
			//App reference
			if(isset($get_data['app_reference']) == true && empty($get_data['app_reference']) == false) {
				$filter_condition[] = array('BD.app_reference', '=', '"'.trim($get_data['app_reference']).'"');
			}
			//Booking-Status
			if(isset($get_data['filter_booking_status']) == true && $get_data['filter_booking_status'] == 'BOOKING_CONFIRMED') {
				//Confirmed Booking
				$filter_condition[] = array('BD.status', '=', '"BOOKING_CONFIRMED"');
			} elseif (isset($get_data['filter_booking_status']) == true && $get_data['filter_booking_status'] == 'BOOKING_PENDING') {
				//Pending Booking
				$filter_condition[] = array('BD.status', '=', '"BOOKING_PENDING"');	
			} elseif (isset($get_data['filter_booking_status']) == true && $get_data['filter_booking_status'] == 'BOOKING_CANCELLED') {
				//Cancelled Booking
				$filter_condition[] = array('BD.status', '=', '"BOOKING_CANCELLED"');
			}
			//Today's Booking Data
			if(isset($get_data['today_booking_data']) == true && empty($get_data['today_booking_data']) == false) {
				$filter_condition[] = array('DATE(BD.created_datetime)', '=', '"'.date('Y-m-d').'"');
			}
			//Last day Booking Data
			if(isset($get_data['last_day_booking_data']) == true && empty($get_data['last_day_booking_data']) == false) {
				$filter_condition[] = array('DATE(BD.created_datetime)', '=', '"'.trim($get_data['last_day_booking_data']).'"');
			}
			//Previous Booking Data: last 3 days, 7 days, 15 days, 1 month and 3 month
			if(isset($get_data['prev_booking_data']) == true && empty($get_data['prev_booking_data']) == false) {
				$filter_condition[] = array('DATE(BD.created_datetime)', '>=', '"'.trim($get_data['prev_booking_data']).'"');
			}
			if(isset($get_data['daily_sales_report']) == true && $get_data['daily_sales_report'] == ACTIVE) {
				$from_date = date('d-m-Y', strtotime('-1 day'));
				$to_date = date('d-m-Y');
				$filter_condition[] = array('DATE(BD.created_datetime)', '>=', '"'.date('Y-m-d', strtotime($from_date)).'"');
				$filter_condition[] = array('DATE(BD.created_datetime)', '<=', '"'.date('Y-m-d', strtotime($to_date)).'"');
			}
			return array('filter_condition' => $filter_condition, 'from_date' => $from_date, 'to_date' => $to_date);
		}
	}
	public function package_enquiries(){
		$this->load->model('Package_Model');
		$data ['enquiries'] = $this->Package_Model->gerEnquiryPackages ($this->entity_user_id);
		// debug($data);exit;
		$this->template->view ( 'report/package_enquiries', $data );
	}
}