<?php //debug($data); exit; ?>
<div class="panel">	
<?php

	if (isset ( $data ['offline_cancel'] ) && valid_array ( $data ['offline_cancel'] ) && $data ['offline_cancel']['refund_status'] == 'INPROGRESS') { 
		$booking_details = $data ['booking_details'] [0];
		$convinence = $booking_details ['convinence_amount'];
		//debug($convinence);exit;
		$offline_cancel = $data ['offline_cancel'];

		$offline_cancel['cancel_pax_details'] = json_decode($offline_cancel['cancel_pax_details'],true);
	//	debug($booking_details); exit;
		extract ( $booking_details );
		$default_view ['default_view'] = $GLOBALS ['CI']->uri->segment ( 1 );
		$pax_cancelled = array();
		
		$total_price_attributes = $grand_total;
	//	debug($total_price_attributes); exit;
		$offline_cancel = ($offline_cancel);
		//foreach($offline_cancel['cancel_pax_details'] as $trrip => $cancel_v){
			foreach ($offline_cancel['cancel_pax_details'] as $pax_o => $v_d) {
				$pax_cancelled[$pax_o] = $v_d;
			}
			
		//}
		//debug($pax_cancelled);exit;
		$booking_transaction_details = $booking_details['booking_transaction_details'];
			//debug(get_enum_list('offline_cancel_status_options'));exit;
?>
		<style>
		input[type=checkbox], input[type=radio] {
			position: relative !important;
			left: 0 !important;
		}
		</style>
		<div class="panel-body">
		<div class="search-result">			
		    <div class="bakrd_color">
					<div class="cetrel_all">
		        <?php //echo $GLOBALS['CI']->template->isolated_view('share/navigation', $default_view) ?>
		      </div>
					<div class="clearfix"></div>
					<div class="cancellation_page">
						<div class="head_can">
							<h3 class="canc_hed">Cancellation</h3>
							<div class="ref_number">
								<div class="rows_cancel">
									Booking ID: <strong><?=$app_reference?></strong>
								</div>
								<div class="rows_cancel">Booking Date: <?=$booked_date?></div>
								<div class="rows_cancel">Cancel Requested Date: <?=date('d-M-Y',strtotime($offline_cancel['created_datetime']))?></div>
							</div>
						</div>
						<div class="clearfix"></div>						
						<div class="cancel_bkd">
							<form method="post" action="<?=base_url().'index.php/flight/update_offline_cancel_request/'.$offline_cancel['RequestId'];?>">
								<input type= 'hidden' name="_fcr_origin" value="<?=$offline_cancel['origin']?>">
								<input type= 'hidden' name="app_reference" value="<?=$app_reference?>">
								<?php
								define ( 'FLOAT_PRECESSION', 2 );
							
			foreach ( $booking_itinerary_details as $itinerary_k => $itinerary_v ) {

									$transction_key = $itinerary_v ['flight_booking_transaction_details_fk'];
									extract ( $itinerary_v );
								
								 	$total_fares = $booking_transaction_details[$itinerary_k] ['total_fare'];
								 	$admin_markup = $booking_transaction_details[$itinerary_k] ['admin_markup'];
								 
								 	//echo $airline_name; exit;
									?>
							<div class="col-xs-12" style="padding:0px 10px; background:#fafafa; border:1px solid #ddd;">
								<div class="col-xs-3 nopad">
									<div class="pad_evry">
										<div class="imge_can">
											<img class="airline-logo"
												src="<?=SYSTEM_IMAGE_DIR.'airline_logo/'.$airline_code.'.gif'?>"
												alt="Flight Image">
										</div>
										<div class="can_flt_name"><?=$airline_name?><strong><?=$airline_code?> <?=$flight_number?></strong>
										</div>
									</div>
								</div>
								<div class="col-xs-3 nopad">
									<div class="pad_evry">
										<span class="place_big_text"><?=$from_airport_code?></span> <span
											class="place_smal_txt"><?=$from_airport_name?></span> <span
											class="date_mension"><?=app_friendly_datetime($departure_datetime)?></span>
									</div>
								</div>
								<div class="col-xs-1 nopad">
									<div class="pad_evry">
										<div class="aroow_can fa fa-long-arrow-right"></div>
									</div>
								</div>
								<div class="col-xs-3 nopad">
									<div class="pad_evry">
										<span class="place_big_text"><?=$to_airport_code?></span> <span
											class="place_smal_txt"><?=$to_airport_name?></span> <span
											class="date_mension"><?=app_friendly_datetime($arrival_datetime)?></span>
									</div>
								</div>
								</div>
									
								<table style="width: 100%; background:#f5f5f5; margin-bottom:15px; border:1px solid #ddd;">
                                                    <tr>
                                                        <td
                                                            style="padding: 5px 10px; width: 50%; text-align: left;  color: #333; border-bottom: 1px solid #ccc;"><strong>Fare Details</strong></td>
                                                        <td
                                                            style="padding: 5px 10px; font-weight: normal; text-align: right; width: 50%; color: #555;  border-bottom: 1px solid #ccc;">Amount
                                                            (INR)</td>
                                                    </tr>

                                                    <tr>
                                                        <td
                                                            style="padding: 1px 10px; width: 50%; text-align: left; background: #fff; color: #555; font-weight: normal;">
                                                            Fare</td>
                                                        <td
                                                            style="padding: 1px 10px; width: 50%; text-align: right; color: #555; background: #fff; font-weight: normal;"><?= number_format(round($total_fares), FLOAT_PRECESSION) ?></td>
                                                    </tr>

                                                    <tr>
                                                        <td
                                                            style="padding: 1px 10px; width: 50%; text-align: left; background: #fff; color: #555; font-weight: normal;">Admin Markup
                                                        </td>
                                                        <td
                                                            style="padding: 1px 10px; width: 50%; text-align: right; color: #555; background: #fff; font-weight: normal;"><?= number_format($admin_markup, FLOAT_PRECESSION) ?></td>
                                                    </tr>
                                                 
        <?php if (isset($transaction_fares['total_breakup']['meal_and_baggage_fare']) && $transaction_fares['total_breakup']['meal_and_baggage_fare'] > 0) { ?>
                                                        <tr>
                                                            <td
                                                                style="padding: 1px 10px; width: 50%; text-align: left; background: #fff; color: #555; font-weight: normal;">Meals & baggage charges</td>
                                                            <td
                                                                style="padding: 1px 10px; width: 50%; text-align: right; color: #555; background: #fff; font-weight: normal;"><?= number_format($transaction_fares['total_breakup']['meal_and_baggage_fare'], FLOAT_PRECESSION) ?></td>
                                                        </tr>
        <?php } ?>
                                                    <tr>
                                                        <td
                                                            style="padding: 1px 10px; width: 50%; text-align: left; background: #fff; color: #555; font-weight: normal;">Convenience Charges</td>
                                                        <td
                                                            style="padding: 1px 10px; width: 50%; text-align: right; color: #555; background: #fff; font-weight: normal;"><?= number_format($convinence, FLOAT_PRECESSION) ?></td>
                                                    </tr>

                                                    <tr>
                                                        <td
                                                            style="padding: 5px 10px; width: 50%; text-align: left;  color: #555; border-top: 1px solid #ccc; font-weight: 600;">Total</td>
                                                        <td
                                                            style="padding: 5px 10px; width: 50%; text-align: right; color: #555;  border-top: 1px solid #ccc; font-weight: 600;"><?= number_format(round($total_fares +$admin_markup+ $convinence), FLOAT_PRECESSION) ?></td>
                                                    </tr>
                                                </table>
                                                
								<!--					<div class="col-xs-2 nopad">-->
								<!--						<div class="pad_evry">-->
								<!--							<div class="btn_continue">-->
								<!--								<button data-toggle="modal" data-target="#confirm_cancel<?=@$itinerary_k?>" class="b-btn bookallbtn" type="button">Cancel</button>-->
								<!--							</div>-->
								<!--						</div>-->
								<!--					</div>-->

								<!-- Confirm Cancealltion Starts-->
								<div class="modal fade" tabindex="-1" role="dialog"
									id="confirm_cancel<?=@$itinerary_k?>">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal"
													aria-label="Close">
													<span aria-hidden="true">&times;</span>
												</button>
												<h4 class="modal-title">Do you want to cancel the Booking?</h4>
											</div>
											<!--<div class="modal-body"></div>-->
											<div class="modal-footer">
												<button type="button" class="btn btn-default"
													data-dismiss="modal">No</button>
												<a
													href="<?=base_url().'index.php/flight/cancel_booking/'.$app_reference.'/'.$booking_source.'/'.$transction_key?>"
													class="btn btn-danger">Yes</a>
											</div>
										</div>
										<!-- /.modal-content -->
									</div>
									<!-- /.modal-dialog -->
								</div>
								<!-- /.modal -->
								<!-- Confirm Cancealltion Ends-->
								<?php } ?>
								<div class="clearfix"></div>
								<div class="ritside_can col-xs-12 nopad">							
									<div class="btn_continue">
										<div class="amnt_disply">
											Total Amount Paid: <span class="amnt_paid" style="color: #FB5D00;"><?=$currency?> <?=round($grand_total);?></span>
										</div>
									</div>									
								</div>					
								<div class="clearfix"></div>
								<div class="row_can_table hed_table">
									<div class="col-xs-2 nopad">
										<div class="can_pads">Passenger Name</div>
									</div>
									<div class="col-xs-2 nopad">
										<div class="can_pads">Type</div>
									</div>
									<div class="col-xs-2 nopad">
										<div class="can_pads">PNR</div>
									</div>
								<!-- 	<div class="col-xs-2 nopad">
									<div class="can_pads">TicketID</div>
								</div> -->
									<div class="col-xs-2 nopad">
										<div class="can_pads">Status</div>
									</div>
									<div class="col-xs-1 nopad">
										<div class="can_pads"><?=$booking_details['from_loc'].' - '.$booking_details['to_loc'] ?></div>
									</div>
									<?php if(strcmp($booking_details['trip_type'],'circle') == 0){ ?> 
									<div class="col-xs-1 nopad">
										<div class="can_pads"><?=$booking_details['to_loc'].' - '.$booking_details['from_loc'] ?></div>
									</div>
									<?php } ?>
								</div>
								<?php 
			 foreach($booking_transaction_details as $transaction_k => $transaction_v) {
									       extract($transaction_v);
								?>
								<?php
								//debug($booking_customer_details); exit;
			foreach ( $booking_customer_details as $customer_k => $customer_v ) {
			
									extract ( $customer_v );
									$status = str_replace ( 'BOOKING_', '', $status );
									$pax_name = get_enum_list ( 'title', $title ) . ' ' . $first_name . ' ' . $last_name;
									// debug($pax_cancelled);exit;
									//$pax_details = json_decode ( $pax_details, true );
									
									?>        
								<div class="row_can_table">
									<div class="col-xs-2 nopad">
										<div class="can_pads text-uppercase"><?=$pax_name?></div>
									</div>
									<div class="col-xs-2 nopad">
										<div class="can_pads"><?=$passenger_type?></div>
									</div>
									<div class="col-xs-2 nopad">
										<div class="can_pads"><?=$pnr?></div>
									</div>
								<!-- 	<div class="col-xs-2 nopad">
									<div class="can_pads"><?=@$ticket_no?></div>
								</div> -->
									<div class="col-xs-2 nopad">
										<div class="can_pads"><?=$status?></div>
									</div>
									<div class="col-xs-1 nopad">
										<div class="can_pads">
											<?php if(isset($pax_cancelled[$origin])) {
												$disabled = isset($origin)?'':'disabled';
											 ?>
											<input type="checkbox" name="cancel_pax_origin[]"
												id="tpax_origin_<?=$origin ?>"
												value="<?=$origin ?>" checked <?=$disabled?>>
											<?php } else { echo ''; } ?>
										</div>
									</div>
									<?php if(strcmp($booking_details['trip_type'],'circle') == 0){ ?> 
									<div class="col-xs-1 nopad">
										<div class="can_pads">
											<?php if(isset($pax_cancelled[$origin])) { 
												$disabled = isset($origin)?'':'disabled';
											?>
											<input type="checkbox" name="cancel_pax_origin[]"
												id="tpax_origin_<?=$origin ?>"
												value="<?=$origin ?>" checked <?=$disabled?>>
											<?php } else { echo ''; } ?>
										</div>
									</div>
									<?php } ?>
								</div>
								<?php  } ?>
								<?php } ?>
							
								<div class="clearfix"></div>						
								<div class="form-group">
									<label class="col-md-12">Reason</label>
									<div class="col-md-12"><?=$offline_cancel['reason']?></div>
								</div>
								<div class="form-group">
								<div class="row">
									<div class="col-md-6">
										<label>Refundable Amount</label>
										<div>
											<input type="integer" name="amount_refund"
												class="form-control numeric" value="<?=$offline_cancel['API_RefundedAmount']?>">
										</div>
									</div>
									<div class="col-md-6">
										<label>Cancellation Charge</label>
										<div>
											<input type="integer" name="cancellation_charge"
												class="form-control numeric" value="<?=$offline_cancel['API_CancellationCharge']?>">
										</div>
									</div>
								</div>
								</div>
								<div class="form-group">
									<label class="col-md-12">Refund Status</label>
									<div class="col-md-12">
										<select name="status" class="form-control" required>
											<?=generate_options(get_enum_list('offline_cancel_status_options'), $offline_cancel['current_status'])?>
										</select>
									</div>
								</div>
								<div class="clearfix"></div>
								<div class="col-xs-12 nopad">			
									
								<div class="btn_continue">
									<button class="btn btn-danger" type="submit">Edit Request</button>
								</div>
									
								</div>
							</form>
						</div>
							
					</div>
				</div>
			
		</div>
		</div>
		<div class="clearfix"></div>						
		<?php } ?>
		<div class="panel-body">
		<table class="table table-bordered">
			<tbody>
				<tr>
					<td>#</td>
					<td>Agency Name</td>
					<td>App Reference</td>
					<td>Requested Date</td>
					<td>Status</td>
					<td>Amount Refund</td>
					<td>Cancellation Charge</td>
					<td>Remark</td>
					<td>Action</td>
				</tr>
				<?php foreach($offline_cancel_requests as $request_k => $request_v ){ 				
				?>
				<tr>
					<td><?=$request_k+1?></td>
					<td><?php if(!empty($request_v['agency_name'])){ 
						echo $request_v['agency_name'];
						}
						else{
							echo $request_v['first_name'].' '.$request_v['last_name'];
							}?></td>
					<td><?=$request_v['app_reference']?></td>
					<td><?=date('d-M-Y',strtotime($request_v['created_datetime']))?></td>
					<td><?php if($request_v['refund_status'] == 'INPROGRESS'){echo 'INPROGRESS';} else {echo $request_v['refund_status']; }?></td>
					<td><?=$request_v['API_RefundedAmount']?></td>
					<td><?=$request_v['API_CancellationCharge']?></td>
					<td><?=$request_v['refund_comments']?></td>					
					<td>
					<?php if( $request_v['refund_status'] =='INPROGRESS') { ?>
						<a href="<?php echo base_url().'index.php/flight/offline_cancel_request/'.$request_v['RequestId'] ?> ">Update</a>
					<?php } ?>
					
					<?php if($request_v['refund_status'] == 'PROCESSED') { ?>
						 <a href="<?php echo base_url().'index.php/voucher/receipt/'.$request_v['app_reference'] ?> ">Receipt</a>
					<?php } ?>
					
					</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
</div>


