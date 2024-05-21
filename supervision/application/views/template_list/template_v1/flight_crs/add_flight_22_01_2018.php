<?php 

if($_SERVER['REMOTE_ADDR']=="192.168.0.40"){
   
}?>
<link rel="stylesheet" type="text/css" href="<?php echo $GLOBALS['CI']->template->template_css_dir('page_resource/wickedpicker.css');?>">
<!-- <link rel="stylesheet" type="text/css" href="<?php echo $GLOBALS['CI']->template->template_css_dir('page_resource/timepicker.css');?>"> -->
<section class="content" style="opacity: 1;">
   <!-- UTILITY NAV -->
   <div class="container-fluid utility-nav clearfix">
      <!-- ROW --><!-- /ROW -->
   </div>
   <!-- Info boxes -->
   <div class="row">
      <!-- HTML BEGIN -->
      <div class="bodyContent">
         <div class="panel panel-primary">
            <!-- PANEL WRAP START -->
            <div class="panel-heading">
               <!-- PANEL HEAD START -->
               <div class="panel-title"><i class="fa fa-edit"></i> Add Flight</div>
            </div>
            <!-- PANEL HEAD START -->
            <div class="panel-body ad_flt">
               <!-- PANEL BODY START -->
               <fieldset>
                  <legend class="sm_titl"> Select Stop Information</legend>
                  <form action="<?php echo base_url();?>index.php/flight/save_crs_flight_details" enctype="multipart/form-data" class="form-horizontal" method="POST" autocomplete="off">
                     <div class="form-group">
                        <div class="col-sm-4">
                           <label class="radio-inline">
                              <input type="radio" class="crs_is_domestic" name="is_domestic" checked value="0">Domestic
                           </label>
                           <label class="radio-inline">
                              <input type="radio" class="crs_is_domestic" name="is_domestic" value="1">International
                           </label>
                        </div>

                        <div class="col-sm-4" id="triptype" style="display: none;">
                           <label class="radio-inline">
                              <input type="radio" class="crs_is_triptype" name="is_triptype" checked value="0">Oneway
                           </label>
                           <label class="radio-inline">
                              <input type="radio" class="crs_is_triptype" name="is_triptype" value="1">RoundTrip
                           </label>
                        </div>
                     </div>
               </fieldset>
