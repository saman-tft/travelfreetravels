<style type="text/css">
/*	input[type="checkbox"]{
    position: absolute;
     left: 0px; 
   
}*/
</style>
<div id="Package" class="bodyContent col-md-12">
	<div class="panel panel-default">
		<!-- PANEL WRAP START -->
		<div class="panel-heading">
			<!-- PANEL HEAD START -->
			<div class="panel-title">
				<ul class="nav nav-tabs nav-justified" role="tablist" id="myTab">
					<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->
					<li role="presentation" class="active" id="add_package_li"><a
						href="#add_package" aria-controls="home" role="tab"
						data-toggle="tab">Add Transfers </a></li>
					<li role="presentation" class="" id="vehicle_li"><a href="#vehicle"
						aria-controls="home" role="tab" data-toggle="">Vehicle Details
					</a></li>
					<li role="presentation" class="" id="price_li"><a href="#price_manage"
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
					<div role="tabpanel" class="tab-pane active" id="add_package">
						<div class="col-md-12">

							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_name'>Transfers type</label>
								<div class='col-sm-4 controls'>
									<select class='select2 form-control add_pckg_elements'
										data-rule-required='true' name="transfer_type" id="disn" required>
										<option value=''>Select Transfers Type</option>
                   					     <?php
											for($l = 0; $l < count ( $package_type_data ); $l ++) {
												?>
					                        <option value='<?php echo $package_type_data[$l]->package_types_id; ?>'> <?php echo $package_type_data[$l]->package_types_name; ?>  </option>
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
											placeholder="Enter Transfers Name"
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
											placeholder="Enter Source"
											class='form-control add_pckg_elements' required>
									</div>
								</div>
							</div>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>Destination </label>
								 <div class='col-sm-4 controls'>
									<div class="controls">
										<input type="text" name="destination" id="destination"
											data-rule-minlength='2' data-rule-required='true'
											placeholder="Enter Destination"
											class='form-control add_pckg_elements' required>
									</div>
								</div>
							</div>

							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>Distance </label>
								 <div class='col-sm-4 controls'>
									<div class="controls">
										<input type="text" name="destination" id="destination"
											data-rule-minlength='2' data-rule-required='true'
											placeholder="Enter Destination"
											class='form-control add_pckg_elements' required>
									</div>
								</div>
							</div>

							<div class='form-group ' id="select_date">
					        <label class='control-label col-sm-3' for='validation_current'>Start Date <span style = "color:red">*</span>
					        </label>
					        <div class='col-sm-4 controls'>
					        <input type="text" name="start_date" id="tour_start_date" data-rule-required='true'
										class='form-control add_pckg_elements' required value="" placeholder="Choose Date" data-rule-required='true'  readonly> 
					        </div>
					       </div>
							<div class='form-group ' id="select_date">
					        <label class='control-label col-sm-3' for='validation_current'>Expiry Date <span style = "color:red">*</span>
					        </label>
					        <div class='col-sm-4 controls'>
					        <input type="text" name="expire_date" id="tour_expire_date" data-rule-required='true'
										class='form-control add_pckg_elements' required value="" placeholder="Choose Date" data-rule-required='true' readonly> 
					        </div>
					       </div>

					       <div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>No of Days </label>
								 <div class='col-sm-4 controls'>
									<div class="controls">
										<input type="text" name="days" id="days"
											data-rule-minlength='2' data-rule-required='true'
											placeholder="Number of Days"
											class='form-control add_pckg_elements' required readonly>
									</div>
								</div>
							</div>

						
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_company'>Transfers
									Display Image</label>
								<div class='col-sm-4 controls'>
									<input type="file" title='Image to add'
										class='add_pckg_elements' data-rule-required='true' id='transfer_image'
										name='transfer_image' required> <span id="pacmimg"
										style="color: #F00; display: none">Please Upload Transfers Image</span>
								</div>
							</div>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_name'>Description</label>
								<div class='col-sm-4 controls'>
									<textarea name="description" data-rule-required='true'
										class="form-control add_pckg_elements" cols="70" rows="3"
										placeholder="Description" required></textarea>
									<!--   <span id="dorigin_error" style="color:#F00;  display:none;"></span> -->
								</div>
							</div>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_rating'>Rating
								</label>
								<div class="col-sm-4 controls">
									<select class='form-control add_pckg_elements'
										data-rule-required='true' name='rating' id="rating" required>
										<option value="0">0</option>
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
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
									<div class='col-sm-9 col-sm-offset-3'>
										<a class='btn btn-primary' id="add_package_button"> submit &
											continue</a>&nbsp;&nbsp; <a class='btn btn-primary'
											href="<?php echo base_url(); ?>supplier/view_transfer_list">
											Cancel</a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- Add Activity Ends -->

					<!-- Vehicle Starts -->
					<!-- <div role="tabpanel" class="tab-pane" id="vehicle">
						<div class="col-md-12">
							<div class="duration_info_class clearfix" id="">
								<div class='form-group clearfix'>
									<label class='control-label col-sm-3' for='validation_desc'>Day </label>
									<div class='col-sm-4 controls'>
										<select class='form-control'
										data-rule-required='true' name='days_option' id="days_option" required>
										<option value="0">All Days</option>
										
									</select>
									</div>
								</div>

								<div class='form-group'>
									<label class='control-label col-sm-3' for='validation_name'> Shift Time
									</label>
									<div>
									<div class='col-sm-2 controls'>
										<input type="text" name="driver_shift_from" id="driver_shift_from_1" value="<?php echo $data->driver_shift_from;?>"
											data-rule-required='true' placeholder="Driver Shift From"
											class='form-control vehicle_elements' required readonly>
									</div>
									<span style="margin-left: -166px; font-weight: bold;"> to </span>
									<div class='col-sm-2 controls'>
										<input type="text" name="driver_shift_to" id="driver_shift_to_1"
											data-rule-required='true' value="<?php echo $data->driver_shift_to;?>" placeholder="Driver Shift To"
											class='form-control vehicle_elements' required readonly>
									</div>
									</div>
								</div>
								
								<div class='form-group clearfix'>
									<label class='control-label col-sm-3' for='validation_desc'>Vehicle </label>
									<div class='col-sm-4 controls'>
										<input type="text" name="vehicle_type" id="days"
											data-rule-required='true' placeholder="Vehicle Type"
											class='form-control vehicle_elements' required>
									</div>
								</div>
								
								<div class='form-group clearfix'>
									<label class='control-label col-sm-3' for='validation_desc'>Driver </label>
									<div class='col-sm-4 controls'>
										<input type="text" name="vehicle_name" id=""
											data-rule-required='true' placeholder="Vehicle Name"
											class='form-control vehicle_elements' required>
									</div>
								</div>
							
								
							    <div class="add_driver_btn"><span class="btn btn-primary pull-right" style="margin-right: 294px;">Add Vehicle</span></div>

							</div>
							<div class='form-actions' style='margin-bottom: 0'>
								<div class='row'>
									<div class='col-sm-9 col-sm-offset-3'>
										<a class='btn btn-primary' id="vehicle_button">submit &
											continue</a>
									</div>
								</div>
							</div>
						</div>
					</div> -->
					<!-- Vehicle Ends -->

					<!-- Vehicle Manage Starts -->
					<div role="tabpanel" class="tab-pane" id="vehicle">
						<div class="col-md-12">
						 <form method="post" class='' id="map_vehicle_driver">
							<div class="duration_info_class clearfix" id="">
							<div class="vehicle_div">
								<div class="vehicle_details">
								  <div class='form-group'>
									<!-- <label class='control-label col-sm-3' for='validation_name'>Price Manage
									</label> -->
									
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
														<!-- <option value="">--Select Days--</option> -->
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
														data-rule-required='true' name="vehicle[0][vehicle]" id="" required>
														<option value="">--Select Option--</option>
													</select>
                                                  </td>
                                                   <td>
	                                                  <select class='form-control vehicle_elements driver_list_option'
														data-rule-required='true' name="vehicle[0][driver]" id="" required>
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
					</div>
					<!-- Vehicle Manage Ends -->

					<!-- Price Manage Starts -->
					<div role="tabpanel" class="tab-pane" id="price_manage">
						<div class="col-md-12">
							<div class="duration_info_class clearfix" id="duration_info">
							<div class="price_div">
								<div class="price_details">
								  <div class='form-group'>
									<!-- <label class='control-label col-sm-3' for='validation_name'>Price Manage
									</label> -->
									<table class="table table-bordered table-striped table-highlight">
                                              <thead>
                                                <tr>
                                                  <th>Days</th>
                                                  <th>Shift From</th>
                                                  <th>Shift To</th>
                                                  <th>Price</th>
                                                  <th>Action</th>
                                                </tr>
                                              </thead>
                                              <tbody class="price_management_tbody">
                                                <tr class="price_management_tr">
                                                  <td>
                                                    <select class='form-control price_elements'
														data-rule-required='true' name="price[0][shift_day]" id="" required>
														<option value="">--Select Days--</option>
														 <?php
															for($l = 0; $l < count ( $weekdays_name ); $l ++) {
																?>
									                        <option value='<?php echo $weekdays_name[$l]->id; ?>'> <?php echo $weekdays_name[$l]->day; ?>  </option>
									                        <?php
									                        }
									                        ?>
														<!-- <option value="0">Monday</option>
														<option value="1">Tuesday</option>
														<option value="2">Wednesday</option>
														<option value="3">Thursday</option>
														<option value="4">Friday</option>
														<option value="5">Saturday</option>
														<option value="6">Sunday</option> -->
													</select>
											      </td>
                                                  <td>
                                                  		<input type="text" name="price[0][shift_from]" id="price_shift_from_1"
														data-rule-required='true' placeholder="Price Shift From"
														class='form-control price_elements' required readonly>
                                                  </td>
                                                  <td>
	                                                  <input type="text" name="price[0][shift_to]" id="price_shift_to_1"
													data-rule-required='true' placeholder="Price Shift To"
													class='form-control price_elements' required readonly>
													</td>	
                                                  <td>
	                                                  <input type="text" name="price[0][price]" id="price_1"
													data-rule-required='true' placeholder="Price"
													class='form-control price_elements' required>
                                                  </td>

                                                  <td>
	                                                  <button type="button" class="btn btn-default btn-sm remove_price" style="margin-top: 8px;color: red;margin-bottom: 12px;">
												          <span class="glyphicon glyphicon-remove"></span> 
												    </button>
                                                  </td>
                                                </tr>
                                               
                                              </tbody>
                                            </table>
										
									</div>
									</div>
									</div>

									<div class="add_price_btn" id="add_vehicle_btn"><span class="btn btn-primary pull-right" style="margin-right: 48px;margin-top: 15px;">Add Price</span></div>
								
								
							</div>
							<div class='form-actions' style='margin-bottom: 0'>
								<div class='row'>
									<div class='col-sm-9 col-sm-offset-3'>
										<a class='btn btn-primary' id="price_button">submit &
											continue</a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- Price Manage Ends -->

					<!-- Itenary Starts -->
					<!-- <div role="tabpanel" class="tab-pane" id="itenary">
						<div class="col-md-12">
							<div class="duration_info_class clearfix" id="duration_info">
								<div class='form-group clearfix'>
									<label class='control-label col-sm-3' for='validation_desc'>Itinerary
										Description </label>
									<div class='col-sm-4 controls'>
										<textarea name="desc[]" class="form-control itenary_elements"
											data-rule-required="true" cols="70" rows="3"
											placeholder="Description" required></textarea>
									</div>
								</div>
								<div class='form-group clearfix'>
									<label class='control-label col-sm-3' for='validation_company'>Itinerary
										Image</label>
									<div class='col-sm-3 controls'>
										<input type="file" title='Image to add'
											class='itenary_elements' data-rule-required='true' id='image'
											name='image[]' required> <span id="pacmimg"
											style="color: #F00; display: none">Please Upload Itinerary
											Image</span>
									</div>
								</div>
								<div class='form-group clearfix'>
									<label class='control-label col-sm-3' for='validation_name'>Days
									</label>
									<div class='col-sm-4 controls'>
										<input type="text" name="days[]" id="days"
											data-rule-required='true' placeholder="Days"
											class='form-control itenary_elements' required>
									</div>
								</div>
								<div class='form-group'>
									<label class='control-label col-sm-3' for='validation_name'>Place
									</label>
									<div class='col-sm-4 controls'>
										<input type="text" name="place[]" id="Place"
											data-rule-required='true' placeholder="Place"
											class='form-control itenary_elements' required>
									</div>
								</div>
							</div>
							<div class='form-actions' style='margin-bottom: 0'>
								<div class='row'>
									<div class='col-sm-9 col-sm-offset-3'>
										<a class='btn btn-primary' id="itenary_button">submit &
											continue</a>
									</div>
								</div>
							</div>
						</div>
					</div> -->
					<!-- Itenary Ends -->

					<!-- Photo Gallery Starts -->
					<!-- <div role="tabpanel" class="tab-pane" id="gallery">
						<div class="col-md-12">
							<div class='form-group clearfix'>
								<label class='control-label col-sm-3' for='validation_company'>Add
									Images</label>
								<div class='col-sm-3 controls'>
									<input type="file" title='upload Photos'
										class='gallery_elements' data-rule-required='true'
										value="upload photo" id='traveller' name='traveller[]'
										multiple required> <span id="travel"
										style="color: #F00; display: none"> Upload Image</span>
								</div>
							</div>
							<div class='form-actions' style='margin-bottom: 0'>
								<div class='row'>
									<div class='col-sm-9 col-sm-offset-3'>
										<a class='btn btn-primary' id="gallery_button">submit &
											continue</a>
									</div>
								</div>
							</div>
						</div>
					</div> -->
					<!-- Photo Gallery Ends -->

					<!-- Rate card Starts -->
					<div role="tabpanel" class="tab-pane" id="rate_card">
						<div class="col-md-12">
							<div class='form-group clearfix'>
								<label class='control-label col-sm-3' for='validation_includes'>Price
									Includes </label>
								<div class='col-sm-4 controls'>
									<!-- <input type="text" name="includes" id="includes" data-rule-required='true' class='form-control'> -->
									<textarea name="includes"
										class="form-control rate_card_elements"
										data-rule-required="true" cols="70" rows="3"
										placeholder="Price Includes" required></textarea>
								</div>
							</div>
							<div class='form-group clearfix'>
								<label class='control-label col-sm-3' for='validation_excludes'>Price
									Excludes </label>
								<div class='col-sm-4 controls'>
									<textarea name="excludes"
										class="form-control rate_card_elements"
										data-rule-required="true" cols="70" rows="3"
										placeholder="Price Excludes" required></textarea>
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
										placeholder="Cancellation In Advance" required></textarea>
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
										placeholder="Cancellation Penalty" required></textarea>
								</div>
							</div>
							<div class='form-actions' style='margin-bottom: 0'>
								<div class='row'>
									<div class='col-sm-9 col-sm-offset-3'>
										<button class='btn btn-primary' type='submit'>submit</button>
									</div>
								</div>
							</div>

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


      $("#add_package_li").removeClass("active");
      	  $("#add_package").removeClass("active");
      	  $("#vehicle_li").addClass("active");
      	  $("#vehicle").addClass("active");
	  });

