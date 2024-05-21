<!--<script src="<?php echo SYSTEM_RESOURCE_LIBRARY ?>/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo SYSTEM_RESOURCE_LIBRARY ?>/datatables/dataTables.bootstrap.min.js"></script> -->
<style>
    .invDownloadType{
        display: flex;
        justify-content: center;
        align-items: self-end;
    }
    @media screen and (max-width: 425px) {
        .invDownloadType{
            display: inline-block;
        }
        .invDown{
            margin-top: 10px;
        }
    }
</style>

<!-- new style and modal for invoice date filter -->
<div class="modal fade" id="invDownloadModal" tabindex="-1" role="dialog" aria-labelledby="invDownloadModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="invDownloadModalLabel">Invoice Download</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <!-- <b style="font-size: medium;">Date wise download</b><br><br> -->
                    <div class="row">
                        <form action="<?php echo base_url() ?>index.php/voucher/all_flight_invoice_GST/<?php echo B2B_USER; ?><?= !empty($_SERVER["QUERY_STRING"]) ? '?' . $_SERVER["QUERY_STRING"] : '' ?>" method="POST" target="_blank">
                            <div class="row invDownloadType">
                                <div class="col col-sm-4">
                                    <label for="">From Date</label><br>
                                    <input type="date" name="from_date" required>
                                </div>
                                <div class="col col-sm-4">
                                    <label for="">To Date</label><br>
                                    <input type="date" name="to_date" required>
                                </div>
                                <div class="col col-sm-4">
                                    <input type="hidden" name="filter_type" value="datewise" hidden>
                                    <button type="submit" class="btn btn-primary invDown">Download</button>
                                </div>
                            </div>
                        </form>
                    </div>


                    <!-- <br><br>
                    <b style="font-size: medium;">Number of invoices download</b><br><br>
                    <div class="row">
                        <form action="<?php echo base_url() ?>index.php/voucher/all_flight_invoice_GST/<?php echo B2B_USER; ?><?= !empty($_SERVER["QUERY_STRING"]) ? '?' . $_SERVER["QUERY_STRING"] : '' ?>" method="POST">
                            <div class="row invDownloadType">
                                <div class="col col-sm-8">
                                    <label for="">Number of invoices</label><br>
                                    <input type="number" min="1" name="inv_number" required>
                                </div>
                                <div class="col col-sm-4">
                                    <input type="hidden" name="filter_type" value="lengthwise" hidden>
                                    <button type="submit" class="btn btn-primary invDown">Download</button>
                                </div>
                            </div>
                        </form>
                    </div> -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
<?= $GLOBALS['CI']->template->isolated_view('report/email_popup') ?>

