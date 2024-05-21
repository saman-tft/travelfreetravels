<style>
	.addnwhotl {margin-right: 5px;margin-top: 5px;}
</style>
<!-- HTML BEGIN -->
<? //debug(json_decode(base64_decode($hotel_id)));exit; ?>
<div class="bodyContent">
	<div class="panel panel-default clearfix">
		<div class="col-md-12">
			<?=$GLOBALS['CI']->template->isolated_view('hotel/hotel_widgets',['hotel_id'=>json_decode(base64_decode($hotel_id)),'hotel_name'=>$hotel_name, 'active'=>$wizard_status, 'current'=>'step3'])?>

	        
	      </div>

	       <div class="clearfix"></div>
		<!-- PANEL WRAP START -->
		<div class="panel-heading" style="position: relative;">
			<!-- PANEL HEAD START -->
			<div class="panel-title">
				<i class="fa fa-credit-card"></i> Room Management
				<?php if(isset($hotel_id)) { ?>
				<a href="<?php echo site_url()."/hotels/add_room_type/".$hotel_id; ?>" class="btn btn-primary addnwhotl pull-right" style=" position: absolute; top: 0px; right: 0px;">Add Room</a>
				<?php } else { ?>
				 <a href="<?php echo site_url()."/hotels/add_room_type"; ?>" class="btn btn-primary addnwhotl pull-right" style=" position: absolute; top:0px; right: 0px;">Add Room</a>
				<?php } ?>
			</div>
		</div>
		
		<!-- PANEL HEAD START -->
		<div class="panel-body pull-left" style="width: 100%;">
			<!-- PANEL BODY START -->
			<div class="table-responsive">
				<form action="" method="POST" autocomplete="off">
					<table class="table table-striped">
						<tr>
							<th>Sl No</th>
							<th>Hotel Name</th>
							<th>Room Type Name</th>
							
							<!-- <th>Room Type Description</th> -->
							<th>Max Adult</th>
							<th>Max Child</th>
							<th>No of person</th>
							<th>Extra Bed</th>
							<th>Status</th>
							<th>Actions</th>							
						</tr>
				<tbody>
					<?php if($room_types_list!=''){ for($a=0;$a<count($room_types_list);$a++){ ?>
						<tr>
							<td><?php echo ($a+1); ?></td>
							<td><?php echo $room_types_list[$a]->hotel_name; ?></td>
							<td><?php echo $room_types_list[$a]->room_type_name; ?></td>
							

							<!-- <td><?php echo substr($room_types_list[$a]->room_description, 0, 100).'...'; ?></td> -->
							<td><?php echo $room_types_list[$a]->adult; ?></td>
							<td><?php echo $room_types_list[$a]->child; ?></td>
							<td><?php echo $room_types_list[$a]->max_pax; ?></td>
							<td>
								<?php if($room_types_list[$a]->extra_bed == "Available"){ ?>  
									<button type="button" class="btn btn-green btn-icon icon-left">Available<i class="entypo-check"></i></button>
								<?php }else{ ?>
										<button type="button" class="btn btn-red	 btn-icon icon-left">Not Available<i class="entypo-cancel"></i></button>
								<?php } ?>
							</td>
							<td>
								<?php if($room_types_list[$a]->status == "ACTIVE"){ ?>
									<button type="button"  class="btn btn-green btn-icon icon-left my-actve">Active<i class="entypo-check"></i></button>
								<?php }else{ ?>
										<button type="button" style="background-color: #b74f4f;" class="btn btn-orange btn-icon icon-left">InActive<i class="entypo-cancel"></i></button>
								<?php } ?>
							</td>
							<td class="center">
							   <?php if(isset($hotel_id)) { ?>
							   	<?php if($room_types_list[$a]->status == "ACTIVE"){ ?>
									
									<a href="<?php echo site_url()."/hotels/inactive_room_type/".base64_encode(json_encode($room_types_list[$a]->hotel_room_type_id))."/".$hotel_id; ?>"><button type="button"  class="btn btn-orange tooltip-primary btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="InActive" title="Inactive"><i class="glyphicon glyphicon-eye-open"></i></button></a>
								<?php }else{ ?>
									
									<a href="<?php echo site_url()."/hotels/active_room_type/".base64_encode(json_encode($room_types_list[$a]->hotel_room_type_id))."/".$hotel_id; ?>"> <button type="button" class="btn btn-success tooltip-primary btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="Active" title="Active"><i class="glyphicon glyphicon-ok"></i></button></a>
								<?php } ?>	
								<a href="<?php echo site_url()."/hotels/edit_room_type/".base64_encode(json_encode($room_types_list[$a]->hotel_room_type_id))."/".$hotel_id; ?>" ><button type="button" class="btn btn-blue tooltip-primary btn-sm" data-placement="top" data-toggle="tooltip" data-original-title="Edit" title="Edit"><i class="glyphicon glyphicon-pencil"></i></button></a>														
								
							   <?php } else { ?>
								<?php if($room_types_list[$a]->status == "ACTIVE"){ ?>
									
									<a href="<?php echo site_url()."/hotels/inactive_room_type/".base64_encode(json_encode($room_types_list[$a]->hotel_room_type_id)); ?>"><button type="button" class="btn btn-orange tooltip-primary btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="InActive"><i class="glyphicon glyphicon-eye-open" title="Inactive"></i></button></a>
								<?php }else{ ?>
									
									<a href="<?php echo site_url()."/hotels/active_room_type/".base64_encode(json_encode($room_types_list[$a]->hotel_room_type_id)); ?>"> <button type="button" class="btn btn-success tooltip-primary btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="Active" title="Active"><i class="glyphicon glyphicon-ok"></i></button></a>
								<?php } ?>	
								<a href="<?php echo site_url()."/hotels/edit_room_type/".base64_encode(json_encode($room_types_list[$a]->hotel_room_type_id)); ?>" ><button type="button" class="btn btn-blue tooltip-primary btn-sm" data-placement="top" data-toggle="tooltip" data-original-title="Edit" title="Edit"><i class="glyphicon glyphicon-pencil"></i></button></a>														
								
							   <?php } ?>	
							</td>
							
						</tr>
					<?php }} ?>												
					</tbody>
				</table>
				</form>
			</div>
			
				<div class="col-sm-4">
									<a href="<?php echo site_url()?>/hotels/hotel_crs_list/" class="btn btn-primary addnwhotl pull-right">Back to Hotels</a>
									</div>
			<? if($room_types_list!=''){ ?>
			<div class="next_continue">
				<button onclick="location.href = '<?=base_url()?>index.php/seasons/seasons_list/<?=$hotel_id?>';" id="myButton" class="float-left submit-button btn btn-primary addnwhotl pull-right" >Next</button>
			</div>
		<? }?>
		</div>
		<!-- PANEL BODY END -->
	</div>
	<!-- PANEL END -->
</div>

<script type="text/javascript">
		jQuery(document).ready(function($)
		{
			var table = $("#room_types_list").dataTable({
				"sPaginationType": "bootstrap",
				"sDom": "<'row'<'col-xs-3 col-left'l><'col-xs-9 col-right'<'export-data'T>f>r>t<'row'<'col-xs-6 col-left'i><'col-xs-6 col-right'p>>",
				"oTableTools": {
				},
			});
			table.columnFilter({
				"sPlaceHolder" : "head:after"
			});
		});
		$('#extra_bed1').change(function(){
				var current_status = $('#extra_bed').val();
				if(current_status == "Available"){
					$('#status').val('NotAvailable');
					('#extra_bed_count').$("input").prop('disabled', true);
				}else{
					$('#status').val('Available');
					$('#extra_bed_count').prop('disabled', false);
				}
			});		
	</script>

<!-- Page Ends Here -->
