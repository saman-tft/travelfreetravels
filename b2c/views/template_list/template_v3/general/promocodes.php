<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .container-p {
    /*font-weight:800;*/
        margin: 0 auto;


        /*padding: 0 80px;*/
        font-size: 16px;
    
        justify-content: center;
    }

    .card-container-p {
        display: flex;
        justify-content: space-between;
        padding: 15px;
        gap: 10px;
        margin-bottom: 10px;


    }
    #sign__in-small{
        display:none;
        }

    .card-content-p {

        color: white;
            font-family: monospace;
        width: 60%;
        align-items: center;
        padding: 20px;
        text-align: center;
        background: #337AB7;
        justify-content: center;
        box-shadow: 10px 10px 10px black;
    }

    .value-p {
        font-size: 2.5rem;
        font-weight: 800;
        font-style: italic;

    }


    .card-description-p {
        width: 100%;
        margin-left: 10px;
        font-family: monospace;
        font-weight:800;
        align-items: center;
        padding: 20px;
        text-align: justify;
        hyphens: auto;
        color: white;
        font-weight: 700;
        box-shadow: 10px 10px 10px black;
        background: #337AB7;
    }

    .card-header-p {
        padding-bottom: 5px;
        border-bottom: dotted 1px;
        border-color: whitesmoke;
    }

    .description-p {
        display: flex;

    }

    .promo__row {
        display: flex;
        align-items: center;
        margin: 25px auto;
        width: fit-content;
    }

    .promo__code {
        border: 1px dashed white;
        padding: 10px 20px;
        font-weight: 800;
        font-size: 2rem;
        width: fit-content;
        border-right: 0;
    }

    .copy__button {
        border: 1px solid white;
        background: white;
        font-weight: 800;
        padding: 10px 10px;
        color: #7158fe;
        cursor: pointer;

    }

    .description-p-mobile {
        justify-content: center;
        align-items: center;
        margin-top: 1em;
        border: 1px dashed white;
    }

    .description-p-mobile {
        display: none;
    }

    body .topssec {
        display: none !important;
    }

    footer {
        display: none !important;
    }
 #space_container{
     display:none;
 }

    .logo__container {
        display: flex;
        max-height: 300px;
        justify-content: flex-start;
        overflow: hidden;
        background-color: white;
 

    }

    .banner__image {
        min-height: 50vh;
        background-image: url('<?php echo SYSTEM_IMAGE_DIR . "promocode-banner-large.jpg" ?>');
        margin-bottom: 20px;
        background-size:contain;
        background-position:center;
        background-repeat:no-repeat;
    }

    .title {
        display: flex;
        justify-self: center;
        font-size: 1em;
        font-weight: 800;
        margin: 1em;
        color: purple;
    }

    .sector {
        margin: 2px;
    }

    .login__text {
        display: grid;
        place-content: center;
    }

    .signin__prompt {
        font-size: 3rem;
        font-weight: 800;
        color: purple;
        display: flex;
        justify-content: center;
        align-items:center;
        margin: 1em;
    }
    .signin__prompt p{
        text-align:center;
    }

    .signin__prompt a:hover {
        text-decoration: underline !important;
    }

    .back__container {
        display: flex;
        padding: 0 9em;
        justify-content: space-between;
    }

    .back__container-text {
        color: purple;
        font-weight: 800;
        font-size: 3rem;
    }

    .book__now {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 10px;
        border: 1px solid purple;
        background-color: purple;
        border-radius: 20px;
        color: white;
        box-shadow: 2px 3px 3px black;

    }

    .book__now:hover {
        text-decoration: none;
        color: white;
        background-color: #8f53a1;
    }

    .instructions {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;

        color: black;
        padding: 2em;
        text-align: justify;
        hyphens: auto;
    }

    .instructions h2 {
        font-weight: 800;
        margin-bottom: 1em;
    }

    #instructions ul,
    li {
        list-style-type: decimal;
        font-weight: 500;

    }

    .terms {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        color: black;
        padding: 2em;
        text-align: justify;
        hyphens: auto;
    }

    .terms h2 {
        font-weight: 800;
        margin-bottom: 1em;
    }

    #instructions ul,
    li {
        list-style-type: decimal;
        font-weight: 500;

    }
    
    .home-text {

        text-align: center;
        background-color: purple;
        border: 1px solid purple;
        outline: none;
        text-decoration: none;
        box-shadow: 5px 5px 5px purple;
        color: white;
        padding: 10px 50px;
        font-size: 18px;
        max-height: 50px;
        transition: 0.3s all ease-in-out;
    }

    .home-text:hover {
        background-color: #0ba0dc;
        color: white;
    }
       
     .back_to_home {
        margin-right: 2em;
        flex-grow: 1;
        display: flex;
        justify-content: flex-end;
        align-items: center;

    }

    .terms p{
        margin:2em 2em 2em 0;
        font-weight:800;
        
    }
    @media (max-width:280px) {

        .description-p-mobile {
            display: flex;
        }

        .card-description-p {
            display: none;
        }

        .container-p {
            padding: 0;

        }
        .sign__in-large{
            display:none;
        }
        .sign__in-small{
            display:block;
        }

        h2 {
            font-size: 2rem;
            text-align: center;
        }

        ul,
        li {
            font-size: 1em;
        }

        .card-content-p {
            width: 100%;
        }

        .value-p {
            font-size: 2rem;
        }

        .promo__code {
            padding: 5px 5px;
        }

        .copy__button {
            padding: 8px 8px;
        }

        .date {
            font-size: 1.3rem;
        }

        .signin__prompt {
            font-size: 1rem;
            font-weight: 800;
            color: purple;
            display: flex;
            justify-content: center;
            margin: 1em;
        }

        .banner__image {
        background-image: url('<?php echo SYSTEM_IMAGE_DIR . "promocode-banner.jpeg" ?>');
            min-height: 30vh;
            margin-bottom: 20px;
        }
 
.back__container{
    padding:0 2em;
}
        .back__container-text {
            font-size: 1rem;
        }

        .book__now {
            padding: 5px;
            font-size: 0.8rem;
        }

        .home-text {
            padding: 10px 10px;
            font-size: 12px;
        }
    }

    @media only screen and (min-width: 281px) and (max-width: 480px) {

        .description-p-mobile {
            display: flex;
        }

        .card-description-p {
            display: none;
        }

        .card-content-p {
            width: 100%;
        }

        .container-p {
            padding: 0;

        }
        .sign__in-large{
            display:none;
        }
         .sign__in-small{
            display:block;
        }

        h2 {
            font-size: 2rem;
            text-align: center;
        }

        ul,
        li {
            font-size: 1em;
        }

        .card-content-p {
            width: 100%;
        }

        .value-p {
            font-size: 2rem;
        }

        .promo__code {
            padding: 5px 5px;
        }

        .copy__button {
            padding: 8px 8px;
        }

        .date {
            font-size: 1.3rem;
        }

        .signin__prompt {
            font-size: 1.4rem;
            font-weight: 800;
            color: purple;
            display: flex;
            justify-content: center;
            align-items:center;
            margin: 1em;
        }


        .banner__image {
                    background-image: url('<?php echo SYSTEM_IMAGE_DIR . "promocode-banner.jpeg" ?>');
            min-height: 30vh;
            margin-bottom: 20px;
        }
        .back__container {
            padding: 0 2em;
        }

        .back__container-text {
            font-size: 1.4rem;
        }

        .book__now {
            padding: 8px;
            font-size: 1.4rem;
        }
          .home-text {
            padding: 10px 10px;
            font-size: 12px;
        }
    }
 @media only screen and (min-width: 281px) and (max-width: 400px) {
.back__container{
    padding:0 2em;
}
        .back__container-text {
            font-size: 1rem;
        }

        .book__now {
            padding: 5px;
            font-size: 0.8rem;
        }
 }
    @media only screen and (min-width: 480px) and (max-width: 768px) {
        
        .back__container {
            padding: 0 1em;
        }
        .container-p {
            margin-top: 100px;
        }

        .description-p-mobile {
            display: flex;
        }

        .card-description-p {
            display: none;
        }

        .card-content-p {
            width: 100%;
        }

        .container-p {
            padding: 0;

        }

        .signin__prompt {
            margin-top: 30px;
            font-size: 1.8rem;
            font-weight: 800;
            color: purple;
            display: flex;
            justify-content: center;

        }

        .back__container,
        .signin__prompt {
            margin-bottom: -60px;
        }
        .banner__image{
                    background-image: url('<?php echo SYSTEM_IMAGE_DIR . "promocode-banner.jpeg" ?>');
        }



    }
      @media only screen and (min-width: 480px) and (max-width: 592px){
                  .back__container {
            padding: 0 2em;
        }

        .back__container-text {
            font-size: 1.4rem;
        }

        .book__now {
            padding: 8px;
            font-size: 1.4rem;
        }
      }

    @media only screen and (min-width: 768px) and (max-width: 834px) {
        
        .back__container {
            padding: 0 2em;
        }
        .container-p {
            margin-top: 100px;
        }

        .card-content-p {
            width: 60%;
        }

        .container-p {
            padding: 0;

        }


        .back__container,
        .signin__prompt {
            margin-bottom: -60px;
        }


    }


    
