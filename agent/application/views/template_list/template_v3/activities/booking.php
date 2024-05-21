<style type="text/css">
  .ffty {
    padding: 10px 5px;
    text-align: center;
    font-size: 16px;
}
.nigthcunt {
    
    font-size: 15px;
    text-align: left;
}
.borddo.brdrit{
  text-align: left;
}
.borddo{
  text-align: right;
}
.grandtotal{
font-size: 18px !important;
padding: 10px 15px !important;
padding-top: 10px;
padding-right: 15px;
color: #006bd7 !important;
font-weight: 500;
}
.bookcont_custom {
  background: linear-gradient(0deg, rgba(1,80,159,1) 0%, rgba(0,119,240,1) 100%);
  border: 1px solid #006bd7;
  border-radius: 4px;
  color: #ffffff;
  display: table;
  font-size: 18px;
  padding: 9px 15px;
  width: 25%;
  margin: 0 auto;
  display: block;
  float: right;
  margin-right: 10px;
}
.checked {
  color: orange;
}

</style>
<?php //debug($package); 
$contrller = '';
if($package->module_type == 'transfers'){

  $contrller = 'transferv1/pre_booking_crs/';
}else{

  $contrller = 'activities/pre_booking/';
}


if(is_logged_in_user()) {
  $review_active_class = ' success ';
  $review_tab_details_class = '';
  $review_tab_class = ' inactive_review_tab_marker ';
  $travellers_active_class = ' active ';
  $travellers_tab_details_class = ' gohel ';
  $travellers_tab_class = ' travellers_tab_marker ';
} else {
  $review_active_class = ' active ';
  $review_tab_details_class = ' gohel ';
  $review_tab_class = ' review_tab_marker ';
  $travellers_active_class = '';
  $travellers_tab_details_class = '';
  $travellers_tab_class = ' inactive_travellers_tab_marker ';
} 

?>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-sanitize.js"></script>
<!--  -->
<script type="text/javascript" src="<?=$GLOBALS['CI']->template->template_js_dir('page_resource/booking_script.js?v=101')?>"></script>

