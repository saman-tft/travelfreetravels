 
<div id="package_types" class="bodyContent col-md-12">
	<div class="panel panel-default">
		<!-- PANEL WRAP START -->
		<div class="panel-heading">
			<!-- PANEL HEAD START -->
			<div class="panel-title">
				<ul class="nav nav-tabs" role="tablist" id="myTab">
					<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->
					<li role="presentation" class="active"><a href="#fromList"
						aria-controls="home" role="tab" data-toggle="tab"><h1>Add / Edit Nationality Country</h1></a></li>
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
									action="<?php echo base_url(); ?>index.php/transfers/save_nationalityCountries"
									method="post" name="frm1" enctype="multipart/form-data">
									<div class='form-group'>
										<label class='control-label col-sm-2' for='validation_name'>Nationality type</label>
										<div class='col-sm-3 controls'>

										<?php 
											$value="";
											if($id)
											{
												$value =$pack_data[0]->price_category_name;
											}

 
										?> 
										<input type="hidden" name="pack_id" value="<?=$id ?>">
											<input class='form-control' data-rule-minlength='2'
												data-rule-required='true' id='pname' name='name' value="<?=$edit_notionality_country[0]['name'];?>"
												placeholder='Nationality Type' type='text' required> <span id="pacname"
												style="color: #F00; display: none;">Please enter Price
												Type</span>
										</div>
									</div>

									<div class='form-group'>
								<label class='control-label col-sm-2' for='validation_current'>Choose Region <span style = "color:red">*</span>
								</label>
								<div class='col-sm-6 col-md-6 controls'>
								<select class='select2 form-control' data-rule-required='true' name='tours_continent' id="tours_continent" data-rule-required='true' required>
                               <option value="NA">Select Region </option>
                                <?php
                                foreach($nationality_regions as $tours_continent_key => $tours_continent_value)
                                {
                                	if($tours_continent_value['id']==$edit_notionality_country[0]['continent'])
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
 
 <!-- 
							<div class='form-group'>
								<label class='control-label col-sm-2' for='validation_current'>Choose Country <span style = "color:red">*</span>
								</label>
								<div class='col-sm-6 col-md-6 controls'>
								<select class='select2 form-control' data-rule-required='true' name='tours_country[]' id="tours_country" multiple data-rule-required='true' required>
                              
                                 <?php
                                /*theme = $tour_data['theme'];*/
                               /* $country = json_decode($theme,1); 
	                        	$tour_data['theme'] = explode(',', $tour_data['theme']);*/
	                            $theme = $country_array;

	                        	$include_countryIds = explode(',', $edit_notionality_country[0]['include_countryIds']);
	                        		

                                foreach($country_list as $k => $v)
                                {
                                	if(in_array($v['origin'],$include_countryIds)){
                                		$selected='selected';}
                                	else{$selected='';
                             
                                     }
                                	echo '<option value="'.$v['origin'].'" '.$selected.'>'.$v['country_name'].' </option>';
                                	//echo '<option value="'.$v['id'].'" '.$selected.'>1 </option>';
                                }
                                ?>
 
								</select>				
								</div>
							</div> -->

							<div class='form-group'>
								<label class='control-label col-sm-2' for='validation_current'>Choose Country <span style = "color:red">*</span>
								</label>
								<div class='col-sm-6 col-md-6 controls'>

								<div class="org_row orr">
								<div class='col-sm-6'>
								<?php 
								if(empty($edit_notionality_country)){
									$Except_country_count=count($country_list);
									$Include_country_count=0;
								}
								else
								{
									$Except_country_count=0;
									$Include_country_count=0;
								     $include_countryIds = explode(',', $edit_notionality_country[0]['include_countryIds']);  
	                                foreach($country_list as $k => $v)
	                                {
	                                	if(in_array($v['origin'],$include_countryIds))
	                                	{
	                                		$Include_country_count++;
	                                	}
	                                	else{ 
	                             			$Except_country_count++;
	                                     }	                                	 
	                                }

								}
									
									  
								?>
									<label for="bootstrap-duallistbox-nonselected-list_tours_country[]">Except Countries
									(<strong id="Except_country_count"><?=$Except_country_count; ?></strong>)</label>
								<select class='select2 form-control bx' data-rule-required='true'  id="tours_city" multiple data-rule-required='true' style="height: 75%;">
                                        
                                 <?php
                                /*theme = $tour_data['theme'];*/
                               /* $country = json_decode($theme,1); 
	                        	$tour_data['theme'] = explode(',', $tour_data['theme']);*/
	                            $theme = $country_array;

	                        	$include_countryIds = explode(',', $edit_notionality_country[0]['include_countryIds']);
	                        		

                                foreach($country_list as $k => $v)
                                {
                                	if(in_array($v['origin'],$include_countryIds)){
                                		$selected='selected';}
                                	else{$selected='';
                                			echo '<option value="'.$v['origin'].'" '.$selected.'>'.$v['country_name'].' </option>';
                             
                                     }
                                	//echo '<option value="'.$v['id'].'" '.$selected.'>1 </option>';
                                }
                                ?>

								</select>
								</div>
								
								<div class='col-sm-6'>
									<label for="bootstrap-duallistbox-nonselected-list_tours_country[]">Included Countries 
										(<strong id="Include_country_count"><?=$Include_country_count; ?></strong>)</label>
								<select id="second" class="form-control bx" name="tours_country[]" multiple style="height:  75%;">
									<?php 
									foreach($country_list as $k => $v)
		                                {
		                                	if(in_array($v['origin'],$include_countryIds)){
		                                		$selected='selected';
		                                		echo '<option value="'.$v['origin'].'" '.$selected.'>'.$v['country_name'].' </option>';
		                                	}
		                                	 
		                                	 
		                                }
									?>
								</select>
								</div>				
								</div>	

								</div>
							</div>

 


									<div class='form-actions' style='margin-bottom: 0'>
										<div class='row'>
											<div class='col-sm-9 col-sm-offset-4'>
												<a
													href="<?php echo base_url(); ?>index.php/transfers/view_notionality_country">
													<button class='btn btn-primary' type='button'>
														<i class='icon-reply'></i> Back
													</button>
												</a>&nbsp;
												<button class='btn btn-primary' type='submit'>
													<i class='icon-save'></i> Submit
												</button>
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
     	  $('#tours_city').multiselect();
     	  
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
     	 
	// $('#tours_continent').on('click', function() { 
            
 //        tours_continent = $('#tours_continent').val();

 //        if(tours_continent!='NA'){
 //        	$.post('<?=base_url();?>activity/ajax_tours_continent',{'tours_continent':tours_continent},function(data){
 //          	  //alert(data);
 //              $('#tours_country').html(data);
 //              tours_country.bootstrapDualListbox('refresh', true);

 //         	});
 //        }else{
 //        	$('#tours_country').html('');
 //            $('#tours_city').html('');
 //        }
 //        });  


     // 	var tours_country = $('select[name="tours_country[]"]').bootstrapDualListbox({
					//  nonSelectedListLabel: 'Except Countries',
					//  selectedListLabel: 'Included Countries',
					//  eventMoveAllOverride:false,
					//  // preserveSelectionOnMove: false,
					//  // moveOnSelect: false
					// });
        });  


     	$('#tours_city').click(function() {
		    var options = $("#tours_city").find(':selected').clone();
		    $('#second').append(options);
		    getSelectMultiple();

		     $("#tours_city").find(':selected').remove();
		    count_countries('Except');
		});

		$('#second').click(function() {
			var options = $("#second").find(':selected').clone();
		    $('#tours_city').append(options);		    

		   $("#second").find(':selected').remove();
		   getSelectMultiple();
		   count_countries('Included');
		   sort_options();
		});

		function getSelectMultiple(){
			$("#second option").prop('selected', true);
		}

		function count_countries(type='')
		{	
			var Except_country_count=$('#tours_city option').length;
			var Include_country_count=$('#second option').length;

			 if(type =='Except')
			 { 				 				     
			    $("#Except_country_count").html(Except_country_count);
			    $("#Include_country_count").html(Include_country_count);
			 }
			 else
			 { 			     
			    $("#Except_country_count").html(Except_country_count);
			    $("#Include_country_count").html(Include_country_count);
			 }
		}


		function sort_options() { 
            var options = $("#tours_city option"); 
            options.detach().sort(function(a, b) { 
                var at = $(a).text(); 
                var bt = $(b).text(); 
                return (at > bt) ? 1 : ((at < bt) ? -1 : 0); 
            }); 
            options.appendTo("#tours_city"); 
        }


</script>
<link href="<?=RESOURCE_DIR?>/system/template_list/template_v1/css/bootstrap-duallistbox-master/src/bootstrap-duallistbox.css" rel="stylesheet">

<script src="<?=RESOURCE_DIR?>/system/template_list/template_v1/javascript/bootstrap-duallistbox-master/src/jquery.bootstrap-duallistbox.js"></script>
<script src="<?=RESOURCE_DIR?>/system/library/chosen/chosen.jquery.min.js"
 type="text/javascript"></script>
 <link href="<?=RESOURCE_DIR?>/system/library/chosen/chosen.min.css"
	rel="stylesheet">