<?php
if (is_array($search_params)) {
    extract($search_params);
    //echo '<pre>'; print_r($search_params);die;
}
$_datepicker = array(array('created_datetime_from', PAST_DATE), array('created_datetime_to', PAST_DATE));
$this->current_page->set_datepicker($_datepicker);
$this->current_page->auto_adjust_datepicker(array(array('created_datetime_from', 'created_datetime_to')));
?>
<div class="modal fade" id="pax_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document" style="width: 880px;">
        <div class="modal-content">
            <div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-users"></i>
                    Customer Details</h4>
            </div>
            <div class="modal-body">
                <div id="customer_parameters">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="bodyContent col-md-12">
    <div class="panel panel-default clearfix"><!-- PANEL WRAP START -->
        <div class="panel-heading"><!-- PANEL HEAD START -->
            <?= $GLOBALS['CI']->template->isolated_view('report/report_tab_b2b') ?>
        </div><!-- PANEL HEAD START -->
        <div class="panel-body">
            <div class="clearfix">
                <?php echo $GLOBALS['CI']->template->isolated_view('report/make_search_easy'); ?>

            </div>
            <hr>
            <h4>Advanced Search Panel <button class="btn btn-primary btn-sm toggle-btn" data-toggle="collapse" data-target="#show-search">+
                </button> </h4>
            <hr>
            <div id="show-search" class="collapse">

                <form method="GET" autocomplete="off" action="<?= base_url() . 'report/b2b_flight_report?' ?>">

                    <div class="clearfix form-group">
                        <div class="col-xs-4">
                            <label>
                                Agent
                            </label>
                            <select class="form-control" name="created_by_id">
                                <option>All</option>
                                <?= generate_options($agent_details, array(@$created_by_id)) ?>
                            </select>
                        </div>
                        <div class="col-xs-4">
                            <label>
                                PNR
                            </label>
                            <input type="text" class="form-control" name="pnr" value="<?= @$pnr ?>" placeholder="PNR">
                        </div>
                        <div class="col-xs-4">
                            <label>
                                Application Reference
                            </label>
                            <input type="text" class="form-control" name="app_reference" value="<?= @$app_reference ?>" placeholder="Application Reference">
                        </div>
                        <!--<div class="col-xs-4">
                                <label>
                                Phone
                                </label>
                                <input type="text" class="form-control numeric" name="phone" value="<?= @$phone ?>" placeholder="Phone">
                        </div>
                        <div class="col-xs-4">
                                <label>
                                Email
                                </label>
                                <input type="text" class="form-control" name="email" value="<?= @$email ?>" placeholder="Email">
                        </div>-->
                        <div class="col-xs-4">
                            <label>
                                Status
                            </label>
                            <select class="form-control" name="status">
                                <option>All</option>
                                <?= generate_options($status_options, array(@$status)) ?>
                            </select>
                        </div>
                        <div class="col-xs-4">
                            <label>
                                Booked From Date
                            </label>
                            <input type="text" readonly id="created_datetime_from" class="form-control" name="created_datetime_from" value="<?= @$created_datetime_from ?>" placeholder="Request Date">
                        </div>
                        <div class="col-xs-4">
                            <label>
                                Booked To Date
                            </label>
                            <input type="text" readonly id="created_datetime_to" class="form-control disable-date-auto-update" name="created_datetime_to" value="<?= @$created_datetime_to ?>" placeholder="Request Date">
                        </div>
                    </div>
                    <div class="col-sm-12 well well-sm">
                        <button type="submit" class="btn btn-primary">Search</button>
                        <button type="reset" id="btn-reset" class="btn btn-warning">Reset</button>
                        <a href="<?= base_url() . 'report/b2b_flight_report?' ?>" id="clear-filter" class="btn btn-primary">Clear Filter</a>
                    </div>

                </form>
            </div>
            <!-- EXCEL/PDF EXPORT STARTS -->
            <?php if ($total_records > 0) { ?>
                <div class="clearfix"></div>
                <div class="dropdown col-xs-1">
                    <button class="btn btn-info dropdown-toggle" type="button" id="excel_imp_drop" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <i class="fa fa-download" aria-hidden="true"></i> Excel
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="excel_imp_drop">
                        <li>
                            <!-- changes action url changed -->
                            <a href="<?php echo base_url(); ?>index.php/report/export_booking_airline_report/excel/all/download/b2b<?= !empty($_SERVER["QUERY_STRING"]) ? '?' . $_SERVER["QUERY_STRING"] : '' ?>">All Booking download</a>
                        </li>
                        <li>
                            <a href="" data-toggle="modal" data-target="#excelallmodal">All Booking email</a>
                        </li>
                        <li>
                            <!-- changes action url changed -->
                            <a href="<?php echo base_url(); ?>index.php/report/export_booking_airline_report/excel/confirmed/download/b2b<?= !empty($_SERVER["QUERY_STRING"]) ? '?' . $_SERVER["QUERY_STRING"] : '' ?>">Confirmed Booking</a>
                        </li>
                        <li>
                            <a href="" data-toggle="modal" data-target="#excelconfirmmodal">Confirmed Booking email</a>
                        </li>
                        <li>
                            <!-- changes action url changed -->
                            <a href="<?php echo base_url(); ?>index.php/report/export_booking_airline_report/excel/cancelled/download/b2b<?= !empty($_SERVER["QUERY_STRING"]) ? '?' . $_SERVER["QUERY_STRING"] : '' ?>">Cancelled Booking</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" data-toggle="modal" data-target="#excelecancelmodal">Cancelled Booking email</a>
                        </li>
                    </ul>
                </div>
                <div class="dropdown col-xs-1">
                    <button class="btn btn-info dropdown-toggle" type="button" id="pdf_imp_drop" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <i class="fa fa-download" aria-hidden="true"></i> Pdf
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="pdf_imp_drop">
                        <li>
                            <!-- changes action url changed -->
                            <a href="<?php echo base_url(); ?>index.php/report/export_booking_airline_report/pdf/all/download/b2b<?= !empty($_SERVER["QUERY_STRING"]) ? '?' . $_SERVER["QUERY_STRING"] : '' ?>">All Booking download</a>
                        </li>
                        <li>
                            <a href="" data-toggle="modal" data-target="#pdfallmodal">All Booking email</a>
                        </li>
                        <li>
                            <!-- changes action url changed -->
                            <a href="<?php echo base_url(); ?>index.php/report/export_booking_airline_report/pdf/confirmed/download/b2b<?= !empty($_SERVER["QUERY_STRING"]) ? '?' . $_SERVER["QUERY_STRING"] : '' ?>">Confirmed Booking download</a>
                        </li>
                        <li>
                            <a href="" data-toggle="modal" data-target="#pdfconfirmmodal">Confirmed Booking email</a>
                        </li>
                        <li>
                            <!-- changes action url changed -->
                            <a href="<?php echo base_url(); ?>index.php/report/export_booking_airline_report/pdf/cancelled/download/b2b<?= !empty($_SERVER["QUERY_STRING"]) ? '?' . $_SERVER["QUERY_STRING"] : '' ?>">Cancelled Booking download</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" data-toggle="modal" data-target="#pdfcancelmodal">Cancelled Booking email</a>
                        </li>
                    </ul>
                </div>
                <div class="dropdown col-xs-1">
                    <button class="btn btn-info dropdown-toggle" type="button" id="csv_imp_drop" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <i class="fa fa-download" aria-hidden="true"></i> CSV
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="csv_imp_drop">
                        <li>
                            <!-- changes action url changed -->
                            <a href="<?php echo base_url(); ?>index.php/report/export_booking_airline_report/csv/all/download/b2b<?= !empty($_SERVER["QUERY_STRING"]) ? '?' . $_SERVER["QUERY_STRING"] : '' ?>">All Booking download</a>
                        </li>
                        <li>
                            <a href="" data-toggle="modal" data-target="#csvallmodal">All Booking email</a>
                        </li>
                        <li>
                            <!-- changes action url changed -->
                            <a href="<?php echo base_url(); ?>index.php/report/export_booking_airline_report/csv/confirmed/download/b2b<?= !empty($_SERVER["QUERY_STRING"]) ? '?' . $_SERVER["QUERY_STRING"] : '' ?>">Confirmed Booking download</a>
                        </li>
                        <li>
                            <a href="" data-toggle="modal" data-target="#csvconfirmmodal">Confirmed Booking email</a>
                        </li>
                        <li>
                            <!-- changes action url changed -->
                            <a href="<?php echo base_url(); ?>index.php/report/export_booking_airline_report/csv/cancelled/download/b2b<?= !empty($_SERVER["QUERY_STRING"]) ? '?' . $_SERVER["QUERY_STRING"] : '' ?>">Cancelled Booking download</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" data-toggle="modal" data-target="#csvcancelmodal">Cancelled Booking email</a>
                        </li>
                    </ul>



                </div>
                <!-- <a href="<?php echo base_url(); ?>index.php/voucher/all_flight_invoice_GST/<?php echo B2B_USER; ?><?= !empty($_SERVER["QUERY_STRING"]) ? '?' . $_SERVER["QUERY_STRING"] : '' ?>" class="btn btn-success">30 Invoice download</a> -->
                 <a href="#" target="_blank" class="btn btn-success" id="invDownoadBtn"><i class="fa fa-download" aria-hidden="true"></i> Invoice download</a>
            <?php } ?>

        </div>
        <!-- hotel -->

        <div id="excelallmodal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Send Booking report in Email</h4>
                    </div>
                    <div class="modal-body">
                        <!-- changes action url changed -->
                        <form action="<?= base_url() ?>index.php/report/export_booking_airline_report/excel/all/mail/b2b<?= !empty($_SERVER["QUERY_STRING"]) ? '?' . $_SERVER["QUERY_STRING"] : '' ?>" method="post">
                            <div class="form-group">
                                <input type="text" class="form-control mfc" id="email" placeholder="Enter email" name="email" required="">
                                <p>provide multiple email separated by(,)abc@gmail.com,xyz@gmail.com</p>
                                <span id="errormsg"></span>
                            </div>
                            <button type="submit" class="btn btn-default flteml" style="background:#ef0303;border-radius:0px!important;border:1px solid #ef0303!important;">Send</button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>
        <div id="pdfallmodal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Send Booking report in Email</h4>
                    </div>
                    <div class="modal-body">
                        <!-- changes action url changed -->
                        <form action="<?= base_url() ?>index.php/report/export_booking_airline_report/pdf/all/mail/b2b<?= !empty($_SERVER["QUERY_STRING"]) ? '?' . $_SERVER["QUERY_STRING"] : '' ?>" method="post">
                            <div class="form-group">
                                <input type="text" class="form-control mfc" id="email" placeholder="Enter email" name="email" required="">
                                <p>provide multiple email separated by(,)abc@gmail.com,xyz@gmail.com</p>
                                <span id="errormsg"></span>
                            </div>
                            <button type="submit" class="btn btn-default flteml" style="background:#ef0303;border-radius:0px!important;border:1px solid #ef0303!important;">Send</button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>
        <div id="csvallmodal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Send Booking report in Email</h4>
                    </div>
                    <div class="modal-body">
                        <!-- changes action url changed -->
                        <form action="<?= base_url() ?>index.php/report/export_booking_airline_report/csv/all/mail/b2b<?= !empty($_SERVER["QUERY_STRING"]) ? '?' . $_SERVER["QUERY_STRING"] : '' ?>" method="post">
                            <div class="form-group">
                                <input type="text" class="form-control mfc" id="email" placeholder="Enter email" name="email" required="">
                                <p>provide multiple email separated by(,)abc@gmail.com,xyz@gmail.com</p>
                                <span id="errormsg"></span>
                            </div>
                            <button type="submit" class="btn btn-default flteml" style="background:#ef0303;border-radius:0px!important;border:1px solid #ef0303!important;">Send</button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>


        <div id="excelconfirmmodal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Send Booking report in Email</h4>
                    </div>
                    <div class="modal-body">
                        <!-- changes action url changed -->
                        <form action="<?= base_url() ?>index.php/report/export_booking_airline_report/excel/confirmed/mail/b2b<?= !empty($_SERVER["QUERY_STRING"]) ? '?' . $_SERVER["QUERY_STRING"] : '' ?>" method="post">
                            <div class="form-group">
                                <input type="text" class="form-control mfc" id="email" placeholder="Enter email" name="email" required="">
                                <p>provide multiple email separated by(,)abc@gmail.com,xyz@gmail.com</p>
                                <span id="errormsg"></span>
                            </div>
                            <button type="submit" class="btn btn-default flteml" style="background:#ef0303;border-radius:0px!important;border:1px solid #ef0303!important;">Send</button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>
        <div id="excelecancelmodal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Send Booking report in Email</h4>
                    </div>
                    <div class="modal-body">
                        <!-- changes action url changed -->
                        <form action="<?php echo base_url(); ?>index.php/report/export_booking_airline_report/excel/cancelled/mail/b2b<?= !empty($_SERVER["QUERY_STRING"]) ? '?' . $_SERVER["QUERY_STRING"] : '' ?>" method="post">
                            <div class="form-group">
                                <input type="text" class="form-control mfc" placeholder="Enter email" name="email" required="">
                                <p>provide multiple email separated by(,)abc@gmail.com,xyz@gmail.com</p>
                                <span id="errormsg"></span>
                            </div>
                            <button type="submit" class="btn btn-default flteml" style="background:#ef0303;border-radius:0px!important;border:1px solid #ef0303!important;">Send</button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>

        <div id="pdfconfirmmodal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Send Booking report in Email</h4>
                    </div>
                    <div class="modal-body">
                        <!-- changes action url changed -->
                        <form action="<?= base_url() ?>index.php/report/export_booking_airline_report/pdf/confirmed/mail/b2b<?= !empty($_SERVER["QUERY_STRING"]) ? '?' . $_SERVER["QUERY_STRING"] : '' ?>" method="post">
                            <div class="form-group">
                                <input type="text" class="form-control mfc" id="email" placeholder="Enter email" name="email" required="">
                                <p>provide multiple email separated by(,)abc@gmail.com,xyz@gmail.com</p>
                                <span id="errormsg"></span>
                            </div>
                            <button type="submit" class="btn btn-default flteml" style="background:#ef0303;border-radius:0px!important;border:1px solid #ef0303!important;">Send</button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>
        <div id="pdfcancelmodal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Send Booking report in Email</h4>
                    </div>
                    <div class="modal-body">
                        <!-- changes action url changed -->
                        <form action="<?php echo base_url(); ?>index.php/report/export_booking_airline_report/pdf/cancelled/mail/b2b<?= !empty($_SERVER["QUERY_STRING"]) ? '?' . $_SERVER["QUERY_STRING"] : '' ?>" method="post">
                            <div class="form-group">
                                <input type="text" class="form-control mfc" placeholder="Enter email" name="email" required="">
                                <p>provide multiple email separated by(,)abc@gmail.com,xyz@gmail.com</p>
                                <span id="errormsg"></span>
                            </div>
                            <button type="submit" class="btn btn-default flteml" style="background:#ef0303;border-radius:0px!important;border:1px solid #ef0303!important;">Send</button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>


        <div id="csvconfirmmodal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Send Booking report in Email</h4>
                    </div>
                    <div class="modal-body">
                        <!-- changes action url changed -->
                        <form action="<?= base_url() ?>index.php/report/export_booking_airline_report/csv/confirmed/mail/b2b<?= !empty($_SERVER["QUERY_STRING"]) ? '?' . $_SERVER["QUERY_STRING"] : '' ?>" method="post">
                            <div class="form-group">
                                <input type="text" class="form-control mfc" id="email" placeholder="Enter email" name="email" required="">
                                <p>provide multiple email separated by(,)abc@gmail.com,xyz@gmail.com</p>
                                <span id="errormsg"></span>
                            </div>
                            <button type="submit" class="btn btn-default flteml" style="background:#ef0303;border-radius:0px!important;border:1px solid #ef0303!important;">Send</button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>
        <div id="csvcancelmodal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Send Booking report in Email</h4>
                    </div>
                    <div class="modal-body">
                        <!-- changes action url changed -->
                        <form action="<?php echo base_url(); ?>index.php/report/export_booking_airline_report/csv/cancelled/mail/b2b<?= !empty($_SERVER["QUERY_STRING"]) ? '?' . $_SERVER["QUERY_STRING"] : '' ?>" method="post">
                            <div class="form-group">
                                <input type="text" class="form-control mfc" placeholder="Enter email" name="email" required="">
                                <p>provide multiple email separated by(,)abc@gmail.com,xyz@gmail.com</p>
                                <span id="errormsg"></span>
                            </div>
                            <button type="submit" class="btn btn-default flteml" style="background:#ef0303;border-radius:0px!important;border:1px solid #ef0303!important;">Send</button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>
        <!-- end hotel  -->
        <div class="clearfix table-responsive"><!-- PANEL BODY START -->
            <div class="pull-left">
                <?php echo $this->pagination->create_links(); ?> <span class="">Total <?php echo $total_rows ?> Bookings</span>
            </div>

            <table class="table table-condensed table-bordered" id="b2b_report_airline_table">
                <thead>
                    <tr>
                        <th>Sno</th>
                        <!--modified width-->

                        <th style="min-width: 150px;">Reference No</th>
                        <th>Status</th>
                        <th>Lead Pax <br />Details</th>
                        <th>PNR</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Booked Via</th>
                        <!--added following 3 th(s)-->
                        <th style="min-width: 180px; max-width: 180px;">Financial Remarks</th>
                        <th style="min-width: 180px; max-width: 180px;">Other Remarks</th>
                        <th style="min-width: 200px; max-width: 200px;">Creation Details</th>
                        <th>Supplier Name</th>



                        <th>Type</th>
                        <th>BookedOn</th>
                        <th>Travel<br /> date</th>
                        <th>Comm.Fare</th>
                        <th>Commission</th>
                        <th>Segment Discount</th>
                        <th>TDS</th>
                        <th>Admin NetFare</th>
                        <th>Admin<br />Markup</th>
                        <th>GST</th>
                        <th>Agent<br />Commission</th>
                        <th>Agent<br />TDS</th>
                        <th>Agent <br />Net Fare</th>
                        <th>Agent<br />Markup</th>

                        <th>TotalFare</th>

                        <th>Action</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Sno</th>
                        <th>Reference No</th>
                        <th>Status</th>
                        <th>Lead Pax <br />Details</th>
                        <th>PNR</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Booked Via</th>
                        <!--added following 3 th(s)-->
                        <th>Financial Remarks</th>
                        <th>Other Remarks</th>
                        <th>Creation Details</th>
                        <th>Supplier Name</th>



                        <th>Type</th>
                        <th>BookedOn</th>
                        <th>Travel<br /> date</th>
                        <th>Comm.Fare</th>
                        <th>Commission</th>
                        <th>Segment Discount</th>
                        <th>TDS</th>
                        <th>Admin NetFare</th>
                        <th>Admin<br />Markup</th>
                        <th>GST</th>
                        <th>Agent<br />Commission</th>
                        <th>Agent<br />TDS</th>
                        <th>Agent <br />Net Fare</th>
                        <th>Agent<br />Markup</th>

                        <th>TotalFare</th>

                        <th>Action</th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php

                    //debug($table_data['booking_details']);exit;
                    if (valid_array($table_data['booking_details']) == true) {
                        $booking_details = $table_data['booking_details'];
                        $segment_3 = $GLOBALS['CI']->uri->segment(3);
                        $current_record = (empty($segment_3) ? 1 : $segment_3 + 1);
                        // added following section
                        foreach ($booking_details as $b_d_k => $b_d_v) {
                            $arr_origin[] = $b_d_v['origin'];
                        }
                        $arr_org_list = htmlspecialchars(json_encode($arr_origin));
                        // debug($arr_org_list);die;
                    ?>
                        <input type="text" id="arr_org_list" name="arr_org_list" value="<?= $arr_org_list ?>" hidden readonly>
                        <?php
                        // upto here
                        foreach ($booking_details as $parent_k => $parent_v) {
                            extract($parent_v);
                            //debug($parent_v);exit;
                            $action = '';
                            $cancellation_btn = '';
                            $voucher_btn = '';

                            $booked_by = '';

                            //Status Update Button
                            /* if (in_array($status, array('BOOKING_CONFIRMED')) == false) {
                              switch ($booking_source) {
                              case PROVAB_FLIGHT_BOOKING_SOURCE :
                              $status_update_btn = '<button class="btn btn-success btn-sm update-source-status" data-app-reference="'.$app_reference.'"><i class="far fa-database"></i> Update Status</button>';
                              break;
                              }
                              } */



                            $voucher_btn = flight_voucher($app_reference, $booking_source, $status, 'b2b');
                            //$invoice = flight_invoice($app_reference, $booking_source, $status);
                            $invoice = flight_GST_Invoice($app_reference, $booking_source, $status, 'b2b');
                            $cancel_btn = flight_cancel($app_reference, $booking_source, $status);
                            $pdf_btn = flight_pdf($app_reference, $booking_source, $status, 'b2b');
                            $email_btn = flight_voucher_email($app_reference, $booking_source, $status, $email);
                            $customer_details = customer_details($app_reference, $booking_source, $status);
                            $error_details = error_details($app_reference, $booking_source, $status);
                            $jrny_date = date('Y-m-d', strtotime($journey_start));
                            $tdy_date = date('Y-m-d');
                            $diff = get_date_difference($tdy_date, $jrny_date);
                            $action .= check_run_ticket_method($parent_v['app_reference'], $parent_v['booking_source'], $status, $parent_v['is_domestic'], $parent_v['journey_start']);

                            $action .= $voucher_btn;
                            $action .= '<br />' . $pdf_btn;
                            $action .= '<br />' . $email_btn;
                            $action .= '<br />' . $customer_details;
                            $action .= '<br/>' . $invoice;
                            if ($status != 'BOOKING_CONFIRMED' && $status != 'BOOKING_HOLD' && $status != 'BOOKING_CANCELLED' && $status != 'BOOKING_INPROGRESS') {
                                $action .= '<br />' . $error_details;
                            }
                            if ($diff > 0) {
                                $action .= $cancel_btn;
                            }

                            if ($status != 'BOOKING_CANCELLED') {

                                if (strtotime('now') < strtotime($parent_v['journey_start'])) {
                                    $update_booking_details_btn = update_booking_details($app_reference, $booking_source, $status);
                                    $action .= '<br />' . $update_booking_details_btn;
                                }
                            }
                            // added booking_attributes_remarks
                            $booking_attributes_remarks = json_decode($attributes);
                            $action .= get_cancellation_details_button($parent_v['app_reference'], $parent_v['booking_source'], $parent_v['status'], $parent_v['booking_transaction_details']);
                        ?>
                            <tr>
                                <td><?= ($current_record++) ?></td>
                                <td><?php echo $app_reference; ?></td>
                                <td><span class="<?php echo booking_status_label($status) ?>"><?php echo $status ?></span></td>


                                <td>
                                    <?php
                                    echo $lead_pax_name . '<br/>' .
                                        $email . "<br/>" .
                                        $phone;
                                    ?>
                                </td>
                                <td><?= @$pnr ?></td>
                                <td><?php echo $from_loc ?></td>
                                <td><?php echo $to_loc ?></td>
                                <td><?php echo $agency_name; ?></td>

                                <!--added following 3 td(s) for added th(s)-->

                                <td class="<?php if ($booking_attributes_remarks->fin_remarks_counter == 0) { ?>fin-remarks-td-<?= $origin ?> fin-remarks-update-td-<?= $origin ?><?php } ?>">
                                    <p class="fin-remarks-editable-<?= $origin ?>"><?php if ($booking_attributes_remarks->fin_remarks != '') echo $booking_attributes_remarks->fin_remarks;
                                                                                    else echo 'click to add remarks' ?></p>
                                    <form class="fin-edit-remarks-form-<?= $origin ?> hide" method="POST" action="<?= base_url() ?>report/modify_remarks">
                                        <input type="text" name="report_origin" value="<?= $origin ?>" readonly hidden>
                                        <input type="text" name="requested_uri" value="<?php echo $_SERVER['REQUEST_URI']; ?>" readonly hidden>
                                        <input type="text" name="module" value="b2b" readonly hidden>
                                        <input type="text" name="fin_remarks" value="<?= $booking_attributes_remarks->fin_remarks ?>">
                                        <a href="#" class="fin-remarks-update-cancel-<?= $origin ?>">Cancel</a>
                                        <button type="submit" style="border: none; background:none; color:green">Save</button>
                                    </form>
                                </td>
                                <td class="<?php if ($booking_attributes_remarks->oth_remarks_counter == 0) { ?>oth-remarks-td-<?= $origin ?> oth-remarks-update-td-<?= $origin ?><?php } ?>">
                                    <p class="oth-remarks-editable-<?= $origin ?>"><?php if ($booking_attributes_remarks->oth_remarks != '') echo $booking_attributes_remarks->oth_remarks;
                                                                                    else echo 'click to add remarks' ?></p>
                                    <form class="oth-edit-remarks-form-<?= $origin ?> hide" method="POST" action="<?= base_url() ?>report/modify_remarks">
                                        <input type="text" name="report_origin" value="<?= $origin ?>" readonly hidden>
                                        <input type="text" name="requested_uri" value="<?php echo $_SERVER['REQUEST_URI']; ?>" readonly hidden>
                                        <input type="text" name="module" value="b2b" readonly hidden>
                                        <input type="text" name="oth_remarks" value="<?= $booking_attributes_remarks->oth_remarks ?>">
                                        <a href="#" class="oth-remarks-update-cancel-<?= $origin ?>">Cancel</a>
                                        <button type="submit" style="border: none; background:none; color:green">Save</button>
                                    </form>
                                </td>
                                <td>
                                    Added At: <?= $booking_attributes_remarks->remarks_updated; ?><br />

                                    Financial Remarks By <?= $booking_attributes_remarks->fin_remarks_user_name; ?><br />
                                   Other Remarks By <?= $booking_attributes_remarks->oth_remarks_user_name; ?>
                                </td>

                                <!--upto here-->
                                <td>
                                    <?php
                                    if (flight_supplier_name($booking_source) == null) {
                                        $booking_via = 'amadeus';
                                    }
                                    echo flight_supplier_name($booking_source);
                                    ?>
                                </td>


                                <td><?php echo $trip_type_label ?></td>
                                <td><?php echo date('d-m-Y H:i:s A', strtotime($created_datetime)) ?></td>
                                <td><?php echo date('d-m-Y', strtotime($journey_start)) ?></td>
                                <td><?php echo $fare ?></td>
                                <td><?php echo $net_commission ?></td>
                                <td><?php echo $segment_discount ?></td>
                                <td><?php echo $net_commission_tds ?></td>
                                <td><?php echo $net_fare ?></td>
                                <td><?php echo $admin_markup ?></td>
                                <td><?php echo $gst ?></td>
                                <td><?php echo $agent_commission ?></td>
                                <td><?php echo $agent_tds ?></td>
                                <td><?php echo $agent_buying_price - $agent_markup ?></td>
                                <td><?php echo $agent_markup ?></td>

                                <td><?php echo ($grand_total - ($segment_discount + $reward_amount)) ?></td>

                                <td>
                                    <div class="action_system" role="group">

                                        <div class="dropdown">
                                            <button class="dropbtn">
                                                <i class="fa fa-ellipsis-v"></i>
                                            </button>
                                            <div class="dropdown-content">

                                                <?php echo $action; ?>

                                            </div>
                                        </div>

                                    </div>
                                </td>
                            </tr>
                    <?php
                        }
                    } else {
                        echo '<tr><td>---</td><td>---</td><td>---</td><td>---</td><td>---</td><td>---</td><td>---</td>
										   <td>---</td><td>---</td><td>---</td><td>---</td><td>---</td><td>---</td><td>---</td>
										   <td>---</td><td>---</td><td>---</td><td>---</td><td>---</td><td>---</td><td>---</td></tr>';
                    }
                    ?>
                </tbody>
            </table>


        </div>
    </div>
