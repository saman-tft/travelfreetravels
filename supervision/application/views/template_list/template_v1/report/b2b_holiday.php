<?php
if (is_array ( $search_params )) {
  extract ( $search_params );
}

$_datepicker = array (
  array (
    'created_datetime_from',
    PAST_DATE 
    ),
  array (
    'created_datetime_to',
    PAST_DATE 
    ) 
  );
$this->current_page->set_datepicker ( $_datepicker );
$this->current_page->auto_adjust_datepicker ( array (
  array (
    'created_datetime_from',
    'created_datetime_to' 
    ) 
  ) );
  ?>
<!-- 
   <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="<?php echo SYSTEM_RESOURCE_LIBRARY?>/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo SYSTEM_RESOURCE_LIBRARY?>/datatables/dataTables.bootstrap.min.js"></script> 
   <script src="//code.jquery.com/jquery-1.12.3.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
  -->
  <script>
  $( function() {
    $( "#datepicker" ).datepicker();
    $( "#datepicker2" ).datepicker();
    // $("#datepicker2").datepicker().datepicker("setDate", new Date());
  } );
  </script>
  <?=$GLOBALS['CI']->template->isolated_view('report/email_popup')?>
  <div class="bodyContent col-md-12">
    <div class="panel panel-default clearfix">
      <div class="panel-heading"><!-- PANEL HEAD START -->
            <?= $GLOBALS['CI']->template->isolated_view('report/report_tab_b2b') ?>
        </div>
      <div class="panel-heading">
         Tours Report
      </div>
      <div class="panel-body">
        <div class="clearfix">
                <?php echo $GLOBALS['CI']->template->isolated_view('report/make_search_easy'); ?>

            </div>
        <h4>Search Panel</h4>
        <hr>
        <form action="<?=base_url().'index.php/report/b2b_package_report/'.$module_type?>" method="GET" autocomplete="off"> 
          <div class="clearfix form-group">
            <div class="col-xs-4">
              <label> Reservation Code </label> <input type="text"
              class="form-control" name="app_reference" value="<?=@$app_reference?>" placeholder="Reservation Code">
            </div>
            <div class="col-xs-4">
              <label> Phone </label> <input type="text"
              class="form-control mobile" name="phone" value="<?=@$phone?>"
              placeholder="Phone">
            </div>
            <div class="col-xs-4">
              <label> Email </label> <input type="text" class="form-control"
              name="email" value="<?=@$email?>" placeholder="Email">
            </div>
            <div class="col-xs-4">
            <?php 
            $status_options_cus=array(
              'BOOKING_CONFIRMED'=>'CONFIRMED',
              'CANCELLED'=>'CANCELLED',
              'CANCELLATION_IN_PROCESS'=>'CANCELLATION IN PROGRESS',
              );
            ?>
              <label> Status </label> <select class="form-control" name="status">
              <option>ALL</option>
              <?=generate_options($status_options_cus, array(@$status))?>
            </select>
          </div>
          <div class="col-xs-4">
            <label> Booked From Date </label> <input type="text" 
            id="datepicker" class="form-control" readonly=""
            name="created_datetime_from" value="<?=@$created_datetime_from?>"
            placeholder="Request Date">
          </div>
          <div class="col-xs-4">
            <label> Booked To Date </label> <input type="text" 
            id="datepicker2" readonly=""
            class="form-control disable-date-auto-update"
            name="created_datetime_to" value="<?=@$created_datetime_to?>"
            placeholder="Request Date">
          </div>
        </div>
        <div class="col-sm-12 well well-sm">
          <button type="submit" class="btn btn-primary">Search</button>
          <a href="<?=base_url().'report/holiday'?>" class="btn btn-warning">Reset</a>
          <!-- <button type="button" class="btn btn-primary pull-right" data-toggle="collapse" data-target="#screen_option">Screen Options</button> -->          
        </div>
      </form>
      <div id="screen_option" class="collapse"> 
        <form action="<?=base_url()?>report/report_column_setting/holiday/<?=$module_type?>" method="post">
          <style type="text/css">
            #screen_option{border: 1px solid #ccc; border-top: 0px;padding: 0px 10px;}
            .cust_list{padding-left: 0;}
            .cust_list li{display: inline-block; margin-right: 20px;}
          </style>
          <?php 
          // $GLOBALS['active_column_list']=$active_column_list;

         // debug($active_column_list);exit();
         /* function check_colume($column){
            if(in_array($column, array_column($GLOBALS['active_column_list'], 'column_name'))) {
              return 'checked="checked"';
            }
          }*/
          ?>
          
          </form>
        </div>
         <?php if($total_records > 0){ ?>
            <div class="clearfix"></div>
                <div class="dropdown col-xs-1">
                    <button class="btn btn-info dropdown-toggle" type="button" id="excel_imp_drop" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <i class="fa fa-download" aria-hidden="true"></i> Excel
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="excel_imp_drop">
                      <li >
                            <a href="<?php echo base_url(); ?>index.php/report/export_confirmed_booking_holiday_report_b2b/excel/all<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>">All Booking download</a>
                        </li>
                        <li >
                            <a href="" data-toggle="modal" data-target="#excelallmodal">All Booking email</a>
                        </li>
                        <li >
                            <a href="<?php echo base_url(); ?>index.php/report/export_confirmed_booking_holiday_report_b2b/excel<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>">Confirmed Booking download</a>
                        </li>
                        <li >
                            <a href="" data-toggle="modal" data-target="#excelconfirmmodal">Confirmed Booking email</a>
                        </li>
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/report/export_cancelled_booking_holiday_report_b2b/excel<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>">Cancelled Booking download</a>
                        </li>
                         <li>
                            <a href="javascript:void(0)" data-toggle="modal" data-target="#excelecancelmodal">Cancelled Booking email</a>
                        </li>
                    </ul>
                </div>
                <div class="dropdown col-xs-1">
                    <button class="btn btn-info dropdown-toggle" type="button" id="pdf_imp_drop" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <i class="fa fa-download" aria-hidden="true"></i> Pdf
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="pdf_imp_drop">
                      <li >
                            <a href="<?php echo base_url(); ?>index.php/report/export_confirmed_booking_holiday_report_b2b/pdf/all<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>">All Booking download</a>
                        </li>
                        <li>
                            <a href="" data-toggle="modal" data-target="#pdfallmodal">All Booking email</a>
                        </li>
                        <li >
                            <a href="<?php echo base_url(); ?>index.php/report/export_confirmed_booking_holiday_report_b2b/pdf<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>">Confirmed Booking download</a>
                        </li>
                        <li >
                            <a href="" data-toggle="modal" data-target="#pdfconfirmmodal">Confirmed Booking email</a>
                        </li>
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/report/export_cancelled_booking_holiday_report_b2b/pdf<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>" >Cancelled Booking download</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" data-toggle="modal" data-target="#pdfcancelmodal">Cancelled Booking email</a>
                        </li>
                    </ul>
                </div>
                <div class="dropdown col-xs-1">
                    <button class="btn btn-info dropdown-toggle" type="button" id="csv_imp_drop" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <i class="fa fa-download" aria-hidden="true"></i> CSV
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="csv_imp_drop">
                        <li >
                            <a href="<?php echo base_url(); ?>index.php/report/export_confirmed_booking_holiday_report_b2b/csv/all<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>">All Booking download</a>
                        </li>
                        <li>
                            <a href="" data-toggle="modal" data-target="#csvallmodal">All Booking email</a>
                        </li>
                        <li >
                            <a href="<?php echo base_url(); ?>index.php/report/export_confirmed_booking_holiday_report/csv<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>">Confirmed Booking download</a>
                        </li>
                        <li >
                            <a href="" data-toggle="modal" data-target="#csvconfirmmodal">Confirmed Booking email</a>
                        </li>
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/report/export_cancelled_booking_holiday_report/csv<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>" >Cancelled Booking download</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" data-toggle="modal" data-target="#csvcancelmodal">Cancelled Booking email</a>
                        </li>
                    </ul>



                </div>
            
            <?php } ?>
      </div>
      <!-- hotel -->
