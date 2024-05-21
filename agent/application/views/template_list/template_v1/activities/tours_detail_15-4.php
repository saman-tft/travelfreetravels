
<?php //debug($package); 
$contrller = '';
if($package->module_type == 'transfers'){

  $contrller = 'transferv1';
}else{

  $contrller = 'activities';
} 


  
 $package_price=0;
 if(!empty($formated_data['0crs']))
 { 

    if(isset($formated_data['0crs']['Price']))
    {
    $package_price=$formated_data['0crs']['Price']['TotalDisplayFare'];
          
    }

    
 }

 // debug($package_price);exit();

?>
<?php
Js_Loader::$css[] = array('href' => $GLOBALS['CI']->template->template_css_dir('owl.carousel.min.css'), 'media' => 'screen');
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('owl.carousel.min.js'), 'defer' => 'defer');
?>
<link href="<?php echo $GLOBALS['CI']->template->template_css_dir('custom_tour_package.css') ?>" rel="stylesheet">
<link  href="<?php echo $GLOBALS['CI']->template->template_css_dir('custom_sky.css') ?>" rel="stylesheet">
<script src="<?php echo $GLOBALS['CI']->template->template_js_dir('initialize-carousel-detailspage.js') ?>" type="text/javascript"></script> 
<script src="<?php echo $GLOBALS['CI']->template->template_js_dir('jquery.provabpopup.js') ?>" type="text/javascript"></script>
<script src="<?php echo $GLOBALS['CI']->template->template_js_dir('jquery.carouFredSel-6.2.1-packed.js') ?>" type="text/javascript"></script>

<style type="text/css">
    footer { margin: 0px; }
    .ovrimpdiv { padding: 0px !important; margin: 0px; }
    .masthead-wrapper .actimgdiv { margin-top: 0px; }
    .ovrimpdiv .tab-content { margin-bottom: 0px; padding: 0px; box-shadow: none; }
    .innertabs { margin-bottom: 10px; }
</style>

<div class="outactdiv sghtseen">
  <div class="container">
 <?php if($enquire_status == 1){ ?>
    <br>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="alert alert-dismissible alert-success">
              <button type="button" class="close" style="margin-top: -5px;" data-dismiss="alert">&times;</button>
              <strong> Your enquire is submitted successfully. one of our travel consultant will contact you soon.!</strong> 
            </div>
        </div>
    </div>
       
                    <?php } ?>
        

      <div class="col-xs-12 nopad">
        <div class="org_row">

            <div class="col-md-12 nopad">


        <div class="col-md-4 rit_summery">
         <div class="frmdiv">
           <div class="booking-price">
             <span class="price-from">Best Price From </span>
             <span class="price-amount price-amount-l">
             <span class="currency-sign"><?php echo $currency_obj->get_currency_symbol($currency_obj->to_currency); ?></span> 
                <span class="currency-amount"><?php //echo isset($package_price)? number_format(get_converted_currency_value ( $currency_obj->force_currency_conversion ( $package_price) ), 2):0; 

