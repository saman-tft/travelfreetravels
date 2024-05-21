
<?php
$active_domain_modules = $this->active_domain_modules;
$default_active_tab = $default_view;

function set_default_active_tab($module_name, &$default_active_tab) {
	if (empty ( $default_active_tab ) == true || $module_name == $default_active_tab) {
		if (empty ( $default_active_tab ) == true) {
			$default_active_tab = $module_name; // Set default module as current active module
		}
		return 'active';
	}
}

//add to js of loader
Js_Loader::$css[] = array('href' => $GLOBALS['CI']->template->template_css_dir('backslider.css'), 'media' => 'screen');
Js_Loader::$css[] = array('href' => $GLOBALS['CI']->template->template_css_dir('owl.carousel.min.css'), 'media' => 'screen');
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('owl.carousel.min.js'), 'defer' => 'defer');
 Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('backslider.js'), 'defer' => 'defer');
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/index.js'), 'defer' => 'defer');
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/pax_count.js'), 'defer' => 'defer');
?>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.js"></script>
<div class="clearfix"></div>



<div class="inner-banner">
	<div class="container-fluid">
     
     <img class="" src="<?php echo $slideImageJson[0]['image']; ?>" alt="best travel agency platform">
     <h1><?php echo $slideImageJson[0]['title']; ?></h1>
    </div>
 </div>

<div class="clearfix"></div>
	<div class="trustedexperts">
		<div class="container">
		<div class="pagehdwrap investor-heading">
            <h2 class="pagehding ">Trusted by Experts</h2>
			<p>Alkhaleej Tours offers you the best flights,hotels,Car hire,Packages,transfer and Holidays around the globe.We are a highly dedicated team working for many years and trusted by our partners from around the globe.</p>
		</div>
			<div class="trtexp">
				 <div class="container">


				 	<div class="customer-logos slider">
				 		<?php $query = "SELECT * FROM trusted_by_experts_images WHERE status='1' ORDER BY banner_order ASC";
            			$get_data= $this->db->query($query)->result_array(); 
            			foreach ($get_data as $key => $value) { ?> 
	                    <div class="slide"><img class="lazy lazy_loader" src="<?php echo base_url().IMG_UPLOAD_DIR.$value['image']; ?>" alt="best travel agency platform"></div>
	                    <?php } ?> 
	                    
                   </div>

			  </div>
				
			</div>
		</div>
	</div>
<div class="clearfix"></div>
<!--who we are start-->
<div class="whowre" style="background:#fff;">
    <div class="container">
        <div class="pagehdwrap investor-heading">
            <h2 class="pagehding ">Who we are?</h2>
            <p>Alkhaleej is one of the best travel agency platform's with following features.</p>
        </div>
        <div class="row">

            <div class="col-md-6">

                <div class="row">
                    <?php $query = "SELECT * FROM who_we_are_images WHERE status='1' ORDER BY banner_order ASC";
			$get_data= $this->db->query($query)->result_array(); 
			foreach ($get_data as $key => $value) { ?>
                    <div class="col-md-6 prime-invest">
                        <div class="whowearesect">
                            <img src="<?php echo base_url().IMG_UPLOAD_DIR.$value['image']; ?>"
                                alt="best travel agency platform">
                        </div>
                        <div class="whowe-tag">
                            <h5><?php echo $value['title'];?></h5>
                        </div>
                    </div>

                    <?php } ?>

                </div>
            </div>
            <div class="col-md-6">
                <div class="invest-image">
                    <img src="https://www.alkhaleejtours.com/extras/system/template_list/template_v3/images/invest-we-are.jpg"
                        width="100%" alt="">
                </div>
            </div>
        </div>
    </div>
</div>

