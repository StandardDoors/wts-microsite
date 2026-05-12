<?php

/**
 * Global message partial
 *
 * This partial is used globally to display messages on all site pages.
 *
 * @package WTS
 * @subpackage Global Message
 * @since 1.0.0
 */

require_once __DIR__ . '/../Helpers/DateHelper.php';

use WTS\Helpers\DateHelper;

/**
 * Dev mode setting.
 *
 * false = normal logic: only enabled banners within their start/end dates are shown.
 * true  = dev logic: all enabled banners are shown, regardless of start/end dates.
 */
define('WTS_BANNER_DEV_MODE', true);

/**
 * Banner messages.
 *
 * Date logic:
 * - start: first day the message can appear
 * - end: first day the message no longer appears
 *
 * Example:
 * start = 2026-05-01
 * end   = 2026-05-19
 *
 * This means the message shows from May 1 through May 18.
 */
function wts_get_banner_messages(): array
{
    return [
        [
            'enabled' => false,
            'start'   => '2026-05-11',
            'end'     => '2026-05-12',

            'fr' => [
                'class'       => 'banner-urgent',
                'title_class' => '',
                'title'       => '*** Avis important ***',
                'lines'       => [
                    'Veuillez noter que le réseau sera interrompu aujourd’hui à 16 h 15 pour une durée approximative de 15 à 30 minutes afin d’effectuer des réparations d’urgence.',
                    'Pendant cette période, tous les appels seront interrompus et les courriels seront temporairement suspendus.',
                    'Les opérations normales devraient reprendre peu après l’interruption.',
                    'Merci de votre compréhension.',
                ],
            ],

            'en' => [
                'class'       => 'banner-urgent',
                'title_class' => '',
                'title'       => '*** Important Notice ***',
                'lines'       => [
                    'Please note that the network will go down today at 4:15 PM for approximately 15–30 minutes to conduct emergency repairs.',
                    'During this time, all calls will be dropped and emails will be temporarily paused.',
                    'Normal operations are expected to resume shortly after the interruption.',
                    'Thank you for your understanding.',
                ],
            ],
        ],

        [
            'enabled' => true,
            'start'   => '2026-05-01',
            'end'     => '2026-05-19',

            'fr' => [
                'class'       => 'banner-warning',
                'title_class' => '',
                'title'       => '*** Portes Standard sera fermé le lundi 18 mai ***',
                'lines'       => [
                    'Veuillez noter que nous serons fermés le lundi 18 mai à l’occasion de la fête de la Reine.',
                    'Nous reprendrons nos heures d’ouverture habituelles le mardi 19 mai. Merci de votre compréhension.',
                ],
            ],

            'en' => [
                'class'       => 'banner-warning',
                'title_class' => '',
                'title'       => '*** Standard Doors Will be Closed on Monday, May 18th ***',
                'lines'       => [
                    'Please note that we will be closed on Monday, May 18<sup>th</sup>, in observance of Victoria Day.',
                    'We will resume regular business hours on Tuesday, May 19<sup>th</sup>. Thank you for your understanding.',
                ],
            ],
        ],

        [
            'enabled' => true,
            'start'   => '2026-06-10',
            'end'     => '2026-06-25',

            'fr' => [
                'class'       => 'banner-warning',
                'title_class' => '',
                'title'       => '*** Portes Standard sera fermé mercredi le 24 juin ***',
                'lines'       => [
                    'Veuillez noter que nous serons fermés mercredi le 24 juin à l’occasion de la fête national du Quebec.',
                    'Nous reprendrons nos heures d’ouverture habituelles jeudi le 25 juin. Merci de votre compréhension.',
                ],
            ],

            'en' => [
                'class'       => 'banner-warning',
                'title_class' => '',
                'title'       => '*** Standard Doors Will be Closed on Wednesday, June 24<sup>th</sup> ***',
                'lines'       => [
                    'Please note that we will be closed on Wednesday, June 24<sup>th</sup>, in observance of Quebec National Day.',
                    'We will resume regular business hours on Thursday, June 25<sup>th</sup>. Thank you for your understanding.',
                ],
            ],
        ],

        [
            'enabled' => true,
            'start'   => '2026-06-17',
            'end'     => '2026-07-02',

            'fr' => [
                'class'       => 'banner-warning',
                'title_class' => '',
                'title'       => '*** Portes Standard sera fermé mercredi le 1<sup>er</sup> juillet ***',
                'lines'       => [
                    'Veuillez noter que nous serons fermés mercredi le 1<sup>er</sup> juillet à l’occasion de la fête du Canada.',
                    'Nous reprendrons nos heures d’ouverture habituelles jeudi le 2 juillet. Merci de votre compréhension.',
                ],
            ],

            'en' => [
                'class'       => 'banner-warning',
                'title_class' => '',
                'title'       => '*** Standard Doors Will be Closed on Wednesday, July 1<sup>st</sup> ***',
                'lines'       => [
                    'Please note that we will be closed on Wednesday, July 1<sup>st</sup>, in observance of Canada Day.',
                    'We will resume regular business hours on Thursday, July 2<sup>nd</sup>. Thank you for your understanding.',
                ],
            ],
        ],

        [
            'enabled' => true,
            'start'   => '2026-08-24',
            'end'     => '2026-09-08',

            'fr' => [
                'class'       => 'banner-warning',
                'title_class' => '',
                'title'       => '*** Portes Standard sera fermé lundi le 7 septembre ***',
                'lines'       => [
                    'Veuillez noter que nous serons fermés lundi le 7 septembre à l’occasion de la fête du travail.',
                    'Nous reprendrons nos heures d’ouverture habituelles mardi le 8 septembre. Merci de votre compréhension.',
                ],
            ],

            'en' => [
                'class'       => 'banner-warning',
                'title_class' => '',
                'title'       => '*** Standard Doors Will be Closed on Monday, September 7<sup>th</sup> ***',
                'lines'       => [
                    'Please note that we will be closed on Monday, September 7<sup>th</sup>, in observance of Labour Day.',
                    'We will resume regular business hours on Tuesday, September 8<sup>th</sup>. Thank you for your understanding.',
                ],
            ],
        ],

        [
            'enabled' => true,
            'start'   => '2026-09-28',
            'end'     => '2026-10-13',

            'fr' => [
                'class'       => 'banner-warning',
                'title_class' => '',
                'title'       => '*** Portes Standard sera fermé lundi le 12 octobre ***',
                'lines'       => [
                    'Veuillez noter que nous serons fermés lundi le 12 octobre à l’occasion de l’Action de grâce.',
                    'Nous reprendrons nos heures d’ouverture habituelles mardi le 13 octobre. Merci de votre compréhension.',
                ],
            ],

            'en' => [
                'class'       => 'banner-warning',
                'title_class' => '',
                'title'       => '*** Standard Doors Will be Closed on Monday, October 12<sup>th</sup> ***',
                'lines'       => [
                    'Please note that we will be closed on Monday, October 12<sup>th</sup>, in observance of Thanksgiving Day.',
                    'We will resume regular business hours on Tuesday, October 13<sup>th</sup>. Thank you for your understanding.',
                ],
            ],
        ],

        [
            'enabled' => false,
            'start'   => '2026-12-15',
            'end'     => '2026-12-24',

            'fr' => [
                'class'       => 'banner-warning',
                'title_class' => '',
                'title'       => '*** Vacances d’hiver 2026 ***',
                'lines'       => [
                    'Veuillez noter que nos bureaux seront fermés pour la période des Fêtes.',
                    'Toute l’équipe STANDARD vous remercie pour votre soutien continu et vous souhaite de Joyeuses Fêtes!',
                ],
            ],

            'en' => [
                'class'       => 'banner-warning',
                'title_class' => '',
                'title'       => '*** Winter Holidays 2026 ***',
                'lines'       => [
                    'Please note that our offices will be closed for the holiday season.',
                    'The entire team at STANDARD thanks you for your continued support and wishes you Happy Holidays!',
                ],
            ],
        ],
    ];
}

