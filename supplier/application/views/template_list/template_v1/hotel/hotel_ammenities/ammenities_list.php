<!-- HTML BEGIN -->
<?php
//echo "test";die;
//debug($ammenities_list);die;
?><style type="text/css">
	.panel-default > a {
    background-color: #f58830;
    border-color: #f58830;
    border-radius: 3px;
    color: #fff;
    font-size: 14px;
    height: 34px;
    margin: 10px 5px 0 0;
    padding: 6px 12px;
    float: right;
    vertical-align: middle;
}
</style>
<?php //print_r($this->session->flashdata('success_message'));exit('sdfs');?>
<?php if ($this->session->flashdata('error_message') != '') { ?>
<div class="alert alert-danger alert-dismissible">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>Error!</strong> <?=$this->session->flashdata('error_message')?>
</div>
<?php } ?>
<?php if ($this->session->flashdata('success_message') != '') { ?>
<div class="alert alert-success alert-dismissible">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>Success!</strong> <?=$this->session->flashdata('success_message')?>
</div>
<?php } ?>
<div class="bodyContent">
	<div class="panel panel-primary clearfix">
		<!-- PANEL WRAP START -->
		<div class="panel-heading">
			<!-- PANEL HEAD START -->
			<div class="panel-title">
				<i class="fa fa-credit-card"></i> Amenities List
			</div>
		</div>
		<a style="margin-top: 10px;" href="<?php echo base_url()?>index.php/hotel/add_hotel_ammenties" class="btn btn-primary addnwhotl pull-right">Add Hotel Amenity</a>
		<div class="clearfix"></div>
		<!-- PANEL HEAD START -->
		<div class="panel-body">
			<!-- PANEL BODY START -->
			<div class="table-responsive">
				<form action="" method="POST" autocomplete="off">
					<table class="table table-striped">
						<tr>
							<th>Sl No</th>
							<th>Hotel Amenity Name</th>
							<th>Status</th>
							<th>Actions</th>
						</tr>
				<tbody>

				<?php if(!empty($ammenities_list))
							{ 
								foreach($ammenities_list as $a => $list)
								{ 
					?>
						<tr>
							<td><?php echo ($a+1); ?></td>
							<td><?php echo $list->name; ?></td>
							<td>
							<?php if($list->status == "ACTIVE")
							{ 
								?>
								<button type="button" class="btn btn-green btn-icon icon-left my-actve">Active<i class="entypo-check"></i></button>
							<?php 
							}
							else
							{ 
								?>
									<button type="button" class="btn btn-orange btn-icon icon-left my-inactve">InActive<i class="entypo-cancel"></i></button>
							<?php 
							} 
							?>
							</td>
							<td class="center">
								<?php 
								if($list->status == "ACTIVE")
									{ 
								?>
									<a href="<?php echo base_url()."index.php/hotel/inactive_hotel_ammenity/".base64_encode(json_encode($list->id)); ?>" class="btn btn-orange btn-sm btn-icon icon-left my-inactve"><i class="entypo-eye"></i>InActive</a>
								<?php 
								}
								else
								{ ?>
									<a href="<?php echo base_url()."index.php/hotel/active_hotel_ammenity/".base64_encode(json_encode($list->id)); ?>" class="btn btn-green btn-sm btn-icon icon-left my-actve"><i class="entypo-check"></i>Active</a>
								<?php 
								} 
								?>
								<a href="<?php echo base_url()."index.php/hotel/edit_hotel_ammenity/".base64_encode(json_encode($list->id)); ?>" class="btn btn-default btn-sm btn-icon icon-left"><i class="entypo-pencil"></i>Edit</a>	
									<!-- <a href="#" onclick="myFunctiondel('<?php echo base_url()."/hotels/delete_hotel_ammenity/".base64_encode(json_encode($list->id)); ?>')" class="btn btn-danger btn-sm btn-icon icon-left"><i class="entypo-cancel"></i>Delete</a> -->
							</td>
						</tr>
					<?php 
					}
					} 
					?>												
					</tbody>
				</table>
				</form>
			</div>
		</div>
		<!-- PANEL BODY END -->
	</div>
	<!-- PANEL END -->
</div>

<!-- Page Ends Here -->
<script type="text/javascript">
	function myFunctiondel(argument) {
		//alert(argument);
	    if (confirm("Do You want to delete ?")) {
	        location.href = argument;
	    }
	}
</script>