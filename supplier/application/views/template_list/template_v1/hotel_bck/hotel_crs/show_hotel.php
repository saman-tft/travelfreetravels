<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">	
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="description" content="Provab Admin Panel" />
	<meta name="author" content="" />	
	<title><?php echo PAGE_TITLE; ?> | Hotel Management</title>	
	<!-- Load Default CSS and JS Scripts -->
	<?php $this->load->view('general/load_css');	?>
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/selectboxit/jquery.selectBoxIt.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/select2/select2-bootstrap.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/select2/select2.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/daterangepicker/daterangepicker-bs3.css">
		
</head>
<body  class="page-body <?php if(isset($transition)){ echo $transition; } ?>" data-url="<?php echo PROVAB_URL; ?>">
	<div class="page-container <?php if(isset($header) && $header == 'header_top'){ echo "horizontal-menu"; } ?> <?php if(isset($header) && $header == 'header_right'){ echo "right-sidebar"; } ?> <?php if(isset($sidebar)){ echo $sidebar; } ?>">
			<?php if(isset($header) && $header == 'header_top'){ $this->load->view('general/header_top'); }else{ $this->load->view('general/left_menu'); }	?>
		<div class="main-content">
			<?php if(!isset($header) || $header != 'header_top'){ $this->load->view('general/header_left'); } ?>
			<?php $this->load->view('general/top_menu');	?>
			<hr />
			<ol class="breadcrumb bc-3">						
				<li><a href="<?php echo site_url()."/dashboard/dashboard"; ?>"><i class="entypo-home"></i>Home</a></li>
				<li><a href="<?php echo site_url()."/hotel/hotel_crs_list"; ?>">Hotel Management</a></li>
				<li class="active"><strong>Hotel Details</strong></li>
			</ol>
			<div class="row">
				<div class="col-md-12">					
					<div class="panel panel-primary" data-collapsed="0">					
						<div class="panel-heading">
							<div class="panel-title">Hotel Details</div>							
							<div class="panel-options">
								<a href="#sample-modal" data-toggle="modal" data-target="#sample-modal-dialog-1" class="bg"><i class="entypo-cog"></i></a>
								<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
								<a href="#" data-rel="reload"><i class="entypo-arrows-ccw"></i></a>
								<a href="#" data-rel="close"><i class="entypo-cancel"></i></a>
							</div>
						</div>						
						<div class="panel-body"><!-- <?php echo site_url()."/hotel/save_hotel_information/".$hotels_list[0]->hotel_details_id; ?> -->
							<div class="form-wizard form-horizontal form-groups-bordered">
							<div class="steps-progress"><div class="progress-indicator"></div></div>
							<ul>
								<li class="active" id="step1"><a href="#tab_hotel" data-toggle="tab"><span>1</span>Hotel Basic Info</a></li>
								<li id="step2"><a href="#tab_contact" data-toggle="tab"><span>2</span>My Contact Info</a></li>
<!--
								<li id="step4"><a href="#tab_room" data-toggle="tab"><span>3</span>Room Details</a></li>
								<li id="step6"><a href="#tab_room_management" data-toggle="tab"><span>4</span>Room Management</a></li>
