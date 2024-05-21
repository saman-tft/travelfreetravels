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

             <!--  <div class="navmenu">
			      <nav class="navbar navbar-inverse">
                    <?php
                    $menu_active = $GLOBALS ['CI']->uri->segment ( 2 );
                    $transfer_list_admin=""; 
                      if($menu_active == "view_transfer_list")
                      {
                        $transfer_list_admin="active";  
                      }
                    

                    $transfer_list_staff="";
                     if($menu_active == "view_transfer_list_staff")
                      {
                        $transfer_list_staff="active";  
                      }

                    $transfer_list_supplier="";
                     if($menu_active == "view_transfer_list_supplier")
                      {
                        $transfer_list_supplier="active";  
                      }

                    


                    ?>
        					<ul class="nav navbar-nav icimg">

            					<li class="<?= $transfer_list_admin;?>">
                        <a href="<?= base_url()."transfers/view_transfer_list" ?>">
                          <i class="fa fa-taxi"></i> Admin Transfer Approval List
                        </a>
                      </li>

            					<li class="<?= $transfer_list_staff;?>">
                        <a href="<?php echo base_url().'index.php/transfers/view_transfer_list_staff'?>">
                          <i class="fa fa-taxi"></i> Staff Transfer Approval List
                        </a>
                      </li>

            					<li class="<?= $transfer_list_supplier; ?>">
                        <a href="<?php echo base_url().'index.php/transfers/view_transfer_list_supplier'?>">
                        <i class="fa fa-taxi"></i> Supplier Transfer Approval List
                        </a>
                      </li>

            					
                  </ul>
			         </nav>
		        </div>-->