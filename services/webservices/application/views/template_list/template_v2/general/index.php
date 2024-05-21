<link href="<?php echo $GLOBALS['CI']->template->template_css_dir('index_style.css'); ?>" rel="stylesheet">
<?php
$active_domain_modules = $this->active_domain_modules;
$default_active_tab = $default_view;
//echo "<pre>"; print_r($holiday_data);exit;

/**
 * set default active tab
 * @param string $module_name		 name of current module being output
 * @param string $default_active_tab default tab name if already its selected otherwise its empty
 */
function set_default_active_tab($module_name, &$default_active_tab)
{
	if (empty($default_active_tab) == true || $module_name == $default_active_tab) {
		if (empty($default_active_tab) == true) {
			$default_active_tab = $module_name; // Set default module as current active module
		}
		return 'active';
	}
}
?>

<link href="<?php echo $GLOBALS['CI']->template->template_css_dir('jquery-ui.css'); ?>" rel="stylesheet">
<link href="<?php echo $GLOBALS['CI']->template->template_css_dir('backslider.css'); ?>" rel="stylesheet">
<link href="<?php echo $GLOBALS['CI']->template->template_css_dir('animate.css'); ?>" rel="stylesheet">


<section class="banner_outer">
  <div class="container pos_rel">
    <div class="banner_content">
      <div class="text_slider wow fadeInDown">
        <div id="banner_text" class="owl-carousel owl-theme">
          <div class="item">
            <h1>GET INSPIRED FOR YOUR NEXT VACATION WITH xenium</h1>
          </div>
          <div class="item">
            <h1>GET INSPIRED FOR YOUR NEXT VACATION WITH xenium</h1>
          </div>
          <div class="item">
            <h1>GET INSPIRED FOR YOUR NEXT VACATION WITH xenium</h1>
          </div>
        </div>
      </div>
      <div class="col-md-12 nopad search-engine">
        <ul class="nav nav-tabs col-md-6 nopad mar_auto">
            
          <?php if (in_array(META_ACCOMODATION_COURSE, $active_domain_modules)) { ?>  
          <li class="<?php echo set_default_active_tab(META_ACCOMODATION_COURSE, $default_active_tab)?>"><a data-toggle="tab" href="#hotel_tab">Hotel</a></li>
          <?php  }  ?>
          <?php if (in_array(META_AIRLINE_COURSE, $active_domain_modules)) { ?>
          <li class="<?php echo set_default_active_tab(META_AIRLINE_COURSE, $default_active_tab)?>"><a data-toggle="tab" href="#flight_tab">Flight</a></li>
          <?php }  ?>
          <?php if (in_array(META_PACKAGE_COURSE, $active_domain_modules)) { ?>
          <li  class="<?php echo set_default_active_tab(META_PACKAGE_COURSE, $default_active_tab)?>"><a data-toggle="tab" href="#sight_tab">Sightseeing</a></li>
          <?php } ?>
          <?php if (in_array(META_BUS_COURSE, $active_domain_modules)) { ?>
          <li class="<?php echo set_default_active_tab(META_BUS_COURSE, $default_active_tab)?>"><a data-toggle="tab" href="#bus_tab">Bus</a></li>
          <?php } ?>
          
          
        </ul>
        <div class="tab-content">
          <div id="hotel_tab" class="tab-pane fade in <?php echo set_default_active_tab(META_ACCOMODATION_COURSE, $default_active_tab)?>">
            <div class="tab-pane">
              <?php if (in_array(META_ACCOMODATION_COURSE, $active_domain_modules)) { ?>
      
       <?php echo $GLOBALS['CI']->template->isolated_view('share/hotel_search') ?>
     
     <?php } ?>
            </div>
          </div>
          <div id="flight_tab" class="tab-pane fade in <?php echo set_default_active_tab(META_AIRLINE_COURSE, $default_active_tab)?>">
              <div class="tab-pane">
              <?php if (in_array(META_AIRLINE_COURSE, $active_domain_modules)) { ?>
     
        <?php echo $GLOBALS['CI']->template->isolated_view('share/flight_search') ?>

      <?php } ?>    
              </div>
              
          </div>
          <div id="sight_tab" class="tab-pane fade in <?php echo set_default_active_tab(META_PACKAGE_COURSE, $default_active_tab)?>">
            <div class="col-md-12">
                <div class="tab-pane">
                    <?php if (in_array(META_PACKAGE_COURSE, $active_domain_modules)) { ?>
     
       <?php echo $GLOBALS['CI']->template->isolated_view('share/holiday_search',$holiday_data) ?>
     
     <?php } ?>
                </div>
            </div>
          </div>
          
          <div id="bus_tab" class="tab-pane fade in <?php echo set_default_active_tab(META_BUS_COURSE, $default_active_tab)?>">
            <div class="col-md-12">
                <div class="tab-pane">
                    <?php if (in_array(META_BUS_COURSE, $active_domain_modules)) { ?>
    
     	<?php echo $GLOBALS['CI']->template->isolated_view('share/bus_search',$holiday_data) ?>
    
     <?php } ?>
                </div>
            </div>
            
            
            
          </div>
          
        </div>
      </div>
    </div>
    
    
  </div>