</div>
<!-- Exception Log Modal starts -->
<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel" id="exception_log_modal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="gridSystemModalLabel">Error Log Details - <strong><i id="exception_app_reference"></i></strong></h4>
            </div>
            <div class="modal-body" id="exception_log_container">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Exception Log Modal ends -->
<script>
    $(document).ready(function() {
         $('#invDownoadBtn').on('click', function (e) {
        e.preventDefault();
        $('#invDownloadModal').modal('show');
    });
        // added following scripts

        let jsonArrayString_arr_org = $('#arr_org_list').val();
        let arr_org = JSON.parse(jsonArrayString_arr_org);
        $.each(arr_org, function(arr_key, arr_val) {
            $(document).on('click', '.fin-remarks-update-td-' + arr_val, function(e) {
                e.preventDefault();
                $('.fin-remarks-editable-' + arr_val).addClass('hide');
                $('.fin-edit-remarks-form-' + arr_val).removeClass('hide');
                $('.fin-remarks-td-' + arr_val).removeClass('fin-remarks-update-td-' + arr_val);
            });
            $(document).on('click', '.fin-remarks-update-cancel-' + arr_val, function(e) {
                e.preventDefault();
                $('.fin-remarks-editable-' + arr_val).removeClass('hide');
                $('.fin-edit-remarks-form-' + arr_val).addClass('hide');
                $('.fin-remarks-td-' + arr_val).addClass('fin-remarks-update-td-' + arr_val);
            });
            $(document).on('click', '.oth-remarks-update-td-' + arr_val, function(e) {
                e.preventDefault();
                $('.oth-remarks-editable-' + arr_val).addClass('hide');
                $('.oth-edit-remarks-form-' + arr_val).removeClass('hide');
                $('.oth-remarks-td-' + arr_val).removeClass('oth-remarks-update-td-' + arr_val);
            });
            $(document).on('click', '.oth-remarks-update-cancel-' + arr_val, function(e) {
                e.preventDefault();
                $('.oth-remarks-editable-' + arr_val).removeClass('hide');
                $('.oth-edit-remarks-form-' + arr_val).addClass('hide');
                $('.oth-remarks-td-' + arr_val).addClass('oth-remarks-update-td-' + arr_val);
            });
        });
        // upto here
        //update-source-status update status of the booking from api
        $(document).on('click', '.update-source-status', function(e) {
            e.preventDefault();
            $(this).attr('disabled', 'disabled'); //disable button
            var app_ref = $(this).data('app-reference');
            $.get(app_base_url + 'index.php/flight/get_booking_details/' + app_ref, function(response) {

                console.log(response);
            });
        });
        $('.issue_hold_ticket').on('click', function(e) {
            e.preventDefault();
            var confirm_ticket = confirm('Do you want to confirm the ticket ?');
            if (confirm_ticket == true) {
                var _user_status = this.value;
                var _opp_url = app_base_url + 'index.php/flight/run_ticketing_method/';
                _opp_url = _opp_url + $(this).data('app-reference') + '/' + $(this).data('booking-source');
                toastr.info('Please Wait!!!');
                $.get(_opp_url, function(res) {
                    var obj = JSON.parse(res);
                    toastr.info(obj.Message);
                });
            }
        });
        /*$('.update_flight_booking_details').on('click', function(e) {
         e.preventDefault();
         var _user_status = this.value;
         var _opp_url = app_base_url+'index.php/report/update_flight_booking_details/';
         _opp_url = _opp_url+$(this).data('app-reference')+'/'+$(this).data('booking-source');
         toastr.info('Please Wait!!!');
         $.get(_opp_url, function() {
         toastr.info('Updated Successfully!!!');
         });
         });*/
        $('.update_flight_booking_details').on('click', function(e) {
            e.preventDefault();
            var _user_status = this.value;
            var _opp_url = app_base_url + 'index.php/report/update_pnr_details/';
            _opp_url = _opp_url + $(this).data('app-reference') + '/' + $(this).data('booking-source') + '/' + $(this).data('booking-status');
            toastr.info('Please Wait!!!');
            $.get(_opp_url, function() {
                toastr.info('Updated Successfully!!!');
                //  location.reload();
            });
        });

        //send the email voucher
        $('.send_email_voucher').on('click', function(e) {
            $("#mail_voucher_modal").modal('show');
            $('#mail_voucher_error_message').empty();
            email = $(this).data('recipient_email');
            $("#voucher_recipient_email").val(email);
            app_reference = $(this).data('app-reference');
            book_reference = $(this).data('booking-source');
            app_status = $(this).data('app-status');
            $("#send_mail_btn").off('click').on('click', function(e) {
                email = $("#voucher_recipient_email").val();
                var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
                if (email != '') {
                    if (!emailReg.test(email)) {
                        $('#mail_voucher_error_message').empty().text('Please Enter Correct Email Id');
                        return false;
                    }

                    var _opp_url = app_base_url + 'index.php/voucher/flight/';
                    _opp_url = _opp_url + app_reference + '/' + book_reference + '/' + app_status + '/email_voucher/' + email;
                    toastr.info('Please Wait!!!');
                    $.get(_opp_url, function() {

                        toastr.info('Email sent  Successfully!!!');
                        $("#mail_voucher_modal").modal('hide');
                    });
                } else {
                    $('#mail_voucher_error_message').empty().text('Please Enter Email ID');
                }
            });

        });

        $(document).on('click', '.error_log', function(e) {
            e.preventDefault();
            var app_reference = $(this).data('app-reference');
            var booking_source = $(this).data('booking_source');
            var status = $(this).data('status');
            $.get(app_base_url + 'index.php/flight/exception_log_details?app_reference=' + app_reference + '&booking_source=' + booking_source + '&status=' + status, function(response) {
                $('#exception_app_reference').empty().text(app_reference);
                $('#exception_log_container').empty().html(response);
                $('#exception_log_modal').modal();

            });
        });

    });
