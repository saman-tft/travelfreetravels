<div class="bodyContent col-md-12">
	<div class="panel panel-default clearfix"><!-- PANEL WRAP START -->
			<div class="panel-heading"><!-- PANEL HEAD START -->
			<div class="panel-title">
				<ul id="myTab" role="tablist" class="nav nav-tabs  ">
					<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->
										
					<li class="active" role="presentation"><a  href="#"><i class="fa fa-plane"></i> Flight Booking</a></li>
					<li class="active" role="presentation"><a  href="<?=base_url()?>report/bus"><i class="fa fa-bus"></i> Bus Booking</a></li>
					<li class="active" role="presentation"><a  href="<?=base_url()?>report/hotel"><i class="fa fa-bed"></i> Hotel Booking</a></li>
					<li class="active" role="presentation"><a  href="<?=base_url()?>"><i class="fa fa-suitcase"></i> Package Booking</a></li>
					<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE END -->
				</ul>
				
			</div>
		</div><!-- PANEL HEAD START -->
		<div class="panel-body"><!-- PANEL BODY START -->
			<div class="tab-content">
				<div id="tableList" class="table-responsive">
					<div class="pull-right">
						<?php echo $this->pagination->create_links();?> <span class="">Total <?php echo $total_rows ?> Bookings</span>
					</div>
					<table class="table table-condensed table-bordered">
						<tr>
							<th>Sno</th>
							<th>Application Reference</th>
							<th>Status</th>
							<th>Total Fare</th>
							<th>Payment Mode</th>
							<th>Customer</th>
							<th>Booking</th>
							<th>Total Ticket(s)</th>
							<th>Booked On</th>
							<th>Action</th>
						</tr>
						<?php
							if (isset($table_data) == true and valid_array($table_data)) {
								$segment_3 = $GLOBALS['CI']->uri->segment(3);
								$current_record = (empty($segment_3) ? 0 : $segment_3);
								foreach($table_data as $k => $v) {
									extract($v);
									$action = '';
									$cancellation_btn = '';
									$voucher_btn = '';
									$status_update_btn = '';
									if (strtotime($v['journey_start']) > time() AND ($v['status'] == BOOKING_PENDING || $v['status'] == BOOKING_VOUCHERED || $v['status'] == BOOKING_CONFIRMED || $v['status'] == BOOKING_HOLD)) {
										//$cancellation_btn = get_accomodation_cancellation($api_code, $v['reference']);
									}

									//Status Update Button
									if (in_array($v['status'], array('BOOKING_CONFIRMED')) == false) {
										switch ($v['booking_source']) {
											case TBO_FLIGHT_BOOKING_SOURCE :
												$status_update_btn = '<button class="btn btn-success btn-sm update-source-status" data-app-reference="'.$v['app_reference'].'"><i class="fa fa-database"></i> Update Status</button>';
												break;
										}
									}

									$voucher_btn = flight_voucher($v['app_reference'], $booking_source, $v['status']);
									$action = $voucher_btn.$status_update_btn.$cancellation_btn;
								?>
									<tr>
										<td><?=($current_record+$k+1)?></td>
										<td><?=$app_reference;?></td>
										<td><span class="<?=booking_status_label($status) ?>"><?=$status?></span></td>
										<td><?php echo $currency.':'.($total_fare+$level_one_markup+$domain_markup)?></td>
										<td><?php echo $payment_name?></td>
										<td><?php echo $name.'<br>Email:'.$email?><br><?php echo 'P:'.$phone_number?></td>
										<td><strong><?php echo $journey_from.' to '.$journey_to?></strong><br><?php echo app_friendly_datetime($journey_start)?>-to-<?php echo app_friendly_datetime($journey_end)?></td>
										<td><?php echo $total_passengers?></td>
										<td><?php echo app_friendly_absolute_date($created_datetime)?></td>
										<td><div class="" role="group"><?php echo $action; ?></div></td>
									</tr>
								<?php
								}
							} else {
								echo '<tr><td colspan="12">No Data Found</td></tr>';
							}
						?>
						
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
		$.get('<?=base_url()?>flight/get_booking_details/'+app_ref, function(response) {
			console.log(response);
		});
	});
});
</script>
<?php
function get_accomodation_cancellation($courseType, $refId)
{
	return '<a href="'.base_url().'booking/accomodation_cancellation?courseType='.$courseType.'&refId='.$refId.'" class="col-md-12 btn btn-sm btn-danger "><i class="fa fa-exclamation-triangle"></i> Cancel</a>';
}
?>