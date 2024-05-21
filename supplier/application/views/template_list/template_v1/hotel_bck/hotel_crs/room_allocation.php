<?php $rooms = $this->Hotel_Model->get_room_types_list_stat($hotel_id); ?>

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
<!-- id="top" oncontextmenu="return false" -->
<body  class="page-body <?php if(isset($transition)){ echo $transition; } ?>" data-url="<?php echo PROVAB_URL; ?>">
	<div class="page-container <?php if(isset($header) && $header == 'header_top'){ echo "horizontal-menu"; } ?> <?php if(isset($header) && $header == 'header_right'){ echo "right-sidebar"; } ?> <?php if(isset($sidebar)){ echo $sidebar; } ?>">
			<?php if(isset($header) && $header == 'header_top'){ $this->load->view('general/header_top'); }else{ $this->load->view('general/left_menu'); }	?>
		<div class="main-content">
			<?php if(!isset($header) || $header != 'header_top'){ $this->load->view('general/header_left'); } ?>
			<?php $this->load->view('general/top_menu');	?>
			<?php if($supplier_rights == 1){
					 $url = site_url()."/supplier_dashboard";
				 } else {
					  $url = site_url()."/dashboard/dashboard";
				 } 
			?>
			<ol class="breadcrumb bc-3">						
				<li><a href="<?php echo $url; ?>"><i class="entypo-home"></i>Home</a></li>
				<li><a href="<?php echo site_url()."/hotel/hotel_crs_list"; ?>">Hotel Management</a></li>
				<li class="active"><strong>Compulsory Meals Details</strong></li>
			</ol>
			<div class="row">
				<div class="col-md-12">					
					<div class="panel panel-primary" data-collapsed="0">					
						<div class="panel-heading">
							<div class="panel-title">Compulsory Meals Details</div>							
							<div class="panel-options">
								<a href="#sample-modal" data-toggle="modal" data-target="#sample-modal-dialog-1" class="bg"><i class="entypo-cog"></i></a>
								<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
								<a href="#" data-rel="reload"><i class="entypo-arrows-ccw"></i></a>
								<a href="#" data-rel="close"><i class="entypo-cancel"></i></a>
							</div>
						</div>						
						<div class="panel-body"><!-- <?php echo site_url()."/hotel/save_hotel_information/".$hotels_list[0]->hotel_details_id; ?> -->
							<div class="form-wizard form-horizontal form-groups-bordered">
							<ul>
<!--
								<li class="active" id="step4"><a href="#meal_plan" data-toggle="tab"><span>1</span>Compulsary Dining Details</a></li>
