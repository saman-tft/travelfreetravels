<?php 

  $booking_details = $data['booking_details'][0];
  $itinerary_details = $data['booking_details'][0]['itinerary_details'][0];
  //debug($itinerary_details);

  $attributes = json_decode($booking_details['attributes'],true);

  //debug($s_search_params);
  if($attributes['Additional_info']){
      $additional_info = json_decode($attributes['Additional_info']);
  }else{
    $additional_info =array();
  }
  if($attributes['Inclusions']){
    $inclustions =json_decode($attributes['Inclusions'],true);   
  }else{
    $inclustions = array();
  }
 
  if($attributes['Exclusions']){
    $exclustions = json_decode($attributes['Exclusions'],true);
  }else{
    $exclustions = array();
    
    
  }
  if($attributes['Duration']){
    $duration = $attributes['Duration'];
  }else{
    $duration = '';
  }
   if($attributes['ShortDesc']){
    $desc = $attributes['ShortDesc'];
  }else{
    $desc = $attributes['ShortDesc'];
  }

if(isset($booking_details)){
    $app_reference = $booking_details['app_reference'];
  }
  if(isset($booking_details)){
    $booking_source = $booking_details['booking_source'];
  }
  if(isset($booking_details)){
    $status = $booking_details['status'];
  }
  if(isset($booking_details)){
    $lead_pax_email = $booking_details['lead_pax_email'];
  }
 
  // debug($booking_details);
  // exit;

?>

