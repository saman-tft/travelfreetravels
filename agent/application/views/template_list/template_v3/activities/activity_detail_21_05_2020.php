<style type="text/css">
  .date_tr{
    padding-left:34px !important;
  }
</style>
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
    .btn.btn-default.btn-number{
      text-transform: uppercase;
    width: 100%;
    height: 50px;
    font-size: 18px;
    background: #7d7d7d;
    border: 1px solid #7d7d7d;
    font-weight: 400;
    color: #fff;
    line-height: 28px;
    text-align: center;
    /* border: 1px solid transparent; */
    border-radius: 0px;
    background: #006bd7 !important;
    border: 1px solid #006bd7 !important;
    }
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
                                    <input type="hidden" name="booking_amount" value="<?php echo base64_encode($package_price)?>" />
                                    	<div class="credit clearfix">
			
				<div class="credit_item col-md-12 nopad">
					<div class="col-md-6 nopad">
						<p>No of Adults (12+ YRS)</p>
					</div>
				<!-- 	<div class="col-md-6 nopad">
						<select name="no_adults" class="no_adults holyday_selct1 newslterinput" id="no_adults">
							<option>1</option>
							<option>2</option>
							<option>3</option>
							<option>4</option>
							<option>5</option>
							<option>6</option>
							<option>7</option>
							<option>8</option>
							<option>9</option>
							<option>10</option>
						</select> -->
              <div class="col-md-6 nopad">
           <div class="input-group countmore pax-count-wrapper adult_count_div">
              <span class="input-group-btn">
              <button type="button" class="btn btn-default btn-number" data-type="minus" data-field="adult" onclick="manage_adult_count('adult','minus')"> 
              <span class="glyphicon glyphicon-minus"></span>
              </button></span>
              <input type="text" id="no_adults" name="no_adults" class="form-control input-number centertext valid_class pax_count_value" value="1" min="1" max="9" readonly="" onchange="roomcountcalculation()"><span class="input-group-btn">
                <button type="button" class="btn btn-default btn-number" onclick="manage_adult_count('adult','plus')" data-type="plus" data-field="adult"> <span class="glyphicon glyphicon-plus"></span> </button></span> 
           </div>
        
					</div>
				</div>
				<div class="credit_item col-md-12 nopad">
					<div class="col-md-6 nopad">
						<p>No of Child (5-11 YRS)</p>
					</div><!-- 
					<div class="col-md-3 nopad">
						<select name="no_child" class="no_child holyday_selct1 newslterinput" id="no_child">
						<option>0</option>
							<option>1</option>
							<option>2</option>
							<option>3</option>
							<option>4</option>
						</select>
					</div> -->
           <div class="col-md-6 nopad">
           <div class="input-group countmore pax-count-wrapper adult_count_div">
              <span class="input-group-btn">
              <button type="button" class="btn btn-default btn-number" data-type="minus" data-field="adult" onclick="manage_child_count('child','minus')"> 
              <span class="glyphicon glyphicon-minus"></span>
              </button></span>
              <input type="text" id="no_child" name="no_child" class="form-control input-number centertext valid_class pax_count_value" value="0" min="1" max="9" readonly="" onchange="roomcountcalculation()"><span class="input-group-btn">
                <button type="button" class="btn btn-default btn-number" onclick="manage_child_count('child','plus')" data-type="plus" data-field="child"> <span class="glyphicon glyphicon-plus"></span> </button></span> 
           </div>
        </div>
				</div>

				<div class="credit_item col-md-12 nopad">
					<div class="col-md-6 nopad">
						<p>No of Infant (0-5 YRS)</p>
					</div>
					 <div class="col-md-6 nopad">
           <div class="input-group countmore pax-count-wrapper adult_count_div">
              <span class="input-group-btn">
              <button type="button" class="btn btn-default btn-number" data-type="minus" data-field="adult" onclick="manage_infant_count('child','minus')"> 
              <span class="glyphicon glyphicon-minus"></span>
              </button></span>
              <input type="text" id="no_infant" name="no_infant" class="form-control input-number centertext valid_class pax_count_value" value="0" min="1" max="9" readonly="" onchange="roomcountcalculation()"><span class="input-group-btn">
                <button type="button" class="btn btn-default btn-number" onclick="manage_infant_count('infant','plus')" data-type="plus" data-field="infant"> <span class="glyphicon glyphicon-plus"></span> </button></span> 
           </div>
        </div>
				</div>
       <div class="credit_item col-md-12 nopad">
          <div class="col-md-6 nopad">
            <p>Departure Date</p>
          </div>
           <div class="col-md-6 nopad">
           <div class="plcetogo datemark sidebord">
                            <input  type="text" class="form-control b-r-0 date_tr" data-date="true" readonly name="date_of_travel" id="date_of_travel" required=""  placeholder="Date of travel" required />
           </div>
        </div>
        </div>
               <div class="credit_item col-md-12 nopad">
         <span id="err" style="color: red;"></span>
        </div>
			</div>
                            <input type="submit" class="patencls check" id="book" value="Book" style="width: 150px; float: right;" />
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
                        
                        <?php
                        if(!empty($package_traveller_photos))
                        {
                            foreach($package_traveller_photos as $pimg)
                            {
                                ?>
                          <div class="item">
                          <div class="smalbanr"><img src="<?php echo $GLOBALS['CI']->template->domain_upload_pckg_images($pimg->traveller_image); ?>" alt="" /></div>
                          </div>
                                <?php
                            }
                        }
                        ?>
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


                    <div class="detailtab hide">
                        <ul class="nav nav-tabs trul">
                         
                          <li><a href="#gallery" data-toggle="tab">Gallery</a></li>
                           <li><a href="#rating" data-toggle="tab">Rating</a></li>
                        </ul>
                        <div class="tab-content5">

                     
                       

                       

                        <!-- Map-->
                        <!-- <div class="tab-pane" id="gallery">
                          <div class="innertabs">

                                <div id="owl-demobaner1" class="owl-carousel indexbaner">
                                <?php if(!empty($package_traveller_photos)){ ?>
                                    <?php foreach($package_traveller_photos as $ptp){ ?>
                                   <div class="item">
                                        <div class="xlimg"><img class="fixedgalhgt" src="<?php echo $GLOBALS['CI']->template->domain_upload_pckg_images($ptp->traveller_image); ?>" alt="" /></div>
                                  </div>
                                  <?php } ?>
                                <?php } ?>
                                </div>
              </div>
                        </div> -->
                        <!-- Map End-->

                        <!-- Reviews-->
                        <div class="tab-pane" id="rating">
                          <div class="innertabs">
                              <div class="ratingusr">
                                  <strong><?php echo $package->rating; ?></strong>
                                    <span class="ratingimg">Star<!-- <img src="/images/user-rating-<?php echo $package->rating; ?>.png" alt="" /> --></span>
                                    <b>User Rating</b>
                                </div>
                            </div>
                        </div>
                        <!-- Reviews End-->

                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
    </div>