-->
								<li id="step5"><a href="#tab_room" data-toggle="tab"><span>2</span>Room Details</a></li>
								<li id="step6"><a href="#tab_room_management" data-toggle="tab"><span>3</span>Room Management</a></li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane active" id="meal_plan">
									<div class="main-content" id="room_list">
										<ol class="breadcrumb bc-3">						
											<li class="active"><strong><h3>Compulsory Meals Details</h3></strong></li>
											<li class="active" style="float:right;"><a href="#" onclick="add_meals()">Add New CompulsoryMeal</a></li>
										</ol>
										<div class="form-group" id="me_details">
											
				                              <table class="table table-bordered datatable" width="100%" id="domain_list">
												<thead>
													<tr>
														<th>Sl No</th>
														<th>Hotel Name</th>
														<th>Meal Plan Name</th>
														<th>Date</th>
														<th>Adult Price</th>
														<th>Child Price</th>
														<th>Description</th>
														<th>Actions</th>
													</tr>
												</thead>
												<tbody>
													<?php if($meal_list!=''){  for($r=0;$r<count($meal_list);$r++){ ?>
															<tr>
																<td><?php echo ($r+1); ?></td>
																<td><?php echo $meal_list[$r]->hotel_name; ?></td>
																<td><?php echo $meal_list[$r]->meal_plan_name; ?></td>
																<td><?php echo $meal_list[$r]->date; ?></td>
																<td><?php echo $meal_list[$r]->adult_price; ?></td>
																<td width="20%">
																	<?php if($child_group[0]->child_group_a != "" ) { ?>
																	Child Group(<?php echo $child_group[0]->child_group_a; ?>) : <?php echo $meal_list[$r]->child_price_a; ?> <br />
																	<?php } ?>
																	<?php if($child_group[0]->child_group_b != "" ) { ?>
																	Child Group(<?php echo $child_group[0]->child_group_b; ?>) : <?php echo $meal_list[$r]->child_price_b; ?> <br />
																	<?php } ?>
																	<?php if($child_group[0]->child_group_c != "" ) { ?>
																	Child Group(<?php echo $child_group[0]->child_group_c; ?>) : <?php echo $meal_list[$r]->child_price_c; ?> <br />
																	<?php } ?>
																	<?php if($child_group[0]->child_group_d != "" ) { ?>
																	Child Group(<?php echo $child_group[0]->child_group_d; ?>) : <?php echo $meal_list[$r]->child_price_d; ?> <br />
																	<?php } ?>
																	<?php if($child_group[0]->child_group_e != "" ) { ?>
																	Child Group(<?php echo $child_group[0]->child_group_e; ?>) : <?php echo $meal_list[$r]->child_price_e; ?> <br />
																	<?php } ?>
																	
																</td>																     
																<td><?php echo $meal_list[$r]->description; ?></td>
																<td class="center">
																	<?php if($meal_list[$r]->status == "ACTIVE"){ ?>
																		<a href="<?php echo site_url()."/hotel/inactive_meal_plan/".base64_encode($meal_list[$r]->meal_details_id); ?>/<?php echo base64_encode($meal_list[$r]->hotel_details_id); ?>" ><button type="button" class="btn btn-orange tooltip-primary btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="InActive"><i class="glyphicon glyphicon-eye-open"></i></button></a>
																	<?php }else{ ?>
																		<a href="<?php echo site_url()."/hotel/active_meal_plan/".base64_encode($meal_list[$r]->meal_details_id); ?>/<?php echo base64_encode($meal_list[$r]->hotel_details_id); ?>"><button type="button" class="btn btn-success tooltip-primary btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="Active"><i class="glyphicon glyphicon-ok"></i></button></a>
																	<?php } ?>
																	<a href="<?php echo site_url()."/hotel/delete_meal_plan/".base64_encode($meal_list[$r]->meal_details_id); ?>/<?php echo base64_encode($meal_list[$r]->hotel_details_id); ?>" class="btn btn-danger btn-sm btn-icon icon-left"><i class="entypo-cancel"></i>Delete</a>
																	<a href="<?php echo site_url()."/hotel/edit_meal_plan/".base64_encode($meal_list[$r]->meal_details_id); ?>/<?php echo base64_encode($meal_list[$r]->hotel_details_id); ?>" class="btn btn-default btn-sm btn-icon icon-left"><i class="entypo-pencil"></i>Edit</a>
																</td>
															</tr>
													<?php }} ?>
												</tbody>
											</table>
											<div class="form-group">
												<label class="col-sm-3 control-label">&nbsp;</label>									
												<div class="col-sm-5"><a id="complete_room_details" data-toggle="tab" href=""><button type="button" class="btn btn-success" id="room_details">Next</button></a></div>
											</div>
										</div>
										<div id="add_new_meal" style="display:none;">
											<form id="meal_details_info" name="measl_details_info" method="post" >
												<div class="panel panel-primary" data-collapsed="0">
													<div class="panel-heading">
														<div class="panel-title">Meal Information</div>											
													
													</div>
													<form name="meal_plan" id="meal_plan" type="post" >
													<div class="panel-body">
														<div class="form-group" >
															<label class="col-sm-3 control-label">Meal Name</label>									
															<div class="col-sm-5">
																<input type="text" class="form-control" id="meal_plan_name"  name="meal_plan_name" placeholder="Meal Name" data-validate="" data-message-required="Please enter the Meal Name">
															</div>
														</div>
														
														<div class="form-group" >
															<label class="col-sm-3 control-label">Date</label>									
															<div class="col-sm-5">
																<input type="text" class="form-control datepicker" id="date"  name="date" placeholder="Meal Name" data-validate="" data-message-required="Please enter the Meal Name">
															</div>
														</div>
														
														<div class="form-group" >
															<label class="col-sm-3 control-label">Adult Price</label>									
															<div class="col-sm-5">
																<input type="text" class="form-control" id="adult_price"  name="adult_price" placeholder="Adult Price" data-validate="" data-message-required="Please enter the Meal Name">
															</div>
														</div>
														
														<?php if($child_group[0]->child_group_a != ""){ ?>
														<div class="form-group" >
															<label class="col-sm-3 control-label">Child Price(<?php echo $child_group[0]->child_group_a; ?>)</label>									
															<div class="col-sm-5">
																<input type="text" class="form-control" id="adult_price"  name="child_price_a" placeholder="Child Price" data-validate="" data-message-required="Please enter the Meal Name">
															</div>
														</div>
														<?php } ?>
														
														<?php if($child_group[0]->child_group_b != "") { ?>
														<div class="form-group" >
															<label class="col-sm-3 control-label">Child Price(<?php echo $child_group[0]->child_group_b; ?>)</label>									
															<div class="col-sm-5">
																<input type="text" class="form-control" id="adult_price"  name="child_price_b" placeholder="Child Price" data-validate="" data-message-required="Please enter the Meal Name">
															</div>
														</div>
														<?php } ?>
														<?php if($child_group[0]->child_group_c != "") { ?>
														<div class="form-group" >
															<label class="col-sm-3 control-label">Child Price(<?php echo $child_group[0]->child_group_c; ?>)</label>									
															<div class="col-sm-5">
																<input type="text" class="form-control" id="adult_price"  name="child_price_c" placeholder="Child Price" data-validate="" data-message-required="Please enter the Meal Name">
															</div>
														</div>
														<?php } ?>
														
														<?php if($child_group[0]->child_group_d != "") { ?>
														<div class="form-group" >
															<label class="col-sm-3 control-label">Child Price(<?php echo $child_group[0]->child_group_d; ?>)</label>									
															<div class="col-sm-5">
																<input type="text" class="form-control" id="adult_price"  name="child_price_d" placeholder="Child Price" data-validate="" data-message-required="Please enter the Meal Name">
															</div>
														</div>
													<?php } ?>
														
														<?php if($child_group[0]->child_group_e != "") { ?>
														<div class="form-group" >
															<label class="col-sm-3 control-label">Child Price(<?php echo $child_group[0]->child_group_e; ?>)</label>									
															<div class="col-sm-5">
																<input type="text" class="form-control" id="adult_price"  name="child_price_e" placeholder="Child Price" data-validate="" data-message-required="Please enter the Meal Name">
															</div>
														</div>
														<?php } ?>
																							 
														<div class="form-group">
															<label for="field-1" class="col-sm-3 control-label">Description</label>
															<div class="col-sm-8">
																<textarea class="form-control " name="description"  data-message-required="Please enter the Meal Info"></textarea>
															</div>
														</div>
								
									<div class="form-group">
													<label class="col-sm-3 control-label">&nbsp;</label>									
													<div class="col-sm-5"><button type="button" onclick="save_meal_plan()" class="btn btn-success">save</button></div>
												</div>
													
														
														</div>
													</div>
												</div>
											
											</form>
										</div>
									</div>
								
								<div class="tab-pane " id="tab_room">
									<div class="main-content" id="room_list"><hr />
										<ol class="breadcrumb bc-3">						
											<li class="active"><strong><h3>Room Details</h3></strong></li>
											<li class="active" style="float:right;"><a href="#" onclick="add_room()">Add New Room</a></li>
										</ol>
										<div class="form-group" id="room_details">
											
				                              <table class="table table-bordered datatable" width="100%" id="domain_list">
												<thead>
													<tr>
														<th>Sl No</th>
														<th>Room Type</th>
		<!--
														<th>Sale Type</th>
														<th>Sale Date Range</th>
		-->
														<th>Room Info</th>
														<th>Room CheckIn</th>
														<th>Room CheckOut</th>
														<th>Room Amenities</th>
														<th>Meal Plan</th>
														<th>Actions</th>
													</tr>
												</thead>
												<tbody>
													<?php if($room_list!=''){ for($r=0;$r<count($room_list);$r++){ ?>
															<tr>
																<td><?php echo ($r+1); ?></td>
																<td><?php echo $room_list[$r]->room_type_name; ?></td>
		<!--
																<td><?php echo $room_list[$r]->sale_type; ?></td>
																<td><?php echo $room_list[$r]->sale_date_range; ?></td>
		-->
																<td><?php echo $room_list[$r]->room_info; ?></td>
																<td><?php echo $room_list[$r]->checkin_time; ?></td>
																<td><?php echo $room_list[$r]->checkout_time; ?></td>
																<td><?php echo $ammenities = $this->Hotel_Model->show_hotel_ammenities($room_list[$r]->room_amenities); ?></td>
																<td width="20%"> Breakfast : <?php if($room_list[$r]->breakfast_price_flag == 1){ echo "Included";} else{ echo "Not Included"; } ?>  <br />
																     HalfBoard :<?php $room_list[$r]->lucnh_price_flag;  if($room_list[$r]->lucnh_price_flag == 1){ echo "Included";} else{ echo "Not Included"; } ?>  <br />
																     FullBoard : <?php $room_list[$r]->dinner_price_flag; if($room_list[$r]->dinner_price_flag == 1){ echo "Included";} else{ echo "Not Included"; } ?> <br />
																
																     <?php $this->load->model('Hotel_Model'); $meal_plan = $this->Hotel_Model->get_meal_plan($room_list[$r]->hotel_room_details_id); 
																  
																           if($meal_plan != ""){
																			   for($mp=0; $mp < count($meal_plan); $mp++) { 
																				 		$meals =  $meal_plan[$mp]->meal_type_name; 
																				 		if($meal_plan[$mp]->oth_meals_flag == 1){
																							echo $meals." : Included<br>";
																						}else{
																							echo $meals." : Not Included<br>";
																						}
																																					   
																			  }
																		   } ?>
																     
																     </td>
																     
																     <td class="center">
																	<?php if($room_list[$r]->status == "ACTIVE"){ ?>
																		<a href="<?php echo site_url()."hotel/inactive_hotel_room/".base64_encode($room_list[$r]->hotel_room_details_id); ?>/<?php echo base64_encode($room_list[$r]->hotel_details_id); ?>" class="btn btn-orange btn-sm btn-icon icon-left"><i class="entypo-eye"></i>InActive</a>
																	<?php }else{ ?>
																		<a href="<?php echo site_url()."hotel/active_hotel_room/".base64_encode($room_list[$r]->hotel_room_details_id); ?>/<?php echo base64_encode($room_list[$r]->hotel_details_id); ?>" class="btn btn-green btn-sm btn-icon icon-left"><i class="entypo-check"></i>Active</a>
																	<?php } ?>
																	<a href="<?php echo site_url()."hotel/delete_hotel_room/".base64_encode($room_list[$r]->hotel_room_details_id); ?>/<?php echo base64_encode($room_list[$r]->hotel_details_id); ?>" class="btn btn-danger btn-sm btn-icon icon-left"><i class="entypo-cancel"></i>Delete</a>
																	<a href="<?php echo site_url()."hotel/edit_hotel_room/".base64_encode($room_list[$r]->hotel_room_details_id); ?>/<?php echo base64_encode($room_list[$r]->hotel_details_id); ?>" class="btn btn-default btn-sm btn-icon icon-left"><i class="entypo-pencil"></i>Edit</a>
																</td>
															</tr>
													<?php }} ?>
												</tbody>
											</table>
											<div class="form-group">
												<label class="col-sm-3 control-label">&nbsp;</label>									
												<div class="col-sm-5"><a id="complete_room_details" data-toggle="tab" href=""><button type="button" class="btn btn-success" id="room_details">Next</button></a></div>
											</div>
										</div>
										<div id="add_new_room" style="display:none;">
											<form id="room_details_info" name="room_details_info" method="post" >
												<div class="panel panel-primary" data-collapsed="0">
													<div class="panel-heading">
														<div class="panel-title">Room Information</div>											
														<div class="panel-options">
															<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
															<a href="#" data-rel="reload"><i class="entypo-arrows-ccw"></i></a>
															<a href="#" data-rel="close"><i class="entypo-cancel"></i></a>
														</div>
													</div>
													<div class="panel-body">
														<div class="form-group">
															<label class="col-sm-3 control-label">Type</label>									
															<div class="col-sm-5">
																<select class="form-control" name="new_rooms_type_id">
																	<?php // $rooms = $this->Hotel_Model->get_room_types_list_stat($hotel_id);
																		for($r=0; $r<count($rooms); $r++){ ?>
																			<option value="<?php echo $rooms[$r]->hotel_room_type_id; ?>"><?php echo $rooms[$r]->room_type_name; ?></option>
																	<?php } ?>
																</select>
															</div>
														</div>
														<div class="form-group" style="display:none;">
															<label class="col-sm-3 control-label">Room Name</label>									
															<div class="col-sm-5">
																<input type="text" class="form-control" id="room_name"  name="room_name" placeholder="Room Name" data-validate="" data-message-required="Please enter the Room Name">
															</div>
														</div>
														<div class="form-group">
															<label for="field-1" class="col-sm-3 control-label">Description</label>
															<div class="col-sm-8">
																<textarea class="form-control " name="room_info" placeholder="Room Info" data-message-required="Please enter the Room Info"></textarea>
															</div>
														</div>
														<div class="form-group">
															<label for="field-1" class="col-sm-3 control-label">Room Policy </label>
															<div class="col-sm-8">
																<textarea class="form-control " id="cancellation_policy" name="cancellation_policy" placeholder="Room Info" data-message-required="Please enter the Cancellation Info"></textarea>
															</div>
														</div>
														<div class="form-group">
															<label class="col-sm-3 control-label">Check In Time</label>						
															<div class="col-sm-2">
																<div class="input-group">
																	<input type="text" class="form-control timepicker"  id="checkin_time"  name="checkin_time"  data-template="dropdown" data-show-seconds="false" data-default-time="<?php echo date('H:i'); ?>" data-show-meridian="false" data-minute-step="5" data-second-step="5" />																	
																	<div class="input-group-addon">
																		<a href="#"><i class="entypo-clock"></i></a>
																	</div>
																</div>
															</div>

															<label class="col-sm-3 control-label">Check Out Time</label>						
															<div class="col-sm-2">
																<div class="input-group">
																	<input type="text" class="form-control timepicker" d="checkout_time"  name="checkout_time"  data-template="dropdown" data-show-seconds="false" data-default-time="<?php echo date('H:i'); ?>" data-show-meridian="false" data-minute-step="5" data-second-step="5" />														
																	<div class="input-group-addon">
																		<a href="#"><i class="entypo-clock"></i></a>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="panel panel-primary" data-collapsed="0">
													<div class="panel-heading">
														<div class="panel-title">Extras : Inclusive (* Tick checkbox if these inclusive are added in room price itself)</div>											
														<div class="panel-options">
															<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
															<a href="#" data-rel="reload"><i class="entypo-arrows-ccw"></i></a>
															<a href="#" data-rel="close"><i class="entypo-cancel"></i></a>
														</div>
													</div>
													<div class="panel-body">
													<div class="form-group">
														
														<label for="field-1" class="col-sm-2 control-label">Breakfast (Price)</label>
														<div class="col-sm-2">
															<div class="checkbox checkbox-replace color-green">
																<label class="cb-wrapper"><input type="checkbox" id="chk-brekfast" name="chk_brekfast" ><div class="checked"></div></label>
																<input type="text" name="break_fast_p" class="form-control" />
															</div>
														</div>

														<label for="field-1" class="col-sm-2 control-label">Half Board (Price)</label>
														<div class="col-sm-2">
															<div class="checkbox checkbox-replace color-blue">
																<label class="cb-wrapper"><input type="checkbox" id="chk-lunch" name="chk_lunch" ><div class="checked"></div></label>
																<input type="text" name="lunch_p" class="form-control" />
															</div>
														</div>
														
														<label for="field-1" class="col-sm-2 control-label">Full Board(Price)</label>
														<div class="col-sm-2">
															<div class="checkbox checkbox-replace color-red">
																<label class="cb-wrapper"><input type="checkbox" id="chk-dinner" name="chk_dinner" ><div class="checked"></div></label>
																<input type="text" name="dinner_p" class="form-control" />
															</div>
														</div>
												<div class="form-group">
														
														
													</div>		
											<div class="form-group">
											<label for="field-1" class="col-sm-2 control-label">Meal Type Name</label>									
											<div class="col-sm-4">
												<input type="text" class="form-control"   name="mealtype_name[]" value="" id="mealtype_name"   />
											</div>
											<div class="col-sm-2">
												<div class="mark checkbox checkbox-replace color-blue">
													<label class="cb-wrapper">
														<input type="checkbox" id="oth_meals_flag" name="oth_meals_flag[]">
														<div class="checked"></div>
													</label>
												</div>
											</div>
											<div class="col-sm-2">
												<input type="text" class="form-control"   name="mealtype_price[]" value="" id="mealtype_price" />
											</div>
											</div>
													<div id="rooms" class="form-group" ></div>
													
										<div class="form-group ">
											<div class="col-md-9"></div>
											<div class="col-md-1"><input type="hidden" id="rows_cnt" value="1"/><button type="button" class="btn btn-success" onclick="addMoreRooms1(this);">Add</button></div>
											<div class="col-md-2"><button type="button" class="btn btn-success" onclick="removeLastRoom1(this);">Remove Last</button></div>
										</div>
										
										
													</div>
													<div class="form-group">
														
														
													</div>
													<div class="form-group">
														
														
													</div>
												</div>
												</div>
												<div class="panel panel-primary" data-collapsed="0">
													<div class="panel-heading">
														<div class="panel-title">Amenities Details</div>											
														<div class="panel-options">
															<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
															<a href="#" data-rel="reload"><i class="entypo-arrows-ccw"></i></a>
															<a href="#" data-rel="close"><i class="entypo-cancel"></i></a>
														</div>
													</div>
													<div class="panel-body">															
														<div class="form-group">
															<div class="col-sm-5">
															<?php $color1 = array("primary", "red", "blue", "green", "gold"); $color = array("green");
																$ammenities = $this->Hotel_Model->get_ammenities_list(); for($a=0; $a<count($ammenities); $a++){ $color_keys = array_rand($color, 1); ?>
																<div class="checkbox checkbox-replace color-<?php echo $color[$color_keys] ?>">
																	<label class="cb-wrapper"><input type="checkbox" value="<?php echo $ammenities[$a]->hotel_amenities_id; ?>" name="room_ammenities_list[]" id="chk-<?php echo $a; ?>" ><div class="checked"></div></label>
																	<label><?php echo $ammenities[$a]->amenities_name; ?></label>
																</div>
															<?php } ?>			
															</div>
														</div>																						
													</div>	
												</div>	
												<div class="form-group">
													<label class="col-sm-3 control-label">&nbsp;</label>									
													<div class="col-sm-5"><button type="button" onclick="save_room()" class="btn btn-success">save</button></div>
												</div>
											</form>
										</div>
									</div>
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
													<th>Room Count</th>
		<!--
													<th>From</th>
													<th>To</th>
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
															<td><?php echo $room_count_info[$r]->no_of_room; ?></td>
		<!--
															<td><?php echo $room_count_info[$r]->from_date; ?></td>
															<td><?php echo $room_count_info[$r]->to_date; ?></td>
															<td><?php echo $room_count_info[$r]->adult; ?></td>
															<td><?php echo $room_count_info[$r]->child; ?></td>
		-->
															<td class="center">
																<?php if($room_count_info[$r]->status == "ACTIVE"){ ?>
																	<a href="<?php echo site_url()."hotel/inactive_hotel_room_count/".base64_encode(json_encode($room_count_info[$r]->hotel_room_count_info_id)); ?>/<?php echo base64_encode(json_encode($room_count_info[$r]->hotel_details_id)); ?>" class="btn btn-orange btn-sm btn-icon icon-left"><i class="entypo-eye"></i>InActive</a>
																<?php }else{ ?>
																	<a href="<?php echo site_url()."hotel/active_hotel_room_count/".base64_encode(json_encode($room_count_info[$r]->hotel_room_count_info_id)); ?>/<?php echo base64_encode(json_encode($room_count_info[$r]->hotel_details_id)); ?>" class="btn btn-green btn-sm btn-icon icon-left"><i class="entypo-check"></i>Active</a>
																<?php } ?>
																<a href="<?php echo site_url()."hotel/delete_hotel_room_count/".base64_encode(json_encode($room_count_info[$r]->hotel_room_count_info_id)); ?>/<?php echo base64_encode(json_encode($room_count_info[$r]->hotel_details_id)); ?>" class="btn btn-danger btn-sm btn-icon icon-left"><i class="entypo-cancel"></i>Delete</a>
																<a href="<?php echo site_url()."hotel/edit_hotel_room_count/".base64_encode(json_encode($room_count_info[$r]->hotel_room_count_info_id)); ?>/<?php echo base64_encode(json_encode($room_count_info[$r]->hotel_room_details_id)); ?>/<?php echo base64_encode(json_encode($room_count_info[$r]->hotel_details_id)); ?>" class="btn btn-default btn-sm btn-icon icon-left"><i class="entypo-pencil"></i>Edit</a>
																<a href="<?php echo site_url()."calendar/show_calendar/".base64_encode(json_encode($room_count_info[$r]->hotel_room_count_info_id)); ?>/<?php echo base64_encode(json_encode($room_count_info[$r]->hotel_room_details_id)); ?>/<?php echo base64_encode(json_encode($room_count_info[$r]->hotel_details_id)); ?>" class="btn btn-default btn-sm btn-icon icon-left"><i class="entypo-menu"></i>Manage</a>
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
											<!--<label class="col-md-2 control-label">Date Range</label> -->
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
												  for($r=0; $r<count($rooms); $r++){ ?>
													<option value="<?php echo $rooms[$r]->hotel_room_type_id; ?>"><?php echo $rooms[$r]->room_type_name; ?></option>
												  <?php } ?>
												  <!--
													<?php 
														if($room_list!=''){ for($r=0;$r<count($room_list);$r++){ ?>
															<option value="<?php echo $room_list[$r]->hotel_room_details_id; ?>"><?php echo $room_list[$r]->room_type_name; ?></option>
													<?php }} ?>
													-->
												</select>
											</div>
											<div class="col-sm-2">
												<select class="form-control" name="sale_type[]">
													<option value="FreeSaleBasis">Free Sale Basis</option>
													<option value="Allotment">Allotment Basis with a release period</option>
													<option value="OnRequest">ON REQUEST Basis</option>
													<option value="Guaranty">Guaranty</option>
												</select>
											</div>
											<div class="col-sm-2">
												<input type="text" class="form-control daterange"   name="sale_date_range[]" id="date_range" data-min-date="<?php echo date('m-d-Y');?>" data-validate="required" data-message-required="Please Select the Date Range" />
											</div>
											<!--
											<div class="col-md-2">
												<input type="text" class="form-control daterange"  name="date_rane[]" id="date_rane" data-min-date="<?php echo date('m-d-Y');?>" data-validate="required" data-message-required="Please Select the Date Range" />
											</div>
											-->
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
										<div id="rooms1111"></div>
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
													<?php //$rooms = $this->Hotel_Model->get_room_types_list_stat();
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
																	<a href="<?php echo site_url()."hotel/inactive_hotel_room_rate/".$room_rate_info[$r]->hotel_room_rate_info_id; ?>/<?php echo base64_encode(json_encode($room_rate_info[$r]->hotel_details_id)); ?>" class="btn btn-orange btn-sm btn-icon icon-left"><i class="entypo-eye"></i>InActive</a>
																<?php }else{ ?>
																	<a href="<?php echo site_url()."hotel/active_hotel_room_rate/".$room_rate_info[$r]->hotel_room_rate_info_id; ?>/<?php echo base64_encode(json_encode($room_rate_info[$r]->hotel_details_id)); ?>" class="btn btn-green btn-sm btn-icon icon-left"><i class="entypo-check"></i>Active</a>
																<?php } ?>
																<a href="<?php echo site_url()."hotel/delete_hotel_room_rate/".$room_rate_info[$r]->hotel_room_rate_info_id; ?>/<?php echo base64_encode(json_encode($room_rate_info[$r]->hotel_details_id)); ?>" class="btn btn-danger btn-sm btn-icon icon-left"><i class="entypo-cancel"></i>Delete</a>
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
				if($('#address').val()==''){
					$('#address').css('border', '1px solid #f52c2c');
					$('#address').focus();
					return false;
				}
				if($('#city').val()==''){
					$('#city').css('border', '1px solid #f52c2c');
					$('#city').focus();
					return false;
				}
				if($('#state_name').val()==''){
					$('#state_name').css('border', '1px solid #f52c2c');
					$('#state_name').focus();
					return false;
				}
				if($('#zip_code').val()==''){
					$('#zip_code').css('border', '1px solid #f52c2c');
					$('#zip_code').focus();
					return false;
				}
				if($('#country').selectedIndex==0){
					$('#country').css('border', '1px solid #f52c2c');
					$('#country').focus();
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
					if($('#secondary_phone_no').val()=='' || isNaN($('#secondary_phone_no').val())){
						$('#secondary_phone_no').css('border', '1px solid #f52c2c');
						$('#secondary_phone_no').focus();
						return false;
					}
					if($('#secondary_mobile_no').val()=='' || isNaN($('#secondary_mobile_no').val())){
						$('#secondary_mobile_no').css('border', '1px solid #f52c2c');
						$('#secondary_mobile_no').focus();
						return false;
					}
					if($('#secondary_address').val()==''){
						$('#secondary_address').css('border', '1px solid #f52c2c');
						$('#secondary_address').focus();
						return false;
					}
					if($('#secondary_city').val()==''){
						$('#secondary_city').css('border', '1px solid #f52c2c');
						$('#secondary_city').focus();
						return false;
					}
					if($('#secondary_state_name').val()==''){
						$('#secondary_state_name').css('border', '1px solid #f52c2c');
						$('#secondary_state_name').focus();
						return false;
					}
					if($('#secondary_zip_code').val()==''){
						$('#secondary_zip_code').css('border', '1px solid #f52c2c');
						$('#secondary_zip_code').focus();
						return false;
					}
					if($('#secondary_country').selectedIndex==0){
						$('#secondary_country').css('border', '1px solid #f52c2c');
						$('#secondary_country').focus();
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
		
		function save_meal_plan()
		{
			$.ajax({
				type	: "POST",
				url  	:'<?php echo base_url(); ?>hotel/save_meal_plan/<?php echo $hotels_list[0]->hotel_details_id; ?>',
				data	: $('#meal_details_info').serialize(),
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
			$("#rooms1111").append('<div class="form-group"><div class="col-md-2"><select class="form-control" name="rooms_type_id[]"><?php if($rooms != ''){ for($r=0; $r<count($rooms); $r++){ ?><option value="<?php echo $rooms[$r]->hotel_room_type_id; ?>"><?php echo $rooms[$r]->room_type_name; ?></option><?php }} ?></select></div><div class="col-sm-2"><select class="form-control" name="sale_type[]"><option value="FreeSaleBasis">Free Sale Basis</option><option value="Allotment">Allotment Basis with a release period</option><option value="OnRequest">ON REQUEST Basis</option><option value="Guaranty">Guaranty</option></select></div><div class="col-sm-2"><input type="text" class="form-control daterange" name="sale_date_range[]" data-min-date="<?php echo date('m-d-Y');?>" data-validate="required" data-message-required="Please Select the Date Range" /></div><div class="col-md-1"><input name="rooms_tot_units[]" type="number" size="2" class="form-control" value="0"/></div></div>');
			$('.daterange').daterangepicker();
			id = id+1;			
			$('#rows_cnt').val(id);
		}

		function removeLastRoom(v){			
			var id = $('#rows_cnt').val();
			$('#rooms1111 .form-group').last().remove();
			id = id-1;
			$('#rows_cnt').val(id);
		}
		
		function add_meals(){
			$('#me_details').hide();
			$('#add_new_meal').show();
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
		
		function addMoreRooms1(c) {
		  
			var id = $('#rows_cnt').val();
												
			$("#rooms").append('<div class="form-group">'+
			                   '<label for="field-1" class="col-sm-2 control-label">Meal Type Name</label>'+
			                   '<div class="col-sm-4">'+
			                   '<input type="text" class="form-control"   name="mealtype_name[]" value="" id="mealtype_name"   />'+
			                   '</div>'+
			                   '<div class="col-sm-2">'+
			                   '<div id="clickid'+id+'"  onclick="checkedbox(this);">'+
			                   '<label class="cb-wrapper"><label class="cb-wrapper"><input type="checkbox" id="oth_meals_flag'+id+'" name="oth_meals_flag[]" ><div class="checked"></div></label><div class="checked"></div></label>'+
			                   '</div></div>'+
			                   '<div class="col-sm-2">'+
			                   '<input type="text" class="form-control"   name="mealtype_price[]" value="" id="mealtype_price" />'+
			                   '</div></div>');
			  $('#clickid'+id).addClass('checkbox');
			  $('#clickid'+id).addClass('mark');
			  $('#clickid'+id).addClass('checkbox-replace');
			  $('#clickid'+id).addClass('color-blue');
			  $('#clickid'+id).addClass('neon-cb-replacement');
			  
			id = parseInt(id)+parseInt(1);
			$('#rows_cnt').val(id);
		}
		function removeLastRoom1(v){
			var id = $('#rows_cnt').val();
			$('#rooms .form-group').last().remove();
			id = id-1;
			$('#rows_cnt').val(id);
		}
		
		function checkedbox(that){
		$('#'+that.id).toggleClass('checked');
		}
		
	//~ $('.mark').click(function(){
		//~ alert("asdfasdf");
		//~ $(this).toggleClass("checked");
		//~ });
		
		</script>

</body>
</html>