<!--             </div>
PANEL BODY END
<div class="panel-body"> -->
               <!-- PANEL BODY START -->
               <fieldset>
                  <legend class="sm_titl"> Flight Information - Onward</legend>
                     <div class="form-group">
                        <label form="user" for="title" class="col-sm-4 control-label text-left">Number of Connecting Stops for onward</label>                        
                        <div class="col-sm-4">
                           <select class="form-control at_wdt" id="sel1" name="no_of_stop">
                               <option value="">Select No. of Stops</option>
                               <option value="0">0</option>
                               <!-- <option value="1">1</option> -->
                              <!-- <option value="2">2</option>
                               <option value="3">3</option>-->
                           </select>
                        </div>
                    </div>                  
                  <div class="col-xs-12 con_flt con_flight_1"><h4>Connecting Flight 1</h4>
                     <div class="col-xs-12 col-sm-6">
                     <div class="form-group">
                        <label form="user" for="title" class="col-sm-6 control-label">Departure Airport Code</label>       
                        <div class="col-sm-6">
                        <input type="text" class="form-control getAiportlist" placeholder="Departure Airport" name="origin[]" required>
                        <?php if(false){ ?>
                           <select class="form-control" name="origin[]" id="origin_0">
                           <option value="NA">Select Departure Airport</option>
                           <?php 
                            foreach ( $airport_list_l as $k => $v ) {
                           
                              echo  '<option value="' . $v ['airport_code'] . '" >' . $v ['airport_city'] . '</option>';
                           }
                           
                           ?>
                           </select>
                        <?php } ?>
                        </div>
                     </div>
                     </div>
                     <div class="col-xs-12 col-sm-6">
                     <div class="form-group">
                        <label form="user" for="title" class="col-sm-6 control-label">Arrival Airport Code</label>       
                        <div class="col-sm-6">
                        <input type="text" class="form-control getAiportlist" placeholder="Arrival Airport" name="destination[]" required>
                        <?php if(false){ ?>
                           <select class="form-control" name="destination[]" id="destination_0">
                           <option value="NA">Select Arrival Airport</option>
                           <?php    
                           foreach ( $airport_list_l as $k => $v ) {
                              
                              echo  '<option value="' . $v ['airport_code'] . '" >' . $v ['airport_city'] . '</option>';
                           }
                           
                           ?>
                           </select>
                        <?php } ?>
                        </div>
                     </div>
                     </div>
                    <div class="col-xs-12 col-sm-6">
                     <div class="form-group">
                        <label form="user" for="title" class="col-sm-6 control-label">Departure Time</label>       
                        <div class="col-sm-6">
                        <!-- <input type="time" class="form-control" placeholder="12:50:00 24 hour format" name="departure_time[]" required/> -->
                        <input type="text" name="departure_time[]" class="timepicker-24 form-control" placeholder="12:50:00 24 hour format"/>
                        </div>
                     </div>
                     </div>
                     <div class="col-xs-12 col-sm-6">
                     <div class="form-group">
                        <label form="user" for="title" class="col-sm-6 control-label">Arrival Time</label>       
                        <div class="col-sm-6">
                        <!-- <input type="time" class="form-control" placeholder="15:50:00 24 hour format" name="arrival_time[]" required/> -->                        
                        <input type="text" name="arrival_time[]" class="timepicker-24 form-control" placeholder="12:50:00 24 hour format"/>
                        </div>
                     </div>
                     </div>
                     <div class="col-xs-12 col-sm-6">
                     <div class="form-group">
                        <label form="user" for="title" class="col-sm-6 control-label">Flight Number</label>       
                        <div class="col-sm-6">
                        <input type="text" class="form-control" placeholder="3123" name="flight_num[]" required />
                        </div>
                     </div>
                     </div>
                     <div class="col-xs-12 col-sm-6">
                     <div class="form-group">
                        <label form="user" for="title" class="col-sm-6 control-label">Operating Airline Code</label>       
                        <div class="col-sm-6">
                        <input type="text" class="form-control tags" placeholder="SG" name="carrier_code[]" required/>
                        </div>
                     </div>
                     </div>
                     <!--  <div class="col-xs-12 col-sm-6">
                     <div class="form-group">
                        <label form="user" for="title" class="col-sm-6 control-label">AirLine Name</label>       
                        <div class="col-sm-6">
                        <input type="text" class="form-control" placeholder="SpiceJet" name="airline_name[]" required/>
                        </div>
                     </div>
                     </div> -->
                     <div class="col-xs-12 col-sm-6">
                     <div class="form-group">
                        <label form="user" for="title" class="col-sm-6 control-label">Class Type</label>       
                        <div class="col-sm-6">
                        <!-- <input type="text" class="form-control" placeholder="Economy" name="class_type[]" required/> -->
                           <select class="form-control" name="class_type[]">
                           <option value="Economy">Economy</option>                           
                           <option value="Business">Business</option>                           
                           <option value="First">First</option>  
                           </select>
                        </div>
                     </div>
                     </div>
                     <div class="col-xs-12 col-sm-12">
                     <div class="form-group">
                        <label form="user" for="title" class="col-sm-3 control-label">Fare Rule</label>       
                        <div class="col-sm-9 ad_pad padR15">                        
                        <textarea rows="2" cols="3" class="form-control" placeholder="Fare Rule" name="fare_rule[]"></textarea>
                        </div>
                     </div>
                     </div>


                      <!-- <div class="col-xs-12 nopad">
                                           <a class="add_flight_date"><i class="fa fa-plus-circle" aria-hidden="true"></i>
                                           </a>
                                           </div> 
                     <div class="col-xs-12 nopad" id="adnl_cont">

                     </div> -->
                     <!-- add content ends here -->

                     
                  </div>

                  <div class="clearfix"></div>
                  <?php if(false){?>
                  <div class="col-xs-12 con_flt con_flight_2" ><h4>Connecting Flight 2</h4>
                      <div class="col-xs-12 col-sm-6">
                      <div class="form-group">
                        <label form="user" for="title" class="col-sm-6 control-label">Departure Airport Code</label>       
                        <div class="col-sm-6">

                        <input type="text" class="form-control getAiportlist" placeholder="Departure Airport" name="origin[]" required>
                        <?php if(false){ ?>
                           <select class="form-control" name="origin[]" id="origin_1">
                           <option value="NA">Select Departure Airport</option>
                            <?php   
                           foreach ( $airport_list_l as $k => $v ) {
                              
                              echo  '<option value="' . $v ['airport_code'] . '" >' . $v ['airport_city'] . '</option>';
                           }
                           
                           ?>
                           </select>
                        <?php } ?>
                        </div>
                        </div>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                           <label form="user" for="title" class="col-sm-6 control-label">Arrival Airport Code</label>       
                           <div class="col-sm-6">
                           <input type="text" class="form-control getAiportlist" placeholder="Arrival Airport" name="destination[]" required>
                        
                           </div>
                        </div>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                           <label form="user" for="title" class="col-sm-6 control-label">Flight Number</label>       
                           <div class="col-sm-6">
                           <input type="text" class="form-control" placeholder="3123" name="flight_num[]" required />
                           </div>
                        </div>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                           <label form="user" for="title" class="col-sm-6 control-label">Operating Airline Code</label>       
                           <div class="col-sm-6">
                           <input type="text" class="form-control tags" placeholder="SG" name="carrier_code[]" required/>
                           </div>
                        </div>
                        </div>
                        <!-- <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                           <label form="user" for="title" class="col-sm-6 control-label">AirLine Name</label>       
                           <div class="col-sm-6">
                           <input type="text" class="form-control" placeholder="SpiceJet" name="airline_name[]" required/>
                           </div>
                        </div>
                        </div> -->
                        <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                           <label form="user" for="title" class="col-sm-6 control-label">Class Type</label>       
                           <div class="col-sm-6">
                           <!-- <input type="text" class="form-control" placeholder="Economy" name="class_type[]" required/> -->                        
                              <select class="form-control" name="class_type[]">
                              <option value="Economy">Economy</option>                           
                              <option value="Business">Business</option>                           
                              <option value="First">First</option>  
                              </select>
                           </div>
                        </div>
                        </div>
                        <div class="col-xs-12 col-sm-12">
                        <div class="form-group">
                           <label form="user" for="title" class="col-sm-3 control-label">Fare Rule</label>       
                           <div class="col-sm-9 ad_pad">                        
                           <textarea rows="2" cols="3" class="form-control" placeholder="Fare Rule" name="fare_rule[]"></textarea>
                           </div>
                        </div>
                        </div>

                      
                     <div class="col-xs-12 nopad" id="adnl_cont1">

                     </div>
                        </div>

                        <?php } ?>
                  </fieldset>

               <fieldset id="returnflightinfo" style="display: none;">
                  <legend class="sm_titl"> Flight Information - Return</legend>
                     <div class="form-group">
                        <label form="user" for="title" class="col-sm-4 control-label text-left">Number of Connecting Stops for return</label>                        
                        <div class="col-sm-4">
                           <select class="form-control at_wdt" id="sel1_1" name="no_of_stop_1">
                               <option value="">Select No. of Stops</option>
                               <option value="0">0</option>
                               <<!-- option value="1">1</option> -->
                              <!-- <option value="2">2</option>
                               <option value="3">3</option>-->
                           </select>
                        </div>
                    </div>            


                  <div class="col-xs-12 con_flt con_flight_1_2" style="display: none;"><h4>Connecting Flight 1</h4>
                     <div class="col-xs-12 col-sm-6">
                     <div class="form-group">
                        <label form="user" for="title" class="col-sm-6 control-label">Departure Airport Code</label>       
                        <div class="col-sm-6">
                        <input type="text" class="form-control getAiportlist" placeholder="Departure Airport" name="origin_1[]">
                        <?php if(false){ ?>
                           <select class="form-control" name="origin_1[]" id="origin_0_1">
                           <option value="NA">Select Departure Airport</option>
                           <?php 
                            foreach ( $airport_list_l as $k => $v ) {
                           
                              echo  '<option value="' . $v ['airport_code'] . '" >' . $v ['airport_city'] . '</option>';
                           }
                           
                           ?>
                           </select>
                        <?php } ?>
                        </div>
                     </div>
                     </div>
                     <div class="col-xs-12 col-sm-6">
                     <div class="form-group">
                        <label form="user" for="title" class="col-sm-6 control-label">Arrival Airport Code</label>       
                        <div class="col-sm-6">
                        <input type="text" class="form-control getAiportlist" placeholder="Arrival Airport" name="destination_1[]">
                        <?php if(false){ ?>
                           <select class="form-control" name="destination_1[]" id="destination_0_1">
                           <option value="NA">Select Arrival Airport</option>
                           <?php    
                           foreach ( $airport_list_l as $k => $v ) {
                              
                              echo  '<option value="' . $v ['airport_code'] . '" >' . $v ['airport_city'] . '</option>';
                           }
                           
                           ?>
                         
                           </select>
                        <?php } ?>
                        </div>
                     </div>
                     </div>
                     <div class="col-xs-12 col-sm-6">
                     <div class="form-group">
                        <label form="user" for="title" class="col-sm-6 control-label">Departure Dates</label> 
                        <!-- <div class="col-sm-7">
                                    <div class="demo">
                                       <div class="box1"><input type="text" class="from-input" placeholder="click here for multi select date" name="departure_date[]" ></div>
                                       <div class="code-box">
                                          <pre class="code prettyprint" style="display: none;">
                                                $('.box1').multiDatesPicker({
                                                      dateFormat: "yy-mm-dd",
                                                      altField: '.from-input'
                                                });
                                          </pre>
                                       </div>
                                    </div>
                              </div> -->      
                        <div class="col-sm-6">
                        <input type="text" class="form-control" name="dep_date_1[]" id="dep_date1_1" />
                        </div>
                     </div>
                     </div>
                     <div class="col-xs-12 col-sm-6">
                     <div class="form-group">
                        <label form="user" for="title" class="col-sm-6 control-label">Arrival Dates</label>
                        <div class="col-sm-6">
                        <input type="text" class="form-control" name="arr_date_1[]" id="arr_date1_1" />
                        </div>
                     </div>
                     </div>
                     <div class="col-xs-12 col-sm-6">
                     <div class="form-group">
                        <label form="user" for="title" class="col-sm-6 control-label">Departure Time</label>       
                        <div class="col-sm-6">
                        <!-- <input type="time" class="form-control" placeholder="12:50:00 24 hour format" name="departure_time[]" required/> -->
                        <input type="text" name="departure_time_1[]" class="timepicker-24 form-control" placeholder="12:50:00 24 hour format"/>
                        </div>
                     </div>
                     </div>
                     <div class="col-xs-12 col-sm-6">
                     <div class="form-group">
                        <label form="user" for="title" class="col-sm-6 control-label">Arrival Time</label>       
                        <div class="col-sm-6">
                        <!-- <input type="time" class="form-control" placeholder="15:50:00 24 hour format" name="arrival_time[]" required/> -->                        
                        <input type="text" name="arrival_time_1[]" class="timepicker-24 form-control" placeholder="12:50:00 24 hour format"/>
                        </div>
                     </div>
                     </div>
                     <div class="col-xs-12 col-sm-6">
                     <div class="form-group">
                        <label form="user" for="title" class="col-sm-6 control-label">Flight Number</label>       
                        <div class="col-sm-6">
                        <input type="text" class="form-control" placeholder="3123" name="flight_num_1[]" />
                        </div>
                     </div>
                     </div>
                     <div class="col-xs-12 col-sm-6">
                     <div class="form-group">
                        <label form="user" for="title" class="col-sm-6 control-label">Operating Airline Code</label>       
                        <div class="col-sm-6">
                        <input type="text" class="form-control tags" placeholder="SG" id="carrier_code_1" name="carrier_code_1[]"/>
                        </div>
                     </div>
                     </div>
                     <!--  <div class="col-xs-12 col-sm-6">
                     <div class="form-group">
                        <label form="user" for="title" class="col-sm-6 control-label">AirLine Name</label>       
                        <div class="col-sm-6">
                        <input type="text" class="form-control" placeholder="SpiceJet" name="airline_name[]" required/>
                        </div>
                     </div>
                     </div> -->
                     <div class="col-xs-12 col-sm-6">
                     <div class="form-group">
                        <label form="user" for="title" class="col-sm-6 control-label">Class Type</label>       
                        <div class="col-sm-6">
                        <!-- <input type="text" class="form-control" placeholder="Economy" name="class_type[]" required/> -->
                           <select class="form-control" name="class_type_1[]">
                           <option value="Economy">Economy</option>                           
                           <option value="Business">Business</option>                           
                           <option value="First">First</option>  
                           </select>
                        </div>
                     </div>
                     </div>
                     <div class="col-xs-12 col-sm-12">
                     <div class="form-group">
                        <label form="user" for="title" class="col-sm-3 control-label">Fare Rule</label>       
                        <div class="col-sm-9 ad_pad">                        
                        <textarea rows="2" cols="3" class="form-control" placeholder="Fare Rule" name="fare_rule_1[]"></textarea>
                        </div>
                     </div>
                     </div>
                  </div>
                  <div class="clearfix"></div>

                  <div class="col-xs-12 con_flt con_flight_2_2"  style="display: none;"><h4>Connecting Flight 2</h4>
                      <div class="col-xs-12 col-sm-6">
                      <div class="form-group">
                        <label form="user" for="title" class="col-sm-6 control-label">Departure Airport Code</label>       
                        <div class="col-sm-6">
                        <input type="text" class="form-control origin_1" placeholder="Departure Airport" name="origin_1[]">
                        <?php if(false){ ?>
                           <select class="form-control" name="origin_1[]" id="origin_1_1">
                           <option value="NA">Select Departure Airport</option>
                            <?php   
                           foreach ( $airport_list_l as $k => $v ) {
                              
                              echo  '<option value="' . $v ['airport_code'] . '" >' . $v ['airport_city'] . '</option>';
                           }
                           
                           ?>
                           </select>
                        <?php } ?>
                        </div>
                        </div>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                           <label form="user" for="title" class="col-sm-6 control-label">Arrival Airport Code</label>       
                           <div class="col-sm-6">
                           <input type="text" class="form-control origin_1" placeholder="Arrival Airport" name="destination_1[]">
                           <?php if(false){ ?>
                              <select class="form-control" name="destination_1[]" id="destination_1_1">
                              <option value="NA">Select Arrival Airport</option>
                               <?php   
                              foreach ( $airport_list_l as $k => $v ) {
                                 
                                 echo  '<option value="' . $v ['airport_code'] . '" >' . $v ['airport_city'] . '</option>';
                              }
                              
                              ?>
                              </select>
                           <?php } ?>
                           </div>
                        </div>
                        </div>
                  <!--       <div class="col-xs-12 col-sm-6">
                                          <div class="form-group">
                                             <label form="user" for="title" class="col-sm-6 control-label">Departure Dates</label> 
                                           
                                          <div class="col-sm-6">
                                          <input type="text" class="form-control" id="dep_date2_1" name="dep_date_1[]"/>
                                          </div>
                                          </div>
                                          </div>
                                          <div class="col-xs-12 col-sm-6">
                                          <div class="form-group">
                                             <label form="user" for="title" class="col-sm-6 control-label">Arrival Dates</label>
                                             <div class="col-sm-6">
                                             <input type="text" class="form-control" name="arr_date_1[]" id="arr_date2_1"/>
                                             </div>
                                          </div>
                                          </div>
                                           -->                        <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                           <label form="user" for="title" class="col-sm-6 control-label">Departure Time</label>       
                           <div class="col-sm-6">
                           <!-- <input type="time" class="form-control" placeholder="12:50:00 24 hour format" name="departure_time[]" required/> -->                        
                           <input type="text" name="departure_time_1[]" class="timepicker-24 form-control" />
                           </div>
                        </div>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                           <label form="user" for="title" class="col-sm-6 control-label">Arrival Time</label>       
                           <div class="col-sm-6">
                          <!--  <input type="time" class="form-control" placeholder="15:50:00 24 hour format" name="arrival_time[]" required/> -->                       
                           <input type="text" name="arrival_time_1[]" class="timepicker-24 form-control" />
                           </div>
                        </div>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                           <label form="user" for="title" class="col-sm-6 control-label">Flight Number</label>       
                           <div class="col-sm-6">
                           <input type="text" class="form-control" placeholder="3123" name="flight_num_1[]" />
                           </div>
                        </div>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                           <label form="user" for="title" class="col-sm-6 control-label">Operating Airline Code</label>       
                           <div class="col-sm-6">
                           <input type="text" class="form-control tags" placeholder="SG" id="carrier_code_1" name="carrier_code_1[]"/>
                           </div>
                        </div>
                        </div>
                        <!-- <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                           <label form="user" for="title" class="col-sm-6 control-label">AirLine Name</label>       
                           <div class="col-sm-6">
                           <input type="text" class="form-control" placeholder="SpiceJet" name="airline_name[]" required/>
                           </div>
                        </div>
                        </div> -->
                        <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                           <label form="user" for="title" class="col-sm-6 control-label">Class Type</label>       
                           <div class="col-sm-6">
                           <!-- <input type="text" class="form-control" placeholder="Economy" name="class_type[]" required/> -->                        
                              <select class="form-control" name="class_type_1[]">
                              <option value="Economy">Economy</option>                           
                              <option value="Business">Business</option>                           
                              <option value="First">First</option>  
                              </select>
                           </div>
                        </div>
                        </div>
                        <div class="col-xs-12 col-sm-12">
                        <div class="form-group">
                           <label form="user" for="title" class="col-sm-3 control-label">Fare Rule</label>       
                           <div class="col-sm-9 ad_pad">                        
                           <textarea rows="2" cols="3" class="form-control" placeholder="Fare Rule" name="fare_rule_1[]"></textarea>
                           </div>
                        </div>
                        </div>
                  </div>



               </fieldset> 
                  <div class="col-xs-12 nopad far_info">

                  <legend class="sm_titl">Fare Information</legend> 

                     <!-- <div class="col-xs-12 col-sm-6 col-sm-offset-3">
                                   <div class="form-group">
                                      <label form="user" for="title" class="col-sm-6 control-label">Seats Available</label>       
                                      <div class="col-sm-6">
                                      <input type="text" class="form-control">
                                      </div>
                                   </div>
                                   </div>  -->              
                     <div class="col-xs-12 nopad">
                     <input type="hidden" id="hidcnt" name="hidcnt" value='0'>
                     <div class="col-xs-12 col-sm-12 fare_info add_fare nopad" id="ad_fare">
                           <div id="fare_row_0" class="rw_detl">
                               <h5 class="dat_tit">First Flight Date Info</h5>
                                <a class="delete_fare_date" href="javascript:deletethisrow('delete_0')" style="display:none;" id="delete_0" ><i class="fa fa-times-circle" aria-hidden="true"></i> Remove</a>
                               <div class="col-xs-12 col-sm-4">
                              <div class="form-group mb0">
                                 <label form="user" for="title" class="col-sm-6 control-label">Departure Dates</label> 
                                 <div class="col-sm-6">
                                 <input type="text" class="form-control dep_d" name="dep_date[]" id="dep_date_0"  required />
                                 </div>
                              </div>
                              </div>
                              <div class="col-xs-12 col-sm-4">
                              <div class="form-group mb0">
                                 <label form="user" for="title" class="col-sm-6 control-label">Arrival Dates</label>
                                 <div class="col-sm-6">
                                 <input type="text" class="form-control arr_d" name="arr_date[]" id="arr_date_0" required/>
                                 </div>
                              </div>
                              </div>
                              <div class="clearfix"></div>
                              <div class="col-xs-12 col-sm-4">
                              <div class="form-group">
                                 <label form="user" for="title" class="col-sm-12 control-label">&nbsp;</label>  
                                 <label form="user" for="title" class="col-sm-6 control-label">Seats Available</label>       
                                 <div class="col-sm-6">
                                 <input type="text" class="form-control" name="seats[]">
                                 </div>
                                 <label form="user" for="title" class="col-sm-6 control-label">Enter PNR</label>       
                                 <div class="col-sm-6">
                                 <input type="text" class="form-control" name="pnr[]">
                                 </div>
                              </div>
                              </div>
                              <div class="col-xs-12 col-sm-4">
                              <div class="form-group">
                                 <label form="user" for="title" class="col-sm-12 control-label">Adult</label>    
                                 <label form="user" for="title" class="col-sm-6 control-label">Base Fare</label>       
                                 <div class="col-sm-6">
                                 <input type="text" class="form-control" placeholder="5050" name="adult_basefare[]">
                                 </div>
                                 <div class="clearfix"></div>
                                 <label form="user" for="title" class="col-sm-6 control-label">Tax</label>       
                                 <div class="col-sm-6">
                                 <input type="text" class="form-control" placeholder="5050" name="adult_tax[]">
                                 </div>
                              </div>
                              </div>
                             <!--  <div class="col-xs-12 col-sm-3">
                              <div class="form-group">
                                 <label form="user" for="title" class="col-sm-12 control-label">Child</label>     
                                 <label form="user" for="title" class="col-sm-6 control-label">Base Fare</label>       
                                 <div class="col-sm-6">
                                 <input type="text" class="form-control" placeholder="5050" name="child_basefare">
                                 </div>
                                 <div class="clearfix"></div>
                                 <label form="user" for="title" class="col-sm-6 control-label">Tax</label>       
                                 <div class="col-sm-6">
                                 <input type="text" class="form-control" placeholder="5050" name="child_tax">
                                 </div>
                              </div>
                              </div> -->
                              <div class="col-xs-12 col-sm-4">
                              <div class="form-group">
                                 <label form="user" for="title" class="col-sm-12 control-label">Infant</label>    
                                 <label form="user" for="title" class="col-sm-6 control-label">Base Fare</label>       
                                 <div class="col-sm-6">
                                 <input type="text" class="form-control" placeholder="5050" name="infant_basefare[]">
                                 </div>
                                 <div class="clearfix"></div>
                                 <label form="user" for="title" class="col-sm-6 control-label">Tax</label>       
                                 <div class="col-sm-6">
                                 <input type="text" class="form-control" placeholder="5050" name="infant_tax[]">
                                 </div>
                              </div>
                              </div>
                              </div>
                     </div>

                     <a class="add_fare_date"><i class="fa fa-plus-circle" aria-hidden="true"></i> Add more

                     </a>
                    
                     </div>
                     <div class="col-xs-12 nopad" id="adnl_fare">

                     </div>
                     </div>
                     </div>
                     <div class="col-xs-12 col-sm-12">
                        <div class="clearfix col-md-offset-1"><button class="btn btn-sm btn-success pull-right" type="submit">Submit</button>
                     </div>
                     </div>
                  </form>
               </fieldset>

            </div>
         </div>
         <!-- PANEL WRAP END -->
      </div>
   </div>
   <!-- /.row -->
