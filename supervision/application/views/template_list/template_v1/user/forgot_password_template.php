<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Password Reset</title>

    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap"
      rel="stylesheet"
    />
  </head>
  <body
    style="
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background: #ffffff;
      font-size: 14px;
    "
  >
    <div
      style="
        max-width: 680px;
        margin: 0 auto;
        padding: 45px 30px 60px;
        background: #fff;
        background-repeat: no-repeat;
        background-size: 800px 452px;
        background-position: top center;
        font-size: 14px;
      "
    >
      <header>
        <table style="width: 100%;">
          <tbody>
            <tr style="height: 0;">
              <td>
                <img
                  alt="Travel Free Travels"
                 src="https://drive.google.com/uc?export=download&id=15gR5sTlyufGENXl48E6xNoTPKW1F57WN" alt="Travel Free Travels" border="0"
                  height="100px"
                />
              </td>
              <td style="text-align: right;">
                <span
                  style="font-size: 16px; line-height: 30px;"
                  ><?php echo date("Y/m/d");?></span
                >
              </td>
            </tr>
          </tbody>
        </table>
      </header>

      <main>
        <div
          style="
            margin: 0;
            margin-top: 70px;
            padding: 20px 92px;
            background: #ffffff;
            border-radius: 30px;
            text-align: center;
          "
        >
          <div style="width: 100%; max-width: 489px; margin: 0 auto;">
            <h1
              style="
                margin: 0;
                font-size: 24px;
                font-weight: 500;
                color: purple;
              "
            >
              Password Reset
            </h1>
            <p
              style="
                margin: 0;
                margin-top: 17px;
                font-size: 16px;
                font-weight: 500;
                color:black;
              "
            >
              Hey <?php echo $first_name;?>,
            </p>
            <p
              style="
                margin: 0;
                margin-top: 17px;
                font-weight: 500;
                letter-spacing: 0.56px;
              "
            >
              Your password has been successfully reset as per your request. Your new credentials are shown below.<strong>Please change your password as soon as you login.</strong>
            </p>
                      <p
              style="
                margin: 0;
                margin-top: 60px;
                font-size: 20px;
                font-weight: 600;
                color: purple;
              "
            >
              Username/Email: <?php echo $email;?>
            </p>
                      <p
              style="
                margin: 0;
                margin-top: 60px;
                font-size: 20px;
                font-weight: 600;
                color: purple;
              "
            >
              Password: <?php echo $password;?>
            </p>
            <a style="margin-top:3em;" href="travelfreetravels.com/agent">Click here to redirect back to the login page.</a>
 
        </div>

        <p
          style="
            max-width: 400px;
            margin: 0 auto;
            margin-top: 90px;
            text-align: center;
            font-weight: 500;
            color: #8c8c8c;
          "
        >
          Need help? Ask at
          <a
            href="mailto:info@travelfreetravels.com"
            style="color: #499fb6; text-decoration: none;"
            >info@travelfreetravels.com</a
          >
        </p>
        <p>If you didn't request for a password reset please contact us at<a
        href="tel:+9779860000111" 
        style="
        text-decoration:underline;
        color:black
        ">9860000111</a> immediately.</p> 
      </main>

      <footer
        style="
          width: 100%;
          max-width: 490px;
          margin: 20px auto 0;
          text-align: center;
          border-top: 1px solid #e6ebf1;
        "
      >
        <p
          style="
            margin: 0;
            margin-top: 40px;
            font-size: 16px;
            font-weight: 600;
            color: #434343;
          "
        >
          Travel Free Travels
        </p>
        <p style="margin: 0; margin-top: 8px; color: #434343;">
          <?php echo STATIC_ADDRESS?>
        </p>
        <p style="margin: 0; margin-top: 16px; color: #434343;">
          Copyright © 2024 Travel Free Travels. All rights reserved.
        </p>
      </footer>
    </div>
  </body>
</html>