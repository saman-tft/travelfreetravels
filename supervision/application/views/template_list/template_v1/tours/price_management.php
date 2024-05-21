<style>
    .eml i.fa.fa-money {
    position: absolute;
    top: 10px;
    left: 24px;
}
.modl .eml input {
    padding-left: 32px;
}
.col-md-7.col-xs-8.eml {
    margin-top: 5px;
    margin-bottom: 6px;
}
</style>
<div class="price_mgnt">
    <div class="pk_form col-xs-12" id="myModal">
        <form id="enquiry_form" method="post" action="<?php echo base_url() ?>index.php/tours/save_price_management">
            <div class="modal-body">
                <h4 class="text-center hldy_tit">Price Management</h4>

                <ul class="radio_set" style="display:none">
                    <li class="radio">
                        <input id="international" name="radio" type="radio" value="international" >
                        <label for="international" class="radio-label">International</label>
                    </li>

                    <li class="radio">
                        <input id="residential" name="radio" type="radio" value="residential" >
                        <label for="residential" class="radio-label">Residential</label>
                    </li>
                </ul>

                <div class="form-group modl">

                    <input type="hidden" name="occupancy" value="5" id="occupancy">
                </div>
 <div class="form-group modl">
                    <label class="control-label col-md-5 col-xs-4" >Nationality :<strong
                            class="text-danger"></strong></label>
                    <div class="col-md-7 col-xs-8 eml n_psngr"> 
                         <select class='form-control'  name='nationality' id="nationality"  required> 
                    
                    <option value="0">Select Nationality Group</option>
                                <?php foreach ($nationality_group as $coun) {
                                 
                                  ?>
                                <option value="<?php echo $coun->name; ?>"  data-cur="<?php echo $coun->currency; ?>"><?php echo $coun->name; ?></option>
                                <?php }?>
                              </select>
                    </div>
                </div>

                <div class="form-group modl" >
                  <label for="field-1" class="control-label col-md-5 col-xs-4">Currency<span class="text-danger">*</span></label>                  
                  <div class="col-md-7 col-xs-8 eml n_psngr">
                    <input id="currency" name="currency" class="form-control" data-validate="required" data-message-required="Please Select the Currency" readonly />
                  </div>
                </div>
                <div class="form-group modl">
                    <label class="control-label col-md-5 col-xs-4" for="user_name">From Date :<strong
                            class="text-danger"></strong></label>
                    <div class="col-md-7 col-xs-8 eml n_psngr"><i class="fa fa-calendar" aria-hidden="true"></i> <input
                            type="text" name="from_date" class="form-control mntxt form_parametere_needs" id="from_"
                            placeholder="From Date" aria-required="true" required="required" readonly="readonly">
                    </div>
                </div>
                <div class="form-group modl">
                    <label class="control-label col-md-5 col-xs-4" for="user_name">To Date :<strong
                            class="text-danger"></strong></label>
                    <div class="col-md-7 col-xs-8 eml n_psngr"><i class="fa fa-calendar" aria-hidden="true"></i> <input
                            type="text" name="to_date" class="form-control mntxt form_parametere_needs" id="to_"
                            placeholder="To Date" aria-required="true" required="required" readonly="readonly">
                    </div>
                </div>

                <div class="form-group modl">
                    <label class="control-label col-md-5 col-xs-4" for="user_email">Adult Price (12+ YRS):<strong
                            class="text-danger"></strong></label>
                    <div class="col-md-7 col-xs-8 eml"><i class="fa fa-money" aria-hidden="true"></i><input type="text"
                            class="form-control mntxt numeric" name="adult_sessional_price" id="adult_sessional_price"
                            placeholder="Adult Price" aria-required="true" required="required"></div>
                </div>
                <div class="form-group modl">
                    <label class="control-label col-md-5 col-xs-4" for="user_email">Child Price (2-11 YRS) :<strong
                            class="text-danger"></strong></label>
                    <div class="col-md-7 col-xs-8 eml"><i class="fa fa-money" aria-hidden="true"></i><input type="text"
                            class="form-control mntxt numeric" name="child_sessional_price" id="child_sessional_price"
                            placeholder="Child Price" aria-required="true" required="required"></div>
                </div>
                <div class="form-group modl">
                    <label class="control-label col-md-5 col-xs-4" for="user_email">Infant Price (0-2 YRS) :<strong
                            class="text-danger"></strong></label>
                    <div class="col-md-7 col-xs-8 eml"><i class="fa fa-money" aria-hidden="true"></i><input type="text"
                            class="form-control mntxt numeric" name="Infant_sessional_price" id="child_sessional_price"
                            placeholder="Child Price" aria-required="true" required="required"></div>
                </div>
                <!-- <div class="form-group modl">
           <label class="control-label col-md-5 col-xs-4" for="user_email">Hotel Pricing :<strong class="text-danger"></strong></label>
        </div> -->
                <?php 
        $hotel=0;
        $car=0;
        $tour_inclusions=json_decode($tour_data['inclusions_checks'],true);
        foreach ($tour_inclusions as $tkey => $tvalue) {
          if($tour_inclusions[$tkey]=='Hotel')
          {
            $hotel=1;
          }
          if($tour_inclusions[$tkey]=='Car')
          {
            $car=1;
          }
        }
         if($hotel==1) {?>
                <!-- <h4 class="text-center hldy_tit">Hotel Pricing</h4>
           <div class="form-group modl">
              <label class="control-label col-md-5 col-xs-4" for="budget">Budget :<strong class="text-danger"></strong></label>
              <div class="col-md-7 col-xs-8 eml"><i class="fa fa-money" aria-hidden="true"></i><input type="text" class="form-control mntxt numeric" name="budget_hotel_price" id="budget_hotel_price"  placeholder="Budget Price" aria-required="true" required="required" value=""></div>
           </div>
           <div class="form-group modl">
              <label class="control-label col-md-5 col-xs-4" for="user_email">Standard :<strong class="text-danger"></strong></label>
              <div class="col-md-7 col-xs-8 eml"><i class="fa fa-money" aria-hidden="true"></i><input type="text" class="form-control mntxt numeric" name="standard_hotel_price" id="standard_hotel_price"  placeholder="Standard Price" aria-required="true" required="required" value=""></div>
           </div>
           <div class="form-group modl">
              <label class="control-label col-md-5 col-xs-4" for="user_email">Deluxe :<strong class="text-danger"></strong></label>
              <div class="col-md-7 col-xs-8 eml"><i class="fa fa-money" aria-hidden="true"></i><input type="text" class="form-control mntxt numeric" name="deluxe_hotel_price" id="deluxe_hotel_price"  placeholder="Deluxe Price" aria-required="true" required="required" value=""></div>
           </div>
           <div class="form-group modl">
              <label class="control-label col-md-5 col-xs-4" for="user_email">4 Star :<strong class="text-danger"></strong></label>
              <div class="col-md-7 col-xs-8 eml"><i class="fa fa-money" aria-hidden="true"></i><input type="text" class="form-control mntxt numeric" name="4_star_hotel_price" id="4_star_hotel_price"  placeholder="4 Star" aria-required="true" required="required" value=""></div>
           </div> -->
                <!--div class="form-group modl">
              <label class="control-label col-md-5 col-xs-4" for="user_email">Price /per person Twin share :<strong class="text-danger"></strong></label>
              <div class="col-md-7 col-xs-8 eml"><i class="fa fa-money" aria-hidden="true"></i><input type="text" class="form-control mntxt numeric" name="twin_share_hotel_price" id="twin_share_hotel_price"  placeholder="Price /per person Twin share" aria-required="true" required="required" value=""></div>
           </div-->
                <?php } ?>
                <!-- <div class="form-group modl">
           <label class="control-label col-md-5 col-xs-4" for="user_email">Car Pricing :<strong class="text-danger"></strong></label>
        </div> -->
                <?php if($car==1){?>
                <!--  <h4 class="text-center hldy_tit">Car Pricing</h4>
         <div class="form-group modl">
            <label class="control-label col-md-5 col-xs-4" for="budget">Standard :<strong class="text-danger"></strong></label>
            <div class="col-md-7 col-xs-8 eml"><i class="fa fa-money" aria-hidden="true"></i><input type="text" class="form-control mntxt numeric" name="standard_car_price" id="standard_car_price"  placeholder="Standard Price" aria-required="true" required="required" value=""></div>
         </div>
        
         <div class="form-group modl">
            <label class="control-label col-md-5 col-xs-4" for="user_email">Deluxe :<strong class="text-danger"></strong></label>
            <div class="col-md-7 col-xs-8 eml"><i class="fa fa-money" aria-hidden="true"></i><input type="text" class="form-control mntxt numeric" name="deluxe_car_price" id="deluxe_car_price"  placeholder="Deluxe Price" aria-required="true" required="required" value=""></div>
         </div>
         <div class="form-group modl">
            <label class="control-label col-md-5 col-xs-4" for="user_email">SUV :<strong class="text-danger"></strong></label>
            <div class="col-md-7 col-xs-8 eml"><i class="fa fa-money" aria-hidden="true"></i><input type="text" class="form-control mntxt numeric" name="suv_car_price" id="suv_car_price"  placeholder="SUV Price" aria-required="true" required="required" value=""></div>
         </div>
         <div class="form-group modl">
            <label class="control-label col-md-5 col-xs-4" for="user_email">Temp Traveller :<strong class="text-danger"></strong></label>
            <div class="col-md-7 col-xs-8 eml"><i class="fa fa-money" aria-hidden="true"></i><input type="text" class="form-control mntxt numeric" name="temp_traveller_price" id="temp_traveller_price"  placeholder="Temp Traveller Price" aria-required="true" required="required" value=""></div>
         </div>
          <div class="form-group modl">
            <label class="control-label col-md-5 col-xs-4" for="user_email">Bus :<strong class="text-danger"></strong></label>
            <div class="col-md-7 col-xs-8 eml"><i class="fa fa-money" aria-hidden="true"></i><input type="text" class="form-control mntxt numeric" name="bus_price" id="bus_price"  placeholder="Bus Price" aria-required="true" required="required" value=""></div>
         </div> -->
                <?php } ?>
                <input type="hidden" name="tour_id" id="tour_id" value="<?php echo $tour_id; ?>">
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-default send_enquiry_button" id="send_enquiry_button">Save</button>
                <a class="btn btn-default" href="<?=base_url().'index.php/'.$this->router->fetch_class()?>/tour_list">Go
                    to tour list</a>
            </div>

        </form>
    </div>
