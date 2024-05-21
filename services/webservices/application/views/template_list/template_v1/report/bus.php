<div class="bodyContent col-md-12">
	<div class="panel panel-default clearfix"><!-- PANEL WRAP START -->
			<div class="panel-heading"><!-- PANEL HEAD START -->
			<div class="panel-title">
				<ul id="myTab" role="tablist" class="nav nav-tabs  ">
					<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->
										
					<li class="active" role="presentation"><a  href="<?=base_url()?>report/flight"><i class="fa fa-plane"></i> Flight Booking</a></li>
					<li class="active" role="presentation"><a  href="#"><i class="fa fa-bus"></i> Bus Booking</a></li>
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
							<th>PNR/Ticket</th>
							<th>Total Fare</th>
							<th>Payment Mode</th>
							<th>Customer</th>
							<th>Booking</th>
							<th>journey</th>
							<th>Operator</th>
							<th>Booked On</th>
							<th>Action</th>
						</tr>
						<?php
							if (isset($table_data) == true and valid_array($table_data)) {
								$segment_3 = $GLOBALS['CI']->uri->segment(3);
								$current_record = (empty($segment_3) ? 0 : $segment_3);
								foreach($table_data as $k => $v) {
									//get cancel button only if check in date has not passed and api cancellation is active
									//ONLY AOT IS ACTIVE FOR CANCELLATION
									$api_code = '';
									$action = '';
									if ($v['booking_source'] == TRAVELYAARI_BUS_BOOKING_SOURCE) {
										$api_code = TRAVELYAARI_BUS_BOOKING_SOURCE;
										if (strtotime($v['departure_datetime']) > time() AND ($v['status'] == BOOKING_CONFIRMED || $v['status'] == BOOKING_HOLD)) {
											//$action .= get_accomodation_cancellation($api_code, $v['reference']);
										}
									}
									if (empty($api_code) == false) {
										$action .= bus_voucher($v['app_reference'], $api_code, $v['status']);
									}
									$customer = explode(DB_SAFE_SEPARATOR, $v['name']);
								?>
									<tr>
										<td><?php echo ($current_record+$k+1)?></td>
										<td><?php echo $v['app_reference'];?></td>
										<td><span class="<?php echo booking_status_label($v['status']) ?>"><?php echo $v['status']?></span></td>
										<td class=""><span><?php echo 'PNR:'.$v['pnr']?></span><br><span><?php echo 'Tick:'.$v['ticket']?></span></td>
										<td><?php echo $v['currency'].':'.($v['total_fare']+$v['level_one_markup']+$v['domain_markup'])?></td>
										<td><?php echo $v['payment_name']?></td>
										<td><?php echo $customer[0].'<br>Email:'.$v['email']?><br><?php echo 'P:'.$v['phone_number']?><br><?php echo 'O:'.$v['alternate_number']?></td>
										<td><?php echo $v['departure_from']?>-<?php echo $v['arrival_to']?><br>(<?php echo $v['total_passengers']?> <?=(intval($v['total_passengers']) > 1 ? 'tickets' : 'ticket' )?>)</td>
										<td><?php echo app_friendly_datetime($v['departure_datetime'])?></td>
										<td><?php echo $v['operator']?></td>
										<td><?php echo app_friendly_absolute_date($v['created_datetime'])?></td>
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