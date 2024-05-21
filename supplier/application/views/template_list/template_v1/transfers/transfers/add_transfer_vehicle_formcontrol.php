<?php 
$path = $GLOBALS['CI']->template->domain_upload_pckg_images();
// echo $path.$transfer_data->vehicle_image;
// debug($transfer_data);exit;
?>
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
							<h1>Add Transfers Vehicle</h1>
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
												action="<?php echo base_url(); ?>index.php/transfers/add_transfer_vehicle_details/<?php echo $transfer_data->id;?>"
												method="post" enctype="multipart/form-data">


												<!-- <div class='box-header blue-background'>
													<div class=''>
														<h4>Vehicle Info</h4>
													</div>
												</div>-->
												
												<div class='form-group'>
													<label class='control-label col-sm-3'
														for='validation_includes'>Vehicle Type </label>
													<div class='col-sm-4 controls'>
														<input type="text" name="vehicle_type" id="vehicle_type"
															data-rule-number='true' data-rule-required='true'
															class='form-control'
															value="<?php echo $transfer_data->vehicle_type;?>">
													</div>
												</div>

												<div class='form-group'>
													<label class='control-label col-sm-3'
														for='validation_includes'>Vehicle Name </label>
													<div class='col-sm-4 controls'>
														<input type="text" name="vehicle_name" id="vehicle_name"
															data-rule-number='true' data-rule-required='true'
															class='form-control'
															value="<?php echo $transfer_data->vehicle_name;?>">
													</div>
												</div>

												<div class='form-group'>
													<label class='control-label col-sm-3'
														for='validation_includes'>Vehicle Number </label>
													<div class='col-sm-4 controls'>
														<input type="text" name="vehicle_number" id="vehicle_number"
															data-rule-number='true' data-rule-required='true'
															class='form-control'
															value="<?php echo $transfer_data->vehicle_number;?>">
													</div>
												</div>

												<div class='form-group'>
													<label class='control-label col-sm-3'
														for='validation_includes'>Vehicle Image </label>
													<div class='col-sm-3 controls'>
														<input type="file" title='Image to add to add' class=''
															id='vehicle_image' name='vehicle_image'>
														<input type="hidden"
															name='vehicle_image' value="<?php echo $transfer_data->vehicle_image; ?>">
														<img id="thumbnil"
															src="<?php echo $path.$transfer_data->vehicle_image; ?>"
															width="100" name="photo">
													</div>
												</div>

												<div class='form-group'>
													<label class='control-label col-sm-3'
														for='validation_includes'>Equipment </label>
													<div class='col-sm-4 controls'>
			                                            <?php $Equipment = $this->db->get_where('add_equipment', array('status' => 1))->result_array();  
			                                            	$equipment_data = json_decode($transfer_data->equipment,true);
			                                            	// debug($equipment_data);exit;
			                                            ?>
			                                            <select class='select2_multiple form-control' required name='equipment[]' id="equipment" multiple>
			                                                
			                                                <?php foreach ($Equipment as $ekey => $evalue) { 
			                                                	// debug($Equipment);exit;
			                                                	foreach ($equipment_data as $key => $eq_val) {
			                                                		// debug($eq_val);exit;
			                                                		if($evalue['id'] == $eq_val){
			                                                		   $select = 'selected="selected"';
				                                                	}else{
				                                                		$select = '';
				                                                	}
			                                                	}
			                                                	// debug($select);exit;
			                                                	?>
			                                                <option value='<?=$evalue['id']?>' <?=$select?>><?=$evalue['euipment_name']?></option>
			                                                <?php } ?>
			                                            </select> 
			                                             <span class="error" style="color:#F00; display:none; ">This field is required</span>
                                                    </div>
												</div>

												<div class='form-group'>
													<label class='control-label col-sm-3'
														for='validation_includes'>Max Passenger </label>
													<div class='col-sm-4 controls'>
														<input type="text" name="max_passenger" id="max_passenger"
															data-rule-number='true' data-rule-required='true'
															class='form-control'
															value="<?php echo $transfer_data->max_passenger;?>">
													</div>
												</div>

												<div class='form-group'>
													<label class='control-label col-sm-3'
														for='validation_includes'>Max Luggage </label>
													<div class='col-sm-4 controls'>
														<input type="text" name="max_luggage" id="max_luggage"
															data-rule-number='true' data-rule-required='true'
															class='form-control'
															value="<?php echo $transfer_data->max_luggage;?>">
													</div>
												</div>

												<div class='form-group'>
													<label class='control-label col-sm-3'
														for='validation_includes'>Registration Number </label>
													<div class='col-sm-4 controls'>
														<input type="text" name="max_luggage" id=""
															data-rule-number='true' data-rule-required='true'
															class='form-control'
															value="<?php echo $transfer_data->max_luggage;?>">
													</div>
												</div>

												<div class='form-group'>
													<label class='control-label col-sm-3'
														for='validation_includes'>Engine Number </label>
													<div class='col-sm-4 controls'>
														<input type="text" name="max_luggage" id=""
															data-rule-number='true' data-rule-required='true'
															class='form-control'
															value="<?php echo $transfer_data->max_luggage;?>">
													</div>
												</div>

												<div class='form-group'>
													<label class='control-label col-sm-3'
														for='validation_includes'>Color </label>
													<div class='col-sm-4 controls'>
														<input type="text" name="max_luggage" id=""
															data-rule-number='true' data-rule-required='true'
															class='form-control'
															value="<?php echo $transfer_data->max_luggage;?>">
													</div>
												</div>

												<div class='form-group'>
													<label class='control-label col-sm-3'
														for='validation_includes'>Mileage </label>
													<div class='col-sm-4 controls'>
														<input type="text" name="max_luggage" id=""
															data-rule-number='true' data-rule-required='true'
															class='form-control'
															value="<?php echo $transfer_data->max_luggage;?>">
													</div>
												</div>



												<div class='box-header blue-background'>
													<div class=''>
														<h4>Insurance Details</h4>
													</div>
												</div>

												<div class='form-group'>
													<label class='control-label col-sm-3'
														for='validation_includes'>Insurance Company </label>
													<div class='col-sm-4 controls'>
														<input type="text" name="max_luggage" id=""
															data-rule-number='true' data-rule-required='true'
															class='form-control'
															value="<?php echo $transfer_data->max_luggage;?>">
													</div>
												</div>

												<div class='form-group'>
													<label class='control-label col-sm-3'
														for='validation_includes'>Insurance Begin Date </label>
													<div class='col-sm-4 controls'>
														<input type="text" name="max_luggage" id=""
															data-rule-number='true' data-rule-required='true'
															class='form-control'
															value="<?php echo $transfer_data->max_luggage;?>">
													</div>
												</div>

												<div class='form-group'>
													<label class='control-label col-sm-3'
														for='validation_includes'>Insurance End Date </label>
													<div class='col-sm-4 controls'>
														<input type="text" name="max_luggage" id=""
															data-rule-number='true' data-rule-required='true'
															class='form-control'
															value="<?php echo $transfer_data->max_luggage;?>">
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
										<div class='col-sm-9 col-sm-offset-3'>
											<a href="<?php echo base_url(); ?>transfers/view_transfer_list">
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

 <link rel="stylesheet" type="text/css" href="<?=$GLOBALS ['CI']->template->template_css_dir ( 'page_resource/select2.min.css')?>">
  <?php
   Js_Loader::$js [] = array (
    'src' => $GLOBALS ['CI']->template->template_js_dir ( 'page_resource/select2.full.js' ),
    'defer' => 'defer' 
);?>  
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
</script>