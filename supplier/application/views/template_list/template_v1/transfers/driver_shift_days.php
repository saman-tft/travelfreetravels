 <div class="modal-header">
    <button type="button" class="close" onclick="return location.reload(true);" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">Driver Shift Days</h4>
   </div>
<div class="modal-body">
<form class="form-horizontal"  method="post" id="upload">
        <input type="hidden" name="visa_id" value="<?=$tc_data['user_id']?>">
              
                
 <div class='form-group'>

<div class='col-sm-12 controls'>
<div class="plcetogo sidebord">

                    
  <ul class="list-group ">
  <li class="list-group-item list-group-item-action active"> <b>Name - <?=$shift_days['driver_name']?></b></li>
  <?php
$driver_shift_days = json_decode($shift_days['driver_shift_days'],true);
                            $shiftDay = '';
                            foreach ($driver_shift_days as $shift_key => $value) {
                            

                              if($value == 1){
                                $shiftDay = 'Monday';
                              }
                              if($value == 2){
                                $shiftDay = 'Tuesday';
                              }
                              if($value == 3){
                                $shiftDay = 'Wednesday';
                              }
                              if($value == 4){
                                $shiftDay = 'Thursday';
                              }
                              if($value == 5){
                                $shiftDay = 'Friday';
                              }
                              if($value == 6){
                                $shiftDay = 'Saturday';
                              }
                              if($value == 7){
                                $shiftDay = 'Sunday';
                              }
                        ?>
  <li class="list-group-item"><?=$shiftDay?></li>
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