

<div class="car_sec">

 <?php

 $template_images = $GLOBALS['CI']->template->template_images();

 $i = 0;


 foreach($raw_car_list['CarSearchResult']['CarResults'] as $car_key => $car_details) {


  $all_coverage_type = array();

  if(isset($car_details) && valid_array($car_details)) {

      $PickUpLocationCode = $car_details['PickUpLocationCode'];

      $ReturnLocationCode = $car_details['ReturnLocationCode'];					

      $AirConditionInd = $car_details['AirConditionInd'];

      $TransmissionType = $car_details['TransmissionType'];

      $FuelType = $car_details['FuelType'];

      $DriveType = $car_details['DriveType'];

      $PassengerQuantity = $car_details['PassengerQuantity'];

      $BaggageQuantity = $car_details['BaggageQuantity'];

      $VendorCarType = $car_details['VendorCarType'];

      $Code = $car_details['Code'];

      $CodeContextType = $car_details['CodeContext'];

      $DoorCount = $car_details['DoorCount'];

      $car_type = $car_details['VehicleCategory'];	

      $car_type_name = $car_details['VehicleCategoryName'];

      $car_class = $car_details['VehClassSizeName'];	

      $car_class_id = $car_details['VehClassSize'];  			

      $car_name = $car_details['Name'];

      $car_image = $car_details['PictureURL'];

      $supplier_logo = $car_details['TPA_Extensions']['SupplierLogo'];				

      $TotalAmount = $car_details['TotalCharge']['EstimatedTotalAmount']; 

      $markup_gst = $car_details['TotalCharge']['_Markup_Gst']; 

      $OnewayTotalFare =  $car_details['TotalCharge']['OneWayFee'];

      $ID_Context = @$car_details['Reference']['ID_Context'];

      $Type = @$car_details['details']['Reference']['Type'];

      $Vendor = @$car_details['Vendor'];

      $vehicle_package = @$car_details['RateComments'];

      $VendorLocation = @$car_details['VendorLocation'];					

      $DropOffLocation = @$car_details['DropOffLocation'];

      $oneway_fee ='';

      $mileagetype = 'Limited' ;

      $car_name = str_replace('or similar', '', $car_name);  

      ######################################################

      ######################################################

      $fuel_policy_code = '';

      $fuel_policy_desc ='';

      if(isset($car_details['PricedCoverage']) && !empty($car_details['PricedCoverage']))

      {

        $oneway_code = '';

        $offer_Includes_html = '';

        $offer_Includes_html1 ='';

        foreach($car_details['PricedCoverage'] as $key => $pricedCoverage)

        {

          if($key == 0){

            $oneway_fee .= $pricedCoverage['CoverageType'];

            $oneway_code = $pricedCoverage['Code'];

            

          }

          $currency_sym = $currency_obj->get_currency_symbol($pricedCoverage['Currency']);

          if($pricedCoverage['Code'] == 'UNL'){

            $mileagetype = 'Unlimited';

          }

          if($pricedCoverage['Code'] == 'F2F'){      

            $fuel_policy_code .= $pricedCoverage['Code'];

            $fuel_policy_desc .= @$pricedCoverage['Desscription'];

          }



          $temp_arr = array('CDW','TP','F2F', 'F2E', 'UNL', 'CF', '412', $oneway_code); 
   

          if(!in_array($pricedCoverage['Code'], $temp_arr)){      

            $all_coverage_type[] = @$pricedCoverage['CoverageType'];

          }

          $desc = $pricedCoverage['Desscription'];

         

          $pan = $key;

          if($pricedCoverage['Code'] == 'CDW' || $pricedCoverage['Code'] == 'TP' || $pricedCoverage['Code'] == 'UNL' || $pricedCoverage['Code'] == 'F2F' || $pricedCoverage['Code'] == 416){

            $offer_Includes_html .= '<div class="carprc clearfix">

                                   <button type="button" class="sumtab"  id="carnect'.$car_key.'"><b>'.$pricedCoverage['CoverageType'].'</b></button>

                                   

                                 <div class="clearfix"></div>

                                 <div id="agelmt'.$car_key.''.$pan.'">



                                  <p style="padding-left: 17px;">'. $desc.'</p>

                                 </div>

                                </div>';  

          }

          if($pricedCoverage['Code'] == 412 || $pricedCoverage['Code'] == 418 || $pricedCoverage['Code'] == 410){


            $desc = explode('per rental: ', $desc);



            if(isset($desc[1])){

              $desc = $desc[1];

            }

            else{

              $desc = $desc[0];

            }

            

            $offer_Includes_html1 .= '<div class="carprc clearfix">

                                   <button type="button" class="sumtab"><b>'.$pricedCoverage['CoverageType'].' - '.$desc.'</b></button>

                                   </div>'; 

          }



              

        }

        if(isset($car_details['Estimated_Deposit_Amount']) && empty($car_details['Estimated_Deposit_Amount']) == false){

          $offer_Includes_html .= '<div class="carprc clearfix">

                                   <button type="button" class="sumtab"><b>EstimatedDeposit</b></button>

                                   

                                 <div class="clearfix"></div>

                                 <div>



                                  <p style="padding-left: 17px;">'. $car_details['Estimated_Deposit_Amount'].'</p>

                                 </div>

                                </div>';  

        }

        if(empty($offer_Includes_html1) == false){

            $heading = "<button type='button' class='sumtab'><b>Fees</b><br/><i>These fees are included in the price shown on the search results page, but are only paid upon arrival at the car rental supplier's desk. Any currency conversion to allow us to display the combined headline price of fees plus rental charge is an estimate for illustrative purposes only.

                        </i></button>";

            $offer_Includes_html .= $heading.$offer_Includes_html1;

         }

     }



    

               	

   ?>

  <div class="rowresult r-r-i">          

    <div class="madgrid">

     <div class="col-xs-12 nopad">

      <div class="sidenamedesc mobile_f_i">

       <div class="celhtl width20 midlbord">

         <div class="suplier_logo"> <img src="<?=$supplier_logo?>" alt=""></div>



        <div class="car_image"> <img src="<?=$car_image?>" class="lazy lazy_loader h-img" onError="this.onerror=null;this.src='<?php echo $GLOBALS['CI']->template->template_images('no-img.jpg'); ?>';" alt=""/></div>

       </div>

       <div class="celhtl width60">

        <div class="waymensn">

         <div class="flitruo_hotel">

          <div class="hoteldist"> 

           <span class="supplier_name hide"><?=$Vendor;?></span>

           <span class="car_type hide"><?=@$car_type_name;?></span>	

           <span class="car_type_id hide"><?=@$car_type_name;?></span>  

            									

           <span class="car_name"><?=$car_name?><span> or Similar</span></span>


           <div class="clearfix"></div>

           <span class="hotel_address elipsetool"><?=$CodeContextType?></span>

           <div class="clearfix"></div>



            <div class="pick cr_wdt">

            <i class="fal fa-car"></i> <span>Vehicle Class:</span>

            <h3><?=ucwords(@$car_class);?></h3>

            <span class="hide vehicle_size"><?=@$car_class?></span>

            <span class="hide vehicle_package"><?=@$vehicle_package?></span>

           </div>



           <div class="pick">		    

            <span class="fuel_icon">Fuel:<?=$fuel_policy_code?></span>

            <h3>

            <a href="#" data-toggle="tooltip" title="<?=$fuel_policy_desc?>">

              <i class="fa fa-info-circle" aria-hidden="true"></i>

              <?=$FuelType; ?>

             </a>

            </h3>

           </div>

          

           <div class="pick cr_wdt">

            <i class="fal fa-car"></i> <span>Vehicle Type:</span>

            <h3><?=ucwords(@$car_type_name);?></h3>

           </div>

           

           <div class="clearfix"></div>

           <div class="middleCol" style="margin-top: 20px; background: #f5f5f5; border-radius: 5px; border: 1px solid #fafafa; padding-bottom: 4px;">

            <ul class="features">

             <li class="person tooltipv">

              <a title="Passengers" data-toggle="tooltip">

               <?php if(isset($PassengerQuantity) && !empty($PassengerQuantity)){ ?><strong><?=$PassengerQuantity?></strong> <span class="hide passenger_quantity"><?=$PassengerQuantity?></span><span class="mn-icon"></span> <?php } ?>

              </a>

             </li> 

             <li class="baggage tooltipv">

              <a  title="Bags" data-toggle="tooltip">

               <?php if(isset($BaggageQuantity) && !empty($BaggageQuantity)){ ?><strong><?=$BaggageQuantity?></strong> <span class="mn-icon"></span> <?php } ?>

              </a>

             </li> 

             <li class="doors tooltipv">

              <a  title="Doors" data-toggle="tooltip">

               <?php if(isset($DoorCount) && !empty($DoorCount)){ ?><strong><?=$DoorCount?></strong> <span class="mn-icon"></span><span class="hide door_count"><?=$DoorCount?></span> <?php } ?>

              </a>

             </li>    

            </ul>



             <div class="pick" style="width: 50%;">

               <i class="fal fa-tachometer-alt"></i> <span>Mileage Allowance:</span>

               <h3><?= $mileagetype; ?></h3>

             </div>



          

           

             </div>

             <div class="clearfix"></div>		

            </div>

           </div>

          </div>

         </div>



         <div class="clearfix"></div>



          <div class="middleCol">

            <ul class="features1">                                      

             <li data-original-title="gear" class="transmission tooltipv"><?php if(isset($TransmissionType) && !empty($TransmissionType)){ ?><span class="mn-icon"></span> <span class="vehicle_manual hide"><?=$TransmissionType; ?></span> <strong><?=$TransmissionType?></strong><?php } ?></li>  

             <li data-original-title="Air Conditioning" class="ac tooltipv"><?php if(isset($AirConditionInd) && !empty($AirConditionInd)){ ?><span class="mn-icon"></span> <span class="vehicle_ac hide"><?php if($AirConditionInd == 'true'){ echo "AC"; }else{ echo "Non-AC"; } ?></span><strong><?php if($AirConditionInd == 'true'){ echo "A/C"; }else{ echo "Non A/C"; } ?></strong><?php } ?></li>  

             <?php if(!empty($oneway_fee)){?>

             <li  class="fuel tooltipv" data-toggle="tooltip" title="<?=$oneway_fee?>"><i class="fa fa-plus"></i><strong> <?=$oneway_fee?></strong></li>

            <?php } ?>

            </ul>                                             

           </div>

        </div>



        <div class="width20 mobile_f_i">

         <div class="mrinfrmtn">

          <div class="sidepricewrp">



           <div class="sideprice">

             <strong><?php echo $currency_obj->get_currency_symbol($currency_obj->to_currency); ?></strong>

             <span class="vehicle_price hide"><?=$TotalAmount+$markup_gst?></span>

               <span class="f-p" data-price="<?=$TotalAmount+$markup_gst?>"><?php

                echo $TotalAmount+$markup_gst;

              ?></span>

          </div>

          <?php 

          $policy_text ='';

          if(isset($car_details['CancellationPolicy']) && valid_array($car_details['CancellationPolicy']) && !empty($car_details['CancellationPolicy'])){

            

            $policy_text .= '<p class="policy_text">';

            foreach($car_details['CancellationPolicy'] as $policy){

              $polic_amount[] = $policy['Amount'];

             

              $policy_text .= $policy['Amount'].' '.$currency_obj->get_currency_symbol($currency_obj->to_currency).' Amount would be charged between '.$policy['FromDate'] .' to '.$policy['ToDate']. '<br/>';



            }

            $policy_text .= '</p>';

            if(in_array(0, $polic_amount)){

              $non_ref = false;

            }

            else{

              $non_ref = true;

            }

          }

          else{

            $non_ref = true;

          }

          

         if($non_ref == false){

          ?>

          <span class="text-center non_ref" style="color:#0B9FD1; display: block;"><a id="cancel_<?=$car_key?>" class="cancel-policy-btn" data-target="#roomCancelModal" data-toggle="modal" data-cancel= '<?php echo $policy_text; ?>'>Cancellation Policy</a></span>

          <?php } 

          else{ ?>

             <span class="text-center non_ref" style="color:#0B9FD1; display: block;">Non Refundable</span>

         <?php } ?>


           <div class="bookbtn">

            <form method="post" action="<?php echo base_url().'index.php/car/car_details/'.($search_id)?>" name="form_transfer_">

              <input type="hidden" id="mangrid_id_<?=$car_key?>_<?=$Code?>" value="<?=$car_details['ResultToken']?>" name="ResultIndex"  data-key="<?=$car_key?>" data-hotel-code="<?=$Code?>" class="result-index">

              <input type="hidden" id="booking_source_<?=$car_key?>_<?=$Code?>" value="<?=urlencode($booking_source)?>" name="booking_source"  data-key="<?=$car_key?>" data-hotel-code="<?=$Code?>" class="booking_source">

              <input type="hidden" value="get_details" name="op" class="operation">

             <input type="submit" value="Book" class="booknow frdsk" />

            

            </form> 



           </div>



          </div> 

           

           <a class="detailsflt" data-toggle="collapse" data-target="#car_rental<?=$car_key?>"> More <label>Details</label><span class="caret"></span>

          </a>

         </div>

        </div>

       </div>		

       <?php 

      }

      ?>

      <div class="clearfix"></div>

      <?php 

      if(isset($car_details['RateRestrictions']) && !empty($car_details['RateRestrictions'])){

       $minimum_age = $car_details['RateRestrictions']['MinimumAge'];

       $maximum_age = $car_details['RateRestrictions']['MaximumAge'];

      }      

      ?>

      <div id="car_rental<?=$car_key?>" class="collapse" data-role="dialog">

       <div class="carextent">

        <div class="modal-content1">

         <div class="clearfix"></div>

         <div class="modal-body1">

          <div class="col-xs-12 nopad">

          

           <div class="clearfix"></div>

           <div class="rentcondition">

            <div class="hotel_detailtab">

             <div class="clearfix"></div>

             <div class="tab-content"> 

              <div class="tab-pane active" id="htldets<?=$car_key?>">

               <div class="innertabs">

                <div class="secn_pot"> 

                 <div class="includ">

                  <div class="parasub">


                  </div>

                 </div>

                 <?php 

                 if(isset($minimum_age) || isset($maximum_age)) {

                  ?> 

                  <div class="linebrk"></div>

                  <button type="button" class="sumtab" data-toggle="collapse" data-target="#agelmt">Age Limit</button>

                  <div class="collapse in age_lmt" id="agelmt">

                   <div class="parasub">

                    <ul class="checklist">	                     

                     <li> <span>Minimum age: <strong><?=$minimum_age?></strong></span></li>

                     <li> <span>Maximum age: <strong><?=$maximum_age?></strong></span></li>	                      

                    </ul>

                   </div>

                  </div>

                  <div class="linebrk"></div>

                  <?php 

                 } 

                 ?>

                 <div class="clearfix"></div>

                 <p class="carhead"><?php echo $vehicle_package; ?></p>

                 <div id="see_more<?=$car_key?>" class="collapse in">                   

                  <?=$offer_Includes_html;?>

                 </div>

                 

                </div>

               </div>

              </div>

              <div class="clearfix"></div>

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

    <?php 

    $i++; 

   }

    $mini_loading_image = '<div class="text-center loader-image"><img src="'.$GLOBALS['CI']->template->template_images('loader_v3.gif').'" alt="Loading........"/></div>'; 

   ?>

  </div>



