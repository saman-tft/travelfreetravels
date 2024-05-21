 <div class="modal-header">
    <button type="button" class="close" onclick="return location.reload(true);" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">Excursion Sub Type</h4>
   </div>
<div class="modal-body">
<form class="form-horizontal"  method="post" id="upload">
        <input type="hidden" name="visa_id" value="<?=$tc_data['user_id']?>">
              
                
 <div class='form-group'>

<div class='col-sm-12 controls'>
<div class="plcetogo sidebord">

<?php
foreach ($sub_category as $key => $value) {
    $activity_type = $value['activity_types_name'];
}
?>
                    
  <ul class="list-group ">
  <li class="list-group-item list-group-item-action active"> <b>Excursion Type - <?=$activity_type?></b></li>
  <?php foreach ($sub_category as $key => $value) {
                        $sub_type = $value['activity_sub_category']; ?>
  <li class="list-group-item"><?=$sub_type?></li>
                    <?php 
                    }
                    ?>
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