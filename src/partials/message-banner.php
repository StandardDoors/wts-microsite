<?php

/**
 * Global message partial
 *
 * This partial is used to globally to display a message on all sites pages.
 *
 * @package WTS
 * @subpackage Global Message
 * @since 1.0.0
 */

require_once __DIR__ . '/../Helpers/DateHelper.php';

use WTS\Helpers\DateHelper;

// Banner expiry dates (YYYY-MM-DD format)
define('WTS_BANNER_EXPIRY_FULL', '2026-04-06');
define('WTS_BANNER_EXPIRY_SHORT', '2026-04-06');

function wts_render_message_banner(string $lang, bool $isDev): void
{
    $dateHelper = new DateHelper();

    // If dev site, then ignore dates
    if ($isDev === true) {
        echo wts_message_banner_full('fr');
        echo wts_message_banner_full('en');
        return;
    }

    // Show full banner before first expiry date
    if ($dateHelper->isBeforeDate(WTS_BANNER_EXPIRY_FULL)) {
        if ($lang === 'bi') {
            echo wts_message_banner_full('fr');
            echo wts_message_banner_full('en');
        } elseif ($lang !== 'bi') {
            echo wts_message_banner_full($lang);
        }
        return;
    }

    // Show short banner between first and second expiry dates
    if ($dateHelper->isBetweenDates(WTS_BANNER_EXPIRY_FULL, WTS_BANNER_EXPIRY_SHORT)) {
        if ($lang === 'bi') {
            echo wts_message_banner_short('fr');
            echo wts_message_banner_short('en');
        } else {
            echo wts_message_banner_short($lang);
        }
    }
}

function wts_message_banner_full(string $lang): string
{
    ob_start();
    if ($lang === 'fr') {
        ?>
        <div class="leftalign">
            <h1 class="redtext">*** Portes Standard sera fermé le lundi 18 mai ***</h1> 
            <h3>Veuillez noter que nous serons fermés le lundi 18 mai à l’occasion de la fête de la Reine.</h3>  
            <h3>Nous reprendrons nos heures d’ouverture habituelles le mardi 19 mai. Merci de votre compréhension.</h3>
        </div>
        <?php
    } else {
        ?>
        <div class="leftalign">
            <h1 class="redtext">*** Standard Doors Will be Closed on Monday, May 18th***</h1> 
            <h3>Please note that we will be closed on Monday, May 18<sup>th</sup>, in observance of Victoria Day.</h3>  
            <h3>We will resume regular business hours on Tuesday, May 19<sup>th</sup>. Thank you for your understanding.</h3>
        </div>
        <?php
    }

    return ob_get_clean();
}

function wts_message_banner_short(string $lang): string
{
    ob_start();
    if ($lang === 'fr') {
        ?>
        <!--
        <div class="centerimage">
            <h1 class="redtext">*** Vacances d'hiver 2025-26 ***</h1> 
            <h3>Notez que les livraisons reprendront le 12 janvier 2026 selon notre horaire habituel.</h3>
            <h3>Toute l'équipe STANDARD tient à vous remercier pour votre soutien continu et vous souhaite de Joyeuses Fêtes et une très belle année 2026! </h3>
        </div>
        -->
        <?php
    } else {
        ?>
        <!--
        <div class="centerimage">
            <h1 class="redtext">*** Upcoming Easter Closure ***</h1> 
            <h3>The entire team at STANDARD would like to thank you for your continued support and wish you Happy Holidays and all the best for 2026!</h3>
        </div>
        -->
        <?php
    }

    return ob_get_clean();
}
