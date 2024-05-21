<style>
	.trvlwrap {
    width: 80%;
    margin: 0 auto;
    position: relative;
    left: 45%;
    transform: translateX(-50%);
}
</style>
<?php 

// debug($total_point);exit;

?>
<!-- HTML BEGIN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.10.0/jquery.timepicker.min.css">
<div class="content-wrapper dashboard_section">
    <div class="container">
        <div class="staffareadash">
            <?php echo $GLOBALS['CI']->template->isolated_view('share/profile_navigator_tab') ?>
            <div id="general_user" class="bodyContent">

                <div class="panel panel-default">
                    <!-- PANEL WRAP START -->
                    <div class="panel-heading">
                        <!-- PANEL HEAD START -->
                        <div class="panel-title">

                            <h4>Total Reward point</h4>

                        </div>
                    </div>
                    <!-- PANEL HEAD START -->
                    <div class="panel-body">
                        <!-- PANEL BODY START -->
                        <div class="cetrel_all">

                            <?php  if(!isset($print_voucher) && ($print_voucher!='yes')){ echo $GLOBALS['CI']->template->isolated_view('share/navigation'); } ?>

                        </div>
                        <div class="tab-content">

                            <div role="tabpanel" class="tab-pane active" id="mybookings">

                                <div class="trvlwrap">

                                    <!--/************************ GENERATE Filter Form ************************/-->

                                    <div class="clearfix"></div>
                                    <!--/************************ GENERATE Filter Form ************************/-->
                                    <div class="clearfix">




                                        <div class="clearfix">
                                            <div class="col-md-12 table-responsive ">
                                                <table class="table table-condensed table-bordered">
                                                    <thead>
                                                        <tr>


                                                            <th>Total</th>
                                                            <th>Hotel</th>
                                                            <th>Transfer</th>
                                                            <th>Holidays</th>
                                                            <th>Excursion</th>
                                                            <th>Visa</th>





                                                        </tr>
                                                    </thead>
                                                    <tbody>











                                                        <tr>

                                                            <?php if($total_point){?>
                                                            <td><?=@$total_point['t_reward']?></td>
                                                            <td><?=@$total_point['hotel']?></td>
                                                            <td><?=@$total_point['transfer']?></td>
                                                            <td><?=@$total_point['holidays']?></td>
                                                            <td><?=@$total_point['activities']?></td>


                                                            <td><?=@$total_point['visa']?></td>

                                                            <?php }else{?>
                                                            <td>0</td>
                                                            <td>0</td>
                                                            <td>0</td>
                                                            <td>0</td>
                                                            <td>0</td>
                                                            <td>0</td>

                                                            <?php }?>


                                                        </tr>




                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- PANEL BODY END -->
    </div>
    <!-- PANEL WRAP END -->
</div>
<!-- HTML END -->





<script>
$(document).ready(function() {

    //set dropdownlist selected


});
</script>

<script type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.10.0/jquery.timepicker.js"></script>