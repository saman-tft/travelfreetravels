<?php error_reporting(0); //debug($tour_destination_details); //exit; ?>
<div id="Package" class="bodyContent col-md-12 yhgjk">
    <div class="panel panel-default">
        <!-- PANEL WRAP START -->
        <div class="panel-heading">
            <!-- PANEL HEAD START -->
            <div class="panel-title">
                <ul class="nav nav-tabs nav-justified" role="tablist" id="myTab">
                    <!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->
                    <li role="presentation" class="active" id="add_package_li"><a
                            href="#add_package" aria-controls="home" role="tab"
                            data-toggle="tab">Add Sub Theme  </a></li>          
                </ul>
            </div>
        </div>
        <!-- PANEL HEAD START -->
        <div class="panel-body">
            <!-- PANEL BODY START -->
            <form
                action="<?php echo base_url(); ?>index.php/activity/add_subtheme_save"
                method="post" enctype="multipart/form-data" id="form form-horizontal validate-form"
                class='form form-horizontal validate-form'>
                <div class="tab-content">
                    <!-- Add Package Starts -->
                    <div role="tabpanel" class="tab-pane active" id="add_package">
                        <div class="col-md-12">
                                                  
                            <div class='form-group'>
                                <label class='control-label col-sm-3' for='validation_current'>Theme
                                </label>
                                <div class='col-sm-4 controls'>
                                    <input type="text" name="activity_subtheme" id="activity_subtheme"
                                           placeholder="" data-rule-required='true'
                                           class='form-control add_pckg_elements' required value="<?php echo string_replace_encode($activity_theme_details['activity_subtheme']);?>" readonly>
                                </div>
                            </div>
                            <div class='form-group'>
                                <label class='control-label col-sm-3' for='validation_current'>Add Sub Theme
                                </label>
                                <div class='col-sm-4 controls'>
                                    <input type="text" name="add_subtheme" id="add_subtheme"
                                           placeholder="" data-rule-required='true'
                                           class='form-control add_pckg_elements' required>
                                </div>
                            </div>
                            
                            <div class='' style='margin-bottom: 0'>
                                <div class='row'>
                                    <div class='col-sm-9 col-sm-offset-3'>  
                                        <input type="hidden" name="id" value="<?= $activity_theme_details['id'] ?>">                       
                                        <button class='btn btn-primary' type='submit'>Save</button>
                                        <a href="<?php echo base_url(); ?>index.php/activity/activity_subtheme" class='btn btn-primary' style="color:white;">Back</a>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </form>
        </div>
            <div class="table-responsive scroll_main" style="overflow-hidden; overflow-x:scroll;">
            <table class="table table-bordered">
            <thead>
                <tr>
                    <?php $j=1; ?>
                    <th>Sl.No</th>
                    <th><input type='checkbox' name='alll' id='selectall<?=$j?>' onclick='checkall(<?=$j?>);'>&nbsp;&nbsp;&nbsp;<b>Select All </b>
        <div class="dropdown2" role="group" style="float:right">
                                <div class="dropdown slct_tbl pull-left hjkuu"> <i class="fa fa-ellipsis-v"></i>
                                    <ul class="dropdown-menu sidedis" style="display: none;">
                                        <li> <a href="#" class="sideicbb3 sidedis" onclick="manage_details(<?=$j?>,'deactivate');"><i class="fa fa-toggle-off" ></i>Deactivate</a> </li>
                                        <li> <a href="#" class="sideicbb3 sidedis" onclick="manage_details(<?=$j?>,'activate');"><i class="fa fa-toggle-on" ></i>Activate</a> </li>
                                        <li> <a href="#" class="sideicbb3 sidedis" onclick="manage_details(<?=$j?>,'delete');"><i class="fa fa-trash" ></i>Delete</a> </li>
                                    </ul>
                                </div>
                            </div></th> 
                    <th>Action</th>
                    <th>Sub Theme</th>
                    <th>Current Status</th>
                    <th>Status Change</th>              
                </tr>
            </thead>
            <tbody>     
                <?php
        $sn = 1;
        // debug($subtheme_details);exit;
        foreach ($subtheme_details as $key => $data) {
        if($data['status']==1)
        {
           $status = '<span style="color:green;">Active</span>';
        }
        else
        {
           $status = '<span style="color:red;">In-Active</span>';
        }
        echo '<tr>
              <td>'.$sn.'</td>
              <td><input type="checkbox" class="interested'.$j.'"   id="interested_'.$j.'_'.$sn.'"   value="'.$data['id'].'" onclick="uncheckfun(this)"/></td>
              <td>
                <div class="dropdown2" role="group">
                   <div class="dropdown slct_tbl pull-left sideicbb">
                       <i class="fa fa-ellipsis-v"></i>  
                        <ul class="dropdown-menu sidedis" style="display: none;">
                           
                           <li> <a class="sidedis sideicbb3 callDelete" id="'.$data['id'].'" href="'.base_url().'index.php/activity/delete_subtheme/'.$data['id'].'/'.$data['activity_theme_id'].'"> 
                <i class="glyphicon glyphicon-trash"></i> Delete</a></li>
                       </ul>
                    </div>
                </div>
              </td>
              <td>'.$data['sub_theme'].'</td>';
              // <li><a class="sidedis sideicbb1" data-placement="top" href="'.base_url().'index.php/activity/edit_activity_subtheme/'.$data['id'].'"
              //   data-original-title="Edit Excursion Sub Theme"> <i class="glyphicon glyphicon-pencil"></i> Edit
              //   </a> </li>
        //echo '<td>'.$galleryImages.'</td>';
        echo '<td>'.$status.'</td>';
        if($data['status']==1)
        {
        echo '<td>
              <a class="" data-placement="top" href="'.base_url().'index.php/activity/activation_subtheme/'.$data['id'].'/0/'.$data['activity_theme_id'].'"
              data-original-title="Deactivate Excursion Theme"> <i class="glyphicon glyphicon-th-large"></i></i> De-activate
              </a>
              </td>
              </tr>';
        }
        else
        {
        echo '<td>
              <a class="" data-placement="top" href="'.base_url().'index.php/activity/activation_subtheme/'.$data['id'].'/1/'.$data['activity_theme_id'].'"
              data-original-title="Activate Excursion Theme"> <i class="glyphicon glyphicon-th-large"></i></i> Activate
              </a>
              </td>
              </tr>';
        } 
        $sn++;
        }
        ?>
        </tbody>
        </table>
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
<script>
    $(function () { $('.table').DataTable(); }); 
