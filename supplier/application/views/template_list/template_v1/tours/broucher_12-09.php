<!DOCTYPE html>
<html>
<body>
<?php  if($menu == true){ ?>
  <div class="container">
    <div class="col-xs-12 text-center mt10">
      <ul class="list-inline">
        <li>
          <button class="btn-sm btn-primary print" onclick="window.print(); return true;">Print</button>
        </li>
        <li>
          <button type="button" class="btn-sm btn-primary btn-popup bnt_orange" data-toggle="collapse" data-target="#emailmodel" aria-expanded="false" aria-controls="markup_update">Email</button>
        </li>
        <li>
        <a href="<?php echo base_url () . 'index.php/tours/voucher/'.$tour_id.'/show_pdf';?>"  ><button class="btn-sm btn-primary pdf">PDF</button></a>
       </li>
       <li>
       <?php 
       if($this->session->userdata( 'back_link' )){        
         $back_link = $this->session->userdata( 'back_link' );
       }else{
         $back_link = base_url () . 'index.php/tours/tour_list/';
       }
       ?>
         <a href="<?= $back_link ?>"  ><button class="btn-sm btn-primary pdf">Back</button></a>
       </li>
     </ul>
   </div>
 </div>
 <div class="collapse" id="emailmodel">
  <div class="well max_wd20">
    <h4>Send Email</h4>
    <form name="agent_email" method="post" action="<?php echo base_url () . 'index.php/tours/voucher/'.$tour_id.'/show_broucher/mail';?>">
      <input id="inc_sddress" value="1" type="hidden" name="inc_sddress">
      <input id="inc_fare" value="1" type="hidden" name="inc_fare">
      <div class="row">
        <label>Email Id </label><input id="email" placeholder="Please Enter Email Id" class="airlinecheckbox validate_user_register form-control" type="text" checked name="email">
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary" value="Submit">Send Email</button>
      </div>
    </form>
  </div>
</div>
<?php } ?>
  <table cellspacing="0" cellpadding="10" border="0" style="border-collapse: collapse; background: #ffffff; border: 1px solid #b7b7b7; font-size: 12px; line-height: 18px; width: 100%; max-width: 800px; margin: 0px auto; font-family: Arial, Helvetica, sans-serif; color:#000;">
    <tbody>
      <tr style="border-bottom:1px solid #333;">
        <td style="padding:10px 40px;">
          <span><img style="margin-bottom:10px; height:60px;" src="<?php echo $GLOBALS['CI']->template->domain_images($GLOBALS['CI']->template->get_domain_logo()); ?>" alt="logo"></span>
          <span style="display:block">www.ziphop.com</span>
        </td>
      </tr>
      <tr>
        <td style="padding:20px 40px 10px">
        <span style="width:100%; float:left"><img  style="width:100%; height:300px;" src="<?=$this->template->domain_images($tour_data['banner_image']);?>" alt="img" /></span>
        </td>
      </tr>
      <tr>
        <td style="padding:0px 40px 20px">
          <h2 style="margin:0 0 8px;font-size: 24px;"><?= $tour_data['package_name']; ?></h2>
          <h4 style="margin:0 0 2px;font-weight:bold"><?=($tour_data['duration']+1);?> Days / <?=$tour_data['duration'];?> Nights</h4>
        </td>
      </tr>
      <?php 
      if($quotation_details){
        debug($quotation_details); exit('');
        $user_attributes = json_decode($quotation_details['user_attributes'],ture);
      ?>
      <tr>
        <td style="padding:0px 40px 20px">
          <h3 style="margin:0 0 8px ">Quote Reference : <?=$quotation_details['quote_reference']?></h3>
          <h3 style="margin:0 0 8px ">Fare Breakup (<?=$quotation_details['currency_code']?>)</h3>
          <?php 
          if ($user_attributes['adult_price']) {
            echo '<p>Adult Fare : '. sprintf("%.2f", ceil($user_attributes['adult_price'])).'</p>';
          }
          if ($user_attributes['child_price']) {
            echo '<p>Child Fare : '. sprintf("%.2f", ceil($user_attributes['child_price'])).'</p>';
          }
          if ($user_attributes['infant_price']) {
            echo '<p>Infant Fare : '. sprintf("%.2f", ceil($user_attributes['infant_price'])).'</p>';
          }
          ?>
          <h4 style="margin:0 0 2px;font-weight:bold">Total : <?=$quotation_details['currency_code']?> <?=$quotation_details['quoted_price']?></h4>          
        </td>
      </tr>
      <?php 
      }elseif($booking_details){
        $attributes = json_decode($booking_details['attributes'],ture);
      ?>
      <tr>
        <td style="padding:0px 40px 20px">
          <h3 style="margin:0 0 8px ">Fare Breakup (<?=$booking_details['currency_code']?>)</h3>
          <?php 
          if ($attributes['adult_price']) {
            echo '<p>Adult Fare : '. sprintf("%.2f", ceil($attributes['adult_price'])).'</p>';
          }
          if ($attributes['child_price']) {
            echo '<p>Child Fare : '. sprintf("%.2f", ceil($attributes['child_price'])).'</p>';
          }
          if ($attributes['infant_price']) {
            echo '<p>Infant Fare : '. sprintf("%.2f", ceil($attributes['infant_price'])).'</p>';
          }
          ?>
          <h4 style="margin:0 0 2px;font-weight:bold">Total : <?=$booking_details['currency_code']?> <?=$booking_details['basic_fare']?></h4>          
        </td>
      </tr>
      <?php 
      }
      ?>
      <tr>
        <td style="padding:0px 40px 10px">
          <h4 style="margin:0 0 2px; font-weight:bold">CITIES VISITED</h4>
          <p style="margin:0;">
            <?php 
            foreach ($visited_city as  $city) {
              echo $city['CityName'].',';
            }
            ?>
          </p>
        </td>
      </tr>
      <?php
      foreach ($tours_itinerary_dw as $key => $itinary) {
        $accommodation = $itinary['accomodation'];
        $accommodation = json_decode($accommodation);
        ?>
        <tr>
          <td style="padding:0px 40px 10px">
            <h4 style="margin:0 0 2px; font-weight:bold">Day <?php echo $key+1; ?> - <?php echo  $itinary['program_title']; ?> </h4>
            <p style="margin:0;">
              <?php echo  htmlspecialchars_decode($itinary['program_des']);   ?>
            </p>
            <p style="margin:0;">Overnight at hotel <?=$itinary['hotel_name']?></p>
            <h4 style="margin:0 0 2px; font-weight:bold">Meal Plan:
              <?php foreach ($accommodation as  $accom) {
                echo $accom.'|';
              } ?></h4>
            </td>
          </tr>
          <?php
        }
        ?>
        <tr>
          <td style="padding:0px 40px 10px">
            <h3 style="margin:10px 0 2px; font-size: 15px; font-weight:bold">TOUR COST</h3>
            <?php 
            $arr_occ = array();
            foreach ($tour_price as $tour_price_fly) {
              $arr_occ_fly = explode(',',$tour_price_fly['occ']);
              $arr_occ = array_merge($arr_occ, $arr_occ_fly);
            }
            $arr_occ = array_unique($arr_occ);
            ?>
            <table class="table table-bordered">
              <thead>
                <?php  $currencyp = ($this->session->userdata('currency') != "") ? $this->session->userdata('currency') : "CAD"; ?>
                <tr>
                  <th> From Date</th>
                  <th> To Date</th>
                  <?php                  
                  foreach ($arr_occ as $occ) {
                    $query_x = "select * from occupancy_managment where id='$occ'"; 
                    $exe_x   = mysql_query($query_x);
                    $fetch_x = mysql_fetch_assoc($exe_x);
                    ?>
                    <th>
                      <?=$fetch_x['occupancy_name']?>
                    </th>
                    <?php           
                  }
                  ?>

                </tr>
              </thead>
              <tbody>
                <?php
                foreach ($tour_price as  $value) {
                  $markup = $value['markup'];
                  $arr_pricing_x = explode(',',$value['pricing']);
                  $markup = explode(',',$value['markup']);
                  $arr_occ_x = explode(',',$value['occ']);
                  $arr_pri = array();
                  foreach ($arr_occ_x as $key => $value_x) {
                    $arr_pri[$value_x] = array(
                      'pricing' => $arr_pricing_x[$key],
                      'markup' => $markup[$key]
                    );
                  }
                  // debug($value);
                  // debug($arr_pri); 
                  ?>
                  <tr>
                    <td>
                      <?=date("d-M-Y", strtotime($value['from_date']) ); ?>
                    </td>
                    <td>
                      <?= date("d-M-Y", strtotime( $value['to_date'] ) );?>
                    </td>
                    <?php
                    // foreach ($arr_pricing_x as $key => $pricing) {
                  foreach ($arr_occ as $key => $occ) {
                    if(array_key_exists($occ, $arr_pri)){
                      ?>
                      <td>
                        CAD <?=sprintf('%.2f',$arr_pri[$occ]['pricing'])?>
                        <?php //echo (($arr_pri[$occ]['pricing'])+($arr_pri[$occ]['markup']));?>
                      </td>
                      <?php
                    }else{
                      ?>
                      <td>N/A</td>
                      <?php
                    }
                    }
                    ?>
                  </tr>
                  <?php
                }
                ?>
              </tbody>
            </table>
          </td>
        </tr>
        <tr>
          <td style="padding:0px 40px 10px">
            <h4 style="margin:0 0 2px; font-weight:bold">Tour Inclusions</h4>
            <p style="margin:0;white-space: normal;">
              <?php 
              $tours_itinerary['inclusions'] = str_replace('\n', '', $tours_itinerary['inclusions']);
              echo htmlspecialchars_decode($tours_itinerary['inclusions']); 
              ?>
            </p>
          </td>
        </tr>
        <tr>
          <td style="padding:0px 40px 10px">
            <h4 style="margin:0 0 2px; font-weight:bold">Tour Exclusions</h4>
            <p style="margin:0;white-space: normal;">
              <?php 
              $tours_itinerary['exclusions'] = str_replace('\n', '', $tours_itinerary['exclusions']);
              echo htmlspecialchars_decode($tours_itinerary['exclusions']); 
              ?>
            </p>
          </td>
        </tr>
        <tr>
          <td style="padding:0px 40px 10px">
            <h4 style="margin:0 0 2px; font-weight:bold">Terms &amp; Conditions</h4>
            <p style="margin:0;white-space: normal;">
              <?php echo htmlspecialchars_decode($tours_itinerary['terms']); ?>
            </p>
          </tr>
          <tr>
            <td style="padding:0px 40px 10px">
              <h4 style="margin:0 0 2px; font-weight:bold">Cancellation Policy</h4>
              <p style="margin:0;white-space: normal;">
                <?php echo htmlspecialchars_decode($tours_itinerary['canc_policy']); ?>.</p>
              </td>
            </tr>
          </tbody>
        </table>
      </body>
      </html>