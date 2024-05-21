<style>
	.addnwhotl {margin-right: 5px;margin-top: 5px;}
</style>
<!-- HTML BEGIN -->
<?php $redirection_url=($this->uri->segment(3)); //debug($room_rate_list);exit; ?>

<div class="bodyContent">
	<div class="panel panel-default clearfix">
		<div class="col-md-12">
			<?=$GLOBALS['CI']->template->isolated_view('hotel/hotel_widgets',['hotel_id'=>$hotel_id,'hotel_name'=>$hotel_name, 'active'=>$wizard_status, 'current'=>'step5'])?>

	      </div>

	       <div class="clearfix"></div>
		<!-- PANEL WRAP START -->
		<div class="panel-heading" style="position: relative;">
			<!-- PANEL HEAD START -->
			<div class="panel-title">
				<i class="fa fa-credit-card"></i> Room Rate Management (Room Price all are in AED)
			</div>
			<?php 
					$room='';
					/*if($supplier_rights == 1){
					 $url = site_url()."supplier_dashboard";
					 } else {
					  $url = site_url()."dashboard";
					 } */
				  	  $new_roomrate_link = site_url()."/roomrate/add_room_rate";
				  	 // echo $GET;exit;
					   if(isset($GET) && !empty($GET))
					  {
					  	$room='/'.$GET;
					  	$new_roomrate_link .= $room;
					 	$current_page_type = $GET;
					  } else {
					  	$current_page_type = '';
					  }
				 ?>	

		<a href="<?=$new_roomrate_link ?>" class="btn btn-primary addnwhotl pull-right" style=" position: absolute; top:0px; right: 0px;">Add Room Rate</a>
		</div>
		
		<!-- PANEL HEAD START -->
		<div class="panel-body pull-left">
			<!-- PANEL BODY START -->
			<div class="table-responsive">
				<form action="" method="POST" autocomplete="off">
					<table class="table table-striped">
						<tr>
							<th>Sl No</th>
							<th>Default</th>
							<th>Hotel Name</th>
							<th>Room Name</th>
							
							<th>Seasons Name</th>
                             <th>Nationality</th>
							<th>Infant Price</th>
							<th>Child Price</th>
							<!-- <th>Child Group C</th>
							<th>Child Group D</th>
							<th>Child Group E</th> -->
							<th>Single Adult Price</th>
							<th>Double Adult Price</th>
							<th>Triple Adult Price</th>
							<!-- <th>Quad Room</th>
							<th>Hex Room</th> -->

							<th>Status</th>
							<th>Actions</th>
							
						</tr>
				<tbody>
					<?php if($room_rate_list!=''){ for($a=0;$a<count($room_rate_list);$a++){ 
					    if($room_rate_list[$a]->default_nationality)
					    {
					        //$nationality=$room_rate_list[$a]->country_name;
					        	if($room_rate_list[$a]->country_name)
            					{ 
            					    $nationality=$room_rate_list[$a]->country_name.'[Default]';
            					    
            					}
            					
            					else{
            					    $nationality="Default";
            					    
            					}
					    }
					    else{
					        	if($room_rate_list[$a]->country_name)
            					{ 
            					    $nationality=$room_rate_list[$a]->country_name;
            					    
            					}
            					
            					else{
            					    $nationality="Default";
            					    
            					}
					    }
				
					
					?>
						<tr>
							<td><?php echo ($a+1); ?></td>
							<td><?php echo $room_rate_list[$a]->hotel_name; ?></td>
							<td><?php echo $room_rate_list[$a]->room_type_name; ?></td>
										

							<td><?php echo $room_rate_list[$a]->seasons_name; ?></td>
                            <td><?php echo $nationality; ?></td>
							<td><?php echo $room_rate_list[$a]->room_child_price_a; ?></td>
							<td><?php echo $room_rate_list[$a]->room_child_price_b; ?></td>
							<!-- <td><?php echo $room_rate_list[$a]->room_child_price_c; ?></td>
							<td><?php echo $room_rate_list[$a]->room_child_price_d; ?></td>
							<td><?php echo $room_rate_list[$a]->room_child_price_e; ?></td> -->
							<td><?php echo $room_rate_list[$a]->single_room_price; ?></td>
							<td><?php echo $room_rate_list[$a]->double_room_price; ?></td>
							<td><?php echo $room_rate_list[$a]->triple_room_price; ?></td>
							<!-- <td><?php echo $room_rate_list[$a]->quad_room_price; ?></td>
							<td><?php echo $room_rate_list[$a]->hex_room_price; ?></td>	 -->						
<!--
							<td>
								<?php if($room_rate_list[$a]->roomrate_status == "ACTIVE"){ ?>
									<button type="button" style="background-color:green;" class="btn btn-green btn-icon icon-left">Active<i class="entypo-check"></i></button>
								<?php }else{ ?>
										<button type="button" style="background-color: #b74f4f;" class="btn btn-orange btn-icon icon-left">InActive<i class="entypo-cancel"></i></button>
								<?php } ?>
							</td>-->
							
							
							<td>
								<?php if($room_rate_list[$a]->hotel_room_rate_status == "ACTIVE"){ ?>
									<button type="button" style="background-color:green;" class="btn btn-green btn-icon icon-left">Active<i class="entypo-check"></i></button>
								<?php }else{ ?>
										<button type="button" style="background-color: #b74f4f;" class="btn btn-orange btn-icon icon-left">InActive<i class="entypo-cancel"></i></button>
								<?php } ?>
							</td>
							
							
						<!--	<td class="center">								
								<?php if($room_rate_list[$a]->roomrate_status == "ACTIVE"){ 	
											if(isset($GET) && $GET!='') { ?>		
												<a href="<?php echo site_url()."/roomrate/inactive_roomrate/".base64_encode(json_encode($room_rate_list[$a]->hotel_room_rate_info_id))."/".$GET; ?>"><button type="button" class="btn btn-orange tooltip-primary btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="InActive" title="Inactive"><i class="glyphicon glyphicon-eye-open"></i></button></a>					
												<?php } else { ?>
												<a href="<?php echo site_url()."/roomrate/inactive_roomrate/".base64_encode(json_encode($room_rate_list[$a]->hotel_room_rate_info_id)); ?>"><button type="button" class="btn btn-orange tooltip-primary btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="InActive" title="Active"><i class="glyphicon glyphicon-eye-open"></i></button></a>
											<?php } 
										}
									else{ 
											if(isset($GET) && $GET!='') { ?>	
												<a href="<?php echo site_url()."/roomrate/active_roomrate/".base64_encode(json_encode($room_rate_list[$a]->hotel_room_rate_info_id))."/".$GET; ?>"> <button type="button" class="btn btn-success tooltip-primary btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="Active" title="Aactive"><i class="glyphicon glyphicon-ok"></i></button></a>									
											<?php } else { ?> 
												<a href="<?php echo site_url()."/roomrate/active_roomrate/".base64_encode(json_encode($room_rate_list[$a]->hotel_room_rate_info_id)); ?>"> <button type="button" class="btn btn-success tooltip-primary btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="Active" title="Aactive"><i class="glyphicon glyphicon-ok"></i></button></a>
											<?php } 
									} ?>								
								<a href="<?php echo site_url()."/roomrate/edit_roomrate/".base64_encode(json_encode(array($current_page_type,$room_rate_list[$a]->hotel_room_rate_info_id))); ?>" ><button type="button" class="btn btn-blue tooltip-primary btn-sm" data-placement="top" data-toggle="tooltip" data-original-title="Edit" title="Edit"><i class="glyphicon glyphicon-pencil"></i></button></a>																		
								<a href="#" onclick="myFunctiondel('<?php echo site_url()."/roomrate/delete_roomrate/".base64_encode(json_encode($room_rate_list[$a]->hotel_room_rate_info_id)); ?>')"><button type="button" class="btn btn-red tooltip-primary btn-sm" data-placement="top" data-toggle="tooltip" data-original-title="Delete" title="Cancel"><i class="glyphicon glyphicon-remove"></i></button></a>				
							</td>-->
							
								<td class="center">								
								<?php 
						
							if($room_rate_list[$a]->hotel_room_rate_status == "ACTIVE")
								{ 	
											if(isset($GET) && $GET!='') { ?>		
												<a href="<?php echo site_url()."/roomrate/inactive_roomrate/".base64_encode(json_encode($room_rate_list[$a]->hotel_room_rate_info_id))."/".$GET."/". $room_rate_list[$a]->hotel_room_rate_id; ?>"><button type="button" class="btn btn-orange tooltip-primary btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="InActive" title="Inactive"><i class="glyphicon glyphicon-eye-open"></i></button></a>					
												<?php } else { //echo "else"; ?>
												<a href="<?php echo site_url()."/roomrate/inactive_roomrate/".base64_encode(json_encode($room_rate_list[$a]->hotel_room_rate_info_id)); ?>"><button type="button" class="btn btn-orange tooltip-primary btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="InActive" title="Active"><i class="glyphicon glyphicon-eye-open"></i></button></a>
											<?php } 
										}
									else{ 
											if(isset($GET) && $GET!='') { ?>	
												<a href="<?php echo site_url()."/roomrate/active_roomrate/".base64_encode(json_encode($room_rate_list[$a]->hotel_room_rate_info_id))."/".$GET."/". $room_rate_list[$a]->hotel_room_rate_id; ?>"> <button type="button" class="btn btn-success tooltip-primary btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="Active" title="Aactive"><i class="glyphicon glyphicon-ok"></i></button></a>									
											<?php } else { ?> 
												<a href="<?php echo site_url()."/roomrate/active_roomrate/".base64_encode(json_encode($room_rate_list[$a]->hotel_room_rate_info_id)); ?>"> <button type="button" class="btn btn-success tooltip-primary btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="Active" title="Aactive"><i class="glyphicon glyphicon-ok"></i></button></a>
											<?php } 
									} ?>								
							<!--	<a href="<?php echo site_url()."/roomrate/edit_roomrate/".base64_encode(json_encode(array($current_page_type,$room_rate_list[$a]->hotel_room_rate_info_id))); ?>" ><button type="button" class="btn btn-blue tooltip-primary btn-sm" data-placement="top" data-toggle="tooltip" data-original-title="Edit" title="Edit"><i class="glyphicon glyphicon-pencil"></i></button></a>																		
							-->
								<a href="<?php echo site_url()."/roomrate/edit_roomrate/".base64_encode(json_encode(array($current_page_type,$room_rate_list[$a]->hotel_room_rate_info_id)))."/".$room_rate_list[$a]->hotel_room_rate_id; ?>" ><button type="button" class="btn btn-blue tooltip-primary btn-sm" data-placement="top" data-toggle="tooltip" data-original-title="Edit" title="Edit"><i class="glyphicon glyphicon-pencil"></i></button></a>																		
	
							<a href="#" onclick="myFunctiondel('<?php echo site_url()."/roomrate/delete_roomrate/".base64_encode(json_encode($room_rate_list[$a]->hotel_room_rate_info_id))."/".$redirection_url."/".$room_rate_list[$a]->hotel_room_rate_id; ?>')"><button type="button" class="btn btn-red tooltip-primary btn-sm" data-placement="top" data-toggle="tooltip" data-original-title="Delete" title="Cancel"><i class="glyphicon glyphicon-remove"></i></button></a>				
							</td>			
							
							
						</tr>
					<?php }} ?>												
					</tbody>
				</table>
				</form>
			</div>
			<?  if($room_rate_list!=''){ ?>
				<div class="next_continue">
					<button onclick="location.href = '<?=base_url()?>index.php/hotels/hotel_crs_list';" id="myButton" class="btn btn-primary addnwhotl pull-right" >Submit</button>
				</div>
				<div class="next_continue">
					<button onclick="location.href = '<?=base_url()?>index.php/seasons/seasons_list/<?=base64_encode(json_encode($hotel_id))?>';" id="myButton" class="btn btn-primary addnwhotl pull-right" >Back To Season Management</button>
				</div>
			<? }?>
		</div>
		<!-- PANEL BODY END -->
	</div>
	<!-- PANEL END -->
</div>
<script type="text/javascript">
	function myFunctiondel(argument) {
		//alert(argument);
	    if (confirm("Do You want to delete ?")) {
	        location.href = argument;
	    }
	}
</script>

<!-- Page Ends Here -->
