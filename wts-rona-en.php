<?php
$pageTitle = 'WTS RONA Home Tab EN';
include 'partials/header.php';
?>
        <div class="centerimage">
            <a href="https://standarddoors.com/" target="_blank"><img src="assets/logos/LogoStandardColourEN2024.png"></a>
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
<?php include 'partials/footer.php'; ?>