</div>

<div id="subtqry" class="wellme minwnwidth" style="display: none;">
    <div class="popuperror" style="display:none;"></div>
    <div  class="pophed"> Send Enquiry For <?php echo $package->package_name; ?></div>
    <div class="signdiv">
      <form action="<?php echo base_url(); ?>index.php/activities/enquiry" method="post">
        <input type="hidden" class="fulwishxl" id="package_id"  name="package_id" value="<?php echo $package->package_id; ?>"/>
        <div class="rowlistwish">
            <div class="col-md-4">
              <span class="qrylbl">Name<span class="dfman">*</span></span>
            </div>
            <div class="col-md-8">
              <input type="text" class="fulwishxl alpha" id="first_name"  name="first_name" value="<?=@$this->entity_name?>" required/>
              <span id="verificationCodeErr" style="color:red; font-size: small"></span>
            </div>
        </div>
        <div class="rowlistwish">
            <div class="col-md-4">
              <span class="qrylbl"> Contact Number<span class="dfman">*</span> </span>
            </div>
            <div class="col-md-8">
              <input type="text" class="fulwishxl numeric" maxlength='15' id="phone1" name="phone1" value="<?=@$this->entity_phone?>" required/>
              <span id="verificationCodeErr" style="color:red; font-size: small"></span>
            </div>
        </div>
        <div class="rowlistwish">
            <div class="col-md-4">
              <span class="qrylbl"> Email<span class="dfman">*</span> </span>
            </div>
            <div class="col-md-8">
              <input type="email" class="fulwishxl" id="activiest_email" name="email" value="<?=@$this->entity_email?>" required/>
              <span id="verificationCodeErr" style="color:red; font-size: small"></span>
            </div>
        </div>
        <div class="rowlistwish">
            <div class="col-md-4">
              <span class="qrylbl">Departure Place<span class="dfman">*</span> </span>
            </div>
            <div class="col-md-8">
              <input type="text" class="fulwishxl" id="place1" name="place1" required/>
              <span id="verificationCodeErr" style="color:red; font-size: small"></span>
            </div>
        </div>
          <div class="rowlistwish">
              <div class="col-md-4">
                  <span class="qrylbl">Number of Passengers<span class="dfman">*</span> </span>
              </div>
              <div class="col-md-8">
                  <input type="text" class="fulwishxl numeric " id="pax" name="pax" required maxlength="6" />
                  <span id="verificationCodeErr" style="color:red; font-size: small"></span>
              </div>
          </div>
        <div class="rowlistwish">
            <div class="col-md-4">
              <span class="qrylbl">Message<span class="dfman">*</span> </span>
            </div>
            <div class="col-md-8">
              <input type="text" class="fulwishxl" id="message1" name="message1" required/>
              <span id="verificationCodeErr" style="color:red; font-size: small"></span>
            </div>
        </div>


      <div class="clear"></div>
      <div class="downselfom">
      <div class="col-md-10 col-xs-12">
        <div class="col-md-6 col-xs-12 "><input type="cancel"  value="Cancel" class="btn colorcancel closequery" id="hideCancelPopup" readonly/></div>
        <div class="col-md-6 col-xs-12"><input type="submit"  value="Send Enquiry" class="btn colorsave" id="send_enquiry_button"/></div>
        </div>
      </div>
      </form>
    </div>
