<style type="text/css">
 .read_more{cursor: pointer;}
	.rtghu{min-width:36px!important;}
</style>
<script type="text/javascript">
   $( function() {
   // $('.datepicker_from').val($.datepicker.formatDate('dd/mm/yy', new Date()));
$('.datepicker_from').datepicker({
    changeMonth: true,
    changeYear: true,
    dateFormat: 'dd/mm/yy',
    minDate: 0,
    beforeShow: function(input, inst) {
        var maxDate = $('.datepicker_to').datepicker('getDate');
        if(maxDate!=null)
        $('.datepicker_from').datepicker('option', 'maxDate', maxDate);
    }
});

$('.datepicker_to').datepicker({
    changeMonth:true,
    changeYear:true,
    dateFormat: 'dd/mm/yy',
    beforeShow: function(input, inst) {
        var minDate = $('.datepicker_from').datepicker('getDate');
        $('.datepicker_to').datepicker('option', 'minDate', minDate);
    }
});

  } );
   function get_seasonality()
   {
    var tran_id = $('#transfer_id').val();
    if(tran_id=='')
    {
      alert('Please Select Transfer Type')
      $('#seasonality_from').val('');
      $('#seasonality_to').val('');
    }
    var seasonality_from = $('#seasonality_from').val();
     $.ajax({
      url: "<?php echo base_url();?>transfers/check_seasonality",
      type: "POST",
      data: {tran_id:tran_id,seasonality_from:seasonality_from},
      success: function (data) {
        if(data==1)
        {

        }else{
        alert(data);
        $('#seasonality_from').val('');
        $('#seasonality_to').val('');
        }
      }
    });
   }
   function get_seasonality_details()
   {
    var tran_id = $('#transfer_id').val();
    if(tran_id=='')
    {
      alert('Please Select Transfer Type')
      $('#seasonality_from').val('');
    }
    var seasonality_from = $('#seasonality_from').val();
    if(seasonality_from=='')
    {
      alert('Please Select Seasonality From')
      $('#seasonality_from').val('');
      $('#seasonality_to').val('');
    }
    var seasonality_to = $('#seasonality_to').val();
     $.ajax({
      url: "<?php echo base_url();?>transfers/check_seasonality_detls",
      type: "POST",
      data: {tran_id:tran_id,seasonality_from:seasonality_from,seasonality_to:seasonality_to},
      success: function (data) {
        if(data==1)
        {

        }else{
        alert(data);
        $('#seasonality_from').val('');
        $('#seasonality_to').val('');
        }
      }
    });
   }
</script>
<?php //error_reporting(E_ALL); ?>
<div id="Package" class="bodyContent col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">
                <ul class="nav nav-tabs nav-justified" role="tablist" id="myTab">
                    <li role="presentation" class="active" id="add_package_li">
      <a href="#add_package" aria-controls="home" role="tab" data-toggle="tab">Cancellation Policy Management </a>
     </li>
     <li aria-controls="home"> &nbsp;&nbsp;
      <button class='btn btn-primary' onclick="$('.form').slideToggle();">Add</button>
     </li>  
    </ul>
   </div>
  </div>
  <div class="panel-body">

    <!-- PANEL HEAD START -->
<form class='form form-horizontal validate-form' style='margin-bottom: 0;' action="<?php echo base_url(); ?>transfers/cancellation_policy"  method="post" id="vehicle_form" name="frm1" enctype="multipart/form-data" novalidate> 
  <input type="hidden" value="<?=$tc_data['id'];?>" name="id">
 <div class="col-md-12 col-sm-12 col-xs-12 mtp20">
                                         <div class="form-group item">
                                            <label class="control-label col-md-2 col-sm-2 col-xs-12">Transfer Name<span class="text-danger">*</span></label>
                                           
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                             <select class="form-control" name="transfer_id" id="transfer_id" required >
                                              <option value="">Select Transfer</option>
                                             <?php $car_supplier = $this->db->get_where('transfer_info', array('status' => 'ACTIVE'))->result_array();  ?>
                                                     <?php for($car_sup=0;$car_sup<count($car_supplier);$car_sup++){ ?>
                                                     <option value="<?php echo $car_supplier[$car_sup]['id'];?>" <?php if($tc_data['v_id']==$car_supplier[$car_sup]['id']) { echo 'selected'; } ?> ><?php echo $car_supplier[$car_sup]['transfer_name'];?></option>
                                                    <?php } ?>
                                                </select>
                                        
                                            <span class="error" style="color:#F00; display:none; ">This field is required</span>
                                            </div>
                                            </div>

                                            </div>