</section>

<script type="text/javascript">
          
   $(document).ready(function(){
       var nomonths = 1;
       var dformat  = 'dd-mm-yy';

 $(".dep_d").datepicker({
           minDate: 0,
           numberOfMonths: nomonths,
           dateFormat:dformat,
           onSelect: function(selected) {
            // $("#arr_date1").datepicker("option","minDate", selected)
           }
       });
  $(".arr_d").datepicker({
           minDate: 0,
           numberOfMonths: nomonths,
           dateFormat:dformat,
           onSelect: function(selected) {
            // $("#arr_date1").datepicker("option","minDate", selected)
           }
       });


        $("#dep_date1").datepicker({
           minDate: 0,
           numberOfMonths: nomonths,
           dateFormat:dformat,
           onSelect: function(selected) {
             $("#arr_date1").datepicker("option","minDate", selected)
           }
       });

       $("#dep_date1").datepicker({
           minDate: 0,
           numberOfMonths: nomonths,
           dateFormat:dformat,
           onSelect: function(selected) {
             $("#arr_date1").datepicker("option","minDate", selected)
           }
       });

       $("#arr_date1").datepicker({ 
           numberOfMonths: nomonths,
           dateFormat:dformat,
           onSelect: function(selected) {
              $("#dep_date2").datepicker("option","minDate", selected)
              //$("#dep_date1_1").datepicker("option","minDate", selected) Jagannath to modify
           }
       });  

       $("#dep_date2").datepicker({
           minDate: 0, 
           numberOfMonths: nomonths,
           dateFormat:dformat,
           onSelect: function(selected) {
              $("#arr_date2").datepicker("option","minDate", selected)
           }
       });  

       $("#arr_date2").datepicker({ 
           minDate: 0,
           numberOfMonths: nomonths,
           dateFormat:dformat,
           onSelect: function(selected) {
              $("#dep_date1_1").datepicker("option","minDate", selected)
           }
       });

       $("#dep_date1_1").datepicker({
           minDate: 0,
           numberOfMonths: 1,
           dateFormat:'dd-mm-yy',
           onSelect: function(selected) {
             $("#arr_date1_1").datepicker("option","minDate", selected)
           }
       });
       $("#arr_date1_1").datepicker({ 
           numberOfMonths: 1,
           dateFormat:'dd-mm-yy',
           onSelect: function(selected) {
              $("#dep_date2_1").datepicker("option","minDate", selected)
           }
       });  
       $("#dep_date2_1").datepicker({
           minDate: 0,
           numberOfMonths: 1,
           dateFormat:'dd-mm-yy',
           onSelect: function(selected) {
             $("#arr_date2_1").datepicker("option","minDate", selected)
           }
       });
       $("#arr_date2_1").datepicker({ 
           numberOfMonths: 1,
           dateFormat:'dd-mm-yy',
           onSelect: function(selected) {
              $("#dep_date2").datepicker("option","maxDate", selected)
           }
       });

   });