$('#vehicle_button').click(function(){
	var error_free = true;
    $( ".vehicle_elements" ).each(function() {
        if($( this ).val() == ''){
          error_free = false;
          $( this ).closest( ".form-group" ).addClass( "has-error" );
        }        
      });
      if(error_free)
      {
	  $("#vehicle_li").removeClass("active");
	  $("#vehicle").removeClass("active");
	 $("#price_li").addClass("active");
	  $("#price_manage").addClass("active");
      }
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
		    $('.days_option').html('');
		    $('.days_option').append('<option value="0">All Days</option');
		    // for(var i=1;i<=no_days;i++){
		    // 	$('.days_option').append('<option value="'+i+'">'+i+' Day</option');
		    // }

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

	     });

	    $(document).on('change','.days_option',function(){
	    	var day = $(this).val();
    		var date = $(this).find(':selected').attr('data-date');
    		var weekday =$(this).find(':selected').attr('data-weekday');
    		$(this).parent().find('.date').val(date);
    		$(this).parent().find('.weekday').val(weekday);
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
	    // var count = $('.driver_details').length;
	    // for(var i=0; i <=8; i++){
	    // 	$('#driver_shift_to_'+i).timepicker();
	    //     $('#driver_shift_from_'+i).timepicker();
	    
	    // }

	    $('#price_shift_to_1').timepicker();
	    $('#price_shift_from_1').timepicker();
	    $('#vehicle_shift_to_1').timepicker();
	    $('#vehicle_shift_from_1').timepicker();
    });
    $(document).ready(function(){
    	
    	$('.add_vehicle_btn').on('click',function(){
			var error_free = true;
		    $( ".vehicle_elements" ).each(function() {
		        if($( this ).val() == ''){
		          error_free = false;
		          $( this ).closest( ".form-group" ).addClass( "has-error" );
		        }        
		      });
		      if(error_free)
		      {
				$.ajax({
			        url: '<?php echo base_url(); ?>index.php/transfers/add_vehicle_driver',
			        type: 'post',
			        dataType: 'json',
			        data: $('form#map_vehicle_driver').serialize(),
			        success: function(data) {
			        	console.log(data);
			             add_vehicle_row();
			         }
			    });
			 

	     }
   	});


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

    	$('.add_price_btn').on('click',function(){
    		var count = $('.price_management_tr').length +1;
    		var price_div = '';
    		if(count<=6){
    		var length = 1;

			price_div +=`<tr class="price_management_tr">
                          <td>
                            <select class='form-control price_elements'
								data-rule-required='true' name="price[${length}][shift_day]" id="" required>
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
                          		<input type="text" name="price[${length}][shift_from]" id="price_shift_from_${count}"
								data-rule-required='true' placeholder="Price Shift From"
								class='form-control price_elements' required readonly>
                          </td>
                          <td>
                              <input type="text" name="price[${length}][shift_to]" id="price_shift_to_${count}"
							data-rule-required='true' placeholder="Price Shift To"
							class='form-control price_elements' required readonly>
							</td>	
                          <td>
                              <input type="text" name="price[${length}][price]" id="price_${count}"
							data-rule-required='true' placeholder="Price"
							class='form-control price_elements' required>
                          </td>

                          <td>
                              <button type="button" class="btn btn-default btn-sm remove_price" style="margin-top: 8px;color: red;margin-bottom: 12px;">
						          <span class="glyphicon glyphicon-remove"></span> 
						    </button>
                          </td>
                        </tr>`;

			length++;
			$('.price_management_tbody').append(price_div);
			$('#price_shift_to_'+count).timepicker();
	        $('#price_shift_from_'+count).timepicker();
		}else{
			alert('Max Reached');
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

$(document).ready(function(){
	$(document).on('click','.vehicle_shift_time_end',function(){
		var thisObj = $(this);
		var shift_time_end = $(this).val();
		var shift_time_start = $(this).parent().find('.vehicle_shift_time_start').val();
		var day = $(this).parent().find('.days_date').val();
		var date = $(this).parent().find('.days_date').data('date');
		var weekDay = $(this).parent().find('.days_date').data('weekday');
		// alert(day_date);
		 $.ajax({
            type:"POST",
            url: "<?php echo base_url(); ?>transfers/get_vehicle_list",
            // data:{country:country,country:country,country:country},
            success:function(response)
            {
              var data = $.parseJSON(response);
              $.each(data.result.vehicle,function(key,val){
              	thisObj.closest('.vehicle_management_tr').find('.vehicle_list_option').append('<option value="'+val.id+'">'+val.vehicle_name+'</option>');
              });
              $.each(data.result.driver,function(key,val){
              	thisObj.closest('.vehicle_management_tr').find('.driver_list_option').append('<option value="'+val.id+'">'+val.driver_name+'</option>');
              });
            }
          });
		

	});
});


// $('input#submitButton').click( function() {
  
// });
    
</script>