<style>
    .topssec::after{display:none;}
</style>
<?php
$adult_count = $transfer_search_params['adult'];
$child_count = $transfer_search_params['child'];
$total_paxes = $adult_count + $child_count;
$total_pax = 2;
$room_count = 2;

 // debug($transfer_search_params);exit;
?>
<div class="modfictions">
    <div class="modinew">
        <div class="container">
            <div class="contentsdw">
                <div class="smldescrptn">
                    <div class="col-sm-9 col-xs-10 nopad">
                        <div class="col-xs-6 boxpad none_boil_full">
                            <h4 class="contryname">
                            <?php if($transfer_search_params['trip_type']=="oneway") {
                                echo "Oneway";
                             }else{
                                echo 'Round Trip';
                                } ?></h4>
                            <!-- <h3 class="placenameflt">Dubai International Airport</h3> -->
                            <div class="namefromto set_fromloc"><span class="set_dots"></span><?php echo $transfer_search_params['from'];?></div>
                        <div class="namefromto set_toloc"><span class="set_dots"></span><?php echo $transfer_search_params['to']; ?></div>
                        </div>

                        <div class="col-xs-3 boxpad none_boil">
                            <div class="boxlabl"><span class="faldate fa fa-calendar-o"></span>Departure</div>
                            <div class="datein">
                                <span class="calinn"><?=app_friendly_absolute_date($transfer_search_params['depature']); ?></span>
                            </div>
                        </div>


                        <div class="col-xs-3 boxpad none_boil" <?php if($transfer_search_params['trip_type']=="oneway") {?>style="opacity:0.4" <?php }?>>

                            <div class="boxlabl"><span class="faldate fa fa-calendar-o"></span>Return</div>

                            <div class="datein">
                                <span class="calinn">
                                    <?php 
                            if(@$transfer_search_params['trip_type'] == 'circle')
                            {
                                echo app_friendly_absolute_date($transfer_search_params['return']); 
                            }
                            else{
                                echo app_friendly_absolute_date($transfer_search_params['depature']);
                            }?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3 col-xs-2 nopad">
                        <div class="col-xs-7 boxpad none_mody">
                            <div class="boxlabl">Passenger</div>
                            <div class="countlbl" style="text-align: left;"><?=$total_paxes?></div>
                        </div>
                        <div class="col-xs-5 boxpad pull-right">
                            <a class="modifysrch" data-toggle="collapse" data-target="#modify"><span class="mdyfydsktp">Modify Search</span>
                                <i class="fa fa-angle-down mobresdv" aria-hidden="true"></i>
                            </a>

                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>

    <div class="clearfix"></div>
    <div class="splmodify">
        <div class="container">
            <div id="modify" class="collapse araeinner">
                <!-- <div class="insplarea">
                    <?php echo $GLOBALS['CI']->template->isolated_view('share/transfer_search') ?>
                </div> -->
            </div>
        </div>
    </div>
</div>



<script type="text/javascript">
    $(document).ready(function () {
        $('.modifysrch').click(function () {
            $(this).stop(true, true).toggleClass('up');
            $('.search-result').stop(true, true).toggleClass('flightresltpage');
            $('.modfictions').stop(true, true).toggleClass('fixd');
        });
    });
</script>

