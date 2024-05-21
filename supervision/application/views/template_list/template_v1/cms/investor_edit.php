<?php 
$tab1 = " active ";
$page_data = $data_list[0];
$primary_id = $page_data['id'];
$fullname =  $page_data['fullname'];
$email =  $page_data['email'];
$phone =  $page_data['phone'];
$country =  $page_data['country'];
$state =  $page_data['state'];
$city =  $page_data['city'];
$zipcode =  $page_data['zipcode'];
$passid =  $page_data['passid'];
$passcopy =  $page_data['passcopy'];
$address =  $page_data['address'];
$passno =  $page_data['passno'];
$message =  $page_data['message'];
$packselect =  $page_data['packselect'];
$package =  $page_data['package'];
$payment_status =  $page_data['payment_status'];
$accountno =  $page_data['accountno'];
$bankname =  $page_data['bankname'];
$sortcode =  $page_data['sortcode'];
$iban =  $page_data['iban'];
?>

<!-- HTML BEGIN -->
<div class="bodyContent">
<div class="panel panel-default"><!-- PANEL WRAP START -->
<div class="panel-heading"><!-- PANEL HEAD START -->
<div class="panel-title">
<ul class="nav nav-tabs" role="tablist" id="myTab">
	<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->
	<li role="presentation" class="<?=$tab1?>">
		<a id="fromListHead" href="#fromList" aria-controls="home" role="tab"	data-toggle="tab">Manage Investor</a>
	</li>
	<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE END -->
</ul>
</div>
</div>
<!-- PANEL HEAD START -->
<div class="panel-body"><!-- PANEL BODY START -->
<div class="tab-content">
<div role="tabpanel" class="clearfix tab-pane <?=$tab1?>" id="fromList">
<div class="panel-body">


