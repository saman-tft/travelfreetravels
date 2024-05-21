<link href="<?php echo $GLOBALS['CI']->template->template_css_dir('custom_tour.css') ?>" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?php echo $GLOBALS['CI']->template->template_css_dir('owl.carousel.min.css') ?>" rel="stylesheet">
 <!-- <link  href="<?php echo APP_ROOT_DIR; ?>extras/system/library/bootstrap/css/font-awesome.min.css" media="screen" rel="stylesheet" type="text/css" hreflang="en">
      </link> -->
<?php
$base_url=str_replace('agent/', '', base_url());
// error_reporting(E_ALL);
//echo $search_params['radio'];die;

?>





<style type="text/css">
 .tooltip { width: auto !important; float: left; background: none !important; border-radius: 3px;} 
 .tooltip.left { padding: 0px !important; }
 .tooltip-inner { padding: 2px 7px !important; background: #333 !important; max-width: 100% !important; }
 .tooltip-inner .table { margin-bottom: 0px !important; background: #333 !important; }
 .tooltip.left .tooltip-arrow { right: -5px !important; border-left-color: #333; }
 .tooltip.in { opacity: 1 !important; }
 .padfive { padding:0px !important; }
 .normalsel { border-radius: 0px !important; height: 49px; border-right: 1px solid #ddd; }
 .sector{
  background: #fff none repeat scroll 0 0;
  float: left;
  padding: 20px 0;
  width: 100%;
  display: block;
  position: relative;
  overflow: hidden;
 }
 .fullsec{
  background: #fff none repeat scroll 0 0;
  display: block;
  overflow: hidden;
  position: relative;
  width:100%;

 }
 .fullsec1{
  background: none!important;
  display: block;
  overflow: hidden;
  position: relative;
  width:100%;
  padding:10px;
 }
 .desplname{
  background: rgba(0, 0, 0, 0.8) none repeat scroll 0 0;
  color: #ffffff;
  display: block;
  font-size: 24px;
  font-weight: 500;
  left: 20px;
  line-height: 23px;
  padding: 3px;
  position: absolute;
  top: 20px;
  z-index: 10;
  text-transform:uppercase;
 }
 .destpack{
  position: relative;
 }
 .destpack img{
  width:100%;
  max-height: 140px !important;
  min-height: 140px;
 }
 .travelname{
  bottom: 0px;
  display: block;
  font-weight: 500;
  position: absolute;
  z-index: 10;
  background: rgba(0, 0, 0, 0.66) none repeat scroll 0% 0%;
  left:0;
  right:0;
  height:50px;
 }
 .pname{
  color: #fefefe;
  float: left;
  font-size: 14px;
  width: 60%;
  padding: 6px 6px;
  font-weight: normal;
 }
 .pprize{
  color: #fefefe;
  float: right;
  font-size: 14px;
  line-height: 17px;
  background: #f58830;
  padding: 8px 2px;
  width: 40%;
  font-weight: normal;
  text-align: center;
 }
 .head_owl{
  padding: 20px 20px 0;
  font-size: 22px;
  color: #f58830;
 }
 .modinew { padding: 8px 0;}
 /*.owl-carousel .owl-wrapper-outer{overflow:inherit;}*/
</style>

<link href="<?php echo $GLOBALS['CI']->template->template_css_dir('custom_tour.css') ?>" rel="stylesheet">
<link href="<?php echo $GLOBALS['CI']->template->template_css_dir('font-awesome.min.css') ?>" rel="stylesheet">
<link href="<?php echo $GLOBALS['CI']->template->template_css_dir('bootstrap.min.css') ?>" rel="stylesheet">
 
<?php
// debug($searchResult);die;
$sr_count = ($searchResult)? count($searchResult) : 0;
$region_list =array();
$region_list = array_unique(array_column($searchResult,'tours_continent'));
$country_list = array();
$cont_list = array();
$adult_count = 1;
$child_count = 0;//$this->session->userdata('child_count');
$converted_currency_rate=1;
foreach ($searchResult as $sr_key => $sr_value) 
{
  $tour_id = $sr_value['id'];
  
  $tour_price_changed = tour_price_list($currency_obj,$tour_id,$adult_count,$child_count,$search_params['nationality']);
 
 
 
if(valid_array($tour_price_changed))
  {
   $min_numbers = array_column($tour_price_changed, 'changed_price');
   $min_price = min($min_numbers);        
  
  }
$markup=0;
$gst_value=0;
$convience_fee=0;
// debug($markup_details);exit;
  /*if($markup_details !="")
  {
    // debug($markup_details);exit;
    $markup_currency=$markup_details['markup_currency'];
    if($markup_details['value_type']=='plus')
    {
      $markup_value=$markup_details['value'];
      $markup=get_converted_currency_value ( $currency_obj->force_currency_conversion ($markup_value) );
      
      
    }
    else
    {
      $markup=($min_price/100)*$markup_details['value'];
      
    }
  }*/
// debug($markup);exit;
    // debug($min_price);exit;


  // debug($min_price);exit;
 // $atsArray[] = $sr_value['min_price'];
 $atsArray[] = $min_price;
 //debug($atsArray);
 $cont_list [] = $sr_value['tours_country'];
}
// debug($converted_currency_rate);exit;

$cont_list = implode(',', $cont_list );
$cont_list = explode(',', $cont_list);
$cont_list = array_unique($cont_list);
 // debug($atsArray);exit;
$minPrice_1 = sprintf("%.2f", min($atsArray)*$converted_currency_rate);
$maxPrice_1= sprintf("%.2f", max($atsArray)*$converted_currency_rate);
$admin_markup_holidaylcrs = $this->domain_management_model->addHolidayCrsMarkup($min_price_1, $tours_crs_markup,$currency_obj);
$admin_markup_holidaylcrs2 = $this->domain_management_model->addHolidayCrsMarkup($maxPrice_1, $tours_crs_markup,$currency_obj);
// debug($admin_markup_holidaylcrs2);exit;
// debug($maxPrice_1);



//added:starts
  $gst_value=0;
            if($admin_markup_holidaylcrs > 0 ){
                $gst_details = $this->custom_db->single_table_records('gst_master', '*', array('module' => 'holiday'));
                if($gst_details['status'] == true){
                    if($gst_details['data'][0]['gst'] > 0){
                        //added
                       //$gst_details['data'][0]['gst']= (get_converted_currency_value ( $currency_obj->force_currency_conversion ( $gst_details['data'][0]['gst'] ) ));
                        $gst_value = ($admin_markup_holidaylcrs/100) * $gst_details['data'][0]['gst'];
                    }
                }

             }
             // debug($gst_value);exit;
               $gst_value2=0;
            if($admin_markup_holidaylcrs2 > 0 ){
                $gst_details2 = $this->custom_db->single_table_records('gst_master', '*', array('module' => 'holiday'));
                if($gst_details2['status'] == true){
                    if($gst_details2['data'][0]['gst'] > 0){
                        //added
                      
                        $gst_value2 = ($admin_markup_holidaylcrs2/100) * $gst_details2['data'][0]['gst'];
                    }
                }

             }
         

//added:ends


// debug($minPrice);debug($maxPrice);exit;
// $this->currency->get_currency_symbol($default_currency); 
   $minPrice =   isset($minPrice_1)? number_format(get_converted_currency_value ( $currency_obj->force_currency_conversion ( $minPrice_1 ) ), 2):0;

   $maxPrice =   isset($maxPrice_1)? number_format(get_converted_currency_value ( $currency_obj->force_currency_conversion ( $maxPrice_1 ) ), 2):0; 
  
    $minPrice=str_replace(',','', $minPrice) + $admin_markup_holidaylcrs + $gst_value; 
    $maxPrice=str_replace(',','', $maxPrice) + $admin_markup_holidaylcrs2 + $gst_value2; 
    
    
// $minPrice=110;
// $maxPrice=5010;
// exit;

?>
<input type="hidden" id="minPrice" value="<?=$minPrice;?>">
<input type="hidden" id="maxPrice" value="<?=$maxPrice;?>">
<input type="hidden" id="MINPRICE" value="<?=$minPrice;?>">
<input type="hidden" id="MAXPRICE" value="<?=$maxPrice;?>">
<input type="hidden" id="mypricecurrency" value="<?=($this->session->userdata('currency') != '') ? $this->session->userdata('currency') : $this->currency->get_currency_symbol($default_currency);?>">

<?php
    //debug($tour_search_params);exit;

echo $GLOBALS ['CI']->template->isolated_view('holiday/search_panel_summary',$tour_search_params);
$converted_currency_rate = $currency_obj->getConversionRate(false);

// debug($converted_currency_rate );exit();
?>

<div class="full witcontent  marintopcnt">
	 
 <div class="container">
  <div class="cnclpoly1 layout_upgrade">   
   <div class="pagi_nation">
   <div class="page_lft">
  
</div>   
</div>
   <div class="clear"></div>
   <div class="coleft">
   

    <div class="flteboxwrp">
     <div class="filtersho">
      <div class="avlhtls">
       <strong id="total_records">
        <?=$sr_count;?>        
       </strong>  <span id="total_records_lable"><?=($sr_count>1)? "Tours": "Tour"?> </span> Found
      </div>
    </div>
      
     <div class="fltrboxin">
       <div class="row">
         <span class="close_fil_box"><i class="fa fa-close"></i></span>
       <a class="pull-right" id="reset_filters">RESET ALL</a>
     </div>
       <div class="rangebox">
        <button data-target="#collapse1" data-toggle="collapse" class="collapsebtn" type="button">Price</button>
        <div id="collapse1" class="in">
         <div class="price_slider1">
          <div id="core_min_max_slider_values"></div>
          <p id="price-range-amount" class="level"></p>
          <div id="price-range" class="" aria-disabled="false"></div>
         </div>
        </div>
       </div>
       <div class="septor"></div>             
       <div class="rangebox">
        <button data-target="#collapse6" data-toggle="collapse" class="collapsebtn" type="button">Regions</button>
        <div id="collapse6" class="collapse in">
         <div class="boxins">
          <ul class="locationul region">
           <?php
           foreach ($region_set as $k => $v) {
            if(in_array($k, $region_list)){
             echo '<li>
             <div class="squaredThree">
              <input type="checkbox" value="'.$k.'" class="regionCheckbox" id="region'.$k.'">
              <label for="region'.$k.'"></label>
             </div>
             <label for="dur1" class="lbllbl">'.$v.'</label>
            </li>';
           }
          }
          ?>            
         </ul>
        </div>
       </div>
      </div>
      <div class="septor"></div>              
      <div class="rangebox">
       <button data-target="#collapse7" data-toggle="collapse" class="collapsebtn" type="button">Country</button>
       <div id="collapse7" class="collapse in">
        <div class="boxins">
         <ul class="locationul country_set">
          <?php
          foreach ($country_set as $k => $v) 
          {
           echo '<li>
           <div class="squaredThree">
            <input type="checkbox" value="'.$k.'" class="countryCheckbox" id="country'.$k.'">
            <label for="country'.$k.'"></label>
           </div>
           <label for="dur1" class="lbllbl">'.$v.'</label>
          </li>';
         }
         ?>           
        </ul>
       </div>
      </div>
     </div>
     <div class="septor"></div>      
     <div class="rangebox">
      <button data-target="#collapse3" data-toggle="collapse" class="collapsebtn" type="button">Durations</button>
      <div id="collapse3" class="collapse in">
       <?php 
       function check_duration_filter($duration_set,$min ,$max )
       {
        if (count($duration_set)>0) 
        {
          for ($i=$min; $i <= $max; $i++) { 
            if (in_array($i, $duration_set)) {
              return true;
              break;
            }
          }
        }
        else
        {
          return false;
        }        
       }
       if(!empty($searchResult)) 
       {

  
        ?>
       <div class="boxins">
        <ul class="locationul package_duration">
         <li class="<?=(check_duration_filter($duration_set,1,3))? '':'hide'?>">
          <div class="squaredThree">
           <input type="checkbox" value="3" name="check" class="durationCheckbox" id="dur1">
           <label for="dur1"></label>
          </div>
          <label for="dur1" class="lbllbl">Upto 3 Nights</label>
         </li>
         <li class="<?=(check_duration_filter($duration_set,4,6))? '':'hide'?>">
          <div class="squaredThree">
           <input type="checkbox" value="6" name="check" class="durationCheckbox" id="dur2">
           <label for="dur2"></label>
          </div>
          <label for="dur2" class="lbllbl">4 to 6 Nights</label>
         </li>
         <li class="<?=(check_duration_filter($duration_set,7,10))? '':'hide'?>">
          <div class="squaredThree">
           <input type="checkbox" value="10" name="check" class="durationCheckbox" id="dur3">
           <label for="dur3"></label>
          </div>
          <label for="dur3" class="lbllbl">7 to 10 Nights</label>
         </li>
         <li class="<?=(check_duration_filter($duration_set,11,30))? '':'hide'?>">
          <div class="squaredThree">
           <input type="checkbox" value="11" name="check" class="durationCheckbox" id="dur4">
           <label for="dur4"></label>
          </div>
          <label for="dur4" class="lbllbl">10+ Nights</label>
         </li>
        </ul>
       </div>
       <?php }?>
      </div>
     </div>
     <div class="septor"></div>
     <div class="rangebox">
      <button data-target="#collapse8" data-toggle="collapse" class="collapsebtn" type="button">Activity</button>
      <div id="collapse8" class="collapse in">
       <div class="boxins">
        <ul class="locationul Activity_package">
         <?php
         foreach ($category_set as $k => $v) {
          echo '<li>
          <div class="squaredThree">
           <input type="checkbox" value="'.$k.'" class="categoryCheckbox" id="category'.$k.'">
           <label for="category'.$k.'"></label>
          </div>
          <label for="dur1" class="lbllbl">'.$v.'</label>
         </li>';
        }
        ?>      
       </ul>
      </div>
     </div>
    </div> 
    <?php
    // debug(count($theme_set));die;
    if(count($theme_set) > 0)
    {
     ?>
     <div class="septor"></div>
     <div class="rangebox">
      <button data-target="#collapse2" data-toggle="collapse" class="collapsebtn" type="button">Theme</button>
      <div id="collapse2" class="collapse in">
       <div class="boxins">
        <ul class="locationul">
         <?php
         foreach ($theme_set as $k => $v) {
           echo '<li>
           <div class="squaredThree">
            <input type="checkbox" value="'.$v.'" class="themeCheckbox" id="theme'.$v.'">
            <label for="theme'.$v.'"></label>
           </div>
           <label for="squaredThree1" class="lbllbl">'.$theme_name[$v].'</label>
          </li>';
         
        }
        ?>  
       </ul>
      </div>
     </div>
    </div>
    <?php 
   }
   ?>
  </div>
 </div>
</div>
</div>
<div class="colrit layout_upgrade">  
 <div class="filter_tab "><i class="fa fa-filter"></i> <span class="">Filter</span></div>
 <!-- <div class="head_owl"> New Packages</div> -->
 <div id="packgtr" class="packgtr">
  <!-- <div id="owl-demo2_air" class="owl-carousel owlindexnw2 owl-theme nopad">
   <?php
   if(!empty($top_new_packages))
   {
    //   debug($top_new_packages);die;
    $currency = ($this->session->userdata('currency') != '') ? $this->session->userdata('currency') : 'CAD';
    foreach ($top_new_packages as $k => $v) {
     $tours_id = $v['id'];
     $new_tour_price_changed = tour_price_list($currency_obj,$tours_id);
     if(valid_array($new_tour_price_changed)){
      $new_min_numbers = array_column($new_tour_price_changed, 'changed_price');
      $new_min_numbers = min($new_min_numbers);
     }
     echo '<div class="item">
     <a href="'.$base_url.'index.php/tours/details/'.$v['id'].'" title="'.$v['package_name'].'" data-toggle="tooltip" data-placement="top">
      <div class="fullsec1 fsheight">
       <div class="destpack">
        <img src="'.$this->template->domain_images($v['banner_image']).'" alt="" />
        <span class="travelname fsbtm">
         <span class="pname">'.$v['package_name'].'</span>
         <span class="pprize">'.$currency. ' <br>'.$new_min_numbers.'</span>
        </span>
       </div>
      </div>    
     </a>        
    </div>';
   }
  }
  ?>
 </div> -->
 <div class="vluendsort">
  <div class="col-md-7 col-xs-5"><span class="price_sort">Price Sort:</span></div>
  <div class="col-md-5 col-xs-7 nopad pull-right"> 
   <div class="filterforallnty" id="top-sort-list-wrapper">
    <div class="col-xs-12 nopad">
     <div class="insidemyt">
      <ul class="sortul" style="margin-bottom: 0px;">
       <li class="sortli threonly" style="width: 48%; margin: 0px 2px;" data-sort="hn"> <a class="sorta price-l-2-h" data-toggle="tab"> <!-- <i class="fa fa-long-arrow-down"></i> --> <strong>Low to High</strong> </a></li>
       <li class="sortli threonly" style="width: 47%; margin: 0px 2px;" data-sort="sr"> <a class="sorta price-h-2-l" data-toggle="tab"> <!-- <i class="fa fa-long-arrow-up"></i> --> <strong>High to Low</strong> </a></li>
      </ul>
     </div>
    </div>
   </div>
  </div>
 </div>
  <ul id="container" class=""> 
  <?php

    if(!empty($searchResult))
    {
      $currency = ($this->session->userdata('currency') != '') ? $this->session->userdata('currency') : 'INR';
      foreach($searchResult as $key => $record)
      { 
        $tour_id = $record['id'];

        $tour_price_changed = tour_price_list($currency_obj,$tour_id,$adult_count,$child_count,$search_params['nationality']);
     
     
     
        $min_price = '';
       
        if(valid_array($tour_price_changed))
        {
          $min_numbers = array_column($tour_price_changed, 'changed_price');

          $min_price = min($min_numbers); 
          $min_price_1 =   isset($min_price)? (get_converted_currency_value ( $currency_obj->force_currency_conversion ( $min_price ) )):0;  
          //   debug($currency_obj);die;
          $this->load->model('domain_management_model');
          $admin_markup_holidaylcrs = $this->domain_management_model->addHolidayCrsMarkup($min_price_1, $tours_crs_markup,$currency_obj);
          $min_price_1=str_replace(',','', $min_price_1); 

          $min_price=$min_price_1+$admin_markup_holidaylcrs;
          $Markup=$admin_markup_holidaylcrs;
          $gst_value = 0;
          //adding gst
          if($Markup > 0 ){
          $gst_details = $this->custom_db->single_table_records('gst_master', '*', array('module' => 'holiday'));
          if($gst_details['status'] == true){
          if($gst_details['data'][0]['gst'] > 0){
          $gst_value = ($Markup/100) * $gst_details['data'][0]['gst'];
          }
          }

          }
          $min_price=$min_price+ $gst_value;
          // debug($min_price);exit;
        }
        if(!empty($min_price))
        {
          ?>
          <li <?=$tour_id?> class=" col-xs-12 col-sm-12 col-md-12 nopad padtwo container_li tour-item" data-price="<?=($min_price);?>" data-price-sort="<?php echo isset($min_price)? number_format($min_price, 2):0; ?>" data-duration="<?= $record['duration'];?>" data-region="<?= $record['tours_continent'];?>" data-country="<?= $record['tours_country'];?>" data-category="<?= $record['tour_type'];?>" data-theme="<?= $record['theme'];?>">
            <div class="inlitp">
              <div class="tpimage">

                <img
                src="<?=$base_url.'extras/custom/'.CURRENT_DOMAIN_KEY.'/images/'.basename($record['banner_image']);?>"
                alt="<?=$record['package_name']; ?>" />
              </div>
              
              <div class="tpcontent">
               
                <h3 class="tptitle txtwrapRow"><?=$record['package_name'];?> </h3>
                <div class="clear"></div> 
                <h5 class="pack-des"><?=$record['package_description'];?> </h5>
                <div class="clear"></div>
                <div class="durtio" data-toggle="tooltip" data-placement="right" title="<?php echo ($record['duration']+1); ?> Days/ <?php echo $record['duration']; ?> Nights"><?php echo ($record['duration']+1); ?> Days/ <?php echo $record['duration']; ?> <?=($record['duration'] > 1) ? "Nights" : "Night"?></div>
                <p class="hide"> <?=substr($record['package_name'], 0,300); ?></p>                 
                <?php
                  $inclusions_checks = $record['inclusions_checks'];
                  // debug($inclusions_checks);
                  $inclusions_checks = json_decode($inclusions_checks,1);
                  // debug($inclusions_checks);
                  if(!empty($inclusions_checks)) 
                  {
                    ?>
                    <div class="clearfix"></div>                    
                    <div class="mn_inclus"><h4>Inclusions</h4></div>
                    <div class="mn_incl">
                    <ul class="inclusions">
                    <?php                   
                    foreach ($inclusions_checks as $k => $v) {
                    echo '<li><a data-toggle="tooltip" data-placement="top" title="'.$v.'"> <i class="'.inclusions_class($v).'"></i><p>Hotel</p></a> </li>';
                    }
                    ?>

                    </ul>
                    </div>
                    <?php 
                  } 
                ?>        
              </div>
              <div class="t_price each-tour">
                <?php 
                if(!empty($min_price))
                  {

                    //$min_price= isset($min_price)? number_format(get_converted_currency_value ( $currency_obj->force_currency_conversion ( $min_price ) ), 2):0;
                    ?>
                    <div class="pkprice">
                      <div class="pricebolk">

                      <!-- <?=$this->currency->get_currency_symbol($default_currency);?> -->
                      <?php if(empty(get_application_display_currency_preference())){ 
                      echo 'NPR'; 
                      }else{ 
                      echo get_application_display_currency_preference();
                      }  ?>
                      <?php echo isset($min_price)? number_format($min_price, 2):0; ?>

                      <span>per person</span></div>
                      <span class="adult_price hide">Per Person Double Occupancy</span>
                    </div>
                    <?php 
                  }
                ?>
                <!-- <div class="clear"></div> -->
                <a class="relativefmsub trssxl" href="<?php echo base_url(); ?>index.php/tours/details/<?php echo base64_encode($record['id']);?>?radio=<?php echo $search_params['nationality']; ?>">
                  <span class="sfitlblx">Book</span> <span class="srcharowx"></span>
                </a>
              </div>
              <?php 
                // debug($record['tours_city']);
                $len=sizeof($record['tours_itinerary_dw']);
                // debug($record['tours_itinerary_dw'][0]['visited_city_day']);exit;
                // debug(explode(',',$record['tours_city']));exit;
                // $start_city_id=explode(',',$record['tours_city']);
                // debug( $start_city_id);exit;
                $start_city=$tours_city_name[$record['tours_itinerary_dw'][0]['visited_city_day']];
                $end_city=$tours_city_name[$record['tours_itinerary_dw'][$len-1]['visited_city_day']];
                // $cities_covered=json_decode($tour_data['tours_city'],true);
                $cities=explode(',',$record['tours_city']);
                // debug($cities);exit;
                // debug($cities);exit('pankaj');
                $cities_count = count($cities) -1;
                $palces_covered='';
                foreach ($cities as $ckey => $cvalue) {
                $palces_covered.=$tours_city_name[$cities[$ckey]];
                if ($ckey < $cities_count) {
                $palces_covered.=' , ';
                }

                }
                // debug($palces_covered);exit;
              ?>
              <div class="more_deat">
                <div class="mn_inclus"><h4><strong class="city-dtl">Start city: </strong> <?=$start_city?></h4></div>
                <div class="mn_inclus"><h4><strong class="city-dtl">End city: </strong> <?=$end_city?></h4></div>
                <div class="mn_inclus"><h4><strong class="city-dtl">Places Covered: </strong><?= $palces_covered?></h4></div>
              </div>

            </div>
            <span class="sortPrice hide"><?=($min_price);?></span>

          </li>
          <?php
        } 
      } 
      

    }
    else
    {
      ?>
      <li class="tpli cenful no_result_search">
      <div class="inlitp">
      <div class="tpimagexl">
      <span class="tptitle">No results found for this Search criteria.</span>
      </div>


      </div>
      </li>
      <?php 
    }
      ?>
      <li class="tpli cenful no_result_search" style="display: none;">
        <div class="inlitp">
        <div class="tpimagexl">
        <!-- <span class="tptitle">No results found for this Search criteria.</span>  -->            
        </div>
        </div>
      </li>
</ul>
</div>
</div>
</div>
</div>
</div>

<script src="<?=$base_url?>extras/system/template_list/template_v1/javascript/page_resource/jquery.jsort.0.4.js" type="text/javascript"></script>
<script src="<?=$base_url?>extras/system/template_list/template_v1/javascript/page_resource/holiday_filter.js" type="text/javascript"></script>

<script>
 $(document).ready(function() { $('[data-toggle="tooltip"]').tooltip(); });
</script>
<script src="<?=$base_url?>extras/system/template_list/template_v1/javascript/owl.carousel.min.js"type="text/javascript"></script>
<script type="text/javascript"> 
 $("#owl-demo2_air").owlCarousel({
  items : 4, 
  itemsDesktop : [1000,4],
  itemsDesktopSmall : [768,3], 
  itemsTablet: [550,2], 
  itemsMobile : [360,1], 
  navigation : true,
  pagination : false,
  autoPlay : true
 });
</script>
<script type="text/javascript">
 $('.filter_tab').click(function() {
  $('.resultalls').stop( true, true ).toggleClass('open');
  $('.coleft').addClass('round_filt');
  $('.coleft').stop( true, true ).slideToggle(500);
 });
 $( function() {
  $( "#departure_date" ).datepicker();
 }); 
 $('#send_enquiry_button').click(function() {
  $title    = $('#title').val();
  $ename    = $('#ename').val();
  $elname   = $('#elname').val();
  $emobile  = $('#emobile').val();
  $eemail   = $('#eemail').val();
  $ecomment = $('#ecomment').val();
  $tour_id  = $('#tour_id').val();
  $number_of_passengers  = $('#number_of_passengers').val();
  $durations  = $('#durations').val();
  $departure_date  = $('#departure_date').val();
  $pn_country_code  = $('#pn_country_code').val();
  $tours_itinerary_id = $('#tours_itinerary_id').val();
  if($ename=='' || $eemail=='' || $ecomment=='' || $number_of_passengers=='' || $departure_date=='') { return false; }
  $.post('<?=$base_url?>index.php/tours/ajax_enquiry', {'title': $title,'ename': $ename,'elname': $elname,'pn_country_code':$pn_country_code,'emobile': $emobile,'eemail': $eemail,'ecomment': $ecomment,'tour_id': $tour_id,'tours_itinerary_id': $tours_itinerary_id,'number_of_passengers':$number_of_passengers,'durations':$durations,'departure_date':$departure_date}, function(data) {
   $('#enquiry_form').trigger("reset");
   $('#alertp').removeClass('hide');
  });
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

  $(".close_fil_box").click(function(){
      $(".coleft").hide();
    });

 });
</script>

</body>
</html>

