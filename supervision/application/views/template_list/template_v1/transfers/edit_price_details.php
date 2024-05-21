<?php 
$path = $GLOBALS['CI']->template->domain_upload_pckg_images();
// debug($transfer_price_data);exit;
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
							<h1>Edit Transfers Price</h1>
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
												action="<?php echo base_url(); ?>index.php/transfers/edit_transfer_price_details/<?php echo $transfer_data->id;?>"
												method="post" enctype="multipart/form-data">

												
												<div class='form-group'>
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
                                              <?php 
                                              foreach ($transfer_price_data as $key => $price_data) {
                                              	// debug($price_data['shift_day']);exit;
                                              	$count = $key+1;
                                              ?>
                                              
	                                                <tr class="price_management_tr">
	                                                  <td>
	                                                    <select class='form-control price_elements'
															data-rule-required='true' name="price[<?=$key?>][shift_day]" id="" required>
															<option value="">--Select Days--</option>
															 <?php
																for($l = 0; $l < count ( $weekdays_name ); $l ++) {
																	if($weekdays_name[$l]->id == $price_data->shift_day){
																		$select = 'selected="selected"';
																	}else{
																		$select = '';
																	}
																	?>
										                        <option value='<?php echo $weekdays_name[$l]->id; ?>' <?=$select?>> <?php echo $weekdays_name[$l]->day; ?>  </option>
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
	                                                  		<input type="hidden" name="price[<?=$key?>][id]" value="<?php echo $price_data->id;?>">
	                                                  		<input type="text" name="price[<?=$key?>][shift_from]" id="price_shift_from_<?=$count?>"
															data-rule-required='true' placeholder="Price Shift From" value="<?php echo $price_data->shift_from;?>"
															class='form-control price_elements' required readonly>
	                                                  </td>
	                                                  <td>
		                                                  <input type="text" name="price[<?=$key?>][shift_to]" id="price_shift_to_<?=$count?>"
														data-rule-required='true' placeholder="Price Shift To"
														class='form-control price_elements' value="<?php echo $price_data->shift_to;?>" required readonly>
														</td>	
	                                                  <td>
		                                                  <input type="text" name="price[<?=$key?>][price]" id="price_<?=$count?>"
														data-rule-required='true' value="<?php echo $price_data->price;?>" placeholder="Price"
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
	                                              ?>
	                                              </tbody>
	                                            </table>
										</div>

											<div class="add_price_btn"><span class="btn btn-primary pull-right" style="    margin-right: 48px;margin-top: 15px;">Add Price</span></div>
												
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

	    $('.add_price_btn').on('click',function(){
    		var count = $('.price_management_tr').length +1;
    		var price_div = '';
    		if(count<=6){
    		var length = $('.price_management_tr').length;

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
</script>