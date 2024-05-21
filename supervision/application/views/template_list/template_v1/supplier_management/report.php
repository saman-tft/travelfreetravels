<?php
if (is_array ( $search_params )) {
  extract ( $search_params );
}

$_datepicker = array (
  array (
    'created_datetime_from',
    PAST_DATE 
    ),
  array (
    'created_datetime_to',
    PAST_DATE 
    ) 
  );
$this->current_page->set_datepicker ( $_datepicker );
$this->current_page->auto_adjust_datepicker ( array (
  array (
    'created_datetime_from',
    'created_datetime_to' 
    ) 
  ) );
  ?>
  <?=$GLOBALS['CI']->template->isolated_view('report/email_popup')?>
    <script src="<?php echo SYSTEM_RESOURCE_LIBRARY ?>/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo SYSTEM_RESOURCE_LIBRARY ?>/datatables/dataTables.bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">


  <div class="bodyContent col-md-12">
    <div class="panel panel-default clearfix">
      <div class="panel-heading">
      </div>
      <div class="panel-body">
        <h4>Search Panel</h4>
        <hr>
        <form action="<?=base_url().'supplier_management/report/'?>" method="GET" autocomplete="off"> 
          <div class="clearfix form-group">
              
              
            <div class="col-xs-4">
            
              <label> Supplier </label> 
              <select class="form-control" name="supplier_name">
              <option>ALL</option>
                <?php
                  foreach($supplier_list as $supplier)
                  {
                    if($supplier_id == $supplier['user_id'])
                    {
                      echo '<option value="'.$supplier['user_id'].'" selected>'.$supplier['first_name'].' '.$supplier['last_name'].'</option>';
                    }
                    else
                    {
                      echo '<option value="'.$supplier['user_id'].'">'.$supplier['first_name'].' '.$supplier['last_name'].'</option>';
                    }
                    
                  }
                ?>
            </select>
          </div>
          <div class="col-xs-4">
            <label> Month </label> 
            <select class="form-control" name="month">
              <option>All</option>
              <?php
                for($i = 1; $i <= 12; $i++)
                {
                  if($month == $i)
                  {
                   echo '<option value="'.$i.'" selected>'.date('F', strtotime('2020-'.$i.'-01')).'</option>';
                  }
                  else
                  {
                    echo '<option value="'.$i.'">'.date('F', strtotime('2020-'.$i.'-01')).'</option>';
                  }
                }

              ?>
            </select>

           <!--  <input type="text" readonly
            id="created_datetime_from" class="form-control"
            name="created_datetime_from" value="<?=@$created_datetime_from?>"
            placeholder="Request Date"> -->
          </div>
          <div class="col-xs-4">
            <label> Year </label> 
            <select class="form-control" name="year">
              <option>All</option>
              <?php
                for($i = 2020; $i <= date('Y'); $i++)
                {
                  if($year == $i)
                  {
                    echo '<option value="'.$i.'" selected>'.$i.'</option>';
                  }
                 else
                 {
                   echo '<option value="'.$i.'">'.$i.'</option>';
                 }
                }

              ?>
            </select>
            <!-- <input type="text" readonly
            id="created_datetime_to"
            class="form-control disable-date-auto-update"
            name="created_datetime_to" value="<?=@$created_datetime_to?>"
            placeholder="Request Date"> -->
          </div>
             <div class="col-xs-4">
            <label> Module </label> 
            <select class="form-control" name="module">
              <option>All</option>
              <option value="Tour" >Tour</option>
            <option value="hotel" >hotel</option>
              <option value="Transfer" >Transfer</option>
               <option value="Activities" >Activities</option>
                
            </select>
            <!-- <input type="text" readonly
            id="created_datetime_to"
            class="form-control disable-date-auto-update"
            name="created_datetime_to" value="<?=@$created_datetime_to?>"
            placeholder="Request Date"> -->
          </div>
        </div>
        <div class="col-sm-12 well well-sm">
          <button type="submit" class="btn btn-primary">Search</button>
          <a href="<?=base_url().'supplier_management/report'?>" class="btn btn-warning">Reset</a>
                  
        </div>
      </form>
      
      </div>
      <div class="clearfix"></div>
     
      <div id="tableList"  class="clearfix table-responsive">
        <div class="pull-left"><?=$GLOBALS ['CI']->pagination->create_links () ?><span class="">Total <?=$total_records?> Bookings</span></div>
           <div class="clearfix"></div><br>
           
        
        <table class="table table-condensed table-bordered" id="holiday_table">
          <thead>
            <tr>
                <th>S.No</th>
                <th>Action</th>
                 <th>Module</th>
                <th>Supplier Code</th>
                <th>Supplier Name</th>
                <th>Email</th>
               
                <th>Price</th>
             

            </tr>
          </thead>
          <tbody>
            <?php 
            
             $current_record =1;
            
            
            if (isset ( $table_data ) == true) {
              $segment = $GLOBALS ['CI']->uri->segment ( 4 );
             
              foreach ( $table_data as $key => $data ) 
              {
                $action = '';
                $email = '';
                $action .= '';
                if($module_type == 'b2c'){   
                  $action = '';           
                  /*$action .= '<a href="#" data-toggle="modal" data-target="#myModal'.$key.'">Pax Profile</a>';
                  $action .= '<a href="#" data-toggle="modal" data-target="#package'.$key.'">Package</a>';*/
                  $action .= '<a target="_blank" href="'.base_url().'supplier_management/supplier_report/?supplier_name='.$data['supplier_id'].'&month='.$month.'&year='.$year.'" >View Report</a>';
                ?>
                <tr>
                    
                    
                    <td><?=($current_record ++)?></td>
                    
                    <td><div class="btn-group dropdown_hover" role="group">
                      <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="glyphicon glyphicon-chevron-right"></span>Actions
                      </button>
                      <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                        <?=$action?>
                      </div>
                    </div></td>
                      <td class=""><?='Tours'?></span></td>
                    <td class=""><?=provab_decrypt($data['uuid'])?></span></td>
                    
                    
                    <td class=""><?=$data['first_name'].' '.$data['last_name']?></span></td>
                    <td class=""><?=provab_decrypt($data['email'])?></span></td>
                   
                   
                     <td><?=$data['total_supplier_price']?></td>
                   
                    
                    
                </tr>
                <?php
              }
            }
          }
            ?>
               <?php 
            //debug($table_data);exit();
            if (isset ( $Htable_data ) == true) {
              $segment = $GLOBALS ['CI']->uri->segment ( 4 );
             // $current_record = (empty ( $segment ) ? 1 : $segment);
              foreach ( $Htable_data as $key => $data ) 
              {
                $action = '';
                $email = '';
                $action .= '';
                if($module_type == 'b2c'){   
                  $action = '';           
                  /*$action .= '<a href="#" data-toggle="modal" data-target="#myModal'.$key.'">Pax Profile</a>';
                  $action .= '<a href="#" data-toggle="modal" data-target="#package'.$key.'">Package</a>';*/
                  $action .= '<a target="_blank" href="'.base_url().'supplier_management/hotel_supplier_report/?supplier_name='.$data['supplier_id'].'&month='.$month.'&year='.$year.'" >View Report</a>';
                ?>
                <tr>
                    <td><?=($current_record ++)?></td>
                    
                    <td><div class="btn-group dropdown_hover" role="group">
                      <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="glyphicon glyphicon-chevron-right"></span>Actions
                      </button>
                      <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                        <?=$action?>
                      </div>
                    </div></td>
                      <td class=""><?='Hotel'?></span></td>
                    <td class=""><?=provab_decrypt($data['uuid'])?></span></td>
                    
                    
                    <td class=""><?=$data['first_name'].' '.$data['last_name']?></span></td>
                    <td class=""><?=provab_decrypt($data['email'])?></span></td>
                    <td><?=$data['total_supplier_price']?></td>
                    
                    
                </tr>
                <?php
              }
            }
          }
            ?>
                   <?php 
            //debug($table_data);exit();
            if (isset ( $Ttable_data ) == true) {
              $segment = $GLOBALS ['CI']->uri->segment ( 4 );
             // $current_record = (empty ( $segment ) ? 1 : $segment);
              foreach ( $Ttable_data as $key => $data ) 
              {
                $action = '';
                $email = '';
                $action .= '';
                if($module_type == 'b2c'){   
                  $action = '';           
                  /*$action .= '<a href="#" data-toggle="modal" data-target="#myModal'.$key.'">Pax Profile</a>';
                  $action .= '<a href="#" data-toggle="modal" data-target="#package'.$key.'">Package</a>';*/
                  $action .= '<a target="_blank" href="'.base_url().'supplier_management/transfer_supplier_report/?supplier_name='.$data['supplier_id'].'&month='.$month.'&year='.$year.'" >View Report</a>';
                ?>
                <tr>
                    <td><?=($current_record ++)?></td>
                    
                    <td><div class="btn-group dropdown_hover" role="group">
                      <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="glyphicon glyphicon-chevron-right"></span>Actions
                      </button>
                      <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                        <?=$action?>
                      </div>
                    </div></td>
                      <td class=""><?='Transfers'?></span></td>
                    <td class=""><?=provab_decrypt($data['uuid'])?></span></td>
                    
                    
                    <td class=""><?=$data['first_name'].' '.$data['last_name']?></span></td>
                    <td class=""><?=provab_decrypt($data['email'])?></span></td>
                  
                    <td><?=$data['total_supplier_price']?></td>
                    
                    
                </tr>
                <?php
              }
            }
          }
            ?>
              <?php 
              
         
            if (isset( $Atable_data[0]['origin'])==true) {
              $segment = $GLOBALS ['CI']->uri->segment ( 4 );
           //   $current_record = (empty ( $segment ) ? 1 : $segment);
              foreach ( $Atable_data as $key => $data ) 
              {
                $action = '';
                $email = '';
                $action .= '';
                if($module_type == 'b2c'){   
                  $action = '';           
                  /*$action .= '<a href="#" data-toggle="modal" data-target="#myModal'.$key.'">Pax Profile</a>';
                  $action .= '<a href="#" data-toggle="modal" data-target="#package'.$key.'">Package</a>';*/
                  $action .= '<a target="_blank" href="'.base_url().'supplier_management/activities_supplier_report/?supplier_name='.$data['supplier_id'].'&month='.$month.'&year='.$year.'" >View Report</a>';
                ?>
                <tr>
                    <td><?=($current_record ++)?></td>
                    
                    <td><div class="btn-group dropdown_hover" role="group">
                      <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="glyphicon glyphicon-chevron-right"></span>Actions
                      </button>
                      <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                        <?=$action?>
                      </div>
                    </div></td>
                      <td class=""><?='Activities'?></span></td>
                    <td class=""><?=provab_decrypt($data['uuid'])?></span></td>
                    
                    
                    <td class=""><?=$data['first_name'].' '.$data['last_name']?></span></td>
                    <td class=""><?=provab_decrypt($data['email'])?></span></td>
                    
                    <td><?=$data['total_supplier_price']?></td>
                    
                    
                </tr>
                <?php
              }
            }
          }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

