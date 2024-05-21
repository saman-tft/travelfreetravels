 <div id="redeem-data" >
                                                <div class="col-md-12 nopad">
                                                  
                                                    <table class="table table-bordered" style="width: 100%;margin-top:10px;">
                                                        <thead>
                                                            <tr>
                                                               
                                                                <th>Product Name</th>
                                                                <th>Used Rewards</th>
                                                                <th>Redeemed Date</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                          <?php
                                                        //  debug($getproduct);
                                                          for($i=0;$i<count($getproduct);$i++)
                                                          {
                                                          //    echo $i;
                                                          ?>
                                                            <tr>
                                                               
                                                                <td><?php echo $getproduct[$i]['name']; ?></td>
                                                                <td><?php echo $getproduct[$i]['point']; ?></td>
                                                                <td><?php echo $getproduct[$i]['created_date']; ?></td>
                                                               
                                                            </tr>
                                                          <?php
                                                          }
                                                          ?>

                                                          
                                                        </tbody>
                                                    </table>

                                                   

                                                   

                                                  
                                                </div>
                                            </div>