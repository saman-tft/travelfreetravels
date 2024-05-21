<table dir="ltr" style="width:100%;font-size:10pt;font-family:arial,sans,sans-serif;border-collapse:collapse;border:none; max-width: 800px; margin:0 auto" cellspacing="0" cellpadding="5" border="1">
   <tbody>
      <tr>
         <td width="20%" colspan="2" rowspan="2" colspan="1" style="background: #ddd;font-family:Arial;border:1px solid #d3d3d3;overflow:hidden;vertical-align:middle;font-weight:bold;text-align:center">
            <div style="max-height:42px"><span class="aBn" data-term="goog_1180971059" tabindex="0"><span class="aQJ"><?=$updated_flight_details['avail_date']?></span></span></div>
         </td>
         <td width="20%" style="background: #ddd;font-family:Arial;border:1px solid #d3d3d3;overflow:hidden;vertical-align:middle;font-size:11pt;font-weight:bold;text-align:center"><?=$segment_details['origin']?></td>
         <td width="20%" style="background: #ddd;font-family:Arial;border:1px solid #d3d3d3;overflow:hidden;vertical-align:middle;font-size:11pt;font-weight:bold;text-align:center"><?=$segment_details['destination']?></td>
         <td width="20%" style="background: #ddd;font-family:Arial;border:1px solid #d3d3d3;overflow:hidden;vertical-align:middle;font-size:11pt;font-weight:bold;text-align:center"><?=$segment_details['airline_name']?></td>
         <td width="20%" style="background: #fffc0099;font-family:Arial;border:1px solid #d3d3d3;overflow:hidden;vertical-align:middle;font-size:11pt;font-weight:bold;text-align:center;"> &nbsp;PNR&nbsp;&nbsp; </td>
      </tr>
      <tr>
         <td style="font-family:Arial;border:1px solid #d3d3d3;overflow:hidden;vertical-align:middle;background-color:#ddd;font-weight:bold;text-align:center"><span class="aBn" data-term="goog_1180971060" tabindex="0"><span class="aQJ"><?=$updated_flight_details['dep_time']?></span></span></td>
         <td style="font-family:Arial;border:1px solid #d3d3d3;overflow:hidden;vertical-align:middle;background-color:#ddd;font-weight:bold;text-align:center"><span class="aBn" data-term="goog_1180971061" tabindex="0"><span class="aQJ"><?=$updated_flight_details['arr_time']?></span></span></td>
         <td style="font-family:Arial;border:1px solid #d3d3d3;overflow:hidden;vertical-align:middle;background-color:#ddd;font-weight:bold;text-align:center"><?=$segment_details['carrier_code']?>-<?=$segment_details['flight_num']?></td>
         <td rowspan="<?=count($data)+2?>" style="font-family:Arial;border:1px solid #d3d3d3;overflow:hidden;vertical-align:middle;background-color:#fcfcfc;font-weight:bold;text-align:center;font-size: 16px;color: #2b8c00;  text-transform: uppercase;"><?=$updated_flight_details['pnr']?></td>
      </tr>

      <?php
      $sl = 0;
         foreach($data as $k=>$d){
            $sl  = $sl +1;
            if($k==0){
            ?>
              <td rowspan="<?=count($data)+1?>" style="font-family:Arial;border:1px solid #d3d3d3;overflow:hidden;vertical-align:middle;font-size:11pt;font-weight:bold;text-align:center">Pax Info</td>
            <?php    
            }
            ?>
          <tr>
       
         <td style="font-family:Arial;overflow:hidden;vertical-align:middle;font-weight:bold;color:rgb(0,0,0);text-align:center;border:1px solid rgb(204,204,204)"><?=$sl?></td>
         <td style="font-family:Arial;overflow:hidden;vertical-align:middle;font-weight:bold;color:rgb(51,51,51);text-align:center;border:1px solid rgb(204,204,204)"><?=$d['title']?></td>
         <td style="font-family:Arial;overflow:hidden;vertical-align:middle;font-weight:bold;text-align:center;border:1px solid rgb(204,204,204)"><?=$d['first_name']?></td>
         <td style="font-family:Arial;border:1px solid #d3d3d3;overflow:hidden;vertical-align:middle;font-weight:bold;color:rgb(51,51,51);text-align:center"><?=$d['last_name']?></td>
      </tr>


            <?php
         }

      ?>
   
   </tbody>
</table>