</div>
<script type="text/javascript">
  $("#nationality").on("change",function(){
        var cur=$('option:selected', this).attr("data-cur");
           console.log(cur);
        $("#currency").val(cur);
    });
$('.form_parametere_needs').change(function() {
    $('.send_enquiry_button').attr('disabled', true);
    $('.send_enquiry_button').css({
        "border-color": "#006bd7",
        "background-color": "#006bd7"
    });
    var date1 = $('#from_').val();
    var date2 = $('#to_').val();

    //var occupencies = $("#occupancy").val();

    var tour_id = $('#tour_id').val();
var nationality = $('#nationality').val();
    //if(date1 != '' && date2 != '' && occupancy != ''){
    if (date1 != '' && date2 != '' && occupancy != '') {

        var myurl = '<?=base_url()?>index.php/tours/check_price_avilability';
        $.ajax({
            url: myurl,
            type: "POST",
            dataType: "JSON",
            data: {
                from: date1,
                to: date2,
                nationality: nationality,
                tour_id: tour_id
            },
            async: false,
            success: function(result) {
                if (result.status) {
                    $('.send_enquiry_button').removeAttr('disabled');
                    $(".error").hide();
                } else {
                    $('.send_enquiry_button').attr('disabled', true);

                    $('.send_enquiry_button').css({
                        "border-color": "#006bd7",
                        "background-color": "#006bd7"
                    });
                    // $(".modal-body").after("<span class = 'error'>The price is already added for same criteria. Please change your criteria</span>");
                    toastr.warning(
                        'The price is already added for same criteria. Please change your criteria'
                        );
                }
            }
        });
    }

});
</script>

