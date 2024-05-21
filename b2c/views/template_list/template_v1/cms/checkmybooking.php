<style>
    #login .container #login-row #login-column #login-box {
  margin: 25px auto;
  padding: 20px 25px;
  max-width: 600px;
  border: 1px solid #dadada;
  background-color: #EAEAEA;
}
.pt-5{margin-top: 20px;}
#login .container #login-row #login-column #login-box #login-form {
  padding: 20px;
}
.sptext{display: block;}
.texrt{text-align: right;float: right;} 
.textdec{text-decoration: underline;}
.blutext{color: #009edb;text-decoration: underline;}
.contact_details{padding: 20px 0px;}
.sidrht{float: right;
    /*width: 48%;*/
    padding: 7px 0px;
    display: inline-flex;
 }
 .sidrht label.lbllbl{margin-left: 0px!important;}
.textdec{padding: 0px 10px!important;}

</style>
<body>
    <div id="login">
        <h3 class="text-center text-white pt-5">Check my booking</h3>

        <div class="container">
            <div id="login-row" class="row justify-content-center align-items-center">
                <div id="login-column" class="col-md-12 col-xs-12 nopad">
                    <div id="login-box">
                        <form class="form"  method="post"  action="https://travelfreetravels.com/index.php/general/processcheckbooking">
                            <span class="text-center sptext">Do you have any booking status?</span>
                            <h5 class="text-center text-info">Enter your details here to access your booking</h5>
                            <div class="form-group">
                                <label for="email" class="text-info">Email:</label><br>
                                <input type="email" name="email" id="email_id" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="phone" class="text-info">Phone Number:</label><br>
                                <input type="number" name="phone" id="phone_number" class="form-control">
                            </div>
                            <div class="form-group pnrsh">
                                <label for="phone" class="text-info">PNR:</label><br>
                                <input type="text" name="pnr"  class="form-control">
                            </div>
                            <div class="form-group">
                                <input type="submit" name="submit" class="btn btn-info btn-md" value="Submit">
                                <div class="sidrht">
                                <div class="squaredThree">
                                	<input type="checkbox" value="AI" name="check" class="airlinecheckbox" id="squaredThree1"><label for="squaredThree1"></label>
                                </div>
                                <label for="squaredThree1" class="lbllbl">Dont have PNR ? then</label>
                                <a href="#" class="btn btn-link textdec">
                                   Connect Us
                                </a>
                              </div>
                            </div>
                            
                        <div class="contact_details">
                          <h4>Contact Us</h4>
                         <strong>Hotline/Telephone  <span class="blutext"> +977 01-5365553 </span></strong>
                          <br>
                          <strong>Customer service 12/24 Hours</strong>
                        </div>
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    $(document).ready(function(){
              $("#checkpnr").click(function(){

                   if ($('#checkpnr').is(':checked')) {

                  //  $(".pnrsh").hide();

                   }
                   else
                   {
                    //    $(".pnrsh").show();
                   }

              });
});
</script>