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
				<i class="fa fa-credit-card"></i> Duration Management
			</div>
		</div>
		<!-- PANEL HEAD START -->
		
			<!-- PANEL BODY START -->
			<div class="panel-body">
				<form method="post" id="tax" name="tax" action="<?php echo site_url()."/duration/add_duration"; ?>" class="form-horizontal form-groups-bordered validate" enctype= "multipart/form-data">				
					<fieldset form="user_edit">
				<legend class="form_legend">Add Duration</legend>
				

								<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">Duration Name</label>									
									<div class="col-sm-5"> 
										 <input type="text" class="form-control" name="duration_name" id="duration_name" data-validate="required" data-message-required="Please Enter the Seasons Name" value="<?php if(isset($duration_name)){ echo $duration_name; }?>"/>
										 <?php echo form_error('duration_name',  '<span for="field-1" class="validate-has-error">', '</span>'); ?>
									</div>
								</div>	
								<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">Season Date</label>									
									<div class="col-sm-5"> 
										 <input type="text" class="form-control" id="duration_date_range" name="duration_date_range" readonly  data-validate="required" data-message-required="Please Select the Date Range" value="<?php if(isset($duration_date_range)){ echo $duration_date_range; }?>" />
										 <?php echo form_error('duration_date_range',  '<span for="field-1" class="validate-has-error">', '</span>'); ?>
									</div>
								</div>									  								
								<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label"></label>
									<div class="col-sm-5"> 	
										<button type="submit" class="btn btn-success">Add Duration</button>
									</div>
								</div>
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
	
	<script src="<?php echo base_url(); ?>hotel_assets/js/bootstrap-switch.min.js"></script>
	<script src="<?php echo base_url(); ?>hotel_assets/js/jquery.validate.min.js"></script>	
	<script src="<?php echo base_url(); ?>hotel_assets/js/fileinput.js"></script>
	<script src="<?php echo base_url(); ?>hotel_assets/js/selectboxit/jquery.selectBoxIt.min.js"></script>
	<script src="<?php echo base_url(); ?>hotel_assets/js/jquery.bootstrap.wizard.min.js"></script>
	<script src="<?php echo base_url(); ?>hotel_assets/js/ckeditor/ckeditor.js"></script>
	<script src="<?php echo base_url(); ?>hotel_assets/js/ckeditor/adapters/jquery.js"></script>
	<script src="<?php echo base_url(); ?>hotel_assets/js/bootstrap-timepicker.min.js"></script>
	<script src="<?php echo base_url(); ?>hotel_assets/js/bootstrap-datepicker.js"></script>

	<script src="<?php echo base_url(); ?>hotel_assets/js/select2/select2.min.js"></script>
   <script src="<?php echo base_url(); ?>hotel_assets/js/jquery-ui.js"></script>

  <script src="<?php echo base_url(); ?>hotel_assets/js/jquery.dataTables.min.js"></script>
  <script src="<?php echo base_url(); ?>hotel_assets/js/datatables/TableTools.min.js"></script>
  <script src="<?php echo base_url(); ?>hotel_assets/js/dataTables.bootstrap.js"></script>
  <script src="<?php echo base_url(); ?>hotel_assets/js/datatables/jquery.dataTables.columnFilter.js"></script>
  <script src="<?php echo base_url(); ?>hotel_assets/js/datatables/lodash.min.js"></script>
  <script src="<?php echo base_url(); ?>hotel_assets/js/datatables/responsive/js/datatables.responsive.js"></script>   
 <!--    <script src="<?= base_url(); ?>assets/js/plugins/datatables/dataTables.overrides.js" type="text/javascript"></script>
    <script src="<?= base_url(); ?>assets/js/plugins/lightbox/lightbox.min.js" type="text/javascript"></script>
 <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jquery-ui.css" type="text/css">-->
  
	<script src="<?php echo base_url(); ?>hotel_assets/js/daterangepicker/moment.min.js"></script>
	<script src="<?php echo base_url(); ?>hotel_assets/js/daterangepicker/daterangepicker.js"></script>

  <script>
		$(document).ready(function () { 
					$('#duration_date_range').daterangepicker({
						format: 'DD/MM/YYYY'
		        
		    });
		});

	  $('#status').change(function(){
				var status = $('#status').val();
				if(status == "ACTIVE")
					$('#status').val('INACTIVE');
				else
					$('#status').val('ACTIVE');
	  });	

	</script>