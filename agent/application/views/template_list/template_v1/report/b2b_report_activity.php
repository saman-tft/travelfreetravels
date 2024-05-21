<?php 
//error_reporting(E_ALL);
$_datepicker = array(array('created_datetime_from', PAST_DATE), array('created_datetime_to', PAST_DATE));
$this->current_page->set_datepicker($_datepicker);
$this->current_page->auto_adjust_datepicker(array(array('created_datetime_from', 'created_datetime_to')));
?>
<link href="<?php echo $GLOBALS['CI']->template->template_css_dir('bootstrap-toastr/toastr.min.css');?>" rel="stylesheet" defer>
<script src="<?php echo $GLOBALS['CI']->template->template_js_dir('bootstrap-toastr/toastr.min.js'); ?>"></script>
<?=$GLOBALS['CI']->template->isolated_view('report/email_popup')?>
<div class="bodyContent">
	<div class="table_outer_wrper"><!-- PANEL WRAP START -->
		<div class="panel_custom_heading"><!-- PANEL HEAD START -->
			<div class="panel_title">
				<?php echo $GLOBALS['CI']->template->isolated_view('share/report_navigator_tab') ?>
                <div class="clearfix"></div>
                <h4 class="advncpnl">Advanced Search Panel<button class="btn btn-primary btn-sm toggle-btn spadv" data-toggle="collapse" data-target="#show-search">+
        		</button> </h4>
        		<hr>
        		<div id="show-search" class="collapse">
  				<form method="GET" autocomplete="off">
  					<input type="hidden" id="module" value="<?=PROVAB_SIGHTSEEN_SOURCE_CRS?>">
  					<div class="clearfix form-group">
				 		<div class="col-xs-4">
	              			<label>Reference No</label>
	              			<input type="text" autocomplete="off" data-search_category="search_query" placeholder="AppReference/PNR" class="form-control auto_suggest_booking_id ui-autocomplete-input" id="auto_suggest_booking_id" name="filter_report_data" value="<?=@$_GET['filter_report_data']?>">
	              			</div>
	             		<div class="col-xs-4">
				              <label>Status </label>
				              <select class="form-control" name="status">
				                <option>ALL</option>
				                <option>PROCESSING</option>
				                <option>BOOKING_CONFIRMED</option>
				                <option>CANCELLED</option>
				                <option>CANCELLATION_IN_PROCESS</option>
				                <option>BOOKING_INPROGRESS</option>
				              </select>
            			</div>
            			<div class="col-xs-4">
			              	<label>Booked From Date</label>
			              	<input type="text" readonly id="created_datetime_from" class="form-control" name="created_datetime_from" value="<?=@$created_datetime_from?>" placeholder="Request Date">
			            </div>
			            <div class="col-xs-4">
			              <label>Booked To Date</label>
              			  <input type="text" readonly id="created_datetime_to" class="form-control disable-date-auto-update" name="created_datetime_to" value="<?=@$created_datetime_to?>" placeholder="Request Date" disabled="true">
            			</div>
					</div>
					<div class="col-sm-12 well well-sm">
		          		<button type="submit" class="btn btn-primary">Search</button> 
		         		<button type="reset" class="btn btn-warning">Reset</button>
		          		<a href="<?php echo base_url().'index.php/report/activities_crs? '?>" id="clear-filter" class="btn btn-primary">Clear Filter</a>
		           	</div>
	        </form>
	      </div>
	    </div>	
			<!--	<button title="Search" class="btn btn-default" type="submit"><i class="far fa-search"></i></button>
				<a title="Clear Search" class="btn btn-default" href="<?=base_url().'index.php/report/activities_crs'?>"><i class="far fa-history"></i></a> -->
		<!-- PANEL HEAD START -->
		<div class="panel_bdy"><!-- PANEL BODY START -->

        <div class="clearfix"></div>
			<div class="tab-content">
				<div id="tableList" class="table-responsive">
					<div class="pull-right">
						<?php echo $this->pagination->create_links();?> <span class="">Total <?php echo $total_rows ?> Bookings</span>
					</div>
					<table class="table table-condensed table-bordered example3 b-report" id="b2c_report_airline_table">
						<thead>					
						<tr>
							<th>Sno</th>
							<th>Action</th>
							<th>Reference No</th>
							<th>Status</th>
							<!-- <th>Package Name</th> -->
							<th>Lead Pax Details</th>
							<th>Package Price</th>
	                        <th>Markup(Agent+Instant)</th>
	                        <th>Convenience Fees</th>
	                        <th>VAT Amount</th>
	                        <th>Total<br/>Fare</th>
							<!-- <th>Customer paid <br/>amount</th> -->
	                        <th>Currency</th>
	                        <th>Payment Mode</th>
							<th>BookedOn</th>
							<th>Travel<br/> date</th>
							 <th>Cancelled Date</th>
						</tr>
						</thead>
						<tfoot>
						<tr>
							<th>Sno</th>
							<th>Action</th>
							<th>Reference No</th>
							<th>Status</th>
							<!-- <th>Package Name</th> -->
							<th>Lead Pax Details</th>
							<th>Package Price</th>
	                        <th>Markup(Agent+Instant)</th>
	                        <th>Convenience Fees</th>
	                        <th>VAT Amount</th>
	                        <th>Total<br/>Fare</th>
							<!-- <th>Customer paid <br/>amount</th> -->
	                        <th>Currency</th>
	                        <th>Payment Mode</th>
							<th>BookedOn</th>
							<th>Travel<br/> date</th>
							 <th>Cancelled Date</th>
						</tr>
						</tfoot><tbody>
						<?php
							// debug($table_data['booking_details']);exit;
							if(valid_array($table_data['booking_details']) == true) {
				        		$booking_details = $table_data['booking_details'];
				        		//$CI =& get_instance();$CI->toExcel($booking_details);

								$segment_3 = $GLOBALS['CI']->uri->segment(3);
								$current_record = (empty($segment_3) ? 1 : $segment_3);
					        	foreach($booking_details as $parent_k => $parent_v) {
					        		$parent_pax = $parent_v['itinerary_details'];
					        		
					        		//debug($name);exit;
					        		extract($parent_v);
					        //	debug($parent_v);exit;
									$action = '';
									$cancellation_btn = '';
									$voucher_btn = '';
									$invoice_btn = '';
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

									//$invoice = flight_invoice($app_reference, $booking_source, $status);
									$cancel_btn = holiday_cancel($app_reference, $booking_source, $status);
									$pdf_btn= activity_pdf($app_reference, $booking_source, $status, 'b2b');
									$email_btn = flight_voucher_email($app_reference, $booking_source,$status,$email);
									
									$jrny_date = date('Y-m-d', strtotime($date_of_travel));
									$tdy_date = date ( 'Y-m-d' );
									$diff = get_date_difference($tdy_date,$jrny_date);
					        		$action .= $voucher_btn;
					        		$action .= '<br />'.$invoice_btn;
					        		$action .=  '<br />'.$pdf_btn;
					        		$action .=  '<br />'.$email_btn;
									if($diff > 0 && $status != 'CANCELLED'){
										$action .= $cancel_btn;
									}
									$action .=get_message_details($app_reference,$booking_source,'activities_crs');
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
											    <ul class="dropdown-menu sidedis" style="left:30px;display: none;">
												   <li>
						   	                         <?php echo $action; ?>
						   	                       </li>
						   	                    </ul>
						   	                 </div>
						   	                </div>


											<!--<div class="my-group" role="group"><?php echo $action; ?></div>
-->

										</td>
										<?php 
					                      if($status == 'CANCELLED'){
					                        $status = 'BOOKING_CANCELLED';
					                      }
					                      ?>
										<td><?php echo $app_reference;?></td>
										<td><span class="<?php echo booking_status_label($status) ?>"><?php echo $status?></span></td>
										<td>
									<?php echo $parent_pax['0']['first_name']." ".$parent_pax['0']['last_name']
										. '<br/>'.
										  $email."<br/>".
										  $phone;?>
										</td>
										<td><?php echo $parent_v['basic_fare']+$parent_v['admin_markup']-$parent_v['reward_amount'];?></td>
										<td><?php echo $parent_v['agent_markup'];?></td>
										<td><?php echo $parent_v['convenience_fee'];?></td>
										<td><?php echo $parent_v['gst_percentage'];?></td>
										<td><?php echo $parent_v['total_fare']-$parent_v['reward_amount'];?></td><!-- 
										<td><?php echo $parent_v['agent_buying_price'];?></td> -->
										<td><?php echo $parent_v['currency'];?></td>
										<td><?php echo $parent_v['payment_mode'];?></td>

										
										<!-- <td><?=$pnr?></td>
										<td><?php echo $from_loc?></td>
										<td><?php echo $to_loc?></td>
										<td><?php echo $trip_type_label?></td> -->
										<td><?php echo date('d-M-y', strtotime($created_datetime))?></td>
										<td><?php echo date('d-M-y', strtotime($date_of_travel))?></td>
										<td><?php  
					                       //debug($final_cancel_date);exit;
					                       if(($final_cancel_date == '0000-00-00 00:00:00')||($final_cancel_date == ' ')||($final_cancel_date == '0')){
					                        $cancel_date = "-";
					                      // debug("hello"  .$final_cancel_date);exit;
					                       }else{
					                        //debug($final_cancel_date);exit;
					                        $cancel_date = date('d-M-y', strtotime($final_cancel_date));
					                       }
					                        echo $cancel_date;
					                     ?></td>
										<!-- <td><?php echo $fare?></td>
										<td><?php echo $net_commission?></td>
										<td><?php echo $net_commission_tds?></td>
										<td><?php echo $net_fare?></td>										
										<td><?php echo $admin_markup?></td>										
										<td><?php echo $convinence_amount?></td>
										
										<td><?php echo $booking_transaction_details[0]['discount']?></td> -->
									</tr>
								<?php
								}
							}
							 else {
								echo '<tr><td>---</td><td>---</td><td>---</td><td>---</td><td>---</td><td>---</td>
								 		  <td>---</td><td>---</td></tr>';
							
							}
						?>
						</tbody>
					</table>
				</div>
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
		$.get(app_base_url+'index.php/sightseeing/get_booking_details/'+app_ref, function(response) {
			
		});
	});

    /*
    *Sagar Wakchaure
    *send email voucher
    */
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
			      		$.ajax({
						      type: 'POST',
						      url: app_base_url+'index.php/voucher/activity_crs/'+app_reference+'/'+book_reference+'/'+app_status+'/email_voucher/',
						      data: {
						      	email:email
						      },
						      dataType: "text",
						      success: function(resultData) { 
						      	// alert("Save Complete")
						      	toastr.info('Email sent  Successfully!!!');
								$("#mail_voucher_modal").modal('hide');
						      	 }
						});
						// var _opp_url = app_base_url+'index.php/voucher/activity_crs/';
						// _opp_url = _opp_url+app_reference+'/'+book_reference+'/'+app_status+'/email_voucher/'+email;
						// toastr.info('Please Wait!!!');
						// $.get(_opp_url, function() {
							
						// 	toastr.info('Email sent  Successfully!!!');
						// 	$("#mail_voucher_modal").modal('hide');
						// });
			  }else{
				  $('#mail_voucher_error_message').empty().text('Please Enter Email ID');
			  }
		  });
	
	});
	
});
 var dis1 = document.getElementById("created_datetime_from");
	dis1.onchange = function () {
   if (this.value != "" || this.value.length > 0) {
      document.getElementById("created_datetime_to").disabled = false;
   }
}
</script>
<?php

