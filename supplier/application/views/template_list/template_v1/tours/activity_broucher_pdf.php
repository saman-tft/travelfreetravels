<?php 
$site_logo = $GLOBALS['CI']->template->domain_images($GLOBALS['CI']->template->get_domain_logo()); 
$banner_image = $this->template->domain_images($tour_data['banner_image']);
//$mascot = $this->template->template_images('mascot.png');
 // debug($booking_details);
 // debug($booking_details['attributes']);
 $data = json_decode($booking_details['attributes']);
 // debug($data);
 // debug($data->enquiry_reference_no);exit;
 // debug($data->adult_count);exit;
 // debug($data->child_count);exit;
 // debug($data->infant_count);exit;
 // debug($data->total);exit;
 // debug($data->enquiry_reference_no);exit;
  // debug($tour_data[0]->duration);
?>
<!DOCTYPE html>
<html>
<body>
<style type="text/css">
p {margin: 0 0 5px;}
.sm_tab.table>tbody>tr>td, .sm_tab.table>thead>tr>th {padding: 5px;font-size: 13px;}
</style>
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
         <a href="<?php echo base_url () . 'index.php/tours/tour_list/';?>"  ><button class="btn-sm btn-primary pdf">Back</button></a>
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
  <table cellspacing="0" cellpadding="10" border="0" style="border-collapse: collapse; background: #ffffff; border: 1px solid #ddd; font-size: 14px; line-height: 20px; width: 100%; max-width: 600px; margin: 0px auto; font-family: Aller, sans-serif; color:#405364; padding: 40px">
  <tbody>
  <tr>

        <td style="">
         <span style="float: right; ">
            <?php
        $pathd = DOMAIN_IMAGE_UPLOAD_DIR."keWD7SNXhVwQmNRymfGNQIA%20logo%20final.png";
         $path = str_replace("/html", '', $pathd);
         $final_path = str_replace("/var/www/", "https://", $path)
          ?>
        
            <img style="margin-bottom:-40px; height:80px !important;width:auto;" src="<?=$final_path?>" alt="logo"></span>

        </td>
      </tr>
      <tr style="border-bottom:3px solid #b2b2b2;">
        <td style="padding:10px 20px 20px 0px">&nbsp;<!-- 
        <span style="width:100%; font-size:18px; line-height:22px;">G’day and thank you for choosing <strong>ZipHop.com.</strong> 
        Your query is well received. One of our travel professionals will communicate with you shortly.</span>
         --></td>
      </tr> 
            

             <tr>
        <td style="">
         <span style="width:100%; float:left">
          <img  style=" width: 100%; max-width: 600px;  height:auto" src="https://www.tripmia.com.au/tripmia_v2/extras/custom/keWD7SNXhVwQmNRymfGN/uploads/packages/<?=$tour_data[0]->image?>">
         </span>
       
        </td>
      </tr>

       


      <tr>
        <td style="padding-left:50px">
          <h2 style="margin:15px 0 0;font-size: 16.5px; text-decoration: underline; line-height:20px; font-weight:600">

            <b>Activity Name:</b> <?=$tour_data[0]->package_name; ?></h2>
            
          <span style="margin:0 0 2px;">
            <b>Quotation No:</b> <?=$data->enquiry_reference_no; ?> <?=$quotation_details['quote_reference']?><br>
            <b>Duration:</b> 
            <?=($tour_data[0]->duration+1);?> Days / <?=$tour_data[0]->duration.(($tour_data[0]->duration==1)? 'Night': ' Nights');?> </span>

        </td>
      </tr>



      

       <tr>
        <td style="padding-left:50px">
          <?php
    if ($data->adult_count!=0 && $data->adult_count!='' ) {?>
       <b>No of Adult: </b><?=$data->adult_count?> &nbsp;&nbsp;
   <?php }
          ?>

            <?php
    if ($data->child_count !=0 && $data->child_count!='') {?>
       <b>No of Child: </b><?=$data->child_count?> &nbsp;&nbsp;
   <?php }
          ?>


            <?php
    if ($data->infant_count!=0 && $data->infant_count!='') {?>
       <b>No of Infant: </b><?=$data->infant_count?>
   <?php }
          ?>
        </td>
      </tr>

      <?php 
      if($quotation_details){
        $user_attributes = json_decode($quotation_details['user_attributes'],ture);
      ?>
      <tr>
        <td style="padding-left:50px">
          <!-- <h3 style="margin:0 0 8px ">Quote Reference : <?=$quotation_details['quote_reference']?></h3> -->
          <h3 style="margin:0 0 8px ">Activity Quote (<?=$quotation_details['currency_code']?>)</h3>
          <?php 
          if ($user_attributes['adult_price']) {
            echo '<p>Total Adult Fare : '. sprintf("%.2f", ceil($user_attributes['adult_price'])).'</p>';
          }
          if ($user_attributes['child_price']) {
            echo '<p>Total Child Fare : '. sprintf("%.2f", ceil($user_attributes['child_price'])).'</p>';
          }
          if ($user_attributes['infant_price']) {
            echo '<p>Total Infant Fare : '. sprintf("%.2f", ceil($user_attributes['infant_price'])).'</p>';
          }
          ?>

          <h2 style="margin:0 0 2px;font-weight:bold">Total : <?=$quotation_details['currency_code']?> <?=$quotation_details['quoted_price']?></h2>          
        </td>
      </tr>
      <?php 
      }elseif($booking_details){
        $attributes = json_decode($booking_details['attributes'],ture);
      ?>
      <tr>
        <td style="padding-left:50px">
          <h3 style="margin:0 0 8px ">Activity Quote (<?=$booking_details['currency_code']?>)</h3>
          <?php 
          if ($attributes['adult_price']) {
            echo '<p>Total Adult Fare : '. sprintf("%.2f", ceil($attributes['adult_price'])).'</p>';
          }
          if ($attributes['child_price']) {
            echo '<p>Total Child Fare : '. sprintf("%.2f", ceil($attributes['child_price'])).'</p>';
          }
          if ($attributes['infant_price']) {
            echo '<p>Total Infant Fare : '. sprintf("%.2f", ceil($attributes['infant_price'])).'</p>';
          }
          ?>
          <h2 style="margin:0 0 2px;font-weight:bold">Total : <?=$booking_details['currency_code']?> <?=$booking_details['basic_fare']?></h2>          
        </td>
      </tr>
      <?php 
      }
      ?>
      <?php  if($en_note){?>
      <tr>
        <td style="padding-left:50px">
          <h2 style="margin: 5px 0 2px;font-size: 16.5px;line-height:20px;font-weight:600;">IMPORTANT NOTES</h2>
          <p style="margin:0;">
            <?php 
            echo $en_note;
           
            ?>
          </p>
        </td>
      </tr>
      <?php } ?>
      <tr style="display: none">
        <td style="padding-left:50px">
          <h2 style="margin: 5px 0 2px;font-size: 16.5px;line-height:20px;font-weight:600;">CITIES VISITED</h2>
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
          <td style="padding-left:50px">
            <span style="margin:0 0 2px; font-weight:bold">Day <?php echo $key+1; ?> - <?php echo  $itinary['program_title']; ?> </span>
            <p style="margin:0;">
              <?php echo  htmlspecialchars_decode($itinary['program_des']);   ?>
            </p>
            <p style="margin:0;">Overnight at hotel <?=$itinary['hotel_name']?></p>
            <p>
              <?php 
              if($itinary['rating'])
              {
                ?>
                <img src="http://www.tripmia.com.au/extras/custom/keWD7SNXhVwQmNRymfGN/images/star_rating_<?=$itinary['rating']?>.png">
                <?php
              }
              ?>
            </p>
            <h4 style="margin:0 0 2px; font-weight:bold">Meal Plan:
              <?php foreach ($accommodation as  $accom) {
               if ($accom === end($accommodation)){
                       echo $accom;
                    }else{
                       echo $accom.'|';
                    }
              } ?></h4>
            </td>
          </tr>
          <?php
        }
        ?>
        <!-- <tr>
          <td style="padding:0px 10px 10px 0">
            <h2 style="margin:5px 0;font-size: 16.5px; line-height:20px; font-weight:600">TOUR COST</h2>
            <?php 
            $arr_occ = array();
            foreach ($tour_price as $tour_price_fly) {
              $arr_occ_fly = explode(',',$tour_price_fly['occ']);
              $arr_occ = array_merge($arr_occ, $arr_occ_fly);
            }
            $arr_occ = array_unique($arr_occ);
            ?>
            <table align="center" class="table table-bordered sm_tab" style="margin-bottom:8px; font-size:12px;border-collapse: collapse; border:1px solid #ddd; ">
              <thead>
                <?php  $currencyp = ($this->session->userdata('currency') != "") ? $this->session->userdata('currency') : "CAD"; ?>
                <tr>
                  <th style="border:1px solid #ddd;"> From Date</th>
                  <th style="border:1px solid #ddd;"> To Date</th>
                  <?php
                  foreach ($arr_occ as $occ) {
                    $query_x = "select * from occupancy_managment where id='$occ'"; 
                    $exe_x   = mysql_query($query_x);
                    $fetch_x = mysql_fetch_assoc($exe_x);
                    ?>
                    <th style="border:1px solid #ddd;">
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
                    <td style="border:1px solid #ddd; text-align:center">
                      <?=date("d-M-Y", strtotime($value['from_date']) ); ?>
                    </td>
                    <td style="border:1px solid #ddd; text-align:center">
                      <?= date("d-M-Y", strtotime( $value['to_date'] ) );?>
                    </td>
                    <?php
                    // foreach ($arr_pricing_x as $key => $pricing) {
                  foreach ($arr_occ as $key => $occ) {
                    if(array_key_exists($occ, $arr_pri)){
                      ?>
                      <td style="border:1px solid #ddd; text-align:center">
                        CAD <?=sprintf('%.2f',$arr_pri[$occ]['pricing'])?>
                        <?php //echo (($arr_pri[$occ]['pricing'])+($arr_pri[$occ]['markup']));?>
                      </td>
                      <?php
                    }else{
                      ?>
                      <td style="border:1px solid #ddd; text-align:center">N/A</td>
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
        </tr> -->
        <tr style="display: none">
          <td style="padding-left:50px">
            <h2 style="margin:5px 0;font-size: 16.5px; line-height:20px; font-weight:600">Tour Inclusions</h2>
            <p style="margin:0;white-space: normal;">
              <?php 
              $tours_itinerary['inclusions'] = str_replace('\n', '', $tours_itinerary['inclusions']);
              echo htmlspecialchars_decode($tours_itinerary['inclusions']); 
              ?>
            </p>
          </td>
        </tr>
        <tr style="display: none">
          <td style="padding-left:50px">
            <h2 style="margin:5px 0;font-size: 16.5px; line-height:20px; font-weight:600">Tour Exclusions</h2>
            <p style="margin:0;white-space: normal;">
              <?php 
              $tours_itinerary['exclusions'] = str_replace('\n', '', $tours_itinerary['exclusions']);
              echo htmlspecialchars_decode($tours_itinerary['exclusions']); 
              ?>
            </p>
          </td>
        </tr>
        <tr style="display: none">
          <td style="padding-left:50px">
            <h2 style="margin:5px 0;font-size: 16.5px; line-height:20px; font-weight:600">Terms &amp; Conditions</h2>
            <p style="margin:0;white-space: normal;">
              <?php
            
               $tours_itinerary['terms'] = str_replace('\n', '', $tours_itinerary['terms']);
               echo htmlspecialchars_decode($tours_itinerary['terms']);
                ?>
            </p>
          </tr>
          <tr style="display: none">
          <td style="padding-left:50px">
            <h2 style="margin:5px 0;font-size: 16.5px; line-height:20px; font-weight:600">Cancellation Policy</h2>
              <p style="margin:0;white-space: normal;">
                <?php 
                $tours_itinerary['canc_policy'] = str_replace('\n', '', $tours_itinerary['canc_policy']);

                echo htmlspecialchars_decode($tours_itinerary['canc_policy']); ?>
                  
                </p>
              </td>
            </tr>
            <?=email_footer()?>
          </td>
          </tr>
        </tbody>
      </table>
    </td>
    </tr>
    </tbody>
    </table>
    </body>
    </html>