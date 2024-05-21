<?php 
    // debug($product_details['modalities']);exit;
 //debug($product_details['ProductName']);exit;
$loading_image = '<div class="spinner"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div><div class="bounce4"></div></div>';
// $last_year_arr =  end($product_details['Product_available_date']);
// $end_year = key($product_details['Product_available_date']);
// $end_year_date = end($last_year_arr);  

// $start_date_arr = reset($product_details['Product_available_date']);

// $star_month_year = key($product_details['Product_available_date']);  

// $start_date_picker = reset($start_date_arr); 

// $starting_month_date = $star_month_year.'-'.$start_date_picker;
// $end_yr_month_date=  $end_year.'-'.$end_year_date;

// $end_month_date = date('Y-m-d',strtotime($end_yr_month_date));

// $stop_date = date('Y-m-d', strtotime($product_details['to_date'] . ' +1 day'));

// $period = new DatePeriod(
//  new DateTime($product_details['from_date']),
//  new DateInterval('P1D'),
//  new DateTime($stop_date)
// );

Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/sightseeing_details_slider.js'), 'defer' => 'defer');
Js_Loader::$css[] = array('href' => $GLOBALS['CI']->template->template_css_dir('owl.carousel.min.css'), 'media' => 'screen');
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('owl.carousel.min.js'), 'defer' => 'defer'); 
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/pax_count.js'), 'defer' => 'defer'); 

 //debug($product_details);
 // exit;
//$product_available_date =$product_details['Product_available_date'];
?>

