<?php
   $tab1 = 'active';
   $get_data = $this->input->get();
   // debug($get_data);exit;
 	if(isset($get_data) && valid_array($get_data)){
 		$action = base_url().'index.php/cms/add_investment_chart?&origin='.$get_data['origin'];
 	}
 	else{
 		$action = base_url().'index.php/cms/add_investment_chart';
 	}
 	// echo $action;exit;
?>
<!-- HTML BEGIN -->
<div class="bodyContent">
<div class="panel panel-default"><!-- PANEL WRAP START -->
<div class="panel-heading"><!-- PANEL HEAD START -->
<div class="panel-title">
<ul class="nav nav-tabs" role="tablist" id="myTab">
	<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->
	<li role="presentation" class="<?=$tab1?>">
		<a id="fromListHead" href="#fromList" aria-controls="home" role="tab"	data-toggle="tab">Manage Package</a>
	</li>
	<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE END -->
</ul>
</div>
</div>
<!-- PANEL HEAD START -->
<div class="panel-body"><!-- PANEL BODY START -->
<span class="error_msg"><?php $msg = $this->uri->segment(3); if(isset($msg)){ echo urldecode($msg); }?></span>
<div class="tab-content">
<div role="tabpanel" class="clearfix tab-pane <?=$tab1?>" id="fromList">
<div class="panel-body">


<div class="tab-content">
   <div id="fromList" class="clearfix tab-pane  active " role="tabpanel">
      <div class="panel-body">
         <form class="form-horizontal" role="form" enctype="multipart/form-data" method="POST" action="<?= $action; ?>" autocomplete="off" name="home_page_heading">
            <fieldset form="promo_codes_form_edit">
               <legend class="form_legend">Add Package</legend>
               <div class="form-group">
                  <label form="promo_codes_form_edit" for="header_title" class="col-sm-3 control-label">PACKAGE TITLE</label>
                     <div class="col-sm-6">
                        <select class="form-control pak_title" name="package_title">
                           <?php if(@$package_title==''){ ?>
                        <option value="">Please Select</option>
                           <?php }else{ ?>
                        <option value="<?php echo @$package_title?>"><?php echo @$package_title?></option>
                           <?php } ?>
                         <option value="silver_package">Silver Package</option>
                         <option value="gold_package">Gold Package</option>
                         <option value="platinum_package">Platinum Package</option>
                        </select>
                     </div>
               </div>
               <div class="form-group">
                  <label form="promo_codes_form_edit" for="header_title" class="col-sm-3 control-label">INVEST</label>
                  	<div class="col-sm-6">
                  		<input type="text"  id="header_title" class="form-control" placeholder="INVEST" name="invest" value="<?php echo @$invest?>" required maxlength="145">
                  	</div>
               </div>
               <div class="form-group">
                  <label form="promo_codes_form_edit" for="header_title" class="col-sm-3 control-label">MONTHLY INCOME</label>
                     <div class="col-sm-6">
                        <input type="text"  id="header_title" class="form-control" placeholder="MONTHLY INCOME" name="monthly_income" value="<?php echo @$monthly_income?>" required maxlength="145">
                     </div>
               </div>
               <div class="form-group">
                  <label form="promo_codes_form_edit" for="header_title" class="col-sm-3 control-label">YEARLY PROFITS</label>
                     <div class="col-sm-6">
                        <input type="text"  id="header_title" class="form-control" placeholder="YEARLY PROFITS" name="yearly_profits" value="<?php echo @$yearly_profits?>" required maxlength="145">
                     </div>
               </div>
               <div class="form-group">
                  <label form="promo_codes_form_edit" for="header_title" class="col-sm-3 control-label">DEPOSIT RETURNS</label>
                     <div class="col-sm-6">
                        <input type="text"  id="header_title" class="form-control" placeholder="DEPOSIT RETURNS" name="deposit_returns" value="<?php echo @$deposit_returns?>" required maxlength="145">
                     </div>
               </div>
              	<div class="form-group">
                  <label form="promo_codes_form_edit" for="header_icon" class="col-sm-3 control-label">6 MONTHS PROFIT</label>
                  	<div class="col-sm-6">
                  		<input type="text"  id="header_icon" class="form-control" placeholder="6 MONTHS PROFIT" name="months_profit" value="<?php echo @$months_profit?>" required maxlength="145">
                  	</div>
               </div>
              </fieldset>
            <div class="form-group">
            <?php if(isset($title)){
                  $button = 'Update';
               }else{
                  $button = 'Save';
               }
               ?>
               <div class="col-sm-8 col-sm-offset-4"> <button class="btn btn-success" id="submit" type="submit"><?php echo $button; ?></button> <button class=" btn btn-warning " id="promo_codes_form_edit_reset" type="reset">Reset</button></div>
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

<script type="text/javascript">
   $('#submit').on('click',function(){
      var a = $('.pak_title').val();
      if(a==''){
         alert('Please Select Package Title');return false;
      }
   });
</script>