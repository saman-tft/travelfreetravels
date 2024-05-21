<?php
   $tab1 = 'active';
?>
<script src="<?php echo SYSTEM_RESOURCE_LIBRARY?>/ckeditor/ckeditor.js"></script>
<!-- HTML BEGIN -->
<div class="bodyContent">
<div class="panel panel-default"><!-- PANEL WRAP START -->
<div class="panel-heading"><!-- PANEL HEAD START -->
<div class="panel-title">
<ul class="nav nav-tabs" role="tablist" id="myTab">
	<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->
	<li role="presentation" >
		<a  href="<?=base_url().'user/blog_images'?>" ><button class="btn btn-primary btn-sm pull-right amarg">Manage Blog List</button></a>
	</li>
	<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE END -->
</ul>
</div>
</div>
<!-- PANEL HEAD START -->
<div class="panel-body"><!-- PANEL BODY START -->
<div class="tab-content">
<div role="tabpanel" class="clearfix tab-pane <?=$tab1?>" id="fromList">
<div class="panel-body">


<div class="tab-content">
   <div id="fromList" class="clearfix tab-pane  active " role="tabpanel">
      <div class="panel-body">
         <form class="form-horizontal" role="form" id="promo_codes_form_edit" enctype="multipart/form-data" method="POST" action="<?=base_url().'user/add_blog_action'?>" autocomplete="off" name="promo_codes_form_edit">
            <fieldset form="promo_codes_form_edit">
               <legend class="form_legend">Add Blog</legend>
               <div class="form-group">
                  <label form="promo_codes_form_edit" for="banner_title" class="col-sm-3 control-label">Title</label>
                  	<div class="col-sm-6">
                  		<input type="text"  id="banner_title" class="form-control" placeholder="Title" name="banner_title" value="">
                  	</div>
               </div> 
                <div class="form-group">
                  <label form="promo_codes_form_edit" for="description" class="col-sm-3 control-label">Description</label>
                  <div class="col-sm-6">
                  	<textarea class=" description form-control ckeditor" id="editor" rows="10" cols="80" id="description" name="description" dt=""  data-original-title="" title=""><?=$description?></textarea>
                  </div>
               </div>
               <div class="form-group">
                  <label form="promo_codes_form_edit" for="module" class="col-sm-3 control-label">Blog Image</label>
                  <div class="col-sm-6">
                    <input type="file" name="banner_image" required="required">
                  </div>
               </div>
               
               <div class="radio">
               	<label form="promo_codes_form_edit" for="status" class="col-sm-3 control-label">Status<span class="text-danger">*</span></label>
               		<label for="promo_codes_form_editstatus0" class="radio-inline">  
               			<input type="radio" value="0" id="promo_codes_form_editstatus0"  name="status" class=" status radioIp" dt="" required="">Inactive
               		</label>
               		<label for="promo_codes_form_editstatus1" class="radio-inline"> 
               		 <input type="radio" value="1" id="promo_codes_form_editstatus1" name="status" class=" status radioIp" checked="checked"  required="">Active
               		 </label>
               </div>
               <div class="form-group" style="margin-top:5px;">
                  <label form="promo_codes_form_edit" for="banner_order" class="col-sm-3 control-label">Order</label>
                  	<div class="col-sm-6">
                  		<!-- <input type="number" min="1" max="5"  id="banner_order" class="form-control" placeholder="Order" name="banner_order" value="0"> -->
                        <input type="number" min="1" id="banner_order" class="form-control" placeholder="Order" name="banner_order" value="0">
                  	</div>
               </div>
            </fieldset>
            <div class="form-group">
               <div class="col-sm-8 col-sm-offset-4"> <button class=" btn btn-success " id="promo_codes_form_edit_submit" type="submit">Save</button> <button class=" btn btn-warning " id="promo_codes_form_edit_reset" type="reset">Reset</button></div>
            </div>
         </form>
      </div>
   </div>
</div>


</div>
</div>

</div>
</div>
<!-- PANEL BODY END --></div>
<!-- PANEL WRAP END --></div>
<!-- HTML END -->

