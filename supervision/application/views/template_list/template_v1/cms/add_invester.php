<?php
   $tab1 = 'active';
?>
<!-- HTML BEGIN -->
<div class="bodyContent">
<div class="panel panel-default"><!-- PANEL WRAP START -->
<div class="panel-heading"><!-- PANEL HEAD START -->
<div class="panel-title">
<ul class="nav nav-tabs" role="tablist" id="myTab">
	<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->
	<li role="presentation" >
		<a  href="<?=base_url().'index.php/cms/plan_retirement'?>" ><button class="btn btn-primary btn-sm pull-right amarg">Adding Invester</button></a>
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
         <form class="form-horizontal" role="form" id="promo_codes_form_edit" enctype="multipart/form-data" method="POST" action="<?=base_url().'cms/add_invester_details'?>" autocomplete="off" name="promo_codes_form_edit">
            <fieldset form="promo_codes_form_edit">
               <legend class="form_legend">Add Invester</legend>
               <div class="form-group">
                  <label form="promo_codes_form_edit" for="banner_title" class="col-sm-3 control-label">Name</label>
                  	<div class="col-sm-6">
                  		<input type="text"  id="banner_title" class="form-control" placeholder="Fullname" name="fullname" value="" required="">
                  	</div>
               </div> 
               <div class="form-group">
                  <label form="promo_codes_form_edit" for="banner_title" class="col-sm-3 control-label">Email</label>
                     <div class="col-sm-6">
                        <input type="text"  id="banner_title" class="form-control" placeholder="Email" name="email" value="" required="">
                     </div>
               </div> 
               <div class="form-group">
                  <label form="promo_codes_form_edit" for="banner_title" class="col-sm-3 control-label">Phone</label>
                     <div class="col-sm-6">
                        <input type="text"  id="banner_title" class="form-control" maxlength="15" placeholder="Phone" name="phone" required="" value="" onkeydown="return ( event.ctrlKey || event.altKey 
                    || (47<event.keyCode && event.keyCode<58 && event.shiftKey==false) 
                    || (95<event.keyCode && event.keyCode<106)
                    || (event.keyCode==8) || (event.keyCode==9) 
                    || (event.keyCode>34 && event.keyCode<40) 
                    || (event.keyCode==46) )">
                     </div>
               </div> 
               <div class="form-group">
                  <label form="promo_codes_form_edit" for="banner_title" class="col-sm-3 control-label">Country</label>
                     <div class="col-sm-6">
                          <!-- <label for="country">COUNTRY</label> -->
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
                        ?>
                        <select name="country" id="country_id" class="form-control select_form" required>
                              <option value="">Select Country</option>
                              <?=generate_options($country_list,'');?>
                        </select>
                  </div>
               </div> 
               <div class="form-group">
                  <label form="promo_codes_form_edit" for="banner_title" class="col-sm-3 control-label">State</label>
                     <div class="col-sm-6">
                        <input type="text"  id="banner_title" class="form-control" placeholder="State" name="state" value="" required="">
                     </div>
               </div> 
               <div class="form-group">
                  <label form="promo_codes_form_edit" for="banner_title" class="col-sm-3 control-label">City</label>
                     <div class="col-sm-6">
                        <select name="city"  id="city_id" class="form-control select_form" required>
                              <option value = '' selected="">Select City</option>
                            </select>
                     </div>
               </div> 
               <div class="form-group">
                  <label form="promo_codes_form_edit" for="banner_title" class="col-sm-3 control-label">Bank Account Number</label>
                     <div class="col-sm-6">
                        <input type="text"  id="banner_title" class="form-control" placeholder="Account Number" name="accountno" value="" required="">
                     </div>
               </div>
               <div class="form-group">
                  <label form="promo_codes_form_edit" for="banner_title" class="col-sm-3 control-label">Bank Name</label>
                     <div class="col-sm-6">
                        <input type="text"  id="banner_title" class="form-control" placeholder="Bank Name" name="bankname" value="" required="">
                     </div>
               </div>
               <div class="form-group">
                  <label form="promo_codes_form_edit" for="banner_title" class="col-sm-3 control-label">Bank Sort Code: XX-XX-XX</label>
                     <div class="col-sm-6">
                        <div id="example1" class="autotabbed">
                          <input type="text" maxlength="2" size="2" id="scode1" required="" />
                          -
                          <input type="text" maxlength="2" size="2" id="scode2" required="" />
                          -
                          <input type="text" maxlength="2" size="2" id="scode3" required="" />
                          <input type="hidden" id="setscode" name="sortcode" />
                        </div>
                     </div>
               </div> 
               <div class="form-group">
                  <label form="promo_codes_form_edit" for="banner_title" class="col-sm-3 control-label">IBAN Number</label>
                     <div class="col-sm-6">
                        <input type="text" name="iban" id="iban" class="form-control" placeholder="IBAN Number" required="" />
                     </div>
               </div>
               <div class="form-group">
                  <label form="promo_codes_form_edit" for="banner_title" class="col-sm-3 control-label">Zipcode</label>
                     <div class="col-sm-6">
                        <input type="text"  id="banner_title" class="form-control" placeholder="Zipcode" name="zipcode" pattern="[a-zA-Z0-9]+" maxlength="8" value="" required="">
                     </div>
               </div> 
               <div class="form-group">
                  <label form="promo_codes_form_edit" for="description" class="col-sm-3 control-label">Address</label>
                  <div class="col-sm-6">
                  	<textarea class=" description form-control" rows="3" id="banner_description" name="address" dt=""  data-original-title="" title=""></textarea>
                  </div>
               </div> 
               <div class="form-group">
                  <label form="promo_codes_form_edit" for="module" class="col-sm-3 control-label">Passport ID</label>
                  <div class="col-sm-6">
                    <input type="file" class="form-control custom-file-input" name="passid" id="customFile" required="">
                  </div>
               </div>
               <div class="form-group">
                  <label form="promo_codes_form_edit" for="module" class="col-sm-3 control-label">Passport Copy</label>
                  <div class="col-sm-6">
                    <input type="file" class="form-control custom-file-input" name="passcopy" id="customFile" required="">
                  </div>
               </div>
               <div class="form-group">
                  <label form="promo_codes_form_edit" for="banner_title" class="col-sm-3 control-label">Passport Number</label>
                     <div class="col-sm-6">
                        <input type="text"  id="banner_title" class="form-control" placeholder="Passport Number" name="passno" value="" required="">
                     </div>
               </div> 
               <div class="form-group">
                  <label form="promo_codes_form_edit" for="description" class="col-sm-3 control-label">MESSAGE</label>
                  <div class="col-sm-6">
                    <textarea id="message" name="message" name="msg" placeholder="Enter your Message" rows="4"  required=""></textarea>
                  </div>
               </div> 
               <div class="selpack col-md-12"><!--selpack start-->
                  <div class="col-md-6 nopad">
            <div class="panel with-nav-tabs panel-primary">
                <div class="panel-heading">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#silverpack" data-toggle="tab" onclick="gettype('1')">Silver Package</a></li>
                            <li><a href="#goldpack" data-toggle="tab" onclick="gettype('2')">Gold Package</a></li>
                            <li><a href="#platinumpack" data-toggle="tab" onclick="gettype('3')">Platinum Package</a></li>
                            
                        </ul>
                </div>
                <div class="panel-body"><!--panel body start-->
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="silverpack">
              <div class="inline-radio">
            <?php $query = "SELECT * FROM investment_chart WHERE package_title='silver_package'";
                $get_data= $this->db->query($query)->result_array(); 
                foreach ($get_data as $key => $value) { ?> 
                 <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="packselect" id="inlineRadio1" value="<?=$value['invest'];?>" required>
                  <label class="form-check-label" for="inlineRadio1"><?=$value['invest'];?></label>
                </div>            
              <?php } ?>        
              </div>
            </div>
                        <div class="tab-pane fade" id="goldpack">
                          <div class="inline-radio">
              <?php $query = "SELECT * FROM investment_chart WHERE package_title='gold_package'";
                $get_data= $this->db->query($query)->result_array(); 
                foreach ($get_data as $key => $value) { ?> 
                 <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="packselect" id="inlineRadio2" value="<?=$value['invest'];?>" required>
                  <label class="form-check-label" for="inlineRadio1"><?=$value['invest'];?></label>
                </div>            
              <?php } ?>        
              </div>
                        </div>
                        <div class="tab-pane fade" id="platinumpack">
                          <div class="inline-radio">
              <?php $query = "SELECT * FROM investment_chart WHERE package_title='platinum_package'";
                $get_data= $this->db->query($query)->result_array(); 
                foreach ($get_data as $key => $value) { ?> 
                 <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="packselect" id="inlineRadio3" value="<?=$value['invest'];?>" required>
                  <label class="form-check-label" for="inlineRadio1"><?=$value['invest'];?></label>
                </div>            
              <?php } ?>        
              </div>
                        </div>
                        
                    </div>
                </div><!--panel body end-->
            </div>
        </div>
                  
                </div><!--selpack end-->
                
                <input type="hidden" name="getpack" id="getpack" value="silver_package">
            </fieldset>
            <div class="form-group">
               <div class="col-sm-8 col-sm-offset-4"> <button class=" btn btn-success " id="promo_codes_form_edit_submit" type="submit">Save</button> <button class=" btn btn-warning " id="promo_codes_form_edit_reset" type="reset">Reset</button></div>
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
  $(document).ready(function(){
    
    get_city_list();
    //get the state
    $('#country_id').on('change', function(){
      country_origion = $(this).val();
      // if(country_origion == '92'){
      //   $("#pan_data").css("display", "block");
      //   $("#pan_number").addClass('_guest_validate_field');
      // }
      // else{
      //   $("#pan_data").css("display", "none");
      //   $("#pan_number").removeClass('_guest_validate_field');
      // }
      get_city_list();
    });
    function get_city_list(country_id)
    {
      var country_id = $('#country_id').val();
      if(country_id == ''){
          $("#city_id").empty().html('<option value = "" selected="">Select City</option>');
         return false;
         } 
      $.post(app_base_url+'index.php/ajax/get_city_listsnew',{  country_id : country_id},function( data ) {
         $("#city_id").empty().html(data);
         $('#city_id').val(default_city)
      });
    }
});    
</script>

<script type="text/javascript">
  function gettype(id){
     //alert(id);return false;
     if(id == '1'){
        $('#getpack').val('silver_package');
     }
     if(id == '2'){
        $('#getpack').val('gold_package');
     }
     if(id == '3'){
        $('#getpack').val('platinum_package');
     }
  } 
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
  $('#promo_codes_form_edit_submit').on('click',function(){
    var acctype =$('.acc_type').val();
    if(acctype=='')
    {
      alert('Please Select Account Type');return false;
    }
  });
//   document.getElementById('iban').addEventListener('input', function (e) {
//   e.target.value = e.target.value.replace(/[^\dA-Z]/g, '').replace(/(.{4})/g, '$1 ').trim();
// });
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