<style type="text/css">.error_msg{color: red;}</style>
<!-- HTML BEGIN -->
<div class="bodyContent">
    <div class="panel panel-default clearfix"><!-- PANEL WRAP START -->
        <div class="panel-heading"><!-- PANEL HEAD START -->
        <div class="panel-title">
        <h4>Supplier's Details
        <!-- <br /><u>Currency:</u>   <strong id="agent_base_currency"><?=$agent_details['agent_base_currency']?></strong></h4> -->
        </div>
        <form name="supplier_form" method="post" autocomplete="off">
            <div class="panel-body">
                <div class="row form-group">
                    <div class="col-sm-6">
                        <div class="col-sm-4">Supplier Name</div>
                        <div class="col-sm-8"><input type="text" name="supplier_name" class="form-control" required=""></div>    
                    </div>
                    <div class="col-sm-6">
                        <div class="col-sm-4">Booking Source</div>
                        <div class="col-sm-8"><input type="text" name="booking_source" class="form-control" required=""></div>
                    </div>
                    <div class="clearfix col-sm-2 pull-right">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </form>
        </div>
        <div class="clearfix"></div>
        <div class="row">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <td>S.No</td>
                            <td>Supplier Name</td>
                            <td>Booking Source</td>
                            <td>Action</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($supplier)){
                        $i=1; 
                            foreach($supplier as $key => $value):
                        ?>
                            <tr>
                                <td><?=$i?></td>
                                <td><?=$value['supplier_name']?></td>
                                <td><?=$value['booking_source']?></td>
                                <td><a href="<?=base_url().'index.php/flight/delete_suppliers/'.$value['origin'].''?>" class="btn btn-primary"> Delete</a></td>
                            </tr>
                        <?php $i++; endforeach; }else{ ?>
                            <tr>
                              <td colspan="4" align="center" class="error_msg"> No Result Found.. </td>  
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div><!-- PANEL WRAP END -->
</div>

<!-- HTML END -->
<script>
$(document).ready(function() {
    var agent_base_currency = $('#agent_base_currency').text().trim();
    if(agent_base_currency!=''){
        $('#amount').after('<strong class="text-danger">NOTE: currency is '+agent_base_currency+'</strong>');
    }
    $('#agent_id').change(function(){
        var agent_id = $(this).val().trim();
       window.location.href = app_base_url+'index.php/private_management/credit_balance_b2c?agent_id='+agent_id;    
    });
});
</script>