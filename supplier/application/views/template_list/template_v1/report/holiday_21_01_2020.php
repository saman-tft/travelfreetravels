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
        B2C Tours Report
      </div>
      <div class="panel-body">
        <h4>Search Panel</h4>
        <hr>
        <form action="<?=base_url().'index.php/report/holiday/'.$module_type?>" method="GET" autocomplete="off"> 
          <div class="clearfix form-group">
            <div class="col-xs-4">
              <label> Reservation Code </label> <input type="text"
              class="form-control" name="app_reference" value="<?=@$app_reference?>" placeholder="Reservation Code">
            </div>
            <div class="col-xs-4">
              <label> Phone </label> <input type="text"
              class="form-control mobile" name="phone" value="<?=@$phone?>"
              placeholder="Phone">
            </div>
            <div class="col-xs-4">
              <label> Email </label> <input type="text" class="form-control"
              name="email" value="<?=@$email?>" placeholder="Email">
            </div>
            <div class="col-xs-4">
            <?php 
            $status_options_cus=array(
              'BOOKING_CONFIRMED'=>'CONFIRMED',
              'CANCELLED'=>'CANCELLED',
              'CANCELLATION_IN_PROCESS'=>'CANCELLATION IN PROGRESS',
              );
            ?>
              <label> Status </label> <select class="form-control" name="status">
              <option>ALL</option>
              <?=generate_options($status_options_cus, array(@$status))?>
            </select>
          </div>
          <div class="col-xs-4">
            <label> Booked From Date </label> <input type="text" readonly
            id="created_datetime_from" class="form-control"
            name="created_datetime_from" value="<?=@$created_datetime_from?>"
            placeholder="Request Date">
          </div>
          <div class="col-xs-4">
            <label> Booked To Date </label> <input type="text" readonly
            id="created_datetime_to"
            class="form-control disable-date-auto-update"
            name="created_datetime_to" value="<?=@$created_datetime_to?>"
            placeholder="Request Date">
          </div>
        </div>
        <div class="col-sm-12 well well-sm">
          <button type="submit" class="btn btn-primary">Search</button>
          <a href="<?=base_url().'report/holiday'?>" class="btn btn-warning">Reset</a>
        <!--   <button type="button" class="btn btn-primary pull-right" data-toggle="collapse" data-target="#screen_option">Screen Options</button>      -->     
        </div>
      </form>
      <div id="screen_option" class="collapse"> 
        <form action="<?=base_url()?>report/report_column_setting/holiday/<?=$module_type?>" method="post">
          <style type="text/css">
            #screen_option{border: 1px solid #ccc; border-top: 0px;padding: 0px 10px;}
            .cust_list{padding-left: 0;}
            .cust_list li{display: inline-block; margin-right: 20px;}
          </style>
          <?php 
          $GLOBALS['active_column_list']=$active_column_list;
          function check_colume($column){
            if(in_array($column, array_column($GLOBALS['active_column_list'], 'column_name'))) {
              return 'checked="checked"';
            }
          }
          ?>
          <ul class="cust_list">          
            <li><label><input type="checkbox" name="column_name[]" value="Sno" <?=check_colume('Sno')?>>Sno</label> </li>

            <li><label><input type="checkbox" name="column_name[]" value="Action" <?=check_colume('Action')?>>Action</label> </li>

            <li><label><input type="checkbox" name="column_name[]" value="Booking Reference" <?=check_colume('Booking Reference')?>> Reservation Code</label> </li>

            <li><label><input type="checkbox" name="column_name[]" value="Status" <?=check_colume('Status')?>> Status</label> </li>

            <li><label><input type="checkbox" name="column_name[]" value="Package Name" <?=check_colume('Package Name')?>>Package Name</label> </li>

            <li><label><input type="checkbox" name="column_name[]" value="Email" <?=check_colume('Phone')?>> Email</label> </li>

            <li><label><input type="checkbox" name="column_name[]" value="Phone" <?=check_colume('Phone')?>> Phone</label> </li>

           <!--  <li><label><input type="checkbox" name="column_name[]" value="API Reference" <?=check_colume('Phone')?>> API Reference Number</label> </li> -->
           
            <li><label><input type="checkbox" name="column_name[]" value="Passanger Name" <?=check_colume('Passanger Name')?>> Passanger Name</label> </li>

            <li><label><input type="checkbox" name="column_name[]" value="Departure Date" <?=check_colume('Departure Date')?>> Departure Date</label> </li>

            <li><label><input type="checkbox" name="column_name[]" value="Number Of Days" <?=check_colume('Number Of Days')?>> Number Of Days</label> </li>

             <li><label><input type="checkbox" name="column_name[]" value="Package Price" <?=check_colume('Package Price')?>> Package Price</label> </li>

