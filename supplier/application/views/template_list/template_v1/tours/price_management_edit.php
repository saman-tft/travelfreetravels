<div class="price_mgnt">
<div class="pk_form col-xs-12" id="myModal">
   <form id="enquiry_form" method="post" action="<?php echo base_url() ?>index.php/tours/save_edit_price_management">
      <div class="modal-body">
         <h4 class="text-center hldy_tit">Price Management</h4>
           <ul class="radio_set" style="display:none">
                    <li class="radio">
                        <input id="international" name="radio" type="radio" value="international" <?php if($price_details_single[0]['Type']=="international"){  echo "checked"; }  ?> >
                        <label for="international" class="radio-label">International</label>
                    </li>

                    <li class="radio">
                        <input id="residential" name="radio" type="radio" value="residential" <?php if($price_details_single[0]['Type']=="residential"){  echo "checked"; }  ?> >
                        <label for="residential" class="radio-label">Residential</label>
                    </li>
                </ul>
                <?php

//debug($price_details_single);
                ?>
         <div class="form-group modl">
            <label class="control-label col-md-5 col-xs-4" for="user_name">From Date  :<strong class="text-danger"></strong></label>
           <div class="col-md-7 col-xs-8 eml n_psngr"><i class="fa fa-calendar" aria-hidden="true"></i>  <input type="text" value="<?php echo @$price_details_single[0]['from_date'];?>" name="from_date" class="form-control mntxt" id="hl_depdat" placeholder="From Date" aria-required="true" required="required" >    
            </div>
         </div>
         <div class="form-group modl">
            <label class="control-label col-md-5 col-xs-4" for="user_name">To Date  :<strong class="text-danger"></strong></label>
            <div class="col-md-7 col-xs-8 eml n_psngr"><i class="fa fa-calendar" aria-hidden="true"></i> <input type="text" value="<?= @$price_details_single[0]['to_date'];?>" name="to_date" class="form-control mntxt" id="hl_depdat1" placeholder="To Date" aria-required="true" required="required"  >    
            </div>
         </div>