<?php 
//Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('js/jquery.dataTables.js'), 'defer' => 'screen');
//Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('js/dataTables.tableTools.js'), 'defer' => 'defer');
 ?>
<script>
  $(document).ready(function() {
    // $('#holiday_table').DataTable( {
    //   "bLengthChange" : false,
    //   "bInfo":false,    
    // });

   $('.remark_submit').click(function(){
     var remark = this.id.split("_");
     var r_id = remark[1];
     var remark_text_id = "#agent_remark_"+r_id;
     var remark_text_id_emergency = "#agent_remark_emergency_"+r_id; 
     var remark_text = $(remark_text_id).val();
     var emergency = $(remark_text_id_emergency).val();
     $.ajax({
            url:app_base_url+'tours/update_contact_info',
            type:'POST',
            data:{'r_id':r_id,'agent_remark':remark_text,'emergency_contact':emergency},
            dataType: "json",
            success:function(ret){
             if(ret==true){
             
             setTimeout(function(){
             $('.remarks_msg').show();
             window.location.reload();
              }, 1000);
             } },
            error:function(){
            }
           }) ;
  });

      $('.remark_submit_invoice').click(function(){
     var remark = this.id.split("_");
     var r_id = remark[1];
     var remark_text_id = "#agent_remark_invoice"+r_id;
     var remark_text = $(remark_text_id).val();
     $.ajax({
            url:app_base_url+'tours/update_invoice_info',
            type:'POST',
            data:{'r_id':r_id,'agent_remark':remark_text},
            dataType: "json",
            success:function(ret){
             if(ret==true){
             
             setTimeout(function(){
             $('.remarks_msg').show();
             window.location.reload();
              }, 1000);
             } },
            error:function(){
            }
           }) ;
  });
$('#holiday_table').DataTable({
                dom: 'Bfrtip',
                buttons:  [
            {
                extend: 'copyHtml5',
                exportOptions: {
                    columns: [ 0,1,2,3,4,5,6,7,8,9]
                    ,
                }
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7,8,9],
                   
                }
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [0,1,2,3,4,5,6,7,8,9],
                   
                }
            }
        ]
            });
       
  /*  $('#holiday_table').dataTable( {
        "oLanguage": {
        "sEmptyTable":"No records to show"
        },
         "bLengthChange" : false,
         "bInfo":false, 
        });*/

        $(".dataTables_empty").attr('colspan',0);

    $('.send_email_voucher').on('click', function(e) {
      $("#mail_voucher_modal").modal('show');
      $('#mail_voucher_error_message').empty();
      email = $(this).data('recipient_email');
      $("#voucher_recipient_email").val(email);
      app_reference = $(this).data('app-reference');
      book_reference = $(this).data('booking-source');
      app_status = $(this).data('app-status');
      $("#send_mail_btn").off('click').on('click',function(e){
        email = $("#voucher_recipient_email").val();
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        if(email != ''){ 
          if(!emailReg.test(email)){
            $('#mail_voucher_error_message').empty().text('Please Enter Correct Email Id');
            return false;    
          }         
          var _opp_url = app_base_url+'index.php/voucher/hotel/';
          _opp_url = _opp_url+app_reference+'/'+book_reference+'/'+app_status+'/email_voucher/'+email;
          toastr.info('Please Wait!!!');
          $.get(_opp_url, function() {              
            toastr.info('Email sent  Successfully!!!');
            $("#mail_voucher_modal").modal('hide');
          });
        }else{
          $('#mail_voucher_error_message').empty().text('Please Enter Email ID');
        }       
      });  
    });
  });
</script>

