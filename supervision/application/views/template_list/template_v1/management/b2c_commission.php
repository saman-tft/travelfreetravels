<?php
$tab1 = 'active';
if($domain_admin_exists == true && (isset($eid) == false || empty($eid) == true)) {
	//if domain admin exists and not in update mode, then hide add form
	$domain_admin_exists = true;
} else {
	$domain_admin_exists = false;
}
// echo $action;exit;
?>
<!-- HTML BEGIN -->
<div class="bodyContent">
    <div class="panel panel-default"><!-- PANEL WRAP START -->

<!-- PANEL HEAD START -->

        <div class="panel-heading"><!-- PANEL HEAD START -->
            <div class="panel-title">
                <ul class="nav nav-tabs" role="tablist" id="myTab">
                    <!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->
                    <li role="presentation" class="<?= $tab1 ?>">
                        <a id="fromListHead" href="#fromList" aria-controls="AddSectors" role="tab"	data-toggle="tab">Generate Net Rate Sectors</a>
                    </li>
                    <li role="presentation" class="<?= $tab2 ?>">
                        <a id="fromListHead" href="#tableList" aria-controls="List" role="tab"	data-toggle="tab">Manage List</a>
                    </li>
                    <!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE END -->
                </ul>
            </div>
        </div>
        <!-- PANEL HEAD START -->
        <div class="panel-body"><!-- PANEL BODY START -->
            <span class="error_msg"><?php if (isset($message)) {
    echo $message;
} ?></span>
            <div class="tab-content">
                <div role="tabpanel" class="clearfix tab-pane <?= $tab1 ?>" id="fromList">
                    <div class="panel-body">


                        <div class="tab-content">
                            <div id="fromList" class="clearfix tab-pane  active " role="tabpanel">
                                <div class="panel-body">
                                    <form class="form-horizontal" role="form" enctype="multipart/form-data" method="POST" action="<?= base_url() . 'general/email_configuration' ?>" autocomplete="off" name="home_page_heading">
                                        <fieldset form="promo_codes_form_edit">
                                            <legend class="form_legend">Create Net Rate Sectors</legend>
                                            <div class="form-group">
                                                <label form="promo_codes_form_edit" for="header_title" class="col-sm-3 control-label">Username</label>
                                                <div class="col-sm-6">
                                                    <input type="email" id="username" class="form-control" placeholder="Username" name="username" required value="<?php echo $user_name; ?>">
                                                </div>
                                            </div>
                                             <div class="form-group">
                                                <label form="promo_codes_form_edit" for="header_title" class="col-sm-3 control-label">Username</label>
                                                <div class="col-sm-6">
                                                    <input type="email" id="username" class="form-control" placeholder="Username" name="username" required value="<?php echo $user_name; ?>">
                                                </div>
                                            </div>
                                           
                                           
                                            
                                          
                                        </fieldset>
                                        <div class="form-group">
                                            <?php
                                            if (isset($title)) {
                                                $button = 'Update';
                                            } else {
                                                $button = 'Save';
                                            }
                                            ?>
                                            <div class="col-sm-8 col-sm-offset-4"> <button class="btn btn-success" type="submit"><?php echo $button; ?></button> <button class=" btn btn-warning " id="promo_codes_form_edit_reset" type="reset">Reset</button></div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>


                  <div role="tabpanel" class="clearfix tab-pane <?= $tab2 ?>" id="tableList">
                    <div class="panel-body">


                        <div class="tab-content">
                        	 <fieldset form="promo_codes_form_edit">
                                            <legend class="form_legend">List of Sectors</legend>

                                        </fieldset>


                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- PANEL BODY END --></div>
    <!-- PANEL WRAP END --></div>
<!-- HTML END -->