</div>

 <?php 
   //debug($tour_data);exit();
  $start_date=$package->start_date;
  $expire_date=$package->end_date;

  ?>
  <input type="hidden" id="strt" value="<?=$start_date?>" name="strt">
  <input type="hidden" id="expr" value="<?=$expire_date?>" name="ex">


<!--<script src="<?php echo $GLOBALS['CI']->template->template_js_dir('jquery.masonry.min.js') ?>" type="text/javascript"></script> -->
<!-- <?php //debug($package);exit(); ?> -->

<script type="text/javascript">

$(document).ready(function(){
    $("#hotel_top").owlCarousel({
        items : 1,
        itemsDesktop : [1000,1],
        itemsDesktopSmall : [900,1],
        itemsTablet: [600,1],
        itemsMobile : [479,1],
        navigation : false,
        pagination : true,
        autoPlay : true
      });

    $("#owl-demobaner1").owlCarousel({
        items : 1,
        itemsDesktop : [1000,1],
        itemsDesktopSmall : [900,1],
        itemsTablet: [600,1],
        itemsMobile : [479,1],
        navigation : false,
        pagination : true,
        autoHeight : true,
        autoPlay : true
      });

    $('#sendquery').on('click', function(e) {
        e.preventDefault();

        $('#subtqry').provabPopup({
                    modalClose: true,
                    closeClass: 'closequery',
                    zIndex: 100005
        });
    });

});</script>

