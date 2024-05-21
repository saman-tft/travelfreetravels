<style type="text/css">
/*	input[type="checkbox"]{
    position: absolute;
     left: 0px; 
   
}*/
</style>
<?php 
$id = $GLOBALS ['CI']->uri->segment (3);
$sub_tab = $GLOBALS ['CI']->uri->segment (4);
$path = $GLOBALS['CI']->template->domain_upload_pckg_images();
if(isset($sub_tab) && !empty($sub_tab)){
	// debug($sub_tab);
	if($sub_tab == 'vehicle'){
      $active_vehicle = 'active';	
	}else{
	  $active_vehicle = '';	
	  // $active_tranfer = '';	
	}

	if($sub_tab == 'price_manage'){
      $active_price_manage = 'active';
       $active_tranfer = '';
       $active_vehicle = '';	
	}else{
	  $active_price_manage = '';	
	  // $active_tranfer = '';	
	}
	
}else{
	$active_tranfer = 'active';
	$active_vehicle = '';
	$active_price_manage = '';	
}
// debug($sub_tab);
// debug($active_tranfer);exit;
 // debug($transfer_data->rating);exit;

?>
<div id="Package" class="bodyContent col-md-12">
	<div class="panel panel-default">
		<!-- PANEL WRAP START -->
		<div class="panel-heading">
			<!-- PANEL HEAD START -->
			<div class="panel-title">
				<ul class="nav nav-tabs nav-justified" role="tablist" id="myTab">
					<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->
					<li role="presentation" class="<?=$active_tranfer?>" id="add_package_li"><a
						href="#add_package" aria-controls="home" role="tab"
						data-toggle="tab">Add Transfers </a></li>
					<li role="presentation" class="<?=$active_vehicle?>" id="vehicle_li"><a href="#vehicle"
						aria-controls="home" role="tab" data-toggle="">Mapping  Vehicle/Driver
					</a></li>
					<li role="presentation" class="<?=$active_price_manage?>" id="price_li"><a href="#price_manage"
						aria-controls="home" role="tab" data-toggle="">Price Manage
					</a></li>
					<!-- <li role="presentation" class="" id="itenary_li"><a href="#itenary"
						aria-controls="home" role="tab" data-toggle="">Add Day(s) Details
					</a></li>
					<li role="presentation" class="" id="gallery_li"><a href="#gallery"
						aria-controls="home" role="tab" data-toggle="">Photo Gallery </a></li> -->
					<li role="presentation" class="" id="rate_card_li"><a
						href="#rate_card" aria-controls="home" role="tab" data-toggle="">Rate
							Card </a></li>
					<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE END -->
				</ul>
			</div>
		</div>
		<!-- PANEL HEAD START -->
		<div class="panel-body">
			<!-- PANEL BODY START -->
			<!-- <form
				action="<?php echo base_url(); ?>index.php/transfers/add_transfer_details"
				method="post" enctype="multipart/form-data"
				class='form form-horizontal validate-form'> -->
				<div class="class='form form-horizontal validate-form'>">
				<div class="tab-content">
					<!-- Add Activity Starts -->
					<div role="tabpanel" class="tab-pane <?=$active_tranfer?>" id="add_package">
						<div class="col-md-12">
						<form
							action="<?php echo base_url(); ?>index.php/transfers/add_transfer_details/<?php echo $transfer_data->id;?>"
							method="post" enctype="multipart/form-data"
							class=''>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_name'>Transfers type</label>
								<div class='col-sm-4 controls'>
									<select class='select2 form-control add_pckg_elements'
										data-rule-required='true' name="transfer_type" id="disn" required>
										<option value=''>Select Transfers Type</option>
                   					     <?php


											for($l = 0; $l < count ( $package_type_data ); $l ++) {
												if($transfer_data->transfer_type == $package_type_data[$l]->package_types_id){
													$select = 'selected';
												}else{
													$select = '';
												}
												?>
					                        <option value='<?php echo $package_type_data[$l]->package_types_id; ?>' <?=$select?>> <?php echo $package_type_data[$l]->package_types_name; ?>  </option>
					                        <?php
											}
											?>
					                      </select>
					                       <span id="distination" style="color: #F00; display: none;">validate</span>
								</div>
							</div>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>Transfers Name </label>
								<div class='col-sm-4 controls'>
									<div class="controls">
										<input type="text" name="transfer_name" id="trnsfer_name"
											data-rule-minlength='2' data-rule-required='true'
											placeholder="Enter Transfers Name" value="<?php echo $transfer_data->transfer_name;?>"
											class='form-control add_pckg_elements' required>
									</div>
								</div>
							</div>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>Source </label>
								<div class='col-sm-4 controls'>
									<div class="controls">
										<input type="text" name="source" id="source"
											data-rule-minlength='2' data-rule-required='true'
											placeholder="Enter Source" value="<?php echo $transfer_data->source;?>"
											class='form-control add_pckg_elements airport_location' required>
									</div>
								</div>
							</div>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>Destination </label>
								 <div class='col-sm-4 controls'>
									<div class="controls">
										<input type="text" name="destination" id="destination"
											data-rule-minlength='2' data-rule-required='true' value="<?php echo $transfer_data->destination;?>"
											placeholder="Enter Destination"
											class='form-control add_pckg_elements airport_location' required>
									</div>
								</div>
							</div>

							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>Distance </label>
								 <div class='col-sm-4 controls'>
									<div class="controls">
										<input type="text" name="distance" id="destination"
											data-rule-minlength='2' data-rule-required='true'
											placeholder="Enter Destination" value="<?php echo $transfer_data->distance;?>"
											class='form-control add_pckg_elements' required>
									</div>
								</div>
							</div>

							<div class='form-group ' id="select_date">
					        <label class='control-label col-sm-3' for='validation_current'>Start Date <span style = "color:red">*</span>
					        </label>
					        <div class='col-sm-4 controls'>
					        <input type="text" name="start_date" value="<?php echo $transfer_data->start_date;?>" id="tour_start_date" data-rule-required='true'
										class='form-control add_pckg_elements' required value="" placeholder="Choose Date" data-rule-required='true'  readonly> 
					        </div>
					       </div>
							<div class='form-group ' id="select_date">
					        <label class='control-label col-sm-3' for='validation_current'>Expiry Date <span style = "color:red">*</span>
					        </label>
					        <div class='col-sm-4 controls'>
					        <input type="text" name="expire_date" value="<?php echo $transfer_data->expiry_date;?>" id="tour_expire_date" data-rule-required='true' 
										class='form-control add_pckg_elements' required value="" placeholder="Choose Date" data-rule-required='true' readonly> 
					        </div>
					       </div>

					       <div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>No of Days </label>
								 <div class='col-sm-4 controls'>
									<div class="controls">
										<input type="text" name="no_days" id="days"
											data-rule-minlength='2' data-rule-required='true'
											placeholder="Number of Days" value="<?php echo $transfer_data->no_days;?>"
											class='form-control add_pckg_elements' required readonly>
									</div>
								</div>
							</div>

						
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_company'>Transfers
									Display Image</label>
								<div class='col-sm-4 controls'>

								<!-- 	<input type="file" title='Image to add'
										class='add_pckg_elements' data-rule-required='true' id='transfer_image'
										name='transfer_image' required> <span id="pacmimg"
										style="color: #F00; display: none">Please Upload Transfers Image</span>
 -->
									<input type="file" title='Image to add to add' class=''
														id='transfer_image' name='transfer_image'> <input type="hidden"
														name='transfer_image' value="<?php echo $transfer_data->image; ?>">
									<img src="<?php echo $path.$transfer_data->image; ?>"
														width="100" name="photo">
								</div>
							</div>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_name'>Description</label>
								<div class='col-sm-4 controls'>
									<textarea name="description" data-rule-required='true'
										class="form-control add_pckg_elements" cols="70" rows="3"
										placeholder="Description" required><?php echo $transfer_data->description;?></textarea>
									<!--   <span id="dorigin_error" style="color:#F00;  display:none;"></span> -->
								</div>
							</div>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_rating'>Rating
								</label>
								<div class="col-sm-4 controls">
									<select class='form-control add_pckg_elements'
										data-rule-required='true' name='rating' id="rating" required>
										<option value="0" <?php if($transfer_data->rating == '0')echo 'selected'; ?>>0</option>
										<option value="1" <?php if($transfer_data->rating == '1')echo 'selected'; ?>>1</option>
										<option value="2" <?php if($transfer_data->rating == '2')echo 'selected'; ?>>2</option>
										<option value="3" <?php if($transfer_data->rating == '3')echo 'selected'; ?>>3</option>
										<option value="4" <?php if($transfer_data->rating == '4')echo 'selected'; ?>>4</option>
										<option value="5" <?php if($transfer_data->rating == '5')echo 'selected'; ?>>5</option>
									</select>
								</div>
							</div>
							<div class='form-group'>
								<div id="addCityButton" class="col-lg-2" style="display: none;">
									<input type="button" class="srchbutn comncolor"
										id="addCityInput" value="Add Peroid"
										style="padding: 3px 10px;">
								</div>
								<div id="removeCityButton" class="col-lg-2"
									style="display: none;">
									<input type="button" class="srchbutn comncolor"
										id="removeCityInput" value="Remove Peroid"
										style="padding: 3px 10px;">
								</div>
							</div>
							<div class='' style='margin-bottom: 0'>
								<div class='row'>
									
									<?php 
										if(isset($id) && !empty($id)){
									?>
									<div class='col-sm-9 col-sm-offset-3'>
										<button class='btn btn-primary' type="submit">update</button>
										<a class='btn btn-primary' id="add_package_button">
											continue</a>&nbsp;&nbsp; <a class='btn btn-primary'
											href="<?php echo base_url(); ?>transfers/view_transfer_list">
											go to list</a>
									</div>
									<?php }else{ 
									?>
									<div class='col-sm-9 col-sm-offset-3'>
									<button class='btn btn-primary' type="submit">submit</button>
										<a class='btn btn-primary'
											href="<?php echo base_url(); ?>transfers/view_transfer_list">
											cancel</a>
									</div>
									<?php } ?>
								</div>
							</div>
							</form>
						</div>
					</div>
					<!-- Add Activity Ends -->

					<!-- Vehicle Starts -->
					<div role="tabpanel" class="tab-pane <?=$active_vehicle?>" id="vehicle">
						<div class="col-md-12">
						<form action="<?php echo base_url(); ?>index.php/transfers/add_vehicle_driver/<?=$id?>" method="post" class='' id="map_vehicle_driver">
							<div class="duration_info_class clearfix" id="">
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
									 <button type="submit" class="btn btn-primary add_vehicle_btn" >Add Vehicle/Driver</button>

									<a class='btn btn-primary' id="vehicle_button">continue</a>
									</div>
								</div>
							</div>
								
							   <!--  <div class="add_vehicle_btn"><button type="submit" class="btn btn-primary" style="margin-left: 325px; margin-bottom: 8px;">Add Vehicle/Driver</button></div> -->

							</div>
							</form>
							<div class='form-actions' style='margin-bottom: 0'>
								<table class="table table-bordered table-striped table-highlight">
                                  <thead>
                                    <tr>
                                      <th>Days</th>
                                      <th>Date</th>
                                      <th>Week Day</th>
                                      <th>Shift From</th>
                                      <th>Shift To</th>
                                      <th>Vehicle</th>
                                      <th>Driver</th>
                                      <th>Action</th>
                                    </tr>
                                  </thead>
                                  <tbody class="vehicle_management_tbody">
                                  <?php 
                                  // debug($vehicle_data);exit;
                                  	if(isset($vehicle_data) && !empty($vehicle_data)){
                                  		foreach ($vehicle_data as $key => $value) {
                                  			if(isset($value->shift_time_from)){
                                  				 // $shift_time_from = minutesToHours($value->shift_time_from);
                                  				 $shift_time_from = date("h:i A", mktime(0,$value->shift_time_from));
                                  				 // $shift_time_to = minutesToHours($value->shift_time_to);
                                  				 $shift_time_to = date("h:i A", mktime(0,$value->shift_time_to));
                                  			}
                                  		
                                  ?>
                                   		 <tr>
	                                      <td><?=$value->day.' Day'?></td>
	                                      <td><?=$value->date?></td>
	                                      <td><?=$value->weekday?></td>
	                                      <td><?=$shift_time_from?></td>
	                                      <td><?=$shift_time_to?></td>
	                                      <td><?=$value->vehicle_name?></td>
	                                      <td><?=$value->driver_name?></td>
	                                      <td class="center">
	                                      <a class="edit_mapping_vehicle" data-placement="top" href="#"
												data-original-title="Edit Transfer" data-toggle="modal" data-target="#basicModal" data-map_id="<?=$value->id?>"> <i class="glyphicon glyphicon-pencil"></i> Edit
											</a><br>
											<a href="<?php echo base_url(); ?>transfers/delete_mapping_vehicle/<?=$id?>/<?php echo $value->id; ?>"
												data-original-title="Delete"
												onclick="return confirm('Do you want delete this record');"
												class="" data-original-title="Delete"> <i
													class="glyphicon glyphicon-trash"></i>Delete 
											</a>
										</td>
	                                    </tr>
	                                <?php 
	                                   }
                                  	 }
	                                ?>
                                  </tbody>
                                </table>
							</div>

							<!-- <div class='form-actions' style='margin-bottom: 0'>
								<div class='row'>
									<div class='col-sm-9 col-sm-offset-3'>
										<a class='btn btn-primary' id="vehicle_button">submit &
											continue</a>
									</div>
								</div>
							</div> -->
						</div>
					</div>
					<!-- Vehicle Ends -->

					<!-- Vehicle Manage Starts -->
				<!-- 	<div role="tabpanel" class="tab-pane" id="vehicle#">
						<div class="col-md-12">
						 <form method="post" class='' id="map_vehicle_driver">
							<div class="duration_info_class clearfix" id="">
							<div class="vehicle_div">
								<div class="vehicle_details">
								  <div class='form-group'>
									<table class="table table-bordered table-striped table-highlight">
                                              <thead>
                                                <tr>
                                                  <th>Days</th>
                                                  <th>Shift From</th>
                                                  <th>Shift To</th>
                                                  <th>Vehicle</th>
                                                  <th>Driver</th>
                                                  <th>Action</th>
                                                </tr>
                                              </thead>
                                              <tbody class="vehicle_management_tbody">
                                                <tr class="vehicle_management_tr">
                                                  <td>
                                                    <select class='form-control vehicle_elements days_option days_date'
														data-rule-required='true' name="vehicle[0][day]" id="days_option" required>
													</select>
													<input type="hidden" name="vehicle[0][weekday]" class="weekday">
							                        <input type="hidden" name="vehicle[0][date]" class="date">
											      </td>
                                                  <td>
                                                  		<input type="text" name="vehicle[0][shift_from]" id="vehicle_shift_from_1"
														data-rule-required='true' placeholder="Shift From"
														class='form-control vehicle_elements vehicle_shift_time_start' required readonly>
                                                  </td>
                                                  <td>
	                                                  <input type="text" name="vehicle[0][shift_to]" id="vehicle_shift_to_1"
													data-rule-required='true' placeholder="Shift To"
													class='form-control vehicle_elements vehicle_shift_time_end' required readonly>
													</td>	
                                                  <td>
	                                                  <select class='form-control vehicle_elements vehicle_list_option'
														data-rule-required='true' name="vehicle[0][vehicle]" id="vehicle_list_option" required>
														<option value="">--Select Option--</option>
													</select>
                                                  </td>
                                                   <td>
	                                                  <select class='form-control vehicle_elements driver_list_option'
														data-rule-required='true' name="vehicle[0][driver]" id="driver_list_option" required>
														<option value="">--Select Option--</option>
													</select>
                                                  </td>

                                                  <td>
	                                                  <button type="submit" class="btn btn-default btn-sm remove_price" style="margin-top: 8px;color: red;margin-bottom: 12px;">
												          <span class="glyphicon glyphicon-remove"></span> 
												    </button>
                                                  </td>
                                                </tr>
                                               
                                              </tbody>
                                            </table>
                                           
										
									</div>
									</div>
									</div>

									<div class="add_vehicle_btn"><span class="btn btn-primary pull-right" style="    margin-right: 48px;margin-top: 15px;">Add Vehicle/Driver</span></div>

							  		</div>
							</form>
							<div class='form-actions' style='margin-bottom: 0'>
								<div class='row'>
									<div class='col-sm-9 col-sm-offset-3'>
										<a class='btn btn-primary' id="price_button">submit &
											continue</a>
									</div>
								</div>
							</div>
						</div>
					</div> -->
					<!-- Vehicle Manage Ends -->

					<!-- Price Manage Starts -->
					<div role="tabpanel" class="tab-pane <?=$active_price_manage?>" id="price_manage">
						<div class="col-md-12">
						<form action="<?php echo base_url(); ?>index.php/transfers/add_tranfer_price/<?=$id?>" method="post" class='' id="map_vehicle_driver">
							<div class="duration_info_class clearfix" id="duration_info">
							<div class="nationality">
								<div class='form-group'>
									<label class='control-label col-sm-3' for=''> Nationality
									</label>
									<div>
									<div class='col-sm-3'>
										<select class='form-control' name='country' id="country_list">
										<option value="">--Select Country--</option>
			                                <?php foreach ($country as $ekey => $evalue) { 
                                            ?>
                                            
                                            <option value='<?=$evalue->country_list?>' <?=$select?>><?=$evalue->country_name?></option>
                                            <?php } ?>
                                        </select> 
									</div>
									
									<div class='col-sm-3 '>
										 <input type="text" name="" id="currency" placeholder="Currency" class='form-control' readonly>
									</div>

									<div class='col-sm-3 '>
										  <span type="" class="btn btn-primary add_nationality_table" >Add Nationality</span>

									</div>
									</div>
								</div>
							</div>

							<div class="price_div">
							<?php 
								foreach ($price_nationality as $n_key => $nationality) {
							?>
							<div class="price_details" data-country="<?=$nationality->country?>" data-currency="<?=$nationality->currency?>" data-country_name="<?=$nationality->country_name?>" >
							  <div class='form-group'>
							
								<table class="table table-bordered table-striped table-highlight">
								    <thead>
								    	<tr>
								    		<th colspan="6"><?=$nationality->country_name?> (<?=$nationality->currency?>)<span class="glyphicon glyphicon-remove pull-right remove_price_table"></span></th>	
								    	</tr>
				                        <tr>
				                          <th>Days</th>
				                          <th>Shift From</th>
				                          <th>Shift To</th>
				                          <th>Price</th>
				                          <th>Tax</th>
				                          <th>Action</th>
				                        </tr>
				                    </thead>
				                    <tbody class="price_management_tbody">
				                    	<?php 
				                    		foreach ($price_data as $key => $price_info) {
				                    			if($price_info->country == $nationality->country){
				                    	?>

				                       <tr class="price_management_tr price_management_tr_<?=$nationality->country?>">
				                           <td>
				                            <select class='form-control price_elements'
												data-rule-required='true' name="price[<?=$key?>][shift_day]" id="" required>
													<option value="">--Select Days--</option>
													<option value="1" <?php if($price_info->shift_day == '1')echo 'selected'?> >Monday</option>
													<option value="2" <?php if($price_info->shift_day == '2')echo 'selected'?>>Tuesday</option>
													<option value="3" <?php if($price_info->shift_day == '3')echo 'selected'?>>Wednesday</option>
													<option value="4" <?php if($price_info->shift_day == '4')echo 'selected'?>>Thursday</option>
													<option value="5" <?php if($price_info->shift_day == '5')echo 'selected'?>>Friday</option>
													<option value="6" <?php if($price_info->shift_day == '6')echo 'selected'?>>Saturday</option>
													<option value="7" <?php if($price_info->shift_day == '7')echo 'selected'?>>Sunday</option>
											</select>
									      </td>
				                            <td>
				                            	<input type="hidden" name="price[<?=$key?>][id]" value="<?=$price_info->id?>">
				                          		<input type="hidden" name="price[<?=$key?>][country]" value="<?=$price_info->country?>">
					                          	<input type="hidden" name="price[<?=$key?>][currency]" value="<?=$price_info->currency?>">
					                          	<input type="hidden" name="price[<?=$key?>][country_name]" value="<?=$price_info->country_name?>">
				                          		<input type="text" name="price[<?=$key?>][shift_from]" id="price_shift_from_<?=$key?>" value="<?=$price_info->shift_from?>"
												data-rule-required='true' placeholder="Price Shift From" 
												class='form-control price_elements price_shift_from' required readonly>
				                          </td>
				                          <td>
				                              <input type="text" name="price[<?=$key?>][shift_to]" id="price_shift_to_<?=$key?>" value="<?=$price_info->shift_to?>"
											data-rule-required='true' placeholder="Price Shift To"
											class='form-control price_elements price_shift_to' required readonly>
											</td>	
				                          <td>
				                              <input type="text" name="price[<?=$key?>][price]" id="price_<?=$key?>" value="<?=$price_info->price?>"
											data-rule-required='true' placeholder="Price"
											class='form-control price_elements' required>
				                          </td>
				                            <td>
				                              <input type="text" name="price[<?=$key?>][tax]" id="tax_<?=$key?>" value="<?=$price_info->tax?>"
											data-rule-required='true' placeholder="Price"
											class='form-control price_elements' required>
				                           </td>

				                          <td>
				                              <button type="button" class="btn btn-default btn-sm remove_price" style="margin-top: 8px;color: red;margin-bottom: 12px;">
										          <span class="glyphicon glyphicon-remove"></span> 
										    </button>
				                          </td>
				                        </tr>
				                        
										<?php
											 }		
				                    		}
				                    	?>
				                    	<tr>
				                      	<td colspan="6"><div class="add_price_btn"><span class="btn btn-primary pull-right">Add Price</span></div></td>
				                      </tr>
				                      </tbody>
				                   </table>
									
								</div>
							</div>
							<?php		
								}
							?>
							
							</div>


						
							
								
								
							</div>
							<div class='form-actions' style='margin-bottom: 0'>
								<div class='row'>
									<div class='col-sm-9 col-sm-offset-3'>
										<button type="submit" class="btn btn-primary" >submit</button>
										<a class='btn btn-primary' id="price_button">
											continue</a>
									</div>
								</div>
							</div>
							</form>
						</div>
					</div>
					<!-- Price Manage Ends -->

					<!-- Rate card Starts -->
					<div role="tabpanel" class="tab-pane" id="rate_card">
						<div class="col-md-12">
						<form action="<?php echo base_url(); ?>index.php/transfers/add_rate_card/<?=$id?>" method="post" class='' id="map_vehicle_driver">
							<div class='form-group clearfix'>
								<label class='control-label col-sm-3' for='validation_includes'>Price
									Includes </label>
								<div class='col-sm-4 controls'>
									<!-- <input type="text" name="includes" id="includes" data-rule-required='true' class='form-control'> -->
									<textarea name="includes"
										class="form-control rate_card_elements"
										data-rule-required="true" cols="70" rows="3"
										placeholder="Price Includes" required><?php echo $transfer_data->price_includes;?></textarea>
								</div>
							</div>
							<div class='form-group clearfix'>
								<label class='control-label col-sm-3' for='validation_excludes'>Price
									Excludes </label>
								<div class='col-sm-4 controls'>
									<textarea name="excludes"
										class="form-control rate_card_elements"
										data-rule-required="true" cols="70" rows="3"
										placeholder="Price Excludes" required><?php echo $transfer_data->price_excludes;?></textarea>
								</div>
							</div>
							<div class='form-group clearfix'>
								<label class='control-label col-sm-3' for='validation_advance'>Cancellation
									In Advance </label>
								<div class='col-sm-4 controls'>
									<!-- <input type="text" name="excludes" id="excludes" data-rule-required='true' class='form-control'> -->
									<textarea name="cancellation_advance"
										class="form-control rate_card_elements"
										data-rule-required="true" cols="70" rows="3"
										placeholder="Cancellation In Advance" required><?php echo $transfer_data->cancellation_advance;?></textarea>
								</div>
							</div>
							<div class='form-group clearfix'>
								<label class='control-label col-sm-3' for='validation_excludes'>Cancellation
									Penalty </label>
								<div class='col-sm-4 controls'>
									<!-- <input type="text" name="excludes" id="excludes" data-rule-required='true' class='form-control'> -->
									<textarea name="cancellation_penality"
										class="form-control rate_card_elements"
										data-rule-required="true" cols="70" rows="3"
										placeholder="Cancellation Penalty" required><?php echo $transfer_data->cancellation_penalty;?></textarea>
								</div>
							</div>
							<div class='form-actions' style='margin-bottom: 0'>
								<div class='row'>
									<div class='col-sm-9 col-sm-offset-3'>
									
										<button class='btn btn-primary'>submit</button>
										<a class='btn btn-primary' id="back_button">
											back</a>
									</div>
								</div>
							</div>
							</form>
						</div>
					</div>
					<!-- Rate card Ends -->

				</div>
				</div>
			<!-- </form> -->
		</div>
		<!-- PANEL BODY END -->
	</div>
	<!-- PANEL WRAP END -->