</section>


<section class="advc_section">
	<div class="container">
    	<h2>Advice For Everyone</h2>
        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p>
    <div class="servc_boxes">
    	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
        	<i class="sprite rtslct_icn wow flipOutY"></i>
            <h3>Route Selection</h3>
            <p>Mauris fermentum tortor non enim aliquet condimentum. Nam aliquam pretium duis sem feugiat.</p>
        </div>
        
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
        	<i class="sprite suprt_icn wow flipOutY"></i>
            <h3>24/7 Support</h3>
            <p>Mauris fermentum tortor non enim aliquet condimentum. Nam aliquam pretium duis sem feugiat.</p>
        </div>
        
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
        	<i class="sprite widechc_icn wow flipOutY"></i>
            <h3>Wide Choice</h3>
            <p>Mauris fermentum tortor non enim aliquet condimentum. Nam aliquam pretium duis sem feugiat.</p>
        </div>
        
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
        	<i class="sprite buytckt_icn wow flipOutY"></i>
            <h3>Buy Tickets</h3>
            <p>Mauris fermentum tortor non enim aliquet condimentum. Nam aliquam pretium duis sem feugiat.</p>
        </div>
      </div> 
	</div>
</section>
<section class="top_dest_sectn">
	<div class="container">
    	<h4>Top Destinations</h4>
        <div class="clearfix"></div>
        <div class="top_dest_box">
        	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
            	<div class="dest_list_box">
            	<figure class="dest_img_box">
               	 <img src="<?php echo $GLOBALS['CI']->template->template_images('top_dest_1.png'); ?>" alt=""/>
                 	<i class="fa fa-plus-square-o dest_img_over"></i>
                </figure>
                <figcaption>
               	  <h3>Paris</h3>
                    <p>(990 PLACES)</p>
                  <button class="btn topd_btn"><i class="fa fa-chevron-right"></i></button>
              </figcaption>
          </div>
          </div>
          
          <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
            	<div class="dest_list_box">
            	<figure class="dest_img_box">
               	 <img src="<?php echo $GLOBALS['CI']->template->template_images('top_dest_2.png'); ?>" alt=""/>
                 	<i class="fa fa-plus-square-o dest_img_over"></i>
                </figure>
                <figcaption>
               	  <h3>London</h3>
                    <p>(990 PLACES)</p>
                  <button class="btn topd_btn"><i class="fa fa-chevron-right"></i></button>
              </figcaption>
          </div>
          </div>
          <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
            	<div class="dest_list_box">
            	<figure class="dest_img_box">
               	 <img src="<?php echo $GLOBALS['CI']->template->template_images('top_dest_3.png'); ?>" alt=""/>
                 	<i class="fa fa-plus-square-o dest_img_over"></i>
                </figure>
                <figcaption>
               	  <h3>New York</h3>
                    <p>(990 PLACES)</p>
                  <button class="btn topd_btn"><i class="fa fa-chevron-right"></i></button>
              </figcaption>
          </div>
          </div>
          <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
            	<div class="dest_list_box">
            	<figure class="dest_img_box">
               	 <img src="<?php echo $GLOBALS['CI']->template->template_images('top_dest_4.png'); ?>" alt=""/>
                 	<i class="fa fa-plus-square-o dest_img_over"></i>
                </figure>
                <figcaption>
               	  <h3>Sydney</h3>
                    <p>(990 PLACES)</p>
                  <button class="btn topd_btn"><i class="fa fa-chevron-right"></i></button>
              </figcaption>
          </div>
          </div>
          
          <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
            	<div class="dest_list_box">
            	<figure class="dest_img_box">
               	 <img src="<?php echo $GLOBALS['CI']->template->template_images('top_dest_5.png'); ?>" alt=""/>
                 	<i class="fa fa-plus-square-o dest_img_over"></i>
                </figure>
                <figcaption>
               	  <h3>Spain</h3>
                    <p>(990 PLACES)</p>
                  <button class="btn topd_btn"><i class="fa fa-chevron-right"></i></button>
              </figcaption>
          </div>
          </div>
          <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
            	<div class="dest_list_box">
            	<figure class="dest_img_box">
               	 <img src="<?php echo $GLOBALS['CI']->template->template_images('top_dest_6.png'); ?>" alt=""/>
                 	<i class="fa fa-plus-square-o dest_img_over"></i>
                </figure>
                <figcaption>
               	  <h3>England</h3>
                    <p>(990 PLACES)</p>
                  <button class="btn topd_btn"><i class="fa fa-chevron-right"></i></button>
              </figcaption>
          </div>
          </div>
          <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
            	<div class="dest_list_box">
            	<figure class="dest_img_box">
               	 <img src="<?php echo $GLOBALS['CI']->template->template_images('top_dest_7.png'); ?>" alt=""/>
                 	<i class="fa fa-plus-square-o dest_img_over"></i>
                </figure>
                <figcaption>
               	  <h3>Rome</h3>
                    <p>(990 PLACES)</p>
                  <button class="btn topd_btn"><i class="fa fa-chevron-right"></i></button>
              </figcaption>
          </div>
          </div>
          <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
            	<div class="dest_list_box">
            	<figure class="dest_img_box">
               	 <img src="<?php echo $GLOBALS['CI']->template->template_images('top_dest_8.png'); ?>" alt=""/>
                 	<i class="fa fa-plus-square-o dest_img_over"></i>
                </figure>
                <figcaption>
               	  <h3>Austrilia</h3>
                    <p>(990 PLACES)</p>
                  <button class="btn topd_btn"><i class="fa fa-chevron-right"></i></button>
              </figcaption>
          </div>
          </div>
          <div class="clearfix"></div>
        </div>
	</div>
