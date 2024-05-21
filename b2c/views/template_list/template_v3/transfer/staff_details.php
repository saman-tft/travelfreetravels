 <div class="modal-header">
    <button type="button" class="close" onclick="return location.reload(true);" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">Staff Details</h4>
   </div>
<div class="modal-body">
<form class="form-horizontal"  method="post" id="upload">
        <input type="hidden" name="visa_id" value="<?=$tc_data['user_id']?>">
              
                
 <div class='form-group'>

<div class='col-sm-12 controls'>
<div class="plcetogo sidebord">
                   
  <ul class="list-group ">
  <li class="list-group-item list-group-item-action active"> <b>Name</b> - <?=$get_staff_info[0]['first_name']." ".$get_staff_info[0]['last_name']?></li>
  <li class="list-group-item list-group-item-action active"><b>Phone Number</b> - <?=$get_staff_info[0]['phone']?></li>
  <li class="list-group-item list-group-item-action active"><b>Email</b> - <?=provab_decrypt($get_staff_info[0]['email'])?></li>
  <li class="list-group-item list-group-item-action active"><b>Staff Code</b> - <?=provab_decrypt($get_staff_info[0]['uuid'])?></li>
                   
</ul>
                </div>
                </div>
                </div>
      <div class='' style='margin-bottom: 0'>
        
                </div>
            </form> 

             </div>


     <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal" onclick="return location.reload(true);">Close</button>
   </div>