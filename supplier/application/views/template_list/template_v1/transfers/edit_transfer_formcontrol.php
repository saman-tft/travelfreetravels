<?php 
$path = $GLOBALS['CI']->template->domain_upload_pckg_images();
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
							<h1>Edit Transfers</h1>
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
												action="<?php echo base_url(); ?>index.php/transfers/edit_transfer_details/<?php echo $transfer_data->id;?>"
												method="post" enctype="multipart/form-data">

												<div class='form-group'>
													<label class='control-label col-sm-3'
														for='validation_current'>Transfers Name </label>
													<div class='col-sm-4 controls'>

														<div class="controls">
															<input type="text" name="transfer_name" id="name"
																value="<?php echo $transfer_data->transfer_name;?>"
																data-rule-required='true' class='form-control'>
														</div>

													</div>
												</div>
												<div class='form-group'>
													<label class='control-label col-sm-3'
														for='validation_current'>Source </label>
													<div class='col-sm-4 controls'>

														<div class="controls">
															<input type="text" name="source" id=""
																value="<?php echo $transfer_data->source;?>"
																data-rule-required='true' class='form-control'>
														</div>

													</div>
												</div>
												<div class='form-group'>
													<label class='control-label col-sm-3'
														for='validation_current'>Destination </label>
													<div class='col-sm-4 controls'>

														<div class="controls">
															<input type="text" name="destination" id="destination"
																value="<?php echo $transfer_data->destination;?>"
																data-rule-required='true' class='form-control'>
														</div>

													</div>
												</div>

												<div class='form-group ' id="select_date">
											       <label class='control-label col-sm-3' for='validation_current'>Start Date <!-- <span style = "color:red">*</span> -->
											        </label>
											        <div class='col-sm-4 controls'>
											        <input type="text" name="start_date" id="start_date" data-rule-required='true'
																class='form-control add_pckg_elements' required value="<?php echo $transfer_data->start_date;?>" placeholder="Choose Date" data-rule-required='true'  readonly> 
											        </div>
											    </div>

												<div class='form-group ' id="select_date">
											        <label class='control-label col-sm-3' for='validation_current'>Expiry Date<!--  <span style = "color:red">*</span> -->
											        </label>
											        <div class='col-sm-4 controls'>
											        <input type="text" name="expiry_date" id="expire_date" data-rule-required='true'
																class='form-control add_pckg_elements' required value="<?php echo $transfer_data->expiry_date;?>" placeholder="Choose Date" data-rule-required='true'  readonly> 
											        </div>
											    </div>
												

												<div class='form-group'>
													<label class='control-label col-sm-3'
														for='validation_company'>Transfers Main Image</label>
													<div class='col-sm-3 controls'>
														<!--  <input type="hidden" value="<?php echo $transfer_data->image; ?>" name="photo"> -->
														<input type="file" title='Image to add to add' class=''
															id='transfer_image' name='transfer_image'> <input type="hidden"
															name='transfer_image' value="<?php echo $transfer_data->image; ?>">
														<img
															src="<?php echo $path.$transfer_data->image; ?>"
															width="100" name="photo">
													</div>


												</div>

												<div class='form-group'>
													<label class='control-label col-sm-3' for='validation_name'>Description</label>
													<div class='col-sm-3 controls'>
														<textarea name="description" class="form-control"
															data-rule-required="true" cols="70" rows="3"
															placeholder="Description" value=""><?php echo $transfer_data->description;?></textarea>
														<!--   <span id="dorigin_error" style="color:#F00;  display:none;"></span> -->
													</div>
												</div>

												<div class='form-group'>

													<label class='control-label col-sm-3'
														for='validation_rating'>Rating </label>
													<div class="col-sm-4 controls">
														<select class='form-control' data-rule-required='true'
															name='rating' id="rating" value="">

															<option><?php echo $transfer_data->rating;?></option>
															<option value="0">0</option>
															<option value="1">1</option>
															<option value="2">2</option>
															<option value="3">3</option>
															<option value="4">4</option>
															<option value="5">5</option>

														</select>
													</div>
												</div>

												<!-- <div class='box-header blue-background'>
													<div class=''>
														<h4>Vehicle Info</h4>
													</div>
												</div>
												
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
															id='vehicle_image' name='vehicle_image'> <input type="hidden"
															name='vehicle_image' value="<?php echo $transfer_data->vehicle_image; ?>">
														<img
															src="<?php echo $path.$transfer_data->vehicle_image; ?>"
															width="100" name="photo">
													</div>
												</div>

												<div class='form-group'>
													<label class='control-label col-sm-3'
														for='validation_includes'>Equipment </label>
													<div class='col-sm-4 controls'>
			                                            <?php $Equipment = $this->db->get_where('add_equipment', array('status' => 1))->result_array();  
			                                            	$equipment_data = $transfer_data->equipment;
			                                            ?>
			                                            <select class='select2_multiple form-control' required name='equipment[]' id="equipment" multiple>
			                                                
			                                                <?php foreach ($Equipment as $ekey => $evalue) { ?>
			                                                <option value='<?=$evalue['id']?>'><?=$evalue['euipment_name']?></option>
			                                                <?php } ?>
			                                            </select> 
			                                             <span class="error" style="color:#F00; display:none; ">This field is required</span>
                                                    </div>
												</div> -->

												<!-- <div class='form-group'>
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

												<div class='box-header blue-background'>
													<div class=''>
														<h4>Driver Info</h4>
													</div>
												</div>


												<div class='form-group'>
													<label class='control-label col-sm-3'
														for='validation_includes'>Driver Name </label>
													<div class='col-sm-4 controls'>
														<input type="text" name="driver_name" id="driver_name"
															data-rule-number='true' data-rule-required='true'
															class='form-control'
															value="<?php echo $transfer_data->driver_name;?>">
													</div>
												</div>

												<div class='form-group'>
													<label class='control-label col-sm-3'
														for='validation_includes'>Driver Shift Time </label>
													<div>
														<div class='col-sm-2 controls'>
															<input type="text" name="driver_shift_from" id="driver_shift_from"
																data-rule-required='true' placeholder="Driver Shift From" value="<?=$transfer_data->driver_shift_from?>"
																class='form-control vehicle_elements' required readonly>
														</div>
														<span style="margin-left: -166px; font-weight: bold;"> to </span>
														<div class='col-sm-2 controls'>
															<input type="text" name="driver_shift_to" id="driver_shift_to" value="<?=$transfer_data->driver_shift_to?>"
																data-rule-required='true' placeholder="Driver Shift To"
																class='form-control vehicle_elements' required readonly>
														</div>
													</div>
												</div>

												<div class='form-group'>
													<label class='control-label col-sm-3'
														for='validation_includes'>Driver License </label>
													<div class='col-sm-4 controls'>
														<input type="text" name="driver_license" id="driver_license"
															data-rule-number='true' data-rule-required='true'
															class='form-control'
															value="<?php echo $transfer_data->driver_license;?>">
													</div>
												</div> -->


												<!-- <div class='box-header blue-background'>
													<div class=''>
														<h4>Price Info</h4>
													</div>
												</div>

												<div class='form-group'>
													<label class='control-label col-sm-3'
														for='validation_number'>Price </label>
													<div class='col-sm-3 controls'>


														<input type="text" name="price" id="price"
															data-rule-number='true' data-rule-required='true'
															class='form-control'
															value="<?php echo $transfer_data->price;?>">


													</div>
												</div> -->
												

												




												<div class='box-header blue-background'>
													<div class=''>
														<h4>Pricing Policy</h4>
													</div>
												</div>
												
												<div class='form-group'>
													<label class='control-label col-sm-3'
														for='validation_includes'>Price Includes </label>
													<div class='col-sm-4 controls'>

														<!-- <input type="text" name="includes" id="includes" data-rule-required='true' class='form-control'> -->
														<textarea name="includes" class="form-control"
															data-rule-required="true" value="" cols="70" rows="3"
															placeholder="Price Includes"><?php echo $transfer_data->price_includes;?></textarea>


													</div>
												</div>


												<div class='form-group'>
													<label class='control-label col-sm-3'
														for='validation_excludes'>Price Excludes </label>
													<div class='col-sm-4 controls'>

														<!-- <input type="text" name="excludes" id="excludes" data-rule-required='true' class='form-control'> -->
														<textarea name="excludes" class="form-control"
															data-rule-required="true" value="" cols="70" rows="3"
															placeholder="Price Excludes"><?php echo $transfer_data->price_excludes;?></textarea>

													</div>
												</div>
												
												<div class='form-group'>
													<label class='control-label col-sm-3'
														for='validation_advance'>Cancellation In Advance </label>
													<div class='col-sm-4 controls'>

														<!-- <input type="text" name="excludes" id="excludes" data-rule-required='true' class='form-control'> -->
														<textarea name="advance" class="form-control"
															data-rule-required="true" cols="70" rows="3"
															placeholder="Cancellation In Advance"><?php echo $transfer_data->cancellation_advance;?></textarea>

													</div>
												</div>
												<div class='form-group'>
													<label class='control-label col-sm-3'
														for='validation_excludes'>Cancellation Penalty </label>
													<div class='col-sm-4 controls'>

														<!-- <input type="text" name="excludes" id="excludes" data-rule-required='true' class='form-control'> -->
														<textarea name="penality" class="form-control"
															data-rule-required="true" cols="70" rows="3"
															placeholder="Cancellation Penalty"><?php echo $transfer_data->cancellation_penalty;?></textarea>

													</div>
												</div>
										
										</div>


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
												<i class='icon-save'></i> Update
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
	    $('#driver_shift_from').timepicker();
	    $('#driver_shift_to').timepicker();
    });
</script>