<!--who we are end-->
<div class="clearfix"></div>
<!--experience profile start-->
<div class="experprof professional-invest">
    <div class="container">
        <div class="pagehdwrap investor-heading">
            <h2 class="pagehding text-white">We Are Experienced Creative And Professional</h2>
        </div>

        <div class="col-lg-4 col-md-4 col-xs-12">
            <div class="professional-icon">
                <div class="icon-invest">
                    <img src="https://www.alkhaleejtours.com/extras/system/template_list/template_v3/images/pro-invest1.png"
                        width="100%" alt="">
                </div>
                <div class="content-invest">
                    <span>95k</span><br> Happy Customers
                </div>
            </div>

        </div>
        <div class="col-lg-4 col-md-4 col-xs-12">
            <div class="professional-icon">
                <div class="icon-invest">
                    <img src="https://www.alkhaleejtours.com/extras/system/template_list/template_v3/images/pro-invest2.png"
                        width="100%" alt="">
                </div>
                <div class="content-invest">
                    <span>100% </span><br>Satisfaction
                </div>
            </div>

        </div>
        <div class="col-lg-4 col-md-4 col-xs-12">
            <div class="professional-icon">
                <div class="icon-invest">
                    <img src="https://www.alkhaleejtours.com/extras/system/template_list/template_v3/images/pro-invest3.png"
                        width="100%" alt="">
                </div>
                <div class="content-invest">
                    <span>10</span><br> Years of Experience
                </div>
            </div>

        </div>
    </div>
</div>
</div>

<!--experience profile end-->
<div class="clearfix"></div>
<!--
<div class="ychoose">
	<div class="container">
		<div class="pagehdwrap">
			<h2 class="pagehding">Why Choose us</h2>
		</div>
		<div class="allys">
			<div class="col-xs-12 col-md-3">
				<div class="threey">
					<div class="apritopty">
						<img src="https://www.alkhaleejtours.com/extras/system/template_list/template_v3/images/support.png" alt="best travel agency platform" >
					</div>
					<div class="dismany">
						<div class="number">
							24/7 Support
						</div>
						<div class="hedsprite">
							All financial sites open for visitors, with 24/7 video surveillance
						</div>
					</div>
					<div>
					</div>
				</div>
			</div>
			
			<div class="col-xs-12 col-md-3">
				<div class="threey">
					<div class="apritopty">
						<img src="https://www.alkhaleejtours.com/extras/system/template_list/template_v3/images/team.png" alt="best travel agency platform">
					</div>
					<div class="dismany">
						<div class="number">
							Our Dedicated Team
						</div>
						<div class="hedsprite">
							We have a team of specialists capable of maximizing the result and delivering services
						</div>
					</div>
					<div>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-md-3">
				<div class="threey">
					<div class="apritopty">
						<img src="https://www.alkhaleejtours.com/extras/system/template_list/template_v3/images/choice.png" alt="best travel agency platform">
					</div>
					<div class="dismany">
						<div class="number">
							Fair Price
						</div>
						<div class="hedsprite">
							We give you the best deal in the market.
						</div>
					</div>
					<div>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-md-3">
				<div class="threey">
					<div class="apritopty">
						<img src="https://www.alkhaleejtours.com/extras/system/template_list/template_v3/images/offer.png" alt="best travel agency platform">
					</div>
					<div class="dismany">
						<div class="number">
							Best Offers
						</div>
						<div class="hedsprite">
							All aspects of our operations are transparent and clear to our clients and business partners.
						</div>
					</div>
					<div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
-->
<div class="clearfix"></div>

