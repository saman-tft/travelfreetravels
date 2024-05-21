
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
		
		</div><!-- PANEL HEAD START -->
		<div class="panel-body">
			<div class="clearfix">
				<?php echo $GLOBALS['CI']->template->isolated_view('report/make_search_easy'); ?>
				
			</div>
			<hr>			
			<h4>Advanced Search Panel<button class="btn btn-primary btn-sm toggle-btn" data-toggle="collapse" data-target="#show-search">+
				</button> </h4>
			<hr>
			<div id="show-search" class="collapse">

				<form method="GET" autocomplete="off" action="<?= base_url().'report/activities_crs?'?>">
					
					<div class="clearfix form-group">
						<!--<div class="col-xs-4">
							<label>
							Agent
							</label>
							<select class="form-control" name="created_by_id">
								<option>All</option>
								<?=generate_options($agent_details, array(@$created_by_id))?>
							</select>
						</div>-->
						<div class="col-xs-4">
							<label>
							Application Reference
							</label>
							<input type="text" class="form-control" name="app_reference" value="<?=@$app_reference?>" placeholder="Application Reference">
						</div>
						<div class="col-xs-4">
							<label>
							Application Type
							</label>
							<select class="form-control" name="application_type">
							<option>All</option>
							<option value="Crs">CRS</option>
							<option value="Api">API</option>
							</select>
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
					<a href="<?=base_url().'report/activities_crs?' ?>" id="clear-filter" class="btn btn-danger">Clear Filter</a>
					</div>
					
				</form>
				
				<!-- <form method="GET" role="search" class="navbar-form" id="auto_suggest_booking_id_form">
				<div class="form-group">
				<input type="hidden" id="module" value="<?=PROVAB_SIGHTSEEN_SOURCE_CRS?>">
				<input type="text" autocomplete="off" data-search_category="search_query" placeholder="AppReference/PNR" class="form-control auto_suggest_booking_id ui-autocomplete-input" id="auto_suggest_booking_id" name="filter_report_data" value="<?=@$_GET['filter_report_data']?>">
				</div>
				<button title="Search" class="btn btn-default" type="submit"><i class="far fa-search"></i></button>
				<a title="Clear Search" class="btn btn-default" href="<?=base_url().'index.php/report/activities_crs'?>"><i class="far fa-history"></i></a>
		</form> -->
			</div>
			<!-- EXCEL/PDF EXPORT STARTS -->
            <?php if($total_records > 0){ ?>
            <div class="clearfix"></div>
                <div class="dropdown col-xs-3">
                    <button class="btn btn-info dropdown-toggle" type="button" id="excel_imp_drop" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <i class="fa fa-download" aria-hidden="true"></i> Excel
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="excel_imp_drop">
                        <li >
                            <a href="<?php echo base_url(); ?>index.php/report/export_confirmed_booking_activity_crs_report_b2b/excel<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>">Confirmed Booking</a>
                        </li>
                        <li>
                            <a href="<?php echo base_url(); ?>index.php/report/export_cancelled_booking_activities_report_b2b/excel<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>">Cancelled Booking</a>
                        </li>
                    </ul>
                </div>
            
            <?php } ?>
            <!-- EXCEL/PDF EXPORT ENDS -->
			
		</div>
		
		<div class="clearfix table-responsive"><!-- PANEL BODY START -->
					<div class="pull-left">
						<?php echo $this->pagination->create_links();?> <span class="">Total <?php echo $total_rows ?> Bookings</span>
					</div> 
					
					<table class="table table-condensed table-bordered example3 b-report" id="b2c_report_airline_table">
						<thead>					
						<tr>
							<th>Sno</th>
							<th>Action</th>
							<th>Reference No</th>
							<th>Status</th>
							<th>Agency Name</th>
							<th>Lead Pax Details</th>
							<th>Package Price</th>
							<th>Admin Markup</th>
	                        <th>Markup(Agent+Instant)</th>
	                        <th>Convenience Fees</th>
	                        <th>VAT Amount</th>
	                        <th>Total<br/>Fare</th>
							<!-- <th>Customer paid <br/>amount</th> -->
	                        <th>Currency</th>
	                        <th>Payment Mode</th>
							<th>BookedOn</th>
							<th>Travel<br/> date</th>
							<th>Staff Emulate</th>
							<th>Cancelled Date</th>
							 <th>Customer<br/>Remarks</th>
							 <th>Booked<br/>By</th>
						</tr>
						</thead>
						<tfoot>
						<tr>
							<th>Sno</th>
							<th>Action</th>
							<th>Reference No</th>
							<th>Status</th>
							<th>Agency Name</th>
							<th>Lead Pax Details</th>
							<th>Package Price</th>
							<th>Admin Markup</th>
	                        <th>Markup(Agent+Instant)</th>
	                        <th>Convenience Fees</th>
	                        <th>VAT Amount</th>
	                        <th>Total<br/>Fare</th>
							<!-- <th>Customer paid <br/>amount</th> -->
	                        <th>Currency</th>
	                        <th>Payment Mode</th>
							<th>BookedOn</th>
							<th>Travel<br/> date</th>
							<th>Staff Emulate</th>
							<th>Cancelled Date</th>
							<th>Customer<br/>Remarks</th>
							 <th>Booked<br/>By</th>
						</tr>
						</thead>
						<tfoot><tbody>
						<?php
						//	debug($table_data['booking_details']);exit;
							if(valid_array($table_data['booking_details']) == true) {
				        		$booking_details = $table_data['booking_details'];
				        		//$CI =& get_instance();$CI->toExcel($booking_details);

								$segment_3 = $GLOBALS['CI']->uri->segment(3);
								$current_record = (empty($segment_3) ? 1 : $segment_3);
					        	foreach($booking_details as $parent_k => $parent_v) {
					        		// debug($parent_v);exit;
					        		extract($parent_v);
					        		  if($status == 'CANCELLED' || $status==""){
					                        $status = 'CANCELLED';
					                      }
					        	//	debug($status);exit;
									$action = '';
									$cancellation_btn = '';
									$voucher_btn = '';
									$invoice_btn = '';
									$vat_invoice_btn = '';
									$view_details='';
									//$update_booking_details_btn = update_booking_details($app_reference, $booking_source);
									$booked_by = '';
									
									//Status Update Button
									/*if (in_array($status, array('BOOKING_CONFIRMED')) == false) {
										switch ($booking_source) {
											case PROVAB_FLIGHT_BOOKING_SOURCE :
												$status_update_btn = '<button class="btn btn-success btn-sm update-source-status" data-app-reference="'.$app_reference.'"><i class="fa fa-database"></i> Update Status</button>';
												break;
										}
									}*/

									$voucher_btn = activity_voucher($app_reference, $booking_source, $status, 'b2b');
									$invoice_btn = activity_invoice($app_reference, $booking_source, $status, 'b2b');
									$vat_invoice_btn = activity_vat_invoice($app_reference, $booking_source, $status, 'b2b');
									$view_details = activity_booking_details($app_reference, $booking_source, $status, 'b2b');

									//$invoice = flight_invoice($app_reference, $booking_source, $status);
									$cancel_btn = holiday_cancel($app_reference, $booking_source, $status);
									$pdf_btn= activity_pdf($app_reference, $booking_source, $status, 'b2b');
									$email_btn = flight_voucher_email($app_reference, $booking_source,$status,$email);
									
									$jrny_date = date('Y-m-d', strtotime($date_of_travel));
									$tdy_date = date ( 'Y-m-d' );
									$diff = get_date_difference($tdy_date,$jrny_date);
					        		$action .= $voucher_btn;
					        		$action .= '<br />'.$invoice_btn;
					        		$action .= '<br />'.$vat_invoice_btn;
					        		$action .=  '<br />'.$pdf_btn;
					        		$action .=  '<br />'.$email_btn;
					        		$action .=  '<br />'.$view_details;
									if($diff > 0 && $status != 'CANCELLED'){
										$action .= $cancel_btn;
									}
									//$message=check_message_test($app_reference,'activities_crs');
				                      if($message)
				                      {
				                       // $action .=view_mesage_history($message);
				                      }
									// $action .= $invoice;
									// if ($status != 'BOOKING_CANCELLED') {
											
									// 	if(strtotime('now') < strtotime($parent_v['date_of_travel'])){
									// 		$update_booking_details_btn = update_booking_details($app_reference, $booking_source,$status);
									// 		$action .= '<br />'.$update_booking_details_btn;
									// 	}
											
									// }	
									// error_reporting(E_ALL);
									// $action .= get_cancellation_details_button($parent_v['app_reference'], $parent_v['booking_source'], $parent_v['status'], $parent_v['booking_transaction_details']);								
								?>
									<tr>
										<td><?= ($current_record++) ?></td>
										<td>
											<div class="dropdown2" role="group">
												<div class="dropdown slct_tbl pull-left sideicbb">
												<i class="fa fa-ellipsis-v"></i>  
													<ul class="dropdown-menu sidedis" style="display: none;">
														<li>
														<?php echo $action; ?>
														</li>
													</ul>
												</div>
											</div>

											
										<?php 
					                      if($status == 'CANCELLED' || $status==" "){
					                        $status = 'BOOKING_CANCELLED';
					                      }
					                      ?>
										</td>
										<td><?php echo $app_reference;?></td>
										<td><span class="<?php echo booking_status_label($status) ?>"><?php echo $status?></span></td>
										<td><?php echo $parent_v['agent_agency_name'];?></td>
										<td>
										<?php echo $parent_v['itinerary_details'][0]['first_name'].' '.$parent_v['itinerary_details'][0]['last_name']. '<br/>'.
										  $email."<br/>".
										  $phone;?>
										</td>
										<td><?php echo $parent_v['basic_fare'];?></td>
										<td><?php echo $parent_v['admin_markup'];?></td>
										<td><?php echo $parent_v['agent_markup'];?></td>
										<td><?php echo $parent_v['convenience_fee'];?></td>
										<td><?php echo $parent_v['gst_percentage'];?></td>
										<td><?php echo $parent_v['total_fare']-$parent_v['reward_amount'];?></td>
										<!-- <td><?php echo $parent_v['agent_buying_price'];?></td> -->
										<td><?php echo $parent_v['currency'];?></td>
										<td><?php echo $parent_v['payment_mode'];?></td>
										
										<!-- <td><?=$pnr?></td>
										<td><?php echo $from_loc?></td>
										<td><?php echo $to_loc?></td>
										<td><?php echo $trip_type_label?></td> -->
										<td><?php echo date('d-M-y', strtotime($created_datetime))?></td>
										<td><?php echo date('d-M-y', strtotime($date_of_travel))?></td>
										<td><?=$parent_v['emulate_user_name']?></td>
										 <td><?php  
					                       //debug($final_cancel_date);exit;
					                       if(($final_cancel_date == '0000-00-00 00:00:00')||($final_cancel_date == ' ')||($final_cancel_date == '0')){
					                        $cancel_date = "-";
					                       }else{
					                        $cancel_date = date('d-M-y', strtotime($final_cancel_date));
					                       }
					                        echo $cancel_date;
					                     ?></td>
					                       <td><?php echo $remarks?></td>
					                       <?php if($emulate_booking == 1){ ?>
											<td><a href="<?php echo base_url().'index.php/report/staff_details/'.$emulate_user ?>" data-toggle="modal" data-target="#view_modal" >Click here</a></td>
										<?php }else if($parent_v['created_by_id'] != $this->entity_user_id){ ?>
											<td><a href="<?php echo base_url().'index.php/report/staff_details/'.$parent_v['created_by_id'] ?>" data-toggle="modal" data-target="#view_modal" >Click here</a></td>
										<?php }else{?>
										
											<td><?='-';  ?></td>
										<?php } ?>
										<!-- <td><?php echo $fare?></td>
										<td><?php echo $net_commission?></td>
										<td><?php echo $net_commission_tds?></td>
										<td><?php echo $net_fare?></td>										
										<td><?php echo $admin_markup?></td>										
										<td><?php echo $convinence_amount?></td>
										-->
										
									</tr>
								<?php
								}
							}
							 else {
								echo '<tr><td>---</td><td>---</td><td>---</td><td>---</td><td>---</td><td>---</td>
								 		  <td>---</td><td>---</td><td>---</td><td>---</td><td>---</td><td>---</td>
										  <td>---</td><td>---</td><td>---</td><td>---</td><td>---</td></tr>';
							}
						?>
						</tbody>
					</table>
					
				
		</div>
	</div>