<!--              <li><label><input type="checkbox" name="column_name[]" value="Admin Markup" <?=check_colume('Admin Markup')?>> Admin Markup</label> </li> -->

       <!--       <li><label><input type="checkbox" name="column_name[]" value="Convenience Fee" <?=check_colume('Convenience Fee')?>> Convenience Fee</label> </li> -->

              <li><label><input type="checkbox" name="column_name[]" value="Promocode" <?=check_colume('Promocode')?>>Promocode</label> </li>

            <li><label><input type="checkbox" name="column_name[]" value="Promocode Amount" <?=check_colume('Promocode Amount')?>>Promocode Amount</label> </li>

            <li><label><input type="checkbox" name="column_name[]" value="Total Fare" <?=check_colume('Total Fare')?>> Total Fare</label> </li>

            <li><label><input type="checkbox" name="column_name[]" value="Currency" <?=check_colume('Currency')?>>Currency</label> </li>

             <li><label><input type="checkbox" name="column_name[]" value="Payment Mode" <?=check_colume('Payment Mode')?>> Payment Mode</label> </li>

            <li><label><input type="checkbox" name="column_name[]" value="BookedOn" <?=check_colume('BookedOn')?>> BookedOn</label> </li>

            <li><label><input type="checkbox" name="column_name[]" value="Billing Type" <?=check_colume('Billing Type')?>> Billing Type</label> </li>
             
            <button class="btn btn-primary pull-right">Apply</button>
          </form>
        </div>
      </div>
      <div class="clearfix"></div>
      <?php 
      /*if (isset ( $table_data ) == true and valid_array ( $table_data ['booking_details'] ) == true) {
        $paxhtml ='';
        ?>
        <div class="row">
          <a href="<?php echo base_url(); ?>index.php/report/hotel/excel<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>">
            <button class="btn btn-primary btn-xs" type="button">Export to Excel</button>
          </a>
          <a href="<?php echo base_url(); ?>index.php/report/hotel/pdf<?= !empty($_SERVER["QUERY_STRING"])?'?'.$_SERVER["QUERY_STRING"]:''?>" target="_blank" >
            <button class="btn btn-primary btn-xs" type="button">PDF</button>
          </a>

          <button class="btn btn-primary btn-xs" type="button" onclick="window.print(); return true;">Print</button>

        </div>
        <?php 
      }*/ 
      ?>
      <div id="tableList"  class="clearfix table-responsive">
        <div class="pull-left"><?=$GLOBALS ['CI']->pagination->create_links () ?><span class="">Total <?=$total_records?> Bookings</span></div>
           <div class="clearfix"></div><br>
           <div class="pull-left">*The first passenger listed is the Lead Passenger/Pax.</span></div>
        
        <table class="table table-condensed table-bordered print-area" id="holiday_table">
          <thead>
            <tr>
              <?php
              if(check_colume('Sno')){
                ?>
                <th>Sno</th>
                <?php
              }
              if(check_colume('Action')){
                ?>
                <th>Action</th>
                <?php
              }
              if(check_colume('Booking Reference')){
                ?>
                <th>Reservation Code</th>
                <?php
              }
              if(check_colume('Status')){
                ?>
                <th>Status</th>
                <?php
              }
               if(check_colume('Package Name')){
                ?>
                <th>Package Name</th>
                <?php
              }
               if(check_colume('Email')){
                ?>
                <th>Email</th>
                <?php
              }
              
              if(check_colume('Phone')){
                ?>
                <th>Phone</th>
                <?php
              }
             // if(check_colume('API Reference')){
                ?>
              <!--   <th>API Reference Number </th> -->
                <?php
           //   }
              
              
              if(check_colume('Passanger Name')){
                ?>
                <th>Passanger Name(Last Name/First Name/Title)</th>
                <?php
              }

              if(check_colume('Departure Date')){
                ?>
                <th>Departure Date</th>
                <?php
              }
              if(check_colume('Number Of Days')){
                ?>
                <th>Number Of Days</th>
                <?php
              }
              if(check_colume('Package Price')){
                ?>
                <th>Package Price</th>
                <?php
              }
              
              if(check_colume('Promocode')){
                ?>
                <th>Promocode</th>
                <?php
              }
              if(check_colume('Promocode Amount')){
                ?>
                <th>Promocode Amount</th>
                <?php
              }
              if(check_colume('Total Fare')){
                ?>
                <th>Total<br/>Fare</th>
                <?php
              }
              if(check_colume('Currency')){
                ?>
                <th>Currency</th>
                <?php
              }
              if(check_colume('Payment Mode')){
                ?>
                <th>Payment Mode</th>
                <?php
              }
              if(check_colume('BookedOn')){
                ?>
                <th>BookedOn</th>
                <?php
              }
              if(check_colume('Billing Type')){
                ?>
                <th>Billing Type</th>
                <?php
              }
              ?>
            </tr>
          </thead>
          <tbody>
            <?php 
            if (isset ( $table_data ) == true) {
              $segment = $GLOBALS ['CI']->uri->segment ( 4 );
              $current_record = (empty ( $segment ) ? 1 : $segment);
              foreach ( $table_data as $key => $data ) {
              $book_attr = json_decode($data['booking_details']['attributes'],TRUE);
              
                $papassenger_details = json_decode($data['pax_details'],true);
                $e_condition ['user_id'] = $data['booking_details']['created_by_id'];
                //debug($data);exit();
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


                // debug( $country_name1);exit();
                $action = '';
                $email = '';
                $action .= '';
                if($module_type == 'b2c'){   
                  $action = '';           
                  $action .= '<a href="#" data-toggle="modal" data-target="#myModal'.$key.'"><i class="fal fa-user-circle"></i> Pax Profile</a>';
                  $action .= '<a href="#" data-toggle="modal" data-target="#package'.$key.'"><i class="fal fa-umbrella-beach"></i> Package</a>';
                  if ($data['booking_details']['status']=='BOOKING_CONFIRMED' || $data['booking_details']['status']=='CANCELLATION_IN_PROCESS') {
                    $action .= '<a href="'.base_url().'index.php/tours/cancel_booking/'.$data['booking_details']['app_reference'].'" onclick="return confirm(\'Do you want to cancel your booking?\')"><i class="fal fa-ban"></i> Cancel</a>';
                  }
                  $action .= '<a href="'.base_url().'index.php/voucher/holiday/'.$data['booking_details']['app_reference'].'" ><i class="fal fa-file"></i> Voucher</a>';
                  $paxhtml = '<div class="modal fade" id="myModal'.$key.'" role="dialog">
                  <div class="modal-dialog">
                   <div class="modal-content">
                    <div class="modal-header">
                     <button type="button" class="close" data-dismiss="modal">&times;</button>
                     <h4 class="modal-title">Passenger Profile</h4>
                   </div>
                   <div class="modal-body">';
                    $user_attributes=$data['booking_details']['user_attributes'];
                    // print_r($user_attributes);
                    $user_attributes=json_decode($user_attributes,true);
                    $attributes=$data['booking_details']['attributes'];

                    $attributes=json_decode($attributes,true);

                    // debug($attributes['phone']);exit();
                    $departure_date = $data['enquiry_details']['departure_date'] ;
                    if (!$departure_date) {
                      $departure_date = $attributes['departure_date'];
                    }
                    $title = get_enum_list ( 'title', $data['pax_details'][0]['pax_title'] );


                    // debug($title);exit();
                    $name=$data['pax_details'][0]['pax_first_name'];
                    $mname=$data['pax_details'][0]['pax_middle_name'];
                    $lname=$data['pax_details'][0]['pax_last_name'];
                    $phone_no = $user_attributes['passenger_contact'];
                    $email = $user_attributes['billing_email'];    
                     $paxhtml .= '
                     <span>Title : &nbsp;</span><span>'.$title.'</span><br/>
                     <span>First Name : &nbsp;</span><span>'.$name .'</span><br/>
                     <span>Last Name : &nbsp;</span><span>'. $lname.'</span><br/>
                     <span>Mobile : &nbsp;</span><span>'.$phone_no.'</span><br/>                        
                     <span>Email : &nbsp;</span><span>'.$email.'</span><br/>
                      <span>Street Address : &nbsp;</span><span>'. $user_attributes["billing_address_1"].'</span><br/>
                     <span>Apartment / Suite : &nbsp;</span>'.$user_attributes["address2"].'<span></span><br/>
                        <span>City : &nbsp;</span><span>'.$user_attributes["billing_city"].'</span><br/>
                        <span>State / Province : &nbsp;'.$user_attributes["state"].'</span><br/>
                        <span>Country : &nbsp;'.$user_attributes["country_code"].'</span><br/>
                        <span>Postal Code : &nbsp;'.$user_attributes["billing_zipcode"].'</span><span></span><br/>
                        ';
                     $paxhtml .= '</div>
                     <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                  </div>
                </div>
              </div>';
              echo $paxhtml;

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
                  $duration = ($duration).' N | '.($duration+1).' D';
                }
                else
                { 
                  $duration = ($duration).' N | '.($duration+1).' D';
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
                <tr><?php
                  if(check_colume('Sno')){
                    ?>
                    <td><?=($current_record ++)?></td>
                    <?php
                  }
                  if(check_colume('Action')){
                    ?>
                    <td><div class="btn-group dropdown_hover" role="group">
                      <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="glyphicon glyphicon-chevron-right"></span>Actions
                      </button>
                      <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                        <?=$action?>
                      </div>
                    </div></td>
                    <?php
                  }
                   if(check_colume('Booking Reference')){
                    ?>
                    <td class=""><?=$data['booking_details']['app_reference']?></span></td>
                    <?php
                  }
                  if(check_colume('Status')){
                    ?>
                    <td><span class="<?= booking_status_label ( $data['booking_details']['status'] )?>"><?=get_holiday_display_status( $data['booking_details']['status'] ) ?></span></td>
                    <?php
                  }
                   if(check_colume('Package Name')){
                    ?>
                    <td class=""><?=$data['tours_details']['package_name']?></span></td>
                    <?php
                  }
                  if(check_colume('Email')){
                    ?>
                    <td class=""><?=$book_attr['billing_email']?></span></td>
                    <?php
                  }
                 
                  if(check_colume('Phone')){
                    ?>
                    <td class=""><?=$book_attr['passenger_contact']?></span></td>
                    <?php
                  }
                 
                //  if(check_colume('API Reference')){
                    ?>
                 <!--    <td class=""></span></td> -->
                    <?php
               //   }
                  
                  if(check_colume('Passanger Name')){
                    ?>
                    <td>
                     <?php  //debug($data['pax_details']); ?>
                       <?php 
                      if($data['pax_details']){
                       foreach ($data['pax_details'] as  $value){ ?>

                        <?php

                         $title = get_enum_list('title',$value['pax_title']);
                         echo  $value['pax_last_name']." ".$value['pax_first_name']." ".$title."<br>"; ?>
                         
                      <?php }
                          
                         }
                          
                       ?> 
                    </td>



                    <?php
                  }
                  if(check_colume('Departure Date')){
                    ?>
                    <td><?=$attributes['departure_date']?></td>
                    <?php
                  }
                  if(check_colume('Number Of Days')){
                    ?>
                    <td><?=$data['tours_details']['duration']?></td>
                    <?php
                  }
                  if(check_colume('Package Price')){
                    ?>
                    <td><?=$data['booking_details']['basic_fare']+$data['booking_details']['discount']?></td>
                    <?php
                  }
                
                   if(check_colume('Promocode')){
                    ?>
                    <td><?php 
                      
                      if($data['booking_details']['promocode']){
                      echo $data['booking_details']['promocode'];
                    }else{
                      echo "NA";
                    }
                     ?>
                    </td>
                    <?php
                  }
                  if(check_colume('Promocode Amount')){
                    ?>
                    <td><?php 
                    if($data['booking_details']['discount']){
                      echo number_format($data['booking_details']['discount'],2);
                    }else{
                      echo "NA";
                    }

                    ?></td>
                    <?php
                  }

                  if(check_colume('Total Fare')){
                    ?>
                    <td><?php
                      // $total = $data['booking_details']['basic_fare']-$data['booking_details']['discount'];
                      $total = $data['booking_details']['basic_fare'];
                      echo number_format($total,2);

                     ?></td>
                    <?php
                  }
                  if(check_colume('Currency')){
                    ?>
                    <td><?=$data['booking_details']['currency_code'] ?></td>
                    <?php
                  }
                  if(check_colume('Payment Mode')){
                    ?>
                    <td><?=PAY_NOW?></td>
                    <?php
                  }
                  if(check_colume('BookedOn')){
                    ?>
                    <td><?=changeDateFormat($data['booking_details']['booked_datetime']) ?></td>
                    <?php
                  }
                   if(check_colume('Billing Type')){
                    ?>
                    <td>Online</td>
                    <?php
                  }

                  ?>
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
    $('#holiday_table').DataTable( {
      "bLengthChange" : false,
      "bInfo":false,    
    });
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

