<div class="clearfix form-group">
	<div class="col-md-1">
		<input type="hidden" name="pax_type[]" value="<?=ucfirst($pax_type); ?>">
		<select class="form-control" name="pax_title[]">
			<?php 
			if($pax_type=="infant"){
				echo generate_options(get_enum_list('infant_title'));
			}else{
				echo generate_options(get_enum_list('title'));
			}
			
			?>														
		</select>
	</div>
	<div class="col-md-2">
		<input type="text" class="form-control text-uppercase" name="pax_first_name[]" placeholder="First Name" required>
	</div>
	<div class="col-md-2">
		<input type="text" class="form-control text-uppercase" name="pax_last_name[]" placeholder="Last Name" required>
	</div>
	<div class="col-md-2 hide">
		<input type="hidden" class="form-control" name="pax_ff_num[]" placeholder="Freq. Flyer No.">
		<input type="text" class="form-control" name="pax_total_fare[]" placeholder="Total Fare">
	</div>
	<?php
		$class = ''; 
		if($booking_type == 'domestic'){
			$class = 'hide';
		}
	?>
	<div class="col-md-2 passport <?=$class?>">
		<input type="text" class="form-control" name="pax_passport_num[]" placeholder="Pass Port No.">
	</div>
	<div class="col-md-2 passport <?=$class?>">
		<input type="text" class="form-control" name="pax_pp_expiry[]" placeholder="PPExpiry">
	</div>
	<?php
		$trip_types = ''; 
		if($trip_types == 'domestic'){
			$trip_types = 'hide';
		} 
		$is_lcc_class = '';
		if($is_lcc != 'gds'){
			$is_lcc_class = 'hide';
		} 
	?>
	<div class="col-md-1 ticket_number <?=$trip_types?> <?=$is_lcc_class?>">
	    <input type="text" class="form-control text-uppercase" name="pax_ticket_num_onward[]" value="<?= @$pax_ticket_num_onward[$i]; ?>" placeholder="Onward" <?= @$is_lcc == 'gds' ? 'required' : '' ?>>
	    <input type="text" class="form-control  text-uppercase" name="pax_ticket_num_return[]" value="<?= @$pax_ticket_num_return[$i]; ?>" placeholder="Return" <?= @$trip_type != 'circle' ? 'style="display:none" disabled' : '' ?> <?= @$is_lcc == 'gds' ? 'required' : '' ?>>
	</div>
<?php  if($pax_type=="infant"){ ?>
	<div class="col-md-2">
		<input type="text" class="form-control" name="date_of_birth[]" placeholder="DOB">
	</div>

<?php }else{ ?>	
<div class="col-md-2">
		<input type="hidden" class="form-control" name="date_of_birth[]" placeholder="DOB">
	</div>
<?php	} ?>
<div class="col-md-1">
	<input type="hidden" class="form-control text-uppercase" name="pax_ticket_num_onward[]" placeholder="Onward" <?= @$c_type == 'gds' ?'required':'' ?>>
	<input type="hidden" class="form-control text-uppercase" name="pax_ticket_num_return[]" placeholder="Return" <?= @$trip_type != 'circle' ?'style="display:none" disabled':'' ?> <?= @$c_type == 'gds' ?'required':'' ?>>
</div> 
</div>
