<?php 
$path = $GLOBALS['CI']->template->domain_upload_pckg_images();
// echo $path.$transfer_data->vehicle_image;
// debug($country);exit;
?>
<style type="text/css">
	.padfive{ padding: 0 5px !important; }
.days_lablel label{ margin-left: 5px; padding-left: 21px !important; font-weight: 500; }
.ui-timepicker-viewport li a{}
.col-md-1.padfive.days_lablel {width: 10.3%;}
.ui-menu .ui-menu-item{padding: 3px 0 3px 0 !important;}
.ui-menu .ui-menu-item a{padding: 4px .4em !important; border-radius: 0 !important;}
.ui-menu .ui-menu-item:last-child a {border-bottom: none !important;}
.ui-widget-content a{ color: #333 !important; }
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
<div id="package_types" class="bodyContent col-md-12">
	<div class="panel panel-default">
		<!-- PANEL WRAP START -->
		<div class="panel-heading">
			<!-- PANEL HEAD START -->
			<div class="panel-title">
				<ul class="nav nav-tabs nav-justified" role="tablist" id="myTab">
					<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->
					<li role="presentation" class="active"><a href="#fromList"
						aria-controls="home" role="tab" data-toggle="tab">
							<h1> Transfers Driver</h1>

					</a></li>
					<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE END -->
				</ul>
			</div>
		</div>
		<!-- PANEL HEAD START -->
		<div class="panel-body">
			<!-- PANEL BODY START -->
			<div class="tab-content">
				<div role="tabpanel" class="tab-pane active" id="fromList">
					<div class="col-md-12">
						<div class='row'>
							<div class='row'>
								<div class='col-sm-12'>
									<div class='' style='margin-bottom: 0;'>
										<div class='box-content'>
											<form class='form form-horizontal validate-form'
												style='margin-bottom: 0;'
												action="<?php echo base_url(); ?>index.php/transfers/add_transfer_driver/<?php echo $data->id;?>"
												method="post" enctype="multipart/form-data">


												<!-- <div class='box-header blue-background'>
													<div class=''>
														<h4>Vehicle Info</h4>
													</div>
												</div>-->
												
												<div class='form-group'>
													<label class='control-label col-sm-3'
														for='validation_includes'>Driver Name </label>
													<div class='col-sm-4 controls'>
														<input type="text" name="driver_name" id="driver_name"
															data-rule-number='true' data-rule-required='true'
															class='form-control'
															value="<?php echo $data->driver_name;?>" required>
													</div>
												</div>

												<div class='form-group'>
													<label class='control-label col-sm-3'
														for='validation_includes'>Contact Number</label>
													<div class='col-sm-4 controls'>
														<input type="text" name="contact_number" id="contact_number"
															data-rule-number='true' data-rule-required='true'
															class='form-control'
															value="<?php echo $data->contact_number;?>" onchange="phonenumber(this);" required>
															<span class="error" id="number_validation" style="color:#F00; display:none; "></span>
													</div>
												</div>

												<div class='form-group'>
													<label class='control-label col-sm-3'
														for='validation_includes'>Email </label>
													<div class='col-sm-4 controls'>
														<input type="email" name="email" id="email"
															data-rule-number='true' data-rule-required='true'
															class='form-control'
															value="<?php echo $data->email;?>" required>
													</div>
												</div>


												<div class='form-group'>
													<label class='control-label col-sm-3'
														for='validation_includes'>Country </label>
													<div class='col-sm-4 controls'>
			                                            <?php 
			                                            	// $equipment_data = json_decode($data->equipment,true);
			                                            	// debug($equipment_data);exit;
			                                            ?>
			                                            <select class='form-control' required name='country' id="">
			                                                <option>--Select Country--</option>
			                                                <?php foreach ($country as $ekey => $evalue) { 
			                                                	// debug($Equipment);exit;
			                                                	// foreach ($equipment_data as $key => $eq_val) {
			                                                	// 	// debug($eq_val);exit;
			                                                		if($evalue->country_list == $data->country){
			                                                		   $select = 'selected="selected"';
				                                                	}else{
				                                                		$select = '';
				                                                	}
			                                                	// }
			                                                	// debug($select);exit;
			                                                	?>
			                                                <option value='<?=$evalue->country_list?>' <?=$select?>><?=$evalue->country_name?></option>
			                                                <?php } ?>
			                                            </select> 
			                                             <span class="error" style="color:#F00; display:none; ">This field is required</span>
                                                    </div>
												</div>

												<div class='form-group'>
													<label class='control-label col-sm-3'
														for='validation_includes'>City </label>
													<div class='col-sm-4 controls'>
														<input type="text" name="city" id="city"
															data-rule-number='true' data-rule-required='true'
															class='form-control'
															value="<?php echo $data->city;?>" required>
													</div>
												</div>

												<div class='form-group'>
													<label class='control-label col-sm-3'
														for='validation_includes'>Location </label>
													<div class='col-sm-4 controls'>
														<input type="text" name="location" id="location"
															data-rule-number='true' data-rule-required='true'
															class='form-control'
															value="<?php echo $data->location;?>" required>
													</div>
												</div>

												<div class='form-group'>
													<label class='control-label col-sm-3'
														for='validation_includes'>Address </label>
													<div class='col-sm-4 controls'>
														<input type="text" name="address" id="address"
															data-rule-number='true' data-rule-required='true'
															class='form-control'
															value="<?php echo $data->address;?>">
													</div>
												</div>

												<?php 

												// debug($driver_info['driver_shift_days']);exit;

													$driver_shift_days = json_decode($data->driver_shift_days,true);

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
													<label class='control-label col-sm-3' for='validation_name'> Shift Days
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
												<?php 
												 $driver_shift_from = date("h:i A", mktime(0,$data->driver_shift_from));
                                  				 
                                  				 $driver_shift_to = date("h:i A", mktime(0,$data->driver_shift_to));
												?>

												<div class='form-group'>
													<label class='control-label col-sm-3' for='validation_name'> Shift Time
													</label>
													<div>
													<div class='col-sm-2 controls'>
														<input type="text" name="driver_shift_from" id="driver_shift_from" value="<?php echo $driver_shift_from;?>"
															data-rule-required='true' placeholder="Driver Shift From"
															class='form-control vehicle_elements' required readonly>
													</div>
													<span style="margin-left: -166px; font-weight: bold;"> to </span>
													<div class='col-sm-2 controls'>
														<input type="text" name="driver_shift_to" id="driver_shift_to"
															data-rule-required='true' value="<?php echo $driver_shift_to;?>" placeholder="Driver Shift To"
															class='form-control vehicle_elements' required readonly>
													</div>
													</div>
												</div>

												<div class='form-group'>
													<label class='control-label col-sm-3'
														for='validation_includes'>Driver Photo </label>
													<div class='col-sm-3 controls'>
														<input type="file" title='Image to add to add' class=''
															id='driver_photo' name='driver_photo' accept="image/jpg,image/png,image/jpeg,image/gif" onchange="showMyImage(this,'P')">
														<input type="hidden"
															name='driver_photo' value="<?php echo $data->driver_photo; ?>">
															<?php 
															if($data->driver_photo!=''){
																$style = 'style="width:50%; margin-top:10px;height: auto;"';
															}else{
																$style = 'style="width:50%; margin-top:10px;height: auto; display: none;"';
															}?>
														<img id="thumbnil" <?=$style?>
															src="<?php echo $path.$data->driver_photo; ?>" name="photo">
													</div>
												</div>

												<div class='form-group'>
													<label class='control-label col-sm-3'
														for=''>Driver Id Proof </label>
													<div class='col-sm-3 controls'>
														<input type="file" title='Image to add to add' class=''
															id='driver_id_proof' name='driver_id_proof' onchange="showMyImage(this,'I')">
														<input type="hidden"
															name='driver_id_proof' value="<?php echo $data->driver_id_proof; ?>">
															<?php 
															if($data->driver_id_proof!=''){
																$style_id = 'style="width:50%; margin-top:10px;height: auto;"';
															}else{
																$style_id = 'style="width:50%; margin-top:10px;height: auto; display: none;"';
															}?>
														<img id="thumbnil_id" <?=$style_id?>
															src="<?php echo $path.$data->driver_id_proof; ?>" name="photo">
													</div>
												</div>



												<div class='box-header blue-background'>
													<div class=''>
														<h4>License Details</h4>
													</div>
												</div>

												<div class='form-group'>
													<label class='control-label col-sm-3'
														for='validation_includes'>License Number </label>
													<div class='col-sm-4 controls'>
														<input type="text" name="license_number" id=""
															data-rule-number='true' data-rule-required='true'
															class='form-control'
															value="<?php echo $data->license_number;?>" required>
													</div>
												</div>

												<div class='form-group'>
													<label class='control-label col-sm-3'
														for='validation_includes'>Validity </label>
													<div class='col-sm-4 controls'>
														<input type="text" name="license_validity" id="insurance_start_date"
															data-rule-number='true' data-rule-required='true'
															class='form-control'
															value="<?php echo $data->license_validity;?>" required>
													</div>
												</div>

												<div class='form-group'>
													<label class='control-label col-sm-3'
														for='validation_includes'>License Photo </label>
													<div class='col-sm-3 controls'>
														<input type="file" title='Image to add to add' class=''
															id='license_image' name='license_image' onchange="showMyImage(this,'L')">
														<input type="hidden"
															name='license_image' value="<?php echo $data->license_image; ?>">
															<?php 
															if($data->license_image!=''){
																$style_id = 'style="width:50%; margin-top:10px;height: auto;"';
															}else{
																$style_id = 'style="width:50%; margin-top:10px;height: auto; display: none;"';
															}?>
														<img id="thumbnil_license" <?=$style_id?>
															src="<?php echo $path.$data->license_image; ?>" name="photo">
													</div>
												</div>

												<div class='form-group'>
													<label class='control-label col-sm-3'
														for='validation_includes'>Display Cart Number </label>
													<div class='col-sm-4 controls'>
														<input type="text" name="display_cart_number" id=""
															data-rule-number='true' data-rule-required='true'
															class='form-control'
															value="<?php echo $data->display_cart_number;?>" required>
													</div>
												</div>

												<div class='form-group'>
													<label class='control-label col-sm-3'
														for='validation_includes'>Validity </label>
													<div class='col-sm-4 controls'>
														<input type="text" name="display_cart_validity" id="insurance_expire_date"
															data-rule-number='true' data-rule-required='true'
															class='form-control'
															value="<?php echo $data->display_cart_validity;?>" required>
													</div>
												</div>

												<div class='form-group'>
													<label class='control-label col-sm-3'
														for=''>Insurance Number</label>
													<div class='col-sm-4 controls'>
														<input type="text" name="insurance_number" id="insurance"
															data-rule-number='true' data-rule-required='true'
															class='form-control'
															value="<?php echo $data->insurance_number;?>" required>
													</div>
												</div>

												<div class='form-group'>
													<label class='control-label col-sm-3'
														for=''>Insurance File </label>
													<div class='col-sm-3 controls'>
														<input type="file" title='Image to add to add' class=''
															id='insurance_file' name='insurance_file' onchange="showMyImage(this,'F')">
														<input type="hidden"
															name='insurance_file' value="<?php echo $data->insurance_file; ?>">
															<?php 
															if($data->insurance_file!=''){
																$style_id = 'style="width:50%; margin-top:10px;height: auto;"';
															}else{
																$style_id = 'style="width:50%; margin-top:10px;height: auto; display: none;"';
															}?>
														<img id="thumbnil_insurance" <?=$style_id?>
															src="<?php echo $path.$data->insurance_file; ?>"
															width="100" name="photo">
													</div>
												</div>
												<!-- <div class='box-header blue-background'>
													<div class=''>
														<h4>Operating Time</h4>
													</div>
												</div> -->




												
									</div>

								</div>


								<div class='form-actions' style='margin-bottom: 0'>
									<div class='row'>
										<div class='col-sm-9 col-sm-offset-3 bbh'>
											<a href="<?php echo base_url(); ?>transfers/view_driver_list">
												<button class='btn btn-primary' type='button'>
													<i class='icon-reply'></i> Go Back
												</button>
											</a>
											<button class='btn btn-primary' type='submit'>
												<i class='icon-save'></i> Submit
											</button>
										</div>
									</div>
								</div>

							</div>
						</div>
						</form>

					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- PANEL BODY END -->
</div>
<!-- PANEL WRAP END -->
</div>
<!-- jQuery timepicker library -->
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.3/jquery.timepicker.min.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.3/jquery.timepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.full.js"></script>
 <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
  <?php
  /* Js_Loader::$js [] = array (
    'src' => $GLOBALS ['CI']->template->template_js_dir ( 'page_resource/select2.full.js' ),
    'defer' => 'defer' 
);
*/
?>  
<script type="text/javascript">
	 $(document).ready(function () {
        $('#start_date').datepicker({
        	minDate:0,
        	numberOfMonths: 2,
        	changeMonth: !0,
        	dateFormat: "yy-mm-dd"
        });

         $('#expire_date').datepicker({
        	minDate:0,
        	numberOfMonths: 2,
        	changeMonth: !0,
        	dateFormat: "yy-mm-dd"
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
	    $('#driver_shift_to').timepicker({
	        timeFormat: 'h:mm p',
            // startTime: '8:00 am',
            dynamic: true,
            dropdown: true,
            scrollbar: true
	    });
	    $('#driver_shift_from').timepicker({
	        timeFormat: 'h:mm p',
            // startTime: '8:00 am',
            dynamic: true,
            dropdown: true,
            scrollbar: true
	    });
	      var count = $('.driver_details').length;
	     for(var i=0; i <=count; i++){
	    	$('#driver_shift_to_'+i).timepicker();
	        $('#driver_shift_from_'+i).timepicker();
	    
	    }
	    // $('#driver_shift_from').timepicker();
	    // $('#driver_shift_to').timepicker();

	      $('.add_driver_btn').on('click',function(){
	    	var count = $('.driver_details').length;
    		var driver_div = '';
    		// alert(count);
    		
    		if(count<3){
    		var length = 1;
    		driver_div +=`<div class="driver_details">
								<h4>Driver ${count}</h4>
								<div class='form-group'>
									<label class='control-label col-sm-3' for='validation_name'>Driver Name
									</label>
									<div class='col-sm-4 controls'>
										<input type="text" name="driver[${count}][driver_name]" id="driver_name"
											data-rule-required='true' placeholder="Driver Name"
											class='form-control vehicle_elements' required>
									</div>
								</div>
								<div class='form-group'>
									<label class='control-label col-sm-3' for='validation_name'>Driver Shift Days
									</label>
									<div class="col-md-8 col-sm-8 col-xs-12">
                                       <input class='form-control checkbox' id='monday_${count}' name='driver[${count}][driver_shift_days][]' value="1" type='checkbox'>&nbsp;
                                       <label for="monday_${count}">Mon</label>
                                       &nbsp;&nbsp;
                                       <input class='form-control checkbox' id='tuesday_${count}' name='driver[${count}][driver_shift_days][]' value="2" type='checkbox'>&nbsp;
                                       <label for="tuesday_${count}">Tue</label>
                                       &nbsp;&nbsp;
                                       <input class='form-control checkbox' id='wednesday_${count}' name='driver[${count}][driver_shift_days][]' value="3" type='checkbox'>&nbsp;
                                       <label for="wednesday_${count}">Wed</label>
                                        &nbsp;&nbsp;
                                       <input class='form-control checkbox' id='thursday_${count}' name='driver[${count}][driver_shift_days][]' value="4" type='checkbox'>&nbsp;
                                       <label for="thursday_${count}">Thu</label>
                                        &nbsp;&nbsp;
                                       <input class='form-control checkbox' id='friday_${count}' name='driver[${count}][driver_shift_days][]' value="5" type='checkbox'>&nbsp;
                                       <label for="friday_${count}">Fri</label>
                                        &nbsp;&nbsp;
                                       <input class='form-control checkbox' id='saturday_${count}' name='driver[${count}][driver_shift_days][]' value="6" type='checkbox'>&nbsp;
                                       <label for="saturday_${count}">Sat</label>
                                        &nbsp;&nbsp;
                                       <input class='form-control checkbox' id='sunday_${count}' name='driver[${count}][driver_shift_days][]' value="7" type='checkbox'>&nbsp;
                                       <label for="sunday_${count}">Sun</label>
                                         
                                    </div>
								</div> 
								<div class='form-group'>
									<label class='control-label col-sm-3' for='validation_name'>Driver Shift Time
									</label>
									<div>
									<div class='col-sm-2 controls'>
										<input type="text" name="driver[${count}][driver_shift_from]" id="driver_shift_from_${count}"
											data-rule-required='true' placeholder="Driver Shift From"
											class='form-control vehicle_elements' required readonly>
									</div>
									<span style="margin-left: -166px; font-weight: bold;"> to </span>
									<div class='col-sm-2 controls'>
										<input type="text" name="driver[${count}][driver_shift_to]" id="driver_shift_to_${count}"
											data-rule-required='true' placeholder="Driver Shift To"
											class='form-control vehicle_elements' required readonly>
									</div>
									</div>
								</div>
								<div class='form-group'>
									<label class='control-label col-sm-3' for='validation_name'>Driver License
									</label>
									<div class='col-sm-4 controls'>
										<input type="file" title='File to add'
											class='vehicle_elements' data-rule-required='true' id='driver_license_${count}'
											name="driver_license_${count}" required> <span id="pacmimg"
											style="color: #F00; display: none">Please Upload License
											</span>
									</div>
								</div> 
								<button type="button" class="btn btn-default btn-sm remove_driver" style="margin-left: 449px;
                                       color: red;">
						          <span class="glyphicon glyphicon-remove"></span> Remove 
						        </button>

							</div>`;
			length++;

			$('.driver_div').append(driver_div);
			$('#driver_shift_to_'+count).timepicker();
	        $('#driver_shift_from_'+count).timepicker();
		}else{
			alert('Max 3 driver');
		}
    	});
	  
    	$(document).on('click','.remove_driver',function(){
    		var count = $('.driver_details').length;
    		if(count == 1){
    			alert('One driver details required');
    		}else{
    		 $(this).closest('.driver_details').remove();
    		}
    	});



    });

  $(document).ready(function () {
        $('#insurance_start_date').datepicker({
        	minDate:0,
        	// numberOfMonths: 2,
        	changeMonth: !0,
        	dateFormat: "yy-mm-dd"
        });

         $('#insurance_expire_date').datepicker({
        	minDate:0,
        	// numberOfMonths: 2,
        	changeMonth: !0,
        	dateFormat: "yy-mm-dd"
        });

        $('#driver_shift_to').timepicker();
	    $('#driver_shift_from').timepicker();
    });

  (function($) {
  $.fn.inputFilter = function(inputFilter) {
    return this.on("input keydown keyup mousedown mouseup select contextmenu drop", function() {
      if (inputFilter(this.value)) {
        this.oldValue = this.value;
        this.oldSelectionStart = this.selectionStart;
        this.oldSelectionEnd = this.selectionEnd;
      } else if (this.hasOwnProperty("oldValue")) {
        this.value = this.oldValue;
        this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
      } else {
        this.value = "";
      }
    });
  };
}(jQuery));


// Install input filters.
$("#contact_number").inputFilter(function(value) {
  return /^-?\d*$/.test(value); });
function phonenumber(inputtxt)
{
  var phoneno = /^\d{10}$/;
  if(inputtxt.value.match(phoneno))
  {
  	  $('#number_validation').hide();
      return true;
  }
  else
  {
     var msg = "Phone number should be 10 digit";
     $('#number_validation').text(msg);
     $('#number_validation').show();
     return false;
  }
 }
$("#driver_name").inputFilter(function(value) {
  return /^[A-Za-z\s]*$/i.test(value); });
function showMyImage(fileInput, id) {
	  if(id=='P'){
      $('#thumbnil').show();
	  }else if(id=='I'){
		  $('#thumbnil_id').show();
	  }else if(id=='L'){
		  $('#thumbnil_license').show();
	  }else if(id=='F'){
		  $('#thumbnil_insurance').show();
	  }
      var files = fileInput.files;
      for (var i = 0; i < files.length; i++) { 
      var file = files[i];
      // var imageType = /image.*/; 
      // if (!file.type.match(imageType)) {
      // continue;
      // } 
      if(id=='P'){
      	var img=document.getElementById("thumbnil"); 
	  }else if(id=='I'){
      	var img=document.getElementById("thumbnil_id"); 
	  }else if(id=='L'){
		var img=document.getElementById("thumbnil_license"); 
	  }else if(id=='F'){
		var img=document.getElementById("thumbnil_insurance");
	  }
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
</script>