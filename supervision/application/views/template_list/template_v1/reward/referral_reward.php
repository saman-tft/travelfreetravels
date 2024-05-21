<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/datatables/1.10.12/css/dataTables.bootstrap.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.1.0/css/responsive.bootstrap.min.css"
    type="text/css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.2.1/css/buttons.bootstrap.min.css" type="text/css" />

<h2>Referral settings</h2>
<div class="referralset">
    <div class="row">
        <div class="col-md-2 col-xs-2">
            <label>Reward bonus</label>
        </div>
        <div class="col-md-8 col-xs-8">
            <form action="<?php echo base_url(); ?>reward/referralset" method="post">
            <input type="text" name="rewardbonus" value="<?php echo $bonus; ?>" class="form-control" />
            <input type="submit" name="submit" class="btn btn-primary" />
            </form>
        </div>
    </div>
</div>
<h2>Referral report</h2>
<table class="table table-striped" id="referralreward">
    <thead>
        <tr>
            
            <th>Referral code</th>
            <th>Referrer email</th>
            <th>User email(Joined)</th>
            <th>Reward point</th>
            <th>Status</th>
            <th>Created at</th>
            
        </tr>
    </thead>
    <tbody>
        <?php
              for($i=0;$i<count($users_data);$i++)
              {
                //  debug($user_data);die;
               ?>
        <tr>
           
            <td><?php echo $users_data[$i]['ref_code']; ?></td>
            <td><?php echo $users_data[$i]['ref_email']; ?></td>
            <td><?php echo $users_data[$i]['user_email']; ?></td>
            <td><?php echo $users_data[$i]['comm_amount']; ?></td>
            <td><?php echo $users_data[$i]['status']; ?></td>
            <td><?php echo $users_data[$i]['comm_date']; ?></td>
            
            
        </tr>
         <?php
    }
    ?>
    </tbody>
   
</table>


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
    $('#referralreward').DataTable();
    
});
</script>