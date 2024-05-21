<?php

$country_array=explode(',', $pack_data[0]->country);
foreach($country_array as $country_val)
{
	foreach($tours_continent_sel as $county_list)
	{
	
		if($county_list['id']==$country_val)
		{
			$sel_county_list[]=$county_list['id'];
		}
	}
}
//debug($sel_county_list);exit;
//exit
?>
<div id="package_types" class="bodyContent col-md-12">
	<div class="panel panel-default">
		<!-- PANEL WRAP START -->
		<div class="panel-heading">
			<!-- PANEL HEAD START -->
			<div class="panel-title">
				<ul class="nav nav-tabs" role="tablist" id="myTab">
					<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->
					<li role="presentation" class="active"><a href="#fromList"
						aria-controls="home" role="tab" data-toggle="tab"><h1>Add / Edit Nationality Group</h1></a></li>
					<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE END -->
				</ul>
			</div>
		</div>
		<!-- PANEL HEAD START -->
		<div class="panel-body">
			<!-- PANEL BODY START -->
			<div class="tab-content">
				<div role="tabpanel" class="tab-pane active" id="fromList">
					<div class="col-md-12">
					<div class='row'>
					<div class='col-sm-12'>
						<div class=''>
							<div class='box-header '>
								<div class='title' id="tab1">  <?php if(isset($status)){echo $status;}?></div>
								<div class='actions'></div>
							</div>
							<div class=''>
								<form class='form form-horizontal validate-form'
									style='margin-bottom: 0;'
									action="<?php echo base_url(); ?>index.php/activity/save_price_category"
									method="post" name="frm1" enctype="multipart/form-data">
									<div class='form-group'>
										<label class='control-label col-sm-2' for='validation_name'>Nationality Group</label>
										<div class='col-sm-3 controls'>

										<?php 
											$value="";
											if($id)
											{
												$value =$pack_data[0]->price_category_name;
											}
 
										?>


										<input type="hidden" id="price_id" name="activity_types_id" value="<?=$id;?>">
											<input class='form-control' data-rule-minlength='2'
												data-rule-required='true' id='pname' name='name' value="<?=$value;?>"
												placeholder='Nationality Group Name' type='text' required> <span id="pacname"
												style="color: #F00; display: none;">Please enter Nationality Group</span>
										</div>
									</div>

									<div class='form-group'>
								<label class='control-label col-sm-2' for='validation_current'>Choose Region <span style = "color:red">*</span>
								</label>
								<div class='col-sm-6 col-md-6 controls'>
								<select class='select2 form-control' data-rule-required='true' name='tours_continent' id="tours_continent" data-rule-required='true' required>
                               <option value="NA">Select Region </option>
                                <?php
                                foreach($tours_continent as $tours_continent_key => $tours_continent_value)
                                {
                                	if($tours_continent_value['id']==$pack_data[0]->contient)
                                	{
                                		echo '<option value="'.$tours_continent_value['id'].'" selected>'.$tours_continent_value['name'].' </option>';
                                	}
                                	else
                                	{
                                		echo '<option value="'.$tours_continent_value['id'].'" >'.$tours_continent_value['name'].' </option>';
                                	}

                                	

                                }
                                ?>
								</select>				
								</div>
							</div>
								<div class='form-group'>
								<label class='control-label col-sm-2' for='validation_current'>Choose Country <span style = "color:red">*</span>
								</label>
								<div class='col-sm-6 col-md-6 controls bxpd'>
								<select class='select2 form-control' data-rule-required='true' name='tours_country[]' id="tours_country" multiple data-rule-required='true' required>
                              
                                 <?php
                                /*theme = $tour_data['theme'];*/
                               /* $country = json_decode($theme,1); 
	                        	$tour_data['theme'] = explode(',', $tour_data['theme']);*/
	                            $theme = $country_array;

                                foreach($tours_continent_sel as $k => $v)
                                {
                                	if(in_array($v['id'],$sel_county_list)){
                                		$selected='selected';}
                                	else{$selected='';
                                	debug(no);
                                     }
                                	echo '<option value="'.$v['id'].'" '.$selected.'>'.$v['name'].' </option>';
                                	//echo '<option value="'.$v['id'].'" '.$selected.'>1 </option>';
                                }
                                ?>
 
								</select>				
								</div>
							</div>

									<div class='form-actions' style='margin-bottom: 0'>
										<div class='row'>
											<div class='col-sm-9 col-sm-offset-4'>
												
												<button class='btn btn-primary' type='submit'>
													<i class='icon-save'></i> Submit
												</button>	
												&nbsp;
												<a
													href="<?php echo base_url(); ?>index.php/activity/view_price_category">
												<button class='btn btn-primary' type='button'>
														<i class='icon-reply'></i> Back
													</button></a>
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
					</div>
				</div>
			</div>
		</div>
		<!-- PANEL BODY END -->
	</div>
	<!-- PANEL WRAP END -->
</div>
<script type="text/javascript">
$(document).ready(function()
     {
     /*	var cont_val=$('#tours_continent').val();
     	var price_id=$('#price_id').val();
     	if(price_id!='0'&& cont_val!='0')
     	{
     		$(function(){
			$("#tours_continent").val(cont_val).change();
			
			setTimeout(function(){ 
				$('#tours_continent').val(cont_val).trigger('click');
			}, 200);
			
		});
     	}*/
     	 
	$('#tours_continent').on('click', function() { 
            
        tours_continent = $('#tours_continent').val();

        if(tours_continent!='NA'){
        	$.post('<?=base_url();?>activity/ajax_tours_continent',{'tours_continent':tours_continent},function(data){
          	  //alert(data);
              $('#tours_country').html(data);
              tours_country.bootstrapDualListbox('refresh', true);

         	});
        }else{
        	$('#tours_country').html('');
            $('#tours_city').html('');
        }
        });  


     	var tours_country = $('select[name="tours_country[]"]').bootstrapDualListbox({
					 // nonSelectedListLabel: 'Non-selected',
					 // selectedListLabel: 'Selected',
					 // preserveSelectionOnMove: false,
					 // moveOnSelect: false
					});
        });  

</script>
<link href="<?=RESOURCE_DIR?>/system/template_list/template_v1/css/bootstrap-duallistbox-master/src/bootstrap-duallistbox.css" rel="stylesheet">

<script src="<?=RESOURCE_DIR?>/system/template_list/template_v1/javascript/bootstrap-duallistbox-master/src/jquery.bootstrap-duallistbox.js"></script>
<script src="<?=RESOURCE_DIR?>/system/library/chosen/chosen.jquery.min.js"
 type="text/javascript"></script>
 <link href="<?=RESOURCE_DIR?>/system/library/chosen/chosen.min.css"
	rel="stylesheet">