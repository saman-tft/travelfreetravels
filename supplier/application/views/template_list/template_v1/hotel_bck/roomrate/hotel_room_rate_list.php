<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">	
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="description" content="Provab Admin Panel" />
	<meta name="author" content="" />	
	<title><?php echo PAGE_TITLE; ?> | Room Rate</title>	
	<!-- Load Default CSS and JS Scripts -->
	<?php $this->load->view('general/load_css');	?>	
</head>
<body id="top" oncontextmenu="return false"  class="page-body <?php if(isset($transition)){ echo $transition; } ?>" data-url="<?php echo PROVAB_URL; ?>">
	<div class="page-container <?php if(isset($header) && $header == 'header_top'){ echo "horizontal-menu"; } ?> <?php if(isset($header) && $header == 'header_right'){ echo "right-sidebar"; } ?>  <?php if(isset($sidebar)){ echo $sidebar; } ?>">
		<?php if(isset($header) && $header == 'header_top'){ $this->load->view('general/header_top'); }else{ $this->load->view('general/left_menu'); }	?>
		<div class="main-content">
			<?php if(!isset($header) || $header != 'header_top'){ $this->load->view('general/header_left'); } ?>
			<?php $this->load->view('general/top_menu');	?>
			<hr />
			<ol class="breadcrumb bc-3">	
									
					<?php 
					$room='';
					if($supplier_rights == 1){
					 $url = site_url()."/supplier_dashboard";
					 } else {
					  $url = site_url()."/dashboard";
					 } 
				  	  $new_roomrate_link = site_url()."/roomrate/hotel_add_room_rate";
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
				 				
				<li><a href="<?php echo $url; ?>"><i class="entypo-home"></i>Home</a></li>
				<li><a href="<?php echo site_url()."/roomrate/list_room_rate".$room; ?>">Room Rate</a></li>
				<li class="active"><strong>Room Rate List</strong></li>
				<li class="active" style="float:right;"><a href="<?=$new_roomrate_link ?>">Add New Room Rate</a></li>
			</ol>
			<div class="row" style="overflow-x: scroll;">
				<table class="table table-bordered datatable" id="room_rate_list">
					<thead>
						<tr>
							<th>Sl No</th>
							<th>Hotel Name</th>
							<th>Room Name</th>
							<th>Status</th>
							<th>Actions</th>
							
							<th>From Date</th>
							<th>To Date</th>							
							<th>Infant Price</th>
							<th>Child Price</th>
							<!-- <th>Child Group C</th>
							<th>Child Group D</th>
							<th>Child Group E</th> -->
							<th>Single Room</th>
							<th>Double Room</th>
							<th>Triple Room</th>
							<th>Quad Room</th>
							<th>Hex Room</th>
							<th>Extrabed Adult Price</th>
							<th>Extrabed Child Price</th>
							
						</tr>
						<tr class="replace-inputs">
							<th>Sl No</th>
							<th>Hotel Name</th>
							<th>Room Name</th>
							<th>Status</th>
							<th>Actions</th>
							
							<th>From Date</th>
							<th>To Date</th>							
							<th>Infant Price</th>
							<th>Child Price</th>
							<!-- <th>Child Group C</th>
							<th>Child Group D</th>
							<th>Child Group E</th> -->
							<th>Single Room</th>
							<th>Double Room</th>
							<th>Triple Room</th>
							<th>Quad Room</th>
							<th>Hex Room</th>
							<th>Extrabed Adult Price</th>
							<th>Extrabed Child Price</th>							
							
						</tr>
					</thead>
					<tbody>
					<?php if($room_rate_list!=''){ for($a=0;$a<count($room_rate_list);$a++){ ?>
						<tr>
							<td><?php echo ($a+1); ?></td>
							<td><?php echo $room_rate_list[$a]->hotel_name; ?></td>
							<td><?php echo $room_rate_list[$a]->room_type_name; ?></td>
							<td>
								<?php if($room_rate_list[$a]->roomrate_status == "ACTIVE"){ ?>
									<button type="button" class="btn btn-green btn-icon icon-left">Active<i class="entypo-check"></i></button>
								<?php }else{ ?>
										<button type="button" class="btn btn-orange btn-icon icon-left">InActive<i class="entypo-cancel"></i></button>
								<?php } ?>
							</td>
							<td class="center">								
								<?php if($room_rate_list[$a]->roomrate_status == "ACTIVE"){ 	
											if(isset($GET) && $GET!='') { ?>		
												<a href="<?php echo site_url()."//roomrate/inactive_hotel_roomrate/".base64_encode(json_encode($room_rate_list[$a]->hotel_room_rate_info_id))."/".$GET; ?>"><button type="button" class="btn btn-orange tooltip-primary btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="InActive"><i class="glyphicon glyphicon-eye-open"></i></button></a>					
												<?php } else { ?>
												<a href="<?php echo site_url()."/roomrate/inactive_hotel_roomrate/".base64_encode(json_encode($room_rate_list[$a]->hotel_room_rate_info_id)); ?>"><button type="button" class="btn btn-orange tooltip-primary btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="InActive"><i class="glyphicon glyphicon-eye-open"></i></button></a>
											<?php } 
										}
									else{ 
											if(isset($GET) && $GET!='') { ?>	
												<a href="<?php echo site_url()."/roomrate/active_hotel_roomrate/".base64_encode(json_encode($room_rate_list[$a]->hotel_room_rate_info_id))."/".$GET; ?>"> <button type="button" class="btn btn-success tooltip-primary btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="Active"><i class="glyphicon glyphicon-ok"></i></button></a>									
											<?php } else { ?> 
												<a href="<?php echo site_url()."/roomrate/active_hotel_roomrate/".base64_encode(json_encode($room_rate_list[$a]->hotel_room_rate_info_id)); ?>"> <button type="button" class="btn btn-success tooltip-primary btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="Active"><i class="glyphicon glyphicon-ok"></i></button></a>
											<?php } 
									} ?>								
								<a href="<?php echo site_url()."/roomrate/edit_hotel_roomrate/".base64_encode(json_encode(array($current_page_type,$room_rate_list[$a]->hotel_room_rate_info_id))); ?>" ><button type="button" class="btn btn-blue tooltip-primary btn-sm" data-placement="top" data-toggle="tooltip" data-original-title="Edit"><i class="glyphicon glyphicon-pencil"></i></button></a>																		
								<a href="<?php echo site_url()."/roomrate/delete_hotel_roomrate/".base64_encode(json_encode($room_rate_list[$a]->hotel_room_rate_info_id)); ?>"><button type="button" class="btn btn-red tooltip-primary btn-sm" data-placement="top" data-toggle="tooltip" data-original-title="Delete"><i class="glyphicon glyphicon-remove"></i></button></a>				
							</td>						

						
							<td><?php echo $room_rate_list[$a]->from_date; ?></td>
							<td><?php echo $room_rate_list[$a]->to_date; ?></td>							
							<td><?php echo $room_rate_list[$a]->room_child_price_a; ?></td>
							<td><?php echo $room_rate_list[$a]->room_child_price_b; ?></td>
							<!-- <td><?php echo $room_rate_list[$a]->room_child_price_c; ?></td>
							<td><?php echo $room_rate_list[$a]->room_child_price_d; ?></td>
							<td><?php echo $room_rate_list[$a]->room_child_price_e; ?></td> -->
							<td><?php echo $room_rate_list[$a]->single_room_price; ?></td>
							<td><?php echo $room_rate_list[$a]->double_room_price; ?></td>
							<td><?php echo $room_rate_list[$a]->triple_room_price; ?></td>
							<td><?php echo $room_rate_list[$a]->quad_room_price; ?></td>
							<td><?php echo $room_rate_list[$a]->hex_room_price; ?></td>							
							<td><?php echo $room_rate_list[$a]->adult_extra_bed_price; ?></td>
							<td><?php echo $room_rate_list[$a]->child_extra_bed_price; ?></td>
							
							
						</tr>
					<?php }} ?>												
					</tbody>
				</table>
			</div>
			<!-- Footer -->
			<?php $this->load->view('general/footer');	?>				
		</div>				
		
	</div>
	<!-- Bottom Scripts -->
	<?php $this->load->view('general/load_js');	?>
	<script src="<?php echo base_url(); ?>assets/js/bootstrap-switch.min.js"></script>	
	<script src="<?php echo base_url(); ?>assets/js/jquery.dataTables.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/datatables/TableTools.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/dataTables.bootstrap.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/datatables/jquery.dataTables.columnFilter.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/datatables/lodash.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/datatables/responsive/js/datatables.responsive.js"></script>
	<script type="text/javascript">
		jQuery(document).ready(function($)
		{
			var table = $("#room_rate_list").dataTable({
				"sPaginationType": "bootstrap",
				"sDom": "<'row'<'col-xs-3 col-left'l><'col-xs-9 col-right'<'export-data'T>f>r>t<'row'<'col-xs-6 col-left'i><'col-xs-6 col-right'p>>",
				"oTableTools": {
				},
			});
			table.columnFilter({
				"sPlaceHolder" : "head:after"
			});
		});		
	</script>
</body>
</html>
