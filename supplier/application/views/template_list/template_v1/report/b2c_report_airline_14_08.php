<!--  <script src="<?php echo SYSTEM_RESOURCE_LIBRARY?>/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo SYSTEM_RESOURCE_LIBRARY?>/datatables/dataTables.bootstrap.min.js"></script> --> 
    <!-- <script src="//code.jquery.com/jquery-1.12.3.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
 -->
 
 
 
<?=$GLOBALS['CI']->template->isolated_view('report/email_popup')?>
<?php
if (is_array($search_params)) {
	extract($search_params);
}
$_datepicker = array(array('created_datetime_from', PAST_DATE), array('created_datetime_to', PAST_DATE));
$this->current_page->set_datepicker($_datepicker);
$this->current_page->auto_adjust_datepicker(array(array('created_datetime_from', 'created_datetime_to')));
?>
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
						 <div class="col-xs-4">
							<label>
							PNR
							</label>
							<input type="text" class="form-control" name="pnr" value="<?=@$pnr?>" placeholder="PNR">
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
					<a href="<?php echo base_url().'index.php/report/b2c_flight_report? '?>" id="clear-filter" class="btn btn-primary">Clear Filter</a>
					</div>
				</form>
			</div>
		</div>
		

		<div class="clearfix table-responsive"><!-- PANEL BODY START -->
					 <div class="pull-left">
						<?php echo $this->pagination->create_links();?> <span class="">Total <?php echo $total_rows ?> Bookings</span>
					</div> 


					<table class="table table-condensed table-bordered example3" id="b2c_report_airline_table">
						<thead>					
						<tr>
							<th>Sno</th>
							<th>Reference No</th>
							<th>Status</th>
							<th>Lead Pax <br/>Details</th>
							<th>PNR</th>
							<th>From</th>
							<th>To</th>
							<th>Type</th>
							<th>BookedOn</th>
							<th>Travel<br/> date</th>
							<th>Comm.Fare</th>
							<th>Commission</th>
							<th>TDS</th>
							<th>NetFare</th>
							<th>Admin <br/>Markup</th>
							<th>Convenience<br/>Fee</th>
							<th>Discount</th>
							<th> Customer paid <br/>amount</th>
							<th>Action</th>
						</tr>
						</thead>
						<tfoot>
						<tr>
							<th>Sno</th>
							<th>Reference No</th>
							<th>Status</th>
							<th>lead Pax Details</th>
							<th>PNR</th>
							<th>From</th>
							<th>To</th>
							<th>Type</th>
							<th>BookedOn</th>
							<th>Travel<br/> date</th>
							<th>Comm.Fare</th>
							<th>Commission</th>
							<th>TDS</th>
							<th>NetFare</th>							
							<th>Admin <br/>Markup</th>
							<th>Convenience<br/>Fee</th>
							<th>Discount</th>
							<th> Customer paid <br/>amount</th>
							<th>Action</th>
						</tr>
						</tfoot><tbody>
						<?php
						//	debug($table_data['booking_details']);exit;
							if(valid_array($table_data['booking_details']) == true) {
				        		$booking_details = $table_data['booking_details'];
				        		//$CI =& get_instance();$CI->toExcel($booking_details);

								$segment_3 = $GLOBALS['CI']->uri->segment(3);
								$current_record = (empty($segment_3) ? 1 : $segment_3);
					        	foreach($booking_details as $parent_k => $parent_v) {
					        		//debug($parent_v);exit;
					        		extract($parent_v);
					        		//debug($parent_v);exit;
									$action = '';
									$cancellation_btn = '';
									$voucher_btn = '';
									//$update_booking_details_btn = update_booking_details($app_reference, $booking_source);
									$booked_by = '';
									
									//Status Update Button
									/*if (in_array($status, array('BOOKING_CONFIRMED')) == false) {
										switch ($booking_source) {
											case PROVAB_FLIGHT_BOOKING_SOURCE :
												$status_update_btn = '<button class="btn btn-success btn-sm update-source-status" data-app-reference="'.$app_reference.'"><i class="far fa-database"></i> Update Status</button>';
												break;
										}
									}*/
									$voucher_btn = flight_voucher($app_reference, $booking_source, $status, 'b2c');
									$invoice = flight_invoice($app_reference, $booking_source, $status);
									$cancel_btn = flight_cancel($app_reference, $booking_source, $status);
									$pdf_btn= flight_pdf($app_reference, $booking_source, $status, 'b2c');
// echo $pdf_btn;exit;
									// $pdf_btn ='';
									$email_btn = flight_voucher_email($app_reference, $booking_source,$status,$email);
									
									$jrny_date = date('Y-m-d', strtotime($journey_start));
									$tdy_date = date ( 'Y-m-d' );
									$diff = get_date_difference($tdy_date,$jrny_date);
					        		$action .= $voucher_btn;
					        		$action .=  '<br />'.$pdf_btn;
					        		$action .=  '<br />'.$email_btn;
									if($diff > 0){
										$action .= $cancel_btn;
									}
									//$action .= $invoice;
									if ($status != 'BOOKING_CANCELLED') {
											
										if(strtotime('now') < strtotime($parent_v['journey_start'])){
											$update_booking_details_btn = update_booking_details($app_reference, $booking_source,$status);
											$action .= '<br />'.$update_booking_details_btn;
										}
											
									}									
								?>
									<tr>
										<td><?= ($current_record++) ?></td>
										<td><?php echo $app_reference;?></td>
										<td><span class="<?php echo booking_status_label($status) ?>"><?php echo $status?></span></td>
										<td>
										<?php echo $lead_pax_name. '<br/>'.
										  $email."<br/>".
										  $phone;?>
										</td>
										
										<td><?=$pnr?></td>
										<td><?php echo $from_loc?></td>
										<td><?php echo $to_loc?></td>
										<td><?php echo $trip_type_label?></td>
										<td><?php echo date('d-m-Y', strtotime($booked_date))?></td>
										<td><?php echo date('d-m-Y', strtotime($journey_start))?></td>
										<td><?php echo $fare?></td>
										<td><?php echo $net_commission?></td>
										<td><?php echo $net_commission_tds?></td>
										<td><?php echo $net_fare?></td>										
										<td><?php echo $admin_markup?></td>										
										<td><?php echo $convinence_amount?></td>
										<td><?php echo $discount?></td>
										<td><?php echo $grand_total?></td>
										<td><div class="" role="group"><?php echo $action; ?></div></td>
									</tr>
								<?php
								}
							}
							 else {
								echo '<tr><td>---</td><td>---</td><td>---</td><td>---</td><td>---</td><td>---</td>
								 		  <td>---</td><td>---</td><td>---</td><td>---</td><td>---</td><td>---</td>
										  <td>---</td><td>---</td><td>---</td><td>---</td><td>---</td><td>---</td><td>---</td></tr>';
							}
						?>
						</tbody>
					</table>
		</div>
		
				</div>
	</div>
