<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">	
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="description" content="Provab Admin Panel" />
	<meta name="author" content="" />	
	<title><?php echo PAGE_TITLE; ?> | Dashboard</title>	
	<!-- Load Default CSS and JS Scripts -->
	<?php $this->load->view('general/load_css');	?>	
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/selectboxit/jquery.selectBoxIt.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/select2/select2-bootstrap.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/select2/select2.css">
</head>
<body id="top" oncontextmenu="return false" class="page-body <?php if(isset($transition)){ echo $transition; } ?>" data-url="<?php echo PROVAB_URL; ?>">
	<div class="page-container <?php if(isset($header) && $header == 'header_top'){ echo "horizontal-menu"; } ?> <?php if(isset($header) && $header == 'header_right'){ echo "right-sidebar"; } ?> <?php if(isset($sidebar)){ echo $sidebar; } ?>">
		<?php if(isset($header) && $header == 'header_top'){ $this->load->view('general/header_top'); }else{ $this->load->view('general/left_menu'); }	?>
		<div class="main-content">
			<?php if(!isset($header) || $header != 'header_top'){ $this->load->view('general/header_left'); } ?>
			<?php $this->load->view('general/top_menu');	?>
			<hr />
			<ol class="breadcrumb bc-3">						
				<ol class="breadcrumb bc-3">						
					 <?php if($supplier_rights == 1){
					 $url = site_url()."/supplier_dashboard";
				 } else {
					  $url = site_url()."/dashboard/dashboard";
				 } ?>	
				 				
				<li><a href="<?php echo $url; ?>"><i class="entypo-home"></i>Home</a></li>
				<li><a href="<?php echo site_url()."/hotel/hotel_crs_list"; ?>">Hotel Management</a></li>
				<li class="active"><strong>Cancellation Policy</strong></li>
			</ol>
			<div class="row">
				<div class="col-md-12">					
					<div class="panel panel-primary" data-collapsed="0">					
						<div class="panel-heading">
							<div class="panel-title">
								Update Cancellation Policy
							</div>							
							<div class="panel-options">
								<a href="#sample-modal" data-toggle="modal" data-target="#sample-modal-dialog-1" class="bg"><i class="entypo-cog"></i></a>
								<a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
								<a href="#" data-rel="close"><i class="entypo-cancel"></i></a>
							</div>
						</div>						
						<div class="panel-body">							
							<form method="post" name="domain" id="domain" action="<?php echo site_url()."/hotel/cancellation_policy/".base64_encode($hotel_list[0]->hotel_details_id); ?>" class="form-horizontal form-groups-bordered validate" enctype= "multipart/form-data">				
									    <div class="form-group">
											<label class="col-md-2 control-label">From(days)</label>
											<label class="col-md-3 control-label">To(Days)</label>
											<label class="col-md-4 control-label">Charges (%) includes tranfers</label>
											<label class="col-md-2 control-label">Arrival</label>											
										</div>
							
								<div class="form-group" id="clone_1">															
									<div class="col-sm-3">
										<input type="text" class="form-control" name="canecllation_from[]" id="canecllation_from" value="<?php if($cancellation_policy != ''){ echo $cancellation_policy[0]->cancellation_from; } ?>">
									</div>
															
									<div class="col-sm-3">
										<input type="text" class="form-control" name="canecllation_to[]" id="canecllation_to" value="<?php if($cancellation_policy != ''){ echo $cancellation_policy[0]->cancellation_to; } ?>">
									</div>
									
												
									<div class="col-sm-3">
										<input type="text" class="form-control" name="cancellation_charge[]" id="cancellation_charge" value="<?php if($cancellation_policy != ''){ echo $cancellation_policy[0]->cancellation_charge; } ?>">
									</div>
									
									<input type="hidden" name="cancellation_arrival[]" value="PRIOR" />
								</div>
								
								 <div id="cancellation_clone" style="display:none;" class="form-group"></div>
		    
							   <div class="form-group" id="btn">
								   <div class="col-md-9"> </div>
											<div class="col-md-1"><input type="hidden" id="rows_cnt" value="1"/><button type="button" class="btn btn-success" onclick="addMoreRooms1();">Add</button></div>
											<div class="col-md-2"><button type="button" class="btn btn-success" onclick="removeLastRoom1(this);">Remove Last</button></div>
							  </div>
							  
							  <div class="form-group">
							  	<label for="field-1" class="col-sm-3 control-label">Policy Description</label>									
											<div class="col-sm-8">
												<div class="input-group">
													<textarea class="form-control " id="cancellation_policy" cols="50" name="cancellation_policy" > <?php echo $hotel_list[0]->cancellation_policy; ?> </textarea>
												</div>
											</div>
							  </div>
								<div class="form-group">
									<label class="col-sm-3 control-label">&nbsp;</label>									
									<div class="col-sm-5">
										<button type="submit" class="btn btn-success">Update Cancellation Policy</button>
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
	<script src="<?php echo base_url(); ?>assets/js/select2/select2.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/ckeditor/ckeditor.js"></script>
	<script>
		function addMoreRooms1() {
			$("#cancellation_clone").css({'display':'inherit'});
			var id = $('#rows_cnt').val();
			
	    	$("#cancellation_clone").append( '<div class="form-group">'+
								'<div class="col-sm-3">'+								
								'<input type="text" class="form-control" name="canecllation_from[]" id="canecllation_from'+id+'" value="">'+
								'</div>'+
								'<div class="col-sm-3">'+
								'<input type="text" class="form-control" name="canecllation_to[]" id="canecllation_to'+id+'" value="">'+
								'</div>'+
								'<div class="col-sm-3">'+							
								'<input type="text" class="form-control" name="cancellation_charge[]" id="cancellation_charge'+id+'" value="">'+
								'</div>'+
								'<div class="col-sm-3 none">'+							
								' <input type="hidden" value="PRIOR" name="cancellation_arrival[]" /> </div></div>');
								
				
								
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
		
		 <?php if($cancellation_policy != '') {
		  for($cp = 1; $cp < count($cancellation_policy); $cp++) {  ?>
		
		  addMoreRooms1(null);
		
		  $('#canecllation_from'+<?php echo $cp; ?>).val("<?php echo $cancellation_policy[$cp]->cancellation_from; ?>");
		  $('#canecllation_to'+<?php echo $cp; ?>).val("<?php echo $cancellation_policy[$cp]->cancellation_to; ?>");
		  $('#cancellation_charge'+<?php echo $cp; ?>).val("<?php echo $cancellation_policy[$cp]->cancellation_charge; ?>");
		  $('#cancellation_arrival'+<?php echo $cp; ?>).val("<?php echo $cancellation_policy[$cp]->cancellation_arrival; ?>");
	
	<?php } } ?>
			
	</script>
</body>
</html>
