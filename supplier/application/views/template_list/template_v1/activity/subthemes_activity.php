 <div class="modal-header">
    <button type="button" class="close" onclick="return location.reload(true);" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">Excursion Sub Theme</h4>
   </div>
<div class="modal-body">
<form class="form-horizontal" action="<?php echo base_url(); ?>index.php/visa/save_application/<?=$utype['utype']?>" method="post" id="upload">
        <input type="hidden" name="visa_id" value="<?=$tc_data['user_id']?>">
              
                
 <div class='form-group'>

<div class='col-sm-12 controls'>
<div class="plcetogo sidebord">

<?php
foreach ($get_theme_details as $key => $value) {
    $activity_subtheme = $value['activity_subtheme'];
}
?>
                    
  <ul class="list-group ">
  <li class="list-group-item list-group-item-action active"> <b>Theme - <?=$activity_subtheme?></b></li>
  <?php foreach ($get_theme_details as $key => $value) {
                        $sub_theme = $value['sub_theme']; ?>
  <li class="list-group-item"><?=$sub_theme?></li>
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