<!-- pop-up -->

<div class="modal fade" id="roomCancelModal" role="dialog">

    <div class="modal-dialog modal-lg">

      <div class="modal-content">

        <div class="modal-header">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Cancellation Policy</h4>

        </div>

        <div class="modal-body">

          <p class="can-loader hide"><?=$mini_loading_image?></p>

          <div id='can-model'>

              <p class="policy_text"></p>

          </div>

    

        </div>

        <div class="modal-footer">

          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

        </div>

      </div>

    </div>

  </div>

<!-- end -->

  <script type="text/javascript">

   $(document).ready(function(){

    $('[data-toggle="tooltip"]').tooltip();

   });

  </script>



  <script type="text/javascript">

   $(document).ready(function() {

    $('.squaredThree label').bind('click',function(){

     var input = $(this).find('input');  

     if(input.prop('checked')){

      input.prop('checked',false);

      $('html, body').animate({scrollTop:0}, 1500);

     }else{

      input.prop('checked',true);

      $('html, body').animate({scrollTop:0}, 1500);

     }

    });

    $(".cancel-policy-btn").on("click",function(){

      $("#can-model").html('');

      var policy_text = $(this).data('cancel');



      $("#can-model").html(policy_text);

      $(".can-loader").addClass('hide');

      $(".loader-image").addClass('hide');

      

    });

   });

  </script>



  <style>

   .car_price_new {

    color: #f88c3e;

    display: block;

    font-size: 18px;

    font-weight: 500;

    overflow: hidden;

   }

   .carprc button.sumtab {

    float: left;

    color: #666;

    font-size: 13px;

    font-weight: normal;

    background: #f5f5f5;

    width: 100%;

    text-align: left;

}

   .prcright {

    float: right;

    text-align: left;

    width: 40%;

   }

   p.carhead {

    color: #f58830;

    font-size: 17px;

    font-weight: 500;

    padding: 0 16px;

}

   @media ( min-width :992px) {

    .sidenamedesc {

     display: block;

     width: 75%; background: #fff;

     display: table-cell; position: relative; border-radius: 5px;

    }

    .celhtl.width20.midlbord {

     float: none;

     display: table-cell;

     vertical-align: middle;

    }

    .celhtl.width60 {

     float: none;

     display: table-cell;

     vertical-align: middle;

    }

    .width20 {

     float: none !important;

     vertical-align: middle;

     display: table-cell !important;

    }

    .madgrid .col-xs-12.nopad {

     width: 100%;

     display: table;

    }

   }



.features1 .tooltip { width: auto !important; float: left; background: none !important; border-radius: 3px;} 

.features1 .tooltip.left { padding: 0px !important; }

.features1 .tooltip-inner { padding: 10px !important;

    background: #20364f !important;

    max-width: auto !important;

    color: #fff;

    /* max-width: 200px !important; */

    text-align: left;

    font-family: 'Aller', sans-serif;

    font-size: 13px;}

.features1 .tooltip-inner .table { margin-bottom: 0px !important; background: #333 !important; }

.features1 .tooltip.left .tooltip-arrow { right: -5px !important; border-left-color: #333; }

.features1 .tooltip.in { opacity: 1 !important; }

 </style>