<style type="text/css">
  table td, table th { border:1px solid #eee; font-weight: normal; }
</style>

<div class="container">
<div style="margin:30px auto; width: 800px;">
<div style="float: left;width: 100%;display: block;overflow:hidden;padding: 20px 0;margin-left: 10px 0;background: #fff;"  id="printableArea">
   <div>
      <img style="max-width:250px; margin: 0 30px;" src="<?=$GLOBALS['CI']->template->domain_images($GLOBALS['CI']->template->get_domain_logo())?>" alt="domain_logo" />
      <div style="float: right; padding: 0px 10px;">
       <div style="font-size: 16px;padding: 5px 0;"><span>Travel Date :<b style="color: #199dbe;font-weight: 100;"><?=date("l\, jS F Y",strtotime($booking_details['travel_date']));?></b></span></div>
       <div style="font-size: 16px;padding: 5px 0;"><span>Booking Reference :<b style="color: #199dbe;font-weight: 100;"><?=$booking_details['booking_id']?></b></span></div>
      </div>
   </div>


   <div style="float: left;width: 100%;display: block;">
    

     <div style="display: block;overflow: hidden;margin:0px 0;padding: 10px 10px;">
      <table style="width: 100%; display: table; vertical-align: top; font-size: 14px;">
        <tbody>
        <tr style="background: #e9ebeb;">
         <th style="width: 25%; padding: 10px;">&nbsp;</th>
          <th style="width: 38%; padding: 10px;">Name / Code</th>
          <th style="width: 15%; padding: 10px;">Grade Code</th>
          <!-- <th style="width: 25%; padding: 10px;">Tour Desc</th> -->
          <th style="width: 17%; padding: 10px;">Booking Status</th>
        </tr>


          <tr>
           <td style="padding: 10px; vertical-align: top;"> 
          <div style="float: left; width: 100%; display: block;margin:0px 0 0;">
             <img style="width: 100%; height: 120px;margin:0px;" src="<?=$attributes['ProductImage']?>" alt="product_image" />
           </div>
          </td>

          <td style="padding: 10px; vertical-align: top;"><?=$booking_details['product_name']?> / <?=$booking_details['product_code']?> </td>
          <td style="padding: 10px; vertical-align: top;"><?=$booking_details['grade_code']?></td>
         <!--  <td style="padding: 10px; vertical-align: top;"><?=$booking_details['grade_desc']?></td> -->
          <td style="padding: 10px; vertical-align: top;">
          <strong class="<?php echo booking_status_label( $booking_details['status']);?>" style=" font-size:14px; display: block; padding: 7px;">
                      <?php 
                      switch($booking_details['status']){
                        case 'BOOKING_CONFIRMED': echo 'CONFIRMED';break;
                        case 'BOOKING_CANCELLED': echo 'CANCELLED';break;
                        case 'BOOKING_FAILED': echo 'FAILED';break;
                        case 'BOOKING_INPROGRESS': echo 'INPROGRESS';break;
                        case 'BOOKING_INCOMPLETE': echo 'INCOMPLETE';break;
                        case 'BOOKING_HOLD': echo 'HOLD';break;
                        case 'BOOKING_PENDING': echo 'PENDING';break;
                        case 'BOOKING_ERROR': echo 'ERROR';break;
                        
                      }
                      
                                        
                      ?>
                      </strong></td>
        </tr>        
        </tbody>
      </table>
     </div>

     </div>
    <div class="clearfix"></div>
    <div style="float: left;width: 100%;padding: 10px;">
   
     <table style="width: 100%; display: table; vertical-align: top; font-size: 14px;">
        <tbody>
          <tr style="background: #e9ebeb;">
           <th style="width: 25%; padding: 10px;">Lead Traveler</th>
            <th style="width: 20%; padding: 10px;">Number of Travelers </th>
            <th style="width: 33%; padding: 10px;">Supplier</th>
            <th style="width: 22%; padding: 10px;">Supplier Phone No</th>
          </tr>

          <tr>
             <td style="padding: 10px; vertical-align: top;"> 
             <?= $booking_details['lead_pax_name']?>
            </td>

            <td style="padding: 10px; vertical-align: top;">
                   <?php 
                $total_travell_count = $booking_details['adult_count']+$booking_details['child_count'] +$booking_details['senior_count']+$booking_details['youth_count']+$booking_details['infant_count'];
                ?>
              <?=$total_travell_count?>
            </td>

            <td style="padding: 10px; vertical-align: top;"><?=$attributes['SupplierName']?></td>

            <td style="padding: 10px; vertical-align: top;"><?=$attributes['SupplierPhoneNumber']?></td>
          </tr> 

        </tbody>
      </table>

    </div>

   <div style="float: left;width: 100%;padding: 10px;">
   
     <table style="width: 100%; display: table; vertical-align: top; font-size: 14px;">
        <tbody>
          <tr><td style="padding: 10px;border: 1px solid #eee; font-size: 14px; background: #e9ebeb;">Price Summary</td></tr>

          <tr>
          <td style="border: 1px solid #eee;">
          <table width="100%" cellpadding="5" style="padding: 10px;font-size: 14px;">
          <tbody>
            <tr>
            <td style="padding: 10px; vertical-align: top;"style="padding: 10px; vertical-align: top;"><span>Base Fare</span></td>
          
            <td style="padding: 10px; vertical-align: top;"><span>Discount</span></td>
            <td style="padding: 10px; width: 25%; vertical-align: top;"><span>Total Fare</span></td>
            </tr>

             <tr>
             <td style="padding: 10px; vertical-align: top;"><?php echo $booking_details['currency']; ?> <?php echo roundoff_number($booking_details['product_total_price']+$booking_details['convinence_amount']); ?></td>                                            
                                                      
             <td style="padding: 10px; vertical-align: top;"><?php echo $booking_details['currency']; ?> <?php echo roundoff_number($booking_details['discount']); ?></td>                                            
             <td style="padding: 10px; vertical-align: top;"> <?php echo $booking_details['currency']; ?> <?php echo roundoff_number($booking_details['product_total_price']+$booking_details['convinence_amount']-$booking_details['discount']); ?></td>
             </tr>

             <tr style="font-size:16px;">    
             <td style="padding: 10px; vertical-align: top;" colspan="3" align="right">
             Total Fare 
             </td>                                    
             <td style="padding: 10px; vertical-align: top;" align="left">
            <?php echo $booking_details['currency']; ?> <?php echo roundoff_number($booking_details['product_total_price']+$booking_details['convinence_amount']-$booking_details['discount']); ?>
             </td>

             </tr>
             </tbody>
             </table>
             </td>
          </tr> 

        </tbody>
      </table>

    </div>
    <div class="clearfix"></div>
    <div class="voucher-info"  style="float: left;width: 100%; padding:10px;">
       <?php
          // if($booking_details['voucher_req']){
          //    echo '<h5> Voucher Information : '.$booking_details['voucher_req'].'</h5>';
          // }
          // if($booking_details['bar_code']){
          //  echo '<h5> Bar Code Option : '.$booking_details['bar_code'].'</h5>';
          // }
          // if($booking_details['bar_code_type']){
          //  echo '<h5> Bar Code Type : '.$booking_details['bar_code_type'].'</h5>'; 
          // }
       ?>
    </div>
    <div class="clearfix"></div> 
        <div style="display: block; padding:10px;">
          <div>
               <span style="font-size: 18px;color: #000;font-weight: 500;">Tour Information</span>
          </div>
           
            <h4 style="font-size: 16px;">Tour Description</h4>
            <p><?=$desc?></p>
            <p style="color: #0095ce;">Duration:<?=$duration?></p>
          <?php if($additional_info):?>
           
            <div style="font-size: 18px;font-weight: 100;color: #333;">Information</div>
            <ul style="margin: 0px;display: block;line-height: 15px;">
              <?php foreach($additional_info as $ad):?>
                <li style="padding: 8px 0;list-style:inside;font-size: 14px;"><?=$ad?></li>
              <?php endforeach;?>
            </ul>
          <?php endif;?>
         </div>
          <div style="display: block; padding:10px;">
          
            <div style="font-size: 18px;font-weight: 100;color: #333;">Inclusions</div>
            <ul style="margin: 0px;display: block; line-height: 15px;">
                 <?php foreach($inclustions as $incl):?>
                    <li style="padding: 8px 0;list-style:inside;font-size: 14px;"><?=$incl?></li>
                <?php endforeach;?>
              
            </ul>
         </div>
          <div style="display: block; padding:10px;">
          
            <div style="font-size: 18px;font-weight: 100;color: #333;">Exclusions</div>
            <ul style="margin: 0px;display: block; line-height: 15px;">
               <?php foreach($exclustions as $exclu):?>
                  <li style="padding: 8px 0;list-style:inside;font-size: 14px ;"><?=$exclu?></li>
               <?php endforeach;?>
            </ul>
         </div>

         <div style="display: block; padding:10px;">
          
            <div style="font-size: 18px;font-weight: 100;color: #333;">Cancellation Policy</div>
            <ul style="margin: 0px;display: block; line-height: 15px;">
             
                  <li style="padding: 8px 0;list-style:inside;font-size: 14px ;"><?=$attributes['TM_Cancellation_Policy']?></li>
    
            </ul>
         </div>
          
    </div>
</div>
</div>