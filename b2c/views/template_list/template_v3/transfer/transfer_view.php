<?php 

  $booking_details = $data['booking_details'][0];
  $agent_details = $data['agent_info'];
  // debug($booking_details['itinerary_details'][$booking_details['app_reference']][0]['total_fare']);
  // exit;
 // debug($agent_details); die;
 // debug($transfer_details); die;
  $total_fare = $booking_details['admin_buying_price']+$booking_details['admin_markup'];
  $total_fare = $booking_details['basic_fare'];
  $taxes = $booking_details['agent_markup'];
 
  $itinerary_details = $data['booking_details'][0]['itinerary_details'][0];
  //debug($itinerary_details); die;

  $attributes = json_decode($booking_details['attributes'],true);


  if($attributes['Additional_info']){
      $additional_info = json_decode($attributes['Additional_info'],true);
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
  foreach ($package_details as $key => $value){
    // debug($value->distance);exit;
    $duration = $value->distance;
    $transfer_name = $value->transfer_name;
    $transfer_image = $value->vehicle_image;    
  }
  // debug($duration);exit;
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
  
  $customer_details = $booking_details['customer_details'];
?>
<link href="https://cdn.rawgit.com/mdehoog/Semantic-UI/6e6d051d47b598ebab05857545f242caf2b4b48c/dist/semantic.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $GLOBALS['CI']->template->template_css_dir_hotel('select2.min.css'); ?>" rel="stylesheet" >
<link href="<?php echo $GLOBALS['CI']->template->template_css_dir('css.css'); ?>" rel="stylesheet" >
<style>
   .table>tbody>tr>td {padding:2px 0px 2px 4px!important;}
   .suppliertab{width:50%;float:right;}
   .agenttab{width:50%;float:left;}

   input, text, textarea { 
    width: 100%;
    /*border-color: #1aafa1;*/
    /*background-color: yellow;*/
}


.blink-class {
  border-top: 1px solid #D75A5A;
  border-bottom: 1px solid #D75A5A; 
  background-color: #FFEFEF;
}

 <!-- Loader Coder -->
   <style type="text/css">   
    .modal {
        display:    none;
        position:   fixed;
        z-index:    1000;
        top:        0;
        left:       0;
        height:     100%;
        width:      100%;
        background: rgba( 255, 255, 255, .8 ) 
                    url('https://i.gifer.com/origin/d3/d3f472b06590a25cb4372ff289d81711_w200.gif') 
                    50% 50% 
                    no-repeat;
    }

    body.loading {
        overflow: hidden;   
    }

    body.loading .modal {
        display: block;
    }
 
 
</style>
<div class="modal"></div>

<section class="content" >
   <form name="submit" action="" method="post">
   <div class="container-fluid utility-nav clearfix">
   </div>
   <div class="wrapper">
      <div class="container wrp-cont yhgjk">
         <div class="bodyContent"><br/>
            <h4>Booking ID: <?=$booking_details['app_reference'] ?></h4>
            <div class="panel panel-primary">
               <div class="panel-body">
                  <table class="table table-bordered table-responsive tbl-ap" style="border:1px solid #ddd;border-top: 0px;margin-bottom: 0px;text-align: center;">
                     <tbody>
                        <tr>
                           <p class="bgc-blue-dark text-center apconfirmtab">Booking Details</p>
                           <table class="table table-bordered table-responsive tbl-ap" style="border:1px solid #ddd;border-top: 0px;margin-bottom: 0px;text-align: center;">
                              <thead>
                              </thead>
                              <tbody>
                                 <tr>
                                    <td class="text-left" style="padding:0px" width="100%" colspan="4"></td>
                                 </tr>

                                 <tr>
                                    <td class="bgc-blue text-left" style="border-top: 1px solid;border-right: 1px solid #ccc;" width="20%"> Booking ID </td>
                                    <td class="text-left">  <?=$booking_details['app_reference'] ?> </td>

                                    <td class="bgc-blue text-left" style="border-top: 1px solid;border-right:  1px solid #ccc;" width="20%">Service Provider</td>
                                    <td class="text-left"> 
                                     <?=$booking_details['domainname'] ?>
                                    </td>
                                 </tr>

                                 <tr>
                                    <td class="bgc-blue text-left" style="border-top: 1px solid;border-right:  1px solid #ccc;" width="20%">Created Date</td>
                                    <td class="text-left"><?= date('d-M-Y H:m',strtotime($booking_details['created_datetime'] ))?></td> 

                                    <td class="bgc-blue text-left" style="border-top: 1px solid;border-right:  1px solid #ccc;" width="20%">SP Ref. No#</td>
                                    <td class="text-left"></td>
                                 </tr>

                                 <tr>
                                    <td class="bgc-blue text-left" style="border-top: 1px solid;border-right:  1px solid #ccc;" width="20%">Travel Date</td>
                                    <td class="text-left"><?= date('d-M-Y',strtotime($booking_details['date_of_travel'] ))?>
                                    </td>
                                    <?php
                                    $total_travell_count =count($booking_details['customer_details']);
                                    ?>
                                    <td class="bgc-blue text-left" style="border-top: 1px solid;border-right:  1px solid #ccc;" width="20%">Total Traveler(s)</td>
                                    <td class="text-left" style="width: 100%;"><?= $total_travell_count?>
                                    </td>
                                 </tr>
                              </tbody>
                           </table>
                        </tr>
                        <tr>
                           <p class="bgc-blue-dark text-center apconfirmtab">Transfer Details</p>
                           <table class="table table-bordered table-responsive tbl-ap" style="border:1px solid #ddd;border-top: 0px;margin-bottom: 0px;text-align: center;">
                              <thead>
                              </thead>
                              <tbody>
                                 <tr>
                                    <td class="text-left" style="padding:0px" width="100%" colspan="4"></td>
                                 </tr>
                                 <tr>
                                    <td class="bgc-blue text-left" style="border-top: 1px solid;border-right: 1px solid #ccc;" width="20%">Transfer Name</td>
                                    <td class="text-left">
                                       <?=$transfer_name?>
                                    </td>
                                    <td class="bgc-blue text-left" style="border-top: 1px solid;border-right:  1px solid #ccc;" width="20%">Transfer Address</td>
                                    <td class="text-left"><?=$transfer_details->contact_address?></td>
                                 </tr>
                                 <tr>
                                    <td class="bgc-blue text-left" style="border-top: 1px solid;border-right:  1px solid #ccc;" width="20%">Contact Number</td>
                                    <td class="text-left">---</td>
                                    <td class="bgc-blue text-left" style="border-top: 1px solid;border-right:  1px solid #ccc;" width="20%">Contact Email</td>
                                    <td class="text-left"><?=$transfer_details->contact_email?></td>
                                 </tr>
                                 <tr>
                                    <td class="bgc-blue text-left" style="border-top: 1px solid;border-right:  1px solid #ccc;" width="20%">From</td>
                                    <td class="text-left"><?=$transfer_details->source?></td>
                                    <td class="bgc-blue text-left" style="border-top: 1px solid;border-right:  1px solid #ccc;" width="20%">To</td>
                                    <td class="text-left"><?=$transfer_details->destination?></td>
                                 </tr>
                                 <tr>
                                    <td class="bgc-blue text-left" style="border-top: 1px solid;border-right:  1px solid #ccc;" width="20%">Driver Name</td>
                                    <td class="text-left"><?=$itinerary_details->driver_name?></td>
                                    <td class="bgc-blue text-left" style="border-top: 1px solid;border-right:  1px solid #ccc;" width="20%">Driver Contact Name</td>
                                    <!-- <td class="text-left"><?=$itinerary_details->driver_contact_number?></td> -->
                                 </tr>
                                 <tr>
                                    <td class="bgc-blue text-left" style="border-top: 1px solid;border-right:  1px solid #ccc;" width="20%">Transfer Rating</td>
                                    <td class="text-left"><?=$transfer_details->rating?> Star</td>
                                 </tr>
                              </tbody>
                           </table>
                        </tr>
                        


                  	<!-- 	<input type="hidden" id="agent_currency" value="<?=$booking_details['agent_currency']?>">
                  		<input type="hidden" id="hotel_currency" value="<?=$booking_details['supplier_currency']?>">
                  		<input type="hidden" id="hotel_id" name="hotel" value="<?=$booking_details['hotel_id']?>"> -->

                  		
                        <!--new section for rooms start-->
                        <tr>
                           <td class="text-center" style="padding:0px" width="100%" colspan="3"></td>
                           <p class="bgc-blue-dark text-center apconfirmtab">Pax Details</p>
                           <!---agent tab--start-->

                           <div class="agenttab" style="width: 100%;">
                              <table class="table" style="border-top: 1px solid #ccc;margin-bottom: 0px;text-align: center;">
                                 <tbody id="agent_room_details">

                                    
                                    <!---table need to repeat 1start-->
                                    <span class="<?= $room_section_class; ?>">
                                    
                                                   <table class="table" style="border-top: 1px solid #ccc;margin-bottom: 0px;text-align: center;">
                                                      <tbody>
                                                        <tr>
                                                <td style="background-color:#d9d9d9;padding:5px;color: #333333;">Sr No.</td>
                                                <td style="background-color:#d9d9d9;padding:5px;color: #333333;">Passenger(s) Name</td>
                                                <td style="background-color:#d9d9d9;padding:5px;color: #333333;">Type</td>
                                                <td style="background-color:#d9d9d9;padding:5px;color: #333333;">Email</td>
                                                <td style="background-color:#d9d9d9;padding:5px;color: #333333;">Contact Number</td>
                                             </tr>
                                                         <tr>
                                                            <?php $i=1;?>  
                                             <?php foreach($customer_details as $name): ?>                               
                                             <tr>
                                                <td style="padding:5px;"><?=$i?></td>
                                                <td style="padding:5px"><?=@$name['title'].'  '.$name['first_name'].' '.$name['last_name']?></td>
                                               <!--  <td style="padding:5px;"><?=$name['pax_type']?></td>  -->
                                                <td style="padding:5px;"><?=$name['passenger_type']?></td>
                                                <td style="padding:5px;"><?=$booking_details['lead_pax_email']?></td>
                                                <td style="padding:5px;"><?=$booking_details['phone']?></td>                            
                                             </tr>
                                              <?php $i++;?>
                                             <?php endforeach;?>
                                                         
                                                      </tbody>
                                                   </table>
                                                 </span>
                                             </tbody>
                                          </table>
                                    <!---table need to repeat  1 end-->
                           </div>

                           <!--agent tab end-->
                        </tr>
                        <!--new section for rooms end-->
                        <!--total section start-->
                        <tr>
                           <table class="table table-bordered" style="border:1px solid #ddd;border-top: 0px;margin-bottom: 0px;text-align: center;" >
                              <tbody>
                                 <tr>
                                    <td class="text-center" style="padding:0px;" width="100%" colspan="4"></td>
                                 </tr>
                                 <tr>
                                    <td class="bgc-blue-dark text-center" style="border-top: 1px solid;border-right: 1px solid;" width="50%">Total</td>
                                 </tr>
                                 <tr>
                                    <td class="left" style="width: 100%;">
                                       <table>
                                          <tbody>
                                             <tr>
                                                <td class="text-center" style="border-right: 1px solid #ccc;border-left: 1px solid #ccc;width: 50%;">Total Rate</td>
                                                <td class="text-center" style="border-right: 1px solid #ccc;border-left: 1px solid #ccc;width: 50%;"><?=$booking_details['currency'].' '.$itinerary_details['grand_total']; ?></td>
                                             </tr>
                                             <tr>
                                                <td class="text-center" style="border-right: 1px solid #ccc;border-left: 1px solid #ccc;width: 50%;">Net Rate</td>
                                                <td class="text-center" style="border-right: 1px solid #ccc;border-left: 1px solid #ccc;width: 50%;"><?=$booking_details['currency'].' '.$itinerary_details['grand_total']; ?></td>
                                             </tr>
                                          </tbody>
                                       </table>
                                    </td>
                                    
                                 </tr>
                              </tbody>
                           </table>
                        </tr>
                        <!--total section end-->

                        


                        <tr>
                           <table class="table table-bordered" style="border:1px solid #ddd;border-top: 0px;margin-bottom: 0px;text-align: center;" >
                              <tbody>
                                 <tr>
                                    <td class="text-center" style="padding:0px;" width="100%"></td>
                                 </tr>
                                 <tr>
                                    <td class="bgc-blue-dark text-center" style="border-top: 1px solid;border-right: 1px solid;" >IP Address</td>
                                 </tr>
                                 <tr>
                                    <td class="text-center" style="border-top: 1px solid;border-right: 1px solid;" >Booking has been done through <?=$booking_details['domain_ip'] ?> IP Address.</td>
                                 </tr>
                              </tbody>
                           </table>
                        </tr>
                       
                       
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
</form>
</section>


<script type="text/javascript" src="<?php echo $GLOBALS['CI']->template->template_js_dir_hotel('datepicker/semantic.min.js'); ?>"></script> 
 <script src="<?php echo $GLOBALS['CI']->template->template_js_dir('page_resource/select2.min.js'); ?>"></script>
<script type="text/javascript">
   $( document ).ready(function()
    {
   $('.date_of_travel').calendar({
        type: 'date',
        // minDate:new Date(), 
         formatter: {
            date: function (date, settings) {
              if (!date) return '';
                var day =(date.getDate() < 10 ? '0' : '') + date.getDate();
                var month =date.toLocaleString('default', { month: 'short' });
                var year = date.getFullYear().toString().slice(-2);
                return day + '-' + month + '-' + year;              
            }
          }
      });
   });
</script>
 