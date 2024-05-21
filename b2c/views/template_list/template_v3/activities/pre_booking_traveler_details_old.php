
<?php //debug($booking); debug($package);

$total= (($booking['no_adults'] +  $booking['no_child'] ) *  $package->price ); 
//debug($total);exit;?>
<?php
$package_datepicker = array(array('date_of_travel', FUTURE_DATE_DISABLED_MONTH));
$GLOBALS['CI']->current_page->set_datepicker($package_datepicker);
?>
   <style>
   .imgsele > img {
  width: 100%;
}
     .imgcaption {
  left: 0;
  position: relative;
  right: 0;
  text-align: center;
  top: 15px;
}
.hotel {
  color: #ff0000;
  font-size: 15px;
}
.hotavai .fa.fa-star.yellow-star {
  color: #ff0000;
  font-size: 14px;
  margin: 5px 0;
}
.butsele .form-group > input {
  border-color: #999999;
  border-radius: 0;
  left: 15px;
}
.butsele .form-control {
  border-color: #999999;
  border-radius: 0;
}
.butsele .form-group > label {
  color: #000000;
  font-size: 14px;
  font-weight: normal;
}
  </style>                          
  <div class="prebook">
  <div class="container">
    <div class="staffareadash1">
      <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="profile">
          <div class="trvlwrap">
            <div class="row prebooking"> 
              <!-- Booking Details-->
              <div class="col-md-5 col-sm-5 col-xs-12 bookdetails">
                <div class="col-md-12 col-sm-12 col-xs-12">
                <h2>Booking Details</h2>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <hr>
                </div>
           
                  <div class="col-md-12 col-sm-6 col-xs-3 imgsele">
                  <img src="<?php echo $GLOBALS['CI']->template->domain_upload_pckg_images($package->image); ?>" alt="" height="342" width="342"/> 
                   </div>
                   <div class="clearfix"></div><div class="clearfix"></div>
                    <div class="imgcaption">
                    <div class="hotel"><?php echo  $package->package_name; ?></div>
                    <div class="hotavai"><i class="fa fa-star yellow-star"></i> <i class="fa fa-star yellow-star"></i> <i class="fa fa-star yellow-star"></i> <i class="fa fa-star yellow-star"></i> <i class="fa fa-star yellow-star"></i> </div>
                  </div>  
                  <div class="clearfix"></div>
                  <div class="col-md-12 col-sm-12 col-xs-12">
                    <hr>
                  </div>
                 
                  
                  
                  <div class="col-md-6 col-sm-6 col-xs-6"> <strong>Adult </strong></div>
                  <div class="col-md-6 col-sm-6 col-xs-6"><?php echo  $booking['no_adults']; ?></div>
                  <div class="col-md-12 col-sm-12 col-xs-12">
                    <hr>
                  </div>
                  <div class="col-md-6 col-sm-6 col-xs-6"> <strong> Child </strong></div>
                  <div class="col-md-6 col-sm-6 col-xs-6"><?php echo  $booking['no_child']; ?></div>
                  <div class="col-md-12 col-sm-12 col-xs-12">
                    <hr>
                  </div>
                 
                  
                  <div class="col-md-12 col-sm-12 col-xs-12">
                    <hr>
                  </div>
                  <div class="col-md-6 col-sm-6 col-xs-6"> <strong> TOTAL PRICE: </strong></div>
                  <div class="col-md-6 col-sm-6 col-xs-6"><?php

                                        echo  $total; ?></div>
                  <div class="col-md-12 col-sm-12 col-xs-12">
                    <hr>
                  </div>
                
              </div>
              <!-- Booking Details--> 
              
                <!-- Personal Information-->
              <div class="col-md-7 col-sm-7 col-xs-12 butsele">
                <form action="<?php echo base_url()?>index.php/tours/pre_booking_itinary/<?php echo  $package->package_id; ?>" method="post" autocomplete='off'>
                  <div class="row">
                    <div class="clearfix"></div>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                      <h2>Personal Information</h2>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                      <hr>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                      <label>First Name</label>
                      <input type="text" class="form-control" placeholder="First Name" name="first_name" required>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                      <label>Last Name</label>
                      <input type="text" class="form-control" placeholder="Last Name " name="last_name" required>
                    </div>
                    
                    <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                      <label>Gender</label>
                    
                      	<select name="gender" class="form-control" >
							<option value="female">Female</option>
							<option value="male">Male</option>
							
						</select>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                        <div class="lablform">Date Of travel</div>
                        <div class="plcetogo datemark sidebord">
                          <input  type="text" class="form-control b-r-0 normalinput" data-date="true" readonly name="date_of_travel" id="date_of_travel"  placeholder="Date Of travel" required />
                        </div>
                      </div>
                    <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                      <label>Contact No.</label>
                      <input type="number" class="form-control" placeholder="Contact No." name="phone">
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                      <label>Email ID</label>
                      <input type="email" class="form-control" placeholder="Email address" name="email">
                    </div>
                     <div class="col-md-3 col-sm-3 col-xs-12 form-group">
                      <label>Country</label>
                     
                     <select class='select2 form-control add_pckg_elements'
                    data-rule-required='true' name='country' id="country" required>
                    <!--  <input type="text" name="country" id="country" data-rule-required='true' class='form-control'>  -->
                    <option value="">Select Location</option>
                        <?php foreach ($country as $coun) {?>
                        <option value='<?php echo $coun->country_id; ?>'><?php echo $coun->name; ?></option>
                        <?php }?>
                      </select>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12 form-group">
                      <label>State</label>
                      <input type="text" class="form-control" placeholder="State " name="state" >
                    </div>
                     <div class="col-md-3 col-sm-3 col-xs-12 form-group">
                      <label>City</label>
                      <input type="text" class="form-control" placeholder="City" name="city" required>
                    </div>
                   
                   
                    
                    <div class="col-md-3 col-sm-3 col-xs-12 form-group">
                      <label>Postal Code</label>
                      <input type="number" class="form-control" placeholder="000000 " name="postal" required>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                      <label>Address 1</label>
                      <textarea class="form-control" name="add1"></textarea>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12 form-group" >
                      <label>Address 2</label>
                      <textarea class="form-control" name="add2"></textarea>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                      <input type="checkbox" placeholder="City"  required>
                      &nbsp;&nbsp;
                      <p class="resposelect">I agree with the booking conditions and to pay the total amount shown, which includes Service Fees, on the right</br> and to the Terms of Service, <strong> <a href="#">Privacy Policy</a> &amp; <a href="#">Taxes &amp; Fees. </a></strong></p> </div>
                    <div class="col-md-12 col-sm-12 col-xs-12 text-right">
                       <input type="hidden" name="pack_id" value="<?php echo  $booking['pack_id']; ?>" />
       

         <input type="hidden" name="no_adults" value="<?php echo  $booking['no_adults']; ?>" />
          <input type="hidden" name="no_child" value="<?php echo  $booking['no_child']; ?>" />
           <input type="hidden" name="total" value="<?php echo  $total ?>" />
           <input type="hidden" name="booking_source" value="<?php echo PACKAGE_BOOKING_SOURCE ?>" />
           <input type="hidden" name="payment_method" value="<?php echo PAY_NOW ?>" />
          
           
           <input type="submit" class="btn btn-primary" value="CONFIRM BOOKING" />
                     
                    </div>
                  </div>
                </form>
              </div>
              <div class="col-md-12 col-sm-12 col-xs-12 detailsele">
                 <div class="col-md-12 col-sm-12 col-xs-12">
                    <h2>Other Details</h2>
                  </div>
                  <div class="col-md-12 col-sm-12 col-xs-12 detailsele1">
                    <?php echo isset($package->package_description)?($package->package_description):"No Description"; ?>
                  </div>
              </div>
              <!-- Personal Information--> 
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