echo isset($package_price)? number_format($package_price, 2):0;
                ?> </span>
             </span>

             <span class="snote">*Per Person</span>
                            <div class="sdfr">
                                
                               <!-- <?php echo isset($package->duration)?($package->duration - 1):0; ?> Nights / <?php echo isset($package->duration)?$package->duration:0; ?> Days-->
                                <?php echo isset($package->duration)?$package->duration:0; ?> Days / <?php echo isset($package->duration)?($package->duration - 1):0;  ?> Nights
                               
                               
                                </div>

           </div>
           
               <div class="datediv">           
                

                <div class="clearfix"></div>
                

                <div class="patendiv">
                   <!--  <input type="submit" class="patencls" id="sendquery" value="Send Query" style="width: 150px; float: left; background: #888; border:1px solid #888;" /> -->

                    <form  role="form" id="package"
                                    enctype="multipart/form-data" method="POST" action="<?php echo base_url(); ?>index.php/<?=$contrller?>/book_packages"
                                    autocomplete="off" name="package">
                                    <input type="hidden" name="package_id" value="<?php echo $package->package_id?>" />
                            <input type="submit" class="patencls" id="book" value="Book" style="width: 150px; float: right;" />
                            </form>
                </div>

                </div>
           </div>
         </div>

    

                <div class="col-md-8 col-xs-12 nopad fulatnine">

                    <div class="htladdet light-border">             
                        <span><?php echo $package->package_name ?></span>                             
                        
                        <ul class="locdurdiv">              
                            <!-- <li>Location: Bengaluru, India</li>        -->                         
                          <!--  <li> Duration:<span><?php echo isset($package->duration)?($package->duration - 1):0; ?> Nights / <?php echo isset($package->duration)?$package->duration:0; ?> Days</span>(approx.)</li>                           
                        -->
                          <li> Duration:<span><?php echo isset($package->duration)?$package->duration:0; ?> Days / <?php echo isset($package->duration)?($package->duration - 1):0; ?> Nights</span>(approx.)</li>                           
                        
                        </ul>        
                   </div>

                   <div class="clearfix"></div>

                    <div id="act_sldr">  
                        <div id="hotel_top" class="owl-carousel indexbaner">
                        <?php if(!empty($package)){ ?>
                           <div class="item">
                                <div class="smalbanr"><img src="<?php echo $GLOBALS['CI']->template->domain_upload_pckg_images($package->image); ?>" alt="" /></div>
                          </div>
                        <?php } ?>
                        </div>
                   </div>

                    <div class="clearfix"></div>

                    <div class="masthead-wrapper act_wrp">

                        <div class="col-md-12 actimgdiv">
                            <div class="ovrimpdiv">
                                <div class="tab-content">

                                    <div class="tab-pane active">
                                     <div class="innertabs">
                                      <h3 class="mobile_view_header"><i class="fal fa-list-alt"></i> Overview</h3>
                                      <div class="clearfix"></div>

                                      <div class="lettrfty">
                                         <?php echo isset($package->package_description)?($package->package_description):"No Description"; ?>

                                                                         <div class="linebrk"></div>
<?php 

