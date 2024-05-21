<?php

   $tab1 = 'active';
   $get_data = $this->input->get();
   // debug($get_data);exit;
 	if(isset($get_data) && valid_array($get_data)){
 		$action = base_url().'index.php/cms/contact_us';
 	}
 	else{
 		$action = base_url().'index.php/cms/contact_us';
 	}
 	// echo $action;exit;
?>
<!-- HTML BEGIN -->
<div class="bodyContent">
<div class="panel panel-default"><!-- PANEL WRAP START -->
<div class="panel-heading"><!-- PANEL HEAD START -->

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
         <form class="form-horizontal" role="form" enctype="multipart/form-data" method="POST" action="<?= $action; ?>" autocomplete="off" name="contact_us">
            <fieldset form="promo_codes_form_edit">
               <legend class="form_legend">Contact Us Details</legend>
               <div class="form-group">
                  <label form="promo_codes_form_edit" for="header_title" class="col-sm-3 control-label">Address</label>
                  	<div class="col-sm-6">
                  		<textarea class="form-control" rows ="6" value="" cols="100" name="address1" required><?php echo @$address1;?></textarea>
                        <input type="hidden" name="id" value="<?php echo @$id?>">
                  	</div>
               </div>
               <!-- <div class="form-group">
                  <label form="promo_codes_form_edit" for="header_title" class="col-sm-3 control-label">Address 2</label>
                    <div class="col-sm-6">
                      <textarea class="form-control" rows ="6" value="" cols="100" name="address2" required><?php echo @$address2;?></textarea>
                    </div>
               </div> -->
              <div class="form-group">
                  <label form="promo_codes_form_edit" for="email" class="col-sm-3 control-label">Email 1</label>
                     <div class="col-sm-6">
                     <input type="text"  id="email" class="form-control" placeholder="Eamil 1" name="email1" value="<?php echo @$email1?>" required >
                    </div>              
              </div>
              <div class="form-group">
                  <label form="promo_codes_form_edit" for="email" class="col-sm-3 control-label">Email 2</label>
                     <div class="col-sm-6">
                     <input type="text"  id="email" class="form-control" placeholder="Eamil 2" name="email2" value="<?php echo @$email2?>" required >
                    </div>              
              </div>
              <div class="form-group">
                  <label form="promo_codes_form_edit" for="email" class="col-sm-3 control-label">Website</label>
                     <div class="col-sm-6">
                     <input type="text"  id="email" class="form-control" placeholder="Eamil 3" name="email3" value="<?php echo @$email3?>" required >
                    </div>              
              </div>
              <div class="form-group">
                  <label form="promo_codes_form_edit" for="phone" class="col-sm-3 control-label">Phone Number 1</label>
                     <div class="col-sm-6">
                      <input type="text"  id="phone" class="form-control" placeholder="Phone Number 1" name="phone1" value="<?php echo @$phone1?>" required >     
                     </div>   
               </div>
               <div class="form-group">
                  <label form="promo_codes_form_edit" for="phone" class="col-sm-3 control-label">Phone Number 2</label>
                     <div class="col-sm-6">
                      <input type="text"  id="phone" class="form-control" placeholder="Phone Number 2" name="phone2" value="<?php echo @$phone2?>" required >     
                     </div>   
               </div>
               <div class="form-group">
                  <label form="promo_codes_form_edit" for="phone" class="col-sm-3 control-label">Phone Number 3</label>
                     <div class="col-sm-6">
                      <input type="text"  id="phone" class="form-control" placeholder="Phone Number 3" name="phone3" value="<?php echo @$phone3?>" required >     
                     </div>   
               </div>
             
              	<style>
                   #branches{
                        max-width: 943px;
                        margin: 25px auto;
                   }
                   #branches textarea{
                        /* min-height: 93px;
                        margin-bottom: 27px; */
                   }
                   #branches input[type="file"]{
                       margin-bottom:5px;
                   }
                   #branches .add_remove{
                      
                   }
                   #branches .branch_list{
                        background-color: #f1f1f1;
                        padding: 14px;
                        border-radius: 10px;
                        margin-bottom:10px;
                   }
                   #branches .file_with_image{
                       display:flex;
                   }
                   #branches .file_with_image input[type="file"]{
                       flex:1;
                   }
                   #branches .file_with_image img{
                        width: 41px;
                        height: 30px;
                   }
               </style>
             <div id="branches">
                 <div class="inner">
                     <?php 
                        $branches_data= json_encode(array(array('branch_name' => "", 'branch_phone' => "", 'branch_order' => "1", 'branch_address' => "")));
                        
                        $branches_data = json_decode($branches_data);
                        if($branches_detail!=""){
                            $branches_data=json_decode($branches_detail);
                            
                            $keys = array_column($branches_data, 'branch_order');
                            array_multisort($keys, SORT_ASC, $branches_data);
                            //var_dump($new);
                        }
                        //debug($branches_data);exit();
                        
                        foreach($branches_data as $key => $value):
                            //if($key+1 == $value->branch_order):
                     ?>
                 <div class="branch_list" id="<?= ($key == 0)? 'list_one' : '';?>">
                     <div class="row">
                         <div class="col-md-6">
                                <div>
                                  <label form="branch_name" class="control-label">Branch Name</label>
                                     <div>
                                      <input type="text" class="form-control" value="<?=$value->branch_name?>" placeholder="Branch Name" name="branch_name[]"  required >     
                                     </div>   
                               </div>
                                <div>
                                  <label form="branch_phone" for="branch_phone" class="control-label">Branch Phone</label>
                                     <div>
                                      <input type="text" class="form-control" value="<?=$value->branch_phone?>" placeholder="Branch Phone" name="branch_phone[]"  required >     
                                     </div>   
                               </div>
                               <div>
                                  <label form="branch_order" for="branch_order" class="control-label">Branch Order</label>
                                     <div>
                                      <input type="text" class="form-control" value="<?=$value->branch_order?>" placeholder="Branch Order" name="branch_order[]"  required >     
                                     </div>   
                               </div>
                                 
                         </div>
                         <div class="col-md-6">
                                <div>
                                  <label form="branch_address" for="branch_address" class="control-label">Branch Address</label>
                                     <div>
                                      <textarea type="text" class="form-control" placeholder="Branch Address" name="branch_address[]"  required ><?=$value->branch_address?></textarea>     
                                     </div>   
                               </div>
                               <div>
                                  <label form="branch_flag" for="branch_flag" class="control-label">Branch Flag</label>
                                     <div class="file_with_image">
                                      <input type="file" class="form-control" name="branch_flag[]"> 
                                      <img src="<?=$GLOBALS['CI']->template->domain_images($value->branch_flag)?>" />
                                      <input type="hidden" name="hide_flag_name[]" value="<?=$value->branch_flag?>" />
                                     </div>   
                               </div>
                               
                                <div class="add_remove">
                                    <div><span onclick="removeBranch(this)" class="remove_branch add_remove_btn btn btn-danger" style="display: block;"> <span class="fa fa-minus-circle"></span>Remove Branch </span></div>
                                </div>
                         </div>
                     </div>
                </div>
                <?php /*endif;*/ endforeach; ?>
                </div>
                        <div class="add_remove">
                            <div ><span onclick="appendBranch()" class="add_branch add_remove_btn btn btn-success"> <span class="fa fa-plus-circle"></span>Add Branch </span></div>
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
               <div class="col-sm-8 col-sm-offset-4"> <button class="btn btn-success" type="submit"><?php echo $button; ?></button> <button class=" btn btn-warning " id="promo_codes_form_edit_reset" type="reset">Reset</button></div>
            </div>
         </form>
      </div>
   </div>
