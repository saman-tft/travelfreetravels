<?php 
$tab1 = " active ";
$page_data = $data_list[0];
$primary_id = $page_data['id'];
$title =  $page_data['text'];
//$description =  $page_data['subtitle'];
$image =  $page_data['image'];
$module =  $page_data['module'];
$about_order =$page_data['about_order'];
//$status =  $page_data['status'];

// debug($tab1);
// debug($page_data);
// debug($primary_id);
// debug($title);
// debug($image);
// debug($module);
//exit;
?>

<!-- HTML BEGIN -->
<div class="bodyContent">
<div class="panel panel-default"><!-- PANEL WRAP START -->
<div class="panel-heading"><!-- PANEL HEAD START -->
<div class="panel-title">
<ul class="nav nav-tabs" role="tablist" id="myTab">
	<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->
	<li role="presentation" class="<?=$tab1?>">
		<a id="fromListHead" href="#fromList" aria-controls="home" role="tab"	data-toggle="tab">Manage About Us</a>
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
         <form class="form-horizontal" role="form" id="promo_codes_form_edit" enctype="multipart/form-data" method="POST" action="<?=base_url().'cms/update_about_us?bid='.$primary_id?>" autocomplete="off" name="promo_codes_form_edit">
            <input type="hidden" value="<?=$primary_id?>" name="BID">
            <fieldset form="promo_codes_form_edit">
               <legend class="form_legend">Update About Us</legend>
               <div class="form-group">
                  <label form="user" for="title" class="col-sm-3 control-label"><span class="">  Text  </span></label>
                  <div class="col-sm-5">
                  <!-- <input type="text" autocomplete="off" name="flight_text" class="normalinput auto-focus valid_class  form-control b-r-0" id="to" placeholder="Flight Text" value="" required /> -->

                  <textarea id="w3review" autocomplete="off" name="flight_text" class="normalinput auto-focus valid_class  form-control b-r-0" rows="4" cols="50"><?php echo $title ?>

                  </textarea>

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
                     <option value="Flight" <?php if($module == "Flight"){ ?> selected <?php }else{ } ?> >Flight</option>
                     <option value="Hotel" <?php if($module == "Hotel"){ ?> selected <?php }else{ } ?>>Hotel</option>
                     <option value="Transfer" <?php if($module == "Transfer"){ ?> selected <?php }else{ } ?>>Transfer</option>
                     <option value="Car" <?php if($module == "Car"){ ?> selected <?php }else{ } ?>>Car</option>
                     <option value="Activities" <?php if($module == "Activities"){ ?> selected <?php }else{ } ?>>Activities</option>
                     <option value="Holiday" <?php if($module == "Holiday"){ ?> selected <?php }else{ } ?>>Holiday</option>
                     
                     <option value="Cruise" <?php if($module == "Cruise"){ ?> selected <?php }else{ } ?>>Cruise</option>
                     <option value="Private Jet" <?php if($module == "Private Jet"){ ?> selected <?php }else{ } ?>>Private Jet</option>
                     <option value="Villas & Apts" <?php if($module == 'Villas & Apts'){ ?> selected <?php }else{ } ?>>Villas & Apts</option>
                     <option value="Private Car" <?php if($module == "Private Car"){ ?> selected <?php }else{ } ?>>Private Car</option>
                     <option value="Private transfer" <?php if($module == "Private transfer"){ ?> selected <?php }else{ } ?>>Private transfer</option>
                     <option value="Investor" <?php if($module == "Investor"){ ?> selected <?php }else{ } ?>>Investor</option>
                  </select>   

                     <!-- <select name="to_airport" class="form-control" required="">
                        <option value="INVALIDIP">Please Select</option>
                        <?=generate_options($flight_list)?>
                     </select> -->
                  </div>
               </div>
               <div class="form-group">
					<label form="about_order" for="about_order" class="col-sm-3 control-label"> Order<span class="text-danger">*</span></label>
					<div class="col-sm-6">
						<input type="number" class="form-control" required="required" value="<?=$about_order?>" id="about_order" placeholder="Order" name="about_order">
					</div>
				</div>
               <!-- <div class="form-group">
                  <label form="user" for="title" class="col-sm-3 control-label"> Image<span class="text-danger">*</span></label>
                  <div class="col-sm-6">
                     <img src="<?php echo $GLOBALS ['CI']->template->domain_images ($image) ?>" height="100px" width="100px" class="img-thumbnail">
                     <input type="file" class="" accept="image/*" required="" name="top_destination">
                  </div>
               </div> -->
                <div class="form-group">
                  <label form="promo_codes_form_edit" for="module" class="col-sm-3 control-label">Image</label>
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

