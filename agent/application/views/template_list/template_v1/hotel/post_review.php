<?php
//debug($post_review_data['user_details']);

$user_name   = $post_review_data['user_details']['user_name'];
if($user_name == ''){
    $user_name = $this->entity_first_name;
}
$user_lname   = $post_review_data['user_details']['user_lname'];
if($user_lname == ''){
    $user_lname = $this->entity_last_name;
}
$user_email  = $post_review_data['user_details']['user_email'];
if($user_email == ''){
    $user_email = $this->entity_email;
}
$user_pn_country_code = $post_review_data['user_details']['pn_country_code'];
if($user_pn_country_code == ''){
    $user_pn_country_code = $this->entity_phone_code;
}
$user_mobile = $post_review_data['user_details']['user_mobile'];
if($user_mobile == ''){
    $user_mobile = $this->entity_phone;
}
$user_phone_code = $post_review_data['user_details']['phone_code'];
if($user_phone_code == ''){
    $user_phone_code = $this->entity_phone_code;
}
$user_phone_country = $post_review_data['user_details']['phone_country'];
if($user_phone_country == ''){
    $user_phone_country = $this->entity_phone_country;
}
//debug($user_phone_country);
$comment     = '';

$exist_user1        = $post_review_data['review_field']['exist_user1'];
$exist_user0        = $post_review_data['review_field']['exist_user0'];
$previously_booked1 = $post_review_data['review_field']['previously_booked1'];
$previously_booked0 = $post_review_data['review_field']['previously_booked0'];
$exist_user_field   = $post_review_data['review_field']['exist_user_field'];

$created_by         = $post_review_data['required']['created_by'];
$booking_source     = $post_review_data['required']['booking_source'];
$module             = $post_review_data['required']['module'];
$module_id          = $post_review_data['required']['module_id'];
$title              = $post_review_data['required']['title'];
$address            = $post_review_data['required']['address'];

