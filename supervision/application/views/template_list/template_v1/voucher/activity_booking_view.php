<?php
// debug($data);exit;
// $agent_details = $data['get_agent_info'][0];
// $booking_details = $data['booking_details'];
// $staff_details = $data['get_staff_info'][0];
// $admin_details = $data['get_admin_info'][0];
// // debug($agent_details);exit;
$agent_details = $data['get_agent_info'][0];
$staff_details = $data['get_staff_info'][0];
$admin_details = $data['get_admin_info'][0];
//debug($agent_details);exit;
$booking_details = $data['booking_details'][0];

 $itinerary_details = $data['booking_details'][0]['itinerary_details'][0];
  //debug($itinerary_details); die;

  $attributes = json_decode($booking_details['attributes'],true);
$path = base_url ( '../extras' ).'/custom/'.CURRENT_DOMAIN_KEY.'/uploads/activity/';

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
  
  $customer_details = $booking_details['customer_details'];
?>
<link href="https://cdn.rawgit.com/mdehoog/Semantic-UI/6e6d051d47b598ebab05857545f242caf2b4b48c/dist/semantic.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $GLOBALS['CI']->template->template_css_dir_hotel('select2.min.css'); ?>" rel="stylesheet" >
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

<section class="content" style="margin-top: -45%;">
   <form name="submit" action="" method="post">
   <div class="container-fluid utility-nav clearfix">
   </div>
   <div class="wrapper">
      <div class="container wrp-cont yhgjk">
         <div class="bodyContent">
           
            <div class="panel panel-primary">
               <div class="panel-heading  text-center">
                  <span style="font-size: 18px;">Booking ID: <?=$booking_details['app_reference'] ?></span>
               </div>
               <div class="panel-body">
                  <table class="table table-bordered table-responsive tbl-ap" style="border:1px solid #ddd;border-top: 0px;margin-bottom: 0px;text-align: center;">
                     <tbody>
                         <?php if($agent_details['user_type'] == 3){ ?>
                        <tr>
                           <p class="bgc-blue-dark text-center apconfirmtab">Agent Details</p>
                           <table class="table table-bordered table-responsive tbl-ap" style="border:1px solid #ddd;border-top: 0px;margin-bottom: 0px;text-align: center;">
                              <thead>
                              </thead>
                              <tbody>
                                 <tr>
                                    <td class="text-left" style="padding:0px" width="100%" colspan="4"></td>
                                 </tr>
                                 <tr>
                                    <td class="bgc-blue text-left" style="border-top: 1px solid;border-right: 1px solid #ccc;" width="20%">
                                       Contact Person
                                    </td>
                                    <td class="text-left">    
                                        <?=$agent_details['first_name'].' '.$agent_details['last_name']?>
                                    </td>
                                    <td class="bgc-blue text-left" style="border-top: 1px solid;border-right:  1px solid #ccc;" width="20%">
                                       Zone Name
                                    </td>
                                    <td class="text-left">
                                       ---
                                    </td>
                                 </tr>
                                 <tr>
                                    <td class="bgc-blue text-left" style="border-top: 1px solid;border-right:  1px solid #ccc;" width="20%">
                                    Client Billing</td>
                                    <td class="text-left">Client Billing ***</td>
                                    <td class="bgc-blue text-left" style="border-top: 1px solid;border-right:  1px solid #ccc;" width="20%">Agent Code</td>
                                    <td class="text-left"><?=provab_decrypt($agent_details ['uuid']) ?></td>
                                 </tr>
                                 <tr>
                                    <td class="bgc-blue text-left" style="border-top: 1px solid;border-right:  1px solid #ccc;" width="20%">Consultant Name</td>
                                    <td class="text-left">---</td>
                                    <td class="bgc-blue text-left" style="border-top: 1px solid;border-right:  1px solid #ccc;" width="20%">Agent Email</td>
                                    <td class="text-left"><?=provab_decrypt($agent_details ['email']) ?></td>
                                 </tr>
                                 <tr>
                                    <td class="bgc-blue text-left" style="border-top: 1px solid;border-right:  1px solid #ccc;" width="20%">Agent Type</td>
                                    <td class="text-left"><?=$agent_details['agent_payment_type'] ?></td>
                                    <td class="bgc-blue text-left" style="border-top: 1px solid;border-right:  1px solid #ccc;" width="20%">Client Type</td>
                                    <td class="text-left"></td>
                                 </tr>
                                 <tr>
                                    <td class="bgc-blue text-left" style="border-top: 1px solid;border-right:  1px solid #ccc;" width="20%">Agent Telephone</td>
                                    <td class="text-left"><?=$agent_details['phone'] ?></td>
                                    <td class="bgc-blue text-left" style="border-top: 1px solid;border-right:  1px solid #ccc;" width="20%">Agent Reference ID</td>
                                    <td class="text-left"><?=$agent_details['agent_reference_no'] ?></td>
                                 </tr>
                                 <tr>
                                    <td class="bgc-blue text-left" style="border-top: 1px solid;border-right:  1px solid #ccc;" width="20%">Agency Name</td>
                                    <td class="text-left"><?=$agent_details['agency_name'] ?></td>
                                    <td class="bgc-blue text-left" style="border-top: 1px solid;border-right:  1px solid #ccc;" width="20%"></td>
                                    <td class="text-left"></td>
                                 </tr>
                              </tbody>
                           </table>
                        </tr>
                      <?php }elseif($staff_details['user_type'] == 9){ ?>
                        <tr>
                           <p class="bgc-blue-dark text-center apconfirmtab">Staff Details</p>
                           <table class="table table-bordered table-responsive tbl-ap" style="border:1px solid #ddd;border-top: 0px;margin-bottom: 0px;text-align: center;">
                              <thead>
                              </thead>
                              <tbody>
                                 <tr>
                                    <td class="text-left" style="padding:0px" width="100%" colspan="4"></td>
                                 </tr>
                                 <tr>
                                    <td class="bgc-blue text-left" style="border-top: 1px solid;border-right: 1px solid #ccc;" width="20%">
                                       Contact Person
                                    </td>
                                    <td class="text-left">    
                                        <?=$staff_details['first_name'].' '.$staff_details['last_name']?>
                                    </td>
                                    <td class="bgc-blue text-left" style="border-top: 1px solid;border-right:  1px solid #ccc;" width="20%">
                                       Zone Name
                                    </td>
                                    <td class="text-left">
                                       ---
                                    </td>
                                 </tr>
                                 <tr>
                                    <td class="bgc-blue text-left" style="border-top: 1px solid;border-right:  1px solid #ccc;" width="20%">
                                    Client Billing</td>
                                    <td class="text-left">Client Billing ***</td>
                                    <td class="bgc-blue text-left" style="border-top: 1px solid;border-right:  1px solid #ccc;" width="20%">Staff Code</td>
                                    <td class="text-left"><?=provab_decrypt($staff_details ['uuid']) ?></td>
                                 </tr>
                                 <tr>
                                    <td class="bgc-blue text-left" style="border-top: 1px solid;border-right:  1px solid #ccc;" width="20%">Consultant Name</td>
                                    <td class="text-left">---</td>
                                    <td class="bgc-blue text-left" style="border-top: 1px solid;border-right:  1px solid #ccc;" width="20%">Staff Email</td>
                                    <td class="text-left"><?=provab_decrypt($staff_details ['email']) ?></td>
                                 </tr>
                                 <tr>
                                    <td class="bgc-blue text-left" style="border-top: 1px solid;border-right:  1px solid #ccc;" width="20%">Staff Type</td>
                                    <td class="text-left"><?=$staff_details['agent_payment_type'] ?></td>
                                    <td class="bgc-blue text-left" style="border-top: 1px solid;border-right:  1px solid #ccc;" width="20%">Client Type</td>
                                    <td class="text-left"></td>
                                 </tr>
                                 <tr>
                                    <td class="bgc-blue text-left" style="border-top: 1px solid;border-right:  1px solid #ccc;" width="20%">Staff Telephone</td>
                                    <td class="text-left"><?=$staff_details['phone'] ?></td>
                                    <td class="bgc-blue text-left" style="border-top: 1px solid;border-right:  1px solid #ccc;" width="20%">Staff Reference ID</td>
                                    <td class="text-left"><?=$staff_details['agent_reference_no'] ?></td>
                                 </tr>
                                 <tr>
                                    <td class="bgc-blue text-left" style="border-top: 1px solid;border-right:  1px solid #ccc;" width="20%">Staff Name</td>
                                    <td class="text-left"><?=$staff_details['agency_name'] ?></td>
                                    <td class="bgc-blue text-left" style="border-top: 1px solid;border-right:  1px solid #ccc;" width="20%"></td>
                                    <td class="text-left"></td>
                                 </tr>
                              </tbody>
                           </table>
                        </tr> 

                   
                      <?php }elseif($admin_details['user_type'] == 1){ ?>
                         <tr>
                           <p class="bgc-blue-dark text-center apconfirmtab">Admin Details</p>
                           <table class="table table-bordered table-responsive tbl-ap" style="border:1px solid #ddd;border-top: 0px;margin-bottom: 0px;text-align: center;">
                              <thead>
                              </thead>
                              <tbody>
                                 <tr>
                                    <td class="text-left" style="padding:0px" width="100%" colspan="4"></td>
                                 </tr>
                                 <tr>
                                    <td class="bgc-blue text-left" style="border-top: 1px solid;border-right: 1px solid #ccc;" width="20%">
                                       Contact Person
                                    </td>
                                    <td class="text-left">    
                                        <?=$admin_details['first_name'].' '.$admin_details['last_name']?>
                                    </td>
                                    <td class="bgc-blue text-left" style="border-top: 1px solid;border-right:  1px solid #ccc;" width="20%">
                                       Zone Name
                                    </td>
                                    <td class="text-left">
                                       ---
                                    </td>
                                 </tr>
                                 <tr>
                                    <td class="bgc-blue text-left" style="border-top: 1px solid;border-right:  1px solid #ccc;" width="20%">
                                    Client Billing</td>
                                    <td class="text-left">Client Billing ***</td>
                                    <td class="bgc-blue text-left" style="border-top: 1px solid;border-right:  1px solid #ccc;" width="20%">Admin Code</td>
                                    <td class="text-left"><?=provab_decrypt($admin_details ['uuid']) ?></td>
                                 </tr>
                                 <tr>
                                    <td class="bgc-blue text-left" style="border-top: 1px solid;border-right:  1px solid #ccc;" width="20%">Consultant Name</td>
                                    <td class="text-left">---</td>
                                    <td class="bgc-blue text-left" style="border-top: 1px solid;border-right:  1px solid #ccc;" width="20%">Admin Email</td>
                                    <td class="text-left"><?=provab_decrypt($admin_details ['email']) ?></td>
                                 </tr>
                                 <tr>
                                    <td class="bgc-blue text-left" style="border-top: 1px solid;border-right:  1px solid #ccc;" width="20%">Admin Type</td>
                                    <td class="text-left"><?=$admin_details['agent_payment_type'] ?></td>
                                    <td class="bgc-blue text-left" style="border-top: 1px solid;border-right:  1px solid #ccc;" width="20%">Client Type</td>
                                    <td class="text-left"></td>
                                 </tr>
                                 <tr>
                                    <td class="bgc-blue text-left" style="border-top: 1px solid;border-right:  1px solid #ccc;" width="20%">Admin Telephone</td>
                                    <td class="text-left"><?=$admin_details['phone'] ?></td>
                                    <td class="bgc-blue text-left" style="border-top: 1px solid;border-right:  1px solid #ccc;" width="20%">Admin Reference ID</td>
                                    <td class="text-left"><?=$admin_details['agent_reference_no'] ?></td>
                                 </tr>
                                 <tr>
                                    <td class="bgc-blue text-left" style="border-top: 1px solid;border-right:  1px solid #ccc;" width="20%">Admin Name</td>-->
                                   <td class="text-left"><?=$admin_details['agency_name'] ?></td>
                                    <td class="text-left">    
                                        <?=$admin_details['first_name'].' '.$admin_details['last_name']?>
                                    </td>
                                    <td class="bgc-blue text-left" style="border-top: 1px solid;border-right:  1px solid #ccc;" width="20%"></td>
                                    <td class="text-left"></td>
                                 </tr>
                              </tbody>
                           </table>
                        </tr>  

                    <?php   } ?>
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
                                     <?=$data['domainname'] ?>
                                    </td>
                                 </tr>

                                 <tr>
                                    <td class="bgc-blue text-left" style="border-top: 1px solid;border-right:  1px solid #ccc;" width="20%">Created Date</td>
                                    <td class="text-left"><?= date('d-M-Y H:m',strtotime($booking_details['created_datetime']))?></td> 

                                    <td class="bgc-blue text-left" style="border-top: 1px solid;border-right:  1px solid #ccc;" width="20%">Travel Date</td>
                                    <td class="text-left"><?= date('d-M-Y',strtotime($booking_details['date_of_travel']))?>
                                    </td>
                                 </tr>
                              </tbody>
                           </table>
                        </tr>
                        <tr>
                           <p class="bgc-blue-dark text-center apconfirmtab">Excursion Details</p>
                           <table class="table table-bordered table-responsive tbl-ap" style="border:1px solid #ddd;border-top: 0px;margin-bottom: 0px;text-align: center;">
                              <thead>
                              </thead>
                             <tbody>
                                  <tr>
                                    <td class="text-left" style="padding:0px" width="100%" colspan="4"></td>
                                 </tr>
                                 <tr>
                                    <td class="bgc-blue text-left" style="border-top: 1px solid;border-right: 1px solid #ccc;" width="20%">Excursion Name</td>
                                    <td class="text-left">
                                       <?=$pack_details->package_name?>
                                    </td>
                                    <td class="bgc-blue text-left" style="border-top: 1px solid;border-right:  1px solid #ccc;" width="20%">Contact Address</td>
                                    <td class="text-left"><?=$pack_details->contact_address?></td>
                                 </tr>
                                 <tr>
                                    <td class="bgc-blue text-left" style="border-top: 1px solid;border-right:  1px solid #ccc;" width="20%">Contact Email</td>
                                    <td class="text-left"><?=$pack_details->contact_email?></td>
                                    <!-- <?php
                                    if($booking_details[0]->status==1)
                                    {
                                      $visaStatus = 'Document Verification - Initiated';
                                    }else if($booking_details[0]->status==2)
                                    {
                                      $visaStatus = 'Document Verification - Rejected'; 
                                    }else if($booking_details[0]->status==3)
                                    {
                                      $visaStatus = 'Document Verification - Pending Documents';
                                    }else if($booking_details[0]->status==4)
                                    {
                                      $visaStatus = 'Document Verification - Accepted / Approved';
                                    }else if($booking_details[0]->status==5)
                                    {
                                      $visaStatus = 'Application initiated for VISA';
                                    }else if($booking_details[0]->status==6)
                                    {
                                      $visaStatus = 'Application inprogress for VISA';
                                    }else if($booking_details[0]->status==7)
                                    {
                                      $visaStatus = 'Application rejected for VISA';
                                    }else if($booking_details[0]->status==8)
                                    {
                                      $visaStatus = 'Application accepted for VISA';
                                    }else if($booking_details[0]->status==9)
                                    {
                                      $visaStatus = $data['other_status'];
                                    }
                                    else
                                    {
                                      $visaStatus = 'PENDING';
                                    }

                                    if($booking_details[0]->duration=='Other')
                                    {
                                        $duration = $booking_details[0]->no_of_years.' Year';
                                    }
                                    else
                                    {
                                        $duration = $booking_details[0]->duration;
                                    }
                                    ?> -->
                                    <td class="bgc-blue text-left" style="border-top: 1px solid;border-right:  1px solid #ccc;" width="20%">City</td>
                                    <td class="text-left"><?=$pack_details->package_city?></td>
                                 </tr>
                                 <tr>
                                    <td class="bgc-blue text-left" style="border-top: 1px solid;border-right: 1px solid #ccc;" width="20%">Rating</td>
                                    <td class="text-left">
                                       <?=$pack_details->rating.' Star'?>
                                    </td>
                                    <!-- <td class="bgc-blue text-left" style="border-top: 1px solid;border-right:  1px solid #ccc;" width="20%">Passenger Address</td>
                                    <td class="text-left"><?=$booking_details[0]->address?></td> -->
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
                           <p class="bgc-blue-dark text-center apconfirmtab">Traveller Details</p>
                           <!---agent tab--start-->

                           <div class="agenttab" style="width: 100%;">
                              <table class="table" style="border-top: 1px solid #ccc;margin-bottom: 0px;text-align: center;">
                                 <tbody id="agent_room_details">
                                  <?php
                                  if($booking_details[0]->age>$booking_details[0]->child_age_limit)
                                  {
                                      $passenger_type = 'Adult';
                                  }
                                  else
                                  {
                                      $passenger_type = 'Child';
                                  }
                                  ?>
                                    
                                    <!---table need to repeat 1start-->
                                    <span class="<?= $room_section_class; ?>">
                                    
                                                   <table class="table" style="border-top: 1px solid #ccc;margin-bottom: 0px;text-align: center;">
                                                      <tbody>
                                                        <tr>
                                                <td style="background-color:#d9d9d9;padding:5px;color: #333333;">SL No</td>
                                                <td style="background-color:#d9d9d9;padding:5px;color: #333333;">Passenger Name</td>
                                                <td style="background-color:#d9d9d9;padding:5px;color: #333333;">Type</td>
                                                <td style="background-color:#d9d9d9;padding:5px;color: #333333;">Email</td>
                                                <td style="background-color:#d9d9d9;padding:5px;color: #333333;">Contact Number</td>
                                                <!-- <td style="background-color:#d9d9d9;padding:5px;color: #333333;">Passport Number</td> -->
                                             </tr>
                                                         <tr>                              
                                             <!-- <tr>
                                                <td style="padding:5px"><?=@$booking_details[0]->fname.'  '.$booking_details[0]->mname.' '.$booking_details[0]->lname?></td> -->
                                               <!--  <td style="padding:5px;"><?=$name['pax_type']?></td>  -->
                                                <!-- <td style="padding:5px;"><?=$passenger_type?></td>
                                                <td style="padding:5px;"><?=$booking_details[0]->email?></td>
                                                <td style="padding:5px;"><?=$booking_details[0]->phone?></td>
                                                 <td style="padding:5px;"><?=$booking_details[0]->passport_no?></td>                            
                                             </tr> -->
                                                          <?php $i=1;?>  
                                             <?php foreach($customer_details as $name): ?>                               
                                             <tr>
                                                <td style="padding:5px;"><?=$i?></td>
                                                <td style="padding:5px"><?=@$name['title'].'  '.$name['first_name'].' '.$name['last_name']?></td>
                                               <!--  <td style="padding:5px;"><?=$name['pax_type']?></td>  -->
                                                <td style="padding:5px;"><?=$name['gender']?></td>
                                                <td style="padding:5px;"><?=$booking_details['email']?></td>
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
                           <p class="bgc-blue-dark text-center apconfirmtab">Total</p>
                           <table class="table table-bordered table-responsive tbl-ap" style="border:1px solid #ddd;border-top: 0px;margin-bottom: 0px;text-align: center;">
                              <thead>
                              </thead>
                              <tbody>
                                 <tr>
                                    <td class="text-left" style="padding:0px" width="100%" colspan="4"></td>
                                 </tr>
                                 <tr>
                                    <td class="bgc-blue text-left" style="border-top: 1px solid;border-right: 1px solid #ccc;" width="25%">
                                      Total Excursion Rate
                                    </td>
                                    <td class="text-left" width="25%">    
                                        <?=$booking_details['basic_fare']?>
                                    </td>
                                    <td class="bgc-blue text-left" style="border-top: 1px solid;border-right:  1px solid #ccc;" width="25%">
                                       Total Supplier Rate
                                    </td>
                                    <td class="text-left" width="25%">
                                       <?php 
                                              $total=$booking_details['basic_fare']+ $booking_details['admin_markup'];
                                              ?>
                                        <?= $total;?>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td class="bgc-blue text-left" style="border-top: 1px solid;border-right:  1px solid #ccc;" width="25%">
                                    Net Rate</td>
                                    <td class="text-left" width="25%" ><?=$booking_details['basic_fare']?></td>
                                    <td class="bgc-blue text-left" style="border-top: 1px solid;border-right:  1px solid #ccc;" width="25%">  Net Rate</td>
                                    <td class="text-left" width="25%"><?=$total; ?></td>
                                 </tr>
                                 
                              </tbody>
                           </table>
                        </tr>
                        <!--total section end-->

                        


                        <tr>
                           <!-- <table class="table table-bordered" style="border:1px solid #ddd;border-top: 0px;margin-bottom: 0px;text-align: center;" >
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
                           </table> -->
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
 