<script type="text/javascript">
$(document).ready(function(){
    $( ".detailtab" ).tabs();
//  Check Radio-box
    $(".rating input:radio").attr("checked", false);
    $('.rating input').click(function () {
        $(".rating span").removeClass('checked');
        $(this).parent().addClass('checked');
    });

    $('input:radio').change(
    function(){

        var userRating = this.value;
        var pkg_id=$('#pkg_id').val();

        //alert(userRating);
        //alert(pkg_id);
       var str=pkg_id+','+userRating;
        $.ajax({
            url:app_base_url+'activities/package_user_rating',
            type:'POST',
            data:'rate='+str,
            success:function(msg){
                //alert(msg);
               $('#msg_pak').show();
               $('#msg_pak').text('Thank you for rating this package').css('color','green').fadeOut(3000);
            },
            error:function(){
            }
         }) ;

    });

});


</script>

<script type="text/javascript">
    $(document).ready(function(){


        if('<?php echo @$current_user_details['phone_code']?>'){
            $("#pn_country_code").val('<?php echo @$current_user_details['phone_code']."_".@$current_user_details['phone_country']." ".$current_user_details['phone_code'];?>');
        }
        var sync1 = $("#sync1");
        var sync2 = $("#sync2");
        sync1.owlCarousel({
            singleItem : true,
            slideSpeed : 1000,
            navigation: true,
            pagination:false,
            afterAction : syncPosition,
            responsiveRefreshRate : 200,
        });

        sync2.owlCarousel({
            items : 4,
            itemsDesktop  : [1199,4],
            itemsDesktopSmall  : [979,4],
            itemsTablet  : [768,4],
            itemsMobile  : [479,2],
            pagination:false,
            responsiveRefreshRate : 100,
            afterInit : function(el){
                el.find(".owl-item").eq(0).addClass("synced");
            }
        });

        function syncPosition(el){
            var current = this.currentItem;
            $("#sync2")
                .find(".owl-item")
                .removeClass("synced")
                .eq(current)
                .addClass("synced")
            if($("#sync2").data("owlCarousel") !== undefined){
                center(current)
            }

        }

        $("#sync2").on("click", ".owl-item", function(e){
            e.preventDefault();
            var number = $(this).data("owlItem");
            sync1.trigger("owl.goTo",number);
        });

        function center(number){
            var sync2visible = sync2.data("owlCarousel").owl.visibleItems;

            var num = number;
            var found = false;
            for(var i in sync2visible){
                if(num === sync2visible[i]){
                    var found = true;
                }
            }

            if(found===false){
                if(num>sync2visible[sync2visible.length-1]){
                    sync2.trigger("owl.goTo", num - sync2visible.length+2)
                }else{
                    if(num - 1 === -1){
                        num = 0;
                    }
                    sync2.trigger("owl.goTo", num);
                }
            } else if(num === sync2visible[sync2visible.length-1]){
                sync2.trigger("owl.goTo", sync2visible[1])
            } else if(num === sync2visible[0]){
                sync2.trigger("owl.goTo", num-1)
            }
        }
        $('#send_enquiry_button').click(function() { 
            $first_name    = $('#first_name').val();
            $phone   = $('#phone1').val();
            $email  = $('#activiest_email').val(); 
            $place = $('#place1').val(); 
            $pax  = $('#pax').val();
            $message  = $('#message1').val(); 

            $package_id = "<?php echo $package->package_id; ?>";
            

            if($first_name==''  || $phone==''|| $email=='' || $place=='' || $pax==''|| $message=='') 
            {  
              return false;
            }
            // $.post('<?=base_url();?>index.php/tours/ajax_enquiry_activities', {'first_name': $first_name,'phone': $phone,'email': $email,'place':$place,'pax': $pax,'message': $message,'package_id':$package_id}, function(data) {
            //     $('#enquiry_form').trigger("reset");
            //     $('#alertp').removeClass('hide');
            //     $('#enquiry_form').addClass('hide');
            // });
        });
        // $('#send_enquiry_button').click(function() {
        //     $title    = $('#title').val();
        //     $ename    = $('#ename').val();
        //     $elname   = $('#elname').val();
        //     $emobile  = $('#emobile').val();
        //     $eemail   = $('#eemail').val();
        //     $ecomment = $('#ecomment').val();
        //     $tour_id  = $('#tour_id').val();
        //     $number_of_passengers  = $('#number_of_passengers').val();
        //     $durations  = $('#durations').val();
        //     $departure_date  = $('#departure_date').val();

        //     $pn_country_code  = $('#pn_country_code').val();
        //     $pn_country_code  =  $pn_country_code.split("_");
        //     $pn_country_code = $pn_country_code[0];
        //     // alert($pn_country_code);

        //     $tours_itinerary_id = $('#tours_itinerary_id').val();
        //     console.log(title);
        //     console.log();
        //     console.log();
        //     console.log();
        //     console.log();

        //     if($ename==''  || $eemail==''|| $number_of_passengers=='' || $departure_date=='') { return false; }
        //     $.post('<?=base_url();?>index.php/tours/ajax_enquiry_activities', {'title': $title,'ename': $ename,'elname': $elname,'pn_country_code':$pn_country_code,'emobile': $emobile,'eemail': $eemail,'ecomment': $ecomment,'tour_id': $tour_id,'tours_itinerary_id': $tours_itinerary_id,'number_of_passengers':$number_of_passengers,'durations':$durations,'departure_date':$departure_date}, function(data) {
        //         $('#enquiry_form').trigger("reset");
        //         $('#alertp').removeClass('hide');
        //         $('#enquiry_form').addClass('hide');
        //     });
        // });

            $('#enquiry_pop_button').click(function() {
            $('#alertp').addClass('hide');
            $('#enquiry_form').removeClass('hide');
        });
    });