</div>


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
										data-rule-required='true' name="day" id="edit_days_option" required>
										<!-- <option value="">--Select Days--</option> -->
						</select>
					</div>
				</div>

				<div class='form-group clearfix'>
					<label class='control-label col-sm-3' for='validation_desc'>Date </label>
					<div class='col-sm-4 controls'>
						<input type="text" name="date" id="edit_date"
							data-rule-required='true' placeholder="Date"
							class='form-control vehicle_elements date' required readonly>
					</div>
				</div>

				<div class='form-group clearfix'>
					<label class='control-label col-sm-3' for='validation_desc'>Weekday </label>
					<div class='col-sm-4 controls'>
						<input type="text" name="weekday" id="edit_weekday"
							data-rule-required='true' placeholder="Weekday"
							class='form-control vehicle_elements weekday' required readonly>
					</div>
				</div>

				<div class='form-group'>
					<label class='control-label col-sm-3' for='validation_name'> Shift Time
					</label>
					<div>
					<div class='col-sm-2 controls'>
						<input type="text" name="shift_from" id="edit_vehicle_shift_from" data-rule-required='true' placeholder="Shift From" class='form-control vehicle_elements vehicle_shift_time_start' required readonly>
					</div>
					<span style="margin-left: -166px; font-weight: bold;"> to </span>
					<div class='col-sm-2 controls'>
						 <input type="text" name="shift_to" id="edit_vehicle_shift_to" data-rule-required='true' placeholder="Shift To" class='form-control vehicle_elements vehicle_shift_time_end' required readonly>
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