<!--    <div class="col-md-12 col-sm-12 col-xs-12 mtp20">
                                         <div class="form-group item">
                                            <label class="control-label col-md-2 col-sm-2 col-xs-12">Country</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                             <select class="form-control" name="country_id" id="country_id" onchange="get_country_currency(this.value)" required>
                                             <option value="">Select Country</option>
                                             <?php $car_supplier = $this->db->get_where('country_list', array())->result_array();  ?>
                                                     <?php for($car_sup=0;$car_sup<count($car_supplier);$car_sup++){ ?>
                                                     <option value="<?php echo $car_supplier[$car_sup]['country_list'];?>" <?php if($tc_data['country_id']==$car_supplier[$car_sup]['country_list']) { echo 'selected'; } ?> ><?php echo $car_supplier[$car_sup]['country_name'];?></option>
                                                    <?php } ?>
                                                </select>
                                        
                                            <span class="error" style="color:#F00; display:none; ">This field is required</span>
                                            </div>
                                            <div class="col-md-2 col-sm-2 col-xs-12">
                                            <label>Currency </label>
                                            <span id="currency"></span>
                                            </div>
                                            </div>

                                            </div> -->

 <div class="col-md-12 col-sm-12 col-xs-12 mtp20">
                                         <div class="form-group item">
                                            <label class="control-label col-md-2 col-sm-2 col-xs-12">Policy Name<span class="text-danger">*</span></label>
                                           
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                            <input type="text" name="policy_name" id="policy_name" class="form-control" required value="<?=$tc_data['policy_name']?>" maxlength="30">                                 
                                                    <span class="error" style="color:#F00; display:none; ">This field is required</span>
                                            </div>
                                            </div>

                                            </div>
 <div class="col-md-12 col-sm-12 col-xs-12 mtp20">
                                         <div class="form-group item">
                                            <label class="control-label col-md-2 col-sm-2 col-xs-12">Seasonality<span class="text-danger">*</span></label>
                                           <?php
                                           if($tc_data['start_date']!=''){
                                           $start_date = date('d/m/Y',strtotime($tc_data['start_date']));
                                           }else{$start_date='';}
                                           if($tc_data['start_date']!=''){
                                           $expiry_date = date('d/m/Y',strtotime($tc_data['expiry_date']));
                                           }else{$expiry_date='';}
                                           ?>
                                          <div class='col-sm-4 controls padfive'>
                                          <label>From</label>
                                          <input type="text" name="seasonality_from" id="seasonality_from" placeholder="" readonly class='form-control datepicker_from' maxlength="10" value="<?=$start_date?>" onchange="get_seasonality();" required> 
                                          </div>
                                          <div class='col-sm-4 controls padfive'>
                                          <label>To</label>
                                          <input type="text" name="seasonality_to" id="seasonality_to" placeholder="" readonly class='form-control datepicker_to' value="<?=$expiry_date?>" maxlength="10" onchange="get_seasonality_details();" required> 
                                          </div>
                                            </div>

                                            </div>
 <div class="col-md-12 col-sm-12 col-xs-12 mtp20">
 <div class="form-group item">
                                            <label class="control-label col-md-2 col-sm-2 col-xs-12">Charge Details<span class="text-danger">*</span></label>
                                           
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                            <div class="available_dates">
                                            <?php
                  if(isset($tc_data) && !empty($tc_data)){ 
                    // debug($dates_data);exit; 
                    foreach ($tc_data['charge_details'] as $key => $dates_data) {
                      $k = $key + 1;
// debug($dates_data->charge_type);exit;
                    
                    ?>
                    <div class="clearfix"><div class="dates_div">
                      <div class='col-sm-3 controls padfive'>
                     <label class="center">Charge Type</label><br>
                          
                       <input type="radio"  onclick="get_value(this.value,<?=$k?>);"  name="dates[<?=$k?>][option1]" id="option1_<?=$k?>" value="%"  <?php if($dates_data->charge_type=='%') { echo 'checked'; } else if($dates_data->charge_type=='') { echo 'checked'; } ?> >Percentage
                         &nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" onclick="get_value(this.value,<?=$k?>);" name="dates[<?=$k?>][option1]" id="option2_<?=$k?>"  value="Amount" <?php if($dates_data->charge_type=='Amount') { echo 'checked'; } ?> > Amount 
                         <input type="hidden" class="form-control" name="dates[<?=$k?>][charge_type]" id="charge_type_<?=$k?>" value="<?php if($dates_data->charge_type=='Amount'){ echo 'Amount'; } else { echo '%'; }?>"  maxlength="10" >
                      </div>
                      <div class='col-sm-3 controls padfive'>
                      <label>Charges</label>
                      <input type="text" class="form-control numeric" name="dates[<?=$k?>][amount]" id="amount_<?=$k?>" required maxlength="10" value="<?=$dates_data->amount?>" placeholder="Enter Charges" >
                      <span class="error" style="color:#F00; display:none; ">This field is required</span> 
                      </div>
                      <div class='col-sm-3 controls padfive'>
                      <label>Days</label>
                      <input type="text" name="dates[<?=$k?>][no_days]" id="days_<?=$k?>"
                        data-rule-minlength='2' data-rule-required='true'
                        placeholder="Number of Days" value="<?php echo $dates_data->no_of_days;?>"
                        class='form-control add_pckg_elements days' required>
                      </div>
                    
                        <?php
                        if($k==1){
                        ?>
                          <div class='col-sm-2 controls add_tab padfive add_avail_dates'><br>
                        <span class="btn btn-primary"><i class="fa fa-plus"></i></span>
                        </div>
                        <?php }else{
                          ?>
                        <div class='col-sm-2 close_bar controls padfive'><br>
                    <span class="btn btn-primary"><i class="far fa-times-circle"></i></span>
                  </div>
                          <?php }?>
                      
                    </div></div>
                 <?php
                }
                  }else{
                  ?>
                    <div class="clearfix"><div class="dates_div">
                      <div class='col-sm-3 controls padfive'>
                      <label class="center">Charge Type</label><br>
                          
                       <input type="radio"  onclick="get_value(this.value,1);"  name="dates[1][option1]" id="option1_1" value="%"  <?php if($tc_data['charge_type']=='%') { echo 'checked'; } else if($tc_data['charge_type']=='') { echo 'checked'; } ?> >Percentage
                         &nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" onclick="get_value(this.value,1);" name="dates[1][option1]" id="option2_1"  value="Amount" <?php if($tc_data['charge_type']=='Amount') { echo 'checked'; } ?> > Amount
                        
                        <input type="hidden" class="form-control" name="dates[1][charge_type]" id="charge_type_1" value="<?php if($tc_data['charge_type']!=''){ echo $tc_data['charge_type']; } else { echo '%'; }?>"  maxlength="10" >
                
                    <span class="error" style="color:#F00; display:none; ">This field is required</span>
                      </div>
                      <div class='col-sm-3 controls padfive'>
                      <label>Charges</label>
                      <input type="text" class="form-control numeric" name="dates[1][amount]" id="amount_1" required maxlength="10" value="<?=$tc_data['amount']?>" placeholder="Enter Charges">
                                              
                                        
                                            <span class="error" style="color:#F00; display:none; ">This field is required</span> 
                      </div>
                      <div class='col-sm-3 controls padfive'>
                      <label>Days</label>
                      <input type="text" name="dates[1][no_days]" id="days_1"
                        data-rule-minlength='2' data-rule-required='true'
                        placeholder="Number of Days" value="<?php echo $transfer_data->no_days;?>"
                        class='form-control add_pckg_elements days' required>
                      </div>
                      <div class='col-sm-1 controls add_tab padfive add_avail_dates'>
                        <br><span class="btn btn-primary"><i class="fa fa-plus"></i></span>
                      </div>
                    </div></div>

                   <?php 
               }
               ?>
                                           </div> </div>
                                            </div>

 </div>