<div id="excelallmodal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Send Booking report in Email</h4>
      </div>
      <div class="modal-body">
        <form action="<?=base_url()?>index.php/report/export_confirmed_booking_holiday_report_b2b_email/excel/all<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>" method="post">
            <div class="form-group">
             <input type="text" class="form-control mfc" id="email" placeholder="Enter email" name="email" required="">
            <p>provide multiple email separated by(,)abc@gmail.com,xyz@gmail.com</p>
             <span id="errormsg"></span>
            </div>
            <button type="submit" class="btn btn-default flteml" style="background:#ef0303;border-radius:0px!important;border:1px solid #ef0303!important;" >Send</button>
      </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<div id="pdfallmodal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Send Booking report in Email</h4>
      </div>
      <div class="modal-body">
        <form action="<?=base_url()?>index.php/report/export_confirmed_booking_holiday_report_b2b_email/pdf/all<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>" method="post">
            <div class="form-group">
             <input type="text" class="form-control mfc" id="email" placeholder="Enter email" name="email" required="">
            <p>provide multiple email separated by(,)abc@gmail.com,xyz@gmail.com</p>
             <span id="errormsg"></span>
            </div>
            <button type="submit" class="btn btn-default flteml" style="background:#ef0303;border-radius:0px!important;border:1px solid #ef0303!important;" >Send</button>
      </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<div id="csvallmodal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Send Booking report in Email</h4>
      </div>
      <div class="modal-body">
        <form action="<?=base_url()?>index.php/report/export_confirmed_booking_holiday_report_b2b_email/csv/all<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>" method="post">
            <div class="form-group">
             <input type="text" class="form-control mfc" id="email" placeholder="Enter email" name="email" required="">
            <p>provide multiple email separated by(,)abc@gmail.com,xyz@gmail.com</p>
             <span id="errormsg"></span>
            </div>
            <button type="submit" class="btn btn-default flteml" style="background:#ef0303;border-radius:0px!important;border:1px solid #ef0303!important;" >Send</button>
      </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<div id="excelconfirmmodal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Send Booking report in Email</h4>
      </div>
      <div class="modal-body">
        <form action="<?=base_url()?>index.php/report/export_confirmed_booking_holiday_report_b2b_email/excel<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>" method="post">
            <div class="form-group">
             <input type="text" class="form-control mfc" id="email" placeholder="Enter email" name="email" required="">
            <p>provide multiple email separated by(,)abc@gmail.com,xyz@gmail.com</p>
             <span id="errormsg"></span>
            </div>
            <button type="submit" class="btn btn-default flteml" style="background:#ef0303;border-radius:0px!important;border:1px solid #ef0303!important;" >Send</button>
      </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<div id="excelecancelmodal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Send Booking report in Email</h4>
      </div>
      <div class="modal-body">
        <form action="<?php echo base_url(); ?>index.php/report/export_cancelled_booking_holiday_report_b2b_email/excel<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>" method="post">
            <div class="form-group">
             <input type="text" class="form-control mfc" placeholder="Enter email" name="email" required="">
            <p>provide multiple email separated by(,)abc@gmail.com,xyz@gmail.com</p>
             <span id="errormsg"></span>
            </div>
            <button type="submit" class="btn btn-default flteml" style="background:#ef0303;border-radius:0px!important;border:1px solid #ef0303!important;" >Send</button>
      </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<div id="pdfconfirmmodal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Send Booking report in Email</h4>
      </div>
      <div class="modal-body">
        <form action="<?=base_url()?>index.php/report/export_confirmed_booking_holiday_report_b2b_email/pdf<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>" method="post">
            <div class="form-group">
             <input type="text" class="form-control mfc" id="email" placeholder="Enter email" name="email" required="">
            <p>provide multiple email separated by(,)abc@gmail.com,xyz@gmail.com</p>
             <span id="errormsg"></span>
            </div>
            <button type="submit" class="btn btn-default flteml" style="background:#ef0303;border-radius:0px!important;border:1px solid #ef0303!important;" >Send</button>
      </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<div id="pdfcancelmodal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Send Booking report in Email</h4>
      </div>
      <div class="modal-body">
        <form action="<?php echo base_url(); ?>index.php/report/export_cancelled_booking_holiday_report_b2b_email/pdf<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>" method="post">
            <div class="form-group">
             <input type="text" class="form-control mfc" placeholder="Enter email" name="email" required="">
            <p>provide multiple email separated by(,)abc@gmail.com,xyz@gmail.com</p>
             <span id="errormsg"></span>
            </div>
            <button type="submit" class="btn btn-default flteml" style="background:#ef0303;border-radius:0px!important;border:1px solid #ef0303!important;" >Send</button>
      </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>


