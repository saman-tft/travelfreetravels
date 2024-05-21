<style>
#doublescroll
{
  overflow: auto; overflow-y: hidden; 
}
#doublescroll p
{
  margin: 0; 
  padding: 1em; 
  white-space: nowrap; 
}
</style>
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
			<?=$GLOBALS['CI']->template->isolated_view('report/report_tab_corporate')?>
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
				
				<form method="GET" autocomplete="off" action="<?= base_url().'report/corporate_flight_report?'?>">
					
					<div class="clearfix form-group">
						<div class="col-xs-4">
							<label>
							Corporate
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
						<div class="col-xs-4">
							<label>
							Quarterly
							</label>
							<select class="form-control" name="quarterly">
							<option value="">Select From & To Month</option>
								<option value="01-04">JAN-APR</option>
								<option value="05-08">MAY-AUG</option>
								<option value="09-12">SEP-DEC</option>
							</select>
						</div>
						<div class="col-xs-4">
							<label>
							Monthly
							</label>
							<select class="form-control" name="month_wise">
							<option value="">Select Month</option>
								<option value="01">JAN</option>
								<option value="02">FEB</option>
								<option value="03">MAR</option>
								<option value="04">APR</option>
								<option value="05">MAY</option>
								<option value="06">JUN</option>
								<option value="07">JUL</option>
								<option value="08">AUG</option>
								<option value="09">SEP</option>
								<option value="10">OCT</option>
								<option value="11">NOV</option>
								<option value="12">DEC</option>

							</select>
						</div>
						<div class="col-xs-4">
							<label>
							Year Wise
							</label>
							<input type="text" readonly id="year_wise" class="form-control" name="year_wise" value="<?=@$year_wise?>" placeholder="year_wise">
						</div>
						</script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>

 <script type="text/javascript">
      $('#year_wise').datepicker({
         minViewMode: 2,
         format: 'yyyy'
       });
  </script>
					</div>
					<div class="col-sm-12 well well-sm">
					<button type="submit" class="btn btn-primary">Search</button> 
					<button type="reset" id="btn-reset" class="btn btn-warning">Reset</button>
					<a href="<?=base_url().'report/b2b_flight_report?' ?>" id="clear-filter" class="btn btn-primary">Clear Filter</a>
					</div>
					
				</form>
			</div>
			
		</div>
		<div class="clearfix"></div>
		<?php if(valid_array($table_data['booking_details']) == true) {?>
		 <div class="row">
                    <a href="<?php echo base_url(); ?>index.php/report/corporate_flight_report/excel<?= !empty($_SERVER["QUERY_STRING"]) ? '?' . $_SERVER["QUERY_STRING"] : '' ?>">
                        <button class="btn btn-primary btn-xs" type="button">Export to Excel</button>
                    </a>
                     <!--   <a href="<?php echo base_url(); ?>index.php/report/b2b_bus_report/pdf<?= !empty($_SERVER["QUERY_STRING"]) ? '?' . $_SERVER["QUERY_STRING"] : '' ?>" target="_blank">
                        <button class="btn btn-primary btn-xs" type="button">Pdf</button>
                    </a>

                <button class="btn btn-primary btn-xs" type="button" onclick="window.print(); return true;">Print</button> -->

                </div><?php } ?>