$tours_data = json_encode($package);
$tourss_itinerary=json_encode($package_itinerary);
$tourss_itinerary_dw=json_encode($package_price_policy);
$tours_price=json_encode($package_cancel_policy);
$tours_data = urlencode($tours_data);
$tourss_itinerary = urlencode($tourss_itinerary);
$tourss_itinerary_dw = urlencode($tourss_itinerary_dw);
$tours_price = urlencode($tours_price);
       ?>
                                 <form method="post" action="<?=base_url().'index.php/voucher/activity_pdf'?>" target="_blank">
                                  <input type="hidden" name="tour_data" value="<?php echo $tours_data?>">
                                  <input type="hidden" name="tours_itinerary" value="<?php echo $tourss_itinerary?>">
                                  <input type="hidden" name="tours_itinerary_dw" value="<?php echo $tourss_itinerary_dw?>">
                                  <input type="hidden" name="tour_price" value="<?php echo $tours_price?>">
                                  <input type="submit" value="Export PDF" class="btn btn-warning pull-right" style='margin-top:20px;'>
                                </form>

                                      </div>

                                     </div>
                                    </div>

                                    <div class="tab-pane active">
                                     <div class="innertabs">
                                      <h3 class="mobile_view_header"><i class="fal fa-list-alt"></i> Detailed Itinerary</h3>
                                      <div class="clearfix"></div>

                                      <div class="lettrfty">
                                          <?php $i=1; ?>
                            <?php foreach($package_itinerary as $pi){ ?>
                            <div class="htlrumrowxl">
                                <div class="hotelistrowhtl">

                                    <div class="daytrip">
                                            <strong>Day</strong>
                                            <b><?php echo $i; ?></b>
                                     </div>

                                    <div class="clear"></div>
                                    <div class="dayecd">
                                     <div class="img_tour">
                                         <img src="<?php echo $GLOBALS['CI']->template->domain_upload_pckg_images($pi->itinerary_image); ?>" alt="" />
                                    </div>
                                        <div class="hotelhed"><?php echo $pi->place; ?></div>
                                        <span class="singleadrspara"><?php echo $pi->itinerary_description; ?></span>
                                    </div>

                                </div>

                            </div>
                            <?php $i++; ?>
                            <?php } ?>
                                      </div>

                                     </div>
                                    </div>


                                    <div class="tab-pane active">
                                     <div class="innertabs">
                                      <h3 class="mobile_view_header"><i class="fal fa-list-alt"></i> Terms & Conditions</h3>
                                      <div class="clearfix"></div>

                                      <div class="lettrfty">
                                           <div class="col-md-12 nopad">
                                    <div class="col-md-12">
                                    <h3 class="hedft">Price Includes: </h3>
                                    <ul class="checklist checklistxl">
                                        <li><?php echo isset($package_price_policy->price_includes)?($package_price_policy->price_includes):"No Description"; ?></li>
                                    </ul>
                                </div>


                                </div>
                                <div class="linebrk"></div>
                                <div class="col-md-12 nopad">
                                    <div class="col-md-12">
                                    <h3 class="hedft">Price Excludes: </h3>
                                    <ul class="checklist checklistxl">
                                        <li><?php echo isset($package_price_policy->price_excludes)?($package_price_policy->price_excludes):""; ?></li>
                                    </ul>
                                </div>


                                </div>
                                <div class="linebrk"></div>
                                <div class="col-md-12 nopad">
                                    <div class="col-md-12">
                                    <h3 class="hedft">Cancellation Advance: </h3>
                                    <ul class="checklist checklistxl">
                                        <li><?php echo isset($package_cancel_policy->cancellation_advance)?($package_cancel_policy->cancellation_advance):""; ?></li>
                                    </ul>
                                </div>
                                <div class="linebrk"></div>
                                <div class="col-md-12 nopad">
                                    <div class="col-md-12">
                                    <h3 class="hedft">Cancellation penality: </h3>
                                    <ul class="checklist checklistxl">
                                        <li><?php echo isset($package_cancel_policy->cancellation_penality)?($package_cancel_policy->cancellation_penality):""; ?></li>
                                    </ul>
                                </div>

                                </div>
                                </div>
                                      </div>

                                     </div>
                                    </div>

                                   <!-- <div class="tab-pane active">
                                     <div class="innertabs">
                                      <h3 class="mobile_view_header"><i class="fal fa-list-alt"></i> Terms & Conditions</h3>
                                      <div class="clearfix"></div>

                                      <div class="lettrfty">
                                           <div class="col-md-12 nopad">
                                    <div class="col-md-12">
                                    <h3 class="hedft">Price Includes: </h3>
                                    <ul class="checklist checklistxl">
                                        <li><?php echo isset($package_price_policy->price_includes)?($package_price_policy->price_includes):"No Description"; ?></li>
                                    </ul>
                                </div>


                                </div>
                                <div class="linebrk"></div>
                                <div class="col-md-12 nopad">
                                    <div class="col-md-12">
                                    <h3 class="hedft">Price Excludes: </h3>
                                    <ul class="checklist checklistxl">
                                        <li><?php echo isset($package_price_policy->price_excludes)?($package_price_policy->price_excludes):""; ?></li>
                                    </ul>
                                </div>


                                </div>
                                <div class="linebrk"></div>
                                <div class="col-md-12 nopad">
                                    <div class="col-md-12">
                                    <h3 class="hedft">Cancellation Advance: </h3>
                                    <ul class="checklist checklistxl">
                                        <li><?php echo isset($package_cancel_policy->cancellation_advance)?($package_cancel_policy->cancellation_advance):""; ?></li>
                                    </ul>
                                </div>
                                <div class="linebrk"></div>
                                <div class="col-md-12 nopad">
                                    <div class="col-md-12">
                                    <h3 class="hedft">Cancellation penality: </h3>
                                    <ul class="checklist checklistxl">
                                        <li><?php echo isset($package_cancel_policy->cancellation_penality)?($package_cancel_policy->cancellation_penality):""; ?></li>
                                    </ul>
                                </div>

                                </div>
                                </div>
                                      </div>

                                     </div>
                                    </div>
                                    -->

                                    <div class="tab-pane active">
                                     <div class="innertabs">
                                      <h3 class="mobile_view_header"><i class="fal fa-list-alt"></i> Rating</h3>
                                      <div class="clearfix"></div>
                                        

                                         <div class="ratingusr">
                                    <strong><?php echo $package->rating; ?></strong>
                                    <span class="ratingimg"> Star <!-- <img src="/images/user-rating-<?php echo $package->rating; ?>.png" alt="" /> --></span>
                                    <b> User Rating</b>
                                </div>
                                    

                                     </div>
                                    </div>


                                   

                                </div>
                            </div>
                        </div>

                    </div>
 
                   <div class="clearfix"></div>


                    <div class