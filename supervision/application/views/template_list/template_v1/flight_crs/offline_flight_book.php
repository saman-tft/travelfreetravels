<?php
$page_datepicker = array();
if (isset($low_balance_alert)) {
    echo $low_balance_alert;
}

?>
<div class="bodyContent col-md-12">
    <div class="panel panel-primary clearfix">
        <!-- PANEL WRAP START -->
        <div class="panel-heading"><h4>Offline Ticket Booking</h4>
            <!-- PANEL HEAD START -->
        </div>
      
        <?php if($empty==true){ ?>
        <div class="panel-body">
            <form method="POST" id="offline_booking" autocomplete="off"
                  action="<?php echo base_url() . 'index.php/flight/offline_flight_book' ?>">
                  <input type="hidden" name="fdid" value="<?=$fdid?>">
                  <input type="hidden" name="fsid" value="<?=$fsid?>">
                <div class="clearfix form-group">
                  
                    <div class="col-md-4">
                        <label>Booking Status</label> 
                        <select class="form-control" name="status" required>
                            <?= generate_options(get_enum_list('offline_booking_status_options'), array(@$status)) ?>   
                        </select>
                    </div>  
                    <div class="col-md-4 pull-right">
                        <label>&nbsp;</label> 
                        <h4 style="color:#3c763d;text-align:center;">Available Seats: <strong><?=$avail_seat?></strong></h4>                         
                    </div>  				
                </div>
                <div class="clearfix form-group">
                  
                </div>
                <h4>Passenger Contact Info</h4>
                <div class="clearfix form-group">
                    <div class="col-md-4">
                        <label>Domain User</label>  
                        <input type="text" class="form-control" id="agent" name="agent" placeholder="Domain User" value="<?= @$agent; ?>" required>
                        <input class="hide" id="agent_id_holder" name="agent_id" type="hidden" value="<?= @$agent_id; ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label>Email</label> 
                        <input type="text" class="form-control" name="passenger_email" id="passenger_email" placeholder="Email" value="<?= @$passenger_email; ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label>Mobile</label> 
                        <input type="text" class="form-control" name="passenger_phone"  id="passenger_phone" placeholder="Phone Num" value="<?= @$passenger_phone; ?>" required>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-xs-12 mt10">
                    <label class="text-success text-bold" id="agent_balance_details"></label>

                    <input type="hidden" name="hid_user_type" id="hid_user_type">
                    <input type="hidden" name="hid_available_balance" id="hid_available_balance">
                    <input type="hidden" name="hid_credit_limit" id="hid_credit_limit">
                    <input type="hidden" name="hid_due_amount" id="hid_due_amount">

                    </div>
                   <!--  <div class="col-md-4">
                       <label>Address</label> 
                       <input type="text" class="form-control" name="passenger_address" placeholder="address" value="<?= @$passenger_address; ?>" required>
                   </div> -->
                </div>
                 <div class="onward_flight_details">
                    <h4>Flight Details</h4>
                    <div class="clearfix form-group">
                        <div class="col-md-2">
                            <label>Carrier/Booking Class</label>
                        </div>
                        <div class="col-md-2">
                            <label>Flight No.</label>
                        </div>						
                        <div class="col-md-2">
                            <label>Dep. Airport/Arr. Airport</label>
                        </div>
                        <div class="col-md-2">
                            <label>Dep. Date/Arr. Date</label>
                        </div>
                        <div class="col-md-2">
                            <label>Dep. Time/Arr. Time</label> 
                        </div>
                        <div class="col-md-2">
                            <label>PNR</label> 
                        </div>
                    </div>
                    <div class="onward_flight_row" data-count="<?= @count($flight_info); ?>">
                        <?php

                        $ticket_num = $flight_info[0]['pnr'];
                        $origin_city = $flight_info[0]['origin'];
                        $destination_city = $flight_info[count($flight_info)-1]['destination'];
                       
                        for ($i = 0; $i < @count($flight_info); $i++):
                            $page_datepicker[] = array('dep_date_onward_' . $i, FUTURE_DATE);
                            $page_datepicker[] = array('arr_date_onward_' . $i, FUTURE_DATE);

                            $page_datepicker[] = array('dep_time_onward_' . $i, TIMEPICKER_24H);
                            $page_datepicker[] = array('arr_time_onward_' . $i, TIMEPICKER_24H);
                            ?>
                            <div class="clearfix form-group flt_detl">
                                <div class="col-md-2">
                                    <input type="text" class="form-control" name="career_onward[]" value="<?= @$flight_info[$i]['carrier_code']; ?>" placeholder="Carrier" required readonly>
                                    <input type="text" class="form-control" name="booking_class_onward[]" value="<?= @$flight_info[$i]['class_type']; ?>" placeholder="Booking Class" readonly>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control" name="flight_num_onward[]" value="<?= @$flight_info[$i]['flight_num']; ?>" placeholder="Flight No." readonly>								
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control" name="dep_loc_onward[]" value="<?= @$flight_info[$i]['origin']; ?>" placeholder="Dep. Airport" required readonly>
                                    <input type="text" class="form-control" name="arr_loc_onward[]" value="<?= @$flight_info[$i]['destination']; ?>" placeholder="Arr. Airport" required readonly>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control" id="adep_date_onward_<?= $i; ?>" name="dep_date_onward[]" value="<?= app_friendly_absolute_date(@$dep_date_1[0]); ?>" placeholder="Dep. Date" required readonly>
                                    <input type="text" class="form-control" id="aarr_date_onward_<?= $i; ?>" name="arr_date_onward[]" value="<?= app_friendly_absolute_date(@$dep_date_1[0]); ?>" placeholder="Arr. Date" required readonly>
                                </div>
                                <div class="col-md-2">
                                    <input type="time" class="form-control" id="dep_time_onward_<?= $i; ?>" name="dep_time_onward[]" value="<?= @$flight_info[$i]['departure_time']; ?>" placeholder="Dep. Time" required readonly>
                                    <input type="time" class="form-control" id="arr_time_onward_<?= $i; ?>" name="arr_time_onward[]" value="<?= @$flight_info[$i]['arrival_time']; ?>" placeholder="Arr. Time" required readonly>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control text-uppercase" name="gds_pnr_onward[]" value="<?= @$gds_pnr_onward[$i]; ?>" placeholder="GDS PNR" <?= @$is_lcc != 'gds' ? 'style="display:none;"' : '' ?> readonly>
                                    <input type="text" class="form-control text-uppercase" name="airline_pnr_onward[]" value="<?= @$flight_info[$i]['pnr']; ?>" placeholder="Arline PNR" readonly>

                                </div>				
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>
                <div class="return_flight_details" <?= $trip_type != 'circle' ? 'style="display:none;"' : '' ?>>
                    <h4>Return Flight Details</h4>
                    <div class="clearfix form-group">
                        <div class="col-md-2">
                            <label>Carrier/Booking Class</label>
                        </div>
                        <div class="col-md-2">
                            <label>Flight No.</label>
                        </div>						
                        <div class="col-md-2">
                            <label>Dep. Airport/Arr. Airport</label>
                        </div>
                        <div class="col-md-2">
                            <label>Dep. Date/Arr. Date</label>
                        </div>
                        <div class="col-md-2">
                            <label>Dep. Time/Arr. Time</label> 
                        </div>
                        <div class="col-md-2">
                            <label>PNR</label> 
                        </div>					
                    </div>
                    <div class="return_flight_row" data-count="<?= @$sect_num_return; ?>">
                        <?php
                        for ($i = 0; $i < @$sect_num_return; $i++):
                            $page_datepicker[] = array('dep_date_return_' . $i, FUTURE_DATE);
                            $page_datepicker[] = array('arr_date_return_' . $i, FUTURE_DATE);

                            $page_datepicker[] = array('dep_time_return_' . $i, TIMEPICKER_24H);
                            $page_datepicker[] = array('arr_time_return_' . $i, TIMEPICKER_24H);
                            ?>
                            <div class="clearfix form-group">
                                <div class="col-md-2">
                                    <input type="text" class="form-control" name="career_return[]" value="<?= @$career_return[$i]; ?>" placeholder="Carrier" required>
                                    <input type="text" class="form-control" name="booking_class_return[]" value="<?= @$booking_class_return[$i]; ?>" placeholder="Booking Class">
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control" name="flight_num_return[]" value="<?= @$flight_num_return[$i]; ?>" placeholder="Flight No.">
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control" name="dep_loc_return[]" value="<?= @$dep_loc_return[$i]; ?>" placeholder="Dep. Airport" required>
                                    <input type="text" class="form-control" name="arr_loc_return[]" value="<?= @$arr_loc_return[$i]; ?>" placeholder="Arr. Airport" required>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control" id="dep_date_return_<?= $i; ?>" name="dep_date_return[]" value="<?= app_friendly_absolute_date(@$dep_date_return[$i]); ?>" placeholder="Dep. Date" required>
                                    <input type="text" class="form-control" id="arr_date_return_<?= $i; ?>" name="arr_date_return[]" value="<?= app_friendly_absolute_date(@$arr_date_return[$i]); ?>" placeholder="Arr. Date" required>
                                </div>
                                <div class="col-md-2">
                                    <input type="time" class="form-control" id="dep_time_return_<?= $i; ?>" name="dep_time_return[]" value="<?= @$dep_time_return[$i]; ?>" placeholder="Dep. Time" required>
                                    <input type="time" class="form-control" id="arr_time_return_<?= $i; ?>" name="arr_time_return[]" value="<?= @$arr_time_return[$i]; ?>" placeholder="Arr. Time" required>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control text-uppercase" name="gds_pnr_return[]" value="<?= @$gds_pnr_return[$i]; ?>" placeholder="GDS PNR" <?= @$is_lcc != 'gds' ? 'style="display:none;"' : '' ?>>
                                    <input type="text" class="form-control text-uppercase" name="airline_pnr_return[]" value="<?= @$airline_pnr_return[$i]; ?>" placeholder="Arline PNR">
                                    <input type="hidden" class="form-control" name="date_of_birth[]" placeholder="DOB">
                                </div>
                            </div>
                        <?php endfor; ?>					
                    </div>
                </div>
                <h4>Select Passenger Details</h4>
                <div class="clearfix form-group">
                    <div class="col-md-4">
                        <label>Adults</label> 
                        <select class="form-control" id="adult_count" name="adult_count" data-price="<?=$flight_info[0]['adult_base']?>" data-type="adult" required>
                            <?= generate_options(custom_numeric_dropdown($avail_seat, 1, 1), (array) @$adult_count) ?>							
                        </select>
                    </div>
             
                    <div class="col-md-4">
                        <label>Infants</label> 
                        <select class="form-control" id="infant_count" name="infant_count" data-price="<?=$flight_info[0]['child_base']?>" data-type="infant">
                            <?= generate_options(custom_numeric_dropdown($avail_seat, 0, 1), (array) @$infant_count) ?>							
                        </select>
                    </div>
                </div>
                <div class="adult_info">
                    <h4>Adult Info</h4>
                    <div class="clearfix form-group">
                        <div class="col-md-1">
                            <label>Title</label>							
                        </div>
                        <div class="col-md-2">
                            <label>First Name <!-- <button type="button" class="btn btn-primary btn-xs fill-first-name">Fill</button> --></label>							
                        </div>
                        <div class="col-md-2">
                            <label>Last Name <!-- <button type="button" class="btn btn-primary btn-xs fill-last-name">Fill</button> --></label>							
                        </div>
             
                        <div class="col-md-2">
                            <label>Total Fare</label> 
                        </div>
                    </div>
                    
                    <div class="adult_row" data-count="<?= @$adult_count ?>">
                        <?php
                        for ($i = 0; $i < @$adult_count; $i++):
                            $page_datepicker[] = array('adult_pax_pp_expiry_' . $i, FUTURE_DATE);
                            ?>
                            <div class="clearfix form-group">
                                <div class="col-md-1">
                                    <input type="hidden" name="pax_type[]" value="Adult">
                                    <select class="form-control" name="pax_title[]">
                                        <?= generate_options(get_enum_list('title'), (array) @$pax_title[$i]) ?>														
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control text-uppercase" name="pax_first_name[]" value="<?= @$pax_first_name[$i]; ?>" placeholder="First Name" required>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control text-uppercase" name="pax_last_name[]" value="<?= @$pax_last_name[$i]; ?>" placeholder="Last Name" required>
                                </div>
                              
                                <div class="col-md-2">
                                    <input type="text" class="form-control" id="BaseFare<?= ($i - @$adult_count + @$child_count); ?>" name="pax_base_fare[]" value="<?= @$flight_info[0]['adult_base']; ?>" placeholder="BaseFare">
                                    <input type="hidden" name="pax_tax_fare[]" value="0">
                                </div>
                                <div class="col-md-1">
                                    <input type="hidden" class="form-control text-uppercase" name="pax_ticket_num_onward[]" value="<?= @$ticket_num; ?>" placeholder="Onward" <?= @$is_lcc == 'gds' ? 'required' : '' ?>>
                                    <input type="hidden" class="form-control text-uppercase" name="pax_ticket_num_return[]" value="<?= @$ticket_num; ?>" placeholder="Return" <?= @$trip_type != 'circle' ? 'style="display:none" disabled' : '' ?> <?= @$is_lcc == 'gds' ? 'required' : '' ?>>
                                    <input type="hidden" class="form-control" name="date_of_birth[]" placeholder="DOB">
                                </div>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>
              
                <div class="infant_info" <?= @$infant_count <= 0 ? 'style="display:none;"' : '' ?>>
                    <h4>Infant Info</h4>
                    <div class="clearfix form-group">
                        <div class="col-md-1">
                            <label>Title</label>							
                        </div>
                        <div class="col-md-2">
                            <label>First Name</label>							
                        </div>
                        <div class="col-md-2">
                            <label>Last Name</label>							
                        </div>
                
                        <div class="col-md-2">
                            <label>Total Fare</label> 
                        </div>
                        <div class="col-md-1">
                         <label>DOB</label> 
                                             </div>
                   
                    </div>
                    <div class="infant_row" data-count="<?= @$infant_count ?>">
                        <?php
                        for ($i = @$adult_count + @$child_count; $i < @$adult_count + @$child_count + @$infant_count; $i++):
                            $page_datepicker[] = array('infant_pax_pp_expiry_' . ($i - @$adult_count + @$child_count), FUTURE_DATE);
                             $page_datepicker[] = array('infant_DOB_' . ($i - @$adult_count + @$child_count), PAST_DATE);
                            ?>
                            <div class="clearfix form-group">
                                <div class="col-md-1">
                                    <input type="hidden" name="pax_type[]" value="Infant">
                                    <select class="form-control" name="pax_title[]">
                                        <?= generate_options(get_enum_list('title'), (array) @$pax_title[$i]) ?>														
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control text-uppercase" name="pax_first_name[]" value="<?= @$pax_first_name[$i]; ?>" placeholder="First Name" required>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control text-uppercase" name="pax_last_name[]" value="<?= @$pax_last_name[$i]; ?>" placeholder="Last Name" required>
                                </div>
                              
                                <div class="col-md-2">
                                    <input type="text" class="form-control" id="BaseFare<?= ($i - @$adult_count + @$child_count); ?>" name="pax_base_fare[]" value="<?= @$flight_info[0]['child_base']; ?>" placeholder="BaseFare">
                                    <input type="hidden" class="form-control"  name="pax_tax_fare[]" value="0">
                                </div>
                                <div class="col-md-1">
                                    <input type="hidden" class="form-control text-uppercase" name="pax_ticket_num_onward[]" value="<?= @$ticket_num; ?>" placeholder="Onward" <?= @$is_lcc == 'gds' ? 'required' : '' ?>>
                                    <input type="hidden" class="form-control text-uppercase" name="pax_ticket_num_return[]" value="<?= @$ticket_num; ?>" placeholder="Return" <?= @$trip_type != 'circle' ? 'style="display:none" disabled' : '' ?> <?= @$is_lcc == 'gds' ? 'required' : '' ?>>
                                </div>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>
            
                <h4>Grand Total</h4>
                <div class="clearfix form-group">
                    <div class="col-xs-12">
                        <button type="button" class="btn btn-primary btn-xs tot-calc">Calculate</button>					
                    </div>
                </div>
                <div class="clearfix form-group">
                    <div class="col-md-4">
                        <label>Grand Total</label>
                        <input type="hidden" class="form-control" name="origin_city"  value="<?= @($origin_city); ?>"> 
                        <input type="hidden" class="form-control" name="destination_city" value="<?= @($destination_city); ?>"> 
                        <input type="hidden" class="form-control" name="adt_base_fare" id="adt_base_fare" value="<?= intval(@($flight_info[0]['adult_base'])); ?>"> 
                        <input type="hidden" class="form-control" name="inf_base_fare" id="inf_base_fare" value="<?= intval(@($flight_info[0]['child_base'])); ?>"> 
                        <input type="hidden" class="form-control" name="adt_tax_fare" id="adt_tax_fare" value="<?= intval(@($flight_info[0]['adult_tax'])); ?>"> 
                        <input type="hidden" class="form-control" name="inf_tax_fare" id="inf_tax_fare" value="<?= intval(@($flight_info[0]['child_tax'])); ?>"> 
                        <input type="hidden" class="form-control" name="api_total_basic_fare"  value="<?= intval(@($flight_info[0]['adult_base'])); ?>" placeholder="Basefare">
                        <input type="text" class="form-control" name="total_basic_fare"  value="<?= intval(@($flight_info[0]['adult_base'])); ?>" placeholder="Basefare">
                    </div>
                     <input type="hidden" class="form-control" name="api_total_tax" value="0" placeholder="Other Taxes & Charges">
          
                <div class="clearfix form-group">
           
                     <input type="hidden" class="form-control" name="api_total_selling_price" value="<?= intval(@$flight_info[0]['adult_base']+$flight_info[0]['adult_tax']); ?>" placeholder="Total Fare">
          
                </div>
                <div class="clearfix form-group">
                    <div class="col-xs-12">
                        <button type="submit" name="save" id="form_submit" class="btn btn-primary">Save</button>
                        <button type="reset" class="btn btn-default">Reset</button>
                    </div>
                </div>

            </form>
        </div>
        <?php } ?>
    </div>
