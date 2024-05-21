<script src="<?php echo SYSTEM_RESOURCE_LIBRARY?>/ckeditor/ckeditor.js"></script>
<?php
//debug($blog_image);exit;
//get sub admin table
$table = '<div class="table-responsive">
				<table class="table table-bordered blg-tbl table-condensed">
				<tr>
					<th>Title</th>
					
					<th> Description</th>
					<th> Image</th>
					
					<th>Status</th>
					<th>Action</th>
				</tr>';
	if (valid_array($sub_admin) == true) {
		foreach ($sub_admin as $key => $value) {
			$action = '<a role="button" href="'.base_url().'index.php/utilities/manage_blog/'.$value['id'].'"><button class="btn btn-sm">Edit</button></a>';
			if (intval($value['page_status']) == 1) {
				$status_label = '<span class="label label-success">Active</span>';
				$status_button = '<a role="button" href="'.base_url().'index.php/utilities/blog_status/'.$value['id'].'/D"><button class="label label-danger">Deactivate</button></a>'; 
			} else {
				$status_label = '<span class="label label-danger">Inactive</span>';
				$status_button = '<a role="button" href="'.base_url().'index.php/utilities/blog_status/'.$value['id'].'/A"><button class="label label-success">Activate</button></a>';
			} 
			$table .= '<tr>
				<td>'.$value['blog_title'].'</td>
				
				<td>'.$value['blog_description'].'</td>
				

				<td><img src="'.$GLOBALS ['CI']->template->domain_blog_images ($value['blog_image']) .'" height="100px" width="100px" class="img-thumbnail" alt=""></td>

				<td>'.$status_label.'</td>
				<td>'.$action.' '.$status_button.'</td>
			</tr>';
		}
	} else {
		$table .= '<tr><td colspan="8">No Cms Page Found In The System</td></tr>';
	}
$table .= '</table></div>';
//end of table section
?>
<div class="bodyContent">
	<div class="panel <?=PANEL_WRAPPER?>"><!-- PANEL WRAP START -->
		<div class="panel-heading"><!-- PANEL HEAD START -->
			<div class="panel-title">
				<i class="fa fa-edit"></i>BLOG
			</div>
		</div><!-- PANEL HEAD START -->
		<div class="panel-body"><!-- PANEL BODY START -->

<form method="post" autocomplete="off" action="<?php echo base_url();?>index.php/utilities/manage_blog/<?php echo $ID;?>" enctype="multipart/form-data" id="profile_form">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-condensed">
 <tr>
    <td>Blog Title<span class="text-danger">*</span></td>
    <td><input type="text" name="blog_title" value="<?php echo isset($blog_title) ? $blog_title : '';?>">
    <font color="red" ><?=@form_error('blog_title')?> </font>
    </td>
  </tr>
  <tr>
  	<td>Blog Description<span class="text-danger">*</span></td>
    <td><textarea class="ckeditor" id="editor" name="blog_description" rows="10" cols="80"><?php echo isset($blog_description) ? $blog_description : '';?></textarea>
     <font color="red" ><?=@form_error('blog_description')?> </font>
    </td>
  </tr>
  <?php if(isset($blog_image)){?>
               <tr><td> Current Image
                  </td>
                        <td>
                    <img src="<?php echo $GLOBALS ['CI']->template->domain_blog_images ($blog_image) ?>" height="100px" width="100px" class="img-thumbnail">
              </td></tr>
                             <?php } ?>
<tr>

    	<td>Blog Image<span class="text-danger">*</span></td>
                  
                  		<td><input type="file"  id="blog_image" class="form-control" name="blog_image" value="<?php echo @$blog_image?>" <?php if(!isset($blog_image)){?>required<?php } ?>
                  	</td>
              
</tr>
  <tr>
    <td colspan="3" align="center"><input type="submit" class="btn btn-sm btn-success" value="Submit"/></td>
  
  </tr>
</table>
</form>
</div>
</div>
</div>
<div class="bodyContent">
	<div class="panel <?=PANEL_WRAPPER?>"><!-- PANEL WRAP START -->
		<div class="panel-heading"><!-- PANEL HEAD START -->
			<div class="panel-title">
				<i class="fa fa-edit"></i> CMS Page List
			</div>
		</div><!-- PANEL HEAD START -->
		<div class="panel-body"><!-- PANEL BODY START -->
<div class="panel-body">
<?php 
echo $table;
?>
</div>
</div></div></div>