<div class="pull-left">
						<?php echo $this->pagination->create_links();?> <span class="">Total <?php echo $total_rows ?> Bookings</span>
					</div>
                <div class="clearfix"></div>
                <br/>
		<div class="clearfix table-responsive"><!-- PANEL BODY START -->
					 
					<div id="doublescroll">
									<table class="table table-condensed table-bordered" id="b2b_report_airline_table">
						<thead>
							<tr>
								<th>Sno</th>
								<th>Application Reference</th>
								<th>Status</th>
								<th>Corporate Name</th>
								<th>Corporate ID</th>
								<th>Lead Pax <br/>Details</th>
								<th>PNR</th>
								<th>From</th>
								<th>To</th>
								<th>Type</th>
								<th>BookedOn</th>
								<th>JourneyDate</th>
								<th>Payment Mode</th>
								<th>No. of pax</th>
								<th>Comm.Fare</th>
								<th>Admin<br/>Markup</th>
								<th>Commission</th>
								<th>TDS</th>
								<th>Convenience Fee</th>
								<th>Admin NetFare</th>
								
								<!--<th>Corporate<br/>Commission</th>
								<th>Corporate<br/>TDS</th>-->
								<th>Corporate <br/>Net Fare</th>
								<th>Discount</th>
								<!--<th>Corporate<br/>Markup</th>-->
								<th>TotalFare</th>
								<th>Admin Profit</th>
								<th>Action</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th>Sno</th>
								<th>Application Reference</th>
								<th>Status</th>
								<th>Corporate Name</th>
								<th>Corporate ID</th>
								<th>Lead Pax <br/>Details</th>
								<th>PNR</th>
								<th>From</th>
								<th>To</th>
								<th>Type</th>
								<th>BookedOn</th>
								<th>JourneyDate</th>
								<th>Payment Mode</th>
								<th>No. of pax</th>
								<th>Comm.Fare</th>
								<th>Admin<br/>Markup</th>
								<th>Commission</th>
								<th>TDS</th>
								<th>Convenience Fee</th>
								<th>Admin NetFare</th>
								
								<!--<th>Corporate<br/>Commission</th>
								<th>Corporate<br/>TDS</th>-->
								<th>Corporate <br/>Net Fare</th>
								<th>Discount</th>
								<!--<th>Corporate<br/>Markup</th>-->
								<th>TotalFare</th>
								<th>Admin Profit</th>
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
									if($status==1)
        {
        	$nstatus='BOOKING_CONFIRMED';
        	
        }
        else if($status==2)
        {
$nstatus='BOOKING_HOLD';
        }
        
        else if($status==3)
        {
        	$nstatus='BOOKING_CANCELLED';
        }
        
        else if($status==4)
        {
        	$nstatus='BOOKING_ERROR';
        }
        
        else if($status==5)
        {
        	$nstatus='BOOKING_INCOMPLETE';
        }
        
        else if($status==7)
        {
        	$nstatus='BOOKING_PENDING';
        }
         else if($status==8)
        {
        	$nstatus='BOOKING_FAILED';
        }
         else if($status==9)
        {
        	$nstatus='BOOKING_INPROGRESS';
        }
									
									//Status Update Button
									/*if (in_array($status, array('BOOKING_CONFIRMED')) == false) {
										switch ($booking_source) {
											case PROVAB_FLIGHT_BOOKING_SOURCE :
												$status_update_btn = '<button class="btn btn-success btn-sm update-source-status" data-app-reference="'.$app_reference.'"><i class="fa fa-database"></i> Update Status</button>';
												break;
										}
									}*/
									
									
									
									$voucher_btn = flight_voucher($app_reference, $booking_source, $status,'b2b');
									$invoice = flight_invoice($app_reference, $booking_source, $status);
									$cancel_btn = flight_cancel($app_reference, $booking_source, $status);
									$pdf_btn= flight_pdf($app_reference, $booking_source, $status);
									$email_btn = flight_voucher_email($app_reference, $booking_source,$status,$email);
									$jrny_date = date('Y-m-d', strtotime($journey_start));
									$tdy_date = date ( 'Y-m-d' );
									$diff = get_date_difference($tdy_date,$jrny_date);
									$action .= check_run_ticket_method($parent_v['app_reference'],$parent_v['booking_source'],$status,$parent_v['is_domestic'],$parent_v['journey_start']);
					        		$action .= $voucher_btn;
					        		$action .=  '<br />'.$pdf_btn;
					        		$action .=  '<br />'.$email_btn;
									if($diff > 0){
										$action .= $cancel_btn;
									}
									
									if ($status != 'BOOKING_CANCELLED') {
									
										if(strtotime('now') < strtotime($parent_v['journey_start'])){
											$update_booking_details_btn = update_booking_details($app_reference, $booking_source,$status);
											$action .= '<br />'.$update_booking_details_btn;
										}
									
									}
									$action .= get_cancellation_details_button($parent_v['app_reference'], $parent_v['booking_source'], $parent_v['status'], $parent_v['booking_transaction_details']);
                                    $get_count= $this->custom_db->single_table_records ( 'flight_booking_passenger_details','count(*)',array('app_reference'=> $app_reference));
									$passenger_count = $get_count['data'][0]['count(*)'];

								?>
									<tr>
										<td><?=($current_record++)?></td>
										<td><?php echo $app_reference;?></td>
										<td><span class="<?php echo booking_status_label($status) ?>"><?php echo $status?></span></td>
										<td><?php echo $agency_name;?></td>
										<td><?php echo $corporate_id;?></td>
										<td>
										<?php echo $lead_pax_name. '<br/>'.
										  $email."<br/>".
										  $phone;?>
										</td>
										<td><?=@$pnr?></td>
										<td><?php echo $departure_from?></td>
										<td><?php echo $arrival_to?></td>
										<td><?php echo $trip_type_label?></td>
										<td><?php echo date('d-m-Y', strtotime($booked_date))?></td>
										<td><?php echo date('d-m-Y', strtotime($journey_start))?></td>
										<td><?php 
										
										if($payment_mode=='PNHB1')
										{
											echo 'Online';
										}
										else if($payment_mode=='PABHB3'){
											echo 'Wallet';
										}
										else {
											echo $payment_mode;
										}
										
										?></td>
										<td><?php echo $passenger_count;?></td>
										<td><?php echo $fare?></td>
										<td><?php echo $admin_markup?></td>
										<td><?php echo $net_commission?></td>
										<td><?php echo $net_commission_tds?></td>
										<td><?php echo $convinence_amount;?></td>
										<td><?php echo $net_fare?></td>
										
										<!--<td><?php echo $agent_commission?></td>
										<td><?php echo $agent_tds?></td>-->
										<td><?php echo $agent_buying_price?></td>
										<td><?php echo $discount;?></td>
										<!--<td><?php echo $agent_markup?></td>-->
										<td><?php echo $grand_total?></td>
										<!--<td><?php echo round(($agent_buying_price - $net_fare),2)?></td>-->
										<td><?php echo round((($convinence_amount + $admin_markup + $net_commission)-$net_commission_tds),2)?></td>

										<td><div class="" role="group"><?php echo $action; ?></div></td>
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
</div>
<script>
$(document).ready(function() {
	//update-source-status update status of the booking from api
	$(document).on('click', '.update-source-status', function(e) {
		e.preventDefault();
		$(this).attr('disabled', 'disabled');//disable button
		var app_ref = $(this).data('app-reference');
		$.get(app_base_url+'index.php/flight/get_booking_details/'+app_ref, function(response) {
			console.log(response);
		});
	});
	$('.issue_hold_ticket').on('click', function(e) {
		e.preventDefault();
		var _user_status = this.value;
		var _opp_url = app_base_url+'index.php/flight/run_ticketing_method/';
		_opp_url = _opp_url+$(this).data('app-reference')+'/'+$(this).data('booking-source');
		toastr.info('Please Wait!!!');
		$.get(_opp_url, function(res) {
			var obj = JSON.parse(res);
			toastr.info(obj.Message);
		});
	});
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
 function DoubleScroll(element) {
	        var scrollbar= document.createElement('div');
	        scrollbar.appendChild(document.createElement('div'));
	        scrollbar.style.overflow= 'auto';
	        scrollbar.style.overflowY= 'hidden';
	        scrollbar.firstChild.style.width= element.scrollWidth+'px';
	        scrollbar.firstChild.style.paddingTop= '1px';
	        scrollbar.firstChild.appendChild(document.createTextNode('\xA0'));
	        scrollbar.onscroll= function() {
	            element.scrollLeft= scrollbar.scrollLeft;
	        };
	        element.onscroll= function() {
	            scrollbar.scrollLeft= element.scrollLeft;
	        };
	        element.parentNode.insertBefore(scrollbar, element);
	    }

	    DoubleScroll(document.getElementById('doublescroll'));
	
});
</script>
<?php
function get_accomodation_cancellation($courseType, $refId)
{
	return '<a href="'.base_url().'index.php/booking/accomodation_cancellation?courseType='.$courseType.'&refId='.$refId.'" class="btn btn-sm btn-danger "><i class="fa fa-exclamation-triangle"></i> Cancel</a>';
}
function update_booking_details($app_reference, $booking_source,$booking_status)
{
	
	return '<a class="btn btn-danger update_flight_booking_details" data-app-reference="'.$app_reference.'" data-booking-source="'.$booking_source.'"data-booking-status="'.$booking_status.'">Update PNR Details</a>';
}
function flight_voucher_email($app_reference, $booking_source,$status,$recipient_email)
{

	return '<a class="btn btn-sm btn-primary send_email_voucher fa fa-envelope-o" data-app-status="'.$status.'"   data-app-reference="'.$app_reference.'" data-booking-source="'.$booking_source.'"data-recipient_email="'.$recipient_email.'">Email Voucher</a>';
}
function get_cancellation_details_button($app_reference, $booking_source, $master_booking_status, $booking_customer_details)
{
		//echo '<pre>'; 	print_r ($master_booking_status); die;
	$status = 'BOOKING_CONFIRMED';
	if($master_booking_status == 'BOOKING_CANCELLED'){
		$status = 'BOOKING_CANCELLED';
	} else if($master_booking_status == 'BOOKING_FAILED'){
		foreach($booking_customer_details as $tk => $tv){
			foreach($tv['booking_customer_details'] as $pk => $pv){
				if($pv['status'] == 'BOOKING_CANCELLED'){
					$status = 'BOOKING_CANCELLED';
					break;
				}
			}
		}
	}
	if($status == 'BOOKING_CANCELLED'){
		return '<a target="_blank" href="'.base_url().'index.php/flight/ticket_cancellation_details?app_reference='.$app_reference.'&booking_source='.$booking_source.'&status='.$master_booking_status.'" class="col-md-12 btn btn-sm btn-info "><i class="fa fa-info"></i> Cancellation Details</a>';
	}
}
?>
<script>
$(document).ready(function() {

	// $("#btn-reset").click(function(){

		
		
	// });
    // $('#b2b_report_airline_table').DataTable({
    //     // Disable initial sort 
    //     "aaSorting": []
    // });
});
</script>