</div>
<div class="modal fade" id="view_modal">
     <div class="modal-dialog">
      <div class="modal-content">

       


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
		      
					var _opp_url = app_base_url+'index.php/voucher/activity_crs/';
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

function sightseeing_voucher_email($app_reference, $booking_source,$status,$recipient_email)
{

	return '<a class="sideicbb3 sidedis send_email_voucher" data-app-status="'.$status.'"   data-app-reference="'.$app_reference.'" data-booking-source="'.$booking_source.'"data-recipient_email="'.$recipient_email.'"><i class="far fa-envelope"></i> Email Voucher</a>';
}

function get_cancellation_details_button($app_reference, $booking_source, $master_booking_status)
{
		//echo '<pre>'; 	print_r ($master_booking_status); die;
	
	if($master_booking_status == 'CANCELLED'){
		return '<a target="_blank" href="'.base_url().'index.php/sightseeing/ticket_cancellation_details?app_reference='.$app_reference.'&booking_source='.$booking_source.'&status='.$master_booking_status.'" class="col-md-12 btn btn-sm btn-info "><i class="far fa-info"></i> Cancellation Details</a>';
	}
	
}
function get_booking_pending_status($app_reference, $booking_source, $status)
{
	if($status == 'BOOKING_HOLD'){
		return '<a class="get_sightseeing_hb_status col-md-12 btn btn-sm btn-info flight_u" id="pending_status_'.$app_reference.'" data-booking-source="'.$booking_source.'"
			data-app-reference="'.$app_reference.'" data-status="'.$status.'"><i class="far fa-info"></i> Update Supplier Info</a>';
	}
}
function holiday_cancel($app_reference, $booking_source, $status)
{
	return '<a href="'.holiday_cancel_url($app_reference, $booking_source, $status).'"   onclick="'.$confirm.'" class="sidedis sideicbb4 " target="_blank"><i class="fa fa-arrows-alt"></i> Cancel</a>';

	

}

