<?php 
$tab1 = " active ";
$page_data = $data_list[0];
$primary_id = $page_data['id'];
$title =  $page_data['adv_text'];
$module =  $page_data['module'];
$image =  $page_data['image'];
?>

<!-- HTML BEGIN -->
<div class="bodyContent">
<div class="panel panel-default"><!-- PANEL WRAP START -->
<div class="panel-heading"><!-- PANEL HEAD START -->
<div class="panel-title">
<ul class="nav nav-tabs" role="tablist" id="myTab">
	<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->
	<li role="presentation" class="<?=$tab1?>">
		<a id="fromListHead" href="#fromList" aria-controls="home" role="tab"	data-toggle="tab">Manage Ad Banners</a>
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
         <form class="form-horizontal" role="form" id="promo_codes_form_edit" enctype="multipart/form-data" method="POST" action="<?=base_url().'cms/update_ad_banner_action?bid='.$primary_id?>" autocomplete="off" name="promo_codes_form_edit">
            <input type="hidden" value="<?=$primary_id?>" name="BID">
            <fieldset form="promo_codes_form_edit">
               <legend class="form_legend">Update Ad Banners</legend>
               <div class="form-group">
                  <label form="user" for="title" class="col-sm-3 control-label"><span class="">Advertisement Text Banner </span></label>
                  <div class="col-sm-5">
                  <input type="text" autocomplete="off" name="textbanner" class="normalinput auto-focus valid_class  form-control b-r-0" id="to" placeholder="Text" value="<?php echo $title ?>" required />

                     <!-- <select name="to_airport" class="form-control" required="">
                        <option value="INVALIDIP">Please Select</option>
                        <?=generate_options($flight_list)?>
                     </select> -->
                  </div>
               </div>
               <div class="form-group">
                  <label form="user" for="title" class="col-sm-3 control-label"><span class=""> Module </span></label>
                  <div class="col-sm-5">
                  <!-- <input type="text" autocomplete="off" name="module" class="normalinput auto-focus valid_class  form-control b-r-0" id="to" placeholder="Flight, " value="<?php echo $module ?>" required /> -->
                  <select name="module" class="normalinput auto-focus valid_class  form-control b-r-0" required />
                     <option value="">Please Select</option>
                     <option value="flights" <?php if($module == "flights"){ ?> selected <?php }else{ } ?> >Flight</option>
                     <option value="hotels" <?php if($module == "hotels"){ ?> selected <?php }else{ } ?>>Hotel</option>
                     <option value="transfers" <?php if($module == "transfers"){ ?> selected <?php }else{ } ?>>Transfer</option>
                     <option value="car" <?php if($module == "car"){ ?> selected <?php }else{ } ?>>Car</option>
                     <option value="activities" <?php if($module == "activities"){ ?> selected <?php }else{ } ?>>Activities</option>
                     <option value="holidays" <?php if($module == "holidays"){ ?> selected <?php }else{ } ?>>Holiday</option>
                  </select>   

                     <!-- <select name="to_airport" class="form-control" required="">
                        <option value="INVALIDIP">Please Select</option>
                        <?=generate_options($flight_list)?>
                     </select> -->
                  </div>
               </div>
               <!-- <div class="form-group">

                 
                  <label form="promo_codes_form_edit" for="banner_title" class="col-sm-3 control-label">Module</label>
                     <div class="col-sm-6">
                  <select class="col-sm-6 name="cars" name="banner_module" id="cars">
                  <option value="flights">flights</option>
                  <option value="hotels">hotels</option>
                  <option value="transfers">transfers</option>
                  <option value="car">car</option>
                  <option value="activities">activities</option>
                  <option value="holidays">holidays</option>
            </select>
                       <input type="text"  id="banner_title" class="form-control" placeholder="FLight , hotel  transfers, activities,car " name="banner_module" value="" maxlength="90">
                     </div>
               </div> -->
              <!--  <div class="form-group">
                  <label form="promo_codes_form_edit" for="description" class="col-sm-3 control-label">Description</label>
                  <div class="col-sm-6">
                  	<textarea class=" description form-control" rows="3" id="banner_description" name="banner_description" dt=""  data-original-title="" title=""><?=$description?></textarea>
                  </div>
               </div> -->
              <div class="form-group">
                  <label form="user" for="title" class="col-sm-3 control-label"> Advertisement Image<span class="text-danger">*</span></label>
                  <div class="col-sm-6">
                     <img src="<?php echo $GLOBALS ['CI']->template->domain_images ($image) ?>" height="100px" width="100px" class="img-thumbnail">
                     <input type="file" class="" accept="image/*" name="top_destination">
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