</script>
<script>
   $(function() {
      $(".crs_is_triptype").change(function(){
         var is_triptyp = $('input[name=is_triptype]:checked').val();
         $("#returnflightinfo").hide(500);
         if(is_triptyp == 1){
            $("#returnflightinfo").show(500);
         }
      });

      $(".crs_is_domestic").change(function(){
         var is_domestic = $('input[name=is_domestic]:checked').val();
         $("#returnflightinfo").hide(500);
         if(is_domestic == 1){
            $("#triptype").show(500);
            $('#triptype').each(function(){
               $('input[type=radio]', this).get(0).checked = true;
            });
         }else{
            $("#triptype").hide(500);
         }

         $.ajax({url: app_base_url + "index.php/ajax/get_airline_list/"+is_domestic, success: function(result){
            
            var obj = JSON.parse(result);

            var availableTags = new Array();
            var availableTags_obj = new Array();
            availableTags_obj = obj.airline;
            for(i=0;i<obj.airline.length;i++){
               availableTags.push(availableTags_obj[i]); 
            }
      
            $( ".tags" ).autocomplete({
               source: availableTags
            });
            
         }});
      });
      $( window ).load(function() {
        var is_domestic = $('input[name=is_domestic]:checked').val();
        $.ajax({url: app_base_url + "index.php/ajax/get_airline_list/"+is_domestic, success: function(result){
            
            var obj = JSON.parse(result);

            var availableTags = new Array();
            var availableTags_obj = new Array();
            availableTags_obj = obj.airline;
            for(i=0;i<obj.airline.length;i++){
            
               availableTags.push(availableTags_obj[i]); 
             }
      
           $( ".tags" ).autocomplete({
               source: availableTags
             });
            
          }});
      });
   // var availableTags_static = ["Air India(AI)","Jet Airways(9W)","Vistara(UK)","Indigo(6E)","Spicejet(SG)","Go Air(G8)","Air Asia(AK)","Tru Jet(2T)","Aeroflot(SU)","Aerosvit airlines(VV)","Air berlin(AB)","Air canada(AC)","Air china ltd(CA)","Air france(AF)","Air mauritius(MK)","Air newzealand(NZ)","Air sychelles(HM)","Alitalia(AZ)","Al nippon airways(NH)","American airline(AA)","Asiana airlines(OZ)","Austrian airlines(OS)","Bangkok airlines(PG)","Biman bangladesh(BG)","British midland(BD)","British airways(BA)","Bhutan airlines(B3)","Cathay pacific(CX)","China airlines(CI)","China eastern airlines(MU)","China southern airlines(CZ)","Delta air lines(DL)","Egypt air(MS)","El al israel airline(LY)","Emirates(EK)","Ethiopian airlines(ET)","Etihad airways(EY)","Finnair oyj(AJ)","Gulf air(GF)","Heli air(YO)","Japan airlines(JL)","Kenya airways(KQ)","Klm  dutch(KL)","Korean air(KE)","Kuwait airways(KU)","Lufthansa german air(LH)","Mahan air(W5)","Malindo air(OD)","Malaysia airlines(MH)","Mihin lanka(MJ)","Oman air(WY)","Phillipines airlines(PR)","Qantas airways ltd(QF)","Qatar airways(QR)","Regent airways(RX)","Royal bhutan airlines  (druk air)(KB)","Royal brunei airline(BI)","Royal jordanian(RJ)","Royal nepal airlines(RA)","Saudi arabian airline(SV)","Silk air(MI)","Singapore airlines(SQ)","South african airlines(SA)","Srilankan airlines(UL)","Swiss intl airlines(LX)","Thai airways international(TG)","Turkish airlines inc(TK)","Us airways(US)","United airlines(UA)","United airways bd(4H)","Vietnam airlines(VN)","Yemenia yemen airways(IY)","Air arabia(G9)","Indigo special 1(6E1)","Indigo special 2(6E2)","Indigo special 3(6EC)","Spice jet special 1(SG1)","Spice jet special 2(SG2)","Air Astana(KC)","Fly Dubai(FZ)","Virgin Australia(VA)","West Jet(WS)","Icelandair(FI)","Royal Air Maroc(AT)","Finnair(AY)","Aer Lingus(EI)","LOT Polish(LO)","Virgin Atlantic(VS)","Ukraine International Airlines(PS)","Air Serbia(JU)","Pakistan Airlines(PK)","Pegasus Airline(PC)","Middle East Airline(ME)","Eurowings(EW)","HongKong Airlines(HX)","Air Asia - Thai (FD)","Air Asia X(D7)","Air Asia - Indonesia (QZ)","Air Asia - India(I5)"]; 
   
   
  } );

  $(document).ready(function(){
      $('.con_flight_1').hide();
      $('.con_flight_2').hide();

      $('.con_flight_0').css('display','block');

      $(".getAiportlist").autocomplete({
         source: app_base_url+"index.php/flight/get_flight_suggestions",
         minLength: 2,//search after two characters
         autoFocus: true, // first item will automatically be focused
         select: function(event,ui){
             //var inputs = $(this).closest('form').find(':input:visible');
             //inputs.eq( inputs.index(this)+ 1 ).focus();
         }
     });


      $("#destination_0").change(function(){
         var d = $('#destination_0').val();
         document.getElementById('origin_1').value = d;
         //$('#origin_1').attr("disabled","disabled");
      
        });
      $("#sel1").change(function(){
         //con_flight_2
         var flight_stops = $(this).val();
         if(flight_stops == parseInt(0)){
            $('.con_flight_1').show();
            $('#field_set').removeAttr("disabled");
            //$('#field_set').attr("disabled","disabled"); Jagannath
            $('.con_flight_2').hide();
            addReq("con_flight_1");
            removeReq("con_flight_2");
            //$('.con_flight_3').css('display','none');
            /* $('.con_flight_3').css('display','none');
            $('.con_flight_4').css('display','none'); */
         } else if(flight_stops == parseInt(1)){
            $('.con_flight_1').show();
            $('#field_set').removeAttr("disabled");
            $('.con_flight_2').show();
            addReq("con_flight_1");
            addReq("con_flight_2");
            // destination_0
            //$('.con_flight_2').css('display','block');
            /*$('.con_flight_3').css('display','none');
            $('.con_flight_4').css('display','none'); */
         } else if(flight_stops == parseInt(2)){
            $('.con_flight_1').css('display','block');
            $('.con_flight_2').css('display','block');
            addReq("con_flight_1");
            addReq("con_flight_2");            
            // $('.con_flight_3').css('display','block');
            // $('.con_flight_4').css('display','none');
         } else if(flight_stops == parseInt(3)){
            $('.con_flight_1').css('display','block');
            $('.con_flight_2').css('display','block');
            addReq("con_flight_1");
            addReq("con_flight_2");             
            /*$('.con_flight_3').css('display','block'); */
            //$('.con_flight_4').css('display','block');
         } 
      });
      $("#sel1_1").change(function(){
         //con_flight_2
         // var pstop = $("#sel1").val();
         // alert(pstop);
         var flight_stops = $(this).val();
         if(flight_stops == parseInt(0)){
            $('.con_flight_1_2').show();
            $('#field_set').removeAttr("disabled");
            //$('#field_set').attr("disabled","disabled"); Jagannath
            $('.con_flight_2_2').hide();
            addReq("con_flight_1_2");
            removeReq("con_flight_2_2");            
            //$('.con_flight_3').css('display','none');
            /* $('.con_flight_3').css('display','none');
            $('.con_flight_4').css('display','none'); */
         } else if(flight_stops == parseInt(1)){
            $('.con_flight_1_2').show();
            $('#field_set').removeAttr("disabled");
            $('.con_flight_2_2').show();
            addReq("con_flight_1_2");
            addReq("con_flight_2_2");
            // destination_0
            //$('.con_flight_2').css('display','block');
            /*$('.con_flight_3').css('display','none');
            $('.con_flight_4').css('display','none'); */
         } else if(flight_stops == parseInt(2)){
            $('.con_flight_1_2').css('display','block');
            $('.con_flight_2_2').css('display','block');
            addReq("con_flight_1_2");
            addReq("con_flight_2_2");             
            // $('.con_flight_3').css('display','block');
            // $('.con_flight_4').css('display','none');
         } else if(flight_stops == parseInt(3)){
            $('.con_flight_1_2').css('display','block');
            $('.con_flight_2_2').css('display','block');
            addReq("con_flight_1_2");
            addReq("con_flight_2_2");            
            /*$('.con_flight_3').css('display','block'); */
            //$('.con_flight_4').css('display','block');
         } 
      });      
   });

   function addReq(divCls){
      $('.'+divCls+' input').attr('required', 'required');
      $('.'+divCls+' select').attr('required', 'required');
      $('.'+divCls+' option').attr('required', 'required');
   }

   function removeReq(divCls){
      $('.'+divCls+' input').removeAttr('required');
      $('.'+divCls+' select').removeAttr('required');
      $('.'+divCls+' option').removeAttr('required');
   }

  </script>
