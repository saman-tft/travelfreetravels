<!--  <script src="<?php echo SYSTEM_RESOURCE_LIBRARY?>/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo SYSTEM_RESOURCE_LIBRARY?>/datatables/dataTables.bootstrap.min.js"></script> --> 
<?=$GLOBALS['CI']->template->isolated_view('report/email_popup')?>
<?php
if (is_array($search_params)) {
	extract($search_params);
}
$_datepicker = array(array('created_datetime_from', PAST_DATE), array('created_datetime_to', PAST_DATE));
$this->current_page->set_datepicker($_datepicker);
$this->current_page->auto_adjust_datepicker(array(array('created_datetime_from', 'created_datetime_to')));
?>
<div class="modal fade" id="pax_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">  
    <div class="modal-dialog" role="document" style="width: 880px;">    <div class="modal-content">    <div class="modal-header">        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-users"></i> 
                    Customer Details</h4> 
            </div>   
            <div class="modal-body">  
                <div id="customer_parameters">    	
                </div>   
            </div>  
        </div> 
    </div>
</div>
<div class="bodyContent col-md-12">
	<div class="panel panel-default clearfix"><!-- PANEL WRAP START -->
			<div class="panel-heading"><!-- PANEL HEAD START -->
				<?=$GLOBALS['CI']->template->isolated_view('report/report_tab_b2c')?>
		</div><!-- PANEL HEAD START -->
		<div class="panel-body">
				<div class="clearfix">
					<?php echo $GLOBALS['CI']->template->isolated_view('report/make_search_easy'); ?>
					
				</div>
				<hr>			
				<h4>Advanced Search Panel <button class="btn btn-primary btn-sm toggle-btn" data-toggle="collapse" data-target="#show-search">+
					</button> </h4>
				<hr>
			<div id="show-search" class="collapse">
				<form method="GET" autocomplete="off">
					<input type="hidden" name="created_by_id" value="<?=@$created_by_id?>" >
					<div class="clearfix form-group">
						<div class="col-xs-4">
							<label>
							Application Reference
							</label>
							<input type="text" class="form-control" name="app_reference" value="<?=@$app_reference?>" placeholder="Application Reference">
						</div>
						<!-- <div class="col-xs-4">
							<label>
							Phone
							</label>
							<input type="text" class="form-control numeric" name="phone" value="<?=@$phone?>" placeholder="Phone">
						</div>
						<div class="col-xs-4">
							<label>
							Email
							</label>
							<input type="text" class="form-control" name="email" value="<?=@$email?>" placeholder="Email">
						</div> -->
						<div class="col-xs-4">
							<label>
							Status
							</label>
							<select class="form-control" name="status">
								<option>All</option>
								<?=generate_options($status_options, array(@$status))?>
							</select>
						</div>
						<div class="col-xs-4">
							<label>
							Booked From Date
							</label>
							<input type="text" readonly id="created_datetime_from" class="form-control" name="created_datetime_from" value="<?=@$created_datetime_from?>" placeholder="Request Date">
						</div>
						<div class="col-xs-4">
							<label>
							Booked To Date
							</label>
							<input type="text" readonly id="created_datetime_to" class="form-control disable-date-auto-update" name="created_datetime_to" value="<?=@$created_datetime_to?>" placeholder="Request Date">
						</div>
					</div>
					<div class="col-sm-12 well well-sm">
					<button type="submit" class="btn btn-primary">Search</button> 
					<button type="reset" class="btn btn-warning">Reset</button>
					<a href="<?php echo base_url().'index.php/report/b2c_hotel_report?' ?>" id="clear-filter" class="btn btn-primary">Clear Filter</a>
					</div>
				</form>
			</div>
			<!-- EXCEL/PDF EXPORT STARTS -->
            <?php if($total_records > 0){ ?>
            <div class="clearfix"></div>
                <div class="dropdown col-xs-1">
                    <button class="btn btn-info dropdown-toggle" type="button" id="excel_imp_drop" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <i class="fa fa-download" aria-hidden="true"></i> Excel
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="excel_imp_drop">
                      <li >
                            <a href="<?php echo base_url(); ?>index.php/report/export_all_booking_hotel_report/excel<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>">All Booking download</a>
                        </li>
                        <li >
                            <a href="" data-toggle="modal" data-target="#excelallmodal">All Booking email</a>
                        </li>
                        <li >
                            <a href="<?php echo base_url(); ?>index.php/report/export_confirmed_booking_hotel_report/excel<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>">Confirmed Booking download</a>
                        </li>
                        <li >
                            <a href="" data-toggle="modal" data-target="#excelconfirmmodal">Confirmed Booking email</a>
                        </li>
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/report/export_cancelled_booking_hotel_report/excel<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>" >Cancelled Booking download</a>
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
                            <a href="<?php echo base_url(); ?>index.php/report/export_all_booking_hotel_report/pdf<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>">All Booking download</a>
                        </li>
                        <li>
                            <a href="" data-toggle="modal" data-target="#pdfallmodal">All Booking email</a>
                        </li>
                        <li >
                            <a href="<?php echo base_url(); ?>index.php/report/export_confirmed_booking_hotel_report/pdf<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>">Confirmed Booking download</a>
                        </li>
                        <li >
                            <a href="" data-toggle="modal" data-target="#pdfconfirmmodal">Confirmed Booking email</a>
                        </li>
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/report/export_cancelled_booking_hotel_report/pdf<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>" >Cancelled Booking download</a>
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
                            <a href="<?php echo base_url(); ?>index.php/report/export_all_booking_hotel_report/csv<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>">All Booking download</a>
                        </li>
                        <li>
                            <a href="" data-toggle="modal" data-target="#csvallmodal">All Booking email</a>
                        </li>
                        <li >
                            <a href="<?php echo base_url(); ?>index.php/report/export_confirmed_booking_hotel_report/csv<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>">Confirmed Booking download</a>
                        </li>
                        <li >
                            <a href="" data-toggle="modal" data-target="#csvconfirmmodal">Confirmed Booking email</a>
                        </li>
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/report/export_cancelled_booking_hotel_report/csv<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>" >Cancelled Booking download</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" data-toggle="modal" data-target="#csvcancelmodal">Cancelled Booking email</a>
                        </li>
                    </ul>                 

                </div>
                <a href="<?php echo base_url(); ?>index.php/voucher/all_hotel_invoice_GST/<?php echo B2C_USER;?><?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>" class="btn btn-success">30 Invoice download</a>
            
            <?php } ?>
            <!-- EXCEL/PDF EXPORT ENDS -->
		</div>
		
		<div class="clearfix" style="overflow: auto"><!-- PANEL BODY START -->
			<?php echo get_table($table_data, $total_rows);?>
		</div>
	</div>
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
        <form action="<?=base_url()?>index.php/report/export_all_booking_hotel_report_email/excel<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>" method="post">
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
        <form action="<?=base_url()?>index.php/report/export_all_booking_hotel_report_email/pdf<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>" method="post">
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
        <form action="<?=base_url()?>index.php/report/export_all_booking_hotel_report_email/csv<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>" method="post">
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
        <form action="<?=base_url()?>index.php/report/export_confirmed_booking_hotel_report_email/excel<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>" method="post">
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
        <form action="<?php echo base_url(); ?>index.php/report/export_cancelled_booking_hotel_report_email/excel<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>" method="post">
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
        <form action="<?=base_url()?>index.php/report/export_confirmed_booking_hotel_report_email/pdf<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>" method="post">
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
        <form action="<?php echo base_url(); ?>index.php/report/export_cancelled_booking_hotel_report_email/pdf<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>" method="post">
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
        <form action="<?=base_url()?>index.php/report/export_confirmed_booking_hotel_report_email/csv<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>" method="post">
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
        <form action="<?php echo base_url(); ?>index.php/report/export_cancelled_booking_hotel_report_email/csv<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>" method="post">
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


