<?php
$page = $_SERVER['PHP_SELF'];
$sec = "43200";
?><!DOCTYPE HTML>
<html>
    <head>
    	<meta http-equiv="refresh" content="<?php echo $sec?>;URL='<?php echo $page?>'">
    	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
 		 <title>WTS RONA Home Tab EN</title>
 		<!-- goto https://www.textfixer.com/html/html-character-encoding.php to encode accents -->
                <style>
            #container, #left, #right{
                margin: 0px; 
                padding: 30px; 
                border: 0px;
            }

            #left{
                max-width: 100%; 
                float: left;
            }

            #right{
                max-width: 100%; 
                float: left;
            }

            .centerimage {
             text-align:center;
             display:block;
            }

            .leftalign {
             text-align:leftalign;
             display:block;
            }

            .redtext{
                color:red;
            }

            .bluetext{
                color:#00BFFF;
            }

            body {
             margin: 0;
             font-family: 'Helvetica', 'Arial', sans-serif;
            }

            table {
             font-family: arial, sans-serif;
             border-collapse: collapse;
             width: 90%;
            }

            td, th {
             border: 1px solid #dddddd;
             text-align: left;
             padding: 8px;
            }

            tr:nth-child(even) {
             background-color: #dddddd;
            }
        </style>
        <script>
         (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
          ga('create', 'UA-6137292-1', 'standardpatiodoors.com');
          ga('send', 'pageview');
        </script>
    </head>
    <body>
        <div class="centerimage">
            <a href="https://standarddoors.com/" target="_blank"><img src="/img/logos/LogoStandardColourEN2024.png"></a>
            <br>
            <br>
        </div>
        <?php
        session_start();
        $str_expiry_date = "2026-01-05"; #YYYY-MM-DD
        $str_expiry_date2 = "2026-01-12"; #YYYY-MM-DD
        $current_date = new DateTime();
        $expiry_date  = new DateTime($str_expiry_date);
        $expiry_date2  = new DateTime($str_expiry_date2);
        if ($current_date < $expiry_date)
            echo '<div class="leftalign">
                <h1 class="bluetext">NEW LOWE PLUS CONFIGURATION</h1>
                <h3>Due to glass supply issues, we must update our LowE PLUS IG configuration. While the new configuration passes Energy Star, we are waiting for NRCAN to publish the official results.  We expect our software to be revised within a few weeks, so the NRCAN values appear on quotations and orders.</h3>
                <h3>For any orders that require energy star, we will keep track of them manually and issue Energy Star Stickers as soon as possible.</h3> 
                <h3>If you have any questions, please contact your sales representative. We apologize for the inconvenience and appreciate your understanding. </h3>
                <br>
                <h1 class="redtext">*** Winter Vacation 2025-26 ***</h1> 
                <h3>Please note that our office and production facility will be closed for the holiday season from December 19<sup>th</sup> at noon until January 4<sup>th</sup> inclusively.</h3>  
                <h3>We will be back on Monday, January 5<sup>th</sup>.</h3>
                <h3>Note that our regular shipping schedule will resume on January 12<sup>th</sup>, 2026.</h3>
                <h3>The entire team at STANDARD would like to thank you for your continued support and wish you Happy Holidays and all the best for 2026!</h3>
            </div>';
        if ($current_date > $expiry_date && $current_date < $expiry_date2)
            echo'<div class="centerimage">
                    <h1 class="redtext">*** Winter Vacation 2025-26 ***</h3> 
                    <h2>Note that our regular shipping schedule will resume on January 12<sup>th</sup>, 2026.</h3>
                    <h3>The entire team at STANDARD would like to thank you for your continued support and wish you Happy Holidays and all the best for 2026!</h3>
                </div>';
        ?>
	</body>
</html>