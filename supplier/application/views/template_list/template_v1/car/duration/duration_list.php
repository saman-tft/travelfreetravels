<style>
	.addnwhotl {margin-right: 5px;margin-top: 5px;}
</style>
<!-- HTML BEGIN -->
<div class="bodyContent">
	<div class="panel panel-default clearfix">
		<!-- PANEL WRAP START -->
		<div class="panel-heading">
			<!-- PANEL HEAD START -->
			<div class="panel-title">
				<i class="fa fa-credit-card"></i> Duration Management
			</div>
		</div>
		<a href="<?php echo site_url()."/
		duration/add_duration" ?>" class="btn btn-primary addnwhotl pull-right">Add Duration</a>
		<!-- PANEL HEAD START -->
		<div class="panel-body">
			<!-- PANEL BODY START -->
			<div class="table-responsive">
				<form action="" method="POST" autocomplete="off">
					<table class="table table-striped" id="duration_li">
						<tr>
							<th>Sl No</th>
							<th>Name</th>
							<th>From Date</th>
							<th>To Date</th>
							<th>Status</th>
							<th>Actions</th>
							
						</tr>
				<tbody>
					<?php if($duration_list!=''){
						$a = 1;
						foreach ($duration_list as $key => $value) { ?>
						<tr>
							<td><?php echo $a++; ?></td>
							<td><?php echo $value->duration_name; ?></td>
							<td><?php echo $value->duration_from_date; ?></td>							
							<td><?php echo $value->duration_to_date; ?></td>							
							<td>
								<?php if($value->status == "1"){ ?>
									<button type="button" class="btn btn-green btn-icon icon-left">Active<i class="entypo-check"></i></button>
								<?php }else{ ?>
										<button type="button" class="btn btn-orange btn-icon icon-left">InActive<i class="entypo-cancel"></i></button>
								<?php } ?>
							</td>
							<td class="center">
								<?php if($value->status == "1"){ ?>
									
									<a href="<?php echo site_url()."/duration/inactive_duration/".base64_encode(json_encode($value->duration_id)); ?>"><button type="button" class="btn btn-orange tooltip-primary btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="InActive"><i class="glyphicon glyphicon-off"></i></button></a>
								<?php }else{ ?>
									
									<a href="<?php echo site_url()."/duration/active_duration/".base64_encode(json_encode($value->duration_id)); ?>"> <button type="button" class="btn btn-success tooltip-primary btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="Active"><i class="glyphicon glyphicon-off"></i></button></a>
								<?php } ?>
								
								<a href="<?php echo site_url()."/duration/edit_duration/".base64_encode(json_encode($value->duration_id)); ?>" ><button type="button" class="btn btn-blue tooltip-primary btn-sm" data-placement="top" data-toggle="tooltip" data-original-title="Edit"><i class="glyphicon glyphicon-pencil"></i></button></a>										
								
								<a href="<?php echo site_url()."/duration/delete_duration/".base64_encode(json_encode($value->duration_id)); ?>"><button type="button" class="btn btn-red tooltip-primary btn-sm" data-placement="top" data-toggle="tooltip" data-original-title="Delete"><i class="glyphicon glyphicon-remove"></i></button></a>				
							</td>
							
						</tr>
					<?php }} ?>												
					</tbody>
				</table>
				</table>
				</form>
			</div>
		</div>
		<!-- PANEL BODY END -->
	</div>
	<!-- PANEL END -->
</div>
<script type="text/javascript">
		jQuery(document).ready(function($)
		{
			var table = $("#duration_list").dataTable({
				"sPaginationType": "bootstrap",
				"oTableTools": {
				},
			});
			table.columnFilter({
				"sPlaceHolder" : "head:after"
			});
		});		
	</script>
<!-- Page Ends Here -->
