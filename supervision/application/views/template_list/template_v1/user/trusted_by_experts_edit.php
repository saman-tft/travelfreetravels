<?php 
$tab1 = " active ";
$page_data = $data_list[0];
$primary_id = $page_data['origin'];
$title =  $page_data['title'];
$description =  $page_data['subtitle'];
$image =  $page_data['image'];
$order =  $page_data['banner_order'];
$status =  $page_data['status'];
?>

<!-- HTML BEGIN -->
<div class="bodyContent">
<div class="panel panel-default"><!-- PANEL WRAP START -->
<div class="panel-heading"><!-- PANEL HEAD START -->
<div class="panel-title">
<ul class="nav nav-tabs" role="tablist" id="myTab">
	<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->
	<li role="presentation" class="<?=$tab1?>">
		<a id="fromListHead" href="#fromList" aria-controls="home" role="tab"	data-toggle="tab">Manage Trusted By Experts</a>
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
         <form class="form-horizontal" role="form" id="promo_codes_form_edit" enctype="multipart/form-data" method="POST" action="<?=base_url().'user/update_trusted_by_experts_action?bid='.$primary_id?>" autocomplete="off" name="promo_codes_form_edit">
            <input type="hidden" value="<?=$primary_id?>" name="BID">
            <fieldset form="promo_codes_form_edit">
               <legend class="form_legend">Update Trusted By Experts</legend>
               <!-- <div class="form-group">
                  <label form="promo_codes_form_edit" for="banner_title" class="col-sm-3 control-label">Title</label>
                  	<div class="col-sm-6">
                  		<input type="text"  id="banner_title" class="form-control" placeholder="Title" name="banner_title" value="<?=$title?>" maxlength="90">
                  	</div>
               </div> -->
              <!--  <div class="form-group">
                  <label form="promo_codes_form_edit" for="description" class="col-sm-3 control-label">Description</label>
                  <div class="col-sm-6">
                  	<textarea class=" description form-control" rows="3" id="banner_description" name="banner_description" dt=""  data-original-title="" title=""><?=$description?></textarea>
                  </div>
               </div> -->
               <div class="form-group">
                  <label form="promo_codes_form_edit" for="module" class="col-sm-3 control-label">Current Image</label>
                  <div class="col-sm-6">
                    <img src="<?php echo $GLOBALS ['CI']->template->domain_ban_images ($image) ?>" height="100px" width="100px" class="img-thumbnail">
                    <input type="file" name="banner_image">
                  </div>
               </div>
               
               <div class="radio">
               	<label form="promo_codes_form_edit" for="status" class="col-sm-3 control-label">Status<span class="text-danger">*</span></label>
               		<label for="promo_codes_form_editstatus0" class="radio-inline">  
               			<input type="radio" value="0" id="promo_codes_form_editstatus0" <?=($status==0) ? 'checked="checked"':'';?> name="status" class=" status radioIp" dt="" required="">Inactive
               		</label>
               		<label for="promo_codes_form_editstatus1" class="radio-inline"> 
               		 <input type="radio" value="1" id="promo_codes_form_editstatus1"<?=($status==1) ? 'checked="checked"' : '';?> name="status" class=" status radioIp"  required="">Active
               		 </label>
               </div>
               <div class="form-group" style="margin-top:5px;">
                  <label form="promo_codes_form_edit" for="banner_order" class="col-sm-3 control-label">Order</label>
                  	<div class="col-sm-6">
                  		<input type="number" id="banner_order" class="form-control" placeholder="Order" name="banner_order" value="<?=$order?>">
                  	</div>
               </div>
            </fieldset>
            <div class="form-group">
               <div class="col-sm-8 col-sm-offset-4"> <button class=" btn btn-success " id="promo_codes_form_edit_submit" type="submit">Update</button> <button class=" btn btn-warning " id="promo_codes_form_edit_reset" type="reset">Reset</button></div>
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

