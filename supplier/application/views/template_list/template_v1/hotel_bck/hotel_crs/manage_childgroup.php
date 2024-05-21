<?php
//debug($hotels_list[0]);die;
?>
<style>
.error{
	color:red;
}
</style>
<link rel="stylesheet" href="<?php echo base_url(); ?>hotel_assets/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>hotel_assets/css/font-icons/entypo/css/entypo.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>hotel_assets/css/bootstrap.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>hotel_assets/css/provab-core.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>hotel_assets/css/provab-theme.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>hotel_assets/css/provab-forms.css">
	
	
<!-- HTML BEGIN -->
<div class="bodyContent">
	<div class="panel panel-default clearfix">
		<div class="col-md-12">
			<?=$GLOBALS['CI']->template->isolated_view('hotel/hotel_widgets',['hotel_id'=>$hotels_list[0]->hotel_details_id,'hotel_name'=> $hotel_name,'active'=>$wizard_status, 'current'=>'step2'])?>
	      </div>

	       <div class="clearfix"></div>
		<!-- PANEL WRAP START -->
		<div class="panel-heading">
			<!-- PANEL HEAD START -->
			<div class="panel-title">
				<i class="fa fa-credit-card"></i> Manage Hotel Age Group
			</div>
			<a href="<?php echo site_url()?>/hotels/hotel_crs_list/" class="btn btn-primary addnwhotl pull-right">Back to Hotels</a>
		</div>
		<!-- PANEL HEAD START -->
		
			<!-- PANEL BODY START -->
			<div class="panel-body">

				<form method="post" id="hotel_child_age" name="hotel" action=<?php echo site_url()."/hotels/manage_hotelchildgroup/".base64_encode(json_encode($hotels_list[0]->hotel_details_id));?> class="form-horizontal form-groups-bordered validate" enctype= "multipart/form-data">				
					
									<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">Infant Age Group</label>									
									<div class="col-sm-5">
										<input type="text" class="form-control" id="child_group_a" maxlenght="5" name="child_group_a" placeholder="Infant Age Group"  value="<?php if($hotels_list!= ''){echo $hotels_list[0]->child_group_a;} ?>" data-validate="required" data-message-required="Please enter the Child Group A">
										<span id="child_group_a-error" class="error" for="child_group_a"> </span>
										<span style="color:#333;">Eg: 0-2</span>
									</div>
								</div>
								<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">Child Age Group</label>									
									<div class="col-sm-5">
										<input type="text" class="form-control" id="child_group_b" maxlenght="5" name="child_group_b" placeholder="Child Group B" value="<?php if($hotels_list!= ''){ echo $hotels_list[0]->child_group_b;} ?>">
										<span id="child_group_b-error" class="error" for="child_group_b"> </span>
										<span style="color:#333;">Eg: 3-12</span>
									</div>
								</div>
							<!-- 	<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">Child Group C</label>									
									<div class="col-sm-5">
										<input type="text" class="form-control" id="child_group_c" maxlenght="5" name="child_group_c" placeholder="Child Group C" value="<?php if($hotels_list!= ''){ echo $hotels_list[0]->child_group_c; } ?>">
										<span id="child_group_c-error" class="error" for="child_group_c"> </span>
										<span style="color:#333;">Ex: 5-6</span>
									</div>
								</div> -->
								<!-- <div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">Child Group D</label>									
									<div class="col-sm-5">
										<input type="text" class="form-control" id="child_group_d" maxlenght="5" name="child_group_d" placeholder="Child Group D" value="<?php if($hotels_list!= ''){ echo $hotels_list[0]->child_group_d; } ?>">
										<span id="child_group_d-error" class="error" for="child_group_d"> </span>
										<span style="color:#333;">Ex: 7-8</span>
									</div>
								</div> -->
								<!-- <div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">Child Group E</label>									
									<div class="col-sm-5">
										<input type="text" class="form-control" id="child_group_e" maxlenght="5" name="child_group_e" placeholder="Child Group E" value="<?php if($hotels_list!= ''){ echo $hotels_list[0]->child_group_e; } ?>">
										<span id="child_group_e-error" class="error" for="child_group_e"> </span>
										<span style="color:#333;">Ex: 9-10</span>
									</div>
								</div> -->
								
					
								<div class="form-group">
									<label class="col-sm-3 control-label">&nbsp;</label>									
									<div class="col-sm-5">
										<button type="submit" class="btn btn-success">Update Hotel Age Group</button>
									</div>
								</div>
							</form>
			</div>
		
		<!-- PANEL BODY END -->
	</div>
	<!-- PANEL END -->
</div>

	<script src="<?php echo base_url(); ?>hotel_assets/js/jquery.validate.min.js"></script>
	
	</script>


<script>
$("#hotel_child_age").validate({
      
	submitHandler: function(form) { 
		var action = $("#hotel_child_age").attr('action');
		//$("#signup").attr("disabled", "disabled");
		$.ajax({
			type: "POST",
			url: action,
			data: $("#hotel_child_age").serialize(),
			dataType: "json",
			success: function(data){
				
				if(data.status == 3) {
					//$("#signup").attr("disabled", "");
					$.each(data, function(key, value) {
					 $('#'+key).text(value);
					});
				}
				
				if(data.status == 1){
					window.location.href = data.success_url;
				}else{
				    //$("#signup").attr("disabled", "");
				    $.each(data, function(key, value) {
					 $('#'+key).text(value);
					});  	
				}
			}
		}); 
		return false; 
	}  
});
</script>