<!-- HTML BEGIN -->
<div class="bodyContent">
    <div class="panel <?= PANEL_WRAPPER ?>"><!-- PANEL WRAP START -->
        <div class="panel-heading"><!-- PANEL HEAD START -->
            <div class="panel-title">
                <i class="fa fa-edit"></i> Manage Application Segment
                <span class="pull-right">Note : Application Default Currency - <strong><?= get_application_default_currency() ?></strong></span>

            </div>
        </div><!-- PANEL HEAD START -->
        <div class="panel-body"><!-- Add Airline Starts-->
            <fieldset><legend><i class="fa fa-plane"></i> Per Segment Discount   </legend>
                <form action="" class="form-horizontal" method="POST" autocomplete="off">
                    <input type="hidden" name="form_values_origin" value="<?=@$discount['origin']?>" />
                    <div class="row">
                        <?php
                            $per_check = '';
                            $plus_check = '';
                            if(isset($discount['value_type'])){ 
                                if($discount['value_type'] == 'percentage'){
                                    $per_check = 'checked="checked"';
                                }else{
                                    $plus_check = 'checked="checked"';
                                }
                            }else{
                                $plus_check = 'checked="checked"';
                            }
                        ?>
                        <div class="col-md-5">
                            <div class="radio">
                                <?php if(false){ ?>
                                <label for="value_type" class="col-sm-4 control-label">Discount Type<span class="text-danger">*</span></label>
                                <label for="airline_value_type_plus" class="radio-inline">
                                    <input checked="checked" type="radio" value="plus" id="airline_value_type_plus" name="value_type" class=" value_type_plus radioIp" <?=$plus_check?> required=""> Plus(+ <?= get_application_default_currency() ?>)
                                </label>
                                <label for="airline_value_type_percent" class="radio-inline">
                                    <input type="radio" value="percentage" id="airline_value_type_percent" name="value_type" class=" value_type_percent radioIp" <?=$per_check?> required=""> Percentage(%)
                                </label>
                            <?php } ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="hidden" name="value_type" value="plus">
                                <label for="new_airline_value" class="col-sm-4 control-label">Discount Value<span class="text-danger">*</span></label>
                                <input type="text" id="new_airline_value" name="discount_value" class=" generic_value numeric" placeholder="Discount Value" value="<?=@$discount['value']?>" />
                            </div>
                        </div>
                    </div>
                    <div class="well well-sm">
                        <div class="clearfix col-md-offset-1">
                            <button class=" btn btn-sm btn-success " id="add-airline-submit-btn" type="submit">Update</button>
                            <button class=" btn btn-sm btn-warning " id="add-airline-reset-btn" type="reset">Reset</button>
                        </div>
                    </div>
                </form>
            </fieldset>
        </div>
    </div><!-- PANEL WRAP END -->
</div>