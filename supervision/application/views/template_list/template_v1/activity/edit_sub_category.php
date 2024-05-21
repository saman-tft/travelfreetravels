<?php error_reporting(0); //
// debug($sub_category_details);exit; //exit; ?>
<div id="Package" class="bodyContent col-md-12">
    <div class="panel panel-default">
        <!-- PANEL WRAP START -->
        <div class="panel-heading">
            <!-- PANEL HEAD START -->
            <div class="panel-title">
                <ul class="nav nav-tabs nav-justified" role="tablist" id="myTab">
                    <!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->
                    <li role="presentation" class="active" id="add_package_li"><a
                            href="#add_package" aria-controls="home" role="tab"
                            data-toggle="tab">Edit Sub Excursion Type</a></li>          
                </ul>
            </div>
        </div>
        <!-- PANEL HEAD START -->
        <div class="panel-body">
            <!-- PANEL BODY START -->
            <form
                action="<?php echo base_url(); ?>index.php/activity/edit_sub_category_save"
                method="post" enctype="multipart/form-data" id="form form-horizontal validate-form"
                class='form form-horizontal validate-form'>
                <div class="tab-content">
                    <!-- Add Package Starts -->
                    <div role="tabpanel" class="tab-pane active" id="add_package">
                        <div class="col-md-12">
                                                  
                            <div class='form-group'>
                                <label class='control-label col-sm-3' for='validation_current'>Excursion Type
                                </label>
                                <div class='col-sm-4 controls'>
                                    <input type="text" name="activity_type" id="activity_type"
                                           placeholder="" data-rule-required='true'
                                           class='form-control add_pckg_elements' required value="<?=$pack_data[0]->activity_types_name;?>" readonly>
                                </div>
                            </div>
                            <div class='form-group'>
                                <label class='control-label col-sm-3' for='validation_current'>Edit Sub Excursion Type
                                </label>
                                <div class='col-sm-4 controls'>
                                    <input type="text" name="add_subcategory" id="add_subcategory"
                                           placeholder="" data-rule-required='true'
                                           class='form-control add_pckg_elements' value="<?=$sub_category_details[0]['activity_sub_category']?>" required>
                                </div>
                            </div>
                            
                            <div class='' style='margin-bottom: 0'>
                                <div class='row'>
                                    <div class='col-sm-9 col-sm-offset-3'>  
                                        <input type="hidden" name="id" value="<?= $id?>">
                                        <input type="hidden" name="activity_id" value="<?= $activity_id?>">                       
                                        <button class='btn btn-primary' type='submit'>Update</button>
                                        <a href="<?php echo base_url(); ?>index.php/activity/view_packages_types" class='btn btn-primary' style="color:white;">Back</a>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </form>
        </div>
        <!-- PANEL BODY END -->
    </div>
    <!-- PANEL WRAP END -->
</div>

<?php
       $HTTP_HOST = '192.168.0.63';
       if(($_SERVER['HTTP_HOST']==$HTTP_HOST) || ($_SERVER['HTTP_HOST']=='localhost'))
	   {
				$airliners_weburl = '/airliners/';	 
	   }
	   else
	   {
				$airliners_weburl = '/~development/airliners_v1/';
       } 
       /*<?=$airliners_weburl?>*/          
       ?> 
<script type="text/javascript" src="<?=$airliners_weburl?>extras/system/template_list/template_v1/javascript/js/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="<?=$airliners_weburl?>extras/system/template_list/template_v1/javascript/js/tiny_mce/tiny_mce_call.js"></script> 
<!--
<script type="text/javascript" src="/chariot/extras/system/template_list/template_v1/javascript/js/nicEdit-latest.js"></script> 
<script type="text/javascript">
bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });
</script>-->
