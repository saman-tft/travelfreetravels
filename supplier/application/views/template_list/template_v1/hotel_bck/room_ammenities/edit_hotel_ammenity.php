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
				<i class="fa fa-credit-card"></i> Edit Room Amenities
			</div>
		</div>
		<!-- PANEL HEAD START -->
		
			<!-- PANEL BODY START -->
			<div class="panel-body">
				<form method="post" id="hotel_ammenity" name="hotel_ammenity" action="<?php echo site_url()."/hotels/update_room_ammenity/".base64_encode(json_encode($ammenities_list[0]->id)); ?>" class="form-horizontal form-groups-bordered validate" enctype= "multipart/form-data">
					<fieldset form="user_edit">
				<legend class="form_legend">Room Amenities</legend>
				<input name="user_id" id="user_id" class=" user_id hiddenIp" required="" value="1128" type="hidden">
				<div class="form-group">
					<label class="col-sm-3 control-label" for="title" form="user_edit">Room Amenity Name<span class="text-danger">*</span></label>
					<div class="col-sm-6">
						<input type="text" class="form-control" required id="" name="hotel_ammenity_name" value="<?php echo $ammenities_list[0]->name; ?>" data-validate="required" data-message-required="Please enter the Room Amenity Name">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Room Amenity Status</label>									
					<div class="col-sm-5">
						<div id="label-switch" class="make-switch" data-on-label="Active" data-off-label="InActive">
							<input type="checkbox" name="status" value="<?php echo $ammenities_list[0]->status; ?>" id="status" <?php if($ammenities_list[0]->status == "ACTIVE"){ echo "checked"; } ?>>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">&nbsp;</label>									
					<div class="col-sm-5">
						<button type="submit" class="btn btn-success">Update Room Amenity</button>
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