</script>
<script>
  
function manage_adult_count(pax_type,cal) 
{
   
 var adult_count = parseInt($('#no_adults').val());
    var count=0;
     if((pax_type == 'adult') && (cal=='plus'))
     { 
          if(adult_count >9)
          {
            return false;
          }

        count=adult_count+1; 
        $('#no_adults').val(""+count);
     }
     else
     {

      if(adult_count <=1)
          {
            return false;
          }

        count=adult_count-1; 
        $('#no_adults').val(""+count);
     }

}


function manage_child_count(pax_type,cal) 
{
   
 var no_child = parseInt($('#no_child').val());
    var count=0;
     if((pax_type == 'child') && (cal=='plus'))
     { 
          if(no_child >3)
          {
            return false;
          }

        count=no_child+1; 
        $('#no_child').val(""+count);
     }
     else
     {

      if(no_child <=0)
          {
            return false;
          }

        count=no_child-1; 
        $('#no_child').val(""+count);
     }

}

function manage_infant_count(pax_type,cal) 
{
   
 var no_infant = parseInt($('#no_infant').val());
    var count=0;
     if((pax_type == 'infant') && (cal=='plus'))
     { 
          if(no_infant >3)
          {
            return false;
          }

        count=no_infant+1; 
        $('#no_infant').val(""+count);
     }
     else
     {

      if(no_infant <=0)
          {
            return false;
          }

        count=no_infant-1; 
        $('#no_infant').val(""+count);
     }

}


$(document).ready(function() {
$("#date_of_travel").datepicker({
           dateFormat:'dd-mm-yy',
           minDate: "<?=date("d-m-Y",strtotime($package->start_date))?>",
           maxDate: "<?=date("d-m-Y",strtotime($package->end_date))?>",
           });
           });
          // maxDate: <?php echo $actual_date;?>
});

</script>
