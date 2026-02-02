<?php
$page = $_SERVER['PHP_SELF'];
$sec = "43200";
?><!DOCTYPE HTML>
<html>
    <head>
    	<meta http-equiv="refresh" content="<?php echo $sec?>;URL='<?php echo $page?>'">
    	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
 		 <title>WTS Home Tab FR</title>
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
            <a href="https://standarddoors.com/fr/" target="_blank"><img src="/img/logos/LogoStandardColourFR2024.png"></a>
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
                <h1 class="bluetext"> NOUVELLE CONFIGURATION LOWE PLUS :</h1>
                <h3>En raison de problèmes d\'approvisionnement de verre, nous devons mettre à jour la configuration de nos vitrages isolants LowE PLUS. La nouvelle configuration conforme à Energy Star, mais nous attendons que RNCan publie les résultats officiels. Nous prévoyons que notre logiciel sera mis à jour d’ici quelques semaines, afin que les valeurs RNCan apparaissent sur les soumissions et les commandes.</h3>
                <h3>Pour toute commande nécessitant Energy Star, nous en assurerons le suivi manuellement et émettrons les autocollants Energy Star dès que possible.</h3>
                <h3>Si vous avez des questions, veuillez communiquer avec votre représentant des ventes. Nous nous excusons des inconvénients occasionnés et vous remercions de votre compréhension.</h3>
                <br>
                <h1 class="redtext">*** Vacances d\'hiver 2025-26 ***</h1> 
                <h3>Veuillez prendre note que notre bureau et l’usine de production seront fermés pour la période des fêtes à partir du 19 décembre à midi jusqu\'au 4 janvier inclusivement.</h3> 
                <h3>Nous serons de retour dès lundi le 5 janvier!</h3>
                <h3>Notez que les livraisons reprendront le 12 janvier 2026 selon notre horaire habituel.</h3>
                <h3>Toute l\'équipe STANDARD tient à vous remercier pour votre soutien continu et vous souhaite de Joyeuses Fêtes et une très belle année 2026! </h3>
            </div>';
        if ($current_date > $expiry_date && $current_date < $expiry_date2)
            echo'<div class="centerimage">
                    <h1 class="redtext">*** Vacances d\'hiver 2025-26 ***</h1> 
                    <h3>Notez que les livraisons reprendront le 12 janvier 2026 selon notre horaire habituel.</h3>
                    <h3>Toute l\'équipe STANDARD tient à vous remercier pour votre soutien continu et vous souhaite de Joyeuses Fêtes et une très belle année 2026! </h3>
                </div>';
        ?>
    </body>
</html>