<div class="tab-content">
   <div id="fromList" class="clearfix tab-pane  active " role="tabpanel">
      <div class="panel-body">
         <form class="form-horizontal" role="form" id="promo_codes_form_edit" enctype="multipart/form-data" method="POST" action="<?=base_url().'user/update_investor_action?bid='.$primary_id?>" autocomplete="off" name="promo_codes_form_edit">
            <input type="hidden" value="<?=$primary_id?>" name="BID">
            <fieldset form="promo_codes_form_edit">
               <legend class="form_legend">Update Investor</legend>
               <div class="form-group">
                  <label form="promo_codes_form_edit" for="banner_title" class="col-sm-3 control-label">Name</label>
                  	<div class="col-sm-6">
                  		<input type="text"  id="banner_title" class="form-control" placeholder="Fullname" name="fullname" value="<?=$fullname?>" maxlength="100">
                  	</div>
               </div>
             <div class="form-group">
                  <label form="promo_codes_form_edit" for="description" class="col-sm-3 control-label">Email</label>
                  <div class="col-sm-6">
                  	<input type="text"  id="banner_title" class="form-control" placeholder="Email" name="email" value="<?=$email?>">
                  </div>
               </div>
               <div class="form-group">
                  <label form="promo_codes_form_edit" for="banner_title" class="col-sm-3 control-label">Phone</label>
                     <div class="col-sm-6">
                        <input type="text"  id="banner_title" class="form-control" placeholder="Phone" name="phone" value="<?=$phone?>">
                     </div>
               </div>
               <div class="form-group">
                  <label form="promo_codes_form_edit" for="description" class="col-sm-3 control-label">Passport Number</label>
                  <div class="col-sm-6">
                    <input type="text"  id="banner_title" class="form-control" placeholder="Passport Number" name="passno" value="<?=$passno?>">
                  </div>
               </div>
               <div class="form-group">
                  <label form="promo_codes_form_edit" for="banner_title" class="col-sm-3 control-label">Country</label>
                     <div class="col-sm-6">
                        <?php
                          if(empty(set_value('country')) == false) {
                            $default_country = set_value('country');
                          } else {
                            $default_country = $active_data['api_country_list_fk'];
                          }
                          if(empty(set_value('city')) == false) {
                            $default_city = set_value('city');
                          } else {
                            $default_city = $active_data['api_city_list_fk'];
                          }
                         // debug($country_list);exit;
                          $query = "SELECT * FROM api_country_list WHERE origin='".$country."'";
                          $country_value = $this->db->query($query)->row_array();
                        ?>
                        <select name="country" id="country_id" class="form-control select_form">
                              <option value="<?=$country?>"><?php echo $country_value['name']; ?></option>
                              <option value="">Select Country</option>
                              <?=generate_options($country_list,'');?>
                            </select>
                     </div>
               </div>
               <div class="form-group">
                  <label form="promo_codes_form_edit" for="banner_title" class="col-sm-3 control-label">State</label>
                     <div class="col-sm-6">
                        <input type="text"  id="banner_title" class="form-control" placeholder="State" name="state" value="<?=$state?>">
                     </div>
               </div>
               <div class="form-group">
                  <label form="promo_codes_form_edit" for="banner_title" class="col-sm-3 control-label">City</label>
                     <div class="col-sm-6"> 
                     <?php
                          $querycity = "SELECT * FROM api_city_list WHERE origin='".$city."'";
                          $city_value = $this->db->query($querycity)->row_array();
                       // debug($city);
                       //  debug($city_value);exit;
                        ?>  
                       
                        <select name="city"  id="city_id" class="form-control select_form">
                              <!-- <option value = '' selected="">Select City</option> -->
                            </select>
                     </div>
               </div>
               <div class="form-group">
                  <label form="promo_codes_form_edit" for="banner_title" class="col-sm-3 control-label">Bank Account Number</label>
                     <div class="col-sm-6">
                        <input type="text"  id="banner_title" class="form-control" placeholder="Account Number" name="accountno" value="<?=$accountno?>">
                     </div>
               </div>
               <div class="form-group">
                  <label form="promo_codes_form_edit" for="banner_title" class="col-sm-3 control-label">Bank Name</label>
                     <div class="col-sm-6">
                        <input type="text"  id="banner_title" class="form-control" placeholder="Bank Name" name="bankname" value="<?=$bankname?>">
                     </div>
               </div>
               <div class="form-group">
                  <label form="promo_codes_form_edit" for="banner_title" class="col-sm-3 control-label">Bank Sort Code: XX-XX-XX</label>
                     <div class="col-sm-6">
                        <div id="example1" class="autotabbed">
                          <?php $splittedString = explode(' ', $sortcode);
                                $numbers = explode('-', $splittedString[0]); ?>
                          <input type="text" maxlength="2" size="2" id="scode1" value="<?=$numbers[0]?>"/>
                          -
                          <input type="text" maxlength="2" size="2" id="scode2" value="<?=$numbers[1]?>" />
                          -
                          <input type="text" maxlength="2" size="2" id="scode3" value="<?=$numbers[2]?>" />
                          <input type="hidden" id="setscode" name="sortcode" />
                        </div>
                     </div>
               </div> 
               <div class="form-group">
                  <label form="promo_codes_form_edit" for="banner_title" class="col-sm-3 control-label">IBAN Number</label>
                     <div class="col-sm-6">
                        <input type="text" name="iban" id="iban" class="form-control" value="<?=$iban?>" placeholder="IBAN Number" />
                     </div>
               </div>
               <div class="form-group">
                  <label form="promo_codes_form_edit" for="banner_title" class="col-sm-3 control-label">Zipcode</label>
                     <div class="col-sm-6">
                        <input type="text"  id="banner_title" class="form-control" placeholder="Zipcode" name="zipcode" value="<?=$zipcode?>" maxlength="10">
                     </div>
               </div>
               <div class="form-group">
                  <label form="promo_codes_form_edit" for="banner_title" class="col-sm-3 control-label">Address</label>
                     <div class="col-sm-6">
                        <textarea class=" description form-control" rows="3" id="banner_description" name="address" dt=""  data-original-title="" title=""><?=$address?></textarea>
                     </div>
               </div>
               <div class="form-group">
                  <label form="promo_codes_form_edit" for="banner_title" class="col-sm-3 control-label">Message</label>
                     <div class="col-sm-6">
                        <textarea class=" description form-control" rows="3" id="banner_description" name="message" dt=""  data-original-title="" title=""><?=$message?></textarea>
                     </div>
               </div>
               <div class="form-group">
                  <label form="promo_codes_form_edit" for="module" class="col-sm-3 control-label">Passport ID</label>
                  <div class="col-sm-6">
                    <img src="<?php echo $GLOBALS['CI']->template->domain_images($GLOBALS['CI']->template).$passid; ?>" height="100px" width="100px" class="img-thumbnail">
                    <input type="file" name="passid">
                  </div>
               </div>

               <div class="form-group">
                  <label form="promo_codes_form_edit" for="module" class="col-sm-3 control-label">Passport Copy</label>
                  <div class="col-sm-6">
                    <img src="<?php echo $GLOBALS['CI']->template->domain_images($GLOBALS['CI']->template).$passcopy; ?>" height="100px" width="100px" class="img-thumbnail">
                    <input type="file" name="passcopy">
                  </div>
               </div>
               <div class="form-group">
                  <label form="promo_codes_form_edit" for="banner_title" class="col-sm-3 control-label">Package Select</label>
                     <div class="col-sm-6">
                        <input type="text"  id="banner_title" class="form-control" placeholder="packselect" name="packselect" value="<?=$packselect?>">
                     </div>
               </div>
               <div class="form-group">
                  <label form="promo_codes_form_edit" for="banner_title" class="col-sm-3 control-label">Package</label>
                     <div class="col-sm-6">
                        <select class="form-control pak_title" name="package">
                           <?php if(@$package==''){ ?>
                        <option value="">Please Select</option>
                           <?php }else{ ?>
                        <option value="<?php echo @$package?>"><?php echo @$package?></option>
                           <?php } ?>
                         <option value="silver_package">Silver Package</option>
                         <option value="gold_package">Gold Package</option>
                         <option value="platinum_package">Platinum Package</option>
                        </select>
                     </div>
               </div>
               <div class="form-group">
                  <label form="promo_codes_form_edit" for="banner_title" class="col-sm-3 control-label">Payment Status</label>
                     <div class="col-sm-6">
                        <select class="form-control pak_title" name="payment_status">
                           <?php if(@$payment_status==''){ ?>
                        <option value="">Please Select</option>
                           <?php }else{ ?>
                        <option value="<?php echo @$payment_status?>"><?php echo @$payment_status?></option>
                           <?php } ?>
                         <option value="pending">Pending</option>
                         <option value="accepted">Accepted</option>
                         <option value="declined">Declined</option>
                        </select>
                     </div>
               </div>
            </fieldset>
            <div class="form-group">
               <div class="col-sm-8 col-sm-offset-4"> <button class=" btn btn-success " id="promo_codes_form_edit_submit" type="submit">Update</button> <button class=" btn btn-warning " id="promo_codes_form_edit_reset" type="reset">Reset</button></div>
            </div>
         </form>
      </div>
   </div>
