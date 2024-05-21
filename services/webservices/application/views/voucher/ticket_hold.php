<style>
   th,td{padding:5px;}
</style>
<table style="border-collapse: collapse; background: #ffffff;font-size: 12pt; margin: 0 auto; font-family: arial;" width="100%" cellpadding="0" cellspacing="0" border="0">
   <tbody>
      <tr>
         <td style="border-collapse: collapse; padding:10px 20px 20px" >
            <table width="100%" style="border-collapse: collapse;" cellpadding="0" cellspacing="0" border="0">
               <tr>
                  <td style="font-size:15pt; line-height:30px; width:100%; display:block; font-weight:600; text-align:center"></td>
               </tr>
               <tr>
                  <td>
                     <table width="100%" style="border-collapse: collapse;" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                           <td style="padding: 10px; width:65%"></td>
                           <td style="padding: 10px; width:35%">
                              <table width="100%" style="border-collapse: collapse;text-align: right; line-height:15px;" cellpadding="0" cellspacing="0" border="0">
                                 <tr>
                                    <td style="font-size:12pt;"><span style="width:100%; float:left"></span>
                                    </td>
                                 </tr>
                              </table>
                           </td>
                        </tr>
                     </table>
                  </td>
               </tr>
               <tr>
                  <td>Please confirm this ticket. </td>
               </tr>
               <tr>
                  <td style="padding: 10px;">
                     <table cellpadding="5" cellspacing="0" border="0" width="100%" style="border-collapse: collapse;">
                        <tr>
                           <td width="100%" style="padding: 10px;border: 1px solid #cccccc; font-size: 11pt; font-weight: bold;">Reservation Lookup</td>
                        </tr>
                        <tr>
                           <td style="border: 1px solid #cccccc;">
                              <table width="100%" cellpadding="5" style="padding: 10px;font-size: 11pt;">
                                 <tr>
                                    <td><strong>Domain Name</strong></td>
                                    <td><strong>Booking Reference</strong></td>
                                    <td><strong>BookingAPI</strong></td>
                                    <td><strong>BookingID</strong></td>
                                    <td><strong>PNR</strong></td>
                                    <td><strong>Lead Passenger Name</strong></td>
                                    <td><strong>Journey Date</strong></td>
                                 </tr>
                                 <tr>
                                    <td><?=@$domain_name; ?></td>
                                    <td><?=@$AppReference; ?></td>
                                    <td><?=@$booking_api_name; ?></td>
                                    <td><?=@$BookingID; ?></td>
                                    <td><?=@$PNR ?></td>
                                    <td><?= $leade_pax_name['title'].'.  '.$leade_pax_name['first_name'].' '.$leade_pax_name['last_name'] ?></td>
                                    <td><?=@$travel_date ?></td>
                                 </tr>
                              </table>
                           </td>
                        </tr>
                      
                     </table>
                  </td>
               </tr>
            </table>
         </td>
      </tr>
   </tbody>
</table>