<div class="investmentchart">
	<div class="container">
	<div class="pagehdwrap investor-heading">
            <h2 class="pagehding">Investment Chart</h2>
        </div>
		<div class="invest-sec">
			<div class="">
			<div class="package-sp">
				<h5>Silver Package - Earning 4% Profits</h5>
				<table class="table table-responsive table-striped">
				<tr class="package-sp-first">
					<th>SL NO</th>
					<th>INVEST</th>
					<th>MONTHLY INCOME</th>
					<th>YEARLY PROFITS</th>
					<th>DEPOSIT RETURNS</th>
				    <th>6 MONTHS PROFIT</th>
				</tr>
				<?php $query = "SELECT * FROM investment_chart WHERE package_title='silver_package'";
    			$get_data= $this->db->query($query)->result_array(); 
    			foreach ($get_data as $key => $value) { ?> 
				<tr>
				    <td><?=($key+1)?></td>
				    <td><?=$value['invest'];?></td>
				    <td><?=$value['monthly_income'];?></td>
				    <td><?=$value['yearly_profits'];?></td>
				    <td><?=$value['deposit_returns'];?></td>
				    <td><?=$value['months_profit'];?></td>
				</tr>
				<?php } ?> 
				</table>
				</div>

          

          <div class="package-gp">
				<h5>Gold Package - Earning 8% Profits</h5>
				<table class="table table-responsive table-striped">
				<tr class="package-gp-first">
					<th>SL NO</th>
					<th>INVEST</th>
					<th>MONTHLY INCOME</th>
					<th>YEARLY PROFITS</th>
					<th>DEPOSIT RETURNS</th>
				    <th>6 MONTHS PROFIT</th>
				</tr>
				<?php $query = "SELECT * FROM investment_chart WHERE package_title='gold_package'";
    			$get_data= $this->db->query($query)->result_array(); 
    			foreach ($get_data as $key => $value) { ?> 
				<tr>
				    <td><?=($key+1)?></td>
				    <td><?=$value['invest'];?></td>
				    <td><?=$value['monthly_income'];?></td>
				    <td><?=$value['yearly_profits'];?></td>
				    <td><?=$value['deposit_returns'];?></td>
				    <td><?=$value['months_profit'];?></td>
				</tr>
				<?php } ?>
				</table>
				</div>

				<div class="package-pp">
				<h5>Platinum Package - Earning 8% Profits</h5>
				<table class="table table-responsive table-striped">
				<tr class="package-pp-first">
					<th>SL NO</th>
					<th>INVEST</th>
					<th>MONTHLY INCOME</th>
					<th>YEARLY PROFITS</th>
					<th>DEPOSIT RETURNS</th>
				    <th>6 MONTHS PROFIT</th>
				</tr>
				<?php $query = "SELECT * FROM investment_chart WHERE package_title='platinum_package'";
    			$get_data= $this->db->query($query)->result_array(); 
    			foreach ($get_data as $key => $value) { ?> 
				<tr>
				    <td><?=($key+1)?></td>
				    <td><?=$value['invest'];?></td>
				    <td><?=$value['monthly_income'];?></td>
				    <td><?=$value['yearly_profits'];?></td>
				    <td><?=$value['deposit_returns'];?></td>
				    <td><?=$value['months_profit'];?></td>
				</tr>
				<?php } ?>
				</table>
				</div>

			</div>
			 
		</div>
	</div>