</div>


</div>
</div>

</div>
</div>
<!-- PANEL BODY END --></div>
<!-- PANEL WRAP END --></div>
<!-- HTML END -->

<script type="text/javascript">
var default_city = '<?=$default_city;?>';
var city = '<?=$city;?>';
//alert(city);exit;
  $(document).ready(function(){
    
    get_city_list();
    //get the state
    $('#country_id').on('change', function(){
      country_origion = $(this).val();
      get_city_list();
    });
    function get_city_list(country_id)
    {
      var country_id = $('#country_id').val();
      if(country_id == ''){
          $("#city_id").empty().html('<option value = "" selected="">Select City</option>');
         return false;
         } 
      $.post(app_base_url+'index.php/user/get_city_listsnew',{  country_id : country_id, city:city},function( data ) {
        //alert(data);exit;
         $("#city_id").empty().html(data);
         //$('#city_id').val(default_city)
      });
    }
});    
</script>
<script type="text/javascript">
  $('#promo_codes_form_edit_submit').on('click',function(){
    var code1 = $('#scode1').val();
    var code2 = $('#scode2').val();
    var code3 = $('#scode3').val();
    var getcode1 = code1.concat('-', code2);
    var getcode = getcode1.concat('-', code3);
    $('#setscode').val(getcode);
  });
