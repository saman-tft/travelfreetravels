<?php

$query = "SELECT * FROM plan_retirement WHERE id='".$data['id']."'";
$investor_record = $this->db->query($query)->row_array();

?>
<style type="text/css">
    td, th {padding: 5px;}
    table {
        border-collapse: collapse;
        border-spacing: 0;
    }
    @media print { 
        header, footer, #show_log { display: none; }
        .pag_brk { page-break-before: always; }
    }
</style>
<div style="background:#ccc; width:100%; position:relative">
    <table cellpadding="0" border-collapse cellspacing="0" width="100%" style="font-size:9pt;font-family: 'Open Sans', sans-serif; max-width:850px; margin:0px auto;background-color:#fff; padding:45px;border-collapse:separate; color: #000;">
        <tbody>
            <tr>
                              <td style="font-size:22px; line-height:30px; width:100%; display:block; font-weight:600; text-align:center">Investor Voucher</td>
                           </tr>
            <tr>
                <td valign="bottom" colspan="2" align="left">
                    <img class="ful_logo" style="width: 100px;" src="https://www.alkhaleejtours.com/extras/custom/TMX9604421616070986/images/TMX1512291534825461logo-loginpg.png" alt="" />
                </td>
                <td valign="bottom" colspan="2" style="padding-bottom:10px" align="right"><span>Booking ID : <?= @$investor_record['app_reference'] ?></span><br><span>Booked on : <?= app_friendly_absolute_date(@$investor_record['created_date']) ?></span></td>
            </tr>
            <tr><td colspan="4" style="line-height:7px;padding:0;">&nbsp;</td></tr>
            <tr>
                <td align="right" colspan="4" style="line-height:25px;font-size: 14px; border-top:2px solid #00a9d6; border-bottom:1px solid #00a9d6;"><span style="font-size:9pt;">Status: </span><strong class="<?php echo booking_status_label($investor_record['payment_status']) ?>" style=" font-size:14px;">
                        <?php
                        switch (@$investor_record['payment_status']) {
                            case 'accepted' :
                                echo 'CONFIRMED';
                                break;
                            case 'declined' :
                                echo 'DECLINED';
                                break;
                            case 'BOOKING_FAILED' :
                                echo 'FAILED';
                                break;
                            case 'BOOKING_INPROGRESS' :
                                echo 'INPROGRESS';
                                break;
                            case 'BOOKING_INCOMPLETE' :
                                echo 'INCOMPLETE';
                                break;
                            case 'BOOKING_HOLD' :
                                echo 'HOLD';
                                break;
                            case 'pending' :
                                echo 'PENDING';
                                break;
                            case 'BOOKING_ERROR' :
                                echo 'ERROR';
                                break;
                        }
                        ?></strong>
                </td>
            </tr>
            <?php if ($investor_record ['id'] != '') { ?>
               
            <tr>
                <td colspan="10" style="padding:0;">
                    <table cellspacing="0" cellpadding="5" width="100%" style="font-size:9pt; padding:0;">
                        <tbody>
                            <tr>
                                <td width="100%" style="padding:0;padding-right:14px;">
                                    <table cellspacing="0" cellpadding="5" width="100%" style="font-size:9pt; padding:0;border:1px solid #9a9a9a;">
                                        <tbody>                                      
                                            <tr>
                                                <td style="border-bottom:1px solid #ccc;background: #ccc;"><span style="font-size:10pt">Payment Details</span></td>
                                                <td style="border-bottom:1px solid #ccc;background: #ccc;"><span style="font-size:8pt">Amount ( <?php echo UNIVERSAL_DEFAULT_CURRENCY ?>
                                                        )</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="line-height: 13px;"><span>Full Name</span></td>
                                                <td style="line-height: 13px;"><span> <?php echo $investor_record['fullname']; ?></span></td>
                                            </tr>
                                            <tr>
                                                <td style="line-height: 13px;"><span>Email</span></td>
                                                <td style="line-height: 13px;"><span> <?php echo $investor_record['email']; ?></span></td>
                                            </tr>
                                            <tr>
                                                <td style="line-height: 13px;"><span>Phone</span></td>
                                                <td style="line-height: 13px;"><span> <?php echo $investor_record['phone']; ?></span></td>
                                            </tr>
                <?php 
                    $querycountry = "SELECT * FROM api_country_list WHERE origin='".$investor_record['country']."'";
                    $country = $this->db->query($querycountry)->row_array();
                    $querycity = "SELECT * FROM api_city_list WHERE origin='".$investor_record['city']."'";
                    $city = $this->db->query($querycity)->row_array();
                ?>
                                            <tr>
                                                <td style="line-height: 13px;"><span>Country,State,City</span></td>
                                                <td style="line-height: 13px;"><span> <?php echo $country.''.$investor_record['state'].''.$city; ?></span></td>
                                            </tr>
                                            <tr>
                                                <td style="line-height: 13px;"><span>Zipcode</span></td>
                                                <td style="line-height: 13px;"><span> <?php echo $investor_record['zipcode']; ?></span></td>
                                            </tr>
                                            <tr>
                                                <td style="line-height: 13px;"><span>Address</span></td>
                                                <td style="line-height: 13px;"><span> <?php echo $investor_record['address']; ?></span></td>
                                            </tr>
                                            <tr>
                                                <td style="line-height: 13px;"><span>Passport Number</span></td>
                                                <td style="line-height: 13px;"><span> <?php echo $investor_record['passno']; ?></span></td>
                                            </tr>
                                            <tr>
                                                <td style="line-height: 13px;"><span>Message</span></td>
                                                <td style="line-height: 13px;"><span> <?php echo $investor_record['message']; ?></span></td>
                                            </tr>
                                            <tr>
                                                <td style="line-height: 13px;"><span>Passport ID</span></td>
                                                <td style="line-height: 13px;"><span> <img src="<?php echo $GLOBALS['CI']->template->domain_images($GLOBALS['CI']->template).$investor_record['passid']; ?>" height="100px" width="200px" class="img-thumbnail"></span></td>
                                            </tr>
                                            <tr>
                                                <td style="line-height: 13px;"><span>Passport Copy</span></td>
                                                <td style="line-height: 13px;"><span> <img src="<?php echo $GLOBALS['CI']->template->domain_images($GLOBALS['CI']->template).$investor_record['passcopy']; ?>" height="100px" width="200px" class="img-thumbnail"></span></td>
                                            </tr> 
                                            <tr>
                                                <td style="line-height: 13px;"><span>Package Select</span></td>
                                                <td style="line-height: 13px;"><span> <?php echo $investor_record['packselect']; ?></span></td>
                                            </tr>
                                            <tr>
                                                <td style="line-height: 13px;"><span>Package</span></td>
                                                <td style="line-height: 13px;"><span> <?php echo $investor_record['package']; ?></span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="line-height:3px;padding:0;">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="4" style="padding-bottom:15px;line-height:30px;border-bottom:1px solid #999999;text-align:center"><span style="font-size:10pt; color:#555;">Customer Contact Details | E-mail : <?= $investor_record['email'] ?> | Contact No : <?= $investor_record['phone'] ?></span></td>
            </tr>
            
            <?php
                $query = "SELECT * FROM domain_list";
                $domain_details = $this->db->query($query)->row_array(); ?>
            <tr>
                <td colspan="4" align="right" style="padding-top:10px; line-height:16px"><?php echo $domain_details['domain_name'] ?><br>Email : <?php echo $domain_details['email'] ?><br>Address : <?php echo $domain_details['address'] ?></td>
            </tr>
        </tbody>
    </table>
</div>
<?php } ?>