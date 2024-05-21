

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

				

				<form method="GET" autocomplete="off" action="<?= base_url().'report/b2b_transfers_report_crs?'?>">

					

					<div class="clearfix form-group">

						<!-- <div class="col-xs-4">

							<label>

							From

							</label>

							<input type="text" id="from_date" class="form-control" name="from_date" placeholder="From Date" value="<?=@$from_date?>">

						</div>

						<div class="col-xs-4">

							<label>

							To

							</label>

							<input type="text" id="to_date" class="form-control disable-date-auto-update" name="to_date" placeholder="To Date" value="<?=@$to_date?>">

						</div> -->

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

		                       <option value="">All</option>

		                       	<?=generate_options(get_enum_list('report_filter_status'),(array)@$_GET['status']);?>

		                    </select>

						</div>

						<div class="col-xs-4">

							<label>

							Booked From Date

							</label>

							<input type="text" readonly id="created_datetime_from" class="form-control" name="created_datetime_from" value="<?=@$from_date?>" placeholder="Request Date">

						</div>

						<div class="col-xs-4">

							<label>

							Booked To Date

							</label>

							<input type="text" readonly id="created_datetime_to" class="form-control disable-date-auto-update" name="created_datetime_to" value="<?=@$to_date?>" placeholder="Request Date">

						</div>

					</div>

					<div class="col-sm-12 well well-sm">

					<button class="btn btn-success " type="submit">Search</button> 

					<button type="reset" id="btn-reset" class="btn btn-warning">Reset</button>

					<a href="<?=base_url().'report/b2b_transfers_report_crs?' ?>" id="clear-filter" class="btn btn-danger">Clear Filter</a>

					</div>

					

				</form>

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

                            <a href="<?php echo base_url(); ?>index.php/report/export_confirmed_booking_transfer_report_crs/excel<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>">Confirmed Booking</a>

                        </li>

                        <li>

                            <a href="<?php echo base_url(); ?>index.php/report/export_cancelled_booking_transfer_report_crs/excel<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>">Cancelled Booking</a>

                        </li>

                    </ul>

                </div>

            

            <?php } ?>

            <!-- EXCEL/PDF EXPORT ENDS -->

			

		</div>

		

		<div class="clearfix table-responsive"><!-- PANEL BODY START -->

					<div class="pull-left">

						<?php echo $GLOBALS['CI']->pagination->create_links();?> <span class="">Total <?php echo $total_rows ?> Bookings</span>

					</div> 

					

									<table class="table table-condensed table-bordered" id="b2b_report_airline_table">

						<thead>

							<tr>

							<th>Sno</th>

							

							<th>Application Reference</th> 

							<th>Agency Name</th>

							<th>Customer<br/>Name</th>

							<th>Travel From</th>

							<th>Travel To</th>	

							<th>Admin<br/>Fare</th>						

							<th>Agent Net Fare</th>

							<th>Agent Markup<br/>(Instant+Agent)</th>

							<th>Admin<br/>Markup</th>

							<th>VAT</th>

							<th>Convenience Fee</th>

							<th>TotalFare</th>

							<th>TravelDate</th>

							<th>BookedOn</th>

							<th>Driver Name</th>

							<th>Driver Contact<br/>Number</th>

							<th>Customer Remarks</th>

							<th>Status</th>

							<th>Staff Emulate</th>

							<th>Cancelled Date</th>

							<th>Booked<br/>By</th>
							<th>Action</th>

							</tr>

						</thead>

						<tfoot>

							<tr>

							<th>Sno</th>

							

							<th>Application Reference</th> 

							<th>Agency Name</th>

							<th>Customer<br/>Name</th>

							<th>Travel From</th>

							<th>Travel To</th>	

							<th>Admin<br/>Fare</th>							

							<th>Agent Net Fare</th>

							<th>Agent<br/>Markup</th>

							<th>Admin<br/>Markup</th>

							<th>VAT</th>

							<th>Convenience Fee</th>

							<th>TotalFare</th>

							<th>TravelDate</th>

							<th>BookedOn</th>

							<th>Driver Name</th>

							<th>Driver Contact<br/>Number</th>

							<th>Customer Remarks</th>

							<th>Status</th>

							<th>Staff Emulate</th>

							<th>Cancelled Date</th>

							<th>Booked<br/>By</th>
							<th>Action</th>

							</tr>

						</tfoot><tbody>

						<?php

							if(valid_array($table_data['booking_details']) == true) {

								// debug($table_data['booking_details']);exit;

				        		$booking_details = $table_data['booking_details'];

								$segment_3 = $GLOBALS['CI']->uri->segment(3);

								$current_record = (empty($segment_3) ? 1 : $segment_3);

					        	foreach($booking_details as $parent_k => $parent_v) {

					        		// debug($parent_v);exit;

					        		extract($parent_v);

					        		// debug($parent_v['customer_details']['status']);exit;

									$action = '';

									$cancellation_btn = '';

									$voucher_btn = '';

									$invoice_btn = '';

									$vat_invoice_btn = '';

									$status_update_btn = '';

									$booked_by = '';

									$cancel_btn='';

									$view_btn = '';

									$view_details='';

									$status=$parent_v['customer_details'][0]['status'];



									//Status Update Button

									if ($status=='BOOKING_HOLD'){



										$status_update_btn = '<button class="btn btn-success btn-sm update-source-status" data-app-reference="'.$app_reference.'"><i class="far fa-database"></i> Update Status</button>';

									}

									

									$voucher_btn = transfers_crs_voucher($app_reference, $booking_source, $status);

									$invoice_btn = transfers_crs_invoice($app_reference, $booking_source, $status);

									$vat_invoice_btn = transfers_crs_vat_invoice($app_reference, $booking_source, $status);

									$amendment_btn = transfers_amendment($app_reference, $booking_source, $status);

									$view_btn = transfers_booking_view($app_reference, $booking_source, $status);

									$pdf_btn = transfers_pdf_crs($app_reference, $booking_source, $status);

									$view_details = transfer_booking_details($app_reference, $booking_source, $status);

									

									if($status=='BOOKING_CONFIRMED'){



										$cancel_btn = cancel_booking($app_reference, $booking_source, $status);

									}

									

									$email_btn = transfer_voucher_email($app_reference, $booking_source,$status,provab_decrypt($parent_v['email']));



									$jrny_date = date('Y-m-d', strtotime($parent_v['date_of_travel']));

									$tdy_date = date ( 'Y-m-d' );

									$diff = get_date_difference($tdy_date,$jrny_date);

									$action .= $voucher_btn;

									//$action .= $amendment_btn;

									$action .= $view_btn;

									$action .=$pdf_btn;

									$action .= $email_btn;

									$action .= $invoice_btn;

									$action .=  $view_details;

									$action .= $vat_invoice_btn;

									$action .= $status_update_btn;

									if($diff > 0){

										$action .= $cancel_btn;

									}

									$action .= get_cancellation_details_button($parent_v['app_reference'], $parent_v['booking_source'], $parent_v['status']);

									//$message=check_message_test($app_reference,'activities');

				                      if($message)

				                      {

				                      //  $action .=view_mesage_history($message);

				                      }

								?>

									<tr>

										<td><?=($current_record++)?></td>

										

										<td><?php echo $app_reference;?></td> 

										<td><?php echo $parent_v['agent_agency_name'];?></td>

										<td><?=$parent_v['customer_details'][0]['first_name'].' '.$parent_v['customer_details'][0]['last_name']?></td>

										<td><?=$parent_v['itinerary_details'][0]['travel_from']?></td>

										<td><?=$parent_v['itinerary_details'][0]['location']?></td>

										<td><?=$parent_v['itinerary_details'][0]['total_fare']?></td>

										<td><?php echo $parent_v['itinerary_details'][0]['total_fare']+$parent_v['itinerary_details'][0]['admin_markup']?></td>

										<td><?php echo $agent_markup+$parent_v['itinerary_details'][0]['search_level_markup']?></td>

										<td><?php echo $admin_markup?></td>

										<td><?php echo ($gst)?></td>

										<td><?php echo $parent_v['itinerary_details'][0]['convenience_fee']?></td>

										<td><?php echo $basic_fare?></td>

										<td><?php echo app_friendly_absolute_date($parent_v['date_of_travel'])?></td>

										<td><?php echo $voucher_date?></td>

										<td><?=$parent_v['itinerary_details'][0]['driver_name']?></td>

										<td><?=$parent_v['itinerary_details'][0]['driver_contact_number']?></td>

										<td><?=$parent_v['itinerary_details'][0]['customer_remarks']?></td>

										<td><span class="<?php echo booking_status_label($status) ?>"><?php echo $status?></span></td>

										<td><?=$parent_v['emulate_user_name']?></td>

										<td><?php  

					                       //debug($final_cancel_date);exit;

					                       if(($final_cancel_date == '0000-00-00 00:00:00')||($final_cancel_date == ' ')||($final_cancel_date == '0')){

					                        $cancel_date = "-";

					                       }else{

					                        $cancel_date = date('d-m-Y', strtotime($final_cancel_date));

					                       }

					                        echo $cancel_date;

					                     ?></td>

					                     <?php if($parent_v['user_id'] != $this->entity_user_id){ ?>

											<td><a href="<?php echo base_url().'index.php/report/staff_details/'.$parent_v['user_id'] ?>" data-toggle="modal" data-target="#view_modal" >Click here</a></td>

										<?php }else{?>

										

											<td><?='-';  ?></td>

										<?php } ?>

										<td>

										

													 

										<div class="action_system activity_second" role="group">
									<div class="dropdown">
										 <button class="dropbtn">
										 <i class="fa fa-ellipsis-v"></i>
										 </button>
										 <div class="dropdown-content">
                                           <?php echo $action; ?>
                                         </div>
										</div>
									
									
									</div>

											      

			                            </td>

								

								



										 

									</tr>

								<?php

								}

							} else {

								echo '<tr><td>No Data Found</td></tr>';

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

</script>



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

<?php

function transfers_crs_voucher($app_reference, $booking_source='', $status='',$module='')

{

	return '<a href="'.transfers_crs_voucher_url($app_reference, $booking_source, $status,$module).'/show_voucher" target="_blank" class="sideicbb1 sidedis"><i class="far fa-file"></i> Voucher</a>';

}

function transfers_crs_voucher_url($app_reference, $booking_source='', $status='',$module='')

{	

		return base_url().'index.php/voucher/transfer_crs/'.$app_reference.'/'.$booking_source.'/'.$status;

}

function transfers_crs_invoice($app_reference, $booking_source='', $status='',$module='')

{

	return '<a href="'.transfers_crs_invoice_url($app_reference, $booking_source, $status,$module).'/show_invoice" target="_blank" class="sideicbb1 sidedis"><i class="far fa-file"></i> Invoice</a>';

}

function transfers_crs_invoice_url($app_reference, $booking_source='', $status='',$module='')

{	

		return base_url().'index.php/voucher/transfer_crs/'.$app_reference.'/'.$booking_source.'/'.$status;

}



function transfers_crs_vat_invoice($app_reference, $booking_source='', $status='',$module='')

{

	return '<a href="'.transfers_crs_vat_invoice_url($app_reference, $booking_source, $status,$module).'/show_vat_invoice" target="_blank" class="sideicbb1 sidedis"><i class="far fa-file"></i>VAT Invoice</a>';

}

function transfers_crs_vat_invoice_url($app_reference, $booking_source='', $status='',$module='')

{	

		return base_url().'index.php/voucher/transfer_crs/'.$app_reference.'/'.$booking_source.'/'.$status;

}



function transfers_amendment($app_reference, $booking_source='', $status='',$module='')

{

	return '<a href="'.transfers_amendment_url($app_reference, $booking_source, $status,$module).'/show_amendment" target="_blank" class="sideicbb1 sidedis"><i class="far fa-file"></i> Amendment</a>';

}

function transfers_amendment_url($app_reference, $booking_source='', $status='',$module='')

{

	

		return base_url().'index.php/voucher/transfer_crs/'.$app_reference.'/'.$booking_source.'/'.$status;

}

function transfers_booking_view($app_reference, $booking_source='', $status='',$module='')

{

	return '<a href="'.transfers_booking_view_url($app_reference, $booking_source, $status,$module).'/show_details" target="_blank" class="sideicbb1 sidedis"><i class="far fa-eye"></i> View</a>';

}

function transfers_booking_view_url($app_reference, $booking_source='', $status='',$module='')

{

	

		return base_url().'index.php/voucher/transfer_crs/'.$app_reference.'/'.$booking_source.'/'.$status;

	

	

}

function transfers_pdf_crs($app_reference, $booking_source='', $status='', $module='')

{

	

	return '<a href="'.transfers_crs_voucher_url_crs($app_reference, $booking_source, $status, $module).'/show_pdf" target="_blank" class="sideicbb2 sidedis"><i class="far fa-file-pdf"></i> Pdf</a>';

}

function transfers_crs_voucher_url_crs($app_reference, $booking_source='', $status='')

{

	return base_url().'index.php/voucher/transfer_crs/'.$app_reference.'/'.$booking_source.'/'.$status;

}

function transfer_voucher_email($app_reference, $booking_source,$status,$recipient_email)

{



	return '<a class="sideicbb3 sidedis send_email_voucher" data-app-status="'.$status.'"   data-app-reference="'.$app_reference.'" data-booking-source="'.$booking_source.'"data-recipient_email="'.$recipient_email.'"><i class="far fa-envelope"></i> Email Voucher</a>';

}

function get_cancellation_details_button($app_reference, $booking_source, $master_booking_status)

{

	

	if($master_booking_status == 'BOOKING_CANCELLED'){



		return '<a target="_blank" href="'.base_url().'index.php/transferv1/cancellation_refund_details?app_reference='.$app_reference.'&booking_source='.$booking_source.'&status='.$master_booking_status.'" class="col-md-12 btn btn-sm btn-info "><i class="far fa-info"></i> Cancellation Details</a>';

	}



	

}

function cancel_booking($app_reference, $booking_source, $status)

{

	// $confirm = "return confirm('Are you sure?')";

	return '<a href="'.cancel_booking_url($app_reference, $booking_source, $status).'"   onclick="'.$confirm.'" class="sidedis sideicbb4 "><i class="fa fa-arrows-alt"></i> Cancel</a>';



	



}



function cancel_booking_url($app_reference, $booking_source='', $status='')

{

	return base_url().'index.php/transfers/pre_cancellation/'.$app_reference.'/'.$booking_source.'/'.$status;

}



function transfer_booking_details($app_reference, $booking_source, $status)

{

  return '<a href="'.transfer_vat_booking_details_url($app_reference, $booking_source, $status).'/show_transfer_details" target="_blank" class="sideicbb3 sidedis"><i class="fa fa-file"></i>Transfer View Details</a>';



}

function transfer_vat_booking_details_url($app_reference, $booking_source='', $status='')

{

  return base_url().'index.php/voucher/transfer_crs/'.$app_reference.'/'.$booking_source.'/'.$status;

}

?>



