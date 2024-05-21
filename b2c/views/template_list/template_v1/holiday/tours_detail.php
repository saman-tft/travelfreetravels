<?php
Js_Loader::$css[] = array('href' => $GLOBALS['CI']->template->template_css_dir('owl.carousel.min.css'), 'media' => 'screen');
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('owl.carousel.min.js'), 'defer' => 'defer');
?>
<link  href="<?php echo $GLOBALS['CI']->template->template_css_dir('page_resource/jquery.scrolling-tabs.css') ?>" rel="stylesheet">
<link  href="<?php echo $GLOBALS['CI']->template->template_css_dir('custom_tour.css') ?>" rel="stylesheet">
<link  href="<?php echo $GLOBALS['CI']->template->template_css_dir('custom_sky.css') ?>" rel="stylesheet">
<script src="<?php echo $GLOBALS['CI']->template->template_js_dir('initialize-carousel-detailspage.js') ?>" type="text/javascript"></script> 
<script src="<?php echo $GLOBALS['CI']->template->template_js_dir('jquery.provabpopup.js') ?>" type="text/javascript"></script>
<script src="<?php echo $GLOBALS['CI']->template->template_js_dir('jquery.carouFredSel-6.2.1-packed.js') ?>" type="text/javascript"></script>

<div class="full witcontent  marintopcnt">
  <div class="container">
    	<div class="full">
        <div class="contentpad">

            <div class="fulldetab">

                <div class="col-md-4 col-xs-12 nopadMob fulatnine frtrit">
                <div class="alsidex">
                    <div class="sidebfs">
                        <div id="owl-demobaner" class="owl-carousel indexbaner">
                        <?php if(!empty($package)){ ?>
                           <div class="item">
                                <div class="smalbanr"><img src="<?php echo $GLOBALS['CI']->template->domain_upload_pckg_images($package->image); ?>" alt="" /></div>
                          </div>
                        <?php } ?>
                        </div>
                    </div>

                    <div class="clear"></div>

                    <div class="siddwn">
                        <div class="col-xs-6">
                            <div class="bigpriced"> <strong> <?php echo $currency_obj->get_currency_symbol($currency_obj->to_currency); ?> </strong> <?php echo isset($package->price)?get_converted_currency_value ( $currency_obj->force_currency_conversion ( $package->price ) ):0; ?> Only</div>
                            <span class="snote">*Per Person</span>
                            <div class="sdfr"> <?php echo isset($package->duration)?$package->duration:0; ?> Days / <?php echo isset($package->duration)?($package->duration-1):0; ?> Nights</div>
                        </div>
                        <div class="col-xs-6">
                            <input type="submit" class="booknowhtl" id="sendquery" value="Send Query" />
                        </div>
                    </div>
                 </div>

                  
                     <div class="rating mypacksy mt10 mb10">
                         <label>Rate this package</label>
                        <span class="star"><input type="radio" name="rating" id="str5" value="5"><label for="str5"></label></span>
                        <span class="star"><input type="radio" name="rating" id="str4" value="4"><label for="str4"></label></span>
                        <span class="star"><input type="radio" name="rating" id="str3" value="3"><label for="str3"></label></span>
                        <span class="star"><input type="radio" name="rating" id="str2" value="2"><label for="str2"></label></span>
                        <span class="star"><input type="radio" name="rating" id="str1" value="1"><label for="str1"></label></span>
                     </div>
                    <input type="hidden" id="pkg_id" value="<?php echo $package->package_id?>">
                    
                    <div id="msg_pak"></div>


                </div>
            <div class="clearfix visible-sm-block visible-xs-block"></div>
                <div class="col-md-8 col-xs-12 nopad fulatnine">
                    <div class="detailtab">
                        <ul class="nav nav-tabs trul">
                          <li class="active"><a href="#ovrvw" data-toggle="tab">OverView</a></li>
                          <li class="trooms"><a href="#itnery" data-toggle="tab">Detailed Itinerary</a></li>
                          <li class="tfacility"><a href="#tandc" data-toggle="tab">Terms &amp; Conditions</a></li>
                          <li><a href="#gallery" data-toggle="tab">Gallery</a></li>
                           <li><a href="#rating" data-toggle="tab">Rating</a></li>
                        </ul>
                        <div class="tab-content5">

                       <!-- Over View-->
                        <div class="tab-pane" id="ovrvw">
                        	<div class="innertabsxl">
                            	<div class="comenhtlsum">
                                	<?php echo isset($package->package_description)?($package->package_description):"No Description"; ?>
                                </div>
                                <div class="linebrk"></div>

                            </div>
                        </div>
                        <!-- Over View End-->

                        <!-- Itinerary-->
                        <div class="tab-pane active" id="itnery">
                        	<div class="innertabsxl">
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
                                        <div class="hotelhed"><?php echo $pi->place; ?></div>
                                        <span class="singleadrspara"><?php echo $pi->itinerary_description; ?></span>
                                    </div>

                                </div>

                            </div>
                            <?php $i++; ?>
                            <?php } ?>
                           </div>
                        </div>
                        <!-- Itinerary End-->

                        <!-- Terms & Conditions-->
                        <div class="tab-pane" id="tandc">
                        	<div class="innertabs">
                            	<div class="comenhtlsum">
                                	<?php //echo isset($package->package_description)?($package->package_description):"No Description"; ?>
                                </div>
                                <div class="linebrk"></div>
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
                        <!-- Terms & Conditions End-->

                        <!-- Map-->
                        <div class="tab-pane" id="gallery">
                        	<div class="innertabs">

                                <div id="owl-demobaner1" class="owl-carousel indexbaner">
                                <?php if(!empty($package_traveller_photos)){ ?>
                                    <?php foreach($package_traveller_photos as $ptp){ ?>
                                   <div class="item">
                                        <div class="xlimg"><img src="<?php echo $GLOBALS['CI']->template->domain_upload_pckg_images($ptp->traveller_image); ?>" alt="" /></div>
                                  </div>
                                  <?php } ?>
                                <?php } ?>
                                </div>
							</div>
                        </div>
                        <!-- Map End-->

                        <!-- Reviews-->
                        <div class="tab-pane" id="rating">
                        	<div class="innertabs">
                            	<div class="ratingusr">
                                	<strong><?php echo $package->rating; ?></strong>
                                    <span class="ratingimg"> Star <!-- <img src="/images/user-rating-<?php echo $package->rating; ?>.png" alt="" /> --></span>
                                    <b> User Rating</b>
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
      <form action="" method="post" id="tourenquiry">
        <input type="hidden" class="fulwishxl" id="package_id"  name="package_id" value="<?php echo $package->package_id; ?>"/>
        <span class="success_msg" style="color:#008000;"></span>
        <div class="rowlistwish">
            <div class="col-md-4">
            	<span class="qrylbl">Name<span class="dfman">*</span></span>
            </div>
            <div class="col-md-8">
            	<input type="text" class="fulwishxl alpha" id="first_name"  name="first_name" required/>
            	<span id="verificationCodeErr" style="color:red; font-size: small"></span>
            </div>
        </div>
        <div class="rowlistwish">
            <div class="col-md-4">
            	<span class="qrylbl"> Contact Number<span class="dfman">*</span> </span>
            </div>
            <div class="col-md-8">
            	<input type="text" class="fulwishxl numeric" maxlength='13' id="phone" name="phone" required/>
            	<span id="verificationCodeErr" style="color:red; font-size: small"></span>
            </div>
        </div>
        <div class="rowlistwish">
            <div class="col-md-4">
            	<span class="qrylbl"> Email<span class="dfman">*</span> </span>
            </div>
            <div class="col-md-8">
            	<input type="email" class="fulwishxl"  id="email" name="email" required/>
            	<span id="verificationCodeErr" style="color:red; font-size: small"></span>
            </div>
        </div>
        <div class="rowlistwish">
            <div class="col-md-4">
            	<span class="qrylbl">Departure Place<span class="dfman">*</span> </span>
            </div>
            <div class="col-md-8">
            	<input type="text" class="fulwishxl" id="place" name="place" required/>
            	<span id="verificationCodeErr" style="color:red; font-size: small"></span>
            </div>
        </div>
        <div class="rowlistwish">
            <div class="col-md-4">
            	<span class="qrylbl">Message<span class="dfman">*</span> </span>
            </div>
            <div class="col-md-8">
            	<input type="text" class="fulwishxl" id="message" name="message" required/>
            	<span id="verificationCodeErr" style="color:red; font-size: small"></span>
            </div>
        </div>


      <div class="clear"></div>
      <div class="downselfom">
      <div class="col-md-10 col-xs-12">
        <div class="col-md-6 col-xs-6"><input type="cancel"  value="Cancel" class="btn colorcancel closequery" id="hideCancelPopup" readonly/></div>
        <div class="col-md-6 col-xs-6"><button type="submit"  value="Send Enquiry" class="btn colorsave" id="startCancelProc">Send Enquiry</button></div>
        </div>
      </div>
      </form>
    </div>
</div>



<script src="<?php echo $GLOBALS['CI']->template->template_js_dir('page_resource/jquery.scrolling-tabs.js') ?>" type="text/javascript"></script>


<script type="text/javascript">

$(document).ready(function(){
    $("#owl-demobaner").owlCarousel({
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
            url:app_base_url+'tours/package_user_rating',
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
    
    $('#startCancelProc').click(function (e) {
        e.preventDefault();

        var input_text=$("#email").val();

            var mailformat =/^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/.test(input_text);
            // var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
            
            if(!mailformat)
            {
               alert("Enter Valid email address!");
               return false;
            }


        $.ajax({
            url:app_base_url+'tours/enquiry',
            type:'POST',
            data:$("#tourenquiry").serialize(),
            success:function(msg){

                if(msg.status == true){
                    $('.success_msg').text(msg.message);
                    $('#tourenquiry')[0].reset(); 
                }
            },
            error:function(){
            }
         }) ;

    });
    $('.nav-tabs').scrollingTabs();
});
</script>
</body></html>