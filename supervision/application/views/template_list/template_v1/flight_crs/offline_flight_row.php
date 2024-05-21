<div class="clearfix form-group">
	<div class="col-md-2">
		<input type="text" class="form-control" name="career_<?=@$trip_type;?>[]" placeholder="Carrier" required>
		<input type="text" class="form-control" name="booking_class_<?=@$trip_type;?>[]" placeholder="Booking Class">
	</div>
	<div class="col-md-2">
		<input type="text" class="form-control" name="flight_num_<?=@$trip_type;?>[]" placeholder="Flight No.">
	</div>
	<div class="col-md-2">
		<input type="text" class="form-control" name="dep_loc_<?=@$trip_type;?>[]" placeholder="Dep. Airport" required>
		<input type="text" class="form-control" name="arr_loc_<?=@$trip_type;?>[]" placeholder="Arr. Airport" required>
	</div>
	<div class="col-md-2">
		<input type="text" class="form-control" name="dep_date_<?=@$trip_type;?>[]" placeholder="Dep. Date" required>
		<input type="text" class="form-control" name="arr_date_<?=@$trip_type;?>[]" placeholder="Arr. Date" required>
	</div>
	<div class="col-md-2">
		<input type="time" class="form-control" name="dep_time_<?=@$trip_type;?>[]" placeholder="Dep. Time" required>
		<input type="time" class="form-control" name="arr_time_<?=@$trip_type;?>[]" placeholder="Arr. Time" required>
	</div>
	<div class="col-md-2">
		<input type="text" class="form-control" name="gds_pnr_<?=@$trip_type;?>[]" placeholder="GDS PNR" <?= $is_lcc != 'gds'?'style="display:none;"':'' ?>>
		<input type="text" class="form-control" name="airline_pnr_<?=@$trip_type;?>[]" placeholder="Arline PNR">
	</div>
</div>