<!--  <div class="col-md-12 col-sm-12 col-xs-12 mtp20">
                                         <div class="form-group item">
                                            <label class="control-label col-md-2 col-sm-2 col-xs-12">Charge Type</label>
                   <div class="col-md-8 col-sm-8 col-xs-12">
                                            
                                               <input type="radio"  onclick="get_value(this.value);"  name="option1" id="option1" value="%"  maxlength="10" <?php if($tc_data['charge_type']=='%') { echo 'checked'; } else if($tc_data['charge_type']=='') { echo 'checked'; } ?> >%
                                                 <input type="radio" onclick="get_value(this.value);" name="option1" id="option2"  maxlength="10" value="Amount" <?php if($tc_data['charge_type']=='Amount') { echo 'checked'; } ?> > Amount
                                                
                                                <input type="hidden" class="form-control" name="charge_type" id="charge_type" value="<?php if($tc_data['charge_type']!=''){ echo $tc_data['charge_type']; } else { echo '%'; }?>"  maxlength="10" >
                                        
                                            <span class="error" style="color:#F00; display:none; ">This field is required</span>
                                            </div>
                                            </div>

                                            </div> -->
                                           <!--   <div class="col-md-12 col-sm-12 col-xs-12 mtp20">
                                         <div class="form-group item">
                                            <label class="control-label col-md-2 col-sm-2 col-xs-12">Charges</label>
                   <div class="col-md-8 col-sm-8 col-xs-12">
                                             <input type="text" class="form-control numeric" name="amount" id="amount" required maxlength="10" value="<?=$tc_data['amount']?>" >
                                              
                                        
                                            <span class="error" style="color:#F00; display:none; ">This field is required</span>
                                            </div>
                                            </div>

                                            </div> -->

 <div class="col-md-12 col-sm-12 col-xs-12 mtp20">
                                         <div class="form-group item">
                                            <label class="control-label col-md-2 col-sm-2 col-xs-12">Description</label>
                                           
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                            <textarea class="form-control ckeditor" name="description"><?=$tc_data['description']?></textarea>                                 
                                                    <span class="error" style="color:#F00; display:none; ">This field is required</span>
                                            </div>
                                            </div>

                                            </div>
                    

                                  <div class="col-md-12 col-sm-12 col-xs-12 mtp20">
                                        <div class="form-group item">
                                            <label class="control-label col-md-2 col-sm-2 col-xs-12">&nbsp;</label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                            <button class="btn btn-success" type="submit" id="send">Save</button> 
                                            </div>
                                        </div>
                                        </div>

