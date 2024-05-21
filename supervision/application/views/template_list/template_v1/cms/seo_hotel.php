<!-- HTML BEGIN -->
<div class="bodyContent">
	<div class="row">
	</div>
	<div class="panel <?=PANEL_WRAPPER?>"><!-- PANEL WRAP START -->
		<div class="panel-heading"><!-- PANEL HEAD START -->
			<div class="panel-title">
				<i class="fa fa-edit"></i> SEO
			</div>
		</div><!-- PANEL HEAD START -->
		
		<div class="panel-body">
			<table class="table table-condensed">
				<tr>
					<th>Sl no</th>
					<th>Title</th>
					<th>Keyword</th>
					<th>Description</th>
					<th>Module</th>
					<th>Action</th>
				</tr>
				<?php
				$data_list = $data_list['data'];
				// debug($data_list);exit;
				if (valid_array($data_list) == true) {
					foreach ($data_list as $k => $v) :
						$action = '<form method="post" action="'.base_url().'index.php/cms/edit_seo_hotel">
	     <input type="submit" class="btn btn-default btn-sm btn-primary" value="'.get_app_message('AL0041').'" />
	     <input type="hidden" value="'.$v['id'].'"  name="id" />
		 <span class="glyphicon glyphicon-pencil"></span></form><!--<a role="button" href="'.base_url().'index.php/cms/edit_seo_hotel/'.$v['id'].'"><button class="btn btn-sm">Edit</button></a>-->';
				?>
					<tr>
						<td><?=($k+1)?></td>
						<td><?=$v['title']?></td>
						<td><?=$v['keyword']?></td>
						<td><?=$v['description']?></td>
						<td><?=$v['module']?></td>
						<td><?=$action?></td>
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