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
                 <h4 class="advncpnl">Advanced Search Panel<button class="btn btn-primary btn-sm toggle-btn" data-toggle="collapse" data-target="#show-search">+
        		</button> </h4>
        		<hr>
        		<div id="show-search" class="collapse">
  				<form method="GET" autocomplete="off">
  					<input type="hidden" id="module" value="<?=PROVAB_TRANSFERV1_BOOKING_SOURCE?>">
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
				                <option>BOOKING_CANCELLED</option>
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
		         		<?php $default_view = $GLOBALS ['CI']->uri->segment ( 3 );?>
		          		<a href="<?php echo base_url().'index.php/report/transfers_crs/'.$default_view.'? '?>" id="clear-filter" class="btn btn-primary">Clear Filter</a>
		           	</div>
	        </form>
	      </div>
	    </div>	

				<!--<button title="Search" class="btn btn-default" type="submit"><i class="far fa-search"></i></button>
				<a title="Clear Search" class="btn btn-default" href="<?=base_url().'index.php/report/transfers'?>"><i class="far fa-history"></i></a>-->
		<!-- PANEL HEAD START -->
		<div class="panel_bdy"><!-- PANEL BODY START -->

        <div class="clearfix"></div>
			<div class="tab-content">
				<div id="tableList" class="table-responsive">
					<div class="pull-right">
						<?php echo $this->pagination->create_links();?> <span class="">Total <?php echo $total_rows ?> Bookings</span>
					</div>
					<table class="table table-condensed table-bordered">
						<tr>
							<th>Sno</th>
							
							<th>Application Reference</th>
							<th>Customer<br/>Name</th>
							<th>Travel From</th>
							<th>Travel To</th>						
							<th>Agent Net Fare</th>
							<th>Agent Markup<br/>(Instant+Agent)</th>
							<th>VAT</th>
							<th>TotalFare</th>
							<th>TravelDate</th>
							<th>BookedOn</th>
							<th>Driver Name</th>
							<th>Driver Contact<br/>Number</th>
							<th>Customer Remarks</th>
							<th>Status</th>
							<th>Cancelled Date</th>
							<?php //debug($table_data['booking_details'][0]['origin']);exit; 
								$booking_details = $table_data['booking_details'];
								//debug($booking_details);exit;
								foreach($booking_details as $parent_k => $parent_v) {
									if(($parent_v['emulate_booking'])==1){
										//debug($parent_v['emulate_booking']);exit;
									 ?>
										<th>Staff Details</th>
									</tr>
									<?php break;
								  }
								}
							?>
							<th>Action</th>
							
						</tr>
						<?php
							if(valid_array($table_data['booking_details']) == true) {
				        		$booking_details = $table_data['booking_details'];
								$segment_3 = $GLOBALS['CI']->uri->segment(3);
								$current_record = (empty($segment_3) ? 1 : $segment_3);
					        	foreach($booking_details as $parent_k => $parent_v) {
					        //		debug($booking_details);exit;
					        		extract($parent_v);
					        		//debug($parent_v['origin']);exit; 
									$action = '';
									$cancellation_btn = '';
									$voucher_btn = '';
									$view_btn = '';
									$invoice_btn = '';
									$status_update_btn = '';
									$booked_by = '';
									$cancel_btn='';
									
									//Status Update Button
									if ($status=='BOOKING_HOLD'){

										$status_update_btn = '<button class="btn btn-success btn-sm update-source-status" data-app-reference="'.$app_reference.'"><i class="far fa-database"></i> Update Status</button>';
									}
									
									$voucher_btn = transfer_voucher($app_reference, $booking_source, $status,'b2b');
									$view_btn = transfer_view($app_reference, $booking_source, $status,'b2b');
									$invoice_btn = transfer_invoice($app_reference, $booking_source, $status,'b2b');
									$pdf_btn = transfer_pdf($app_reference, $booking_source, $status,'b2b');
									$cancel_btn = cancel_transfer_booking($app_reference, $booking_source, $status,'b2b');
									
									
									$email_btn = transfer_voucher_email($app_reference, $booking_source,$status,$parent_v['email'],'b2b');

									$jrny_date = date('Y-m-d', strtotime($travel_date));
									$tdy_date = date ( 'Y-m-d' );
									$diff = get_date_difference($tdy_date,$jrny_date);
									$action .= $voucher_btn;
									$action .= $view_btn ;
									$action .= '<br />'.$pdf_btn;
									$action .= '<br />'.$invoice_btn;
									$action .=  '<br />'.$email_btn;
									$action .= '<br/>'.$status_update_btn;
									if($diff > 0 || $status=='BOOKING_CONFIRMED'){
										$action .= $cancel_btn;
									}
									// $action .= get_cancellation_details_button($parent_v['app_reference'], $parent_v['booking_source'], $parent_v['status']);
									$action .=get_message_details($parent_v['app_reference'], $parent_v['booking_source'],'transfer_crs');
								?>
									<tr>
										<td><?=($current_record++)?></td>
<?php
						//				debug($parent_v);die;
?>
										<td><?php echo $app_reference;?></td> 
										<td><?=$parent_v['customer_details'][0]['first_name'].' '.$parent_v['customer_details'][0]['last_name']?></td>
										<td><?=$parent_v['itinerary_details'][0]['travel_from']?></td>
										<td><?=$parent_v['itinerary_details'][0]['location']?></td>
										<td><?php echo $net_fare+$admin_markup ?></td>
										<td><?php echo $agent_markup+$parent_v['itinerary_details'][0]['search_level_markup']?></td>
										<td><?php echo ($gst)?></td>
										<td><?php echo $product_total_price-$parent_v['reward_amount'] ?></td>
										<td><?php echo app_friendly_absolute_date($parent_v['date_of_travel'])?></td>
										<td><?php echo $voucher_date?></td>
										<td><?=$parent_v['itinerary_details'][0]['driver_name']?></td>
										<td><?=$parent_v['itinerary_details'][0]['driver_contact_number']?></td>
										<td><?=$parent_v['itinerary_details'][0]['customer_remarks']?></td>
										<td><span class="<?php echo booking_status_label($status) ?>"><?php echo $status?></span></td>
										 <td><?php  
							               if(($final_cancel_date == '0000-00-00 00:00:00')||($final_cancel_date == ' ')||($final_cancel_date == '0')){
							                $cancel_date = "-";
							               }else{
							                $cancel_date = date('d-M-y', strtotime($final_cancel_date));
							               }
							                echo $cancel_date;
							             ?></td>

										<?php if($emulate_booking == 1){ ?>
											<td><a href="<?php echo base_url().'index.php/report/staff_details/'.$emulate_user ?>" data-toggle="modal" data-target="#view_modal" >Click here</a></td>
										<?php }?>


										<td>
											<div class="" role="group"><?php echo $action; ?></div>
											<!-- <div class="dropdown2" role="group">
											 <div class="dropdown slct_tbl pull-left sideicbb">
											   <i class="fa fa-ellipsis-v"></i>  
											    <ul class="dropdown-menu sidedis" style="display: none;left:30px;">
												   <li>
						   	                         <?php echo $action; ?>
						   	                       </li>
						   	                    </ul>
						   	                 </div>
						   	                </div> -->

										</td>
										

									</tr>
								<?php
								}
							} else {
								echo '<tr><td>No Data Found</td></tr>';
							}
						?>
						
					</table>
				</div>
			</div>
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
	//update-source-status update status of the booking from api
	$(document).on('click', '.update-source-status', function(e) {
		e.preventDefault();
		$(this).attr('disabled', 'disabled');//disable button
		var app_ref = $(this).data('app-reference');
		$.get(app_base_url+'index.php/transfers/get_booking_details/'+app_ref, function(response) {
			
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
			      
						var _opp_url = app_base_url+'index.php/voucher/transfer_crs/';
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
var dis1 = document.getElementById("created_datetime_from");
	dis1.onchange = function () {
   if (this.value != "" || this.value.length > 0) {
      document.getElementById("created_datetime_to").disabled = false;
   }
}
</script>
<?php


function transfer_voucher_email($app_reference, $booking_source,$status,$recipient_email)
{

	return '<a class="btn btn-sm btn-primary send_email_voucher" data-app-status="'.$status.'"   data-app-reference="'.$app_reference.'" data-booking-source="'.$booking_source.'"data-recipient_email="'.$recipient_email.'"><i class="far fa-envelope"></i> Email Voucher</a>';
}
function get_cancellation_details_button($app_reference, $booking_source, $master_booking_status)
{
	
	if($master_booking_status == 'BOOKING_CANCELLED'){

		return '<a target="_blank" href="'.base_url().'index.php/transfer/cancellation_refund_details?app_reference='.$app_reference.'&booking_source='.$booking_source.'&status='.$master_booking_status.'" class="col-md-12 btn btn-sm btn-info "><i class="far fa-info"></i> Cancellation Details</a>';
	}

	
}
function get_message_details($app_reference, $booking_source,$type)
{
	
		return '<a class="btn btn-sm btn-primary flight_u" target="_blank" href="'.base_url().'message/send_message_view?app_reference='.$app_reference.'&booking_source='.$booking_source.'&type='.$type.'"><i class="far fa-envelope"></i> Send Message</a>';
}
function transfer_voucher($app_reference, $booking_source, $status)
{
  return '<a href="'.transfer_voucher_url($app_reference, $booking_source, $status).'" target="_blank" class="btn btn-sm btn-primary"><i class="fa fa-file"></i> Voucher</a>';

}
function transfer_voucher_url($app_reference, $booking_source='', $status='')
{
  return base_url().'index.php/voucher/transfer_crs/'.$app_reference.'/'.$booking_source.'/'.$status.'/show_voucher';
}
function transfer_view($app_reference, $booking_source, $status)
{
  return '<a href="'.transfer_view_url($app_reference, $booking_source, $status).'" target="_blank" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i>View</a>';

}
function transfer_view_url($app_reference, $booking_source='', $status='')
{
  return base_url().'index.php/voucher/transfer_crs/'.$app_reference.'/'.$booking_source.'/'.$status.'/show_view';
}
function transfer_invoice($app_reference, $booking_source, $status)
{
  return '<a href="'.transfer_invoice_url($app_reference, $booking_source, $status).'" target="_blank" class="btn btn-sm btn-primary"><i class="fa fa-file"></i> Invoice</a>';

}
function transfer_invoice_url($app_reference, $booking_source='', $status='')
{
  return base_url().'index.php/voucher/transfer_crs/'.$app_reference.'/'.$booking_source.'/'.$status.'/show_invoice';
}
function transfer_pdf($app_reference, $booking_source, $status)
{
  return '<a href="'.transfer_pdf_url($app_reference, $booking_source, $status).'" target="_blank" class="btn btn-sm btn-primary"><i class="far fa-file-pdf"></i> PDF</a>';

}
function transfer_pdf_url($app_reference, $booking_source='', $status='')
{
  return base_url().'index.php/voucher/transfer_crs/'.$app_reference.'/'.$booking_source.'/'.$status.'/show_pdf';
}
function cancel_transfer_booking($app_reference, $booking_source, $status)
{
  return '<a href="'.cancel_transfer_booking_url($app_reference, $booking_source, $status).'" target="_blank" class="btn btn-sm btn-primary"><i class="fa fa-times"></i> Cancel</a>';

}
function cancel_transfer_booking_url($app_reference, $booking_source='', $status='') 
{
  return base_url().'index.php/transfer/pre_cancellation/'.$app_reference.'/'.$booking_source.'/'.$status;
}
?>
