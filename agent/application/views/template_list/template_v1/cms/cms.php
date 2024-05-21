<?php
if (isset($login) == false || is_object($login) == false) {
	$login = new Provab_Page_Loader('login');
}
?>
<style type="text/css">
.sec_topmenu{background: #fff;}
.skin-black-light .wrapper{background: #e6f9f7!important;}
    .background_login {background: #e6f9f7;position: relative;}
    .cmsbg{margin-top: 30px;
        margin-bottom: 50px;
    background: #fff;
    box-shadow: 1px 0px 10px 1px #ccc;}
    .cmsheadr h1{background: #0e1938;
    color: #fff;
    padding: 7px 10px;
    font-size: 26px!important;}
    .cmspgtext h2{background: #0e1938;
    color: #fff;
    padding: 7px 10px;
    font-size: 24px!important;}
    /*.cmspgtext{padding: 20px 0px;}*/
    .cmspgtext p{font-size: 15px;margin-bottom:15px;}
    .cmspgtext strong{font-weight: bold;}
</style>



<div class="background_login">
    <div class="sec_topmenu">
    <div class="container">
    <div class="log_head">
        <span class="open-panel"><i class="fa fa-navicon"></i></span>  
        <div class="col-md-3 col-sm-3 Tx_lef">
            <div class="logoContainer">
                <a href="<?php echo base_url();?>"><img src="<?php echo $GLOBALS['CI']->template->domain_images($GLOBALS['CI']->template->get_domain_logo())?>" alt="logo"  class="img-responsive center-block"></a>
            </div>
        </div>
        <nav class="HeaderNavigation col-md-9 col-sm-9">
            <span class="close-panel"><i class="fa fa-close"></i></span>
            <ul class="menu Tx_rig">
                

            </ul>
        </nav>
    </div>
    </div>
    </div>
    
    	<!-- <div class="loadcity"></div>
    
    	<div class="clodnsun"></div>
        
        <div class="reltivefligtgo">
        	<div class="flitfly"></div>
        </div>

        <div class="clearfix"></div>
        <div class="busrunning">
            <div class="runbus"></div>
            <div class="runbus2"></div>
            <div class="roadd"></div>
        </div> -->
        <div class="container cmsbg">
   <div class="col-md-12 col-xs-12">
<div class="lblbluebold16px cmsheadr"><h1><?php

//debug($data);die;
echo $data[0]['page_title'];?></h1></div>
<div class="lblfont12px cmspgtext"><p><?php echo $data[0]['page_description'];?></p></div>
</div> 
</div>



</div>
<div class="clearfix">
<div class="btm_footer">
    <div class="container">
        <div class="row">
          
            <ul class="footerNav">
<ul>
                                            <?php
                                            $cond = array(
                                                'page_status' => ACTIVE
                                            );
                                            $cms_data = $this->custom_db->single_table_records('cms_pages', '', $cond);
                                           //debug($cms_data);exit;
                                            foreach ($cms_data ['data'] as $keys => $values) {
                                                if ($keys>6 && $keys<=10) {
                                                   //echo '<li class="frteli"><a href="' . base_url () . 'index.php/general/cms/Bottom/' . $values ['page_id'] . '">' . $values ['page_title'] . ' <br> </a></li>';
                                                }
                                                
                                            }
                                            ?>
                                        </ul>
            </ul>
        </div>
    </div>
</div>