<div class="outactdiv sghtseen">
  <div class="container-fluid">
    <div class="col-xs-12 nopad">
      <div class="org_row">
       <div class="col-md-12 nopad"> 
        <div class="col-md-8">
           <form method="POST" action="<?php echo base_url().'index.php/activity/booking'?>">
          <div class="col-md-12 nopad">
            <div class="htladdet light-border">
             <span><?=$product_details['ProductName']?></span>
             <?php
             if(empty($product_details['ReviewCount']))
             {
              $product_details['ReviewCount'] = 0;
            }
            $current_hotel_rate = round($product_details['StarRating']);
                //echo $current_hotel_rate;
            ?>

            <ul class="locdurdiv">
              <li>Location: <?=$product_details['Location']?></li>
              <?php if($product_details['Duration']):?>
                <li> Duration:<span><?=$product_details['Duration']?></span>(approx.)</li>
              <?php endif;?>
            </ul>

          </div>
          <div class="clearfix"></div>
          <?php 
          $a=4;
          $b=4;
          $show_picture='style=display:none';
          $show_video ='style=display:none';
          if($product_details['Product_Video']){
           $show_video='';
           $show_picture='style=display:none';
         }else{               
           $show_picture='';
         }
         ?>
         

         <div id="actvdeo" <?=$show_video?> >
          <video width="100%" controls>
            <source src="<?=$product_details['Product_Video']?>" type="video/mp4">
              Your browser does not support HTML5 video.
            </video>  
          </div>
          
          <div class="more_pic" <?=$show_video?>><i class="far fa-images"></i> See More Photos
          </div> 

          <div id="act_sldr" <?=$show_picture?> >
           <div id="hotel_top" class="owl-carousel owl-theme act_carsl owl-theme">                                                            

            <?php if(isset($product_details['ProductPhotos'])):?> 
              <?php if($product_details['ProductPhotos']):?>
                <?php foreach($product_details['ProductPhotos'] as $photos):?>     

                  <div class="item">                                            
                    <img src="<?=$photos['photoHiResURL']?>" alt="<?=@$photos['caption']?>"> 
                  </div>
                <?php endforeach;?>
                <?php else:?>
                 <div class="item">                  
                  <img src="<?php echo $GLOBALS['CI']->template->template_images('no_image_available.jpg'); ?>" alt="Image"> 
                </div>
              <?php endif; ?>
            <?php endif; ?>                             
          </div> 
        </div>              
        <div class="more_vdeo" <?=$show_video?> ><i class="far fa-images"></i> See Video
        </div> 
        <div class="masthead-wrapper act_wrp">

         <div class="col-md-12 actimgdiv">
           <div id="holidaySliderone" class="holiySlide hide">
            <?php if(isset($product_details['ProductPhotos'])):?>

              <?php foreach($product_details['ProductPhotos'] as $photos):?>                      
                <div class="item">
                 <img src="<?=$photos['photoHiResURL']?>" alt="<?=@$photos['caption']?>"/>
               </div>
             <?php endforeach;?>
           <?php endif; ?>
         </div>

         <div class="clearfix"></div>
         <div class="basic_info">
          <h4>Important Information</h4>
          <ul class="list-inline">
            <?php if($product_details['Cancellation_available']):?>
             <!--  <li><img src="<?php echo $GLOBALS['CI']->template->template_images('free_cancellation.svg'); ?>" alt="icon" />Free Cancelation - <?=$product_details['Cancellation_day']?> days prior</li> -->
             <?php else:?>
              <!-- <li><img src="<?php echo $GLOBALS['CI']->template->template_images('non-refundable.svg'); ?>" alt="icon" />Non Refundable</li> -->
            <?php endif;?>
            <?php if($product_details['VoucherOption']):?>
              <li><img src="<?php echo $GLOBALS['CI']->template->template_images('show_mobile.svg'); ?>" alt="icon" /> <?=$product_details['VoucherOption']?></li>
            <?php endif;?>
            <?php if($product_details['Duration']):?>
              <li><img src="<?php echo $GLOBALS['CI']->template->template_images('duration.svg'); ?>" alt="icon" /><?php echo $product_details['Duration']?> Duration</li>
            <?php endif;?>
             <li><img src="<?php echo $GLOBALS['CI']->template->template_images('join_group.svg'); ?>" alt="icon" /> Totall Pax - <b style="color:#0096ce"><?= $product_details['search_param']['data']['adult']+$product_details['search_param']['data']['child'] ?></b></li> 
             <input type="hidden" name="total_pax" value="<?= $product_details['search_param']['data']['adult']+$product_details['search_param']['data']['child'] ?>">
             <input type="hidden" name="no_of_adult" value="<?= $product_details['search_param']['data']['adult']?>">
             <input type="hidden" name="no_of_child" value="<?= $product_details['search_param']['data']['child']?>">
     
           <li><img src="<?php echo $GLOBALS['CI']->template->template_images('meet_up.svg'); ?>" alt="icon" /> Meet Up At Location</li>
         </li>
       </ul>
     </div>
     <div class="clearfix"></div>


     <div class="col-md-12 col-xs-12 nopad">
       <div class="ovrimpdiv">
         <ul class="nav nav-pills hide">
           <li class="active full_mobile"><a data-toggle="pill" href="#home"><i class="fal fa-list-alt"></i> Overview</a></li>
           <li class="full_mobile"><a data-toggle="pill" href="#menu1"><i class="fal fa-info-circle"></i> Important Info</a></li>
           <li class="full_mobile"><a data-toggle="pill" href="#menu2"><i class="fal fa-smile"></i> Review</a></li>
         </ul>

         <div class="tab-content">
           <div id="home" class="tab-pane active">
             <div class="innertabs">
               <h3 class="mobile_view_header"><i class="fal fa-list-alt"></i> Overview</h3>
               <div class="clearfix"></div>
               <div class="lettrfty short-text new_ico">
                <ul class="nopad">
                  <?php if($product_details['Duration']):?>
                    <li class="list-unstyled">
                      <label><i class="fa fa-clock"></i> Duration</label>
                      <span><?php echo $product_details['Duration']?></span>
                    </li>
                  <?php endif;?>

                  <li class="list-unstyled">
                    <label><i class="fa fa-language"></i> Language</label>
                    <span>English</span>
                  </li>
                  <?php if($product_details['Type']):?>
                    <li class="list-unstyled">
                      <label><i class="fa fa-file"></i> Type</label>
                      <span><?php echo $product_details['Type']?></span>
                    </li>
                  <?php endif;?>
                  <?php if($product_details['other_activity']):?>
                    <li class="list-unstyled">
                      <label><i class="fa fa-binoculars"></i> Other Activities</label>
                      <span><?php echo $product_details['other_activity']?></span>
                    </li>
                  <?php endif;?>
                  <li class="list-unstyled">
                    <label><i class="fa fa-ticket"></i> Features</label>
                    <span>TICKET Includes : TICKET</span>
                  </li>
                </ul>

              </div>
              <div class="show-more">
                <a href="javascript:void(0);">Show More +</a>
              </div>
            </div>
          </div>


          <div id="menu1" class="tab-pane">

           <div class="innertabs">
             <h3 class="mobile_view_header"><i class="fal fa-info-circle" aria-hidden="true"></i>&nbsp;Details</h3>
             <div class="clearfix"></div>
             <div class="lettrfty short-text">
              <!-- <h3><i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp;Details</h3> -->
              <div class="cms-content mtm"> 
                <p>Description : <?=$product_details['ShortDescription']?></p>

                <?php if(isset($product_details['Itinerary'])):?>
                  <?php if($product_details['Itinerary']):?>
                    <div>
                     <div class="strong">Itinerary</div>
                     <p><?=$product_details['Itinerary']?></p>
                   </div>
                 <?php endif;?>
               <?php endif;?>
               <?php if(isset($product_details['Inclusions'])): ?>
                 <?php if($product_details['Inclusions']):?>
                  <div class="mvm">                      
                    <div class="strong">Inclusions</div>
                    <ul>
                      <?php foreach($product_details['Inclusions'] as $include):?>  <li><?=$include?></li>
                    <?php endforeach;?> 
                  </ul>                        
                </div> 
              <?php endif;?>
            <?php endif;?>
            <?php if(isset($product_details['Exclusions'])): ?>
              <?php if($product_details['Exclusions']):?>
                <div class="mvm">                      
                  <div class="strong">Exclusions</div>
                  <ul>
                    <?php foreach($product_details['Exclusions'] as $exclude):?>  <li><?=$exclude?></li>
                  <?php endforeach;?> 
                </ul>                        
              </div>
            <?php endif;?> 
          <?php endif;?>
          <?php if(isset($product_details['SalesPoints'])): ?>
            <?php if($product_details['SalesPoints']): ?>
              <div class="mvm">                      
                <div class="strong">HighLights</div>
                <ul>
                  <?php foreach($product_details['SalesPoints'] as $sales):?>  <li><?=$sales?></li>
                <?php endforeach;?> 
              </ul>                        
            </div> 
          <?php endif;?>
        <?php endif;?>

        <?php if(isset($product_details['TermsAndConditions'])): ?>
          <?php if($product_details['TermsAndConditions']):?>
            <div class="mvm">                      
              <div class="strong">TermsAndConditions</div>
              <ul>
                <?php foreach($product_details['TermsAndConditions'] as $terms):?>  <li><?=$terms?></li>
              <?php endforeach;?> 
            </ul>                        
          </div> 
        <?php endif;?>
      <?php endif;?>
      <?php if(isset($product_details['Highlights'])): ?>
        <?php if($product_details['Highlights']):?>
          <div class="mvm">                      
            <div class="strong">Highlights</div>
            <ul>
              <?php foreach($product_details['Highlights'] as $hight):?>  <li><?=$hight?></li>
            <?php endforeach;?> 
          </ul>                        
        </div> 
      <?php endif;?>
    <?php endif;?>

    <?php if(isset($product_details['AdditionalInfo'])): ?>
      <?php if($product_details['AdditionalInfo']): ?>
        <div class="mvm">                      
          <div class="strong">AdditionalInfo</div>
          <ul>
            <?php foreach($product_details['AdditionalInfo'] as $info):?>  <li><?=$info?></li>
          <?php endforeach;?> 
        </ul>                        
      </div>
    <?php endif;?>
  <?php endif;?>