</div>
<div class="clearfix"></div>
<!--package tabs start-->
<div class="package-tabs">
<div class="container">
<div class="pagehdwrap investor-heading" style="margin-bottom:10px;">
            <h2 class="pagehding">Order Now</h2>
        </div>
 	<div class="col-md-12 nopad">
            <div class="panel pnl with-nav-tabs panel-default">
                
                <div class="panel-body pnlbdy">
                    <div class="tab-content tbcont">
                        <div class="tab-pane fade in active" id="plan"><!--plan start-->
							<form class="form-horizontal" role="form" id="plan_retirement" enctype="multipart/form-data" method="POST" action="<?=base_url().'index.php/user/plan_retirement'?>" autocomplete="off" name="plan_retirement">
								<div class="form-row">
								<div class="form-group col-md-4">
								  <label for="name">NAME</label>
								  <input type="text" class="form-control" name="fullname" id="name" placeholder="Name" required="">
								</div>
								<div class="form-group col-md-4">
								  <label for="email">EMAIL</label>
								  <input type="email" class="form-control" name="email" id="email" placeholder="Email" required="">
								</div>
								  <div class="form-group col-md-4">
								  <label for="phone">PHONE</label>
								  <input type="text" class="form-control" name="phone" id="phone" placeholder="Phone Number" maxlength="15" required="" onkeydown="return ( event.ctrlKey || event.altKey 
                    || (47<event.keyCode && event.keyCode<58 && event.shiftKey==false) 
                    || (95<event.keyCode && event.keyCode<106)
                    || (event.keyCode==8) || (event.keyCode==9) 
                    || (event.keyCode>34 && event.keyCode<40) 
                    || (event.keyCode==46) )">
								</div>
							  </div>
								<div class="form-row">
								<div class="form-group col-md-4">
								  <label for="country">COUNTRY</label>
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
                        ?>
                        <select name="country" id="country_id" class="form-control select_form" required>
                              <option value="">Select Country</option>
                              <?=generate_options($country_list,'');?>
                            </select>
                          </div>
                         <?php if(!empty(form_error('country'))) { ?>
                        <div class="b2c_error"><?php echo form_error('country');?></div>
                        <?php } ?>
								
								<div class="form-group col-md-4">
								  <label for="state">STATE</label>
								  <input type="text" class="form-control" name="state" id="state" placeholder="State">
								</div>
								  <div class="form-group col-md-4">
								   <label for="city">CITY</label>
								  <select name="city"  id="city_id" class="form-control select_form" required>
                              <option value = '' selected="">Select City</option>
                            </select>
                            
                          </div>
                          <?php if(!empty(form_error('city'))) { ?>
                        <div class="b2c_error"><?php echo form_error('city');?></div>
                        <?php } ?>
								</div>
							  
							  <div class="form-row">
								<div class="form-group col-md-4">
								  <label for="zipcode">ZIP CODE</label>
								  <input type="text" class="form-control" name="zipcode" id="zipcode" placeholder="Zipcode" pattern="[a-zA-Z0-9]+" maxlength="8" required="">
								</div>
								<div class="form-group col-md-4">
								  <label for="address">ADDRESS</label>
								 <input type="text" class="form-control" name="address" id="address" placeholder="1234 Main St" required="">
								</div>
								  <div class="form-group col-md-4">
								 <div class="custom-file">
									<label class="custom-file-label" for="customFile">ID COPY</label>
								  <input type="file" class="form-control custom-file-input" name="passid" id="customFile" required="">
								 </div>
								</div>
							  </div>
								
								<div class="form-row">
								<div class="form-group col-md-4">
								<div class="custom-file">
									<label class="custom-file-label" for="customFile">PASSPORT COPY</label>
								  <input type="file" class="form-control custom-file-input" name="passcopy" id="customFile" required="">
								 </div>
								</div>
								<div class="form-group col-md-4">
								  <label for="passportnumber">PASSPORT NUMBER</label>
								  <input type="text" class="form-control" name="passno" id="passportnumber" required="">
								</div>
								  <div class="clearfix"></div>
							  </div>
								<div class="form-row">
									<div class="form-group col-md-12">
									  <label for="message">MESSAGE</label>
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
							  
							   <button class="btn btn-primary order-now" type="submit">ORDER NOW</button>
							</form>
						</div><!--plan end-->
                        <div class="tab-pane fade" id="home">form content2</div>
                        <div class="tab-pane fade" id="funds">form content3</div>
                        <div class="tab-pane fade" id="budget">form content4</div>
                        <div class="tab-pane fade" id="business">form content5</div>
						<div class="tab-pane fade" id="veichle">form content6</div>
                        <div class="tab-pane fade" id="emergency">form content7</div>
                        <div class="tab-pane fade" id="education">form content8</div>
                    </div>
                </div>
            </div>
        </div>
</div>
</div>
<!--package tabs start-->
<div class="clearfix"></div>