</div>

<?php
$this->current_page->set_datepicker($page_datepicker);
?>
<script>
    $(document).ready(function () {
/*
        $("#form_submit").on('click',function(){
  
          });
*/

		load();
	   var nomonths = 1;
       var dformat  = 'dd-mm-yy';
       $("#dep_date1").datepicker({
           minDate: 0,
           numberOfMonths: nomonths,
           dateFormat:dformat
		});
		function load(){
			var type ='<?$stop_list?>';
            var count =1;
            if (count > 0) {
                $('.' + type + '_flight_details').show();
            } else {
                $('.' + type + '_flight_details').hide();
            }
            //alert(type+count)
            get_flight_row(type, count);
			}
		/*$("#form_submit").on('click',function(){
			if($('.getAiportlist').val()==''){
				alert("Choose the Airport List");return false;
				}
			if($('#dep_date1').val()==''){
				$('#dep_date1').focus();return false;
				}
		    
		    $.get(app_base_url + "index.php/flight/get_offline_flight/",$('#offline_booking_start').serialize(), function (data, status, xhr) {
				
				});
				$('.check_validation').removeClass('hide');
		    if($('.check_validation').val()!=''){
				$("#offline_booking_start").submit();
				}
		  })*/
		$(".getAiportlist").autocomplete({
         source: app_base_url+"index.php/flight/get_flight_suggestions",
         minLength: 2,//search after two characters
         autoFocus: true, // first item will automatically be focused
         select: function(event,ui){
             //var inputs = $(this).closest('form').find(':input:visible');
             //inputs.eq( inputs.index(this)+ 1 ).focus();
         }
     });
        
        $('[name="trip_type"]').on('change', function () {
            if ($(this).val() === 'circle') {
                $('#sect_num_return').prop("disabled", false);
                $('.trip_circle').show().find('[name]').prop("disabled", false);
                $('.return_flight_details').show().find('[name]').prop("disabled", false);
                $('[name="pax_ticket_num_return[]"]').show().prop("disabled", false);
                //update number of sectors to 1
                show_default_return_segments();
            } else {
                $('#sect_num_return').prop("disabled", true);
                $('.trip_circle').hide().find('[name]').prop("disabled", true);
                $('.return_flight_details').hide().find('[name]').prop("disabled", true);
                $('[name="pax_ticket_num_return[]"]').hide().prop("disabled", true);
            }
        });
   

        /**
         *
         */
        function show_default_return_segments()
        {
            var return_seg = $('#sect_num_return');
            var default_segs = parseInt(return_seg.val());
            if (default_segs < 1) {
                var count = 1;
                var type = return_seg.data('type');
                return_seg.val(count);
                return_seg.trigger('change');
                //get_flight_row(type,count);
            }
        }
        $('[name="is_lcc"]').on('change', function () {
            if ($(this).val() === 'gds') {
                $('[name="gds_pnr_onward[]"],[name="gds_pnr_return[]"]').show().prop("disabled", false);
                $('[name="pax_ticket_num_onward[]"],[name="pax_ticket_num_return[]"]').prop("required", true);
            } else {
                $('[name="gds_pnr_onward[]"],[name="gds_pnr_return[]"]').hide().prop("disabled", true);
                $('[name="pax_ticket_num_onward[]"],[name="pax_ticket_num_return[]"]').prop("required", false);
            }
        });
        $('#sect_num_onward,#sect_num_return').on('change', function () {
            var type ='<?$stop_list?>';
            var count =0;
            if (count > 0) {
                $('.' + type + '_flight_details').show();
            } else {
                $('.' + type + '_flight_details').hide();
            }
            //alert(type+count)
            get_flight_row(type, count);
        });
        $('#child_count,#adult_count,#infant_count').on('change', function () {
            var type = $(this).data('type');
            var price = $(this).data('price');
            var count = parseInt($(this).val());
            if (count > 0) {
                $('.' + type + '_info').show();
                $('.pax_fare_' + type).show().find('[name="pax_type_count_onward[]"],[name="pax_type_count_return[]"]').val(count);
            } else {
                $('.' + type + '_info').hide();
                $('.pax_fare_' + type).hide().find('[name="pax_type_count_onward[]"],[name="pax_type_count_return[]"]').val(0);
            }
            get_pax_row(type, count,price);
        });
        $('.pax-fare-row').on('change', '[name^="pax_basic_fare_"],[name^="pax_yq_"],[name^="pax_other_tax_"],[name="pax_type_count_"]', function () {
            var $cont = $(this).closest('.pax-fare-row');
            var tot = 0;
            var basic = $cont.find('[name^="pax_basic_fare_"]').val();
            var yq = $cont.find('[name^="pax_yq_"]').val();
            var tax = $cont.find('[name^="pax_other_tax_"]').val();
            var count = $cont.find('[name^="pax_type_count_"]').val();
            tot = ((basic ? parseInt(basic) : 0) + (yq ? parseInt(yq) : 0) + (tax ? parseInt(tax) : 0)) * parseInt(count);
            $cont.find('[name^="pax_total_fare_"]').val(tot);
        });
        $('.tot-calc').on('click', function () {
            $.post(app_base_url + "index.php/flight/offline_fare_calculate/", $('#offline_booking').serialize(), function (data, status, xhr) {
                data = $.parseJSON(data);
                $.each(data, function (key, value) {
                    $('[name="' + key + '"]').val(value);
                });
                //alert($("#agent_buying_price").attr('max'));
               // if (parseInt($("#agent_buying_price").attr('max')) < parseInt(data.agent_buying_price))
                //    alert('Insufficient balance');
            });
        });
        $('.comm-auto-fill').on('click', function () {
            //var agent_id = $('#agent_id_holder').val();
            auto_fill_commission_data();
        });
        $('.fill-first-name').on('click', function () {
            var name = $('[name="pax_first_name[]"]').first().val();
            if (name == '')
                name = 'TBA';
            $('[name="pax_first_name[]"]').val(name);
        });
        $('.fill-last-name').on('click', function () {
            var name = $('[name="pax_last_name[]"]').first().val();
            if (name == '')
                name = 'TBA';
            $('[name="pax_last_name[]"]').val(name);
        });
        $('#suplier_id').on('change', function () {
            if($(this).val()=="PTBSID0000000009")
            {
                $('.supplier_name').show();
               $("#supplier_name").prop('disabled', false);
            }
            else 
            {
                  $('.supplier_name').hide();
                  $("#supplier_name").prop('disabled', true);
            }
         });
        $("#agent").autocomplete({
            source: function (request, response) {

                var term = request.term;
                var search_key = term;
                var cache = {};
                if (search_key in cache) {
                    response(cache[search_key]);
                    return
                } else {
                    $.getJSON(app_base_url + "index.php/ajax/get_all_domain_list", request, function (data, status, xhr) {

                        if ($.isEmptyObject(data) == true && $.isEmptyObject(cache[""]) == false) {
                            data = cache[""]
                        } else {
                            cache[search_key] = data;
                            response(cache[search_key])
                        }
                    })
                }
            },
            minLength: 0,
            autoFocus: true,
            select: function (event, ui) {
                var label = ui.item.label;
            
                $(this).siblings('#agent_id_holder').val(ui.item.id);

                $("#passenger_email").val(ui.item.email);
                $("#passenger_phone").val(ui.item.phone);
                $("#agent_buying_price").attr('max', ui.item.balance);
                // $("#agent").val(label);	
                var str = '';	
                if(ui.item.user_type==3){
                    var str = 'Available Balance : '+ ui.item.balance +', Credit Limit : '+ui.item.credit_limit+', Due Amount : '+ui.item.due_amount;
                    $('#hid_available_balance').val(ui.item.balance);
                    $('#hid_credit_limit').val(ui.item.credit_limit);
                    $('#hid_due_amount').val(ui.item.due_amount);
                }else{
                    var str = 'Available Balance : '+ ui.item.balance ;
                }
                $('#hid_user_type').val(ui.item.user_type);
                auto_fill_passenger_data(str);
            },
            change: function (ev, ui) {
                if (!ui.item) {
                    $(this).val("")
                }
            }
        }).bind('focus', function () {
            $(this).autocomplete("search");
        }).autocomplete("instance")._renderItem = function (ul, item) {
            var auto_suggest_value = highlight_search_text(this.term.trim(), item.value, item.label);

            return $("<li class='custom-auto-complete'>").append('<a>' + auto_suggest_value + '</a>').appendTo(ul)
        };
        function get_flight_row(type, count) {
            //alert(type+count)
            var $_row = $('.' + type + '_flight_row');
            var _c = parseInt($_row.data('count'));
				if (_c < count) {
					for(_c;_c<count;_c++){
					$.get(app_base_url + "index.php/flight/get_offline_flight_row/" + type, {'is_lcc': $('[name="is_lcc"]').val()}, function (data, status, xhr) {
						if ($.trim(data) != '') {
							while (++_c <= count) {
								$_row.append(data);
								$_row.find('[name="dep_date_' + type + '[]"]').last().attr('id', 'dep_date_' + type + '_' + _c);
								futureDatepicker('dep_date_' + type + '_' + _c);
								$_row.find('[name="arr_date_' + type + '[]"]').last().attr('id', 'arr_date_' + type + '_' + _c);
								futureDatepicker('arr_date_' + type + '_' + _c);

								$_row.find('[name="dep_time_' + type + '[]"]').last().attr('id', 'dep_time_' + type + '_' + _c);
								timePicker24('dep_time_' + type + '_' + _c);
								$_row.find('[name="arr_time_' + type + '[]"]').last().attr('id', 'arr_time_' + type + '_' + _c);
								timePicker24('arr_time_' + type + '_' + _c);
							}
						}
					});
				}
				} else if (_c > count) {
					while (--_c >= count)
						$_row.find('> .form-group').last().remove();
				}
			
            $_row.data('count', count)
        }
        function get_pax_row(type, count,price) {
            //alert(type+count)
            var $_row = $('.' + type + '_row');
            var _c = parseInt($_row.data('count'));
            //alert(_c);
            if (_c < count) {
                $.get(app_base_url + "index.php/flight/get_offline_pax_row/" + type + "/" + price, {'trip_type': $('[name="trip_type"]:checked').val(), 'c_type': $('[name="is_lcc"]').val()}, function (data, status, xhr) {
                    if ($.trim(data) != '') {
                        while (++_c <= count) {
                            $_row.append(data);
                            $_row.find('[name="pax_pp_expiry[]"]').last().attr('id', type + '_pax_pp_expiry_' + _c);
                            futureDatepicker(type + '_pax_pp_expiry_' + _c);

                            $_row.find('[name="date_of_birth[]"]').last().attr('id', type + '_date_of_birth_' + _c);

                            pastDatepicker(type + '_date_of_birth_' + _c);
                        }
                    }
                });
            } else if (_c > count) {
                while (--_c >= count)
                    $_row.find('> .form-group').last().remove();
            }
            $_row.data('count', count);
        }
        function auto_fill_passenger_data(data) {
            // console.log(data);
            $("#agent_balance_details").html(data);
         /*   $.each(data.details, function (key, value) {
                $('[name="' + key + '"]').val(value);
            }); */
        }
        function auto_fill_commission_data() {
            $.post(app_base_url + "index.php/flight/get_current_commission_details/", $('#offline_booking').serialize(), function (data, status, xhr) {
                data = $.parseJSON(data);
                $.each(data, function (key, value) {
                    $('[name="' + key + '"]').val(value);
                });
            });

        }
/*
        $('#adult_count,#infant_count').on('change', function () {
          change_total_price();
        });

        $('[name="pax_base_fare"]').on('change', function () {
          change_total_price();
        });
        function change_total_price(){
            
            $('input[name="pax_base_fare[]"]').each(function() {
              //  alert($(this).val());
            });
        }*/
    });
</script>
