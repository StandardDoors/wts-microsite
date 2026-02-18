<?php
$page = $_SERVER['REQUEST_URI'];
$sec = 43200;
?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="refresh" content="<?php echo $sec?>;URL='<?php echo $page?>'">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <title><?php echo $pageTitle ?? 'WTS Home Tab'; ?></title>
        <!-- goto https://www.textfixer.com/html/html-character-encoding.php to encode accents -->
        <link rel="stylesheet" href="assets/style.css">
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