function uncheckfun(data)
{
    // alert(data.id);
    if(!$('#'+data.id).is(':checked')) {
        if($('#selectall1').is(':checked')) { 

            $('#selectall1').prop('checked', false); 
         
        } 
    }

}
function checkall(id)
{ 
    if($('#selectall'+id).is(':checked')) { 

     $('.interested'+id).prop('checked', true); 
     
    } 
    else{ 
     $('.interested'+id).prop('checked', false); 
    } 
      // for unselect disabled checkbox
       $('.interested'+id+':checked').map( 
      
        function(){ 
          var idd=$(this).attr('id');
          
          if($('#'+idd).is(':disabled')) {
          
          $('#'+idd).prop('checked', false); 
        } 
        }).get();
}

function manage_details(id,operation)
{
      var checkval = $('.interested'+id+':checked').map( function(){ return $(this).val();}).get(); 
      if(checkval=='')
      {
        alert('Please Select Any Excursion Sub Theme!!')
        return false;
      }
      if(operation=='delete'){
     var result = confirm("Are you sure to delete?");
      if(result){
          
      }else{
        return false;
      }
    }
      var theme_tbl = 'sub_theme_activity';
              var url="<?php echo base_url().'index.php/activity/manage_activity_details_theme'; ?>" ;
              $.ajax({
                      url :url,
                      type: 'POST',
                      data: {checkval:checkval,operation:operation,theme_tbl:theme_tbl},
                      success: function(data)
                      {
                        alert(data);
                       location.reload()
                      }
                    });
}

 // $(".callDelete").click(function() { 
 //            $id = $(this).attr('id'); //alert($id);
 //                $response = confirm("Are you sure to delete this record???");
 //                if($response==true){ window.location='<?=base_url()?>index.php/activity/delete_subtheme/'+$id; } else{}
 //           });
</script>
<!--
<script type="text/javascript" src="/chariot/extras/system/template_list/template_v1/javascript/js/nicEdit-latest.js"></script> 
<script type="text/javascript">
bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });
</script>-->
