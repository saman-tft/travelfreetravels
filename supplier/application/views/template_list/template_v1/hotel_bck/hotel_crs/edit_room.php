<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">	
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="description" content="Provab Admin Panel" />
	<meta name="author" content="" />	
	<title><?php echo PAGE_TITLE; ?> | Edit Room</title>	
	<!-- Load Default CSS and JS Scripts -->
	<?php $this->load->view('general/load_css');	?>
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/selectboxit/jquery.selectBoxIt.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/select2/select2-bootstrap.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/select2/select2.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/daterangepicker/daterangepicker-bs3.css">
		
</head>
<body class="page-body <?php if(isset($transition)){ echo $transition; } ?>" data-url="<?php echo PROVAB_URL; ?>">
<div class="page-container <?php if(isset($header) && $header == 'header_top'){ echo "horizontal-menu"; } ?> <?php if(isset($header) && $header == 'header_right'){ echo "right-sidebar"; } ?> <?php if(isset($sidebar)){ echo $sidebar; } ?>">
<?php if(isset($header) && $header == 'header_top'){ $this->load->view('general/header_top'); }else{ $this->load->view('general/left_menu'); }	?>
<div class="main-content">
	<?php if(!isset($header) || $header != 'header_top'){ $this->load->view('general/header_left'); } ?>
	<?php $this->load->view('general/top_menu');	?>
	<hr />
	<ol class="breadcrumb bc-3">						
		<li><a href="<?php echo site_url()."/dashboard/dashboard"; ?>"><i class="entypo-home"></i>Home</a></li>
		<li><a href="<?php echo site_url()."/hotel/hotel_crs_list"; ?>">Hotel Management</a></li>
		<li class="active"><strong>Edit Room</strong></li>
	</ol>
	<div class="row">
		<div class="col-md-12">					
			<div class="panel panel-primary" data-collapsed="0">					
						
				<div class="panel-body"><!-- <?php echo site_url()."/hotel/save_hotel_information/".$hotels_list[0]->hotel_details_id; ?> -->
					<div class="form-wizard form-horizontal form-groups-bordered">
					
					
					<div class="tab-content">
								
						</div>
						<div class="tab-pane active" id="tab_room">
							<div class="main-content" id="room_list">
								
								
								<div id="add_new_room" style="">
									<form id="room_details_info" name="room_details_info" action="<?php echo site_url()."/hotel/update_hotel_room/".base64_encode(json_encode($room_data->hotel_room_details_id)).'/'.base64_encode(json_encode($room_data->hotel_details_id)); ?>" name="room_details_info" method="post" >
										<div class="panel panel-primary" data-collapsed="0">
											<div class="panel-heading">
												<div class="panel-title">Edit Room Information</div>											
												<div class="panel-options">
													<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
													<a href="#" data-rel="reload"><i class="entypo-arrows-ccw"></i></a>
													
												</div>
											</div>
											<div class="panel-body">
												<div class="form-group">
													<label class="col-sm-3 control-label">Type</label>									
													<div class="col-sm-5">
														<select class="form-control" name="new_rooms_type_id">															
															<?php $rooms = $this->Hotel_Model->get_room_types_list_stat();
															for($r=0; $r<count($rooms); $r++){ ?>
															<option value="<?php echo  $rooms[$r]->hotel_room_type_id; ?>" <?php if( $room_data->hotel_room_type_id== $rooms[$r]->hotel_room_type_id) echo "Selected='Selected'"; ?>><?php echo  $rooms[$r]->room_type_name; ?></option>
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
														<textarea class="form-control " name="room_info" placeholder="Room Info" data-message-required="Please enter the Room Info"><?php echo $room_data->room_info?></textarea>
													</div>
												</div>
												<div class="form-group">
													<label for="field-1" class="col-sm-3 control-label">Room Policy </label>
													<div class="col-sm-8">
														<textarea class="form-control " name="cancellation_policy" placeholder="Room Info" data-message-required="Please enter the Cancellation Info"><?php echo $room_data->cancellation_policy?></textarea>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">Check In Time</label>						
													<div class="col-sm-2">
														<div class="input-group">
															<input type="text" readonly class="form-control timepicker"  id="checkin_time"  name="checkin_time"  value="<?php echo $room_data->checkin_time?>" data-template="dropdown" data-show-seconds="false" data-default-time="<?php echo date('H:i'); ?>" data-show-meridian="false" data-minute-step="5" data-second-step="5" />
															<div class="input-group-addon">
																<a href="#"><i class="entypo-clock"></i></a>
															</div>
														</div>
													</div>

													<label class="col-sm-3 control-label">Check Out Time</label>						
													<div class="col-sm-2">
														<div class="input-group">
															<input type="text" readonly class="form-control timepicker" d="checkout_time"  name="checkout_time"  value="<?php echo $room_data->checkout_time?>" data-template="dropdown" data-show-seconds="false" data-default-time="<?php echo date('H:i'); ?>" data-show-meridian="false" data-minute-step="5" data-second-step="5" />														
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
													  
												</div>
											</div>
											<div class="panel-body">
											<div class="form-group">
												
												<label for="field-1" class="col-sm-2 control-label">Breakfast (Price)</label>
												<div class="col-sm-2">
													<div id="brkfast" class="checkbox checkbox-replace color-green ">
														<label class="cb-wrapper"><input type="checkbox" id="chk_brekfast" name="chk_brekfast" >  </label>
														<input type="text" id="break_fast_p" name="break_fast_p" class="form-control"  value="<?php if($room_data->break_fast_price != 0){ echo  $room_data->break_fast_price; } ?>" />
													</div>
												</div>

												<label for="field-1" class="col-sm-2 control-label">Half Board (Price)</label>
												<div class="col-sm-2">
													<div id="lunch"  class="checkbox checkbox-replace color-blue">
														<label class="cb-wrapper"><input type="checkbox" id="chk_lunch" name="chk_lunch" ></label>
														<input type="text" id="lunch_p" name="lunch_p" class="form-control" value="<?php if($room_data->lucnh_price_flag != 0){ echo  $room_data->lunch_price; } ?>"  />
													</div>
												</div>
												
												<label for="field-1" class="col-sm-2 control-label">Full Board(Price)</label>
												<div class="col-sm-2">
													<div id="dinner" class="checkbox checkbox-replace color-red  ">
														<label class="cb-wrapper"><input type="checkbox" id="chk_dinner" name="chk_dinner" > </label>
														<input type="text"  name="dinner_p" id="dinner_p" class="form-control"  value="<?php if($room_data->dinner_price != 0){ echo  $room_data->dinner_price; }  ?>" />
													</div>
												</div>
											</div>
											<div class="form-group">
												
												
											</div>
											
												<div class="form-group">
														
														
													</div>		
											<div class="form-group">
											<label for="field-1" class="col-sm-2 control-label">Meal Type Name</label>									
											<div class="col-sm-4">
												<input type="text" class="form-control"  <?php if($meal_plan != ''){ echo "value=".$meal_plan[0]->meal_type_name; } ?>   name="mealtype_name[]" value="" id="mealtype_name"   />
											</div>
											<div class="col-sm-2">
												<div class="checkbox checkbox-replace color-blue <?php if($meal_plan != ''){ if($meal_plan[0]->oth_meals_flag){ ?> checked <?php } } ?>">
												<label class="cb-wrapper"><input type="checkbox" id="oth_meals_flag"    name="oth_meals_flag[]" > <?php if($meal_plan != ''){ if($meal_plan[0]->oth_meals_flag){ ?><div class="checked"></div> <?php } } ?></label>
												</div>
											</div>
											<div class="col-sm-2">
												<input type="text" class="form-control"  <?php if($meal_plan != ''){ echo "value=".$meal_plan[0]->mealtype_price; } ?>   name="mealtype_price[]" value="" id="mealtype_price" />
											</div>
											</div>
													<div id="rooms" class="form-group" ></div>
													
												<input type="hidden" id="rows_cnti" value="1"/>	
										<div class="form-group ">
											<div class="col-md-9"></div>
											<div class="col-md-1"><button type="button" class="btn btn-success" onclick="addMoreRooms1(this);">Add</button></div>
											<div class="col-md-2"><button type="button" class="btn btn-success" onclick="removeLastRoom1(this);">Remove Last</button></div>
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
													  
												</div>
											</div>
											<div class="panel-body">															
												<div class="form-group">
													<div class="col-sm-5" id="amenities">
													<?php $color1 = array("primary", "red", "blue", "green", "gold"); $color = array("green");
														$room_amenity	= explode(',', $room_data->room_amenities);
														$ammenities = $this->Hotel_Model->get_ammenities_list(); for($a=0; $a<count($ammenities); $a++){ $color_keys = array_rand($color, 1); ?>
														<div  class="checkbox checkbox-replace color-<?php echo $color[$color_keys] ?>">
															<label class="cb-wrapper"><input type="checkbox" value="<?php echo $ammenities[$a]->hotel_amenities_id; ?>" name="room_ammenities_list[]" id="chk-<?php echo $a; ?>" <?php if (in_array($ammenities[$a]->hotel_amenities_id, $room_amenity)){  echo "Checked"; } ?>><div class="checked"></div></label>
															<label><?php echo $ammenities[$a]->amenities_name; ?></label>
														</div>
													<?php } ?>			
													</div>
												</div>																						
											</div>	
										</div>	
										<div class="form-group">
											<label class="col-sm-3 control-label">&nbsp;</label>									
											<div class="col-sm-5"><button type="submit"  class="btn btn-success">save</button></div>
										</div>
									</form>
								</div>
							</div>
						</div>
						
						<?php if(false){ ?>
						<div class="tab-pane" id="tab_room_rate">
							<form id="room_rate_info" name="room_ratet" class="form-wizard form-horizontal form-groups-bordered validate" enctype= "multipart/form-data" >
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
															<a href="<?php echo site_url()."/hotel/inactive_hotel_room_rate/".$room_rate_info[$r]->hotel_room_rate_info_id; ?>/<?php echo $room_rate_info[$r]->hotel_details_id; ?>" class="btn btn-orange btn-sm btn-icon icon-left"><i class="entypo-eye"></i>InActive</a>
														<?php }else{ ?>
															<a href="<?php echo site_url()."/hotel/active_hotel_room_rate/".$room_rate_info[$r]->hotel_room_rate_info_id; ?>/<?php echo $room_rate_info[$r]->hotel_details_id; ?>" class="btn btn-green btn-sm btn-icon icon-left"><i class="entypo-check"></i>Active</a>
														<?php } ?>
														<a href="<?php echo site_url()."/hotel/delete_hotel_room_rate/".$room_rate_info[$r]->hotel_room_rate_info_id; ?>/<?php echo $room_rate_info[$r]->hotel_details_id; ?>" class="btn btn-danger btn-sm btn-icon icon-left"><i class="entypo-cancel"></i>Delete</a>
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

    
	<!--	<script>
			var lat = $('#hotel_latitude').val();
			var lng = $('#hotel_longitude').val();
			var myCenter=new google.maps.LatLng(lat,lng);
			function initialize(){
				var mapProp = {
				  center: myCenter,
				  zoom:5,
				  mapTypeId: google.maps.MapTypeId.ROADMAP
				};
				var map = new google.maps.Map(document.getElementById("googleMap"),mapProp);
				var marker = new google.maps.Marker({
					position: myCenter,
					title:'Click to zoom'
				});
				marker.setMap(map);
				// Zoom to 9 when clicking on marker
				google.maps.event.addListener(marker,'click',function() {
					map.setZoom(9);
					map.setCenter(marker.getPosition());
				});
			}
			google.maps.event.addDomListener(window, 'load', initialize);
			document.getElementById('lat').value = lat;
			document.getElementById('lng').value = lng;
		</script> -->
	<script type="text/javascript">
		$(function()
		{   
		 
		    <?php if($meal_plan != '') { 
				 for($meal =1; $meal < count($meal_plan); $meal++){ ?>
							addMoreRooms1("");
							
						$('#mealtype_name<?php echo $meal; ?>').val('<?php echo $meal_plan[0]->meal_type_name; ?>');
						<?php	if($meal_plan[$meal]->oth_meals_flag == 1){ ?>
					      $('#clickid<?php echo $meal; ?>').addClass('checkbox');
					 <?php } ?>
					 $('#mealtype_price<?php echo $meal; ?>').val('<?php echo $meal_plan[$meal]->mealtype_price; ?>');
					<?php } 
				      }  ?>
							
		
			
			 <?php if($room_data->break_fast_price != 0){ ?>
			
				 $('#brkfast').addClass('checked')
		     <?php } ?>
		     
		      <?php if($room_data->lunch_price != 0){ ?>
			
				 $('#lunch').addClass('checked')
		     <?php } ?>
		     
		      <?php if($room_data->dinner_price != 0){ ?>
			
				 $('#dinner').addClass('checked')
		     <?php } ?>
			 
			var break_fast_p = document.getElementById('break_fast_p');
			var lunch_p = document.getElementById('lunch_p');
			var dinner_p = document.getElementById('dinner_p');
			
			var filter = /^[0-9]+(\.[0-9]{1,2})?$/;
			
			$('input#break_fast_p').keyup(function() {
			   
				var $th = $(this);		
				if($th.val().trim() != ""){
					 var regex = /^[0-9]+(\.[0-9]{1,2})?$/;
					if (regex.test($th.val())) {
						$th.css('border', '1px solid #099A7D');					
					} else {
						// alert("Please use only letters");
						$th.css('border', '1px solid #f52c2c');
						return '';
					}
				}else if(break_fast_p.value.length >3) {
					    break_fast_p.style.border = "1px solid #f52c2c";   
						break_fast_p.focus(); 
						return false; 
				}
			});	
			
			$('input#lunch_p').keyup(function() {
			    
				var $th = $(this);		
				if($th.val().trim() != ""){
					 var regex = /^[0-9]+(\.[0-9]{1,2})?$/;
					if (regex.test($th.val())) {
						$th.css('border', '1px solid #099A7D');					
					} else {
						// alert("Please use only letters");
						$th.css('border', '1px solid #f52c2c');
						return false; 
					}
				}else if(break_fast_p.value.length >10) {
					    break_fast_p.style.border = "1px solid #f52c2c";   
						break_fast_p.focus(); 
						return false; 
				}
			});	
			
			$('input#dinner_p').keyup(function() {
			   
				var $th = $(this);		
				if($th.val().trim() != ""){
					 var regex = /^[0-9]+(\.[0-9]{1,2})?$/;
					if (regex.test($th.val())) {
						$th.css('border', '1px solid #099A7D');					
					} else {
						// alert("Please use only letters");
						$th.css('border', '1px solid #f52c2c');
						return false;
					}
				}else if(dinner_p.value.length >10) {
					    dinner_p.style.border = "1px solid #f52c2c";   
						dinner_p.focus(); 
						return false; 
				}
			});	
			
			
			$('#room_details_info').submit(function(){
				 
				if(($('#brkfast').hasClass('checked'))){
				
					if(break_fast_p.value == '') {
						break_fast_p.style.border = "1px solid #f52c2c";   
						return false; 
					}
					
					if(break_fast_p.value != '') {
						
				    if(!(break_fast_p.value.match(filter)))
					{
						break_fast_p.style.border = "1px solid #f52c2c";   
						break_fast_p.focus(); 
						return false; 
					} else {
						break_fast_p.css('border', '1px solid #f52c2c');
						return '';
					}
						
					}
				}
				
						
				if(($('#lunch').hasClass('checked'))){
					
					if(lunch_p.value == '') {
						lunch_p.style.border = "1px solid #f52c2c";   
						return false; 
					}
					
					if(lunch_p.value != '') {
						
				    if(!(lunch_p.value.match(filter)))
					{
						lunch_p.style.border = "1px solid #f52c2c";   
						lunch_p.focus(); 
						return false; 
					} else {
						lunch_p.css('border', '1px solid #f52c2c');
						return false; 
					}
						
					}
				}
			
				if(($('#dinner').hasClass('checked'))){
					
					if(dinner_p.value == '') {
						dinner_p.style.border = "1px solid #f52c2c";  
						return false;  
					}
					
					if(dinner_p.value != '') {
						
				    if(!(dinner_p.value.match(filter)))
					{
						dinner_p.style.border = "1px solid #f52c2c";   
						dinner_p.focus(); 
						return false; 
					} else {
						dinner_p.css('border', '1px solid #f52c2c');
						return false; 
					}
						
					}
				}
			
			});
	
		});	
		
		
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
		//~ function select_room(id){
		
			//~ var $select = $('#room_details_id');
			//~ $.ajax({
				//~ url:'<?php echo base_url();?>hotel/get_room_info/'+id+'/'+hotelId,
				//~ success: function(data, textStatus, jqXHR) {
					//~ $select.html('');
					//~ $select.html('<option value="">Select Any Room</option>'+data);
				//~ }
			//~ });	
		//~ }
		
		function addMoreRooms1(c) {
		  
			var id = $('#rows_cnti').val();
			$("#rooms").append('<div class="form-group">'+
			                   '<label for="field-1" class="col-sm-2 control-label">Meal Type Name</label>'+
			                   '<div class="col-sm-4">'+
			                   '<input type="text" class="form-control"   name="mealtype_name[]" value="" id="mealtype_name'+id+'"   />'+
			                   '</div>'+
			                   '<div class="col-sm-2">'+
			                   '<div id="clickid'+id+'"  onclick="checkedbox(this);">'+
			                   '<label class="cb-wrapper"><label class="cb-wrapper"><input type="checkbox" id="oth_meals_flag'+id+'" name="oth_meals_flag[]" ><div class="checked"></div></label><div class="checked"></div></label>'+
			                   '</div></div>'+
			                   '<div class="col-sm-2">'+
			                   '<input type="text" class="form-control"   name="mealtype_price[]" value="" id="mealtype_price'+id+'" />'+
			                   '</div></div>');
			  $('#clickid'+id).addClass('checkbox');
			  $('#clickid'+id).addClass('mark');
			  $('#clickid'+id).addClass('checkbox-replace');
			  $('#clickid'+id).addClass('color-blue');
			  $('#clickid'+id).addClass('neon-cb-replacement');
			  
			id = parseInt(id)+parseInt(1);
			$('#rows_cnti').val(id);
		}
		function removeLastRoom1(v){
			var id = $('#rows_cnt').val();
			$('#rooms .form-group').last().remove();
			id = id-1;
			$('#rows_cnti').val(id);
		}
		
		function checkedbox(that){
		$('#'+that.id).toggleClass('checked');
		}
		
		</script>
	<!--	<script type="text/javascript">
	var directionsDisplay, directionsService, map;
function initialize()
    {

        // Set static latitude, longitude value
        var latlng = new google.maps.LatLng(document.getElementById('hotel_latitude').value, document.getElementById('hotel_longitude').value);
        document.getElementById('lat').value = document.getElementById('hotel_latitude').value;
        document.getElementById('lng').value = document.getElementById('hotel_longitude').value;
        // Set map options
        
        var myOptions = {
            zoom: 16,
            center: latlng,
            panControl: true,
            zoomControl: true,
            scaleControl: true,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        }
        // Create map object with options
        map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
        // Create and set the marker
        marker = new google.maps.Marker({
            map: map,
            draggable:true,
            position: latlng
        });

        // Register Custom "dragend" Event
        google.maps.event.addListener(marker, 'dragend', function() {

            // Get the Current position, where the pointer was dropped
            var point = marker.getPosition();
            // Center the map at given point
            map.panTo(point);
            // Update the textbox
            document.getElementById('lat').value=point.lat();
            document.getElementById('lng').value=point.lng();
        });
    }

</script>	-->
</body>
</html>
