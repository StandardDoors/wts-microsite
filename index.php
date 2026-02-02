<?php
$page = $_SERVER['PHP_SELF'];
$sec = "43200";
?>
<html>
    <head>
        <meta http-equiv="refresh" content="<?php echo $sec?>;URL='<?php echo $page?>'">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
         <title>WTS Home Tab Bilingual</title>
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

            .redtext{
                color:red;
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
            <a href="http://www.standarddoors.com" target="_blank"><img src="img/logos/LogoStandardColourBi2024.png"></a>
            <br>
            <br>
        </div>
    </body>
</html>