<?php 
if(!empty($duration))
{
for ($i = 0; $i < $duration; $i++) {?>
		<div class='form-group' id="sub_activity_selection_duration">
			<label class='control-label col-sm-3' for='validation_current'>Sub Activity Selection  
			</label>
			<div class="sub_activities_duration">
				<div class="div_sub_activity_duration">					
				<div class="col-sm-2"> 
					 <select class='form-control'  name='filter_list_duration[1]' id="filter_list_duration_1" onchange="filtr();" >
					 	<option value="NA">Select Filter</option>
                        <option>Beach</option>
                        <option>Water</option>
                        <option>Boating</option>
					 </select>
					
				</div>
				<div class="col-sm-2"> <input type="text" class='form-control'  name='sub_duration_itinery[1]' id="time_duration_itinery_1" placeholder="Time(Hours/ Min)">
				</div>
				<div class='col-sm-2 controls'>
					<textarea name="desc[]" class="form-control itenary_elements"
						data-rule-required="true" cols="70" rows="3"
						placeholder="Description" required></textarea>
				</div>
				<div class='col-sm-1 controls add_tab padfive add_sub_activities_duration'>
	        		<span class="btn btn-primary"><i class="fa fa-plus"></i></span>
	        	</div>
			</div>
			</div>
		</div>
	
<!-- <div class='form-group clearfix'>
	<label class='control-label col-sm-3' for='validation_desc'>Itinerary Description <?php echo $i + 1; ?> </label>
	
</div> -->
<div class='form-group clearfix'>
	<label class='control-label col-sm-3' for='validation_name'>Day </label>
	<div class='col-sm-4 controls'>
		<input type="text" name="days[]" id="days<?php echo $i + 1; ?>" data-rule-required='true'
			readonly value="<?php echo $i + 1; ?>"
			class='form-control itenary_elements' required>
	</div>
</div>
<!-- <div class='form-group'>
	<label class='control-label col-sm-3' for='validation_current'>Offer Ticket<span style = "color:red">*</span>
	</label>
	<div class="col-sm-4 controls ">
		<input type="radio" id="offer_ticket_yes" name="offer_ticket" value="Y">&nbsp;<label for="sunday_0">Yes</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="offer_ticket_no" name="offer_ticket" value="N" >&nbsp;<label for="sunday_0">No</label>
		<div id="offet_ticket_div" style="display: none;">
		<div class='col-sm-3 controls'>
		<input type="file" title='Ticket to add' class='itenary_elements'
			data-rule-required='true' id='image' name='image' > <span
			id="pacmimg" style="color: #F00; display: none" >Please Upload
			Ticket</span>
	</div></div> 
		
	</div>
</div> -->
<!--<div class='form-group clearfix'>
	<label class='control-label col-sm-3' for='validation_name'>Place </label>
	<div class='col-sm-4 controls'>
		<input type="text" name="place[]" id="Place<?php echo $i + 1; ?>" data-rule-required='true'
			class='form-control itenary_elements' placeholder="Place Name" required>
	</div>
</div>
 <div class='form-group clearfix'>
	<label class='control-label col-sm-3' for='validation_company'>Itinerary
		Image</label>
	<div class='col-sm-3 controls'>
		<input type="file" title='Image to add' class='itenary_elements'
			data-rule-required='true' id='image<?php echo $i + 1; ?>' name='image[]' multiple required> <span
			id="pacmimg" style="color: #F00; display: none" >Please Upload
			Itinerary Image</span>
	</div>
</div> -->
<hr>
<?php } ?>
<!-- <div class='form-group clearfix'>
	<label class='control-label col-sm-3' for='validation_name'>Day </label>
	<div class='col-sm-4 controls'>
		<input type="text" name="days[]" id="days<?php echo $i + 1; ?>" data-rule-required='true'
			readonly value="<?php echo $i + 1; ?>"
			class='form-control itenary_elements' required>
	</div>
</div> -->
<!-- <div class='form-group'>
	<label class='control-label col-sm-3' for='validation_current'>Offer Ticket<span style = "color:red">*</span>
	</label>
	<div class="col-sm-4 controls ">
		<input type="radio" id="offer_ticket_yes" name="offer_ticket" value="Y" >&nbsp;<label for="sunday_0">Yes</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="offer_ticket_no" name="offer_ticket" value="N" >&nbsp;<label for="sunday_0">No</label>
		<div id="offet_ticket_div" style="display: none;">
		<div class='col-sm-3 controls'>
		<input type="file" title='Ticket to add' class='itenary_elements'
			data-rule-required='true' id='image' name='image' > <span
			id="pacmimg" style="color: #F00; display: none" >Please Upload
			Ticket</span>
	</div></div> 
		
	</div>
</div> -->
<hr>
<!-- 
<?php }?> -->
<script type="text/javascript">
	function filtr() {
		alert(1)
		// body...
	}
</script>