$tour_id            = $post_review_data['required']['tour_id'];
$tours_itinerary_id = $post_review_data['required']['tours_itinerary_id'];
?>
<p id="user_message_review">Your opinion matters!</p>
<div class="mlgnformin">
    <form class="form-horizontal" role="form" id="post_review">
        <div class="form-group">
            <label class="control-label col-md-5 col-xs-4" for="user_name">First Name: <strong class="text-danger">*</strong></label>
            <div class="col-md-7 col-xs-8">
                <input type="text" class="form-control mntxt" name="user_name" id="user_name" name placeholder="" value="<?=@$user_name?>" aria-required="true" required="required" style="text-transform: capitalize !important;">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-5 col-xs-4" for="user_name">Last Name: <strong class="text-danger">*</strong></label>
            <div class="col-md-7 col-xs-8">
                <input type="text" class="form-control mntxt" name="user_lname" id="user_lname" name placeholder="" value="<?=@$user_lname?>" aria-required="true" required="required" style="text-transform: capitalize !important;">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-5 col-xs-4" for="user_email">Email: <strong class="text-danger">*</strong></label>
            <div class="col-md-7 col-xs-8">
                <input type="email" class="form-control mntxt" name="user_email" id="user_email" placeholder="" value="<?=@$user_email?>" aria-required="true" required="required">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-5 col-xs-4" for="user_mobile">Phone Number:</label>
            <div class="col-md-7 col-xs-8">
                <div class="col-xs-5 nopad airform">
                    <!-- <span class="formlabel">Country Code <sup class="text-danger no_block">*</sup></span> -->
                    <?php if(isset($country_code) && valid_array($country_code)) {
                        ?><div class="selectedwrap"><select name="pn_country_code" id="pn_country_code_review" required="" aria-required="true" class="mySelectBoxClass flyinputsnor">
                            <option value="+1_Canada +1">Canada +1</option>
                            <option value="+1_United States +1">United States +1</option>
                            <?php foreach($country_code as $c__k => $__country) {
                                ?><option value="<?=trim(@$c__k."_".$__country)?>" <?php if($c__k == $default_country_code) {echo 'selected';} ?>><?=@$__country?></option><?php
                            }?>
                        </select></div><?php
                    }?>
                    <!--													<input type="text" placeholder="+41" class="pre_put form-control" required="" name="pn_country_code" aria-required="true">-->
                </div>
                <div class="col-md-7 col-xs-7 airformleft">
                    <input type="text" class="mobile form-control mntxt" name="user_mobile" id="user_mobile" placeholder="" value="<?=@$user_mobile?>">
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-5 col-xs-4" for="">Booked with Troogles Before?</label>

            <div class="col-md-7 col-xs-8 yes_no">
                <input type="radio" class="" value="1" name="previously_booked" id="previously_booked1" <?=$previously_booked1?>>
                <label for="previously_booked1">Yes </label> &nbsp; &nbsp;
                <input type="radio" class="" value="0" name="previously_booked" id="previously_booked0" <?=$previously_booked0?> checked="true">
                <label for="previously_booked0">No </label>
            </div>
        </div>
        <?php
        if(empty($post_review_data['user_details'])){ ?>

            <div class="form-group">
                <label class="control-label col-md-5 col-xs-4 reg
			_user" for="">Are you a registered user?</label>
                <div class="col-md-7 col-xs-8 yes_no">
                    <?=$exist_user_field['open_tag']?>
                    <input type="radio" class="" value="1" name="exist_user" id="exist_user1" <?=$exist_user1?>>
                    <label for="exist_user1">Yes </label> &nbsp; &nbsp;
                    <input type="radio" class="" value="0" name="exist_user" id="exist_user0" <?=$exist_user0?> checked="true">
                    <label for="exist_user0">No </label>
                    <?=$exist_user_field['close_tag']?>
                </div>
            </div>

        <?php }else{ ?>

            <input type="hidden" class="" value="0" name="exist_user" id="exist_user0" <?=$exist_user0?>>
        <?php  }

        ?>


        <div class="form-group">
            <label class="control-label col-md-5 col-xs-4" for="comment">Your review: <strong class="text-danger">*</strong></label>
            <div class="col-md-7 col-xs-8">
				<textarea rows="3" class="form-control mntxt" name="comment" id="comment" placeholder="" aria-required="true" required="required"><?=@$comment?>
				</textarea>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-4 col-md-offset-5 col-sm-10 col-md-9">
                <button type="submit" class="btn btn-default inblk lgnbtn">Submit</button>
            </div>
        </div>
        <input type="hidden" name="created_by" value="<?=@$created_by?>">
        <input type="hidden" name="booking_source" value="<?=@$booking_source?>">
        <input type="hidden" name="module" value="<?=@$module?>">
        <input type="hidden" name="module_id" value="<?=@$module_id?>">
        <input type="hidden" name="title" value="<?=@$title?>">
        <input type="hidden" name="address" value="<?=@$address?>">
        <input type="hidden" name="tour_id" value="<?=@$tour_id?>">
        <input type="hidden" name="tours_itinerary_id" value="<?=@$tours_itinerary_id?>">
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        if('<?php echo $user_phone_code;?>'){
            $("#pn_country_code_review").val('<?php echo @$user_phone_code."_".@$user_phone_country." ".$user_phone_code;?>');
        }
        $("#exist_user1").click(function(){
            $('#mylogin').modal('show');
        });
        $("#exist_user0").click(function(){
            $('#mylogin').modal('show');
        });
        $('#mylogin').on('hidden.bs.modal', function () {
            $('#exist_user1').prop('checked',false);
            $('#exist_user0').prop('checked',true);
        });
        $("#post_review").submit(function() {
            $(".alert").remove();
            var data = $("#post_review").serialize();
            $.ajax({
                type: "POST",
                url: '<?=base_url()?>index.php/hotels/set_post_review',
                data: data,
                dataType:'json', 
                cache:false,
                success: function(result){
                    get_review();
                    if(result['status'] == 1)
                    {
                        $("#post_review").before('<p class="alert alert-success" id="post_msg">Review submitted successfully</p>');
                        $("#post_review").find("input[type=text], input[type=email], input[type=number], textarea").val("");
                        $('#post_review').addClass('hide');
                    }else
                    {
                        $("#post_review").before('<p class="alert alert-danger">Review submit failed</p>');
                        $('#post_review').removeClass('hide');
                    }
                }
            });
            return false;
        });
        get_review();

        $('#post_review_tab').click(function() {
            $('#post_review').removeClass('hide');
            $("#post_msg").addClass('hide');
        });


    });
    function get_review(){
//        $("#user_message_review").text('');
        var data_def = {
            'booking_source' : $("input[name=booking_source]").val(),
            'module' : $("input[name=module]").val(),
            'module_id' : $("input[name=module_id]").val(),
            'title' : $("input[name=title]").val(),
            'address' : $("input[name=address]").val(),
            'created_by' : $("input[name=created_by]").val()
        };
        $.ajax({
            type: "POST",
            url: '<?=base_url()?>index.php/hotel/get_post_review',
            data: data_def,
            cache:false,
            success: function(result){
                $("#user_message_review").text(result);
            }
        });
    }
</script>

<style type="text/css">
    .airformleft {
        padding-right: 0;
    }
    .airform select {
        height: 34px;
        padding: 0 6px;
    }
</style>
