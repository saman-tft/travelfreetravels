
<style type="text/css">
  .colorred{
    color:red;
  }
</style>
  <div class="box-body bby">
    <div class="row">
      <div class="col-md-5 bxpd">
        <div class="hedbox box-danger">
          <div class="box-header">
            <h3 class="box-title">Master Modules List</h3>
          </div>
        </div>
      </div>
      
      <div class="col-md-12 bxpd">
        
        <!-- Tab panes -->
        <div class="tab-content highlight">
          <p class="hide">Check Modules</p>
          
         
          <div role="tabpanel"
            class="clearfix tab-pane fade in active"
            id="hotel">

            <table class="table table-condensed table-bordered scroll_main_set datatable" id="hotel">
              <thead>
                <tr>
                  <th>S.No</th>
                  <th>Action</th>
                  <th>Modules</th>
                  <th>Status</th>
                  
                  
                  
                  
                  <!--<th>Current Status</th> -->
                </tr>
              </thead>  
              <tbody>
                <?php
                $query=$this->user_model->get_module_list();
                // debug($query);exit;
                $action="";
                foreach ($query as $key => $value) {
                  $mod_name='"'.$value['module_name'].'"';
                 $action="<a type='button' class='sidedis sideicbb1' onclick='openmodalrange(".$value['id'].",".$mod_name.")'>Details</a>";
                ?>
                <tr>
                  <td><?=$value['id']?></td>
                  <td>
					  
					  
					  <div class="dropdown2" role="group">
				   <div class="dropdown slct_tbl pull-left sideicbb">
					   <i class="fa fa-ellipsis-v"></i>  
					    <ul class="dropdown-menu sidedis" style="display: none;">
						   <li>
						 <?=$action?>
						   </li>
						</ul>
				    </div>
				</div></td>
                  <td><?=$value['module_name']?></td>
                <td>
                <input type="hidden" id="userid" name="userid" value="<?=$value['id']?>">
                <select class="action" id="stat_<?=$value['id']?>" onchange="updatemodulestatus(<?=$value['id']?>)">
                  
                  <option value="">Please Select</option>
                  <option value="ENABLE" <?php if($value['status']=='ENABLE'){echo "selected";}?>> ENABLE </option>
                  <option value="DISABLE" <?php if($value['status']=='DISABLE'){echo "selected";}?>> DISABLE </option>
              </select>
              </td>
              
              <input type="hidden" name="rewar_value" class="form-control" id="rewar_value_default<?=$value['id']?>" value="<?=$value['defult_value']?>">
              
            </tr>
            <?php } ?>
              </tbody>            
              <tfoot>
                <tr>
                  <th>S.No</th>
                  <th>Action</th>
                  <th>Modules</th>
                  <th>Status</th>
                  
                  
                  
                  
                </tr>
              </tfoot>
            </table>
          </div>

        </div>
      </div>
    </div>

      <div id="myModal" class="modal fade" role="dialog">
          <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="title"></h4>
              </div>
              <div class="modal-body">
                
                <form method="POST" autocomplete="off" id="range_point_form" >
                      <input type="hidden" placeholder="module_id" value="" name="module_id" id="module_id" class="search_filter form-control">
                     <div class="clearfix form-group">
                      <div class="col-xs-4">
                          <label>Start Range Amount</label>
                          <input type="text" placeholder="start_range" value="" name="start_range"  id="start_range" class="search_filter form-control numeric">
                      </div>
                       <div class="clearfix form-group">
                      <div class="col-xs-4">
                          <label>End Range Amount</label>
                          <input type="text" placeholder="end_range" value="" name="end_range" id="end_range" class="search_filter form-control numeric">
                      </div>
                       <div class="clearfix form-group">
                      <div class="col-xs-4">
                          <label>Reward Point</label>
                          <input type="text" placeholder="point" value="" name="point" id="point" class="search_filter form-control numeric">
                      </div>
                      <span class="colorred"></span>
                        <!-- <div class="col-xs-4">
                          <label>Reward point</label>
                          <input type="text" placeholder="Reward point"  name="reward_point" class="search_filter form-control" value="<?=@$hreward_point?>">
                        </div> -->
                        
                        
                        
                      </div>
                      <div class="col-sm-12 well well-sm">
                        <button class="btn btn-primary" type="button" id="range_button">Submit</button> 

                        
                      </div>
                    </form>

                    <div class="clearfix form-group">
                      <div class="col-xs-4">
                          <label>Default Reward Points</label>
                         <input type="text" name="rewar_value" class="form-control numeric" id="rewar_value_" value=""><input type="button" class="btn btn-primary" name="submit" value="Submit" onclick="update_defult_value()">
                      </div>
                    </div>
                <div id="range_list">
                  


                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </div>

          </div>
        </div>



  </div>
  <!-- /.box-body -->

