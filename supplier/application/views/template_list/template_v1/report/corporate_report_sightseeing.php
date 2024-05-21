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
				<form method="GET" autocomplete="off" action="<?= base_url().'report/corporate_sightseeing_report?'?>">
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
							Type
							</label>
							<select class="form-control" name="type">
								<option>All</option>
								<option value="<?= CORPORATE_USER ?>" <?= ($type == CORPORATE_USER) ? 'Selected' : '' ?>>Corporate</option>
								<option value="<?= SUB_CORPORATE ?>" <?= ($type == SUB_CORPORATE) ? 'Selected' : '' ?>>Sub Corporate</option>
								<option value="<?= EMPLOYEE ?>" <?= ($type == EMPLOYEE) ? 'Selected' : '' ?>>Employees</option>
								
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
						<!-- <div class="col-xs-4">
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
						</div> -->
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
					<button type="reset" class="btn btn-warning">Reset</button>
					<a href="<?php echo base_url().'index.php/report/corporate_sightseeing_report?' ?>" id="clear-filter" class="btn btn-primary">Clear Filter</a>
					</div>
				</form>
			</div>
		</div>
		
		<div class="clearfix" style="overflow: auto"><!-- PANEL BODY START -->
			<?php echo get_table($table_data, $total_rows);?>
		</div>
	</div>
</div>

<?php
function get_table($table_data, $total_rows)
{
	$pagination = '<div class="pull-left">'.$GLOBALS['CI']->pagination->create_links().' <span class="">Total '.$total_rows.' Bookings</span></div>';
	$report_data = '';
	$report_data .= '<div id="tableList" class="clearfix">';
	$report_data .= $pagination;
	
	$report_data .= '<table class="table table-condensed table-bordered" id="corporate_report_hotel_table">
		<thead>
		<tr>
			<th>Sno</th>
			<th>Reference No</th>
			<th>Status</th>
			<th>Lead Pax details</th>
			<!--<th>Confirmation<br/>Reference</th>-->
			<th>Product Name</th>
			<th>No. of Pax<br/>(Adult + Child + Infant + Youth +Senior)</th>
			<th>City</th>
			<th>Travel Date</th>
			<th>Booked On</th>
			<th>Currency</th>
			<th>Comm.Fare</th>
			
			<th>Admin <br/> NetFare</th>
			<th>Admin <br/>Markup</th>
			<th>GST</th>
			<!--<th>Convn.Fee</th>
			<th>Discount</th>-->
			<th>Customer Paid <br/>amount</th>	
			<th>Action</th>
		</tr>
		</thead><tfoot>
		<tr>
			<th>Sno</th>
			<th>Reference No</th>
			<th>Status</th>
			<th>Lead Pax details</th>
				<!--<th>Confirmation<br/>Reference</th>-->
			<th>Product Name</th>
			<th>No. of Pax<br/>(Adult + Child + Infant + Youth +Senior)</th>
			<th>City</th>
			<th>Travel Date</th>
			<th>Booked On</th>
			<th>Currency</th>
			<th>Comm.Fare</th>
			
			<th>Admin <br/> NetFare</th>
			<th>Admin <br/>Markup</th>
			<th>GST</th>
			<!--<th>Convn.Fee</th>
			<th>Discount</th>-->
			<th>Customer Paid <br/>amount</th>	
			<th>Action</th>
		</tr>
		</tfoot><tbody>';
		
		if (isset($table_data) == true and valid_array($table_data['booking_details']) == true) {
			$segment_3 = $GLOBALS['CI']->uri->segment(3);
			$current_record = (empty($segment_3) ? 1 : $segment_3);
			$booking_details = $table_data['booking_details'];
			//debug($booking_details); exit;
		    foreach($booking_details as $parent_k => $parent_v) { 
		        	extract($parent_v);
		        	//debug($itinerary_details);exit;
				$action = '';
				$email='';
				$tdy_date = date ( 'Y-m-d' );
				$diff = get_date_difference($tdy_date,$travel_date);
				$action .= sightseeing_voucher($app_reference, $booking_source, $status,'corporate');
				$action.='<br/>';
				$action .= sightseeing_pdf($app_reference, $booking_source, $status,'corporate');
				$action.='<br/>';
				$action .= sightseeing_voucher_email($app_reference, $booking_source,$status,$parent_v['email']);
				$action.='<br/>';
		    	if($status == 'BOOKING_CONFIRMED' && $diff > 0) {
					$action .= cancel_sightseeing_booking($app_reference, $booking_source, $status,'corporate');
				}
				$action .=get_booking_pending_status($parent_v['app_reference'], $parent_v['booking_source'], $parent_v['status']);
				$action .= get_cancellation_details_button($parent_v['app_reference'], $parent_v['booking_source'], $parent_v['status']);
			
		$report_data .= '<tr>
					<td>'.($current_record++).'</td>
					<td>'.$app_reference.'</td>
					<td><span class="'.booking_status_label($status).'">'.$status.'</span></td>
					<td>'.$lead_pax_name. '<br/>'.
						  $lead_pax_email.'<br/>'.
						  $lead_pax_phone_number.'
					</td>
					<!--<td class="">'.$confirmation_reference.'</span></td>-->
					<td>'.$product_name.'</td>
					<td>('.$adult_count.'+'.$child_count.'+'.$infant_count.'+'.$youth_count.'+'.$senior_count.')</td>
					<td>'.$Destination.'</td>
					<td>'.date('d-m-Y', strtotime($travel_date)).'</td>
				<td>'.date('d-m-Y', strtotime($voucher_date)).'</td>
					<td>'.$currency.'</td>
					<td>'.$fare.'</td>
				
					<td>'.$admin_net_fare.'</td>
					<td>'.$admin_markup.'</td>
					<td>'.$gst.'</td>
					<!--<td>'.$convinence_amount.'</td>
					<td>'.$discount.'</td>-->
					<td>'.roundoff_number($admin_net_fare+$admin_markup+$gst).'</td>			
					<td><div class="" role="group">'.$action.'</div></td>
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

function sightseeing_voucher_email($app_reference, $booking_source,$status,$recipient_email)
{

	return '<a class="btn btn-sm btn-primary send_email_voucher fa fa-envelope-o" data-app-status="'.$status.'"   data-app-reference="'.$app_reference.'" data-booking-source="'.$booking_source.'"data-recipient_email="'.$recipient_email.'">Email Voucher</a>';
}
function get_booking_pending_status($app_reference, $booking_source, $status)
{
	if($status == 'BOOKING_HOLD'){
		return '<a class="get_sightseeing_hb_status col-md-12 btn btn-sm btn-info flight_u" id="pending_status_'.$app_reference.'" data-booking-source="'.$booking_source.'"
			data-app-reference="'.$app_reference.'" data-status="'.$status.'"><i class="fa fa-info"></i>Update Supplier Info</a>';
	}
}
function get_cancellation_details_button($app_reference, $booking_source, $master_booking_status)
{
		//echo '<pre>'; 	print_r ($master_booking_status); die;
	
	if($master_booking_status == 'BOOKING_CANCELLED'){
		return '<a target="_blank" href="'.base_url().'index.php/sightseeing/ticket_cancellation_details?app_reference='.$app_reference.'&booking_source='.$booking_source.'&status='.$master_booking_status.'" class="col-md-12 btn btn-sm btn-info "><i class="fa fa-info"></i> Cancellation Details</a>';
	}
	
}
?>
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
					var _opp_url = app_base_url+'index.php/voucher/b2e_sightseeing_voucher/';
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