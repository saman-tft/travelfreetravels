<?php
$get_data = $this->input->get();
// debug($user_details_lo_pro);exit;
if($user_details_lo_pro['status']){
  if($user_details_lo_pro['data']){
    foreach ($user_details_lo_pro['data'] as $key => $value) {
      if($value['module_type']=='Hotel'){
        $hstatus=$value['status'];
        // $hreward_point=$value['reward_point'];
        // $hbooking_range=$value['booking_range'];
        // $htime_period=$value['time_period'];
      }
      if($value['module_type']=='Holiday'){
        $holstatus=$value['status'];
        // $holreward_point=$value['reward_point'];
        // $holbooking_range=$value['booking_range'];
        // $holtime_period=$value['time_period'];
      }
      if($value['module_type']=='Transfer'){
        $tstatus=$value['status'];
        // $treward_point=$value['reward_point'];
        // $tbooking_range=$value['booking_range'];
        // $ttime_period=$value['time_period'];
      }
      if($value['module_type']=='Excursion'){
        $acstatus=$value['status'];
        // $acreward_point=$value['reward_point'];
        // $acbooking_range=$value['booking_range'];
       // $actime_period=$value['time_period'];
      }
      if($value['module_type']=='Visa'){
        $acstatus=$value['status'];
        // $acreward_point=$value['reward_point'];
        // $acbooking_range=$value['booking_range'];
       // $actime_period=$value['time_period'];
      }

    }
  }
}


$module_list=array();
// debug($master_module);
foreach ($master_module as $key => $value) {
    array_push($module_list,$value['module_name']);
}

