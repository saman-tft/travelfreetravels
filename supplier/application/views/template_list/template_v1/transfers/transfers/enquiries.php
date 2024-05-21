<div id="enquiries" class="bodyContent col-md-12">
    <div class="panel panel-default">
        <!-- PANEL WRAP START -->
        <div class="panel-heading">
            <!-- PANEL HEAD START -->
            <div class="panel-title">
                <ul class="nav nav-tabs nav-justified" role="tablist" id="myTab">
                    <!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->
                    <li role="presentation" class="active">
                        <a href="#fromList"
                                                              aria-controls="home" role="tab" data-toggle="tab"><h1>View
                                Enquiries</h1></a></li>
                    <!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE END -->
                </ul>
            </div>
        </div>
        <!-- PANEL HEAD START -->
        <div class="panel-body">
            <!-- PANEL BODY START -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="fromList">
                    <div class="col-md-12 nopad">
                        <div class='row'>

                            <div class='row'>
                                <div class="col-sm-12 nopad">
                                    <div class='' style='margin-bottom: 0;'>
                                        <div class=''>
                                            <div class='table-responsive'>
                                                <div class='scrollable-area'>
                                                    <table
                                                            class='data-table-column-filter table table-bordered table-striped'
                                                            style='margin-bottom: 0;'>
                                                        <thead>
                                                        <tr>
                                                            <th >S.No</th>
                                                            <th >Enq No</th>
                                                            <th >Name</th>
                                                            <th >Email</th>
                                                            <th >Contact</th>
                                                            <th >Activity Name</th>
                                                            <th >Status</th>
                                                            <th >Date</th>
                                                            <th >Action</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php

                                                        if (! empty ( $enquiries )) {
                                                            $count = 1;
//                                                            print_r($enquiries);
                                                            foreach ( $enquiries as $key => $package ) {
                                                                ?>
                                                                <tr>
                                                                    <td><?php echo $count; ?></td>
                                                                    <td><?php echo $package->enquiry_reference_no; ?></td>
                                                                    <td><?php echo $package->first_name; ?></td>
                                                                    <td><?php echo $package->email; ?></td>
                                                                    <td><?php echo $package->phone; ?></td>
                                                                    <td style="word-wrap: break-word;min-width: 160px;max-width: 160px; white-space:normal;"><?php echo $package->package_name; ?></td>
                                                                    <td><?php
                                                                        if ($package->enquiry_status == 1) {
                                                                            echo '<span class="label label-success"><i class="fa fa-check"></i> Read</span>';
                                                                        } else {
                                                                            echo '<span class="label label-danger">Unread</span>     <a role="button" href="' . base_url () . 'index.php/supplier/read_enquiry/' . $package->id . '" >Read</a>';
                                                                        }
                                                                        ?></td>
                                                                    <td><?php echo $package->date; ?></td>
                                                                    <td class="center">

<!--                                                                        <a href="--><?php //echo base_url(); ?><!--supplier/enquiry_detail/--><?php //echo $package->id; ?><!--/--><?php //echo $package->package_id; ?><!--"                                            data-original-title="enq_det" data-toggle="modal" data-target="#myModal--><?//=$key?><!--" class="btn btn-default btn-xs has-tooltip" data-original-title="enq_det"> <i class="icon-remove">Enquiry Details</i>-->
<!--                                                                        </a>-->
                                                                        <button type="button" class="btn btn-default btn-xs has-tooltip" data-toggle="modal" data-target="#myModal<?=$key?>">Enquiry Details</button>
                                                                        <div class="modal fade" id="myModal<?=$key?>" role="dialog">
                                                                            <div class="modal-dialog">

                                                                                <!-- zModal content-->
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header">
                                                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                        <h4 class="modal-title">Enquiry Details</h4>
                                                                                    </div>
                                                                                    <div class="modal-body">
                                                                                        <h5 class="modal-title"><b>Passenger Profile</b></h5>
                                                                                        <span>Name : &nbsp;</span><span><?php echo $package->first_name; ?></span><br/>

                                                                                        <span> Email : &nbsp;</span><span><?php echo $package->email; ?></span><br/>
                                                                                        <span> Phone : &nbsp;</span><span><?php echo $package->phone; ?></span><br/>
                                                                                        <h5 class="modal-title"><b>Enquiry Details</b></h5>
                                                                                        <span>No. of Passengers : &nbsp;</span><span><?php echo $package->pax; ?></span><br/>
                                                                                        <span style="float: left;display: inline-block;">Message : &nbsp;</span><span  style="float: left;display: inline-block;     white-space: normal;"><?php echo $package->message; ?></span><br/>

                                                                                    </div>
                                                                                    <div class="modal-footer" style="clear: both;">
                                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                    </div>
                                                                                </div>

                                                                            </div>
                                                                        </div>
                                                                    </br>
                                                                        <button type="button" class="btn btn-success btn-xs has-tooltip" id="resetdata" data-toggle="modal" data-target="#quote<?=$key?>">Approve/Quote</button>
                                                                        <div class="modal fade" id="quote<?=$key?>" role="dialog">
                                                                            <div class="modal-dialog">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header">
                                                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                        <h4 class="modal-title">Approval</h4>
                                                                                    </div>
                                                                                    <div class="modal-body">
                            <form method="POST" name="approve_form" id="approve_form" role="form" action="<?=base_url()?>activities/send_booking_link">
                  <input type="hidden" name="enquiry_reference_no" id="enquiry_reference_no" value="">
                                                                                            <div class="row">
                                                                                                <div class="col-md-6 mb10">
                                                                                                    <div class="radio form-group inline">
                                                                                                        <label>
                                                                                                            <input type="radio" id="price_type1" name="price_type" value="total" checked="checked">
                                                                                                            Total
                                                                                                        </label>
                                                                                                    </div>&nbsp; &nbsp; &nbsp;
                                                                                                    <div class="radio form-group inline">
                                                                                                        <label>
                                                                        <input type="radio" id="price_type2" name="price_type" value="adult_wise" >
                                                                                                            Adult/Child/Infant
                                                                                                        </label>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="row">
                                             <div class="col-md-6">
                                                   <div class="form-group">
                                                                     <label for="">Adult Count</label>
                                                                    <select name="adult_count" id="adult_count" class="form-control" required="required">
                                                                   
                                                                   <?=generate_options1(custom_numeric_dropdown(20,1,1));?>                                     </select>
                                                                                                    </div>
            <div class="form-group">
                                                <label for="">Child Count</label>
                                                                 <select name="child_count" id="child_count" class="form-control" required="required">0
                                                     <?=generate_options(custom_numeric_dropdown(5,0,1));?>                                      </select>
                                                                                                    </div>
                                                    <div class="form-group">
                                                                            <label for="">Infant Count</label>
                                                        <select name="infant_count" id="infant_count" class="form-control" required="required">
                                                                                                            <?=generate_options(custom_numeric_dropdown(5,0,1));?>
                                                                                                        </select>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="col-md-6">
                                                                                                    <div class="form-group price_type1">
                                                                                                        <label for="">Price <span style="color:red">*</span></label>
                                                                                                        <input type="text" class="form-control numeric" name="new_price" id="new_price" required="required" value="0">
                                                                                                    </div>


                                                                                                    <div class="form-group price_type2 hide">
                                                                                                        <label for="">Total Adult Price <span style="color:red">*</span></label>
                                                                                                        <input type="text" class="form-control numeric calculation" name="adult_price" id="adult_price" value="0" >
                                                                                                    </div>
                                                                                                    <div class="form-group price_type2 hide">
                                                                                                        <label for="">Total Child Price</label>
                                                                                                        <input type="text" class="form-control numeric calculation" name="child_price" id="child_price" value="0" readonly="readonly">
                                                                                                    </div>
                                                                                                    <div class="form-group price_type2 hide">
                                                                                                        <label for="">Total Infant Price</label>
                                                                                                        <input type="text" class="form-control numeric calculation" name="infant_price" id="infant_price" value="0" readonly="readonly">
                                                                                                    </div>
                                                                                                    <div class="form-group">
                                                                                                        <label for="">Notes</label>
                                                                                                        <input type="text" name="en_note" id="en_note" class="form-control" />
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="clearfix"></div>

                                                                                                <div class="col-md-6">
                                                                                                    <div class="radio form-group inline">
                                                                                                        <label>
                <input type="radio" id="quote_type1" name="quote_type" value="request_quote" checked="checked">
                                            Request for Quote
                                                                                                        </label>
                                                                                                    </div> &nbsp; &nbsp;
                                                                                                    <div class="radio form-group inline">
                                                                                                        <label>
                                                                                                            <input type="radio" id="quote_type2" name="quote_type" value="final_quote" >
                                                                                                            Final Quote
                                                                                                        </label>
                                                                                                    </div>
                                                                                                </div>

                                                                                                <div class="col-md-6 col-xs-12 pull-right crncy_det">
                                                                                                    <div class="row">
                                                                                                        <div class="form-group col-md-6 col-xs-6">
                                                                                                            <label for="">Currency</label>
                                                                                                            <input type="text" id="currency" name="currency" class="form-control" readonly="readonly" value="<?=get_application_currency_preference()?>">
                                                                                                        </div>
                                                                                                        <div class="form-group col-md-6 col-xs-6">
                                                                                                            <label for="">Total</label>
                                                                                                            <input type="text" readonly="readonly" class="form-control total" id="total" name="total" value="0">
                                                                                                        </div>
                                                                                                        <div class="form-group col-md-12 col-xs-12">
                                                                                                            <input type="hidden"  name="enquiry_id" value="<?=$package->id?>">

                                                                                                            <input type="hidden"  name="enquiry_reference_no" value="<?=$package->enquiry_reference_no?>">
                                                                                                            <input type="hidden"  name="name" value="<?=$package->first_name?>">
                                                                                                            <input type="hidden"  name="email" value="<?=$package->email?>">
                                                                                                            <input type="hidden"  name="phone" value="<?=$package->phone?>">
                                                                                                            <input type="hidden"  name="tour_id" value="<?=$package->package_id?>">
                                                                                                            <button class="btn btn-primary pull-right">Send</button>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </form>
                                                                                    <div class="modal-body">

                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        </div>
                                                                        </br>
                                                                        <a href="<?php echo base_url(); ?>supplier/delete_enquiry/<?php echo $package->id; ?>/<?php echo $package->package_id; ?>" data-original-title="Delete" onclick="return confirm('Do you want delete this record');" class="btn btn-danger btn-xs has-tooltip" data-original-title="Delete"> <i class="icon-remove">Delete</i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                                <?php $count++; } } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- PANEL BODY END -->
    </div>
    <!-- PANEL WRAP END -->
