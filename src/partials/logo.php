<?php
/**
 * Logo partial
 *
 * Renders a logo block based on language/site.
 *
 * @package WTS
 * @subpackage Logo
 * @since 1.0.0
 */

function wts_render_logo(string $lang, string $site = 'default'): void
{
    $href = 'https://standarddoors.com/';
    $logo = 'assets/logos/LogoStandardColourEN2024.png';

    if ($site === 'usa') {
        $href = 'https://usa.standarddoors.com/';
        $logo = 'assets/logos/LogoStandardColourEN2024.png';
    } elseif ($lang === 'fr') {
        $href = 'https://standarddoors.com/fr/';
        $logo = 'assets/logos/LogoStandardColourFR2024.png';
    } elseif ($lang === 'bi') {
        $logo = 'assets/logos/LogoStandardColourBi2024.png';
    }

    echo '<div class="centerimage" style="margin-bottom: 2rem;">
        <a href="' . $href . '" target="_blank"><img src="' . $logo . '"></a>
    </div>';
}
