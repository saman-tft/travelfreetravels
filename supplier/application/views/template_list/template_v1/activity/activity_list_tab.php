 <head>  

<link href="<?php echo $GLOBALS['CI']->template->template_css_dir_hotel('bootstrap.min.css'); ?>" rel="stylesheet" >

<link href="<?php echo $GLOBALS['CI']->template->template_css_dir_hotel('font-awesome.min.css'); ?>" rel="stylesheet" > 

<link href="<?php echo $GLOBALS['CI']->template->template_css_dir_hotel('style.css'); ?>" rel="stylesheet" > 

 <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700,900&display=swap" rel="stylesheet">
	 <style>.top_header img {
    float: left;
    width: 18px;
		 padding-right:1px;padding-top:2px;}</style> 
 </head>  
 
  
              <div class="clearfix"></div>

            <div class="navmenu">
			         <nav class="navbar navbar-inverse">
                    <?php
                    $menu_active = $GLOBALS ['CI']->uri->segment ( 2 );
                    $activity_list_admin=""; 
                      if($menu_active == "view_with_price")
                      {
                        $activity_list_admin="active";  
                      }
                    

                    $activity_list_staff="";
                     if($menu_active == "view_with_price_staff")
                      {
                        $activity_list_staff="active";  
                      }

                    $activity_list_supplier="";
                     if($menu_active == "view_with_price_supplier")
                      {
                        $activity_list_supplier="active";  
                      }

                    


                    ?>
        					<ul class="nav navbar-nav icimg">

            					<li class="<?= $activity_list_admin;?>">
                        <a href="<?= base_url()."activity/view_with_price" ?>">
                          <i class="fa fa-binoculars"></i> Admin Excursion Approval List
                        </a>
                      </li>

            					<li class="<?= $activity_list_staff;?>">
                        <a href="<?php echo base_url().'index.php/activity/view_with_price_staff'?>">
                          <i class="fa fa-binoculars"></i> Staff Excursion Approval List
                        </a>
                      </li>

            					<li class="<?= $activity_list_supplier; ?>">
                        <a href="<?php echo base_url().'index.php/activity/view_with_price_supplier'?>">
                        <i class="fa fa-binoculars"></i> Supplier Excursion Approval List
                        </a>
                      </li>

            					
                  </ul>
			         </nav>
		        </div>