<!-- jQuery timepicker library -->
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.3/jquery.timepicker.min.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.3/jquery.timepicker.min.js"></script>

 <link rel="stylesheet" type="text/css" href="<?=$GLOBALS ['CI']->template->template_css_dir ( 'page_resource/select2.min.css')?>">
  <?php
   Js_Loader::$js [] = array (
    'src' => $GLOBALS ['CI']->template->template_js_dir ( 'page_resource/select2.full.js' ),
    'defer' => 'defer' 
);?>  
<script type="text/javascript">
 
     $(document).ready(function(){
         $('#country').on('change', function() {
           $.ajax({
           url: 'get_crs_city/' + $(this).val(),
           dataType: 'json',
           success: function(json) {
               $('select[name=\'cityname_old\']').html(json.result);
           }
       });
         });
         $("#cityname").on('click',function(){
        	var dropdownVal=$(this).val();

        	$("#textbox").val(dropdownVal); 
		
    	});
     });
  
     function show_duration_info(duration)
       {
	       if(duration=='')
	       {
	       	duration=0;
	       }
	       if (window.XMLHttpRequest)
	       {// code for IE7+, Firefox, Chrome, Opera, Safari
	      	xmlhttp=new XMLHttpRequest();
	       }
	       else
	       {// code for IE6, IE5
	      	 xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	       }
	      	 xmlhttp.onreadystatechange=function()
	       {
	       	if (xmlhttp.readyState==4 && xmlhttp.status==200)
	       {
	      	 document.getElementById("duration_info").innerHTML=xmlhttp.responseText;
	       }
	       }
	      	 xmlhttp.open("GET","itinerary_loop/"+duration,true);
	      	 xmlhttp.send();
       }
     $("#addanother").click(function(){
     var addin = '<input type="text" name="ancountry" value="" placeholder="country" class="ma_pro_txt" style="margin:2px;"/><input type="text" name="anstate" placeholder="state" value="" class="ma_pro_txt" style="margin:2px;"/><input type="text" name="ancity" placeholder="city" value="" class="ma_pro_txt" style="margin:2px;"/><div onclick="removeinput()" style="font-weight:bold;cursor:pointer;">Remove</div><br/>';
     $("#addmorefields").html(addin);
  });
  
  function removeinput(){
   $("#addmorefields").html('');
  }
  
       function activate(that) { window.location.href = that; }
  var a;
  $(document).ready(function(){ 
  
  $('#addCityInput').click(function(){
   var cityNo = parseInt($('#multiCityNo').val());
    //alert(cityNo);
    var duration = $('#duration').val();
   var cityNo = cityNo+1;
    var cit = cityNo-1;
   var allCity = '';
   var i = cityNo;
   var s = i-1;
    
   allCity += "<div id='bothCityInputs"+i+"'><div class='form-group'><label class='control-label col-sm-2' for='validation_company'>From Date</label><div class='input-group col-sm-3' ><input class='fromd datepicker2 b2b-txtbox form-control' placeholder='MM/DD/YYYY' id='deptDate"+i+"'  myid='"+i+"' name='sd[]'' type='text'><span class='input-group-addon'><i class='icon-calendar'></i></span></div><label class='control-label col-sm-2' for='validation_name'>To Date</label><div class='input-group col-sm-3' ><input class='form-control b2b-txtbox' placeholder='MM/DD/YYYY' id='too"+i+"' name='ed[]'' type='text' readonly><span class='input-group-addon'><i class='icon-calendar'></i></span><span id='dorigin_error7' style='color:#F00;'></span><span id='dorigin_error' style='color:#F00;'></span><br></div><br></div>";
  
   allCity += "<div class='form-group clearfix'><label class='control-label col-sm-2' for='adult'>Adult Price</label><div class='input-group col-sm-3' ><input type='text' name='adult[]' id='adult"+i+"'  myid='"+i+"' data-rule-number='true' data-rule-required='true' class='form-control'><span class='input-group-addon'><i class='icon-usd'></i></span></div><label class='control-label col-sm-2' for='child'>Child Price</label><div class='input-group col-sm-3' ><input type='text' name='child[]' id='child"+i+"'  myid='"+i+"' data-rule-number='true' data-rule-required='true' class='form-control'><span class='input-group-addon'><i class='icon-usd'></i></span></div></div><hr>";
  allCity += '<script>var d1 = $("#deptDate'+cit+'").datepicker("getDate");'+
                 //'var dd = d1.getDate() + 1;var mm = d1.getMonth() + 1;var yy = d1.getFullYear();'+
                 'd1.setDate(d1.getDate() + parseInt(1));'+
                  'var dd = d1.getDate();var mm = d1.getMonth() + 1;var yy = d1.getFullYear();'+
                 'var to_date = (mm) + "/" + dd + "/" + yy;'+
                 //'var to_date = (mm) + "/" + dd + "/" + yy;'+
                 'alert(to_date);'+
                  'var duration = $("#duration").val();'+
                  '$("#deptDate'+i+'").datepicker({'+
                  'dateFormat: "mm/dd/yy",'+
                  'minDate: to_date,'+
                   'onSelect: function(dateStr) {'+
                    'var d1 = $(this).datepicker("getDate");'+  
                    
  
                    'd1.setDate(d1.getDate() + parseInt(duration));'+
                   
                     'var dd = d1.getDate();var mm = d1.getMonth() + 1;var yy = d1.getFullYear();'+
                     'var to_date = (mm) + "/" + dd + "/" + yy;'+
                     '$("#too'+i+'").val(to_date);'+
                       '}'+
                    '});'+
                       '<\/script>'+
                       '</div>';
   //$("#addMultiCity").append("<label class='control-label col-sm-2' for='validation_company'>From</label><div class='col-sm-3 controls'><input name='sd' id='' type='text' class='datepicker2 b2b-txtbox form-control'     />   <span id='dorigin_error6' style='color:#F00;'></span><br></div><label class='control-label col-sm-3' for='validation_name'>To</label><div class='col-sm-3 controls'><input name='ed' id='' type='text' class='datepicker3 b2b-txtbox form-control'   />  <span id='dorigin_error7' style='color:#F00;'></span><span id='dorigin_error' style='color:#F00;'></span></div>");
                         
   $("#addMultiCity").append(allCity);
   if(cityNo>1){
     $("#removeCityButton").show();
   }
   $('#multiCityNo').val(cityNo);
     });
  $('#removeCityInput').click(function(){
   var cityNo = parseInt($('#multiCityNo').val());
   
   var allCity = '';
   if(cityNo >1){
     $("#bothCityInputs"+cityNo).remove();
     var cityNo = cityNo-1;
     if(cityNo>1){
       $("#removeCityButton").show();
   }
   }
   else
      {
     $("#removeCityButton").hide();
      }
   $('#multiCityNo').val(cityNo);
  });  

$('#add_package_button').click(function(){
	var error_free = true;
    // $( ".add_pckg_elements" ).each(function() {
    //     if($( this ).val() == ''){
    //       error_free = false;
    //       $( this ).closest( ".form-group" ).addClass( "has-error" );
    //     }        
    //   });
    //   if(error_free)
    //   {
    //   	  $("#add_package_li").removeClass("active");
    //   	  $("#add_package").removeClass("active");
    //   	  $("#vehicle_li").addClass("active");
    //   	  $("#vehicle").addClass("active");
    //   	  // $("#itenary_li").addClass("active");
    //   	  // $("#itenary").addClass("active");
    //   }
		var id = '<?=$id?>';
		if(id!='' && id!='undefined'){
          $("#add_package_li").removeClass("active");
      	  $("#add_package").removeClass("active");
      	  $("#vehicle_li").addClass("active");
      	  $("#vehicle").addClass("active");
      	}
	  });

$('#vehicle_button').click(function(){
	var error_free = true;
  //   $( ".vehicle_elements" ).each(function() {
  //       if($( this ).val() == ''){
  //         error_free = false;
  //         $( this ).closest( ".form-group" ).addClass( "has-error" );
  //       }        
  //     });
  //     if(error_free)
  //     {
	 //  $("#vehicle_li").removeClass("active");
	 //  $("#vehicle").removeClass("active");
	 // $("#price_li").addClass("active");
	 //  $("#price_manage").addClass("active");
  //     }

        $("#vehicle_li").removeClass("active");
	  $("#vehicle").removeClass("active");
	 $("#price_li").addClass("active");
	  $("#price_manage").addClass("active");
 });

$('#price_button').click(function(){
	var error_free = true;
    $( ".price_elements" ).each(function() {
        if($( this ).val() == ''){
          error_free = false;
          $( this ).closest( ".form-group" ).addClass( "has-error" );
        }        
      });
      if(error_free)
      {
	  $("#price_li").removeClass("active");
	  $("#price_manage").removeClass("active");
	 $("#rate_card_li").addClass("active");
	  $("#rate_card").addClass("active");
      }
 });


$('#itenary_button').click(function(){
	var error_free = true;
    $( ".itenary_elements" ).each(function() {
        if($( this ).val() == ''){
          error_free = false;
          $( this ).closest( ".form-group" ).addClass( "has-error" );
        }        
      });
      if(error_free)
      {
	  $("#itenary_li").removeClass("active");
	  $("#itenary").removeClass("active");
	  $("#gallery_li").addClass("active");
	  $("#gallery").addClass("active");
      }
	  });
$('#gallery_button').click(function(){
	var error_free = true;
    $( ".gallery_elements" ).each(function() {
        if($( this ).val() == ''){
          error_free = false;
          $( this ).closest( ".form-group" ).addClass( "has-error" );
        }        
      });
      if(error_free)
      {
	  $("#gallery_li").removeClass("active");
	  $("#gallery").removeClass("active");
	  $("#rate_card_li").addClass("active");
	  $("#rate_card").addClass("active");
      }
	  });

$('#back_button').click(function(){
	 $("#rate_card_li").removeClass("active");
	  $("#rate_card").removeClass("active");
	  $("#add_package_li").addClass("active");
	  $("#add_package").addClass("active");
      });
  });


  



 $(document).ready(function(){ 
  
  $(document).on("change",".fromd",function(){ 
     current_date = $(this).val();
     
   current_id = $(this).attr('id');
   // alert(current_id);
  $(".fromd").each(function(){ 
     previous_dates = $(this).val();
      //alert(previous_dates);
     currenr_id=$(this).attr('id');
  
      
     if(current_date == previous_dates && current_id != currenr_id){
   myid=$("input[type='text']#"+current_id).attr('myid');
     alert("Already Same Date Selected");
     $("#"+current_id).val(" ");
    // alert(myid);
      $("#to"+myid).val(" ");
        $("#too"+myid).val(" ");
  }
   });
  });
  });
  
    $('#validation_country').on('change', function(){
        var country=$(this).val();
        $.ajax({
            type:"POST",
            url: "<?php echo base_url(); ?>supplier/get_cities/"+country,
            data:{country:country},
            success:function(wcity)
            {
              $('#city').html(wcity);
            }
          });
      });


    $(document).ready(function () {
        $('#tour_start_date').datepicker({
        	minDate:0,
        	numberOfMonths: 2,
        	changeMonth: !0,
        	dateFormat: "yy-mm-dd"
        });

         $('#tour_expire_date').datepicker({
        	minDate:0,
        	numberOfMonths: 2,
        	changeMonth: !0,
        	dateFormat: "yy-mm-dd"
        });
         $('#tour_start_date').on('change',function(){
         	$("#tour_expire_date").val('');
         	$("#days").val('');
         });

		 $('#tour_expire_date').on('change',function(){
	     	var start= $("#tour_start_date").datepicker("getDate");
		    var end= $("#tour_expire_date").datepicker("getDate");
		    var days = (end- start) / (1000 * 60 * 60 * 24);
		    // console.log(Math.round(days));
		    var no_days = Math.round(days+1)
		    $('#days').val(no_days);
			get_days_options(start,end);

	     });
		$(document).ready(function(){
			var start = '<?php echo $transfer_data->start_date;?>';
			var end = '<?php echo $transfer_data->expiry_date;?>';
			get_days_options(start,end);
		});


	    function get_days_options(start,end){
	    	$('.days_option').html('');
		    $('.days_option').append('<option value="0">All Days</option');
		   
		    var fd = new Date(start);
			var td = new Date(end);
			var i=1;
			while(fd<=td){
				mnth = ("0" + (fd.getMonth() + 1)).slice(-2),
			    day = ("0" + fd.getDate()).slice(-2);
			    var nextdate =  [fd.getFullYear(), mnth, day].join("-");
				var weekDay = fd.toString().split(' ')[0];
				$('.days_option').append('<option class="option_day" value="'+i+'" data-date="'+nextdate+'" data-weekday="'+weekDay+'">'+i+' Day</option>');

			fd.setDate(fd.getDate() +1);
			i++;
    	    }
	    }

	    $(document).on('change','.days_option',function(){
	    	var day = $(this).val();
    		var date = $(this).find(':selected').attr('data-date');
    		var weekday =$(this).find(':selected').attr('data-weekday');
    		$('#date').val(date);
    		$('#weekday').val(weekday);
    		// $(this).parent().find('.date').val(date);
    		// $(this).parent().find('.weekday').val(weekday);
    	});
    });

    $(document).ready(function(){

     $('.select2_multiple').on('change',function(){ 
         if($(this).val() == 'all'){ 
         var ids = $(this).attr('id');
           var selectstring = $('#'+ids).val();
         var stringVal = [];
         $('#'+ids).find('option').each(function(){  
           //  if($(this).is(':contains(' + selectstring + ')')){
             if(!isNaN($(this).val())){
               stringVal.push($(this).val());
             }
                       
             //}
             $('#'+ids).val(stringVal).trigger("change");
         });
         }
         });
         
         
         $(".select2_multiple").select2({
             allowClear: true
         });
       });

    $(document).ready(function(){
	    $('#time').timepicker({
	        timeFormat: 'h:mm p',
            // startTime: '8:00 am',
            dynamic: true,
            dropdown: true,
            scrollbar: true
	    });
	    var tt = $('.price_management_tr').length;
	    for(var i=0; i <=tt; i++){
	    	$('#price_shift_to_'+i).timepicker({startTime: '6:00 am'});
		  $('#price_shift_from_'+i).timepicker({startTime: '6:00 am',change: checkTimeAvailbility});
	    
	    }

	    // $('#price_shift_to_1').timepicker();
	    // $('#price_shift_from_1').timepicker();
	    $('#vehicle_shift_to_1').timepicker({startTime: '6:00 am',change: setDefaultTime});
	    $('#vehicle_shift_from_1').timepicker({startTime: '6:00 am',change: checkAvailabilityTime});

	    //edit shift time.........
	    $('#edit_vehicle_shift_to').timepicker({startTime: '6:00 am',change: setDefaultTime});
	    $('#edit_vehicle_shift_from').timepicker({startTime: '6:00 am',change: checkAvailabilityTime});
    });
    $(document).ready(function(){
    	
   //  	$('.add_vehicle_btn').on('click',function(){
			// var error_free = true;
		 //    $( ".vehicle_elements" ).each(function() {
		 //        if($( this ).val() == ''){
		 //          error_free = false;
		 //          $( this ).closest( ".form-group" ).addClass( "has-error" );
		 //        }        
		 //      });
		 //      if(error_free)
		 //      {
		 //      	var id = '<?=$id?>';
			// 	$.ajax({
			//         url: '<?php echo base_url(); ?>index.php/transfers/add_vehicle_driver'+id,
			//         type: 'post',
			//         dataType: 'json',
			//         data: $('form#map_vehicle_driver').serialize(),
			//         success: function(data) {
			//         	console.log(data);
			//              // add_vehicle_row();
			//          }
			//     });
			 

	  //    }
   // 	});




    	function add_vehicle_row(){

	    		var count = $('.vehicle_management_tr').length +1;
	    		var price_div = '';
	    		if(count<=10){
	    		var length = 1;

				price_div +=`<tr class="vehicle_management_tr">
	                          <td>
	                            <select class='form-control vehicle_elements days_option_${count} days_date'
									data-rule-required='true' name="vehicle[${length}][day]" id="" required>
									
								</select>

								<input type="hidden" name="vehicle[${length}][weekday]" class="weekday">
								<input type="hidden" name="vehicle[${length}][shift_to]" class="date">
								</td>	
						      </td>
	                          <td>
	                          		<input type="text" name="vehicle[${length}][shift_from]" id="vehicle_shift_from_${count}"
									data-rule-required='true' placeholder="Price Shift From"
									class='form-control vehicle_elements vehicle_shift_time_start' required readonly>
	                          </td>
	                          <td>
	                              <input type="text" name="vehicle[${length}][shift_to]" id="vehicle_shift_to_${count}"
								data-rule-required='true' placeholder="Price Shift To"
								class='form-control vehicle_elements vehicle_shift_time_end' required readonly>
								</td>	
	                          <td>
	                             <select class='form-control vehicle_elements vehicle_list_option'
									data-rule-required='true' name="vehicle[${length}][vehicle]" id="" required>
									<option value="">--select option--</option>
								</select>
	                          </td>

	                          <td>
	                             <select class='form-control vehicle_elements driver_list_option'
									data-rule-required='true' name="vehicle[${length}][driver]" id="" required>
									<option value="">--select option--</option>
								</select>
	                          </td>


	                          <td>
	                              <button type="button" class="btn btn-default btn-sm remove_vehicle" style="margin-top: 8px;color: red;margin-bottom: 12px;">
							          <span class="glyphicon glyphicon-remove"></span> 
							    </button>
	                          </td>
	                        </tr>`;

				length++;

				$('.vehicle_management_tbody').append(price_div);
				var no_days = $('#days').val();
				// alert(no_days);
			    $('.days_option_'+count).html('');
			    $('.days_option_'+count).append('<option value="0">All Days</option');
			    for(var i=1;i<=no_days;i++){
			    	$('.days_option_'+count).append('<option class="option_day" value="'+i+'">'+i+' Day</option');
			    }

				$('#vehicle_shift_to_'+count).timepicker();
		        $('#vehicle_shift_from_'+count).timepicker();
			}else{
				alert('Max Reached');
			}	
    	}

    	$(document).on('click','.remove_vehicle',function(){
    		var count = $('.vehicle_management_tr').length;
    		if(count == 1){
    			alert('One Vehicle details required');
    		}else{
    		 $(this).closest('.vehicle_management_tr').remove();
    		}
    	});

    	$(document).on('click','.add_price_btn',function(){
    		var country = $(this).closest('.price_details').data('country');
    		var country_name = $(this).closest('.price_details').data('country_name');
    		var currency = $(this).closest('.price_details').data('currency');
    		// alert(country);

    		var error_free = true;
		    $( ".price_elements" ).each(function() {
		        if($( this ).val() == ''){
		          error_free = false;
		          $( this ).focus();
		          $( this ).closest( ".form-group" ).addClass( "has-error" );
		        }        
		      });
		      if(error_free)
		      {
		      	// alert(country);
		      	var count = $('.price_management_tr').length;
	    		var price_div = '';
	    		if(count<=20){
	    		// var length = 1;
	    		// alert(count);
				price_div +=`<tr class="price_management_tr price_management_tr_${country}">
	                          <td>
	                            <select class='form-control price_elements'
									data-rule-required='true' name="price[${count}][shift_day]" id="" required>
									<option value="">--Select Days--</option>
									<option value="1">Monday</option>
									<option value="2">Tuesday</option>
									<option value="3">Wednesday</option>
									<option value="4">Thursday</option>
									<option value="5">Friday</option>
									<option value="6">Saturday</option>
									<option value="7">Sunday</option>
								</select>
						      </td>
	                          <td>	
	                          		<input type="hidden" name="price[${count}][country]" value="${country}">
	                          		<input type="hidden" name="price[${count}][currency]" value="${currency}">
	                          		<input type="hidden" name="price[${count}][country_name]" value="${country_name}">
	                          		<input type="text" name="price[${count}][shift_from]" id="price_shift_from_${count}"
									data-rule-required='true' placeholder="Price Shift From"
									class='form-control price_elements price_shift_from' required readonly>
	                          </td>
	                          <td>
	                              <input type="text" name="price[${count}][shift_to]" id="price_shift_to_${count}"
								data-rule-required='true' placeholder="Price Shift To"
								class='form-control price_elements price_shift_to' required readonly>
								</td>	
	                          <td>
	                              <input type="text" name="price[${count}][price]" id="price_${count}"
								data-rule-required='true' placeholder="Price"
								class='form-control price_elements' required>
	                          </td>
	                           <td>
	                              <input type="text" name="price[${count}][tax]" id="tax_${count}" data-rule-required='true' placeholder="Tax"
								class='form-control price_elements' required>
	                          </td>                          

	                          <td>
	                              <button type="button" class="btn btn-default btn-sm remove_price" style="margin-top: 8px;color: red;margin-bottom: 12px;">
							          <span class="glyphicon glyphicon-remove"></span> 
							    </button>
	                          </td>

	                        </tr>`;

				// length++;
				// alert(country);
				$('.price_management_tr_'+country+':last').after(price_div);
				//$('.price_management_tbody').append(price_div);
				$('#price_shift_to_'+count).timepicker({startTime: '6:00 am'});
		        $('#price_shift_from_'+count).timepicker({startTime: '6:00 am',change: checkTimeAvailbility});
			}else{
				alert('Max Reached');
			}

		 }
    	});

    	$(document).on('click','.remove_price',function(){
    		var count = $('.price_management_tr').length;
    		if(count == 1){
    			alert('One price details required');
    		}else{
    		 $(this).closest('.price_management_tr').remove();
    		}
    	});


    	
    });

	function checkAvailabilityTime(){
		// var shift_time_end = $(this).val();
		var shift_time_start = $('.vehicle_shift_time_start').val();
		// var shift_time_start = $('.vehicle_shift_time_start').val();
		var day = $('.days_option').val();
		var date = $('#date').val();
		var weekDay = $('#weekday').val();
		var reference_id='<?=$id?>'
		
		if(shift_time_start!='' && shift_time_start!='undefined' && day!='' && day!='undefined' && date!='' && date!='undefined' && weekDay!='' && weekDay!='undefined' ){
			// console.log(shift_time_start);
		 $.ajax({
            type:"POST",
            url: "<?php echo base_url(); ?>transfers/check_vehicle_mapping",
            data:{
            	reference_id:reference_id,
            	day:day,
            	date:date,
            	weekDay:weekDay,
            	shift_time_start:shift_time_start
            	// shift_time_end:shift_time_end
            },
            success:function(response)
            {
             var data = $.parseJSON(response);
             console.log(data.result); 
             if(data.result == false){
             	alert('Already mapped vehicle');
             	$('.vehicle_shift_time_start').val('');
             }
            }
          });
		}else{
			alert('Day information required !');
			$('.vehicle_shift_time_start').val('');
		}
	}



	function setDefaultTime(){
		// var thisObj = $(this);
		var shift_time_end = $(this).val();
		var shift_time_start = $('.vehicle_shift_time_start').val();
		// var shift_time_start = $('.vehicle_shift_time_start').val();
		var day = $('.days_option').val();
		var date = $('#date').val();
		var weekDay = $('#weekday').val();
		
		// alert(day_date);
		console.log(day);
		console.log(date);
		console.log(weekDay);
		console.log(shift_time_end);
		console.log(shift_time_start);
		
		if(shift_time_start!='' && shift_time_start!='undefined' && day!='' && day!='undefined' && date!='' && date!='undefined' && weekDay!='' && weekDay!='undefined' ){
			// console.log(shift_time_start);
		 $.ajax({
            type:"POST",
            url: "<?php echo base_url(); ?>transfers/get_vehicle_list",
            data:{
            	day:day,
            	date:date,
            	weekDay:weekDay,
            	shift_time_start:shift_time_start,
            	shift_time_end:shift_time_end
            },
            success:function(response)
            {
            	$('#driver_list_option').append('');
            	$('#vehicle_list_option').append('');
              var data = $.parseJSON(response);
              $.each(data.result.vehicle,function(key,val){
              	// thisObj.closest('.vehicle_management_tr').find('.vehicle_list_option').append('<option value="'+val.id+'">'+val.vehicle_name+'</option>');
              	$('#vehicle_list_option').append('<option value="'+val.id+'">'+val.vehicle_name+'</option>');
              });
              $.each(data.result.driver,function(key,val){
              	// thisObj.closest('.vehicle_management_tr').find('.driver_list_option').append('<option value="'+val.id+'">'+val.driver_name+'</option>');
              	$('#driver_list_option').append('<option value="'+val.id+'">'+val.driver_name+'</option>');
              });
            }
          });
		}else{
			alert('Day information required !');
		}
		

	// });
}
$('#country_list').on('change',function(){
	var country_id = $(this).val();
	
	   $.ajax({
            type:"POST",
            url: "<?php echo base_url(); ?>transfers/get_currency/"+country_id,
            // data:{country:country},
            success:function(data)
            {
             var data = $.parseJSON(data);
             console.log(data.result.currency_code);
             $('#currency').val(data.result.currency_code);
            }
          });

});


	$('.add_nationality_table').on('click',function(){

		var country = $('#country_list').val();
		var currency = $('#currency').val();
		var country_name = $("#country_list option:selected").text();
		// console.log(country);
		// console.log(country_name);
		// var count = $('.price_management_tr').length +1;
		var price_table_div = '';
		var length = $('.price_details').length;
		var count = $('.price_management_tr').length;
		// alert(count);

		price_table_div +=`
		<div class="price_details" data-country="${country}" data-country_name="${country_name}" data-currency="${currency}">
			  <div class='form-group'>
			
				<table class="table table-bordered table-striped table-highlight">
				    <thead>
				    	<tr>
				    		<th colspan="6">${country_name} (${currency}) <span class="glyphicon glyphicon-remove pull-right remove_price_table"></span></th>	
				    	</tr>
                        <tr>
                          <th>Days</th>
                          <th>Shift From</th>
                          <th>Shift To</th>
                          <th>Price</th>
                          <th>Tax</th>
                          <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="price_management_tbody_${length}">
                       <tr class="price_management_tr price_management_tr_${country}">
                           <td>
                            <select class='form-control price_elements'
								data-rule-required='true' name="price[${count}][shift_day]" id="" required>
									<option value="">--Select Days--</option>
									<option value="1">Monday</option>
									<option value="2">Tuesday</option>
									<option value="3">Wednesday</option>
									<option value="4">Thursday</option>
									<option value="5">Friday</option>
									<option value="6">Saturday</option>
									<option value="7">Sunday</option>
							</select>
					      </td>
                            <td>
                          		<input type="hidden" name="price[${count}][country]" value="${country}">
	                          	<input type="hidden" name="price[${count}][currency]" value="${currency}">
	                          	<input type="hidden" name="price[${count}][country_name]" value="${country_name}">
                          		<input type="text" name="price[${count}][shift_from]" id="price_shift_from_${count}"
								data-rule-required='true' placeholder="Price Shift From" 
								class='form-control price_elements price_shift_from' required readonly>
                          </td>
                          <td>
                              <input type="text" name="price[${count}][shift_to]" id="price_shift_to_${count}"
							data-rule-required='true' placeholder="Price Shift To"
							class='form-control price_elements price_shift_to' required readonly>
							</td>	
                          <td>
                              <input type="text" name="price[${count}][price]" id="price_${count}"
							data-rule-required='true' placeholder="Price"
							class='form-control price_elements' required>
                          </td>
                            <td>
                              <input type="text" name="price[${count}][tax]" id="tax_${count}"
							data-rule-required='true' placeholder="Price"
							class='form-control price_elements' required>
                           </td>

                          <td>
                              <button type="button" class="btn btn-default btn-sm remove_price" style="margin-top: 8px;color: red;margin-bottom: 12px;">
						          <span class="glyphicon glyphicon-remove"></span> 
						    </button>
                          </td>
                        </tr>
                        

                      <tr>
                      	<td colspan="6"><div class="add_price_btn"><span class="btn btn-primary pull-right">Add Price</span></div></td>
                      </tr>
                       
                      </tbody>
                   </table>
					
				</div>
			</div>`;

		// length++;
		// alert(country);
		if(country !='undefined' && country !=''){
		   $('.price_div').append(price_table_div);
		  $('#price_shift_to_'+count).timepicker({startTime: '6:00 am'});
		  $('#price_shift_from_'+count).timepicker({startTime: '6:00 am',change: checkTimeAvailbility});
		}else{
			alert('Please Select Country');
		}
			//$('.price_management_tbody').append(price_div);
		// }
	});

		$(document).on('click','.remove_price_table',function(){
    		var count = $('.price_details').length;
    		if(count == 1){
    			alert('One price details required');
    		}else{
    		 $(this).closest('.price_details').remove();
    		}
    	});


   function checkTimeAvailbility(){
   	var thisObj = $(this);
   	var change_value = $(this).val();
   	console.log(change_value+'current');
   	var country = $(this).closest('.price_details').data('country');
   	var div_price = $(this).closest('.price_details').find('.price_management_tr_'+country);
   	// alert(div_price.length);
   	var i=1;
   	    $( div_price).each(function() {
   	    	if(i<div_price.length){
   	    	var from_time = $(this).find('.price_shift_from').val();
   	    	if(from_time == change_value){
	         alert('Time is already selected');
	         thisObj.val('');
	         // thisObj.find('.price_shift_to').val('');
	        }
	        i++;
	        }        
	    });
   }

   $(document).ready(function(){
   	$(document).on('click','.edit_mapping_vehicle',function(){
   		var map_id =  $(this).data('map_id');
   		$.ajax({
            type:"POST",
            url: "<?php echo base_url(); ?>transfers/get_mappping_vehicle/"+map_id,
            // data:{country:country},
            success:function(data)
            {
             var data = $.parseJSON(data);
             console.log(data);
             $('#edit_days_option').val(data.result.day);
             $('#edit_date').val(data.result.date);
             $('#edit_weekday').val(data.result.weekday);

             // var shift_time_from = date("h:i A", mktime(0,data.result.shift_time_from));
             // consol.log()

             $('#edit_vehicle_shift_from').val(shift_time_from);
             $('#edit_vehicle_shift_to').val(data.result.shift_time_to);

             // $('#edit_weekday').val(data.result.weekday);
             // $('#edit_weekday').val(data.result.weekday);
            }
          });

   	});

   });
$(document).ready(function(){


//get source and destination.........

   	 $(".airport_location").autocomplete({
       source: "<?=base_url();?>transfers/get_airport_loaction_suggestions",
       minLength: 2,//search after two characters     
       select: function(event,ui){
           $(this).val(ui.item.id);
          // if(ui.item.hasOwnProperty("id")==true){
          //    $(".airport_location").val(ui.item.id);
          // }else{
          //    $(".airport_location").val(0);
          // }
          //console.log(ui.item.hasOwnProperty("id"));
           // $(".departflight").focus();
           //$(".flighttoo").focus();
       }
       
     });


// $( ".airport_location" ).autocomplete({
//       source: function( request, response ) {
//         $.ajax({
//           url: "<?=base_url();?>transfers/get_airport_loaction_suggestions",
//           dataType: "jsonp",
//           data: {
//             q: request.term
//           },
//           success: function( data ) {
//             response( data );
//             console.log(data);
//           }
//         });
//       },
//       minLength: 3,
//       select: function( event, ui ) {
//         log( ui.item ?
//           "Selected: " + ui.item.label :
//           "Nothing selected, input was " + this.value);
//       },
//       open: function() {
//         $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
//       },
//       close: function() {
//         $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
//       }
//     });
});

</script>