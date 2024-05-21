<style>
	.addnwhotl {margin-right: 5px;margin-top: 5px;}
</style>
<!-- HTML BEGIN -->
<div class="bodyContent">
	<div class="panel panel-default clearfix">
		<div class="col-md-12 hide" >
	        <?=$GLOBALS['CI']->template->isolated_view('hotel/hotel_widgets',['hotel_id'=>$hotel_id,'hotel_name'=>$hotel_name, 'active'=>$wizard_status, 'current'=>'step4'])?>
	      </div>

	       <div class="clearfix"></div>
		<!-- PANEL WRAP START -->
		<div class="panel-heading" style="position: relative;">
			<!-- PANEL HEAD START -->
			<div class="panel-title">
				<i class="fa fa-credit-card"></i> Seasons Management
				
			</div>
				<a href="<?php echo site_url()?>index.php/hotel/hotel_crs_list/<?=$hotel_id?>" class="btn btn-primary addnwhotl pull-right">Back to Hotels</a>
				<a href="<?php echo site_url()."index.php/hotel/add_season/".$hotel_id; ?>" class="btn btn-primary addnwhotl pull-right" >Add Seasons</a>
		</div>
		<!-- PANEL HEAD START -->
		<div class="panel-body">
			<!-- PANEL BODY START -->
			<div class="table-responsive">
				<form action="" method="POST" autocomplete="off">
					<table class="table table-striped">
						<tr>
							<th>Sl No</th>
							<th>Seasons</th>
							
							<th>From Date</th>
							<th>To Date</th>
							<th>Status</th>
							<th>Actions</th>
							
						</tr>
				<tbody>
					<?php if($seasons_list!=''){ for($a=0;$a<count($seasons_list);$a++){ ?>
						<tr>
							<td><?php echo ($a+1); ?></td>
							<td><?php echo $seasons_list[$a]->seasons_name; ?></td>							
							
							<td><?php if($seasons_list[$a]->seasons_from_date == "0000-00-00"){ echo "-";} else{ echo $seasons_list[$a]->seasons_from_date; }?></td>
							<td><?php if($seasons_list[$a]->seasons_to_date == "0000-00-00"){ echo "-";} else{ echo $seasons_list[$a]->seasons_to_date; }?></td>
							<td>
								<?php if($seasons_list[$a]->status == "ACTIVE"){ ?>
									<button type="button"  class="btn btn-green btn-icon icon-left my-actve">Active<i class="entypo-check"></i></button>
								<?php }else{ ?>
										<button type="button" style="background-color: #b74f4f;" class="btn btn-orange btn-icon icon-left my-inactve">InActive<i class="entypo-cancel"></i></button>
								<?php } ?>
							</td>
							<td class="center">
								<?php if($seasons_list[$a]->status == "ACTIVE"){ ?>
									
									<a href="<?php echo site_url()."index.php/hotel/inactive_seasons/".$seasons_list[$a]->seasons_details_id; ?>"><button type="button" class="btn btn-orange tooltip-primary btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="InActive" title="Inactive"><i class="fa fa-times" aria-hidden="true"></i></button></a>
								<?php }else{ ?>
									
									<a href="<?php echo site_url()."index.php/hotel/active_seasons/".$seasons_list[$a]->seasons_details_id; ?>"> <button type="button" class="btn btn-success tooltip-primary btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="Active" title="Active"><i class="fa fa-check" aria-hidden="true"></i></button></a>
								<?php } ?>
								
								<a href="<?php echo site_url()."index.php/hotel/edit_seasons/".$seasons_list[$a]->seasons_details_id; ?>" ><button type="button" class="btn btn-blue tooltip-primary btn-sm" data-placement="top" data-toggle="tooltip" data-original-title="Edit" title="Edit"><i class="glyphicon glyphicon-pencil"></i></button></a>										
								
								<a onclick="myFunctiondel('<?php echo site_url()."index.php/hotel/delete_seasons/".$seasons_list[$a]->seasons_details_id; ?>')"><button type="button" class="btn btn-red tooltip-primary btn-sm" data-placement="top" data-toggle="tooltip" data-original-title="Delete" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></button></a> 

										
							</td>
							
						</tr>
					<?php }} ?>												
					</tbody>
				</table>
				</table>
				</form>
			</div>
			<div class="col-sm-4">
								
									</div>
	
		</div>

		<!-- PANEL BODY END -->
	</div>
	<!-- PANEL END -->
</div>
<script type="text/javascript">
		jQuery(document).ready(function($)
		{
			var table = $("#seasons_list").dataTable({
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
<script type="text/javascript">
	function myFunctiondel(argument) {
		//alert(argument);
	    if (confirm("Do You want to delete ?")) {
	        location.href = argument;
	    }
	}
</script>