</script>
<?php

function get_accomodation_cancellation($courseType, $refId)
{
    return '<a href="' . base_url() . 'index.php/booking/accomodation_cancellation?courseType=' . $courseType . '&refId=' . $refId . '" class="btn"><i class="far fa-exclamation-triangle"></i> Cancel</a>';
}

function update_booking_details($app_reference, $booking_source, $booking_status)
{

    return '<a class="btn update_flight_booking_details" data-app-reference="' . $app_reference . '" data-booking-source="' . $booking_source . '"data-booking-status="' . $booking_status . '"><i class="far fa-sync"></i> Update PNR Details</a>';
}

function flight_voucher_email($app_reference, $booking_source, $status, $recipient_email)
{

    return '<a class="btn send_email_voucher" data-app-status="' . $status . '"   data-app-reference="' . $app_reference . '" data-booking-source="' . $booking_source . '"data-recipient_email="' . $recipient_email . '"><i class="far fa-envelope"></i> Email Voucher</a>';
}

function get_cancellation_details_button($app_reference, $booking_source, $master_booking_status, $booking_customer_details)
{
    //echo '<pre>'; 	print_r ($master_booking_status); die;
    $status = 'BOOKING_CONFIRMED';
    if ($master_booking_status == 'BOOKING_CANCELLED') {
        $status = 'BOOKING_CANCELLED';
    } else if ($master_booking_status == 'BOOKING_FAILED') {
        foreach ($booking_customer_details as $tk => $tv) {
            foreach ($tv['booking_customer_details'] as $pk => $pv) {
                if ($pv['status'] == 'BOOKING_CANCELLED') {
                    $status = 'BOOKING_CANCELLED';
                    break;
                }
            }
        }
    }
    foreach ($booking_customer_details as $val_details) {
        if ($val_details['status'] == "BOOKING_CANCELLED") {
            $status = 'BOOKING_CANCELLED';
        }
    }
    if ($status == 'BOOKING_CANCELLED') {
        return '<a target="_blank" href="' . base_url() . 'index.php/flight/ticket_cancellation_details?app_reference=' . $app_reference . '&booking_source=' . $booking_source . '&status=' . $master_booking_status . '" class="col-md-12 btn"><i class="far fa-info"></i> Cancellation Details</a>';
    }
}

