<style type="text/css">
/*	input[type="checkbox"]{
    position: absolute;
     left: 0px; 
   
}*/
.add_tab{ margin-top: 25px; }
.padfive{ padding: 0 5px !important; }
.days_lablel label{ margin-left: 5px; padding-left: 21px !important; font-weight: 500; }
.ui-timepicker-viewport li a{}
.col-md-1.padfive.days_lablel {width: 10.3%;}
.ui-menu .ui-menu-item{padding: 3px 0 3px 0 !important;}
.ui-menu .ui-menu-item a{padding: 4px .4em !important; border-radius: 0 !important;}
.ui-menu .ui-menu-item:last-child a {border-bottom: none !important;}
.ui-widget-content a{ color: #333 !important; }
.available_dates{ float: left; width:52%; }
.dates_div {float: left;width: 100%;margin-top: 7px;}
.close_bar .btn-primary{background-color:transparent!important;border-color: transparent!important;color: #f00;font-size:24px;    font-size: 24px;
    padding: 0 6px;
    margin-top: 22px!important;}
   .price_submit_button{display: none;}
   .ui-timepicker-standard{ z-index: 99999999 !important; }

/* Base for label styling */
[type="checkbox"]:not(:checked),
[type="checkbox"]:checked {
  position: absolute;
  left: -9999px;
}
[type="checkbox"]:not(:checked) + label,
[type="checkbox"]:checked + label {
  position: relative;
  padding-left: 18px;
  cursor: pointer;
}

/* checkbox aspect */
[type="checkbox"]:not(:checked) + label:before,
[type="checkbox"]:checked + label:before {
  content: '';
  position: absolute;
  left: 0; top: 0;
  width: 18px; height:18px;
  border:0px solid #ccc;
  background: #c4c3c2;
  border-radius: 4px;
  box-shadow: inset 0 1px 3px rgba(0,0,0,.1);
}
/* checked mark aspect */
[type="checkbox"]:not(:checked) + label:after,
[type="checkbox"]:checked + label:after {
  content: '\2713\0020';
  position: absolute;
  top: .15em; left: .22em;
  font-size: 1.3em;
  line-height: 0.8;
  color: #d4630d;
  transition: all .2s;
  font-family: 'Lucida Sans Unicode', 'Arial Unicode MS', Arial;
}
/* checked mark aspect changes */
[type="checkbox"]:not(:checked) + label:after {
  opacity: 0;
  transform: scale(0);
}
[type="checkbox"]:checked + label:after {
  opacity: 1;
  transform: scale(1);
}
/* disabled checkbox */
[type="checkbox"]:disabled:not(:checked) + label:before,
[type="checkbox"]:disabled:checked + label:before {
  box-shadow: none;
  border-color: #bbb;
  background-color: #ddd;
}
[type="checkbox"]:disabled:checked + label:after {
  color: #999;
}
[type="checkbox"]:disabled + label {
  color: #aaa;
}
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
						data-toggle="tab" id="add_package_tab">Add Transfers </a></li>
					<li role="presentation" class="<?=$active_vehicle?>" id="vehicle_li"><a href="#vehicle"
						aria-controls="home" role="tab" id="mapping_tab" data-toggle="">Mapping  Vehicle/Driver
					</a></li>
					<li role="presentation" class="<?=$active_price_manage?>" id="price_li"><a href="#price_manage"
						aria-controls="home" role="tab" id="price_tab" data-toggle="">Price Management
					</a></li>
					<!-- <li role="presentation" class="" id="itenary_li"><a href="#itenary"
						aria-controls="home" role="tab" data-toggle="">Add Day(s) Details
					</a></li>
					<li role="presentation" class="" id="gallery_li"><a href="#gallery"
						aria-controls="home" role="tab" data-toggle="">Photo Gallery </a></li> -->
					<li role="presentation" class="" id="rate_card_li"><a
						href="#rate_card" aria-controls="home" id="rate_tab" role="tab" data-toggle="">Rate
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
											placeholder="Region, City, Area (Worldwide)" value="<?php echo $transfer_data->source;?>"
											class='form-control add_pckg_elements airport_location' required>
									</div>
								</div>
							</div>
							<input type="hidden" name="country_code" id="country_code">
							<input type="hidden" name="city" id="city">
							<input type="hidden" name="origin" id="origin">
							<input type="hidden" name="airport_code" id="airport_code">
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>Destination </label>
								 <div class='col-sm-4 controls'>
									<div class="controls">
										<input type="text" name="destination" id="destination"
											data-rule-minlength='2' data-rule-required='true' value="<?php echo $transfer_data->destination;?>"
											placeholder="Region, City, Area (Worldwide)"
											class='form-control add_pckg_elements airport_location2' required>
									</div>
								</div>
							</div>

							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>Distance(km) </label>
								 <div class='col-sm-4 controls'>
									<div class="controls">
										<input type="text" name="distance" id="distance"
											data-rule-minlength='2' data-rule-required='true'
											placeholder="Enter Distance" value="<?php echo $transfer_data->distance;?>"
											class='form-control add_pckg_elements' required>
									</div>
								</div>
							</div>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>Duration(Minute) </label>
								 <div class='col-sm-4 controls'>
									<div class="controls">
										<input type="text" name="time_duration" id="time_duration"
											data-rule-minlength='2' data-rule-required='true'
											placeholder="Enter Duration in Minute" value="<?php echo $transfer_data->duration;?>"
											class='form-control add_pckg_elements' required>
									</div>
								</div>
							</div>


							<!-- <div class='form-group ' id="select_date">
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
							</div> -->

							<div class='form-group ' id="select_date">
					        <label class='control-label col-sm-3' for='validation_current'>Available Dates <span style = "color:red">*</span>
					        </label>
					        <div class="available_dates">
					        <?php
					        if(isset($transfer_dates_data) && !empty($transfer_dates_data)){
					        	foreach ($transfer_dates_data as $key => $dates_data) {
					        		$k = $key + 1;
					        	
					        	?>
					        	<div class="dates_div">
							        <div class='col-sm-4 controls padfive'>
							        <label class="center">Start Date</label>
							        <input type="hidden" name="dates[<?=$k?>][id]" value="<?php echo $dates_data->id;?>">
							        <input type="text" name="dates[<?=$k?>][start_date]" value="<?php echo date('d-M-y',strtotime($dates_data->start_date));?>" id="tour_start_date_<?=$k?>" data-rule-required='true'
												class='form-control add_pckg_elements tour_start_date' required value="" placeholder="Choose Date" data-rule-required='true'  readonly> 
							        </div>
							        <div class='col-sm-4 controls padfive'>
							        <label>Expiry Date</label>
							        <input type="text" name="dates[<?=$k?>][expire_date]" value="<?php echo date('d-M-y',strtotime($dates_data->expiry_date));?>" id="tour_expire_date_<?=$k?>" data-rule-required='true' 
											class='form-control add_pckg_elements tour_expire_date' required value="" placeholder="Choose Date" data-rule-required='true' readonly> 
							        </div>
							        <div class='col-sm-2 controls padfive'>
							        <label>Days</label>
							        <input type="text" name="dates[<?=$k?>][no_days]" id="days_<?=$k?>"
												data-rule-minlength='2' data-rule-required='true'
												placeholder="Days" value="<?php echo $dates_data->no_days;?>"
												class='form-control add_pckg_elements days' required readonly>
							        </div>
							      
							        	<?php
							        	if($k==1){
							        	?>
							        	  <div class='col-sm-1 controls add_tab padfive add_avail_dates'>
						        		<span class="btn btn-primary"><i class="fa fa-plus"></i></span>
						        		</div>
						        		<?php }else{
						        			?>
						        		<div class='col-sm-2 close_bar controls padfive'>
					        	<span class="btn btn-primary"><i class="far fa-times-circle"></i></span>
					        </div>
						        			<?php }?>
						        	
						        </div>
						     <?php
						 		}
					        }else{
					        ?>
					        	<div class="dates_div">
							        <div class='col-sm-4 controls padfive'>
							        <label class="center">Start Date</label>
							        <input type="text" name="dates[1][start_date]" value="" id="tour_start_date_1" data-rule-required='true'
												class='form-control add_pckg_elements tour_start_date' required value="" placeholder="Choose Date" data-rule-required='true'  readonly> 
							        </div>
							        <div class='col-sm-4 controls padfive'>
							        <label>Expiry Date</label>
							        <input type="text" name="dates[1][expire_date]" value="" id="tour_expire_date_1" data-rule-required='true' 
											class='form-control add_pckg_elements tour_expire_date' required value="" placeholder="Choose Date" data-rule-required='true' readonly> 
							        </div>
							        <div class='col-sm-2 controls padfive' >
							        <label>Days</label>
							        <input type="text" name="dates[1][no_days]" id="days_1"
												data-rule-minlength='2' data-rule-required='true'
												placeholder="Days" value="<?php echo $transfer_data->no_days;?>"
												class='form-control add_pckg_elements days' required readonly>
							        </div>
							        <div class='col-sm-1 controls add_tab padfive add_avail_dates'>
						        		<span class="btn btn-primary"><i class="fa fa-plus"></i></span>
						        	</div>
						        </div>

						       <?php 
						   }
						   ?>
						    </div>
					       </div>
					       <br>
							<?php 

							// debug($driver_info['driver_shift_days']);exit;
 
								$driver_shift_days = json_decode($transfer_data->driver_shift_days,true);

							    	$monday = '';
									$tuesday = '';
									$wednesday = '';
									$thursday = '';
									$friday = '';
									$saturday = '';
									$sunday = '';
									foreach ($driver_shift_days as $shift_key => $value) {
									

										if($value == 1){
											$monday = 'checked';
										}
										if($value == 2){
											$tuesday = 'checked';
										}
										if($value == 3){
											$wednesday = 'checked';
										}
										if($value == 4){
											$thursday = 'checked';
										}
										if($value == 5){
											$friday = 'checked';
										}
										if($value == 6){
											$saturday = 'checked';
										}
										if($value == 7){
											$sunday = 'checked';
										}
									}	
								?>
							

							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_name'> Available Weekdays
								</label>
								<div class="col-md-8 col-sm-8 col-xs-12">
									<div class="col-md-1 padfive days_lablel">
	                                   <input class='form-control checkbox' id='monday_0' name='driver_shift_days[]' value="1" type='checkbox' <?php echo $monday;?> onclick='uncheck();'>
	                                   <label for="monday_0">Mon</label>
                                   </div>
                                  	<div class="col-md-1 padfive days_lablel">
	                                   <input class='form-control checkbox' id='tuesday_0' name='driver_shift_days[]' value="2" type='checkbox' <?php echo $tuesday;?> onclick='uncheck();'>
	                                   <label for="tuesday_0">Tue</label>
	                                </div>
	                                <div class="col-md-1 padfive days_lablel">
	                                   <input class='form-control checkbox' id='wednesday_0' name='driver_shift_days[]' value="3" type='checkbox' <?php echo $wednesday;?> onclick='uncheck();'>
	                                   <label for="wednesday_0">Wed</label>
	                                </div>
	                                <div class="col-md-1 padfive days_lablel">
	                                   <input class='form-control checkbox' id='thursday_0' name='driver_shift_days[]' value="4" type='checkbox' <?php echo $thursday;?> onclick='uncheck();'>
	                                   <label for="thursday_0">Thu</label>
                                    </div>
                                    <div class="col-md-1 padfive days_lablel">
	                                   <input class='form-control checkbox' id='friday_0' name='driver_shift_days[]' value="5" type='checkbox' <?php echo $friday;?> onclick='uncheck();'>
	                                   <label for="friday_0">Fri</label>
	                                </div>
	                                <div class="col-md-1 padfive days_lablel">
	                                   <input class='form-control checkbox' id='saturday_0' name='driver_shift_days[]' value="6" type='checkbox' <?php echo $saturday;?> onclick='uncheck();'>
	                                   <label for="saturday_0">Sat</label>
	                                </div>
	                                <div class="col-md-1 padfive days_lablel">
	                                   <input class='form-control checkbox' id='sunday_0' name='driver_shift_days[]' value="7" type='checkbox' <?php echo $sunday;?> onclick='uncheck();'>
	                                   <label for="sunday_0">Sun</label>
                                     </div>
                                     <div class="col-md-1 padfive days_lablel">
					                                   <input class='form-control checkbox' name='alll' id='selectall' onclick='checkall();' type='checkbox' >
					                                   <label for="selectall">All</label>
				                                     </div>
                                </div>
							</div> 

						
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_company'>Transfers
									Display Image</label>
								<div class='col-sm-4 controls' style="margin-top: 9px;">

								<!-- 	<input type="file" title='Image to add'
										class='add_pckg_elements' data-rule-required='true' id='transfer_image'
										name='transfer_image' required> <span id="pacmimg"
										style="color: #F00; display: none">Please Upload Transfers Image</span>
 -->
									<input type="file" title='Image to add to add' class=''
														id='transfer_image' name='transfer_image' onchange="showMyImage(this,'P')"> <input type="hidden"
														name='transfer_image' value="<?php echo $transfer_data->image; ?>">
														<?php 
															if($transfer_data->image!=''){
																$style = 'style="width:50%; margin-top:10px;height: auto;"';
															}else{
																$style = 'style="width:50%; margin-top:10px;height: auto; display: none;"';
															}?>
									<img id="thumbnil" <?=$style?> src="<?php echo $path.$transfer_data->image; ?>" name="photo">
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
									<div class='col-sm-9 col-sm-offset-3 bbh'>
										<button class='btn btn-primary' type="submit">Update</button>
										<a class='btn btn-primary' id="add_package_button">
											Continue</a>&nbsp;&nbsp; <a class='btn btn-primary'
											href="<?php echo base_url(); ?>transfers/view_transfer_list">
											Go to list</a>
									</div>
									<?php }else{ 
									?>
									<div class='col-sm-9 col-sm-offset-3 bbh'>
									<button class='btn btn-primary' type="submit">Submit</button>
										<a class='btn btn-primary'
											href="<?php echo base_url(); ?>transfers/view_transfer_list">
											Cancel</a>
									</div>
									<?php } ?>
								</div>
							</div>
							</form>
						</div>
					</div>
					<!-- Add Activity Ends -->

					<!-- Vehicle Starts -->

					<?php
						$available_dates = '';

					        if(isset($transfer_dates_data) && !empty($transfer_dates_data)){
					        	foreach ($transfer_dates_data as $key => $dates_data) {
					        		$k = $key + 1;
					        		$available_dates .= '<option value="'.$dates_data->id.'" data-no_days="'.$dates_data->no_days.'" data-start_date="'.$dates_data->start_date.'" data-end_date="'.$dates_data->expiry_date.'"> '.$dates_data->start_date.' to '.$dates_data->expiry_date.'</option>';
					        	}
					        }
					?>
					<div role="tabpanel" class="tab-pane <?=$active_vehicle?>" id="vehicle">
						<div class="col-md-12">
						<form action="<?php echo base_url(); ?>index.php/transfers/add_vehicle_driver/<?=$id?>" method="post" class='' id="map_vehicle_driver">
							<div class="duration_info_class " id="">
								<div class='form-group clearfix'>
									<label class='control-label col-sm-3' for='validation_desc'>Available Date Range</label>
									<div class='col-sm-4 controls'>
										<select class='form-control vehicle_elements'
														data-rule-required='true' name="date_range_id" id="available_date_range" required>
														<option value="">--Select Date Range--</option>
														<?= $available_dates;?>
										</select>
									</div>
								</div>

								<!-- <div class='form-group clearfix'>
									<label class='control-label col-sm-3' for='validation_desc'>Day </label>
									<div class='col-sm-4 controls'>
										<input type="hidden" name="reference_id" value="<?=$id?>">
										<select class='form-control vehicle_elements days_option days_date'
														data-rule-required='true' name="day" id="days_option" required>
														
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
								</div> -->

								<div class='form-group'>
									<label class='control-label col-sm-3' for='validation_name'> Shift Time
									</label>
									<div>
									<div class='col-sm-2 controls'>
										<input type="hidden" name="reference_id" value="<?=$id?>">
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
									<div class='col-sm-9 col-sm-offset-3 bbh'>
									 <button type="submit" class="btn btn-primary add_vehicle_btn" >Add Vehicle/Driver</button>

									<a class='btn btn-primary' id="vehicle_button">Continue</a>
									</div>
								</div>
							</div>
								
							   <!--  <div class="add_vehicle_btn"><button type="submit" class="btn btn-primary" style="margin-left: 325px; margin-bottom: 8px;">Add Vehicle/Driver</button></div> -->

							</div>
							</form>

							<br>

							<!--Table of vehicle and driver-->
							<?php
							$date_range_tab = '';
							$data_range_div = '';
								 if(isset($transfer_dates_data) && !empty($transfer_dates_data)){
							        foreach ($transfer_dates_data as $key => $dates_data) {
						        		if($key==0){
						        			$active = 'active';
						        		}else{
						        			$active = '';
						        		}

						        	$data_td = '';
				        			if(isset($vehicle_data) && !empty($vehicle_data)){
                                  		foreach ($vehicle_data as $key => $value) {
                                  			$k=1;
                                  		if($value->date_range_id == $dates_data->id){
                                  			if(isset($value->shift_time_from)){
                                  				 $shift_time_from = date("h:i A", mktime(0,$value->shift_time_from));
                                  				 $shift_time_to = date("h:i A", mktime(0,$value->shift_time_to));
                                  			}	
                                  			$del_url = base_url().'transfers/delete_mapping_vehicle/'.$id.'/'.$value->id.'';
 													//<td>'.$value->day.' Day</td>
										            //<td>'.$value->date.'</td>
										            //<td>'.$value->weekday.'</td>
                                  			$data_td .= '<tr>
                                  			<td>'.$k.'</td>
                                  			<td class="center">
				                                      
														<div class="dropdown2" role="group">
					   <div class="dropdown slct_tbl pull-left sideicbbb">
						   <i class="fa fa-ellipsis-v"></i>  
						    <ul class="dropdown-menu sidedis" style="display: none;">';

												/*<li><a class="edit_mapping_vehicle" data-placement="top" href="#"
															data-original-title="Edit Transfer" data-toggle="modal" data-target="#basicModal"  data-map_id="'.$value->id.'"> <i class="glyphicon glyphicon-pencil"></i> Edit
														</a></li>*/
											$data_td .= '<li><a class="sideicbb5 sidedis"
												href="'.$del_url.'"
												data-original-title="Delete" 
												class="" data-original-title="Delete" onclick="confirm_delt();"> <i
													class="glyphicon glyphicon-trash"></i>Delete
											</a></li>
										</ul>
									</div>
								</div>
													</td>
				                                      <td>'.$shift_time_from.'</td>
				                                      <td>'.$shift_time_to.'</td>
				                                      <td>'.$value->vehicle_name.'</td>
				                                      <td>'.$value->driver_name.'</td>
				                                    </tr>';
                                  		}
                                  		$k++;	
						        		}
						        	}
						        		//table tab
						        		$date_range_tab .= '<li class="'.$active.'"><a data-toggle="tab" href="#menu_'.$dates_data->id.'"> '.$dates_data->start_date.' to '.$dates_data->expiry_date.'</a></li>';


						        		//container div
										$data_range_div .= '<div id="menu_'.$dates_data->id.'" class="tab-pane fade in '.$active.'">
										     	<div class="form-actions" style="margin-bottom: 0">
													<table class="table table-bordered table-striped table-highlight">
					                                  <thead>
					                                    <tr>
					                                      <th>S.No</th>
					                                      <th>Action</th>
					                                      <th>Shift From</th>
					                                      <th>Shift To</th>
					                                      <th>Vehicle</th>
					                                      <th>Driver</th>
					                                    </tr>
					                                  </thead>
					                                  <tbody class="vehicle_management_tbody">
					                                  '.$data_td.'
					                                  </tbody>
					                                </table>
												</div>
										    </div>';





							        }
							     }
							?>
								<div class="">
								  <ul class="nav nav-tabs">
								    <?=$date_range_tab?>
								  </ul>

								  <div class="tab-content">
								   <?=$data_range_div?>
 
								  </div>
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

					<!-- Price Manage Starts -->
					<div role="tabpanel" class="tab-pane <?=$active_price_manage?>" id="price_manage">
						<div class="col-md-12">
						<form action="<?php echo base_url(); ?>index.php/transfers/add_tranfer_price/<?=$id?>" method="post" class='' id="map_vehicle_driver">
							<div class="duration_info_class" id="duration_info">
							<div class="nationality">
								<div class='form-group'>
									<label class='control-label col-sm-2' for=''> Nationality Group
									</label>
									<div>
									<div class='col-sm-3'>
										<select class='form-control' name='country' id="country_list" onchange="check_nationality_date_map();">
										<option value="">--Select Nationality Group--</option>
			                                <?php foreach ($nationality_group_data as $ekey => $evalue) { 
                                            ?>
                                            
                                            <option value='<?=$evalue->origin?>'><?=$evalue->name?></option>
                                            <?php } ?>
                                        </select> 
									</div>
									
									<!-- <div class='col-sm-3 '>
										 <input type="text" name="" id="date_range_price" placeholder="date_range_price" class='form-control' readonly>
									</div> -->
								<?php
								$arry = array();
								$k=0;
								foreach ($price_nationality as $n_key => $nationality) {
									$arry[$k] = $nationality->date_range;
									$k++;
								}
								// debug($arry);exit;
									$available_range = '';
								        if(isset($transfer_dates_data) && !empty($transfer_dates_data)){
								        	foreach ($transfer_dates_data as $key => $dates_data) {
								        		$range = $dates_data->start_date.' - '.$dates_data->expiry_date;
								        		$k = $key + 1;
					        					$available_range .= '<option value="'.$dates_data->id.'" data-no_days="'.$dates_data->no_days.'" data-start_date="'.$dates_data->start_date.'" data-end_date="'.$dates_data->expiry_date.'"> '.$dates_data->start_date.' to '.$dates_data->expiry_date.'</option>';
								        		// if(!in_array($range, $arry)){
								        		// $available_range .= '<option value="'.$dates_data->id.'" data-no_days="'.$dates_data->no_days.'" data-start_date="'.$dates_data->start_date.'" data-end_date="'.$dates_data->expiry_date.'"> '.$dates_data->start_date.' to '.$dates_data->expiry_date.'</option>';
								        		// 	}
								        	}
								        }
								?>
									<div class='form-group clearfix'>
										<label class='control-label col-sm-3' for='validation_desc'>Date Range</label>
										<div class='col-sm-4 controls'>
											<select class='form-control vehicle_elements'
															data-rule-required='true' name="date_range_price" id="date_range_price" onchange="check_nationality_date_map();">
															<option value="">--Select Date Range--</option>
															<?= $available_range;?>
											</select>
										</div>
									</div>

									<div class='col-sm-3 '>
										  <span type="" class="btn btn-primary add_nationality_table" disabled>Add Nationality</span>

									</div>
									</div>
								</div>
							</div>

							<div class="price_div">
							<?php 
							// debug($price_nationality);exit;
								foreach ($price_nationality as $n_key => $nationality) {
							?>
							<div class="price_details" data-country="<?=$nationality->nationality_group?>" data-date_range_price="<?=$nationality->date_range?>" data-country_name="<?=$nationality->nationality_group_name?>" >
							  <div class='form-group'>
							
								<table class="table table-bordered table-striped table-highlight">
								    <thead>
								    	<tr>

								    		<th colspan="8"><?=$nationality->nationality_group_name?> (<?=$nationality->date_range?>)<span class="glyphicon glyphicon-remove pull-right remove_price_table"></span></th>	
								    	</tr>
				                        <tr>
				                          <th>Days</th>
				                          <th>Shift From</th>
				                          <th>Shift To</th>
				                          <th>Price (NPR)</th>
				                          <th>Action</th>
				                        </tr>
				                    </thead>
				                    <tbody class="price_management_tbody">
				                    	<?php 
				                    	$i=0;
				                    		foreach ($price_data as $key => $price_info) {
				                    			if(($price_info->date_range == $nationality->date_range) && ($price_info->nationality_group == $nationality->nationality_group)){
				                    	?>

				                       <tr class="price_management_tr price_management_tr_<?=$nationality->nationality_group?>">
				                           <td>
				                           	<?php 
				                           	$shiftDay = $price_info->shift_day;
				                           	$shiftDay = explode(',', $shiftDay);
				                           	$options='';
				                           	
				                           	$driver_shift_days = json_decode($transfer_data->driver_shift_days,true);
				                           	
											foreach ($driver_shift_days as $shift_key => $value) {
												$mon='';$tue='';$wed='';$thu='';$fri='';$sat='';$sun='';
												$s = 0;
												for($k=0; $k<count($shiftDay); $k++){
				                           		if($shiftDay[$k]==1){$mon='checked';$s++;}
				                           		if($shiftDay[$k]==2){$tue='checked';$s++;}
				                           		if($shiftDay[$k]==3){$wed='checked';$s++;}
				                           		if($shiftDay[$k]==4){$thu='checked';$s++;}
				                           		if($shiftDay[$k]==5){$fri='checked';$s++;}
				                           		if($shiftDay[$k]==6){$sat='checked';$s++;}
				                           		if($shiftDay[$k]==7){$sun='checked';$s++;}
				                           		}
				                           		if($s==7){
				                           			$all_check='checked';
				                           		}else{$all_check='';}
				                           		if($value==1){ ?>
				                           		<input class='form-control checkbox' id='mon_<?=$i?>' name='price[<?=$i?>][shift_day][]' value='1' type='checkbox' <?=$mon?> readonly onclick='uncheck_val(<?=$i?>)' disabled><label for='mon_<?=$i?>'>Mon</label>&nbsp;
				                           		<?php }else if($value==2){ ?>
				                           		<input class='form-control checkbox' id='tue_<?=$i?>' name='price[<?=$i?>][shift_day][]' value='2' type='checkbox' <?=$tue?> readonly onclick='uncheck_val(<?=$i?>)' disabled><label for='tue_<?=$i?>'>Tue</label>&nbsp;
				                           		<?php }else if($value==3){ ?>
				                           		<input class='form-control checkbox' id='wed_<?=$i?>' name='price[<?=$i?>][shift_day][]' value='3' type='checkbox' <?=$wed?> readonly onclick='uncheck_val(<?=$i?>)' disabled><label for='wed_<?=$i?>'>Wed</label>&nbsp;
				                           		<?php }else if($value==4){ ?>
				                           		<input class='form-control checkbox' id='thu_<?=$i?>' name='price[<?=$i?>][shift_day][]' value='4' type='checkbox' <?=$thu?> readonly onclick='uncheck_val(<?=$i?>)' disabled><label for='thu_<?=$i?>'>Thu</label>&nbsp;
				                           		<?php }else if($value==5){ ?>
				                           		<input class='form-control checkbox' id='fri_<?=$i?>' name='price[<?=$i?>][shift_day][]' value='5' type='checkbox' <?=$fri?> readonly onclick='uncheck_val(<?=$i?>)' disabled><label for='fri_<?=$i?>'>Fri</label>&nbsp;
				                           		<?php }else if($value==6){ ?>
				                           		<input class='form-control checkbox' id='sat_<?=$i?>' name='price[<?=$i?>][shift_day][]' value='6' type='checkbox' <?=$sat?> readonly onclick='uncheck_val(<?=$i?>)' disabled><label for='sat_<?=$i?>'>Sat</label>&nbsp;
				                           		<?php }else if($value==7){ ?>
				                           		<input class='form-control checkbox' id='sun_<?=$i?>' name='price[<?=$i?>][shift_day][]' value='7' type='checkbox' <?=$sun?> readonly onclick='uncheck_val(<?=$i?>)' disabled><label for='sun_<?=$i?>'>Sun</label>&nbsp;
				                           		<input class='form-control checkbox' name='alll_val' id='selectall_val<?=$i?>' onclick='checkall_val(<?=$i?>);' type='checkbox' disabled <?=$all_check?>><label for="selectall_val<?=$i?>">All</label>
				                           		<?php }

				                           		
				                          	}

				                          	echo $options;
				                           	?>
									      </td>
				                            <td>
				                     
				                            	<input type="hidden" name="price[<?=$key?>][id]" value="<?=$price_info->id?>">
				                          		<input type="hidden" name="price[<?=$key?>][country]" value="<?=$price_info->nationality_group?>">
				                          		<input type="hidden" name="price[<?=$key?>][date_from]" value="<?=$price_info->date_from?>">
				                          		<input type="hidden" name="price[<?=$key?>][date_to]" value="<?=$price_info->date_to?>">
					                          	<input type="hidden" name="price[<?=$key?>][date_range_price]" value="<?=$price_info->date_range?>">
					                          	<input type="hidden" name="price[<?=$key?>][country_name]" value="<?=$price_info->nationality_group_name?>">
				                          		<input type="text" name="price[<?=$key?>][shift_from]" id="price_shift_from_<?=$nationality->nationality_group?>_<?=$key?>" value="<?=$price_info->shift_from?>" data-value="<?=$key?>"
												data-rule-required='true' placeholder="Shift From" 
												class='form-control price_elements price_shift_from' required readonly disabled>
				                          </td>
				                          <td>
				                              <input type="text" name="price[<?=$key?>][shift_to]" id="price_shift_to_<?=$nationality->nationality_group?>_<?=$key?>" value="<?=$price_info->shift_to?>"
											data-rule-required='true' placeholder="Shift To"
											class='form-control price_elements price_shift_to' required readonly disabled>
											</td>
				                          <td>
				                            <input type="text" name="price[<?=$key?>][price]" id="price_<?=$nationality->nationality_group?>_<?=$key?>" value="<?=$price_info->price?>"
											data-rule-required='true' placeholder="Price"
											class='form-control price_elements' disabled required >
				                          </td>
				                          <td>

	                           <div class="dropdown2" role="group">
					   <div class="dropdown slct_tbl pull-left sideicbbb">
						   <i class="fa fa-ellipsis-v"></i>  
						    <ul class="dropdown-menu sidedis" style="display: none;">

													<li><a class="sideicbb5 sidedis remove_price" data-placement="top" 
												title=""
												data-original-title="Remove Price"> <i
													class="glyphicon glyphicon-remove"></i>Remove Price 
											</a></li>
											<li><a class="sideicbb5 sidedis edit_price" data-placement="top" data-value="<?=$i?>" data-group="<?=$nationality->nationality_group?>"
												title=""
												data-original-title="Edit Price"> <i
													class="glyphicon glyphicon-pencil"></i>Edit Price 
											</a></li>
											<li><a class="sideicbb5 sidedis delete_price" data-placement="top" 
												title=""
												data-original-title="Delete Price" data-value="<?=$price_info->id?>"> <i
													class="glyphicon glyphicon-trash"></i>Delete
											</a></li>
										</ul>
									</div>
								</div>
				                              <!-- <button type="button" class="btn btn-default btn-sm remove_price" style="margin-top: 8px;color: red;margin-bottom: 12px;">
										          <span class="glyphicon glyphicon-remove"></span> 
										    </button> -->
				                          </td>
				                        <!--   <td>
				                              <button type="button" class="btn btn-default btn-sm delete_price" value="<?=$price_info->id?>"  style="margin-top: 8px;color: red;margin-bottom: 12px;">
										          Delete
										    </button>
				                          </td> -->
				                        </tr>
				                        
										<?php
											 }	
											 $i++;	
				                    		}
				                    	?>
				                    	<tr>
				                      	<td colspan="8"><div class="add_price_btn"><span class="btn btn-primary pull-right">Add Price</span></div></td>
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
									<div class='col-sm-9 col-sm-offset-3  bbh'>
										<button type="submit" class="btn btn-primary price_submit_button" >Submit</button>
										<a class='btn btn-primary' id="price_button">
											Continue</a>
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
						<?php	
							$exclusive_ride = ''; 
							$pick_up = '';
							if($transfer_data->exclusive_ride == 'Y'){
								$exclusive_ride = 'checked';
							}
							if($transfer_data->meetup_location == 'Y'){
								$pick_up = 'checked';
							}

							?>
                            <div class='form-group clearfix'>
								<label class='control-label col-sm-3' for='validation_includes'>Service Provide </label>
								<div class='col-sm-2 controls'>
									<input class='form-control checkbox' id='exclusive_ride' name='exclusive_ride' value="Y" type='checkbox' <?=$exclusive_ride?> >
	                                   <label for="exclusive_ride"> Exclusive ride for you</label>
								</div>
								<div class='col-sm-2 controls'>
									<input class='form-control checkbox' id='meetup_location' name='meetup_location' value="Y" type='checkbox' <?=$pick_up?>>
	                                   <label for="meetup_location"> Meet Up At Location</label>
								</div>
							</div>       
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
							<div class='form-group clearfix'>
								<label class='control-label col-sm-3' for='validation_excludes'>General Info List</label>
								<div class='col-sm-4 controls'>
									<!-- <input type="text" name="excludes" id="excludes" data-rule-required='true' class='form-control'> -->
									<textarea name="general_list_info"
										class="form-control rate_card_elements"
										data-rule-required="true" cols="70" rows="3"
										placeholder="General Info List" required><?php echo $transfer_data->general_list_info;?></textarea>
								</div>
							</div>
							<div class='form-group clearfix'>
								<label class='control-label col-sm-3' for='validation_excludes'>Pickup Information</label>
								<div class='col-sm-4 controls'>
									<!-- <input type="text" name="excludes" id="excludes" data-rule-required='true' class='form-control'> -->
									<textarea name="pick_up_info"
										class="form-control rate_card_elements"
										data-rule-required="true" cols="70" rows="3"
										placeholder="Pickup Information" required><?php echo $transfer_data->pick_up_info;?></textarea>
								</div>
							</div>
							<div class='form-group clearfix'>
								<label class='control-label col-sm-3' for='validation_excludes'>Guidelines List</label>
								<div class='col-sm-4 controls'>
									<!-- <input type="text" name="excludes" id="excludes" data-rule-required='true' class='form-control'> -->
									<textarea name="guidelines_list"
										class="form-control rate_card_elements"
										data-rule-required="true" cols="70" rows="3"
										placeholder="Guidelines List" required><?php echo $transfer_data->guidelines_list;?></textarea>
								</div>
							</div>
							<div class='form-group clearfix'>
								<label class='control-label col-sm-3' for='validation_excludes'>Contact Address</label>
								<div class='col-sm-4 controls'>
									<!-- <input type="text" name="excludes" id="excludes" data-rule-required='true' class='form-control'> -->
									<textarea name="contact_address"
										class="form-control rate_card_elements"
										data-rule-required="true" cols="70" rows="3"
										placeholder="Contact Address" required><?php echo $transfer_data->contact_address;?></textarea>
								</div>
							</div>
							<div class='form-group clearfix'>
								<label class='control-label col-sm-3' for='validation_excludes'>Contact Email</label>
								<div class='col-sm-4 controls'>
									<!-- <input type="text" name="excludes" id="excludes" data-rule-required='true' class='form-control'> -->
									<input type="text" name="contact_email"
                    class="form-control rate_card_elements"
                    data-rule-required="true" id="contact_email"
                    placeholder="Contact Email" value="<?php echo $transfer_data->contact_email;?>" required>
								</div>
							</div>
							<div class='form-actions' style='margin-bottom: 0'>
								<div class='row'>
									<div class='col-sm-9 col-sm-offset-3 bbh'>
									
										<button class='btn btn-primary'>Submit</button>
										<a class='btn btn-primary' id="back_button">
											Back</a>&nbsp;&nbsp; <a class='btn btn-primary'
											href="<?php echo base_url(); ?>transfers/view_transfer_list">
											Cancel</a>
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
					<label class='control-label col-sm-3 col-md-offset-2' for='validation_desc'>Date Range</label>
					<div class='col-sm-6 controls'>
						<input type="text" name="date_range" id="edit_date_range"
							data-rule-required='true' placeholder="Date Range"
							class='form-control vehicle_elements date' required readonly>
							<input type="hidden" name="date_range_id" id="edit_date_range_id">
					</div>
				</div>

				<!-- <div class='form-group clearfix'>
					<label class='control-label col-sm-3 col-md-offset-2' for='validation_desc'>Day </label>
					<div class='col-sm-6 controls'>
						<input type="hidden" name="reference_id" value="<?=$id?>">
						<input type="hidden" name="update_id" id="update_id">
						<input type="text" name="day" id="edit_days_option"
							data-rule-required='true' placeholder="Date"
							class='form-control vehicle_elements' required readonly>
					</div>
				</div>
				<div class='form-group clearfix'>
					<label class='control-label col-sm-3 col-md-offset-2' for='validation_desc'>Date </label>
					<div class='col-sm-6 controls'>
						<input type="text" name="date" id="edit_date"
							data-rule-required='true' placeholder="Date"
							class='form-control vehicle_elements date' required readonly>
					</div>
				</div>

				<div class='form-group clearfix'>
					<label class='control-label col-sm-3 col-md-offset-2' for='validation_desc'>Weekday </label>
					<div class='col-sm-6 controls'>
						<input type="text" name="weekday" id="edit_weekday"
							data-rule-required='true' placeholder="Weekday"
							class='form-control vehicle_elements weekday' required readonly>
					</div>
				</div> -->

				<div class='form-group' style="float: left; width:100%;">
					<label class='control-label col-sm-3 col-md-offset-2' for='validation_name'> Shift Time
					</label>
					<div>
					<div class='col-sm-3 controls'>
						<input type="hidden" name="reference_id" value="<?=$id?>">
						<input type="hidden" name="update_id" id="update_id">
						<input type="text" name="shift_from" id="edit_vehicle_shift_from" data-rule-required='true' placeholder="Shift From" class='form-control vehicle_elements vehicle_shift_time_start' required readonly>
					</div>
					<span style="margin-left: -148px; font-weight: bold;"> to </span>
					<div class='col-sm-3 controls'>
						 <input type="text" name="shift_to" id="edit_vehicle_shift_to" data-rule-required='true' placeholder="Shift To" class='form-control vehicle_elements vehicle_shift_time_end' required readonly>
					</div>
					<div id="error_data"></div>
					</div>
				</div>
				
				<div class='form-group clearfix'>
					<label class='control-label col-sm-3 col-md-offset-2' for='validation_desc'>Vehicle </label>
					<div class='col-sm-6 controls'>
						 <select class='form-control vehicle_elements vehicle_list_option'
							data-rule-required='true' name="vehicle" id="edit_vehicle_list_option" required>
							<option value="">--Select Option--</option>
							
						</select>
					</div>
				</div>
				
				<div class='form-group clearfix'>
					<label class='control-label col-sm-3 col-md-offset-2' for='validation_desc'>Driver </label>
					<div class='col-sm-6 controls'>
						 <select class='form-control vehicle_elements driver_list_option'
							data-rule-required='true' name="driver" id="edit_driver_list_option" required>
							<option value="">--Select Option--</option>
							
						</select>
					</div>
				</div>
				<div class='form-actions' style='margin-bottom: 8px'>
				<div class='row'>
					<div class='col-sm-9 col-sm-offset-5'>
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

		$('form').submit(function(e) {
			$(':disabled').each(function(e) {
			$(this).removeAttr('disabled');
			})
		});
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
$('#add_package_tab').click(function(){
	$("#add_package_li").addClass("active");
    $("#add_package").addClass("active");
	$("#vehicle_li").removeClass("active");
    $("#vehicle").removeClass("active");
    $("#price_li").removeClass("active");
	$("#price_manage").removeClass("active");
	$("#rate_card_li").removeClass("active");
	 $("#rate_card").removeClass("active");
	  });
$('#mapping_tab').click(function(){
	$("#vehicle_li").addClass("active");
    $("#vehicle").addClass("active");
	$("#add_package_li").removeClass("active");
    $("#add_package").removeClass("active");
    $("#price_li").removeClass("active");
	$("#price_manage").removeClass("active");
	$("#rate_card_li").removeClass("active");
	 $("#rate_card").removeClass("active");
	  });
$('#price_tab').click(function(){
    $("#price_li").addClass("active");
	$("#price_manage").addClass("active");
	$("#add_package_li").removeClass("active");
    $("#add_package").removeClass("active");
	$("#vehicle_li").removeClass("active");
    $("#vehicle").removeClass("active");
	$("#rate_card_li").removeClass("active");
	 $("#rate_card").removeClass("active");
	  });
$('#rate_tab').click(function(){

	$("#rate_card_li").addClass("active");
	 $("#rate_card").addClass("active");
	$("#add_package_li").removeClass("active");
    $("#add_package").removeClass("active");
	$("#vehicle_li").removeClass("active");
    $("#vehicle").removeClass("active");
    $("#price_li").removeClass("active");
	$("#price_manage").removeClass("active");
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
      	  $('body').scrollTop(0);
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
	  $('body').scrollTop(0);
      }
 });
