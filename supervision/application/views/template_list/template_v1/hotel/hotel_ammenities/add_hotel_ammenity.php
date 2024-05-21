<link rel="stylesheet" href="<?php echo base_url(); ?>hotel_assets/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>hotel_assets/css/font-icons/entypo/css/entypo.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>hotel_assets/css/bootstrap.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>hotel_assets/css/provab-core.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>hotel_assets/css/provab-theme.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>hotel_assets/css/provab-forms.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>hotel_assets/js/selectboxit/jquery.selectBoxIt.css">
<!-- HTML BEGIN -->
<div class="bodyContent">
	<div class="panel panel-default clearfix">
		<!-- PANEL WRAP START -->
		<div class="panel-heading">
			<!-- PANEL HEAD START -->
			<div class="panel-title">
				<i class="fa fa-credit-card"></i> Add Hotel Amenity
			</div>
		</div>
		<!-- PANEL HEAD START -->
		
			<!-- PANEL BODY START -->
			<div class="panel-body">
				<form method="post" id="hotel_ammenity" name="hotel_ammenity" action="<?php echo site_url()."index.php/hotel/save_hotel_amenities_data"; ?>" class="form-horizontal form-groups-bordered validate" enctype= "multipart/form-data">
					<fieldset form="user_edit">
				<legend class="form_legend">Hotel Amenities</legend>
				<input name="user_id" id="user_id" class=" user_id hiddenIp" required="" value="1128" type="hidden">

				<!-- Adding icon image code hided -->
				<div class="form-group">
					<label class="col-sm-3 control-label" for="title" form="user_edit">Hotel Amenity Name<span class="text-danger">*</span></label>
					<div class="col-sm-6">
						<input type="text" required class="form-control" id="" name="hotel_ammenity_name" placeholder="Hotel Amenity Name" data-validate="required" data-message-required="Please enter the Hotel Amenity Name">
					</div>
				</div>
				<!-- <div class="form-group">
					<label class="col-sm-3 control-label" for="title" form="user_edit">Hotel Amenity Image<span class="text-danger">*</span></label>
					<div class="col-sm-6">
						<input type="file" required class="form-control" id="" name="hotel_ammenity_image" placeholder="Hotel Amenity Image" data-validate="required" data-message-required="Please upload Hotel Ammenity Image">
					</div>
					<span>Only .svg files are allowed to upload </span>
				</div> -->
				<div class="form-group">
					<label class="col-sm-3 control-label">Hotel Amenity Status</label>									
					<div class="col-sm-5">
						<div id="label-switch" class="make-switch" data-on-label="Active" data-off-label="InActive">
							<input type="checkbox" name="status" value="ACTIVE" id="status" checked>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">&nbsp;</label>									
					<div class="col-sm-5">
						<button type="submit" class="btn btn-success">Add Hotel Amenity</button>
					</div>
				</div>
				</form>
			</div>
		
		<!-- PANEL BODY END -->
	</div>
	<!-- PANEL END -->
</div>

<script src="<?php echo base_url(); ?>hotel_assets/js/bootstrap-switch.min.js"></script>
	<script src="<?php echo base_url(); ?>hotel_assets/js/jquery.validate.min.js"></script>	
	<script src="<?php echo base_url(); ?>hotel_assets/js/fileinput.js"></script>
	<script src="<?php echo base_url(); ?>hotel_assets/js/selectboxit/jquery.selectBoxIt.min.js"></script>

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

<!-- Page Ends Here -->
<script>
		$(function(){
			$('#status').change(function(){
				var current_status = $('#status').val();
				if(current_status == "ACTIVE")
					$('#status').val('INACTIVE');
				else
					$('#status').val('ACTIVE');
			});
			
			var ammenity_name = document.getElementById('field-1');
			
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

				if(ammenity_name.value.length < 2 || ammenity_name.value.length >150) {
					ammenity_name.style.border = "1px solid #f52c2c";   
				    ammenity_name.focus(); 
					return false; 
				}	
				
			});	
			
			$('#hotel_ammenity').submit(function() {
				
				var filter = /^[a-zA-Z 0-9!@#$%^&*_() - +=:;'",. ]*$/;
				if(ammenity_name.value != '')
				{
					if(!(ammenity_name.value.match(filter)))
					{
						ammenity_name.style.border = "1px solid #f52c2c";   
						ammenity_name.focus(); 
						return false; 
					}
				}
				else
				{
					ammenity_name.style.border = "1px solid #f52c2c";   
					ammenity_name.focus(); 
					return false; 
				}

				if(ammenity_name.value.length < 2 || ammenity_name.value.length >50) {
					ammenity_name.style.border = "1px solid #f52c2c";   
				    ammenity_name.focus(); 
					return false; 
				}					
				
				});
			
			
		});
	</script>