function holiday_cancel_url($app_reference, $booking_source='', $status='')
{
	return base_url().'index.php/activity/pre_cancellation/'.$app_reference.'/'.$booking_source.'/'.$status;
}
function flight_voucher_email($app_reference, $booking_source,$status,$recipient_email)
{

	return '<a class="sidedis sideicbb3 send_email_voucher" data-app-status="'.$status.'"   data-app-reference="'.$app_reference.'" data-booking-source="'.$booking_source.'"data-recipient_email="'.$recipient_email.'"> <i class="fa fa-envelope"></i>Email Voucher</a>';
}
function activity_invoice($app_reference, $booking_source, $status)
{
  return '<a href="'.activity_invoice_url($app_reference, $booking_source, $status).'/show_invoice" target="_blank" class="sideicbb3 sidedis"><i class="fa fa-file"></i>Invoice</a>';

}
function activity_invoice_url($app_reference, $booking_source='', $status='')
{
  return base_url().'index.php/voucher/activity_crs/'.$app_reference.'/'.$booking_source.'/'.$status;
}

function activity_vat_invoice($app_reference, $booking_source, $status)
{
  return '<a href="'.activity_vat_invoice_url($app_reference, $booking_source, $status).'/show_vat_invoice" target="_blank" class="sideicbb3 sidedis"><i class="fa fa-file"></i>VAT Invoice</a>';

}
function activity_vat_invoice_url($app_reference, $booking_source='', $status='')
{
  return base_url().'index.php/voucher/activity_crs/'.$app_reference.'/'.$booking_source.'/'.$status;
}

function activity_booking_details($app_reference, $booking_source, $status)
{
  return '<a href="'.activity_vat_booking_details_url($app_reference, $booking_source, $status).'/show_activity_details" target="_blank" class="sideicbb3 sidedis"><i class="fa fa-file"></i>Excursion View Details</a>';

}
function activity_vat_booking_details_url($app_reference, $booking_source='', $status='')
{
  return base_url().'index.php/voucher/activity_crs/'.$app_reference.'/'.$booking_source.'/'.$status;
}
?>
<script>
$(document).ready(function() {

		$(".get_sightseeing_hb_status").on("click",function(e){
  		
	 	app_reference = $(this).data('app-reference');
        book_reference = $(this).data('booking-source');
        app_status = $(this).data('status');
        var _opp_url = app_base_url+'index.php/sightseeing/get_pending_booking_status/';
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


