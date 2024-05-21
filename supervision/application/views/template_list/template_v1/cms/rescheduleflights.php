 <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.1.0/css/responsive.bootstrap.min.css" type="text/css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.2.1/css/buttons.bootstrap.min.css" type="text/css" />
  <style>
    .hedfont{font-size: 22px;
    margin-bottom: 30px;
    padding: 0px 15px;}
    #rescheduleflight_paginate,#rescheduleflight_filter{float: right;}
    #rescheduleflight_filter .form-control{margin-left: 10px;}
  </style>
<h1 class="hedfont">Reschedule Flights Enquires</h1>
<table class="table table-bordered" id="rescheduleflight">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">PNR</th>
      <th scope="col">Airline</th>
      <th scope="col">Contact no</th>
      <th scope="col">remarks</th>
      <th scope="col">Amount</th>
      <th scope="col">Created at</th>
      <th scope="col">Updated at</th>
    </tr>
  </thead>
  <tbody>

    <?php
    $n=1;
       for($i=0;$i<count($datas);$i++)
       {
    ?>
    <tr>
      <td scope="row"><?=$n++?></td>
      <td><?=$datas[$i]['ticketnumber']?></td>
      <td><?=$datas[$i]['airlines']?></td>
      <td><?=$datas[$i]['phonenumber']?></td>
      <td><?=$datas[$i]['remarks']?></td>
       <td><?=$datas[$i]['amount']?></td>
      <td><?=$datas[$i]['created_at']?></td>
      <td><?=$datas[$i]['updated_at']?></td>
    </tr>
    <?php
     }
  ?>
   
  </tbody>
  <tfoot>
    <tr>
      <th scope="col">#</th>
      <th scope="col">PNR</th>
      <th scope="col">Airline</th>
      <th scope="col">Contact no</th>
      <th scope="col">remarks</th>
      <th scope="col">Amount</th>
      <th scope="col">Created at</th>
      <th scope="col">Updated at</th>
    </tr>
  </tfoot>
</table>


  <script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
  <script src="//cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>

 <script type="text/javascript">
   $(document).ready( function () {
    $('#rescheduleflight').DataTable();
} );
 </script>