// debug($module_list);exit;
?>
<div class="box box-danger">
  <div class="box-header with-border">
    <h3 class="box-title">
      <i class="fa fa-database"></i> Agent Loyalty Program
    </h3>
  </div>
  <div class="box-body">
    <div class="row">
      <div class="col-md-5">
        <div class="box box-danger" style="background: #EFEFEF">
          <div class="box-header with-border">
            <h3 class="box-title">Agent Loyalty Program</h3>
          </div>
        </div>
      </div>
      <div class="col-md-7">
        <div class="nav-tabs-custom nav_br">
          <!-- Nav pills -->
          <ul class="nav nav-tabs ggty" role="tablist">
            
            <?php 
              if(in_array("Hotel", $module_list))
              {

                ?>
                  <li role="presentation"
                    class="hotel-l-bg ghty "><a
                    href="#hotel" aria-controls="hotel" role="pill"
                    data-toggle="pill"><span
                      class="hidden-xs">Hotel</span></a></li>
                      <?php

              }
              if(in_array("Holiday", $module_list))
              {
                      ?>
           
            
            
          
            <li role="presentation" class="payment-l-bg ghty ">
              <a  href="#Holidays" aria-controls="Holidays" role="pill" data-toggle="pill">
                <span class="hidden-xs">Holidays</span>
              </a>
            </li>
             <?php

              }
              if(in_array("Transfer", $module_list))
              {
                      ?>

           
            <li role="presentation"
              class="transfer-l-bg ghty "><a
              href="#transfer" aria-controls="transfer" role="pill"
              data-toggle="pill"><span
                class="hidden-xs">Transfer</span></a></li>
            <?php

              }
              if(in_array("Excursion", $module_list))
              {
                      ?>
            
            <li role="presentation"
              class="transfer-l-bg ghty "><a
              href="#activities" aria-controls="activities" role="pill"
              data-toggle="pill"> <span
                class="hidden-xs">Excursion</span></a></li>
            <?php

              }
              if(in_array("Visa", $module_list))
              {
                      ?>
            
            <li role="presentation"
              class="transfer-l-bg ghty "><a
              href="#Visa" aria-controls="Visa" role="pill"
              data-toggle="pill"> <span
                class="hidden-xs">Visa</span></a></li>
            <?php

              }
              
                      ?>
            


          </ul>
        </div>
      </div>
      <div class="col-md-12">
        
        <!-- Tab panes -->
        <div class="tab-content highlight">
          <p class="hide">Check Module Reward Points</p>
          
         
          <div role="tabpanel"
            class="clearfix tab-pane fade in active"
            id="hotel">

            <form method="POST" autocomplete="off" id="search_filter_form" action="<?php echo base_url()?>loyalty_program/savehotel">
              <input type="hidden" name="module_type" value="Hotel" >
              <input type="hidden" name="agent_id" value="<?php echo $get_data['agent_id'];?>" >
             <div class="clearfix form-group">
              <div class="col-xs-4">
                  <label>Status</label>
                  <select class="form-control" name="status" value="<?=@$agency_group?>">
                    <option value="">Please Select</option>
                    
                    <option value="1" <?php if(@$hstatus==1){echo 'selected';}?>>Active</option>
                    <option value="0" <?php if(@$hstatus==0){echo 'selected';}?>>Inactive</option>
                    
                  
                  </select>
              </div>
              
                <!-- <div class="col-xs-4">
                  <label>Reward point</label>
                  <input type="text" placeholder="Reward point"  name="reward_point" class="search_filter form-control" value="<?=@$hreward_point?>">
                </div> -->
                <!-- <div class="col-xs-4">
                  <label>Booking Range</label>
                  <input type="text" placeholder="Booking Range" value="<?=@$hbooking_range?>" name="booking_range" class="search_filter form-control">
                </div> -->
                <!-- <div class="col-xs-4">
                  <label>Select time</label>
                  <select class="form-control" name="time_period" value="<?=@$atime_period?>">
                    <option value="">Please Select</option>
                    
                    <option value="1" <?php if(@$htime_period==1){echo 'selected';}?>>monlthly</option>
                    <option value="3" <?php if(@$htime_period==3){echo 'selected';}?>>quatrly</option>
                    <option value="6" <?php if(@$htime_period==6){echo 'selected';}?>>halfly</option>
                    <option value="12" <?php if(@$htime_period==12){echo 'selected';}?>>yearly</option>
                    
                  
                  </select>
              </div> -->
                
              </div>
              <div class="col-sm-12 well well-sm">
                <button class="btn btn-primary" type="submit">Submit</button> 

                <a class="btn btn-warning" href="<?php echo base_url();?>user/b2b_user?user_status=1">Back</a>
              </div>
            </form>

            
          </div>
        

          

          
          
          <div role="tabpanel"
            class="clearfix tab-pane fade in "
            id="transfer">
            
           <form method="POST" autocomplete="off" id="search_filter_form" action="<?php echo base_url()?>loyalty_program/savehotel">
              <input type="hidden" name="module_type" value="Transfer">
               <input type="hidden" name="agent_id" value="<?php echo $get_data['agent_id'];?>" >
             <div class="clearfix form-group">
              <div class="col-xs-4">
                  <label>Status</label>
                  <select class="form-control" name="status" value="<?=@$agency_group?>">
                    <option value="">Please Select</option>
                    
                    <option value="1" <?php if(@$tstatus==1){echo 'selected';}?>>Active</option>
                    <option value="0" <?php if(@$tstatus==0){echo 'selected';}?>>Inactive</option>
                    
                  
                  </select>
              </div>
              
                <!-- <div class="col-xs-4">
                  <label>Reward point</label>
                  <input type="text" placeholder="Reward point"  name="reward_point" class="search_filter form-control" value="<?=@$treward_point?>">
                </div> -->
                <!-- <div class="col-xs-4">
                  <label>Booking Range</label>
                  <input type="text" placeholder="Booking Range" value="<?=@$tbooking_range?>" name="booking_range" class="search_filter form-control">
                </div> -->
               <!--  <div class="col-xs-4">
                  <label>Select time</label>
                  <select class="form-control" name="time_period" value="<?=@$atime_period?>">
                    <option value="">Please Select</option>
                    
                    <option value="1" <?php if(@$ttime_period==1){echo 'selected';}?>>monlthly</option>
                    <option value="3" <?php if(@$ttime_period==3){echo 'selected';}?>>quatrly</option>
                    <option value="6" <?php if(@$ttime_period==6){echo 'selected';}?>>halfly</option>
                    <option value="12" <?php if(@$ttime_period==12){echo 'selected';}?>>yearly</option>
                    
                  
                  </select>
              </div> -->
                
              </div>
              <div class="col-sm-12 well well-sm">
                <button class="btn btn-primary" type="submit">Submit</button> 
               <a class="btn btn-warning" href="<?php echo base_url();?>user/b2b_user?user_status=1">Back</a>
              </div>
            </form>

            
          </div>
         
            
          <div role="tabpanel" class="clearfix tab-pane fade in " id="Holidays"> 

            <form  method="POST" autocomplete="off" id="search_filter_form" action="<?php echo base_url()?>loyalty_program/savehotel">
              <input type="hidden" name="module_type" value="Holiday">
               <input type="hidden" name="agent_id" value="<?php echo $get_data['agent_id'];?>" >
              <div class="clearfix form-group">
              <div class="col-xs-4">
                  <label>Status</label>
                  <select class="form-control" name="status" value="<?=@$agency_group?>">
                    <option value="">Please Select</option>
                    
                    <option value="1" <?php if(@$holstatus==1){echo 'selected';}?>>Active</option>
                    <option value="0" <?php if(@$holstatus==0){echo 'selected';}?>>Inactive</option>
                    
                  
                  </select>
              </div>
              
                <!-- <div class="col-xs-4">
                  <label>Reward point</label>
                  <input type="text" placeholder="Reward point"  name="reward_point" class="search_filter form-control" value="<?=@$holreward_point?>">
                </div> -->
                <!-- <div class="col-xs-4">
                  <label>Booking Range</label>
                  <input type="text" placeholder="Booking Range" value="<?=@$holbooking_range?>" name="booking_range" class="search_filter form-control">
                </div> -->
               <!--  <div class="col-xs-4">
                  <label>Select time</label>
                  <select class="form-control" name="time_period" value="<?=@$atime_period?>">
                    <option value="">Please Select</option>
                    
                    <option value="1" <?php if(@$holtime_period==1){echo 'selected';}?>>monlthly</option>
                    <option value="3" <?php if(@$holtime_period==3){echo 'selected';}?>>quatrly</option>
                    <option value="6" <?php if(@$holtime_period==6){echo 'selected';}?>>halfly</option>
                    <option value="12" <?php if(@$holtime_period==12){echo 'selected';}?>>yearly</option>
                    
                  
                  </select>
              </div> -->
                
              </div>
              <div class="col-sm-12 well well-sm">
                <button class="btn btn-primary" type="submit">Submit</button> 
               <a class="btn btn-warning" href="<?php echo base_url();?>user/b2b_user?user_status=1">Back</a>
              </div>
            </form>

          
          </div>
          <div role="tabpanel"
            class="clearfix tab-pane fade in "
            id="activities">
            
            <form  method="POST" autocomplete="off" id="search_filter_form" action="<?php echo base_url()?>loyalty_program/savehotel">
              <input type="hidden" name="module_type" value="Excursion">
               <input type="hidden" name="agent_id" value="<?php echo $get_data['agent_id'];?>" >
             <div class="clearfix form-group">
              <div class="col-xs-4">
                  <label>Status</label>
                  <select class="form-control" name="status" value="<?=@$agency_group?>">
                    <option value="">Please Select</option>
                    
                    <option value="1" <?php if(@$acstatus==1){echo 'selected';}?>>Active</option>
                    <option value="0" <?php if(@$acstatus==0){echo 'selected';}?>>Inactive</option>
                    
                  
                  </select>
              </div>
             
               <!--  <div class="col-xs-4">
                  <label>Reward point</label>
                  <input type="text" placeholder="Reward point" name="reward_point" class="search_filter form-control" value="<?=@$acreward_point?>">
                </div> -->
               <!--  <div class="col-xs-4">
                  <label>Booking Range</label>
                  <input type="text" placeholder="Booking Range" value="<?=@$acbooking_range?>" name="booking_range" class="search_filter form-control">
                </div> -->
                <!-- <div class="col-xs-4">
                  <label>Select time</label>
                  <select class="form-control" name="time_period" value="<?=@$atime_period?>">
                    <option value="">Please Select</option>
                    
                    <option value="1" <?php if(@$actime_period==1){echo 'selected';}?>>monlthly</option>
                    <option value="3" <?php if(@$actime_period==3){echo 'selected';}?>>quatrly</option>
                    <option value="6" <?php if(@$actime_period==6){echo 'selected';}?>>halfly</option>
                    <option value="12" <?php if(@$actime_period==12){echo 'selected';}?>>yearly</option>
                    
                  
                  </select>
              </div> -->
                
              </div>
              <div class="col-sm-12 well well-sm">
                <button class="btn btn-primary" type="submit">Submit</button> 
               <a class="btn btn-warning" href="<?php echo base_url();?>user/b2b_user?user_status=1">Back</a>
              </div>
            </form>

            
          </div>
          <div role="tabpanel"
            class="clearfix tab-pane fade in "
            id="Visa">
            
            <form  method="POST" autocomplete="off" id="search_filter_form" action="<?php echo base_url()?>loyalty_program/savehotel">
              <input type="hidden" name="module_type" value="Visa">
               <input type="hidden" name="agent_id" value="<?php echo $get_data['agent_id'];?>" >
             <div class="clearfix form-group">
              <div class="col-xs-4">
                  <label>Status</label>
                  <select class="form-control" name="status" value="<?=@$agency_group?>">
                    <option value="">Please Select</option>
                    
                    <option value="1" <?php if(@$acstatus==1){echo 'selected';}?>>Active</option>
                    <option value="0" <?php if(@$acstatus==0){echo 'selected';}?>>Inactive</option>
                    
                  
                  </select>
              </div>
             
              
                
              </div>
              <div class="col-sm-12 well well-sm">
                <button class="btn btn-primary" type="submit">Submit</button> 
               <a class="btn btn-warning" href="<?php echo base_url();?>user/b2b_user?user_status=1">Back</a>
              </div>
            </form>

            
          </div>
          

        </div>
      </div>
    </div>
  </div>
  <!-- /.box-body -->