function sightseeing_voucher_email($app_reference, $booking_source,$status,$recipient_email)
{

	return '<a class="sideicbb3 sidedis send_email_voucher" data-app-status="'.$status.'"   data-app-reference="'.$app_reference.'" data-booking-source="'.$booking_source.'"data-recipient_email="'.$recipient_email.'"><i class="far fa-envelope"></i> Email Voucher</a>';
}

function get_cancellation_details_button($app_reference, $booking_source, $master_booking_status)
{
	
	if($master_booking_status == 'BOOKING_CANCELLED'){

		return '<a target="_blank" href="'.base_url().'index.php/sightseeing/cancellation_refund_details?app_reference='.$app_reference.'&booking_source='.$booking_source.'&status='.$master_booking_status.'" class="col-md-12 btn btn-sm btn-info "><i class="far fa-info"></i> Cancellation Details</a>';
	}

	
}
function holiday_cancel($app_reference, $booking_source, $status)
{
	return '<a href="'.holiday_cancel_url($app_reference, $booking_source, $status).'"   onclick="'.$confirm.'" class="" target="_blank"><i class="fa fa-arrows-alt"></i> Cancel</a>';

	

}

function holiday_cancel_url($app_reference, $booking_source='', $status='')
{
	return base_url().'index.php/activities/pre_cancellation/'.$app_reference.'/'.$booking_source.'/'.$status;
}

function activity_invoice($app_reference, $booking_source, $status)
{
  return '<a href="'.activity_invoice_url($app_reference, $booking_source, $status).'/show_invoice" target="_blank" class="sideicbb3 sidedis"><i class="fa fa-file"></i>Invoice</a>';

}
function activity_invoice_url($app_reference, $booking_source='', $status='')
{
  return base_url().'index.php/voucher/activity_crs/'.$app_reference.'/'.$booking_source.'/'.$status;
}
function flight_voucher_email($app_reference, $booking_source,$status,$recipient_email)
{

	return '<a class="sideicbb3 sidedis send_email_voucher" data-app-status="'.$status.'"   data-app-reference="'.$app_reference.'" data-booking-source="'.$booking_source.'"data-recipient_email="'.$recipient_email.'"><i class="far fa-envelope"></i> Email Voucher</a>';
}
function get_message_details($app_reference, $booking_source,$type)
{
	
		return '<a class="sideicbb5 sidedis flight_u" target="_blank" href="'.base_url().'message/send_message_view?app_reference='.$app_reference.'&booking_source='.$booking_source.'&type='.$type.'"><i class="far fa-envelope"></i>Send Message</a>';
}

?>
