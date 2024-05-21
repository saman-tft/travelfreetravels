<style>
    .radio
    {
        z-index: -1;
    }
</style>
<div id="general_account" class="bodyContent col-md-12">
	<div class="panel panel-default clearfix"><!-- PANEL WRAP START -->
		<div class="panel-heading"><!-- PANEL HEAD START -->
			<div class="panel-title">
				<ul class="nav nav-tabs" role="tablist" id="myTab">
					<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->
					<li role="presentation" class="active"><a id="tableListHead" href="#tableList" aria-controls="profile" role="tab" data-toggle="tab"><?php echo get_app_message('AL0016');?> <span class="glyphicon glyphicon-book"></span></a></li>
					<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE END -->
				</ul>
			</div>
		</div><!-- PANEL HEAD START -->
		<div class="panel-body"><!-- PANEL BODY START -->
			<div class="tab-content">
			<?php if ((check_default_edit_privilege($form_data['user_id']) || super_privilege())) { ?>
				  <div role="tabpanel" class="tab-pane clearfix" id="fromList">
						<div class="col-md-12">
							<div class="panel <?=PANEL_WRAPPER?> clearfix">
								<div class="panel-heading"><?php echo get_utility_message('UL008')?>
								</div>
								<div class="panel-body">
								<?php 
								/************************ GENERATE USER UPDATE PAGE FORM ************************/
								/*$form_data['title']=(array)$form_data['title'];
								$form_data['user_type']=(array)$form_data['user_type'];
								$form_data['country_code']=(array)$form_data['country_code'];*/

								$form_data['title']=$form_data['title'];
								$form_data['user_type']=$form_data['user_type'];
								$form_data['country_code']=$form_data['country_code'];


								// debug($form_data);						 
								// exit();
								echo $this->user_page->generate_form('user_edit',$form_data);
								/************************ GENERATE USER UPDATE PAGE FORM ************************/
							?>
							</div>
							</div>
						</div>
					</div>
				<?php } ?>
					<div role="tabpanel" class="tab-pane active clearfix" id="tableList">
						<div class="col-md-12 table-responsive">
							<div class="panel <?=PANEL_WRAPPER?> clearfix">
								<div class="panel-heading"><?php echo get_utility_message('UL007')?>
								</div>
								<div class="panel-body">
								<?php
								/************************ GENERATE CURRENT PAGE DETAILS ************************/
								echo get_details_summary($form_data);
								/************************ GENERATE CURRENT PAGE DETAILS ************************/
								?>
								</div>
							</div>
						</div>
					</div>
			</div>
		</div><!-- PANEL BODY END -->
	</div><!-- PANEL WRAP END -->
	<script>
	$(document).ready(function() {
		<?php
		/**
		 * tab set up
		 */
		if (valid_array($_POST) == true) {
		?>
			$('#fromListHead').trigger('click');
		<?php
		}
		?>
		$('#fromListHead').on('click', function() {
			$('#tableListHead').parent().removeClass('active');
		});
	});
	</script>
</div>

<!-- CKEditor Plugin -->
<script src="<?php echo RESOURCE_FOLDER; ?>ckeditor/ckeditor.js"></script>


<?php 
function get_details_summary($default_data='')
{
	if ((check_default_edit_privilege($default_data['user_id']) || super_privilege())) {
		$edit_button = '<div class="col-md-12"><a href="#fromList" id="fromListHead" aria-controls="home" role="tab" data-toggle="tab" class="btn btn-primary pull-right"><span class="glyphicon glyphicon-pencil"></span> &nbsp;'.get_app_message('AL0017').'</a></div>';
	} else {
		$edit_button = '';
	}
	return $summary = '<div class="col-md-12">
		<div class="col-md-3"><img src="'.$GLOBALS['CI']->template->domain_images(get_profile_image($default_data['image'])).'" alt="..." class="img-rounded col-md-12"></div>
		<div class="col-md-9">
			<div class="tr_titl">
				<h3 class="col-md-12 text-info">'.get_enum_list('title', $default_data['title']).' '.$default_data['first_name'].' '.$default_data['last_name'].' - '.$default_data['user_profile_name'].'</h3>
  <div class="col-md-12"><div class="col-md-3"><span class="glyphicon glyphicon-qrcode"></span> UID</div><div class="col-md-2">:</div><div class="col-md-7">'.$default_data['uuid'].'</div></div>
  <div class="col-md-12"><div class="col-md-3"><span class="glyphicon glyphicon-calendar"></span> '.get_label(15).'</div><div class="col-md-2">:</div><div class="col-md-7">'.$default_data['date_of_birth'].'</div></div>
  <div class="col-md-12"><div class="col-md-3"><span class="glyphicon glyphicon-envelope"></span> '.get_app_message('AL008').'</div><div class="col-md-2">:</div><div class="col-md-7">'.$default_data['email'].'&nbsp; / &nbsp;'.$default_data['country_code_value'].'&nbsp;&nbsp;&nbsp;'.$default_data['phone'].'</div></div>
  <div class="col-md-12"><div class="col-md-3"><span class="glyphicon glyphicon-home"></span> '.get_app_message('AL0010').'</div><div class="col-md-2">:</div><div class="col-md-7">'.$default_data['address'].'</div></div>
  <div class="col-md-12"><div class="col-md-3"><span class="glyphicon glyphicon-off"></span> '.get_label(21).'</div><div class="col-md-2">:</div><div class="col-md-7">'.get_enum_list('status', $default_data['status']).'</div></div>
  <div class="col-md-12"><div class="col-md-3"><span class="glyphicon glyphicon-list-alt"></span> '.get_label(33).'</div><div class="col-md-2">:</div><div class="col-md-7">'.get_enum_list('language_preference', $default_data['language_preference']).'</div></div>
  '.$edit_button.'
</div></div>
	</div>';
}

 
								// debug($form_data);exit();
								// debug($form_data['pan_no']);
								// debug($form_data['gst_no']);
								// debug($form_data['pin_code']);
								// debug($form_data['comp_website_link']);
								// debug($form_data['pin_code']);
								// exit();
?>

<script>
$(document).ready(function()
{
	var company_reg_id="<?= $form_data['pan_no'];?>";
	var travel_licence_no="<?= $form_data['gst_no'];?>";
	var postal_code="<?= $form_data['pin_code'];?>";
	var comp_website_link="<?= $form_data['comp_website_link'];?>"; 
	var comp_email="<?= $form_data['comp_email'];?>"; 
	$('[name="postal_code"]').on('click keypress',function(){
		var val=this.value;
		if(val.length>6){
			$(this).val(val.substring(0,5));

		}
	})
	$('#phone').attr('minlength',7).trigger('change');	
	
	$("#company_reg_id").val(""+company_reg_id);
	$("#travel_licence_no").val(""+travel_licence_no);
	$("#postal_code").val(""+postal_code);
	$("#comp_website_link").val(""+comp_website_link);
	$("#comp_email").val(""+comp_email);
});

</script>