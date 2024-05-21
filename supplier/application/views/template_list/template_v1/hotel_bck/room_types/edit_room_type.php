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
 .spruplad{margin-top:10px;}
</style>	

<div class="bodyContent">
	<div class="panel panel-default clearfix">
		<!-- PANEL WRAP START -->
		<div class="panel-heading">
			<!-- PANEL HEAD START -->
			<div class="panel-title">
				<i class="fa fa-credit-card"></i> Room Management
			</div>
		</div>
		<!-- PANEL HEAD START -->
		
			<!-- PANEL BODY START -->
			<div class="panel-body">
				<form method="post" id="room_type" name="room_type" action="<?php 
							if(isset($hotel_id)){
							  	echo site_url()."/hotels/update_room_type/".base64_encode(json_encode($room_type_id_from))."/".$hotel_id;	
							  }
							  else{
							   echo site_url()."/hotels/update_room_type/".base64_encode(json_encode($room_type_id_from));
							  } 
							?>" 
						 class="form-wizard form-horizontal form-groups-bordered validate" enctype= "multipart/form-data">	
					<fieldset form="user_edit">
				<legend class="form_legend">Edit Room</legend>
				<?php if($supplier_rights == 1) { ?>
					<input type="hidden" name="supplier_rights" id="supplier_rights" value="<?php echo $supplier_rights; ?>" />
				<?php } else { ?>
					<input type="hidden" name="supplier_rights" id="supplier_rights" value="" />
				<?php } ?>	
								<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">Hotel</label>									
									<div class="col-sm-5">
									<input type="hidden" id="hotel_details_id" name="hotel_details_id" value="<?=$hotel_id?>">
										 <select  disabled="disabled" onChange="select_room(this.value);" >
											<?php foreach ($hotels_list as $hotel){ ?>
												<option value="<?php echo $hotel->hotel_details_id; ?>"
												 <?php  if(isset($hotel_details_id)){ if($hotel_details_id == $hotel->hotel_details_id) { echo "selected"; } } elseif($hotel_id_rec[0]->hotel_details_id == $hotel->hotel_details_id ){ echo "selected"; } ?> data-iconurl=""><?php echo $hotel->hotel_name; ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
							<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">Room Type Name</label>									
									<div class="col-sm-5">
										<select id="room_type_name" name="room_type_name" class="sasaselectboxit" placeholder="Room Type Name" data-validate="required" data-message-required="Please enter the Room Type Name">
										<option value="">----Select Room Type----</option>
										<?php foreach ($room_types as $type){ ?>
												<option value="<?php echo $type->room_type_name; ?>" data-iconurl="" <?php if(isset($room_type_name) && $room_type_name == $type->room_type_name){ echo "selected"; }elseif(isset($room_types_list[0]->room_type_name) && $room_types_list[0]->room_type_name == $type->room_type_name) { echo "selected"; } ?> ><?php echo $type->room_type_name; ?></option>
											<?php } ?>
										</select>
										<!-- <input type="text" class="form-control" id="field-1" name="room_type_name" value="<?php if(isset($room_type_name)){ echo $room_type_name; } elseif(isset($room_types_list[0]->room_type_name)) { echo $room_types_list[0]->room_type_name; } ?>" data-validate="required" data-message-required="Please enter the Room Type Name"> -->
										<?php echo form_error('room_type_name',  '<span for="field-1" class="validate-has-error">', '</span>'); ?>
									</div>
								</div>			
								<div class="form-group">
								  <label for="field-1" class="col-sm-3 control-label">Room Description</label>
									<div class="col-sm-8">
								     	<textarea class="form-control " name="room_description" placeholder="Room Description" data-message-required="Please enter the Room Description"><?php echo $room_types_list[0]->room_description ?></textarea>								     									  								     	 
								     	
									</div>																		
								</div>	
								<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">Number of Rooms</label>																 				
											<div class="col-md-5">
												<input readonly name="rooms_tot_units" type="text" maxlength="2" size="2" class="form-control" value="<?php if(isset($rooms_tot_units)){ echo $rooms_tot_units; } elseif(isset($room_types_list[0]->no_of_room)) { echo $room_types_list[0]->no_of_room; } ?>" placeholder="No of Room" data-validate="number,required" data-message-required="Please enter the Number of Room" data-rule-number='true'/>
											    <?php echo form_error('rooms_tot_units',  '<span for="field-1" class="validate-has-error">', '</span>'); ?>
											</div>
								</div>

								<!-- <div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">Number of Rooms Booked</label>																 				
											<div class="col-md-5">
												<input name="rooms_tot_units_booked" type="text" maxlength="2" size="2" class="form-control" value="<?php if(isset($rooms_tot_units_booked)){ echo $rooms_tot_units_booked; } elseif(isset($room_types_list[0]->no_of_room)) { echo $room_types_booked[0]->no_of_room_booked; } ?>" placeholder="No of Rooms Booked" data-validate="number" data-message-required="Please enter the Number of Rooms" data-rule-number='true'/>
											    <?php echo form_error('rooms_tot_units_booked',  '<span for="field-1" class="validate-has-error">', '</span>'); ?>
											</div>
								</div>	 -->		
					
								<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">Adult Capacity  Per Room</label>									
									<div class="col-sm-5">
										<input type="text" class="form-control" id="adult_val" name="adult" value="<?php if(isset($adult)){ echo $adult; } elseif(isset($room_types_list[0]->adult)) { echo $room_types_list[0]->adult; } ?>" placeholder="Adult Capacity  Per Room" data-validate="required" data-message-required="Please enter the Adult Capacity">
										<?php echo form_error('adult',  '<span for="field-1" class="validate-has-error">', '</span>'); ?>
									</div>
								</div>
								<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">Child Capacity  Per Room</label>									
									<div class="col-sm-5">
										<input type="text" class="form-control" id="child_val" name="child" value="<?php if(isset($child)){ echo $child; } elseif(isset($room_types_list[0]->child)) { echo $room_types_list[0]->child; } ?>" placeholder="Child Capacity  Per Room" data-validate="required" data-message-required="Please enter the Child Capacity">
										<?php echo form_error('child',  '<span for="field-1" class="validate-has-error">', '</span>'); ?>
									</div>
								</div>
								<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">Max Capacity  Per Room</label>									
									<div class="col-sm-5">
										<input type="text" class="form-control" id="max_val" name="max_pax" maxlength="3" value="<?php if(isset($max_pax)){ echo $max_pax; } elseif(isset($room_types_list[0]->max_pax)) { echo $room_types_list[0]->max_pax; } ?>" placeholder="Max Capacity  Per Room" data-validate="required" data-message-required="Please enter the Max Capacity" data-rule-number='true'>
										<?php echo form_error('max_pax',  '<span for="field-1" class="validate-has-error">', '</span>'); ?>
									</div>
								</div>

								<!-- <div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">Image Url</label>									
									<div class="col-sm-5">
										<textarea class="form-control" name="image_url" placeholder="Image Url" data-message-required="Please enter the Image Url"><?php if(isset($image_url)) { echo $image_url; } elseif(isset($room_types_list[0]->room_url_image)) { echo $room_types_list[0]->room_url_image; } ?></textarea>								     									  																														
									</div>
									<div class="col-sm-3">
										<label for="field-1" class="col-sm-5 control-label" style = "color: red" >Use # for Multiple url separation</label>									
									</div>
								</div> -->

								<?php //echo $room_types_list[0]->extra_bed; ?>

								<div class="form-group">
									<label class="col-sm-3 control-label">Extra Bed</label>									
									<div class="col-sm-5">
										<div id="label-switch" class="make-switch" data-on-label="NotAvailable" data-off-label="Available" style="width:55%">
											<input type="checkbox" name="extra_bed" value="<?php if(isset($room_types_list[0]->extra_bed)) { echo $room_types_list[0]->extra_bed; } ?>" id="extra_bed" <?php if(isset($extra_bed)) { if($extra_bed == "NotAvailable"){ echo "checked"; }} elseif(isset($room_types_list[0]->extra_bed)){ if($room_types_list[0]->extra_bed == "NotAvailable"){ echo "checked"; } }?>>
										</div>
									</div>
								</div>
								
								<div class="form-group" style="display:none;" id="extra_bed_details">
									<label for="field-1" class="col-sm-3 control-label">Number of Beds</label>									
									<div class="col-sm-5">
										<input type="text" class="form-control" maxlength="2" id="extra_bed_count" name="extra_bed_count"  placeholder="Number of Beds" data-validate="required" data-message-required="Please enter the Number of Beds" data-rule-number='true' value="<?php if(isset($extra_bed_count)){ echo $extra_bed_count; } elseif(isset($room_types_list[0]->extra_bed_count)) { echo $room_types_list[0]->extra_bed_count;} ?>">
										<?php echo form_error('extra_bed_count',  '<span for="field-1" class="validate-has-error">', '</span>'); ?>
									</div>
								</div>					
								<!--			
								<div class="form-group">
									<label class="col-sm-3 control-label">Room Type Status</label>									
									<div class="col-sm-5">
										<div id="label-switch" class="make-switch" data-on-label="Active" data-off-label="InActive">
											<input type="checkbox" name="status" value="<?php echo $room_types_list[0]->status; ?>" id="status" <?php if(isset($room_types_list[0]->status)) { if($room_types_list[0]->status == "ACTIVE"){ echo "checked"; } } ?>>
										</div>
									</div>
								</div>
								-->	
								
							   	<?php
							   	 $hotel_images = "";
							   	 if(isset($room_types_list[0]->room_uploaded_image)){
							   	   $hotel_images = explode(',', $room_types_list[0]->room_uploaded_image);  
							   	 }  
							   	 if(isset($hotel_image_old)){							   	 
							   	 	$hotel_images = explode(",", $hotel_image_old);
							   	 }
							   	?>
								
								<div class="form-group">
									<label for="field-3" class="col-sm-3 control-label">Upload Hotel Images</label>									
									<div class="col-sm-5">
										<?php $loop = 1; foreach($hotel_images as $hotel_image){ ?>
											<!-- <div class="fileinput-new thumbnail" data-trigger="fileinput"> -->
												<?php if( $hotel_image == ''){ ?>
													<img style = "width:80px; heigth:60px" src="<?php echo base_url()."assets/images/logo.png"; ?>" alt="Airline Logo">
												<?php }else{ ?>
												  <div id="img_<?php echo $loop; ?>" class="fileinput-new thumbnail" data-trigger="fileinput" >
												  <?php //echo $hotel_image; ?>
													<img  style="width:80px; height:60px" src="<?php echo base_url(); ?>uploads/room/<?php echo $hotel_image; ?>" alt="Hotel Image">
						                            <a href="javascript:void(0)" class="remove_field" onclick="remove_image('<?php echo base64_encode(json_encode($room_type_id_from)); ?>','<?php echo base64_encode(json_encode($hotel_image)); ?>','<?php echo $loop; ?>');">Remove</a>
						                          </div>
												<?php $loop++; } ?>
											<!-- </div>-->
											<?php } ?>
									</div>
									<div class="col-sm-5">
									    <label for="field-5" class="col-sm-7 control-label"> </label>									
										<div class="fileinput fileinput-new" data-provides="fileinput">											
											<div class="fileinput-preview fileinput-exists thumbnail"></div>
											<div class="spruplad">
												<span class="btn btn-white btn-file">
													<span class="fileinput-new">Select image</span>
													<span class="fileinput-exists">Change</span>
													<input name="hotel_image[]" type="file" multiple />
													<input type="hidden" value="<?php if(isset($hotel_image_old)){ echo $hotel_image_old; } elseif(isset($room_types_list[0]->room_uploaded_image)){ echo $room_types_list[0]->room_uploaded_image; } ?>" name="hotel_image_old">
												</span>
												<a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput">Remove</a>
											</div>											
										</div>										
									</div>
								</div>
								<?php  if(isset($room_types_list[0]->room_amenities)){ $sel_ammenities = explode(",",$room_types_list[0]->room_amenities); } ?>
								<div class="form-group">							  	   	
									<label for="field-1" class="col-sm-3 control-label">Amenities</label>									
									<div class="col-sm-5">
										 <select id="ammenities" name="sel_ammenities[]" class="sasaselect2" multiple>										 
											<?php 
										//	$ammenities = $this->hotels_model->get_ammenities_list();
											$ammenities = $this->hotels_model->get_active_room_ammenities_list();
										//	debug($ammenities);exit;

											for($a=0; $a<count($ammenities); $a++){ ?>
												<option value="<?php echo $ammenities[$a]->hotel_amenities_id; ?>" data-iconurl="" 
												<?php if(isset($sel_ammenities)){ if(count($sel_ammenities) > 0){
													if(in_array($ammenities[$a]->hotel_amenities_id,$sel_ammenities)){
														echo "selected";
													}
												} }
												?>
												>
												<?php echo ucfirst($ammenities[$a]->amenities_name); ?></option>
											<?php } ?>
										</select>
									</div>
								</div>	
								
							  	<div class="form-group hide">
								  <label for="field-1" class="col-sm-3 control-label">Description</label>
									<div class="col-sm-8">
								     	<textarea class="form-control " name="room_info" placeholder="Room Info" data-message-required="Please enter the Room Info"><?php if(isset($room_info)){ echo $room_info; } elseif(isset($room_types_list[0]->room_info)) { echo $room_types_list[0]->room_info; } ?></textarea>								     									  
									</div>									
									   	<label for="field-1" class="col-sm-8 control-label"><?php echo form_error('room_info',  '<span class="tab_error_color">', '</span>'); ?></label>
								</div>

								<div class="form-group">
								  <label for="field-1" class="col-sm-3 control-label">Room Policy </label>
									<div class="col-sm-8">
								   		<textarea class="form-control " id="cancellation_policy" name="cancellation_policy" placeholder="Room Info" data-message-required="Please enter the Cancellation Info"><?php if(isset($cancellation_policy)){ echo $cancellation_policy; } elseif(isset($room_types_list[0]->cancellation_policy)) { echo $room_types_list[0]->cancellation_policy; }?></textarea>								   		
									</div>
									<label for="field-1" class="col-sm-8 control-label"><?php echo form_error('cancellation_policy',  '<span for="field-1" class="tab_error_color">', '</span>'); ?> </label>
								</div>

								<!--<div class="form-group">								
									<label for="field-1" class="col-sm-10 control-label">Extras : Exclusive (* Tick checkbox if these Exclusive are added in room price itself)</label>
									<label for="field-1" class="col-sm-12 control-label tab_error_color"><p>* If the hotel have the below ticked(Available) items, please add the price for particular items other wise take out the tick for Not Available.</p></label>											
									<label for="field-1" class="col-sm-5 control-label tab_error_color"><p>* Child price is half of the actual price.</p></label>											
								</div>	-->
								<!-- <div class="form-group">								
								  <label for="field-1" class="col-sm-3 control-label">Breakfast (Available/Not Available)</label>
								  <div class="col-sm-1">
									<div class="checkbox checkbox-replace color-green">
									 <label class="cb-wrapper">
									  <input type="checkbox" id="chk-brekfast" name="chk_breakfast" <?php if(isset($chk_breakfast)){ if($chk_breakfast != "" ) { echo "checked"; } } elseif(isset($room_types_list[0])){ if($room_types_list[0]->breakfast_price_flag == 1){ echo "checked"; }} ?> >
									  <div class="checked"></div>
									 </label>
								    </div>
								  </div>  
								  <div id="breakfast">
								    <label for="field-1" class="col-sm-2 control-label">Breakfast Price</label>								  
								  <div class="col-sm-2">  
									<input type="text" name="break_fast_p" data-rule-number="true" class="form-control" maxlength="5" value="<?php if(isset($break_fast_p)){ echo $break_fast_p;  } else {  echo $room_types_list[0]->break_fast_price; } ?>" />									
									<?php echo form_error('break_fast_p',  '<span class="tab_error_color">', '</span>'); ?>
								  </div>
								 </div>  							  
								</div>

								<div class="form-group">								
								  <label for="field-1" class="col-sm-3 control-label">Half Board (Available/Not Available)</label>
								  <div class="col-sm-1">								    
									<div class="checkbox checkbox-replace color-green">
									 <label class="cb-wrapper">
									 <input type="checkbox" onclick="get_half(this)" id="chk_half" name="chk_half" <?php if(isset($chk_half)){ if($chk_half != "") { echo "checked"; } } elseif(isset($room_types_list[0])){ if($room_types_list[0]->half_board_flag == 1){ echo "checked"; }}  ?> >
									 <div class="checked"></div></label>																
								    </div>
								  </div>  
								  <div id="halfboard">
								    <label for="field-1" class="col-sm-2 control-label">Half Board Price</label>								  
								  <div class="col-sm-2">  
									<input type="text" name="half_board_p" data-rule-number="true" class="form-control" value="<?php if(isset($half_board_p)){ echo $half_board_p;  } else {  echo $room_types_list[0]->half_board_price; } ?>" maxlength="5" />									
									<?php echo form_error('half_board_p',  '<span class="tab_error_color">', '</span>'); ?>
								  </div>
								 </div>  							  
								</div>
							  
							    <div class="form-group">								
								  <label for="field-1" class="col-sm-3 control-label">Full Board (Available/Not Available)</label>
								  <div class="col-sm-1">
									<div class="checkbox checkbox-replace color-green">
									 <label class="cb-wrapper">
 									 <input type="checkbox" id="chk_full" name="chk_full" onclick="get_full(this)" name="chk_full" <?php if(isset($chk_full)){ if($chk_full != "") { echo "checked"; } } elseif(isset($room_types_list[0])){ if($room_types_list[0]->full_board_flag == 1){ echo "checked"; }} ?> >
 									 <div class="checked"></div></label>
								    </div>
								  </div>  
								  <div id="fullboard">
								    <label for="field-1" class="col-sm-2 control-label">Full Board Price</label>								  
								  <div class="col-sm-2">  
									<input type="text" name="full_board_p" data-rule-number="true" class="form-control" value="<?php if(isset($full_board_p)){ echo $full_board_p;  } else {  echo $room_types_list[0]->full_board_price; }?>" maxlength="5" />
									<?php echo form_error('full_board_p',  '<span class="tab_error_color">', '</span>'); ?>
								  </div>
								 </div>  							  
								</div>		

								<div class="form-group">								
								  <label for="field-1" class="col-sm-3 control-label">Dinner (Available/Not Available)</label>
								  <div class="col-sm-1">
									<div class="checkbox checkbox-replace color-green">
									 <label class="cb-wrapper">
 									 <input type="checkbox" id="chk_dinner" name="chk_dinner" onclick="get_dinner(this)" name="chk_dinner" <?php if(isset($chk_dinner)){ if($chk_dinner != "") { echo "checked"; } } elseif(isset($room_types_list[0])){ if($room_types_list[0]->dinner_price_flag == 1){ echo "checked"; }}  ?> >
 									 <div class="checked"></div></label>
								    </div>
								  </div>  
								  <div id="dinner">
								    <label for="field-1" class="col-sm-2 control-label">Dinner Price</label>								  
								  <div class="col-sm-2">  
									<input type="text" name="dinner_p" data-rule-number="true" class="form-control" value="<?php if(isset($dinner_p)){ echo $dinner_p; }elseif(isset($room_types_list[0]->dinner_price)){echo $room_types_list[0]->dinner_price; } ?>" maxlength="5" />
									<?php echo form_error('dinner_p',  '<span class="tab_error_color">', '</span>'); ?>
								  </div>
								 </div>  							  
								</div>	

								<div class="form-group">								
								  <label for="field-1" class="col-sm-3 control-label">Lunch (Available/Not Available)</label>
								  <div class="col-sm-1">
									<div class="checkbox checkbox-replace color-green">
									 <label class="cb-wrapper">
 									 <input type="checkbox" id="chk_lunch" name="chk_lunch" onclick="get_lunch(this)" name="chk_full" <?php if(isset($chk_lunch)){ if($chk_lunch != "") { echo "checked"; } } elseif(isset($room_types_list[0])){ if($room_types_list[0]->lucnh_price_flag == 1){ echo "checked"; }}  ?> >
 									 <div class="checked"></div></label>
								    </div>
								  </div>  
								  <div id="lunch">
								    <label for="field-1" class="col-sm-2 control-label">Lunch Price</label>								  
								  <div class="col-sm-2">  
									<input type="text" name="lunch_p" data-rule-number="true" class="form-control" value="<?php if(isset($lunch_p)){ echo $lunch_p;  } elseif(isset($room_types_list[0]->lunch_price)){echo $room_types_list[0]->lunch_price; }  ?>" maxlength="5" />
									<?php echo form_error('lunch_p',  '<span class="tab_error_color">', '</span>'); ?>
								  </div>
								 </div>  							  
								</div>							

								<div class="form-group">								
								  <label for="field-1" class="col-sm-3 control-label">Meals (Available/Not Available)</label>								  
								  <div class="col-sm-1">
									<div class="checkbox checkbox-replace color-green">
									 <label class="cb-wrapper">
									 <input type="checkbox" onclick="get_meals(this)" id="oth_meals_flag" name="oth_meals_flag" <?php if(isset($oth_meals_flag)){ if($oth_meals_flag != "") { echo "checked"; } } elseif(isset($additional_meals[0])){ if($additional_meals[0]->oth_meals_flag == 1){ echo "checked"; }}  ?> >
									 <div class="checked"></div>
								    </div>
								  </div>  

								  <div id="meals">
								    <label for="field-1" class="col-sm-2 control-label">Meals Name</label>								  
								  <div class="col-sm-2">  
									<input type="text" class="form-control"   name="mealtype_name[]" value="<?php if(isset($mealtype_name[0])){ echo $mealtype_name[0];  } else {  echo $additional_meals[0]->meal_type_name; } ?>"  maxlength="150" value="" id="mealtype_name" />
									<?php echo form_error('mealtype_name',  '<span class="tab_error_color">', '</span>'); ?>
								  </div>
								    <label for="field-1" class="col-sm-1 control-label">Meals Price</label>								  
								  <div class="col-sm-2">  
									<input type="text" class="form-control" data-rule-number="true"  name="mealtype_price[]" value="<?php if(isset($mealtype_price[0])){ echo $mealtype_price[0];  } else {  echo $additional_meals[0]->mealtype_price; } ?>" maxlength="5" value="" id="mealtype_price" />
									<?php echo form_error('mealtype_price',  '<span class="tab_error_color">', '</span>'); ?>
								  </div> 	
	  							  <div class="col-sm-1">
    								<input type="hidden" class="form-control" readonly name="meal_type_id[]" id="meal_type_id" value="<?php if( isset($meal_type_id[0])){ echo $meal_type_id[0]; } elseif(isset($additional_meals[0]->meal_type_id)){ echo $additional_meals[0]->meal_type_id; } ?>">										
								   </div>	
								<div class="form-group" id="btn">							  	 																	   								  

								 <div class="col-md-10"> 								     		
								  <div id="extra_meals"></div>		 
								 </div> 
								 </div> 

							  	   <div class="form-group" id="btn">							  	 
								     <div class="col-md-10"> 								     		
											<div class="col-md-1"><input type="hidden" id="rows_cnt" value="1"/><button type="button" class="btn btn-success" onclick="addMoreRooms1();">Add</button></div>
											<div class="col-md-2"><button type="button" class="btn btn-success" onclick="removeLastRoom1(this);">Remove Last</button></div>
									</div>		
							  	   </div>

								 </div>  	

								</div>	 -->	

								<div class="form-group">
									<label class="col-sm-3 control-label">&nbsp;</label>									
									<div class="col-sm-5">
										<button type="submit" class="btn btn-success">Update Room</button>
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
  
  
 <script src="<?php echo base_url(); ?>assets/js/daterangepicker/daterangepicker.js"></script>
	<script>
	 function addMoreRooms1() {
			$("#extra_meals").css({'display':'inherit'});
			var id = $('#rows_cnt').val();
			
	    	$("#extra_meals").append('<div class="form-group style="widht:80%;">'+
	    						  '<label for="field-1" class="col-sm-3 control-label">Meals Name</label>'+								  
								  '<div class="col-sm-3">'+  
								  '<input type="text" class="form-control" name="mealtype_name[]" value=""  maxlength="150" value="" id="mealtype_name'+id+'" />'+								  
								  '</div>'+
								  '<label for="field-1" class="col-sm-2 control-label">Meals Price</label>'+								  
								  '<div class="col-sm-3">'+  
								  '<input type="text" class="form-control" name="mealtype_price[]" value="" maxlength="5" value="" id="mealtype_price'+id+'" />'+
								  '</div>'+
								  '<div class="col-sm-1 ">'+							
								  '<input type="hidden" readonly class="form-control" name="meal_type_id[]" id="meal_type_id'+id+'" value="">'+
								  '</div>'+
								  '</div>');
								  
			id = parseInt(id)+1;
			$('#rows_cnt').val(id);																

		}
		function removeLastRoom1(v){
			var id = $('#rows_cnt').val();
			var id1= parseInt(id)-1;
			var meal_type_id = $('#meal_type_id'+id1).val();					
			if(meal_type_id > 1){
				var conval = confirm("Are you want to delete " +$('#mealtype_name'+id1).val()+"?");
				if(conval == true){
					$.ajax({
            			url: '<?php echo base_url(); ?>hotel/delete_meal_type/' + meal_type_id,
            			success: function (data, textStatus, jqXHR) {                     	            	            	              			
            				alert("Deleted Successfully");            				
            				$('#extra_meals .form-group').last().remove();
							if(id <= 1) {
								$("#extra_meals").css({'display':'none'});
							}
							id = parseInt(id)-1;
							$('#rows_cnt').val(id);
						}	
           			});   	
           		}	
			}
			else{
				$('#extra_meals .form-group').last().remove();
				if(id <= 1) {
					$("#extra_meals").css({'display':'none'});
				}
				id = parseInt(id)-1;
				$('#rows_cnt').val(id);
		   }	
		}	


	  document.getElementById('chk-brekfast').onclick = function() {    

    	if ( this.checked ) {          	        	    
	  		$("#breakfast").show();
        } else {    		
     		$("#breakfast").hide();
        }
  	  };

  	  function get_half(value){
  	  	if(value.checked){  	  		
  	  		$("#halfboard").show();
  	  	}
  	  	else{
  	  		$("#halfboard").hide();
  	  	}
  	  }

  	  function get_full(value){
  	  	if(value.checked){  	  		
  	  		$("#fullboard").show();
  	  	}
  	  	else{
  	  		$("#fullboard").hide();
  	  	}
  	  }

  	  function get_lunch(value){
  	  	if(value.checked){  	  		
  	  		$("#lunch").show();
  	  	}
  	  	else{
  	  		$("#lunch").hide();
  	  	}
  	  }

  	    function get_dinner(value){
  	  	if(value.checked){  	  		
  	  		$("#dinner").show();
  	  	}
  	  	else{
  	  		$("#dinner").hide();
  	  	}
  	  }

  	  function get_meals(value){
  	  	if(value.checked){  	  		
  	  		$("#meals").show();
  	  	}
  	  	else{
  	  		$("#meals").hide();
  	  	}
  	  }


		$(function(){

			<?php 	  	
	  		 if(isset($additional_meals)) {
	  	 		$count = sizeof($additional_meals); 	  	 	
	  	 		if($count >= 1){
		     		for($cp = 1; $cp < $count; $cp++) {  	?>		     		      
		      			addMoreRooms1(null);			    		      
		      			$('#mealtype_name'+<?php echo $cp; ?>).val("<?php echo $additional_meals[$cp]->meal_type_name; ?>");		      			
      		  			$('#mealtype_price'+<?php echo $cp; ?>).val("<?php echo $additional_meals[$cp]->mealtype_price; ?>");			  
      		  			$('#meal_type_id'+<?php echo $cp; ?>).val("<?php echo $additional_meals[$cp]->meal_type_id; ?>");			  
			  			//alert("<?php echo $additional_meals[$cp]->mealstype_price; ?>");
			<?php   }//for
	        	}//if
	       	  }//if  
	       	  elseif(isset($mealtype_name)){
	       	  	$count = sizeof($mealtype_name); 	  	 	
	  	 		if($count >= 1){
		     		for($cp = 1; $cp < $count; $cp++) {  	?>		     		      
						addMoreRooms1(null);			    		      
		      			$('#mealtype_name'+<?php echo $cp; ?>).val("<?php echo $mealtype_name[$cp]; ?>");		      			
      		  			$('#mealtype_price'+<?php echo $cp; ?>).val("<?php echo $mealtype_price[$cp]; ?>");			  
      		  			$('#meal_type_id'+<?php echo $cp; ?>).val("<?php echo $meal_type_id[$cp]; ?>");			  
			  			//alert("<?php echo $additional_meals[$cp]->mealstype_price; ?>");			  		
			<?php   }//for
	        	}//if
	       	  }//if  
	       	  ?>
			<?php if(isset($extra_bed)){
				if($extra_bed == "NotAvailable"){ ?>
					$('#extra_bed').val('NotAvailable');
					$('#extra_bed_details').hide();
			<?php } else { ?>
				    $('#extra_bed').val('Available');
					$('#extra_bed_details').show();
			 <?php } } 	?>


			<?php if(isset($chk_breakfast)){
				if($chk_breakfast != ""){ ?>					
					$("#breakfast").show();					
			<?php } else { ?>					
					$("#breakfast").hide();								
			<?php } } elseif(isset($room_types_list[0])){
				if($room_types_list[0]->breakfast_price_flag == 1){ ?>					
					$("#breakfast").show();					
			<?php } else { ?>					
					$("#breakfast").hide();					
			<?php }  } else{  	?>					
				$("#breakfast").hide();					
			<?php } ?>	

			<?php if(isset($chk_half)){
				if($chk_half != ""){ ?>					
					$("#halfboard").show();
			<?php } else { ?>					
					$("#halfboard").hide();
			<?php } } elseif(isset($room_types_list[0])){
				if($room_types_list[0]->half_board_flag == 1){ ?>				
					$("#halfboard").show();
			<?php } else { ?>					
					$("#halfboard").hide();
			<?php } } else{	?>	
				$("#halfboard").hide();					
			<?php } ?>	

			<?php if(isset($chk_full)){
				if($chk_full != ""){ ?>					
					$("#fullboard").show();
			<?php } else { ?>					
					$("#fullboard").hide();
			<?php } } elseif(isset($room_types_list[0])){
				if($room_types_list[0]->full_board_flag == 1){ ?>				
					$("#fullboard").show();
			<?php } else { ?>					
					$("#fullboard").hide();
			<?php } } 	else { ?>	
				$("#fullboard").hide();					
			<?php } ?>

			<?php if(isset($oth_meals_flag)){
				if($oth_meals_flag != ""){ ?>					
					$("#meals").show();
			<?php } else { ?>					
					$("#meals").hide();
			<?php } } 	
			  elseif(isset($additional_meals[0])){
				if($additional_meals[0]->oth_meals_flag == 1){ ?>				
					$("#meals").show();
			<?php } else { ?>					
					$("#meals").hide();
			<?php } } else{	?>	
				$("#meals").hide();					
			<?php } ?>

			<?php if(isset($chk_dinner)){
				if($chk_dinner != ""){ ?>					
					$("#dinner").show();
			<?php } else { ?>					
					$("#dinner").hide();
			<?php } } 	elseif(isset($room_types_list[0])){
				if($room_types_list[0]->dinner_price_flag == 1){ ?>				
					$("#dinner").show();
			<?php } else { ?>					
					$("#dinner").hide();
			<?php } } 	else { ?>	
				$("#dinner").hide();					
			<?php } ?>


			<?php if(isset($chk_lunch)){
				if($chk_lunch != ""){ ?>					
					$("#lunch").show();
			<?php } else { ?>					
					$("#lunch").hide();
			<?php } } 	elseif(isset($room_types_list[0])){
				if($room_types_list[0]->lucnh_price_flag == 1){ ?>				
					$("#lunch").show();
			<?php } else { ?>					
					$("#lunch").hide();
			<?php } } else{	?>	
				$("#lunch").hide();					
			<?php } ?>	

			<?php if(isset($room_types_list[0]->extra_bed)) { if($room_types_list[0]->extra_bed == "NotAvailable") { ?>
				$('#extra_bed_details').hide();
			<?php } else { ?>	
				$('#extra_bed_details').show();
			<?php } } ?>	
			$('#status').change(function(){
				var current_status = $('#status').val();
				if(current_status == "ACTIVE")
					$('#status').val('INACTIVE');
				else
					$('#status').val('ACTIVE');
			});
			$('#extra_bed').change(function(){
				var extra_bed = $('#extra_bed').val();
				if(extra_bed == "Available"){
					$('#extra_bed').val('NotAvailable');
					$('#extra_bed_details').hide();
				}else{
					$('#extra_bed').val('Available');
					$('#extra_bed_details').show();
				}
			});
			
			var room_type_name = document.getElementById('field-1');
			var adult_val = document.getElementById('adult_val');
			var child_val = document.getElementById('child_val');
			var max_val = document.getElementById('max_val');
			
			$('input#field-1').keyup(function() {
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
				}

				if(room_type_name.value.length < 2 || room_type_name.value.length > 100) {
					    room_type_name.style.border = "1px solid #f52c2c";   
						room_type_name.focus(); 
						return false; 
				}
				
			});	
			
			$('input#adult_val').keyup(function() {
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

				if(adult_val.value.length > 2) {
					    adult_val.style.border = "1px solid #f52c2c";   
						adult_val.focus(); 
						return false; 
				}
				
			});	
			
			$('input#child_val').keyup(function() {
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

				if(child_val.value.length > 2) {
					    child_val.style.border = "1px solid #f52c2c";   
						child_val.focus(); 
						return false; 
				}
				
			});	
			
			$('input#max_val').keyup(function() {
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

				if(max_val.value.length > 3) {
					    max_val.style.border = "1px solid #f52c2c";   
						max_val.focus(); 
						return false; 
				}
				
			});	
			
			$('#room_type').submit(function() {
				var room_type_name = document.getElementById('field-1');
				var adult_val = document.getElementById('adult_val');
				var child_val = document.getElementById('child_val');
				var max_val = document.getElementById('max_val');
				var filter = /^[a-zA-Z 0-9!@#$%^&*_() - +=:;'",. ]*$/;
				var number_filter  = /^[0-9]*$/;
				
				if(room_type_name.value != '')
				{
					if(!(room_type_name.value.match(filter)))
					{
						room_type_name.style.border = "1px solid #f52c2c";   
						room_type_name.focus(); 
						return false; 
					}
				}
				else
				{
					room_type_name.style.border = "1px solid #f52c2c";   
					room_type_name.focus(); 
					return false; 
				}

				if(room_type_name.value.length < 2 || room_type_name.value.length > 100) {
					    room_type_name.style.border = "1px solid #f52c2c";   
						room_type_name.focus(); 
						return false; 
				}
				
				
				if(adult_val.value != '')
				{
					if(!(adult_val.value.match(number_filter)))
					{
						adult_val.style.border = "1px solid #f52c2c";   
						adult_val.focus(); 
						return false; 
					}
				}
				else
				{
					adult_val.style.border = "1px solid #f52c2c";   
					adult_val.focus(); 
					return false; 
				}

				if(adult_val.value.length > 2) {
					    adult_val.style.border = "1px solid #f52c2c";   
						adult_val.focus(); 
						return false; 
				}
				
				if(child_val.value != '')
				{
					if(!(child_val.value.match(number_filter)))
					{
						child_val.style.border = "1px solid #f52c2c";   
						child_val.focus(); 
						return false; 
					}
				}
				else
				{
					child_val.style.border = "1px solid #f52c2c";   
					child_val.focus(); 
					return false; 
				}

				if(child_val.value.length > 2) {
					    child_val.style.border = "1px solid #f52c2c";   
						child_val.focus(); 
						return false; 
				}
				
				if(max_val.value != '')
				{
					if(!(max_val.value.match(number_filter)))
					{
						max_val.style.border = "1px solid #f52c2c";   
						max_val.focus(); 
						return false; 
					}
				}
				else
				{
					max_val.style.border = "1px solid #f52c2c";   
					max_val.focus(); 
					return false; 
				}

				if(max_val.value.length > 3) {
					    max_val.style.border = "1px solid #f52c2c";   
						max_val.focus(); 
						return false; 
				}
			
				});
				
		});
		function remove_image(room_type_id,image_name,id){
	  	//alert(image_name);
	  	if(room_type_id != '' && image_name != '') {
                $.ajax({
                            url: '<?php echo site_url(); ?>/hotel/unlink_room_image/' + room_type_id + '/' + image_name,
                            success: function (data, textStatus, jqXHR) {                            
                                //alert(data);   
                                  $("#img_" + id).remove();                                                                       		   			
                        	}
                         });
                         }
	  }

	</script>
	<script>
		$(document).ready(function(){
			$("#ammenities").select2();
		});
	</script>