</form>

   </div>
  <div class="table-responsive scroll_main" style="overflow-hidden; overflow-x:scroll;">
   <table class="table table-bordered">
    <thead>
     <tr>
      <th>Sl.No</th>
      <?php $j=1; ?>
      <th class="widass"><input type='checkbox' name='alll' id='selectall<?=$j?>' onclick='checkall(<?=$j?>);'>&nbsp;&nbsp;&nbsp;<b>Select All </b>
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
      <th>Transfer Name</th>
      <th>Policy Name</th>
      <th>Seasonality From</th>
      <th>Seasonality To</th>
      <th>Charges Type</th>
      <th>Charges</th>
      <th>Number of days</th>
      <th>Description</th>
       <th>Status</th>           
     </tr>
    </thead>
    <tbody>     
     <?php
     $sn = 1;
     foreach ($transferdata as $data) 
     {
      ?>
      <tr>
       <td><?=$sn?></td>
       <td><input type='checkbox' class='interested<?=$j?>'   id='interested_<?=$j?>_<?=$sn?>' onclick="uncheck(<?=$j?>);" value="<?=$data['id']?>" /></td>
       <td class="center">
        <div class="dropdown2" role="group">
         <div class="dropdown slct_tbl pull-left sideicbb rtghu">
           <i class="fa fa-ellipsis-v"></i>  
            <ul class="dropdown-menu sidedis" style="display: none;">
        <li><a class="sideicbb1 sidedis" href="<?=base_url()?>index.php/transfers/cancellation_policy/<?=$data['id']?>" > 
         <i class="glyphicon glyphicon-pencil"></i> Edit
        </a></li>
        <li><a class="sideicbb3 sidedis" href="<?=base_url()?>index.php/transfers/cancellation_policy/<?=$data['id']?>/delete" onclick="return confirm('Are you sure to delete this record?');"> 
         <i class="glyphicon glyphicon-trash"></i> Delete
        </a></li>
      </ul>
    </div>
  </div>
       </td>
       <td><?=$this->custom_db->single_table_records('transfer_info','transfer_name',array('id'=>$data['v_id']))['data']['0']['transfer_name'];?></td>
        <td><?=$data['policy_name'];?></td>
        <td><?=date('d-M-y',strtotime($data['start_date']));?></td>
        <td><?=date('d-M-y',strtotime($data['expiry_date']));?></td>
         <td><div>
                  <?php for ($i=0; $i <count($data['charge_details']) ; $i++) { 
                    ?> 
                  <div class="row">
                    <?php 
                    if($data['charge_details'][$i]->charge_type=='%'){
                      echo 'Percentage';
                    }else if($data['charge_details'][$i]->charge_type=='Amount'){
                     echo $data['charge_details'][$i]->charge_type;
                   }      
                     ?>
                    
                  </div>
                  <?php 
                  }
                  ?>
                </div></td>
        <td><div>
                  <?php for ($i=0; $i <count($data['charge_details']) ; $i++) { ?> 
                  <div class="row">
                    <?php 
                     echo $data['charge_details'][$i]->amount;      
                     ?>
                    
                  </div>
                  <?php 
                  }
                  ?>
                </div></td>
        <td><div>
                  <?php for ($i=0; $i <count($data['charge_details']) ; $i++) { ?> 
                  <div class="row">
                    <?php 
                     echo $data['charge_details'][$i]->no_of_days;      
                     ?>
                    
                  </div>
                  <?php 
                  }
                  ?>
                </div></td> 
        <td><?=$data['description'];?></td>
        <td><?php if ($data['status'] == 1) { ?>
                                  <span style="color:green;">Active</span>
                                  <?php } else { ?>
                                  <span style="color:red;">In-Active</span>
                                <?php } ?>
     </td>
      </tr>
      <?php
      $sn++;
     }
     ?>
    </tbody>
   </table>
  </div>  
 </div>
 