</section>
<!-- end explore more -->

<section class="newltr_section">
	<div class="container">
    	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 nwsltr_outr">
        	<div class="col-lg-9"><input id="newsemail" type="email" class="form-control input-lg" placeholder="Enter your email..."></div>
            <div class="col-lg-3"><input id="newsemailbtn" type="submit" class="btn btn-primary btn-lg" value="Submit"></div>
        </div>
        
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 socialmedia_outr">
        	<ul class="list-unstyled socialmedia_icns">
            	<li><a href=""><i class="sprite gpls_icn wow flipOutY"></i></a></li>
                <li><a href=""><i class="sprite insta_icn wow flipOutY"></i></a></li>
                <li><a href=""><i class="sprite ytb_icn wow flipOutY"></i></a></li>
                <li><a href=""><i class="sprite twtr_icn wow flipOutY"></i></a></li>
                <li><a href=""><i class="sprite fb_icn wow flipOutY"></i></a></li>
            </ul>
        </div>
        
    </div>
</section>


<link rel="stylesheet" href="<?php echo  $GLOBALS['CI']->template->template_css_dir('backslider.css'); ?>" />
<link rel="stylesheet" href="<?php echo  $GLOBALS['CI']->template->template_css_dir('backslider2.css');?>" />
<script src="<?php echo $GLOBALS['CI']->template->template_js_dir('backslider.js');?>"></script>
<script src="<?php echo $GLOBALS['CI']->template->template_js_dir('backslider2.js');?>"></script>
        
<script>
 new WOW().init();
 //homepage slide show
$(function($){
	
        $.supersized({
        
          // Functionality
          slide_interval          :   5000,   // Length between transitions
          transition              :   1,      // 0-None, 1-Fade, 2-Slide Top, 3-Slide Right, 4-Slide Bottom, 5-Slide Left, 6-Carousel Right, 7-Carousel Left
          transition_speed    : 700,    // Speed of transition
                                 
          // Components             
          slide_links       : 'blank',  // Individual links for each slide (Options: false, 'num', 'name', 'blank')
          slides :    [{"image":"<?php echo $GLOBALS['CI']->template->template_images('banner_1.png'); ?>"}, {"image":"<?php echo $GLOBALS['CI']->template->template_images('banner_2.png'); ?>"} ]          
        });
        });

//homepage slide show end

$(document).ready(function() {

  $("#banner_text").owlCarousel({
 
      navigation : false, // Show next and prev buttons
      slideSpeed : 300,
      paginationSpeed : 400,
      singleItem:true
 
      // "singleItem:true" is a shortcut for:
      // items : 1, 
      // itemsDesktop : false,
      // itemsDesktopSmall : false,
      // itemsTablet: false,
      // itemsMobile : false
 
  });
  	 $("#trvl_drop_btn").click(function(){
   $("#trvl_drop_drp").html($("#trvl_drop_drp").text() == "-" ? "+" : "-");
$("#trvl_drop_drp_cnts").slideToggle(400);

});


$("#onew-trp").click(function(){
	$(".rndw-outer").hide();
	$(".mutiw-outer").hide();
	$(".onw-outer").show();		
	$( "#onew-trp" ).addClass( "active" );
	$( "#mutl-trp" ).removeClass( "active" );
	$( "#rnd-trp" ).removeClass( "active" );
});


	 $("#rnd-trp").click(function(){
$(".rndw-outer").show();
	$(".mutiw-outer").hide();
	$(".onw-outer").hide();
		$( "#onew-trp" ).removeClass( "active" );
	$( "#mutl-trp" ).removeClass( "active" );
	$( "#rnd-trp" ).addClass( "active" );

});



	 $("#mutl-trp").click(function(){
$(".rndw-outer").hide();
	$(".mutiw-outer").show();
	$(".onw-outer").hide();
		$( "#onew-trp" ).removeClass( "active" );
	$( "#mutl-trp" ).addClass( "active" );
	$( "#rnd-trp" ).removeClass( "active" );

});

	 $("#intrn-src").click(function(){
	$( "#intrn-src" ).addClass( "active" );
	$( "#dmstc-src" ).removeClass( "active" );

});

	 $("#dmstc-src").click(function(){
		 $( "#dmstc-src" ).addClass( "active" );
	$( "#intrn-src" ).removeClass( "active" );
	

});
 
});
</script>
