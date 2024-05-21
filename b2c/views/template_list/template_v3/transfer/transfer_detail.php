<?php 
    // debug($transfer_data['modalities']);exit;
// debug($transfer_data);
//  debug($formated_data);exit;
$loading_image = '<div class="spinner"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div><div class="bounce4"></div></div>';
if(!empty($formated_data['0crs']))
 { 

    $transfer_data = $formated_data['0crs'];
    $search_data = $formated_data['data'];
   
 }
// $last_year_arr =  end($transfer_data['Product_available_date']);
// $end_year = key($transfer_data['Product_available_date']);
// $end_year_date = end($last_year_arr);  

// $start_date_arr = reset($transfer_data['Product_available_date']);

// $star_month_year = key($transfer_data['Product_available_date']);  

// $start_date_picker = reset($start_date_arr); 

// $starting_month_date = $star_month_year.'-'.$start_date_picker;
// $end_yr_month_date=  $end_year.'-'.$end_year_date;

// $end_month_date = date('Y-m-d',strtotime($end_yr_month_date));

// $stop_date = date('Y-m-d', strtotime($transfer_data['to_date'] . ' +1 day'));

// $period = new DatePeriod(
//  new DateTime($transfer_data['from_date']),
//  new DateInterval('P1D'),
//  new DateTime($stop_date)
// );

Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/sightseeing_details_slider.js'), 'defer' => 'defer');
Js_Loader::$css[] = array('href' => $GLOBALS['CI']->template->template_css_dir('owl.carousel.min.css'), 'media' => 'screen');
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('owl.carousel.min.js'), 'defer' => 'defer'); 
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/pax_count.js'), 'defer' => 'defer'); 

 //debug($transfer_data);
 // exit;
//$product_available_date =$transfer_data['Product_available_date'];
?>

