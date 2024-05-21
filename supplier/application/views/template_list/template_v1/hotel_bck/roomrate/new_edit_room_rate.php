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
<?php 

$get_url='';
if(isset($GET) && !empty($GET))
{
	$get_url = '/'.base64_encode($GET);
}
 ?>
<div class="bodyContent">
	<div class="panel panel-default clearfix">
		<!-- PANEL WRAP START -->
		<div class="panel-heading">
			<!-- PANEL HEAD START -->
			<div class="panel-title">
				<i class="fa fa-credit-card"></i> Room Rate Management
			</div>
		</div>
		<!-- PANEL HEAD START -->
		
			<!-- PANEL BODY START -->
			<div class="panel-body">
				<form id="room_rate_details11" method="post" action="<?php echo site_url()."/roomrate/update_room_rate/".base64_encode(json_encode($room_rate_id)); ?>" class="form-horizontal form-groups-bordered validate" enctype= "multipart/form-data">	
				
				 <input type="hidden" class="form-control" name="hotel_room_rate_id"  id="hotel_room_rate_id"  value="<?php echo $room_rate_id_new; ?>"  />
				
					<input type="hidden" class="form-control" id="country_list_nationality_id" maxlength="7" name="country_list_nationality_id" value="<?php if(isset($country_list_nationality_id)) { echo $country_list_nationality_id;} ?>"  data-rule-number="true" data-validate="required" data-message-required="Please Enter the Single Room Price" />
					<fieldset form="user_edit">
				<legend class="form_legend">Edit Room Rate</legend>
				<?php if($supplier_rights == 1) { ?>
							<input type="hidden" name="supplier_rights" id="supplier_rights" value="<?php echo $supplier_rights; ?>" />
							<?php } else { ?>
							<input type="hidden" name="supplier_rights" id="supplier_rights" value="" />
							<?php } ?>
							<div class="col-xs-12">
								<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">Hotel</label>									
									<div class="col-sm-5">
										 <select id="hotel_details_id" name="hotel_details_id" onChange="select_room_type(this.value);" class="form-control">
											
											<?php foreach ($hotels_list as $hotel){ 
											    if($hotel_details_id == $hotel->hotel_details_id) 
											    {
											
											?>
												<option value="<?php echo $hotel->hotel_details_id; ?>" 
												<?php if(isset($hotel_details_id))
												{ if($hotel_details_id == $hotel->hotel_details_id) 
												{ echo "selected"; 
												} } ?> data-iconurl="">
												    <?php echo $hotel->hotel_name; ?>
												    </option>
											<?php } } ?>
										</select>
									</div>
									<?php echo form_error('hotel_details_id',  '<span for="field-1" class="tab_error_color">', '</span>'); ?>
								</div>	
									<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">Nationality</label>									
									<div class="col-sm-5">
										 <input type="text" class="form-control"  id="country_name" name="country_name" value="<?php if(isset($country_name)) { echo $country_name;}else{ echo "Default";} ?>" data-message-required="Nationality" readonly/> 
									</div>
									
								</div>		
								<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">Room Type</label>									
									<div class="col-sm-5">
										 <select id="room_details_id" name="room_details_id" onchange="select_season(this.value)" class="form-control">
										 <option value="0">Select Room</option>
										</select>
										<?php echo form_error('room_details_id',  '<span for="field-1" class="tab_error_color">', '</span>'); ?>
									</div>									
								</div>	
								<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">Seasons</label>									
									<div class="col-sm-5">
										 <select id="seasons_details_id" name="seasons_details_id" class="form-control">
										 <option value="0">Select Seasons</option>
										 </select>
										 <?php echo form_error('seasons_details_id',  '<span for="field-1" class="tab_error_color">', '</span>'); ?>
									</div>
								</div>						
													

								<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">Cancellation Policy</label>									
									<div class="col-sm-5">
										 <select id="cancellation_policy" name="cancellation_policy" class="form-control">										 
										 	<option value="1" <?php if(isset($cancellation_policy)){ if($cancellation_policy == '1')   { echo "selected"; } } ?> >Applicable</option>
										 	<option value="0" <?php if(isset($cancellation_policy)){ if($cancellation_policy == '0')   { echo "selected"; } } ?> >Not Applicable</option>										 
										 </select>
										 <?php echo form_error('cancellation_policy',  '<span for="field-1" class="tab_error_color">', '</span>'); ?>
									</div>
								</div>						

								<div class="form-group" style="display:none";>
									<label for="field-1" class="col-sm-3 control-label">Week End Require</label>									
									<div class="col-sm-5">
										 <select id="week_end_select" name="week_end_select" onchange="select_week_end(this.value)" class="form-control">										 
										    <option value="0" <?php if(isset($week_end_select)){ if($week_end_select == '0')  { echo "selected"; } } ?> >No</option>										 
										 	<option value="1" <?php if(isset($week_end_select)){ if($week_end_select == '1')  { echo "selected"; } } ?> >Yes</option>										 	
										 </select>
										 <?php echo form_error('week_end_select',  '<span for="field-1" class="tab_error_color">', '</span>'); ?>
									</div>
								</div>						
								                             
                              
<!--								<div class="form-group">-->
<!--									<label for="field-1" class="col-sm-3 control-label">Date Range</label>									-->
<!--									<div class="col-sm-5">-->
										<!-- <input type="text" class="form-control" name="date_rane_rate" id="date_rane_rate"   data-validate="required" data-message-required="Please Select the Date Range"  /> -->
<!--										 <input type="text" class="form-control" id="date_rane_rate" name="date_rane_rate" value="--><?php //if(isset($date_rane_rate)) { echo $date_rane_rate; } ?><!--" data-validate="required" data-message-required="Please Select the Main Date Range" />-->
										 <!-- <input type="text" class="form-control daterange" id="date_rane_rate" name="date_rane_rate" value="" data-min-date="<?php echo date('m/d/Y');?>" data-validate="required" data-message-required="Please Select the Main Date Range" /> -->
