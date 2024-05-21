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

<div class="bodyContent">
	<div class="panel panel-default clearfix">
		<!-- PANEL WRAP START -->
		<div class="panel-heading">
			<!-- PANEL HEAD START -->
			<div class="panel-title">
				<i class="fa fa-credit-card"></i> Hotel Management
			</div>
		</div>
		<!-- PANEL HEAD START -->
		
			<!-- PANEL BODY START -->
			<div class="panel-body">
				<form method="post" id="hotel11" name="hotel11" action="<?php echo site_url()."/index.php/hotel/upload_hotel_image"; ?>" class="form-horizontal form-groups-bordered validate" enctype= "multipart/form-data">				
					<fieldset form="user_edit">
				<legend class="form_legend">Add Hotel Images</legend>
<input type="hidden" name="hotel_id" value="<?= $hotel_data->id?>">
								<div class="form-group">
																		
									<div class="col-sm-2"></div>
									<div class="col-sm-5">
										<div class="fileinput fileinput-new" data-provides="fileinput">
											<div class="fileinput-new thumbnail" data-trigger="fileinput">
												<img src="<?php echo base_url(); ?>assets/images/logo.png" alt="Hotel Logo">
											</div>
											<div class="fileinput-preview fileinput-exists thumbnail"></div>
											<div>
												<span class="btn btn-white btn-file">
												    <!--<label for="field-3" class=" control-label">Choose Image</label>-->
													<span class="fileinput-new">Choose image</span>
													<span class="fileinput-exists">Change</span>
													<input type="file"  name="hotel_image[]" multiple accept="image/*" required>
												</span>
												<a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput">Remove</a>
											</div>
										</div>										
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">&nbsp;</label>									
									<div class="col-sm-5">
										<button type="submit" class="btn btn-success">Upload Hotel Image</button>
									</div>
											<label class="col-sm-1 control-label">&nbsp;</label>									
									<div class="col-sm-4">
									<!--<a href="<?php echo site_url()?>/hotels/hotel_crs_list/" class="btn btn-primary addnwhotl">Back to Hotels</a>-->
									<a href="<?php echo site_url().'index.php/hotel/room_crs_list/'.$hotel_data->id;?>" class="btn btn-info addnwhotl">Go to Rooms</a>
									</div>
								</div>
				</form>
			</div>

			<div class="table-responsive">
				<form action="" method="POST" autocomplete="off">
					<table class="table table-striped">
						<tr>
							<th>Sl No</th>
							<th>Image</th>
							<th>Status</th>
							<th>Actions</th>
						</tr>
				<tbody>
					<?php if(!empty($images))
							{ 
								foreach($images as $a => $list)
								{ 
					?>
						<tr>
							<td><?php echo ($a+1); ?></td>
							<td><?php echo '<img src="'.DOMAIN_HOTEL_IMAGE_DIR.$list->image.'" alt="" width="100" height="100">'; ?></td>
							<td>
							<?php if($list->status == "ACTIVE")
							{ 
								?>
								<button type="button" class="btn btn-green btn-icon icon-left my-actve">Active<i class="entypo-check"></i></button>
							<?php 
							}
							else
							{ 
								?>
									<button type="button" class="btn btn-orange btn-icon icon-left my-inactve">InActive<i class="entypo-cancel"></i></button>
							<?php 
							} 
							?>
							</td>
							<td class="center">
								<?php 
								if($list->status == "ACTIVE")
									{ 
								?>
									<a href="<?php echo site_url()."index.php/hotel/inactive_hotel_image/".$list->hotel_id."/".$list->id; ?>" class="btn btn-orange btn-sm btn-icon icon-left my-inactve"><i class="entypo-eye"></i>InActive</a>
								<?php 
								}
								else
								{ ?>
									<a href="<?php echo site_url()."index.php/hotel/active_hotel_image/".$list->hotel_id."/".$list->id; ?>" class="btn btn-green btn-sm btn-icon icon-left my-actve"><i class="entypo-check"></i>Active</a>
								<?php 
								} 
								?>
									 <a href="<?php echo site_url()."index.php/hotel/delete_hotel_images/".$list->hotel_id."/".$list->id; ?>"  class="btn btn-danger btn-sm btn-icon icon-left"><i class="entypo-cancel"></i>Delete</a>
							</td>
						</tr>
					<?php 
					}
					} 
					?>												
					</tbody>
				</table>
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
			var search_city  = $('#city_name').val();
			var country = $('#country').val();
		 	if(search_city!=''){
		 		geocodeAddress(search_city+','+country);	
		 	}
		});
	$("#ammenities").select2();
		$(document).ready(function(){
			var canc = "<?php echo $hotels_list[0]->cancellation_status; ?>";
			if(canc == 1){
				$("#cancellation").show();
			}else{
				$("#cancellation").show();
			}
			
		})

		function addMoreRooms1() {
			$("#cancellation_clone").css({'display':'inherit'});
			var id = $('#rows_cnt').val();
			
	    	$("#cancellation_clone").append( '<div class="form-group" style="widht:80%;" ><div class="col-sm-2">'+								
								'<input type="text" class="form-control" name="cancellation_from[]" id="cancellation_from'+id+'" value="">'+
								'</div>'+
								
								'<div class="col-sm-3">'+							
								'<input type="text" class="form-control" name="cancellation_nightcharge[]" id="cancellation_nightcharge'+id+'" value="">'+
								'</div>'+
								'<div class="col-sm-3 ">'+							
								' <input type="text" class="form-control" value="" name="cancellation_percentage[]" id="cancellation_percentage'+id+'" value=""> </div></div>');																				
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
		 jQuery(document).ready(function($){
			
                    var country_id = $( "#country" ).val();
                    var from_selected_city_id = "<?php if(isset($hotels_list[0]->city_details_id)) { echo $hotels_list[0]->city_details_id; } ?>";                                        
                    //alert(from_selected_city_id)
                    if (country_id != '') {
                        var select1 = $('#city_name');
                        $.ajax({
                            url: '<?php echo site_url(); ?>/hotel/get_city_name/' + country_id + '/' + from_selected_city_id,
                            success: function (data, textStatus, jqXHR) {                            
                                select1.html('');
                                //alert(data)
                                select1.html(data);
                                select1.trigger("chosen:updated");                                                                             		   			
                        	}
                         });
                    } 
                    var city_id = "<?php if(isset($hotels_list[0]->city_details_id)) { echo $hotels_list[0]->city_details_id; } ?>";
                    var selected_location_id = "<?php if(isset($hotels_list[0]->location_info)) { echo $hotels_list[0]->location_info; } ?>"; 
                    if (city_id != '') {
                        var location_select = $('#location_info');
                        $.ajax({
                            url: '<?php echo site_url(); ?>/hotel/get_location_name/' + city_id + '/' + selected_location_id,
                            success: function (data, textStatus, jqXHR) {                            
                                location_select.html('');
                                location_select.html(data);
                                location_select.trigger("chosen:updated");                                                                             		   			
                        	}
                         });
                    }      
      });
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

			$('#hotel11').submit(function(){		 			
	 			var number_filter = /^[0-9]*$/;

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

				if(location_info.value == "select" && location_name.value == ""){
					location_info.style.border = "1px solid #f52c2c";   
					location_name.style.border = "1px solid #f52c2c";   										
					return false;
				}

				var hotel_type_id = $('#hotel_type').val();				
				if(hotel_type_id == "0"){					
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

				if(phone_number.value.length > 50 ) {
					    phone_number.style.border = "1px solid #f52c2c";   
						phone_number.focus(); 
						return false; 
				}				
						
				

			});	

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
		
		function select_location(city_id){				 
		 if (city_id != '') {         	  
          var location_select = $('#location_info');          
          $.ajax({
            url: '<?php echo site_url(); ?>/hotel/get_location_name/' + city_id,
            success: function (data, textStatus, jqXHR) {                                    
              location_select.html('');
              location_select.html(data);
              location_select.trigger("chosen:updated");  
          	}
           });         
          }		
		 }
		
		$("#add_location").click(function(){
				$("#add_location").hide();
				$("#location_info").hide();
				$("#location_name").slideToggle("slow");
		});
		 
		
	</script>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false&key=AIzaSyAiR9CLZshY_vQpB7z5M7nIGCg16gfo2E8"></script>
    <script>

    	$(function(){
    	
    	});
		var map;
		var geocoder;
		var mapOptions = { center: new google.maps.LatLng(0.0, 0.0), zoom: 2,
        mapTypeId: google.maps.MapTypeId.ROADMAP };
	
		function initialize() {				
			var latitude =  <?php echo $hotels_list[0]->latitude; ?>; 
			var longitude = <?php echo $hotels_list[0]->longitude; ?>;
			var myOptions = {
                center: new google.maps.LatLng(latitude,longitude),
                zoom: 10,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };                     

            var newPosition = new google.maps.LatLng(latitude,longitude);                        
            geocoder = new google.maps.Geocoder();
            var map = new google.maps.Map(document.getElementById("map_canvas"),
            myOptions);            
            marker = new google.maps.Marker({ //on créé le marqueur
                        position: newPosition, 
                        map: map
            });            

            google.maps.event.addListener(map, 'click', function(event) {
                placeMarker(event.latLng);
            });

            var marker;
            function placeMarker(location) {
            	alert("You are changing the Address of the hotel");
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
		 
	
		 
		 <?php if(isset($hotels_list[0]->exclude_checkout_date) && $hotels_list[0]->exclude_checkout_date != '') {
		  $exclude_checkout_date = explode(",", $hotels_list[0]->exclude_checkout_date);
			  
		  for($min = 1; $min < count($exclude_checkout_date); $min++) {  ?>
		  addMoreRooms(null);
		  $('#exclude_checkout_date'+<?php echo $min; ?>).val("<?php echo $exclude_checkout_date[$min]; ?>");
		<?php } } ?>
	
	  /*function remove_image(hotel_id,image_name,id){
		if(hotel_id != '' && image_name != '') {
			$.ajax({
						url: '<?php echo site_url(); ?>/hotel/unlink_image/' + hotel_id + '/' + image_name,
						success: function (data, textStatus, jqXHR) {                            
							  $("#img_" + id).remove();                                                                       		   			
						}
					 });
					 }
	  }*/

	  function remove_image(hotel_id,image_name,id){
		if(hotel_id != '' && image_name != '') {


					$.ajax({
						url: '<?php echo site_url(); ?>index.php/hotel/unlink_image/' + hotel_id + '/' + image_name,
						success: function (data, textStatus, jqXHR) {   
							$("#img_" + id).remove(); 

							var old_image_box = $('input[name=hotel_image_old]');   
							
							var oib_arr = old_image_box.val().split(',');

					     	var new_images = [];

					     	if(oib_arr.length>0)
				     		{	
				     			for(i=0;i<oib_arr.length;i++)
						     	{
						     		if(i!=(parseInt(id)))
						     		{
						     			new_images.push(oib_arr[i]);
						     		}
						     	}
						     	var new_images_a = new_images.join(',');
			     				$('input[name=hotel_image_old]').val(new_images_a);
				     		} else {
				     			$('input[name=hotel_image_old]').val('');
				     		}                                                               		   			
						}
					 });
					 }
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
</script>
<script>
function deletechecked()
    {
        if(confirm("Delete image"))
        {
            return true;
        }
        else
        {
            return false;  
        } 
    }
</script>
<script >
$('#top_deals').change(function(){
				var current_status = $('#top_deals').val();
				if(current_status == "1")
					$('#top_deals').val('0');
				else
					$('#top_deals').val('1');
			});
</script>