<!-- HTML BEGIN -->
<div class="bodyContent">
	<div class="row">
		<div class="pull-left" style="margin:5px 0">
			<a href="<?=base_url().'index.php/user/add_affiliate_partners'?>">
				<button  class="btn btn-primary btn-sm pull-right amarg">Contact_Us</button>
			</a>
		</div>
	</div>
	<div class="panel <?=PANEL_WRAPPER?>"><!-- PANEL WRAP START -->
		<div class="panel-heading"><!-- PANEL HEAD START -->
			<div class="panel-title">
				<i class="fa fa-edit"></i> Contact_Us
			</div>
		</div><!-- PANEL HEAD START -->
		
		<div class="panel-body">
			<table class="table table-condensed">
				<tr>
					<th>Sl no</th>
					<th>Customer Name</th> 
					<th>Email</th>
					<th>Phone</th>
					<th>Message</th>
					<th>Action</th>
				</tr>
				<?php
				// debug($data_list);exit;
				if (valid_array($data_list) == true) {
					foreach ($data_list as $k => $v) :
						$action = '<form method="post" action="'.base_url().'index.php/cms/delete_contact_us">
	     <input type="submit" class="btn btn-default btn-sm btn-primary" value="Delete" />
	     <input type="hidden" value="'.$v['id'].'"  name="id" />
		 </form><!--<a role="button" href="'.base_url().'index.php/cms/contact_us/'.$v['origin'].'"><button class="btn btn-sm">Edit</button></a>-->'; 
				?>
					<tr>
						<td><?=($k+1)?></td>
						<td><?=$v['custname']?></td> 
						<td><?=$v['email']?></td>
						<td><?=$v['phone']?></td>
						<td><?=$v['message']?></td>
						<td><?=$action;?></td>
					</tr>
				<?php
					endforeach;
				} else {
					echo '<tr><td>No Data Found</td></tr>';
				}
				?>
			</table>
		</div>
	</div><!-- PANEL WRAP END -->
</div>
<?php 
function get_status_label($status)
{
	if (intval($status) == ACTIVE) {
		return '<span class="label label-success"><i class="fa fa-circle-o"></i> '.get_enum_list('status', ACTIVE).'</span>
	<a role="button" href="" class="hide">'.get_app_message('AL0021').'</a>';
	} else {
		return '';
	}
}

function get_status_toggle_button($status, $origin)
{
	if (intval($status) == ACTIVE) {
		return '<a role="button" href="'.base_url().'index.php/cms/deactivate_flight_top_destination/'.$origin.'" class="text-danger">Deactivate</a>';
	} else {
		return '';		
	}
}

?>
