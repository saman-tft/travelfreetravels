<form name="b2as_user" autocomplete="off" action="<?=base_url()?>index.php/user/b2as_user?<?=$_SERVER['QUERY_STRING']?>" method="POST" enctype="multipart/form-data" id="b2as_user" role="form" class="form-horizontal">
  <fieldset form="b2as_user" class="service_selection_crs">
     
                    <div class="clearfix"></div>
                     <!-----Tourcrs--->
              <div class="tour_crs box selectt" style="<?php echo ($default_view == 'tour_crs') ?  "display: block" : "display: none" ;  ?>">
                <div class="each_sections">
                  <div class="sec_heading">Tour Supplier Info</div>
                    <div class="inside_regwrp">
                      <div class="col-sm-12 nopad">
                        <div class="wrap_space" style="width: 100%;">
                          <div class="div_wrap">
                            <div class="col-sm-6 nopad">
                              <div class="wrap_space">
                                <div class="label_form">Company Type<span class="text-danger"></span></div>
                                  <div class="div_wrap">
                                      <input type="text" name="tour_company_name"  placeholder="Company Type" value="<?php echo set_value('company_type'); ?>" id="company_type" class="input_form alpha_space " maxlength="45" />
                                  </div>
                                   <!--  <?php if(!empty(form_error('company_name'))) { ?>
                                    <div class="agent_error"><?php //echo form_error('company_name');?></div>
                                    <?php } ?>-->
                              </div>
                            </div>
                           <div class="col-sm-6 nopad">
                              <div class="wrap_space">
                                <div class="label_form">Authorised Person <span class="text-danger"></span></div>
                                  <div class="div_wrap">
                                    <input type="text" name="tour_authorised_person"  placeholder="Authorised person" value="<?php echo set_value('company_name'); ?>" id="company_name" class="input_form alpha_space " maxlength="45" />
                                  </div>
                                 <!--  <?php if(!empty(form_error('company_name'))) { ?>
                                  <div class="agent_error"><?php //echo form_error('company_name');?></div>
                                  <?php } ?>-->
                               </div>
                            </div>
                            <div class="col-sm-6 nopad">
                              <div class="wrap_space">
                                <div class="label_form">Contact Person <span class="text-danger"></span></div>
                                  <div class="div_wrap">
                                    <input type="text" name="tour_contact_person"  placeholder="Contact person" value="<?php echo set_value('contact_person'); ?>" id="contact_person" class="input_form alpha_space " maxlength="45" />
                                  </div>
                                   <!--  <?php if(!empty(form_error('company_name'))) { ?>
                                    <div class="agent_error"><?php //echo form_error('company_name');?></div>
                                    <?php } ?>-->
                              </div>
                            </div>
                            <div class="col-sm-6 nopad">
                              <div class="wrap_space">
                                <div class="label_form">Supplier Site<span class="text-danger"></span></div>
                                  <div class="div_wrap">
                                    <input type="text" name="tour_supplier_site"  placeholder="Supplier site" value="<?php echo set_value('company_name'); ?>" id="Supplier_site" class="input_form " maxlength="" />
                                  </div>
                                   <!--  <?php if(!empty(form_error('company_name'))) { ?>
                                    <div class="agent_error"><?php //echo form_error('company_name');?></div>
                                    <?php } ?>-->
                              </div>
                            </div>
                            <div class="col-sm-6 nopad">
                      <div class="wrap_space">
                        <div class="label_form">Country <span class="text-danger"></span></div>
                        <div class="select_wrap">
                        <?php 
                          if(empty(set_value('tour_country')) == false) {
                            $default_country = set_value('tour_country');
                          } else {
                            //$default_country = $active_data['api_country_list_fk'];
                            $default_country = 212;
                          }
                          if(empty(set_value('city')) == false) {
                            $default_city = set_value('city');
                          } else {
                            // $default_city = $active_data['api_city_list_fk'];
                            $default_city = 212;
                          }
                        ?>
                            <select name="tour_country" id="tour_country_id" class="select_form ">
                              <option value="">Select Country</option>
                              <?=generate_options($country_list, $default_country);?>
                            </select>
                          </div>
                        <!-- <?php if(!empty(form_error('country'))) { ?>
                        <div class="agent_error"><?php //echo form_error('country');?></div>
                        <?php } ?>-->
                      </div>
                    </div>
                    <div class="col-sm-6 nopad">
                              <div class="wrap_space">
                                <div class="label_form">Business Type<span class="text-danger"></span></div>
                                  <div class="div_wrap">
                                    <input type="text" name="tour_business_type"  placeholder="Business Type" value="<?php echo set_value('jet_business_type'); ?>" id="company_name" class="input_form alpha_space " maxlength="45" />
                                  </div>
                                 <!--  <?php if(!empty(form_error('company_name'))) { ?>
                                  <div class="agent_error"><?php //echo form_error('company_name');?></div>
                                  <?php } ?>-->
                               </div>
                            </div>
                            <div class="col-sm-6 nopad">
                            <div class="wrap_space">
                              <div class="label_form">Tour Operators<span class="text-danger"></span></div>
                                <div class="select_wrap">
                                <select name="tour_operator" class="select_form noborderit">
                                 <option value="">Select</option>
                                 <option value="Inbound">Inbound Tour Operator</option>
                                 <option value="Outbound">Outbound Tour Operator</option>
                                 <option value="Domestic">Domestic Tour Operator</option>
                                 <option value="Ground">Ground Tour Operator</option>
                                 
                            </select>
                            
                          </div>
                             </div>
                        </div>
                            
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
               </div>
             <div class="clearfix"></div> 
    <!-----Tourcrs--->   
     <!----villas_apts&hotel------->

      <div class="villas_apts box selectt" style="<?php echo ($default_view == 'villas_apts') ?  "display: block" : "display: none" ;  ?>">
                <div class="each_sections">
                  <div class="sec_heading">Villas & Apts/Hotels Supplier Info</div>
                    <div class="inside_regwrp">
                      <div class="col-sm-12 nopad">
                        <div class="wrap_space" style="width: 100%;">
                          <div class="div_wrap">
                            <div class="col-sm-6 nopad">
                              <div class="wrap_space">
                                <div class="label_form">Company Type<span class="text-danger"></span></div>
                                  <div class="div_wrap">
                                      <input type="text" name="hotel_company_name"  placeholder="Company Type" value="<?php echo set_value('hotel_company_name'); ?>" id="company_type" class="input_form alpha_space " maxlength="45" />
                                  </div>
                                   <!--  <?php if(!empty(form_error('company_name'))) { ?>
                                    <div class="agent_error"><?php //echo form_error('company_name');?></div>
                                    <?php } ?>-->
                              </div>
                            </div>
                           <div class="col-sm-6 nopad">
                              <div class="wrap_space">
                                <div class="label_form">Authorised Person <span class="text-danger"></span></div>
                                  <div class="div_wrap">
                                    <input type="text" name="hotel_authorised_person"  placeholder="Authorised person" value="<?php echo set_value('hotel_authorised_person'); ?>" id="company_name" class="input_form alpha_space " maxlength="45"  />
                                  </div>
                                 <!--  <?php if(!empty(form_error('company_name'))) { ?>
                                  <div class="agent_error"><?php //echo form_error('company_name');?></div>
                                  <?php } ?>-->
                               </div>
                            </div>
                            <div class="col-sm-6 nopad">
                              <div class="wrap_space">
                                <div class="label_form">Contact Person <span class="text-danger"></span></div>
                                  <div class="div_wrap">
                                    <input type="text" name="hotel_contact_person"  placeholder="Contact person" value="<?php echo set_value('hotel_contact_person'); ?>" id="contact_person" class="input_form alpha_space " maxlength="45"  />
                                  </div>
                                   <!--  <?php if(!empty(form_error('company_name'))) { ?>
                                    <div class="agent_error"><?php //echo form_error('company_name');?></div>
                                    <?php } ?>-->
                              </div>
                            </div>
                            <div class="col-sm-6 nopad">
                              <div class="wrap_space">
                                <div class="label_form">Star Rating<span class="text-danger"></span></div>
                                  <div class="div_wrap">
                                    <input type="text" name="hotel_star_rating"  placeholder="Star Rating" value="<?php echo set_value('hotel_star_rating'); ?>" id="star_rating" class="input_form alpha_space " maxlength="45"  />
                                  </div>
                                   <!--  <?php if(!empty(form_error('company_name'))) { ?>
                                    <div class="agent_error"><?php //echo form_error('company_name');?></div>
                                    <?php } ?>-->
                              </div>
                            </div>
                            <div class="col-sm-6 nopad">
                              <div class="wrap_space">
                                <div class="label_form">Number of rooms<span class="text-danger"></span></div>
                                  <div class="div_wrap">
                                    <input type="text" name="hotel_num_room"  placeholder="Number of rooms" value="<?php echo set_value('hotel_num_room'); ?>" id="star_rating" class="input_form alpha_space " maxlength="45" />
                                  </div>
                                   <!--  <?php if(!empty(form_error('company_name'))) { ?>
                                    <div class="agent_error"><?php //echo form_error('company_name');?></div>
                                    <?php } ?>-->
                              </div>
                            </div>
                            <div class="col-sm-6 nopad">
                              <div class="wrap_space">
                                <div class="label_form">Supplier Site<span class="text-danger"></span></div>
                                  <div class="div_wrap">
                                    <input type="text" name="hotel_supplier_site"  placeholder="Supplier site" value="<?php echo set_value('hotel_supplier_site'); ?>" id="Supplier_site" class="input_form " maxlength=""  />
                                  </div>
                                   <!--  <?php if(!empty(form_error('company_name'))) { ?>
                                    <div class="agent_error"><?php //echo form_error('company_name');?></div>
                                    <?php } ?>-->
                              </div>
                            </div>
                            <div class="col-sm-6 nopad">
                      <div class="wrap_space">
                        <div class="label_form">Country <span class="text-danger"></span></div>
                        <div class="select_wrap">
                        <?php 
                          if(empty(set_value('hotel_country')) == false) {
                            $default_country = set_value('hotel_country');
                          } else {
                            //$default_country = $active_data['api_country_list_fk'];
                            $default_country = 212;
                          }
                          if(empty(set_value('city')) == false) {
                            $default_city = set_value('city');
                          } else {
                            // $default_city = $active_data['api_city_list_fk'];
                            $default_city = 212;
                          }
                          $default_country="+1";
                        ?>
                            <select name="hotel_country" id="hotel_country_id" class="select_form " >
                              <option value="">Select Country</option>
                              <?=generate_options($country_list, $default_country);?>
                            </select>
                          </div>
                        <!-- <?php if(!empty(form_error('country'))) { ?>
                        <div class="agent_error"><?php //echo form_error('country');?></div>
                        <?php } ?>-->
                      </div>
                    </div>
                    <div class="col-sm-6 nopad">
                              <div class="wrap_space">
                                <div class="label_form">Business Type<span class="text-danger"></span></div>
                                  <div class="div_wrap">
                                    <input type="text" name="hotel_business_type"  placeholder="Business Type" value="<?php echo set_value('hotel_business_type'); ?>" id="company_name" class="input_form alpha_space " maxlength="45" />
                                  </div>
                                 <!--  <?php if(!empty(form_error('company_name'))) { ?>
                                  <div class="agent_error"><?php //echo form_error('company_name');?></div>
                                  <?php } ?>-->
                               </div>
                            </div>
                            
                            
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
               </div>
             <div class="clearfix"></div>


             <!----villas_apts&hotel------>
              <!----privatetransfer------>

              <div class="private_transfer box selectt" style="<?php echo ($default_view == 'private_transfer') ?  "display: block" : "display: none" ;  ?>">
                <div class="each_sections">
                  <div class="sec_heading">Private Transfer Supplier Info</div>
                    <div class="inside_regwrp">
                      <div class="col-sm-12 nopad">
                        <div class="wrap_space" style="width: 100%;">
                          <div class="div_wrap">
                            <div class="col-sm-6 nopad">
                              <div class="wrap_space">
                                <div class="label_form">Company Type<span class="text-danger"></span></div>
                                  <div class="div_wrap">
                                      <input type="text" name="transfer_company_name"  placeholder="Company Type" value="<?php echo set_value('transfer_company_name'); ?>" id="company_type" class="input_form alpha_space " maxlength="45" />
                                  </div>
                                   <!--  <?php if(!empty(form_error('company_name'))) { ?>
                                    <div class="agent_error"><?php //echo form_error('company_name');?></div>
                                    <?php } ?>-->
                              </div>
                            </div>
                           <div class="col-sm-6 nopad">
                              <div class="wrap_space">
                                <div class="label_form">Authorised Person <span class="text-danger"></span></div>
                                  <div class="div_wrap">
                                    <input type="text" name="transfer_authorised_person"  placeholder="Authorised person" value="<?php echo set_value('transfer_authorised_person'); ?>" id="company_name" class="input_form alpha_space " maxlength="45" />
                                  </div>
                                 <!--  <?php if(!empty(form_error('company_name'))) { ?>
                                  <div class="agent_error"><?php //echo form_error('company_name');?></div>
                                  <?php } ?>-->
                               </div>
                            </div>
                            <div class="col-sm-6 nopad">
                              <div class="wrap_space">
                                <div class="label_form">Contact Person <span class="text-danger"></span></div>
                                  <div class="div_wrap">
                                    <input type="text" name="transfer_contact_person"  placeholder="Contact person" value="<?php echo set_value('transfer_contact_person'); ?>" id="contact_person" class="input_form alpha_space " maxlength="45" />
                                  </div>
                                   <!--  <?php if(!empty(form_error('company_name'))) { ?>
                                    <div class="agent_error"><?php //echo form_error('company_name');?></div>
                                    <?php } ?>-->
                              </div>
                            </div>
                            <div class="col-sm-6 nopad">
                              <div class="wrap_space">
                                <div class="label_form">Supplier Site<span class="text-danger"></span></div>
                                  <div class="div_wrap">
                                    <input type="text" name="transfer_supplier_site"  placeholder="Supplier site" value="<?php echo set_value('transfer_supplier_site'); ?>" id="Supplier_site" class="input_form " maxlength="" />
                                  </div>
                                   <!--  <?php if(!empty(form_error('company_name'))) { ?>
                                    <div class="agent_error"><?php //echo form_error('company_name');?></div>
                                    <?php } ?>-->
                              </div>
                            </div>
                            <div class="col-sm-6 nopad">
                      <div class="wrap_space">
                        <div class="label_form">Country <span class="text-danger">*</span></div>
                        <div class="select_wrap">
                        <?php 
                          if(empty(set_value('transfer_country')) == false) {
                            $default_country = set_value('transfer_country');
                          } else {
                            //$default_country = $active_data['api_country_list_fk'];
                            $default_country = 212;
                          }
                          if(empty(set_value('city')) == false) {
                            $default_city = set_value('city');
                          } else {
                            // $default_city = $active_data['api_city_list_fk'];
                            $default_city = 212;
                          }
                        ?>
                            <select name="transfer_country" id="transfer_country_id" class="select_form " >
                              <option value="">Select Country</option>
                              <?=generate_options($country_list, $default_country);?>
                            </select>
                          </div>
                        <!-- <?php if(!empty(form_error('country'))) { ?>
                        <div class="agent_error"><?php //echo form_error('country');?></div>
                        <?php } ?>-->
                      </div>
                    </div>
                    <div class="col-sm-6 nopad">
                              <div class="wrap_space">
                                <div class="label_form">Business Type<span class="text-danger"></span></div>
                                  <div class="div_wrap">
                                    <input type="text" name="transfer_business_type"  placeholder="Business Type" value="<?php echo set_value('transfer_business_type'); ?>" id="company_name" class="input_form alpha_space " maxlength="45" />
                                  </div>
                                 <!--  <?php if(!empty(form_error('company_name'))) { ?>
                                  <div class="agent_error"><?php //echo form_error('company_name');?></div>
                                  <?php } ?>-->
                               </div>
                            </div>
                            <div class="col-sm-6 nopad">
                            <div class="wrap_space">
                              <div class="label_form">Transfer Type<span class="text-danger"></span></div>
                                <div class="div_wrap">
                                  <input type="text" name="transfer_type"  placeholder="Transfer Type" value="<?php echo set_value('transfer_type'); ?>" id="company_name" class="input_form alpha_space " maxlength="45" />
                                </div>
                               <!--  <?php if(!empty(form_error('company_name'))) { ?>
                                <div class="agent_error"><?php //echo form_error('company_name');?></div>
                                <?php } ?>-->
                             </div>
                        </div>
                        <div class="col-sm-6 nopad">
                            <div class="wrap_space">
                              <div class="label_form">Quantity Of Transfer<span class="text-danger"></span></div>
                                <div class="div_wrap">
                                  <input type="text" name="transfer_quantity"  placeholder="Quantity Of Transfer" value="<?php echo set_value('transfer_quantity'); ?>" id="company_name" class="input_form alpha_space " maxlength="45" />
                                </div>
                               <!--  <?php if(!empty(form_error('company_name'))) { ?>
                                <div class="agent_error"><?php //echo form_error('company_name');?></div>
                                <?php } ?>-->
                             </div>
                        </div>
                            
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
               </div>
             <div class="clearfix"></div>

               <!----privatetransfer------>

                <!----privatecar------>

              <div class="private_car box selectt" style="<?php echo ($default_view == 'private_car') ?  "display: block" : "display: none" ;  ?>">
                <div class="each_sections">
                  <div class="sec_heading">Private Car Supplier Info</div>
                    <div class="inside_regwrp">
                      <div class="col-sm-12 nopad">
                        <div class="wrap_space" style="width: 100%;">
                          <div class="div_wrap">
                            <div class="col-sm-6 nopad">
                              <div class="wrap_space">
                                <div class="label_form">Company Type<span class="text-danger"></span></div>
                                  <div class="div_wrap">
                                      <input type="text" name="car_company_name"  placeholder="Company Type" value="<?php echo set_value('car_company_name'); ?>" id="company_type" class="input_form alpha_space " maxlength="45" />
                                  </div>
                                   <!--  <?php if(!empty(form_error('company_name'))) { ?>
                                    <div class="agent_error"><?php //echo form_error('company_name');?></div>
                                    <?php } ?>-->
                              </div>
                            </div>
                           <div class="col-sm-6 nopad">
                              <div class="wrap_space">
                                <div class="label_form">Authorised Person <span class="text-danger"></span></div>
                                  <div class="div_wrap">
                                    <input type="text" name="car_authorised_person"  placeholder="Authorised person" value="<?php echo set_value('car_authorised_person'); ?>" id="company_name" class="input_form alpha_space " maxlength="45" />
                                  </div>
                                 <!--  <?php if(!empty(form_error('company_name'))) { ?>
                                  <div class="agent_error"><?php //echo form_error('company_name');?></div>
                                  <?php } ?>-->
                               </div>
                            </div>
                            <div class="col-sm-6 nopad">
                              <div class="wrap_space">
                                <div class="label_form">Contact Person <span class="text-danger"></span></div>
                                  <div class="div_wrap">
                                    <input type="text" name="car_contact_person"  placeholder="Contact person" value="<?php echo set_value('car_contact_person'); ?>" id="contact_person" class="input_form alpha_space " maxlength="45" />
                                  </div>
                                   <!--  <?php if(!empty(form_error('company_name'))) { ?>
                                    <div class="agent_error"><?php //echo form_error('company_name');?></div>
                                    <?php } ?>-->
                              </div>
                            </div>
                            <div class="col-sm-6 nopad">
                              <div class="wrap_space">
                                <div class="label_form">Supplier Site<span class="text-danger"></span></div>
                                  <div class="div_wrap">
                                    <input type="text" name="car_supplier_site"  placeholder="Supplier site" value="<?php echo set_value('car_supplier_site'); ?>" id="Supplier_site" class="input_form " maxlength="" />
                                  </div>
                                   <!--  <?php if(!empty(form_error('company_name'))) { ?>
                                    <div class="agent_error"><?php //echo form_error('company_name');?></div>
                                    <?php } ?>-->
                              </div>
                            </div>
                            <div class="col-sm-6 nopad">
                      <div class="wrap_space">
                        <div class="label_form">Country <span class="text-danger"></span></div>
                        <div class="select_wrap">
                        <?php 
                          if(empty(set_value('car_country')) == false) {
                            $default_country = set_value('car_country');
                          } else {
                            //$default_country = $active_data['api_country_list_fk'];
                            $default_country = 212;
                          }
                          if(empty(set_value('city')) == false) {
                            $default_city = set_value('city');
                          } else {
                            // $default_city = $active_data['api_city_list_fk'];
                            $default_city = 212;
                          }
                        ?>
                            <select name="car_country" id="car_country_id" class="select_form ">
                              <option value="">Select Country</option>
                              <?=generate_options($country_list, $default_country);?>
                            </select>
                          </div>
                        <!-- <?php if(!empty(form_error('country'))) { ?>
                        <div class="agent_error"><?php //echo form_error('country');?></div>
                        <?php } ?>-->
                      </div>
                    </div>
                    <div class="col-sm-6 nopad">
                        <div class="wrap_space">
                          <div class="label_form">Business Type<span class="text-danger"></span></div>
                            <div class="div_wrap">
                              <input type="text" name="car_business_type"  placeholder="Business Type" value="<?php echo set_value('car_business_type'); ?>" id="company_name" class="input_form alpha_space " maxlength="45" />
                            </div>
                           <!--  <?php if(!empty(form_error('company_name'))) { ?>
                            <div class="agent_error"><?php //echo form_error('company_name');?></div>
                            <?php } ?>-->
                         </div>
                    </div>
                    <div class="col-sm-6 nopad">
                        <div class="wrap_space">
                          <div class="label_form">Car Type<span class="text-danger"></span></div>
                            <div class="div_wrap">
                              <input type="text" name="car_type"  placeholder="Car Type" value="<?php echo set_value('car_type'); ?>" id="company_name" class="input_form alpha_space " maxlength="45" />
                            </div>
                           <!--  <?php if(!empty(form_error('company_name'))) { ?>
                            <div class="agent_error"><?php //echo form_error('company_name');?></div>
                            <?php } ?>-->
                         </div>
                    </div>
                    <div class="col-sm-6 nopad">
                        <div class="wrap_space">
                          <div class="label_form">Quantity Of Cars<span class="text-danger"></span></div>
                            <div class="div_wrap">
                              <input type="text" name="car_quantity"  placeholder="Quantity Of Cars" value="<?php echo set_value('car_quantity'); ?>" id="company_name" class="input_form alpha_space " maxlength="45" />
                            </div>
                           <!--  <?php if(!empty(form_error('company_name'))) { ?>
                            <div class="agent_error"><?php //echo form_error('company_name');?></div>
                            <?php } ?>-->
                         </div>
                    </div>
                            
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
               </div>
             <div class="clearfix"></div>

               <!----privatecar------>

               <!----privatejet------>

               <div class="private_jet box selectt" style="<?php echo ($default_view == 'private_jet') ?  "display: block" : "display: none" ;  ?>">
                <div class="each_sections">
                  <div class="sec_heading">Private Jet Supplier Info</div>
                    <div class="inside_regwrp">
                      <div class="col-sm-12 nopad">
                        <div class="wrap_space" style="width: 100%;">
                          <div class="div_wrap">
                            <div class="col-sm-6 nopad">
                              <div class="wrap_space">
                                <div class="label_form">Company Type<span class="text-danger"></span></div>
                                  <div class="div_wrap">
                                      <input type="text" name="jet_company_name"  placeholder="Company Type" value="<?php echo set_value('jet_company_name'); ?>" id="company_type" class="input_form alpha_space " maxlength="45" />
                                  </div>
                                   <!--  <?php if(!empty(form_error('company_name'))) { ?>
                                    <div class="agent_error"><?php //echo form_error('company_name');?></div>
                                    <?php } ?>-->
                              </div>
                            </div>
                           <div class="col-sm-6 nopad">
                              <div class="wrap_space">
                                <div class="label_form">Authorised Person <span class="text-danger"></span></div>
                                  <div class="div_wrap">
                                    <input type="text" name="jet_authorised_person"  placeholder="Authorised person" value="<?php echo set_value('jet_authorised_person'); ?>" id="company_name" class="input_form alpha_space " maxlength="45" />
                                  </div>
                                 <!--  <?php if(!empty(form_error('company_name'))) { ?>
                                  <div class="agent_error"><?php //echo form_error('company_name');?></div>
                                  <?php } ?>-->
                               </div>
                            </div>
                            <div class="col-sm-6 nopad">
                              <div class="wrap_space">
                                <div class="label_form">Contact Person <span class="text-danger"></span></div>
                                  <div class="div_wrap">
                                    <input type="text" name="jet_contact_person"  placeholder="Contact person" value="<?php echo set_value('jet_contact_person'); ?>" id="contact_person" class="input_form alpha_space " maxlength="45" />
                                  </div>
                                   <!--  <?php if(!empty(form_error('company_name'))) { ?>
                                    <div class="agent_error"><?php //echo form_error('company_name');?></div>
                                    <?php } ?>-->
                              </div>
                            </div>
                            <div class="col-sm-6 nopad">
                              <div class="wrap_space">
                                <div class="label_form">Supplier Site<span class="text-danger"></span></div>
                                  <div class="div_wrap">
                                    <input type="text" name="jet_supplier_site"  placeholder="Supplier site" value="<?php echo set_value('jet_supplier_site'); ?>" id="Supplier_site" class="input_form " maxlength="" />
                                  </div>
                                   <!--  <?php if(!empty(form_error('company_name'))) { ?>
                                    <div class="agent_error"><?php //echo form_error('company_name');?></div>
                                    <?php } ?>-->
                              </div>
                            </div>
                            <div class="col-sm-6 nopad">
                      <div class="wrap_space">
                        <div class="label_form">Country <span class="text-danger"></span></div>
                        <div class="select_wrap">
                        <?php 
                          if(empty(set_value('jet_country')) == false) {
                            $default_country = set_value('jet_country');
                          } else {
                            //$default_country = $active_data['api_country_list_fk'];
                            $default_country = 212;
                          }
                          if(empty(set_value('city')) == false) {
                            $default_city = set_value('city');
                          } else {
                            // $default_city = $active_data['api_city_list_fk'];
                            $default_city = 212;
                          }
                        ?>
                            <select name="jet_country" id="jet_country_id" class="select_form ">
                              <option value="">Select Country</option>
                              <?=generate_options($country_list, $default_country);?>
                            </select>
                          </div>
                        <!-- <?php if(!empty(form_error('country'))) { ?>
                        <div class="agent_error"><?php //echo form_error('country');?></div>
                        <?php } ?>-->
                      </div>
                    </div>
                    <div class="col-sm-6 nopad">
                              <div class="wrap_space">
                                <div class="label_form">Business Type<span class="text-danger"></span></div>
                                  <div class="div_wrap">
                                    <input type="text" name="jet_business_type"  placeholder="Business Type" value="<?php echo set_value('jet_business_type'); ?>" id="company_name" class="input_form alpha_space " maxlength="45" />
                                  </div>
                                 <!--  <?php if(!empty(form_error('company_name'))) { ?>
                                  <div class="agent_error"><?php //echo form_error('company_name');?></div>
                                  <?php } ?>-->
                               </div>
                            </div>
                            <div class="col-sm-6 nopad">
                        <div class="wrap_space">
                          <div class="label_form">Jet Type<span class="text-danger"></span></div>
                            <div class="div_wrap">
                              <input type="text" name="jet_type"  placeholder="Jet Type" value="<?php echo set_value('jet_type'); ?>" id="company_name" class="input_form alpha_space " maxlength="45" />
                            </div>
                           <!--  <?php if(!empty(form_error('company_name'))) { ?>
                            <div class="agent_error"><?php //echo form_error('company_name');?></div>
                            <?php } ?>-->
                         </div>
                    </div>
                    <div class="col-sm-6 nopad">
                        <div class="wrap_space">
                          <div class="label_form">Quantity Of Jet<span class="text-danger"></span></div>
                            <div class="div_wrap">
                              <input type="text" name="jet_quantity"  placeholder="Quantity Of Jet" value="<?php echo set_value('jet_quantity'); ?>" id="company_name" class="input_form alpha_space " maxlength="45" />
                            </div>
                           <!--  <?php if(!empty(form_error('company_name'))) { ?>
                            <div class="agent_error"><?php //echo form_error('company_name');?></div>
                            <?php } ?>-->
                         </div>
                    </div>
                            
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
               </div>
             <div class="clearfix"></div>

               <!----privatejet------>
      

   </fieldset>





   <fieldset form="b2as_user">
      <legend class="form_legend">User Profile</legend>
      <input name="user_id" type="hidden" id="user_id" class=" user_id hiddenIp" required="" value="<?=isset($user_id)?$user_id:''?>">
      <div class="form-group">
         <label class="col-sm-3 control-label" for="title" form="b2as_user">Title<span class="text-danger">*</span></label>
         <div class="col-sm-6">
            <select required="" dt="PROVAB_SOLID_SB01" name="title" class=" title form-control" id="title" data-container="body" data-toggle="popover" data-original-title="Here To Help" data-placement="bottom" data-trigger="hover focus" data-content="Title Ex:Mr, Miss.">
               <option value="INVALIDIP">Please Select</option>
               <option value="1" <?=($title==1)?'selected':''?> >Mr</option>
               <option value="2" <?=($title==2)?'selected':''?>>Ms</option>
               <option value="3" <?=($title==3)?'selected':''?>>Miss</option>
               <option value="4" <?=($title==4)?'selected':''?>>Master</option>
            </select>
         </div>
      </div>
      <div class="form-group">
         <label class="col-sm-3 control-label" for="first_name" form="b2as_user">First Name<span class="text-danger">*</span></label>
         <div class="col-sm-6">
          <input value="<?=isset($first_name)?$first_name:''?>" name="first_name" required="" type="text" placeholder="First Name" class="first_name form-control" id="first_name" ></div>
      </div>
      <div class="form-group">
         <label class="col-sm-3 control-label" for="last_name" form="b2as_user">Last Name<span class="text-danger">*</span></label>
         <div class="col-sm-6"><input value="<?=isset($last_name)?$last_name:''?>" name="last_name" required="" type="text" placeholder="Last Name" class=" last_name form-control" id="last_name">
         </div>
      </div>
      <div class="form-group">
          <?php
         //  debug($phone_code_array);
           $country_code=+1;
          ?>
         <label class="col-sm-3 control-label" for="country_code" form="b2as_user">Country Code<span class="text-danger">*</span></label>
         <div class="col-sm-6">
            <select required="" name="country_code" class=" country_code form-control" id="country_code">
                
                <option value="+977">Nepal +977</option>
               <?=generate_options($phone_code_array, $country_code)?>
            </select>
         </div>
      </div>
      <div class="form-group">
         <label class="col-sm-3 control-label" for="phone" form="b2as_user">Phone Number<span class="text-danger">*</span></label>
         <div class="col-sm-6"><input maxlength="15" minlength="7" value="<?=isset($phone)?$phone:''?>" name="phone"  required="" type="text" placeholder="Phone Number" class=" phone form-control" id="phone" ></div>
      </div>
  
      <div class="radio">
        <label class="col-sm-3 control-label" for="status" form="b2as_user">User Status<span class="text-danger">*</span></label>
        <label class="radio-inline" for="b2as_userstatus1">  
          <input <?php if(isset($status) && $status==1){ echo 'checked'; }?> required="" dt="PROVAB_SOLID_B01" class=" status radioIp" type="radio" name="status" id="b2as_userstatus1" value="1">Active
        </label>
        <label class="radio-inline" for="b2as_userstatus0">  <input <?php if(isset($status) && $status==0){ echo 'checked'; }?> required=""  class=" status radioIp" type="radio" name="status" id="b2as_userstatus0" value="0">Inactive</label></div>

      <input name="language_preference" type="hidden" id="language_preference" class=" language_preference hiddenIp" value="">
   </fieldset>

   <fieldset form="b2as_user">  
    <legend class="form_legend">Company Details</legend>   
      <div class="form-group">
         <label class="col-sm-3 control-label" for="compant_name" form="b2as_user">Company Name<span class="text-danger">*</span></label>
         <div class="col-sm-6">
               <input value="<?=isset($agency_name)?$agency_name:''?>" name="agency_name" required="" type="company_name" placeholder="Company Name" class=" form-control" id="company_name"></div>
      </div>
        <div class="form-group">
         <label class="col-sm-3 control-label" for="address" form="b2as_user">Address<span class="text-danger">*</span></label>
         <div class="col-sm-6"><textarea required=""  name="address" id="address" rows="3" class=" address form-control" data-original-title="" title=""><?=isset($address)?$address:''?></textarea></div>
      </div>
      <div class="form-group">
         <label class="col-sm-3 control-label" for="country_id" form="b2as_user">Country</label>
          
         <div class="col-sm-6">
          <?php 
          //debug($country_name);exit();
            $this->db->select('*');
            $this->db->from('api_country_list');
            $this->db->where('origin',$country_name);
            $query=$this->db->get();
            foreach ($query->result_array() as  $value) {
              # code...
            }
            $cname=$value['name'];
           // debug($cname);exit();
              $this->db->select('*');
            $this->db->from('api_state_list');
            $this->db->where('origin',$state);
            $query2=$this->db->get();
            foreach ($query2->result_array() as  $value) {
              # code...
            }
