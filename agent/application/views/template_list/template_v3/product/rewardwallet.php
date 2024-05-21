 <!-- rewardsbuy start -->
                        <div class="alldasbord">
                            <div class="step_head">
                                <h3 class="dashhed">Rewards Wallet</h3>
                            </div>
                            <div class="step_head_inner">
                                <h5 class="dashhed">Reward points Used: </h5>
                                <p class="dashhed_p">
                                    <strong><?php echo $reward_total_report[0]['used_reward'];?></strong></p>

                            </div>
                            <div class="step_head_inner">
                                <h5 class="dashhed">Reward points Available: </h5>
                                <p class="dashhed_p"><strong><?php echo $user['pending_reward'];?></strong></p>

                            </div>

                            <div class="step_head">
                                <h4 class="float-left">Buy Reward Points</h4>
                                <a class="addbutton" data-toggle="modal" data-target="#add_rewardsbuy_tab">Buy Now</a>
                            </div>
                            <!-- rewardsbuy  -->
                            <div class="modal fade" id="add_rewardsbuy_tab" data-aria-labelledby="myModalLabel">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title" id="myModalLabel">Buy Reward Points</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="othinformtn">
                                                <div class="tab-content">
                                                    <div class="tab-pane active" role="tabpanel">
                                                        <div class="infowone">
                                                            <form action="<?=base_url().'index.php/loyalty_program/buyrewards'?>"
                                                                method="post" name="add_traveller_form"
                                                                id="add_traveller_form" autocomplete="off">
                                                                <div class="paspertedit">
                                                                    <div class="col-md-6 col-xs-12 margpas">
                                                                        <div class="tnlepasport reward_elements">
                                                                            <div class="paspolbl cellpas">Select
                                                                                Points<span class="text-dange">*</span>
                                                                            </div>
                                                                            <div class="lablmain cellpas">
                                                                                <select name="rewardpoints"
                                                                                    class="rewards_points clainput alpha  rewards_points_chg no_copy_paste"
                                                                                    id="rewardpoints_points">
                                                                                    <option>select</option>
                                                                                    <?php
                                               for($i=0;$i<count($wallet_settings);$i++)
                                               {
                                               ?>
                                                                                    <option
                                                                                        data-id="<?php echo $wallet_settings[$i]['price'] ?>"
                                                                                        value="<?php echo $wallet_settings[$i]['reward-points'] ?>">
                                                                                        <?php echo $wallet_settings[$i]['reward-points'];  ?>
                                                                                    </option>


                                                                                    <?php
                                             }
                                             ?>
                                                                                </select>

                                                                                <input name="rewardpoints_amount"
                                                                                    type="text"
                                                                                    class="clainput alpha ramount no_copy_paste"
                                                                                    placeholder="Amount" maxlength="20"
                                                                                    required="required"
                                                                                    id="rewardpoints_amount" readonly>
                                                                                <span id="rewardpoints_amount_error"
                                                                                    style="color:red;"></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="clearfix"></div>
                                                                    <button type="submit" id="add_rewardsbuy_btn"
                                                                        class="savepspot">Buy Now</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- rewardsbuy  -->
                        </div>
                        <!-- rewardsbuy  end -->
                        <table class="table table-striped table-bordered" id="wallet_report">
                            <thead>
                                <tr>
                                    <th>Transaction ID</th>
                                    <th>Rewards</th>
                                    <th>Price</th>
                                    <th>Payment status</th>
                                    <th>Created at</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                               for($i=0;$i<count($wallet_report);$i++)
                                               {
                                               ?>
                                <tr>
                                    <td><?php echo $wallet_report[$i]['transactionid']  ?></td>
                                    <td><?php echo $wallet_report[$i]['earned_rewards']  ?></td>
                                    <td><?php echo $wallet_report[$i]['amount']  ?></td>
                                    <td><?php echo $wallet_report[$i]['paymentstatus']  ?></td>
                                    <td><?php echo $wallet_report[$i]['created_at']  ?></td>
                                </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="//cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
<!-- Responsive extension -->
<script src="https://cdn.datatables.net/responsive/2.1.0/js/responsive.bootstrap.min.js"></script>
<!-- Buttons extension -->
<script src="//cdn.datatables.net/buttons/1.2.1/js/dataTables.buttons.min.js"></script>
<script src="//cdn.datatables.net/buttons/1.2.1/js/buttons.bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script src="//cdn.datatables.net/buttons/1.2.1/js/buttons.html5.min.js"></script>
<script>
$(document).ready(function() {
    $('#wallet_report').DataTable();
    $('.rewards_points_chg').on("change", function() {
        var price = $(this).find(':selected').attr('data-id');
        $(".ramount").val(price);
    });
});
</script>