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
<body class="page-body <?php if(isset($transition)){ echo $transition; } ?>" data-url="<?php echo PROVAB_URL; ?>">
	<div class="page-container <?php if(isset($header) && $header == 'header_top'){ echo "horizontal-menu"; } ?> <?php if(isset($header) && $header == 'header_right'){ echo "right-sidebar"; } ?>  <?php if(isset($sidebar)){ echo $sidebar; } ?>">
		<?php if(isset($header) && $header == 'header_top'){ $this->load->view('general/header_top'); }else{ $this->load->view('general/left_menu'); }	?>
		<div class="main-content">
			<?php if(!isset($header) || $header != 'header_top'){ $this->load->view('general/header_left'); } ?>
			<?php $this->load->view('general/top_menu');	?>
			<hr />
			<ol class="breadcrumb bc-3">						
				<li><a href="<?php echo site_url()."/dashboard/dashboard"; ?>"><i class="entypo-home"></i>Home</a></li>
				<li><a href="<?php echo site_url()."/hotel/hotel_crs_list"; ?>">Hotel Management</a></li>
				<li class="active"><strong>Edit Room Management</strong></li>
			</ol>
			<div class="row">
				<div class="col-md-12">					
					<div class="panel panel-primary" data-collapsed="0">					
						<div class="panel-heading">
							<div class="panel-title">
								Edit Room Managemnet
							</div>							
							<div class="panel-options">
								<a href="#sample-modal" data-toggle="modal" data-target="#sample-modal-dialog-1" class="bg"><i class="entypo-cog"></i></a>
								<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
								<a href="#" data-rel="reload"><i class="entypo-arrows-ccw"></i></a>
								<a href="#" data-rel="close"><i class="entypo-cancel"></i></a>
							</div>
						</div>						
						<div class="panel-body">	
													
							<form method="post" id="edit_rate" name="edit_rate" action="<?php echo site_url()."/hotel/update_room_mgt/".$room_rate->hotel_room_count_info_id; ?>/<?php echo $room_rate->hotel_room_details_id?>/<?php echo $room_rate->hotel_details_id?>" class="form-horizontal form-groups-bordered validate" enctype= "multipart/form-data">				
								
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
										<select class="form-control" name="rooms_type_id">
											<?php 
												if($room_list!=''){ for($r=0;$r<count($room_list);$r++){ ?>
													<option value="<?php echo $room_list[$r]->hotel_room_details_id; ?>" <?php if( $room_rate->hotel_room_details_id== $room_list[$r]->hotel_room_details_id) echo "Selected='Selected'"; ?>><?php echo $room_list[$r]->room_type_name; ?></option>
											<?php }} ?>
										</select>
									</div>
									<div class="col-sm-2">
										<select class="form-control" name="sale_type">
											<option value="FreeSaleBasis" <?php if(isset($room_rate)) { if($room_rate->sale_type == 'FreeSaleBasis') { echo 'selected'; } } ?>>Free Sale Basis</option>
											<option value="Allotment" <?php if(isset($room_rate)) { if($room_rate->sale_type == 'Allotment') { echo 'selected'; } } ?>>Allotment Basis with a release period</option>
											<option value="OnRequest" <?php if(isset($room_rate)) { if($room_rate->sale_type == 'OnRequest') { echo 'selected'; } } ?>>ON REQUEST Basis</option>
										</select>
									</div>
									<div class="col-sm-2">
										<input type="text" class="form-control daterange" id="sale_date_range" name="sale_date_range" value="<?php echo $room_rate->sale_date_range?>" data-min-date="<?php echo date('m-d-Y');?>" data-validate="required" data-message-required="Please Select the Date Range" />
									</div>
									<div class="col-md-2">
										<input type="text" class="form-control daterange" id="date_rane" name="date_rane" value="<?php echo date('d/m/Y', strtotime($room_rate->from_date)) ;?> - <?php echo date('d/m/Y', strtotime($room_rate->to_date)); ?>" data-min-date="<?php echo date('m-d-Y');?>" data-validate="required" data-message-required="Please Select the Date Range" />
									</div>
									<div class="col-md-1">
										<input name="rooms_tot_units" type="number" size="2" class="form-control" value="<?php echo $room_rate->no_of_room?>"/>
									</div>
									<div class="col-md-1">
										<input name="adults" type="hidden" size="2" class="form-control" value="0"/>
									</div>
									<div class="col-md-1">
										<input name="child" type="hidden" size="2" class="form-control" value="0"/>
									</div>
								</div>
								<div id="rooms"></div>								
								
								<div class="form-group">
									<label class="col-sm-3 control-label">&nbsp;</label>									
									<div class="col-sm-5"><button type="submit" class="btn btn-success">Save</button></div>
								</div>
							</form>	
							</form>
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
		$(function(){
			$('#status').change(function(){
				var current_status = $('#status').val();
				if(current_status == "ACTIVE")
					$('#status').val('INACTIVE');
				else
					$('#status').val('ACTIVE');
			});
			// $('#countries_list').change(function(){
				// var country = $('#countries_list').val();
				// $.ajax({
					// url:'<?php echo site_url(); ?>/hotel/filter_city_list/'+country,
					// dataType: "json",
					// success: function(data){
						// if (data.status == 1) {
							// $('#cities_list').html(data.city);  
						// } 
					// }
				// });
			// }); 
			
			$('#edit_rate').submit(function(){
				
				if($('#sale_date_range').val() == '')
				{
					   $('#sale_date_range').css = "1px solid #f52c2c";   
						$('#sale_date_range').focus(); 
						return false; 
				}
				
				if($('#date_rane').val() == '')
				{
					   $('#date_rane').css = "1px solid #f52c2c";   
						$('#date_rane').focus(); 
						return false; 
				}
			})
		});
	</script>
    <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
    <script>
		var map;
		var geocoder;
		var mapOptions = { center: new google.maps.LatLng(0.0, 0.0), zoom: 2,
        mapTypeId: google.maps.MapTypeId.ROADMAP };
	
		function initialize() {
			var myOptions = {
                center: new google.maps.LatLng(12.851, 77.659 ),
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
						document.getElementById("pincode").value 	= zipcode;						
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
</script>
    
</body>
</html>
