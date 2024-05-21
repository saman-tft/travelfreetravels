      <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Topup Report</title>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    <style>
        input{
  font: inherit;
}

label {
  display: block;
  font-weight: bold;
}
     div.dataTables_wrapper div.dataTables_filter {
            text-align: left;
        }

    </style>
</head>
<body>
    <h1 style="text-align:center;margin:0.5em;">Agent Topup Records</h1>
        <div class="clearfix table-responsive"><!-- PANEL BODY START -->
            <div class="pull-left">
                <h2 style="margin:0.5em;">Total <?php echo $total_rows ?> Records</h2>
            </div>
            <br/>
            <br/>

  </div>
  <!--<div class="input__container"><label for="emailFilter">Email:</label>-->
  <!--<input type="text" id="emailFilter" placeholder="Filter by Email"></div>-->
  <div class="clearfix form-group"><!--<div class="col-xs-4"><label>User ID</label><input type="text" placeholder="User ID" value="" name="uuid" class="search_filter form-control"></div>-->
    <div class="col-xs-4"><label>Email</label><input type="text" id="emailFilter" placeholder="Email" value="" name="email" class="search_filter form-control"></div>
    <div class="col-xs-4"><label>Phone</label><input type="text" id="phoneFilter" placeholder="Phone Number" value="" name="phone" class="search_filter numeric form-control"></div>
</div>
            <table class="table table-condensed table-bordered example3" id="b2c_report_airline_table">
                <thead>
                    <tr>
                        <th>Sno</th>
                        <th>Status</th>
                        <th>Agent Name</th>
                        <th>Agency<br />Name</th>
                        <th>Agency<br />Email</th>
                        <th>Agency<br />Phone Number</th>
                        <th>Payment Status</th>
                        <th>Payment mode</th>
                        <th>Transaction id</th>
                        <th>Paid On</th>
                        <th>Requested Amount</th>
                        <th>Convenience Fees</th>
                        <th>Total Paid Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (valid_array($data) == true) {
                        
                        
                    $current_record = 1;
            
                        foreach ($data as $k => $v) {
                        $remarks = (array)json_decode($v['remarks']);
                        
                        						switch ($remarks['payment_method']) {
							case PAY_NOW:
								$PG = 'FONEPAY';
								break;
							case PAY_AT_BANK:
								$PG = 'CONNECT IPS';
								break;
							case PAY_WITH_ESEWA:
								$PG = 'ESEWA';
								break;
							case PAY_WITH_KHALTI:
								$PG = 'KHALTI';
								break;
							case PAY_WITH_NICA:
								$PG = 'CARD';
								break;
							default:
								$PG = '';
						}
						switch ($remarks['payment_status']) {
							case ACCEPTED:
								$status ="ACCEPTED";
								break;
							case DECLINED:
								$status = "DECLINED";
								break;
							case PENDING:
								$status = "PENDING";
								break;
											case INITIATED:
								$status = "INITIATED";
								break;
											case REFUNDED:
								$status = "REFUNDED";
								break;
											case EXPIRED:
								$status = "EXPIRED";
								break;
											case FULL_REFUND:
								$status = "FULL_REFUND";
								break;
												case PARTIAL_REFUND:
								$status = "PARTIAL_REFUND";
								break;
												case AMBIGUOUS:
								$status = "AMBIGUOUS";
								break;
                                                    case ERROR:
                                $status = "ERROR";
                                break;
                                                    case FAILED:
                                $status = "FAILED";
                                break;
													case NOT_FOUND:
								$status = "NOT_FOUND";
								break;
							default:
								$status = "";
						}
							switch ($remarks['topupStatus']) {
							case TOPUP_INPROGRESS:
								$topUpStatus ="IN PROGRESS";
								break;
							case TOPUP_SUCCESSFUL:
								$topUpStatus = "COMPLETED";
								break;
							case TOPUP_FAILED:
								$topUpStatus = "FAILED";
								break;
							case TOPUP_INITIATED:
							    $topUpStatus = "INITIATED";
								break;
							default:
								$topUpStatus = "";
						}
                        ?>
                           <tr>
                                <td><?= ($current_record++) ?></td>
                                <td><?php echo $topUpStatus;?></td>
                                <td>
                                    <?php
                                    echo $v['name'];
                                    ?>
                                </td>
                                     <td>
                                    <?php
                                    echo $v['company_name'];
                                    ?>
                                </td>
                                     <td>
                                    <?php
                                    echo provab_decrypt($v['email']);
                                    ?>
                                </td>
                                     <td>
                                    <?php
                                    echo $v['phone'];
                                    ?>
                                </td>
                                         <td><?php echo $status;?></td>
                                                  <td><?php echo $PG?></td>
                                <td><?= $v['refernce_code'] ?></td>

                                <td><?php echo $v['created_date'] ?></td>
                                <td>
                                    <?php


                                    echo $v['amount'];

                                    ?>
                                </td>
                                           <td>
                                    <?php


                                    echo $remarks['pg_convenience'];

                                    ?>
                                </td>
                                                           <td>
                                    <?php


                                    echo $remarks['received_amount'];

                                    ?>
                                </td>
                            </tr>
                    <?php
                        }
                    } else {
                        echo '<tr><td>---</td><td>---</td><td>---</td><td>---</td><td>---</td><td>---</td>
								 		  <td>---</td><td>---</td><td>---</td><td>---</td><td>---</td><td>---</td>
										  </tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </div>
</div>
</body>
<script>
        $(document).ready(function() {
            var table = $('#b2c_report_airline_table').DataTable({
                columnDefs: [
                    { type: 'num', targets: 0 }
                ]
            });

            $('#emailFilter').on('keyup', function() {
                table.column(4).search(this.value).draw();
            });
                        $('#phoneFilter').on('keyup', function() {
                table.column(5).search(this.value).draw();
            });
        });
    </script>