<!--testimonial start-->
<div class="testimonialsec">
    <div class="container">
	<div class="pagehdwrap investor-heading">
            <h2 class="pagehding">Testimonials</h2>
        </div>
        <div class="trtexp">
            <div class="container nopad">
                <div class="grid">
                    <div class="testimonials-investor" >
                        <?php $query = "SELECT * FROM testimonial_images WHERE status='1' ORDER BY banner_order ASC";
			$get_data= $this->db->query($query)->result_array(); 
			foreach ($get_data as $key => $value) { ?>
                        <div class="col-md-4">
                            <div class="col-sm-12 col-xs-12 nopad htd-wrap plctstyspc-testi">
                                <div class="threeyy">
                                    <div class="apritoptyy">
                                        <img src="<?php echo base_url().IMG_UPLOAD_DIR.$value['image']; ?>"
                                            alt="best travel agency platform">
											<div class="testiname"><span
                                                style="font-size:30px"></span><?php echo $value['title'];?><br />
											<i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i>  <i class="fa fa-star"></i>  <i class="fa fa-star"></i>  </div>
                                    </div>
                                    <div class="dismanyy">
                                       
                                        <div class="hedspritee">
                                            <?php echo $value['description'];?>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <?php } ?>





                    </div>
                </div>
            </div>
        </div>
    </div>
</div>





<script type="text/javascript">
var default_city = '<?=$default_city;?>';
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
      $.post(app_base_url+'index.php/ajax/get_city_lists',{  country_id : country_id},function( data ) {
         $("#city_id").empty().html(data);
         $('#city_id').val(default_city)
      });
    }
});    
</script>

<script type="text/javascript">
	function gettype(id){
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

<!--testimonial end-->



<script>  $(document).ready(function() {
        var testimoni1 = $("#testimonials");

        testimoni1.owlCarousel({      
            itemsCustom : [
                 [0, 1],
                [450, 2],
                [551, 3],
                [700, 4],
                [1000, 3],
                [1200, 3],
                [1400, 3],
                [1600, 3]
            ],
            navigation : true,
            loop: true,
            autoPlay:true,
            autoplayTimeout: 5000,
            autoplayHoverPause: true,
			pagination: false

        });
		  });</script> 


		  <script>

		  function openCity(evt, cityName) {
  // Declare all variables
  var i, tabcontent, tablinks;

  // Get all elements with class="tabcontent" and hide them
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }

  // Get all elements with class="tablinks" and remove the class "active"
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }

  // Show the current tab, and add an "active" class to the button that opened the tab
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.className += " active";
}
		  </script>

		  <script type="text/javascript">
		  	var el = $('input[name="investment"]');
			el.prop("autocomplete",false); // remove autocomplete (optional)
			el.on('keydown',function(e){
				var allowedKeyCodesArr = [9,96,97,98,99,100,101,102,103,104,105,48,49,50,51,52,53,54,55,56,57,8,37,39,109,189,46,110,190];  // allowed keys
				if($.inArray(e.keyCode,allowedKeyCodesArr) === -1 && (e.keyCode != 17 && e.keyCode != 86)){  // if event key is not in array and its not Ctrl+V (paste) return false;
					e.preventDefault();
				} else if($.trim($(this).val()).indexOf('.') > -1 && $.inArray(e.keyCode,[110,190]) != -1){  // if float decimal exists and key is not backspace return fasle;
					e.preventDefault();
				} else {
					return true;
				};  
			}).on('paste',function(e){  // on paste
				var pastedTxt = e.originalEvent.clipboardData.getData('Text').replace(/[^0-9.]/g, '');  // get event text and filter out letter characters
				if($.isNumeric(pastedTxt)){  // if filtered value is numeric
					e.originalEvent.target.value = pastedTxt;
					e.preventDefault();
				} else {  // else 
					e.originalEvent.target.value = ""; // replace input with blank (optional)
					e.preventDefault();  // retur false
				};
			});
		  </script>

		<script type="text/javascript">
	$(document).ready(function(){
    $('.customer-logos').slick({
        slidesToShow: 6,
        autoplay: true,
        autoplaySpeed: 0,
        speed: 1000,
        cssEase: "linear",
        arrows: false,
        dots: false,
        pauseOnHover: false,
        responsive: [{
            breakpoint: 768,
            settings: {
                slidesToShow: 4
            }
        }, {
            breakpoint: 520,
            settings: {
                slidesToShow: 3
            }
        }]
    });
});
</script>