</div>

<script>
    $(document).ready(function(){
    // $('#price_type1').click(function(){
        $(document).on('click', '#resetdata', function(){
            $('#new_price').val(0);
            $('#total').val(0);
            $('.calculation').val(0);
             $('.total').val(0);
            // alert('I am ready');

        });
        $(document).on('click', '#price_type1', function(){
        $(this).prop('checked', true);
        $('#price_type2').prop('checked', false);
        $('.price_type1').removeClass('hide');
        $('.price_type2').addClass('hide');
        $('#total').val(parseFloat($('#new_price').val()));
    });
    // $('#price_type2').click(function(){
        $(document).on('click', '#price_type2', function(){
        $(this).prop('checked', true);
        $('#price_type1').prop('checked', false);
        $('.price_type2').removeClass('hide');
        $('.price_type1').addClass('hide');
        var total = 0;
        $.each($('.calculation'), function() {
            if($(this).val() == ''){
                var price = 0;
            }else{
                var price = $(this).val();
                //alert(price);
            }
            total = total + parseFloat(price);

        });
        $('#total').val(total);
    });

    // $('#child_count').change(function(){
    $(document).on('change', '#child_count', function(){    
        if($(this).val()>0){
           
            $('#child_price').removeAttr('readonly');
        }else{
         
            $('#child_price').attr('readonly','readonly');
        }
    });

    // $('#infant_count').change(function(){
    $(document).on('change', '#infant_count', function(){   
        if($(this).val()>0){
       
            $('#infant_price').removeAttr('readonly');
        }else{
           
            $('#infant_price').attr('readonly','readonly');
        }
    });
    // $('#approve_form').submit(function() {
        $(document).on('submit', '#approve_form', function(){   
        if($('#total').val() == 'NaN' || $('#total').val() == 0){

            $('#total').addClass('invalid-ip');
            return false;
        }
        return true;
    });

    // $('.approve_modal_btn').click(function(){
$(document).on('click', '.approve_modal_btn', function(){   

        $('#enquiry_reference_no').val($(this).data('enquiry'));
        var price = $(this).data('price');
        if(price == ''){
            price =0;
        }
        $('#new_price').val(price);
        $('#total').val(price);
    });
    // $('#new_price').on('keyup blur change', function(e) {
   $(document).on('keyup blur change', '#new_price', function(){      
        //alert($(this).val());
        
        var tot = $(this).val();
        if(tot == '' || tot==0 || tot == null){
            tot =0;
        }

        tot = Math.ceil(tot);
        tot = tot.toFixed(2);
        $('.total').val(tot);
        // $('#total').val(tot);
    });
    // $('.calculation').on('keyup blur change', function(e) {
     $(document).on('keyup', '.calculation', function(){      
        var total = 0;
        $.each($('.calculation'), function() {
            if($(this).val() == '' || $(this).val() == 0 || $(this).val() == null){
                var price = 0;
            }else{
                var price = $(this).val();
            }
            total = total + parseFloat(Math.round(price));
        });
        $('.total').val(total.toFixed(2));
    });
});
</script>