<?php
function get_table($table_data, $total_rows)
{
	$pagination = '<div class="pull-left">'.$GLOBALS['CI']->pagination->create_links().' <span class="">Total '.$total_rows.' Bookings</span></div>';
	$report_data = '';
	$report_data .= '<div id="tableList" class="table-responsive clearfix">';
	$report_data .= $pagination;
	
	$report_data .= '<table class="table table-condensed table-bordered" id="b2c_report_hotel_table">
		<thead>
		<tr>
			<th>Sno</th>
			<th>Reference No</th>
			<th>Confirmation/<br/>Reference</th>
			<th>Lead Pax details</th>
			<th>Hotel Name</th>
			<th>No. of rooms<br/>(Adult + Child)</th>
			<th>City</th>
			<th>CheckIn/<br/>CheckOut</th>
			<th>Comm.Fare</th>
			<th>TDS</th>
			<th>Admin <br/>Markup+Markup GST</th>
			<th>Convn.Fee</th>
			<th>GST</th>
			<th>Discount</th>
			<th>Grand Total</th>	
			<th>Booked On</th>
			<th>Status</th>
			<th>Payment Status</th>
      <th>Payment id</th>
			<th>Action</th>
		</tr>
		</thead><tfoot>
		<tr>
			<th>Sno</th>
			<th>Reference No</th>
			<th>Confirmation/<br/>Reference</th>
			<th>Lead Pax details</th>
			<th>Hotel Name</th>
			<th>No. of rooms<br/>(Adult + Child)</th>
			<th>City</th>
			<th>CheckIn/<br/>CheckOut</th>
			<th>Comm.Fare</th>
			<th>TDS</th>
			<th>Admin <br/>Markup+Markup GST</th>
			<th>Convn.Fee</th>
			<th>GST</th>
			<th>Discount</th>
			<th>Grand Total</th>	
			<th>Booked On</th>
			<th>Status</th>
			<th>Payment Status</th>
      <th>Payment id</th>
			<th>Action</th>
		</tr>
		</tfoot><tbody>';
		
		if (isset($table_data) == true and valid_array($table_data['booking_details']) == true) {
			$segment_3 = $GLOBALS['CI']->uri->segment(3);
			$current_record = (empty($segment_3) ? 1 : $segment_3);
			$booking_details = $table_data['booking_details'];
			// debug($booking_details); exit;
		    foreach($booking_details as $parent_k => $parent_v) { 
		    	
		        	extract($parent_v);
              // debug($parent_v);exit;
				$action = '';
				$email='';
				$tdy_date = date ( 'Y-m-d' );
				$diff = get_date_difference($tdy_date,$hotel_check_in);
				$customer_details = customer_details($app_reference, $booking_source, $status);
				$action .= hotel_voucher($app_reference, $booking_source, $status,'b2c');
				$action.='<br/>';
				$action .= hotel_pdf($app_reference, $booking_source, $status);
				$action.='<br/>';
				$action .= hotel_voucher_email($app_reference, $booking_source,$status,$parent_v['email']);
				$action .= '<br />' . $customer_details;
                                $action .= hotel_GST_Invoice($app_reference, $booking_source, $status, 'b2c');
				$action.='<br/>';
		    	if($status == 'BOOKING_CONFIRMED' && $diff > 0) {
					$action .= cancel_hotel_booking($app_reference, $booking_source, $status);
				}
				$action .=get_booking_pending_status($parent_v['app_reference'], $parent_v['booking_source'], $parent_v['status']);
				$
				$email = hotel_email_voucher($app_reference, $booking_source, $status);
			if(!empty($payment_details))
            {
              $payment_id1=json_decode($payment_details[0]['response_params'],true);
              $payment_id=$payment_id1['razorpay_payment_id'];
            }
            else
            {
               $payment_id="";
            }
		$report_data .= '<tr>
					<td>'.($current_record++).'</td>
					<td>'.$app_reference.'</td>
					<td class="">'.$confirmation_reference.'/<br/>'.$booking_reference.'</span></td>
					<td>'.$lead_pax_name. '<br/>'.
						  $lead_pax_email.'<br/>'.
						  $lead_pax_phone_number.'
					</td>
					<td>'.$hotel_name.'</td>
					<td>'.$total_rooms.'<br/>('.$adult_count.'+'.$child_count.')</td>
					<td>'.$hotel_location.'</td>
					<td>'.date('d-m-Y', strtotime($hotel_check_in)).'/<br/>'.date('d-m-Y', strtotime($hotel_check_out)).'</td>
					<td>'.$currency.''.($fare).'</td>
					<td>'.$currency.''.($itinerary_details[0]['TDS']).'</td>
					<td>'.$currency.''.$admin_markup.'</td>
					<td>'.$currency.''.$convinence_amount.'</td>
					<td>'.$currency.''.$gst.'</td>
					<td>'.$currency.''.$discount.'</td>
					<td>'.$currency.''.$grand_total.'</td>
					<td>'.date('d-m-Y', strtotime($voucher_date)).'</td>
					<td><span class="'.booking_status_label($status).'">'.$status.'</span></td>
          <td><span class="'.booking_status_label($payment_details[0]['status']).'">'.$payment_details[0]['status'].'</span></td>
					<td>'.$payment_id.'</td>

					<td><div class="" role="group">
					<div class="dropdown">
						 <button class="dropbtn">
							 <i class="fa fa-ellipsis-v"></i>
							 </button>
							 <div class="dropdown-content">

							'.$action.'

							 </div>
						</div>
					
					</div></td>
				</tr>';
			}
		} else {
			$report_data .= '<tr><td>---</td><td>---</td><td>---</td><td>---</td><td>---</td><td>---</td>
								 <td>---</td><td>---</td><td>---</td><td>---</td><td>---</td><td>---</td>
								<td>---</td><td>---</td><td>---</td><td>---</td></tr>';
		}
	$report_data .= '</tbody></table>
			</div>';
	return $report_data;
}
function get_accomodation_cancellation($courseType, $refId)
{
	return '<a href="'.base_url().'index.php/booking/accomodation_cancellation?courseType='.$courseType.'&refId='.$refId.'" class="col-md-12 btn btn-sm btn-danger "><i class="far fa-exclamation-triangle"></i> Cancel</a>';
}
function hotel_voucher_email($app_reference, $booking_source,$status,$recipient_email)
{

	return '<a class="btn send_email_voucher" data-app-status="'.$status.'"   data-app-reference="'.$app_reference.'" data-booking-source="'.$booking_source.'"data-recipient_email="'.$recipient_email.'"><i class="far fa-envelope"></i> Email Voucher</a>';
}
function get_booking_pending_status($app_reference, $booking_source, $status)
{
	if($status == 'BOOKING_HOLD'){
		return '<a class="get_hotel_hb_status col-md-12 btn flight_u" id="pending_status_'.$app_reference.'" data-booking-source="'.$booking_source.'"
			data-app-reference="'.$app_reference.'" data-status="'.$status.'"><i class="far fa-info"></i> Update Supplier Info</a>';
	}
}
function customer_details($app_reference, $booking_source = '', $status = '') {
        return '<a  target="_blank" data-app-reference="' . $app_reference . '" data-booking-status="' . $status . '" data-booking-source="' . $booking_source . '" class="btn flight_u customer_details"><i class="fa fa-file"></i>Pax profile</a>';
}
?>
<script>
$(document).ready(function() {
    // $('#b2c_report_hotel_table').DataTable({
    //     // Disable initial sort 
    //     "aaSorting": []
    // });

	    //send the email voucher
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
					var _opp_url = app_base_url+'index.php/voucher/hotel/';
					_opp_url = _opp_url+app_reference+'/'+book_reference+'/'+app_status+'/email_voucher/'+email;
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
	$(".get_hotel_hb_status").on("click",function(e){
  		
		 	app_reference = $(this).data('app-reference');
        book_reference = $(this).data('booking-source');
        app_status = $(this).data('status');
        var _opp_url = app_base_url+'index.php/hotel/get_pending_booking_status/';
		_opp_url = _opp_url+app_reference+'/'+book_reference+'/'+app_status;
		toastr.info('Please Wait!!!');
		$.get(_opp_url, function(res) {
			if(res==1){
				toastr.info('Status Updated Successfully!!!');	
				location.reload(); 
			}else{
				toastr.info('Status not updated');
			}
			
			$("#mail_voucher_modal").modal('hide');
		});
  });
});
$(document).on('click', '.customer_details', function (e) {
            
            e.preventDefault();
            //$(this).attr('disabled', 'disabled');//disable button
            var app_ref = $(this).data('app-reference');
            var booking_src = $(this).data('booking-source');
            var status = $(this).data('booking-status');
            var module = 'hotel';

            jQuery.ajax({
                type: "GET",
                url: app_base_url + 'index.php/report/get_customer_details/' + app_ref + '/' + booking_src + '/' + status + '/' + module + '/',
                dataType: 'json',
                success: function (res) {

                    $('#customer_parameters').html(res.data);
                    $('#pax_modal').modal('show');
                }
            });
        });
</script>