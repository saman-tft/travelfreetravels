
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
				<i class="fa fa-credit-card"></i> Hotel Types List
			</div>
		</div>
		<!-- PANEL HEAD START -->
		
			<!-- PANEL BODY START -->
			<div class="panel-body">
				<form method="post" name="hotel_type" id="hotel_type" action="<?php echo site_url()."index.php/hotel/update_hotel_type/".base64_encode(json_encode($hotel_types_list[0]->id)); ?>" class="form-horizontal form-groups-bordered validate" enctype= "multipart/form-data">
					<fieldset form="user_edit">
				<legend class="form_legend">Edit Hotel Type</legend>
				<input name="user_id" id="user_id" class=" user_id hiddenIp" required="" value="1128" type="hidden">
				<div class="form-group">
					<label class="col-sm-3 control-label" for="title" form="user_edit">Hotel Type Name<span class="text-danger">*</span></label>
					<div class="col-sm-6">
						<input type="text" class="form-control" id="field-1" name="hotel_type_name" value="<?php echo $hotel_types_list[0]->name; ?>" data-validate="required" data-message-required="Please enter the Hotel Type Name">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Hotel Type Status</label>									
					<div class="col-sm-5">
						<div id="label-switch" class="make-switch" data-on-label="Active" data-off-label="InActive">
							<input type="checkbox" name="status" value="<?php echo $hotel_types_list[0]->status; ?>" id="status" <?php if($hotel_types_list[0]->status == "ACTIVE"){ echo "checked"; } ?>>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">&nbsp;</label>									
					<div class="col-sm-5">
						<button type="submit" class="btn btn-success">Update Hotel Type</button>
					</div>
				</div>
				</form>
			</div>
		
		<!-- PANEL BODY END -->
	</div>
	<!-- PANEL END -->
</div>

<!-- Page Ends Here -->
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
	<script>
		$(function(){
			$('#status').change(function(){
				var current_status = $('#status').val();
				if(current_status == "ACTIVE")
					$('#status').val('INACTIVE');
				else
					$('#status').val('ACTIVE');
			});
			
			var hotel_type = document.getElementById('field-1');
			
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

				if(hotel_type.value.length < 2 || hotel_type.value.length > 50) {
					    hotel_type.style.border = "1px solid #f52c2c";   
						hotel_type.focus(); 
						return false; 
				}   
			    }) ;
			    
				$('#hotel_type').submit(function() {
					var filter = /^[a-zA-Z 0-9!@#$%^&*_() - +=:;'",. ]*$/;
				if(hotel_type.value != '')
				{
					if(!(hotel_type.value.match(filter)))
					{
						hotel_type.style.border = "1px solid #f52c2c";   
						hotel_type.focus(); 
						return false; 
					}
				}
				else
				{
					hotel_type.style.border = "1px solid #f52c2c";   
					hotel_type.focus(); 
					return false; 
				}

				if(hotel_type.value.length < 2 || hotel_type.value.length > 50) {
					    hotel_type.style.border = "1px solid #f52c2c";   
						hotel_type.focus(); 
						return false; 
				}
			 
		});
		
	});
	</script>