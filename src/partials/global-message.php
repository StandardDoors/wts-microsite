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

function wts_render_message_banner(string $lang): void
{
    $str_expiry_date = "2026-01-05"; #YYYY-MM-DD
    $str_expiry_date2 = "2026-01-12"; #YYYY-MM-DD
    $current_date = new DateTime();
    $expiry_date  = new DateTime($str_expiry_date);
    $expiry_date2  = new DateTime($str_expiry_date2);

    if ($current_date < $expiry_date) {
        if ($lang === 'bi') {
            wts_render_message_banner_new_lowe_plus('fr');
            wts_render_message_banner_new_lowe_plus('en');
        } else {
            wts_render_message_banner_new_lowe_plus($lang);
        }
    }

    if ($current_date > $expiry_date && $current_date < $expiry_date2) {
        if ($lang === 'bi') {
            wts_render_message_banner_winter_vacation('fr');
            wts_render_message_banner_winter_vacation('en');
        } else {
            wts_render_message_banner_winter_vacation($lang);
        }
    }
}

function wts_render_message_banner_new_lowe_plus(string $lang): void
{
    if ($lang === 'fr') {
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
        return;
    }

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
}

function wts_render_message_banner_winter_vacation(string $lang): void
{
    if ($lang === 'fr') {
        echo '<div class="centerimage">
                <h1 class="redtext">*** Vacances d\'hiver 2025-26 ***</h1> 
                <h3>Notez que les livraisons reprendront le 12 janvier 2026 selon notre horaire habituel.</h3>
                <h3>Toute l\'équipe STANDARD tient à vous remercier pour votre soutien continu et vous souhaite de Joyeuses Fêtes et une très belle année 2026! </h3>
            </div>';
        return;
    }

    echo '<div class="centerimage">
            <h1 class="redtext">*** Winter Vacation 2025-26 ***</h3> 
            <h2>Note that our regular shipping schedule will resume on January 12<sup>th</sup>, 2026.</h3>
            <h3>The entire team at STANDARD would like to thank you for your continued support and wish you Happy Holidays and all the best for 2026!</h3>
        </div>';
}