<div class="col-xs-12 prc_tbl">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>S.N0.</th>
                     <th>Nationality</th>
                    <th>currency</th>
                    <th>From Date</th>
                    <th>To Date</th>
                    <th>Adult Price</th>
                    <th>Child Price</th>
                    <th>Infant Price</th>
                    <!-- <th>Budget Hotel</th>
        <th>Standard Hotel</th>
        <th>Deluxe Hotel</th>
        <th>4 Star Hotel</th>
        <th>Twin Share Hotel</th>
        <th>Standard Car</th>
        <th>Deluxe Car</th>
        <th>SUV Car</th>
        <th>Temp Traveller</th>
        <th>Bus</th> -->
                    <th>Operation</th>
                </tr>
            </thead>
            <tbody>
                <?php
   $i = 1;

     foreach ($price_details as  $price) {
      //debug($price);
   
    //   $currency_obj = new Currency(array('module_type' => 'holiday','from' => $price['currency'], 'to' => 'CAD')); 
    //  $get_currency_symbol = ($this->session->userdata('currency') != '') ? $this->CI->session->userdata('currency') : 'CAD';
    //  $current_currency_symbol = $currency_obj->get_currency_symbol($get_currency_symbol);
    //  $converted_currency_rate = $currency_obj->getConversionRate(false);
    // // $final_price = $converted_currency_rate*$price['sessional_price'];
    //  $final_price = $price['final_airliner_price'];
    //  if( $final_price==0){
    //   $final_price = $price['sessional_price'];
    // //  echo  $final_price."----".$price['calculated_markup'];
    //  }
    //  $price_with_out_up = $converted_currency_rate*$price['sessional_price'];

      // $query_x = "select * from occupancy_managment where id='".$price['occupancy']."'";      
      // $exe_x   = mysql_query($query_x);
      // $fetch_x = mysql_fetch_assoc($exe_x);
      // debug($price);
    ?>
                <tr>
                    <td><?= $i;?></td>
                 
                                        <td><?= $price['nationality']; ?></td>
                    <td><?= $price['currency']; ?></td>
                    <td><?= $price['from_date']; ?></td>
                    <td><?= $price['to_date'];?></td>
                    <td><?= sprintf("%.2f", $price['adult_airliner_price']);?></td>
                    <td><?= sprintf("%.2f", $price['child_airliner_price']);?></td>
                     <td><?= sprintf("%.2f", $price['infant_airline_price']);?></td>
                    <!-- <td><?= sprintf("%.2f", $price['budget_hotel_price']);?></td> 
        <td><?= sprintf("%.2f", $price['standard_hotel_price']);?></td> 
        <td><?= sprintf("%.2f", $price['deluxe_hotel_price']);?></td> 
        <td><?= sprintf("%.2f", $price['4_star_hotel_price']);?></td> 
        <td><?= sprintf("%.2f", $price['twin_share_hotel_price']);?></td> 
        <td><?= sprintf("%.2f", $price['standard_car_price']);?></td> 
        <td><?= sprintf("%.2f", $price['deluxe_car_price']);?></td> 
        <td><?= sprintf("%.2f", $price['suv_car_price']);?></td> 
        <td><?= sprintf("%.2f", $price['temp_traveller_price']);?></td> 
        <td><?= sprintf("%.2f", $price['bus_price']);?></td>  -->
                    <td> <?php echo  '<a class="" data-placement="top" href="'.base_url().'index.php/tours/edit_price/'.$price['id'].'"
              data-original-title="Edit Tour Destination"> <i class="glyphicon glyphicon-pencil" ></i> Edit
              </a> &nbsp; <br>'; ?>

                        <?php
              $url = $this->uri->segment(3);
             /* echo  '<a data-placement="top" class="callDelete" id="'.$price['id'].'" href="'.base_url().'index.php/tours/delete_price/'.$price['id'].'/'.$url.'"
              data-original-title="Edit Tour Destination"> <i class="glyphicon glyphicon-trash" ></i> Delete
              </a> &nbsp; <br>';*/
               echo  '<a data-placement="top" class="callDelete" id="'.$price['id'].'" 
              data-original-title="Edit Tour Destination"> <i class="glyphicon glyphicon-trash" ></i> Delete
              </a> &nbsp; <br>';
              
              
              ?>

                    </td>



                </tr>
                <?php
     $i++;
    }
    ?>


            </tbody>
        </table>
    </div>