</div>
<script>
$(document).ready(function() {
	/*$('#b2c_report_airline_table').DataTable({
        // Disable initial sort 
        "aaSorting": []
    });*/
	//update-source-status update status of the booking from api
	$(document).on('click', '.update-source-status', function(e) {
		e.preventDefault();
		$(this).attr('disabled', 'disabled');//disable button
		var app_ref = $(this).data('app-reference');
		$.get(app_base_url+'index.php/flight/get_booking_details/'+app_ref, function(response) {
			console.log(response);
		});
	});
	/*$('.update_flight_booking_details').on('click', function(e) {
		e.preventDefault();
		var _user_status = this.value;
		var _opp_url = app_base_url+'index.php/report/update_flight_booking_details/';
		_opp_url = _opp_url+$(this).data('app-reference')+'/'+$(this).data('booking-source');
		toastr.info('Please Wait!!!');
		$.get(_opp_url, function() {
			toastr.info('Updated Successfully!!!');
		});
	});*/
	$('.update_flight_booking_details').on('click', function(e) {
		e.preventDefault();
		var _user_status = this.value;
		var _opp_url = app_base_url+'index.php/report/update_pnr_details/';
		_opp_url = _opp_url+$(this).data('app-reference')+'/'+$(this).data('booking-source')+'/'+$(this).data('booking-status');
		toastr.info('Please Wait!!!');
		$.get(_opp_url, function() {
			toastr.info('Updated Successfully!!!');
			location.reload();
		});
	
	
});

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
			      
						var _opp_url = app_base_url+'index.php/voucher/flight/';
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
$(document).ready(function() {
	
    //$('.example3').DataTable();
} );
</script>
<?php
function get_accomodation_cancellation($courseType, $refId)
{
	return '<a href="'.base_url().'index.php/booking/accomodation_cancellation?courseType='.$courseType.'&refId='.$refId.'" class="btn btn-sm btn-danger "><i class="far fa-exclamation-triangle"></i> Cancel</a>';
}
function update_booking_details($app_reference, $booking_source,$booking_status)
{
	
	return '<a class="btn btn-sm btn-danger update_flight_booking_details" data-app-reference="'.$app_reference.'" data-booking-source="'.$booking_source.'"data-booking-status="'.$booking_status.'"><i class="far fa-sync"></i> Update PNR Details</a>';
}
function flight_voucher_email($app_reference, $booking_source,$status,$recipient_email)
{

	return '<a class="btn btn-sm btn-primary send_email_voucher" data-app-status="'.$status.'"   data-app-reference="'.$app_reference.'" data-booking-source="'.$booking_source.'"data-recipient_email="'.$recipient_email.'"><i class="far fa-envelope"></i> Email Voucher</a>';
}
?>