<div id="csvconfirmmodal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Send Booking report in Email</h4>
      </div>
      <div class="modal-body">
        <form action="<?=base_url()?>index.php/report/export_confirmed_booking_holiday_report_b2b_email/csv<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>" method="post">
            <div class="form-group">
             <input type="text" class="form-control mfc" id="email" placeholder="Enter email" name="email" required="">
            <p>provide multiple email separated by(,)abc@gmail.com,xyz@gmail.com</p>
             <span id="errormsg"></span>
            </div>
            <button type="submit" class="btn btn-default flteml" style="background:#ef0303;border-radius:0px!important;border:1px solid #ef0303!important;" >Send</button>
      </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<div id="csvcancelmodal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Send Booking report in Email</h4>
      </div>
      <div class="modal-body">
        <form action="<?php echo base_url(); ?>index.php/report/export_cancelled_booking_holiday_report_b2b_email/csv<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>" method="post">
            <div class="form-group">
             <input type="text" class="form-control mfc" placeholder="Enter email" name="email" required="">
            <p>provide multiple email separated by(,)abc@gmail.com,xyz@gmail.com</p>
             <span id="errormsg"></span>
            </div>
            <button type="submit" class="btn btn-default flteml" style="background:#ef0303;border-radius:0px!important;border:1px solid #ef0303!important;" >Send</button>
      </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<!-- end hotel  -->
      <div class="clearfix"></div>
      <?php 
      /*if (isset ( $table_data ) == true and valid_array ( $table_data ['booking_details'] ) == true) {
        $paxhtml ='';
        ?>
        <div class="row">
          <a href="<?php echo base_url(); ?>index.php/report/hotel/excel<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>">
            <button class="btn btn-primary btn-xs" type="button">Export to Excel</button>
          </a>
          <a href="<?php echo base_url(); ?>index.php/report/hotel/pdf<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>" target="_blank" >
            <button class="btn btn-primary btn-xs" type="button">PDF</button>
          </a>

          <button class="btn btn-primary btn-xs" type="button" onclick="window.print(); return true;">Print</button>

        </div>
        <?php 
      }*/ 
      ?>
      <div id="tableList"  class="clearfix table-responsive">
        <div class="pull-left clearfix"><?=$GLOBALS ['CI']->pagination->create_links () ?><p class="">Total <?=$total_records?> Bookings</p></div>
           <div class="clearfix"></div>
           <div class="clearfix col-md-12 nopad"><p>*The first passenger listed is the Lead Passenger/Pax.</p></span></div>
        
        <table class="table table-condensed table-bordered print-area" id="holiday_table">
          <thead>
            <tr>
              
                <th>Sno</th>
               
                
               
                <th>Reservation Code</th>
                
                <th>Status</th>
                
                <th>Package Name</th>
               
                <th>Email</th>
                
                <th>Phone</th>
                
                <th>Passanger Name(Last Name/First Name/Title)</th>
                
                <th>Departure Date</th>
                
                <th>Number Of Days</th>
                
                <th>Package Price</th>
                
                <th>Admin Markup</th>
                 <th>Agent Markup</th>
                
                <th>VAT</th>
                
                <!-- <th>Total<br/>Fare</th> -->
                
              <th>Grand Total</th>
              
              
                <th>BookedOn</th>
                
                <th>Billing Type</th>
                <th>Action</th>
                
            </tr>
          </thead>
          <tbody>
            <?php 
            if (isset ( $table_data ) == true) {
              $segment = $GLOBALS ['CI']->uri->segment ( 3 );
              if($segment>9)
              {
                  $segment=$segment+1;
              }
              
              
              
              
              
            
              $current_record = (empty ( $segment ) ? 1 : $segment);
              foreach ( $table_data as $key => $data ) 
              {
            // debug($data);exit;
                  
                  
                      //added:starts
                  
                      $aed_attributes=json_decode($data['booking_details']['aed_array']);
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
                                          
                                     
                                          
                                          

                               $total_fare=$base_fare-$discount_value+$aed_attributes->aed_convenience_fee;
                               
                               $package_price=$base_fare-$markup-$gst_value;
                               /*echo $base_fare.", ";
                               echo $markup.",";
                               echo $gst_value.",";
                               echo "<br/>";*/
                               
                               
                             
                              $total_fare=number_format($total_fare, 2);
                                $base_fare=number_format($base_fare, 2);
                  
                  
                  //added:ends

              $currency_obj = new Currency(array('module_type' => 'holiday', 'from' => $data['booking_details']['currency_code'], 'to' =>get_application_default_currency()));



              // DEBUG($currency_obj);exit();
              $book_attr = json_decode($data['booking_details']['attributes'],TRUE);
              
                $papassenger_details = json_decode($data['pax_details'],true);
                $e_condition ['user_id'] = $data['booking_details']['created_by_id'];
                //debug($data);exit();
                $user_data = $GLOBALS['CI']->user_model->get_user_uuid($e_condition);
                $postal_code = 'Data Not Available';
                if ($user_data['pin_code'] != "") {
                  $postal_code = $user_data['pin_code'];
                }
                $street_address = 'Data Not Available';
                if($user_data['address2'] != ""){
                  $street_address = $user_data['address2']; 
                }
                $state_name = 'Data Not Available';
                if($user_data['state_name'] != ""){
                  $state_name = $user_data['state_name']; 
                }
                $city_name = 'Data Not Available';
                if($user_data['city_name'] != ""){
                  $city_name = $user_data['city_name']; 
                }
                if($user_data['phone_code'] != ""){
                  $country_name = $GLOBALS ['CI']->user_model->get_country_name($user_data['phone_code']);  
                  $country_name1 = $country_name->name;
                }
                else{
                  $country_name1 = 'Data Not Available';
                }


                // debug( $country_name1);exit();
                $action = '';
                $email = '';
                $action .= '';
                if($module_type == 'b2c'){   
                  $action = '';           
                  $action .= '<a href="#" class="btn" data-toggle="modal" data-target="#myModal'.$key.'"><i class="fal fa-user-circle"></i> Pax Profile</a>';
                  $action .= '<a href="#" class="btn" data-toggle="modal" data-target="#package'.$key.'"><i class="fal fa-umbrella"></i> Tour</a>';
                  if ($data['booking_details']['status']=='BOOKING_CONFIRMED' || $data['booking_details']['status']=='CANCELLATION_IN_PROCESS') {
                    $action .= '<a class="btn" href="'.base_url().'index.php/tours/cancel_booking/'.$data['booking_details']['app_reference'].'" onclick="return confirm(\'Do you want to cancel your booking?\')"><i class="fal fa-ban"></i> Cancel</a>';
                  }
                  $action .= '<a class="btn" href="'.base_url().'index.php/voucher/b2b_holiday/'.$data['booking_details']['app_reference'].'" ><i class="fal fa-file"></i> Voucher</a>';
                  $action .= '<a class="btn" href="'.base_url().'index.php/voucher/b2b_holiday/'.$data['booking_details']['app_reference'].'/'.$data['booking_details']['status'].'/download_pdf" ><i class="fal fa-file"></i> Download PDF</a> </br>';
                  $action .= '<a class="btn" href="'.base_url().'index.php/voucher/b2b_holiday/'.$data['booking_details']['app_reference'].'/'.$data['booking_details']['status'].'/show_pdf" ><i class="fal fa-file"></i> View PDF</a></br>';

                  $action.='<a class="send_email_voucher btn" data-app-status="'.$data['booking_details']['status'].'"   data-app-reference="'.$data['booking_details']['app_reference'].'" data-booking-source="'.$booking_source.'"data-recipient_email="'.$user_attr['billing_email'].'" id=""><i class="far fa-envelope"></i> Email Voucher</a>';

                  $paxhtml = '<div class="modal fade" id="myModal'.$key.'" role="dialog">
                  <div class="modal-dialog">
                   <div class="modal-content">
                    <div class="modal-header">
                     <button type="button" class="close" data-dismiss="modal">&times;</button>
                     <h4 class="modal-title">Passenger Profile</h4>
                   </div>
                   <div class="modal-body">';
                    $user_attributes=$data['booking_details']['user_attributes'];
                    // print_r($user_attributes);
                    $user_attributes=json_decode($user_attributes,true);
                    $attributes=$data['booking_details']['attributes'];

                    $attributes=json_decode($attributes,true);

                    // debug($attributes['phone']);exit();
                    $departure_date = $data['enquiry_details']['departure_date'] ;
                    if (!$departure_date) {
                      $departure_date = $attributes['departure_date'];
                    }
                    $title = get_enum_list ( 'title', $data['pax_details'][0]['pax_title'] );


                    // debug($title);exit();
                    $name=$data['pax_details'][0]['pax_first_name'];
                    $mname=$data['pax_details'][0]['pax_middle_name'];
                    $lname=$data['pax_details'][0]['pax_last_name'];
                    $phone_no = $user_attributes['passenger_contact'];
                    $email = $user_attributes['billing_email'];    
                     $paxhtml .= '
                     <span>Title : &nbsp;</span><span>'.$title.'</span><br/>
                     <span>First Name : &nbsp;</span><span>'.$name .'</span><br/>
                     <span>Last Name : &nbsp;</span><span>'. $lname.'</span><br/>
                     <span>Mobile : &nbsp;</span><span>'.$phone_no.'</span><br/>                        
                     <span>Email : &nbsp;</span><span>'.$email.'</span><br/>
                      <span>Street Address : &nbsp;</span><span>'. $user_attributes["billing_address_1"].'</span><br/>
                     <span>Apartment / Suite : &nbsp;</span>'.$user_attributes["address2"].'<span></span><br/>
                        <span>City : &nbsp;</span><span>'.$user_attributes["billing_city"].'</span><br/>
                        <span>State / Province : &nbsp;'.$user_attributes["state"].'</span><br/>
                        <span>Country : &nbsp;'.$user_attributes["country_code"].'</span><br/>
                        <span>Postal Code : &nbsp;'.$user_attributes["billing_zipcode"].'</span><span></span><br/>
                        ';
                     $paxhtml .= '</div>
                     <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                  </div>
                </div>
              </div>';
              echo $paxhtml;

              $packagehtml = '<div class="modal fade" id="package'.$key.'" role="dialog">
              <div class="modal-dialog">
               <div class="modal-content">
                <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal">&times;</button>
                 <h4 class="modal-title">Tour Details</h4>
               </div>
               <div class="modal-body">';
                $duration = $data['tours_details']['duration'];
                if($duration==1)
                { 
                  $duration = ($duration).' N | '.($duration+1).' D';
                }
                else
                { 
                  $duration = ($duration).' N | '.($duration+1).' D';
                }
                $packagehtml .= '<span>Tour Name : &nbsp;</span><span>'.$data['tours_details']['package_name'].'</span><br/>
                <span>Country : &nbsp;</span><span>'.$data['tours_details']['country_name'].'</span><br/>                        
                <span>City : &nbsp;</span><span>'.implode(',',$data['tours_details']["city_name"]).'</span><br/>
                <span>Duration : &nbsp;</span><span>'.$duration.'</span><br/>
                ';
                $packagehtml .= '</div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>';
          echo $packagehtml;
                ?>
                <tr>
                    <td><?=($current_record ++)?></td>
                    
                    
                    
                    <td class=""><?=$data['booking_details']['app_reference']?></span></td>
                    
                    <td><span class="<?= booking_status_label ( $data['booking_details']['status'] )?>"><?=get_holiday_display_status( $data['booking_details']['status'] ) ?></span></td>

                    <td class=""><?=$data['tours_details']['package_name']?></span></td>
                    
                    <td class=""><?=$book_attr['billing_email']?></span></td>
                    
                    <td class=""><?=$book_attr['passenger_contact']?></span></td>
                    
                    <td>
                     <?php  //debug($data['pax_details']); ?>
                       <?php 
                      if($data['pax_details']){
                       foreach ($data['pax_details'] as  $value){ ?>

                        <?php

                         $title = get_enum_list('title',$value['pax_title']);
                         echo  $value['pax_last_name']." ".$value['pax_first_name']." ".$title."<br>"; ?>
                         
                      <?php }
                          
                         }
                          
                       ?> 
                    </td>



                    
                    <td><?=$attributes['departure_date']?></td>
                    
                    <td><?=$data['tours_details']['duration']?></td>
                   
                    <td><?php 

                    // debug($currency_obj);exit();
                   // $package_price=$data['booking_details']['basic_fare']-$data['booking_details']['markup']-$data['booking_details']['gst_value'];
                    //   echo isset($package_price)? number_format(get_converted_currency_value ( $currency_obj->force_currency_conversion ( $package_price ) ), 2):0;
                 
                     echo isset($package_price)? number_format($package_price , 2):0;
                 
                      ?>
                    </td>
                   
                   <!--  <td><?php 
                    // if($data['booking_details']['discount']){
                      // echo number_format($data['booking_details']['discount'],2);
                     // echo isset($data['booking_details']['discount'])? number_format(get_converted_currency_value ( $currency_obj->force_currency_conversion ( $data['booking_details']['discount'] ) ), 2):0;
                         // echo $discount_value;  
                    // }else{
                    //   echo "NA";
                    // }

                    ?></td> -->
                    <?php
                  
$admin_markup=$markup-$book_attr['agent_markup'];
                  
                    ?>
                    <td><?php 
                      //$markup = $data['booking_details']['markup'];                      
                     // echo isset($markup)? number_format(get_converted_currency_value ( $currency_obj->force_currency_conversion ( $markup ) ), 2):0;
                    if($admin_markup!="")
                    {
                        echo $admin_markup;
                    }
                  else {
                    echo"0.00";
                  }
                     ?></td>
                      <td><?php 
                      //$markup = $data['booking_details']['markup'];                      
                     // echo isset($markup)? number_format(get_converted_currency_value ( $currency_obj->force_currency_conversion ( $markup ) ), 2):0;
                      if($book_attr['agent_markup']!="")
                      {
                        echo $book_attr['agent_markup'];
                      }
                      else{
                      echo"0.00";
                      }
                    
                     ?></td>
                    
                  
                 
               <!--    <td><?php  echo isset( $aed_attributes->aed_convenience_fee)? number_format($aed_attributes->aed_convenience_fee,2):0; ?></td> -->
                  
                  
                    <td><?php 
                    //   $gst_value = $data['booking_details']['gst_value'];                      
                    //   echo isset($gst_value)? number_format(get_converted_currency_value ( $currency_obj->force_currency_conversion ( $gst_value ) ), 2):0;
echo $gst_value;
                     ?></td>
                    
        <!--             <td><?php
                      // $total = $data['booking_details']['basic_fare']-$data['booking_details']['discount'];
                     // $total = $data['booking_details']['basic_fare'];
                      
                     // echo isset($total)? number_format(get_converted_currency_value ( $currency_obj->force_currency_conversion ( $total ) ), 2):0;
//echo $base_fare;

                      // echo number_format($total,2);

                     ?></td> -->
                      <td><?php  echo isset( $total_fare)? number_format($total_fare,2):0; ?></td>
                    
                    
                    
                    <td><?=changeDateFormat($data['booking_details']['booked_datetime']) ?></td>
                    
                    <td>Online</td>
                    <td>

                      <div class="action_system" role="group">
                  
                  <div class="dropdown">
                     <button class="dropbtn">
                     <i class="fa fa-ellipsis-v"></i>
                     </button>
                     <div class="dropdown-content">

                     <?php echo $action; ?>

                     </div>
                    </div>
                  
                  </div> 

                     <!--  <div class="btn-group dropdown_hover" role="group">
                      <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="glyphicon glyphicon-chevron-right"></span>Actions
                      </button>
                      <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                        <?=$action?>
                      </div>
                    </div>  -->

                  </td>
                    
                </tr>
                <?php
              }
            }
          }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php 
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('js/jquery.dataTables.js'), 'defer' => 'screen');
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('js/dataTables.tableTools.js'), 'defer' => 'defer');
 ?>