</div> 

<div class="clearfix"></div>  </div>
<div class="show-more">
  <a href="#">Show More +</a>
</div>
</div>                           
</div>
<div id="menu2" class="tab-pane">

</div>
</div>
</div>
</div>


</div>
</div>
</div>
</div>
<div class="col-md-4">
   <div class="frmdiv">
     <div class="booking-price">
       <span class="price-from">Best Price From </span>
       <span class="price-amount price-amount-l">
         <span class="currency-sign"><?php echo $currency_obj->get_currency_symbol($currency_obj->to_currency); ?></span>
         <span class="currency-amount"><?=$product_details['Price']['TotalDisplayFare']?></span>
       </span>
     </div>

     <div class="datediv">           
      <div class="clearfix"></div>
      <input type="hidden" name="search_id" value="<?= $product_details['search_id'] ?>">
      <input type="hidden" name="max_count" value="<?=$product_details['MaxTravellerCount']?>">
      <input type="hidden" name="all_passenger_name_req" value="<?=$product_details['AllTravellerNamesRequired']?>">

      <div class="clearfix"></div>
      <div class="spc_list">
        <div class="pickup_div" style="display: block !important;">
          <label>Pick Up Location</label>
          <select name="pickup_location"  class="select_pickup" required >
            <option value="">Choose Pick Up Location</option>
            <?php foreach ($product_details['modalities'] as $key => $value) { 
                // debug($product_details['modalities'][$key]['rate_key']);exit();
              ?>
              <option data-role="<?= $key ?>" value="<?= $product_details['modalities'][$key]['name']."||".$product_details['modalities'][$key]['rate_key'] ?>"><?= $product_details['modalities'][$key]['name'] ?></option>
            <?php } ?>
          </select>
           <input type="hidden" name="vat" value="<?=base64_encode($product_details['Price']['_Markup'])?>">
          <input type="hidden" name="modalities" class="modalities" value=""/>
        </div>

        <div class="activity_date_div hide">
         <label>Select Date</label>

         <?php foreach ($product_details['modalities'] as $mkey => $value) { ?>
           <select name="activity_date" class="select_date select_date_<?=  $mkey ?> hide" required>
            <option value="">Choose Activity Date</option>
            <?php foreach($product_details['modalities'][$mkey]['available_dates'] as $rkey => $value) { 
              ?>

              <option  data-role="<?php echo "Cancelation from ".$product_details['modalities'][$mkey]['cancellation_date'][$rkey]." will charge ".$currency_obj->get_currency_symbol($currency_obj->to_currency).$product_details['Price']['TotalDisplayFare']; ?>" value="<?= $value ?>"><?= $value ?></option>
            <?php } ?>
          </select>
        <?php  } ?>
        <input type="hidden" name="from_date" value="<?=$product_details['from_date']?>">
        <input type="hidden" name="to_date" value="<?=$product_details['to_date']?>">
        <input type="hidden" name="cancel_policy" class="cancel_policy" value="">
      </div>

      <ul> 
       <li class="text-danger cancel_err_li hide"><i class="fa fa-times-circle"></i> <span class="cancel_err_msg">Cancellation from 24-01-2019 Will charge <?php echo $currency_obj->get_currency_symbol($currency_obj->to_currency); ?> 1096.31</span></li>
       <ul> 

        <ul> 
          <?php if($product_details['from_date']):?>
            <li><i class="fas fa-clock"></i> Earliest available date: <?=date('d M Y',strtotime($product_details['from_date']))?></li>
          <?php endif;?>
          <li><i class="fas fa-thumbs-up"></i> Instant Confirmation</li>  

                <!-- <?php if($product_details['Cancellation_available']):?>
                    <li><img src="<?php echo $GLOBALS['CI']->template->template_images('free_cancellation.svg'); ?>" alt="icon" />Free Cancelation - <?=$product_details['Cancellation_day']?> days prior</li>
                    <?php else:?>
                      <li><img src="<?php echo $GLOBALS['CI']->template->template_images('non-refundable.svg'); ?>" alt="icon" />Non Refundable</li>
                      <?php endif;?> -->

                      <!--   <li><img src="<?php echo $GLOBALS['CI']->template->template_images('free_cancellation.svg'); ?>" alt="icon" />Free Cancelation - <?=$product_details['Cancellation_day']?> days prior</li>  -->      

                      <?php if($product_details['Promotion']):?>
                        <li><i class="fas fa-gift"></i> Special Offer: <span><?=$product_details['Promotion']?></span></li>
                      <?php endif;?>
                    </ul>
                  </div>

                  <div class="patendiv">
                    <input type="hidden" name="product_details" value="<?= base64_encode(json_encode($product_details))?>" >
                    <button type="submit" class="patencls" id="bk_now">Book Now</button>

                    <?php
                    if(isset($params['enquiry_origin']) && $params['enquiry_origin'] != "") { ?>
                      <br><br>
                      <a class="b-btn bookallbtn map_to_enquiry" data-activity_data="<?php echo serialized_data($product_details); ?>" data-enquiry_id="<?php echo $params['enquiry_origin']; ?>">Book Later</a>
                      <?php
                    } ?>
                  </div>
                
                </div>
              </div>
            </div>
          </div>
        </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function(){

      $('.map_to_enquiry').on('click',function(){
      var sight_seeing_param = $(this).data('activity_data');
      var enquiry_id = $(this).data('enquiry_id');
      $.ajax({
          type    : 'POST',
          url     : '<?php echo base_url();?>index.php/ajax/map_sightseeing_to_enquiry',
          data    :{sight_seeing_param:sight_seeing_param,enquiry_id:enquiry_id},
          dataType: 'json',
          success : function(res){
           if(res == 'success'){
            alert('Sightseeing successfully mapped against enquiry...');
           }else{
            alert('Some error occured, Please try again...');
           }
          }
      });
    });

      $('.select_pickup').val("");
      $("#total_pax_count").val(1);
      var total_pax =$("#total_pax_count").val();
      var text_str = 'Traveller';
      if(parseInt(total_pax)>1){
        text_str = 'Travellers';
      }
      $("#travel_text").text("Total "+text_str+" "+total_pax);

      $(".more_vdeo").hide();
      $(".more_pic").click(function(){
        $("#act_sldr").show();
        $(".more_vdeo").show();
        $("#actvdeo").hide();
        $(".more_pic").show();
        //alert();
      });
      $(".more_vdeo").click(function(){
        $("#actvdeo").show();
        $(".more_pic").show();
        $("#act_sldr").hide();
        $(".more_vdeo").hide();
      });

      $("#pax_done_btn").click(function(e){
        e.preventDefault();
        var total_pax =$("#total_pax_count").val();
        var text_str = 'Traveller';
        if(parseInt(total_pax)>1){
          text_str = 'Travellers';
        }

        $("#travel_text").text("Total "+text_str+" "+total_pax);
      });

/*      $("#bk_now").click(function() {
        $('html, body').animate({
          scrollTop: $("#prc_rght").offset().top
        }, 700);
        $("#prc_rght").addClass('opc');
        //$("#activitydate").datepicker('show');
      });*/
      $(".show-more a").on("click", function() {
        console.log(32);
        var $link = $(this);
        var $content = $link.parent().prev("div.lettrfty");
        var linkText = $link.text();
        $content.toggleClass("short-text, full-text");
        $link.text(getShowLinkText(linkText));
        return false;
      });
      function getShowLinkText(currentText) {
        var newText = '';
        if (currentText.toUpperCase() === "SHOW MORE +") {
          newText = "Show Less -";
        } else {
          newText = "Show More +";
        }
        return newText;
      }
    });

    $('.select_pickup').change(function(){
  //alert();
  var key = $('option:selected', this).attr('data-role');
  $('.activity_date_div').attr("style", "display: block !important");
  $('.select_date').hide();
  $('.select_date').val("");
  $('.select_date_'+key+'').attr("style", "display: block !important");
  $('.modalities').val(key);
}); 

    $('.select_date').change(function(){
     var cancel_err_msg = $('option:selected', this).attr('data-role');
 //alert($('.select_date').attr('name'));
 $('.select_date').attr('name',"");
 $(this).attr('name','activity_date');
 $('.cancel_err_li').attr("style", "display: block !important");
 $('.cancel_err_msg').html(cancel_err_msg);
 $('.cancel_policy').val(cancel_err_msg);
});

</script>
