<div class="bodyContent col-md-12">
	<div class="panel panel-default clearfix"><!-- PANEL WRAP START -->
			<div class="panel-heading"><!-- PANEL HEAD START -->
			<div class="panel-title">
				<ul id="myTab" role="tablist" class="nav nav-tabs  ">
					<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->
										
					<li class="active" role="presentation"><a  href="<?=base_url()?>report/flight"><i class="fa fa-plane"></i> Flight Booking</a></li>
					<li class="active" role="presentation"><a  href="<?=base_url()?>report/flight"><i class="fa fa-bus"></i> Bus Booking</a></li>
					<li class="active" role="presentation"><a  href="#"><i class="fa fa-bed"></i> Hotel Booking</a></li>
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
							<th>Confirmation/Reference</th>
							<th>Total Fare</th>
							<th>Payment Mode</th>
							<th>Customer</th>
							<th>Booking</th>
							<th>Check-In</th>
							<th>Hotel</th>
							<th>Booked On</th>
							<th>Action</th>
						</tr>
						<?php
							if (isset($table_data) == true and valid_array($table_data)) {
								$segment_3 = $GLOBALS['CI']->uri->segment(3);
								$current_record = (empty($segment_3) ? 0 : $segment_3);
								foreach($table_data as $k => $v) {
									extract($v);
									//get cancel button only if check in date has not passed and api cancellation is active
									//ONLY AOT IS ACTIVE FOR CANCELLATION
									$action = '';
									if ($v['booking_source'] == TBO_HOTEL_BOOKING_SOURCE) {
										if (strtotime($v['hotel_check_in']) > time() AND ($v['status'] == BOOKING_PENDING || $v['status'] == BOOKING_VOUCHERED || $v['status'] == BOOKING_CONFIRMED || $v['status'] == BOOKING_HOLD)) {
											//$action .= get_accomodation_cancellation($api_code, $v['reference']);
										}
									}
									$action .= hotel_voucher($v['app_reference'], $booking_source, $v['status']);
								?>
									<tr>
										<td><?=($current_record+$k+1)?></td>
										<td><?=$app_reference;?></td>
										<td><span class="<?=booking_status_label($status) ?>"><?=$status?></span></td>
										<td class=""><span><?php echo 'Conf:'.$confirmation_reference?></span><br><span><?php echo 'Ref:'.$booking_reference?></span></td>
										<td><?php echo $currency.':'.($total_fare+$level_one_markup+$domain_markup)?></td>
										<td><?php echo $payment_name?></td>
										<td><?php echo $name.'<br>Email:'.$email?><br><?php echo 'P:'.$phone_number?></td>
										<td><?php echo app_friendly_absolute_date($hotel_check_in)?> to <?php echo app_friendly_date($hotel_check_out)?></td>
										<td><?php echo $total_passengers?> Pax, <?php echo $v['total_rooms']?> <?=(intval($total_rooms) > 1 ? 'Rooms' : 'Room' )?></td>
										<td><?php echo $hotel_name?></td>
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

<?php
function get_accomodation_cancellation($courseType, $refId)
{
	return '<a href="'.base_url().'booking/accomodation_cancellation?courseType='.$courseType.'&refId='.$refId.'" class="col-md-12 btn btn-sm btn-danger "><i class="fa fa-exclamation-triangle"></i> Cancel</a>';
}
?>