</script>
<script type="text/javascript">
  $.fn.autoTab = function() {
  
  autoTabOn = true; // yes, it's global. If you turn off auto tabbing on one input, you turn it off for all
  var autoTabbedInputs = this.find('input');
  var almostTabbedInputs = autoTabbedInputs.not(':last-child'); // note we don't attach tabbing event to the last of an input group. If you tab out of there, you have a reason to
  var justAutoTabbed = false;
  var tabKeyDetected = false;
  var revTabKeyDetected = false;
  var inputField = false;
  
  // init
  var init = function() {
    detectKeyDown();
    detectKeyUp();
  }
  
  // keydown detection, hijack it if it's in the fields we're looking for
  var detectKeyDown = function() {
    autoTabbedInputs.on('keydown',function(ev){
      // the field that you're in when you keydown might not be the field you're in when you keyup
      inputField = this;
      // detect keystroke in the fields 
      ev = ev || event;
      var charCode = null;
      if ("which" in ev)
        charCode = ev.which;
      else if ("keyCode" in e)
        charCode = ev.keyCode;
      else if ("keyCode" in window.event)
        charCode = window.event.keyCode;
      else if ("which" in window.event)
        charCode = window.event.which;
      // if tabbing forward
      if (charCode === 9 && !ev.shiftKey) {
        // if auto tabbing is off, don't change it's behavior
        if (!autoTabOn) {
          return;
        }
        if (justAutoTabbed) {
          ev.preventDefault();
          notifyAutoTabbingOff();
          autoTabOn = false;
          if ($('#autotab-toggle').length > 0) { // only used if toggle is present
            $('#autotab-toggle').removeClass('on');
          }
        }
        tabKeyDetected = true;
      // if tabbing backward
      } else if (charCode === 9 && ev.shiftKey) {
        revTabKeyDetected = true;
      // backspace key fakes reverse tab
      } else if (charCode === 8 && this.value.length == 0) {
        revTabKeyDetected = true;
        $(this).prev("input,select,textarea,a").focus();
      // fake tab keystrokes
      } else if (
        charCode === 191                  // "/" - for dates
        ||
        charCode === 111                  // "/" - for dates (numberpad)
        ||
        charCode === 190                  // "." - for IP addresses
        ||
        charCode === 110                  // "." - for IP addresses (numberpad)
        ||
        charCode === 189                  // "-" - for sortcodes
        ||
        charCode === 109                  // "-" - for sortcodes (numberpad)
      ) {
        ev.preventDefault();
        // if we've not yet hit the max chars for this field, and haven't already just auto-tabbed, fake a tab key
        if (!hasHitMaxChars(this) && !justAutoTabbed) {
          $(this).next("input,select,textarea,a").focus();
        }
      }
      // removed any flag to say we've just auto-tabbed
      justAutoTabbed = false;
    });
  }
  
  // entering text into auto-tabbed fields
  var detectKeyUp = function() {
    almostTabbedInputs.on('keyup',function(ev){
      // if auto tabbing is off, bug out now
      if (!autoTabOn) {
        return;
      }
      // if the complimentary keydown was a tab key, ignore this event (and reset it for the next keyup)
      if (tabKeyDetected) {
        tabKeyDetected = false;
        return;
      }
      // if we were tabbing backwards, don't jump forwards again!
      if (revTabKeyDetected) {
        revTabKeyDetected = false;
        return;
      }
      // edge case: if you've tabbed from one input group to another, the inputField that was used in keyDown hasn't yet been set
      if (!inputField) {
        return;
      }
      // removed flag to say we've just auto-tabbed
      justAutoTabbed = false;
      // else auto-tab if the field is full
      if (hasHitMaxChars(inputField)) {
        $(inputField).next().focus();
        // we've just auto-tabbed - flag it
        justAutoTabbed = true;
      }
    })
  }
  
  // detect if a field has hit max chars
  var hasHitMaxChars = function(el) {
    var elObj = $(el);
    var maxFieldLength = elObj.attr('maxlength') || elObj.attr('size');
    var valueLength = el.value.length;
    if (valueLength>=maxFieldLength) {
      return true;
    }
    return false;
  }
 
  // init function!
  init();
}
// set up example
$('#example1').autoTab();

// enable toggle
var toggle = $('#autotab-toggle');
toggle.click(function(ev){
  ev.preventDefault();
  if (toggle.hasClass('on')) {
    autoTabOn = false;
    $('#autotab-toggle').removeClass('on');
  } else {
    autoTabOn = true;
    $('#autotab-toggle').addClass('on');
  }
})
</script>