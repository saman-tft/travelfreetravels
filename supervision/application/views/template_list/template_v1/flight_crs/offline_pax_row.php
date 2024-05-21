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
	<!--div class="col-md-2">
		<input type="text" class="form-control" name="pax_ff_num[]" placeholder="Freq. Flyer No.">
	</div>
	<div class="col-md-2">
		<input type="text" class="form-control" name="pax_passport_num[]" placeholder="Pass Port No.">
	</div>
	<div class="col-md-2">
		<input type="text" class="form-control" name="pax_pp_expiry[]" placeholder="PPExpiry">
	</div-->
	<div class="col-md-2">
      <input type="text" class="form-control" id="BaseFare<?= ($i - @$adult_count + @$child_count); ?>" name="pax_base_fare[]" value="<?= @$price; ?>" placeholder="BaseFare">
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