<style>
.lprebk > img {
  height: 400px;
  margin: 25px 0;
  width: 100%;
}
.rprebk { background: #fff; margin-top: 25px; margin-bottom: 40px; }
.rprebk h3 {
  margin: 20px 0; color: #006bd7;
}

.rprebk h4 {
  margin: 10px 0; color: #006bd7; font-size: 16px; color: #555; font-weight: 600;
}

.rprebk > p {
  font-size: 15px;
  float: left; width: 100%; overflow: hidden;
}
.butsele .btn.btn-primary {
  float: left;
}
.butsele .form-group > input {
  left: 15px;
}
.imgsele > img {
  width: 100%;
}
.detailsele1 {
  font-size: 14px;
  line-height: 30px;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
  width: 100%;
}
.credit_item .col-md-4 > p {
  font-size: 13px;
  margin: 0 0 20px;
}


.credit_item p {
  font-size: 13px;
  margin: 10px 0 20px;
}
.newslterinput { margin-bottom: 12px; }
</style>
<?php

$url = ($package->module_type=='transfers')?"privatetransfer/pre_booking_crs":"activities/pre_booking";



 // $start_date = explode('-', $package->start_date);
$start_date = date('d-M-Y',strtotime($package->start_date));
$end_date = date('d-M-Y',strtotime($package->end_date));
 //$end_date = explode('-', $package->end_date);
 ?>
<div class="prebk clearfix">
  <div class="container">
    <div class="topalldesc col-md-8 col-md-8 col-sm-8 col-xs-12">
    <!--   <img src="<?php echo $GLOBALS['CI']->template->domain_upload_pckg_images($package->image); ?>" alt="" />
      <h3><?=$package->package_name; ?> </h3>
      <p><?=$package->package_description; ?></p>
      <h4>Duration: <?=$package->duration?><?php echo ($package->duration==1)?"Day":"Days"; ?>  </h4>
      <p>Start Date : <?=$start_date?></p> -->
      

<div class="hotelistrowhtl">
   <div class="col-md-4 col-sm-4 col-xs-12 nopad xcel">
      <div class="imagehotel">
        <img src="<?php echo $GLOBALS['CI']->template->domain_upload_pckg_images($package->image); ?>"> </div>
   </div>
   <div class="col-md-12 padall10 xcel">
      <div class="hotelhed">Title : <?=$package->package_name; ?></div>
      <div class="hotavai">
      <?php 
      for($i=0;$i<5;$i++){ 
      $checked = ($package->rating>$i)?'checked':""; ?>
      <span class="fa fa-star <?=$checked?>"></span>
       <?php  } ?>
      </div>
      <div class="clearfix"></div>
      <div class="bokratinghotl rating-no">          
      </div>
      <div class="clearfix"></div>
     
      <div class="sckint">
        <!--  <div class="ffty">
            <div class="borddo brdrit">
               Start Date : <?=$start_date;?>
            </div>
         </div>
         <div class="ffty">
            <div class="borddo">
                End Date : <?=$end_date;?>
            </div>
         </div> -->
         <div class="clearfix"></div>
         <div class="nigthcunt"> <?=$package->package_description; ?></div>
         <div class="nigthcunt grandtotal"><?=$currency?> <?=number_format($grand_total,2); ?></div>
      </div>
   </div>
</div>
<?php if(is_logged_in_user()) { ?>  

<div class="loginspld">
<div>


<form id="form_logged" action="<?=base_url()?>index.php/<?=$url?>/<?=$package->package_id?>" method="post">


<input type="hidden" name="date_of_travel" value="<?=$_POST['date_of_travel']?>">
<input type="hidden" name="no_adults" value="<?=$no_adults?>">
<input type="hidden" name="no_child" value="<?=$no_child?>">
<input type="hidden" name="no_infant" value="<?=$no_infant?>">
<input type="hidden" name="grand_total" value="<?=$grand_total?>">
<input type="hidden" name="total_amount" value="<?=$total_amount?>">
<input type="hidden" name="convenience_fee" value="<?=$convenience_fee?>">
<input type="hidden" name="currency" value="<?=$currency?>">
<input type="hidden" name="gst" value="<?=$gst?>">

<button class=" bookcont_custom" id="continue">Continue</button>
</form>
</div>
</div>
</div>
<?php }else { ?>

<div class="loginspld">
<div class="logininwrap">
<div class="signinhde">Sign in now to Book Online</div>
<div class="newloginsectn">



<div class="col-xs-5 celoty nopad">
<div class="insidechs">
<div class="mailenter">
<form id="form_user_submit" action="<?=base_url()?>index.php/<?=$url?>/<?=$package->package_id?>" method="post">

<input type="text" name="booking_user_name" id="booking_user_name" maxlength="80" placeholder="Your mail id" class="newslterinput nputbrd _guest_validate">

<!-- <input type="hidden" name="date_of_travel" value="<?=$date_of_travel?>"> -->
<input type="hidden" name="date_of_travel" value="<?=$_POST['date_of_travel']?>">
<input type="hidden" name="no_adults" value="<?=$no_adults?>">
<input type="hidden" name="no_child" value="<?=$no_child?>">
<input type="hidden" name="no_infant" value="<?=$no_infant?>">
<input type="hidden" name="grand_total" value="<?=$grand_total?>">
<input type="hidden" name="total_amount" value="<?=$total_amount?>">
<input type="hidden" name="convenience_fee" value="<?=$convenience_fee?>">
<input type="hidden" name="currency" value="<?=$currency?>">
<input type="hidden" name="gst" value="<?=$gst?>">


</div>
<div class="noteinote">Your booking details will be sent to this email address.</div>
<div class="clearfix"></div>
<div class="havealrdy">
<div class="squaredThree">
<input id="alreadyacnt" type="checkbox" name="check" value="None">
<label for="alreadyacnt"></label>
</div>
<label for="alreadyacnt" class="haveacntd">I have Alkhaleej account</label>
</div>
<div class="clearfix"></div>
<div class="twotogle">
<div class="cntgust">
<div class="phoneumber">
<div class="col-xs-5 nopadding">
<select class="newslterinput nputbrd _numeric_only" id="before_country_code">
<option value="Canada" +1="">Canada +1</option>
<option value="United" states="" +1="">United States +1</option>
<option value="US" minor="" outlying="" islands="" +1="">US Minor Outlying Islands +1</option>
<option value="Bahamas" +1242="">Bahamas +1242</option>
<option value="Barbados" +1246="">Barbados +1246</option>
<option value="Anguilla" +1264="">Anguilla +1264</option>
<option value="Antigua" and="" barbuda="" +1268="">Antigua And Barbuda +1268</option>
<option value="Virgin" islands="" -="" british="" +1284="">Virgin Islands - British +1284</option>
<option value="Virgin" islands="" -="" u.s.="" +1340="">Virgin Islands - U.S. +1340</option>
<option value="Cayman" islands="" +1345="">Cayman Islands +1345</option>
<option value="Bermuda" +1441="">Bermuda +1441</option>
<option value="Grenada" +1473="">Grenada +1473</option>
<option value="Saint" martin="" +1599="">Saint Martin +1599</option>
<option value="Turks" and="" caicos="" islands="" +1649="">Turks And Caicos Islands +1649</option>
<option value="Montserrat" +1664="">Montserrat +1664</option>
<option value="Northern" mariana="" islands="" +1670="">Northern Mariana Islands +1670</option>
<option value="Guam" +1671="">Guam +1671</option>
<option value="American" samoa="" +1684="">American Samoa +1684</option>
<option value="Saint" lucia="" +1758="">Saint Lucia +1758</option>
<option value="Dominica" +1767="">Dominica +1767</option>
<option value="St" vincent="" and="" the="" grenadines="" +1784="">St Vincent And The Grenadines +1784</option>
<option value="Puerto" rico="" +1787="">Puerto Rico +1787</option>
<option value="Dominican" republic="" +1809="">Dominican Republic +1809</option>
<option value="Trinidad" and="" tobago="" +1868="">Trinidad And Tobago +1868</option>
<option value="Saint" kitts="" and="" nevis="" +1869="">Saint Kitts And Nevis +1869</option>
<option value="Jamaica" +1876="">Jamaica +1876</option>
<option value="Egypt" +20="">Egypt +20</option>
<option value="Morocco" +212="">Morocco +212</option>
<option value="Algeria" +213="">Algeria +213</option>
<option value="Tunisia" +216="">Tunisia +216</option>
<option value="Libyan" arab="" jamahiriya="" +218="">Libyan Arab Jamahiriya +218</option>
<option value="Gambia" +220="">Gambia +220</option>
<option value="Senegal" +221="">Senegal +221</option>
<option value="Mauritania" +222="">Mauritania +222</option>
<option value="Mali" +223="">Mali +223</option>
<option value="Guinea" +224="">Guinea +224</option>
<option value="Burkina" faso="" +226="">Burkina Faso +226</option>
<option value="Niger" +227="">Niger +227</option>
<option value="Togo" +228="">Togo +228</option>
<option value="Benin" +229="">Benin +229</option>
<option value="Mauritius" +230="">Mauritius +230</option>
<option value="Sierra" leone="" +232="">Sierra Leone +232</option>
<option value="Ghana" +233="">Ghana +233</option>
<option value="Nigeria" +234="">Nigeria +234</option>
<option value="Chad" +235="">Chad +235</option>
<option value="Central" african="" republic="" +236="">Central African Republic +236</option>
<option value="Cameroon" +237="">Cameroon +237</option>
<option value="Cape" verde="" +238="">Cape Verde +238</option>
<option value="Sao" tome="" and="" principe="" +239="">Sao Tome And Principe +239</option>
<option value="Equatorial" guinea="" +240="">Equatorial Guinea +240</option>
<option value="Gabon" +241="">Gabon +241</option>
<option value="Congo" +242="">Congo +242</option>
<option value="Congo," dr="" of="" the="" +243="">Congo, DR Of The +243</option>
<option value="Angola" +244="">Angola +244</option>
<option value="Guinea-Bissau" +245="">Guinea-Bissau +245</option>
<option value="Seychelles" +248="">Seychelles +248</option>
<option value="Sudan" +249="">Sudan +249</option>
<option value="Rwanda" +250="">Rwanda +250</option>
<option value="Ethiopia" +251="" selected="">Ethiopia +251</option>
<option value="Djibouti" +253="">Djibouti +253</option>
<option value="Kenya" +254="">Kenya +254</option>
<option value="Tanzania" +255="">Tanzania +255</option>
<option value="Uganda" +256="">Uganda +256</option>
<option value="Burundi" +257="">Burundi +257</option>
<option value="Mozambique" +258="">Mozambique +258</option>
<option value="Zambia" +260="">Zambia +260</option>
<option value="Madagascar" +261="">Madagascar +261</option>
<option value="Mayotte" +262="">Mayotte +262</option>
<option value="Reunion" +262="">Reunion +262</option>
<option value="Zimbabwe" +263="">Zimbabwe +263</option>
<option value="Namibia" +264="">Namibia +264</option>
<option value="Malawi" +265="">Malawi +265</option>
<option value="Lesotho" +266="">Lesotho +266</option>
<option value="Botswana" +267="">Botswana +267</option>
<option value="Swaziland" +268="">Swaziland +268</option>
<option value="Comoros" +269="">Comoros +269</option>
<option value="South" africa="" +27="">South Africa +27</option>
<option value="St." helena="" +290="">St. Helena +290</option>
<option value="Eritrea" +291="">Eritrea +291</option>
<option value="Aruba" +297="">Aruba +297</option>
<option value="Faroe" islands="" +298="">Faroe Islands +298</option>
<option value="Greenland" +299="">Greenland +299</option>
<option value="Greece" +30="">Greece +30</option>
<option value="Netherlands" +31="">Netherlands +31</option>
<option value="Belgium" +32="">Belgium +32</option>
<option value="France" +33="">France +33</option>
<option value="Spain" +34="">Spain +34</option>
<option value="Gibralter" +350="">Gibralter +350</option>
<option value="Portugal" +351="">Portugal +351</option>
<option value="Luxembourg" +352="">Luxembourg +352</option>
<option value="Ireland" +353="">Ireland +353</option>
<option value="Iceland" +354="">Iceland +354</option>
<option value="Albania" +355="">Albania +355</option>
<option value="Malta" +356="">Malta +356</option>
<option value="Cyprus" +357="">Cyprus +357</option>
<option value="Finland" +358="">Finland +358</option>
<option value="Bulgaria" +359="">Bulgaria +359</option>
<option value="Hungary" +36="">Hungary +36</option>
<option value="Lithuania" +370="">Lithuania +370</option>
<option value="Latvia" +371="">Latvia +371</option>
<option value="Estonia" +372="">Estonia +372</option>
<option value="Moldova," republic="" of="" +373="">Moldova, Republic Of +373</option>
<option value="Armenia" +374="">Armenia +374</option>
<option value="Belarus" +375="">Belarus +375</option>
<option value="Andorra" +376="">Andorra +376</option>
<option value="Monaco" +377="">Monaco +377</option>
<option value="San" marino="" +378="">San Marino +378</option>
<option value="Vatican" city="" state="" +379="">Vatican City State +379</option>
<option value="Ukraine" +380="">Ukraine +380</option>
<option value="Serbia" +381="">Serbia +381</option>
<option value="Yugoslavia" +381="">Yugoslavia +381</option>
<option value="Montenegro" +382="">Montenegro +382</option>
<option value="Croatia" +385="">Croatia +385</option>
<option value="Slovenia" +386="">Slovenia +386</option>
<option value="Bosnia" and="" herzegovina="" +387="">Bosnia And Herzegovina +387</option>
<option value="Macedonia," fyr="" of="" +389="">Macedonia, FYR Of +389</option>
<option value="Italy" +39="">Italy +39</option>
<option value="Romania" +40="">Romania +40</option>
<option value="Switzerland" +41="">Switzerland +41</option>
<option value="Czech" republic="" +420="">Czech Republic +420</option>
<option value="Slovakia" (slovak="" republic)="" +421="">Slovakia (Slovak Republic) +421</option>
<option value="Liechtenstein" +423="">Liechtenstein +423</option>
<option value="Austria" +43="">Austria +43</option>
<option value="United" kingdom="" +44="">United Kingdom +44</option>
<option value="Denmark" +45="">Denmark +45</option>
<option value="Sweden" +46="">Sweden +46</option>
<option value="Norway" +47="">Norway +47</option>
<option value="Svalbard" and="" jan="" mayen="" islands="" +47="">Svalbard And Jan Mayen Islands +47</option>
<option value="Polan" +48="">Polan +48</option>
<option value="Poland" +48="">Poland +48</option>
<option value="Germany" +49="">Germany +49</option>
<option value="Falkland" islands="" +500="">Falkland Islands +500</option>
<option value="Belize" +501="">Belize +501</option>
<option value="Guatemala" +502="">Guatemala +502</option>
<option value="El" salvador="" +503="">El Salvador +503</option>
<option value="Honduras" +504="">Honduras +504</option>
<option value="Nicaragua" +505="">Nicaragua +505</option>
<option value="Costa" rica="" +506="">Costa Rica +506</option>
<option value="Panama" +507="">Panama +507</option>
<option value="St." pierre="" and="" miquelon="" +508="">St. Pierre And Miquelon +508</option>
<option value="Haiti" +509="">Haiti +509</option>
<option value="Peru" +51="">Peru +51</option>
<option value="Mexico" +52="">Mexico +52</option>
<option value="Argentina" +54="">Argentina +54</option>
<option value="Brazil" +55="">Brazil +55</option>
<option value="Chile" +56="">Chile +56</option>
<option value="Colombia" +57="">Colombia +57</option>
<option value="Venezuela" +58="">Venezuela +58</option>
<option value="Guadeloupe" +590="">Guadeloupe +590</option>
<option value="Saint" barthelemy="" +590="">Saint Barthelemy +590</option>
<option value="Bolivia" +591="">Bolivia +591</option>
<option value="Guyana" +592="">Guyana +592</option>
<option value="Ecuador" +593="">Ecuador +593</option>
<option value="French" guiana="" +594="">French Guiana +594</option>
<option value="Paragua" +595="">Paragua +595</option>
<option value="Paraguay" +595="">Paraguay +595</option>
<option value="Martinique" +596="">Martinique +596</option>
<option value="Suriname" +597="">Suriname +597</option>
<option value="Uruguay" +598="">Uruguay +598</option>
<option value="Malaysia" +60="">Malaysia +60</option>
<option value="Australia" +61="">Australia +61</option>
<option value="Christmas" island="" +61="">Christmas Island +61</option>
<option value="Indonesia" +62="">Indonesia +62</option>
<option value="Philippines" +63="">Philippines +63</option>
<option value="New" zealand="" +64="">New Zealand +64</option>
<option value="Singapore" +65="">Singapore +65</option>
<option value="Thailand" +66="">Thailand +66</option>
<option value="Norfolk" island="" +672="">Norfolk Island +672</option>
<option value="Brunei" darussalam="" +673="">Brunei Darussalam +673</option>
<option value="Nauru" +674="">Nauru +674</option>
<option value="Papua" new="" guinea="" +675="">Papua New Guinea +675</option>
<option value="Tonga" +676="">Tonga +676</option>
<option value="Solomon" islands="" +677="">Solomon Islands +677</option>
<option value="Vanuatu" +678="">Vanuatu +678</option>
<option value="Fiji" +679="">Fiji +679</option>
<option value="Palau" +680="">Palau +680</option>
<option value="Wallis" and="" futuna="" islands="" +681="">Wallis And Futuna Islands +681</option>
<option value="Cook" islands="" +682="">Cook Islands +682</option>
<option value="Niue" +683="">Niue +683</option>
<option value="Samoa" +685="">Samoa +685</option>
<option value="Kiribati" +686="">Kiribati +686</option>
<option value="New" caledonia="" +687="">New Caledonia +687</option>
<option value="Tuvalu" +688="">Tuvalu +688</option>
<option value="French" polynesia="" +689="">French Polynesia +689</option>
<option value="Micronesia," fs="" of="" +691="">Micronesia, FS Of +691</option>
<option value="Marshall" islands="" +692="">Marshall Islands +692</option>
<option value="Kazakhstan" +7="">Kazakhstan +7</option>
<option value="Russian" federation="" +7="">Russian Federation +7</option>
<option value="Japan" +81="">Japan +81</option>
<option value="Korea," republic="" of="" +82="">Korea, Republic Of +82</option>
<option value="Viet" nam="" +84="">Viet Nam +84</option>
<option value="Korea," dpr="" of="" +850="">Korea, DPR Of +850</option>
<option value="Hong" kong="" +852="">Hong Kong +852</option>
<option value="Macao" +853="">Macao +853</option>
<option value="Cambodia" +855="">Cambodia +855</option>
<option value="Laos" +856="">Laos +856</option>
<option value="China" +86="">China +86</option>
<option value="Pitcairn" +870="">Pitcairn +870</option>
<option value="Bangladesh" +880="">Bangladesh +880</option>
<option value="Taiwan" +886="">Taiwan +886</option>
<option value="Cocos" (keeling)="" islands="" +891="">Cocos (Keeling) Islands +891</option>
<option value="Turkey" +90="">Turkey +90</option>
<option value="India" +91="">India +91</option>
<option value="Pakistan" +92="">Pakistan +92</option>
<option value="Afghanistan" +93="">Afghanistan +93</option>
<option value="Sri" lanka="" +94="">Sri Lanka +94</option>
<option value="Myanmar" +95="">Myanmar +95</option>
<option value="Maldives" +960="">Maldives +960</option>
<option value="Lebanon" +961="">Lebanon +961</option>
<option value="Jordan" +962="">Jordan +962</option>
<option value="Kuwait" +965="">Kuwait +965</option>
<option value="Saudi" arabia="" +966="">Saudi Arabia +966</option>
<option value="Yemen" +967="">Yemen +967</option>
<option value="Oman" +968="">Oman +968</option>
<option value="United" arab="" emirates="" +971="">United Arab Emirates +971</option>
<option value="Israel" +972="">Israel +972</option>
<option value="Bahrain" +973="">Bahrain +973</option>
<option value="Qatar" +974="">Qatar +974</option>
<option value="Bhutan" +975="">Bhutan +975</option>
<option value="Mongolia" +976="">Mongolia +976</option>
<option value="Nepal" +977="">Nepal +977</option>
<option value="Tajikistan" +992="">Tajikistan +992</option>
<option value="Turkmenistan" +993="">Turkmenistan +993</option>
<option value="Azerbaijan" +994="">Azerbaijan +994</option>
<option value="Georgia" +995="">Georgia +995</option>
<option value="Kyrgyzstan" +996="">Kyrgyzstan +996</option>
<option value="Uzbekistan" +998="">Uzbekistan +998</option>
</select>
</div>
<div class="col-xs-1 nopadding">
<div class="sidepo">-</div>
</div>
<div class="col-xs-6 nopadding">
<input type="text" id="booking_user_mobile" placeholder="Mobile Number" class="newslterinput numeric_only guest_validate" maxlength="15">
</div>
<div class="clearfix"></div>
<div class="noteinote">We'll use this number to send possible update alerts.</div>
</div>
<div class="clearfix"></div>
<div class="continye col-xs-8 nopad">
<button class="bookcont" id="continue_as_guest">Book as guest</button>
</div>

</div>
<div class="alrdyacnt">
<div class="col-xs-12 nopad">
<div class="relativemask">
<input type="password" name="booking_user_password" id="booking_user_password" class="clainput" placeholder="Password">
</div>
<div class="clearfix"></div><a class="frgotpaswrd">Forgot Password?</a>
<div style="" class="hide alert alert-danger"></div>
</div>
<div id="book_login_auth_loading_image" style="display: none">
<div class="text-center loader-image">
<img src="/development/extras/system/template_list/template_v3/images/loader_v3.gif" alt="please wait">
</div>
</div>
<div class="clearfix"></div>
<div class="continye col-xs-8 nopad">
<a class="bookcont" id="continue_as_user">Proceed to Book</a>
</form>
</div>
</div>
</div>
</div>
</div>
<div class="col-xs-2 celoty nopad linetopbtm">
<div class="orround">OR</div>
</div>
<div class="col-xs-5 celoty nopad">
<div class="insidechs booklogin">
<div class="leftpul">
<div id="g-signin-btn" class="g-signin2 hide" data-onsuccess="onSignIn" data-gapiscan="true" data-onload="true">
<div style="height:36px;width:120px;" class="abcRioButton abcRioButtonLightBlue">
<div class="abcRioButtonContentWrapper">
<div class="abcRioButtonIcon" style="padding:8px">
<div style="width:18px;height:18px;" class="abcRioButtonSvgImageWithFallback abcRioButtonIconImage abcRioButtonIconImage18">
<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="18px" height="18px" viewBox="0 0 48 48" class="abcRioButtonSvg">
<g>
<path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"></path>
<path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"></path>
<path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"></path>
<path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"></path>
<path fill="none" d="M0 0h48v48H0z"></path>
</g>
</svg>
</div>
</div><span style="font-size:13px;line-height:34px;" class="abcRioButtonContents"><span id="not_signed_in68223ntigpci">Sign in</span><span id="connected68223ntigpci" style="display:none">Signed in</span></span>
</div>
</div>
</div>
<div class="g-signin2 " data-width="255" data-height="42" data-longtitle="true" data-onsuccess="onSignIn" data-gapiscan="true" data-onload="true">
<div style="height:42px;width:255px;" class="abcRioButton abcRioButtonLightBlue">
<div class="abcRioButtonContentWrapper">
<div class="abcRioButtonIcon" style="padding:11px">
<div style="width:18px;height:18px;" class="abcRioButtonSvgImageWithFallback abcRioButtonIconImage abcRioButtonIconImage18">
<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="18px" height="18px" viewBox="0 0 48 48" class="abcRioButtonSvg">
<g>
<path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"></path>
<path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"></path>
<path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"></path>
<path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"></path>
<path fill="none" d="M0 0h48v48H0z"></path>
</g>
</svg>
</div>
</div><span style="font-size:14px;line-height:40px;" class="abcRioButtonContents"><span id="not_signed_inpgbqgl7lg9ka">Sign in with Google</span><span id="connectedpgbqgl7lg9ka" style="display:none">Signed in with Google</span></span>
</div>

</div>

</div>
 
</div>

</div>

</div>
</div>



</div>


</div>

</div>
<?php } ?> 
    <div class="col-xs-4 full_room_buk rhttbepa rit_summery">
      <table class="table table-condensed tblemd">
        <tbody>
          <tr class="rmdtls">
<?php if($package->module_type == 'transfers'){?>
            <th> Transfer Details</th>
          <?php } 
else{
  ?><th> Activity Details</th>
<?php }
          ?>
            <td></td></tr>
          <?php
           $total_pax= $no_adults+$no_child+$no_infant;
           $passenger = ($total_pax>1)?"Passengers":"Passenger"; ?>
          <tr><th>No of <?=$passenger?></th><td><?=$no_adults+$no_child+$no_infant?></td> </tr>
          <tr><th>Departure Date</th><td><?=$_POST['date_of_travel']?></td> </tr>      
          <tr><th>Total Price</th><td><?=$currency." ".number_format($total_amount,2)?></td> </tr>  
          <tr><th>Taxes & Service fee</th><td><?=$currency." ".number_format($convenience_fee,2)?></td> </tr>  
         <!--  <tr><th>GST</th><td><?=$currency." ".$gst?></td> </tr>  -->

          <tr class="grd_tol"><th >Grand Total</th><td class="grandtotal"><?=$currency." ".number_format($grand_total,2)?></td> </tr>  
          
         
        </tbody>
      </table>
       <form id="no_persons" action="<?php echo base_url()?>index.php/<?=$contrller?><?php echo  $package->package_id; ?>" method="post">
      
      
        <input type="hidden" name="pack_id" value="<?php echo  $package->package_id; ?>" />
        
     <!--  <input type="submit" class=" bookcont" id="continue" value="Continue" />-->
      </form>

    </div>
  
  </div>
</div>

<script src="https://leafo.net/sticky-kit/example.js"></script>
<script src="https://leafo.net/sticky-kit/src/sticky-kit.js"></script>
<script type="text/javascript">

$(document).ready(function(){
//  Check Radio-box
   var adult_count="1";
   var child_count="0";
    $('.no_adults').on('change',function () {
      adult_count="0";
      adult_count = parseInt(adult_count, 10) + parseInt(this.value, 10);
       //alert(adult_count);
    });
        $('.no_child').on('change',function () {
      child_count="0";
        child_count = parseInt(child_count, 10) + parseInt(this.value, 10) ;
       //alert(child_count);
    });
        $('#no_persons').on('submit', function() {
      var total_persons="0";
      total_persons= parseInt(adult_count, 10) + parseInt(child_count, 10) ;
      if(total_persons == "0")
      {
        alert("Please select minimum 1 persons");
        return false;
      }
      else if(total_persons>"6")
      {
        alert("Please select Maximum of 6 persons");
        return false;
      }
      else
      {
        //alert(total_persons);
        return true;
      }

    });

});


</script>

<script type="text/javascript">
  $(document).ready(function(){
      

       $("#form_user").click(function(){

       // $username = $("#booking_user_name").val(); 
       // $password = $("#booking_user_password").val();
       // var re = /[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}/igm;
        
       // if(username=='' ){
       //  $("#booking_user_name").css('border','2px solid red');
       //  return false;
       // }else{
       //  if (re.test(username)) {
       //    $("#booking_user_name").css('border','2px solid red');
       //     alert('invalid email');
       //     return false;
       //  }else{
       //    return true;
       //  }
       // } 
       // if($password==''){
       //    $("#booking_user_name").css('border','2px solid red');
       //    return false;
       // }else{
       //     return true;
       // }
     });
  });
</script>