$state_name=$value['en_name'];
           ?>
          <select name="country" id="country_id" class="form-control" required>
            <option value="<?php echo $country_name; ?>" selected><?php echo $cname; ?></option>
              
               <?=generate_options($country_list, $country_code);?> 
          </select>
       </div>
      </div>
      <div class="form-group">
         <label class="col-sm-3 control-label" for="state_name" form="b2as_user">State/Province<span class="text-danger">*</span></label>
         <div class="col-sm-6">
         <input value="<?=isset($state_name)?$state_name:''?>" name="state_name" required="" type="text" placeholder="" class=" state_name alpha form-control" id="state_name"></div>
      </div>
      <div class="form-group">
         <label class="col-sm-3 control-label" for="city_name" form="b2as_user">City<span class="text-danger">*</span></label>
         <div class="col-sm-6"><input value="<?=isset($city_name)?$city_name:''?>" name="city_name" required="" type="text" placeholder="City" class="alpha city_name form-control" id="city_name"></div>
      </div>
      <div class="form-group">
         <label class="col-sm-3 control-label" for="pin_code" form="b2as_user">Postal Code<span class="text-danger">*</span></label>
         <div class="col-sm-6"><input minlength="3" maxlength="6" value="<?=isset($pin_code)?$pin_code:''?>" name="pin_code" required="" type="text" placeholder="" class=" pin_code form-control" id="pin_code" data-original-title="" title=""></div>
      </div>

        <div class="form-group">
         <label class="col-sm-3 control-label" for="ofc_country_code" form="b2as_user">Office Phone Code<span class="text-danger">*</span></label>
         <div class="col-sm-6">
             <?php
             $ofc_country_code="+977";
             ?>
            <select required="" name="ofc_country_code" class=" ofc_country_code form-control" id="ofc_country_code" >
                <option value="+977">Nepal +977</option>
               <?=generate_options($phone_code_array, $ofc_country_code)?>
            </select>
         </div>
      </div>
       <div class="form-group">
         <label class="col-sm-3 control-label" for="office_phone" form="b2as_user">Office Phone<span class="text-danger">*</span></label>
         <div class="col-sm-6"><input minlength="7" maxlength="15" value="<?=isset($office_phone)?$office_phone:''?>" name="office_phone" required="" type="text" placeholder="" class=" office_phone phone form-control" id="office_phone" data-original-title="" title=""></div>
      </div>
      <div class="form-group">
         <label class="col-sm-3 control-label" for="comp_website_link" form="b2as_user">Company Website link<span class="text-danger">*</span></label>
         <div class="col-sm-6"><input minlength="3"  value="<?=isset($comp_website_link)?$comp_website_link:''?>" name="comp_website_link" required="" type="text" placeholder="" class=" comp_website_link form-control" id="comp_website_link" data-original-title="" title=""></div>
      </div>
      <div class="form-group">
         <label class="col-sm-3 control-label" for="comp_email" form="b2as_user">Company Email ID<span class="text-danger">*</span></label>
         <div class="col-sm-6"><input  value="<?=isset($comp_email)?$comp_email:''?>" name="comp_email" required="" type="email" placeholder="" class=" comp_email form-control" id="comp_email" data-original-title="" title=""></div>
      </div>
      
   </fieldset>
   <?php 
     function get_crs_checked($module){
          $flag='false';
          foreach ($supplier_privailage as $key => $value) {
              if($module==$value){
                    $flag='true';
              }
          }          
          return $flag;
     }
   ?>
   <fieldset form="b2as_user">
      <legend class="form_legend">Login Details</legend>
           <div class="checkbox">
           
               <!-- <label class="col-sm-3 control-label" for="user_type" form="b2as_user">CRS Type</label><label class="radio-inline" for="b2as_useruser_type2">  
                    <input class=" user_type checkboxIp" type="checkbox" name="user_type[]" id="b2as_useruser_type2" value="2" required="required" <?php if(get_crs_checked(2)=='true'){ echo 'checked'; }?>>Activity CRS
               </label>
               <label class="radio-inline" for="b2as_useruser_type3">  
                    <input class=" user_type checkboxIp" type="checkbox" name="user_type[]" id="b2as_useruser_type3" value="3" required="required" <?php if(get_crs_checked(3)=='true'){ echo 'checked'; }?>>Hotel CRS 
               </label>
               <label class="radio-inline" for="b2as_useruser_type4">  
                    <input class=" user_type checkboxIp" type="checkbox" name="user_type[]" id="b2as_useruser_type4" value="4" required="required" <?php if(get_crs_checked(4)=='true'){ echo 'checked'; }?>>Transfers CRS 
                    </label> -->
               </div>
      <div class="form-group">
         <label class="col-sm-3 control-label" for="email" form="b2as_user">Email ID<span class="text-danger">*</span></label>
         <div class="col-sm-6"><input <?=isset($email)?'value="'.$email.'" readonly':''?> name="email"  required="" type="email" placeholder="Email ID" class=" email form-control" id="email" /></div>
      </div>
      <?php if(!$this->input->get('eid')){?> 
        <div class="form-group">
           <label class="col-sm-3 control-label" for="password" form="b2as_user">Password<span class="text-danger">*</span></label>
           <div class="col-sm-6"><input value="" name="password"  required="" type="password" placeholder="Password" class=" password form-control" id="password" ></div>
        </div>
        <div class="form-group">
           <label class="col-sm-3 control-label" for="confirm_password" form="b2as_user">Confirm Password<span class="text-danger">*</span></label>
           <div class="col-sm-6"><input value="" name="confirm_password" required="" type="password" placeholder="Confirm Password" class=" confirm_password form-control" id="confirm_password" ></div>
        </div>
      <?php }?>
      
   </fieldset>

   <div class="form-group">
      <div class="col-sm-8 col-sm-offset-4"> <button type="submit" id="b2as_user_submit" class=" btn btn-success ">Save</button> <button type="reset" id="b2as_user_reset" class=" btn btn-warning ">Reset</button></div>
   </div>
</form>
<script type="text/javascript">
            $(document).ready(function() {
                $('.service_sel').click(function() {
                    var inputValue = $(this).attr("value");
                    $("." + inputValue).toggle();
                    //$(".service_sel").removeAttribute("required");
                    $('.service_sel').attr('required', false); 


                });
                $('.businesstype_check').attr('required', false); 
            });
            $('body').on('click','#suppliersubmit',function(){
            $service_checked = $('.service_sel:checked');
            if($service_checked['length'] == 0){
              alert("please select atleast one service");
              return false;
              
            }
           
            });
        </script>