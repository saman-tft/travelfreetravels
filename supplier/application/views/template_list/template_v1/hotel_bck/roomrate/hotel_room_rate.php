<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">	
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="description" content="Provab Admin Panel" />
	<meta name="author" content="" />	
	<title><?php echo PAGE_TITLE; ?> | Room Rate Management</title>	
	<!-- Load Default CSS and JS Scripts -->
	<?php $this->load->view('general/load_css');	?>
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/selectboxit/jquery.selectBoxIt.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/select2/select2-bootstrap.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/select2/select2.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/daterangepicker/daterangepicker-bs3.css">
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
<body  class="page-body <?php if(isset($transition)){ echo $transition; } ?>" data-url="<?php echo PROVAB_URL; ?>">
	<div class="page-container <?php if(isset($header) && $header == 'header_top'){ echo "horizontal-menu"; } ?> <?php if(isset($header) && $header == 'header_right'){ echo "right-sidebar"; } ?>  <?php if(isset($sidebar)){ echo $sidebar; } ?>">
		<?php if(isset($header) && $header == 'header_top'){ $this->load->view('general/header_top'); }else{ $this->load->view('general/left_menu'); }	?>
		<div class="main-content">
			<?php if(!isset($header) || $header != 'header_top'){ $this->load->view('general/header_left'); } ?>
			<?php $this->load->view('general/top_menu');	?>
			<hr />
			<ol class="breadcrumb bc-3">						
					<?php if($supplier_rights == 1){
					 $url = site_url()."/supplier_dashboard";
				 } else {
					  $url = site_url()."/dashboard";
				 } ?>	
				 				
				<li><a href="<?php echo $url; ?>"><i class="entypo-home"></i>Home</a></li>
				<li><a href="<?php echo site_url()."/roomrate/list_room_rate"; ?>">Room Rate Management</a></li>
				<li class="active"><strong>Add New Room Rate Profile</strong></li>
			</ol>
			<div class="row">
				<div class="col-md-12">					
					<div class="panel panel-primary" data-collapsed="0">					
						<div class="panel-heading">
							<div class="panel-title">
								Add New Room Rate
							</div>							
							<div class="panel-options">
								<a href="#sample-modal" data-toggle="modal" data-target="#sample-modal-dialog-1" class="bg"><i class="entypo-cog"></i></a>
								<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
								<a href="#" data-rel="reload"><i class="entypo-arrows-ccw"></i></a>
								<a href="#" data-rel="close"><i class="entypo-cancel"></i></a>
							</div>
						</div>						
						<div class="panel-body">							
							<form id="room_rate_details11" method="post" action="<?php echo site_url()."/roomrate/hotel_add_room_rate".$get_url; ?>" class="form-horizontal form-groups-bordered validate" enctype= "multipart/form-data">				
							<?php if($supplier_rights == 1) { ?>
							<input type="hidden" name="supplier_rights" id="supplier_rights" value="<?php echo $supplier_rights; ?>" />
							<?php } else { ?>
							<input type="hidden" name="supplier_rights" id="supplier_rights" value="" />
							<?php } ?>
								<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">Hotel</label>									
									<div class="col-sm-5">
										<select <?php if(isset($GET) && !empty($GET)){ echo ' disabled="disabled" readonly="readonly"';}?> id="hotel_details_id" <?php if(isset($GET)){ echo  ' name="hotel_details_id"';}?> onChange="select_room_type(this.value);" class="form-control">
											 <option value="0">Select Hotel</option>
											<?php foreach ($hotels_list as $hotel){ 
												
												if(isset($GET) && !empty($GET))
												{
												?>
													<option <?php if($hotel->hotel_name==str_replace('"','',($GET))){ echo ' selected="selected"'; $selected_hotel_detail_id = $hotel->hotel_details_id; }?> value="<?php echo $hotel->hotel_details_id; ?>" <?php if(isset($hotel_details_id)){ if($hotel_details_id == $hotel->hotel_details_id) { echo "selected"; } } ?> data-iconurl=""><?php echo $hotel->hotel_name; ?></option>
												<?php
												} else {
												?>
													<option value="<?php echo $hotel->hotel_details_id; ?>" <?php if(isset($hotel_details_id)){ if($hotel_details_id == $hotel->hotel_details_id) { echo "selected"; } } ?> data-iconurl=""><?php echo $hotel->hotel_name; ?></option>
												<?php
												}
												?>

												
											<?php } ?>
										</select>
										<?php 
										if(isset($GET) && !empty($GET))
										{
										?>
											<input type="hidden" value="<?=$selected_hotel_detail_id?>" name="hotel_details_id" />
										<?php
										 } 
										?>
									</div>
									<?php echo form_error('hotel_details_id',  '<span for="field-1" class="tab_error_color">', '</span>'); ?>
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
								<!-- <div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">Seasons</label>									
									<div class="col-sm-5">
										 <select id="seasons_details_id" name="seasons_details_id" onchange="select_date(this.value)" class="form-control">
										 <option value="0">Select Seasons</option>
										 </select>
										 <?php echo form_error('seasons_details_id',  '<span for="field-1" class="tab_error_color">', '</span>'); ?>
									</div>
								</div>	 -->					
								
								<!-- <div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">Cancellation Policy</label>									
									<div class="col-sm-5">
										 <select id="cancellation_policy" name="cancellation_policy" class="form-control">										 
										 	<option value="1" <?php if(isset($cancellation_policy)){ if($cancellation_policy == '1')   { echo "selected"; } } ?> >Applicable</option>
										 	<option value="0" <?php if(isset($cancellation_policy)){ if($cancellation_policy == '0')   { echo "selected"; } } ?> >Not Applicable</option>										 
										 </select>
										 <?php echo form_error('cancellation_policy',  '<span for="field-1" class="tab_error_color">', '</span>'); ?>
									</div>
								</div>	 -->					

								<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">Week End Require</label>									
									<div class="col-sm-5">
										 <select id="week_end_select" name="week_end_select" onchange="select_week_end(this.value)" class="form-control">										 
										    <option value="0" <?php if(isset($week_end_select)){ if($week_end_select == '0')  { echo "selected"; } } ?> >No</option>										 
										 	<option value="1" <?php if(isset($week_end_select)){ if($week_end_select == '1')  { echo "selected"; } } ?> >Yes</option>										 	
										 </select>
										 <?php echo form_error('week_end_select',  '<span for="field-1" class="tab_error_color">', '</span>'); ?>
									</div>
								</div>						
								
								<div id="promotion">
								<div class="form-group">								
									<label for="field-1" class="col-sm-6 control-label tab_error_color"><p>* Please select country in either Include or Exclude</p><label>											
								</div>	
								 <div class="form-group" >
									<label for="field-1" class="col-sm-3 control-label">Promotion</label>									
									<div class="col-sm-5"> 
										 <input type="text" class="form-control" name="promotion" value="<?php if(isset($promotion)){ echo $promotion; } ?>" maxlength="200" id="promotion" data-validate="required" data-message-required="Please Enter the Promotion" />
										 <?php echo form_error('promotion',  '<span for="field-1" class="tab_error_color">', '</span>'); ?>
									</div>
								 </div>								 
								 
								 <div class="form-group">
                                        <label for="field-1" class="col-sm-3 control-label">Country (Include)</label>									
                                        <div class="col-sm-5">                                            
                                            <select name="include_country[]" id="include_country" class="select2" multiple>                                                
                                                <?php for ($c = 0; $c < count($country); $c++) { ?>
                                                    <option value="<?php echo $country[$c]->country_id; ?>" 
                                                    <?php if(isset($include_country)) { if(in_array($country[$c]->country_id, $include_country	)) { echo "selected"; } } ?> data-iconurl=""><?php echo $country[$c]->country_name . " (" . $country[$c]->iso3_code . ")"; ?></option>
                                                <?php } ?>
                                            </select>
                                            <?php echo form_error('include_country', '<span for="field-1" class="validate-has-error">', '</span>'); ?>
                                        </div>                                        
                                </div>  
                                <div class="form-group">
                                        <label for="field-1" class="col-sm-3 control-label">Country (Exclude)</label>									
                                        <div class="col-sm-5">                                            
                                            <select name="exclude_country[]" id="exclude_country" class="select2" multiple>                                                
                                                <?php for ($c = 0; $c < count($country); $c++) { ?>
                                                    <option value="<?php echo $country[$c]->country_id; ?>" 
                                                    <?php if(isset($exclude_country)) { if(in_array($country[$c]->country_id, $exclude_country)){ echo "selected"; } } ?> data-iconurl=""><?php echo $country[$c]->country_name . " (" . $country[$c]->iso3_code . ")"; ?></option>
                                                <?php } ?>
                                            </select>
                                            <?php echo form_error('exclude_country', '<span for="field-1" class="validate-has-error">', '</span>'); ?>
                                        </div>                                        
                                </div>                                 
                               </div> 	




								<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">Date Range</label>									
									<div class="col-sm-5">
										<!-- <input type="text" class="form-control" name="date_rane_rate" id="date_rane_rate"   data-validate="required" data-message-required="Please Select the Date Range"  /> -->
										 <input type="text" class="form-control daterange" id="date_rane_rate" name="date_rane_rate" value="<?php if(isset($date_rane_rate)) { echo $date_rane_rate; } ?>" data-validate="required" data-message-required="Please Select the Main Date Range" />
										 <!-- <input type="text" class="form-control daterange" id="date_rane_rate" name="date_rane_rate" value="" data-min-date="<?php echo date('m/d/Y');?>" data-validate="required" data-message-required="Please Select the Main Date Range" /> -->
										 <?php echo form_error('date_rane_rate',  '<span for="field-1" class="tab_error_color">', '</span>'); ?>
									</div>
								</div>

								<div class="form-group">
									<div class="col-sm-3">
										<label class="col-sm-7 control-label">GST</label>		
										<div id="label-switch" class="make-switch" data-on-label="Inclusive" data-off-label="Exclusive" style="min-width: 200px;">
											<input type="checkbox" name="gst" value="Inclusive" id="gst" <?php if(isset($gst)) { if($gst == "Inclusive") { echo "checked"; } } else { echo "checked"; } ?>>
										</div>
									</div>									
									<div class="col-sm-3">
										<label class="col-sm-7 control-label">Service Charges</label>		
										<div id="label-switch" class="make-switch" data-on-label="Inclusive" data-off-label="Exclusive" style="min-width: 200px;">
											<input type="checkbox" name="service_charge" value="Inclusive" id="service_charge" <?php if(isset($service_charge)) { if($service_charge == "Inclusive") { echo "checked"; } } else { echo "checked"; } ?> >
										</div>
									</div>

									<!--
									<div class="col-sm-2">
										<label class="col-sm-12 control-label">Status</label>		
										<div id="label-switch" class="make-switch" data-on-label="Active" data-off-label="InActive" style="min-width: 200px;">
											<input type="checkbox" name="status" value="ACTIVE" id="status" checked>
										</div>
									</div>
									-->
									<input type="hidden" class="form-control" maxlength="7" value="0" id="adult_price" name="adult_price" data-validate="required" data-message-required="Please Enter the Adult Price" />
								</div>	
								   <div id="child_group">								   
								   </div>
								  <div class="form-group"> 
								  <div id="child_group1">
								   <?php if($settings[0]->child_group_a != ''){ ?>
										<div class="col-sm-2">
											<label for="field-1" class="col-sm-12 control-label">Infant Price/ Person (<?php echo $settings[0]->child_group_a; ?>)</label>									
											<input type="text" class="form-control" maxlength="7" id="child_price_ge_a" value="<?php if(isset($child_price_ge_a)) { echo $child_price_ge_a;} ?>" name="child_price_ge_a" data-message-required="Please Enter the Child Price" />
										</div>
									<?php } ?>
									<?php if($settings[0]->child_group_b != ''){ ?>
										<div class="col-sm-2">
											<label for="field-1" class="col-sm-12 control-label">Child Price/ Person (<?php echo $settings[0]->child_group_b; ?>)</label>									
											 <input type="text" class="form-control" maxlength="7" id="child_price_ge_b" value="<?php if(isset($child_price_ge_b)) { echo $child_price_ge_b; } ?>" name="child_price_ge_b" data-message-required="Please Enter the Child Price" />
										</div>
									<?php } ?>
																	
									
								</div>			
							</div>		
								   <div class="form-group" id="extra_bed_price">
								      <div class="col-sm-2">
										<label for="field-2" class="col-sm-12 control-label">Extra Bed Price for Child </label>									
										<input type="text" class="form-control"  maxlength="7" id="child_extra_bed_price" value="<?php if(isset($child_extra_bed_price)) { echo $child_extra_bed_price;} else{ echo ""; }?>" name="child_extra_bed_price"   data-rule-number='true'/>										 
										<?php echo form_error('child_extra_bed_price',  '<span for="field-1" class="tab_error_color">', '</span>'); ?>
									  </div>
									  <div class="col-sm-2">			 
										<label for="field-2" class="col-sm-12 control-label">Extra Bed Price for Adult </label>									
										<input type="text" class="form-control"  maxlength="7" id="adult_extra_bed_price" name="adult_extra_bed_price" value="<?php if(isset($adult_extra_bed_price)) { echo $adult_extra_bed_price;}else{ echo ""; } ?>"  data-rule-number='true'/>
										<?php echo form_error('adult_extra_bed_price',  '<span for="field-1" class="tab_error_color">', '</span>'); ?>
									  </div>	
									</div>									
								
																			
								<div class="form-group">									
                                    <div class="form-group">
                                     <label for="field-2" class="col-sm-2 control-label">Week Day Price</label>									
                                    </div>								  							     	
									<div class="col-sm-2">
										<label for="field-2" class="col-sm-16 control-label">Single Room Price</label>									
										<input type="text" class="form-control" id="week_sgl_price" maxlength="7" name="week_sgl_price" value="<?php if(isset($week_sgl_price)) { echo $week_sgl_price;} ?>"  data-validate="required" data-message-required="Please Enter the Single Room Price" />
										<?php echo form_error('week_sgl_price',  '<span for="field-1" class="tab_error_color">', '</span>'); ?>
									</div>
									<div class="col-sm-3">
										<label for="field-2" class="col-sm-12 control-label">Adult Breakfast(Include/Exclude)</label>									
										<select name="week_single_adult_bf" id="week_single_adult_bf" class="form-control">
											<option value="1" <?php if(isset($week_single_adult_bf)){ if($week_single_adult_bf == "1"){ echo "selected"; } } ?> >Include</option>
										 	<option value="0" <?php if(isset($week_single_adult_bf)){ if($week_single_adult_bf == "0"){ echo "selected"; } } ?>>Exclude</option>										 
										</select> 	
									</div>
									<div class="col-sm-3">
										<label for="field-2" class="col-sm-12 control-label">Child Breakfast(Include/Exclude)</label>									
										<select name="week_single_child_bf" id="week_single_child_bf" class="form-control">
											<option value="1" <?php if(isset($week_single_child_bf)){ if($week_single_child_bf == "1"){ echo "selected"; } } ?>>Include</option>
										 	<option value="0" <?php if(isset($week_single_child_bf)){ if($week_single_child_bf == "0"){ echo "selected"; } } ?>>Exclude</option>										 
										</select> 	
									</div>																			
								  </div>
								  <div class="form-group">									
								 	 <div class="col-sm-2">
										<label for="field-1" class="col-sm-14 control-label">Double Room Price</label>									
										<input type="text" class="form-control" id="week_dbl_price" value="<?php if(isset($week_dbl_price)) { echo $week_dbl_price; } ?>" maxlength="7" name="week_dbl_price" />
										<?php echo form_error('week_dbl_price',  '<span for="field-1" class="tab_error_color">', '</span>'); ?>
									 </div>
									<div class="col-sm-3">
										<label for="field-2" class="col-sm-12 control-label">Adult Breakfast(include/Exclude)</label>									
										<select name="week_double_adult_bf" id="week_double_adult_bf" class="form-control">
											<option value="1" <?php if(isset($week_double_adult_bf)){ if($week_double_adult_bf == "1"){ echo "selected"; } } ?> >Include</option>
										 	<option value="0" <?php if(isset($week_double_adult_bf)){ if($week_double_adult_bf == "0"){ echo "selected"; } } ?>>Exclude</option>										 
										</select> 	
									</div>
									<div class="col-sm-3">
										<label for="field-2" class="col-sm-12 control-label">Child Breakfast(include/Exclude)</label>									
										<select name="week_double_child_bf" id="week_double_child_bf" class="form-control">
											<option value="1" <?php if(isset($week_double_child_bf)){ if($week_double_child_bf == "1"){ echo "selected"; } } ?>>Include</option>
										 	<option value="0" <?php if(isset($week_double_child_bf)){ if($week_double_child_bf == "0"){ echo "selected"; } } ?>>Exclude</option>										 
										</select> 	
									</div>											
								   </div>	
								   <div class="form-group">									
								 	 <div class="col-sm-2">
										<label for="field-1" class="col-sm-14 control-label">Triple Room Price</label>									
										<input type="text" class="form-control" id="week_trp_price" value="<?php if(isset($week_trp_price)) { echo $week_trp_price; } ?>" maxlength="7" name="week_trp_price" />
										<?php echo form_error('week_trp_price',  '<span for="field-1" class="tab_error_color">', '</span>'); ?>
									 </div>
									<div class="col-sm-3">
										<label for="field-2" class="col-sm-12 control-label">Adult Breakfast(include/Exclude)</label>									
										<select name="week_trp_adult_bf" id="week_trp_adult_bf" class="form-control">
											<option value="1" <?php if(isset($week_trp_adult_bf)){ if($week_trp_adult_bf == "1"){ echo "selected"; } } ?> >Include</option>
										 	<option value="0" <?php if(isset($week_trp_adult_bf)){ if($week_trp_adult_bf == "0"){ echo "selected"; } } ?>>Exclude</option>										 
										</select> 	
									</div>
									<div class="col-sm-3">
										<label for="field-2" class="col-sm-12 control-label">Child Breakfast(include/Exclude)</label>									
										<select name="week_trp_child_bf" id="week_trp_child_bf" class="form-control">
											<option value="1" <?php if(isset($week_trp_child_bf)){ if($week_trp_child_bf == "1"){ echo "selected"; } } ?>>Include</option>
										 	<option value="0" <?php if(isset($week_trp_child_bf)){ if($week_trp_child_bf == "0"){ echo "selected"; } } ?>>Exclude</option>										 
										</select> 	
									</div>											
								   </div>
								   <div class="form-group">									
								 	 <div class="col-sm-2">
										<label for="field-1" class="col-sm-14 control-label">Quad Room Price</label>									
										<input type="text" class="form-control" id="week_quad_price" value="<?php if(isset($week_quad_price)) { echo $week_quad_price; } ?>" maxlength="7" name="week_quad_price" />
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
										<label for="field-1" class="col-sm-14 control-label">Hex Room Price</label>									
										<input type="text" class="form-control" id="week_hex_price" value="<?php if(isset($week_hex_price)) { echo $week_hex_price; } ?>" maxlength="7" name="week_hex_price" />
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
								   </div>
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
										<label for="field-2" class="col-sm-16 control-label">Single Room Price</label>									
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
										<label for="field-2" class="col-sm-14 control-label">Double Room Price</label>									
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
										<label for="field-2" class="col-sm-14 control-label">Triple Room Price</label>									
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
								<div class="form-group">	
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
								</div>
								<div class="form-group">	
									<div  id="weekend_bed_room_count">
									</div>									
								</div>	
							</div>						
								<div class="form-group">
									<div class="col-md-2">
										<button type="submit" class="btn btn-success">Add</button>
									</div>
								</div>								
							</form>
						</div>
					</div>				
				</div>
			</div>
			<!-- Footer -->
			<?php $this->load->view('general/footer');	?>				
		</div>				
			
	</div>
	<!-- Bottom Scripts -->
	<?php $this->load->view('general/load_js');	?>
	<script src="<?php echo base_url(); ?>assets/js/bootstrap-switch.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/jquery.validate.min.js"></script>	
	<script src="<?php echo base_url(); ?>assets/js/fileinput.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/selectboxit/jquery.selectBoxIt.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/ckeditor/ckeditor.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/ckeditor/adapters/jquery.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/daterangepicker/moment.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/daterangepicker/daterangepicker.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/select2/select2.min.js"></script>
	<script>
		
		
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
				url:'<?php echo base_url();?>seasons/get_room_type/'+hotel_id + "/"+ room_type_id,				
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
			  url:'<?php echo base_url();?>hotel/get_child_group/'+hotel_id + '/' +child_price,
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
				url:'<?php echo base_url();?>seasons/get_season_room_type/'+room_type_id + "/" + seasons_id,				
				 success: function(data, textStatus, jqXHR) {					
					//alert(data);	  
					select1.html('');
					select1.html(data);						
					select1.trigger("chosen:updated");              
				 }
			    });	 	
			    var data1 = "";
		  	    $.ajax({
			  	url:'<?php echo base_url();?>roomrate/get_extra_bed_avail/'+room_type_id,
			  	dataType: "json",
			  	 success: function(data, textStatus, jqXHR) {
				  data1 = data.extra_bed;
				  data2 = data.no_of_room;
				  //alert(data2);				  
				  if(data1 == "yes"){
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
				if(gst_markup == "Inclusive")
					$('#gst_markup').val('Exclusive');
				else
					$('#gst_markup').val('Inclusive');
			});
			
			$('#gst').change(function(){
				var gst = $('#gst').val();
				if(gst == "Inclusive")
					$('#gst').val('Inclusive');
				else
					$('#gst').val('Exclusive');
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
				if(service_charge == "Inclusive")
					$('#service_charge').val('Inclusive');
				else
					$('#service_charge').val('Exclusive');
				//alert($('#service_charge').val());
			});
			$('#room_details_id').change(function(){
				var room_details_id = $('#room_details_id').val();
				$.ajax({
					url:'<?php echo base_url();?>roomrate/get_extra_bed/'+room_details_id,
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
								  '</div>'+
								  '</div>');	
			$('.daterange').daterangepicker();								  							  
			id = parseInt(id)+1;
			$('#rows_cnt').val(id);																

		}

		function removeLastRoom1(v){
			var id = $('#rows_cnt').val();
			$('#extra_date .form-group').last().remove();
			if(id <= 1) {
				$("#extra_date").css({'display':'none'});
			}
			id = parseInt(id)-1;
			$('#rows_cnt').val(id);
		}	

	
		function select_room_type(hotel_id){
			var select = $('#room_details_id');
			if(hotel_id != ""){
				$.ajax({
				url:'<?php echo base_url();?>seasons/get_room_type/'+hotel_id,				
				success: function(data, textStatus, jqXHR) {					
					//alert(data);	  
					select.html('');
					select.html(data);						
					select.trigger("chosen:updated");              
				}
			  });	 	
			  var data1 = "";
		  	  $.ajax({
			  url:'<?php echo base_url();?>hotel/get_child_group/'+hotel_id,
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
				url:'<?php echo base_url();?>seasons/get_season_room_type/'+room_type_id,				
				 success: function(data, textStatus, jqXHR) {					
					//alert(data);	  
					select.html('');
					select.html(data);						
					select.trigger("chosen:updated");              
				 }
			    });	 	
			    var data1 = "";
		  	    $.ajax({
			  	url:'<?php echo base_url();?>roomrate/get_extra_bed_avail/'+room_type_id,
			  	dataType: "json",
			  	 success: function(data, textStatus, jqXHR) {
				  data1 = data.extra_bed;
				  data2 = data.no_of_room;
				  //alert(data2);				  
				  if(data1 == "yes"){
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
		
		function select_date(id){			
			var $select = $('#room_details_id');
			var $select1 = $('#seasons_details_id');
			$.ajax({
				url:'<?php echo base_url();?>roomrate/get_season_date/'+id,
				dataType: "json",
				success: function(data) {
					console.log(data);
					$('#date_rane_rate').val(data);
				}
			});
		}

		function select_room(hotelId){			
			var $select = $('#room_details_id');
			var $select1 = $('#seasons_details_id');
			$.ajax({
				url:'<?php echo base_url();?>roomrate/get_room_data1/'+hotelId,
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
			  url:'<?php echo base_url();?>hotel/get_child_group/'+hotelId,
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
   
<?php 
if(isset($GET) && !empty($GET))
{
?>
	<script type="text/javascript">
		$(document).ready(function(){
		//alert('<?=$selected_hotel_detail_id?>');
		select_room_type('<?=$selected_hotel_detail_id?>')
		});
	 </script>
<?php
}
?>

</body>
</html>
