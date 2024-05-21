<div id="Package" class="bodyContent col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">
                <ul class="nav nav-tabs nav-justified" role="tablist" id="myTab">
                    <li role="presentation" class="active" id="add_package_li"><a
                        href="#add_package" aria-controls="home" role="tab"
                        data-toggle="tab">Edit Tour City </a></li>          
                    </ul>
                </div>
            </div>
            <div class="panel-body">
                <form
                action="<?php echo base_url(); ?>index.php/tours/edit_tour_city/<?=$id?>"
                method="post" enctype="multipart/form-data" id="form form-horizontal validate-form"
                class='form form-horizontal validate-form'>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="add_package">
                        <div class="col-md-12">
                            <div class='form-group'>
                                <label class='control-label col-sm-3' for='validation_current'>Tour City
                                </label>
                                <div class='col-sm-8 controls'>
                                    <input type="text" name="CityName" id="CityName"
                                    placeholder="Enter City" data-rule-required='true'
                                    class='form-control add_pckg_elements' required value="<?=$data['CityName'];?>">
                                </div>
                            </div>
                            <div class='' style='margin-bottom: 0'>
                                <div class='row'>
                                    <div class='col-sm-9 col-sm-offset-3'>  
                                        <button class='btn btn-primary' type='submit'>Save</button>
                                        <a href="<?php echo base_url(); ?>index.php/tours/tour_city" class='btn btn-primary' style="color:white;">Tour City</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>