<!-- HTML BEGIN -->
<head>
<link rel="stylesheet" href="<?php echo base_url(); ?>hotel_assets/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>hotel_assets/css/font-icons/entypo/css/entypo.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>hotel_assets/css/bootstrap.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>hotel_assets/css/provab-core.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>hotel_assets/css/provab-theme.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>hotel_assets/css/provab-forms.css">

<link rel="stylesheet" href="<?php echo base_url(); ?>hotel_assets/css/custom.css">
	
	<link rel="stylesheet" href="<?php echo base_url(); ?>hotel_assets/assets/js/daterangepicker/daterangepicker-bs3.css"> 
	
	<link rel="stylesheet" href="<?php echo base_url(); ?>hotel_assets/js/selectboxit/jquery.selectBoxIt.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>hotel_assets/js/daterangepicker/daterangepicker-bs3.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>hotel_assets/js/select2/select2-bootstrap.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>hotel_assets/js/select2/select2.css">

</head>
<style> 
 .tab_error_color {
 	color: red !important;
 } 
 .tab_msg_color {
 	background: blue !important;
 }
</style>	

<div class="bodyContent" >
	<div class="panel panel-default clearfix">
		<!-- PANEL WRAP START -->
		<div class="panel-heading">
			<!-- PANEL HEAD START -->
			<div class="panel-title">
				<i class="fa fa-credit-card"></i> Hotel Management
			</div>
		</div>
		<!-- PANEL HEAD START -->
		
			<!-- PANEL BODY START -->
			<div class="panel-body hide">

				<div class="col-md-12">
          <?=$GLOBALS['CI']->template->isolated_view('hotel/hotel_widgets',['hotel_id'=>'', 'active'=>['step1'], 'current'=>'step1'])?> 

          </div>

           <div class="clearfix"></div>

          <div class="col-sm-12 hide">
                      <div class="card mt20 xs-mt10 sm-mt10">
                        <div class="card-header">
                          <div>
                            <div class="col-md-12">
                              <h2>Steps to Add Hotel</h2>
                            </div>
                          </div>
                        </div>
                        <div class="card-body">  
                          <div id="document-ele-carousal">
                            <ul class="wizard-verfication-ul">
                              <li>
                                <p class="para-small">
                                  Step
                                </p>
                                <div class="circle-icon-no">
                                  <span>
                                    1
                                  </span>
                                </div>
                                <p class="para">
                                  Add Hotel Basic Details
                                </p>
                              </li>
                              <li>  
                                <p class="para">
                                  Add Child/Infant Age Groups
                                </p>
                                <div class="circle-icon-no">
                                  <span>
                                    2
                                  </span>
                                </div>
                                <p class="para-small">
                                  Step
                                </p>                                
                              </li>
                              <li>
                                <p class="para-small">
                                  Step
                                </p>
                                <div class="circle-icon-no">
                                  <span>
                                    3
                                  </span>
                                </div>
                                <p class="para">
                                  Add Rooms Details
                                </p>
                              </li>
                              <li>
                                <p class="para">
                                  Add Seasons
                                </p>
                                <div class="circle-icon-no">
                                  <span>
                                    4
                                  </span>
                                </div>
                                <p class="para-small">
                                  Step
                                </p>                                
                              </li>
                              <li>
                                <p class="para-small">
                                  Step
                                </p>
                                <div class="circle-icon-no">
                                  <span>
                                    5
                                  </span>
                                </div>
                                <p class="para">
                                  Add Pricing
                                </p>
                              </li>
                            </ul>
                          </div>

                          <div id="post-identity-carousal" style="display: none;">
                            <ul class="wizard-verfication-ul wizard-verfication-ul2">
                              <li>
                                <p class="para-small">
                                  Step
                                </p>
                                <div class="circle-icon-no">
                                  <span>
                                    1
                                  </span>
                                </div>
                                <p class="para">
                                  Dowload the pre - populated 
                                  consent form from the secure server
                                </p>
                              </li>
                              <li>  
                                <p class="para">
                                  Sign and date the consent form                                                                    
                                </p>
                                <div class="circle-icon-no">
                                  <span>
                                    2
                                  </span>
                                </div>
                                <p class="para-small">
                                  Step
                                </p>                                
                              </li>
                              <li>
                                <p class="para-small">
                                  Step
                                </p>
                                <div class="circle-icon-no">
                                  <span>
                                    3
                                  </span>
                                </div>
                                <p class="para">
                                  Obtain certified true copies of Photo
                                </p>
                              </li>
                              <li>
                                <p class="para">
                                  Send in the signed and dated form and documents to Auth N Tick
                                </p>
                                <div class="circle-icon-no">
                                  <span>
                                    4
                                  </span>
                                </div>
                                <p class="para-small">
                                  Step
                                </p>                                
                              </li>                              
                            </ul>
                          </div>


                        </div>

                         <!--div class="col-md-12">
                      <div class="card comman-card-page mt0">
                        <div class="card-header">
                          <div>
                            <div class="col-md-12">
                              <h1 class="card-h1">Things required to Add Hotels</h1>
                              <p class="para-small card-para">Step 1/6</p> 
                            </div>
                          </div>
                        </div>
                        <div class="card-body">
                          <div class="clearfix">
                            <div class="col-lg-12">
                              <ul class="block-inline">
                                <li>
                                  Create Hotel Type
                                </li>
                                <li>
                                  Create Room Type
                                </li>
                                <li>
                                  Create Hotel ammenities
                                </li>
                                <li>
                                  Create Room ammenities
                                </li>
                                <li>
                                  In order to add a Hotel fill Hotel basic details like Name, Address, Description .
                                </li>
                                <li>
                                  Then add Hotel Banner Image and Hotel Images.
                                </li>
                                <li>                                  
                                 Then add Seasons details along with Room rates for that seasons based on Age groups
                                </li>
                                
                              </ul>
                            </div>
                          </div>
                        </div>
                      </div-->
                    </div>
                    
                      </div>
                     
                      

                      </div>
                    </div>

          <div class="clearfix"></div>
				<form method="post"  id="hotel" name="hotel" action="<?php echo base_url()."index.php/hotel/update_hotel_datas/{$hotel_id}"; ?>" class="form-horizontal form-groups-bordered validate" enctype= "multipart/form-data">
					<fieldset form="user_edit">
				<legend class="form_legend">Edit Hotel</legend>
				<?php if($supplier_rights == 1) { ?>
							<input type="hidden" name="supplier_rights" id="supplier_rights" value="<?php echo $supplier_rights; ?>" />
							<?php } else { ?>
							<input type="hidden" name="supplier_rights" id="supplier_rights" value="" />
							<?php } ?>
								<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">Hotel Type<span class="text-danger">*</span></label>									
									<div class="col-sm-5">
										 <select id="hotel_type" name="hotel_type" class="form-control" data-validate="required" data-message-required="Please Select the Hotel Type">										 
											 <option value="0">Select Hotel Type</option>
											<?php foreach ($hotel_types_list as $type){ ?>
												<option value="<?php echo $type->id; ?>" <?=$hotels_data->hotel_type_id == $type->id ? "selected" : "";?> data-iconurl=""><?php echo $type->name; ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
							<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">Hotel Name<span class="text-danger">*</span></label>									
									<div class="col-sm-5">
										<input type="text" class="form-control" id="hotel_name" maxlength="30" value="<?=$hotels_data->hotel_name?>" name="hotel_name" placeholder="Hotel Name" data-validate="required" data-message-required="Please enter the Hotel Name">
									</div>
								</div>
									<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">Contract Expire Date<span class="text-danger">*</span></label>									
									<div class="col-sm-5">
									<input type="text" class="form-control logpadding dateFrom" value="<?=$hotels_data->contract_expires_in?>" id="datepickerform" name="exclude_checkout_date" value="" id="exclude_checkout_date" data-min-date="<?php echo date('m-d-Y');?>" data-validate="required" data-message-required="Please Select the Contract Expire Date" readonly />										
									</div>
								</div>
						<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">Star Rating<span class="text-danger">*</span></label>									
									<div class="col-sm-5">
										<select id="star_rating" name="star_rating" class="form-control" data-validate="required" data-message-required="Please Select the Star Rating">
											 <option value="">Select Rating</option>
											
												<option value="1" <?=$hotels_data->star_rating == 1 ? "selected" : "";?> data-iconurl="">1</option>
												<option value="2" <?=$hotels_data->star_rating == 2 ? "selected" : "";?> data-iconurl="">2</option>
												<option value="3" <?=$hotels_data->star_rating == 3 ? "selected" : "";?> data-iconurl="">3</option>
												<option value="4" <?=$hotels_data->star_rating == 4 ? "selected" : "";?> data-iconurl="">4</option>
												<option value="5" <?=$hotels_data->star_rating == 5 ? "selected" : "";?> data-iconurl="">5</option>
											
										</select>
									</div>
								</div>

								<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">Hotel Description<span class="text-danger">*</span></label>									
									<div class="col-sm-5">

										<textarea class="form-control" id="hotel_description"   name="hotel_description" placeholder="Hotel Description" data-message-required="Please enter the Hotel Description" data-validate="required" data-message-required="Please Enter Hotel Description"><?=$hotels_data->hotel_description?></textarea>
									</div>
								</div>
								<div class="form-group">							  	   	
									<label for="field-1" class="col-sm-3 control-label">Amenities <span class="text-danger">*</span></label>									
									<div class="col-sm-5">
										 <select id="ammenities" name="ammenities[]" class="ddssselect2" multiple style="width:300px" data-validate="required" data-message-required="Please enter the amenities">										 
											<?php 
											$am = explode(",",$hotels_data->amenities);
                      foreach ($hotel_amenities_list as $j => $list) {
                        # code...
                    ?>
												<option value="<?php echo $list->id; ?>" data-iconurl="" <?=in_array($list->id,$am) ? "selected" : "";?> > <?php echo $list->name; ?></option>
											<?php } ?>
										</select>
									</div>
								</div>	
								<div class="form-group">
                                        <label for="field-1" class="col-sm-3 control-label">Country<span class="text-danger">*</span></label>									
                                        <div class="col-sm-5">                                            
                                            <select name="country" id="country" onchange="select_city(this.value,'<?php echo $country[$c]->country_name; ?>')" class="form-control" data-validate="required" data-message-required="Please Select the Country">
                                                <option value="">Select Country</option>                                                    
                                               <?php for ($c = 0; $c < count($country); $c++) { ?>
                                                    <option value="<?php echo $country[$c]->country_name; ?>" <?=$hotels_data->country == $country[$c]->country_name ? "selected" : "";?> data-iconurl=""><?php echo $country[$c]->country_name; ?></option>
                                                <?php } ?>
                                            </select>
                                            <?php echo form_error('country_id', '<span for="field-1" class="validate-has-error">', '</span>'); ?>
                                        </div>                                        
                                </div>  

                                <div class="form-group" id="api">
									<label for="field-1"  class="col-sm-3 control-label">City<span class="text-danger">*</span></label>									
									<div class="col-sm-5">                                      
										<select name="city_name"  class="form-control"  value="<?=$hotels_data->city?>" id="city_name" data-validate="required" data-message-required="Please Select the City">											
										<option value="">Select</option>
										</select>
                    <?php echo form_error('city_name',  '<span for="field-1" class="validate-has-error">', '</span>'); ?>
									</div>
								 </div> 
								

								
								<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">Hotel Map<span class="text-danger">*</span></label>									
									<div class="col-sm-5">
                                      <div id="map_canvas" style="height:300px;width:700px;margin: 0.6em;">                                      	
                                      </div>
                                    </div>
                                </div>   
                                <div class="form-group">                                    
                                    <label for="field-1" class="col-sm-3 control-label">Hotel Address<span class="text-danger">*</span></label>									
									<div class="col-sm-5">
										<textarea class="form-control" id="hotel_address"   name="hotel_address" placeholder="Hotel Address" data-validate="required" data-message-required="Please enter the Hotel Address" id="hotel_address" rows="7" readonly="true"><?=$hotels_data->hotel_address?></textarea>
									</div> 								
								</div>
								<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">Latitude<span class="text-danger">*</span></label>									
									<div class="col-sm-5">
										<input type="text" class="form-control" name="latitude"  value="<?=$hotels_data->lattitude?>"   onblur="getmap()" placeholder="Latitude" data-validate="required" data-message-required="Please enter the Latitude of Hotel" id="lat" readonly="true">
									</div>
								</div>
								<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">Longitude<span class="text-danger">*</span></label>									
									<div class="col-sm-5">
										<input type="text" class="form-control" name="longitude"  value="<?=$hotels_data->longtitude?>"    onblur="getmap()" placeholder="Longitude" data-validate="required" data-message-required="Please enter the Longitude of Hotel" id="lng" readonly="true">
									</div>
								</div>
								<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">Postal Code<span class="text-danger">*</span></label>									
									<div class="col-sm-5">										
										<input type="text" class="form-control"  value="<?=$hotels_data->postal_code?>" name="postal_code">
									</div>
								</div>

								<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">Phone Number<span class="text-danger">*</span></label>									
									<div class="col-sm-5">
										<input type="text" class="form-control"  id="phone_number"  value="<?=$hotels_data->phone_number?>" name="phone_number" maxlength="15" placeholder="Phone Number" data-validate="required" placeholder="Postal Code" data-rule-number="true" data-message-required="Please enter the Phone Number">
									</div>
								</div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-3 control-label">Fax Number</label>                  
                  <div class="col-sm-5">
                    <input type="text" class="form-control"   name="fax_number" maxlength="15" placeholder="Fax Number"  value="<?=$hotels_data->fax_number?>" >
                  </div>
                </div>
								<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">Email<span class="text-danger">*</span></label>									
									<div class="col-sm-5">
										<input type="text" class="form-control"  id="email" name="email"  value="<?=$hotels_data->email?>" onBlur="checkUniqueEmail(this.value)" placeholder="Email Address" data-validate="email,required" data-message-required="Please enter the Valid Email Id"/>
										
									</div>
								</div>
                <div class="form-group">
                  <label for="field-1" class="col-sm-3 control-label">Image<span class="text-danger">*</span></label>                 
                  <div class="col-sm-5">
                    <?php echo '<img src="'.DOMAIN_HOTEL_IMAGE_DIR.$hotels_data->image.'" alt="" width="100" height="100">'; ?>
                    <input type="file" class="form-control"  name="image" accept="image/*"   />
                    
                  </div>
                </div>
							<!--<div class="form-group">-->
							<!--	<label for="field-1" class="col-sm-3 control-label">Upload Hotel Images<span class="text-danger">*</span></label>-->
							<!--	<div class="col-sm-5">-->
							<!--		<div class="fileinput fileinput-new" data-provides="fileinput">-->
							<!--			<div class="fileinput-new thumbnail" data-trigger="fileinput">-->
							<!--				<img src="<?php echo $GLOBALS['CI']->template->domain_images($GLOBALS['CI']->template->get_domain_logo()); ?>" alt="Hotel Images">-->
							<!--			</div>-->
							<!--			<div class="fileinput-preview fileinput-exists thumbnail"></div>-->
							<!--			<div>-->
							<!--				<span class="btn btn-white btn-file">-->
							<!--					<span class="fileinput-new">Select image</span>-->
							<!--					<span class="fileinput-exists">Change</span>-->
							<!--					<input name="hotel_image[]" id="hotel_image" type="file" multiple  required/>-->
							<!--					<input type="hidden" name="imageflag1" id="imageflag1">-->
							<!--				</span>-->
							<!--				<a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput">Remove</a>-->
							<!--			</div>-->
							<!--		</div>-->
							<!--	</div>-->
							<!--</div>-->
								<div class="form-group">
									<label class="col-sm-3 control-label">&nbsp;</label>									
									<div class="col-sm-5">
										<input type="submit" name="submit"  class="btn btn-success" value="Update">
										<!--<input type="submit" name="submit"  class="btn btn-primary" value="Save">-->
										<a href="<?=base_url('index.php/hotel/hotel_crs_list')?>"   class="btn btn-danger" >Close</a>
									</div>
								</div>
				</form>
			</div>
		
		<!-- PANEL BODY END -->
	</div>
	<!-- PANEL END -->
</div>

<!-- Page Ends Here -->
<!--Load Js--> 
	<script src="<?php echo base_url(); ?>hotel_assets/js/gsap/main-gsap.js"></script>
	<script src="<?php echo base_url(); ?>hotel_assets/js/jquery-ui.min.js"></script>
	<script src="<?php echo base_url(); ?>hotel_assets/js/store.min.js"></script>
	<script src="<?php echo base_url(); ?>hotel_assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js"></script>
	<script src="<?php echo base_url(); ?>hotel_assets/js/bootstrap.js"></script>
	<script src="<?php echo base_url(); ?>hotel_assets/js/joinable.js"></script>
	<script src="<?php echo base_url(); ?>hotel_assets/js/resizeable.js"></script>
	<script src="<?php echo base_url(); ?>hotel_assets/js/jquery.validate.min.js"></script>
	<script src="<?php echo base_url(); ?>hotel_assets/js/provab-login.js"></script>
	<script src="<?php echo base_url(); ?>hotel_assets/js/provab-api.js"></script>
	<script src="<?php echo base_url(); ?>hotel_assets/js/jquery-idleTimeout.js"></script>
	
	<script src="<?php echo base_url(); ?>hotel_assets/js/provab-custom.js"></script>
	
	<script src="<?php echo base_url(); ?>assets/js/bootstrap-switch.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/jquery.validate.min.js"></script>	
	<script src="<?php echo base_url(); ?>assets/js/fileinput.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/selectboxit/jquery.selectBoxIt.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/jquery.bootstrap.wizard.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/ckeditor/ckeditor.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/ckeditor/adapters/jquery.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/bootstrap-timepicker.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/bootstrap-datepicker.js"></script>

	<script src="<?php echo base_url(); ?>assets/js/select2/select2.min.js"></script>
   <script src="<?php echo base_url(); ?>assets/js/jquery-ui.js"></script>

  <script src="<?php echo base_url(); ?>assets/js/jquery.dataTables.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/datatables/TableTools.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/dataTables.bootstrap.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/datatables/jquery.dataTables.columnFilter.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/datatables/lodash.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/datatables/responsive/js/datatables.responsive.js"></script>   
 <!--    <script src="<?= base_url(); ?>assets/js/plugins/datatables/dataTables.overrides.js" type="text/javascript"></script>
    <script src="<?= base_url(); ?>assets/js/plugins/lightbox/lightbox.min.js" type="text/javascript"></script>
 <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jquery-ui.css" type="text/css">-->
  
  
  
  

<script type="text/javascript">

$(document).ready(function () {
	window.onload =getmap();
			$('#datepickerform').datepicker({
				dateFormat: 'dd/mm/yy',
				minDate: 0,
				//firstDay: 1,
				//maxDate: "+1Y",
			});
		});
$("#ammenities").select2();
$("#cancellation").hide();
	function addMoreRooms1() {
			$("#cancellation_clone").css({'display':'inherit'});
			var id = $('#rows_cnt').val();
			
	    	$("#cancellation_clone").append( '<div class="form-group" style="widht:80%;" ><div class="col-sm-4">'+								
								'<input type="text" class="form-control" data-rule-number="true" name="cancellation_from[]" id="cancellation_from'+id+'" value="">'+
								'</div>'+
								'<div class="col-sm-4">'+							
								'<input type="text" class="form-control" data-rule-number="true" name="cancellation_nightcharge[]" id="cancellation_nightcharge'+id+'" value="">'+
								'</div>'+
								'<div class="col-sm-4 ">'+							
								' <input type="text" class="form-control" data-rule-number="true" value="" name="cancellation_percentage[]" id="cancellation_percentage'+id+'" value=""> </div></div>');																				
			id = parseInt(id)+1;
			$('#rows_cnt').val(id);																

		}

		function removeLastRoom1(v){
			var id = $('#rows_cnt').val();
			$('#cancellation_clone .form-group').last().remove();
			if(id <= 1) {
				$("#cancellation_clone").css({'display':'none'});
			}
			id = parseInt(id)-1;
			$('#rows_cnt').val(id);
		}

		$("#hotel_cancellation").on("change",function(){
			var hotel_cancellation = $("#hotel_cancellation").val();
			if(hotel_cancellation == 0){
				$("#cancellation").hide();
			}else{
				$("#cancellation").show();
			}
		});
</script>	
<script> 
$("#event_title").hide();
		$(function(){			  
			$('#exclude_checkin_time').timepicker({
      			pickDate: false,
      			showMeridian: false
    });
			$('#exclude_checkout_time').timepicker({
      			pickDate: false,
      			showMeridian: false
    });
    
			$('#status').change(function(){
				var current_status = $('#status').val();
				if(current_status == "ACTIVE")
					$('#status').val('INACTIVE');
				else
					$('#status').val('ACTIVE');
			});

			$('#top_deals').change(function(){
				var current_status = $('#top_deals').val();
				if(current_status == "1")
					$('#top_deals').val('0');
				else
					$('#top_deals').val('1');
			});

			$('#event').change(function(){
				var current_status = $('#event').val();
				if(current_status == "1"){
					$('#event').val('0');
					$("#event_title").hide();
					$('#remove').remove();
					$("#event_name").removeAttr('required');
				}
				else{
					$("#event_title").show();
					$("#event_name").attr("required", "true");
					
					$('#event').val('1');
					$('#distance').append('<div class="form-group" id="remove"><label for="field-1" class="col-sm-3 control-label">Distance from the Conference</label><div class="col-sm-5"><textarea class="form-control" name="distance" placeholder="Distance from the Conference" data-message-required="Please enter the Distance from the Conference " required></textarea></div></div>');
				}
			});


			$(".top_deals").click(function(){
				alert("#top_deals").val();
			})
			// $('#countries_list').change(function(){
				// var country = $('#countries_list').val();
				// $.ajax({
					// type: "POST",
					// url:'<?php echo base_url(); ?>hotel/filter_city_list/'+country,
					// dataType: "json",
					// success: function(data){
						// if (data.status == 1) {
							// $('#cities_div').html(data.city);
						// } 
					// }  
				// });
			// }); 
			
			
			var hotel_name = document.getElementById('hotel_name');
			var hotel_code = document.getElementById('hotel_code');
			var position = document.getElementById('position');
			var postal_code = document.getElementById('postal_code');
			var phone_number = document.getElementById('phone_number');
			var email = document.getElementById('email');
			var hotel_address = document.getElementById('hotel_address');
			var countries_list = document.getElementById('countries_list');			

			$('input#hotel_name').keyup(function() {
				var $th = $(this);		
				if($th.val().trim() != ""){
					 var regex = /^[a-zA-Z 0-9!@#$%^&*_() - +=:;'",. ]*$/;
					if (regex.test($th.val())) {
						$th.css('border', '1px solid #099A7D');					
					} else {
						// alert("Please use only letters");
						$th.css('border', '1px solid #f52c2c');
						return '';
					}
				}else if(hotel_name.value.length < 2 || hotel_name.value.length > 60) {
					    hotel_name.style.border = "1px solid #f52c2c";   
						hotel_name.focus(); 
						return false; 
				}
			});	
			
			$("#add_location").click(function(){
				$("#add_location").hide();
				$("#location_info").hide();
				$("#location_name").slideToggle("slow");
			});
			
			$('input#postal_code').keyup(function() {
				var $th = $(this);		
				if($th.val().trim() != ""){
					 var regex = /^[0-9]*$/;
					if (regex.test($th.val())) {
						$th.css('border', '1px solid #099A7D');					
					} else {
						// alert("Please use only letters");
						$th.css('border', '1px solid #f52c2c');
						return '';
					}
				
				}
				 if(postal_code.value.length > 7 ) {
					    postal_code.style.border = "1px solid #f52c2c";   
						postal_code.focus(); 
						return false; 
				}
			});				
			
			
			$('#hotel').submit(function(){	

				var hotel_type1 = $('#hotel_type').val();				
				if(hotel_type1 == "0"){					
					hotel_type.style.border = "1px solid #f52c2c";   
					hotel_type.focus(); 
					return false; 			
				}				

				var country_id = $('#country').val();      				
				if(country_id == "0"){										
					country.style.border = "1px solid #f52c2c";   
					country.focus(); 
					return false; 		
				}				

				var city_id = $('#city_name').val();
				if(city_id == "0"){					
					city_name.style.border = "1px solid #f52c2c";   
					city_name.focus(); 
					return false; 		
				}	


			    var filter = /^[a-zA-Z 0-9!@#$%^&*()-+=:;'",.  ]*$/;
			    var alpha_filter = /^[a-zA-Z 0-9]*$/;
			    var number_filter = /^[0-9]*$/;
			    var number_filter2 = /^[+0-9]*$/;

				if(hotel_name.value != '')
				{
					if(!(hotel_name.value.match(filter)))
					{
						hotel_name.style.border = "1px solid #f52c2c";   
						hotel_name.focus(); 
						return false; 
					}
				}
				else
				{
					hotel_name.style.border = "1px solid #f52c2c";   
					hotel_name.focus(); 
					return false; 
				}

				if(hotel_name.value.length < 2 || hotel_name.value.length > 50) {
					    hotel_name.style.border = "1px solid #f52c2c";   
						hotel_name.focus(); 
						return false; 
				}								

				if(location_info.value == "select" && location_name.value == ""){
					location_info.style.border = "1px solid #f52c2c";   
					location_name.style.border = "1px solid #f52c2c";   															
					return false;
				}
				
				if(postal_code.value != '')
				{
					if(!(postal_code.value.match(number_filter)))
					{
						postal_code.style.border = "1px solid #f52c2c";   
						postal_code.focus(); 
						return false; 
					}
				}
				else
				{
					postal_code.style.border = "1px solid #f52c2c";   
					postal_code.focus(); 
					return false; 
				}
				
				if(postal_code.value == '') {
					    postal_code.style.border = "1px solid #f52c2c";   
						postal_code.focus(); 
						return false; 
				}

				if(postal_code.value.length > 8 ) {
					    postal_code.style.border = "1px solid #f52c2c";   
						postal_code.focus(); 
						return false; 
				}			
		
				
				if(phone_number.value ==  '') {
					    phone_number.style.border = "1px solid #f52c2c";   
						phone_number.focus(); 
						return false; 
				}

				if(phone_number.value.length > 50 ) {
					    phone_number.style.border = "1px solid #f52c2c";   
						phone_number.focus(); 
						return false; 
				}
				if(email.value ==  '') {
					    email.style.border = "1px solid #f52c2c";   
						email.focus(); 
						return false; 
				}
				
				if(hotel_address.value == '') {
					 hotel_address.style.border = "1px solid #f52c2c";   
						hotel_address.focus(); 
						return false; 
				}


				
			
			});
		
			$.fn.checkFileType = function(options1) {
				var defaults = {
					allowedExtensions: [],
					success: function() {},
					error: function() {}
				};
				options1 = $.extend(defaults, options1);

				return this.each(function() {

					$(this).on('change', function() {
						var value = $(this).val(),
							file = value.toLowerCase(),
							extension = file.substring(file.lastIndexOf('.') + 1);

						if ($.inArray(extension, options1.allowedExtensions) == -1) {
							options1.error();
							$(this).focus();
						} else {
							options1.success();

						}

					});

				});
			};
			
			$('#thumb_image').checkFileType({
				allowedExtensions: ['jpg', 'jpeg','png'],
				success: function() {
					file_upload = true;
					// alert('Success');
					 $("#imageflag").val("true");
				},
				error: function() {
					file_upload = false;
					alert('Please Select Valid Image (Ex: jpg,jpeg,png) ');
					 $("#imageflag").val("false");
			   	 
				}
			});

  var _URL = window.URL || window.webkitURL;
$("#thumb_image").change(function(e) {
    var file, img;
    if ((file = this.files[0])) {
        img = new Image();
        img.onload = function() {
          if((this.width > 1080) || (this.height > 20))
          {
            // alert("image size should be 1080*720");
             this.value = "";
          }
        };
        img.onerror = function() {
            alert( "not a valid file: " + file.type);
        };
        img.src = _URL.createObjectURL(file);


    }

});

     /*var uploadField = document.getElementById("thumb_image");

      uploadField.onchange = function() {
        if(this.files[0].size < 1300){
          alert("File is too small!");
          this.value = "";
        };
        if(this.files[0].size > 10000000){
          alert("File is too big!");
          this.value = "";
        };
      };*/
		

			$('#hotel_image').checkFileType({
				allowedExtensions: ['jpg', 'jpeg','png'],
				success: function() {
					file_upload = true;
					// alert('Success');
					 $("#imageflag1").val("true");
				},
				error: function() {
					file_upload = false;
					alert('Please Select Valid Image (Ex: jpg,jpeg,png) ');
					 $("#imageflag1").val("false");
			   	 
				}
			});

			return false;
			
			
			
		});
		select_city('<?php echo $hotels_data->country; ?>','<?php echo $hotels_data->city; ?>');
		function select_city(country_id,selected){
		   /* console.log("here");
		  console.log(country_id);*/
		 if (country_id != '') {         	  
          var select1 = $('#city_name');          
          $.ajax({
            url: '<?php echo base_url(); ?>index.php/hotel/get_city_name/'+country_id+'/'+selected,
            success: function (data, textStatus, jqXHR) {                                    
              select1.html('');
              select1.html(data);
              select1.trigger("chosen:updated");  
          	}
           });         
         }		
		}
		
		function select_location(city_id){				 
		 if (city_id != '') {         	  
          var location_select = $('#location_info');          
          $.ajax({
            url: '<?php echo base_url(); ?>index.php/hotel/get_location_name/' + city_id,
            success: function (data, textStatus, jqXHR) {                                   
              location_select.html('');
              location_select.html(data);
              location_select.trigger("chosen:updated");  
          	}
           });         
          }		
		 }
		
	</script>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false&key=AIzaSyCJfvWH36KY3rrRfopWstNfduF5-OzoywY"></script>
   	<script>   	
		var map;
		var geocoder;
		var mapOptions = { center: new google.maps.LatLng(0.0, 0.0), zoom: 2,
        mapTypeId: google.maps.MapTypeId.ROADMAP };
		
		function initialize() {			
			var myOptions = {
                center: new google.maps.LatLng(12.851, 77.659 ),
                //center: new google.maps.LatLng(-1.9501,30.0588),
                zoom: 10,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };

            geocoder = new google.maps.Geocoder();
            var map = new google.maps.Map(document.getElementById("map_canvas"),
            myOptions);
            google.maps.event.addListener(map, 'click', function(event) {
            	placeMarker(event.latLng);
            });

            var marker;
            function placeMarker(location) {            	
                if(marker){ //on vérifie si le marqueur existe
                    marker.setPosition(location); //on change sa position
                }else{
                    marker = new google.maps.Marker({ //on créé le marqueur
                        position: location, 
                        map: map
                    });
                }
                 document.getElementById('lat').value=location.lat();
                 document.getElementById('lng').value=location.lng();
                getAddress(location);
            }

			function getAddress(latLng) {				
				geocoder.geocode( {'latLng': latLng},
				function(results, status) {
					if(status == google.maps.GeocoderStatus.OK) {
					  if(results[0]) {					 
						document.getElementById("hotel_address").value 	= results[0].formatted_address;
						var address = results[0].address_components;
						var zipcode = address[address.length - 1].long_name;
						//document.getElementById("city").value 		= results[0].address_components[1]['long_name'];
						document.getElementById("postal_code").value 	= zipcode;						
					  }
					  else {
						//document.getElementById("city").value = "No results";
					  }
					}
					else {
					  //document.getElementById("city").value = status;
					}
				});
			}
			getmap();
		}
      google.maps.event.addDomListener(window, 'load', initialize);

      function getmap(){
		  
	 	var edValue = document.getElementById("lat");
		//console.log(edValue);
        lat = edValue.value;
      	var edValue = document.getElementById("lng");
        lng = edValue.value;        
        var newPosition = new google.maps.LatLng(lat,lng);
        if(lat > 0 && lng > 0){
           myOptions = {                
                center: new google.maps.LatLng(lat,lng),
                zoom: 10,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            geocoder = new google.maps.Geocoder();
            map = new google.maps.Map(document.getElementById("map_canvas"),myOptions);                        
            marker = new google.maps.Marker({ //on créé le marqueur
                        position: newPosition, 
                        map: map
            });            
            getAddress2(newPosition);        
       }        
	 }

	function getAddress2(latLng) {				
				geocoder.geocode( {'latLng': latLng},
				function(results, status) {
					if(status == google.maps.GeocoderStatus.OK) {
					  if(results[0]) {					 
						document.getElementById("hotel_address").value 	= results[0].formatted_address;
						var address = results[0].address_components;
						var zipcode = address[address.length - 1].long_name;
						//document.getElementById("city").value 		= results[0].address_components[1]['long_name'];
						document.getElementById("postal_code").value 	= zipcode;						
					  }
					  else {
						//document.getElementById("city").value = "No results";
					  }
					}
					else {
					  //document.getElementById("city").value = status;
					}
				});
			}
      
       function addMoreRooms(c) {
			var id = $('#rows_cnt').val();
			$("#rooms").css({'display':'inherit'});
			$("#rooms").append('<div class="form-group"><label for="field-1" class="col-sm-3 control-label">Exclude Checkout Date</label><div class="col-md-5"><input type="text" class="form-control datepicker" name="exclude_checkout_date[]" id="exclude_checkout_date'+id+'" data-min-date="<?php echo date('m-d-Y');?>" data-validate="required" data-message-required="Please Select the Date Range" /></div></div>');
			$('#datepicker').datepicker({
				
				dateFormat: 'dd/mm/yy',
				minDate: 0,
				firstDay: 1,
				maxDate: "+1Y",
			}
				);
			id = id+1;
			$('#rows_cnt').val(id);
		}
		function removeLastRoom(v){
			var id = $('#rows_cnt').val();
			$('#rooms .form-group').last().remove();
			id = id-1;
			$('#rows_cnt').val(id);
		}
		function checkUniqueEmail(email){
			var sEmail = document.getElementById('email');
			if (sEmail.value != ''){
				var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
				if(!(sEmail.value.match(filter))){
					$("#email").val(email);
					return false; 
				}else{
				}
			}
			return false;
		}
		 function geocodeAddress(address) {
	 		geocoder.geocode({address:address}, function (results,status)
		      { 
		         if (status == google.maps.GeocoderStatus.OK) {
		          var p = results[0].geometry.location;
		          var lat=p.lat();
		          var lng=p.lng();
		          //createMarker(address,lat,lng);
		          ///alert(lng);
		          var myOptions = {
	                center: new google.maps.LatLng(lat, lng ),
			                //center: new google.maps.LatLng(-1.9501,30.0588),
			                zoom: 10,
			                mapTypeId: google.maps.MapTypeId.ROADMAP
			            };
              	 	var map = new google.maps.Map(document.getElementById("map_canvas"),myOptions);
	              	 google.maps.event.addListener(map, 'click', function(event) {
	              	 	placeMarker(event.latLng);
		            });	

	              	 var marker;
		            function placeMarker(location) {            	
		                if(marker){ //on vérifie si le marqueur existe
		                    marker.setPosition(location); //on change sa position
		                }else{
		                    marker = new google.maps.Marker({ //on créé le marqueur
		                        position: location, 
		                        map: map
		                    });
		                }
		                 document.getElementById('lat').value=location.lat();
		                 document.getElementById('lng').value=location.lng();
		                getAddress(location);
		            }

					function getAddress(latLng) {				
						geocoder.geocode( {'latLng': latLng},
						function(results, status) {
							if(status == google.maps.GeocoderStatus.OK) {
							  if(results[0]) {					 
								document.getElementById("hotel_address").value 	= results[0].formatted_address;
								var address = results[0].address_components;
								var zipcode = address[address.length - 1].long_name;
								//document.getElementById("city").value 		= results[0].address_components[1]['long_name'];
								document.getElementById("postal_code").value 	= zipcode;						
							  }
							  else {
								//document.getElementById("city").value = "No results";
							  }
							}
							else {
							  //document.getElementById("city").value = status;
							}
						});
					}
		        }
		        
		      }
		    );
		  }

		
		 $('#city_name').on('change',function(){
		 	var search_city  = $('#city_name').val();
		 	var country = $('#country').val();
		 	if(search_city!=''){
		 		geocodeAddress(search_city+','+country);
		 	}
		 });

</script>