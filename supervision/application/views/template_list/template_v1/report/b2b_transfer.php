
<!--<script src="<?php echo SYSTEM_RESOURCE_LIBRARY?>/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo SYSTEM_RESOURCE_LIBRARY?>/datatables/dataTables.bootstrap.min.js"></script> -->
<?=$GLOBALS['CI']->template->isolated_view('report/email_popup')?>

<?php
if (is_array($search_params)) {
	extract($search_params);
	//echo '<pre>'; print_r($search_params);die;
}
$_datepicker = array(array('created_datetime_from', PAST_DATE), array('created_datetime_to', PAST_DATE));
$this->current_page->set_datepicker($_datepicker);
$this->current_page->auto_adjust_datepicker(array(array('created_datetime_from', 'created_datetime_to')));
?>
<div class="bodyContent col-md-12">
	<div class="panel panel-default clearfix"><!-- PANEL WRAP START -->
		<div class="panel-heading"><!-- PANEL HEAD START -->
			<?=$GLOBALS['CI']->template->isolated_view('report/report_tab_b2b')?>
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
				
				<form method="GET" autocomplete="off" action="<?= base_url().'report/b2b_transfers_report?'?>">
					
					<div class="clearfix form-group">
						<div class="col-xs-4">
							<label>
							Agent
							</label>
							<select class="form-control" name="created_by_id">
								<option>All</option>
								<?=generate_options($agent_details, array(@$created_by_id))?>
							</select>
						</div>
						<div class="col-xs-4">
							<label>
							PNR
							</label>
							<input type="text" class="form-control" name="pnr" value="<?=@$pnr?>" placeholder="PNR">
						</div>
						<div class="col-xs-4">
							<label>
							Application Reference
							</label>
							<input type="text" class="form-control" name="app_reference" value="<?=@$app_reference?>" placeholder="Application Reference">
						</div>
						<!--<div class="col-xs-4">
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
						</div>-->
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
					<button type="reset" id="btn-reset" class="btn btn-warning">Reset</button>
					<a href="<?=base_url().'report/b2b_transfers_report?' ?>" id="clear-filter" class="btn btn-primary">Clear Filter</a>
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
                            <a href="<?php echo base_url(); ?>index.php/report/export_confirmed_booking_transfer_report_b2b/excel/all<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>">All Booking download</a>
                        </li>
                        <li>
                            <a href="" data-toggle="modal" data-target="#csvallmodal">All Booking email</a>
                        </li>
                        <li >
                            <a href="<?php echo base_url(); ?>index.php/report/export_confirmed_booking_transfer_report_b2b/excel<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>">Confirmed Booking</a>
                        </li>
                        <li >
                            <a href="" data-toggle="modal" data-target="#excelconfirmmodal">Confirmed Booking email</a>
                        </li>
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/report/export_cancelled_booking_transfer_report_b2b/excel<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>">Cancelled Booking</a>
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
                            <a href="<?php echo base_url(); ?>index.php/report/export_confirmed_booking_transfer_report_b2b/pdf/all<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>">All Booking download</a>
                        </li>
                        <li>
                            <a href="" data-toggle="modal" data-target="#csvallmodal">All Booking email</a>
                        </li>
                        <li >
                            <a href="<?php echo base_url(); ?>index.php/report/export_confirmed_booking_transfer_report_b2b/pdf<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>">Confirmed Booking download</a>
                        </li>
                        <li >
                            <a href="" data-toggle="modal" data-target="#pdfconfirmmodal">Confirmed Booking email</a>
                        </li>
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/report/export_cancelled_booking_transfer_report_b2b/pdf<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>" >Cancelled Booking download</a>
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
                            <a href="<?php echo base_url(); ?>index.php/report/export_confirmed_booking_transfer_report_b2b/csv/all<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>">All Booking download</a>
                        </li>
                        <li>
                            <a href="" data-toggle="modal" data-target="#csvallmodal">All Booking email</a>
                        </li>
                        <li >
                            <a href="<?php echo base_url(); ?>index.php/report/export_confirmed_booking_transfer_report_b2b/csv<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>">Confirmed Booking download</a>
                        </li>
                        <li >
                            <a href="" data-toggle="modal" data-target="#csvconfirmmodal">Confirmed Booking email</a>
                        </li>
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/report/export_cancelled_booking_transfer_report_b2b/csv<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>" >Cancelled Booking download</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" data-toggle="modal" data-target="#csvcancelmodal">Cancelled Booking email</a>
                        </li>
                    </ul>



                </div>
              <a href="<?php echo base_url(); ?>index.php/voucher/all_transfer_invoice_GST/<?php echo B2B_USER;?><?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>" class="btn btn-success">30 Invoice download</a>
            <?php } ?>
            <!-- EXCEL/PDF EXPORT ENDS -->
			
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
        <form action="<?=base_url()?>index.php/report/export_confirmed_booking_transfer_report_b2b_email/excel/all<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>" method="post">
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
        <form action="<?=base_url()?>index.php/report/export_confirmed_booking_transfer_report_b2b_email/pdf/all<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>" method="post">
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
        <form action="<?=base_url()?>index.php/report/export_confirmed_booking_transfer_report_b2b_email/csv/all<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>" method="post">
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
        <form action="<?=base_url()?>index.php/report/export_confirmed_booking_transfer_report_b2b_email/excel<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>" method="post">
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
        <form action="<?php echo base_url(); ?>index.php/report/export_cancelled_booking_transfer_report_b2b_email/excel<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>" method="post">
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
        <form action="<?=base_url()?>index.php/report/export_confirmed_booking_transfer_report_b2b_email/pdf<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>" method="post">
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
        <form action="<?php echo base_url(); ?>index.php/report/export_cancelled_booking_transfer_report_b2b_email/pdf<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>" method="post">
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
        <form action="<?=base_url()?>index.php/report/export_confirmed_booking_transfer_report_b2b_email/csv<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>" method="post">
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
        <form action="<?php echo base_url(); ?>index.php/report/export_cancelled_booking_transfer_report_b2b_email/csv<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>" method="post">
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
		<div class="clearfix table-responsive"><!-- PANEL BODY START -->
					<div class="pull-left">
						<?php echo $this->pagination->create_links();?> <span class="">Total <?php echo $total_rows ?> Bookings</span>
					</div> 
					
									<table class="table table-condensed table-bordered" id="b2b_report_airline_table">
						<thead>
							<tr>
								<th>Sno</th>
								<th>Application Reference</th>
								<th>Status</th>
								<th>Agency Name</th>
								<th>Lead Pax <br/>Details</th>
								<th>Activity Name</th>
								<th>Acitvity Location</th>	
								<th>BookedOn</th>
								<th>JourneyDate</th>
								<th>Confirmation Reference</th>
								<th>Comm.Fare</th>
								<th>Commission</th>
								<th>TDS</th>
								<th>Admin NetFare</th>
								<th>Admin Profit</th>
								<th>Admin<br/>Markup</th>
								<th>Agent<br/>Commission</th>
								<th>Agent<br/>TDS</th>
								<th>Agent <br/>Net Fare</th>
								<th>Agent<br/>Markup</th>
								<th>GST</th>
								<th>TotalFare</th>
								<th>Action</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th>Sno</th>
								<th>Application Reference</th>
								<th>Status</th>
								<th>Agency Name</th>
								<th>Lead Pax <br/>Details</th>
								<th>Activity Name</th>
								<th>Activity Location</th>								
								<th>BookedOn</th>
								<th>JourneyDate</th>
								<th>Confirmation Reference</th>
								<th>Comm.Fare</th>
								<th>Commission</th>
								<th>TDS</th>
								<th>Admin NetFare</th>
								<th>Admin Profit</th>
								<th>Admin<br/>Markup</th>
								<th>Agent <br/>Commission</th>
								<th>Agent<br/>TDS</th>
								<th>Agent <br/>Net Fare</th>
								<th>Agent<br/>Markup</th>
								<th>GST</th>
								<th>TotalFare</th>
								<th>Action</th>
							</tr>
						</tfoot><tbody>
						<?php
							//debug($table_data['booking_details']);exit;
							if(valid_array($table_data['booking_details']) == true) {
				        		$booking_details = $table_data['booking_details'];
								$segment_3 = $GLOBALS['CI']->uri->segment(3);
								$current_record = (empty($segment_3) ? 1 : $segment_3 + 1);
					        	foreach($booking_details as $parent_k => $parent_v) {
					        		extract($parent_v);
					        		//debug($parent_v);exit;
									$action = '';
									$cancellation_btn = '';
									$voucher_btn = '';								
									$booked_by = '';
									$cancel_btn ='';

									$voucher_btn = transfers_voucher($app_reference, $booking_source, $status, 'b2b');
									
									if($status=='BOOKING_CONFIRMED'){
										$cancel_btn = cancel_transfers_booking($app_reference, $booking_source, $status,'b2b');
									}
									
									$pdf_btn= transfers_pdf($app_reference, $booking_source, $status,'b2b');
									$email_btn = b2b_transfer_voucher_email($app_reference, $booking_source,$status,$email);
									$jrny_date = date('Y-m-d', strtotime($travel_date));
									$tdy_date = date ( 'Y-m-d' );
									$diff = get_date_difference($tdy_date,$jrny_date);
									
                                                                        
					        		$action .= $voucher_btn;
					        		$action .=  '<br />'.$pdf_btn;
					        		$action .=  '<br />'.$email_btn;
					        		$action .= transfer_GST_Invoice($app_reference, $booking_source, $status, 'b2b');
									if($diff > 0){
										$action .= $cancel_btn;
									}
									
									$action .=get_booking_pending_status($parent_v['app_reference'], $parent_v['booking_source'], $parent_v['status']);
									
									$action .= get_cancellation_details_button($parent_v['app_reference'], $parent_v['booking_source'], $parent_v['status']);
								?>
									<tr>
										<td><?=($current_record++)?></td>
										<td><?php echo $app_reference;?></td>
										<td><span class="<?php echo booking_status_label($status) ?>"><?php echo $status?></span></td>
										<td><?php echo $agency_name;?></td>
										<td>
										<?php echo $lead_pax_name. '<br/>'.
										  $email."<br/>".
										  $parent_v['customer_details'][0]['phone'];?>
										</td>
										<td><?php echo $product_name;?></td>
										<td><?php echo $Destination?></td>
										
										<td><?php echo date('d-m-Y', strtotime($voucher_date))?></td>
										<td><?php echo date('d-m-Y', strtotime($travel_date))?></td>
										<td><?=@$confirmation_reference?></td>
										<td><?php echo $fare?></td>
										<td><?php echo $net_commission?></td>
										<td><?php echo $net_commission_tds?></td>
										<td><?php echo $net_fare?></td>
										<td><?php echo $admin_commission?></td>
										<td><?php echo $admin_markup?></td>
										<td><?php echo $agent_commission?></td>
										<td><?php echo $agent_tds?></td>
										<td><?php echo $agent_buying_price?></td>
										<td><?php echo $agent_markup?></td>
										<td><?php echo $gst?></td>
										<td><?php echo $grand_total?></td>
										<td><div class="" role="group">
											<div class="dropdown">
												 <button class="dropbtn">
												 <i class="fa fa-ellipsis-v"></i>
												 </button>
												 <div class="dropdown-content">

												<?php echo $action; ?>

												 </div>
												</div>
											
											</div></td>
									</tr>
								<?php
								}
							} else {
								echo '<tr><td>---</td><td>---</td><td>---</td><td>---</td><td>---</td><td>---</td><td>---</td>
										   <td>---</td><td>---</td><td>---</td><td>---</td><td>---</td><td>---</td><td>---</td>
										   <td>---</td><td>---</td><td>---</td><td>---</td><td>---</td><td>---</td><td>---</td></tr>';
							}
						?>
						</tbody>
					</table>
					
				
		</div>
	</div>
