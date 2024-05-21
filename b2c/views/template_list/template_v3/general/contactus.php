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

<div class="clearfix"></div>
<div class="innerbanner-image">
	<div class="container">
<div class="innerbanner-image_img">
     <span> <h1>Contact Us</h1></span>
 </div>
</div>
</div>
<div class="clearfix"></div>


<section class="contact_quarters">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="pagehdwrap investor-heading" style="margin:0">
                    <h2 class="pagehding "> Head Quarters </h2>

                </div>
                <div class="quarter-block1">
                    <h3><i class="fa fa-home" aria-hidden="true"></i> Address</h3>
                    <?php $query = "SELECT * FROM contact_us_details";
                            $get_data= $this->db->query($query)->result();?>
                    <p><i class="fa fa-map-marker" aria-hidden="true"></i> <?php echo $get_data[0]->address1; ?></p>
                </div>
                <div class="quarter-block1">
                    <h3><i class="fa fa-phone" aria-hidden="true"></i> Phone Number</h3>
                    <p><i class="fa fa-phone" aria-hidden="true"> </i><?php echo $get_data[0]->phone1; ?></p>
                    <p><i class="fab fa-whatsapp" aria-hidden="true"></i><?php echo $get_data[0]->phone2; ?></p>
                    <p><i class="fab fa-whatsapp" aria-hidden="true"></i><?php echo $get_data[0]->phone3; ?></p>
                </div>
                <div class="quarter-block1">
                    <h3><i class="fa fa-envelope" aria-hidden="true"></i> Email Address</h3>
                    <p><i class="fa fa-envelope" aria-hidden="true"></i><a
                            href="mailto:<?php echo $get_data[0]->email1; ?>"><?php echo $get_data[0]->email1; ?></a>
                    </p>
                    <p><i class="fa fa-envelope" aria-hidden="true"></i><a
                            href="mailto:<?php echo $get_data[0]->email2; ?>"><?php echo $get_data[0]->email2; ?></a>
                    </p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="pagehdwrap investor-heading" style="margin:0">
                    <h2 class="pagehding "> Lets get in touch </h2>

                </div>
                <div class="form-contact">
                    <div class="contact_form">
                        <h2 style="">
                            FIll in the details and we will get back to you soon</h2>
                        <form role="form" id="" enctype="multipart/form-data" method="POST"
                            action="<?=base_url().'index.php/general/contact_us_details'?>" autocomplete="off">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Name" name="custname"
                                    id="c_name" />
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control" placeholder="Email Address" name="email"
                                    id="c_email" />
                            </div>
                            <span id="invalid_email"></span>

                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Phone Number" name="phone"
                                    maxlength="15" id="c_phone" onkeydown="return ( event.ctrlKey || event.altKey 
                    || (47<event.keyCode && event.keyCode<58 && event.shiftKey==false) 
                    || (95<event.keyCode && event.keyCode<106)
                    || (event.keyCode==8) || (event.keyCode==9) 
                    || (event.keyCode>34 && event.keyCode<40) 
                    || (event.keyCode==46) )" />
                            </div>
                            <span id="errmsg"></span>

                            <div class="form-group">
                                <textarea class="form-control" placeholder="Message" name="message"
                                    id="c_message"></textarea>
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn_sub subsbtms" value="Submit">
                            </div>
                            <strong style="font-weight: 18px;" id="contactmsg"></strong>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="contact_branches">
    <div class="container">
        <div class="row">
            <div class="pagehdwrap investor-heading" style="margin:0">
                <h2 class="pagehding " style="color:#fff;"> Branches </h2>

            </div>
            <?php 
                $branches_data= json_encode(array(array('branch_name' => "", 'branch_phone' => "", 'branch_order' => "1", 'branch_address' => "")));
                
                $branches_data = json_decode($branches_data);
                if($get_data[0]->branches_detail != ""){
                    $branches_data=json_decode($get_data[0]->branches_detail);
                    $keys = array_column($branches_data, 'branch_order');
                    array_multisort($keys, SORT_ASC, $branches_data);
                   
                }
            ?>
            <?php foreach($branches_data as $key => $value): ?>
            <div class="col-md-4">
                <div class="branch-block">
                    <div class="quarter-block1">
                        <img src="<?=$GLOBALS['CI']->template->domain_images($value->branch_flag)?>"
                            class="branches_img" alt="">
                        <h4><?=$value->branch_name?></h4>
                        <p><i class="fa fa-map-marker" aria-hidden="true"></i> <?=$value->branch_address?></p>
                        <p><i class="fa fa-phone" aria-hidden="true"></i> <?=$value->branch_phone?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
           
        </div>
    </div>
</section>


<div class="clearfix"></div>



