<?php

$pageTitle = 'WTS Home Tab EN';
$lang = 'en';
$site = 'usa';
include 'partials/header.php';

include_once 'partials/logo.php';
wts_render_logo($lang, $site);

include_once 'partials/message-banner.php';
wts_render_message_banner($lang);

include 'partials/footer.php';