<div class="form-group modl">
                    <label class="control-label col-md-5 col-xs-4">Nationality</label>
                    
                    <div class="col-md-7 col-xs-8 eml n_psngr">
                         <select class='form-control'  name='nationality' id="nationality"  required> 
                    
                    <option value="0">Select Nationality Group</option>
                                <?php foreach ($nationality_group as $coun) {
                                 
                                  ?>
                                <option value="<?php echo $coun->name; ?>"  data-cur="<?php echo $coun->currency; ?>" <?php if($price_details_single[0]['nationality']==$coun->name){  echo "selected"; }  ?> ><?php echo $coun->name; ?></option>
                                <?php }?>
                              </select>
                    </div>
                </div>
          <div class="form-group modl" >
                  <label for="field-1" class="control-label col-md-5 col-xs-4">Currency<span class="text-danger">*</span></label>                  
                  <div class="col-md-7 col-xs-8 eml">
                     <input id="currency" name="currency" class="form-control" data-validate="required" data-message-required="Please Select the Currency" value="<?php echo $price_details_single[0]['currency']; ?>" readonly />
                  </div>
                </div>
         <div class="form-group modl">
            <label class="control-label col-md-5 col-xs-4" for="user_email">Adult Price (12+ YRS :<strong class="text-danger"></strong></label>
            <div class="col-md-7 col-xs-8 eml"><i class="fa fa-money" aria-hidden="true"></i><input type="text" value="<?= @$price_details_single[0]['adult_airliner_price'];?>" class="form-control mntxt" name="adult_sessional_price" id="adult_sessional_price" placeholder="Adult Price" aria-required="true" required="required"></div>
         </div>

         <div class="form-group modl">
            <label class="control-label col-md-5 col-xs-4" for="user_email">Child Price (2-11 YRS) :<strong class="text-danger"></strong></label>
            <div class="col-md-7 col-xs-8 eml"><i class="fa fa-money" aria-hidden="true"></i><input type="text" value="<?= @$price_details_single[0]['child_airliner_price'];?>" class="form-control mntxt" name="child_sessional_price" id="child_sessional_price" placeholder="Child Price" aria-required="true" required="required"></div>
         </div>
<div class="form-group modl">
                    <label class="control-label col-md-5 col-xs-4" for="user_email">Infant Price (0-2 YRS) :<strong
                            class="text-danger"></strong></label>
                    <div class="col-md-7 col-xs-8 eml"><i class="fa fa-money" aria-hidden="true"></i><input type="text"
                            class="form-control mntxt numeric" name="Infant_sessional_price" id="child_sessional_price"
                            placeholder="Child Price" aria-required="true" value="<?= @$price_details_single[0]['infant_airline_price'];?>"  required="required"></div>
                </div>
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
              <div class="col-md-7 col-xs-8 eml"><i class="fa fa-money" aria-hidden="true"></i><input type="text" class="form-control mntxt numeric" name="budget_hotel_price" id="budget_hotel_price"  placeholder="Budget Price" aria-required="true" required="required" value="<?= @$price_details_single[0]['budget_hotel_price'];?>"></div>
           </div>
           <div class="form-group modl">
              <label class="control-label col-md-5 col-xs-4" for="user_email">Standard :<strong class="text-danger"></strong></label>
              <div class="col-md-7 col-xs-8 eml"><i class="fa fa-money" aria-hidden="true"></i><input type="text" class="form-control mntxt numeric" name="standard_hotel_price" id="standard_hotel_price"  placeholder="Standard Price" aria-required="true" required="required" value="<?= @$price_details_single[0]['standard_hotel_price'];?>"></div>
           </div>
           <div class="form-group modl">
              <label class="control-label col-md-5 col-xs-4" for="user_email">Deluxe :<strong class="text-danger"></strong></label>
              <div class="col-md-7 col-xs-8 eml"><i class="fa fa-money" aria-hidden="true"></i><input type="text" class="form-control mntxt numeric" name="deluxe_hotel_price" id="deluxe_hotel_price"  placeholder="Deluxe Price" aria-required="true" required="required" value="<?= @$price_details_single[0]['deluxe_hotel_price'];?>"></div>
           </div>
           <div class="form-group modl">
              <label class="control-label col-md-5 col-xs-4" for="user_email">4 Star :<strong class="text-danger"></strong></label>
              <div class="col-md-7 col-xs-8 eml"><i class="fa fa-money" aria-hidden="true"></i><input type="text" class="form-control mntxt numeric" name="4_star_hotel_price" id="4_star_hotel_price"  placeholder="4 Star" aria-required="true" required="required" value="<?= @$price_details_single[0]['4_star_hotel_price'];?>"></div>
           </div> -->
<!--           <div class="form-group modl">-->
<!--              <label class="control-label col-md-5 col-xs-4" for="user_email">Price /per person Twin share :<strong class="text-danger"></strong></label>-->
<!--              <div class="col-md-7 col-xs-8 eml"><i class="fa fa-money" aria-hidden="true"></i><input type="text" class="form-control mntxt numeric" name="twin_share_hotel_price" id="twin_share_hotel_price"  placeholder="Price /per person Twin share" aria-required="true" required="required" value="--><?//= @$price_details_single[0]['twin_share_hotel_price'];?><!--"></div>-->
<!--           </div>-->
         <?php } ?>
          <!-- <div class="form-group modl">
           <label class="control-label col-md-5 col-xs-4" for="user_email">Car Pricing :<strong class="text-danger"></strong></label>
        </div> -->
        <?php if($car==1){?><!-- 
        <h4 class="text-center hldy_tit">Car Pricing</h4>
         <div class="form-group modl">
            <label class="control-label col-md-5 col-xs-4" for="budget">Standard :<strong class="text-danger"></strong></label>
            <div class="col-md-7 col-xs-8 eml"><i class="fa fa-money" aria-hidden="true"></i><input type="text" class="form-control mntxt numeric" name="standard_car_price" id="standard_car_price"  placeholder="Standard Price" aria-required="true" required="required" value="<?= @$price_details_single[0]['standard_car_price'];?>"></div>
         </div>
        
         <div class="form-group modl">
            <label class="control-label col-md-5 col-xs-4" for="user_email">Deluxe :<strong class="text-danger"></strong></label>
            <div class="col-md-7 col-xs-8 eml"><i class="fa fa-money" aria-hidden="true"></i><input type="text" class="form-control mntxt numeric" name="deluxe_car_price" id="deluxe_car_price"  placeholder="Deluxe Price" aria-required="true" required="required" value="<?= @$price_details_single[0]['deluxe_car_price'];?>"></div>
         </div>
         <div class="form-group modl">
            <label class="control-label col-md-5 col-xs-4" for="user_email">SUV :<strong class="text-danger"></strong></label>
            <div class="col-md-7 col-xs-8 eml"><i class="fa fa-money" aria-hidden="true"></i><input type="text" class="form-control mntxt numeric" name="suv_car_price" id="suv_car_price"  placeholder="SUV Price" aria-required="true" required="required" value="<?= @$price_details_single[0]['suv_car_price'];?>"></div>
         </div>
         <div class="form-group modl">
            <label class="control-label col-md-5 col-xs-4" for="user_email">Temp Traveller :<strong class="text-danger"></strong></label>
            <div class="col-md-7 col-xs-8 eml"><i class="fa fa-money" aria-hidden="true"></i><input type="text" class="form-control mntxt numeric" name="temp_traveller_price" id="temp_traveller_price"  placeholder="Temp Traveller Price" aria-required="true" required="required" value="<?= @$price_details_single[0]['temp_traveller_price'];?>"></div>
         </div>
          <div class="form-group modl">
            <label class="control-label col-md-5 col-xs-4" for="user_email">Bus :<strong class="text-danger"></strong></label>
            <div class="col-md-7 col-xs-8 eml"><i class="fa fa-money" aria-hidden="true"></i><input type="text" class="form-control mntxt numeric" name="bus_price" id="bus_price"  placeholder="Bus Price" aria-required="true" required="required" value="<?= @$price_details_single[0]['bus_price'];?>"></div>
         </div> -->
        <?php } ?>
         <input type="hidden" name="sessional_price" value="<?= @$price_details_single[0]['sessional_price'];?>">

         <input type="hidden" name="tour_id" value="<?php echo $price_details_single[0]['tour_id']; ?>">
         <input type="hidden" name="id" value="<?php echo $price_details_single[0]['id']; ?>">

          <!-- <div class="form-group modl">
            <label class="control-label col-md-5 col-xs-4" for="user_email">Price for sessional :<strong class="text-danger"></strong></label>
            <div class="col-md-7 col-xs-8 eml"><i class="fa fa-inr" aria-hidden="true"></i><input type="text" class="form-control mntxt" name="sessional_price" id="eemail" placeholder="Price" aria-required="true" required="required"></div>
         </div> -->
        <!--   <div class="form-group modl">
            <label class="control-label col-md-5 col-xs-4" for="user_email">Markup  :<strong class="text-danger"></strong></label>
            <div class="col-md-7 col-xs-8 eml"><i class="fa fa-line-chart" aria-hidden="true"></i><input type="text" value="<?= @$price_details_single[0]['markup'];?>" class="form-control mntxt" name="markup" id="eemail" placeholder="Markup" aria-required="true" required="required"></div>
         </div> -->
      
      </div>
      <div class="modal-footer"><input type="hidden" id="tour_id" value=""><input type="hidden" id="tours_itinerary_id" value="">
      <button type="submit" class="btn btn-default" id="send_enquiry_button">Update</button>
      <a class='btn btn-primary' href="<?php echo base_url() ."index.php/tours/price_management/".$price_details_single[0]['tour_id']; ?>">Cancel</a>
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
  $( function() {
    futureDatepickerMonthDisabled("hl_depdat1");
     futureDatepickerMonthDisabled("hl_depdat");
    
    //if second date is already set then dont run
    if ($("#from_").val() == '' ) {
       auto_set_dates($("#Pickup").datepicker('getDate'), "hl_depdat", 'minDate');
    }
  } );
</script>