<!--										 --><?php //echo form_error('date_rane_rate',  '<span for="field-1" class="tab_error_color">', '</span>'); ?>
<!--									</div>-->
<!--								</div>-->
						<!--		<div class="form-group">
                                    <label for="field-1" class="col-sm-3 control-label">Currency</label>
                                    <div class="col-sm-5">
                                        <select  data-rule-required='true' name='currency' id="currency" data-rule-required='true' class="form-control" required>
                                            <option value="">Choose Currency</option>
                                            <?php

                                            foreach($currency as $currency_key => $currency_value)
                                            { ?>
                                                <option value="<?php echo $room_rate_list[0]->currency; ?>" <?php if($room_rate_list[0]->currency == $currency_value['country'] ){ echo "selected"; }?> data-iconurl=""><?php echo $currency_value['country']; ?></option>

                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
								</div>-->
								<!--
								<?php 
								  //if(isset($gst)) { echo $gst; }
								?>
								-->
							<!--	<div class="form-group">
									<label class="col-sm-3 control-label">GST</label>		
									<div id="label-switch" class="make-switch col-xs-2 nopad" data-on-label="INCLUSIVE" data-off-label="EXCLUSIVE" style="min-width: 200px;">
										<input type="checkbox" name="gst" value="INCLUSIVE" id="gst" <?php if(isset($gst)) { if($gst == "INCLUSIVE") { echo "checked"; } } else { echo "checked"; } ?>>
									</div>	
								</div>	-->
							<!--	<div class="form-group">
									<label class="col-sm-3 control-label">Service Charges</label>		
									<div id="label-switch" class="make-switch col-xs-2 nopad" data-on-label="INCLUSIVE" data-off-label="EXCLUSIVE" style="min-width: 200px;">
										<input type="checkbox" name="service_charge" value="INCLUSIVE" id="service_charge" <?php if(isset($service_charge)) { if($service_charge == "INCLUSIVE") { echo "checked"; } } else { echo "checked"; } ?> >
									</div>
									<input type="hidden" class="form-control" maxlength="7" value="0" id="adult_price" name="adult_price" data-validate="required" data-message-required="Please Enter the Adult Price" />
								</div>-->
								   <div id="child_group" class="" style="">								   
								   </div>
								  <div class="form-group"> 
								  <div id="child_group1">
								   <?php if($settings[0]->child_group_a != ''){ ?>
										<div class="col-sm-2">
											<label for="field-1" class="col-sm-12 control-label">Child Price/ Person (<?php echo $settings[0]->child_group_a; ?>)</label>									
											<input type="text" class="form-control" maxlength="7" data-rule-number="true" id="child_price_ge_a" value="<?php if(isset($child_price_ge_a)) { echo $child_price_ge_a;} ?>" name="child_price_ge_a" data-message-required="Please Enter the Child Price" />
										</div>
									<?php } ?>
									<?php if($settings[0]->child_group_b != ''){ ?>
										<div class="col-sm-2">
											<label for="field-1" class="col-sm-12 control-label">Child Price/ Person (<?php echo $settings[0]->child_group_b; ?>)</label>									
											 <input type="text" class="form-control" maxlength="7" data-rule-number="true" id="child_price_ge_b" value="<?php if(isset($child_price_ge_b)) { echo $child_price_ge_b; } ?>" name="child_price_ge_b" data-message-required="Please Enter the Child Price" />
										</div>
									<?php } ?>
									<?php if($settings[0]->child_group_c != ''){ ?>
										<div class="col-sm-2">
											<label for="field-1" class="col-sm-12 control-label">Child Price/ Person (<?php echo $settings[0]->child_group_c; ?>)</label>									
											 <input type="text" class="form-control" maxlength="7" data-rule-number="true" id="child_price_ge_c" name="child_price_ge_c" value="<?php if(isset($child_price_ge_c)) { echo $child_price_ge_c;} ?>" data-message-required="Please Enter the Child Price" />
										</div>
									<?php } ?>
									<?php if($settings[0]->child_group_d != ''){ ?>
										<div class="col-sm-2">
											<label for="field-1" class="col-sm-12 control-label">Child Price/ Person (<?php echo $settings[0]->child_group_d; ?>)</label>									
											 <input type="text" class="form-control" maxlength="7" data-rule-number="true" id="child_price_ge_d" name="child_price_ge_d" value="<?php if(isset($child_price_ge_d)) { echo $child_price_ge_d;} ?>"  data-message-required="Please Enter the Child Price" />
										</div>
									<?php } ?>
									<?php if($settings[0]->child_group_e != ''){ ?>
										<div class="col-sm-2">
											<label for="field-1" class="col-sm-12 control-label">Child Price/ Person (<?php echo $settings[0]->child_group_e; ?>)</label>									
											 <input type="text" class="form-control" maxlength="7" data-rule-number="true" id="child_price_ge_e" value="<?php if(isset($child_price_ge_e)) { echo $child_price_ge_e;} ?>" name="child_price_ge_e" data-message-required="Please Enter the Child Price" />
										</div>
									<?php } ?>										
									
								</div>			
							</div>		
								   <div class="form-group" id="extra_bed_price">
								    
									</div>									
								
																			
							<!--	<div class="form-group">									
                                  
                                     <label for="field-2" class="col-sm-3 control-label">Week Day Price</label>									
                                    </div>-->								  							     	
									<div class="form-group">
										<label for="field-2" class="col-sm-3 control-label">Single Adult Price</label>		
										<div class="col-xs-5">						
										<input type="text" class="form-control" id="week_sgl_price" maxlength="7" name="week_sgl_price" value="<?php if(isset($week_sgl_price)) { echo $week_sgl_price;} ?>"  data-rule-number="true" data-validate="required" data-message-required="Please Enter the Single Room Price" />
										<?php echo form_error('week_sgl_price',  '<span for="field-1" class="tab_error_color">', '</span>'); ?>
										</div>
									</div>
									<!-- <div class="form-group">
										<label for="field-2" class="col-sm-3 control-label">Adult Breakfast(Include/Exclude)</label>	
										<div class="col-xs-5">									
										<select name="week_single_adult_bf" id="week_single_adult_bf" class="form-control">
											<option value="1" <?php if(isset($week_single_adult_bf)){ if($week_single_adult_bf == "1"){ echo "selected"; } } ?> >Include</option>
										 	<option value="0" <?php if(isset($week_single_adult_bf)){ if($week_single_adult_bf == "0"){ echo "selected"; } } ?>>Exclude</option>										 
										</select> 	
										</div>
									</div> -->
									<!-- <div class="form-group">
										<label for="field-2" class="col-sm-3 control-label">Child Breakfast(Include/Exclude)</label>		
										<div class="col-xs-5">								
										<select name="week_single_child_bf" id="week_single_child_bf" class="form-control">
											<option value="1" <?php if(isset($week_single_child_bf)){ if($week_single_child_bf == "1"){ echo "selected"; } } ?>>Include</option>
										 	<option value="0" <?php if(isset($week_single_child_bf)){ if($week_single_child_bf == "0"){ echo "selected"; } } ?>>Exclude</option>										 
										</select> 	
										</div>																			
									</div> -->
								  
								  <div class="form-group">									
								 	
										<label for="field-1" class="col-sm-3 control-label">Double Adult Price</label>				<div class="col-xs-5">					
										<input type="text" class="form-control" id="week_dbl_price" value="<?php if(isset($week_dbl_price)) { echo $week_dbl_price; } ?>" maxlength="7" name="week_dbl_price" data-rule-number="true" data-validate="required" data-message-required="Please Enter the Double Room Price"/>
										<?php echo form_error('week_dbl_price',  '<span for="field-1" class="tab_error_color">', '</span>'); ?>
										</div>
									 </div>
									<!-- <div class="form-group">
										<label for="field-2" class="col-sm-3 control-label">Adult Breakfast(include/Exclude)</label>									
										<div class="col-sm-5">
											<select name="week_double_adult_bf" id="week_double_adult_bf" class="form-control">
												<option value="1" <?php if(isset($week_double_adult_bf)){ if($week_double_adult_bf == "1"){ echo "selected"; } } ?> >Include</option>
											 	<option value="0" <?php if(isset($week_double_adult_bf)){ if($week_double_adult_bf == "0"){ echo "selected"; } } ?>>Exclude</option>										 
											</select> 
										</div>	
									</div> -->
									<!-- <div class="form-group">
										<label for="field-2" class="col-sm-3 control-label">Child Breakfast(include/Exclude)</label>									
										<div class="col-sm-5">
											<select name="week_double_child_bf" id="week_double_child_bf" class="form-control">
												<option value="1" <?php if(isset($week_double_child_bf)){ if($week_double_child_bf == "1"){ echo "selected"; } } ?>>Include</option>
											 	<option value="0" <?php if(isset($week_double_child_bf)){ if($week_double_child_bf == "0"){ echo "selected"; } } ?>>Exclude</option>										 
											</select> 	
										</div>
									</div>		 -->									
								   </div>

								   <div class="form-group">									
								 	 <div class="form-group">
										<label for="field-1" class="col-sm-3 control-label">Triple Adult Price</label>			
										<div class="col-xs-5">						
										<input type="text" class="form-control" id="week_trp_price" value="<?php if(isset($week_trp_price)) { echo $week_trp_price; } ?>" maxlength="7" name="week_trp_price" data-rule-number="true" data-validate="required" data-message-required="Please Enter the Triple Room Price" />
										<?php echo form_error('week_trp_price',  '<span for="field-1" class="tab_error_color">', '</span>'); ?>
										</div>
									 </div>
									<!-- <div class="form-group">
										<label for="field-2" class="col-sm-3 control-label">Adult Breakfast(include/Exclude)</label>									
										<div class="col-sm-5">
											<select name="week_trp_adult_bf" id="week_trp_adult_bf" class="form-control">
												<option value="1" <?php if(isset($week_trp_adult_bf)){ if($week_trp_adult_bf == "1"){ echo "selected"; } } ?> >Include</option>
											 	<option value="0" <?php if(isset($week_trp_adult_bf)){ if($week_trp_adult_bf == "0"){ echo "selected"; } } ?>>Exclude</option>										 
											</select> 	
										</div>
									</div> -->
									<!-- <div class="form-group">
										<label for="field-2" class="col-sm-3 control-label">Child Breakfast(include/Exclude)</label>									
										<div class="col-sm-5">
										<select name="week_trp_child_bf" id="week_trp_child_bf" class="form-control">
											<option value="1" <?php if(isset($week_trp_child_bf)){ if($week_trp_child_bf == "1"){ echo "selected"; } } ?>>Include</option>
										 	<option value="0" <?php if(isset($week_trp_child_bf)){ if($week_trp_child_bf == "0"){ echo "selected"; } } ?>>Exclude</option>										 
										</select> 
										</div>	
									</div>	 -->										
								   </div>	

								   <!-- <div class="form-group">									
								 	 <div class="col-sm-2">
										<label for="field-1" class="col-sm-14 control-label">Quad Room Price</label>									
										<input type="text" class="form-control" id="week_quad_price" value="<?php if(isset($week_quad_price)) { echo $week_quad_price; } ?>" maxlength="7" name="week_quad_price" data-rule-number="true" data-validate="required" data-message-required="Please Enter the Quad Room Price"/>
										<?php echo form_error('week_quad_price',  '<span for="field-1" class="tab_error_color">', '</span>'); ?>
									 </div>
									<div class="col-sm-3">
										<label for="field-2" class="col-sm-12 control-label">Adult Breakfast(include/Exclude)</label>									
										<select name="week_quad_adult_bf" id="week_quad_adult_bf" class="form-control">
											<option value="1" <?php if(isset($week_quad_adult_bf)){ if($week_quad_adult_bf == "1"){ echo "selected"; } } ?> >Include</option>
										 	<option value="0" <?php if(isset($week_quad_adult_bf)){ if($week_quad_adult_bf == "0"){ echo "selected"; } } ?>>Exclude</option>										 
										</select> 	
									</div>
									<div class="col-sm-3">
										<label for="field-2" class="col-sm-12 control-label">Child Breakfast(include/Exclude)</label>									
										<select name="week_quad_child_bf" id="week_quad_child_bf" class="form-control">
											<option value="1" <?php if(isset($week_quad_child_bf)){ if($week_quad_child_bf == "1"){ echo "selected"; } } ?>>Include</option>
										 	<option value="0" <?php if(isset($week_quad_child_bf)){ if($week_quad_child_bf == "0"){ echo "selected"; } } ?>>Exclude</option>										 
										</select> 	
									</div>											
								   </div>
								   <div class="form-group">									
								 	 <div class="col-sm-2">
										<label for="field-1" class="col-sm-14 control-label">Penta Room Price</label>									
										<input type="text" class="form-control" id="week_hex_price" value="<?php if(isset($week_hex_price)) { echo $week_hex_price; } ?>" maxlength="7" name="week_hex_price" data-rule-number="true" data-validate="required" data-message-required="Please Enter the Penta Room Price" />
										<?php echo form_error('week_hex_price',  '<span for="field-1" class="tab_error_color">', '</span>'); ?>
									 </div>
									<div class="col-sm-3">
										<label for="field-2" class="col-sm-12 control-label">Adult Breakfast(include/Exclude)</label>									
										<select name="week_hex_adult_bf" id="week_hex_adult_bf" class="form-control">
											<option value="1" <?php if(isset($week_hex_adult_bf)){ if($week_hex_adult_bf == "1"){ echo "selected"; } } ?> >Include</option>
										 	<option value="0" <?php if(isset($week_hex_adult_bf)){ if($week_hex_adult_bf == "0"){ echo "selected"; } } ?>>Exclude</option>										 
										</select> 	
									</div>
									<div class="col-sm-3">
										<label for="field-2" class="col-sm-12 control-label">Child Breakfast(include/Exclude)</label>									
										<select name="week_hex_child_bf" id="week_hex_child_bf" class="form-control">
											<option value="1" <?php if(isset($week_hex_child_bf)){ if($week_hex_child_bf == "1"){ echo "selected"; } } ?>>Include</option>
										 	<option value="0" <?php if(isset($week_hex_child_bf)){ if($week_hex_child_bf == "0"){ echo "selected"; } } ?>>Exclude</option>										 
										</select> 	
									</div>											
								   </div> -->	
									<div class="form-group">
										<div id="week_bed_room_count">																													
										</div>				
									</div>														
								
								<div id="week_end_panel">									
                                    <div class="form-group">
                                     <label for="field-2" class="col-sm-2 control-label">Week End Price</label>									
                                    </div>					
                                    <div class="form-group">			  							     	
									<div class="col-sm-2">
									   

										<label for="field-2" class="col-sm-16 control-label">Single Adult Price</label>	
										
										
									
										
										
										<input type="text" class="form-control" id="weekend_sgl_price" maxlength="7" name="weekend_sgl_price" value="<?php if(isset($weekend_sgl_price)) { echo $weekend_sgl_price; }?>" data-validate="required" data-message-required="Please Enter the Single Room Price" />
										<?php echo form_error('weekend_sgl_price',  '<span for="field-1" class="tab_error_color">', '</span>'); ?>
									</div>
									<div class="col-sm-3">
										<label for="field-2" class="col-sm-12 control-label">Adult Breakfast(include/Exclude)</label>									
										<select name="weekend_single_adult_bf" id="weekend_single_adult_bf" class="form-control">
											<option value="1" <?php if(isset($weekend_single_adult_bf)){ if($weekend_single_adult_bf == "1"){ echo "selected"; } } ?> >Include</option>
										 	<option value="0" <?php if(isset($weekend_single_adult_bf)){ if($weekend_single_adult_bf == "0"){ echo "selected"; } } ?>>Exclude</option>										 
										</select> 	
									</div>
									<div class="col-sm-3">
										<label for="field-2" class="col-sm-12 control-label">Child Breakfast(include/Exclude)</label>									
										<select name="weekend_single_child_bf" id="weekend_single_child_bf" class="form-control">
											<option value="1" <?php if(isset($weekend_single_child_bf)){ if($weekend_single_child_bf == "1"){ echo "selected"; } } ?> >Include</option>
										 	<option value="0" <?php if(isset($weekend_single_child_bf)){ if($weekend_single_child_bf == "0"){ echo "selected"; } } ?>>Exclude</option>										 
										</select> 	
									</div>										
								  </div>
								  <div class="form-group">	
									<div class="col-sm-2">
										<label for="field-2" class="col-sm-14 control-label">Double Adult Price</label>									
										<input type="text" class="form-control" id="weekend_dbl_price" value="<?php if(isset($weekend_dbl_price)) { echo $weekend_dbl_price; }?>" maxlength="7" name="weekend_dbl_price"  />
										<?php echo form_error('weekend_dbl_price',  '<span for="field-1" class="tab_error_color">', '</span>'); ?>
									</div>
									<div class="col-sm-3">
										<label for="field-2" class="col-sm-12 control-label">Adult Breakfast(include/Exclude)</label>									
										<select name="weekend_double_adult_bf" id="weekend_double_adult_bf" class="form-control">
											<option value="1" <?php if(isset($weekend_double_adult_bf)){ if($weekend_double_adult_bf == "1"){ echo "selected"; } } ?> >Include</option>
										 	<option value="0" <?php if(isset($weekend_double_adult_bf)){ if($weekend_double_adult_bf == "0"){ echo "selected"; } } ?>>Exclude</option>										 
										</select> 	
									</div>
									<div class="col-sm-3">
										<label for="field-2" class="col-sm-12 control-label">Child Breakfast(include/Exclude)</label>									
										<select name="weekend_double_child_bf" id="weekend_double_child_bf" class="form-control">
											<option value="1" <?php if(isset($weekend_double_child_bf)){ if($weekend_double_child_bf == "1"){ echo "selected"; } } ?> >Include</option>
										 	<option value="0" <?php if(isset($weekend_double_child_bf)){ if($weekend_double_child_bf == "0"){ echo "selected"; } } ?>>Exclude</option>										 
										</select> 	
									</div>										
								</div>
								<div class="form-group">	
									<div class="col-sm-2">
										<label for="field-2" class="col-sm-14 control-label">Triple Adult Price</label>									
										<input type="text" class="form-control" id="weekend_tpl_price" value="<?php if(isset($weekend_tpl_price)) { echo $weekend_tpl_price; }?>" maxlength="7" name="weekend_tpl_price"  />
										<?php echo form_error('weekend_tpl_price',  '<span for="field-1" class="tab_error_color">', '</span>'); ?>
									</div>
									<div class="col-sm-3">
										<label for="field-2" class="col-sm-12 control-label">Adult Breakfast(include/Exclude)</label>									
										<select name="weekend_triple_adult_bf" id="weekend_triple_adult_bf" class="form-control">
											<option value="1" <?php if(isset($weekend_triple_adult_bf)){ if($weekend_triple_adult_bf == "1"){ echo "selected"; } } ?> >Include</option>
										 	<option value="0" <?php if(isset($weekend_triple_adult_bf)){ if($weekend_triple_adult_bf == "0"){ echo "selected"; } } ?>>Exclude</option>										 
										</select> 	
									</div>
									<div class="col-sm-3">
										<label for="field-2" class="col-sm-12 control-label">Child Breakfast(include/Exclude)</label>									
										<select name="weekend_triple_child_bf" id="weekend_triple_child_bf" class="form-control">
											<option value="1" <?php if(isset($weekend_triple_child_bf)){ if($weekend_triple_child_bf == "1"){ echo "selected"; } } ?> >Include</option>
										 	<option value="0" <?php if(isset($weekend_triple_child_bf)){ if($weekend_triple_child_bf == "0"){ echo "selected"; } } ?>>Exclude</option>										 
										</select> 	
									</div>										
								</div>
								<!-- <div class="form-group">	
									<div class="col-sm-2">
										<label for="field-2" class="col-sm-14 control-label">Quad Room Price</label>									
										<input type="text" class="form-control" id="weekend_quad_price" value="<?php if(isset($weekend_quad_price)) { echo $weekend_quad_price; }?>" maxlength="7" name="weekend_quad_price"  />
										<?php echo form_error('weekend_quad_price',  '<span for="field-1" class="tab_error_color">', '</span>'); ?>
									</div>
									<div class="col-sm-3">
										<label for="field-2" class="col-sm-12 control-label">Adult Breakfast(include/Exclude)</label>									
										<select name="weekend_quad_adult_bf" id="weekend_quad_adult_bf" class="form-control">
											<option value="1" <?php if(isset($weekend_quad_adult_bf)){ if($weekend_quad_adult_bf == "1"){ echo "selected"; } } ?> >Include</option>
										 	<option value="0" <?php if(isset($weekend_quad_adult_bf)){ if($weekend_quad_adult_bf == "0"){ echo "selected"; } } ?>>Exclude</option>										 
										</select> 	
									</div>
									<div class="col-sm-3">
										<label for="field-2" class="col-sm-12 control-label">Child Breakfast(include/Exclude)</label>									
										<select name="weekend_quad_child_bf" id="weekend_quad_child_bf" class="form-control">
											<option value="1" <?php if(isset($weekend_quad_child_bf)){ if($weekend_quad_child_bf == "1"){ echo "selected"; } } ?> >Include</option>
										 	<option value="0" <?php if(isset($weekend_quad_child_bf)){ if($weekend_quad_child_bf == "0"){ echo "selected"; } } ?>>Exclude</option>										 
										</select> 	
									</div>										
								</div>
								<div class="form-group">	
									<div class="col-sm-2">
										<label for="field-2" class="col-sm-14 control-label">Hex Room Price</label>									
										<input type="text" class="form-control" id="weekend_hex_price" value="<?php if(isset($weekend_hex_price)) { echo $weekend_hex_price; }?>" maxlength="7" name="weekend_hex_price"  />
										<?php echo form_error('weekend_hex_price',  '<span for="field-1" class="tab_error_color">', '</span>'); ?>
									</div>
									<div class="col-sm-3">
										<label for="field-2" class="col-sm-12 control-label">Adult Breakfast(include/Exclude)</label>									
										<select name="weekend_hex_adult_bf" id="weekend_hex_adult_bf" class="form-control">
											<option value="1" <?php if(isset($weekend_hex_adult_bf)){ if($weekend_hex_adult_bf == "1"){ echo "selected"; } } ?> >Include</option>
										 	<option value="0" <?php if(isset($weekend_hex_adult_bf)){ if($weekend_hex_adult_bf == "0"){ echo "selected"; } } ?>>Exclude</option>										 
										</select> 	
									</div>
									<div class="col-sm-3">
										<label for="field-2" class="col-sm-12 control-label">Child Breakfast(include/Exclude)</label>									
										<select name="weekend_hex_child_bf" id="weekend_hex_child_bf" class="form-control">
											<option value="1" <?php if(isset($weekend_hex_child_bf)){ if($weekend_hex_child_bf == "1"){ echo "selected"; } } ?> >Include</option>
										 	<option value="0" <?php if(isset($weekend_hex_child_bf)){ if($weekend_hex_child_bf == "0"){ echo "selected"; } } ?>>Exclude</option>										 
										</select> 	
									</div>										
								</div> -->
								<div class="form-group">	
									<div  id="weekend_bed_room_count">
									</div>									
								</div>	
							</div>						
								<div class="form-group">
									<div class="col-md-6">
										<button type="submit" class="btn btn-success right">Update Rate</button>
									</div>
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
	
	<script src="<?php echo base_url(); ?>hotel_assets/js/bootstrap-switch.min.js"></script>
	<script src="<?php echo base_url(); ?>hotel_assets/js/jquery.validate.min.js"></script>	
	<script src="<?php echo base_url(); ?>hotel_assets/js/fileinput.js"></script>
	<script src="<?php echo base_url(); ?>hotel_assets/js/selectboxit/jquery.selectBoxIt.min.js"></script>
	<script src="<?php echo base_url(); ?>hotel_assets/js/jquery.bootstrap.wizard.min.js"></script>
	<script src="<?php echo base_url(); ?>hotel_assets/js/ckeditor/ckeditor.js"></script>
	<script src="<?php echo base_url(); ?>hotel_assets/js/ckeditor/adapters/jquery.js"></script>
	<script src="<?php echo base_url(); ?>hotel_assets/js/bootstrap-timepicker.min.js"></script>
	<script src="<?php echo base_url(); ?>hotel_assets/js/bootstrap-datepicker.js"></script>

	<script src="<?php echo base_url(); ?>hotel_assets/js/select2/select2.min.js"></script>
   <script src="<?php echo base_url(); ?>hotel_assets/js/jquery-ui.js"></script>

  <script src="<?php echo base_url(); ?>hotel_assets/js/jquery.dataTables.min.js"></script>
  <script src="<?php echo base_url(); ?>hotel_assets/js/datatables/TableTools.min.js"></script>
  <script src="<?php echo base_url(); ?>hotel_assets/js/dataTables.bootstrap.js"></script>
  <script src="<?php echo base_url(); ?>hotel_assets/js/datatables/jquery.dataTables.columnFilter.js"></script>
  <script src="<?php echo base_url(); ?>hotel_assets/js/datatables/lodash.min.js"></script>
  <script src="<?php echo base_url(); ?>hotel_assets/js/datatables/responsive/js/datatables.responsive.js"></script> 
  <script src="<?php echo base_url(); ?>hotel_assets/js/daterangepicker/moment.min.js"></script>
  <script src="<?php echo base_url(); ?>hotel_assets/js/daterangepicker/daterangepicker.js"></script>
 
 <!--    <script src="<?= base_url(); ?>assets/js/plugins/datatables/dataTables.overrides.js" type="text/javascript"></script>
    <script src="<?= base_url(); ?>assets/js/plugins/lightbox/lightbox.min.js" type="text/javascript"></script>
 <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jquery-ui.css" type="text/css">-->
  
  
  
<script>
		$(document).ready(function () { 
			$('#date_rane_rate').daterangepicker({
				format: 'DD/MM/YYYY'
        
           });
		});
		$(function(){				
		   $('#promotion').hide();	
		   $('#extra_bed_price').hide();
		   $('#bed_room_count').hide();
		   $('#week_end_panel').hide();
		   $('#block_out_date_div').hide();

		   $('#child_group1').hide();
			$('input#adult_price,#child_price_a,#child_price_b,#child_price_c,#child_price_d,#child_price_e,#extra_bed_price,#sgl_price,#dbl_price,#tpl_price,#quad_price,#hex_price').keyup(function() {
				var $th = $(this);		
				if($th.val().trim() != ""){
					 var regex = /^[0-9. ]*$/;
					if (regex.test($th.val())) {
						$th.css('border', '1px solid #099A7D');					
					} else {
						// alert("Please use only letters");
						$th.css('border', '1px solid #f52c2c');
						return '';
					}
				}
			});	

			<?php
			 if(isset($week_end_select)){			 	
			 	if($week_end_select  == "1"){  ?>
			 		$('#week_end_panel').show();	
			<?php } else { ?>
				    $('#week_end_panel').hide();					
			<?php } } else { ?>
				$('#week_end_panel').hide();
			<?php  } ?>
			<?php 	  	
	  	 	 if(isset($block_out_date_rane_rate)) {
	  	 		$count = sizeof($block_out_date_rane_rate); 	  	 	
	  	 		if($count >= 1){
		     		for($cp =1; $cp < $count; $cp++) {  	?>		     		      
		      			addMoreRooms1(null);			    		      
		      			$('#block_out_date_rane_rate'+<?php echo $cp; ?>).val("<?php echo $block_out_date_rane_rate[$cp]; ?>");      		  			
						$('#hotel_blockout_id'+<?php echo $cp; ?>).val("<?php echo $hotel_blockout_id[$cp]; ?> ");		      									
			  		    //alert("<?php echo $block_out_date_rane_rate[$cp]; ?>");
			<?php 
	         		}//for
	        	}//if
	       	 }//if  
	    	?>  
			
			var hotel_id = "<?php if(isset($hotel_details_id)) { echo $hotel_details_id; } ?>";
			var room_type_id = "<?php if(isset($room_details_id)) { echo $room_details_id; } ?>";
			var seasons_id = "<?php if(isset($seasons_details_id)) { echo $seasons_details_id; } ?>";				
			if(hotel_id != ""){
				var select = $('#room_details_id');
				$.ajax({
				url:'<?php echo site_url();?>/seasons/get_room_type/'+hotel_id + "/"+ room_type_id,				
				success: function(data, textStatus, jqXHR) {					
					//alert(data);	  
					select.html('');
					select.html(data);						
					select.trigger("chosen:updated");              
				}
			  });	 	
			  var data1 = "";			 
			  var child_price = "<?php echo $child_price; ?>";			  
		  	  $.ajax({
			  url:'<?php echo site_url();?>/hotels/get_child_group/'+hotel_id + '/' +child_price,
			  success: function(data, textStatus, jqXHR) {
				data1 = data;
				if(data1.trim() != ''){
			     $('#child_group').html(data);
			     $('#child_group1').hide();
			    } else {
				 $('#child_group').html("");
			     $('#child_group1').show();
			    }			
		      }
		     });			  	 
			}

			if(room_type_id != ""){
				var select1 = $('#seasons_details_id');
				$.ajax({
				url:'<?php echo site_url();?>/seasons/get_season_room_type_room/'+room_type_id + "/" + seasons_id,				
				 success: function(data, textStatus, jqXHR) {					
						
					select1.html(''); 
					select1.html(data);						
					select1.trigger("chosen:updated"); 
				 }
			    });	 	
			    var data1 = "";
		  	    $.ajax({
			  	url:'<?php echo site_url();?>/roomrate/get_extra_bed_avail/'+room_type_id,
			  	dataType: "json",
			  	 success: function(data, textStatus, jqXHR) {
				  data1 = data.extra_bed;
				  //data2 = data.no_of_room;  JAN7 Change
				  //alert(data2);				  
				  if(data1 == "yes"){
				    var panel1 = "";
				     var panel1 = "";
				    panel1 =  "<div class='col-sm-2'>";
				    panel1 += "<label for='field-2' class='col-sm-12 control-label'>Extra Bed Price for Child </label>";													    
					panel1 += "<input type='text' class='form-control'  maxlength='7' id='child_extra_bed_price' value='<?php if(isset($child_extra_bed_price)) { echo $child_extra_bed_price;} else{ echo ''; }?>' name='child_extra_bed_price'   data-validate='required' data-message-required='Please enter the Child Extra bed price' data-rule-number='true'/>";										 					
					panel1 += "<?php echo form_error('child_extra_bed_price',  '<span for=field-1 class=tab_error_color>', '</span>'); ?>";										
					panel1 += "</div>";
					panel1 += "<div class='col-sm-2'>";
					panel1 += "<label for='field-2' class='col-sm-12 control-label'>Extra Bed Price for Adult </label>";																			
					panel1 += "<input type='text' class='form-control'  maxlength='7' id='adult_extra_bed_price' name='adult_extra_bed_price' value='<?php if(isset($adult_extra_bed_price)) { echo $adult_extra_bed_price;}else{ echo ''; } ?>'  data-validate='required' data-message-required='Please enter the Adult Extra bed price' data-rule-number='true'/>";					
					panel1 += "<?php echo form_error('adult_extra_bed_price',  '<span for=field-1 class=tab_error_color>', '</span>'); ?>";
					panel1 += "</div>";
					//alert(panel1);
					$('#extra_bed_price').html("");
				  	$('#extra_bed_price').html(panel1)
				  	$('#extra_bed_price').show();
				  }
				  else{
				   $('#extra_bed_price').hide();	
				  }				  
				  if(data2 > 0){
				  	var panel = "";
				  	panel     = "<div class='col-sm-2'><label for='tpl_price' class='col-sm-16 control-label'>"+data2 +" Bed Room Price</label>";		
         		    panel    += "<input type='text' class='form-control' id='week_bedroom_price' value='<?php if(isset($week_bedroom_price)) { echo $week_bedroom_price;} ?>' maxlength='5' name='week_bedroom_price'/>";
					panel    += "<?php echo form_error('week_bedroom_price',  '<span for=\"field-1\" class=\"tab_error_color\">', '</span>'); ?></div>";         		    
         		    panel    += "<div class='col-sm-3'><label for='field-2' class='col-sm-12 control-label'>Adult Breakfast(include/Exclude)</label>";									
					panel    +=	"<select name='week_bedroom_adult_bf' id='week_bedroom_adult_bf' class='form-control'>";
					panel    += "<option value='1' <?php if(isset($week_bedroom_adult_bf)){ if($week_bedroom_adult_bf == '1'){ echo 'selected'; }} ?>>Include</option><option value='0' <?php if(isset($week_bedroom_adult_bf)){ if($week_bedroom_adult_bf == '0'){ echo 'selected'; }} ?> >Exclude</option></select></div>";
					panel    += "<div class='col-sm-3'><label for='field-2' class='col-sm-12 control-label'>Child Breakfast(include/Exclude)</label>";									
					panel    +=	"<select name='week_bedroom_child_bf' id='week_bedroom_child_bf' class='form-control'>";
					panel    += "<option value='1' <?php if(isset($week_bedroom_child_bf)){ if($week_bedroom_child_bf == '1'){ echo 'selected'; }} ?> >Include</option><option value='0' <?php if(isset($week_bedroom_child_bf)){ if($week_bedroom_child_bf == '0'){ echo 'selected'; }} ?>  >Exclude</option></select></div>";
				  	$('#week_bed_room_count').html("");
				  	$('#week_bed_room_count').html(panel)
				  	$('#week_bed_room_count').show();

				  	panel = "";
				  	panel     = "<div class='col-sm-2'><label for='tpl_price' class='col-sm-16 control-label'>"+data2 +" Bed Room Price</label>";		
         		    panel    += "<input type='text' class='form-control' id='weekend_bedroom_price' maxlength='5' value='<?php if(isset($weekend_bedroom_price)) { echo $weekend_bedroom_price; } ?>' name='weekend_bedroom_price'/>";
         		    panel    += "<?php echo form_error('weekend_bedroom_price',  '<span for=\"field-1\" class=\"tab_error_color\">', '</span>'); ?></div>";         		    
         		    panel    += "<div class='col-sm-3'><label for='field-2' class='col-sm-12 control-label'>Adult Breakfast(include/Exclude)</label>";									
					panel    +=	"<select name='weekend_bedroom_adult_bf' id='weekend_bedroom_adult_bf' class='form-control'>";
					panel    += "<option value='1' <?php if(isset($weekend_bedroom_adult_bf)){ if($weekend_bedroom_adult_bf == '1'){ echo 'selected'; }} ?> >Include</option><option value='0' <?php if(isset($weekend_bedroom_adult_bf)){ if($weekend_bedroom_adult_bf == '0'){ echo 'selected'; }} ?> >Exclude</option></select></div>";
					panel    += "<div class='col-sm-3'><label for='field-2' class='col-sm-12 control-label'>Child Breakfast(include/Exclude)</label>";									
					panel    +=	"<select name='weekend_bedroom_child_bf' id='weekend_bedroom_child_bf' class='form-control'>";
					panel    += "<option value='1' <?php if(isset($weekend_bedroom_child_bf)){ if($weekend_bedroom_child_bf == '1'){ echo 'selected'; }} ?>  >Include</option><option value='0' <?php if(isset($weekend_bedroom_child_bf)){ if($weekend_bedroom_child_bf == '0'){ echo 'selected'; }} ?> >Exclude</option></select></div>";

				  	$('#weekend_bed_room_count').html("");
				  	$('#weekend_bed_room_count').html(panel)
				  	$('#weekend_bed_room_count').show();
				  }
				  else{
				  	$('#week_bed_room_count').html("");
				  	$('#week_bed_room_count').hide();	
				  	$('#weekend_bed_room_count').html("");
				  	$('#weekend_bed_room_count').hide();	
				  }
		         }
		        });		
			 }//room_type_id

			 <?php if(isset($room_rate_type)){ ?>			     
				$('#block_out_date_div').hide();
				$('#promotion').hide();			

			<?php if($room_rate_type == 2){ ?>
				$('#promotion').show();				
			<?php }
			elseif($room_rate_type == 3){ ?>
				$('#promotion').show();					
				$('#block_out_date_div').show();
			<?php }
			elseif($room_rate_type == 5){ ?>
				$('#block_out_date_div').show();	
			<?php }
			else{ ?>
				$('#promotion').hide();	
				$('#block_out_date_div').hide();
			<?php } } ?>
			
			$('#room_rate_details11').submit(function() {				

				var hotel_type1 = $('#hotel_details_id').val();			
				if(hotel_type1 == "0"){					
					hotel_details_id.style.border = "1px solid #f52c2c";   
					hotel_details_id.focus(); 
					return false; 			
				}


				var hotel_type111 = $('#room_details_id').val();			
				if(hotel_type111 == "" || hotel_type111 == "0" ){					
					room_details_id.style.border = "1px solid #f52c2c";   
					room_details_id.focus(); 
					return false; 			
				}

				var hotel_type11 = $('#seasons_details_id').val();						
				if(hotel_type11 == "" || hotel_type11 == "0"){					
					seasons_details_id.style.border = "1px solid #f52c2c";   
					seasons_details_id.focus(); 
					return false; 			
				}

				var hotel_type11 = $('#room_rate_type').val();						
				if(hotel_type11 == "" || hotel_type11 == "0"){					
					room_rate_type.style.border = "1px solid #f52c2c";   
					room_rate_type.focus(); 
					return false; 			
				}		


				var number_filter1 = /^[0-9 -/]*$/;
				if(date_rane_rate.value != '')
				{
					if(!(date_rane_rate.value.match(number_filter1)))
					{
						date_rane_rate.style.border = "1px solid #f52c2c";   
						date_rane_rate.focus(); 
						return false; 
					}
				}
				else
				{
					date_rane_rate.style.border = "1px solid #f52c2c";   
					date_rane_rate.focus(); 
					return false; 
				}
				
				if(date_rane_rate.value == '' ) {
					    date_rane_rate.style.border = "1px solid #f52c2c";   
						date_rane_rate.focus(); 
						return false; 
				}

				if(date_rane_rate.value.length != 23 ) {
					    date_rane_rate.style.border = "1px solid #f52c2c";   
						date_rane_rate.focus(); 
						return false; 
				}


				// domain Name validation 
				var domain_name = document.getElementById('adult_price');	
				var filter = /^[0-9. ]*$/;
				if(domain_name.value != ''){
					if(!(domain_name.value.match(filter))){
						domain_name.style.border = "1px solid #f52c2c";   
						domain_name.focus(); 
						return false; 
					}
				}else{
					domain_name.style.border = "1px solid #f52c2c";   
					domain_name.focus(); 
					return false; 
				}
				
				var domain_name = document.getElementById('child_price_a');	
				var filter = /^[0-9. ]*$/;
				if(domain_name.value != ''){
					if(!(domain_name.value.match(filter))){
						domain_name.style.border = "1px solid #f52c2c";   
						domain_name.focus(); 
						return false; 
					}
				}else{
					domain_name.style.border = "1px solid #f52c2c";   
					domain_name.focus(); 
					return false; 
				}
				var domain_name = document.getElementById('child_price_b');	
				var filter = /^[0-9. ]*$/;
				if(domain_name.value != ''){
					if(!(domain_name.value.match(filter))){
						domain_name.style.border = "1px solid #f52c2c";   
						domain_name.focus(); 
						return false; 
					}
				}else{
					domain_name.style.border = "1px solid #f52c2c";   
					domain_name.focus(); 
					return false; 
				}
				var domain_name = document.getElementById('child_price_c');	
				var filter = /^[0-9. ]*$/;
				if(domain_name.value != ''){
					if(!(domain_name.value.match(filter))){
						domain_name.style.border = "1px solid #f52c2c";   
						domain_name.focus(); 
						return false; 
					}
				}else{
					domain_name.style.border = "1px solid #f52c2c";   
					domain_name.focus(); 
					return false; 
				}
				var domain_name = document.getElementById('child_price_d');	
				var filter = /^[0-9. ]*$/;
				if(domain_name.value != ''){
					if(!(domain_name.value.match(filter))){
						domain_name.style.border = "1px solid #f52c2c";   
						domain_name.focus(); 
						return false; 
					}
				}else{
					domain_name.style.border = "1px solid #f52c2c";   
					domain_name.focus(); 
					return false; 
				}				
				
				var domain_name5 = document.getElementById('extra_bed_price');	
				var filter = /^[0-9. ]*$/;
				if(domain_name5.value != ''){
					if(!(extra_bed_price.value.match(filter))){
						extra_bed_price.style.border = "1px solid #f52c2c";   
						extra_bed_price.focus(); 
						return false; 
					}
				}else{
					extra_bed_price.style.border = "1px solid #f52c2c";   
					extra_bed_price.focus(); 
					return false; 
				}
				var domain_name6 = document.getElementById('sgl_price');	
				var filter = /^[0-9. ]*$/;
				if(domain_name6.value != ''){
					if(!(sgl_price.value.match(filter))){
						sgl_price.style.border = "1px solid #f52c2c";   
						sgl_price.focus(); 
						return false; 
					}
				}else{
					sgl_price.style.border = "1px solid #f52c2c";   
					sgl_price.focus(); 
					return false; 
				}
				var domain_name7 = document.getElementById('dbl_price');	
				var filter = /^[0-9. ]*$/;
				if(domain_name7.value != ''){
					if(!(dbl_price.value.match(filter))){
						dbl_price.style.border = "1px solid #f52c2c";   
						dbl_price.focus(); 
						return false; 
					}
				}else{
					dbl_price.style.border = "1px solid #f52c2c";   
					dbl_price.focus(); 
					return false; 
				}
				var domain_name8 = document.getElementById('tpl_price');	
				var filter = /^[0-9. ]*$/;
				if(domain_name8.value != ''){
					if(!(tpl_price.value.match(filter))){
						tpl_price.style.border = "1px solid #f52c2c";   
						tpl_price.focus(); 
						return false; 
					}
				}else{
					tpl_price.style.border = "1px solid #f52c2c";   
					tpl_price.focus(); 
					return false; 
				}
				var domain_name9 = document.getElementById('hex_price');	
				var filter = /^[0-9. ]*$/;
				if(domain_name9.value != ''){
					if(!(hex_price.value.match(filter))){
						hex_price.style.border = "1px solid #f52c2c";   
						hex_price.focus(); 
						return false; 
					}
				}
			});			

			
			$('#status').change(function(){
				var current_status = $('#status').val();
				if(current_status == "ACTIVE")
					$('#status').val('INACTIVE');
				else
					$('#status').val('ACTIVE');
			});

			$('#gst_markup').change(function(){
				var gst_markup = $('#gst_markup').val();
				if(gst_markup == "INCLUSIVE")
					$('#gst_markup').val('EXCLUSIVE');
				else
					$('#gst_markup').val('INCLUSIVE');
			});
			
			$('#gst').change(function(){
				var gst = $('#gst').val();
				if(gst == "INCLUSIVE")
					$('#gst').val('INCLUSIVE');
				else
					$('#gst').val('EXCLUSIVE');
				//alert($('#gst').val());
			});
			
			$('#gst_green_tax').change(function(){
				var gst_green_tax = $('#gst_green_tax').val();
				if(gst_green_tax == "No")
					$('#gst_green_tax').val('Yes');
				else
					$('#gst_green_tax').val('No');
			});
			$('#green_tax').change(function(){
				var green_tax = $('#green_tax').val();
				if(green_tax == "Inclusive")
					$('#green_tax').val('Exclusive');
				else
					$('#green_tax').val('Inclusive');
			});
			
			$('#sc_applicable').change(function(){
				var sc_applicable = $('#sc_applicable').val();
				if(sc_applicable == "No")
					$('#sc_applicable').val('Yes');
				else
					$('#sc_applicable').val('No');
			});
			
			$('#service_charge').change(function(){
				var service_charge = $('#service_charge').val();					
				if(service_charge == "INCLUSIVE")
					$('#service_charge').val('INCLUSIVE');
				else
					$('#service_charge').val('EXCLUSIVE');
				//alert($('#service_charge').val());
			});
			$('#room_details_id').change(function(){
				var room_details_id = $('#room_details_id').val();
				$.ajax({
					url:'<?php echo site_url();?>/roomrate/get_extra_bed/'+room_details_id,
					success: function(data, textStatus, jqXHR) { 
						if(data == "Available"){
						$('#extra_bed_price1').show();
						$('#extra_bed_price_total1').show();
						$('#extra_bed_price_total').val(0);
							$('#extra_bed_price').val(0);
						}else{
							$('#extra_bed_price1').hide();
							$('#extra_bed_price_total1').hide();
							$('#extra_bed_price_total').val(0);
							$('#extra_bed_price').val(0);
							}
						
					}
				});
	    
	    	var hotel_id = $('#hotel_details_id').val()
	    	/*
		    $.ajax({
				url:'<?php echo base_url();?>roomrate/get_room_daterange/'+hotel_id+'/'+room_details_id,
				success: function(data, textStatus, jqXHR) {
				 $('#date_rane_rate').val(data);
				 var date_array = data.split(" - ");
				
				 var from_date_array = date_array[0].split("/");
				 var from_date = from_date_array[2]+"-"+from_date_array[1]+"-"+from_date_array[0];
			     var to_date_array = date_array[1].split("/");
				 var todate = to_date_array[2]+"-"+to_date_array[1]+"-"+to_date_array[0];
				  $('#date_rane_rate').daterangepicker({
				       format: 'MM/DD/YYYY',
	                    startDate: from_date,
                        endDate: todate,
                        minDate: from_date,
                        maxDate:  todate,
		     	});
					
				 //~ $('#date_rane_rate').attr('data-min-date',from_date);
				 //~ $('#date_rane_rate').attr('data-max-date',todate);
				 //~ $('input[name="daterangepicker_start"]').val(date_array[0]);
				  //~ $('input[name="daterangepicker_end"]').val(date_array[1]);
				//~ 
				 }
			});
		*/
			
			});
		});
		 function addMoreRooms1() {		 		    	
			$("#extra_date").css({'display':'inherit'});
			var id = $('#rows_cnt').val();
			
	    	$("#extra_date").append('<div class="form-group">'+
	    						  '<label for="field-1" class="col-sm-3 control-label">Block Out Date Range</label>'+								  
								  '<div class="col-sm-5">'+  
								  '<input type="text" class="form-control daterange" id="block_out_date_rane_rate'+id+'" name="block_out_date_rane_rate[]" value="" data-validate="required" data-message-required="Please Select the block out date range" />'+								  
								  '<input type="hidden" id="hotel_blockout_id'+id+'" name="hotel_blockout_id[]" value=""/>'+
								  '</div>'+
								  '</div>');	
			$('.daterange').daterangepicker();								  							  
			id = parseInt(id)+1;
			$('#rows_cnt').val(id);																

		}

		function removeLastRoom1(v){		  
			var id = $('#rows_cnt').val();
			var id1= parseInt(id)-1;
			var hotel_blockout_id = $('#hotel_blockout_id'+id1).val();									
			//alert(hotel_blockout_id);
			if(hotel_blockout_id > 0){
				var conval = confirm("Are you want to delete " +$('#block_out_date_rane_rate'+id1).val());
				if(conval == true){
					$.ajax({
            			url: '<?php echo site_url(); ?>/roomrate/delete_blockout_date/' + hotel_blockout_id,
            			success: function (data, textStatus, jqXHR) {                     	            	            	              			
            				alert("Cancellation policy has deleted");
            				$('#extra_date .form-group').last().remove();
							if(id <= 1) {
								$("#extra_date").css({'display':'none'});
							}
							id = parseInt(id)-1;
							$('#rows_cnt').val(id);
						}	
           			});   	
           		}	
			}
			else{
				$('#extra_date .form-group').last().remove();
				if(id <= 1) {
					$("#extra_date").css({'display':'none'});
				}
				id = parseInt(id)-1;
				$('#rows_cnt').val(id);
			}	
		}	

	
		function select_room_type(hotel_id){
			var select = $('#room_details_id');
			if(hotel_id != ""){
				$.ajax({
				url:'<?php echo site_url();?>/seasons/get_room_type/'+hotel_id,				
				success: function(data, textStatus, jqXHR) {					
					//alert(data);	  
					select.html('');
					select.html(data);						
					select.trigger("chosen:updated");              
				}
			  });	 	
			  var data1 = "";
		  	  $.ajax({
			  url:'<?php echo site_url();?>/hotels/get_child_group/'+hotel_id,
			  success: function(data, textStatus, jqXHR) {
				data1 = data;
				if(data1.trim() != ''){
			     $('#child_group').html(data);
			     $('#child_group1').hide();
			    } else {
				 $('#child_group').html("");
			     $('#child_group1').show();
			    }			
		      }
		     });			  	 
			}
			else{
			 	    select.html('');					
					select.trigger("chosen:updated");              
			}
		}

		function slect_rate_type(rate_type){									
			$('#block_out_date_div').hide();
			$('#promotion').hide();					
			if(rate_type == 2){
				$('#promotion').show();				
			}
			else if(rate_type == 3){
				$('#promotion').show();					
				$('#block_out_date_div').show();
			}
			else if(rate_type == 5){
				$('#block_out_date_div').show();	
			}
			else{
				$('#promotion').hide();	
				$('#block_out_date_div').hide();
			}
		}

		function select_week_end(weekend){			
			if(weekend == 0){
				$('#week_end_panel').hide();
			}
			else{
				$('#week_end_panel').show();	
			}
		}

		function select_season(room_type_id){			
			var select = $('#seasons_details_id');
			if(room_type_id != ""){
				$.ajax({
				url:'<?php echo site_url();?>/seasons/get_season_room_type/'+room_type_id,				
				 success: function(data, textStatus, jqXHR) {					
					
					select.html('');
					select.html(data);						
					select.trigger("chosen:updated");              
				 }
			    });	 	
			    var data1 = "";
		  	    $.ajax({
			  	url:'<?php echo site_url();?>/roomrate/get_extra_bed_avail/'+room_type_id,
			  	dataType: "json",
			  	 success: function(data, textStatus, jqXHR) {
				  data1 = data.extra_bed;
				  data2 = data.no_of_room;
				  //alert(data2);				  
				  if(data1 == "yes"){				    
				    var panel1 = "";
				    panel1 =  "<div class='col-sm-2'>";
				    panel1 += "<label for='field-2' class='col-sm-12 control-label'>Extra Bed Price for Child </label>";													    
					panel1 += "<input type='text' class='form-control'  maxlength='7' id='child_extra_bed_price' value='<?php if(isset($child_extra_bed_price)) { echo $child_extra_bed_price;} else{ echo ''; }?>' name='child_extra_bed_price'   data-validate='required' data-message-required='Please enter the Child Extra bed price' data-rule-number='true'/>";										 					
					panel1 += "<?php echo form_error('child_extra_bed_price',  '<span for=field-1 class=tab_error_color>', '</span>'); ?>";										
					panel1 += "</div>";
					panel1 += "<div class='col-sm-2'>";
					panel1 += "<label for='field-2' class='col-sm-12 control-label'>Extra Bed Price for Adult </label>";																			
					panel1 += "<input type='text' class='form-control'  maxlength='7' id='adult_extra_bed_price' name='adult_extra_bed_price' value='<?php if(isset($adult_extra_bed_price)) { echo $adult_extra_bed_price;}else{ echo ''; } ?>'  data-validate='required' data-message-required='Please enter the Adult Extra bed price' data-rule-number='true'/>";					
					panel1 += "<?php echo form_error('adult_extra_bed_price',  '<span for=field-1 class=tab_error_color>', '</span>'); ?>";
					panel1 += "</div>";
					//alert(panel1);
					$('#extra_bed_price').html("");
				  	$('#extra_bed_price').html(panel1)				  	
				  	$('#extra_bed_price').show();
				  }
				  else{
				   $('#extra_bed_price').hide();	
				  }				  
				  if(data2 > 0){
				  	var panel = "";
				  	panel     = "<div class='col-sm-2'><label for='tpl_price' class='col-sm-16 control-label'>"+data2 +" Bed Room Price</label>";		
         		    panel    += "<input type='text' class='form-control' id='week_bedroom_price' maxlength='5' <?php if(isset($week_bedroom_price)) { echo $week_bedroom_price; } ?> name='week_bedroom_price'/></div>";
         		    panel    += "<div class='col-sm-3'><label for='field-2' class='col-sm-12 control-label'>Adult Breakfast(include/Exclude)</label>";									
					panel    +=	"<select name='week_bedroom_adult_bf' id='week_bedroom_adult_bf' class='form-control'>";
					panel    += "<option value='1' <?php if(isset($week_bedroom_adult_bf)){ if($week_bedroom_adult_bf == '1'){ echo 'selected'; }} ?> >Include</option><option value='0' <?php if(isset($week_bedroom_adult_bf)){ if($week_bedroom_adult_bf == '0'){ echo 'selected'; }} ?>>Exclude</option></select></div>";
					panel    += "<div class='col-sm-3'><label for='field-2' class='col-sm-12 control-label'>Child Breakfast(include/Exclude)</label>";									
					panel    +=	"<select name='week_bedroom_child_bf' id='week_bedroom_child_bf' class='form-control'>";
					panel    += "<option value='1' <?php if(isset($week_bedroom_child_bf)){ if($week_bedroom_child_bf == '1'){ echo 'selected'; }} ?> >Include</option><option value='0' <?php if(isset($week_bedroom_child_bf)){ if($week_bedroom_child_bf == '0'){ echo 'selected'; }} ?>>Exclude</option></select></div>";
				  	$('#week_bed_room_count').html("");
				  	$('#week_bed_room_count').html(panel)
				  	$('#week_bed_room_count').show();

				  	panel = "";
				  	panel     = "<div class='col-sm-2'><label for='tpl_price' class='col-sm-16 control-label'>"+data2 +" Bed Room Price</label>";		
         		    panel    += "<input type='text' class='form-control' id='weekend_bedroom_price' maxlength='5' <?php if(isset($weekend_bedroom_price)) { echo $weekend_bedroom_price; } ?> name='weekend_bedroom_price'/></div>";
         		    panel    += "<div class='col-sm-3'><label for='field-2' class='col-sm-12 control-label'>Adult Breakfast(include/Exclude)</label>";									
					panel    +=	"<select name='weekend_bedroom_adult_bf' id='weekend_bedroom_adult_bf' class='form-control'>";
					panel    += "<option value='1' <?php if(isset($weekend_bedroom_adult_bf)){ if($weekend_bedroom_adult_bf == '1'){ echo 'selected'; }} ?>>Include</option><option value='0' <?php if(isset($weekend_bedroom_adult_bf)){ if($weekend_bedroom_adult_bf == '0'){ echo 'selected'; }} ?>>Exclude</option></select></div>";
					panel    += "<div class='col-sm-3'><label for='field-2' class='col-sm-12 control-label'>Child Breakfast(include/Exclude)</label>";									
					panel    +=	"<select name='weekend_bedroom_child_bf' id='weekend_bedroom_child_bf' class='form-control'>";
					panel    += "<option value='1' <?php if(isset($weekend_bedroom_child_bf)){ if($weekend_bedroom_child_bf == '1'){ echo 'selected'; }} ?>>Include</option><option value='0' <?php if(isset($weekend_bedroom_child_bf)){ if($weekend_bedroom_child_bf == '0'){ echo 'selected'; }} ?>>Exclude</option></select></div>";

				  	$('#weekend_bed_room_count').html("");
				  	$('#weekend_bed_room_count').html(panel)
				  	$('#weekend_bed_room_count').show();
				  }
				  else{
				  	$('#week_bed_room_count').html("");
				  	$('#week_bed_room_count').hide();	
				  	$('#weekend_bed_room_count').html("");
				  	$('#weekend_bed_room_count').hide();	
				  }
		         }
		        });		
			 }
			 else{
			 	    select.html('');					
					select.trigger("chosen:updated");              
			 }

		}

		function select_room(hotelId){			
			var $select = $('#room_details_id');
			var $select1 = $('#seasons_details_id');
			$.ajax({
				url:'<?php echo site_url();?>/roomrate/get_room_data1/'+hotelId,
				dataType: "json",
				success: function(data, textStatus, jqXHR) {
					$select.html('');
					$select.html('<option value="">Select Any Room</option>'+data.options);
					$select1.html('');
					$select1.html('<option value="">Select Any Seasons</option>'+data.options1);
					$('#extra_bed_price1').hide();
					$('#extra_bed_price_total1').hide();
					$('#extra_bed_price_total').val(0);
					$('#extra_bed_price').val(0);
				}
			});
		   var data1 = "";
		  $.ajax({
			  url:'<?php echo site_url();?>/hotels/get_child_group/'+hotelId,
			 success: function(data, textStatus, jqXHR) {
				data1= data;
				if(data1.trim() != ''){
			     $('#child_group').html(data);
			    $('#child_group1').hide();
			} else {
				$('#child_group').html("");
			    $('#child_group1').show();
			}
			
		    	}
		   });
		
	   }
		  
		
		
	</script>