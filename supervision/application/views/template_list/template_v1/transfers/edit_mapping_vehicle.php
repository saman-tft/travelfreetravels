<div class="modal fade" id="basicModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Edit Mapping  Vehicle / Driver</h4>
      </div>
      <div class="modal-body">
        <form action="<?php echo base_url(); ?>index.php/transfers/add_vehicle_driver/<?=$id?>" method="post" class='' id="">
			<div class="" id="">
				<div class='form-group clearfix'>
					<label class='control-label col-sm-3' for='validation_desc'>Day </label>
					<div class='col-sm-4 controls'>
						<input type="hidden" name="reference_id" value="<?=$id?>">
						<select class='form-control vehicle_elements days_option days_date'
										data-rule-required='true' name="day" id="days_option" required>
										<!-- <option value="">--Select Days--</option> -->
						</select>
					</div>
				</div>

				<div class='form-group clearfix'>
					<label class='control-label col-sm-3' for='validation_desc'>Date </label>
					<div class='col-sm-4 controls'>
						<input type="text" name="date" id="date"
							data-rule-required='true' placeholder="Date"
							class='form-control vehicle_elements date' required readonly>
					</div>
				</div>

				<div class='form-group clearfix'>
					<label class='control-label col-sm-3' for='validation_desc'>Weekday </label>
					<div class='col-sm-4 controls'>
						<input type="text" name="weekday" id="weekday"
							data-rule-required='true' placeholder="Weekday"
							class='form-control vehicle_elements weekday' required readonly>
					</div>
				</div>

				<div class='form-group'>
					<label class='control-label col-sm-3' for='validation_name'> Shift Time
					</label>
					<div>
					<div class='col-sm-2 controls'>
						<input type="text" name="shift_from" id="vehicle_shift_from_1" data-rule-required='true' placeholder="Shift From" class='form-control vehicle_elements vehicle_shift_time_start' required readonly>
					</div>
					<span style="margin-left: -166px; font-weight: bold;"> to </span>
					<div class='col-sm-2 controls'>
						 <input type="text" name="shift_to" id="vehicle_shift_to_1" data-rule-required='true' placeholder="Shift To" class='form-control vehicle_elements vehicle_shift_time_end' required readonly>
					</div>
					</div>
				</div>
				
				<div class='form-group clearfix'>
					<label class='control-label col-sm-3' for='validation_desc'>Vehicle </label>
					<div class='col-sm-4 controls'>
						 <select class='form-control vehicle_elements vehicle_list_option'
							data-rule-required='true' name="vehicle" id="vehicle_list_option" required>
							<option value="">--Select Option--</option>
						</select>
					</div>
				</div>
				
				<div class='form-group clearfix'>
					<label class='control-label col-sm-3' for='validation_desc'>Driver </label>
					<div class='col-sm-4 controls'>
						 <select class='form-control vehicle_elements driver_list_option'
							data-rule-required='true' name="driver" id="driver_list_option" required>
							<option value="">--Select Option--</option>
						</select>
					</div>
				</div>
				<div class='form-actions' style='margin-bottom: 8px'>
				<div class='row'>
					<div class='col-sm-9 col-sm-offset-3'>
					 <button type="submit" class="btn btn-primary add_vehicle_btn" >Edit Vehicle/Driver</button>
<!-- 
					<a class='btn btn-primary' id="vehicle_button">continue</a> -->
					</div>
				</div>
			</div>
				
			   <!--  <div class="add_vehicle_btn"><button type="submit" class="btn btn-primary" style="margin-left: 325px; margin-bottom: 8px;">Add Vehicle/Driver</button></div> -->

			</div>
			</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
       <!--  <button type="button" class="btn btn-primary">Save changes</button> -->
      </div>
    </div>
  </div>
</div>