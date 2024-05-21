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
 .btn{
  margin-right: 4px;
 }
</style>	

<div class="bodyContent">
	<div class="panel panel-default clearfix">
		<!-- PANEL WRAP START -->
		<div class="panel-heading">
			<!-- PANEL HEAD START -->
			<div class="panel-title">
				<i class="fa fa-credit-card"></i> Edit Room Details
			</div>
		</div>
    <a href="<?php echo base_url()."index.php/hotel/room_crs_list/{$data->hotel_id}"; ?>" class="btn btn-primary addnwhotl pull-right">Back</a>
  
		<!-- PANEL HEAD START -->
		
			<!-- PANEL BODY START -->
			<div class="panel-body pull-left" style="width: 100%;display:none;">

				<div class="col-md-12 hide">
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
				<form method="post"  id="hotel" name="hotel" action="<?php echo base_url()."index.php/hotel/update_room/{$data->id}"; ?>" class="form-horizontal form-groups-bordered validate" enctype= "multipart/form-data">
					<fieldset form="user_edit">
				<legend class="form_legend">Update Room</legend>

							<input type="hidden" name="hotel_id" id="hotel_id" value="<?php echo $data->hotel_id; ?>" />
							<input type="hidden" name="type" id="type" value="2" />
					
								<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">Hotel Room Type<span class="text-danger">*</span></label>									
									<div class="col-sm-5">
										 <select id="room_type_id" name="room_type_id" class="form-control" data-validate="required" data-message-required="Please Select the Hotel Room Type">										 
											 <option value="0">Select</option>
											<?php foreach ($room_types_list as $type){ ?>
												<option value="<?php echo $type->id; ?>" <?php echo $type->id == $data->room_type_id ? 'selected':''; ?> data-iconurl=""><?php echo $type->name; ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
					
						<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">Maximum Passengers Capacity<span class="text-danger">*</span></label>									
									<div class="col-sm-5">
										<select id="max_stay" name="max_stay" class="form-control" data-validate="required" data-message-required="Please Select maximum passengers">
											 <option value="">Select</option>
											
												<option value="1" <?=$data->max_stay == 1 ? "selected" : "";?> data-iconurl="">1</option>
												<option value="2" <?=$data->max_stay == 2 ? "selected" : "";?> data-iconurl="">2</option>
												<option value="3" <?=$data->max_stay == 3 ? "selected" : "";?> data-iconurl="">3</option>
												<option value="4" <?=$data->max_stay == 4 ? "selected" : "";?> data-iconurl="">4</option>
												<option value="5" <?=$data->max_stay == 5 ? "selected" : "";?> data-iconurl="">5</option>
												<option value="6" <?=$data->max_stay == 6 ? "selected" : "";?> data-iconurl="">6</option>
											
										</select>
									</div>
								</div>

	<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">Maximum Adult Capacity<span class="text-danger">*</span></label>									
									<div class="col-sm-5">
										<select id="max_adult_capacity" name="max_adult_capacity" class="form-control" data-validate="required" data-message-required="Please Select maximum adult capacity">
											 <option value="">Select</option>
											
												<option value="1" <?=$data->max_adult_capacity == 1 ? "selected" : "";?> data-iconurl="">1</option>
												<option value="2" <?=$data->max_adult_capacity == 2 ? "selected" : "";?> data-iconurl="">2</option>
												<option value="3" <?=$data->max_adult_capacity == 3 ? "selected" : "";?> data-iconurl="">3</option>
												<option value="4" <?=$data->max_adult_capacity == 4 ? "selected" : "";?> data-iconurl="">4</option>
												<option value="5" <?=$data->max_adult_capacity == 5 ? "selected" : "";?> data-iconurl="">5</option>
												<option value="6" <?=$data->max_adult_capacity == 6 ? "selected" : "";?> data-iconurl="">6</option>
											
										</select>
									</div>
								</div>
				<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">Maximum Child Capacity<span class="text-danger">*</span></label>									
									<div class="col-sm-5">
										<select id="max_adult_capacity" name="max_child_capacity" class="form-control" data-validate="required" data-message-required="Please Select maximum child capacity">
											 <option value="">Select</option>
											
												<option value="1" <?=$data->max_child_capacity == 1 ? "selected" : "";?> data-iconurl="">1</option>
												<option value="2" <?=$data->max_child_capacity == 2 ? "selected" : "";?> data-iconurl="">2</option>
												<option value="3" <?=$data->max_child_capacity == 3 ? "selected" : "";?> data-iconurl="">3</option>
												<option value="4" <?=$data->max_child_capacity == 4 ? "selected" : "";?> data-iconurl="">4</option>
												<option value="5" <?=$data->max_child_capacity == 5 ? "selected" : "";?> data-iconurl="">5</option>
												<option value="6" <?=$data->max_child_capacity == 6 ? "selected" : "";?> data-iconurl="">6</option>
											
										</select>
									</div>
								</div>
								
								<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">Extra Bed Availability<span class="text-danger">*</span></label>									
									<div class="col-sm-5">
										<select id="extra_bed" name="extra_bed" class="form-control" data-validate="required" data-message-required="Please Select the Extra Bed Availability">

												<option value="Y" <?=$data->extra_bed == "Y" ? "selected" : "";?> data-iconurl="">Available</option>
												<option value="N" <?=$data->extra_bed == "N" ? "selected" : "";?> data-iconurl="">Not Available</option>
									
											
										</select>
									</div>
								</div>								
								<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">Room Description<span class="text-danger">*</span></label>									
									<div class="col-sm-5">
										<textarea style="min-height:150px;" id="room_description" name="room_description" class="form-control" data-validate="required" data-message-required="Room description required"><?=$data->room_description?></textarea>
									</div>
								</div>
							
			<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">Room Policy<span class="text-danger">*</span></label>									
									<div class="col-sm-5">
										<textarea style="min-height:150px;" id="room_policy" name="room_policy" class="form-control" data-validate="required" data-message-required="Room policy required"><?=$data->room_policy?></textarea>
									</div>
								</div>
							
							

								<div class="form-group">							  	   	
									<label for="field-1" class="col-sm-3 control-label">Room Amenities<span class="text-danger">*</span></label>									
									<div class="col-sm-5">
										 <select id="ammenities" name="room_amenities[]" class="ddssselect2" multiple style="width:484px" data-validate="required" data-message-required="Please enter the amenities">										 
											<?php
											$am = explode(",",$data->room_amenities);
											foreach ($ammenities_list as $ame){ ?>
												<option value="<?php echo $ame->id; ?>" <?=in_array($ame->id,$am) ? 'selected' : ''?> data-iconurl=""><?php echo $ame->name; ?></option>
											<?php } ?>
										</select>
									</div>
								</div>	
								<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">Status<span class="text-danger">*</span></label>									
									<div class="col-sm-5">
										<select id="star_rating" name="status" class="form-control" data-validate="required" data-message-required="Please Select the Status">

												<option value="ACTIVE" <?=$data->status == "ACTIVE" ? "selected" : "";?> data-iconurl="">ACTIVE</option>
												<option value="INACTIVE" <?=$data->status == "INACTIVE" ? "selected" : "";?> data-iconurl="">INACTIVE</option>
									
											
										</select>
									</div>
								</div>
						
								<div class="form-group">
									<label class="col-sm-3 control-label">&nbsp;</label>									
									<div class="col-sm-5">
										
										<!--<input type="submit" name="submit"  class="btn btn-success" value="Continue">-->
										<input type="submit" name="submit"  class="btn btn-primary btn btn-primary  btn btn-success  " value="Update">
										<a href="<?=base_url("index.php/hotel/room_crs_list/{$data->hotel_id}")?>" class="btn btn-success12 btn btn btn-warning " >Back</a>
										<!--<a href="<?=base_url('index.php/hotels/hotel_crs_list')?>"   class="btn btn-danger" >Exit</a>-->
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
		function select_city(country_id){
		   /* console.log("here");
		  console.log(country_id);*/
		 if (country_id != '') {         	  
          var select1 = $('#city_name');          
          $.ajax({
            url: '<?php echo base_url(); ?>/hotels/get_city_name/'+country_id,
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
            url: '<?php echo base_url(); ?>/hotels/get_location_name/' + city_id,
            success: function (data, textStatus, jqXHR) {                                   
              location_select.html('');
              location_select.html(data);
              location_select.trigger("chosen:updated");  
          	}
           });         
          }		
		 }
		
	</script>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false&key=AIzaSyAiR9CLZshY_vQpB7z5M7nIGCg16gfo2E8"></script>
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
		}
      google.maps.event.addDomListener(window, 'load', initialize);

      function getmap(){	 		 	
	 	var edValue = document.getElementById("lat");
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