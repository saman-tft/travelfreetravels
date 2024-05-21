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
				 <?php if($supplier_rights == 1){
					 $url = site_url()."/supplier_dashboard";
				 } else {
					  $url = site_url()."/dashboard/dashboard";
				 } ?>						
				<li><a href="<?php echo $url; ?>"><i class="entypo-home"></i>Home</a></li>
				<li><a href="<?php echo site_url()."/hotel/hotel_crs_list"; ?>">Hotel Management</a></li>
				<li class="active"><strong>Manage Transfers Child Group</strong></li>
			</ol>
			<div class="row">
				<div class="col-md-12">					
					<div class="panel panel-primary" data-collapsed="0">					
						<div class="panel-heading">
							<div class="panel-title">
								Edit Hotel
							</div>							
							<div class="panel-options">
								<a href="#sample-modal" data-toggle="modal" data-target="#sample-modal-dialog-1" class="bg"><i class="entypo-cog"></i></a>
								<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
								<a href="#" data-rel="reload"><i class="entypo-arrows-ccw"></i></a>
								<a href="#" data-rel="close"><i class="entypo-cancel"></i></a>
							</div>
						</div>						
						<div class="panel-body">	
													
							<form method="post" id="hotel" name="hotel" action="<?php echo site_url()."/hotel/managetransfer_childgroup/".base64_encode(json_encode($hotels_list[0]->hotel_details_id)); ?>" class="form-horizontal form-groups-bordered validate" enctype= "multipart/form-data">				
					
								
									<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">Child Group A</label>									
									<div class="col-sm-5">
										<input type="text" class="form-control" id="child_age_transa" maxlenght="5" name="child_age_transa" placeholder="Child Group A"  value="<?php if($hotels_list != ''){echo $hotels_list[0]->child_age_transa;} ?>" data-validate="required" data-message-required="Please enter the Hotel Code">
									</div>
								</div>
								<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">Child Group B</label>									
									<div class="col-sm-5">
										<input type="text" class="form-control" id="child_age_transb" maxlenght="5" name="child_age_transb" placeholder="Child Group B" value="<?php if($hotels_list != ''){ echo $hotels_list[0]->child_age_transb;} ?>">
									</div>
								</div>
								<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">Child Group C</label>									
									<div class="col-sm-5">
										<input type="text" class="form-control" id="child_age_transc" maxlenght="5" name="child_age_transc" placeholder="Child Group C" value="<?php if($hotels_list != ''){ echo $hotels_list[0]->child_age_transc; } ?>">
									</div>
								</div>
								<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">Child Group D</label>									
									<div class="col-sm-5">
										<input type="text" class="form-control" id="child_age_transd" maxlenght="5" name="child_age_transd" placeholder="Child Group D" value="<?php if($hotels_list != ''){ echo $hotels_list[0]->child_age_transd; } ?>">
									</div>
								</div>
								<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">Child Group E</label>									
									<div class="col-sm-5">
										<input type="text" class="form-control" id="child_age_transe" maxlenght="5" name="child_age_transe" placeholder="Child Group E" value="<?php if($hotels_list != ''){ echo $hotels_list[0]->child_age_transe; } ?>">
									</div>
								</div>
								
					
								<div class="form-group">
									<label class="col-sm-3 control-label">&nbsp;</label>									
									<div class="col-sm-5">
										<button type="submit" class="btn btn-success">Update Transfers Child Group</button>
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
		<!-- Chat Module -->
			<?php $this->load->view('general/chat');	?>	
	</div>
	<!-- Bottom Scripts -->
	<?php $this->load->view('general/load_js');	?>
	<script src="<?php echo base_url(); ?>assets/js/bootstrap-switch.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/jquery.validate.min.js"></script>	
	<script src="<?php echo base_url(); ?>assets/js/fileinput.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/selectboxit/jquery.selectBoxIt.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/ckeditor/ckeditor.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/ckeditor/adapters/jquery.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/bootstrap-datepicker.js"></script>
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
      
      function addMoreRooms(c) {
			var id = $('#rows_cnt').val();
			$("#rooms").css({'display':'inherit'});
			$("#rooms").append('<div class="form-group"><label for="field-1" class="col-sm-3 control-label">Exclude Checkout Date</label><div class="col-md-5"><input type="text" class="form-control datepicker" name="exclude_checkout_date[]" id="exclude_checkout_date'+id+'" data-min-date="<?php echo date('m-d-Y');?>" data-validate="required" data-message-required="Please Select the Date Range" /></div></div>');
			$('.datepicker').datepicker();
			id = id+1;
			$('#rows_cnt').val(id);
		}
		function removeLastRoom(v){
			var id = $('#rows_cnt').val();
			$('#rooms .form-group').last().remove();
			id = id-1;
			$('#rows_cnt').val(id);
		}
		 
	
		 
		 <?php if($hotels_list[0]->exclude_checkout_date != '') {
		  $exclude_checkout_date = explode(",", $hotels_list[0]->exclude_checkout_date);
			  
		  for($min = 1; $min < count($exclude_checkout_date); $min++) {  ?>
		  addMoreRooms(null);
		  $('#exclude_checkout_date'+<?php echo $min; ?>).val("<?php echo $exclude_checkout_date[$min]; ?>");
		<?php } } ?>
	
</script>
    
</body>
</html>