-->
								<!--<li id="step6"><a href="#tab_room_rate" data-toggle="tab"><span>5</span>Room RateManagement</a></li>-->
							</ul>
							<div class="tab-content">
								<div class="tab-pane active" id="tab_hotel">
									<div class="form-group">
										<label for="field-1" class="col-sm-3 control-label">Hotel Name</label>									
										<div class="col-sm-5">
											<input type="text" class="form-control" id="hotel_name" name="hotel_name" placeholder="Hotel Name" data-validate="required" data-message-required="Please enter the Hotel Name" value="<?php echo $hotels_list[0]->hotel_name; ?>" readonly>
										</div>
									</div>
									<div class="form-group">
										<label for="field-1" class="col-sm-3 control-label">Hotel Code</label>									
										<div class="col-sm-5">
											<input type="text" class="form-control" id="hotel_code" name="hotel_code" placeholder="Hotel Code" data-validate="required" data-message-required="Please enter the Hotel Code" value="<?php echo "NASHO".$hotels_list[0]->hotel_details_id; ?>" readonly>
										</div>
									</div>
									<div class="form-group">
										<label for="field-1" class="col-sm-3 control-label">Star Rating</label>									
										<div class="col-sm-5">
											<select id="star_rating" name="star_rating" class="selectboxit" disabled>
												<option value="" data-iconurl="" <?php if($hotels_list[0]->star_rating=='') echo 'Selected'; ?>>Select Star Rating</option>
												<option value="0" data-iconurl="" <?php if($hotels_list[0]->star_rating==0) echo 'Selected'; ?>>0</option>
												<option value="1" data-iconurl="" <?php if($hotels_list[0]->star_rating==1) echo 'Selected'; ?>>1</option>
												<option value="2" data-iconurl="" <?php if($hotels_list[0]->star_rating==2) echo 'Selected'; ?>>2</option>
												<option value="3" data-iconurl="" <?php if($hotels_list[0]->star_rating==3) echo 'Selected'; ?>>3</option>
												<option value="4" data-iconurl="" <?php if($hotels_list[0]->star_rating==4) echo 'Selected'; ?>>4</option>
												<option value="5" data-iconurl="" <?php if($hotels_list[0]->star_rating==5) echo 'Selected'; ?>>5</option>
											</select>
										</div>
									</div>
									<div class="form-group">
                                        <label for="field-1" class="col-sm-3 control-label">Country</label>									
                                        <div class="col-sm-5">                                            
                                            <select name="country" id="country" onchange="select_city(this.value)" class="form-control" disabled>
                                                <option value="0">Select Country</option>                                                    
                                                <?php for ($c = 0; $c < count($country); $c++) { ?>
                                                    <option value="<?php echo $country[$c]->country_id; ?>" 
                                                    <?php if($hotels_list[0]->country_id==$country[$c]->country_id) echo "Selected"; ?> data-iconurl=""><?php echo $country[$c]->country_name . " (" . $country[$c]->iso3_code . ")"; ?></option>
                                                <?php } ?>
                                            </select>
                                            <?php echo form_error('country_id', '<span for="field-1" class="validate-has-error">', '</span>'); ?>
                                        </div>                                        
                                </div>  

                                <div class="form-group" id="api">
									<label for="field-1"  class="col-sm-3 control-label">City Name</label>									
									<div class="col-sm-5">                                      
										<select name="city_name"  class="form-control" id="city_name" disabled>											
										<option value="0">Select</option>
										</select>
                                        <?php echo form_error('city_name',  '<span for="field-1" class="validate-has-error">', '</span>'); ?>
									</div>
								 </div>	
									<div class="form-group">
										<label for="field-1" class="col-sm-3 control-label">Hotel Address</label>
										<div class="col-sm-5">
											<textarea class="form-control"  name="hotel_address" placeholder="Hotel Address" data-message-required="Please enter the Hotel Address" readonly><?php echo $hotels_list[0]->hotel_address; ?></textarea>
											<input type="hidden" id="hotel_latitude" name="hotel_latitude" value="<?php echo $hotels_list[0]->latitude; ?>"/>
											<input type="hidden" id="hotel_longitude" name="hotel_longitude" value="<?php echo $hotels_list[0]->longitude; ?>"/>
										</div>
									</div>
									<div class="form-group">
										<label for="field-1" class="col-sm-3 control-label">Thumb Image</label>
										<div class="col-sm-5">
											<div class="fileinput fileinput-new" data-provides="fileinput">
												<div class="fileinput-new thumbnail" data-trigger="fileinput">
													<?php if($hotels_list[0]->thumb_image==''){ ?>
													<img style = "width:140px; heigth:120px" src="<?php echo base_url()."assets/images/".MAIN_LOGO; ?>" alt="API Logo">
													<?php } else { ?>
													<img src="<?php echo base_url(); ?>uploads/hotel_images/<?php echo $hotels_list[0]->thumb_image; ?>" alt="Hotel Logo">
													<?php } ?>
												</div>
												<!--<div class="fileinput-preview fileinput-exists thumbnail"></div>
												<div>
													<span class="btn btn-white btn-file">
														<span class="fileinput-new">Select image</span>
														<span class="fileinput-exists">Change</span>
														<input type="file" id="hotel_image" name="hotel_image" accept="image/*">
													</span>
													<a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput">Remove</a>
												</div>-->
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label">&nbsp;</label>									
										<div class="col-sm-5"><a id="complete_hotel_info" data-toggle="tab" href=""><button type="button" class="btn btn-success" id="hotel_basic_info">Next</button></a></div>
									</div>
								</div>
								<div class="tab-pane" id="tab_contact">
									<form id="contact_form" name="contact_form" method="post"  class="form-horizontal form-groups-bordered validate">
										<div class="panel panel-primary" data-collapsed="0">
											<div class="panel-heading">
												<div class="panel-title">Sale Contact</div>											
												<div class="panel-options">
													<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
													<a href="#" data-rel="reload"><i class="entypo-arrows-ccw"></i></a>
													<a href="#" data-rel="close"><i class="entypo-cancel"></i></a>
												</div>
											</div>
											<div class="panel-body">
												<div class="form-group">
													<label for="field-2" class="col-sm-3 control-label">Full User Name</label>

													<?php if($contatc_list != ''){
														$fullName = explode("/",$contatc_list->primary_full_name);
														} ?>						
													<div class="col-sm-2">
														<select name="salution" id="salution" class="selectboxit">
															<option value="Mr." <?php if(isset($fullName[0])) { if($fullName[0]== "Mr."){ echo "selected"; } }?>>Mr.</option>
															<option value="Mrs." <?php if(isset($fullName[0])) { if($fullName[0]== "Mrs."){ echo "selected"; }} ?>>Mrs.</option>
															<option value="Miss" <?php if(isset($fullName[0])) { if($fullName[0]== "Miss"){ echo "selected"; }} ?>>Miss</option>
															<option value="Ms."  <?php if(isset($fullName[0])) { if($fullName[0]== "Ms."){ echo "selected"; }} ?>>Ms.</option>
															<option value="Dr." <?php if(isset($fullName[0])) { if($fullName[0]== "Dr."){ echo "selected"; }} ?>>Dr.</option>
															<option value="Prof." <?php if(isset($fullName[0])) { if($fullName[0]== "Prof."){ echo "selected"; }} ?>>Prof.</option>
															<option value="Rev." <?php  if(isset($fullName[0])) {if($fullName[0]== "Rev."){ echo "selected"; }} ?>>Rev.</option>
															<option value="Other" <?php if(isset($fullName[0])) { if($fullName[0]== "Other"){ echo "selected"; }} ?>>Other</option>
														</select>
													</div>
													<div class="col-sm-2">
														<input type="text" class="form-control" id="first_name" placeholder="First Name" name="first_name" value="<?php if(isset($fullName[1])){ echo $fullName[1]; } ?>" data-validate="required" data-message-required="Please enter the First Name">
													</div>
													<div class="col-sm-2">
														<input type="text" class="form-control" id="middle_name" placeholder="Middle Name" name="middle_name" value="<?php if(isset($fullName[2])){echo $fullName[2]; } ?>">
													</div>
													<div class="col-sm-2">
														<input type="text" class="form-control" id="last_name" name="last_name" value="<?php if(isset($fullName[3])){echo $fullName[3]; } ?>" >
													</div>
												</div>
												<div class="form-group" id="userEmailGroup">
													<label class="col-sm-3 control-label">Email</label>											
													<div class="col-sm-5">
														<div class="input-group">
															<span class="input-group-addon"><i class="entypo-mail"></i></span>
															<input type="text" class="form-control" id="email_id" name="email_id" value="<?php if($contatc_list != ''){ echo $contatc_list->primary_mail_id; } ?>" data-validate="email,required" data-message-required="Please enter the Valid Email Id">
														</div>
													</div>
												</div>
												<div class="form-group">
													<label for="field-1" class="col-sm-3 control-label">Phone Number</label>									
													<div class="col-sm-5">
														<div class="input-group">
															<span class="input-group-addon"><i class="entypo-phone"></i></span>
															<input type="text" class="form-control landline" id="phone_no" name="phone_no" value="<?php if($contatc_list != ''){ echo $contatc_list->primary_phone_no; } ?>" data-validate="number" data-message-required="Please enter the Phone Number">
														</div>
													</div>
												</div>
												<div class="form-group">
													<label for="field-1" class="col-sm-3 control-label">Mobile Number</label>									
													<div class="col-sm-5">
														<div class="input-group">
															<span class="input-group-addon"><i class="entypo-mobile"></i></span>
															<input type="text" class="form-control" id="mobile_no" name="mobile_no" value="<?php if($contatc_list != ''){ echo $contatc_list->primary_mobile_no; } ?>" data-validate="required,number" data-message-required="Please enter the Mobile Number">
														</div>
													</div>
												</div>
												<div class="form-group">
													<label for="field-1" class="col-sm-3 control-label">Address</label>									
													<div class="col-sm-5">
														<div class="input-group">
															<span class="input-group-addon"><i class="entypo-doc-text-inv"></i></span>
															<input type="text" class="form-control" id="address" name="address" value="<?php if($contatc_list != ''){ echo $contatc_list->primary_address; } ?>" data-validate="required" data-message-required="Please enter the Address" />
														</div>
													</div>
												</div>
												<div class="form-group">
													<label for="field-1" class="col-sm-3 control-label">City</label>									
													<div class="col-sm-5">
														<div class="input-group">
															<span class="input-group-addon"><i class="entypo-address"></i></span>
															<input type="text" class="form-control" id="city" name="city" value="<?php  if($contatc_list != ''){echo $contatc_list->primary_city_name; } ?>" data-validate="required" data-message-required="Please enter the City">
														</div>
													</div>
												</div>
												<div class="form-group">
													<label for="field-1" class="col-sm-3 control-label">State Name</label>									
													<div class="col-sm-5">
														<div class="input-group">
															<span class="input-group-addon"><i class="entypo-address"></i></span>
															<input type="text" class="form-control" id="state_name" name="state_name" value="<?php if($contatc_list != ''){ echo $contatc_list->primary_state_name; } ?>" data-validate="required" data-message-required="Please enter the State Name">
														</div>
													</div>
												</div>
												<div class="form-group">
													<label for="field-1" class="col-sm-3 control-label">Zip Code</label>									
													<div class="col-sm-5">
														<div class="input-group">
															<span class="input-group-addon"><i class="entypo-address"></i></span>
															<input type="text" class="form-control" id="zip_code" name="zip_code" value="<?php if($contatc_list != ''){ echo $contatc_list->primary_pincode; } ?>"  data-validate="required" data-message-required="Please enter the Zip Code" />
														</div>
													</div>
												</div>
												<div class="form-group">
													<label for="field-1" class="col-sm-3 control-label">Country</label>									
													<div class="col-sm-5">
														<select name="country" id="country" class="selectboxit">
														 <option value="0">Select Country</option>                                                    
                                                		<?php for ($c = 0; $c < count($country); $c++) { ?>
                                                    		<option value="<?php echo $country[$c]->country_id; ?>" 
                                                    			<?php if($contatc_list->primary_country_id==$country[$c]->country_id) echo "Selected"; ?> data-iconurl=""><?php echo $country[$c]->country_name . " (" . $country[$c]->iso3_code . ")"; ?></option>
                                                			<?php } ?>
														</select>
													</div>
												</div>
												<!-- <div id="map_canvas" style="height:400px;width:100%;margin: 0.6em;clear:both"></div>-->
												</div>
												
										</div>
										<div class="panel panel-primary" data-collapsed="0">
											<div class="panel-heading">
												<div class="panel-title">Reservation Contact</div>											
												<div class="panel-options">
													<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
													<a href="#" data-rel="reload"><i class="entypo-arrows-ccw"></i></a>
													<a href="#" data-rel="close"><i class="entypo-cancel"></i></a>
												</div>
											</div>
											<div class="panel-body">	
												<div class="form-group">

													<?php if($contatc_list != ''){ $fullName1 = explode("/",$contatc_list->primary_full_name); } ?>	
													<label for="field-2" class="col-sm-3 control-label">Full User Name</label>									
													<div class="col-sm-2">
														<select name="secondary_salution" id="secondary_salution" class="selectboxit">
															<option  value="Mr." <?php  if($contatc_list != ''){ if($fullName1[0]== "Mr."){ echo "selected"; } } ?>>Mr.</option>
															<option value="Mrs." <?php  if($contatc_list != '') {if($fullName1[0]== "Mrs."){ echo "selected"; } }?>>Mrs.</option>
															<option value="Miss" <?php   if($contatc_list != ''){if($fullName1[0]== "Miss"){ echo "selected"; } } ?>>Miss</option>
															<option value="Ms."  <?php  if($contatc_list != '') {if($fullName1[0]== "Ms."){ echo "selected"; } } ?>>Ms.</option>
															<option value="Dr." <?php  if($contatc_list != ''){ if($fullName1[0]== "Dr."){ echo "selected"; } } ?>>Dr.</option>
															<option value="Prof." <?php  if($contatc_list != ''){if($fullName1[0]== "Prof."){ echo "selected"; }} ?>>Prof.</option>
															<option value="Rev." <?php  if($contatc_list != ''){if($fullName1[0]== "Rev."){ echo "selected"; }}?>>Rev.</option>
															<option value="Other" <?php  if($contatc_list != ''){if($fullName1[0]== "Other"){ echo "selected"; }} ?>>Other</option>
														</select>
													</div>
													<div class="col-sm-2">
														<input type="text" class="form-control" id="secondary_first_name" name="secondary_first_name" value="<?php if(isset($fullName1[1])){ echo $fullName1[1]; } ?>" data-validate="required" data-message-required="Please enter the First Name">
													</div>
													<div class="col-sm-2">
														<input type="text" class="form-control" id="secondary_middle_name" name="secondary_middle_name" value="<?php if(isset($fullName1[2])){ echo $fullName1[2]; } ?>" >
													</div>
													<div class="col-sm-2">
														<input type="text" class="form-control" id="secondary_last_name" name="secondary_last_name" value="<?php if(isset($fullName1[3])){ echo $fullName1[3]; } ?>" >
													</div>
												</div>
												<div class="form-group" id="userEmailGroup">
													<label class="col-sm-3 control-label">Email</label>											
													<div class="col-sm-5">
														<div class="input-group">
															<span class="input-group-addon"><i class="entypo-mail"></i></span>
															<input type="text" class="form-control" id="secondary_email_id" name="secondary_email_id" value="<?php if($contatc_list != ''){ echo $contatc_list->secondary_mail_id; }  if($contatc_list->secondary_mail_id == ''){ echo $hotels_list[0]->email; }?>" data-validate="email,required" data-message-required="Please enter the Valid Email Id">
														</div>
													</div>
												</div>	
												<div class="form-group">
													<label for="field-1" class="col-sm-3 control-label">Phone Number</label>									
													<div class="col-sm-5">
														<div class="input-group">
															<span class="input-group-addon"><i class="entypo-phone"></i></span>
															<input type="text" class="form-control" id="secondary_phone_no" name="secondary_phone_no" value="<?php if($contatc_list != ''){ echo $contatc_list->secondary_phone_no; } if($contatc_list->secondary_phone_no == ''){ echo $hotels_list[0]->phone_number; }?>"   data-message-required="Please enter the Phone Number">
														</div>
													</div>
												</div>
												
												<div class="form-group">
													<label class="col-sm-3 control-label">&nbsp;</label>									
													<div class="col-sm-5">
														<a id="complete_contact_info" data-toggle="tab" href=""><button type="submit" class="btn btn-success" id="contact_info">save</button></a>
													</div>
												</div>	
											</div>	
											<!-- <div id="map_canvas2" style="height:400px;width:100%;margin: 0.6em;clear:both"></div> -->
										</div>
									</form>	
								</div>
								
								<div class="tab-pane" id="tab_room_management">
									<div class="form-group" id="room_details_count">
										<table class="table table-bordered ">
											<thead>
												<tr>
													<th>Sl No</th>
													<th>Room Name</th>
													<th>Room Availability Type</th>
													<th>Allotment Date Range</th>
													<th>From</th>
													<th>To</th>
													<th>Room Count</th>
		<!--
													<th>Adult</th>
													<th>Child</th>
		-->
													<th>Actions</th>
												</tr>
											</thead>
											<tbody>
												<?php if($room_count_info!=''){ for($r=0;$r<count($room_count_info);$r++){ ?>
														<tr>
															<td><?php echo ($r+1); ?></td>
															<td><?php echo $room_count_info[$r]->room_type_name; ?></td>
															<td><?php echo $room_count_info[$r]->sale_type; ?></td>
															<td><?php echo $room_count_info[$r]->sale_date_range; ?></td>
															<td><?php echo $room_count_info[$r]->from_date; ?></td>
															<td><?php echo $room_count_info[$r]->to_date; ?></td>
															<td><?php echo $room_count_info[$r]->no_of_room; ?></td>
		<!--
															<td><?php echo $room_count_info[$r]->adult; ?></td>
															<td><?php echo $room_count_info[$r]->child; ?></td>
		-->
															<td class="center">
																<?php if($room_count_info[$r]->status == "ACTIVE"){ ?>
																	<a href="<?php echo site_url()."/hotel/inactive_hotel_room_count/".base64_encode(json_encode($room_count_info[$r]->hotel_room_count_info_id)); ?>/<?php echo base64_encode(json_encode($room_count_info[$r]->hotel_details_id)); ?>" class="btn btn-orange btn-sm btn-icon icon-left"><i class="entypo-eye"></i>InActive</a>
																<?php }else{ ?>
																	<a href="<?php echo site_url()."/hotel/active_hotel_room_count/".base64_encode(json_encode($room_count_info[$r]->hotel_room_count_info_id)); ?>/<?php echo base64_encode(json_encode($room_count_info[$r]->hotel_details_id)); ?>" class="btn btn-green btn-sm btn-icon icon-left"><i class="entypo-check"></i>Active</a>
																<?php } ?>
																<a href="<?php echo site_url()."/hotel/delete_hotel_room_count/".base64_encode(json_encode($room_count_info[$r]->hotel_room_count_info_id)); ?>/<?php echo base64_encode(json_encode($room_count_info[$r]->hotel_details_id)); ?>" class="btn btn-danger btn-sm btn-icon icon-left"><i class="entypo-cancel"></i>Delete</a>
																<a href="<?php echo site_url()."/hotel/edit_hotel_room_count/".base64_encode(json_encode($room_count_info[$r]->hotel_room_count_info_id)); ?>/<?php echo base64_encode(json_encode($room_count_info[$r]->hotel_room_details_id)); ?>/<?php echo base64_encode(json_encode($room_count_info[$r]->hotel_details_id)); ?>" class="btn btn-default btn-sm btn-icon icon-left"><i class="entypo-pencil"></i>Edit</a>
																<a href="<?php echo site_url()."/calendar/show_calendar/".base64_encode(json_encode($room_count_info[$r]->hotel_room_count_info_id)); ?>/<?php echo base64_encode(json_encode($room_count_info[$r]->hotel_room_details_id)); ?>/<?php echo base64_encode(json_encode($room_count_info[$r]->hotel_details_id)); ?>" class="btn btn-default btn-sm btn-icon icon-left"><i class="entypo-menu"></i>Manage</a>
															</td>
														</tr>
												<?php }} ?>
											</tbody>
										</table>
									</div>
									<form id="room_count_info" name="room_details_info" method="post" >
										<h3>Rooms Management</h3>
										<div class="form-group">
											<label class="col-md-2 control-label">Rooms</label>
											<label class="col-md-2 control-label">Room Availability Type</label>
											<label class="col-md-2 control-label">Allotment Date Range</label>
											<label class="col-md-2 control-label">Date Range</label>
											<label class="col-md-1 control-label">Number of Rooms</label>
		<!--
											<label class="col-md-1 control-label">Number of Adults</label>
											<label class="col-md-1 control-label">Number of Children</label>
		-->
										</div>
										<div class="form-group">
											<div class="col-md-2">
												<select class="form-control" name="rooms_type_id[]">
													<?php 
														if($room_list!=''){ for($r=0;$r<count($room_list);$r++){ ?>
															<option value="<?php echo $room_list[$r]->hotel_room_details_id; ?>"><?php echo $room_list[$r]->room_type_name; ?></option>
													<?php }} ?>
												</select>
											</div>
											<div class="col-sm-2">
												<select class="form-control" name="sale_type[]">
													<option value="FreeSaleBasis">Free Sale Basis</option>
													<option value="Allotment">Allotment Basis with a release period</option>
													<option value="OnRequest">ON REQUEST Basis</option>
												</select>
											</div>
											<div class="col-sm-2">
												<input type="text" class="form-control daterange"   name="sale_date_range[]" id="date_range" data-min-date="<?php echo date('m-d-Y');?>" data-validate="required" data-message-required="Please Select the Date Range" />
											</div>
											<div class="col-md-2">
												<input type="text" class="form-control daterange"  name="date_rane[]" id="date_rane" data-min-date="<?php echo date('m-d-Y');?>" data-validate="required" data-message-required="Please Select the Date Range" />
											</div>
											<div class="col-md-1">
												<input name="rooms_tot_units[]" type="number" size="2" class="form-control" value="0"/>
											</div>
											<div class="col-md-1">
												<input name="adults[]" type="hidden" size="2" class="form-control" value="0"/>
											</div>
											<div class="col-md-1">
												<input name="child[]" type="hidden" size="2" class="form-control" value="0"/>
											</div>
										</div>
										<div id="rooms"></div>
										<div class="form-group">
											<div class="col-md-1"><input type="hidden" id="rows_cnt" value="1"/><button type="button" class="btn btn-success" onclick="addMoreRooms(this);">Add</button></div>
											<div class="col-md-2"><button type="button" class="btn btn-success" onclick="removeLastRoom(this);">Remove Last</button></div>
										</div>
										
										<div class="form-group">
											<label class="col-sm-3 control-label">&nbsp;</label>									
											<div class="col-sm-5"><button type="button" onclick="save_room_count()" class="btn btn-success">Save</button></div>
										</div>
									</form>	
								</div>
								<?php if(false){ ?>
								<div class="tab-pane" id="tab_room_rate">
									<form id="room_rate_info" name="room_ratet" >
										<h3>Add Room Rate</h3>
										<div class="form-group">
											<div class="col-md-3">
												<select class="form-control" name="rooms_type_id_rate">
													<?php $rooms = $this->Hotel_Model->get_room_types_list_stat();
														for($r=0; $r<count($room_list); $r++){ ?>
															<option value="<?php echo $room_list[$r]->hotel_room_type_id; ?>"><?php echo $room_list[$r]->room_type_name; ?></option>
													<?php } ?>
												</select>
											</div>
											<div class="col-md-2">
												<input type="text" class="form-control daterange" name="date_rane_rate" data-min-date="<?php echo date('m-d-Y');?>" data-validate="required" data-message-required="Please Select the Date Range" />
											</div>
											<div class="col-md-2">
												<input name="adult_price" type="text" class="form-control" placeholder="Adult Price"/>
											</div>
											<div class="col-md-2">
												<input name="child_price" type="text"  class="form-control" placeholder="Child Price"/>
											</div>
											<div class="col-md-2">
												<button type="button" onclick="save_room_rate()" class="btn btn-success">Add</button>
											</div>
										</div>
									</form>	
									<div class="form-group" id="room_details_rate">
										<table class="table table-bordered ">
											<thead>
												<tr>
													<th>Sl No</th>
													<th>Room Type</th>
													<th>Room Name</th>
													<th>From</th>
													<th>To</th>
													<th>Price</th>
													<th>Actions</th>
												</tr>
											</thead>
											<tbody>
												<?php if($room_rate_info!=''){ for($r=0;$r<count($room_rate_info);$r++){ ?>
														<tr>
															<td><?php echo ($r+1); ?></td>
															<td><?php echo $room_rate_info[$r]->room_type_name; ?></td>
															<td><?php echo $room_rate_info[$r]->room_name; ?></td>
															<td><?php echo $room_rate_info[$r]->from_date; ?></td>
															<td><?php echo $room_rate_info[$r]->to_date; ?></td>
															<td><?php echo $room_rate_info[$r]->price; ?></td>
															<td class="center">
																<?php if($room_count_info[$r]->status == "ACTIVE"){ ?>
																	<a href="<?php echo site_url()."/hotel/inactive_hotel_room_rate/".$room_rate_info[$r]->hotel_room_rate_info_id; ?>/<?php echo base64_encode(json_encode($room_rate_info[$r]->hotel_details_id)); ?>" class="btn btn-orange btn-sm btn-icon icon-left"><i class="entypo-eye"></i>InActive</a>
																<?php }else{ ?>
																	<a href="<?php echo site_url()."/hotel/active_hotel_room_rate/".$room_rate_info[$r]->hotel_room_rate_info_id; ?>/<?php echo base64_encode(json_encode($room_rate_info[$r]->hotel_details_id)); ?>" class="btn btn-green btn-sm btn-icon icon-left"><i class="entypo-check"></i>Active</a>
																<?php } ?>
																<a href="<?php echo site_url()."/hotel/delete_hotel_room_rate/".$room_rate_info[$r]->hotel_room_rate_info_id; ?>/<?php echo base64_encode(json_encode($room_rate_info[$r]->hotel_details_id)); ?>" class="btn btn-danger btn-sm btn-icon icon-left"><i class="entypo-cancel"></i>Delete</a>
															</td>
														</tr>
												<?php }} ?>
											</tbody>
										</table>
									</div>							
								</div>
								<?php } ?>
							</div>
					  </div>
				</div>		
			</div>
		</div>
					<!-- Footer -->
					<?php $this->load->view('general/footer');	?>				
				</div>				
				<!-- Chat Module -->
					<?php $this->load->view('general/chat');	?>	
			</div>
			<!-- Bottom Scripts -->
	</div>
	<?php $this->load->view('general/load_js');	?>

	<script src="<?php echo base_url(); ?>assets/js/bootstrap-switch.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/fileinput.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/selectboxit/jquery.selectBoxIt.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/jquery.bootstrap.wizard.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/select2/select2.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/validate/field_validate.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/bootstrap-timepicker.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/bootstrap-datepicker.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/daterangepicker/moment.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/daterangepicker/daterangepicker.js"></script>
	
	<script src="<?php echo base_url(); ?>assets/js/jquery.dataTables.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/datatables/TableTools.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/dataTables.bootstrap.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/datatables/jquery.dataTables.columnFilter.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/datatables/lodash.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/datatables/responsive/js/datatables.responsive.js"></script>
	
	<script src="<?php echo base_url(); ?>assets/js/ckeditor/ckeditor.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/ckeditor/adapters/jquery.js"></script>
	
	
		<script src="<?php echo base_url(); ?>assets/js/bootstrap-switch.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/fileinput.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/selectboxit/jquery.selectBoxIt.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/jquery.bootstrap.wizard.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/select2/select2.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/validate/field_validate.js"></script>
	<script src="http://maps.googleapis.com/maps/api/js"></script>
    
	<script>
		 jQuery(document).ready(function($){
                    var country_id = $( "#country" ).val();
                    var from_selected_city_id = "<?php if(isset($hotels_list[0]->city_details_id)) { echo $hotels_list[0]->city_details_id; } ?>";                                        
                    if (country_id != '') {
                        var select1 = $('#city_name');
                        $.ajax({
                            url: '<?php echo site_url(); ?>/hotel/get_city_name/' + country_id + '/' + from_selected_city_id,
                            success: function (data, textStatus, jqXHR) {                            
                                select1.html('');
                                select1.html(data);
                                select1.trigger("chosen:updated");                                                                             		   			
                        	}
                         });
                    }    
      });

		function select_city(country_id){				 
		 if (country_id != '') {         	  
          var select1 = $('#city_name');          
          $.ajax({
            url: '<?php echo site_url(); ?>/hotel/get_city_name/' + country_id,
            success: function (data, textStatus, jqXHR) {                                    
              select1.html('');
              select1.html(data);
              select1.trigger("chosen:updated");  
          	}
           });         
         }		
		}


		var map;
		var geocoder;
		var mapOptions = { center: new google.maps.LatLng(0.0, 0.0), zoom: 7,
        mapTypeId: google.maps.MapTypeId.ROADMAP };
	
		function initialize() {
			var myOptions = {
                center: new google.maps.LatLng(12.851, 77.659 ),
                zoom: 10,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };

            geocoder = new google.maps.Geocoder();
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
                getAddress(location);
            }

			function getAddress(latLng) {
				geocoder.geocode( {'latLng': latLng},
				function(results, status) {
					if(status == google.maps.GeocoderStatus.OK) {
					  if(results[0]) {	
						document.getElementById("address").value 	= results[0].formatted_address;
						var address = results[0].address_components;
						var zipcode = address[address.length - 1].long_name;
						document.getElementById("city").value 		= results[0].address_components[1]['long_name'];
						document.getElementById("state_name").value 		= results[3].address_components[1]['long_name'];
						document.getElementById("zip_code").value 	= zipcode;						
					  }
					  else {
						document.getElementById("city").value = "No results";
					  }
					}
					else {
					  document.getElementById("city").value = status;
					}
				});
			}
			
			// start secondary map		
			var mapsecondary = new google.maps.Map(document.getElementById("map_canvas2"), myOptions);			
			 google.maps.event.addListener(mapsecondary, 'click', function(event) {
                secondary_placeMarker(event.latLng);
            });

            var secondary_marker;
            function secondary_placeMarker(location) {
                if(secondary_marker){ //on vérifie si le marqueur existe
                    secondary_marker.setPosition(location); //on change sa position
                }else{
                    secondary_marker = new google.maps.Marker({ //on créé le marqueur
                        position: location, 
                        map: mapsecondary
                    });
                }
                secondary_getAddress(location);
            }
			
			function secondary_getAddress(latLng) {
				geocoder.geocode( {'latLng': latLng},
				function(results2, status) {
					console.log(results2);
					if(status == google.maps.GeocoderStatus.OK) {
					  if(results2[0]) {					 
						document.getElementById("secondary_address").value 	= results2[0].formatted_address;
						var address = results2[0].address_components;
						var zipcode = address[address.length - 1].long_name;
						document.getElementById("secondary_address").value 		= results2[0].address_components[1]['long_name'];
						document.getElementById("secondary_state_name").value 		= results2[3].address_components[1]['long_name'];
						document.getElementById("secondary_zip_code").value 	= zipcode;						
					  }
					  else {
						document.getElementById("secondary_city").value = "No results";
					  }
					}
					else {
					  document.getElementById("city").value = status;
					}
				});
			}
			// end secondary map
		}
      google.maps.event.addDomListener(window, 'load', initialize);