<script>
  $(document).ready(function() {
    // $('#holiday_table').DataTable({
    //   "bLengthChange" : false,
    //   "bInfo":false , 
    // });
    $('.send_email_voucher').on('click', function(e) {

      $("#mail_voucher_modal").modal('show');
      $('#mail_voucher_error_message').empty();
      email = $(this).data('recipient_email');
      $("#voucher_recipient_email").val(email);
      app_reference = $(this).data('app-reference');
      book_reference = $(this).data('booking-source');
      app_status = $(this).data('app-status');
      $("#send_mail_btn").off('click').on('click',function(e){
        email = $("#voucher_recipient_email").val();
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        if(email != ''){ 
          if(!emailReg.test(email)){
            $('#mail_voucher_error_message').empty().text('Please Enter Correct Email Id');
            return false;    
          }         
          var _opp_url = app_base_url+'index.php/voucher/b2b_holiday/';
          _opp_url = _opp_url+app_reference+'/'+app_status+'/email_voucher/'+email;
          toastr.info('Please Wait!!!');
          $.get(_opp_url, function() {              
            toastr.info('Email sent  Successfully!!!');
            $("#mail_voucher_modal").modal('hide');
          });
        }else{
          $('#mail_voucher_error_message').empty().text('Please Enter Email ID');
        }       
      });  
    });
  });
</script>

