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
  <div class="bodyContent col-md-12">
    <div class="panel panel-default clearfix">
      <div class="panel-heading">

      </div>
      <div class="panel-body">
        <h4>Search Panel</h4>
        <hr>
        <form action="<?=base_url().'report/holiday_report/'?>" method="GET" autocomplete="off"> 
          <div class="clearfix form-group">
            
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
        </div>
        <div class="col-sm-12 well well-sm">
          <button type="submit" class="btn btn-primary">Search</button>
          <a href="<?=base_url().'report/holiday_report'?>" class="btn btn-warning">Reset</a>
                   
        </div>
      </form>
      
      </div>
      <div class="clearfix"></div>
     
      <div id="tableList"  class="clearfix table-responsive">
        <div class="pull-left" style="margin-left: 10px"><?=$GLOBALS ['CI']->pagination->create_links () ?><span class="">Total <?=$total_records?> Bookings</span></div>
           <div class="clearfix"></div><br>
           <div class="pull-left" style="padding-left:1%">
             <h4> Amount Earned : <?php echo get_application_default_currency().' '.$supplier_amount_details['total_payable'];?></h4>
             <?php 
                if(isset($payment_status) && valid_array($payment_status))
                {
                  if($payment_status['status'] == SUCCESS_STATUS)
                  {
                    echo '<h4>Payment Status &nbsp; :  Payment Done at '.app_friendly_absolute_date($payment_status['data'][0]['created_datetime']).'</h4>';
                  }
                  else if($payment_status['status'] == "0")
                  {
                    echo '<h4>Payment Status &nbsp; : Payment Not Done</h4>';
                  }
                }
             ?>
           </div>
        
        <table class="table table-condensed table-bordered" id="holiday_table">
          <thead>
            <tr>
                <th>S.No</th>
                <th>Action</th>
                <th>Reservation Code</th>
                <th>Status</th>
                <th>Package Name</th>
                <th>Supplier Name</th>
                <th>Departure Date</th>
                <th>Currency</th>
                <th>Price</th>
                <th>Booked On</th>

            </tr>
          </thead>
          <tbody>
            <?php 
            //debug($table_data);exit();
            if (isset ( $table_data ) == true) {
              $segment = $GLOBALS ['CI']->uri->segment ( 4 );
              $current_record = (empty ( $segment ) ? 1 : $segment);
              foreach ( $table_data as $key => $data ) {
                 //debug($data);
                $app_reference_contact = $data['booking_details']['app_reference'];
                $papassenger_details = json_decode($data['pax_details'],true);
                $e_condition ['user_id'] = $data['booking_details']['created_by_id'];
                //debug($papassenger_details);exit();
                $intial_cancel_date = $data['booking_details']['intial_cancel_date'];
                $final_cancel_date = $data['booking_details']['final_cancel_date'];
                $user_data = $GLOBALS['CI']->user_model->get_user_uuid($e_condition);
                $postal_code = 'Data Not Available';
                if ($user_data['pin_code'] != "") {
                  $postal_code = $user_data['pin_code'];
                }
                $street_address = 'Data Not Available';
                if($user_data['address2'] != ""){
                  $street_address = $user_data['address2']; 
                }
                $state_name = 'Data Not Available';
                if($user_data['state_name'] != ""){
                  $state_name = $user_data['state_name']; 
                }
                $city_name = 'Data Not Available';
                if($user_data['city_name'] != ""){
                  $city_name = $user_data['city_name']; 
                }
                if($user_data['phone_code'] != ""){
                  $country_name = $GLOBALS ['CI']->user_model->get_country_name($user_data['phone_code']);  
                  $country_name1 = $country_name->name;
                }
                else{
                  $country_name1 = 'Data Not Available';
                }
                $action = '';
                $email = '';
                $action .= '';
                if($module_type == 'b2c'){   
                  $action = '';           
                  $action .= '<a href="#" data-toggle="modal" data-target="#myModal'.$key.'">Pax Profile</a>';
                  $action .= '<a href="#" data-toggle="modal" data-target="#package'.$key.'">Package</a>';
                  $action .= '<a href="'.base_url().'voucher/holiday/'.$data['booking_details']['app_reference'].'" >View Voucher</a>';
                  
                  $paxhtml = '<div class="modal fade" id="myModal'.$key.'" role="dialog">
                  <div class="modal-dialog">
                   <div class="modal-content">
                    <div class="modal-header">
                     <button type="button" class="close" data-dismiss="modal">&times;</button>
                     <h4 class="modal-title">Lead Traveler Information</h4>
                   </div>
                   <div class="modal-body">';
                    $user_attributes=$data['booking_details']['user_attributes'];
                    $user_attributes=json_decode($user_attributes,true);
                    $attributes=$data['booking_details']['attributes'];

                    $attributes=json_decode($attributes,true);
                     //debug($attributes);die;
                    if(($data['booking_details']['card_details'] != "") && ($data['booking_details']['status'] == "BOOKING_CONFIRMED") && ($this->payment_privilege_action == 1)){
            $customer_payment_details = json_decode($data['booking_details']['card_details'], true);
            
            $payment_customer_title = get_enum_list($customer_payment_details[0]['name_title']);
            $payment_customer_title .= " ". ucwords($customer_payment_details["cardholdername"]);
            $payment_address = $customer_payment_details["street"];
            if ($customer_payment_details["Apartment"] != "") {
              $payment_address .= ", ".$customer_payment_details["Apartment"];
            }
            $payment_address .= ", ".$customer_payment_details["city"];
            $payment_address .= ", ".$customer_payment_details["state"];
            $wdata['iso_country_code'] = $customer_payment_details["country"];
            $result = $this->db->get_where('api_country_list',$wdata)->row_array();
             // debug($result);die;
           
          }
                    //debug($attributes['phone']);exit();
                    $departure_date = $data['enquiry_details']['departure_date'] ;
                    if (!$departure_date) {
                      $departure_date = $attributes['date_of_travel'];
                    }
                    $title = get_enum_list ( 'title', $data['pax_details'][0]['pax_title'] );
                    $name=$data['pax_details'][0]['pax_first_name'];
                    $mname=$data['pax_details'][0]['pax_middle_name'];
                    $lname=$data['pax_details'][0]['pax_last_name'];
                    $phone_no = @$user_attributes['pn_country_code'].' '.$user_attributes['mobile'];
                    $min_dd = "";
                    $email = $user_attributes['email']; if (!empty($mname)) {
                      $min_dd = '<span>Middle Name : &nbsp;</span><span>'. ucwords($mname).'</span><br/>';
                    }                   
                     $paxhtml .= '
                     <span>Title : &nbsp;</span><span>'.$title.'</span><br/>
                     <span>First Name : &nbsp;</span><span>'.ucwords($name) .'</span><br/>
                     '.$min_dd.'
                     <span>Last Name : &nbsp;</span><span>'. ucwords($lname).'</span><br/>
                     <span>Mobile : &nbsp;</span><span>'.$phone_no.'</span><br/>                        
                     <span>Email : &nbsp;</span><span>'.$email.'</span><br/>
                      <span>Street Address : &nbsp;</span><span>'. $user_attributes["street_address"].'</span><br/>
                     <span>Apartment / Suite : &nbsp;</span>'.$user_attributes["address"].'<span></span><br/>
                        <span>City : &nbsp;'.$user_attributes["city"].'</span><span></span><br/>
                        <span>State/Province : &nbsp;'.$user_attributes["state"].'</span><span></span><br/>
                        <span>Country : &nbsp;'.$user_attributes["country_name"].'</span><span></span><br/>
                        <span>Postal Code : &nbsp;'.$user_attributes["zip"].'</span><span></span><br/>

                        ';
                     $paxhtml .= '</div>
                     <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                  </div>
                </div>
              </div>';
              echo $paxhtml;

               $invoice_info = '<div class="modal fade" id="myModal_invoice'.$app_reference_contact.'" role="dialog">
              <div class="modal-dialog">
               <div class="modal-content">
                <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal">&times;</button>
                 <h4 class="modal-title">Additional Information Invoice</h4>';

               $invoice_info .='<span class="remarks_msg" style="display:none;">
                  <div class="alert alert-info" >
                    <strong >Additional information added successfully.</strong>
                  </div>
                </div>
               </span>
               <div class="modal-body">';

           $invoice_info .='<input type="text" placeholder="Enter Additional Information" class="form-control"  value ="'.$data['booking_details']['additional_invoice_info'].'"  name="agent_remark" id="agent_remark_invoice'.$app_reference_contact.'" /
           ><input  type="hidden" name="remark_id" value="'.$app_reference_contact.'" id="remark_id_invoice'.$app_reference_contact.'" />';
           
            
            $invoice_info .= '</div>
            <div class="modal-footer">
             <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
             <button type="button"  id="current_'.$app_reference_contact.'"   class="btn btn-primary remark_submit_invoice">Update</button>
             
             </div>
            </div>
            </div>
          </div>';
          echo $invoice_info;


              $packagehtml = '<div class="modal fade" id="package'.$key.'" role="dialog">
              <div class="modal-dialog">
               <div class="modal-content">
                <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal">&times;</button>
                 <h4 class="modal-title">Package Details</h4>
               </div>
               <div class="modal-body">';
                $duration = $data['tours_details']['duration'];
                if($duration==1)
                { 
                  $duration = ($duration+1).'D | '.($duration).'N';
                }
                else
                { 
                  $duration = ($duration+1).' D | '.($duration).' N';
                }
                $packagehtml .= '<span>Package Name : &nbsp;</span><span>'.$data['tours_details']['package_name'].'</span><br/>
                <span>Country : &nbsp;</span><span>'.$data['tours_details']['country_name'].'</span><br/>                        
                <span>City : &nbsp;</span><span>'.implode(',',$data['tours_details']["city_name"]).'</span><br/>
                <span>Duration : &nbsp;</span><span>'.$duration.'</span><br/>
                ';
                $packagehtml .= '</div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>';
          echo $packagehtml;
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
                    
                    <td class=""><?=$data['booking_details']['app_reference']?></span></td>
                    
                    <td><span class="<?= booking_status_label ( $data['booking_details']['status'] )?>"><?=get_holiday_display_status( $data['booking_details']['status'] ) ?></span></td>
                    
                    <td class=""><?=$data['tours_details']['package_name']?></span></td>
                    
                    <td class=""><?=$data['tours_details']['supplier_name']?></span></td>
                    
                    <td><?=app_friendly_absolute_date($attributes['date_of_travel'])?></td>
                    
                    <td><?=$data['booking_details']['currency_code'] ?></td>
                    
                    <td><?=$data['booking_details']['supplier_price']?></td>
                    
                    <td><?=changeDateFormat($data['booking_details']['booked_datetime']) ?></td>
                    
                    
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
</div>
<?php 
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('js/jquery.dataTables.js'), 'defer' => 'screen');
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('js/dataTables.tableTools.js'), 'defer' => 'defer');
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


    $('#holiday_table').dataTable( {
        "oLanguage": {
        "sEmptyTable":"No records to show"
        },
         "bLengthChange" : false,
         "bInfo":false, 
        });

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