</script>

    <script type="text/javascript">
		jQuery(document).ready(function($)
		{
			// Action for Step Wise Next Button
			$('#hotel_basic_info').click(function(){
				if($('#hotel_name').val()==''){
					$('#hotel_name').css('border', '1px solid #f52c2c');
					$('#hotel_name').focus();
					return false;
				}
				if($('#star_rating').selectedIndex==0){
					$('#star_rating').css('border', '1px solid #f52c2c');
					$('#star_rating').focus();
					return false;
				}
				if($('#hotel_image').val()==''){
					$('#hotel_image').css('border', '1px solid #f52c2c');
					$('#hotel_image').focus();
					return false;
				}
				else{
					$('#complete_hotel_info').attr('href','#tab_contact');
					$('li.active').toggleClass( "completed" );
					$('li').siblings().removeClass('active');
					$('li#step1').next().addClass('active');
				}
			});
			
			$('#contact_info').click(function(){              
				if($('#first_name').val()=='' || !isNaN($('#first_name').val())){
					$('#first_name').css('border', '1px solid #f52c2c');
					$('#first_name').focus();
					return false
				}
				if($('#last_name').val()=='' || !isNaN($('#last_name').val())){
					$('#last_name').css('border', '1px solid #f52c2c');
					$('#last_name').focus();
					return false;
				}
				if($('#email_id').val()==''){
					$('#email_id').css('border', '1px solid #f52c2c');
					$('#email_id').focus();
					return false;
				}
				if($('#phone_no').val()=='' || isNaN($('#phone_no').val())){
					$('#phone_no').css('border', '1px solid #f52c2c');
					$('#phone_no').focus();
					return false;
				}
				if($('#mobile_no').val()=='' || isNaN($('#mobile_no').val())){
					$('#mobile_no').css('border', '1px solid #f52c2c');
					$('#mobile_no').focus();
					return false;
				}
				
				if(!$('#copy_main_contact').is(":checked")){
					if($('#secondary_first_name').val()=='' || !isNaN($('#secondary_first_name').val())){
						$('#secondary_first_name').css('border', '1px solid #f52c2c');
						$('#secondary_first_name').focus();
						return false
					}
					if($('#secondary_last_name').val()=='' || !isNaN($('#secondary_last_name').val())){
						$('#secondary_last_name').css('border', '1px solid #f52c2c');
						$('#secondary_last_name').focus();
						return false;
					}
					if($('#secondary_email_id').val()==''){
						$('#secondary_email_id').css('border', '1px solid #f52c2c');
						$('#secondary_email_id').focus();
						return false;
					}
					if($('#secondary_phone_no').val()==''){
						$('#secondary_phone_no').css('border', '1px solid #f52c2c');
						$('#secondary_phone_no').focus();
						return false;
					}
					
					
				} 
				$.ajax({
					type	: "POST",
					url  	:'<?php echo base_url(); ?>hotel/save_contact_details/<?php echo $hotels_list[0]->hotel_details_id; ?>',
					data	: $('#contact_form').serialize(),
					dataType: "json",
					success: function(data)
					{
						if(data.status == 1){
							$('#complete_contact_info').attr('href','#tab_details');
							$('li.active').toggleClass( "completed" );
							$('li').siblings().removeClass('active');
							$('li#step2').next().addClass('active');
							location.reload();	
						}
					}
				});
			});
			
			$('#hotel_details').click(function(){
				if($('#hotel_overview').val()==''){
					$('#hotel_overview').css('border', '1px solid #f52c2c');
					$('#hotel_overview').focus();
					return false;
				}
				if($('#location_information').val()==''){
					$('#location_information').css('border', '1px solid #f52c2c');
					$('#location_information').focus();
					return false;
				}
				if($('#hotel_image1').val()==''){
					$('#hotel_image1').css('border', '1px solid #f52c2c');
					$('#hotel_image1').focus();
					return false;
				}
				else{ 
					$('#complete_hotel_details').attr('href','#tab_room');
					$('li.active').toggleClass( "completed" );
					$('li').siblings().removeClass('active');
					$('li#step3').next().addClass('active');
				}
			});
			
			$('#room_details').click(function(){
				$('#complete_room_details').attr('href','#tab_location');
				$('li.active').toggleClass( "completed" );
				$('li').siblings().removeClass('active');
				$('li#step4').next().addClass('active');
			});
			
			$('#location').click(function(){
				$('#complete_location').attr('href','#tab_room_management');
				$('li.active').toggleClass( "completed" );
				$('li').siblings().removeClass('active');
				$('li#step5').next().addClass('active');
			});
			// Next Button Actions Completed
		});	
		
		function add_room(){      
			$('#room_details').css('display','none');
			$('#add_new_room').css('display','');
		}
		function save_room(){
			
			$.ajax({
				type	: "POST",
				url  	:'<?php echo base_url(); ?>hotel/save_room_details/<?php echo base64_encode(json_encode($hotels_list[0]->hotel_details_id)); ?>',
				data	: $('#room_details_info').serialize(),
				dataType: "json",
				success: function(data){
					if(data.status == 1){
						// $('#room_details').css('display','');
						// $('#add_new_room').css('display','none');
						location.reload();
					}
				}
			});   
			$('#room_details').css('display','');
			$('#add_new_room').css('display','none');
		}
		function save_room_count(){
			
			if($('#date_range').val() == '') {
				$('#date_range').css('border', '1px solid #f52c2c');
				$('#date_range').focus(); 
				return false; 
			}
			
			if($('#date_rane').val() == '') {
				$('#date_rane').css('border', '1px solid #f52c2c');
				$('#date_rane').focus(); 
						return false; 
			}
			
			$.ajax({
				type	: "POST",
				url  	:'<?php echo base_url(); ?>hotel/save_room_count_info/<?php echo $hotels_list[0]->hotel_details_id; ?>',
				data	: $('#room_count_info').serialize(),
				dataType: "json",
				success: function(data){
					if(data.status == 1){
						location.reload();
					}
				}
			});   
			$('#room_details').css('display','');
			$('#add_new_room').css('display','none');
		}
		function save_room_rate(){
			$.ajax({
				type	: "POST",
				url  	:'<?php echo base_url(); ?>hotel/save_room_rate/<?php echo $hotels_list[0]->hotel_details_id; ?>',
				data	: $('#room_rate_info').serialize(),
				dataType: "json",
				success: function(data){
					if(data.status == 1){
						location.reload();
					}
				}
			});   
			$('#room_details').css('display','');
			$('#add_new_room').css('display','none');
		}
		
		
		function addMoreRooms(c) {
			var id = $('#rows_cnt').val();
			$("#rooms").append('<div class="form-group"><div class="col-md-2"><select class="form-control" name="rooms_type_id[]"><?php if($room_list!=''){ for($r=0;$r<count($room_list);$r++){ ?><option value="<?php echo $room_list[$r]->hotel_room_details_id; ?>"><?php echo $room_list[$r]->room_name; ?></option><?php }} ?></select></div><div class="col-sm-2"><select class="form-control" name="sale_type[]"><option value="FreeSaleBasis">Free Sale Basis</option><option value="Allotment">Allotment Basis with a release period</option><option value="OnRequest">ON REQUEST Basis</option></select></div><div class="col-sm-2"><input type="text" class="form-control daterange" name="sale_date_range[]" data-min-date="<?php echo date('m-d-Y');?>" data-validate="required" data-message-required="Please Select the Date Range" /></div><div class="col-md-2"><input type="text" class="form-control daterange" name="date_rane[]" id="date_range_'+id+'" data-min-date="<?php echo date('m-d-Y');?>" data-validate="required" data-message-required="Please Select the Date Range" /></div><div class="col-md-1"><input name="rooms_tot_units[]" type="number" size="2" class="form-control" value="0"/></div></div>');
			$('.daterange').daterangepicker();
			id = id+1;
			$('#rows_cnt').val(id);
		}
		function removeLastRoom(v){
			var id = $('#rows_cnt').val();
			$('#rooms .form-group').last().remove();
			id = id-1;
			$('#rows_cnt').val(id);
		}
	</script>
	<script>
		$(function(){
			$('#domain_status').change(function(){
				var current_status = $('#domain_status').val();
				if(current_status == "ACTIVE")
					$('#domain_status').val('INACTIVE');
				else
					$('#domain_status').val('ACTIVE');
			});
			                        
	                     
			$('#copy_main_contact').click(function(){
				if($(this).is(":checked")){  
					$('#secondary_salution').val($('#salution').val());
					$('#secondary_first_name').val($('#first_name').val());
					$('#secondary_middle_name').val($('#middle_name').val());
					$('#secondary_last_name').val($('#last_name').val());
					$('#secondary_email_id').val($('#email_id').val());
					$('#secondary_phone_no').val($('#phone_no').val());
					$('#secondary_mobile_no').val($('#mobile_no').val());
					$('#secondary_address').val($('#address').val());
					$('#secondary_city').val($('#city').val());
					$('#secondary_state_name').val($('#state_name').val());
					$('#secondary_zip_code').val($('#zip_code').val());
					$('#secondary_country').val($('#country').val());
				}
				else if($(this).is(":not(:checked)")){
					$('#secondary_salution').val('');
					$('#secondary_first_name').val('');
					$('#secondary_middle_name').val('');
					$('#secondary_last_name').val('');
					$('#secondary_email_id').val('');
					$('#secondary_phone_no').val('');
					$('#secondary_mobile_no').val('');
					$('#secondary_address').val('');
					$('#secondary_city').val('');
					$('#secondary_state_name').val('');
					$('#secondary_zip_code').val('');
					$('#secondary_country').val('');
				}
			});
		});
		function select_room(id){
			var hotelId = '<?php echo $hotel_id; ?>'; 
			var $select = $('#room_details_id');
			$.ajax({
				url:'<?php echo base_url();?>hotel/get_room_info/'+id+'/'+hotelId,
				success: function(data, textStatus, jqXHR) {
					$select.html('');
					$select.html('<option value="">Select Any Room</option>'+data);
				}
			});	
		}
		</script>

</body>
</html>
