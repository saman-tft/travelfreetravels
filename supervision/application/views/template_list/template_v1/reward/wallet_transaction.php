
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/datatables/1.10.12/css/dataTables.bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.1.0/css/responsive.bootstrap.min.css" type="text/css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.2.1/css/buttons.bootstrap.min.css" type="text/css" />
<div class="wallet-report">
    <div class="panel-heading"><!-- PANEL HEAD START -->
			<div class="panel-title">
				Wallet Transaction Report
			
		
			</div>
		</div>
    <div class="table-responsive">
        <table class="table table-striped" id="walletreward">
            <thead>
            <tr>
                <th>TransactionID</th>
                <th>Amount</th>
                <th>Rewards</th>
                <th>userID</th>
                <th>email</th>
                <th>created at</th>
            </tr>
            </thead>
            <tbody>
            <?php
              for($i=0;$i<count($users_data);$i++)
              {
                //  debug($user_data);die;
               ?>
                 <tr>
                <td><?php echo $users_data[$i]['transactionid']; ?></td>
                <td><?php echo $users_data[$i]['amount']; ?></td>
                <td><?php echo $users_data[$i]['earned_rewards']; ?></td>
                <td><?php echo $users_data[$i]['user_id']; ?></td>
                <td><?php echo provab_decrypt($users_data[$i]['email']);  ?></td>
                <td><?php echo $users_data[$i]['created_at']; ?></td>
            </tr>
               <?php
              } 
            ?>
            </tbody>
        </table>
    </div>
     
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
    $(document).ready( function () {
    $('#walletreward').DataTable();
} );
</script>