/**
 * Render active banner messages.
 *
 * @param string $lang Supported values: 'fr', 'en', or 'bi'.
 * @param bool|null $devModeOverride Optional override.
 *                                   true  = show all enabled banners regardless of dates.
 *                                   false = use normal date logic.
 *                                   null  = use WTS_BANNER_DEV_MODE.
 */
function wts_render_message_banner(string $lang, ?bool $devModeOverride = null): void
{
    $devMode = $devModeOverride ?? WTS_BANNER_DEV_MODE;

    $dateHelper = new DateHelper();
    $messages = wts_get_banner_messages();

    foreach ($messages as $message) {
        if (($message['enabled'] ?? false) !== true) {
            continue;
        }

        /**
         * Dev mode:
         * - true: show all enabled messages regardless of date
         * - false: show only messages within their date range
         */
        if (!$devMode && !wts_banner_is_active($dateHelper, $message)) {
            continue;
        }

        if ($lang === 'bi') {
            echo wts_render_single_banner_message($message, 'fr');
            echo wts_render_single_banner_message($message, 'en');
        } else {
            echo wts_render_single_banner_message($message, $lang);
        }
    }
}

/**
 * Check whether a banner message is active today.
 */
function wts_banner_is_active(DateHelper $dateHelper, array $message): bool
{
    $start = $message['start'] ?? null;
    $end = $message['end'] ?? null;

    if (!is_string($start) || !is_string($end)) {
        return false;
    }

    return $dateHelper->isBetweenDates($start, $end);
}

/**
 * Render one banner message in one language.
 */
function wts_render_single_banner_message(array $message, string $lang): string
{
    if (!isset($message[$lang]) || !is_array($message[$lang])) {
        return '';
    }

    $content = $message[$lang];

    $class = isset($content['class']) && is_string($content['class'])
        ? $content['class']
        : 'leftalign';

    $titleClass = isset($content['title_class']) && is_string($content['title_class'])
        ? $content['title_class']
        : '';

    $title = isset($content['title']) && is_string($content['title'])
        ? $content['title']
        : '';

    $lines = isset($content['lines']) && is_array($content['lines'])
        ? $content['lines']
        : [];

    ob_start();
    ?>
    <div class="<?php echo htmlspecialchars($class, ENT_QUOTES, 'UTF-8'); ?>">
        <?php if ($title !== '') : ?>
            <h1<?php echo $titleClass !== '' ? ' class="' . htmlspecialchars($titleClass, ENT_QUOTES, 'UTF-8') . '"' : ''; ?>>
                <?php echo wts_banner_allowed_html($title); ?>
            </h1>
        <?php endif; ?>

        <?php foreach ($lines as $line) : ?>
            <?php if (is_string($line)) : ?>
                <h3><?php echo wts_banner_allowed_html($line); ?></h3>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
    <?php

    return ob_get_clean();
}

/**
 * Allow a limited set of safe HTML tags inside banner messages.
 */
function wts_banner_allowed_html(string $html): string
{
    return strip_tags($html, '<sup><br><strong><b><em><i>');
}
