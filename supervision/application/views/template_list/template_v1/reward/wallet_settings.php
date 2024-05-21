
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/datatables/1.10.12/css/dataTables.bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.1.0/css/responsive.bootstrap.min.css" type="text/css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.2.1/css/buttons.bootstrap.min.css" type="text/css" />
<div class="wallet-report">
    <div class="panel-heading"><!-- PANEL HEAD START -->
			<div class="panel-title">
				<i class="fa fa-edit"></i> Wallet Settings
			
		
			</div>
		</div>
    <button class="btn btn-primary" data-toggle="modal" data-target="#myModal">Add</button>
    <!-- The Modal -->
<div class="modal" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Add wallet setting</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <form method="post" action="<?php echo base_url(); ?>index.php/reward/add_wallet_settings">
            <input type="number" name="reward-points" placeholder="Reward point" />
            <input type="number" name="price" placeholder="Amount" />
            <input type="submit" name="submit" class="btn btn-success" value="submit" />
            
        </form>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>
    <div class="table-responsive">
        <table class="table table-striped" id="walletreward">
            <thead>
            <tr>
                <th>Reward points</th>
                <th>Price</th>
                <th>Action</th>
                <th>Created at</th>
                <th>Updated at</th>
            </tr>
            </thead>
           <tbody>
            <?php
              for($i=0;$i<count($users_data);$i++)
              {
                //  debug($user_data);die;
               ?>
                 <tr>
                <td><?php echo $users_data[$i]['reward-points']; ?></td>
                <td><?php echo $users_data[$i]['price']; ?></td>
                <td><a class="btn btn-success" data-toggle="modal" data-target="#myModal<?php echo $users_data[$i]["wallet-id"] ?>">Edit</a><a href="<?php echo base_url(); ?>index.php/reward/delete_wallet/<?php echo $users_data[$i]['wallet-id']; ?>" class="btn btn-danger" >Delete</a>
                <div class="modal" id="myModal<?php echo $users_data[$i]['wallet-id']; ?>">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Add wallet setting</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <form method="post" action="<?php echo base_url(); ?>index.php/reward/update_wallet_settings">
             <input type="number" name="wallet-id" value="<?php  echo $users_data[$i]['wallet-id']; ?>" hidden/>
            <input type="number" name="reward-points" value="<?php  echo $users_data[$i]['reward-points']; ?>" />
            <input type="number" name="price" value="<?php  echo $users_data[$i]['price']; ?>" />
            <input type="submit" name="submit" class="btn btn-success" value="submit" />
            
        </form>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>
                
                
                </td>
                <td><?php echo $users_data[$i]['created_at']; ?></td>
                <td><?php echo $users_data[$i]['updated_at'];  ?></td>
                
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