<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>

<script type="text/javascript">
  
  $('body').on("keypress", '.numeric', function(event) {
    /*$('.numeric').keypress(function(event){*/
            console.log(event.which);
        if(event.which != 8 && isNaN(String.fromCharCode(event.which))){
            event.preventDefault();
        }
    });

function update_defult_value(){
  
    
    var defult_value=$("#rewar_value_").val();
    var module_id=$("#module_id").val();
    
 
    //toastr.info('Please Wait!!!');
  $status=$.post(app_base_url + "index.php/loyalty_program/edit_module_status", {defult_value: defult_value,id: module_id}, function(result){


    });
    if($status!='')
    {
     toastr.info("Update Successfully!!!");
     window.location.reload();
    }
    else
    {
     toastr.info("Not Update!!!");
     window.location.reload();
    }
}
function updatemodulestatus(id){
  
    
    var status=$("#stat_"+id).val();
    
 
    // alert(status);
  $status=$.post(app_base_url + "index.php/loyalty_program/update_master_module_status", {status: status,id: id}, function(result){


    });
    if($status!='')
    {
     toastr.info("Update Successfully!!!");
     // window.location.reload();
    }
    else
    {
     toastr.info("Not Update!!!");
     // window.location.reload();
    }
} 
function openmodalrange(id,module){
  
    var module_default_val=$("#rewar_value_default"+id).val();
    
    
 
   
  $status=$.post(app_base_url + "index.php/loyalty_program/get_module_range", {id: id}, function(result){
       $('#myModal').modal('show');
        $('#range_list').html(result);
        $('#title').text(module+" Module-Define Range");
        $('#module_id').val(id);

        $("#rewar_value_").val(module_default_val);

    });
    /*if($status!='')
    {
     toastr.info("Update Successfully!!!");
     // window.location.reload();
    }
    else
    {
     toastr.info("Not Update!!!");
     // window.location.reload();
    }*/
}
$(document).ready(function(){
  $("#range_button").click(function(){
      var start_range=$("#start_range").val();
      var end_range=$("#end_range").val();
      var point=$("#point").val();
      // var point=$("#point").val();
      var module_id=$("#module_id").val();
      if(start_range =="" || end_range =="" || point =="")
      {
          $(".colorred").text("Provide all value");
      }
      else
      {
           

             $status=$.post(app_base_url + "index.php/loyalty_program/save_range_point", {start_range: start_range,end_range:end_range,point:point,module_id:module_id}, function(result){
                    if(result==1)
                    {
                          $("#start_range").empty();
                          $("#end_range").empty();
                          $("#point").empty();
            
                          $.post(app_base_url + "index.php/loyalty_program/get_module_range", {id: module_id}, function(result){
                            
                              $('#range_list').html(result);
                              

                              

                          });
                    }
                    else
                    {
                      alert("Price Range aAlready Exist");

                    }

                   
              

              

          });

            
      }
  })
})  

$(".allownumericwithdecimal").on("keypress keyup blur",function (event) {
            //this.value = this.value.replace(/[^0-9\.]/g,'');
     $(this).val($(this).val().replace(/[^0-9\.]/g,''));
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });

</script>