</div>

<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit Reward Point</h4>
      </div>
      <div class="modal-body">
        <form method="post" autocomplete="off" action="<?php echo base_url();?>index.php/user/edit_reward" >
              <input type="text" name="reward_point" placeholder="Enter Hotel Reward Point">
              <input type="hidden" name="moduleid" id="moduleid">
              <input type="submit" class="btn btn-sm btn-primary" value="Submit"/>
            </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>

<script type="text/javascript">
$(document).on('click', '.update1', function(){
  
    var userid=$('#userid1').val();
    $('#moduleid').val(userid);
    $('#myModal').modal("show");
});
$(document).on('click', '.update2', function(){
  
    var userid=$('#userid2').val();
    $('#moduleid').val(userid);
    $('#myModal').modal("show");
});
$(document).on('click', '.update3', function(){
  
    var userid=$('#userid3').val();
    $('#moduleid').val(userid);
    $('#myModal').modal("show");
});
$(document).on('click', '.update4', function(){
  
    var userid=$('#userid4').val();
    $('#moduleid').val(userid);
    $('#myModal').modal("show");
});

$("#submit1").on('click',function(){
                  
      $status=$.post(app_base_url + "index.php/user/b2b_loyalty_user", function(result){

    });
    if($status!='')
    {
     toastr.info("Please Select Update!!!");
     window.location.reload();
    }
    else
    {
     toastr.info("Please Enter Reward Points!!!");
     window.location.reload();
    }
});
$("#submit2").on('click',function(){
                  
      $status=$.post(app_base_url + "index.php/user/b2b_loyalty_user", function(result){

    });
    if($status!='')
    {
     toastr.info("Please Select Update!!!");
     window.location.reload();
    }
    else
    {
     toastr.info("Please Enter Reward Points!!!");
     window.location.reload();
    }
});
$("#submit3").on('click',function(){
                  
      $status=$.post(app_base_url + "index.php/user/b2b_loyalty_user", function(result){

    });
    if($status!='')
    {
     toastr.info("Please Select Update!!!");
     window.location.reload();
    }
    else
    {
     toastr.info("Please Enter Reward Points!!!");
     window.location.reload();
    }
});
$("#submit4").on('click',function(){
                  
      $status=$.post(app_base_url + "index.php/user/b2b_loyalty_user", function(result){

    });
    if($status!='')
    {
     toastr.info("Please Select Update!!!");
     window.location.reload();
    }
    else
    {
     toastr.info("Please Enter Reward Points!!!");
     window.location.reload();
    }
});

</script>