function customer_details($app_reference, $booking_source = '', $status = '')
{
    return '<a  target="_blank" data-app-reference="' . $app_reference . '" data-booking-status="' . $status . '" data-booking-source="' . $booking_source . '" class="btn flight_u customer_details"><i class="fa fa-file"></i>Pax profile</a>';
}

function error_details($app_reference, $booking_source = '', $status = '')
{
    return '<a data-app-reference="' . $app_reference . '" data-booking_source="' . $booking_source . '" data-status="' . $master_booking_status . '" class="error_log btn"><i class="fa fa-exclamation"></i> <small>ErroLog</small></a>';
}
?>
<script type="text/javascript">
    // Show the customer Details
    $(document).on('click', '.customer_details', function(e) {

        e.preventDefault();
        //$(this).attr('disabled', 'disabled');//disable button
        var app_ref = $(this).data('app-reference');
        var booking_src = $(this).data('booking-source');
        var status = $(this).data('booking-status');
        var module = 'flight';
        jQuery.ajax({
            type: "GET",
            url: app_base_url + 'index.php/report/get_customer_details/' + app_ref + '/' + booking_src + '/' + status + '/' + module + '/',
            dataType: 'json',
            success: function(res) {

                $('#customer_parameters').html(res.data);
                $('#pax_modal').modal('show');
            }
        });
    });
</script>