$('.price_submit_button').click(function(e){
    	var k=0;
   	    $( ".price_details").each(function() {
    	var date_range_price = $(this).closest(".price_details").data('date_range_price');
    	var country = $(this).closest(".price_details").data('country');     
    	var lenth_tr = $(this).closest('.price_management_tr_'+country).length;
    	var div_price = $(this).closest('.price_details').find('.price_management_tr_'+country);
    	var i=0;
   	    $( div_price).each(function() { 
   	    	if(i<div_price.length){
   	    	if($('#mon_'+k).is(':checked')){
		    }else if($('#tue_'+k).is(':checked')){
		    }else if($('#wed_'+k).is(':checked')){
		    }else if($('#thu_'+k).is(':checked')){
		    }else if($('#fri_'+k).is(':checked')){
		    }else if($('#sat_'+k).is(':checked')){
		    }else if($('#sun_'+k).is(':checked')){
		    }else{
		    	alert('Please make sure that you selected atlease one shift day in each row!!');
		    	e.preventDefault();
		    	return false;
		    }
	        i++;
	    	k++;
	        }        
	    });
	    });
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
    	var cc = $('.dates_div').length;
    	for(var i=0;i<=cc;i++){
        $('#tour_start_date_'+cc).datepicker({
        	minDate:0,
        	numberOfMonths: 2,
        	changeMonth: !0,
        	dateFormat: "d-M-y"
        });

         $('#tour_expire_date_'+cc).datepicker({
        	minDate:0,
        	numberOfMonths: 2,
        	changeMonth: !0,
        	dateFormat: "d-M-y"
        });

	}

	     $(document).on('change','.tour_start_date',function(){
			$(this).parent().parent().find(".tour_expire_date").val('');
         	$(this).parent().parent().find(".days").val('');	
         	var thisObj = $(this);
         	check_date_avail(thisObj);


         });

	     $(document).on('change','.tour_expire_date',function(){
		 // $('#tour_expire_date_'+cc).on('change',function(){
		 	var thisObj = $(this);
		 	
	     	var end= $(this).datepicker("getDate");
		    var start= $(this).parent().parent().find(".tour_start_date").datepicker("getDate");
		    var et = new Date(end);
		    var st = new Date(start);
		    if(et >=st){
		    	var days = (end- start) / (1000 * 60 * 60 * 24);
			    var no_days = Math.round(days+1)
			    $(this).parent().parent().find('.days').val(no_days);
		    }else{
		    	 $(this).parent().parent().find(".tour_expire_date").val('');
         	     $(this).parent().parent().find(".days").val('');
         	    alert('Please Select Proper Date Range');
		    }
		    
			// get_days_options(start,end);
			check_date_avail(thisObj);

	     });

		$(document).ready(function(){
			// var start = '<?php echo $transfer_data->start_date;?>';
			// var end = '<?php echo $transfer_data->expiry_date;?>';
			// get_days_options(start,end);
		});


		function check_date_avail(thisObj){
			var ll = thisObj.parent().parent().parent().find(".dates_div").length;
         	var choose_date = new Date(thisObj.val());
         	var i=1;
         	$('.dates_div').each(function(){
         		var start_date = new Date($(this).find(".tour_start_date").val());
         		var end_date = new Date($(this).find(".tour_expire_date").val());
         		if(i != ll && ll!=1){
         			if(choose_date >= start_date && choose_date <= end_date){
         				alert('Already selected date!');
         				thisObj.val('');
         			}
         		
         		}
         		i++;
         	});
		}

		$(document).on('change','#available_date_range',function(){
			  var no_days = $(this).find(':selected').data('no_days');
			  var start = $(this).find(':selected').data('start_date');
			  var end = $(this).find(':selected').data('end_date');
			  get_days_options(start,end);
			  // alert(no_days);
		   	});

	    function get_days_options(start,end){
	    	$('.days_option').html('');
		    $('.days_option').append('<option value="0">Select Days</option');
		   // alert();
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
    	$('.price_shift_to').timepicker({
	        timeFormat: 'h:mm p',
            // startTime: '8:00 am',
            dynamic: true,
            dropdown: true,
            scrollbar: true
	    });
	    $('.price_shift_from').timepicker({
	        timeFormat: 'h:mm p',
            // startTime: '8:00 am',
            dynamic: true,
            dropdown: true,
            scrollbar: true
	    });
	    $('#time').timepicker({
	        timeFormat: 'h:mm p',
            // startTime: '8:00 am',
            dynamic: true,
            dropdown: true,
            scrollbar: true
	    });
	    var tt = $('.price_management_tr').length;
	    for(var i=0; i <=tt; i++){
	    	$('#price_shift_to_'+i).timepicker({startTime: '6:00 am',scrollbar: true});
		  $('#price_shift_from_'+i).timepicker({startTime: '6:00 am',scrollbar: true,change: checkTimeAvailbility});
	    
	    }

	    // $('#price_shift_to_1').timepicker();
	    // $('#price_shift_from_1').timepicker();
	    $('#vehicle_shift_to_1').timepicker({startTime: '6:00 am',scrollbar: true,change: setDefaultTime});
	    $('#vehicle_shift_from_1').timepicker({startTime: '6:00 am',scrollbar: true,change: checkAvailabilityTime});

	    //edit shift time.........
	    $('#edit_vehicle_shift_to').timepicker({startTime: '6:00 am',scrollbar: true,change: setEditDefaultTime});
	    $('#edit_vehicle_shift_from').timepicker({startTime: '6:00 am',scrollbar: true,change: checkAvailabilityTime_edit});
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
									data-rule-required='true' placeholder="Shift From"
									class='form-control vehicle_elements vehicle_shift_time_start' required readonly>
	                          </td>
	                          <td>
	                              <input type="text" name="vehicle[${length}][shift_to]" id="vehicle_shift_to_${count}"
								data-rule-required='true' placeholder="Shift To"
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
			    $('.days_option_'+count).append('<option value="0">Select Days</option');
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
    		var date_range_price = $(this).closest('.price_details').data('date_range_price');
    		var vall= date_range_price.split(' - ');
    		var from_date = vall[0];
    		var to_date = vall[1];
    		// alert(country);
    		var error_free = true;
		    $( ".price_elements" ).each(function() {
		        if($( this ).val() == ''){
		          error_free = false;
		          $( this ).focus();
		          $( this ).closest( ".form-group" ).addClass( "has-error" );
		        }        
		      });
		    var options = '';
		    var count = $('.price_management_tr').length;
		    var check_cnt = count;
		    if(count>0){
		    check_cnt = count-1;
		    }
		    if($('#mon_'+check_cnt).is(':checked')){
		    }else if($('#tue_'+check_cnt).is(':checked')){
		    }else if($('#wed_'+check_cnt).is(':checked')){
		    }else if($('#thu_'+check_cnt).is(':checked')){
		    }else if($('#fri_'+check_cnt).is(':checked')){
		    }else if($('#sat_'+check_cnt).is(':checked')){
		    }else if($('#sun_'+check_cnt).is(':checked')){
		    }else{
		    	alert('Please select any shift day!!');
		    	return false;
		    }
			<?php
			$driver_shift_days = json_decode($transfer_data->driver_shift_days,true);
			foreach ($driver_shift_days as $shift_key => $value) { ?>
				var avlble_weekdys = '<?=$value?>';
				if(avlble_weekdys == 1) {
				options += '<input class="form-control checkbox" id="mon_'+count+'" name="price['+count+'][shift_day][]" value="1" type="checkbox" onclick="uncheck_val('+count+');" ><label for="mon_'+count+'">Mon</label>&nbsp;';
      		}else if(avlble_weekdys == 2){
      			options += '<input class="form-control checkbox" id="tue_'+count+'" name="price['+count+'][shift_day][]" value="2" type="checkbox" onclick="uncheck_val('+count+');" ><label for="tue_'+count+'">Tue</label>&nbsp;';
      		}else if(avlble_weekdys == 3){
      			options += '<input class="form-control checkbox" id="wed_'+count+'" name="price['+count+'][shift_day][]" value="3" type="checkbox" onclick="uncheck_val('+count+');" ><label for="wed_'+count+'">Wed</label>&nbsp;';	
      		}else if(avlble_weekdys == 4){
      			options += '<input class="form-control checkbox" id="thu_'+count+'" name="price['+count+'][shift_day][]" value="4" type="checkbox" onclick="uncheck_val('+count+');" ><label for="thu_'+count+'">Thu</label>&nbsp;';
      		}else if(avlble_weekdys == 5){
      			options += '<input class="form-control checkbox" id="fri_'+count+'" name="price['+count+'][shift_day][]" value="5" type="checkbox" onclick="uncheck_val('+count+');" ><label for="fri_'+count+'">Fri</label>&nbsp;';
      		}else if(avlble_weekdys == 6){
      			options += '<input class="form-control checkbox" id="sat_'+count+'" name="price['+count+'][shift_day][]" value="6" type="checkbox" onclick="uncheck_val('+count+');" ><label for="sat_'+count+'">Sat</label>&nbsp;';
      		}else if(avlble_weekdys == 7){
      			options += '<input class="form-control checkbox" id="sun_'+count+'" name="price['+count+'][shift_day][]" value="7" type="checkbox" onclick="uncheck_val('+count+');" ><label for="sun_'+count+'">Sun</label>&nbsp;';
      		}
      		
			<?php	}	
			?>
			options += '<input class="form-control checkbox" name="alll_val" id="selectall_val'+count+'" onclick="checkall_val('+count+');" type="checkbox" ><label for="selectall_val'+count+'">All</label>';
		      if(error_free)
		      {
		      	// alert(country);
	    		var price_div = '';
	    		if(count<=20){
	    		// var length = 1;
	    		// alert(count);
				price_div +=`<tr class="price_management_tr price_management_tr_${country}">
	                          <td>
	                            `+options+`
						      </td>
	                          <td>	
	                          		<input type="hidden" name="price[${count}][country]" value="${country}">
	                          		<input type="hidden" name="price[${count}][date_range_price]" value="${date_range_price}">
	                          		<input type="hidden" name="price[${count}][date_from]" value="${from_date}">
	                          		<input type="hidden" name="price[${count}][date_to]" value="${to_date}">
	                          		<input type="hidden" name="price[${count}][country_name]" value="${country_name}">
	                          		<input type="text" name="price[${count}][shift_from]" id="price_shift_from_${country}_${count}"  data-value="${count}"
									data-rule-required='true' placeholder="Shift From"
									class='form-control price_elements price_shift_from' data-value="${count}" required readonly>
	                          </td>
	                          <td>
	                              <input type="text" name="price[${count}][shift_to]" id="price_shift_to_${country}_${count}"
								data-rule-required='true' placeholder="Shift To"
								class='form-control price_elements price_shift_to' required readonly>
								</td>	
	                          <td>
	                              <input type="text" name="price[${count}][price]" id="price_${country}_${count}"
								data-rule-required='true' placeholder="Price"
								class='form-control price_elements' required>
	                          </td>  
	                          <td><div class="dropdown2" role="group">
					   <div class="dropdown slct_tbl pull-left sideicbbb">
						   <i class="fa fa-ellipsis-v"></i>  
						    <ul class="dropdown-menu sidedis" >

											<li><a class="sideicbb5 sidedis remove_price" data-placement="top" 
												title=""
												data-original-title="Remove Price"> <i
													class="glyphicon glyphicon-remove"></i>Remove Price 
											</a></li>
										</ul>
									</div>
								</div>
	                          </td>

	                        </tr>`;

				// length++;
				// alert(country);
				// $('.price_management_tr_'+country+':last').after(price_div);
				 $(price_div).insertBefore($(this).closest('tr'));
				//$('.price_management_tbody').append(price_div);
				$('#price_shift_to_'+country+'_'+count).timepicker({startTime: '6:00 am',scrollbar: true});
		        $('#price_shift_from_'+country+'_'+count).timepicker({startTime: '6:00 am',scrollbar: true,change: checkTimeAvailbility});
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
    			// return confirm("Do you want delete this record?"); 
    		 $(this).closest('.price_management_tr').remove();
    		}
    	});
    	$(document).on('click','.edit_price',function(){
    		var val = $(this).data('value');
    		var group = $(this).data('group');
    		var ary = ['mon_','tue_','wed_','thu_','fri_','sat_','sun_','selectall_val'];
    		for(var i=0; i<ary.length; i++){
    			$('#'+ary[i]+val).removeAttr("disabled");
    		}
    		$('#price_shift_from_'+group+'_'+val).removeAttr("disabled");
    		$('#price_shift_to_'+group+'_'+val).removeAttr("disabled");
    		$('#price_'+group+'_'+val).removeAttr("disabled");
    	});
    	$(document).on('click','.delete_price',function(){
    		var confirmVal = window.confirm("Do you want delete this record?");
            if( confirmVal == true ){  
    		var price_id = $(this).data('value');
    		$.ajax({
            type:"POST",
            url: "<?php echo base_url(); ?>transfers/delete_transfer_price",
            data:{
            	price_id:price_id
            },
            success:function(response)
            {
             location.reload();
            }
          });
    	 }
    	});

    	
    });

	function checkAvailabilityTime(){
		// var shift_time_end = $(this).val();
		// var shift_time_start = $('.vehicle_shift_time_start').val();
		var shift_time_start = $('.vehicle_shift_time_start').val();
		// var day = $('.days_option').val();
		// var date = $('#date').val();
		// var weekDay = $('#weekday').val();
		var reference_id='<?=$id?>';
		// if(shift_time_start!='' && shift_time_start!='undefined' && day!='' && day!='undefined' && date!='' && date!='undefined' && weekDay!='' && weekDay!='undefined' )
		if(shift_time_start!='' && shift_time_start!='undefined'){
			// console.log(shift_time_start);
		 $.ajax({
            type:"POST",
            url: "<?php echo base_url(); ?>transfers/check_vehicle_mapping",
            data:{
            	reference_id:reference_id,
            	shift_time_start:shift_time_start
            	// shift_time_end:shift_time_end
            },
            success:function(response)
            {
             var data = $.parseJSON(response);
             // console.log(data.result); 
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
function checkAvailabilityTime_edit(){

		var shift_time_start = $('#edit_vehicle_shift_from').val();
		// // var shift_time_start = $('.vehicle_shift_time_start').val();
		// var day = $('#edit_days_option').val();
		// var date = $('#edit_date').val();
		// var weekDay = $('#edit_weekday').val();
		var reference_id='<?=$id?>';
		if(shift_time_start!='' && shift_time_start!='undefined' ){
		 $.ajax({
            type:"POST",
            url: "<?php echo base_url(); ?>transfers/check_vehicle_mapping_time",
            data:{
            	reference_id:reference_id,
            	shift_time_start:shift_time_start
            	// shift_time_end:shift_time_end
            },
            success:function(response)
            {
             var data = $.parseJSON(response);
             // console.log(data.result); 
             if(data.result == false){
             	alert('Already mapped vehicle');
             	$('#edit_vehicle_shift_from').val('');
             }
            }
          });
		}else{
			alert('Day information required !');
			$('.vehicle_shift_time_start').val('');
		}
	}
	function setDefaultTime(){
		var avlble_weekdys = [];
		var shift_time_end = $(this).val();
		var shift_time_start = $('.vehicle_shift_time_start').val();
		var start_date = $('#available_date_range').find(':selected').attr('data-start_date');
		var end_date = $('#available_date_range').find(':selected').attr('data-end_date');
		var i = 0;
		<?php
		$driver_shift_days = json_decode($transfer_data->driver_shift_days,true);
		foreach ($driver_shift_days as $shift_key => $value) { ?>
			avlble_weekdys[i] = '<?=$value?>';
			i++;
		<?php	}	
		?>
		// var day = $('.driver_shift_days').val();
		// var date = $('#date').val();
		// var weekDay = $('#weekday').val();
		
		if(shift_time_start!='' && shift_time_start!='undefined' ){
			// console.log(shift_time_start);
		 $.ajax({
            type:"POST",
            url: "<?php echo base_url(); ?>transfers/get_vehicle_list",
            data:{
            	avlble_weekdys:avlble_weekdys,
            	start_date:start_date,
            	end_date:end_date,
            	shift_time_start:shift_time_start,
            	shift_time_end:shift_time_end
            },
            success:function(response)
            {
            	
            	//$('#error_data').append('');
            	$('#driver_list_option').append('');
            	$('#vehicle_list_option').append('');
            	
            	
              var data = $.parseJSON(response);
              if(data.result.vehicle != ''){
              	$('#vehicle_list_option').html('');
	              $.each(data.result.vehicle,function(key,val){
	              	
	              	

	              	// thisObj.closest('.vehicle_management_tr').find('.vehicle_list_option').append('<option value="'+val.id+'">'+val.vehicle_name+'</option>');
	              	$('#vehicle_list_option').append('<option value="'+val.id+'">'+val.vehicle_name+'</option>');
	              });
              }else{
              	$('#vehicle_list_option').html('');
              	$('#vehicle_list_option').append('<option value="">Vehicle not available!</option>');
              	alert('Vehicle not available in this day!');
              }
              
              if(data.result.driver != ''){
              	$('#driver_list_option').html('');
              $.each(data.result.driver,function(key,val){
              	// thisObj.closest('.vehicle_management_tr').find('.driver_list_option').append('<option value="'+val.id+'">'+val.driver_name+'</option>');
              	$('#driver_list_option').append('<option value="'+val.id+'">'+val.driver_name+'</option>');
              });
               }else{
               	$('#driver_list_option').html('');
              	$('#driver_list_option').append('<option value="">Driver not available!</option>');
              	alert('Driver not available in this day!');
              }



              // $.each(data.result.vehicle,function(key,val){
              // 	// thisObj.closest('.vehicle_management_tr').find('.vehicle_list_option').append('<option value="'+val.id+'">'+val.vehicle_name+'</option>');
              // 	$('#vehicle_list_option').append('<option value="'+val.id+'">'+val.vehicle_name+'</option>');
              // });
              // $.each(data.result.driver,function(key,val){
              // 	// thisObj.closest('.vehicle_management_tr').find('.driver_list_option').append('<option value="'+val.id+'">'+val.driver_name+'</option>');
              // 	$('#driver_list_option').append('<option value="'+val.id+'">'+val.driver_name+'</option>');
              // });
            }
          });
		}else{
			alert('Day information required !');
		}
		
     }


	function setEditDefaultTime(){
		// var thisObj = $(this);
		var shift_time_end = $(this).val();
		var shift_time_start = $('#edit_vehicle_shift_from').val();
		// var shift_time_start = $('.vehicle_shift_time_start').val();
		// var day = $('#edit_days_option').val();
		// var date = $('#edit_date').val();
		// var weekDay = $('#edit_weekday').val();
		
		if(shift_time_start!='' && shift_time_start!='undefined'){
			// console.log(shift_time_start);
		 $.ajax({
            type:"POST",
            url: "<?php echo base_url(); ?>transfers/get_vehicle_list",
            data:{
            	shift_time_start:shift_time_start,
            	shift_time_end:shift_time_end
            },
            success:function(response)
            {
            	$('#edit_driver_list_option').append('');
            	$('#edit_vehicle_list_option').append('');
              var data = $.parseJSON(response);
              if(data.result.vehicle != ''){
              	$('#edit_vehicle_list_option').html('');
	              $.each(data.result.vehicle,function(key,val){
	              	
	              	// thisObj.closest('.vehicle_management_tr').find('.vehicle_list_option').append('<option value="'+val.id+'">'+val.vehicle_name+'</option>');
	              	$('#edit_vehicle_list_option').append('<option value="'+val.id+'">'+val.vehicle_name+'</option>');
	              });
              }else{
              	$('#edit_vehicle_list_option').html('');
              	$('#edit_vehicle_list_option').append('<option value="">Vehicle not available!</option>');
              }
              
              if(data.result.driver != ''){
              	$('#edit_driver_list_option').html('');
              $.each(data.result.driver,function(key,val){
              	// thisObj.closest('.vehicle_management_tr').find('.driver_list_option').append('<option value="'+val.id+'">'+val.driver_name+'</option>');
              	$('#edit_driver_list_option').append('<option value="'+val.id+'">'+val.driver_name+'</option>');
              });
               }else{
               	$('#edit_driver_list_option').html('');
              	$('#edit_driver_list_option').append('<option value="">Driver not available!</option>');
              }
            }
          });
		}else{
			alert('Day information required !');
		}
		
     }

$('#country_list').on('change',function(){
	var country_id = $(this).val();
	$('.add_nationality_table').removeAttr('disabled');
	// $('#date_range_price').val('');
	// $('.add_nationality_table').attr('disabled','disabled');
	//    $.ajax({
 //            type:"POST",
 //            url: "<?php echo base_url(); ?>transfers/get_date_range_price/"+country_id,
 //            // data:{country:country},
 //            success:function(data)
 //            {
 //             var data = $.parseJSON(data);
 //             // console.log(data.result.date_range_price_code);
 //             $('#date_range_price').val(data.result.date_range_price_code);
             
 //            }
 //          });

});


	
	$('.add_nationality_table').on('click',function(){
		var country = $('#country_list').val();
		var date_range = $('#date_range_price').val();
		if(country==''){
			alert('Please Select Nationality Group!!');
			return false;
		}
		if(date_range==''){
			alert('Please Select Date Range!!');
			return false;
		}
		var country_name = $("#country_list option:selected").text();
		var start_date = $('#date_range_price').find(':selected').attr('data-start_date');
		var end_date = $('#date_range_price').find(':selected').attr('data-end_date');
		var date_range_price = start_date+' - '+end_date
		// console.log(country);
		// console.log(country_name);
		// var count = $('.price_management_tr').length +1;
		var price_table_div = '';
		var length = $('.price_details').length;
		var count = $('.price_management_tr').length;
		// alert(count);
		var options = '';
		<?php
		$driver_shift_days = json_decode($transfer_data->driver_shift_days,true);
		foreach ($driver_shift_days as $shift_key => $value) { ?>
			var avlble_weekdys = '<?=$value?>';
			// if(avlble_weekdys == 1) {
			// 	options += '<option value="1">Monday</option>';
   //    		}else if(avlble_weekdys == 2){
   //    			options += '<option value="2">Tuesday</option>';
   //    		}else if(avlble_weekdys == 3){
   //    			options += '<option value="3">Wednesday</option>';	
   //    		}else if(avlble_weekdys == 4){
   //    			options += '<option value="4">Thursday</option>';
   //    		}else if(avlble_weekdys == 5){
   //    			options += '<option value="5">Friday</option>';
   //    		}else if(avlble_weekdys == 6){
   //    			options += '<option value="6">Saturday</option>';
   //    		}else if(avlble_weekdys == 7){
   //    			options += '<option value="7">Sunday</option>';
   //    		}

      		if(avlble_weekdys == 1) {
				options += '<input class="form-control checkbox" id="mon_'+count+'" name="price['+count+'][shift_day][]" value="1" type="checkbox" onclick="uncheck_val('+count+');" ><label for="mon_'+count+'">Mon</label>&nbsp;';
      		}else if(avlble_weekdys == 2){
      			options += '<input class="form-control checkbox" id="tue_'+count+'" name="price['+count+'][shift_day][]" value="2" type="checkbox" onclick="uncheck_val('+count+');" ><label for="tue_'+count+'">Tue</label>&nbsp;';
      		}else if(avlble_weekdys == 3){
      			options += '<input class="form-control checkbox" id="wed_'+count+'" name="price['+count+'][shift_day][]" value="3" type="checkbox" onclick="uncheck_val('+count+');" ><label for="wed_'+count+'">Wed</label>&nbsp;';	
      		}else if(avlble_weekdys == 4){
      			options += '<input class="form-control checkbox" id="thu_'+count+'" name="price['+count+'][shift_day][]" value="4" type="checkbox" onclick="uncheck_val('+count+');" ><label for="thu_'+count+'">Thu</label>&nbsp;';
      		}else if(avlble_weekdys == 5){
      			options += '<input class="form-control checkbox" id="fri_'+count+'" name="price['+count+'][shift_day][]" value="5" type="checkbox" onclick="uncheck_val('+count+');" ><label for="fri_'+count+'">Fri</label>&nbsp;';
      		}else if(avlble_weekdys == 6){
      			options += '<input class="form-control checkbox" id="sat_'+count+'" name="price['+count+'][shift_day][]" value="6" type="checkbox" onclick="uncheck_val('+count+');" ><label for="sat_'+count+'">Sat</label>&nbsp;';
      		}else if(avlble_weekdys == 7){
      			options += '<input class="form-control checkbox" id="sun_'+count+'" name="price['+count+'][shift_day][]" value="7" type="checkbox" onclick="uncheck_val('+count+');" ><label for="sun_'+count+'">Sun</label>&nbsp;';
      		}

		<?php	}	
		?>
		options += '<input class="form-control checkbox" name="alll_val" id="selectall_val'+count+'" onclick="checkall_val('+count+');" type="checkbox" ><label for="selectall_val'+count+'">All</label>';
		
		price_table_div +=`
		<div class="price_details" data-country="${country}" data-country_name="${country_name}" data-date_range_price="${date_range_price}">
			  <div class='form-group'>
			
				<table class="table table-bordered table-striped table-highlight">
				    <thead>
				    	<tr>
				    		<th colspan="8">  ${country_name} (${date_range_price}) <span class="glyphicon glyphicon-remove pull-right remove_price_table"></span></th>	
				    	</tr>
                        <tr>
                          <th>Days</th>
                          <th>Shift From</th>
                          <th>Shift To</th>
                          <th>Price (AED)</th>
                          <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="price_management_tbody_${length}">
                       <tr class="price_management_tr price_management_tr_${country}">
                           <td>
								`+options+`
					      </td>
                            <td>
                          		<input type="hidden" name="price[${count}][country]" value="${country}">
	                          	<input type="hidden" name="price[${count}][date_range_price]" value="${date_range_price}">
	                          	<input type="hidden" name="price[${count}][date_from]" value="${start_date}"><input type="hidden" name="price[${count}][date_to]" value="${end_date}">
	                          	<input type="hidden" name="price[${count}][country_name]" value="${country_name}">
                          		<input type="text" name="price[${count}][shift_from]" id="price_shift_from_${country}_${count}" data-value="${count}"
								data-rule-required='true' placeholder="Shift From" 
								class='form-control price_elements price_shift_from' data-value="${count}" required readonly>
                          </td>
                          <td>
                              <input type="text" name="price[${count}][shift_to]" id="price_shift_to_${country}_${count}"
							data-rule-required='true' placeholder="Shift To"
							class='form-control price_elements price_shift_to' required readonly>
							</td>
	                          <td>
	                              <input type="text" name="price[${count}][price]" id="price_${country}_${count}"
								data-rule-required='true' placeholder="Price"
								class='form-control price_elements' required>
	                          </td> 
                          <td>
                          <div class="dropdown2" role="group">
					   <div class="dropdown slct_tbl pull-left sideicbbb">
						   <i class="fa fa-ellipsis-v"></i>  
						    <ul class="dropdown-menu sidedis" >

													<li><a class="sideicbb5 sidedis remove_price" data-placement="top" href=""
												title=""
												data-original-title="Remove Price"> <i
													class="glyphicon glyphicon-remove"></i>Remove Price
											</a></li>
										</ul>
									</div>
								</div>
                              
                          </td>
                        </tr>
                        

                      <tr>
                      	<td colspan="8"><div class="add_price_btn"><span class="btn btn-primary pull-right">Add Price</span></div></td>
                      </tr>
                       
                      </tbody>
                   </table>
					
				</div>
			</div>`;
// <button type="button" class="btn btn-default btn-sm remove_price" style="margin-top: 8px;color: red;margin-bottom: 12px;">
// 						          <span class="glyphicon glyphicon-remove"></span> 
// 						    </button>
		// length++;
		// alert(country);
		if(country !='undefined' && country !=''){
		   $('.price_div').append(price_table_div);
		  $('#price_shift_to_'+country+'_'+count).timepicker({startTime: '6:00 am',scrollbar: true,change: checkTimeAmbiquity});
		  $('#price_shift_from_'+country+'_'+count).timepicker({startTime: '6:00 am',scrollbar: true,change: checkTimeAvailbility});
		  $('.price_submit_button').show();
		  var country = $('#country_list').val('');
		  var date_range_price = $('#date_range_price').val('');
		}else{
			alert('Please Select Country');
		}

		// $("#date_range_price option[value='"+date_range+"']").remove();
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
function checkTimeAmbiquity()
{
	var thisObj = $(this);
   	var change_value = $(this).val();
   	console.log(change_value+'current');
   	var country = $(this).closest('.price_details').data('country');
   	var div_price = $(this).closest('.price_details').find('.price_management_tr_'+country);
   	// alert(div_price.length);
   	var i=0;
   	    $( div_price).each(function() {
   	    	if(i<div_price.length){
   	    	var from_time = $(this).find('.price_shift_from').val();
   	    	// if(from_time == change_value){
	        //  alert('Time should not be same.');
	        //  thisObj.val('');
	        // }
	        i++;
	        }        
	    });
}

   function checkTimeAvailbility(){
   	var thisObj = $(this);
   	var change_value = $(this).val();
   	var count = $(this).data('value');
   	console.log(change_value+'current');
   	var country = $(this).closest('.price_details').data('country');
   	var div_price = $(this).closest('.price_details').find('.price_management_tr_'+country);
   	var k=0;
   	var trans_ary = [];
   	if($('#mon_'+count).is(':checked')) {
   	  	trans_ary[k] = 1;k++;	
   	}
	if($('#tue_'+count).is(':checked')) {
		trans_ary[k] = 2;k++;
	}
	if($('#wed_'+count).is(':checked')) {
		trans_ary[k] = 3;k++;
	}
	if($('#thu_'+count).is(':checked')) {
		trans_ary[k] = 4;k++;
	}
	if($('#fri_'+count).is(':checked')) {
		trans_ary[k] = 5;k++;
	}
	if($('#sat_'+count).is(':checked')) {
		trans_ary[k] = 6;k++;
	}
	if($('#sun_'+count).is(':checked')) {
		trans_ary[k] = 7;k++;
	}
   	var i=1;
   	    $( div_price).each(function() {
   	    	if(i<div_price.length){
   	    	var from_time = $(this).find('.price_shift_from').val();
   	    	var loop_id = $(this).find('.price_shift_from').data('value');
   	    	if(from_time == change_value){
   	    		if($('#mon_'+loop_id).is(':checked')) {
   	    			if(jQuery.inArray(1, trans_ary) != -1) {
   	    				alert('Time is already selected for Monday');
	         			thisObj.val('');return false;
      				}
   	    		}
				if($('#tue_'+loop_id).is(':checked')) {
					if(jQuery.inArray(2, trans_ary) != -1) {
   	    				alert('Time is already selected for Tuesday');
	         			thisObj.val('');return false;
      				}
				}
				if($('#wed_'+loop_id).is(':checked')) {
					if(jQuery.inArray(3, trans_ary) != -1) {
   	    				alert('Time is already selected for Wednesday');
	         			thisObj.val('');return false;
      				}
				}
				if($('#thu_'+loop_id).is(':checked')) {
					if(jQuery.inArray(4, trans_ary) != -1) {
   	    				alert('Time is already selected for Thursday');
	         			thisObj.val('');return false;
      				}
				}
				if($('#fri_'+loop_id).is(':checked')) {
					if(jQuery.inArray(5, trans_ary) != -1) {
   	    				alert('Time is already selected for Friday');
	         			thisObj.val('');return false;
      				}
				}
				if($('#sat_'+loop_id).is(':checked')) {
					if(jQuery.inArray(6, trans_ary) != -1) {
   	    				alert('Time is already selected for Saturday');
	         			thisObj.val('');return false;
      				}
				}
				if($('#sun_'+loop_id).is(':checked')) {
					if(jQuery.inArray(7, trans_ary) != -1) {
   	    				alert('Time is already selected for Sunday');
	         			thisObj.val('');return false;
      				}
				}
	         
	         // thisObj.find('.price_shift_to').val('');
	        }
	        i++;
	        }        
	    });
   }

   function mintohrs(mins){
   	   let hrs = Math.floor(mins / 60);  
        // getting the minutes. 
        let min = mins % 60;  
        // formatting the hours. 
         var dd = "AM";
		  if (hrs >= 12) {
		    dd = "PM";
		  }
		hrs = hrs < 10 ? '0' + hrs : hrs;  
        min = min < 10 ? '0' + min : min;  
        
        return `${hrs}:${min} ${dd}`;

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
              $('#edit_date_range').val(data.result.start_date +' to '+data.result.expiry_date);
             $('#update_id').val(data.result.c_id);
             $('#edit_date_range_id').val(data.result.date_range_id);
             $('#edit_days_option').val(data.result.day);
             $('#edit_date').val(data.result.date);
             $('#edit_weekday').val(data.result.weekday);

            var shift_time_from = mintohrs(data.result.shift_time_from);
            var shift_time_to = mintohrs(data.result.shift_time_to);

             $('#edit_vehicle_shift_from').val(shift_time_from);
             $('#edit_vehicle_shift_to').val(shift_time_to);

             // $('#edit_weekday').val(data.result.weekday);
             // $('#edit_weekday').val(data.result.weekday);
            }
          });

   	});

   	$('.add_avail_dates').on('click',function(){
   		var count = $('.dates_div').length;
   		var cc = count +1;
   		var dates_div = '';
   		if(count<5){
   		var dates_div =`<div class="dates_div">
					        <div class='col-sm-4 controls padfive'>
					        <label class="center">Start Date</label>
					        <input type="text" name="dates[${cc}][start_date]" id="tour_start_date_${cc}" data-rule-required='true'
										class='form-control add_pckg_elements tour_start_date' required value="" placeholder="Choose Date" data-rule-required='true'  readonly> 
					        </div>
					        <div class='col-sm-4 controls padfive'>
					        <label>Expiry Date</label>
					        <input type="text" name="dates[${cc}][expire_date]"  id="tour_expire_date_${cc}" data-rule-required='true' 
									class='form-control add_pckg_elements tour_expire_date' required value="" placeholder="Choose Date" data-rule-required='true' readonly> 
					        </div>
					        <div class='col-sm-2 controls padfive'>
					        <label>Days</label>
					        <input type="text" name="dates[${cc}][no_days]" id="days_${cc}"
										data-rule-minlength='2' data-rule-required='true'
										placeholder="Days" 
										class='form-control add_pckg_elements days' required readonly>
					        </div>
					        <div class='col-sm-2 close_bar controls padfive'>
					        	<span class="btn btn-primary"><i class="far fa-times-circle"></i></span>
					        </div>
					     </div>`;
		$('.available_dates').append(dates_div);

		 $('#tour_start_date_'+cc).datepicker({
        	minDate:0,
        	numberOfMonths: 2,
        	changeMonth: !0,
        	dateFormat: "d-M-y"
        });

         $('#tour_expire_date_'+cc).datepicker({
        	minDate:0,
        	numberOfMonths: 2,
        	changeMonth: !0,
        	dateFormat: "d-M-y"
        });
	}
	else{
		alert('Maximum Reached');
	}
   	});

   	$(document).on('click','.close_bar',function(){
   		$(this).closest('.dates_div').remove();
   	});




   });
$(document).ready(function(){
$('#tour_start_date_1').datepicker({
        	minDate:0,
        	numberOfMonths: 2,
        	changeMonth: !0,
        	dateFormat: "d-M-y"
        });
$('#tour_expire_date_1').datepicker({
        	minDate:0,
        	numberOfMonths: 2,
        	changeMonth: !0,
        	dateFormat: "d-M-y"
        });
var length = $('.price_details').length;
// alert(length);
if(length == 0){
	$('.price_submit_button').hide();
}else{
	$('.price_submit_button').show();
}
//get source and destination.........
   	 $(".airport_location").autocomplete({
       source: "<?=base_url();?>transfers/get_transfer_city_list",
       minLength: 2,//search after two characters     
       select: function(event,ui){
          /* console.log(ui.item.id);
           console.log(ui.item.country_code);
           console.log(ui.item.city);
           console.log(ui.item.origin);*/

           $("#airport_code").val(ui.item.id);
           $("#country_code").val(ui.item.country_code);
           $("#city").val(ui.item.city);
           $("#origin").val(ui.item.origin);
           $("#destination").val('');
          // if(ui.item.hasOwnProperty("id")==true){
          //    $(".airport_location").val(ui.item.id);
          // }else{
          //    $(".airport_location").val(0);
          // }
          // console.log(ui.item.hasOwnProperty("id"));
          //  $(".departflight").focus();
          //  $(".flighttoo").focus();
       },
       change: function(ev, ui) {
            if (!ui.item) {
                $(this).val("")
            }
        }
       
     });
   	 $('.airport_location2').on('click', function() {
           country_code = $('#country_code').val();
     $(".airport_location2").autocomplete({ 
       source: "<?=base_url();?>transfers/get_transfer_city_list/"+country_code,
       minLength: 2,//search after two characters 
       select: function(event,ui){
           /*console.log(ui.item.id);
           console.log(ui.item.country_code);
           console.log(ui.item.city);
           console.log(ui.item.origin);*/
          // if(ui.item.hasOwnProperty("id")==true){
          //    $(".airport_location").val(ui.item.id);
          // }else{
          //    $(".airport_location").val(0);
          // }
          // console.log(ui.item.hasOwnProperty("id"));
          //  $(".departflight").focus();
          //  $(".flighttoo").focus();
       },
       change: function(ev, ui) {
            if (!ui.item) {
                $(this).val("")
            }
        }
       
     });
     });

});
function showMyImage(fileInput) {
      $('#thumbnil').show();
      var files = fileInput.files;
      for (var i = 0; i < files.length; i++) { 
      var file = files[i];
      // var imageType = /image.*/; 
      // if (!file.type.match(imageType)) {
      // continue;
      // } 
      var img=document.getElementById("thumbnil"); 
      img.file = file; 
      var reader = new FileReader();
      reader.onload = (function(aImg) { 
      return function(e) { 
      aImg.src = e.target.result; 
      }; 
      })(img);
      reader.readAsDataURL(file);
      } 
    }
    
    function confirm_delt(){
    	return confirm("Do you want delete this record?"); 
    }
    function checkall(){
	if($('#selectall').is(':checked')) {
        $("#monday_0"). prop("checked", true);
        $("#tuesday_0"). prop("checked", true);
        $("#wednesday_0"). prop("checked", true);
        $("#thursday_0"). prop("checked", true);
        $("#friday_0"). prop("checked", true);
        $("#saturday_0"). prop("checked", true);
        $("#sunday_0"). prop("checked", true);
      }else{
      	$("#monday_0"). prop("checked", false);
        $("#tuesday_0"). prop("checked", false);
        $("#wednesday_0"). prop("checked", false);
        $("#thursday_0"). prop("checked", false);
        $("#friday_0"). prop("checked", false);
        $("#saturday_0"). prop("checked", false);
        $("#sunday_0"). prop("checked", false);
      }
    }
    function uncheck(){
    	if(!$(this).is(':checked')) {
		$("#selectall"). prop("checked", false);
    	}
    	if($('#monday_0').is(':checked') && $('#tuesday_0').is(':checked') && $('#wednesday_0').is(':checked') && $('#thursday_0').is(':checked') && $('#friday_0').is(':checked') && $('#saturday_0').is(':checked') && $('#sunday_0').is(':checked')){
    		$("#selectall"). prop("checked", true);
    	}
    }
    function checkall_val(id){
	if($('#selectall_val'+id).is(':checked')) {
        $("#mon_"+id). prop("checked", true);
        $("#tue_"+id). prop("checked", true);
        $("#wed_"+id). prop("checked", true);
        $("#thu_"+id). prop("checked", true);
        $("#fri_"+id). prop("checked", true);
        $("#sat_"+id). prop("checked", true);
        $("#sun_"+id). prop("checked", true);
      }else{
      	$("#mon_"+id). prop("checked", false);
        $("#tue_"+id). prop("checked", false);
        $("#wed_"+id). prop("checked", false);
        $("#thu_"+id). prop("checked", false);
        $("#fri_"+id). prop("checked", false);
        $("#sat_"+id). prop("checked", false);
        $("#sun_"+id). prop("checked", false);
      }
    }
     function uncheck_val(id){
    	if(!$(this).is(':checked')) {
		$("#selectall_val"+id). prop("checked", false);
    	}
    	if($('#mon_'+id).is(':checked') && $('#tue_'+id).is(':checked') && $('#wed_'+id).is(':checked') && $('#thu_'+id).is(':checked') && $('#fri_'+id).is(':checked') && $('#sat_'+id).is(':checked') && $('#sun_'+id).is(':checked')){
    		$("#selectall_val"+id). prop("checked", true);
    	}
    }
    function check_nationality_date_map(){
    	var nationality_grp = $('#country_list').val();
    	console.log(nationality_grp);
    	var start_date = $('#date_range_price').find(':selected').attr('data-start_date');
		var end_date = $('#date_range_price').find(':selected').attr('data-end_date');
		if((nationality_grp!='')&&(start_date!=undefined)&&(end_date!=undefined)){
			var date_range = start_date+' - '+end_date;
			console.log(date_range);
			<?php
			foreach ($price_data as $key => $price_info) { ?>
					console.log("loop");
				var nationality_id = <?=$price_info->nationality_group?>;
				
				var daterange = '<?=$price_info->date_range?>';
		
				if((nationality_grp==nationality_id)&&(date_range==daterange)){
					console.log(nationality_id);
							console.log(daterange);
					alert('Nationality Group is already selected for selected date range!!!');
					$('#date_range_price').val('');
					return false;
				}
			<?php
		}
			?>
		}else{return false;}
    }
    $('#contact_email').on('change',function(){
       var sEmail = document.getElementById('contact_email');
      if (sEmail.value != ''){
        var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
        if(!(sEmail.value.match(filter))){
          alert("Please input a valid email address!");
          $('#contact_email').focus();
          return false; 
        }else{
          var email_val = sEmail.value;
          var length_email = email_val.length;
          if(length_email>50){
            alert("Email Address Length Exceeded!! Maximumlenth allowed is 50.");
          $('#contact_email').focus();
          return false;
          }
        }
      }
      return false;
      
      });
</script> 