<script type="text/javascript">
$(document).ready(function(){
   //$('.timepicker-24').wickedpicker({now: '8:16', twentyFour: true, title: 'Time', showSeconds: false});
//   $('.timepicker-24').wickedpicker({twentyFour: true, title: 'Time', showSeconds: false});
  //$('.timepicker-24').wickedpicker({twentyFour: true});
});
</script>
<!-- <script>
   $('.timepicker-24').timepicker();
</script> -->
<script type="text/javascript">
$(document).ready(function(){
    $(".add_flight_date").click(function() {
        $("#ad_cont").clone()
            .removeAttr("id")
            .append( $('<a class="delete_flight_date" ><i class="fa fa-times-circle" aria-hidden="true"></i></a>') )
            .appendTo("#adnl_cont");
    });
    $("body").on('click',".delete_flight_date", function() {
        $(this).closest(".add_cont").remove();
    });
    $(".add_flight_date1").click(function() {
        $("#ad_cont1").clone()
            .removeAttr("id")
            .append( $('<a class="delete_flight_date1" ><i class="fa fa-times-circle" aria-hidden="true"></i></a>') )
            .appendTo("#adnl_cont1");
    });
    $("body").on('click',".delete_flight_date1", function() {
        $(this).closest(".add_cont1").remove();
    });
    $(".add_fare_date").click(function() {
        /*$("#ad_fare").clone()
            .removeAttr("id")
            .append( $('<a class="delete_fare" ><i class="fa fa-times-circle" aria-hidden="true"></i></a>') )
            .appendTo("#adnl_fare");*/
            var cnt = $("#hidcnt").val();
            var ccnt = cnt*1+1;
             $("#hidcnt").val(ccnt);
         var htmldata = $("#ad_fare").html();
         htmldata = htmldata.replace(/dep_date_0/g,"dep_date_"+ccnt);
         htmldata = htmldata.replace(/arr_date_0/g,"arr_date_"+ccnt);

         htmldata = htmldata.replace(/hasDatepicker/g,"");
         htmldata = htmldata.replace(/delete_0/g,"delete_"+ccnt);
         htmldata = htmldata.replace(/fare_row_0/g,"fare_row_"+ccnt);
         $("#adnl_fare").append(htmldata);
         //$("#ad_fare").html(htmldata);
         $("#delete_"+ccnt).show();
            $("#dep_date_"+ccnt).datepicker({
                 minDate: 0,
                 numberOfMonths: 1,
                 dateFormat:'dd-mm-yy',
                 onSelect: function(selected) {
                  // $("#arr_date1").datepicker("option","minDate", selected)
                 }
             });

            $("#arr_date_"+ccnt).datepicker({
                 minDate: 0,
                 numberOfMonths: 1,
                 dateFormat:'dd-mm-yy',
                 onSelect: function(selected) {
                  // $("#arr_date1").datepicker("option","minDate", selected)
                 }
             });

    });
    $("body").on('click',".delete_fare", function() {
        $(this).closest(".add_fare").remove();

    });
    
  
});

function deletethisrow(id){
   var delId = id;
   var tempID = delId.split("_");
   var divId  = tempID[1];
   $("#fare_row_"+divId).html("");   
}
</script>