</div>


</div>
</div>

</div>
</div>


<div id="blank_branch" style="display:none;">
     <div class="branch_list">
         
         <div class="row">
             <div class="col-md-6">
                    <div>
                      <label form="branch_name" class="control-label">Branch Name</label>
                         <div>
                          <input type="text" class="form-control" placeholder="Branch Name" name="branch_name[]"  required >     
                         </div>   
                   </div>
                    <div>
                      <label form="branch_phone" for="branch_phone" class="control-label">Branch Phone</label>
                         <div>
                          <input type="text" class="form-control" placeholder="Branch Phone" name="branch_phone[]"  required >     
                         </div>   
                   </div>
                   <div>
                      <label form="branch_order" for="branch_order" class="control-label">Branch Order</label>
                         <div>
                          <input type="text" class="form-control" placeholder="Branch Order" name="branch_order[]"  required >     
                         </div>   
                   </div>
                     
             </div>
             <div class="col-md-6">
                    <div>
                      <label form="branch_address" for="branch_address" class="control-label">Branch Address</label>
                         <div>
                          <textarea type="text" class="form-control" placeholder="Branch Address" name="branch_address[]"  required ></textarea>     
                         </div>   
                   </div>
                   
                   <div>
                      <label form="branch_flag" for="branch_flag" class="control-label">Branch Flag</label>
                         <div>
                          <input type="file" class="form-control" name="branch_flag[]"  required >    
                          <input type="hidden" name="hide_flag_name[]" value="" />
                         </div>   
                   </div>
                   
                    <div class="add_remove">
                        <div><span onclick="removeBranch(this)" class="remove_branch add_remove_btn btn btn-danger" style="display: block;"> <span class="fa fa-minus-circle"></span>Remove Branch </span></div>
                    </div>
             </div>
         </div>
    </div>
 </div>
<!-- PANEL BODY END --></div>
<!-- PANEL WRAP END --></div>
<!-- HTML END -->

<script>
    
    function appendBranch() {
		var newbranch =$('#blank_branch').html();
		$("#branches .inner").append(newbranch);
	}
	function removeBranch(elem){
	    var first_branch=$(elem).closest('.branch_list').attr('id');
	    if(first_branch != "list_one"){
	        $(elem).closest('.branch_list').remove();
	    }
	}
</script>