</div>
<script>
$(document).ready(function() {
	

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
		      
					var _opp_url = app_base_url+'index.php/voucher/transfers/';
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
	
});
</script>
<?php
function b2b_transfer_voucher_email($app_reference, $booking_source,$status,$recipient_email)
{

  return '<a class="btn send_email_voucher" data-app-status="'.$status.'"   data-app-reference="'.$app_reference.'" data-booking-source="'.$booking_source.'"data-recipient_email="'.$recipient_email.'"><i class="far fa-envelope"></i> Email Voucher</a>';
}
function get_accomodation_cancellation($courseType, $refId)
{
	return '<a href="'.base_url().'index.php/booking/accomodation_cancellation?courseType='.$courseType.'&refId='.$refId.'" class="btn"><i class="far fa-exclamation-triangle"></i> Cancel</a>';
}
function update_booking_details($app_reference, $booking_source,$booking_status)
{
	
	return '<a class="btn update_flight_booking_details" data-app-reference="'.$app_reference.'" data-booking-source="'.$booking_source.'"data-booking-status="'.$booking_status.'">Update PNR Details</a>';
}
function flight_voucher_email($app_reference, $booking_source,$status,$recipient_email)
{

	return '<a class="btn send_email_voucher" data-app-status="'.$status.'"   data-app-reference="'.$app_reference.'" data-booking-source="'.$booking_source.'"data-recipient_email="'.$recipient_email.'"><i class="far fa-envelope"></i> Email Voucher</a>';
}
function get_cancellation_details_button($app_reference, $booking_source, $master_booking_status)
{
		//echo '<pre>'; 	print_r ($master_booking_status); die;
	
	if($master_booking_status == 'BOOKING_CANCELLED'){
		return '<a target="_blank" href="'.base_url().'index.php/transferv1/ticket_cancellation_details?app_reference='.$app_reference.'&booking_source='.$booking_source.'&status='.$master_booking_status.'" class="col-md-12 btn"><i class="far fa-info"></i> Cancellation Details</a>';
	}
	
}
function get_booking_pending_status($app_reference, $booking_source, $status)
{
	if($status == 'BOOKING_HOLD'){
		return '<a class="get_sightseeing_hb_status col-md-12 btn flight_u" id="pending_status_'.$app_reference.'" data-booking-source="'.$booking_source.'"
			data-app-reference="'.$app_reference.'" data-status="'.$status.'"><i class="far fa-info"></i> Update Supplier Info</a>';
	}
}
?>
<script>
$(document).ready(function() {

		$(".get_sightseeing_hb_status").on("click",function(e){
  		
	 	app_reference = $(this).data('app-reference');
        book_reference = $(this).data('booking-source');
        app_status = $(this).data('status');
        var _opp_url = app_base_url+'index.php/transferv1/get_pending_booking_status/';
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
</script>
