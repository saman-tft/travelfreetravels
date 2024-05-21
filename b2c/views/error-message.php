<?php 
$___favicon_ico = $GLOBALS ['CI']->template->domain_images('favicon/favicon.ico');?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="<?= $___favicon_ico ?>" type="image/x-icon" />
    <title>Error</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: linear-gradient(120deg, purple, #8f53a1, #0ba0dc);
            background-size: cover;
            background-repeat: no-repeat;
        }

        .main__article {
            display: grid;
            place-content: center;
            place-items: center;
            min-height: 100vh;
        }
        .home-text {
            position: fixed;
            top: 20px;
            right: 40px;
            text-align: center;
            background-color: transparent;
            border: 1px solid purple;
            outline: none;
            text-decoration: none;
            box-shadow: 5px 5px 5px purple;
            color: white;
            padding: 10px 50px;
            font-size: 18px;
        }

        .main__article-section {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            max-width: 80%;
            min-width: 30em;
            padding: 20px;
            background: purple;
            border: 10px solid;
            border-color: #0ba0dc #8f53a1 #8f53a1 #0ba0dc;
            box-shadow: 8px 5px 10px;
        }

        .image__container {
            text-align: center;
        }

        .title {
            text-align: center;
            font-family: monospace;
            color: white;
            font-weight: 800;
            font-size: 3em;
            margin-bottom: 0.5em;
        }

        @media (max-width: 306px) {
            .main__article-section {
                max-width: auto;
                min-width: auto;
            }
            .home-text{
                font-size:14px;
                padding:10px 20px;
            }

            .title {
                font-size: 2em;

            }

            .image__container img {
                max-width: 100px;
            }
        }

        @media only screen and (min-width:306px) and (max-width:768px) {
            .main__article-section {
                max-width: 90%;
                min-width: auto;
            }

            .title {
                font-size: 2em;

            }
        }
    </style>

</head>

<body>
            <header>
        <a href="<?php echo base_url('/');?>" class="home-text">Back To Home</a>
    </header>
    <main>
        <article class="main__article">
            <section class="main__article-section">
                <div class="image__container">
                    <a href="<?php echo base_url('/'); ?>"><img src="<?php echo base_url('/images/companylogo.png')?>" alt="Travel Free Travels" width="270" /></a>
                </div>

                <div class="tick__mark">
                    <img src="data:image/png;base64,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">
                </div>
                <div class="title">
                    <h1><?php echo $data;?></h1>
                </div>
                              <a href="<?php echo base_url('/');?>" class="home-text-small">Back To Home</a>  

            </section>
        </article>

    </main>
</body>

</html>