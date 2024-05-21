<?php $flag = 1; ?>
<!DOCTYPE html>
<html>
<head>
        <div id="Package" class="bodyContent col-md-12">
            <div class="panel panel-default">
                <!-- PANEL WRAP START -->
                <div class="panel-heading">
                    <!-- PANEL HEAD START -->
                    <div class="panel-title">
                        <ul class="nav nav-tabs nav-justified" role="tablist" id="myTab">
                            <!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->
                            <li role="presentation" class="active" id="add_package_li">
                                <a href="#add_package" aria-controls="home" role="tab" data-toggle="tab"> Visited City List : [ <?php echo 'Tour Name : ' . string_replace_encode($tour_data['package_name'])  ; ?> ] </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- PANEL HEAD START -->
                <div class="panel-body">
                    <!-- PANEL BODY START -->
                    <form action="<?php echo base_url(); ?>index.php/tours/tour_itinerary_p2_save" method="post" enctype="multipart/form-data" id="form form-horizontal validate-form" class='form form-horizontal validate-form'>
                        <div class="tab-content">
                            <!-- Add Package Starts -->
                            <div role="tabpanel" class="tab-pane active" id="add_package">
                                <div class="col-md-12">
                                    <div class='form-group'>
                                        <label class='control-label col-sm-3' for='validation_current'>Tour Duration
                                        </label>
                                        <div class='col-sm-4 controls'>
                                            <input type="text" value="<?= $tour_data['duration']+1 . ' Days / ' . ($tour_data['duration'] ) . (($tour_data['duration']==1)?' Night': 'Nights'); ?>" class='form-control' disabled>
                                        </div>
                                    </div>
                                    <?php 
                                    foreach ($tour_visited_cities as $tvcKey => $tvcValue) { 
                                        $city_in_record = $tvcValue['city'];
                                        $city_in_record = json_decode($city_in_record,1);
                                        foreach($city_in_record as $k => $v)
                                        {
                                            if($k==0){ $city_in_record_str = $tours_city_name[$v];} 
                                            else{ $city_in_record_str = $city_in_record_str.', '.$tours_city_name[$v];}                             
                                        }
                                        ?>
                                        <div class='form-group'>
                                            <label class='control-label col-sm-3' for='validation_current'>City Visited
                                            </label>
                                            <div class='col-sm-8 controls'>
                                                <input type="text" name="city" id="city" value="<?= $city_in_record_str . ' [ ' . $tvcValue['no_of_nights'] . (($tvcValue['no_of_nights']==1)?' Night': 'Nights').']'; ?>" placeholder="Enter City" class='form-control' disabled>
                                            </div>
                                        </div>
                                        <input type="hidden" name="id[]" value="<?= $tvcValue['id'] ?>">
                                        <?php } ?>
                                        <div id="itinerary_list">
                                            <?php
                                            $Dayno = 1;
                                            $totalvisint_tours=count($tour_visited_cities)-1;
                                            foreach ($tour_visited_cities as $tvcKey => $tvcValue) {
                                                $itinerary = $tvcValue['itinerary'];
                                                $itinerary = json_decode($itinerary, 1);
                                                for ($i = 0; $i < $tvcValue['no_of_nights']; $i++) {
                                                    $id = $tvcValue['id'];
                                                    $accomodation = $itinerary[$i]['accomodation'];
                                                    if (in_array('Breakfast', $accomodation)) {
                                                        $Breakfast = 'checked';
                                                    } else {
                                                        $Breakfast = '';
                                                    }
                                                    if (in_array('Lunch', $accomodation)) {
                                                        $Lunch = 'checked';
                                                    } else {
                                                        $Lunch = '';
                                                    }
                                                    if (in_array('Dinner', $accomodation)) {
                                                        $Dinner = 'checked';
                                                    } else {
                                                        $Dinner = '';
                                                    }
                                                    $city_in_record = $tvcValue['city'];
                                                    $city_in_record = json_decode($city_in_record,1);
                                                    foreach($city_in_record as $k => $v)
                                                    {
                                                        if($k==0){ 
                                                            $city_in_record_str = $tours_city_name[$v];
                                                        } else
                                                        { 
                                                            $city_in_record_str = $city_in_record_str.', '.$tours_city_name[$v];
                                                        }
                                                    }
                                                    // echo $itinerary[$i]['program_title'];
                                                    if (isset($itinerary[$i]['program_title']) && ($itinerary[$i]['program_title'] != "")) {
                                                        $flag = 0;
                                                    }
                                                    ?>
                                                    <hr>
                                                    <?php
                                                        $tours_city = $tour_data['tours_city'];
                                                    //    debug($tours_city); exit();
                                                       $tours_city     = explode(',',$tours_city);
                                                       // debug($tours_itinerary_dw);
                                                        ?>
                                                    <div class = "form-group">
                                                        <!--<label class = "control-label col-sm-3 " for="validation_current">Day <?= $Dayno ?>  in <?= string_replace_encode($city_in_record_str) ?></label> -->
                                                        <label class = "control-label col-sm-3 " for="validation_current">Day <?= $Dayno ?>  in 
                                                        </label>
                                                         <div class="col-sm-8 controls">
                                                        <select class=' form-control' name='city_day_list[]' id="city_day_list"  data-rule-required='true' required>   
                                                            <option value="">Select City</option>
                                                           
                                                                                         
                                                            <?php
                                                            foreach($tours_city as $key => $value)
                                                            {  ?>
                                                                <option value="<?=$value?>" <?php if($tours_itinerary_dw[$Dayno_cities]['visited_city_day']==$value){echo "selected";}?>><?=$tours_city_name[$value]?> </option>
                                                            <?php
                                                            }
                                                            ?>                              
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-sm-3" for="validation_current">Day Program Title &nbsp; <span style = "color:red">*</span> </label>
                                                        <div class="col-sm-8 controls">
                                                            <input type="text" name="program_title[<?= $id ?>][]" placeholder="Enter Program Title" data-rule-required="true" class="form-control" required value="<?= string_replace_encode($itinerary[$i]['program_title']) ?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-sm-3" for="validation_current">Program Description
                                                        </label>
                                                        <div class="col-sm-8 controls">
                                                            <textarea name="program_des[<?= $id?>][]" data-rule-required="true" class="form-control" data-rule-required="true" cols="70" rows="5" placeholder="Description"><?=string_replace_encode($itinerary[$i]['program_des']) ?></textarea>
                                                        </div>
                                                    </div>
                                                    <!-- <div class="form-group">
                                                        <label class="control-label col-sm-3" for="validation_current">Hotel Name </label>
                                                        <div class="col-sm-4 controls">
                                                            <input type="text" name="hotel_name[<?=$id ?>][]"  placeholder="Enter hotel name" class="form-control" value="<?=string_replace_encode($itinerary[$i]['hotel_name']) ?>">                  
                                                        </div>
                                                    </div> -->
                                                    <!-- <div class="form-group">
                                                        <label class="control-label col-sm-3" for="validation_current">Star Rating </label>
                                                        <div class="col-sm-4 controls">
                                                            <select name="rating[<?=$id ?>][]" class="form-control">
                                                                <option value="0">Select star rating</option>
                                                                <?php
                                                                for ($s = 1; $s <= 5; $s++) {
                                                                    $rating = $itinerary[$i]['rating'];
                                                                    if ($s == $rating) {
                                                                        $selected = 'selected';
                                                                    } else {
                                                                        $selected = '';
                                                                    }
                                                                    echo '<option value="' . $s . '" ' . $selected . '>' . $s . ' Star</option>';
                                                                }
                                                                ?>
                                                            </select>               
                                                        </div>
                                                    </div> -->
                                                    <!-- <div class="form-group">
                                                        <label class="control-label col-sm-3" for="validation_current">Meals </label>
                                                        <div class="col-sm-4 controls">
                                                            <input type="checkbox" name="accomodation[<?= $id ?>][<?= ($i) ?>][]" value="Breakfast" <?= $Breakfast ?>> Breakfast <br>                  
                                                            <input type="checkbox" name="accomodation[<?= $id ?>][<?= ($i) ?>][]" value="Lunch" <?= $Lunch ?>> Lunch <br>              
                                                            <input type="checkbox" name="accomodation[<?= $id ?>][<?= ($i) ?>][]" value="Dinner" <?= $Dinner ?>> Dinner <br>               
                                                        </div>
                                                    </div> -->
                                                    <?php
                                                    $Dayno++;
                                                }
                                                if($tvcKey==$totalvisint_tours) {
                                                    $accomodation = $itinerary[$i]['accomodation'];
                                                    if (in_array('Breakfast', $accomodation)) {
                                                        $Breakfast = 'checked';
                                                    } else {
                                                        $Breakfast = '';
                                                    }
                                                    if (in_array('Lunch', $accomodation)) {
                                                        $Lunch = 'checked';
                                                    } else {
                                                        $Lunch = '';
                                                    }
                                                    if (in_array('Dinner', $accomodation)) {
                                                        $Dinner = 'checked';
                                                    } else {
                                                        $Dinner = '';
                                                    }
                                                    ?>
                                                    <hr>
                                                    <div class="form-group">
                                                        <!--<label class="control-label col-sm-3" for="validation_current">Day <?= $Dayno ?>  in <?= string_replace_encode($city_in_record_str)?></label>-->
                                                        <label class="control-label col-sm-3" for="validation_current">Day <?= $Dayno ?>  in </label>
                                                         <div class="col-sm-8 controls">
                                                        <select class=' form-control' name='city_day_list[]' id="city_day_list"  data-rule-required='true' required>      
                                                            <option value="">Select City</option>
                                                                                 
                                                            <?php
                                                            foreach($tours_city as $key => $value)
                                                            {  ?>
                                                                <option value="<?=$value?>" <?php if($tours_itinerary_dw[$Dayno_cities]['visited_city_day']==$value){echo "selected";}?>><?=$tours_city_name[$value]?> </option>
                                                            <?php
                                                            }
                                                            ?>                              
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-sm-3" for="validation_current">Day Program Title &nbsp; <span style = "color:red">*</span> </label>
                                                        <div class="col-sm-8 controls">
                                                            <input type="text" name="program_title[<?= $id?>][]" placeholder="Enter Program Title" data-rule-required="true" class="form-control" required value="<?= string_replace_encode($itinerary[$i]['program_title']) ?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label col-sm-3" for="validation_current">Program Description
                                                        </label>
                                                        <div class="col-sm-8 controls">
                                                            <textarea name="program_des[<?= $id ?>][]" data-rule-required="true" class="form-control" data-rule-required="true" cols="70" rows="5" placeholder="Description"><?= string_replace_encode($itinerary[$i]['program_des']) ?></textarea>
                                                        </div>
                                                    </div>
                                                   <!--  <div class="form-group">
                                                        <label class="control-label col-sm-3" for="validation_current">Hotel Name </label>
                                                        <div class="col-sm-4 controls">
                                                            <input type="text" name="hotel_name[<?= $id?>][]" placeholder="Enter hotel name" class="form-control" value="<?= string_replace_encode($itinerary[$i]['hotel_name']) ?>">
                                                        </div>
                                                    </div>  -->
                                                   <!--  <div class="form-group">
                                                        <label class="control-label col-sm-3" for="validation_current">Star Rating </label>
                                                        <div class="col-sm-4 controls">
                                                            <select name="rating[<?= $id ?>][]" class="form-control">
                                                                <option value="0">Select star rating</option>
                                                                <?php
                                                                for ($s = 1; $s <= 5; $s++) {
                                                                    $rating = $itinerary[$i]['rating'];
                                                                    if ($s == $rating) {
                                                                        $selected = 'selected';
                                                                    } else {
                                                                        $selected = '';
                                                                    }
                                                                    echo '<option value="' . $s . '" ' . $selected . '>' . $s . ' Star</option>';
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div> -->
                                                   <!--  <div class="form-group">
                                                        <label class="control-label col-sm-3" for="validation_current">Meals </label>
                                                        <div class="col-sm-4 controls">
                                                            <input type="checkbox" name="accomodation[<?= $id?>][<?= ($i)?>][]" value="Breakfast" <?= $Breakfast?>> Breakfast <br>
                                                            <input type="checkbox" name="accomodation[<?= $id?>][<?= ($i)?>][]" value="Lunch" <?= $Lunch?>> Lunch <br>
                                                            <input type="checkbox" name="accomodation[<?= $id?>][<?= ($i)?>][]" value="Dinner" <?= $Dinner?>> Dinner <br>
                                                        </div>
                                                    </div> -->
                                                    <?php
                                                }
                                            }
                                            ?>
                                            </div>
                                            <hr>
                                            
                                            <div class='form-group'>
                                                <label class='control-label col-sm-3' for='validation_current'>Inclusions
                                                </label>
                                                <?php 
                                                $inclusions_arr = json_decode($tour_data['inclusions_checks'],1);
                                               // debug($inclusions_arr);die;
                                                ?>
                                                <div class='col-sm-4 controls'>
                                                     <input type="checkbox" name="inclusions[]" value="Flight" <?=(in_array('Flight',$inclusions_arr))? 'checked="checked"': '';?>>
                                                    <!-- <i class="fa fa-hotel"> --> Flight
                                                    <br>
                                                    <input type="checkbox" name="inclusions[]" value="Hotel" <?=(in_array('Hotel',$inclusions_arr))? 'checked="checked"': '';?>>
                                                    <!-- <i class="fa fa-hotel"> --> Hotel
                                                    <br>
                                                    <!-- <input type="checkbox" name="inclusions[]" value="Car" <?=(in_array('Car',$inclusions_arr))? 'checked="checked"': '';?>>
                                                     <i class="fa fa-car">Car
                                                    <br> -->
                                                    <input type="checkbox" name="inclusions[]" value="Meals" <?=(in_array('Accomodation',$inclusions_arr))? 'checked="checked"': '';?>>
                                                    <!-- <i class="fa fa-spoon"> -->Accomodation
                                                    <br>
                                                    <input type="checkbox" name="inclusions[]" value="Meals" <?=(in_array('Game Drives',$inclusions_arr))? 'checked="checked"': '';?>>
                                                    <!-- <i class="fa fa-spoon"> -->Game Drives
                                                    <br>
                                                    <input type="checkbox" name="inclusions[]" value="Meals" <?=(in_array('Meals',$inclusions_arr))? 'checked="checked"': '';?>>
                                                    <!-- <i class="fa fa-spoon"> -->Meals
                                                    <br>
                                                    <input type="checkbox" name="inclusions[]" value="Sightseeing" <?=(in_array('Sightseeing',$inclusions_arr))? 'checked="checked"': '';?>>
                                                    <!-- <i class="fa fa-binoculars"> -->Sightseeing
                                                    <br>
                                                    <input type="checkbox" name="inclusions[]" value="Transfers" <?=(in_array('Transfers',$inclusions_arr))? 'checked="checked"': '';?>>
                                                    <!-- <i class="fa fa-binoculars"> -->Transfers
                                                </div>
                                            </div>
                                            
                                            <div class='' style='margin-bottom: 0'>
                                                <div class='row'>
                                                    <div class='col-sm-9 col-sm-offset-3'>
                                                        <input type="hidden" name="tour_id" value="<?= $tour_id ?>">
                                                        <button class='btn btn-primary' name='it_submit' id="it_submit"  type='submit'>Save
                                                        </button>
                                                        <?php 
                                                        if($this->session->userdata('edit_itinary')){
                                                            ?>
                                                            <a class="btn btn-primary" href="<?=base_url()?>index.php/tours/tour_list">Go Back to Tours List </a>
                                                            <?php 
                                                        }
                                                        ?>
                                                        <!--<button class='btn btn-primary'><a href="<?php echo base_url(); ?>index.php/tours/tour_pricing_p2/<?= $tour_id ?>" style="color:white;">Next</a></button>-->
                                                    </div>
                                                </div>
                                            </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- PANEL BODY END -->
                <!-- PANEL WRAP END -->
            </div>
        </div>
            <!--script type="text/javascript" src="<?=get_domain()?>extras/system/template_list/template_v1/javascript/js/tiny_mce/tiny_mce.js"></script>
            <script type="text/javascript" src="<?=get_domain()?>extras/system/template_list/template_v1/javascript/js/tiny_mce/tiny_mce_call.js">
            </script>-->
            <!--
<script type="text/javascript" src="/chariot/extras/system/template_list/template_v1/javascript/js/nicEdit-latest.js"></script> 
<script type="text/javascript">
bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });
</script>-->
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width">
            <title>ctrlq
            </title>
</head>

<body>

</body>

</html>
<script>
/*window.onbeforeunload = function(e) {
    return "Sure you want to leave?";
};*/
$(document).ready(function() {
// $('#it_submit').click(function(){
//         window.btn_clicked = true;
//     });
// window.onbeforeunload = function(){
//         if(!window.btn_clicked){
//             return 'It looks like you have been editing something. If you leave before saving, your changes will be lost.';
//         }
//     };
   });

</script>