</div>


<script type="text/javascript">
$(document).ready(function() {
    $(function() {
        //$( "#hl_depdat" ).datepicker();
        //$( "#hl_depdat1" ).datepicker();
        $(".callDelete").click(function() {
            $id = $(this).attr('id'); //alert($id);
            $response = confirm("Are you sure to delete this record?");

            var url = <?php echo ${url}; ?>;

            if ($response == true) {

                window.location = '<?=base_url()?>index.php/tours/delete_price/' + $id + '/' +
                    url;

            } else {


            }
        });
    });
});
</script>
<script type="text/javascript">
$(function() {
    futureDatepickerMonthDisabled("to_");
    futureDatepickerMonthDisabled("from_");
    //$("#Pickup").change(function() {
    //manage date validation
    //  auto_set_dates($("#Pickup").datepicker('getDate'), "from_", 'minDate');
    //});
    //if second date is already set then dont run
    if ($("#from_").val() == '') {
        auto_set_dates($("#Pickup").datepicker('getDate'), "from_", 'minDate');
    }
});

$('#to__places').change(function() {
    var country = $(this).val();
    $.ajax({
        url: app_base_url + 'ajax/getcity_of_country',
        type: 'post',
        data: {
            'country': country
        },
        success: function(response) {
            $('#to__city').html(response);
        },
        error: function() {
            alert('City Not Available.');
        }
    });
    //alert(country);
});
</script>