</style>

<body>
    <div class="logo__container" style="max-height:90px;" onload="stopBack();">
                   <a class="logo" style="mix-blend-mode:multiply;" href="<?php echo base_url() ?>">
                        <img width="300" class="tab_logo" src="<?php echo $GLOBALS['CI']->template->domain_images($GLOBALS['CI']->template->get_domain_logo()); ?>" alt="Logo" />
                        <!-- Added styles here for large screen logos -->
                        <img class="ful_logo" width="250" style="margin-top:-2em;margin-left:4.6em;" src="<?php echo $GLOBALS['CI']->template->domain_images($GLOBALS['CI']->template->get_domain_logo()); ?>" alt="Book a tour to India" />
                    </a>
                <div class="back_to_home">
            <a href="<?php echo base_url('/'); ?>" class="home-text">Back To Home</a>
        </div>



    </div>
    <div class="banner__image">
    </div>

    <?php if (is_logged_in_user() == false) {
        echo ('  <div id="sign__in-small" class="signin__prompt container sign__in-small"><p>
        Please  &nbsp;<a href="https://localhost/travelfreetravels/login">login</a> &nbsp;or &nbsp;<a href="https://localhost/travelfreetravels/register"> signup</a>&nbsp; to be able to use promocodes.</p>
    </div>');
        echo ('  <div class="signin__prompt sign__in-large"><p>
        Please  &nbsp;<a href="https://localhost/travelfreetravels/login">login</a> &nbsp;or &nbsp;<a href="https://localhost/travelfreetravels/register"> signup</a>&nbsp; to be able to use promocodes.</p>
    </div>');
    } else {
        echo ('<div class="back__container">
        <p class="back__container-text">Make the full use of promocodes</p>
        <a href="https://localhost/travelfreetravels/flights" class="book__now">Book Flights Now</a>
    </div>');
    } ?>
    <div class="login">

    </div>
    
    <div class="container">
    <div class="container-p">
        <?php $promo_codes = get_active_promocodes(); ?>
        <input type="hidden" id="counter" value="<?= count($promo_codes) ?>" readonly>
        <?php
        foreach ($promo_codes as $key => $promo_code) { ?>
            <div class="card-container-p">
                <div class="card-content-p">
                    <div class="card-header-p">
                        <div class="value-p">
                            <?php
                            if ($promo_code['value_type'] == 'percentage') {
                                echo $promo_code['value'] . '% OFF';
                            } else {
                                echo get_application_default_currency() . ' ' . $promo_code['value'] . ' OFF';
                            } ?>
                        </div>
                    </div>
                    <div class="promo__row">
                        <span class="promo__code" id=<?php echo "promo__code_$i" ?>><?= $promo_code['promo_code'] ?></span>
                        <span style="display:flex;flex-wrap:nowrap;" class="copy__button" id=<?php echo "copy__button_$i" ?>>COPY
                            CODE</span>
                    </div>
                    <div class="sector">
                        <?php  $fromCity = $promo_code['promo_for_city'] != "" ?   $promo_code['promo_for_city'] : "";
                            $toCity = $promo_code['promo_to_city'] != "" ? $promo_code['promo_to_city'] : "";
                                                                        if (($fromCity == "") || ($fromCity == "")) {
                                                                            if (($promo_code['for_country'] == 'NP' || $promo_code['for_country'] == 'IN') && ($promo_code['to_country'] == 'NP' || $promo_code['to_country'] == 'IN')) {
                                                                                $locationText = "Domestic Flights";
                                                                            } elseif (($promo_code['for_country'] == '' && $promo_code['for_city'] == '') && ($promo_code['to_country'] == '' && $promo_code['to_city'] == '')) {
                                                                                $locationText = "All Flights";
                                                                            } else {
                                                                                $locationText = "International Flights";
                                                                            }
                                                                        } else {
                                                                            $locationText = "$fromCity - $toCity";
                                                                        }?>

                        <?= $locationText ?>
                      
                    </div>
                    <div class="date">
                        Valid till : <date>
                            <?= $promo_code['expiry_date'] ?>
                        </date>
                    </div>
                    <div class="description-p-mobile">
                        <?= $promo_code['description'] ?>
                    </div>
                </div>
                 <div class="card-description-p">
                    <div class="description-p">
                        <?php echo $promo_code['description'] . '&nbsp;<br>This promo-code is eligible for use with a minimum purchase of NPR&nbsp' . $promo_code['minimum_amount'] . '.' ?>
                    </div>
                </div>
            </div>
            <?php
            $i++;
        }
        ?>
        <div class="below__container">
            <div id="instructions" class="instructions">
                <h2>How to Use?</h2>
                <ul>
                    <li>
                        Log In to Your Account
                    </li>
                    <li> Copy the promocode from the promocodes page which can be visited from link in the footer or by
                        clicking on the popup that appears at the beginning when you visit the site.</li>
                    <li>
                        Find your ideal flight by either going back to home by clicking on the logo or clicking on the
                        book now button that appears below the banner in the promocode page and entering the details in
                        the
                        search form and click the search button.
                    </li>
                    <li>
                        Initiate the booking by pressing the "Book Now" button.
                    </li>
                    <li>
                        You will then be prompted with a form to fill in your details where you can find a field to
                        enter
                        the
                        promocode.
                    </li>
                    <li>
                        After entering the promo code, click on the "Apply" button next to the promo code field.
                    </li>
                    <li>
                        Take a moment to review your booking details, including the applied discount. Ensure that
                        everything
                        is
                        accurate before proceeding.
                    </li>
                    <li>
                        Once you are satisfied with the details, proceed to the payment section to finalize your
                        booking.
                        Follow
                        the on-screen instructions to complete the transaction.
                    </li>
                    <li>
                        Congratulations! You have successfully applied the promo code and completed your flight booking
                        at a
                        discounted rate. Have a great trip!
                    </li>
                </ul>
            </div>
            <div id="promoterms" class="terms">
                <h2>Terms & Conditions</h2>
                <ul>
                    <li>
                        Promo codes may have specific eligibility criteria. Ensure compliance with tour package, flight location, and other requirements specified. Travel for Travels have right to change this any time as per the requirement.
                    </li>
                    <li>Unless explicitly stated otherwise, promo codes are generally for single-use per customer. Subsequent attempts to use the same code may be invalid.</li>
                    <li>
                        Certain promotions may have minimum booking requirements. Verify that your booking meets or exceeds the specified criteria for the promo code to be applicable.
                    </li>
                    <li>
                        Some promotions may exclude specific services, packages, or destinations. Check the exclusions list to confirm that your chosen product is eligible for the discount.
                    </li>
                    <li>
                        Promo codes may not be transferable, if transferable also it can be used only once and cannot be combined with other promotions unless explicitly stated otherwise.
                    </li>
                    <li>
                            Promotions are subject to availability, and limited inventory may apply. Use the promo code promptly to secure your booking.
                    </li>
                    <li>
                            Once promo code is used it cannot be modified and cancellation of booking will void the promocode. In case of refund, amount will be based on the actual amount paid by the customer.
                    </li>
                    <li>
                            The company reserves the right to modify or terminate the promo code offer at any time without prior notice.
                    </li>
                    <li>
                            Promo codes cannot be extended beyond their expiration date. Be mindful of the validity period and plan your bookings accordingly.
                    </li>
                    <li>
                            Any attempt to use promo codes fraudulently, including but not limited to multiple accounts or bookings, may result in the cancellation of the reservation and account suspension.
                    </li>
                    <li>
                                Some promo codes may be valid only for specific currencies or regions. Confirm that your booking complies with these restrictions.
                    </li>
                    <li>
                            If a booking made with a promo code is canceled, any applicable cancellation fees will be based on the original price before the discount.
                    </li>
                    <li>
                            For any inquiries or concerns regarding promo codes, contact our customer support at info@localhost/travelfreetravels
                    </li>
                </ul>
                <p>By using the promo code, you acknowledge and agree to comply with these terms and conditions. The company reserves the right to interpret, modify, or cancel these terms at its discretion.</p>
         
            </div>
                   
        </div>
        </div>
</body>

<script>
           const jsConfetti = new JSConfetti();
      jsConfetti.addConfetti();

    let cpnBtns = document.querySelectorAll(".copy__button");



    cpnBtns.forEach(function (cpnBtn) {

        let cpnCodeId = "promo__code_" + cpnBtn.id.split("_")[3];

        let cpnCode = document.getElementById(cpnCodeId);

        cpnBtn.onclick = function () {
            navigator.clipboard.writeText(cpnCode.innerHTML);
            cpnBtn.innerHTML = "COPIED";
            setTimeout(function () {
                cpnBtn.innerHTML = "COPY CODE";
            }, 3000);
        };
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        let popupContainer = document.getElementById("popup-container");

        popupContainer.style.display = "none";
        couponContainer.classList.remove("active");
    });
</script>