<div class="outactdiv sghtseen">
  <div class="container">
    <div class="col-xs-12 nopad">
      <div class="org_row">
       <div class="col-md-12 nopad">
        <div class="col-md-8 nopad">
           <form method="POST" action="<?php echo base_url().'index.php/transfer/booking'?>">
          <div class="col-md-12 nopad">
            <div class="htladdet light-border">
             <span><?=$transfer_data['transfer_name']?></span>
             <?php
             if(empty($transfer_data['ReviewCount']))
             {
              $transfer_data['ReviewCount'] = 0;
            }
            $current_transfer_rate = round($transfer_data['StarRating']);
                //echo $current_hotel_rate;
            ?>

            <ul class="locdurdiv">
              <li>Pickup Location: <?=$transfer_data['source']?></li><br>
              <li>Drop Off Location: <?=$transfer_data['destination']?></li>
              <?php if($transfer_data['Duration']):?>
                <li> Duration:<span><?=$transfer_data['Duration']?></span>(approx.)</li>
              <?php endif;?>
            </ul>

          </div>
          <div class="clearfix"></div>
          <?php 
          $a=4;
          $b=4;
          $show_picture='style=display:none';
          $show_video ='style=display:none';
          if($transfer_data['Product_Video']){
           $show_video='';
           $show_picture='style=display:none';
         }else{               
           $show_picture='';
         }
         ?>
         

         <div id="actvdeo" <?=$show_video?> >
          <video width="100%" controls>
            <source src="<?=$transfer_data['Product_Video']?>" type="video/mp4">
              Your browser does not support HTML5 video.
            </video>  
          </div>
          
          <div class="more_pic" <?=$show_video?>><i class="far fa-images"></i> See More Photos
          </div> 

          <div id="act_sldr" <?=$show_picture?> >
           <div id="hotel_top" class="owl-carousel owl-theme act_carsl owl-theme">                                                            

            <?php if(isset($transfer_data['ImageUrl'])):?> 
              <?php if($transfer_data['ImageUrl']):?>

                  <div class="item">                                            
                    <img src="<?=$transfer_data['ImageUrl']?>" alt="<?=@$transfer_data['caption']?>"> 
                  </div>
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
            <?php if(isset($transfer_data['ImageUrl'])):?>

              <?php foreach($transfer_data['ImageUrl'] as $photos):?>                      
                <div class="item">
                 <img src="<?=$photos['ImageUrl']?>" alt="<?=@$photos['caption']?>"/>
               </div>
             <?php endforeach;?>
           <?php endif; ?>
         </div>

         <div class="clearfix"></div>
         <div class="basic_info">
          <h4>Important Information</h4>
          <ul class="list-inline">
            <?php if($transfer_data['Cancellation_available']):?>
             <!--  <li><img src="<?php echo $GLOBALS['CI']->template->template_images('free_cancellation.svg'); ?>" alt="icon" />Free Cancelation - <?=$transfer_data['Cancellation_day']?> days prior</li> -->
             <?php else:?>
              <!-- <li><img src="<?php echo $GLOBALS['CI']->template->template_images('non-refundable.svg'); ?>" alt="icon" />Non Refundable</li> -->
            <?php endif;?>
            <?php if($transfer_data['VoucherOption']):?>
              <li><img src="<?php echo $GLOBALS['CI']->template->template_images('show_mobile.svg'); ?>" alt="icon" /> <?=$transfer_data['VoucherOption']?></li>
            <?php endif;?>
            <?php if($transfer_data['Duration']):?>
              <li><img src="<?php echo $GLOBALS['CI']->template->template_images('duration.svg'); ?>" alt="icon" /><?php echo $transfer_data['Duration']?> Duration</li>
            <?php endif;?>
             <li><img src="<?php echo $GLOBALS['CI']->template->template_images('join_group.svg'); ?>" alt="icon" /> Maximum Traveller - <b style="color:#0096ce"><?= $transfer_data['max_passenger']?></b></li> 
             <input type="hidden" name="total_pax" value="<?= $search_data['adult']+$search_data['child'] ?>">
             <input type="hidden" name="no_of_adult" value="<?= $search_data['adult']?>">
             <input type="hidden" name="no_of_child" value="<?= $search_data['child']?>">
             <input type="hidden" name="convenience_fees" value="<?=$transfer_data['convenience_fees']?>">
             <input type="hidden" name="TotalDisplayFare" value="<?= $transfer_data['Price']['TotalDisplayFare']?>">
             <input type="hidden" name="booking_source" value="<?=$transfer_data['booking_source']?>">
             <?php 
             if($transfer_data['meetup_location']=='Y'){
                  $meetup_status = '';
                }else{
                  $meetup_status = 'style="display:none;"';
                }
                ?>
             <li <?=$meetup_status?>><img src="<?php echo $GLOBALS['CI']->template->template_images('meet_up.svg'); ?>" alt="icon" /> Meet Up At Location</li>
              <li><img src="<?php echo $GLOBALS['CI']->template->template_images('join_group.svg'); ?>" alt="icon" /> Total Pax - <b style="color:#0096ce"><?= $search_data['adult']+$search_data['child'] ?></b></li> 
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
                  <?php if($transfer_data['Duration']):?>
                    <li class="list-unstyled">
                      <label><i class="fa fa-clock"></i> Duration</label>
                      <span><?php echo $transfer_data['Duration']?></span>
                    </li>
                  <?php endif;?>
                  <?php if($transfer_data['Distance']):?>
                    <li class="list-unstyled">
                      <label><i class="fa fa-road"></i> Distance</label>
                      <span><?php echo $transfer_data['Distance']?> km</span>
                    </li>
                  <?php endif;?>
                  <li class="list-unstyled">
                    <label><i class="fa fa-language"></i> Vehicle Image</label>
                    <?php if(isset($transfer_data['ImageVehicleUrl'])):?> 
                    <?php if($transfer_data['ImageVehicleUrl']):?>

                        <div class="item"><a target="_blank">                                            
                          <img src="<?=$transfer_data['ImageVehicleUrl']?>" alt="<?=@$transfer_data['caption']?>"> </a>
                        </div>
                      <?php else:?>
                       <div class="item">                  
                        <img src="<?php echo $GLOBALS['CI']->template->template_images('no_image_available.jpg'); ?>" alt="Image"> 
                      </div>
                    <?php endif; ?>
                  <?php endif; ?> 
                  </li>
                  <li class="list-unstyled">
                    <label><i class="fa fa-language"></i> Language</label>
                    <span>English</span>
                  </li>
                  <li class="list-unstyled">
                    <label><i class="fa fa-star"></i> Rating</label>
                    <span><?=$transfer_data['StarRating']?> Star Rating</span>
                  </li>
                  <?php if($transfer_data['package_types_name']):?>
                    <li class="list-unstyled">
                      <label><i class="fa fa-file"></i> Type</label>
                      <span><?php echo $transfer_data['package_types_name']?></span>
                    </li>
                  <?php endif;?>
                  <?php if($transfer_data['other_activity']):?>
                    <li class="list-unstyled">
                      <label><i class="fa fa-binoculars"></i> Other Activities</label>
                      <span><?php echo $transfer_data['other_activity']?></span>
                    </li>
                  <?php endif;?>
                  <!-- <li class="list-unstyled">
                    <label><i class="fa fa-ticket"></i> Features</label>
                    <span>TICKET Includes : TICKET</span>
                  </li> -->
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
              <div> 
                <ul class="nopad">
                <li class="list-unstyled"><label>Description :</label><span><?=$transfer_data['description']?></span></li>
                <li class="list-unstyled"><label>Price Exclusion :</label><span> <?=$transfer_data['price_includes']?></span></li>
                <li class="list-unstyled"><label>Price Exclusion :</label><span> <?=$transfer_data['price_excludes']?></span></li>
                <li class="list-unstyled"><label>Contact Address :</label><span> <?=$transfer_data['contact_address']?></span></li>
                <li class="list-unstyled"><label>Contact Email :</label><span> <?=$transfer_data['contact_email']?></span></li>
                </ul>
        <?php if(isset($transfer_data['TermsAndConditions'])): ?>
          <?php if($transfer_data['TermsAndConditions']):?>
            <div class="mvm">                      
              <div class="strong">TermsAndConditions</div>
              <ul>
                <?php foreach($transfer_data['TermsAndConditions'] as $terms):?>  <li><?=$terms?></li>
              <?php endforeach;?> 
            </ul>                        
          </div> 
        <?php endif;?>
      <?php endif;?>
      <?php if(isset($transfer_data['Highlights'])): ?>
        <?php if($transfer_data['Highlights']):?>
          <div class="mvm">                      
            <div class="strong">Highlights</div>
            <ul>
              <?php foreach($transfer_data['Highlights'] as $hight):?>  <li><?=$hight?></li>
            <?php endforeach;?> 
          </ul>                        
        </div> 
      <?php endif;?>
    <?php endif;?>

    <?php if(isset($transfer_data['AdditionalInfo'])): ?>
      <?php if($transfer_data['AdditionalInfo']): ?>
        <div class="mvm">                      
          <div class="strong">AdditionalInfo</div>
          <ul>
            <?php foreach($transfer_data['AdditionalInfo'] as $info):?>  <li><?=$info?></li>
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
         <span class="currency-amount"><?=round(($transfer_data['Price']['TotalDisplayFare']),2)?></span>
       </span>
     </div>

     <div class="datediv">           
      <div class="clearfix"></div>
      <input type="hidden" name="search_id" value="<?=$search_data['search_id'] ?>">
      <input type="hidden" name="max_count" value="<?=$transfer_data['max_passenger']?>">
      <input type="hidden" name="price_id" value="<?=$transfer_data['price_id']?>">
      <input type="hidden" name="all_passenger_name_req" value="<?=$transfer_data['AllTravellerNamesRequired']?>">

      <div class="clearfix"></div>
      <div class="spc_list">
        <div class="pickup_div" style="display: block !important;">
          <label>Pick Up Location : <?= $transfer_data['source'] ?></label>
        <!--   <select name="pickup_location"  class="select_pickup" required >
            <option value="">Choose Pick Up Location</option>
            <?php foreach ($transfer_data['modalities'] as $key => $value) { 
                // debug($transfer_data['modalities'][$key]['rate_key']);exit();
              ?>
              <option data-role="<?= $key ?>" value="<?= $transfer_data['modalities'][$key]['name']."||".$transfer_data['modalities'][$key]['rate_key'] ?>"><?= $transfer_data['modalities'][$key]['name'] ?></option>
            <?php } ?>
          </select> -->
           <input type="hidden" name="vat" value="<?=$transfer_data['Price']['GSTPrice']?>">
          <input type="hidden" name="modalities" class="modalities" value=""/><br>
          <i class="fa fa-times-circle"></i> <span style="color: #912f3c;"><?= $transfer_data['cancellation_details'] ?></span>
        </div>

        <div class="activity_date_div hide">
        <input type="hidden" name="from_date" value="<?=$transfer_data['from_date']?>">
        <input type="hidden" name="to_date" value="<?=$transfer_data['to_date']?>">
        <input type="hidden" name="cancel_policy" class="cancel_policy" value="">
      </div>

      <ul> 
       <li class="text-danger cancel_err_li hide"><i class="fa fa-times-circle"></i> <span class="cancel_err_msg">Cancellation from 24-01-2019 Will charge <?php echo $currency_obj->get_currency_symbol($currency_obj->to_currency); ?> 1096.31</span></li>
       <ul> 

        <ul> 
          <?php if($transfer_data['from_date']):?>
            <li><i class="fas fa-clock"></i> Departure Date: <?=date('d M Y',strtotime($transfer_data['from_date']))?></li>
            <li><i class="fas fa-clock"></i> Time: <?=$transfer_data['time_in_12_hour_format']?></li>
          <?php endif;?>
          <li><i class="fas fa-thumbs-up"></i> Instant Confirmation</li>  

                <!-- <?php if($transfer_data['Cancellation_available']):?>
                    <li><img src="<?php echo $GLOBALS['CI']->template->template_images('free_cancellation.svg'); ?>" alt="icon" />Free Cancelation - <?=$transfer_data['Cancellation_day']?> days prior</li>
                    <?php else:?>
                      <li><img src="<?php echo $GLOBALS['CI']->template->template_images('non-refundable.svg'); ?>" alt="icon" />Non Refundable</li>
                      <?php endif;?> -->

                      <!--   <li><img src="<?php echo $GLOBALS['CI']->template->template_images('free_cancellation.svg'); ?>" alt="icon" />Free Cancelation - <?=$transfer_data['Cancellation_day']?> days prior</li>  -->      

                      <?php if($transfer_data['Promotion']):?>
                        <li><i class="fas fa-gift"></i> Special Offer: <span><?=$transfer_data['Promotion']?></span></li>
                      <?php endif;?>
                    </ul>
                  </div>

                  <div class="patendiv">
                    <input type="hidden" name="transfer_data" value="<?= base64_encode(json_encode($transfer_data))?>" >
                    <button type="submit" class="patencls" id="bk_now">Book Now</button>

                    <?php
                    if(isset($params['enquiry_origin']) && $params['enquiry_origin'] != "") { ?>
                      <br><br>
                      <a class="b-btn bookallbtn map_to_enquiry" data-activity_data="<?php echo serialized_data($transfer_data); ?>" data-enquiry_id="<?php echo $params['enquiry_origin']; ?>">Book Later</a>
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
