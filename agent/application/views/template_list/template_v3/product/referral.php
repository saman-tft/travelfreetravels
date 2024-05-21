<div>
    <table class="table table-striped table-bordered" >
                            <thead>
                                <tr>
                                    <th>Referral code</th>
                                    <th>Referrer email</th>
                                    <th>user email</th>
                                    <th>status</th>
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
            <td><?php echo $i++; ?></td>
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
    
</div>