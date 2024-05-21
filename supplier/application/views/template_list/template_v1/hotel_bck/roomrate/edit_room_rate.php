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
				<li><a href="<?php echo site_url()."/hotel/hotel_crs_list"; ?>">Room Rate Management</a></li>
				<li class="active"><strong>Edit Room Rate Profile</strong></li>
			</ol>
			<div class="row">
				<div class="col-md-12">					
					<div class="panel panel-primary" data-collapsed="0">					
						<div class="panel-heading">
							<div class="panel-title">
								Edit Room Rate
							</div>							
							<div class="panel-options">
								<a href="#sample-modal" data-toggle="modal" data-target="#sample-modal-dialog-1" class="bg"><i class="entypo-cog"></i></a>
								<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
								<a href="#" data-rel="reload"><i class="entypo-arrows-ccw"></i></a>
								<a href="#" data-rel="close"><i class="entypo-cancel"></i></a>
							</div>
						</div>						
						<div class="panel-body">							
							<form id="room_rate_details" method="post" action="<?php echo site_url()."/roomrate/update_room_rate/".base64_encode(json_encode($room_rate_list[0]->hotel_room_rate_info_id)); ?>" class="form-horizontal form-groups-bordered validate" enctype= "multipart/form-data">				
							<?php if($supplier_rights == 1) { ?>
							<input type="hidden" name="supplier_rights" id="supplier_rights" value="<?php echo $supplier_rights; ?>" />
							<?php } else { ?>
							<input type="hidden" name="supplier_rights" id="supplier_rights" value="" />
							<?php } ?>
								<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">Hotel</label>									
									<div class="col-sm-5">
										 <select id="hotel_details_id" name="hotel_details_id" onChange="select_room(this.value);" class="form-control">
											 <option value="0">Select Hotel</option>
											<?php foreach ($hotels_list as $hotel){ ?>
												<option value="<?php echo $hotel->hotel_details_id; ?>" <?php if($hotel->hotel_details_id == $room_rate_list[0]->hotel_details_id ){ echo "selected"; }?> data-iconurl=""><?php echo $hotel->hotel_name; ?></option>
											<?php } ?>
										</select>
									</div>
								</div>		
								<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">Seasons</label>									
									<div class="col-sm-5">
										 <select id="seasons_details_id" name="seasons_details_id" class="form-control">
											<option value="<?php echo $room_rate_list[0]->seasons_details_id; ?>" data-iconurl=""><?php echo $room_rate_list[0]->seasons_name; ?></option>
										 </select>
									</div>
								</div>						
								<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">Room</label>									
									<div class="col-sm-5">
										 <select id="room_details_id" name="room_details_id" class="form-control">
											 <option value="<?php echo $room_rate_list[0]->hotel_room_type_id ?>"><?php echo $room_rate_list[0]->room_type_name; ?></option>
										</select>
									</div>
								</div>						
								<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">Promotion</label>									
									<div class="col-sm-5"> 
										 <input type="text" class="form-control" name="promotion" maxlength="200" id="promotion" data-validate="required" value="<?php echo $room_rate_list[0]->room_promotion; ?>" data-message-required="Please Enter the Promotion" />
									</div>
								</div>										
								<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">Date Range</label>									
									<div class="col-sm-5">
										 <input type="text" class="form-control daterange" id="date_rane_rate" value="<?php echo date('m/d/Y', strtotime($room_rate_list[0]->from_date)); ?> - <?php echo date('m/d/Y', strtotime($room_rate_list[0]->to_date)); ?>" name="date_rane_rate" data-min-date="<?php echo date('m/d/Y');?>" data-validate="required" data-message-required="Please Select the Date Range"  />										 
									</div>
								</div>
								<div class="form-group">
                                    <label for="field-1" class="col-sm-3 control-label">Currency</label>
                                    <div class="col-sm-5">
                                        <select  data-rule-required='true' name='currency' id="currency" data-rule-required='true' class="form-control" required>
                                            <option value="">Choose Currency</option>
                                            <?php

                                            foreach($currency as $currency_key => $currency_value)
                                            { ?>
                                                <option value="<?php echo $hotel->currency; ?>" <?php if($hotel->currency == $currency_value['country'] ){ echo "selected"; }?> data-iconurl=""><?php echo $currency_value['country']; ?></option>

                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
								</div>
								<div class="form-group">
									<div class="col-sm-3">
										<label class="col-sm-7 control-label">GST</label>		
										<div id="label-switch" class="make-switch" data-on-label="Inclusive" data-off-label="Exclusive" style="min-width: 200px;">
											<input type="checkbox" name="gst" value="<?php echo $room_rate_list[0]->gst; ?>" id="gst" <?php if($room_rate_list[0]->gst == "Inclusive"){ ?> checked <?php } ?> >
										</div>
									</div>
									
									<div class="col-sm-3">
										<label class="col-sm-7 control-label">Service Charges</label>		
										<div id="label-switch" class="make-switch" data-on-label="Inclusive" data-off-label="Exclusive" style="min-width: 200px;">
											<input type="checkbox" name="service_charge" value="<?php echo $room_rate_list[0]->service_charge; ?>" id="service_charge" <?php if($room_rate_list[0]->service_charge == "Inclusive"){ ?> checked <?php } ?> >
										</div>
									</div>
									<div class="col-sm-3">
										<label class="col-sm-7 control-label">Status</label>		
										<div id="label-switch" class="make-switch" data-on-label="Active" data-off-label="InActive" style="min-width: 200px;">
											<input type="checkbox" name="status" value="<?php echo $room_rate_list[0]->status; ?>" id="status" <?php if($room_rate_list[0]->status == "ACTIVE"){ ?> checked <?php } ?> >
										</div>
									</div>
									<!--div class="col-sm-2">
										<label for="field-1" class="col-sm-12 control-label">Adult Price / Person</label -->									
										<input type="hidden" class="form-control" value="<?php echo $room_rate_list[0]->adult_price ?>" maxlength="7" id="adult_price" name="adult_price" data-validate="required" data-message-required="Please Enter the Adult Price" />										
									<!-- /div -->
								</div>		
									<div id="child_group" >
										
									</div>
									
									<div class="form-group">
									<div id="child_group1" style="disply:none;">
										<?php if($settings[0]->child_group_a != ''){ ?>
										<div class="col-sm-2">
											<label for="field-1" class="col-sm-12 control-label">Child Price/ Person (<?php echo $settings[0]->child_group_a; ?>)</label>									
											<input type="text" class="form-control" value="<?php echo $room_rate_list[0]->child_price_a ?>" maxlength="7" id="child_price_a" name="child_price_a" data-message-required="Please Enter the Child Price" />
										</div>
									<?php } ?>
									<?php if($settings[0]->child_group_b != ''){ ?>
										<div class="col-sm-2">
											<label for="field-1" class="col-sm-12 control-label">Child Price/ Person (<?php echo $settings[0]->child_group_b; ?>)</label>									
											 <input type="text" class="form-control" value="<?php echo $room_rate_list[0]->child_price_b ?>" maxlength="7" id="child_price_b"  name="child_price_b" data-message-required="Please Enter the Child Price" />
										</div>
									<?php } ?>
									<?php if($settings[0]->child_group_c != ''){ ?>
										<div class="col-sm-2">
											<label for="field-1" class="col-sm-12 control-label">Child Price/ Person (<?php echo $settings[0]->child_group_c; ?>)</label>									
											 <input type="text" class="form-control" value="<?php echo $room_rate_list[0]->child_price_c ?>" maxlength="7" id="child_price_c" name="child_price_c" data-message-required="Please Enter the Child Price" />
										</div>
									<?php } ?>
									<?php if($settings[0]->child_group_d != ''){ ?>
										<div class="col-sm-2">
											<label for="field-1" class="col-sm-12 control-label">Child Price/ Person (<?php echo $settings[0]->child_group_d; ?>)</label>									
											 <input type="text" class="form-control" value="<?php echo $room_rate_list[0]->child_price_d ?>" maxlength="7" id="child_price_d" name="child_price_d" data-message-required="Please Enter the Child Price" />
										</div>
									<?php } ?>
									<?php if($settings[0]->child_group_e != ''){ ?>
										<div class="col-sm-2">
											<label for="field-1" class="col-sm-12 control-label">Child Price/ Person (<?php echo $settings[0]->child_group_e; ?>)</label>									
											 <input type="text" class="form-control" value="<?php echo $room_rate_list[0]->child_price_e ?>" maxlength="7" id="child_price_e" name="child_price_e" data-message-required="Please Enter the Child Price" />
										</div>
									<?php } ?>	
									<?php if($room_rate_list[0]->extra_bed_price != ''){ ?>
										<div class="col-sm-2" id="extra_bed_price1">
											<label for="field-1" class="col-sm-12 control-label">Bed Price</label>									
											 <input type="text" class="form-control" value="<?php echo $room_rate_list[0]->extra_bed_price ?>" maxlength="7" id="extra_bed_price" name="extra_bed_price" data-message-required="Please Enter the Child Price"  data-rule-number='true' id="extra_bed_price"/>
										</div>
									<?php } ?>	
									</div>

										<?php if($room_rate_list[0]->extra_bed_price != ''){ ?>
										<div class="col-sm-2" id="extra_bed_price1">
											<label for="field-1" class="col-sm-12 control-label">Bed Price</label>									
											 <input type="text" class="form-control" value="<?php echo $room_rate_list[0]->extra_bed_price ?>" maxlength="7" id="extra_bed_price" name="extra_bed_price" data-message-required="Please Enter the Child Price"  data-rule-number='true' id="extra_bed_price"/>
										</div>
									<?php } ?>	
									</div>
														
								<div class="form-group">
									<div class="col-sm-2">
										<label for="field-2" class="col-sm-12 control-label">Single Room Price</label>									
										<input type="text" class="form-control" value="<?php echo $room_rate_list[0]->sgl_price ?>" id="sgl_price" maxlength="7" name="sgl_price" data-min-date="<?php echo date('m-d-Y');?>" data-validate="required" data-message-required="Please Enter the Total Price" />
									</div>
									<div class="col-sm-2">
										<label for="dbl_price" class="col-sm-12 control-label">Double Room Price</label>									
										<input type="text" class="form-control" value="<?php echo $room_rate_list[0]->dbl_price ?>" id="dbl_price" maxlength="7" name="dbl_price"  />
									</div>
									<div class="col-sm-2">	 
										<label for="tpl_price" class="col-sm-12 control-label">Tripple Room Price</label>									
										<input type="text" class="form-control" value="<?php echo $room_rate_list[0]->tpl_price ?>" id="tpl_price" maxlength="7" name="tpl_price"  />
									</div>
									<div class="col-sm-2">
										<label for="quad_price" class="col-sm-12 control-label">Quad Room Price</label>									
										<input type="text" class="form-control" value="<?php echo $room_rate_list[0]->quad_price ?>" id="quad_price" maxlength="7" name="quad_price"  />
									</div>
									<div class="col-sm-2">
										<label for="field-2" class="col-sm-12 control-label">Hex Room Price</label>
										<input type="text" class="form-control" value="<?php echo $room_rate_list[0]->hex_price ?>" id="hex_price" maxlength="7" name="hex_price"  />
										<input type="hidden" class="form-control" value="<?php echo $room_rate_list[0]->tax_rate_info_id ?>" name="tax_rate_info_id"  />
									</div>
									<?php if($room_rate_list[0]->extra_bed_price_total != ''){ ?>
									<div class="col-sm-2" id="extra_bed_price_total1">
										<label for="extra_bed_price_total" class="col-sm-12 control-label">Total Bed Price</label>
										<input type="text" class="form-control" value="<?php echo $room_rate_list[0]->extra_bed_price_total ?>" maxlength="7"  id="extra_bed_price_total" name="extra_bed_price_total"   data-rule-number='true'/>
									</div>
									<?php	} ?>
								</div>	
								<div class="form-group">
									<div class="col-md-2">
										<button type="submit" class="btn btn-success">Update</button>
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
	<script>
		
		$(document).ready(function(){ 
			/*
			 var data = $('#date_rane_rate').val();
			 if(data != '0000-00-00-0000-00-00'){
			     var date_array = data.split(" - ");
			     var from_date_array = date_array[0].split("/");
				 var from_date = from_date_array[0]+"-"+from_date_array[1]+"-"+from_date_array[2];
				 var to_date_array = date_array[1].split("/");
				 var todate = to_date_array[0]+"-"+to_date_array[1]+"-"+to_date_array[2];
				  $('#date_rane_rate').daterangepicker({
				       format: 'MM/DD/YYYY',
	                    startDate: date_array[0],
                        endDate: date_array[1],
                        minDate: date_array[0],
                        maxDate: date_array[1]
		     	});
			} 
			*/
			
		});
		
		$(function(){
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
			
				$('#room_rate_details').submit(function() {

						var hotel_type1 = $('#hotel_details_id').val();									
				if(hotel_type1 == "0"){					
					hotel_details_id.style.border = "1px solid #f52c2c";   
					hotel_details_id.focus(); 
					return false; 			
				}

				var hotel_type11 = $('#seasons_details_id').val();						
				if(hotel_type11 == "" || hotel_type11 == "0"){					
					seasons_details_id.style.border = "1px solid #f52c2c";   
					seasons_details_id.focus(); 
					return false; 			
				}

				var hotel_type111 = $('#room_details_id').val();			
				if(hotel_type111 == "" || hotel_type111 == "0" ){					
					room_details_id.style.border = "1px solid #f52c2c";   
					room_details_id.focus(); 
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
				
				/*var domain_name = document.getElementById('child_price_e');	
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
				}*/
				
				var domain_name = document.getElementById('extra_bed_price');	
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
				var domain_name = document.getElementById('sgl_price');	
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
				var domain_name = document.getElementById('dbl_price');	
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
				var domain_name = document.getElementById('tpl_price');	
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
				var domain_name = document.getElementById('hex_price');	
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
					$('#gst').val('Exclusive');
				else
					$('#gst').val('Inclusive');
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
					$('#service_charge').val('Exclusive');
				else
					$('#service_charge').val('Inclusive');
			});
			$('#room_details_id').change(function(){
				var room_details_id = $('#room_details_id').val();
				$.ajax({
					url:'<?php echo base_url();?>roomrate/get_extra_bed/'+room_details_id,
					success: function(data, textStatus, jqXHR) { 
						if(data == "Available"){
						$('#extra_bed_price1').show();
						$('#extra_bed_price_total1').show();
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
				 var from_date = from_date_array[0]+"-"+from_date_array[1]+"-"+from_date_array[2];
				 var to_date_array = date_array[1].split("/");
				 var todate = to_date_array[0]+"-"+to_date_array[1]+"-"+to_date_array[2];
				  $('#date_rane_rate').daterangepicker();
				  $('#date_rane_rate').daterangepicker({
				       format: 'MM/DD/YYYY',
	                    startDate: date_array[0],
                        endDate: date_array[1],
                        minDate: date_array[0],
                        maxDate: date_array[1]
		     	});
					
				}
			});
			*/
			});
			
		});
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
			 var data1;
		  $.ajax({
			  url:'<?php echo base_url();?>hotel/get_child_group/'+hotelId,
			 success: function(data, textStatus, jqXHR) {
				  $('#child_group').html(data);
				}
		   });
		   if(data1 == ''){
			   $('#child_group').css({'dispaly' :  'inherit'});
		   }
		}
		<?php if($room_rate_list[0]->child_price_a != ''){ ?>
			$('#child_price_a').val('<?php echo $room_rate_list[0]->child_price_a; ?>')
		<?php } ?>
		<?php if($room_rate_list[0]->child_price_b != ''){ ?>
			$('#child_price_b').val('<?php echo $room_rate_list[0]->child_price_b; ?>')
		<?php } ?>
		<?php if($room_rate_list[0]->child_price_c != ''){ ?>
			$('#child_price_c').val('<?php echo $room_rate_list[0]->child_price_c; ?>')
		<?php } ?>
		<?php if($room_rate_list[0]->child_price_d != ''){ ?>
			$('#child_price_d').val('<?php echo $room_rate_list[0]->child_price_d; ?>')
		<?php } ?>
		<?php if($room_rate_list[0]->child_price_e != ''){ ?>
			$('#child_price_e').val('<?php echo $room_rate_list[0]->child_price_e; ?>')
		<?php } ?>
	</script>
  
</body>
</html>
