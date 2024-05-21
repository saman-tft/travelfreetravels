<?php
// debug($data);exit;
$query = "SELECT * FROM plan_retirement WHERE id='".$data['id']."'";
$investor_record = $this->db->query($query)->row_array();
//debug($investor_record);exit;
//debug($terms_conditions);exit;
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
    <table cellpadding="0" border-collapse cellspacing="0" width="100%" style="font-size:9pt;font-family: 'Open Sans', sans-serif; max-width:800px; margin:0px auto;background-color:#fff; padding:45px;border-collapse:separate; color: #000;">
        <tbody>
            <tr>
                              <td style="font-size:14px; width:100%; display:block; font-weight:600; text-align:center">Investor Voucher</td>
                           </tr>

                           <!-- <tr>
                               <td align="left"> <img class="ful_logo" style="width: 100px;" src="<?php echo $GLOBALS['CI']->template->domain_images($GLOBALS['CI']->template->get_domain_logo()); ?>" alt="" /></td>
                           </tr> -->
            <tr>
              <td>
            <table width="100%" style="border-collapse: collapse;" cellpadding="0" cellspacing="0" border="0">
                 <tr>
                    <td style="padding: 10px;width:65%;"><img style="width:110px;" src="<?php echo $GLOBALS['CI']->template->domain_images($GLOBALS['CI']->template->get_domain_logo()); ?>"></td>
                    <td style="padding: 10px;width:35%"><table width="100%" style="border-collapse: collapse;text-align: right; font-size:10pt" cellpadding="0" cellspacing="0" border="0" valign="bottom">                 
                         <tr>
                <td valign="bottom" colspan="2" style="top:100px" align="right"><span>Booking ID : <?= @$investor_record['app_reference'] ?></span><br><span>Booked on : <?= app_friendly_absolute_date(@$investor_record['created_date']) ?></span></td>
                </tr>
                         </table></td>
                  </tr>
                  
                  
                </table>
                </td>
            </tr>
            <!-- <tr><td colspan="4" style="line-height:7px;padding:0;">&nbsp;</td></tr> -->
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
                    <td colspan="4" style="font-size: 15pt;font-weight: 600;text-align: center;padding: 10px 0 0; line-height:5px;"></td>
                </tr>
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
                                                <td style="line-height: 13px;"><span><strong>Full Name</strong></span></td>
                                                <td style="line-height: 13px;"><span> <?php echo $investor_record['fullname']; ?></span></td>
                                            </tr>
                                            <tr>
                                                <td style="line-height: 13px;"><span><strong>Email</strong></span></td>
                                                <td style="line-height: 13px;"><span> <?php echo $investor_record['email']; ?></span></td>
                                            </tr>
                                            <tr>
                                                <td style="line-height: 13px;"><span><strong>Phone</strong></span></td>
                                                <td style="line-height: 13px;"><span> <?php echo $investor_record['phone']; ?></span></td>
                                            </tr>
                <?php 
                    $querycountry = "SELECT * FROM api_country_list WHERE origin='".$investor_record['country']."'";
                    $country = $this->db->query($querycountry)->row_array();
                    $querycity = "SELECT * FROM api_city_list WHERE origin='".$investor_record['city']."'";
                    $city = $this->db->query($querycity)->row_array();
                ?>
                                            <tr>
                                                <td style="line-height: 13px;"><span><strong>Country,State,City</strong></span></td>
                                                <td style="line-height: 13px;"><span> <?php echo $country['name'].' , '.$investor_record['state'].' , '.$city['destination']; ?></span></td>
                                            </tr>
                                            <?php if($investor_record['accountno']!=''){ ?>
                                            <tr>
                                                <td style="line-height: 13px;"><span><strong>Bank Account Number</strong></span></td>
                                                <td style="line-height: 13px;"><span> <?php echo $investor_record['accountno']; ?></span></td>
                                            </tr> 
                                            <?php } ?>
                                            <?php if($investor_record['bankname']!=''){ ?>
                                            <tr>
                                                <td style="line-height: 13px;"><span><strong>Bank Name</strong></span></td>
                                                <td style="line-height: 13px;"><span> <?php echo $investor_record['bankname']; ?></span></td>
                                            </tr> 
                                            <?php } ?>
                                            <?php if($investor_record['sortcode']!=''){ ?>
                                            <tr>
                                                <td style="line-height: 13px;"><span><strong>Bank Sort Code</strong></span></td>
                                                <td style="line-height: 13px;"><span> <?php echo $investor_record['sortcode']; ?></span></td>
                                            </tr> 
                                            <?php } ?>
                                            <?php if($investor_record['iban']!=''){ ?>
                                            <tr>
                                                <td style="line-height: 13px;"><span><strong>IBAN Number</strong></span></td>
                                                <td style="line-height: 13px;"><span> <?php echo $investor_record['iban']; ?></span></td>
                                            </tr> 
                                            <?php } ?>
                                            <tr>
                                                <td style="line-height: 13px;"><span><strong>Zipcode</strong></span></td>
                                                <td style="line-height: 13px;"><span> <?php echo $investor_record['zipcode']; ?></span></td>
                                            </tr>
                                            <tr>
                                                <td style="line-height: 13px;"><span><strong>Address</strong></span></td>
                                                <td style="line-height: 13px;"><span> <?php echo $investor_record['address']; ?></span></td>
                                            </tr>
                                            <tr>
                                                <td style="line-height: 13px;"><span><strong>Passport Number</strong></span></td>
                                                <td style="line-height: 13px;"><span> <?php echo $investor_record['passno']; ?></span></td>
                                            </tr>
                                            <tr>
                                                <td style="line-height: 13px;"><span><strong>Message</strong></span></td>
                                                <td style="line-height: 13px;"><span> <?php echo $investor_record['message']; ?></span></td>
                                            </tr> 
                                           <tr>
                                                <td style="line-height: 13px;"><span><strong>Passport ID</strong></span></td>
                                                <td style="line-height: 13px;"><span> <img src="<?php echo $GLOBALS['CI']->template->domain_images($GLOBALS['CI']->template).$investor_record['passid']; ?>" height="50px" width="100px" class="img-thumbnail"></span></td>
                                            </tr>
                                            <tr>
                                                <td style="line-height: 13px;"><span><strong>Passport Copy</strong></span></td>
                                                <td style="line-height: 13px;"><span> <img src="<?php echo $GLOBALS['CI']->template->domain_images($GLOBALS['CI']->template).$investor_record['passcopy']; ?>" height="50px" width="100px" class="img-thumbnail"></span></td>
                                            </tr> 
                                            <tr>
                                                <td style="line-height: 13px;"><span><strong>Package Select</strong></span></td>
                                                <td style="line-height: 13px;font-size: 12px;padding-left: 2px;"><span><strong> <?php echo $investor_record['packselect']; ?></strong> </span></td>
                                            </tr>
                                            <tr>
                                                <td style="line-height: 13px;"><span><strong>Package</strong></span></td>
                                                <td style="line-height: 13px;color: #40b5ec;font-size: 12px;text-align: left;padding: 0px"><span><strong> <?php echo $investor_record['package']; ?></strong></span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <!-- <tr>
                <td style="line-height:3px;padding:0;">&nbsp;</td>
            </tr> -->
            <tr>
                <td colspan="4" style="padding-bottom:15px;line-height:30px;border-bottom:1px solid #999999;text-align:center"><span style="font-size:10pt; color:#555;">Customer Contact Details | E-mail : <?= $investor_record['email'] ?> | Contact No : <?= $investor_record['phone'] ?></span></td>
            </tr>
            <tr>
                <td colspan="4" style="line-height:8px;padding:0;">&nbsp;</td>
            </tr>
            <!-- <tr>
                <td colspan="4" align="right" style="padding-top:10px; line-height:16px"><img src="https://www.alkhaleejtours.com/extras/custom/TMX9604421616070986/images/alk.gif" width="70px" class=""></td>
            </tr>-->
            <!-- <tr>
                <td colspan="4" ><span style="line-height:23px; font-size:10pt;">Important Information</span></td>
            </tr>
            <tr>
                <td colspan="4" style="font-size:9pt; color:#555">
                    <?php echo $investor_record['terms_conditions']; ?>
                    
                </td>
            </tr>
            <tr>
                <td colspan="4" style="line-height:8px;padding:0;border-bottom:1px solid #999999;">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="4" style="line-height:8px;padding:0;">&nbsp;</td>
            </tr> -->
            <?php
                $query = "SELECT * FROM domain_list";
                $domain_details = $this->db->query($query)->row_array();
                // debug($domain_details);exit;
                 ?>
            <tr>
                <td colspan="4" align="right"><?php echo strtoupper($domain_details['domainname']) ?><br>Email : <?php echo $domain_details['email'] ?><br>Phone : <?php echo $domain_details['phone'] ?><br>Address : <?php
                 // echo $domain_details['address'];
                echo STATIC_ADDRESS;
                 ?>
                 <br/><?php echo STATIC_COUNTRY; ?></td>
            </tr>
            <!-- <tr>
                <td colspan="4" align="right" style="padding-top:10px; line-height:16px"><img src="https://www.alkhaleejtours.com/extras/custom/TMX9604421616070986/images/alkhaleej.png" width="150px" height="70px" ></td>
            </tr> -->
        </tbody>
    </table>
</div>
<?php } ?>