<style>
    #reschudleflt .container #reschudleflt-row #reschudleflt-column #reschudleflt-box {
  margin: 25px auto;
  max-width: 600px;
   padding: 20px 10px;
   display: flex;
  border: 1px solid #dadada;
  background-color: #EAEAEA;
}
#reschudleflt .container #reschudleflt-row #reschudleflt-column #reschudleflt-box #reschudleflt-form {
  padding: 0px;
}
.sptext{display: block;font-size: 20px;padding: 25px 0px;} 
.texrt{text-align: right;float: right;}
.textdec{text-decoration: underline;}

</style>
<body>
    <div id="reschudleflt">
     

        <div class="container">
            <div id="reschudleflt-row" class="row justify-content-center align-items-center">
                <div id="reschudleflt-column" class="col-md-12 col-xs-12 nopad">
                    <div id="reschudleflt-box">
                        <form id="reschudleflt-form" class="form" action="https://travelfreetravels.com/index.php/general/saverescheduleflights" method="post">
                            <span class="text-center sptext">Kindly, contact our customer support Travel free Travels <strong style="color: #009edb;"> +977 01-5365553</strong></span>
                           <div class="form-group col-md-12">
                                <label for="pnr number" class="text-info">Enter PNR Number:</label><br>
                                <input type="text" name="ticketnumber" id="pnr_number" class="form-control" required>
                                <button class="btn verify_btn" style="display: none;">Verify</button>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="phone" class="text-info">Phone Number:</label><br>
                                <input type="number" name="phonenumber" id="phone_number" class="form-control" required>
                            </div>
                                <div class="form-group col-md-12">
                                <label for="name" class="text-info">Full Name:</label><br>
                                <input type="text" name="fullname" id="full_name" class="form-control" required>
                            </div>
                            <div class="form-group col-md-6"> 
                                <label for="airline" class="text-info">Airlines:</label><br>
                                <input type="text" name="airlines" id="airline" class="form-control" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="date" class="text-info">Date:</label><br>
                                <input type="text" name="date" id="datepickerrs" class="form-control" required>
                            </div>
                             <div class="form-group col-md-12">
                                <label for="name" class="text-info">Email:</label><br>
                                <input type="text" name="email" id="full_name" class="form-control" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="date" class="text-info">Amount :</label><br>
                                <input type="text" name="amount"  class="form-control" required>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="remark" class="text-info">Remarks:</label><br>
                                <textarea name="remarks" id="remarks" class="form-control" rows="4"></textarea>
                            </div>
                            <div class="form-group col-md-12">
                                <input type="submit" name="submit" class="btn btn-info btn-md" value="Submit" >
                            </div> 
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

  <script>
  $( function() {
    $( "#datepickerrs" ).datepicker();
  } );
  </script>