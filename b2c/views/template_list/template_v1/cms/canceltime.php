<style>
    #cancelation_flt .container #cancelation_flt-row #cancelation_flt-column #cancelation_flt-box {
  margin: 25px auto;
  max-width: 600px;
   padding: 20px 10px;
  display: flex;
  border: 1px solid #dadada;
  background-color: #EAEAEA;
}
.pt-5{margin-top: 20px;}
#cancelation_flt .container #cancelation_flt-row #cancelation_flt-column #cancelation_flt-box #cancelation_flt-form {
  padding: 0px;
}
.sptext{display: block;font-size: 20px;padding: 50px 10px;}
.texrt{text-align: right;float: right;}
.textdec{text-decoration: underline;}

</style>
<body>
    <div id="cancelation_flt">
      
        <h3 class="text-center text-white pt-5">Cancellation Timelines Policies and Process </h3>
        <div class="container">
            <div id="cancelation_flt-row" class="row justify-content-center align-items-center">
                <div id="cancelation_flt-column" class="col-md-12 col-xs-12 nopad">
                    <div id="cancelation_flt-box">
                        <form id="cancelation_flt-form" class="form" action="https://travelfreetravels.com/index.php/general/savecanceltime" method="post">
                            
                           <div class="form-group col-md-6 col-xs-12">
                                <label for="pnr number" class="text-info">Enter PNR Number:</label><br>
                                <input type="text" name="PNR" id="pnr_number" class="form-control" required>
                            </div>
                            <div class="form-group col-md-6 col-xs-12">
                                <label for="phone" class="text-info">Phone Number:</label><br>
                                <input type="number" name="contactnumber" id="phone_number" class="form-control" required>
                            </div>
                            <div class="form-group col-md-12 col-xs-12">
                                <label for="name" class="text-info">Full Name:</label><br>
                                <input type="text" name="fullname" id="full_name" class="form-control" required>
                            </div>
                            <div class="form-group col-md-12 col-xs-12">
                                <label for="name" class="text-info">Email:</label><br>
                                <input type="text" name="email" id="full_name" class="form-control" required>
                            </div>
                            <div class="form-group col-md-12 col-xs-12">
                                <label for="remark" class="text-info">Remarks:</label><br>
                                <textarea name="remarks" id="remarks" class="form-control" rows="4"></textarea>
                            </div>
                            <div class="form-group col-md-12 col-xs-12">
                                <input type="submit" name="submit" class="btn btn-info btn-md" value="Submit" > 
                            </div> 
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