</div>
<script src="<?php echo SYSTEM_RESOURCE_LIBRARY?>/ckeditor/ckeditor.js"></script>
<div class="modal fade" id="view_modal">
 <div class="modal-dialog">
  <div class="modal-content">
   <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">Modal title</h4>
   </div>
   <div class="modal-body">
    
   </div>
   <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
   </div>
  </div>
 </div>
</div>
<script type="text/javascript">
function activate(that) { window.location.href = that; }
 $(function() {
  $('.read_more').click(function(){
   var title = $(this).data('title');
   var module = $(this).data('module');
   var content = $(this).data('content');
   $('#view_modal .modal-title').html(module+' '+title);
   $('#view_modal .modal-body').html(content);
   $('#view_modal').modal('show');
  });
 });

    $(document).ready(function(){
    $('.numeric').keypress(function(event){
            console.log(event.which);
        if(event.which != 8 && isNaN(String.fromCharCode(event.which))){
            event.preventDefault();
        }
    });
      $('.add_avail_dates').on('click',function(){
      var count = $('.dates_div').length;
      var cc = count +1;
      var dates_div = '';
      if(count<5){
      var dates_div =`<div class="clearfix"><div class="dates_div">
                  <div class='col-sm-3 controls padfive'>
                  <label class="center">Charge Type</label><br>
                  <input type="radio"  onclick="get_value(this.value,${cc});" id="option1_${cc}" value="%" name="dates[${cc}][option1]" required checked>Percentage&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio"  value="Amount" onclick="get_value(this.value,${cc});" id="option2_${cc}" name="dates[${cc}][option1]"  
                     required > Amount<input type="hidden" class="form-control" name="dates[${cc}][charge_type]" id="charge_type_${cc}" value="%"   maxlength="10" >
                  </div>
                  <div class='col-sm-3 controls padfive'>
                  <label>Charges</label>
                  <input type="text" name="dates[${cc}][amount]"  id="amount_${cc}" data-rule-required='true' 
                  class="form-control numeric" maxlength="10"  required value="" placeholder="Enter Charges" data-rule-required='true'>
                     <span class="error" style="color:#F00; display:none; ">This field is required</span>  
                  </div>
                  <div class='col-sm-3 controls padfive'>
                  <label>Days</label>
                  <input type="text" name="dates[${cc}][no_days]" id="days_${cc}"
                    data-rule-minlength='2' data-rule-required='true'
                    placeholder="Number of Days" 
                    class='form-control add_pckg_elements days' required >

                  </div>
                  <div class='col-sm-2 close_bar controls padfive'><br>
                    <span class="btn btn-primary"><i class="far fa-times-circle"></i></span>
                  </div>
               </div></div>`;
    $('.available_dates').append(dates_div);

  }
  else{
    alert('Maximum Reached');
  }
    });

    $(document).on('click','.close_bar',function(){
      $(this).closest('.dates_div').remove();
    });
    });
    /*function get_country_currency(country_id)
    {
      var action = '<?=base_url();?>index.php/transfers/country_currency/'+country_id;
        $.ajax({
        type: "POST",
        url: action,
        data: "country_id="+country_id,
        success: function(data){
          $('#currency').html(data);
        }
      });
    }*/
    function get_value(charge_type,id)
    {
     $('#charge_type_'+id).val(charge_type);
    }
    function uncheck(id){
$('#selectall'+id).prop('checked', false);
   }
function checkall(id){ 
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
      var theme_tbl = 'transfer_cancellation';
      var id = 'id';
      if(checkval=='')
      {
        alert('Please Select Any Cancellation Policy!!')
        return false;
      }
      if(operation=='delete'){
     var result = confirm("Are you sure to delete?");
      if(result){
          
      }else{
        return false;
      }
    }
              var url="<?php echo base_url().'index.php/transfers/manage_transfers_all_details'; ?>" ;
              $.ajax({
                      url :url,
                      type: 'POST',
                      data: {checkval:checkval,operation:operation,theme_tbl:theme_tbl,id:id},
                      success: function(data)
                      {
                        location.reload()
                      }
                    });
}
</script>